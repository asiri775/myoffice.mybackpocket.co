@extends('layouts.empty')
@section('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('libs/fotorama/fotorama.css') }}" />
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="panel col-12 m-0 p-0">
                    <div class="panel-title mb-2 text-center"><strong>{{ __('Description Generator') }}</strong></div>
                    <div class="panel-body">
                        <h6>{{ $space->title }}</h6>
                        <div class="fotorama" data-width="100%" data-thumbwidth="135" data-thumbheight="135"
                             data-thumbmargin="15" data-nav="thumbs" data-allowfullscreen="true">
                            <a href="{{ Modules\Media\Helpers\FileHelper::url($space->banner_image_id, 'large') }}"
                               data-thumb="{{ Modules\Media\Helpers\FileHelper::url($space->banner_image_id, 'thumb') }}"
                               data-alt="{{ __('Banner') }}"></a>
                        </div>
                        <div class="row">
                            <div class="col-7">{!! $text !!}</div>
                            <div class="col-5">{!! $info !!}</div>
                        </div>
                        <!-- Button to add description to textarea -->
                        <div class="text-center mt-3">
                            <button id="addDescriptionButton" class="btn btn-primary">Add Description to Textarea</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript" src="{{ asset('libs/fotorama/fotorama.js') }}"></script>
    <!-- <script>
    // JavaScript code to add description to textarea
    document.getElementById('addDescriptionButton').addEventListener('click', function() {
        var description = {!! json_encode($text) !!}; // Assuming $text contains the generated description
        localStorage.setItem('generatedDescription', description);
        // Redirect back to the previous page
        history.back();
    });
    </script> -->
    <script>
        // JavaScript code to add description to textarea and redirect to the edit page
        document.getElementById('addDescriptionButton').addEventListener('click', function() {
            var description = {!! json_encode($text) !!}; // Assuming $text contains the generated description
            localStorage.setItem('generatedDescription', description);
            // Extract the ID from the current URL
            var currentUrl = window.location.href;
            var dynamicId = currentUrl.substring(currentUrl.lastIndexOf('/') + 1);
            // Redirect to the edit page with dynamic ID
            var redirectUrl = "{{ route('space.admin.edit', ['id' => ':id']) }}";
            redirectUrl = redirectUrl.replace(':id', dynamicId);
            window.location.href = redirectUrl;
        });
    </script>

@endsection

