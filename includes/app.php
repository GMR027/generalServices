<?php
// Mostrar errores en desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/funciones.php';
require __DIR__ . '/config/database.php';

// Conectar y configurar ActiveRecord si lo usamos
use Model\ActiveRecord;

$conexion = conectarBD();
ActiveRecord::confDatabase($conexion);
