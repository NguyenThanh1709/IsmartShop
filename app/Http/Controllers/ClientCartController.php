<?php

namespace App\Http\Controllers;

use App\Models\ProductCat;
use App\Models\Warehouse;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class ClientCartController extends Controller
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
                $url = route('product.searchCat', ['slug' => $item['slug'], 'id' => $item['id']]);
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

    public function listCart()
    {
        $productCat = ProductCat::where('status', 'public')->get();
        $menu = render_menu($productCat, 0, 0);
        return view('clients.cart.cart', compact('menu'));
    }

    public function updateCart(Request $request)
    {
        $qty = $request->input('qty');
        $id = $request->input('id');
        $update = Cart::update($id, $qty);
        $subtotal = number_format($update->total, 0, '', ',') . 'đ';
        $total = Cart::total(0, '', ',') . 'đ';
        $temp = Cart::count();
        return json_encode(['subtotal' => $subtotal, 'total' => $total, 'allnumber' => $temp], 200);
    }

    public function addProductToCart(Request $request)
    {
        $color_id = $request->input('color_id') ?? 0;
        $product_id = $request->input('product_id') ?? 0;
        $config_id = $request->input('config_id') ?? 0;
        $qty = $request->input('number_order');

        if ($color_id > 0) {
            $infoProduct = Warehouse::join('products', 'warehouses.product_id', '=', 'products.id')
                ->join('colors', 'warehouses.color_id', '=', 'colors.id')
                ->join('configs', 'warehouses.config_id', '=', 'configs.id')
                ->select('warehouses.*', 'products.name as product_name', 'products.thumbnail as product_thumbnail', 'colors.name as color_name', 'configs.name as config_name')
                ->where('color_id', $color_id)
                ->where('product_id', $product_id)
                ->where('config_id', $config_id)
                ->get();

            $color_name = $infoProduct->first()->color_name;
            $config_name = $infoProduct->first()->config_name;
        }

        if ($color_id == 0) {
            $infoProduct = Warehouse::join('products', 'warehouses.product_id', '=', 'products.id')
                ->select('warehouses.*', 'products.name as product_name', 'products.thumbnail as product_thumbnail')
                ->where('product_id', $product_id)
                ->get();
        }
        $product_name = $infoProduct->first()->product_name;
        $warehouse_id = $infoProduct->first()->id;
        $thumbnail = $infoProduct->first()->product_thumbnail;
        if ($infoProduct->first()->discount > 0) {
            $productPrice = number_format($infoProduct->first()->sale_off, 0, '', ',') . 'đ';
            $productPrice = $infoProduct->first()->sale_off;
        } else if ($infoProduct->first()->discount == 0) {
            $productPrice = $infoProduct->first()->price;
        }
        Cart::add([
            'id' => $product_id,
            'name' => $product_name,
            'qty' => $qty,
            'price' =>  $productPrice,
            'options' => ['color' =>  $color_name ?? '', 'config' => $config_name ?? '', 'thumbnail' => $thumbnail, 'warehouse_id' => $warehouse_id]
        ]);

        $str = "";
        // $temp = 0;
        foreach (Cart::content() as $item) {
            // $temp ++;
            $name = $item->name . '/' . $item->options->config . '/' . $item->options->color;
            $price = number_format($item->price, 0, '', ',') . 'đ';
            $img = asset($item->options->thumbnail);
            $str .= "<li class='clearfix list_cart_show'>
            <a href='' title='' class='thumb fl-left'><img src='$img' alt=''></a>
            <div class='info fl-right'>
                <a href='' title=''class='product-name'>$name</a>
                <p class='price'>$price</p>
                <p class='qty'>Số lượng: <span>$item->qty</span></p>
            </div>
        </li>";
        }
        $temp = Cart::count();
        $totalPrice = Cart::total(0, '', ',') . 'đ';
        return json_encode(['str' => $str, 'total' => $temp, 'totalPrice' => $totalPrice], 200);
    }

    public function deleteCart(Request $request)
    {
        if (isset($_POST['id'])) {
            $id = $request->input('id');
            Cart::remove($id);
        } else {
            Cart::destroy();
        }
        return json_encode('success', 200);
    }
}
