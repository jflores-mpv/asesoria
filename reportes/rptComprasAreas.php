<?php
   session_start();
	# Incluyendo librerias necesarias #
	 
	require_once('../conexion.php');
    require "./pdf_mc_table_compras_areas.php";
    
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];

    
    function ceros($valor){
               $s='';
        for($i=1;$i<=9-strlen($valor);$i++)
                $s.="0";
        return $s.$valor;
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

    $pdf = new PDF_MC_Table('L','mm','A4');
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
    

    $sqlCentrosCostos="SELECT id_centro_costo ,descripcion FROM `centro_costo` WHERE `empresa`=$sesion_id_empresa ";
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
 if($_GET['estado_factura']!='0'){
	       $sql.= " LEFT JOIN cobrospagos on cobrospagos.id_factura = compras.id_compra AND cobrospagos.documento=1 ";  
	    }
	    
    $sql .="  INNER JOIN (Select id_centro_costo, descripcion from centro_costo where empresa =$sesion_id_empresa   and id_centro_costo=".$rowCC['id_centro_costo']." ) as cc ON
    cc.id_centro_costo = detalle_compras.idBodega";

	   $sql.=" where compras.`id_empresa`='".$sesion_id_empresa."'  AND
       DATE_FORMAT(compras.fecha_compra, '%Y-%m-%d') >='".$_GET['txtFechaDesde']."' and
       DATE_FORMAT(compras.fecha_compra, '%Y-%m-%d')<='".$_GET['txtFechaHasta']."' ";
    if($_GET['txtProveedor']!='0'){
         $sql .="  AND  proveedores.id_proveedor = ".$_GET['txtProveedor'];
    }
     if($_GET['estado_factura']=='Pagadas'){
	       $sql.= " AND  cobrospagos.id_factura!=0 ";  
	    }else if($_GET['estado_factura']=='NoPagadas'){
	       $sql.= " AND  cobrospagos.id_factura=0 ";  
	    }
	    
   
        $sql .=" and compras.codSustento='".intval($_GET['codSustento'])."'and compras.TipoComprobante='".intval($_GET['txtTipoComprobante'])."'  ";

  if($_GET['estado_factura']!='0' ){
	       $sql.= " GROUP BY  compras.id_compra ";  
	    }
	    
        // $sql.= "and detalle_ventas.`idBodega`='".$rowCC['id_centro_costo']."' "; 

        // $sql.= " GROUP BY ventas.id_venta ORDER BY ventas.numero_factura_venta ".$_GET['orden']."  ;"; 
        
        // echo $sql;
        if($sesion_id_empresa==41){
            // echo $sql;
        }
        $result[$cc] = mysql_query($sql) or die(mysql_error());
        $contadorfilas = mysql_num_rows( $result[$cc] );
        $num=0;
        $suma_base12=0;
        $suma_base0=0;
        $sumaCantidades=0;
        $subtotal_compra=0;
        $total_compra =0;
        $sumaDescuentos=0;
        $sumaSubtotal =0;
        $sumatotal = 0;
        $sumaIva= 0 ;
        $data2=array();
//  $data2[0][]='#';
//  $data2[0][]='Fecha';
//  $data2[0][]='No. Factura';
//  $data2[0][]=utf8_decode('Identificacion');
//  $data2[0][]='Proveedor';
//  $data2[0][]='Detalle';
//  $data2[0][]='Cantidad';
//  $data2[0][]='V. Unitario';
//  $data2[0][]='Descuento';
//  $data2[0][]='Subtotal';
//  $data2[0][]='IVA %';
//  $data2[0][]='Total Neto';
      $pdf->SetWidths(array(10,15,20,25,40,55,20,20,20,25,15,25));
$pdf->SetLineHeight(5);
      
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
        $pdf->Cell(25,10,utf8_decode('IdentificaciÃ³n'),$borde3,0,'L',$relleno2);
        $pdf->Cell(40,10,'Proveedor',$borde3,0,'L',$relleno2);
        $pdf->Cell(55,10,'Detalle',$borde3,0,'L',$relleno2);
        $pdf->Cell(20,10,'Cantidad',$borde3,0,'L',$relleno2);
        $pdf->Cell(20,10,'V. Unitario',$borde3,0,'L',$relleno2);
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


             $pdf->SetFont('Arial','',9);  
            $data2[$num][]=$num;
             $data2[$num][]=$row["compras_fecha_venta"];
             $data2[$num][]=$numeroCompra;
             $data2[$num][]=$row["proveedores_cedula"];
             $data2[$num][]=$row["proveedores_nombre"];
             $data2[$num][]=utf8_decode($row["productos_producto"]);
             $data2[$num][]=number_format($row["detalle_compras_cantidad"], 2, '.', '');
             $data2[$num][]=number_format($row["detalle_compras_valor_unitario"], 2, '.', '');
             $data2[$num][]=number_format($row["detalle_compras_des"], 2, '.', '');
             $data2[$num][]=$subtotalProducto;
             $data2[$num][]=$ivaProducto;
             $data2[$num][]=$totalProducto;

            
            // number_format($n¨²mero, 2, '.', '');
             $sumaCantidades=$sumaCantidades+$row["detalle_compras_cantidad"];
             $sumaDescuentos=$sumaDescuentos +$row["detalle_compras_des"]; 
             $sumaSubtotal=$sumaSubtotal +$subtotalProducto;
             $sumaIva=$sumaIva +$ivaProducto;
             $sumatotal=$sumatotal +$totalProducto;
        }
         
         
        // if($contadorfilas>0){
        //     $num++;
        //     $data2[$num][]='';
        //  $data2[$num][]='';
        //  $data2[$num][]='';
        //  $data2[$num][]='';
        //  $data2[$num][]='';
        //  $data2[$num][]='TOTAL';
        //  $data2[$num][]=number_format($sumaCantidades, 2, '.', '');
        //  $data2[$num][]='';
        //  $data2[$num][]=$sumaDescuentos;
        //  $data2[$num][]=$sumaSubtotal;
        //  $data2[$num][]=$sumaIva;
        //  $data2[$num][]=$sumatotal;
        // }
      
        $cc++;
        $cantidadItem = 0;
            foreach($data2 as $item){
                $cantidadItem++;
        
                    $pdf->Row(array(
        $item[0],
        $item[1],
        $item[2],
        $item[3],
        $item[4],
        $item[5],
        $item[6],
        $item[7],
        $item[8],
        $item[9],
        $item[10],
        $item[11],
      ));

    }
        if($contadorfilas>0){
            $pdf->SetFont('Arial','',9);  

        $pdf->Cell(165,10,'TOTAL',$borde3,0,'R',$relleno2);
    
        $pdf->Cell(20,10,$sumaCantidades,$borde3,0,'L',$relleno2);
        $pdf->Cell(20,10,'',$borde3,0,'L',$relleno2);
        $pdf->Cell(20,10,$sumaDescuentos,$borde3,0,'L',$relleno2);
        $pdf->Cell(25,10,$sumaSubtotal,$borde3,0,'L',$relleno2);
        $pdf->Cell(15,10,$sumaIva,$borde3,0,'L',$relleno2);
        $pdf->Cell(25,10,$sumatotal,$borde3,0,'R',$relleno2);
        
        
            // $pdf->Cell(20,10,'',$sumaCantidades,0,'L',$relleno2);
            // $pdf->Cell(20,10, $sumaDescuentos,$borde3,0,'L',$relleno2);
            // $pdf->Cell(25,10,$sumaSubtotal ,$borde3,0,'L',$relleno2);
            // $pdf->Cell(15,10,$sumaIva,$borde3,0,'L',$relleno2);
            // $pdf->Cell(25,10,$sumatotal,$borde3,0,'R',$relleno2);
            
            $pdf->Ln(20);
    
        }
    }


    # Nombre del archivo PDF #
    $pdf->Output("I","RepoteDeCompras.pdf",true);