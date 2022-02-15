<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Category;
use DB;
class CategoriesController extends Controller
{
    public function categoryListing(Request $request)
    {
        //echo 'ccccc';die;//print_r($request);die;
		$categories = DB::table('categories as A')->select('A.id','A.name','A.status','A.category_image','A.position_order','A.description','A.parent_id','B.name as parent_name','A.created_at','A.updated_at')
                            ->leftJoin('categories as B','A.parent_id','=','B.id')
                            ->orderBy('A.position_order','ASC')->orderBy('parent_name','ASC')
							->where('A.status','Active')->where('A.parent_id','!=','0')->get();

		/*
		echo '<pre>';print_r($categories);//die;
		$sql=DB::table('categories')->select('id','name','category_image')->where('status','Active')->where('parent_id','0')->get();
		echo '<pre>';print_r($sql);
		$submenu=DB::table('categories As A')->leftJoin('categories as B','A.parent_id','=','B.id')
		->where('A.status','Active')->where('A.parent_id','!=','0')->get();
		echo '<pre>';print_r($submenu);die;
		*/
        return view('front.categories.listing',compact('categories'));
    }

}
