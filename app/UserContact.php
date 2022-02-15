<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserContact extends Model
{
    
    protected $table = 'user_contacts';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = ['user_contact_name', 'user_contact_email', 'user_contact_phone_no', 'user_contact_topic', 'user_contact_comment'];
}