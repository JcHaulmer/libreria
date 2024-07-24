<div class="container-fluid mb-4 contenidoVista text-center">
	<h1 class="display-4 fw-bold">Productos</h1>
	<h2 class="lead">Crear Producto</h2>
</div>

<div class="container pb-6 pt-6 contenidoVista">
    <?php
        use app\models\mainModel;

        $insModel = new mainModel();
        $autores = $insModel->seleccionarDatos("Normal","Autores");
        $autores = $autores->fetchAll(PDO::FETCH_ASSOC);
        $editoriales = $insModel->seleccionarDatos("Normal","Editoriales");
        $editoriales = $editoriales->fetchAll(PDO::FETCH_ASSOC);
    ?>

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/productAjax.php" method="POST" autocomplete="off">

		<input type="hidden" name="modulo_producto" value="registrar">

		<div class="row">
			<div class="col-md-6 mb-3">
				<div class="form-group">
					<label for="producto_nombre">Nombre</label>
					<input type="text" class="form-control" id="producto_nombre" name="producto_nombre" maxlength="40">
				</div>
			</div>
			<div class="col-md-6 mb-3">
				<div class="form-group">
					<label for="producto_descripcion">Descripcion</label>
					<input type="text" class="form-control" id="producto_descripcion" name="producto_descripcion" maxlength="40">
				</div>
			</div>
		</div>
        <div class="row">
            <div class="col-md-6 mb-3">
				<div class="form-group">
					<label for="producto_autor">Autor</label>
					<select class="form-control" id="producto_autor" name="producto_autor">
						<option value="" disabled selected>Seleccione un Autor</option>
						<?php foreach ($autores as $autor): ?>
							<option value="<?php echo $autor['ID']; ?>"><?php echo $autor['Nombre']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-md-6 mb-3">
				<div class="form-group">
					<label for="producto_editorial">Editorial</label>
					<select class="form-control" id="producto_editorial" name="producto_editorial">
						<option value="" disabled selected>Seleccione una Editorial</option>
						<?php foreach ($editoriales as $editorial): ?>
							<option value="<?php echo $editorial['ID']; ?>"><?php echo $editorial['Nombre']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
            <div class="col-md-6 mb-3">
				<div class="form-group">
					<label for="producto_tipo">Tipo</label>
					<select class="form-control" id="producto_tipo" name="producto_tipo">
						<option value="" disabled selected>Seleccione un Tipo</option>
						<option value="Libro">Libro</option>
						<option value="Revista">Revista</option>
                        <option value="Enciclopedia">Enciclopedia</option>
					</select>
				</div>
			</div>
		</div>
		<div class="text-center">
			<button type="reset" class="btn btn-secondary">Limpiar</button>
			<button type="submit" class="btn btn-primary">Guardar</button>
		</div>
	</form>
</div>
