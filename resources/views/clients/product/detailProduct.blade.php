@extends('layouts.client')

@section('title', 'Danh sách sản phẩm')

@section('content-right')
    <div class="secion" id="breadcrumb-wp">
        <div class="secion-detail">
            <ul class="list-item clearfix pb-3">
                <li>
                    <a href="" title="">Trang chủ</a>
                </li>
                <li>
                    <a href="" title="">Sản phẩm</a>
                </li>
                <li>
                    <a href="" title="">Chi tiết</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="section" id="detail-product-wp">
        <div class="section-detail clearfix">
            <div class="thumb-wp fl-left">
                <a href="" title="" id="main-thumb">
                    <img id="zoom" src="{{ asset($warehouse->Product->thumbnail) }}"
                        data-zoom-image="{{ asset($warehouse->Product->thumbnail) }}" />
                </a>
                <div id="list-thumb">
                    @foreach ($listImages as $img)
                        @php
                            $img = str_replace('\\', '', $img);
                        @endphp
                        <a href="" data-image="{{ asset($img) }}" data-zoom-image="{{ asset($img) }}">
                            <img id="zoom" src="{{ asset($img) }}" />
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="thumb-respon-wp fl-left">
                <img src="{{ asset($warehouse->Product->thumbnail) }}" alt="">
            </div>
            <div class="info fl-right">
                <h3 class="product-name">{{ $warehouse->Product->name }}</h3>
                <div class="desc">{!! $warehouse->Product->desc !!}</div>
                @if ($listColor->count() > 1)
                    <div id="color">
                        @foreach ($listColor as $key => $color)
                            <a href="" data-url="{{ route('getDataColor') }}" id="{{ $color->color_id }}"
                                data-id="{{ $warehouse->Product->id }}"
                                class="color-btn mb-2 @if ($key == 0) active @endif">Màu
                                {{ $color->color->name }}</a>
                        @endforeach
                    </div>
                @endif

                <div id="config" class="pb-3"></div>

                <div class="num-product">
                    <span class="title">Sản phẩm:</span>

                    @if ($warehouse->quantity > 0)
                        <span class="status">Còn hàng (Số lượng còn: {{ $warehouse->quantity }})</span>
                    @else
                        <span class="status">Hết hàng</span>
                    @endif
                </div>
                <div id="price">
                    @if ($warehouse->discount > 0)
                        <span class="price new">{{ number_format($warehouse->sale_off, 0, '', ',') }}đ</span>
                        <span class="price old pl-4">{{ number_format($warehouse->price, 0, '', ',') }}đ</span>
                    @else
                        <span class="price new">{{ number_format($warehouse->price, 0, '', ',') }}đ</span>
                    @endif
                </div>
                <div id="num-order-wp">
                    <a title="" id="minus"><i class="fa fa-minus"></i></a>
                    <input type="text" name="num-order" value="1" max="{{ $warehouse->quantity }}" id="num-order">
                    <a title="" id="plus"><i class="fa fa-plus"></i></a>
                </div>
                <div id="wp-btn-card">
                    @if ($warehouse->quantity > 0)
                        <a href="" data-url="{{ route('addCart') }}" data-id="{{ $warehouse->product_id }}"
                            title="Thêm giỏ hàng" class="add-cart" data-url-cart={{ route('cart') }}>
                            Thêm giỏ hàng</a>
                    @else
                        <button class="btn btn-dark">Tạm hết hàng</button>
                    @endif
                </div>

            </div>
        </div>
    </div>
    <div class="section wp_content" id="post-product-wp">
        <div class="section-head">
            <h3 class="section-title">Mô tả sản phẩm</h3>
        </div>
        <div class="section-detail wp-content-product">
            {!! $warehouse->Product->content !!}
        </div>
        <div class="see_next">
            <a id="see_next_link">Xem Thêm</a>
        </div>
    </div>

    <div class="section" id="same-category-wp">
        <div class="section-head">
            <h3 class="section-title">Cùng chuyên mục</h3>
        </div>
        <div class="section-detail">
            <ul class="list-item clearfix" id="listProduct">
                @foreach ($sameItem as $item)
                    <li>
                        <a href="{{ route('product.detail',['slug' => $item->slug]) }}" title="" class="thumb">
                            <img class="img_item_product" src="{{ asset($item->thumbnail) }}">
                        </a>
                        <a href="{{ route('product.detail', ['slug' => $item->slug]) }}" title="" class="product-name">
                            {{ $item->name }}
                        </a>
                        <div class="price">
                            @if ($item->discount > 0)
                                <span class="new">{{ number_format($item->sale_off, 0, '', ',') }}đ</span>
                                <span class="old">{{ number_format($item->price, 0, '', ',') }}đ</span>
                            @else
                                <span class="new">{{ number_format($item->price, 0, '', ',') }}đ</span>
                            @endif
                        </div>
                        <div class="action clearfix btn-detail pb-2">
                            <a href="{{ route('product.detail',['slug' => $item->slug, 'id'=>$item->id]) }}" title=""
                                class="p-1 rounded text-center">Xem chi tiết <i class="fa-solid fa-eye"></i></a>
                        </div>
                    </li>
                @endforeach

            </ul>
        </div>

    </div>
@endsection

@section('content-left')
    <div class="section" id="category-product-wp">
        <div class="section-head">
            <h3 class="section-title">Danh mục sản phẩm</h3>
        </div>
        <div class="secion-detail">
            {!! $menu !!}
        </div>
    </div>
    <div class="section" id="banner-wp">
        <div class="section-detail">
            @foreach ($banners as $banner)
                <a href="{{ $banner->link }}" title="" class="thumb mb-2 d-block">
                    <img src="{{ asset($banner->thumbnail) }}" alt="">
                </a>
            @endforeach
        </div>
    </div>
@endsection
