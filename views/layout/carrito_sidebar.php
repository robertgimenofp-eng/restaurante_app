<div class="offcanvas offcanvas-end" tabindex="-1" id="carritoSidebar" aria-labelledby="carritoLabel">
    <div class="offcanvas-header bg-dark text-white d-flex align-items-center position-relative">
        <button type="button" class="btn text-white p-0 border-0 fw-bold d-flex align-items-center gap-2" data-bs-dismiss="offcanvas">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
            </svg>
            Seguir comprando
        </button>
        <h5 class="offcanvas-title fw-bold m-0 position-absolute start-50 translate-middle-x" id="carritoLabel">TU PEDIDO 🍔</h5>
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
                    foreach($carrito as $c) {
                        $unidades = isset($c['unidades']) ? $c['unidades'] : 1;
                        $total += $c['precio'] * $unidades;
                    }
                    echo number_format($total, 2) . ' €';
                ?>
            </span>
        </div>
        <a href="index.php?controller=Carrito&action=checkout" class="btn btn-dark w-100 py-3 fw-bold">
            FINALIZAR PEDIDO ✅
        </a>
    </div>
</div>
