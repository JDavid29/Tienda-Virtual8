<div>
    <!--Checkout Area Start-->
    <div class="checkout-area pt-60 pb-30">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="coupon-accordion">
                        <!--Accordion Start-->
                        <h3>¿Ya eres cliente? <span id="showlogin"> Haz clic aquí para iniciar sesión.</span></h3>
                        <div id="checkout-login" class="coupon-content">
                            <div class="coupon-info">
                                <p class="coupon-text">Los cupones son extraordinarios al comprar. No pierdas la oportunidad</p>
                                <p class="login-instruction">Si aún no ha iniciado sesión, puede hacerlo en este formulario y continuamos luego con el siguiente paso.</p>
                                <form wire:submit.prevent="login">
                                    @if($loginError)
                                        <div class="alert alert-danger">{{ $loginError }}</div>
                                    @endif
                                    @if($loginSuccess)
                                        <div class="alert alert-success">{{ $loginSuccess }}</div>
                                    @endif
                                    <p class="form-row-first">
                                        <label>Nombre de usuario o correo electrónico <span class="required">*</span></label>
                                        <input type="text" wire:model.defer="login_identifier">
                                        @error('login_identifier') <span class="text-danger">{{ $message }}</span> @enderror
                                    </p>
                                    <p class="form-row-last">
                                        <label>Contraseña  <span class="required">*</span></label>
                                        <input type="password" wire:model.defer="login_password">
                                        @error('login_password') <span class="text-danger">{{ $message }}</span> @enderror
                                    </p>
                                    <p class="form-row">
                                        <button type="submit" class="btn btn-primary">Iniciar sesión</button>
                                        <label>
                                            <input type="checkbox"> Acuérdate de mí
                                        </label>
                                    </p>
                                    <p class="lost-password"><a href="#">¿Perdiste tu contraseña?</a></p>
                                </form>
                            </div>
                        </div>
                        <!--Accordion End-->
                        <!--Accordion Start-->
                        <h3>¿Tienes un cupón?  <span id="showcoupon">Haz clic aquí para introducirlo.</span></h3>
                        <div id="checkout_coupon" class="coupon-checkout-content">
                            <div class="coupon-info">
                                <form action="#">
                                    <p class="checkout-coupon">
                                        <input placeholder="Código de cupón" type="text">
                                        <input value="Aplicar cupón" type="submit">
                                    </p>
                                </form>
                            </div>
                        </div>
                        <!--Accordion End-->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-12">
                    <form wire:submit.prevent="placeOrder">
                        <div class="checkbox-form">
                            <h3>Detalles de facturación</h3>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="country-select clearfix">
                                        <label>País <span class="required">*</span></label>
                                        <select class="nice-select wide">
                                            <option data-display="Bangladesh">Boliva</option>
                                            <option value="ar">Argentina</option>
                                            <option value="rou">Romania</option>
                                            <option value="fr">French</option>
                                            <option value="eu">EEUU</option>
                                            <option value="aus">Australia</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>Nombre de pila <span class="required">*</span></label>
                                        <input placeholder="" type="text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>Apellido <span class="required">*</span></label>
                                        <input placeholder="" type="text">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="checkout-form-list">
                                        <label>Nombre de la empresa</label>
                                        <input placeholder="" type="text">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="checkout-form-list">
                                        <label>DIRECCIÓN <span class="required">*</span></label>
                                        <input placeholder="Direccion de calle" type="text">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="checkout-form-list">
                                        <input placeholder="Apartamento, suite, unidad, etc. (opcional)" type="text">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="checkout-form-list">
                                        <label>Pueblo / Ciudad <span class="required">*</span></label>
                                        <input type="text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>Estado / Condado <span class="required">*</span></label>
                                        <input placeholder="" type="text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>Código postal / Zip <span class="required">*</span></label>
                                        <input placeholder="" type="text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>Dirección de correo electrónico <span class="required">*</span></label>
                                        <input placeholder="" type="email">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkout-form-list">
                                        <label>Teléfono  <span class="required">*</span></label>
                                        <input type="text">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="checkout-form-list create-acc">
                                        <input id="cbox" type="checkbox">
                                        <label>¿Crear una cuenta?</label>
                                    </div>
                                    <div id="cbox-info" class="checkout-form-list create-account">
                                        <p>Crea una cuenta ingresando la información a continuación. Si eres un cliente recurrente, por favor inicia sesión en la parte superior de la página.</p>
                                        <label>Contraseña de la cuenta  <span class="required">*</span></label>
                                        <input placeholder="contraseña" type="password">
                                    </div>
                                </div>
                            </div>
                            <div class="different-address">
                                <div class="ship-different-title">
                                    <h3>
                                        <label>¿Enviar a una dirección diferente?</label>
                                        <input id="ship-box" type="checkbox">
                                    </h3>
                                </div>
                                <div id="ship-box-info" class="row">
                                    <div class="col-md-12">
                                        <div class="country-select clearfix">
                                            <label>Pais <span class="required">*</span></label>
                                            <select class="nice-select wide">
                                                <option data-display="Bangladesh">Bolivia</option>
                                                <option value="uk">London</option>
                                                <option value="rou">Romania</option>
                                                <option value="fr">French</option>
                                                <option value="de">Germany</option>
                                                <option value="aus">Australia</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="checkout-form-list">
                                            <label>Nombre <span class="required">*</span></label>
                                            <input placeholder="" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="checkout-form-list">
                                            <label>Apellido <span class="required">*</span></label>
                                            <input placeholder="" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="checkout-form-list">
                                            <label>Nombre de la empresa</label>
                                            <input placeholder="" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="checkout-form-list">
                                            <label>Direccion <span class="required">*</span></label>
                                            <input placeholder="Dirección de calle" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="checkout-form-list">
                                            <input placeholder="Apartamento, suite, unidad, etc. (opcional)" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="checkout-form-list">
                                            <label>Pueblo / Ciudad <span class="required">*</span></label>
                                            <input type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="checkout-form-list">
                                            <label>Estado / Departamento <span class="required">*</span></label>
                                            <input placeholder="" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="checkout-form-list">
                                            <label>Codigo postal / Zip <span class="required">*</span></label>
                                            <input placeholder="" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="checkout-form-list">
                                            <label>Correo Electrónico <span class="required">*</span></label>
                                            <input placeholder="" type="email">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="checkout-form-list">
                                            <label>Teléfono  <span class="required">*</span></label>
                                            <input type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="order-notes">
                                    <div class="checkout-form-list">
                                        <label>Notas del pedido</label>
                                        <textarea id="checkout-mess" cols="30" rows="10" placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="your-order">
                        <h3>Su pedido</h3>
                        <div class="your-order-table table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="cart-product-name">Producto</th>
                                        <th class="cart-product-total">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($cartItems as $item)
                                        <tr class="cart_item">
                                            <td class="cart-product-name">
                                                {{ $item['name'] }}
                                                <strong class="product-quantity"> × {{ $item['quantity'] }}</strong>
                                            </td>
                                            <td class="cart-product-total">
                                                <span class="amount">{{ $currencySymbol }}{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2">No hay productos en el carrito.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr class="cart-subtotal">
                                        <th>Subtotal del carrito</th>
                                        <td><span class="amount">{{ $currencySymbol }}{{ number_format($subtotal ?? 0, 2) }}</span></td>
                                    </tr>
                                    <tr class="order-total">
                                        <th>Total del pedido</th>
                                        <td><strong><span class="amount">{{ $currencySymbol }}{{ number_format($cartTotal ?? $subtotal ?? 0, 2) }}</span></strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="payment-method">
                            @if($orderError)
                                <div class="alert alert-danger">{{ $orderError }}</div>
                            @endif
                            @if($orderSuccess)
                                <div class="alert alert-success">{{ $orderSuccess }}</div>
                            @endif
                            <div class="form-group mb-3">
                                <label>Método de pago</label>
                                <select wire:model="payment_method" class="form-control">
                                    <option value="cash_on_delivery">Pago contra entrega</option>
                                    <option value="paypal">PayPal</option>
                                    <option value="stripe">Stripe</option>
                                    <option value="card">Tarjeta</option>
                                </select>
                            </div>
                            <div class="payment-accordion">
                                <div id="accordion">
                                    <div class="card">
                                    <div class="card-header" id="#payment-1">
                                        <h5 class="panel-title">
                                        <a class="" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Transferencia bancaria directa.
                                        </a>
                                        </h5>
                                    </div>
                                    <div id="collapseOne" class="collapse show" data-parent="#accordion">
                                        <div class="card-body">
                                        <p>Realice su pago directamente en nuestra cuenta bancaria. Utilice su número de pedido como referencia de pago. Su pedido no se enviará hasta que los fondos se hayan acreditado en nuestra cuenta.</p>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="card">
                                    <div class="card-header" id="#payment-2">
                                        <h5 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Pago con cheque
                                        </a>
                                        </h5>
                                    </div>
                                    <div id="collapseTwo" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                        <p>Realice su pago directamente en nuestra cuenta bancaria. Utilice su número de pedido como referencia de pago. Su pedido no se enviará hasta que los fondos se hayan acreditado en nuestra cuenta.</p>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="card">
                                    <div class="card-header" id="#payment-3">
                                        <h5 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            PayPal
                                        </a>
                                        </h5>
                                    </div>
                                    <div id="collapseThree" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                        <p>Realice su pago directamente en nuestra cuenta bancaria. Utilice su número de pedido como referencia de pago. Su pedido no se enviará hasta que los fondos se hayan acreditado en nuestra cuenta.</p>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="order-button-payment">
                                    <button type="button" wire:click="placeOrder" class="btn btn-success">Realizar pedido</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Checkout Area End-->
</div>
