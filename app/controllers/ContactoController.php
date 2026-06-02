<?php
class ContactoController {
    
    public function index() {
        $view = "views/contacto/index.php";
        require_once 'views/main.php';
    }

    public function faq() {
        $view = "views/contacto/faq.php";
        require_once 'views/main.php';
    }

    // Acción para procesar el formulario (simulada)
    public function enviar() {
        // Aquí iría la lógica de enviar email
        echo "<script>alert('¡Mensaje enviado! Te responderemos pronto.'); window.location.href='index.php';</script>";
    }
}
?>