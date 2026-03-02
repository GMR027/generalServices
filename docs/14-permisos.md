# Paso 14: Asignación de permisos y vista de cliente

Los permisos permiten al administrador asignar a un cliente acceso de lectura o edición sobre uno o varios proyectos, dentro de una empresa específica.

## 14.1. Tabla SQL

```sql
CREATE TABLE permisos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    proyecto_id INT NOT NULL,
    empresa_id INT NOT NULL,
    permiso ENUM('leer','editar') NOT NULL DEFAULT 'leer',
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id),
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id),
    FOREIGN KEY (empresa_id) REFERENCES empresas(id)
);
```

Esta tabla se ha añadido al esquema y la migración se ejecutó automáticamente en pasos anteriores.

## 14.2. CRUD de permisos

- **Modelo**: `models/Permiso.php` hereda de `ActiveRecord` y valida que se haya escogido cliente, proyecto, empresa y permiso válido.
- **Controlador**: `controllers/PermisoController.php` ofrece:
  * `index()` – lista todos los permisos (solo admin).
  * `crear()` – formulario que permite seleccionar un cliente, una empresa y el tipo de permiso. **La lista de proyectos se presenta ahora como casillas de verificación en lugar de un multiselect**, de modo que el administrador puede marcar fácilmente varios proyectos a la vez. Se crean tantas filas como proyectos seleccionados.
  * `actualizar()` – permite modificar un permiso existente. El formulario acepta ahora seleccionar varios proyectos (si se eligen más de uno, el permiso original se elimina y se generan nuevos registros), lo que facilita asignar varios proyectos a un mismo cliente en la misma operación.
  * `eliminar()` – borra un permiso individual.
  * `misProyectos()` – vista especial para clientes.

- **Vistas** en `views/permisos/`:
  * `index.php` – tabla de permisos.
  * `crear.php` – formulario con selects y multiselect.
  * `actualizar.php` – formulario para modificar un permiso ya existente.
  * `misProyectos.php` – listado para clientes con los proyectos que tienen asignados y el permiso correspondiente.

- **Rutas registradas**:
  ```php
  $router->get('/permisos', [PermisoController::class, 'index']);
  $router->get('/permisos/crear', [PermisoController::class, 'crear']);
  $router->post('/permisos/crear', [PermisoController::class, 'crear']);
  $router->post('/permisos/eliminar', [PermisoController::class, 'eliminar']);
  $router->get('/mis-proyectos', [PermisoController::class, 'misProyectos']);
  ```

  El enlace **Permisos** en la barra de administración dirige a `/permisos`.

## 14.3. Vista de cliente

Cuando un usuario con rol `cliente` inicia sesión aparece un enlace **Mis proyectos**. Al hacer clic se muestra:

- El nombre y el permiso (leer/editar) de cada proyecto asignado.
- Un nuevo botón **Crear registro** en cada fila que redirige al formulario de reportes preseleccionando el proyecto. *El botón solo aparece cuando el permiso es `editar`; para los proyectos en `leer` la columna muestra un texto indicativo y no se permite crear.*
- La tabla utiliza la misma hoja de estilos y formato que el resto del sistema.

  > 🔄 *Nota:* al cambiar un permiso de `editar` a `leer`, el proyecto sigue apareciendo en la lista y el cliente puede crear reportes; sin embargo los botones de editar/borrar desaparecen hasta que se vuelva a asignar `editar`.

## 14.4. Comprobaciones de sesión

- Solo administradores ven y utilizan el formulario de asignación.
- El método `misProyectos()` garantiza que solo usuarios autenticados pueden acceder a sus propias asignaciones.

## 14.5. Estilo y ajustes

Los formularios y tablas utilizan los estilos definidos en los parciales SCSS (`_formularios.scss`, `_botones.scss`, etc.). Recuerda recompilar con `npx gulp css` tras cualquier cambio en los archivos SCSS.

---

Este paso completa la infraestructura de permisos y permite a los clientes visualizar sus asignaciones. Si necesitas restricciones adicionales (por ejemplo, impedir que clientes editen proyectos aunque tengan permiso de edición), se puede implementar con middleware adicional en los controladores de proyectos.