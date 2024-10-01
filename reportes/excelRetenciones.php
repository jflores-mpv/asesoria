<?php
//ob_end_clean();
//Start session

session_start();

//Include database connection details
require_once('../conexion.php');
require_once('class.ezpdf.php');
date_default_timezone_set("America/Guayaquil");

$sesion_id_empresa = $_SESSION["sesion_id_empresa"];

	$sesion_id_periodo_contable =$_GET['Periodo'];// $_SESSION["sesion_id_periodo_contable"];
	$id_compras = $_GET["id_compras"];
	$fecha_desde_principal = explode(" ", $_GET['txtFechaDesde']);
	$fecha_hasta_principal = explode(" ", $_GET['txtFechaHasta']);
	$txtProveedor =  $_GET['txtProveedor'];
// 	$fecha_desde='2022-04-01';
// 	$fecha_hasta='2022-04-30';
	if($txtProveedor=''){
		$txtProveedor =  '';
	} 
	$fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
	$fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");
	
	function ceros($valor){
    $s='';
	for($i=1;$i<=9-strlen($valor);$i++)
		$s.="0";
	return $s.$valor;
}
	header("Content-Type: application/xls");
	header("Content-Disposition: attachment; filename=documento_exportado_".date('Y:m:d:m:s').".xls");
	header("Pragma: no-cache");
	header("Expices:0");

 $resultEmpresa=mysql_query( "select * from empresa where id_empresa='".$sesion_id_empresa."' ");
	$Oempresa=mysql_fetch_array($resultEmpresa);
	
	{$output .="<table class='table table-bordered'>
	<thead>
	<tr><th colspan='6'>".utf8_decode(strtoupper($Oempresa['nombre']))."</th></tr>
	<tr><th colspan='6'>".utf8_decode(strtoupper($Oempresa['razonSocial']))."</th></tr>
	<tr><th colspan='6'>REPORTE DE RETENCIONES</th></tr>
    <tr>
    <th></th>
	<th>FECHA DESDE:</th>
	<th>$fecha_desde</th>
	<th>FECHA HASTA:</th>
	<th>$fecha_hasta</th>
	<th></th>
    </tr>
    <tr></tr>
	<tr>
	<th>TIPO DE ".utf8_decode('RETENCIÓN')."</th>
	<th># COMPRA</th>
	<th>PROVEEDOR</th>
	<th># ".utf8_decode('RETENCIÓN')."</th>
	<th>BASE IMPONIBLE</th>
	<th>VALOR RETENIDO</th>
	</tr>
	</thead>
	<tbody>
	";

	$sql="SELECT d.*
    FROM dcretencion d 
    JOIN mcretencion m ON d.Retencion_id = m.Id 
    JOIN compras c ON m.Factura_id = c.id_compra 
    where c.id_empresa=$sesion_id_empresa and  c.fecha_compra >= '".$fecha_desde."' AND c.fecha_compra <= '".$fecha_hasta."' ORDER BY d.CodImp ";

	$result = mysql_query($sql) or die(mysql_error());
    $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
    $numero = 0;
    $currentCodImp = null;
  
    $nombre_retencion="";
	while($row = mysql_fetch_array($result)) 
	{
	     $BasImp= $row['BaseImp'];
	    $CodImp= $row['CodImp'];
	    $CodPorcentaje= $row['Porcentaje'];
	    
	     if ($currentCodImp != $CodImp) {
	         $sql2 = "SELECT  d.* ,p.nombre,m.Numero,m.Serie,c.*

                    FROM dcretencion d 
                    JOIN mcretencion m ON d.Retencion_id = m.Id 
                    JOIN compras c ON m.Factura_id = c.id_compra 
                    JOIN proveedores p ON c.id_proveedor = p.id_proveedor 
                    
                    where d.CodImp=$CodImp and c.id_empresa=$sesion_id_empresa
                    
                    and  c.fecha_compra >= '".$fecha_desde."' AND c.fecha_compra <= '".$fecha_hasta."'
                    
                    and  m.anulado='0'
                    
                    ORDER BY d.CodImp, c.numero_factura_compra ";
		
		    $result2=mysql_query($sql2);
	        $sumaBaseImp=0;
            $sumaBasePor=0;
	      	while($row2=mysql_fetch_assoc($result2))
			{
			       $numeroCompra= $row2['numSerie']."-".$row2['txtEmision']."-".ceros($row2['numero_factura_compra']);
            	    
            	    $total= $row2['total'];
            	    $baseImp=$row2['BaseImp'];
            	    
            	    
            	    $porcentajeRetenido=$row2['BaseImp']*($row2['Porcentaje']/100);


				
				$descuento = is_null($row2["compras_descuento"])?0:$row2["compras_descuento"];
				$output .="
				<tr>
				<td>".$CodPorcentaje."</td>	
				<td>".$numeroCompra."</td>		
				<td>".utf8_decode($row2['nombre'])."</td>
				<td>".$row2['Serie']."-".ceros($row2['Numero'])."</td>
				<td>".$row2['BaseImp']."</td>
				<td>".$porcentajeRetenido."</td>	
				</tr>	";
				    $sumaBaseImp= $sumaBaseImp + $row2['BaseImp'];
                    $sumaBasePor= $sumaBasePor + $porcentajeRetenido;
			}  
				$output .="
				<tr>
				<td></td>		
				<td></td>
				<td></td>
				<td>TOTAL</td>	
				<td >".$sumaBaseImp."</td>
				<td>".$sumaBasePor."</td>	
				</tr>	";
	         
	     }
		}			
		
		$output .="
		</tbody>
		</table>
		";
		echo $output;
	}			

?>
