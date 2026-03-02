<h2>Actualizar Subdisciplina</h2>

<?php foreach($errores as $error): ?>
    <div class="alerta error"><?php echo $error; ?></div>
<?php endforeach; ?>

<form method="POST" class="formulario">
    <label for="disciplina_id">Disciplina:</label>
    <select name="subdisciplina[disciplina_id]" id="disciplina_id">
        <option value="">-- Seleccione --</option>
        <?php foreach($disciplinas as $d): ?>
            <option value="<?php echo $d->id; ?>" <?php echo $subdisciplina->disciplina_id == $d->id ? 'selected' : ''; ?>><?php echo $d->nombre; ?></option>
        <?php endforeach; ?>
    </select>

    <label for="nombre">Nombre:</label>
    <input type="text" name="subdisciplina[nombre]" id="nombre" value="<?php echo $subdisciplina->nombre; ?>">

    <button type="submit" class="btn green"><i class="material-icons left">save</i>Actualizar</button>
</form>