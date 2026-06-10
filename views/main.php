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



    <?php require_once 'layout/carrito_sidebar.php'; ?>

        <script src="assets/js/api/ApiService.js"></script>
        <script src="assets/js/main.js"></script>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <script src="assets/js/carrito.js"></script>

        <script src="assets/js/carta/menu.js"></script>
        <script src="assets/js/carta/productos.js"></script>
        <script src="assets/js/carta/promociones.js"></script>
        

</body>
</html>
