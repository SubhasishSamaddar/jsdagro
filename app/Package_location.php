<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class Package_location extends Model /*implements Auditable*/
{
	protected $fillable = [
        'location_name', 'cover_area', 'status',
    ];
}
