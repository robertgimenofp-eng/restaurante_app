<?php
abstract class Usuario {
    protected $id_usuario;
    protected $nombre;
    protected $email;
    protected $password;
    protected $telefono;
    protected $direccion;
    protected $rol;
    protected $fecha_registro;

    public function __construct() {}

    public function getId_usuario() { return $this->id_usuario; }
    public function getNombre() { return $this->nombre; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getTelefono() { return $this->telefono; }
    public function getDireccion() { return $this->direccion; }
    public function getRol() { return $this->rol; }
    public function getFecha_registro() { return $this->fecha_registro; }

    public function setId_usuario($id) { $this->id_usuario = $id; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setEmail($email) { $this->email = $email; }
    public function setPassword($pass) { $this->password = $pass; }
    public function setTelefono($telf) { $this->telefono = $telf; }
    public function setDireccion($dir) { $this->direccion = $dir; }
    public function setRol($rol) { $this->rol = $rol; }
    public function setFecha_registro($fecha) { $this->fecha_registro = $fecha; }

    abstract public function isAdministrador();
}

class Cliente extends Usuario {
    public function isAdministrador() {
        return false;
    }
}

class Admin extends Usuario {
    public function isAdministrador() {
        return true;
    }
}
?>
