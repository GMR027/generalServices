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
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
        function isActive($path, $current) {
            // return true if $current starts with $path
            return strpos($current, $path) === 0;
        }
    ?>
    <header>
        <nav class="blue-grey darken-4 z-depth-1 primary" role="navigation">
            <div class="nav-wrapper container">
                <a href="/" class="brand-logo">GS</a>
                <ul class="right hide-on-med-and-down" style="margin-left:auto;">
                    <li class="<?php echo isActive('/', $currentPath) ? 'active' : ''; ?>"><a href="/">Inicio</a></li>
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
                            <li class="<?php echo isActive('/admin', $currentPath) ? 'active' : ''; ?>"><a href="/admin">Home Admin</a></li>
                            <li class="<?php echo isActive('/disciplinas', $currentPath) ? 'active' : ''; ?>"><a href="/disciplinas">Disciplinas</a></li>
                            <li class="<?php echo isActive('/subdisciplinas', $currentPath) ? 'active' : ''; ?>"><a href="/subdisciplinas">Subdisciplinas</a></li>
                            <li class="<?php echo isActive('/clientes', $currentPath) ? 'active' : ''; ?>"><a href="/clientes">Clientes</a></li>
                            <li class="<?php echo isActive('/empresas', $currentPath) ? 'active' : ''; ?>"><a href="/empresas">Empresas</a></li>
                            <li class="<?php echo isActive('/proyectos', $currentPath) ? 'active' : ''; ?>"><a href="/proyectos">Proyectos</a></li>
                            <li class="<?php echo isActive('/permisos', $currentPath) ? 'active' : ''; ?>"><a href="/permisos">Permisos</a></li>
                            <li class="<?php echo isActive('/categorias', $currentPath) ? 'active' : ''; ?>"><a href="/categorias">Categorías</a></li>
                            <li class="<?php echo isActive('/bitacora', $currentPath) ? 'active' : ''; ?>"><a href="/bitacora">Bitacora</a></li>
                            <li class="<?php echo isActive('/minutas', $currentPath) ? 'active' : ''; ?>"><a href="/minutas">Minutas</a></li>
                            <li class="<?php echo isActive('/p-obra', $currentPath) ? 'active' : ''; ?>"><a href="/p-obra">P. Obra</a></li>
                        <?php endif; ?>
                        <?php if(rolActual() === 'cliente'): ?>
                            <li class="<?php echo isActive('/mis-proyectos', $currentPath) ? 'active' : ''; ?>"><a href="/mis-proyectos">Mis proyectos</a></li>
                            <li class="<?php echo isActive('/reportes/crear', $currentPath) ? 'active' : ''; ?>"><a href="/reportes/crear">Registro</a></li>
                            <li class="<?php echo isActive('/reportes', $currentPath) ? 'active' : ''; ?>"><a href="/reportes">Ver reportes</a></li>
                            <li class="<?php echo isActive('/reportes/galeria', $currentPath) ? 'active' : ''; ?>"><a href="/reportes/galeria">Galería</a></li>
                            <li class="<?php echo isActive('/bitacora', $currentPath) ? 'active' : ''; ?>"><a href="/bitacora">Bitacora</a></li>
                            <li class="<?php echo isActive('/minutas', $currentPath) ? 'active' : ''; ?>"><a href="/minutas">Minutas</a></li>
                            <li class="<?php echo isActive('/p-obra', $currentPath) ? 'active' : ''; ?>"><a href="/p-obra">P. Obra</a></li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                <a href="#" data-target="mobile-secondary" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            </div>
        </nav>
        <ul class="sidenav" id="mobile-main">
            <li class="<?php echo isActive('/', $currentPath) ? 'active' : ''; ?>"><a href="/">Inicio</a></li>
            <?php if(usuarioActual()): ?>
                <li><a class="disabled"><?php echo usuarioActual(); ?> (<?php echo rolActual(); ?>)</a></li>
                <li><a href="/logout" class="red">Cerrar sesión</a></li>
            <?php endif; ?>
        </ul>
        <ul class="sidenav" id="mobile-secondary">
            <?php if(usuarioActual()): ?>
                <?php if(rolActual() === 'admin'): ?>
                    <li class="<?php echo isActive('/admin', $currentPath) ? 'active' : ''; ?>"><a href="/admin">Home Admin</a></li>
                    <li class="<?php echo isActive('/disciplinas', $currentPath) ? 'active' : ''; ?>"><a href="/disciplinas">Disciplinas</a></li>
                    <li class="<?php echo isActive('/subdisciplinas', $currentPath) ? 'active' : ''; ?>"><a href="/subdisciplinas">Subdisciplinas</a></li>
                    <li class="<?php echo isActive('/clientes', $currentPath) ? 'active' : ''; ?>"><a href="/clientes">Clientes</a></li>
                    <li class="<?php echo isActive('/empresas', $currentPath) ? 'active' : ''; ?>"><a href="/empresas">Empresas</a></li>
                    <li class="<?php echo isActive('/proyectos', $currentPath) ? 'active' : ''; ?>"><a href="/proyectos">Proyectos</a></li>
                    <li class="<?php echo isActive('/permisos', $currentPath) ? 'active' : ''; ?>"><a href="/permisos">Permisos</a></li>
                    <li class="<?php echo isActive('/categorias', $currentPath) ? 'active' : ''; ?>"><a href="/categorias">Categorías</a></li>
                    <li class="<?php echo isActive('/bitacora', $currentPath) ? 'active' : ''; ?>"><a href="/bitacora">Bitacora</a></li>
                    <li class="<?php echo isActive('/minutas', $currentPath) ? 'active' : ''; ?>"><a href="/minutas">Minutas</a></li>
                <?php endif; ?>
                <?php if(rolActual() === 'cliente'): ?>
                    <li class="<?php echo isActive('/mis-proyectos', $currentPath) ? 'active' : ''; ?>"><a href="/mis-proyectos">Mis proyectos</a></li>
                    <li class="<?php echo isActive('/reportes/crear', $currentPath) ? 'active' : ''; ?>"><a href="/reportes/crear">Registro</a></li>
                    <li class="<?php echo isActive('/reportes', $currentPath) ? 'active' : ''; ?>"><a href="/reportes">Ver reportes</a></li>                    <li class="<?php echo isActive('/reportes/galeria', $currentPath) ? 'active' : ''; ?>"><a href="/reportes/galeria">Galería</a></li>                    <li class="<?php echo isActive('/bitacora', $currentPath) ? 'active' : ''; ?>"><a href="/bitacora">Bitacora</a></li>
                    <li class="<?php echo isActive('/minutas', $currentPath) ? 'active' : ''; ?>"><a href="/minutas">Minutas</a></li>
                    <li class="<?php echo isActive('/p-obra', $currentPath) ? 'active' : ''; ?>"><a href="/p-obra">P. Obra</a></li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
    </header>
    <main>
        <div class="container">
        <?php
        // mostrar mensaje si existe (puede incluir tipo)
        if(function_exists('obtenerMensajeFlash')) {
            $flash = obtenerMensajeFlash();
            if($flash) {
                if(is_array($flash)) {
                    $texto = $flash['texto'];
                    $tipo = $flash['tipo'] ?? 'success';
                } else {
                    $texto = $flash;
                    $tipo = 'success';
                }
                $color = $tipo === 'error' ? 'red lighten-4' : 'green lighten-4';
                echo "<div class=\"card-panel $color\"><span class=\"black-text\">" . htmlspecialchars($texto) . "</span></div>";
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