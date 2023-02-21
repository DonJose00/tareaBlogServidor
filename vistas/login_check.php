<?php
session_start();

echo $_POST['usuario'];
echo $_POST['password'];

// Comprobar si se han enviado los datos del formulario
if (isset($_POST['usuario']) && isset($_POST['password'])) {

    // Comprobar si el usuario es normal o administrador
    if ($_POST['usuario'] == 'user' && $_POST['password'] == 'user') {
        // Guardar los datos en la sesión
        $_SESSION["id"] = $id;
        $_SESSION["username"] = $_POST['usuario'];
        $_SESSION["role"] = 'normal';
        header('Location: ../index.php');

    } elseif ($_POST['usuario'] == 'admin' && $_POST['password'] == 'admin') {
        // Guardar los datos en la sesión
        $_SESSION["id"] = $id;
        $_SESSION["username"] = $_POST['usuario'];
        $_SESSION["role"] = 'admin';
        header('Location: ../index.php');

    } else {
        echo "Nombre de usuario o contraseña incorrectos.";
    }
}
