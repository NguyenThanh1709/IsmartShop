//Hàm sử lý code click
function handleColorClick() {
    $(document).on("click", ".color-btn", function (e) {
        e.preventDefault();
        $(".color-btn").removeClass("active");
        $(this).addClass("active");
        let color = $(this).attr("id");
        let id_product = $(this).attr("data-id");
        let data = { color: color, id_product: id_product };
        let url = $(".color-btn").attr("data-url");

        $.ajax({
            url: url,
            method: "GET",
            data: data,
            dataType: "json",
            success: function (response) {
                $("#config").html(response.str);
                $("#wp-btn-card").html(response.btnCart);
                $("#num-order").attr("max", response.qty);
                $(".status").html(response.status);
                //Nếu có class active cùng vs btn-config lấy dữ liệu
                let config_id =
                    $(".btn-config.active").attr("storage_capacity");

                let dataPrice = {
                    config_id: config_id,
                    color: color,
                    id_product: id_product,
                };
                loadDataProduct(dataPrice);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            },
        });
    });
}

//Load cấu hình - giá sản phẩm
function loadDataProduct(data) {
    let url_config_price = $(".btn-config").attr("data-url");
    if (url_config_price !== undefined) {
        $.ajax({
            url: url_config_price,
            method: "GET",
            data: data,
            dataType: "json",
            success: function (response) {
                // console.log(response);
                $(".status").html(response.status);
                $("#price").html(response.str);
                $("#num-order").attr("max", response.qty);
                $("#num-order").val(1);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            },
        });
    } else {
        //console.log("url_config_price is undefined");
    }
}

$(document).ready(function () {
    //  SLIDER
    let slider = $("#slider-wp .section-detail");
    slider.owlCarousel({
        autoPlay: 4500,
        navigation: false,
        navigationText: false,
        paginationNumbers: false,
        pagination: true,
        items: 1, //10 items above 1000px browser width
        itemsDesktop: [1000, 1], //5 items between 1000px and 901px
        itemsDesktopSmall: [900, 1], // betweem 900px and 601px
        itemsTablet: [600, 1], //2 items between 600 and 0
        itemsMobile: true, // itemsMobile disabled - inherit from itemsTablet option
    });

    //Load cấu hình theo màu sắc
    if ($(".color-btn").hasClass("active")) {
        let color = $(".color-btn").attr("id");
        let id_product = $(".color-btn").attr("data-id");
        let data = { color: color, id_product: id_product };
        let url = $(".color-btn").attr("data-url");
        $.ajax({
            url: url,
            method: "GET",
            data: data,
            dataType: "json",
            success: function (response) {
                //console.log(response)
                $("#config").html(response.str);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            },
        });
    }

    //Click cấu hình
    $(document).ready(function () {
        $(document).on("click", ".btn-config", function (e) {
            e.preventDefault();
            $(".btn-config").removeClass("active");
            $(this).addClass("active");
            let config_id = $(".btn-config.active").attr("storage_capacity");
            let id_product = $(".color-btn.active").attr("data-id");
            let color = $(".color-btn.active").attr("id");
            let dataPrice = {
                config_id: config_id,
                color: color,
                id_product: id_product,
            };
            console.log(dataPrice)
            loadDataProduct(dataPrice);
        });
    });

    if ($(".list-cart-show")[0]) {
        $("#cart_null").hide();
    } else {
        $(".wp-noti").hide();
    }

    //  ZOOM PRODUCT DETAIL
    $("#zoom").elevateZoom({
        gallery: "list-thumb",
        cursor: "pointer",
        galleryActiveClass: "active",
        imageCrossfade: true,
        loadingIcon: "http://www.elevateweb.co.uk/spinner.gif",
    });

    //  LIST THUMB
    let list_thumb = $("#list-thumb");
    list_thumb.owlCarousel({
        navigation: true,
        navigationText: false,
        paginationNumbers: false,
        pagination: false,
        stopOnHover: true,
        items: 5, //10 items above 1000px browser width
        itemsDesktop: [1000, 5], //5 items between 1000px and 901px
        itemsDesktopSmall: [900, 5], // betweem 900px and 601px
        itemsTablet: [768, 5], //2 items between 600 and 0
        itemsMobile: true, // itemsMobile disabled - inherit from itemsTablet option
    });

    //  FEATURE PRODUCT
    let feature_product = $("#feature-product-wp .list-item");
    feature_product.owlCarousel({
        autoPlay: true,
        navigation: true,
        navigationText: false,
        paginationNumbers: false,
        pagination: false,
        stopOnHover: true,
        items: 4, //10 items above 1000px browser width
        itemsDesktop: [1000, 4], //5 items between 1000px and 901px
        itemsDesktopSmall: [800, 3], // betweem 900px and 601px
        itemsTablet: [600, 2], //2 items between 600 and 0
        itemsMobile: [375, 1], // itemsMobile disabled - inherit from itemsTablet option
    });

    //  SAME CATEGORY
    let same_category = $("#same-category-wp .list-item");
    same_category.owlCarousel({
        autoPlay: true,
        navigation: true,
        navigationText: false,
        paginationNumbers: false,
        pagination: false,
        stopOnHover: true,
        items: 4, //10 items above 1000px browser width
        itemsDesktop: [1000, 4], //5 items between 1000px and 901px
        itemsDesktopSmall: [800, 3], // betweem 900px and 601px
        itemsTablet: [600, 2], //2 items between 600 and 0
        itemsMobile: [375, 1], // itemsMobile disabled - inherit from itemsTablet option
    });

    //  SCROLL TOP
    $(window).scroll(function () {
        if ($(this).scrollTop() != 0) {
            $("#btn-top").stop().fadeIn(150);
        } else {
            $("#btn-top").stop().fadeOut(150);
        }
    });
    $("#btn-top").click(function () {
        $("body,html").stop().animate({ scrollTop: 0 }, 800);
    });

    // CHOOSE NUMBER ORDER

    // Lấy giá trị tối đa từ dữ liệu PHP
   $(document).ready(function () {
        const maxValue = parseInt($("#num-order").attr("max")); // Lấy giá trị tối đa từ dữ liệu PHP
        console.log(maxValue);

        // Sự kiện khi nhấn nút minus (giảm giá trị)
        $("#minus").click(function () {
            let currentValue = parseInt($("#num-order").val());
            if (currentValue > 1) {
                currentValue--;
                $("#num-order").val(currentValue);
            }
        });

        // Sự kiện khi nhấn nút plus (tăng giá trị)
        $("#plus").click(function () {
            let currentValue = parseInt($("#num-order").val());
            if (currentValue < maxValue) {
                currentValue++;
                $("#num-order").val(currentValue);
            }
        });

        // Kiểm tra giá trị nhập vào input để đảm bảo nằm trong phạm vi cho phép
        $("#num-order").on("input", function () {
            const maxValue = parseInt($("#num-order").attr("max")); // Lấy giá trị tối đa từ dữ liệu PHP
            let currentValue = parseInt($(this).val());
            if (isNaN(currentValue) || currentValue < 1) {
                $(this).val(1);
            } else if (currentValue > maxValue) {
                $(this).val(maxValue);
            }
        });
    });

    //  MAIN MENU
    $("#category-product-wp .list-item > li")
        .find(".sub-menu")
        .after('<i class="fa fa-angle-right arrow" aria-hidden="true"></i>');

    //  TAB
    tab();

    //  EVEN MENU RESPON
    $("html").on("click", function (event) {
        let target = $(event.target);
        let site = $("#site");

        if (target.is("#btn-respon i")) {
            if (!site.hasClass("show-respon-menu")) {
                site.addClass("show-respon-menu");
            } else {
                site.removeClass("show-respon-menu");
            }
        } else {
            $("#container").click(function () {
                if (site.hasClass("show-respon-menu")) {
                    site.removeClass("show-respon-menu");
                    return false;
                }
            });
        }
    });

    //  MENU RESPON
    $("#main-menu-respon li .sub-menu").after(
        '<span class="fa fa-angle-right arrow"></span>'
    );
    $("#main-menu-respon li .arrow").click(function () {
        if ($(this).parent("li").hasClass("open")) {
            $(this).parent("li").removeClass("open");
        } else {
            //            $('.sub-menu').slideUp();
            //            $('#main-menu-respon li').removeClass('open');
            $(this).parent("li").addClass("open");
            //            $(this).parent('li').find('.sub-menu').slideDown();
        }
    });

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    ///////////////////////
    //--Pagination Ajax--//
    ///////////////////////
    // Click item a
    $(document).on("click", "#module-product .pagination a", function (event) {
        event.preventDefault();
        if ($('input[name="r-price"]:checked').attr("id")) {
            var max = $('input[name="r-price"]:checked').attr("id");
            var min = $('input[name="r-price"]:checked').attr("min");
        }
        let title = $(".title-cat").text();
        let href = $(this).attr("href");
        let urlParams = new URLSearchParams(href.split("?")[1]);
        let sort = urlParams.get("sort");
        let page = urlParams.get("page");
        let data = { max: max, min: min, sort: sort, page: page };
        console.log(page);
        //replace span -> a
        $("ul.pagination li:eq(1) span").replaceWith(function () {
            return $("<a/>", {
                html: $(this).html(),
                class: "page-link",
                href:
                    "https://vothanh.devcv.online/arrange?sort=" +
                    sort +
                    "&page=1",
            });
        });
        $("ul.pagination li:eq(0) span").replaceWith(function () {
            return $("<a/>", {
                html: $(this).html(),
                class: "page-link",
                href:
                    "https://vothanh.devcv.online/arrange?sort=" +
                    sort +
                    "&page=" +
                    page,
            });
        });
        //Open li prevois
        $("ul.pagination li:eq(0)").removeClass("disabled");
        $.ajax({
            url: "https://vothanh.devcv.online/get-data?page=" + page,
            type: "GET",
            data: data,
            dataType: "json",
            success: function (data) {
                // console.log(data)
                $("#listProduct").html(data);
                $(".pagination li").removeClass("active");
                $(".pagination li").eq(page).addClass("active");
            },
        });
    });

    // Click item a last-child (next)
    $(document).on(
        "click",
        "#module-product .pagination .page-item:last-child a",
        function (event) {
            event.preventDefault();
            let activeLi = $("ul.pagination li.active");
            let href = activeLi.children("a").attr("href");
            var urlParams = new URLSearchParams(href.split("?")[1]);
            let page = parseInt(urlParams.get("page")) + 1;
            let newHref = href.replace(/page=\d+/, "page=" + page);
            $(this).attr("href", newHref);
            var urlParams = new URLSearchParams(href.split("?")[1]);
            let sort = urlParams.get("sort");
            let data = { sort: sort, page: page };
            $.ajax({
                url: "https://vothanh.devcv.online/get-data" + page,
                type: "GET",
                data: data,
                dataType: "json",
                success: function (data) {
                    if (data != "") {
                        $("#listProduct").html(data);
                        $(".pagination li").removeClass("active");
                        $(".pagination li").eq(page).addClass("active");
                    } else {
                        $(".pagination .page-item:first-child a").addClass(
                            "disabled"
                        );
                    }
                },
            });
            $("ul.pagination li:eq(0) span").replaceWith(function () {
                return $("<a/>", {
                    html: $(this).html(),
                    class: "page-link",
                    href:
                        "https://vothanh.devcv.online/arrange?sort=" +
                        sort +
                        "&page=" +
                        page,
                })
                    .parent()
                    .removeAttr("disabled");
            });
        }
    );

    $(document).on(
        "click",
        "#module-product .pagination .page-item:first-child a",
        function (event) {
            event.preventDefault();
            let activeLi = $("ul.pagination li.active");
            let href = activeLi.children("a").attr("href");
            var urlParams = new URLSearchParams(href.split("?")[1]);
            let page = parseInt(urlParams.get("page")) - 1;
            let newHref = href.replace(/page=\d+/, "page=" + page);
            $(this).attr("href", newHref);
            var urlParams = new URLSearchParams(href.split("?")[1]);
            let sort = urlParams.get("sort");
            let data = { sort: sort, page: page };
            $.ajax({
                url: "https://vothanh.devcv.online/get-data/" + page,
                type: "GET",
                data: data,
                dataType: "json",
                success: function (data) {
                    if (data != "") {
                        $("#listProduct").html(data);
                        $(".pagination li").removeClass("active");
                        $(".pagination li").eq(page).addClass("active");
                    } else {
                        $(".pagination .page-item:first-child a").addClass(
                            "disabled"
                        );
                    }
                },
            });
        }
    );

    $("#select-option-arrange").change(function () {
        let title = $(".title-cat").text();
        // console.log(title);
        let url = $("#select-option-arrange").attr("data-url");
        let arrange = $(this).val();
        if ($('input[name="r-price"]:checked').attr("id")) {
            var max = $('input[name="r-price"]:checked').attr("id");
            var min = $('input[name="r-price"]:checked').attr("min");
        }
        const data = { title: title, max: max, min: min, arrange: arrange };
        // console.log(data);
        $.ajax({
            url: url,
            method: "GET",
            data: data,
            dataType: "json",
            success: function (response) {
                // console.log(response);
                $("#listProduct").html(response.str);
                $(".wp-paging").html(response.paging);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            },
        });
    });

    $(".arrange-price").change(function () {
        let title = "";
        title = $(".title-cat").text();
        let url = $(this).attr("data-url");
        let max = $(this).attr("id");
        let min = $(this).attr("min");
        var arrange = $("#select-option-arrange").find(":selected").val();

        const data = { title: title, max: max, min: min, arrange: arrange };
        // console.log(data);
        $.ajax({
            url: url,
            method: "GET",
            data: data,
            dataType: "json",
            success: function (response) {
                // console.log(response);
                $("#listProduct").html(response.str);
                $(".wp-paging").html(response.paging);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            },
        });
    });

    $("#see_next_link").click(function () {
        $(".wp-content-product").css({ height: "auto" });
        $(".see_next").css({ display: "none" });
    });

      // Gọi hàm handleColorClick() khi tài liệu đã sẵn sàng (ready)
    $(document).ready(function () {
        handleColorClick();
    });

    $(document).on("click", ".add-cart", function (e) {
        e.preventDefault();
        if ($("#color")[0]) {
        if (!$(".btn-config").hasClass("active")) {
            toastr.warning("Bạn vui lòng chọn cấu hình");
            return;
        }
       
            var color_id = $(".color-btn.active").attr("id");
            var product_id = $(".color-btn.active").attr("data-id");
            var config_id = $(".btn-config.active").attr("storage_capacity");
            var number_order = $("#num-order").val();
            var data = {
                color_id: color_id,
                product_id: product_id,
                config_id: config_id,
                number_order: number_order,
            };
        } else {
            var number_order = $("#num-order").val();
            var product_id = $(".add-cart").attr("data-id");
            var data = { number_order: number_order, product_id: product_id };
        }
        let url = $(".add-cart").attr("data-url");
        let dataUrlCart = $(this).attr("data-url-cart");
        // console.log(data);
        $.ajax({
            url: url,
            method: "GET",
            data: data,
            dataType: "json",
            success: function (response) {
                // console.log(response.str)
                if (response.str != null) {
                    $("#cart_null").hide();
                    $(".wp-noti").show();
                }
                $(".list-cart").html(response.str);
                $(".number-in-cart").html(response.total);
                $("#num").html(response.total);
                $("#num_mobile").html(response.total);
                $("#total-cart").html(response.totalPrice);
                Swal.fire({
                    title: '<span style="font-size:20px;">Đã thêm sản phẩm vào giỏ hàng!</span>',
                    icon: "success",
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: "Đến giỏ hàng",
                    cancelButtonText: "Tiếp tục mua sắm",
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = dataUrlCart;
                    } else {
                        //  window.location.reload();
                    }
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            },
        });
    });

    $(".num-order").change(function () {
        let id = $(this).attr("data-id");
        let qty = $(this).val();
        let data = { id: id, qty: qty };
        let url = $(this).attr("data-url");
        console.log(data);
        $.ajax({
            url: url,
            method: "POST",
            data: data,
            dataType: "json",
            success: function (data) {
                console.log(data);
                $("#sub-total-" + id).html(data.subtotal);
                $("#num").html(data.allnumber);
                $("#total-price span").html(data.total);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            },
        });
    });

    $(".del-product").click(function (e) {
        e.preventDefault();
        // alert('Đã click');
        let id = $(this).attr("data-id");
        let url = $(this).attr("data-url");
        let data = {
            id: id,
        };
        // console.log(data);
        Swal.fire({
            title: "Bạn chắc chắn xoá hay không?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ok",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: "POST",
                    data: data,
                    dataType: "text",
                    success: function (data) {
                        if (data != null) {
                            Swal.fire({
                                title: '<span style="font-size:20px;">Đã xoá sản phẩm khỏi giỏ hàng!</span>',
                                icon: "success",
                                showCancelButton: false,
                                focusConfirm: false,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status);
                        alert(thrownError);
                        console.log(thrownError);
                    },
                });
            }
        });
    });

    $(".delete-cart").click(function (e) {
        e.preventDefault();
        let url = $(this).attr("data-url");
        Swal.fire({
            title: "Bạn chắc chắn xoá hay không?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ok",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: "POST",
                    dataType: "text",
                    success: function (data) {
                        if (data != null) {
                            Swal.fire({
                                title: '<span style="font-size:20px;">Đã xoá sản phẩm khỏi giỏ hàng!</span>',
                                icon: "success",
                                showCancelButton: false,
                                focusConfirm: false,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status);
                        alert(thrownError);
                        console.log(thrownError);
                    },
                });
            }
        });
    });

    // Select Tỉnh thành phố
    $("#province-city").change(function () {
        let id = $(this).val();
        let url = $(this).attr("data-url");
        console.log(id);
        $.ajax({
            url: url,
            method: "POST",
            data: { id: id },
            dataType: "json",
            success: function (data) {
                console.log(data);
                $("#district").html("");

                $.each(data, function (key, value) {
                    // Use the Option() constructor to create a new HTMLOptionElement.
                    let option = new Option(value, key);
                    // Convert the HTMLOptionElement into a JQuery object that can be used with the append method.
                    $(option).html(value);
                    // Append the option to our Select element.
                    $("#district").append(option);
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            },
        });
    });
    // Select district
    $("#district").change(function () {
        let id = $(this).val();
        let url = $(this).attr("data-url");
        console.log(id);
        $.ajax({
            url: url,
            method: "POST",
            data: { id: id },
            dataType: "json",
            success: function (data) {
                console.log(data);
                $("#commune").html("");
                $.each(data, function (key, value) {
                    let option = new Option(value, key);
                    $(option).html(value);
                    $("#commune").append(option);
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            },
        });
    });

    $("#order-now").click(function (e) {
        let timerInterval;
        Swal.fire({
            title: "Vui lòng chờ !",
            html: "Hệ thống đang xử lý",
            timer: 6000,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
                const b = Swal.getHtmlContainer().querySelector("b");
                timerInterval = setInterval(() => {
                    b.textContent = Swal.getTimerLeft();
                }, 100);
            },
            willClose: () => {
                clearInterval(timerInterval);
            },
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer) {
                console.log("I was closed by the timer");
            }
        });
    });

    $("#s").keyup(function () {
        let key = $(this).val();
        let url = $(this).attr("data-url");
        $.ajax({
            url: url,
            method: "POST",
            data: { key: key },
            dataType: "json",
            success: function (response) {
                $("#wp-suggest").html(response);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            },
        });
    });

    $(document).on("click", ".icon-search-mobile", function () {
        $("#search-mobile").fadeToggle(200);
    });

    $(document).on("click", "#sm-reg", function (e) {
        e.preventDefault();
        let url = $(this).attr("data-url");
        let email = $("#email").val();
        console.log(url);
        $.ajax({
            url: url,
            method: "POST",
            data: { email: email },
            dataType: "json",
            success: function (response) {
                toastr.options.progressBar = true;
                if (response == "Null") {
                    toastr.warning("Vui lòng điền Email của bạn!");
                }
                if (response == "Successfully") {
                    toastr.success(
                        "Cảm ơn bạn đã đăng ký, thông báo ưu đãi sẽ gửi đến bạn sớm nhất"
                    );
                }
                if (response == "Exists") {
                    toastr.error("Địa chỉ Email này đã tôn tại");
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            },
        });
    });
});

function tab() {
    let tab_menu = $("#tab-menu li");
    tab_menu.stop().click(function () {
        $("#tab-menu li").removeClass("show");
        $(this).addClass("show");
        let id = $(this).find("a").attr("href");
        $(".tabItem").hide();
        $(id).show();
        return false;
    });
    $("#tab-menu li:first-child").addClass("show");
    $(".tabItem:first-child").show();
}

