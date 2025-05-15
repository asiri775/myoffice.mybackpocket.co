@extends('admin.layouts.app')
@section('content')
    <div class="container">
        <div class="mb40">
            <div class="d-flex justify-content-between">
                <h1 class="title-bar">Default Content</h1>
            </div>
            <hr>
        </div>
        @include('admin.message')
        <div class="row">
            <div class="col-md-12">
                <form action="{{url('admin/module/core/settings/store/'.$current_group)}}" method="post" autocomplete="off">
                    @csrf

                    @include('Language::admin.navigation')

                    <div class="lang-content-box">
                        @if(empty($group['view']))
                            @include ('Core::admin.settings.groups.'.$current_group)
                        @else
                            @include ($group['view'])
                        @endif
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between">
                        <span></span>
                        <button class="btn btn-primary" type="submit">{{__('Save settings')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
