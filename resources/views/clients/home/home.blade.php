@extends('layouts.client')

@section('title', 'Trang chủ')

@section('content-right')
    <div class="section" id="slider-wp">
        <div class="section-detail">
            @foreach ($sliders as $slider)
                <div class="item">
                    <img src="{{ asset($slider->thumbnail) }}" alt="">
                </div>
            @endforeach
        </div>
    </div>

    <div class="section" id="support-wp">
        <div class="section-detail">
            <ul class="list-item clearfix">
                <li>
                    <div class="thumb">
                        <img src="{{ asset('public/css/import/client/images/icon-1.png') }}">
                    </div>
                    <h3 class="title">Miễn phí vận chuyển</h3>
                    <p class="desc">Tới tận tay khách hàng</p>
                </li>
                <li>
                    <div class="thumb">
                        <img src="{{ asset('public/css/import/client/images/icon-2.png') }}">
                    </div>
                    <h3 class="title">Tư vấn 24/7</h3>
                    <p class="desc">1900.9999</p>
                </li>
                <li>
                    <div class="thumb">
                        <img src="{{ asset('public/css/import/client/images/icon-3.png') }}">
                    </div>
                    <h3 class="title">Tiết kiệm hơn</h3>
                    <p class="desc">Với nhiều ưu đãi cực lớn</p>
                </li>
                <li>
                    <div class="thumb">
                        <img src="{{ asset('public/css/import/client/images/icon-4.png') }}">
                    </div>
                    <h3 class="title">Thanh toán nhanh</h3>
                    <p class="desc">Hỗ trợ nhiều hình thức</p>
                </li>
                <li>
                    <div class="thumb">
                        <img src="{{ asset('public/css/import/client/images/icon-5.png') }}">
                    </div>
                    <h3 class="title">Đặt hàng online</h3>
                    <p class="desc">Thao tác đơn giản</p>
                </li>
            </ul>
        </div>
    </div>

    @if ($featuredProducts->count() > 0)
        <div class="section" id="feature-product-wp">
            <div class="section-head">
                <h3 class="section-title pt-3 pb-2">Sản phẩm nổi bật</h3>
            </div>
            <div class="section-detail">
                <ul class="list-item">
                    @foreach ($featuredProducts as $item)
                        <li>
                            <a href="{{ route('product.detail', ['slug' => $item->Product->slug]) }}" title="" class="thumb">
                                <img class="img_item_product" src="{{ asset($item->Product->thumbnail) }}">
                            </a>
                            <a href="{{ route('product.detail', ['slug' => $item->Product->slug]) }}" title=""
                                class="product-name">{{ $item->Product->name }}</a>
                            <div class="price">
                                <span class="new">{{ number_format($item->price, 0, '', ',') }}đ</span>
                            </div>
                            <div class="action clearfix btn-detail pb-2">
                                <a href="{{ route('product.detail', ['slug' => $item->Product->slug]) }}" title=""
                                    class="p-1 rounded text-center">Xem chi tiết <i class="fa-solid fa-eye"></i></a>
                            </div>

                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if ($saleProduct->count() > 0)
        <div class="section" id="feature-product-wp">
            <div class="section-head">
                <h3 class="section-title pt-3 pb-2">Sản phẩm Đang giảm giá</h3>
            </div>
            <div class="section-detail">
                <ul class="list-item">
                    @foreach ($saleProduct as $item)
                        <li>
                            <a href="{{ route('product.detail', ['slug' => $item->Product->slug]) }}" title="" class="thumb">
                                <img class="img_item_product" src="{{ asset($item->Product->thumbnail) }}">
                            </a>
                            <a href="{{ route('product.detail',['slug' => $item->Product->slug]) }}" title=""
                                class="product-name">
                                @if ($item->config_id == '')
                                    {{ $item->Product->name }}
                                @else
                                    {{ $item->Product->name . ' - ' . $item->Config->storage_capacity . 'GB - ' . $item->Color->name }}
                                @endif
                            </a>
                            <div class="price">
                                <span class="new">{{ number_format($item->price, 0, '', ',') }}đ</span>
                                <span class="old">{{ number_format($item->sale_off, 0, '', ',') }}đ</span>
                            </div>
                            <div class="action clearfix btn-detail pb-2">
                                <a href="{{ route('product.detail', ['slug' => $item->Product->slug]) }}" title=""
                                    class="p-1 rounded text-center">Xem chi tiết <i class="fa-solid fa-eye"></i></a>
                            </div>
                            <div class="home-product-item__sale-off">
                                <span class="home-product-item__sale-off-percent">{{ $item->discount }}%</span>
                                <span class="home-product-item__sale-off-label">GIẢM</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if ($phoneProducts->count() > 0)
        <div class="section" id="list-product-wp">
            <div class="section-head">
                <h3 class="section-title pt-3 pb-2">Điện thoại</h3>
            </div>
            <div class="section-detail">
                <ul class="list-item clearfix">
                    @foreach ($phoneProducts as $item)
                        <li>
                            <a href="{{ route('product.detail', ['slug' => $item->Product->slug]) }}" title="" class="thumb">
                                <img class="img_item_product" src="{{ asset($item->Product->thumbnail) }}">
                            </a>
                            <a href="{{ route('product.detail', ['slug' => $item->Product->slug]) }}" title=""
                                class="product-name">{{ $item->Product->name }}</a>
                            <div class="price">
                                <span class="new">{{ number_format($item->price, 0, '', ',') }}đ</span>
                                {{-- <span class="old">6.190.000đ</span> --}}
                            </div>
                            <div class="action clearfix btn-detail pb-2">
                                <a href="{{ route('product.detail', ['slug' => $item->Product->slug]) }}" title=""
                                    class="p-1 rounded text-center">Xem chi tiết <i class="fa-solid fa-eye"></i></a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if ($laptopProduct->count() > 0)
        <div class="section" id="list-product-wp">
            <div class="section-head">
                <h3 class="section-title pt-3 pb-2">LAPTOP</h3>
            </div>
            <div class="section-detail">
                <ul class="list-item clearfix">
                    @foreach ($laptopProduct as $item)
                        <li>
                            <a href="{{ route('product.detail', ['slug' => $item->Product->slug]) }}" title="" class="thumb">
                                <img class="img_item_product" src="{{ asset($item->Product->thumbnail) }}">
                            </a>
                            <a href="{{ route('product.detail', ['slug' => $item->Product->slug]) }}" title=""
                                class="product-name">{{ $item->Product->name }}</a>
                            <div class="price">
                                <span class="new">{{ number_format($item->price, 0, '', ',') }}đ</span>
                                {{-- <span class="old">6.190.000đ</span> --}}
                            </div>
                            <div class="action clearfix btn-detail pb-2">
                                <a href="{{ route('product.detail', ['slug' => $item->Product->slug])}}" title=""
                                    class="p-1 rounded text-center">Xem chi tiết <i class="fa-solid fa-eye"></i></a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

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

      {{-- Sản phẩm bán chạy --}}
    <div class="section" id="selling-wp">
        <div class="section-head">
            <h3 class="section-title">Sản phẩm bán chạy</h3>
        </div>
        <div class="section-detail">
            <ul class="list-item">
                @foreach ($productASC as $item)
                    <li class="clearfix list-item-link">
                        <a href="{{ route('product.detail', ['slug' => $item->Product->slug]) }}" title=""
                            role="button" class="w-100 wp-link">
                            <div class="row ml-0 mr-0">
                                <div class="col-4 p-0">
                                    <a href="{{ route('product.detail', ['slug' => $item->Product->slug]) }}"
                                        class="w-100" title="" class="thumb fl-left">
                                        <img src="{{ asset($item->thumbnail) }}" class="w-100 h-100"
                                            style="object-fit: cover" alt="Ảnh sản phẩm">
                                    </a>
                                </div>
                                <div class="col-8   ">
                                    <div class="info fl-right">
                                        <a href="{{ route('product.detail', ['slug' => $item->Product->slug]) }}"
                                            title="" class="product-name">{{ $item->product->name }}</a>
                                        <div class="price d-flex">
                                            @if ($item->Warehouse->discount > 0)
                                                <span
                                                    class="new">{{ number_format($item->Warehouse->sale_off, 0, '', ',') }}đ</span>
                                                <span
                                                    class="old">{{ number_format($item->Warehouse->price, 0, '', ',') }}đ</span>
                                            @else
                                                <span
                                                    class="new">{{ number_format($item->Warehouse->price, 0, '', ',') }}đ</span>
                                            @endif

                                        </div>

                                    </div>
                                </div>

                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
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
    
     {{-- Bài viết --}}
    <div class="section" id="selling-wp">
        <div class="section-head">
            <h3 class="section-title">BÀI VIẾT</h3>
        </div>
        <div class="section-detail">
            <ul class="list-item">
                @foreach ($blogs as $item)
                    <li class="clearfix list-item-link">
                        <a href="{{ route('blogs.detail', ['slug' => $item->slug]) }}" title="" role="button"
                            class="w-100 wp-link">
                            <div class="row ml-0 mr-0">
                                <div class="col-4 p-0">
                                    <a href="{{ route('blogs.detail', ['slug' => $item->slug]) }}" class="w-100"
                                        title="" class="thumb fl-left">
                                        <img src="{{ asset($item->thumbnail) }}" class="h-100 w-100"
                                            style="object-fit: cover" alt="Ảnh sản phẩm">
                                    </a>
                                </div>
                                <div class="col-8">
                                    <div class="info fl-right">
                                        <a href="{{ route('blogs.detail', ['slug' => $item->slug]) }}" title=""
                                            class="blogs-name">{{ $item->title }}</a>
                                    </div>
                                </div>

                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
