@extends('pwa.layout.index')
@section('content')
    <!-- loader -->
    <div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <!-- * loader -->
    <!-- App Header -->
    <div class="appHeader bg-dark text-light">
        <div class="left">
            <a href="#" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Profile</div>



        <div class="right">
            <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#actionInfoForm">
                <ion-icon name="create-outline"></ion-icon>
            </a>
        </div>
    </div>





    <!-- * App Header -->
    <!-- App Capsule -->
    <div id="appCapsule">

        @if (Session::has('error'))
            <script>
                window.webAlerts.push({
                    type: "error",
                    message: "{{ Session::get('error') }}"
                });
            </script>
        @endif
        @if (Session::has('success'))
            <script>
                window.webAlerts.push({
                    type: "success",
                    message: "{{ Session::get('success') }}"
                });
            </script>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <script>
                        window.webAlerts.push({
                            type: "error",
                            message: "{{ $error }}"
                        });
                    </script>
                @endforeach
            </div>
        @endif

        <style>
            .profile-head .in .subtext {
                width: 150px;
            }
        </style>

        <div class="section mt-2 profile">
            <div class="left profile-head">
                <div class="avatar">
                    <img src="{{ get_file_url(old('avatar_id', $data['dataUser']->avatar_id)) ?? ($data['dataUser']->getAvatarUrl() ?? '') }}"
                        alt="avatar" class="imaged w64 rounded">
                </div>
                <div class="in">
                    <h3 class="name">{{ $data['dataUser']->first_name }} {{ $data['dataUser']->last_name }}</h3>
                    <h5 class="subtext">{!! $data['dataUser']->formattedAddress() !!}</h5>
                </div>
            </div>
            <div class="right credit-round">
                <div class="credit-price">
                    ${{ $data['dataUser']->balance }}
                </div>
                <p class="small pb-0 mb-0">CREDITS</p>
            </div>
        </div>
        <div class="section full mt-2">
            <div class="profile-stats ps-2 pe-2">
                <a href="{{ route('pwa.get.profile') }}#booking" class="item open-profile-tabs">
                    <strong>{{ count($booking_list) }}</strong>Bookings
                </a>
                <a href="{{ route('pwa.get.profile') }}#bookmarks" class="item open-profile-tabs">
                    <strong>{{ count($fav_list) }}</strong>Favourites
                </a>
                <a href="{{ route('pwa.get.profile') }}#reviews" class="item open-profile-tabs">
                    <strong>{{ $totalReviews != null ? count($totalReviews) : 0 }}</strong>Reviews
                </a>
            </div>
        </div>
        <div class="section mt-1 mb-2">
            <div class="profile-info">
                <div class="bio">
                    {{ $data['dataUser']->profile_info ?? '' }}
                </div>
                <?php
                    if(trim($data['dataUser']->site_link) != null && trim($data['dataUser']->instagram_link) != null){
                ?>
                <div class="link">
                    <a href="#" target="_blank">{{ $data['dataUser']->instagram_link ?? '' }}</a>&nbsp;&nbsp;
                    <br>
                    <a href="#" target="_blank">{{ $data['dataUser']->site_link ?? '' }}</a>
                </div>
                <?php
         }
         ?>
            </div>
        </div>
        <div class="section full profile-nav">
            <div class="wide-block transparent p-0">
                <ul class="nav nav-tabs lined iconed" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#settings" role="tab">
                            <ion-icon name="settings-outline"></ion-icon>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#friends" role="tab">
                            <ion-icon name="people-outline"></ion-icon>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#bookmarks" role="tab">
                            <ion-icon name="heart"></ion-icon>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#booking" role="tab">
                            <ion-icon name="grid-outline"></ion-icon>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#reviews" role="tab">
                            <ion-icon name="star"></ion-icon>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- tab content -->
        <div class="section full mb-2">
            <div class="tab-content profile-tabs">
                <!-- * Setting -->
                <div class="tab-pane fade show active" id="settings" role="tabpanel">
                    <ul class="listview image-listview flush transparent pt-1">
                        <li>
                            <a href="#" class="item" data-bs-toggle="modal" data-bs-target="#PanelRight">
                                <img src="{{ url('pwa') }}/assets/img/sample/icons/notification.png" alt="image"
                                    class="image">
                                <div class="in">
                                    <div>
                                        Notifications
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="item" data-bs-toggle="modal" data-bs-target="#actionCardRemoveForm1">
                                <img src="{{ url('pwa') }}/assets/img/sample/icons/credit-card.png" alt="image"
                                    class="image">
                                <div class="in">
                                    <div>
                                        Payment Methods
                                    </div>
                                </div>
                            </a>
                        </li>
                        {{-- <div class="mb-2"></div> --}}
                        <li>
                            <a href="#" class="item" data-bs-toggle="modal" data-bs-target="#PanelRight2">
                                <img src="{{ url('pwa') }}/assets/img/sample/icons/support.png" alt="image"
                                    class="image">
                                <div class="in">
                                    <div class="tab-title">
                                        Help and Support
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="item" data-bs-toggle="modal" data-bs-target="#PanelRight3">
                                <img src="{{ url('pwa') }}/assets/img/sample/icons/privacy.png" alt="image"
                                    class="image">
                                <div class="in">
                                    <div class="tab-title">
                                        Terms and Privacy
                                    </div>
                                </div>
                            </a>
                        </li>
                        {{-- <div class="mb-2"></div> --}}
                        <li class="logout-link">
                            <a href="{{ url('m/logout') }}" class="item">
                                <img src="{{ url('pwa') }}/assets/img/sample/icons/logout.png" alt="image"
                                    class="image">
                                <div class="in">
                                    <div>
                                        Log Out
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- * setting -->
                <!-- * Personalinfo -->
                <div class="tab-pane fade" id="friends" role="tabpanel">
                    <div class="sub-tab-title">
                        <h3>Personal Info</h3>
                        <div class="right tab-edit me-2">
                            <a href="#" class="headerButton" data-bs-toggle="modal"
                                data-bs-target="#actionCardRemoveForm">
                                <ion-icon name="create-outline"></ion-icon>
                            </a>
                        </div>
                    </div>
                    <ul class="listview image-listview flush transparent pt-1">
                        <li>
                            <a href="#" class="item rm-arrow">
                                <ul>
                                    <!--   <li><span class="col-item col-4">Name</span><span class="col-8">Chelsea Koy</span></li>
                                                       <li><span class="col-item col-4">Company</span><span class="col-8">Schoop Designs Inc.</span></li>
                                                       <li><span class="col-item col-4">Mobile</span><span class="col-8">(323) 456 2123</span></li>
                                                       <li><span class="col-item col-4">Office </span><span class="col-8">(218) 567 7754</span></li>
                                                       <li><span class="col-item col-4">Email </span><span class="col-8">me@myoffice.ca</span></li>
                                                       <li><span class="col-item col-4">Home Base</span><span class="col-8">Toronto, ON</span></li>    -->
                                    <li><span class="col-item col-4">Name</span><span
                                            class="col-8">{{ $data['dataUser']->user_name ?? 'null' }}</span></li>
                                    <li><span class="col-item col-4">Email </span><span
                                            class="col-8">{{ $data['dataUser']->email ?? 'null' }}</span></li>
                                    <li><span class="col-item col-4">First Name</span><span
                                            class="col-8">{{ $data['dataUser']->first_name ?? 'null' }}</span></li>
                                    <li><span class="col-item col-4">Last Name</span><span
                                            class="col-8">{{ $data['dataUser']->last_name ?? 'null' }}</span></li>
                                    <li><span class="col-item col-4">Phone Number</span><span
                                            class="col-8">{{ $data['dataUser']->phone ?? 'null' }}</span></li>
                                    <li><span class="col-item col-4">Birthday</span><span
                                            class="col-8">{{ $data['dataUser']->birthday ?? 'null' }}</span></li>
                                    <li><span class="col-item col-4">About Yourself</span><span
                                            class="col-8">{{ $data['dataUser']->bio ?? 'null' }}</span></li>
                                </ul>
                            </a>
                        </li>
                        <div class="sub-tab-title">
                            <h3>Networking and Business</h3>
                            <div class="right tab-edit me-2">
                                <a href="#" class="headerButton" data-bs-toggle="modal"
                                    data-bs-target="#actionNetworkForm">
                                    <ion-icon name="create-outline"></ion-icon>
                                </a>
                            </div>
                        </div>
                        <li>
                            <a href="#" class="item rm-arrow">
                                <img src="{{ url('pwa') }}/assets/img/sample/icons/credit-card.png" alt="image"
                                    class="image">
                                <div class="in">
                                    <div>
                                        Backpocket
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="item rm-arrow">
                                <img src="{{ url('pwa') }}/assets/img/sample/icons/linkedin.png" alt="image"
                                    class="image">
                                <div class="in">
                                    <div>
                                        LinkedIn
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="item rm-arrow">
                                <img src="{{ url('pwa') }}/assets/img/sample/icons/credit-card.png" alt="image"
                                    class="image">
                                <div class="in">
                                    <div>
                                        Bark
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="item rm-arrow">
                                <img src="{{ url('pwa') }}/assets/img/sample/icons/meetup.png" alt="image"
                                    class="image">
                                <div class="in">
                                    <div>
                                        Meetup
                                    </div>
                                </div>
                            </a>
                        </li>
                        <div class="sub-tab-title">
                            <h3>Social</h3>
                            <div class="right tab-edit me-2">
                                <a href="#" class="headerButton" data-bs-toggle="modal"
                                    data-bs-target="#actionSocialForm">
                                    <ion-icon name="create-outline"></ion-icon>
                                </a>
                            </div>
                        </div>
                        <li>
                            <?php
                            if ($data['dataUser']->instagram_link && $data['dataUser']->instagram_link != null) {
                                $insta_redirection = 'http://www.instagram.com/' . $data['dataUser']->instagram_link;
                            } else {
                                $insta_redirection = '#';
                            }
                            ?>
                            <a href="{{ $insta_redirection }}" target="_blank" class="item rm-arrow">
                                <img src="{{ url('pwa') }}/assets/img/sample/icons/instagram.png" alt="image"
                                    class="image">
                                <div class="in">
                                    <div>
                                        {{ $data['dataUser']->instagram_link ?? 'Instagram' }}
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <?php
                            if ($data['dataUser']->facebook_personal && $data['dataUser']->facebook_personal != null) {
                                $fb_redirection = 'http://www.facebook.com/' . $data['dataUser']->facebook_personal;
                            } else {
                                $fb_redirection = '#';
                            }
                            ?>
                            <a href="{{ $fb_redirection }}" target="_blank" class="item rm-arrow">
                                <img src="{{ url('pwa') }}/assets/img/sample/icons/facebook.png" alt="image"
                                    class="image">
                                <div class="in">
                                    <div>
                                        {{ $data['dataUser']->facebook_personal ?? 'Facebook' }}
                                    </div>
                                </div>
                            </a>
                        </li>
                        <!-- <li>
                                                    <a href="#" class="item rm-arrow">
                                                        <img src="{{ url('pwa') }}/assets/img/sample/icons/google-plus.png" alt="image"
                                                            class="image">
                                                        <div class="in">
                                                            <div>
                                                                Google Plus
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li> -->
                    </ul>
                    <!-- <div class="text-center mt-2 mb-2 p-3">
                                           <button class="btn btn-primary btn-block btn-lg" type="button">
                                               MAKE CHANGES
                                           </button>
                                           </div>  -->
                </div>
                <!-- * end personalinfo -->
                <!--  bookmarks -->
                <div class="tab-pane fade spaces" id="bookmarks" role="tabpanel">
                    <div class="sub-tab-title">
                        <h3>Favourite Spaces</h3>
                    </div>
                    <div class="transactions p-2">
                        <!-- item -->
                        @foreach ($fav_list as $row)
                            @include('pwa.common.single_space_list_wise', ['row' => $row->space])
                        @endforeach
                        <!-- item -->
                        <!-- <div class="section mt-2 mb-2 text-center">
                                              <a href="#" id="more-envlop" class="f-12 btn btn-more btn-lg rounded">Load More Items</a>
                                              </div> -->
                        <!-- <div class="text-center mt-2 mb-2 p-3">
                                              <button class="btn btn-primary btn-block btn-lg" type="button">
                                                  MAKE CHANGES
                                              </button>
                                              </div>  -->
                    </div>
                </div>
                <!-- * bookmarks -->
                <!--  booking -->
                <div class="tab-pane fade booking" id="booking" role="tabpanel">
                    <div class="sub-tab-title">
                        <h3>Booking History</h3>
                    </div>
                    <div class="transactions p-2">


                        @foreach ($booking_list as $row)
                            @include('pwa.common.single_booking_list_wise', ['row' => $row])
                        @endforeach


                        <!-- <div class="section mt-2 mb-2 text-center">
                                              <a href="#" id="more" class="f-12 btn btn-more btn-lg rounded">Load More Items</a>
                                           </div>
                                           <div class="text-center mt-2 mb-2 p-3">
                                              <button class="btn btn-primary btn-block btn-lg" type="button">
                                              MAKE CHANGES
                                              </button>
                                           </div> -->
                    </div>
                </div>
                <!-- * booking -->
                <div class="tab-pane fade booking" id="reviews" role="tabpanel">
                    <div class="sub-tab-title">
                        <h3>Reviews</h3>
                    </div>
                    <div class="transactions p-2">


                        @foreach ($totalReviews as $review)
                        <a href="{{url('m/booking-details').'/'.$review->reference_id}}" class="reviewed-card">
                            <div class="head">
                                <div class="left">
                                    <div class="reviewer-image"
                                        style="background-image: url('{{ $review->author->getAvatarUrl() }}')">
                                    </div>
                                    <div class="head">
                                        <h4><?= trim($review->title) ?></h4>
                                        <div class="body">
                                            <p><?= trim($review->content) ?></p>
                                        </div>
                                        <p class="time-name">
                                            <?=$review->author->first_name?> <?=$review->author->last_name?>, 
                                            <?= date('d M, Y', strtotime($review->created_at)) ?> 
                                        </p>
                                    </div>
                                </div>
                                <div class="right">
                                    <div class="average-rate">
                                        <h4><?= number_format($review->rate_number, 1) ?></h4>
                                        <p>User Rating</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach


                        <!-- <div class="section mt-2 mb-2 text-center">
                                              <a href="#" id="more" class="f-12 btn btn-more btn-lg rounded">Load More Items</a>
                                           </div>
                                           <div class="text-center mt-2 mb-2 p-3">
                                              <button class="btn btn-primary btn-block btn-lg" type="button">
                                              MAKE CHANGES
                                              </button>
                                           </div> -->
                    </div>
                </div>
            </div>
        </div>
        <!-- * tab content -->
        <!-- Form Action Sheet -->
        <form enctype="multipart/form-data" action="{{ route('user.profile.update') }}" method="post"
            class="input-has-icon">
            @csrf
            <div class="modal fade action-sheet profile-sheet" id="actionCardRemoveForm" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Personal Info</h5>
                        </div>
                        <div class="modal-body">
                            <div class="action-sheet-content">
                                <form>
                                    <div class="form-group boxed mb-1">
                                        <div class="input-wrapper">
                                            <label class="label" for="text4b">Name</label>
                                            <input type="text" class="form-control" name="user_name"
                                                value="{{ old('user_name', $data['dataUser']->user_name) }}"
                                                placeholder="{{ __('User name') }}">
                                        </div>
                                    </div>
                                    <div class="form-group boxed mb-1">
                                        <div class="input-wrapper">
                                            <label class="label" for="text4b">Email Address</label>
                                            <input type="email" class="form-control" name="email"
                                                value="{{ old('email', $data['dataUser']->email) }}"
                                                placeholder="{{ __('E-mail') }}">
                                        </div>
                                    </div>
                                    <div class="form-group boxed mb-1">
                                        <div class="input-wrapper">
                                            <label class="label" for="text4b">First Name</label>
                                            <input type="text" class="form-control" name="first_name"
                                                value="{{ old('first_name', $data['dataUser']->first_name) }}"
                                                placeholder="{{ __('First name') }}">
                                        </div>
                                    </div>
                                    <div class="form-group boxed mb-1">
                                        <div class="input-wrapper">
                                            <label class="label" for="text4b">Last Name</label>
                                            <input type="text" class="form-control" name="last_name"
                                                value="{{ old('last_name', $data['dataUser']->last_name) }}"
                                                placeholder="{{ __('Last name') }}">
                                        </div>
                                    </div>
                                    <div class="form-group boxed mb-1">
                                        <div class="input-wrapper">
                                            <label class="label" for="text4b">Phone Number</label>
                                            <input type="tel" class="form-control"
                                                value="{{ old('phone', $data['dataUser']->phone) }}" name="phone"
                                                placeholder="{{ __('Phone Number') }}">
                                        </div>
                                    </div>
                                    <div class="form-group boxed mb-1">
                                        <div class="input-wrapper">
                                            <label class="label" for="text4b">Birthday</label>
                                            <!-- <input type="tel" class="form-control" value="{{ old('phone', $data['dataUser']->phone) }}" name="phone" placeholder="{{ __('Phone Number') }}"> -->
                                            <input type="text"
                                                value="{{ old('birthday', $data['dataUser']->birthday ? display_date($data['dataUser']->birthday) : '') }}"
                                                name="birthday" placeholder="{{ __('Birthday') }}"
                                                class="form-control date-picker">
                                        </div>
                                    </div>
                                    <div class="form-group boxed mb-1">
                                        <div class="input-wrapper">
                                            <label class="label" for="text4b">About Yourself</label>
                                            <textarea name="bio" rows="5" class="form-control">{{ old('bio', $data['dataUser']->bio) }}</textarea>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group card-btn-set text-center">
                                  <button type="button" class="btn btn-lg btn-primary me-1 mb-1"
                                      data-bs-dismiss="modal">Save Changes</button>
                                  </div>-->
                                    <div class="col-md-12">
                                        <hr>
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i>
                                            {{ __('Save Changes') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- * Form Action Sheet -->
        <!-- Form Action Sheet -->
        <div class="modal fade action-sheet profile-sheet" id="actionNetworkForm" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Networking and Business</h5>
                    </div>
                    <div class="modal-body">
                        <div class="action-sheet-content">
                            <form method="post" action="{{ route('pwa.updateNetworkingInfo') }}">
                                {{ csrf_field() }}
                                <div class="form-group boxed mb-1 row">
                                    <div class="col-9">
                                        <div class="input-wrapper">
                                            <label class="label" for="text4b">Myoffice User Email</label>
                                            <input type="email" name="myoffice_user_email"
                                                value="{{ $data['dataUser']->myoffice_user_email ?? '' }}"
                                                class="form-control" placeholder="Enter myoffice user email">
                                        </div>
                                    </div>
                                    <div class="col-3 input-icon mt-4">
                                        <span class="help-icon">
                                            <ion-icon name="help-circle-outline"></ion-icon>
                                        </span>
                                        <span class="check-icon">
                                            <ion-icon name="checkmark-circle-outline"></ion-icon>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group boxed mb-1 row">
                                    <div class="input-wrapper">
                                        <label class="label" for="text4b">LinkedIn</label>
                                        <input type="text" name="linkedin_link" class="form-control"
                                            value="{{ $data['dataUser']->linkedin_link ?? '' }}"
                                            placeholder="Enter Linkedin">
                                    </div>
                                </div>
                                <div class="form-group boxed mb-1 row">
                                    <div class="col-11">
                                        <div class="input-wrapper">
                                            <label class="label" for="text4b">Facebook Page</label>
                                            <input type="text" class="form-control" name="facebook_page"
                                                value="{{ $data['dataUser']->facebook_page ?? '' }}"
                                                placeholder="Enter facebook page">
                                        </div>
                                    </div>
                                    <div class="col-1 input-icon mt-4">
                                        <span class="help-icon">
                                            <ion-icon name="help-circle-outline"></ion-icon>
                                        </span>
                                    </div>
                                    <p class="fontsize-small">This is for your public FB page. Only enter the page url
                                        suffix.<br>
                                        For Example : http://facebook.com/myurl, Only enter 'myurl'
                                    </p>
                                </div>
                                <div class="form-group boxed mb-1 row">
                                    <div class="input-wrapper">
                                        <label class="label" for="text4b">Meetup Account</label>
                                        <input type="text" class="form-control" name="meetup_account"
                                            value="{{ $data['dataUser']->meetup_account ?? '' }}"
                                            placeholder="Enter Meetup Acoount">
                                    </div>
                                </div>
                                <div class="form-group card-btn-set text-center">
                                    <!-- <button type="button" class="btn btn-lg btn-primary me-1 mb-1" data-bs-dismiss="modal">Save Changes</button> -->
                                    <input type="submit" class="btn btn-lg btn-primary me-1 mb-1" value="Save Changes">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- * Form Action Sheet -->
        <!-- Form Action Sheet -->
        <div class="modal fade action-sheet profile-sheet" id="actionSocialForm" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Social</h5>
                    </div>
                    <div class="modal-body">
                        <div class="action-sheet-content">
                            <form method="post" action="{{ route('pwa.updateSocialInfo') }}">
                                {{ csrf_field() }}
                                <div class="form-group boxed mb-1">
                                    <div class="input-wrapper">
                                        <label class="label" for="text4b">Instagram</label>
                                        <input type="text" value="{{ $data['dataUser']->instagram_link ?? '' }}"
                                            class="form-control" name="instagram_link" placeholder="Enter Instagram">
                                    </div>
                                </div>
                                <div class="form-group boxed mb-1">
                                    <div class="input-wrapper">
                                        <label class="label" for="text4b">Facebook Personal</label>
                                        <input type="text" class="form-control"
                                            value="{{ $data['dataUser']->facebook_personal ?? '' }}"
                                            name="facebook_personal">
                                    </div>
                                </div>
                                <!-- <div class="form-group boxed mb-1">
                                                            <div class="input-wrapper">
                                                                <label class="label" for="text4b">Google plus</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $data['dataUser']->google_plus ?? '' }}" name="google_plus">
                                                            </div>
                                                        </div> -->
                                <div class="form-group card-btn-set text-center mb-5">
                                    <!-- <button type="button" class="btn btn-lg btn-primary me-1 mb-1" data-bs-dismiss="modal">Save Changes</button> -->
                                    <input type="submit" class="btn btn-lg btn-primary me-1 mb-1" value="Save Changes"
                                        name="">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- * Form Action Sheet -->
        <!-- Form Action Sheet -->
        <div class="modal fade action-sheet profile-sheet" id="actionInfoForm" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Profile Information</h5>
                    </div>
                    <div class="modal-body">
                        <div class="action-sheet-content">
                            <form method="post" action="{{ route('pwa.updateProfileInfo') }}">
                                {{ csrf_field() }}
                                <div class="form-group boxed mb-1">
                                    <div class="input-wrapper">
                                        <label class="label" for="text4b">Profile Info</label>
                                        <textarea type="text" name="profile_info" rows="10" class="form-control"
                                            placeholder="What makes you interesting? Tell us why you’re unique!">{{ $data['dataUser']->profile_info ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group boxed mb-1">
                                    <div class="input-wrapper">
                                        <label class="label" for="text4b">Instagram</label>
                                        <input type="text" name="instagram_link" class="form-control"
                                            value="{{ $data['dataUser']->instagram_link ?? '' }}">
                                    </div>
                                </div>
                                <div class="form-group boxed mb-1">
                                    <div class="input-wrapper">
                                        <label class="label" for="text4b">My Web</label>
                                        <input type="text" name="site_link" class="form-control"
                                            value="{{ $data['dataUser']->site_link ?? '' }}">
                                    </div>
                                </div>
                                <div class="form-group card-btn-set text-center mb-5">
                                    <!-- <button type="button" class="btn btn-lg btn-primary me-1 mb-1" data-bs-dismiss="modal">SAVE CHANGES</button> -->
                                    <input type="submit" class="btn btn-lg btn-primary me-1 mb-1" value="SAVE CHANGES">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- * Form Action Sheet -->
        <!-- Panel Right -->
        <div class="modal fade panelbox panelbox-right payment-delails" id="PanelRight" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Notifications</h4>
                        <a href="#" class="ms-1 close" data-bs-dismiss="modal"><i
                                class="icon ion-ios-close-circle"></i></a>
                    </div>
                    <div class="modal-body">
                        <p>Myoffice can notify you of any chnages to your account,special offers from your favourite
                            stores,or upcoming events that may be of
                            interest to you.
                        </p>
                        <form method="post" action="{{ route('pwa.updateNotificationProfileDetails') }}">
                            {{ csrf_field() }}
                            <div class="form-group boxed mb-1">
                                <div class="input-wrapper">
                                    <label class="label" for="text4b">Notify me via</label>
                                    <select class="form-select" aria-label="Default select example">
                                        <option selected>Text</option>
                                        <option value="1">Email</option>
                                        <option value="2">WhatsApp</option>
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="form-group boxed mb-1">
                                                        <div class="input-wrapper">
                                                            <label class="label" for="text4b">Phone Number for text messaging</label>
                                                            <input type="tel" class="form-control" value="{{ $data['dataUser']->phone ?? '' }}">
                                                        </div>
                                                    </div> -->
                            <div class="form-group boxed mb-1">
                                <div class="input-wrapper">
                                    <label class="label" for="text4b">Email Address </label>
                                    <input type="email" class="form-control" name="email"
                                        value="{{ $data['dataUser']->email ?? '' }}">
                                </div>
                            </div>
                            <div class="form-group boxed mb-1">
                                <div class="input-wrapper">
                                    <label class="label" for="text4b">WhatsApp Number</label>
                                    <input type="tel" class="form-control" name="phone"
                                        value="{{ $data['dataUser']->phone ?? '' }}">
                                </div>
                            </div>
                            <h3>Select Notifications</h3>
                            <div class="form-group mb-5">
                                <ul class="listview simple-listview transparent flush">
                                    <li>
                                        <div>Account Changes</div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="SwitchCheckDefault4">
                                            <label class="form-check-label" for="SwitchCheckDefault4"></label>
                                        </div>
                                    </li>
                                    <li>
                                        <div>Offers from My Stores</div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="SwitchCheckDefault5">
                                            <label class="form-check-label" for="SwitchCheckDefault5"></label>
                                        </div>
                                    </li>
                                    <li>
                                        <div>Special Events</div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="SwitchCheckDefault6">
                                            <label class="form-check-label" for="SwitchCheckDefault6"></label>
                                        </div>
                                    </li>
                                    <li>
                                        <div>Budget Warnings</div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="SwitchCheckDefault7">
                                            <label class="form-check-label" for="SwitchCheckDefault7"></label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="form-group card-btn-set text-center mb-5">
                                <button type="submit" class="btn btn-lg btn-primary me-1 mb-1"
                                    data-bs-dismiss="modal">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- * Panel Right -->
        <!-- Panel Right -->
        <div class="modal fade panelbox panelbox-right payment-delails" id="PanelRight2" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Help and Support</h4>
                        <a href="#" class="ms-1 close" data-bs-dismiss="modal"><i
                                class="icon ion-ios-close-circle"></i></a>
                    </div>
                    <div class="modal-body">
                        <p>Our Client Services Team will always be here to support you with every need. Please fill out the
                            form below,and we will reach out to you as soon as possible.
                        </p>
                        <form class="supportAjaxForm" method="post" action="{{ route('pwa.supportSubmit') }}">
                            {{ csrf_field() }}
                            <div class="form-fields-box">
                                <div class="form-group boxed mb-1">
                                    <div class="input-wrapper">
                                        <label class="label" for="text4b">Subject</label>
                                        <input id="supportSubject" name="subject" type="text" class="form-control"
                                            placeholder="Enter Subject">
                                    </div>
                                </div>
                                <div class="form-group boxed mb-1">
                                    <div class="input-wrapper">
                                        <label class="label" for="text4b">What is the Issue?</label>
                                        <textarea id="supportIssue" type="text" rows="10" class="form-control" name="issue"
                                            placeholder="Please describe your concern in detail" required=""></textarea>
                                    </div>
                                </div>
                                <div class="form-group card-btn-set text-center mt-3 mb-5">
                                    <button type="submit" name="submit"
                                        class="btn btn-lg btn-primary me-1 mb-1">Submit</button>
                                </div>
                            </div>
                            <div class="form-fields-msg">
                                <div class="successfull-msg text-center mt-5 mb-5">
                                    <h1>Thank You!</h1>
                                    <p>We have created a Support Ticket <br>
                                        regarding your issue and our team will
                                        review the case,and provide you with a
                                        response within the next 48 hours.
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- * Panel Right -->
        <!-- Panel Right -->
        <div class="modal fade panelbox panelbox-right payment-delails" id="PanelRight3" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Terms and Privacy</h4>
                        <a href="#" class="ms-1 close" data-bs-dismiss="modal"><i
                                class="icon ion-ios-close-circle"></i></a>
                    </div>
                    <div class="modal-body mb-3">
                        <p>BackPocket is an online repository for receipts,a payment management application,and a networking
                            and social
                            engagement company.
                        </p>
                        <p>Our Terms of Service,and our Privacy Policy is subject to change at any time,without any prior
                            notice.</p>
                        <p>Below is a brief summary of these items. To view our full documents,please visit our website at
                            <a href="http://www.backpocket.ca/legals" target="_blank">http://www.backpocket.ca/legals</a>
                        </p>
                        <h2 class="mt-3">Terms of Service</h2>
                        <p>These terms and conditions govern your use of this website and
                            mobile application;by using this website or application,you
                            accept these terms and conditions in full and without
                            reservation.If you disagree with these terms and conditions or
                            any part of these terms and conditions,you must not use this
                            website or application.
                        </p>
                        <h2 class="mt-3">Privacy Policy </h2>
                        <p>We take our clients' financial privacy very seriously. When using
                            our website and application,we restrict access to nonpublic
                            personal information about you to those employees who need
                            to know that information to provide products or services to you.
                            We maintain physical,electronic,and procedural safeguards that comply with federal regulations
                            to
                            guard you nonpublic personal information.
                        </p>
                        <p>We collect nonpublic information about you from the following
                            sources : (i) information we receive from you on applications or
                            other forms;(ii) information about your transaction with us,our
                            affiliares,or others;and (iii) information we receive from a consumer reporting agency.
                        </p>
                        <p>We do not disclose any nonpublic information about our
                            customers or former customers to any third party,except as
                            permitted and required by law.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- * Panel Right -->
        <!-- Form Action Sheet -->
        <div class="modal fade action-sheet" id="actionCardRemoveForm1" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Payment Methods</h5>
                    </div>
                    <div class="modal-body">
                        <div class="action-sheet-content">
                            <form>
                                <div class="form-group boxed mb-1">
                                    <div class="input-wrapper">
                                        <label class="label mt-1 mb-1" for="text4b">Choose Primary Method</label>
                                        <select class="form-select" aria-label="Default select example">
                                            <option value="backpocket" selected>BackPocket Credits</option>
                                            <option value="credit">Credit Card</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="payment-section backpocket">
                                    <div class="form-group boxed mb-1">
                                        <div class="input-wrapper">
                                            <label class="label" for="text4b">Backpocket Email Id</label>
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group boxed mb-1">
                                        <div class="input-wrapper">
                                            <label class="label" for="text4b">Password</label>
                                            <input type="password" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <ul class="listview simple-listview transparent flush">
                                            <li>
                                                <div>Set Default</div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="SwitchCheckDefault1">
                                                    <label class="form-check-label" for="SwitchCheckDefault1"></label>
                                                </div>
                                            </li>
                                            <li></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="payment-section credit">
                                    <div class="form-group boxed mb-1">
                                        <div class="input-wrapper">
                                            <label class="label" for="text4b">Cardholder Name</label>
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group boxed mb-1">
                                        <div class="input-wrapper">
                                            <label class="label" for="text4b">Card Number</label>
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group boxed mb-1">
                                        <div class="input-group">
                                            <div class="input-wrapper date-card">
                                                <label class="label mb-1" for="text4b">Expiry</label>
                                                <input type="text" class="form-control" name="date"
                                                    placeholder="MM/YY" />
                                            </div>
                                            <div class="input-wrapper w-50">
                                                <label class="label mb-1" for="text4b">CCV</label>
                                                <input type="number" class="form-control" name="ccv"
                                                    placeholder="CCV" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group boxed mb-1">
                                        <ul class="listview simple-listview transparent flush">
                                            <li>
                                                <div>Set Default</div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="SwitchCheckDefault3">
                                                    <label class="form-check-label" for="SwitchCheckDefault3"></label>
                                                </div>
                                            </li>
                                            <li></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-group card-btn-set text-center">
                                    <button type="button" class="btn btn-lg btn-primary me-1 mb-1"
                                        data-bs-dismiss="modal">SAVE CHANGES</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- * Form Action Sheet -->
    </div>
    <!-- * App Capsule -->
    <!-- * App Capsule -->
    <div class="appBottomMenu">
        <a href="{{ route('pwa.get.home') }}" class="item active">
            <div class="col">
                <ion-icon name="home-outline" role="img" class="md hydrated" aria-label="home outline"></ion-icon>
                <strong>Home</strong>
            </div>
        </a>
        <a href="#" class="item">
            <div class="col">
                <ion-icon name="document-text-outline" role="img" class="md hydrated"
                    aria-label="document text outline"></ion-icon>
                <strong>Bookings</strong>
            </div>
        </a>
        <a href="search.html" class="item">
            <div class="action-button large blk">
                <!-- <ion-icon class="blk" name="share-social-outline"></ion-icon>       -->
                <img class="text-center pt-04" src="assets/img/share.png" alt="" width="40">
            </div>
            <strong class="sear">Search</strong>
        </a>
        <a href="#" class="item">
            <div class="col">
                <ion-icon name="mail-outline"></ion-icon>
                <strong>Inbox</strong>
            </div>
        </a>
        <a href="#" class="item">
            <div class="col">
                <ion-icon name="person-circle-outline"></ion-icon>
                <strong>Profile</strong>
            </div>
        </a>
    </div>
    </div>
    <!-- * App Sidebar -->
@endsection
<!-- ============== Js Files ==============  -->
<!-- Bootstrap -->
<script src="assets/js/lib/bootstrap.min.js"></script>
<!-- Ionicons -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<!-- Splide -->
<script src="assets/js/plugins/splide/splide.min.js"></script>
<!-- ProgressBar js -->
<script src="assets/js/plugins/progressbar-js/progressbar.min.js"></script>
<!-- Base Js File -->
<script src="assets/js/base.js?v=1"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
<script id="rendered-js">
    $(document).ready(function() {
        $('.enve-item').hide();
        $('.enve-item').each(function(index, value) {
            if (index < 5) {
                $(this).show();
            }
        });
        if ($('.enve-item:hidden').length) {
            $('#more-search').show();
        }
        if (!$('.enve-item:hidden').length) {
            $('#more-envlop').hide();
        }

    });

    $('#more-envlop').on('click', function() {
        $('.enve-item:hidden').each(function(index, value) {
            if (index < 5) {
                $(this).show();
            }
        });
        if (!$('.enve-item:hidden').length) {
            $('#more-envlop').hide();
        }
    });
    //# sourceURL=pen.js
</script>
<script id="rendered-js">
    $(document).ready(function() {
        $('.booking-item').hide();
        $('.booking-item').each(function(index, value) {
            if (index < 5) {
                $(this).show();
            }
        });
        if ($('.booking-item:hidden').length) {
            $('#more').show();
        }
        if (!$('.booking-item:hidden').length) {
            $('#more').hide();
        }

    });

    $('#more').on('click', function() {
        $('.booking-item:hidden').each(function(index, value) {
            if (index < 5) {
                $(this).show();
            }
        });
        if (!$('.booking-item:hidden').length) {
            $('#more').hide();
        }
    });
    //# sourceURL=pen.js
</script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {
        $("select").change(function() {
            $(this).find("option:selected").each(function() {
                var optionValue = $(this).attr("value");
                if (optionValue) {
                    $(".payment-section").not("." + optionValue).hide();
                    $("." + optionValue).show();
                } else {
                    $(".payment-section").hide();
                }
            });
        }).change();
    });

    $(document).on("click", ".open-profile-tabs", function(e) {
        e.preventDefault();
        window.location.replace($(this).attr("href"));
    });

    function activeAsPerHash() {
        setTimeout((e) => {
            let hash = window.location.hash;
            if (hash !== "") {
                $('a.nav-link[href="' + hash + '"]')[0].click();
            }
        }, 0);
    }
    $(window).on('hashchange', function(e) {
        activeAsPerHash();
        setTimeout(() => {
            activeAsPerHash();
        }, 1000);
    });
    activeAsPerHash();
    setTimeout(() => {
        activeAsPerHash();
    }, 1000);
</script>
</body>

</html>
