<?php

namespace Modules\Referalprogram\Models;

use App\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReferralProgram extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name','uri','lifetime_minutes'];
}