<?php
    require_once "../../config/app.php";
    require_once "../views/inc/session_start.php";
    require_once "../../autoload.php";

    use app\controllers\autorController;

    if(isset($_POST['modulo_autor'])){
        $insAutor = new autorController();

        if($_POST['modulo_autor']=="registrar"){
            echo $insAutor->registrarAutorControlador();
        }

        if($_POST['modulo_autor']=="eliminar"){
            echo $insAutor->eliminarAutorControlador();
        }

        if($_POST['modulo_autor']=="actualizar"){
            echo $insAutor->actualizarAutorControlador();
        }

    }else{
        session_destroy();
        header("Location: ".APP_URL."login/");
    }