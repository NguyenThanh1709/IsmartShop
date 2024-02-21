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
                    <a href="" title="">Liên hệ</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="section" id="detail-blog-wp">
        <div class="section-head clearfix">
            <h3 class="section-title">{{ $contacts->first()->title }}</h3>
        </div>
        <div class="section-detail">
            <span class="create-date">{{ $contacts->first()->created_at }}</span>
            <div class="detail">
                {!! $contacts->first()->content !!}
            </div>
        </div>
    </div>
    <div class="wp-maps">
         <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3930.211191081524!2d105.14170387494548!3d9.916361490184803!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a0b61bcce77cf1%3A0x945249dac98e1952!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBLacOqbiBHaWFuZw!5e0!3m2!1svi!2s!4v1682609935881!5m2!1svi!2s"
            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe> 

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
            @foreach ($banners as $banner)
                <a href="{{ $banner->link }}" title="" class="thumb mb-2 d-block">
                    <img src="{{ asset($banner->thumbnail) }}" alt="">
                </a>
            @endforeach
        </div>
    </div>
@endsection
