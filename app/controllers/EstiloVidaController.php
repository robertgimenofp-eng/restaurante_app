<?php
class EstiloVidaController {
    
    // Página principal con el grid de blogs
    public function index() {
        // Aquí podrías sacar los blogs de la base de datos en el futuro
        $view = "views/estilo/index.php";
        require_once 'views/main.php';
    }

    // Página de detalle de un artículo
    public function entrada() {
        $view = "views/estilo/entrada.php";
        require_once 'views/main.php';
    }
}
?>