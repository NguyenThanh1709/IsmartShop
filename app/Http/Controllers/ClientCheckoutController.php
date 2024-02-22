<?php

namespace App\Http\Controllers;

use App\Mail\SendMailOrder;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductCat;
use App\Models\tbl_commune;
use App\Models\tbl_district;
use App\Models\tbl_province_city;
use App\Models\Warehouse;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use GuzzleHttp\Promise\Create;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Psy\CodeCleaner\IssetPass;

class ClientCheckoutController extends Controller
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
    public function index()
    {
        $productCat = ProductCat::where('status', 'public')->get();
        $menu = render_menu($productCat, 0, 0);
        $listCity = tbl_province_city::pluck('name', 'matp');
        $listCity[''] = '---Chọn Tỉnh/Thành phố---';
        return view('clients.checkout.checkout', compact('listCity', 'menu'));
    }

    public function getDistrict(Request $request)
    {
        $id = $request->input('id');
        $listDistrict = tbl_district::where('matp', $id)->pluck('name', 'maqh');
        return json_encode($listDistrict, 200);
    }

    public function getCommune(Request $request)
    {
        $id = $request->input('id');
        $listCommune = tbl_commune::where('maqh', $id)->pluck('name', 'xaid');
        return json_encode($listCommune, 200);
    }

    public function order(Request $request)
    {
        foreach (Cart::content() as $item) {
            $productQuantity = Warehouse::where('id', $item->options->warehouse_id)->value('quantity');
        }
        global $order_id;
        //Validation
        $request->validate(
            [
                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required|regex:/[0-9]{9}/|max:15',
                'province-city' => 'required|not_in:0',
                'district' => 'required|not_in:0',
                'commune' => 'required|not_in:0',
                'address' => 'required|not_in:0'
            ],
            [
                'required' => 'Không được để trống trường :attribute',
                'regex' => ':attribute không đúng định dạng',
                'max' => ':attribute không lớn hơn 15 số',
                'not_in' => 'Vui lòng chọn thông tin :attribute'
            ],
            [
                'name' => 'Họ và tên',
                'phone' => 'Số điện thoại',
                'province-city' => 'Tỉnh/Thành phố',
                'district' => 'Huyện/Thị trấn',
                'commune' => 'Xã/Phường',
                'address' => 'Địa chỉ'
            ]
        );

        //VNPAY Payment
        if (isset($_POST['payment-vnpay'])) {
            session(['order_request' => $request->all()]);
            return redirect()->route('vnpay.payment');
        }
        $timeString = Carbon::now()->format('His') + random_int(0, 99);
        $codeOrder = $timeString;
        if (Cart::total() > 0) {
            $Order = Order::create([
                'id' => $codeOrder,
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'total' => Cart::total(0, '', ''),
                'xaid' => $request->input('commune'),
                'maqh' => $request->input('district'),
                'matp' => $request->input('province-city'),
                'payment_method' => $request->input('payment-method'),
                'address' => $request->input('address')
            ]);

            // $order_id = $Order->id;
            foreach (Cart::content() as $item) {
                //Lấy số lượng sản phẩm trong kho
                $productQty = Warehouse::where('id', $item->options->warehouse_id)->value('quantity');
                $oderQty = $item->qty;
                $newQty = $productQty - $oderQty;
                Warehouse::find($item->options->warehouse_id)->update(['quantity' => $newQty]); //Cập nhật lại số lượng tồn kho
                if ($item->options->config != '') {
                    $name = $item->name . '/' . $item->options->config . '/' . $item->options->color;
                } else {
                    $name = $item->name;
                }
                OrderDetail::create([
                    'product_id' => $item->id,
                    'order_id' => $codeOrder,
                    'thumbnail' => $item->options->thumbnail,
                    'name' => $name,
                    'price' => $item->price,
                    'quantity' => $item->qty,
                    'warehouse_id' => $item->options->warehouse_id
                ]);
            }
            $name_city = tbl_province_city::select('name')->where('matp', $Order->matp)->first()->name;
            $name_district = tbl_district::select('name')->where('maqh', $Order->maqh)->first()->name;
            $name_commune = tbl_commune::select('name')->where('xaid', $Order->xaid)->first()->name;
            $data = [
                'codeOrder' => $Order->id,
                'name' => $Order->name,
                'address' => $Order->address,
                'email' => $Order->email,
                'total' => $Order->total,
                'payment_method' => $Order->payment_method,
                'note' => $Order->note,
                'phone' => $Order->phone,
                'city' => $name_city,
                'district' => $name_district,
                'commune' => $name_commune,
            ];
            Mail::to($Order->email)->send(new SendMailOrder($data));
            session()->flash('infoOrder', json_encode($data));
            $infoOrder = json_decode(session('infoOrder'), true);
            $productCat = ProductCat::where('status', 'public')->get();
            $menu = render_menu($productCat, 0, 0);
            return view('clients.checkout.ordersuccess', compact('infoOrder', 'menu'));
        } else {
            return redirect()->route('home.index');
        }
        Cart::destroy();
    }
}
