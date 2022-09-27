@extends('layout.mainlayout',['active_page' => 'home'])

@section('title',__('Home'))

@section('content')
    <div class="site-body">
        <div class="site-hero overflow-hidden position-relative d-md-block">
            <img style="object-fit: cover;" src="{{ url('images/upload/'.$setting->banner_image) }}" alt="">
            <div class="btn-appointment ms-auto mt-sm-0 mt-3 position-absolute">
                <a class="btn btn-link text-center mt-0" target="_blank" href="{{ $setting->banner_url }}" role="button">{{ __('Consult Now') }}</a>
            </div>
        </div>
        <div class="container-xl">
            <div class="content mx-auto my-3">
                <div class="d-flex w-100 describe justify-content-between flex-md-row flex-column py-3 ">
                    <div class="consult">
                        <h2>{{ __('What are you looking for?') }}</h2>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-lg-3 row-cols-sm-2 g-0 ">
                    @foreach ($banners as $banner)
                        <a href="{{ $banner->link }}" target="_blank">
                            <div class="col">
                                <div class="card h-100 border-0 {{ $loop->iteration != 1 ? 'ml-2' : '' }} looking-card">
                                    <div class="img-wrapper rounded-3 overflow-hidden">
                                        <img src="{{ $banner->fullImage }}" alt="...">
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="content mx-auto my-3 our_doctor">
                <div class="d-flex w-100 describe justify-content-between flex-sm-row flex-column py-3 align-items-sm-center">
                    <div class="consult ml-2">
                        <h2>{{ __('Our Doctors') }}</h2>
                    </div>
                    <div class="btn-appointment  mt-sm-0 mt-3">
                        <a class="btn btn-link text-center mt-0 rounded-1" href="{{ url('show-doctors') }}" role="button">{{ __('Consult Now') }}</a>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-xl-4 row-cols-lg-3 row-cols-sm-2 g-0 ">
                    @forelse ($doctors as $doctor)
                        <div class="col">
                            <div class="ml-2 mt-2">
                                <div class="card h-100 p-3 our_doctor_card rounded-3">
                                    <div class="d-flex">
                                        <div class="our_doctor_card_img me-3 position-relative">
                                            <img src="{{ $doctor->full_image }}" alt="">
                                            <p class="position-absolute">{{ $doctor->rate }}/5</p>
                                        </div>
                                        <div class="our_doctor_card_txt">
                                            <h6 class="mb-1">
                                                <a href="{{ url('doctor_profile/'.$doctor['id'].'/'.Str::slug($doctor['name'])) }}">{{ $doctor->name }}</a>
                                            </h6>
                                            <p>{{ $doctor['category']['name'] }}</p>
                                            <p>{{ $doctor->experience }} {{ __('years Experience') }}</p>
                                            <p>{{ $doctor->total_appointment }} {{ __('consults Appointment') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="w-100 text-center">
                            <i class='bx bxs-user-plus noData'></i>
                            <br>
                            <h6 class="mt-3">{{__('Doctors Not Available.')}}</h6>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="content mx-auto Doc-Cards">
                <div class="d-flex w-100 describe justify-content-between flex-md-row flex-column">
                    <div class="consult">
                        <h2>{{ __('Consult from the Best Doctors') }}</h2>
                        <p>{{ __ ('Get in touch with the experts and specialist Doctors.') }}</p>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="row g-xl-0 w-100 g-3 row-cols-xl-6  row-cols-lg-4 row-cols-md-3 row-cols-sm-2 row-cols-1 ">
                        @forelse ($treatments as $treatment)
                            <div class="col">
                                <div class="card border-0 consult-card ">
                                    <a href="javascript:void(0)">
                                        <div class="consult-img d-flex align-items-center justify-content-center">
                                            <img src="{{ $treatment->fullImage }}" alt="">
                                        </div>
                                        <div class="info mx-auto">
                                            <h6 class="text-center">{{ $treatment->name  }}</h6>
                                            <form action="{{ url('show-doctors') }}" method="post" class="text-center">
                                                @csrf
                                                <input type="hidden" name="treatment_id" value="{{ $treatment->id }}">
                                                <button type="submit" class="text-center text-uppercase">{{ __('Consult Now') }}</button>
                                            </form>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="w-100 text-center">
                                <i class='bx bx-recycle noData'></i>
                                <br>
                                <h6 class="mt-3">{{__('Treatments Not Available.')}}</h6>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <!-- Consult Top Doctor -->

            <div class="content mx-auto my-5">
                <div class="text-center mb-5">
                    <h3>{{ __('Read top articles from health experts') }}</h3>
                </div>

                <div class="single-item mb-5">
                    @foreach ($reviews as $review)
                        <div>
                            <div class="card comment-card  p-2 d-flex mx-auto flex-column m-2 rounded-3 shadow-sm">
                                <div class=" comentor-name mb-3 mt-2 ms-2 d-flex align-items-center">
                                    <img src="{{ $review->user['fullImage'] }}" class="avtar rounded-circle" alt="">
                                    <p class="ms-2">{{ $review->user['name'] }}</p>
                                </div>
                                <h6 class="m-2 ">{{ $review->review }}</h6>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="contact-ban">
            <div class="Footer-ban h-100 mx-auto">
                <div class="row g-4 h-100">
                    <div class="col-lg-6  h-100">
                        <div class="position-relative h-100 footer-img">
                            <img src="{{ url('assets/img/banner.png') }}" class="position-absolute" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6  h-100">
                        <div class="footer-ban-text h-100 d-flex">
                            <div class="my-auto">
                                <h3>{{ __('Download the ') }}{{ $setting->business_name }} {{ __('app') }}</h3>
                                <p>{{ __('Get in touch with the top-most expert Specialist Doctors for an accurate consultation on the Doctro. Connect with Doctors, that will be available 24/7 right for you.') }}</p>
                                <h4>{{ __('Get the link to download the app') }}</h4>

                                <div class="d-flex download-app">
                                    <div class="me-2">
                                        <a href="{{ $setting->playstore }}" target="_blank"><img src="{{asset('assets/static/google_play.webp')}}" alt=""></a>
                                    </div>
                                    <div class="">
                                        <a href="{{ $setting->appstore }}" target="_blank"><img src="{{asset('assets/static/apple_store.webp')}}" alt=""></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection