<?php

namespace Controllers;

use MVC\Router;
use Model\Proyecto;
use Model\Disciplina;
use Model\Subdisciplina;
use Model\Permiso;

class ProyectoController {
    public static function index(Router $router) {
        $proyectos = Proyecto::all();
        // maps for display
        $disciplinas = Disciplina::all();
        $discipMap = [];
        foreach($disciplinas as $d){ $discipMap[$d->id] = $d->nombre; }
        $subdisciplinas = Subdisciplina::all();
        $subMap = [];
        foreach($subdisciplinas as $s){ $subMap[$s->id] = $s->nombre; }
        $router->render('proyectos/index', [
            'proyectos' => $proyectos,
            'discipMap' => $discipMap,
            'subMap' => $subMap
        ]);
    }

    public static function crear(Router $router) {
        if(!\esAdmin()) {
            header('Location: /');
            return;
        }
        $proyecto = new Proyecto;
        $disciplinas = Disciplina::all();
        $subdisciplinas = Subdisciplina::all();
        $errores = Proyecto::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto = new Proyecto($_POST['proyecto']);
            $errores = $proyecto->validarErrores();
            if(empty($errores)) {
                $proyecto->guardar();
                header('Location: /proyectos?resultado=1');
            }
        }

        $router->render('proyectos/crear', [
            'proyecto' => $proyecto,
            'disciplinas' => $disciplinas,
            'subdisciplinas' => $subdisciplinas,
            'errores' => $errores
        ]);
    }

    public static function actualizar(Router $router) {
        if(!\esAdmin()) {
            header('Location: /');
            return;
        }
        $id = validarId('/proyectos');
        $proyecto = Proyecto::find($id);
        $disciplinas = Disciplina::all();
        $subdisciplinas = Subdisciplina::all();
        $errores = Proyecto::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto->sincronizar($_POST['proyecto']);
            $errores = $proyecto->validarErrores();
            if(empty($errores)) {
                $proyecto->guardar();
                header('Location: /proyectos?resultado=2');
            }
        }

        $router->render('proyectos/actualizar', [
            'proyecto' => $proyecto,
            'disciplinas' => $disciplinas,
            'subdisciplinas' => $subdisciplinas,
            'errores' => $errores
        ]);
    }

    public static function eliminar() {
        if(!\esAdmin()) {
            header('Location: /');
            return;
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if($id) {
                // comprobar relaciones: permisos, minutas, reportes, bitacora etc.
                $rels = [];
                $check = Permiso::consultaSQL("SELECT 1 FROM permisos WHERE proyecto_id=" . intval($id) . " LIMIT 1");
                if(!empty($check)) { $rels[] = 'permisos'; }
                // minutas
                $m = Proyecto::consultaSQL("SELECT 1 FROM minutas WHERE proyecto_id=" . intval($id) . " LIMIT 1");
                if(!empty($m)) { $rels[] = 'minutas'; }
                // reportes
                $r = Proyecto::consultaSQL("SELECT 1 FROM reportes WHERE proyecto_id=" . intval($id) . " LIMIT 1");
                if(!empty($r)) { $rels[] = 'reportes'; }
                // bitacora
                $b = Proyecto::consultaSQL("SELECT 1 FROM bitacoras WHERE proyecto_id=" . intval($id) . " LIMIT 1");
                if(!empty($b)) { $rels[] = 'bitacora'; }

                if(!empty($rels)) {
                    $lista = implode(', ', $rels);
                    establecerMensajeFlash('No se puede eliminar proyecto porque existen registros en: ' . $lista, 'error');
                    header('Location: /proyectos');
                    return;
                }

                $proyecto = Proyecto::find($id);
                if($proyecto) {
                    $proyecto->eliminar();
                    header('Location: /proyectos?resultado=3');
                }
            }
        }
    }
}
