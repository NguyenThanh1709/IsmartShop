@extends('layouts.client')

@section('title', 'title')

@section('content-right')
    <div class="secion" id="breadcrumb-wp">
        <div class="secion-detail">
            <ul class="list-item clearfix">
                <li>
                    <a href="" title="">Trang chủ</a>
                </li>
                <li>
                    <a href="" title="">Blog</a>
                </li>
                <li>
                    <a href="" title="">Chi tiết blog</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="section" id="detail-blog-wp">
        <div class="section-head clearfix">
            <h3 class="section-title">{{ $infoPost->title }}</h3>
        </div>
        <div class="section-detail">
            <span class="create-date">{{ $infoPost->created_at }}</span>
            <div class="detail">
                {!! $infoPost->content !!}
            </div>
        </div>
    </div>
    <div class="section" id="social-wp">
        <div class="section-detail">
            <div class="fb-like" data-href="" data-layout="button_count" data-action="like" data-size="small"
                data-show-faces="true" data-share="true"></div>
            <div class="g-plusone-wp">
                <div class="g-plusone" data-size="medium"></div>
            </div>
            <div class="fb-comments" id="fb-comment" data-href="" data-numposts="5"></div>
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
    <div class="section" id="banner-wp">
        <div class="section-detail">
            <a href="?page=detail_blog_product" title="" class="thumb">
                <img src="public/images/banner.png" alt="">
            </a>
        </div>
    </div>
@endsection
