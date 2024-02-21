<table>
    <thead>
        <tr>
            <th colspan="4">Ismart: Website bán hàng trực truyến</th>
        </tr>
        <tr>
            <th colspan="4" style="font-size: 18px; text-align: center; text-transform: uppercase; font-weight: 700;">
    THỐNG KÊ SẢN PHẨM BÁN RA 
    {{ $month !== 'null' ? 'THÁNG' . '/' . $year : ($quarter !== 'null' ? 'QUÝ ' . $quarter . ' ' . $year : 'NĂM ' . $year) }}
</th>

        </tr>
        <tr>
            <td></td>
        </tr>
        <tr style="width: 1200px;">
            <th style="width: 400px; font-weight: 600;">Tên sản phẩm</th>
            <th style="width: 100px; font-weight: 600;">Số lượng bán ra</th>
            <th style="width: 150px; font-weight: 600;">Tổng doanh thu</th>
            <th style="width: 150px; font-weight: 600;">Thời gian</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($list as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->total * $item->quantity}}</td>
                <td>{{ $item->date }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td></td>
        </tr>
        <tr>
            <th colspan="4" style="text-align: right">Người xuất báo cáo: <b>{{ $username }}</b></th>
        </tr>
    </tfoot>
</table>
