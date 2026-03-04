<?php

namespace Controllers;

use MVC\Router;
use Model\ActividadObra;
use Model\Proyecto;
use Model\Permiso;

class ActividadObraController {
    public static function index(Router $router) {
        ensureSession();
        $userId = idActual();

        // proyectos disponibles para filtro (todos para admin, permisos para cliente)
        if(isAdmin()) {
            $filterProjects = Proyecto::all();
        } else {
            $sql = "SELECT p.* FROM permisos perm JOIN proyectos p ON perm.proyecto_id = p.id " .
                   "WHERE perm.cliente_id = " . intval($userId);
            $filterProjects = Proyecto::consultaSQL($sql);
        }

        $selected = [];
        if(isset($_GET['proyecto_id'])) {
            $selected = array_map('intval', (array)$_GET['proyecto_id']);
        }

        if(isAdmin()) {
            $query = "SELECT a.*, p.nombre AS proyecto_nombre " .
                     "FROM actividades_obra a " .
                     "JOIN proyectos p ON a.proyecto_id = p.id";
            if(!empty($selected)) {
                $query .= " WHERE a.proyecto_id IN (" . join(',', $selected) . ")";
            }
            $query .= " ORDER BY a.proyecto_id, a.item";
            $actividades = ActividadObra::consultaSQL($query);
        } else {
            $query = "SELECT a.*, p.nombre AS proyecto_nombre " .
                     "FROM actividades_obra a " .
                     "JOIN permisos perm ON a.proyecto_id = perm.proyecto_id " .
                     "JOIN proyectos p ON a.proyecto_id = p.id " .
                     "WHERE perm.cliente_id = " . intval($userId);
            if(!empty($selected)) {
                $query .= " AND a.proyecto_id IN (" . join(',', $selected) . ")";
            }
            $query .= " ORDER BY a.proyecto_id, a.item";
            $actividades = ActividadObra::consultaSQL($query);
        }

        $router->render('pobra/index', [
            'actividades' => $actividades,
            'filterProjects' => $filterProjects,
            'selectedProjects' => $selected
        ]);
    }

    public static function crear(Router $router) {
        ensureSession();
        $userId = idActual();

        if(isAdmin()) {
            $proyectos = Proyecto::all();
        } else {
            $sql = "SELECT p.* FROM permisos perm JOIN proyectos p ON perm.proyecto_id = p.id " .
                   "WHERE perm.cliente_id = " . intval($userId);
            $proyectos = Proyecto::consultaSQL($sql);
        }

        $actividad = new ActividadObra;
        $errores = ActividadObra::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $actividad = new ActividadObra($_POST['actividad']);
            // recalcular dias
            if($actividad->fecha_inicio && $actividad->fecha_fin) {
                $ini = strtotime($actividad->fecha_inicio);
                $fin = strtotime($actividad->fecha_fin);
                if($ini && $fin) {
                    $actividad->dias = intval(($fin - $ini) / 86400) + 1;
                }
            }

            $errores = $actividad->validarErrores();
            if(!isAdmin()) {
                $allowed = array_map(fn($p) => $p->id, $proyectos);
                if(!in_array($actividad->proyecto_id, $allowed, true)) {
                    $errores[] = 'No tiene permiso para el proyecto seleccionado.';
                }
            }
            if(empty($errores)) {
                $actividad->guardar();
                setFlashMessage('Actividad guardada correctamente');
                header('Location: /p-obra');
                return;
            }
        }

        $router->render('pobra/crear', [
            'proyectos' => $proyectos,
            'actividad' => $actividad,
            'errores' => $errores
        ]);
    }

    public static function editar(Router $router) {
        ensureSession();
        $userId = idActual();
        $id = $_GET['id'] ?? $_POST['actividad']['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if(!$id) {
            header('Location: /p-obra');
            return;
        }
        $actividad = ActividadObra::find($id);
        if(!$actividad) {
            header('Location: /p-obra');
            return;
        }

        $errores = ActividadObra::getErrores();
        if(isAdmin()) {
            $proyectos = Proyecto::all();
        } else {
            $sql = "SELECT p.* FROM permisos perm JOIN proyectos p ON perm.proyecto_id = p.id " .
                   "WHERE perm.cliente_id = " . intval($userId);
            $proyectos = Proyecto::consultaSQL($sql);
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $actividad->sincronizar($_POST['actividad']);
            if($actividad->fecha_inicio && $actividad->fecha_fin) {
                $ini = strtotime($actividad->fecha_inicio);
                $fin = strtotime($actividad->fecha_fin);
                if($ini && $fin) {
                    $actividad->dias = intval(($fin - $ini) / 86400) + 1;
                }
            }
            $errores = $actividad->validarErrores();
            if(empty($errores)) {
                $actividad->guardar();
                setFlashMessage('Actividad actualizada correctamente');
                header('Location: /p-obra');
                return;
            }
        }

        $router->render('pobra/editar', [
            'proyectos' => $proyectos,
            'actividad' => $actividad,
            'errores' => $errores
        ]);
    }

    public static function eliminar() {
        ensureSession();
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if($id) {
                $act = ActividadObra::find($id);
                if($act) {
                    $act->eliminar();
                    setFlashMessage('Actividad eliminada');
                }
            }
            header('Location: /p-obra');
        }
    }
}
