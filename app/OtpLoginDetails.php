<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OtpLoginDetails extends Model
{
    protected $table = 'otp_login_details';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = ['otp', 'mobile'];
}
