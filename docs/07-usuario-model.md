# Paso 7: Modelo de usuario y métodos de autenticación

Con la tabla `usuarios` creada, necesitaremos un modelo que represente a cada fila y facilite operaciones comunes como verificar la existencia o autenticar contraseñas.

## 7.1. Archivo `models/Usuario.php`

```php
<?php

namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'correo', 'contrasena', 'rol', 'creado'];

    public $id;
    public $nombre;
    public $correo;
    public $contrasena;
    public $rol;
    public $creado;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->correo = $args['correo'] ?? '';
        $this->contrasena = $args['contrasena'] ?? '';
        $this->rol = $args['rol'] ?? 'cliente';
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
        return self::$errores;
    }

    // comprueba si ya existe usuario con ese correo
    public function existeUsuario() {
        $query = "SELECT * FROM " . self::$tabla . " WHERE correo = '" . self::$infoBasedatos->escape_string($this->correo) . "' LIMIT 1";
        $resultado = self::$infoBasedatos->query($query);
        return $resultado->fetch_assoc();
    }

    // verifica contraseña con hash
    public function verificarPassword($usuario) {
        $resultado = password_verify($this->contrasena, $usuario['contrasena']);
        if(!$resultado) {
            self::$errores[] = 'Contraseña incorrecta';
        }
        return $resultado;
    }

    public function hashPassword() {
        $this->contrasena = password_hash($this->contrasena, PASSWORD_DEFAULT);
    }

    public function autenticar() {
        session_start();
        $_SESSION['id'] = $this->id;
        $_SESSION['nombre'] = $this->nombre;
        $_SESSION['correo'] = $this->correo;
        $_SESSION['rol'] = $this->rol;
        $_SESSION['login'] = true;
        // redirigir según rol
        if($this->rol === 'admin') {
            header('Location: /admin');
        } else {
            header('Location: /proyectos');
        }
        exit;
    }
}
```

### Métodos clave

- **validarErrores**: asegura que nombre, correo y contraseña no estén vacíos y que el rol sea válido.
- **existeUsuario**: consulta la base de datos por el correo proporcionado.
- **verificarPassword**: compara la contraseña en texto plano con el hash almacenado. El método ahora aplica `trim()` al valor recuperado para eliminar espacios o saltos de línea accidentales, que pueden provocar fallos en la verificación.
- **hashPassword**: convierte la contraseña en un hash seguro antes de guardar.
- **autenticar**: inicializa la sesión con los datos del usuario y lo redirige según su rol.

## 7.2. Pruebas manuales

1. Crea un usuario de prueba utilizando `php -r`:

   ```bash
   php -r "require 'vendor/autoload.php'; require 'includes/app.php'; 
   use Model\\Usuario; 
   
   $u = new Usuario(['nombre'=>'Admin','correo'=>'admin@x.com','contrasena'=>'1234','rol'=>'admin']);
   $u->hashPassword(); 
   echo $u->contrasena;" 
   ```

   El comando imprimirá el hash; cópialo y genera un registro en la tabla `usuarios` con esa cadena.

2. O bien, puedes crear el registro desde PHP usando `$u->guardar()` después de `hashPassword()`.

3. Después, utiliza el método `existeUsuario()` para recuperar la fila y prueba `verificarPassword()`.

---

Cuando confirmes que el modelo funciona, el siguiente paso será **crear el controlador de autenticación y la vista de login**, documentado en el siguiente archivo (`docs/08-auth-controller.md`).
