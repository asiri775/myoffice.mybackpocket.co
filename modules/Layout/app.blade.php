@if(request()->route()->getName() == 'home')
    @include('Layout::Home.home')
@else
    @include('Layout::Other.app')
@endif
