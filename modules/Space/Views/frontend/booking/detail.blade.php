@php
    use App\Helpers\CodeHelper;
$lang_local = app()->getLocale(); @endphp

<?php
$platform = Session::get('platform');
?>
<div class="booking-review" id="checkout-booking-review">
    <div id="checkout-booking-review-inner">
        <div class="booking-review-content">
            <div class="review-section">
                <div class="service-info">
                    <div>
                        @php
                            $service_translation = $service->translateOrOrigin($lang_local);
                        @endphp
                        {{-- <h3 class="service-name"><a href="{{ $service->getDetailUrl() }}">{!! clean($service_translation->title) !!}</a></h3> --}}
                        <h3 class="service-name">{!! clean($service_translation->title) !!}</h3>
                        <!--                     @if ($service_translation->address)
<p class="address"><i class="fa fa-map-marker"></i>
                            {{ $service_translation->address }}
                        </p>
@endif -->
                    </div>
                    <div>
                        @if ($image_url = $service->image_url)
                            @if (!empty($disable_lazyload))
                                <img src="{{ $service->image_url }}" class="img-responsive"
                                    alt="{!! clean($service_translation->title) !!}">
                            @else
                                {!! get_image_tag($service->image_id, 'medium', [
                                    'class' => 'img-responsive',
                                    'alt' => $service_translation->title,
                                ]) !!}
                            @endif
                        @endif
                    </div>
                    <!-- @php $vendor = $service->author; @endphp
                @if ($vendor->hasPermissionTo('dashboard_vendor_access') and !$vendor->hasPermissionTo('dashboard_access'))
                    <div class="mt-2">
                        {{ __('Host') }}:
                        @if ($platform == 'mobile')
<a href="{{ route('pwa.get.host', ['id' => $vendor->id]) }}"
                                >{{ $vendor->getDisplayName() }}</a>
@else
<a href="{{ route('user.profile', ['id' => $vendor->id]) }}"
                                target="_blank">{{ $vendor->getDisplayName() }}</a>
@endif
                    </div>
                @endif -->
                </div>
            </div>

            <div class="review-section">
                <div class="section-booking-date p-2">
                    <p class="service-name-booking-text text-uppercase"
                        style="font-family: inherit;font-size:12px !important">{{ __('Booking Date and time') }}</p>
                    <ul class="review-list">
                        @if ($booking->start_date)
                            <li>
                                <div class="label">&nbsp;&nbsp;&nbsp;&nbsp;
                                    <img src="{{ asset('/images/icons/svg/015-planedown.svg') }}" alt=""
                                        style="width: 20px;height:20px;border-radius:50%;background-color:white">
                                </div>
                                <div class="val">
                                    {!! display_string_month_custom($booking->start_date) !!}
                                </div>
                            </li>
                            <li>
                                <div class="label">&nbsp;&nbsp;&nbsp;&nbsp;
                                    <img src="{{ asset('/images/icons/svg/016-planeup.svg') }}" alt=""
                                        style="width: 20px;height:20px;border-radius:50%;background-color:white">
                                </div>
                                <div class="val text-bold">
                                    {!! display_string_month_custom($booking->end_date) !!}
                                </div>
                            </li>
                            @if ($platform == 'mobile')
                                <li>
                                    <div class="label">&nbsp;&nbsp;&nbsp;&nbsp;
                                        <img src="{{ asset('/icon/man.png') }}" alt=""
                                            style="width:20px;height:20px;">
                                    </div>
                                    <div class="val text-center w-100">
                                        <select class="form-control" style="width:60px">
                                            @for ($i = 0; $i < $booking->service['max_guests']; $i++)
                                                <option>{{ $i + 1 }}</option>
                                            @endfor
                                        </select>
                                        <span class="text-muted" style="font-size:8px">Capacity:
                                            {{ $booking->service['max_guests'] }} Guests</span>
                                    </div>
                                </li>
                            @endif
                            @if ($platform != 'mobile')
                                <li>
                                    <div class="label">&nbsp;&nbsp;&nbsp;&nbsp;
                                        <img src="{{ asset('/icon/man.png') }}" alt="" class="mt-2"
                                            style="width:20px;height:20px;">
                                    </div>
                                    <div class="val float-left">
                                        <select class="form-control" style="width:60px">
                                            @for ($i = 0; $i < $booking->service['max_guests']; $i++)
                                                <option>{{ $i + 1 }}</option>
                                            @endfor
                                        </select>
                                        <span class="text-muted" style="font-size:8px">Capacity:
                                            {{ $booking->service['max_guests'] }} Guests</span>
                                    </div>
                                </li>
                            @endif

                            @if ($meta = $booking->getMeta('adults'))
                                {{-- <li class="py-2" style="border-top: 1px solid #ddd">
                        <div class="label">&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Adults:') }}</div>
                        <div class="val">
                            {{ $meta }}
                        </div>
                    </li> --}}
                            @endif
                            @if ($meta = $booking->getMeta('children'))
                                {{-- <li class="py-2" style="border-top: 1px solid #ddd">
                        <div class="label">&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Children:') }}</div>
                        <div class="val">
                            {{ $meta }}
                        </div>
                    </li> --}}
                            @endif
                        @endif
                    </ul>
                    <div class="cotainer text-center py-3 ">
                        <a href="javascript:;" id="openCalendar" style="font-weight: 600;font-size:12px">View Full
                            Availabilty Calendar</a>
                    </div>
                </div>
            </div>

            <div class="review-section mb-2">
                <div class="quantity-section">
                    <ul class="review-list">
                        @if ($booking->start_date)
                            @php
                                $space = \Modules\Space\Models\Space::where('id', $booking->object_id)->first();
                                $bookingPriceDetails = \App\Helpers\CodeHelper::getSpacePrice(
                                    $space,
                                    $booking->start_date,
                                    $booking->end_date,
                                    $booking->id,
                                );

                                $price_item = $bookingPriceDetails['price'];
                                // $price_item = $booking->total_before_extra_price;
                                // $d1 = new DateTime($booking->start_date); // first date
                                // $d2 = new DateTime($booking->end_date); // second date
                                // $interval = $d1->diff($d2); // get difference between two dates
                                // //echo "difference " . $interval->y . " years, " . $interval->m." months, ".$interval->d." days ".$interval->h." hours ";
                                // $days = $interval->days;
                                // $stat_date = $d1->format('Y-m-d');
                                // $end_date = $d2->format('Y-m-d');
                                // $weeks = CodeHelper::numWeeks($stat_date, $end_date);
                                // $months = $interval->format('%m');

                                // if ($interval->h != '' and $interval->h < 24 and $months == 0 and $days == 0 and $weeks == 0) {
                                //     $book_qty = $interval->h;
                                //     $book_units = 'Hours';
                                //     $book_rate = $service->hourly != '' ? $service->hourly : $price_item / $interval->h;
                                //     $book_amount = $service->hourly != '' ? $interval->h * $service->hourly : $price_item;
                                // } elseif ($days != '' and $days >= 1 and $weeks == 0 and $months == 0) {
                                //     $book_qty = $interval->days;
                                //     $book_units = 'Days';
                                //     $book_rate = $service->daily;
                                //     $book_amount = $interval->days * $service->daily;
                                // } elseif ($weeks != '' and $weeks >= 1 and $months == 0) {
                                //     $book_qty = $interval->days;
                                //     $book_units = 'Weeks';
                                //     $book_rate = $service->weekly;
                                //     $book_amount = $weeks * $service->weekly;
                                // } elseif ($months != '' and $months >= 1) {
                                //     $book_qty = $months;
                                //     $book_units = 'Months';
                                //     $book_rate = $service->monthly;
                                //     $book_amount = $months * $service->monthly;
                                // } else {
                                //     $book_qty = 1;
                                //     $book_units = 'Fixed';
                                //     $book_rate = $price_item;
                                //     $book_amount = $price_item;
                                // }
                            @endphp
                            {!! $bookingPriceDetails['itemInfoHtml'] !!}
                        @endif
                    </ul>
                    <div class="review-list">
                        {!! $bookingPriceDetails['priceInfoHtml'] !!}
                        <div style="margin-top:10px;">
                            @includeIf('Coupon::frontend/booking/checkout-coupon')
                        </div>
                    </div>

                    @if ($booking->status != 'draft')
                        <div class="review-list">
                            <table class="booking-table-listing"
                                style="font-size: 14px;font-weight: 550;color: #007bff;">
                                <tbody>
                                    <tr>
                                        <td width="30%">&nbsp;</td>
                                        <td width="50%">{{ __('Paid:') }}</td>
                                        <td width="20%">{{ format_money($booking->paid) }}</td>
                                    </tr>

                                </tbody>
                            </table>
                            @if ($booking->paid < $booking->total)
                                <table class="booking-table-listing"
                                    style="font-size: 16px;font-weight: 550;border: 2px solid rgb(177, 173, 173);margin:5px;">
                                    <tbody>
                                        <tr>
                                            <td width="30%">&nbsp;</td>
                                            <td width="50%">{{ __('BALANCE') }}</td>
                                            <td width="20%">{{ format_money($booking->total - $booking->paid) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    @endif
                    <!-- @include ('Booking::frontend/booking/checkout-deposit-amount') -->
                </div>
            </div>
        </div>

        <?php
        $dateDetail = $service->detailBookingEachDate($booking);
        ?>
        <div class="modal fade" id="detailBookingDate{{ $booking->code }}" tabindex="-1" role="dialog"
            aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center">{{ __('Detail') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="review-list list-unstyled">
                            <li class="mb-3 pb-1 border-bottom">
                                <h6 class="label text-center font-weight-bold mb-1"></h6>
                                <div>
                                    @includeIf('Space::frontend.booking.detail-date', [
                                        'rows' => $dateDetail,
                                    ])
                                </div>
                                <div class="d-flex justify-content-between font-weight-bold px-2">
                                    <span>{{ __('Total:') }}</span>
                                    <span>{{ format_money(array_sum(\Illuminate\Support\Arr::pluck($dateDetail, ['price']))) }}</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
