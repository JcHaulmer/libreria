<div class="container-fluid mb-4 contenidoVista text-center">
    <h1 class="display-4 fw-bold">Bodega</h1>
    <h2 class="lead">Ingresar Productos</h2>
</div>

<div class="container pb-6 pt-6 contenidoVista">
    <?php
        use app\models\mainModel;

        $insModel = new mainModel();
        $bodegas = $insModel->seleccionarDatos("Normal", "Bodegas");
        $bodegas = $bodegas->fetchAll(PDO::FETCH_ASSOC);
        $productos = $insModel->seleccionarDatos("Normal", "Productos");
        $productos = $productos->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/cellarAjax.php" method="POST" autocomplete="off">
        <input type="hidden" name="modulo_bodega" value="productoenbodega">

        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <label for="bodega_bodega">Bodega</label>
                    <select class="form-control" id="bodega_bodega" name="bodega_bodega">
                        <option value="" disabled selected>Seleccione una Bodega</option>
                        <?php foreach ($bodegas as $bodega): ?>
                            <option value="<?php echo $bodega['ID']; ?>"><?php echo $bodega['Nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div id="listado_productos"></div>
        <div class="text-center">
            <button type="button" class="btn btn-secondary" onclick="agregarProducto()">Agregar Producto</button>
            <button type="reset" class="btn btn-secondary">Limpiar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>
</div>

<script>
    function agregarProducto() {
        const idUnico = Date.now();
        const nuevoProducto = `
            <div class="row" id="producto_${idUnico}">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="bodega_producto_${idUnico}">Producto</label>
                        <select class="form-control" id="bodega_producto_${idUnico}" name="bodega_producto[]" required>
                            <option value="" disabled selected>Seleccione un Producto</option>
                            <?php foreach ($productos as $producto): ?>
                                <option value="<?php echo $producto['ID']; ?>"><?php echo $producto['Nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="bodega_productoCantidad_${idUnico}">Cantidad</label>
                        <input type="number" class="form-control" id="bodega_productoCantidad_${idUnico}" name="bodega_productoCantidad[]" min="1" required>
                    </div>
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="button" class="btn btn-danger" onclick="eliminarProducto(${idUnico})">Eliminar</button>
                </div>
            </div>
        `;
        document.getElementById('listado_productos').insertAdjacentHTML('beforeend', nuevoProducto);
    }
</script>