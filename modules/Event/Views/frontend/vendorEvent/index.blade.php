@extends('layouts.new_user')
@section('head')
@endsection
@section('content')
    <section class="coming-soon-section">
        <div class="container">
            <div class="coming-soon-section-box">
                <div class="image-box">
                    <img src="{{ asset('images/coming-soon.png') }}" alt="">
                </div>
                <div class="content-box">
                    <h1>Whoopsie!</h1>
                    <p>We currently giving our office rental website a makeover to ensure it's as sleek and polished as the
                        corner office with a view!</p>
                    <p>While we're putting on our hard hats and dusting off the blueprints, feel free to brainstorm your ideal
                        workspace, practice your elevator pitch, or even imagine yourself sealing that big deal in your future
                        office space.</p>
                    <p>Stay tuned for when we unveil our revamped digs - we promise it'll be worth the wait!</p>
                </div>
            </div>
        </div>
    </section>

    {{-- <div class="content sm-gutter">

        <div class="bg-white">
            <div class="container-fluid pl-5 page-breadcrumb-header">
                <ol class="breadcrumb breadcrumb-alt bg-white mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Events</li>
                </ol>

            </div>
        </div>

        <div class="container-fluid pt-3 px-5">

            @if (Auth::user()->hasPermissionTo('event_create') && empty($recovery))
                <div class="row mb-3">
                    <div class="col-12">
                        <a href="{{ route('space.vendor.create') }}" class="btn btn-primary reverse ">
                            {{ __('Add New Event') }}
                        </a>
                    </div>
                </div>
            @endif

            @if ($rows->total() > 0)
                <div class="bravo-list-item">
                    <div class="list-item">
                        <div class="row">
                            @foreach ($rows as $row)
                                <div class="col-md-12">
                                    <div class="card-m-box">
                                        @include('Event::frontend.vendorEvent.loop-list')
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="bravo-pagination">
                        <span
                            class="count-string">{{ __('Showing :from - :to of :total Spaces', ['from' => $rows->firstItem(), 'to' => $rows->lastItem(), 'total' => $rows->total()]) }}</span>
                        {{ $rows->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                {{ __('No Events') }}
            @endif
        </div>

    </div> --}}
@endsection
