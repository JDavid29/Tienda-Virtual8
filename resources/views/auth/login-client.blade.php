@extends('layouts.toolbar')
@section('content')
<div class="page-section mb-60">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-xs-12 col-lg-6 mb-30">
                <!-- Formulario de Inicio de Sesión -->
                <form method="POST" action="{{ route('login.client.submit') }}">
                    @csrf
                    <div class="login-form" style="border: 1px solid #ddd; padding: 20px; border-radius: 6px;">
                        <div class="mb-15">
                            <span class="li-button li-button-dark li-button-sm" style="display:inline-block">
                                <i class="fa fa-sign-in"></i> Área de inicio de sesión
                            </span>
                        </div>
                        <h4 class="login-title"><i class="fa fa-user"></i> Iniciar sesión</h4>
                        <div class="row">
                            <div class="col-md-12 col-12 mb-20">
                                <label>Correo electrónico*</label>
                                <input class="mb-0" type="email" name="email" value="{{ old('email') }}" placeholder="Correo electrónico" required autofocus>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12 mb-20">
                                <label>Contraseña</label>
                                <input class="mb-0" type="password" name="password" placeholder="Contraseña" required>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-8">
                                <div class="check-box d-inline-block ml-0 ml-md-2 mt-10">
                                    <input type="checkbox" id="remember_me" name="remember">
                                    <label for="remember_me">Recuérdame</label>
                                </div>
                            </div>
                            <div class="col-md-4 mt-10 mb-20 text-left text-md-right">
                                <a href="#">¿Olvidaste tu contraseña?</a>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="register-button li-button li-button-dark li-button-fullwidth mt-0">Iniciar sesión</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-6 col-xs-12">
                <form method="POST" action="{{ route('register.submit') }}">
                    @csrf
                    <div class="login-form" style="border: 1px solid #4a90e2; padding: 20px; border-radius: 6px;">
                        <div class="mb-15">
                            <span class="li-button li-button-sm" style="display:inline-block">
                                <i class="fa fa-user-plus"></i> Área de registro de usuario
                            </span>
                        </div>
                        <h4 class="login-title"><i class="fa fa-id-card"></i> Registrarse</h4>
                        <div class="row">
                            <div class="col-md-6 col-12 mb-20">
                                <label>Nombre</label>
                                <input class="mb-0" type="text" name="name" value="{{ old('name') }}" placeholder="Nombre" required>
                            </div>
                            <div class="col-md-6 col-12 mb-20">
                                <label>Apellido</label>
                                <input class="mb-0" type="text" name="last_name" placeholder="Apellido" required>
                            </div>
                            {{-- telefono y nacimiento --}}
                            <div class="col-md-6 col-12 mb-20">
                                <label>Teléfono</label>
                                <input class="mb-0" type="tel" name="phone" placeholder="Teléfono" pattern="[0-9]{10,15}" required>
                                <small>Formato: 10 a 15 dígitos, solo números.</small>
                            </div>
                            <div class="col-md-6 col-12 mb-20">
                                <label>Fecha de Nacimiento</label>
                                <input class="mb-0" type="date" name="birthdate" placeholder="Fecha de Nacimiento" required>
                                <small>Selecciona tu fecha de nacimiento.</small>
                            </div>
                            {{-- fin telefono y nacimiento --}}
                            <div class="col-md-12 mb-20">
                                <label>Correo electrónico*</label>
                                <input class="mb-0" type="email" name="email" value="{{ old('email') }}" placeholder="Correo electrónico" required>
                            </div>
                            <div class="col-md-6 mb-20">
                                <label>Contraseña</label>
                                <input class="mb-0" type="password" name="password" placeholder="Contraseña" required>
                            </div>
                            <div class="col-md-6 mb-20">
                                <label>Confirmar contraseña</label>
                                <input class="mb-0" type="password" name="password_confirmation" placeholder="Confirmar contraseña" required>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="register-button li-button li-button-fullwidth mt-0">Registrarse</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
