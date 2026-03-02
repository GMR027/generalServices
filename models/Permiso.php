<?php

namespace Model;

class Permiso extends ActiveRecord {
    protected static $tabla = 'permisos';
    protected static $columnasDB = ['id', 'cliente_id', 'proyecto_id', 'empresa_id', 'permiso'];

    public $id;
    public $cliente_id;
    public $proyecto_id;
    public $empresa_id;
    public $permiso;

    // nombres para consultas con JOIN
    public $cliente_nombre;
    public $proyecto_nombre;
    public $empresa_nombre;
    public $disciplina_nombre;   // agregado
    public $subdisciplina_nombre; // agregado

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->cliente_id = $args['cliente_id'] ?? '';
        $this->proyecto_id = $args['proyecto_id'] ?? '';
        $this->empresa_id = $args['empresa_id'] ?? '';
        $this->permiso = $args['permiso'] ?? 'leer';

        // asignar nombres si vienen en el arreglo
        $this->cliente_nombre = $args['cliente_nombre'] ?? '';
        $this->proyecto_nombre = $args['proyecto_nombre'] ?? '';
        $this->empresa_nombre = $args['empresa_nombre'] ?? '';
        $this->disciplina_nombre = $args['disciplina_nombre'] ?? '';
        $this->subdisciplina_nombre = $args['subdisciplina_nombre'] ?? '';
    }

    public function validarErrores() {
        if(!$this->cliente_id) {
            self::$errores[] = 'Seleccione un cliente';
        }
        if(!$this->proyecto_id) {
            self::$errores[] = 'Seleccione un proyecto';
        }
        if(!$this->empresa_id) {
            self::$errores[] = 'Seleccione una empresa';
        }
        if(!in_array($this->permiso, ['leer','editar'])) {
            self::$errores[] = 'Permiso no válido';
        }
        return self::$errores;
    }
}
