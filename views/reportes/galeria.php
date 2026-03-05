<?php use Model\Proyecto; ?>

<div class="row valign-wrapper" style="margin-bottom:1rem;">
    <div class="col s12 m6">
        <h2 class="left">Galería de reportes</h2>
    </div>
    <div class="col s12 m6 right-align">
        <a href="/reportes" class="btn grey btn-small"><i class="material-icons left">arrow_back</i>Volver a reportes</a>
    </div>
</div>

<!-- filtro por proyectos -->
<?php if(!empty($filterProjects)): ?>
<form method="GET" class="row" style="margin-top:1rem;">
    <div class="input-field col s12 m6">
        <select name="proyecto_id[]" multiple>
            <option value="" disabled>Selecciona proyectos</option>
            <?php foreach($filterProjects as $fp): ?>
                <option value="<?php echo $fp->id; ?>" <?php echo in_array($fp->id, $selectedProjects) ? 'selected' : ''; ?>><?php echo $fp->nombre; ?></option>
            <?php endforeach; ?>
        </select>
        <label>Filtrar por proyecto</label>
    </div>
    <div class="col s12 m6" style="margin-top:1.5rem;">
        <button type="submit" class="btn blue">Aplicar filtro</button>
        <a href="/reportes/galeria" class="btn grey">Limpiar</a>
    </div>
</form>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('select');
    M.FormSelect.init(elems);
});
</script>

<?php if(empty($reportes)): ?>
    <p>No hay reportes con imágenes para mostrar.</p>
<?php else: ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.materialboxed');
    M.Materialbox.init(elems);
});
</script>

    <?php foreach($reportes as $r): ?>
        <table class="tabla-datos" style="margin-top:2rem;">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Actividad</th>
                    <th>Subdisciplina</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo isset($r->created_at) ? date('d\/m\/Y', strtotime($r->created_at)) : '-'; ?></td>
                    <td><?php echo htmlspecialchars(
                        strlen($r->actividad) > 60 ? substr($r->actividad,0,60) . '...' : $r->actividad
                    ); ?></td>
                    <td><?php echo $r->subdisciplina_nombre ?? '-'; ?></td>
                </tr>
            </tbody>
        </table>

        <?php if($r->imagenes):
            $imgs = json_decode($r->imagenes, true);
            if(!empty($imgs)): ?>
                <div class="row" style="margin-bottom:2rem;">
                <?php foreach($imgs as $idx => $img): ?>
                    <div class="col s4">
                        <img src="/image/<?php echo $img; ?>" class="responsive-img materialboxed" style="max-width:400px; height:auto;" />
                    </div>
                    <?php if((($idx+1) % 3) === 0): ?>
                        </div>
                        <div class="row">
                    <?php endif; ?>
                <?php endforeach; ?>
                </div>
        <?php endif; endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
