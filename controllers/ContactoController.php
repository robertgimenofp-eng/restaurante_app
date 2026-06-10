<?php
class ContactoController {
    
    public function index() {
        $view = "views/contacto/index.php";
        require_once __DIR__ . '/../views/main.php';
    }

    public function faq() {
        $view = "views/contacto/faq.php";
        require_once __DIR__ . '/../views/main.php';
    }

    // Acción para procesar el formulario (simulada)
    public function enviar() {
        $_SESSION['mensaje'] = "¡Mensaje enviado! Te responderemos pronto.";
        header("Location: index.php");
        exit();
    }
}
?>