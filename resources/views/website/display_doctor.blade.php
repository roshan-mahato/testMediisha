<div class="">
    <div class="content mx-auto">
        <div class="ps-xl-0 ps-3 mt-3">
            <h3>{{ count($doctors) }} &nbsp;{{ __('Doctors available') }}</h3>
            <p class="mt-2">{{ __('Book Your Appointment with easy Way') }}</p>
        </div>
    </div>
</div>

<div class="content mx-auto">
    <div class="row row-cols-1 row-cols-lg-2 g-0">
        @if (count($doctors) > 0)
            @foreach ($doctors as $doctor)
                <div class="col">
                    <div class="doct-card p-3 card border-0 m-3  ms-xl-0 pb-2 mb-0 position-relative ">
                        <div class="d-flex flex-sm-row flex-column">
                            <div class="doct-card-img me-3">
                                <img src="{{ $doctor['fullImage'] }}" class="rounded-circle" alt="...">
                            </div>
                            <div class=" doctor-info d-flex flex-column">
                                <div class="personalInfo">
                                    <div>
                                        <h6>{{ $doctor['name'] }}</h6>
                                    </div>
                                    <div class="d-flex mt-1  text-center">
                                        <i class='bx bx-map'></i>
                                        <p class="mb-0 ps-1">{{ $doctor['hospital']['name'] }}</p>
                                    </div>

                                    <div class="post d-flex mt-2 align-items-center">
                                        <img src="{{ $doctor['category']['fullImage'] }}" alt="">
                                        <p class="ps-2 mb-0">{{ $doctor['category']['name'] }}</p>
                                        <p class="ms-1 ps-1 mb-0 border-start text-muted">
                                            {{ $doctor['expertise']['name'] }}
                                        </p>
                                    </div>
                                    <div class="d-flex flex-sm-row flex-column mt-2">
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
                                        <div class="d-flex ms-sm-3 mt-sm-0 mt-3 align-items-center fbk ">
                                            <i class='bx bx-message-dots'></i>
                                            <p class="ms-2"> <span>{{ $doctor['review'] }}</span> {{ __(' Feedback') }}</p>
                                        </div>
                                    </div>

                                </div>
                                <div class="d-flex mt-2 align-items-center ">
                                    <i class='bx bx-money'></i>
                                    <p class="ms-2"> {{ $currency }} <span>{{ $doctor['appointment_fees'] }}</span></p>
                                </div>
                                <div class="location mt-2 mb-2 d-flex">
                                    <i class='bx bx-map'></i>
                                    <p>{{ $doctor['hospital']['address'] }}</p>
                                </div>

                                <div class="d-flex align-items-sm-center my-3 flex-sm-row flex-column justify-contentss-between">
                                    <div class="btn-appointment">
                                        <a href="{{ url('doctor_profile/'.$doctor['id'].'/'.Str::slug($doctor['name'])) }}" class="view-profile btn btn-outline-secondary login-btn marg_right">{{__('View Profile')}}</a>
                                    </div>
                                    <div class="btn-appointment mt-sm-0 mt-3">
                                        <a class="btn btn-link text-center mt-0 marg_left" href="{{ url('booking/'.$doctor['id'].'/'.Str::slug($doctor['name'])) }}" role="button">{{ __('Book Appointment') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div data-id="{{ $doctor['id'] }}" class="position-absolute d-flex align-items-center justify-content-center shadow add-favourite {{ $doctor['is_fav'] == 'true' ? 'active' : '' }}">
                            <i class='bx bx-bookmark-heart'></i>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="w-100 text-center">
                <i class='bx bxs-user-plus noData'></i>
                <br>
                <h6 class="mt-3">{{__('Doctors Not Available.')}}</h6>
            </div>
        @endif
    </div>
</div>