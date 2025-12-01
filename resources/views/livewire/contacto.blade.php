<div>
    <!-- Begin Contact Main Page Area -->
    <div class="contact-main-page mt-60 mb-40 mb-md-40 mb-sm-40 mb-xs-40">
        <div class="container mb-60">
            <div id="google-map"></div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-5 offset-lg-1 col-md-12 order-1 order-lg-2">
                    <div class="contact-page-side-content">
                        <h3 class="contact-page-title">Contáctanos</h3>
                        <p class="contact-page-message mb-25">La claridad es también un proceso dinámico que se adapta a los cambios en los hábitos de lectura. Resulta sorprendente observar cómo la escritura gótica, que hoy consideramos poco clara, prefería las formas de escritura humanas.</p>
                        <div class="single-contact-block">
                            <h4><i class="fa fa-fax"></i> DIRECCIÓN</h4>
                            <p>000 Tarija, Yacuiba, CA 12345 – BO</p>
                        </div>
                        <div class="single-contact-block">
                            <h4><i class="fa fa-phone"></i> Teléfono</h4>
                            <p>Móvil: (+591) 60 456 789</p>
                            <p>Línea directa: 1009 678 456</p>
                        </div>
                        <div class="single-contact-block last-child">
                            <h4><i class="fa fa-envelope-o"></i> Correo electrónico</h4>
                            <p>yourmail@domain.com</p>
                            <p>support@hastech.company</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 order-2 order-lg-1">
                    <div class="contact-form-content pt-sm-55 pt-xs-55">
                        <h3 class="contact-page-title">Cuéntanos tu mensaje</h3>
                        <div class="contact-form">
                            <style>
                                /* Estilos locales para resaltar inputs inválidos */
                                .is-invalid {
                                    border: 1px solid #dc3545 !important;
                                    box-shadow: 0 0 0 .2rem rgba(220,53,69,.25) !important;
                                }
                                .invalid-feedback {
                                    color: #dc3545;
                                    font-size: 13px;
                                    margin-top: 6px;
                                    display: block;
                                }
                            </style>
                            <form id="contact-form" action="{{ route('contacto.submit') }}" method="POST" wire:submit.prevent="send" onsubmit="return contactClientValidate(event)">
                                @csrf
                                <noscript>
                                    <div class="alert alert-info">Javascript deshabilitado: el formulario se enviará mediante POST tradicional.</div>
                                </noscript>
                                @if (empty(config('app.key')))
                                    <div class="alert alert-warning">Advertencia: la clave de aplicación (APP_KEY) no está configurada. Algunas funciones de Livewire (validación incremental) están deshabilitadas hasta que se genere la clave.</div>
                                @endif
                                @if (session()->has('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                @if (session()->has('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif

                                <div class="form-group">
                                    <label>Su nombre <span class="required">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name" id="customername">
                                    <div id="contact-error-name" class="text-danger" style="display:none;font-size:13px;margin-top:6px;"></div>
                                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label>Tu correo electrónico <span class="required">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model.defer="email" id="customerEmail">
                                    <div id="contact-error-email" class="text-danger" style="display:none;font-size:13px;margin-top:6px;"></div>
                                    @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label>Sujeto</label> <!--o ASUNTO-->
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" wire:model.defer="subject" id="contactSubject">
                                    @error('subject') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-30">
                                    <label>Tu mensaje</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" wire:model.defer="message" id="contactMessage"></textarea>
                                    <div id="contact-error-message" class="text-danger" style="display:none;font-size:13px;margin-top:6px;"></div>
                                    @error('message') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <!-- Hidden Livewire trigger: used by client-side validator to call the component method without submitting the form natively -->
                                    <button id="contact-submit-hidden" type="button" wire:click="send" style="display:none"></button>
                                    <button id="contact-submit" type="submit" class="li-btn-3" wire:loading.attr="disabled">Enviar</button>
                                    <span wire:loading> Enviando...</span>
                                </div>
                            </form>
                        </div>
                        <!-- Floating notification container -->
                        <div id="contact-notification" style="position:fixed; top:90px; right:20px; z-index:2000; display:none; min-width:260px;">
                        </div>

                        <script>
                            (function(){
                                function escapeHtml(unsafe) {
                                    return unsafe
                                        .replace(/&/g, "&amp;")
                                        .replace(/</g, "&lt;")
                                        .replace(/>/g, "&gt;")
                                        .replace(/\"/g, "&quot;")
                                        .replace(/\'/g, "&#039;");
                                }

                                // Client-side validation function invoked on form submit.
                                window.contactClientValidate = function(event){
                                    // clear previous client errors
                                    var nameErrEl = document.getElementById('contact-error-name');
                                    var emailErrEl = document.getElementById('contact-error-email');
                                    var msgErrEl = document.getElementById('contact-error-message');
                                    if(nameErrEl) { nameErrEl.style.display = 'none'; nameErrEl.textContent = ''; }
                                    if(emailErrEl) { emailErrEl.style.display = 'none'; emailErrEl.textContent = ''; }
                                    if(msgErrEl) { msgErrEl.style.display = 'none'; msgErrEl.textContent = ''; }

                                    var name = (document.getElementById('customername')||{}).value || '';
                                    var email = (document.getElementById('customerEmail')||{}).value || '';
                                    var message = (document.getElementById('contactMessage')||{}).value || '';
                                    var valid = true;

                                    if(!name.trim() || name.trim().length < 2){
                                        if(nameErrEl){ nameErrEl.textContent = 'Por favor ingresa tu nombre (mínimo 2 caracteres).'; nameErrEl.style.display = 'block'; }
                                        valid = false;
                                    }

                                    // simple email regex (client-side only)
                                    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                                    if(!emailPattern.test(email)){
                                        if(emailErrEl){ emailErrEl.textContent = 'Por favor ingresa un correo válido.'; emailErrEl.style.display = 'block'; }
                                        valid = false;
                                    }

                                    if(!message.trim() || message.trim().length < 10){
                                        if(msgErrEl){ msgErrEl.textContent = 'Escribe un mensaje de al menos 10 caracteres.'; msgErrEl.style.display = 'block'; }
                                        valid = false;
                                    }

                                    if(!valid){
                                        // Prevent native submit and Livewire handling when client validation fails
                                        try{ event.preventDefault(); event.stopImmediatePropagation(); }catch(e){}
                                        // focus first invalid field
                                        if(!name.trim() || name.trim().length < 2) { document.getElementById('customername').focus(); }
                                        else if(!emailPattern.test(email)) { document.getElementById('customerEmail').focus(); }
                                        else { document.getElementById('contactMessage').focus(); }
                                        return false;
                                    }

                                    // Prevent native form POST and trigger Livewire via hidden button
                                    try{ event.preventDefault(); event.stopImmediatePropagation(); }catch(e){}
                                    var hidden = document.getElementById('contact-submit-hidden');
                                    if(hidden){ hidden.click(); }
                                    return false;
                                };

                                window.addEventListener('contact-notification', event => {
                                    const detail = event.detail || {};
                                    const type = detail.type === 'success' ? 'success' : 'danger';
                                    const message = detail.message || '';
                                    const container = document.getElementById('contact-notification');
                                    if (!container) return;

                                    container.innerHTML = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">'
                                        + escapeHtml(message)
                                        + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                        + '<span aria-hidden="true">&times;</span></button></div>';
                                    container.style.display = 'block';

                                    // Auto close after 5 seconds
                                    setTimeout(function(){
                                        const alertEl = container.querySelector('.alert');
                                        if (alertEl) {
                                            try { $(alertEl).alert('close'); } catch(e) { alertEl.remove(); }
                                        }
                                    }, 5000);
                                });
                            })();
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact Main Page Area End Here -->
</div>
