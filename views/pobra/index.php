<div class="row valign-wrapper" style="margin-bottom:1rem;">
    <div class="col s12 m6">
        <h2 class="left">Programa de Obra - Actividades</h2>
    </div>
    <div class="col s12 m6 right-align">
        <a href="/p-obra/crear" class="btn green btn-small"><i class="material-icons left">add</i>Nueva actividad</a>
    </div>
</div>

<form method="GET" action="/p-obra" class="formulario" style="margin-bottom:1rem;">
    <div class="input-field">
        <select name="proyecto_id[]" multiple>
            <option value="" disabled>Filtrar por proyecto</option>
            <?php foreach($filterProjects as $p): ?>
                <option value="<?php echo $p->id; ?>" <?php echo in_array($p->id, $selectedProjects) ? 'selected' : ''; ?>><?php echo htmlspecialchars($p->nombre); ?></option>
            <?php endforeach; ?>
        </select>
        <label>Proyecto</label>
    </div>
    <button type="submit" class="btn amber"><i class="material-icons left">filter_list</i>Filtrar</button>
</form>



<table class="striped highlight responsive-table">
    <thead>
        <tr>
            <th>Proyecto</th>
            <th>Item</th>
            <th class="col-actividad s6">Actividad</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Días</th>
            <th>% Avance</th>
            <th>Comentarios</th>
            <th>Gráfico</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($actividades as $a): ?>
            <tr>
                <td><?php echo htmlspecialchars($a->proyecto_nombre); ?></td>
                <td><?php echo $a->item; ?></td>
                <td class="col-actividad s6"><?php echo htmlspecialchars($a->actividad); ?></td>
                <td><?php echo $a->fecha_inicio; ?></td>
                <td><?php echo $a->fecha_fin; ?></td>
                <td><?php echo $a->dias; ?></td>
                <?php
                    $perc = intval($a->porcentajeAvance);
                    if($perc <= 30) {
                        $percClass = 'red-text';
                    } elseif($perc <= 90) {
                        $percClass = 'yellow-text text-darken-3';
                    } else {
                        $percClass = 'green-text';
                    }
                ?>
                <td class="<?php echo $percClass; ?>"><?php echo $perc; ?>%</td>
                <td><?php echo nl2br(htmlspecialchars($a->comentarios)); ?></td>
                <td style="width:150px;">
                    <div class="gantt-bar" style="width:<?php echo max(1,$a->dias) * 10; ?>px;
                         background:#2196F3; height:1rem;
                         position:relative;">
                        <span class="gantt-label" style="position:absolute;left:0;top:-1.2rem; font-size:.75rem;"><?php echo $a->dias; ?>d</span>
                    </div>
                </td>
                <td>
                    <a href="/p-obra/editar?id=<?php echo $a->id; ?>" class="btn amber darken-2 btn-small"><i class="material-icons left">edit</i>Editar</a>
                    <form method="POST" action="/p-obra/eliminar" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $a->id; ?>">
                        <button type="submit" class="btn red btn-small"><i class="material-icons left">delete</i>Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
