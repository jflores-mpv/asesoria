<?php
   session_start();
	# Incluyendo librerias necesarias #
	 
	require_once('../conexion.php');
    require "./code128.php";
    
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];

    
    function ceros($valor){
               $s='';
        for($i=1;$i<=9-strlen($valor);$i++)
                $s.="0";
        return $s.$valor;
    }
    function medirMulticel($cellWidth, $cellHeight, $nombre,$pdf){
        // $cellWidth=55;
        // $cellHeight=5;
        if($pdf->GetStringWidth($nombre)<$cellWidth){
            $line=1;
        }else{
            $textLength=strlen($nombre);
            $errMargin=20;
            $startChar=0;
            $maxChar=0;
            $textArray=array();
            $tmpString='';
            while($startChar< $textLength){
                while($pdf->GetStringWidth($tmpString)<($cellWidth-$errMargin)&&($startChar+$maxChar)<$textLength){
                    $maxChar++;
                    $tmpString=substr($nombre,$startChar,$maxChar);
                }
                $startChar= $startChar+$maxChar;
                array_push($textArray,$tmpString);
                $maxChar=0;
                $tmpString='';
            }
            $line=count($textArray);
        }
        
        return $line;
    }
    
    $fecha_desde_principal = explode(" ", $_GET['txtFechaDesde']);
    $fecha_hasta_principal = explode(" ", $_GET['txtFechaHasta']);
    // $txtProveedor =  $_GET['txtProveedor'];
    
    $fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
    $fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");

	$areas= $_GET['areas'];
	$nombreArea='';
    if($areas!='0'){
		$sqlArea="SELECT `id_centro_costo`, `descripcion` FROM `centro_costo` WHERE id_centro_costo=$areas ";
		$resultArea = mysql_query($sqlArea) or die(mysql_error());
		while($rowArea = mysql_fetch_array($resultArea)){
			$nombreArea = $rowArea['descripcion'];
		}
		$nombreArea = ' de '.$nombreArea;
		

	}else{
		$nombreArea=' por areas';
	}

    $pdf = new PDF_Code128('L','mm','A4');
    $pdf->SetMargins(4,10,4);
    $pdf->AddPage();
    
    
    $resultEmpresa=mysql_query( "select * from empresa where id_empresa='".$sesion_id_empresa."' ");
	$Oempresa=mysql_fetch_array($resultEmpresa);
    
    # Encabezado y datos de la empresa #
    $pdf->SetFont('Arial','B',10);
    $pdf->SetTextColor(0,0,0);
    $pdf->MultiCell(0,5,utf8_decode(strtoupper($Oempresa['nombre'])),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode(strtoupper($Oempresa['razonSocial'])),0,'C',false);
    $pdf->MultiCell(0,5,strtoupper('Reporte de compras '.$nombreArea),0,'C',false);
    
    $borde = 1;
    $relleno = 1;
    
    $borde3 = 0;
    $relleno3 = 0;
    
    $borde2 = 1;
    $relleno2 = 1;
    
    $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetFont('Arial','',12);
    

    $sqlCentrosCostos="SELECT id_centro_costo ,descripcion FROM `centro_costo` WHERE `empresa`=$sesion_id_empresa";
	if($areas!='0'){
		$sqlCentrosCostos .=" and id_centro_costo=$areas";
	}

    $resultCentrosCostos = mysql_query($sqlCentrosCostos);
	$cc=0;
	$contadorfilas=0;
	$result = array();
	while($rowCC = mysql_fetch_array($resultCentrosCostos)){
    
                
        $sql = "SELECT
        compras.id_compra AS compras_id_venta,
        compras.numero_factura_compra AS compras_numero_factura_venta,
        compras.id_proveedor AS compras_id_cliente,
        compras.fecha_compra AS compras_fecha_venta,
        compras.sub_total AS compras_sub_total,
        compras.id_iva AS compras_id_iva,
        compras.total AS compras_total,
        compras.`numSerie` AS compras_numSerie,
        compras.`txtEmision` AS compras_txtEmision,
        compras.`txtNum` AS compras_txtNum,
        compras.`subtotal0` AS compras_subtotal0,
        compras.`subtotal12` AS compras_subtotal12,
        compras.subtotal12*12/100 AS compras_iva,
        compras.`numSerie` AS compras_numSerie,
        compras.`txtEmision` AS compras_txtEmision,
        compras.`txtNum` AS compras_txtNum,

        detalle_compras.`cantidad` as detalle_compras_cantidad,
        detalle_compras.`valor_unitario`  as detalle_compras_valor_unitario,
        detalle_compras.`des` as detalle_compras_des,
        detalle_compras.`valor_total`  as detalle_compras_valor_total,

        productos.iva as productos_iva,
        productos.producto as productos_producto,
        cc.descripcion AS centro_costo_nombre,
        cc.id_centro_costo AS centro_costo_id,

        proveedores.id_proveedor AS proveedores_id_cliente,
        proveedores.nombre_comercial AS proveedores_nombre,
        proveedores.direccion AS proveedores_direccion,
        proveedores.ruc AS proveedores_cedula
      
       FROM
        `proveedores` proveedores
        INNER JOIN `compras` compras ON proveedores.id_proveedor = compras.id_proveedor 
       INNER JOIN  detalle_compras ON detalle_compras.`id_compra` = compras.`id_compra` 
	   INNER JOIN productos on productos.id_producto =  detalle_compras.`id_producto` ";

    $sql .="  INNER JOIN (Select id_centro_costo, descripcion from centro_costo where empresa =$sesion_id_empresa   and id_centro_costo=".$rowCC['id_centro_costo']." ) as cc ON
    cc.id_centro_costo = detalle_compras.idBodega";

	   $sql.=" where compras.`id_empresa`='".$sesion_id_empresa."'  AND
       DATE_FORMAT(compras.fecha_compra, '%Y-%m-%d') >='".$_GET['txtFechaDesde']."' and
       DATE_FORMAT(compras.fecha_compra, '%Y-%m-%d')<='".$_GET['txtFechaHasta']."' ";
    if($_GET['txtProveedor']!='0'){
         $sql .="  AND  proveedores.id_proveedor = ".$_GET['txtProveedor'];
    }
    
   
        $sql .=" and compras.codSustento='".intval($_GET['codSustento'])."'and compras.TipoComprobante='".intval($_GET['txtTipoComprobante'])."'  ";

        // $sql.= "and detalle_ventas.`idBodega`='".$rowCC['id_centro_costo']."' "; 

        // $sql.= " GROUP BY ventas.id_venta ORDER BY ventas.numero_factura_venta ".$_GET['orden']."  ;"; 
        $result[$cc] = mysql_query($sql) or die(mysql_error());
        $contadorfilas = mysql_num_rows( $result[$cc] );
        $num=0;
        $suma_base12=0;
        $suma_base0=0;
        $subtotal_compra=0;
        $total_compra =0;
        $sumaDescuentos=0;
        $sumaSubtotal =0;
        $sumatotal = 0;
        $sumaIva= 0 ;
        
            while($row = mysql_fetch_array( $result[$cc])) {
            if($areas=='0'&& $num==0){
                $pdf->SetFont('Arial','',12); 
                $anchoTitulo = strlen($row["centro_costo_nombre"])*3.7;
                $pdf->Cell( $anchoTitulo,10,"AREA :".$row['centro_costo_nombre'],$borde,0,'L',$relleno);
                $pdf->Ln();
            } 
            if($num==0){
                $pdf->SetFont('Arial','',9);  
                $pdf->Cell(10,10,'#',$borde3,0,'L',$relleno2);
                $pdf->Cell(15,10,'Fecha',$borde3,0,'L',$relleno2);
                $pdf->Cell(20,10,'No. Factura',$borde3,0,'L',$relleno2);
                $pdf->Cell(25,10,utf8_decode('Identificaci贸n'),$borde3,0,'L',$relleno2);
                $pdf->Cell(40,10,'Proveedor',$borde3,0,'L',$relleno2);
                $pdf->Cell(55,10,'Detalle',$borde3,0,'L',$relleno2);
                $pdf->Cell(15,10,'Cantidad',$borde3,0,'L',$relleno2);
                $pdf->Cell(25,10,'Valor Unitario',$borde3,0,'L',$relleno2);
                $pdf->Cell(20,10,'Descuento',$borde3,0,'L',$relleno2);
                $pdf->Cell(25,10,'Subtotal',$borde3,0,'L',$relleno2);
                $pdf->Cell(15,10,'IVA %',$borde3,0,'L',$relleno2);
                $pdf->Cell(25,10,'Total Neto',$borde3,0,'R',$relleno2);
                $pdf->Ln();
            }
    
                
            $num++;
            $compras_id_compra = $row["compras_id_venta"];

            $suma_base12 = $suma_base12 + $row["compras_subtotal12"];//suma de base12
            $suma_base0 = $suma_base0 + $row["compras_subtotal0"];//suma de base0
            $subtotal_compra = $subtotal_compra + $row["compras_sub_total"];//suma de subtotal

            $total_compra = $total_compra + $row["compras_total"]; //suma de total

            
            $subtotale = isset($row["compras_sub_total"])?$row["compras_sub_total"]:0;
            $totale = isset($row["compras_total"])?$row["compras_total"]:0;

                       
             $numeroCompra= $row['compras_numSerie']."-".$row['compras_txtEmision']."-".$row['compras_txtNum'];
                            
            $ivaProducto = ($row['productos_iva']=='Si')?$row["compras_id_iva"]:0;
          
            $subtotalProducto = ($row["detalle_compras_cantidad"] * $row["detalle_compras_valor_unitario"])-$row["detalle_compras_des"];
             $subtotalProducto = number_format($subtotalProducto, 2, '.', '');
            
            $ivaProducto =  $subtotalProducto*($ivaProducto/100);
            $ivaProducto =  number_format($ivaProducto, 2, '.', '');
            
            $totalProducto =  $subtotalProducto + $ivaProducto;
             $totalProducto =   number_format($totalProducto, 2, '.', '');
          
//-------------


        
// $row["proveedores_nombre"]="Lorem ipsum, dolor sit amet consectetur adipisicing elit. Necessitatibus commodi similique, incidunt eum veniam explicabo. Eum corporis unde ipsa architecto corrupti ullam enim saepe illo numquam amet accusantium, quae dolores.";
$line =medirMulticel(55,5,$row["productos_producto"],$pdf);
$variable= ($line==1)?10:5;

// $line2 =medirMulticel(15,5,$row["compras_fecha_venta"],$pdf);
// $variable2= ($line2==1)?10:5;

// $line3 =medirMulticel(55,5,$row["productos_producto"],$pdf);
// $variable3= ($line3==1)?10:5;
//=============

             $pdf->SetFont('Arial','',9);  
             $pdf->Cell(10,10,$num,$borde2,0,'L',$relleno3);
             $anchoFecha = (strlen($row["compras_fecha_venta"])>0 )?5:10;
             $y= $pdf->GetY();
             $pdf->MultiCell(15,$anchoFecha,substr($row["compras_fecha_venta"], 0, 11),1,'C');
             $pdf->SetY($y);
             $pdf->SetX(29);
            //  $pdf->Cell(15,10,$row["compras_fecha_venta"],$borde2,0,'L',$relleno3);
            $anchoNumeroCompra = (strlen($numeroCompra)>10 )?5:10;
            $y= $pdf->GetY();
            $pdf->MultiCell(20, $anchoNumeroCompra,$numeroCompra ,1,'C');
            //  $pdf->Cell(20,10, $numeroCompra ,$borde2,0,'L',$relleno3);
             $pdf->SetY($y);
             $pdf->SetX(49);
             $pdf->Cell(25,10,$row["proveedores_cedula"],$borde2,0,'L',$relleno3);

             $anchoNombreProveedor = (strlen($row["proveedores_nombre"])>21 )?5:10;
             $y= $pdf->GetY();
             $pdf->MultiCell(40,$anchoNombreProveedor,utf8_decode(substr($row["proveedores_nombre"], 0, 47)),1,'C');
             $pdf->SetY($y);
             $pdf->SetX(114);
            //  $pdf->Cell(40, $anchoNombreProveedor ,utf8_decode($row["proveedores_nombre"]),$borde2,0,'L',$relleno3);
             $y= $pdf->GetY();
             $pdf->SetFont('Arial','',9);  
             $anchoProducto = (strlen($row["productos_producto"])>26 )?5:10;
             $pdf->MultiCell(55, $variable,utf8_decode(substr($row["productos_producto"], 0, 40)),1,'C');
            //  $pdf->MultiCell(55,10,utf8_decode($row["productos_producto"]),$borde2,0,'L',$relleno3);
             $y1= $pdf->GetY();
             $pdf->SetY($y);
             $pdf->SetX(169);
             $pdf->Cell(15,10,number_format($row["detalle_compras_cantidad"], 2, '.', ''),$borde2,0,'L',$relleno3);
             $pdf->Cell(25,10,number_format($row["detalle_compras_valor_unitario"], 2, '.', ''),$borde2,0,'L',$relleno3);
             $pdf->Cell(20,10, number_format($row["detalle_compras_des"], 2, '.', ''),$borde2,0,'L',$relleno3);
             $pdf->Cell(25,10,$subtotalProducto ,$borde2,0,'L',$relleno3);
             $pdf->Cell(15,10, $ivaProducto ,$borde2,0,'L',$relleno3);
             $pdf->Cell(25,10,$totalProducto,$borde2,0,'R',$relleno3);
             $pdf->Ln();
            // number_format($número, 2, '.', '');
             $sumaDescuentos =  $sumaDescuentos +$row["detalle_compras_des"]; 
             $sumaSubtotal =  $sumaSubtotal +$subtotalProducto;
             $sumaIva =  $sumaIva +$ivaProducto;
             $sumatotal =  $sumatotal +$totalProducto;
        }
        if($contadorfilas>0){
            $pdf->SetFont('Arial','',9);  
            $pdf->Cell(10,10,'',$borde3,0,'L',$relleno2);
            $pdf->Cell(15,10,'',$borde3,0,'L',$relleno2);
            $pdf->Cell(20,10,'',$borde3,0,'L',$relleno2);
            $pdf->Cell(20,10,'',$borde3,0,'L',$relleno2);
            $pdf->Cell(40,10,'',$borde3,0,'L',$relleno2);
            $pdf->Cell(55,10,'',$borde3,0,'L',$relleno2);
            $pdf->Cell(20,10,'',$borde3,0,'L',$relleno2);
            $pdf->Cell(25,10,'TOTAL',$borde3,0,'L',$relleno2);
            $pdf->Cell(20,10, $sumaDescuentos,$borde3,0,'L',$relleno2);
            $pdf->Cell(25,10,$sumaSubtotal ,$borde3,0,'L',$relleno2);
            $pdf->Cell(15,10,$sumaIva,$borde3,0,'L',$relleno2);
            $pdf->Cell(25,10,$sumatotal,$borde3,0,'R',$relleno2);
            $pdf->Ln(20);
    
        }
      
        // $pdf->Cell(230,10,'','',0,'R','');
        // $pdf->Cell(25,10,$subtotal_compra,$borde,0,'R',$relleno);
        // $pdf->Cell(25,10,$total_compra,$borde,0,'R',$relleno);
        // $pdf->Ln();
        // $pdf->Ln(4);

        $cc++;
    }

    # Nombre del archivo PDF #
    $pdf->Output("I","RepoteDeCompras.pdf",true);