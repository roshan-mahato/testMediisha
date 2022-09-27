@extends('layout.mainlayout',['active_page' => 'checkout'])

@section('title',__('Medicine Checkout'))

@section('content')
<div class="full-content">
    @php
        $price = array_sum(array_column(Session::get('cart'), 'price'));
    @endphp
    <input type="hidden" name="currency_code" value="{{ $master['setting']->currency_code }}">

    <input type="hidden" name="company_name" value="{{$master['setting']->business_name}}">
    <input type="hidden" name="user_name" value="{{ auth()->user()->name }}">
    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
    <input type="hidden" name="phone" value="{{ auth()->user()->phone }}">

    <input type="hidden" name="shipping_at" value="pharmacy">
    <input type="hidden" name="amount" value="{{ $price }}">
    <input type="hidden" name="delivery_charge" value="0">
    <div class="content px-lg-0 px-2 py-3 mx-auto">
        <h3 class="location-name mb-3 "> {{ __('Medicine Checkout') }}</h3>
        @if (Session::get('pharmacy')['is_shipping'] == 1)
            <div class="bg-white p-3 rounded-2">
                <p class="normal-label mb-1">{{ __('Shipping At Home ??') }}</p>
                <div class="form-check delivery-check">
                    <input type="checkbox" class="form-check-input " id="delivery_type" value="checkedValue">
                    <label class="form-check-label" for="delivery_type">{{ __('Yes') }}</label>
                </div>
            </div>
            <div>
                <div class="bg-white border rounded-2 mt-4 disp-none addresses-list">
                    <div class="d-flex justify-content-between border-bottom p-3 align-items-center shipping-details">
                        <h3 class="location-name ">{{ __('Shipping Details') }}</h3>
                        <div class="success-block w-auto">
                            <a class="btn btn-primary btn-sm" href="javascript:void(0)" role="button" data-bs-toggle="modal" data-bs-target="#exampleModal">{{ __('Add new Address') }}</a>
                        </div>
                    </div>

                    <div class="p-3">
                        @foreach ($master['address'] as $address)
                            <div class="position-relative d-flex align-items-center my-1 mt-2">
                                <input type="radio" class="d-none custom_radio" id="address{{ $address['id'] }}" value="{{ $address['id'] }}" name="address_id">
                                <label for="address{{ $address['id'] }}" class="position-absolute custom-radio"></label>
                                <label for="address{{ $address['id'] }}" class="ms-4 normal-label">{{ $address['address'] }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        <div class="mt-4">
            <div class=" py-2 bg-white rounded-2 px-2">
                <div class="row">
                    <div class="col-md-7 col-lg-8">
                        <div class="m-2 border bg-white rounded-3 ">
                            <h6 class="fs-5 fw-normal p-3 border-bottom">{{ __('Billing Details') }}</h6>
                            <div class="px-2 py-2  {{ $master['prescription'] == 0 ? 'disp-none' : ''}}">
                                <p class="mb-1 mt-3 prescription-txt">{{ __('Add Prescription PDF') }} <span class="text-danger">{{ __('**Please First Add Doctor Prescription**') }}</span></p>
                                <input type="file" name="pdf" accept=".pdf" id="pdf" class="form-control">
                            </div>
                            <div>
                                <div class="p-3 payment_style {{ $master['prescription'] == 1 ? 'disp-none' : ''}}">
                                    @if ($master['setting']->cod == 1)
                                        <div class="position-relative d-flex align-items-center my-1 mt-2">
                                            <input type="radio" class="d-none custom_radio" value="cod" id="cod" name="payment" checked>
                                            <label for="cod" class="position-absolute custom-radio"></label>
                                            <label for="cod" class="ms-4 normal-label">{{__('COD')}}</label>
                                        </div>
                                    @endif

                                    @if ($master['setting']->paypal == 1)
                                        <div class="position-relative d-flex align-items-center my-1 ">
                                            <input type="radio" class="d-none custom_radio" value="paypal" id="paypal" name="payment">
                                            <label for="paypal" class="position-absolute custom-radio"></label>
                                            <label for="paypal" class="ms-4 normal-label">{{__('paypal')}}</label>
                                        </div>
                                    @endif

                                    @if ($master['setting']->stripe == 1)
                                        <div class="position-relative d-flex align-items-center my-1 ">
                                            <input type="radio" class="d-none custom_radio" value="stripe" id="stripe" name="payment">
                                            <label for="stripe" class="position-absolute custom-radio"></label>
                                            <label for="stripe" class="ms-4 normal-label">{{__('Stripe')}}</label>
                                        </div>
                                    @endif

                                    @if ($master['setting']->paystack == 1)
                                        <div class="position-relative d-flex align-items-center my-1 ">
                                            <input type="radio" class="d-none custom_radio" value="paystack" id="paystack" name="payment">
                                            <label for="paystack" class="position-absolute custom-radio"></label>
                                            <label for="paystack" class="ms-4 normal-label">{{__('Paystack')}}</label>
                                        </div>
                                    @endif

                                    @if ($master['setting']->paystack == 1)                                                    
                                        <div class="position-relative d-flex align-items-center my-1 ">
                                            <input type="radio" class="d-none custom_radio" value="flutterwave" id="flutterwave" name="payment">
                                            <label for="flutterwave" class="position-absolute custom-radio"></label>
                                            <label for="flutterwave" class="ms-4 normal-label">{{__('Flutterwave')}}</label>
                                        </div>
                                    @endif

                                    @if($master['setting']->razor == 1)
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

                                        <div id="paypalPayment" class="cod_row">
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <input type="button" class="btn btn-primary" onclick="bookMedicine()" value="{{__('Pay offline')}}">
                                                </div>
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
                                                <input type="hidden" name="flutterwave_key" value="{{ $master['setting']->flutterwave_key }}">
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
                                            <input type="hidden" id="RAZORPAY_KEY" value="{{ $master['setting']->razor_key }}">
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <input type="button" id="paybtn" onclick="RazorPayPayment()" value="{{__('pay with razorpay')}}" class="btn btn-primary">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5 col-lg-4">
                        <div class="booking-summery border m-2 bg-white rounded-3">
                            <div class="booking-card-head p-3 border-bottom">
                                <h6>{{('Your Order')}}</h6>
                            </div>
                            <div class="p-3">
                                <div class="pb-4 border-bottom bill my-2">
                                    <p class="d-flex justify-content-between mb-1">{{__('Total Items')}}<span class="text-muted">{{count(Session::get('cart'))}}</span></p>
                                    <p class="d-flex justify-content-between mb-1">{{ __('SubTotal') }}
                                        <span class="text-muted">{{ $master['setting']->currency_symbol }}<span class="subtotal">{{ $price }}</span></span>
                                    </p>
                                    <p class="d-flex justify-content-between ">{{ __('Delivery Charge') }}<span class="text-muted">{{ $master['setting']->currency_symbol }}<span class="deliveryCharge">00</span></span></p>
                                </div>
                                <div class="pt-3 total-bill pb-2 ">
                                    <h5 class="d-flex justify-content-between">{{ __('Grand Total') }}
                                        <p>{{ $master['setting']->currency_symbol }}<span class="finalPrice">{{ $price }}</span></p>
                                    </h5>
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

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('User Address') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('addAddress') }}" method="post">
                    @csrf
                    <input type="hidden" name="from" value="add_new">
                    <input type="hidden" name="id">
                    <input type="hidden" name="lat" id="lat" value="22.3039">
                    <input type="hidden" name="lang" id="lng" value="70.8022">
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <div id="map" class="mapClass"></div>
                    <div class="form-group">
                        <textarea name="address" cols="30" class="form-control" rows="10">{{ __('Rajkot , Gujrat') }}</textarea>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>


@section('js')
    @if (App\Models\Setting::first()->map_key)
        <script src="https://maps.googleapis.com/maps/api/js?key={{App\Models\Setting::first()->map_key}}&libraries=places&v=weekly" async></script>
    @endif
    <script src="{{ url('assets/js/medicine_list.js') }}"></script>

    <script src="{{ url('payment/stripe.js')}}"></script>

    @if(App\Models\Setting::first()->paypal_sandbox_key)
        <script src="https://www.paypal.com/sdk/js?client-id={{ App\Models\Setting::first()->paypal_sandbox_key }}&currency={{ App\Models\Setting::first()->currency_code }}" data-namespace="paypal_sdk"></script>
    @endif

    <script src="{{ url('payment/razorpay.js')}}"></script>

    @if(App\Models\Setting::first()->paystack_public_key)
        <script src="{{ url('payment/paystack.js')}}"></script>
    @endif
@endsection