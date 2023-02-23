<!DOCTYPE html>
<html lang="es">

<?php require 'includes/head.php';?>

<body>
<?php require 'includes/inicio.php';?>
<?php
if (count($datosVistas['datos']) == 0) {
    echo '<div class="d-flex justify-content-center my-3">';
    echo '<p>No existen entradas en la categoría seleccionada</p></div>';
} else {
    //Recorremos el array $datosVistas para imprimir las entradas
    for ($i = 0; $i < count($datosVistas['datos']); $i++) {?>
    <div class="display-4 my-3 d-flex justify-content-center text-primary">Mis Entradas</div>
        <div class="d-flex justify-content-center my-3">
            <div class="card mb-3" style="width: 550px !important">
                <div class="row g-0">
                    <div class="col-md-4 d-flex align-items-center">
                        <img src="img/<?php echo $datosVistas['datos'][$i]['imagen']; ?>">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $datosVistas['datos'][$i]['titulo']; ?> </h5>
                            <p class="card-text">Titulo de la entradaaaaaaaaaaaa</p>
                            <p class="card-text"><small class="text-muted"><?php echo $datosVistas['datos'][$i]['fecha']; ?></small></p>
                            <a href="index.php?accion=detalle&<?php echo 'id=' . $datosVistas['datos'][$i]['id']; ?>" title="Detalles"><i class="fas fa-plus"></i></a>
                            <?php if (isset($_SESSION['id'])) {
                                    if ($_SESSION['id'] == $datosVistas['datos'][$i]['id_usuario']) {
                                        echo '<a href="index.php?accion=modEntrada&id=' . $datosVistas['datos'][$i]['id'] . '" title="Modificar"><i class="fas fa-edit"></i></a>
                                        <a href="index.php?accion=delEntrada&id=' . $datosVistas['datos'][$i]['id'] . '" title="Eliminar"><i class="fas fa-trash-alt"></i></a>';
                                    }
                                }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }}?>
<div style="padding-top: 20px; padding-bottom:20px; text-align:center;">
    <a href="" title="Imprimir"><img src="images/excel.png" width="40px" height="47px"></a>
</div>
<?php require 'includes/footer.php';?>
</body>
</html>