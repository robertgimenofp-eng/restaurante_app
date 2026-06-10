<?php


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

            
            // pero para que funcione vamos a guardar los IDs y el precio fijo.
            // Luego en la vista del carrito ya recuperaremos los nombres.
            
            $item = [
                "tipo" => "menu_personalizado",
                "nombre" => "Menú Personalizado", 
                "precio" => 13.50,
                "unidades" => 1,
                "ingredientes" => [
                    "principal_id" => $_POST['principal'],
                    "snack_id"     => $_POST['snack'],
                    "bebida_id"    => $_POST['bebida']
                ]
            ];

            $_SESSION['carrito'][] = $item;

            // Respuesta al JS con ÉXITO
            echo json_encode(['status' => 'success']);
        } else {
            // Respuesta al JS con ERROR
            echo json_encode(['status' => 'error', 'msg' => 'Faltan ingredientes']);
        }
    }
// 1. LÓGICA PARA LOS PACKS DE BEBIDAS (Amigos y Familiar)
    // Recibe: id_pack y bebidas
    public function addPackComplejo() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        if(isset($_POST['id_pack'])) {
            $id_pack = $_POST['id_pack'];
            
            // Recogemos las bebidas dinámicamente
            $bebidas_elegidas = [];
            foreach($_POST as $key => $value) {
                // Buscamos campos que contengan la palabra "bebida" 
                if(strpos($key, 'bebida') !== false && !empty($value)) {
                    $bebidas_elegidas[] = $value; // Guardamos el ID de la bebida
                }
            }

            // Asignamos datos

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

    // 3. LÓGICA PARA PRODUCTOS SIMPLES (Pack Vegano)
    // Recibe: id (por URL GET)
    public function add() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        // El JS manda POST, pero el ID va en la URL (GET)
        if(isset($_GET['id'])) {
            $id = $_GET['id'];

            require_once __DIR__ . '/../models/ProductoDAO.php';
            $productoModel = new ProductoDAO(); 

            $todos = $productoModel->getAll();
            $producto_encontrado = null;

            foreach($todos as $p) {
                if($p->getId_producto() == $id) {
                    $producto_encontrado = $p;
                    break;
                }
            }

            if ($producto_encontrado) {
                if(!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];
                
                $_SESSION['carrito'][] = [
                    "id_producto" => $producto_encontrado->getId_producto(),
                    "nombre" => $producto_encontrado->getNombre(),
                    "precio" => $producto_encontrado->getPrecio(),
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
    // Devuelve el HTML del carrito actualizado para JS
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

    // Función para eliminar
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

    // Función para cambiar cantidad
    public function changeQuantity() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if(isset($_POST['index']) && isset($_POST['change'])) {
            $index = $_POST['index'];
            $change = (int)$_POST['change'];
            
            if(isset($_SESSION['carrito'][$index])) {
                $_SESSION['carrito'][$index]['unidades'] += $change;
                
                // Si la cantidad llega a 0, eliminamos el item
                if($_SESSION['carrito'][$index]['unidades'] <= 0) {
                    unset($_SESSION['carrito'][$index]);
                    $_SESSION['carrito'] = array_values($_SESSION['carrito']);
                }
            }
        }
        echo json_encode(['status' => 'success']);
    }

    // MUESTRA LA PÁGINA DE CHECKOUT
    public function checkout() {
    // 1. COMPROBAR CARRITO
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }
    $carrito = $_SESSION['carrito'];

    // 2. RESCATAR IMÁGENES DE LA BBDD
    require_once __DIR__ . '/../models/ProductoDAO.php';
    $productoDAO = new ProductoDAO();

    $ids_productos = [];
    foreach($carrito as $c) {
        if(isset($c['id_producto'])) $ids_productos[] = $c['id_producto'];
    }

    $imagenes_map = $productoDAO->getImagenesByIds($ids_productos);

    // 3. CÁLCULOS
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

    // 4. RENDERIZAR VISTA
    $view = 'views/carrito/checkout.php';
    require_once __DIR__ . '/../views/main.php';
}

    // PROCESA EL PEDIDO
    public function confirmar() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        
        $carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
        
        if(!empty($carrito)) {
            require_once __DIR__ . '/../models/PedidoDAO.php';
            $pedidoDAO = new PedidoDAO();

            // 1. Calcular total
            $total = 0;
            foreach($carrito as $c) $total += $c['precio'];

            // 2. Insertar Cabecera del Pedido
            $usuario_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; 
            $fecha = date('Y-m-d H:i:s');
            $estado = 'Pendiente'; 

            $pedidoDAO->crearPedidoAntiguo($usuario_id, $fecha, $total, $estado, $carrito);

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