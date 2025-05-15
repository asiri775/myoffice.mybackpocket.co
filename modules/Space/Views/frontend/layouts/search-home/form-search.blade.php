<form action="{{ route("space.search") }}" class="form bravo_form" method="get">
    <input type="hidden" name="_layout" value="map"/>
    <div class="g-field-search">
        <div class="row">
            @php $space_search_fields = setting_item_array('space_search_fields');
            $space_search_fields = array_values(\Illuminate\Support\Arr::sort($space_search_fields, function ($value) {
                return $value['position'] ?? 0;
            }));
            @endphp
            @if(!empty($space_search_fields))
                @foreach($space_search_fields as $field)
                    @php $field['title'] = $field['title_'.app()->getLocale()] ?? $field['title'] ?? "" @endphp
                    <div class="col-md-{{ $field['size'] ?? "6" }} border-right">
                        @switch($field['field'])
                            @case ('service_name')
                                @include('Space::frontend.layouts.search-home.fields.service_name')
                            @break
                            @case ('location')
                                @include('Space::frontend.layouts.search-home.fields.location')
                            @break
                            @case ('date')
                                @include('Space::frontend.layouts.search-home.fields.date')
                            @break
                            @case ('time')
                                @include('Space::frontend.layouts.search-home.fields.time')
                            @break
                            @case ('attr')
                                @include('Space::frontend.layouts.search-home.fields.attr')
                            @break
                            @case ('guests')
                                @include('Space::frontend.layouts.search-home.fields.guests')
                            @break
                        @endswitch
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="g-button-submit">
        <button class="btn btn-primary btn-search" type="submit">{{__("Search")}}</button>
    </div>
</form>
