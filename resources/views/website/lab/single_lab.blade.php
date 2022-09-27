@extends('layout.mainlayout',['active_page' => 'single_lab'])

@section('title',$lab->name.' profile')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('assets_admin/css/select2.min.css')}}">
    <style>
        .select2-selection__rendered{
            font-size: 13px;
        }

        .appointment-form .pathelogy_disp
        {
            max-width: -webkit-fill-available !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: var(--site_color);
            border: 1px solid var(--site_color_hover);
            border-radius: 4px;
            cursor: default;
            float: left;
            margin-right: 5px;
            margin-top: 5px;
            padding: 0 5px;
            color: white;
        }

        .active_type
        {
            border-bottom: 3px solid var(--site_color);
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--site_color);
            color: white;
        }
    </style>
@endsection

@section('content')
<div class="content px-lg-0 px-2 py-3 mx-auto">
    <h3>Test Report</h3>
    <div class="bg-white mt-3">
        <div class="doc-profile Appointment-detail  bg-white rounded-3 h-100 p-3">
            <!-- Doctor Profile -->
            <input type="hidden" name="currency" value="{{ $setting->currency_code }}">
            <input type="hidden" name="company_name" value="{{ $setting->business_name }}">
            <input type="hidden" name="user_name" value="{{ auth()->user()->name }}">
            <input type="hidden" name="email" value="{{ auth()->user()->email }}">
            <input type="hidden" name="phone" value="{{ auth()->user()->phone }}">
            <div class="d-flex  flex-sm-row flex-column align-items-sm-center">
                <div class="doc-profile-img book-doc-img  me-3">
                    <img src="{{ $lab->fullImage }}" alt="">
                </div>
                <div class="doct-card  doctor-info mt-sm-0 mt-3">
                    <div class="personalInfo">
                        <div>
                            <h6>{{ $lab->name }}</h6>
                        </div>
                        <div class="location my-2 d-flex">
                            <i class="bx bx-map me-1"></i>
                            <p>{{ $lab->address }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Doctor Profile Over -->

            <!-- Stapper -->
            <div class="mt-3">
                <div class="progress-container">
                    <div class="progress" id="progress"></div>
                    <div class="circle progress_active">1</div>
                    <div class="circle">2</div>
                    <div class="circle">3</div>
                </div>
                <form id="testForm">
                    <input type="hidden" name="lab_id" value="{{ $lab->id }}">
                    <input type="hidden" name="prescription_required" value="0">
                    <input type="hidden" name="payment_type" value="COD">
                    <input type="hidden" name="amount">
                    <input type="hidden" name="payment_token">
                    <input type="hidden" name="payment_status" value="0">
                    <div class="my-3 appointment-form px-2">
                        <div id="step1" class="disp-block">
                            <div class="appointment-form">
                                <h5 class="common-heading mb-4">{{ __('Patient Details') }}</h5>
                                <div class="pb-4">
                                    <div class="row g-2">
                                        <div class="col-md">
                                            <div>
                                                <label for="" class="form-label mb-1">{{__('Patient Name')}}</label>
                                                <input type="text" class="form-control @error('patient_name') is-invalid @enderror" value="{{ old('patient_name') }}" name="patient_name">
                                                <span class="invalid-div text-danger"><span class="patient_name"></span></span>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div>
                                                <label for="" class="form-label mb-1">{{__('Patient Age')}}</label>
                                                <input type="number" class="form-control @error('age') is-invalid @enderror" min="1" value="{{ old('age') }}" name="age" id="age">
                                                <span class="invalid-div text-danger"><span class="age"></span></span>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div>
                                                <label for="" class="form-label mb-1">{{__('Phone number')}}</label>
                                                <input type="number" min="1" name="phone_no" value="{{ old('phone_no') }}" class="form-control @error('phone_no') is-invalid @enderror" id="phone_no">
                                                <span class="invalid-div text-danger"><span class="phone_no"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pb-4">
                                    <div class="row g-2">
                                        <div class="col-md">
                                            <div>
                                                <label for="" class="form-label mb-1">{{__('Patient Gender')}}</label>
                                                <select name="gender" id="gender" class="form-control">
                                                    <option value="male">{{__('Male')}}</option>
                                                    <option value="female">{{__('Female')}}</option>
                                                    <option value="other">{{__('other')}}</option>
                                                </select>
                                                <span class="invalid-div text-danger"><span class="gender"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="appointment-form">
                                <h5 class="common-heading mb-4">{{ __('Report Details') }}</h5>
                                <div class="pb-4">
                                    <div class="card">
                                        <div class="schedule bg-white rounded-3 h-100 ">
                                            <div class="d-flex align-items-center border-bottom p-3">
                                                <h6 class="ms-2">{{ __('Select Test Types') }}</h6>
                                            </div>
                                            <div class="d-flex book-slot shadow-sm">
                                                <div class="w-50 d-flex p-2 align-items-center justify-content-center flex-column border-end active_type pathology_div" onclick="seeData1('#toDays')">
                                                    <p>{{ __('Pathology') }}</p>
                                                </div>
                                                <div class="w-50 d-flex p-2 align-items-center justify-content-center border-start flex-column radiology_div" onclick="seeData1('#tomorrows')">
                                                    <p>{{ __('Radiology') }}</p>
                                                </div>
                                            </div>
                    
                                            <div class="p-3 pathelogy_disp pt-2 ">
                                                <div id="toDays" class="disp-none disp-block">
                                                    <div class="row">
                                                        <div class="col-md-6 col-lg-6">
                                                            <label for="" class="form-label mb-1">{{__('Pathology Category')}}</label>
                                                            <select name="pathology_category_id" id="pathology_category_id" class="form-control select2">
                                                                <option value="">{{__('Select Pathology Category')}}</option>
                                                                @foreach ($pathology_categories as $pathology_category)
                                                                    <option value="{{ $pathology_category->id }}">{{ $pathology_category->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="invalid-div text-danger"><span class="pathology_category_id"></span></span>
                                                        </div>
                                                        <div class="col-md-6 col-lg-6">
                                                            <label for="" class="form-label mb-1">{{__('Test Type')}}</label>
                                                            <select name="pathology_id[]" id="pathology_id" class="form-control select2" multiple>
                                                            </select>
                                                            <span class="invalid-div text-danger"><span class="pathology_id"></span></span>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-5 disp-none pathology_single_details">
                                                    </div>
                                                </div>

                                                <div id="tomorrows" class="disp-none ">
                                                    <div class="row">
                                                        <div class="col-md-6 col-lg-6">
                                                            <label for="" class="form-label mb-1">{{__('Radiology Category')}}</label>
                                                            <select name="radiology_category_id" id="radiology_category_id" class="form-control select2">
                                                                <option value="">{{__('Select Radiology Category')}}</option>
                                                                @foreach ($radiology_categories as $radiology_category)
                                                                    <option value="{{ $radiology_category->id }}">{{ $radiology_category->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="invalid-div text-danger"><span class="radiology_category_id"></span></span>
                                                        </div>
                                                        <div class="col-md-6 col-lg-6">
                                                            <label for="" class="form-label mb-1">{{__('Screening For')}}</label>
                                                            <select name="radiology_id[]" id="radiology_id" class="form-control select2" multiple>
                                                            </select>
                                                            <span class="invalid-div text-danger"><span class="radiology_id"></span></span>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-5 disp-none radiology_single_details">
                                                    </div>
                                                </div>
                                                <div class="row mt-3 disp-none presciption_required">
                                                    <div class="col-md-6 col-lg-6">
                                                        <label for="" class="form-label mb-1">{{__('Select Doctor')}}</label>
                                                        <select name="doctor_id" id="doctor_id" class="form-control @error('doctor_id') is-invalid @enderror">
                                                            <option value="">{{__('Please Select Doctor')}}</option>
                                                            @foreach ($doctors as $doctor)
                                                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="invalid-div text-danger"><span class="doctor_id"></span></span>
                                                    </div>
                                                    <div class="col-md-6 col-lg-6">
                                                        <label for="" class="form-label mb-1">{{__('Upload Test Prescription')}}</label>
                                                        <input type="file" name="prescription" id="prescription" class="form-control @error('prescription') is-invalid @enderror">
                                                        <span class="invalid-div text-danger"><span class="prescription"></span></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="step2" class="disp-none">
                            <div class="d-flex ">
                                <div class="todays_slot mx-auto">
                                    <div class="mb-3">
                                        <label for="" class="form-label mb-1">{{ __('Appointment Date') }}</label>
                                        <input type="date" class="form-control" value="{{ $date }}" name="date" id="date" min="{{ $date }}">
                                    </div>
                                    <div class="">
                                        <div class="mt-2 slotes d-flex timeSlotRow">
                                            @if (count($timeslots) > 0)
                                                @foreach ($timeslots as $timeslot)
                                                    <input type="hidden" name="time" value="{{ $loop->iteration == 1 ? $timeslot['start_time'] : '' }}">
                                                    <div class="m-1 d-flex time {{ $loop->iteration == 1 ? 'active' : '' }} rounded-3" onclick="thisTime({{ $loop->iteration }})">
                                                        <a class="selectedClass{{$loop->iteration}}" href="javascript:void(0)">{{ $timeslot['start_time'] }}</a>
                                                    </div>
                                                @endforeach
                                            @else
                                                <strong class="text-danger text-center w-100">{{__('At this Date Laboratory is not availabel please change the date...')}}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="step3" class="disp-none">
                            <div class="row">
                                <div class="col-md-7 col-lg-8">
                                    <div class="m-2 border rounded-3 p-3">
                                        <h6>{{__('Payment Method')}}</h6>
                                        @if ($setting->cod == 1)
                                        <div class="position-relative d-flex align-items-center my-1 mt-2">
                                            <input type="radio" class="d-none custom_radio" value="cod" id="cod" name="payment" checked>
                                            <label for="cod" class="position-absolute custom-radio"></label>
                                            <label for="cod" class="ms-4 normal-label">{{__('COD')}}</label>
                                        </div>
                                        @endif

                                        @if ($setting->paypal == 1)
                                            <div class="position-relative d-flex align-items-center my-1 ">
                                                <input type="radio" class="d-none custom_radio" value="paypal" id="paypal" name="payment">
                                                <label for="paypal" class="position-absolute custom-radio"></label>
                                                <label for="paypal" class="ms-4 normal-label">{{__('paypal')}}</label>
                                            </div>
                                        @endif

                                        @if ($setting->stripe == 1)
                                            <div class="position-relative d-flex align-items-center my-1 ">
                                                <input type="radio" class="d-none custom_radio" value="stripe" id="stripe" name="payment">
                                                <label for="stripe" class="position-absolute custom-radio"></label>
                                                <label for="stripe" class="ms-4 normal-label">{{__('Stripe')}}</label>
                                            </div>
                                        @endif

                                        @if ($setting->paystack == 1)
                                            <div class="position-relative d-flex align-items-center my-1 ">
                                                <input type="radio" class="d-none custom_radio" value="paystack" id="paystack" name="payment">
                                                <label for="paystack" class="position-absolute custom-radio"></label>
                                                <label for="paystack" class="ms-4 normal-label">{{__('Paystack')}}</label>
                                            </div>
                                        @endif

                                        @if ($setting->paystack == 1)                                                    
                                            <div class="position-relative d-flex align-items-center my-1 ">
                                                <input type="radio" class="d-none custom_radio" value="flutterwave" id="flutterwave" name="payment">
                                                <label for="flutterwave" class="position-absolute custom-radio"></label>
                                                <label for="flutterwave" class="ms-4 normal-label">{{__('Flutterwave')}}</label>
                                            </div>
                                        @endif

                                        @if($setting->razor == 1)
                                            <div class="position-relative d-flex align-items-center my-1 ">
                                                <input type="radio" class="d-none custom_radio" value="razor" id="razorpay" name="payment">
                                                <label for="razorpay" class="position-absolute custom-radio"></label>
                                                <label for="razorpay" class="ms-4 normal-label">{{__('Razor Pay')}}</label>
                                            </div>
                                        @endif


                                        <div class="mt-3">
                                            <div id="paypalPayment" class="paypal_row disp-none">
                                                <div class="card">
                                                    <div class="paypal_row_body"></div>
                                                </div>
                                            </div>
    
                                            <div id="stripePayment" class="stripe_row disp-none">
                                                <div class="alert alert-warning stripe_alert hide" role="alert">
                                                </div>
                                                <input type="hidden" name="stripe_publish_key" value="{{App\Models\Setting::find(1)->stripe_public_key}}">
                                                <form role="form" method="post" class="require-validation customform" data-cc-on-file="false" id="stripe-payment-form">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label>{{__('Email')}}</label>
                                                                <input type="email" class="email form-control required" title="Enter Your Email"
                                                                    name="email" required />
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label>{{__('Card Information')}}</label>
                                                                <input type="text" class="card-number required form-control" title="please input only number." pattern="[0-9]{16}" name="card-number" placeholder="1234 1234 1234 1234" title="Card Number" required />
                                                                <div class="row" class="mt-1">
                                                                    <div class="col-lg-6 pr-0">
                                                                        <input type="text" class="expiry-date required form-control" name="expiry-date" title="Expiration date" title="please Enter data in MM/YY format." pattern="(0[1-9]|10|11|12)/[0-9]{2}$" placeholder="MM/YY" required />
                                                                        <input type="hidden" class="card-expiry-month required form-control" name="card-expiry-month" />
                                                                        <input type="hidden" class="card-expiry-year required form-control" name="card-expiry-year" />
                                                                    </div>
    
                                                                    <div class="col-lg-6 pl-0">
                                                                        <input type="text" class="card-cvc required form-control" title="please input only number." pattern="[0-9]{3}" name="card-cvc" placeholder="CVC" title="CVC" required />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
    
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label>{{__('Name on card')}}</label>
                                                                <input type="text" class="required form-control" name="name" title="Name on Card" required />
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group text-center">
                                                                <input type="button" class="btn btn-primary mt-4 btn-submit" value="{{ __('Pay with stripe') }}" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
    
                                            <div id="paystackPayment" class="paystack_row disp-none">
                                                <form id="paymentForm">
                                                    <input type="hidden" id="paystack-public-key" value="{{ App\Models\Setting::find(1)->paystack_public_key }}">
                                                    <input type="hidden" id="email-address" value="{{ auth()->user()->email }}" required />
                                                    <div class="form-submit">
                                                        <div class="row">
                                                            <div class="col-md-12 text-center">
                                                                <input type="button" class="btn btn-primary" onclick="payWithPaystack()" value="{{__('Pay with paystack')}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
    
                                            <div id="flutterPayment" class="flutterwave_row disp-none">
                                                <form>
                                                    <input type="hidden" name="flutterwave_key" value="{{ $setting->flutterwave_key }}">
                                                    <script src="{{ asset('payment/flutterwave.js') }}"></script>
                                                    <div class="w-full px-4 flex gap-3 items-center mt-5 rounded-md h-auto justify-center">
                                                        <div class="row">
                                                            <div class="col-md-12 text-center">
                                                                <input type="button" class="btn btn-primary" onclick="makePayment()" value="{{__('Pay With Flutterwave')}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div id="razorPayment" class="razor_row disp-none">
                                                <input type="hidden" id="RAZORPAY_KEY" value="{{ $setting->razor_key }}">
                                                <div class="row">
                                                    <div class="col-md-12 text-center">
                                                        <input type="button" id="paybtn" onclick="RazorPayPayment()" value="{{__('pay with razorpay')}}" class="btn btn-primary">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 col-lg-4">
                                    <div class="booking-summery border m-2 rounded-3">
                                        <div class="booking-card-head p-3 border-bottom">
                                            <h6>{{__('Booking Summary')}}</h6>
                                        </div>
                                        <div class="p-3">
                                            <div
                                                class="d-flex  flex-sm-row flex-column align-items-sm-center mb-2">
                                                <div class="booking-card-img  me-3">
                                                    <img src="{{ $lab->fullImage }}" alt="">
                                                </div>
                                                <div class="doct-card doctor-info">
                                                    <div class="personalInfo">
                                                        <div>
                                                            <h6>{{ $lab->name }}</h6>
                                                        </div>
                                                        <div class="rating d-flex  align-items-center">
                                                            <i class="bx bxs-star active"></i>
                                                            <i class="bx bxs-star active"></i>
                                                            <i class="bx bxs-star active"></i>
                                                            <i class="bx bxs-star"></i>
                                                            <i class="bx bxs-star"></i>
                                                            <span class="d-inline-block average-rating">(0)</span>
                                                        </div>
                                                        <div class="location my-2 d-flex">
                                                            <i class="bx bx-map me-1"></i>
                                                            <p>{{ $lab->address }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="pt-3 total-bill pb-2 ">
                                                <h5 class="d-flex justify-content-between">{{__('Total')}}
                                                    <p>{{ $setting->currency_symbol }}
                                                        <span class="total_amount">00</span>
                                                    </p>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="d-flex justify-content-between">
                    <button class="btn" id="prev" disabled>Prev</button>
                    <button class="btn" id="next">Next</button>
                    <a href="javascript:void(0)" id="payment" onclick="report_book()" class="btn d-none">Proceed To Pay</a>
                </div>
            </div>
            <!-- Stapper Over -->
        </div>
    </div>
</div>
@endsection

@section('js')
    <script src="{{ url('assets/js/lab_test.js') }}"></script>
    <script src="{{ url('assets_admin/js/select2.min.js')}}"></script>

    @if (App\Models\Setting::first()->paypal_sandbox_key)
        <script src="https://www.paypal.com/sdk/js?client-id={{ App\Models\Setting::first()->paypal_sandbox_key }}&currency={{ App\Models\Setting::first()->currency_code }}" data-namespace="paypal_sdk"></script>
    @endif
    <script src="{{ url('payment/razorpay.js')}}"></script>
    <script src="{{ url('payment/stripe.js')}}"></script>
    @if(App\Models\Setting::first()->paystack_public_key)
        <script src="{{ url('payment/paystack.js') }}"></script>
    @endif
@endsection