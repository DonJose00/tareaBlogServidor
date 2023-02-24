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
<html lang="es">
<?php require 'includes/head.php';?>
<body>
  <?php if (isset($_POST['volver'])) header('Location:index.php'); ?>
  <div class="container d-flex justify-content-center my-4">
      <form action="index.php?accion=adduser" method="POST" name="formAddUser" enctype="multipart/form-data">
          <label for="usuario">Nombre de Usuario: 
          <input type="text" class="form-control" name="usuario" value="<?php if (isset($_POST['usuario'])) echo $_POST['usuario'];?>">
          <?php if (isset($_POST['registrar'])) if (isset($error['usuario'])) echo '<div class="alert alert-danger">'.$error['usuario'].'</div>';?></label><br>

          <label for="password">Contraseña: 
          <input type="password" class="form-control" name="password"> 
          <?php if (isset($_POST['registrar'])) if (isset($error['password'])) echo '<div class="alert alert-danger">'.$error['password'].'</div>';?></label><br>

          <label for="nombre">Nombre: 
          <input type="text" class="form-control" name="nombre" value="<?php if (isset($_POST['nombre'])) echo $_POST['nombre'];?>">
          <?php if (isset($_POST['registrar'])) if (isset($error['nombre'])) echo '<div class="alert alert-danger">'.$error['nombre'].'</div>';?></label><br>

          <label  for="apellidos">Apellidos: 
          <input type="text" class="form-control" name="apellidos" value="<?php if (isset($_POST['apellidos'])) echo $_POST['apellidos'];?>"> 
          <?php if (isset($_POST['registrar'])) if (isset($error['apellidos'])) echo '<div class="alert alert-danger">'.$error['apellidos'].'</div>';?></label><br>

          <label  for="email">Email: 
          <input type="email" class="form-control" name="email" value="<?php if (isset($_POST['email'])) echo $_POST['email'];?>"> 
          <?php if (isset($_POST['registrar'])) if (isset($error['email'])) echo '<div class="alert alert-danger">'.$error['email'].'</div>';?></label><br>

          <label for="imagen">Imagen: <input type="file" class="form-control" name="imagen"></label><br>
            <br>
          <label for="rol">Elige un rol:</label>
          <select id="rol">
            <option value="normal">Usuario normal</option>
            <option value="administrador">Usuario administrador</option>
          </select>
          <hr>
          <input type="submit" name="registrar" value="Registrar">
          <a href="index.php"><button type="button" name="volver" style="margin-left: 10px">Volver</button></a>
      </form>
  </div>
  <?php require 'includes/footer.php'; ?>
</body>

</html>