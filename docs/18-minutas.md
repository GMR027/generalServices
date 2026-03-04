# Minutas

El nuevo módulo de minutas almacena los siguientes campos:

- `id` (auto increment)
- `fecha` (date)
- `tituloJunta` (text)
- `personalJunta` (text)
- `personalCliente` (text)
- `categoriaJunta` (text)
- `informacionJunta` (text)
- `compromisos` (text)

## Crear tabla

Ejecutar la siguiente sentencia SQL en la base de datos:

```sql
CREATE TABLE minutas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  proyecto_id INT NOT NULL,
  fecha DATE NOT NULL,
  tituloJunta TEXT,
  personalJunta TEXT,
  personalCliente TEXT,
  categoriaJunta TEXT,
  informacionJunta TEXT,
  compromisos TEXT,
  FOREIGN KEY (proyecto_id) REFERENCES proyectos(id)
);

-- si ya tienes la tabla, añade la columna:
-- ALTER TABLE minutas ADD COLUMN proyecto_id INT NOT NULL AFTER id;
-- y opcionalmente agrega llave foránea.
```

> ajuste los tipos y restricciones según sus necesidades.

## Uso

- Agrega la ruta `/minutas` al menú secundario (ya implementado en `views/layout.php`).
- Accede como administrador (o cliente autenticado) para listar, crear y también editar/minutar registros. En el listado se puede **filtrar por proyecto**; la columna "Proyecto" muestra la selección y en la vista de detalle el nombre del proyecto aparece sobre la fecha. Los enlaces de CRUD están disponibles en la barra de navegación; edición y eliminación requieren permisos sobre el proyecto (admins o clientes con acceso).

Se han creado:

- Modelo `Model\Minuta`
- Controlador `Controllers\MinutaController` (ahora incluye método `detalle` para mostrar la minuta completa; cualquier usuario autenticado puede ver listados, detalles y crear nuevas minutas; sólo los administradores pueden editar o eliminar)
- Vistas `views/minutas/` (`index.php`, `crear.php`, `actualizar.php`, `detalle.php`)
  - `index.php` muestra sólo un extracto de la **información** y los **compromisos**; use el botón "Ver detalle" para abrir la página de oficio.
  - `detalle.php` presenta todos los campos en un formato de minuta/oficio.
- Rutas en `public/index.php` (añadida `/minutas/detalle` además de las CRUD habituales; la ruta de creación está abierta a clientes autenticados)

---

## Ajustes generales

Las tablas de listado para **minutas**, **reportes/registros** y **bitácora** ahora muestran sólo un fragmento de los campos de texto largos (por ejemplo comentarios, información o compromisos). Cada fila incluye un botón "Ver detalle" que abre una página dedicada donde se muestra el registro completo con formato de oficio o cuadro informativo.

- Reportes ya contaba con `/reportes/detalle`.
- Bitácora se extendió con `/bitacora/detalle` y su vista asociada.

Esta mejora mantiene los índices limpios y permite visualizar todo el contenido sólo bajo demanda. Además, el mismo patrón de truncado se ha aplicado en el panel de administración de reportes y en la vista de listado de bitácora.
