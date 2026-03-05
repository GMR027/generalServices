<?php

namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'correo', 'contrasena', 'rol', 'estatus', 'creado'];

    public $id;
    public $nombre;
    public $correo;
    public $contrasena;
    public $rol;
    public $estatus;
    public $creado;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->correo = $args['correo'] ?? '';
        $this->contrasena = $args['contrasena'] ?? '';
        $this->rol = $args['rol'] ?? 'cliente';
        $this->estatus = $args['estatus'] ?? 'activo';
        $this->creado = date('Y-m-d H:i:s');
    }

    public function validarErrores() {
        if(!$this->nombre) {
            self::$errores[] = 'El nombre es obligatorio';
        }
        if(!$this->correo) {
            self::$errores[] = 'El correo es obligatorio';
        }
        if(!$this->contrasena) {
            self::$errores[] = 'La contraseña es obligatoria';
        }
        if(!in_array($this->rol, ['admin','cliente'])) {
            self::$errores[] = 'Rol no válido';
        }
        if(!in_array($this->estatus, ['activo','inactivo'])) {
            self::$errores[] = 'Estatus no válido';
        }
        return self::$errores;
    }

    // Validation used during login (only email + password required)
    public function validarLogin() {
        self::$errores = [];
        if(!$this->correo) {
            self::$errores[] = 'El correo es obligatorio';
        }
        if(!$this->contrasena) {
            self::$errores[] = 'La contraseña es obligatoria';
        }
        return self::$errores;
    }

    // comprueba si el correo existe en la base de datos
    public function existeUsuario() {
        $query = "SELECT * FROM " . self::$tabla . " WHERE correo = '" . self::$infoBasedatos->escape_string($this->correo) . "' LIMIT 1";
        $resultado = self::$infoBasedatos->query($query);
        return $resultado->fetch_assoc();
    }

    // verifica que la contraseña coincide
    public function verificarPassword($usuario) {
        // some hashes may contain stray whitespace or newlines
        $hash = trim($usuario['contrasena']);
        $resultado = password_verify($this->contrasena, $hash);
        if(!$resultado) {
            self::$errores[] = 'Contraseña incorrecta';
        }
        return $resultado;
    }

    public function hashPassword() {
        $this->contrasena = password_hash($this->contrasena, PASSWORD_DEFAULT);
    }

    public function autenticar() {
        // ensure session started without duplicate warnings
        if(function_exists('asegurarSesion')) {
            asegurarSesion();
        } else {
            if(session_status() !== PHP_SESSION_ACTIVE) session_start();
        }

        $_SESSION['id'] = $this->id;
        $_SESSION['nombre'] = $this->nombre;
        $_SESSION['correo'] = $this->correo;
        $_SESSION['rol'] = $this->rol;
        $_SESSION['login'] = true;

        // mensaje de bienvenida
        if(function_exists('establecerMensajeFlash')) {
            establecerMensajeFlash('Bienvenido ' . $this->nombre . ' (' . $this->rol . ')');
        } else {
            $_SESSION['mensaje'] = 'Bienvenido ' . $this->nombre . ' (' . $this->rol . ')';
        }

        // redirigir según rol
        if($this->rol === 'admin') {
            header('Location: /admin');
        } else {
            header('Location: /proyectos');
        }
        exit;
    }
}
