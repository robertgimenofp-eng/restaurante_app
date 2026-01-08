<?php
class LegalController {
    // Una sola acción que carga la vista con todas las secciones
    public function index() {
        // Podemos capturar qué pestaña abrir por defecto si quieres (opcional)
        // $seccion = isset($_GET['seccion']) ? $_GET['seccion'] : 'aviso';
        $view = "views/legal/index.php";
        require_once 'views/main.php';
    }
}
?>