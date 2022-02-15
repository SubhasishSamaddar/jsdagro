<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use DB;

use App\Category;

use Session;

use Cookie;

use Cart;

use Auth;

/*

use PHPMailer\PHPMailer\PHPMailer;

use PHPMailer\PHPMailer\Exception;

*/

class ProductsController extends Controller

{

	

	

	public function stockAlert(Request $request){

		//DB::enableQueryLog();

		$limitedStockProducts='';

		$stockAlert = DB::table('products as A')

						->select('A.prod_name','A.available_qty','A.ordered_qty','A.stock_alert','A.price','A.weight_per_pkt','A.weight_unit','B.name as category_name','C.location_name as swadesh_hut')

						->whereRaw('A.available_qty<=A.stock_alert')

						->leftJoin('categories as B','A.category_id','=','B.id')

						->leftJoin('swadesh_huts as C','A.swadesh_hut_id','=','C.id')->get();          

				//dd(DB::getQueryLog());die;

		//print_r($stockAlert);

		if( count($stockAlert)>0 && !empty($stockAlert) ):

			$html='<table>

					<tr>

						<td>Product Name</td>

						<td>Category</td>

						<td>Stock Alert</td>

						<td>Available Stock</td>

						<td>Swadesh Hut</td>

					</tr>';

			foreach( $stockAlert as $stockAlerts ):

				

				if( ($stockAlerts->available_qty-$stockAlerts->ordered_qty)<$stockAlerts->stock_alert ):

					//$limitedStockProducts.=$stockAlerts->prod_name.',';

				

					$html.='<tr>

								<td>'.$stockAlerts->prod_name.'</td>

								<td>'.$stockAlerts->category_name.'</td>

								<td>'.$stockAlerts->stock_alert.'</td>

								<td>'.$stockAlerts->available_qty.'</td>

								<td>'.$stockAlerts->swadesh_hut.'</td>

							</tr>';

					endif;

				

			endforeach;

			$html.='</table>';

		endif;

		echo $html;

		//echo env("MAIL_DRIVER");

		/*

		require 'vendor/autoload.php';

		$mail = new PHPMailer();

                $mail->isSMTP();

                $mail->Host = 'Host Name';

                $mail->SMTPAuth = true;

                $mail->Username = 'Mail Server Username';

                $mail->Password = 'Mail Server password';

                $mail->SMTPSecure = 'tls';

                $mail->Port = 2525;



                $mail->setFrom('from email', 'Competensea');

                $mail->addAddress($input['email'], $input['first_name'].' '.$input['last_name']);



                $mail->isHTML(true);



                $mail->Subject = "PHPMailer SMTP test";



                $mailContent = "<h1>Send HTML Email using SMTP in PHP</h1>

                    <p>This is a test email I’m sending using SMTP mail server with PHPMailer.</p>";



                $mail->Body = $mailContent;

                $mail->send();



                if(!$mail->send()){

                    echo 'Message could not be sent.';

                    echo 'Mailer Error: ' . $mail->ErrorInfo;

                    $mail_message='  Mail could not sent successfully';

                }else{

                    echo 'Message has been sent';

                    $mail_message='  Mail sent successfully';

                }

		*/		

	}

	

	public function categoryWiseListing(Request $request,$id)

    {

		

		/*--------*/

		

		/*--------*/



        $categories = DB::table('categories as A')->select('A.id','A.name','A.status','A.category_image','A.position_order','A.description','A.parent_id','B.name as parent_name','A.created_at','A.updated_at')

                            ->leftJoin('categories as B','A.parent_id','=','B.id')

                            ->orderBy('A.position_order','ASC')->orderBy('parent_name','ASC')

							->where('A.status','Active')->get();



		$subcategories = DB::table('categories')->where('parent_id',$id)->get();





		if(Session::has('swadesh_hut_id')):

			$swadesh_hut_id=Session::get('swadesh_hut_id');

			$products = DB::table('products as A')

				->select('A.id','A.prod_name','A.price', 'A.discount', 'A.stock_alert', 'A.status','A.product_image', 'A.description','B.name as category_name','A.created_at','A.updated_at')

                ->leftJoin('categories as B','A.category_id','=','B.id')

                ->where('A.status','Active')->where('A.category_id',$id)->where('A.swadesh_hut_id',$swadesh_hut_id)

				->orderBy('A.prod_name','ASC')

				->offset(0)->limit(3)

				->get();

        else:

			$products = DB::table('products as A')

				->select('A.id','A.prod_name','A.price', 'A.discount', 'A.stock_alert', 'A.status','A.product_image', 'A.description','B.name as category_name','A.created_at','A.updated_at')

                ->leftJoin('categories as B','A.category_id','=','B.id')

                ->where('A.status','Active')->where('A.category_id',$id)

				->orderBy('A.prod_name','ASC')

				->offset(1)->limit(3)

				->get();

		endif;

		if(Session::has('swadesh_hut_id')):

			$swadesh_hut_id=Session::get('swadesh_hut_id');

			$siteBarProducts = DB::table('products as A')

				->select('A.id','A.prod_name','A.price', 'A.discount', 'A.stock_alert', 'A.status','A.product_image', 'A.description','B.name as category_name','A.created_at','A.updated_at')

                ->leftJoin('categories as B','A.category_id','=','B.id')

				->where('A.swadesh_hut_id',$swadesh_hut_id)

                ->orderBy('A.prod_name','ASC')->limit(2)->get();

		else:

			$siteBarProducts = DB::table('products as A')

				->select('A.id','A.prod_name','A.price', 'A.discount', 'A.stock_alert', 'A.status','A.product_image', 'A.description','B.name as category_name','A.created_at','A.updated_at')

                ->leftJoin('categories as B','A.category_id','=','B.id')

                ->orderBy('A.prod_name','ASC')->limit(2)->get();

		endif;

							

		return view('front.products.listing',compact('categories', 'siteBarProducts', 'products','subcategories'));

    }

	

	public function categoryWiseListingPagination(Request $request)

    {

		$html='';

		if( $request->page_no ):

			$offset=$request->page_no*3;

		else:

			$offset=0;

		endif;

		//echo ' Offset : '.$offset;

		$limit=3;

		//$limit=$offset+1;

		if(Session::has('swadesh_hut_id')):

			$swadesh_hut_id=Session::get('swadesh_hut_id');

			$productsCount = count(DB::table('products as A')

							->select('A.id','A.prod_name','A.price','A.status','A.product_image', 'A.description','B.name as category_name','A.created_at','A.updated_at')

                            ->leftJoin('categories as B','A.category_id','=','B.id')

                            ->where('A.swadesh_hut_id',$swadesh_hut_id)

							->where('A.status','Active')->where('A.category_id',$request->category_id)->orderBy('A.prod_name','ASC')->get());

		else:

			$productsCount = count(DB::table('products as A')

							->select('A.id','A.prod_name','A.price','A.status','A.product_image', 'A.description','B.name as category_name','A.created_at','A.updated_at')

                            ->leftJoin('categories as B','A.category_id','=','B.id')

                            ->where('A.status','Active')->where('A.category_id',$request->category_id)->orderBy('A.prod_name','ASC')->get());

		endif;

		if(Session::has('swadesh_hut_id')):

			$swadesh_hut_id=Session::get('swadesh_hut_id');

			$products = DB::table('products as A')

							->select('A.id','A.prod_name','A.price', 'A.discount', 'A.stock_alert','A.status','A.product_image', 'A.description','B.name as category_name','A.created_at','A.updated_at')

                            ->leftJoin('categories as B','A.category_id','=','B.id')

							->where('A.swadesh_hut_id',$swadesh_hut_id)

                            ->where('A.status','Active')->where('A.category_id',$request->category_id)->orderBy('A.prod_name','ASC')->offset($offset)->limit($limit)->get();

		else:

			$products = DB::table('products as A')

							->select('A.id','A.prod_name','A.price', 'A.discount', 'A.stock_alert','A.status','A.product_image', 'A.description','B.name as category_name','A.created_at','A.updated_at')

                            ->leftJoin('categories as B','A.category_id','=','B.id')

                            ->where('A.status','Active')->where('A.category_id',$request->category_id)->orderBy('A.prod_name','ASC')->offset($offset)->limit($limit)->get();

	

		endif;

		if( !empty($products) ):

			$html.='<ul class="thumbnails">';

			foreach($products as $data):

				$productUrl=route('products',$data->id);

				$productImage=asset('/storage/'.$data->product_image);	

				if( $data->discount && $data->discount >0 ):

					$newPrice=((100-$data->discount)*$data->price/100); 

					$priceHtml='<small><del> र'.$data->price.'</del></small> र'.number_format($newPrice,2);

				else:

					$priceHtml='र'.$data->price;

				endif;

				$html.='<li class="span4">

							<div class="thumbnail">

							<a href="'.$productUrl.'" class="overlay"></a>

							<a class="zoomTool" href="'.$productUrl.'" title="add to cart"><span class="icon-search"></span> QUICK VIEW</a>

							<a href="'.$productUrl.'"><img src="'.$productImage.'" alt="" height="200" width="200"></a>

								<div class="caption cntr">

								<p>'.$data->prod_name.$data->id.'</p>

								<p><strong>'.$priceHtml.'</strong></p>

								<h4><a class="shopBtn" href="'.$productUrl.'" title="add to cart"> Product Details </a></h4>

									

								<br class="clr">

								</div>

							</div>

						</li>';

			endforeach;

			$html.='</ul>';

			$limit=$offset+$limit;

			if($productsCount>$limit):

				$counter=$offset+1;

				$html.='<span id="spanscrollcount'.$counter.'"><button id="scrollcount" onclick="productpagination('.$counter.')">Load More</button></span>';

			endif;

			return json_encode( array('status' => '1', 'msg' => $html));

		else:

			return json_encode( array('status' => '0', 'msg' => $html));

		endif;

					

	}

	

	public function listing(Request $request){

		//Auth::logout();

		//print_r($request->all());die;

		$products = DB::table('products as A')

        ->select('A.id','A.prod_name','A.price','A.status','A.product_image', 'A.description','B.name as category_name','A.created_at','A.updated_at')

                            ->leftJoin('categories as B','A.category_id','=','B.id')

                            ->orderBy('A.prod_name','ASC')->get();

		//echo '<pre>';print_r($products);die;



        return view('front.products.listing',compact('products'));

	}

	

	

	

	public function details(Request $request, $name_url){


		$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->orderBy('position_order','ASC')->limit(12)->get();

		

		$categories = DB::table('categories as A')->select('A.id','A.name','A.status','A.category_image','A.position_order','A.description','A.parent_id','B.name as parent_name','A.created_at','A.updated_at')

					->leftJoin('categories as B','A.parent_id','=','B.id')

					->orderBy('A.position_order','ASC')->orderBy('parent_name','ASC')

					->where('A.status','Active')->get();

					

		if(Session::has('swadesh_hut_id')):

			$swadesh_hut_id=Session::get('swadesh_hut_id');

			$siteBarProducts = DB::table('products as A')

			->select('A.id','A.prod_name','A.price', 'A.discount', 'A.stock_alert', 'A.status','A.product_image', 'A.description', 'B.name as category_name','A.created_at','A.updated_at')

			->where('A.swadesh_hut_id', $swadesh_hut_id)

			->leftJoin('categories as B','A.category_id','=','B.id')

            ->orderBy('A.prod_name','ASC')->limit(2)->get();	

		else:

			$siteBarProducts = DB::table('products as A')

			->select('A.id','A.prod_name','A.price', 'A.discount', 'A.stock_alert', 'A.status','A.product_image', 'A.description','B.name as category_name','A.created_at','A.updated_at')

            ->leftJoin('categories as B','A.category_id','=','B.id')

            ->orderBy('A.prod_name','ASC')->limit(2)->get();

		endif;

		if(Session::has('swadesh_hut_id')):

			$swadesh_hut_id=Session::get('swadesh_hut_id');

			$get_product_details = DB::table('products')->where('name_url',$name_url)->first();

			$id = $get_product_details->id;

			
			$details = DB::table('products as A')

						->select('A.*')

						->leftJoin('categories as B','A.category_id','=','B.id')

						->where('A.swadesh_hut_id', $swadesh_hut_id)

						->where( 'A.id', $id )->first();

		else:
			$get_product_details = DB::table('products')->where('name_url',$name_url)->first();

			$id = $get_product_details->id;

			$details = DB::table('products as A')

						->select('A.*')

						->leftJoin('categories as B','A.category_id','=','B.id')

						->where( 'A.id', $id )->first();

		endif;

		 

		  //echo Session::get('swadesh_hut_id');exit;
		  //print_r($details);exit;

			$link_similar_products = DB::table('products')->select('id','prod_name','weight_per_pkt','weight_unit', 'name_url', 'swadesh_hut_id')

								->where('similar_product',$details->similar_product)

								//->where('swadesh_hut_id', Session::get('swadesh_hut_id'))

								//->whereNotNull('similar_product')

								->orderBy('weight_per_pkt','ASC')->get();

		 

		

		 

		$product_categroy = $details->category_id;

		$get_similar_products = DB::table('products as A')

		->select('A.id','A.prod_name','A.price', 'A.discount', 'A.available_qty', 'A.ordered_qty', 'A.stock_alert', 'A.status','A.product_image', 'A.description', 'A.category_id', 'A.specification', 'B.name as category_name','A.created_at','A.updated_at')

		->where('A.category_id',$product_categroy)

		->where('A.swadesh_hut_id', Session::get('swadesh_hut_id'))

		->where('A.id', '<>' , $id)

		->leftJoin('categories as B','A.category_id','=','B.id')

		->limit(5)

		->get();


		$get_product_category_details = Category::Where('id',$details->category_id)->first();
		$get_product_parent_category_details = Category::Where('id',$get_product_category_details->parent_id)->first();


		$bread_crumb_string = '<div class="breadcrumb" style="margin-bottom:15px;"><a href="'.url('/').'/home">Home</a>  >  ';
		$get_parent_details = $this->categoryParent( $get_product_category_details->parent_id);
		$count1=0;
		$parent_id_array = array();
		foreach($get_parent_details as $key=>$val){
			$parent_id_array[$count1] = $key;
			$count1++;
			if(count($val)>0){
				foreach($val as $key=>$ids){
					$parent_id_array[$count1] = $key;
					$count1++;
				}
			}
		}
		$parent_id_array = array_reverse($parent_id_array);
		foreach($parent_id_array as $pid)
		{
			$get_details = DB::table('categories')->where('id', $pid)->first();
			$bread_crumb_string.='<a href="'.url('/').'/category/'.$get_details->name_url.'">'.$get_details->name.'</a>  >  ';
		}
		$bread_crumb_string.='<a href="'.url('/').'/category/'.$get_product_category_details->name_url.'">'.$get_product_category_details->name.'</a>  >  '.$details->prod_name.'</div>';


        return view('front.products.details',compact('categories', 'parentCategory', 'siteBarProducts', 'details','get_similar_products','link_similar_products','bread_crumb_string'));

	}

	

	public function productAddToCart(Request $request){  


		$buttonEnable='0';

		if( isset($request->product_id) && !empty($request->product_id) ):

			$productDetails = DB::table('products as A')->where( 'id', $request->product_id )->first();


			$productFind=0;

			if( $this->productCount()!=0 ):

				foreach( Cart::getContent() as $cartKey=>$cartData ):

					if( $cartData->name==$request->product_id.'%'.$productDetails->prod_name ):


						if(isset(Auth::user()->user_type) && Auth::user()->user_type=='Wholeseller')
						{
							$price=((100-$productDetails->discount)*$productDetails->wholesale_price/100); 
						}
						else 
						{
							$price=$productDetails->price;
						}

						if(isset($request->quantity) && $request->quantity!='')
						{
							$cart_add_quantity = $request->quantity;
						}
						else 
						{
							if(isset(Auth::user()->user_type) && Auth::user()->user_type=='Wholeseller')
							{
								$cart_add_quantity = $productDetails->wholesale_min_qty;
							}
							else 
							{
								$cart_add_quantity = 1;
							}
							
						}

						

							

						$options = array( 'product_image' => $productDetails->product_image, 'product_availability' => ($productDetails->available_qty-$productDetails->ordered_qty), 'weight_per_pkt' => intval($productDetails->weight_per_pkt), 'weight_unit' => $productDetails->weight_unit );

						

						Cart::add($cartKey, $request->product_id.'%'.$productDetails->prod_name, $price, $cart_add_quantity, $options);

						$returnMsg='Product Quantity Updated Successfully!';

						$availableStock=($productDetails->available_qty-$request->quantity);

						if( ($availableStock)< 1 ):

							$buttonEnable='1';

						endif;

				

						$productFind=1;  

					endif;

				endforeach;

			endif;

			if( $productFind=='0' ):

				$options = array( 'product_image' => $productDetails->product_image,'product_availability' => ($productDetails->available_qty-$productDetails->ordered_qty), 'weight_per_pkt' => intval($productDetails->weight_per_pkt), 'weight_unit' => $productDetails->weight_unit );

				$availableStock=($productDetails->available_qty-$request->quantity);

				if( ($availableStock)<'1' ):

					$buttonEnable='1';

				endif;

				if(isset(Auth::user()->user_type) && Auth::user()->user_type=='Wholeseller')
				{
					$price=((100-$productDetails->discount)*$productDetails->wholesale_price/100)/$productDetails->wholesale_min_qty; 
				}
				else 
				{
					$price=$productDetails->price;
				}

				if(isset($request->quantity) && $request->quantity!='')
				{
					$cart_add_quantity = $request->quantity;
				}
				else 
				{
					if(isset(Auth::user()->user_type) && Auth::user()->user_type=='Wholeseller')
					{
						$cart_add_quantity = $productDetails->wholesale_min_qty;
					}
					else 
					{
						$cart_add_quantity = 1;
					}
					
				}
				/*echo $price;
				echo $cart_add_quantity;exit;*/

				Cart::add(uniqid(), $request->product_id.'%'.$productDetails->prod_name, $price, $cart_add_quantity, $options);

				$returnMsg='Product Added Successfully!';

			endif;


			return json_encode( array('status' => $this->productCount(), 'msg' => $returnMsg, 'buttonEnable' => $buttonEnable ));

		else:

			return json_encode( array('status' => $this->productCount(), 'msg' => 'No Product Found!', 'buttonEnable' => $buttonEnable ));


		endif;	

	}

	

	public function cart(Request $request){

		//echo 'abcd';die;   

		//	PARENT CATEGORY

		$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->orderBy('position_order','ASC')->limit(12)->get();

		

		//	

		$swadeshHutDetails = DB::table('swadesh_huts')->where( 'status', 'Active' )->get();

						

		$productDetails=Cart::getContent();  

		

		return view('front.products.cart',compact('productDetails', 'swadeshHutDetails', 'parentCategory'));

	}

	

	public function productRemoveFromCart(Request $request){

		if( isset($request->item_session) && !empty($request->item_session) ):

			//echo $request->item_session;die;

			Cart::remove($request->item_session);

			return json_encode( array('status' => $this->productCount(), 'msg' => 'Product Remove From Cart!' ));

		else:

			return json_encode( array('status' => $this->productCount(), 'msg' => 'No Product Found!'));

		endif;

	}

	

	public function productUpdateCart(Request $request){

		if( isset($request->item_session) && !empty($request->item_session) && isset($request->product_quantity) && $request->product_quantity!=0 ):

			//echo $request->item_session;

			//echo 'Quantity : '.$request->product_quantity;

			//echo 'Price : '.$request->product_per_price;die;

			//$data['quantity']=array('relative' => true, 'value' => $request->product_quantity);

			$data=array('quantity' => array('relative' => false,'value' => $request->product_quantity));	

			Cart::update($request->item_session,$data);

			

			return json_encode( array('status' => $this->productCount(), 'msg' => 'Product Updated Successfully!' ));

		else:

			return json_encode( array('status' => $this->productCount(), 'msg' => 'Product Quantity Must Be Greater Than 0'));

		endif;

	}

	

	public function productCount(){

		$productCount=0;

		foreach( Cart::getContent() as $pdKey=>$pdValue ):

			$productCount=$productCount+$pdValue->quantity;

		endforeach;

		return $productCount;

	}

	

	public function getSwadeshHut(Request $request){

		if( isset($request->pincode) && !empty($request->pincode) ):

			$data = DB::table('swadesh_huts')

					->select('id','location_name')   

					->where('cover_area_pincodes', 'like', '%'.$request->pincode.'%')

					->first();

			/*

			if( !empty($data) && count($data)>0 ):

				foreach( $data as $val ):

				

				endforeach;

			endif;

			*/

			if( !empty($data) ):

				Session::forget('swadesh_hut_id');  

				Session::forget('swadesh_hut_name');

				$request->session()->put('swadesh_hut_id', $data->id);

				$request->session()->put('swadesh_pin_code', $request->pincode);

				$request->session()->put('swadesh_hut_name', $data->location_name);

				Cookie::queue('swadesh_hut_id', $data->id, '720');

				Cookie::queue('swadesh_pin_code', $request->pincode, '720');

				Cookie::queue('swadesh_hut_name', $data->location_name, '720');

				return json_encode( array('status' => '1', 'msg' => 'Store Name : '.$data->location_name ));

			else:  

				return json_encode( array('status' => '0', 'msg' => 'No Store Found Try Different Pincode' ));

			endif;

		else:

			return json_encode( array('status' => '0', 'msg' => 'Pincode Required!'));

		endif;  

	}

	public function productSearchListing(Request $request){

		//print_r($request->all());die;

		//echo '====>'.Cookie::get('swadesh_hut_id').'====>';die;  

		if( isset($request->q) && !empty($request->q) ):  

			if(Cookie::has('swadesh_hut_id')):

				$swadesh_hut_id=Cookie::get('swadesh_hut_id');

				$products = DB::table('products')  

					->where('prod_name', 'like', '%'.$request->q.'%')

					->orWhere('tags', 'LIKE', '%'. $request->q. '%')

					->where('swadesh_hut_id', $swadesh_hut_id)

					->paginate(12);

			else:

				$products = DB::table('products')  

					->where('prod_name', 'like', '%'.$request->q.'%')

					->orWhere('tags', 'LIKE', '%'. $request->q. '%')

					->paginate(12);

			endif;

			/*$categories = DB::table('categories as A')->select('A.id','A.name','A.status','A.category_image','A.position_order','A.description','A.parent_id','B.name as parent_name','A.created_at','A.updated_at')

							->leftJoin('categories as B','A.parent_id','=','B.id')

							->orderBy('A.position_order','ASC')->orderBy('parent_name','ASC')

							->where('A.status','Active')->get();*/

							$categories = [];

			if(Cookie::has('swadesh_hut_id')):

				$swadesh_hut_id=Cookie::get('swadesh_hut_id');

				$siteBarProducts = DB::table('products as A')

					->select('A.id','A.prod_name','A.price', 'A.discount', 'A.stock_alert', 'A.status','A.product_image', 'A.description','B.name as category_name','A.created_at','A.updated_at')

					->leftJoin('categories as B','A.category_id','=','B.id')

					->where('A.swadesh_hut_id',$swadesh_hut_id)

					->orderBy('A.prod_name','ASC')->limit(2)->get();

			else:

				$siteBarProducts = DB::table('products as A')

					->select('A.id','A.prod_name','A.price', 'A.discount', 'A.stock_alert', 'A.status','A.product_image', 'A.description','B.name as category_name','A.created_at','A.updated_at')

					->leftJoin('categories as B','A.category_id','=','B.id')

					->orderBy('A.prod_name','ASC')->limit(2)->get();

			endif;

			//	PARENT CATEGORY

			$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->orderBy('position_order','ASC')->limit(12)->get();

			

			$categorydetails="Search Result For : ".$request->q;

			$bread_crumb_string = '<div class="breadcrumb" style="margin-bottom:15px;"><a href="#">'.$request->q.'</a></div>';

			return view('front.products.searchlisting',compact('categories', 'siteBarProducts', 'products', 'parentCategory', 'categorydetails','bread_crumb_string'));  

		endif;

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


	function categoryParent($id) {
		$parent_details = DB::table('categories')->where('id', $id)->get();  
		$parent = array();
		
		if(count($parent_details)>0) {
			# It has children, let's get them.
			$i=0;
			foreach($parent_details as $details) {
				# Add the child to the list of children, and get its subchildren
				$parent[$details->id] = $this->categoryParent($details->parent_id);
				$i++;
			}
		}
		return $parent;
	}

	

	public function productCategoryListing($name_url){
		//echo Session::get('swadesh_hut_id');exit;
		$cat_by_name_url = DB::table('categories')->where('name_url', $name_url)->first();
		$cat_id = $cat_by_name_url->id;
		$getcategorydetails = DB::table('categories')->where('id', $cat_id)->first();
		if($getcategorydetails->parent_id==0)
		{
			$categories = $categories = DB::table('categories')->where('parent_id', $cat_id)->get();  
			$sub_category_id_array = array();
			$child_array = $this->categoryChild($cat_id);
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
			//print_r($sub_category_id_array);exit;
			/*$categories = $categories = DB::table('categories')->where('parent_id', $cat_id)->get();  
			$sub_category_id_array = array();
			if(!empty($categories))
			{
				$i=0;
				foreach($categories as $ctgry)
				{
					$sub_category_id_array[$i] = $ctgry->id;
					$i++;
				}
			}
			$products = DB::table('products')  

				->where('category_id', $cat_id)

				->where('swadesh_hut_id', $swadesh_hut_id)

				->where(function ($query){

					$query->whereRaw('similar_product = sku')

					->orWhereNull('similar_product');

				})

				->get();
			*/




			
			if(Session::has('swadesh_hut_id')):

				$swadesh_hut_id=Session::get('swadesh_hut_id');
				
				$products = DB::table('products')  
				->whereIn('category_id', $sub_category_id_array)
				->where('swadesh_hut_id', $swadesh_hut_id)
				->groupBy('prod_name')
				/*->where(function ($query){
					$query->whereRaw('similar_product = sku')
					->orWhereNull('similar_product');
				})*/
				->orderBy('prod_name','ASC')
				->paginate(20);
			else:
				$products = DB::table('products')  
				->whereIn('category_id', $sub_category_id_array)
				->groupBy('prod_name')
				/*->where(function ($query){
					$query->whereRaw('similar_product = sku')
					->orWhereNull('similar_product');
				})*/
				->orderBy('prod_name','ASC')
				->paginate(20);
			endif;

			$bread_crumb_string = '<div class="breadcrumb" style="margin-bottom:15px;"><a href="'.url('/').'/home">Home</a>  >  <a href="'.url('/').'/category/'.$getcategorydetails->name_url.'">'.$getcategorydetails->name.'</a></div>';
	
		}
		else 
		{
			$categories = DB::table('categories')->where('parent_id', $getcategorydetails->id)->get(); 
			$sub_category_id_array = array();
			if(count($categories)>0)
			{
				$i=0;
				foreach($categories as $ctgry)
				{
					$sub_category_id_array[$i] = $ctgry->id;
					$i++;
				}
				$sub_category_id_array[$i] = $getcategorydetails->id;
				if(Session::has('swadesh_hut_id')):
					$swadesh_hut_id=Session::get('swadesh_hut_id');
					$products = DB::table('products')  
					->whereIn('category_id', $sub_category_id_array)
					->where('swadesh_hut_id', $swadesh_hut_id)
					->groupBy('prod_name')
					->orderBy('prod_name','ASC')
					/*->where(function ($query){
						$query->whereRaw('similar_product = sku')
						->orWhereNull('similar_product');
					})*/
					->paginate(20);
				else:
					$products = DB::table('products')  
					->whereIn('category_id', $sub_category_id_array)
					->groupBy('prod_name')
					->orderBy('prod_name','ASC')
					/*->where(function ($query){
						$query->whereRaw('similar_product = sku')
						->orWhereNull('similar_product');
					})*/
					->paginate(20);
				endif;
			}
			else 
			{
				if(Session::has('swadesh_hut_id')):
					$swadesh_hut_id=Session::get('swadesh_hut_id');
					$products = DB::table('products')  
					->where('category_id', $cat_id)
					->where('swadesh_hut_id', $swadesh_hut_id)
					->groupBy('prod_name')
					->orderBy('prod_name','ASC')
					/*->where(function ($query){
						$query->whereRaw('similar_product = sku')
						->orWhereNull('similar_product');
					})*/
					->paginate(20);
				else:
					$products = DB::table('products')  
					->where('category_id', $cat_id)
					->groupBy('prod_name')
					->orderBy('prod_name','ASC')
					/*->where(function ($query){
						$query->whereRaw('similar_product = sku')
						->orWhereNull('similar_product');
					})*/
					->paginate(20);
				endif;
			}
			
			$get_parent_category_details = DB::table('categories')->where('id', $getcategorydetails->parent_id)->first();


			$bread_crumb_string = '<div class="breadcrumb" style="margin-bottom:15px;"><a href="'.url('/').'/home">Home</a>  >  ';
			$get_parent_details = $this->categoryParent( $getcategorydetails->parent_id);
			$count1=0;
			$parent_id_array = array();
			foreach($get_parent_details as $key=>$val){
				$parent_id_array[$count1] = $key;
				$count1++;
				if(count($val)>0){
					foreach($val as $key=>$ids){
						$parent_id_array[$count1] = $key;
						$count1++;
					}
				}
			}
			$parent_id_array = array_reverse($parent_id_array);
			foreach($parent_id_array as $pid)
			{
				$get_details = DB::table('categories')->where('id', $pid)->first();
				$bread_crumb_string.='<a href="'.url('/').'/category/'.$get_details->name_url.'">'.$get_details->name.'</a>  >  ';
			}
			$bread_crumb_string.='<a href="'.url('/').'/category/'.$getcategorydetails->name_url.'">'.$getcategorydetails->name.'</a></div>';
			

			/*$bread_crumb_string = '<div class="breadcrumb" style="margin-bottom:15px;"><a href="'.url('/').'/home">Home</a>  |  <a href="'.url('/').'/category/'.$get_parent_category_details->id.'">'.$get_parent_category_details->name.'</a>  |  <a href="'.url('/').'/category/'.$getcategorydetails->id.'">'.$getcategorydetails->name.'</a></div>';*/
		}


		 	

		$categorydetails = DB::table('categories') 

				->select('name','category_banner_images','parent_id')

				->where('id', $cat_id)

				->first();

		$all_banners = [];
		if($categorydetails->category_banner_images!=''){
			$all_banners = explode(",",$categorydetails->category_banner_images);
		}

		$categorydetails=$categorydetails->name; 


		//print_r($products);exit;


		$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->orderBy('position_order','ASC')->limit(12)->get();

		if($getcategorydetails->parent_id==0){
			return view('front.products.listing',compact('parentCategory', 'categorydetails', 'categories', 'products','bread_crumb_string','all_banners'));
		}
		else{
			$category_details_parent_id=$getcategorydetails->parent_id; 
			$get_parent = Category::where('id',$category_details_parent_id)->first();
			$get_categories_by_parent = Category::where('parent_id',$category_details_parent_id)->get();
			return view('front.products.listingsub',compact('parentCategory', 'categorydetails', 'categories', 'products','bread_crumb_string','all_banners','category_details_parent_id','get_categories_by_parent','get_parent'));
		}
		


	}



	// public function getchildcategory($categories){ 

	// 	$sub_Ids = [];

	// 	foreach($categories->children as $cat){

	// 		$sub_Ids[] = $cat->id;

	// 		$sub_Ids = array_merge($sub_Ids, $this->getchildcategory($cat))	;

	// 	}

	// 	return $sub_Ids;



	// }



	public function brand($brand_id){     

		if(Session::has('swadesh_hut_id')):

			$swadesh_hut_id=Session::get('swadesh_hut_id');

			$products = DB::table('products')  

				->where('brand_id', $brand_id)

				->where('swadesh_hut_id', $swadesh_hut_id)

				->where(function ($query){

					$query->whereRaw('similar_product = sku')

					->orWhereNull('similar_product');

				})

				->get();

		else:

			$products = DB::table('products')  

				->where('brand_id', $brand_id)

				->where(function ($query){

					$query->whereRaw('similar_product = sku')

					->orWhereNull('similar_product');

				})

				->get();

		endif;

		//	

		$branddetails = DB::table('brands')  

				->where('id', $brand_id)

				->first(); 

				

		//	PARENT CATEGORY

		$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->limit(12)->get();  

		

		return view('front.products.brand',compact('branddetails', 'products','parentCategory')); 

		//echo '<pre>';print_r($products);die;  

	}









	public function featuredProduct(){

		//	PARENT CATEGORY

		$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->limit(12)->get();

		//	SHOW IN HOME PAGE CATEGORY

		$showinHomePageCategory = Category::Where('show_home_page','Show')->limit(2)->get();

		//print_r($showinHomePageCategory);    



		$categories = DB::table('categories as A')->select('A.id','A.name','A.status','A.category_image','A.position_order','A.description','A.parent_id','B.name as parent_name','A.created_at','A.updated_at')

					->leftJoin('categories as B','A.parent_id','=','B.id')

					->orderBy('A.position_order','ASC')->orderBy('parent_name','ASC')

					->where('A.status','Active')->get();



		//Featured Product

		if(Session::has('swadesh_hut_id')):

			$swadesh_hut_id=Session::get('swadesh_hut_id');

			$featuredroducts = DB::table('products') 

				->where('is_featured', 'yes')

				->where('swadesh_hut_id', $swadesh_hut_id)

				->where(function ($query){

					$query->whereRaw('similar_product = sku')

					->orWhereNull('similar_product');

				})

				->orderBy('id','DESC')

				->get();

		else:  

			$featuredroducts = DB::table('products') 

			->where('is_featured', 'yes')

			->where(function ($query){

				$query->whereRaw('similar_product = sku')

				->orWhereNull('similar_product');

			})

			->orderBy('id','DESC')->get();

		endif;  





		return view('front.products.featured_products',compact('categories','parentCategory', 'showinHomePageCategory','featuredroducts'));

    }



	public function get_product_price_by_weight(Request $request)

	{

		$input = $request->all();



		$product_id = $input['product_id'];

		$weight_per_packet = $input['weight_per_packet'];

		$get_price = DB::table('product_weight_price')->where('product_id',$product_id)->where('weight_per_pkt',$weight_per_packet)->first();

		return response()->json(['price'=>$get_price->price,'unit'=>$get_price->weight_unit]);

	}



	public function get_product_price_by_id(Request $request)

	{

		$input = $request->all();

		$product_id = $input['product_id']; 

		$get_price = DB::table('products')->select('id','price','max_price','weight_per_pkt','weight_unit','discount')->where('id',$product_id)->first();

		return response()->json(['price'=>$get_price->price,'max_price'=>$get_price->max_price,'weight_per_pkt'=>$get_price->weight_per_pkt,'weight_unit'=>$get_price->weight_unit,'discount'=>$get_price->discount]);

	}


	public function getExactProductByBarcode(Request $request) 
	{
		$input = $request->all();
		$barcode_text = $input['barcode_text'];
		$product_html = '';
		$get_exact_products_by_barcode = DB::table('products')->where('barcode', $barcode_text)->first();
		if(empty($get_exact_products_by_barcode))
		{
			$product_id='';
			$product_count=0;
		}
		else 
		{
			$product_count=1;
			$product_id=$get_exact_products_by_barcode->id;
			if( $get_exact_products_by_barcode->discount && $get_exact_products_by_barcode->discount >0 )
			{
				$pro_price=((100-$get_exact_products_by_barcode->discount)*$get_exact_products_by_barcode->price/100); 
			}
			else
			{
				$pro_price=$get_exact_products_by_barcode->price;
			}
			$product_html.='<div class="w-140px w-xl-180px w-xxl-210px mx-2" onclick="setSessionIdArray(\''.$get_exact_products_by_barcode->id.'\')">
				<div class="card bg-white c-pointer product-card hov-container">
					<div class="position-relative">
						<span class="absolute-top-left mt-1 ml-1 mr-0">
							<span class="badge badge-inline badge-success fs-13">In stock
							: '.$get_exact_products_by_barcode->available_qty.'</span>
						</span>
						<img src="'.asset('/storage/'.$get_exact_products_by_barcode->product_image).'" class="card-img-top img-fit h-120px h-xl-180px h-xxl-210px mw-100 mx-auto">
					</div>
					<div class="card-body p-2 p-xl-3">
						<div class="text-truncate fs-14 mb-2">'.$get_exact_products_by_barcode->prod_name.'</div>
						<div class="prd_weight">'.intval($get_exact_products_by_barcode->weight_per_pkt).' '.$get_exact_products_by_barcode->weight_unit.'</div>
						<div class="price">
							<del class="mr-2 ml-0">₹'.number_format($get_exact_products_by_barcode->price,2).'</del><span>₹'.number_format($pro_price,2).'</span>
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
		return response()->json(array('product_html'=>$product_html,'product_id'=>$product_id,'product_count'=>$product_count));
	}


	public function filterProductsByBarcode(Request $request)
	{
		$input = $request->all();
		$product_keyword = $input['product_keyword'];

		if($product_keyword!='')
		{
			$get_products_by_keyword = DB::table('products')->where('prod_name', 'like', '%'.$product_keyword.'%')->get();
		}
		else 
		{
			$get_products_by_keyword = DB::table('products')->get();
		}
		//print_r($get_products_by_keyword);exit;

		$product_html='';

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
							<div class="prd_weight">'.intval($products->weight_per_pkt).' '.$products->weight_unit.'</div>
							<div class="price">
								<del class="mr-2 ml-0">₹'.number_format($products->max_price,2).'</del><span>₹'.number_format($products->price,2).'</span>
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

	public function setSessionIdArray(Request $request)
	{
		$input = $request->all();
		$product_id = $input['product_id'];
		$products_cart_ids = $input['products_cart_ids'];
		$products_cart_ids.=','.$product_id;
		return response()->json(array('products_cart_ids'=>$products_cart_ids));		
	}

	public function generateCartData(Request $request)
	{
		$input = $request->all();
		$products_cart_ids = $input['products_cart_ids'];
		$products_cart_ids = explode(',',$products_cart_ids);
		$product_id=$input['product_id'];
		
		
	
		
		$list_html='';
		$price_html=''; 
		$subtotal_price = 0;
		$total_price = 0;
		$tax_amount = 0;
		if(!empty($products_cart_ids))
		{
			foreach($products_cart_ids as $ids)
			{
				if($ids!=0)
				{
					$get_products = DB::table('products')->where('id', $ids)->first();
					if(isset($get_products->discount) && $get_products->discount >0 )
					{
						$pro_price=((100-$get_products->discount)*$get_products->price/100); 
					}
					else
					{
						$pro_price=$get_products->price;
					}

					$pro_tax = ($pro_price*$get_products->cgst/(100+$get_products->cgst+$get_products->sgst))+($pro_price*$get_products->sgst/(100+$get_products->cgst+$get_products->sgst));

					$subtotal_price+=$pro_price;
					//$total_price+=$pro_price+number_format($pro_tax,2);
					$total_price+=$pro_price;
					$tax_amount+=number_format($pro_tax,2);
				}
				
			}
		}


		$get_product = DB::table('products')->where('id', $product_id)->first();
		if(isset($get_product->discount) && $get_product->discount >0 )
		{
			$pro_price=((100-$get_product->discount)*$get_product->price/100); 
		}
		else
		{
			$pro_price=$get_product->price;
		}
		$pro_tax = ($pro_price*$get_product->cgst/(100+$get_product->cgst+$get_product->sgst))+($pro_price*$get_product->sgst/(100+$get_product->cgst+$get_product->sgst));
		$list_html.='<li class="list-group-item py-0 pl-2" id="pro_li'.$product_id.'">
			<div class="row gutters-5 align-items-center">
				<div class="col-auto w-60px">
					<div class="row no-gutters align-items-center flex-column aiz-plus-minus">
						<button class="btn col-auto btn-icon btn-sm fs-15" type="button" data-type="plus" onclick="updateQuantity(\''.$product_id.'\',\''.$pro_price.'\',\''.$pro_tax.'\')" data-field="qty-0">
							<i class="las la-plus"></i>
						</button>
						<input type="text" name="qty-0" id="qty-'.$product_id.'" class="col border-0 text-center flex-grow-1 fs-16 input-number" placeholder="1" value="1" min="1" max="10">
						<input type="hidden" name="form_product_id[]" id="form_product_id" value="'.$product_id.'">
						<input type="hidden" name="form_product_qty[]" id="form_product_qty'.$product_id.'" value="1">
						<input type="hidden" name="form_product_price[]" id="form_product_price'.$product_id.'" value="'.$pro_price.'">
						<input type="hidden" name="form_product_tax[]" id="form_product_tax'.$product_id.'" value="'.$pro_tax.'">
						
						
						
						<button class="btn col-auto btn-icon btn-sm fs-15" type="button" data-type="minus" data-field="qty-0" onclick="decreaseQuantity(\''.$product_id.'\',\''.$pro_price.'\',\''.$pro_tax.'\')" data-field="qty-0">
							<i class="las la-minus"></i>
						</button>
					</div>
				</div>
				<div class="col">
					<div class="text-truncate-2">'.$get_product->prod_name.'</div>
					<span class="span badge badge-inline fs-12 badge-soft-secondary" style="margin:0px;">'.intval($get_product->weight_per_pkt).' '.$get_product->weight_unit.'</span>
				</div>
				<div class="col-auto">
					<div class="fs-12 opacity-60">₹'.number_format($pro_price,2).' x <span id="current_single_qty'.$product_id.'">1</span></div>
					<div class="fs-15 fw-600">₹<span id="current_single_total'.$product_id.'">'.number_format($pro_price,2).'</span></div>
				</div>
				<div class="col-auto">
					<button type="button" class="btn btn-circle btn-icon btn-sm btn-soft-danger ml-2 mr-0 deb" onclick="removeFromCart(\''.$product_id.'\')">
						<i class="las la-trash-alt"></i>
					</button>
				</div>
			</div>
		</li>';




		$price_html.='<!--<div class="d-flex justify-content-between fw-600 mb-2 opacity-70">
			<span>Sub Total</span>
			<span>₹<span id="sub_total_cart_amount">'.$subtotal_price.'</span></span>
		</div>
		<input type="hidden" id="form_sub_total_cart_amount" name="form_sub_total_cart_amount" value="'.$subtotal_price.'">
		<div class="d-flex justify-content-between fw-600 mb-2 opacity-70">
			<span>Tax</span>
			<span>$<span id="total_tax_amount">'.$tax_amount.'</span></span>
		</div>
		<input type="hidden" id="form_total_tax_amount" name="form_total_tax_amount" value="'.$tax_amount.'">
		<div class="d-flex justify-content-between fw-600 mb-2 opacity-70">
			<span>Shipping</span>
			<span>$0.000</span>
		</div>
		<div class="d-flex justify-content-between fw-600 mb-2 opacity-70">
			<span>Discount</span>
			<span>$0.000</span>
		</div>-->
		<div class="d-flex justify-content-between fw-600 fs-18 border-top pt-2">
			<span>TOTAL</span>
			<span>₹<span id="total_cart_amount">'.number_format($total_price,2).'</span></span>
		</div>
		<input type="hidden" id="form_total_cart_amount" name="form_total_cart_amount" value="'.$total_price.'">';


		return response()->json(array('list_html'=>$list_html,'price_html'=>$price_html,'single_price'=>$pro_price,'total_price'=>$total_price));	

	}

	public function showRegisterdUser(Request $request)
	{
		$registered_users_html = '<strong style="color:green;padding-left:20px;"><span id="set_address_html"></span></strong><br>';
		$registered_users = DB::table('users')->where('user_type','Customer')->distinct()->get();
		$current_name = '';
		if(!empty($registered_users))
		{
			foreach($registered_users as $users)
			{
				if($current_name!=$users->name)
				{
					$registered_users_html.='<a href="javascript:void(0)" style="padding:20px;" onclick="setaddress(\''.$users->id.'\')"><strong>'.$users->name.'</strong></a><br>';
				}				
				$current_name = $users->name;
			}			
		}
		return response()->json(array('registered_users_html'=>$registered_users_html));
	}

	public function setUserAddress(Request $request)
	{
		$input = $request->all();
		$user_id = $input['user_id'];
		$registered_users = DB::table('users')->where('id',$user_id)->first();
		return response()->json(array('name'=>$registered_users->name,'email'=>$registered_users->email,'address1'=>$registered_users->address_1,'city'=>$registered_users->city,'pincode'=>$registered_users->pincode,'phone'=>$registered_users->phone,'msg'=>'Address Set Successfully!!'));
	}

	public function checkAvailability(Request $request)
	{
		$input = $request->all();
		$product_id = $input['product_id'];
		$cur_qty = $input['cur_qty'];
		$availableStock = 'yes';
		$get_proudct_details = DB::table('products')->where('id',$product_id)->first();
		if($cur_qty>$get_proudct_details->available_qty)
		{
			$availableStock = 'no';
		}
		return response()->json(array('availableStock'=>$availableStock));
	}

}

