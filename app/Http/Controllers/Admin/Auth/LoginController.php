<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Lockout;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Requests\Auth\AdminLoginRequest;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin.guest')->except('logout');

        // $this->username = $this->findUsername();

    }

    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function Login(AdminLoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();
        return redirect()->intended(RouteServiceProvider::ADMIN_DASHBOARD);
    //     $this->ensureIsNotRateLimited();


    //    if (Auth::guard('admin')->attempt($request->only('username', 'password'))) {
    //     dd('login success');
    }


        // $request->authenticate();
        // $request->session()->regenerate();
        // return redirect()->intended(RouteServiceProvider::ADMIN_DASHBOARD);
    // }

    // public function findUsername()
    // {
    //     $login = request()->input('username');

    //     $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    //     request()->merge([$fieldType => $login]);
    //     return $fieldType;
    // }

    // public function username()
    // {
    //     return $this->username;
    // }


    public function logout(Request $request){
       
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $notify[] = ['success', 'You have been logged out.'];
        return to_route('home')->withNotify($notify);
    }
}
