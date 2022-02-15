<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;

use DB;

class CompanyPayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	 
	/*
	public function __construct(){
        $this->middleware('auth');
    }
	*/
	
	function __construct(){
    }
	
    public function index()
    {
        $records=DB::table('company_payout')->get();
		return view('admin.company_payout.index',compact('records'));	
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.company_payout.create');
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
		$this->validate($request, [
            'company_name' => 'required|max:150',
            'payout_amount' => 'required'
        ]);  
		$input = $request->all();
        $data['status']=$input['status'];
		$data['company_name']=$request['company_name'];
		$data['payout_amount']=$request['payout_amount'];
        $data['created_at']=date('Y-m-d H::s');
		DB::table('company_payout')->insert($data);
		//$product = Product::create($input);  
        return redirect()->route('company-payouts.index')
                        ->with('success','Payout created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detailsRecord=DB::table('company_payout')->where( 'id', $id )->first();
		//$country = Country::find($id);
        return view('admin.swadesh_huts.show',compact('detailsRecord'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $details=DB::table('company_payout')->where( 'id', $id )->first();
        return view('admin.company_payout.edit',compact('details'));
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
            'company_name' => 'required|max:150',
            'payout_amount' => 'required'
        ]);  
		$data['status']=$request['status'];
		$data['company_name']=$request['company_name'];
		$data['payout_amount']=$request['payout_amount'];
        $data['updated_at']=date('Y-m-d H::s');
		//print_r($data);die;  
		DB::table('company_payout')->where( 'id', $id )->update($data);
		return redirect()->route('company-payouts.index')
                        ->with('success','Payout updated successfully');	
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('company_payout')->where( 'id', $id )->delete();
		return redirect()->route('company-payouts.index')
                        ->with('success','Payout Details deleted successfully');
    }

    public function getLog(Request $request)
    {
       $from = Carbon::parse($request->start_date)->startOfDay()->toDateTimeString();
       $to = Carbon::parse($request->end_date)->endOfDay()->toDateTimeString();

        $pay_out_details = DB::table('company_payout')->whereBetween('created_at', [$from, $to])->where('status',$request->type)->get();

        $fileName = 'company-payout'.date('Y-m-d').'.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"

        );

        $columns = array('Date', 'Company Name', 'Payout Amount', 'Status');

        $callback = function() use($pay_out_details, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($pay_out_details as $pay_out)
            {
                fputcsv($file, array(date("jS F, Y", strtotime($pay_out->created_at)),$pay_out->company_name,$pay_out->payout_amount,$pay_out->status));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
	
}
