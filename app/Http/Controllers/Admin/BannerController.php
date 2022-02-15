<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Banner;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    function __construct()
    {
         $this->middleware('permission:banner-list');
         $this->middleware('permission:banner-create', ['only' => ['create','store']]);
         $this->middleware('permission:banner-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:banner-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $banners = Banner::where('banner_image_type','normal')->get();
        $small_banners = Banner::where('banner_image_type','small')->get();
        return view('admin.banner.index',compact('banners','small_banners'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();


        if($files = $request->file('banner_image')) {
            $image_text = $input['banner_image_text'];
            $banner_image_url = $input['banner_image_url'];
            // Define upload path
            $destinationPath = public_path('/storage/banner/'); // upload path
            // Upload Orginal Image
            for($i=0;$i<count($files);$i++)
            {
                $banner_image = 'banner_'. time() . rand(00000,99999) . "." . $files[$i]->getClientOriginalExtension();
                $files[$i]->move($destinationPath, $banner_image);
                $input['banner_image'] = $banner_image;
                $input['banner_image_text'] = $image_text[$i];
                $input['banner_url'] = $banner_image_url[$i];
                $input['banner_image_type'] = 'normal';
                Banner::create($input);
            }
        }


        if($files2 = $request->file('small_banner_image')) {
            // Define upload path
            $destinationPath2 = public_path('/storage/banner/small/'); // upload path
            // Upload Orginal Image
            for($c=0;$c<count($files2);$c++)
            {
                $small_banner_image = 'small_banner_'. time() . rand(00000,99999) . "." . $files2[$c]->getClientOriginalExtension();
                $files2[$c]->move($destinationPath2, $small_banner_image);
                $input2['banner_image'] = $small_banner_image;
                $input2['banner_image_text'] = $input['small_banner_image_text'][$c];
                $input2['banner_url'] = $input['small_banner_image_url'][$c];
                $input2['banner_image_type'] = 'small';
                Banner::create($input2);
            }
        }


        return redirect()->route('banner.index')
                        ->with('success','Banner Images Updated successfully');
    }


    /**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteImage(Request $request)
    {
        $image_type = $request->image_type;
        $id = $request->id;
        Banner::where('id',$id)->delete();

        return response()->json(['success'=>'Banner Image Deleted successfully.']);
    }

    public function updateText(Request $request)
    {
        $text = $request->text;
        $id = $request->id;
        Banner::where('id',$id)->update(['banner_image_text'=>$text]);

        return response()->json(['success'=>'Banner text updated successfully.']);
    }

    public function updateUrl(Request $request)
    {
        $url = $request->url;
        $id = $request->id;
        Banner::where('id',$id)->update(['banner_url'=>$url]);

        return response()->json(['success'=>'Banner url updated successfully.']);
    }

}
