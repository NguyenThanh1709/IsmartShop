@extends('layouts.client')

@section('title', 'Giỏ hàng')

@section('content')
    <div class="section" id="breadcrumb-wp">
        <div class="wp-inner">
            <div class="section-detail">
                <ul class="list-item clearfix">
                    <li>
                        <a href="/" title="">Trang chủ</a>
                    </li>
                    <li>
                        <a href="" title="">Giỏ hàng</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @if (Cart::count() > 0)
        <div id="wrapper" class="wp-inner clearfix mt-3">
            <div class="section" id="info-cart-wp">
                <div class="section-detail table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <td class="code-product">Mã sản phẩm</td>
                                <td>Ảnh sản phẩm</td>
                                <td>Tên sản phẩm</td>
                                <td>Giá sản phẩm</td>
                                <td>Số lượng</td>
                                <td colspan="2">Thành tiền</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (Cart::content() as $row)
                                <tr>
                                    <td class="code-product">#CODE{{ $row->id }}</td>
                                    <td>
                                        <a href="" title="" class="thumb">
                                            <img src="{{ asset($row->options->thumbnail) }}" alt="">
                                        </a>
                                    </td>
                                    <td>
                                        <a href="" title="" class="name-product">{{ $row->name }}
                                            @if ($row->options->config != '')
                                                {{ '/' . $row->options->config . '/' . $row->options->color }}
                                            @endif
                                        </a>
                                    </td>
                                    <td>{{ number_format($row->price, 0, '', ',') . 'đ' }}</td>
                                    <td>
                                        <input data-url="{{ route('cart.update') }}" data-id="{{ $row->rowId }}"
                                            type="number" min="1" name="num-order" value="{{ $row->qty }}"
                                            class="num-order">
                                    </td>
                                    <td><span
                                            id="sub-total-{{ $row->rowId }}">{{ number_format($row->total, 0, '', ',') . 'đ' }}</span>
                                    </td>
                                    <td>
                                        <a href="" title="" data-id="{{ $row->rowId }}"
                                            data-url="{{ route('cart.delete') }}" class="del-product"><i
                                                class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7">

                                </td>
                            </tr>
                            <tr>
                                <td colspan="7">

                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="clearfix">
                        <p id="total-price" class="fl-right">Tổng giá:
                            <span>{{ Cart::total(0, ',', '.') }}đ<span>
                        </p>
                    </div>
                    <div class="clearfix">
                        <div class="fl-right">
                            <a title=""  data-url="{{ route('cart.delete') }}" class="delete-cart" id="update-cart">Xoá giỏ hàng</a>
                            <a href="{{ route('checkout.index') }}" title="" id="checkout-cart">Thanh toán</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section" id="action-cart-wp">
                <div class="section-detail">
                    <p class="title">Click vào <span>“Xoá giỏ hàng”</span> để xoá tất cả sản phẩm. Nhấn vào thanh toán để
                        hoàn tất mua hàng.
                    </p>
                    <a href="{{ route('product.list') }}" title="" id="buy-more">Mua tiếp</a><br />
                    {{-- <a href="" title="" id="delete-cart">Xóa giỏ hàng</a> --}}
                </div>
            </div>
        </div>
    @else
        <div id="wrapper" class="wp-inner clearfix mt-3">
            <div class="wp-img">
                <img src="{{ asset('public/images/no-cart.png') }}" alt="" class="ml-auto mr-auto">
                <div class="wp-btn d-flex justify-content-center">
                    <a href="{{ route('product.list') }}" type="button" class="text-white btn btn-danger">Mua sắm ngay</a>
                </div>
            </div>
        </div>
    @endif

@endsection
