<?php

	session_start();
    require_once('../chatb/Chat.php');
	//Include database connection details
// echo 'ingreso';

	require_once('../conexion.php');

// echo '==2==';
        
try{
//    function safe($value) {
//     return mysqli_real_escape_string( $value);
// }
    // $txtLogin = safe($_POST['txtLogin']);
    // $txtPassword = safe( $_POST['txtPassword']);
 $txtPassword =  $_POST['txtPassword'];
    // $txtPassword = sha1($txtPassword);
// echo '$txtPassword='.$txtPassword.'|';
    if($txtPassword != ""){      

        $sql="SELECT
     empresa.`id_empresa` AS empresa_id_empresa,
     empresa.`ruc` AS empresa_ruc,
     empresa.`contribuyente` AS empresa_contribuyente,
     empresa.`nombre` AS empresa_nombre,
     empresa.`razonSocial` AS empresa_razonSocial,
     empresa.`direccion` AS empresa_direccion,
     empresa.`pais` AS empresa_pais,
     empresa.`provincia` AS empresa_provincia,
     empresa.`id_ciudad` AS empresa_id_ciudad,
     empresa.`telefono1` AS empresa_telefono1,
     empresa.`telefono2` AS empresa_telefono2,
     empresa.`email` AS empresa_email,
     empresa.`pag_web` AS empresa_pag_web,
     empresa.`imagen` AS empresa_imagen,
     empresa.`fecha_inicio` AS empresa_fecha_inicio,
     empresa.`informacion_general` AS empresa_informacion_general,
     empresa.`perfil_empresa` AS empresa_perfil_empresa,
     empresa.`descripcion` AS empresa_descripcion,
     empresa.`mision` AS empresa_mision,
     empresa.`vision` AS empresa_vision,
     empresa.`actividad_empresa` AS empresa_actividad_empresa,
     empresa.`codigo_empresa` AS empresa_codigo_empresa,
     empresa.`caracter_identificacion` AS empresa_caracter_identificacion,
     empresa.`autorizacion_sri` AS empresa_autorizacion_sri,
     empresa.`id_tipo_empresa` AS empresa_id_tipo_empresa,
empresa.`URL` AS URL,
     empresa.`estado` AS empresa_estado,
     empresa.`Ocontabilidad` AS Ocontabilidad,

     
     periodo_contable.`id_periodo_contable` AS periodo_contable_id_periodo_contable,
     periodo_contable.`fecha_desde` AS periodo_contable_fecha_desde,
     periodo_contable.`fecha_hasta` AS periodo_contable_fecha_hasta,
     periodo_contable.`estado` AS periodo_contable_estado,
     periodo_contable.`ingresos` AS periodo_contable_ingresos,
     periodo_contable.`gastos` AS periodo_contable_gastos,
     periodo_contable.`id_empresa` AS periodo_contable_id_empresa,
     ciudades.`id_ciudad` AS ciudades_id_ciudad,
     ciudades.`ciudad` AS ciudades_ciudad,
     ciudades.`id_provincia` AS ciudades_id_provincia,
     tipo_empresas.`id_tipo_empresa` AS tipo_empresas_id_tipo_empresa,
     tipo_empresas.`tipo_empresa` AS tipo_empresas_tipo_empresa
     
FROM
     `empresa` empresa INNER JOIN `periodo_contable` periodo_contable ON empresa.`id_empresa` = periodo_contable.`id_empresa`
     
     INNER JOIN `ciudades` ciudades ON empresa.`id_ciudad` = ciudades.`id_ciudad`
     
     INNER JOIN `tipo_empresas` tipo_empresas ON empresa.`id_tipo_empresa` = tipo_empresas.`id_tipo_empresa`
     
     WHERE 
     
     empresa.`codigo_empresa`='".$txtPassword."' 
     

    
     ";
     
//      AND (
//     empresa.`codigo_empresa` = 'ADMIN'
//     OR (
//         empresa.`codigo_empresa` != 'ADMIN'
//         AND (URL IS NULL OR (URL = '$server_name' OR URL = 'www.$server_name2' AND URL != 'emdet.net'))
//     )
// )

 	// echo $sql;
        $result = mysql_query($sql);
		$a=0;
   if (!$result) {
  // Handle the error here
  echo "Error executing query: " . mysql_error();
  // Optionally, log the error or perform cleanup actions
  exit(); // Terminate script execution (optional)
} else {
  // Process the query result (if successful)
  echo $a = 0; // Assuming this is for initialization after successful query
  // ...
}
	//	ECHO "VA A ENTRAR";
         while  ($row=mysql_fetch_array($result)){
			$fecha_inicio= $row['empresa_fecha_inicio'];
             $estado = $row['empresa_estado'];
             $codigo_empresa = $row['empresa_codigo_empresa'];
            $empresa_ruc= $row['empresa_ruc'];
            $sesion_id_institucion_educativa = $_SESSION["sesion_id_institucion_educativa"];
               
             //variables de secion
            $_SESSION['sesion_id_empresa'] = $row['empresa_id_empresa'];
            $_SESSION['sesion_empresa_nombre'] = $row['empresa_nombre'];
            $_SESSION['sesion_empresa_razonSocial'] = $row['empresa_razonSocial'];
            $_SESSION['sesion_empresa_direccion'] = $row['empresa_direccion'];
            $_SESSION['empresa_contribuyente'] = $row['empresa_contribuyente'];
            $_SESSION['Ocontabilidad'] = $row['Ocontabilidad'];
            
            
            $_SESSION['sesion_id_tipo_empresa'] = $row['empresa_id_tipo_empresa'];
            $_SESSION['sesion_estado_empresa'] = $row['empresa_estado'];
            $_SESSION['sesion_id_periodo_contable'] = $row['periodo_contable_id_periodo_contable'];
            $_SESSION['sesion_empresa_id_ciudad'] = $row['empresa_id_ciudad'];
            $_SESSION['sesion_empresa_ruc'] = $row['empresa_ruc'];
            $_SESSION['sesion_ciudades_ciudad'] = $row['ciudades_ciudad'];
            $_SESSION['sesion_empresa_imagen'] = $row['empresa_imagen'];
            $_SESSION['sesion_teoria'] = $row['nivel_de_institucion_teoria'];
            $_SESSION['sesion_tipo_empresa'] = $row['tipo_empresas_tipo_empresa'];
            $_SESSION['sesion_cod_activacion'] = $row['empresa_cod_activacion'];
			$_SESSION['sesion_id_institucion_educativa'] = $row['empresa_id_institucion_educativa'];
			$_SESSION['sesion_id_nivel_institucion'] = $row['empresa_id_nivel'];	
			$_SESSION['sesion_cod_aula'] = $row['empresa_cod_aula'];

			
		//$sesion_id_institucion_educativa
        
        }
        $result1 = mysql_query("select userid from chat_users where userid={$_SESSION['sesion_id_empresa']}");
		if(mysql_num_rows($result1)==0)
				$result1 = mysql_query("INSERT INTO `chat_users`(`userid`, `username`, `avatar`, `online`) select id_empresa,nombre,'avatar.jpg','1' from empresa where id_empresa={$_SESSION['sesion_id_empresa']}");
		$chat = new Chat();
		$_SESSION['userid'] = $_SESSION['sesion_id_empresa'];
		$chat->updateUserOnline($$_SESSION['sesion_id_empresa'], 1);
		$lastInsertId = $chat->insertUserLoginDetails($_SESSION['sesion_id_empresa']);
		$_SESSION['login_details_id'] = $lastInsertId;
//	echo "ingreso de empresa"."<br>";
//	echo "id_empresa".$_SESSION['sesion_id_empresa'] ;
            if($codigo_empresa != ""){
              /*    if ($_SESSION['sesion_id_institucion_educativa']== "187"){
                    ?> <div class='alert alert-warning mt-3'>Su cuenta esta suspendida, consulte con la administracion del instituto</div> <?php
            } */

                // if($_SESSION['sesion_id_empresa']=='41'){
                    
                     $sqlRenovaciones="SELECT `id_renovacion`, `fecha_registro`, `fecha_renovacion`, `id_empresa` 
                     FROM `renovaciones` WHERE id_empresa=".$_SESSION['sesion_id_empresa'];
           
                $resultRenovaciones = mysql_query($sqlRenovaciones);
                $numFilasRenovaciones= mysql_num_rows($resultRenovaciones);
            // echo 'sql'.$sqlRenovaciones;
            // echo 'ejecuto'.$numFilasRenovaciones;
            
                if(  $numFilasRenovaciones==0){
                    $fecha_objeto = new DateTime($fecha_inicio);
                    $fecha_objeto->modify('+1 year');
                    $nueva_fecha = $fecha_objeto->format('Y-m-d'); 

                     $sqlInsert="INSERT INTO `renovaciones`( `fecha_registro`, `fecha_renovacion`, `id_empresa`) VALUES ('".$fecha_inicio."','".$nueva_fecha."','".$_SESSION['sesion_id_empresa']."')";
                    $resultInsert= mysql_query($sqlInsert);
                }
                while($rowRen = mysql_fetch_array($resultRenovaciones)){
                    $fechaRenovacion = $rowRen['fecha_renovacion'];
                     $pagado = $rowRen['Pagado'];
                     $hoy = date('Y-m-d');
                    $date1 = new DateTime($fechaRenovacion);
                    $date2 = new DateTime($hoy);
                    
                    if($date1<$date2){
                            session_destroy();
                            ?> 
                            


                            <div class='alert alert-danger mt-3'>Pendiente de renovaci&oacute;n, Porfavor consulte con el Administrador</div>

                            
                            
                            
                            <?php
                            exit;
                    }
                   
                }
                // } //cierre
            
               if($estado == "Activo"){
                   echo "1";

               }
               else{
                    session_destroy();
                    ?> <div class='alert alert-danger mt-3'>Su Empresa no esta Activa, Porfavor consulte con el Administrador</div> <?php
                   
               }
              
            }
            
           
            else{
                session_destroy();
                ?> <div class='alert alert-danger mt-3'>Codigo de Empresa no existe.</div> <?php

            }

    }
    else{
        session_destroy();         
        ?> <div class='alert alert-danger mt-3'>Los campos estan vacios</div> <?php       
    }

}catch(Exception $e){
    
    ?> <div class="alert alert-danger">Error: <?php echo "".$e; ?></div> <?php
}

?>   