<?php

namespace App\Http\Controllers;

use App\Exports\ExportFile;
use App\Models\Order;
use App\Mail\SendMailOrder;
use App\Models\OrderDetail;
use App\Models\tbl_commune;
use App\Models\tbl_district;
use App\Models\tbl_province_city;
use App\Models\Warehouse;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class AdminOrderController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'order']);
            return $next($request);
        });
    }

    public function show(Request $request)
    {
        $status = $request->input('status');
        $list_option = [
            'processing' => 'Đang xử lý',
            'delivering' => 'Đang giao',
            'delivered' => 'Đã giao',
            'canceled' => 'Đã huỷ',
        ];
        if ($status == 'canceled') {
            $list_option = [
                'processing' => 'Đang xử lý',
                'delivering' => 'Đang giao',
                'delivered' => 'Đã giao',
            ];
            $orders = DB::table('orders')
                ->where('status', $status)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else if ($status == 'processing') {
            $list_option = [
                'delivering' => 'Đang giao',
                'delivered' => 'Đã giao',
                'canceled' => 'Đã huỷ',
            ];
            $orders = DB::table('orders')
                ->where('status', $status)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else if ($status == 'delivering') {
            $list_option = [
                'processing' => 'Đang xử lý',
                'delivered' => 'Đã giao',
                'canceled' => 'Đã huỷ',
            ];
            $orders = DB::table('orders')
                ->where('status', $status)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else if ($status == 'delivered') {
            $list_option = [
                'processing' => 'Đang xử lý',
                'delivering' => 'Đang giao',
                'canceled' => 'Đã huỷ',
            ];
            $orders = DB::table('orders')
                ->where('status', $status)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
                $orders = DB::table('orders')
                    ->where('name', 'LIKE', "%{$keyword}%")
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);
            } else {
                $orders = DB::table('orders')
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);
            }
        }
        $count_all = Order::count();
        $count_order_delivered = Order::where('status', 'delivered')->count();
        $count_order_processing = Order::where('status', 'processing')->count();
        $count_order_canceled = Order::where('status', 'canceled')->count();
        $count_order_delivering = Order::where('status', 'delivering')->count();
        $revenue = Order::where('status', 'delivered')->sum('total');
        $options = ['processing' => 'Đang chờ xử lý', 'canceled' => 'Đã huỷ', 'delivering' => 'Đang vận chuyển', 'delivered' => 'Đã giao',];
        $count = [$count_all, $count_order_delivered, $count_order_processing, $count_order_canceled, $count_order_delivering];
        return view('admin.order.listOrder', compact('orders', 'count', 'options', 'list_option', 'status', 'revenue'));
    }

    public function deleteOrder($id)
    {
        if (Gate::allows('order.delete')) {
            $order = Order::find($id);
            $order->delete();
            return Redirect::back()->with('status', 'Đã xoá đơn hàng vĩnh viễn thành công');
        } else {
            return Redirect::back()->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function detailOrder(Request $request)
    {
        $id = $request->input('id');
        $order = Order::find($id);
        $nameCity = tbl_province_city::select('name')->where('matp', $order->matp)->first();
        $nameDistrict = tbl_district::select('name')->where('maqh', $order->maqh)->first();
        $nameCommune = tbl_commune::select('name')->where('xaid', $order->xaid)->first();
        $str = "";
        $products = OrderDetail::where('order_id', $id)->get();
        $temp = 0;
        foreach ($products as $product) {
            $thumbnail = asset($product->thumbnail);
            $price = number_format($product->price, 0, '', ',') . 'đ';
            $subtotal = $product->price * $product->quantity;
            $subtotal_fm = number_format($subtotal, 0, '', ',') . 'đ';
            $temp++;
            $str .= "<tr>
                        <th scope='row'>$temp</th>
                        <td><img src='$thumbnail' class='img-product-modal-info' alt='Ảnh sản phẩm'></td>
                        <td>$product->name</td>
                        <td>$product->quantity</td>
                        <td>$subtotal_fm</td>
                    </tr>";
        }
        return json_encode([$order, $nameCity, $nameDistrict, $nameCommune, $str], 200);
    }

    public function updateOrder(Request $request)
    {
        if (Gate::allows('order.edit')) {
            $id = $request->input('id');
            $status = $request->input('status');
            Order::find($id)->update(['status' => $status]);
            $request->session()->flash('status', 'Cập nhật đơn hàng thành công');
            return json_encode($status, 200);
        } else {
            $request->session()->flash('status-danger', 'Bạn không có quyền truy cập chức năng này');
            return json_encode('success', 200);

        }
    }

    public function actionOrder(Request $request)
    {
        if (Gate::allows('order.edit')) {
            $list_check = $request->input('list_check');
            if ($list_check) {
                if (!empty($list_check)) {
                    $act = $request->input('act');
                    switch ($act) {
                        case "canceled":
                            Order::whereIn('id', $list_check)->update(['status' => 'canceled']);
                            return Redirect::back()->with('status', 'Đơn hàng đã được huỷ');
                            break;
                        case "delivered":
                            Order::whereIn('id', $list_check)->update(['status' => 'delivered']);
                            return Redirect::back()->with('status', 'Đơn hàng đã được giao');
                            break;
                        case "delivering":
                            Order::whereIn('id', $list_check)->update(['status' => 'delivering']);
                            return Redirect::back()->with('status', 'Đơn hàng đang được vận chuyển');
                            break;
                        case "processing":
                            Order::whereIn('id', $list_check)->update(['status' => 'processing']);
                            return Redirect::back()->with('status', 'Đơn hàng đang chờ được xử lý');
                            break;
                    }
                }
            } else {
                return Redirect::back()->with('status-danger', 'Bạn cần click chọn đối tượng để thao tác');
            }
        } else {
            return Redirect::back()->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function addOrder(Request $request)
    {
        if (Gate::allows('order.add')) {
            $listCity = tbl_province_city::pluck('name', 'matp');
            $listCity[''] = '---Chọn Tỉnh/Thành Phố---';
            return view('admin.order.addOrder', compact('listCity'));
        } else {
            return Redirect::back()->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function checkInfo(Request $request)
    {
        $phone = $request->input('phone');
        $infoCustomer = Order::where('phone', $phone)->distinct()->first();
        if ($infoCustomer && $infoCustomer->exists()) {
            $name_district = tbl_district::select('maqh', 'name')->where('maqh', $infoCustomer->maqh)->first();
            $name_commune = tbl_commune::select('xaid', 'name')->where('xaid', $infoCustomer->xaid)->first();
            return json_encode([$infoCustomer, $name_district, $name_commune], 200);
        } else {
            return json_encode($infoCustomer, 200);
        }
    }

    public function search(Request $request)
    {
        $key = $request->input('keyword');
        $listProduct = Warehouse::join('products', 'warehouses.product_id', '=', 'products.id')
            ->select('warehouses.*', 'products.*')
            ->where('represent', 1)
            ->where('products.name', 'LIKE', "%{$key}%")
            ->get();
        $str = "";
        $temp = 0;
        foreach ($listProduct as $item) {
            $listColor = Warehouse::join('colors', 'warehouses.color_id', '=', 'colors.id')
                ->select('colors.id', 'colors.name')
                ->where('warehouses.product_id', $item->product_id)
                ->distinct()->get();
            $temp++;
            $url_addCart = route('order.addCart');
            $image = asset($item->thumbnail);
            if ($item->discount > 0) {
                $price = number_format($item->sale_off, 0, '', ',') . 'đ';
            } else {
                $price = number_format($item->price, 0, '', ',') . 'đ';
            }

            $url = route('order.selectConfig');
            $urlGetPrice = route('order.getPrice');
            $name = substr($item->name, 0, 100);
            $str .= "<tr>
            <td>$temp</td>
            <td class='wp_thumbnail'><img class='img_thumbnail'
                    src='$image'
                    alt=''></td>
            <td class='w-25'>$name</td>
            <td>
                <select name='' id='color-$item->product_id' data-url='$url' data-id='$item->product_id' class='form-control color-option'>";
            if ($listColor->count() > 0) {
                $str .= "<option value=''>---Chọn---</option>";
                foreach ($listColor as $color) {
                    $str .= "<option value='$color->id'>$color->name</option>";
                }
            } else {
                $str .= "<option value=''>---Không---</option>";
            }
            $str .= "</select>
            </td>
            <td>
                <select name='' id='config-$item->product_id' data-url='$urlGetPrice' data-id='$item->product_id' class='form-control config-option'>";
            if ($listColor->count() > 0) {
                $str .= "<option value=''>---Chọn---</option>";
            } else {
                $str .= "<option value=''>---Không---</option>";
            }
            $str .= "</select>
            </td>
            <td>
            <div class='wp-number-order ml-auto mr-auto '>
                <input type='number' value='1' min='1' id='quantity-modal-$item->product_id' class='form-control quantity-class'>
            </div>
            </td>
            <td id='price-modal-$item->product_id'>$price</td>
            <td class='icon-cart text-success' data-url='$url_addCart' data-id='$item->product_id'><i class='fa-solid fa-cart-plus'></i></td>
        </tr>";
        }
        return json_encode($str, 200);
    }

    public function getDistrict(Request $request)
    {
        $id = $request->input('id');
        $listDistrict = tbl_district::where('matp', $id)->pluck('name', 'maqh')->first();
        return json_encode($listDistrict, 200);
    }

    public function getPrice(Request $request)
    {
        $id = $request->input('product_id');
        $config_id = $request->input('config_id');
        $price = Warehouse::select('price')->where('product_id', $id)->where('config_id', $config_id)->first();
        return json_encode($price, 200);
    }

    public function getCommune(Request $request)
    {
        $id = $request->input('id');
        $listCommune = tbl_commune::where('maqh', $id)->pluck('name', 'xaid');
        return json_encode($listCommune, 200);
    }

    public function selectConfig(Request $request)
    {
        $product_id = $request->input('id');
        $color_id = $request->input('val');
        $listConfig = Warehouse::join('configs', 'warehouses.config_id', '=', 'configs.id')
            ->select('configs.id', 'configs.name')
            ->where('product_id', $product_id)->where('color_id', $color_id)->get();
        $str = "";
        foreach ($listConfig as $config) {
            $str .= "<option value='$config->id'>$config->name</option>";
        }
        return json_encode($str, 200);
    }

    public function storeCart(Request $request)
    {
        $color_id = $request->input('color_id') ?? 0;
        $product_id = $request->input('product_id') ?? 0;
        $config_id = $request->input('config_id') ?? 0;
        $qty = $request->input('quantity');

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
        $temp = 0;
        $data_url = route('order.deleteCart');
        foreach (Cart::content() as $item) {
            $temp++;
            $image = asset($item->options->thumbnail);
            $name = $item->name . "/" . $item->options->color . "/" . $item->options->config;
            $price = number_format($item->price, 0, '', ',') . 'đ';
            $str .= "<tr>
            <td>$temp</td>
            <td class='wp_thumbnail'><img class='img_thumbnail'
                    src='$image'
                    alt=''></td>
            <td class='w-25'>$name</td>

            <td>
                <span>$item->qty</span>
            </td>
            <td>$price</td>
            <td class='text-center'><a
            class='btn btn-danger btn-delete-cart btn-sm rounded-0 text-white'
            type='button' data-toggle='tooltip' data-placement='top'
            data-rowId='$item->rowId'
            data-url='$data_url' title='Delete'>
            <i class='fa fa-trash'></i></a></td>
        </tr>";
        }
        // Cart::destroy();
        return json_encode($str, 200);
    }

    public function deleteCart(Request $request)
    {
        if ($request->input('rowID')) {
            $id = $request->input('rowID');
            Cart::remove($id);
        } else {
            Cart::destroy();
        }
        return json_encode('success', 200);
    }

    public function order(Request $request)
    {
        global $order_id;
        $request->validate(
            [
                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required|regex:/[0-9]{9}/|max:15',
                'province-city' => 'required',
                'district' => 'required',
                'commune' => 'required',
                'address' => 'required'
            ],
            [
                'required' => 'Không được để trống trường :attribute',
                'regex' => ':attribute không đúng định dạng',
                'max' => ':attribute không lớn hơn 15 số',
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
        if (Cart::total() > 0) {
            $Order = Order::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'total' => Cart::total(0, '', ''),
                'xaid' => $request->input('commune'),
                'maqh' => $request->input('district'),
                'matp' => $request->input('province-city'),
                'payment_method' => 'payment_home',
                'address' => $request->input('address')
            ]);

            $order_id = $Order->id;
            foreach (Cart::content() as $item) {
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
            Cart::destroy();

            return redirect()->route('order.index')->with('status', 'Đã thêm đơn hàng mới thành công');
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function listRevenue(Request $request)
    {
        if ($request->input('month') && $request->input('year')) {
            $dateMonth = $request->input('month') ?? '';
            $dateYear = $request->input('year') ?? '';
        } else {
            $dateMonth = date('m');
            $dateYear = date('Y');
        }
        $currentDate = Carbon::now();

        $revenueOfDay = Order::selectRaw('DATE(created_at) as date, SUM(total) as total_price_on_day')
            ->whereRaw('MONTH(created_at) = ?', [$dateMonth])
            ->whereRaw('YEAR(created_at) = ?', [$dateYear])
            ->where('status', 'delivered')
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->paginate(25);

        $revenueOfProduct = OrderDetail::join('orders', 'order_details.order_id', '=', 'orders.id')->selectRaw('order_details.name, sum(order_details.quantity) as quantity, SUM(order_details.price) as total, order_details.price')
            ->whereRaw('MONTH(order_details.created_at) = ?', [$dateMonth])
            ->whereRaw('YEAR(order_details.created_at) = ?', [$dateYear])
            ->where('orders.status', 'delivered')
            ->groupBy('name', 'price')
            ->get();

        $months = Order::select(DB::raw('MONTH(created_at) as month'))
            ->distinct()
            ->get();
        $years = Order::select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->get();
        // return $months;
        return view('admin.order.revenue', compact('revenueOfDay', 'revenueOfProduct', 'currentDate', 'months', 'years', 'dateMonth','dateYear'));
    }

    public function detailProductSaleOneDate(Request $request)
    {
        $time = $request->input('time');
        $day = substr("$time", 8, 11);
        $month = substr("$time", 5, -3);
        $year = substr("$time", 0, 4);
        $listDetail = OrderDetail::join('orders', 'order_details.order_id', '=', 'orders.id')->selectRaw('order_details.name, sum(order_details.quantity) as quantity, SUM(order_details.price) as total, order_details.price')
            ->whereRaw('DAY(order_details.created_at) = ?', [$day])
            ->whereRaw('MONTH(order_details.created_at) = ?', [$month])
            ->whereRaw('YEAR(order_details.created_at) = ?', [$year])
            ->where('orders.status', 'delivered')
            ->groupBy('name', 'price')
            ->get();
        $str = "";
        $temp = 0;
        foreach ($listDetail as $item) {
            $temp++;
            $subTotal = $item->price * $item->quantity;
            $subTotalFormat = number_format($subTotal, 0, ',') . 'đ';
            $str .= "<tr>
            <td class='font-weight-bold'>$temp</td>
            <td>$item->name</td>
            <td>$item->quantity</td>
            <td>$subTotalFormat</td>
        </tr>";
        }
        return json_encode($str, 200);
    }

    public function excelExport(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        $quarter = $request->input('quarter');

        $data = [
            'quarter' => $quarter,
            'month' => $month,
            'year' => $year,
            'username' => Auth::user()->name,
        ];

        $fileName = $month !== 'null' ? 'sales_' . $month . '_' . $year . '.xlsx' :
            ($quarter !== 'null' ? 'sales_quarter_' . $quarter . '_' . $year . '.xlsx' :
            'sales_year_' . $year . '.xlsx');

        return Excel::download(new ExportFile($data), $fileName);
    }

}
