<h2>Actualizar Usuario</h2>

<?php foreach($errores as $error): ?>
    <div class="alerta error"><?php echo $error; ?></div>
<?php endforeach; ?>

<form method="POST" class="formulario">
    <fieldset>
        <legend>Datos</legend>
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="usuario[nombre]" value="<?php echo $usuario->nombre; ?>">

        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="usuario[correo]" value="<?php echo $usuario->correo; ?>">

        <label for="contrasena">Contraseña (dejar en blanco para no cambiar):</label>
        <input type="password" id="contrasena" name="usuario[contrasena]">

        <label for="rol">Rol:</label>
        <select id="rol" name="usuario[rol]">
            <option value="cliente" <?php echo $usuario->rol === 'cliente' ? 'selected' : ''; ?>>Cliente</option>
            <option value="admin" <?php echo $usuario->rol === 'admin' ? 'selected' : ''; ?>>Administrador</option>
        </select>

        <label for="estatus">Estatus:</label>
        <select id="estatus" name="usuario[estatus]">
            <option value="activo" <?php echo $usuario->estatus === 'activo' ? 'selected' : ''; ?>>Activo</option>
            <option value="inactivo" <?php echo $usuario->estatus === 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
        </select>
    </fieldset>
    <button type="submit" class="btn green"><i class="material-icons left">save</i>Actualizar</button>
</form>