<h2>Mis Proyectos</h2>
<?php if(empty($permisos)): ?>
    <p>No tienes permisos asignados.</p>
<?php else: ?>
    <div class="responsive-table">
    <table class="tabla-datos">
        <thead>
            <tr>
                <th>Proyecto ID</th>
                <th>Nombre</th>
                <th>Disciplina</th>
                <th>Subdisciplina</th>
                <th>Permiso</th>
                <th>Empresa</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($permisos as $p): ?>
                <tr>
                    <td><?php echo $p->proyecto_id; ?></td>
                    <td><?php echo $p->proyecto_nombre; ?></td>
                    <td><?php echo $p->disciplina_nombre ?? '-'; ?></td>
                    <td><?php echo $p->subdisciplina_nombre ?? '-'; ?></td>
                    <td><?php echo $p->permiso; ?></td>
                    <td><?php echo $p->empresa_nombre; ?></td>
                    <td>
                    <?php if($p->permiso === 'editar'): ?>
                        <a href="/reportes/crear?proyecto_id=<?php echo $p->proyecto_id; ?>" class="btn green btn-small"><i class="material-icons left">add</i>Crear registro</a>
                    <?php else: ?>
                        <span class="texto-pequeno">(solo lectura)</span>
                    <?php endif; ?>
                </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
<?php endif; ?>