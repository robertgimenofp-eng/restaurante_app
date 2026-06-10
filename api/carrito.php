<?php
// API REST para carrito
session_start();

require_once '../controllers/CarritoController.php';
$controller = new CarritoController();

$metodo = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($metodo === 'GET') {
    if ($action === 'getHtml') {
        $controller->getCarritoHtml();
    }
} elseif ($metodo === 'POST') {
    if ($action === 'remove') {
        $controller->remove();
    }
}
?>
