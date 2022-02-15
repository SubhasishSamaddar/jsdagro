<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PackageInventory;
use App\Category;
use App\Product;
use App\Package_location;
use App\PackageInventoryOut;
use App\User;
use Helper;
use AUth;
use DB;
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
        $records=PackageInventory::all()->where('user_id',Auth::user()->id);
        $swadesh_huts = DB::table('swadesh_huts')->get();
		return view('admin.package_inventory.index', compact('records','swadesh_huts'));
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

        return view('admin.package_inventory.create',compact('categories','package_location'));
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
            'product_image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'required',
            'specification' => 'required',
        ]);
        $input = $request->all();
        $input['product_image'] = 'package_inventory/package_inventory.jpg';
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
    public function show($id)
    {

		$inventory = PackageInventory::find($id);
        return view('admin.package_inventory.show',compact('inventory'));

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
            'no_of_packet' => 'required',
            'total_weight' => 'required',
            'product_image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'required',
            'specification' => 'required',
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


        if($inv_out_qty<$get_package_inventory_details->available_qty)
        {
            //create product while inventory is being out
            $sku = $get_package_inventory_details->sku;
            $get_product_by_sku = Product::where('sku',$sku)->where('swadesh_hut_id',$swadesh_hut_id)->first();


            if($get_product_by_sku)
            {
                $available_qty = $inv_out_qty+$get_product_by_sku->available_qty;
                $update_product_array = array('prod_name'=>$get_package_inventory_details->prod_name,'sku'=>$sku,'category_id'=>$get_package_inventory_details->category_id,'swadesh_hut_id'=>$swadesh_hut_id,'weight_per_pkt'=>$get_package_inventory_details->weight_per_packet,'weight_unit'=>$get_package_inventory_details->weight_unit,'price'=>$get_package_inventory_details->selling_price,'cgst'=>$get_package_inventory_details->cgst,'sgst'=>$get_package_inventory_details->sgst,'igst'=>$get_package_inventory_details->igst,'available_qty'=>$available_qty);
                $get_product_by_sku->update($update_product_array);
                $product_id = $get_product_by_sku->id;

            }
            else
            {
                $create_product_array = array('prod_name'=>$get_package_inventory_details->prod_name,'sku'=>$sku,'category_id'=>$get_package_inventory_details->category_id,'swadesh_hut_id'=>$swadesh_hut_id,'weight_per_pkt'=>$get_package_inventory_details->weight_per_packet,'weight_unit'=>$get_package_inventory_details->weight_unit,'price'=>$get_package_inventory_details->selling_price,'cgst'=>$get_package_inventory_details->cgst,'sgst'=>$get_package_inventory_details->sgst,'igst'=>$get_package_inventory_details->igst,'available_qty'=>$inv_out_qty);
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
        }
        else
        {
            $msg = '<div class="alert alert-danger">'.$inv_out_qty.' kilogram Quantity not available in the Inventory</div>';
            $error = 1;
        }

        return response()->json(array('msg'=>$msg,'error'=>$error));
    }

	public function packageInventoryImport(Request $request){
		
							
		$package_location = Package_location::select('id','location_name')
                            ->where('status','Active')
                            ->orderBy('location_name','ASC')->get();

		if(isset($_POST['_token'])){
			
			$request->validate([
                'inventory_file' => 'required|max:5|mimes:csv,txt'
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
				echo '<pre>';print_r($filedata);
				if( $i!=0 ){
					
					die;
					$data['prod_name']=($filedata[0])?$filedata[0]:"";
					$selectCategory = Category::select('id','name')
									->where('name', 'like', '%'.$filedata[1].'%')
									->first();
					if( !empty($selectCategory) ):
						$filedata[1]=$selectCategory->id;
					else:
						$input['name'] = $filedata[1];
						$input['category_image'] = 'category/category.png';
						$input['parent_id'] = '0';  
						$input['position_order'] = '1';
						$input['description'] = '';
						$input['status']= 'Active';
						$input['created_at']= date('Y-m-d H:i:s');
						$categoryId = Category::create($input);
						$filedata[1]=$categoryId->id;
					endif;
					$data['category_id']=$filedata[1];
					//$data['weight_per_packet']=($filedata[2])?$filedata[2]:1;
					$data['purchased_price']=($filedata[3])?$filedata[3]:'0.00';
					$data['selling_price']=($filedata[4])?$filedata[4]:'0.00';
					$data['cgst']=($filedata[5])?$filedata[5]:'0.00';
					$data['sgst']=($filedata[6])?$filedata[6]:'0.00';
					$data['igst']=($filedata[7])?$filedata[7]:'0.00';
					$data['product_image']=($filedata[8])?$filedata[8]:'product/product.png';
					$data['description']=($filedata[9])?$filedata[9]:'';
                    $data['specification']=($filedata[10])?$filedata[10]:'';
                    $data['weight_per_packet']=($filedata[11])?$filedata[11]:1;
					$data['weight_unit']=($filedata[12])?$filedata[12]:'kilogram';
					$data['inventory_in_out']='In';
					$data['user_id']=Auth::user()->id;
					$data['package_location_id']=$input['package_location_id']=Auth::user()->package_location_id;
					$data['total_weight']=($filedata[11]*$filedata[13]);
                    $data['created_at']=date('Y-m-d H:m:s');
					if($filedata[14]!=''){
                        $data['sku'] = $filedata[14];
                    }else{
                        $data['sku']=Helper::rand_string(6);
                    }
					$find_inventory_by_sku = PackageInventory::where('sku',$data['sku'])->first();

                    if($find_inventory_by_sku)
                    {
                        $data['available_qty']=$filedata[13]+$find_inventory_by_sku->available_qty;
                        $data['no_of_packet']=$filedata[13]+$find_inventory_by_sku->no_of_packet;
                        $find_inventory_by_sku->update($data);
                    }
                    else
                    {
                        $data['available_qty']=($filedata[13]);
                        $data['no_of_packet']=($filedata[13])?$filedata[13]:1;
                        $product = PackageInventory::create($data);
                    }
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
	$tasks = DB::table('package_inventories')
            ->orderBy('id','ASC')->get();
		//echo '<pre>';print_r($tasks);die;
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Product Name', 'Weight per packet', 'Weight unit', 'No of Packet', 'Category', 'Total Stock', 'Stock Out Details', 'Available Quantity','Purchased Price', 'Selling Price', 'CGST', 'SGST', 'IGST',  'Description', 'Specification');
//echo '<pre>';
//echo '<pre>'; print_r($tasks);die;
        $callback = function() use($tasks, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tasks as $task) {
                //print_r($task);
                $get_category_details = Category::where('id',$task->category_id)->first();

                $stock_out_details='';

                $get_package_inventory_out_details = Helper::get_package_inventory_out_details($task->id);
                if(count($get_package_inventory_out_details)>0)
                {
                    foreach($get_package_inventory_out_details as $pidetails)
                    {
                        $stock_out_details.='Out Date:'.$pidetails->created_at.' Out Qty:'.$pidetails->inv_out_qty.' '.$task->weight_unit.'   ';
                    }
                }

                fputcsv($file, array($task->prod_name, $task->weight_per_packet, $task->weight_unit, $task->no_of_packet, $get_category_details->name, $task->no_of_packet, $stock_out_details, $task->available_qty, $task->purchased_price, $task->selling_price, $task->cgst, $task->sgst, $task->igst, $task->description, $task->specification));
            }//die;

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

        $columns = array('Product Name',  'Category Name', 'Total Weight','Purchased Price', 'Selling Price', 'CGST', 'SGST', 'IGST',  'Product Image', 'Description', 'Specification', 'weight per packet', 'Weight Unit', 'No of Packet', 'Sku');
//echo '<pre>';
//echo '<pre>'; print_r($tasks);die;
        $callback = function() use($tasks, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, array('Test Product', 'Test Category', 500, 100, 120 , 9.25, 9.25, 9.25,'', 'Test Desc', 'Test Spec',5,'kilogram',100,'1234567890QWERTY'));
            fclose($file);
        };  

        return response()->stream($callback, 200, $headers);
    }

}
