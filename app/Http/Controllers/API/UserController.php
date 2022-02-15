<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\User;
use Auth;
use Illuminate\Support\Facades\Hash;
class UserController extends BaseController
{
    public function updateProfile(Request $request){
		$this->validate($request, [
			'name' => 'required',
			'phone' => 'required',
			'address_1' => 'required',  
			'city' => 'required',
			'state' => 'required',
			'pincode' => 'required',
			'state' => 'required',  
		]); 
		//$userId=$request->user_id;  
		$input['name']=$request->name;  
		$input['phone']=$request->phone;  
		$input['address_1']=$request->address_1;  
		$input['city']=$request->city;  
		$input['state']=$request->state;  
		$input['pincode']=$request->pincode;  
		$input['state']=$request->state;   
		
		$User = User::find(Auth::user()->id);
        $User->update($input); 

		$data = User::find(Auth::user()->id);    
		return $this->sendResponse($data->toArray(), 'Data retrieved successfully.');
	}
	
	public function change_password(Request $request)
    {
        $input = $request->all();
        $this->validate($request, [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|max:16', //|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/
            'new_confirm_password' => ['same:new_password'],
        ]);
		$userid = auth()->user()->id;
		$user = User::where('id', $userid)->first();
		if (Hash::check($request->current_password, $user->password)) {
			$user->password = Hash::make($request->new_password);
			if( $user->save() ){
				return $this->sendResponse($user->toArray(), 'Data retrieved successfully.');
			}
		}else{
			return $this->sendResponse([],'You have entered an invalid username or password');
			
		}
		
    }


/*
$user = User::findOrFail($id); 
$this->validate($request, [ 'password' => 'required', 'new_password' => 'confirmed|max:8|different:password', ]); 
if (Hash::check($request->password, $user->password)) 
{ 
$user->fill([ 'password' => Hash::make($request->new_password) ])->save(); 
$request->session()->flash('success', 'password changed'); 
return redirect()->route('your.route'); } else { $request->session()->flash('error', 'password does not match'); return redirect()->route('your.route'); }
*/
}
