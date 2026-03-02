<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/includes/app.php';

use Model\Usuario;

$u = new Usuario(['correo'=>'e.gm27@outlook.com','contrasena'=>'1234']);
$found = $u->existeUsuario();
var_dump($found);
var_dump(password_verify('1234', $found['contrasena']));
