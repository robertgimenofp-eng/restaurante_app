// Función principal que actualiza el carrito visualmente
function actualizarVisualizacionCarrito() {
    fetch('index.php?controller=Carrito&action=getCarritoHtml')
    .then(response => response.json())
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

    fetch('index.php?controller=Carrito&action=remove', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Actualizamos la vista con lo que devuelve el servidor
        document.getElementById('carrito-body-content').innerHTML = data.html;
        document.getElementById('carrito-total-price').innerText = data.total + " €";
    });
}
function aplicarCodigo() {
    const codigo = document.getElementById('codigo_input').value;
    const mensajeDiv = document.getElementById('mensaje-cupon');

    if(!codigo) return;

    fetch('index.php?controller=Promociones&action=validar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ codigo: codigo })
    })
    .then(response => response.json())
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