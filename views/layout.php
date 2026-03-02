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
    <header>
        <nav class="blue-grey darken-4 z-depth-1" role="navigation">
            <div class="nav-wrapper container">
                <a href="/" class="brand-logo">GS</a>
                <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a href="/">Inicio</a></li>
                    <?php if(usuarioActual()): ?>
                        <?php if(rolActual() === 'admin'): ?>
                            <li><a href="/admin">Home Admin</a></li>
                            <li><a href="/disciplinas">Disciplinas</a></li>
                            <li><a href="/subdisciplinas">Subdisciplinas</a></li>
                            <li><a href="/clientes">Clientes</a></li>
                            <li><a href="/empresas">Empresas</a></li>
                            <li><a href="/proyectos">Proyectos</a></li>
                            <li><a href="/permisos">Permisos</a></li>
                        <?php endif; ?>
                        <?php if(rolActual() === 'cliente'): ?>
                            <li><a href="/mis-proyectos">Mis proyectos</a></li>
                            <li><a href="/reportes/crear">Registro</a></li>
                            <li><a href="/reportes">Ver reportes</a></li>
                        <?php endif; ?>
                        <li><a href="#" class="disabled"><?php echo usuarioActual(); ?> (<?php echo rolActual(); ?>)</a></li>
                        <li><a href="/logout" class="red">Cerrar sesión</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
        <ul class="sidenav" id="mobile-demo">
            <li><a href="/">Inicio</a></li>
            <?php if(usuarioActual()): ?>
                <?php if(rolActual() === 'admin'): ?>
                    <li><a href="/admin">Home Admin</a></li>
                    <li><a href="/disciplinas">Disciplinas</a></li>
                    <li><a href="/subdisciplinas">Subdisciplinas</a></li>
                    <li><a href="/clientes">Clientes</a></li>
                    <li><a href="/empresas">Empresas</a></li>
                    <li><a href="/proyectos">Proyectos</a></li>
                    <li><a href="/permisos">Permisos</a></li>
                <?php endif; ?>
                <?php if(rolActual() === 'cliente'): ?>
                    <li><a href="/mis-proyectos">Mis proyectos</a></li>
                    <li><a href="/reportes/crear">Registro</a></li>
                    <li><a href="/reportes">Ver reportes</a></li>
                <?php endif; ?>
                <li><a href="#" class="disabled"><?php echo usuarioActual(); ?> (<?php echo rolActual(); ?>)</a></li>
                <li><a href="/logout" class="red">Cerrar sesión</a></li>
            <?php endif; ?>
        </ul>
    </header>
    <main>
        <div class="container">
        <?php
        // mostrar mensaje si existe (puede incluir tipo)
        if(function_exists('getFlashMessage')) {
            $flash = getFlashMessage();
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