<?php
$type = $type ?? 'general';
$name = $name ?? 'general';
?>

<div class="d-flex justify-content-between mb20">
    <h1 class="title-bar">{{ $name }} - Prompts</h1>
    <div class="title-actions">
        <a href="javascript:;" id="newPromptTrigger" class="btn btn-primary">Add New Prompt</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">

        <div class="tablefilters-box">

            <div class="tablefilters-box-filters card card-default card-bordered p-4 card-radious">

                <div class="table-filters p-0">
                    <div class="row data-search">
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Search</label>
                                        <input type="text" value="<?= isset($_GET['q']) ? $_GET['q'] : '' ?>"
                                            class="form-control filterField" name="q" id="filterSearch"
                                            placeholder="Enter Name/Prompt">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="full-width form-control filterField" id="booking_category"
                                            name="is_active">
                                            <option value="" selected>Select Status</option>
                                            <option value="1">Active</option>
                                            <option value="0">In-Active</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2 col-md-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="ActivateAdvanceSerach" class="control-label">&nbsp;</label>
                                    <button type="button" class="btn btn-primary w-100 new-padding form-control"
                                        id="doSearch">
                                        Search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div
                class="tablefilters-box-data card card-default card-bordered card-padding-body-zero p-4 mb-100 card-radious">

                <div class="card-body table-no-page-options">
                    <table class="table demo-table-search table-responsive-block data-table" id="promptsTable">
                        <thead>
                            <tr>
                            <tr>
                                <th>ID#</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Updated On</th>
                                <th style="text-align: center">Actions</th>
                            </tr>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>

    </div>
</div>

@include('Space::admin.prompts.form', ['type' => $type])

@section('script.body')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        $(document).ready(function(e) {
            var table = $('#promptsTable');
            $.fn.dataTable.ext.errMode = 'none';

            let dataTableOptions = {!! json_encode(\App\Helpers\CodeHelper::dataTableOptions()) !!};

            var datatable = table.DataTable({
                ...dataTableOptions,
                ajax: {
                    "url": "{{ route('admin.promptsDataTable') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "method": "POST",
                    'data': function(data) {
                        data['search_query'] = {
                            type: "{{ $type }}"
                        };
                        $(".filterField").each(function() {
                            let obj = $(this);
                            data['search_query'][obj.attr("name")] = obj.val();
                        });
                    }
                },
                "columns": [
                    // {
                    //     data: 'checkboxes',
                    //     name: 'checkboxes',
                    //     orderable: false,
                    //     searchable: false
                    // },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'status',
                        name: 'is_active',
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at',
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false
                    },
                ],
            });

            $(document).on("change", 'select.filterField', function() {
                datatable.draw();
            });

            $(document).on("keypress", 'input.filterField', function(e) {
                if (e.which == 13) {
                    datatable.draw();
                }
            });

            $(document).on("click", "#doSearch", function() {
                datatable.draw();
            });

            $(document).on("change", '.table-top-filter-length', function() {
                var obj = $(this);
                var tableLength = obj.val();
                obj.closest(".card").find('select[name="promptsTable_length"]').val(tableLength).trigger(
                    "change");
            });

            $(document).on("click", "#newPromptTrigger", function() {
                $("#promptFormModal").modal("show");
                $("#aiPromptId").val("");
            });

            $(document).on("click", "#editPromptTrigger", function() {
                var obj = $(this);
                $("#promptFormModal").modal("show");
            });

            $(document).on("click", ".edit-prompt-model", function() {
                var obj = $(this);
                $("#" + obj.attr("data-target")).modal("show");
            });

        });
    </script>
@endsection
