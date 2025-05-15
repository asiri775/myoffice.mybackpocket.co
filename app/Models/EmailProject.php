<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailProject extends Model
{
    protected $connection = 'mailserver';
    protected $table = 'projects';
}
