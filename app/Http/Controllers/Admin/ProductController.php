<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use DB;

use App\Category;

use App\Product;

use App\User;

use Image;

use Auth;

use Helper;



class ProductController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

    */

    function __construct()

    {

         $this->middleware('permission:product-list');

         $this->middleware('permission:product-create', ['only' => ['create','store']]);

         $this->middleware('permission:product-edit', ['only' => ['edit','update']]);

         $this->middleware('permission:product-delete', ['only' => ['destroy']]);

    }



    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index(Request $request)

    {

        $user_type = Auth::user()->user_type;



        if($user_type=='Swadesh_Hut')

        {

            $get_swadesh_hut_user = DB::table('swadesh_hut_users')->where('user_id',Auth::user()->id)->first();

            $products = DB::table('products as A')

            ->where('swadesh_hut_id',$get_swadesh_hut_user->swadesh_hut_id)

            ->select('A.id','A.prod_name','A.price','A.max_price','A.status','A.product_image', 'A.description','A.stock_alert','A.available_qty','A.ordered_qty','A.weight_per_pkt','A.weight_unit','B.name as category_name','C.location_name as swadesh_hut','A.created_at','A.updated_at')

            ->leftJoin('categories as B','A.category_id','=','B.id')

			->leftJoin('swadesh_huts as C','A.swadesh_hut_id','=','C.id')

            ->orderBy('A.prod_name','ASC')->paginate(10);



        }else{

            $products = DB::table('products as A')

            ->select('A.id','A.prod_name','A.price','A.max_price','A.status','A.product_image', 'A.description','A.stock_alert','A.available_qty','A.ordered_qty','A.weight_per_pkt','A.weight_unit','B.name as category_name','C.location_name as swadesh_hut','A.created_at','A.updated_at')

            ->leftJoin('categories as B','A.category_id','=','B.id')

            ->leftJoin('swadesh_huts as C','A.swadesh_hut_id','=','C.id')

            ->orderBy('A.prod_name','ASC')->paginate(10);

        }

		//echo '<pre>';print_r($products);die;





        return view('admin.products.index',compact('products','user_type'));

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        $categories = Category::select('id','name')

                            ->where('status','Active')

                            ->orderBy('name','ASC')->get();

        $swadesh_huts = DB::table('swadesh_huts')->get();

        return view('admin.products.create',compact('categories','swadesh_huts'));

    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)

    {

        $this->validate($request, [

            'prod_name' => 'required|max:200',

            'category_id' => 'required',

            'price' => 'required',

            'position_order' => 'numeric',

            'product_image' => 'image|mimes:jpeg,png,jpg|max:2048',

            'description' => 'required',

            'specification' => 'required',

        ]);

        $input = $request->all();

        $input['product_image'] = 'product/product.png';

        $input['status']= (isset($input['status']) && $input['status']=='on')?'Active':'Inactive';



        $input['available_qty']= (isset($input['available_qty']) && $input['available_qty']!='')?$input['available_qty']:0;

        $input['ordered_qty']= (isset($input['ordered_qty']) && $input['ordered_qty']!='')?$input['ordered_qty']:0;

        $input['swadesh_hut_id']= (isset($input['swadesh_hut_id']) && $input['swadesh_hut_id']!='')?$input['swadesh_hut_id']:0;

        $input['weight_per_pkt']= (isset($input['weight_per_pkt']) && $input['weight_per_pkt']!='')?$input['weight_per_pkt']:0.00;

        $input['cgst']= (isset($input['cgst']) && $input['cgst']!='')?$input['cgst']:0.00;

        $input['sgst']= (isset($input['sgst']) && $input['sgst']!='')?$input['sgst']:0.00;

        $input['igst']= (isset($input['igst']) && $input['igst']!='')?$input['igst']:0.00;

        $input['weight_unit']= (isset($input['weight_unit']) && $input['weight_unit']!='')?$input['weight_unit']:"kilogram";



        $input['is_featured']= (isset($input['is_featured']) && $input['is_featured']=='on')?'yes':'no';



        $product_image = '';

        if ($files = $request->file('product_image')) {

            $product_image = 'product_'.date('YmdHis') . "." . $files->getClientOriginalExtension();



            /*Image Resize*/

            ///$resizeDestinationPath = public_path('/storage/product/resize-image');

            //$img = Image::make($files->getRealPath());

            //$img->resize(275, 335)->save($resizeDestinationPath.'/'.$product_image);



            // Define upload path

            $destinationPath = public_path('/storage/product/'); // upload path

            // Upload Orginal Image



            $files->move($destinationPath, $product_image);

            $input['product_image'] = 'product/'.$product_image;

        }

        $product = Product::create($input);



        return redirect()->route('products.index')

                        ->with('success','Product created successfully');

    }



    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function show($id)

    {

        //$product = Product::find($id);





		$product = DB::table('products as A')

            ->select('A.*','B.name as category_name','C.location_name as swadesh_hut')

            ->leftJoin('categories as B','A.category_id','=','B.id')

            ->leftJoin('swadesh_huts as C','A.swadesh_hut_id','=','C.id')

            ->orderBy('A.prod_name','ASC')->first();



		$product_inventory = DB::table('package_inventory_out as A')

            ->select('A.*','C.location_name as swadesh_hut')

            ->leftJoin('swadesh_huts as C','A.swadesh_hut_id','=','C.id')

            ->orderBy('A.id','ASC')->first();





		//echo '<pre>';print_r($product);die;

        return view('admin.products.view',compact('product', 'product_inventory'));

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function edit($id)

    {

        $product = Product::find($id);

        $categories = Category::select('id','name')

                            ->where('status','Active')

                            ->orderBy('name','ASC')->get();

        $swadesh_huts = DB::table('swadesh_huts')->get();



        $get_weight_price_by_product_id = DB::table('product_weight_price')->where('product_id',$id)->get();



        return view('admin.products.edit',compact('product','categories','swadesh_huts','get_weight_price_by_product_id'));

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, $id)

    {

        $this->validate($request, [

            'prod_name' => 'required|max:200',

            'category_id' => 'required',

            'price' => 'required',

            'position_order' => 'numeric',

            'product_image' => 'image|mimes:jpeg,png,jpg|max:2048',

            'description' => 'required',

            'specification' => 'required',

        ]);



        $input = $request->all();



        $input['status']= (isset($input['status']) && $input['status']=='on')?'Active':'Inactive';



        $input['available_qty']= (isset($input['available_qty']) && $input['available_qty']!='')?$input['available_qty']:0;

        $input['ordered_qty']= (isset($input['ordered_qty']) && $input['ordered_qty']!='')?$input['ordered_qty']:0;

        $input['swadesh_hut_id']= (isset($input['swadesh_hut_id']) && $input['swadesh_hut_id']!='')?$input['swadesh_hut_id']:0;

        $input['weight_per_pkt']= (isset($input['weight_per_pkt']) && $input['weight_per_pkt']!='')?$input['weight_per_pkt']:0.00;

        $input['cgst']= (isset($input['cgst']) && $input['cgst']!='')?$input['cgst']:0.00;

        $input['sgst']= (isset($input['sgst']) && $input['sgst']!='')?$input['sgst']:0.00;

        $input['igst']= (isset($input['igst']) && $input['igst']!='')?$input['igst']:0.00;

        $input['weight_unit']= (isset($input['weight_unit']) && $input['weight_unit']!='')?$input['weight_unit']:"kilogram";



        $input['is_featured']= (isset($input['is_featured']) && $input['is_featured']=='on')?'yes':'no';



        $product_image = '';

        if ($files = $request->file('product_image')) {

            $product_image = 'product_'.date('YmdHis') . "." . $files->getClientOriginalExtension();



            /*Image Resize*/

            //$resizeDestinationPath = public_path('/storage/product/resize-image');

            //$img = Image::make($files->getRealPath());

            //$img->resize(275, 335)->save($resizeDestinationPath.'/'.$product_image);





            // Define upload path

            $destinationPath = public_path('/storage/product/'); // upload path

            // Upload Orginal Image

            $files->move($destinationPath, $product_image);

            $input['product_image'] = 'product/'.$product_image;

        }





        $get_product_weight_details = DB::table('product_weight_price')->where('product_id',$id)->get();

        if(!empty($get_product_weight_details))

        {

            DB::table('product_weight_price')->where('product_id',$id)->delete();

        }

        $product_weight_per_pkt = $input['product_weight_per_pkt'];

        $product_weight_unit = $input['product_weight_unit'];

        $product_price = $input['product_price'];

        for($i=0;$i<count($product_weight_per_pkt);$i++)

        {

            $weight_price_array = array(

                'product_id'=>$id,

                'weight_per_pkt'=>$product_weight_per_pkt[$i],

                'weight_unit'=>$product_weight_unit[$i],

                'price'=>$product_price[$i],

                'created_at'=>date('y-m-d H:i:s'),

                'updated_at'=>date('y-m-d H:i:s')

            );

            DB::table('product_weight_price')->insert($weight_price_array);

        }





        $product = Product::find($id);

        $product->update($input);



        return redirect()->route('products.index')

                        ->with('success','Product updated successfully');

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function destroy($id)

    {

        Product::find($id)->delete();

        return redirect()->route('products.index')

                        ->with('success','Product deleted successfully');

    }



    /**

     * Responds with a welcome message with instructions

     *

     * @return \Illuminate\Http\Response

     */

    public function changeStatus(Request $request)

    {

        $product = Product::find($request->id);

        $product->status = $request->status;

        $product->save();



        return response()->json(['success'=>'Status change successfully.']);

    }



	public function productImport(Request $request){

		if(isset($_POST['_token'])){

			$request->validate([

                'product_file' => 'required|max:1|mimes:csv,txt'

            ]);



			if( is_dir(public_path('/storage/product-import/')) === false ):

				mkdir( public_path('/storage/product-import/'), 0755);

			endif;



			$files = $request->file('product_file');

            $fileName = 'product_import_'.date('YmdHis') . "." . $files->getClientOriginalExtension();

			// Define upload path

            $destinationPath = public_path('/storage/product-import/'); // upload path

            $files->move($destinationPath, $fileName);



			// Import CSV to Database

			$filepath = public_path('/storage/product-import/'.$fileName);

			$file = fopen($filepath,"r");

			$importData_arr = array();

			$i = 0;

			while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {

				echo '<pre>';

				$num = count($filedata );

				if( $i!=0 ){

					/*

					for ($c=0; $c < $num; $c++) {

						echo '==>'.$filedata [$c];

					}

					*/

					$data['prod_name']=$filedata[0];

					if( isset($filedata[1]) && is_numeric($filedata[1]) ):

						$filedata[1]=$filedata[1];

					else:

						$filedata[1]='0';

					endif;

					$data['category_id']=$filedata[1];

					$data['sku']=($filedata[2])?$filedata[2]:"";

					if( isset($filedata[3]) && is_numeric($filedata[3]) ):

						$filedata[3]=$filedata[3];

					else:

						$filedata[3]='0';

					endif;

					$data['swadesh_hut_id']=$filedata[3];

					$data['weight_per_pkt']=($filedata[4])?$filedata[4]:'0.00';

					$data['weight_unit']=$filedata[5];

					$data['price']=($filedata[6])?$filedata[6]:'0.00';

					$data['cgst']=($filedata[7])?$filedata[7]:'0.00';

					$data['sgst']=($filedata[8])?$filedata[8]:'0.00';

					$data['igst']=($filedata[9])?$filedata[9]:'0.00';

					if( isset($filedata[10]) && ( $filedata[10]=='Active' || $filedata[10]=='Inactive' ) ):

						$filedata[10]=$filedata[10];

					else:

						$filedata[10]='Inactive';

					endif;



					$data['status']=$filedata[10];

					$data['product_image']=($filedata[11])?$filedata[11]:'product/product.png';

					$data['description']=($filedata[12])?$filedata[12]:'';

					$data['specification']=($filedata[13])?$filedata[13]:'';

					$data['available_qty']=($filedata[14])?$filedata[14]:'0';

					$data['ordered_qty']=($filedata[15])?$filedata[15]:'0';

					$data['created_at']=date('Y-m-d H:m:s');

					//print_r($data);die;

					$product = Product::create($data);

				}

				$i++;

			}

			fclose($file);

			return redirect()->route('products.index')

                        ->with('success','Product created successfully');

		}

		return view('admin.products.import');

	}





	public function exportCsv(Request $request)

	{

	 $fileName = 'product-'.date('Y-m-d').'.csv';



     $get_user_details = User::where('id',Auth::user()->id)->first();



     if($get_user_details->user_type=='Swadesh_Hut')

     {

        $swadesh_hut_user_details = DB::table('swadesh_hut_users')->where('user_id',Auth::user()->id)->first();

        $tasks = DB::table('products as A')

        ->select('A.*','B.name as category_name','C.location_name as swadesh_hut')

        ->where('A.swadesh_hut_id','=',$swadesh_hut_user_details->swadesh_hut_id)

        ->leftJoin('categories as B','A.category_id','=','B.id')

        ->leftJoin('swadesh_huts as C','A.swadesh_hut_id','=','C.id')

        ->orderBy('A.prod_name','ASC')->get();

     }

     else 

     {

        $tasks = DB::table('products as A')

        ->select('A.*','B.name as category_name','C.location_name as swadesh_hut')

        ->leftJoin('categories as B','A.category_id','=','B.id')

        ->leftJoin('swadesh_huts as C','A.swadesh_hut_id','=','C.id')

        ->orderBy('A.prod_name','ASC')->get();

     }

	 

	/*$tasks =  $products = DB::table('products')

            ->orderBy('id','ASC')->get();*/

		//echo '<pre>';print_r($tasks);die;

        $headers = array(

            "Content-type"        => "text/csv",

            "Content-Disposition" => "attachment; filename=$fileName",

            "Pragma"              => "no-cache",

            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",

            "Expires"             => "0"

        );



        $columns = array('Id', 'Product Name', 'Category Name', 'Category Id', 'SKU', 'Swadesh Hut Name', 'Swadesh Hut Id', 'Weight/Packet', 'Weight Unit ', 'Price', 'Discount', 'Stock Alert', 'CGST', 'SGST', 'IGST', 'Status', 'Product Image', 'Description', 'Specification', 'Available Quantity', 'Ordered Quantity', 'hsn', 'Bar Code', 'Created', 'Updated' );



        $callback = function() use($tasks, $columns) {

            $file = fopen('php://output', 'w');

            fputcsv($file, $columns);



            foreach ($tasks as $task) {



                fputcsv($file, array( $task->id, $task->prod_name, $task->category_name, $task->category_id, $task->sku, $task->swadesh_hut, $task->swadesh_hut_id, $task->weight_per_pkt, $task->weight_unit , $task->price, $task->discount, $task->stock_alert, $task->cgst, $task->sgst, $task->igst, $task->status, $task->product_image, $task->description, $task->specification, $task->available_qty, $task->ordered_qty , $task->hsn, $task->barcode, $task->created_at ,$task->updated_at ));

            }



            fclose($file);

        };



        return response()->stream($callback, 200, $headers);

    }





	public function export()

{

    $headers = array(

        "Content-type" => "text/csv",

        "Content-Disposition" => "attachment; filename=file.csv",

        "Pragma" => "no-cache",

        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",

        "Expires" => "0"

    );



    $reviews = Reviews::getReviewExport($this->hw->healthwatchID)->get();

    $columns = array('ReviewID', 'Provider', 'Title', 'Review', 'Location', 'Created', 'Anonymous', 'Escalate', 'Rating', 'Name');



    $callback = function() use ($reviews, $columns)

    {

        $file = fopen('php://output', 'w');

        fputcsv($file, $columns);



        foreach($reviews as $review) {

            fputcsv($file, array($review->reviewID, $review->provider, $review->title, $review->review, $review->location, $review->review_created, $review->anon, $review->escalate, $review->rating, $review->name));

        }

        fclose($file);

    };

    return Response::stream($callback, 200, $headers);

}



	public function changeDiscount(Request $request){

		//echo $request->discount;//die;

		if( $request->discount && $request->discount>=0 && $request->discount<=100 ):

			$product = Product::find($request->id);

			$product->discount = $request->discount;

			$product->save();

			return response()->json(['success'=>'Discount Updated Successfully.']);

		else:

			return response()->json(['success'=>'Discount Range Must be 0-100']);

		endif;



    }



	public function changeStockAlert(Request $request){

		//echo '====>'.$request->stock_alert;die;

		if( $request->stock_alert && $request->stock_alert>=0 && $request->stock_alert<=100 ):

			$product = Product::find($request->id);

			$product->stock_alert = $request->stock_alert;

			$product->save();

			return response()->json(['success'=>'Stock Alert Updated Successfully.']);

		else:

			return response()->json(['success'=>'Stock Alert Range Must be 0-100']);

		endif;

    }



    public function generateCriticalProductReport()

    {

        $fileName = 'critical-quantity-product-'.date('Y-m-d').'.csv';



        $tasks = DB::table('products as A')

                ->select('A.*','B.name as category_name','C.location_name as swadesh_hut')

                ->whereRaw('A.available_qty<A.stock_alert')

                ->leftJoin('categories as B','A.category_id','=','B.id')

                ->leftJoin('swadesh_huts as C','A.swadesh_hut_id','=','C.id')

                ->orderBy('A.prod_name','ASC')->get();



        /*$tasks =  $products = DB::table('products')

                ->orderBy('id','ASC')->get();*/

            //echo '<pre>';print_r($tasks);die;

        $headers = array(

            "Content-type"        => "text/csv",

            "Content-Disposition" => "attachment; filename=$fileName",

            "Pragma"              => "no-cache",

            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",

            "Expires"             => "0"

        );



        $columns = array('Id', 'Product Name', 'Category Name', 'Category Id', 'SKU', 'Swadesh Hut Name', 'Swadesh Hut Id', 'Weight/Packet', 'Weight Unit ', 'Price', 'Discount', 'Stock Alert', 'CGST', 'SGST', 'IGST', 'Status', 'Product Image', 'Description', 'Specification', 'Available Quantity', 'Ordered Quantity', 'Created');



        $callback = function() use($tasks, $columns) {

            $file = fopen('php://output', 'w');

            fputcsv($file, $columns);



            foreach ($tasks as $task) {



                fputcsv($file, array( $task->id, $task->prod_name, $task->category_name, $task->category_id, $task->sku, $task->swadesh_hut, $task->swadesh_hut_id, $task->weight_per_pkt, $task->weight_unit , $task->price, $task->discount, $task->stock_alert, $task->cgst, $task->sgst, $task->igst, $task->status, $task->product_image, $task->description, $task->specification, $task->available_qty, $task->ordered_qty, date("jS F, Y", strtotime($task->created_at))));

            }



            fclose($file);

        };



        return response()->stream($callback, 200, $headers);

    }

    public function returnProduct(Request $request)
    {
        $input = $request->all();
        $product_id = $input['id'];
        $return_qty = $input['return_qty'];

        $product = Product::find($product_id);
        $update_array = array('available_qty'=>$product->available_qty-$return_qty);
        $product->update($update_array);

        $get_pi_details = DB::table('package_inventories')->where('sku',$product->sku)->first();
        $pi_update_quantity_array = array('available_qty'=>$get_pi_details->available_qty+$return_qty);
        DB::table('package_inventories')->where('id',$get_pi_details->id)->update($pi_update_quantity_array);

        $return_insert_array = array('product_id'=>$product_id,'package_inventory_id'=>$get_pi_details->id,'swadesh_hut_id'=>$product->swadesh_hut_id,'product_name'=>$product->prod_name,'product_sku'=>$product->sku,'return_quantity'=>$return_qty,'created_at'=>date('Y-m-d H:i:s'));
        DB::table('returned_product_details')->insert($return_insert_array);


        return response()->json(['success'=>'Product Return Initiated!!']);
    }


    public function expiryproductlog(Request $request)
    {
        $tasks = DB::table('products as A')
        ->select('A.*','B.name as category_name','C.location_name as swadesh_hut')
        ->where('A.expiry_date', '<>' , '')
        ->leftJoin('categories as B','A.category_id','=','B.id')
        ->leftJoin('swadesh_huts as C','A.swadesh_hut_id','=','C.id')
        ->orderBy('A.prod_name','ASC')->get();

        $fileName = 'expiry-product-info'.date('Y-m-d').'.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Product Name', 'Expiry Date', 'Expire In(Days)', 'Weight/Packet', 'Weight Unit ', 'Created');


        $callback = function() use($tasks, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($tasks as $task) {
                if($task->expiry_date!='')
                {
                    $diff = abs(strtotime($task->expiry_date) - strtotime(now()));
                    $years = floor($diff / (365*60*60*24));
                    $months = floor(($diff - $years * 365*60*60*24)/(30*60*60*24));
                    $days = floor(($diff - $years * 365*60*60*24)/ (60*60*24));
                    if($months==0 && $days>0 && (strtotime($task->expiry_date) - strtotime(now()))>0)
                    {
                        $get_category_details = Category::where('id',$task->category_id)->first();

                        fputcsv($file, array($task->prod_name, date("jS F Y", strtotime($task->expiry_date)),  $days, $task->weight_per_pkt, $task->weight_unit, date("jS F, Y", strtotime($task->created_at))));
                    }                  
                }
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }


    public function changeNameUrl(Request $request){
        $products = Product::all();

        foreach($products as $details){
            $nameUrl=Helper::make_url($details->prod_name);
            $weight = explode('.',$details->weight_per_pkt);
            $nameUrl=$nameUrl.'-'.$weight[0];
            $update_array = array('name_url'=>$nameUrl);
            $products = Product::find($details->id);
            $products->update($update_array);
        }
        return redirect()->route('products.index')->with('success','Name Url Updated successfully');
    }


    public function productSearch(Request $request)
    {
        $table_html = '';
        $pagination_html = '';
        $search_value = $request->search_value;

        $get_category_by_search_value = Category::where('name',$search_value)->first();
        if(!empty($get_category_by_search_value)){
            $products = Product::where('category_id', $get_category_by_search_value->id)->orWhere('barcode', 'LIKE', '%'. $search_value. '%')->orderBy('created_at', 'DESC')->paginate(50);

            $total_products = Product::where('category_id', $get_category_by_search_value->id)->orWhere('barcode', 'LIKE', '%'. $search_value. '%')->get();
        }
        else{
            $user_type = Auth::user()->user_type;
            if($user_type=='Swadesh_Hut')
            {
                $get_swadesh_hut_user = DB::table('swadesh_hut_users')->where('user_id',Auth::user()->id)->first();
                $products = DB::table('products as A')
                ->where('swadesh_hut_id',$get_swadesh_hut_user->swadesh_hut_id)
                ->where('A.prod_name', 'LIKE', '%'. $search_value. '%')
                ->select('A.*','B.name as category_name','C.location_name as swadesh_hut','A.created_at','A.updated_at')
                ->leftJoin('categories as B','A.category_id','=','B.id')
                ->leftJoin('swadesh_huts as C','A.swadesh_hut_id','=','C.id')
                ->orderBy('A.prod_name','ASC')->paginate(50);

                $total_products = DB::table('products as A')
                ->where('swadesh_hut_id',$get_swadesh_hut_user->swadesh_hut_id)
                ->where('A.prod_name', 'LIKE', '%'. $search_value. '%')
                ->select('A.*','B.name as category_name','C.location_name as swadesh_hut','A.created_at','A.updated_at')
                ->leftJoin('categories as B','A.category_id','=','B.id')
                ->leftJoin('swadesh_huts as C','A.swadesh_hut_id','=','C.id')
                ->orderBy('A.prod_name','ASC')->get();


            }else{
                $products = DB::table('products as A')
                ->where('A.prod_name', 'LIKE', '%'. $search_value. '%')
                ->select('A.*','B.name as category_name','C.location_name as swadesh_hut','A.created_at','A.updated_at')
                ->leftJoin('categories as B','A.category_id','=','B.id')
                ->leftJoin('swadesh_huts as C','A.swadesh_hut_id','=','C.id')
                ->orderBy('A.prod_name','ASC')->paginate(50);

                $total_products = DB::table('products as A')
                ->where('A.prod_name', 'LIKE', '%'. $search_value. '%')
                ->select('A.*','B.name as category_name','C.location_name as swadesh_hut','A.created_at','A.updated_at')
                ->leftJoin('categories as B','A.category_id','=','B.id')
                ->leftJoin('swadesh_huts as C','A.swadesh_hut_id','=','C.id')
                ->orderBy('A.prod_name','ASC')->get();
            }

        }

        $total_page = count($total_products)/50;
        $last_page = count($total_products)%50;


        if(count($products)>0){
            foreach($products as $inv){
                $get_category_details = DB::table('categories')->where('id',$inv->category_id)->first();
                if(isset($get_category_details->name) && $get_category_details->name!=''){
                    $cname = $get_category_details->name;
                }else {
                    $cname = 'N/A';
                }
                

                $table_html.='<tr><td>'.$inv->prod_name.'</td><td>'.$inv->category_name.'</td><td> र'.$inv->max_price.'</td><td> र'.$inv->price.'</td><td class="text-center">'.(int)$inv->available_qty.'</td><td class="text-center">'.$inv->weight_per_pkt.$inv->weight_unit.'</td><td><img src="'.asset('/storage/'.$inv->product_image).'" style="height:60px;" /></td><td class="text-center"><input data-id="'.$inv->id.'" class="toggle-class  btn-sm ajax-toggle-class" onclick="changeStatus(\''.$inv->id.'\',\''.$inv->status.'\')" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="Inactive" '; if($inv->status=="Active"){ $table_html.='checked'; }   $table_html.='data-size="xs"></td><td class="text-center" data-sort="'.date('d-m-Y',strtotime($inv->created_at)).'">'.date('d-m-Y',strtotime($inv->created_at)).'</td><td class="text-center"><a class="btn btn-success" href="'.route('products.edit',$inv->id).'" target="_blank" title="Edit"><i class="fas fa-edit"></i></a><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#returnModal" onclick="set_return_product_id(\''.$inv->id.'\')">Return Product</button><input type="hidden" id="pavlbl_qty'.$inv->id.'" value="{{ $product->available_qty}}"></td></tr>';
            }
        }
        $pagination_html.='<ul class="pagination" role="navigation"><li class="page-item active" aria-current="page"><span class="page-link">1</span></li>';
        if($total_page>1)
        {
            for($i=2;$i<=$total_page+1;$i++)
            {
                if($i<=20)
                {
                    $pagination_html.='<li class="page-item"><a class="page-link" href="#" onclick="show_search_data(\''.$i.'\')">'.$i.'</a></li>';
                }
                
            }
        }
        $pagination_html.='</ul>';
                                                                        
        return response()->json(array('table_html'=>$table_html,'pagination_html'=>$pagination_html));


    }


    public function showSearchData(Request $request)
    {
        $table_html = '';
        $pagination_html = '';
        $search_value = $request->search_value;
        $page_no = $request->page_no;
        $user_type = Auth::user()->user_type;
        if($user_type=='Swadesh_Hut')
        {
            $get_swadesh_hut_user = DB::table('swadesh_hut_users')->where('user_id',Auth::user()->id)->first();
            $products = DB::table('products as A')
            ->where('swadesh_hut_id',$get_swadesh_hut_user->swadesh_hut_id)
            ->where('A.prod_name', 'LIKE', '%'. $search_value. '%')
            ->select('A.*','B.name as category_name','C.location_name as swadesh_hut','A.created_at','A.updated_at')
            ->leftJoin('categories as B','A.category_id','=','B.id')
            ->leftJoin('swadesh_huts as C','A.swadesh_hut_id','=','C.id')
            ->orderBy('A.prod_name','ASC')->skip(($page_no-1)*50)->take(50)->get();

            $total_products = DB::table('products as A')
            ->where('swadesh_hut_id',$get_swadesh_hut_user->swadesh_hut_id)
            ->where('A.prod_name', 'LIKE', '%'. $search_value. '%')
            ->select('A.*','B.name as category_name','C.location_name as swadesh_hut','A.created_at','A.updated_at')
            ->leftJoin('categories as B','A.category_id','=','B.id')
            ->leftJoin('swadesh_huts as C','A.swadesh_hut_id','=','C.id')
            ->orderBy('A.prod_name','ASC')->get();


        }else{
            $products = DB::table('products as A')
            ->where('A.prod_name', 'LIKE', '%'. $search_value. '%')
            ->select('A.*','B.name as category_name','C.location_name as swadesh_hut','A.created_at','A.updated_at')
            ->leftJoin('categories as B','A.category_id','=','B.id')
            ->leftJoin('swadesh_huts as C','A.swadesh_hut_id','=','C.id')
            ->orderBy('A.prod_name','ASC')->skip(($page_no-1)*50)->take(50)->get();

            $total_products = DB::table('products as A')
            ->where('A.prod_name', 'LIKE', '%'. $search_value. '%')
            ->select('A.*','B.name as category_name','C.location_name as swadesh_hut','A.created_at','A.updated_at')
            ->leftJoin('categories as B','A.category_id','=','B.id')
            ->leftJoin('swadesh_huts as C','A.swadesh_hut_id','=','C.id')
            ->orderBy('A.prod_name','ASC')->get();
        }
        $total_page = count($total_products)/50;
        $last_page = count($total_products)%50;

        if(count($products)>0){
            foreach($products as $inv){
                $get_category_details = DB::table('categories')->where('id',$inv->category_id)->first();
                if(isset($get_category_details->name) && $get_category_details->name!=''){
                    $cname = $get_category_details->name;
                }else {
                    $cname = 'N/A';
                }
                

                $table_html.='<tr><td>'.$inv->prod_name.'</td><td>'.$inv->category_name.'</td><td> र'.$inv->max_price.'</td><td> र'.$inv->price.'</td><td class="text-center">'.(int)$inv->available_qty.'</td><td class="text-center">'.$inv->weight_per_pkt.$inv->weight_unit.'</td><td><img src="'.asset('/storage/'.$inv->product_image).'" style="height:60px;" /></td><td class="text-center"><input data-id="'.$inv->id.'" class="toggle-class  btn-sm ajax-toggle-class" onclick="changeStatus(\''.$inv->id.'\',\''.$inv->status.'\')" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="Inactive" '; if($inv->status=="Active"){ $table_html.='checked'; }   $table_html.='data-size="xs"></td><td class="text-center" data-sort="'.date('d-m-Y',strtotime($inv->created_at)).'">'.date('d-m-Y',strtotime($inv->created_at)).'</td><td class="text-center"><a class="btn btn-success" href="'.route('products.edit',$inv->id).'" target="_blank" title="Edit"><i class="fas fa-edit"></i></a><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#returnModal" onclick="set_return_product_id(\''.$inv->id.'\')">Return Product</button><input type="hidden" id="pavlbl_qty'.$inv->id.'" value="{{ $product->available_qty}}"></td></tr>';
            }
        }
        $pagination_html.='<ul class="pagination" role="navigation">';
        if($total_page>1)
        {
            for($i=1;$i<=$total_page+1;$i++)
            {
                if($i<=20)
                {
                    $pagination_html.='<li class="page-item '; if($i==$page_no) { $pagination_html.='active'; }   $pagination_html.='"><a class="page-link" href="#" onclick="show_search_data(\''.$i.'\')">'.$i.'</a></li>';
                }
                
            }
        }
        $pagination_html.='</ul>';
                                                                        
        return response()->json(array('table_html'=>$table_html,'pagination_html'=>$pagination_html));
    }



    public function addPIDinProdcuct(){
        $all_products = Product::all();
        foreach($all_products as $details)
        {
            $get_pi_details = DB::table('package_inventories')->where('prod_name',$details->prod_name)->where('weight_per_packet',$details->weight_per_pkt)->first();
            $update_array = array('package_inventory_id'=>$get_pi_details->id);
            DB::table('products')->where('id',$details->id)->update($update_array);
        }
        echo "updated";
    }


    public function assigninventoryid()
    {
        $all_products = Product::all();
        foreach($all_products as $details)
        {
            $get_pi_details = DB::table('package_inventories')->where('sku',$details->sku)->first();
            if(isset($get_pi_details->id) && $get_pi_details->id!='')
            {
                $update_array = array('package_inventory_id'=>$get_pi_details->id);
                DB::table('products')->where('id',$details->id)->update($update_array);
            }
        }
        echo "inventory id assigned successfully";
    }


    public function generatestockreport(){
        $all_products = Product::all();
        $all_stores = DB::table('swadesh_huts')->get();

        $fileName = 'product-stock-info'.date('Y-m-d').'.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Product Name', 'Weight', 'Unit', 'Package Inventory Stock');

        foreach($all_stores as $details){
            $columns[]=$details->location_name.' Stock';
        }

        $product_weight_array = array();
        $count = 0;
        $sotck_array = array();
        $product_weight_count = 0;
        foreach ($all_products as $details) {
            if(!in_array($details->prod_name.$details->weight_per_pkt.$details->weight_unit,$product_weight_array))
            {
                $get_package_details = DB::table('package_inventories')->where('id',$details->package_inventory_id)->first();
                if(isset($get_package_details->available_qty))
                {
                    $inv_stock = $get_package_details->available_qty;
                }
                else 
                {
                    $inv_stock = 0;
                }
                $sotck_array[$count][] = $details->prod_name;
                $sotck_array[$count][] = $details->weight_per_pkt;
                $sotck_array[$count][] = $details->weight_unit;
                $sotck_array[$count][] = $inv_stock;

                foreach($all_stores as $store_details){
                    $get_swadesh_stock = DB::table('products')->where('prod_name',$details->prod_name)->where('weight_per_pkt',$details->weight_per_pkt)->where('weight_unit',$details->weight_unit)->where('swadesh_hut_id',$store_details->id)->get();
                    if(!empty($get_swadesh_stock))
                    {
                        $aqty = 0;
                        foreach($get_swadesh_stock as $gss){
                            $aqty+= $gss->available_qty;
                        }
                    }
                    else
                    {
                        $aqty = 0;
                    }
                    $sotck_array[$count][]=$aqty;
                }
                $count++;
            }
            $product_weight_array[$product_weight_count] = $details->prod_name.$details->weight_per_pkt.$details->weight_unit;
            $product_weight_count++;
        }


        $callback = function() use($sotck_array, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($sotck_array as $stock_details) {
                fputcsv($file, array($stock_details[0],$stock_details[1],$stock_details[2],$stock_details[3],$stock_details[4],$stock_details[5]));                                   
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }



}

