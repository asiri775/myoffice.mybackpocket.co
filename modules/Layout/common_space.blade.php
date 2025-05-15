@section('bodyClass', 'frontend-page header-normal has-search-map')
@section('footerLastExtra')
    <link rel="stylesheet" href="{{ asset('css/search-map.css') }}">
    <style>
        body {
            overflow: hidden;
        }
    </style>
@endsection
@include('Layout::common_home')
