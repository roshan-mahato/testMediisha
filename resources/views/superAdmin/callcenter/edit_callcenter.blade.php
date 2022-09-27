@extends('layout.mainlayout_admin',['activePage' => 'callcenter'])

@section('title',__('Edit Call Center'))
@section('content')

<section class="section">
    @include('layout.breadcrumb',[
        'title' => __('Edit Call Center'),
        'url' => url('callcenter'),
        'urlTitle' =>  __('Call Center'),
    ])
    <form action="{{ url('callcenter/'.$callcenter->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
        <div class="card">
            <div class="card-header text-primary">
                {{__('personal information')}}
            </div>
            <div class="card-body">
                <div class="row mt-4">
                    <div class="col-lg-2 col-md-4">
                        <label for="Doctor_image" class="ul-form__label"> {{__('Call Center image')}}</label>
                        <div class="avatar-upload avatar-box avatar-box-left">
                            <div class="avatar-edit">
                                <input type='file' id="image" name="image" accept=".png, .jpg, .jpeg" />
                                <label for="image"></label>
                            </div>
                            <div class="avatar-preview">
                                <div id="imagePreview" style="background-image: url({{ $callcenter->fullImage }});">
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
                            <input type="text" value="{{ $callcenter->name }}" name="name" class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
    
                        <label class="col-form-label">{{__('email')}}</label>
                        <div class="form-group">
                            <input type="email" readonly value="{{ $callcenter->user['email'] }}" name="email" class="form-control @error('email') is-invalid @enderror">
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
                        <div class="d-flex">
                            <select name="phone_code" class="phone_code_select2" disabled>
                                @foreach ($countries as $country)
                                    <option value="+{{$country->phonecode}}" {{ $callcenter->user['phone_code'] == +$country->phonecode ? 'selected' : '' }}>+{{ $country->phonecode }}</option>
                                @endforeach
                            </select>
                            <input type="number" min="1" readonly value="{{$callcenter->user['phone']}}" name="phone" class="form-control @error('phone') is-invalid @enderror">
                        </div>
                        @error('phone')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-lg-6 form-group">
                        <label class="col-form-group">{{__('Hospital')}}</label>
                        <select name="hospital_id" class="select2 @error('hospital_id') is-invalid @enderror">
                            @foreach ($hospitals as $hospital)
                                <option value="{{ $hospital->id }}" {{ $callcenter->hospital_id == $hospital->id ? 'selected' : '' }}>{{ $hospital->name }}</option>
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
                        <label class="col-form-group">{{__('Date of birth')}}</label>
                        <input type="text" value="{{ $callcenter->dob }}" class="form-control datePicker @error('dob') is-invalid @enderror" name="dob">
                        @error('dob')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-6 form-group">
                        <label class="col-form-group">{{__('Gender')}}</label>
                        <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                            <option value="male" {{ $callcenter->gender == 'male' ? 'selected' : '' }}>{{__('male')}}</option>
                            <option value="female" {{ $callcenter->gender == 'female' ? 'selected' : '' }}>{{__('female')}}</option>
                            <option value="other" {{ $callcenter->gender == 'other' ? 'selected' : '' }}>{{__('other')}}</option>
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
                <div class="row mt-4">
                    <div class="col-lg-12 form-group">
                        <label class="col-form-group">{{__('Add Education')}}</label>
                        <div class="education-info">
                            @if (json_decode($callcenter->education))
                                @foreach (json_decode($callcenter->education) as $education)
                                <div class="row form-row education-cont">
                                        <div class="col-12 col-md-10 col-lg-11">
                                            <div class="row form-row">
                                                <div class="col-12 col-md-6 col-lg-4">
                                                    <div class="form-group">
                                                        <label>{{__('Degree')}}</label>
                                                        <input type="text" value="{{ $education->degree }}" required name="degree[]" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6 col-lg-4">
                                                    <div class="form-group">
                                                        <label>{{__('College/Institute')}}</label>
                                                        <input type="text" value="{{ $education->college }}" required name="college[]" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6 col-lg-4">
                                                    <div class="form-group">
                                                        <label>{{__('Year of Completion')}}</label>
                                                        <input type="text" maxlength="4" value="{{ $education->year }}" pattern="^[0-9]{4}$" required name="year[]" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($loop->iteration != 1)
                                            <div class="col-12 col-md-2 col-lg-1">
                                                <label class="d-md-block d-sm-none d-none">&nbsp;</label>
                                                <a href="javascript:void(0);" class="btn btn-danger trash">
                                                    <i class="far fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        @endif
                                </div>
                                @endforeach
                            @else
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
                            @endif
                        </div>
                        <div class="add-more">
                            <a href="javascript:void(0);" class="add-education"><i class="fa fa-plus-circle"></i>{{__(' Add More')}}</a>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-12 form-group">
                        <div class="awards-info">
                            @if (json_decode($callcenter->certificate))
                                @foreach (json_decode($callcenter->certificate) as $certificate)
                                    <div class="row form-row awards-cont">
                                        <div class="col-12 col-md-5">
                                            <div class="form-group">
                                                <label>{{__('certificate')}}</label>
                                                <input type="text" value="{{ $certificate->certificate }}" required name="certificate[]" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-5">
                                            <div class="form-group">
                                                <label>{{__('Year')}}</label>
                                                <input type="text" required value="{{ $certificate->certificate_year }}" name="certificate_year[]" maxlength="4" pattern="^[0-9]{4}$" class="form-control">
                                            </div>
                                        </div>
                                        @if ($loop->iteration != 1)
                                            <div class="col-12 col-md-2">
                                                <label class="d-md-block d-sm-none d-none">&nbsp;</label>
                                                <a href="javascript:void(0);" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
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
                            @endif
                        </div>
                        <div class="add-more">
                            <a href="javascript:void(0);" class="add-award"><i class="fa fa-plus-circle"></i>{{__(' Add More')}}</a>
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
                <div class="row mt-4">
                    <div class="col-lg-6 form-group">
                        <label class="col-form-group">{{__('Experience (in years)')}}</label>
                        <input type="number" min="1" name="experience" value="{{ $callcenter->experience }}" class="form-control @error('experience') is-invalid @enderror">
                        @error('number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                
                <div class="row mt-4">
                    <div class="col-lg-6 form-group">
                        <label class="col-form-group">{{__('Start Time')}}</label>
                        <input class="form-control timepicker @error('start_time') is-invalid @enderror" value="{{ $callcenter->start_time }}" name="start_time" type="time">
                        @error('start_time')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-6 form-group">
                        <label class="col-form-group">{{__('End Time')}}</label>
                        <input class="form-control timepicker @error('end_time') is-invalid @enderror" value="{{ $callcenter->end_time }}"  name="end_time" type="time">
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
</section>

@endsection

