@extends('layouts.common_home')
@section('content')
    @if ($row->template_id)
        <div class="layout1 homePage">

            {!! $row->getProcessedContent() !!}


        </div>
    @else
        <div class="layout1 homePage">
            <div class=" container container-fixed-lg">
                <!-- Tab panes -->
                <div class="sub-page page-cms-content {{ $row->slug }}">
                    <div class="slide-left padding-20 sm-no-padding" id="tab5">
                        <div class="row row-same-height">
                            <div class="col-md-12">
                                <h1 class="inner pt-5 main-title cms-main-title">{!! clean($translation->title) !!}</h1>
                                <div class="sm-padding-5">
                                    <p>{!! $translation->content !!}</p>
                                </div>
                                <?php
                                if(in_array($row->slug, ['guests-how-it-works', 'how-it-works-hosts'])){
                                    ?>
                                <div role="form" class="form_wrapper">
                                    <form method="post" action="{{ route('contact.store') }}"
                                        class="bravo-contact-block-form">
                                        {{ csrf_field() }}
                                        <div style="display: none;">
                                            <input type="hidden" name="g-recaptcha-response" value="">
                                        </div>
                                        <div class="contact-form contactPageData">
                                            <div class="contact-header" style="padding-top: 10px;text-align:left;">
                                                <h1 style="text-transform: uppercase;">
                                                    {{ setting_item_with_lang('page_contact_title') }}</h1>
                                                <h2>{{ 'Complete the contact form below and our team will respond as soon as possible' }}
                                                </h2>
                                            </div>
                                            @include('admin.message')
                                            <div class="contact-form">
                                                <input type="hidden" name="title"
                                                    value="<?= $row->slug === 'guests-how-it-works' ? 'Guest: How it works' : 'Host: How it works' ?>">
                                                <div class="form-group">
                                                    <input type="text" value="" placeholder=" {{ __('Name') }} "
                                                        name="name" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" value="" placeholder="{{ __('Email') }}"
                                                        name="email" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" value="" placeholder="{{ __('Subject') }}" name="subject" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <textarea name="message" cols="40" rows="10" class="form-control textarea" placeholder="{{ __('Message') }}"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    {{ recaptcha_field('contact') }}
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
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="brands">
            <img src="{{ asset('images/brands.png') }}" alt="brands" />
        </div>
    @endif
@endsection
