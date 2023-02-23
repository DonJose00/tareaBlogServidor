<html>
  <head>
    <?php require_once 'includes/head.php';?>
  </head>
  <body>
    <div class="container centrar">
      <a href="index.php">Inicio</a>
      <div class="container cuerpo text-center centrar">
        <p><h2><img class="alineadoTextoImagen" src="images/user.png" width="50px"/>Listar Usuarios</h2> </p>
      </div>
      <!--Mostramos los mensajes que se hayan generado al realizar el listado-->
      <?php foreach ($datosVistas["mensajes"] as $mensaje){ ?>
        <!-- Obtenemos el tipo del mensaje, si es sucess o danger, y además el contenido del mensaje -->
        <div class="alert alert-<?=$mensaje["tipo"]?>"><?=$mensaje["mensaje"]?></div>
      <?php }?>
      <!--Creamos la tabla que utilizaremos para el listado:-->
      <table class="table table-striped">
        <tr>
          <th>Nombre</th>
          <!-- <th>Contraseña</th>-->
          <th>Email</th>
          <th>Foto</th>
          <!-- Añadimos una columna para las operaciones que podremos realizar con cada registro -->
          <th>Operaciones</th>
        </tr>
        <!--Los datos a listar están almacenados en $datosVistas["datos"], que lo recibimos del controlador-->
        <?php foreach ($datosVistas["datos"] as $datosUser){ ?>
          <!--Metemos en la tabla los datos correspondientes-->
          <tr>
            <td><?=$datosUser["nombre"]?></td>
            <td><?=$datosUser["email"]?></td>
            <?php if ($datosUser["imagen"] !== null){ ?>
              <td><img src="fotos/<?=$datosUser['imagen']?>" width="35" /></td>
            <?php }else{ ?>
              <!-- Para mostrar que está vacio -->
              <td>----</td> 
            <?php }?>
            <!-- Enviamos a actuser.php o deluser.php, mediante GET, el id del registro que deseamos editar o eliminar: -->
            <td><a href="index.php?accion=actuser&id=<?=$datosUser['id']?>">Editar </a><a href="index.php?accion=deluser&id=<?=$datosUser['id']?>">Eliminar</a></td>
          </tr>
          <!-- Fin del bucle  -->
        <?php }?> 
      </table>
    </div>
  </body>
</html>