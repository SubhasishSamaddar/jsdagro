<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Order_master;
use Validator;
use DB;
use Auth;
use App\Notifications\OrderCancel;
use App\Notifications\OrderReturn;

class OrderMasterController extends BaseController   
{
	public function placeOrder(Request $request){
		$this->validate($request, [
			'total_amount' => 'required',
			'swadesh_hut_id' => 'required|numeric',
			'payment_mode' => 'required',
			'payment_status' => 'required',
			'shipping_phone' => 'required',
			'shipping_address' => 'required',
			'location' => 'required',
			'city' => 'required',
			'pin_code' => 'required|numeric',
			'state' => 'required',
			'billing_name' => 'required',
			'billing_email' => 'required|email',
			'billing_phone' => 'required',
			'billing_street' => 'required',
			'billing_state' => 'required',
			'billing_pincode' => 'required'  
		]);
		
		//$lastId=Order_master::all()->last();
		$lastId=Order_master::latest()->first();
		//print_r($lastId);die;
		if(!empty($lastId) ):
			$lastNo=sprintf("%05d", ($lastId->id+1));
			$orderNumber=date('Y').'-JSDAGRO-'.$lastNo;
		else:
			$orderNumber=date('Y').'-JSDAGRO-00001';
		endif;
		$input['user_id']=Auth::user()->id;
		$input['total_amount']=$request->total_amount;
		$input['swadesh_hut_id']=$request->swadesh_hut_id;
		$input['payment_mode']=$request->payment_mode;
		$input['payment_status']=$request->payment_status;
		$input['shipping_phone']=$request->shipping_phone;
		$input['shipping_address']=$request->shipping_address;
		$input['location']=$request->location;
		$input['city']=$request->city;
		$input['pin_code']=$request->pin_code;
		$input['state']=$request->state;
		$input['billing_name']=$request->billing_name;
		$input['billing_email']=$request->billing_email;
		$input['billing_phone']=$request->billing_phone;
		$input['billing_street']=$request->billing_street;
		$input['billing_state']=$request->billing_state;
		$input['billing_city']=$request->billing_city;
		$input['billing_pincode']=$request->billing_pincode;
		$input['order_number']=$orderNumber;
		$input['order_date']=date('Y-m-d');
		//print_r($input);die;
		$data = Order_master::create($input);
		//print_r($package_location);die;
		return $this->sendResponse($data->toArray(), 'Data retrieved successfully.');
	}
	
	public function listing(Request $request){
		$sort = ($request->get('sort')=='DESC')?'DESC':'ASC';
		$orderBy = ($request->get('orderby'))?$request->get('orderby'):'created_at';
		$offset = ($request->get('offset'))?$request->get('offset'):'15';  
		$data = Order_master::where(function($query) use ($request) {
			if ($request->get('order_number')):
				$query->where('order_number','like', "%".$request->get('order_number')."%");
			endif;
		})      
		->where('user_id', Auth::user()->id)
		->orderBy($orderBy,$sort)->paginate($offset);
		//$data = Order_master::where('user_id',auth()->user()->id)->orderBy($orderBy,$sort)->paginate($offset);
		//print_r($data);//die;
		//echo '====>'.$data->total;die;
		$data=$data->toArray();
		if( is_numeric($data['to']) &&  is_numeric($data['from']) ):
			$total=$data['to']-$data['from']+1;
		else:
			$total=0;  
		endif;  
		return $this->sendPaginationResponse($data, 'Data retrieved successfully.', $total);  
   
		//return $this->sendResponse($data->toArray(), 'Data retrieved successfully.');
	}
	
	public function orderDetails(Request $request){ 
		$data = Order_master::where('id', $request->id)->first();
		//print_r($data);die;
		//$data['details'] = DB::table('order_details')->where('order_id', $request->id)->get();
		
		$data['details'] = DB::table('order_details as A')
            ->where('order_id', $request->id)
            ->select('A.*', 'B.prod_name as title','B.product_image as product_image')
            ->leftJoin('products as B','A.product_id','=','B.id')
			//->leftJoin('swadesh_huts as C','A.swadesh_hut_id','=','C.id')
            //->orderBy('A.prod_name','ASC')
			->get();
		
		return $this->sendSingleResponse($data, 'Data retrieved successfully.');
	}



	public function cancelOrder(Request $request)
	{
		$input = $request->all();
		$order_id = $input['order_id'];
		$data = Order_master::where('id', $order_id)->first();
		$order_status_array = array('order_status'=>'Cancelled');
		$order_master = Order_master::find($order_id);
        $order_master->update($order_status_array);

		$user = User::where('id',$order_master->user_id)->first();
		//send Order Cancel Mail
		Notification::send($user, new OrderCancel($user,$order_id));
		//send Order Cancel Mail To Admin
		$headers='MIME-Version: 1.0' . "\r\n";
		$headers.='Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers.="From: no-reply@swadeshhaat.co.in";
		$subject = "Order Cancel Request";
		$txt = $user->email." Has Made A Cancel Request for <a href='".url('/')."/order-details/".$order_id."'>This Order</a>";
		mail('subhasish@webee.co.in',$subject,$txt,$headers);

		return $this->sendSingleResponse($data, 'Order Cancelled successfully.');
	}



	public function returnOrder(Request $request)
	{
		$input = $request->all();
		$order_id = $input['order_id'];
		$data = Order_master::where('id', $order_id)->first();
		$order_status_array = array('order_status'=>'Returned');

		$order_master = Order_master::find($order_id);
        $order_master->update($order_status_array);

		$user = User::where('id',$order_master->user_id)->first();
		//send Order Return Mail
		Notification::send($user, new OrderReturn($user,$order_id));
		//send Order Return Mail To Admin
		$headers='MIME-Version: 1.0' . "\r\n";
		$headers.='Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers.="From: no-reply@swadeshhaat.co.in";
		$subject = "Order Return Request";
		$txt = $user->email." Has Made A Return Request for <a href='".url('/')."/order-details/".$order_id."'>This Order</a>";
		mail('subhasish@webee.co.in',$subject,$txt,$headers);

		return $this->sendSingleResponse($data, 'Order Returned successfully.');
	}




	
	public function orderInduvisualDetails(Request $request){ 
		$omData = Order_master::where('id', $request->id)->first();
		$omData=$omData->toArray();
		//print_r($omData);die;
		$data = DB::table('order_details as A')
				->where('order_id', $request->id)
				->select('A.*', 'B.prod_name as title','B.product_image as product_image')
				->leftJoin('products as B','A.product_id','=','B.id')
				->get();
		$data=$data->toArray();
		//print_r($data);die; 
		$newData=array();
		foreach( $data as $value ):
			//print_r($value);die; 
			$tempImage=url('/').'/public/storage/'.$value->product_image;
			unset($value->product_image);
			$value->product_image=$tempImage;
			$value->order_number=$omData['order_number'];
			$value->total_amount=$omData['total_amount'];
			$value->swadesh_hut_id=$omData['swadesh_hut_id'];
			$value->order_date=$omData['order_date'];
			$value->order_status=$omData['order_status'];
			$value->payment_mode=$omData['payment_mode'];
			$value->payment_status=$omData['payment_status'];
			$value->shipping_phone=$omData['shipping_phone'];
			$value->shipping_address=$omData['shipping_address'];
			$value->shipping_address1=$omData['shipping_address1'];
			$value->shipping_location=$omData['location'];
			$value->shipping_city=$omData['city'];
			$value->shipping_pin_code=$omData['pin_code'];
			$value->shipping_state=$omData['state'];
			$value->billing_name=$omData['billing_name'];
			$value->billing_email=$omData['billing_email'];
			$value->billing_phone=$omData['billing_phone'];
			$value->billing_street=$omData['billing_street'];
			$value->billing_city=$omData['billing_city'];
			$value->billing_pincode=$omData['billing_pincode'];
			$value->billing_state=$omData['billing_state'];
			$newData[]=$value;
			//$newData[]=$omData;
		endforeach;     
			
		return $this->sendResponse($newData, 'Data retrieved successfully.');
	}
	
	
	
	public function checkPicode(Request $request){ 
		/*
		$this->validate($request, [
							'pincode' => 'required',
						]);
		*/
		$data = DB::table('swadesh_huts')
				->where('cover_area_pincodes', 'like', '%'.$request->pincode.'%')
				->first();
		//print_r($data);die;    
		return $this->sendSingleResponse($data, 'Data retrieved successfully.');
	}
	
	public function placeOrderDetails(Request $request){
		$this->validate($request, [
			'order_id'		=> 'required|numeric',  
			'product_id'	=> 'required',
			'product_name'	=> 'required',
			'product_image'	=> 'required',
			'sku'			=> 'required',
			'weight_per_pkt'=> 'required|numeric',
			'weight_unit'	=> 'required',  
			'quantity'		=> 'required|numeric',
			'item_price'	=> 'required',
			'cgst'			=> 'required',
			'sgst'			=> 'required',
			'igst'			=> 'required',
		]);
		$odData['order_id']=$request->order_id;
		$odData['product_id']=$request->product_id;
		$odData['product_name']=$request->product_name;
		$odData['product_image']=$request->product_image;
		$odData['weight_unit']=$request->weight_unit;  
		$odData['weight_per_pkt']=$request->weight_per_pkt;
		$odData['sku']=$request->sku;
		$odData['quantity']=$request->quantity;
		$odData['item_price']=$request->item_price;
		$odData['item_total']=$request->item_price*$request->quantity;
		$odData['cgst']=$request->cgst;
		$odData['sgst']=$request->sgst;
		$odData['igst']=$request->igst;
		$odData['created_at']=date("Y-m-d H:i:s");
		$lastId=DB::table('order_details')->insertGetId($odData);
		$data=DB::table('order_details')->where('id', $lastId)->get();  	
		return $this->sendResponse($data->toArray(), 'Data retrieved successfully.');
	
	}
	
	public function placeOrderDetails1(Request $request){ 

		if( $request->name && !empty($request->name ) ):
			//$customer = json_decode($request->customer); 
			$lastId=Order_master::latest()->first();
			if(!empty($lastId) ):
				$lastNo=sprintf("%05d", ($lastId->id+1));
				$orderNumber=date('Y').'-JSDAGRO-'.$lastNo;
			else:
				$orderNumber=date('Y').'-JSDAGRO-00001';
			endif;
			$input['user_id']=Auth::user()->id;
			$input['total_amount']=$request->cartTotal;
			$input['swadesh_hut_id']=$request->swadesh_hut_id;
			$input['payment_mode']='COD';
			$input['payment_status']='Unpaid';
			$input['shipping_phone']=$request->phone;
			$input['shipping_address']=$request->street;
			$input['location']=$request->location;
			$input['city']=$request->city;
			$input['pin_code']=$request->pincode;
			$input['state']='88';
			$input['billing_name']=$request->name;
			$input['billing_email']=$request->email;
			$input['billing_phone']=$request->phone;
			$input['billing_street']=$request->street;
			$input['billing_state']='88';
			$input['billing_city']=$request->city;
			$input['billing_pincode']=$request->pincode;
			$input['order_number']=$orderNumber;
			$input['order_date']=date('Y-m-d');
			$data = Order_master::create($input);
			if( $data ):
				
				if( $request->cartItems && !empty($request->cartItems) ):
					$cartItems = $request->cartItems;
					if( $request->cartItems && !empty($request->cartItems) ):
						$found=0;
						foreach($cartItems as $item)
						{
							$odData['order_id']=$data->id;
							$odData['product_id']=$item['id'];
							$odData['product_name']=$item['title'];
							$odData['product_image']=$item['product_image'];
							$odData['weight_unit']=$item['weight_unit'];  
							$odData['weight_per_pkt']=$item['weight_per_pkt'];
							$odData['sku']=$item['sku'];
							$odData['quantity']=$item['units'];
							$odData['item_price']=$item['cost'];
							$odData['item_total']=$item['cost']*$item['units'];
							$odData['cgst']=$item['cgst'];
							$odData['sgst']=$item['sgst'];
							$odData['igst']=$item['igst'];
							$odData['created_at']=date("Y-m-d H:i:s");
							$lastId=DB::table('order_details')->insertGetId($odData);
							$ddata=DB::table('order_details')->where('id', $lastId)->get(); 
							if($ddata):
								$found=1;
							endif;
						}
						if( $ddata ):
							return response()->json(['order_id' => $data->id,'message' => 'Successfully order created!' ], 200);
							//return $this->sendResponse($data->id, 'order created successfully');
						endif;
					endif;	  
				endif;
			endif;	  
		endif;
			
	
	die;    
	//print_r($request->all());die;
	//echo $request->swadesh_hut_id;
	//echo $request->cartItems;
	//echo $request->cartItems; 
	//$newData=json_decode($request->newcart, true);	
	//print_r($newData);
	if( $request->cartItems ):
		//echo 'here';
		//$data=json_encode($request->cartItems);
		$data=json_decode($request->cartItems, true);
		//$data=$request->cartItems;
		//print_r($data->toArray());
		//$data = json_encode( $request->cartItems);
		//$data = json_decode( $request->cartItems );
		print_r($data);die;    
		//if( !empty($data) ):
			foreach( $data as $value ):
				echo $value->available_qty;
			endforeach;
		//endif;
		
		//print_r($data);  
	endif;
	
	
	}
	
	
}
