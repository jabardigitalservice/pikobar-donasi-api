<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Libraries\LoginProxy;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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

    private $loginProxy;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(LoginProxy $loginProxy)
    {
        $this->loginProxy = $loginProxy;
        $this->middleware('guest')->except('logout');
    }

    /**
     * [OVERRIDE].
     *
     * Validate the user login request.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * [OVERRIDE].
     *
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    protected function attemptLogin(Request $request)
    {
        $valid = $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
        if ($valid) {
            $email = $request->input('email');
            $password = $request->input('password');
            $data = $this->loginProxy->attemptLogin($email, $password);
            $request->session()->put([
                'access_token' => $data['access_token'],
                'refresh_token' => $data['refresh_token'],
            ]);
            return true;
        }
        return false;
    }
}
