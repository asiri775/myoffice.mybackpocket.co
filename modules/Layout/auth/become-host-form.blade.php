<form class="form bravo-register-host themeForm" method="post">
    @csrf
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="form-group">
                <label class="formlabel fontsize13 robotoregular graytext font-weight-bold">First Name
                    <span class=" required">*</span></label>
                <input type="text" class="form-control" name="first_name" autocomplete="off"
                    placeholder="{{ __('First Name') }}">
                <i class="input-icon field-icon icofont-waiter-alt"></i>
                <span class="invalid-feedback error error-first_name"></span>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="form-group">
                <label class="formlabel fontsize13 robotoregular graytext font-weight-bold">Last Name
                    <span class=" required">*</span></label>
                <input type="text" class="form-control" name="last_name" autocomplete="off"
                    placeholder="{{ __('Last Name') }}">
                <i class="input-icon field-icon icofont-waiter-alt"></i>
                <span class="invalid-feedback error error-last_name"></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="formlabel fontsize13 robotoregular graytext font-weight-bold">Phone</label>
        <input type="text" class="form-control" name="phone" autocomplete="off" placeholder="{{ __('Phone') }}">
        <i class="input-icon field-icon icofont-ui-touch-phone"></i>
        <span class="invalid-feedback error error-phone"></span>
    </div>
    <div class="form-group">
        <label class="formlabel fontsize13 robotoregular graytext font-weight-bold">Email Address
            <span class=" required">*</span></label>
        <input type="email" class="form-control" name="email" autocomplete="off"
            placeholder="{{ __('Email address') }}">
        <i class="input-icon field-icon icofont-mail"></i>
        <span class="invalid-feedback error error-email"></span>
    </div>
    <div class="form-group password">
        <label class="formlabel fontsize13 robotoregular graytext font-weight-bold">Password
            <span class=" required">*</span></label>
        <input type="password" class="form-control" name="password" autocomplete="off"
            placeholder="{{ __('Password') }}">
        <i class="input-icon field-icon icofont-ui-password"></i>
        <a href="javascript:;" class="togglePassField"><i class="input-icon icofont-eye"></i></a>
        <span class="invalid-feedback error error-password"></span>
    </div>
    <div class="form-group">
        <label for="term">
            <input id="term" type="checkbox" name="term" class="mr5">
            {!! __("I have read and accept the <a href=':link' target='_blank'>Terms and Privacy Policy</a>", [
                'link' => get_page_url(setting_item('booking_term_conditions')),
            ]) !!}
        </label>
        <div><span class="invalid-feedback error error-term"></span></div>
    </div>
    @if (setting_item('user_enable_register_recaptcha'))
        <div class="form-group">
            {{ recaptcha_field($captcha_action ?? 'register') }}
        </div>
        <div><span class="invalid-feedback error error-g-recaptcha-response"></span></div>
    @endif
    <div class="error message-error invalid-feedback"></div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary form-submit btn-block">
            {{ __('Sign Up') }}
            <span class="spinner-grow spinner-grow-sm icon-loading" role="status" aria-hidden="true"></span>
        </button>
    </div>

    <div class="advanced">
        <p class="text-center f14 c-grey">Already a MyOffice member? <a href="javascript:;"
                class="signinclickmain gray-text">Log
                In</a></p>
    </div>

    @if (setting_item('facebook_enable') or setting_item('google_enable') or setting_item('twitter_enable'))
        <div class="socialAuthStages">
            <div class="loginrow orPlx fulwidthm left josfinsanregular fontsize16 graytext mgnB15 text-center">
                <span>or</span>
            </div>
            <div class="fontsize12 graytext text-center mb-3">Continue With</div>
            <div id="wrapper" style="text-align: center;">
                <div style="display: inline-block; vertical-align: top;">
                    <a href="{{ route('social.login', 'facebook') }}">
                        <div class="text left pr-3">
                            <img src="/images/facebook.png">
                            <p class="robotoregular fontsize11 graytext text-uppercase mt-2">Facebook
                            </p>
                        </div>
                    </a>
                </div>
                <div style="display: inline-block; vertical-align: top;">
                    <a href="{{ route('social.login', 'google') }}">
                        <div class="text-center left">
                            <img src="/images/google.png">
                            <p class="robotoregular fontsize11 graytext text-uppercase mt-2">Google+</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    @endif



    {{-- @if (setting_item('facebook_enable') or setting_item('google_enable') or setting_item('twitter_enable'))
        <div class="advanced">
            <p class="text-center f14 c-grey">{{ __('or continue with') }}</p>
            <div class="row">
                @if (setting_item('facebook_enable'))
                    <div class="col-xs-12 col-sm-4">
                        <a href="{{ url('/social-login/facebook') }}" class="btn btn_login_fb_link"
                            data-channel="facebook">
                            <i class="input-icon fa fa-facebook"></i>
                            {{ __('Facebook') }}
                        </a>
                    </div>
                @endif
                @if (setting_item('google_enable'))
                    <div class="col-xs-12 col-sm-4">
                        <a href="{{ url('social-login/google') }}" class="btn btn_login_gg_link" data-channel="google">
                            <i class="input-icon fa fa-google"></i>
                            {{ __('Google') }}
                        </a>
                    </div>
                @endif
                @if (setting_item('twitter_enable'))
                    <div class="col-xs-12 col-sm-4">
                        <a href="{{ url('social-login/twitter') }}" class="btn btn_login_tw_link"
                            data-channel="twitter">
                            <i class="input-icon fa fa-twitter"></i>
                            {{ __('Twitter') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif --}}


</form>
