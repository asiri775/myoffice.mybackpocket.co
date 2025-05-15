<style>
    textarea {
        height: 100px;
    }
</style>


<div class="card card-default card-radious">
    <div class="card-header card-header-actions" style="padding-inline: 0 !important;">
        <div class="card-title">
            <div class="panel-title"><strong>{{ __('Reviews') }}</strong></div>
        </div>
        <div class="card-actions">
            <div class="table-top-filter-length-main">
                <span>Show</span>
                <select class="table-top-filter-length">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="-1">All</option>
                </select>
                <span>Records</span>
            </div>
        </div>
    </div>
    <div class="card-body table-no-page-options p-0">
        <table class="table demo-table-search table-responsive-block data-table" id="reviewsTableHistory">
            <thead>
                <tr>
                    {{-- <th>&nbsp;</th> --}}
                    <th style="width: 50px;">ID#</th>
                    <th  style="width: 50px;">Booking</th>
                    <th  style="width: 50px;">Date</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th  style="width: 50px;">Rating</th>
                    <th  style="width: 50px;">Review By</th>
                    <th  style="width: 50px;">Status</th>
                    <th  style="width: 50px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                </tr>
            </tbody>
        </table>

    </div>
</div>