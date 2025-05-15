<div class="filter-item filter-simple dropdown">
    <div class="form-group dropdown-toggle" data-toggle="dropdown">
        <h3 class="filter-title">{{ __('Price filter') }} <i class="fa fa-angle-down"></i></h3>
    </div>
    <div class="filter-dropdown dropdown-menu dropdown-menu-right">
        <div class="bravo-filter-price" data-value="hourly">
            <div class="mb-3">
                <select name="price_type" id="" class="form-control">
                    <option value="hourly">Hourly</option>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                </select>
            </div>
            <?php
            $price_min = $pri_from = floor(App\Currency::convertPrice($space_min_max_price[0]));
            $price_max = $pri_to = ceil(App\Currency::convertPrice($space_min_max_price[1]));
            if (!empty(($price_range = Request::query('price_range')))) {
                $pri_from = explode(';', $price_range)[0];
                $pri_to = explode(';', $price_range)[1];
            }
            $currency = App\Currency::getCurrency(App\Currency::getCurrent());
            ?>

            <div class="hourly iri-filter">
                <input type="hidden" class="filter-price irs-hidden-input" name="price_range_hourly"
                    data-symbol=" {{ $currency['symbol'] ?? '' }}" data-min="{{ $price_min }}"
                    data-max="{{ $price_max }}" data-from="{{ $pri_from }}" data-to="{{ $pri_to }}"
                    readonly="" value="{{ $price_range }}">
            </div>

            <div class="daily iri-filter">
                <input type="hidden" class="filter-price irs-hidden-input" name="price_range_daily"
                    data-symbol=" {{ $currency['symbol'] ?? '' }}" data-min="50" data-max="500" data-from="50"
                    data-to="500" readonly="" value="50;900">
            </div>

            <div class="weekly iri-filter">
                <input type="hidden" class="filter-price irs-hidden-input" name="price_range_weekly"
                    data-symbol=" {{ $currency['symbol'] ?? '' }}" data-min="100" data-max="2500" data-from="100"
                    data-to="2500" readonly="" value="100;2500">
            </div>

            <div class="monthly iri-filter">
                <input type="hidden" class="filter-price irs-hidden-input" name="price_range_monthly"
                    data-symbol=" {{ $currency['symbol'] ?? '' }}" data-min="500" data-max="10000" data-from="500"
                    data-to="10000" readonly="" value="500;10000">
            </div>

            <div class="text-right">
                <br>
                <a href="#" onclick="return false;"
                    class="btn btn-primary btn-sm btn-apply-advances">{{ __('APPLY') }}</a>
            </div>
        </div>
    </div>
</div>


<style>
    .iri-filter {
        display: none;
    }

    .bravo-filter-price[data-value="hourly"] .iri-filter.hourly {
        display: block
    }

    .bravo-filter-price[data-value="daily"] .iri-filter.daily {
        display: block
    }

    .bravo-filter-price[data-value="weekly"] .iri-filter.weekly {
        display: block
    }

    .bravo-filter-price[data-value="monthly"] .iri-filter.monthly {
        display: block
    }
</style>

<script>
    $(document).on("change", 'select[name="price_type"]', function() {
        $(this).closest(".bravo-filter-price").attr("data-value", $(this).val());
    });
</script>
