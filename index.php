<?php

/**
 * Script que se encarga de redirigir las rutas de los enlaces pulsado en la
 * vista del listado. A él deberá llegar el nombre de la acción y, si fuese
 * necesario, el id del usuario sobre el que se va a realizar dicha acción.
 * Lo hacemos as´para poder llamar directamente a los métodos del controlador,
 * de lo contrario tendríamos que especificar los controladores en ficheros
 * diferentes
 */
require_once 'controladores\controlador.php';
//Definimos un objeto controlador
$controlador = new controlador();

if (isset($_GET['accion']) && $_GET['accion'] == 'login') {
    $controlador->login();
} else if ($_GET && $_GET["accion"]) {
    //Sanitizamos los datos que recibamos mediante el GET
    $accion = filter_input(INPUT_GET, "accion", FILTER_SANITIZE_STRING);
    //Verificamos que el objeto controlador que hemos creado implementa el
    //método que le hemos pasado mediante GET
    if (method_exists($controlador, $accion)) {
        $controlador->$accion(); //Ejecutamos la operación indicada en $accion
    } else {
        $controlador->index(); //Redirigimos a la página de inicio
    }

} else {
    $controlador->index();
}
