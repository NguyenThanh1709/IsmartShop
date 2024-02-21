<!DOCTYPE html>
<html>

<head>
    <title>ISMART STORE</title>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('public/css/import/client/css/bootstrap/bootstrap-theme.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
        integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('public/css/import/client/reset.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/css/import/client/css/carousel/owl.carousel.css') }}" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('public/css/import/client/css/carousel/owl.theme.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/css/import/client/css/font-awesome/css/font-awesome.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('public/css/import/client/style.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/css/import/client/responsive.css') }}" rel="stylesheet" type="text/css" />
    <title>@yield('title')</title>
</head>

<body>
    <div id="site">
        <div id="container">
            <div id="header-wp">
                <div id="head-top" class="clearfix">
                    <div class="wp-inner">
                        <a href="" title="" id="payment-link" class="fl-left">Hình thức thanh toán</a>
                        <div id="main-menu-wp" class="fl-right">
                            <ul id="main-menu" class="clearfix">
                                <li>
                                    <a href="{{ route('home.index') }}" title="">Trang chủ</a>
                                </li>
                                <li>
                                    <a href="{{ route('product.list') }}" title="">Sản phẩm</a>
                                </li>
                                <li>
                                    <a href="{{ route('blogs.list') }}" title="">Blog</a>
                                </li>
                                <li>
                                    <a href="{{ route('introduce.index') }}" title="">Giới thiệu</a>
                                </li>
                                <li>
                                    <a href="{{ route('contact.index') }}" title="">Liên hệ</a>
                                </li>
                            </ul>
                        </div>
                        <div id="btn-respon" class="fl-right"><i class="fa fa-bars" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                <div id="head-body" class="clearfix">
                    <div class="wp-inner">
                        <a href="{{ route('home.index') }}" title="" id="logo" class="fl-left"><img
                                src="{{ asset('public/css/import/client/images/logo.png') }}" /></a>
                        <div id="search-wp" class="fl-left">
                            <form method="GET" class="form-search " action="{{ route('prodcut.search') }}">
                                <input type="text" name="key" id="s"
                                    data-url="{{ route('prodcut.searchAjax') }}" value="{{ request()->input('key') }}"
                                    placeholder="Nhập từ khóa tìm kiếm tại đây!">
                                <button type="submit" id="sm-s">Tìm kiếm</button>
                            </form>
                            <ul class="wp-suggest rounded bg-light" id="wp-suggest">
                            </ul>
                        </div>
                        <div id="action-wp" class="fl-right">
                            <div id="advisory-wp" class="fl-left">
                                <span class="title">Tư vấn</span>
                                <span class="phone">0987.654.321</span>
                            </div>

                            <i class="icon-search-mobile fa-solid fa-magnifying-glass"></i>
                            <a href="{{ route('cart') }}" title="giỏ hàng" id="cart-respon-wp" class="fl-right">
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                <span id="num_mobile">{{ Cart::count() }}</span>
                            </a>

                            <div id="cart-wp" class="fl-right">

                                <div id="btn-cart">
                                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                    <span id="num">{{ Cart::count() }}</span>
                                </div>
                                <div id="dropdown">
                                    <div class="wp-noti">
                                        <p class="desc">Có <span class="number-in-cart">
                                                {{ Cart::count() }}</span>
                                            sản phẩm trong giỏ hàng</p>
                                        <ul class="list-cart">
                                            @foreach (Cart::content() as $row)
                                                <li class="clearfix list-cart-show">
                                                    <a href="{{ route('cart') }}" title=""
                                                        class="thumb fl-left ">
                                                        <img src="{{ asset($row->options->thumbnail) }}"
                                                            alt="">
                                                    </a>
                                                    <div class="info fl-right">
                                                        <a href="{{ route('cart') }}" title=""
                                                            class="product-name">{{ $row->name }}
                                                            @if ($row->options->config != '')
                                                                {{ '/' . $row->options->config . '/' . $row->options->color }}
                                                            @endif
                                                        </a>
                                                        <p class="price">
                                                            {{ number_format($row->price, 0, '', ',') . 'đ' }}</p>
                                                        <p class="qty">Số lượng:
                                                            <span>{{ $row->qty }}</span>
                                                        </p>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="total-price clearfix">
                                            <p class="title fl-left">Tổng:</p>
                                            <p id="total-cart" class="price fl-right">{{ Cart::total(0, ',', '.') }}đ
                                            </p>
                                        </div>
                                        <div class="action-cart clearfix">
                                            <a href="{{ route('cart') }}" title="Giỏ hàng"
                                                class="view-cart fl-left">Giỏ
                                                hàng</a>
                                            <a href="{{ route('checkout.index') }}" title="Thanh toán"
                                                class="checkout fl-right">Thanh
                                                toán</a>
                                        </div>
                                    </div>
                                    <div id="cart_null" class="wp_img_prodcut_null">
                                        <img src="{{ asset('public/images/no-cart.png') }}" alt="">
                                        <p>Chưa có sản phẩm</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="search-mobile">
                    <form method="GET" class="" action="{{ route('prodcut.search') }}">
                        <input type="text" name="key" id="s"
                            data-url="{{ route('prodcut.searchAjax') }}" value="{{ request()->input('key') }}"
                            placeholder="Nhập từ khóa tìm kiếm tại đây!" class="">
                        <button type="submit" id="sm-s">Tìm kiếm</button>
                    </form>
                </div>
            </div>

            <div id="main-content-wp" class="home-page clearfix">
                <div class="wp-inner">
                    @yield('content')
                    <div class="main-content fl-right">
                        @yield('content-right')
                    </div>
                    <div class="sidebar fl-left">
                        @yield('content-left')
                    </div>
                </div>
            </div>

            <div id="footer-wp">
                <div id="foot-body">
                    <div class="wp-inner clearfix">
                        <div class="block" id="info-company">
                            <h3 class="title">ISMART</h3>
                            <p class="desc">ISMART luôn cung cấp luôn là sản phẩm chính hãng có thông tin rõ ràng,
                                chính sách ưu đãi cực lớn cho khách hàng có thẻ thành viên.</p>
                            <div id="payment">
                                <div class="thumb">
                                    <img src="{{ asset('public/css/import/client/images/img-foot.png') }}"
                                        alt="">
                                </div>
                            </div>
                        </div>
                        <div class="block menu-ft" id="info-shop">
                            <h3 class="title">Thông tin cửa hàng</h3>
                            <ul class="list-item">
                                <li>
                                    <p>106 - Trần Bình - Cầu Giấy - Hà Nội</p>
                                </li>
                                <li>
                                    <p>0987.654.321 - 0989.989.989</p>
                                </li>
                                <li>
                                    <p>vshop@gmail.com</p>
                                </li>
                            </ul>
                        </div>
                        <div class="block menu-ft policy" id="info-shop">
                            <h3 class="title">Chính sách mua hàng</h3>
                            <ul class="list-item">
                                <li>
                                    <a href="" title="">Quy định - chính sách</a>
                                </li>
                                <li>
                                    <a href="" title="">Chính sách bảo hành - đổi trả</a>
                                </li>
                                <li>
                                    <a href="" title="">Chính sách hội viện</a>
                                </li>
                                <li>
                                    <a href="" title="">Giao hàng - lắp đặt</a>
                                </li>
                            </ul>
                        </div>
                        <div class="block" id="newfeed">
                            <h3 class="title">Bảng tin</h3>
                            <p class="desc">Đăng ký với chung tôi để nhận được thông tin ưu đãi sớm nhất</p>
                            <div id="form-reg">
                                <form method="POST" action="">
                                    <input type="email" name="email" id="email"
                                        placeholder="Nhập email tại đây">
                                    <button type="submit" data-url="{{ route('home.register') }}"
                                        id="sm-reg">Đăng ký</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="foot-bot">
                    <div class="wp-inner">
                        <p id="copyright">© Bản quyền thuộc về unitop.vn | Php Master</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="menu-respon">
            <a href="?page=home" title="" class="logo">DANH MỤC</a>
            <div id="menu-respon-wp">
                <ul class="" id="main-menu-respon">
                    <li>
                        <a href="{{ route('home.index') }}" title>Trang chủ</a>
                    </li>
                    <li>
                        <a href="{{ route('introduce.index') }}" title>Giới thiệu</a>
                    </li>
                    <li>
                        <a href="{{ route('blogs.list') }}" title>Blog</a>
                    </li>
                    <li>
                        <a href="{{ route('contact.index') }}" title>Liên hệ</a>
                    </li>
                    <li>
                        <a href="{{ route('product.list') }}" title>Sản phẩm</a>
                        {!! $menu !!}
                    </li>
                </ul>
            </div>
        </div>

        <div id="btn-top"><img src="{{ asset('public/css/import/client/images/icon-to-top.png') }}"
                alt="" /></div>
        <div id="fb-root"></div>
        <script>
            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id))
                    return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.8&appId=849340975164592";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        <script src="{{ asset('public/css/import/client/js/customs.js') }}" type="text/javascript"></script>
        <script src="{{ asset('public/css/import/client/js/jquery-2.2.4.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('public/css/import/client/js/elevatezoom-master/jquery.elevatezoom.js') }}"
            type="text/javascript"></script>
        <script src="{{ asset('public/css/import/client/js/bootstrap/bootstrap.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('public/css/import/client/js/carousel/owl.carousel.js') }}" type="text/javascript"></script>
        <script src="{{ asset('public/css/import/client/js/main.js') }}" type="text/javascript"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
            integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>
