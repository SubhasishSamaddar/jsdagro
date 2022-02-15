<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Order_master  extends Model /*implements Auditable */
{
	protected $table='order_master';
    protected $fillable = [
         'order_number', 'user_id', 'total_amount', 'swadesh_hut_id', 'order_date', 'order_status', 'payment_status', 'payment_mode','shipping_type','shipping_phone', 'shipping_address', 'shipping_address1', 'location', 'city', 'pin_code', 'state', 'billing_name', 'billing_email', 'billing_phone', 'billing_street', 'billing_city', 'billing_pincode', 'billing_state'
   ];  
	public function userName(){
		return $this->belongsTo(App\User::class);
	}
}
