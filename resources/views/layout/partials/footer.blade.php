<div class="footer {{ Request::is('/') ? '' : 'mt-5' }}">
    <div class="content mx-auto">
        <div class="pb-5">
            <div class="row g-0">
                <div class=" col-lg-3 col-md-4 ps-3 pb-4 col-sm-6  ">
                <img src="{{ $setting->companyWhite }}" height="60px" class="footer-logo" alt="">

                </div>
                <div class=" col-lg-3 col-md-4 ps-3 pb-4 col-sm-6  ">
                    <div>
                        <h6>{{__('For Patients')}}</h6>
                    </div>
                    <div class="mt-4">
                        <ul class="nav mt-3 footer-nav flex-column">
                            <li class="nav-item">
                                <a href="{{ url('show-doctors') }}">{{__('Search for Doctors')}}</a>
                            </li>
                            @if (auth()->check())
                                <li class="nav-item">
                                    <a href="{{ url('user_profile') }}">{{ auth()->user()->name }}</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a href="{{ url('patient-login') }}">{{__('Login')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('patient-register') }}">{{__('Register')}}</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class=" col-lg-3 col-md-4 ps-3 pb-4 col-sm-6  ">
                    <div>
                        <h6>{{$setting->business_name}}</h6>
                        <ul class="nav mt-3 footer-nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link ps-0" target="_blank" href="{{ url('user_about_us') }}">{{__('About Us')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link ps-0" target="_blank" href="{{ url('user_privacy_policy') }}">{{__('Privacy Policy')}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class=" col-lg-3 col-md-4 ps-3 pb-4 col-sm-6  ">
                    <div>
                        <h6>{{__('Contact Us')}}</h6>
                        <ul class="nav mt-3 footer-nav flex-column">
                            <li class="nav-item">
                                <a href="tel:{{$setting->phone}}" class="nav-link ps-0">{{ $setting->phone }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link ps-0" href="mailto:{{$setting->email}}">{{ $setting->email }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex">
            <div class="mx-auto d-flex flex-column align-items-center">
                {{-- <img src="{{ $setting->companyWhite }}" height="60px" class="footer-logo" alt=""> --}}
                <p>{{__('Copyright')}} &copy; {{ Carbon\Carbon::now(env('timezone'))->year }} {{ $setting->business_name }}{{__(',All rights reserverd')}} </p>
            </div>
        </div>
    </div>
</div>