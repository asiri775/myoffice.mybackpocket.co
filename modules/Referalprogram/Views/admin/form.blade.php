<div class="panel">
    <div class="panel-title"><strong>{{__("General")}}</strong></div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__("Referal Uri")}} <span class="text-danger">*</span></label>
                    <input type="text" maxlength="50" required value="{{$row->uri}}" placeholder="{{__("Uri")}}" name="uri" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__("Referal Name")}} </label>
                    <input type="text" value="{{$row->name}}" placeholder="{{__("Name")}}" name="name" class="form-control">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__("Referal Amount")}} <span class="text-danger">*</span></label>
                    <input type="number" required step="0.1" min="0" value="{{$row->amount}}" placeholder="0" name="amount" class="form-control">
                </div>
            </div>
        </div>
    </div>
</div>