@php
    $terms_ids = $row->terms->pluck('term_id');
    $attributes = \Modules\Core\Models\Terms::getTermsById($terms_ids);
@endphp
@if (!empty($terms_ids) and !empty($attributes))
    @foreach ($attributes as $attribute)
        @php $translate_attribute = $attribute['parent']->translateOrOrigin(app()->getLocale()) @endphp
        @if (empty($attribute['parent']['hide_in_single']))
            <div class="g-attributes {{ $attribute['parent']->slug }} attr-{{ $attribute['parent']->id }}">
                <h3>{{ $translate_attribute->name }}</h3>
                @php $terms = $attribute['child'] @endphp
                <div class="list-attributes">
                    @foreach ($terms as $term)
                        @php $translate_term = $term->translateOrOrigin(app()->getLocale()) @endphp
                        <div class="item {{ $term->slug }} term-{{ $term->id }}">
                            @if (!empty($term->image_id))
                                @php $image_url = get_file_url($term->image_id, 'full'); @endphp
                                <img src="{{ $image_url }}" class="img-responsive" alt="{{ $translate_term->name }}">
                            @else
                                <i class="{{ $term->icon ?? 'icofont-check-circled icon-default' }}"></i>
                            @endif
                            {{ $translate_term->name }}
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
@endif

<h3>FACILITIES</h3>
<ul class="aminitlistingul mgnT20">
    @if (!empty($terms_ids) and !empty($attributes))

        @foreach ($attributes as $attribute)
            @php $translate_attribute = $attribute['parent']->translateOrOrigin(app()->getLocale()) @endphp
            @if (empty($attribute['parent']['hide_in_single']))

                @php $terms = $attribute['child'] @endphp
                @foreach ($terms as $term)
                    @php $translate_term = $term->translateOrOrigin(app()->getLocale()) @endphp
                    <li class="detaillistingli {{ (array_key_exists($translate_term->name, $icons)) ? "" : "not" }} fulwidthm mgnB10">@if (array_key_exists($translate_term->name, $icons)) <i class="aminti_icon {{ $icons[$translate_term->name] }}"></i> @endif <span
                            class="aminidis">{{ $translate_term->name }}</span></li>
                @endforeach
            @endif
        @endforeach
    @endif
</ul>
