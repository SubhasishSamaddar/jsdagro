<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;



use DB;



use App\Category;



use App\Product;



use Session;



use Auth;



use App\User;



use Redirect;



use Illuminate\Support\Facades\Crypt;











class PosController extends Controller



{



	



    public function login()



    {



        $title = 'Login';



        



        $categories = DB::table('categories as A')->select('A.id','A.name','A.status','A.category_image','A.position_order','A.description','A.parent_id','B.name as parent_name','A.created_at','A.updated_at')



					->leftJoin('categories as B','A.parent_id','=','B.id')



					->orderBy('A.position_order','ASC')->orderBy('parent_name','ASC')



					->where('A.status','Active')->get();



        



        //	PARENT CATEGORY



		$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->limit(12)->get();



    



        return view('pos.login', compact('categories','parentCategory'));



    }	





	public function logout(Request $request)

	{

		$request->session()->put('pos_islogged_in', false);  

		return redirect()->route('poslogin')->with('message', 'Logged Out Successfully!!');

	}



	







    public function posloginsubmit(Request $request)



    {



        $this->validate($request, [            



            'email' => 'required',



            'password' => 'required'



        ]); 







        $username = $request['email'];



        $password = $request['password'];



        $users_count = DB::table('swadeshhut_pos')



                        ->where('userName', '=', $username)



                        ->where('password', '=', $password)



                        ->count();



                        //dd($users_count);



        if($users_count >0){



            $users = DB::table('swadeshhut_pos')

            ->where('userName', '=', $username)

            ->where('password', '=', $password)

            ->first();



            $request->session()->put('pos_swadesh_hut', $users->swadeshhut_id);  



			$request->session()->put('pos_islogged_in', true);  



            return redirect()->route('posdashboard')



            ->with('success','logged in successfully');







        }else{



            //return redirect()->route('poslogin')



            //->with('errors','User Name and password mismatch.');



            Session::flash('message', "User Name and password mismatch.");



            return Redirect::back();







        }







    }







    



	public function posdashboard(Request $request)



    {

		if(!Session::get('pos_islogged_in'))

		{

			return redirect()->route('poslogin')->with('message', 'Login First To Continue!!!');

		}

        //dd("pos dash board");



        $pageTitle='POS Dashboard';  



		$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->limit(12)->get();



        
		$total_products = Product::Where('swadesh_hut_id',Session::get('pos_swadesh_hut'))->get();

		if(count($total_products)>1000){
			$total_page = (count($total_products)/50)+1;
			$all_products = Product::Where('swadesh_hut_id',Session::get('pos_swadesh_hut'))->orderBy('created_at', 'DESC')->paginate(50);
		}
		else{
			$total_page = (count($total_products)/25)+1;
			$all_products = Product::Where('swadesh_hut_id',Session::get('pos_swadesh_hut'))->orderBy('created_at', 'DESC')->paginate(25);
		}
		

		$pagination_html='<ul class="pagination" role="navigation">';
        if($total_page>=1)
        {
            for($i=1;$i<=$total_page;$i++)
            {
                if($i<=20)
                {
                    $pagination_html.='<li class="page-item"><a class="page-link" href="#" onclick="showpossearchdata(\''.$i.'\')">'.$i.'</a></li>';
                }
                
            }
        }
        $pagination_html.='</ul>';



		$registered_users = DB::table('users')->where('user_type','Customer')->distinct()->get();



		return view('pos.dashboard',compact('parentCategory', 'pageTitle','all_products','registered_users','total_page','pagination_html'));



    }





    public function place_order( Request $request ){



			

			$lastId=DB::table('order_master')->latest()->first();



			if(!empty($lastId) ):

				$lastNo=sprintf("%05d", ($lastId->id+1));

				$get_swadesh_hut_details = DB::table('swadesh_huts')->where('id',Session::get('pos_swadesh_hut'))->first();

				$orderNumber=date('Y').'-JSDAGRO-'.$get_swadesh_hut_details->invoice_prefix.'-'.$lastNo;

			else:

				$orderNumber=date('Y').'-'.$get_swadesh_hut_details->invoice_prefix.'-JSDAGRO-00001';

			endif;



			$data['order_number']=$orderNumber;



			$data['total_amount']=$request->form_total_cart_amount;



			$data['swadesh_hut_id']=Session::get('pos_swadesh_hut');



			$data['order_date']=date('Y-m-d');



			//	SHIPPING DETAILS



			if($request->billing_user_id!='')

			{

				



				$data['shipping_phone']=$request->billing_phone;



				$data['shipping_address']=$request->billing_address;



				$data['shipping_address1']=$request->billing_address;



				$data['location']=$request->billing_city;



				$data['city']=$request->billing_city;



				$data['pin_code']=$request->billing_postal_code;



				$data['state']='West Bengal';



				//	BILLING DETAILS



				$data['billing_name']=$request->billing_name;



				$data['billing_email']=$request->billing_email;



				$data['billing_phone']=$request->billing_phone;



				$data['billing_street']=$request->billing_address;



				$data['billing_city']=$request->billing_city;



				$data['billing_pincode']=$request->billing_postal_code;



				$data['billing_state']='West Bengal';



				$data['user_id']=$request->billing_user_id;

			}

			else 

			{

				if($request->billing_name!=''){

					$ano_user_billing_name = $request->billing_name;

				}

				else {

					$ano_user_billing_name = 'Anonymous';

				}



				if($request->billing_email!=''){

					$ano_user_billing_email = $request->billing_email;

				}

				else {

					$ano_user_billing_email = 'anonymous@gmail.com';

				}



				if($request->billing_phone!=''){

					$ano_user_billing_phone = $request->billing_phone;

				}

				else {

					$ano_user_billing_phone = '123456790';

				}



				if($request->billing_address!=''){

					$ano_user_billing_address = $request->billing_address;

				}

				else {

					$ano_user_billing_address = 'Anonymous Address';

				}



				if($request->billing_city!=''){

					$ano_user_billing_city = $request->billing_city;

				}

				else {

					$ano_user_billing_city = 'Kolkata';

				}



				if($request->billing_pincode!=''){

					$ano_user_billing_pincode = $request->billing_pincode;

				}

				else {

					$ano_user_billing_pincode = '712232';

				}



				$data['shipping_phone']=$ano_user_billing_phone;



				$data['shipping_address']=$ano_user_billing_address;



				$data['shipping_address1']=$ano_user_billing_address;



				$data['location']=$ano_user_billing_city;



				$data['city']=$ano_user_billing_city;



				$data['pin_code']=$ano_user_billing_pincode;



				$data['state']='West Bengal';



				//	BILLING DETAILS



				$data['billing_name']=$ano_user_billing_name;



				$data['billing_email']=$ano_user_billing_email;



				$data['billing_phone']=$ano_user_billing_phone;



				$data['billing_street']=$ano_user_billing_address;



				$data['billing_city']=$ano_user_billing_city;



				$data['billing_pincode']=$ano_user_billing_pincode;



				$data['billing_state']='West Bengal';



				$data['user_id']=substr(time(),4,6)+rand(123465,987125);



				if($request->billing_name!='' && $request->billing_email!='')

				{

					$customer_details_array = array('user_id'=>$data['user_id'],'name'=>$data['billing_name'],'email'=>$data['billing_email'],'phone'=>$data['billing_phone'],'address'=>$data['billing_street'],'state'=>$data['billing_state'],'city'=>$data['billing_city'],'pincode'=>$data['billing_pincode']);

					DB::table('pos_customer_details')->insert($customer_details_array);

				}



			}







			$data['order_status']='Ordered';



			if(isset($request->payment_type) && ($request->payment_type=='cash_on_delivery' || $request->payment_type=='cash_at_store' || $request->payment_type=='mobile_payment'))

			{

				$data['payment_status']='Unpaid';

			}

			else 

			{

				$data['payment_status']='Paid';

			}

			



			$data['payment_mode']='COD';



			$data['shipping_type']=$request->shipping_type;



			$data['created_at']=date("Y-m-d H:i:s");







            //calculation shipping date time

            $get_swadesh_hut_details = DB::table('swadesh_huts')->where('id',Session::get('pos_swadesh_hut'))->first();



			date_default_timezone_set('asia/kolkata');

            if(time()>strtotime($get_swadesh_hut_details->close_time))

            {

                $data['shipping_date_time']=Date('y:m:d', strtotime('+1 days'));

				$shipping_date_time = Date('Y:m:d H:i:s', strtotime('+1 days'));

            }

            else

            {

                $data['shipping_date_time']=date("Y-m-d H:i:s");

				$shipping_date_time = date("Y-m-d H:i:s");



            }

			if(isset($request->form_discount_amount) && $request->form_discount_amount!=''){
				$data['pos_order_discount']=$request->form_discount_amount;
			}
			else{
				$data['pos_order_discount']=0.00;
			}
			



				$id=DB::table('order_master')->insertGetId($data);



				$data['order_id']=$id;



				if( $id ){



					for( $i=0; $i<count($request->form_product_id); $i++ ){



						



						$odData['order_id']=$data['order_id'];



						$odData['product_id']=$request->form_product_id[$i];



						$odData['quantity']=$request->form_product_qty[$i];



						$odData['item_price']=$request->form_product_price[$i];



						$odData['item_total']=$request->form_product_price[$i];



						$odData['created_at']=date("Y-m-d H:i:s");



						$get_proudct_details = DB::table('products')->where('id',$request->form_product_id[$i])->first();

						$available = $get_proudct_details->available_qty - $request->form_product_qty[$i];

						DB::table('products')->where('id',$request->form_product_id[$i])->update(['available_qty'=>$available]);





						$id=DB::table('order_details')->insert($odData);



					}



				}





				return response()->json(array('order_id'=>Crypt::encryptString($data['order_id']),'msg'=>'Billing Successful!!'));



		





		



	}





	public function lazyLoad(Request $request)
	{

		$page = $request->page;
		$page = $page-1;

		$product_keyword = $request->product_keyword;


		if($product_keyword=='')
		{
			$all_products = Product::Where('swadesh_hut_id',Session::get('pos_swadesh_hut'))->skip($page*18)->take(18)->get();
		}
    	else 
		{
			$all_products = Product::Where('swadesh_hut_id',Session::get('pos_swadesh_hut'))->where('prod_name', 'like', '%' . $product_keyword . '%')->skip($page*18)->take(18)->get();
		}

    	

	

	

		//print_r($all_products);exit;

		$product_html='';

		$end_html='';

		if(!empty($all_products))

		{

			foreach($all_products as $products)

			{

				if( $products->discount && $products->discount >0 )

				{

					$pro_price=((100-$products->discount)*$products->price/100); 

				}

				else

				{

					$pro_price=$products->price;

				}

				$no_dtls = explode('.',$products->weight_per_pkt);
				$after_decimal_number = $no_dtls[1];
				if($after_decimal_number=='00')
				{
					$show_weight = substr($products->weight_per_pkt,0,strpos($products->weight_per_pkt,'.'));
				}
				else 
				{
					$show_weight = $products->weight_per_pkt;
				}

				$product_html.='<div class="w-140px w-xl-180px w-xxl-210px mx-2" onclick="setSessionIdArray(\''.$products->id.'\')">

					<div class="card bg-white c-pointer product-card hov-container">

						<div class="position-relative">

							<span class="absolute-top-left mt-1 ml-1 mr-0">

								<span class="badge badge-inline badge-success fs-13">In stock

								: '.$products->available_qty.'</span>

							</span>

							<img src="'.asset('/storage/'.$products->product_image).'" class="card-img-top img-fit h-120px h-xl-180px h-xxl-210px mw-100 mx-auto">

						</div>

						<div class="card-body p-2 p-xl-3">

							<div class="text-truncate fs-14 mb-2">'.$products->prod_name.'</div>

							<div class="prd_weight">'.$show_weight.' '.$products->weight_unit.'</div>

							<div class="price">';

							if(number_format($products->max_price,2)!=number_format($pro_price,2)) {

								$product_html.='<del class="mr-2 ml-0">₹'.number_format($products->max_price,2).'</del>';

							}

							$product_html.='<span>₹'.number_format($pro_price,2).'</span>

							</div>

						</div>

						<div class="add-plus absolute-full rounded overflow-hidden hov-box " data-stock-id="223">

							<div class="absolute-full bg-dark opacity-50">

							</div>

							<i class="las la-plus absolute-center la-6x text-white"></i>

						</div>

					</div>

				</div><br clear="all"><br clear="all">';

			}

		}

		

		if(count($all_products)==0)

		{

			$end_html.='<div class="fs-14 d-inline-block fw-600 btn btn-soft-primary c-pointer">Nothing more found.</div>';

		}

		else 

		{

			$end_html.='';

		}



		return response()->json(array('product_html'=>$product_html,'end_html'=>$end_html));

	}




	public function lazyLoad2(Request $request)
	{

		$page = $request->page;
		$page = $page-1;

		$product_keyword = $request->product_keyword;


		if($product_keyword=='')
		{
			$all_products = Product::Where('swadesh_hut_id',Session::get('pos_swadesh_hut'))->skip($page*15)->take(15)->get();
		}
    	else 
		{
			$all_products = Product::Where('swadesh_hut_id',Session::get('pos_swadesh_hut'))->where('prod_name', 'like', '%' . $product_keyword . '%')->skip($page*15)->take(15)->get();
		}

    	

	

	

		//print_r($all_products);exit;

		$product_html='';

		$end_html='';

		if(!empty($all_products))

		{

			foreach($all_products as $products)

			{

				if( $products->discount && $products->discount >0 )

				{

					$pro_price=((100-$products->discount)*$products->price/100); 

				}

				else

				{

					$pro_price=$products->price;

				}

				$no_dtls = explode('.',$products->weight_per_pkt);
				$after_decimal_number = $no_dtls[1];
				if($after_decimal_number=='00')
				{
					$show_weight = substr($products->weight_per_pkt,0,strpos($products->weight_per_pkt,'.'));
				}
				else 
				{
					$show_weight = $products->weight_per_pkt;
				}

				$product_html.='<div class="w-140px w-xl-180px w-xxl-210px mx-2" onclick="setSessionIdArray(\''.$products->id.'\')">

					<div class="card bg-white c-pointer product-card hov-container">

						<div class="position-relative">

							<span class="absolute-top-left mt-1 ml-1 mr-0">

								<span class="badge badge-inline badge-success fs-13">In stock

								: '.$products->available_qty.'</span>

							</span>

							<img src="'.asset('/storage/'.$products->product_image).'" class="card-img-top img-fit h-120px h-xl-180px h-xxl-210px mw-100 mx-auto">

						</div>

						<div class="card-body p-2 p-xl-3">

							<div class="text-truncate fs-14 mb-2">'.$products->prod_name.'</div>

							<div class="prd_weight">'.$show_weight.' '.$products->weight_unit.'</div>

							<div class="price">';

							if(number_format($products->max_price,2)!=number_format($pro_price,2)) {

								$product_html.='<del class="mr-2 ml-0">₹'.number_format($products->max_price,2).'</del>';

							}

							$product_html.='<span>₹'.number_format($pro_price,2).'</span>

							</div>

						</div>

						<div class="add-plus absolute-full rounded overflow-hidden hov-box " data-stock-id="223">

							<div class="absolute-full bg-dark opacity-50">

							</div>

							<i class="las la-plus absolute-center la-6x text-white"></i>

						</div>

					</div>

				</div><br clear="all"><br clear="all">';

			}

		}

		

		if(count($all_products)==0)

		{

			$end_html.='<div class="fs-14 d-inline-block fw-600 btn btn-soft-primary c-pointer">Nothing more found.</div>';

		}

		else 

		{

			$end_html.='';

		}



		return response()->json(array('product_html'=>$product_html,'end_html'=>$end_html));

	}


	public function filterProductsByBarcode(Request $request)
	{
		$input = $request->all();
		$product_keyword = $input['product_keyword'];

		$get_category_by_search_value = Category::where('name',$product_keyword)->first();

		if($product_keyword!='')
		{
			if(!empty($get_category_by_search_value)){
				$get_products_by_keyword = DB::table('products')->where('category_id', $get_category_by_search_value->id)->Where('swadesh_hut_id',Session::get('pos_swadesh_hut'))->orderBy('created_at', 'DESC')->get();
			}else{
				$get_products_by_keyword = DB::table('products')->where('prod_name', 'like', '%'.$product_keyword.'%')->Where('swadesh_hut_id',Session::get('pos_swadesh_hut'))->orderBy('created_at', 'DESC')->get();
			}
			if(count($get_products_by_keyword)>500){
				$total_page = (count($get_products_by_keyword)/50);
        		$last_page = count($get_products_by_keyword)%50;
				if(!empty($get_category_by_search_value)){
					$get_products_by_keyword = DB::table('products')->where('category_id', $get_category_by_search_value->id)->Where('swadesh_hut_id',Session::get('pos_swadesh_hut'))->orderBy('created_at', 'DESC')->paginate(50);
				}else{
					$get_products_by_keyword = DB::table('products')->where('prod_name', 'like', '%'.$product_keyword.'%')->Where('swadesh_hut_id',Session::get('pos_swadesh_hut'))->orderBy('created_at', 'DESC')->paginate(50);
				}
			}
			else{
				$total_page = (count($get_products_by_keyword)/25);
        		$last_page = count($get_products_by_keyword)%25;
				if(!empty($get_category_by_search_value)){
					$get_products_by_keyword = DB::table('products')->where('category_id', $get_category_by_search_value->id)->Where('swadesh_hut_id',Session::get('pos_swadesh_hut'))->orderBy('created_at', 'DESC')->paginate(25);
				}else{
					$get_products_by_keyword = DB::table('products')->where('prod_name', 'like', '%'.$product_keyword.'%')->Where('swadesh_hut_id',Session::get('pos_swadesh_hut'))->orderBy('created_at', 'DESC')->paginate(25);
				}
			}
		}
		else 
		{
			$get_products_by_keyword = DB::table('products')->get();
		}
		//print_r($get_products_by_keyword);exit;

		$product_html='';
		$pagination_html = '';

		if(!empty($get_products_by_keyword))
		{
			foreach($get_products_by_keyword as $products)
			{
				if( $products->discount && $products->discount >0 )
				{
					$pro_price=((100-$products->discount)*$products->price/100); 
				}
				else
				{
					$pro_price=$products->price;
				}

				$no_dtls = explode('.',$products->weight_per_pkt);
				$after_decimal_number = $no_dtls[1];
				if($after_decimal_number=='00')
				{
					$show_weight = substr($products->weight_per_pkt,0,strpos($products->weight_per_pkt,'.'));
				}
				else 
				{
					$show_weight = $products->weight_per_pkt;
				}
				
				$product_html.='<div class="w-140px w-xl-180px w-xxl-210px mx-2" onclick="setSessionIdArray(\''.$products->id.'\')">
					<div class="card bg-white c-pointer product-card hov-container">
						<div class="position-relative">
							<span class="absolute-top-left mt-1 ml-1 mr-0">
								<span class="badge badge-inline badge-success fs-13">In stock
								: '.$products->available_qty.'</span>
							</span>
							<img src="'.asset('/storage/'.$products->product_image).'" class="card-img-top img-fit h-120px h-xl-180px h-xxl-210px mw-100 mx-auto">
						</div>
						<div class="card-body p-2 p-xl-3">
							<div class="text-truncate fs-14 mb-2">'.$products->prod_name.'</div>
							<div class="prd_weight">'.$show_weight.' '.$products->weight_unit.'</div>
							<div class="price">';
								if(number_format($products->max_price,2)!=number_format($pro_price,2)) {
									$product_html.='<del class="mr-2 ml-0">₹'.number_format($products->max_price,2).'</del>';
								}
								$product_html.='<span>₹'.number_format($pro_price,2).'</span>
							</div>
						</div>
						<div class="add-plus absolute-full rounded overflow-hidden hov-box " data-stock-id="223">
							<div class="absolute-full bg-dark opacity-50">
							</div>
							<i class="las la-plus absolute-center la-6x text-white"></i>
						</div>
					</div>
				</div>';
			}
		}
		
		$pagination_html.='<ul class="pagination" role="navigation">';
        if($total_page>=1)
        {
            for($i=1;$i<=$total_page;$i++)
            {
                if($i<=20)
                {
                    $pagination_html.='<li class="page-item"><a class="page-link" href="#" onclick="showpossearchdata(\''.$i.'\')">'.$i.'</a></li>';
                }
                
            }
        }
        $pagination_html.='</ul>';
		return response()->json(array('product_html'=>$product_html,'pagination_html'=>$pagination_html));

	}


	public function filterProductsByPageno(Request $request){
		$product_keyword = $request->product_keyword;
        $page = $request->page;

		$get_category_by_search_value = Category::where('name',$product_keyword)->first();

		if(!empty($get_category_by_search_value)){
			$all_products = DB::table('products')->where('category_id', $get_category_by_search_value->id)->Where('swadesh_hut_id',Session::get('pos_swadesh_hut'))->orderBy('created_at', 'DESC')->skip(($page-1)*25)->take(25)->get();
		}else{
			$all_products = DB::table('products')->where('prod_name', 'like', '%'.$product_keyword.'%')->Where('swadesh_hut_id',Session::get('pos_swadesh_hut'))->orderBy('created_at', 'DESC')->skip(($page-1)*25)->take(25)->get();
		}

		//$all_products = Product::where('prod_name', 'LIKE', '%'. $product_keyword. '%')->Where('swadesh_hut_id',Session::get('pos_swadesh_hut'))->orderBy('created_at', 'DESC')->skip(($page-1)*25)->take(25)->get();

		$product_html='';
		$pagination_html = '';

		if(!empty($all_products))
		{
			foreach($all_products as $products)
			{
				if( $products->discount && $products->discount >0 )
				{
					$pro_price=((100-$products->discount)*$products->price/100); 
				}
				else
				{
					$pro_price=$products->price;
				}

				$no_dtls = explode('.',$products->weight_per_pkt);
				$after_decimal_number = $no_dtls[1];
				if($after_decimal_number=='00')
				{
					$show_weight = substr($products->weight_per_pkt,0,strpos($products->weight_per_pkt,'.'));
				}
				else 
				{
					$show_weight = $products->weight_per_pkt;
				}
				
				$product_html.='<div class="w-140px w-xl-180px w-xxl-210px mx-2" onclick="setSessionIdArray(\''.$products->id.'\')">
					<div class="card bg-white c-pointer product-card hov-container">
						<div class="position-relative">
							<span class="absolute-top-left mt-1 ml-1 mr-0">
								<span class="badge badge-inline badge-success fs-13">In stock
								: '.$products->available_qty.'</span>
							</span>
							<img src="'.asset('/storage/'.$products->product_image).'" class="card-img-top img-fit h-120px h-xl-180px h-xxl-210px mw-100 mx-auto">
						</div>
						<div class="card-body p-2 p-xl-3">
							<div class="text-truncate fs-14 mb-2">'.$products->prod_name.'</div>
							<div class="prd_weight">'.$show_weight.' '.$products->weight_unit.'</div>
							<div class="price">';
								if(number_format($products->max_price,2)!=number_format($pro_price,2)) {
									$product_html.='<del class="mr-2 ml-0">₹'.number_format($products->max_price,2).'</del>';
								}
								$product_html.='<span>₹'.number_format($pro_price,2).'</span>
							</div>
						</div>
						<div class="add-plus absolute-full rounded overflow-hidden hov-box " data-stock-id="223">
							<div class="absolute-full bg-dark opacity-50">
							</div>
							<i class="las la-plus absolute-center la-6x text-white"></i>
						</div>
					</div>
				</div>';
			}
		}
		return response()->json(array('product_html'=>$product_html));
	}



	public function showAllProduct(Request $request)
	{
		$product_html='';
		$pagination_html = '';
		$total_products = Product::Where('swadesh_hut_id',Session::get('pos_swadesh_hut'))->get();

		if(count($total_products)>1000){
			$total_page = (count($total_products)/50)+1;
			$all_products = Product::Where('swadesh_hut_id',Session::get('pos_swadesh_hut'))->orderBy('created_at', 'DESC')->paginate(50);
		}
		else{
			$total_page = (count($total_products)/25)+1;
			$all_products = Product::Where('swadesh_hut_id',Session::get('pos_swadesh_hut'))->orderBy('created_at', 'DESC')->paginate(25);
		}

		if(!empty($all_products))
		{
			foreach($all_products as $products)
			{
				if( $products->discount && $products->discount >0 )
				{
					$pro_price=((100-$products->discount)*$products->price/100); 
				}
				else
				{
					$pro_price=$products->price;
				}

				$no_dtls = explode('.',$products->weight_per_pkt);
				$after_decimal_number = $no_dtls[1];
				if($after_decimal_number=='00')
				{
					$show_weight = substr($products->weight_per_pkt,0,strpos($products->weight_per_pkt,'.'));
				}
				else 
				{
					$show_weight = $products->weight_per_pkt;
				}
				
				$product_html.='<div class="w-140px w-xl-180px w-xxl-210px mx-2" onclick="setSessionIdArray(\''.$products->id.'\')">
					<div class="card bg-white c-pointer product-card hov-container">
						<div class="position-relative">
							<span class="absolute-top-left mt-1 ml-1 mr-0">
								<span class="badge badge-inline badge-success fs-13">In stock
								: '.$products->available_qty.'</span>
							</span>
							<img src="'.asset('/storage/'.$products->product_image).'" class="card-img-top img-fit h-120px h-xl-180px h-xxl-210px mw-100 mx-auto">
						</div>
						<div class="card-body p-2 p-xl-3">
							<div class="text-truncate fs-14 mb-2">'.$products->prod_name.'</div>
							<div class="prd_weight">'.$show_weight.' '.$products->weight_unit.'</div>
							<div class="price">';
								if(number_format($products->max_price,2)!=number_format($pro_price,2)) {
									$product_html.='<del class="mr-2 ml-0">₹'.number_format($products->max_price,2).'</del>';
								}
								$product_html.='<span>₹'.number_format($pro_price,2).'</span>
							</div>
						</div>
						<div class="add-plus absolute-full rounded overflow-hidden hov-box " data-stock-id="223">
							<div class="absolute-full bg-dark opacity-50">
							</div>
							<i class="las la-plus absolute-center la-6x text-white"></i>
						</div>
					</div>
				</div>';
			}
		}
		

		$pagination_html='<ul class="pagination" role="navigation">';
        if($total_page>=1)
        {
            for($i=1;$i<=$total_page;$i++)
            {
                if($i<=20)
                {
                    $pagination_html.='<li class="page-item"><a class="page-link" href="#" onclick="showpossearchdata(\''.$i.'\')">'.$i.'</a></li>';
                }
                
            }
        }
        $pagination_html.='</ul>';

		return response()->json(array('product_html'=>$product_html,'pagination_html'=>$pagination_html));

	}

	
}



