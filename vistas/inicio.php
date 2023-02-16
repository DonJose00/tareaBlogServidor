

<!DOCTYPE html>
<html>

<head>
  <?php require_once 'includes/head.php';?>
  <link rel="stylesheet" href="css/misestilos.css">
</head>

<body class="cuerpo">

  <?php require_once 'C:\xampp\htdocs\tareaBlogServidor\modelos\modelo.php';?>
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
            <li class="nav-item"> <a class="nav-link" href="index.php?accion=listado">Listar entradas</a></li>
            <li class="nav-item"> <a class="nav-link" href="index.php?accion=adduser"> Añadir entradas</a></li>
            <form class="d-flex" role="search">
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-success" type="submit">Buscar</button>
            </form>
            <li class="nav-item"> <a class="nav-link" href="../includes/descargarPDF.php">Descargar PDF</a></li>
            <li class="nav-item"> <a class="nav-link" href="vistas/login.php">
              <?php 
            session_start();
            //Si se han enviado datos Y no están vacios 
            if ((isset($_SESSION['username'])) && (!empty($_SESSION['username']))) {
              echo $_SESSION["username"].','.$_SESSION["role"];
            }else{
            echo 'Login';
            }?>
            </a></li>
            <li class="nav-item"> <a class="nav-link" href="vistas/logout.php"> Cerrar Sesion</a></li>
          </ul>
        </div>
      </div>
    </nav>
  </div>
  <div class="contenedor">
    <div class="post">
      <article>
        <h2 class="titulo">Titulo el articulo</h2>
        <p class="fecha">1 Enero de 2016</p>
      </article>
    </div>
  </div>
</body>
<?php include 'includes/footer.php'?>

</html>