<?php



require('fpdf17/fpdf.php');

require('rptCheque.php');

require('../clases/funciones.php');

require_once('../conexion.php');
session_start();

$sesion_empresa_imagen = $_SESSION["sesion_empresa_imagen"];
$usuarios_login = $_SESSION["usuarios_login"];
$dominio = $_SERVER['SERVER_NAME'];

class PDF extends FPDF
{
// Cabecera de página
function Header($cab=0)
{
    $sesion_empresa_imagen = $_SESSION["sesion_empresa_imagen"];

	    	$this->SetFont('Arial','B',15);
if($cab==1){
   

      	       if(trim($sesion_empresa_imagen)!='' && file_exists('../sql/archivos/'.$sesion_empresa_imagen) ){
      $this->Image('../sql/archivos/'.$sesion_empresa_imagen, 10, 10,20,20); 

$this->SetX(70);
	    	    	$this->Cell(80,10,'Comprobante de Diario',1,0,'C');
	    	    
	    	    	
    }else{
	    	   // Movernos a la derecha
	$this->Cell(60);
        //$this->Ln(2);
	// Título
	$this->Cell(80,10,'Comprobante de Diario',1,0,'C');
	// Salto de línea
	$this->Ln(20); 
	    	}
}
	    	    
	  
	

}

// Pie de página
function Footer()
{
    $numeroPaginas= $this->PageNo();
	if($numeroPaginas==1){
	    	$this->SetY(-15);
	// Arial italic 8
	$this->SetFont('Arial','I',8);
	// Número de página
	$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	// Posición: a 1,5 cm del final

}
}


    // Creación del objeto de la clase heredada
    $pdf = new PDF();

    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_id_periodo_contable = $_SESSION['sesion_id_periodo_contable'];
    $sesion_empresa_ruc = $_SESSION['sesion_empresa_ruc'];
    $sesion_ciudades_ciudad = $_SESSION['sesion_ciudades_ciudad'];
    





     $sqlEmpresa= " SELECT `razonSocial`, `nombreContador` FROM `empresa`  WHERE empresa.`id_empresa`=$sesion_id_empresa ";
      
    $resultEmpresa= mysql_query($sqlEmpresa);
    while($rEmpresa = mysql_fetch_array($resultEmpresa)){
        $razonSocialEmpresa = $rEmpresa['razonSocial'];
        $nombreContador=$rEmpresa['nombreContador'];
        
    }
    $sql = "SELECT
     libro_diario.`id_libro_diario` AS libro_diario_id_libro_diario,
     libro_diario.`id_periodo_contable` AS libro_diario_id_periodo_contable,
     libro_diario.`numero_asiento` AS libro_diario_numero_asiento,
     libro_diario.`fecha` AS libro_diario_fecha,
     libro_diario.`total_debe` AS libro_diario_total_debe,
     libro_diario.`total_haber` AS libro_diario_total_haber,
     libro_diario.`descripcion` AS libro_diario_descripcion,
     libro_diario.`numero_comprobante` AS libro_diario_numero_comprobante,
     libro_diario.`tipo_comprobante` AS libro_diario_tipo_comprobante,
     libro_diario.`numero_cpra_vta` AS libro_diario_numero_cpra_vta,
       libro_diario.id_cliente  AS libro_diario_numero_id_cliente, 
      libro_diario.tipo_mov  AS libro_diario_tipo_mov,
     periodo_contable.`id_periodo_contable` AS periodo_contable_id_periodo_contable,
     periodo_contable.`fecha_desde` AS periodo_contable_fecha_desde,
     periodo_contable.`fecha_hasta` AS periodo_contable_fecha_hasta,
     periodo_contable.`estado` AS periodo_contable_estado,
     periodo_contable.`ingresos` AS periodo_contable_ingresos,
     periodo_contable.`gastos` AS periodo_contable_gastos,
     periodo_contable.`id_empresa` AS periodo_contable_id_empresa

FROM
     `periodo_contable` periodo_contable INNER JOIN `libro_diario` libro_diario ON periodo_contable.`id_periodo_contable` = libro_diario.`id_periodo_contable` ";
if( isset($_GET['txtComprobanteNumero']) ){
    if ( ($_GET['txtComprobanteNumero']) != ""){
        $sql .= " where periodo_contable.`id_empresa` = '".$sesion_id_empresa."' AND libro_diario.`tipo_comprobante`= 'Diario' AND libro_diario.`numero_comprobante` = '".($_GET['txtComprobanteNumero'])."'  ";

    //    if (($_GET['fecha_desde']) != "" && ($_GET['fecha_hasta']) != ""){
    //        $sql .= "  AND libro_diario.`fecha` BETWEEN '".($_GET['fecha_desde'])." 00:00:00'  AND '".($_GET['fecha_hasta'])." 23:59:59' order by libro_diario.`fecha` asc;";
    //    }else
    //        {
    //        $sql .= "  order by libro_diario.`fecha` asc; ";
    //    }
    }
	else if (($_GET['fecha_desde']) != "" && ($_GET['fecha_hasta']) != ""){
        $sql .= " where periodo_contable.`id_empresa` = '".$sesion_id_empresa."' AND libro_diario.`tipo_comprobante`= 'Diario' AND libro_diario.`fecha` BETWEEN '".($_GET['fecha_desde'])." 00:00:00'  AND '".($_GET['fecha_hasta'])." 23:59:59' order by libro_diario.`fecha` asc; ";
    }
}else if( isset($_GET['txtAsientoNumero'])){
    if ( ($_GET['txtAsientoNumero']) != ""){
        $sql .= " where periodo_contable.`id_empresa` = '".$sesion_id_empresa."' AND libro_diario.`tipo_comprobante`= 'Diario' AND libro_diario.`numero_asiento` = '".($_GET['txtAsientoNumero'])."'  ";

    //    if (($_GET['fecha_desde']) != "" && ($_GET['fecha_hasta']) != ""){
    //        $sql .= "  AND libro_diario.`fecha` BETWEEN '".($_GET['fecha_desde'])." 00:00:00'  AND '".($_GET['fecha_hasta'])." 23:59:59' order by libro_diario.`fecha` asc;";
    //    }else
    //        {
    //        $sql .= "  order by libro_diario.`fecha` asc; ";
    //    }
    }
	else if (($_GET['fecha_desde']) != "" && ($_GET['fecha_hasta']) != ""){
        $sql .= " where periodo_contable.`id_empresa` = '".$sesion_id_empresa."' AND libro_diario.`tipo_comprobante`= 'Diario' AND libro_diario.`fecha` BETWEEN '".($_GET['fecha_desde'])." 00:00:00'  AND '".($_GET['fecha_hasta'])." 23:59:59' order by libro_diario.`fecha` asc; ";
    }
}
    if($sesion_id_empresa==116)
{
    //  echo $sql;
}// echo $sql;

// if ( ($_GET['txtComprobanteNumero']) != ""){
//        $sql .= " where periodo_contable.`id_empresa` = '".$sesion_id_empresa."' AND libro_diario.`tipo_comprobante`= 'Diario' AND libro_diario.`numero_asiento` = '".($_GET['txtComprobanteNumero'])."'  ";
//
//        if (($_GET['fecha_desde']) != "" && ($_GET['fecha_hasta']) != ""){
 //           $sql .= "  AND libro_diario.`fecha` BETWEEN '".($_GET['fecha_desde'])." 00:00:00'  AND '".($_GET['fecha_hasta'])." 23:59:59' order by libro_diario.`fecha` asc;";
 //       }else
 //           {
 //           $sql .= "  order by libro_diario.`fecha` asc; ";
  //      }
  //  }else if (($_GET['fecha_desde']) != "" && ($_GET['fecha_hasta']) != ""){
  //      $sql .= " where periodo_contable.`id_empresa` = '".$sesion_id_empresa."' AND libro_diario.`tipo_comprobante`= 'Diario' AND libro_diario.`fecha` BETWEEN '".($_GET['fecha_desde'])." 00:00:00'  AND '".($_GET['fecha_hasta'])." 23:59:59' order by libro_diario.`fecha` asc; ";
  //  }


//   echo $sql;

    $result = mysql_query($sql) or die(mysql_error());
$numero=0;
    while($row = mysql_fetch_array($result)) {
        $numero++;
        $id_libro_diario = $row["libro_diario_id_libro_diario"];
         $libro_diario_fecha = $row["libro_diario_fecha"];
         $libro_diario_numero_asiento = $row["libro_diario_numero_asiento"];
         $libro_diario_descripcion = $row["libro_diario_descripcion"];
         $libro_diario_numero_comprobante = $row["libro_diario_numero_comprobante"];
        $numeroVta=$row["libro_diario_numero_cpra_vta"];

         $sqlBancos="SELECT `id_detalle_banco`, `tipo_documento`, `numero_documento`, `detalle`, `valor`, `fecha_cobro`, `fecha_vencimiento`, `id_bancos`, `estado`, `id_libro_diario`, `fecha` FROM `detalle_bancos` WHERE `id_libro_diario`=$id_libro_diario ";
        $resultBancos = mysql_query($sqlBancos);
   
        $dBanco='';
        while($row5= mysql_fetch_array($resultBancos)){
            $tipoDocumento = $row5['tipo_documento'] ;
            $descripcionDocuento=  $row5['detalle'] ;
            $dBanco= $tipoDocumento.' girado a '.$descripcionDocuento;
        }
        
         //**********************************************************************
  
        $pdf->AliasNbPages();  
        $pdf->AddPage(); 
        
    //         if(trim($sesion_empresa_imagen)!=''){
    //   $pdf->Image('../sql/archivos/'.$sesion_empresa_imagen, 10, 10,-900); 
    //   $pdf->Ln();
    // }
    $debajoImagen = $pdf->GetY();
   
        $pdf->Line(10,$debajoImagen,200,$debajoImagen); // LINEA SUPERIOR PRINCIPAL
        $pdf->SetFont('Times','',10);
        $numero_caracteres = strlen($sesion_empresa_nombre); // cuenta cuantos caracteres hay        
        $pdf->SetXY($numero_caracteres, $debajoImagen+15);
        $pdf->Cell(0, 0, $sesion_empresa_nombre, 0,1,'C');
        $pdf->SetFont('Arial', 'B', 14);

        $pdf->SetXY(150, $debajoImagen+25);
        $pdf->Cell(5, 10, 'Diario Nro.: '.$libro_diario_numero_comprobante, 0,1);
        $pdf->SetXY(10, $debajoImagen+20);
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(10, 7, 'Lugar y Fecha: '.$sesion_ciudades_ciudad.", ".covertirFecha($libro_diario_fecha), 0,1);
        //   $pdf->Cell(10, 7, 'ID LIBRO: '.$id_libro_diario, 0,1);
        
        
        // $pdf->Cell(10, 7, 'Ruc: '.$sesion_empresa_ruc, 0,1);
        //$pdf->Cell(10, 7, 'Origen:'.'', 0);
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->SetXY(10, $debajoImagen+30);
        $pdf->Cell(5, 20, 'POR CONCEPTO DE: ', 0,1);
        $pdf->Line(10,$debajoImagen+44,200,$debajoImagen+44);// LINEA DEBAJO POR CONCEPTO DE


        //$libro_diario_descripcion ="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";
       $primeros_caracteres = substr(utf8_decode($libro_diario_descripcion), 0, 90);  // devuelve los 75 primeros caracteres
        // $primeros_caracteres = utf8_decode($libro_diario_descripcion);  // devuelve los 75 primeros caracteres
        $numero_caracteres = strlen($libro_diario_descripcion); // cuenta cuantos caracteres hay
        $txtdecodificado= utf8_decode($libro_diario_descripcion);
       $TXT_Parts = str_split($txtdecodificado, 90); //Lo divido en partes iguales de 75
       if($dBanco!=''){
        //   echo 'si';
              $TXT_Parts[]=$dBanco;
       }
    
       $contadorFilasC= count($TXT_Parts);
        $posaY=$pdf->GetY()-7;
            $pdf->SetXY(10, $posaY+1);
               $pdf->MultiCell(190,5,'                                        '.utf8_decode($libro_diario_descripcion),'1','L');
            // $pdf->Cell(5, 0, '                 '.$TXT_Parts[$k], 0,1);
            //$pdf->Line(10,$posaY+7,200,$posaY+7); // linea por concepto 2
         
          $posaY= $pdf->GetY();
  $posY1= $pdf->GetY();
       if(trim($dBanco)!=''){
  $pdf->Line(10,$posaY+7,200,$posaY+7);

    $pdf->SetXY(30, $posaY+4);
    $pdf->Cell(5, 0,$dBanco, 0,1);
    $posaY= $pdf->GetY()+4; 
    $posY1 = $pdf->GetY()+4; 
       }


      
        // $TXT_Parts = utf8_decode($libro_diario_descripcion); //Lo divido en partes iguales de 75
        //  $txtUneString = $TXT_Parts[1].$TXT_Parts[2].$TXT_Parts[3];
      //  $txtUneString = $TXT_Parts;
       //  $txt_Partes2 = str_split($txtUneString, 98); //Lo divido en partes iguales de 98
        
        // $pdf->SetXY(30, 57);
        // $pdf->Cell(5, 0, '                 '.$primeros_caracteres, 0,1);
        // $pdf->Line(10,60,200,60); // linea por concepto 2
        // $pdf->SetXY(10, 64);
        // $pdf->Cell(5, 0, ''.$TXT_Parts[0], 0,1);
        // $pdf->Line(10,67,200,67); // linea por concepto 3
        // $pdf->SetXY(10, 71);
        // // $pdf->Cell(5, 0, ''.$TXT_Parts[1], 0,1);
        // $pdf->Line(10,73,200,73); // linea por concepto 4
    
       
        
        $pdf->SetXY(10, $posY1-23);
        $pdf->Cell(40, 54, utf8_decode('Código'), 0);
        $pdf->Cell(100, 54,utf8_decode( 'Descripción'), 0);
        $pdf->Line(10,$posY1+7,200,$posY1+7); // linea abajo de descripcion
        $pdf->Cell(30, 54, 'Debe', 0);
        $pdf->Cell(0, 54, 'Haber', 0);
        // $pdf->SetXY(10, $posY1);
        $yy=63; // posicion de las cuentas



         //*************************************************************************

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
         plan_cuentas.`total` AS plan_cuentas_total,
         plan_cuentas.`cuenta_banco` AS plan_cuentas_cuenta_banco,
         plan_cuentas.`tipo_cheque` AS plan_cuentas_tipo_cheque
    FROM
         `plan_cuentas` plan_cuentas INNER JOIN `detalle_libro_diario` detalle_libro_diario ON plan_cuentas.`id_plan_cuenta` = detalle_libro_diario.`id_plan_cuenta`
         WHERE detalle_libro_diario.`id_libro_diario`='".$id_libro_diario."' AND detalle_libro_diario.`id_periodo_contable` = '".$sesion_id_periodo_contable."';";
        $result2=mysql_query($sql2);
        // echo "--------------------".$sql2;
        $detalle_libro_diario_id_detalle_libro_diario = array();
        $plan_cuentas_codigo = array();
        $plan_cuentas_nombre = array();
        $detalle_libro_diario_debe = array();
        $detalle_libro_diario_haber = array();
        $b=0;
		$sumadebe = 0;
		$sumahaber = 0;
  

		$num_filas_detalle_libro_diario = mysql_num_rows($result2); // obtenemos el número de filas

$planCuentasBanco='';
        while($row2=mysql_fetch_array($result2))//permite ir de fila en fila de la tabla
        {
            $detalle_libro_diario_id_detalle_libro_diario[$b] = $row2['detalle_libro_diario_id_detalle_libro_diario'];
            $plan_cuentas_codigo[$b] = $row2['plan_cuentas_codigo'];
            $plan_cuentas_nombre[$b] = $row2['plan_cuentas_nombre']." ".$row2['plan_cuentas_cuenta_banco'];
            // $plan_cuentas_nombre[$b]="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's";
            $detalle_libro_diario_debe[$b] = $row2['detalle_libro_diario_debe'];
            $detalle_libro_diario_haber[$b] = $row2['detalle_libro_diario_haber'];
            $sumadebe = $sumadebe + $row2['detalle_libro_diario_debe'];
            $sumahaber = $sumahaber + $row2['detalle_libro_diario_haber'];
            $b++;
            if( $row2['plan_cuentas_cuenta_banco']!=''){
                 $planCuentasBanco= $row2['plan_cuentas_cuenta_banco'];
                   $tipo_cheque= $row2['plan_cuentas_tipo_cheque'];
            }
       
        }
// echo "PLAN CUENTAS".$planCuentasBanco;
        $pdf->Ln(33);



        for($j=0; $j<$num_filas_detalle_libro_diario; $j++){

            //inicio calculos
            $cellWidth=105;
            $cellHeight=2;
            if($pdf->GetStringWidth($plan_cuentas_nombre[$j])<$cellWidth){
                $line=2;
            }else{
                $textLength=strlen($plan_cuentas_nombre[$j]);
                $errMargin=10;
                $startChar=0;
                $maxChar=0;
                $textArray=array();
                $tmpString='';
                while($startChar< $textLength){
                    while($pdf->GetStringWidth($tmpString)<($cellWidth-$errMargin)&&($startChar+$maxChar)<$textLength){
                        $maxChar++;
                        $tmpString=substr($plan_cuentas_nombre[$j],$startChar,$maxChar);
                    }
                    $startChar= $startChar+$maxChar;
                    array_push($textArray,$tmpString);
                    $maxChar=0;
                    $tmpString='';
                }
               
                $line=count($textArray);
                
            }
        
            $ancho = $line*2;
            $variable= 10;
            //fin calculos
               
                if($detalle_libro_diario_debe[$j] == 0){
                    $pdf->Cell(30, $ancho, $plan_cuentas_codigo[$j], 0,0);
                    // $pdf->Cell(100, $yy, utf8_decode($plan_cuentas_nombre[$j]), 0);
                    $y= $pdf->GetY();
                      $pdf->MultiCell(105,$ancho,'     '.utf8_decode($plan_cuentas_nombre[$j]),0,'L');

                     $y1= $pdf->GetY();
                     $pdf->SetY($y);
                     $pdf->SetX(146);
                }else{
                    $pdf->Cell(30, $ancho, $plan_cuentas_codigo[$j], 0,0);
                    // $pdf->Cell(110, $yy, utf8_decode($plan_cuentas_nombre[$j]), 0);
                    $y= $pdf->GetY();
                     $pdf->MultiCell(105,$ancho,utf8_decode($plan_cuentas_nombre[$j]),0,'L');
                     $y1= $pdf->GetY();
                     $pdf->SetY($y);
                     $pdf->SetX(146);
                }

                $pdf->Cell(25+4, $ancho, number_format($detalle_libro_diario_debe[$j], 2, '.', ' '), 0,0,'R');
                $pdf->Cell(0, $ancho, number_format($detalle_libro_diario_haber[$j], 2, '.', ' '), 0,0,'R');
                $yy=$yy+7;
                $pdf->SetY($y+8);

        }
           
        $posY= $pdf->GetY();
        $posX= $pdf->GetX();
        $pdf->SetXY(145, $posY+5);
        $pdf->Cell(25+5, 0,number_format($sumadebe, 2, '.', ' '), 0,0,'R');
        $pdf->Cell(0, 0, number_format($sumahaber, 2, '.', ' '), 0,0,'R');

        
        $pdf->SetXY(10, 10);
       
        $pdf->Line(10,$posY+1,200,$posY+1); // linea arriba de suma
        $pdf->SetXY(125, $posY+5); // palabra suma
      //  $pdf->SetXY($posX+7, $posY+7); // palabra suma
        $pdf->Cell(0, 0, 'Suma:', 0,0);
        $pdf->Line(145,$posY+8,200,$posY+8); 
        $pdf->Line(10,$posY+20,200,$posY+20); // linea debajo de la suma
        
        $pdf->SetXY(10, $posY+32);
        $lineaNombres = $posY+32;
        $pdf->Multicell(47, 5, 'Elaborado por: '.utf8_decode($usuarios_login), 0,'L');
       $posYmasbajo = $pdf->GetY();
       
         $pdf->SetXY(57,$lineaNombres);
        $pdf->Multicell(47, 5, 'Revisado por: '.utf8_decode($nombreContador), 0,'L');
        $yRevisado = $pdf->GetY();
      $posYmasbajo = ($posYmasbajo>$yRevisado )?$posYmasbajo:$yRevisado;
        
          $pdf->SetXY(104,$lineaNombres);
        $pdf->Multicell(47, 5, 'Aprobado por: '.utf8_decode($razonSocialEmpresa), 0,'L');
        $yAprobado = $pdf->GetY();
      $posYmasbajo = ($posYmasbajo>$yAprobado )?$posYmasbajo:$yAprobado;
      
        $libro_diario_numero_cpra_vta = trim($row["libro_diario_numero_cpra_vta"]);
        $libro_diario_numero_id_cliente = trim($row["libro_diario_numero_id_cliente"]);
        $libro_diario_tipo_mov = trim($row["libro_diario_tipo_mov"]);
        $nombre_buscado='';
        $numFilaProveedor=0;
        // if($sesion_id_empresa==1827){
        if($libro_diario_tipo_mov=='C'){
            $sqlCompra="SELECT `id_compra`, proveedores.nombre_comercial FROM `compras` INNER JOIN proveedores ON proveedores.id_proveedor = compras.id_proveedor  WHERE  compras.id_empresa = '".$sesion_id_empresa."' and compras.numero_factura_compra='".$libro_diario_numero_cpra_vta."'";
            $resultCompra = mysql_query($sqlCompra);
            while($rowCompra = mysql_fetch_array($resultCompra) ){
                $nombre_buscado= $rowCompra['nombre_comercial'];
                $numFilaProveedor=1;
                }
        }
        if($libro_diario_tipo_mov=='CP'){
            $sqlCP="SELECT `id_cuenta_por_pagar`, `tipo_documento`, `numero_compra`, `referencia`, `valor`, `saldo`, `numero_asiento`, `fecha_vencimiento`, `fecha_pago`, `id_proveedor`, `id_cliente`, `id_lead`, `id_plan_cuenta`, `id_empresa`, `id_compra`, `estado`, `id_forma_pago`, `banco_referencia`, `documento_numero`, `id_empleado`, `motivoAnticipo`, `tipo_anticipo`, `registro` FROM `cuentas_por_pagar` WHERE  id_empresa = '".$sesion_id_empresa."' and id_cuenta_por_pagar='".$libro_diario_numero_cpra_vta."'";
            $resultCP = mysql_query($sqlCP);
            while($rowCP = mysql_fetch_array($resultCP) ){
                
                if( trim($rowCP['id_proveedor'])!='' ){
                    $sqlbc="SELECT `id_proveedor`, `nombre_comercial` as nombreBuscado, `nombre`, `nombreProveedor`, `apellidoProveedor` FROM `proveedores`WHERE id_proveedor='".$rowCP['id_proveedor']."'";
                }else if( trim($rowCP['id_cliente'])!='' ){
                    $sqlbc="SELECT `id_cliente`, CONCAT(`nombre`,' ', `apellido`) as nombreBuscado FROM `clientes` WHERE id_cliente='".$rowCP['id_cliente']."'";
                    
                }else if( trim($rowCP['id_lead'])!='' ){
                    $sqlbc="SELECT leads.id, CONCAT(`name`,' ', `apellido`) as nombreBuscado FROM leads   WHERE id='".$rowCP['id_lead']."'";
                }else{
                    $sqlbc="SELECT empleados.id_empleado, CONCAT(`nombre`,' ', `apellido`) as nombreBuscado FROM empleados WHERE id_empleado='".$rowCP['id_empleado']."'";
                }
                $resultbc=mysql_query($sqlbc);
                while($rowbc = mysql_fetch_array($resultbc) ){
                    $nombre_buscado= $rowbc['nombreBuscado'];
                    $numFilaProveedor=1;
                }
                
                }
        }       
              
        // }else{
        //       $sqlProveedor="SELECT `id_proveedor`, `nombre_comercial`, `nombre`, `nombreProveedor`, `apellidoProveedor` FROM `proveedores`  WHERE '".$libro_diario_descripcion."' LIKE CONCAT('%', `nombre_comercial`, '%')  AND '".$libro_diario_descripcion."' LIKE CONCAT('%', `nombre`, '%') AND proveedores.id_empresa=$sesion_id_empresa;";
        //     $resultProveedor =mysql_query($sqlProveedor);
        //     $numFilaProveedor = mysql_num_rows($resultProveedor);
        //     while($rowPro = mysql_fetch_array($resultProveedor) ){
        //         $nombre_buscado = $rowPro['nombre_comercial'];
        //     }
            
        // }
     
            //   si no hay numero de venta ni tipo de movimiento

            // $sqlProveedor="SELECT `id_proveedor`, `nombre_comercial`, `nombre`, `nombreProveedor`, `apellidoProveedor` FROM `proveedores`  WHERE '".$libro_diario_descripcion."' LIKE CONCAT('%', `nombre_comercial`, '%')  AND '".$libro_diario_descripcion."' LIKE CONCAT('%', `nombre`, '%') AND proveedores.id_empresa=$sesion_id_empresa;";
            // $resultProveedor =mysql_query($sqlProveedor);
            // $numFilaProveedor = mysql_num_rows($resultProveedor);
            // while($rowPro = mysql_fetch_array($resultProveedor) ){
            //     $nombre_buscado = $rowPro['nombre_comercial'];
            // }
            
            $numFilaCliente=0;
            $numFilaEmpleado=0;
            if($numFilaProveedor==0){
                 $sqlCliente ="SELECT clientes.id_cliente, clientes.nombre,clientes.apellido FROM clientes WHERE ('".$libro_diario_descripcion."' LIKE CONCAT('%',apellido , ' ', nombre,'%') OR '".$libro_diario_descripcion."' LIKE CONCAT('%', nombre, ' ', apellido,'%') ) AND apellido!='' and nombre!=''  AND clientes.id_empresa=$sesion_id_empresa;";
                $resultCliente = mysql_query($sqlCliente);
                 $numFilaCliente = mysql_num_rows($resultCliente);
                while($rowCli = mysql_fetch_array($resultCliente) ){
                     $nombre_buscado = $rowCli['nombre'].' '.$rowCli['apellido'];
                }
            }
             if($numFilaProveedor==0 && $numFilaCliente==0){
                 $sqlEmpleados="SELECT empleados.id_empleado, empleados.nombre, empleados.apellido FROM empleados WHERE '".$libro_diario_descripcion."' LIKE CONCAT('%',apellido , ' ', nombre,'%') AND '".$libro_diario_descripcion."' LIKE CONCAT('%', apellido, ' ', nombre,'%') AND apellido!='' and nombre!='' AND empleados.id_empresa=$sesion_id_empresa";
                 $resultEmpleados = mysql_query($sqlEmpleados);
                 $numFilaEmpleado = mysql_num_rows($resultEmpleados);
                 while($rowEmp = mysql_fetch_array($resultEmpleados) ){
                     $nombre_buscado = $rowEmp['nombre'].' '.$rowEmp['apellido'];
                }
             }
             if($numFilaProveedor==0 && $numFilaCliente==0 && $numFilaEmpleado==0){
                 $sqlLead="SELECT leads.id, leads.name, leads.apellido FROM leads WHERE '".$libro_diario_descripcion."' LIKE CONCAT('%',apellido , ' ', name,'%') AND '".$libro_diario_descripcion."' LIKE CONCAT('%', apellido, ' ', name,'%') AND apellido!='' and name!='' AND leads.id_empresa=$sesion_id_empresa;";
                 $resultLead = mysql_query($sqlLead);
                 while($rowLead = mysql_fetch_array($resultLead)  ){
                      $nombre_buscado = $rowLead['name'].' '.$rowLead['apellido'];
                 }
             }
           
        
           
        
         if(trim($nombre_buscado)!=''){
               $ultimoCuadroContenido= 'Contabilizado por: '.utf8_decode($nombre_buscado);
           }else{
               $ultimoCuadroContenido= 'Contabilizado por: '.utf8_decode($usuarios_login);
           }
          
          
         $pdf->SetXY(151,$lineaNombres);
        $pdf->Multicell(49, 5, $ultimoCuadroContenido, 0,'L');
          $yContador = $pdf->GetY();
      $posYmasbajo = ($posYmasbajo>$yContador )?$posYmasbajo:$yContador;
        
         $pdf->Line(57,$posY+20, 57, $posYmasbajo); // linea vertical entre el elaborado y el revisado
         $pdf->Line(104,$posY+20, 104,  $posYmasbajo);// linea vertical entre revisado y aprobado
         $pdf->Line(151, $posY+20, 151,  $posYmasbajo); //linea entre aprobado y contabilisado
        
        $pdf->Line(10,$posYmasbajo,200,$posYmasbajo); //line horizontal inferior principal
         $pdf->Line(10,$posYmasbajo,10,$debajoImagen); // linea vertical izquierda principal
        $pdf->Line(200,$posYmasbajo,200,$debajoImagen); // linea vertical derecha principal
        
        $pdf->SetXY(10,30);
        
        $pdf->Line(40, $posY1, 40,  $posY+1); // linea vertical entre codigo y descripcion
        $pdf->Line(145, $posY1, 145, $posY+8);// linea vertical entre el descripcion y el debe
        $pdf->Line(175, $posY1, 175, $posY+8); // linea vertical entre el debe y el haber
       
        if($planCuentasBanco!=''){
            
           if($dominio=='www.contaweb.com' or $dominio=='contaweb.ec' 
            or $dominio=='www.contaweb.com.ec'  or $dominio=='www.contaweb.ec'){
                
              if($tipo_cheque == 'produbanco'){
                      $pdf = imprimirChequeProdubanco($id_libro_diario, $pdf,$nombre_buscado);
                }else{
                     $pdf = imprimirCheque2($id_libro_diario, $pdf,$nombre_buscado);
                }
                
            }else{
                 $pdf = imprimirCheque2($id_libro_diario, $pdf,$nombre_buscado);
            }
            
        
        }
        // if($sesion_id_empresa==41 && $planCuentasBanco!=''){
        //      $pdf = imprimirCheque2($id_libro_diario, $pdf);
        // }
             if($numero>45){
                $pdf->Output();   
            }
       
   }

$pdf->Output();

?>