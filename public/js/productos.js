document.addEventListener('DOMContentLoaded', function() {
    
    // Seleccionamos todos los botones de añadir producto
    const botones = document.querySelectorAll('.btn-add-producto');

    botones.forEach(btn => {
        btn.addEventListener('click', function() {
            // 1. Obtenemos la URL del producto
            const url = this.getAttribute('data-url');
            const originalText = this.innerText;

            // 2. Feedback visual (Evitar doble click)
            this.disabled = true;
            this.innerText = "Añadiendo...";

            // 3. Petición al servidor
            fetch(url, {
                method: 'POST' // O GET, según como tengas tu controller, pero POST suele ir bien
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    
                    // A) Cambiamos botón a verde
                    this.classList.remove('btn-naranja');
                    this.classList.add('btn-success');
                    this.innerText = "✔ Añadido";

                    // B) ACTUALIZAR EL CARRITO LATERAL (La magia)
                    if (typeof actualizarVisualizacionCarrito === "function") {
                        actualizarVisualizacionCarrito();
                    }

                    // C) Restaurar botón tras 1.5 segundos
                    setTimeout(() => {
                        this.classList.remove('btn-success');
                        this.classList.add('btn-naranja');
                        this.innerText = originalText;
                        this.disabled = false;
                    }, 1500);

                } else {
                    alert("Error: " + data.msg);
                    this.innerText = originalText;
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.innerText = originalText;
                this.disabled = false;
            });
        });
    });
});