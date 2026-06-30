<div>
    @if(session('message'))
        <div class="alert alert-success text-center mb-0" role="alert" style="border-radius:0;">
            {{ session('message') }}
        </div>
    @endif

    <div class="content-wraper pt-60 pb-60">
        <div class="container">

            {{-- Encabezado --}}
            <div class="row mb-40">
                <div class="col-12">
                    <div style="border-left:4px solid #fed700;padding-left:14px;">
                        <h3 style="font-weight:700;margin-bottom:4px;">🔍 Marcas / Proveedores</h3>
                        <p class="text-muted" style="margin:0;">Explora productos por proveedor.</p>
                    </div>
                </div>
            </div>

            {{-- Tarjetas de marcas --}}
            <div class="row">
                @forelse ($marcas as $marca)
                    <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-30">
                        <div wire:click="seleccionar({{ $marca->id }}, '{{ $marca->nombre }}')"
                             style="cursor:pointer;border:2px solid {{ $marcaSeleccionada == $marca->id ? '#fed700' : '#e5e5e5' }};
                                    border-radius:8px;padding:20px 10px;text-align:center;
                                    transition:all .2s;background:{{ $marcaSeleccionada == $marca->id ? '#fffbe6' : '#fff' }};">
                            <div style="width:60px;height:60px;border-radius:50%;background:#f5f5f5;
                                        display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                                <i class="fa fa-industry" style="font-size:24px;color:#fed700;"></i>
                            </div>
                            <h6 style="font-weight:700;font-size:13px;margin-bottom:2px;">{{ $marca->nombre }}</h6>
                            <small class="text-muted">{{ $marca->total }} producto(s)</small>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-40">
                        <p class="text-muted">No hay proveedores registrados con productos.</p>
                    </div>
                @endforelse
            </div>

            {{-- Productos de la marca seleccionada --}}
            @if($marcaSeleccionada)
                <div class="row mt-20 mb-20">
                    <div class="col-12 d-flex align-items-center justify-content-between">
                        <h4 style="font-weight:700;border-left:4px solid #fed700;padding-left:12px;">
                            Productos de: {{ $nombreMarca }}
                        </h4>
                        <button wire:click="limpiar" class="btn btn-sm btn-outline-secondary">
                            <i class="fa fa-times"></i> Ver todas las marcas
                        </button>
                    </div>
                </div>

                <div class="row">
                    @forelse ($productos as $producto)
                        <div class="col-lg-3 col-md-4 col-sm-6 mt-30">
                            <div class="single-product-wrap">
                                <div class="product-image">
                                    <a href="{{ route('single-product', $producto->id) }}">
                                        @include('partials.product-image', [
                                            'image'          => $producto->cover_img ?? null,
                                            'alt'            => $producto->nombre ?? 'Producto',
                                            'default'        => 'images/product/large-size/1.jpg',
                                            'attributesHtml' => ''
                                        ])
                                    </a>
                                </div>
                                <div class="product_desc">
                                    <div class="product_desc_info">
                                        <h4>
                                            <a class="product_name" href="{{ route('single-product', $producto->id) }}">
                                                {{ \Illuminate\Support\Str::limit($producto->nombre, 50) }}
                                            </a>
                                        </h4>
                                        <div class="price-box">
                                            <span class="new-price">Bs. {{ number_format($producto->precio, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="add-actions">
                                        <ul class="add-actions-link">
                                            <li class="add-cart active">
                                                <a href="#" wire:click.prevent="agregarCarrito({{ $producto->id }})">
                                                    Agregar al Carrito
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('single-product', $producto->id) }}" title="Ver detalle">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-40">
                            <p class="text-muted">Este proveedor no tiene productos disponibles.</p>
                        </div>
                    @endforelse
                </div>
            @endif

        </div>
    </div>
</div>
