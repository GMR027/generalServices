# Paso 6: Creación de la tabla de usuarios

Necesitamos un lugar para almacenar a los dos tipos de usuarios (administrador y cliente) con sus credenciales y roles.

## 6.1. Estructura de la tabla
La tabla `usuarios` contendrá los campos comunes y un campo `rol` que definirá el permiso.

```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    correo VARCHAR(255) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    rol ENUM('admin','cliente') NOT NULL DEFAULT 'cliente',
    creado DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### Significado de columnas
- `nombre`: nombre de la persona (ambos roles).
- `correo`: dirección de email, única para cada usuario.
- `contrasena`: contraseña hasheada.
- `rol`: enum con valores `admin` o `cliente`. Por defecto `cliente`.
- `creado`: marca temporal de registro.

> 🔒 Importante: guarda siempre contraseñas usando `password_hash()` en PHP.

## 6.2. Cómo crear la base de datos
1. Accede a MySQL/MariaDB desde la terminal:
   ```bash
   mysql -u root -p
   ```
2. Crea la base de datos si no existe:
   ```sql
   CREATE DATABASE gestionproyectos;
   USE gestionproyectos;
   ```
3. Ejecuta la sentencia de creación de tabla mostrada arriba.

> ✅ Verificación: después de `SHOW TABLES;` debe aparecer `usuarios`. Puedes inspeccionar la definición con `DESCRIBE usuarios;` y confirmar las columnas.

## 6.3. Usuarios iniciales
Puedes añadir manualmente uno o dos registros para probar:

```sql
INSERT INTO usuarios (nombre, correo, contrasena, rol)
VALUES
('Admin Principal','admin@example.com','$2y$10$hash…','admin'),
('Cliente Demo','cliente@example.com','$2y$10$hash…','cliente');
```

(usa `php -r "echo password_hash('tuclave', PASSWORD_DEFAULT);"` para generar el hash).

Una vez creada la tabla y verificados los campos, el siguiente paso será construir el modelo `Usuario` y las funciones para autenticación.
