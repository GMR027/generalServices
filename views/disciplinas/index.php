<h2 class="header center-align">Disciplinas</h2>
<div class="right-align" style="margin-bottom:1rem;">
    <a href="/disciplinas/crear" class="btn green btn-small"><i class="material-icons left">add</i>Nueva disciplina</a>
    <a href="/subdisciplinas" class="btn amber darken-2 btn-small"><i class="material-icons left">list</i>Subdisciplinas</a>
</div>

<table class="striped highlight responsive-table">
    <thead>
        <tr><th>ID</th><th>Nombre</th><th>Acciones</th></tr>
    </thead>
    <tbody>
        <?php foreach($disciplinas as $d): ?>
            <tr>
                <td><?php echo $d->id; ?></td>
                <td><?php echo $d->nombre; ?></td>
                <td>
                    <a href="/disciplinas/actualizar?id=<?php echo $d->id; ?>" class="btn amber darken-2 btn-small"><i class="material-icons left">edit</i>Editar</a>
                    <form method="POST" action="/disciplinas/eliminar" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $d->id; ?>">
                        <button type="submit" class="btn red btn-small"><i class="material-icons left">delete</i>Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>