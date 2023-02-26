<!DOCTYPE html>
<html lang="es">
<?php require 'includes/head.php';?>

<body>
    
<?php require 'includes/header.php'; ?> 
<?php if (!isset($_POST['ok'])){?>
<div class="<?php echo $parametrosVistas['tipo'];?>"><?php echo $parametrosVistas['mensaje'];?></div>
<div class="d-flex justify-content-center my-3"> 


<div class="card">
  <h3 class="card-header text-danger text-center">Eliminar Entrada</h3>
  <div class="card-body">
    <h4 class="card-title">¿Desea continuar?</h4>
    <p class="card-text">** Aviso: no podrá volver atrás **</p>
    <div class="d-flex flex-row justify-content-center mx-2">
        <div class="p-2">
            <form action="index.php?accion=delEntrada" method="POST">
                <input type="hidden" name="id" value="<?php if (isset($_GET['id'])) echo $_GET['id'];?>">
                <input type="hidden" name="b" value="1">
                <input type="submit" class="btn btn-warning" value="Eliminar"><br>
            </form>
        </div>
        <div class="p-2">
            <a href="index.php?accion=listado"><button type="button" class="btn btn-success">Cancelar</button></a>
        </div>
    </div>
  </div>
</div>
</div>
<?php }
      else{?>
      <div class="<?php echo $parametrosVistas['tipo'];?>"><?php echo $parametrosVistas['mensaje'];?></div>
      <div class="d-flex justify-content-center my-3"> 
        <div class="p-2">
            <a href="index.php?accion=listado"><button type="button" class="btn btn-success">Volver</button></a>
        </div>
      </div>
<?php }?>
<?php require 'includes/footer.php'; ?>

</body>
</html>