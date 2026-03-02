# Paso 2: Enrutador básico (`Router.php`)

El enrutador es la columna vertebral de este pequeño framework MVC. Se encarga de:

- registrar rutas para peticiones `GET` y `POST`
- verificar la URL actual y el método HTTP
- proteger rutas que requieren autenticación
- invocar el controlador/método correspondiente
- renderizar vistas con datos

A continuación te explico cómo crear uno manualmente y cómo comprobar que funciona.

## 2.1. Archivo `Router.php`

Crea el fichero en la raíz del proyecto (junto con `composer.json`):

```php
<?php

namespace MVC;

class Router {

  public $rutasGet = [];
  public $rutasPost = [];

  public function get($url, $funcion) {
    $this->rutasGet[$url] = $funcion;
  }

  public function post($url, $funcion) {
    $this->rutasPost[$url] = $funcion;
  }

  public function comprobarRutas() {
    session_start();
    $ingreso = $_SESSION['login'] ?? null;

    // rutas protegidas (se pueden ajustar según las necesites)
    $rutasProtegidas = [
      '/admin',
      '/propiedades/crear',
      '/propiedades/actualizar',
      '/propiedades/eliminar',
      '/vendedores/crear',
      '/vendedores/actualizar',
      '/vendedores/eliminar',
    ];

    $urlActual = $_SERVER['PATH_INFO'] ?? $_SERVER['ORIG_PATH_INFO'] ?? $_SERVER['REQUEST_URI'] ?? '/';
    // descartar parámetros de consulta para que '/?foo=bar' se considere '/'
    $urlActual = parse_url($urlActual, PHP_URL_PATH) ?: '/';
    $metodo = $_SERVER['REQUEST_METHOD'];

    if($metodo === 'GET') {
      $funcion = $this->rutasGet[$urlActual] ?? null;
    } else if ($metodo === 'POST') {
      $funcion = $this->rutasPost[$urlActual] ?? null;
    }

    if(in_array($urlActual, $rutasProtegidas) && !$ingreso) {
      header('location: /');
    }

    if($funcion) {
      call_user_func($funcion, $this);
    } else {
      echo "Pagina no encontrada";
    }
  }

  public function render($view, $datos = []) {
    foreach($datos as $key => $value) {
      $$key = $value; // convierte claves del array a variables
    }

    ob_start();      // inicia buffering
    include_once __DIR__ . $view; // incluye la vista solicitada
    $contenido = ob_get_clean();
    include_once __DIR__ . '/views/layout.php';
  }
}
```

### ¿Qué hace cada parte?

1. **Propiedades `$rutasGet` y `$rutasPost`**: almacenan asociaciones URL → función.
2. **métodos `get()`/`post()`**: añaden rutas al arreglo correspondiente.
3. **`comprobarRutas()`**:
   - Inicia la sesión para saber si el usuario está autenticado.
   - Obtiene la URL actual usando varias variables superglobales (para compatibilidad con distintos servidores).
   - Elige la función que corresponde según el método HTTP.
   - Si la ruta está en la lista de protegidas y no hay login, redirige a `/`.
   - Si hay función, se llama usando `call_user_func` pasándole el router mismo; si no, muestra mensaje de error.
4. **`render()`**: crea variables a partir de `$datos`, guarda la vista en el buffer, carga un layout donde se inyecta el contenido.

## 2.2. Comprobar que el enrutador funciona

1. Asegúrate de que Composer esté cargando el namespace `MVC`:
   ```php
   <?php
   require __DIR__ . '/vendor/autoload.php';
   use MVC\Router;
   $router = new Router();
   var_dump($router);
   ```
   Ejecuta este script (por ejemplo `php public/index.php`) y verifica que la clase se instancia sin errores.

2. Registra una ruta de prueba en un archivo que se ejecutará en `public/index.php`:
   ```php
   $router = new Router();
   $router->get('/prueba', function() {
       echo 'ruta GET correcta';
   });
   $router->comprobarRutas();
   ```
   Luego apunta el navegador a `http://localhost:3000/prueba` y deberías ver "ruta GET correcta".

3. Comprueba que rutas protegidas redirigen:
   - Deja la sesión limpia (`session_destroy()` si es necesario).
   - Registra `/admin` en `$rutasProtegidas` (ya está) y accede a esa URL; el router debería devolver al inicio.

Una vez estas pruebas pasen, el enrutador está listo y puedes continuar con la creación de controladores y vistas.

---

En el próximo documento crearemos la primera vista y el controlador correspondiente.
