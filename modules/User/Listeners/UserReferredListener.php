<?php
namespace Modules\User\Listeners;

use App\Helpers\CodeHelper;
use App\Helpers\Constants;
use Illuminate\Support\Facades\Mail;
use Modules\Contact\Emails\NotificationEmail;
use Modules\Referalprogram\Models\ReferralLink;
use Modules\Referalprogram\Models\ReferralRelationship;
use Modules\User\Events\UserReferredEvent;

class UserReferredListener
{
    public function handle(UserReferredEvent $event)
    {
        if ($event->referralId != null) {
            $referral = ReferralLink::find($event->referralId);
            if (!is_null($referral)) {
                ReferralRelationship::create(['referral_link_id' => $referral->id, 'user_id' => $event->user->id]);
                if ($referral->program->uri === 'register') {

                    // User who was sharing link
                    $ownerUser = $referral->user;
                    // User who used the link
                    $user = $event->user;
                    //amount to credit
                    $amount = $referral->program->amount;

                    CodeHelper::addUserTransaction(
                        $ownerUser,
                        Constants::TRANSACTION_TYPE_REFERRAL,
                        $amount,
                        Constants::CREDIT,
                        'REFERRAL_BONUS' . '-' . $user->id,
                        [],
                        [],
                        false,
                        true,
                        true,
                        [
                            'reference' => 'Referral Bonus on registration of ' . $user->email,
                            'object_id' => $user->id,
                            'object_model' => 'user'
                        ]
                    );

                    Mail::to($ownerUser->email)->send(
                        new NotificationEmail(
                            'Referral Bonus Received',
                            'You have received ' . CodeHelper::formatPrice($amount) . ' as promo credits on registration of ' . $user->first_name . ' ' . $user->last_name . '.</br> Go to wallet to get coupon code to use this credits.',
                        )
                    );

                    CodeHelper::addUserTransaction(
                        $user,
                        Constants::TRANSACTION_TYPE_REFERRAL_PROMO,
                        $amount,
                        Constants::CREDIT,
                        'REFERRAL_PROMO_BONUS' . '-' . $ownerUser->id,
                        [],
                        [],
                        false,
                        true,
                        true,
                        [
                            'reference' => 'Referral Bonus on using ' . $ownerUser->email . ' coupon while registration',
                            'object_id' => $ownerUser->id,
                            'object_model' => 'user'
                        ]
                    );

                    Mail::to($user->email)->send(
                        new NotificationEmail(
                            'Promotional bonus Received',
                            'You have received ' . CodeHelper::formatPrice($amount) . ' as promo credits by using coupon code while signup.</br> Go to wallet to get coupon code to use this credits.',
                        )
                    );


                }
            }
        }
    }
}