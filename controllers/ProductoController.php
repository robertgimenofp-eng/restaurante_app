<?php
// controllers/ProductoController.php
require_once 'models/Producto.php';

class ProductoController {

    public function index() {
        require_once 'config/db.php';
        $database = new Database();
        $db = $database->connect();

        $productoModel = new Producto($db);
        $todosLosProductos = $productoModel->getAll();

        // ORGANIZAR POR CATEGORÍAS
        // Creamos un array donde la clave es la categoría y el valor es la lista de platos
        $productosPorCategoria = [];

        foreach ($todosLosProductos as $prod) {
            $productosPorCategoria[$prod->categoria][] = $prod;
        }

        // Cargamos la vista
        $view = 'views/producto/index.php';
        require_once 'views/main.php';
    }
}
?>