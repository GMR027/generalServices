<?php

namespace Model;

class ActividadObra extends ActiveRecord {
    protected static $tabla = 'actividades_obra';
    protected static $columnasDB = [
        'id',
        'proyecto_id',
        'item',
        'actividad',
        'fecha_inicio',
        'fecha_fin',
        'dias',
        'porcentajeAvance',
        'comentarios'
    ];

    public $id;
    public $proyecto_id;
    public $item;
    public $actividad;
    public $fecha_inicio;
    public $fecha_fin;
    public $dias;
    public $porcentajeAvance;
    public $comentarios;

    // campos auxiliares
    public $proyecto_nombre;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->proyecto_id = $args['proyecto_id'] ?? '';
        $this->item = $args['item'] ?? '';
        $this->actividad = $args['actividad'] ?? '';
        $this->fecha_inicio = $args['fecha_inicio'] ?? '';
        $this->fecha_fin = $args['fecha_fin'] ?? '';
        // calcular dias si no viene o si fechas cambian
        if(!empty($this->fecha_inicio) && !empty($this->fecha_fin)) {
            $ini = strtotime($this->fecha_inicio);
            $fin = strtotime($this->fecha_fin);
            if($ini && $fin) {
                $this->dias = intval(($fin - $ini) / 86400) + 1;
            } else {
                $this->dias = $args['dias'] ?? 0;
            }
        } else {
            $this->dias = $args['dias'] ?? 0;
        }
        $this->porcentajeAvance = $args['porcentajeAvance'] ?? 0;
        $this->comentarios = $args['comentarios'] ?? '';

        $this->proyecto_nombre = $args['proyecto_nombre'] ?? '';
    }

    public function validarErrores() {
        if(!$this->proyecto_id) {
            self::$errores[] = 'Seleccione un proyecto';
        }
        if(!is_numeric($this->item) || $this->item <= 0) {
            self::$errores[] = 'Item inválido';
        }
        if(!$this->actividad) {
            self::$errores[] = 'Ingrese la actividad';
        }
        if(!$this->fecha_inicio) {
            self::$errores[] = 'Fecha de inicio requerida';
        }
        if(!$this->fecha_fin) {
            self::$errores[] = 'Fecha de fin requerida';
        }
        if($this->fecha_inicio && $this->fecha_fin) {
            $ini = strtotime($this->fecha_inicio);
            $fin = strtotime($this->fecha_fin);
            if($ini && $fin && $fin < $ini) {
                self::$errores[] = 'La fecha de fin debe ser posterior a la de inicio';
            }
        }
        if(!is_numeric($this->porcentajeAvance) || $this->porcentajeAvance < 0 || $this->porcentajeAvance > 100) {
            self::$errores[] = 'Porcentaje de avance inválido';
        }
        // dias calculados automáticamente
        return self::$errores;
    }
}
