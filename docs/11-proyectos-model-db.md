# Paso 11: Tablas para proyectos, disciplina y subdisciplina

Los proyectos requieren tanto atributos propios como foráneos que apuntan a disciplina y subdisciplina.

## 11.1. Estructura de tablas

```sql
CREATE TABLE disciplinas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

CREATE TABLE subdisciplinas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    disciplina_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id)
    ON DELETE CASCADE
);

CREATE TABLE proyectos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    ubicacion VARCHAR(255) NOT NULL,
    disciplina_id INT NOT NULL,
    subdisciplina_id INT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NULL,
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id),
    FOREIGN KEY (subdisciplina_id) REFERENCES subdisciplinas(id)
);

-- tabla para permisos asignados a clientes
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

- `disciplinas`: catálogo principal.
- `subdisciplinas`: cada registro pertenece a una disciplina.
- `proyectos`: contiene los datos pedidos más las claves foráneas.

## 11.2. Modelo MVC

Crearemos tres modelos:

- `Disciplina` con `$tabla='disciplinas'` y `$columnasDB=['id','nombre']`.
- `Subdisciplina` con `$tabla='subdisciplinas'` y `$columnasDB=['id','disciplina_id','nombre']`.
- `Proyecto` con `$tabla='proyectos'` y columnas correspondientes.

Cada modelo heredará de `ActiveRecord` y tendrá validaciones mínimas (campos obligatorios).

## 11.3. CRUD y formularios

1. **DisciplinaController**: CRUD simple para mantener el catálogo.
2. **SubdisciplinaController**: CRUD, con select dinámico de disciplina.
3. **ProyectoController**: formulario incluyendo selects para disciplina y subdisciplina (los sublistados filtrados mediante AJAX o recarga simple). Las columnas de fecha se muestran como `<input type="date">`.

Las vistas se alojarán debajo de `views/disciplinas/`, `views/subdisciplinas/`, `views/proyectos/`.

## 11.4. Rutas para administración

Añadir en `public/index.php` rutas similares a las de usuarios:

```php
$router->get('/disciplinas', ...);
$router->get('/disciplinas/crear', ...);
$router->post('/disciplinas/crear', ...);
// etc.
```

## 11.5. Verificación

- Crear algunas disciplinas y subdisciplinas manualmente y comprobar que aparecen en los selects de creación de proyecto.
- Insertar un proyecto y verificar que la relación se mantiene.

Una vez implementes el CRUD y los modelos, podrás asignar disciplinas y proyectos desde el panel de administración.

---

El siguiente paso será generar los modelos y controladores mencionados. Podemos empezar por la tabla `disciplinas` y su CRUD.

> **Actualización:** más adelante se decidió simplificar la tabla de proyectos y eliminar las columnas `disciplina_id` y `subdisciplina_id`. Los formularios y consultas ya no las incluyen y deben quitarse de la base de datos con:
> ```sql
> ALTER TABLE proyectos
>   DROP FOREIGN KEY proyectos_ibfk_1,
>   DROP FOREIGN KEY proyectos_ibfk_2,
>   DROP COLUMN disciplina_id,
>   DROP COLUMN subdisciplina_id;
> ```
> Esta documentación permanece para el histórico, pero el modelo `Proyecto` se ajustó en el código.

A su vez se decidió almacenar dicha información directamente en la tabla `reportes`. Para añadir la columna correspondiente ejecuta:

```sql
ALTER TABLE reportes
  ADD COLUMN subdisciplina_id INT NULL AFTER proyecto_id,
  ADD FOREIGN KEY (subdisciplina_id) REFERENCES subdisciplinas(id);
```

Posteriormente se actualizó el modelo `Reporte` y los formularios para permitir al usuario especificar la subdisciplina asociada a cada reporte.