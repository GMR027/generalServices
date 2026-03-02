# Paso 10: CRUD de usuarios (admin y clientes)

Ya que el administrador puede crear y gestionar tanto clientes como otros administradores, implementaremos un CRUD completo usando el modelo `Usuario` existente.

## 10.1. Rutas y controlador

Hemos añadido el controlador `UsuarioController` con métodos:

- `index()` – lista todos los usuarios
- `crear()` – formulario de alta y manejo de POST
- `actualizar()` – edición de registro existente
- `eliminar()` – borra un usuario

Las rutas configuradas en `public/index.php` son:

```php
$router->get('/clientes', [UsuarioController::class, 'index']);
$router->get('/clientes/crear', [UsuarioController::class, 'crear']);
$router->post('/clientes/crear', [UsuarioController::class, 'crear']);
$router->get('/clientes/actualizar', [UsuarioController::class, 'actualizar']);
$router->post('/clientes/actualizar', [UsuarioController::class, 'actualizar']);
$router->post('/clientes/eliminar', [UsuarioController::class, 'eliminar']);
```

> Nota: Usamos `/clientes` como base; el admin podrá acceder aquí para ver usuarios de ambos roles.

## 10.2. Vistas generadas

- `views/usuarios/index.php` lista y ofrece enlaces de edición/borrado.
- `views/usuarios/crear.php` y `actualizar.php` contienen el formulario con campos `nombre`, `correo`, `contraseña` y `rol`.

Ambos formularios muestran errores devueltos por `$usuario->validarErrores()`.

## 10.3. Barra de navegación del panel

La barra del layout ahora muestra los enlaces específicos cuando un administrador está conectado:

- **Home Admin** → `/admin`
- **Disciplinas** → `/disciplinas` (gestión de disciplinas y subdisciplinas)
- **Clientes** → `/clientes`
- **Empresas** → `/empresas`
- **Proyectos** → `/proyectos`

Estos enlaces solo aparecen si `rolActual()` retorna `'admin'`. El resto de usuarios seguirá viendo únicamente el enlace de login.

## 10.4. Mensajería y redirección

Después de crear, actualizar o borrar, el usuario es redirigido a `/clientes` con un parámetro `resultado` que en el panel puede mostrar un mensaje (implementable usando `getFlashMessage()` si se desea).

## 10.4. Ejecución

1. Inicia sesión como admin.
2. Ve a la sección “Clientes” mediante la barra de navegación.
3. Crea un nuevo usuario (escribe contraseña simple; se hasheará automáticamente).
4. Edita o elimina registros.

> 🔒 Recuerda que sólo un admin debería poder acceder a estas rutas; la protección se realiza en el router y también puedes añadir `isAdmin()` al inicio de los métodos del controlador si deseas doble chequeo.

Con esto, la gestión de usuarios queda lista.

---

En los siguientes pasos crearemos el CRUD de proyectos con tablas auxiliares para disciplina y subdisciplina.