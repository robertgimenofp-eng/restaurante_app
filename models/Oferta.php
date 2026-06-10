<?php
class Oferta implements JsonSerializable {
    private $id_oferta;
    private $codigo_opcional;
    private $descuento_porcentaje;
    private $fecha_inicio;
    private $fecha_fin;

    public function __construct() {}

    public function jsonSerialize(): mixed {
        return [
            'id_oferta' => $this->id_oferta,
            'codigo_opcional' => $this->codigo_opcional,
            'descuento_porcentaje' => $this->descuento_porcentaje,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin
        ];
    }

    public function getId_oferta() { return $this->id_oferta; }
    public function getCodigo_opcional() { return $this->codigo_opcional; }
    public function getDescuento_porcentaje() { return $this->descuento_porcentaje; }
    public function getFecha_inicio() { return $this->fecha_inicio; }
    public function getFecha_fin() { return $this->fecha_fin; }

    public function setId_oferta($val) { $this->id_oferta = $val; }
    public function setCodigo_opcional($val) { $this->codigo_opcional = $val; }
    public function setDescuento_porcentaje($val) { $this->descuento_porcentaje = $val; }
    public function setFecha_inicio($val) { $this->fecha_inicio = $val; }
    public function setFecha_fin($val) { $this->fecha_fin = $val; }

    // Permitir la creacion dinamica de propiedades si la tabla tiene más columnas
    public function __set($name, $value) {
        $this->$name = $value;
    }
}
?>
