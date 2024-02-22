@extends('layouts.client')

@section('title', 'Thanh toán')

@section('content')
    <div class="secion" id="breadcrumb-wp">
        <div class="secion-detail">
            <ul class="list-item clearfix">
                <li>
                    <a href="" title="">Trang chủ</a>
                </li>
                <li>
                    <a href="" title="">Thanh toán</a>
                </li>
            </ul>
        </div>
    </div>
    <div id="wrapper" class="wp-inner clearfix">
        {!! Form::open(['url' => 'thanh-toan-thanh-cong.html', 'method' => 'POST']) !!} @csrf
        <div class="row">
            <div class="col-12 col-sm-6" id="customer-info-wp">
                <div class="section-head pl-3">
                    <h1 class="section-title">Thông tin khách hàng</h1>
                </div>
                <div class="section-detai pl-3">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            {!! Form::label('name', 'Tên khách hàng (*)', ['class' => 'form-label title-checkout']) !!}
                            {!! Form::text('name', '', ['class' => 'form-control', 'id' => 'name']) !!}
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            {!! Form::label('email', 'Email (*)', ['class' => 'form-label title-checkout']) !!}
                            {!! Form::email('email', '', ['class' => 'form-control', 'id' => 'email']) !!}
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            {!! Form::label('phone', 'Số điện thoại (*)', ['class' => 'form-label title-checkout']) !!}
                            {!! Form::number('phone', '', ['class' => 'form-control', 'id' => 'phone']) !!}
                            @error('phone')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            {!! Form::label('province-city', 'Tỉnh/Thành phố (*)', ['class' => 'form-label title-checkout']) !!}
                            {!! Form::select('province-city', array([0 => 'Chọn tỉnh/ thành phố'], $listCity), '', [
                                'class' => 'form-control',
                                'id' => 'province-city',
                                'data-url' => route('getDistrict'),
                            ]) !!}
                            @error('province-city')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            {!! Form::label('district', 'Quận/Huyện (*)', ['class' => 'form-label title-checkout']) !!}
                            {!! Form::select('district', ['0' => '---Chọn Quận/Huyện---'], '', [
                                'class' => 'form-control',
                                'id' => 'district',
                                'data-url' => route('getCommune'),
                            ]) !!}
                            @error('district')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            {!! Form::label('commune', 'Xã/Thị trấn (*)', ['class' => 'form-label title-checkout']) !!}
                            {!! Form::select('commune', ['0' => '---Chọn Xã/Thị trấn---'], '', [
                                'class' => 'form-control',
                                'id' => 'commune',
                            ]) !!}
                            @error('commune')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-12">
                            {!! Form::label('address', 'Địa chỉ', ['class' => 'form-label title-checkout']) !!}
                            {!! Form::text('address', '', [
                                'class' => 'form-control',
                                'id' => 'address',
                                'placeholder' => 'Ví dụ: 35, đường Nguyễn Trung Trức',
                            ]) !!}
                            @error('address')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-12">
                            {!! Form::label('note', 'Ghi chú (Nếu có)', ['class' => 'form-label title-checkout']) !!}
                            {!! Form::textarea('note', '', [
                                'class' => 'form-control text-note',
                                'id' => 'note',
                                'placeholder' => 'Ghi chú vào đây',
                            ]) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6" id="order-review-wp">
                <div class="section-head">
                    <h1 class="section-title">Thông tin đơn hàng</h1>
                </div>
                <div class="section-detail">
                    <table class="shop-table">
                        <thead>
                            <tr>
                                <td>Sản phẩm</td>
                                <td>Tổng</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (Cart::content() as $item)
                                <tr class="cart-item">
                                    <td class="product-name">{{ $item->name }}
                                        @if ($item->options->config != '')
                                            {{ '/' . $item->options->config . '/' . $item->options->color }}
                                        @endif
                                        <strong class="product-quantity">x
                                            {{ $item->qty }}</strong>
                                    </td>
                                    <td class="product-total">{{ number_format($item->total, 0, '', ',') . 'đ' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="order-total">
                                <td>Tổng đơn hàng:</td>
                                <td><strong class="total-price">{{ Cart::total(0, '', ',') . 'đ' }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                    <div id="payment-checkout-wp">
                        <ul id="payment_methods">
                            <li>
                                <input type="radio" checked id="payment-home" name="payment-method" value="payment-home">
                                <label for="payment-home">Thanh toán tại nhà</label>
                            </li>
                            <li>
                                <input type="radio" id="direct-payment" name="payment-method" value="direct-payment">
                                <label for="direct-payment">Thanh toán tại cửa hàng</label>
                            </li>

                        </ul>
                        <div class="checkout-online">
                            <span class="font-italic">Đặt hàng và thanh toán online</span>
                            <div class="wp-img-logo-payment">
                                <div class="row">
                                    <button type="submit" name="payment-vnpay" class="btn-payment-oline col-lg-4">
                                        <img src="public/images/Logo-VNPAY-QR-1.webp" alt="">
                                    </button>
                                </div>               
                            </div>
                        </div>
                    </div>
                    <div class="place-order-wp clearfix">
                        <input type="submit" id="order-now" value="Đặt hàng">
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
