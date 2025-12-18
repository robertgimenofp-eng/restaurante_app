<div class="container py-5">

    <div class="text-center mb-5">
        <h1 class="fw-bold">Tu comida sana con sabor</h1>
        <p class="text-muted">Elige tus platos favoritos y combínalos a tu gusto</p>
    </div>

    <?php foreach ($productosPorCategoria as $categoria => $productos): ?>

        <h3 class="fw-bold mb-4 text-uppercase border-bottom pb-2" style="color: #333;">
            <?php echo $categoria; ?>
        </h3>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5">

            <?php foreach ($productos as $prod): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 product-card">

                        <span class="badge bg-white text-dark position-absolute top-0 end-0 m-3 shadow-sm px-3 py-2 fw-bold rounded-pill">
                            <?php echo $prod->precio; ?>€
                        </span>

                        <img src="public/img/productos/<?php echo $prod->imagen; ?>"
                            class="card-img-top p-3"
                            alt="<?php echo $prod->nombre; ?>"
                            style="object-fit: contain; height: 180px;"
                            onerror="this.src='https://via.placeholder.com/150'">

                        <div class="card-body text-center d-flex flex-column">
                            <h5 class="card-title fw-bold" style="font-size: 1.1rem;"><?php echo $prod->nombre; ?></h5>
                            <p class="card-text text-muted small flex-grow-1">
                                <?php echo $prod->descripcion; ?>
                            </p>

                            <form action="index.php?controller=Carrito&action=add" method="POST" class="mt-3">
                                <input type="hidden" name="id_producto" value="<?php echo $prod->id_producto; ?>">

                                <button type="submit" class="btn btn-naranja rounded-pill w-100 fw-bold">
                                    Añadir +
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    <?php endforeach; ?>
</div>
<div class="modal fade" id="carritoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">
                    🍽️ Tu Pedido VivaEATS
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <?php if(isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0): ?>

                    <ul class="list-group list-group-flush">
                        <?php
                            $total = 0;
                            foreach($_SESSION['carrito'] as $item):
                                $subtotal = $item['precio'] * $item['cantidad'];
                                $total += $subtotal;
                        ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="public/img/productos/<?php echo $item['imagen']; ?>"
                                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; margin-right: 10px;">
                                        <div>
                                            <h6 class="my-0 fw-bold"><?php echo $item['nombre']; ?></h6>
                                            <small class="text-muted"><?php echo $item['cantidad']; ?> x <?php echo $item['precio']; ?>€</small>
                                        </div>
                                    </div>
                                    <span class="fw-bold text-naranja"><?php echo number_format($subtotal, 2); ?>€</span>

                                    <a href="index.php?controller=Carrito&action=remove&id=<?php echo $item['id']; ?>" class="text-danger ms-2">x</a>
                                </li>
                            <?php endforeach; ?>
                    </ul>

                    <div class="mt-3 text-end">
                        <h5 class="fw-bold">Total: <?php echo number_format($total, 2); ?>€</h5>
                    </div>

                <?php else: ?>
                    <p class="text-center text-muted py-3">Tu carrito está vacío todavía.</p>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Seguir pidiendo</button>
                <a href="index.php?controller=Pedido&action=index" class="btn btn-naranja rounded-pill fw-bold">
                    Finalizar Compra
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Esperamos a que la página cargue
    document.addEventListener("DOMContentLoaded", function() {
        // Comprobamos si la URL tiene ?openCart=true
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('openCart')) {
            // Usamos Bootstrap para abrir el modal
            var myModal = new bootstrap.Modal(document.getElementById('carritoModal'));
            myModal.show();

            // Opcional: Limpiamos la URL para que si recargas no salga otra vez
            window.history.replaceState({}, document.title, "index.php?controller=Producto&action=index");
        }
    });
</script>