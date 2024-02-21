@extends('layouts.client')

@section('title', 'Đặt hàng thành công')

@section('content')
    <div id="main-content-wp" class="list-product-page">
        <div class="secion" id="breadcrumb-wp">
            <div class="secion-detail">
                <ul class="list-item clearfix">
                    <li>
                        <a href="" title="">Trang chủ</a>
                    </li>
                    <li>
                        <a href="" title="">Đặt hàng thành công</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="wrap clearfix wp-inner wp_noti_order_success">
            <div class="wp_container_noti_success">
                <h2 class="font-weight-bold h4 mb-2 d-block text-success"><i class="fa-solid fa-circle-check mr-2"></i>ĐẶT
                    HÀNG THÀNH
                    CÔNG</h2>
                <p class="">Cảm ơn quý khách đã đặt hàng tại cửa hàng chúng tôi.</p>
                <p>Nhân viên của chúng tôi sẽ liên hệ với bạn để xác nhận đơn hàng, thời gian giao hàng chậm nhất là 48h.
                </p>
            </div>
        </div>
        <div class="wrap clearfix">
            <div id="content" class="detail-exhibition wp-inner">
                <div class="section" id="info">
                    <div class="title h6 font-weight-bold text-left">Mã đơn hàng: <span
                            class="detail text-order-id">#DH{{ $infoOrder['codeOrder'] }}</span></div>
                </div>
                <h5 class="mb-1 mt-3 text-success text-left"><i class="fa-solid fa-circle-info"></i>Thông tin khách hàng
                </h5>
                <div class="section">
                    <div class="table-responsive table-danger">
                        <table class="table info-exhibition">
                            <thead class="font-weight-bold">
                                <tr>
                                    <td class="thead-text">Họ và tên</td>
                                    <td class="thead-text">Số điện thoại</td>
                                    <td class="thead-text">Email</td>
                                    <td class="thead-text">Địa chỉ</td>
                                    <td class="thead-text">Ghi chú</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="font-weight-bold">{{ $infoOrder['name'] }}</td>
                                    <td>{{ $infoOrder['phone'] }}</td>
                                    <td>{{ $infoOrder['email'] }}</td>
                                    <td>{{ $infoOrder['address'] . '/' . $infoOrder['commune'] . '/' . $infoOrder['district'] . '/' . $infoOrder['city'] }}
                                    </td>
                                    <td>{{ $infoOrder['note'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h5 class="mb-1 mt-3 text-title-info text-left text-success"><i
                            class="fa-solid fa-circle-info"></i>Thông tin đơn
                        hàng</h5>
                    <div class="table-responsive table-danger">
                        <table class="table info-exhibition">
                            <thead class="font-weight-bold">
                                <tr>
                                    <td class="thead-text">STT</td>
                                    <td class="thead-text">Ảnh sản phẩm</td>
                                    <td class="thead-text ">Tên sản phẩm</td>
                                    <td class="thead-text">Đơn giá</td>
                                    <td class="thead-text">Số lượng</td>
                                    <td class="thead-text">Thành tiền</td>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $temp = 0;
                                @endphp
                                @foreach (Cart::content() as $item)
                                    @php
                                        $temp++;
                                    @endphp
                                    <tr>
                                        <td class="thead-text">{{ $temp }}</td>
                                        <td class="thead-text">
                                            <div class="thumb">
                                                <img style="width:75px;heght:auto"
                                                    src="{{ asset($item->options->thumbnail) }}" alt="Ảnh sản phẩm">
                                            </div>
                                        </td>
                                        <td class="thead-text text-left">{{ $item->name }}
                                            @if ($item->options->config != '')
                                                {{ '/' . $item->options->config . '/' . $item->options->color }}
                                            @endif</td>
                                        <td class="thead-text">{{ number_format($item->price, 0, '', ',') . 'đ' }}</td>
                                        <td class="thead-text">{{ $item->qty }}</td>
                                        <td class="thead-text">{{ number_format($item->total, 0, '', ',') . 'đ' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="font-weight-bold">
                                <tr>
                                    <td colspan="5" class="thead-text text_total text-right">Tổng tiền</td>
                                    <td class="thead-text">{{ Cart::total(0, '', ',') . 'đ' }}
                                    </td>
                                </tr>
                            </tfoot>
                            {{ Cart::destroy() }}
                        </table>
                    </div>
                </div>
                <div class="w-100 pt-4">
                    <a class="btn btn-outline-danger" href="{{ route('home.index') }}" role="button">Trang chủ</a>
                </div>
            </div>
        </div>
    </div>
@endsection
