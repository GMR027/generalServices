<?php

namespace Model;

class Proyecto extends ActiveRecord {
    protected static $tabla = 'proyectos';
    protected static $columnasDB = ['id','nombre','ubicacion','disciplina_id','subdisciplina_id','fecha_inicio','fecha_fin'];

    public $id;
    public $nombre;
    public $ubicacion;
    public $disciplina_id;
    public $subdisciplina_id;
    public $fecha_inicio;
    public $fecha_fin;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->ubicacion = $args['ubicacion'] ?? '';
        $this->disciplina_id = $args['disciplina_id'] ?? '';
        $this->subdisciplina_id = $args['subdisciplina_id'] ?? '';
        $this->fecha_inicio = $args['fecha_inicio'] ?? '';
        $this->fecha_fin = $args['fecha_fin'] ?? '';
    }

    public function validarErrores() {
        if(!$this->nombre) {
            self::$errores[] = 'El nombre del proyecto es obligatorio';
        }
        if(!$this->ubicacion) {
            self::$errores[] = 'La ubicación es obligatoria';
        }
        if(!$this->disciplina_id) {
            self::$errores[] = 'Seleccione disciplina';
        }
        if(!$this->subdisciplina_id) {
            self::$errores[] = 'Seleccione subdisciplina';
        }
        if(!$this->fecha_inicio) {
            self::$errores[] = 'Fecha de inicio obligatoria';
        }
        return self::$errores;
    }
}
