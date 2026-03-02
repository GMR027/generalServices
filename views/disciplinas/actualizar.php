<h2>Actualizar Disciplina</h2>

<?php foreach($errores as $error): ?>
    <div class="alerta error"><?php echo $error; ?></div>
<?php endforeach; ?>

<form method="POST" class="formulario">
    <label for="nombre">Nombre:</label>
    <input type="text" name="disciplina[nombre]" id="nombre" value="<?php echo $disciplina->nombre; ?>">
    <button type="submit" class="btn green"><i class="material-icons left">save</i>Actualizar</button>
</form>