<?php
// controllers/PedidoController.php

class PedidoController {
    public function hacer() {
            // 1. SEGURIDAD
        if (!isset($_SESSION['identity'])) {
            header("Location: index.php?controller=Auth&action=showlogin");
            exit(); 
        }

        if (!isset($_SESSION['carrito']) || count($_SESSION['carrito']) == 0) {
            header("Location: index.php");
            exit();
        }

            // --- CONEXIÓN BD ---
        require_once 'config/db.php';
        $database = new Database();
        $db = $database->connect();
   
            // 2. CÁLCULO DE COSTES
        $usuario_id = $_SESSION['identity']->id_usuario; 
        
        // A) Subtotal de los productos (Hamburguesas, bebidas...)
        $subtotal_productos = 0;
        foreach($_SESSION['carrito'] as $elemento){
            $subtotal_productos += $elemento['precio'] * $elemento['unidades'];
        }

        // B) Gastos de envío (LO QUE PEDISTE: 3.00€ FIJOS)
        $gastos_envio = 3.00;

        // C) Calcular Descuento
        $descuento_total = 0;

        if(isset($_SESSION['descuento_activo'])){
            $promo = $_SESSION['descuento_activo'];
            
            if($promo['tipo'] == 'porcentaje') {
                // El porcentaje se aplica sobre los productos, no sobre el envío
                $descuento_total = $subtotal_productos * ($promo['valor'] / 100);
            } 
            elseif($promo['tipo'] == 'envio') {
                // Si el cupón es de envío gratis, descontamos los 3.00€
                $descuento_total = $gastos_envio; 
                // Opcional: Si quieres poner envío a 0 visualmente
                $gastos_envio = 0; 
            }
            elseif($promo['tipo'] == 'fijo') {
                $descuento_total = $promo['valor'];
            }
        }

        // D) TOTAL FINAL A GUARDAR EN LA BBDD
        // (Subtotal + Envío original 3€) - Descuento
        // Nota: Si arriba pusimos gastos_envio a 0 por el cupón, aquí sumará 0.
        $total_pedido = ($subtotal_productos + 3.00) - $descuento_total;
        
        // Si era cupón de envío gratis, ajustamos para que la matemática sea exacta:
        if(isset($promo) && $promo['tipo'] == 'envio') {
             $total_pedido = $subtotal_productos; // Solo paga productos
        }

        if($total_pedido < 0) $total_pedido = 0;


        try {
            // 3. GUARDAR EL PEDIDO (Tabla 'pedido')
            $sql = "INSERT INTO pedido (id_usuario, fecha, total, id_estado) VALUES (:usuario, CURDATE(), :total, 1)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':usuario', $usuario_id);
            $stmt->bindParam(':total', $total_pedido);
            $stmt->execute();
            
            $pedido_id = $db->lastInsertId();

            // 4. GUARDAR LOS PRODUCTOS (Tabla 'linea_pedido')
            // AQUÍ ESTÁ LA CLAVE DE LA IMAGEN: Guardamos el 'id_producto' correcto.
            // Luego, al leer el pedido, el sistema busca la foto asociada a este ID.
            $sql_linea = "INSERT INTO linea_pedido (id_pedido, id_producto, cantidad, precio_unitario) VALUES (:pedido, :producto, :cantidad, :precio)";
            $stmt_linea = $db->prepare($sql_linea);

            foreach($_SESSION['carrito'] as $elemento){
                // Aseguramos que guardamos el ID del producto que toca
                $stmt_linea->bindValue(':pedido', $pedido_id);
                $stmt_linea->bindValue(':producto', $elemento['id_producto']); 
                $stmt_linea->bindValue(':cantidad', $elemento['unidades']);
                $stmt_linea->bindValue(':precio', $elemento['precio']);
                $stmt_linea->execute();
            }

            // 5. LIMPIEZA
            unset($_SESSION['carrito']);
            if(isset($_SESSION['descuento_activo'])) unset($_SESSION['descuento_activo']);

            // 6. MENSAJE DE ÉXITO
            echo "<script>
                    alert('✅ Pedido confirmado.\\n\\nSubtotal: " . number_format($subtotal_productos, 2) . "€\\n+ Envío: 3.00€\\n- Descuento: " . number_format($descuento_total, 2) . "€\\n------------------\\nTOTAL: " . number_format($total_pedido, 2) . "€');
                    window.location.href = 'index.php';
                  </script>";

        } catch (PDOException $e) {
            echo "<h1>Error al procesar pedido:</h1><p>" . $e->getMessage() . "</p>";
            die();
        }
    }
    // API: Listar todos los pedidos (para el Admin)
    public function apiListar() {
        // Seguridad: Solo admin
        if (!isset($_SESSION['identity']) || $_SESSION['identity']->rol != 'admin') {
            echo json_encode([]);
            exit();
        }

        require_once 'config/db.php';
        $database = new Database();
        $db = $database->connect();

        // SQL CORREGIDO: Solo pedimos lo que existe de verdad
        $sql = "SELECT 
                    p.id_pedido,
                    p.fecha,
                    p.total,
                    u.nombre as nombre_usuario,
                    u.direccion,       /* Solo direccion */
                    ep.nombre_estado,
                    ep.id_estado
                FROM pedido p
                INNER JOIN usuario u ON p.id_usuario = u.id_usuario
                INNER JOIN estado_pedido ep ON p.id_estado = ep.id_estado
                ORDER BY p.id_pedido DESC";

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($pedidos);
        } catch (PDOException $e) {
            // Si falla, enviamos el error al JS para verlo en consola
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit();
    }
    // API: Obtener detalles de un pedido (Hamburguesas)
    public function apiDetalles() {
        if (!isset($_GET['id'])) exit();
        $id_pedido = $_GET['id'];

        require_once 'config/db.php'; $database = new Database(); $db = $database->connect();

        $sql = "SELECT 
                    lp.cantidad,
                    lp.precio_unitario,
                    prod.nombre as nombre_producto,
                    prod.imagen_url
                FROM linea_pedido lp
                INNER JOIN producto prod ON lp.id_producto = prod.id_producto
                WHERE lp.id_pedido = :id";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id_pedido);
        $stmt->execute();
        
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        exit();
    }

    // API: Cambiar Estado
    public function apiCambiarEstado() {
        // Recibimos JSON del Javascript
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['id_pedido']) && isset($data['id_estado'])) {
            require_once 'config/db.php'; $database = new Database(); $db = $database->connect();

            $sql = "UPDATE pedido SET id_estado = :estado WHERE id_pedido = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':estado', $data['id_estado']);
            $stmt->bindParam(':id', $data['id_pedido']);
            
            if($stmt->execute()){
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        }
        exit();
    }
}