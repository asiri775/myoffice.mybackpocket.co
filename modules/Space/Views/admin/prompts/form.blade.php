<?php
$type = $type ?? 'general';
$model = $model ?? new \App\Models\AiPrompts();
?>
<div id="promptFormModal{{$model ? $model->id: ''}}" class="modal fade">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" data-refresh="reloadPrompts" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Prompt') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="adminAjaxForm row form_modal_calendar form-horizontal" method="post"
                    action="{{ route('admin.savePrompt') }}">
                    <div class="col-md-12">
                        <input type="hidden" name="id" value="{{old('id', $model->id)}}" id="aiPromptId">
                        <input type="hidden" name="type" value="{{ old('id', $model->type ? $model->type : $type) }}">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" required="" class="form-control"
                                placeholder="Give prompt a name" value="{{old('name', $model->name)}}" />
                        </div>
                        <div class="form-group">
                            <div class="form-controls">
                                <textarea required name="prompt" class="form-control" cols="30" rows="15">{{old('prompt', $model->prompt)}}</textarea>
                            </div>
                            <div class="help-variables">
                                <span class="badge badge-primary">{title}</span>
                                <span class="badge badge-primary">{city}</span>
                                <span class="badge badge-primary">{state}</span>
                                <span class="badge badge-primary">{country}</span>
                                <span class="badge badge-primary">{streetAddress}</span>
                                <span class="badge badge-primary">{desks}</span>
                                <span class="badge badge-primary">{maxGuests}</span>
                                <span class="badge badge-primary">{latitude}</span>
                                <span class="badge badge-primary">{longitude}</span>
                                <span class="badge badge-primary">{categories}</span>
                                <span class="badge badge-primary">{amenities}</span>
                                <span class="badge badge-primary">{addonServices}</span>
                                <span class="badge badge-primary">{availableFrom}</span>
                                <span class="badge badge-primary">{availableTo}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="display: flex; justify-content:flex-end;">
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
