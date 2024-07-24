<?php
    require_once "../../config/app.php";
    require_once "../views/inc/session_start.php";
    require_once "../../autoload.php";

    use app\controllers\cellarController;

    if(isset($_POST['modulo_bodega'])){
        $insBodega = new cellarController();

        if($_POST['modulo_bodega']=="registrar"){
            echo $insBodega->registrarBodegaControlador();
        }

        if($_POST['modulo_bodega']=="eliminar"){
            echo $insBodega->eliminarBodegaControlador();
        }

        if($_POST['modulo_bodega']=="actualizar"){
            echo $insBodega->actualizarBodegaControlador();
        }

        if($_POST['modulo_bodega']=="productoenbodega"){
            echo $insBodega->registrarProductoEnBodegaControlador();
        }

    }else{
        session_destroy();
        header("Location: ".APP_URL."login/");
    }