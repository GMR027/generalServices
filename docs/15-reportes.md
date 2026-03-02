# 15 - Reportes

Se añadió la funcionalidad de registro y visualización de reportes por proyecto. Los requerimientos eran:

* Tabla `reportes` con los campos:
  * `id` auto incremental
  * `proyecto_id` (FK)
  * `actividad` TEXT
  * `area_zonal` VARCHAR(255)
  * `nivel` VARCHAR(255)
  * `permisoTrabajo` TEXT
  * `horastrabajadas` INT
  * `imagenes` JSON (lista de nombres de archivos)
  * `created_at` DATETIME (timestamp de creación, se rellena automáticamente con la hora local del servidor; en vistas se muestra como día/mes/año)

En el modelo `Reporte` se declara también la propiedad pública `permiso_tipo`, que no existe en la tabla, pero permite a las vistas saber si el cliente tiene permiso `editar` sobre cada fila cuando el controlador incluye un JOIN con la tabla de permisos.
* CRUD básico: lista, creación, edición, eliminación y visualización detallada. Los clientes pueden editar, borrar y ver en detalle sus propios reportes.
* El cliente sólo puede ver los reportes de los proyectos a los que tiene permiso. Además:
  * **Crear**: solo proyectos con permiso `editar` permiten generar nuevos reportes. Si un permiso está en `leer`, el botón de creación no aparece y el formulario tampoco mostrará ese proyecto.
  * **Editar / eliminar**: sólo si el permiso es `editar`; cuando el administrador cambia el permiso a `leer`, el proyecto seguirá apareciendo en el listado pero los botones de modificar/borrar desaparecen hasta que el permiso vuelva a ser `editar`.
* Tanto los admins como los clientes ven los botones de editar y eliminar; para los clientes estos sólo aparecen sobre reportes de proyectos a los que tienen permiso.
* Se añadió enlace "Registro" y "Ver reportes" en la barra para usuarios autenticados (eliminada la sección de "Nosotros"). El enlace de registro abre el formulario de reporte.
* En la tabla de "Mis proyectos" (vista de cliente) se coloca un botón "Crear registro" que redirige al formulario, preseleccionando el proyecto correspondiente.
* El panel de administración (`/admin`) ahora muestra una tabla con **todos los reportes de todos los clientes**. El administrador puede filtrar por uno o varios proyectos, editar o eliminar cualquier registro directamente desde ese listado.

* En el formulario de edición se modificó el texto que acompaña el campo de imágenes para dejar claro que las nuevas fotos **reemplazan** a las anteriores y que estas se eliminan del servidor.
* Se agregó botón "Ver detalle" en el listado de reportes, que abre una página con todos los campos del reporte y muestra las imágenes agrupadas en filas de tres. El campo `imagenes` siempre se guarda como un JSON válido (`[]` cuando no hay fotos) para evitar errores de tipo *Invalid JSON text*.

### Base de datos

Se modificó la tabla existente para eliminar columnas viejas (`imagenUbicacion`, `imagenesdeTrabajo`, `notascomentarios`) y agregar las columnas `imagenes` de tipo JSON y `created_at` para almacenar la fecha de creación. Ejecución:

```sql
ALTER TABLE reportes
    DROP COLUMN imagenUbicacion,
    DROP COLUMN imagenesdeTrabajo,
    DROP COLUMN notascomentarios,
    ADD COLUMN imagenes JSON DEFAULT NULL,
    ADD COLUMN created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
``` 

### Modelo

`models/Reporte.php` se actualizó para reflejar las columnas reales de la tabla y simplificar la estructura.

### Controlador

`controllers/ReporteController.php` maneja:

* `index` – lista los reportes; consulta SQL con `JOIN permisos` si es cliente. El enlace **Nuevo reporte** sólo aparece cuando el usuario tiene al menos un proyecto en modo `editar` (o si es admin).
* `crear` – formulario con validación, carga de múltiples imágenes y guarda JSON. Si se recibe `proyecto_id` en la query string, el selector de proyectos se preselecciona automáticamente. Los proyectos disponibles para el cliente provienen de su tabla de permisos **solo si el permiso es `editar`**; si no tiene ninguno se muestra un mensaje explicativo y no se despliega el formulario.
* `eliminar` – administradores pueden borrar cualquier reporte; los clientes pueden borrar los suyos (verificación por permiso).
* `editar` – permite modificar un reporte existente si se tiene acceso al proyecto.

Se aseguraron las sesiones en los métodos y redirecciones a `/login` si hace falta. El listado `/reportes` ahora admite parámetros `proyecto_id[]` para filtrar resultados.

### Vistas

* `views/reportes/index.php` muestra el nombre del proyecto en lugar de la ID, y añade enlaces para editar/borrar. Los botones se habilitan según los permisos del usuario.
* `views/reportes/editar.php` – formulario similar a 'crear' precargado con valores existentes.
* `views/reportes/crear.php` incluye despliegue de errores y rellena los valores previos tras un envío inválido.

### Rutas y protección

En `public/index.php` se añadieron las rutas:

```php
use Controllers\ReporteController;
$router->get('/reportes', [ReporteController::class, 'index']);
$router->get('/reportes/crear', [ReporteController::class, 'crear']);
$router->post('/reportes/crear', [ReporteController::class, 'crear']);
$router->get('/reportes/editar', [ReporteController::class, 'editar']);
$router->post('/reportes/editar', [ReporteController::class, 'editar']);
$router->get('/reportes/detalle', [ReporteController::class, 'detalle']);
$router->post('/reportes/eliminar', [ReporteController::class, 'eliminar']);
```

Asimismo, `Router.php` incluye `/reportes`, `/reportes/crear` y `/reportes/eliminar` en el array de rutas protegidas para requerir sesión y restringir acceso a administradores cuando corresponda.

### Resultados

Después de estos cambios, los clientes sólo ven sus proyectos en la lista y al crear un reporte. El administrador puede ver y eliminar cualquier reporte.

### UI y estilos

La interfaz se ha renovado utilizando **Materialize** (CDN) para conseguir un diseño moderno, accesible y completamente responsive. Se ha eliminado por completo el uso de Sass/SCSS; los archivos de estilo se borraron y ahora solo se utilizan las hojas de Materialize más un pequeño archivo CSS (`custom.css`) con las pocas correcciones necesarias (nav sin padding vertical, anchura de contenedor, etc.).

Los cambios más notables son:

* Navegación con `nav-wrapper` y menú lateral (`sidenav`) para móviles; los enlaces ya no se solapan y colapsan en el botón hamburguesa en pantallas pequeñas. Se ajustó el `line-height`, se añadió scrolling horizontal y se usa `flex` con `align-items:center` y `justify-content: space-between` para evitar que el logo se superponga con los enlaces.
* Contenedor principal limitado al **95 % de ancho** en escritorio (85 % a partir de 1200px), 95 % en tablet/móvil, centrado. Esto utiliza mucho más espacio horizontal disponible y evita márgenes vacíos en pantallas grandes.
* Tablas estilizadas con `striped highlight responsive-table` y botones de acción compactos con iconos (`edit`, `delete`, etc.).
* Formularios reformateados con `input-field`, selects mejorados y mensajes claros cuando no hay proyectos editables. Los botones de envío se han cambiado a componentes Materialize con iconos, incluso en la página de login y creación de usuarios. Los mensajes flash de creación/edición/eliminación ahora usan tarjetas (`card-panel`) de Materialize para ser más visibles y tipificables.
* Botones uniformes usando las clases `btn`, `btn-small` y colores Materialize en lugar de las viejas clases `.boton-*`. El botón de descarga de PDF en la vista de detalle se ajusta ahora al ancho disponible y emplea `white-space: nowrap` para evitar que el texto desborde.
* Material icons añadidos para mejorar la claridad de los controles.

La hoja de estilos personalizada se mantiene únicamente reglas auxiliares (ancho del contenedor, pequeñas correcciones de nav, textos pequeños) y se extiende opcionalmente `.btn` para compatibilidad. También se incluyen reglas para:

* Garantizar separación entre el logo y los enlaces en el `nav` y evitar superposiciones.
* Ajustar el tamaño del título de detalle para exportaciones y pantalla.
* Estilos de `alerta` por si se usa el markup heredado.

Esta actualización soluciona también el problema de los elementos de la barra de navegación que antes se traslapaban con el contenido y aprovecha mejor el espacio horizontal disponible.

El formulario permite subir varias imágenes; ahora se procesan con **Intervention Image** (es necesario ejecutar `composer require intervention/image`).

Se usa la clase **ImageManager** de la versión 3 del paquete (el antiguo `ImageManagerStatic` ya no existe), por lo que en los controladores se crea un gestor (`ImageManager::gd()`) antes de llamar a `read()` (o bien `new ImageManager(new Intervention\Image\Drivers\Gd\Driver())`). (o alternativamente `new ImageManager(new Intervention\Image\Drivers\Gd\Driver())`)

Los ficheros resultantes se guardan en `public/image`. (se recomienda crear la carpeta si no existe) La ruta se guarda como JSON en la base de datos. Cuando un reporte se actualiza con nuevas fotos o se elimina, los archivos antiguos se borran automáticamente.