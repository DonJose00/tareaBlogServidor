<html>
  <head>
    <?php require_once 'includes/head.php'; ?>
  </head>
  <body class="cuerpo">
    <div class="container centrar">
      <a href="index.php">Inicio</a>
      <div class="container cuerpo text-center centrar">	
        <p><h2><img class="alineadoTextoImagen" src="images/user.png" width="50px"/>Listar Usuarios</h2> </p>
      </div>
      <!--Mostramos los mensajes que se hayan generado al realizar el listado-->
      <?php foreach ($parametros["mensajes"] as $mensaje) : ?> 
        <div class="alert alert-<?= $mensaje["tipo"] ?>"><?= $mensaje["mensaje"] ?></div>
      <?php endforeach; ?>
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
        <!--Los datos a listar están almacenados en $parametros["datos"], que lo recibimos del controlador-->
        <?php foreach ($parametros["datos"] as $d) : ?>
          <!--Mostramos cada registro en una fila de la tabla-->
          <tr>  
            <td><?= $d["nombre"] ?></td>
            <!--<td><?= $d["password"] ?></td>-->
            <td><?= $d["email"] ?></td>
            <?php if ($d["imagen"] !== NULL) : ?>
              <td><img src="fotos/<?= $d['imagen'] ?>" width="40" /></td>
            <?php else : ?>
              <td>----</td>
            <?php endif; ?>
            <!-- Enviamos a actuser.php, mediante GET, el id del registro que deseamos editar o eliminar: -->
            <td><a href="index.php?accion=actuser&id=<?= $d['id'] ?>">Editar </a><a href="index.php?accion=deluser&id=<?= $d['id'] ?>">Eliminar</a></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </body>
</html>