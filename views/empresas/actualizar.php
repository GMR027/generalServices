<h2>Actualizar Empresa</h2>

<?php foreach($errores as $error): ?>
    <div class="alerta error"><?php echo $error; ?></div>
<?php endforeach; ?>

<form method="POST" class="formulario" enctype="multipart/form-data">
    <label for="nombre">Nombre:</label>
    <input type="text" name="empresa[nombre]" id="nombre" value="<?php echo $empresa->nombre; ?>">

    <label for="nombreCliente">Nombre Cliente:</label>
    <input type="text" name="empresa[nombreCliente]" id="nombreCliente" value="<?php echo $empresa->nombreCliente; ?>">

    <label for="email">Email:</label>
    <input type="email" name="empresa[email]" id="email" value="<?php echo $empresa->email; ?>">

    <label for="telefono">Teléfono:</label>
    <input type="text" name="empresa[telefono]" id="telefono" value="<?php echo $empresa->telefono; ?>">

    <label for="logo">Logo:</label>
    <input type="file" name="logo" id="logo">
    <?php if($empresa->logo): ?>
        <?php
            $src = $empresa->logo;
            // si el valor ya contiene una barra asumimos ruta completa
            if(strpos($src, '/') === false) {
                $src = '/image/' . $src;
            }
        ?>
        <img src="<?php echo $src; ?>" width="100" alt="logo">
    <?php endif; ?>

    <label for="rfc">RFC:</label>
    <input type="text" name="empresa[rfc]" id="rfc" value="<?php echo $empresa->rfc; ?>">

    <label for="direccion">Dirección:</label>
    <textarea name="empresa[direccion]" id="direccion"><?php echo $empresa->direccion; ?></textarea>

    <button type="submit" class="btn green"><i class="material-icons left">save</i>Actualizar</button>
</form>