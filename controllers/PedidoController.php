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
        require_once __DIR__ . '/../models/PedidoDAO.php';
        $pedidoDAO = new PedidoDAO();
   
            // 2. CÁLCULO DE COSTES
        $usuario_id = $_SESSION['identity']->getId_usuario(); 
        
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
            // 3. y 4. GUARDAR EL PEDIDO Y SUS LÍNEAS a través del DAO
            $pedidoDAO->crearPedidoCompleto($usuario_id, $total_pedido, $_SESSION['carrito']);

            // 5. LIMPIEZA
            unset($_SESSION['carrito']);
            if(isset($_SESSION['descuento_activo'])) unset($_SESSION['descuento_activo']);

            // 6. MENSAJE DE ÉXITO Y REDIRECCIÓN
            $_SESSION['mensaje'] = "✅ Pedido confirmado.\n\nSubtotal: " . number_format($subtotal_productos, 2) . "€\n+ Envío: 3.00€\n- Descuento: " . number_format($descuento_total, 2) . "€\n------------------\nTOTAL: " . number_format($total_pedido, 2) . "€";
            header("Location: index.php");
            exit();

        } catch (PDOException $e) {
            $_SESSION['error'] = "Error al procesar pedido: " . $e->getMessage();
            header("Location: index.php?controller=Carrito&action=checkout");
            exit();
        }
    }
    // API: Listar todos los pedidos (para el Admin)
    public function apiListar() {
        // Seguridad: Solo admin
        if (!isset($_SESSION['identity']) || $_SESSION['identity']->getRol() != 'admin') {
            echo json_encode([]);
            exit();
        }

        require_once __DIR__ . '/../models/PedidoDAO.php';
        $pedidoDAO = new PedidoDAO();

        try {
            $pedidos = $pedidoDAO->apiListarAdmin();
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

        require_once __DIR__ . '/../models/PedidoDAO.php';
        $pedidoDAO = new PedidoDAO();

        $detalles = $pedidoDAO->apiDetalles($id_pedido);
        echo json_encode($detalles);
        exit();
    }

    // API: Cambiar Estado
    public function apiCambiarEstado() {
        // Recibimos JSON del Javascript
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['id_pedido']) && isset($data['id_estado'])) {
            require_once __DIR__ . '/../models/PedidoDAO.php';
            $pedidoDAO = new PedidoDAO();
            
            if($pedidoDAO->cambiarEstado($data['id_pedido'], $data['id_estado'])){
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        }
        exit();
    }

    // API: Eliminar Pedido
    public function apiEliminar() {
        if (!isset($_SESSION['identity']) || $_SESSION['identity']->getRol() != 'admin') {
            echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
            exit();
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['id_pedido'])) {
            require_once __DIR__ . '/../models/PedidoDAO.php';
            require_once __DIR__ . '/../models/LogDAO.php';
            $pedidoDAO = new PedidoDAO();
            
            if($pedidoDAO->delete($data['id_pedido'])){
                LogDAO::save($data['id_pedido'], 'pedido', 'DELETE', "Se eliminó el pedido #" . $data['id_pedido']);
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el pedido']);
            }
        }
        exit();
    }
}