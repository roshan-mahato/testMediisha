"use strict";
var base_url = $('input[name=base_url]').val();
$(document).ready(function () {
    $("#all_filter").click(function () {
        $(".myDrop").toggleClass("show");
    });
    
    $("#Sortbtn").click(function () {
        $(".myDrop").removeClass("show");
    });
    
    $(".dd").click(function () {
        $(this).attr("tabindex", 1).focus();
        $(this).toggleClass("active");
        $(this).find(".dd-menu").slideToggle(300);
    });
    
    $(".dd").focusout(function () {
        $(this).removeClass("active");
        $(this).find(".dd-menu").slideUp(300);
    });
    
    $(".dd .dd-menu li").click(function () {
        $(this).parents(".dd").find("span").text($(this).text());
        $(this).parents(".dd").find("input").attr("value", $(this).attr("id"));
        var value = $(this).parents(".dd").find("input").attr("value", $(this).attr("id")).val();
        $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        data:{
            value:value,
        },
        url: base_url + '/show-doctors',
        success: function (result)
        {
            $('.dispDoctor').html('');
            $('.dispDoctor').append(result.html);
            categories.length = 0;
        },
        error: function (err) {
    
        }
        });
    });
    
    $('#filter_form').change(function () {
        var categories = [];
        var gender = $('input[name="gender_type"]:checked').val();
        $('input[name="select_specialist"]:checked').each(function(i)
        {
            if(categories.indexOf(this.value) === -1) {
            categories.push(this.value);
            }
        });

        $.ajax({
          headers:
          {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type: "POST",
          data:{
              category:categories,
              gender_type:gender,
              from : 'js'
          },
          url: base_url + '/show-doctors',
          success: function (result)
          {
              $('.dispDoctor').html('');
              $('.dispDoctor').append(result.html);
              $(".myDrop").toggleClass("show");
              categories.length = 0;
          },
          error: function (err) {

          }
        });
    });
});

function geolocate()
{
    var autocomplete = new google.maps.places.Autocomplete(
        /** @type {HTMLInputElement} */(document.getElementById('autocomplete')),
        { types: ['geocode'] });
    google.maps.event.addListener(autocomplete, 'place_changed', function()
    {
        var lat = autocomplete.getPlace().geometry.location.lat();
        var lang = autocomplete.getPlace().geometry.location.lng();
        $('input[name=doc_lat]').val(lat);
        $('input[name=doc_lang]').val(lang);
        if (base_url+'/show-doctors' == window.location.href) {
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
            url: base_url + '/show-doctors',
            success: function (result)
            {
                $('.dispDoctor').html('');
                $('.dispDoctor').append(result.html);
            },
            error: function (err) {

            }
            });
        }
    });
}

function searchDoctor() {
    var search_val = $('input[name=search_doctor]').val();
    $.ajax({
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        data:{
            search_val:search_val,
        },
        url: base_url + '/show-doctors',
        success: function (result)
        {
            $('.dispDoctor').html('');
            $('.dispDoctor').append(result.html);
        },
        error: function (err) {

        }
    });
}