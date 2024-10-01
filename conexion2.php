<?php
// Definir constantes de conexión
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'new_password');
define('DB_NAME', 'asesoria_asesoria');

// Crear la conexión
$conexion = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verificar la conexión
if ($conexion === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// echo "Connected successfully";

// Definir constante para la paginación
define('NUM_ITEMS_BY_PAGE', 10);

// Tu código adicional aquí...

// Cerrar la conexión cuando ya no se necesite
// mysqli_close($conexion);
?>
