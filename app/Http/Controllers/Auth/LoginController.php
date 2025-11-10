<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    //Metodo para redireccionar a la vista admin ERROR
    //protected function redirectTo()
    //{
        //if (auth()->user()->roles->first()->name == 'Admin') {
        //    return '/home';
        //}else if (auth()->user()->roles->first()->name == 'Estudiante') {
        //    return '/dashboard';
        //}
        //return $redirectTo;
    //}

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
