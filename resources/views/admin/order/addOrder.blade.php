@extends('layouts.admin')

@section('title', 'Thêm đơn hàng mới')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm đơn hàng mới
            </div>
            <div class="card-body">
                {{-- <div class="row"> --}}
                {!! Form::open(['url' => 'admin/order', 'method' => 'POST']) !!}@csrf
                <div class="modal-body">
                    <h6>Thông tin khách hàng</h6>
                    <div class="row">
                        {{-- <div class=""> --}}
                        <div class="col-12 col-sm-4">
                            {!! Form::label('phone', 'Số điện thoại (*)', ['class' => 'phone title']) !!}
                            <div class="d-flex align-items-center justify-content-between input-phone-check-number">
                                <div class="wp-input-phone">
                                    {!! Form::text('phone', '', ['id' => 'phone', 'class' => 'form-control ']) !!}

                                </div>
                                <div class="wp-button-check-number-phone cursor-pointer">
                                    <a data-url="{{ route('order.checkInfo') }}"
                                        class="btn btn-primary text-white btn-check">Kiểm tra<i
                                            class="pl-1 fa-solid fa-check"></i></a>
                                </div>
                            </div>
                            @error('phone')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-12 col-sm-4">
                            {!! Form::label('name', 'Họ và tên (*)', ['class' => 'name title']) !!}
                            {!! Form::text('name', '', ['id' => 'name', 'class' => 'form-control']) !!}
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-12 col-sm-4">
                            {!! Form::label('email', 'Email (*)', ['class' => 'email title']) !!}
                            {!! Form::text('email', '', ['id' => 'email', 'class' => 'form-control']) !!}
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-12 col-sm-4">
                            {!! Form::label('province-city', 'Tỉnh/Thành phố (*)', ['class' => 'title form-label title-checkout']) !!}
                            {!! Form::select('province-city', $listCity, '', [
                                'class' => 'form-control',
                                'id' => 'province-city',
                                'data-url' => route('getDistrict'),
                            ]) !!}
                            @error('province-city')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-12 col-sm-4">
                            {!! Form::label('district', 'Quận/Huyện (*)', ['class' => 'form-label title-checkout title']) !!}
                            {!! Form::select('district', ['0' => '---Chọn Quận/Huyện---'], '', [
                                'class' => 'form-control',
                                'id' => 'district',
                                'data-url' => route('getCommune'),
                            ]) !!}
                            @error('district')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-12 col-sm-4">
                            {!! Form::label('commune', 'Xã/Thị trấn (*)', ['class' => 'form-label title-checkout title']) !!}
                            {!! Form::select('commune', ['0' => '---Chọn Xã/Thị trấn---'], '', [
                                'class' => 'form-control',
                                'id' => 'commune',
                            ]) !!}
                            @error('commune')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-12 col-sm-4">
                            {!! Form::label('address', 'Địa chỉ (*)', ['class' => 'address title']) !!}
                            {!! Form::text('address', '', ['id' => 'address', 'class' => 'form-control']) !!}
                            @error('address')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-12 col-sm-8">
                            {!! Form::label('note', 'Ghi chú', ['class' => 'title']) !!}
                            {!! Form::text('note', '', ['id' => 'note', 'class' => 'form-control']) !!}
                            @error('note')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        {{-- </div> --}}
                    </div>

                    <h6 class="pt-3">Danh sách sản phẩm</h6>

                    <div class="wp-info-customer border p-2">

                        <div class="d-flex align-items-center justify-content-end">
                            <a data-url="{{ route('order.checkInfo') }}" data-toggle="modal" data-target="#exampleModal"
                                class="btn btn-primary text-white  cursor-pointer">Lưa chọn sản phẩm<i
                                    class="pl-2 fa-solid fa-cart-plus"></i></a>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-12 pt-2">
                                <table class="table table-responsive-sm table-striped ">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Ảnh sản phẩm</th>
                                            <th scope="col">Tên sản phẩm</th>
                                            <th scope="col">Số lượng</th>
                                            <th scope="col">Giá</th>
                                            <th scope="col">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-product-data" id="table-info-modal-product">
                                        @php
                                            $temp = 0;
                                        @endphp
                                        @foreach (Cart::content() as $item)
                                            @php
                                                $temp++;

                                                $image = asset($item->options->thumbnail);
                                                $name = $item->name . '/' . $item->options->color . '/' . $item->options->config;
                                                $price = number_format($item->price, 0, '', ',') . 'đ';
                                            @endphp
                                            <tr>
                                                <td>{{ $temp }}</td>
                                                <td class="wp_thumbnail"><img class="img_thumbnail"
                                                        src="{{ $image }}" alt=""></td>
                                                <td class="w-25">{{ $name }}</td>
                                                <td>
                                                    <span>{{ $item->qty }}</span>
                                                </td>
                                                <td>{{ $price }}</td>
                                                <td class="text-center"><a
                                                        class="btn btn-danger btn-delete-cart btn-sm rounded-0 text-white"
                                                        type="button" data-toggle="tooltip" data-placement="top"
                                                        data-rowId="{{ $item->rowId }}"
                                                        data-url="{{ route('order.deleteCart') }}" title="Delete">
                                                        <i class="fa fa-trash"></i></a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div class="wp-btn text-right">
                            <button type="submit" class="btn btn-add-order btn-primary text-white">Thêm đơn
                                hàng</button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
                {{-- </div> --}}
            </div>
        </div>
    </div>


    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" id="modal-dialog-add-product-order" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">LỰA CHỌN SẢN PHẨM</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {!! Form::open(['', 'method' => 'POST']) !!}@csrf
                <div class="modal-body">
                    <div class="wp-info-customer border p-2">
                        <div class="row">
                            <div class="col-12 col-sm-12">
                                {!! Form::text('keyword', '', [
                                    'id' => 'keyword',
                                    'class' => 'form-control',
                                    'placeholder' => 'Nhập tên của sản phẩm',
                                    'data-url' => route('order.searchAjax'),
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <h6 class="pt-3">Danh sách sản phẩm</h6>
                    <div class="wp-info-customer border p-2">
                        <table class="table table-responsive-sm table-striped ">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Ảnh sản phẩm</th>
                                    <th scope="col">Tên sản phẩm</th>
                                    <th scope="col">Màu sắc</th>
                                    <th scope="col">Cấu hình</th>
                                    <th scope="col">Số lượng</th>
                                    <th scope="col">Giá</th>
                                    <th scope="col">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="table-modal table-info-modal-product" id="table-info-modal-product">

                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Xong</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection
