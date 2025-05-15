<?php

namespace Modules\Referalprogram\Models;

use App\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReferralRelationship extends BaseModel
{
    use HasFactory;

    protected $fillable = ['referral_link_id','user_id'];
}