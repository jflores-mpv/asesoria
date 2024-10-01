<?php
//ob_end_clean();
//Start session
error_reporting(0);
session_start();
function ceros($valor){
	$s='';
 for($i=1;$i<=9-strlen($valor);$i++)
	 $s.="0";
 return $s.$valor;
}
//Include database connection details
require_once('../conexion.php');
require_once('class.ezpdf.php');
date_default_timezone_set("America/Guayaquil");

 $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
    $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_id_usuario =  $_SESSION["sesion_id_usuario"];
    $id_empresa_cookies = $_COOKIE["id_empresa"];

   function tab($no)
    {// funcion para dar espacion o sangria a las cuentas
        for($x=1; $x<$no; $x++)
        $tab.="&nbsp;";
        return $tab;
    }
 $fecha_desde_principal = explode(" ", $_GET['txtFechaDesde']);
        $fecha_hasta_principal = explode(" ", $_GET['txtFechaHasta']);
        $fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
        $fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");
	
	header("Content-Type: application/xls");
	header("Content-Disposition: attachment; filename=libro_mayor_".date('Y:m:d:m:s').".xls");
	header("Pragma: no-cache");
	header("Expices:0");



    


	$output .="<table style=' border-collapse: collapse;
        width: 100%;'> <thead>
	<tr ><th colspan='7'  >".utf8_decode('LIBRO MAYOR')."</th>
	</tr>
	<tr >
		<th colspan='7'  >".strtoupper($sesion_empresa_nombre)."</th>
	</tr>
	<tr >
		<th colspan='7'  >Fecha desde: &#09;".$_GET['txtFechaDesde']." &#09; &#09; &#09;Fecha hasta: &#09;".$_GET['txtFechaHasta']."</th>
	</tr>
	
	<tr></tr>
	<tr></tr>
	</table>";

$sql = "SELECT
     mayorizacion.`id_mayorizacion` AS mayorizacion_id_mayorizacion,
     mayorizacion.`id_plan_cuenta` AS mayorizacion_id_plan_cuenta,
     mayorizacion.`id_periodo_contable` AS mayorizacion_id_periodo_contable,
     periodo_contable.`estado` AS periodo_contable_estado,
     plan_cuentas.`codigo` AS plan_cuentas_codigo,
     plan_cuentas.`nombre` AS plan_cuentas_nombre,
     plan_cuentas.`id_empresa` AS plan_cuentas_id_empresa,
     libro_diario.`fecha` AS libro_diario_fecha, libro_diario.`numero_asiento` AS numero_asiento
     
FROM
     `mayorizacion` mayorizacion INNER JOIN `periodo_contable` periodo_contable ON mayorizacion.`id_periodo_contable` = periodo_contable.`id_periodo_contable`
     INNER JOIN `plan_cuentas` plan_cuentas ON mayorizacion.`id_plan_cuenta` = plan_cuentas.`id_plan_cuenta`
     INNER JOIN `libro_diario` libro_diario ON periodo_contable.`id_periodo_contable` = libro_diario.`id_periodo_contable` ";

        if ($_GET['txtIdPlanCuenta'] >= 1){
		$sql .= " WHERE libro_diario.`fecha` BETWEEN '".$fecha_desde."' and '".$fecha_hasta."' AND plan_cuentas.`id_empresa` = '".$sesion_id_empresa."'
		AND plan_cuentas.`id_plan_cuenta` = '".$_GET['txtIdPlanCuenta']."' AND periodo_contable.`id_periodo_contable` = '".$sesion_id_periodo_contable."' ";}
                else {$sql .= " WHERE libro_diario.`fecha` BETWEEN '".$fecha_desde."' and '".$fecha_hasta."' 
                AND plan_cuentas.`id_empresa` = '".$sesion_id_empresa."' AND plan_cuentas.`id_plan_cuenta` like '".$_GET['txtIdPlanCuenta']."%' 
                AND periodo_contable.`id_periodo_contable` = '".$sesion_id_periodo_contable."' ";}
                
	if (isset($_GET['criterio_ordenar_por']))
		$sql .= " GROUP BY plan_cuentas.`codigo` order by ".$_GET['criterio_ordenar_por']." ".$_GET['criterio_orden']."";
	else
		$sql .= " GROUP BY plan_cuentas.`codigo` order by plan_cuentas.`codigo` asc ";
        if (isset($_GET['criterio_mostrar']))
		$sql .= " LIMIT ".((int)$_GET['criterio_mostrar']);

        $result = mysql_query($sql) or die(mysql_error());
        $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
        $numero = 0;
       
        // echo $sql."***    ".$numero_filas;
        $data2 = array(
                 );
       while($row = mysql_fetch_array($result)){ //************************* FOR MAYOR ****************************************
            $numero ++;
            $mayorizacion_id_plan_cuenta = $row['mayorizacion_id_plan_cuenta'];
            $plan_cuentas_codigo = $row['plan_cuentas_codigo'];
            $plan_cuentas_nombre = $row['plan_cuentas_nombre'];


           
          	
             

            $sql2 = "SELECT
     detalle_libro_diario.`id_libro_diario`,
     detalle_libro_diario.`debe`,
     detalle_libro_diario.`haber`,
     libro_diario.`id_libro_diario` AS libro_diario_id_libro_diario,
     libro_diario.`fecha` AS libro_diario_fecha,
     libro_diario.`numero_asiento` AS numero_asiento
     
FROM
     `libro_diario` libro_diario INNER JOIN `detalle_libro_diario` detalle_libro_diario 
     ON libro_diario.`id_libro_diario` = detalle_libro_diario.`id_libro_diario`
           AND libro_diario.id_periodo_contable= detalle_libro_diario.`id_periodo_contable`
            WHERE libro_diario.`fecha` BETWEEN '".$fecha_desde."' and '".$fecha_hasta."' and 
            detalle_libro_diario.`id_plan_cuenta`='".$mayorizacion_id_plan_cuenta."' AND 
            detalle_libro_diario.`id_periodo_contable`='".$sesion_id_periodo_contable."' ";
            
    if (isset($_GET['criterio_ordenar_por']))
		$sql2 .= " order by ".$_GET['criterio_ordenar_por']." ".$_GET['criterio_orden']."";
	else
		$sql2 .= "  order by plan_cuentas.`codigo` asc ";
// echo $sql2;
        
            $result2=mysql_query($sql2);
            $debe_detalle_mayorizacion = array();
            $haber_detalle_mayorizacion = array();
            $id_libro_diario = array();
            $id_libro_diario2 = "";
            $fecha = "";
            $numero_comprobante = "";
            $b=0;
            $sumadebe = 0;
            $sumahaber = 0;
            $numero_filas_detalle_mayorizacion = mysql_num_rows($result2); // obtenemos el número de filas
            if($numero_filas_detalle_mayorizacion > 0){
                 $output .="<table>
          	<tr></tr>
          	<tr style='border-style: solid;'>
          	<td colspan='3'>".utf8_decode('Código').": ".$plan_cuentas_codigo."</td>
          	<td colspan='4'>Cuenta: ".utf8_decode($plan_cuentas_nombre)."</td>
          	</tr>";
          	 $output .="<tr style='border-style: solid;'>
          	<td ><b>".utf8_decode('Número')."</b></td>
          	<td ><b>Cpte. Nro.</b></td>
          	<td ><b>Fecha</b></td>
          	<td ><b>".utf8_decode('Descripción')."</b></td>
          	<td ><b>".utf8_decode('Débito')."</b></td>
          	<td ><b>".utf8_decode('Crédito')."</b></td>
          	<td ><b>Saldo</b></td>
          	</tr>";
          	$saldo=0;
            }
           
            while($row2=mysql_fetch_array($result2))//permite ir de fila en fila de la tabla
            {
                $sumadebe = $sumadebe + $row2['debe'];
                $sumahaber = $sumahaber + $row2['haber'];
                $debe_detalle_mayorizacion[$b] = $row2['debe'];
                $haber_detalle_mayorizacion[$b] = $row2['haber'];
                $id_libro_diario[$b] = $row2['id_libro_diario'];
                $b++;
            }
            $data1 = "";
           
            for($j=0; $j<$numero_filas_detalle_mayorizacion; $j++){

                $sql3 = "SELECT
                         libro_diario.`id_libro_diario`,
                         libro_diario.`fecha`,
                         libro_diario.`numero_comprobante`,
                         libro_diario.`descripcion`,
                        
                         libro_diario.`tipo_comprobante`, libro_diario.`numero_asiento`
                         
                         
                    FROM
                         `libro_diario` libro_diario
                    WHERE libro_diario.`fecha` BETWEEN '".$fecha_desde."' and '".$fecha_hasta."' AND libro_diario.`id_libro_diario`='".$id_libro_diario[$j]."';  ";
                $result3=mysql_query($sql3);
                while($row3=mysql_fetch_array($result3))//permite ir de fila en fila de la tabla
                {
                    $id_libro_diario2 = $row3['id_libro_diario'];
                    $fecha = $row3['fecha'];
                    $numero_asiento = $row3['numero_asiento'];
                    
                    $numero_comprobante = $row3['numero_comprobante'];
                    $tipo_comprobante = $row3['tipo_comprobante'];
                    $descripcion = $row3['descripcion'];
                     $descripcion = $row3['descripcion'];
                }
                if($tipo_comprobante == "Diario"){
                    $letra_tipo_compro = "D-";
                }
                if($tipo_comprobante == "Ingreso"){
                    $letra_tipo_compro = "I-";
                }
                if($tipo_comprobante == "Egreso"){
                    $letra_tipo_compro = "E-";
                }
                $fecha2 = explode(" ", $fecha);
            
                $saldo = $saldo+  $debe_detalle_mayorizacion[$j] -$haber_detalle_mayorizacion[$j]; 

$output .= "<tr style='border-left: 2px solid black; border-right: 2px solid black;'>
    <td>".$numero_asiento."</td>
    <td>".$letra_tipo_compro.$numero_comprobante."</td>
    <td>".$fecha2[0]."</td>
    <td>".utf8_decode($descripcion)."</td>
    <td>".number_format($debe_detalle_mayorizacion[$j], 2, '.', ',')."</td>
    <td>".number_format($haber_detalle_mayorizacion[$j], 2, '.', ',')."</td>
    <td>".number_format($saldo, 2, '.', ',')."</td>
</tr>";



                
                //segunda
                $saldo_string = "";
                $saldo_deudor = 0;
                $saldo_acreedor =0;
                if($sumadebe > $sumahaber){
                    $saldo_deudor = $sumadebe - $sumahaber;
                    $saldo_string = "SD";
                }
                if($sumadebe < $sumahaber){
                    $saldo_acreedor = $sumahaber - $sumadebe;
                    $saldo_string = "SA";
                }
                if($saldo_deudor == "0" && $saldo_acreedor =="0"){
                    $saldo_string = "S";
                }
            }
           

            if($sumadebe == 0 & $sumahaber == 0){
                $numero --;
            }else{
                
              $output .="<tr style='border-style: solid;'>
    <td colspan='3'></td>
    <td >SUMAS:</td>
    <td >".number_format($sumadebe, 2, '.', ',')."</td>
    <td >".number_format($sumahaber, 2, '.', ',')."</td>
    <td >".number_format($saldo_deudor+$saldo_acreedor, 2, '.', ',')."</td>
</tr>
<tr></tr> <tr></tr> </table>";

          	
             
                
            }

         

           
            
          
        }
	 
$sqlEmpresa= " SELECT `razonSocial`, `nombreContador` FROM `empresa`  WHERE empresa.`id_empresa`=$sesion_id_empresa ";
      
    $resultEmpresa= mysql_query($sqlEmpresa);
    while($rEmpresa = mysql_fetch_array($resultEmpresa)){
        $razonSocialEmpresa = $rEmpresa['razonSocial'];
        $nombreContador=$rEmpresa['nombreContador'];
        
    }
    
	
		
		 $output .="<table>
		  <tr></tr>
		  <tr></tr>
		  <tr></tr>
		 <tr>
		 <td></td>
		 <td>___________________</td>
		 
		 <td>_________________________</td>
		
		 <td>___________________</td>
		 </tr>
		 
		 <tr>
		 <td></td>
		 <th>".$nombreContador."</th>
		 
		 <th>".utf8_decode($razonSocialEmpresa)."</th>
		
		 <td></td>
		 </tr>
		 
		 <tr>
		 <td></td>
		 <td>DPTO. CONTABILIDAD</td>
		 
		 <td>REPRESENTANTE LEGAL</td>
		 
		 <td> </td>
		 </tr>
		 </table>";	
		echo $output;

	?>
