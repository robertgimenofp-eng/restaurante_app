<?php
    // controllers/ProductoController.php
    require_once 'models/Producto.php';
    require_once 'models/Log.php';

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

        public function apiListar() {
            // 1. Seguridad: Solo admin
                if (!isset($_SESSION['identity']) || $_SESSION['identity']->rol != 'admin') {
                    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
                    exit();
                }

                // 2. Conectar y pedir datos
                require_once 'config/db.php';
                $database = new Database();
                $db = $database->connect();
                $producto = new Producto($db);
                
                // 3. Obtener el array de productos
                $lista = $producto->getAll(); 

                // 4. Devolver JSON (Aquí está la clave)
                header('Content-Type: application/json');
                echo json_encode($lista);
                exit();
            }

           public function save() {
    // 1. Verificación de seguridad
    if (!isset($_SESSION['identity']) || $_SESSION['identity']->rol != 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
        exit();
    }

    require_once 'config/db.php';
    $database = new Database();
    $db = $database->connect();

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
                if ($id) {
                    // === MODO EDITAR (UPDATE) ===
                    $sql = "UPDATE producto SET nombre=:nombre, descripcion=:descripcion, precio=:precio, stock=:stock, categoria=:categoria";
                    if ($nombre_fichero) {
                        $sql .= ", imagen_url=:imagen";
                    }
                    $sql .= " WHERE id_producto=:id";
                    
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(':id', $id);
                    if ($nombre_fichero) {
                        $stmt->bindParam(':imagen', $nombre_fichero);
                    }
                } else {
                    // === MODO CREAR (INSERT) ===
                    $img_final = $nombre_fichero ?? 'no-image.webp';
                    $sql = "INSERT INTO producto (nombre, descripcion, precio, stock, categoria, imagen_url) 
                            VALUES (:nombre, :descripcion, :precio, :stock, :categoria, :imagen)";
                    
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(':imagen', $img_final);
                }

                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':descripcion', $descripcion);
                $stmt->bindParam(':precio', $precio);
                $stmt->bindParam(':stock', $stock);
                $stmt->bindParam(':categoria', $categoria);

                if ($stmt->execute()) {
                    // --- 🛡️ REGISTRO DINÁMICO DE LOGS ---
                    if ($id) {
                        // Es una edición
                        Log::save($id, 'producto', 'UPDATE', "Se actualizó el producto: $nombre (ID: $id)");
                    } else {
                        // Es una creación, recuperamos el ID que acaba de generar la BD
                        $nuevo_id = $db->lastInsertId();
                        Log::save($nuevo_id, 'producto', 'CREATE', "Se creó el producto: $nombre");
                    }
                    
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
            if (!isset($_SESSION['identity']) || $_SESSION['identity']->rol != 'admin') {
                echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
                exit();
            }

            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                
                require_once 'config/db.php';
                $database = new Database();
                $db = $database->connect();
                
                // Borrado directo (SQL simple para asegurar que funciona)
                // Asegúrate de que tu tabla se llama 'producto' y la clave 'id_producto'
                $sql = "DELETE FROM producto WHERE id_producto = :id";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':id', $id);
                
                if ($stmt->execute()) {
                    // REGISTRO DE LOG
                    Log::save($id, 'producto', 'DELETE', "Se eliminó el producto con ID: $id");
                    
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
                require_once 'config/db.php';
                $database = new Database();
                $db = $database->connect();

                $sql = "SELECT * FROM producto WHERE id_producto = :id";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                
                $producto = $stmt->fetch(PDO::FETCH_ASSOC);
                
                header('Content-Type: application/json');
                echo json_encode($producto);
            }
            exit();
        }
    }
    
?>