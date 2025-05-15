@extends('admin.layouts.app')
@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('libs/fotorama/fotorama.css') }}" />
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{ __('Processing Complete') }}</h1>
            <div class="title-actions">
                <a href="{{ route('space.admin.imageGenerator') }}">
                    <button
                        class="btn-secondary btn-sm btn btn-icon btn_search float-right mt-2">{{ __('Back to Dashboard') }}</button>
                </a>
                <a href="{{ url()->current() }}?method=1&regenerate=true">
                    <button type="submit"
                        class="btn-secondary btn-sm btn btn-icon btn_search float-right mt-2
                        mr-2">{{ __('Regenerate') }}</button>
                </a>
            </div>
        </div>
        @include('admin.message')
        <div class="panel">
            <div class="panel-body row">
                @foreach ($rows as $row)
                    @php
                    // dd($row->getGallery(true));
                    @endphp
                    <div class="col-md-3 col-sm-6 border  p-2">
                        <div class="attach-demo">

                            <h5 class="title text-primary">
                                {{ $row->title }} <small
                                    class="float-right">{{ $row->city ?? '' }},{{ $row->country ?? '' }}</small>
                            </h5>
                            <Hr />
                            <div class="fotorama" data-width="100%" data-thumbwidth="135" data-thumbheight="135"
                                data-thumbmargin="15" data-nav="thumbs" data-allowfullscreen="true">


                                <a href="{{ Modules\Media\Helpers\FileHelper::url($row->banner_image_id, 'large') }}"
                                    data-thumb="{{ Modules\Media\Helpers\FileHelper::url($row->banner_image_id, 'thumb') }}"
                                    data-alt="{{ __('Banner') }}"></a>

                                @foreach ($row->getGallery(true) as $key => $item)
                                    <a href="{{ $item['large'] }}" data-thumb="{{ $item['thumb'] }}"
                                        data-alt="{{ __('Gallery') }}"></a>
                                @endforeach
                            </div>
                            <h6 style="font-size: 10px">
                                @php
                                    $first = true;
                                @endphp
                                @foreach ($row->terms as $t)
                                    @if ($t->term->attr_id == 3)
                                        @if (!$first)
                                            &nbsp;|&nbsp;
                                        @endif {{ $t->term->name }}
                                        @php
                                            $first = false;
                                        @endphp
                                    @endif
                                @endforeach
                            </h6>
                            <a href="{{ route('space.admin.edit', ['id' => $row->id]) }}" target="_blank">
                                <button
                                    class="btn-secondary btn-sm btn btn-icon btn_search float-right mt-2">{{ __('View Space') }}</button>
                            </a>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection



@section('script.body')
    <script type="text/javascript" src="{{ asset('libs/fotorama/fotorama.js') }}"></script>
@endsection
