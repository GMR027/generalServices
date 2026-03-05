<?php

namespace Controllers;

use MVC\Router;
use Model\Reporte;
use Model\Proyecto;

class AdminController {
    public static function index(Router $router) {
        // mostrar listado de reportes con filtro por proyecto
        asegurarSesion();

        // proyectos para filtro
        $filterProjects = Proyecto::all();
        $selected = [];
        if(isset($_GET['proyecto_id'])) {
            $selected = array_map('intval', (array)$_GET['proyecto_id']);
        }

        $query = "SELECT r.*, p.nombre AS proyecto_nombre, d.nombre AS disciplina_nombre, sd.nombre AS subdisciplina_nombre " .
                 "FROM reportes r " .
                 "JOIN proyectos p ON r.proyecto_id = p.id " .
                 "LEFT JOIN disciplinas d ON p.disciplina_id = d.id " .
                 "LEFT JOIN subdisciplinas sd ON p.subdisciplina_id = sd.id";
        if(!empty($selected)) {
            $query .= " WHERE r.proyecto_id IN (" . join(',', $selected) . ")";
        }
        if(Reporte::hasColumn('created_at')) {
            $query .= " ORDER BY r.created_at DESC";
        }
        $reportes = Reporte::consultaSQL($query);

        $router->render('admin/index', [
            'reportes' => $reportes,
            'filterProjects' => $filterProjects,
            'selectedProjects' => $selected
        ]);
    }
}
