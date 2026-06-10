<?php
// API REST para ofertas/promociones
session_start();

require_once '../controllers/PromocionesController.php';
$controller = new PromocionesController();

$metodo = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($metodo === 'POST' && $action === 'validar') {
    $controller->validar();
}
?>
