<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Helper;
use Illuminate\Support\Facades\Crypt;

class WebViewController extends Controller
{
    public function order_details_webview($order_id) {
        $decrypt_order_id= Crypt::decryptString($order_id);
        $data = DB::table('order_details as A')
				->where('order_id', $decrypt_order_id)
				->select('A.*', 'O.*', 'B.prod_name as title','B.product_image as product_image','B.sku as SKU','B.weight_per_pkt as WPP','B.weight_unit as WU')
                ->leftJoin('products as B','A.product_id','=','B.id')
                ->leftJoin('order_master as O','O.id','=','A.order_id')
				->get();
		$data=$data->toArray();
        return view('front.webview.order_details_webview',compact('order_id','data'));
    }

    public function pos_order_details_webview($order_id) {
        $decrypt_order_id= Crypt::decryptString($order_id);
        $data = DB::table('order_details as A')
				->where('order_id', $decrypt_order_id)
				->select('A.*', 'O.*', 'B.prod_name as title','B.product_image as product_image','B.sku as SKU','B.weight_per_pkt as WPP','B.weight_unit as WU')
                ->leftJoin('products as B','A.product_id','=','B.id')
                ->leftJoin('order_master as O','O.id','=','A.order_id')
				->get();
		$data=$data->toArray();
        return view('front.webview.pos_order_details_webview',compact('order_id','data'));
    }

}
