<?php

namespace Controllers;

use MVC\Router;
use Model\Usuario;
use Model\Permiso;

class UsuarioController {
    public static function index(Router $router) {
        if(!\esAdmin()) {
            header('Location: /');
            return;
        }
        $usuarios = Usuario::all();
        $router->render('usuarios/index', [
            'usuarios' => $usuarios
        ]);
    }

    public static function crear(Router $router) {
        if(!\esAdmin()) {
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
        if(!\esAdmin()) {
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
                // verificar dependencias en permisos
                $hay = Permiso::consultaSQL("SELECT 1 FROM permisos WHERE cliente_id=" . intval($id) . " LIMIT 1");
                if(!empty($hay)) {
                    establecerMensajeFlash('No se puede eliminar usuario con permisos asignados', 'error');
                    header('Location: /clientes');
                    return;
                }

                $usuario = Usuario::find($id);
                if($usuario) {
                    $usuario->eliminar();
                    header('Location: /clientes?resultado=3');
                }
            }
        }
    }
}
