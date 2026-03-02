<?php

namespace Model;

class Disciplina extends ActiveRecord {
    protected static $tabla = 'disciplinas';
    protected static $columnasDB = ['id', 'nombre'];

    public $id;
    public $nombre;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
    }

    public function validarErrores() {
        if(!$this->nombre) {
            self::$errores[] = 'El nombre de la disciplina es obligatorio';
        }
        return self::$errores;
    }
}
