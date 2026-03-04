<?php

namespace Model;

class Minuta extends ActiveRecord {
    protected static $tabla = 'minutas';
    protected static $columnasDB = [
        'id',
        'proyecto_id',
        'fecha',
        'tituloJunta',
        'personalJunta',
        'personalCliente',
        'categoriaJunta',
        'informacionJunta',
        'compromisos'
    ];

    public $id;
    public $proyecto_id;
    public $fecha;
    public $tituloJunta;
    public $personalJunta;
    public $personalCliente;
    public $categoriaJunta;
    public $informacionJunta;
    public $compromisos;

    // campo auxiliar para joins
    public $proyecto_nombre;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->proyecto_id = $args['proyecto_id'] ?? '';
        $this->fecha = $args['fecha'] ?? date('Y-m-d');
        $this->tituloJunta = $args['tituloJunta'] ?? '';
        $this->personalJunta = $args['personalJunta'] ?? '';
        $this->personalCliente = $args['personalCliente'] ?? '';
        $this->categoriaJunta = $args['categoriaJunta'] ?? '';
        $this->informacionJunta = $args['informacionJunta'] ?? '';
        $this->compromisos = $args['compromisos'] ?? '';
        $this->proyecto_nombre = $args['proyecto_nombre'] ?? '';
    }

    public function validarErrores() {
        if(!$this->proyecto_id) {
            self::$errores[] = 'Seleccione un proyecto';
        }
        if(!$this->fecha) {
            self::$errores[] = 'La fecha es obligatoria';
        }
        if(!$this->tituloJunta) {
            self::$errores[] = 'El título de la junta es obligatorio';
        }
        // demás campos son opcionales, pero podemos advertir si se requieren
        return self::$errores;
    }
}
