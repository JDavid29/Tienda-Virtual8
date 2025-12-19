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

    /**
     * Redirect users after successful authentication.
     * Admin -> panel administrativo (Voyager /admin)
     * Usuario -> vista del sistema opción "Todo" (route('inicio'))
     */
    protected function authenticated($request, $user)
    {
        // Si el usuario tiene rol Admin (Voyager roles) o el email coincide con patrón de admin
        try {
            $roleName = method_exists($user, 'roles') && $user->roles()->exists()
                ? optional($user->roles()->first())->name
                : null;
        } catch (\Throwable $e) {
            $roleName = null;
        }

        $isAdmin = false;
        if ($roleName) {
            $isAdmin = strcasecmp($roleName, 'admin') === 0;
        }
        // Heurística adicional por correo (opcional): si el email empieza con 'admin' o pertenece a dominio interno
        if (!$isAdmin && isset($user->email)) {
            $email = strtolower($user->email);
            $isAdmin = str_starts_with($email, 'admin@') || str_contains($email, '@empresa.local');
        }

        if ($isAdmin) {
            return redirect()->intended('/admin');
        }
        return redirect()->intended(route('inicio'));
    }
}
