<?php

function debuguear($dato) {
    echo '<pre>';
    var_dump($dato);
    echo '</pre>';
    exit;
}

function limpiar($input): string {
    return htmlspecialchars($input);
}

function validarId(string $url) {
    $id = $_GET['id'] ?? null;
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if(!$id) {
        header("Location: $url");
    }
    return $id;
}

function asegurarSesion() {
    if(session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function esAdmin(): bool {
    asegurarSesion();
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
}

function esCliente(): bool {
    asegurarSesion();
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'cliente';
}

// flash message helpers
function establecerMensajeFlash(string $mensaje, string $tipo = 'success') {
    asegurarSesion();
    // almacenamos texto y tipo (success, error, info, etc.)
    $_SESSION['mensaje'] = ['texto' => $mensaje, 'tipo' => $tipo];
}

/**
 * Devuelve el mensaje flash y elimina la sesión.
 * Puede ser un array con 'texto' y 'tipo', o null si no existe.
 *
 * @return null|array
 */
function obtenerMensajeFlash() {
    asegurarSesion();
    $msg = $_SESSION['mensaje'] ?? null;
    if($msg) {
        unset($_SESSION['mensaje']);
    }
    return $msg;
}

function usuarioActual(): ?string {
    asegurarSesion();
    // email stored during autenticación
    return $_SESSION['correo'] ?? null;
}

function idActual(): ?int {
    asegurarSesion();
    return isset($_SESSION['id']) ? intval($_SESSION['id']) : null;
}

function rolActual(): ?string {
    asegurarSesion();
    return $_SESSION['rol'] ?? null;
}
