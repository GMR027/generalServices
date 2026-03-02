<?php use Model\Proyecto;
use Model\Disciplina;
use Model\Subdisciplina; ?>


<div class="container reporte-detalle">
    <h2>Detalle del Reporte #<?php echo $reporte->id; ?></h2>
    <?php if(isset($reporte->created_at)): ?>
        <p class="small">Creado: <?php echo date('d\/m\/Y', strtotime($reporte->created_at)); ?></p>
    <?php endif; ?>
    <div class="responsive-table">
    <table class="tabla-datos">
        <tr>
            <th>Proyecto</th>
            <th>Disciplina</th>
            <th>Subdisciplina</th>
            <th>Actividad</th>
            <th>Área/Zona</th>
            <th>Nivel</th>
            <th>Permiso trabajo</th>
            <th>Horas trabajadas</th>
        </tr>
        <tr>
            <?php /** @var Proyecto|null $proj */
            $proj = Proyecto::find($reporte->proyecto_id); ?>
            <td><?php echo $proj->nombre ?? '-'; ?></td>
            <td><?php echo Disciplina::find($proj->disciplina_id)->nombre ?? '-'; ?></td>
            <td><?php echo Subdisciplina::find($proj->subdisciplina_id)->nombre ?? '-'; ?></td>
            <td><?php echo $reporte->actividad; ?></td>
            <td><?php echo $reporte->area_zonal; ?></td>
            <td><?php echo $reporte->nivel; ?></td>
            <td><?php echo $reporte->permisoTrabajo; ?></td>
            <td><?php echo $reporte->horastrabajadas; ?></td>
        </tr>
    </table>
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

    <!-- button for PDF export -->
    <button id="downloadPdf" class="btn blue" style="margin-top:1rem;
           white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
        <i class="material-icons left">picture_as_pdf</i>
        Descargar PDF
    </button>

    <!-- image modal -->
    <div id="imgModal" class="img-modal">
        <div class="img-wrapper">
            <span id="modalClose" class="modal-close">&times;</span>
            <img id="modalImg" class="modal-content" src="" alt="imagen ampliada">
        </div>
    </div>
</div>

<!-- include html2pdf (CDN with local fallback) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js" onerror="this.onerror=null;this.src='/js/html2pdf.bundle.min.js'"></script>
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

    const pdfBtn = document.getElementById('downloadPdf');
    if (pdfBtn) {
        pdfBtn.addEventListener('click', () => {
            if (typeof html2pdf === 'undefined') {
                alert('No se ha podido cargar la librería de generación de PDF. Intente nuevamente o contacte al administrador.');
                return;
            }
            const element = document.querySelector('.reporte-detalle');
            if (!element) return;
            const clone = element.cloneNode(true);
            // quitar botones/modales del clon para evitar interferencias
            const btn = clone.querySelector('#downloadPdf');
            if (btn) btn.remove();
            const modalClone = clone.querySelector('#imgModal');
            if (modalClone) modalClone.remove();

            // reorganizar tabla (si hay dos filas) dentro de un try para no bloquear la generación
            try {
                const infoTable = clone.querySelector('.tabla-datos');
                if (infoTable) {
                    const firstRow = infoTable.rows[0];
                    if (firstRow && infoTable.rows.length === 2) {
                        const values = Array.from(infoTable.rows[1].cells);
                        infoTable.innerHTML = '';
                        const row1 = infoTable.insertRow();
                        ['Proyecto','Disciplina','Subdisciplina','Área/Zona','Nivel','Permiso trabajo'].forEach(h => {
                            const th = document.createElement('th'); th.textContent = h; row1.appendChild(th);
                        });
                        const row1vals = infoTable.insertRow();
                        [values[0],values[1],values[2],values[4],values[5],values[6]].forEach(v => row1vals.appendChild(v));
                        const row2 = infoTable.insertRow();
                        ['Actividad','Horas trabajadas'].forEach(h=>{const th=document.createElement('th');th.textContent=h;row2.appendChild(th);});
                        const row2vals = infoTable.insertRow();
                        [values[3],values[7]].forEach(v=>row2vals.appendChild(v));
                    }
                }
            } catch(e) {
                console.error('Error reorganizando tabla para PDF:', e);
            }

            const opt = {
                margin:       0.5,
                filename:     'reporte-<?php echo $reporte->id; ?>.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            html2pdf().set(opt).from(clone).save();
        });
    }
});
</script>