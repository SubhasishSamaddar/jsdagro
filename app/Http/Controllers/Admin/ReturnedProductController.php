<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use DB;

use App\Category;

use App\Product;

use App\User;

use Image;

use Auth;

use Helper;



class ReturnedProductController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

    */

    function __construct()
    {

    }



    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index(Request $request)
    {
        $user_type = Auth::user()->user_type;

        if($user_type=='Swadesh_Hut')
        {
            $get_swadesh_hut_user = DB::table('swadesh_hut_users')->where('user_id',Auth::user()->id)->first();
            $products = DB::table('returned_product_details as A')
            ->select('A.*', 'B.*')
            ->where('swadesh_hut_id',$get_swadesh_hut_user->swadesh_hut_id)
            ->leftJoin('swadesh_huts as B','A.swadesh_hut_id','=','B.id')
            ->get();
        }else{

            $products = DB::table('returned_product_details as A')
            ->select('A.*', 'B.*')
            ->leftJoin('swadesh_huts as B','A.swadesh_hut_id','=','B.id')
            ->get();
        }

        return view('admin.returnedproducts.index',compact('products','user_type'));

    }



}

