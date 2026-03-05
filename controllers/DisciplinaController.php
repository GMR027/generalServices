<?php

namespace Controllers;

use MVC\Router;
use Model\Disciplina;

class DisciplinaController {
    public static function index(Router $router) {
        if(!\esAdmin()) {
            header('Location: /');
            return;
        }
        $disciplinas = Disciplina::all();
        $router->render('disciplinas/index', [
            'disciplinas' => $disciplinas
        ]);
    }

    public static function crear(Router $router) {
        if(!\esAdmin()) {
            header('Location: /');
            return;
        }
        $disciplina = new Disciplina;
        $errores = Disciplina::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $disciplina = new Disciplina($_POST['disciplina']);
            $errores = $disciplina->validarErrores();
            if(empty($errores)) {
                $disciplina->guardar();
                header('Location: /disciplinas?resultado=1');
            }
        }

        $router->render('disciplinas/crear', [
            'disciplina' => $disciplina,
            'errores' => $errores
        ]);
    }

    public static function actualizar(Router $router) {
        if(!\esAdmin()) {
            header('Location: /');
            return;
        }
        $id = validarId('/disciplinas');
        $disciplina = Disciplina::find($id);
        $errores = Disciplina::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $disciplina->sincronizar($_POST['disciplina']);
            $errores = $disciplina->validarErrores();
            if(empty($errores)) {
                $disciplina->guardar();
                header('Location: /disciplinas?resultado=2');
            }
        }

        $router->render('disciplinas/actualizar', [
            'disciplina' => $disciplina,
            'errores' => $errores
        ]);
    }

    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if($id) {
                $disciplina = Disciplina::find($id);
                $disciplina->eliminar();
                header('Location: /disciplinas?resultado=3');
            }
        }
    }
}
