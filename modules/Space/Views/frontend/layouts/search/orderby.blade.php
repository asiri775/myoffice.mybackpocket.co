<div class="item">
    @php
        $param = request()->input();
        $orderby = request()->input('orderby'); 
        $spaceSearchQTerm = request()->input('q'); 
    @endphp 
    <input type="hidden" name="orderby" id="spaceOrderBy" value="{{$orderby ?? ''}}">
    <input type="hidden" name="q" id="spaceSearchQTerm" value="{{$spaceSearchQTerm ?? ''}}">
    <div class="item-title">
        {{ __('Sort by:') }}    
    </div>
    <div class="dropdown" id="spaceOrderBySelectorMain">
        <span class=" dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            @switch($orderby)
                @case('best_rated')
                    {{ __('Best Rated') }}
                @break

                @case('on-sale')
                    {{ __('On Sale') }}
                @break

                @case('price_low_high')
                    {{ __('Price (Low to high)') }}
                @break

                @case('price_high_low')  
                    {{ __('Price (High to low)') }}
                @break

                @case('rate_high_low')
                    {{ __('Rating (High to low)') }}
                @break

                @default
                    {{ __('All') }} 
            @endswitch
        </span>
        <div class="dropdown-menu dropdown-menu-right" id="spaceOrderBySelector" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" data-value="" href="javascript:;">{{ __('All') }}</a>
            <a class="dropdown-item" data-value="best_rated" href="javascript:;">{{ __('Best Rated') }}</a>
            <a class="dropdown-item" data-value="on-sale" href="javascript:;">{{ __('On Sale') }}</a>
            <a class="dropdown-item" data-value="price_low_high" href="javascript:;">{{ __('Price (Low to high)') }}</a>
            <a class="dropdown-item" data-value="price_high_low" href="javascript:;">{{ __('Price (High to low)') }}</a>
            <a class="dropdown-item" data-value="rate_high_low" href="javascript:;">{{ __('Rating (High to low)') }}</a>
        </div>
    </div>
</div>
