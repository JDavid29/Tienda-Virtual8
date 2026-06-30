@extends('layouts.toolbar')
@section('content')

<style>
.auth-wrapper {
    background: #f7f7f7;
    padding: 60px 0;
}
.auth-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.10);
    overflow: hidden;
}
.auth-card-header {
    background: #242424;
    padding: 36px 40px 28px;
    text-align: center;
}
.auth-card-header h2 {
    color: #fff;
    font-size: 26px;
    font-weight: 700;
    margin: 0 0 4px;
    letter-spacing: 0.5px;
}
.auth-card-header p {
    color: #aaa;
    font-size: 14px;
    margin: 0;
}
.auth-card-header .auth-icon {
    width: 60px;
    height: 60px;
    background: #fed700;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
}
.auth-card-header .auth-icon i {
    font-size: 26px;
    color: #242424;
}
.auth-card-body {
    padding: 36px 40px;
}
.auth-field {
    margin-bottom: 18px;
}
.auth-field label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #444;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.auth-field .input-icon-wrap {
    position: relative;
}
.auth-field .input-icon-wrap i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #aaa;
    font-size: 14px;
}
.auth-field input {
    width: 100%;
    border: 1.5px solid #e0e0e0;
    border-radius: 7px;
    padding: 10px 14px 10px 38px;
    font-size: 14px;
    color: #333;
    background: #fafafa;
    transition: border-color .2s, box-shadow .2s;
    outline: none;
}
.auth-field input:focus {
    border-color: #fed700;
    box-shadow: 0 0 0 3px rgba(254,215,0,0.15);
    background: #fff;
}
.auth-field .field-hint { font-size: 11px; color: #999; margin-top: 3px; display: block; }
.auth-field .text-danger { font-size: 12px; margin-top: 3px; display: block; }
.section-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #bbb;
    margin: 8px 0 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.section-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #eee;
}
.auth-submit-btn {
    width: 100%;
    background: #242424;
    color: #fff;
    border: none;
    border-radius: 7px;
    padding: 13px;
    font-size: 15px;
    font-weight: 700;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: background .2s;
    margin-top: 6px;
}
.auth-submit-btn:hover { background: #fed700; color: #242424; }
.auth-divider {
    text-align: center;
    margin: 24px 0 0;
    font-size: 14px;
    color: #888;
}
.auth-divider a {
    color: #242424;
    font-weight: 700;
    text-decoration: none;
    border-bottom: 2px solid #fed700;
    padding-bottom: 1px;
}
.auth-divider a:hover { color: #fed700; }
</style>

<div class="auth-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-9 col-lg-7">

                <div class="auth-card">
                    <div class="auth-card-header">
                        <div class="auth-icon">
                            <i class="fa fa-user-plus"></i>
                        </div>
                        <h2>Crear cuenta</h2>
                        <p>Completa tus datos para registrarte</p>
                    </div>
                    <div class="auth-card-body">
                        <form method="POST" action="{{ route('register.submit') }}">
                            @csrf

                            <div class="section-label">Datos personales</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="auth-field">
                                        <label>Nombre</label>
                                        <div class="input-icon-wrap">
                                            <i class="fa fa-user"></i>
                                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Tu nombre" required>
                                        </div>
                                        @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="auth-field">
                                        <label>Apellido</label>
                                        <div class="input-icon-wrap">
                                            <i class="fa fa-user"></i>
                                            <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Tu apellido" required>
                                        </div>
                                        @error('last_name')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="auth-field">
                                        <label>Teléfono</label>
                                        <div class="input-icon-wrap">
                                            <i class="fa fa-phone"></i>
                                            <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="Ej: 70012345" pattern="[0-9]{10,15}" required>
                                        </div>
                                        <span class="field-hint">10 a 15 dígitos, solo números.</span>
                                        @error('phone')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="auth-field">
                                        <label>Fecha de nacimiento</label>
                                        <div class="input-icon-wrap">
                                            <i class="fa fa-calendar"></i>
                                            <input type="date" name="birthdate" value="{{ old('birthdate') }}" required>
                                        </div>
                                        @error('birthdate')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="section-label">Acceso a la cuenta</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="auth-field">
                                        <label>Correo electrónico</label>
                                        <div class="input-icon-wrap">
                                            <i class="fa fa-envelope"></i>
                                            <input type="email" name="email" value="{{ old('email') }}" placeholder="correo@ejemplo.com" required>
                                        </div>
                                        @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="auth-field">
                                        <label>Contraseña</label>
                                        <div class="input-icon-wrap">
                                            <i class="fa fa-lock"></i>
                                            <input type="password" name="password" placeholder="Mínimo 8 caracteres" required>
                                        </div>
                                        @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="auth-field">
                                        <label>Confirmar contraseña</label>
                                        <div class="input-icon-wrap">
                                            <i class="fa fa-lock"></i>
                                            <input type="password" name="password_confirmation" placeholder="Repite la contraseña" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="auth-submit-btn">
                                <i class="fa fa-user-plus" style="margin-right:6px;"></i> Crear cuenta
                            </button>
                        </form>

                        <div class="auth-divider">
                            ¿Ya tienes cuenta?
                            <a href="{{ route('login.client') }}">Inicia sesión aquí</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
