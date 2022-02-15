<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    
    protected $table = 'contact_us';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = ['user_name', 'user_email', 'user_phone_no', 'user_comment','subject'];
}