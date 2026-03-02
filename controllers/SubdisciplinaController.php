<?php

namespace Controllers;

use MVC\Router;
use Model\Subdisciplina;
use Model\Disciplina;

class SubdisciplinaController {
    public static function index(Router $router) {
        if(!\isAdmin()) {
            header('Location: /');
            return;
        }
        $subdisciplinas = Subdisciplina::all();
        $router->render('subdisciplinas/index', [
            'subdisciplinas' => $subdisciplinas
        ]);
    }

    public static function crear(Router $router) {
        if(!\isAdmin()) {
            header('Location: /');
            return;
        }
        $sub = new Subdisciplina;
        $disciplinas = Disciplina::all();
        $errores = Subdisciplina::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sub = new Subdisciplina($_POST['subdisciplina']);
            $errores = $sub->validarErrores();
            if(empty($errores)) {
                $sub->guardar();
                header('Location: /subdisciplinas?resultado=1');
            }
        }

        $router->render('subdisciplinas/crear', [
            'subdisciplina' => $sub,
            'disciplinas' => $disciplinas,
            'errores' => $errores
        ]);
    }

    public static function actualizar(Router $router) {
        if(!\isAdmin()) {
            header('Location: /');
            return;
        }
        $id = validarId('/subdisciplinas');
        $sub = Subdisciplina::find($id);
        $disciplinas = Disciplina::all();
        $errores = Subdisciplina::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sub->sincronizar($_POST['subdisciplina']);
            $errores = $sub->validarErrores();
            if(empty($errores)) {
                $sub->guardar();
                header('Location: /subdisciplinas?resultado=2');
            }
        }

        $router->render('subdisciplinas/actualizar', [
            'subdisciplina' => $sub,
            'disciplinas' => $disciplinas,
            'errores' => $errores
        ]);
    }

    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if($id) {
                $sub = Subdisciplina::find($id);
                $sub->eliminar();
                header('Location: /subdisciplinas?resultado=3');
            }
        }
    }
}
