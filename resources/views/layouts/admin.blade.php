@php
    $module_active = session('module_active');
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css"
        integrity="sha512-wJgJNTBBkLit7ymC6vvzM1EcSWeM9mmOu+1USHaRBbHkm6W9EgM0HY27+UtUaprntaYQJF75rc8gjxllKs5OIQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/solid.min.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="{{ asset('public/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/responsive.css') }}">
        "></script>
    <title>@yield('title')</title>
</head>

<body>
    <div id="warpper" class="nav-fixed">
        <nav class="topnav shadow navbar-light bg-white d-flex">
            <a class="navbar-brand" href="{{ url('admin') }}"><img class="img-logo"
                    src="{{ asset('public/images/logo.jpg') }}" alt="">STORE ADMIN</a>
            <div class="nav-right">
                <div class="btn-group mr-auto">
                    <button type="button" class="btn dropdown" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="plus-icon fas fa-plus-circle"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ url('admin/post/add') }}">Thêm bài viết</a>
                        <a class="dropdown-item" href="{{ url('admin/page/add') }}">Thêm trang</a>
                        <a class="dropdown-item" href="{{ url('admin/slider/add') }}">Thêm slider</a>
                        <a class="dropdown-item" href="{{ url('admin/banner/add') }}">Thêm banner</a>
                        <a class="dropdown-item" href="{{ url('admin/product/add') }}">Thêm sản phẩm</a>
                        <a class="dropdown-item" href="{{ url('admin/order/add') }}">Thêm đơn hàng</a>
                        <a class="dropdown-item" href="{{ url('admin/order/list') }}">Xem đơn hàng</a>
                    </div>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        {{ Auth::user()->name }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{ route('edit_user', Auth::user()->id) }}">Thông tin tài
                            khoản</a>
                        <a class="dropdown-item"
                            href="{{ route('logout') }}"onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">{{ __('Đăng xuất') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>

                <i class="icon-menu fa-solid fa-bars"></i>
                <div class="header__menu">
                    <div class="header__menu__overlay">
                    </div>
                    <div class="header__menu__content">
                        <div class="header__menu__content--header">
                            <h1>Danh mục</h1>
                            <i class="fa-solid fa-xmark icon__close--menu"></i>
                        </div>
                        <ul id="sidebar-menu">
                            <li class="nav-link">
                                <a href="{{ url('admin/dashboard') }}">
                                    <div class="nav-link-icon d-inline-flex">
                                        <i class="far fa-folder"></i>
                                    </div>
                                    Dashboard
                                </a>
                                <i class="arrow fas fa-angle-right"></i>
                            </li>
                            <li class="nav-link {{ $module_active == 'page' ? 'active' : '' }}">
                                <a href="{{ url('admin/page/list') }}">
                                    <div class="nav-link-icon d-inline-flex">
                                        <i class="far fa-folder"></i>
                                    </div>
                                    Trang
                                </a>
                                <i class="arrow fas fa-angle-right"></i>

                                <ul class="sub-menu">
                                    <li><a href="{{ url('admin/page/add') }}">Thêm mới</a></li>
                                    <li><a href="{{ url('admin/page/list') }}">Danh sách</a></li>
                                </ul>
                            </li>
                            <li class="nav-link {{ $module_active == 'post' ? 'active' : '' }}">
                                <a href="{{ url('admin/post/list') }}">
                                    <div class="nav-link-icon d-inline-flex">
                                        <i class="far fa-folder"></i>
                                    </div>
                                    Bài viết
                                </a>
                                <i class="arrow fas fa-angle-right"></i>
                                <ul class="sub-menu">
                                    <li><a href="{{ url('admin/post/add') }}">Thêm mới</a></li>
                                    <li><a href="{{ url('admin/post/list') }}">Danh sách</a></li>
                                    <li><a href="{{ url('admin/post/cat/list') }}">Danh mục</a></li>
                                </ul>
                            </li>
                            <li class="nav-link {{ $module_active == 'product' ? 'active' : '' }}">
                                <a href="{{ url('admin/product/list') }}">
                                    <div class="nav-link-icon d-inline-flex">
                                        <i class="far fa-folder"></i>
                                    </div>
                                    Sản phẩm
                                </a>
                                <i class="arrow fas fa-angle-right"></i>
                                <ul class="sub-menu">
                                    <li><a href="{{ url('admin/product/add') }}">Thêm mới</a></li>
                                    <li><a href="{{ url('admin/product/list') }}">Danh sách</a></li>
                                    <li><a href="{{ url('admin/product/cat/list') }}">Danh mục</a></li>
                                    <li><a href="{{ url('admin/product/color/list') }}">Màu sắc</a></li>
                                    <li><a href="{{ url('admin/product/brand/list') }}">Thương hiệu</a></li>
                                </ul>
                            </li>
                            <li class="nav-link {{ $module_active == 'order' ? 'active' : '' }}">
                                <a href="{{ url('admin/order/list') }}">
                                    <div class="nav-link-icon d-inline-flex">
                                        <i class="far fa-folder"></i>
                                    </div>
                                    Bán hàng
                                </a>
                                <i class="arrow fas fa-angle-right"></i>
                                <ul class="sub-menu">
                                    <li><a href="{{ url('admin/order/add') }}">Thêm mới</a></li>
                                    <li><a href="{{ url('admin/order/list') }}">Đơn hàng</a></li>
                                    <li><a href="{{ url('admin/order/revenue') }}">Doanh thu</a></li>
                                </ul>
                            </li>
                            <li class="nav-link {{ $module_active == 'banner' ? 'active' : '' }}">
                                <a href="{{ url('admin/banner/list') }}">
                                    <div class="nav-link-icon d-inline-flex">
                                        <i class="far fa-folder"></i>
                                    </div>
                                    Banner
                                </a>
                                <i class="arrow fas fa-angle-right"></i>
                                <ul class="sub-menu">
                                    <li><a href="{{ url('admin/banner/add') }}">Thêm mới</a></li>
                                    <li><a href="{{ url('admin/banner/list') }}">Danh sách</a></li>
                                </ul>
                            </li>
                            <li class="nav-link {{ $module_active == 'slider' ? 'active' : '' }}">
                                <a href="{{ url('admin/slider/list') }}">
                                    <div class="nav-link-icon d-inline-flex">
                                        <i class="far fa-folder"></i>
                                    </div>
                                    Slider
                                </a>
                                <i class="arrow fas fa-angle-right"></i>
                                <ul class="sub-menu">
                                    <li><a href="{{ url('admin/slider/add') }}">Thêm mới</a></li>
                                    <li><a href="{{ url('admin/slider/list') }}">Danh sách</a></li>
                                </ul>
                            </li>
                            <li class="nav-link {{ $module_active == 'user' ? 'active' : '' }}">
                                <a href="{{ url('admin/user/list') }}">
                                    <div class="nav-link-icon d-inline-flex">
                                        <i class="far fa-folder"></i>
                                    </div>
                                    Users
                                </a>
                                <i class="arrow fas fa-angle-right"></i>
                                <ul class="sub-menu">
                                    <li><a href="{{ url('admin/user/add') }}">Thêm mới</a></li>
                                    <li><a href="{{ url('admin/user/list') }}">Danh sách</a></li>
                                </ul>
                            </li>
                            <li class="nav-link {{ $module_active == 'permission' ? 'active' : '' }}">
                                <a href="{{ url('admin/permission/add') }}">
                                    <div class="nav-link-icon d-inline-flex">
                                        <i class="far fa-folder"></i>
                                    </div>
                                    Phân quyền
                                </a>
                                <i class="arrow fas fa-angle-right"></i>
                                <ul class="sub-menu">
                                    <li><a href="{{ url('admin/permission/add') }}">Thêm quyền</a></li>
                                    <li><a href="{{ url('admin/role/add') }}">Thêm vai trò</a></li>
                                    <li><a href="{{ url('admin/role/list') }}">Danh sách vai trò</a></li>
                                </ul>
                            </li>
                            <li class="nav-link {{ $module_active == 'registerEmail' ? 'active' : '' }}">
                                <a href="{{ url('admin/order/list') }}">
                                    <div class="nav-link-icon d-inline-flex">
                                        <i class="far fa-folder"></i>
                                    </div>
                                    Email đăng ký thông báo
                                </a>
                                <i class="arrow fas fa-angle-right"></i>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- end nav  -->
        <div id="page-body" class="">
            <div id="sidebar" class="bg-white">
                {{-- {{ $module_active }} --}}
                <ul id="sidebar-menu">
                    <li class="nav-link">
                        <a href="{{ url('admin/dashboard') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Dashboard
                        </a>
                        {{-- <i class="arrow fas fa-angle-right"></i> --}}
                    </li>
                    <li class="nav-link {{ $module_active == 'page' ? 'active' : '' }}">
                        <a href="{{ url('admin/page/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Trang
                        </a>
                        <i class="arrow fas fa-angle-right"></i>

                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/page/add') }}">Thêm mới</a></li>
                            <li><a href="{{ url('admin/page/list') }}">Danh sách</a></li>
                        </ul>
                    </li>
                    <li class="nav-link {{ $module_active == 'post' ? 'active' : '' }}">
                        <a href="{{ url('admin/post/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Bài viết
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/post/add') }}">Thêm mới</a></li>
                            <li><a href="{{ url('admin/post/list') }}">Danh sách</a></li>
                            <li><a href="{{ url('admin/post/cat/list') }}">Danh mục</a></li>
                        </ul>
                    </li>
                    <li class="nav-link {{ $module_active == 'product' ? 'active' : '' }}">
                        <a href="{{ url('admin/product/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Sản phẩm
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/product/add') }}">Thêm mới</a></li>
                            <li><a href="{{ url('admin/product/list') }}">Danh sách</a></li>
                            <li><a href="{{ url('admin/product/cat/list') }}">Danh mục</a></li>
                            <li><a href="{{ url('admin/product/color/list') }}">Màu sắc</a></li>
                            <li><a href="{{ url('admin/product/config/list') }}">Cấu hình</a></li>
                            <li><a href="{{ url('admin/product/brand/list') }}">Thương hiệu</a></li>
                        </ul>
                    </li>
                    <li class="nav-link {{ $module_active == 'order' ? 'active' : '' }}">
                        <a href="{{ url('admin/order/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Bán hàng
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/order/add') }}">Thêm mới</a></li>
                            <li><a href="{{ url('admin/order/list') }}">Đơn hàng</a></li>
                            <li><a href="{{ url('admin/order/revenue') }}">Doanh thu</a></li>
                        </ul>
                    </li>
                    <li class="nav-link {{ $module_active == 'banner' ? 'active' : '' }}">
                        <a href="{{ url('admin/banner/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Banner
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/banner/add') }}">Thêm mới</a></li>
                            <li><a href="{{ url('admin/banner/list') }}">Danh sách</a></li>
                        </ul>
                    </li>
                    <li class="nav-link {{ $module_active == 'slider' ? 'active' : '' }}">
                        <a href="{{ url('admin/slider/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Slider
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/slider/add') }}">Thêm mới</a></li>
                            <li><a href="{{ url('admin/slider/list') }}">Danh sách</a></li>
                        </ul>
                    </li>
                    <li class="nav-link {{ $module_active == 'user' ? 'active' : '' }}">
                        <a href="{{ url('admin/user/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Users
                        </a>
                        <i class="arrow fas fa-angle-right"></i>

                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/user/add') }}">Thêm mới</a></li>
                            <li><a href="{{ url('admin/user/list') }}">Danh sách</a></li>
                        </ul>
                    </li>
                    <li class="nav-link {{ $module_active == 'permission' ? 'active' : '' }}">
                        <a href="{{ url('admin/permission/add') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Phân quyền
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/permission/add') }}">Thêm quyền</a></li>
                            <li><a href="{{ url('admin/role/add') }}">Thêm vai trò</a></li>
                            <li><a href="{{ url('admin/role/list') }}">Danh sách vai trò</a></li>
                        </ul>
                    </li>
                    <li class="nav-link {{ $module_active == 'registerEmail' ? 'active' : '' }}">
                        <a href="{{ url('admin/email-register/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Email đăng ký
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                    </li>
                </ul>
            </div>
            <div id="wp-content">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"
        integrity="sha512-zlWWyZq71UMApAjih4WkaRpikgY9Bz1oXIW5G0fED4vk14JjGlQ1UmkGM392jEULP8jbNMiwLWdM8Z87Hu88Fw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="{{ asset('public/js/app.js') }}"></script>
    {{-- CDN --}}
    <script type="text/javascript"
        src='https://cdn.tiny.cloud/1/85x3362pro565b2wsnb78flv61oyrfxcqde4u1zxhwvuzthw/tinymce/5/tinymce.min.js'
        referrerpolicy="origin"></script>
    <script>
        var editor_config = {
            path_absolute: 'http://127.0.0.1:8000/',
            selector: 'textarea',
            relative_urls: false,
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table directionality",
                "emoticons template paste textpattern"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
            file_picker_callback: function(callback, value, meta) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName(
                    'body')[0].clientWidth;
                var y = window.innerHeight || document.documentElement.clientHeight || document
                    .getElementsByTagName('body')[0].clientHeight;

                var cmsURL = editor_config.path_absolute + 'laravel-filemanager?editor=' + meta.fieldname;
                if (meta.filetype == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }
                tinyMCE.activeEditor.windowManager.openUrl({
                    url: cmsURL,
                    title: 'Filemanager',
                    width: x * 0.8,
                    height: y * 0.8,
                    resizable: "yes",
                    close_previous: "no",
                    onMessage: (api, message) => {
                        callback(message.content);
                    }
                });
            }
        };
        tinymce.init(editor_config);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>

</html>
