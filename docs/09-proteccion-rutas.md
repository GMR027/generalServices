# Paso 9: Protección de rutas y gestión de roles

Con los usuarios y el login funcionando, es hora de restringir el acceso según el rol.

## 9.1. Helpers de sesión y rol

En `includes/funciones.php` añadimos varias funciones para trabajar con el usuario autenticado y su rol:

```php
function isAdmin(): bool {
    session_start();
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
}

function isCliente(): bool {
    session_start();
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'cliente';
}

function usuarioActual(): ?string {
    // devuelve el correo del usuario conectado (antes retornaba el nombre)
    session_start();
    return $_SESSION['correo'] ?? null;
}

function idActual(): ?int {
    session_start();
    return isset($_SESSION['id']) ? intval($_SESSION['id']) : null;
}
```

Estas funciones inician la sesión (si no está iniciada) y devuelven `true` solo si el rol coincide.

## 9.2. Ejemplo de rutas protegidas

En `public/index.php` ya existía un arreglo de rutas protegidas dentro del `Router`; para un proyecto de gestión de proyectos puedes actualizarlo así:

```php
$rutasProtegidas = [
    '/admin',
    '/clientes',
    '/clientes/crear',
    '/clientes/actualizar',
    '/clientes/eliminar',
    '/disciplinas',
    '/disciplinas/crear',
    '/disciplinas/actualizar',
    '/disciplinas/eliminar',
    '/subdisciplinas',
    '/subdisciplinas/crear',
    '/subdisciplinas/actualizar',
    '/subdisciplinas/eliminar',
    '/proyectos',
    '/proyectos/crear',
    '/proyectos/actualizar',
    '/proyectos/eliminar',
];
```

En el método `comprobarRutas()` del router, esa lista se verifica y redirige a `/login` si no hay sesión activa. Puedes modificarlo para comprobar además el rol:

```php
if(in_array($urlActual, $rutasProtegidas)) {
    if(!isset($_SESSION['login'])) {
        header('Location: /login');
    } else if(!isAdmin()) {
        // si el usuario es cliente y trata de acceder a una ruta de administración
        header('Location: /');
    }
}
```

Este fragmento evita el acceso de clientes a páginas de administración.

> 🔐 Otra posibilidad es definir rutas protegidas por tipo y comprarlas individualmente en cada controlador.

## 9.3. Comprobaciones dentro de controladores

Es buena práctica verificar el rol al inicio de cada método de un controlador que maneje recursos sensibles. Por ejemplo, en `ProyectoController` podrías hacer:

```php
public static function index(Router $router) {
    if(!isAdmin()) {
        header('Location: /');
        return;
    }
    // ... resto del código
}
```

Para clientes podrías mostrar solo sus proyectos y no permitir creación/edición.

## 9.4. Verificación manual

1. Inicia sesión con un usuario admin.
2. Accede a `/proyectos` o `/admin`; deberías entrar sin problemas.
3. Cierra sesión y vuelve a iniciar con un cliente.
4. Navega a `/proyectos` o intenta acceder a una ruta de administración: deberías ser redirigido al inicio o al login.

> ✅ Si los usuarios no autorizados no pueden ver ni ejecutar acciones restringidas, la protección está activa.

En el próximo documento abordaremos la interfaz de administración y la asignación de clientes a proyectos (cuando llegue ese momento).