@extends('layout.mainlayout_admin',['activePage' => 'banner'])

@section('title',__('Add banner'))
@section('content')

<section class="section">
    @include('layout.breadcrumb',[
        'title' => __('Add Banner'),
        'url' => url('banner'),
        'urlTitle' => __('Banner'),
    ])
    <div class="section_body">
        @if (session('status'))
        @include('superAdmin.auth.status',[
            'status' => session('status')])
        @endif
        <div class="card">
            <form action="{{ url('banner') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-4">
                            <label for="banner_image" class="ul-form__label"> {{__('banner image')}}</label>
                        </div>
                        <div class="col-lg-12 col-md-8">
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
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">{{__('Link')}}</label>
                        <input type="text" value="{{ old('link') }}" name="link" class="form-control @error('link') is-invalid @enderror">
                        @error('link')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="col-lg-3">
                                <label for="">{{__('status')}}</label>
                            </div>
                            <div class="col-lg-9">
                                <label class="cursor-pointer">
                                    <input type="checkbox" id="status" class="custom-switch-input" name="status">
                                    <span class="custom-switch-indicator"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection
