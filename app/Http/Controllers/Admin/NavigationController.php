<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Navigation;

class NavigationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:navigation-list');
         $this->middleware('permission:navigation-create', ['only' => ['create','store']]);
         $this->middleware('permission:navigation-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:navigation-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $navigations = DB::table('navigations as A')
                        ->select('A.id','A.title','A.link_url','A.target','A.position_order','A.position_block','A.created_at','A.updated_at')
                        ->orderBy('A.title','ASC')->get();

        return view('admin.navigations.index',compact('navigations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.navigations.create');
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
            'title' => 'required|max:150',
            'link_url' => 'required|max:255',
            'position_block' => 'required|max:150',
            'position_order' => 'numeric',
        ]);
        $input = $request->all();
        $input['target']= (isset($input['target']) && $input['target']=='on')?'_self':'_blank';

        $navigation = Navigation::create($input);

        return redirect()->route('navigations.index')
                        ->with('success','Navigation created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $navigation = Navigation::find($id);
        return view('admin.navigations.show',compact('navigation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $navigation = Navigation::find($id);
        return view('admin.navigations.edit',compact('navigation'));
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
            'title' => 'required|max:150',
            'link_url' => 'required|max:255',
            'position_block' => 'required|max:150',
            'position_order' => 'numeric',
        ]);
        $input = $request->all();
        $input['target']= (isset($input['target']) && $input['target']=='on')?'_self':'_blank';

        $navigation = Navigation::find($id);
        $navigation->update($input);

        return redirect()->route('navigations.index')
                        ->with('success','Navigation updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Navigation::find($id)->delete();
        return redirect()->route('navigations.index')
                        ->with('success','Navigation deleted successfully');
    }

    /**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {
        $navigation = Navigation::find($request->id);
        $navigation->target = $request->target;
        $navigation->save();

        return response()->json(['success'=>'Target window change successfully.']);
    }
}
