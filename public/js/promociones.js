document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. LÓGICA DE CANJEAR REAL (CONECTADA A PHP) ---
    const btnCanjear = document.getElementById('btn-canjear');
    const inputCode = document.getElementById('input-promo-code');
    const msgArea = document.getElementById('mensaje-promo');

    btnCanjear.addEventListener('click', function() {
        const codigo = inputCode.value.trim().toUpperCase();
        
        // Limpiamos mensajes previos
        msgArea.style.display = 'none';
        msgArea.className = 'mt-3 alert'; 

        if(codigo === "") {
            mostrarMensaje('alert-warning', 'Escribe un código primero.');
            return;
        }

        // LLAMADA AL SERVIDOR (AJAX)
        fetch('index.php?controller=Promociones&action=validar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ codigo: codigo })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarMensaje('alert-success', data.mensaje);
            } else {
                mostrarMensaje('alert-danger', data.mensaje);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarMensaje('alert-danger', 'Hubo un error de conexión.');
        });
    });

    function mostrarMensaje(tipo, texto) {
        msgArea.classList.add(tipo);
        msgArea.innerHTML = texto;
        msgArea.style.display = 'block';
    }

    // --- 2. LÓGICA DE COPIAR (SE MANTIENE IGUAL) ---
    document.querySelectorAll('.btn-copiar').forEach(btn => {
        btn.addEventListener('click', function() {
            const code = this.getAttribute('data-code');
            navigator.clipboard.writeText(code);
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-check"></i> Copiado';
            this.classList.remove('btn-outline-secondary');
            this.classList.add('btn-success');
            setTimeout(() => {
                this.innerHTML = originalText;
                this.classList.add('btn-outline-secondary');
                this.classList.remove('btn-success');
            }, 2000);
        });
    });
});