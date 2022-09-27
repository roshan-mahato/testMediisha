@extends('layout.mainlayout',['active_page' => 'user_profile'])

@section('title',__(' Forgot password'))

@section('content')
    <div class="full-content">
        <div class="content mx-auto h-100">
            <div class="row g-0">
                <div class="col-xl-5 col-md-6">
                    <div class="d-flex h-100 login-img px-5 align-items-center justify-content-center">
                        <img src="{{url('assets/img/loginSvg.svg')}}" alt="">
                    </div>
                </div>
                <div class="col-xl-7 col-md-6">
                @if (session('status'))
                    @include('superAdmin.auth.status',[
                        'status' => session('status')])
                    @endif
                    <div class="m-3 p-sm-3 p-1 h-100">
                        <div class="bg-white rounded-3 Common-form  d-flex align-items-center justify-content-center flex-column p-3 px-4">
                            <div class="my-2 mb-4 text-center">
                                <h5>{{__('Forget Password')}}</h5>
                                <p class="already-text">{{__('Enter Your Email To Get A New Password')}}</p>
                            </div>
                            @if ($errors->any())
                                @foreach ($errors->all() as $item)
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ $item }}
                                    </div>
                                @endforeach
                            @endif
                            <form action="{{url('user_forgot_password')}}" method="post" class="pt-3 w-100">
                                @csrf
                                <div class="w-100">
                                    <div class="mb-4">
                                        <label for="" class="form-label mb-1">{{__('Email')}}</label>
                                        <input type="email" class="form-control w-100" name="email" required>
                                    </div>
                                    <div class="d-flex flex-column pt-0 Appointment-detail w-100">
                                        <a href="{{url('patient-login')}}" class="ms-auto sidelink">{{__('Remember Your Password?')}}</a>
                                        <button type="submit" class="btn w-100" href="javascript:void(0)" role="button" data-bs-toggle="modal" data-bs-target="#exampleModal">{{__('Reset Password')}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

