<div class="left pl-10">
    @if ($user)
        <h3 class="m-0">Hello {{ $user->user_name }}</h3>
    @else
        <h3 class="m-0">
            <img class="pwa-head-logo" src="{{url('images/logo_myoffice.png')}}" alt="">
        </h3>
    @endif
</div>

<div class="right">

        @if ($user)
        <div id="profile-container" class="mr-10">

         <a href="#">
            <ion-icon class="vl-m" name="notifications-outline">
            </ion-icon>
           </a>
        </div>

        <div id="profile-container" class="mr-10">

            <a href="{{ route('pwa.get.myFavourites') }}">
                <ion-icon name="heart"></ion-icon>
            </a>
        </div>


        <div id="profile-container" class="mr-10 profile-border">

            <a href="{{ route('pwa.get.profile') }}">
                <image id="profileImage"
                    src="{{ get_file_url(old('avatar_id', $user->avatar_id)) ?? ($user->getAvatarUrl() ?? '') }}" />
            </a>
        </div>


        @else
            <a href="{{ route('pwa.get.signin') }}" class="login-pwa-top-icon" title="Login">
                <!-- <image id="profileImage" src="{{ url('pwa') }}/assets/img/profile.PNG" /> -->
                <ion-icon class="vl-m" name="log-in-outline"></ion-icon>
                {{-- <p>Login</hp> --}}
            </a>
        @endif


    @if ($user)
        {{-- <a href="notifications.html">
            <ion-icon class="vl-m" name="notifications-outline">
            </ion-icon>
        </a> --}}
    @else
    @endif

</div>
