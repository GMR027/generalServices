<?php

namespace Controllers;

use MVC\Router;
use Model\Reporte;
use Model\Proyecto;
use Model\Permiso;
// Intervention Image for handling uploads (v3 uses ImageManager)
use Intervention\Image\ImageManager;


class ReporteController {
    public static function index(Router $router) {
        asegurarSesion();
        $idUsuario = idActual();
        // determinar si el cliente puede crear reportes (tiene al menos un proyecto editable)
        $canCreate = esAdmin();

        // proyectos disponibles para filtro (todos para admin, sólo los permisos del cliente para usuarios normales)
        if(esAdmin()) {
            $filterProjects = Proyecto::all();
        } else {
            $sql = "SELECT p.* FROM permisos perm JOIN proyectos p ON perm.proyecto_id = p.id " .
                   "WHERE perm.cliente_id = " . intval($idUsuario);
            $filterProjects = Proyecto::consultaSQL($sql);
        }

        // recoger proyectos seleccionados en el filtro (puede ser array)
        $selected = [];
        if(isset($_GET['proyecto_id'])) {
            $selected = array_map('intval', (array)$_GET['proyecto_id']);
        }

        // construir consulta principal
        if(esAdmin()) {
            // include discipline and subdiscipline via joins to avoid N+1
            $query = "SELECT r.*, d.nombre AS disciplina_nombre, sd.nombre AS subdisciplina_nombre " .
                     "FROM reportes r " .
                     "JOIN proyectos p ON r.proyecto_id = p.id " .
                     "LEFT JOIN disciplinas d ON p.disciplina_id = d.id " .
                     "LEFT JOIN subdisciplinas sd ON p.subdisciplina_id = sd.id ";
            if(!empty($selected)) {
                $query .= " WHERE r.proyecto_id IN (" . join(',', $selected) . ")";
            }
            if(Reporte::hasColumn('created_at')) {
                $query .= " ORDER BY r.created_at DESC";
            }
            $reportes = Reporte::consultaSQL($query);
        } else {
            $query = "SELECT r.*, perm.permiso AS permiso_tipo, d.nombre AS disciplina_nombre, sd.nombre AS subdisciplina_nombre " .
                     "FROM reportes r " .
                     "JOIN permisos perm ON r.proyecto_id = perm.proyecto_id " .
                     "JOIN proyectos p ON r.proyecto_id = p.id " .
                     "LEFT JOIN disciplinas d ON p.disciplina_id = d.id " .
                     "LEFT JOIN subdisciplinas sd ON p.subdisciplina_id = sd.id " .
                     "WHERE perm.cliente_id = " . intval($idUsuario);
            if(!empty($selected)) {
                $query .= " AND r.proyecto_id IN (" . join(',', $selected) . ")";
            }
            if(Reporte::hasColumn('created_at')) {
                $query .= " ORDER BY r.created_at DESC";
            }
            $reportes = Reporte::consultaSQL($query);

            // comprobar si hay proyectos con permiso editar
            $q2 = "SELECT 1 FROM permisos WHERE cliente_id=" . intval($idUsuario) . " AND permiso='editar' LIMIT 1";
            $tmp = Permiso::consultaSQL($q2);
            if(!empty($tmp)) {
                $canCreate = true;
            }
        }
        $router->render('reportes/index', [
            'reportes' => $reportes,
            'canCreate' => $canCreate,
            'filterProjects' => $filterProjects,
            'selectedProjects' => $selected
        ]);
    }

    public static function galeria(Router $router) {
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

        // construir consulta similar a index pero sólo para reportes con imágenes
        if(esAdmin()) {
            $query = "SELECT r.*, d.nombre AS disciplina_nombre, sd.nombre AS subdisciplina_nombre " .
                     "FROM reportes r " .
                     "JOIN proyectos p ON r.proyecto_id = p.id " .
                     "LEFT JOIN disciplinas d ON p.disciplina_id = d.id " .
                     "LEFT JOIN subdisciplinas sd ON p.subdisciplina_id = sd.id ";
            $condiciones = [];
            // sólo reportes con imágenes almacenadas
            $condiciones[] = "r.imagenes IS NOT NULL AND r.imagenes <> '[]'";
            if(!empty($selected)) {
                $condiciones[] = "r.proyecto_id IN (" . join(',', $selected) . ")";
            }
            if(!empty($condiciones)) {
                $query .= " WHERE " . join(' AND ', $condiciones);
            }
            if(Reporte::hasColumn('created_at')) {
                $query .= " ORDER BY r.created_at DESC";
            }
            $reportes = Reporte::consultaSQL($query);
        } else {
            $query = "SELECT r.*, perm.permiso AS permiso_tipo, d.nombre AS disciplina_nombre, sd.nombre AS subdisciplina_nombre " .
                     "FROM reportes r " .
                     "JOIN permisos perm ON r.proyecto_id = perm.proyecto_id " .
                     "JOIN proyectos p ON r.proyecto_id = p.id " .
                     "LEFT JOIN disciplinas d ON p.disciplina_id = d.id " .
                     "LEFT JOIN subdisciplinas sd ON p.subdisciplina_id = sd.id " .
                     "WHERE perm.cliente_id = " . intval($idUsuario) .
                     " AND r.imagenes IS NOT NULL AND r.imagenes <> '[]'";
            if(!empty($selected)) {
                $query .= " AND r.proyecto_id IN (" . join(',', $selected) . ")";
            }
            if(Reporte::hasColumn('created_at')) {
                $query .= " ORDER BY r.created_at DESC";
            }
            $reportes = Reporte::consultaSQL($query);
        }

        $router->render('reportes/galeria', [
            'reportes' => $reportes,
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
            // solo proyectos con permiso editar para evitar que clientes con solo leer
            // puedan acceder al formulario
            $query = "SELECT p.* FROM permisos perm " .
                     "JOIN proyectos p ON perm.proyecto_id = p.id " .
                     "WHERE perm.cliente_id = " . intval($idUsuario) .
                     " AND perm.permiso = 'editar'";
            $proyectos = Proyecto::consultaSQL($query);
        }

        $reporte = new Reporte;
        // garantizar que imagenes contenga JSON válido aun cuando no se suban archivos
        if(!$reporte->imagenes) {
            $reporte->imagenes = json_encode([]);
        }
        // si llega proyecto_id en query, lo usamos para selección inicial
        if(isset($_GET['proyecto_id'])) {
            $pid = intval($_GET['proyecto_id']);
            // sólo asignar si está en la lista de proyectos permitidos
            foreach($proyectos as $p) {
                if($p->id === $pid) {
                    $reporte->proyecto_id = $pid;
                    break;
                }
            }
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            /** @var Reporte $reporte */
            /** @var Reporte $reporte */
            $reporte = new Reporte($_POST);

            // comprobar permiso sobre proyecto seleccionado (solo editar)
            if(!esAdmin()) {
                $allowedIds = array_map(fn($pr) => $pr->id, $proyectos);
                if(!in_array($reporte->proyecto_id, $allowedIds, true)) {
                    $errores[] = 'No tiene permiso para crear un reporte en el proyecto seleccionado.';
                }
            }

            if(!empty($_FILES['imagenes']['tmp_name'][0])) {
                // create folder if missing
                if(!is_dir(__DIR__ . '/../public/image/')) {
                    mkdir(__DIR__ . '/../public/image/', 0755, true);
                }

                $imgs = [];
                foreach($_FILES['imagenes']['tmp_name'] as $key => $tmp) {
                    $nombre = uniqid() . '-' . basename($_FILES['imagenes']['name'][$key]);
                    $dest = __DIR__ . '/../public/image/' . $nombre;
                    // process with Intervention Image
                    /** @noinspection PhpUndefinedClassInspection */
                    /** @var \Intervention\Image\Image $image */
                    $manager = ImageManager::gd();
                    $image = $manager->read($tmp);
                    // enforce max 600x600 using cover
                    $image->cover(600, 600);
                    $image->save($dest);
                    $imgs[] = $nombre;
                }
                $reporte->imagenes = json_encode($imgs);
            }

            $errores = $reporte->validarErrores();
            if(empty($errores)) {
                $reporte->guardar();
                establecerMensajeFlash('Reporte creado correctamente');
                header('Location: /reportes');
                return; // evitar que se renderice el formulario y consuma el mensaje
            }
        }

        $router->render('reportes/crear', [
            'proyectos' => $proyectos,
            'reporte' => $reporte
        ]);
    }

    public static function eliminar() {
        asegurarSesion();
        $idUsuario = idActual();
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if(!$id) {
                header('Location: /reportes');
                return;
            }
            /** @var Reporte|null $reporte */
            $reporte = Reporte::find($id);
            if(!$reporte) {
                header('Location: /reportes');
                return;
            }
            /** @var Reporte $reporte */

            // before deleting record, remove any stored images
            if($reporte->imagenes) {
                $imgs = json_decode($reporte->imagenes, true);
                foreach($imgs as $img) {
                    $path = __DIR__ . '/../public/image/' . $img;
                    if(is_file($path)) {
                        unlink($path);
                    }
                }
            }

            if(esAdmin()) {
                $reporte->eliminar();
                establecerMensajeFlash('Reporte eliminado');
            } else {
                // verify user has 'editar' permission on project
                $query = "SELECT * FROM permisos WHERE cliente_id=" . intval($idUsuario) . " AND proyecto_id=" . intval($reporte->proyecto_id) . " LIMIT 1";
                $perm = Permiso::consultaSQL($query);
                if(!empty($perm) && $perm[0]->permiso === 'editar') {
                    $reporte->eliminar();
                    establecerMensajeFlash('Reporte eliminado');
                } else {
                    establecerMensajeFlash('No tienes permiso para eliminar este reporte', 'error');
                }
            }
            header('Location: /reportes');
        }
    }

    public static function editar(Router $router) {
        asegurarSesion();
        $idUsuario = idActual();
        // aceptar id por GET cuando se carga el formulario o por POST tras el submit
        $id = $_GET['id'] ?? $_POST['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if(!$id) {
            header('Location: /reportes');
            return;
        }
        $reporte = Reporte::find($id);
        if(!$reporte) {
            header('Location: /reportes');
            return;
        }
        /** @var Reporte $reporte */
// permission check for client; require permiso 'editar' to modify
        if(!esAdmin()) {
            $query = "SELECT * FROM permisos WHERE cliente_id=" . intval($idUsuario) . " AND proyecto_id=" . intval($reporte->proyecto_id) . " LIMIT 1";
            $perm = Permiso::consultaSQL($query);
            if(empty($perm) || $perm[0]->permiso !== 'editar') {
                // no permiso o sólo lectura
                establecerMensajeFlash('No tienes permiso para modificar este reporte', 'error');
                header('Location: /reportes');
                return;
            }
        }

        $errores = Reporte::getErrores();
        if(esAdmin()) {
            $proyectos = Proyecto::all();
        } else {
            // sólo proyectos con permiso de edición (deberíamos haber bloqueado el acceso
            // si ya no existiera, pero así evitamos mostrarlos en el selector)
            $query = "SELECT p.* FROM permisos perm " .
                     "JOIN proyectos p ON perm.proyecto_id = p.id " .
                     "WHERE perm.cliente_id = " . intval($idUsuario) .
                     " AND perm.permiso = 'editar'";
            $proyectos = Proyecto::consultaSQL($query);
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            /** @var Reporte $reporte */
            /** @var Reporte $reporte */
            $reporte->sincronizar($_POST);

            if(!empty($_FILES['imagenes']['tmp_name'][0])) {
                // delete any previous files
                if($reporte->imagenes) {
                    $viejas = json_decode($reporte->imagenes, true);
                    foreach($viejas as $img) {
                        $path = __DIR__ . '/../public/image/' . $img;
                        if(is_file($path)) {
                            unlink($path);
                        }
                    }
                }

                if(!is_dir(__DIR__ . '/../public/image/')) {
                    mkdir(__DIR__ . '/../public/image/', 0755, true);
                }

                $imgs = [];
                foreach($_FILES['imagenes']['tmp_name'] as $key => $tmp) {
                    $nombre = uniqid() . '-' . basename($_FILES['imagenes']['name'][$key]);
                    $dest = __DIR__ . '/../public/image/' . $nombre;
                    /** @noinspection PhpUndefinedClassInspection */
                    /** @var \Intervention\Image\Image $image */
                    $manager = ImageManager::gd();
                    $image = $manager->read($tmp);
                    // enforce max 600x600 using cover
                    $image->cover(600, 600);
                    $image->save($dest);
                    $imgs[] = $nombre;
                }
                $reporte->imagenes = json_encode($imgs);
            }

            $errores = $reporte->validarErrores();
            if(empty($errores)) {
                $reporte->guardar();
                establecerMensajeFlash('Reporte actualizado correctamente');
                header('Location: /reportes');
                return; // detener aquí para que el flash no se consuma por el render
            }
        }

        $router->render('reportes/editar', [
            'reporte' => $reporte,
            'errores' => $errores,
            'proyectos' => $proyectos
        ]);
    }

    public static function detalle(Router $router) {
        asegurarSesion();
        $idUsuario = idActual();
        $id = $_GET['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if(!$id) {
            header('Location: /reportes');
            return;
        }
        $reporte = Reporte::find($id);
        if(!$reporte) {
            header('Location: /reportes');
            return;
        }
        /** @var Reporte $reporte */
        if(!esAdmin()) {
            $query = "SELECT * FROM permisos WHERE cliente_id=" . intval($idUsuario) .
                     " AND proyecto_id=" . intval($reporte->proyecto_id) . " LIMIT 1";
            $perm = Permiso::consultaSQL($query);
            if(empty($perm)) {
                header('Location: /reportes');
                return;
            }
        }
        $router->render('reportes/detalle', [
            'reporte' => $reporte
        ]);
    }
}
