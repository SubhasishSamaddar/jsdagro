<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;

class Swadesh_hut_posController extends Controller
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
	
	
    public function index()
    {
        if(Auth::user()->user_type=='Swadesh_Hut')
        {
            $user_id = Auth::user()->id;
            $swadeshhut_id = DB::table('swadesh_hut_users')->where('user_id',$user_id)->first();
            //dd($swadeshhut_id->swadesh_hut_id);
            
        $records=DB::table('swadeshhut_pos')->where('swadeshhut_id',$swadeshhut_id->swadesh_hut_id)->get();
        }else{
         $records=DB::table('swadeshhut_pos')->get();   
        }
		return view('admin.swadeshhut_pos.index',compact('records'));	
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $records=DB::table('swadesh_huts')->get();
        return view('admin.swadeshhut_pos.create',compact('records'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        if(Auth::user()->user_type=='Swadesh_Hut')
        {
            $this->validate($request, [
            'name' => 'required|max:150',
            'userName' => 'required',
            'password' => 'required'
        ]);
        
        $input = $request->all();
		if( $request['status'] ):
			$data['status']='Active';
		endif;
		$data['name']=$request['name'];
		$data['userName']=$request['userName'];
        $data['password']=$request['password'];
		$data['created_at']=date("Y-m-d H:i:s");
        $data['updated_at']=date("Y-m-d H:i:s");
        $get_swadesh_hut_user = DB::table('swadesh_hut_users')->where('user_id',Auth::user()->id)->first();
        $data['swadeshhut_id']=$get_swadesh_hut_user->swadesh_hut_id;
        //$data['swadeshhut_id']=$request['swadeshhut_id'];
            
        }else{
		$this->validate($request, [
            'name' => 'required|max:150',
            'userName' => 'required',
            'password' => 'required',
            'swadeshhut_id' => 'required'
        ]); 
        
        $input = $request->all();
		if( $request['status'] ):
			$data['status']='Active';
		endif;
		$data['name']=$request['name'];
		$data['userName']=$request['userName'];
        $data['password']=$request['password'];
		$data['created_at']=date("Y-m-d H:i:s");
        $data['updated_at']=date("Y-m-d H:i:s");
        //$get_swadesh_hut_user = DB::table('swadesh_hut_users')->where('user_id',Auth::user()->id)->first();
        //$data['swadeshhut_id']=$get_swadesh_hut_user->swadesh_hut_id;
        $data['swadeshhut_id']=$request['swadeshhut_id'];
        
        }
		
        
		DB::table('swadeshhut_pos')->insert($data);
		//$product = Product::create($input);  
        return redirect()->route('swadesh_hut_pos.index')
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
        $detailsRecord=DB::table('swadeshhut_pos')->where( 'id', $id )->first();
		//$country = Country::find($id);
        return view('admin.swadeshhut_pos.show',compact('detailsRecord'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $details=DB::table('swadeshhut_pos')->where( 'id', $id )->first();
        $records=DB::table('swadesh_huts')->get();
        return view('admin.swadeshhut_pos.edit',compact('details','records'));
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
        if(Auth::user()->user_type=='Swadesh_Hut')
        {
            $this->validate($request, [
            'name' => 'required|max:150',
            'userName' => 'required',
            'password' => 'required'
        ]);
        
        $input = $request->all();
		if( $request['status'] ):
			$data['status']='Active';
		else:
			$data['status']='Inactive';
		endif;
		$data['name']=$request['name'];
		$data['userName']=$request['userName'];
        $data['password']=$request['password'];
       // $data['swadeshhut_id']=$request['swadeshhut_id'];
		$data['updated_at']=date("Y-m-d H:i:s");
            
        }else{
          $this->validate($request, [
            'name' => 'required|max:150',
            'userName' => 'required',
            'password' => 'required',
            'swadeshhut_id' => 'required'
        ]); 
        
        $input = $request->all();
		if( $request['status'] ):
			$data['status']='Active';
		else:
			$data['status']='Inactive';
		endif;
		$data['name']=$request['name'];
		$data['userName']=$request['userName'];
        $data['password']=$request['password'];
        $data['swadeshhut_id']=$request['swadeshhut_id'];
		$data['updated_at']=date("Y-m-d H:i:s");
            
        }
        
        
		//print_r($data);die;  
		DB::table('swadeshhut_pos')->where( 'id', $id )->update($data);
		return redirect()->route('swadesh_hut_pos.index')
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
        DB::table('swadeshhut_pos')->where( 'id', $id )->delete();
		return redirect()->route('swadesh_hut_pos.index')
                        ->with('success','Swadesh Hut Pos deleted successfully');
    }
	
	public function changestatus(Request $request)
    {
		//echo $request->id;die;
		//echo '<pre>'; print_r($request);die;
		$data['updated_at']=date("Y-m-d H:i:s");
		$data['status']=$request->status;
		//print_r($data);die;  
		DB::table('swadeshhut_pos')->where( 'id', $request->id )->update($data);
		return response()->json(['success'=>'Status change successfully.']);

    }
	
}
