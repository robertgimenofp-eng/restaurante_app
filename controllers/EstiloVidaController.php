<?php
class EstiloVidaController {
    
    // PÃ¡gina principal con el grid de blogs
    public function index() {
        // AquÃ­ podrÃ­as sacar los blogs de la base de datos en el futuro
        $view = "views/estilo/index.php";
        require_once __DIR__ . '/../views/main.php';
    }

    // PÃ¡gina de detalle de un artÃ­culo
    public function entrada() {
        $view = "views/estilo/entrada.php";
        require_once __DIR__ . '/../views/main.php';
    }
}
?>