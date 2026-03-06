<?php use Model\Proyecto; ?>



<div class="container reporte-detalle">
    <h2>Detalle del Reporte #<?php echo $reporte->id; ?></h2>
    <?php if(isset($reporte->created_at)): ?>
        <p class="small">Creado: <?php echo date('d\/m\/Y', strtotime($reporte->created_at)); ?></p>
    <?php endif; ?>
    <?php if(isset($reporte->subdisciplina_nombre)): ?>
        <p class="small">Subdisciplina: <?php echo htmlspecialchars($reporte->subdisciplina_nombre); ?></p>
    <?php endif; ?>
    <div class="responsive-table">
    <table class="tabla-datos">
        <tr>
            <th>Proyecto</th>
            <th>Área/Zona</th>
            <th>Nivel</th>
            <th>Permiso trabajo</th>
            <th>Horas trabajadas</th>
        </tr>
        <tr>
            <?php /** @var Proyecto|null $proj */
            $proj = Proyecto::find($reporte->proyecto_id); ?>
            <td><?php echo $proj->nombre ?? '-'; ?></td>
            <td><?php echo $reporte->area_zonal; ?></td>
            <td><?php echo $reporte->nivel; ?></td>
            <td><?php echo $reporte->permisoTrabajo; ?></td>
            <td><?php echo $reporte->horastrabajadas; ?></td>
        </tr>
    </table>
    </div>

    <div class="actividad-section" style="margin-top:1rem;">
        <h3>Actividad</h3>
        <p><?php echo nl2br(htmlspecialchars($reporte->actividad)); ?></p>
    </div>

    <?php if($reporte->imagenes):
        $imgs = json_decode($reporte->imagenes, true);
        if(is_array($imgs) && count($imgs) > 0): ?>
        <h3>Imágenes</h3>
        <div class="galeria">
            <?php foreach($imgs as $idx => $imgName): ?>
                <img class="clickable-img" src="/image/<?php echo $imgName; ?>" alt="imagen <?php echo $idx+1; ?>">
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php endif; ?>


    <!-- image modal -->
    <div id="imgModal" class="img-modal">
        <div class="img-wrapper">
            <span id="modalClose" class="modal-close">&times;</span>
            <img id="modalImg" class="modal-content" src="" alt="imagen ampliada">
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // lightbox images
    document.querySelectorAll('.clickable-img').forEach(img => {
        img.addEventListener('click', () => {
            const modal = document.getElementById('imgModal');
            const modalImg = document.getElementById('modalImg');
            modal.style.display = 'flex';
            modalImg.src = img.src;
        });
    });
    const modal = document.getElementById('imgModal');
    document.getElementById('modalClose').addEventListener('click', () => {
        modal.style.display = 'none';
    });
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});
</script>