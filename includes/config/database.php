<?php

function conectarBD(): mysqli {
    $host = $_ENV['ENV'] ?? 'localhost';
    if ($host === 'production') {
        $db = new mysqli(
            $_ENV['DB_HOST'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD'],
            $_ENV['DB_NAME']
        );
    } else {
        $db = new mysqli('localhost', 'root', '', 'gestionproyectos');
    }

    if(!$db) {
        echo 'Error en la conexión con la base de datos';
        exit;
    }

    $db->set_charset('utf8');
    return $db;
}
