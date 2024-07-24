<div class="container-fluid mb-4 contenidoVista text-center">
    <h1 class="display-4 fw-bold">Productos</h1>
    <h2 class="lead">Listar Productos</h2>
</div>

<div class="container py-6 contenidoVista">
    <?php
        use app\controllers\productController;

        $insProducto = new productController();

        if(!isset($_SESSION[$url[0]]) || empty($_SESSION[$url[0]])) {
    ?>
    <div class="row mb-4">
        <div class="col">
            <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/buscadorAjax.php" method="POST" autocomplete="off">
                <input type="hidden" name="modulo_buscador" value="buscar">
                <input type="hidden" name="modulo_url" value="<?php echo $url[0]; ?>">
                <div class="input-group">
                    <input class="form-control rounded-pill" type="text" name="txt_buscador" placeholder="¿Qué estás buscando?" maxlength="40">
                    <div class="input-group-append">
                        <button class="btn btn-info" style="margin-left: 20px;" type="submit">Buscar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php }else{ ?>
    <div class="row mt-4 mb-3">
        <div class="col text-center">
            <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/buscadorAjax.php" method="POST" autocomplete="off">
                <input type="hidden" name="modulo_buscador" value="eliminar">
                <input type="hidden" name="modulo_url" value="<?php echo $url[0]; ?>">
                <p>Estás buscando <strong>“<?php echo $_SESSION[$url[0]]; ?>”</strong></p>
                <button type="submit" class="btn btn-danger rounded-pill mb-3">Eliminar búsqueda</button>
            </form>
        </div>
    </div>
    <?php
        } 
        if(!isset($_SESSION[$url[0]]) || empty($_SESSION[$url[0]])) { 
            echo $insProducto->listarProductoControlador($url[1],10,$url[0],""); 
        } else {
            echo $insProducto->listarProductoControlador($url[1],10,$url[0],$_SESSION[$url[0]]);
        }
    ?>
</div>