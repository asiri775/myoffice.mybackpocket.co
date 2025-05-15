@extends('pwa.layout.index')
@section('content')

<!-- loader -->
<div id="loader">
    <img src="{{url('pwa')}}/assets/img/logo_instagram.png" alt="icon" class="loading-icon">
</div>
<!-- * loader -->




<div class="content-area ">
    <div class=" home pt-1 h-300 bg-img1">
        
        <img class="img-o" src="{{url('pwa')}}/assets/img/logo_myoffice2_400x100.png" alt="">
        <p class="text-center mt">Find the right Space for You.</p>
        </div>

        <!-- <div class="row bg-b ">
           <div class="col c1">
            <h4 class="center yel">Log In</h4>
           </div>
           <div class="col c2">
            <h4 class="center whit">Sign Up</h4>
           </div>
        </div> -->
        <div class="card box-s">
            <div class=" pt-0">

                <ul class="nav nav-tabs lined bg-b" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link f-16 active fw-bold"   role="tab" aria-selected="false">
                           Log In
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('pwa.get.signup') }}" class="nav-link f-16 fw-bold "    role="tab" aria-selected="true">
                            Sign Up
                        </a>
                    </li>
                  
                </ul>
                <div class=" card-body tab-content mt-2">
                    <div class="tab-pane fade active show" id="login" role="tabpanel">
                        

                        <form class="form bravo-form-login themeForm" method="POST" action="{{route('login')}}" >
                        <input type="hidden" name="redirect" value="{{ request()->query('redirect') }}">
 
                        @csrf

                            <div class="form-group boxed">
                                <div class="input-wrapper">
                                    <input type="text" class="form-control" id="email4b" placeholder="Email" name='email'>
                                    <!-- <i class="clear-input">
                                        <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                    </i> -->
                                    <span class="invalid-feedback error error-email"></span>
                                </div>
                                <div class="input-wrapper mt-2">
                                    <input id="password-field" type="password" class="form-control" name="password" value="" placeholder="Password">
                                         <i class="clear-input">
                                    
                                        <ion-icon  toggle="#password-field" class="toggle-password" style="margin-left: -30px; cursor: pointer;" name="eye-outline"></ion-icon>
                                    </i>
                                    <span class="invalid-feedback error error-password"></span>
                                </div>
                            </div>
                            
                            
                            <div class="error message-error invalid-feedback"></div>

                            
                            <div class=" mt-2">
                                {{-- <a href="#" class="btn btn-primary btn-lg btn-block">
                                    Sign In</a> --}}
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    {{ __('Login') }}
                                    {{-- <span class="spinner-grow spinner-grow-sm icon-loading" role="status" aria-hidden="true"></span> --}}
                                </button>
                            </div>

                            <!-- <p class="f-16 mt-2 text-center "><a class="text-dark fw-bold" href="forgot-password.html">Forgot password?</a></p> -->
                            <div class="spacer"></div>

                        </form>


                        </div>
                    <div class="tab-pane fade" id="cards2" role="tabpanel">                                
                    
                            <div class=" mt-2">
                               <p class="text-center">"Sign Up content goes here"</p>
                            </div>
                    </div>
                   
                </div>
            </div>
        </div>

        
        </div>


    </div>
<div>


@endsection





