@php
    $translation = $row->translateOrOrigin(app()->getLocale());
@endphp
<div class="col-xs-12 col-sm-4 mb-5">
    <div class="image" style="background-image:url('{{get_file_url($row->image_id,'medium')}}"></div>
    <h3>{!! clean($translation->title) !!}</h3>
    <div class="description">
        {!! $translation->content!!}
    </div>
</div>

