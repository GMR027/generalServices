<div class="row">
    <div class="col s12">
        <h2>Detalle Bitácora</h2>
        <p><strong>Fecha:</strong> <?php echo $bitacora->fecha; ?></p>
        <p><strong>Proyecto:</strong> <?php echo htmlspecialchars($bitacora->proyecto_nombre ?? ''); ?></p>
        <p><strong>Categoría:</strong> <?php echo htmlspecialchars($bitacora->categoria_nombre ?? ''); ?></p>
        <p><strong>Usuario:</strong> <?php echo htmlspecialchars($bitacora->usuario); ?></p>
        <p><strong>Nivel de importancia:</strong> <?php echo htmlspecialchars($bitacora->nivelImportancia); ?></p>
        <hr>
        <h5>Comentarios</h5>
        <p><?php echo nl2br(htmlspecialchars($bitacora->comentarios)); ?></p>
        <div class="right-align" style="margin-top:2rem;">
            <a href="/bitacora" class="btn grey"><i class="material-icons left">arrow_back</i>Volver</a>
       </div>
    </div>
</div>