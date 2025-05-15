<div class="space-form-box">
    <?php
    if (!isset($selected_terms)) {
        $selected_terms = new \Illuminate\Support\Collection(old('terms', []));
    }
    ?>
    @foreach ($attributes as $attribute)
        <?php
if($attribute->slug != "space-type"){
?>
        <div class="panel">
            <div class="panel-title with-actions">
                <strong data-slug="{{$attribute->slug}}">{{ __(':name', ['name' => $attribute->name]) }}</strong>
                <a href="javascript:;" class="btn btn-primary btn-sm select-all-amenity">Select All</a>
                <a href="javascript:;" class="btn btn-primary btn-sm deselect-all-amenity">Deselect All</a>
            </div>
            <div class="panel-body">
                <div class="terms-scrollable terms-scrollable-ux">
                    @foreach ($attribute->terms as $term)
                        <label class="term-item">
                            <input @if (!empty($selected_terms) and $selected_terms->contains($term->id)) checked @endif type="checkbox" name="terms[]"
                                value="{{ $term->id }}">
                            <span class="term-name">{{ $term->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
        <?php } ?>
    @endforeach


    <script>
        $(document).on("click", ".select-all-amenity", function() {
            var obj = $(this);
            var panelBody = obj.closest(".panel").find(".panel-body");
            panelBody.find('input[type="checkbox"]').each(function() {
                $(this).prop("checked", true);
            });
        });
        $(document).on("click", ".deselect-all-amenity", function() {
            var obj = $(this);
            var panelBody = obj.closest(".panel").find(".panel-body");
            panelBody.find('input[type="checkbox"]').each(function() {
                $(this).prop("checked", false);
            });
        });
    </script>
</div>
