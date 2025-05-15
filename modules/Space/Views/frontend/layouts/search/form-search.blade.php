
<style>
    .bravo_wrap .bravo_form .input-search .form-control[readonly], .bravo_wrap .bravo_form .input-search .parent_text[readonly], .bravo_wrap .bravo_form .smart-search .form-control[readonly], .bravo_wrap .bravo_form .smart-search .parent_text[readonly],
    .bravo_wrap .bravo_form .form-content .date-wrapper .check-in-wrapper .check-in-render, .bravo_wrap .bravo_form .form-content .date-wrapper .check-in-wrapper .check-out-render,
    .wrapper-more span {
        font-size: 13px;
    }
</style>
<?php
$col = 'col-md-3';
$showHours = true;
if (isset($_GET['long_term_rental']) && $_GET['long_term_rental'] == 1) {
    $showHours = false;
    $col = 'col-md-4';
}
?>
<form action="{{ route("space.search") }}" class="form bravo_form" method="get">
    @if(isset($_GET['long_term_rental']))
    <input type="hidden" name="long_term_rental" value="{{$_GET['long_term_rental']}}">
@endif
@if(isset($_GET['have']))
    <input type="hidden" name="have" value="{{$_GET['have']}}">
@endif
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
                                @include('Space::frontend.layouts.search.fields.service_name')
                            @break
                            @case ('location')
                                @include('Space::frontend.layouts.search.fields.location')
                            @break
                            @case ('date')
                                @include('Space::frontend.layouts.search.fields.date')
                            @break
                            @case ('attr')
                                @include('Space::frontend.layouts.search.fields.attr')
                            @break
                            @case ('guests')
                                @include('Space::frontend.layouts.search.fields.guests')
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