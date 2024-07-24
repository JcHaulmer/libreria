<div class="container-fluid mb-4 contenidoVista text-center">
    <h1 class="display-4 fw-bold">Movimientos</h1>
    <h2 class="lead">Listar Movimientos</h2>
</div>

<div class="container py-6 contenidoVista">
    <?php
        use app\controllers\movementController;

        $insProducto = new movementController();

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
    <div class="row mt-4">
        <div class="col text-center">
            <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/buscadorAjax.php" method="POST" autocomplete="off">
                <input type="hidden" name="modulo_buscador" value="eliminar">
                <input type="hidden" name="modulo_url" value="<?php echo $url[0]; ?>">
                <p>Estas buscando <strong>“<?php echo $_SESSION[$url[0]]; ?>”</strong></p>
                <button type="submit" class="btn btn-danger rounded-pill mb-3">Eliminar búsqueda</button>
            </form>
        </div>
    </div>
    <?php
        } 
        if(!isset($_SESSION[$url[0]]) || empty($_SESSION[$url[0]])) { 
            echo $insProducto->listarMovimientoControlador($url[1],9,$url[0],""); 
        } else {
            echo $insProducto->listarMovimientoControlador($url[1],9,$url[0],$_SESSION[$url[0]]);
        }
    ?>
</div>
<!-- Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="productModalLabel">Productos del Movimiento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="modalContent">
            <!-- Listado de productos -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>