<div class="container py-5 checkout-container" style="max-width: 800px;">
    
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="font-size: 2.5rem; color: #000;">Tu pedido VivaEATS 🍔</h2>
    </div>

    <div class="card card-viva mb-4 shadow-sm border-0">
        <div class="card-body p-0">
            <?php if(isset($carrito) && count($carrito) > 0): ?>
                <?php foreach($carrito as $index => $item): ?>
                <div class="d-flex align-items-center p-4 bg-white <?= $index !== count($carrito)-1 ? 'border-bottom' : '' ?>">
                    
                    <div class="flex-shrink-0 me-4">
                        <?php 
                            // Aquí la vista CONSULTA el mapa que preparó el controlador
                            $id = isset($item['id_producto']) ? $item['id_producto'] : null;
                            if (isset($item['tipo']) && $item['tipo'] == 'menu_personalizado') {
                                $nombre_fichero = 'menupersonalizado.webp';
                            } else {
                                $nombre_fichero = ($id && isset($imagenes_map[$id])) ? $imagenes_map[$id] : 'default.jpg';
                            }
                            $imgSrc = "assets/img/productos/" . $nombre_fichero;
                        ?>
                            <img src="<?= $imgSrc ?>" 
                             class="img-fluid rounded-3" 
                             style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid #f0f0f0;"
                             alt="<?= $item['nombre'] ?>"
                             onerror="this.onerror=null; this.src='https://via.placeholder.com/80x80?text=Sin+Foto'">
                    </div>
                    
                    <div class="flex-grow-1">
                        <h5 class="fw-bold mb-1 text-dark" style="font-size: 1.1rem;"><?= $item['nombre'] ?></h5>
                        <p class="text-muted small mb-0">
                            <?php 
                                if(isset($item['tipo']) && $item['tipo'] == 'pack_fijo') {
                                    echo "Pack Ahorro";
                                } elseif(isset($item['descripcion'])) {
                                    echo substr($item['descripcion'], 0, 60);
                                } else {
                                    echo "x" . $item['unidades'] . " ud.";
                                }
                            ?>
                        </p>
                    </div>
                    
                    <div class="flex-shrink-0 ms-4 text-end d-flex flex-column align-items-end">
                        <span class="fw-bold fs-5 text-dark"><?= number_format($item['precio'] * $item['unidades'], 2) ?>€</span>
                        <button class="btn btn-sm text-danger mt-2 p-0 border-0 bg-transparent" onclick="eliminarItemCheckout(<?= $index ?>)" title="Eliminar producto">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.814 1-.841 10.518a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.186 3.5h9.628Zm-7.468 3.197a.5.5 0 0 0-.706.706L6.293 9l-1.653 1.653a.5.5 0 0 0 .706.706L7 9.707l1.653 1.653a.5.5 0 0 0 .706-.706L7.707 9l1.653-1.653a.5.5 0 0 0-.706-.706L7 8.293 5.347 6.64Z"/>
                            </svg> Quitar
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="p-5 text-center">
                    <p class="mb-0">Tu carrito está vacío.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mx-auto" style="max-width: 500px;">
        
        <div class="card card-viva mb-4 shadow-sm border-0">
            <div class="card-body p-4 bg-white">
                <h4 class="fw-bold mb-4 text-center">Resumen de compra</h4>
                
                <div class="d-flex justify-content-between mb-2 text-muted fw-bold">
                    <span>Subtotal</span>
                    <span><?= number_format($subtotal, 2) ?>€</span>
                </div>

                <div class="d-flex justify-content-between mb-2 text-muted fw-bold">
                    <span>Gastos de envío</span>
                    <span><?= number_format($gastos_envio, 2) ?>€</span>
                </div>
                
                <?php if($descuento > 0): ?>
                <div class="d-flex justify-content-between mb-2 text-success fw-bold border-bottom pb-3">
                    <span>Descuento (<?= $codigo_nombre ?>)</span>
                    <span>- <?= number_format($descuento, 2) ?>€</span>
                </div>
                <?php else: ?>
                <div class="border-bottom mb-3"></div>
                <?php endif; ?>
                
                <div class="d-flex justify-content-between mt-3">
                    <span class="fw-bold fs-3 text-dark">Total</span>
                    <span class="fw-bold fs-3 text-dark"><?= number_format($total_final, 2) ?>€</span>
                </div>
            </div>
        </div>

        <div class="card card-viva mb-4 shadow-sm border-0">
            <div class="card-body p-4 bg-white">
                <label class="form-label fw-bold text-dark mb-2">Código promocional</label>
                <?php if(!isset($_SESSION['descuento_activo'])): ?>
                    <div class="d-flex gap-2">
                        <input type="text" id="codigo_input" class="form-control" placeholder="Introduce tu código">
                        <button class="btn btn-outline-secondary px-3 py-2" type="button" onclick="aplicarCodigo()">Aplicar</button>
                    </div>
                    <div id="mensaje-cupon" class="mt-2 small"></div>
                <?php else: ?>
                    <div class="alert alert-success py-2 text-center mb-0">
                        ✅ Cupón aplicado. <a href="index.php?controller=Promociones&action=quitar" class="fw-bold text-success">Quitar</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <a href="index.php?controller=Pedido&action=pasarela" class="btn btn-viva-finalizar w-100 py-3 fw-bold fs-4 shadow-sm d-block text-center text-dark text-decoration-none" style="background-color: #ffc107; border:none;">
            Tramitar pedido
        </a>
    </div>

</div>

<script src="assets/js/carrito.js"></script>