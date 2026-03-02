<h2>Asignar Permisos</h2>

<?php foreach($errores as $error): ?>
    <div class="alerta error"><?php echo $error; ?></div>
<?php endforeach; ?>

<form method="POST" class="formulario">
    <label for="cliente">Cliente:</label>
    <select name="permisos[cliente_id]" id="cliente">
        <option value="">-- Seleccione --</option>
        <?php foreach($clientes as $c): ?>
            <?php if($c->rol === 'cliente'): ?>
            <option value="<?php echo $c->id; ?>"><?php echo $c->nombre; ?></option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>

    <label for="empresa">Empresa:</label>
    <select name="permisos[empresa_id]" id="empresa">
        <option value="">-- Seleccione --</option>
        <?php foreach($empresas as $e): ?>
            <option value="<?php echo $e->id; ?>"><?php echo $e->nombre; ?></option>
        <?php endforeach; ?>
    </select>

    <?php
        // si el formulario se reenvía con errores, conservar selección
        $selectedProjects = $_POST['permisos']['proyecto_id'] ?? [];
        if(!is_array($selectedProjects)) {
            $selectedProjects = $selectedProjects ? [$selectedProjects] : [];
        }
    ?>
    <label>Proyectos:</label>
    <div class="checkbox-grupo">
        <?php foreach($proyectos as $pr): ?>
            <?php $checked = in_array($pr->id, $selectedProjects) ? 'checked' : ''; ?>
            <label>
                <input type="checkbox" class="filled-in" name="permisos[proyecto_id][]" value="<?php echo $pr->id; ?>" <?php echo $checked; ?>>
                <span><?php echo $pr->nombre; ?></span>
            </label>
        <?php endforeach; ?>
    </div>

    <label for="permiso">Permiso:</label>
    <select name="permisos[permiso]" id="permiso">
        <option value="leer">Leer</option>
        <option value="editar">Editar</option>
    </select>

    <button type="submit" class="btn green"><i class="material-icons left">check</i>Asignar</button>
</form>