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

<div class="filter-item filter-simple dropdown">
    <div class="filterinp instantbook_filter">
        <div class="form-group dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <h3 class="filter-title">Office Type <i class="fa fa-angle-down"></i></h3>
        </div>
        <div class="filter-dropdown dropdown-menu dropdown-menu-right" x-placement="bottom-end"
             style="position: absolute; transform: translate3d(50px, 42px, 0px); top: 0px; left: 0px; will-change: transform;">
            @foreach ($attributes as $item)
                @if($item['slug'] == 'space-type')
                    <ul class="checkbox_ul">
                        @php $count = 1 @endphp
                        @foreach($item->terms as $term)
                            @php $translate = $term->translateOrOrigin(app()->getLocale()); @endphp
                            <li>
                                <input @if(in_array($term->id,$selected)) checked @endif class="cat_check "
                                       id="office_type_{{ $count }}" name="terms[]"
                                       type="checkbox" value="{{$term->id}}">
                                <label class="textlabel" for="office_type_{{ $count }}">{{$translate->name}}</label>
                            </li>
                            @php $count++ @endphp
                        @endforeach
                    </ul>
                @endif
            @endforeach
            <div class="dropdiv mt-3 mb-3">
                <div class="left droptext">
                    <a href="#" class="btn btn-dark whitetext cancel"
                       data-toggle="dropdown">Cancel</a>
                </div>
                <div class="right">
                    <a href="#" onclick="return false;"
                       class="btn btn-primary btn-sm btn-apply-advances">{{__("APPLY")}}</a>
                </div>
            </div>
        </div>
    </div>
</div>
