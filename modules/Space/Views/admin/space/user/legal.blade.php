<div class="panel">
    <div class="panel-title"><strong>{{ __('Legals') }}</strong></div>
    <div class="panel-body">
        <div class="form-group">
            <div class="control-inline-label">
                <label class="control-label">{{ __('House Rules') }}</label>
                <a href="javascript:;" id="loadDefaultHouseRules">Load Default Content</a>
            </div>
            <div class="">
                <textarea id="spaceHouseRules" name="house_rules" class="d-none has-ckeditor" cols="30" rows="10">{{ old('house_rules', $row->house_rules) }}</textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="control-inline-label">
                <label class="control-label">{{ __('Terms of Service') }}</label>
                <a href="javascript:;" id="loadDefaultTerms">Load Default Content</a>
            </div>
            <div class="">
                <textarea id="spaceTerms" name="tos" class="d-none has-ckeditor" cols="30" rows="10">{{ old('tos', $row->tos) }}</textarea>
            </div>
        </div>
    </div>
</div>  
