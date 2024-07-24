<div class="d-flex flex-column flex-shrink-0 p-3 bg-dark text-white sidebar" style="width: 280px; height: 100vh;">
  <a href="<?php echo APP_URL; ?>dashboard/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
    <img src="<?php echo APP_URL; ?>app/views/img/logo.png" alt="Logo" width="35" height="35" class="me-2">
    <span class="fs-3">El Gran Poeta</span>
  </a>
  <hr>
  <ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
      <a href="<?php echo APP_URL; ?>dashboard/" class="nav-link <?php echo ($url[0] == 'dashboard') ? 'active' : 'text-white'; ?>" aria-current="page">
        <i class="bi bi-house-door"></i>
        Home
      </a>
    </li>
    <li class="<?php if($rol!="Administrador") echo "hidden"; ?>">
      <a href="<?php echo APP_URL; ?>userNew/" class="nav-link <?php echo ($url[0] == 'userNew') ? 'active' : 'text-white'; ?>">
        <i class="bi bi-person-plus-fill"></i>
        Crear Usuario
      </a>
    </li>
    <li class="<?php if($rol!="Administrador") echo "hidden"; ?>">
      <a href="<?php echo APP_URL; ?>userList/" class="nav-link <?php echo ($url[0] == 'userList') ? 'active' : 'text-white'; ?>">
        <i class="bi bi-person-lines-fill"></i>
        Listar Usuarios
      </a>
    </li>
    <li class="<?php if($rol=="Bodeguero") echo "hidden"; ?>">
      <a href="<?php echo APP_URL; ?>autorNew/" class="nav-link <?php echo ($url[0] == 'autorNew') ? 'active' : 'text-white'; ?>">
        <i class="bi bi-journal-plus"></i>
        Crear Autor
      </a>
    </li>
    <li class="<?php if($rol=="Bodeguero") echo "hidden"; ?>">
      <a href="<?php echo APP_URL; ?>autorList/" class="nav-link <?php echo ($url[0] == 'autorList') ? 'active' : 'text-white'; ?>">
        <i class="bi bi-journal-text"></i>
        Listar Autores
      </a>
    </li>
    <li class="<?php if($rol=="Bodeguero") echo "hidden"; ?>">
      <a href="<?php echo APP_URL; ?>editorialNew/" class="nav-link <?php echo ($url[0] == 'editorialNew') ? 'active' : 'text-white'; ?>">
        <i class="bi bi-bookmark-plus"></i>
        Crear Editorial
      </a>
    </li>
    <li class="<?php if($rol=="Bodeguero") echo "hidden"; ?>">
      <a href="<?php echo APP_URL; ?>editorialList/" class="nav-link <?php echo ($url[0] == 'editorialList') ? 'active' : 'text-white'; ?>">
        <i class="bi bi-bookmark-star"></i>
        Listar Editoriales
      </a>
    </li>
    <li class="<?php if($rol=="Bodeguero") echo "hidden"; ?>">
      <a href="<?php echo APP_URL; ?>productNew/" class="nav-link <?php echo ($url[0] == 'productNew') ? 'active' : 'text-white'; ?>">
        <i class="bi bi-book"></i>
        Crear Producto
      </a>
    </li>
    <li class="<?php if($rol=="Bodeguero") echo "hidden"; ?>">
      <a href="<?php echo APP_URL; ?>productList/" class="nav-link <?php echo ($url[0] == 'productList') ? 'active' : 'text-white'; ?>">
        <i class="bi bi-book-half"></i>
        Listar Productos
      </a>
    </li>
    <li class="<?php if($rol=="Bodeguero") echo "hidden"; ?>">
      <a href="<?php echo APP_URL; ?>cellarNew/" class="nav-link <?php echo ($url[0] == 'cellarNew') ? 'active' : 'text-white'; ?>">
        <i class="bi bi-bag"></i>
        Crear Bodega
      </a>
    </li>
    <li class="<?php if($rol=="Bodeguero") echo "hidden"; ?>">
      <a href="<?php echo APP_URL; ?>cellarList/" class="nav-link <?php echo ($url[0] == 'cellarList') ? 'active' : 'text-white'; ?>">
        <i class="bi bi-bag-check"></i>
        Listar Bodegas
      </a>
    </li>
    <li class="<?php if($rol=="Bodeguero") echo "hidden"; ?>">
      <a href="<?php echo APP_URL; ?>cellarProducts/" class="nav-link <?php echo ($url[0] == 'cellarProducts') ? 'active' : 'text-white'; ?>">
        <i class="bi bi-bag-plus"></i>
        Ingresar Producto
      </a>
    </li>
    <li class="<?php if($rol=="Jefe de Bodega") echo "hidden"; ?>">
      <a href="<?php echo APP_URL; ?>movementNew/" class="nav-link <?php echo ($url[0] == 'movementNew') ? 'active' : 'text-white'; ?>">
        <i class="bi bi-send-plus"></i>
        Crear Movimiento
      </a>
    </li>
    <li class="<?php if($rol=="Jefe de Bodega") echo "hidden"; ?>">
      <a href="<?php echo APP_URL; ?>movementList/" class="nav-link <?php echo ($url[0] == 'movementList') ? 'active' : 'text-white'; ?>">
        <i class="bi bi-send-exclamation"></i>
        Listar Movimientos
      </a>
    </li>
    <li class="<?php if($rol=="Bodeguero") echo "hidden"; ?>">
      <a href="<?php echo APP_URL; ?>cellarReport/" class="nav-link <?php echo ($url[0] == 'cellarReport') ? 'active' : 'text-white'; ?>">
        <i class="bi bi-file-earmark-text"></i>
        Informe de Bodegas
      </a>
    </li>
    <li class="<?php if($rol=="Bodeguero") echo "hidden"; ?>">
      <a href="<?php echo APP_URL; ?>movementReport/" class="nav-link <?php echo ($url[0] == 'movementReport') ? 'active' : 'text-white'; ?>">
        <i class="bi bi-file-earmark-text"></i>
        Informe de Movimientos
      </a>
    </li>
  </ul>
  <hr>
  <div class="dropdown">
    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
      <img src="<?php echo APP_URL; ?>app/views/img/user.png" alt="" width="32" height="32" class="rounded-circle me-2">
      <strong><?php echo $_SESSION['nombre']." ".$_SESSION['apellido'] ?></strong>
    </a>
    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
      <li><a class="dropdown-item" href="<?php echo APP_URL; ?>logOut/">Cerrar sesión</a></li>
    </ul>
  </div>
</div>