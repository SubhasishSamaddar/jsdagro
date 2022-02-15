<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Country;
use Validator;


class CountryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Country::all();


        return $this->sendResponse($data->toArray(), 'Data retrieved successfully.');
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


        $validator = Validator::make($input, [
            'country_name' => 'required|max:200',
            'country_code2' => 'required|unique:countries,country_code2|max:2|min:2',
            'country_code3' => 'required|unique:countries,country_code3|max:3|min:3',
            'position_order' => 'numeric', 
        ]);


        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }


        $data = Country::create($input);


        return $this->sendResponse($data->toArray(), 'Data created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Country::find($id);


        if (is_null($data)) {
            return $this->sendError('Data not found.');
        }


        return $this->sendResponse($data->toArray(), 'Data retrieved successfully.');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int  $id, Country $data)
    {
        $input = $request->all();
         
        $validator = Validator::make($input, [
            'country_name' => 'required|max:200',
            'country_code2' => 'required|max:2|min:2|unique:countries,country_code2,'.$data->id,
            'country_code3' => 'required|max:3|min:3|unique:countries,country_code3,'.$data->id,
            'position_order' => 'numeric', 
        ]);


        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }


        $data->country_name = $input['country_name'];
        $data->country_code2 = $input['country_code2'];
        $data->country_code3 = $input['country_code3'];
        $data->position_order = $input['position_order'];
        $data->save();


        return $this->sendResponse($data->toArray(), 'Country updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $data)
    {
        $data->delete();


        return $this->sendResponse($data->toArray(), 'Country deleted successfully.');
    }
}