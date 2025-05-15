<?php
//if(!setting_item('tour_enable_inbox')) return;
$vendor = $row->author;
$badge = $vendor->getBadgeInfo();
?> 
@if (!empty($vendor->id))

    <div class="contact-info">
        <div class="{{ $badge!=null ? 'col-md-8' : 'col-md-12' }}">
            <div class="owner-info widget-box mb-4">
                <div class="flex">
                    <div class="media-left">
                        <a href="{{ route('user.profile', ['id' => $vendor->user_name ?? $vendor->id]) }}" target="_blank"
                            class="avatar-cover" style="background-image: url('{{ $vendor->getAvatarUrl() }}')">
                        </a>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading" style="font-weight: 700; color: white;"><a class="author-link"
                                href="{{ route('user.profile', ['id' => $vendor->user_name ?? $vendor->id]) }}"
                                target="_blank">{{ $vendor->getDisplayName() }}</a>
                        </h4>
                        <h5 style="font-size: small; font-weight: 500; margin-bottom: 0; color: white;">
                            @if ($vendor->is_verified)
                                {{ __('Verified') }}
                            @else 
                                {{ __('Not Verified') }}
                            @endif
                        </h5>
                        <p style="color: white;">
                            {{ __('Member Since :time', ['time' => date('M Y', strtotime($vendor->created_at))]) }}</p>
                    </div>
                </div>
            </div>
            <div class="host-btn">
                <div class="host-btn">
                    <a href="javascript:;" class="contactHostModalTrigger" data-host="{{ $row->create_user }}"
                        data-space="{{ $row->id }}">Contact Host</a>
                </div>
            </div>
        </div>
        @if($badge!=null)    
        <div class="medal-container">
            <img src="{{asset('')}}/images/badges/{{$badge->icon}}.png" style="width: 80px;">
            <h6 class="medal-label">{{$badge->badge_name}}</h6>
        </div>
        @endif
    </div>

@endif
