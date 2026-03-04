# Paso 17: Programa de Obra

Se añade un nuevo módulo para registrar actividades del programa de obra. Cada cliente solo puede ver y editar las actividades de los proyectos para los que tiene permiso.

## 17.1. Base de datos

La tabla `actividades_obra` ya debe existir en la base de datos (se creó previamente utilizando el SQL que sigue). Si aún no lo ha hecho, puede ejecutar:

```sql
CREATE TABLE IF NOT EXISTS actividades_obra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT NOT NULL,
    item INT NOT NULL,
    actividad TEXT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    dias INT NOT NULL,
    porcentajeAvance INT NOT NULL DEFAULT 0,
    comentarios TEXT NULL,
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE
);
```

`dias` se calcula automáticamente como la diferencia entre `fecha_fin` y `fecha_inicio` (inclusive).

## 17.2. Modelo y controlador

- `models/ActividadObra.php` extiende ActiveRecord y calcula/valida fechas, días y porcentaje.
- `controllers/ActividadObraController.php` incluye lógica de permisos similar a bitácora, y realiza el CRUD completo.

## 17.3. Rutas y vistas

Las rutas en `public/index.php` quedan bajo la URL `/p-obra`:

```php
use Controllers\ActividadObraController;
$router->get('/p-obra', [ActividadObraController::class, 'index']);
$router->get('/p-obra/crear', [ActividadObraController::class, 'crear']);
$router->post('/p-obra/crear', [ActividadObraController::class, 'crear']);
$router->get('/p-obra/editar', [ActividadObraController::class, 'editar']);
$router->post('/p-obra/editar', [ActividadObraController::class, 'editar']);
$router->post('/p-obra/eliminar', [ActividadObraController::class, 'eliminar']);
```

Vistas en `views/pobra/`:

- `index.php` lista actividades con filtro por proyecto y gráfico de barras en la última columna.
- `crear.php` y `editar.php` contienen el formulario con los campos especificados y cálculo automático de días mediante JavaScript.

## 17.4. Estilo

Se agregó una regla CSS para `.gantt-bar` y la tabla de detalle se hace más pequeña en exportaciones PDF.

Con esto el cliente puede gestionar gráficamente las actividades del programa de obra con una representación básica similar a un diagrama de Gantt.