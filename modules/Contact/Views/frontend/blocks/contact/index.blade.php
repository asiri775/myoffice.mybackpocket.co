<div class="bravo-contact-block contactArea">
    <div class="container">
        <div class="row section">
            <div class="col-md-12 col-lg-6">
                <div role="form" class="form_wrapper" lang="en-US" dir="ltr">
                    <form method="post" action="{{ route("contact.store") }}"  class="bravo-contact-block-form">
                        {{csrf_field()}}
                        <div style="display: none;">
                            <input type="hidden" name="g-recaptcha-response" value="">
                        </div>
                        <div class="contact-form contactPageData">
                            <div class="contact-header" style="padding-top: 50px;">
                                <h1>{{ setting_item_with_lang("page_contact_title") }}</h1>
                                <h2>{{ "Complete the contact form below and our team will respond as soon as possible" }}</h2>
                            </div>
                            @include('admin.message')
                            <div class="contact-form">
                                <div class="form-group">
                                    <input type="text" value="" placeholder=" {{ __('Name') }} " name="name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="email" value="" placeholder="{{ __('Email') }}" name="email" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="text" value="" placeholder="{{ __('Subject') }}" name="subject" class="form-control">
                                </div>
                                <div class="form-group">
                                    <textarea name="message" cols="40" rows="10" class="form-control textarea" placeholder="{{ __('Message') }}"></textarea>
                                </div>
                                <div class="form-group">
                                    {{recaptcha_field('contact')}}
                                </div>
                                <p>
                                    <button class="submit btn btn-primary " type="submit">
                                        {{ __('SEND MESSAGE') }}
                                    </button>
                                </p>
                            </div>
                        </div>
                        <div class="form-mess"></div>
                    </form>
                </div>
            </div>
            <div class="contactPageImage col-md-12 col-lg-6">
                <div class="contact-info">
                    <div class="info-bg">
                        {{-- @if($bg = get_file_url(setting_item("page_contact_image"),"full"))
                            <img src="{{$bg}}" class="img-responsive" alt="{{ setting_item_with_lang("page_contact_title") }}">
                        @endif --}}
                        <img src="{{asset('images/contact-right.png')}}?v=1" class="img-responsive" alt="{{ setting_item_with_lang("page_contact_title") }}">
                    </div>
                    <div class="info-content">
                        <div class="sub">
                            <p></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
