"use strict";

var lat , lng;
var base_url = $('input[name=base_url]').val();
lat = parseFloat($('#lat').val());
lng = parseFloat($('#lng').val());

$(document).ready(function()
{
    $('.address_btn').on('click', function()
    {
        var from = $(this).attr('data-from');
        if (from == 'add_new') {
            $('input[name=from]').val('add_new');
            lat = parseFloat($('#lat').val());
            lng = parseFloat($('#lng').val());
            initAutocomplete();
        } 
        else 
        {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: base_url + '/edit_user_address/'+$(this).attr('data-id'),
                success: function (result)
                {
                    $('input[name=from]').val('edit');
                    $('input[name=id]').val(result.data.id);
                    $('#lat').val(result.data.lat);
                    $('#lng').val(result.data.lang);
                    $('textarea[name=address]').val(result.data.address);
                    lat = parseFloat($('#lat').val());
                    lng = parseFloat($('#lng').val());
                    initAutocomplete();
                },
                error: function (err) {
                }
            });
        }
    });
});

function initAutocomplete()
{
    const map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: lat, lng: lng },
        zoom: 13,
        mapTypeId: "roadmap",
    });

    const a = new google.maps.Marker({
        position: {
            lat: lat,
            lng: lng
        },
        map,
        draggable: true,
    });

    google.maps.event.addListener(a, 'dragend', function() {
        geocodePosition(a.getPosition());
        $('#lat').val(a.getPosition().lat().toFixed(5));
        $('#lng').val(a.getPosition().lng().toFixed(5));
    });
}

function geocodePosition(pos) {
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({
    latLng: pos
    }, function(responses) {
    if (responses && responses.length > 0) {
        $('textarea[name=address]').val(responses[0].formatted_address);
    } else {
        $('textarea[name=address]').val('Cannot determine address at this location.');
    }
    });
}

function deleteData(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) =>
    {
        if (result.value) {
            $.ajax({
                headers:
                {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                dataType: "JSON",
                url: base_url + '/address_delete' + '/' + id,
                success: function (result) {
                    if (result.success == true) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                        Swal.fire(
                            'Deleted!',
                            'Your Data has been deleted.',
                            'success'
                        )
                    }
                    else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: result.data,
                        })
                    }
                },
                error: function (err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'This record is conntect with another data!'
                    })
                }
            });
        }
    });
}