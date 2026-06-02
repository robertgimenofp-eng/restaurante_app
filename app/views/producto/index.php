<div class="container py-5">

    <div class="text-center mb-5">
        <h1 class="fw-bold">Tu comida sana con sabor</h1>
        <p class="text-muted">Elige tus platos favoritos y combínalos a tu gusto</p>
    </div>

    <?php foreach ($productosPorCategoria as $categoria => $productos): ?>

        <?php 
        // --- CORRECCIÓN 1: FILTRO DE MENÚS ---
        // Si la categoría es Menús, Packs o contiene la palabra "Menú", se salta esta vuelta.
        if ($categoria == 'Menús' || $categoria == 'Pack' || stripos($categoria, 'Menú') !== false) {
            continue; 
        }
        ?>

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

                        <div class="img-contenedor-producto rounded-top">
                            <img src=" /public/img/productos/<?php echo $prod->imagen_url; ?>"
                                alt="<?php echo $prod->nombre; ?>"
                                onerror="this.src='https://via.placeholder.com/200?text=Sin+Imagen'">
                        </div>

                        <div class="card-body text-center d-flex flex-column">
                            <h5 class="card-title fw-bold" style="font-size: 1.1rem;"><?php echo $prod->nombre; ?></h5>
                            <p class="card-text text-muted small flex-grow-1">
                                <?php echo $prod->descripcion; ?>
                            </p>

                            <div class="mt-3">
                                <button type="button" 
                                        class="btn btn-naranja rounded-pill w-100 fw-bold btn-add-producto"
                                        data-url="index.php?controller=Carrito&action=add&id=<?= $prod->id_producto ?>">
                                    Añadir +
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    <?php endforeach; ?>
</div>
<script src="public/js/productos.js"></script>