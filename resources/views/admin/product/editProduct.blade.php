@extends('layouts.admin')

@section('title', 'Thông tin chi tiết sản phẩm')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                THÔNG TIN CHI TIẾT SẢN PHẨM
            </div>
            <div class="card-body">
                {!! Form::open(['url' => 'admin/product/updateProduct/' . $product->id, 'method' => 'POST', 'files' => true]) !!} @csrf
                <div class="row">
                    <div class="col-sm-6 col-lg-8 col-md-6">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    {!! Form::label('name', 'Tên sản phẩm (*)', ['class' => 'title']) !!}
                                    {!! Form::text('name', $product->name, [
                                        'id' => 'name',
                                        'class' => 'form-control',
                                        'placeholder' => 'Nhập tên sản phẩm',
                                    ]) !!}
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-12">
                                <div class="form-group">
                                    {!! html_entity_decode(
                                        Form::label('slug', 'Slug (*) <span class="autofill title text-success">[Tự động điền]</span>', [
                                            'class' => 'title',
                                        ]),
                                    ) !!}
                                    {!! Form::text('slug', $product->slug, [
                                        'class' => 'form-control',
                                        'id' => 'slug',
                                        'placeholder' => 'Nhập slug sản phẩm',
                                    ]) !!}
                                    @error('slug')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('brand', 'Thương hiệu (*)', ['class' => 'title']) !!}
                                    <select class="form-control" name="brand" id="">
                                        <option value="">---Chọn thương hiệu---</option>
                                        @foreach ($listBrands as $item)
                                            <option @if ($product->Brand->id == $item->id) selected @endif
                                                value="{{ $item->id }}">
                                                {{ str_repeat('--', $item['level']) . $item['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('brand')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('cat', 'Danh mục (*)', ['class' => 'title']) !!}
                                    <select class="form-control" name="cat" id="">
                                        <option value="">---Chọn danh mục---</option>
                                        @foreach ($listProductCats as $item)
                                            <option @if ($product->productCat->id == $item->id) selected @endif
                                                value="{{ $item->id }}">
                                                {{ str_repeat('--', $item['level']) . $item['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cat')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-12">
                                <div class="form-group">
                                    {!! Form::label('desc', 'Mô tả ngắn (*)', ['class' => 'title']) !!}
                                    {!! Form::textarea('desc', $product->desc, ['class' => 'form-control', 'id' => 'desc', 'rows' => 10]) !!}
                                    @error('desc')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-12">
                                <div class="form-group">
                                    {!! Form::label('content', 'Mô tả chi tiết (*)', ['class' => 'title']) !!}
                                    {!! Form::textarea('content', $product->content, ['class' => 'form-control', 'id' => 'content', 'rows' => 10]) !!}
                                    @error('content')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="title pl-3 d-flex" for="">Trạng thái (*)</label>
                                        <div class="form-check">
                                            {!! Form::radio('status', 'pending', $product->status == 'pending', [
                                                'class' => 'form-check-input',
                                                'id' => 'exampleRadios1',
                                            ]) !!}
                                            {!! Form::label('exampleRadios1', 'Chờ duyệt', ['class' => 'form-check-label']) !!}
                                        </div>
                                        <div class="form-check">
                                            {!! Form::radio('status', 'public', $product->status == 'public', [
                                                'class' => 'form-check-input',
                                                'id' => 'exampleRadios2',
                                            ]) !!}
                                            {!! Form::label('exampleRadios2', 'Công khai', ['class' => 'form-check-label']) !!}
                                        </div>
                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row pl-3 pr-3">
                                <div class="col-sm-4 form-group">
                                    {!! Form::label('create_at', 'Ngày tạo', ['class' => 'title']) !!}
                                    {!! Form::text('create_at', $product->created_at, [
                                        'id' => 'create_at',
                                        'class' => 'form-control',
                                        'disabled' => 'disabled',
                                    ]) !!}
                                    @error('create_at')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-4 form-group">
                                    {!! Form::label('updated_at', 'Cập nhật gần nhất', ['class' => 'title']) !!}
                                    {!! Form::text('updated_at', $product->updated_at, [
                                        'id' => 'updated_at',
                                        'class' => 'form-control',
                                        'disabled' => 'disabled',
                                    ]) !!}
                                    @error('update_at')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-4 form-group">
                                    {!! Form::label('user-created', 'Người tạo', ['class' => 'title']) !!}
                                    {!! Form::text('user-created', $product->user->name, [
                                        'id' => 'user-created',
                                        'class' => 'form-control',
                                        'disabled' => 'disabled',
                                    ]) !!}
                                    @error('update_at')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-12">
                                {!! Form::label('thumbnail', 'Ảnh đã tải lên', ['class' => 'title form-label']) !!}
                                <div class="wp-img-thumbnail pb-3">
                                    <img class="img-thumbnail" src="{{ asset($product->thumbnail) }}" alt="">
                                </div>
                                <div class="form-group">
                                    {{-- {!! Form::label('thumbnail', 'Chọn ảnh nổi bật (*)', ['class' => 'title form-label']) !!} --}}
                                    {!! Form::file('thumbnail', ['class' => 'form-control', 'id' => 'thumbnail']) !!}
                                    @error('thumbnail')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-12">
                                {!! Form::label('listImages', 'Ảnh chi tiết', ['class' => 'title form-label']) !!}
                                <div class="row">
                                    @foreach ($listImages as $image)
                                        <div class="col-12 col-sm-4">
                                            <div class="wp-img-thumbnail pb-3">
                                                <img class="img-list border rounded " src="{{ asset($image) }}"
                                                    alt="">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="form-group">
                                    {{-- {!! Form::label('listFile', 'Chọn ảnh chi tiết', ['class' => 'title form-label']) !!} --}}
                                    {!! Form::file('listFile[]', ['class' => 'form-control', 'id' => 'listFile', 'multiple' => 'multiple']) !!}
                                    @error('file')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--  --}}
                    <div class="col-sm-6 col-lg-4 col-md-6">
                        <div class="row">
                            {!! Form::label('', 'Thuộc tính sản phẩm', ['class' => 'title form-label pl-3']) !!}
                            <div class="col-12 col-sm-12"
                                @foreach ($warehouses as $warehouse)
                            @if ($warehouse->color_id == '' && $warehouse->config_id == '')
                            style="display: block"
                            @else
                            style="display: none"
                            @endif @endforeach>
                                <div class="border rounded mb-3">
                                    <div class="mt-1">
                                        <span class="text-item">Không: </span>
                                        <input type="checkbox" @foreach ($warehouses as $warehouse)
                                        @if ($warehouse->color_id == '')
                                            checked
                                        @endif @endforeach name="color[]" id="0"
                                            value=""style="width: 20px">
                                        <label for="0" class="cursor-pointer">Khác</label>
                                    </div>
                                    <div class="" id="0_isChecked"
                                        @foreach ($warehouses as $warehouse)
                                    @if ($warehouse->color_id == '' && $warehouse->config_id == '')
                                        style="display: block"
                                        @else
                                        style="display: none"
                                    @endif @endforeach>
                                        <div class="row">
                                            <div class="d-flex align-items-center mb-2 ml-2 mr-2">
                                                <div class="col-sm-4">
                                                    <input type="number" name="quantity" class="form-control"
                                                        placeholder="Số lượng"
                                                        @foreach ($warehouses as $warehouse)
                                                        @if ($warehouse->color_id == '' && $warehouse->config_id == '')
                                                            value={{ $warehouse->quantity }}
                                                        @endif @endforeach>
                                                </div>
                                                <div class="col-sm-4">
                                                    <input type="text" name="price-old" class="price form-control"
                                                        placeholder="Giá"
                                                        title="Giá mặc định"
                                                        @foreach ($warehouses as $warehouse)
                                                        @if ($warehouse->color_id == '' && $warehouse->config_id == '')
                                                            value={{ number_format($warehouse->price, 0, '', ',')}}
                                                        @endif @endforeach>
                                                </div>
                                                <div class="col-sm-4">
                                                    <input type="text" name="price-new" class="price form-control"
                                                        placeholder="Giá mới"
                                                        title="Giá khuyến mãi"
                                                        @foreach ($warehouses as $warehouse)
                                                        @if ($warehouse->color_id == '' && $warehouse->config_id == '')
                                                            value={{ number_format($warehouse->sale_off, 0, '', ',')}}
                                                        @endif @endforeach>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    @error('color')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            @foreach ($listColors as $color)
                                <div class="col-12 col-sm-12">
                                    <div class="border rounded mb-3">
                                        <div class="mt-1">
                                            <span class="text-item">Chọn màu: </span>
                                            <input
                                                @foreach ($warehouses as $warehouse)
                                                                @if ($warehouse->color_id == $color->id)
                                                                    checked
                                                                @endif @endforeach
                                                type="checkbox" name="color[{{ $color->id }}]" class="checked-item "
                                                id="{{ $color->id }}" value="{{ $color->id }}"
                                                style="width: 20px">
                                            <label for="{{ $color->id }}"
                                                class="cursor-pointer">{{ $color->name }}</label>
                                        </div>
                                        <div class="wp-form-quantity"
                                            @foreach ($warehouses as $warehouse)
                                        @if ($warehouse->color_id == $color->id)
                                            style="display: block"
                                        @endif @endforeach
                                            id="checked-{{ $color->id }}">
                                            {{-- <div class="row"> --}}
                                                @foreach ($config as $item)
                                                    <div class="wp-option p-2">
                                                        <div class="col-sm-12 pb-2">
                                                            <div class="wp-check">
                                                                <input type="checkbox" class="checkbox-item-config"
                                                                    @foreach ($warehouses as $warehouse)
                                                                @if ($warehouse->config_id == $item->id && $warehouse->color_id == $color->id)
                                                                    checked
                                                                @endif @endforeach
                                                                    id="fc{{ $item->id . $color->id }}"
                                                                    name="config[{{ $item->id }}]"
                                                                    data-id="{{ $item->id . $color->id }}"
                                                                    value="{{ $item->id . $color->id }}">
                                                                <label class="cursor-pointer"
                                                                    for="fc{{ $item->id . $color->id }}">{{ $item->storage_capacity }}-GB</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 pb-2">
                                                            <div class="wp-form-text p4">
                                                                <input type="text"
                                                                    class="form-control text-item-quantity"
                                                                    name="colorQuantity[{{ $item->id }}][{{ $color->id }}]"
                                                                    @foreach ($warehouses as $warehouse)
                                                                @if ($warehouse->config_id == $item->id && $warehouse->color_id == $color->id)
                                                                    value="{{ $warehouse->quantity }}"
                                                                {{-- @else
                                                                    disabled --}}
                                                                @endif @endforeach
                                                                    placeholder="Số lượng"
                                                                    id="quantity-{{ $item->id . $color->id }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 pb-2">
                                                            <div class="wp-form-text p4">
                                                                <input
                                                                    @foreach ($warehouses as $warehouse)
                                                                @if ($warehouse->config_id == $item->id && $warehouse->color_id == $color->id)                                          
                                                                value="{{ number_format($warehouse->price,0,'',',') }}"
                                                                {{-- @else
                                                                    disabled --}}
                                                                @endif @endforeach
                                                                    type="text" class="price form-control text-item-price"
                                                                    name="price[{{ $item->id }}][{{ $color->id }}]"
                                                                    placeholder="Giá"
                                                                    title="Giá mặc định"
                                                                    id="price-{{ $item->id . $color->id }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 pb-2">
                                                            <div class="wp-form-text p4">
                                                                <input
                                                                    @foreach ($warehouses as $warehouse)
                                                                @if ($warehouse->config_id == $item->id && $warehouse->color_id == $color->id)
                                                                    value="{{ number_format($warehouse->sale_off, 0,'',',') }}"
                                                                {{-- @else
                                                                    disabled --}}
                                                                @endif @endforeach
                                                                    type="text"
                                                                    class="price form-control text-item text-item-sale-off"
                                                                    name="sale_off[{{ $item->id }}][{{ $color->id }}]"
                                                                    placeholder="Giá mới"
                                                                    title="Giá khuyến mãi"
                                                                    id="sale_off-{{ $item->id . $color->id }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            {{-- </div> --}}
                                        </div>
                                    </div>
                                </div>
                                @error('color')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            @endforeach
                        </div>
                    </div>
                </div>
                {!! Form::submit('Cập nhật', ['class' => 'btn btn-primary btn-submit', 'name' => 'btn_add']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
