@extends('layouts.client')

@section('title', 'title')

@section('content-right')
    <div class="secion" id="breadcrumb-wp">
        <div class="secion-detail">
            <ul class="list-item clearfix">
                <li>
                    <a href="{{ asset('home.index') }}" title="">Trang chủ</a>
                </li>
                <li>
                    <a href="" title="">Giới thiệu</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="section" id="detail-blog-wp">
        <div class="section-head clearfix">
            <h3 class="section-title">{{ $pageIntroduce->first()->title }}</h3>
        </div>
        <div class="section-detail">
            <span class="create-date">{{ $pageIntroduce->first()->created_at }}</span>
            <div class="detail">
                {!! $pageIntroduce->first()->content !!}
            </div>
        </div>
    </div>
@endsection
@section('content-left')
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
    {{-- <div class="section" id="banner-wp">
        <div class="section-detail">
            @foreach ($banners as $banner)
                <a href="{{ $banner->link }}" title="" class="thumb mb-2 d-block">
                    <img src="{{ asset($banner->thumbnail) }}" alt="">
                </a>
            @endforeach
        </div>
    </div> --}}
@endsection
