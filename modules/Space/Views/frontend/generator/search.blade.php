@extends('layouts.empty')
@section('head')
@endsection
@section('content')


    <div class="container ">
        <div class="row">
            <div class="col">
                <div class="panel col-12 m-0 p-0">
                    <div class="panel-title mb-2 text-center"><strong>{{ __('Found Spaces') }}</strong>
                    </div>
                    <a href="{{ route('space.home') }}">
                        <button
                            class="btn-secondary btn-sm btn btn-icon btn_search float-right mt-2">{{ __('Back to Dashboard') }}</button>
                    </a><br /><br />
                    <form action="{{ route('space.generator') }}" method="post" class="bravo-form-item">

                        <div class="row p-2">
                            <div class="col-3">
                                <div class="form-group">
                                    <label>Max Word Count</label> <input type="number" required
                                        placeholder="{{ __('Max word count') }}" name="wordcount" class="form-control"
                                        min="100" value="200">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label>Language Style</label>
                                    <select name="style" class="form-control">
                                        <option value="">Regular</option>
                                        <option>Humorous</option>
                                        <option>Formal</option>
                                        <option>Slang</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-3"></div>
                            <div class="text-right col-3">
                                <p class="small"><i>{{ __('Found :total items', ['total' => $rows->total()]) }}</i>
                                    <select class="num_rows">
                                        <option @if ($length == 10) selected @endif>10</option>
                                        <option @if ($length == 20) selected @endif>20</option>
                                        <option @if ($length == 50) selected @endif>50</option>
                                        <option @if ($length == 100) selected @endif>100</option>


                                    </select>
                                </p>
                            </div>

                        </div>
                        <div class="panel">
                            <div class="panel-body">
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
                                                        <td><input type="radio" name="id" class="check-item"
                                                                value="{{ $row->id }}" required>
                                                        </td>
                                                        <td class="title">
                                                            <a
                                                                href="{{ $row->getDetailUrl($include_param ?? true) }}">{{ $row->title }}</a>
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

                                    <div class="col-9">

                                        {{ $rows->appends(request()->query())->links() }}
                                    </div>
                                    <div class="col-3">

                                        <div class="form-group switch-toggle pull-right">

                                            <button class="btn-primary btn btn-icon btn_search" type="submit">
                                                {{ __('Next') }}</button>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('footer')
    <script>
        $(document).ready(function() {
            $('.num_rows').on('change', function() {
                var numRows = $(this).val();
                var currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('rows', numRows);
                window.location.href = currentUrl.toString();
            });
        });
    </script>
@endsection
