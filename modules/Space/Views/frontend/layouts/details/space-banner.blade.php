@if($row->banner_image_id)
<div class="bravo_banner" style="background-image: url('{{$row->getBannerImageUrlAttribute('full')}}')">
    <div class="container">
        <div class="bravo_gallery">

        </div>
    </div>
</div>

@endif

