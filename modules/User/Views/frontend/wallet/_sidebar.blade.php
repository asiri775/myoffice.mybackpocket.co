<?php
use Modules\Referalprogram\Models\ReferralLink;
use Modules\Referalprogram\Models\ReferralProgram;
use App\Models\CreditCoupons;

$referral_details = null;
$signUpReferral = ReferralProgram::where('uri', 'register')->first();
if ($signUpReferral != null) {
    $referral_details = new ReferralLink();
    $referral_details = $referral_details->getRefferal($row, $signUpReferral);
}

$promoCredits = CreditCoupons::where('user_id', $row->id)
    ->where('pending', '>', 0)
    ->get();

?>
<style>
    .social-buttons ul {
        position: relative;
        margin: 0 auto;
        padding: 0;
        text-align: center;
        width: 100%;
        display: flex;
        background: rgba(0, 0, 0, 0.2);
        transition: 0.5s;
    }

    .social-buttons ul li {
        list-style: none;
        box-sizing: border-box;
    }

    .social-buttons ul li a {
        color: #fff;
        font-size: 20px;
        margin: 10px;
        display: inline-block;
    }

    .social-buttons ul li:nth-child(1) {
        background: #3b5999;
    }

    .social-buttons ul li:nth-child(2) {
        background: #55acee;
    }

    .social-buttons ul li:nth-child(3) {
        background: #25d366;
    }

    .social-buttons ul li:nth-child(4) {
        background: #0077B5;
    }
</style>
<div class="card card-default card-bordered p-2 card-radious">
    <div class="card-header card-header-actions">
        <div class="card-title">
            <h4 class="text-uppercase">
                <strong>
                    Account Balances
                </strong>
            </h4>
        </div>
    </div>
    <div class="card-body">
        <ul class="card-stats-items">
            <li>
                <div class="card-stats-item">
                    <span>Cash</span>
                    <h4>{{ \App\Helpers\CodeHelper::formatPrice(__(':amount', ['amount' => $row->balance])) }}</h4>
                </div>
            </li>
            <li>
                <div class="card-stats-item">
                    <span>Credits</span>
                    <h4>{{ $row->promo_credits > 0 ? \App\Helpers\CodeHelper::formatPrice(__(':amount', ['amount' => $row->promo_credits])) : 0 }}
                    </h4>
                </div>
            </li>
        </ul>
        <hr />
        <h6>Promo Credits</h6>
        <p>Your have
            {{ $row->promo_credits > 0 ? \App\Helpers\CodeHelper::formatPrice(__(':amount', ['amount' => $row->promo_credits])) : 0 }}
            Promo Credits in your account. </p>
        {{-- <p>These promotional credits are <span class="text-danger">set to expire on January 14,
                2024</span></p> --}}
        @if ($row->promo_credits > 0)
            <a data-toggle="modal" data-target="#promoCodeRedeem" href="javascript:;" class="btn btn-primary">Use Promo
                Credits Now</a>
        @endif
        @if ($referral_details)
            <hr />
            <h6>Earn Credits</h6>
            <p>Use your referral code below, and invite friends, colleagues and associates
                to try MyOffice. Receive ${{ $signUpReferral->amount }} in your account every time someone signs up
                with your referral code.</p>
            <div class="referral-box">
                <p>Your Unique Code</p>
                <a href="javascript:;" class="btn btn-primary copyToClipboard" data-clipboard-text="{{ $referral_details->code }}">{{ $referral_details->code }}</a>
                <p>Share Your Code</p>
                @php
                    $referral_code = $referral_details->code;
                    $registerUrl = 'http://myoffice.mybackpocket.co/register?ref=' . $referral_code;
                    $text = 'Hey, Sign up for MyOffice and user my code ' . $referral_code . ' to get $' . $signUpReferral->amount . ', or click this link ' . $registerUrl . '.  We will both get the credits if you sign up using my code or link!';
                    $text = urlencode($text);
                @endphp
                <div class="social-buttons">
                    <ul>
                        <li> <a href="https://www.facebook.com/sharer.php?u={{ $registerUrl }}"
                                title="Share on Facebook" target="_blank"><i class="fa fa-facebook-f"
                                    aria-hidden="true"></i></a> </li>
                        {{-- <li>
                            <a href="https://www.instagram.com/share?text={{ $text }}" target="_blank"
                                title="Share on Instagram">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </a>
                        </li> --}}
                        <li> <a href="https://wa.me/?text={{ $text }}" target="_blank" title="Whatsapp share"><i
                                    class="fa fa-whatsapp"></i></a> </li>
                        <li>
                            <a href="mailto:?subject=MyOffice+Discount+Code&body={{ $text }}" target="_blank"
                                title="Email">
                                <i class="fa fa-envelope"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="modal fade" id="promoCodeRedeem" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header justify-content-between">
                <h5 style="font-family:Montserrat;font-size:16pt;font-weight:900;"
                    class="modal-title text-center w-100">&nbsp;&nbsp;Promo Codes
                    <p>Click on the Promo Code below to display the list of Spaces 
                        where the code will be valid for use</p>
                    <hr />
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-0">
                <table class="table demo-table-search table-responsive-block data-table dataTable no-footer">
                    <thead>
                        <th>Code</th>
                        <th>Reference</th>
                        <th style="max-width: 100px;">Type</th>
                        <th>Amount</th>
                        <th style="min-width: 200px;">Expiring At</th>
                    </thead>
                    <?php
                    foreach ($promoCredits as $promoCredit) {
                        $link = url('/space');
                        if($promoCredit->object_model === "booking"){
                            $bookingModel = \Modules\Booking\Models\Booking::where('id', $promoCredit->object_id)->first();
                            if($bookingModel!==null){
                                $link = route('user.profile.publicProfile', $bookingModel->vendor_id);
                            }
                        }
                        ?>
                    <tr>
                        <td><a href="{{$link}}" target="_blank" style="text-decoration: underline;"><?= $promoCredit->code ?></a></td>
                        <td>#<?= $promoCredit->object_id ?></td>
                        <td style="max-width: 100px;"><?= ucfirst($promoCredit->type) ?></td>
                        <td><?= \App\Helpers\CodeHelper::formatPrice(__(':amount', ['amount' => $promoCredit->pending])) ?>
                        </td>
                        <td><?= \App\Helpers\CodeHelper::formatDateTime($promoCredit->expired_at) ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </div>

    </div>
</div>
