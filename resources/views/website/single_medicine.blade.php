@extends('layout.mainlayout',['active_page' => 'single_medicine'])

@section('title',$medicine->name.__(' Details'))

@section('content')
    <!-- search Bar -->
    <div class="bg-white">
        <input type="hidden" name="pharmacy_id" value="{{ $medicine->pharmacy_id }}">
        <div class="content mx-auto">
            <div class="d-flex flex-lg-row flex-column">
                <form method="post" action="{{ url('all-pharmacies') }}">
                    @csrf
                    <input type="hidden" name="single_pharmacy" value="{{__('single_pharmacy')}}">
                    <input type="hidden" name="pharmacy_lat">
                    <input type="hidden" name="pharmacy_lang">
                    <div class="ps-xl-0 ps-3 d-flex flex-md-row flex-column serach-box">
                        <div class="location position-relative mb-md-0 mb-3 ">
                            <input type="search" class="form-control loc"  onFocus="geolocate()" id="autocomplete" aria-describedby="helpId" placeholder="{{ __('Search Location') }}">
                            <i class="bx bx-map bx_icons position-absolute"></i>
                        </div>
                        <div class="location  doc position-relative d-flex">
                            <input type="search" class="form-control docto" name="search_pharmacy" aria-describedby="helpId" placeholder="{{ __('Search pharmacy.') }}">
                            <div class="location position-relative">
                                <button type="submit" class="btn btn-primary ml-2">
                                    <i class='bx bx-search-alt-2'></i>
                                </button>
                            </div>
                            <i class="bx bx-search bx_icons position-absolute"></i>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- search Bar Over -->

    <div class="full-content">
        <div class="content px-lg-0 px-2 py-3 mx-auto">
            <h3 class="location-name mb-2 ">{{ $medicine->name }}</h3>
            <div class="row g-0">
                <div class="col-xl-4 col-lg-5">
                    <div class="my-2 me-lg-2 h-100 ">
                        <div class="d-flex bg-white flex-sm-row flex-column p-4 rounded-3 h-100">
                            <div class="medicin-image d-flex mx-auto mb-3 mb-sm-0">
                                <img src="{{ $medicine->fullImage }}" alt="" class="m-auto rounded-3">
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-7">
                    <div class="my-2 h-100">
                        <div class="d-flex bg-white p-3 rounded-3 h-100">
                            <div>
                                <div class="medicin-description">
                                    <h6 class="mb-3 pb-2 border-bottom">{{ $medicine->name }}</h6>
                                    <div class="d-flex align-items-center">
                                        <p class="mrp mb-2"><span>{{ __('price') }} :</span> {{ $currency }}
                                            <span class="price">{{ $medicine->price_pr_strip }}</span>
                                        </p>
                                        <p class="available ">{{ $medicine->total_stock }}
                                            {{ __('Strip available') }}</p>
                                    </div>
                                    <div class="mrp mb-3 d-flex align-items-center"><span class="me-2">{{ __('Add Quantity :') }}</span>
                                        @if (Session::get('cart') == null)
                                            <div class="counter">
                                                <div class="d-flex align-items-center ">
                                                    <span class="minus btn" onclick="addCart({{$medicine->id}},`minus`)" id="minus{{$medicine->id}}" href="javascrip:void(0)">-</span>
                                                    <p class="value text-center m-auto" id="txtCart{{$medicine->id}}" name="quantity{{$medicine->id}}">0</p>
                                                    <span class="incris btn" onclick="addCart({{$medicine->id}},`plus`)" id="plus{{$medicine->id}}" href="javascrip:void(0)">+</span>
                                                </div>
                                            </div>
                                        @else
                                            @if (in_array($medicine->id, array_column(Session::get('cart'), 'id')))
                                                @foreach (Session::get('cart') as $cart)
                                                    @if($cart['id'] == $medicine->id)
                                                        <div class="counter">
                                                            <div class="d-flex align-items-center ">
                                                                <span class="minus btn" onclick="addCart({{$medicine->id}},`minus`)" id="minus{{$medicine->id}}" href="javascrip:void(0)">-</span>
                                                                <p class="value text-center m-auto" id="txtCart{{$medicine->id}}" name="quantity{{$medicine->id}}">{{ $cart['qty'] }}</p>
                                                                <span class="incris btn" onclick="addCart({{$medicine->id}},`plus`)" id="plus{{$medicine->id}}" href="javascrip:void(0)">+</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <div class="counter">
                                                    <div class="d-flex align-items-center ">
                                                        <span class="minus btn" onclick="addCart({{$medicine->id}},`minus`)" id="minus{{$medicine->id}}" href="javascrip:void(0)">-</span>
                                                        <p class="value text-center m-auto" id="txtCart{{$medicine->id}}" name="quantity{{$medicine->id}}">0</p>
                                                        <span class="incris btn" onclick="addCart({{$medicine->id}},`plus`)" id="plus{{$medicine->id}}" href="javascrip:void(0)">+</span>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif  
                                    </div>
                                    <p class="product_desc">{{ __('About Product :') }}
                                        <div class="mb-3">
                                            {!! clean($medicine->description) !!}
                                        </div>
                                    </p>
                                    <div class="btn-appointment btn-view-cart">
                                        <a class="btn btn-link text-center mt-0 " href="{{ url('cart') }}" role="button">{{ __('View Cart') }}</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 bg-white  rounded-3 p-3">
                <h3 class="location-name mb-3 pb-2 border-bottom">{{ __('Product Details') }}</h3>
                @if (isset($medicine->meta_info))
                    @foreach (json_decode($medicine->meta_info) as $item)
                        <h6 class="mb-2 mt-3 product_desc_title">{{ $item->title }}</h6>
                        <p class="mb-4 product_desc_para">{!! clean($item->desc) !!}</p>
                    @endforeach
                @else
                    <h4>{{__('No Meta information available for this product')}}</h4>
                @endif
            </div>
        </div>

    </div>
@endsection

@section('js')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ App\Models\Setting::first()->map_key }}&sensor=false&libraries=places"></script>
    <script src="{{ url('assets/js/medicine_list.js') }}"></script>
@endsection