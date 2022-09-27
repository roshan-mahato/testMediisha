@extends('layout.mainlayout',['active_page' => 'single_doctor'])

@section('title',$doctor->name.' Profile')

@section('content')
    <div class="bg-white">
        <div class="content mx-auto">
            <div class="d-flex flex-lg-row flex-column">
                <form method="post" action="{{ url('show-doctors') }}">
                    @csrf
                    <input type="hidden" name="single_doctor" value="{{__('single_doctor')}}">
                    <input type="hidden" name="doc_lat">
                    <input type="hidden" name="doc_lang">
                    <div class="ps-xl-0 ps-3 d-flex flex-md-row flex-column serach-box">
                        <div class="location position-relative mb-md-0 mb-3 ">
                            <input type="search" class="form-control loc"  onFocus="geolocate()" id="autocomplete" name="doctor_location" aria-describedby="helpId" placeholder="{{ __('Search Location') }}">
                            <i class='bx bx-map position-absolute bx_icons'></i>
                        </div>
                        <div class="location doc position-relative d-flex">
                            <input type="search" class="form-control docto" name="search_doctor" aria-describedby="helpId" placeholder="{{ __('Search doctors, clinics, hospitals, etc.') }}">
                            <div class="location position-relative">
                                <button type="submit" class="btn btn-primary ml-2">
                                    <i class='bx bx-search-alt-2'></i>
                                </button>
                            </div>
                            <i class='bx bx-search position-absolute bx_icons'></i>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="full-content">
        <div class="content px-lg-0 px-2 py-3 mx-auto">
            <div class="row">
                <div class="col-lg-6">
                    <div class="doc-profile bg-white rounded-2 h-100 p-3">
                        <div class="d-flex  flex-sm-row flex-column align-items-sm-center">
                            <div class="doc-profile-img  pb-2  me-3">
                                <img src="{{ $doctor->fullImage }}" class="rounded-circle " alt="">
                            </div>
                            <div class="doct-card doctor-info ">
                                <div class="personalInfo">
                                    <div>
                                        <h6>{{$doctor->name}}</h6>
                                    </div>
                                    <div class="post d-flex mt-1 align-items-center">
                                        @if (isset($doctor->expertise) && isset($doctor->category))
                                            <p class=" text-muted">{{$doctor->expertise['name']}}
                                            <img src="{{ $doctor->category['fullImage'] }}" class="ms-1 ps-1 mb-0 border-start" alt="">
                                            <p class="ps-2 mb-0">{{$doctor->category['name']}}</p>
                                            </p>
                                        @endif
                                    </div>

                                    <div class="rating d-flex align-items-center">
                                        @for ($i = 1; $i < 6; $i++)
                                            @if ($i <= $doctor['rate'])
                                                <i class='bx bxs-star active'></i>
                                            @else
                                                <i class='bx bxs-star'></i>
                                            @endif
                                        @endfor
                                        <span class="d-inline-block average-rating">({{ $doctor['rate'] }})</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex mt-2 align-items-center ">
                                <i class="bx bx-money"></i>
                                <p class="ms-2"> {{$currency}} <span>{{ $doctor['appointment_fees'] }}</span></p>
                            </div>
                            <div class="d-flex mt-2 align-items-center fbk ">
                                <i class="bx bx-message-dots"></i>
                                <p class="ms-2"> <span>{{ $doctor['review'] }}</span> {{ __(' Feedback') }}</p>
                            </div>

                            <div class="location my-2 d-flex">
                                <i class="bx bx-map me-3"></i>
                                <p>{{ $doctor['hospital']['address'] }}</p>
                            </div>
                            <div class="doctor-info d-flex ">
                                <div class="btn-appointment ms-auto mt-sm-0 mt-3">
                                    <a class="btn btn-link text-center mt-0"
                                        href="{{ url('booking/'.$doctor->id.'/'.Str::slug($doctor->name)) }}" role="button">{{__('Book Appointment')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg-6">
                    <div class="schedule bg-white rounded-3 h-100 ">
                        <div class="d-flex align-items-center border-bottom p-3">
                            <i class='bx bx-calendar-plus vid-icon text-center'></i>
                            <h6 class="ms-2">{{ __('Doctor Availablity') }}</h6>
                        </div>
                        <div class="d-flex book-slot shadow-sm">
                            <div class="w-50 d-flex p-2 align-items-center justify-content-center flex-column border-end" onclick="seeData('#toDays')">
                                <p>{{ __('Today') }}</p>
                                <p class="available">{{ count($today_timeslots) }} {{ __('Slots Available') }}</p>
                            </div>
                            <div class="w-50 d-flex p-2 align-items-center justify-content-center border-start flex-column" onclick="seeData('#tomorrows')">
                                <p>{{ __('Tomorrow') }}</p>
                                <p class="available">{{ count($tomorrow_timeslots) }} {{ __('Slots Available') }}</p>
                            </div>
                        </div>

                        <div class="p-3 todays_slot pt-2 ">
                            <div id="toDays" class="disp-none disp-block">
                                <h6 class="mb-3 mt-2">{{ __("Today's Schedule") }}</h6>
                                <div class="mt-2 slotes d-flex ">
                                    @foreach ($today_timeslots as $today_timeslot)
                                        <div class="m-1 d-flex time {{ $loop->iteration == 1 ? 'active' : '' }} rounded-3">
                                            <form method="POST" action="{{ url('set_time') }}">
                                                @csrf
                                                <input type="hidden" name="doctor_id" value="{{$doctor->id}}">
                                                <input type="hidden" name="date" value="{{\Carbon\Carbon::today()->format('Y-m-d')}}">
                                                <input type="hidden" name="time" value="{{$today_timeslot['start_time']}}">
                                                <button type="submit" class="noBorderbutton">{{ $today_timeslot['start_time'] }}</button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div id="tomorrows" class="disp-none ">
                                <h6 class="mb-3 mt-2">{{ __('Tomorrow Schedule') }}</h6>
                                <div class="mt-2 slotes d-flex ">
                                    @foreach ($tomorrow_timeslots as $tomorrow_timeslot)
                                        <div class="m-1 d-flex time {{ $loop->iteration == 1 ? 'active' : '' }} rounded-3">
                                            <form method="POST" action="{{ url('set_time') }}">
                                                @csrf
                                                <input type="hidden" name="doctor_id" value="{{$doctor->id}}">
                                                <input type="hidden" name="date" value="{{\Carbon\Carbon::tomorrow()->format('Y-m-d')}}">
                                                <input type="hidden" name="time" value="{{ $tomorrow_timeslot['start_time'] }}">
                                                <button type="submit" class="noBorderbutton">{{ $tomorrow_timeslot['start_time'] }}</button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Profile Info Over -->

            <div class="mt-3">

                <div class="py-2 bg-white rounded-2">
                    <div class="single-nav">
                        <ul class="d-flex justify-content-center border-bottom">
                            <li class="d-flex text-center active"><a href="javascript:void(0)" onclick="seeData('#doctor_information')" class="h-100 w-100">{{ __('Doctor Information') }}</a></li>
                            <li class="d-flex text-center"><a href="javascript:void(0)" class="h-100 w-100" onclick="seeData('#Locations')">{{ __('Locations') }}</a></li>
                            <li class="d-flex text-center"><a href="javascript:void(0)" class="h-100 w-100" onclick="seeData('#Reviews')">{{ __('Reviews') }}</a></li>
                            <li class="d-flex text-center"><a href="javascript:void(0)" class="h-100 w-100" onclick="seeData('#Bus-hour')">{{ __('Business Hours') }}</a>
                            </li>
                        </ul>
                    </div>

                    <div class="px-3 single-dis pt-3 pb-1">

                        <div id="doctor_information" class="disp-none disp-block">
                            <div class="border p-3 rounded-3">
                                <div class="mb-4">
                                    <h6 class="common_head">{{__('Professional Bio')}}</h6>
                                    <p>{{ $doctor->desc }}</p>
                                </div>
                                <div>
                                    <h6 class="common_head">{{__('Education')}}</h6>
                                    @foreach (json_decode($doctor->education) as $education)
                                        <div class="">
                                            <span>{{$education->college}}</span>
                                            <p class="text-muted"> {{$education->degree}}</p>
                                            <p class="text-muted"> {{$education->year}}</p>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4">
                                    <h6 class="common_head">{{__('Work & Experience')}}</h6>
                                    <div class="">
                                        <p class="text-muted">{{$doctor->experience}} {{__(' (Years)')}}</p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <h6 class="common_head">{{__('Certificate')}}</h6>
                                    @foreach (json_decode($doctor->certificate) as $certificate)
                                        <div class="">
                                            <p class="text-info ">{{$certificate->certificate_year}}</p>
                                            <p class="text-muted fs-6">{{$certificate->certificate}}</p>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>

                        <div id="Locations" class="disp-none">
                            <div class="border rounded-3 mb-2 p-3">
                                <div class="d-flex flex-md-row flex-column">
                                    <div class="w-100">
                                        <div class="d-flex w-100 justify-content-between">
                                            <div>
                                                <h6 class="common_head mb-1">{{$doctor['hospital']['name']}}</h6>
                                                <p>{{ $doctor->expertise['name'] }}</p>
                                            </div>
                                            <div>
                                                <h6 class="common_head">{{$currency}} <span>{{$doctor->appointment_fees}}</span></h6>
                                            </div>
                                        </div>
                                        <div class="location my-2 d-flex align-items-center">
                                            <i class="bx bx-map me-2"></i>
                                            <p>{{$doctor['hospital']->address}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="Reviews" class="disp-none">
                            @foreach ($reviews as $review)
                                <div class="p-3 border rounded-3 mb-2">
                                    <div class="review-img d-flex w-100">
                                        <div>
                                            <img src="{{ $review->user['fullImage'] }}" class="rounded-circle me-3" alt="">
                                        </div>
                                        <div>
                                            <div>
                                                <h6 class="common_head mb-0">{{ $review->user['name'] }}</h6>
                                                <div class="rating d-flex align-items-center">
                                                    @for ($i = 1; $i < 6; $i++)
                                                        @if ($i <= $review->rate)
                                                            <i class="bx bxs-star active"></i>
                                                        @else
                                                            <i class="bx bxs-star"></i>
                                                        @endif
                                                    @endfor
                                                        <span class="d-inline-block average-rating">({{$review->rate}})</span>
                                                </div>
                                            </div>

                                            <p class="mt-3">{{ $review->review }}</p>
                                            <div class="mt-1">
                                                <p class="text-muted">{{ $review->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div id="Bus-hour" class="disp-none">
                            <div class="border p-3 business_hour mx-auto rounded-3">
                                @foreach ($doctor->workHour as $hour)
                                    <div class="d-flex day-cal w-100 mb-1 align-items-center">
                                        <p>{{ $hour->day_index }}</p>
                                        @if ($hour->status == 1)
                                            <span class="ms-auto open">
                                                @foreach (json_decode($hour->period_list) as $item)
                                                    {{ $item->start_time }} - {{ $item->end_time }}
                                                @endforeach
                                            </span>
                                        @else
                                            <span class="ms-auto closed">
                                                {{__('close')}}
                                            </span>
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
@endsection

@section('js')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ App\Models\Setting::first()->map_key }}&sensor=false&libraries=places"></script>
    <script src="{{ url('assets/js/doctor_list.js') }}"></script>
@endsection