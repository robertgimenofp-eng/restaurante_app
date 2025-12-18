<?php
// controllers/CarritoController.php
require_once 'models/Producto.php';

class CarritoController {

    public function add() {
        // 1. Comprobamos que nos envían un ID
        if(isset($_POST['id_producto'])) {
            $id = $_POST['id_producto'];

            // 2. Necesitamos saber los datos de ese producto (Precio, Nombre, Foto)
            require_once 'config/db.php';
            $database = new Database();
            $db = $database->connect();
            $productoModel = new Producto($db);
            $producto = $productoModel->getById($id); // Ahora crearemos esta función

            if ($producto) {
                // 3. Añadir al carrito (Sesión)
                // Si el carrito no existe, lo creamos
                if (!isset($_SESSION['carrito'])) {
                    $_SESSION['carrito'] = [];
                }

                // Si el producto ya está, sumamos 1. Si no, lo creamos.
                if (isset($_SESSION['carrito'][$id])) {
                    $_SESSION['carrito'][$id]['cantidad']++;
                } else {
                    $_SESSION['carrito'][$id] = [
                        "id" => $producto->id_producto,
                        "nombre" => $producto->nombre,
                        "precio" => $producto->precio,
                        "imagen" => $producto->imagen,
                        "cantidad" => 1
                    ];
                }
            }
        }

        // 4. REDIRECCIÓN MÁGICA
        // Volvemos a la carta, pero con un parámetro ?openCart=true
        header("Location: index.php?controller=Producto&action=index&openCart=true");
        exit();
    }

    // Función para borrar (por si acaso)
    public function remove() {
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            unset($_SESSION['carrito'][$id]);
        }
        header("Location: index.php?controller=Producto&action=index&openCart=true");
        exit();
    }
}
?>