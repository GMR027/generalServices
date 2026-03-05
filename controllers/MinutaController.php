<?php

namespace Controllers;

use MVC\Router;
use Model\Minuta;
use Model\Proyecto;
use Model\Permiso;

class MinutaController {
    public static function index(Router $router) {
        asegurarSesion();
        $idUsuario = idActual();
        // proyectos para filtro
        if(esAdmin()) {
            $filterProjects = Proyecto::all();
        } else {
            $sql = "SELECT p.* FROM permisos perm JOIN proyectos p ON perm.proyecto_id = p.id " .
                   "WHERE perm.cliente_id = " . intval($idUsuario);
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
        asegurarSesion();
        // obtener lista de proyectos según permisos
        $idUsuario = idActual();
        if(esAdmin()) {
            $proyectos = Proyecto::all();
        } else {
            $sql = "SELECT p.* FROM permisos perm JOIN proyectos p ON perm.proyecto_id = p.id " .
                   "WHERE perm.cliente_id = " . intval($idUsuario);
            $proyectos = Proyecto::consultaSQL($sql);
        }

        $minuta = new Minuta;
        $errores = Minuta::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            /** @var Minuta $minuta */
            $minuta = new Minuta($_POST['minuta']);
            $errores = $minuta->validarErrores();
            // validar que proyecto esté permitido para no-admins
            if(!esAdmin()) {
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
        asegurarSesion();
        $idUsuario = idActual();
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
        if(esAdmin()) {
            $proyectos = Proyecto::all();
        } else {
            $sql = "SELECT p.* FROM permisos perm JOIN proyectos p ON perm.proyecto_id = p.id " .
                   "WHERE perm.cliente_id = " . intval($idUsuario);
            $proyectos = Proyecto::consultaSQL($sql);
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $minuta->sincronizar($_POST['minuta']);
            $errores = $minuta->validarErrores();
            // validación de proyecto para no-admins
            if(!esAdmin()) {
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
        asegurarSesion();
        $idUsuario = idActual();
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if($id) {
                /** @var Minuta|null $min */
                $min = Minuta::find($id);
                if($min) {
                    // si no es admin, comprobar permiso sobre proyecto
                    if(esAdmin()) {
                        $min->eliminar();
                        establecerMensajeFlash('Minuta eliminada');
                    } else {
                        $sql = "SELECT * FROM permisos WHERE cliente_id=" . intval($idUsuario) . " AND proyecto_id=" . intval($min->proyecto_id) . " LIMIT 1";
                        $perm = Permiso::consultaSQL($sql);
                        if(!empty($perm)) {
                            $min->eliminar();
                            establecerMensajeFlash('Minuta eliminada');
                        } else {
                            establecerMensajeFlash('No tiene permiso para eliminar esta minuta', 'error');
                        }
                    }
                }
            }
            header('Location: /minutas');
        }
    }

    public static function detalle(Router $router) {
        asegurarSesion();
        $idUsuario = idActual();
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

        // permisos: si no es administrador debe tener permiso sobre el proyecto
        if(!esAdmin()) {
            $query = "SELECT * FROM permisos WHERE cliente_id=" . intval($idUsuario) .
                     " AND proyecto_id=" . intval($minuta->proyecto_id) . " LIMIT 1";
            $perm = Permiso::consultaSQL($query);
            if(empty($perm)) {
                header('Location: /minutas');
                return;
            }
        }

        // si se solicita impresión, enviamos un HTML mínimo (sin layout) y cargamos estilos
        if(isset($_GET['print'])) {
            // variables para la vista
            $minuta = $minuta;
            $proyecto = $proyecto;
            // HTML básico con hoja de estilos ya compilada
            echo '<!DOCTYPE html>';
            echo '<html lang="es"><head><meta charset="UTF-8">';
            echo '<title>Minuta ' . htmlspecialchars($minuta->tituloJunta) . '</title>';
            echo '<link rel="stylesheet" href="/css/custom.css">';
            echo '</head><body>';
            include __DIR__ . '/../views/minutas/detalle.php';
            // el propio view incluye un script para window.print cuando hay ?print
            echo '</body></html>';
            exit;
        }

        $router->render('minutas/detalle', [
            'minuta' => $minuta,
            'proyecto' => $proyecto
        ]);
    }
}
