<?php
    require_once "../../config/app.php";
    require_once "../views/inc/session_start.php";
    require_once "../../autoload.php";

    use app\controllers\EditorialController;

    if(isset($_POST['modulo_editorial'])){
        $insEditorial = new EditorialController();

        if($_POST['modulo_editorial']=="registrar"){
            echo $insEditorial->registrarEditorialControlador();
        }

        if($_POST['modulo_editorial']=="eliminar"){
            echo $insEditorial->eliminarEditorialControlador();
        }

        if($_POST['modulo_editorial']=="actualizar"){
            echo $insEditorial->actualizarEditorialControlador();
        }

    }else{
        session_destroy();
        header("Location: ".APP_URL."login/");
    }