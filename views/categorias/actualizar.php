<h2>Actualizar Categoría</h2>

<?php if(!empty($errores)): ?>
    <?php foreach($errores as $error): ?>
        <div class="alerta error"><?php echo $error; ?></div>
    <?php endforeach; ?>
<?php endif; ?>

<form method="POST" action="/categorias/actualizar?id=<?php echo $categoria->id; ?>" class="formulario">
    <div class="input-field">
        <input type="text" id="nombre" name="categoria[nombre]" class="validate" required value="<?php echo htmlspecialchars($categoria->nombre); ?>">
        <label for="nombre" class="active">Nombre</label>
    </div>
    <button type="submit" class="btn green"><i class="material-icons left">save</i>Actualizar categoría</button>
</form>
