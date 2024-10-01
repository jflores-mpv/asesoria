<?php
//ob_end_clean();
//Start session

session_start();

//Include database connection details
require_once('../conexion.php');
require_once('class.ezpdf.php');
date_default_timezone_set("America/Guayaquil");

$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
// $sesion_id_empresa= 370;
//$sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
//$sesion_id_empresa = "52";
//echo "empresa".$sesion_id_empresa;
$pdf =& new Cezpdf('a4','Landscape');

$pdf->selectFont('fonts/courier.afm');
$pdf->ezSetCmMargins(1,1,1.5,1.5);
function ceros($valor){
    $s='';
	for($i=1;$i<=9-strlen($valor);$i++)
		$s.="0";
	return $s.$valor;
}
$datacreador = array (
	'Title'=>'Facturas',
	'Subject'=>'Saldo Inicial',
	'Author'=>'25 de junio',
	'Producer'=>'Macarena Lalama'
);

$pdf->addInfo($datacreador);
$pdf->ezText("<b>Fecha actual:</b> ".date("d/m/Y"), 10,array( 'justification' => 'right' ));

//$sesion_id_empresa = $_GET['Empresa_id']; //$_SESSION["sesion_id_empresa"];
	//;$_SESSION["sesion_empresa_nombre"];
	$sesion_id_periodo_contable =$_GET['Periodo'];// $_SESSION["sesion_id_periodo_contable"];
	$id_compras = $_GET["id_compras"];
	$fecha_desde_principal = explode(" ", $_GET['txtFechaDesde']);
	$fecha_hasta_principal = explode(" ", $_GET['txtFechaHasta']);
	$txtProveedor =  $_GET['txtProveedor'];
	$fecha_desde='2022-04-01';
	$fecha_hasta='2022-04-30';
  //      echo "1".$txtProveedor;
	if($txtProveedor=''){
		$txtProveedor =  '';
	} 
//        echo  "2".$txtProveedor;
	$fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
	$fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");
	$pdf->ezText("<b>REPORTE DE RETENCIONES</b>", 18,array( 'justification' => 'center' ));
	$pdf->ezText("<b>".strtoupper($sesion_empresa_nombre)."</b>", 14,array( 'justification' => 'center' ));
	$pdf->ezText("<b>Desde: ".strtoupper($fecha_desde_principal[0])."    Hasta: ".strtoupper($fecha_hasta_principal[0])."</b>\n", 12,array( 'justification' => 'center' ));
	
	
	
	$sql = "SELECT
	mcretencion.id,
	mcretencion.Factura_id,
	mcretencion.Fecha,
	dcretencion.Retencion_id,
	dcretencion.TipoImp,
	dcretencion.CodImp,
	dcretencion.Porcentaje,
	compras.id_compra AS compras_id_venta, 
	compras.numero_factura_compra AS compras_numero_factura_venta, 
	compras.id_proveedor AS compras_id_cliente, 
	substr(compras.fecha_compra,1,10) AS compras_fecha_venta,
	compras.sub_total AS compras_sub_total, compras.id_iva AS compras_id_iva,
	compras.total AS compras_total, compras.`numSerie` AS compras_numSerie,
	compras.`txtEmision` AS compras_txtEmision, compras.`txtNum` AS compras_txtNum,
	compras.`subtotal0` AS compras_subtotal0, compras.`subtotal12` AS compras_subtotal12, 
	compras.subtotal12*12/100 AS compras_iva, compras.descuento AS compras_descuento, 
	proveedores.id_proveedor AS proveedores_id_proveedor, 
	substr(proveedores.nombre_comercial,1,30) AS proveedores_nombre
	FROM
	`mcretencion`
	INNER JOIN dcretencion ON dcretencion.Retencion_id = mcretencion.Id
	INNER JOIN compras ON compras.id_compra = mcretencion.Factura_id
	INNER JOIN `proveedores` proveedores ON proveedores.id_proveedor = compras.id_proveedor
	where compras.`id_empresa`='".$sesion_id_empresa."' ";
	$sql .= "  and  compras.`fecha_compra` BETWEEN '".$fecha_desde."' and 
	'".$fecha_hasta."'  "; 
	
	$sql .= " ORDER BY dcretencion.Porcentaje "; 

	$result = mysql_query($sql) or die(mysql_error());
        $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
        $numero = 0;
        
       // echo "Numero de filas    ".$numero_filas;
        $data2 = array(
        );
        $sumaDescuentoGeneral=0;
        $sumaIvaGeneral=0;
        $nombre_retencion="";
        
        $titulos2 = array(
			//	'nombre_retencion' => '<b>Tipo de Retencion</b>',
        	'fecha' => '<b>Fecha</b>',
        	'numero_factura_venta' => '<b>No. de Factura</b>',
        	'nombre' => '<b>Proveedor</b>',
        	'total' => '<b>Total Neto</b>',
		  // 	'subTotal0' => '<b>Base 0</b>',
		  // 	'subTotal12' => '<b>Base 12</b>',
        	'sub_total' => '<b>Sub_total</b>',
        	'iva' => '<b>Iva</b>',
		  // 	'descuento' => '<b>Descuento</b>',
        	'retencion' => '<b>Valor retenido</b>'

        );
        $data1 = array(
        );
        $data3=array(

        );
        $data4=array(

        );
        

        $opciones1 = array(
        	'shadeCol'=>array(0.9,0.9,0.9),
        	'xOrientation'=>'center',
        	'width'=>750,
        	'cols'=>array(
        		'nombre_retencion'=>array('justification'=>'center','width'=>300)
        	)
        );
        $opciones2 = array(
        	'shadeCol'=>array(0.9,0.9,0.9),
        	'xOrientation'=>'center',
        	'width'=>750,
        	'cols'=>array(
        		'#' => '<b>#</b>',
			//'nombre_retencion' => '<b>Tipo de Retencion</b>',
        		'fecha' => array('justification'=>'left','width'=>70),
        		'numero_factura_venta' =>array('justification'=>'left','width'=>120),
        		'nombre' =>array('justification'=>'left','width'=>280),
        		'total' => array('justification'=>'right','width'=>70),
				// 	'subTotal0' =>array('justification'=>'right','width'=>70),
				// 	'subTotal12' =>array('justification'=>'right','width'=>70),
        		'sub_total' =>array('justification'=>'right','width'=>70),
        		'iva' =>array('justification'=>'right','width'=>70),
        		'descuento' => array('justification'=>'right','width'=>70),
        		'retencion' => array('justification'=>'right','width'=>70)
        		
        	)
        );
        $opciones3 = array(
        	'shadeCol'=>array(0.9,0.9,0.9),
        	'xOrientation'=>'center',
        	'width'=>750,
        	'cols'=>array(
        		'total'=>array('justification'=>'right','width'=>470),	
        		
        		'stotal'=>array('justification'=>'right','width'=>70),
        		'sSubtotal'=>array('justification'=>'right','width'=>70),
        		'fuente'=>array('justification'=>'right','width'=>70),
        		'iva'=>array('justification'=>'right','width'=>70),
        	)
        );
        
        $opciones4 = array
        (
        	'shadeCol'=>array(0.9,0.9,0.9),
        	'xOrientation'=>'center',
        	'width'=>750,
        	'cols'=>array(
        		'descripcion'=>array('justification'=>'right','width'=>608),
        		'tg_fuente'=>array('justification'=>'right','width'=>70),
        		'tg_iva>'=>array('justification'=>'right','width'=>70)
        	)
        );

        $sumaIva = 0;
        $sumaDescuento = 0;
        $sumaSubtotal=0;
        $sumaTotal=0;
		while($row = mysql_fetch_array($result)) //for mayor    for($i=0; $i<$numero_filas; $i++)
		{
			$numero ++;
			if($nombre_retencion != $row["Porcentaje"] && $numero>1){
				
				if(	$pdf->ezGetY==50){
					$pdf->ezNewPage();
				}
				$pdf->ezTable($data1, $titulos1, '', $opciones1);
				$pdf->ezText($txttit, 1);
				$pdf->ezTable($data2, $titulos2, '', $opciones2);
				$pdf->ezText("\n", 1);
				$pdf->ezTable($data3, $titulos3, '', $opciones3);
				$pdf->ezText("\n\n\n", 10); 
				$data2='';
				$sumaIva = 0;
		  // $sumaDescuento = 0;
				$sumaRetenido= 0;
				$sumaTotal= 0;
				$sumaSubtotal= 0;
			}
			
			$titulos1 = array(
				'nombre_retencion' => utf8_decode($row["Porcentaje"])
			);

			
			

			$subtotal_venta = $subtotal_venta + $row["compras_sub_total"];
			$total_venta = $total_venta + $row["compras_total"];

			$iva_actual =number_format($row["compras_iva"], 2, '.', '')  ;
				// $descuento= number_format($row["compras_descuento"], 2, '.', '')  ;
			
			$retenido = $row["compras_sub_total"] *( $row["Porcentaje"] /100);
			$retenido = number_format($retenido, 2, '.', '')  ;
			
			
			$sumaIva = $sumaIva+ $iva_actual;
			$sumaIvaGeneral = $sumaIvaGeneral+ $iva_actual;
			
				// $sumaDescuento = $sumaDescuento + $descuento;
				// $sumaDescuentoGeneral = $sumaDescuentoGeneral + $descuento;
			
			$sumaRetenido = $sumaRetenido + $retenido;
			$sumaRetencionGeneral = $sumaRetencionGeneral + $retenido;
			
			$sumaSubtotal = $sumaSubtotal + $row["compras_sub_total"];
			$sumaSubtotalGeneral = $sumaSubtotalGeneral +$row["compras_sub_total"];
			
			$sumaTotal = $sumaTotal + $row["compras_total"];
			$sumaTotalGeneral = $sumaTotalGeneral + $row["compras_total"];
			

			$data2[] = array(
				'#'=>$numero,
				//	'nombre_retencion'=>$row["enlaces_compras_nombre"] ,       
				'numero_factura_venta'=>$row['compras_numSerie']."-".$row['compras_txtEmision']."-".ceros($row['compras_txtNum']),
				'nombre'=>utf8_decode($row["proveedores_nombre"]) ,
				// 	'subTotal0'=>$row["compras_subtotal0"] ,
				// 	'subTotal12'=>$row["compras_subtotal12"] ,
				'fecha'=>($row["compras_fecha_venta"]) ,
				'sub_total'=>number_format($row["compras_sub_total"], 2, '.', ' '),
				'total'=>number_format($row["compras_total"], 2, '.', ' '),
				'iva'=>number_format($row["compras_iva"], 2, '.', ' ') ,
				// 	'descuento'=>number_format($row["compras_descuento"], 2, '.', ' ') ,
				'retencion'=>number_format($retenido, 2, '.', ' ')  
			);
			
			$titulos3=array(       
				'total'=>'Total por tipo de retencion ==>            ',
				'stotal'=> number_format($sumaTotal, 2, '.', ' '),
				'sSubtotal'=> number_format($sumaSubtotal, 2, '.', ' '),
				'fuente'=> number_format($sumaIva, 2, '.', ' '),
				'iva'=> number_format($sumaRetenido, 2, '.', ' ')
			);

			$titulos4 =array
			(
				'descripcion'=>' Totales General de Retenciones ==>        '.'',
				// 'tg_total'=>number_format($sumaTotalGeneral, 2, '.', ' '),
				// 'tg_subtotal'=> number_format($sumaSubtotalGeneral, 2, '.', ' '),
				'tg_fuente'=>number_format($sumaIvaGeneral, 2, '.', ' '),
				'tg_iva'=> number_format($sumaRetencionGeneral, 2, '.', ' ')
			);

			
			$nombre_retencion=$row["Porcentaje"];
		}
		$pdf->ezTable($data1, $titulos1, '', $opciones1);
		$pdf->ezText($txttit, 1);
		$pdf->ezTable($data2, $titulos2, '', $opciones2);
		$pdf->ezText("\n", 1);
		$pdf->ezTable($data3, $titulos3, '', $opciones3);
		$pdf->ezText("\n\n\n", 10); 
		
		$pdf->ezTable($data4, $titulos4, '', $opciones4);
		$pdf->ezText("\n\n\n", 10); 
		$pdf->ezStartPageNumbers(550, 80, 10);
		$pdf->ezStream();
		$pdf->Output();
		mysql_close();
		mysql_free_result($result);
		?>
