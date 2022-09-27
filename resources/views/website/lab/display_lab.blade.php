<div class="">
    <div class="content mx-auto">
        <div class="ps-xl-0 ps-3 mt-3">
            <h3>{{ count($labs) }} &nbsp;{{ __('Laboratory available') }}</h3>
            <p class="mt-2">{{ __('Book Your Appointment with Easy Way') }}</p>
        </div>
    </div>
</div>

<div class="content mx-auto">
    <div class="row row-cols-1 row-cols-lg-2 g-0">
        @if (count($labs) > 0)
            @foreach ($labs as $lab)
                <div class="col">
                    <div class="doct-card p-3 card border-0 m-3  ms-xl-0 pb-2 mb-0 position-relative lab_card">
                        <div class="d-flex flex-sm-row flex-column">
                            <div class="doct-card-img me-3 lab_card_img">
                                <img src="{{ $lab->fullImage }}">
                            </div>
                            <div class=" doctor-info d-flex flex-column w-100">
                                <div class="personalInfo">
                                    <div>
                                        <h6>{{$lab->name}}</h6>
                                    </div>
                                    <div class="d-flex mt-3  text-center">
                                        <i class='bx bxs-phone-call'></i>
                                        <p class="mb-0 ps-1">{{$lab['user']->phone}}</p>
                                    </div>
                                    <div class="d-flex mt-2 align-items-center fbk ">
                                        <i class='bx bx-mail-send'></i>
                                        <p>{{$lab['user']->email}}</p>
                                    </div>
                                    <div class="d-flex mt-2 align-items-center ">
                                        <i class='bx bxs-door-open'></i>
                                        <p>{{__('Opens At')}}&nbsp;{{$lab->openTime}}</p>
                                    </div>
                                </div>
                                <div class="location mt-2 mb-2 d-flex">
                                    <i class='bx bx-map'></i>
                                    <p>{{$lab->address}}</p>
                                </div>
                                <div class="my-3 w-100">
                                    <div class="btn-appointment text-end mt-sm-0 mt-3">
                                        <a class="btn btn-link text-center mt-0" href="{{ url('lab_tests/'.$lab->id.'/'.Str::slug($lab->name)) }}" role="button">{{__('Test Report')}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="w-100 text-center">
                <i class='bx bxs-shopping-bag noData'></i>
                <br>
                <h6 class="mt-3">{{__('Laboratory Not Available.')}}</h6>
            </div> 
        @endif
    </div>
</div>