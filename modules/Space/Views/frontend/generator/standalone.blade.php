@extends('layouts.empty')
@section('head')
@endsection
@section('content')
{{--    here --}}
    <div class="container ">
        <div class="row">
            <div class="col">
                <div class="panel col-12 m-0 p-0">
                    <div class="panel-title mb-2 text-center"><strong>{{ __('Search for Specific Listing') }}</strong></div>
                    <div class="panel-body">

                        <form action="{{ route('space.generator') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <input type="text" required placeholder="{{ __('Name of the space') }}" name="title"
                                    class="form-control">
                            </div>
                            <div class="text-right mb-2">
                                <button class="btn btn-primary" type="submit"> {{ __('Next') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('footer')
@endsection
