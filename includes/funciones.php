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

function ensureSession() {
    if(session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function isAdmin(): bool {
    ensureSession();
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
}

function isCliente(): bool {
    ensureSession();
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'cliente';
}

// flash message helpers
function setFlashMessage(string $mensaje, string $tipo = 'success') {
    ensureSession();
    // almacenamos texto y tipo (success, error, info, etc.)
    $_SESSION['mensaje'] = ['texto' => $mensaje, 'tipo' => $tipo];
}

/**
 * Devuelve el mensaje flash y elimina la sesión.
 * Puede ser un array con 'texto' y 'tipo', o null si no existe.
 *
 * @return null|array
 */
function getFlashMessage() {
    ensureSession();
    $msg = $_SESSION['mensaje'] ?? null;
    if($msg) {
        unset($_SESSION['mensaje']);
    }
    return $msg;
}

function usuarioActual(): ?string {
    ensureSession();
    // email stored during autenticación
    return $_SESSION['correo'] ?? null;
}

function idActual(): ?int {
    ensureSession();
    return isset($_SESSION['id']) ? intval($_SESSION['id']) : null;
}

function rolActual(): ?string {
    ensureSession();
    return $_SESSION['rol'] ?? null;
}
