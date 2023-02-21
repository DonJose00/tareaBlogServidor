<!-- Estructura en PHP -->
<?php
session_start(); //Activamos el uso de sesiones
if (!isset($_SESSION['usuario']) && (isset($_COOKIE['abierta']))) { //Si no existe la sesión…
    //Redirigimos a la página de login con el tipo de error ‘fuera’: que indica que
    // se trató de acceder directamente a una página sin loguearse previamente
    header("Location: login.php?error=fuera");
}
?>

<!DOCTYPE html>
<html>

<head>
  <?php require_once 'includes/head.php';?>
</head>

<body class="cuerpo">
  <div class="centrar">
    <div class="container centrar">
      <a href="index.php">Inicio</a>
      <div class="container cuerpo text-center centrar">
        <p>
        <h2>Añadir nueva entrada al Blog</h2>
        </p>
      </div>
      <?php foreach ($parametros["mensajes"] as $mensaje): ?>
        <div class="alert alert-<?=$mensaje["tipo"]?>"><?=$mensaje["mensaje"]?></div>
      <?php endforeach;?>
      <div class="addEntradas">
        <form action="controladores/controlador.php?accion=adduser" method="post" enctype="multipart/form-data">
          <label for="titulo">Título:</label><br>
          <input class="form-control" type="text" id="titulo" name="titulo"><br>
          <label for="imagen">Imagen:</label><br>
          <input class="form-control" type="file" id="imagen" name="imagen"><br>
          <label for="descripcion">Descripción:</label><br>
          <textarea class="form-control" id="descripcion" name="descripcion"></textarea><br>
          <label for="fecha">Fecha:</label><br>
          <input type="date" id="fecha" name="fecha"><br>
          <br>
          <input type="submit" value="Guardar" name="submit" class="btn btn-success">
        </form>
      </div>
    </div>
</body>

</html>