{{-- <div class="filter-item filter-simple dropdown">
    <div class="filterinp instantbook_filter">
        <div class="form-group dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
             aria-expanded="false">
            <h3 class="filter-title">RapidBook <i class="fa fa-angle-down"></i></h3>
        </div>
        <div class="filter-dropdown dropdown-menu dropdown-menu-right" x-placement="bottom-end"
             style="position: absolute; transform: translate3d(50px, 42px, 0px); top: 0px; left: 0px; will-change: transform;">
            <div class="dropdiv">
                <div class="left droptext">
                    <h3>RapidBook</h3>
                    <p>Listings you can book without waiting for host approval</p>
                </div>
                <div class="right ml-2">
                    <label class="switch">
                        <input name="rapidbook" type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
            <div class="dropdiv">
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
</div>
 --}}


<div class="filter-item filter-simple dropdown">
    <div class="filterinp instantbook_filter">
        <div class="form-group dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <h3 class="filter-title">Top Rated <i class="fa fa-angle-down"></i></h3>
        </div>
        <div class="filter-dropdown dropdown-menu dropdown-menu-right" x-placement="bottom-end"
            style="position: absolute; transform: translate3d(50px, 42px, 0px); top: 0px; left: 0px; will-change: transform;">
            <div class="dropdiv">
                <div class="left droptext">
                    <h3>Top Rated</h3>
                    <p>Search top rated Spaces around</p>
                </div>
                <div class="right ml-2">
                    <label class="switch">
                        <input name="top_rated" value="1" type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
            <div class="dropdiv">
                <div class="left droptext">
                    <a href="#" class="btn btn-dark whitetext cancel" data-toggle="dropdown">Cancel</a>
                </div>
                <div class="right">
                    <a href="#" onclick="return false;"
                        class="btn btn-primary btn-sm btn-apply-advances">{{ __('APPLY') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
