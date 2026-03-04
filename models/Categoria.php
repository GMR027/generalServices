<?php

namespace Model;

class Categoria extends ActiveRecord {
    protected static $tabla = 'categorias';
    protected static $columnasDB = ['id', 'nombre'];

    public $id;
    public $nombre;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
    }

    public function validarErrores() {
        if(!$this->nombre) {
            self::$errores[] = 'El nombre de la categoría es obligatorio';
        }
        return self::$errores;
    }
}
