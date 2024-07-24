<div class="container col-xl-10 col-xxl-8 px-4 py-5">
    <div class="row align-items-center g-lg-5 py-5">
      <div class="col-lg-7 text-center text-lg-start">
        <h1 class="display-5 fw-bold lh-1 text-body-emphasis mb-3">Librería <strong>“El Gran Poeta”</strong></h1>
        <p class="col-lg-10 fs-4 text-center">Bienvenido a nuestro sistema de inventario! Ingresa tu usuario y contraseña para ingresar al sistema.</p>
      </div>
      <div class="col-md-10 mx-auto col-lg-5">
        <form class="p-4 p-md-5 border rounded-3 bg-body-tertiary" action="" method="POST" autocomplete="off">
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="floatingInput" name="login_usuario" placeholder="Usuario">
            <label for="floatingInput">Usuario</label>
          </div>
          <div class="form-floating mb-3">
            <input type="password" class="form-control" id="floatingPassword" name="login_clave" placeholder="Contraseña">
            <label for="floatingPassword">Contraseña</label>
          </div>
          <button class="w-100 btn btn-lg btn-primary" type="submit">Ingresar</button>
          <hr class="my-4">
          <small class="text-body-secondary">Si no recuerdas tu contraseña, debes contactar al administrador.</small>
        </form>
      </div>
    </div>
</div>

<?php
  if(isset($_POST['login_usuario']) && isset($_POST['login_clave'])){
    $insLogin->iniciarSesionControlador();
  }
?>