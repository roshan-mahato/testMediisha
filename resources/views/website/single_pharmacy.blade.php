@extends('layout.mainlayout',['active_page' => 'single_pharmacy'])

@section('title',$pharmacy->name.__(' Pharmacy'))

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

    <div class="full-content">
        <div class="content px-lg-0 px-2 py-3 mx-auto">
            <div class="row g-lg-0">
                <!-- Profile Info -->
                <div class="col-lg-4">
                    <div class="doc-profile bg-white rounded-2 h-100 p-3 p-2">
                        <div class="pharmacy-pic pb-2">
                            <img src="{{ $pharmacy->full_image }}" alt="">
                        </div>
                        <div class="doct-card ">
                            <div class=" doctor-info d-flex flex-column w-100">
                                <div class="personalInfo">
                                    <div>
                                        <h6>{{ $pharmacy->name }}</h6>
                                    </div>
                                    <div class="d-flex mt-3  text-center">
                                        <i class="bx bxs-phone-call"></i>
                                        <p class="mb-0 ps-1">{{ $pharmacy->phone }}</p>
                                    </div>
                                    <div class="d-flex mt-2 align-items-center fbk ">
                                        <i class="bx bx-mail-send"></i>
                                        <p>{{ $pharmacy->email }}</p>
                                    </div>
                                    <div class="d-flex mt-2 align-items-center ">
                                        <i class="bx bxs-door-open"></i>
                                        <p>{{ __('Opens At ') }}{{ $pharmacy->openTime }}</p>
                                    </div>
                                </div>
                                <div class="location mt-2 mb-2 d-flex">
                                    <i class="bx bx-map"></i>
                                    <p>{{ $pharmacy->address }}</p>
                                </div>


                                <div class="d-flex my-3 flex-lg-column flex-sm-row flex-column justify-content-sm-start w-100">
                                    <div class="btn-appointment mb-2 me-lg-0 me-sm-2 me-0">
                                        <a href="mailto:{{ $pharmacy->email }}" class="view-profile btn btn-outline-secondary login-btn w-100">{{ __('Send Mail') }}</a>
                                    </div>
                                    <div class="btn-appointment mb-2 me-lg-0 me-sm-2 me-0">
                                        <a class="btn btn-link text-center mt-0 w-100" href="tel:{{ $pharmacy->phone }}" role="button">{{ __('Contact') }}</a>
                                    </div>
                                    <div class="btn-appointment ">
                                        <a class="btn btn-link text-center mt-0 w-100"
                                            href="{{ url('pharmacy_product/'.$pharmacy->id.'/'.Str::slug($pharmacy->name)) }}" role="button">{{ __('Browse Products') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Profile Info Over -->

                <div class="col-lg-8">
                    <div class="m-2 my-xl-0 pb-2 bg-white h-100 rounded-2">
                        <div class="px-3 single-dis pt-3 pb-1 single-pharmacy">

                            <div id="doctor_information">
                                <h6 class="common_head">{{ __('Overview') }}</h6>
                                <div class="border p-3 rounded-3">
                                    <div class="mb-2">
                                        <h6 class="common_head">{{ __('About Me') }}</h6>
                                        <p>{!! clean($pharmacy->description) !!}</p>
                                    </div>
                                </div>
                            </div>

                            <div id="Bus-hour" class=" mt-3">
                                <h6 class="common_head">{{ __('Business Hours') }}</h6>
                                <div class="border p-3 business_hour  rounded-3 ">
                                    @foreach ($pharmacy->workHour as $hour)
                                        <div class="d-flex day-cal w-100 mb-2 align-items-center pb-2 border-bottom">
                                            <p>{{ $hour->day_index }}</p>
                                            @if ($hour->status == 0)
                                                <span class="ms-auto closed">{{ __('Closed') }}</span>
                                            @else
                                                @foreach (json_decode($hour->period_list) as $item)
                                                    <span class="ms-auto open">{{ $item->start_time }} {{__( 'to' )}} {{ $item->end_time }}</span>
                                                @endforeach
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('js')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ App\Models\Setting::first()->map_key }}&sensor=false&libraries=places"></script>
    <script src="{{ url('assets/js/medicine_list.js') }}"></script>
@endsection