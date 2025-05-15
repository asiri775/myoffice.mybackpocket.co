@extends('layouts.yellow_user')
@section('content')
    <?php
    $space_categories = \Modules\Core\Models\Attributes::where('service', 'space')->get();
    ?>
    <style>
        .input-sm {
            padding-top: 0px;
        }

        @media only screen and (min-width: 767px) {
            /* #favTable {
                max-width: calc(50% + 3rem);
            } */
        }
    </style>
    <div class="page-content-wrapper ">
        <!-- START PAGE CONTENT -->
        <div class="content sm-gutter">
            <!-- START BREADCRUMBS-->
            <div class="bg-white">
                <div class="container-fluid pl-5">
                    <ol class="breadcrumb breadcrumb-alt bg-white mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('user.profile.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Favourites</li>
                    </ol>
                </div>
            </div>

            <div class="container-fluid p-5" id="favTable" style="margin-left:0;margin-right:auto;">

                <div class="card card-default card-bordered p-4 card-radious">

                    <div class="table-filters p-0">
                        <div class="row data-search">
                            <div class="col-md-10">
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>Search</label>
                                        <input type="text" value="<?= isset($_GET['q']) ? $_GET['q'] : '' ?>"
                                            class="form-control filterField" name="q" id="filterSearch"
                                            placeholder="Enter ID/Title/Alias">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>City</label>
                                        <input type="text" name="city" class="form-control filterField"
                                            id="citySearchFilter" placeholder="Search City">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select class="full-width form-control filterField" id="booking_category"
                                            name="category">
                                            <option value="" selected>Select Category</option>
                                            @foreach ($space_categories[0]->terms as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>Rating</label>
                                        <input type="number" min="0" max="5" class="form-control filterField"
                                            name="review_score" placeholder="Enter Rating" id="filter_rating">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="ActivateAdvanceSerach" class="control-label">&nbsp;</label>
                                        <button type="button" class="btn btn-primary w-100 new-padding form-control"
                                            id="doSearch" style="padding: 0px;">
                                            Search
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card card-default card-bordered card-padding-body-zero p-4 mb-100 card-radious">
                    <div class="card-header card-header-actions">
                        <div class="card-title">
                            <h4 class="pt-1 text-uppercase">
                                <strong>
                                    Favourites
                                </strong>
                            </h4>
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
                    <div class="card-body table-no-page-options">
                        <table class="table demo-table-search table-responsive-block data-table" id="favouriteTable">
                            <thead>
                                <tr>
                                <tr>
                                    <th>ID#</th>
                                    <th>Title</th>
                                    <th>City</th>
                                    <th>Category</th>
                                    <th>Rating</th>
                                    <th>Actions</th>
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
            <!-- END CONTAINER FLUID -->
        </div>

    </div>
    {{--   loading the datatable --}}


    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        $(document).ready(function(e) {
            var table = $('#favouriteTable');
            $.fn.dataTable.ext.errMode = 'none';

            let dataTableOptions = {!! json_encode(\App\Helpers\CodeHelper::dataTableOptions()) !!};

            var datatable = table.DataTable({
                ...dataTableOptions,
                ajax: {
                    "url": "{{ route('user.favourites.datatable') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "method": "POST",
                    'data': function(data) {
                        data['search_query'] = {};
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
                        data: 'idLink',
                        name: 'id'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'city',
                        name: 'city',
                    },
                    {
                        data: 'category',
                        name: 'category',
                        orderable: false
                    },
                    {
                        data: 'review_score',
                        name: 'review_score',
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
                obj.closest(".card").find('select[name="favouriteTable_length"]').val(tableLength).trigger(
                    "change");
            });

        });
    </script>
@endsection
