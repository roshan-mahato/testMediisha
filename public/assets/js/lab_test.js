"use strict";

const progress = document.getElementById("progress");
const prev = document.getElementById("prev");
const next = document.getElementById("next");
const circles = document.querySelectorAll(".circle");
let currentActive = 1;
var currency,amount;
var $form,inputSelector,$inputs,$errorMessage,valid;


$(document).ready(function () {

    if ($('.select2').length) {
        $('.select2').select2({
            dropdownAutoWidth : true,
            width: '-webkit-fill-available'
        });
    }

    $('#pathology_category_id').on('change',function () {
        if (this.value != undefined && this.value != '' && this.value != null) {
            $.ajax({
                headers:
                {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: base_url + '/pathology_category_wise/'+this.value+'/'+$('input[name=lab_id]').val(),
                success: function (result)
                {
                    $("#pathology_id").html('');
                   result.data.forEach(element => {
                        $("#pathology_id").append('<option value="'+element.id+'">'+element.test_name+'</option>');
                        $("#pathology_id").trigger("change");
                    });

                    $("#radiology_category_id").val(null).trigger("change"); 
                    $("#radiology_id").val(null).trigger("change"); 
                    $("#radiology_id").html('').trigger("change"); 
                    $(".radiology_single_details").html('');
                },
                error: function (err) {
                   
                }
            });
        }
    });

    $('#pathology_id').on('change',function () {
        if (this.value != null && this.value != undefined && this.value != '') {
            $.ajax({
                headers:
                {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: base_url + '/single_pathology_details',
                data:{
                    id:$(this).val(),
                    lab_id: $('input[name=lab_id]').val(),
                },
                success: function (result)
                {
                    $('.pathology_single_details').removeClass('disp-none');
                    $('.pathology_single_details').html('');
                    $('input[name="amount"]').val(result.total);
                    amount = result.total;
                    $('.total_amount').text(result.total);
                    result.data.forEach(element => 
                    {
                        $('.pathology_single_details').append('<div class="col-md-4 col-lg-4">'+
                            '<label for="" class="form-label mb-1">Report Days</label>'+
                            '<p class="report_days">'+element.report_days+'</p>'+
                            '</div>'+
                            '<div class="col-md-4 col-lg-4">'+
                                '<label for="" class="form-label mb-1">Charge</label>'+
                                '<p class="report_charge">'+result.currency+element.charge+'</p>'+
                            '</div>'+
                            '<div class="col-md-4 col-lg-4">'+
                                '<label for="" class="form-label mb-1">Method</label>'+
                                '<p class="method">'+element.method+'</p>'+
                            '</div>'
                        );

                        if (element.prescription_required == 1) {
                            if ($('.presciption_required').hasClass('disp-none')) {
                                $('.presciption_required').removeClass('disp-none');
                            }
                            $('input[name=prescription_required]').val(1);
                        }
                    });
                },
                error: function (err) {
                   
                }
            });
        }
    });

    $('#radiology_category_id').on('change',function () {
        if (this.value != null && this.value != undefined && this.value != '') {
            $.ajax({
                headers:
                {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: base_url + '/radiology_category_wise/'+this.value+'/'+$('input[name=lab_id]').val(),
                success: function (result)
                {
                    $("#radiology_id").html('');
                    result.data.forEach(element => {
                        $("#radiology_id").append('<option value="'+element.id+'">'+element.screening_for+'</option>');
                        $("#radiology_id").trigger("change");
                    });

                    $("#pathology_category_id").val(null).trigger("change"); 
                    $("#pathology_id").val(null).trigger("change"); 
                    $("#pathology_id").html('').trigger("change"); 
                    $(".pathology_single_details").html(''); 
                },
                error: function (err) {}
            });
        }
    });

    $('#radiology_id').on('change',function () {
        if (this.value != null && this.value != undefined && this.value != '') {
            $.ajax({
                headers:
                {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                data:{
                    id:$(this).val(),
                    lab_id: $('input[name=lab_id]').val(),
                },
                url: base_url + '/single_radiology_details',
                success: function (result)
                {
                    $('.radiology_single_details').removeClass('disp-none');
                    $('.radiology_single_details').html('');
                    $('input[name="amount"]').val(result.total);
                    amount = result.total;
                    $('.total_amount').text(result.total);
                    result.data.forEach(element => 
                    {
                        $('.radiology_single_details').append('<div class="col-md-6 col-lg-6">'+
                            '<label for="" class="form-label mb-1">Report Days</label>'+
                            '<p class="report_days">'+element.report_days+'</p>'+
                            '</div>'+
                            '<div class="col-md-6 col-lg-6">'+
                                '<label for="" class="form-label mb-1">Charge</label>'+
                                '<p class="report_charge">'+result.currency+element.charge+'</p>'+
                            '</div>'
                        );
                        if ($('.presciption_required').hasClass('disp-none')) {
                            $('.presciption_required').removeClass('disp-none');
                        }
                        $('input[name=prescription_required]').val(1);
                    });
                },
                error: function (err) {
                   
                }
            });
        }
    });

    $('#date').on('change',function () {
        $.ajax({
            headers:
            {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            data:{
                date:$(this).val(),
                lab_id: $('input[name=lab_id]').val(),
            },
            url: base_url + '/lab_timeslot',
            success: function (result)
            {
                $('.timeSlotRow').html('');
                if (result.data.length > 0)
                {
                    $.each(result.data, function (key, value) {
                        var select;
                        if(key == 0)
                        {
                            var select = 'active';
                            $('.timeSlotRow').append('<input type="hidden" name="time" value="'+value.start_time+'">');
                        }
                        else
                            var select = '';
                            $('.timeSlotRow').append(
                            '<div class="m-1 d-flex time '+select+' timing'+key+' rounded-3" onclick="thisTime('+key+')">'+
                                '<a class="selectedClass'+key+'" href="javascript:void(0)">'+value.start_time+'</a>'+
                            '</div>');
                    });
                }
                else
                {
                    $('.timeSlotRow').html('<strong class="text-danger text-center w-100">At this time doctor is not availabel please change the date...</strong>');
                }
            },
            error: function (err) {
                
            }
        });
    });

    currency = $('input[name=currency]').val();
    amount = $('input[name=amount]').val();
    $('input[name=payment]').change(function ()
    {
        if(this.value == 'paypal')
        {
            $('.paypal_row').show();
            $('.razor_row').hide();
            $('.stripe_row').hide();
            $('.cod_row').hide();
            $('.paystack_row').hide();
            $('.flutterwave_row').hide();
            paypalPayment();
        }
        if(this.value == 'razor')
        {
            $('.paypal_row').hide();
            $('.razor_row').show();
            $('.stripe_row').hide();
            $('.cod_row').hide();
            $('.paystack_row').hide();
            $('.flutterwave_row').hide();
        }
        if(this.value == 'cod')
        {
            $('.paypal_row').hide();
            $('.razor_row').hide();
            $('.stripe_row').hide();
            $('.paystack_row').hide();
            $('.flutterwave_row').hide();
            $('.cod_row').show();
        }
        if(this.value == 'stripe')
        {
            $('.paypal_row').hide();
            $('.razor_row').hide();
            $('.stripe_row').show();
            $('.cod_row').hide();
            $('.paystack_row').hide();
            $('.flutterwave_row').hide();
            StripPayment();
        }
        if(this.value == 'paystack')
        {
            $('.paypal_row').hide();
            $('.razor_row').hide();
            $('.stripe_row').hide();
            $('.cod_row').hide();
            $('.paystack_row').show();
            $('.flutterwave_row').hide();
        }
        if(this.value == 'flutterwave')
        {
            $('.paypal_row').hide();
            $('.razor_row').hide();
            $('.stripe_row').hide();
            $('.cod_row').hide();
            $('.paystack_row').hide();
            $('.flutterwave_row').show();
        }
    });
});

function seeData1(id) 
{
    if ($('.pathology_div').hasClass('active_type')) {
        $('.pathology_div').removeClass('active_type');
    }
    else
    {
        $('.pathology_div').addClass('active_type');
    }
    
    if ($('.radiology_div').hasClass('active_type')) {
        $('.radiology_div').removeClass('active_type');
    }
    else
    {
        $('.radiology_div').addClass('active_type');
    }
    $(id).addClass("disp-block");
    $(id).siblings().removeClass("disp-block");
    $(id).siblings().addClass("disp-none");
}

if (next) {
    next.addEventListener("click", () => {
      currentActive++;
      if (currentActive > circles.length) currentActive = circles.length;
      update();
      shoeStep();
    });
}

if (prev) {
    prev.addEventListener("click", () => {
      currentActive--;
      if (currentActive < 1) currentActive = 1;
      update();
      shoeStep();
    });
}

const update = () => {
  circles.forEach((circle, index) => {
    if (index < currentActive) circle.classList.add("progress_active");
    else circle.classList.remove("progress_active");
  });
  const actives = document.querySelectorAll(".progress_active");
  progress.style.width =
    ((actives.length - 1) / (circles.length - 1)) * 100 + "%";
  if (currentActive === 1) prev.disabled = true;
  else if (currentActive === circles.length) next.disabled = true;
  else {
    prev.disabled = false;
    next.disabled = false;
  }
};

function shoeStep() {
  if ($(circles).filter(".progress_active").length == 1) {
    seeData("#step1");
  }
  if ($(circles).filter(".progress_active").length == 2) {
    seeData("#step2");
  }
  if ($(circles).filter(".progress_active").length == 3) {
    seeData("#step3");
    $("#payment").addClass("d-block");
    $("#next").addClass("d-none");
    $("#payment").removeClass("d-none");
  } else {
    $("#payment").removeClass("d-block");
    $("#payment").addClass("d-none");
    $("#next").removeClass("d-none");
  }
}

function geolocate()
{
    var autocomplete = new google.maps.places.Autocomplete(
        /** @type {HTMLInputElement} */(document.getElementById('autocomplete')),
        { types: ['geocode'] });
    google.maps.event.addListener(autocomplete, 'place_changed', function()
    {
        var lat = autocomplete.getPlace().geometry.location.lat();
        var lang = autocomplete.getPlace().geometry.location.lng();
        $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        data:{
            lat:lat,
            lang:lang,
        },
        url: base_url + '/labs',
        success: function (result)
        {
            $('.display_lab').html('');
            $('.display_lab').append(result.html);
        },
        error: function (err) {

        }
        });
    });
}

function searchPharmacy() {
    var search_val = $('input[name=search_pharmacy]').val();
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        data:{
            search_val:search_val,
        },
        url: base_url + '/labs',
        success: function (result)
        {
            $('.display_lab').html('');
            $('.display_lab').append(result.html);
        },
        error: function (err) {

        }
    });
}

function report_book() {
    var formData = new FormData($('#testForm')[0]);
    var time = formData.getAll('time');
    formData.delete('time');
    formData.append('time',time[0]);
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: base_url + '/test_report',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (result)
        {
            if (result.success == true)
            {
                location.replace(base_url+'/user_profile');
            }
            else
            {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Payment not complete",
                }
            )}
        },
        error: function (err)
        {
            $('#prev').trigger('click');
            $('#prev').trigger('click');
            $(".invalid-div span").html('');
            for (let v1 of Object.keys( err.responseJSON.errors)) {
                $(".invalid-div ."+v1).html(Object.values(err.responseJSON.errors[v1]));
            }
        }
    });
}

function paypalPayment()
{
    if(currency != 'INR')
    {
        $('.paypal_row_body').html('');
        paypal_sdk.Buttons({
            createOrder: function (data, actions)
            {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: amount
                        }
                    }]
                });
            },
            onApprove: function (data, actions)
            {
                return actions.order.capture().then(function (details)
                {
                    $('input[name=payment_token]').val(details.id);
                    $('input[name=payment_type]').val('PAYPAL');
                    $('input[name=payment_status]').val(1);
                    report_book();
                });
            }
        }).render('.paypal_row_body');
    }
    else
    {
        $('.paypal_row_body').html('INR currency not supported in Paypal');
    }
}

function StripPayment()
{
    $form = $(".require-validation");
    $('.btn-submit').bind('click', function (e)
    {
        $form = $(".require-validation"),
        inputSelector = ['input[type=email]', 'input[type=password]','input[type=text]', 'input[type=file]','textarea'].join(', '),
        $inputs = $form.find('.required').find(inputSelector),
        $errorMessage = $form.find('div.error'),
        valid = true;
        $errorMessage.addClass('hide');

        $('.has-error').removeClass('has-error');
        $inputs.each(function (i, el) {
            var $input = $(el);
            if ($input.val() === '')
            {
                $input.parent().addClass('has-error');
                $errorMessage.removeClass('hide');
                e.preventDefault();
            }
        });
        var month = $('.expiry-date').val().split('/')[0];
        var year = $('.expiry-date').val().split('/')[1];
        $('.card-expiry-month').val(month);
        $('.card-expiry-year').val(year);

        if (!$form.data('cc-on-file'))
        {
            e.preventDefault();
            Stripe.setPublishableKey($('input[name=stripe_publish_key]').val());

            Stripe.createToken({
                number: $('.card-number').val(),
                cvc: $('.card-cvc').val(),
                exp_month: $('.card-expiry-month').val(),
                exp_year: $('.card-expiry-year').val()
            }, stripeResponseHandler);
        }
    });
}

function stripeResponseHandler(status, response)
{
    if (response.error) {
        $('.stripe_alert').show();
        $('.stripe_alert').text(response.error.message);
    }
    else
    {
        var token = response['id'];
        $form.find('input[type=text]').empty();
        $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
        var paymentData = new FormData();
        paymentData.append('amount',amount);
        paymentData.append('stripeToken',token);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: base_url + '/stripePayment',
            data: paymentData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (result)
            {
                if (result.success == true)
                {
                    $('input[name=payment_token]').val(result.data);
                    $('input[name=payment_type]').val('STRIPE');
                    $('input[name=payment_status]').val(1);
                    report_book();
                }
                else
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Payment not complete",
                    }
                )}
            },
            error: function (err)
            {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: err.responseJSON.message,
                })
            }
        });
    }
}

function payWithPaystack()
{
    var handler = PaystackPop.setup(
    {
        key: $('#paystack-public-key').val(),
        email: document.getElementById('email-address').value,
        amount: amount * 100,
        currency: currency,
        ref: Math.floor(Math.random() * (999999 - 111111)) + 999999,
        callback: function (response)
        {
            $('input[name=payment_token]').val(response.reference);
            $('input[name=payment_type]').val('PAYSTACK');
            $('input[name=payment_status]').val(1);
            report_book();
        },
        onClose: function ()
        {
            alert('Transaction was not completed, window closed.');
        },
    });
    handler.openIframe();
}

function makePayment()
{
    FlutterwaveCheckout({
      public_key: $('input[name=flutterwave_key]').val(),
      tx_ref: Math.floor(Math.random() * (1000 - 9999 + 1) ) + 9999,
      amount: amount,
      currency: currency,
      payment_options: " ",
      customer: {
        email: $('input[name=email]').val(),
        phone_number: $('input[name=phone]').val(),
        name: $('input[name=name]').val(),
      },
      callback: function (data)
      {
        if (data.status == 'successful')
        {
            $('input[name=payment_status]').val(1);
            $('input[name=payment_token]').val(data.transaction_id);
            $('input[name=payment_type]').val('FLUTTERWAVE');
            report_book();
        }
      },
      customizations: {
        title: $('input[name=company_name]').val(),
        description: "Test Report",
      },
    });
}

function RazorPayPayment()
{
    var options =
    {
        key: $('#RAZORPAY_KEY').val(),
        amount: amount * 100,
        description: '',
        currency: currency,
        handler: demoSuccessHandler
    }
    window.r = new Razorpay(options);
    document.getElementById('paybtn').onclick = function ()
    {
        r.open();
    }
}

function padStart(str) {
    return ('0' + str).slice(-2)
}

function demoSuccessHandler(transaction)
{
    $("#paymentDetail").removeAttr('style');
    $('#paymentID').text(transaction.razorpay_payment_id);
    var paymentDate = new Date();
    $('#paymentDate').text(
        padStart(paymentDate.getDate()) + '.' + padStart(paymentDate.getMonth() + 1) + '.' + paymentDate.getFullYear() + ' ' + padStart(paymentDate.getHours()) + ':' + padStart(paymentDate.getMinutes())
    );
    $('input[name=payment_status]').val(1);
    $('input[name=payment_token]').val(transaction.razorpay_payment_id);
    $('input[name=payment_type]').val('RAZOR');
    report_book();
}

// add selected class
function thisTime(i)
{
    $(".time").removeClass('active');
    $('.timing'+i).addClass('active');
    $('input[name=time]').val($.trim($('.selectedClass'+i).text()));
}
