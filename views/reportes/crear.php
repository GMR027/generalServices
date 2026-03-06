<h2>Crear Reporte</h2>

<?php if(!empty($errores)): ?>
    <?php foreach($errores as $error): ?>
        <div class="alerta error"><?php echo $error; ?></div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if(empty($proyectos) && !esAdmin()): ?>
    <div class="alerta error">No tienes proyectos con permiso de edición. No puedes crear reportes.</div>
<?php else: ?>
<form method="POST" action="/reportes/crear" enctype="multipart/form-data" class="formulario">
    <div class="input-field">
        <select name="proyecto_id" required>
            <option value="" disabled selected>Elige un proyecto</option>
            <?php foreach($proyectos as $p): ?>
                <option value="<?php echo $p->id; ?>" <?php echo isset($reporte->proyecto_id) && $reporte->proyecto_id == $p->id ? 'selected' : ''; ?>><?php echo $p->nombre; ?></option>
            <?php endforeach; ?>
        </select>
        <label>Proyecto</label>
    </div>

    <div class="input-field">
        <select name="subdisciplina_id" required>
            <option value="" disabled selected>Elige una subdisciplina</option>
            <?php foreach($subdisciplinas as $sd): ?>
                <option value="<?php echo $sd->id; ?>" <?php echo isset($reporte->subdisciplina_id) && $reporte->subdisciplina_id == $sd->id ? 'selected' : ''; ?>><?php echo $sd->nombre; ?></option>
            <?php endforeach; ?>
        </select>
        <label>Subdisciplina</label>
    </div>

    <div class="input-field">
        <input type="text" id="actividad" name="actividad" class="validate" required value="<?php echo $reporte->actividad ?? ''; ?>">
        <label for="actividad">Actividad</label>
    </div>

    <div class="input-field">
        <input type="text" id="area_zonal" name="area_zonal" class="validate" required value="<?php echo $reporte->area_zonal ?? ''; ?>">
        <label for="area_zonal">Área/Zona</label>
    </div>

    <div class="input-field">
        <input type="text" id="nivel" name="nivel" class="validate" required value="<?php echo $reporte->nivel ?? ''; ?>">
        <label for="nivel">Nivel</label>
    </div>

    <div class="input-field">
        <textarea id="permisoTrabajo" class="materialize-textarea" name="permisoTrabajo"><?php echo $reporte->permisoTrabajo ?? ''; ?></textarea>
        <label for="permisoTrabajo">Permiso de trabajo</label>
    </div>

    <div class="input-field">
        <input type="number" id="horastrabajadas" name="horastrabajadas" class="validate" min="0" required value="<?php echo $reporte->horastrabajadas ?? ''; ?>">
        <label for="horastrabajadas">Horas trabajadas</label>
    </div>

    <div class="campo">
        <label for="imagenes">Imágenes</label>
        <input type="file" id="imagenes" name="imagenes[]" multiple>
    </div>

    <button type="submit" class="btn green"><i class="material-icons left">save</i>Guardar Reporte</button>
</form>

<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('select');
    M.FormSelect.init(elems);
});
</script>
