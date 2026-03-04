<div class="row">
    <div class="col s12">
        <h2>Minuta: <?php echo htmlspecialchars($minuta->tituloJunta); ?></h2>
        <p><strong>Proyecto:</strong> <?php echo htmlspecialchars($proyecto->nombre ?? ''); ?></p>
        <p><strong>Fecha:</strong> <?php echo $minuta->fecha; ?></p>
        <p><strong>Personal de la junta:</strong><br><?php echo nl2br(htmlspecialchars($minuta->personalJunta)); ?></p>
        <p><strong>Personal cliente:</strong><br><?php echo nl2br(htmlspecialchars($minuta->personalCliente)); ?></p>
        <p><strong>Categoría de la junta:</strong> <?php echo htmlspecialchars($minuta->categoriaJunta); ?></p>
        <hr>
        <h5>Información de la junta</h5>
        <p><?php echo nl2br(htmlspecialchars($minuta->informacionJunta)); ?></p>
        <hr>
        <h5>Compromisos</h5>
        <p><?php echo nl2br(htmlspecialchars($minuta->compromisos)); ?></p>
        <div class="right-align" style="margin-top:2rem;">
            <a href="/minutas" class="btn grey"><i class="material-icons left">arrow_back</i>Volver</a>
        </div>
    </div>
</div>