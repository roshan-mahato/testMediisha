@extends('layout.mainlayout',['active_page' => 'login'])

@section('title',__('Patient Login'))

@section('content')
    <div class="full-content">
        <div class="content mx-auto h-100">
            <div class="row g-0">
                <div class="col-xl-5 col-md-6">
                    <div class="d-flex h-100 login-img px-5 align-items-center justify-content-center">
                        <img src="{{ url('assets/img/loginSvg.svg') }}" alt="">
                    </div>
                </div>
                <div class="col-xl-7 col-md-6">
                    <div class="m-3 p-sm-3 p-1 h-100">
                        <div
                            class="bg-white rounded-3 Common-form  d-flex align-items-center justify-content-center flex-column p-3 px-4">
                            <h2 class="">{{ __('Welcome') }}</h2>
                            <h5 class="my-2">{{ __('Login') }}</h5>
                            <form action="{{ ('patient-login') }}" class="pt-3 w-100">
                                @csrf
                                <div class="w-100">
                                    <div class="mb-4">
                                        <label for="" class="form-label mb-1">{{ __('Email') }}</label>
                                        <input type="email" class="form-control w-100" name="email" value="{{ old('email') }}" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="form-label mb-1">{{ __('Password') }}</label>
                                        <input type="password" class="form-control" name="password" value="{{ old('password') }}" required>
                                    </div>
                                    @if (session('error'))
                                        <div class="text-center">
                                            <span class="custom_error">{{ session('error') }}</span>
                                        </div>
                                    @endif
                                    <div class="d-flex flex-column pt-0 Appointment-detail w-100">
                                        <a href="{{ url('forgot_password') }}" class="ms-auto sidelink">{{ __('Forget Password?') }}</a>
                                        <button type="submit" class="btn w-100" href="javascript:void(0)" role="button" data-bs-toggle="modal" data-bs-target="#exampleModal">Submit</button>
                                    </div>

                                    <div class="pt-4">
                                        <p class="already-text text-center">{{ __('Don’t Have An Account?') }}
                                            <a href="{{ url('patient-register') }}" class="sidelink"> {{ __('Register') }}</a>
                                        </p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection