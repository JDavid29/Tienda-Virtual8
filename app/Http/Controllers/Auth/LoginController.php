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
        // Detectar rol principal vía Voyager: users.role_id o relación role
        $roleName = null;
        try {
            $roleName = optional($user->role)->name; // Voyager: relación belongsTo Role
            if (!$roleName && isset($user->role_id)) {
                // Fallback por IDs comunes (ajusta si tu instalación usa otros IDs)
                // 1: admin, 3: seller
                $map = [1 => 'admin', 3 => 'seller'];
                $roleName = $map[(int) $user->role_id] ?? null;
            }
        } catch (\Throwable $e) {
            $roleName = null;
        }

        $normalized = $roleName ? strtolower($roleName) : null;
        $isPanelUser = in_array($normalized, ['admin', 'seller'], true);

        if ($isPanelUser) {
            // Enviar a panel de Voyager
            return redirect()->route('voyager.dashboard');
        }

        // Usuarios normales: enviar a inicio de la tienda
        return redirect()->route('inicio');
    }
}
