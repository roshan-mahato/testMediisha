@extends('layout.mainlayout',['active_page' => 'blood_donate'])

@section('title',__('Blood Bank'))

<style>
    .section_request_form{
        margin: 1rem;
    }
    .mt-4{
        margin-top: 1.5rem;
    }
    .form-group{
        padding-right: 2px;
        padding-left: 2px;
    }

    .breadcrumb{
        max-height: 300px;
        background-color: #cf3d3c;
        opacity: 0.9;
    }
    .breadcrumb_inner{
        max-height: 300px;;
    }
    .request_form_title{
        color: white;
        font-weight: 500;
        font-size: 40px;
        margin-bottom: 10px;
        margin-top: 5rem;
    }
    .breadcrumb_inner_item p{
        font-size:16px; 
        color: white;
    }
    .container{
        height: 250px;
        margin: auto;
    }
    .form-group{
        margin-bottom: 1.25rem;
    }
</style>

@section('content')

    <section class="breadcrumb">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <div class="breadcrumb_inner text-center">
                <div class="breadcrumb_inner_item">
                  <h1 class="request_form_title">Donate Blood</h1>
                  <p>Register with us today to pledge to donate blood and we will notify you when donation events come up near your area OR in Emergency.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

<section class="section_request_form">
    <div class="row mt-4">
        <div class="col-lg-1"></div>
        <div class="col-lg-10">
            <form action="{{ url('/store_blood_donor') }}" method="post" >
                @csrf
                    <div class="form-row d-lg-flex">
                        <div class="form-group col-md-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                            </svg>
                            <label class="col-form-label">{{__('Name*')}}</label>
                            <input type="text" value="{{ old('name') }}" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Your Full Name">
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6" style="padding: ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                                <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94zM8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z"/>
                                <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                            </svg>
                            <label class="col-form-label">{{__('Address*')}}</label>
                            <input type="text" value="{{ old('address') }}" name="address" class="form-control @error('address') is-invalid @enderror" placeholder="Address">
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row d-lg-flex">

                    
                        <div class="form-group col-md-6 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
                                <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                            </svg>
                            <label class="col-form-label">{{__('Phone*')}}</label>
                            <input type="text" value="{{ old('phone') }}" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="Your Contact Number">
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                            </svg>
                            <label class="col-form-label">{{__('Location you want to donate blood*')}}</label>
                            <select class="form-control" name="location">
                                <option>Select a location</option>
                                <option value="chitwan">Chitwan</option>
                                <option value="kathmandu">Kathmandu</option>
                                <option value="Pokhara">Pokhara</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row d-lg-flex">
                        <div class="form-group col-md-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-droplet-fill" viewBox="0 0 16 16">
                                <path d="M8 16a6 6 0 0 0 6-6c0-1.655-1.122-2.904-2.432-4.362C10.254 4.176 8.75 2.503 8 0c0 0-6 5.686-6 10a6 6 0 0 0 6 6ZM6.646 4.646l.708.708c-.29.29-1.128 1.311-1.907 2.87l-.894-.448c.82-1.641 1.717-2.753 2.093-3.13Z"/>
                            </svg>
                            <label class="col-form-label">{{__('Blood Group*')}}</label>
                            <select class="form-control" name="blood_group" id="">
                                <option >Select a blood group</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="Unknown">Unknown</option>
                            </select>  
                        </div>
                        <div class="form-group col-md-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                            </svg>
                            <label class="col-form-label">{{__('Date of Birth*')}}</label>
                            <input type="date" value="{{ old('dob') }}" name="dob" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            {{-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gender-ambiguous" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M11.5 1a.5.5 0 0 1 0-1h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V1.707l-3.45 3.45A4 4 0 0 1 8.5 10.97V13H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V14H6a.5.5 0 0 1 0-1h1.5v-2.03a4 4 0 1 1 3.471-6.648L14.293 1H11.5zm-.997 4.346a3 3 0 1 0-5.006 3.309 3 3 0 0 0 5.006-3.31z"/>
                            </svg> --}}
                            <label class="col-form-label">{{__('Gender*')}}</label>
                            <select class="form-control" name="gender" id="">
                                <option>Select a gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="others">Others</option>
                            </select>
                            
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary" style="background-color: #cf3d3c;">{{__('Submit')}}</button>
                    </div>
            </form>
        </div>

    </div>
</section>
@endsection
