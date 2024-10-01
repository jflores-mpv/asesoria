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
    
    
    $fecha_desde_principal = explode(" ", $_GET['txtFechaDesde']);
    $fecha_hasta_principal = explode(" ", $_GET['txtFechaHasta']);
    $txtProveedor =  $_GET['txtProveedor'];
    
    $fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
    $fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");


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
    $pdf->MultiCell(0,5,'Reporte de retenciones',0,'C',false);
    
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
    
    $sql = "SELECT d.*

    FROM dcretencion d 
    JOIN mcretencion m ON d.Retencion_id = m.Id 
    JOIN compras c ON m.Factura_id = c.id_compra 
        
    where c.id_empresa=$sesion_id_empresa and  c.fecha_compra >= '".$fecha_desde."' AND c.fecha_compra <= '".$fecha_hasta."'
        
    ORDER BY d.CodImp ";

    $result = mysql_query($sql) or die(mysql_error());
    $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
    $numero = 0;
    $currentCodImp = null;
 
    while($row = mysql_fetch_array($result)) {
	    
	    $BasImp= $row['BaseImp'];
	    $CodImp= $row['CodImp'];
	    $CodPorcentaje= $row['Porcentaje'];
	    
	    if ($currentCodImp != $CodImp) {
	            
	        $pdf->Cell(75,10,"Codigo de retencion :".$CodImp."==".$CodPorcentaje,$borde,0,'L',$relleno);
            $pdf->Ln();
            
	        $sql2 = "SELECT  d.* ,p.nombre,m.Numero,m.Serie,c.*

                    FROM dcretencion d 
                    JOIN mcretencion m ON d.Retencion_id = m.Id 
                    JOIN compras c ON m.Factura_id = c.id_compra 
                    JOIN proveedores p ON c.id_proveedor = p.id_proveedor 
                    
                    where d.CodImp='".$CodImp."' and c.id_empresa=$sesion_id_empresa
                    
                    and  c.fecha_compra >= '".$fecha_desde."' AND c.fecha_compra <= '".$fecha_hasta."'
                    
                    and  m.anulado='0'
                    
                    ORDER BY d.CodImp, c.numero_factura_compra ";
            
                    $result2 = mysql_query($sql2) or die(mysql_error());     
                        
                    $pdf->Cell(40,10,'# Compra',$borde2,0,'L',$relleno2);
                    $pdf->Cell(150,10,'Proveedor',$borde2,0,'L',$relleno2);
                    $pdf->Cell(40,10,'# Retencion',$borde2,0,'L',$relleno2);
                    $pdf->Cell(25,10,'B. Imponible',$borde2,0,'R',$relleno2);
                    $pdf->Cell(25,10,'V. Retenido',$borde2,0,'R',$relleno2);
                    $pdf->Ln();
                    
                    $totalBase=0;
                    $totalRetenido='0';
            
            $sumaBaseImp=0;
            $sumaBasePor=0;
            	    while($row2 = mysql_fetch_array($result2)) {
            	    
            	    $numeroCompra= $row2['numSerie']."-".$row2['txtEmision']."-".ceros($row2['numero_factura_compra']);
            	    
            	    $total= $row2['total'];
            	    $baseImp=$row2['BaseImp'];
            	    
            	    
            	    $porcentajeRetenido=$row2['BaseImp']*($row2['Porcentaje']/100);

                    $pdf->Cell(40,10,$numeroCompra,$borde3,0,'L',$relleno3);
                    $pdf->Cell(150,10,utf8_decode($row2['nombre']),$borde3,0,'L',$relleno3);
                    $pdf->Cell(40,10,$row2['Serie']."-".ceros($row2['Numero']),$borde3,0,'L',$relleno3);
                    $pdf->Cell(25,10,$row2['BaseImp'],$borde3,0,'R',$relleno3);
                    $pdf->Cell(25,10,$porcentajeRetenido,$borde3,0,'R',$relleno3);
                    $pdf->Ln();
                    
                    $sumaBaseImp= $sumaBaseImp + $row2['BaseImp'];
                    $sumaBasePor= $sumaBasePor + $porcentajeRetenido;
            	}       
            	    $pdf->Cell(230,10,'','',0,'R','');
                    $pdf->Cell(25,10,$sumaBaseImp,$borde,0,'R',$relleno);
                    $pdf->Cell(25,10,$sumaBasePor,$borde,0,'R',$relleno);
            	    $pdf->Ln();
                    $pdf->Ln(4);
            	
	    }
	    
	    
	    $currentCodImp = $CodImp;
    }
    

    # Nombre del archivo PDF #
    $pdf->Output("I","Retenciones.pdf",true);