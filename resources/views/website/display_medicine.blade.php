@forelse ($medicines as $medicine)    
    <div class="col">
        <div class="p-2">
            <div class="card product_card border-0">
                <a href="{{ url('medicine_details/'.$medicine->id.'/'.Str::slug($medicine->name)) }}">
                    <div class="product_img w-100 ">
                        <img src="{{ $medicine->fullImage }}" alt="" class="">
                    </div>
                </a>
                <div class="card-body">
                    <a href="{{ url('medicine_details/'.$medicine->id.'/'.Str::slug($medicine->name)) }}">
                        <h6>{{ $medicine->name }}</h6>
                    </a>
                    <div class="d-flex align-items-center justify-content-between">
                        <p>{{ $currency }} <span class="price">{{ $medicine->price_pr_strip }}</span> </p>
                        <div class="sessionCart{{$medicine->id}}">
                            @if (Session::get('cart') == null)
                                <a href="javascript:void(0);" onclick="addCart({{$medicine->id}},'plus')" class="cart-icon">
                                    <i class='bx bxs-cart-add btn'></i>
                                </a>
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
                                    <a href="javascript:void(0);" onclick="addCart({{$medicine->id}},'plus')" class="cart-icon">
                                        <i class='bx bxs-cart-add btn'></i>
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@empty
    <div class="w-100 text-center">
        <i class='bx bxs-user-plus noData'></i>
        <br>
        <h6 class="mt-3">{{__('Medicines Not Available.')}}</h6>
    </div>
@endforelse