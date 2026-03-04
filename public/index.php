<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\PaginasController;
use Controllers\AdminController;

$router = new Router();

// Rutas públicas
$router->get('/', [PaginasController::class, 'index']);
$router->post('/', [PaginasController::class, 'index']);
// Programa de obra actividades
use Controllers\ActividadObraController;
$router->get('/p-obra', [ActividadObraController::class, 'index']);
$router->get('/p-obra/crear', [ActividadObraController::class, 'crear']);
$router->post('/p-obra/crear', [ActividadObraController::class, 'crear']);
$router->get('/p-obra/editar', [ActividadObraController::class, 'editar']);
$router->post('/p-obra/editar', [ActividadObraController::class, 'editar']);
$router->post('/p-obra/eliminar', [ActividadObraController::class, 'eliminar']);

// Autenticación
use Controllers\AuthController;
// login se gestiona desde la página de inicio (ruta '/').
// el controlador AuthController sigue disponible si se quiere acceder directamente.
//$router->get('/login', [AuthController::class, 'login']);
//$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// Rutas de prueba para después del login
// admin dashboard
$router->get('/admin', [AdminController::class, 'index']);

// CRUD empresas
use Controllers\EmpresaController;
use Controllers\PermisoController;
$router->get('/empresas', [EmpresaController::class, 'index']);
$router->get('/empresas/crear', [EmpresaController::class, 'crear']);
$router->post('/empresas/crear', [EmpresaController::class, 'crear']);
$router->get('/empresas/actualizar', [EmpresaController::class, 'actualizar']);
$router->post('/empresas/actualizar', [EmpresaController::class, 'actualizar']);
$router->post('/empresas/eliminar', [EmpresaController::class, 'eliminar']);

// permisos
$router->get('/permisos', [PermisoController::class, 'index']);
$router->get('/permisos/crear', [PermisoController::class, 'crear']);
$router->post('/permisos/crear', [PermisoController::class, 'crear']);
$router->get('/permisos/actualizar', [PermisoController::class, 'actualizar']);
$router->post('/permisos/actualizar', [PermisoController::class, 'actualizar']);
$router->post('/permisos/eliminar', [PermisoController::class, 'eliminar']);

// vista de permisos para cliente
$router->get('/mis-proyectos', [PermisoController::class, 'misProyectos']);

// CRUD usuarios (clientes y admins)
use Controllers\UsuarioController;
use Controllers\DisciplinaController;
use Controllers\SubdisciplinaController;
use Controllers\ProyectoController;
$router->get('/clientes', [UsuarioController::class, 'index']);
$router->get('/clientes/crear', [UsuarioController::class, 'crear']);
$router->post('/clientes/crear', [UsuarioController::class, 'crear']);
$router->get('/clientes/actualizar', [UsuarioController::class, 'actualizar']);
$router->post('/clientes/actualizar', [UsuarioController::class, 'actualizar']);
$router->post('/clientes/eliminar', [UsuarioController::class, 'eliminar']);

// CRUD disciplinas
$router->get('/disciplinas', [DisciplinaController::class, 'index']);
$router->get('/disciplinas/crear', [DisciplinaController::class, 'crear']);
$router->post('/disciplinas/crear', [DisciplinaController::class, 'crear']);
$router->get('/disciplinas/actualizar', [DisciplinaController::class, 'actualizar']);
$router->post('/disciplinas/actualizar', [DisciplinaController::class, 'actualizar']);
$router->post('/disciplinas/eliminar', [DisciplinaController::class, 'eliminar']);

// CRUD subdisciplinas
$router->get('/subdisciplinas', [SubdisciplinaController::class, 'index']);
$router->get('/subdisciplinas/crear', [SubdisciplinaController::class, 'crear']);
$router->post('/subdisciplinas/crear', [SubdisciplinaController::class, 'crear']);
$router->get('/subdisciplinas/actualizar', [SubdisciplinaController::class, 'actualizar']);
$router->post('/subdisciplinas/actualizar', [SubdisciplinaController::class, 'actualizar']);
$router->post('/subdisciplinas/eliminar', [SubdisciplinaController::class, 'eliminar']);

// CRUD proyectos
$router->get('/proyectos', [ProyectoController::class, 'index']);
$router->get('/proyectos/crear', [ProyectoController::class, 'crear']);
$router->post('/proyectos/crear', [ProyectoController::class, 'crear']);
$router->get('/proyectos/actualizar', [ProyectoController::class, 'actualizar']);
$router->post('/proyectos/actualizar', [ProyectoController::class, 'actualizar']);
$router->post('/proyectos/eliminar', [ProyectoController::class, 'eliminar']);

// CRUD reportes
use Controllers\ReporteController;
$router->get('/reportes', [ReporteController::class, 'index']);
$router->get('/reportes/crear', [ReporteController::class, 'crear']);
$router->post('/reportes/crear', [ReporteController::class, 'crear']);
$router->get('/reportes/editar', [ReporteController::class, 'editar']);
$router->post('/reportes/editar', [ReporteController::class, 'editar']);
$router->get('/reportes/detalle', [ReporteController::class, 'detalle']);
$router->post('/reportes/eliminar', [ReporteController::class, 'eliminar']);

// CRUD categorías (administrador)
use Controllers\CategoriaController;
$router->get('/categorias', [CategoriaController::class, 'index']);
$router->get('/categorias/crear', [CategoriaController::class, 'crear']);
$router->post('/categorias/crear', [CategoriaController::class, 'crear']);
$router->get('/categorias/actualizar', [CategoriaController::class, 'actualizar']);
$router->post('/categorias/actualizar', [CategoriaController::class, 'actualizar']);
$router->post('/categorias/eliminar', [CategoriaController::class, 'eliminar']);

// CRUD bitácora
use Controllers\BitacoraController;
$router->get('/bitacora', [BitacoraController::class, 'index']);
$router->get('/bitacora/crear', [BitacoraController::class, 'crear']);
$router->post('/bitacora/crear', [BitacoraController::class, 'crear']);
$router->get('/bitacora/editar', [BitacoraController::class, 'editar']);
$router->post('/bitacora/editar', [BitacoraController::class, 'editar']);
$router->post('/bitacora/eliminar', [BitacoraController::class, 'eliminar']);
// detalle bitácora
$router->get('/bitacora/detalle', [BitacoraController::class, 'detalle']);

// CRUD minutas (nueva funcionalidad)
use Controllers\MinutaController;
$router->get('/minutas', [MinutaController::class, 'index']);
$router->get('/minutas/crear', [MinutaController::class, 'crear']);
$router->post('/minutas/crear', [MinutaController::class, 'crear']);
$router->get('/minutas/actualizar', [MinutaController::class, 'actualizar']);
$router->post('/minutas/actualizar', [MinutaController::class, 'actualizar']);
$router->post('/minutas/eliminar', [MinutaController::class, 'eliminar']);
// detalle de minuta
$router->get('/minutas/detalle', [MinutaController::class, 'detalle']);


// Ejecutar
$router->comprobarRutas();
