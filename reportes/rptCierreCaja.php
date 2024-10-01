<?php
error_reporting(0);
// require('fpdf17/fpdf.php');
include('pdf_mc_table_compras_areas.php');
require('../clases/funciones.php');
require_once('../conexion.php');
session_start();
//$sesion_empresa_imagen = $_SESSION["sesion_empresa_imagen"];


$varEfectivoCompras='3';
$varBancoCompras='8';
$varCuentaCompras='4';
$varTarjeta='16';
	class PDF extends PDF_MC_Table
	{
		// Cabecera de página
		function Header()
		{
			$this->SetFont('Arial','B',8);
			$this->Cell(60);
			$this->Ln();
		}
	}

	$pdf = new PDF_MC_Table('P','mm',array(80,108.1));
	$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
	$sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
	$sesion_id_periodo_contable = $_SESSION['sesion_id_periodo_contable'];
	$sesion_empresa_ruc = $_SESSION['sesion_empresa_ruc'];
	$sesion_ciudades_ciudad = $_SESSION['sesion_ciudades_ciudad'];
	$sesion_empresa_direccion = $_SESSION['sesion_empresa_direccion'];

	$sesion_codigo_lug = $_SESSION['sesion_codigo_lug'];
	$total_ventas_anuladas=0;
	$fecha = ($_GET['fecha']);
	
	$idEncabezado = $_GET['id_venta'];

    $sqlEncabezado= "SELECT cierre_caja_encabezado.`id`,cierre_caja_encabezado.`numero_cierre`, cierre_caja_encabezado.`fecha`, cierre_caja_encabezado.`hora`, cierre_caja_encabezado.`id_usuario`, cierre_caja_encabezado.`total`, cierre_caja_encabezado.`caja`, usuarios.login FROM `cierre_caja_encabezado` INNER JOIN usuarios ON usuarios.id_usuario = cierre_caja_encabezado.id_usuario WHERE id=$idEncabezado ";
    $resultEncabezado = mysql_query($sqlEncabezado);
    $nombreUsuario ='';
    $fechaEncabezado = '';
    $horaEncabezado = '';
    $cajaEncabezado ='';
    while($row = mysql_fetch_array($resultEncabezado)){
        $nombreUsuario = $row['login'];
        $fechaEncabezado = $row['fecha'];
        $horaEncabezado = $row['hora'];
        $cajaEncabezado = $row['caja'];
        $saldoEncabezado = $row['total'];
        $numero_cierre = $row['numero_cierre'];
    }
    
      $sql="SELECT
    cobrospagos.tipo,
    SUM(cobrospagos.valor) AS total,
    cobrospagos.id_forma,
    enlaces_compras.tipo_cpra,
    tipo_movim_cpra.nombre,
    tipo_movim_cpra.id_tipo_mov_cpra
FROM
    `cobrospagos`
INNER JOIN compras ON cobrospagos.id_factura = compras.id_compra
INNER JOIN enlaces_compras ON enlaces_compras.id = cobrospagos.id_forma
INNER JOIN tipo_movim_cpra ON enlaces_compras.tipo_cpra = tipo_movim_cpra.id_tipo_mov_cpra
WHERE
    DATE_FORMAT(compras.fecha_compra, '%Y-%m-%d') = '".$fecha."'  AND cobrospagos.id_empresa ='".$sesion_id_empresa."' AND 
    cobrospagos.documento =1 AND tipo_movim_cpra.id_tipo_mov_cpra IN($varEfectivoCompras,$varBancoCompras,$varCuentaCompras,$varTarjeta) 
GROUP BY enlaces_compras.id";

	$result = mysql_query($sql) or die(mysql_error());
	$sumaTotalCompras=0;
	$lcTotal_Anticipo=0;
	$listaCompras=array();
	while($row = mysql_fetch_array($result)) 
	{
		$total= $row['total'];
		$sumaTotalCompras=$sumaTotalCompras+$total;
	}
	$total=0;
	$sql = "SELECT
	ventas.`id_empresa` AS ventas_id_empresa,
	ventas.`id_venta` AS ventas_id_venta,
	ventas.`numero_factura_venta` AS ventas_numero_factura_venta,
	ventas.`id_cliente` AS ventas_id_cliente,
	ventas.`id_forma_pago` AS ventas_id_forma_pago,
	formas_pago.`nombre` AS formas_pago_nombre,
	formas_pago.`id_forma_pago`,
	formas_pago.id_tipo_movimiento AS formas_tipoMovimiento,
	cobrospagos.documento cobrospagos_documento,
	SUM(cobrospagos.valor) AS total
	FROM
	ventas
	INNER JOIN cobrospagos ON cobrospagos.id_factura = ventas.id_venta
	LEFT JOIN `formas_pago` formas_pago ON
	cobrospagos.id_forma = formas_pago.`id_forma_pago`
	WHERE  	cobrospagos.documento = 0 AND formas_pago.id_tipo_movimiento IN(1, 2, 4,16,17) 
	";
	
	$sql .= " and cobrospagos.`id_empresa`='".$sesion_id_empresa."' ";
	
	if($fecha !=''){
		
		$sql.=" AND  DATE_FORMAT(fecha_venta, '%Y-%m-%d')= '$fecha' ";
	}

	$sql.=" GROUP BY formas_pago.id_tipo_movimiento ORDER BY ventas.`id_forma_pago` ";

	$result = mysql_query($sql) or die(mysql_error());

		$sqlVanuladas = "SELECT
	ventas.`id_empresa` AS ventas_id_empresa,
	ventas.`id_venta` AS ventas_id_venta,
	ventas.`numero_factura_venta` AS ventas_numero_factura_venta,
	ventas.`id_cliente` AS ventas_id_cliente,
	ventas.`id_forma_pago` AS ventas_id_forma_pago,
	formas_pago.`nombre` AS formas_pago_nombre,
	formas_pago.`id_forma_pago`,
	formas_pago.id_tipo_movimiento AS formas_tipoMovimiento,
	cobrospagos.documento cobrospagos_documento,
	SUM(cobrospagos.valor) AS total
	FROM
	ventas
	INNER JOIN cobrospagos ON cobrospagos.id_factura = ventas.id_venta
	LEFT JOIN `formas_pago` formas_pago ON
	cobrospagos.id_forma = formas_pago.`id_forma_pago`
	WHERE  ventas.estado='Pasivo' AND
	cobrospagos.documento = 0 AND formas_pago.id_tipo_movimiento IN(1, 2, 4,16,17) 
	";
	
	$sqlVanuladas .= " and cobrospagos.`id_empresa`='".$sesion_id_empresa."' ";
	
	if($fecha !=''){
		
		$sqlVanuladas.=" AND  DATE_FORMAT(fecha_venta, '%Y-%m-%d')= '$fecha' ";
	}

	$sqlVanuladas.=" GROUP BY formas_pago.id_tipo_movimiento, ventas.estado ORDER BY ventas.`id_forma_pago` ";



	$resultVanuladas = mysql_query($sqlVanuladas) or die(mysql_error());
	while($rowAnu = mysql_fetch_array($resultVanuladas)) 
	{
	    $total_ventas_anuladas = $rowAnu['total'];
	}

	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(60, 10, $sesion_empresa_nombre, 1,1,'C',0);
	$alto_cab2=4;
	$mar_left1=30;
	$pdf->Cell(60, 10, 'CIERRE DE CAJA No.'.$numero_cierre, "0,B",1,'L',0);
	$pdf->SetFont('Arial', 'B', 9);
	$cab_col2=50;
	$cab_alto1=5;
	$cab_alto2=5;
	$pdf->Cell(30,$cab_alto1, 'Caja:', 0,0,'L');
    $pdf->Cell(60,$cab_alto1, $cajaEncabezado, 0,1,'L');
	$pdf->Cell(30,$cab_alto1, 'Valor Inicial: ', 0,0,'L');
    $pdf->Cell(60,$cab_alto1, $saldoEncabezado, 0,1,'L');
	$pdf->Cell(30,$cab_alto1, 'Fecha: ', 0,0,'L');
    $pdf->Cell(60,$cab_alto1, $fechaEncabezado.'  '.$horaEncabezado, 0,1,'L');
	$alto_cab2=4;
	$alto2=7;
	$det_col2=30;
	$lcFormaPago=0;
	$lcTotal_FPago=0;
	$lcTotal_General=0;
	$sumaTotal=0;
	$listaIngresos =array();
	$fp_ing= 0;
		$listaIngresos['valor'][$fp_ing]= 'SISTEMA';
		$listaIngresos['nombre'][$fp_ing]= 'FORMA DE PAGO';
		$listaIngresos['valor2'][$fp_ing]= 'USUARIO';
	
		$fp_ing= 1;
	while($row = mysql_fetch_array($result)) 
	{
	    $id_sistema = $row['id_forma_pago'];
	    $listaIngresos['id'][$fp_ing]= $id_sistema;
		$listaIngresos['valor'][$id_sistema]= $row['total'];
		$listaIngresos['nombre'][$id_sistema]= $row['formas_pago_nombre'];
		$lcTotal_FPago= $row['total'];
		$sumaTotal= $sumaTotal+floatval($lcTotal_FPago);

	 $fp_ing++;
	}

	 	$sql = "SELECT
	formas_pago.id_tipo_movimiento,
	formas_pago.nombre,
	formas_pago.id_forma_pago,
	SUM(cierre_caja_detalle.valor) AS total
	FROM
	`cierre_caja_encabezado`
	INNER JOIN cierre_caja_detalle ON cierre_caja_detalle.id_cierre = cierre_caja_encabezado.id
	INNER JOIN formas_pago ON formas_pago.id_forma_pago = cierre_caja_detalle.id_forma
	WHERE
	cierre_caja_encabezado.id='".$idEncabezado."' AND formas_pago.id_tipo_movimiento IN(1, 2, 4, 16, 17)
	GROUP BY
	formas_pago.id_forma_pago";

	$result = mysql_query($sql) or die(mysql_error());
	$sumaTotalIngresosCaja=0;
	$lcTotal_Anticipo=0;
	while($row2 = mysql_fetch_array($result)) 
	{
	    $id_cja = $row2['id_forma_pago'];
    	if (!isset($listaIngresos['valor'][$id_cja])) {
    	    $listaIngresos['id'][$fp_ing]= $id_cja;
    	$listaIngresos['nombre'][$id_cja]= $row2['nombre'];
    	$listaIngresos['valor'][$id_cja] = 0;
        $listaIngresos['valor2'][$id_cja] = $row2['total'];
         $fp_ing++;
        }else{
            $listaIngresos['valor2'][$id_cja]= $row2['total'];
        }
		$total= $row2['total'];
		$sumaTotalIngresosCaja=$sumaTotalIngresosCaja+$total;
		
	}
$totalAperturaCaja = $sumaTotal - $sumaTotalCompras;
    

	$pdf->Ln();
	$pdf->SetFontSize(12);
		$pdf->SetX(5);
	$pdf->Cell(70,$cab_alto1, 'Cierre de caja', 1,1,'C');
	$pdf->Ln();


$z=0;
$sumarX=40;
$sumarY=0;
$cab_alto1=6;

$yActual= $pdf->GetY();
$xActual=  $pdf->GetX();

	$d=0;
	$pdf->SetX(5);
	$pdf->SetFont('Arial','B',10);
$pdf->SetWidths(array(20,20,30));
$pdf->SetAligns(array('C','C','C'));
$pdf->SetLineHeight(5);

        $pdf->Row(array(
        $listaIngresos['nombre'][0],
        $listaIngresos['valor'][0],
        $listaIngresos['valor2'][0]
      ));
      
      $pdf->SetFont('Arial','',10);
for($t=1; $t<$fp_ing; $t++){
    $pdf->SetX(5);
    $id_fila = $listaIngresos['id'][$t];
    
    $nombre = utf8_decode($listaIngresos['nombre'][$id_fila]);
    $valor = number_format($listaIngresos['valor'][$id_fila], 2, '.', ',');
    $valor2 = number_format($listaIngresos['valor2'][$id_fila], 2, '.', ',');

    $pdf->Row(array($nombre, $valor, $valor2));
    
    $d++;
    $posY= $pdf->GetY();
}

  	$pdf->SetFont('Arial', 'B', 9);
	    	$pdf->SetFontSize(9);
	    	$pdf->SetX(5);
	    	
$pdf->Cell(40,$cab_alto1,'Total Ventas Anuladas : ' , 1,0,'L');
$pdf->Cell(30,$cab_alto1,$total_ventas_anuladas , 1,1,'L');
$sumaTotalIngresos = $sumaTotalIngresosCaja - $total_ventas_anuladas;
	$pdf->SetX(5);
$pdf->Cell(40,$cab_alto1,'Total Ingresos : ' , 1,0,'L');
$pdf->Cell(30,$cab_alto1,$sumaTotalIngresos , 1,1,'L');


	$totalApertura= $totalAperturaCaja ;
	$totalCierre=$sumaTotalIngresos;
	if($totalApertura !=$totalCierre){
	    	$pdf->SetFont('Arial', 'B', 9);
	    	$pdf->SetFontSize(9);
	    	$pdf->SetX(5);
$pdf->Cell(40,$cab_alto1,'Total Descuadre : ' , 1,0,'L');
$pdf->Cell(30,$cab_alto1,number_format($totalApertura-$totalCierre,2) , 1,1,'L');

	}
	$alturaFinal = $pdf->GetY();
	$alturaFinal = ceil($alturaFinal)+94.1;

  

	$pdf2 = new PDF_MC_Table('P','mm',array(80,$alturaFinal));
	
    $pdf2->AliasNbPages();
	$pdf2->AddPage();
	$pdf2->SetFont('Arial', 'B', 9);
	$pdf2->SetFillColor(255,255,255);
	$pdf2->Cell(60, 10, $sesion_empresa_nombre, 1,1,'C',0);
	$alto_cab2=4;
	$mar_left1=30;
	$pdf2->Cell(60, 10, 'CIERRE DE CAJA No.'.$numero_cierre, "0,B",1,'L',0);
	$pdf2->SetFont('Arial', 'B', 9);
	$cab_col2=50;
	$cab_alto1=5;
	$cab_alto2=5;
	$pdf2->Cell(30,$cab_alto1, 'Caja:', 0,0,'L');
    $pdf2->Cell(60,$cab_alto1, $cajaEncabezado, 0,1,'L');
	$pdf2->Cell(30,$cab_alto1, 'Valor Inicial: ', 0,0,'L');
    $pdf2->Cell(60,$cab_alto1, $saldoEncabezado, 0,1,'L');
	$pdf2->Cell(30,$cab_alto1, 'Fecha: ', 0,0,'L');
    $pdf2->Cell(60,$cab_alto1, $fechaEncabezado.'  '.$horaEncabezado, 0,1,'L');
	$alto_cab2=4;
	$alto2=7;
	$det_col2=30;
	$lcFormaPago=0;
	$lcTotal_FPago=0;
	$lcTotal_General=0;
	$sumaTotal=0;

		$pdf2->Ln();
	$pdf2->SetFontSize(12);
		$pdf2->SetX(5);
	$pdf2->Cell(70,$cab_alto1, 'Cierre de caja', 1,1,'C');
	$pdf2->Ln();


$z=0;
$sumarX=40;
$sumarY=0;
$cab_alto1=6;

$yActual= $pdf2->GetY();
$xActual=  $pdf2->GetX();

	$d=0;
	$pdf2->SetX(5);
	$pdf2->SetFont('Arial','B',10);
$pdf2->SetWidths(array(20,20,30));
$pdf2->SetAligns(array('C','C','C'));
$pdf2->SetLineHeight(5);
        
        $pdf2->SetX(5);
        $pdf2->Row(array(
        $listaIngresos['nombre'][0],
        $listaIngresos['valor'][0],
        $listaIngresos['valor2'][0]
      ));
   
    $pdf2->SetFont('Arial','',10);
    for($t=1; $t<$fp_ing; $t++){
        $pdf2->SetX(5);
        $id_fila = $listaIngresos['id'][$t];
        $nombre = utf8_decode($listaIngresos['nombre'][$id_fila]);
        $valor = number_format($listaIngresos['valor'][$id_fila], 2, '.', ',');
        $valor2 = number_format($listaIngresos['valor2'][$id_fila], 2, '.', ',');
        $pdf2->Row(array($nombre, $valor, $valor2));
        $d++;
        $posY= $pdf2->GetY();
    }
    
      	$pdf2->SetFont('Arial', 'B', 9);
    	    	$pdf2->SetFontSize(9);
    	    	$pdf2->SetX(5);
    	    	
    $pdf2->Cell(40,$cab_alto1,'Total Ventas Anuladas : ' , 1,0,'L');
    $pdf2->Cell(30,$cab_alto1,$total_ventas_anuladas , 1,1,'L');
   
    
    	$pdf2->SetX(5);
    $pdf2->Cell(40,$cab_alto1,'Total Ingresos : ' , 1,0,'L');
    $pdf2->Cell(30,$cab_alto1,$sumaTotalIngresos , 1,1,'L');
    
    
    	$totalApertura= $totalAperturaCaja ;
    	$totalCierre=$sumaTotalIngresos;
    	if($totalApertura !=$totalCierre){
    	    	$pdf2->SetFont('Arial', 'B', 9);
    	    	$pdf2->SetFontSize(9);
    	    	$pdf2->SetX(5);
    $pdf2->Cell(40,$cab_alto1,'Total Descuadre : ' , 1,0,'L');
    $pdf2->Cell(30,$cab_alto1,number_format($totalApertura-$totalCierre,2) , 1,1,'L');
    	}
	$pdf2->Output();
	?>