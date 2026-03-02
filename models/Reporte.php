<?php

namespace Model;

class Reporte extends ActiveRecord {
    protected static $tabla = 'reportes';
    // columnas de la tabla tal como se definieron en SQL
    protected static $columnasDB = [
        'id',
        'proyecto_id',
        'actividad',
        'area_zonal',
        'nivel',
        'permisoTrabajo',
        'horastrabajadas',
        'imagenes',
        'created_at' // timestamp of creation
    ];

    public $id;
    public $proyecto_id;
    public $actividad;
    public $area_zonal;
    public $nivel;
    public $permisoTrabajo;
    public $horastrabajadas;
    public $imagenes; // json string
    public $created_at;
    public $permiso_tipo; // permiso del usuario sobre el proyecto (no es columna DB)
    // alias fields from joins
    public $proyecto_nombre;
    public $disciplina_nombre;
    public $subdisciplina_nombre;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->proyecto_id = $args['proyecto_id'] ?? '';
        $this->actividad = $args['actividad'] ?? '';
        $this->area_zonal = $args['area_zonal'] ?? '';
        $this->nivel = $args['nivel'] ?? '';
        $this->permisoTrabajo = $args['permisoTrabajo'] ?? '';
        $this->horastrabajadas = $args['horastrabajadas'] ?? '';
        // almacenar siempre JSON válido (lista vacía si no hay imágenes)
        if(isset($args['imagenes']) && $args['imagenes'] !== '') {
            $this->imagenes = $args['imagenes'];
        } else {
            $this->imagenes = json_encode([]);
        }
        // set creation timestamp if provided, otherwise use current datetime
        $this->created_at = $args['created_at'] ?? date('Y-m-d H:i:s');
        // campo extra que puede venir de consultas con JOIN
        $this->permiso_tipo = $args['permiso_tipo'] ?? null;
        // aliases from joins (proyecto/disciplina/subdisciplina)
        $this->proyecto_nombre = $args['proyecto_nombre'] ?? null;
        $this->disciplina_nombre = $args['disciplina_nombre'] ?? null;
        $this->subdisciplina_nombre = $args['subdisciplina_nombre'] ?? null;
    }

    public function validarErrores() {
        if(!$this->proyecto_id) {
            self::$errores[] = 'Seleccione un proyecto';
        }
        if(!$this->actividad) {
            self::$errores[] = 'Ingrese actividad';
        }
        // otros campos opcionales
        return self::$errores;
    }
}
