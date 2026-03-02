# Paso 12: CRUD para empresas

El administrador puede dar de alta empresas con los siguientes campos:

- nombre
- nombreCliente
- email
- telefono
- logo (URL o ruta de imagen)
- rfc
- direccion

## 12.1. Base de datos

Ya creamos la tabla con:

```sql
CREATE TABLE IF NOT EXISTS empresas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    nombreCliente VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telefono VARCHAR(50) NOT NULL,
    logo VARCHAR(255) NULL,
    rfc VARCHAR(50) NULL,
    direccion TEXT NULL
);
```

El campo `logo` es opcional; puedes subir imágenes y almacenar la ruta si prefieres más adelante.

## 12.2. Modelo y controlador

- `models/Empresa.php` extiende `ActiveRecord` y valida los campos obligatorios.
- `controllers/EmpresaController.php` implementa el CRUD y comprueba `isAdmin()` al inicio de cada método.

Las rutas añadidas en `public/index.php` son:

```php
$router->get('/empresas', [EmpresaController::class, 'index']);
$router->get('/empresas/crear', [EmpresaController::class, 'crear']);
$router->post('/empresas/crear', [EmpresaController::class, 'crear']);
$router->get('/empresas/actualizar', [EmpresaController::class, 'actualizar']);
$router->post('/empresas/actualizar', [EmpresaController::class, 'actualizar']);
$router->post('/empresas/eliminar', [EmpresaController::class, 'eliminar']);
```

## 12.3. Vistas

- `views/empresas/index.php` lista todas las empresas.
- `views/empresas/crear.php` y `actualizar.php` contienen el formulario con los campos mencionados y muestran errores.

## 12.4. Navegación

El enlace **Empresas** en la barra de administración lleva a la lista y desde ahí el admin puede crear, editar y borrar.

## 12.5. Estilos y referencias

Para conservar una apariencia similar al proyecto de referencia, hemos adaptado las variables de color y tipografía (`_variables.scss`) y creado clases reutilizables para botones (`_botones.scss`). Los formularios usan la misma estructura y `.alerta` para mensajes de error.

---

Con esto el administrador dispone de un módulo completo de empresas. Más adelante podrás añadir carga de logo con `$_FILES` y creación de miniaturas si lo deseas.