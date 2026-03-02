<?php use Model\Proyecto;
use Model\Disciplina;
use Model\Subdisciplina; ?>


<h2>Reportes</h2>
<?php if(isset($canCreate) && $canCreate): ?>
    <a href="/reportes/crear" class="btn green"><i class="material-icons left">add</i>Nuevo reporte</a>
<?php endif; ?>

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
        <a href="/reportes" class="btn grey">Limpiar</a>
    </div>
</form>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('select');
    M.FormSelect.init(elems);
});
</script>

<div class="responsive-table">
<table class="tabla-datos">
    <thead>
        <tr>
            <th>ID</th>
            <th>Fecha</th>
            <th>Proyecto</th>            <th>Disciplina</th>
            <th>Subdisciplina</th>            <th class="col-actividad">Actividad</th>
            <th>Área/Zona</th>
            <th>Nivel</th>
            <th>Permiso Trabajo</th>
            <th>Horas</th>
            <th>Acciones</th>
            <th>Detalle</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($reportes as $r): ?>
            <tr>
                <td><?php echo $r->id; ?></td>
            <td><?php echo isset($r->created_at) ? date('d\/m\/Y', strtotime($r->created_at)) : '-'; ?></td>
                <?php // if query returned the names, use them; otherwise fall back to lookups ?>
                <?php /** @var Proyecto|null $proj */
                $proj = Proyecto::find($r->proyecto_id);
                ?>
                <td><?php echo $r->proyecto_nombre ?? $proj->nombre ?? '-'; ?></td>
                <td><?php echo $r->disciplina_nombre ?? (Disciplina::find($proj->disciplina_id)->nombre ?? '-'); ?></td>
                <td><?php echo $r->subdisciplina_nombre ?? (Subdisciplina::find($proj->subdisciplina_id)->nombre ?? '-'); ?></td>
                <td><?php echo $r->actividad; ?></td>
                <td><?php echo $r->area_zonal; ?></td>
                <td><?php echo $r->nivel; ?></td>
                <td><?php echo $r->permisoTrabajo; ?></td>
                <td><?php echo $r->horastrabajadas; ?></td>
                <td>
                    <?php
                    $canModify = isAdmin() || (isset($r->permiso_tipo) && $r->permiso_tipo === 'editar');
                ?>
                <?php if($canModify): ?>
                    <a href="/reportes/editar?id=<?php echo $r->id; ?>" class="btn amber darken-2 btn-small"><i class="material-icons left">edit</i>Editar</a>
                    <form method="POST" action="/reportes/eliminar" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $r->id; ?>">
                        <button type="submit" class="btn red btn-small"><i class="material-icons left">delete</i>Eliminar</button>
                    </form>
                <?php else: ?>
                    <!-- permiso insuficiente: no se muestra nada o podría mostrarse un guión -->
                <?php endif; ?>
                </td>
                <td>
                    <a href="/reportes/detalle?id=<?php echo $r->id; ?>" class="btn blue btn-small" title="Ver detalle"><i class="material-icons">visibility</i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

