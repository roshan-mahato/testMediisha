@extends('layout.mainlayout_admin',['activePage' => 'healthCare'])

@section('css')
    <link rel="stylesheet" href="{{asset('assets/plugins/daterangepicker/daterangepicker.css')}}">
@endsection

@section('title',__('HealthCare'))
@section('content')

<section class="section">
    @include('layout.breadcrumb',[
        'title' => __('Health Care'),
    ])
    @if (session('status'))
    @include('superAdmin.auth.status',[
        'status' => session('status')])
    @endif

    <div class="section_body">
        <div class="card">
            

            <div class="card-body">
                <div class="card-header w-100">
                    @include('superAdmin.auth.exportButtons')
                    <a href="{{ url('healthCare/create') }}" class="w-100 text-right">{{ __('Add New') }}</a>
                </div>

                <div class="table-responsive text-center">
                    <table class="w-100 display table datatable">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th>{{__('Category Name')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Display')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($health_cares as $health_care )
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $health_care->name }}</td>
                                <td>
                                    <label class="cursor-pointer">
                                        <input type="checkbox"id="status_1{{$health_care->id}}" class="custom-switch-input" onchange="change_status('health_care',{{ $health_care->id }})" {{ $health_care->status == 1 ? 'checked' : '' }}>
                                        <span class="custom-switch-indicator"></span>
                                    </label>
                                </td>

                                <td>
                                    <label class="cursor-pointer">
                                        <input type="checkbox"id="status_1{{$health_care->id}}" class="custom-switch-input" onchange="change_status('health_care',{{ $health_care->id }})" {{ $health_care->status == 1 ? 'checked' : '' }}>
                                        <span class="custom-switch-indicator"></span>
                                    </label>
                                </td>

                                <td>
                                                                      
                                        <a class="text-success" href="{{url('healthCare/'.$health_care->id.'/edit')}}">
                                            <i class="far fa-edit"></i>
                                        </a>
                                       
                                       
                                        <a class="text-danger" href="javascript:void(0);" onclick="deleteData('healthCare',{{ $health_care->id }})">
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
</section>

@endsection

@section('js')
    <script src="{{asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
@endsection
