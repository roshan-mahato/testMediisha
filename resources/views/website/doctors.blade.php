@extends('layout.mainlayout',['active_page' => 'doctors'])

@section('title',__('Doctors'))

@section('content')
    <div class="site-body">
        <div class="jumbo pt-3">
            <div class="jumbo_ban mx-auto">
                <h1 class="text-white text-center">{{__('Your Home For Health')}}</h1>
                <h3 class="text-white text-center">{{__('Find Better.Appoint Better')}}</h3>
                <div class="content mx-auto doctor">
                    <div class="d-flex mx-auto flex-md-row flex-column serach-box pb-0">
                        <div class="location position-relative mb-md-0 mb-3 ">
                            <input type="search" class="form-control loc" id="autocomplete" onFocus="geolocate()" name="doctor_location" aria-describedby="helpId" placeholder="{{ __('Search Location') }}">
                            <i class='bx bx-map position-absolute bx_icons'></i>
                        </div>
                        <div class="location doc position-relative">
                            <input type="search" class="form-control docto" name="search_doctor" onkeyup="searchDoctor()" aria-describedby="helpId" placeholder="{{ __('Search doctors') }}">
                            <i class='bx bx-search position-absolute bx_icons'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="full-content">
            <div class="searching-filter ps-xl-0 ps-3 ">
                <div class="content py-2 d-flex flex-column mx-auto">
                    <div class="d-flex">
                        <div class="dropdown  dropdown-hover" id="dropdownMenuLink" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" aria-expanded="true">
                            <a class="btn btn-secondary  btn-sm  dropdown-toggle" id="all_filter" href="javascript:void(0)" role="button">
                                {{ __('All Filters') }}
                            </a>
                        </div>
                        <div class="ms-4 d-flex align-items-center select-Sort">
                            <div id="Sortbtn" class="dd">
                                <div class="select">
                                    <span>{{__('Sort By')}}</span>
                                    <i class='bx bxs-chevron-down'></i>
                                </div>
                                <input type="hidden" name="gender">
                                <ul class="dd-menu">
                                    <li class="value" id="rating" data-value="rating">{{__('Rating')}}</li>
                                    <li class="value" id="popular" data-value="popular">{{__('Popular')}}</li>
                                    <li class="value" id="latest" data-value="latest">{{__('Latest')}}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-menu myDrop" aria-labelledby="dropdownMenuLink">
                        <form id="filter_form" method="post">
                            <div class="content d-flex flex-sm-row flex-column mx-auto">
                                <ul>
                                    <h6>{{ __('Gender') }}</h6>
                                    <li>
                                        <a class="dropdown-item " href="javascript:void(0)">
                                            <div class="form-check pb-0 form-check-inline">
                                                <input class="form-check-input" type="radio" id="male" name="gender_type" value="male">
                                                <label class="form-check-label" for="male">{{ __('Male Doctor') }}</label>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item " href="javascript:void(0)">
                                            <div class="form-check pb-0 form-check-inline">
                                                <input class="form-check-input" type="radio" id="female" name="gender_type" value="female">
                                                <label class="form-check-label" for="female">{{ __('Female Doctor')}}</label>
                                            </div>
                                        </a>
                                    </li>

                                </ul>
                                <div class="mt-sm-0 mt-3">
                                    <h6>{{__('Select Category')}}</h6>
                                    <ul class="flex-wrap d-flex">
                                        @foreach ($categories as $category)
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">
                                                    <div class="form-check pb-0 form-check-inline">
                                                        <input class="form-check-input" name="select_specialist" type="checkbox" data-id={{$category->id}} id="category{{$category->id}}" value="{{$category->id}}">
                                                        <label class="form-check-label" for="category{{$category->id}}">{{$category->name}}</label>
                                                    </div>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="dispDoctor">
                @include('website.display_doctor',['doctor' => $doctors])
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ App\Models\Setting::first()->map_key }}&sensor=false&libraries=places"></script>
    <script src="{{ url('assets/js/doctor_list.js') }}"></script>
@endsection