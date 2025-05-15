<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressFixLog extends Model
{
    use HasFactory;
    protected $table = 'address_fix_logs';

    protected $fillable = [
        'space_id',
        'address',
        'api_response',
        'created_at',
        'updated_at'
    ];

}
