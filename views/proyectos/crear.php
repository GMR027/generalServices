<h2>Crear Proyecto</h2>

<?php foreach($errores as $error): ?>
    <div class="alerta error"><?php echo $error; ?></div>
<?php endforeach; ?>

<form method="POST" class="formulario">
    <label for="nombre">Nombre:</label>
    <input type="text" name="proyecto[nombre]" id="nombre" value="<?php echo $proyecto->nombre; ?>">

    <label for="ubicacion">Ubicación:</label>
    <input type="text" name="proyecto[ubicacion]" id="ubicacion" value="<?php echo $proyecto->ubicacion; ?>">


    <label for="fecha_inicio">Fecha inicio:</label>
    <input type="date" name="proyecto[fecha_inicio]" id="fecha_inicio" value="<?php echo $proyecto->fecha_inicio; ?>">

    <label for="fecha_fin">Fecha fin:</label>
    <input type="date" name="proyecto[fecha_fin]" id="fecha_fin" value="<?php echo $proyecto->fecha_fin; ?>">

    <button type="submit" class="btn green"><i class="material-icons left">save</i>Guardar</button>
</form>