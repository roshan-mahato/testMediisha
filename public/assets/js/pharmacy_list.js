"use strict";

var categories;

$(document).ready(function () {
    $("#all_filter").click(function () {
      $(".myDrop").toggleClass("show");
    });

    $("#filter_form").change(function () 
    {
        categories = [];
        $('input[name="select_specialist"]:checked').each(function(i)
        {
            if(categories.indexOf(this.value) === -1)
              categories.push(this.value);
        });
        $.ajax({
            headers:
            {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            data:{
                category:categories,
            },
            url: base_url + '/all-pharmacies',
            success: function (result)
            {
                $('.display_pharmacy').html('');
                $('.display_pharmacy').append(result.html);
                $(".myDrop").toggleClass("show");
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
        $('input[name=pharmacy_lat]').val(lat);
        $('input[name=pharmacy_lang]').val(lang);
        if (base_url+'/all-pharmacies' == window.location.href) {
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
            url: base_url + '/all-pharmacies',
            success: function (result)
            {
                $('.display_pharmacy').html('');
                $('.display_pharmacy').append(result.html);
            },
            error: function (err) {

            }
            });
        }
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
        url: base_url + '/all-pharmacies',
        success: function (result)
        {
            $('.display_pharmacy').html('');
            $('.display_pharmacy').append(result.html);
        },
        error: function (err) {

        }
    });
}