<?php

namespace MVC;

class Router {

  public $rutasGet = [];
  public $rutasPost = [];

  public function get($url, $funcion) {
    $this->rutasGet[$url] = $funcion;
  }

  public function post($url, $funcion) {
    $this->rutasPost[$url] = $funcion;
  }

  public function comprobarRutas() {
    session_start();
    $ingreso = $_SESSION['login'] ?? null;

    // rutas que requieren login (y en su mayoría son de administración)
    $rutasProtegidas = [
      '/admin',
      '/clientes', '/clientes/crear', '/clientes/actualizar', '/clientes/eliminar',
      '/disciplinas', '/disciplinas/crear', '/disciplinas/actualizar', '/disciplinas/eliminar',
      '/subdisciplinas', '/subdisciplinas/crear', '/subdisciplinas/actualizar', '/subdisciplinas/eliminar',
      '/proyectos', '/proyectos/crear', '/proyectos/actualizar', '/proyectos/eliminar',
      '/empresas',
      '/permisos', '/permisos/crear', '/permisos/actualizar', '/permisos/eliminar',
      // '/reportes*' no se protege aquí para permitir acceso de clientes; control interno se encarga
    ];

    // obtener sólo la ruta, sin query string
    $urlActual = $_SERVER['PATH_INFO'] ?? $_SERVER['ORIG_PATH_INFO'] ?? $_SERVER['REQUEST_URI'] ?? '/';
    // eliminar parámetros ?
    $urlActual = parse_url($urlActual, PHP_URL_PATH) ?: '/';
    $metodo = $_SERVER['REQUEST_METHOD'];

    if($metodo === 'GET') {
      $funcion = $this->rutasGet[$urlActual] ?? null;
    } else if ($metodo === 'POST') {
      $funcion = $this->rutasPost[$urlActual] ?? null;
    }

    if(in_array($urlActual, $rutasProtegidas)) {
      if(!$ingreso) {
        header('location: /login');
      } else {
        // solo administradores pueden acceder a estas rutas
        if(function_exists('isAdmin') && !isAdmin()) {
          header('location: /');
        }
      }
    }

    if($funcion) {
      call_user_func($funcion, $this);
    } else {
      echo "Pagina no encontrada";
    }
  }

  public function render($view, $datos = []) {
    foreach($datos as $key => $value) {
      $$key = $value;
    }

    // construir ruta de la vista. Si el parámetro ya incluye 'views/' al inicio no lo duplicamos.
    $normalized = ltrim($view, '/');
    if(strpos($normalized, 'views/') === 0) {
      $rutaView = __DIR__ . '/' . $normalized;
    } else {
      $rutaView = __DIR__ . '/views/' . $normalized;
    }
    if(strpos($rutaView, '.php') === false) {
      $rutaView .= '.php';
    }

    ob_start();
    include_once $rutaView;
    $contenido = ob_get_clean();
    include_once __DIR__ . '/views/layout.php';
  }
}
