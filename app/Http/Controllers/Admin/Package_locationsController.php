<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use DB;
use App\Package_location;
class Package_locationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	 
	function __construct(){
         $this->middleware('permission:package_location-list');
         $this->middleware('permission:package_location-create', ['only' => ['create','store']]);
         $this->middleware('permission:package_location-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:package_location-delete', ['only' => ['destroy']]);
    }
	
    public function index()
    {
		$records=Package_location::all(); 
		return view('admin.package_locations.index', compact('records'));
    }

    public function create(){
		return view('admin.package_locations.create');
    }

    
    public function store(Request $request){
		$this->validate($request, [
            'location_name' => 'required|max:150|unique:package_locations',
            'cover_area' => 'required'
        ]);
		$input = $request->all();
		if( isset($input['status']) ):
			$input['status']='Active';
		else:
			$input['status']='Inactive';
		endif;
		$package_location = Package_location::create($input);
        return redirect()->route('package_location.index')
                        ->with('success','Package Location created successfully'); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function edit($id){
		$details = Package_location::find($id);
        return view('admin.package_locations.edit',compact('details'));
    }

    public function update(Request $request, $id){
        $this->validate($request, [
            'location_name' => 'required|max:150|unique:swadesh_huts,location_name,'.$id.',id',
            'cover_area' => 'required'
        ]);
		$input = $request->all();
		if( isset($input['status']) ):
			$input['status']='Active';
		else:  
			$input['status']='Inactive';
		endif;
		$Package_location = Package_location::find($id);
        $Package_location->update($input);
		return redirect()->route('package_location.index')
                        ->with('success','Package Location updated successfully');	
    }
    public function destroy($id){
		Package_location::find($id)->delete();
        return redirect()->route('package_location.index')
                        ->with('success','Package Location deleted successfully');
    }
	
	public function changestatus(Request $request)
    {
		$data['status']=$request->status;
		$Package_location = Package_location::find($request->id);
        $Package_location->update($data);
		//DB::table('swadesh_huts')->where( 'id', $request->id )->update($data);
		return response()->json(['success'=>'Status change successfully.']);

    }
}
