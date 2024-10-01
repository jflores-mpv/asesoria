<?php

    require_once('../ver_sesion.php');

    //Start session
    session_start();

    //Include database connection details
    require_once('../conexion.php');

    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $accion = $_POST['txtAccion'];
  //  echo "accion".$accion;
 
    $sesion_punto = $_SESSION['userpunto'];
	$sesion_id_est = $_SESSION['userest'];
   
   if($accion == '1'){

        $txtIdentificacion = $_POST['txtIdentificacion'];
        $txtNombre = $_POST['txtNombre'];
        $txtPlaca = $_POST['txtPlaca'];
        $cmbEmi = $_POST['cmbEmi'];
        
      $sql = "INSERT INTO `datosTransportistas`( `identificacion`, `nombre`, `placa`, `puntoEmision`, `idEmpresa`, `empresaTransportista`) 
          VALUES ('".$txtIdentificacion."','".$txtNombre."','".$txtPlaca."','".$cmbEmi."','".$sesion_id_empresa."',NULL) ";
          $result = mysql_query($sql);
 echo $sql;

          if ($result)
          {
            echo "1";
          }
		  else 
			{
                echo "2";
			}
         }
       


