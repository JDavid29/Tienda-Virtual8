<?php

use App\Http\Livewire\Admin\Login;
use App\Http\Livewire\ListProduct;
use App\Http\Livewire\HomeComponent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaypalController;
use App\Http\Livewire\Shop\IndexComponent;
use App\Http\Livewire\Shop\CheckoutComponent;
use App\Http\Livewire\Shop\RegisterComponent;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Livewire\Shop\Cart\IndexComponent as CartIndexComponent;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// RUTAS PÚBLICAS
Route::get('/', HomeComponent::class)->name('home');

// Ruta de tienda
Route::get('/shop', IndexComponent::class)->name('shop.index');

// Ruta de productos
Route::get('/products', ListProduct::class)->name('list.product');

// Ruta de artículo
Route::get('/article', function () {
    return view('article.index');
})->name('article');

// Ruta de smartphone
Route::get('/smartphone', function () {
    return view('smartphone.index');
})->name('smartphone');

// RUTAS DE AUTENTICACIÓN CLIENTE
Route::get('/register', function () {
    return view('auth.register-client');
})->name('register');

Route::get('/login-client', function () {
    return view('auth.login-client');
})->name('login.client');

// RUTAS DE CARRITO Y COMPRAS
Route::get('/cart', CartIndexComponent::class)->name('cart');
Route::get('/checkout', CheckoutComponent::class)->name('checkout');

// RUTAS DE PAGO PAYPAL
Route::get('/paypal/checkout/{order}', [PaypalController::class, 'getExpressCheckout'])
    ->name('paypal.checkout');

Route::get('/paypal-success/{order}', [PaypalController::class, 'getExpressCheckoutSuccess'])
    ->name('paypal.success');

Route::get('/paypal-cancel', [PaypalController::class, 'calcelPage'])
    ->name('paypal.cancel');

// RUTA DE REGISTRO DE TIENDA
Route::get('/register-shop', RegisterComponent::class)->name('register.shop');

// RUTAS PÁGINAS ESTÁTICAS
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// RUTAS DE CATEGORÍAS
Route::get('/category/portatiles', function () {
    return view('categories.portatiles');
})->name('category.portatiles');

Route::get('/category/smartphones', function () {
    return view('categories.smartphones');
})->name('category.smartphones');

Route::get('/category/tablets', function () {
    return view('categories.tablets');
})->name('category.tablets');

Route::get('/category/accesorios', function () {
    return view('categories.accesorios');
})->name('category.accesorios');

// RUTAS DE ADMINISTRACIÓN - CORREGIDAS
Route::get('/admin/login', [LoginController::class, 'index'])->name('admin.login');// ✅ CORREGIDO
Route::post('/admin/login', [LoginController::class, 'postLogin'])->name('postlogin');

// GRUPO DE RUTAS DE VOYAGER ADMIN
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    // override/replace Voyager's admin register route to render HomeComponent
    Route::post('logout', [LoginController::class, 'logout'])->name('voyager.logout');
});

// RUTA FALLBACK TEMPORAL
Route::fallback(function () {
    return redirect('/');
});
