<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('livewire.admin.login');
    }

    public function postLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // usa el guard que Voyager usa, normalmente "web"
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::guard('web')->user();

            if($user->role_id == 1 || $user->role_id == 3){
                // punto clave: manda al dashboard de Voyager
                return redirect()->route('voyager.dashboard');
                // equivalente válido: return redirect('/admin');
            }


            // punto clave: manda al dashboard de Voyager
            return redirect()->route('home');
            // equivalente válido: return redirect('/admin');
        }

        // login fail
        return back()->withErrors([
            'email' => 'Credenciales inválidas',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        // matar la sesión como recomienda Laravel
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // aquí decides dónde enviar al usuario
        return redirect('/'); // o '/tienda' o lo que quieras
    }
}
