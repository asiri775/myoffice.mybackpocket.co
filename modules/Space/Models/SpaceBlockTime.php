<?php

namespace Modules\Space\Models;

use Illuminate\Database\Eloquent\Model;

class SpaceBlockTime extends Model
{
    protected $table = 'bravo_space_block_times';

    protected $fillable = [
        'bravo_space_id', 'from', 'to', 'created_at', 'updated_at', 'data'
    ];

}
