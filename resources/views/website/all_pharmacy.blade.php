@extends('layout.mainlayout',['active_page' => 'pharmacy'])

@section('title',__('Pharmacy'))

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
                        <input type="search" class="form-control docto" name="search_pharmacy" onkeypress="searchPharmacy()" aria-describedby="helpId" placeholder="{{ __('Search pharmacies') }}">
                        <i class='bx bx-search bx_icons position-absolute'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="full-content  pharmacy">

        <div class="searching-filter ps-xl-0 ps-3 ">
            <div class="content py-2 d-flex flex-column mx-auto">
                <div class="d-flex">
                    <div class="dropdown  dropdown-hover" id="dropdownMenuLink" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">
                        <a class="btn btn-secondary  btn-sm  dropdown-toggle" id="all_filter" href="javascript:void(0)" role="button">
                            {{__('All Filters')}}
                        </a>
                    </div>

                </div>
                <div class="dropdown-menu myDrop" aria-labelledby="dropdownMenuLink">
                    <div class="content d-flex flex-sm-row flex-column mx-auto">
                        <div class="mt-sm-0 mt-3">
                            <form id="filter_form" method="post">
                                <ul class="flex-wrap d-flex">
                                    <li>
                                        <a class="dropdown-item " href="javascript:void(0)">
                                            <div class="form-check pb-0 form-check-inline">
                                                <input class="form-check-input" name="select_specialist" type="checkbox" id="popular" value="popular">
                                                <label class="form-check-label" for="popular">{{ __('Popular') }}</label>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item " href="javascript:void(0)">
                                            <div class="form-check pb-0 form-check-inline">
                                                <input class="form-check-input" name="select_specialist" type="checkbox" id="latest" value="latest">
                                                <label class="form-check-label" for="latest">{{ __('Latest') }}</label>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item " href="javascript:void(0)">
                                            <div class="form-check pb-0 form-check-inline">
                                                <input class="form-check-input" name="select_specialist" type="checkbox" id="available" value="opening">
                                                <label class="form-check-label" for="available">{{ __('Availability') }}</label>
                                            </div>
                                        </a>
                                    </li>

                                </ul>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="display_pharmacy">
            @include('website.display_pharmacy',['pharmacy' => $pharmacies])
        </div>
    </div>
@endsection

@section('js')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ App\Models\Setting::first()->map_key }}&sensor=false&libraries=places"></script>
    <script src="{{ url('assets/js/pharmacy_list.js') }}"></script>
@endsection