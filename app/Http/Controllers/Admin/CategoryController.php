<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Category;
use Helper;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    function __construct()
    {
         $this->middleware('permission:category-list');
         $this->middleware('permission:category-create', ['only' => ['create','store']]);
         $this->middleware('permission:category-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = DB::table('categories as A')->select('A.id','A.name','A.status','A.category_image','A.position_order','A.show_home_page','A.description','B.name as parent_name','A.created_at','A.updated_at')
                            ->leftJoin('categories as B','A.parent_id','=','B.id')
                            ->orderBy('A.position_order','ASC')->orderBy('A.name','ASC')->get();


        return view('admin.categories.index',compact('categories'));
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
                            //->where('parent_id','0')
                            ->orderBy('name','ASC')->get();
        return view('admin.categories.create',compact('categories'));
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
            'name' => 'required|max:150',
            'position_order' => 'numeric',
            //'category_image' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);
        $input = $request->all();
        $input['category_image'] = 'category/category.png';
        $input['status']= (isset($input['status']) && $input['status']=='on')?'Active':'Inactive';
        $category_image = '';
        if ($files = $request->file('category_image')) {
            // Define upload path
            $destinationPath = public_path('/storage/category/'); // upload path
         // Upload Orginal Image
            $category_image = 'category_'.date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $category_image);
            $input['category_image'] = 'category/'.$category_image;
        }

        $img_string='';
        if ($filesbanner = $request->file('category_banner_images')) {
            foreach($filesbanner as $bfiles){
                // Define upload path
                $destinationPath = public_path('/storage/category/'); // upload path
                // Upload Orginal Image
                $category_banner_image= 'category_banner_'.Helper::rand_string(8). "." . $bfiles->getClientOriginalExtension();
                $bfiles->move($destinationPath, $category_banner_image);
                $img_string.=$category_banner_image.',';
            }
            $img_string = rtrim($img_string,',');
        }
        $input['category_banner_images'] = $img_string;

        $nameUrl=Helper::make_url($request->name);
        $input['name_url'] = $nameUrl;

        $category = Category::create($input);

        return redirect()->route('categories.index')
                        ->with('success','Category created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        return view('admin.categories.show',compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);
        $categories = Category::select('id','name')
                            ->where('status','Active')
                            //->where('parent_id','0')
                            ->orderBy('name','ASC')->get();
        return view('admin.categories.edit',compact('category','categories'));
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
            'name' => 'required|max:150',
            'position_order' => 'numeric',
            //'category_image' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $input = $request->all();
        $input['status']= (isset($input['status']) && $input['status']=='on')?'Active':'Inactive';

        $category_image = '';
        if ($files = $request->file('category_image')) {
            // Define upload path
            $destinationPath = public_path('/storage/category/'); // upload path
         // Upload Orginal Image
            $category_image = 'category_'.date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $category_image);
            $input['category_image'] = 'category/'.$category_image;
        }

        $img_string='';
        if ($filesbanner = $request->file('category_banner_images')) {
            foreach($filesbanner as $bfiles){
                // Define upload path
                $destinationPath = public_path('/storage/category/'); // upload path
                // Upload Orginal Image
                $category_banner_image= 'category_banner_'.Helper::rand_string(8). "." . $bfiles->getClientOriginalExtension();
                $bfiles->move($destinationPath, $category_banner_image);
                $img_string.=$category_banner_image.',';
            }
            $img_string = rtrim($img_string,',');
        }
        $input['category_banner_images'] = $img_string;

        $category = Category::find($id);
        $category->update($input);

        return redirect()->route('categories.index')
                        ->with('success','Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Category::find($id)->delete();
        return redirect()->route('categories.index')
                        ->with('success','Category deleted successfully');
    }

    /**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {
        $category = Category::find($request->id);
        $category->status = $request->status;
        $category->save();

        return response()->json(['success'=>'Status change successfully.']);
    }

    public function changeshownstatus(Request $request)
    {
        $category = Category::find($request->id);
        $category->show_home_page = $request->shown;
        $category->save();

        return response()->json(['success'=>'Home page shown status changed successfully.']);
    }

    public function changeNameUrl(Request $request){
        $categories = Category::all();

        foreach($categories as $details){
            $nameUrl=Helper::make_url($details->name);
            $update_array = array('name_url'=>$nameUrl);
            $category = Category::find($details->id);
            $category->update($update_array);
        }
        return redirect()->route('categories.index')->with('success','Name Url Updated successfully');
    }

}
