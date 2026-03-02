# Paso 4: Controladores y vistas de páginas públicas

Para empezar a responder a rutas reales necesitaremos controladores que llamen a vistas y las vistas mismas. Este paso muestra cómo crear `PaginasController` y dos plantillas básicas: inicio y nosotros.

## 4.1. Controlador `PaginasController` en `controllers/`

Crea el archivo con este contenido:

```php
<?php

namespace Controllers;

use MVC\Router;

class PaginasController {
    public static function index(Router $router) {
        $router->render('/views/paginas/index.php');
    }

    public static function nosotros(Router $router) {
        $router->render('/views/paginas/nosotros.php');
    }
}
```

### Qué hace el controlador

- Tiene métodos `static` que reciben el objeto `Router`.
- Cada método invoca `$router->render()` pasando la ruta relativa de la vista.
- La página principal (`index`) también funciona como formulario de login si no hay usuario autenticado. En ese caso delega en `AuthController::login()`.
- En un uso real podrías enviar datos al segundo parámetro de `render()`.

> ✅ Verificación manual: después de añadirlo, visita `/` y `/nosotros` en el navegador. Debes ver el contenido de las vistas (si aún no has reiniciado el servidor, recárgalo).

## 4.2. Layout y vistas

El `Router` coloca el contenido de cada vista dentro de un layout común. Crea el archivo `views/layout.php`:

```html
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Proyectos</title>
    <link rel="stylesheet" href="/build/css/app.css">
</head>
<body>
    <header>
        <h1>Mi aplicación MVC</h1>
        <nav>
            <a href="/">Inicio</a> |
            <a href="/nosotros">Nosotros</a>
        </nav>
    </header>
    <main>
        <?php echo $contenido; ?>
    </main>
    <footer>
        <p>© 2026 Gestión de Proyectos</p>
    </footer>
    <script src="/build/js/app.js"></script>
</body>
</html>
```

También añade las vistas específicas:

- `views/paginas/index.php`:
  ```html
  <h2>Bienvenido a la aplicación de gestión de proyectos</h2>
  <p>Esta es la página de inicio.</p>
  ```
- `views/paginas/nosotros.php`:
  ```html
  <h2>Nosotros</h2>
  <p>Aquí va la información sobre el equipo o la empresa.</p>
  ```

### Verificar

1. Inicia el servidor (si no lo hiciste antes):
   ```bash
   cd public
   php -S localhost:3000
   ```
2. Abre `http://localhost:3000/` y debes ver el título "Bienvenido..." dentro del layout con el encabezado y pie de página.
3. Navega a `http://localhost:3000/nosotros` para comprobar la segunda vista.

Si ambas páginas cargan correctamente, el controlador y las vistas están funcionando.

## 4.3. Paso siguiente

El siguiente documento (`docs/05-sass-gulp.md`) explicará cómo compilar SCSS/JS y cómo integrar los archivos generados en el layout. Continua cuando hayas verificado las páginas públicas.
