<h2>Iniciar sesión</h2>

<?php foreach($errores as $error): ?>
    <div class="alerta error">
        <?php echo $error; ?>
    </div>
<?php endforeach; ?>

<form method="POST" class="formulario" style="max-width:400px; margin:auto;">
    <fieldset>
        <legend>Datos de acceso</legend>

        <div class="input-field">
            <input type="email" name="login[correo]" id="correo" class="validate" required>
            <label for="correo">Correo</label>
        </div>

        <div class="input-field">
            <input type="password" name="login[contrasena]" id="contrasena" class="validate" required>
            <label for="contrasena">Contraseña</label>
        </div>
    </fieldset>

    <button type="submit" class="btn green"><i class="material-icons left">login</i>Entrar</button>
</form>