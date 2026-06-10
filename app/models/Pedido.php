<?php
class Pedido implements JsonSerializable {
    private $id_pedido;
    private $id_usuario;
    private $fecha;
    private $total;
    private $id_estado;
    
    // Propiedades adicionales para consultas JOIN (ej: listado de admin)
    private $nombre_usuario;
    private $direccion;
    private $nombre_estado;

    public function __construct() {}

    public function jsonSerialize(): mixed {
        return [
            'id_pedido' => $this->id_pedido,
            'id_usuario' => $this->id_usuario,
            'fecha' => $this->fecha,
            'total' => $this->total,
            'id_estado' => $this->id_estado,
            'nombre_usuario' => $this->nombre_usuario,
            'direccion' => $this->direccion,
            'nombre_estado' => $this->nombre_estado
        ];
    }

    // Getters
    public function getId_pedido() { return $this->id_pedido; }
    public function getId_usuario() { return $this->id_usuario; }
    public function getFecha() { return $this->fecha; }
    public function getTotal() { return $this->total; }
    public function getId_estado() { return $this->id_estado; }
    public function getNombre_usuario() { return $this->nombre_usuario; }
    public function getDireccion() { return $this->direccion; }
    public function getNombre_estado() { return $this->nombre_estado; }

    // Setters
    public function setId_pedido($val) { $this->id_pedido = $val; }
    public function setId_usuario($val) { $this->id_usuario = $val; }
    public function setFecha($val) { $this->fecha = $val; }
    public function setTotal($val) { $this->total = $val; }
    public function setId_estado($val) { $this->id_estado = $val; }
    public function setNombre_usuario($val) { $this->nombre_usuario = $val; }
    public function setDireccion($val) { $this->direccion = $val; }
    public function setNombre_estado($val) { $this->nombre_estado = $val; }
}
?>
