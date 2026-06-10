<?php
class Oferta implements JsonSerializable {
    private $id_oferta;
    private $tipo;
    private $valor;
    private $condiciones_min_cantid;
    private $fecha_inicio;
    private $fecha_fin;
    private $codigo_opcional;

    public function __construct() {}

    public function jsonSerialize(): mixed {
        return [
            'id_oferta' => $this->id_oferta,
            'tipo' => $this->tipo,
            'valor' => $this->valor,
            'condiciones_min_cantid' => $this->condiciones_min_cantid,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'codigo_opcional' => $this->codigo_opcional
        ];
    }

    public function getId_oferta() { return $this->id_oferta; }
    public function getTipo() { return $this->tipo; }
    public function getValor() { return $this->valor; }
    public function getCondiciones_min_cantid() { return $this->condiciones_min_cantid; }
    public function getFecha_inicio() { return $this->fecha_inicio; }
    public function getFecha_fin() { return $this->fecha_fin; }
    public function getCodigo_opcional() { return $this->codigo_opcional; }

    public function setId_oferta($val) { $this->id_oferta = $val; }
    public function setTipo($val) { $this->tipo = $val; }
    public function setValor($val) { $this->valor = $val; }
    public function setCondiciones_min_cantid($val) { $this->condiciones_min_cantid = $val; }
    public function setFecha_inicio($val) { $this->fecha_inicio = $val; }
    public function setFecha_fin($val) { $this->fecha_fin = $val; }
    public function setCodigo_opcional($val) { $this->codigo_opcional = $val; }

    // Permitir la creacion dinamica de propiedades si la tabla tiene más columnas
    public function __set($name, $value) {
        $this->$name = $value;
    }
}
?>
