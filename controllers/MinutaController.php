<?php

namespace Controllers;

use MVC\Router;
use Model\Minuta;
use Model\Proyecto;
use Model\Permiso;

class MinutaController {
    public static function index(Router $router) {
        ensureSession();
        $userId = idActual();
        // proyectos para filtro
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

        // construir consulta de minutas
        $query = "SELECT m.*, p.nombre AS proyecto_nombre FROM minutas m " .
                 "LEFT JOIN proyectos p ON m.proyecto_id = p.id";
        if(!empty($selected)) {
            $query .= " WHERE m.proyecto_id IN (" . join(',', $selected) . ")";
        }
        $query .= " ORDER BY m.fecha DESC";
        $minutas = Minuta::consultaSQL($query);

        $router->render('minutas/index', [
            'minutas' => $minutas,
            'filterProjects' => $filterProjects,
            'selectedProjects' => $selected
        ]);
    }

    public static function crear(Router $router) {
        ensureSession();
        // obtener lista de proyectos según permisos
        $userId = idActual();
        if(isAdmin()) {
            $proyectos = Proyecto::all();
        } else {
            $sql = "SELECT p.* FROM permisos perm JOIN proyectos p ON perm.proyecto_id = p.id " .
                   "WHERE perm.cliente_id = " . intval($userId);
            $proyectos = Proyecto::consultaSQL($sql);
        }

        $minuta = new Minuta;
        $errores = Minuta::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            /** @var Minuta $minuta */
            $minuta = new Minuta($_POST['minuta']);
            $errores = $minuta->validarErrores();
            // validar que proyecto esté permitido para no-admins
            if(!isAdmin()) {
                $allowedIds = array_map(fn($p) => $p->id, $proyectos);
                if(!in_array($minuta->proyecto_id, $allowedIds, true)) {
                    $errores[] = 'No tiene permiso para usar el proyecto seleccionado.';
                }
            }
            if(empty($errores)) {
                $minuta->guardar();
                header('Location: /minutas?resultado=1');
                return;
            }
        }

        $router->render('minutas/crear', [
            'minuta' => $minuta,
            'errores' => $errores,
            'proyectos' => $proyectos
        ]);
    }

    public static function actualizar(Router $router) {
        ensureSession();
        $userId = idActual();
        $id = $_GET['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if(!$id) {
            header('Location: /minutas');
            return;
        }
        /** @var Minuta $minuta */
        $minuta = Minuta::find($id);
        if(!$minuta) {
            header('Location: /minutas');
            return;
        }
        $errores = Minuta::getErrores();
        // proyectos disponibles para selección según permisos
        if(isAdmin()) {
            $proyectos = Proyecto::all();
        } else {
            $sql = "SELECT p.* FROM permisos perm JOIN proyectos p ON perm.proyecto_id = p.id " .
                   "WHERE perm.cliente_id = " . intval($userId);
            $proyectos = Proyecto::consultaSQL($sql);
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $minuta->sincronizar($_POST['minuta']);
            $errores = $minuta->validarErrores();
            // validación de proyecto para no-admins
            if(!isAdmin()) {
                $allowedIds = array_map(fn($p) => $p->id, $proyectos);
                $pid = $minuta->proyecto_id;
                if(!in_array($pid, $allowedIds, true)) {
                    $errores[] = 'No tiene permiso para usar el proyecto seleccionado.';
                }
            }
            if(empty($errores)) {
                $minuta->guardar();
                header('Location: /minutas?resultado=2');
                return;
            }
        }

        $router->render('minutas/actualizar', [
            'minuta' => $minuta,
            'errores' => $errores,
            'proyectos' => $proyectos
        ]);
    }

    public static function eliminar() {
        ensureSession();
        $userId = idActual();
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if($id) {
                /** @var Minuta|null $min */
                $min = Minuta::find($id);
                if($min) {
                    // si no es admin, comprobar permiso sobre proyecto
                    if(isAdmin()) {
                        $min->eliminar();
                        setFlashMessage('Minuta eliminada');
                    } else {
                        $sql = "SELECT * FROM permisos WHERE cliente_id=" . intval($userId) . " AND proyecto_id=" . intval($min->proyecto_id) . " LIMIT 1";
                        $perm = Permiso::consultaSQL($sql);
                        if(!empty($perm)) {
                            $min->eliminar();
                            setFlashMessage('Minuta eliminada');
                        } else {
                            setFlashMessage('No tiene permiso para eliminar esta minuta', 'error');
                        }
                    }
                }
            }
            header('Location: /minutas');
        }
    }

    public static function detalle(Router $router) {
        ensureSession();
        $id = $_GET['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if(!$id) {
            header('Location: /minutas');
            return;
        }
        /** @var Minuta $minuta */
        $minuta = Minuta::find($id);
        if(!$minuta) {
            header('Location: /minutas');
            return;
        }
        $proyecto = Proyecto::find($minuta->proyecto_id);
        $router->render('minutas/detalle', [
            'minuta' => $minuta,
            'proyecto' => $proyecto
        ]);
    }
}
