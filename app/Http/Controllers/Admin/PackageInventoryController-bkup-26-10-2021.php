<?php



namespace App\Http\Controllers\Admin;



use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\PackageInventory;

use App\ProductWeightPrice;

use App\Category;

use App\Product;

use App\Package_location;

use App\PackageInventoryOut;

use App\User;

use Helper;

use AUth;

use DB;

use View;

use Illuminate\Support\Carbon;



class PackageInventoryController extends Controller

{

    function __construct()

    {

         $this->middleware('permission:package_inventory-list');

         $this->middleware('permission:package_inventory-create', ['only' => ['create','store']]);

         $this->middleware('permission:package_inventory-edit', ['only' => ['edit','update']]);

         $this->middleware('permission:package_inventory-delete', ['only' => ['destroy']]);

    }

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()

    {

        $records=DB::table('package_inventories')->where('user_id',Auth::user()->id)->orderBy('created_at','DESC')->paginate(10);

        $swadesh_huts = DB::table('swadesh_huts')->get();

        $package_locations = DB::table('package_locations')->get();

		return view('admin.package_inventory.index', compact('records','swadesh_huts','package_locations'));

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



        $package_location = Package_location::select('id','location_name')

                            ->where('status','Active')

                            ->orderBy('location_name','ASC')->get();



        $user_id = Auth::user()->id;

        $all_inventory_products = PackageInventory::Where('user_id', $user_id)->get();                    



        return view('admin.package_inventory.create',compact('categories','package_location','all_inventory_products'));

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

            'purchased_price' => 'required',

            'weight_per_packet' => 'required',

            'weight_unit' => 'required',

            'no_of_packet' => 'required',

            'total_weight' => 'required',

           // 'product_image' => 'image|mimes:jpeg,png,jpg|max:2048',

            'product_image' => 'required',

            'description' => 'required',

            'specification' => 'required',

        ]);

        $input = $request->all();

        $input['product_image'] = 'package_inventory/package_inventory.jpg';

        $input['other_image'] = 'package_inventory/package_inventory.jpg';

        $input['inventory_in_out']= 'In';



        $input['package_location_id']=Auth::user()->package_location_id;



        $input['cgst']= (isset($input['cgst']) && $input['cgst']!='')?$input['cgst']:0.00;

        $input['sgst']= (isset($input['sgst']) && $input['sgst']!='')?$input['sgst']:0.00;

        $input['igst']= (isset($input['igst']) && $input['igst']!='')?$input['igst']:0.00;



        $input['sku']= Helper::rand_string(6);



        $input['weight_per_packet']= (isset($input['weight_per_packet']) && $input['weight_per_packet']!='')?$input['weight_per_packet']:1;

        $input['weight_unit']= (isset($input['weight_unit']) && $input['weight_unit']!='')?$input['weight_unit']:'kilogram';

        $input['no_of_packet']= (isset($input['no_of_packet']) && $input['no_of_packet']!='')?$input['no_of_packet']:1;



        $input['user_id']= Auth::user()->id;



        $input['total_weight']= $input['weight_per_packet']*$input['no_of_packet'];



        $input['available_qty']= $input['no_of_packet'];



        $product_image = '';

        if ($files = $request->file('product_image')) {

            // Define upload path

            $destinationPath = public_path('/storage/package_inventory/'); // upload path

            // Upload Orginal Image

            $product_image = 'package_inventory_'.date('YmdHis') . "." . $files->getClientOriginalExtension();

            $files->move($destinationPath, $product_image);

            $input['product_image'] = 'package_inventory/'.$product_image;

        }





        // $other_image = '';

        // if ($files = $request->file('other_image')) {

        //     // Define upload path

        //     $destinationPath = public_path('/storage/package_inventory/'); // upload path

        //     // Upload Orginal Image

        //     $other_image = 'package_inventory_other_'.date('YmdHis') . "." . $files->getClientOriginalExtension();

        //     $files->move($destinationPath, $other_image);

        //     $input['other_image'] = 'package_inventory/'.$other_image;

        // }

        

        $images = $request->file('other_image');

        if ($request->hasFile('other_image')) :

                foreach ($images as $item):

                    $var = date_create();

                    $time = date_format($var, 'YmdHis');

                    $imageName = $time . '-' . $item->getClientOriginalName();

                     $destinationPath = public_path('/storage/package_inventory/'); // upload path

                    $item->move($destinationPath, $imageName);

                    $arr[] = $imageName;

                endforeach;

                $input['other_image'] = implode(",", $arr);

        else:

                $input['other_image'] = '';

        endif;





        $product = PackageInventory::create($input);



        return redirect()->route('package_inventory.index')

                        ->with('success','Inventory created successfully');

    }





    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function show(Request $request)

    {
		/*$records= DB::table('package_inventory_out as A')
        ->where('A.voucher_no','<>','')
        ->select('A.*','B.prod_name','B.product_image','B.weight_per_packet','B.weight_unit','C.location_name')
        ->leftJoin('package_inventories as B','A.package_inventory_id','=','B.id')
        ->leftJoin('swadesh_huts as C','A.swadesh_hut_id','=','C.id')
        ->groupBy('A.voucher_no')
        ->get();*/

        $records= DB::table('package_inventory_out')
            ->where('package_inventory_out.voucher_no','<>','')
            ->join('package_inventories', 'package_inventory_out.package_inventory_id', '=', 'package_inventories.id')
            ->join('swadesh_huts', 'package_inventory_out.swadesh_hut_id', '=', 'swadesh_huts.id')
            ->select('package_inventory_out.*','package_inventory_out.created_at as voucher_date','package_inventories.*','swadesh_huts.*', DB::raw('sum(purchased_price) as totalprice'), DB::raw('count(package_inventory_id) as totalproduct'), DB::raw('sum(inv_out_qty) as totalquantity'))
            ->orderBy('package_inventory_out.created_at','DESC')
            ->groupBy('voucher_no')
            ->get();
        if($request->usertype)
        {
            $user_type = $request->usertype;
        }
        else 
        {
            $user_type = '';
        }
        
        return view('admin.package_inventory.voucher', compact('records','user_type'));



    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function edit($id)

    {

        $inventory = PackageInventory::find($id);

        $categories = Category::select('id','name')

                            ->where('status','Active')

                            ->orderBy('name','ASC')->get();

        $package_location = Package_location::select('id','location_name')

                            ->where('status','Active')

                            ->orderBy('location_name','ASC')->get();

        return view('admin.package_inventory.edit',compact('inventory','categories','package_location'));

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

            'purchased_price' => 'required',

            'weight_per_packet' => 'required',

            'weight_unit' => 'required',

            'sku' => 'required',

            'no_of_packet' => 'required',

            'available_qty' => 'required',

            'total_weight' => 'required',

            'product_image' => 'image|mimes:jpeg,png,jpg|max:2048',

            'description' => 'required',

            'specification' => 'required'

        ]);



        $input = $request->all();

        $input['inventory_in_out']= 'In';



        $input['package_location_id']=Auth::user()->package_location_id;



        $input['cgst']= (isset($input['cgst']) && $input['cgst']!='')?$input['cgst']:0.00;

        $input['sgst']= (isset($input['sgst']) && $input['sgst']!='')?$input['sgst']:0.00;

        $input['igst']= (isset($input['igst']) && $input['igst']!='')?$input['igst']:0.00;



        $input['weight_per_packet']= (isset($input['weight_per_packet']) && $input['weight_per_packet']!='')?$input['weight_per_packet']:1;

        $input['weight_unit']= (isset($input['weight_unit']) && $input['weight_unit']!='')?$input['weight_unit']:'kilogram';

        $input['no_of_packet']= (isset($input['no_of_packet']) && $input['no_of_packet']!='')?$input['no_of_packet']:1;



        $product_image = '';

        if ($files = $request->file('product_image')) {

            // Define upload path

            $destinationPath = public_path('/storage/package_inventory/'); // upload path

            // Upload Orginal Image

            $product_image = 'package_inventory_'.date('YmdHis') . "." . $files->getClientOriginalExtension();

            $files->move($destinationPath, $product_image);

            $input['product_image'] = 'package_inventory/'.$product_image;

        }



        $inventory = PackageInventory::find($id);

        $inventory->update($input);



        return redirect()->route('package_inventory.index')

                        ->with('success','Inventory updated successfully');

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function destroy($id)

    {

        PackageInventory::find($id)->delete();

        return redirect()->route('package_inventory.index')

                        ->with('success','Inventory deleted successfully');

    }



    public function inventoryOutProcess(Request $request)

    {

        $input = $request->all();

        $swadesh_hut_id = $input['swadesh_hut_id'];

        $out_qty = $input['inv_out_qty'];

        $package_inventory_id = $input['package_inventory_id'];

        $package_location_id = $input['package_location_id'];



        $get_package_inventory_details = PackageInventory::find($package_inventory_id);



        $inv_out_qty = $out_qty;





        if($inv_out_qty<=$get_package_inventory_details->available_qty)
        {

            //create product while inventory is being out

            $sku = $get_package_inventory_details->sku;

            $get_product_by_sku = Product::where('sku',$sku)->where('swadesh_hut_id',$swadesh_hut_id)->first();





            if($get_product_by_sku)

            {

                $available_qty = $inv_out_qty+$get_product_by_sku->available_qty;

                $nameUrl=Helper::make_url($get_package_inventory_details->prod_name);

                $update_product_array = array('prod_name'=>$get_package_inventory_details->prod_name,'name_url'=>$nameUrl,'product_image'=>$get_package_inventory_details->product_image,'description'=>$get_package_inventory_details->description,'specification'=>$get_package_inventory_details->specification,'sku'=>$sku,'category_id'=>$get_package_inventory_details->category_id,'swadesh_hut_id'=>$swadesh_hut_id,'weight_per_pkt'=>$get_package_inventory_details->weight_per_packet,'weight_unit'=>$get_package_inventory_details->weight_unit,'price'=>$get_package_inventory_details->selling_price,
                'max_price'=>$get_package_inventory_details->mrp,'cgst'=>$get_package_inventory_details->cgst,'sgst'=>$get_package_inventory_details->sgst,'igst'=>$get_package_inventory_details->igst,'available_qty'=>$available_qty,'similar_product'=>$get_package_inventory_details->similar_product,'other_image'=>$get_package_inventory_details->other_image,'hsn'=>$get_package_inventory_details->hsn,'manufacturer_details'=>$get_package_inventory_details->manufacturer_details,'marketed_by'=>$get_package_inventory_details->marketed_by,'country_of_origin'=>$get_package_inventory_details->country_of_origin,'customer_care_details'=>$get_package_inventory_details->customer_care_details,'seller'=>$get_package_inventory_details->seller,'barcode'=>$get_package_inventory_details->barcode,'wholesale_price'=>$get_package_inventory_details->wholesale_price,'wholesale_min_qty'=>$get_package_inventory_details->wholesale_min_qty,'expiry_date'=>$get_package_inventory_details->expiry_date);

                $get_product_by_sku->update($update_product_array);

                $product_id = $get_product_by_sku->id;



            }

            else

            {
                $nameUrl=Helper::make_url($get_package_inventory_details->prod_name);

                $create_product_array = array('prod_name'=>$get_package_inventory_details->prod_name,'name_url'=>$nameUrl,'product_image'=>$get_package_inventory_details->product_image,'description'=>$get_package_inventory_details->description,'specification'=>$get_package_inventory_details->specification,'sku'=>$sku,'category_id'=>$get_package_inventory_details->category_id,'swadesh_hut_id'=>$swadesh_hut_id,'weight_per_pkt'=>$get_package_inventory_details->weight_per_packet,'weight_unit'=>$get_package_inventory_details->weight_unit,'price'=>$get_package_inventory_details->selling_price,
                'max_price'=>$get_package_inventory_details->mrp,'cgst'=>$get_package_inventory_details->cgst,'sgst'=>$get_package_inventory_details->sgst,'igst'=>$get_package_inventory_details->igst,'available_qty'=>$inv_out_qty,'similar_product'=>$get_package_inventory_details->similar_product,'other_image'=>$get_package_inventory_details->other_image,'hsn'=>$get_package_inventory_details->hsn,'manufacturer_details'=>$get_package_inventory_details->manufacturer_details,'marketed_by'=>$get_package_inventory_details->marketed_by,'country_of_origin'=>$get_package_inventory_details->country_of_origin,'customer_care_details'=>$get_package_inventory_details->customer_care_details,'seller'=>$get_package_inventory_details->seller,'barcode'=>$get_package_inventory_details->barcode,'wholesale_price'=>$get_package_inventory_details->wholesale_price,'wholesale_min_qty'=>$get_package_inventory_details->wholesale_min_qty,'expiry_date'=>$get_package_inventory_details->expiry_date);

                $product = Product::create($create_product_array);

                $product_id = $product->id;

            }







            //create inventory out data with product id

            $inventory_out_array = array('package_inventory_id'=>$package_inventory_id,'package_location_id'=>$package_location_id,'inv_out_qty'=>$inv_out_qty,'swadesh_hut_id'=>$swadesh_hut_id,'user_id'=>Auth::user()->id,'product_id'=>$product_id);

            $inventory_out = PackageInventoryOut::create($inventory_out_array);



            //availableinventory quantity update

            $available_inventory = $get_package_inventory_details->available_qty-$inv_out_qty;

            $inventory_update_array = array('available_qty'=>$available_inventory);

            $get_package_inventory_details->update($inventory_update_array);

            $msg = '<div class="alert alert-success">Inventory Set Out Successfully</div>';

            $error = 0;

            $total_out_qty=0;
            $get_package_inventory_out_details = Helper::get_package_inventory_out_details($package_inventory_id);
            if(count($get_package_inventory_out_details)>0)
            {
                foreach($get_package_inventory_out_details as $pidetails)
                {
                    $total_out_qty+=$pidetails->inv_out_qty;
                }
            }
            $available_qty = $available_inventory;

        }
        else
        {
            $msg = '<div class="alert alert-danger">'.$inv_out_qty.' kilogram Quantity not available in the Inventory</div>';
            $error = 1;
            $total_out_qty = 0;
            $available_qty = 0;
        }



        return response()->json(array('msg'=>$msg,'error'=>$error, 'available_qty'=>$available_qty, 'total_out_qty'=>$total_out_qty));

    }



	public function packageInventoryImport(Request $request){
		$package_location = Package_location::select('id','location_name')
            ->where('status','Active')
            ->orderBy('location_name','ASC')->get();



		if(isset($_POST['_token'])){

			

			$request->validate([

                'inventory_file' => 'required|max:50000|mimes:csv,txt'

            ]);



			if( is_dir(public_path('/storage/package_inventory/')) === false ):

				mkdir( public_path('/storage/package_inventory/'), 0755);

			endif;



			$files = $request->file('inventory_file');

          //  dd($files);

            $fileName = 'inventory_import_'.date('YmdHis') . "." . $files->getClientOriginalExtension();

			// Define upload path

            $destinationPath = public_path('/storage/package_inventory/'); // upload path

            $files->move($destinationPath, $fileName);



			// Import CSV to Database

			$filepath = public_path('/storage/package_inventory/'.$fileName);

			$file = fopen($filepath,"r");

			$importData_arr = array();

			$i = 0;

			while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {

				$num = count($filedata );

				//echo Auth::user()->package_location_id.'===>';

				//echo '<pre>';

            

				if( $i!=0 ){

					//print_r($filedata[2]);

            

					//die;

					$data['prod_name']=($filedata[1])?$filedata[1]:"";

					$selectCategory = Category::select('id','name')

									->where('name', $filedata[3])

									->first();

					if( !empty($selectCategory) ):

						$filedata[3]=$selectCategory->id;

					else:
                        $nameUrl = Helper::make_url($filedata[3]);

						$input['name'] = $filedata[3];

                        $input['name_url'] = $nameUrl;

						$input['category_image'] = 'category/category.png';

						$input['parent_id'] = '0';  

						$input['position_order'] = '1';

						$input['description'] = '';

						$input['status']= 'Active';

						$input['created_at']= date('Y-m-d H:i:s');

						$categoryId = Category::create($input);

						$filedata[3]=$categoryId->id;

					endif;

                    $data['similar_product']=($filedata[2])?$filedata[2]:'';					

                    $data['category_id']=$filedata[3];

					//$data['weight_per_packet']=($filedata[2])?$filedata[2]:1;

					$data['purchased_price']=($filedata[5])?$filedata[5]:'0.00';

					$data['mrp']=($filedata[6])?$filedata[6]:'0.00';

					$data['cgst']=($filedata[7])?$filedata[7]:'0.00';

					$data['sgst']=($filedata[8])?$filedata[8]:'0.00';

					$data['igst']=($filedata[9])?$filedata[9]:'0.00';

					$data['product_image']=(strlen($filedata[10])>0)?$filedata[10]:'product/product.png';

					$data['description']=$filedata[11];

                    $data['specification']=$filedata[12];

                    $data['weight_per_packet']=($filedata[13])?$filedata[13]:1;

					$data['weight_unit']=($filedata[14])?$filedata[14]:'kilogram';

					$data['other_image']=($filedata[16])?$filedata[16]:'';

					$data['hsn']=($filedata[17])?$filedata[17]:'';

					$data['selling_price']=($filedata[18])?$filedata[18]:'0.00';

                $data['manufacturer_details']=($filedata[19])?$filedata[19]:'';

                $data['marketed_by']=($filedata[20])?$filedata[20]:'';

                $data['country_of_origin']=($filedata[21])?$filedata[21]:'';

                $data['customer_care_details']=($filedata[22])?$filedata[22]:'';

                $data['seller']=($filedata[23])?$filedata[23]:'';

                $data['barcode']=($filedata[24])?$filedata[24]:'';

                $data['wholesale_price']=($filedata[24])?$filedata[25]:'';

                $data['wholesale_min_qty']=($filedata[24])?$filedata[26]:'';

                $data['expiry_date']=($filedata[24])?$filedata[27]:'';


					$data['inventory_in_out']='In';

					$data['user_id']=Auth::user()->id;

					$data['package_location_id']=$input['package_location_id']=Auth::user()->package_location_id;

					//$data['total_weight']=($filedata[11]*$filedata[13]);

                    $data['created_at']=date('Y-m-d H:m:s');

                    //echo '<pre>';print_r($data);

                    //die();

					if($filedata[0]!=''){

                        $data['sku'] = $filedata[0];

                    }else{

                        $data['sku']=Helper::rand_string(6);

                    }

                    //echo $data['sku'];

					$find_inventory_by_sku = PackageInventory::where('sku',$data['sku'])->first();

                    //$find_inventory_by_name = PackageInventory::where('prod_name',$data['prod_name'])->first();

                       //dd($find_inventory_by_sku);

                    if($find_inventory_by_sku)

                    {

                        //echo "tahi"; die();

                        $data['available_qty']=$filedata[15]+$find_inventory_by_sku->available_qty;

                        $data['no_of_packet']=$filedata[15]+$find_inventory_by_sku->no_of_packet;

                        $find_inventory_by_sku->update($data);

                        $lastProductId = $find_inventory_by_sku->id;

                    }

                    else

                    {

                        //echo "tar"; die();

                        $data['available_qty']=($filedata[15]);

                        $data['no_of_packet']=($filedata[15])?$filedata[15]:1;

                        $product = PackageInventory::create($data);

                        $lastProductId = $product->id;

                    } 



                    $data1= [];

                    $data1['product_id']= $lastProductId;

                    $data1['weight_per_pkt']=($filedata[13])?$filedata[13]:1;                    

                    $data1['weight_unit']=($filedata[14])?$filedata[14]:'kilogram';

                    $data1['price']=($filedata[5])?$filedata[5]:'0.00';

                    $data1['created_at']=date('Y-m-d H:m:s');

                    //dd($data1);

                    $product_weight_price = ProductWeightPrice::create($data1);

                    

				}

				$i++;    

			}

			fclose($file);

			return redirect()->route('package_inventory.index')

                        ->with('success','Package Inventory created successfully');

		}

		return view('admin.package_inventory.import',compact('package_location'));

	}



	public function exportCsv(Request $request){
	    $fileName = 'package-inventory-'.date('Y-m-d').'.csv';
	    $tasks =DB::table('package_inventory_out as A')
        ->select('A.*','A.created_at as stock_out_date','B.*','C.*','D.location_name as swadesh_hut')
        ->leftJoin('package_inventories as B','A.package_inventory_id','=','B.id')
        ->leftJoin('users as C','A.user_id','=','C.id')
        ->leftJoin('swadesh_huts as D','A.swadesh_hut_id','=','D.id')
        ->orderBy('B.prod_name','ASC')->get();
		//echo '<pre>';print_r($tasks);die;
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $columns = array('Product Name', 'Weight per packet', 'Weight unit', 'Stock Out Date', 'Stock Out Qty', 'Store', 'Available Quantity','Stock Out By', 'Bar Code');

        $callback = function() use($tasks, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($tasks as $task) {
                fputcsv($file, array($task->prod_name, $task->weight_per_packet, $task->weight_unit,
                date('Y-m-d',strtotime($task->stock_out_date)), $task->inv_out_qty, $task->swadesh_hut, $task->available_qty, $task->name,$task->barcode));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }



    public function datewiseReport(Request $request)

    {

        return view('admin.package_inventory.report');

    }







    public function datewiseExportCsv(Request $request)

    {

        $fileName = 'datewise-inventory-out'.date('Y-m-d').'.csv';

        $headers = array(

            "Content-type"        => "text/csv",

            "Content-Disposition" => "attachment; filename=$fileName",

            "Pragma"              => "no-cache",

            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",

            "Expires"             => "0"

        );

        $from    = Carbon::parse($request->start_date)

                 ->startOfDay()        // 2018-09-29 00:00:00.000000

                 ->toDateTimeString(); // 2018-09-29 00:00:00



        $to      = Carbon::parse($request->end_date)

                        ->endOfDay()          // 2018-09-29 23:59:59.000000

                        ->toDateTimeString(); // 2018-09-29 23:59:59



        $export_type= $request->export_type;



        if($export_type=='in')

        {

            if(Auth::user()->user_type=='Package')

            {

                $inventory_out_details  = PackageInventory::whereBetween('created_at', [$from, $to])->where('user_id',Auth::user()->id)->get();

            }

            else

            {

                $inventory_out_details  = PackageInventory::whereBetween('created_at', [$from, $to])->get();

            }



            $columns = array('Date', 'Product Name', 'Category', 'Sku', 'Package Location', 'Price', 'package size', 'Unit', 'In Quantity');

            $callback = function() use($inventory_out_details, $columns) {

            $file = fopen('php://output', 'w');

            fputcsv($file, $columns);

            foreach ($inventory_out_details as $inv_out)

            {

                if(isset($inv_out->packageLocation->location_name) && $inv_out->packageLocation->location_name!='')

                {

                    $location_name = $inv_out->packageLocation->location_name;

                }

                else

                {

                    $location_name = '';

                }



                if(isset($inv_out->category->name) && $inv_out->category->name!='')

                {

                    $category_name = $inv_out->category->name;

                }

                else

                {

                    $category_name = '';

                }

                fputcsv($file, array(date("jS F, Y", strtotime($inv_out->created_at)),$inv_out->prod_name,$category_name,$inv_out->sku,$location_name,number_format($inv_out->purchased_price,2),$inv_out->weight_per_packet,$inv_out->weight_unit,number_format($inv_out->total_weight,2)));

            }

            fclose($file);

            };

        }

        else

        {

            if(Auth::user()->user_type=='Package')

            {

                $inventory_out_details  = PackageInventoryOut::whereBetween('created_at', [$from, $to])->where('user_id',Auth::user()->id)->get();

            }

            else

            {

                $inventory_out_details  = PackageInventoryOut::whereBetween('created_at', [$from, $to])->get();

            }

            //$inventory_out_details  = PackageInventoryOut::whereBetween('created_at', [$from, $to])->get();

            $columns = array('Date', 'Product Name', 'Sku', 'Price', 'Package Info', 'Unit', 'Package Location', 'Delivered To', 'Delivered By', 'Out Quantity');

            $callback = function() use($inventory_out_details, $columns) {

            $file = fopen('php://output', 'w');

            fputcsv($file, $columns);

            foreach ($inventory_out_details as $inv_out)

            {

                $get_inventory_details = PackageInventory::where('id',$inv_out->package_inventory_id)->first();

                $get_locatrion_details = Package_location::where('id',$inv_out->package_location_id)->first();

                if(isset($get_locatrion_details->location_name) && $get_locatrion_details->location_name!='')

                {

                    $location_name = $get_locatrion_details->location_name;

                }

                else

                {

                    $location_name = '';

                }

                $swadesh_hut_details = DB::table('swadesh_huts')->where('id',$inv_out->swadesh_hut_id)->first();

                $user_details = User::where('id',$inv_out->user_id)->first();

                $get_product_details = Product::where('id',$inv_out->product_id)->first();

                fputcsv($file, array(date("jS F, Y", strtotime($inv_out->created_at)),$get_inventory_details->prod_name, $get_product_details->sku, $get_product_details->price, $get_product_details->weight_per_pkt, $get_product_details->weight_unit, $location_name, $swadesh_hut_details->location_name,$user_details->name,$inv_out->inv_out_qty));

            }

            fclose($file);

            };

        }



        return response()->stream($callback, 200, $headers);



    }





    public function inventoryStockCheck(Request $request)

    {

        $input = $request->all();

        $inv_out_qty = $input['inv_out_qty'];

        $get_inventory_details = PackageInventory::where('id',$request->package_inventory_id)->first();

        if($inv_out_qty>$get_inventory_details->available_qty)

        {

            $msg = $inv_out_qty.' Packet Not Available';

            $error = 1;

        }

        else

        {

            $msg = '';

            $error = 0;

        }

        return response()->json(array('msg'=>$msg,'error'=>$error));

    }



    public function downloadSampleCsv()

    {

        $fileName = 'sample-package-inventory-'.date('Y-m-d').'.csv';

	    $tasks = DB::table('package_inventories')->orderBy('id','ASC')->first();

		//echo '<pre>';print_r($tasks);die;

        $headers = array(

            "Content-type"        => "text/csv",

            "Content-Disposition" => "attachment; filename=$fileName",

            "Pragma"              => "no-cache",

            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",

            "Expires"             => "0"

        );



        $columns = array('Sku', 'Product Name', 'Similar Product Sku',  'Category Name', 'Total Weight','Purchased Price', 'MRP', 'CGST', 'SGST', 'IGST',  'Product Image', 'Description', 'Specification', 'weight per packet', 'Weight Unit', 'No of Packet','other image','hsn','Selling Price','Manufacturer Details','Marketed By','Country Of Origin','Customer Care Details','Seller','Bar Code','Wholesale Price','Wholesale Min Qty','Expiry Date');

//echo '<pre>';

//echo '<pre>'; print_r($tasks);die;

        $callback = function() use($tasks, $columns) {

            $file = fopen('php://output', 'w');

            fputcsv($file, $columns);

            fputcsv($file, array('1234567890QWERTY', 'Test Product', '1234567890QWERTY', 'Test Category', 500, 100, 120 , 9.25, 9.25, 9.25,'', 'Test Desc', 'Test Spec',5,'kilogram',100,'a.jpg,b.jpg,c.jpg','1234','130','Test Manufacturer Details','Test Marketed By','Test Country Of Origin','Test Customer Care Details','Test Seller','ABCpq123',105.20,100,'2021-08-08 12:07:58'));

            fclose($file);

        };  



        return response()->stream($callback, 200, $headers);

    }


    public function stockInProcess(Request $request)
    {
        $input = $request->all();

        $in_qty = $input['stock_in_qty'];
        $package_inventory_id = $input['package_increase_stock_inventory_id'];
        $package_location_id = $input['package_increase_stock_location_id'];



        $get_package_inventory_details = PackageInventory::find($package_inventory_id);

        $get_stock = $get_package_inventory_details->no_of_packet;
        $get_available_qty = $get_package_inventory_details->available_qty;

        $update_array = array('no_of_packet'=>($get_stock+$in_qty),'available_qty'=>($get_available_qty+$in_qty));
        $get_package_inventory_details->update($update_array);

        DB::table('stock_in_details')->insert(['package_inventory_id'=>$package_inventory_id,'in_quantity'=>$in_qty,'user_id'=>Auth::user()->id,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);

        $msg = '<p style="color:green;">Stock Increased</p>';        

        return response()->json(array('msg'=>$msg));
    }


    public function bulkStockOutProcess(Request $request)
    {
        $input = $request->all();
        //print_r($input);exit;
        $bulk_out_qty = $input['bulk_out_qty'];
        $bulk_out_id = $input['bulk_out_id'];
        $bulk_swadesh_hut_id = $input['bulk_swadesh_hut_id'];
        $bulk_location_id = $input['bulk_location_id'];

        /*$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }*/
        $outNumberDetails = DB::table('package_inventory_out')->where('voucher_no','<>', '')->distinct('voucher_no')->get();
        
        if(count($outNumberDetails)<10)
        {
            $vno=count($outNumberDetails)+1;
            $randomString = 'JSD-VOU-00'.$vno;
        }
        else if(count($outNumberDetails)>=10 && count($outNumberDetails)<100)
        {
            $vno=count($outNumberDetails)+1;
            $randomString = 'JSD-VOU-0'.$vno;
        }
        else 
        {
            $vno=count($outNumberDetails)+1;
            $randomString = 'JSD-VOU-'.$vno;
        }


        for($i=0;$i<count($bulk_out_qty);$i++)
        {
            $get_package_inventory_details = PackageInventory::find($bulk_out_id[$i]);
            $inv_out_qty = $bulk_out_qty[$i];
            if($inv_out_qty<=$get_package_inventory_details->available_qty)
            {
                //create product while inventory is being out
                $sku = $get_package_inventory_details->sku;
                $get_product_by_sku = Product::where('sku',$sku)->where('swadesh_hut_id',$bulk_swadesh_hut_id)->first();

                if($get_product_by_sku)
                {
                    $available_qty = $bulk_out_qty[$i]+$get_product_by_sku->available_qty;

                    $update_product_array = array('prod_name'=>$get_package_inventory_details->prod_name,'product_image'=>$get_package_inventory_details->product_image,'description'=>$get_package_inventory_details->description,'specification'=>$get_package_inventory_details->specification,
                    'sku'=>$sku,
                    'category_id'=>$get_package_inventory_details->category_id,'swadesh_hut_id'=>$get_product_by_sku->swadesh_hut_id,'weight_per_pkt'=>$get_package_inventory_details->weight_per_packet,'weight_unit'=>$get_package_inventory_details->weight_unit,'price'=>$get_package_inventory_details->selling_price,'cgst'=>$get_package_inventory_details->cgst,
                    'sgst'=>$get_package_inventory_details->sgst,
                    'igst'=>$get_package_inventory_details->igst,
                    'available_qty'=>$available_qty,'similar_product'=>$get_package_inventory_details->similar_product,'discount'=>$get_package_inventory_details->discount,'other_image'=>$get_package_inventory_details->other_image,'hsn'=>$get_package_inventory_details->hsn,'manufacturer_details'=>$get_package_inventory_details->manufacturer_details,'marketed_by'=>$get_package_inventory_details->marketed_by,'country_of_origin'=>$get_package_inventory_details->country_of_origin,'customer_care_details'=>$get_package_inventory_details->customer_care_details,'seller'=>$get_package_inventory_details->seller,'barcode'=>$get_package_inventory_details->barcode);

                    $get_product_by_sku->update($update_product_array);
                    $product_id = $get_product_by_sku->id;
                }
                else
                {
                    $create_product_array = array('prod_name'=>$get_package_inventory_details->prod_name,'product_image'=>$get_package_inventory_details->product_image,'description'=>$get_package_inventory_details->description,'specification'=>$get_package_inventory_details->specification,
                    'sku'=>$sku,
                    'category_id'=>$get_package_inventory_details->category_id,
                    'swadesh_hut_id'=>$bulk_swadesh_hut_id,'weight_per_pkt'=>$get_package_inventory_details->weight_per_packet,'weight_unit'=>$get_package_inventory_details->weight_unit,'price'=>$get_package_inventory_details->selling_price,'cgst'=>$get_package_inventory_details->cgst,
                    'sgst'=>$get_package_inventory_details->sgst,
                    'igst'=>$get_package_inventory_details->igst,
                    'available_qty'=>$bulk_out_qty[$i],'similar_product'=>$get_package_inventory_details->similar_product,'discount'=>$get_package_inventory_details->discount,'other_image'=>$get_package_inventory_details->other_image,'hsn'=>$get_package_inventory_details->hsn,'manufacturer_details'=>$get_package_inventory_details->manufacturer_details,'marketed_by'=>$get_package_inventory_details->marketed_by,'country_of_origin'=>$get_package_inventory_details->country_of_origin,'customer_care_details'=>$get_package_inventory_details->customer_care_details,'seller'=>$get_package_inventory_details->seller,'barcode'=>$get_package_inventory_details->barcode);

                    $product = Product::create($create_product_array);
                    $product_id = $product->id;
                }

                
                

                //create inventory out data with product id
                $inventory_out_array = array('package_inventory_id'=>$bulk_out_id[$i],'package_location_id'=>$bulk_location_id,'inv_out_qty'=>$bulk_out_qty[$i],'swadesh_hut_id'=>$bulk_swadesh_hut_id,'user_id'=>Auth::user()->id,'product_id'=>$product_id,'voucher_no'=>$randomString,'voucher_status'=>'inprocess');
                $inventory_out = PackageInventoryOut::create($inventory_out_array);

                //availableinventory quantity update
                $available_inventory = $get_package_inventory_details->available_qty-$bulk_out_qty[$i];
                $inventory_update_array = array('available_qty'=>$available_inventory);
                $get_package_inventory_details->update($inventory_update_array);
                $msg = '<div class="alert alert-success">Inventory Set Out Successfully</div>';
                $error = 0;

            }
        }
        return response()->json(array('msg'=>$msg));
    }

    public function vouchers(Request $request)
    {
        $records= DB::table('package_inventory_out as A')
        ->where('A.voucher_no','<>'.'')
        ->select('A.*','A.created_at as voucher_date','B.prod_name','B.product_image','B.weight_per_packet','B.weight_unit','C.location_name')
        ->leftJoin('package_inventories as B','A.id','=','B.package_inventory_id')
        ->leftJoin('swadesh_huts as C','A.swadesh_hut_id','=','C.id')
        ->get();

        return view('admin.package_inventory.voucher', compact('records'));
    }

    public function voucherChangeStatus(Request $request)
    {
        $input = $request->all();
        $voucher = $input['voucher'];
        $status_array = array('voucher_status'=>'complete');
        DB::table('package_inventory_out')->where('voucher_no',$voucher)->update($status_array);
        return response()->json(array('msg'=>'Voucher Status Changed To Complete!!'));
    }

    public function voucherShowDetails(Request $request)
    {
        $input = $request->all();
        $voucher = $input['voucher'];
        $out_details = DB::table('package_inventory_out as A')
        ->where('A.voucher_no',$voucher)
        ->select('A.*','B.prod_name','B.product_image','B.weight_per_packet','B.weight_unit','C.location_name','B.purchased_price')
        ->leftJoin('package_inventories as B','A.package_inventory_id','=','B.id')
        ->leftJoin('swadesh_huts as C','A.swadesh_hut_id','=','C.id')
        ->get();

        $tbl_html='<table class="table table-striped"><tr><th>Product name</th><th>Out Quantity</th><th>Price</td></th>';
        if(!empty($out_details))
        {
            $total_price=0;
            $single_price=0;
            foreach($out_details as $details)
            {
                $single_price=$details->inv_out_qty*$details->purchased_price;
                $total_price+=$single_price;
                $tbl_html.='<tr><td>'.$details->prod_name.'</td><td>'.$details->inv_out_qty.'</td><td>र'.number_format($single_price,2).'</td></tr>';
            }
        }
        $tbl_html.='<tr><td><strong>Total Price : </strong></td><td></td><td><strong>र'.number_format($total_price,2).'</strong></td></tr>';
        $tbl_html.='</table>';
        return response()->json(array('html'=>$tbl_html));
    }

    public function stockInLog()
    {
        //echo "okkk";exit;
        $fileName = 'package-inventory-in'.date('Y-m-d').'.csv';
	    $tasks = DB::table('stock_in_details')->orderBy('id','ASC')->get();
		//echo '<pre>';print_r($tasks);die;
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Product Name', 'Weight per packet', 'Weight unit', 'Total Stock', 'Stock In Date', 'Stock In Qty','Bar Code');

        $callback = function() use($tasks, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($tasks as $task) {
                //print_r($task);
                $get_inventory_details = packageInventory::where('id',$task->package_inventory_id)->first();
                $get_category_details = Category::where('id',$get_inventory_details->category_id)->first();
                

                fputcsv($file, array($get_inventory_details->prod_name, $get_inventory_details->weight_per_packet, $get_inventory_details->weight_unit, $get_inventory_details->available_qty, date("j F Y", strtotime($task->created_at)), $task->in_quantity, $get_inventory_details->barcode));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function inventoryInLog(){
        $result = DB::table('stock_in_details as A')
        ->select('A.*','A.created_at as stock_in_date','B.*','C.*')
        ->leftJoin('package_inventories as B','A.package_inventory_id','=','B.id')
        ->leftJoin('users as C','A.user_id','=','C.id')
        ->orderBy('B.prod_name','ASC')->get();
        return view('admin.package_inventory.inventory_in', compact('result'));
    }

    public function inventoryOutLog(){
        $result = DB::table('package_inventory_out as A')
        ->select('A.*','A.created_at as stock_out_date','B.*','C.*','D.location_name as swadesh_hut')
        ->leftJoin('package_inventories as B','A.package_inventory_id','=','B.id')
        ->leftJoin('users as C','A.user_id','=','C.id')
        ->leftJoin('swadesh_huts as D','A.swadesh_hut_id','=','D.id')
        ->orderBy('B.prod_name','ASC')->get();
        return view('admin.package_inventory.inventory_out', compact('result'));
    }

    public function printVoucherDetails($voucher_no)
    {
        
        $out_details = DB::table('package_inventory_out as A')
        ->where('A.voucher_no',$voucher_no)
        ->select('A.*','B.prod_name','B.product_image','B.weight_per_packet','B.weight_unit','C.*','B.purchased_price','A.created_at as voucher_date')
        ->leftJoin('package_inventories as B','A.package_inventory_id','=','B.id')
        ->leftJoin('swadesh_huts as C','A.swadesh_hut_id','=','C.id')
        ->get();

        $tbl_html='<table class="table table-striped">
        <tr>
        <th>Product Name</th>
        <th>Weight</th>
        <th>Out Quantity</th>
        <th>Store</th>
        <th>Price</th>
        </tr>';
        if(!empty($out_details))
        {
            $total_price=0;
            $single_price=0;
            foreach($out_details as $details)
            {
                $voucher_date = $details->voucher_date;
                $swadesh_hut_address = $details->address;
                $single_price=$details->inv_out_qty*$details->purchased_price;
                $total_price+=$single_price;
                $tbl_html.='<tr><td>'.$details->prod_name.'</td><td>'.$details->weight_per_packet.''.$details->weight_unit.'</td><td>'.$details->inv_out_qty.'</td><td>'.$details->location_name.'</td><td>र'.number_format($single_price,2).'</td></tr>';
            }
        }
        $tbl_html.='<tr><td></td><td></td><td></td><td><strong>Total Price : </strong></td><td><strong>र'.number_format($total_price,2).'</strong></td></tr>';
        $tbl_html.='</table>';
        return view('admin.package_inventory.print_voucher', compact('tbl_html','swadesh_hut_address','voucher_no','voucher_date'));
    }

    public function inventorySearch(Request $request)
    {
        $table_html = '';
        $pagination_html = '';
        $search_value = $request->search_value;
        $products = PackageInventory::where('prod_name', 'LIKE', '%'. $search_value. '%')->orWhere('barcode', 'LIKE', '%'. $search_value. '%')->orderBy('created_at', 'DESC')->paginate(50);

        $total_products = PackageInventory::where('prod_name', 'LIKE', '%'. $search_value. '%')->orWhere('barcode', 'LIKE', '%'. $search_value. '%')->get();
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
                $get_location_details = DB::table('package_locations')->where('id',$inv->package_location_id)->first();
                if(isset($get_location_details->location_name) && $get_location_details->location_name!=''){
                    $lname = $get_location_details->location_name;
                }else {
                    $lname = 'N/A';
                }

                $total_out_qty=0;
                $get_package_inventory_out_details = Helper::get_package_inventory_out_details($inv->id);
                if(count($get_package_inventory_out_details)>0)
                {
                    foreach($get_package_inventory_out_details as $pidetails)
                    {
                        $total_out_qty+=$pidetails->inv_out_qty;
                    }
                }
                $available_qty = $inv->available_qty; 

                $table_html.='<tr><td>'.$inv->prod_name.'</td><td>'.$cname.'</td><td>'.$lname.'</td><td>'.$inv->mrp.'</td><td>'.$inv->selling_price.'</td><td><img src="'.asset('/storage/'.$inv->product_image).'" style="height:60px;" /></td><td>'.$inv->weight_per_packet .' '. $inv->weight_unit.'</td><td><table><tr><td><strong>Out Quantity</strong></td><td><strong>Available Quantity</strong></td></tr><tr><td id="tbl_out_qty'.$inv->id.'">'.$total_out_qty.'</td><td id="tbl_avl_qty'.$inv->id.'"><input type="hidden" id="pavlqty'.$inv->id.'" value="'.$available_qty.'">'.$available_qty.'</td></tr></table></td><td class="text-center"><a href="javascript:void(0)" id="inv_in_out_button" data-toggle="modal" data-target="#myModal" class="btn btn-xs btn-warning" onclick="set_package_inventory(\''.$inv->id.'\',\''.$inv->package_location_id.'\',\''.$inv->weight_unit.'\',\''.$inv->weight_per_packet.'\')">Set Inventory Out</a><button class="btn btn-xs btn-info"  data-toggle="modal" data-target="#increaseStockModal" onclick="increase_stock(\''.$inv->id.'\',\''.$inv->package_location_id.'\',\''.$inv->weight_unit.'\',\''.$inv->weight_per_packet.'\')">Increase Stock</button></td><td class="text-center">'.date('d-m-Y',strtotime($inv->created_at)).'</td><td class="text-center"><a class="btn btn-primary" href="'.route('package_inventory.edit',$inv->id).'" target="_blank" title="Edit"><i class="fas fa-edit"></i></a></td></tr>';
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
        $products = PackageInventory::where('prod_name', 'LIKE', '%'. $search_value. '%')->orWhere('barcode', 'LIKE', '%'. $search_value. '%')->orderBy('created_at', 'DESC')->skip(($page_no-1)*50)->take(50)->get();

        $total_products = PackageInventory::where('prod_name', 'LIKE', '%'. $search_value. '%')->orWhere('barcode', 'LIKE', '%'. $search_value. '%')->get();
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
                $get_location_details = DB::table('package_locations')->where('id',$inv->package_location_id)->first();
                if(isset($get_location_details->location_name) && $get_location_details->location_name!=''){
                    $lname = $get_location_details->location_name;
                }else {
                    $lname = 'N/A';
                }

                $total_out_qty=0;
                $get_package_inventory_out_details = Helper::get_package_inventory_out_details($inv->id);
                if(count($get_package_inventory_out_details)>0)
                {
                    foreach($get_package_inventory_out_details as $pidetails)
                    {
                        $total_out_qty+=$pidetails->inv_out_qty;
                    }
                }
                $available_qty = $inv->available_qty; 

                $table_html.='<tr><td>'.$inv->prod_name.'</td><td>'.$cname.'</td><td>'.$lname.'</td><td>'.$inv->mrp.'</td><td>'.$inv->selling_price.'</td><td><img src="'.asset('/storage/'.$inv->product_image).'" style="height:60px;" /></td><td>'.$inv->weight_per_packet .' '. $inv->weight_unit.'</td><td><table><tr><td><strong>Out Quantity</strong></td><td><strong>Available Quantity</strong></td></tr><tr><td id="tbl_out_qty'.$inv->id.'">'.$total_out_qty.'</td><td id="tbl_avl_qty'.$inv->id.'"><input type="hidden" id="pavlqty'.$inv->id.'" value="'.$available_qty.'">'.$available_qty.'</td></tr></table></td><td class="text-center"><a href="javascript:void(0)" id="inv_in_out_button" data-toggle="modal" data-target="#myModal" class="btn btn-xs btn-warning" onclick="set_package_inventory(\''.$inv->id.'\',\''.$inv->package_location_id.'\',\''.$inv->weight_unit.'\',\''.$inv->weight_per_packet.'\')">Set Inventory Out</a><button class="btn btn-xs btn-info"  data-toggle="modal" data-target="#increaseStockModal" onclick="increase_stock(\''.$inv->id.'\',\''.$inv->package_location_id.'\',\''.$inv->weight_unit.'\',\''.$inv->weight_per_packet.'\')">Increase Stock</button></td><td class="text-center">'.date('d-m-Y',strtotime($inv->created_at)).'</td><td class="text-center"><a class="btn btn-primary" href="'.route('package_inventory.edit',$inv->id).'"  target="_blank" title="Edit"><i class="fas fa-edit"></i></a></td></tr>';
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

}

