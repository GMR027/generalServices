<?php

namespace Controllers;

use MVC\Router;
use Model\Usuario;

class UsuarioController {
    public static function index(Router $router) {
        if(!\isAdmin()) {
            header('Location: /');
            return;
        }
        $usuarios = Usuario::all();
        $router->render('usuarios/index', [
            'usuarios' => $usuarios
        ]);
    }

    public static function crear(Router $router) {
        if(!\isAdmin()) {
            header('Location: /');
            return;
        }
        $usuario = new Usuario;
        $errores = Usuario::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST['usuario']);
            $usuario->hashPassword();
            $errores = $usuario->validarErrores();
            if(empty($errores)) {
                $usuario->guardar();
                header('Location: /clientes?resultado=1');
            }
        }

        $router->render('usuarios/crear', [
            'usuario' => $usuario,
            'errores' => $errores
        ]);
    }

    public static function actualizar(Router $router) {
        if(!\isAdmin()) {
            header('Location: /');
            return;
        }
        $id = validarId('/clientes');
        $usuario = Usuario::find($id);
        $errores = Usuario::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $args = $_POST['usuario'];
            // si cambian password lo hasheamos
            if(!empty($args['contrasena'])) {
                $usuario->contrasena = $args['contrasena'];
                $usuario->hashPassword();
            }
            unset($args['contrasena']);
            $usuario->sincronizar($args);
            $errores = $usuario->validarErrores();
            if(empty($errores)) {
                $usuario->guardar();
                header('Location: /clientes?resultado=2');
            }
        }

        $router->render('usuarios/actualizar', [
            'usuario' => $usuario,
            'errores' => $errores
        ]);
    }

    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if($id) {
                $usuario = Usuario::find($id);
                $usuario->eliminar();
                header('Location: /clientes?resultado=3');
            }
        }
    }
}
