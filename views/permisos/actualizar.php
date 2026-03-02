<h2>Editar Permiso</h2>

<?php if(!empty($errores)): ?>
    <?php foreach($errores as $error): ?>
        <div class="alerta error"><?php echo $error; ?></div>
    <?php endforeach; ?>
<?php endif; ?>

<form method="POST" class="formulario">
    <fieldset>
        <legend>Información</legend>

        <div class="campo">
        <label for="cliente_id">Cliente</label>
        <select id="cliente_id" name="permisos[cliente_id]">
            <option value="">-- Seleccione --</option>
            <?php foreach($clientes as $c): ?>
                <option value="<?php echo $c->id; ?>" <?php echo $permiso->cliente_id == $c->id ? 'selected' : ''; ?>><?php echo $c->nombre; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

        <?php
            // determinar proyectos seleccionados: preferir envío POST si lo hay, si no usar el valor actual del permiso
            $selected = $_POST['permisos']['proyecto_id'] ?? null;
            if(!is_array($selected)) {
                if($selected !== null) {
                    $selected = [$selected];
                } else {
                    // proviene del controlador
                    $selected = $selectedProjectIds ?? [];
                }
            }
            $selected = array_map('intval', $selected);
        ?>
        <div class="campo">
        <label>Proyectos:</label>
        <div class="checkbox-grupo">
            <?php foreach($proyectos as $p): ?>
                <?php $checked = in_array($p->id, $selected) ? 'checked' : ''; ?>
                <label>
                    <input type="checkbox" class="filled-in" name="permisos[proyecto_id][]" value="<?php echo $p->id; ?>" <?php echo $checked; ?>>
                    <span><?php echo $p->nombre; ?></span>
                </label>
            <?php endforeach; ?>
        </div>
    </div>

        <div class="campo">
        <label for="empresa_id">Empresa</label>
        <select id="empresa_id" name="permisos[empresa_id]">
            <option value="">-- Seleccione --</option>
            <?php foreach($empresas as $e): ?>
                <option value="<?php echo $e->id; ?>" <?php echo $permiso->empresa_id == $e->id ? 'selected' : ''; ?>><?php echo $e->nombre; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

        <div class="campo">
        <label for="permiso">Permiso</label>
        <select id="permiso" name="permisos[permiso]">
            <option value="leer" <?php echo $permiso->permiso === 'leer' ? 'selected' : ''; ?>>Leer</option>
            <option value="editar" <?php echo $permiso->permiso === 'editar' ? 'selected' : ''; ?>>Editar</option>
        </select>
    </div>
    </fieldset>
    <button type="submit" class="btn green"><i class="material-icons left">save</i>Guardar cambios</button>
</form>