<h2 class="header center-align">Permisos</h2>
<div class="right-align" style="margin-bottom:1rem;">
    <a href="/permisos/crear" class="btn green">Asignar permisos</a>
</div>

<table class="striped highlight responsive-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Proyecto</th>
            <th>Empresa</th>
            <th>Permiso</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($permisos as $p): ?>
            <tr>
                <td><?php echo $p->id; ?></td>
                <td><?php echo $p->cliente_nombre ?? $p->cliente_id; ?></td>
                <td><?php echo $p->proyecto_nombre ?? $p->proyecto_id; ?></td>
                <td><?php echo $p->empresa_nombre ?? $p->empresa_id; ?></td>
                <td><?php echo $p->permiso; ?></td>
                <td>
                    <a href="/permisos/actualizar?id=<?php echo $p->id; ?>" class="btn amber darken-2 btn-small"><i class="material-icons left">edit</i>Editar</a>
                    <form method="POST" action="/permisos/eliminar" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $p->id; ?>">
                        <button type="submit" class="btn red btn-small"><i class="material-icons left">delete</i>Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>