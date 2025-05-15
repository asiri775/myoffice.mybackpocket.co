<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserTransaction extends Model
{


    protected $table = 'user_transactions';
    protected $fillable = ['payable_type','payable_id','wallet_id','type','amount','confirmed','full_amount','meta','uuid','create_user',
    'update_user','payment_id','booking_id','is_debit','reference_id'];

}
