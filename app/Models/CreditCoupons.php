<?php

namespace App\Models;

use App\Helpers\CodeHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Space\Models\Space;

class CreditCoupons extends Model
{
    use HasFactory;
    protected $table = 'credit_coupons';

    protected $fillable = [
        'user_id',
        'code',
        'recepient',
        'amount',
        'used',
        'pending',
        'type',
        'reference',
        'notes',
        'expired_at',
        'object_id',
        'object_model'
    ];

    public function space()
    {
        return $this->belongsTo(Space::class, 'object_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function generateCode()
    {
        $code = CodeHelper::generateRandomString(10);
        $code = strtoupper($code);
        $exists = self::where('code', $code)->first();
        if ($exists != null) {
            return $this->generateCode();
        }
        return $code;
    }

}
