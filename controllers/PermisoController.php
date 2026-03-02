<?php

namespace Controllers;

use MVC\Router;
use Model\Permiso;
use Model\Usuario;
use Model\Proyecto;
use Model\Empresa;

class PermisoController {
    public static function index(Router $router) {
        if(!\isAdmin()) {
            header('Location: /');
            return;
        }
        // obtener permisos junto con nombres asociados
        $query = "SELECT perm.*, u.nombre AS cliente_nombre, ";
        $query .= "p.nombre AS proyecto_nombre, e.nombre AS empresa_nombre ";
        $query .= "FROM permisos perm ";
        $query .= "JOIN usuarios u ON perm.cliente_id = u.id ";
        $query .= "JOIN proyectos p ON perm.proyecto_id = p.id ";
        $query .= "JOIN empresas e ON perm.empresa_id = e.id";
        $permisos = Permiso::consultaSQL($query);
        $router->render('permisos/index', [
            'permisos' => $permisos
        ]);
    }

    public static function crear(Router $router) {
        if(!\isAdmin()) {
            header('Location: /');
            return;
        }
        /** @var Permiso $permiso */
        $permiso = new Permiso;
        $errores = Permiso::getErrores();
        $clientes = Usuario::all();
        $proyectos = Proyecto::all();
        $empresas = Empresa::all();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ids = $_POST['permisos']['proyecto_id'] ?? [];
            // asegurar array incluso si sólo viene uno o viene vacío
            if(!is_array($ids)) {
                $ids = $ids ? [$ids] : [];
            }
            $permisoTipo = $_POST['permisos']['permiso'] ?? 'leer';
            $cliente = $_POST['permisos']['cliente_id'] ?? null;
            $empresa = $_POST['permisos']['empresa_id'] ?? null;

            // create multiple rows (uno por proyecto seleccionado)
            foreach($ids as $pid) {
                $p = new Permiso([
                    'cliente_id' => $cliente,
                    'proyecto_id' => $pid,
                    'empresa_id' => $empresa,
                    'permiso' => $permisoTipo
                ]);
                $errs = $p->validarErrores();
                if(empty($errs)) {
                    $p->guardar();
                }
            }
            setFlashMessage('Permisos asignados correctamente');
            header('Location: /permisos');
        }

        $router->render('permisos/crear', [
            'permiso' => $permiso,
            'errores' => $errores,
            'clientes' => $clientes,
            'proyectos' => $proyectos,
            'empresas' => $empresas
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
                $perm = Permiso::find($id);
                $perm->eliminar();
                setFlashMessage('Permiso eliminado');
                header('Location: /permisos');
            }
        }
    }

    public static function actualizar(Router $router) {
        if(!\isAdmin()) {
            header('Location: /');
            return;
        }

        $id = $_GET['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if(!$id) {
            header('Location: /permisos');
            return;
        }

        /** @var Permiso|null $permiso */
        $permiso = Permiso::find($id);
        if(!$permiso) {
            header('Location: /permisos');
            return;
        }

        $errores = Permiso::getErrores();
        $clientes = Usuario::all();
        $proyectos = Proyecto::all();
        $empresas = Empresa::all();

        // prepararemos un arreglo con el proyecto actual para la vista
        $selectedProjectIds = [$permiso->proyecto_id];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ids = $_POST['permisos']['proyecto_id'] ?? [];
            if(!is_array($ids)) {
                $ids = $ids ? [$ids] : [];
            }
            $permisoTipo = $_POST['permisos']['permiso'] ?? 'leer';
            $cliente = $_POST['permisos']['cliente_id'] ?? null;
            $empresa = $_POST['permisos']['empresa_id'] ?? null;

            // si sólo hay un proyecto y es el mismo que el registro actual, podemos actualizarlo en lugar de sustituir
            if(count($ids) === 1 && $ids[0] == $permiso->proyecto_id) {
                // actualizar solamente el permiso/empresa, no cambiar el proyecto
                $args = $_POST['permisos'];
                if(isset($args['proyecto_id']) && is_array($args['proyecto_id'])) {
                    // evitar pasar array al ActiveRecord
                    $args['proyecto_id'] = $args['proyecto_id'][0];
                }
                $permiso->sincronizar($args);
                $errores = $permiso->validarErrores();
                if(empty($errores)) {
                    $permiso->guardar();
                    setFlashMessage('Permiso actualizado');
                    header('Location: /permisos');
                    return;
                }
            } else {
                // borramos el permiso original y creamos nuevos registros según los proyectos seleccionados
                $permiso->eliminar();
                foreach($ids as $pid) {
                    $p = new Permiso([
                        'cliente_id' => $cliente,
                        'proyecto_id' => $pid,
                        'empresa_id' => $empresa,
                        'permiso' => $permisoTipo
                    ]);
                    $errs = $p->validarErrores();
                    if(empty($errs)) {
                        $p->guardar();
                    }
                }
                setFlashMessage('Permisos actualizados');
                header('Location: /permisos');
                return;
            }
        }

        $router->render('permisos/actualizar', [
            'permiso' => $permiso,
            'errores' => $errores,
            'clientes' => $clientes,
            'proyectos' => $proyectos,
            'empresas' => $empresas,
            'selectedProjectIds' => $selectedProjectIds
        ]);
    }

    // muestra proyectos asignados al cliente
    public static function misProyectos(Router $router) {
        ensureSession();
        $clienteId = idActual();
        if(!$clienteId) {
            header('Location: /login');
            return;
        }
        // obtener permisos del cliente junto con nombres de proyecto y empresa
        $query = "SELECT p.id AS proyecto_id, p.nombre AS proyecto_nombre, ";
        $query .= "d.nombre AS disciplina_nombre, sd.nombre AS subdisciplina_nombre, ";
        $query .= "perm.permiso, perm.empresa_id, e.nombre AS empresa_nombre ";
        $query .= "FROM permisos perm ";
        $query .= "JOIN proyectos p ON perm.proyecto_id = p.id ";
        $query .= "LEFT JOIN disciplinas d ON p.disciplina_id = d.id ";
        $query .= "LEFT JOIN subdisciplinas sd ON p.subdisciplina_id = sd.id ";
        $query .= "JOIN empresas e ON perm.empresa_id = e.id ";
        $query .= "WHERE perm.cliente_id = " . intval($clienteId);
        $resultado = Permiso::consultaSQL($query);
        $router->render('permisos/misProyectos', [
            'permisos' => $resultado
        ]);
    }
}
