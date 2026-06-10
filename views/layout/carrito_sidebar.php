<div class="offcanvas offcanvas-end" tabindex="-1" id="carritoSidebar" aria-labelledby="carritoLabel">
    <div class="offcanvas-header bg-dark text-white">
        <h5 class="offcanvas-title fw-bold" id="carritoLabel">TU PEDIDO 🍔</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="offcanvas-body bg-light" id="carrito-body-content">
        <?php 
        if (session_status() == PHP_SESSION_NONE) session_start();
        $carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
        if(empty($carrito)): 
        ?>
            <div class="text-center py-5 text-muted">
                <h1 class="display-1">🛒</h1>
                <p>Carrito vacío</p>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <button class="btn btn-sm btn-outline-dark" onclick="actualizarVisualizacionCarrito()">Cargar mis productos...</button>
            </div>
        <?php endif; ?>
    </div>

    <div class="offcanvas-footer p-3 bg-white border-top">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="h5 mb-0">Total:</span>
            <span class="h4 fw-bold text-success mb-0" id="carrito-total-price">
                <?php 
                    $total = 0; 
                    foreach($carrito as $c) $total += $c['precio'];
                    echo number_format($total, 2) . ' €';
                ?>
            </span>
        </div>
        <a href="index.php?controller=Carrito&action=checkout" class="btn btn-dark w-100 py-3 fw-bold">
            FINALIZAR PEDIDO ✅
        </a>
    </div>
</div>
