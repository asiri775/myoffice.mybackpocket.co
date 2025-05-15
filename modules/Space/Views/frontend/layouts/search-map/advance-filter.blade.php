@php
    $space_map_search_fields = setting_item_array('space_map_search_fields');
    $usedAttrs = [];
    foreach ($space_map_search_fields as $field){
        if($field['field'] == 'attr' and !empty($field['attr']))
        {
            $usedAttrs[] = $field['attr'];
        }
    }
    $selected = (array) request()->query('terms');
@endphp

<div id="advance_filters" style="z-index: 12;" class="d-none">
    <div class="ad-filter-b">
        <div class="filter-item">
            <h2 class="mt-3 mb-3">Desks and Seats</h2>
            <ul class="plusminus_ul">
                <li data-max="5" data-min="0" class="adult">
                    <span class="textlabel">Desks</span>
                    <div class="plusminus">
                        <span class="minus disabled">-</span>
                        <input oninput="this.value = Math.abs(this.value)"
                               id="desk" type="number" name="desk" value="0">
                        <span class="plus">+</span>
                    </div>
                </li>
                <li data-max="5" data-min="0" class="children">
                    <span class="textlabel">Seats</span>
                    <div class="plusminus">
                        <span class="minus disabled">-</span>
                        <input oninput="this.value = Math.abs(this.value)"
                               id="seat" type="number" name="seat" value="0">
                        <span class="plus">+</span>
                    </div>
                </li>

            </ul>
            <hr>
            <h2 class="mt-4 mb-4">More options</h2>
            <ul class="toggle_ul mb-4">
                <li>
                    <div class="dropdiv">
                        <div class="left droptext">
                            <h3>SuperHost</h3>
                            <p>Book your Space with recognized and popular Hosts</p>
                        </div>
                        <div class="right">
                            <label class="switch">
                                <input name="super_host" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="dropdiv">
                        <div class="left droptext">
                            <h3>Accessibility</h3>
                            <p>Find a Space that addresses your mobility requirements</p>
                        </div>
                        <div class="right">
                            <label class="switch">
                                <input name="accessibility" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="dropdiv">
                        <div class="left droptext">
                            <h3>Free cancellation</h3>
                            <p>Show Space that allow you to cancel for free within 48 hours of
                                booking.</p>
                        </div>
                        <div class="right">
                            <label class="switch">
                                <input name="free_cancellation" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                </li>
            </ul>
            <hr>
            @foreach ($attributes as $item)
                @if($item['slug'] == 'amenities')
                    @php
                        if(in_array($item->id,$usedAttrs)) continue;
                            $translate = $item->translateOrOrigin(app()->getLocale());
                    @endphp

                    <h2 class="mt-4 mb-4">{{$translate->name}}</h2>
                    <ul class="checkbox_ul" data-text="Office Type">
                        @php $count = 1 @endphp
                        @foreach($item->terms as $term)
                            @php $translate = $term->translateOrOrigin(app()->getLocale()); @endphp
                            <li>
                                <input @if(in_array($term->id,$selected)) checked @endif class="pull-left chkboxpadng"
                                       type="checkbox" value="{{$term->id}}"
                                       name="terms[]" id="amenities{{ $count }}">
                                <label for="amenities{{ $count }}" class="textlabel">{{$translate->name}}</label>
                            </li>
                            @php $count++ @endphp
                        @endforeach
                    </ul>
                @endif
            @endforeach
        </div>
    </div>
    <div class="ad-filter-f text-right">
        <a href="#" onclick="return false" class="btn btn-primary btn-apply-advances">Apply
            Filters</a>
    </div>
</div>
