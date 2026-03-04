<h2>Crear entrada de Bitácora</h2>

<?php if(!empty($errores)): ?>
    <?php foreach($errores as $error): ?>
        <div class="alerta error"><?php echo $error; ?></div>
    <?php endforeach; ?>
<?php endif; ?>

<form method="POST" action="/bitacora/crear" class="formulario">
    <div class="input-field">
        <input type="date" id="fecha" name="bitacora[fecha]" required value="<?php echo $bitacora->fecha ?? ''; ?>">
        <label for="fecha" class="active">Fecha</label>
    </div>

    <div class="input-field">
        <select name="bitacora[proyecto_id]" required>
            <option value="" disabled selected>Elige un proyecto</option>
            <?php foreach($proyectos as $p): ?>
                <option value="<?php echo $p->id; ?>" <?php echo isset($bitacora->proyecto_id) && $bitacora->proyecto_id == $p->id ? 'selected' : ''; ?>><?php echo htmlspecialchars($p->nombre); ?></option>
            <?php endforeach; ?>
        </select>
        <label>Proyecto</label>
    </div>

    <div class="input-field">
        <select name="bitacora[categoria_id]" required>
            <option value="" disabled selected>Elige una categoría</option>
            <?php foreach($categorias as $c): ?>
                <option value="<?php echo $c->id; ?>" <?php echo isset($bitacora->categoria_id) && $bitacora->categoria_id == $c->id ? 'selected' : ''; ?>><?php echo htmlspecialchars($c->nombre); ?></option>
            <?php endforeach; ?>
        </select>
        <label>Categoría</label>
    </div>

    <div class="input-field">
        <textarea id="comentarios" class="materialize-textarea" name="bitacora[comentarios]"><?php echo $bitacora->comentarios ?? ''; ?></textarea>
        <label for="comentarios">Comentarios</label>
    </div>

    <div class="input-field">
        <input type="text" id="usuario" name="bitacora[usuario]" class="validate" required value="<?php echo $bitacora->usuario ?? ''; ?>">
        <label for="usuario">Usuario</label>
    </div>

    <div class="input-field">
        <select name="bitacora[nivelImportancia]" id="nivelImportancia" required>
            <option value="" disabled selected>Elige nivel</option>
            <option value="1" <?php echo isset($bitacora->nivelImportancia) && $bitacora->nivelImportancia == 1 ? 'selected' : ''; ?>>Bajo</option>
            <option value="2" <?php echo isset($bitacora->nivelImportancia) && $bitacora->nivelImportancia == 2 ? 'selected' : ''; ?>>Medio</option>
            <option value="3" <?php echo isset($bitacora->nivelImportancia) && $bitacora->nivelImportancia == 3 ? 'selected' : ''; ?>>Alto</option>
        </select>
        <label for="nivelImportancia">Nivel de importancia</label>
    </div>

    <button type="submit" class="btn green"><i class="material-icons left">save</i>Guardar entrada</button>
</form>
