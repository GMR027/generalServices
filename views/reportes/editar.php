<h2>Editar Reporte</h2>

<?php if(!empty($errores)): ?>
    <?php foreach($errores as $error): ?>
        <div class="alerta error"><?php echo $error; ?></div>
    <?php endforeach; ?>
<?php endif; ?>

<form method="POST" action="/reportes/editar" enctype="multipart/form-data" class="formulario">
    <input type="hidden" name="id" value="<?php echo $reporte->id; ?>">

    <div class="campo">
        <label for="proyecto">Proyecto</label>
        <select name="proyecto_id" required>
            <option value="">-- Seleccione --</option>
            <?php foreach($proyectos as $p): ?>
                <option value="<?php echo $p->id; ?>" <?php echo $reporte->proyecto_id == $p->id ? 'selected' : ''; ?>><?php echo $p->nombre; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="campo">
        <label for="subdisciplina">Subdisciplina</label>
        <select name="subdisciplina_id" required>
            <option value="">-- Seleccione --</option>
            <?php foreach($subdisciplinas as $sd): ?>
                <option value="<?php echo $sd->id; ?>" <?php echo $reporte->subdisciplina_id == $sd->id ? 'selected' : ''; ?>><?php echo $sd->nombre; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="campo">
        <label for="actividad">Actividad</label>
        <input type="text" id="actividad" name="actividad" required value="<?php echo $reporte->actividad; ?>">
    </div>

    <div class="campo">
        <label for="area_zonal">Área/Zona</label>
        <input type="text" id="area_zonal" name="area_zonal" required value="<?php echo $reporte->area_zonal; ?>">
    </div>

    <div class="campo">
        <label for="nivel">Nivel</label>
        <input type="text" id="nivel" name="nivel" required value="<?php echo $reporte->nivel; ?>">
    </div>

    <div class="campo">
        <label for="permisoTrabajo">Permiso de trabajo</label>
        <textarea id="permisoTrabajo" name="permisoTrabajo"><?php echo $reporte->permisoTrabajo; ?></textarea>
    </div>

    <div class="campo">
        <label for="horastrabajadas">Horas trabajadas</label>
        <input type="number" id="horastrabajadas" name="horastrabajadas" min="0" required value="<?php echo $reporte->horastrabajadas; ?>">
    </div>

    <div class="campo">
        <label for="imagenes">Reemplazar imágenes (las anteriores se eliminarán)</label>
        <input type="file" id="imagenes" name="imagenes[]" multiple>
    </div>

    <button type="submit" class="btn green"><i class="material-icons left">save</i>Guardar Cambios</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('select');
    M.FormSelect.init(elems);
});
</script>