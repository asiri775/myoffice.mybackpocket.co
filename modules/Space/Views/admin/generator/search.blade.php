@extends('admin.layouts.app')
@section('content')

    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{ __('Found Spaces') }}</h1>
            <div class="title-actions">
                <a href="{{ route('space.admin.imageGenerator') }}">
                    <button
                        class="btn-secondary btn-sm btn btn-icon btn_search float-right mt-2">{{ __('Back to Dashboard') }}</button>
                </a>
            </div>
        </div>
        @include('admin.message')

        <div class="text-right">

            <p class="small"><i>{{ __('Found :total items', ['total' => $rows->total()]) }}</i>
                <select class="num_rows">
                    <option @if ($length == 10) selected @endif>10</option>
                    <option @if ($length == 20) selected @endif>20</option>
                    <option @if ($length == 50) selected @endif>50</option>
                    <option @if ($length == 100) selected @endif>100</option>


                </select>
            </p>
            <button class="btn btn-secondary btn-sm pt-0 pb-0 float-right" id="selectNone">Select
                None</button>
            <button class="btn btn-secondary btn-sm pt-0 pb-0 mr-1 float-right" id="selectAll">Select
                All</button>
        </div>
        <div class="panel">
            <div class="panel-body">
                <form action="{{ route('space.admin.imageGenerator') }}?method=1" method="post" class="bravo-form-item">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="60px">
                                        <!-- <input type="checkbox" class="check-all" data-toggle="toggle" data-on="Select All"
                                                                        data-off="Select None" data-onstyle="info" data-offstyle="secondary"
                                                                        data-size="sm"> -->
                                    </th>
                                    <th> {{ __('Name') }}</th>
                                    <th width="200px"> {{ __('Type') }}</th>
                                    <th width="200px"> {{ __('Location') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($rows->total() > 0)
                                    @foreach ($rows as $row)
                                        <tr class="{{ $row->status }}">
                                            <td><input type="checkbox" name="ids[]" class="check-item"
                                                    value="{{ $row->id }}">
                                            </td>
                                            <td class="title">
                                                <a
                                                    href="{{ route('space.admin.edit', ['id' => $row->id]) }}">{{ $row->title }}</a>
                                            </td>
                                            <td>
                                                @foreach ($row->terms as $t)
                                                    @if ($t->term->attr_id == 3)
                                                        {{ $t->term->name }}<br />
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>{{ $row->city ?? '' }},{{ $row->country ?? '' }}</td>

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7">{{ __('No space found') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        <hr />
                    </div>
                    <div class="row">
                        <div class="col-3">

                            <div class="form-group switch-toggle">
                                <label class="switch">

                                    <input type="checkbox" id="replace" name="replace">
                                    <span class="slider round"></span>
                                </label>
                                <span for="replace">{{ __('Replace Existing Images') }}</span>
                                <button class="btn-info btn btn-icon btn_search" type="submit">
                                    {{ __('Generate Images') }}</button>
                            </div>

                        </div>
                        <div class="col-9">

                            {{ $rows->appends(request()->query())->links() }}
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('script.body')
    <script>
        $(document).ready(function() {
            $('.num_rows').on('change', function() {
                var numRows = $(this).val();
                var currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('rows', numRows);
                window.location.href = currentUrl.toString();
            });
        });
        $(document).ready(function() {
            $('#selectAll').click(function(e) {
                e.preventDefault();
                $('input[name="ids[]"]').prop('checked', true);
            });
            $('#selectNone').click(function(e) {
                e.preventDefault();
                $('input[name="ids[]"]').prop('checked', false);
            });
        });
    </script>
@endsection
