<div class="panel">
    <div class="panel-title"><strong>{{ __('Space Content') }}</strong></div>
    <div class="panel-body">
        <div class="form-group">
            <label>{{ __('Title') }}</label>
            <input type="text" value="{!! clean(old('title', $translation->title)) !!}" placeholder="{{ __('Name of the space') }}" name="title"
                class="form-control">
        </div>
        <div class="form-group">
            <label class="control-label">{{ __('Description') }}</label>
            <div class="">
                <textarea name="content" class="d-none has-ckeditor" cols="30" rows="10">{{ old('content', $translation->content) }}</textarea>
            </div>
        </div> 
        @if (is_default_lang())
            <div class="form-group">
                <label class="control-label">{{ __('Youtube Video') }}</label>
                <input type="text" name="video" class="form-control" value="{{ old('video', $row->video) }}"
                    placeholder="{{ __('Youtube link video') }}">
            </div>
        @endif
        <div class="form-group-item">
            <label class="control-label">{{ __('FAQs') }}</label>
            <div class="g-items-header">
                <div class="row">
                    <div class="col-md-5">{{ __('Title') }}</div>
                    <div class="col-md-5">{{ __('Content') }}</div>
                    <div class="col-md-1"></div>
                </div>
            </div>
            <div class="g-items">
                @if (!empty( old('faqs', $translation->faqs)))
                    @php
                        if (!is_array(old('faqs', $translation->faqs))) {
                            $translation->faqs = json_decode(old('faqs', $translation->faqs));
                        }
                    @endphp
                    @foreach (old('faqs', $translation->faqs) as $key => $faq)
                        <div class="item" data-number="{{ $key }}">
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" name="faqs[{{ $key }}][title]" class="form-control"
                                        value="{{ $faq['title'] }}"
                                        placeholder="{{ __('Eg: When and where does the tour end?') }}">
                                </div>
                                <div class="col-md-6">
                                    <textarea name="faqs[{{ $key }}][content]" class="form-control" placeholder="...">{{ $faq['content'] }}</textarea>
                                </div>
                                <div class="col-md-1">
                                    <span class="btn btn-danger btn-sm btn-remove-item"><i
                                            class="fa fa-trash"></i></span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="text-right">
                <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i>
                    {{ __('Add item') }}</span>
            </div>
            <div class="g-more hide">
                <div class="item" data-number="__number__">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" __name__="faqs[__number__][title]" class="form-control"
                                placeholder="{{ __('Eg: Can I bring my pet?') }}">
                        </div>
                        <div class="col-md-6">
                            <textarea __name__="faqs[__number__][content]" class="form-control" placeholder=""></textarea>
                        </div>
                        <div class="col-md-1">
                            <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (is_default_lang())
            <div class="form-group">
                <label class="control-label">{{ __('Banner Image') }}</label>
                <div class="form-group-image">
                    {!! \Modules\Media\Helpers\FileHelper::fieldUpload('banner_image_id', $row->banner_image_id) !!}
                </div>
            </div>
            <div class="form-group">
                <label class="control-label">{{ __('Gallery') }}</label>
                {!! \Modules\Media\Helpers\FileHelper::fieldGalleryUpload('gallery', $row->gallery) !!}
            </div>
        @endif
    </div>
</div>
@if (is_default_lang())
    <div class="panel">
        <div class="panel-title"><strong>{{ __('Extra Info') }}</strong></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>{{ __('No. Desks') }}</label>
                        <input type="number" value="{{ old('desk', $row->desk) }}" placeholder="{{ __('Example: 3') }}"
                            name="desk" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>{{ __('No. Seats') }}</label>
                        <input type="number" value="{{ old('seat', $row->seat) }}" placeholder="{{ __('Example: 5') }}"
                            name="seat" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="control-label">{{ __('Max Guests') }}</label>
                        <input type="number" step="any" name="max_guests" class="form-control"
                            value="{{ old('max_guests', $row->max_guests) }}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>{{ __('Square') }}</label>
                        <input type="number" value="{{ old('square', $row->square) }}" placeholder="{{ __('Example: 100') }}"
                            name="square" class="form-control">
                    </div>
                </div>
            </div>
            @if (is_default_lang())
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="control-label">{{ __('Minimum advance reservations') }}</label>
                            <input type="number" name="min_day_before_booking" class="form-control"
                                value="{{ old('min_day_before_booking', $row->min_day_before_booking) }}" placeholder="{{ __('Ex: 3') }}">
                            <i>{{ __('Leave blank if you dont need to use the min day option') }}</i>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="control-label">{{ __('Minimum day stay requirements') }}</label>
                            <input type="number" name="min_day_stays" class="form-control"
                                value="{{ old('min_day_stays', $row->min_day_stays) }}" placeholder="{{ __('Ex: 2') }}">
                            <i>{{ __('Leave blank if you dont need to set minimum day stay option') }}</i>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="first_working_day">Rapidbook</label>
                        <select name="rapidbook" id="rapidbook" class="form-control">
                            <option {{ old('rapidbook', $row->rapidbook) == 0 ? 'selected' : '' }} value="0">Off</option>
                            <option {{ old('rapidbook', $row->rapidbook) == 1 ? 'selected' : '' }} value="1">On</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="last_working_day">Free Cancellation</label>
                        <select name="free_cancellation" id="free_cancellation" class="form-control">
                            <option {{ old('free_cancellation', $row->free_cancellation) == 0 ? 'selected' : '' }} value="0">Off</option>
                            <option {{ old('free_cancellation', $row->free_cancellation) == 1 ? 'selected' : '' }} value="1">On</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
