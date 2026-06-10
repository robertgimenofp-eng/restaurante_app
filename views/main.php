<!DOCTYPE html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante | VivaEats</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
    <div id="cookie-banner" class="cookie-banner shadow-lg" style="position: fixed; bottom: 0; left: 0; width: 100%; z-index: 20000; background-color: #000; color: white; display: block; border-top: 4px solid #ff4e00;">
        <div class="container py-3">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0 text-white">
                        <h5 class="fw-bold text-viva">ðŸª ¡Hola! Usamos cookies</h5>
                        <p class="mb-0 small">
                            Utilizamos cookies propias y de terceros para mejorar tu experiencia.
                            Si continúas navegando, consideramos que aceptas su uso.
                        </p>
                </div>
                <div class="col-md-4 text-end">
                        <button id="btn-rechazar-cookies" class="btn btn-outline-light btn-sm me-2">Rechazar</button>
                        <button id="btn-aceptar-cookies" class="btn btn-viva-primary fw-bold">¡Acepto!</button>
                </div>
            </div>
        </div>
    </div>
    <?php if (!isset($_GET['action']) || strpos($_GET['action'], 'gestion') === false) {
    require_once 'views/layout/navbar.php';
}; ?>

    <div class="contenido">
        <?php 
        // Mostrar mensajes de sesión globales
        if (isset($_SESSION['mensaje'])) {
            echo "<script>document.addEventListener('DOMContentLoaded', function() { alert('" . addslashes($_SESSION['mensaje']) . "'); });</script>";
            unset($_SESSION['mensaje']);
        }
        if (isset($_SESSION['error'])) {
            echo "<script>document.addEventListener('DOMContentLoaded', function() { alert('Error: " . addslashes($_SESSION['error']) . "'); });</script>";
            unset($_SESSION['error']);
        }
        
        include $view; 
        ?>
    </div>

    <?php if (!isset($_GET['action']) || strpos($_GET['action'], 'gestion') === false) {
    require_once 'views/layout/footer.php';
}?>
    <button class="btn btn-dark position-fixed bottom-0 end-0 m-4 p-3 shadow rounded-circle" 
            style="z-index: 1050; width: 60px; height: 60px;"
            data-bs-toggle="offcanvas" data-bs-target="#carritoSidebar">
        🛒
    </button>



    <div class="offcanvas offcanvas-end" tabindex="-1" id="carritoSidebar" aria-labelledby="carritoLabel">
        <div class="offcanvas-header bg-dark text-white">
            <h5 class="offcanvas-title fw-bold" id="carritoLabel">TU PEDIDO ðŸ”</h5>
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
        

        <script src="assets/js/api/ApiService.js"></script>
        <script src="assets/js/main.js"></script>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <script src="assets/js/carrito.js"></script>

        <script src="assets/js/carta/menu.js"></script>
        <script src="assets/js/carta/productos.js"></script>
        <script src="assets/js/carta/promociones.js"></script>
        

</body>
</html>
