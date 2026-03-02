# Paso 3: Bootstrap y archivo `public/index.php`

En este paso montamos la "entrada" de la aplicación, que es el único fichero accesible desde el navegador. También añadimos funciones comunes y configuramos la conexión a la base de datos.

## 3.1. Crear `public/index.php`

La carpeta `public/` es el *document root* que usará el servidor web. Dentro colocamos un `index.php` muy sencillo que:

1. Carga el autoloader de Composer y las funciones de arranque (`includes/app.php`).
2. Instancia el `Router`.
3. Registra rutas (aún solo las públicas).
4. Llama a `comprobarRutas()` del router.

Aquí está el contenido completo:

```php
<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\PaginasController;

$router = new Router();

// Rutas públicas de ejemplo
$router->get('/', [PaginasController::class, 'index']);
$router->get('/nosotros', [PaginasController::class, 'nosotros']);

// Ejecutar el enrutador
$router->comprobarRutas();
```

### ¿Qué comprobar?

- El script no debe arrojar errores de `class not found` o `require`.
- Si inicias el servidor embebido de PHP desde `public/`:
  ```bash
  cd public
  php -S localhost:3000
  ```
  y visitas `http://localhost:3000/`, el archivo debe ejecutarse y el router iniciarse (mostrará "Página no encontrada" si no hay controlador).

## 3.2. Funciones y configuración (`includes/`)

Creamos una carpeta `includes/` con un par de utilidades básicas y la lógica de conexión:

- `funciones.php` con helper como `debuguear()`, `limpiar()` y `validarId()`.
- `config/database.php` con la función `conectarBD()` que devuelve un objeto `mysqli`.
- `app.php` carga estos archivos, ajusta `error_reporting` y pasa la conexión a `ActiveRecord`.

Contenido de `includes/app.php`:

```php
<?php
// Mostrar errores en desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/funciones.php';
require __DIR__ . '/config/database.php';

use Model\ActiveRecord;

$conexion = conectarBD();
ActiveRecord::confDatabase($conexion);
```

## 3.3. Verificación

1. Asegúrate de tener MySQL/MariaDB corriendo y una base de datos llamada `gestionproyectos` (puedes crearla con `CREATE DATABASE gestionproyectos;`).
2. Abre `public/index.php` en el navegador como se indicó anteriormente. Si obtienes la página de error del router o un `blank page` sin errores, el bootstrap está funcionando.
3. Puedes forzar una conexión fallida cambiando la contraseña en `database.php`; deberías ver el mensaje "Error en la conexión...".

Una vez el arranque base funciona sin errores, pasaremos a crear el primer controlador y la vista de inicio.
