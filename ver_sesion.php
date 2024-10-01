<?php

//Start session 
session_start();

date_default_timezone_set('America/Guayaquil');


$id_sesion = $_SESSION['sesion_id_empleado'];
$sesion_id_empresa = $_SESSION['sesion_id_empresa'];
$id_cookie = $_COOKIE['id_cookie'];

//echo "<script language='javascript'>";
//echo "alert('id: ".$id_sesion."  cookie: ".$id_cookie."');";
//echo "</script>";

if(isset ($_SESSION["sesion_id_empresa"])) {
    //echo "0";
    //return true;
 //   if(isset ($_SESSION["sesion_id_empleado"])){

   // }else{
     //   echo"<script> alert('No hay ninguna sesion de Usuario');</script>";
 //   echo"<script> location.href='ingreso.php';</script>";
  //  }
    
}else{
    echo"<script> alert('Vuelva a ingresar el codigo de Empresa');</script>";
    echo"<script> location.href='sesionCerrada.php';</script>";
    session_destroy();
    //echo "1";
    //return false;
}

?>

