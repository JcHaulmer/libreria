<div class="container-fluid contenidoVista text-center">
	<h1 class="display-4 fw-bold">Bodegas</h1>
	<h2 class="lead">Actualizar Bodega</h2>
</div>

<div class="container pb-6 pt-6 contenidoVista">
    <?php
        include "./app/views/inc/btnBack.php";

        $id = $insLogin->limpiarCadena($url[1]);
        $datos = $insLogin->seleccionarDatos("Unico","Bodegas","ID",$id);
        $bodega = $datos->fetch(PDO::FETCH_ASSOC);
        if($datos->rowCount()==1){
    ?>

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/cellarAjax.php" method="POST" autocomplete="off">

		<input type="hidden" name="modulo_bodega" value="actualizar">
        <input type="hidden" name="bodega_id" value="<?php echo $bodega['ID'] ?>">

		<div class="row">
			<div class="col-md-6 mb-3">
				<div class="form-group">
					<label for="bodega_nombre">Nombre</label>
					<input type="text" class="form-control" value="<?php echo $bodega['Nombre'] ?>" id="bodega_nombre" name="bodega_nombre" maxlength="40" >
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