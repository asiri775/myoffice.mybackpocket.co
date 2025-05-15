<style>
    div#spaceGallery {
        display: flex;
        flex-wrap: wrap;
    }

    .attach-demo-select img {
        height: 200px;
        width: auto;
    }

    .chosse-gallery-image-item {
        flex: 0 0 50%;
        cursor: pointer;
        padding: 7.5px;
    }

    .chosse-gallery-image-item .wrapper {
        border: 1px solid #eee;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f9f9f9;
    }

    .chosse-gallery-image-item .wrapper img {
        max-width: 100%;
        height: auto;
        margin: 0 auto;
    }

    .attach-demo-select {
        display: block;
        background: #fafbfc;
        text-align: center;
        border: 1px solid rgba(195, 207, 216, .3);
        transition: all .2s;
        cursor: pointer;
        overflow: hidden;
        position: relative;
        padding: 20px;
    }

    a.delete-select {
        position: absolute;
        top: 15px;
        right: 15px;
        color: #fff;
    }

    .dungdt-upload-box:not(.active) .attach-demo-select {
        display: none;
    }
</style>

<div class="space-form-box">
    <div class="panel">
        <div class="panel-title"><strong>{{ __('Media') }}</strong></div>
        <div class="panel-body">

            <div class="row">
                <div class="col-md-12 col-12">
                    <p>Upload your Gallery of Images to use for your Space. Note that for best results, use the
                        following specifications for special images. After choosing images, click <b>"Save Changes"</b>
                        to update all.
                        </br></br>
                        <b>Featured image</b> (this is the main image on your listing details page):
                        600x400px, 1200 DPI resolution, Max 100kb
                        </br></br>
                        <b>Banner image</b> (this is the main image on your listing details page):
                        600x400px, 1200 DPI resolution, Max 100kb
                    </p>
                </div>
            </div>

        </div>
    </div>

    <div class="panel">
        <div class="panel-title"><strong>1. Gallery</strong></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12 col-12">
                    @if (is_default_lang())
                        <div class="form-group">
                            {!! \Modules\Media\Helpers\FileHelper::fieldGalleryUpload('gallery', $row->gallery) !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-title"><strong>2. Featured Image</strong></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12 col-12">
                    @if (is_default_lang())
                        <div class="form-group">
                            {!! \Modules\Media\Helpers\FileHelper::fieldUploadSpace('image_id', $row->image_id) !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-title"><strong>3. Tile/Banner Image</strong></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12 col-12">
                    @if (is_default_lang())
                        <div class="form-group">
                            {!! \Modules\Media\Helpers\FileHelper::fieldUploadSpace('banner_image_id', $row->banner_image_id) !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div class="panel d-none">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12 col-12">
                    @if (is_default_lang())
                        <div class="form-group d-none">
                            <label class="control-label">{{ __('Youtube Video') }}</label>
                            <input type="text" name="video" class="form-control"
                                value="{{ old('video', $row->video) }}" placeholder="{{ __('Youtube link video') }}">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="spaceGalleryImagesModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Space Gallery</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="spaceGallery"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function resetIds() {
            var ids = [];
            $("#sortableItems .image-item").each(function() {
                ids.push($(this).find(".edit-img").attr("data-id"));
            });
            console.log(ids);
            $("#sortableItems").parent().find('input[type="hidden"]').val(ids.join(","));
        }

        setTimeout(() => {
            $("#sortableItems").sortable({
                sort: function(event, ui) {
                    setTimeout(() => {
                        resetIds();
                    }, 500);
                }
            });
        }, 2000);

        var mainBox = null;

        $(document).ready(function() {
            $(".chooseFromSpaceGallery").closest(".dungdt-upload-box").find(".edit-img").hide();
        });

        $(document).on("click", ".chosse-gallery-image-item", function() {
            var imageId = $(this).attr("data-id");
            var imageUrl = $(this).find("img").attr("src");

            mainBox.addClass("active").attr("data-val", imageId);
            mainBox.find('input[type="hidden"]').val(imageId);
            mainBox.find(".attach-demo-select img").attr("src", imageUrl);
            mainBox.find(".edit-img").attr("data-file", imageUrl);

            $("#spaceGalleryImagesModal").modal("hide");
        });

        function loadSpaceGallery() {
            $("#spaceGallery").html("");
            $(".image-item").each(function() {
                const mId = $(this).find(".edit-img").attr("data-id");
                const mImage = $(this).find(".edit-img").attr("data-file");
                $("#spaceGallery").append(
                    `<div class="chosse-gallery-image-item" data-id="${mId}"><img src="${mImage}"/></div>`);
            });
        }

        $(document).on("click", ".chooseFromSpaceGallery, .attach-demo-select", function() {
            loadSpaceGallery();
            $("#spaceGalleryImagesModal").modal("show");
            mainBox = $(this).closest(".dungdt-upload-box");
        });

        $(document).on("click", ".delete-select", function() {
            mainBox = $(this).closest(".dungdt-upload-box");

            mainBox.removeClass("active").attr("data-val", "");
            mainBox.find('input[type="hidden"]').val("");
            mainBox.find(".attach-demo-select img").attr("src", "");
            mainBox.find(".edit-img").attr("data-file", "");

        });
    </script>
</div>
