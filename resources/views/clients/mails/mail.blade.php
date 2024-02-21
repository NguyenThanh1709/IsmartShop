<div id=":1ov" class="ii gt"
    jslog="20277; u014N:xr6bB; 1:WyIjdGhyZWFkLWY6MTc2NDI1MTg4NzIyNjI1NjYxOCIsbnVsbCxudWxsLG51bGwsbnVsbCxudWxsLG51bGwsbnVsbCxudWxsLG51bGwsbnVsbCxudWxsLG51bGwsW11d; 4:WyIjbXNnLWY6MTc2NDI1MzU5OTE4MDc1Nzk4MiIsbnVsbCxbXV0.">
    <div id=":1ou" class="a3s aiL ">
        <table align="center" bgcolor="#dcf0f8" border="0" cellpadding="0" cellspacing="0"
            style="margin:0;padding:0 15%;background-color:#ffffff;width:100%!important;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;line-height:18px"
            width="100%">
            <tbody>
                <tr>
                    <td><span class="im">
                            <h1 style="font-size:18px;font-weight:bold;color:#444;padding:0 0 5px 0;margin:0">Cảm ơn quý
                                khách {{ $name }} đã đặt hàng tại Ismart Store</h1>
                        </span>
                        <p
                            style="margin:4px 0;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;line-height:18px;font-weight:normal">
                            Ismart rất vui thông báo đơn hàng DH#{{ $codeOrder }} của quý khách đã được tiếp nhận và
                            đang trong quá
                            trình xử lý. Ismart sẽ thông báo đến quý khách ngay khi hàng chuẩn bị được giao.</p>
                        <h3
                            style="font-size:14px;font-weight:bold;color:#02acea;text-transform:uppercase;margin:20px 0 0 0;border-bottom:1px solid #ddd">
                            Thông tin đơn hàng DH#{{ $codeOrder }} <span
                                style="font-size:13px;color:#777;text-transform:none;font-weight:normal">(26/04/2023 -
                                22:35:52)</span></h3>
                    </td>
                </tr>
                <tr>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;line-height:18px">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th align="left"
                                        style="padding:6px 9px 0px 9px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;font-weight:bold"
                                        width="50%">Thông tin thanh toán</th>
                                    <th align="left"
                                        style="padding:6px 9px 0px 9px;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;font-weight:bold"
                                        width="50%"> Địa chỉ giao hàng </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="padding:3px 9px 9px 9px;border-top:0;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;line-height:18px;font-weight:normal"
                                        valign="top"><span style="text-transform:capitalize">Họ và tên:
                                            {{ $name }}
                                        </span><br>
                                        Địa chỉ email:<a href="http://%20target=" target="_blank"
                                            data-saferedirecturl="https://www.google.com/url?q=http://%2520target%3D&amp;source=gmail&amp;ust=1682820722216000&amp;usg=AOvVaw0HxcN0CSCl3gZFhpmutib1">
                                            {{ $email }}</a><br>
                                        Số điện thoại: {{$phone}}
                                    </td>
                                    <td style="padding:3px 9px 9px 9px;border-top:0;border-left:0;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;line-height:18px;font-weight:normal"
                                        valign="top"><span class="im"><span
                                                style="text-transform:capitalize">{{ $name }}</span><br>
                                            Địa chỉ email:<a href="http://%20target=" target="_blank"
                                                data-saferedirecturl="https://www.google.com/url?q=http://%2520target%3D&amp;source=gmail&amp;ust=1682820722216000&amp;usg=AOvVaw0HxcN0CSCl3gZFhpmutib1">
                                                {{ $email }}</a><br></span>
                                        Địa chỉ giao hàng:
                                        {{ $address . '/' . $commune . '/' . $district . '/' . $city }}<br>
                                        Số điện thoại: {{ $phone }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"
                                        style="padding:7px 9px 0px 9px;border-top:0;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444"
                                        valign="top">
                                        <p
                                            style="font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;line-height:18px;font-weight:normal">
                                            <strong>Phương thức thanh toán: </strong> {{ $payment_method }}<br>
                                            <strong>Thời gian giao hàng dự kiến:</strong> Dự kiến giao hàng giao trong
                                            vòng từ 3-5 ngày<br>
                                            <strong>Phí vận chuyển: </strong> 0đ<br>
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h2
                            style="text-align:left;margin:10px 0;border-bottom:1px solid #ddd;padding-bottom:5px;font-size:14px;color:#02acea">
                            CHI TIẾT ĐƠN HÀNG</h2>
                        <table border="0" cellpadding="0" cellspacing="0" style="background:#f5f5f5" width="100%">
                            <thead>
                                <tr>
                                    <th align="left" bgcolor="#02acea"
                                        style="padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:14px">
                                        Sản phẩm</th>
                                    <th align="left" bgcolor="#02acea"
                                        style="padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:14px">
                                        Đơn giá</th>
                                    <th align="left" bgcolor="#02acea"
                                        style="padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:14px;text-align:center">
                                        Số lượng</th>
                                    <th align="right" bgcolor="#02acea"
                                        style="padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:14px">
                                        Tổng tạm</th>
                                </tr>
                            </thead>
                            <tbody bgcolor="#eee"
                                style="font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;line-height:18px">
                                @foreach (Cart::content() as $item)
                                    <tr>
                                        <td align="left" style="padding:3px 9px" valign="top"><span>
                                                {{ $item->name }}
                                            </span><br></td>
                                        <td align="left" style="padding:3px 9px" valign="top"><span>
                                                {{ number_format($item->price, 0, '', ',') . 'đ' }}</span>
                                        </td>
                                        <td align="left" style="padding:3px 9px;text-align:center" valign="top">
                                            {{ $item->qty }}</span></td>
                                        <td align="right" style="padding:3px 9px" valign="top">
                                            <span>{{ number_format($item->total, 0, '', ',') . 'đ' }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot
                                style="font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;line-height:18px">
                                <tr>
                                    <td align="right" colspan="3" style="padding:5px 9px">Phí vận chuyển</td>
                                    <td align="right" style="padding:5px 9px"><span>0đ</span></td>
                                </tr>
                                <tr bgcolor="#eee">
                                    <td align="right" colspan="3" style="padding:7px 9px"><strong><big>Tổng giá trị
                                                đơn hàng</big> </strong></td>
                                    <td align="right" style="padding:7px 9px">
                                        <strong><big><span>{{ Cart::total(0, '', ',') . 'đ' }}</span>
                                            </big>
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;
                        <p>Một lần nữa Ismart cảm ơn quý khách.</p>
                        <font color="#888888">
                            <p><strong><a href="http://%20target=" target="_blank"
                                        data-saferedirecturl="https://www.google.com/url?q=http://%2520target%3D&amp;source=gmail&amp;ust=1682820722216000&amp;usg=AOvVaw0HxcN0CSCl3gZFhpmutib1">Ismart</a>
                                </strong></p>
                        </font>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="yj6qo"></div>
        <div class="adL">

        </div>
    </div>
</div>
