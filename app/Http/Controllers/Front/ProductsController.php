<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductsController extends Controller
{
	public function listing(Request $request){
		echo 'bbb';die;  
	     $products = DB::table('products as A')
        ->select('A.id','A.prod_name','A.price','A.status','A.product_image', 'A.description','B.name as category_name','A.created_at','A.updated_at')
                            ->leftJoin('categories as B','A.category_id','=','B.id')
                            ->orderBy('A.prod_name','ASC')->get();


        return view('admin.products.index',compact('products'));
	}
}
