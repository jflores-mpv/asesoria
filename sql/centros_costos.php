<?php
error_reporting(0);
require_once('../ver_sesion.php');

    //Start session
session_start();

    //Include database connection details
require_once('../conexion.php');

$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$sesion_id_usuario = $_SESSION["sesion_id_usuario"];
$emision_codigo = $_SESSION["emision_codigo"];
$accion = $_POST['txtAccion'];

date_default_timezone_set('America/Guayaquil');

//   var_dump($_SESSION);
if($accion == '1'){
    $detalle=addslashes(trim($_POST['txtDetalle']));
    $sql="INSERT INTO `centro_costo_empresa`( `detalle`, `id_empresa`) VALUES ('".$detalle."','".$sesion_id_empresa."')";
    $result= mysql_query($sql);
    $response = ($result)?1:0;
    echo $response;
    exit;
}

if($accion == '2'){
    $id=addslashes(trim($_POST['idCentroCosto']));
    $detalle=addslashes(trim($_POST['txtDetalle']));
    $sql="UPDATE `centro_costo_empresa` SET `detalle`='".$detalle."' WHERE id_empresa=$sesion_id_empresa AND id_centro_costo=$id ";
    $result= mysql_query($sql);
    $response = ($result)?3:2;
    echo $response;
    exit;
}

if($accion == '3'){
    $id=addslashes(trim($_POST['id']));
    $detalle=addslashes(trim($_POST['txtDetalle']));
    $sql="DELETE FROM `centro_costo_empresa` WHERE id_empresa=$sesion_id_empresa AND id_centro_costo=$id ";
    $result= mysql_query($sql);
    $response = ($result)?5:4;
    echo $response;
    exit;
}