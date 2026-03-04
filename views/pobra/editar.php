<h2>Editar Actividad</h2>

<?php if(!empty($errores)): ?>
    <?php foreach($errores as $error): ?>
        <div class="alerta error"><?php echo $error; ?></div>
    <?php endforeach; ?>
<?php endif; ?>

<form method="POST" action="/p-obra/editar?id=<?php echo $actividad->id; ?>" class="formulario" id="formActividad">
    <input type="hidden" name="actividad[id]" value="<?php echo $actividad->id; ?>">
    <div class="input-field">
        <select name="actividad[proyecto_id]" required>
            <option value="" disabled>Elige un proyecto</option>
            <?php foreach($proyectos as $p): ?>
                <option value="<?php echo $p->id; ?>" <?php echo $actividad->proyecto_id == $p->id ? 'selected' : ''; ?>><?php echo htmlspecialchars($p->nombre); ?></option>
            <?php endforeach; ?>
        </select>
        <label>Proyecto</label>
    </div>

    <div class="input-field">
        <input type="number" id="item" name="actividad[item]" class="validate" required value="<?php echo $actividad->item; ?>">
        <label for="item" class="active">Item</label>
    </div>

    <div class="input-field">
        <textarea id="actividad" class="materialize-textarea" name="actividad[actividad]"><?php echo $actividad->actividad; ?></textarea>
        <label for="actividad" class="active">Actividad</label>
    </div>

    <div class="input-field">
        <input type="date" id="fecha_inicio" name="actividad[fecha_inicio]" required value="<?php echo $actividad->fecha_inicio; ?>">
        <label for="fecha_inicio" class="active">Fecha Inicio</label>
    </div>

    <div class="input-field">
        <input type="date" id="fecha_fin" name="actividad[fecha_fin]" required value="<?php echo $actividad->fecha_fin; ?>">
        <label for="fecha_fin" class="active">Fecha Fin</label>
    </div>

    <div class="input-field">
        <input type="number" id="dias" name="actividad[dias]" class="validate" readonly value="<?php echo $actividad->dias; ?>">
        <label for="dias" class="active">Días</label>
    </div>

    <div class="input-field">
        <input type="number" id="porcentajeAvance" name="actividad[porcentajeAvance]" class="validate" min="0" max="100" required value="<?php echo $actividad->porcentajeAvance; ?>">
        <label for="porcentajeAvance" class="active">Porcentaje Avance</label>
    </div>

    <div class="input-field">
        <textarea id="comentarios" class="materialize-textarea" name="actividad[comentarios]"><?php echo $actividad->comentarios; ?></textarea>
        <label for="comentarios" class="active">Comentarios</label>
    </div>

    <button type="submit" class="btn green"><i class="material-icons left">save</i>Actualizar actividad</button>
</form>

<script>
(function(){
    const inicio = document.getElementById('fecha_inicio');
    const fin = document.getElementById('fecha_fin');
    const dias = document.getElementById('dias');
    function calc(){
        if(inicio.value && fin.value){
            const d1 = new Date(inicio.value);
            const d2 = new Date(fin.value);
            const diff = Math.ceil((d2 - d1) / (1000*60*60*24)) + 1;
            dias.value = diff > 0 ? diff : '';
        }
    }
    inicio.addEventListener('change', calc);
    fin.addEventListener('change', calc);
})();
</script>
