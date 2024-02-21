<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashBoardController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'dashboard']);
            return $next($request);
        });
    }

    public function show(Request $request)
    {
        $status = $request->input('status');
         $months = array_combine(range(1, 12), array_map(function ($m) {
            return "Tháng $m";
        }, range(1, 12))); //Lấy danh sách tháng

        $years = array_combine(range(date('Y'), date('Y') - 10), array_map(function ($y) {
            return "Năm $y";
        }, range(date('Y'), date('Y') - 10))); //Lấy danh sách năm (10 năm gần nhất) và thêm vào value chữ tháng và năm
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
                ->where('created_at', '>=', Carbon::now()->subDays(2)->startOfDay())
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
                ->where('created_at', '>=', Carbon::now()->subDays(2)->startOfDay())
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
                ->where('created_at', '>=', Carbon::now()->subDays(2)->startOfDay())
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
                ->where('created_at', '>=', Carbon::now()->subDays(2)->startOfDay())
                ->where('status', $status)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
                $orders = DB::table('orders')
                    ->where('created_at', '>=', Carbon::now()->subDays(2)->startOfDay())
                    ->where('name', 'LIKE', "%{$keyword}%")
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);
            } else {
                $orders = DB::table('orders')
                    ->where('created_at', '>=', Carbon::now()->subDays(2)->startOfDay())
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);
            }
        }
        $count_order_status_3day = Order::where('created_at', '>=', Carbon::now()->subDays(2)->startOfDay())
            ->count();
        $count_order_delivered_3day = Order::where('created_at', '>=', Carbon::now()->subDays(2)->startOfDay())
            ->where('status', 'delivered')
            ->count();
        $count_order_delivering_3day = Order::where('created_at', '>=', Carbon::now()->subDays(2)->startOfDay())
            ->where('status', 'delivering')
            ->count();
        $count_order_processing_3day = Order::where('created_at', '>=', Carbon::now()->subDays(2)->startOfDay())
            ->where('status', 'processing')
            ->count();
        $count_order_canceled_3day = Order::where('created_at', '>=', Carbon::now()->subDays(2)->startOfDay())
            ->where('status', 'canceled')
            ->count();

        $count_order_delivered = Order::where('status', 'delivered')->count();
        $count_order_processing = Order::where('status', 'processing')->count();
        $count_order_canceled = Order::where('status', 'canceled')->count();
        $count_order_delivering = Order::where('status', 'delivering')->count();
        $revenue = Order::where('status', 'delivered')->sum('total');
        $options = ['processing' => 'Đang chờ xử lý', 'canceled' => 'Đã huỷ', 'delivering' => 'Đang vận chuyển', 'delivered' => 'Đã giao',];
        $count = [$count_order_delivered, $count_order_processing, $count_order_canceled, $count_order_delivering, $count_order_delivered_3day, $count_order_delivering_3day, $count_order_processing_3day, $count_order_canceled_3day, $count_order_status_3day];
        
        //Thống kê trạng thái đơn hàng
        $count_order_delivered = Order::where('status', 'delivered')->count();
        $count_order_processing = Order::where('status', 'processing')->count();
        $count_order_canceled = Order::where('status', 'canceled')->count();
        $count_order_delivering = Order::where('status', 'delivering')->count();
        $revenueTotal = Order::where('status', 'delivered')->sum('total');
        $options = ['processing' => 'Đang chờ xử lý', 'canceled' => 'Đã huỷ', 'delivering' => 'Đang vận chuyển', 'delivered' => 'Đã giao',];
        $count = [$count_order_delivered, $count_order_processing, $count_order_canceled, $count_order_delivering, $count_order_delivered_3day, $count_order_delivering_3day, $count_order_processing_3day, $count_order_canceled_3day, $count_order_status_3day];


        //Xử lý biểu đồ
        $currentMonth = Carbon::now()->month; // Lấy tháng hiện tại
        $currentYear = Carbon::now()->year; // Lấy năm hiện tại


        if ($request->input('month') && $request->input('year')) {
            $currentMonth = $request->input('month');
            $currentYear = $request->input('year');
        }

        $productCategorySales = DB::table('order_details')
            ->select('product_cats.id', 'product_cats.name as category_name', DB::raw('SUM(order_details.quantity) as total_sold'))
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->join('product_cats', 'product_cats.id', '=', 'products.product_cat_id')
            ->whereYear('orders.created_at', $currentYear)
            ->whereMonth('orders.created_at', $currentMonth)
            ->where('orders.status', 'delivered')
            ->groupBy('product_cats.id', 'product_cats.name')
            ->get();

        $revenues = [];


        $daysInMonth = Carbon::createFromDate($currentYear, $currentMonth)->daysInMonth; // Số ngày trong tháng

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $startOfDay = Carbon::create($currentYear, $currentMonth, $day)->startOfDay();
            $endOfDay = Carbon::create($currentYear, $currentMonth, $day)->endOfDay();

            $revenue = Order::where('status', 'delivered')
                ->whereBetween('created_at', [$startOfDay, $endOfDay])
                ->sum('total');

            $revenues[$day] = $revenue;
        }

        return view('admin.dashboard', compact('orders', 'count', 'revenues', 'revenueTotal', 'options', 'list_option', 'status', 'productCategorySales', 'currentYear', 'currentMonth', 'months', 'years'));
    }


}
