<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use DB;
use App\Category;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Session;
use Helper;
use App\Notifications\SignupActivate;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    
        public function showRegistrationForm()
        {
            $title = 'Register';
            
             $categories = DB::table('categories as A')->select('A.id','A.name','A.status','A.category_image','A.position_order','A.description','A.parent_id','B.name as parent_name','A.created_at','A.updated_at')
					->leftJoin('categories as B','A.parent_id','=','B.id')
					->orderBy('A.position_order','ASC')->orderBy('parent_name','ASC')
					->where('A.status','Active')->get();
        
        //	PARENT CATEGORY
		$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->orderBy('position_order','ASC')->limit(12)->get();
        
            return view('auth.register', compact('categories','parentCategory'));
        }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'address_1' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
			
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = new User([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']), 
            'user_type' => 'Customer',
            'phone' => $data['phone'],
            'address_1' => $data['address_1'],
            'address_2' => $data['address_2'],
            'city' => $data['city'],
            'state' => $data['states'],
            'pincode' => $data['pincode'],
            'country' => '103',
            'active' => 0,
            'activation_token' => str_random(60),
        ]);

        $user->save();
        
        $user->notify(new SignupActivate($user));
        
        return  $user;
    }
	
	public function redirectTo()
    {
        if( Session::has('total_price') ){
            return '/shipping';
		}else{
			return '/login';
		}
    }
	
}
