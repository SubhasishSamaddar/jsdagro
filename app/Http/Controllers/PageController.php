<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Category;
use App\Product;
use App\UserContact;
use App\ContactUs;
use Session;
use Cookie;
use App\Notifications\Contact;
use Notification;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{

	public function __construct(){
		/*Cookie::queue(Cookie::make('swadesh_hut_id', 2, 5));
		Cookie::queue(Cookie::make('swadesh_pin_code', 713401, 5));
		Cookie::queue(Cookie::make('swadesh_hut_name', 'Burdwan', 5));*/
    }


	public function comingsoon(){
		return view('front.pages.home');
	}


    public function homepage(Request $request){
      
		//Cookie::queue('gg', '10', '5');
        /*  
		$content = Content::where('url_aliase','home')->first();
        $homecontent_banner = Banner::orderBy('position','ASC')->where('status','=','Active')->get();
        $data = VideoCategory::with('videohomeitem')->get()->map(function ($query) {
            $query->setRelation('videohomeitem', $query->videohomeitem->take(8));
            return $query;
        });
        $videos = $data->toArray();
		*/
        //return view('home.home', compact('content','videos','homecontent_banner'));
		//echo '<pre>';print_r(Auth::user());die;
		//	BANNER 
		$banners = DB::table('banners')
					->where('banner_image_type', 'normal')
					->get();
		
		//	SMALL BANNER 
		$smallBanners = DB::table('banners')
					->where('banner_image_type', 'small')
					->get();
		
		//print_r($banners);die; 
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
		//	PARENT CATEGORY
		$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->orderBy('position_order','ASC')->limit(12)->get();
		
		//	SHOW IN HOME PAGE CATEGORY
		$showinHomePageCategory = Category::where('show_home_page','Show')->orderBy('position_order','ASC')->limit(15)->get();
		 
		
		if(Session::has('swadesh_hut_id')):
			$swadesh_hut_id=Session::get('swadesh_hut_id');
			$newProducts = DB::table('products as A')
				->select('A.id','A.prod_name','A.price', 'A.discount', 'A.stock_alert','A.status','A.product_image')
				->where('A.swadesh_hut_id', $swadesh_hut_id)
				->orderBy('A.id','DESC')->get();
		else:  
			$newProducts = DB::table('products as A')
			->select('A.id','A.prod_name','A.price', 'A.discount', 'A.stock_alert', 'A.status','A.product_image')
								->orderBy('A.id','DESC')->get();
		endif;  
		//echo '<pre>';print_r($newProducts);die;


		//Featured Product
		if(Session::has('swadesh_hut_id')):
			$swadesh_hut_id=Session::get('swadesh_hut_id');
			
			 if( !isset($swadesh_hut_id) || ($swadesh_hut_id ==null) ){
			 $swadesh_hut_id= 3;
			 }
			
			$featuredroducts = Product::where('is_featured', 'yes')
							->where('swadesh_hut_id', $swadesh_hut_id) 
							->where(function ($query){
								$query->whereRaw('similar_product = sku')
								->orWhereNull('similar_product');
							})
							->orderBy('id','DESC')
							->limit(10)
							->get();


			// $featuredroducts = DB::table('products as A')
			// 	->select('A.id','A.prod_name','A.price', 'A.discount', 'A.stock_alert','A.status','A.product_image', 'A.weight_per_pkt','A.weight_unit')
			// 	->where('A.is_featured', 'yes')
			// 	->where('A.swadesh_hut_id', $swadesh_hut_id)
			// 	->orderBy('A.id','DESC')
			// 	->limit(10)
			// 	->get();
		else:  
			$featuredroducts = Product::where('is_featured', 'yes')
			->where('swadesh_hut_id', 3)
			->where(function ($query){
				$query->whereRaw('similar_product = sku')
				->orWhereNull('similar_product');
			})
			->limit(10)
			->orderBy('id','DESC')->get();

			// $featuredroducts = DB::table('products as A')
			// ->select('A.id','A.prod_name','A.price', 'A.discount', 'A.stock_alert', 'A.status','A.product_image', 'A.weight_per_pkt','A.weight_unit')
			// ->where('A.is_featured', 'yes')
			// ->limit(10)
			// ->orderBy('A.id','DESC')->get();
		endif;   

		/*Cookie::queue(Cookie::make('swadesh_hut_id', 2, 5));
		Cookie::queue(Cookie::make('swadesh_pin_code', 713401, 5));
		Cookie::queue(Cookie::make('swadesh_hut_name', 'Burdwan', 5));*/
		
		
		//set session for default store
		if(!Session::has('swadesh_hut_id'))
		{
			$request->session()->put('swadesh_hut_name', 'uttarpara');
			$request->session()->put('swadesh_pin_code', '712232');
			$request->session()->put('swadesh_hut_id', '3');
			/*Session::set('swadesh_hut_name', 'Uttarpara');
			Session::set('swadesh_pin_code', '712232');
			Session::set('swadesh_hut_id', '3');*/
		}
			
		if( !isset($swadesh_hut_id) || ($swadesh_hut_id ==null) ){
			 $swadesh_hut_id= 3;
			 }


		//print_r(Auth::user());exit;
		//echo $request->session()->get('swadesh_hut_id');exit;
		return view('front.pages.home_old',compact('banners', 'smallBanners', 'categories', 'siteBarProducts', 'newProducts', 'parentCategory', 'showinHomePageCategory','featuredroducts','swadesh_hut_id'));
    }
	
	
	public function contactpage(){
		//	PARENT CATEGORY   
		$pageTitle='Help';  
		$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->orderBy('position_order','ASC')->limit(12)->get();
		return view('front.pages.help',compact('parentCategory', 'pageTitle'));
    }
	
	public function legalnoticepage(){
		//	PARENT CATEGORY   
		$pageTitle='Legal Notice';  
		$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->limit(12)->get();
		return view('front.pages.legalnotice',compact('parentCategory', 'pageTitle'));
    }
	
	public function termsandconditionspage(){
		//	PARENT CATEGORY   
		$pageTitle='Terms & Conditions';    
		$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->orderBy('position_order','ASC')->limit(12)->get();
		return view('front.pages.legalnotice',compact('parentCategory', 'pageTitle'));
    }
	
	
	public function aboutuspage(){  
		//	PARENT CATEGORY   
		$pageTitle='About Us';      
		$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->orderBy('position_order','ASC')->limit(12)->get();
		return view('front.pages.aboutus',compact('parentCategory', 'pageTitle'));
    }
	
	public function sitemappage(){  
		//	PARENT CATEGORY   
		$pageTitle='Sitemap';          
		$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->orderBy('position_order','ASC')->limit(12)->get();
		return view('front.pages.sitemap',compact('parentCategory', 'pageTitle'));
    }

	public function faqpage(){  
		//	PARENT CATEGORY   
		$pageTitle='faq';          
		$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->orderBy('position_order','ASC')->limit(12)->get();
		return view('front.pages.faq',compact('parentCategory', 'pageTitle'));
    }


	public function setCookie()
	{
		Cookie::queue(Cookie::make('swadesh_hut_id', 1, 3));
		Cookie::queue(Cookie::make('swadesh_pin_code', 712232, 3));
		Cookie::queue(Cookie::make('swadesh_hut_name', 'Uttarpara', 3));

		return json_encode( array('msg' => 'Select Store<strong>Uttarpara</strong>', 'pincode' => 712232));
	}


	public function saveHelpData(Request $request)
	{
		$input = $request->all();

		$validator = Validator::make($request->all(), [
			'name' => 'required',
            'email' => 'required',
			'phone' => 'required',
            'topic' => 'required',
			'message' => 'required'
		]);

			
		if ($validator->fails()) {
			$error_msg = $validator->errors()->first();
			$msg = '<div class="alert alert-danger" role="alert">'.$error_msg.'</div>';
	   	}
		else {
			$user_contact_array = array(
				'user_contact_name'=>$input['name'],
				'user_contact_email'=>$input['email'],
				'user_contact_phone_no'=>$input['phone'],
				'user_contact_topic'=>$input['topic'],
				'user_contact_comment'=>$input['message']
			);
			UserContact::create($user_contact_array);

			$headers='MIME-Version: 1.0' . "\r\n";
			$headers.='Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers.="From: no-reply@swadeshhaat.co.in";

			$subject = "Swadeshhaat Help Reply";
			$txt = "Hi, ".$input['name']."!! Thank You For contacting Us.&nbsp;&nbsp; We Will Get Back To You within 24 Hrs";
			mail($input['email'],$subject,$txt,$headers);

			$admin_subject = "User Contacted For Help";
			$amin_txt = $input['name'].' Contacted For Help. <br clear="all">email:'.$input['email'].' <br clear="all">Phone Number:'.$input['phone'].' <br clear="all">Contacted For:'.$input['topic'];
			mail('info@jsdagro.com',$admin_subject,$amin_txt,$headers);

			$msg = '<div class="alert alert-success" role="alert">Thank You For Contacting Us!!We will get back to you soon</div>';
		}
		return response()->json(['msg'=>$msg]);
	}


	public function contactus()
	{
		$pageTitle='Contact us';  
		$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->orderBy('position_order','ASC')->limit(12)->get();
		return view('front.pages.contactus',compact('parentCategory', 'pageTitle'));
	}

	public function saveContactUsData(Request $request)
	{
		$input = $request->all();

		$validator = Validator::make($request->all(), [
			'user_name' => 'required',
            'user_email' => 'required|unique:contact_us',
			'user_phone_no' => 'required',
			'user_comment' => 'required',
			'user_subject' => 'required'
		]);

			
		if ($validator->fails()) {
			//$error_msg = $validator->messages()->toJson();
			$error_msg = $validator->errors()->first();
			$msg = '<div class="alert alert-danger" role="alert">'.$error_msg.'</div>';
	   	}
		else {
			$user_contact_array = array(
				'user_name'=>$input['user_name'],
				'user_email'=>$input['user_email'],
				'user_phone_no'=>$input['user_phone_no'],
				'subject'=>$input['user_subject'],
				'user_comment'=>$input['user_comment']
			);
			ContactUs::create($user_contact_array);

			$headers='MIME-Version: 1.0' . "\r\n";
			$headers.='Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers.="From: no-reply@swadeshhaat.co.in";

			$subject = "Swadeshhaat Contact Us Reply";
			$txt = "Hi, ".$input['user_name']."!! Thank You For contacting Us.&nbsp;&nbsp; We Will Get Back To You within 24 Hrs";
			mail($input['user_email'],$subject,$txt,$headers);

			$admin_subject = "User Contacted For Help";
			$amin_txt = $input['user_name'].' Contacted For Query. <br clear="all">email:'.$input['user_email'].' <br clear="all">Phone Number:'.$input['user_phone_no'];
			mail('info@jsdagro.com',$admin_subject,$amin_txt,$headers);

			$msg = '<div class="alert alert-success" role="alert">Thank You For Contacting Us!!We will get back to you soon</div>';
		}
		return response()->json(['msg'=>$msg]);
	}
	
}
