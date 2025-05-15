<?php
$stats = $user->stats();
?>
<div class="profile-summary mb-2">
    <div class="profile-avatar">
        @if ($avatar = $user->getAvatarUrl())
            <div class="avatar-img avatar-cover" style="background-image: url('{{ $user->getAvatarUrl() }}')">
            </div>
        @else
            <span class="avatar-text">{{ $user->getDisplayName()[0] }}</span>
        @endif
        @if ($user->is_verified)
            <span class="badge">
                <img data-toggle="tooltip" data-placement="top" src="{{ asset('icon/ico-vefified-1.svg') }}"
                title="{{ __('Verified') }}" alt="ico-vefified-1">
            </span>
        @else
            {{-- <img data-toggle="tooltip" data-placement="top" src="{{ asset('icon/ico-not-vefified-1.svg') }}"
                title="{{ __('Not verified') }}" alt="ico-vefified-1"> --}}
        @endif
    </div>
    <h3 class="display-name">
        <span>{{ $user->getDisplayName() }}</span>
    </h3>

    @if (!$isVendor)
        {{-- <p class="profile-since">{{ __('Member Since :time', ['time' => date('M Y', strtotime($user->created_at))]) }}
        </p> --}}
    @endif

    <hr>

    <div class="rating-display-area">
        <div class="average-rate">
            <h4><?= number_format($user->review_score, 1) ?></h4>
            <p>Rating</p>
        </div>
    </div>

    <?php
    $badge = $user->getBadgeInfo();
    ?>
    @if ($badge != null)
        <hr>
        <div class="super-host-image-box">
            <div class="medal-container">
                <img src="{{ asset('') }}/images/badges/{{ $badge->icon }}.png" style="width: 200px;">
                <h6 class="medal-label">{{ $badge->badge_name }}</h6>
                <p class="profile-since">
                    {{ __('Member Since :time', ['time' => date('M Y', strtotime($user->created_at))]) }}</p>
            </div>
        </div>
    @endif


    <hr>

    <div class="profile-sidebar-stats">

        <ul>
            <li class="stats-box">
                <h1><?= $stats['totalBookings'] ?></h1>
                <p>Bookings</p>
            </li>
            <li class="stats-box">
                <h1><?= $user->review_count ?></h1>
                <p>Reviews</p>
            </li>
        </ul>

    </div>

    <hr>

    <div class="profile-contact-box">
        <button class="btn btn-primary" id="contactMeBtn" data-toggle="modal"
            data-target="#contactMEAsModal"><?= $isVendor ? 'CONTACT HOST' : 'CONTACT ME' ?></button>
    </div>

</div>
