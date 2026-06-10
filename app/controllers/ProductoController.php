<?php
    // controllers/ProductoController.php
    require_once 'app/models/ProductoDAO.php';
    require_once 'app/models/LogDAO.php';

    class ProductoController {

        public function index() {
            $productoModel = new ProductoDAO();
            $todosLosProductos = $productoModel->getAll();

            // ORGANIZAR POR CATEGORÍAS
            // Creamos un array donde la clave es la categoría y el valor es la lista de platos
            $productosPorCategoria = [];

            foreach ($todosLosProductos as $prod) {
                $productosPorCategoria[$prod->getCategoria()][] = $prod;
            }

            // Cargamos la vista
            $view = 'app/views/producto/index.php';
            require_once 'app/views/main.php';
        }

        public function apiListar() {
            // 1. Seguridad: Solo admin
                if (!isset($_SESSION['identity']) || $_SESSION['identity']->getRol() != 'admin') {
                    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
                    exit();
                }

                // 2. Conectar y pedir datos
                require_once 'app/models/ProductoDAO.php';
                $producto = new ProductoDAO();
                
                // 3. Obtener el array de productos
                $lista = $producto->getAll(); 

                // 4. Devolver JSON (Aquí está la clave)
                header('Content-Type: application/json');
                echo json_encode($lista);
                exit();
            }

           public function save() {
    // 1. Verificación de seguridad
    if (!isset($_SESSION['identity']) || $_SESSION['identity']->getRol() != 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
        exit();
    }

    require_once 'app/models/ProductoDAO.php';
    require_once 'app/models/LogDAO.php';
    $productoDAO = new ProductoDAO();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombre = $_POST['nombre'] ?? null;
        $descripcion = $_POST['descripcion'] ?? '';
        $precio = $_POST['precio'] ?? null;
        $stock = $_POST['stock'] ?? 0;
        $categoria = $_POST['categoria'] ?? 1; 
        $id = $_POST['id_producto'] ?? null; 

        // --- 2. LÓGICA DE IMAGEN ---
        $nombre_fichero = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $archivo = $_FILES['imagen'];
            $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
            $nombre_nuevo = 'prod_' . uniqid() . '.' . $extension;
            $ruta_destino = 'public/img/productos/' . $nombre_nuevo;

            if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
                $nombre_fichero = $nombre_nuevo; 
            }
        }

        // --- 3. GUARDAR EN BASE DE DATOS ---
        if ($nombre && $precio) {
            try {
                $exito = false;
                if ($id) {
                    // === MODO EDITAR (UPDATE) ===
                    $exito = $productoDAO->update($id, $nombre, $descripcion, $precio, $stock, $categoria, $nombre_fichero);
                    if ($exito) {
                        LogDAO::save($id, 'producto', 'UPDATE', "Se actualizó el producto: $nombre (ID: $id)");
                    }
                } else {
                    // === MODO CREAR (INSERT) ===
                    $img_final = $nombre_fichero ?? 'no-image.webp';
                    $nuevo_id = $productoDAO->create($nombre, $descripcion, $precio, $stock, $categoria, $img_final);
                    if ($nuevo_id) {
                        $exito = true;
                        LogDAO::save($nuevo_id, 'producto', 'CREATE', "Se creó el producto: $nombre");
                    }
                }

                if ($exito) {
                    echo json_encode(['status' => 'success']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Fallo en execute SQL']);
                }

            } catch (PDOException $e) {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Faltan datos obligatorios']);
        }
    }
    exit();
}
    
        // Y añade la de BORRAR también para devolver JSON
        public function borrar() {
            // Seguridad
            if (!isset($_SESSION['identity']) || $_SESSION['identity']->getRol() != 'admin') {
                echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
                exit();
            }

            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                
                require_once 'app/models/ProductoDAO.php';
                require_once 'app/models/LogDAO.php';
                $productoDAO = new ProductoDAO();
                
                if ($productoDAO->delete($id)) {
                    // REGISTRO DE LOG
                    LogDAO::save($id, 'producto', 'DELETE', "Se eliminó el producto con ID: $id");
                    
                    echo json_encode(['status' => 'success']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Fallo SQL']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Sin ID']);
            }
            exit();
        }
        // Nueva función para rellenar el formulario de edición
        public function apiObtener() {
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                require_once 'app/models/ProductoDAO.php';
                $productoDAO = new ProductoDAO();

                $producto = $productoDAO->getByIdAssoc($id);
                
                header('Content-Type: application/json');
                echo json_encode($producto);
            }
            exit();
        }
    }
    
?>