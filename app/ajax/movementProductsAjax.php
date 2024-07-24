<?php

require_once "../../config/app.php";
require_once "../views/inc/session_start.php";
require_once "../../autoload.php";
require_once "../../app/controllers/movementController.php";
use app\controllers\movementController;

if(isset($_POST['id'])) {
    $movementId = $_POST['id'];
    $insProducto = new movementController();
    echo $insProducto->listarProductosPorMovimiento($movementId);
}