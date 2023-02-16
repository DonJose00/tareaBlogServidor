<!-- ESTRUCTURA PHP -->
<?php
//Variables para comprobar el login
$usuariobd = "usuario";
$passwordbd = "admin";
//Comprobamos que se han enviado los datos
//var_dump($_POST);
if (isset($_POST[('enviar')])) {
    //Si se han enviado datos Y no están vacios
    if ((isset($_POST['usuario']) && isset($_POST['password'])) && (!empty($_POST['usuario']) && !empty($_POST['password']))) {
        //Si el dato recogido es igual al declarado arriba, es decir, el correcto
        if ($_POST['usuario'] == $usuariobd && $_POST['password'] == $passwordbd) {
            session_start(); //iniciamos sesion
            $_SESION['logueado'] = $_POST["usuario"];
            $_SESSION["usuario"] = $_POST["usuario"];

            //Si el checkbox está seleccionado
            if (isset($_POST['recuerdame']) && ($_POST['recuerdame'] == "on")) {
                //Creamos las cookies
                setcookie('usuario', $_POST['usuario'], time() + (15 * 24 * 60 * 60));
                setcookie('password', $_POST['password'], time() + (15 * 24 * 60 * 60));
                setcookie('recuerdame', $_POST['recuerdame'], time() + (15 * 24 * 60 * 60));
            } else {
                //Si el checkbox no está seleccionado eliminamos las cookies(No quiere que recuerde)
                //Eliminamos las cookies 
                if (isset($_COOKIE['usuario'])) {
                    setcookie('usuario', time() - 100);
                }
                if (isset($_COOKIE['password'])) {
                    setcookie('password', time() - 100);
                }
                if (isset($_COOKIE['recuerdame'])) {
                    setcookie('recuerdame', time() - 100);
                }
            }

            //Mantener la sesion abierta 
            if (isset($_POST['abierta']) && ($_POST['abierta'] == "on")) {
                //Si el checkbox está seleccionado, creamos una cookie para la session
                setcookie('abierta', $_POST['usuario'], time() + (15 * 24 * 60 * 60));
            } else { //Si el checkbox no está seleccionado eliminamos la cookie
                if (isset($_COOKIE['abierta'])) {
                    setcookie('abierta', "'");
                }
            }
            //Redirigimos a la página inicio.php
            header('Location: index.php');
            // var_dump($_SESSION['usuario']);
        } else {
            header('Location: login.php?error=datos');
            echo "datos incorrectos";
        }

    }
}
?>


<!-- Estructura HTML -->
<html>
<head lang="es">
    <?php require 'includes/head.php'; ?>
    <link rel="stylesheet" href="../css/misestilos.css">
</head>
<body>
    <div class="container cuerpo text-center">
    <a href="../index.php">Inicio</a>
        <p>
        <h2> <img src="../images\formulario.png" width="60px" /> Login de usuario:</h2>
        </p>
    </div>
    <div class="container my-3">
        <div class="row">
            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 py-4 color-fondo">
                <form  class="formLogin" action="login_check.php" method="POST" enctype="multipart/form-data">
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
