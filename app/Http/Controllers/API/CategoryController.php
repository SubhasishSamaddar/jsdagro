<?php


namespace App\Http\Controllers\API;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Category;
use App\Product;
use Validator;
use Auth;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Category::all();
        return $this->sendResponse($data->toArray(), 'Data retrieved successfully.');
    }
	
	public function listing(Request $request)
    {
		$newData=array();    
		$request->parent_id=($request->parent_id)?$request->parent_id:'0';
		$data = Category::where('parent_id',$request->parent_id)->where('status','Active')->get();
		
        if( $data && !empty($data) ):
			foreach( $data as $value ):
				$tempImage= '/public/storage/'.$value->category_image;
				unset($value->category_image);
				$value->category_image=$tempImage;
				$category_products = Product::where('category_id',$value->id)->get();
				$value->total_category_product=count($category_products);
				$newData[]=$value;  
			endforeach;  
		endif;  
		return $this->sendResponse($newData, 'Data retrieved successfully.');
    }

    ///For Api Testing Created By Subhasish
    public function testlisting(Request $request)
    {
		$newData=array();    
		$request->parent_id=($request->parent_id)?$request->parent_id:'0';
		$data = Category::where('parent_id',$request->parent_id)->where('status','Active')->get();
        if( $data && !empty($data) ):
			foreach( $data as $value ):
				$tempImage= url('/').'/public/storage/'.$value->category_image;
				unset($value->category_image);
				$value->category_image=$tempImage;
				$newData[]=$value;  
			endforeach;  
		endif;  
		return $this->sendResponse($newData, 'Data retrieved successfully.');
    }

    public function testbannerslisting(Request $request)
    {
		$newData=array();    
		$data = DB::table('banners')->skip(1)->take(2)->get();
        
        if( $data && !empty($data) ):
			foreach( $data as $value ):
				$tempImage= url('/').'/storage/app/public/banner/'.$value->banner_image;
				unset($value->banner_image);
				$value->banner_image=$tempImage;
				$newData[]=$value;  
			endforeach;  
		endif;  
        //print_r($newData);exit;
		return $this->sendResponse($newData, 'Data retrieved successfully.');
    }
}
