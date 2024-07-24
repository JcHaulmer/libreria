<div class="container-fluid mb-4 contenidoVista text-center">
	<h1 class="display-4 fw-bold">Usuarios</h1>
	<h2 class="lead">Crear Usuario</h2>
</div>

<div class="container pb-6 pt-6 contenidoVista">

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" autocomplete="off">

		<input type="hidden" name="modulo_usuario" value="registrar">

		<div class="row">
			<div class="col-md-6 mb-3">
				<div class="form-group">
					<label for="usuario_nombre">Nombre</label>
					<input type="text" class="form-control" id="usuario_nombre" name="usuario_nombre" maxlength="40">
				</div>
			</div>
			<div class="col-md-6 mb-3">
				<div class="form-group">
					<label for="usuario_apellido">Apellido</label>
					<input type="text" class="form-control" id="usuario_apellido" name="usuario_apellido" maxlength="40">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 mb-3">
				<div class="form-group">
					<label for="usuario_usuario">Usuario</label>
					<input type="text" class="form-control" id="usuario_usuario" name="usuario_usuario" maxlength="20">
				</div>
			</div>
			<div class="col-md-6 mb-3">
				<div class="form-group">
					<label for="usuario_rol">Rol</label>
					<select class="form-control" id="usuario_rol" name="usuario_rol">
						<option value="" disabled selected>Seleccione un Rol</option>
						<option value="Jefe de Bodega">Jefe de Bodega</option>
						<option value="Bodeguero">Bodeguero</option>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 mb-3">
				<div class="form-group">
					<label for="usuario_clave_1">Clave</label>
					<input type="password" class="form-control" id="usuario_clave_1" name="usuario_clave_1" maxlength="40">
				</div>
			</div>
			<div class="col-md-6 mb-3">
				<div class="form-group">
					<label for="usuario_clave_2">Repetir clave</label>
					<input type="password" class="form-control" id="usuario_clave_2" name="usuario_clave_2" maxlength="40">
				</div>
			</div>
		</div>
		<div class="text-center mt-3">
			<button type="reset" class="btn btn-secondary">Limpiar</button>
			<button type="submit" class="btn btn-primary">Guardar</button>
		</div>
	</form>
</div>
