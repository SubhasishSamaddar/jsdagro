<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use DB;

class Product extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

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
    protected $fillable = ['package_inventory_id', 'prod_name', 'name_url', 'category_id', 'sku', 'swadesh_hut_id', 'weight_per_pkt', 'weight_unit', 'cgst', 'sgst', 'igst', 'available_qty', 'ordered_qty', 'price', 'max_price', 'status', 'product_image','description','specification','is_featured','similar_product','brand_id','discount','other_image','hsn','manufacturer_details','marketed_by','country_of_origin','customer_care_details','seller','barcode','wholesale_price','wholesale_min_qty','expiry_date','meta_keyword','meta_description','tags'];


}
