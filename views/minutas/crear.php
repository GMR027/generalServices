<h2>Crear minuta</h2>

<?php if(!empty($errores)): ?>
    <?php foreach($errores as $error): ?>
        <div class="alerta error"><?php echo $error; ?></div>
    <?php endforeach; ?>
<?php endif; ?>

<form method="POST" action="/minutas/crear" class="formulario">
    <div class="input-field">
        <select name="minuta[proyecto_id]" required>
            <option value="" disabled <?php echo empty($minuta->proyecto_id) ? 'selected' : ''; ?>>Elige un proyecto</option>
            <?php foreach($proyectos as $p): ?>
                <option value="<?php echo $p->id; ?>" <?php echo isset($minuta->proyecto_id) && $minuta->proyecto_id == $p->id ? 'selected' : ''; ?>><?php echo htmlspecialchars($p->nombre); ?></option>
            <?php endforeach; ?>
        </select>
        <label>Proyecto</label>
    </div>
    <div class="input-field">
        <input type="date" id="fecha" name="minuta[fecha]" required value="<?php echo $minuta->fecha ?? ''; ?>">
        <label for="fecha" class="active">Fecha</label>
    </div>
    <div class="input-field">
        <input type="text" id="tituloJunta" name="minuta[tituloJunta]" class="validate" value="<?php echo $minuta->tituloJunta ?? ''; ?>">
        <label for="tituloJunta">Título de la junta</label>
    </div>
    <div class="input-field">
        <textarea id="personalJunta" class="materialize-textarea" name="minuta[personalJunta]"><?php echo $minuta->personalJunta ?? ''; ?></textarea>
        <label for="personalJunta">Personal de la junta</label>
    </div>
    <div class="input-field">
        <textarea id="personalCliente" class="materialize-textarea" name="minuta[personalCliente]"><?php echo $minuta->personalCliente ?? ''; ?></textarea>
        <label for="personalCliente">Personal cliente</label>
    </div>
    <div class="input-field">
        <textarea id="categoriaJunta" class="materialize-textarea" name="minuta[categoriaJunta]"><?php echo $minuta->categoriaJunta ?? ''; ?></textarea>
        <label for="categoriaJunta">Categoría de la junta</label>
    </div>
    <div class="input-field">
        <textarea id="informacionJunta" class="materialize-textarea" name="minuta[informacionJunta]" rows="5"><?php echo $minuta->informacionJunta ?? ''; ?></textarea>
        <label for="informacionJunta">Información de la junta</label>
    </div>
    <div class="input-field">
        <textarea id="compromisos" class="materialize-textarea" name="minuta[compromisos]" rows="5"><?php echo $minuta->compromisos ?? ''; ?></textarea>
        <label for="compromisos">Compromisos</label>
    </div>
    <button type="submit" class="btn green"><i class="material-icons left">save</i>Guardar minuta</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('select');
    M.FormSelect.init(elems);
});
</script>
