# Paso 16: Categorías y Bitácora

Este paso añade dos nuevas entidades al sistema:

- **categorías**: una lista sencilla de nombres para clasificar entradas de bitácora.
- **bitácora**: registros libres asociados a proyectos que los clientes pueden crear.

## 16.1. Base de datos

Creamos dos tablas nuevas.

```sql
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS bitacoras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    proyecto_id INT NOT NULL,
    categoria_id INT NOT NULL,
    comentarios TEXT NOT NULL,
    usuario VARCHAR(255) NOT NULL,
    nivelImportancia INT NOT NULL DEFAULT 0,
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
);
```

Asegúrate de ejecutar estas sentencias (por ejemplo con phpMyAdmin o la consola `mysql`) antes de probar la aplicación.

## 16.2. Modelo y controlador

- `models/Categoria.php` y `controllers/CategoriaController.php` gestionan el CRUD de categorías.
- `models/Bitacora.php` y `controllers/BitacoraController.php` implementan la lógica de bitácora; los clientes ven únicamente las entradas de los proyectos sobre los que tienen permiso.

## 16.3. Rutas y navegación

Se añaden nuevas rutas en `public/index.php` para los CRUDs de categorías y bitácora.
Los clientes ven un enlace **Bitacora** en la barra de navegación principal; los administradores pueden utilizar las rutas para gestionar contenido y categorías.

## 16.4. Vistas

Se han creado las siguientes plantillas:

- `views/categorias/index.php`, `crear.php`, `actualizar.php`
- `views/bitacora/index.php`, `crear.php`, `editar.php`

El formulario de bitácora permite seleccionar proyecto y categoría, añadir comentarios, indicar usuario y nivel de importancia.

## 16.5. Estilo y scripts

Los select de Materialize ya estaban inicializados en `public/build/js/app.js`, de modo que los nuevos campos funcionan sin cambios adicionales.

Con esto la funcionalidad de bitácora queda integrada tanto para clientes como para administradores; las categorías ayudan a clasificar entradas.  