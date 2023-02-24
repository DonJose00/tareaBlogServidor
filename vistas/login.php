<!-- Estructura HTML -->
<html>
<head lang="es">
    <?php require 'includes/head.php'; ?>
    <link rel="stylesheet" href="../css/misestilos.css">
</head>
<body>
    <div class="container cuerpo text-center">
        <p>
        <h2> <img src="../images/formulario.png" width="60px" /> Login de usuario:</h2>
        </p>
    </div>
    <div class="container my-3">
        <div class="row">
            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 py-4 color-fondo">
                <form  class="formLogin" action="../index.php?accion=login" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="usuario" class="form-label">User</label>
                        <input type="text" class="form-control" name="usuario">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password">
                    </div>
                    <button type="submit" class="btn btn-primary" name="enviar">Enviar</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
