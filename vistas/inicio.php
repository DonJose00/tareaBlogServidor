<!DOCTYPE html>
<html>
<head>
  <?php require_once 'includes/head.php';?>
  <link rel="stylesheet" href="css/misestilos.css">
</head>
<body class="cuerpo">
  <?php require_once 'modelos\modelo.php';?>
  <!-- NAVBAR -->
  <div class="container-fluid">
    <nav class="navbar navbar-expand-lg bg-light">
      <div class="container-fluid">
        <a class="navbar-brand">Blog F1 Actualidad</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#">Home</a>
            </li>
            <div class="dropdown" style="margin-left: 10px">
              <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Entradas
              </button>
              <ul class="dropdown-menu">
              <li class="nav-item"> <a class="nav-link" href="index.php?accion=listEntradas">Listar entradas</a></li>
              <li class="nav-item"> <a class="nav-link" href="index.php?accion=adduser"> Añadir entradas</a></li>
              </ul>
            </div>
            <hr>
            <div class="dropdown" style="margin-left: 20px">
              <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Usuarios
              </button>
              <ul class="dropdown-menu">
              <li class="nav-item"> <a class="nav-link" href="index.php?accion=listado">Listar usuarios</a></li>
              <li class="nav-item"> <a class="nav-link" href="index.php?accion=adduser"> Añadir usuarios</a></li>
              </ul>
            </div>
            <form class="d-flex" role="search" style="margin-left: 50px">
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-success" type="submit">Buscar</button>
            </form>
            <li class="nav-item" style="margin-left: 400px"> <a class="nav-link" href="../includes/descargarPDF.php">Descargar PDF</a></li>
            <li class="nav-item"> <a class="nav-link" href="vistas/login.php">
              <?php
                //session_start();
                //Si se han enviado datos Y no están vacios
                if ((isset($_SESSION['usuario'])) && (!empty($_SESSION['usuario']))) {
                    echo $_SESSION["usuario"] . ',' . $_SESSION["rol"];
                } else {
                    echo 'Login';
                }
              ?></a>
            </li>
            <li class="nav-item"> <a class="nav-link" href="vistas/logout.php"> Cerrar Sesion</a></li>
          </ul>
        </div>
      </div>
    </nav>
  </div>
</body>
<?php include 'includes/footer.php'?>

</html>