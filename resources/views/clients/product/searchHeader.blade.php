@extends('layouts.client')

@section('title', 'Danh sách sản phẩm')

@section('content-right')
    <div class="secion" id="breadcrumb-wp">
        <div class="secion-detail">
            <ul class="list-item clearfix">
                <li>
                    <a href="" title="">Trang chủ</a>
                </li>
                <li>
                    <a href="" title="">Sản phẩm</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="section" id="list-product-wp">
        @if ($listProduct->count() > 0)
            <div class="section-head clearfix">
                <h3 class="section-title fl-left">Có <span class="text-danger">'{{ $listProduct->count() }}'</span> sản phẩm có từ khoá
                    <span class="text-danger">'{{ request()->input('key') }}'</span> được tìm thấy</h3>
                <div class="filter-wp fl-right">
                    {{-- <p class="desc">Hiển thị 45 trên 50 sản phẩm</p> --}}
                    <div class="form-filter">
                        <form method="POST" action="">
                            <select name="select" id="select-option-arrange"
                                data-url="{{ route('product.arrange.ajax') }}">
                                <option value="0">---Sắp xếp---</option>
                                <option value="1">Từ A-Z</option>
                                <option value="2">Từ Z-A</option>
                                <option value="3">Giá cao xuống thấp</option>
                                <option value="4">Giá thấp lên cao</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
            <div class="section-detail">
                <ul class="list-item clearfix" id="listProduct">
                    @foreach ($listProduct as $product)
                        <li>
                            <a href="{{ route('product.detail',['slug' => $product->Product->slug, 'id' => $product->Product->id]) }}" title="" class="thumb">
                                <img class="img_item_product" src="{{ asset($product->Product->thumbnail) }}">
                            </a>
                            <a href="{{ route('product.detail',['slug' => $product->Product->slug, 'id' => $product->Product->id]) }}" title=""
                                class="product-name">
                                @if ($product->config_id == '')
                                    {{ $product->Product->name }}
                                @else
                                    {{ $product->Product->name . ' - ' . $product->Config->storage_capacity . 'GB' }}
                                @endif
                            </a>
                            <div class="price">
                                <span class="new">{{ number_format($product->price, 0, '', ',') }}đ</span>
                            </div>
                            <div class="action clearfix btn-detail pb-2">
                                <a href="{{ route('product.detail',['slug' => $product->Product->slug, 'id' => $product->Product->id]) }}" title=""
                                    class="p-1 rounded text-center">Xem chi tiết <i class="fa-solid fa-eye"></i></a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="wp-no-see">
                <img src="{{ asset('public/images/icon_no_see.png') }}" alt="Không tìm thấy sản phẩm nào">
                <p class="pt-2">Không tìm thấy sản phẩm nào?</p>
            </div>
        @endif
    </div>
    <div class="wp-paging" id="">
        {{ $listProduct->appends(['key' => request()->input('key')])->links() }}
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
    <div class="section" id="filter-product-wp">
        <div class="section-head">
            <h3 class="section-title">Bộ lọc</h3>
        </div>
        <div class="section-detail">
            <form method="POST" action="">
                <table>
                    <tbody>
                        <tr>
                            <td><input id="price-1" type="radio" name="r-price" class="arrange-price"
                                    data-url="{{ route('product.arrange.ajax') }}"></td>
                            <td><label for="price-1">Dưới 500.000đ</label></td>
                        </tr>
                        <tr>
                            <td><input id="price-2" type="radio" name="r-price" class="arrange-price"
                                    data-url="{{ route('product.arrange.ajax') }}"></td>
                            <td><label for="price-2">500.000đ - 1.000.000đ</label></td>
                        </tr>
                        <tr>
                            <td><input id="price-3" type="radio" name="r-price" class="arrange-price"
                                    data-url="{{ route('product.arrange.ajax') }}"></td>
                            <td><label for="price-3">1.000.000đ - 5.000.000đ</label></td>
                        </tr>
                        <tr>
                            <td><input id="price-4" type="radio" name="r-price" class="arrange-price"
                                    data-url="{{ route('product.arrange.ajax') }}"></td>
                            <td><label for="price-4">5.000.000đ - 10.000.000đ</label></td>
                        </tr>
                        <tr>
                            <td><input id="price-5" type="radio" name="r-price" class="arrange-price"
                                    data-url="{{ route('product.arrange.ajax') }}"></td>
                            <td><label for="price-5">10.000.000đ - 20.000.000đ</label></td>
                        </tr>
                        <tr>
                            <td><input id="price-6" type="radio" name="r-price" class="arrange-price"
                                    data-url="{{ route('product.arrange.ajax') }}"></td>
                            <td><label for="price-6">Trên 20.000.000đ</label></td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    {{-- <div class="section" id="banner-wp">
        <div class="section-detail">
            <a href="{{ route('product.detail', $product->id) }}" title="" class="thumb">
                <img src="public/images/banner.png" alt="">
            </a>
        </div>
    </div> --}}
@endsection
