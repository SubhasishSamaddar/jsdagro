<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Product;
use DB;
use Session;
use Cart;
use Auth;
use File;

class AutocompleteController extends Controller
{
	public function autocomplete(Request $request)
        {
                $search = $request->get('term');
                $swadesh_hut_id=Session::get('swadesh_hut_id');
                $products = Product::where('prod_name', 'LIKE', '%'. $search. '%')->where('swadesh_hut_id', $swadesh_hut_id)->orWhere('tags', 'LIKE', '%'. $search. '%')->groupBy('prod_name')->get();
                
                foreach($products as $details)
                {
                        if (File::exists(public_path('storage/'.$details->product_image))) {
                                $details->product_image = $details->product_image;
                        }else{
                                $details->product_image = 'product.png';
                        }
                }
                return response()->json($products);
	}

}
