<?php
    
    
    session_start();

    if($_SESSION["sesion_tipo"] == "Super Administrador"){
        session_destroy();
    //    echo"<script> location.href='index.php';</script>";
	    echo"<script> location.href='administrador.php';</script>";
    }
    

    $_SESSION["sesion_id_usuario"] = "";
    $_SESSION["sesion_id_empleado"] = "";
    $_SESSION["sesion_tipo"] = "";
    $_SESSION["sesion_estado"] = "";
    $_SESSION["sesion_nombre"] = "";
    $_SESSION["sesion_apellido"] = "";
    $_SESSION["sesion_permisos"] = "";
    $_SESSION["sesion_logo_sistema"] = "";

    $_SESSION["sesion_asientos_contables"] = "";
    $_SESSION["sesion_plan_cuentas"] = "";
    $_SESSION["sesion_reportes_contables"] = "";



    unset($_SESSION["sesion_id_usuario"]);
    unset($_SESSION["sesion_id_empleado"]);
    unset($_SESSION["sesion_tipo"]);
    unset($_SESSION["sesion_estado"]);
    unset($_SESSION["sesion_nombre"]);
    unset($_SESSION["sesion_apellido"]);
    unset($_SESSION["sesion_permisos"]);
    unset($_SESSION["sesion_logo_sistema"]);

    unset($_SESSION["sesion_asientos_contables"]);
    unset($_SESSION["sesion_plan_cuentas"]);
    unset($_SESSION["sesion_reportes_contables"]);


    
    echo"<script> location.href='index.php';</script>";
            //header("Location:index.php");
?>