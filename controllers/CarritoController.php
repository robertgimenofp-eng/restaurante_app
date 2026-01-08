<?php
require_once 'models/Producto.php';

class CarritoController {

    // 1. LÓGICA PARA EL MENÚ PERSONALIZADO (13.50€)
    // Recibe: principal, snack, bebida (IDs) desde menu.js
    public function addMenuCompleto() {
        // Iniciamos sesión si no está iniciada (por seguridad)
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Verificamos que lleguen los 3 datos
        if(isset($_POST['principal']) && isset($_POST['snack']) && isset($_POST['bebida'])) {
            
            // Creamos el carrito si no existe
            if(!isset($_SESSION['carrito'])) {
                $_SESSION['carrito'] = [];
            }

            // Para hacerlo bien, deberíamos sacar los nombres de la BBDD,
            // pero para que funcione YA, vamos a guardar los IDs y el precio fijo.
            // Luego en la vista del carrito ya recuperaremos los nombres.
            
            $item = [
                "tipo" => "menu_personalizado",
                "nombre" => "Menú Personalizado", // Nombre genérico
                "precio" => 13.50,
                "unidades" => 1,
                "ingredientes" => [
                    "principal_id" => $_POST['principal'],
                    "snack_id"     => $_POST['snack'],
                    "bebida_id"    => $_POST['bebida']
                ]
            ];

            $_SESSION['carrito'][] = $item;

            // Respondemos al JS con ÉXITO
            echo json_encode(['status' => 'success']);
        } else {
            // Respondemos al JS con ERROR
            echo json_encode(['status' => 'error', 'msg' => 'Faltan ingredientes']);
        }
    }
// 2. LÓGICA PARA LOS PACKS DE BEBIDAS (Amigos y Familiar)
    // Recibe: id_pack y bebidas (bebida1, bebida2...)
    public function addPackComplejo() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        if(isset($_POST['id_pack'])) {
            $id_pack = $_POST['id_pack'];
            
            // Recogemos las bebidas dinámicamente
            $bebidas_elegidas = [];
            foreach($_POST as $key => $value) {
                // Buscamos campos que contengan la palabra "bebida" (bebida1, bebida2...)
                if(strpos($key, 'bebida') !== false && !empty($value)) {
                    $bebidas_elegidas[] = $value; // Guardamos el ID de la bebida
                }
            }

            // Asignamos datos (HARDCODEADO POR AHORA para simplificar lógica de packs complejos)
            // Si quieres hacerlo por BBDD luego me dices, pero estos packs son especiales.
            if ($id_pack == 34) {
                $nombre = "Pack Amigos";
                $precio = 30.00;
                $desc = "Pack con 2 bebidas a elegir";
            } elseif ($id_pack == 35) {
                $nombre = "Pack Familiar";
                $precio = 50.00;
                $desc = "Pack con 4 bebidas a elegir";
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'Pack desconocido']);
                return;
            }

            if(!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];

            $_SESSION['carrito'][] = [
                "id_producto" => $id_pack,
                "nombre" => $nombre,
                "precio" => $precio,
                "unidades" => 1,
                "tipo" => "pack_fijo",
                "descripcion" => $desc,
                "bebidas_seleccionadas" => $bebidas_elegidas
            ];

            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'Error de datos']);
        }
    }

    // 3. LÓGICA PARA PRODUCTOS SIMPLES (Pack Vegano, etc)
    // Recibe: id (por URL GET)
    public function add() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        // OJO: El JS manda POST, pero el ID va en la URL (GET)
        if(isset($_GET['id'])) {
            $id = $_GET['id'];

            // CONEXIÓN BBDD: Necesaria para sacar el precio real del Pack Vegano
            require_once 'config/db.php';
            $database = new Database();
            $db = $database->connect();
            
            // Usamos tu modelo Producto para buscar info
            $productoModel = new Producto($db);
            // NOTA: Asumo que tienes un método getById o similar. 
            // Si no lo tienes, usamos getAll y filtramos (menos eficiente pero funciona con lo que tienes).
            $todos = $productoModel->getAll();
            $producto_encontrado = null;

            foreach($todos as $p) {
                if($p->id_producto == $id) {
                    $producto_encontrado = $p;
                    break;
                }
            }

            if ($producto_encontrado) {
                if(!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];
                
                $_SESSION['carrito'][] = [
                    "id_producto" => $producto_encontrado->id_producto,
                    "nombre" => $producto_encontrado->nombre,
                    "precio" => $producto_encontrado->precio, // ¡Precio real de la BBDD!
                    "unidades" => 1,
                    "tipo" => "simple"
                ];
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'Producto no encontrado']);
            }
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'Falta ID']);
        }
    }
    // NUEVA FUNCIÓN: Devuelve el HTML del carrito actualizado para JS
    public function getCarritoHtml() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
        $total = 0;

        // Empezamos a guardar la respuesta en un buffer
        ob_start();

        if(empty($carrito)): ?>
            <div class="text-center py-5 text-muted">
                <h1 class="display-1">🛒</h1>
                <p>Tu carrito está vacío.</p>
            </div>
        <?php else: ?>
            <div class="d-flex flex-column gap-3">
                <?php foreach($carrito as $indice => $item): ?>
                    <?php $total += $item['precio']; ?>
                    <div class="card shadow-sm border-0">
                        <div class="card-body position-relative">
                            <button onclick="eliminarItem(<?=$indice?>)" class="btn btn-sm text-danger position-absolute top-0 end-0 fw-bold border-0" style="background:none;">&times;</button>
                            
                            <h6 class="fw-bold mb-1"><?= $item['nombre'] ?></h6>
                            <div class="text-warning fw-bold mb-2"><?= number_format($item['precio'], 2) ?> €</div>
                            
                            <ul class="list-unstyled small text-muted mb-0">
                                <?php if($item['tipo'] == 'menu_personalizado'): ?>
                                    <li>Principal ID: <?= $item['ingredientes']['principal_id'] ?></li>
                                    <li>Snack ID: <?= $item['ingredientes']['snack_id'] ?></li>
                                    <li>Bebida ID: <?= $item['ingredientes']['bebida_id'] ?></li>
                                <?php elseif($item['tipo'] == 'pack_fijo'): ?>
                                    <li><?= $item['descripcion'] ?></li>
                                <?php else: ?>
                                    <li>Producto individual</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; 

        $html_items = ob_get_clean(); // Guardamos el HTML de los items

        // Devolvemos un JSON con el HTML y el Total calculado
        echo json_encode([
            'html' => $html_items,
            'total' => number_format($total, 2)
        ]);
    }

    // EXTRA: Función para eliminar (ya que ponemos el botón X)
    public function remove() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if(isset($_POST['index'])) {
            $index = $_POST['index'];
            if(isset($_SESSION['carrito'][$index])) {
                unset($_SESSION['carrito'][$index]);
                $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Reordenar índices
            }
        }
        $this->getCarritoHtml(); // Devolvemos el carrito actualizado directamente
    }
    // MUESTRA LA PÁGINA DE CHECKOUT (VISUAL)
    public function checkout() {
    // 1. COMPROBAR CARRITO
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }
    $carrito = $_SESSION['carrito'];

    // 2. RESCATAR IMÁGENES DE LA BBDD (El "Bloque de Rescate")
    require_once 'config/db.php';
    $database = new Database();
    $db = $database->connect();

    $ids_productos = [];
    foreach($carrito as $c) {
        if(isset($c['id_producto'])) $ids_productos[] = $c['id_producto'];
    }

    $imagenes_map = []; // Array donde guardaremos las fotos
    if(!empty($ids_productos)) {
        $ids_str = implode(',', array_unique($ids_productos));
        // OJO: Asegúrate que la tabla se llama 'producto' o 'productos'
        $sql = "SELECT id_producto, imagen_url as imagen FROM producto WHERE id_producto IN ($ids_str)";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $imagenes_map[$row['id_producto']] = $row['imagen'];
        }
    }

    // 3. CÁLCULOS MATEMÁTICOS
    $subtotal = 0;
    foreach($carrito as $item) {
        $subtotal += $item['precio'] * $item['unidades'];
    }

    $gastos_envio = 3.00;
    $descuento = 0;
    $codigo_nombre = "";

    if(isset($_SESSION['descuento_activo'])){
        $promo = $_SESSION['descuento_activo'];
        $codigo_nombre = $promo['codigo'];
        
        if($promo['tipo'] == 'porcentaje') {
            $descuento = $subtotal * ($promo['valor'] / 100);
        } elseif($promo['tipo'] == 'envio') {
            $descuento = $gastos_envio; 
        } elseif($promo['tipo'] == 'fijo') {
            $descuento = $promo['valor'];
        }
    }

    $total_final = ($subtotal + $gastos_envio) - $descuento;
    if($total_final < 0) $total_final = 0;

    // 4. RENDERIZAR VISTA (Las variables de arriba estarán disponibles en la vista)
    $view = 'views/carrito/checkout.php';
    require_once 'views/main.php';
}

    // PROCESA EL PEDIDO (BASE DE DATOS)
    public function confirmar() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        require_once 'config/db.php';
        
        $carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
        
        if(!empty($carrito)) {
            $database = new Database();
            $db = $database->connect();

            // 1. Calcular total
            $total = 0;
            foreach($carrito as $c) $total += $c['precio'];

            // 2. Insertar Cabecera del Pedido
            // Asumimos usuario ID 1 si no hay login, o sacalo de $_SESSION['user_id']
            $usuario_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; 
            $fecha = date('Y-m-d H:i:s');
            $estado = 'Pendiente'; // O 'Pagado' si simulamos éxito directo

            $sql = "INSERT INTO pedidos (usuario_id, fecha, coste, estado) VALUES (:uid, :fecha, :coste, :estado)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':uid' => $usuario_id, 
                ':fecha' => $fecha, 
                ':coste' => $total, 
                ':estado' => $estado
            ]);
            
            $pedido_id = $db->lastInsertId(); // Cogemos el ID del pedido creado

            // 3. Insertar Líneas de Pedido
            // NOTA: Para productos compuestos, quizá quieras guardar el detalle en JSON en una columna extra
            // Aquí hago un guardado simple estándar.
            $sqlLinea = "INSERT INTO lineas_pedidos (pedido_id, producto_id, unidades) VALUES (:pid, :prodid, 1)";
            $stmtLinea = $db->prepare($sqlLinea);

            foreach($carrito as $item) {
                // Si es un menú personalizado o pack, el ID puede ser especial. 
                // Asegúrate de que 'id_producto' existe en tu sesión. Si es null, pon uno por defecto o manéjalo.
                $prodId = isset($item['id_producto']) ? $item['id_producto'] : null; 
                
                if($prodId) {
                    $stmtLinea->execute([':pid' => $pedido_id, ':prodid' => $prodId]);
                }
            }

            // 4. Vaciar carrito y éxito
            unset($_SESSION['carrito']);
            
            // Redirigir a una página de gracias
            header("Location: index.php?controller=Pedido&action=gracias"); 
        } else {
            header("Location: index.php");
        }
    }
}
?>