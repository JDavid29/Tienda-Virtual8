<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <div class="container">
        <div class="form-group">
            <label for="">Nombre completo</label>
            <input type="text" class="form-control @error('fullname') is-invalid @enderror"
            wire:model= "fullname">
            @error('fullname')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="">Region o estado</label>
            <input type="text" class="form-control @error('state') is-invalid @enderror"
            wire:model= "state">
            @error('state')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="">Ciudad o distrito</label>
            <input type="text" class="form-control @error('city') is-invalid @enderror"
            wire:model= "city">
            @error('city')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="">ZIP</label>
            <input type="text" class="form-control @error('zipcode') is-invalid @enderror"
            wire:model= "zipcode">
            @error('zipcode')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="">Direccion</label>
            <input type="text" class="form-control @error('address') is-invalid @enderror"
            wire:model= "address">
            @error('address')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="">Telefono</label>
            <input type="text" class="form-control @error('phone') is-invalid @enderror"
            wire:model= "phone">
            @error('phone')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-check">
            <input class="form-check-input" type="radio"
            wire:model="payment_method";
            name="exampleRadios" id="exampleRadios1" value="cash_on_delivery" checked>
            <label class="form-check-label" for="exampleRadios1">
            cash_on_delivery</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio"
            wire:model="payment_method"
            name="exampleRadios" value="paypal" id="exampleRadios2">
            <label class="form-check-label" for="exampleRadios2">
            Paypal</label>
        </div>

        <button type="button"
        wire:click="make_order()"
        class="btn btn-primary">Realizar pedido</button>
    </div>
</div>
