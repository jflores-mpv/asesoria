<?php

    require_once('../ver_sesion.php');

    //Start session
    session_start();

    //Include database connection details
    require_once('../conexion.php');


    $accion = $_POST['txtAccion'];
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
//	echo "aaa";
//echo $sesion_tipo_empresa;
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

        // Is the string length greater than 0?
			if(strlen($queryString) >0) 
			{
				$query = "SELECT
				enlaces_compras.`id` AS enlaces_compras_id,
				enlaces_compras.`nombre` AS enlaces_compras_nombre,
				enlaces_compras.`tipo` AS enlaces_compras_tipo,
				enlaces_compras.`codigo_sri` AS codigo_sri,
				
				'#cpte' as nro_cpte, 
				enlaces_compras.`porcentaje` AS enlaces_compras_porcentaje,
				enlaces_compras.`cuenta_contable` AS enlaces_compras_cuenta_contable
			FROM
				`enlaces_compras` enlaces_compras 
				WHERE enlaces_compras.`id_empresa`='".$sesion_id_empresa."' and CONCAT(enlaces_compras.`nombre`,enlaces_compras.`tipo`) LIKE '%$queryString%'  LIMIT 10; ";
				$result = mysql_query($query) or die(mysql_error());
				$numero_filas = mysql_num_rows($result); // obtenemos el número de filas
				if($result) 
				{
					if($numero_filas == 0)
					{
						echo "<div class='alert alert-danger'> No hay resultados con el parámetro ingresado. </div>";
					}
					else
					{
					//	echo "va a genere tabla";
					//	echo $sesion_tipo_empresa;
					//	echo $cont;
						echo "<table id='tblServicios".$cont."' table class='table table-condensed table-hover' >";
						echo "<thead>";
						echo "<tr>";
						echo "<th style='padding-left: 5px; padding-right: 5px;'>Nombre</th>  <th style='padding-left: 5px; padding-right: 5px;'>Tipo</th> <th style='padding-left: 5px; padding-right: 5px;'>Porcentaje</th><th style='padding-left: 5px; padding-right: 5px;'>Codigo</th><th style='padding-left: 5px; padding-right: 5px;'><a href='javascript: fn_cerrar_div();'><button type='button' class='btn btn-default' aria-label='Left Align'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></button></a></th>";
						echo "</tr>";
						echo "</thead>";
						echo "<tbody>";
						
						while ($row = mysql_fetch_array($result))
						{
                   //	echo '<tr onClick="fill10_FP_Cpras(\''.$cont.'\','.$row["enlaces_compras_id"].',\''.$row["enlaces_compras_id"]."*".$row["enlaces_compras_nombre"]." "."*".$row["enlaces_compras_tipo"]."*".$row["enlaces_compras_porcentaje"].'\');" style="cursor: pointer" title="Clic para seleccionar">'; 
						echo '<tr onClick="fill10_FP_Cpras(\''.$cont.'\','.$row["enlaces_compras_id"].',\''.$row["enlaces_compras_id"]."*".$row["enlaces_compras_nombre"]." "."*".$row["enlaces_compras_tipo"]."*".$row["enlaces_compras_porcentaje"]."*".$row["enlaces_compras_cuenta_contable"]."*".$fecha."*".$sesion_tipo_empresa.'\');" style="cursor: pointer" title="Clic para seleccionar">';
					//	echo "<td>".$row["enlaces_compras_id"]."</td>";
                        echo "<td>".$row["enlaces_compras_nombre"]."</td>";
                        echo "<td>".$row["enlaces_compras_tipo"]."</td>";
                        echo "<td>".$row["enlaces_compras_porcentaje"]."</td>";
                         echo "<td>".$row["codigo_sri"]."</td>";
                    //  echo "<td>".$row["enlaces_compras_cuenta_contable"]."</td>";
					//	echo "<td>".$fecha."</td>";
					//	echo "<td>".$sesion_tipo_empresa."</td>";
			            echo "</tr>";
                    }
                    echo "</tbody>";
                   echo"</table>";
                }
                } else {
                        echo 'ERROR: Hay un problema con la consulta.';
                }
        } else {
            echo 'La longitud no es la permitida.';
        }
    } else {
            echo 'No hay ningún acceso directo a este script!';
    }
}

    
    
?>