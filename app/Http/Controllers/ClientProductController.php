<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCat;
use App\Models\Warehouse;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientProductController extends Controller
{
    //
    public function __construct()
    {
        function has_child($data_src, $cat_id)
        {
            foreach ($data_src as $item) {
                if ($item['parent_id'] == $cat_id) {
                    return true;
                }
            }
            return false;
        }

        function render_menu($data_src, $parent_id = 0, $level = 0)
        {
            if ($level == 0) {
                $result = "<ul class='list-item' id='main-menu-respon'>";
            } else {
                $result = "<ul class='sub-menu'>";
            }
            foreach ($data_src as $item) {
                $url = route('product.searchCat', ['slug' => $item['slug']]);
                if ($item['parent_id'] == $parent_id) {
                    $result .= "<li>";
                    $result .= "<a href='$url' title=''>{$item['name']}</a>";
                    if (has_child($data_src, $item['id'])) {
                        $result .= render_menu($data_src, $item['id'], $level + 1);
                    }
                    $result .= "</li>";
                }
            }
            $result .= "</ul>";
            return $result;
        }

        function data_tree_cat($data, $parent_id = 0)
        {
            $list = array();
            foreach ($data as $item) {
                if ($item['parent_id'] == $parent_id) {
                    $list[] = $item['id'];
                    $child = data_tree_cat($data, $item['id']);
                    $list = array_merge($list, $child);
                }
            }
            return $list;
        }
    }

    public function show(Request $request)
    {
         $listProduct = Warehouse::join('products', 'products.id', '=', 'warehouses.product_id')
                    ->where('warehouses.represent', 1)
                    ->where('warehouses.quantity', '>', 0)
                    ->orderBy('products.name', 'ASC')
                    ->select('warehouses.*') 
                    ->paginate(25);
        $productCat = ProductCat::where('status', 'public')->get();
        $banners = Banner::where('status', 'public')->get();
        $menu = render_menu($productCat, 0, 0);
        return view('clients.product.listProduct', compact('listProduct', 'menu', 'banners'));
    }

    public function searchCat($slug)
    {
         //Get list all ProductCat
        $listProductCat = ProductCat::all();
        $getNameCat = ProductCat::select('name', 'id')->where('slug', $slug)->first();
        if ($getNameCat) {
            $id = $getNameCat->id;
            $list_cat_id = array_merge(array(0 => $id), data_tree_cat($listProductCat, $id));
            $listProduct = Warehouse::join('products', 'warehouses.product_id', '=', 'products.id')
                ->join('product_cats', 'products.product_cat_id', '=', 'product_cats.id')
                ->select('warehouses.*', 'products.*', 'product_cats.name as category_name')
                ->whereIn('products.product_cat_id', $list_cat_id)
                ->where('represent', 1)
                ->paginate(25);
            $menu = render_menu($listProductCat, 0, 0);
            return view('clients.product.searchCatName', compact('listProduct', 'menu', 'getNameCat'));
        }
    }

    public function detail($slug)
    {
       $product = Product::where('slug', $slug)->first();
        if ($product) {
            $id = $product->id;
            $warehouse = Warehouse::join('configs', 'configs.id', '=', 'warehouses.config_id')
                ->where('warehouses.product_id', $id)
                ->where('warehouses.quantity', '>', 0)
                ->orderBy('configs.storage_capacity', 'ASC')
                ->select('warehouses.*') // Chọn tất cả các trường từ bảng warehouses
                ->first();

            if ($warehouse == null) {
                $warehouse = Warehouse::where('product_id', $id)
                ->first();
            }

            $sameItem = Warehouse::join('products', 'warehouses.product_id', '=', 'products.id')
                ->select('warehouses.*', 'products.*')
                ->where('products.product_cat_id', $product->product_cat_id)->where('represent', 1)->get();
            // return dd($sameItem);
            $banners = Banner::where('status', 'public')->get();
            $images = $product->images;
            $productCat = ProductCat::where('status', 'public')->get();
            $menu = render_menu($productCat, 0, 0);
            $listImages = explode(',', str_replace(['[', ']', '"'], '', $images));
            $listColor = Warehouse::select('color_id')->distinct()->where('product_id', $id)->get();
            return view('clients.product.detailProduct', compact('warehouse', 'listImages', 'listColor', 'menu', 'banners', 'sameItem'));
        }
    }

    public function arrangeAjax(Request $request)
    {
        $str = "";
        $arrange = $request->input('arrange');
        $max = $request->input('max') ?? 100000000;
        $min = $request->input('min') ?? 0;
        $title = $request->input('title');

        if (!empty($title)) {
            $query = Warehouse::join('products', 'warehouses.product_id', '=', 'products.id')
                ->select('warehouses.*', 'products.name')
                ->where('represent', 1)
                ->where('price', '>=', $min)
                ->where('price', '<=', $max)
                ->where('products.name', "LIKE", "%$title%");

            switch ($arrange) {
                case 1:
                    $query->orderBy('products.name');
                    break;
                case 2:
                    $query->orderBy('products.name', 'DESC');
                    break;
                case 3:
                    $query->orderBy('price', 'DESC');
                    break;
                case 4:
                    $query->orderBy('price');
                    break;
                default:
            }
        } else {
            $query = Warehouse::join('products', 'warehouses.product_id', '=', 'products.id')
                ->select('warehouses.*', 'products.name')
                ->where('represent', 1)
                ->where('price', '>=', $min)
                ->where('price', '<=', $max);

            switch ($arrange) {
                case 1:
                    $query->orderBy('products.name');
                    break;
                case 2:
                    $query->orderBy('products.name', 'DESC');
                    break;
                case 3:
                    $query->orderBy('price', 'DESC');
                    break;
                case 4:
                    $query->orderBy('price');
                    break;
                default:
            }
           
        }

        $query = $query->paginate(25);
        
        foreach ($query as $item) {
            $images = asset($item->Product->thumbnail);
            $name = $item->Product->name;
            $id = $item->product_id;
            $price = number_format($item->price, 0, '', ',') . 'đ';
            $url = route('product.detail', ['slug' => $item->Product->slug]);
            $str .=
                "<li>
                    <a href='$url' title='' class='thumb'>
                        <img class='img_item_product' src='$images'>
                    </a>
                    <a href='$url' title='' class='product-name'>$name</a>
                    <div class='price'>
                        <span class='new'>$price</span>
                    </div>
                    <div class='action clearfix btn-detail pb-2'>
                        <a href='$url' title='' class='p-1 rounded text-center'>Xem chi tiết <i class='fa-solid fa-eye'></i></a>
                    </div>
                </li>";
        }

        $paging = $query->appends(['sort' => $arrange])->links()->render();

        $data = array(
            'paging' => $paging,
            'str' => $str
        );

        return json_encode($data, 200);
    }

    public function getData(Request $request)
    {
        $page = $request->input('page');
        $sort = $request->input('sort');
        $max = $request->input('max') ?? 100000000;
        $min = $request->input('min') ?? 0;
        $title = $request->input('title');
        $str = "";

        if (!empty($title)) {
            $query = Warehouse::join('products', 'warehouses.product_id', '=', 'products.id')
                ->select('warehouses.*', 'products.name')
                ->where('represent', 1)
                ->where('price', '>=', $min)
                ->where('price', '<=', $max)
                ->where('products.name', "LIKE", "%$title%");

            switch ($sort) {
                case 1:
                    $query->orderBy('products.name');
                    break;
                case 2:
                    $query->orderBy('products.name', 'DESC');
                    break;
                case 3:
                    $query->orderBy('price', 'DESC');
                    break;
                case 4:
                    $query->orderBy('price');
                    break;
                default:
                    $query->orderBy('products.name');
                    break;
            }

        } else {
            $query = Warehouse::join('products', 'warehouses.product_id', '=', 'products.id')
                ->select('warehouses.*', 'products.name')
                ->where('represent', 1)
                ->where('price', '>=', $min)
                ->where('price', '<=', $max);

            switch ($sort) {
                case 1:
                    $query->orderBy('products.name');
                    break;
                case 2:
                    $query->orderBy('products.name', 'DESC');
                    break;
                case 3:
                    $query->orderBy('price', 'DESC');
                    break;
                case 4:
                    $query->orderBy('price');
                    break;
                default:
                    $query->orderBy('products.name');

            }
            
        }

        $query = $query->paginate(25, ['*'], 'page', $page);
        
        foreach ($query as $item) {
            $images = asset($item->Product->thumbnail);
            $name = $item->Product->name;
            $id = $item->product_id;
            $price = number_format($item->price, 0, '', ',') . 'đ';
            $url = route('product.detail', ['slug' => $item->Product->slug]);
            $str .=
                "<li>
                        <a href='$url' title='' class='thumb'>
                            <img class='img_item_product' src='$images'>
                        </a>
                        <a href='$url' title='' class='product-name'>$name</a>
                        <div class='price'>
                            <span class='new'>$price</span>
                        </div>
                        <div class='action clearfix btn-detail pb-2'>
                            <a href='$url' title='' class='p-1 rounded text-center'>Xem chi tiết <i class='fa-solid fa-eye'></i></a>
                        </div>
                    </li>";
        }
        return json_encode($str, 200);
    }

    public function getDataColor(Request $request)
    {
        $str = "";
        $status = "Hết hàng";
        $btnCart = "<button class='btn btn-dark'>Tạm hết hàng</button>";
        $url = route('getDataPrice');
        $dataUrl = route('addCart');
        $dataUrlCart = route('cart');
        $listConfig = Warehouse::join('configs', 'configs.id', '=', 'warehouses.config_id')
            ->where('warehouses.color_id', $request->input('color'))
            ->where('warehouses.product_id', $request->input('id_product'))
            ->orderBy('configs.name', 'ASC')
            ->get();
        $temp = 0;
        foreach ($listConfig as $key => $item) {
            $name = $item->Config->name;
            $config = $item->config_id;
            
            if ($item->quantity > 0) {
                $qty = $item->quantity;
                $activeClass = ($temp === 0) ? 'active' : ''; // Kiểm tra phần tử đầu tiên và active
                $str .= 
                "<a href='' data-url='$url' storage_capacity='$config' class='btn border btn-config $activeClass'>$name</a>";
                $btnCart = "<a href='' data-url='$dataUrl' data-id='$item->product_id' title='Thêm giỏ hàng' 
                class='add-cart' data-url-cart=$dataUrlCart>Thêm giỏ hàng</a>";
                 $status = "Còn hàng (Số lượng còn: $qty)";  
                $temp ++;    
            }
        };
        $data = array(
            'str' => $str,
            'status' => $status,
            'btnCart' => $btnCart,
            'qty' => $qty ?? 0
        );
        return json_encode($data, 200);
    }

    public function getDataPrice(Request $request)
    {
        $str = "";
        $prices = Warehouse::select('price', 'sale_off', 'discount', 'quantity')
            ->where('color_id', $request->input('color'))
            ->where('product_id', $request->input('id_product'))
            ->where('config_id', $request->input('config_id'))
            ->get();
        foreach ($prices as $item) {
            $qty = $item->quantity;
            $status = "Còn hàng (Số lượng còn: $qty)";
            if ($item->discount > 0) {
                $price_new = number_format($item->sale_off, 0, '', ',') . 'đ';
                $price_old = number_format($item->price, 0, '', ',') . 'đ';
                $str .= "<span class='price new'>$price_new</span>
                <span class='price old pl-4'>$price_old</span>";
            } else {
                $price = number_format($item->price, 0, '', ',') . 'đ';
                $str .= "<span class='price new'>$price</span>";
            }
        };
        $data = array(
            'str' => $str,
            'qty' => $qty,
            'status' => $status
        );
        return json_encode($data, 200);
    }

    public function searchHeader(Request $request)
    {
        $key = $request->input('key');
        $listProduct = Warehouse::join('products', 'warehouses.product_id', '=', 'products.id')
            ->select('warehouses.*', 'products.name')
            ->where('products.name', 'LIKE', "%{$key}%")
            ->where('represent', 1)
            ->paginate(20);
        $productCat = ProductCat::where('status', 'public')->get();
        $menu = render_menu($productCat, 0, 0);
        return view('clients.product.searchHeader', compact('listProduct', 'menu'));
    }

    public function searchAjax(Request $request)
    {
        if ($request->ajax()) {
            $key = $request->input('key');
            $str = "";
            $listProduct = Warehouse::join('products', 'warehouses.product_id', '=', 'products.id')
                ->select('warehouses.*', 'products.name')
                ->where('products.name', 'LIKE', "%{$key}%")
                ->where('represent', 1)
                ->paginate(20);
            foreach ($listProduct as $item) {
                $images = asset($item->Product->thumbnail) ?? '';
                $url = route('product.detail', ['slug' => $item->Product->slug, 'id' => $item->product->id]);
                $name = $item->name ?? '';
                $str .= "<li>
                            <a href='$url'>
                                <img src='$images' alt='Ảnh sản phẩm'>
                                <p>$name</p>
                            </a>
                        </li>";
            }
        }
        return json_encode($str, 200);
    }
}
