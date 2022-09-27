@extends('layout.mainlayout',['active_page' => 'login'])

@section('title',__('Verification'))

<style>
    :root
    {
        --site_color: #1f8ced;
        --hover_color: #1f8cedc7;
    }
    .digit-group
    {
        text-align: center;
        display: flex;
    }
    .digit-group input
    {
        width: 60px;
        height: 68px !important;
        background-color: transparent;
        line-height: 50px;
        text-align: center;
        font-weight: 200;
        margin: 0 2px;
        border-radius: 24%;
        transition: all 0.2s ease-in-out;
        outline: none;
        border: solid 1px #ccc;
    }
    .digit-group input:focus
    {
        border-color: var(--site_color);
        box-shadow: 0 0 5px var(--hover_color) inset;
    }
    .digit-group input::selection
    {
        background: transparent;
    }
    .digit-group .splitter {
        padding: 0 5px;
        color: white;
        font-size: 24px;
    }
    .prompt {
        margin-bottom: 20px;
        font-size: 20px;
        color: white;
    }
</style>

@section('content')
        @if ($status && !$errors->any())
        @include('superAdmin.auth.status',[
            'status' => $status])
        @endif
        @if ($errors->any())
            @foreach ($errors->all() as $item)
                @include('superAdmin.auth.status',[
                    'status' => $item])
            @endforeach
            @endif
        <div class="full-content">
            <div class="content mx-auto h-auto">
                <div class="row g-0">
                    <div class="col-xl-5 col-md-6">
                        <div class="d-flex h-100 login-img px-5 align-items-center justify-content-center">
                            <img src="{{ url('assets/img/loginSvg.svg') }}" alt="">
                        </div>
                    </div>
                    <div class="col-xl-7 col-md-6">
                        <div class="m-3 p-sm-3 p-1 h-100">
                            <div class="bg-white rounded-3 Common-form  d-flex align-items-center justify-content-center flex-column p-3 px-4">
                                <h5 class="my-2">{{ __('Verification') }}</h5>
                                <form action="{{ ('verify_user') }}" method="post" class="pt-3 w-100">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <div class="digit-group justify-content-center" data-group-name="digits" data-autosubmit="false" autocomplete="off">
                                        <input type="text" required id="digit-1" name="digit_1" data-next="digit-2" />
                                        <input type="text" required id="digit-2" name="digit_2" data-next="digit-3" data-previous="digit-1" />
                                        <input type="text" required id="digit-3" name="digit_3" data-next="digit-4" data-previous="digit-2" />
                                        <input type="text" required id="digit-4" name="digit_4" data-next="digit-5" data-previous="digit-3" />
                                    </div>
                                    <div class="d-flex flex-column pt-0 Appointment-detail w-100 mt-5">
                                        <a href="{{ url('send_otp') }}" class="ms-auto sidelink">{{ __('Send Again?') }}</a>
                                        <button type="submit" class="btn w-100" href="javascript:void(0)" role="button" data-bs-toggle="modal" data-bs-target="#exampleModal">Submit</button>
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
<script src="{{ url('assets_admin/js/jquery.min.js')}}"></script>
<script>
    $(function()
    {
        "use strict";
        $('.digit-group').find('input').each(function()
        {
            $(this).attr('maxlength', 1);
            $(this).on('keyup', function(e)
            {
                var parent = $($(this).parent());
                if(e.keyCode === 8 || e.keyCode === 37) {
                        var prev = parent.find('input#' + $(this).data('previous'));

                    if(prev.length) {
                        $(prev).select();
                    }
                } else if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
                    var next = parent.find('input#' + $(this).data('next'));

                    if(next.length) {
                        $(next).select();
                    } else {
                        if(parent.data('autosubmit')) {
                            parent.submit();
                        }
                    }
                }
            });
        });
    });
</script>
@endsection