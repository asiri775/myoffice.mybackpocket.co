<?php
namespace Modules\Space\Models;

use App\Currency;
use App\Helpers\CodeHelper;
use Illuminate\Http\Response;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Modules\Booking\Models\Bookable;
use Modules\Booking\Models\Booking;
use Modules\Booking\Traits\CapturesService;
use Modules\Core\Models\Attributes;
use Modules\Core\Models\SEO;
use Modules\Core\Models\Terms;
use Modules\Media\Helpers\FileHelper;
use Modules\Review\Models\Review;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Space\Models\SpaceTranslation;
use Modules\User\Models\UserWishList;
use Modules\Location\Models\Location;
use App\User;

class PostalCodesAndTimeZone extends Bookable
{
    use Notifiable;
    use SoftDeletes;
    use CapturesService;

    protected $table = 'postal_codes_and_time_zone';
    protected $fillable = [
        'postalcode',
        'city',
        'province_abbr',
        'timezone',
        'latitude',
        'longitude'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }



}
