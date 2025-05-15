<div class="panel">
    <div class="panel-body pt-0">
        <div class="row">
            <div class="col-md-12 col-12">
                <div class="panel-title pl-0"><strong>{{ __('Description') }}</strong></div>
                <div class="form-group">
                    <div class="control-inline-label">
                        <label class="control-label">{{ __('Description') }}</label>
                        <a style="text-transform: uppercase;" href="javascript:;" id="desciprionGenerator">Try Our Description Generator</a>
                    </div>
                    <div class="">
                        <textarea id="spaceDescContent" name="content" class="d-none has-ckeditor" cols="30" rows="10">{{ old('content', $translation->content) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    $(document).on("click", "#desciprionGenerator", function() {
        const oldData = $("#desciprionGenerator").html();

        const form = $("#desciprionGenerator").closest("form");
        var formData = new FormData(form[0]);
        const allFormData = {};

        for (var [key, value] of formData.entries()) {
            if (allFormData[key]) {
                if (Array.isArray(allFormData[key])) {
                    allFormData[key].push(value);
                } else {
                    allFormData[key] = [allFormData[key], value];
                }
            } else {
                allFormData[key] = value;
            }
        }

        $("#desciprionGenerator").html("Please wait...");

        try {
            $.post("{{ route('spaceContentGenerator') }}", {
                ...allFormData
            }, function(data) {
                $("#desciprionGenerator").html(oldData);
                if (data.status === "success") {
                    tinymce.get('spaceDescContent').setContent(data.content);
                } else {
                    window.webAlerts.push({
                        type: "error",
                        message: data.message
                    });
                }
            });
        } catch (err) {
            console.log(err);
            $("#desciprionGenerator").html(oldData);
        }
        
    });
</script>
