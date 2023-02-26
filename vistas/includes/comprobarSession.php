<?php

/**
 * Este código verificará el estado actual de la sesión utilizando session_status() y mostrará un mensaje diferente
 * según el valor devuelto. Dependiendo de la configuración de PHP y el estado de la sesión,
 * se imprimirá uno de los siguientes mensajes:
 *"Las sesiones están deshabilitadas" si las sesiones están deshabilitadas en la configuración de PHP.
 *"No se ha iniciado ninguna sesión" si las sesiones están habilitadas pero no se ha iniciado ninguna sesión.
 *"La sesión está activa" si las sesiones están habilitadas y una sesión está activa.
 *"Se ha detectado algún problema con la sesión" si se ha detectado algún problema con la sesión.
 */
// Verificar si las sesiones están deshabilitadas
if (session_status() === PHP_SESSION_DISABLED) {
    echo 'Las sesiones están deshabilitadas';
}

// Verificar si no se ha iniciado ninguna sesión
if (session_status() === PHP_SESSION_NONE) {
    echo 'No se ha iniciado ninguna sesión';
}

// Verificar si una sesión está activa
if (session_status() === PHP_SESSION_ACTIVE) {
    echo 'La sesión está activa';
}

// Verificar si se ha detectado algún problema con la sesión
if (session_status() === PHP_SESSION_WARNING) {
    echo 'Se ha detectado algún problema con la sesión';
}
