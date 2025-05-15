<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserTransactionDetails extends Model
{


    protected $table = 'user_transactions_details';
    protected $fillable = ['payable_type','payment_id','wallet_id','type','amount','confirmed','full_amount','meta','user_id','booking_id','is_debit','reference_id','status','credit_card'];

}

