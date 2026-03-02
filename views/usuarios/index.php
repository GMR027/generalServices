<h2 class="header center-align">Usuarios</h2>
<div class="right-align" style="margin-bottom:1rem;">
    <a href="/clientes/crear" class="btn green btn-small"><i class="material-icons left">person_add</i>Nuevo usuario</a>
</div>

<table class="striped highlight responsive-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Estatus</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($usuarios as $usr): ?>
            <tr>
                <td><?php echo $usr->id; ?></td>
                <td><?php echo $usr->nombre; ?></td>
                <td><?php echo $usr->correo; ?></td>
                <td><?php echo $usr->rol; ?></td>
                <td><?php echo $usr->estatus; ?></td>
                <td>
                    <a href="/clientes/actualizar?id=<?php echo $usr->id; ?>" class="btn amber darken-2 btn-small"><i class="material-icons left">edit</i>Editar</a>
                    <form method="POST" action="/clientes/eliminar" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $usr->id; ?>">
                        <button type="submit" class="btn red btn-small"><i class="material-icons left">delete</i>Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>