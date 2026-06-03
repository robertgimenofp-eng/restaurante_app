<?php
class ContactoController {
    
    public function index() {
        $view = "app/views/contacto/index.php";
        require_once 'app/views/main.php';
    }

    public function faq() {
        $view = "app/views/contacto/faq.php";
        require_once 'app/views/main.php';
    }

    // Acción para procesar el formulario (simulada)
    public function enviar() {
        // Aquí iría la lógica de enviar email
        echo "<script>alert('¡Mensaje enviado! Te responderemos pronto.'); window.location.href='index.php';</script>";
    }
}
?>