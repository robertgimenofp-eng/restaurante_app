<?php
require_once __DIR__ . '/../config/db.php';

class PedidoDAO {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function crearPedidoCompleto($usuario_id, $total_pedido, $carrito) {
        try {
            $this->db->beginTransaction();

            $sql = "INSERT INTO pedido (id_usuario, fecha, total, id_estado) VALUES (:usuario, CURDATE(), :total, 1)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':usuario', $usuario_id);
            $stmt->bindParam(':total', $total_pedido);
            $stmt->execute();
            
            $pedido_id = $this->db->lastInsertId();

            $sql_linea = "INSERT INTO linea_pedido (id_pedido, id_producto, cantidad, precio_unitario) VALUES (:pedido, :producto, :cantidad, :precio)";
            $stmt_linea = $this->db->prepare($sql_linea);

            foreach($carrito as $elemento) {
                $stmt_linea->bindValue(':pedido', $pedido_id);
                $stmt_linea->bindValue(':producto', $elemento['id_producto']); 
                $stmt_linea->bindValue(':cantidad', $elemento['unidades']);
                $stmt_linea->bindValue(':precio', $elemento['precio']);
                $stmt_linea->execute();
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function crearPedidoAntiguo($usuario_id, $fecha, $coste, $estado, $carrito) {
        try {
            $this->db->beginTransaction();
            $sql = "INSERT INTO pedidos (usuario_id, fecha, coste, estado) VALUES (:uid, :fecha, :coste, :estado)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':uid' => $usuario_id, 
                ':fecha' => $fecha, 
                ':coste' => $coste, 
                ':estado' => $estado
            ]);
            
            $pedido_id = $this->db->lastInsertId();

            $sqlLinea = "INSERT INTO lineas_pedidos (pedido_id, producto_id, unidades) VALUES (:pid, :prodid, 1)";
            $stmtLinea = $this->db->prepare($sqlLinea);

            foreach($carrito as $item) {
                $prodId = isset($item['id_producto']) ? $item['id_producto'] : null; 
                if($prodId) {
                    $stmtLinea->execute([':pid' => $pedido_id, ':prodid' => $prodId]);
                }
            }
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function apiListarAdmin() {
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
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function apiDetalles($id_pedido) {
        $sql = "SELECT 
                    lp.cantidad,
                    lp.precio_unitario,
                    prod.nombre as nombre_producto,
                    prod.imagen_url
                FROM linea_pedido lp
                INNER JOIN producto prod ON lp.id_producto = prod.id_producto
                WHERE lp.id_pedido = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id_pedido);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cambiarEstado($id_pedido, $id_estado) {
        $sql = "UPDATE pedido SET id_estado = :estado WHERE id_pedido = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':estado', $id_estado);
        $stmt->bindParam(':id', $id_pedido);
        return $stmt->execute();
    }
}
?>
