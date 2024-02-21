@extends('layouts.admin')

@section('title', 'Thêm sản phẩm')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm sản phẩm
            </div>
            <div class="card-body">
                {!! Form::open(['url' => 'admin/product/storeProduct', 'method' => 'POST', 'files' => true]) !!} @csrf
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="form-group">
                            {!! Form::label('name', 'Tên sản phẩm (*)', ['class' => 'title']) !!}
                            {!! Form::text('name', '', ['id' => 'name', 'class' => 'form-control', 'placeholder' => 'Nhập tên sản phẩm']) !!}
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="form-group">
                            {!! html_entity_decode(
                                Form::label('slug', 'Slug (*) <span class="autofill title text-success">[Tự động điền]</span>', [
                                    'class' => 'title',
                                ]),
                            ) !!}
                            {!! Form::text('slug', '', ['class' => 'form-control', 'id' => 'slug', 'placeholder' => 'Nhập slug sản phẩm']) !!}
                            @error('slug')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            {!! Form::label('brand', 'Thương hiệu (*)', ['class' => 'title']) !!}
                            <select class="form-control" name="brand" id="">
                                <option value="">---Chọn thương hiệu---</option>
                                @foreach ($listBrands as $item)
                                    <option @if (old('brand') == $item->id) @selected(true) @endif
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
                                    <option @if (old('cat') == $item->id) @selected(true) @endif
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
                </div>
               <div class="form-group">
                    {!! html_entity_decode(
                        Form::label('color', 'Màu sắc- cấu hình - số lượng <span class="note">(Áp dụng với điện thoại)</span>', [
                            'class' => 'title',
                        ]),
                    ) !!}        
                    <small class="text-danger error-price"></small>
                    @if($errors->has('price-old') || $errors->has('price-new'))
                        <small class="text-danger">
                            @error('price-old')
                                {{ $message }}
                            @enderror
                        </small>
                    @endif
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="border rounded mb-3">
                                <div class="mt-1">
                                    <span class="text-item">Không: </span>
                                    <input type="checkbox" checked name="color[]" id="0"
                                        value=""style="width: 20px">
                                    <label for="0" class="cursor-pointer">Khác</label>
                                </div>
                                <div class="" id="0_isChecked">
                                    <div class="row">
                                        <div class="d-flex align-items-center flex-wrap mb-2 ml-2 mr-2">
                                            <div class="col-12 col-sm-4 wp-input">
                                                <input type="number"  name="quantity" class="form-control"
                                                    placeholder="Số lượng">
                                            </div>
                                            <div class="col-12 col-sm-4 wp-input">
                                                <input type="text"  name="price-old" id="price-old" class="price form-control"
                                                    placeholder="Giá">
                                            </div>
                                            <div class="col-12 col-sm-4 wp-input">
                                                <input type="text"  name="price-new" id="price-new" class="price form-control"
                                                    placeholder="Giá mới">
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
                            <div class="col-12 col-sm-6">
                                <div class="border rounded mb-3">
                                    <div class="mt-1">
                                        <span class="text-item">Chọn màu: </span>
                                        
                                        <input type="checkbox" name="color[{{ $color->id }}]" class="checked-item "
                                            id="{{ $color->id }}" value="{{ $color->id }}" style="width: 20px">
                                        <label for="{{ $color->id }}"
                                            class="cursor-pointer">{{ $color->name }}</label>
                                    </div>
                                    <div class="wp-form-quantity" id="checked-{{ $color->id }}">
                                        <div class="row">
                                            @foreach ($config as $item)
                                                <div class="d-flex align-items-center flex-wrap p-2">
                                                    <div class="col-12 col-sm-3 wp-input">
                                                        <div class="wp-check">
                                                            <input type="checkbox" class="checkbox-item-config"
                                                                id="fc{{ $item->id . $color->id }}"
                                                                name="config[{{ $item->id }}]"
                                                                data-id="{{ $item->id . $color->id }}"
                                                                value="{{ $item->id . $color->id }}">
                                                            <label class="cursor-pointer"
                                                                for="fc{{ $item->id . $color->id }}">{{ $item->storage_capacity }}-GB</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-3 wp-input">
                                                        <div class="wp-form-text p4">
                                                            <input type="number" class="form-control text-item-quantity"
                                                                name="colorQuantity[{{ $item->id }}][{{ $color->id }}]"
                                                                disabled placeholder="Số lượng"
                                                                id="quantity-{{ $item->id . $color->id }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-3 wp-input">
                                                        <div class="wp-form-text p4">
                                                            <input type="text"  class="form-control text-item-price price"
                                                                name="price[{{ $item->id }}][{{ $color->id }}]"
                                                                disabled placeholder="Giá"
                                                                id="price-{{ $item->id . $color->id }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-3 wp-input">
                                                        <div class="wp-form-text p4">
                                                            <input type="text"  
                                                                class="form-control text-item text-item-sale-off price"
                                                                name="sale_off[{{ $item->id }}][{{ $color->id }}]"
                                                                disabled placeholder="Giá mới"
                                                                id="sale_off-{{ $item->id . $color->id }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                          
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        @endforeach 
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('desc', 'Mô tả ngắn (*)', ['class' => 'title']) !!}
                    {!! Form::textarea('desc', '', ['class' => 'form-control', 'id' => 'desc', 'rows' => 10]) !!}
                    @error('desc')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    {!! Form::label('content', 'Mô tả chi tiết (*)', ['class' => 'title']) !!}
                    {!! Form::textarea('content', '', ['class' => 'form-control', 'id' => 'content', 'rows' => 10]) !!}
                    @error('content')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="title" for="">Trạng thái (*)</label>
                    <div class="form-check">
                        {!! Form::radio('status', 'pending', $checked = true, ['class' => 'form-check-input', 'id' => 'exampleRadios1']) !!}
                        {!! Form::label('exampleRadios1', 'Chờ duyệt', ['class' => 'form-check-label']) !!}
                    </div>
                    <div class="form-check">
                        {!! Form::radio('status', 'public', $checked = false, ['class' => 'form-check-input', 'id' => 'exampleRadios2']) !!}
                        {!! Form::label('exampleRadios2', 'Công khai', ['class' => 'form-check-label']) !!}
                    </div>
                    @error('status')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            {!! Form::label('thumbnail', 'Chọn ảnh nổi bật (*)', ['class' => 'title form-label']) !!}
                            {!! Form::file('thumbnail', ['class' => 'form-control', 'id' => 'thumbnail']) !!}
                            @error('thumbnail')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::label('listFile', 'Chọn ảnh chi tiết', ['class' => 'title form-label']) !!}
                            {!! Form::file('listFile[]', ['class' => 'form-control', 'id' => 'listFile', 'multiple' => 'multiple']) !!}
                            @error('file')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                {!! Form::submit('Thêm mới', ['class' => 'btn btn-primary btn-submit', 'name' => 'btn_add']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
