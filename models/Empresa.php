<?php

namespace Model;

class Empresa extends ActiveRecord {
    protected static $tabla = 'empresas';
    protected static $columnasDB = ['id', 'nombre', 'nombreCliente', 'email', 'telefono', 'logo', 'rfc', 'direccion'];

    public $id;
    public $nombre;
    public $nombreCliente;
    public $email;
    public $telefono;
    public $logo;
    public $rfc;
    public $direccion;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->nombreCliente = $args['nombreCliente'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->logo = $args['logo'] ?? '';
        $this->rfc = $args['rfc'] ?? '';
        $this->direccion = $args['direccion'] ?? '';
    }

    public function validarErrores() {
        if(!$this->nombre) {
            self::$errores[] = 'El nombre de la empresa es obligatorio';
        }
        if(!$this->nombreCliente) {
            self::$errores[] = 'El nombre del cliente es obligatorio';
        }
        if(!$this->email) {
            self::$errores[] = 'El email es obligatorio';
        }
        if(!$this->telefono) {
            self::$errores[] = 'El teléfono es obligatorio';
        }
        return self::$errores;
    }
}
