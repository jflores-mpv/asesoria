<?php

	//require_once('../ver_sesion.php');

        date_default_timezone_set('America/Guayaquil');

	//Start session
	session_start();

	//Include database connection details
	require_once('../conexion.php');

        $txtAccion = $_POST['txtAccion'];
        $id_empresa_cookies = $_COOKIE["id_empresa"];
        $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
        $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
        date_default_timezone_set('America/Guayaquil');

if($txtAccion == 6){
    //BUQUEDA DE CUENTAS CONTABLES PARA LA EDICION O ELIMINAR
    	
	try
	{
    
    $sql="SELECT  id_periodo_contable FROM periodo_contable  where id_empresa ='".$sesion_id_empresa."'";
	//echo $sql;
	$result = mysql_query($sql) or die(mysql_error());					
	$sesion_id_empresa1=0;
	while ($row = mysql_fetch_array($result))
	{                        
		$sesion_id_empresa1 = $row['id_periodo_contable'];
	}
	
    // esta pagina retorna en la pagina: asientosContables.php 
    
    if(isset($_POST['queryString'])) 
	{
        $queryString = $_POST['queryString'];
            //$aux = $_POST['aux'];
			
	//	echo $queryString;            
        $a=0;
        if(strlen($queryString) >0) 
		{
		/* 	$sql="SELECT * FROM
		`libro_diario` libro_diario INNER JOIN `detalle_libro_diario` detalle_libro_diario 
		ON libro_diario.`id_libro_diario` = detalle_libro_diario.`id_libro_diario`
		INNER JOIN `plan_cuentas` plan_cuentas ON detalle_libro_diario.`id_plan_cuenta` = plan_cuentas.`id_plan_cuenta`
     WHERE libro_diario.`id_periodo_contable`=".$sesion_id_empresa1." and 
	 libro_diario.`numero_asiento` = $queryString order by id_detalle_libro_diario asc ; ";
ECHO $sql; */
			
            $query6 = "SELECT
     libro_diario.`id_libro_diario` AS libro_diario_id_libro_diario,
     libro_diario.`id_periodo_contable` AS libro_diario_id_periodo_contable,
     libro_diario.`numero_asiento` AS libro_diario_numero_asiento,
     libro_diario.`fecha` AS libro_diario_fecha,
     libro_diario.`total_debe` AS libro_diario_total_debe,
     libro_diario.`total_haber` AS libro_diario_total_haber,
     libro_diario.`descripcion` AS libro_diario_descripcion,
     libro_diario.`numero_comprobante` AS libro_diario_numero_comprobante,
     libro_diario.`tipo_comprobante` AS libro_diario_tipo_comprobante,
     detalle_libro_diario.`id_detalle_libro_diario` AS detalle_libro_diario_id_detalle_libro_diario,
     detalle_libro_diario.`id_libro_diario` AS detalle_libro_diario_id_libro_diario,
     detalle_libro_diario.`id_plan_cuenta` AS detalle_libro_diario_id_plan_cuenta,
     detalle_libro_diario.`debe` AS detalle_libro_diario_debe,
     detalle_libro_diario.`haber` AS detalle_libro_diario_haber,
     detalle_libro_diario.`id_periodo_contable` AS detalle_libro_diario_id_periodo_contable,
     plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_plan_cuenta,
     plan_cuentas.`codigo` AS plan_cuentas_codigo,
     plan_cuentas.`nombre` AS plan_cuentas_nombre,
     plan_cuentas.`clasificacion` AS plan_cuentas_clasificacion,
     plan_cuentas.`tipo` AS plan_cuentas_tipo,
     plan_cuentas.`categoria` AS plan_cuentas_categoria,
     plan_cuentas.`nivel` AS plan_cuentas_nivel,
     plan_cuentas.`total` AS plan_cuentas_total,
     plan_cuentas.`id_empresa` AS plan_cuentas_id_empresa,
     plan_cuentas.`cuenta_banco` AS plan_cuentas_cuenta_banco
	FROM
		`libro_diario` libro_diario INNER JOIN `detalle_libro_diario` detalle_libro_diario 
		ON libro_diario.`id_libro_diario` = detalle_libro_diario.`id_libro_diario`
		INNER JOIN `plan_cuentas` plan_cuentas ON detalle_libro_diario.`id_plan_cuenta` = plan_cuentas.`id_plan_cuenta`
     WHERE plan_cuentas.`id_empresa`='".$sesion_id_empresa."' 
     AND libro_diario.`id_periodo_contable` = '".$sesion_id_empresa1."' and
	 libro_diario.`numero_asiento` = '" .$queryString ."' order by id_detalle_libro_diario asc ; ";
	 
	 if($sesion_id_empresa==41){
	   //  echo $query6;
	 }
//echo $query6;
    $result6 = mysql_query($query6) or die(mysql_error());
    $numero_filas = mysql_num_rows($result6); // obtenemos el número de filas

		if($result6) 
		{
			//echo "filas".$numero_filas;
            if($numero_filas > 0)
			{// cuando no hay datos envia 0  
                $cadena = "";
                $cadenaBancos = "";
                while ($row = mysql_fetch_assoc($result6))
				{    
                  if($row['plan_cuentas_cuenta_banco'] > 1)
				  {
                                    // consulta los bancos de la cuenta
                    $sqlBancos = "SELECT
                                     bancos.`id_bancos` AS bancos_id_bancos,
                                     bancos.`id_plan_cuenta` AS bancos_id_plan_cuenta,
                                     bancos.`saldo_conciliado` AS bancos_saldo_conciliado,
                                     bancos.`id_periodo_contable` AS bancos_id_periodo_contable,
                                     detalle_bancos.`id_detalle_banco` AS detalle_bancos_id_detalle_banco,
                                     detalle_bancos.`tipo_documento` AS detalle_bancos_tipo_documento,
                                     detalle_bancos.`numero_documento` AS detalle_bancos_numero_documento,
                                     detalle_bancos.`detalle` AS detalle_bancos_detalle,
                                     detalle_bancos.`valor` AS detalle_bancos_valor,
                                     detalle_bancos.`fecha_cobro` AS detalle_bancos_fecha_cobro,
                                     detalle_bancos.`fecha_vencimiento` AS detalle_bancos_fecha_vencimiento,
                                     detalle_bancos.`id_bancos` AS detalle_bancos_id_bancos,
                                     detalle_bancos.`estado` AS detalle_bancos_estado,
                                     detalle_bancos.`id_libro_diario` AS detalle_bancos_id_libro_diario
                                FROM
                                     `bancos` bancos INNER JOIN `detalle_bancos` detalle_bancos ON bancos.`id_bancos` = detalle_bancos.`id_bancos`
                                     WHERE bancos.`id_periodo_contable`='".$sesion_id_periodo_contable."' and bancos.`id_plan_cuenta`='".$row['plan_cuentas_id_plan_cuenta']."' and detalle_bancos.`id_libro_diario`='".$row['libro_diario_id_libro_diario']."' ;";
                                    $resultBancos = mysql_query($sqlBancos) or die(mysql_error());
                                    while ($rowBancos = mysql_fetch_assoc($resultBancos)) {
                                        $cadenaBancos=$rowBancos['bancos_id_bancos']."?".$rowBancos['detalle_bancos_id_detalle_banco']."?".$rowBancos['detalle_bancos_tipo_documento']."?".$rowBancos['detalle_bancos_numero_documento']."?".$rowBancos['detalle_bancos_detalle']."?".$rowBancos['detalle_bancos_fecha_cobro']."?".$rowBancos['detalle_bancos_fecha_vencimiento'];

                                        
                                    }
                                }
                                
                                
                                $cadena=$cadena."*".$numero_filas."?".$row['detalle_libro_diario_id_detalle_libro_diario']."?".$row['plan_cuentas_codigo']."?".$row['plan_cuentas_nombre']."?".$row['plan_cuentas_cuenta_banco']."?".$row['detalle_libro_diario_debe']."?".$row['detalle_libro_diario_haber']."?".$row['libro_diario_descripcion']."?".$row['libro_diario_fecha']."?".$row['libro_diario_numero_comprobante']."?".$row['libro_diario_id_libro_diario']."?".$row['plan_cuentas_id_plan_cuenta']."?".$row['libro_diario_tipo_comprobante'];

                            }
                            echo $cadenaBancos."î".$cadena;
                            //echo $cadena;
                        }
						else
						{
							 $cadena = "";
                             $cadenaBancos = "";
						
                                $cadena=$cadena."*"."1"."?"." "."?"." "."?"." "."?"." "."?"."0"."?"." 0"."?"." "."?".""."?"." "."?"." "."?"." "."?"." ";
								$cadena=$cadena."*"."2"."?"." "."?"." "."?"." "."?"." "."?"."0"."?"." 0"."?"." "."?".""."?"." "."?"." "."?"." "."?"." ";
								$cadena=$cadena."*"."3"."?"." "."?"." "."?"." "."?"." "."?"."0"."?"." 0"."?"." "."?".""."?"." "."?"." "."?"." "."?"." ";
								$cadena=$cadena."*"."4"."?"." "."?"." "."?"." "."?"." "."?"."0"."?"." 0"."?"." "."?".""."?"." "."?"." "."?"." "."?"." ";
      
                             echo $cadenaBancos."î".$cadena;
							 //echo "0";
						}
						//echo "cadena";
						  //   echo $cadenaBancos."î".$cadena;
						

              }
			  else 
			  {
                     echo 'ERROR: Hay un problema con la consulta.';
              }
            }
			else 
			{
                echo 'La longitud no es la permitida.';
                    // Dont do anything.
            } // There is a queryString.
    } 
	else 
	{
            echo 'No hay ningún acceso directo a este script!';
    }

    }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
}


?>