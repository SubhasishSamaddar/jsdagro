<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\State;
use App\Country;

class StatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    function __construct()
    {
         $this->middleware('permission:state-list');
         $this->middleware('permission:state-create', ['only' => ['create','store']]);
         $this->middleware('permission:state-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:state-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $states = DB::table('states as A')
                        ->select('A.id','A.state_name','A.state_code','A.country_id','B.country_name','A.created_at','A.updated_at')
                        ->leftJoin('countries as B','A.country_id','=','B.id')
                        ->orderBy('B.country_name','ASC')
                        ->orderBy('A.state_name','ASC')->get();
        return view('admin.states.index',compact('states'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::select('id','country_name')
                            ->where('status','Active')
                            ->orderBy('country_name','ASC')->get();
        return view('admin.states.create',compact('countries'));
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
            'state_name' => 'required|max:200',
            'state_code' => 'required|unique:states,state_code|max:3|min:2',
            'country_id' => 'required',
        ]);
        $input = $request->all();
        $state = State::create($input);

        return redirect()->route('states.index')
                        ->with('success','State created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $state = State::find($id);
        return view('admin.states.show',compact('state'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $state = State::find($id);
        $countries = Country::select('id','country_name')
                            ->where('status','Active')
                            ->orderBy('country_name','ASC')->get();
        return view('admin.states.edit',compact('state','countries'));
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
            'state_name' => 'required|max:200',
            'state_code' => 'required|max:3|min:2|unique:states,state_code,'.$id,
            'country_id' => 'required',
        ]);

        $input = $request->all();
        $state = State::find($id);
        $state->update($input);

        return redirect()->route('states.index')
                        ->with('success','state updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        State::find($id)->delete();
        return redirect()->route('states.index')
                        ->with('success','State deleted successfully');
    }

}
