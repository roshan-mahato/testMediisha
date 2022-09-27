@extends('layout.mainlayout',['active_page' => 'about us'])

@section('title',__('About Us'))

@section('content')

<div class="content p-5">
    <div class="container">
        {!! clean($about_us) !!}
    </div>
</div>
@endsection
