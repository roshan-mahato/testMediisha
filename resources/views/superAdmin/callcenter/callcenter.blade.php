@extends('layout.mainlayout_admin',['activePage' => 'callcenter'])

@section('css')
    <link rel="stylesheet" href="{{asset('assets/plugins/daterangepicker/daterangepicker.css')}}">
@endsection

@section('title',__('Call Center'))
@section('content')

<section class="section">
    @include('layout.breadcrumb',[
        'title' => __('Call Center'),
    ])
    @if (session('status'))
    @include('superAdmin.auth.status',[
        'status' => session('status')])
    @endif

    <div class="section_body">
        <div class="card">
            <div class="card-header w-100 text-right d-flex justify-content-between">
                @include('superAdmin.auth.exportButtons')
                @can('callcenter_add')
                    <a href="{{ url('callcenter/create') }}" class="w-100 text-right">{{ __('Add New') }}</a>
                @endcan               
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="datatable table table-hover table-center mb-0">
                        <thead>
                            <tr>
                                <th>
                                    <input name="select_all" value="1" id="master" type="checkbox" />
                                    <label for="master"></label>
                                </th>
                                <th> # </th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Email')}}</th>
                                <th>{{__('Status')}}</th>
                                @if (Gate::check('callcenter_edit') || Gate::check('callcenter_delete'))
                                    <th>{{__('Actions')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($callcenters as $callcenter )
                            <tr>
                                <td>
                                    <input type="checkbox" name="id[]" value="{{$callcenter->id}}" id="{{$callcenter->id}}" data-id="{{ $callcenter->id }}" class="sub_chk">
                                    <label for="{{$callcenter->id}}"></label>
                                </td>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $callcenter->name }}</td>
                                <td>
                                    <a href="mailto:{{$callcenter->user['email']}}">
                                        <span class="text_transform_none">{{ $callcenter->user['email'] }}</span>
                                    </a>
                                </td>
                                <td>
                                    <label class="cursor-pointer">
                                        <input type="checkbox"id="status_1{{$callcenter->id}}" class="custom-switch-input" onchange="change_status('callcenter',{{ $callcenter->id }})" {{ $callcenter->status == 1 ? 'checked' : '' }}>
                                        <span class="custom-switch-indicator"></span>
                                    </label>
                                </td>
                                
                                <td>    
                                    @if (Gate::check('callcenter_edit') || Gate::check('callcenter_delete'))
                                        @can('callcenter_edit')
                                            <a class="text-success" href="{{url('callcenter/'.$callcenter->id.'/edit')}}">
                                                <i class="far fa-edit"></i>
                                            </a>
                                        @endcan                                                                
                                        @can('callcenter_delete')
                                            <a class="text-danger" href="javascript:void(0);" onclick="deleteData('callcenter',{{ $callcenter->id }})">
                                                <i class="far fa-trash-alt"></i>
                                            </a>
                                        @endcan
                                    @endif                                             
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card_fotter">
                <input type="button" value="delete selected" onclick="deleteAll('callcenter_all_delete')" class="btn btn-primary">
            </div>
        </div>
    </div>
</section>

@endsection

@section('js')
    <script src="{{asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
@endsection
