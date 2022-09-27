@extends('layout.mainlayout_admin',['activePage' => 'callcenter'])

@section('title',__('Add Call Center'))
@section('content')

<section class="section">
    @include('layout.breadcrumb',[
        'title' => __('Add Call Center'),
        'url' => url('doctor'),
        'urlTitle' =>  __('Call Center'),
    ])
    @if (session('status'))
        @include('superAdmin.auth.status',['status' => session('status')])
    @endif

    <div class="section_body">
        <form action="{{ url('callcenter') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header text-primary">
                    {{__('personal information')}}
                </div>
                <div class="card-body">
                    <div class="row mt-4">
                        <div class="col-lg-2 col-md-4">
                            <label for="callcenter_image" class="col-form-label"> {{__('Call Center image')}}</label>
                            <div class="avatar-upload avatar-box avatar-box-left">
                                <div class="avatar-edit">
                                    <input type='file' id="image" name="image" accept=".png, .jpg, .jpeg" />
                                    <label for="image"></label>
                                </div>
                                <div class="avatar-preview">
                                    <div id="imagePreview">
                                    </div>
                                </div>
                            </div>
                            @error('image')
                                <div class="custom_error">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-10 col-md-8">
                            <div class="form-group">
                                <label class="col-form-label">{{__('Name')}}</label>
                                <input type="text" value="{{ old('name') }}" name="name" class="form-control @error('name') is-invalid @enderror">
                                @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">{{__('email')}}</label>
                                <input type="email" value="{{ old('email') }}" name="email" class="form-control @error('email') is-invalid @enderror">
                                @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-lg-6 form-group">
                            <label for="phone_number" class="col-form-label"> {{__('Phone number')}}</label>
                            <div class="d-flex @error('phone') is-invalid @enderror">
                                <select name="phone_code" class="phone_code_select2">
                                    @foreach ($countries as $country)
                                        <option value="+{{$country->phonecode}}" {{(old('phone_code') == $country->phonecode) ? 'selected':''}}>+{{ $country->phonecode }}</option>
                                    @endforeach
                                </select>
                                <input type="number" min="1" name="phone" class="form-control" value="{{ old('phone') }}">
                            </div>
                            @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-lg-6 form-group">
                            <label class="col-form-label">{{__('Hospital')}}</label>
                            <select name="hospital_id" class="select2 @error('hospital_id') is-invalid @enderror">
                                @foreach ($hospitals as $hospital)
                                    <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                                @endforeach
                            </select>
                            @error('hospital_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-lg-6 form-group">
                            <label class="col-form-label">{{__('Date of birth')}}</label>
                            <input type="text" class="form-control datePicker @error('dob') is-invalid @enderror" name="dob" value="{{ old('dob') }}">
                            @error('dob')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label class="col-form-label">{{__('Gender')}}</label>
                            <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                                <option value="male">{{__('male')}}</option>
                                <option value="female">{{__('female')}}</option>
                                <option value="other">{{__('other')}}</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                </div>
            </div>

            <div class="card">
                <div class="card-header text-primary">
                    {{__('Education and certificate(award details)')}}
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="col-form-label">{{__('Add Education')}}</label>
                        <div class="education-info">
                            <div class="row form-row education-cont">
                                <div class="col-12 col-md-10 col-lg-11">
                                    <div class="row form-row">
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label>{{__('Degree')}}</label>
                                                <input type="text" required name="degree[]" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label>{{__('College/Institute')}}</label>
                                                <input type="text" required name="college[]" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label>{{__('Year of Completion')}}</label>
                                                <input type="text" maxlength="4" pattern="^[0-9]{4}$" required name="year[]" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="add-more">
                            <a href="javascript:void(0);" class="add-education"><i class="fa fa-plus-circle"></i> Add More</a>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="awards-info">
                                <div class="row form-row awards-cont">
                                    <div class="col-12 col-md-5">
                                        <div class="form-group">
                                            <label>{{__('certificate')}}</label>
                                            <input type="text" required name="certificate[]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-5">
                                        <div class="form-group">
                                            <label>{{__('Year')}}</label>
                                            <input type="text" required name="certificate_year[]" maxlength="4" pattern="^[0-9]{4}$" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="add-more">
                                <a href="javascript:void(0);" class="add-award"><i class="fa fa-plus-circle"></i> Add More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header text-primary">
                    {{__('Other information')}}
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="col-form-label">{{__('Experience (in years)')}}</label>
                        <input type="number" min="1" name="experience" value="{{ old('experience') }}" class="form-control @error('experience') is-invalid @enderror">
                        @error('number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="row mt-4">
                        <div class="col-lg-6 form-group">
                            <label class="col-form-label">{{__('Start Time')}}</label>
                            <input class="form-control timepicker @error('start_time') is-invalid @enderror" name="start_time" value="08.00" type="time">
                            @error('start_time')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-6 form-group">
                            <label class="col-form-label">{{__('End Time')}}</label>
                            <input class="form-control timepicker @error('end_time') is-invalid @enderror" name="end_time" value="20.00" type="time">
                            @error('end_time')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="text-right p-2">
                    <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                </div>
            </div>
        </form>
    </div>
</section>

@endsection

