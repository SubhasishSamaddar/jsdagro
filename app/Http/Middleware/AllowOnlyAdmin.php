<?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Support\Facades\Auth;
    use Spatie\Permission\Models\Role;
    use App\User;
    class AllowOnlyAdmin
    {
        public function handle($request, Closure $next)
        {
            $user = User::find(Auth::user()->id);

            $userRole = $user->roles->pluck('name','name')->all();
            //dd($userRole);

            if (in_array('admin',$userRole)) {
                return $next($request);
            }

            return redirect()->route('home');
        }
    }
?>
