$(document).ready(function () {
    $('.color-option').click(function () {
        console.log('OKE');
    });
})
$(document).ready(function () {
    $('.nav-link.active .sub-menu').slideDown();
    // $("p").slideUp();

    $('#sidebar-menu .arrow').click(function () {
        $(this).parents('li').children('.sub-menu').slideToggle();
        $(this).toggleClass('fa-angle-right fa-angle-down');
    });

    $("input[name='checkall']").click(function () {
        let checked = $(this).is(':checked');
        $('.table-checkall tbody tr td input:checkbox').prop('checked', checked);
    });

    $('.btn-delete').click(function (e) {
        e.preventDefault();
        const href = $(this).attr('href');
        Swal.fire({
            title: 'Xác nhận !',
            text: "Bạn chắc chắn xoá dữ liệu này?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Huỷ',
            confirmButtonText: 'Đồng ý',
        }).then((result) => {
            if (result.value) {
                document.location.href = href;
            }
        });
    });

    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    });

    function convertToSlug(str) {
        // Chuyển hết sang chữ thường
        str = str.toLowerCase();
        // xóa dấu
        str = str.replace(/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/g, 'a');
        str = str.replace(/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/g, 'e');
        str = str.replace(/(ì|í|ị|ỉ|ĩ)/g, 'i');
        str = str.replace(/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/g, 'o');
        str = str.replace(/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/g, 'u');
        str = str.replace(/(ỳ|ý|ỵ|ỷ|ỹ)/g, 'y');
        str = str.replace(/(đ)/g, 'd');
        // Xóa ký tự đặc biệt
        str = str.replace(/([^0-9a-z-\s])/g, '');
        // Xóa khoảng trắng thay bằng ký tự -
        str = str.replace(/(\s+)/g, '-');
        // Xóa ký tự - liên tiếp
        str = str.replace(/-+/g, '-');
        // xóa phần dự - ở đầu
        str = str.replace(/^-+/g, '');
        // xóa phần dư - ở cuối
        str = str.replace(/-+$/g, '');
        // return
        return str;
    }

    $('.autofill').click(function () {
        let str = $('#name').val();
        let strSlug = convertToSlug(str);
        $('#slug').val(strSlug);
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    ////////////////////////
    //--Thông tin modal-- //
    ////////////////////////
    $('.btn-info-cat').click(function () {
        let url = $(this).attr('data-url');
        $.ajax({
            url: url,
            method: 'POST',
            dataType: 'json',
            success: function (response) {
                console.log(response);
                $('input[name=title]').val(response.data.name);
                $("#slug_modal").val(response.data.slug);
                $('#cat_modal option[value="' + response.data.id + '"]').attr("selected", "selected");
                if (response.data.status == 'pending') {
                    $('#status-pending').prop('checked', true);
                } else {
                    $('#status-public').prop('checked', true);
                }
                $('#user_create').val(response.user);
                $('input[name=create_at]').val(response.data.created_at);
                $('input[name=update_at]').val(response.data.updated_at);
                let link = $('.btn-update').attr('data-url');
                link = link + '/' + response.data.id;
                $('.btn-update').attr('id', link);
                $('#modal-info').fadeToggle(100);

            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            }
        });
    });

    $('.btn-update').click(function () {
        let url = $(this).attr('id');
        let name = $('input[name=title]').val();
        let slug = $("#slug_modal").val();
        let cat = $("#cat_modal option:selected").val();
        let status = $("#form-modal input[type='radio']:checked").val();
        const data = { name: name, slug: slug, cat: cat, status: status }
        console.log(data);
        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function (response) {
                // console.log(response);
                location.reload();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            }
        });
    });

    $('.close').click(function () {
        $('#modal-info').fadeToggle(1)
    });

    $('.btn-close').click(function () {
        $('#modal-info').fadeToggle(1)
    });

    ////////////////////
    ////Update color////
    ////////////////////

    $('.btn-info-color').click(function () {
        let url = $(this).attr('data-url');
        $.ajax({
            url: url,
            method: 'POST',
            dataType: 'json',
            success: function (response) {
                // console.log(response);
                if (response.data.status == 'pending') {
                    $('#status-pending').prop('checked', true);
                } else {
                    $('#status-public').prop('checked', true);
                }
                $('#name_modal').val(response.data.name);
                $("#slug_modal").val(response.data.slug);
                $('#cat_modal option[value="' + response.data.id + '"]').attr("selected", "selected");
                $("#color_modal").val(response.data.code_color);
                $('#user_create').val(response.user);
                $('#create_at').val(response.data.created_at);
                $('#update_at').val(response.data.updated_at);
                let link = $('.btn-update-color').attr('data-url');
                link = link + '/' + response.data.id;
                $('.btn-update-color').attr('id', link);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            }
        });
    });

    $('.btn-update-color').click(function () {
        let url = $(this).attr('id');
        let name = $('#name_modal').val();
        let slug = $("#slug_modal").val();
        let color = $("#color_modal").val();
        let status = $("#form-modal input[type='radio']:checked").val();
        const data = { name: name, slug: slug, color: color, status: status }
        // console.log(data);
        // console.log(url);
        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function (response) {
                // console.log(response);
                location.reload();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            }
        });
    });
    /////////////////////
    ////Update brand/////
    /////////////////////
    $('.btn-info-brand').click(function () {
        let url = $(this).attr('data-url');
        $.ajax({
            url: url,
            method: 'POST',
            dataType: 'json',
            success: function (response) {
                // console.log(response);
                if (response.data.status == 'pending') {
                    $('#status-pending').prop('checked', true);
                } else {
                    $('#status-public').prop('checked', true);
                }
                $('#name_modal').val(response.data.name);
                $("#slug_modal").val(response.data.slug);
                $('#user_create').val(response.user);
                $('#create_at').val(response.data.created_at);
                $('#update_at').val(response.data.updated_at);
                let link = $('.btn-update-brand').attr('data-url');
                link = link + '/' + response.data.id;
                $('.btn-update-brand').attr('id', link);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            }
        });
    });

    $('.btn-update-brand').click(function () {
        let url = $(this).attr('id');
        let name = $('#name_modal').val();
        let slug = $("#slug_modal").val();
        let status = $("#form-modal input[type='radio']:checked").val();
        const data = { name: name, slug: slug, status: status }
        // console.log(data);
        // console.log(url);
        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function (response) {
                // console.log(response);
                location.reload();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            }
        });
    });

    $('.btn-config').click(function () {
        let url = $(this).attr('data-url');
        $.ajax({
            url: url,
            method: 'POST',
            dataType: 'json',
            success: function (response) {
                // console.log(response);
                if (response.data.status == 'pending') {
                    $('#status-pending').prop('checked', true);
                } else {
                    $('#status-public').prop('checked', true);
                }
                $('#name_modal').val(response.data.name);
                $("#storage_capacity_modal").val(response.data.storage_capacity);
                $('#user_create').val(response.user);
                $('#create_at').val(response.data.created_at);
                $('#update_at').val(response.data.updated_at);
                let link = $('.btn-update-config').attr('data-url');
                link = link + '/' + response.data.id;
                $('.btn-update-config').attr('id', link);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            }
        });
    });

    $('.btn-update-config').click(function () {
        let url = $(this).attr('id');
        let name = $('#name_modal').val();
        let storage_capacity = $("#storage_capacity_modal").val();
        let status = $("#form-modal input[type='radio']:checked").val();
        const data = { name: name, storage_capacity: storage_capacity, status: status }
        console.log(data);
        // console.log(url);
        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {
                // console.log(data);
                location.reload();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            }
        });
    });

    //Checkbox - show quantity
    $('#0').click(function () {
        if ($(this).is(':checked')) {
            $("#0_isChecked").slideDown(300)
            $('.checked-item').prop('checked', false);
            $(".wp-form-quantity").fadeOut(400)
        } else {
            $("#0_isChecked").slideUp(300)
        }
    });

    $('.checked-item').click(function () {
        let colorId = $(this).val();
        if ($(this).is(':checked')) {
            $('#checked-' + colorId).slideDown(200);
            $('#0').prop('checked', false);
            $("#0_isChecked").fadeOut(400)
        } else {
            $('#checked-' + colorId).slideUp(200);
        }
    });

    $('.icon-menu').click(function () {
        $('.header__menu__overlay').fadeIn(500);
        $('.header__menu__content').addClass('show__menu__content');
    });

    $('.icon__close--menu').click(function () {
        $('.header__menu__overlay').fadeOut();
        $('.header__menu__content').removeClass('show__menu__content');
    });

    $('.checkbox-item-config').click(function () {
        const data_id = $(this).attr('data-id');
        if ($('.checkbox-item-config').is(":checked") == true) {
            console.log(data_id);
            $('#quantity-' + data_id).attr('disabled', false);
            $('#price-' + data_id).attr('disabled', false);
            $('#sale_off-' + data_id).attr('disabled', false);
        } else {
            $('#quantity-' + data_id).attr('disabled', true);
            $('#price-' + data_id).attr('disabled', true);
            $('#sale_off-' + data_id).attr('disabled', true);
        }
    });

    $('.btn-info-order').click(function () {
        let id = $(this).attr('data-id')
        $('.btn-update-order').attr('data-id', id)
        let url = $(this).attr('data-url')
        console.log(id);
        $.ajax({
            url: url,
            method: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function (data) {
                console.log(data);
                $('#name').val(data[0].name)
                $('#email').val(data[0].email)
                $('#phone').val("0" + data[0].phone)
                $('#code').val('#CODE' + data[0].id)
                if (data.payment_method == 'payment_home') {
                    $('#payment_method').val('Thanh toán khi nhận hàng')
                } else {
                    $('#payment_method').val('Thanh toán tại cửa hàng')
                }
                $('#status option[value="' + data[0].status + '"]').attr("selected", "selected");
                $('#date_order').val(data[0].created_at)
                $('#date_update').val(data[0].updated_at)
                $('#total').val(data[0].total.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }));
                $('#address').val(data[0].address + "/" + data[1].name + "/" + data[2].name + "/" + data[3].name);
                $('#table-info-modal-product').html(data[4])
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            }
        });
    });

    $('.btn-update-order').click(function () {
        let id = $(this).attr('data-id');
        let url = $(this).attr('data-url');
        let status = $('#status').val();
        const data = { id: id, status: status }
        console.log(status)
        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {
                // console.log(data);
                location.reload();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            }
        });
    })

    // Select Tỉnh thành phố
    $('#province-city').change(function () {
        let id = $(this).val();
        let url = $(this).attr('data-url');
        console.log(id);
        $.ajax({
            url: url,
            method: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function (data) {
                console.log(data);
                $("#district").html('');

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
            }
        });
    });

    // Select district
    $('#district').change(function () {
        let id = $(this).val();
        let url = $(this).attr('data-url');
        console.log(id);
        $.ajax({
            url: url,
            method: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function (data) {
                // console.log(data);
                $("#commune").html('');
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
            }
        });
    });

    // Check info customer numberphone
    $('.btn-check').click(function () {
        let url = $(this).attr('data-url');
        let phone = $('#phone').val()
        $.ajax({
            url: url,
            method: 'POST',
            data: { phone: phone },
            dataType: 'json',
            success: function (response) {
                console.log(response);
                if (response != null) {
                    $('#name').val(response[0].name)
                    $('#email').val(response[0].email)
                    $('#address').val(response[0].address)
                    //District
                    let str = "";
                    str += `<option value="${response[1].maqh}">${response[1].name}</option>`;
                    $("#district").html(str);
                    // Comnune
                    let str1 = "";
                    str1 += `<option value="${response[2].xaid}">${response[2].name}</option>`;
                    $("#commune").html(str1);

                    $('#province-city option[value="' + response[0].matp + '"]').attr("selected", "selected");
                }
                if (response == null) {

                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            }
        });
    });

    let value = parseInt($('#num-order').attr('value'));
    $('#plus').click(function () {
        value++;
        $('#num-order').attr('value', value);
        update_href(value);
    });

    $('#minus').click(function () {
        if (value > 1) {
            value--;
            $('#num-order').attr('value', value);
        }
        update_href(value);
    });

    $('#keyword').keyup(function () {
        let keyword = $(this).val()
        let url = $(this).attr('data-url')
        $.ajax({
            url: url,
            method: 'POST',
            data: { keyword: keyword },
            dataType: 'json',
            success: function (response) {
                console.log(response);
                $('.table-info-modal-product').html(response)
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            }
        });
    });

    $(document).on('change', '.color-option', function () {
        let id = $(this).attr('data-id');
        let url = $(this).attr('data-url');
        let val = $(this).val();
        // $('.icon-cart').attr('data-color', val)
        const data = { id: id, val: val }
        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function (response) {
                console.log(response);
                $('#config-' + id).html(response)
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            }
        });
    });

    $(document).on('click', '.icon-cart', function () {
        let product_id = $(this).attr('data-id');
        let url = $(this).attr('data-url')
        let color_id = $('#color-' + product_id).find(":selected").val()
        let config_id = $('#config-' + product_id).find(":selected").val()
        let quantity = $('#quantity-modal-' + product_id).val()
        const data = { product_id: product_id, config_id: config_id, color_id: color_id, quantity: quantity }
        console.log(data)
        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {
                $('.table-product-data').html(data)
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            }
        });
    });

    $(document).on('click', '.btn-delete-cart', function () {
        let rowID = $(this).attr('data-rowId')
        let url = $(this).attr('data-url')
        console.log(url);
        $.ajax({
            url: url,
            method: 'POST',
            data: { rowID: rowID },
            dataType: 'json',
            success: function (data) {
                if (data == 'success') {
                    location.reload();
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            }
        });
    });

    $(document).on('change', '.config-option', function () {
        // alert('OKE');
        let product_id = $(this).attr('data-id')
        let url = $(this).attr('data-url')
        let config_id = $(this).val()
        // console.log(config_id);
        $.ajax({
            url: url,
            method: 'GET',
            data: { product_id: product_id, config_id: config_id },
            dataType: 'json',
            success: function (data) {
                $('#price-modal-'+product_id).html(data.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') + "đ")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            }
        });
    });

    $('.btn-add-order').click(function (e) {
        let timerInterval
        Swal.fire({
            title: 'Vui lòng chờ !',
            html: 'Hệ thống đang xử lý',
            timer: 6000,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading()
                const b = Swal.getHtmlContainer().querySelector('b')
                timerInterval = setInterval(() => {
                    b.textContent = Swal.getTimerLeft()
                }, 100)
            },
            willClose: () => {
                clearInterval(timerInterval)
            }
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer) {
                console.log('I was closed by the timer')
            }
        })
    })

    $('.table-revenue tbody tr').click(function () {
        if (!$(this).hasClass('active-tr')) {
            $('tr').removeClass('active-tr')
            $(this).addClass('active-tr')
        } else {
            $('tr').removeClass('active-tr')
        }
        let time = $(this).find('td').eq(1).text()
        let url = $('.date').attr('data-url')
        $.ajax({
            url: url,
            method: 'GET',
            data: { time: time },
            dataType: 'json',
            success: function (data) {
                console.log(data)
                $('#detailSaleProduct').html(data)
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            }
        });
    })

    // $('#btn-export').click(function (e) {
    //     e.preventDefault();
    //     alert('Đang phát triển chức năng này')
    // });

    $('.btn-info-permission').click(function(){
        let url = $(this).attr('data-url')
        $.ajax({
            url: url,
            method: 'POST',
            dataType: 'json',
            success: function (data) {
                console.log(data)
                $('.btn-update-permission').attr('data-id', data.id)
                $('#name_modal').val(data.name)
                $('#slug_modal').val(data.slug)
                let editor = tinymce.get('description_modal');
                editor.setContent(data.description);
                $('#create_at').val(data.created_at)
                $('#update_at').val(data.updated_at)
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            }
        });
    })

    $('.btn-update-permission').click(function(){
        let id = $(this).attr('data-id')
        let name = $('#name_modal').val()
        let slug = $('#slug_modal').val()
        let editor = tinymce.get('description_modal');
        let description = editor.getContent();
        const data = {id:id, name:name, slug:slug, description:description}
        console.log(data)
        let url = $(this).attr('data-url')
        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {
                console.log(data)
                if(data == 'success') {
                    location.reload();
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                console.log(thrownError);
            }
        });
    })

    $('.check-all').click(function() {
        $(this).closest('.card').find('.permission').prop('checked', this.checked)
    })
    
    // Mặc định checked tháng và disable các select box khác
    $("#selectQuarter").prop("disabled", true);

    // Xử lý khi checkbox thay đổi trạng thái
    $("#monthCheckbox").change(function () {
        if ($(this).is(":checked")) {
            $("#quarterCheckbox, #yearCheckbox").prop("checked", false);
            $("#selectQuarter").prop("disabled", true);
            $("#selectMonth").prop("disabled", false);
        }
    });

    $("#quarterCheckbox").change(function () {
        if ($(this).is(":checked")) {
            $("#monthCheckbox, #yearCheckbox").prop("checked", false);
            $("#selectMonth").prop("disabled", true);
            $("#selectQuarter").prop("disabled", false);
        }
    });

    $("#yearCheckbox").change(function () {
        if ($(this).is(":checked")) {
            $("#monthCheckbox, #quarterCheckbox").prop("checked", false);
            $("#selectMonth, #selectQuarter").prop("disabled", true);
            $("#selectYear").prop("disabled", false);
        }
    });
    
       $("#btn-export").click(function (e) {
        e.preventDefault();

        let url = $(this).attr("href");
        // Lấy giá trị của select box chỉ khi nó không bị vô hiệu hóa
        let month = $("#selectMonth").prop("disabled")
                    ? null
                    : $("#selectMonth").val();
        let quarter = $("#selectQuarter").prop("disabled")
                    ? null
                    : $("#selectQuarter").val();
        let year = $("#selectYear").prop("disabled")
                    ? null
                    : $("#selectYear").val();

        window.location.href =
            url + "?month=" + month + "&year=" + year + "&quarter=" + quarter;
    });
    
          //Định dạng số dễ hình hơn
      $('.price').on('input', function() {
        let value = $(this).val().replace(/,/g, ''); // Xóa bỏ dấu phẩy nếu có
        let id = $(this).attr('id');
        // Kiểm tra nếu giá trị nhập vào không phải là số
        if (isNaN(value)) {
            $('.error-price').css("display", "block").text("Nhập dữ liệu không đúng định dạng!");
            $('#' + id).css({
                "border": "1px solid red", 
                "box-shadow": "0 0 0 0.2rem rgba(255, 0, 0, .25)",
                "outline": "0"}) 
            return; 
        } 
            $('#' + id).css({
                "border": "1px solid #ced4da", 
                "box-shadow": "none",
                "outline": "0"}) 
            $('.error-price').css("display", "none").text("");
            // Format số theo dạng có dấu phẩy ngăn cách hàng nghìn
            value = Number(value).toLocaleString('en-US');
    
            // Hiển thị giá trị đã được format trong input
            $(this).val(value);
      });
});


