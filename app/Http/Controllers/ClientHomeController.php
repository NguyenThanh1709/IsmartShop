<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Color;
use App\Models\Config;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductCat;
use App\Models\RegisterNoti;
use App\Models\Post;
use App\Models\Slider;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class ClientHomeController extends Controller
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
    }


    public function show()
    {
        $sliders = Slider::where('status', 'public')->get();
        $banners = Banner::where('status', 'public')->get();
        $productCat = ProductCat::where('status', 'public')->get();
        $featuredProducts = Warehouse::where('represent', 4)->get();
        // $productASC = OrderDetail::select('*')->groupBy('product_id', 'warehouse_id')->orderBy(DB::raw('SUM(quantity)'), 'DESC')->limit(10)->get();
        $productASC = OrderDetail::select('product_id', 'warehouse_id', 'name', 'thumbnail', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id', 'warehouse_id', 'name', 'thumbnail')
            ->orderBy(DB::raw('SUM(quantity)'), 'DESC')
            ->take(6)
            ->get();
        //Get prouduct represent
        $productPhone = Product::where('name', 'LIKE', '%Điện thoại%')->get();
        foreach ($productPhone as $item) {
            $product_id[] = $item->id;
        }
        $phoneProducts = Warehouse::wherein('product_id', $product_id)->where('represent', 1)->get();
        $productLaptop = Product::where('name', 'LIKE', '%Laptop%')->get();
        foreach ($productLaptop as $item) {
            $product_id_laptop[] = $item->id;
        }
        $laptopProduct = Warehouse::wherein('product_id', $product_id_laptop)->get();
        $saleProduct = Warehouse::where('discount', '>', 0)->where('represent', 1)->orderBy('discount', 'DESC')->get();
        $menu = render_menu($productCat, 0, 0);
        $blogs = Post::where('status', 'public')
                ->orderBy('created_at', 'desc') 
                ->take(5) 
                ->get(); 
        return view('clients.home.home', compact('blogs','featuredProducts', 'sliders', 'banners', 'phoneProducts', 'laptopProduct', 'saleProduct', 'menu', 'productASC'));
    }

    public function register(Request $request)
    {
        $email = $request->input('email');
        if (empty($email)) {
            return json_encode('Null');
        } else {
            $emailExists = RegisterNoti::where('email', $email)->exists();
            if ($emailExists) {
                return json_encode('Exists');
            } else {
                RegisterNoti::create([
                    'email' => $email,
                ]);
                return json_encode('Successfully');
            }
        }
    }
}
