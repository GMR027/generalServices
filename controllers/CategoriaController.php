<?php

namespace Controllers;

use MVC\Router;
use Model\Categoria;

class CategoriaController {
    public static function index(Router $router) {
        if(!\esAdmin()) {
            header('Location: /');
            return;
        }
        $categorias = Categoria::all();
        $router->render('categorias/index', [
            'categorias' => $categorias
        ]);
    }

    public static function crear(Router $router) {
        if(!\esAdmin()) {
            header('Location: /');
            return;
        }
        $categoria = new Categoria;
        $errores = Categoria::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoria = new Categoria($_POST['categoria']);
            $errores = $categoria->validarErrores();
            if(empty($errores)) {
                $categoria->guardar();
                header('Location: /categorias?resultado=1');
                return;
            }
        }

        $router->render('categorias/crear', [
            'categoria' => $categoria,
            'errores' => $errores
        ]);
    }

    public static function actualizar(Router $router) {
        if(!\esAdmin()) {
            header('Location: /');
            return;
        }
        $id = $_GET['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if(!$id) {
            header('Location: /categorias');
            return;
        }
        $categoria = Categoria::find($id);
        if(!$categoria) {
            header('Location: /categorias');
            return;
        }
        $errores = Categoria::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoria->sincronizar($_POST['categoria']);
            $errores = $categoria->validarErrores();
            if(empty($errores)) {
                $categoria->guardar();
                header('Location: /categorias?resultado=2');
                return;
            }
        }

        $router->render('categorias/actualizar', [
            'categoria' => $categoria,
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
                $cat = Categoria::find($id);
                if($cat) {
                    $cat->eliminar();
                    establecerMensajeFlash('Categoría eliminada');
                }
            }
            header('Location: /categorias');
        }
    }
}
