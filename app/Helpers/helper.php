<?php

namespace App\Helpers;
use Illuminate\Http\Request;
use DB;
use Session;
use Cookie;
class Helper
{
    public static function shout(string $string)
    {
        return strtoupper($string);
    }  

	public static function printMenu($parent_element) {

		$categories = DB::table('categories')
		->orderBy('position_order','ASC')
		->where('status','Active')
		->where('parent_id',$parent_element)
		->get();
		$count=1;
		if( isset($categories) && count($categories)>0 ){
			if($count>1)
			{
				echo '<ul class="submenu lvl2">';
			}
			else 
			{
				echo '<ul class="submenu">';
			}
			
			foreach ($categories as $Dresult) {
				$Surl = route('category',$Dresult->name_url);
				echo '<li><a href="'.$Surl.'"></span>'.$Dresult->name.'</a>';
					Helper::printMenu($Dresult->id);
				echo '</li>';
			}
			echo '</ul>';
		}

	}

	public static function getState() {
		$stateDetails = DB::table('states')->where( 'country_id', '103' )->get();
		$stateHtml='<select name="states">';
		if( !empty($stateDetails) && count($stateDetails) ){
			foreach( $stateDetails as $stateDetail ):
				if( $stateDetail->state_name=='West Bengal' ):
					$selected='selected="selected"';
				else:
					$selected='';
				endif;
				$stateHtml.='<option value="'.$stateDetail->state_name.'" '.$selected.'>'.$stateDetail->state_name.'</option>';
			endforeach;
		}
		$stateHtml.='</select>';
		return $stateHtml;
	}


    public static function rand_string($digits=null) {
        $alphanum = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

        // generate the random string
        if($digits != null){
            $rand = substr(str_shuffle($alphanum), 0, $digits);
        }else{
            $rand = '';
        }
        $time = time();
        $val =$time . $rand;

        return $val;
    }

    public static function get_package_inventory_out_details($id){
        $details = DB::table('package_inventory_out')->where('package_inventory_id',$id)->get();
        return $details;
    }

	public static function get_swadesh_hut_details($swadesh_hut_idd){
        $details = DB::table('swadesh_huts')->where('id',$swadesh_hut_idd)->first();
        return $details;
    }
	
	public static function get_product_by_categorys($catId, $swadesh_hut_id){  
		
		if( $swadesh_hut_id ):
			$data = DB::table('products')
				->where('category_id', $catId)
				->where('swadesh_hut_id', $swadesh_hut_id)
				->where(function ($query){
					$query->whereRaw('similar_product = sku')
					->orWhereNull('similar_product');
				})
				->limit(5)
				->get();  
		else:
			$data = DB::table('products')
				->where('category_id', $catId)
				->where(function ($query){
					$query->whereRaw('similar_product = sku')
					->orWhereNull('similar_product');
				})
				->limit(5)
				->get();
		endif;
		 
		return $data;
	}

	public static function getProductOptions($sku, $swadeshHutId)
	{
		if( $swadeshHutId ):
			$data = DB::table('products')->select('id','prod_name','price','weight_per_pkt', 'weight_unit','discount','wholesale_price', 'swadesh_hut_id', 'name_url')
				->where('similar_product', $sku)
				->where('swadesh_hut_id', $swadeshHutId)
				->whereNotNull('similar_product')
				->orderBy('price','ASC')
				->get();  
		else:
			$data = DB::table('products')->select('id','prod_name','price','weight_per_pkt', 'weight_unit','discount','wholesale_price', 'swadesh_hut_id', 'name_url')
			->where('similar_product', $sku)
			->whereNotNull('similar_product')
			->orderBy('price','ASC')
			->get();
		endif;
		return $data;
	}

	public static function getCategoryIdArray($category_id){
		$child_details = DB::table('categories')->where('parent_id', $category_id)->get();  
		$children = array();
	
		if(count($child_details)>0) {
			# It has children, let's get them.
			$i=0;
			foreach($child_details as $details) {
				# Add the child to the list of children, and get its subchildren
				$children[$details->id] = Helper::getCategoryIdArray($details->id);
				$i++;
			}
		}
		return $children;
	}

	public static function get_products_by_category_id_array($category_id,$swadesh_hut_id){
		$products = DB::table('products')  
		->whereIn('category_id', $category_id)
		->where('swadesh_hut_id', $swadesh_hut_id)
		->groupBy('prod_name')
		->get();  
		return $products;
	}

	public static function get_products_by_category_id($category_id,$swadesh_hut_id){
		$products = DB::table('products')  
		->where('category_id', $category_id)
		->where('swadesh_hut_id', $swadesh_hut_id)
		->groupBy('prod_name')
		->get();  
		return $products;
	}


	public static function make_url($string) {
		$string = str_replace('&', 'and', $string);
		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		$string = preg_replace('/-+/', '-', $string);
		$string =strtolower($string);// Replaces Capital chars.
		//$string = preg_replace('/[0-9]+/', '', $string);// Removes Number.
	 
		return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	 }

	 public static function get_products_by_id($id){
		$products = DB::table('products')  
		->where('id', $id)
		->first();  
		return $products;
	}

}
