@extends('pwa.layout.index')
@section('content')


    <!-- loader -->
  <!--   <div id="loader">
        <img src="{{url('pwa')}}/assets/img/logo_instagram.png" alt="icon" class="loading-icon">
    </div> -->
    <!-- * loader -->

    <!-- App Header -->
    <div class="appHeader br-0 h-96">
        <div class="left pl-10">
            @if($user)
                <h3 class="m-0">Hello {{ $user->user_name}}</h3>
           @else
                <h3 class="m-0">Hello </h3>
           @endif
        </div>
        
        <div class="right">
            <!-- <img src="assets/img/Ellipse 1.png" alt="image" class="imaged1 w30 mr-10" data-bs-toggle="modal" data-bs-target="#actionSheetForm6"> -->
       
            @include('pwa.layout._top-bar')

         <!-- <input id="imageUpload" type="file" name="profile_photo" placeholder="Photo" required="" capture> -->
           
              </div>

              <ul class="nav nav-tabs lined  mt-13" role="tablist">
                <li class="nav-item">
                    <a class="nav-links col-b f-11 fw-bold tabs "  href="{{url('m/home')}}" role="tab" aria-selected="false">
                       HOURLY
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-links col-b f-11 fw-bold  "  href="{{url('m/home').'?type=long_term'}}" role="tab" aria-selected="true">
                       LONG TERM
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-links col-b f-11 fw-bold  "  href="{{url('m/home').'?type=parking'}}" role="tab" aria-selected="true">
                       PARKING
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-links col-b f-11 fw-bold active"  href="{{route('pwa.get.cafesList')}}" role="tab" aria-selected="true">
                        CAFES
                    </a>
                </li>
              
            </ul>
             
    </div>
    <!-- * App Header -->
    

    <!-- App Capsule -->
    <div id="appCapsule" class="full-height mt-50">

    	<div class="section2 mt-2">
            <div class="transactions">


            	@foreach($list as $row)

        			@include('pwa.common.single_space_list_wise' , ["row" => $row ])
                    
                @endforeach

            </div>
        </div>

    </div>

@endsection 