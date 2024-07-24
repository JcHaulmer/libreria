<?php
    require_once "./config/app.php";
    require_once "./autoload.php";
    require_once "./app/views/inc/session_start.php";

    use app\controllers\loginController;
    use app\controllers\viewsController;

    $insLogin = new loginController();

    # Verificar inicio de sesión #
    if (!isset($_SESSION['id']) || !isset($_SESSION['usuario'])) {
        $url = ["login"];
    } else {
        if(isset($_GET['views'])){
            $url = explode("/", $_GET['views']);
        } else {
            $url = ["dashboard"];
        }
    }

    # Verificar rol de usuario para obtener vista #
    $rol = $_SESSION['rol'];
    $viewsController = new viewsController();
    $vista = $viewsController->obtenerVistasControlador($url[0], $rol);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once "./app/views/inc/head.php"; ?>
</head>
<body>
    <?php  
        if($vista=="login" || $vista=="404"){
            require_once "./app/views/content/".$vista."-view.php";
        } else {
            echo '<div class="d-flex">';
            require_once "./app/views/inc/navbar.php";
            echo '<div class="content flex-grow-1">';
            require_once $vista;
            echo '</div></div>';
        }

        require_once "./app/views/inc/script.php";
    ?>
</body>
</html>
