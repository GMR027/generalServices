# Paso 5: Usar Sass y Gulp para los estilos y JavaScript

Ya tenemos la estructura básica de PHP; ahora configuraremos la parte front‑end para compilar Sass y copiar JavaScript mediante Gulp. El objetivo es mantener el CSS y JS compilados en `public/build`.

## 5.1. Manualmente instalar herramientas

1. Asegúrate de tener Node.js y npm instalados (`node -v`, `npm -v`).
2. Desde la raíz del proyecto ejecuta:
   ```bash
   npm init -y             # genera package.json si no existe
   npm install --save-dev gulp sass gulp-sass
   ```
3. Verifica que `node_modules/` y `package-lock.json` aparezcan.

> ✅ Comprobar: `npx gulp --version` debe mostrar la versión instalada de Gulp.

## 5.2. Estructura de SCSS/JS

Creamos los directorios fuente que Gulp observará:

```bash
mkdir -p src/scss src/js
```

Dentro de `src/scss` coloca tu archivo principal `app.scss` (puede ser vacío o contener estilos iniciales):

```scss
// app.scss
body {
  font-family: Arial, sans-serif;
}
```

En `src/js/app.js` puedes añadir un `console.log` provisional:

```js
// app.js
console.log('JS cargado');
```

Estos archivos ya se han creado en los pasos anteriores, pero esta sección explica el proceso manual.

## 5.3. Configuración de `gulpfile.js`

El fichero que hemos añadido contiene tareas para:

- Compilar SCSS con mapas de origen (`sourcemaps`) a `build/css`.
- Copiar `src/js/app.js` a `build/js`.
- Observar cambios y volver a compilar automáticamente (`watch`).

Contenido completo:

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

### ¿Qué hace cada parte?

- `import` y configuración inicial de `gulp-sass` con el compilador Dart Sass.
- `js()` lee el archivo JS y lo deposita en `build/js`.
- `css()` toma el `app.scss`, lo procesa y genera `app.css` con un mapa de origen en `build/css`.
- `dev()` vigila cambios y vuelve a ejecutar las tareas.
- `build()` ejecuta `js`, `css` y luego `dev` (similar a un modo de desarrollo).
- La línea `export default build;` define la tarea predeterminada que se ejecuta cuando solo se llama `gulp`.

## 5.4. Enlazar los recursos en el layout

En `views/layout.php` hemos añadido estas líneas dentro de `<head>` y antes de `</body>`:

```html
<link rel="stylesheet" href="/build/css/app.css">
<script src="/build/js/app.js"></script>
```

Asegúrate de que las rutas coincidan con la salida de Gulp (las rutas son relativas a `public/`).

## 5.5. Verificación

1. Genera los archivos por primera vez:
   ```bash
   npx gulp css    # solo estilos
   npx gulp js     # solo js
   # o bien:
   npx gulp        # compila y arranca el watch
   ```
2. Observa que `build/css/app.css` y `build/js/app.js` existen.
3. En el navegador recarga una de las páginas; abre la consola y comprueba el mensaje `JS cargado`.
4. Cambia `src/scss/app.scss` y guarda; Gulp debería recompilar automáticamente si tienes `npx gulp` corriendo.

Si ves los estilos aplicados y el log de JavaScript, la integración de Sass/Gulp está funcionando correctamente.

---

El siguiente documento explicará cómo crear el modelo `Proyecto` y la conexión a la base de datos (ActiveRecord). Continúa con `docs/06-modelos.md` cuando estés listo.
