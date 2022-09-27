<div class="">
    <div class="content mx-auto">
        <div class="ps-xl-0 ps-3 mt-3">
            <h3>{{count($pharmacies)}}&nbsp;{{__('Pharmacy')}}</h3>
            <p class="mt-2">{{__('We have many Pharmacy to help you.')}}</p>
        </div>
    </div>
</div>
<div class="content mx-auto pb-3">
    <div class="row row-cols-1 row-cols-lg-2 g-0 ">
        @forelse ($pharmacies as $pharmacy)
            <div class="col">
                <div class="doct-card p-3 card border-0 m-3  ms-xl-0 pb-2 mb-0 position-relative pharmacy_card ">
                    <div class="d-flex flex-sm-row flex-column">
                        <div class="doct-card-img me-3 pharmacy_card_img">
                            <img src="{{ $pharmacy->fullImage }}">
                        </div>
                        <div class=" doctor-info d-flex flex-column w-100">
                            <div class="personalInfo">
                                <div>
                                    <h6>{{$pharmacy->name}}</h6>
                                </div>
                                <div class="d-flex mt-3  text-center">
                                    <i class='bx bxs-phone-call'></i>
                                    <p class="mb-0 ps-1">{{$pharmacy->phone}}</p>
                                </div>
                                <div class="d-flex mt-2 align-items-center fbk ">
                                    <i class='bx bx-mail-send'></i>
                                    <p>{{$pharmacy->email}}</p>
                                </div>
                                <div class="d-flex mt-2 align-items-center ">
                                    <i class='bx bxs-door-open'></i>
                                    <p>{{__('Opens At')}}&nbsp;{{$pharmacy->openTime}}</p>
                                </div>
                            </div>
                            <div class="location mt-2 mb-2 d-flex">
                                <i class='bx bx-map'></i>
                                <p>{{$pharmacy->address}}</p>
                            </div>


                            <div
                                class="d-flex align-items-sm-center my-3 flex-sm-row flex-column justify-content-sm-between mt-sm-auto w-100">
                                <div class="btn-appointment">
                                    <a href="{{url('pharmacy-details/'.$pharmacy->id.'/'.Str::slug($pharmacy->name))}}" class="view-profile btn btn-outline-secondary login-btn">{{ __('View Details') }}</a>
                                </div>
                                <div class="btn-appointment mt-sm-0 mt-3">
                                    <a class="btn btn-link text-center mt-0" href="{{ url('pharmacy_product/'.$pharmacy->id.'/'.Str::slug($pharmacy->name)) }}" role="button">{{__('Browse Products')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="w-100 text-center">
                <i class='bx bxs-calendar-minus noData'></i>
                <br>
                <h6 class="mt-3">{{__('Pharmacies Not Available.')}}</h6>
            </div>
        @endforelse
    </div>
</div>