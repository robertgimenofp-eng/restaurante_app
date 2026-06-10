<?php
class RestaurantesController {
    
    public function index() {
        // En un futuro, aquÃ­ podrÃ­as cargar las direcciones desde la BD
        $view = "views/restaurantes/index.php";
        require_once __DIR__ . '/../views/main.php';
    }
}
?>