<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Space\Models\Space;

class AddToFavourite extends Model
{
    use HasFactory;
    protected $table = 'add_to_favourites';

    protected $fillable = ['user_id', 'object_id'];

    public function space()

    {

        return $this->belongsTo(Space::class, 'object_id');

    }


    public function user()

    {

        return $this->belongsTo(User::class, 'user_id');

    }
}
