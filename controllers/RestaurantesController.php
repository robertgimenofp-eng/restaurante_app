<?php
class RestaurantesController {
    
    public function index() {
        // En un futuro, aquí podrías cargar las direcciones desde la BD
        $view = "views/restaurantes/index.php";
        require_once 'views/main.php';
    }
}
?>