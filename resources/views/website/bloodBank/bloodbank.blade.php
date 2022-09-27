@extends('layout.mainlayout',['active_page' => 'bloodBank'])


@section('title',__('Blood Bank'))
<style>
/* .para{
    font-weight: bold;  
    font-size: 35px; 
    color: red;
} */
span{
    color: black; 
    font-family: Roboto, sans-serif;
    font-size: 18px;
}

.banner_content{
    position: absolute;
    top: 50%;
    left: 70%;
    transform: translate(-50%, -50%);
    width: 90%;
}
.pharmacy_banner{
    object-fit: cover;
    width: 100%
}
.img11{
   height: 100%;
   width:100%;
}
.para-content{
    padding: 10px;
}
.span-txt{
    font-weight: 700;
}
</style>


@section('content')
<div class="site-body bg-white">
    
    <div class="site-herobloodbank overflow-hidden position-relative d-md-block">
        {{-- @foreach ($bloodbanks as $bloodbank )
        <img class="pharmacy_banner" src="{{ url($bloodbank->full_image) }}" alt="">
        @endforeach --}}
        <img class="pharmacy_banner"src="images\upload\62c2ab15972c2.jpg" alt="">
        
        <div class="banner_content">

           <p class="text-center text-danger">रगत चाहियो?</p>
           <p class="text-center para1"><span>Fill in the form and send us your details.<br></span>
            <span>Someone will get back to you asap. If it’s an emergency,</span>
            </p>
            <p class="text-center para1"><span>call us @</span>
            </p>

            <p class="text-center">
            
              <a class="blink" href="tel:9779802955186">9779802955186</a>
              
              <br>
            
              <a class="btn btn-sm bg-danger text-center  text-white mt-2" target="_blank" href="" role="button">Request Blood</a>
            
              <a class="btn btn-sm bg-danger text-white text-center mt-2 ml-2 " target="_blank" href="{{ url('/donate-blood') }}" role="button">Donate Blood</a>
            
            </p>
        </div>        
    </div>

    <div class="container -xl">
        <div class="content mx-auto ">
            <div class="d-flex w-100 justify-content-center">
                <h2 class="text-danger d-flex justify-content-center">LEARN ABOUT DONATION</h2>
            </div>
            
            <div class="row row-cols-1 row-cols-lg-2 row-cols-sm-1 g-0 ">

                <div class="col p-2">
  
                    <div class="card h-100 border-0 looking-card">
                                                
                        <div class="img rounded-3 overflow-hidden">
                            <a class="btn btn-sm text-white text-center mt-2 ml-2 " target="_blank" href="{{ url('/donate-blood') }}">
                                <img class="img11" src="images/upload/62c2bea8dfff3.jpg" alt="...">
                            </a>
                        </div>
  
                    </div>
  
                </div>
  
                <div class="col p-2">
  
                  <div class="card h-100 border-0 looking-card">
                                                
                    <div class="img rounded-3 overflow-hidden">
                        <img class="img11" src="images/upload/62c2bea8e0288.png" alt="...">  
                    </div>
  
                  </div>
                
                </div>
  
        </div>
              <div class="d-flex w-100 justify-content-center">
                    <h2 class="para">TYPE OF DONATION</h2>
                </div>

                <div class="content">
                    <p class="text-center para-content">
                        The human body contains five liters of blood, which is made of several useful components i.e. 
                        <span class="span-txt">Whole blood</span>,<span class="span-txt"> Platelet</span>, and <span class="span-txt">Plasma.</span>
                    </p>

                    <p class="text-center para-content">
                        Each type of component has several medical uses and can be used for different medical treatments. your blood donation determines the best donation for you to make.
                    </p>

                    <p class="text-center para-content">
                        For <span class="span-txt">plasma</span> and <span class="span-txt">platelet</span> donation you must have donated whole blood in past two years.
                    </p>
                </div>

        </div>
    </div>

</div>

@endsection