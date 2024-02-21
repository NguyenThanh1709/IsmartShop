<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\OrderDetail;
use App\Models\Page;
use App\Models\ProductCat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientPageController extends Controller
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
    public function showIntroduce()
    {
        // $banners = Banner::where('status', 'public')->get();
        $productCat = ProductCat::where('status', 'public')->get();
        $menu = render_menu($productCat, 0, 0);
        $productASC = OrderDetail::select('product_id', 'warehouse_id', 'name', 'thumbnail', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id', 'warehouse_id', 'name', 'thumbnail')
            ->orderBy(DB::raw('SUM(quantity)'), 'DESC')
            ->take(6)
            ->get();
        $pageIntroduce = Page::where('name', 'Giới thiệu')->where('status', 'public')->get();
        return view('clients.page.introduce', compact('pageIntroduce', 'productASC', 'menu'));
    }

    public function showContact()
    {
        $productCat = ProductCat::where('status', 'public')->get();
        $menu = render_menu($productCat, 0, 0);
        $banners = Banner::where('status', 'public')->get();
        $productASC = OrderDetail::select('product_id', 'warehouse_id', 'name', 'thumbnail', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id', 'warehouse_id', 'name', 'thumbnail')
            ->orderBy(DB::raw('SUM(quantity)'), 'DESC')
            ->take(6)
            ->get();
        $contacts = Page::where('name', 'Liên hệ')->where('status', 'public')->get();
        return view('clients.page.contact', compact('contacts', 'productASC', 'banners','menu'));
    }
}
