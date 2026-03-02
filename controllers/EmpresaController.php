<?php

namespace Controllers;

use MVC\Router;
use Model\Empresa;
use Intervention\Image\ImageManager; // reuse Intervention for logos

class EmpresaController {
    public static function index(Router $router) {
        if(!\isAdmin()) {
            header('Location: /');
            return;
        }
        $empresas = Empresa::all();
        $router->render('empresas/index', [
            'empresas' => $empresas
        ]);
    }

    public static function crear(Router $router) {
        if(!\isAdmin()) {
            header('Location: /');
            return;
        }
        $empresa = new Empresa;
        $errores = Empresa::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $empresa = new Empresa($_POST['empresa']);
            // manejar logo
            if($_FILES['logo']['tmp_name']) {
                // borrar antiguo si existe
                if($empresa->logo) {
                    $pathOld = __DIR__ . '/../public/image/' . basename($empresa->logo);
                    if(is_file($pathOld)) unlink($pathOld);
                }

                $nombreArchivo = md5(uniqid()) . '.' . pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
                $destino = __DIR__ . '/../public/image/' . $nombreArchivo;
                if(!is_dir(__DIR__ . '/../public/image/')) {
                    mkdir(__DIR__ . '/../public/image/', 0755, true);
                }
                // procesar con Intervention (resize opcional)
                /** @noinspection PhpUndefinedClassInspection */
                /** @var \Intervention\Image\Image $img */
                $manager = ImageManager::gd();
                $img = $manager->read($_FILES['logo']['tmp_name']);
                $img->resize(800, null, function($constraint){
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $img->save($destino);

                // sólo guardar nombre en DB
                $empresa->logo = $nombreArchivo;
            }
            $errores = $empresa->validarErrores();
            if(empty($errores)) {
                $empresa->guardar();
                header('Location: /empresas?resultado=1');
            }
        }

        $router->render('empresas/crear', [
            'empresa' => $empresa,
            'errores' => $errores
        ]);
    }

    public static function actualizar(Router $router) {
        if(!\isAdmin()) {
            header('Location: /');
            return;
        }
        $id = validarId('/empresas');
        $empresa = Empresa::find($id);
        $errores = Empresa::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $empresa->sincronizar($_POST['empresa']);
            // manejar logo si se sube nuevo
            if($_FILES['logo']['tmp_name']) {
                // borrar previo en disco
                if($empresa->logo) {
                    $pathOld = __DIR__ . '/../public/image/' . basename($empresa->logo);
                    if(is_file($pathOld)) unlink($pathOld);
                }

                $nombreArchivo = md5(uniqid()) . '.' . pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
                $destino = __DIR__ . '/../public/image/' . $nombreArchivo;
                if(!is_dir(__DIR__ . '/../public/image/')) {
                    mkdir(__DIR__ . '/../public/image/', 0755, true);
                }
                /** @noinspection PhpUndefinedClassInspection */
                /** @var \Intervention\Image\Image $img */
                $manager = ImageManager::gd();
                $img = $manager->read($_FILES['logo']['tmp_name']);
                $img->resize(800, null, function($constraint){
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $img->save($destino);

                $empresa->logo = $nombreArchivo;
            }
            $errores = $empresa->validarErrores();
            if(empty($errores)) {
                $empresa->guardar();
                header('Location: /empresas?resultado=2');
            }
        }

        $router->render('empresas/actualizar', [
            'empresa' => $empresa,
            'errores' => $errores
        ]);
    }

    public static function eliminar() {
        if(!\isAdmin()) {
            header('Location: /');
            return;
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if($id) {
                $empresa = Empresa::find($id);
                // borrar logo asociado
                if($empresa->logo) {
                    $pathOld = __DIR__ . '/../public/image/' . basename($empresa->logo);
                    if(is_file($pathOld)) unlink($pathOld);
                }
                $empresa->eliminar();
                header('Location: /empresas?resultado=3');
            }
        }
    }
}
