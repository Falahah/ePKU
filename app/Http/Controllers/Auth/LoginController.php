<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
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

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */// In your LoginController
     public function login(Request $request)
     {
         $credentials = $request->only('username', 'password');
     
         // Check if "Remember Me" checkbox is checked
         $remember = $request->has('remember');
     
         // Attempt to authenticate the user with the provided credentials
         if (Auth::attempt($credentials, $remember)) {
             $user = Auth::user();
     
             if ($user->role == 'student' || $user->role == 'staff') {
                 return redirect()->intended('/home'); 
             } else {
                 Auth::logout();
                 return redirect()->back()->with('status', 'Invalid username or password');
             }
         }
     
         // Authentication failed
         return redirect()->back()->with('status', 'Invalid username or password');
     }
     
     
    /**
     * Show the application's admin login form.
     *
     * @return \Illuminate\View\View
     */
    public function showAdminLoginForm()
    {
        return view('auth.admin-login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    /**
     * Handle an admin login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function adminLogin(Request $request)
    {

        $credentials = $request->only('username', 'password');
        $credentials['role'] = 'admin';

        if (Auth::attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->back()->with('status', 'Invalid admin credentials');
    }

    public function adminLogout(Request $request)
    {
        $isAdmin = $request->user() && $request->user()->is_admin;
    
        Auth::guard('web')->logout();
        $request->session()->invalidate(); // Manually invalidate the session
    
        // Redirect based on admin status
        return $isAdmin ? redirect()->route('admin.home') : redirect()->route('home');
    }            
}
