<?php

//Start session 
session_start();


$id_sesion = $_SESSION['sesion_id_empresa'];
$id_cookie = $_COOKIE['id_cookie'];

//echo "<script language='javascript'>";
//echo "alert('id: ".$id_sesion."  cookie: ".$id_cookie."');";
//echo "</script>";

if(isset ($_COOKIE["id_cookie"]) ||  isset ($_SESSION["sesion_id_empresa"])) {
    return true;
}
else{
    echo "<script language='javascript'>";
    echo "alert('No hay ninguna session activa. Porfavor Inicie Session');";
    echo "</script>";
    session_destroy();
    echo"<script> location.href='index.php';</script>";
}


?>

