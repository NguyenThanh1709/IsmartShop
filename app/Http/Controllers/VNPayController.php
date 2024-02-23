<?php

namespace App\Http\Controllers;

use App\Mail\SendMailOrder;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\ProductCat;
use App\Models\tbl_commune;
use App\Models\tbl_district;
use App\Models\tbl_province_city;
use App\Models\Warehouse;
use Carbon\Carbon;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class VNPayController extends Controller
{
    //
    public function index()
    {
        $toltalPrice = Cart::total(0, '', '');
        return view('clients.vnpay.index', compact('toltalPrice'));
    }

    public function createPayment(Request $request)
    {
        $vnp_TmnCode = "2N5QEBWE"; //Mã định danh merchant kết nối (Terminal Id)
        $vnp_HashSecret = "RVBEQXRPFBYRHFNVOLJMEJQSPHMYSDEC"; //Secret key
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpay.returnURL');
        $timeString = Carbon::now()->format('His') + random_int(0, 99);
        $codeOrder = $timeString;

        $vnp_TxnRef = $codeOrder; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = $_POST['OrderDescription'];
        $vnp_OrderType = $_POST['ordertype'];
        $vnp_Amount = $_POST['Amount'] * 100;
        $vnp_Locale = $_POST['language'];
        $vnp_BankCode = $_POST['bankcode'];
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'code' => '00', 'message' => 'success', 'data' => $vnp_Url
        );
        if (isset($_POST['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            return redirect($vnp_Url);
        }
    }

    public function vnpayReturn(Request $request)
    {
        // dd($request->all());
        try {
            $orderRequest = session('order_request');
            foreach (Cart::content() as $item) {
                $productQuantity = Warehouse::where('id', $item->options->warehouse_id)->value('quantity');
            }
            global $order_id;
            if (Cart::total() > 0) {
                $Order = Order::create([
                    'id' => $request->input('vnp_TxnRef'),
                    'name' => $orderRequest['name'],
                    'email' => $orderRequest['email'],
                    'phone' => $orderRequest['phone'],
                    'total' => Cart::total(0, '', ''),
                    'xaid' => $orderRequest['commune'],
                    'maqh' => $orderRequest['district'],
                    'matp' => $orderRequest['province-city'],
                    'payment_method' => 'online',
                    'address' => $orderRequest['address']
                ]);

                $order_id = $request->input('vnp_TxnRef');
                // dd($order_id);
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
                        'order_id' => $order_id,
                        'thumbnail' => $item->options->thumbnail,
                        'name' => $name,
                        'price' => $item->price,
                        'quantity' => $item->qty,
                        'warehouse_id' => $item->options->warehouse_id
                    ]);
                }

                try {
                    $payment = Payment::create([
                        'order_id' => $order_id,
                        'p_code_bank' => $request->input('vnp_BankCode'),
                        'p_note' => $request->input('vnp_OrderInfo'),
                        'p_money' => $request->input('vnp_Amount'),
                        'p_vpn_response_code' => $request->input('vnp_ResponseCode'),
                        'p_time' => $request->input('vnp_PayDate'),
                        'p_code_vnpay' => $request->input('vnp_BankTranNo')
                    ]);

                    if ($payment) {
                        Session::flash('success_message', "Đặt hàng và thanh toán thành công!");
                    } else {
                        Session::flash('error_message', "Đã xảy ra lỗi trong quá trình đặt hàng và thanh toán.");
                    }
                } catch (\Exception $e) {
                    $alert = "Đã xảy ra lỗi trong quá trình đặt hàng và thanh toán: " . $e->getMessage();
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
        } catch (Exception $ex) {
            echo "Lỗi . $ex";
            die();
            return redirect()->back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại sau.');
        }
    }
}
