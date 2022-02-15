<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;
use OwenIt\Auditing\Contracts\Auditable;

class Category extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

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
    protected $fillable = ['name', 'name_url', 'parent_id', 'status', 'category_image', 'position_order', 'description','show_home_page', 'category_banner_images'];


    public static function getProducts($country_id=0)
    {
        $value=Product::where('category_id', $country_id)->orderBy('prod_name','ASC')->get();
        return $value;
    }
}
