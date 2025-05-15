@extends('layouts.yellow_user')
@section('head')
@endsection
@section('content')
    <?php
    if (!$row->id) {
        //new record
        $row->enable_extra_price = 1;
        $row->extra_price = [
            [
                'name' => 'Cleaning Fee',
                'price' => 0,
                'type' => 'one_time',
            ],
        ];
    }
    ?>

    <div class="content sm-gutter">
        <!-- START BREADCRUMBS-->
        <div class="bg-white">
            <div class="container-fluid pl-5">
                <ol class="breadcrumb breadcrumb-alt bg-white mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('space.vendor.index') }}">Spaces</a></li>
                    <li class="breadcrumb-item active"><a
                            href="{{ route('space.vendor.edit', $row->id) }}">{{ $row->id ? __('Calendar: ') . $row->title : __('Add new space') }}</a>
                    </li>
                </ol>
            </div>
        </div>

        <div class="container-fluid p-5">

            @if ($row->id)
                @include('Language::admin.navigation')
            @endif

            <div class="lang-content-box">
                <div id='availabilityTimeCalendar'></div>
            </div>

        </div>
    </div>
@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset('libs/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/condition.js?_ver=' . config('app.version')) }}"></script>
    <script type="text/javascript" src="{{ url('module/core/js/map-engine.js?_ver=' . config('app.version')) }}"></script>

    {!! App\Helpers\MapEngine::scripts() !!}

    <script>
        let availabilityTimeCalendar = null;

        function showCalendarModal(spaceId) {
            $("#availabilityCalendar").modal("show");
            availabilityTimeCalendar = new FullCalendar.Calendar(document.getElementById('availabilityTimeCalendar'), {
                eventSources: [{
                    url: '{{ route('space.vendor.availability.calendarEvents') }}?id=' + spaceId,
                }],
                headerToolbar: {
                    left: 'prevYear,prev,next,nextYear today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                initialView: 'timeGridDay',
                dayMaxEvents: true,
                navLinks: true,
                customButtons: {
                    myCustomButton: {
                        text: 'Back to Space Details',
                        click: function() {
                            window.location.replace("{{route('space.vendor.edit', $row->id)}}");
                        }
                    }
                },
                headerToolbar: {
                    left: 'prev,next today myCustomButton',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                }
            });
            availabilityTimeCalendar.render();
        }

        showCalendarModal(<?= $row->id ?>);
    </script>
@endsection
