<!-- Script que finaliza la sesión -->
<?php
 session_start(); // Activamos el uso de sesiones
 session_unset(); // Libera todas las variables de sesión
 session_destroy(); // Destruimos la sesión
 header("Location: index.php"); //Redirigimos a la página de Login
?>