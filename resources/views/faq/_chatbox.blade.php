<!-- Floating non-intrusive Chatbox for FAQ page only -->
<div id="faq-chatbox" aria-live="polite">
    <style>
        /* Scoped styles for FAQ chatbox (ids to avoid global collisions) */
        #faq-chatbox { position: fixed; right: 20px; bottom: 20px; z-index: 1200; font-family: inherit; }
        #faq-chat-toggle { background:#ff6f61; color:#fff; border:none; border-radius:28px; padding:10px 16px; box-shadow:0 6px 18px rgba(0,0,0,0.12); cursor:pointer; }
        #faq-chat-window { width:320px; max-width:calc(100vw - 40px); background:#fff; border-radius:8px; box-shadow:0 10px 30px rgba(0,0,0,0.12); overflow:hidden; margin-bottom:10px; }
        #faq-chat-window .faq-chat-header { background:#f7f7f7; padding:10px 12px; font-weight:600; }
        #faq-chat-window .faq-chat-body { padding:12px; font-size:14px; color:#333; }
        #faq-chat-window .faq-chat-form { padding:10px; border-top:1px solid #eee; }
        #faq-chat-window input[type="email"], #faq-chat-window textarea { width:100%; box-sizing:border-box; padding:8px 10px; margin-bottom:8px; border:1px solid #ddd; border-radius:4px; font-size:13px; }
        #faq-chat-window textarea { min-height:80px; resize:vertical; }
        #faq-chat-window .faq-chat-actions { display:flex; gap:8px; justify-content:flex-end; }
        #faq-chat-window .faq-chat-actions .btn { padding:8px 12px; border-radius:4px; border:none; cursor:pointer; }
        #faq-chat-window .btn-send { background:#28a745; color:#fff; }
        #faq-chat-window .btn-close { background:#6c757d; color:#fff; }
        #faq-chat-status { position:relative; display:block; padding:6px 12px; font-size:13px; color:#0b5; }
        @media (max-width:420px){ #faq-chatbox { right:12px; bottom:12px; } #faq-chat-window{ width:280px; } }
    </style>

    <div id="faq-chat-window" hidden>
        <div class="faq-chat-header">¿necesita más ayuda?</div>
        <div class="faq-chat-body">
            <p>Hola — si no encuentras lo que buscas, deja tu correo y un mensaje. Te responderemos lo antes posible.</p>
        </div>

        <form id="faq-chat-form" class="faq-chat-form" onsubmit="return false;">
            <input id="faq-chat-email" type="email" placeholder="Tu correo (opcional)" aria-label="correo" />
            <textarea id="faq-chat-message" placeholder="Escribe tu mensaje..." aria-label="mensaje"></textarea>
            <div class="faq-chat-actions">
                <button id="faq-chat-send" class="btn btn-send" type="button">Enviar</button>
                <button id="faq-chat-close" class="btn btn-close" type="button">Cerrar</button>
            </div>
            <div id="faq-chat-status" role="status" aria-live="polite" style="display:none"></div>
        </form>
    </div>

    <div style="text-align:right">
        <button id="faq-chat-toggle">¿necesita más ayuda?</button>
    </div>

    <script>
        (function(){
            var toggle = document.getElementById('faq-chat-toggle');
            var win = document.getElementById('faq-chat-window');
            var closeBtn = document.getElementById('faq-chat-close');
            var sendBtn = document.getElementById('faq-chat-send');
            var status = document.getElementById('faq-chat-status');

            function showStatus(msg, timeout){
                status.textContent = msg; status.style.display = 'block';
                if(timeout) setTimeout(function(){ status.style.display = 'none'; }, timeout);
            }

            toggle.addEventListener('click', function(){
                win.hidden = !win.hidden;
                if(!win.hidden){
                    document.getElementById('faq-chat-message').focus();
                }
            });
            closeBtn.addEventListener('click', function(){ win.hidden = true; });

            sendBtn.addEventListener('click', function(){
                var email = document.getElementById('faq-chat-email').value.trim();
                var msg = document.getElementById('faq-chat-message').value.trim();
                if(!msg){ showStatus('Por favor escribe un mensaje.', 3000); return; }

                // Non-invasive behavior: just acknowledge and clear fields.
                showStatus('Gracias — tu mensaje fue enviado (simulado).', 4000);
                document.getElementById('faq-chat-email').value = '';
                document.getElementById('faq-chat-message').value = '';
                // If you want to actually send this, integrate with Livewire or an endpoint here.
            });
        })();
    </script>
</div>
