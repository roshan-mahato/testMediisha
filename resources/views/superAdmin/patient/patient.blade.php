@extends('layout.mainlayout_admin',['activePage' => 'patients'])

@section('title',__('All patient'))

@section('content')

<section class="section">
    @include('layout.breadcrumb',[
        'title' => __('Patient'),
    ])
    <div class="section_body">
        @if (session('status'))
        @include('superAdmin.auth.status',[
            'status' => session('status')])
        @endif
        <div class="card">
            <div class="card-header w-100 text-right d-flex justify-content-between">
                @include('superAdmin.auth.exportButtons')
                @can('patient_add')
                    <a href="{{ url('patient/create') }}">{{ __("Add New") }}</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="datatable table table-hover table-center mb-0 text-center">
                        <thead>
                            <tr>
                                <th>
                                    <input name="select_all" value="1" id="master" type="checkbox" />
                                    <label for="master"></label>
                                </th>
                                <th>#</th>
                                <th>{{__('User Name')}}</th>
                                <th>{{__('email')}}</th>
                                <th>{{__('Status')}}</th>
                                @if (Gate::check('patient_edit') || Gate::check('patient_delete'))
                                    <th>{{__('Actions')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="id[]" value="{{$user->id}}" id="{{$user->id}}" data-id="{{ $user->id }}" class="sub_chk">
                                        <label for="{{$user->id}}"></label>
                                    </td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <p class="table-avatar">
                                            <a href="{{ url('patient/'.$user->id) }}" class="avatar avatar-sm mr-2">
                                                <img class="avatar-img rounded-circle" src="{{ $user->fullImage }}" alt="patient Image"></a>
                                            <a href="{{ url('patient/'.$user->id) }}">{{ $user->name }}</a>
                                        </p>
                                    </td>
                                    <td>
                                        <span class="text_transform_none">{{ $user->email }}</span>
                                    </td>
                                    <td>
                                        <label class="cursor-pointer">
                                            <input type="checkbox" id="status{{$user->id}}" class="custom-switch-input" name="status" onchange="change_status('user',{{ $user->id }})" {{ $user->status == 1 ? 'checked' : '' }}>
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </td>
                                    <td>
                                        @if (Gate::check('patient_edit') || Gate::check('patient_delete'))
                                            <a href="{{ url('patient/'.$user->id) }}" class="text-info">
                                                <i class="far fa-eye"></i>
                                            </a>
                                            @can('patient_edit')
                                            <a class="text-success" href="{{url('patient/'.$user->id.'/edit/')}}">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            @endcan
                                            @can('patient_delete')
                                            <a class="text-danger" href="javascript:void(0);" onclick="deleteData('patient',{{ $user->id }})">
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
            <div class="card-footer">
                <input type="button" value="delete selected" onclick="deleteAll('patient_all_delete')" class="btn btn-primary">
            </div>
        </div>
    </div>
</section>

@endsection
