# Cambios respecto al proyecto inicial

Este documento describe las diferencias entre el "proyecto de referencia inicial" (la estructura MVC básica que se creó en el primer paso) y el estado actual de `gestionproyectosMVC`. Sirve como guía para saber qué carpetas, archivos y funcionalidades nuevas hay que aprender si se quiere construir algo similar desde cero.

## 1. Estructura base

El proyecto inicial sólo tenía:

- carpetas vacías `controllers/`, `models/`, `views/`, `includes/`, `public/`, `src/`, `docs/`
- `composer.json` con autoload PSR‑4 y `vendor/` generado
- `gulpfile.js` y dependencias mínimas para compilar Sass/JS
- un `Router.php` muy simple
- una vista de ejemplo y unas pocas funciones utilitarias básicas

A partir de ahí se ha ido construyendo la aplicación.

## 2. Nuevas funcionalidades implementadas

Las entregas documentadas en la carpeta `docs/` muestran el paso a paso; a grosso modo:

1. **Autenticación y autorización**
   - Controlador `AuthController` con login/logout.
   - Helpers de sesión (`asegurarSesion`, `esAdmin`, `esCliente`, etc.).
   - Ruta protegida para administrador en `Router.php`.

2. **CRUD de usuarios** (`UsuarioController`, modelos y vistas relacionadas).
   - Rol de usuario (`admin`, `cliente`) con validaciones en modelo/servicios.

3. **Proyectos y permisos**
   - Modelos `Proyecto`, `Permiso` y controladores para crear/editar proyectos y asignar permisos de clientes.
   - Filtros en las consultas según permisos del cliente.

4. **Empresas, disciplinas y subdisciplinas**
   - Controladores y vistas de `EmpresaController`, `DisciplinaController`, `SubdisciplinaController`.

5. **Reportes de obra**
   - `ReporteController` con carga de imágenes, galería, filtros y permisos.
   - Bibliotecas de terceros (Intervention Image) para procesar imágenes.

6. **Bitácora**
   - Entradas de bitácora con categorías.
   - Permisos por proyecto para consulta/edición.

7. **Minutas**
   - CRUD completo con relación a proyectos.
   - En la vista detalle se agregó un botón de exportación a PDF (visiblemente impresa).

8. **Administración general**
   - Pantalla de administración (`AdminController` y vistas) para visualizar contadores y accesos rápidos.

9. **Estilos y frontend**
   - Integración de Materialize para componentes UI.
   - Sass compilado en `build/css`, JavaScript mínimo en `src/js/app.js`.
   - Reglas CSS específicas para impresión/PDF (`custom.css`).

10. **Documentación técnica**
    - Más de 18 archivos `docs/0X-*.md` que narran la evolución del proyecto.

## 3. Archivos y dependencias añadidos

- Nuevos controladores en `controllers/` (más de una docena).
- Modelos en `models/` para cada tabla de la BD.
- Vistas completas en `views/` organizadas por recurso.
- `public/index.php` con todas las rutas cargadas.
- dependencias de Composer (autoload básico, Intervention Image).
- `node_modules` y configuraciones de Gulp en `package.json`.

## 4. Código refactorizado recientemente

- Los helpers de sesión y flash han sido renombrados por convención en español (`asegurarSesion`, etc.).
- Variables internas en controladores (ej. `$idUsuario`, `$proyectosFiltro`).

## 5. Conclusión

El proyecto actual incorpora:

- **Autenticación/roles**
- **Control de accesos fino** basado en permisos de proyectos
- **Múltiples recursos CRUD** (usuarios, proyectos, empresas, reportes, bitácora, minutas)
- **Exportación/imágenes/estilos**

Para reproducir esto desde cero se necesita dominar: PHP básico, patrones MVC, sesiones, consultas SQL con seguridad (intval y validaciones), Materialize/Sass y herramientas de build (Gulp). El `docs/` contiene el tutorial paso a paso; revisarlo es una buena forma de aprender el camino recorrido.

En resumen, el proyecto actual está muy alejado de la plantilla inicial: se ha añadido casi todas las capas de la aplicación (lógica de negocio, vistas, filtros, permisos, estilos) y requiere un conocimiento intermedio de PHP y del flujo MVC.