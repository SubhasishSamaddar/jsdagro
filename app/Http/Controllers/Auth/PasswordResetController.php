<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\User;
use App\PasswordReset;
use Illuminate\Support\Facades\Hash;
use DB;

class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create(Request $request)
    {
          
        $request->validate([
            'email' => 'required|string|email',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user)
            return response()->json([
                'message' => "We can't find a user with that e-mail address."
            ], 404);

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => str_random(60)
             ]
        );
        if ($user && $passwordReset)
            $user->notify(
                new PasswordResetRequest($passwordReset->token)
            );
        return response()->json([
            'message' => 'We have e-mailed your password reset link!'
        ]);
    }
    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        
        $passwordReset = PasswordReset::where('token', $token)
            ->first();

        if (!$passwordReset)
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        }
        //return response()->json($passwordReset);
         // $categories = DB::table('categories as A')->select('A.id','A.name','A.status','A.category_image','A.position_order','A.description','A.parent_id','B.name as parent_name','A.created_at','A.updated_at')
				// 	->leftJoin('categories as B','A.parent_id','=','B.id')
				// 	->orderBy('A.position_order','ASC')->orderBy('parent_name','ASC')
				// 	->where('A.status','Active')->get();
        
        //	PARENT CATEGORY
	//	$parentCategory = Category::Where('parent_id','0')->Where('status','Active')->limit(12)->get();
    //dd($parentCategory);
    return view('auth.passwords.reset',compact('passwordReset'));
        //return view('auth.passwords.reset',compact('passwordReset','categories','parentCategory'));
    }
     /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {
         
        //print_r($request->all());
        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();


        if (!$passwordReset)
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);

        $user = User::where('email', $passwordReset->email)->first();
        //print_r($user);
        if (!$user)
            return response()->json([
                'message' => "We can't find a user with that e-mail address."
            ], 404);
        $new_password = Hash::make($request->password);
        $update_array = array ('password' => $new_password);
        DB::table('users')->where('email', $passwordReset->email)->update($update_array);
        DB::table('password_resets')->where('token', $request->token)->delete();
        echo 'Password Changed Successfully';
        $user->notify(new PasswordResetSuccess($passwordReset));
        return redirect()->route('login')->with('success','Password Changed successfully');
    }
}
