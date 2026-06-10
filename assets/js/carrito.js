// Función principal que actualiza el carrito visualmente
function actualizarVisualizacionCarrito() {
    api.get('carrito.php?action=getHtml')
        .then(data => {
            // 1. Actualizamos el cuerpo del carrito (los items)
            document.getElementById('carrito-body-content').innerHTML = data.html;

            // 2. Actualizamos el precio total
            document.getElementById('carrito-total-price').innerText = data.total + " €";

            // 3. Update the badge
            let badge = document.getElementById('cart-badge');
            if (badge) {
                if (data.count > 0) {
                    badge.innerText = data.count;
                    badge.style.display = 'block';
                } else {
                    badge.style.display = 'none';
                }
            }
        })
        .catch(error => console.error('Error actualizando carrito:', error));
}

// Función para eliminar items y actualizar barra lateral (llamado desde checkout si es necesario, o adaptado)
function eliminarItem(index) {
    let datos = { index: index };

    api.post('carrito.php?action=remove', datos)
        .then(data => {
            // Actualizamos la vista con lo que devuelve el servidor
            document.getElementById('carrito-body-content').innerHTML = data.html;
            document.getElementById('carrito-total-price').innerText = data.total + " €";
        });
}

function cambiarCantidadCheckout(index, change) {
    let datos = { index: index, change: change };

    api.post('carrito.php?action=changeQuantity', datos)
        .then(data => {
            location.reload();
        });
}

function cambiarCantidadSidebar(index, change) {
    let datos = { index: index, change: change };

    api.post('carrito.php?action=changeQuantity', datos)
        .then(data => {
            actualizarVisualizacionCarrito();
        });
}
function aplicarCodigo() {
    const codigo = document.getElementById('codigo_input').value;
    const mensajeDiv = document.getElementById('mensaje-cupon');

    if (!codigo) return;

    api.post('ofertas.php?action=validar', { codigo: codigo })
        .then(data => {
            if (data.success) {
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