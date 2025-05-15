<div class="bravo-form-search-all">
    @if(in_array($style,['carousel','']))
        @include("Template::frontend.blocks.form-search-all-service.style-normal")
    @endif
    @if($style == "carousel_v2")
        @include("Template::frontend.blocks.form-search-all-service.style-slider-ver2")
    @endif
</div>
