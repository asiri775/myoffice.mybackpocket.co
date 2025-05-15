@if(is_default_lang())
    <div class="row">
        <div class="col-sm-12">
            
            <div class="panel">
                <div class="panel-body">
                    <div class="form-group-item">
                        <label class="control-label">{{__('Default FAQs')}}</label>
                        <div class="g-items-header">
                            <div class="row">
                                <div class="col-md-11">{{__("FAQ")}}</div>
                                <div class="col-md-1"></div>
                            </div> 
                        </div>
                        <div class="g-items">
                            <?php  $languages = \Modules\Language\Models\Language::getActive();  ?>
                            @if(!empty($settings['space_default_faqs']))
                                <?php $space_default_faqs = json_decode($settings['space_default_faqs'],true); ?>
                                @foreach($space_default_faqs as $key=>$space_default_faq)
                                    <div class="item" data-number="{{$key}}">
                                        <div class="row">
                                            <div class="col-md-11">
                                                @if(!empty($languages) && setting_item('site_enable_multi_lang') && setting_item('site_locale'))
                                                    @foreach($languages as $language)
                                                        <?php $key_lang = setting_item('site_locale') != $language->locale ? "_".$language->locale : ""   ?>
                                                        <div class="g-lang">
                                                            <div class="title-lang">{{$language->name}}</div>
                                                            <input type="text" name="space_default_faqs[{{$key}}][title{{$key_lang}}]" class="form-control" value="{{$space_default_faq['title'.$key_lang] ?? ''}}" placeholder="{{__('Question')}}">
                                                            <input type="text" name="space_default_faqs[{{$key}}][content{{$key_lang}}]" class="form-control" value="{{$space_default_faq['content'.$key_lang] ?? ''}}" placeholder="{{__('Answer')}}">
                                                        </div>

                                                    @endforeach
                                                @else
                                                    <input type="text" name="space_default_faqs[{{$key}}][title]" class="form-control" value="{{$space_default_faq['title'] ?? ''}}" placeholder="{{__('Question')}}">
                                                    <input type="text" name="space_default_faqs[{{$key}}][content]" class="form-control" value="{{$space_default_faq['content'] ?? ''}}" placeholder="{{__('Answer')}}">
                                                @endif
                                            </div>
                                            <div class="col-md-1">
                                                <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="text-right">
                            <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i> {{__('Add item')}}</span>
                        </div>
                        <div class="g-more hide">
                            <div class="item" data-number="__number__">
                                <div class="row">
                                    <div class="col-md-11">
                                        @if(!empty($languages) && setting_item('site_enable_multi_lang') && setting_item('site_locale'))
                                            @foreach($languages as $language)
                                                <?php $key = setting_item('site_locale') != $language->locale ? "_".$language->locale : ""   ?>
                                                <div class="g-lang">
                                                    <div class="title-lang">{{$language->name}}</div>
                                                    <input type="text" __name__="space_default_faqs[__number__][title{{$key}}]" class="form-control" value="" placeholder="{{__('Question')}}">
                                                    <input type="text" __name__="space_default_faqs[__number__][content{{$key}}]" class="form-control" value="" placeholder="{{__('Answer')}}">
                                                </div>

                                            @endforeach
                                        @else
                                            <input type="text" __name__="space_default_faqs[__number__][title]" class="form-control" value="" placeholder="{{__('Question')}}">
                                            <input type="text" __name__="space_default_faqs[__number__][content]" class="form-control" value="" placeholder="{{__('Answer')}}">
                                        @endif
                                    </div>
                                    <div class="col-md-1">
                                        <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-body">
                    <div class="form-group-item">
                        <label class="control-label">{{__('Default Terms of Service')}}</label>
                        <div class="">
                            <textarea name="space_default_terms" class="d-none has-ckeditor" cols="30" rows="10">{{$settings['space_default_terms']}}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-body">
                    <div class="form-group-item">
                        <label class="control-label">{{__('Default Privacy Policy')}}</label>
                        <div class="">
                            <textarea name="space_default_privacy_policy" class="d-none has-ckeditor" cols="30" rows="10">{{$settings['space_default_privacy_policy']}}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-body">
                    <div class="form-group-item">
                        <label class="control-label">{{__('Default House Rules')}}</label>
                        <div class="">
                            <textarea name="space_default_house_rules" class="d-none has-ckeditor" cols="30" rows="10">{{$settings['space_default_house_rules']}}</textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endif

