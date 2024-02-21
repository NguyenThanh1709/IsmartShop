<?php

namespace App\Exports;

use App\Models\OrderDetail;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;


class ExportFile implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    use Exportable;

    public $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        $query = OrderDetail::join('orders', 'order_details.order_id', '=', 'orders.id')
            ->selectRaw('order_details.name, SUM(order_details.quantity) as quantity, SUM(order_details.price) as total, DATE(order_details.created_at) as date')
            ->where('orders.status', 'delivered')
            ->groupBy('name', 'date');

        // Dữ liệu đầu vào
        if ($this->data['month'] != 'null') {
            // Doanh thu của tháng
            $query->whereRaw('MONTH(order_details.created_at) = ?', $this->data['month'])
                ->whereRaw('YEAR(order_details.created_at) = ?', $this->data['year']);
        } elseif ($this->data['quarter'] != 'null') {
            //Doanh thu của quý
            $startMonth = ($this->data['quarter'] - 1) * 3 + 1;
            $endMonth = $startMonth + 2;
            $query->whereRaw('MONTH(order_details.created_at) BETWEEN ? AND ?', [$startMonth, $endMonth])
                ->whereRaw('YEAR(order_details.created_at) = ?', $this->data['year']);
        } else {
            //Doanh thu của năm
            $query->whereRaw('YEAR(order_details.created_at) = ?', $this->data['year']);
        }

        // Lấy dữ liệu - thực thi câu truy vấn
        $list = $query->get();


        return view('admin.Export.ExportView', compact('list'), [
            'month' => $this->data['month'],
            'year' => $this->data['year'],
             'quarter' => $this->data['quarter'],
            'username' => $this->data['username']
        ]);
    }
}
