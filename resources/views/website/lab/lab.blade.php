@extends('layout.mainlayout',['active_page' => 'lab_test'])

@section('title',__('Laboratory'))

@section('content')
    <div class="jumbo pt-3">
        <div class="jumbo_ban mx-auto">
            <h1 class="text-white text-center">{{__('Your Home For Health')}}</h1>
            <h3 class="text-white text-center">{{__('Find Better.Appoint Better')}}</h3>
            <div class="content mx-auto doctor">
                <div class="d-flex mx-auto flex-md-row flex-column serach-box pb-0">
                    <div class="location position-relative mb-md-0 mb-3 ">
                        <input type="search" class="form-control loc" id="autocomplete"  onFocus="geolocate()" aria-describedby="helpId" placeholder="{{ __('Search Location') }}">
                        <i class='bx bx-map bx_icons position-absolute'></i>
                    </div>
                    <div class="location  doc position-relative">
                        <input type="search" class="form-control docto" name="search_pharmacy" onkeyup="searchPharmacy()" aria-describedby="helpId" placeholder="{{ __('Search Laboratories') }}">
                        <i class='bx bx-search bx_icons position-absolute'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="full-content  pharmacy">
        <div class="display_lab">
            @include('website.lab.display_lab',['labs' => $labs])
        </div>
    </div>

@endsection

@section('js')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ App\Models\Setting::first()->map_key }}&sensor=false&libraries=places"></script>
    <script src="{{ url('assets/js/lab_test.js') }}"></script>
@endsection