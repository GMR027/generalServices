<h2>Iniciar sesión</h2>

<?php foreach($errores as $error): ?>
    <div class="alerta error">
        <?php echo $error; ?>
    </div>
<?php endforeach; ?>

<form method="POST" class="formulario">
    <fieldset>
        <legend>Datos de acceso</legend>

        <label for="correo">Correo:</label>
        <input type="email" name="login[correo]" id="correo" placeholder="Tu correo" required>

        <label for="contrasena">Contraseña:</label>
        <input type="password" name="login[contrasena]" id="contrasena" placeholder="Tu contraseña" required>
    </fieldset>

    <button type="submit" class="btn green"><i class="material-icons left">login</i>Entrar</button>
</form>