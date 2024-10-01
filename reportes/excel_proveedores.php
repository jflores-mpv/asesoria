<?php
//ob_end_clean();
//Start session
error_reporting(0);
session_start();

//Include database connection details
require_once('../conexion.php');
require_once('class.ezpdf.php');
date_default_timezone_set("America/Guayaquil");

$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
 $sesion_empresa_nombre = $_SESSION['sesion_empresa_nombre'];
	$sesion_id_periodo_contable =$_GET['Periodo'];
	

	
	header("Content-Type: application/xls");
	header("Content-Disposition: attachment; filename=proveedores".date('Y:m:d:m:s').".xls");
	header("Pragma: no-cache");
	header("Expices:0");

 
    
    $sql = "SELECT
     ciudades.`id_ciudad` AS ciudades_id_ciudad,
     ciudades.`ciudad` AS ciudades_ciudad,
     ciudades.`id_provincia` AS ciudades_id_provincia,
     paises.`id_pais` AS paises_id_pais,
     paises.`pais` AS paises_pais,
     proveedores.`id_proveedor` AS proveedores_id_proveedor,
     proveedores.`nombre_comercial` AS proveedores_nombre_comercial,
     proveedores.`nombre` AS proveedores_nombre,
     proveedores.`direccion` AS proveedores_direccion,
     proveedores.`ruc` AS proveedores_ruc,
     proveedores.`telefono` AS proveedores_telefono,
     proveedores.`movil` AS proveedores_movil,
     proveedores.`fax` AS proveedores_fax,
     proveedores.`email` AS proveedores_email,
     proveedores.`web` AS proveedores_web,
     proveedores.`observaciones` AS proveedores_observaciones,
     proveedores.`id_ciudad` AS proveedores_id_ciudad,
     proveedores.`id_plan_cuenta` AS proveedores_id_plan_cuenta,
     provincias.`id_provincia` AS provincias_id_provincia,
     provincias.`provincia` AS provincias_provincia,
     provincias.`id_pais` AS provincias_id_pais
FROM
     `ciudades` ciudades INNER JOIN `proveedores` proveedores ON ciudades.`id_ciudad` = proveedores.`id_ciudad`
     INNER JOIN `provincias` provincias ON ciudades.`id_provincia` = provincias.`id_provincia`
     INNER JOIN `paises` paises ON provincias.`id_pais` = paises.`id_pais` and  proveedores.`id_empresa`='".$sesion_id_empresa."' ";
	if (isset($_GET['criterio_usu_per']))
		$sql .= " where proveedores.`nombre_comercial` like '%".$_GET['criterio_usu_per']."%' or  proveedores.`ruc` like '%".$_GET['criterio_usu_per']."%' ";
	              
   
	                
        if (isset($_GET['criterio_ordenar_por']))
		$sql .= sprintf(" order by %s %s", $_GET['criterio_ordenar_por'],$_GET['criterio_orden']);
	else
		$sql .= " order by proveedores.`nombre_comercial` asc";
     
        $result = mysql_query($sql) or die(mysql_error());
        $numero_filas = mysql_num_rows($result); // obtenemos el número de filas

    
    $columnas=5;
                
	$output .="<table > <thead>
	<tr >
	<th colspan='".$columnas."' style='border-style: solid;' >".$sesion_empresa_nombre." </th></tr><tr>
	<th colspan='".$columnas."' style='border-style: solid;' >REPORTE DE PROVEEDORES </th></tr>
	<tr>
	<th>R.U.C.</th>
	<th>Nombre</th>
	<th>".utf8_decode('Dirección')."</th>
    <th>".utf8_decode('Teléfono')."</th>
    <th>Ciudad</th>
	</tr>
	</thead> <tbody>";

$totalStock=0;
$totalProceso=0;

      while($row = mysql_fetch_array($result)){ 
            $numero ++;
            
        $output .="
		<tr>
		<td>'".$row['proveedores_ruc']."'</td>
		<td>".utf8_decode($row['proveedores_nombre_comercial'])."</td>
		<td>".utf8_decode($row['proveedores_direccion'])."</td>
		<td>".$row['proveedores_telefono']."</td>
		<td>".utf8_decode($row['ciudades_ciudad'])."</td>
		</tr>";

      }

	
	echo $output;
