<?php

namespace Model;

class Subdisciplina extends ActiveRecord {
    protected static $tabla = 'subdisciplinas';
    protected static $columnasDB = ['id', 'disciplina_id', 'nombre'];

    public $id;
    public $disciplina_id;
    public $nombre;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->disciplina_id = $args['disciplina_id'] ?? '';
        $this->nombre = $args['nombre'] ?? '';
    }

    public function validarErrores() {
        if(!$this->disciplina_id) {
            self::$errores[] = 'Debe seleccionar una disciplina';
        }
        if(!$this->nombre) {
            self::$errores[] = 'El nombre de la subdisciplina es obligatorio';
        }
        return self::$errores;
    }
}
