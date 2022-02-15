<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Image; //Intervention Image
use Illuminate\Support\Facades\Storage;


class ImageController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function imageCrop()
    {
        return view('admin.package_inventory.imageCrop');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function imageCropPost(Request $request)
    {
        if ($request->hasFile('images')) {
            foreach($request->file('images') as $file){ 
                //get filename with extension
                $filenamewithextension = $file->getClientOriginalName();
                //get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                //get file extension
                $extension = $file->getClientOriginalExtension();
                //filename to store
                $filenametostore = $filename.'.'.$extension;
    
                Storage::put('public/product/'. $filenametostore, fopen($file, 'r+'));
                Storage::put('public/product/thumbnail/'. $filenametostore, fopen($file, 'r+'));

                $height = Image::make($file)->height();
                $width = Image::make($file)->width();
                //Resize image here
                $thumbnailpath = public_path('storage/product/thumbnail/'.$filenametostore);
                $img = Image::make($thumbnailpath)->resize($width/2, $height/2, function($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save($thumbnailpath);
            }
            return redirect()->back()->with('success', "Image uploaded and resized successfully.");
        }
    }
}