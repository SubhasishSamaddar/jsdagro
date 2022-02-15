<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class Swadesh_hutsController extends Controller
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
         $this->middleware('permission:swadesh_hut-list');
         $this->middleware('permission:swadesh_hut-create', ['only' => ['create','store']]);
         $this->middleware('permission:swadesh_hut-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:swadesh_hut-delete', ['only' => ['destroy']]);
    }
	
    public function index()
    {
        $records=DB::table('swadesh_huts')->get();
		return view('admin.swadesh_huts.index',compact('records'));	
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.swadesh_huts.create');
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
            'location_name' => 'required|max:150|unique:swadesh_huts',
            'cover_area_pincodes' => 'required',
            'invoice_prefix' => 'required',
        ]);  
		$input = $request->all();
		if( $request['status'] ):
			$data['status']='Active';
		endif;
		$data['location_name']=$request['location_name'];
		$data['cover_area_pincodes']=$request['cover_area_pincodes'];
        $data['address']=$request['address'];
		$data['created_at']=date("Y-m-d H:i:s");
        $data['close_time']=$request['close_time'];
        $data['invoice_prefix']=$request['invoice_prefix'];
		DB::table('swadesh_huts')->insert($data);
		//$product = Product::create($input);  
        return redirect()->route('stores.index')
                        ->with('success','Swadesh Huts created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detailsRecord=DB::table('swadesh_huts')->where( 'id', $id )->first();
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
        $details=DB::table('swadesh_huts')->where( 'id', $id )->first();
        return view('admin.swadesh_huts.edit',compact('details'));
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
            'location_name' => 'required|max:150|unique:swadesh_huts,location_name,'.$id.',id',
            'cover_area_pincodes' => 'required',
            'invoice_prefix' => 'required',
        ]);
		$input = $request->all();
		if( $request['status'] ):
			$data['status']='Active';
		else:
			$data['status']='Inactive';
		endif;
		$data['location_name']=$request['location_name'];
		$data['cover_area_pincodes']=$request['cover_area_pincodes'];
        $data['address']=$request['address'];
		$data['updated_at']=date("Y-m-d H:i:s");
        $data['close_time']=$request['close_time'];
        $data['invoice_prefix']=$request['invoice_prefix'];
		//print_r($data);die;  
		DB::table('swadesh_huts')->where( 'id', $id )->update($data);
		return redirect()->route('stores.index')
                        ->with('success','Stores updated successfully');	
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('swadesh_huts')->where( 'id', $id )->delete();
		return redirect()->route('swadesh_hut.index')
                        ->with('success','Swadesh Hut deleted successfully');
    }
	
	public function changestatus(Request $request)
    {
		//echo $request->id;die;
		//echo '<pre>'; print_r($request);die;
		$data['updated_at']=date("Y-m-d H:i:s");
		$data['status']=$request->status;
		//print_r($data);die;  
		DB::table('swadesh_huts')->where( 'id', $request->id )->update($data);
		return response()->json(['success'=>'Status change successfully.']);

    }
	
}
