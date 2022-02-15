<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Category;
use App\Product;
use Validator;
use Session;
use Cookie;
use Cart;
use DB;
use Auth;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Product::all();
        return $this->sendResponse($data->toArray(), 'Data retrieved successfully.');
    }
	
	public function bkplisting(Request $request)
    {
		$newData=array();
		if( $request->category_id && !empty($request->category_id) ):
			$data = Product::where('category_id',$request->category_id)->where('swadesh_hut_id', $request->shop_id)->where('status','Active')->get();
		else:  
			$data = Product::where('swadesh_hut_id', $request->shop_id)->where('status','Active')->get();
        endif;  
		if( $data && !empty($data) ):
			foreach( $data as $value ):
				$tempImage= '/public/storage/'.$value->product_image;
				unset($value->product_image);
				$value->product_image=$tempImage;
				$newData[]=$value;
			endforeach;  
		endif;  
		return $this->sendResponse($newData, 'Data retrieved successfully.');
    }  
	
	public function listing(Request $request)
    {  
		$sort = ($request->get('sort')=='DESC')?'DESC':'ASC';
		$orderBy = ($request->get('orderby'))?$request->get('orderby'):'prod_name';
		$offset = ($request->get('offset'))?$request->get('offset'):'4';
		$newData=array();
		/*
		if( $request->category_id && !empty($request->category_id) ):
			$data = Product::where('category_id',$request->category_id)->where('swadesh_hut_id', $request->shop_id)->where('status','Active')->orderBy($orderBy,$sort)->paginate($offset);
		else:  
			$data = Product::where('status','Active')->where('swadesh_hut_id', $request->shop_id)->orderBy($orderBy,$sort)->paginate($offset);
        endif;
		*/
		
		$data = Product::where(function($query) use ($request) {  
				if ($request->get('swadesh_hut_id')):
                    $query->where('swadesh_hut_id', $request->get('swadesh_hut_id'));
				endif;
                if ($request->get('category_id')):
                    $query->where('category_id', $request->get('category_id'));
				endif;
                if ($request->get('prod_name')):
                    $query->where('prod_name','like', "%".$request->get('prod_name')."%");
				endif;
                $query->where('status', 'Active');
            })   
			->orderBy($orderBy,$sort)->paginate($offset);
			
		$data=$data->toArray();
		//print_r($data);die;   
		
		if( $data['data'] && !empty($data['data']) && count($data['data'])>0 ):
			foreach( $data['data'] as $value ):
				$tempImage='/public/storage/'.$value['product_image'];
				$tempName=$value['prod_name'];
				unset($value['description']);
				unset($value['specification']);
				unset($value['product_image']);
				unset($value['prod_name']);
				$value['cost']=($value['price']*(100-$value['discount'])/100);
				$value['title']=$tempName;
				$value['product_image']=$tempImage;
				$value['units']=1;
				$newData['data'][]=$value;
			endforeach; 
			unset($data['data']);
			$data['results']=$newData['data'];
		endif;
		//$data['count']=offset;  
		if( is_numeric($data['to']) &&  is_numeric($data['from']) ):
			$total=$data['to']-$data['from']+1;
		else:
			$total=0;
		endif; 
		return $this->sendPaginationResponse($data, 'Data retrieved successfully.', $total);  
    }  
	
	
	public function productDetails(Request $request)
    {
		$data = Product::where('id',$request->product_id)->first();  
		if( $data && !empty($data) ):
			$data->title=$data->prod_name;
			unset($data->prod_name);
			$data->product_image='/public/storage/'.$data->product_image;
			$data->cost=($data->price*(100-$data->discount)/100);
		endif;   
		return $this->sendResponse($data->toArray(), 'Data retrieved successfully.');
    }
	  
	public function featureProductListing(Request $request)
    {
		$newData=array();
		$data = Product::where('is_featured', 'yes')->where('status','Active')->get();
		if( $data && !empty($data) ):
			foreach( $data as $value ):
				$tempImage= '/public/storage/'.$value->product_image;
				unset($value->product_image);
				$value->product_image=$tempImage;
				$newData[]=$value;
			endforeach;  
		endif;   
		return $this->sendResponse($newData, 'Data retrieved successfully.');
    }
	
	public function discountProductListing(Request $request)
    {    
		$newData=array();
		$data = Product::where('discount', '>', '0.00')->where('status','Active')->get();
		if( $data && !empty($data) ):
			foreach( $data as $value ):
				$tempImage='/public/storage/'.$value->product_image;
				unset($value->product_image);
				$value->product_image=$tempImage;
				$newData[]=$value;
			endforeach;  
		endif; 
		return $this->sendResponse($newData, 'Data retrieved successfully.');  
    }
	
	public function bestSellingProductListing(Request $request)
    {/*
		$data = DB::table('order_details')  
        ->select( 'products.prod_name', 'order_details.product_id', DB::raw('count(order_details.quantity) as total'))
		->leftjoin('products', 'order_details.product_id', 'products.id')
        ->groupBy('products.id')
        ->get();
		
		
		$data = DB::table('order_details')
        ->select('product_id', DB::raw('count(quantity) as total'))
		->leftjoin('products', 'order_details.product_id', 'products.id')
        ->groupBy('product_id')
        ->get();
		*/
        //$data=DB::select( DB::raw("select `products`.`id`, `products`.`prod_name`, count(quantity) as total from `order_details` left join `products` on `order_details`.`product_id` = `products`.`id` group by order_details.product_id"));  
		//select `products`.`id`, `products`.`prod_name`, count(quantity) as total from `order_details` left join `products` on `order_details`.`product_id` = `products`.`id` group by order_details.product_id 
			
		//SELECT product_id,COUNT(quantity) FROM `order_details` GROUP BY `product_id`
		//$data = Product::where('is_featured', 'yes')->where('status','Active')->get();
		$newDatas=array();
		$data = DB::table('order_details')  
        ->select('order_details.product_id', DB::raw('count(order_details.quantity) as total'))
		->groupBy('order_details.product_id')
		->orderBy('total', 'DESC')
        ->limit(10)
		->get();
		if( $data && !empty($data) ):
			foreach( $data as $sdata ):
				$newData = Product::where('id',$sdata->product_id)->first();  
				if( $newData && !empty($newData) ):
					$newData->product_image='/public/storage/'.$newData->product_image;
				endif;
				$newData->total_selling=$sdata->total;
				$newDatas[]=$newData;
			endforeach;
		endif;
		
		return $this->sendResponse($newDatas, 'Data retrieved successfully.');
		
    }
	/*
	public function cart(Request $request){
		
		$data=Cart::getContent();
		echo '<pre>';print_r($data);echo '</pre>';
		//return $this->sendResponse($data->toArray(), 'Data retrieved successfully.');
		//return view('front.products.cart',compact('productDetails', 'swadeshHutDetails'));
	}
	
	public function cart(Request $request){
		
		Cart::add(uniqid(), '3%aaaa', 2, '1', 'aaaa');
		$data=Cart::getContent();	
		Session::put('cartitem',$data);		
		return $this->sendResponse($data->toArray(), 'Data retrieved successfully.');
	}
	*/
	public function cart(Request $request){
		//Session::put('cartitem','swadesh Hut');
		$a=Session::get('cartitem');
		print_r($a);die;
		
	}






	public function testlisting(Request $request)
    {  
		$sort = ($request->get('sort')=='DESC')?'DESC':'ASC';
		$orderBy = ($request->get('orderby'))?$request->get('orderby'):'prod_name';
		$offset = ($request->get('offset'))?$request->get('offset'):'20';

		$page_no = ($request->get('page_no'))?$request->get('page_no'):'1';

		$newData=array();
		
		$data = Product::where(function($query) use ($request) {  
				if ($request->get('swadesh_hut_id')):
                    $query->where('swadesh_hut_id', $request->get('swadesh_hut_id'));
				endif;
                if ($request->get('category_id')):
                    $query->where('category_id', $request->get('category_id'));
				endif;
                if ($request->get('prod_name')):
                    $query->where('prod_name','like', "%".$request->get('prod_name')."%");
				endif;
                $query->where('status', 'Active');
            })   
			->orderBy($orderBy,$sort)->groupBy('prod_name')->paginate($offset);
			
		$data=$data->toArray();
		
		if( $data['data'] && !empty($data['data']) && count($data['data'])>0 ):
			foreach( $data['data'] as $value ):
				$weight_array = array();
				$initial_weight_array = array(0);
				$i = 0;
				$tempImage=url('/').'/public/storage/'.$value['product_image'];
				$tempName=$value['prod_name'];
				unset($value['specification']);
				unset($value['product_image']);

				$get_same_products_by_name_url = Product::where('prod_name',$value['prod_name'])->get();
				foreach($get_same_products_by_name_url as $pdetails){
					if(!in_array($pdetails->weight_per_pkt,$initial_weight_array)){
						$weight_array[$pdetails->id] = $pdetails->weight_per_pkt.$pdetails->weight_unit;
					}
					$initial_weight_array[$i] = $pdetails->weight_per_pkt;
					$i++;
				}



				unset($value['prod_name']);
				
				$get_category_details = DB::table('categories')->where('id',$value['category_id'])->first();
				if(isset($get_category_details->name) && $get_category_details->name!=''){
					$value['category_name']=$get_category_details->name;
				}else{
					$value['category_name']='';
				}

				

				$value['weight_array']=$weight_array;
				$value['cost']=($value['price']*(100-$value['discount'])/100);
				$value['title']=$tempName;
				$value['product_image']=$tempImage;
				$value['units']=1;
				$newData['data'][]=$value;
			endforeach; 
			unset($data['data']);
			$data['results']=$newData['data'];
		endif;
		
		if( is_numeric($data['to']) &&  is_numeric($data['from']) ):
			$total=$data['to']-$data['from']+1;
		else:
			$total=0;
		endif; 
		return $this->sendPaginationResponse($data, 'Data retrieved successfully.', $total);  
    }


	public function featuredProductListing(Request $request)
	{
		$newData=array();    
		$data = Product::where('is_featured','yes')->get();  
		
		if( $data && !empty($data) ):
			foreach($data as $value):
				$value->title=$value->prod_name;
				unset($value->prod_name);
				$value->product_image='/public/storage/'.$value->product_image;
				$value->cost=($value->price*(100-$value->discount)/100);
				$newData[]=$value;  
			endforeach;
		endif;   
		return $this->sendResponse($newData, 'Data retrieved successfully.');
	}


	function categoryChild($id) {
		$child_details = DB::table('categories')->where('parent_id', $id)->get();  
		$children = array();
	
		if(count($child_details)>0) {
			# It has children, let's get them.
			$i=0;
			foreach($child_details as $details) {
				# Add the child to the list of children, and get its subchildren
				$children[$details->id] = $this->categoryChild($details->id);
				$i++;
			}
		}
		return $children;
	}


	public function productCategoryListing(Request $request){
		$offset = ($request->get('offset'))?$request->get('offset'):'20';
		$id = $request->category_id;
		$getcategorydetails = DB::table('categories')->where('id', $id)->first();
		if($getcategorydetails->parent_id==0)
		{
			$data = Product::where(function($query) use ($request) {  
				if ($request->get('swadesh_hut_id')):
                    $query->where('swadesh_hut_id', $request->get('swadesh_hut_id'));
				endif;
                if ($request->get('category_id')):
					$categories = $categories = DB::table('categories')->where('parent_id', $request->get('category_id'))->get();  
					$sub_category_id_array = array();
					$child_array = $this->categoryChild($request->get('category_id'));
					$count=0;
					
					foreach($child_array as $key=>$val){
						$sub_category_id_array[$count] = $key;
						$count++;
						if(count($val)>0){
							foreach($val as $key=>$ids){
								$sub_category_id_array[$count] = $key;
								$count++;
							}
						}
					}
                    $query->whereIn('category_id', $sub_category_id_array);
				endif;
                $query->where('status', 'Active');
            })   
			->orderBy('prod_name','ASC')->groupBy('prod_name')->paginate($offset);
			$data=$data->toArray();
		}
		else 
		{
			$data = Product::where(function($query) use ($request) {  
				if ($request->get('swadesh_hut_id')):
                    $query->where('swadesh_hut_id', $request->get('swadesh_hut_id'));
				endif;
                if ($request->get('category_id')):
                    $query->where('category_id', $request->get('category_id'));
				endif;
                $query->where('status', 'Active');
            })   
			->orderBy('prod_name','ASC')->groupBy('prod_name')->paginate($offset);
			$data=$data->toArray();
		}

		$newData=array();
		if( $data['data'] && !empty($data['data']) && count($data['data'])>0 ):
			foreach( $data['data'] as $value ):
				$weight_array = array();
				$initial_weight_array = array(0);
				$i = 0;
				$tempImage=url('/').'/public/storage/'.$value['product_image'];
				$tempName=$value['prod_name'];
				unset($value['specification']);
				unset($value['product_image']);

				$get_same_products_by_name_url = Product::where('prod_name',$value['prod_name'])->get();
				foreach($get_same_products_by_name_url as $pdetails){
					if(!in_array($pdetails->weight_per_pkt,$initial_weight_array)){
						$weight_array[$pdetails->id] = $pdetails->weight_per_pkt.$pdetails->weight_unit;
					}
					$initial_weight_array[$i] = $pdetails->weight_per_pkt;
					$i++;
				}



				unset($value['prod_name']);
				
				$get_category_details = DB::table('categories')->where('id',$value['category_id'])->first();
				if(isset($get_category_details->name) && $get_category_details->name!=''){
					$value['category_name']=$get_category_details->name;
				}else{
					$value['category_name']='';
				}

				

				$value['weight_array']=$weight_array;
				$value['cost']=($value['price']*(100-$value['discount'])/100);
				$value['title']=$tempName;
				$value['product_image']=$tempImage;
				$value['units']=1;
				$newData['data'][]=$value;
			endforeach; 
			unset($data['data']);
			$data['results']=$newData['data'];
		endif;
		if( is_numeric($data['to']) &&  is_numeric($data['from']) ):
			$total=$data['to']-$data['from']+1;
		else:
			$total=0;
		endif;
		
		return $this->sendPaginationResponse($data, 'Data retrieved successfully.', $total);  
	}
}
