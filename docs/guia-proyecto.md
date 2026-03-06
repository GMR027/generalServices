# Guía completa de desarrollo paso a paso

Este documento explica **desde cero** cómo crear un proyecto de gestión de proyectos en PHP
con un patrón MVC ligero. Incluye comandos, estructura de carpetas, dependencias,
y ejemplos de código. El objetivo es terminar con una aplicación equivalente
a la presente en este repositorio.

> **Aviso:** no tiene en cuenta los renombres temporales realizados durante la
> conversación; sigue el flujo natural de iniciar un proyecto limpio.

---

## 1. Preparación del entorno

Instala las herramientas siguientes:

```bash
# sistemas basados en Debian/Ubuntu (ejemplo)
sudo apt update && sudo apt install php php-cli php-mbstring php-zip php-pdo php-mysql \
    composer git nodejs npm mysql-client
```

- PHP 8.x con extensiones `pdo`, `pdo_mysql`, `mbstring`, `json`.
- Composer (gestor de paquetes PHP).
- Node.js y npm (para Gulp y compilación de assets).
- Servidor web (Apache o Nginx) configurado para apuntar a `public/`.
- MySQL / MariaDB para la base de datos.

Comprueba versiones con `php -v`, `composer -V`, `node -v`.

---

## 2. Crear la estructura de carpetas básica

```bash
mkdir gestionproyectosMVC
cd gestionproyectosMVC
# carpetas principales
mkdir controllers models views
mkdir includes config
mkdir public public/css public/js public/build
mkdir docs
```

Crea también el `composer.json` mínimo y el `package.json`:

```bash
composer init --require "intervention/image:^2.7" --no-interaction
npm init -y
```

Instala dependencias JS para Gulp (ver sección 4.4).

---

## 3. Configuración de Composer y autoload

Composer generará `vendor/` y un autoloader. En `includes/app.php`
pon la inicialización de sesiones y la carga automática:

```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

// iniciar sesión
if (!isset($_SESSION)) {
    session_start();
}

// configuración de base de datos
database();
```

Crea también `includes/config/database.php` con una función `database()`:

```php
<?php
function database() {
    $db = neworis\Database(
        'localhost', 'mi_base', 'usuario', 'contraseña'
    );
    \Model\ActiveRecord::setDB($db);
}
```

Ajusta según tus parámetros.

---

## 4. El enrutador (Router)

El enrutador es el corazón del flujo HTTP. Captura la URI solicitada, determina
si corresponde a una ruta GET o POST registrada y llama a la función o método
asociado.

**Meta:** al terminar este paso deberías ser capaz de acceder a diferentes
URLs (`/`, `/usuarios`, etc.) y ver contenido distinto sin tocar manualmente los
archivos HTML.

### Archivo raíz `Router.php`:

```php
<?php

class Router {
    // dos arrays: uno para GET y otro para POST
    public $rutasGET = [];
    public $rutasPOST = [];

    // registra una ruta para peticiones GET
    public function get($url, $fn) {
        $this->rutasGET[$url] = $fn;
    }
    // registra una ruta para peticiones POST
    public function post($url, $fn) {
        $this->rutasPOST[$url] = $fn;
    }

    // comprueba la ruta que se está solicitando
    public function comprobarRutas() {
        // parse_url extrae sólo el path, sin query string
        $urlActual = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
        // determina el verbo HTTP (GET/POST)
        $metodo = $_SERVER['REQUEST_METHOD'];
        if ($metodo === 'GET') {
            $fn = $this->rutasGET[$urlActual] ?? null;
        } else {
            $fn = $this->rutasPOST[$urlActual] ?? null;
        }
        if ($fn) {
            // llama a la función/método pasando el router como parámetro
            call_user_func($fn, $this);
        } else {
            // si no hay coincidencia mostramos un mensaje básico
            echo "Página no encontrada";
        }
    }

    // método de ayuda para renderizar vistas con datos
    public function render($view, $datos = []) {
        // extrae las variables del array $datos
        foreach ($datos as $key => $value) {
            $$key = $value;
        }

        // capturamos la salida de la vista en la variable $contenido
        ob_start();
        include __DIR__ . "/views/{$view}.php";
        $contenido = ob_get_clean();
        // la vista se incluye dentro de layout.php junto a $contenido
        include __DIR__ . "/views/layout.php";
    }
}
```

### ¿Qué hacer después de crear `Router.php`?
1. **Prueba rápida**: añade temporalmente una ruta anónima en un archivo de prueba
   y accede desde el navegador. Por ejemplo, en el propio Router agrega al final
   de `comprobarRutas()` antes de devolver 404:
   ```php
   if ($urlActual === '/test') {
       echo "ruta de prueba funcionando";
       exit;
   }
   ```
   luego visita `http://localhost:3000/test`.
2. Usa `var_dump($urlActual); die;` dentro de `comprobarRutas()` para ver qué valor
   obtiene en cada petición; el script se detendrá y mostrará la ruta.
3. Asegúrate de que el servidor web utiliza `public/` como `DocumentRoot`.

> **Script de diagnóstico**: crea un fichero `tests/router.php` con:
> ```php
> <?php
> require_once __DIR__ . '/../Router.php';
> $router = new Router();
> $router->get('/ping', function(){ echo 'pong'; });
> $_SERVER['REQUEST_URI'] = '/ping';
> $_SERVER['REQUEST_METHOD'] = 'GET';
> $router->comprobarRutas();
> ```
> y ejecútalo (`php tests/router.php`) para ver si imprime `pong`.

Así sabrás que el router intercepta correctamente la URI antes de llegar a las
vistas.

### Lista de verificación
- [ ] `Router.php` se carga sin errores (`php -l Router.php`).
- [ ] Al llamar a `/ruta-de-prueba` aparece el contenido esperado.
- [ ] Redirección a 404 básica cuando la ruta no está registrada.

---

---

## 5. Punto de entrada público

El fichero `public/index.php` es la primera parada de todas las peticiones web.
Desde aquí se cargan dependencias y se definen las rutas concretas que
el enrutador gestionará.

Crea `public/index.php` con el siguiente contenido:

```php
<?php
// carga automática de Composer y otros helpers
require_once __DIR__ . '/../includes/app.php';
// cargamos la clase Router (ubicada en la raíz del proyecto)
require_once __DIR__ . '/../Router.php';

$router = new Router();

// rutas de ejemplo para la entidad "usuarios".
// la sintaxis acepta closures o arrays [clase, metodo]
$router->get('/', function($router) {
    // renderiza la vista views/paginas/index.php
    $router->render('paginas/index');
});
$router->get('/usuarios', [\Controllers\UsuarioController::class, 'index']);
$router->get('/usuarios/crear', [\Controllers\UsuarioController::class, 'crear']);
$router->post('/usuarios/crear', [\Controllers\UsuarioController::class, 'crear']);

// finalmente, examinamos la URI actual y ejecutamos la función correspondiente
$router->comprobarRutas();
```

### Detalles importantes

- `__DIR__` se refiere al directorio actual (`public/`), por eso los `../`
  suben un nivel.
- Las rutas se configurarán de manera incremental: prueba primero solo `/` con
  un `echo 'hola';` para verificar que el archivo está siendo ejecutado.
- Después agrega otras rutas y accede desde el navegador para confirmar que
  aparecen (imprime mensajes temporales para ver qué callback se ejecuta).

### Cómo ver el progreso
1. Inicia el servidor de desarrollo: `php -S localhost:3000 -t public`.
2. Navega a `http://localhost:3000/` y deberías ver la página de inicio o el
   texto que colocaste en la ruta `/`.
3. Cada vez que añadas una nueva ruta vuelve a visitar la URL; si obtienes
   "Página no encontrada" revisa que la ruta está registrada antes de llamar a
   `comprobarRutas()`.
4. Usa `error_log('debug');` o `var_dump` dentro de las funciones para trazar
   la ejecución.
5. Confirma en el registro de PHP (`error_log`) cualquier excepción o fallo.

Al terminar esta sección tendrás una lista de rutas funcionales y tu servidor
responderá a ellas.

---

## 6. Helpers globales

Estos son pequeños funciones reutilizables que se cargan automáticamente desde
`app.php`. Su propósito es encapsular tareas comunes (redirecciones, mensajes de
flash, acceso al usuario actual) y evitar repetir código en los controladores.

`includes/funciones.php`:

```php
<?php

function redireccionar($url) {
    header('Location: ' . $url);
    exit;
}

function usuarioActual() {
    return $_SESSION['usuario'] ?? null;
}

function rolActual() {
    return $_SESSION['rol'] ?? null;
}

function setMensajeFlash($texto, $tipo = 'success') {
    $_SESSION['flash'] = ['texto' => $texto, 'tipo' => $tipo];
}

function obtenerMensajeFlash() {
    $msg = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $msg;
}
```

### Progreso y verificación

- Después de crearlos, `require` el archivo en `includes/app.php` y recarga el
  servidor; no deberían aparecer errores de sintaxis.
- En un controlador cualquiera, prueba `redireccionar('/');` para ver que funciona.
- Simula un mensaje flash en cualquier ruta y observa que aparece en el layout.
  Por ejemplo:
  ```php
  setMensajeFlash('Prueba');
  redireccionar('/');
  ```
- Comprueba que `usuarioActual()` devuelve `null` antes de hacer login y el valor
  correcto después de un inicio de sesión falso (puede ser simplemente
  `$_SESSION['usuario']='demo';`).

Estos helpers simplificarán el código a medida que añades más lógica.

---

## 6. Helpers globales

`includes/funciones.php`:

```php
<?php

function redireccionar($url) {
    header('Location: ' . $url);
    exit;
}

function usuarioActual() {
    return $_SESSION['usuario'] ?? null;
}

function rolActual() {
    return $_SESSION['rol'] ?? null;
}

function setMensajeFlash($texto, $tipo = 'success') {
    $_SESSION['flash'] = ['texto' => $texto, 'tipo' => $tipo];
}

function obtenerMensajeFlash() {
    $msg = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $msg;
}
```

Incluye este archivo en `app.php`.

---

## 7. ActiveRecord y modelos

Para evitar escribir consultas SQL repetidas, el proyecto utiliza un patrón
simple de Active Record. Cada modelo corresponde a una tabla de la base de
datos y hereda métodos comunes para leer y guardar registros.

### `models/ActiveRecord.php`:

```php
<?php
namespace Model;

class ActiveRecord {
    // conexión PDO compartida por todos los modelos
    protected static $db;
    // nombre de la tabla concreta (se especíﬁca en la clase hija)
    protected static $tabla = '';
    // columnas de la tabla que queremos mapear
    protected static $columnasDB = [];

    public static function setDB($database) {
        self::$db = $database;
    }

    // devuelve todos los registros de la tabla como objetos de la clase
    public static function all() {
        $query = "SELECT * FROM " . static::$tabla;
        $stmt = self::$db->query($query);
        // FETCH_CLASS rellena propiedades desde cada fila
        return $stmt->fetchAll(PDO::FETCH_CLASS, get_called_class());
    }

    // inserta o actualiza el registro según exista $this->id
    public function guardar() {
        $atributos = $this->atributos();
        if (isset($this->id)) {
            // UPDATE
            $sets = [];
            foreach ($atributos as $key => $value) {
                $sets[] = "{$key} = :{$key}";
            }
            $query = "UPDATE " . static::$tabla . " SET " . implode(', ', $sets) . " WHERE id = :id";
        } else {
            // INSERT
            $columnas = implode(', ', array_keys($atributos));
            $placeholders = ':' . implode(', :', array_keys($atributos));
            $query = "INSERT INTO " . static::$tabla . " ({$columnas}) VALUES ({$placeholders})";
        }
        $stmt = self::$db->prepare($query);
        $stmt->execute($atributos);
        if (!isset($this->id)) {
            $this->id = self::$db->lastInsertId();
        }
    }

    // transforma las propiedades públicas en array para PDO
    protected function atributos() {
        $atributos = [];
        foreach (static::$columnasDB as $col) {
            if ($col === 'id') continue;
            $atributos[$col] = $this->$col;
        }
        return $atributos;
    }
}
```

La idea es simple: cada modelo define su tabla y columnas; luego usa los
métodos `all()` y `guardar()` para manipular los datos sin escribir SQL manual.

### Modelo `Usuario` (ejemplo concreto):

```php
<?php
namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password'];

    public $id;
    public $nombre;
    public $email;
    public $password;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
    }
}
```

### Base de datos

Ejecuta la siguiente sentencia SQL en tu servidor MySQL para crear la tabla:

```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
);
```

### Progreso esperado

- [ ] Después de configurar la conexión y llamar a `Usuario::all()` desde un
  script de prueba, deberías obtener un array vacío de resultados (si la tabla
  está vacía). Crea `tests/model.php` con:
  ```php
  <?php
  require_once __DIR__ . '/../includes/app.php';
  $usuarios = \Model\Usuario::all();
  var_dump($usuarios);
  ```
  y ejecútalo (`php tests/model.php`) para ver la salida.
- Al crear un nuevo objeto y llamar a `guardar()`, un registro aparece en la
  tabla (`SELECT * FROM usuarios;`). Puedes ampliar el script anterior:
  ```php
  $u = new \Model\Usuario(['nombre'=>'Test','email'=>'t@e.com','password'=>'123']);
  $u->guardar();
  var_dump($u->id);
  ```
- La actualización funciona modificando `$usuario->nombre` y volviendo a
  llamar `guardar()`; imprime el resultado antes y después.

Inspecciona la base de datos con `mysql` o phpMyAdmin tras cada operación para
ver cómo cambia el contenido. Comprueba también valores retornados por
consultas.

---

## 8. Controladores

Los controladores contienen la lógica que responde a cada ruta; actúan como
"puente" entre modelos y vistas. Para cada recurso (usuarios, proyectos,
empresas, etc.) se crea una clase con métodos estáticos que reciben el router
como parámetro para poder invocar `render()`.

### Ejemplo `controllers/UsuarioController.php`:

```php
<?php
namespace Controllers;

use MVC\Router;      // alias de la clase Router definida en la raíz
use Model\Usuario;   // nuestro modelo ActiveRecord, espacio de nombres Model

class UsuarioController {
    // listado de usuarios
    public static function index(Router $router) {
        // recupera todos los registros de la tabla usuarios
        $usuarios = Usuario::all();
        // llama al renderizador pasando la vista y los datos a mostrar
        $router->render('usuarios/index', [
            'usuarios' => $usuarios
        ]);
    }

    // formulario de creación y guardado
    public static function crear(Router $router) {
        $usuario = new Usuario(); // instancia vacía para la vista
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // asignar valores del POST al objeto
            $usuario->nombre = $_POST['nombre'] ?? '';
            $usuario->email = $_POST['email'] ?? '';
            // cifrar contraseña antes de guardarla
            $usuario->password = password_hash(
                $_POST['password'], PASSWORD_BCRYPT
            );
            // persiste en la base de datos (INSERT o UPDATE según id)
            $usuario->guardar();
            // establecer un mensaje flash para la siguiente petición
            setMensajeFlash('Usuario creado');
            // redirige al listado, evitando reenvío de formularios
            redireccionar('/usuarios');
        }

        // si no es POST (GET inicial), renderiza el formulario en blanco
        $router->render('usuarios/crear', [
            'usuario' => $usuario
        ]);
    }
}
```

### Progreso paso a paso

1. Crea el controlador con la estructura anterior y comprueba que no hay errores
   de sintaxis (`php -l controllers/UsuarioController.php`).
2. Registra las rutas necesarias en `public/index.php` (ver sección 5). Si la
   ruta `/usuarios` no funciona, comprueba posibles errores en el namespace o
   en la importación (`use`).
3. En la ruta GET `/usuarios`, añade un `var_dump($usuarios); die();` dentro del
   método `index()` para verificar que se reciben los objetos del modelo. Luego
   quita el `die()` cuando valides.
4. En el método `crear`, haz un `die('POST recibido');` dentro del condicional
   para asegurarte de que la verificación `$_SERVER['REQUEST_METHOD']` es
   correcta.
5. Prueba en el navegador:
   - Accede a `/usuarios/crear` y llena el formulario.
   - Tras pulsar "Guardar" deberías ser redirigido a `/usuarios` y ver el
     mensaje flash (mira fuente HTML o usa `view-source:`).
   - Revisa la base de datos para confirmar que el nuevo usuario se ha insertado.
6. Para comprobar errores en formularios, imprime `print_r($_POST);`
   o `error_log(json_encode($_POST));` antes de procesar.
7. Repite el patrón para `actualizar` y `eliminar` cuando necesites esos
   comportamientos; basta con replicar la lógica POST/GET.

Los controladores serán los talleres donde desarrollarás casi todas las
funcionalidades del proyecto, así que esta sección merece especial atención.

---

## 9. Vistas

Las vistas son fragmentos de HTML con pequeñas porciones de PHP. No deben
contener lógica de negocio, sólo presentación y recurrir a variables
proporcionadas por el router (a través de `render()`). Se organizan en carpetas
bajo `views/` por entidad.

### Layout principal

`views/layout.php` define la estructura global de la página: cabecera,
navegación, zona de contenido y pie. Todo lo que se renderice mediante `render()`
se insertará en la variable `$contenido`.

```php
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Proyectos</title>
    <!-- Materialize & custom CSS -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/build/css/app.css">
</head>
<body>
<header>
    <!-- nav principal y secundario; cuidado con las clases active -->
</header>
<main>
    <div class="container">
        <?php if (function_exists('obtenerMensajeFlash')) {
            $flash = obtenerMensajeFlash();
            if ($flash) {
                $texto = $flash['texto'];
                $tipo = $flash['tipo'] ?? 'success';
                $color = $tipo === 'error' ? 'red lighten-4' : 'green lighten-4';
                echo "<div class=\"card-panel $color\">" . htmlspecialchars($texto) . "</div>";
            }
        }
        ?>
        <?php echo $contenido; ?>
    </div>
</main>
<footer>
    <!-- pie de página con copyright y enlaces -->
</footer>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src="/build/js/app.js"></script>
</body>
</html>
```

> **Nota**: si la navegación no resalta la ruta actual, revisa la función
> `estaActivo` en el layout (o la original `isActive`).

### Vistas específicas

Cada carpeta bajo `views/` contiene vistas para un recurso. En el caso de
usuarios:

```php
<!-- views/usuarios/index.php -->
<h1>Usuarios</h1>
<a href="/usuarios/crear" class="btn">Nuevo</a>
<table class="striped">
    <thead><tr><th>Nombre</th><th>Email</th></tr></thead>
    <tbody>
    <?php foreach($usuarios as $u): ?>
        <tr><td><?php echo $u->nombre; ?></td><td><?php echo $u->email; ?></td></tr>
    <?php endforeach; ?>
    </tbody>
</table>
```

```php
<!-- views/usuarios/crear.php -->
<h1>Crear usuario</h1>
<form method="POST">
    <div class="input-field">
        <input name="nombre" type="text" required>
        <label>Nombre</label>
    </div>
    <div class="input-field">
        <input name="email" type="email" required>
        <label>Email</label>
    </div>
    <div class="input-field">
        <input name="password" type="password" required>
        <label>Contraseña</label>
    </div>
    <button type="submit" class="btn">Guardar</button>
</form>
```

### Cómo avanzar y confirmar

- Tras crear el layout, accede cualquier vista (por ejemplo, `/`) y observa el
  código fuente en el navegador; debería incluir el contenido del layout más
  el de la vista específica.
- Añade un `echo "DEBUG";` al principio de cualquier vista para asegurarte de
  que se está incluyendo correctamente. Puedes combinar con `var_dump(
  $usuario)` u otras variables para ver datos.
- Para cada campo de formulario, comprueba que sus `name` coinciden con los
  índices usados en el controlador (`$_POST['nombre']`, etc.).
- Inspecciona la consola de JavaScript para mensajes de `console.log` que hayas
  puesto en `app.js`.
- Las rutas relativas a CSS/JS deben comenzar con `/build/` para evitar problemas
  de paths. Si el estilo no se carga, revisa `public/build/css/app.css` y el
  permiso de archivos.
- Cuando agregues nuevas vistas (por ejemplo `views/proyectos/editar.php`),
  visita su URL correspondiente para verificar que no hay errores PHP.
- Para verificar el contenido de una variable en la vista, usa:
  ```php
  <pre><?php var_dump($variable); ?></pre>
  ```
  que imprimirá información legible.

Este apartado es el más visible para el usuario; ve construyéndolo a medida
que tu lógica en controladores está lista.

---

## 10. Estilos y assets

El frontend usa Materialize a través de CDN y tus propios estilos/JS se
compilan mediante Gulp. La carpeta `src/` contiene tus fuentes (Sass/JS),
`public/build/` los artefactos compilados.

1. Instala las dependencias de desarrollo:
   ```bash
   npm install --save-dev gulp gulp-sass sass
   ```
2. Crea `gulpfile.js` con la siguiente tarea:

```js
const { src, dest, watch, series } = require('gulp');
const sass = require('gulp-sass')(require('sass'));

function css() {
  return src('src/scss/app.scss')
    // compilar Sass en CSS comprimido
    .pipe(sass({ outputStyle: 'compressed' }))
    // salida a carpeta pública
    .pipe(dest('public/build/css'));
}

exports.dev = function() { watch('src/scss/**/*.scss', css); };
```

3. Añade en `package.json` un script útil:

```json
"scripts": {
  "dev": "gulp dev"
}
```

4. Crea un archivo inicial `src/scss/app.scss` con algo de contenido:

```scss
body { font-family: "Roboto", sans-serif; }
```

5. Crea `src/js/app.js` y añade `console.log('app cargado');`.

### Verificación de progreso

- Ejecuta `npm run dev` y observa que `public/build/css/app.css` se crea y se
  actualiza al modificar el SCSS. Modifica `app.scss` con un color de fondo
  y recarga la página para ver el cambio.
- Abre el navegador en cualquier ruta y, usando las herramientas de desarrollador,
  comprueba que `app.css` y `app.js` se cargan sin 404. En la pestaña "Network"
  deberías ver un 200 OK.
- Comprueba en la consola del navegador el mensaje de `app.js` (`console.log`).
- Si el CSS no se aplica, revisa que el enlace `<link rel="stylesheet" href="/build/css/app.css">` está en el `<head>` del layout.
- Usa `gulp --version` para asegurarte de que el CLI está instalado.
- Para depurar problemas de compilación ejecuta `npm run dev` y mira si gulp
  muestra errores (Sass syntax, etc.).

Estos pasos garantizan que el proceso de build de assets funciona y que puedes
modificar estilos/JS en caliente mientras desarrollas.

---

## 11. Comprobación del progreso

A medida que avanzas, conviene tener una lista de verificación para cada módulo
que implementes.

1. **Modulariza**: desarrolla ingredientes individuales (usuarios, empresas,
   proyectos) sin mezclarlos. Completa un módulo antes de pasar al siguiente.
2. **Navegación**: visita cada ruta nueva en el navegador; el contenido debe
   ser el esperado. Si ves "Página no encontrada", revisa la definición de ruta.
3. **Crear/Guardar**: en formularios POST, comprueba que los datos llegan a
   `$_POST` usando `var_dump($_POST)` y que el método `guardar()` del modelo
   inserta un registro.
4. **Mensajes flash**: establece un mensaje (`setMensajeFlash`) y verifica que se
   muestra en el layout tras la redirección.
5. **Errores**: deja `display_errors = On` y usa `error_log()` para registrar
   valores. Consulta el archivo de log (`/var/log/apache2/error.log` o similar).
6. **Git**: haz commits frecuentes con etiquetas descriptivas como
   `feat: controlador usuarios` o `fix: bug en validar formulario`.
7. **Inspección DB**: abre una consola MySQL y ejecuta `SELECT * FROM <tabla>;`
   después de cada inserción/actualización para ver el efecto real.
8. **Copia de seguridad**: antes de cambios complejos, ejecuta
   `mysqldump dbname > backup.sql` y guarda el archivo con fecha.

> Consejo: crea un pequeño script `tests/diagnostico.php` con llamadas a
> `Usuario::all()` y `new Usuario(...)->guardar()`; ejecútalo con `php` para
> validar la lógica sin usar el navegador.

---

## 12. Añadir nuevas entidades o funcionalidades

Sigue este patrón cuando necesites ampliar el sistema:

1. Añade la tabla SQL correspondiente.
2. Crea un modelo en `models/` heredando de `ActiveRecord`.
3. Implementa un controlador en `controllers/` con métodos `index`, `crear`, `actualizar`, `eliminar`.
4. Añade rutas en `public/index.php`.
5. Crea vistas en `views/<entidad>/`.
6. Actualiza la navegación en `views/layout.php` (agrégalo bajo condicional según rol si es necesario).
7. Prueba en navegador y revisa DB.

Por ejemplo, para `Proyecto` crearías `Proyecto.php`, `ProyectoController.php`, y las carpetas de vistas `views/proyectos/`.

---

## 13. Roles y permisos

El proyecto actual distingue entre usuarios con rol `admin` y `cliente`. El flujo es:

- Tras login, almacenar `$_SESSION['rol']`.
- `layout.php` renderiza menús distintos según `rolActual()`.
- Protege rutas en el router o en controladores: antes de ejecutar, verifica el rol y redirige si no
  tiene permisos.

Ejemplo básico en un controlador:

```php
if (rolActual() !== 'admin') {
    redireccionar('/');
}
```

---

## 14. Despliegue a producción

1. Ajusta las variables de conexión en `config/database.php` o `.env`.
2. Ejecuta `composer install --no-dev` en el servidor.
3. Copia `public/` como raíz del host.
4. Compila los assets (`npm ci --production && npm run dev`).
5. Configura permisos (por ejemplo `storage`, `session`) si los usas.
6. Habilita `display_errors = Off` en producción.

---

## 15. Mantenimiento y crecimiento

- Añade tests simples guardando scripts en `tests/` que incluyan tus modelos y hacen asserts.
- Usa un control de versiones disciplinado.
- Documenta nuevas rutas en `docs/`.
- Refactoriza `Router` si necesitas parámetros dinámicos (por ejemplo, `/proyectos/:id`).
- Considera migraciones SQL si la base crece.

---

Con estas instrucciones tienes una hoja de ruta completa para replicar el proyecto actual
paso a paso, entendiendo qué hace cada archivo y cómo verificar tu trabajo. Ajusta el nivel
de detalle según tu ritmo de aprendizaje y no dudes en revisar los archivos existentes en
el repositorio como ejemplo real.
