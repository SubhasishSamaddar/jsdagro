<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use DB;
use App\Category;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

	public function authenticated()
	{
		/*
		if(auth()->user()->hasRole('admin'))
		{
			return redirect('/admin/dashboard');
		}
		*/
		//echo 'fffff';die;
		//echo '<pre>';print_r(auth()->user());

		$logUser=auth()->user();
		if($logUser['user_type']=="Customer"):
			return redirect('shipping');
		endif;

    }
    
    public function showLoginForm(Request $request)
    {
        $title = 'Login';
        if(!Session::has('swadesh_hut_id'))
		{
			$request->session()->put('swadesh_hut_name', 'uttarpara');
			$request->session()->put('swadesh_pin_code', '712232');
			$request->session()->put('swadesh_hut_id', '3');
			/*Session::set('swadesh_hut_name', 'Uttarpara');
			Session::set('swadesh_pin_code', '712232');
			Session::set('swadesh_hut_id', '3');*/
		}
        $categories = DB::table('categories as A')->select('A.id','A.name','A.status','A.category_image','A.position_order','A.description','A.parent_id','B.name as parent_name','A.created_at','A.updated_at')
					->leftJoin('categories as B','A.parent_id','=','B.id')
					->orderBy('A.position_order','ASC')->orderBy('parent_name','ASC')
					->where('A.status','Active')->get();
        
        //	PARENT CATEGORY
		$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->orderBy('position_order','ASC')->limit(12)->get();
    
        return view('auth.login', compact('categories','parentCategory'));
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/login');
    }


    protected function credentials(Request $request)
    {
        if(is_numeric($request->get('email'))){
            return ['phone'=>$request->get('email'),'password'=>$request->get('password')];
        }
        elseif (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            return ['email' => $request->get('email'), 'password'=>$request->get('password')];
        }
        return ['username' => $request->get('email'), 'password'=>$request->get('password')];
    }

}
