<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use App\User;
use App\OtpLoginDetails;
use App\Notifications\SignupActivate;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
			'state' => 'required',
			'pincode' => 'required'
		]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'company_name' => $request->company_name,
            'title' => $request->title,
            'user_type' => ($request->user_type)?$request->user_type:'Customer',
            'phone' => $request->phone,
            'address_1' => $request->address_1,
            'address_2' => $request->address_2,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'country' => ($request->country)?$request->country:'103',
            'activation_token' => str_random(60),
        ]);

        $user->save(); 
        $user->assignRole('Customer');
        $user->notify(new SignupActivate($user));

        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    public function signupActivate($token)
    {
        $user = User::where('activation_token', $token)->first();
        if (!$user) {
            return response()->json([
                'message' => 'This activation token is invalid.'
            ], 404);
        }
        $user->active = true;
        $user->activation_token = '';
        $user->save();
        //return $user;
        return redirect()->route('login')
                        ->with('success','Your account successfully activated');
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
		/*print_r($credentials);die;
        $credentials['active'] = 1;  */
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'logged_in_user_id' => Auth::user()->id,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
		$data=$request->user();
		$pincodeData = DB::table('swadesh_huts')
						->where('cover_area_pincodes', 'like', '%'.$data->pincode.'%')
						->first();
		$data->swadesh_hut_id=$pincodeData->id;	
		$data->swadesh_hut_name=$pincodeData->location_name;
		$tempProfileImage='/public/storage/'.$data->profile_image;
		unset($data->profile_image);
		$data->profile_image=$tempProfileImage;
        //return response()->json($request->user());
        return response()->json($data);
    }

    public function updateProfile(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
			'name' => 'required',
            'phone' => 'required|numeric',
            'address_1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required',
            'state' => 'required',
		]);
        if ($validator->fails()) {
			$error_msg = $validator->messages()->toJson();
			$msg = '<div class="alert alert-danger" role="alert">'.$error_msg.'</div>';
	   	}
		else {
			$data['name']		= $request['name'];
			$data['phone']		= $request['phone'];
			$data['address_1']  = $request['address_1'];
			$data['company_name']  = $request['company_name'];
			$data['city']		= $request['city'];
			$data['state']		= $request['state'];
			$data['pincode']	= $request['pincode'];
			$data['address_2']	= $request['address_2'];
			$data['updated_at']	= date("Y-m-d H:i:s"); 
			User::where('id',Auth::user()->id )->update($data);  

			$msg = '<div class="alert alert-success" role="alert">Profile Data Updated Successfully !!</div>';
		}
		return response()->json(['msg'=>$msg]);
    }

    public function sendOtp(Request $request)
    {
        $input = $request->all();
        $mobile_no = $input['mobile_no'];
        $get_user_by_mobile = User::where('phone',$mobile_no)->first();
        if(empty($get_user_by_mobile))
        {
            $msg = 'Your Phone Number Is Not Registered With Us.';
            $success = 0;
            return response()->json(array('success'=>$success,'msg'=>$msg));
        }
        else 
        {
            $otp = rand(100000,999999);
            $message = $otp.' is your JSDAgro Login OTP. Please do not share it with anyone.\nSee You Soon,\nTeam JSDGlobal';
            
            $ch = curl_init();
            $url = "http://164.52.195.161/API/SendMsg.aspx";
            $dataArray = ['uname' => '20210580', 'pass' => '9D9Sq9d9', 'send' => 'JSDGLB', 'dest' => $mobile_no, 'msg' => $message];

            $data = http_build_query($dataArray);
            $getUrl = $url."?".$data;
            $getUrl = str_replace('+', '%20', $getUrl);
            $getUrl = str_replace('%5Cn', '%0A', $getUrl);
            $getUrl = str_replace('%2C', ',', $getUrl);
            
        
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPAUTH, 0);
            curl_setopt($ch, CURLOPT_URL, $getUrl);
            curl_setopt($ch, CURLOPT_TIMEOUT, 80);
            
            $response = curl_exec($ch);

            if(curl_error($ch)){
                echo 'Request Error:' . curl_error($ch);
            }
            
            curl_close($ch);

            DB::table('otp_login_details')->insert(array('otp'=>$otp,'mobile'=>$mobile_no,'created_at'=>date('Y-m-d H:i:s')));

            $msg = 'Your OTP Has Been Sent To Your Mobile Number.';
            $success = 1;
            return response()->json(array('success'=>$success, 'msg'=>$msg, 'mobile_no'=>$mobile_no));
  
        }
    }


    public function sendOtpByLogin(Request $request)
    {
        $input = $request->all();
        $mobile_no = $input['mobile_no'];
        $otp = $input['otp'];

        $otp_login_details = DB::table('otp_login_details')->where('otp',$otp)->where('mobile',$mobile_no)->first();

        if(!empty($otp_login_details)){
            $get_user_details_by_phone  = User::where('phone',$mobile_no)->first();
            $credentials = $get_user_details_by_phone->only('email', 'password');
            if (Auth::loginUsingId($get_user_details_by_phone->id, TRUE)) {
                return response()->json(array('success'=>1, 'msg'=>'Login Successful!!', 'mobile_no'=>$mobile_no, 'user_type'=>$get_user_details_by_phone->user_type));
            }
            
        }
    }
}
