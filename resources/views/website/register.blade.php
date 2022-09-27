@extends('layout.mainlayout',['active_page' => 'patient register'])

@section('title',__('Patient Register'))

<link rel="stylesheet" href="{{ url('assets/css/intlTelInput.css') }}" />

@section('content')
    <div class="full-content">
        <div class="content mx-auto">
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
                            <h5 class="my-2">{{ __('New Register') }}</h5>
                            <form action="{{ url('sign_up') }}" method="post" class="pt-3 w-100">
                                @csrf
                                <div class="w-100">
                                    <div class="mb-4">
                                        <label for="" class="form-label mb-1">{{ __('Name') }}</label>
                                        <input type="text" class="form-control w-100 @error('name') is-invalid @enderror" value="{{ old('name') }}" name="name" required>
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="form-label mb-1">{{ __('Email') }}</label>
                                        <input type="email" value="{{ old('email') }}" class="form-control w-100 @error('email') is-invalid @enderror" name="email" required>
                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <input type="hidden" name="phone_code" value="+91">
                                    <div class="mb-4 d-flex flex-column">
                                        <label for="phone" class="form-label mb-1">{{ __('Phone Number') }}</label>
                                        <input type="tel" value="{{ old('phone') }}" class="form-control" id="phone" name="phone" required>
                                        @error('phone')
                                            <div class="custom_error">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="form-label mb-1">{{ __('Create Password') }}</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="form-label mb-1">{{ __('Date of Birth') }}</label>
                                        <input type="date" max="{{ Carbon\Carbon::now(env('timezone'))->format('Y-m-d') }}" value="{{ old('dob') }}" class="form-control @error('dob') is-invalid @enderror" name="dob" required>
                                        @error('dob')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="form-label mb-1">{{ __('Gender') }}</label>
                                        <select name="gender" class="form-select form-control">
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                                            <option value="other" {{ old('other') == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                                        </select>
                                    </div>

                                    <div class="d-flex flex-column pt-0 Appointment-detail w-100">
                                        <button type="submit" class="btn w-100" href="javascript:void(0)" role="button" data-bs-toggle="modal" data-bs-target="#exampleModal">{{ __('Submit') }}</button>
                                    </div>

                                    <div class="pt-4">
                                        <p class="already-text text-center">{{ __('Already Have An Account?') }}<a href="{{ url('patient-login') }}" class="sidelink"> {{ __('Login') }}</a></p>
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

@section('js')
    <script src="{{ url('assets/js/intlTelInput.min.js') }}"></script>
    <script src="{{ url('assets/js/jquery.timepicker.min.js') }}"></script>
    <script>
        const phoneInputField = document.querySelector("#phone");
        const phoneInput = window.intlTelInput(phoneInputField, {
            preferredCountries: ["us", "co", "in", "de"],
            initialCountry: "in",
            separateDialCode: true,

            utilsScript:"{{url('assets/js/utils.js')}}",
        });
        phoneInputField.addEventListener("countrychange",function() {
            var phone_code = $('.iti__selected-dial-code').text();
            $('input[name=phone_code]').val(phone_code);
        });
    </script>
@endsection
