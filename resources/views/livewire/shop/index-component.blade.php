<div>
    {{-- Because she competes with no one, no one can compete with her. --}}
    <div class="container">
        <div class="row">
            @foreach($productos as $producto)
            <div class="col-md-4">
                <div class="card">
                    <img class="card-img-top" src="{{ asset('image_default.png')
                    }}" alt="Card image cap">
                    <div class="card-body">
                        <h4 class="card-title">{{$producto->nombre}}</h4>
                        <p class="card-text">{{$producto->descripcion}}</p>
                    </div>
                    <div class="card-body">

                        <button type="button" class="btn btn-primary"
                        wire:click="addToCart({{ $producto->id }})"
                        >Agregar al carrito</button>

                        <!-- <a href="#" class="card-link">Another link</a> -->
                    </div>
                </div>
            </div>
            @endforeach

        </div>
        <div class="d-flex justify-content-center">
            {{$productos->links()}}
        </div>
    </div>

</div>
