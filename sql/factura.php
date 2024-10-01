<?php
    require_once('../ver_sesion.php');

    //Start session
    session_start();

    //Include database connection details
    require_once('../conexion.php');

    $accion = $_POST['txtAccion'];
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
	$sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
  
   date_default_timezone_set('America/Guayaquil');
 
	if($accion == "4")
	{
		$fecha= date("Y-m-d");
		//echo $fecha;
		// Is there a posted query string?
		if(isset($_POST['queryString'])) 
		{
			$queryString = $_POST['queryString'];
			$cont = $_POST['cont'];
			$cont1=1;
        				
			if(strlen($queryString) >0) 
			{
			/* 	$query = "SELECT
				formas_pago.`nombre` AS formas_pago_nombre,
				formas_pago.`codigo` AS formas_pago_id_plan_cuenta,
				plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_plan_cuenta
			FROM
				`formas_pago_mae` formas_pago inner join `plan_cuentas` plan_cuentas
				on formas_pago.`codigo`=plan_cuentas.`codigo` and plan_cuentas.`id_empresa`='"+$sesion_id_empresa+"' 
				WHERE CONCAT(formas_pago.`nombre`) LIKE '%$queryString%'   LIMIT 10; ";
			
					$query = "SELECT
				formas_pago.`nombre` AS formas_pago_nombre,
				formas_pago.`id_plan_cuenta` AS formas_pago_id_plan_cuenta,
				formas_pago.`tipo` AS formas_pago_tipo,
				plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_plan_cuenta
				
			FROM
				`formas_pago` inner join `plan_cuentas` plan_cuentas
				on formas_pago.`id_plan_cuenta`=plan_cuentas.`codigo` and formas_pago.`id_empresa`='".$sesion_id_empresa."' 
				WHERE CONCAT(formas_pago.`nombre`) LIKE '%$queryString%'   LIMIT 10;";
				*/
				
					$query = "SELECT
				formas_pago.`id_forma_pago` AS formas_pago_id,	
				formas_pago.`nombre` AS formas_pago_nombre,
				formas_pago.`id_plan_cuenta` AS formas_pago_id_plan_cuenta,
				formas_pago.`id_tipo_movimiento` AS formas_pago_tipo,
				plan_cuentas.`codigo` AS plan_cuentas_id_plan_cuenta
				
			FROM
				`formas_pago` formas_pago inner join `plan_cuentas` plan_cuentas
				on formas_pago.`id_plan_cuenta`=plan_cuentas.`id_plan_cuenta` and plan_cuentas.`id_empresa`='".$sesion_id_empresa."' 
				WHERE CONCAT(formas_pago.`nombre`) LIKE '%$queryString%'   LIMIT 10;";
 
				$result = mysql_query($query) or die(mysql_error());
				//echo $query;
				$numero_filas = mysql_num_rows($result); // obtenemos el número de filas
				if($result) 
				{
					if($numero_filas == 0)
					{
						echo "<center><p><div class='alert alert-danger'><strong> No hay resultados con el parámetro ingresado. <strong></label></p></center>";
					}
					else
					{
					//	echo "<th style='padding-left: 5px; padding-right: 5px;'>Código</th>  <th style='padding-left: 5px; padding-right: 5px;'>Nombre</th>  <th style='padding-left: 5px; padding-right: 5px;'>Tipo</th> <th style='padding-left: 5px; padding-right: 5px;'>Porcentaje</th>  <th style='padding-left: 5px; padding-right: 5px;'>Cuenta</th><th style='padding-left: 5px; padding-right: 5px;'>Fecha</th>  <th style='padding-left: 5px; padding-right: 5px;'><a href='javascript: fn_cerrar_div();'><img align='right' src='images/cerrar2.png' width='16' height='16' alt='cerrar' title='Cerrar' /></a></th>";
						echo "<table id='tblServicios4".$cont."' class='table table-bordered table-condensed table-hover' border='0' >";
						echo "<thead>";
						echo "<tr>";
						echo "<th style='padding-left: 5px; padding-right: 5px;'>Código</th>  <th style='padding-left: 5px; padding-right: 5px;'>Nombre</th> <th style='padding-left: 5px; padding-right: 5px;'>Cuenta</th>  <th style='padding-left: 5px; padding-right: 5px;'></th> <th style='padding-left: 5px; padding-right: 5px;'></th>  <th style='padding-left: 5px; padding-right: 5px;'><a href='javascript: fn_cerrar_div();'><button type='button' class='btn btn-default' aria-label='Left Align'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></button></a></th>";
						echo "</tr>";
						echo "</thead>";
						echo "<tbody>";
						while ($row = mysql_fetch_array($result))
						{
							echo '<tr onClick="fill10_FP_Vtas(\''.$cont.'\','.$row["formas_pago_id_plan_cuenta"].',\''.$row["formas_pago_id_plan_cuenta"]."*".$row["formas_pago_nombre"]." "."*".$row["formas_pago_nombre"]."*".$row["plan_cuentas_id_plan_cuenta"]."*".$fecha."*".$row["formas_pago_tipo"]."*".$sesion_tipo_empresa."*".$row["formas_pago_id"].'\');" style="cursor: pointer" title="Clic para seleccionar">';                
							echo "<td>".$row["formas_pago_id_plan_cuenta"]."</td>";						
							echo "<td>".$row["formas_pago_nombre"]."</td>";
							echo "<td>".$row["plan_cuentas_id_plan_cuenta"]."</td>";
							echo "<td>".$fecha."</td>";
							echo "<td>".$row["formas_pago_tipo"]."</td>";
							
							echo "<td></td>";
							echo "</tr>";
						}
                    echo "</tbody>";
                   echo"</table>";
                }
                } else {
                        echo 'ERROR: Hay un problema con la consulta.';
                }
        }
		else 
		{
            echo 'La longitud no es la permitida.';
        }
    } else {
            echo 'No hay ningún acceso directo a este script!';
    }
}

?>