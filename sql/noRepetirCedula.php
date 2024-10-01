<?php
	
	//Start session
	session_start();

	//Include database connection details
	require_once('../conexion.php');

    try
     {
        // VALIDACIONES PARA QUE EL CODIGO NO SE REPITA
        if(isset ($_POST['cedula'])){
          $cedula = $_POST['cedula'];
          //echo "".$cedula;
          $sql = "SELECT cedula from clientes where cedula='".$cedula."'";
          $resp = mysql_query($sql);
          $entro=0;
          while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
                    {
                        $var=$row["cedula"];
                    }
          if($var==$cedula){
               if($var==""&&$cedula==""){
                     $entro=0;                    
                  }else{
                      $entro=1;
                  }
          }              
         echo $entro;
         }

    }
    catch (Exception $e) {
    // Error en algun momento.
        echo "Error: ".$e;    }

?>
