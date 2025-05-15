@extends('admin.layouts.app')
@section('content')
    <div class="container">
        <div class="mb20">
            <div class="d-flex justify-content-between">
                <h1 class="title-bar">{{ $page_title }}</h1>
            </div>
            <hr>
        </div>
        @include('admin.message')
        <div class="row mb-3">
            <div class="col-md-12">
                <form action="{{ route('space.admin.descriptionAI') }}" method="post" autocomplete="off">
                    @csrf

                    @include('Language::admin.navigation')

                    <div class="lang-content-box">
                        <style>
                            .help-variables {
                                margin: 5px 0;
                            }

                            .help-variables .badge {
                                font-weight: normal;
                                text-transform: unset;
                                margin: 2px 0px;
                                min-width: auto;
                                width: auto;
                            }
                        </style>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label class="">{{ __('OpenAI Key') }}</label>
                                            <div class="form-controls">
                                                <input type="text" class="form-control" name="openai_key"
                                                    value="{{ getSettingItem('openai_key') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-start">
                        <span></span>
                        <button class="btn btn-primary" type="submit">{{ __('Save settings') }}</button>
                    </div>
                </form>
            </div>
        </div>
        @include('Space::admin.prompts.list', ['type' => 'space-description-ai', 'name' => 'Space Description AI'])
    </div>
@endsection
