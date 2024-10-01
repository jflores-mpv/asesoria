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
  $cmbCosto = $_GET['cmbCentro'];
        $fecha_desde_principal = explode(" ", ($_GET['fecha_desde']));
        $fecha_hasta_principal = explode(" ", ($_GET['fecha_hasta']));
        $fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
        $fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");

    
	
	header("Content-Type: application/xls");
	header("Content-Disposition: attachment; filename=libro_diario_".date('Y:m:d:m:s').".xls");
	header("Pragma: no-cache");
	header("Expices:0");



    


	$output .="<table style=' border-collapse: collapse;
        width: 100%;'> <thead>
	<tr ><th colspan='4'  >".utf8_decode('LIBRO DIARIO')."</th>
	</tr>
	<tr >
		<th colspan='4'  >".strtoupper($sesion_empresa_nombre)."</th>
	</tr>
	<tr >
	<th >Fecha desde: </th>
	<th>".$_GET['fecha_desde']."</th>
	<th >Fecha hasta: </th>
	<th>".$_GET['fecha_hasta']."</th>
	<th></th>
	</tr>
	<tr></tr>
	<tr></tr>
	</table>";
	
$sql = "SELECT
     libro_diario.`id_libro_diario` AS libro_diario_id_libro_diario,     
     libro_diario.`id_periodo_contable` AS libro_diario_id_periodo_contable,
     libro_diario.`numero_asiento` AS libro_diario_numero_asiento,
     libro_diario.`fecha` AS libro_diario_fecha,
     libro_diario.`total_debe` AS libro_diario_total_debe,
     libro_diario.`total_haber` AS libro_diario_total_haber,
     libro_diario.`descripcion` AS libro_diario_descripcion,
     libro_diario.`tipo_comprobante` AS libro_diario_tipo_comprobante,
     libro_diario.`numero_comprobante` AS libro_diario_numero_comprobante,
     libro_diario.`centroCosto` AS centroCosto,
     periodo_contable.`id_periodo_contable` AS periodo_contable_id_periodo_contable,
     periodo_contable.`fecha_desde` AS periodo_contable_fecha_desde,
     periodo_contable.`fecha_hasta` AS periodo_contable_fecha_hasta,
     periodo_contable.`estado` AS periodo_contable_estado,
     periodo_contable.`ingresos` AS periodo_contable_ingresos,
     periodo_contable.`id_empresa` AS periodo_contable_id_empresa,
     periodo_contable.`gastos` AS periodo_contable_gastos

FROM
     `periodo_contable` periodo_contable INNER JOIN `libro_diario` libro_diario ON periodo_contable.`id_periodo_contable` = libro_diario.`id_periodo_contable` ";

if (isset($_GET['criterio_usu_per']))
    $sql .= " where periodo_contable.`id_empresa`='".$sesion_id_empresa."' AND libro_diario.`numero_asiento` like '%".$_GET['criterio_usu_per']."%' AND periodo_contable.`id_periodo_contable` = '".$_GET['txtIdPeriodoContable']."' AND libro_diario.`fecha` BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."' ";


if (isset($_GET['criterio_ordenar_por']))
    $sql .= " order by ".$_GET['criterio_ordenar_por']." ".$_GET['criterio_orden']." ";
   
else
    $sql .= " order by libro_diario.`numero_asiento` asc";
if (isset($_GET['criterio_mostrar']))
	$sql .=" LIMIT ". $_GET['criterio_mostrar']."";
//echo $sql;
        $result = mysql_query($sql) or die(mysql_error());
        $aux1=0;
        //$ixx = 0;
        while($row = mysql_fetch_assoc($result)) { // ********************** FOR MAYOR **************************
            $id_libro_diario = $row["libro_diario_id_libro_diario"];
             $libro_diario_fecha = $row["libro_diario_fecha"];
             $libro_diario_numero_asiento = $row["libro_diario_numero_asiento"];
             $tipo_comprobante = $row["libro_diario_tipo_comprobante"];
             $descripcion = $row["libro_diario_descripcion"];
             $libro_diario_numero_comprobante = $row["libro_diario_numero_comprobante"];
//             $detalle_libro_diario_haber = $row["detalle_libro_diario_haber"];

                //$ixx = $ixx+1;
                //$data[] = array_merge($row, array('num'=>$ixx));

             $fecha = explode(" ", $libro_diario_fecha);
           
             
            $output .="<table style=' border-collapse: collapse;
        width: 100%;'>
            <tr style='border-style: solid;'>
            <td  style='text-align:center;'><b>".$tipo_comprobante.' Nro.'.$libro_diario_numero_comprobante."</b></td>
   
            <td style='text-align:center;' ><b>Asiento Nro.".$libro_diario_numero_asiento."</b></td>
            <td style='text-align:center;' colspan='2'><b>".$fecha[0]."</b></td>
            </tr>
            ";

                $sql2 = "SELECT
     detalle_libro_diario.`id_detalle_libro_diario` AS detalle_libro_diario_id_detalle_libro_diario,
     detalle_libro_diario.`id_libro_diario` AS detalle_libro_diario_id_libro_diario,
     detalle_libro_diario.`id_plan_cuenta` AS detalle_libro_diario_id_plan_cuenta,
     detalle_libro_diario.`debe` AS detalle_libro_diario_debe,
     detalle_libro_diario.`haber` AS detalle_libro_diario_haber,     
     plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_plan_cuenta,
     plan_cuentas.`codigo` AS plan_cuentas_codigo,
     plan_cuentas.`nombre` AS plan_cuentas_nombre,
     plan_cuentas.`clasificacion` AS plan_cuentas_clasificacion,
     plan_cuentas.`tipo` AS plan_cuentas_tipo,
     plan_cuentas.`categoria` AS plan_cuentas_categoria,
     plan_cuentas.`nivel` AS plan_cuentas_nivel,
     plan_cuentas.`id_empresa` AS plan_cuentas_id_empresa,
     plan_cuentas.`total` AS plan_cuentas_total
FROM
     `plan_cuentas` plan_cuentas INNER JOIN `detalle_libro_diario` detalle_libro_diario ON plan_cuentas.`id_plan_cuenta` = detalle_libro_diario.`id_plan_cuenta` 
     WHERE  plan_cuentas.`id_empresa`='".$sesion_id_empresa."' AND detalle_libro_diario.`id_libro_diario`='".$id_libro_diario."' AND detalle_libro_diario.`id_periodo_contable` = '".$_GET['txtIdPeriodoContable']."';";

        $result2=mysql_query($sql2);
        $detalle_libro_diario_id_detalle_libro_diario = array();
        $plan_cuentas_codigo = array();
        $plan_cuentas_nombre = array();
        $detalle_libro_diario_debe = array();
        $detalle_libro_diario_haber = array();
        $b=0;
         //   echo "   *************       ".$sql2;
        $numero_filas_detalle_libro_diario = mysql_num_rows($result2); // obtenemos el número de filas

 
         $output .="<tr style='border-style: solid;'>
            <td  style='text-align:center;'><b>".utf8_decode('Código')."</b></td>
            <td  style='text-align:center;'><b>Cuenta</b></td>
            <td  style='text-align:center;' ><b>".utf8_decode('Débito')."</b></td>
            <td  style='text-align:center;' ><b>".utf8_decode('Crédito')."</b></td>
            </tr>";
        
        $sumadebe = 0;
        $sumahaber = 0;
        $data2 = "";
        while($row2=mysql_fetch_assoc($result2))
        {

            $sumadebe = $sumadebe + $row2['detalle_libro_diario_debe'];
            $sumahaber = $sumahaber + $row2['detalle_libro_diario_haber'];

            $output .="<tr style=' border-left: 2px solid black;border-right: 2px solid black;'>
            <td>".$row2['plan_cuentas_codigo']."</td>
            <td>".utf8_decode($row2['plan_cuentas_nombre'])."</td>
            <td>".number_format(floatval($row2['detalle_libro_diario_debe']), 2, '.', ',')."</td>
            <td>".number_format(floatval($row2['detalle_libro_diario_haber']), 2, '.', ',')."</td>
            </tr>";
        } 
        

     

        
        $output .="<tr style='border-style: solid;'>
            <td colspan='2'><b>Detalle: </b>".utf8_decode($descripcion)."</td>
            <td>".number_format($sumadebe, 2, '.', ',')."</td>
            <td>".number_format($sumahaber, 2, '.', ',')."</td>
            </tr>
            <tr></tr>
            <tr></tr>
            </table>";
    
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
		 
		 <td></td>
		 </tr>
		 </table>";	
		echo $output;

	?>
