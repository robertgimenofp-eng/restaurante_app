// Función principal que actualiza el carrito visualmente
function actualizarVisualizacionCarrito() {
    api.get('carrito.php?action=getHtml')
    .then(data => {
        // 1. Actualizamos el cuerpo del carrito (los items)
        document.getElementById('carrito-body-content').innerHTML = data.html;
        
        // 2. Actualizamos el precio total
        document.getElementById('carrito-total-price').innerText = data.total + " €";
        
        // 3. Abrimos el carrito automáticamente (opcional, si quieres que se abra al comprar)
        var myOffcanvas = document.getElementById('carritoSidebar');
        var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
        bsOffcanvas.show();
    })
    .catch(error => console.error('Error actualizando carrito:', error));
}

// Función para eliminar items (conectada al botón X del controlador)
function eliminarItem(index) {
    let formData = new FormData();
    formData.append('index', index);

    api.post('carrito.php?action=remove', formData)
    .then(data => {
        // Actualizamos la vista con lo que devuelve el servidor
        document.getElementById('carrito-body-content').innerHTML = data.html;
        document.getElementById('carrito-total-price').innerText = data.total + " €";
    });
}

function cambiarCantidadCheckout(index, change) {
    let formData = new FormData();
    formData.append('index', index);
    formData.append('change', change);
    
    api.post('carrito.php?action=changeQuantity', formData)
    .then(data => {
        location.reload();
    });
}
function aplicarCodigo() {
    const codigo = document.getElementById('codigo_input').value;
    const mensajeDiv = document.getElementById('mensaje-cupon');

    if(!codigo) return;

    api.post('ofertas.php?action=validar', { codigo: codigo })
    .then(data => {
        if(data.success) {
            mensajeDiv.innerHTML = '<span class="text-success">¡Código aplicado! Recargando...</span>';
            setTimeout(() => location.reload(), 1000);
        } else {
            mensajeDiv.innerHTML = '<span class="text-danger">' + data.message + '</span>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mensajeDiv.innerHTML = '<span class="text-danger">Error al validar código.</span>';
    });
}