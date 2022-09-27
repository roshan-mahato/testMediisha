@extends('layout.mainlayout',['active_page' => 'offer'])

@section('title',__('Offers'))

@section('content')

<div class="full-content">
    <div class="site-body mt-5">
        <div class="container">
            <div class="price">
                @if (count($offers))
                    <div class="price_content">
                        @foreach ($offers as $offer)
                            <div class="price_singal_card">
                                <h5>{{ $offer->name }}</h5>
                                <p>{!! clean($offer->desc) !!}</p>
                                <span>
                                    @if ($offer->is_flat == 1)
                                        <span>{{ $currency }}{{ $offer->flatDiscount }}</span>
                                        {{__('Flat Discount')}}
                                    @else
                                        @if ($offer->discount_type == 'amount')
                                            <span>{{ $currency }}{{ $offer->discount }}</span>
                                        @endif
                                        @if ($offer->discount_type == 'percentage')
                                            <span>{{ $offer->discount }}%</span>
                                        @endif
                                        {{__('OFF')}}
                                    @endif
                                </span>
                                <div>{{ $offer->offer_code }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="w-100 text-center">
                        <i class='bx bxs-offer noData' ></i>
                        <br>
                        <h4 class="mt-2">{{__('Offeres Not Availabel.')}}</h4>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
