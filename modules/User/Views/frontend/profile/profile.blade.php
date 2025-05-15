@extends('layouts.common_home')
@section('content')
    <div class="page-profile-content page-template-content">
        <div class="container">
            <div class="">
                <div class="row">
                    <div class="col-md-3">
                        @include('User::frontend.profile.sidebar')
                    </div>
                    <div class="col-md-9">
                        <div class="profile-info-box">
                            <h3 class="profile-section-title"><?= $isVendor ? 'About Us' : 'About Me' ?></h3>
                            <div class="profile-bio">{!! $user->bio !!}</div>
                        </div>
                        <?php
                    if($isVendor){
                        ?>
                        <div class="profile-info-box">
                            <h3 class="profile-section-title">Join Us</h3>
                            <div class="profile-bio">Whether you're looking for a small cubicle workspace or a dedicated
                                suite
                                for 4 staff members, we have the space to accommodate your needs. Checkout out our available
                                listings below. If you can't find exactly what you need, Contact Us and we will create a
                                trailored solution for your business.</div>
                        </div>
                        <div class="profile-info-box">
                            <h3 class="profile-section-title">Listings</h3>
                            <div class="profile-bio listings-box">
                                <div class="bravo_wrap">
                                    <div class="bravo_search_space">
                                        <div class="bravo-list-item">
                                            <div class="listing-items list-item row">
                                                <?php
            if ($listings != null) {
                ?>
                                                @foreach ($listings as $listing)
                                                    <?php
                                                    $row = $listing;
                                                    ?>
                                                    <div class="col-md-4 col-12 col-xs-12">
                                                        @include('Space::frontend.layouts.search.loop-gird')
                                                    </div>
                                                @endforeach
                                                <?php
            }
            ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bravo-pagination-2" style="padding-bottom: 36px">
                                    <div class="search-list">{{ $listings->total() }} Listings | Page
                                        {{ $listings->currentPage() }}
                                        of {{ $listings->lastPage() }}</div>
                                    <div class="listing-paginations">
                                        {{ $listings->links() }}
                                    </div>
                                </div>

                            </div>
                        </div>
                        <?php
                    }else{
                        ?>
                        <div class="profile-info-box">
                            <h3 class="profile-section-title">Let's Network</h3>
                            <div class="profile-bio">
                                <ul class="social-links">
                                    <?php
                                    $facebookLink = $user->facebook_link;
                                    if ($facebookLink != null) {
                                        if (substr($facebookLink, 0, 4) !== 'http') {
                                            $facebookLink = "https://www.facebook.com/".$facebookLink;
                                        }
                                        ?>
                                    <li>
                                        <a href="<?= $facebookLink ?>"><i class="fa fa-facebook"></i></a>
                                    </li>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    $instagramLink = $user->instagram_link;
                                    if ($instagramLink != null) {
                                        if (substr($instagramLink, 0, 4) !== 'http') {
                                            $instagramLink = "https://www.instagram.com/".$instagramLink;
                                        }
                                        ?>
                                    <li>
                                        <a href="<?= $instagramLink ?>"><i class="fa fa-instagram"></i></a>
                                    </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <?php
                        if($user->site_link!=null){
                            ?>
                        <div class="profile-info-box">
                            <h3 class="profile-section-title">Website</h3>
                            <div class="profile-bio">
                                <a style="color: #333;" href="<?= $user->site_link ?>"><?= $user->site_link ?></a>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                        <?php
                    }
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-myoffice fade" tabindex="-1" role="dialog" id="contactMEAsModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content ">
                <form action="{{ route('user.profile.publicProfileContactSubmit', $user->id) }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 style="font-family:Montserrat;font-size:16pt;font-weight:900;"
                            class="modal-title text-center w-100"><img width="30" height="30"
                                src="<?php echo url('/icon/mo_calendar.svg'); ?>" />&nbsp;&nbsp;<?= $isVendor ? 'CONTACT HOST' : 'CONTACT ME' ?>
                            <hr>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Name</label>
                            <input class="form-control" placeholder="Enter Name" required type="text" name="name" value="">
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input class="form-control" placeholder="Enter Email" required type="email" name="email" value="">
                        </div>
                        <div class="form-group">
                            <label for="">Subject</label>
                            <input class="form-control" placeholder="Enter Subject" required type="text" name="subject" value="">
                        </div>
                        <div class="form-group" v-if="!enquiry_is_submit">
                            <label for="">Message</label>
                            <textarea required rows="6" id="notesData" class="form-control" placeholder="{{ __('Enter Message') }}"
                                name="message"></textarea>
                        </div>
                        <div class="message_box"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" style="margin-top:-1px;"
                            class="btn btn-primary btn-su">{{ __('Send Message') }}
                            <i class="fa icon-loading fa-spinner fa-spin fa-fw" style="display: none"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
