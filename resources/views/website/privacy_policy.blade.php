@extends('layout.mainlayout',['active_page' => 'privacy policy'])

@section('title',__('Privacy Policy'))

@section('content')

<div class="content p-5">
    <div class="container">
        {!! clean($privacy_policy) !!}
    </div>
</div>
@endsection
