<div id="live-notify-container" style="position:fixed;top:20px;right:20px;z-index:9999;pointer-events:none"></div>

<style>
    .lv-toast{pointer-events:auto;min-width:220px;background:#fff;padding:10px 14px;border-radius:6px;box-shadow:0 6px 18px rgba(0,0,0,0.12);margin-top:10px;font-size:14px;display:flex;align-items:center;opacity:0;transform:translateY(-6px);transition:all .25s ease}
    .lv-toast-show{opacity:1;transform:translateY(0)}
    .lv-toast-success{border-left:4px solid #28a745}
    .lv-toast-error{border-left:4px solid #dc3545}
    .lv-toast .lv-msg{margin-left:8px}
    .lv-pulse{animation:lv-pop .45s ease forwards}
    @keyframes lv-pop{0%{transform:scale(1)}50%{transform:scale(1.25)}100%{transform:scale(1)}}
    .lv-pulse-heart{color:#e74c3c;transform-origin:center}
    .lv-action-flash{box-shadow:0 4px 14px rgba(0,0,0,0.18);transform:scale(1.08)}
</style>

<script>
    function showToast(type, message, duration){
            duration = duration || 3000;
            var container = document.getElementById('live-notify-container');
            if(!container) return;
            var toast = document.createElement('div');
            toast.className = 'lv-toast ' + (type === 'error' ? 'lv-toast-error' : 'lv-toast-success');
            var icon = type === 'error' ? '&#10060;' : '&#10003;';
            toast.innerHTML = '<strong>' + icon + ' ' + (type === 'error' ? 'Error' : '¡Éxito!') + '</strong><div class="lv-msg">' + (message || '') + '</div>';
            container.appendChild(toast);
            void toast.offsetWidth;
            toast.classList.add('lv-toast-show');
            setTimeout(function(){ toast.classList.remove('lv-toast-show'); setTimeout(function(){ try{ container.removeChild(toast); }catch(e){} }, 250); }, duration);
        }

        // globally listen to Livewire browser events named 'notify'
        window.addEventListener('notify', function(e){
            try {
                var detail = e && e.detail ? e.detail : {};
                var type = detail.type || 'success';
                var message = detail.message || '';
                var action = detail.action || null;
                var productId = detail.productId || null;

                // show toast
                showToast(type, message);

                // animate target buttons if productId provided
                if (productId) {
                    // cart button animation
                    var cartBtn = document.querySelector('.btn-add-cart[data-product-id="' + productId + '"]') || document.querySelector('.btn-add-cart');
                    if (cartBtn && action === 'cart') {
                        cartBtn.classList.add('lv-action-flash');
                        setTimeout(function(){ cartBtn.classList.remove('lv-action-flash'); }, 700);
                    }

                    // wishlist animation
                    var wishBtn = document.querySelector('.btn-wishlist[data-product-id="' + productId + '"]') || document.querySelector('.btn-wishlist');
                    if (wishBtn && action === 'wishlist') {
                        var icon = wishBtn.querySelector('i');
                        if (icon) {
                            icon.classList.add('lv-pulse-heart');
                            icon.classList.add('lv-pulse');
                            setTimeout(function(){ icon.classList.remove('lv-pulse'); }, 600);
                        } else {
                            wishBtn.classList.add('lv-action-flash');
                            setTimeout(function(){ wishBtn.classList.remove('lv-action-flash'); }, 700);
                        }
                    }
                }
            } catch (err) {
                console.error('notify handler error', err);
            }
        });

    @if(session('success'))
    document.addEventListener('DOMContentLoaded', function(){
        showToast('success', @json(session('success')), 6000);
    });
    @endif
    @if(session('error') && !session('message'))
    document.addEventListener('DOMContentLoaded', function(){
        showToast('error', @json(session('error')), 5000);
    });
    @endif

    // Mensaje de éxito proveniente de PayPal Smart Buttons (via sessionStorage)
    document.addEventListener('DOMContentLoaded', function(){
        var msg = sessionStorage.getItem('paypal_success');
        if (msg) { sessionStorage.removeItem('paypal_success'); showToast('success', msg, 6000); }
    });
</script>
