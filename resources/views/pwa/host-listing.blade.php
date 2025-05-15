@extends('pwa.layout.index')
@section('content')


 

    <!-- loader -->
    <!-- div id="loader">
        <img src="{{url('pwa')}}/assets/img/logo_instagram.png" alt="icon" class="loading-icon">
    </div> -->
    <!-- * loader -->


    <!-- App Capsule -->
    <div id="appCapsule" class="full-height">

        
        <!-- App Header -->
        <div class="appHeader">
            <div class="left">
                <a href="#" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle"> 
                <button type="button" class="btn btn-text-primary rounded shadowed"  data-bs-toggle="modal" data-bs-target="#actionSheetForm5">{{$host->name}}</button>
            </div>
            <div class="right pt-8"> 
            </div>
        </div>
        <!-- * App Header -->


 
        <div class="section2 mt-2">
            
             <div class="transactions p-2">
               <!-- item -->

               @foreach($list as $row)

                @include('pwa.common.single_space_list_wise' , ["row" => $row ])

               @endforeach
               
            </div>

        </div>


    </div>
    <!-- * App Capsule -->
    

@endsection 

@section('js')   


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 
 
 @endsection 


