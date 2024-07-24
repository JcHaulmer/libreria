<div class="container-fluid mb-4 contenidoVista text-center">
	<h1 class="display-4 fw-bold">Editoriales</h1>
	<h2 class="lead">Crear Editorial</h2>
</div>

<div class="container pb-6 pt-6 contenidoVista">

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/editorialAjax.php" method="POST" autocomplete="off">

		<input type="hidden" name="modulo_editorial" value="registrar">

		<div class="row">
			<div class="col-md-6 mb-3">
				<div class="form-group">
					<label for="editorial_nombre">Nombre</label>
					<input type="text" class="form-control" id="editorial_nombre" name="editorial_nombre" maxlength="40">
				</div>
			</div>
		</div>
		<div class="text-start">
			<button type="reset" class="btn btn-secondary">Limpiar</button>
			<button type="submit" class="btn btn-primary">Guardar</button>
		</div>
	</form>
</div>
