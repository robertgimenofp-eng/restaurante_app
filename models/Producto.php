<?php

class Producto implements JsonSerializable {

    //Atributos de la clase
    private $id_producto;
    private $nombre;
    private $descripcion;
    private $precio;
    private $stock;
    private $categoria;
    private $imagen_url;

    public function __construct() {}

    //Metodo para serializar a JSON
    public function jsonSerialize() {
        return [
            'id_producto' => $this->id_producto,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'stock' => $this->stock,
            'categoria' => $this->categoria,
            'imagen_url' => $this->imagen_url
        ];
    }

    //GETTERS
    public function getId_producto(){
        return $this->id_producto;
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function getDescripcion(){
        return $this->descripcion;
    }

    public function getPrecio(){
        return $this->precio;
    }

    public function getStock(){
        return $this->stock;
    }

    public function getCategoria(){
        return $this->categoria;
    }

    public function getImagen_url(){
        return $this->imagen_url;
    }

    //SETTERS
    public function setId_producto($id_producto){
        $this->id_producto = $id_producto;
    }

    public function setNombre($nombre){
        $this->nombre = $nombre;
    }

    public function setDescripcion($descripcion){
        $this->descripcion = $descripcion;
    }

    public function setPrecio($precio){
        $this->precio = $precio;
    }

    public function setStock($stock){
        $this->stock = $stock;
    }

    public function setCategoria($categoria){
        $this->categoria = $categoria;
    }

    public function setImagen_url($imagen_url){
        $this->imagen_url = $imagen_url;
    }
}
