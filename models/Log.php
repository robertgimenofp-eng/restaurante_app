<?php
class Log implements JsonSerializable {
    private $id_log; // o id_auditoria
    private $id_usuario;
    private $id_entidad;
    private $entidad_afectada;
    private $accion;
    private $descripcion;
    private $ip;
    private $fecha_hora;

    // Join extra
    private $nombre_usuario;

    public function __construct() {}

    public function jsonSerialize() {
        return [
            'id_usuario' => $this->id_usuario,
            'id_entidad' => $this->id_entidad,
            'entidad_afectada' => $this->entidad_afectada,
            'accion' => $this->accion,
            'descripcion' => $this->descripcion,
            'ip' => $this->ip,
            'fecha_hora' => $this->fecha_hora,
            'nombre_usuario' => $this->nombre_usuario
        ];
    }

    public function getId_usuario() { return $this->id_usuario; }
    public function getId_entidad() { return $this->id_entidad; }
    public function getEntidad_afectada() { return $this->entidad_afectada; }
    public function getAccion() { return $this->accion; }
    public function getDescripcion() { return $this->descripcion; }
    public function getIp() { return $this->ip; }
    public function getFecha_hora() { return $this->fecha_hora; }
    public function getNombre_usuario() { return $this->nombre_usuario; }

    public function setId_usuario($val) { $this->id_usuario = $val; }
    public function setId_entidad($val) { $this->id_entidad = $val; }
    public function setEntidad_afectada($val) { $this->entidad_afectada = $val; }
    public function setAccion($val) { $this->accion = $val; }
    public function setDescripcion($val) { $this->descripcion = $val; }
    public function setIp($val) { $this->ip = $val; }
    public function setFecha_hora($val) { $this->fecha_hora = $val; }
    public function setNombre_usuario($val) { $this->nombre_usuario = $val; }

    public function __set($name, $value) {
        $this->$name = $value;
    }
}
?>
