<h2 class="header center-align">Proyectos</h2>
<div class="right-align" style="margin-bottom:1rem;">
    <a href="/proyectos/crear" class="btn green btn-small"><i class="material-icons left">add</i>Nuevo proyecto</a>
</div>

<table class="striped highlight responsive-table">
    <thead>
        <tr><th>ID</th><th>Nombre</th><th>Ubicación</th><th>Inicio</th><th>Fin</th><th>Acciones</th></tr>
    </thead>
    <tbody>
        <?php foreach($proyectos as $p): ?>
            <tr>
                <td><?php echo $p->id; ?></td>
                <td><?php echo $p->nombre; ?></td>
                <td><?php echo $p->ubicacion; ?></td>
                <td><?php echo $p->fecha_inicio; ?></td>
                <td><?php echo $p->fecha_fin; ?></td>
                <td>
                    <a href="/proyectos/actualizar?id=<?php echo $p->id; ?>" class="btn amber darken-2 btn-small"><i class="material-icons left">edit</i>Editar</a>
                    <form method="POST" action="/proyectos/eliminar" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $p->id; ?>">
                        <button type="submit" class="btn red btn-small"><i class="material-icons left">delete</i>Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>