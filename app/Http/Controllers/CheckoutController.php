<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Category;

use App\User;

use DB;

use Session;

use Cart;

use Auth;

use App\Notifications\Orderinvoice;

use App\Notifications\OrderinvoiceStore;

use Notification;

use Tzsk\Payu\Facade\Payment;

use App\OnlinePayments;



class CheckoutController extends Controller

{

    public function checkout( Request $request ){

		//Auth::logout();

		if( isset(Auth::user()->id) ):

			echo 'send to shipping';

		else:

			//return view('auth.register');

			//echo 'send to register';die;

			return view('front.users.registration');

			/*

			$swadeshHutDetails = DB::table('swadesh_huts')->where( 'status', 'Active' )->get();



			$productDetails=Cart::getContent();

			//echo '<pre>';print_r($fff);echo '</pre>';

			return view('front.users.registration',compact('productDetails', 'swadeshHutDetails'));

			*/

		endif;

		/*

		print_r($_POST);die;

		$data['order_number']=$request['user_id'];

		$data['user_id']=$request['user_id'];

		$data['total_amount']=$request['total_amount'];

		$data['swadesh_hut_id']=$request['swadesh_hut_id'];

		$data['order_date']=$request['order_date'];

		$data['order_status']='Ordered';

		$data['payment_status']='Unpaid';

		$data['payment_mode']='COD';

		$data['created_at']=date("Y-m-d H:i:s");

		$id=DB::table('order_master')->insertGetId($data);

		if( $id ):

			foreach( Cart::getContent() as $cartData ):

				$productIdName=explode('%',$cartData->name);

				$odData['order_id']=$id;

				$odData['product_id']=$productIdName['0'];

				$odData['quantity']=$cartData->quantity;

				$odData['item_price']=$cartData->price;

				$odData['item_total']=$cartData->price*$cartData->quantity;

				$odData['created_at']=date("Y-m-d H:i:s");

				$id=DB::table('order_details')->insert($odData);

			endforeach;

		endif;

		Cart::clear();

		return view('front.checkout.confirm');

		*/

	}



	public function user_registration( Request $request ){





		if(isset($_POST['new_token'])){



			$this->validate($request, [

				'name'		=> 'required',

				'email'		=> 'required|email|unique:users,email',

				'password'	=> 'required|string|min:8',

				'phone'		=> 'required|min:10',

			]);



			$data['name']=$request['name'];

			$data['email']=$request['email'];

			$data['phone']=$request['phone'];

			$data['password']=bcrypt($request['password']);

			$data['created_at']=date("Y-m-d H:i:s");

			$userId=DB::table('users')->insert($data);

			Auth::loginUsingId($userId);

			return redirect()->route('shipping')

                        ->with('success','Swadesh Huts created successfully');

		}

		/*



		Auth::loginUsingId('2');

		*/



		return view('front.users.registration');

		//print_r($_POST);die;



		//$user = User::create(request(['name', 'email', 'password']));



		//auth()->login($user);

	}



	public function shipping( Request $request ){

		if( !Cart::getContent()->isEmpty() ):

			$countryDetails = DB::table('countries')->where( 'status', 'Active' )->get();

			$stateDetails = DB::table('states')->where( 'country_id', '103' )->get();

			$data['total_amount']=$request->total_amount;

			//$data['order_date']=$request->order_date;

			$categories = DB::table('categories as A')->select('A.id','A.name','A.status','A.category_image','A.position_order','A.description','A.parent_id','B.name as parent_name','A.created_at','A.updated_at')

						->leftJoin('categories as B','A.parent_id','=','B.id')

						->orderBy('A.position_order','ASC')->orderBy('parent_name','ASC')

						->where('A.status','Active')->get();



			if(Session::has('swadesh_hut_id')):

				$get_swadesh_hut_details = DB::table('swadesh_huts')->where('id',Session::get('swadesh_hut_id'))->first();
				$selected_swadesh_hut = Session::get('swadesh_hut_id');
				$cover_area_pincode_array = explode(',',$get_swadesh_hut_details->cover_area_pincodes);
				$cover_area_pincode_array = json_encode($cover_area_pincode_array);

				$siteBarProducts = DB::table('products as A')

					->select('A.id','A.prod_name','A.price', 'A.discount', 'A.stock_alert', 'A.status','A.product_image', 'A.description','B.name as category_name','A.created_at','A.updated_at')

					->leftJoin('categories as B','A.category_id','=','B.id')

					->where('A.swadesh_hut_id', Session::get('swadesh_hut_id'))

					->orderBy('A.prod_name','ASC')->limit(2)->get();

			else:
				$selected_swadesh_hut = '';
				$cover_area_pincode_array = array();
				$cover_area_pincode_array = json_encode($cover_area_pincode_array);
				$siteBarProducts = DB::table('products as A')

					->select('A.id','A.prod_name','A.price', 'A.discount', 'A.stock_alert', 'A.status','A.product_image', 'A.description','B.name as category_name','A.created_at','A.updated_at')

					->leftJoin('categories as B','A.category_id','=','B.id')

					->orderBy('A.prod_name','ASC')->limit(2)->get();

			endif;



			//	PARENT CATEGORY

			$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->limit(12)->get();



			$billingDetails = DB::table('users')

						->where('id', Auth::user()->id)->first();





			return view('front.checkout.shipping',compact('data', 'billingDetails', 'countryDetails', 'stateDetails', 'categories', 'siteBarProducts', 'parentCategory','selected_swadesh_hut','cover_area_pincode_array'));

		else:

			//echo 'Here';die;

			return redirect()->route('sitelink');

		endif;

	}



	public function payment( Request $request ){

		if( !Cart::getContent()->isEmpty() ):

			//ECHO '<PRE>';print_r($_POST);DIE;

			if(isset($_POST['new_token'])){

				$this->validate($request, [

					'shiping_street' => 'required',

					'location' => 'required',

					'city' => 'required',

					'state' => 'required'

				]);

				//return redirect()->route('payment');

				//return redirect()->route('payment')->with('shiping_street', $request->shiping_street);



			}

			//ECHO '<PRE>'; print_r($_POST);die;

			$data['total_amount']=$request->total_amount;

			//$data['order_date']=$request->order_date;

			$data['shiping_street']=$request->shiping_street;

			$data['shiping_street_1']=$request->shiping_street_1;

			$data['location']=$request->location;

			$data['city']=$request->city;

			$data['shiping_pincode']=$request->shiping_pincode;

			$data['state']=$request->state;

			$categories = DB::table('categories as A')->select('A.id','A.name','A.status','A.category_image','A.position_order','A.description','A.parent_id','B.name as parent_name','A.created_at','A.updated_at')

					->leftJoin('categories as B','A.parent_id','=','B.id')

					->orderBy('A.position_order','ASC')->orderBy('parent_name','ASC')

					->where('A.status','Active')->get();



			if(Session::has('swadesh_hut_id')):

				$swadesh_hut_id=Session::get('swadesh_hut_id');

				$siteBarProducts = DB::table('products as A')

					->select('A.id','A.prod_name','A.price','A.status','A.product_image', 'A.description','B.name as category_name','A.created_at','A.updated_at')

					->leftJoin('categories as B','A.category_id','=','B.id')

					->where('A.swadesh_hut_id', $swadesh_hut_id)

					->orderBy('A.prod_name','ASC')->limit(2)->get();

			else:

				$siteBarProducts = DB::table('products as A')

					->select('A.id','A.prod_name','A.price','A.status','A.product_image', 'A.description','B.name as category_name','A.created_at','A.updated_at')

					->leftJoin('categories as B','A.category_id','=','B.id')

					->orderBy('A.prod_name','ASC')->limit(2)->get();

			endif;

			return view('front.checkout.payment',compact('categories', 'siteBarProducts', 'data'));

		else:

			return redirect()->route('product-listing')->with('success','cart is empty');

		endif;

	}



	public function place_order( Request $request ){

		//echo Auth::user()->id;exit;

		if( !Cart::getContent()->isEmpty() ):

			//print_r($_POST);die;

			if(isset($_POST['new_token'])){

				$this->validate($request, [

					'contact_name' => 'required',

					'contact_no' => 'required|numeric',

					'shiping_street' => 'required',

					//'location' => 'required',

					'city' => 'required',

					'state' => 'required',

					'billing_name' => 'required',

					'billing_email' => 'required|email',

					'billing_phone' => 'required|numeric',

					'billing_street' => 'required',

					'billing_city' => 'required',

					'billing_pincode' => 'required',

					'billing_state' => 'required',

				]);

			}

			//echo '<pre>'; print_r($_POST);die;

			$lastId=DB::table('order_master')->latest()->first();

			$get_swadesh_hut_details = DB::table('swadesh_huts')->where('id',Session::get('swadesh_hut_id'))->first();

			if(!empty($lastId) ):

				$lastNo=sprintf("%05d", ($lastId->id+1));

				//$orderNumber=date('Y').'-JSDAGRO-'.$get_swadesh_hut_details->invoice_prefix.'-'.$lastNo;
				$orderNumber=date('Y').'-JSDAGRO-WEB-'.$lastNo;

			else:

				$orderNumber=date('Y').'-JSDAGRO-WEB-00001';

			endif;

			//ECHO $orderNumber;DIE;



			$data['order_number']=$orderNumber;

			$data['user_id']=Auth::user()->id;

			$data['total_amount']=$request->total_amount;

			$data['swadesh_hut_id']=Session::get('swadesh_hut_id');

			$data['order_date']=date('Y-m-d');

			//	SHIPPING DETAILS

			//$data['shipping_name']=$request->contact_name;

			$data['shipping_phone']=$request->contact_no;

			$data['shipping_address']=$request->shiping_street;

			$data['shipping_address1']=$request->shiping_street_1;

			$data['location']=$request->location;

			$data['city']=$request->city;

			$data['pin_code']=$request->shiping_pincode;

			$data['state']=$request->state;

			//	BILLING DETAILS

			$data['billing_name']=$request->billing_name;

			$data['billing_email']=$request->billing_email;

			$data['billing_phone']=$request->billing_phone;

			$data['billing_street']=$request->billing_street.' '.$request->billing_street_1;

			$data['billing_city']=$request->billing_city;

			$data['billing_pincode']=$request->billing_pincode;

			$data['billing_state']=$request->billing_state;



			$data['order_status']='Ordered';

			$data['payment_status']='Unpaid';

			$data['payment_mode']=$request->payment_mode;

			$data['created_at']=date("Y-m-d H:i:s");



            //calculation shipping date time

            //dd(session()->all());



            $get_swadesh_hut_details = DB::table('swadesh_huts')->where('id',Session::get('swadesh_hut_id'))->first();

            

          /*  $a = time();

            echo "<br>ptime".date("h:i a", $a);

			echo "<br>ptimesec". $a;

			$b = strtotime($get_swadesh_hut_details->close_time);

			echo "<br>orginal clotime". $get_swadesh_hut_details->close_time;

			echo "<br>stime". $b; */

			

			date_default_timezone_set('asia/kolkata');

			

            if(time()>strtotime($get_swadesh_hut_details->close_time))

            {

                $data['shipping_date_time']=Date('y:m:d', strtotime('+1 days'));

				$shipping_date_time = Date('Y:m:d H:i:s', strtotime('+1 days'));

			//	$r = 1;

            }

            else

            {

                $data['shipping_date_time']=date("Y-m-d H:i:s");

				$shipping_date_time = date("Y-m-d H:i:s");

		    //	$r = 2;	

            }

           /* echo $shipping_date_time. '<br>';

            

           //date_default_timezone_set('asia/kolkata');

           echo $pdate = date('m/d/Y h:i:s a', time());

			echo '<pre>'; print_r($r);

			echo '<br>'.$shipping_date_time; die; */



			if($_POST['shipping_method']=='cod')

			{
				
				$id=DB::table('order_master')->insertGetId($data);

				$data['order_id']=$id;

				if( $id ):

					foreach( Cart::getContent() as $cartData ):

						$productIdName=explode('%',$cartData->name);

						$odData['order_id']=$data['order_id'];

						$odData['product_id']=$productIdName['0'];

						$odData['quantity']=$cartData->quantity;

						$odData['item_price']=$cartData->price;

						$odData['item_total']=$cartData->price*$cartData->quantity;

						$odData['created_at']=date("Y-m-d H:i:s");



						$id=DB::table('order_details')->insert($odData);

						////for stock update/////////////
						$proDetails = DB::table('products')->where('id', $productIdName['0'])->first();
						$old_stock= $proDetails->available_qty;
						$present_stock= $old_stock - $cartData->quantity;

						$old_ordered_qty= $proDetails->ordered_qty;
						$present_ordered_qty= $old_ordered_qty + $cartData->quantity;

						DB::table('products')->where('id', $productIdName['0'])->update(array('available_qty' => $present_stock,'ordered_qty' => $present_ordered_qty)); 
						/////////for stock update///////////



					endforeach;

				endif;

				Cart::clear();

				Session::forget('total_price');



				$user = User::where('email',$request->billing_email)->first();
				//send invoice via email to user
				Notification::send($user, new Orderinvoice($odData,$data));



				$get_swadesh_hut_details = DB::table('swadesh_huts')->where('id',Session::get('swadesh_hut_id'))->first();
				$swadesh_hut_user_id = $get_swadesh_hut_details->id;
				$get_swadesh_hut_id_details = DB::table('swadesh_hut_users')->where('swadesh_hut_id',$swadesh_hut_user_id)->first();
				$swadesh_user_id = $get_swadesh_hut_id_details->user_id;
				$userstore = User::where('id',$swadesh_user_id)->first();
				//echo "<pre>";
				//print_r($data);
				//echo "----";
				//dd($odData);
				//send invoice via email to store owner
				Notification::send($userstore, new OrderinvoiceStore($odData,$data));









				//send message to user for order complete
				$message = 'Dear User,\nThank you for ordering with us. Your order value is Rs. '.$data['total_amount'].'. Your order will be delivered soon.\nSee you soon,\nTeam JSDGlobal';
				$ch = curl_init();
				$url = "http://164.52.195.161/API/SendMsg.aspx";
				$dataArray = ['uname' => '20210580', 'pass' => '9D9Sq9d9', 'send' => 'JSDGLB', 'dest' => $user->phone, 'msg' => $message];

				$smsdata = http_build_query($dataArray);
				$getUrl = $url."?".$smsdata;
				$getUrl = str_replace('+', '%20', $getUrl);
				$getUrl = str_replace('%5Cn', '%0A', $getUrl);
				$getUrl = str_replace('%2C', ',', $getUrl);
				
			
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($ch, CURLOPT_HTTPAUTH, 0);
				curl_setopt($ch, CURLOPT_URL, $getUrl);
				curl_setopt($ch, CURLOPT_TIMEOUT, 80);
				
				$response = curl_exec($ch);

				if(curl_error($ch)){
					echo 'Request Error:' . curl_error($ch);
				}
				
				curl_close($ch);

				



				//	PARENT CATEGORY

				$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->limit(12)->get();



				$get_order_details = DB::table('order_master')->where('id',$data['order_id'])->first();

				return view('front.checkout.confirm',compact('parentCategory','data','get_order_details','shipping_date_time'));

			}

			else 

			{

			    //dd("pp");

				$product_info = '';

				foreach( Cart::getContent() as $cartData )

				{

					$productIdName=explode('%',$cartData->name);

				//	$product_info.=$productIdName[1].'<br clear="all">';
					$proDetails = DB::table('products')->where('id', $productIdName['0'])->first();

					$product_info.= $productIdName[1].' '.$proDetails->weight_per_pkt.$proDetails->weight_unit;

				}

				 Session::push('order_data', $data);

				

				$attributes = [

					'txnid' => substr(hash('sha256', mt_rand() . microtime()), 0, 20), # Transaction ID.

					'amount' => $data['total_amount'], # Amount to be charged.

					'productinfo' => $product_info,

					'firstname' => $data['billing_name'], # Payee Name.

					'email' => $data['billing_email'], # Payee Email Address.

					'phone' => $data['billing_phone'], # Payee Phone Number.

					'address1' => $data['shipping_address'],

					'address2' => $data['shipping_address1'],

					'city' => $data['city'],

					'state' => $data['state'],

					'zipcode' => $data['pin_code'],

					'country' => 'India'

				];

				

				// return Payment::make($attributes, function ($then) {

				// 	$then->redirectRoute('payment.status');

				// });



				$productDetails = Cart::getContent();

				$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->limit(12)->get();

				

				//echo "<pre>";

				//echo $productDetails;

				return view('front.checkout.paybiz',compact('productDetails', 'parentCategory','attributes'));



			}



		else:

			return redirect()->route('sitelink')->with('success','cart is empty');

		endif;

		

		

	}



	public function checkPin(Request $request)
	{
		//echo Session::get('swadesh_hut_id').'*****';exit;
		$input = $request->all();
		$billing_pincode = $input['billing_pincode'];

		
		$get_swadesh_hut_details = DB::table('swadesh_huts')->where('id',Session::get('swadesh_hut_id'))->first();

		$cover_area_pincode_array = explode(',',$get_swadesh_hut_details->cover_area_pincodes);
		print_r($cover_area_pincode_array);exit;
		if(!in_array($billing_pincode,$cover_area_pincode_array))
		{
			return response()->json(['error'=>1,'msg'=>'Sorry we are unavailable in Your shipping pincode']);
		}
		else
		{
			//3.Session::put('swadesh_hut_id',3);
			return response()->json(['error'=>0]);
		}
	}



	public function success(Request $request)

	{


			$order_data = Session::get('order_data');
			

			$order_data = $order_data[0];

			$lastId=DB::table('order_master')->latest()->first();

			$lastNo=sprintf("%05d", ($lastId->id+1));

			$orderNumber=date('Y').'-JSDAGRO-'.$lastNo;



			$data['order_number']=$orderNumber;

			$data['user_id']=$order_data['user_id'];

			$data['total_amount']=$request['amount'];

			$data['swadesh_hut_id']=$order_data['swadesh_hut_id'];

			$data['order_date']=date('Y-m-d');

			//	SHIPPING DETAILS

			//$data['shipping_name']=$request->contact_name;

			$data['shipping_phone']=$order_data['shipping_phone'];

			$data['shipping_address']=$order_data['shipping_phone'];

			$data['shipping_address1']=$order_data['shipping_address1'];

			$data['location']=$order_data['location'];

			$data['city']=$order_data['city'];

			$data['pin_code']=$order_data['pin_code'];

			$data['state']=$order_data['state'];

			//	BILLING DETAILS

			$data['billing_name']=$order_data['billing_name'];

			$data['billing_email']=$order_data['billing_email'];

			$data['billing_phone']=$order_data['billing_phone'];

			$data['billing_street']=$order_data['billing_street'];

			$data['billing_city']=$order_data['billing_city'];

			$data['billing_pincode']=$order_data['billing_pincode'];

			$data['billing_state']=$order_data['billing_state'];



			$data['order_status']='Ordered';

			$data['payment_status']='Paid';

			$data['payment_mode']='Online';

			$data['created_at']=date("Y-m-d H:i:s");



			$get_swadesh_hut_details = DB::table('swadesh_huts')->where('id',3)->first();

			date_default_timezone_set('asia/kolkata');

			if(time()>strtotime($get_swadesh_hut_details->close_time))

			{

				$data['shipping_date_time']=Date('y:m:d', strtotime('+1 days'));

				$shipping_date_time = Date('y:m:d', strtotime('+1 days'));

			}

			else

			{

				$data['shipping_date_time']=date("Y-m-d H:i:s");

				$shipping_date_time = date("Y-m-d H:i:s");

			}

			$id=DB::table('order_master')->insertGetId($data);

			$data['order_id']=$id;

			if( $id ):

				foreach( Cart::getContent() as $cartData ):

					$productIdName=explode('%',$cartData->name);

					$odData['order_id']=$data['order_id'];

					$odData['product_id']=$productIdName['0'];

					$odData['quantity']=$cartData->quantity;

					$odData['item_price']=$cartData->price;

					$odData['item_total']=$cartData->price*$cartData->quantity;

					$odData['created_at']=date("Y-m-d H:i:s");



					$id=DB::table('order_details')->insert($odData);

					////for stock update/////////////
					$proDetails = DB::table('products')->where('id', $productIdName['0'])->first();
					$old_stock= $proDetails->available_qty;
					$present_stock= $old_stock - $cartData->quantity;

					$old_ordered_qty= $proDetails->ordered_qty;
					$present_ordered_qty= $old_ordered_qty + $cartData->quantity;

					DB::table('products')->where('id', $productIdName['0'])->update(array('available_qty' => $present_stock,'ordered_qty' => $present_ordered_qty)); 
					/////////for stock update///////////

				endforeach;

			endif;

			

			$user = User::where('email',$order_data['billing_email'])->first();
			//send invoice via email
			Notification::send($user, new Orderinvoice($odData,$data));


			//send invoice via email to store owner
			$get_swadesh_hut_details = DB::table('swadesh_huts')->where('id',3)->first();
			$swadesh_hut_user_id = $get_swadesh_hut_details->id;
			$get_swadesh_hut_id_details = DB::table('swadesh_hut_users')->where('swadesh_hut_id',$swadesh_hut_user_id)->first();
			$swadesh_user_id = $get_swadesh_hut_id_details->user_id;
			$userstore = User::where('id',$swadesh_user_id)->first();
			Notification::send($userstore, new OrderinvoiceStore($odData,$data));


			//send message to user for order complete
			$message = 'Dear User,\nThank you for ordering with us. Your order value is Rs. '.$data['total_amount'].'. Your order will be delivered soon.\nSee you soon,\nTeam JSDGlobal';
			$ch = curl_init();
			$url = "http://164.52.195.161/API/SendMsg.aspx";
			$dataArray = ['uname' => '20210580', 'pass' => '9D9Sq9d9', 'send' => 'JSDGLB', 'dest' => $user->phone, 'msg' => $message];

			$smsdata = http_build_query($dataArray);
			$getUrl = $url."?".$smsdata;
			$getUrl = str_replace('+', '%20', $getUrl);
			$getUrl = str_replace('%5Cn', '%0A', $getUrl);
			$getUrl = str_replace('%2C', ',', $getUrl);
			
		
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HTTPAUTH, 0);
			curl_setopt($ch, CURLOPT_URL, $getUrl);
			curl_setopt($ch, CURLOPT_TIMEOUT, 80);
			
			$response = curl_exec($ch);

			if(curl_error($ch)){
				echo 'Request Error:' . curl_error($ch);
			}
			
			curl_close($ch);

			



			


			Cart::clear();

			Session::forget('total_price');

			Session::forget('order_data');


			//Save Online Transaction Details in Online Payments Table
			$transaction_array = array(

				'txn_id'=>$request['txnid'],

				'user_id'=>Auth::user()->id,

				'amount'=>$request['amount'],

				'product_info'=>$request['productinfo'],

				'user_email'=>$request['email'],

				'user_phone'=>$request['phone']

			);

			OnlinePayments::create($transaction_array);





			$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->limit(12)->get();

			$get_order_details = DB::table('order_master')->where('id',$data['order_id'])->first();

			return view('front.checkout.confirm',compact('parentCategory','data','get_order_details','shipping_date_time'));

		

	}



	public function failure(Request $request)

	{

		echo '<strong style="color:red;">Oops!!Payment cannot be processed!!</strong><br><br><a style="color:green;" href="'.url('/').'/cart"><strong>Please Try Again</strong></a>';

	}



}

