<!-- Estructura en PHP -->
<!-- 
session_start(); //Activamos el uso de sesiones
if (!isset($_SESSION['usuario']) && (isset($_COOKIE['abierta']))) { //Si no existe la sesión…
    //Redirigimos a la página de login con el tipo de error ‘fuera’: que indica que
    // se trató de acceder directamente a una página sin loguearse previamente
    header("Location: login.php?error=fuera");
}
?> -->

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
        <p><h2>Añadir nueva entrada al Blog</h2></p>
      </div>
      <?php 
      if (isset($datosVistas["mensaje"])) {
        foreach ($datosVistas["mensaje"] as $mensaje){?>
      <div class="alert alert-<?=$mensaje["tipo"]?>"><?=$mensaje["mensaje"]?></div>
      <?php } }?>
      <div class="addEntradas">
        <form action="controladores/controlador.php?accion=addEntrada" method="post" enctype="multipart/form-data">
            <label for="titulo">Título:</label><br>
            <input class="form-control" type="text" id="titulo" name="titulo"><br>
            <label for="imagen">Imagen:</label><br>
            <input class="form-control" type="file" id="imagen" name="imagen"><br>
            <label for="descripcion">Descripción:</label><br>
            <textarea class="form-control" id="descripcion" name="descripcion"></textarea><br>
            <br>
            <select class="form-select" aria-label="Default select example">
            <?php 
            if (isset($datosVistas["categorias"])) {
              foreach ($datosVistas["categorias"] as $categoria){?>
              <option value="<?php echo $categoria['id']?>"><?php echo $categoria['nombre']?></option>
            <?php } }?>
            </select>
            <input type="submit" value="Guardar" name="enviar" class="btn btn-success">
        </form>
      </div>
    </div>
</body>
<?php require_once 'includes/footer.php';?>
</html>