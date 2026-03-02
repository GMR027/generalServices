# Paso 1: Inicialización manual del proyecto MVC

Este documento explica cómo crear manualmente la estructura base de un proyecto MVC en PHP similar al ejemplo de *BienesRaicesMVC*. También describe qué hace cada carpeta/archivo y cómo comprobar que todo está correcto antes de continuar con el desarrollo.

---

## 1. Crear el directorio del proyecto

1. En tu terminal, navega a la ruta donde quieras crear el proyecto:
   ```bash
   cd ~/Documentos/programacion
   mkdir gestionproyectosMVC
   cd gestionproyectosMVC
   ```
2. Confirma con `ls` que el directorio se creó vacío.

> ✅ Verificación: la salida de `ls -la` debe mostrar al menos `.` y `..`.

## 2. Estructura de carpetas

Dentro de `gestionproyectosMVC` crea las siguientes carpetas:

```bash
mkdir controllers models views includes public src docs
```

- **controllers/**: controladores que reciben peticiones y manejan la lógica.
- **models/**: clases que representan datos y acceso a la base de datos.
- **views/**: plantillas HTML/PHP que generan la interfaz.
- **includes/**: archivos comunes (config, funciones, bootstrap).
- **public/**: sitio público, contiene `index.php`, CSS y JS compilado.
- **src/**: recursos fuente (SCSS, JS, imágenes) usados por gulp/sass.
- **docs/**: documentación paso a paso (donde estamos escribiendo).

> ✅ Verificación: `ls` debe listar las carpetas creadas.

Opcionalmente puedes añadir `.gitignore` para ignorar `vendor/`, `node_modules/`, etc.

## 3. Inicializar Composer

Si vas a usar autoloading de PSR-4, crea un `composer.json` básico:

```json
{
    "name": "gestionproyectos/mvc",
    "description": "Proyecto MVC de gestion de proyectos",
    "autoload": {
        "psr-4": {
            "MVC\\": ""
        }
    },
    "require": {}
}
```

Luego ejecuta:

```bash
composer install
```

> ✅ Verificación: debería generarse la carpeta `vendor/` y el autoload funcionando.

Puedes probar en PHP:

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use MVC\Router;

$router = new Router();
var_dump($router);
```

Si la clase `MVC\Router` (aún no existe) no lanza error de `class not found`, el autoloader está activo.

## 4. Configurar Sass/Gulp

Para compilar Sass necesitarás Node.js. Instala dependencias desde el directorio del proyecto:

```bash
npm init -y
npm install --save-dev gulp sass gulp-sass
```

Crea un archivo `gulpfile.js` con el contenido:

```js
import {src, dest, watch, series} from 'gulp'
import * as dartSass from 'sass'
import gulpSass from 'gulp-sass'

const sass = gulpSass(dartSass)

export function js(done) {
  src('src/js/app.js')
    .pipe( dest('build/js'))
  done()
}

export function css(done) {
  src('src/scss/app.scss', {sourcemaps: true})
    .pipe( sass().on('error', sass.logError) )
    .pipe( dest('build/css', {sourcemaps: '.'}) )
  done()
}

export function dev() {
  watch('src/scss/**/*.scss', css)
  watch('src/js/**/*.js', js)
}

export function build(done) {
  js(done)
  css(done)
  dev(done)
}

export default build;
```

> ✅ Verificación: crea los directorios `src/scss` y `src/js` y un archivo `app.scss` simple. Luego ejecuta `npx gulp css` y observa que se genere `build/css/app.css`.

## 5. Siguiente paso

Con la estructura y las herramientas listas, estás preparado para añadir el enrutador (`Router.php`), controladores y vistas. Continúa con el siguiente documento en `docs/02-router.md`.

---

**Resultado esperado:** el proyecto ya tiene la base de directorios, composer funciona y la compilación de Sass produce CSS. Si alguna verificación falla, revisa la configuración antes de avanzar.
