document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // 1. LOGICA PARA EL MENÚ INDIVIDUAL (13.50€)
    // ==========================================
    const btnMenu = document.getElementById('btn-add-menu');
    
    if (btnMenu) {
        btnMenu.addEventListener('click', function() {
            let principal = document.getElementById('select-principal').value;
            let snack = document.getElementById('select-snack').value;
            let bebida = document.getElementById('select-bebida').value;
            let msg = document.getElementById('mensaje-menu');
            let urlDestino = btnMenu.getAttribute('data-url'); 

            // Validación
            if(!principal || !snack || !bebida) {
                mostrarError(msg, "⚠️ Por favor, selecciona las 3 opciones.");
                return;
            }

            let datos = new Data();
            datos.append('principal', principal);
            datos.append('snack', snack);
            datos.append('bebida', bebida);

            enviarAlCarrito(urlDestino, datos, msg);
        });
    }

    // ==========================================
    // 2. LOGICA PARA PACKS CONFIGURABLES (Amigos / Familiar)
    // ==========================================
    const complexButtons = document.querySelectorAll('.btn-add-complex-pack');
    
    complexButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const formId = btn.getAttribute('data-form');
            const msgId = btn.getAttribute('data-msg');
            const urlDestino = btn.getAttribute('data-url');
            
            const form = document.getElementById(formId);
            const msg = document.getElementById(msgId);
            
            // Validación automática
            const selects = form.querySelectorAll('select');
            let completo = true;
            
            selects.forEach(select => {
                if(select.value === "") {
                    select.classList.add('border', 'border-danger');
                    completo = false;
                } else {
                    select.classList.remove('border', 'border-danger');
                }
            });

            if(!completo) {
                mostrarError(msg, "⚠️ Elige todas las bebidas antes de añadir.");
                return;
            }

            let datos = new FormData(form);
            enviarAlCarrito(urlDestino, datos, msg);
        });
    });

    // ==========================================
    // 3. LOGICA PARA PACKS SIMPLES (Vegano)
    // ==========================================
    const simpleButtons = document.querySelectorAll('.btn-add-simple-pack');

    simpleButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const urlDestino = btn.getAttribute('data-url');
            let datos = new FormData(); 
            
            const originalText = btn.innerText;
            const originalClass = btn.className; // Guardamos clases originales

            btn.innerText = "Añadiendo... ⏳";
            btn.disabled = true;

            fetch(urlDestino, {
                method: 'POST',
                body: datos
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    // Feedback visual
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-dark');
                    btn.innerText = "✅ ¡Añadido!";
                    
                    // 1. ACTUALIZAR EL CARRITO LATERAL
                    if (typeof actualizarVisualizacionCarrito === "function") {
                        actualizarVisualizacionCarrito();
                    }

                    // 2. Restaurar botón tras 2 segundos
                    setTimeout(() => { 
                        btn.className = originalClass; // Vuelve a ser verde
                        btn.innerText = originalText;
                        btn.disabled = false;
                    }, 2000);

                } else {
                    alert("Error: " + data.msg);
                    btn.innerText = originalText;
                    btn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Error en el servidor");
                btn.innerText = originalText;
                btn.disabled = false;
            });
        });
    });

    // ==========================================
    // FUNCIONES AUXILIARES
    // ==========================================
    function mostrarError(elemento, texto) {
        if(elemento) {
            elemento.style.display = 'block';
            elemento.style.color = '#dc3545';
            elemento.innerText = texto;
        } else {
            alert(texto);
        }
    }

    function enviarAlCarrito(url, datos, msgElement) {
        if(msgElement) {
            msgElement.style.display = 'block';
            msgElement.style.color = 'black';
            msgElement.innerText = "Procesando...";
        }

        fetch(url, {
            method: 'POST',
            body: datos
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                if(msgElement) {
                    msgElement.style.color = '#198754';
                    msgElement.innerText = "✅ ¡Añadido correctamente!";
                }
                
                // 1. ACTUALIZAR EL CARRITO LATERAL
                if (typeof actualizarVisualizacionCarrito === "function") {
                    actualizarVisualizacionCarrito();
                } else {
                    console.error("Falta cargar carrito.js");
                }

                // 2. Ocultar mensaje de éxito tras 2 segundos para limpiar pantalla
                setTimeout(() => { 
                    if(msgElement) msgElement.style.display = 'none';
                }, 2000);

            } else {
                mostrarError(msgElement, "Error: " + (data.msg || "Desconocido"));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarError(msgElement, "❌ Error de conexión.");
        });
    }
});