<div class="row valign-wrapper" style="margin-bottom:1rem;">
    <div class="col s12 m6">
        <h2 class="left">Bitácora</h2>
    </div>
    <div class="col s12 m6 right-align">
        <a href="/bitacora/crear" class="btn green btn-small"><i class="material-icons left">add</i>Nueva entrada</a>
    </div>
</div>

<form method="GET" action="/bitacora" class="formulario row" style="margin-bottom:1rem;">
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
            <th>Fecha</th>
            <th>Proyecto</th>
            <th>Categoría</th>
            <th>Usuario</th>
            <th>Importancia</th>
            <th>Comentarios</th>
            <th>Detalle</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($bitacoras as $b): ?>
            <tr>
                <td><?php echo $b->fecha; ?></td>
                <td><?php echo htmlspecialchars($b->proyecto_nombre); ?></td>
                <td><?php echo htmlspecialchars($b->categoria_nombre); ?></td>
                <td><?php echo htmlspecialchars($b->usuario); ?></td>
                <?php
                    $niv = $b->nivelImportancia;
                    if($niv == 1) {
                        $label = 'Bajo';
                        $class = 'green lighten-4';
                    } elseif($niv == 2) {
                        $label = 'Medio';
                        $class = 'yellow lighten-4';
                    } elseif($niv == 3) {
                        $label = 'Alto';
                        $class = 'red lighten-4';
                    } else {
                        $label = $niv;
                        $class = '';
                    }
                ?>
                <td class="<?php echo $class; ?>"><?php echo $label; ?></td>
                <td><?php echo nl2br(htmlspecialchars(
                    strlen($b->comentarios) > 60 ? substr($b->comentarios,0,60) . '...' : $b->comentarios
                )); ?></td>
                <td>
                    <a href="/bitacora/detalle?id=<?php echo $b->id; ?>" class="btn blue btn-small"><i class="material-icons left">visibility</i>Ver detalle</a>
                </td>
                <td>
                    <a href="/bitacora/editar?id=<?php echo $b->id; ?>" class="btn amber darken-2 btn-small"><i class="material-icons left">edit</i>Editar</a>
                    <form method="POST" action="/bitacora/eliminar" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $b->id; ?>">
                        <button type="submit" class="btn red btn-small"><i class="material-icons left">delete</i>Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
