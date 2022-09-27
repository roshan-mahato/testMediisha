@extends('layout.mainlayout',['active_page' => 'pharmacy'])

@section('title',__('Pharmacy'))

@section('content')
<!-- search Bar -->
<div class="bg-white">
    <div class="content mx-auto">
        <div class="d-flex flex-lg-row flex-column">
            <form method="post" action="{{ url('all-pharmacies') }}">
                @csrf
                <input type="hidden" name="single_pharmacy" value="{{__('single_pharmacy')}}">
                <input type="hidden" name="pharmacy_lat">
                <input type="hidden" name="pharmacy_lang">
                <div class="ps-xl-0 ps-3 d-flex flex-md-row flex-column serach-box">
                    <div class="location position-relative mb-md-0 mb-3 ">
                        <input type="search" class="form-control loc"  onFocus="geolocate()" id="autocomplete" aria-describedby="helpId" placeholder="{{ __('Search Location') }}">
                        <i class="bx bx-map bx_icons position-absolute"></i>
                    </div>
                    <div class="location  doc position-relative d-flex">
                        <input type="search" class="form-control docto" name="search_pharmacy" aria-describedby="helpId" placeholder="{{ __('Search pharmacy.') }}">
                        <div class="location position-relative">
                            <button type="submit" class="btn btn-primary ml-2">
                                <i class='bx bx-search-alt-2'></i>
                            </button>
                        </div>
                        <i class="bx bx-search bx_icons position-absolute"></i>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- search Bar Over -->

<!-- filter -->
<input type="hidden" name="pharmacy_name" value="{{ $pharmacy->name }}">
<input type="hidden" name="pharmacy_id" value="{{ $pharmacy->id }}">
<div class="searching-filter ps-xl-0 ps-3 ">
    <div class="content py-2 d-flex flex-column mx-auto">
        <div class="d-flex">
            <div class="dropdown dropdown-hover" id="dropdownMenuLink" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">
                <a class="btn btn-secondary btn-sm dropdown-toggle" id="all_filter" href="javascript:void(0)" role="button">
                    {{ __('All Filters') }}
                </a>
            </div>
        </div>
        <div class="dropdown-menu myDrop" aria-labelledby="dropdownMenuLink">
            <div class="content d-flex flex-sm-row flex-column mx-auto">
                <div class="mt-sm-0 mt-3">
                    <h6>{{ __('Select Category') }}</h6>
                    <form id="filter_form">
                        <ul class="flex-wrap d-flex">
                            @foreach ($categories as $category)
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)">
                                        <div class="form-check pb-0 form-check-inline">
                                            <input class="form-check-input" name="select_specialist" type="checkbox" id="category{{ $category->id }}" value="{{ $category->id }}">
                                            <label class="form-check-label" for="category{{ $category->id }}">{{ $category->name }}</label>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </form>
                </div>
            </div>
        </div>


    </div>
</div>

<!-- filter Over -->
<div class="full-content">
    <div class="content px-lg-0 px-2 py-3 mx-auto">
        <h3 class="location-name">{{ $pharmacy->name }}</h3>
        <p class="mt-1 mb-3 d-flex  location-address"><i class='bx bxs-map '></i> {{ $pharmacy->address }}</p>
        <div class="row row-cols-1  row-cols-lg-4 row-cols-md-3 row-cols-sm-2 g-0 display_medicine">
            @include('website.display_medicine',['medicines' => $medicines])
        </div>
    </div>

</div>
@endsection

@section('js')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ App\Models\Setting::first()->map_key }}&sensor=false&libraries=places"></script>
    <script src="{{ url('assets/js/medicine_list.js') }}"></script>
@endsection