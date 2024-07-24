<div class="container col-xxl-8 px-4 py-5 contenidoVista">
    <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
        <div class="col-12 col-sm-8 col-lg-6">
            <img src="<?php echo APP_URL; ?>app/views/img/user.png" class="d-block mx-lg-auto img-fluid" alt="Imagen de usuario" width="700" height="500" loading="lazy">
        </div>
        <div class="col-12 col-lg-6 text-center">
            <h1 class="display-2 fw-bold text-body-emphasis lh-1 mb-3">Bienvenido <?php echo $_SESSION['nombre']." ".$_SESSION['apellido'] ?>!</h1>
            <p class="lead">Ingresa a las secciones disponibles para tu rol</p>
        </div>
    </div>
</div>