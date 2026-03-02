<h2 class="header center-align">Subdisciplinas</h2>
<div class="right-align" style="margin-bottom:1rem;">
    <a href="/subdisciplinas/crear" class="btn green btn-small"><i class="material-icons left">add</i>Nueva subdisciplina</a>
</div>

<table class="striped highlight responsive-table">
    <thead>
        <tr><th>ID</th><th>Disciplina</th><th>Nombre</th><th>Acciones</th></tr>
    </thead>
    <tbody>
        <?php foreach($subdisciplinas as $s): ?>
            <tr>
                <td><?php echo $s->id; ?></td>
                <td><?php echo $disciplinaMap[$s->disciplina_id] ?? $s->disciplina_id; ?></td>
                <td><?php echo $s->nombre; ?></td>
                <td>
                    <a href="/subdisciplinas/actualizar?id=<?php echo $s->id; ?>" class="btn amber darken-2 btn-small"><i class="material-icons left">edit</i>Editar</a>
                    <form method="POST" action="/subdisciplinas/eliminar" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $s->id; ?>">
                        <button type="submit" class="btn red btn-small"><i class="material-icons left">delete</i>Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>