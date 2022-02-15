<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OnlinePayments extends Model
{
    protected $table = 'online_payments';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = ['txn_id', 'user_id', 'amount', 'product_info', 'user_email','user_phone'];
}
