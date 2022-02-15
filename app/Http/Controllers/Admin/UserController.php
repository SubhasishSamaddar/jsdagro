<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notifications\SignupActivate;
use App\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use App\Country;
use Illuminate\Support\Str;
use App\Package_location;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = User::orderBy('id','DESC')->get();
        return view('admin.users.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        $countries = Country::select('id','country_name')->where('status','Active')->orderBy('position_order','ASC')->orderBy('country_name','ASC')->get();
        $swadesh_huts = DB::table('swadesh_huts')->get();

        $package_locations = Package_location::all();

        return view('admin.users.create',compact('roles','countries','swadesh_huts','package_locations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed|same:password_confirmation|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
            'roles' => 'required',
            'profile_image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $input = $request->all();
        $input['profile_image'] = 'profile/profile_image.png';
        $input['password'] = Hash::make($input['password']);
        $input['activation_token'] = Str::random(60);
        $profile_image = 'profile_image.png';
        if ($files = $request->file('profile_image')) {
            // Define upload path
            $destinationPath = public_path('/storage/profile/'); // upload path
         // Upload Orginal Image
            $profile_image = 'profile_image_'.date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profile_image);
            $input['profile_image'] = 'profile/'.$profile_image;
        }

        if(isset($input['user_type']) && $input['user_type']=='Package')
        {
            $input['package_location_id'] = $input['package_location_id'];
        }



        $user = User::create($input);

        if(isset($input['user_type']) && $input['user_type']=='Swadesh_Hut')
        {
            $user_swadesh_hut_id = $input['user_swadesh_hut_id'];
            $swaesh_hut_array = array('user_id'=>$user->id,'swadesh_hut_id'=>$user_swadesh_hut_id);
            DB::table('swadesh_hut_users')->insert($swaesh_hut_array);
        }




        $user->assignRole($request->input('roles'));
        //$user->notify(new SignupActivate($user));

        return redirect()->route('users.index')
                        ->with('success','Successfully created user!');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('admin.users.show',compact('user'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        $countries = Country::select('id','country_name')->where('status','Active')->orderBy('position_order','ASC')->orderBy('country_name','ASC')->get();
        $swadesh_huts = DB::table('swadesh_huts')->get();

        $get_user_swadesh_hut_details = DB::table('swadesh_hut_users')->where('user_id',$id)->first();

        $package_locations = Package_location::all();

        return view('admin.users.edit',compact('user','roles','userRole','countries','swadesh_huts','get_user_swadesh_hut_details','package_locations'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'roles' => 'required',
            'profile_image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);


        $input = $request->all();

        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = array_except($input,array('password'));
        }
        if ($files = $request->file('profile_image')) {
            // Define upload path
            $destinationPath = public_path('/storage/profile/'); // upload path
         // Upload Orginal Image
            $profile_image = 'profile_image_'.date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profile_image);
            $input['profile_image'] = 'profile/'.$profile_image;
        }

        if(isset($input['user_type']) && $input['user_type']=='Package')
        {
            $input['package_location_id'] = $input['package_location_id'];
        }

        $user = User::find($id);
        $user->update($input);


        if(isset($input['user_type']) && $input['user_type']=='Swadesh_Hut')
        {
            $user_swadesh_hut_id = $input['user_swadesh_hut_id'];
            $get_user_swadesh_hut_details = DB::table('swadesh_hut_users')->where('user_id',$id)->first();
            if($get_user_swadesh_hut_details)
            {
                DB::table('swadesh_hut_users')->where('user_id',$id)->delete();
            }
            $swaesh_hut_array = array('user_id'=>$id,'swadesh_hut_id'=>$user_swadesh_hut_id);
            $create_swadesh_hut = DB::table('swadesh_hut_users')->insert($swaesh_hut_array);
        }
        else
        {
            $get_user_swadesh_hut_details = DB::table('swadesh_hut_users')->where('user_id',$id)->first();
            if($get_user_swadesh_hut_details)
            {
                DB::table('swadesh_hut_users')->where('user_id',$id)->delete();
            }
        }



        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile_update()
    {
        $countries = Country::select('id','country_name')->where('status','Active')->orderBy('position_order','ASC')->orderBy('country_name','ASC')->get();
        $states = Country::getStates(auth()->user()->country);
        return view('admin.user.profile',compact('countries','states'));
    }



    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store_profile(Request $request)
    {
        $id = auth()->user()->id;
        $validatedData = $request->validate([
            'name' => 'required|max:150',
            'email' => 'required|email|max:250|unique:users,email,'.$id,
            'profile_image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $input = $request->all();

        if ($files = $request->file('profile_image')) {
            // Define upload path
            $destinationPath = public_path('/storage/profile/'); // upload path
         // Upload Orginal Image
            $profile_image = 'profile_image_'.date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profile_image);
            $input['profile_image'] = 'profile/'.$profile_image;
        }

        $user = User::find($id);
        $user->update($input);

        return redirect('/cpanel/profile_update')->with('success', 'Profile updated successfully.');
    }

    // Fetch records
    public function getStates($country_id=0){

        $states['data'] = Country::getStates($country_id);

        echo json_encode($states);
        exit;
    }

}
