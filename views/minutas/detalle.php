<div class="row minuta-detalle">
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
        <?php if(!isset($_GET['print'])): ?>
        <div class="right-align" style="margin-top:2rem;">
            <?php if(esAdmin()): ?>
                <a href="/minutas/detalle?id=<?php echo $minuta->id; ?>&print=1" target="_blank" class="btn blue" style="white-space: nowrap;">
                    <i class="material-icons left">picture_as_pdf</i>Exportar PDF
                </a>
            <?php endif; ?>
            <a href="/minutas" class="btn grey"><i class="material-icons left">arrow_back</i>Volver</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if(isset($_GET['print'])): ?>
    <script>
        window.onload = () => { window.print(); };
    </script>
<?php endif; ?>