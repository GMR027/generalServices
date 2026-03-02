<?php

namespace Controllers;

use MVC\Router;
use Model\Usuario;

class AuthController {
    public static function login(Router $router) {
        $errores = [];
        $auth = new Usuario();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST['login']);
            $errores = $auth->validarLogin();

            if(empty($errores)) {
                $usuarioExiste = $auth->existeUsuario();
                if(!$usuarioExiste) {
                    $errores[] = 'Usuario no existe';
                } else {
                    // verificar contraseña
                    $hash = $usuarioExiste['contrasena'] ?? '';
                    error_log('Auth: hash recuperado = ' . $hash);
                    $verificado = $auth->verificarPassword($usuarioExiste);
                    if($verificado) {
                        // llenar propiedades necesarias para sesión
                        $auth->id = $usuarioExiste['id'];
                        $auth->nombre = $usuarioExiste['nombre'];
                        $auth->correo = $usuarioExiste['correo'];
                        $auth->rol = $usuarioExiste['rol'];
                                                $auth->autenticar();
                    } else {
                        $errores[] = 'Contraseña incorrecta';
                    }
                }
            }
        }

        $router->render('auth/login', [
            'errores' => $errores
        ]);
    }

    public static function logout() {
        session_start();
        $_SESSION = [];
        header('Location: /');
    }
}
