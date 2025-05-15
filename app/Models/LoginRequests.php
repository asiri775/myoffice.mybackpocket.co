<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class LoginRequests extends Model
{
    use HasFactory;

    protected $table = 'login_requests';

    protected $fillable = [
        'user_id',
        'type',
        'token',
        'created_at',
        'updated_at'
    ];

    public static function createLoginToken($userId, $type)
    {
        $model = new self();
        $model->user_id = $userId;
        $model->type = $type;
        $model->token = (string) Str::uuid();
        $model->save();
        return $model;
    }

}
