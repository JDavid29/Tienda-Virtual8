<?php

use App\Http\Livewire\Wishlist;
use App\Http\Livewire\Admin\Login;
use App\Http\Livewire\ListProduct;
use App\Http\Livewire\HomeComponent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaypalController;
use App\Http\Livewire\Shop\IndexComponent;
use App\Http\Controllers\ContactController;
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

// Ruta alternativa para "Inicio"
Route::get('/inicio', HomeComponent::class)->name('inicio');

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

// RUTA DE LOGIN CLIENTE
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

// PRODUCTO NORMAL
// Ruta para producto único que recibe el id del producto y lo pasa a la vista
Route::get('/single-product/{id}', function($id){
    return view('ProductSingle.Single_Product', compact('id'));
})->name('single-product');

// Rutas de los comercios con barra lateral izquierda
Route::get('/shopleftsidebar', function () {
    return view('Shop.ShopLeftSidebar');
})->name('shopleftsidebar');

Route::get('/blogleftsidebar', function () {
    return view('blog.blogleftsidebar');
})->name('blogleftsidebar');

// Ruta de la página de comparar
Route::get('/compare', function () {
    return view('compare.Compare');
})->name('compare');

// RUTAS PÁGINAS ESTÁTICAS
Route::get('/about', function () {
    return view('about');
})->name('about');

// About Us (Livewire)
//Route::get('/about-us', \App\Http\Livewire\AboutUs::class)->name('about-us');
Route::get('/about-us', function () {
    return view('about.about-us');
})->name('about-us');

// RUTA: Wishlist
// URL pública: /wishlist
// Componente Livewire: App\Http\Livewire\Wishlist (alias: 'wishlist')
// Vista pública: resources/views/wishlist/index.blade.php (extiende layouts.toolbar)
// Vista Livewire: resources/views/livewire/wishlist.blade.php (plantilla que renderiza el componente)
// Nota: puedes llamar al componente desde cualquier Blade con: @livewire('wishlist')
Route::get('/wishlist', function(){
    return view('wishlist.index');
})->name('wishlist');

// RUTA PARA CONTACTO
//esta ruta se esta obviando en favor de la ruta con controlador y metodo store para soporte post
Route::get('/contacto', function() {
    return view('contacto.contacto');
})->name('contacto');

// POST fallback for non-JS form submissions (progressive enhancement)
Route::post('/contacto', [ContactController::class, 'store'])->name('contacto.submit');

Route::get('/contact', function () {
    // Usar la vista de contacto existente (en español)
    return view('contacto.contacto');
})->name('contact');

// Rutas de comercio electrónico
Route::get('/shop3column', function(){
    return view('Shop.Shop3Column');
})->name('shop3column');

Route::get('/shop4column', function(){
    return view('Shop.Shop4Column');
})->name('shop4column');

Route::get('/faq', function(){
    return view('Faq.faq');
})->name('faq');

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

// RUTA FALLBACK y vista 404 temporalmente deshabilitadas mientras se trabaja en el proyecto.
// Si necesitas restaurarlas, quita los comentarios.
/*
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::get('/404', function () {
    return view('errors.404');
})->name('error.404');
*/
