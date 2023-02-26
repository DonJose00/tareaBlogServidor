<!DOCTYPE html>
<html lang="es">
<?php require 'includes/head.php';?>

<body>
    
<?php 
    require 'includes/header.php'; 
    
         //mostramos el resultado de la actualizacion de la entrada
        echo '<div class="'.$parametrosVistas['tipo'].'">'.$parametrosVistas['mensaje'].'</div>';
    if (isset($_POST['volver'])) header('Location:index.php');
    

?>
<div class="display-4 my-3 d-flex justify-content-center text-primary">Modificar Entrada</div>
<div class="container d-flex justify-content-center my-4" style="width: 550px !important">

    
    <form action="index.php?accion=modEntrada" method="POST" name="formUser" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php if (isset($_GET['id'])) echo $_GET['id']; else $_POST['id'];?>">
            <label class="text-info" style="font-family: 'Open Sans Condensed', sans-serif;" for="titulo">Titulo: <input type="text" class="form-control" name="titulo" value="<?php if (isset($_POST['ok'])) echo $_POST['titulo']; else echo $parametrosVistas['entrada']['titulo']; ?>">
            <?php if (isset($_POST['ok'])) if (isset($error['titulo'])) echo '<div class="alert alert-danger">'.$error['titulo'].'</div>';?></label><br>

            <label class="text-info" style="font-family: 'Open Sans Condensed', sans-serif;" for="desc" >Descripción: <textarea class="form-control" name="desc" rows="10" cols="50"><?php if (isset($_POST['ok'])) echo $_POST['desc']; else echo $parametrosVistas['entrada']['descripcion']; ?></textarea>
            <?php if (isset($_POST['ok'])) if (isset($error['desc'])) echo '<div class="alert alert-danger">'.$error['desc'].'</div>';?></label><br>

            <label class="text-info" style="font-family: 'Open Sans Condensed', sans-serif;" for="fecha">Fecha: <input type="date" class="form-control" name="fecha" value="<?php echo date('Y-m-d')?>">
            <?php if (isset($_POST['ok'])) if (isset($error['fecha'])) echo '<div class="alert alert-danger">'.$error['fecha'].'</div>';?></label><br>

            <label class="text-info" style="font-family: 'Open Sans Condensed', sans-serif;" for="categoria">Categoría actual: <input type="text" class="form-control" name="categoria" value="<?php if (isset($_POST['ok'])) echo $_POST['categoria']; else echo $parametrosVistas['categoria']['nombre'];?>"><br>

            <?php if (isset($_POST['ok'])) if (isset($error['cat'])) echo '<div class="alert alert-danger">'.$error['cat'].'</div>';?></label><br>

            <label class="text-info" style="font-family: 'Open Sans Condensed', sans-serif;" for="imgactual">Imagen actual: <input type="text" name="imgactual" value="<?php if (isset($_POST['ok'])) echo $_POST['imgactual']; else echo $parametrosVistas['entrada']['imagen'];?>">
            
            <?php if ($parametrosVistas['entrada']['imagen']="") echo 'Sin imagen'; else
                                                                                    {if (isset($_POST['ok'])) echo '<img src="../img/'.$_POST['imgactual'].'"'.'width="50px" height="50px"'; 
                                                                                    else?> <img src="../img/?php echo $parametrosVistas['entrada']['imagen'];?>" width="50px" height="50px"/><?php }?><br>

            <label class="text-info" style="font-family: 'Open Sans Condensed', sans-serif;" for="imagen">Imagen: <input type="file" class="form-control" name="imagen"></label><br>
            <input type="submit" class="btn btn-info" value="Actualizar" name="ok"/>
            <a href="index.php"><button type="button" class="btn btn-info" name="volver">Volver</button></a>
    </form>

</div>
<?php require 'includes/footer.php'; ?>
               
</body>

</html>