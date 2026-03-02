<h2>Crear Proyecto</h2>

<?php foreach($errores as $error): ?>
    <div class="alerta error"><?php echo $error; ?></div>
<?php endforeach; ?>

<form method="POST" class="formulario">
    <label for="nombre">Nombre:</label>
    <input type="text" name="proyecto[nombre]" id="nombre" value="<?php echo $proyecto->nombre; ?>">

    <label for="ubicacion">Ubicación:</label>
    <input type="text" name="proyecto[ubicacion]" id="ubicacion" value="<?php echo $proyecto->ubicacion; ?>">

    <label for="disciplina_id">Disciplina:</label>
    <select name="proyecto[disciplina_id]" id="disciplina_id">
        <option value="">-- Seleccione --</option>
        <?php foreach($disciplinas as $d): ?>
            <option value="<?php echo $d->id; ?>" <?php echo $proyecto->disciplina_id == $d->id ? 'selected' : ''; ?>><?php echo $d->nombre; ?></option>
        <?php endforeach; ?>
    </select>

    <label for="subdisciplina_id">Subdisciplina:</label>
    <select name="proyecto[subdisciplina_id]" id="subdisciplina_id">
        <option value="">-- Seleccione --</option>
        <?php foreach($subdisciplinas as $s): ?>
            <option value="<?php echo $s->id; ?>" <?php echo $proyecto->subdisciplina_id == $s->id ? 'selected' : ''; ?>><?php echo $s->nombre; ?></option>
        <?php endforeach; ?>
    </select>

    <label for="fecha_inicio">Fecha inicio:</label>
    <input type="date" name="proyecto[fecha_inicio]" id="fecha_inicio" value="<?php echo $proyecto->fecha_inicio; ?>">

    <label for="fecha_fin">Fecha fin:</label>
    <input type="date" name="proyecto[fecha_fin]" id="fecha_fin" value="<?php echo $proyecto->fecha_fin; ?>">

    <button type="submit" class="btn green"><i class="material-icons left">save</i>Guardar</button>
</form>