@extends('layouts.toolbar')
@section('content')

<style>
.auth-wrapper {
    background: #f7f7f7;
    padding: 60px 15px;
    min-height: 80vh;
}
.auth-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.10);
    max-width: 440px;
    margin: 0 auto;
}
.auth-card-header {
    background: #242424;
    padding: 32px 40px 24px;
    text-align: center;
    border-radius: 12px 12px 0 0;
}
.auth-icon {
    width: 64px;
    height: 64px;
    background: #fed700;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 14px;
}
.auth-icon i { font-size: 28px; color: #242424; }
.auth-card-header h2 { color: #fff; font-size: 24px; font-weight: 700; margin: 0 0 4px; }
.auth-card-header p  { color: #999; font-size: 13px; margin: 0; }
.auth-card-body { padding: 32px 40px 36px; }

.auth-field { margin-bottom: 20px; }
.auth-field label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: #555;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.auth-input-wrap { position: relative; }
.auth-input-wrap i {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    color: #bbb;
    font-size: 14px;
    pointer-events: none;
}
.auth-input-wrap input {
    display: block;
    width: 100%;
    border: 1.5px solid #e2e2e2;
    border-radius: 7px;
    padding: 11px 14px 11px 40px;
    font-size: 14px;
    color: #333;
    background: #fafafa;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
    box-sizing: border-box;
}
.auth-input-wrap input:focus {
    border-color: #fed700;
    box-shadow: 0 0 0 3px rgba(254,215,0,0.18);
    background: #fff;
}
.auth-field .text-danger { font-size: 12px; margin-top: 4px; display: block; }

.auth-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 22px;
    font-size: 13px;
    color: #666;
}
.auth-options a { color: #555; font-size: 13px; }
.auth-options a:hover { color: #fed700; }
.auth-options label { margin: 0; cursor: pointer; display: flex; align-items: center; gap: 6px; }

.auth-btn {
    display: block;
    width: 100%;
    background: #242424;
    color: #fff;
    border: none;
    border-radius: 7px;
    padding: 13px;
    font-size: 15px;
    font-weight: 700;
    letter-spacing: 0.4px;
    cursor: pointer;
    transition: background .2s, color .2s;
    text-align: center;
}
.auth-btn:hover { background: #fed700; color: #242424; }

.auth-footer {
    text-align: center;
    margin-top: 22px;
    font-size: 14px;
    color: #888;
}
.auth-footer a {
    color: #242424;
    font-weight: 700;
    text-decoration: none;
    border-bottom: 2px solid #fed700;
}
.auth-footer a:hover { color: #fed700; }
</style>

<div class="auth-wrapper">

    @if(session('info'))
        <div class="alert alert-info" style="max-width:440px;margin:0 auto 20px;">{{ session('info') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success" style="max-width:440px;margin:0 auto 20px;">{{ session('success') }}</div>
    @endif

    <div class="auth-card">
        <div class="auth-card-header">
            <div class="auth-icon">
                <i class="fa fa-user"></i>
            </div>
            <h2>Bienvenido</h2>
            <p>Inicia sesión para continuar</p>
        </div>

        <div class="auth-card-body">
            <form method="POST" action="{{ route('login.client.submit') }}">
                @csrf

                <div class="auth-field">
                    <label>Correo electrónico</label>
                    <div class="auth-input-wrap">
                        <i class="fa fa-envelope"></i>
                        <input type="email" name="email" value="{{ old('email') }}"
                               placeholder="correo@ejemplo.com" required autofocus>
                    </div>
                    @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="auth-field">
                    <label>Contraseña</label>
                    <div class="auth-input-wrap">
                        <i class="fa fa-lock"></i>
                        <input type="password" name="password" placeholder="••••••••" required>
                    </div>
                    @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="auth-options">
                    <label>
                        <input type="checkbox" name="remember">
                        Recuérdame
                    </label>
                    <a href="#">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="auth-btn">
                    <i class="fa fa-sign-in" style="margin-right:6px;"></i> Iniciar sesión
                </button>
            </form>

            <div class="auth-footer">
                ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí</a>
            </div>
        </div>
    </div>

</div>
@endsection
