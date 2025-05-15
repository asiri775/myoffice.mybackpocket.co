<div class="filter-item filter-simple">
    <div class="form-group dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        <h3 class="filter-title">No. of Guests<i class="fa fa-angle-down"></i></h3>
    </div>
    <div class="filter-dropdown dropdown-menu dropdown-menu-right" x-placement="bottom-end"
         style="position: absolute; transform: translate3d(50px, 42px, 0px); top: 0px; left: 0px; will-change: transform;">
        <ul class="plusminus_ul">
            <li data-min="1" class="adult">
                <span class="textlabel">No. of Guests</span>
                <div class="plusminus">
                    <span class="minus disabled">-</span>
                    <input oninput="this.value = !!this.value && Math.abs(this.value) >= 1 ? Math.abs(this.value) : 1"
                    type="number" id="g_adult_guests" name="adults" value="{{Request::query('adults') ?? 1}}">
                    <span class="plus">+</span>
                </div>
            </li>
    
        </ul>
        <div class="dropdiv mt-5 mb-3">
            <div class="left droptext">
                <a href="#" class="btn btn-dark whitetext cancel"
                   data-toggle="dropdown">Cancel</a>
            </div>
            <div class="right">
                <a href="#" onclick="return false;"
                   class="btn btn-primary btn-sm btn-apply-advances">{{__("APPLY")}}</a>
            </div>
        </div>
    </div>
</div>
