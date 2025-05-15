<div class="space-form-box">
    <?php $languages = \Modules\Language\Models\Language::getActive(); ?>
    <style>
        .g-lang {
            padding: 0 !important;
            border: none !important;
            margin: 0 !important;
        }
    </style>
    @if (is_default_lang())
        <div class="panel">
            <div class="panel-title"><strong>{{ __('Pricing') }}</strong></div>
            <div class="panel-body">
                @if (is_default_lang())
                    <div class="row">
                        <div class="col-lg-4 d-none">
                            <div class="form-group">
                                <label class="control-label">{{ __('Price') }}</label>
                                <div class="price-form-group">
                                    <span>$</span>
                                    <input type="number" step="any" min="0" name="price"
                                        class="form-control" value="{{ old('price', $row->price) }}"
                                        placeholder="{{ __('Space Price') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 d-none">
                            <div class="form-group">
                                <label class="control-label">{{ __('Sale Price') }}</label>
                                <div class="price-form-group">
                                    <span>$</span>
                                    <input type="number" step="any" name="sale_price" class="form-control"
                                        value="{{ old('sale_price', $row->sale_price) }}"
                                        placeholder="{{ __('Space Sale Price') }}">
                                </div>
                                <span><i>{{ __('If the regular price is less than the discount , it will show the regular price') }}</i></span>
                            </div>
                        </div>
                        <div class="col-lg-4 d-none">
                            <div class="form-group">
                                <label class="control-label">{{ __('Discount') }}</label>
                                <div class="price-form-group">
                                    <span>$</span>
                                    <input type="number" min="0" name="discount" class="form-control"
                                        value="{{ old('discount', $row->discount) }}"
                                        placeholder="{{ __('Discount %') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <script>
                            let current_price = 0;
                            @if ($row->hourly_price_set_default)
                                current_price = 1;
                            @endif
                            @if ($row->daily_price_set_default)
                                current_price = 2;
                            @endif
                            @if ($row->weekly_price_set_default)
                                current_price = 3;
                            @endif
                            @if ($row->monthly_price_set_default)
                                current_price = 4;
                            @endif

                            function setDefaultPrice(price_id) {
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                    }
                                });
                                jQuery.ajax({
                                    url: "{{ route('space.vendor.defult.price', ['id' => $row->id ? $row->id : '-1', 'lang' => request()->query('lang')]) }}",
                                    method: 'post',
                                    data: {
                                        price_id: price_id,
                                    },
                                    success: function(result) {
                                        for (var i = 0; i < 5; i++) {
                                            $('.setDefaultprice_' + i).html(
                                                '<a href="#" onclick="setDefaultPrice(' + i + ');" >Set Default</a>');
                                        }
                                        if (current_price) {
                                            $('.viewDefaultPrice_' + current_price).html(
                                                '<a href="#" onclick="setDefaultPrice(' + current_price + ');" >Set Default</a>'
                                            );
                                        }
                                        $('.setDefaultprice_' + price_id).html('<span class="btn btn-primary">Default Rate</span>');
                                    }
                                });
                            }
                        </script>
                        <table class="table" style="border-bottom:1px solid #ccc">
                            <tr>
                                <th width="20%"></th>
                                <th width="30%" style="margin-bottom: 0;padding-bottom:0;height:20px;text-align:center;">Standard Rate</th>
                                <th width="30%" style="margin-bottom: 0;padding-bottom:0;height:20px;text-align:center;">Discounted Rate</th>
                                <th width="20%"></th>
                            </tr>
                            <tr>
                                <td>{{ __('Hourly') }}</td>

                                <td>
                                    <div class="price-form-group">
                                        <span>$</span>
                                        <input type="number" step="any" min="0" name="hourly"
                                            class="form-control" value="{{ old('hourly', $row->hourly) }}"
                                            placeholder="{{ __('Hourly') }}">
                                    </div>
                                </td>

                                <td>
                                    <div class="price-form-group">
                                        <span>$</span>
                                        <input type="number" step="any" min="0" name="discounted_hourly"
                                            class="form-control"
                                            value="{{ old('discounted_hourly', $row->discounted_hourly ?? $row->hourly) }}"
                                            placeholder="{{ __('Hourly') }}">
                                    </div>
                                </td>

                                @if (old('hourly_price_set_default', $row->hourly_price_set_default))
                                    <td class="viewDefaultPrice_1">
                                        <span class="btn btn-primary">Default Rate</span>
                                    </td>
                                @else
                                    <td class="setDefaultprice_1">
                                        <a href="#" onclick="setDefaultPrice(1);">Set Default</a>
                                    </td>
                                @endif
                            </tr>
                            <tr>
                                <td>{{ __('Daily') }}</td>
                                <td>
                                    <div class="price-form-group">
                                        <span>$</span>
                                        <input type="number" min="0" name="daily" class="form-control"
                                            value="{{ old('daily', $row->daily) }}" placeholder="{{ __('Daily') }}">
                                    </div>
                                </td>

                                <td>
                                    <div class="price-form-group">
                                        <span>$</span>
                                        <input type="number" step="any" min="0" name="discounted_daily"
                                            class="form-control"
                                            value="{{ old('discounted_daily', $row->discounted_daily ?? $row->daily) }}"
                                            placeholder="{{ __('Daily') }}">
                                    </div>
                                </td>

                                @if (old('daily_price_set_default', $row->daily_price_set_default))
                                    <td class="viewDefaultPrice_2">
                                        <span class="btn btn-primary">Default Rate</span>
                                    </td>
                                @else
                                    <td class="setDefaultprice_2">
                                        <a href="#" onclick="setDefaultPrice(2);">Set Default</a>
                                    </td>
                                @endif
                            </tr>
                            <tr>
                                <td>{{ __('Weekly') }}</td>
                                <td>
                                    <div class="price-form-group">
                                        <span>$</span>
                                        <input type="number" step="any" min="0" name="weekly"
                                            class="form-control" value="{{ old('weekly', $row->weekly) }}"
                                            placeholder="{{ __('Weekly') }}">
                                    </div>
                                </td>
                                <td>
                                    <div class="price-form-group">
                                        <span>$</span>
                                        <input type="number" step="any" min="0" name="discounted_weekly"
                                            class="form-control"
                                            value="{{ old('discounted_weekly', $row->discounted_weekly ?? $row->weekly) }}"
                                            placeholder="{{ __('Weekly') }}">
                                    </div>
                                </td>

                                @if (old('weekly_price_set_default', $row->weekly_price_set_default))
                                    <td class="viewDefaultPrice_3">
                                        <span class="btn btn-primary">Default Rate</span>
                                    </td>
                                @else
                                    <td class="setDefaultprice_3">
                                        <a href="#" onclick="setDefaultPrice(3);">Set Default</a>
                                    </td>
                                @endif
                            </tr>
                            <tr>
                                <td>{{ __('Monthly') }}</td>
                                <td>
                                    <div class="price-form-group">
                                        <span>$</span>
                                        <input type="number" step="any" min="0" name="monthly"
                                            class="form-control" value="{{ old('monthly', $row->monthly) }}"
                                            placeholder="{{ __('Monthly') }}">
                                    </div>
                                </td>
                                <td>
                                    <div class="price-form-group">
                                        <span>$</span>
                                        <input type="number" step="any" min="0"
                                            name="discounted_monthly" class="form-control"
                                            value="{{ old('discounted_monthly', $row->discounted_monthly ?? $row->monthly) }}"
                                            placeholder="{{ __('Monthly') }}">
                                    </div>
                                </td>
                                @if (old('monthly_price_set_default', $row->monthly_price_set_default))
                                    <td class="viewDefaultPrice_4">
                                        <span class="btn btn-primary">Default Rate</span>
                                    </td>
                                @else
                                    <td class="setDefaultprice_4">
                                        <a href="#" onclick="setDefaultPrice(4);">Set Default</a>
                                    </td>
                                @endif
                            </tr>
                        </table>
                    </div>
                @endif

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="control-label">{{ __('Full Day After Hours') }}</label>
                            <div class="price-form-group">
                                <span>$</span>
                                <input type="number" step="any" name="hours_after_full_day"
                                    class="form-control"
                                    value="{{ old('hours_after_full_day', $row->hours_after_full_day) }}"
                                    placeholder="{{ __('Full Day After Hours') }}">
                            </div>
                            <span><i>{{ __('If hours are more than this, it will be treated as full day') }}</i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group @if (!is_default_lang()) d-none @endif">
                    <label><input type="checkbox" name="enable_extra_price"
                            @if (!empty(old('enable_extra_price', $row->enable_extra_price))) checked @endif value="1">
                        {{ __('Enable extra price') }}
                    </label>
                </div>
                <div class="form-group-item @if (!is_default_lang()) d-none @endif"
                    data-condition="enable_extra_price:is(1)">
                    <label class="control-label">{{ __('Extra Price') }}</label>
                    <div class="g-items-header">
                        <div class="row">
                            <div class="col-md-5">{{ __('Name') }}</div>
                            <div class="col-md-3">{{ __('Price') }}</div>
                            <div class="col-md-3">{{ __('Type') }}</div>
                            <div class="col-md-1"></div>
                        </div>
                    </div>
                    <div class="g-items">
                        @if (!empty($row->extra_price))
                            @foreach ($row->extra_price as $key => $extra_price)
                                <div class="item" data-number="{{ $key }}">
                                    <div class="row">
                                        <div class="col-md-5">
                                            @if (!empty($languages) && setting_item('site_enable_multi_lang') && setting_item('site_locale'))
                                                @foreach ($languages as $language)
                                                    <?php $key_lang = setting_item('site_locale') != $language->locale ? '_' . $language->locale : ''; ?>
                                                    <div class="g-lang gLangHide" data-lang="{{ $key_lang }}">
                                                        <div class="title-lang">{{ $language->name }}</div>
                                                        <input type="text"
                                                            name="extra_price[{{ $key }}][name{{ $key_lang }}]"
                                                            class="form-control"
                                                            value="{{ $extra_price['name' . $key_lang] ?? '' }}"
                                                            placeholder="{{ __('Extra price name') }}">
                                                    </div>
                                                @endforeach
                                            @else
                                                <input type="text" name="extra_price[{{ $key }}][name]"
                                                    class="form-control" value="{{ $extra_price['name'] ?? '' }}"
                                                    placeholder="{{ __('Extra price name') }}">
                                            @endif
                                        </div>
                                        <div class="col-md-3">
                                            <div class="price-form-group">
                                                <span>$</span>
                                                <input type="number"
                                                    @if (!is_default_lang()) disabled @endif min="0"
                                                    name="extra_price[{{ $key }}][price]"
                                                    class="form-control" value="{{ $extra_price['price'] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3 same-line-price-col">
                                            <select name="extra_price[{{ $key }}][type]"
                                                class="form-control"
                                                @if (!is_default_lang()) disabled @endif>
                                                <option @if ($extra_price['type'] == 'one_time') selected @endif
                                                    value="one_time">{{ __('One-time') }}</option>
                                                <option @if ($extra_price['type'] == 'per_hour') selected @endif
                                                    value="per_hour">{{ __('Per hour') }}</option>
                                                <option @if ($extra_price['type'] == 'per_day') selected @endif
                                                    value="per_day">
                                                    {{ __('Per day') }}</option>
                                            </select>

                                            <label>
                                                <input @if (!is_default_lang()) disabled @endif
                                                    type="checkbox" min="0"
                                                    name="extra_price[{{ $key }}][per_person]"
                                                    value="on" @if ($extra_price['per_person'] ?? '') checked @endif>
                                                {{ __('Price per person') }}

                                            </label>
                                        </div>
                                        <div class="col-md-1">
                                            @if (is_default_lang())
                                                <span class="btn btn-danger btn-sm btn-remove-item"><i
                                                        class="fa fa-trash"></i></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="text-right">
                        @if (is_default_lang())
                            <span class="btn btn-info btn-sm btn-add-item"><i
                                    class="icon ion-ios-add-circle-outline"></i>
                                {{ __('Add item') }}</span>
                        @endif
                    </div>
                    <div class="g-more hide">
                        <div class="item" data-number="__number__">
                            <div class="row">
                                <div class="col-md-5">
                                    @if (!empty($languages) && setting_item('site_enable_multi_lang') && setting_item('site_locale'))
                                        @foreach ($languages as $language)
                                            <?php $key = setting_item('site_locale') != $language->locale ? '_' . $language->locale : ''; ?>
                                            <div class="g-lang gLangHide" data-lang="{{ $key }}">
                                                <div class="title-lang">{{ $language->name }}</div>
                                                <input type="text"
                                                    __name__="extra_price[__number__][name{{ $key }}]"
                                                    class="form-control" value=""
                                                    placeholder="{{ __('Extra price name') }}">
                                            </div>
                                        @endforeach
                                    @else
                                        <input type="text" __name__="extra_price[__number__][name]"
                                            class="form-control" value=""
                                            placeholder="{{ __('Extra price name') }}">
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <div class="price-form-group">
                                        <span>$</span>
                                        <input type="number" min="0"
                                            __name__="extra_price[__number__][price]" class="form-control"
                                            value="">
                                    </div>
                                </div>
                                <div class="col-md-3 same-line-price-col">
                                    <select __name__="extra_price[__number__][type]" class="form-control">
                                        <option value="one_time">{{ __('One-time') }}</option>
                                        <option value="per_hour">{{ __('Per hour') }}</option>
                                        <option value="per_day">{{ __('Per day') }}</option>
                                    </select>

                                    <label>
                                        <input type="checkbox" min="0"
                                            __name__="extra_price[__number__][per_person]" value="on">
                                        {{ __('Price per person') }}
                                    </label>
                                </div>
                                <div class="col-md-1">
                                    <span class="btn btn-danger btn-sm btn-remove-item"><i
                                            class="fa fa-trash"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (is_default_lang())
                    <hr class="d-nonde">
                    <h3 class="panel-body-title d-none">{{ __('Discount by number of people') }}</h3>
                    <div class="form-group-item d-none">
                        <div class="g-items-header">
                            <div class="row">
                                <div class="col-md-4">{{ __('No of people') }}</div>
                                <div class="col-md-3">{{ __('Discount') }}</div>
                                <div class="col-md-3">{{ __('Type') }}</div>
                                <div class="col-md-1"></div>
                            </div>
                        </div>
                        <div class="g-items">
                            @if (!empty($row->discount_by_people) and is_array($row->discount_by_people))
                                @foreach ($row->discount_by_people as $key => $item)
                                    <div class="item" data-number="{{ $key }}">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="price-form-group">
                                                    <span>$</span>
                                                    <input type="number" min="0"
                                                        name="discount_by_people[{{ $key }}][from]"
                                                        class="form-control" value="{{ $item['from'] }}"
                                                        placeholder="{{ __('From') }}">

                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="price-form-group">
                                                    <span>$</span>
                                                    <input type="number" min="0"
                                                        name="discount_by_people[{{ $key }}][to]"
                                                        class="form-control" value="{{ $item['from'] }}"
                                                        placeholder="{{ __('To') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="price-form-group">
                                                    <span>$</span>
                                                    <input type="number" min="0"
                                                        name="discount_by_people[{{ $key }}][amount]"
                                                        class="form-control" value="{{ $item['amount'] }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <select name="discount_by_people[{{ $key }}][type]"
                                                    class="form-control">
                                                    <option @if ($item['type'] == 'fixed') selected @endif
                                                        value="fixed">{{ __('Fixed') }}</option>
                                                    <option @if ($item['type'] == 'percent') selected @endif
                                                        value="percent">{{ __('Percent (%)') }}</option>
                                                </select>
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
                            <span class="btn btn-info btn-sm btn-add-item"><i
                                    class="icon ion-ios-add-circle-outline"></i>
                                {{ __('Add item') }}</span>
                        </div>
                        <div class="g-more hide">
                            <div class="item" data-number="__number__">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="price-form-group">
                                            <span>$</span>
                                            <input type="number" min="0"
                                                __name__="discount_by_people[__number__][from]" class="form-control"
                                                value="" placeholder="{{ __('From') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="price-form-group">
                                            <span>$</span>
                                            <input type="number" min="0"
                                                __name__="discount_by_people[__number__][to]" class="form-control"
                                                value="" placeholder="{{ __('To') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="price-form-group">
                                            <span>$</span>
                                            <input type="number" min="0"
                                                __name__="discount_by_people[__number__][amount]" class="form-control"
                                                value="">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <select __name__="discount_by_people[__number__][type]" class="form-control">
                                            <option value="fixed">{{ __('Fixed') }}</option>
                                            <option value="percent">{{ __('Percent') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <span class="btn btn-danger btn-sm btn-remove-item"><i
                                                class="fa fa-trash"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if (is_default_lang() and (!empty(setting_item('space_allow_vendor_can_add_service_fee')) or is_admin()))
                    <hr>
                    <h3 class="panel-body-title app_get_locale">{{ __('Service fee') }}</h3>
                    <div class="form-group app_get_locale">
                        <label><input type="checkbox" name="enable_service_fee"
                                @if (!empty($row->enable_service_fee)) checked @endif value="1">
                            {{ __('Enable service fee') }}
                        </label>
                    </div>
                    <div class="form-group-item" data-condition="enable_service_fee:is(1)">
                        <label class="control-label">{{ __('Buyer Fees') }}</label>
                        <div class="g-items-header">
                            <div class="row">
                                <div class="col-md-5">{{ __('Name') }}</div>
                                <div class="col-md-3">{{ __('Price') }}</div>
                                <div class="col-md-3">{{ __('Type') }}</div>
                                <div class="col-md-1"></div>
                            </div>
                        </div>
                        <div class="g-items">
                            <?php $languages = \Modules\Language\Models\Language::getActive(); ?>
                            @if (!empty(($service_fee = $row->service_fee)))
                                @foreach ($service_fee as $key => $item)
                                    <div class="item" data-number="{{ $key }}">
                                        <div class="row">
                                            <div class="col-md-5">
                                                @if (!empty($languages) && setting_item('site_enable_multi_lang') && setting_item('site_locale'))
                                                    @foreach ($languages as $language)
                                                        <?php $key_lang = setting_item('site_locale') != $language->locale ? '_' . $language->locale : ''; ?>
                                                        <div class="g-lang gLangHide"
                                                            data-lang="{{ $key_lang }}">
                                                            <div class="title-lang">{{ $language->name }}</div>
                                                            <input type="text"
                                                                name="service_fee[{{ $key }}][name{{ $key_lang }}]"
                                                                class="form-control"
                                                                value="{{ $item['name' . $key_lang] ?? '' }}"
                                                                placeholder="{{ __('Fee name') }}">
                                                            <input type="text"
                                                                name="service_fee[{{ $key }}][desc{{ $key_lang }}]"
                                                                class="form-control"
                                                                value="{{ $item['desc' . $key_lang] ?? '' }}"
                                                                placeholder="{{ __('Fee desc') }}">
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <input type="text"
                                                        name="service_fee[{{ $key }}][name]"
                                                        class="form-control" value="{{ $item['name'] ?? '' }}"
                                                        placeholder="{{ __('Fee name') }}">
                                                    <input type="text"
                                                        name="service_fee[{{ $key }}][desc]"
                                                        class="form-control" value="{{ $item['desc'] ?? '' }}"
                                                        placeholder="{{ __('Fee desc') }}">
                                                @endif
                                            </div>
                                            <div class="col-md-3">
                                                <div class="price-form-group">
                                                    <span>$</span>
                                                    <input type="number" min="0" step="0.1"
                                                        name="service_fee[{{ $key }}][price]"
                                                        class="form-control" value="{{ $item['price'] ?? '' }}">
                                                </div>
                                                <select name="service_fee[{{ $key }}][unit]"
                                                    class="form-control">
                                                    <option @if (($item['unit'] ?? '') == 'fixed') selected @endif
                                                        value="fixed">{{ __('Fixed') }}</option>
                                                    <option @if (($item['unit'] ?? '') == 'percent') selected @endif
                                                        value="percent">{{ __('Percent') }}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 same-line-price-col">
                                                <select name="service_fee[{{ $key }}][type]"
                                                    class="form-control d-none">
                                                    <option @if ($item['type'] ?? '' == 'one_time') selected @endif
                                                        value="one_time">{{ __('One-time') }}</option>
                                                </select>
                                                <label>
                                                    <input type="checkbox" min="0"
                                                        name="service_fee[{{ $key }}][per_person]"
                                                        value="on"
                                                        @if ($item['per_person'] ?? '') checked @endif>
                                                    {{ __('Price per person') }}
                                                </label>
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
                            <span class="btn btn-info btn-sm btn-add-item"><i
                                    class="icon ion-ios-add-circle-outline"></i>
                                {{ __('Add item') }}</span>
                        </div>
                        <div class="g-more hide">
                            <div class="item" data-number="__number__">
                                <div class="row">
                                    <div class="col-md-5">
                                        @if (!empty($languages) && setting_item('site_enable_multi_lang') && setting_item('site_locale'))
                                            @foreach ($languages as $language)
                                                <?php $key = setting_item('site_locale') != $language->locale ? '_' . $language->locale : ''; ?>
                                                <div class="g-lang gLangHide" data-lang="{{ $key }}">
                                                    <div class="title-lang">{{ $language->name }}</div>
                                                    <input type="text"
                                                        __name__="service_fee[__number__][name{{ $key }}]"
                                                        class="form-control" value=""
                                                        placeholder="{{ __('Fee name') }}">
                                                    <input type="text"
                                                        __name__="service_fee[__number__][desc{{ $key }}]"
                                                        class="form-control" value=""
                                                        placeholder="{{ __('Fee desc') }}">
                                                </div>
                                            @endforeach
                                        @else
                                            <input type="text" __name__="service_fee[__number__][name]"
                                                class="form-control" value=""
                                                placeholder="{{ __('Fee name') }}">
                                            <input type="text" __name__="service_fee[__number__][desc]"
                                                class="form-control" value=""
                                                placeholder="{{ __('Fee desc') }}">
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <div class="price-form-group">
                                            <span>$</span>
                                            <input type="number" min="0" step="0.1"
                                                __name__="service_fee[__number__][price]" class="form-control"
                                                value="">
                                        </div>
                                        <select __name__="service_fee[__number__][unit]" class="form-control">
                                            <option value="fixed">{{ __('Fixed') }}</option>
                                            <option value="percent">{{ __('Percent') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 same-line-price-col">
                                        <select __name__="service_fee[__number__][type]" class="form-control d-none">
                                            <option value="one_time">{{ __('One-time') }}</option>
                                        </select>
                                        <label>
                                            <input type="checkbox" min="0"
                                                __name__="service_fee[__number__][per_person]" value="on">
                                            {{ __('Price per person') }}
                                        </label>
                                    </div>
                                    <div class="col-md-1">
                                        <span class="btn btn-danger btn-sm btn-remove-item"><i
                                                class="fa fa-trash"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="panel">
            <div class="panel-title"><strong>{{ __('Discount Codes') }}</strong></div>
            <div class="panel-body">
                <div class="form-group-item">
                    <div class="g-items-header">
                        <div class="row">
                            <div class="col-md-4">{{ __('Coupon Code') }}</div>
                            <div class="col-md-3">{{ __('Amount') }}</div>
                            <div class="col-md-3">{{ __('Type') }}</div>
                            <div class="col-md-1"></div>
                        </div>
                    </div>
                    <div class="g-items">
                        @if (!empty($row->coupons) and count($row->coupons) > 0)
                            @foreach ($row->coupons as $key => $item)
                                <div class="item" data-number="{{ $key }}">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="text" min="0"
                                                name="coupons[{{ $key }}][code]" class="form-control"
                                                value="{{ $item['code'] }}" placeholder="{{ __('From') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <div class="price-form-group">
                                                <span>$</span>
                                                <input type="number" min="0"
                                                    name="coupons[{{ $key }}][amount]" class="form-control"
                                                    value="{{ $item['amount'] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="coupons[{{ $key }}][type]" class="form-control">
                                                <option @if ($item['discount_type'] == 'fixed') selected @endif
                                                    value="fixed">
                                                    {{ __('Fixed') }}</option>
                                                <option @if ($item['discount_type'] == 'percent') selected @endif
                                                    value="percent">{{ __('Percent (%)') }}</option>
                                            </select>
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
                                <div class="col-md-4">
                                    <input type="text" min="0" __name__="coupons[__number__][code]"
                                        class="form-control" value="" placeholder="{{ __('Code') }}">
                                </div>
                                <div class="col-md-4">
                                    <div class="price-form-group">
                                        <span>$</span>
                                        <input type="number" min="0" __name__="coupons[__number__][amount]"
                                            class="form-control" value="">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select __name__="coupons[__number__][type]" class="form-control">
                                        <option value="fixed">{{ __('Fixed') }}</option>
                                        <option value="percent">{{ __('Percent') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <span class="btn btn-danger btn-sm btn-remove-item"><i
                                            class="fa fa-trash"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif
</div>
