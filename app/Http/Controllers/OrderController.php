<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Category;
use Session;
use Cart;
use Auth;
use App\Order_master;
use App\User;
use App\Notifications\OrderCancel;
use App\Notifications\OrderReturn;
use Notification;

class OrderController extends Controller
{
	public function myOrder(Request $request){
        $user = Auth::user();
		//echo Auth::user()->id;
		//echo '<pre>';print_r($user);die;
        //if($user){
            $records=DB::table('order_master')
            ->select('users.name as customer_name','swadesh_huts.location_name as hut_name','order_master.*')
            //->where('order_master.user_id',$user->id)
            ->where('order_master.user_id',Auth::user()->id)
            ->join('users','order_master.user_id','users.id')
            ->join('swadesh_huts','order_master.swadesh_hut_id','swadesh_huts.id')
			->orderBy('id','DESC')->paginate(10);
        /*
		}else{
            $records=DB::table('order_master')
            ->select('users.name as customer_name','swadesh_huts.location_name as hut_name','order_master.*')
            ->join('users','order_master.user_id','users.id')
            ->join('swadesh_huts','order_master.swadesh_hut_id','swadesh_huts.id')->get();
        }
		*/
		//echo '<pre>';print_r($products);die;
		$categories = DB::table('categories as A')->select('A.id','A.name','A.status','A.category_image','A.position_order','A.description','A.parent_id','B.name as parent_name','A.created_at','A.updated_at')
					->leftJoin('categories as B','A.parent_id','=','B.id')
					->orderBy('A.position_order','ASC')->orderBy('parent_name','ASC')
					->where('A.status','Active')->get();
					 //	PARENT CATEGORY
		$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->limit(12)->get();
        
					
        return view('front.orders.listing',compact('records','categories','parentCategory'));
	}
	
	public function myOrderDetails($id){
		$details=DB::table('order_master')
		->select('users.name as customer_name','swadesh_huts.location_name as hut_name','order_master.*')
		->join('users','order_master.user_id','users.id')
		->join('swadesh_huts','order_master.swadesh_hut_id','swadesh_huts.id')
		->where( 'order_master.id', $id )->first();
		//
		$orderDetails=DB::table('order_details')
		->select('products.*','order_details.*')
		->join('products','order_details.product_id','products.id')
		->where( 'order_details.order_id', $id )->get();
		//print_r($orderDetails);exit;
		$categories = DB::table('categories as A')->select('A.id','A.name','A.status','A.category_image','A.position_order','A.description','A.parent_id','B.name as parent_name','A.created_at','A.updated_at')
					->leftJoin('categories as B','A.parent_id','=','B.id')
					->orderBy('A.position_order','ASC')->orderBy('parent_name','ASC')
					->where('A.status','Active')->get();
					 //	PARENT CATEGORY
		$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->limit(12)->get();
		return view('front.orders.view',compact('details', 'orderDetails','categories','parentCategory'));
    }


	public function cancelOrder(Request $request)
	{
		$input = $request->all();
		$order_id = $input['order_id'];

		$order_status_array = array('order_status'=>'Cancelled');

		$order_master = Order_master::find($order_id);
        $order_master->update($order_status_array);

		$user = User::where('id',$order_master->user_id)->first();
		//send Order Cancel Mail
		Notification::send($user, new OrderCancel($user,$order_id));

		$headers='MIME-Version: 1.0' . "\r\n";
		$headers.='Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers.="From: no-reply@swadeshhaat.co.in";

		$subject = "Order Cancel Request";
		$txt = $user->email." Has Made A Cancel Request for <a href='".url('/')."/order-details/".$order_id."'>This Order</a>";
		mail('subhasish@webee.co.in',$subject,$txt,$headers);

		return response()->json(['msg'=>'Your Order Has Been Cancelled Successfully']);
	}


	public function returnOrder(Request $request)
	{
		$input = $request->all();
		$order_id = $input['order_id'];

		$order_status_array = array('order_status'=>'Returned');

		$order_master = Order_master::find($order_id);
        $order_master->update($order_status_array);

		$user = User::where('id',$order_master->user_id)->first();
		//send Order Return Mail
		Notification::send($user, new OrderReturn($user,$order_id));

		$headers='MIME-Version: 1.0' . "\r\n";
		$headers.='Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers.="From: no-reply@jsdagro.com";

		$subject = "Order Return Request";
		$txt = $user->email." Has Made A Return Request for <a href='".url('/')."/order-details/".$order_id."'>This Order</a>";
		mail('tarun.webee@gmail.com',$subject,$txt,$headers);

		return response()->json(['msg'=>'Your Return request Raised Successfully!!']);
	}




	public function myProfile(Request $request){
		//echo Auth::user()->id;die;
		
		if(isset($_POST['_token'])){
			$this->validate($request, [
				'name' => 'required',
				'phone' => 'required|numeric',
				'address_1' => 'required',
				'city' => 'required',
				'state' => 'required',
				'pincode' => 'required',
				'state' => 'required',
			]); 
			//echo '<pre>';print_r($_POST);die;  
			
			$data['name']		= $request['name'];
			$data['phone']		= $request['phone'];
			$data['address_1']  = $request['address_1'];
			$data['company_name']  = $request['company_name'];
			$data['city']		= $request['city'];
			$data['state']		= $request['state'];
			$data['pincode']	= $request['pincode'];
			$data['address_2']	= $request['address_2'];
			$data['updated_at']	= date("Y-m-d H:i:s"); 
			DB::table('users')->where( 'id', Auth::user()->id )->update($data);  
		}
		
		
		$details=DB::table('users')
		->where( 'id', Auth::user()->id )->first();
		//$countryDetails = DB::table('countries')->where( 'status', 'Active' )->get();
		$stateDetails = DB::table('states')->where( 'country_id', '103' )->get();
		
		
			$categories = DB::table('categories as A')->select('A.id','A.name','A.status','A.category_image','A.position_order','A.description','A.parent_id','B.name as parent_name','A.created_at','A.updated_at')
					->leftJoin('categories as B','A.parent_id','=','B.id')
					->orderBy('A.position_order','ASC')->orderBy('parent_name','ASC')
					->where('A.status','Active')->get();
					 //	PARENT CATEGORY
		$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->limit(12)->get();
		
			
		return view('front.orders.profile',compact('details', 'stateDetails','categories','parentCategory'));
	}


}
