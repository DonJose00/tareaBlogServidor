<!DOCTYPE html>
<html>

<head>
  <?php require_once 'includes/head.php'; ?>
  <link rel="stylesheet" href="css/misestilos.css">
</head>

<body class="cuerpo">

  <!-- NAVBAR -->


  <nav class="navbar navbar-expand-lg bg-light">
    <a class="navbar-brand" href="#">Barra de navegación</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li> <a href="index.php?accion=listado"> Listar usuarios</a></li>
        <li> <a href="index.php?accion=adduser"> Añadir usuario</a></li>
      </ul>
      <a class="navbar-brand" href="#">
        <img src="img/logoF1.png" class="me-2" height="20" alt="MDB Logo" loading="lazy" />
        <small>Jcoronel Web</small>
      </a>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Buscar..." aria-label="Search" />
        <button class="btn btn-outline-success" type="submit">
          Buscar
        </button>
      </form>
    </div>
  </nav>
</body>
<?php include 'includes/footer.php' ?>

</html>