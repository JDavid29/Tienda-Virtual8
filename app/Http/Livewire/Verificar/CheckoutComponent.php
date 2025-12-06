<?php

namespace App\Http\Livewire\Verificar;

use Livewire\Component;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Producto;

class CheckoutComponent extends Component
{
    public $cartItems = [];
    public $subtotal = 0;
    public $discount = 0;
    public $cartTotal = 0;
    public $currencySymbol = 'Bs';

    // login form properties
    public $login_identifier = '';
    public $login_password = '';
    public $loginError = null;
    public $loginSuccess = null;

    // checkout form fields
    public $billing_fullname = '';
    public $billing_address = '';
    public $billing_city = '';
    public $billing_state = '';
    public $billing_zipcode = '';
    public $billing_phone = '';

    public $shipping_fullname = '';
    public $shipping_address = '';
    public $shipping_city = '';
    public $shipping_state = '';
    public $shipping_zipcode = '';
    public $shipping_phone = '';

    public $notes = '';
    public $payment_method = 'cash_on_delivery';

    public $orderSuccess = null;
    public $orderError = null;

    protected $listeners = [
        'cartUpdated' => 'updateCart',
    ];

    public function mount()
    {
        $this->updateCart();
        // allow overriding via config if present
        $this->currencySymbol = config('app.currency_symbol', $this->currencySymbol);
    }

    public function updateCart()
    {
        if (auth()->check()) {
            $this->cartItems = Cart::session(auth()->id())->getContent()->values()->map(
                function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                        'attributes' => $item->attributes,
                    ];
                }
            )->toArray();
            $this->subtotal = Cart::session(auth()->id())->getSubTotal();
            $this->cartTotal = Cart::session(auth()->id())->getTotal();
        } else {
            $this->cartItems = Cart::getContent()->toArray();
            $this->subtotal = Cart::getSubTotal();
            $this->cartTotal = Cart::getTotal();
        }
    }

    /**
     * Attempt to log the user in via username or email without page reload.
     */
    public function login()
    {
        $this->reset(['loginError', 'loginSuccess']);

        $this->validate([
            'login_identifier' => 'required|string',
            'login_password' => 'required|string|min:6',
        ], [
            'login_identifier.required' => 'El usuario o correo es requerido.',
            'login_password.required' => 'La contraseña es requerida.',
            'login_password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        $identifier = trim($this->login_identifier);
        $password = $this->login_password;

        try {
            // First, try treating as email
            if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
                if (Auth::attempt(['email' => $identifier, 'password' => $password])) {
                    $this->loginSuccess = 'Inicio de sesión correcto.';
                    $this->afterSuccessfulLogin();
                    return;
                }
            }

            // Otherwise, try lookup by name (username)
            $user = User::where('name', $identifier)->first();
            if ($user) {
                if (Auth::attempt(['email' => $user->email, 'password' => $password])) {
                    $this->loginSuccess = 'Inicio de sesión correcto.';
                    $this->afterSuccessfulLogin();
                    return;
                }
            }

            $this->loginError = 'Credenciales inválidas. Verifique e intente nuevamente.';
        } catch (\Throwable $e) {
            \Log::error('Login attempt failed: '.$e->getMessage());
            $this->loginError = 'Ocurrió un error al intentar iniciar sesión.';
        }
    }

    protected function afterSuccessfulLogin()
    {
        // refresh cart for the authenticated user
        $this->updateCart();

        // emit event so other components (toolbar/cart counters) refresh
        $this->emit('cartUpdated');

        // clear password for safety
        $this->login_password = '';
    }

    public function rules()
    {
        return [
            'billing_fullname' => 'required|string',
            'billing_address' => 'required|string',
            'billing_city' => 'required|string',
            'billing_state' => 'required|string',
            'billing_zipcode' => 'required|string',
            'billing_phone' => 'required|string',
            'shipping_fullname' => 'required|string',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_state' => 'required|string',
            'shipping_zipcode' => 'required|string',
            'shipping_phone' => 'required|string',
            'payment_method' => 'required|in:cash_on_delivery,paypal,stripe,card',
        ];
    }

    public function placeOrder()
    {
        $this->reset(['orderError', 'orderSuccess']);

        if (! Auth::check()) {
            $this->orderError = 'Debe iniciar sesión antes de realizar el pedido.';
            return;
        }

        $this->validate();

        // get cart totals and items
        $userId = auth()->id();
        if ($userId) {
            $cartContent = Cart::session($userId)->getContent();
            $subtotal = Cart::session($userId)->getSubTotal();
            $total = Cart::session($userId)->getTotal();
        } else {
            $cartContent = Cart::getContent();
            $subtotal = Cart::getSubTotal();
            $total = Cart::getTotal();
        }

        if ($cartContent->isEmpty()) {
            $this->orderError = 'El carrito está vacío.';
            return;
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $userId,
                'order_number' => strtoupper(uniqid('ORD')),
                'status' => 'pending',
                'item_count' => $cartContent->sum('quantity'),
                'is_paid' => false,
                'payment_method' => $this->payment_method,

                'shipping_fullname' => $this->shipping_fullname,
                'shipping_address' => $this->shipping_address,
                'shipping_city' => $this->shipping_city,
                'shipping_state' => $this->shipping_state,
                'shipping_zipcode' => $this->shipping_zipcode,
                'shipping_phone' => $this->shipping_phone,

                'notes' => $this->notes,

                'billing_fullname' => $this->billing_fullname,
                'billing_address' => $this->billing_address,
                'billing_city' => $this->billing_city,
                'billing_state' => $this->billing_state,
                'billing_zipcode' => $this->billing_zipcode,
                'billing_phone' => $this->billing_phone,

                'total' => $total,
            ]);

            // insert order items
            foreach ($cartContent as $item) {
                DB::table('order_items')->insert([
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'order_id' => $order->id,
                    'product_id' => $item->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // clear cart for user
            if ($userId) {
                Cart::session($userId)->clear();
            } else {
                Cart::clear();
            }

            DB::commit();

            $this->orderSuccess = 'Pedido creado correctamente y está pendiente. Número de pedido: ' . $order->order_number;
            // refresh cart and totals
            $this->updateCart();
            $this->emit('cartUpdated');
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Place order failed: ' . $e->getMessage());
            $this->orderError = 'Ocurrió un error al crear el pedido. Intenta de nuevo.';
        }
    }

    public function render()
    {
        return view('livewire.verificar.checkout-component');
    }
}
