<?php

namespace Model;

class ActiveRecord {
    // Base de datos y configuración
    protected static $infoBasedatos;
    protected static $columnasDB = [];
    protected static $errores = [];
    protected static $tabla = '';

    // configurar conexión
    public static function confDatabase($baseDatos) {
        self::$infoBasedatos = $baseDatos;
    }

    // atributos según columnas
    public function atributos() {
        $atributos = [];
        foreach(static::$columnasDB as $columna) {
            if($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarDatos() {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach($atributos as $key => $value) {
            // escape_string expects a string; guard against arrays or objects
            if(is_array($value) || is_object($value)) {
                // convert complex types to JSON so nothing fatal happens
                $value = json_encode($value);
            }
            $sanitizado[$key] = self::$infoBasedatos->escape_string($value);
        }
        return $sanitizado;
    }

    public function guardar() {
        if(!is_null($this->id)) {
            $this->actualizar();
        } else {
            $this->crear();
        }
    }

    public function crear() {
        $atributos = $this->sanitizarDatos();
        $query = "INSERT INTO " . static::$tabla . " (";
        $query .= join(', ', array_keys($atributos));
        $query .= ") VALUES ('";
        $query .= join("', '", array_values($atributos));
        $query .= "')";

        $resultado = self::$infoBasedatos->query($query);
        return $resultado;
    }

    public function actualizar() {
        $atributos = $this->sanitizarDatos();
        $valores = [];
        foreach($atributos as $key => $value) {
            $valores[] = "$key='$value'";
        }
        $formatovalores = join(', ', $valores);

        $query = "UPDATE " .  static::$tabla . " SET ";
        $query .= $formatovalores;
        $query .= " WHERE id = '" . self::$infoBasedatos->escape_string($this->id) . "' LIMIT 1";

        $resultado = self::$infoBasedatos->query($query);
        return $resultado;
    }

    public static function getErrores() {
        return static::$errores;
    }

    public function validarErrores() {
        static::$errores = [];
        return static::$errores;
    }

    public static function all() {
        $query = "SELECT * FROM " . static::$tabla;
        $resultado = self::consultaSQL($query);
        return $resultado;
    }

    public static function consultaSQL($query) {
        $resultado = self::$infoBasedatos->query($query);
        $array = [];
        while($registroDB = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registroDB);
        }
        return $array;
    }

    /**
     * Comprueba si la tabla del modelo contiene una columna específica.
     * Esto permite construir consultas condicionales cuando la base de
     * datos no está completamente migrada.
     *
     * @param string $columna
     * @return bool
     */
    public static function hasColumn($columna) {
        $tabla = static::$tabla;
        $sql = "SHOW COLUMNS FROM {$tabla} LIKE '" . self::$infoBasedatos->escape_string($columna) . "'";
        $resultado = self::$infoBasedatos->query($sql);
        return ($resultado && $resultado->num_rows > 0);
    }

    protected static function crearObjeto($registro) {
        $objeto = new static;
        foreach($registro as $key => $value) {
            if(property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }

    public static function find($id) {
        $consulta = "SELECT * FROM " . static::$tabla . " WHERE id = $id";
        $resultado = self::consultaSQL($consulta);
        return array_shift($resultado);
    }

    public function sincronizar($args = []) {
        foreach($args as $key => $value) {
            if(property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

    public function eliminar() {
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$infoBasedatos->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$infoBasedatos->query($query);
        return $resultado;
    }

    public static function mostrar($cantidad) {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad;
        $resultado = self::consultaSQL($query);
        return $resultado;
    }
}
