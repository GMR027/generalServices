<div class="row valign-wrapper" style="margin-bottom:1rem;">
    <div class="col s12 m6">
        <h2 class="left">Minutas</h2>
    </div>
    <?php if(usuarioActual()): ?>
    <div class="col s12 m6 right-align">
        <a href="/minutas/crear" class="btn green btn-small"><i class="material-icons left">add</i>Nuevo registro</a>
    </div>
    <?php endif; ?>
</div>

<form method="GET" action="/minutas" class="formulario row" style="margin-bottom:1rem;">
    <div class="input-field col s12 m8">
        <select name="proyecto_id[]" multiple>
            <option value="" disabled>Filtrar por proyecto</option>
            <?php foreach($filterProjects as $p): ?>
                <option value="<?php echo $p->id; ?>" <?php echo in_array($p->id, $selectedProjects) ? 'selected' : ''; ?>><?php echo htmlspecialchars($p->nombre); ?></option>
            <?php endforeach; ?>
        </select>
        <label>Proyecto</label>
    </div>
    <div class="col s12 m4" style="margin-top:1.5rem;">
        <button type="submit" class="btn amber"><i class="material-icons left">filter_list</i>Filtrar</button>
    </div>
</form>

<table class="striped highlight responsive-table">
    <thead>
        <tr>
            <th>Proyecto</th>
            <th>Fecha</th>
            <th>Título</th>
            <th>Personal junta</th>
            <th>Personal cliente</th>
            <th>Categoría</th>
            <th>Información</th>
            <th>Compromisos</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($minutas as $m): ?>
            <tr>
                <td><?php echo htmlspecialchars($m->proyecto_nombre ?? ''); ?></td>
                <td><?php echo $m->fecha; ?></td>
                <td><?php echo htmlspecialchars($m->tituloJunta); ?></td>
                <td><?php echo htmlspecialchars($m->personalJunta); ?></td>
                <td><?php echo htmlspecialchars($m->personalCliente); ?></td>
                <td><?php echo htmlspecialchars($m->categoriaJunta); ?></td>
                <td><?php echo nl2br(htmlspecialchars(
                    strlen($m->informacionJunta) > 60 ? substr($m->informacionJunta,0,60) . '...' : $m->informacionJunta
                )); ?></td>
                <td><?php echo nl2br(htmlspecialchars(
                    strlen($m->compromisos) > 60 ? substr($m->compromisos,0,60) . '...' : $m->compromisos
                )); ?></td>
                <td>
                    <a href="/minutas/detalle?id=<?php echo $m->id; ?>" class="btn blue btn-small"><i class="material-icons left">visibility</i>Ver detalle</a>
                    <a href="/minutas/actualizar?id=<?php echo $m->id; ?>" class="btn amber darken-2 btn-small"><i class="material-icons left">edit</i>Editar</a>
                    <form method="POST" action="/minutas/eliminar" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $m->id; ?>">
                        <button type="submit" class="btn red btn-small"><i class="material-icons left">delete</i>Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('select');
    M.FormSelect.init(elems);
});
</script>
