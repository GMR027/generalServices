<h2 class="header center-align">Empresas</h2>
<div class="right-align" style="margin-bottom:1rem;">
    <a href="/empresas/crear" class="btn green"><i class="material-icons left">add</i>Nueva empresa</a>
</div>

<table class="striped highlight responsive-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Logo</th>
            <th>Nombre</th>
            <th>Cliente</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>RFC</th>
            <th>Dirección</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($empresas as $e): ?>
            <tr>
                <td><?php echo $e->id; ?></td>
                <td><?php if($e->logo):
                        $src = $e->logo;
                        if(strpos($src, '/') === false) {
                            $src = '/image/' . $src;
                        }
                    ?><img src="<?php echo $src; ?>" width="50" alt="logo"><?php endif; ?></td>
                <td><?php echo $e->nombre; ?></td>
                <td><?php echo $e->nombreCliente; ?></td>
                <td><?php echo $e->email; ?></td>
                <td><?php echo $e->telefono; ?></td>
                <td><?php echo $e->rfc; ?></td>
                <td><?php echo $e->direccion; ?></td>
                <td>
                    <a href="/empresas/actualizar?id=<?php echo $e->id; ?>" class="btn amber darken-2 btn-small"><i class="material-icons left">edit</i>Editar</a>
                    <form method="POST" action="/empresas/eliminar" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $e->id; ?>">
                        <button type="submit" class="btn red btn-small"><i class="material-icons left">delete</i>Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>