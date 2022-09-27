@extends('layout.mainlayout',['active_page' => 'blog'])

@section('title',__($blog->title))

@section('css')
<style>
 .card {
	 text-align: center;
}
 .card__img {
	 margin-bottom: 5px;
}
 .card__title {
	 text-transform: capitalize;
	 color: var(--site_color);
	 line-height: 20px;
	 font-size: 20px;
	 margin-top: 10px;
}
 .card__text {
	 color: var(--grey);
	 font-size: 16px;
	 line-height: 26px;
	 margin-bottom: 20px;
}
 .card__readbtn {
	 font-size: 14px;
	 text-transform: uppercase;
	 color: var(--site_color_hover);
	 text-decoration: none;
	 line-height: 26px;
	 transition: all ease 0.3s;
	 position: relative;
}
 .card__readbtn::after {
	 content: "";
	 position: absolute;
	 left: 0;
	 bottom: 0;
	 width: 0;
	 height: 2px;
	 background-color: var(--site_color);
	 transition: all ease 0.3s;
}
 .card__readbtn:hover {
	 color: var(--site_color);
}
 .card__readbtn:hover::after {
	 width: 100%;
}
 .divider {
	 background-color: var(--site_color_hover);
	 height: 2px;
	 max-width: 30px;
	 margin: 15px auto 20px;
}
 .grid {
	 display: grid;
	 grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
	 gap: 35px;
	 max-width: 1300px;
	 margin: 50px auto;
	 padding: 0 10px;
}

.card__contenido .content_div
{
    margin-left: 5px;
    margin-right: 5px;
}

.card__contenido
{
    padding: 18px;
}
</style>
@endsection

@section('content')
<div class="grid">
    <div class="card">
        <div class="card__img">
            <img src="{{ url($blog->full_image) }}" width="100%" alt="">
        </div>
        <div class="card__contenido">
            <div class="content_div d-flex justify-content-between">
                <small class="text-muted">
                    <i class='bx bxs-book-content' ></i>{{ $blog->blog_ref }}
                    </small>
                <small class="text-muted">
                    <i class='bx bxs-stopwatch'></i>{{Carbon\Carbon::parse($blog['created_at'])->format('y-m-d h:i A')}}
                </small>
            </div>
            <h3 class="card__title">
                {{ $blog->title }}
            </h3>
            <div class="divider"></div>
            <p class="card__text">
                {!! clean($blog->desc) !!}
            </p>
        </div>
    </div>
</div>
@endsection