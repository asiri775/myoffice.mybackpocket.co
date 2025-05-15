<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;
    protected $table = 'bravo_booking_transactions';
    protected $fillable = ['transaction_id','order_id','payment_type','payment_date','amount','due_amount','full_amount','status','credit_card'];


}
