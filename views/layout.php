<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Proyectos</title>
    <!-- Materialize CSS -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
    <!-- custom overrides (minimal) -->
    <link rel="stylesheet" href="/css/custom.css">
</head>
<body>
    <?php
        // ruta actual solicitada por el usuario
        $rutaActual = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
        function estaActivo($ruta, $actual) {
            // devuelve verdadero si $actual comienza con $ruta
            return strpos($actual, $ruta) === 0;
        }
    ?>
    <header>
        <nav class="blue-grey darken-4 z-depth-1 primary" role="navigation">
            <div class="nav-wrapper container">
                <a href="/" class="brand-logo">GS</a>
                <ul class="right hide-on-med-and-down" style="margin-left:auto;">
                    <li class="<?php echo estaActivo('/', $rutaActual) ? 'active' : ''; ?>"><a href="/">Inicio</a></li>
                    <?php if(usuarioActual()): ?>
                        <li><a class="disabled"><?php echo usuarioActual(); ?> (<?php echo rolActual(); ?>)</a></li>
                        <li><a href="/logout" class="red">Cerrar sesión</a></li>
                    <?php endif; ?>
                </ul>
                <a href="#" data-target="mobile-main" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            </div>
        </nav>
        <nav class="blue-grey lighten-1 secondary" role="navigation">
            <div class="nav-wrapper container">
                <ul class="right hide-on-med-and-down">
                    <?php if(usuarioActual()): ?>
                        <?php if(rolActual() === 'admin'): ?>
                            <li class="<?php echo estaActivo('/admin', $rutaActual) ? 'active' : ''; ?>"><a href="/admin">Home Admin</a></li>
                            <li class="<?php echo estaActivo('/disciplinas', $rutaActual) ? 'active' : ''; ?>"><a href="/disciplinas">Disciplinas</a></li>
                            <li class="<?php echo estaActivo('/subdisciplinas', $rutaActual) ? 'active' : ''; ?>"><a href="/subdisciplinas">Subdisciplinas</a></li>
                            <li class="<?php echo estaActivo('/clientes', $rutaActual) ? 'active' : ''; ?>"><a href="/clientes">Clientes</a></li>
                            <li class="<?php echo estaActivo('/empresas', $rutaActual) ? 'active' : ''; ?>"><a href="/empresas">Empresas</a></li>
                            <li class="<?php echo estaActivo('/proyectos', $rutaActual) ? 'active' : ''; ?>"><a href="/proyectos">Proyectos</a></li>
                            <li class="<?php echo estaActivo('/permisos', $rutaActual) ? 'active' : ''; ?>"><a href="/permisos">Permisos</a></li>
                            <li class="<?php echo estaActivo('/categorias', $rutaActual) ? 'active' : ''; ?>"><a href="/categorias">Categorías</a></li>
                            <li class="<?php echo estaActivo('/bitacora', $rutaActual) ? 'active' : ''; ?>"><a href="/bitacora">Bitacora</a></li>
                            <li class="<?php echo estaActivo('/minutas', $rutaActual) ? 'active' : ''; ?>"><a href="/minutas">Minutas</a></li>
                            <li class="<?php echo estaActivo('/p-obra', $rutaActual) ? 'active' : ''; ?>"><a href="/p-obra">P. Obra</a></li>
                        <?php endif; ?>
                        <?php if(rolActual() === 'cliente'): ?>
                            <li class="<?php echo estaActivo('/mis-proyectos', $rutaActual) ? 'active' : ''; ?>"><a href="/mis-proyectos">Mis proyectos</a></li>
                            <li class="<?php echo estaActivo('/reportes/crear', $rutaActual) ? 'active' : ''; ?>"><a href="/reportes/crear">Registro</a></li>
                            <li class="<?php echo estaActivo('/reportes', $rutaActual) ? 'active' : ''; ?>"><a href="/reportes">Ver reportes</a></li>
                            <li class="<?php echo estaActivo('/reportes/galeria', $rutaActual) ? 'active' : ''; ?>"><a href="/reportes/galeria">Galería</a></li>
                            <li class="<?php echo estaActivo('/bitacora', $rutaActual) ? 'active' : ''; ?>"><a href="/bitacora">Bitacora</a></li>
                            <li class="<?php echo estaActivo('/minutas', $rutaActual) ? 'active' : ''; ?>"><a href="/minutas">Minutas</a></li>
                            <li class="<?php echo estaActivo('/p-obra', $rutaActual) ? 'active' : ''; ?>"><a href="/p-obra">P. Obra</a></li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                <a href="#" data-target="mobile-secondary" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            </div>
        </nav>
        <ul class="sidenav" id="mobile-main">
            <li class="<?php echo estaActivo('/', $rutaActual) ? 'active' : ''; ?>"><a href="/">Inicio</a></li>
            <?php if(usuarioActual()): ?>
                <li><a class="disabled"><?php echo usuarioActual(); ?> (<?php echo rolActual(); ?>)</a></li>
                <li><a href="/logout" class="red">Cerrar sesión</a></li>
            <?php endif; ?>
        </ul>
        <ul class="sidenav" id="mobile-secondary">
            <?php if(usuarioActual()): ?>
                <?php if(rolActual() === 'admin'): ?>
                    <li class="<?php echo estaActivo('/admin', $rutaActual) ? 'active' : ''; ?>"><a href="/admin">Home Admin</a></li>
                    <li class="<?php echo estaActivo('/disciplinas', $rutaActual) ? 'active' : ''; ?>"><a href="/disciplinas">Disciplinas</a></li>
                    <li class="<?php echo estaActivo('/subdisciplinas', $rutaActual) ? 'active' : ''; ?>"><a href="/subdisciplinas">Subdisciplinas</a></li>
                    <li class="<?php echo estaActivo('/clientes', $rutaActual) ? 'active' : ''; ?>"><a href="/clientes">Clientes</a></li>
                    <li class="<?php echo estaActivo('/empresas', $rutaActual) ? 'active' : ''; ?>"><a href="/empresas">Empresas</a></li>
                    <li class="<?php echo estaActivo('/proyectos', $rutaActual) ? 'active' : ''; ?>"><a href="/proyectos">Proyectos</a></li>
                    <li class="<?php echo estaActivo('/permisos', $rutaActual) ? 'active' : ''; ?>"><a href="/permisos">Permisos</a></li>
                    <li class="<?php echo estaActivo('/categorias', $rutaActual) ? 'active' : ''; ?>"><a href="/categorias">Categorías</a></li>
                    <li class="<?php echo estaActivo('/bitacora', $rutaActual) ? 'active' : ''; ?>"><a href="/bitacora">Bitacora</a></li>
                    <li class="<?php echo estaActivo('/minutas', $rutaActual) ? 'active' : ''; ?>"><a href="/minutas">Minutas</a></li>
                <?php endif; ?>
                <?php if(rolActual() === 'cliente'): ?>
                    <li class="<?php echo estaActivo('/mis-proyectos', $rutaActual) ? 'active' : ''; ?>"><a href="/mis-proyectos">Mis proyectos</a></li>
                    <li class="<?php echo estaActivo('/reportes/crear', $rutaActual) ? 'active' : ''; ?>"><a href="/reportes/crear">Registro</a></li>
                    <li class="<?php echo estaActivo('/reportes', $rutaActual) ? 'active' : ''; ?>"><a href="/reportes">Ver reportes</a></li>                    <li class="<?php echo estaActivo('/reportes/galeria', $rutaActual) ? 'active' : ''; ?>"><a href="/reportes/galeria">Galería</a></li>                    <li class="<?php echo estaActivo('/bitacora', $rutaActual) ? 'active' : ''; ?>"><a href="/bitacora">Bitacora</a></li>
                    <li class="<?php echo estaActivo('/minutas', $rutaActual) ? 'active' : ''; ?>"><a href="/minutas">Minutas</a></li>
                    <li class="<?php echo estaActivo('/p-obra', $rutaActual) ? 'active' : ''; ?>"><a href="/p-obra">P. Obra</a></li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
    </header>
    <main>
        <div class="container">
        <?php
        // mostrar mensaje si existe (puede incluir tipo)
        if(function_exists('obtenerMensajeFlash')) {
            $mensajeFlash = obtenerMensajeFlash();
            if($mensajeFlash) {
                if(is_array($mensajeFlash)) {
                    $textoMensaje = $mensajeFlash['texto'];
                    $tipoMensaje = $mensajeFlash['tipo'] ?? 'success';
                } else {
                    $textoMensaje = $mensajeFlash;
                    $tipoMensaje = 'success';
                }
                $colorFondo = $tipoMensaje === 'error' ? 'red lighten-4' : 'green lighten-4';
                echo "<div class=\"card-panel $colorFondo\"><span class=\"black-text\">" . htmlspecialchars($textoMensaje) . "</span></div>";
            }
        }
        ?>
        <?php echo $contenido; ?>
        </div>
    </main>
    <footer class="page-footer blue-grey darken-4">
        <div class="container">
            <div class="row">
                <div class="col s12 center">
                    <a href="/" class="brand-logo white-text">GS</a>
                    <p class="white-text">© 2026 Gestión de Proyectos</p>
                </div>
            </div>
        </div>
    </footer>
    <!-- jQuery required by Materialize -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Materialize JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="/build/js/app.js"></script>
</body>
</html>