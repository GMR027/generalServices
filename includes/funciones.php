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
        exit;
    }
    return $id;
}

define('AUTH_COOKIE', 'auth');

function asegurarSesion() {
    if(session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function leerCookieAuth(): ?array {
    $raw = $_COOKIE[AUTH_COOKIE] ?? null;
    if(!$raw) return null;
    $data = json_decode(base64_decode($raw), true);
    if(!is_array($data) || empty($data['login'])) return null;
    return $data;
}

function esAdmin(): bool {
    $auth = leerCookieAuth();
    return $auth !== null && ($auth['rol'] ?? '') === 'admin';
}

function esCliente(): bool {
    $auth = leerCookieAuth();
    return $auth !== null && ($auth['rol'] ?? '') === 'cliente';
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
    $auth = leerCookieAuth();
    return $auth['correo'] ?? null;
}

function idActual(): ?int {
    $auth = leerCookieAuth();
    return isset($auth['id']) ? intval($auth['id']) : null;
}

function rolActual(): ?string {
    $auth = leerCookieAuth();
    return $auth['rol'] ?? null;
}
