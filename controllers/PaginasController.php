<?php

namespace Controllers;

use MVC\Router;
use Controllers\AuthController;

class PaginasController {
    public static function index(Router $router) {
        // si no hay usuario autenticado delegamos el login al AuthController
        if(!usuarioActual()) {
            AuthController::login($router);
            return;
        }
        $router->render('paginas/index');
    }



}
