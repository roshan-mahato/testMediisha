@extends('layout.mainlayout',['active_page' => 'user_profile'])

@section('title',auth()->user()->name.__(' profile'))

@section('content')
    <div class="site-body">
        <div class="full-content">
            <div>
                <div class="content mx-auto">
                    <div class="ps-xl-0 ps-3 mt-3">
                        <h3>{{ __('Patient Dashboard') }}</h3>
                        @if ($errors->any())
                            @foreach ($errors->all() as $item)
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ $item }}
                                </div>
                            @endforeach
                        @endif
                        @if (Session::has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ Session::get('error') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="content mx-auto">
                <div class="row">
                    <div class="col-md-3">
                        <div class="m-2 profile-side-bar pt-3 pb-2 h-100 bg-white">
                            <div class="edit-profile d-flex flex-column">
                                <img src="{{ url('images/upload/'.auth()->user()->image) }}" class="rounded-circle mx-auto my-3" alt="">
                                <h6 class="mb-3 text-center">{{ auth()->user()->name }}</h6>
                            </div>
                            <ul class="edit-profile-menu pb-2 my-2 position-relative">
                                <li class="user-profile-link active" onclick="seeData('#user-dashboard')">
                                    <a href="javascript:void(0)" class="d-flex align-items-center">
                                        <i class='bx bxs-dashboard me-2'></i>{{ __('Dashboard') }}
                                    </a>
                                </li>
                                <li class="user-profile-link" onclick="seeData('#user-test-report')">
                                    <a href="javascript:void(0)" class="d-flex align-items-center">
                                        <i class='bx bxs-report me-2'></i>{{ __('Test Report') }}
                                    </a>
                                </li>
                                <li class="user-profile-link" onclick="seeData('#user-add')">
                                    <a href="javascript:void(0)" class="d-flex align-items-center">
                                        <i class='bx bxs-map me-2'></i>{{ __('Patient Address') }}
                                    </a>
                                </li>
                                <li class="user-profile-link" onclick="seeData('#user-fav')">
                                    <a href="javascript:void(0)" class="d-flex align-items-center">
                                        <i class='bx bxs-bookmark-heart me-2'></i>{{ __('Favourite') }}
                                    </a>
                                </li>
                                <li class="user-profile-link" onclick="seeData('#user-setting')">
                                    <a href="javascript:void(0)" class="d-flex align-items-center">
                                        <i class='bx bxs-cog me-2'></i>{{ __('Profile Settings') }}
                                    </a>
                                </li>
                                <li class="user-profile-link" onclick="seeData('#user-changePass')">
                                    <a href="javascript:void(0)" class="d-flex align-items-center">
                                        <i class='bx bxs-lock me-2'></i>{{__('Change Password')}}
                                    </a>
                                </li>
                                <li class="slide">
                                    <a href="javascript:void(0)" class="d-flex align-items-center"></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="disp-block h-100" id="user-dashboard">
                            <div class="m-2 profile-side-bar p-3 pt-0 h-100 pb-2 bg-white">

                                <div class="single-nav">
                                    <ul class="d-flex justify-content-center border-bottom">
                                        <li class="d-flex text-center active">
                                            <a href="javascript:void(0)" onclick="seeData('#Appointments')" class="h-100 w-100">{{ __('Appointments') }}</a></li>
                                        <li class="d-flex text-center">
                                            <a href="javascript:void(0)" class="h-100 w-100" onclick="seeData('#prescription')">{{ __('Prescriptions') }}</a></li>
                                        <li class="d-flex text-center">
                                            <a href="javascript:void(0)" class="h-100 w-100" onclick="seeData('#Purchased_med')">{{ __('Purchased Medicines')}}</a>
                                        </li>
                                    </ul>
                                </div>
                                <div>
                                    <div class="disp-block" id="Appointments">
                                        <div class="card card-table mb-0">
                                            <div class="card-body user_profile_body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-center mb-0 datatable">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>{{ __('Appoitment Id') }}</th>
                                                                <th>{{ __('Report Image') }}</th>
                                                                <th>{{ __('Appointment date') }}</th>
                                                                <th>{{ __('Amount') }}</th>
                                                                <th>{{ __('Appointment status') }}</th>
                                                                <th>{{ __('Action') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($appointments as $appointment)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $appointment->appointment_id }}</td>
                                                                    <td class="d-flex">
                                                                        @if ($appointment->report_image != null)
                                                                            @foreach ($appointment->report_image as $item)
                                                                                <a href="{{ $item }}" data-fancybox="gallery2">
                                                                                    <img src="{{ $item }}" alt="Feature Image" width="50px" height="50px">
                                                                                </a>
                                                                            @endforeach
                                                                        @else
                                                                            {{__('Image Not available')}}
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $appointment->date }}
                                                                        <span class="d-block text-info">{{ $appointment->time }}</span>
                                                                    </td>
                                                                    <td>{{ $currency }}{{ $appointment->amount }}</td>
                                                                    <td>
                                                                        @if($appointment->appointment_status == 'pending' || $appointment->appointment_status == 'PENDING')
                                                                            <span class="badge badge-pill bg-warning">{{__('Pending')}}</span>
                                                                        @endif
                                                                        @if($appointment->appointment_status == 'approve' || $appointment->appointment_status == 'APPROVE')
                                                                            <span class="badge badge-pill bg-success">{{__('Approve')}}</span>
                                                                        @endif
                                                                        @if($appointment->appointment_status == 'cancel' || $appointment->appointment_status == 'CANCEL')
                                                                            <span class="badge badge-pill bg-danger">{{__('Cancelled')}}</span>
                                                                        @endif
                                                                        @if($appointment->appointment_status == 'complete' || $appointment->appointment_status == 'COMPLETE')
                                                                            <span class="badge badge-pill bg-info">{{__('Complete')}}</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="w-100">
                                                                        <div class="d-flex">
                                                                            <a onclick="show_appointment({{ $appointment->id }})" class="text-info ml-2" href="javascript:void(0)" role="button" data-from="add_new" data-bs-toggle="modal" data-bs-target="#user_appointment">
                                                                                {{__('View')}}
                                                                            </a>
                                                                            @if ($appointment->appointment_status == 'complete' || $appointment->appointment_status == 'cancel')
                                                                                @if ($appointment->isReview == false)
                                                                                    <a onclick="appointId({{ $appointment->id }})" class="text-success ml-2 d-flex" href="javascript:void(0)" role="button" data-from="add_new" data-bs-toggle="modal" data-bs-target="#add_review">
                                                                                        {{ __(' Review') }}
                                                                                    </a>
                                                                                @endif
                                                                            @endif
                                                                            @if ($appointment->appointment_status != 'cancel' && $appointment->appointment_status != 'complete')
                                                                                <a href="#cancel_reason" onclick="appointId({{ $appointment->id }})" class="text-danger ml-2 d-flex justify-content-between"  href="javascript:void(0)" role="button" data-bs-toggle="modal" data-bs-target="#cancel_reason">{{__('Cancel appointment') }}</a>
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="disp-none" id="prescription">
                                        <div class="card card-table mb-0">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-center mb-0 datatable text-center">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>{{ __('Appointment ID') }}</th>
                                                                <th>{{ __('Date') }}</th>
                                                                <th>{{ __('Created by ') }}</th>
                                                                <th>{{ __('actions') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($prescriptions as $prescription)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $prescription->appointment['appointment_id'] }}
                                                                    </td>
                                                                    <td>{{ Carbon\Carbon::parse($prescription->created_at)->format('d F Y') }}
                                                                    </td>
                                                                    <td class="d-flex">
                                                                        <a href="{{ url('doctor_profile/' . $prescription->doctor['id'] . '/' . Str::slug($prescription->doctor['name'])) }}" class="avatar avatar-sm mr-2">
                                                                            <img class="avatar-img rounded-circle" src="{{ $prescription->doctor['fullImage'] }}" alt="User Image" width="50px" height="50px">
                                                                        </a>
                                                                        <a href="{{ url('doctor_profile/' . $prescription->doctor['id'] . '/' . Str::slug($prescription->doctor['name'])) }}">{{ $prescription->doctor['name'] }}</a>
                                                                    </td>
                                                                    <td>
                                                                        <div class="table-action">
                                                                            <a href="{{ url('downloadPDF/' . $prescription->id) }}" class="btn btn-sm btn-primary">
                                                                                <i class="fas fa-print"></i>{{ __(' Download PDF') }}
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="disp-none" id="Purchased_med">
                                        <div class="card card-table mb-0">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover datatable table-center mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>{{ __('Id') }}</th>
                                                                <th>{{ __('Amount') }}</th>
                                                                <th>{{ __('Attechment') }}</th>
                                                                <th>{{ __('payment type') }}</th>
                                                                <th>{{ __('payment status') }}</th>
                                                                <th>{{ __('View Medicine') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($purchaseMedicines as $purchaseMedicine)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td><a href="javascript:void(0);">{{ $purchaseMedicine->medicine_id }}</a>
                                                                    </td>
                                                                    <td>{{ $currency }}{{ $purchaseMedicine->amount }}
                                                                    </td>
                                                                    <td>
                                                                        @if (isset($purchaseMedicine->pdf) || $purchaseMedicine->pdf != null)
                                                                            <a href="{{ url('prescription/upload/' . $purchaseMedicine->pdf) }}" data-fancybox="gallery2">
                                                                                {{ $purchaseMedicine->pdf }}
                                                                            </a>
                                                                        @else
                                                                            {{ __('Not available') }}
                                                                        @endif
                                                                    </td>
                                                            <td>{{ $purchaseMedicine->payment_type }}</td>
                                                            <td>
                                                                @if ($purchaseMedicine->payment_status == 1)
                                                                    <span
                                                                        class="btn btn-sm btn-success">{{ __('Paid') }}</span>
                                                                @else
                                                                    <span
                                                                        class="btn btn-sm btn-danger">{{ __('Remaining') }}</span>
                                                                @endif
                                                            </td>
                                                            <td> 
                                                                <a onclick="show_medicines({{ $purchaseMedicine->id }})" class="text-info ml-2" href="javascript:void(0)" role="button" data-from="add_new" data-bs-toggle="modal" data-bs-target="#purchased_medicine">
                                                                    {{__('Medicines')}}
                                                                </a>
                                                            </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="disp-none h-100" id="user-test-report">
                            <div class="m-2 profile-side-bar p-3  pb-2 bg-white h-100">
                                <div class="row row-cols-1 g-0">
                                    <div class="card card-table mb-0">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-center mb-0 datatable">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>{{__('Laboratory Name')}}</th>
                                                            <th>{{__('Prescirption')}}</th>
                                                            <th>{{__('Date time')}}</th>
                                                            <th>{{__('Payment Type')}}</th>
                                                            <th>{{__('Amount')}}</th>
                                                            <th>{{ __('Report') }}</th>
                                                            <th>{{ __('View') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($test_reports as $test_report)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $test_report->lab['name'] }}</td>
                                                                <td class="w-100">
                                                                    <div class="d-flex">
                                                                        @if ($test_report->prescription != null)
                                                                            <a href="{{ 'report_prescription/upload/'.$test_report->prescription }}" data-fancybox="gallery2">
                                                                                <img src="{{ 'report_prescription/upload/'.$test_report->prescription}}" alt="Feature Image" width="50px" height="50px">
                                                                            </a>
                                                                        @else
                                                                            {{__('Prescirption Not available')}}
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                                <td class="w-100">{{ $test_report->date }}<span class="d-block text-info">{{ $test_report->time }}</span>
                                                                </td>
                                                                <td>{{ $test_report->payment_type }}</td>
                                                                <td>{{ $currency }}{{ $test_report->amount }}</td>
                                                                <td>
                                                                    @if ($test_report->upload_report == null)
                                                                        {{ __('Report Not Availabel.') }}
                                                                    @else
                                                                        <a href="{{ 'download_report/'.$test_report->id }}">
                                                                            {{ __('Download Report') }}
                                                                        </a>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <a onclick="single_report({{ $test_report->id }})" class="text-info ml-2" href="javascript:void(0)" role="button" data-bs-toggle="modal" data-bs-target="#test_report">
                                                                        {{__('View')}}
                                                                    </a>                                                                
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="disp-none h-100" id="user-add">
                            <div class="m-2 profile-side-bar p-3 pb-2  bg-white h-100">
                                <div class="d-flex  Appointment-detail">
                                    <a class="btn ms-auto address_btn" href="javascript:void(0)" role="button" data-from="add_new" data-bs-toggle="modal" data-bs-target="#exampleModal">{{ __('Add new') }}</a>
                                </div>
                                <div class="card card-table mb-0">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover text-center mb-0 datatable">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>{{__('Address')}}</th>
                                                        <th>{{__('Action')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($useraddress as $uaddress)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $uaddress->address }}</td>
                                                            <td class="">
                                                                <a href="javascript:void(0)" class="text-success address_btn" data-from="edit" data-id="{{ $uaddress->id }}" data-from="add_new" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                                    <i class="far fa-edit"></i>
                                                                </a>
                                                                <a href="javascript:void(0)" class="text-danger ml-2" onclick="deleteData({{ $uaddress->id }})">
                                                                    <i class="far fa-trash-alt"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="disp-none h-100" id="user-fav">
                            <div class="m-2 profile-side-bar p-3  pb-2 bg-white h-100">
                                <div class="row row-cols-1 g-0">
                                    @foreach ($doctors as $doctor)
                                        <div class="col">
                                            <div class="doct-card p-3 card  m-3  ms-xl-0 pb-2 mb-0 position-relative">
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
                                </div>
                            </div>
                        </div>

                        <div class="disp-none h-100" id="user-setting">
                            <div class="m-2 profile-side-bar p-3 h-100 pb-2 bg-white">
                                <form action="{{ url('update_user_profile') }}" method="post" class="h-100" enctype="multipart/form-data">
                                    @csrf
                                    <div class="change-avtar">
                                        <div class="avatar-upload position-relative">
                                            <div class="avatar-edit position-absolute">
                                                <input type='file' name="image" id="imageUpload" class="d-none" accept=".png, .jpg, .jpeg" />
                                                <label for="imageUpload" class="" data-bs-toggle="tooltip" data-bs-placement="right" title="Select new profile pic"></label>
                                            </div>
                                            <div class="avatar-preview">
                                                <div id="imagePreview" style="background-image: url({{ 'images/upload/'.auth()->user()->image }});">
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <p class="text-center patient-image">{{ __('Patient Image') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pb-4">
                                        <div class="row g-2">
                                            <div class="col-md">
                                                <div>
                                                    <label for="name" class="form-label mb-1">{{ __('Name') }}</label>
                                                    <input type="text" class="form-control" name="name" id="name" value="{{ auth()->user()->name }}" required>

                                                </div>
                                            </div>
                                            <div class="col-md">
                                                <div>
                                                    <label for="mail" class="form-label mb-1">{{ __('Email') }}</label>
                                                    <input type="email" class="form-control" placeholder="Enter email" value="{{ auth()->user()->email }}" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pb-4">
                                        <div class="row g-2">
                                            <div class="col-md">
                                                <div>
                                                    <label for="phone" class="form-label mb-1">{{ __('Phone number') }}</label>
                                                    <input type="tel" readonly value="{{ auth()->user()->phone_code }}&nbsp;{{ auth()->user()->phone }}" class="form-control" id="phone">
                                                </div>
                                            </div>
                                            <div class="col-md">
                                                <label for="name" class="form-label mb-1">{{__('Language')}}</label>
                                                <select class="form-select  form-control" name="language">
                                                    @foreach ($languages as $language)
                                                        <option value="{{ $language->name }}" {{ $language->name == auth()->user()->language ? 'selected' : '' }}>{{ $language->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="pb-4">
                                        <div class="row g-2">
                                            <div class="col-md">
                                                <div>
                                                    <label for="b-date" class="form-label mb-1">{{ __('Date Of Birth') }}</label>
                                                    <input type="date" name="dob" max="{{ Carbon\Carbon::now(env('timezone'))->format('Y-m-d') }}" class="form-control" id="b-date" value="{{ auth()->user()->dob }}">
                                                </div>
                                            </div>
                                            <div class="col-md">
                                                <label for="gender" name="gender" class="form-label mb-1">{{ __('Gender') }}</label>
                                                <select class="form-select  form-control" name="gender">
                                                    <option {{ auth()->user()->gender == 'male' ? 'selected' : '' }} value="male">{{ __('Male') }}</option>
                                                    <option {{ auth()->user()->gender == 'female' ? 'selected' : '' }} value="female">{{ __('Female') }}</option>
                                                    <option {{ auth()->user()->gender == 'other' ? 'selected' : '' }} value="other">{{ __('Other') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex  Appointment-detail">
                                        <button type="submit" class="btn ms-auto">{{ __('Submit') }}</button>
                                    </div>
                                </form>

                            </div>
                        </div>

                        <div class="disp-none h-100" id="user-changePass">
                            <div class="m-2 profile-side-bar p-3 pt-5 h-100 pb-2 bg-white">
                                <form action="{{ url('change_password') }}" method="post" class="h-100">
                                    @csrf
                                        <div class="mb-4">
                                            <label for="old_pass" class="form-label mb-1">{{ __('Old Password') }}</label>
                                            <input type="password" class="form-control" name="old_password" id="old_pass">
                                        </div>
                                        <div class="mb-4">
                                            <label for="new_pass" class="form-label mb-1">{{ __('New Password') }}</label>
                                            <input type="password" class="form-control" name="new_password">
                                        </div>

                                        <div class="mb-4">
                                            <label for="conf_new_pass" class="form-label mb-1">{{ __('Confirm new Password') }}</label>
                                            <input type="password" class="form-control" name="confirm_new_password" id="conf_new_pass">
                                        </div>
                                        <div class="d-flex pt-5 Appointment-detail">
                                            <button type="submit" class="btn ms-auto">{{ __('Change password') }}</button>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

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
                        <input type="hidden" name="from">
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

    <div class="modal fade" id="user_appointment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Appointment') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <tr>
                            <td>{{ __('appointment Id') }}</td>
                            <td class="appointment_id"></td>
                        </tr>
                        <tr>
                            <td>{{ __('Doctor name') }}</td>
                            <td class="doctor_name"></td>
                        </tr>
                        <tr>
                            <td>{{ __('patient name') }}</td>
                            <td class="patient_name"></td>
                        </tr>
                        <tr>
                            <td>{{ __('patient address') }}</td>
                            <td class="patient_address"></td>
                        </tr>
                        <tr>
                            <td>{{ __('patient age') }}</td>
                            <td class="patient_age"></td>
                        </tr>
                        <tr>
                            <td>{{ __('amount') }}</td>
                            <td class="amount"></td>
                        </tr>
                        <tr>
                            <td>{{ __('date') }}</td>
                            <td class="date"></td>
                        </tr>
                        <tr>
                            <td>{{ __('time') }}</td>
                            <td class="time"></td>
                        </tr>
                        <tr>
                            <td>{{ __('payment status') }}</td>
                            <td class="payment_status"></td>
                        </tr>
                        <tr>
                            <td>{{ __('payment type') }}</td>
                            <td class="payment_type"></td>
                        </tr>
                        <tr>
                            <td>{{ __('illness information') }}</td>
                            <td class="illness_info"></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="test_report" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Test Report') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <tr>
                            <td>{{ __('Report Id') }}</td>
                            <td class="report_id"></td>
                        </tr>
                        <tr>
                            <td>{{ __('patient name') }}</td>
                            <td class="patient_name"></td>
                        </tr>
                        <tr>
                            <td>{{ __('patient phone number') }}</td>
                            <td class="patient_phone"></td>
                        </tr>
                        <tr>
                            <td>{{ __('patient age') }}</td>
                            <td class="patient_age"></td>
                        </tr>
                        <tr>
                            <td>{{ __('patient gender') }}</td>
                            <td class="patient_gender"></td>
                        </tr>
                        <tr>
                            <td>{{ __('amount') }}</td>
                            <td class="amount"></td>
                        </tr>
                        <tr>
                            <td>{{ __('payment status') }}</td>
                            <td class="payment_status"></td>
                        </tr>
                        <tr>
                            <td>{{ __('payment type') }}</td>
                            <td class="payment_type"></td>
                        </tr>
                        <tr class="pathology_category_id">
                            <td>{{ __('Pathology category') }}</td>
                            <td class="pathology_category"></td>
                        </tr>
                        <tr class="radiology_category_id">
                            <td>{{ __('Radiology category') }}</td>
                            <td class="radiology_category"></td>
                        </tr>
                        <table class="table types">
                        </table>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="purchased_medicine" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Purchased Medicine') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>{{__('shipping At')}}</td>
                                <td class="shippingAt"></td>
                            </tr>
                            <tr class="shippingAddressTr">
                                <td>{{__('shipping Adddress')}}</td>
                                <td class="shippingAddress"></td>
                            </tr>
                            <tr class="shippingAddressTr">
                                <td>{{__('Delivery Charge')}}</td>
                                <td class="deliveryCharge"></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table">
                        <thead>
                            <th>{{__('medicine name')}}</th>
                            <th>{{__('medicine qty')}}</th>
                            <th>{{__('medicine price')}}</th>
                        </thead>
                        <tbody  class="tbody">
    
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add_review" tabindex="-1" aria-labelledby="add_reviewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Review') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('/addReview') }}" method="post" id="reviewForm">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <input type="hidden" name="appointment_id" value="">
                                <label class="col-form-label">{{ __('Rating') }}</label>
                                <div id="full-stars-example-two">
                                    <div class="rating-group">
                                        <input disabled checked class="rating__input rating__input--none" name="rate" id="rating3-none" value="0" type="radio">
                                        <label aria-label="1 star" class="rating__label" for="rating3-1"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                        <input class="rating__input" name="rate" id="rating3-1" value="1" type="radio">
                                        <label aria-label="2 stars" class="rating__label" for="rating3-2"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                        <input class="rating__input" name="rate" id="rating3-2" value="2" type="radio">
                                        <label aria-label="3 stars" class="rating__label" for="rating3-3"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                        <input class="rating__input" name="rate" id="rating3-3" value="3" type="radio">
                                        <label aria-label="4 stars" class="rating__label" for="rating3-4"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                        <input class="rating__input" name="rate" id="rating3-4" value="4" type="radio">
                                        <label aria-label="5 stars" class="rating__label" for="rating3-5"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                        <input class="rating__input" name="rate" id="rating3-5" value="5" type="radio">
                                    </div>
                                    <span class="invalid-div text-danger"><span class="rate"></span></span>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label class="col-form-label">{{ __('Review') }}</label>
                                <div class="form-group">
                                    <textarea name="review" class="form-control" cols="30" rows="10"></textarea>
                                    <span class="invalid-div text-danger"><span class="review"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="button" onclick="addReview()" class="btn btn-primary">{{ __('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancel_reason" tabindex="-1" aria-labelledby="cancel_reasonLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Review') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="cancelForm">
                    @csrf
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="cancel_by" value="user">
                        <table class="table">
                            @foreach (json_decode($cancel_reason) as $cancel_reason)
                                <tr>
                                    <td>
                                        <div class="position-relative d-flex align-items-center my-1 mt-2">
                                            <input type="radio" class="d-none custom_radio" id="cod{{$loop->iteration}}" name="payment" onchange="seeData('#codPayment')" checked>
                                            <label for="cod{{$loop->iteration}}" class="position-absolute custom-radio"></label>
                                            <label for="cod{{$loop->iteration}}" class="ms-4 normal-label">{{$cancel_reason}}</label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="button" onclick="cancelAppointment()" class="btn btn-primary">{{ __('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{url('assets/js/address.js')}}"></script>
    @if (App\Models\Setting::first()->map_key)
        <script src="https://maps.googleapis.com/maps/api/js?key={{App\Models\Setting::first()->map_key}}&libraries=places&v=weekly" async></script>
    @endif
    <script src="{{url('assets/js/all.min.js')}}"></script>
@endsection