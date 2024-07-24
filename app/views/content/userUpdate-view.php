<div class="container-fluid contenidoVista text-center">
	<h1 class="display-4 fw-bold">Usuarios</h1>
	<h2 class="lead">Actualizar Usuario</h2>
</div>

<div class="container pb-6 pt-6 contenidoVista">
    <?php
        include "./app/views/inc/btnBack.php";

        $id = $insLogin->limpiarCadena($url[1]);
        $datos = $insLogin->seleccionarDatos("Unico","Usuarios","ID",$id);
        $usuario = $datos->fetch(PDO::FETCH_ASSOC);
        if($datos->rowCount()==1){
    ?>

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" autocomplete="off">

		<input type="hidden" name="modulo_usuario" value="actualizar">
        <input type="hidden" name="usuario_id" value="<?php echo $usuario['ID'] ?>">

		<div class="row">
			<div class="col-md-6 mb-3">
				<div class="form-group">
					<label for="usuario_nombre">Nombre</label>
					<input type="text" class="form-control" value="<?php echo $usuario['Nombre'] ?>" id="usuario_nombre" name="usuario_nombre" maxlength="40" >
				</div>
			</div>
			<div class="col-md-6 mb-3">
				<div class="form-group">
					<label for="usuario_apellido">Apellido</label>
					<input type="text" class="form-control" value="<?php echo $usuario['Apellido'] ?>" id="usuario_apellido" name="usuario_apellido" maxlength="40">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 mb-3">
				<div class="form-group">
					<label for="usuario_usuario">Usuario</label>
					<input type="text" class="form-control" value="<?php echo $usuario['Usuario'] ?>" id="usuario_usuario" name="usuario_usuario" maxlength="20">
				</div>
			</div>
			<div class="col-md-6 mb-3">
				<div class="form-group">
					<label for="usuario_rol">Rol</label>
					<select class="form-control" id="usuario_rol" name="usuario_rol">
                        <option value="" disabled>Seleccione un Rol</option>
                        <option value="Jefe de Bodega" <?php if ($usuario['Rol'] == 'Jefe de Bodega') echo 'selected'; ?>>Jefe de Bodega</option>
                        <option value="Bodeguero" <?php if ($usuario['Rol'] == 'Bodeguero') echo 'selected'; ?>>Bodeguero</option>
                    </select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 mb-3">
				<div class="form-group">
					<label for="usuario_clave_1">Clave</label>
					<input type="password" class="form-control" id="usuario_clave_1" name="usuario_clave_1" maxlength="40" placeholder="Nueva clave">
				</div>
			</div>
			<div class="col-md-6 mb-3">
				<div class="form-group">
					<label for="usuario_clave_2">Repetir clave</label>
					<input type="password" class="form-control" id="usuario_clave_2" name="usuario_clave_2" maxlength="40" placeholder="Repitir nueva clave">
				</div>
			</div>
		</div>
		<div class="text-center">
			<button type="submit" class="btn btn-success rounded-pill">Actualizar</button>
		</div>
	</form>
    <?php
        }else{
            include "./app/views/inc/errorAlert.php";
        }
    ?>

</div>