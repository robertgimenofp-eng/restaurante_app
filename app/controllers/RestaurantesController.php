<?php
class RestaurantesController {
    
    public function index() {
        // En un futuro, aquí podrías cargar las direcciones desde la BD
        $view = "app/views/restaurantes/index.php";
        require_once 'app/views/main.php';
    }
}
?>