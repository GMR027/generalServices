<h2 class="header center-align">Categorías</h2>
<div class="right-align" style="margin-bottom:1rem;">
    <a href="/categorias/crear" class="btn green btn-small"><i class="material-icons left">add</i>Nueva categoría</a>
</div>

<table class="striped highlight responsive-table">
    <thead>
        <tr><th>ID</th><th>Nombre</th><th>Acciones</th></tr>
    </thead>
    <tbody>
        <?php foreach($categorias as $c): ?>
            <tr>
                <td><?php echo $c->id; ?></td>
                <td><?php echo $c->nombre; ?></td>
                <td>
                    <a href="/categorias/actualizar?id=<?php echo $c->id; ?>" class="btn amber darken-2 btn-small"><i class="material-icons left">edit</i>Editar</a>
                    <form method="POST" action="/categorias/eliminar" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $c->id; ?>">
                        <button type="submit" class="btn red btn-small"><i class="material-icons left">delete</i>Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
