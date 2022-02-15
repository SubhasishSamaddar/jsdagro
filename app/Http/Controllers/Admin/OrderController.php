<?php



namespace App\Http\Controllers\Admin;



use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

//use App\Order_master;

use DB;

use Auth;



use Illuminate\Support\Carbon;



class OrderController extends Controller

{

	function __construct(){

		/*$this->middleware('permission:product-list');

		//$this->middleware('permission:product-create', ['only' => ['create','store']]);

		$this->middleware('permission:order-edit', ['only' => ['edit','update']]);

		$this->middleware('permission:order-show', ['only' => ['show']]);

		//$this->middleware('permission:product-delete', ['only' => ['destroy']]);*/

    }



    public function index(){

		$user = Auth::user();

		//print_r($user);die;  

        if($user->user_type=='Customer' || $user->user_type=='Admin' )

        {

            // $records=DB::table('order_master')

            // ->select('users.name as customer_name','swadesh_huts.location_name as hut_name','order_master.*')

            // ->join('users','order_master.user_id','users.id')

            // ->join('swadesh_huts','order_master.swadesh_hut_id','swadesh_huts.id')->get();



            $records=DB::table('order_master')

            ->select('swadeshhut_pos.name as customer_name','swadesh_huts.location_name as hut_name','order_master.*')

           

            ->join('swadeshhut_pos','order_master.swadesh_hut_id','swadeshhut_pos.swadeshhut_id')

            ->join('swadesh_huts','order_master.swadesh_hut_id','swadesh_huts.id')->get();

        }

        else if($user->user_type=='Swadesh_Hut')

        {

            $get_swadesh_hut_user_details = DB::table('swadesh_hut_users')->where('user_id',$user->id)->first();

            

          /*  $records=DB::table('order_master')

            ->select('users.name as customer_name','swadesh_huts.location_name as hut_name','order_master.*')

            ->where('order_master.swadesh_hut_id',$get_swadesh_hut_user_details->swadesh_hut_id)

            ->join('users','order_master.user_id','users.id')

            ->join('swadesh_huts','order_master.swadesh_hut_id','swadesh_huts.id')->get(); */

            

             $records=DB::table('order_master')

            ->select('swadeshhut_pos.name as customer_name','swadesh_huts.location_name as hut_name','order_master.*')

            ->where('order_master.swadesh_hut_id',$get_swadesh_hut_user_details->swadesh_hut_id)

            ->join('swadeshhut_pos','order_master.swadesh_hut_id','swadeshhut_pos.swadeshhut_id')

            ->join('swadesh_huts','order_master.swadesh_hut_id','swadesh_huts.id')->get();



        }

		 //$records=Order_master::all();   

		//echo '<pre>';print_r($records);die;

		return view('admin.order.index', compact('records'));

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        //

    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)

    {

        //

    }



    public function show($id){

        

      	$user = Auth::user();

		//print_r($user);die;  

        if($user->user_type=='Customer' || $user->user_type=='Admin' )

        { 

        /*  $details=DB::table('order_master')

		->select('users.name as customer_name','swadesh_huts.location_name as hut_name','order_master.*')

		->join('users','order_master.user_id','users.id')

		->join('swadesh_huts','order_master.swadesh_hut_id','swadesh_huts.id')

		->where( 'order_master.id', $id )->first();  */

		

		 $details=DB::table('order_master')

		->select('swadeshhut_pos.name as customer_name','swadesh_huts.location_name as hut_name','order_master.*')

		->join('swadeshhut_pos','order_master.swadesh_hut_id','swadeshhut_pos.swadeshhut_id')

		->join('swadesh_huts','order_master.swadesh_hut_id','swadesh_huts.id')

		->where( 'order_master.id', $id )->first();

            

        }

        else if($user->user_type=='Swadesh_Hut')

        {

          $details=DB::table('order_master')

		->select('swadeshhut_pos.name as customer_name','swadesh_huts.location_name as hut_name','order_master.*')

		->join('swadeshhut_pos','order_master.swadesh_hut_id','swadeshhut_pos.swadeshhut_id')

		->join('swadesh_huts','order_master.swadesh_hut_id','swadesh_huts.id')

		->where( 'order_master.id', $id )->first();  

            

        }

        

        

        

        

        

	

		$orderDetails=DB::table('order_details')

		->select('products.prod_name as product_namee','products.id as pid','order_details.*')

		->leftjoin('products','order_details.product_id','products.id')

		->where( 'order_details.order_id', $id )->get();

		

		return view('admin.order.view',compact('details', 'orderDetails'));

    }



    public function edit($id){

        $details=DB::table('order_master')->where( 'id', $id )->first();

        $hutDetails=DB::table('swadesh_huts')->where( 'status', 'Active' )->get();

        $userDetails=DB::table('users')->where( 'active', '1' )->get();



		$orderDetails=DB::table('order_details')

		->select('products.prod_name as product_namee','order_details.*')

		->join('products','order_details.product_id','products.id')

		->where( 'order_id', $id )->get();





		//echo '<pre>';print_r($orderDetails);die;

        return view('admin.order.edit',compact('details', 'hutDetails', 'userDetails', 'orderDetails'));

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, $id){



		$data['order_status']=$request['order_status'];

		$data['payment_mode']=$request['payment_mode'];

		$data['payment_status']=$request['payment_status'];

		$data['updated_at']=date("Y-m-d H:i:s");

		//echo '<pre>';

		if( $request['order_status']=='Delivered' && $request['payment_status']=='Paid' ):

			$orderDetails=DB::table('order_details')

							->where( 'order_id', $id )->get();

			if( !empty($orderDetails) ):

				foreach( $orderDetails as $orderData ):

					$productDetails=DB::table('products')

							->where( 'id', $orderData->product_id )->first();

					if( !empty($productDetails) ):

						$orderDetailsParams['ordered_qty']=$productDetails->ordered_qty+$orderData->quantity;

						DB::table('products')->where( 'id', $orderData->product_id )->update($orderDetailsParams);

					endif;

					//ordered_qty 

					//echo 'Product : '.$orderData->product_id.' Quantity : '.$orderData->quantity;

				endforeach;

			endif;

		endif;

		//print_r($data);die;

		DB::table('order_master')->where( 'id', $id )->update($data);

		return redirect()->route('order.index')

                        ->with('success','Swadesh Hut updated successfully');

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function destroy($id)

    {

        //

    }

    

    public function datewiseOrderreport(Request $request){

        $user = Auth::user();

        //print_r($user);die;  

         $from = Carbon::parse($request->start_date)

                 ->startOfDay()        // 2018-09-29 00:00:00.000000

                 ->toDateTimeString(); // 2018-09-29 00:00:00



        $to = Carbon::parse($request->end_date)

                        ->endOfDay()          // 2018-09-29 23:59:59.000000

                        ->toDateTimeString(); // 2018-09-29 23:59:59



        $start_date = $request->start_date;

        $end_date = $request->end_date;



        if($user->user_type=='Customer' || $user->user_type=='Admin' )

        {



            $records=DB::table('order_master')

            ->select('swadeshhut_pos.name as customer_name','swadesh_huts.location_name as hut_name','order_master.*')

            ->whereBetween('order_date', [$from, $to])

            ->join('swadeshhut_pos','order_master.swadesh_hut_id','swadeshhut_pos.swadeshhut_id')

            ->join('swadesh_huts','order_master.swadesh_hut_id','swadesh_huts.id')->get();

        }

        else if($user->user_type=='Swadesh_Hut')

        {

            $get_swadesh_hut_user_details = DB::table('swadesh_hut_users')->where('user_id',$user->id)->first(); 

            

             $records=DB::table('order_master')

            ->select('swadeshhut_pos.name as customer_name','swadesh_huts.location_name as hut_name','order_master.*')

            ->where('order_master.swadesh_hut_id',$get_swadesh_hut_user_details->swadesh_hut_id)

            ->whereBetween('order_date', [$from, $to])

            ->join('swadeshhut_pos','order_master.swadesh_hut_id','swadeshhut_pos.swadeshhut_id')

            ->join('swadesh_huts','order_master.swadesh_hut_id','swadesh_huts.id')->get();



        }

         //$records=Order_master::all();   

        //echo '<pre>';print_r($records);die;

        return view('admin.order.index', compact('records','start_date','end_date'));

    }


    public function datewiseOrderExcelDownload(Request $request){
        $user = Auth::user();

        $from = Carbon::parse($request->start_date)

        ->startOfDay()        // 2018-09-29 00:00:00.000000

        ->toDateTimeString(); // 2018-09-29 00:00:00



        $to = Carbon::parse($request->end_date)

                        ->endOfDay()          // 2018-09-29 23:59:59.000000

                        ->toDateTimeString(); // 2018-09-29 23:59:59



        $start_date = $request->start_date;

        $end_date = $request->end_date;



        if($user->user_type=='Customer' || $user->user_type=='Admin' )

        {



            $records=DB::table('order_master')

            ->select('swadeshhut_pos.name as customer_name','swadesh_huts.location_name as hut_name','order_master.*')

            ->whereBetween('order_date', [$from, $to])

            ->join('swadeshhut_pos','order_master.swadesh_hut_id','swadeshhut_pos.swadeshhut_id')

            ->join('swadesh_huts','order_master.swadesh_hut_id','swadesh_huts.id')->get();

        }

        else if($user->user_type=='Swadesh_Hut')

        {

            $get_swadesh_hut_user_details = DB::table('swadesh_hut_users')->where('user_id',$user->id)->first(); 

            

             $records=DB::table('order_master')

            ->select('swadeshhut_pos.name as customer_name','swadesh_huts.location_name as hut_name','order_master.*')

            ->where('order_master.swadesh_hut_id',$get_swadesh_hut_user_details->swadesh_hut_id)

            ->whereBetween('order_date', [$from, $to])

            ->join('swadeshhut_pos','order_master.swadesh_hut_id','swadeshhut_pos.swadeshhut_id')

            ->join('swadesh_huts','order_master.swadesh_hut_id','swadesh_huts.id')->get();



        }

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=Order-Report-".date('Y-m-d')."csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Order Number', 'Name', 'Amount', 'Swadesh Hut Name', 'Order Date');

        $callback = function() use($records, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($records as $details) {
                fputcsv($file, array($details->order_number, $details->billing_name, $details->total_amount, $details->hut_name, date('Y-m-d',strtotime($details->order_date))));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);

    }

}

