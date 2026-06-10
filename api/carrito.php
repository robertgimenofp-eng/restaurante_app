<?php
// API REST para carrito
require_once '../models/Usuario.php';
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
    } elseif ($action === 'add') {
        $controller->add();
    } elseif ($action === 'addMenuCompleto') {
        $controller->addMenuCompleto();
    } elseif ($action === 'addPackComplejo') {
        $controller->addPackComplejo();
    } elseif ($action === 'changeQuantity') {
        $controller->changeQuantity();
    }
}
?>
