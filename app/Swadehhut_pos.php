<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Swadehhut_pos  extends Model /*implements Auditable */
{
	protected $table='swadehhut_pos';
    protected $fillable = [
         'swadeshhut_id', 'name', 'userName', 'password', 'status'
   ];  
	
}
