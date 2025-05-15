<style>
    .field-checkout-password {
        position: relative;
    }

    .field-checkout-password a.togglePassField {
        position: absolute;
        right: 10px;
        top: 40px;
        color: #999;
    }
</style>
<div class="form-checkout" id="form-checkout">
    <input type="hidden" name="code" value="{{ $booking->code }}">
    <div class="form-section">



        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="text-uppercase" style="font-size:12px">{{ __('First Name') }} <span
                            class="required">*</span></label>
                    <input type="text" placeholder="{{ __('First Name') }}" class="form-control"
                        value="{{ $user->first_name ?? '' }}" name="first_name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="text-uppercase" style="font-size:12px">{{ __('Last Name') }} <span
                            class="required">*</span></label>
                    <input type="text" placeholder="{{ __('Last Name') }}" class="form-control"
                        value="{{ $user->last_name ?? '' }}" name="last_name">
                </div>
            </div>
            <div class="col-md-6 field-email">
                <div class="form-group">
                    <label class="text-uppercase" style="font-size:12px">{{ __('Email') }} <span
                            class="required">*</span></label>
                    <input type="email" placeholder="{{ __('email@domain.com') }}" class="form-control"
                        value="{{ $user->email ?? '' }}" name="email">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mobileNoFieldDataParent">
                    <label class="text-uppercase" style="font-size:12px">{{ __('Phone') }} <span
                            class="required">*</span></label>
                    <input type="text" value="{{ $user->phone ?? '' }}" placeholder="{{ __('Phone Number') }}"
                        class="form-control mobileNoFieldData">
                    <input type="hidden" value="{{ $user->phone ?? '' }}" name="phone" class="real">
                </div>
            </div>

        </div>

        <?php
        if(!auth()->check()){
            ?>
        <div class="row">

            <div class="col-md-6 field-password">
                <div class="form-group password field-checkout-password">
                    <label class="text-uppercase" style="font-size:12px">{{ __('New Password') }} <span
                            class="required">*</span></label>
                    <input type="password" placeholder="{{ __('Enter New Password') }}" class="form-control"
                        value="{{ $user->password ?? '' }}" name="password">
                    <a href="javascript:;" class="togglePassField"><i class="input-icon icofont-eye"></i></a>
                </div>
            </div>

            <div class="col-md-6 field-confirm_password">
                <div class="form-group password field-checkout-password">
                    <label class="text-uppercase" style="font-size:12px">{{ __('Confirm Password') }} <span
                            class="required">*</span></label>
                    <input type="password" placeholder="{{ __('Enter Confirm Password') }}" class="form-control"
                        value="{{ $user->confirm_password ?? '' }}" name="confirm_password">
                    <a href="javascript:;" class="togglePassField"><i class="input-icon icofont-eye"></i></a>
                </div>
            </div>

        </div>
        <?php
        }
        ?>

        <div class="row">


            <div class="col-md-6 field-address-line-1">
                <div class="form-group">
                    <label class="text-uppercase" style="font-size:12px">{{ __('Address line 1') }} </label>
                    <input type="text" id="addressLineOne" placeholder="{{ __('Address line 1') }}"
                        class="form-control" value="{{ $user->address ?? '' }}" name="address_line_1">
                </div>
            </div>
            <div class="col-md-6 field-address-line-2">
                <div class="form-group">
                    <label class="text-uppercase" style="font-size:12px">{{ __('Address line 2') }} </label>
                    <input type="text" id="addressLineTwo" placeholder="{{ __('Address line 2') }}"
                        class="form-control" value="{{ $user->address2 ?? '' }}" name="address_line_2">
                </div>
            </div>
            <div class="col-md-6 field-city">
                <div class="form-group">
                    <label class="text-uppercase" style="font-size:12px">{{ __('City') }} </label>
                    <input type="text" id="city" class="form-control" value="{{ $user->city ?? '' }}"
                        name="city" placeholder="{{ __('Your City') }}">
                </div>
            </div>
            <div class="col-md-6 field-state">
                <div class="form-group">
                    <label class="text-uppercase" style="font-size:12px">{{ __('State/Province/Region') }} </label>
                    <input type="text" id="state" class="form-control" value="{{ $user->state ?? '' }}"
                        name="state" placeholder="{{ __('State/Province/Region') }}">
                </div>
            </div>
            <div class="col-md-6 field-zip-code">
                <div class="form-group">
                    <label class="text-uppercase" style="font-size:12px">{{ __('ZIP code/Postal code') }} </label>
                    <input type="text" id="zipCode" class="form-control" value="{{ $user->zip_code ?? '' }}"
                        name="zip_code" placeholder="{{ __('ZIP code/Postal code') }}">
                </div>
            </div>
            <div class="col-md-6 field-country">
                <div class="form-group">
                    <label class="text-uppercase" style="font-size:12px">{{ __('Country') }} <span
                            class="required">*</span> </label>
                    <input type="text" id="country" class="form-control" value="{{ $user->country ?? '' }}"
                        name="country" placeholder="{{ __('Country') }}">
                </div>
            </div>
            <div class="col-md-12">
                <label class="text-uppercase" style="font-size:12px">{{ __('Special Requirements') }} </label>
                <textarea name="customer_notes" cols="30" rows="6" class="form-control"
                    placeholder="{{ __('Special Requirements') }}"></textarea>
            </div>

            <?php
        if(!auth()->check()){
            ?>

            <div class="col-md-12 mt-3 field-referral-code">
                <label class="formlabel fontsize13 robotoregular graytext font-weight-bold">Referral
                    Code</label>
                <input type="text" value="" class="form-control" name="referral_code" autocomplete="off"
                    placeholder="Enter Referral Code to Win Credits">
            </div>

            <?php
        }
        ?>

        </div>
    </div>

    @include ('Booking::frontend/booking/checkout-deposit')
    @include ($service->checkout_form_payment_file ?? 'Booking::frontend/booking/checkout-payment')

    @php
        $term_conditions = setting_item('booking_term_conditions');
    @endphp

    <div class="form-group">
        <label class="term-conditions-checkbox">
            <input type="checkbox" name="term_conditions"> {{ __('I have read and accept the') }} <a target="_blank"
                href="{{ get_page_url($term_conditions) }}">{{ __('terms and conditions') }}</a>
        </label>
    </div>
    @if (setting_item('booking_enable_recaptcha'))
        <div class="form-group">
            {{ recaptcha_field('booking') }}
        </div>
    @endif
    <div class="html_before_actions"></div>

    <p class="alert-text mt10" v-show=" message.content" v-html="message.content"
        :class="{ 'danger': !message.type, 'success': message.type }"></p>

    <div class="form-actions">
        <button class="btn btn-primary col-md-12" id="(anotherConfirmationBox)" @click="doCheckout">PROCEED TO
            CHECKOUT
        </button>

        <a onclick="return confirm('Are you sure you want to cancel this booking?')"
            class="btn btn-outline-primary col-md-12"
            href="{{ route('cancelPendingBooking', $booking->code) }}">CANCEL</a>
    </div>
</div>
