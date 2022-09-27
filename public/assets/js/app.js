"use strict";
var base_url = $('input[name=base_url]').val();

$(window).on('load', function () {
    if($('#loader').length > 0) {
        $('#loader').delay(350).fadeOut('slow');
        $('body').delay(500).css({ 'overflow': 'visible' });
    }
})

$(".add-favourite").click(function () 
{
    $(this).toggleClass("active");
    if ($(this).find("i").hasClass("bx-bookmark-heart") && $(this).hasClass("active")) 
    {
        $(this).find("i").removeClass("bx-bookmark-heart");
        $(this).find("i").addClass("bxs-bookmark-heart");
    } 
    else if ($(this).find("i").hasClass("bxs-bookmark-heart")) 
    {
        $(this).find("i").removeClass("bxs-bookmark-heart");
        $(this).find("i").addClass("bx-bookmark-heart");
    }
    var doctor_id = $(this).attr('data-id');
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: base_url + '/addBookmark/'+doctor_id,
        success: function (result)
        {
            if(result.success == false)
                window.location.href = base_url+'/patient-login';
            else
            {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })
                Toast.fire({
                    icon: 'success',
                    title: result.msg
                })
            }
        },
        error: function (err) {

        }
    });
});

$(document).ready(function () {
    addactiveclass(".edit-profile-menu li.user-profile-link", "active");
    addactiveclass(".single-nav ul li", "active");
    addactiveclass(".slotes .time", "active");

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#img_preview1").css(
                    "background-image",
                    "url(" + e.target.result + ")"
                );
                $("#img_preview1").hide();
                $("#img_preview1").fadeIn(650);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#imageUpload").change(function () {
        readURL(this);
    });

    function readURL1(input) 
    {
        if (input.files && input.files[0]) 
        {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.avta-prview-1').css('background-image', 'url(' + e.target.result + ')');
                $('.avta-prview-1').hide();
                $('.avta-prview-1').fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#image1").change(function () {
        readURL1(this);
    });

    function readURL2(input) 
    {
        if (input.files && input.files[0]) 
        {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.avta-prview-2').css('background-image', 'url(' + e.target.result + ')');
                $('.avta-prview-2').hide();
                $('.avta-prview-2').fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#image2").change(function () {
        readURL2(this);
    });

    function readURL3(input) 
    {
        if (input.files && input.files[0]) 
        {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.avta-prview-3').css('background-image', 'url(' + e.target.result + ')');
                $('.avta-prview-3').hide();
                $('.avta-prview-3').fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#image3").change(function () {
        readURL3(this);
    });
});

function seeData(id) 
{
    $(id).addClass("disp-block");
    $(id).siblings().removeClass("disp-block");
    $(id).siblings().addClass("disp-none");
}

function addactiveclass(id, classname) 
{
    $(id).click(function () {
        $(this).addClass(classname);
        $(this).siblings().removeClass(classname);
    });
}

var datatable = $('.datatable').DataTable({
    language: {
        paginate: {
        previous: "<i class='fa fa-angle-left'>",
        next: "<i class='fa fa-angle-right'>",
        first: "<i class='fa fa-angle-double-left'>",
        last: "<i class='fa fa-angle-double-right'>",
        }
    },
    pagingType: "full_numbers",
});

// Display Appointment
function show_appointment(appointment_id) {
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: base_url + '/show_appointment/'+appointment_id,
        success: function (result)
        {
            $('.appointment_id').text(result.data.appointment_id);
            $('.doctor_name').text(result.data.doctor.name);
            $('.amount').text(result.currency+result.data.amount);
            $('.date').text(result.data.date);
            $('.time').text(result.data.time);
            if(result.data.payment_status == 0)
            {
                $('.payment_status').text('payment not complete')
            }
            else
            {
                $('.payment_status').text('payment complete')
            }
            $('.payment_type').text(result.data.payment_type);
            $('.illness_info').text(result.data.illness_information);
            $('.patient_name').text(result.data.patient_name);
            $('.patient_address').text(result.data.patient_address);
            $('.patient_age').text(result.data.age);
        },
        error: function (err) {

        }
    });
}

function appointId(id)
{
    $('input[name=appointment_id]').val(id);
    $('input[name=id]').val(id);
}

// add review
function addReview()
{
    var formData = new FormData($('#reviewForm')[0]);
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: base_url + '/addReview',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (result)
        {
            $(".invalid-div span").html('');
            if(result.success == true)
            {
                location.reload();
            }
            else
            {
                $(".invalid-div .review").html(result.data);
            }
        },
        error: function (err) {
            $(".invalid-div span").html('');
            for (let v1 of Object.keys( err.responseJSON.errors)) {
                $(".invalid-div ."+v1).html(Object.values(err.responseJSON.errors[v1]));
            }
        }
    });
}

function cancelAppointment()
{
    var formData = new FormData($('#cancelForm')[0]);
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: base_url + '/cancelAppointment',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (result)
        {
            $(".invalid-div span").html('');
            if(result.success == true)
            {
                location.reload();
            }
            else
            {
                $(".invalid-div .review").html(result.data);
            }
        },
        error: function (err) {
            $(".invalid-div span").html('');
            for (let v1 of Object.keys( err.responseJSON.errors)) {
                $(".invalid-div ."+v1).html(Object.values(err.responseJSON.errors[v1]));
            }
        }
    });
}

function show_medicines(id) {
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: base_url + '/display_purchase_medicine/'+id,
        success: function (result) {
            if (result.success == true)
            {
                $('.shippingAt').text(result.data.shipping_at);
                if(result.data.shipping_at == 'home')
                {
                    $('.shippingAddressTr').show();
                    $('.shippingAddress').text(result.data.address.address);
                    $('.deliveryCharge').text(result.currency+result.data.delivery_charge);
                }
                else
                {
                    $('.shippingAddressTr').hide();
                }
                $('.tbody').html('');
                result.data.medicine_name.forEach(element =>
                {
                    $('.tbody').append(
                        '<tr><td>'+element.name+'</td>'+
                        '<td>'+element.qty+'</td>'+
                        '<td>'+result.currency+element.price+'</td></tr>'
                    );
                });
            }
        },
        error: function (err) {
        }
    });
}

function single_report(id) {
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: base_url + '/single_report/'+id,
        success: function (result) {
            if (result.success == true)
            {
                $('.report_id').text(result.data.report_id);
                $('.patient_name').text(result.data.patient_name);
                $('.patient_phone').text(result.data.phone_no);
                $('.patient_age').text(result.data.age);
                $('.patient_gender').text(result.data.gender);
                $('.amount').text(result.currency + result.data.amount);
                if (result.data.payment_status == 1) {
                    $('.payment_status').text('Complete');
                } else {
                    $('.payment_status').text('Not Complete');
                }
                $('.payment_type').text(result.data.payment_type);
                if (result.data.radiology_category == null) {
                    $('.radiology_category_id').hide();
                }
                else
                {
                    $('.radiology_category').text(result.data.radiology_category);
                    $('.types').html('');
                    $('.types').append(
                        '<thead><tr><th>Screening For</th>'+
                        '<th>Charge</th>'+
                        '<th>Report Days</th></tr></thead><tbody></tbody>'
                    );
                    result.data.radiology.forEach(element =>
                    {
                        $('.types tbody').append(
                            '<tr><td>'+element.screening_for+'</td>'+
                            '<td>'+result.currency+element.charge+'</td>'+
                            '<td>'+element.report_days+'</td></tr>'
                        );
                    });
                }

                if (result.data.pathology_category == null) {
                    $('.pathology_category_id').hide();
                    $('.patho_test_type').hide();
                }
                else
                {
                    $('.pathology_category').text(result.data.pathology_category);
                    $('.types').html('');
                    $('.types').append(
                        '<thead><tr><th>Test Name</th>'+
                        '<th>Charge</th>'+
                        '<th>Report Days</th>'+
                        '<th>Method</th></tr></thead><tbody></tbody>'
                    );
                    result.data.pathology.forEach(element =>
                    {
                        $('.types tbody').append(
                            '<tr><td>'+element.test_name+'</td>'+
                            '<td>'+result.currency+element.charge+'</td>'+
                            '<td>'+element.report_days+'</td>'+
                            '<td>'+element.method+'</td></tr>'
                        );
                    });
                }
            }
        },
        error: function (err) {
        }
    });
}