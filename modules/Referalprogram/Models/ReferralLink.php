<?php

namespace Modules\Referalprogram\Models;

use App\BaseModel;
use App\Helpers\CodeHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\User\Models\User;
use Ramsey\Uuid\Uuid;

class ReferralLink extends BaseModel
{
    use HasFactory;

    protected $fillable = ['user_id', 'referral_program_id', 'code'];

    public static function randomCode($length = 6, $iteration = 1)
    {
        if($iteration > 50){  
            $code = time().strtoupper(CodeHelper::generateRandomString(3));
        }else{
            $code = strtoupper(CodeHelper::generateRandomString($length));
        }
        
        $chkCode = static::where('code', $code)->first();
        if ($chkCode != null) {
            $iteration++;
            if ($iteration > 25) {
                $length = 8;
            } elseif ($iteration > 10) {
                $length = 7;
            }
            return self::randomCode($length, $iteration);
        }
        return $code;
    }

    public static function getRefferal($user, $program)
    {
        $model = static::where([
            'user_id' => $user->id,
            'referral_program_id' => $program->id
        ])->first();
        if ($model == null) {
            $model = static::create([
                'user_id' => $user->id,
                'referral_program_id' => $program->id,
                'code' => self::randomCode()
            ]);
        }
        return $model;
    }

    public function getLinkAttribute()
    {
        return url($this->program->uri) . '?ref=' . $this->code;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function program()
    {
        return $this->belongsTo(ReferralProgram::class, 'referral_program_id');
    }

    public function relationships()
    {
        return $this->hasMany(ReferralRelationship::class);
    }
}