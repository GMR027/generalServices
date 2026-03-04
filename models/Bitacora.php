<?php

namespace Model;

class Bitacora extends ActiveRecord {
    protected static $tabla = 'bitacoras';
    protected static $columnasDB = [
        'id',
        'fecha',
        'proyecto_id',
        'categoria_id',
        'comentarios',
        'usuario',
        'nivelImportancia'
    ];

    public $id;
    public $fecha;
    public $proyecto_id;
    public $categoria_id;
    public $comentarios;
    public $usuario;
    public $nivelImportancia;

    // campos auxiliares de consultas con JOIN
    public $proyecto_nombre;
    public $categoria_nombre;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        // si no se pasa fecha usamos la fecha actual
        $this->fecha = $args['fecha'] ?? date('Y-m-d');
        $this->proyecto_id = $args['proyecto_id'] ?? '';
        $this->categoria_id = $args['categoria_id'] ?? '';
        $this->comentarios = $args['comentarios'] ?? '';
        $this->usuario = $args['usuario'] ?? '';
        $this->nivelImportancia = $args['nivelImportancia'] ?? 0;

        $this->proyecto_nombre = $args['proyecto_nombre'] ?? '';
        $this->categoria_nombre = $args['categoria_nombre'] ?? '';
    }

    public function validarErrores() {
        if(!$this->fecha) {
            self::$errores[] = 'La fecha es obligatoria';
        }
        if(!$this->proyecto_id) {
            self::$errores[] = 'Seleccione un proyecto';
        }
        if(!$this->categoria_id) {
            self::$errores[] = 'Seleccione una categoría';
        }
        if(!$this->comentarios) {
            self::$errores[] = 'Ingrese comentarios';
        }
        if(!$this->usuario) {
            self::$errores[] = 'Ingrese el usuario';
        }
        if(!is_numeric($this->nivelImportancia)) {
            self::$errores[] = 'Nivel de importancia inválido';
        }
        return self::$errores;
    }
}
