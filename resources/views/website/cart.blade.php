@extends('layout.mainlayout',['active_page' => 'cart'])

@section('title',__('cart'))

@section('content')
<div class="site-body">
    <input type="hidden" name="pharmacy_id" value="{{ Session::get('pharmacy')->id }}">
    <div class="full-content">
        <div>
            <div class="content mx-auto">
                <div class="ps-xl-0 ps-3 mt-3">
                    <h3 class="location-name mb-3 ">{{ __('My Cart List') }}</h3>
                </div>
            </div>
        </div>
        <div class="content mx-auto">
            <div class="row g-0">
                <div class="col-md-8">
                    <div class="bg-white rounded-3 shadow-sm p-3 border mb-3 m-2">
                        <div class="table-responsive">
                            <table class="table view_cart_table">
                                <thead>
                                    <tr class="">
                                        <th scope="col">{{ __('Product') }}</th>
                                        <th scope="col">{{ __('Price') }}</th>
                                        <th scope="col">{{ __('Quantity') }}</th>
                                        <th scope="col">{{ __('Total') }}</th>
                                        <th scope="col">
                                            <div class="text-end"> {{ __('Action') }}
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (Session::get('cart') as $cart)
                                        <tr class="border-bottom">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bill-img me-2">
                                                        <img src="{{ $cart['image'] }}" class="rounded-circle" alt="">
                                                    </div>
                                                    <p class="bill_text">{{ $cart['name'] }}</p>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="bill_text">{{ $currency }}{{ $cart['original_price'] }}</p>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div class="counter">
                                                           <div class="d-flex align-items-center ">
                                                            <span class="minus btn" onclick="addCart({{$cart['id']}},`minus`)" id="minus{{$cart['id']}}" href="javascrip:void(0)">-</span>
                                                            <p class="value text-center m-auto" id="txtCart{{$cart['id']}}" name="quantity{{$cart['id']}}">{{ $cart['qty'] }}</p>
                                                            <span class="incris btn" onclick="addCart({{$cart['id']}},`plus`)" id="plus{{$cart['id']}}" href="javascrip:void(0)">+</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="bill_text">{{ $currency }}<span class="item_price{{ $cart['id'] }}">{{ $cart['price'] }}</span></p>
                                            </td>
                                            <td>
                                                <a href="{{ url('remove_single_item/'.$cart['id']) }}" class="d-flex pt-2">
                                                    <i class='bx bx-x btn remove-from-bill ms-auto me-3'></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="m-2">
                        <div class="bg-white rounded-3 shadow-sm  border ms-auto total-of-bill">
                            <p class="Cart Totals p-3 border-bottom cart_total_title">{{ __('Cart Totals') }}</p>
                            <div class="p-3">
                                <p class="d-flex align-items-center pb-3  border-bottom total-items">{{ __('Total Items') }}
                                    <span class="ms-auto tot_cart">{{ count(Session::get('cart')) }}</span>
                                <p class="d-flex align-items-center py-3 grand-total">{{ __('Total') }} 
                                    <span class="ms-auto">{{ $currency }}
                                        <span class="total_price">{{ array_sum(array_column(Session::get('cart'), 'price')) }}</span>
                                    </span>
                                </p>
                                <div class="btn-appointment btn-view-cart d-flex">
                                    <a class="btn btn-link text-center mt-0 w-100 m-md-auto ms-auto" href="{{ url('checkout') }}" role="button">{{ __('Proceed to Checkout') }}</a>
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

@section('js')
    <script src="{{ url('assets/js/medicine_list.js') }}"></script>
@endsection