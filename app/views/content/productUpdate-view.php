<div class="container-fluid contenidoVista text-center">
	<h1 class="display-4 fw-bold">Productos</h1>
	<h2 class="lead">Actualizar Producto</h2>
</div>

<div class="container pb-6 pt-6 contenidoVista">
    <?php
        include "./app/views/inc/btnBack.php";
		use app\models\mainModel;

        $insModel = new mainModel();
		$autores = $insModel->seleccionarDatos("Normal","Autores");
        $autores = $autores->fetchAll(PDO::FETCH_ASSOC);
        $editoriales = $insModel->seleccionarDatos("Normal","Editoriales");
        $editoriales = $editoriales->fetchAll(PDO::FETCH_ASSOC);

        $id = $insLogin->limpiarCadena($url[1]);
        $datos = $insLogin->seleccionarDatos("Unico","Productos","ID",$id);
        $producto = $datos->fetch(PDO::FETCH_ASSOC);
        if($datos->rowCount()==1){
    ?>

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/productAjax.php" method="POST" autocomplete="off">

		<input type="hidden" name="modulo_producto" value="actualizar">
        <input type="hidden" name="producto_id" value="<?php echo $producto['ID'] ?>">

		<div class="row">
			<div class="col-md-6 mb-3">
				<div class="form-group">
					<label for="producto_nombre">Nombre</label>
					<input type="text" class="form-control" value="<?php echo $producto['Nombre'] ?>" id="producto_nombre" name="producto_nombre" maxlength="40" >
				</div>
			</div>
			<div class="col-md-6 mb-3">
				<div class="form-group">
					<label for="producto_descripcion">Descripcion</label>
					<input type="text" class="form-control" value="<?php echo $producto['Descripcion'] ?>" id="producto_descripcion" name="producto_descripcion" maxlength="40">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 mb-3">
				<div class="form-group">
					<label for="producto_autor">Autor</label>
					<select class="form-control" id="producto_autor" name="producto_autor">
						<option value="" disabled>Seleccione un Autor</option>
						<?php foreach ($autores as $autor): ?>
							<option value="<?php echo $autor['ID']; ?>" <?php if ($autor['ID'] == $producto['ID_Autor']) echo 'selected'; ?> >
							<?php echo $autor['Nombre']; ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-md-6 mb-3">
				<div class="form-group">
					<label for="producto_editorial">Editorial</label>
					<select class="form-control" id="producto_editorial" name="producto_editorial">
						<option value="" disabled>Seleccione un Editorial</option>
						<?php foreach ($editoriales as $editorial): ?>
							<option value="<?php echo $editorial['ID']; ?>" <?php if ($editorial['ID'] == $producto['ID_Editorial']) echo 'selected'; ?> >
							<?php echo $editorial['Nombre']; ?>
							</option>
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
						<option value="" disabled>Seleccione un Tipo</option>
						<option value="Libro"  <?php if ($producto['Tipo'] == 'Libro') echo 'selected'; ?> >Libro</option>
						<option value="Revista"  <?php if ($producto['Tipo'] == 'Revista') echo 'selected'; ?> >Revista</option>
                        <option value="Enciclopedia"  <?php if ($producto['Tipo'] == 'Enciclopedia') echo 'selected'; ?> >Enciclopedia</option>
					</select>
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