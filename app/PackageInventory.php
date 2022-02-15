<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use OwenIt\Auditing\Contracts\Auditable;

class PackageInventory extends Model
{
  //  implements Auditable
  //use \OwenIt\Auditing\Auditable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'package_inventories';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['inventory_in_out', 'user_id', 'package_location_id', 'prod_name', 'sku', 'category_id', 'total_weight', 'purchased_price', 'mrp', 'cgst', 'sgst', 'igst', 'product_image','description','specification','available_qty','weight_per_packet','weight_unit','no_of_packet','similar_product','brand_id','other_image','hsn','selling_price','manufacturer_details','marketed_by','country_of_origin','customer_care_details','seller','barcode','wholesale_price','wholesale_min_qty','expiry_date'];


    public function category()
    {
        return $this->hasOne('App\Category','id','category_id');
    }

    public function packageLocation()
    {
        return $this->hasOne('App\Package_location','id','package_location_id');
    }

}
