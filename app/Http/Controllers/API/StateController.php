<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Country;
use App\State;
use Validator;


class StateController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $country_id=0)
    {
        $query = State::select('states.*','countries.country_name','country_code2','country_code3')
                    ->leftJoin('countries','states.country_id','=','countries.id');
         
        $data = $query->orderBy('countries.position_order','ASC')
                        ->orderBy('states.state_name','ASC')->get();
        return $this->sendResponse($data->toArray(), 'Data retrieved successfully.');
    } 

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $query = State::select('states.*','countries.country_name','country_code2','country_code3')
                    ->leftJoin('countries','states.country_id','=','countries.id');
        if($id>0)            
        {
            $query->where('states.country_id',$id);
        }
        $data = $query->orderBy('countries.position_order','ASC')
                        ->orderBy('states.state_name','ASC')->get();


        if (is_null($data)) {
            return $this->sendError('Data not found.');
        }


        return $this->sendResponse($data->toArray(), 'Data retrieved successfully.');
    }
	
	public function statesByCountry(Request $request)
    {   
        $data = State::where('country_id', $request->country_id)->get();
        return $this->sendResponse($data->toArray(), 'Data retrieved successfully.');
    } 
	
	public function banner(Request $request)
	{
		$data[]=array('banner' => 'public/storage/banners/banner_1.jpg');   
		$data[]=array('banner' => 'public/storage/banners/banner_2.jpg');   
		$data[]=array('banner' => 'public/storage/banners/banner_3.jpg');   
		$data[]=array('banner' => 'public/storage/banners/banner_4.jpg');   
		return $this->sendResponse($data, 'Data retrieved successfully.');
    } 

     
}