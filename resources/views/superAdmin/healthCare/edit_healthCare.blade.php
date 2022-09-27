@extends('layout.mainlayout_admin',['activePage' => 'healthCare'])

@section('title',__('Add HealthCare Category'))
@section('content')

<section class="section">
    @include('layout.breadcrumb',[
        'title' => __('Edit HealthCare Category'),
        'url' => url('healthCare'),
        'urlTitle' => __('HealthCare'),
    ])
    <div class="card">
        <form action="{{ url('healthCare/'.$health_care->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label class="col-form-label">{{__('Name')}}</label>
                    <input type="text" value="{{ $health_care->name }}" name="name" class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                {{-- <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4>Add more Information (Required)</h4>
                        <a href="javascript:void(0);" class="add-moreInfo float-right">
                            <i class="fa fa-">Add Field</i>
                        </a>
                    </div>

                </div> --}}

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                </div>
        </form>
    </div>
</section>

@endsection
