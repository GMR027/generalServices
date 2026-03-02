# Paso 8: Controlador de autenticación y vista de login

Para manejar el inicio de sesión necesitamos un controlador dedicado que valide los datos, consulte al modelo `Usuario` y, si el login es correcto, cree la sesión. También habrá un método para cerrar sesión.

## 8.1. Archivo `controllers/AuthController.php`

```php
<?php

namespace Controllers;

use MVC\Router;
use Model\Usuario;

class AuthController {
    public static function login(Router $router) {
        $errores = [];
        $auth = new Usuario();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST['login']);
            $errores = $auth->validarErrores();

            if(empty($errores)) {
                $usuarioExiste = $auth->existeUsuario();
                if(!$usuarioExiste) {
                    $errores[] = 'Usuario no existe';
                } else {
                    // verificar contraseña
                    $verificado = $auth->verificarPassword($usuarioExiste);
                    if($verificado) {
                        // llenar propiedades necesarias para sesión
                        $auth->id = $usuarioExiste['id'];
                        $auth->nombre = $usuarioExiste['nombre'];
                        $auth->correo = $usuarioExiste['correo'];
                        $auth->rol = $usuarioExiste['rol'];
                        $auth->autenticar();
                    }
                }
            }
        }

        $router->render('/views/auth/login.php', [
            'errores' => $errores
        ]);
    }

    public static function logout() {
        session_start();
        $_SESSION = [];
        header('Location: /');
    }
}
```

### Flujo del método `login`
1. Se crea un objeto `Usuario` vacío para no tener errores al imprimir la vista.
2. Si la petición es `POST`, se pasa el arreglo `$_POST['login']` al constructor.
3. Se validan campos obligatorios mediante `validarErrores()`.
4. Si no hay errores, se llama a `existeUsuario()` para recuperar el registro de la base.
5. Si el usuario existe se invoca `verificarPassword()`; si la contraseña coincide:
   * Se copian las propiedades necesarias al objeto `$auth`.
   * Se llama a `$auth->autenticar()` para inicializar sesión y redirigir según rol.
6. La vista se renderiza siempre con el arreglo de errores, incluso vacío.

El método `logout()` limpia la sesión y regresa al inicio.

## 8.2. Vista `views/auth/login.php`

```html
<h2>Iniciar sesión</h2>

<?php foreach($errores as $error): ?>
    <div class="alerta error">
        <?php echo $error; ?>
    </div>
<?php endforeach; ?>

<form method="POST" class="formulario">
    <fieldset>
        <legend>Datos de acceso</legend>

        <label for="correo">Correo:</label>
        <input type="email" name="login[correo]" id="correo" placeholder="Tu correo" required>

        <label for="contrasena">Contraseña:</label>
        <input type="password" name="login[contrasena]" id="contrasena" placeholder="Tu contraseña" required>
    </fieldset>

    <input type="submit" class="boton-verde" value="Entrar">
</form>
```

Los errores de validación se muestran en un bloque repetido.

## 8.3. Pruebas
1. Añade las rutas al `public/index.php`:
   ```php
   $router->get('/login', [AuthController::class, 'login']);
   $router->post('/login', [AuthController::class, 'login']);
   $router->get('/logout', [AuthController::class, 'logout']);
   ```
2. Inicia el servidor (`php -S localhost:3000 -t public`).
3. Crea al menos un usuario administrado directamente en la base o mediante script (usa `password_hash` para la contraseña).
4. Accede a `http://localhost:3000/login` y prueba iniciar sesión con credenciales correctas y erróneas.
5. Comprueba que en una sesión válida `$_SESSION['rol']` contenga `admin` o `cliente` y que la redirección sea adecuada.

> ✅ Si puedes iniciar y cerrar sesión sin errores, el controlador de autenticación está listo.

En el siguiente paso documentaremos cómo proteger rutas y manejar permisos dependientes del rol (cliente vs administrador).