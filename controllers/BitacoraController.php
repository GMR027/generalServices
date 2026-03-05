<?php

namespace Controllers;

use MVC\Router;
use Model\Bitacora;
use Model\Proyecto;
use Model\Permiso;
use Model\Categoria;

class BitacoraController {
    public static function index(Router $router) {
        asegurarSesion();
        $idUsuario = idActual();

        // proyectos disponibles para filtro
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

        if(esAdmin()) {
            $query = "SELECT b.*, p.nombre AS proyecto_nombre, c.nombre AS categoria_nombre " .
                     "FROM bitacoras b " .
                     "JOIN proyectos p ON b.proyecto_id = p.id " .
                     "LEFT JOIN categorias c ON b.categoria_id = c.id";
            if(!empty($selected)) {
                $query .= " WHERE b.proyecto_id IN (" . join(',', $selected) . ")";
            }
            $query .= " ORDER BY b.fecha DESC";
            $bitacoras = Bitacora::consultaSQL($query);
        } else {
            $query = "SELECT b.*, p.nombre AS proyecto_nombre, c.nombre AS categoria_nombre " .
                     "FROM bitacoras b " .
                     "JOIN permisos perm ON b.proyecto_id = perm.proyecto_id " .
                     "JOIN proyectos p ON b.proyecto_id = p.id " .
                     "LEFT JOIN categorias c ON b.categoria_id = c.id " .
                     "WHERE perm.cliente_id = " . intval($idUsuario);
            if(!empty($selected)) {
                $query .= " AND b.proyecto_id IN (" . join(',', $selected) . ")";
            }
            $query .= " ORDER BY b.fecha DESC";
            $bitacoras = Bitacora::consultaSQL($query);
        }

        $router->render('bitacora/index', [
            'bitacoras' => $bitacoras,
            'filterProjects' => $filterProjects,
            'selectedProjects' => $selected
        ]);
    }

    public static function crear(Router $router) {
        asegurarSesion();
        $idUsuario = idActual();

        if(esAdmin()) {
            $proyectos = Proyecto::all();
        } else {
            $sql = "SELECT p.* FROM permisos perm JOIN proyectos p ON perm.proyecto_id = p.id " .
                   "WHERE perm.cliente_id = " . intval($idUsuario);
            $proyectos = Proyecto::consultaSQL($sql);
        }

        $categorias = Categoria::all();

        $bitacora = new Bitacora;

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bitacora = new Bitacora($_POST['bitacora']);
            $errores = $bitacora->validarErrores();

            // comprobar que el proyecto elegido esté en la lista permitida
            if(!esAdmin()) {
                $allowedIds = array_map(fn($p) => $p->id, $proyectos);
                if(!in_array($bitacora->proyecto_id, $allowedIds, true)) {
                    $errores[] = 'No tiene permiso para usar el proyecto seleccionado.';
                }
            }

            if(empty($errores)) {
                $bitacora->guardar();
                establecerMensajeFlash('Entrada de bitácora creada correctamente');
                header('Location: /bitacora');
                return;
            }
        }

        $router->render('bitacora/crear', [
            'proyectos' => $proyectos,
            'categorias' => $categorias,
            'bitacora' => $bitacora ?? null,
            'errores' => $errores ?? []
        ]);
    }

    public static function eliminar() {
        asegurarSesion();
        $idUsuario = idActual();
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if(!$id) {
                header('Location: /bitacora');
                return;
            }
            /** @var Bitacora|null $bit */
            $bit = Bitacora::find($id);
            if(!$bit) {
                header('Location: /bitacora');
                return;
            }

            if(esAdmin()) {
                $bit->eliminar();
                establecerMensajeFlash('Entrada eliminada');
            } else {
                $query = "SELECT * FROM permisos WHERE cliente_id=" . intval($idUsuario) . " AND proyecto_id=" . intval($bit->proyecto_id) . " LIMIT 1";
                $perm = Permiso::consultaSQL($query);
                if(!empty($perm)) {
                    $bit->eliminar();
                    establecerMensajeFlash('Entrada eliminada');
                } else {
                    establecerMensajeFlash('No tienes permiso para eliminar esta entrada', 'error');
                }
            }
            header('Location: /bitacora');
        }
    }

    public static function editar(Router $router) {
        asegurarSesion();
        $idUsuario = idActual();
        $id = $_GET['id'] ?? $_POST['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if(!$id) {
            header('Location: /bitacora');
            return;
        }
        $bitacora = Bitacora::find($id);
        if(!$bitacora) {
            header('Location: /bitacora');
            return;
        }

        if(!esAdmin()) {
            // @var Bitacora $bitacora
            /** @var Bitacora $bitacora */
            $pid = $bitacora->proyecto_id;
            $query = "SELECT * FROM permisos WHERE cliente_id=" . intval($idUsuario) . " AND proyecto_id=" . intval($pid) . " LIMIT 1";
            $perm = Permiso::consultaSQL($query);
            if(empty($perm)) {
                establecerMensajeFlash('No tienes permiso para modificar esta entrada', 'error');
                header('Location: /bitacora');
                return;
            }
        }

        $errores = Bitacora::getErrores();
        if(esAdmin()) {
            $proyectos = Proyecto::all();
        } else {
            $sql = "SELECT p.* FROM permisos perm JOIN proyectos p ON perm.proyecto_id = p.id " .
                   "WHERE perm.cliente_id = " . intval($idUsuario);
            $proyectos = Proyecto::consultaSQL($sql);
        }
        $categorias = Categoria::all();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bitacora->sincronizar($_POST['bitacora']);
            $errores = $bitacora->validarErrores();

            if(empty($errores)) {
                $bitacora->guardar();
                establecerMensajeFlash('Entrada actualizada correctamente');
                header('Location: /bitacora');
                return;
            }
        }

        $router->render('bitacora/editar', [
            'proyectos' => $proyectos,
            'categorias' => $categorias,
            'bitacora' => $bitacora,
            'errores' => $errores
        ]);
    }

    public static function detalle(Router $router) {
        asegurarSesion();
        $idUsuario = idActual();
        $id = $_GET['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if(!$id) {
            header('Location: /bitacora');
            return;
        }
        $bitacora = Bitacora::find($id);
        if(!$bitacora) {
            header('Location: /bitacora');
            return;
        }

        // permiso: admin o cliente con cualquier permiso sobre proyecto
        if(!esAdmin()) {
            // @var Bitacora $bitacora
            /** @var Bitacora $bitacora */
            $pid = $bitacora->proyecto_id;
            $query = "SELECT * FROM permisos WHERE cliente_id=" . intval($idUsuario) . " AND proyecto_id=" . intval($pid) . " LIMIT 1";
            $perm = Permiso::consultaSQL($query);
            if(empty($perm)) {
                header('Location: /bitacora');
                return;
            }
        }

        $router->render('bitacora/detalle', [
            'bitacora' => $bitacora
        ]);
    }
}
