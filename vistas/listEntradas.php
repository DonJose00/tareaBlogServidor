<!DOCTYPE html>
<html lang="es">
<?php require 'includes/head.php';?>

<body>

<?php require 'inicio.php';?>

<!-- listado paginado -->
<div class="display-4 my-3 d-flex justify-content-center text-primary">Entradas</div>
<?php for ($i = 0; $i < count($parametrosVistas['datos']); $i++) {?>
    <div class="d-flex justify-content-center my-3">
        <div class="card mb-3" style="max-width: 540px;">
            <div class="row g-0">
                <div class="col-md-4 d-flex align-items-center">
                    <img src="img/<?php echo $parametrosVistas['datos'][$i]['imagen']; ?>" alt="">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $parametrosVistas['datos'][$i]['titulo']; ?> </h5>
                        <p class="card-text">Titulo de la entrada</p>
                        <p class="card-text"><small class="text-muted"><?php echo $parametrosVistas['datos'][$i]['fecha']; ?></small></p>
                        <a href="index.php?accion=detalle&<?php echo 'id=' . $parametrosVistas['datos'][$i]['id']; ?>" title="Detalles"></title><i class="fas fa-plus"></i></a>
                        <?php if (isset($_SESSION['id'])) {if (($_SESSION['id'] == $parametrosVistas['datos'][$i]['id_usuario']) || ($_SESSION['usuario'] == 'root')) {
                            echo '<a href="index.php?accion=modEntrada&id=' . $parametrosVistas['datos'][$i]['id'] . '" title="Modificar"><i class="fas fa-edit"></i></a>
                            <a href="index.php?accion=delEntrada&id=' . $parametrosVistas['datos'][$i]['id'] . '" title="Eliminar"><i class="fas fa-trash-alt"></i></a>';
                            }
                            }?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php }?>

    <div class="d-flex flex-wrap align-content-end justify-content-center">
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <li class="page-item">
                <a class="page-link" href="index.php?accion=paginado&pag=<?php if ($_GET['pag'] > 1) {
    echo ($_GET['pag'] - 1);
} else {
    echo $_GET['pag'];
}
?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Previous</span>
                </a>
            </li>
        <!-- colocamos el paginador según el numero total de paginas que hay en entradas -->
        <?php for ($i = 1; $i <= $parametrosVistas['paginas']; $i++) {?>

            <li class="<?php if ($_GET['pag'] == $i) {
    echo 'page-item active';
} else {
    echo 'page-item';
}
    ?>">
                <a class="page-link" href="<?php echo 'index.php?accion=paginado&pag=' . $i; ?>"><?php echo $i; ?></a></li>
        <?php }?>
            <li class="page-item">
                <a class="page-link" href="index.php?accion=paginado&pag=<?php if ($_GET['pag'] < $parametrosVistas['paginas']) {
    echo ($_GET['pag'] + 1);
} else {
    echo $_GET['pag'];
}
?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
        </ul>
    </nav>
    </div>

<?php require 'includes/footer.php';?>

</body>
</html>