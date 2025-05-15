<?php
namespace Modules\User\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Referalprogram\Models\ReferralLink;

class UserReferredEvent
{
    use SerializesModels;
    public $referralId;
    public $user;

    public function __construct($referralCode, $user)
    {
        if ($referralCode != null) {
            $referral = ReferralLink::whereCode($referralCode)->first();
            if ($referral != null) {
                $this->referralId = $referral->id;
                $this->user = $user;
            }
        }
    }
    public function broadcastOn()
    {
        return [];
    }
}