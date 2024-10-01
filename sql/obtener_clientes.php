<?php 

require_once('../ver_sesion.php');
//Start session
session_start();
//Include database connection details
require_once('../conexion.php');

$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];

$consulta5 = "SELECT `id_cliente`, `nombre`, `apellido` FROM `clientes` WHERE id_empresa=$sesion_id_empresa";
$result = mysql_query($consulta5);

$clientes = array();
while ($row = mysql_fetch_array($result)) {
  $cliente = array(
    'id_cliente' => $row['id_cliente'],
    'nombre' => $row['nombre'].' '. $row['apellido']
  );
  $clientes[] = $cliente;
}

echo json_encode($clientes);
