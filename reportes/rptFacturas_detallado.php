<?php

    session_start();
    
	$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
		$sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
    //Include database connection details
    
       require_once('../conexion.php');
	
	require_once('../tcpdf_include.php');
    function ceros($valor){
        for($i=1;$i<=9-strlen($valor);$i++)
            $s.="0";
        return $s.$valor;
    }

    function generarClave($id,$tipoComprobante,$ruc,$ambiente,$serie,$numeroDocumento,$fecha, $tipoEmision){      
        $secuencia = '765432';      
       // $ceros = 9;      
       // $temp = '';
        //$tam = $ceros - strlen($numeroDocumento);
  
        //for ($i = 0; $i < $tam; $i++) {                 
         // $temp = $temp .'0';        
        //}
       // $numeroDocumento = $temp .''. $numeroDocumento;  
        
        
        //$fechaT = explode('/', $fecha);    
        //$fecha = $fechaT[0].''.$fechaT[1].''.$fechaT[2];            
  
        $clave = $fecha.''.$tipoComprobante.''.$ruc.''.$ambiente.''.$serie.''.$numeroDocumento.''.$fecha.''.$tipoEmision;      
        $tamSecuencia = strlen($secuencia);      
        $ban = 0;
        $inc = 0;
        $sum = 0;
        for ($i = 0; $i < strlen($clave); $i++) { 
          $sum = $sum  + ($clave[$i] * $secuencia[$ban + $inc]);        
          //echo $sum.'<br/>';
          $inc++;
          if($inc >= $tamSecuencia){
            $inc = 0;
          }
        }              
        $resp = $sum % 11;
        $resp = 11 - $resp;      
        if($resp == 10){
          $resp = 1;
        }else{
          if($resp == 11){
            $resp = 0;
          }  
        }
        
        $clave = $clave.$resp; 
       
        return $clave;      
      } 


	$factura=$_GET['txtComprobanteNumero'];
	

	  $sql1= "SELECT 
	
	codigo_lug as codigo_lug,
	ambiente as ambiente,
	tipoEmision as tipoEmision, 
	empresa.nombre as nombreComercial, 
    empresa.razonSocial as razonSocial, 
    empresa.ruc as ruc, 
    ClaveAcceso as claveAcceso,
    tipo_documento as codDoc,
    establecimientos.codigo as estab,
emision.codigo as ptoEmi,
numero_factura_venta as secuencial,
empresa.direccion as dirMatriz,
empresa.clave as clave,
empresa.FElectronica as firma,
ventas.fecha_venta as fechaEmision,
clientes.apellido,
clientes.nombre,
clientes.direccion,
clientes.cedula,
clientes.estado,
clientes.caracter_identificacion,
empresa.Ocontabilidad,
empresa.FElectronica,
ventas.sub0,
ventas.sub12,
ventas.sub_total,
ventas.descuento,
ventas.vendedor_id_tabla,
impuestos.iva,
clientes.telefono,
clientes.email,
empresa.autorizacion_sri,
ventas.descripcion,
establecimientos.direccion as dirSucursal,
empresa.leyenda as rimpe,
empresa.leyenda2 as retencion,
ventas.propina as propina,
ventas.total as totalventas,
ventas.Autorizacion as numAutorizacion,
ventas.FechaAutorizacion as fechaAutorizacion,
emision.SOCIO as socio,
ventas.Vendedor_id as vendedor,
 ventas.`entregado`, ventas.`cambio`
	, ventas.`tipo_inco_term`, ventas.`lugar_inco_term`, ventas.`pais_origen`, ventas.`puerto_embarque`, ventas.`puerto_destino`, ventas.`pais_destino`, ventas.`pais_adquisicion`, ventas.`numero_dae`, ventas.`numero_transporte`, ventas.`flete_internacional`, ventas.`seguro_internaiconal`, ventas.`gastos_aduaneros`, ventas.`gastos_transporte`

from ventas,emision,empresa,establecimientos,clientes,impuestos 
WHERE impuestos.id_iva=ventas.id_iva AND   clientes.id_cliente=ventas.id_cliente AND  id_venta='".$factura."' 
and emision.id=codigo_lug and empresa.id_empresa = ventas.id_empresa and establecimientos.id=ventas.codigo_pun 
and emision.id=ventas.codigo_lug";  
$result1 = mysql_query($sql1);


while($row1 = mysql_fetch_array($result1)){
    $vendedor_id_tabla  = $row1['vendedor_id_tabla'];
    $incoTermFactura= $row1['tipo_inco_term'];
                $lugarIncoTerm =  $row1['lugar_inco_term'];
                $paisOrigen = $row1['pais_origen'];
                $puertoEmbarque = $row1['puerto_embarque'];
                $puertoDestino = $row1['puerto_destino'];
                $paisAdquisicion =  $row1['pais_adquisicion'];
                $numero_dae  =  $row1['numero_dae'];
                $numero_transporte =  $row1['numero_transporte'];
               $pais_destino = $row1['pais_destino'];
                $fleteInternacional = $row1['flete_internacional'];
                $seguroInternacional = $row1['seguro_internaiconal'];
                $gastosAduaneros =  $row1['gastos_aduaneros'];
                $gastosTransporteOtros = $row1['gastos_transporte'];
                $incoTermTotalSinImpuestos = 'FOB';
                
    $tipoIdentificacionComprador =$row1['caracter_identificacion'];
    $tipoemision="1";
    $codDoc="0".$row1['codDoc'];
    	$claveAcceso=generarClave($factura,$codDoc,$row1['ruc'],$row1['ambiente'],$row1['estab'].$row1['ptoEmi'],ceros($row1['secuencial']),substr(date("d/m/Y",strtotime($row1['fechaEmision'])),0,2).substr(date("d/m/Y",strtotime($row1['fechaEmision'])),3,2).substr(date("d/m/Y",strtotime($row1['fechaEmision'])),6,4), $tipoemision);
					
							$estab=	 $row1['estab'];
							$secuencial= ceros($row1['secuencial']);
							$razonSocial = $row1['razonSocial'];
							 $ruc = $row1['ruc'];
							 $ptoEmi= $row1['ptoEmi'];
							 $dirMatriz	= $row1['dirMatriz'];
						$socio=$row1['socio'];
						$totalDescuento	= $row1['descuento'];
						 $importeTotal	= $row1['totalventas'];
						 $entregado	= $row1['entregado'];
						 $cambio	= $row1['cambio'];
                             $subtiva0		= $row1['sub0'];
                            $sub_total	= $row1['sub_total'];
                             $iva			= $row1['iva'];
                             $propina		= $row1['propina'];
                              $obligadoContabilidad  = $row1['Ocontabilidad'];
                               $ambiente= $row1['ambiente'];
                                  $fechaEmision		= $row1['fechaEmision'];
                                   $razonSocialComprador= $row1['nombre']." ".$row1['apellido'];
                                   $identificacionComprador= $row1['cedula'];
								   $subT12 = $row1['sub12'];
								   $subT0 = $row1['sub0'];

$clienteDireccion= $row1['direccion'];
$clienteTelefono =  $row1['telefono'];
$clienteEmail = $row1['email'];
$fechaAutorizacion= $row1['fechaAutorizacion'];
$numeroAutorizacion=  $row1['numAutorizacion'];
}

$fleteInternacional = (trim($fleteInternacional)=='')?0:$fleteInternacional;
$seguroInternacional =(trim($seguroInternacional)=='')?0:$seguroInternacional; 
$gastosAduaneros =  (trim($gastosAduaneros)=='')?0:$gastosAduaneros;  
$gastosTransporteOtros = (trim($gastosTransporteOtros)=='')?0:$gastosTransporteOtros;   

$adicionales= $fleteInternacional +$seguroInternacional + $gastosAduaneros + $gastosTransporteOtros;
                
					if(  $codDoc=='01'){
						$tipoc= 'FACTURA';
					}
					elseif ($codDoc=='04') {
						$tipoc= 'NOTA DE CRÉDITO';
					}elseif ($codDoc=='100') {
						$tipoc= 'NOTA DE VENTA';
					}elseif ($codDoc=='05') {
						$tipoc= 'NOTA DE DÉBITO';
					}
					elseif ($codDoc=='06') {
						$tipoc= 'GUÍA DE REMISIÓN';
					}elseif ($codDoc=='07') {
						$tipoc= 'COMPROBANTE DE RETENCIÓN';
					}	
					// elseif ($codDoc=='05') {
					// 	$tipoc= 'PROFORMA';
					// }	elseif ($codDoc=='10') {
					// 	$tipoc= 'PEDIDO';
					// }		 
							 	    $tipoEmision= '1';

	               
                         
                            


	$Ofact=mysql_fetch_array(mysql_query("SELECT ventas.id_cliente as Cliente_id, clientes.cedula as Cedula, ventas.fecha_venta as Fecha , 
							concat(clientes.nombre,' ',clientes.apellido) as Nombres, ventas.FechaAutorizacion,id_forma_pago as FormaPago,clientes.email as 
							Correoe,empresa.leyenda as leyenda,empresa.leyenda2 as leyenda2,empresa.leyenda3 as leyenda3,empresa.leyenda4 as leyenda4,ventas.descripcion as descri,
							empresa.nombre as nombreComercial,empresa.email as email,empresa.imagen as imagen,emision.SOCIO as socio,ventas.id_vendedor as vendedor
						
							
							from ventas, clientes,empresa ,emision
							
							
							where clientes.id_cliente=ventas.id_cliente and ventas.id_venta=$factura
							and empresa.id_empresa=ventas.id_empresa and emision.id=codigo_lug "));
                            
                    	$ffile="factura";
						$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
						$pdf->setPrintHeader(false);
						$pdf->setPrintFooter(false);
						$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
						$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
						$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
						$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
						if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
							require_once(dirname(__FILE__).'/lang/eng.php');
							$pdf->setLanguageArray($l);
						}
						$pdf->SetFont('helvetica', '', 7);
						$pdf->AddPage();
						$style = array(
							'position' => '',
							'align' => 'C',
							'stretch' => false,
							'fitwidth' => true,
							'cellfitalign' => '',
							'border' => false,
							'hpadding' => 'auto',
							'vpadding' => 'auto',
							'fgcolor' => array(0,0,0),
							'bgcolor' => false, 
							'text' => true,
							'font' => 'helvetica',
							'fontsize' => 7,
							'stretchtext' => 4
						);
 
				
						$pdf->setPrintHeader(false);
						$pdf->setPrintFooter(false);
						$style['position'] = 'R';
						
				// 		descomentar
				$imagenEmpresa = $Ofact['imagen'];
				
				if(trim($imagenEmpresa) !=''){
                    $imagenEmpresa = "../sql/archivos/".$imagenEmpresa;
                    if(file_exists($imagenEmpresa)){
                    $pdf->Image("../sql/archivos/".$Ofact['imagen'], 15, 12, 80, 25, 'jpg', '', '', true, 150, '', false, false,0, false, false, false);
                    }
                }
                


						
						$y=0;
						
						
						$pdf->Cell(80,7, '','', 0, 'L',0);
						
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						
						$pdf->SetFont('helvetica','',7);
						$y3=$pdf->GetY();
						$pdf->Cell(5,7, "RUC : ", '', 1, 'L',0);
						$pdf->SetFont('helvetica','B',7);
						$pdf->SetY($y3);
						$pdf->SetX(112);
						
			
						
						
						$pdf->Cell(75,7, $ruc, '', 1, 'L',0);
						
						$pdf->Cell(80,7, '','', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$pdf->setFillColor(0, 0, 0   ); 
						$pdf->SetTextColor(255, 255, 255  ); 
						$pdf->Cell(91,7, strtoupper($tipoc)." :", '', 1, 'L',1);
						$pdf->SetTextColor(0, 0, 0  ); 
						

					$lrazon= strlen($Ofact['razonSocial']);
					$yActual = $pdf->GetY();
					$pdf->SetY($yActual+1);
					
						  
					if($lrazon>54){
						    $pdf->setFillColor(255, 255, 255  );
						    $pdf->SetFont('helvetica','B',7);
						    $pdf->MultiCell(86, 7, ''.$razonSocial, 0, 'C', 1, 1, '', '', true);
						    $pdf->Cell(9, 7, '', '', 0, 'L',0);
						    $pdf->SetY($yActual-2);	
					        $pdf->SetX(105);
					        $y3= $pdf->GetY();
					        $pdf->Cell(10,14, "Nro: ", '', 1, 'L',0);
					        $pdf->SetFont('helvetica','B',7);
						    $pdf->SetY($y3);
						 	$pdf->SetX(110);
					       // $pdf->Cell(80,7, "Nro: ".$estab."-".$ptoEmi."-".$secuencial, '', 1, 'L',0);
					       $pdf->Cell(60,14, $estab."-".$ptoEmi."-".$secuencial, '', 1, 'L',0);
						}else{
						    $pdf->setFillColor(255, 255, 255  );
						    $pdf->SetFont('helvetica','B',7);
						    $pdf->MultiCell(86, 7, ''.$razonSocial, 0, 'C', 1, 1, '', '', true);
						    $pdf->Cell(9, 7, '', '', 0, 'L',0);
						    $pdf->SetY($yActual);	
					        $pdf->SetX(105);
					        $pdf->Cell(80,7, "Nro: ".$estab."-".$ptoEmi."-".$secuencial, '', 1, 'L',0);
						 }
		
					$lnc= strlen($Ofact['nombreComercial']);
					$yActual = $pdf->GetY();
					$pdf->SetY($yActual);
					
						if($lnc>54){
						        $pdf->setFillColor(255, 255, 255  );
						    	$pdf->MultiCell(86, 7, ''.$Ofact['nombreComercial'], 0, 'C', 1, 1, '', '', true);
						    	$pdf->Cell(9, 7, '', '', 0, 'L',0);
						    	$pdf->SetY($yActual-2);	
					            $pdf->SetX(104);
						}else{
						        $pdf->setFillColor(255, 255, 255  );
						    	$pdf->MultiCell(86, 7, ''.$Ofact['nombreComercial'], 0, 'C', 1, 1, '', '', true);
						    	$pdf->Cell(9, 7, '', '', 0, 'L',0);
						    	$pdf->SetY($yActual);	
					            $pdf->SetX(104);
						}
						

						$pdf->Cell(80,7, "NÚMERO DE AUTORIZACIÓN", '', 1, 'L',0);
						//$pdf->MultiCell(80,7, "Dir. Matriz: ".$dirMatriz,0, 'L', 0, 0, '', '', false);
						$pdf->Cell(80,7,"Email: ".$Ofact['email'],'', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$ambiente=($ambiente==1)?'PRUEBAS':'PRODUCCIÓN';
						$pdf->Cell(80,7,$numeroAutorizacion, '', 1, 'L',0);
						$pdf->Cell(80,7, "Dir. Matriz: ".substr($dirMatriz,0,45),'', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						
					
						
						$lbla=($claveAcceso!=$numeroAutorizacion)?"FECHA AUTORIZACIÓN :":"";
						
	
						
						$pdf->Cell(80,7,  'FECHA AUTORIZACIÓN :', '', 1, 'L',0);
						$pdf->Cell(80,7,"Contribuyente Especial Nro: NO",'', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$pdf->Cell(80,7,  $fechaAutorizacion, '', 1, 'L',0);
						$obligadoContabilidad=($obligadoContabilidad==1)?"SI":"NO";
						$pdf->Cell(80,7,  "OBLIGADO A LLEVAR CONTABILIDAD: ".($obligadoContabilidad),'', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$pdf->Cell(80,7,  "AMBIENTE : ".$ambiente, '', 1, 'L',0);
						$despuesAmbiente = $pdf->GetY();
						// $Ofact['leyenda']='	Leyenda1 ipsum, dolor sit amet consectetur adipisicing elit. Earum esse ab fuga natus tempora culpa nisi deleniti, quo ad ut, error nulla et voluptatum consectetur non, distinctio omnis modi quibusdam';

				if($Ofact['leyenda']!=''){
				    $pdf->MultiCell(80, 7, ''.substr($Ofact['leyenda'],0, 125), '0', 'L', false);
				}else{
				 $pdf->Cell(80,7, substr($Ofact['leyenda'], 125),'', 0, 'L',0);   
				}
				$debajoLeyenda1 = $pdf->GetY();	
						// $pdf->Cell(80,7, $Ofact['leyenda'],'', 0, 'L',0);
						
						$pdf->SetY($despuesAmbiente);
					    $pdf->SetX(95);
						
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$tipoEmision= ($tipoEmision==1)?'NORMAL':'NORMAL';
						$pdf->Cell(80,7, "EMISIÓN : ".$tipoEmision, '', 1, 'L',0);
						
						// $Ofact['leyenda2']='1';	
						
				if($Ofact['leyenda2']=='1'){
						    $retencion='Resolución Nro. NAC-DNCRASC20-00000001 ';
						}else{
						      $retencion='';
						}	
						if($Ofact['leyenda']!=''){
						$pdf->SetY($debajoLeyenda1+1);}
				if($Ofact['leyenda2']!=''){
				    $pdf->MultiCell(80, 7, ''.$retencion, '0', 'L', false);
				}else{
				 $pdf->Cell(80,7, $retencion,'', 0, 'L',0);   
				}
				$debajoLeyenda2 = $pdf->GetY();	

						
						
				// if($Ofact['leyenda2']!=''){
				//     $pdf->MultiCell(80, 7, ''.$Ofact['leyenda2'], '0', 'L', false);
				// }else{
				//  $pdf->Cell(80,7, $Ofact['leyenda2'],'', 0, 'L',0);   
				// }
					    
					    
				     // $pdf->MultiCell(80, 7, ''.$Ofact['leyenda2'], 0, 'C', 1, 1, '', '', true);
				     
						// $pdf->Cell(9, 7, '', '', 0, 'L',0);
						//tenemos que averiguar la posicion en x y en y para colorcar correctamente la clave.
						$pdf->SetXY(104, 90);
						$pdf->Cell(80,7, "CLAVE DE ACCESO:", '', 1, 'L',0);
						$debajoClaveAcceso = $pdf->GetY();
					
						// $Ofact['leyenda3']='	Leyenda3 ipsum, dolor sit amet consectetur adipisicing elit. Earum esse ab fuga natus tempora culpa nisi deleniti, quo ad ut, error nulla et voluptatum consectetur non, distinctio omnis modi quibusdam';
						if($Ofact['leyenda2']=='1'){
						$pdf->SetY($debajoLeyenda2);}
				if($Ofact['leyenda3']!=''){
				    $pdf->MultiCell(80, 7, ''.substr($Ofact['leyenda3'],0, 125), '0', 'L', false);
				}else{
				 $pdf->Cell(80,7,substr($Ofact['leyenda3'],0, 125),'', 0, 'L',0);   
				}
					$debajoLeyenda3 = $pdf->GetY();	
				// 		$pdf->MultiCell(86, 7, ''.$Ofact['leyenda3'], '0', 'L', false);
				// 		$pdf->Cell(80,7,$Ofact['leyenda3'],'', 0, 'L',0);
						
						$pdf->Cell(9, 7,  '', '', 0, 'L',0);
						$pdf->SetY($debajoClaveAcceso);
						$pdf->write1DBarcode($claveAcceso, 'C39', '', '', '', 14, 0.11, $style, 'N');
						
						//  $Ofact['leyenda4']= "Leyenda4 ipsum, dolor sit amet consectetur adipisicing elit. Earum esse ab fuga natus tempora culpa nisi deleniti, quo ad ut, error nulla et voluptatum consectetur non, distinctio omnis modi quibusdam";
				
						if($Ofact['leyenda3']!=''){
						 $pdf->SetY($debajoLeyenda3);
						}
				if($Ofact['leyenda4']!=''){
				    $pdf->MultiCell(80, 7, ''.substr($Ofact['leyenda4'],0, 125), '0', 'L', false);
				}else{
				 $pdf->Cell(80,7, substr($Ofact['leyenda4'],0, 125),'', 1, 'L',0);   
				}
						
				// 		$pdf->Cell(80,7,$Ofact['leyenda4'],'', 0, 'L',0);
			
						$pdf->Cell(9, 7,  '', '', 0, 'L',0);
						$pdf->Cell(80,7, '', '', 1, 'L',0);
						$pdf->Cell(80,7,'','', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$pdf->Cell(80,7, '', '', 1, 'L',0);

						$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(215,215,215)));
	
					    $pdf->Cell(135, 7, "Razón Social/Nombres y Apellidos: ".substr($razonSocialComprador,0,50),'LT', 0, 'L',0);
						$pdf->Cell(46, 7, "RUC/Cl: ".$identificacionComprador, 'TR', 1, 'L',0);
						$pdf->Cell(135, 7,"Fecha Emisión: ".$fechaEmision,'LB', 0, 'L',0);
						$pdf->Cell(46, 7, "" , 'BR', 1, 'L',0);
						$pdf->Cell(5, 7, '', '', 0, 'L',0);
						$pdf->Cell(80,7, '', '', 1, 'L',0);
						$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(215,215,215)));
						$pdf->RoundedRect(15, 40, 86, 80, 3.50, '0000');
						$pdf->RoundedRect(103, 20, 93, 100, 3.50, '0000');
						$pdf->setFillColor(0, 0, 0  ); 
						$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(215,215,215)));
	
					$pdf->SetTextColor(255, 255, 255  );
						$pdf->Cell(20, 7, 'Código', 1, 0, 'L',1);
						$pdf->setFillColor(0, 0, 0 ); 
						$pdf->Cell(10, 7, 'Cant', 1, 0, 'R',1);
						$pdf->setFillColor(0, 0, 0  ); 
						$pdf->Cell(55, 7, 'Descripción', 1, 0, 'L',1);
						$pdf->Cell(30, 7, 'Detalle Adicional', 1, 0, 'L',1);
						$pdf->setFillColor(0, 0, 0  ); 
						
						$pdf->MultiCell(18, 7, 'Precio Unitario', 1, 'L', 1, 0, '', '', true);
				// 		$pdf->Cell(11, 7, 'Precio Unitario', 1, 0, 'R',1);
						$pdf->setFillColor(0, 0, 0   ); 
						$pdf->Cell(13, 7, 'Desc', 1, 0, 'R',1);
						$pdf->setFillColor(0, 0, 0   ); 
						$pdf->MultiCell(13, 7, 'Precio Total', 1, 'L', 1, 0, '', '', true);
				// 		$pdf->Cell(13, 7, 'Precio Total', 1, 0, 'R',1);
						$pdf->setFillColor(0, 0, 0   ); 
						$pdf->Cell(23, 7, 'Subtotal', 1, 1, 'R',1);
						$pdf->SetTextColor(0, 0, 0  );

				// 		$cdet=(mysql_query("
				// 		select productos.id_producto as  Id,productos.codigo as Codigo,productos.producto AS Nombre,
				// 		detalle_ventas.cantidad as Cantidad,detalle_ventas.v_unitario AS Preciounitario, 
				// 		detalle_ventas.descuento as Total,detalle_ventas.detalle as detalle,'' as Nombreproducto, 	centro_costo.descripcion as centro_descripcion 
				// 		FROM detalle_ventas
				// 		INNER JOIN  productos on productos.id_producto=detalle_ventas.id_servicio
				// 		INNER JOIN centro_costo ON centro_costo.id_centro_costo = productos.grupo
				// 		WHERE  detalle_ventas.id_venta=$factura 
				// 		ORDER BY centro_costo.id_centro_costo"));
						
						$cdet=(mysql_query("
						select productos.id_producto as  Id,productos.codigo as Codigo,productos.producto AS Nombre,
						detalle_ventas.cantidad as Cantidad,detalle_ventas.v_unitario AS Preciounitario, 
						detalle_ventas.descuento as Total,detalle_ventas.detalle as detalle,'' as Nombreproducto, 	centro_costo.descripcion as centro_descripcion 
						FROM detalle_ventas
						INNER JOIN  productos on productos.id_producto=detalle_ventas.id_servicio
						INNER JOIN centro_costo ON centro_costo.id_centro_costo = productos.grupo
						WHERE  detalle_ventas.id_venta=$factura 
						"));
					
							$cm=0;
						$centro ='';
						while($Odet=mysql_fetch_array($cdet)){
						    
					
						
						 if($cm==0  && $sesion_tipo_empresa=='TALLER'){
						        $centro= $Odet['centro_descripcion'];
						        	$pdf->setFillColor(200,200, 200 ); 
	 $pdf->Cell(182, $alto, $centro,  1, 1, 'C',1);
						        $cm++;
						    }
// store current object
$pdf->startTransaction();
// get the number of lines
$lines = $pdf->MultiCell(20, 0, $Odet['Codigo'], 0, 'L', 0, 0, '', '', true, 0, false,true, 0);

$lines2 = $pdf->MultiCell(55, 0, $Odet['Nombre'], 0, 'L', 0, 0, '', '', true, 0, false,true, 0);

$lin3 = $pdf->MultiCell(30, 0, ''.$Odet['detalle'], 0, 'L', 0, 0, '', '', true,0,false,true,0);

// restore previous object
$pdf = $pdf->rollbackTransaction();

$lines3= ($lines>$lines2)?$lines:$lines2;
$lines3 = ($lines3>$lin3)?$lines3:$lin3;
$alto=$lines3*3.2;
// listo
if($centro != $Odet['centro_descripcion']  && $sesion_tipo_empresa=='TALLER'){
    $centro= $Odet['centro_descripcion'];
    	$pdf->setFillColor(200,200, 200 ); 
	 $pdf->Cell(182, $alto, $centro,  1, 1, 'C',1);
}
$pdf->setFillColor(255, 255, 255  );
						    	//15,15,55,40,35,21
						    	$pdf->MultiCell(20, $alto, ''.$Odet['Codigo'], 1, 'L', 1, 0, '', '', true);
						    	
						  //  $pdf->Cell(20, $alto, $Odet['Codigo'],  1, 0, 'L',0);
					        $pdf->MultiCell(10, $alto, ''.$Odet['Cantidad'], 1, 'L', 1, 0, '', '', true);
				// 			$pdf->Cell(10, $alto, $Odet['Cantidad'],  1, 0, 'R',0);
							
							
							$pdf->setFillColor(255, 255, 255  );
							$pdf->MultiCell(55, $alto, ''.$Odet['Nombre'], 1, 'L', 1, 0, '', '', true);
							
										
							$pdf->MultiCell(30, $alto, ''.$Odet['detalle'], 1, 'L', 1, 0, '', '', true);
							
							$pdf->MultiCell(18, $alto, number_format($Odet['Preciounitario'],6), 1, 'R', 1, 0, '', '', true);
							$pdf->MultiCell(13, $alto, number_format($Odet['Total'],2), 1, 'R', 1, 0, '', '', true);
							$pdf->MultiCell(13, $alto, number_format($Odet['Preciounitario']-$Odet['Total'],2), 1, 'R', 1, 0, '', '', true);
				// 			$pdf->Cell(13, $alto, number_format($Odet['Preciounitario'],2),  1, 0, 'R',0);
				// 			$pdf->Cell(13, $alto, number_format($Odet['Total'],2),  1, 0, 'R',0);
				// 			$pdf->Cell(13, $alto, number_format($Odet['Preciounitario']-$Odet['Total'],2),  1, 0, 'R',0);
							
							if($lnom>=100){
							    $pdf->MultiCell(21, $alto,  number_format(($Odet['Preciounitario']-$Odet['Total'])*$Odet['Cantidad'],2), 1, 'R', 1, 0, '', '', true);
								// $pdf->Cell(21, $alto, number_format(($Odet['Preciounitario']-$Odet['Total'])*$Odet['Cantidad'],2), 1, 0, 'R',0);
								$pdf->setFillColor(255, 255, 255  );
								$pdf->MultiCell(72, $alto, ''.$Odet['Nombre'], 1, 'L', 1, 1, '', '', true);
							}else{
							    $pdf->MultiCell(23, $alto, number_format(($Odet['Preciounitario']-$Odet['Total'])*$Odet['Cantidad'],2), 1, 'R', 1, 1, '', '', true);
							}
								// $pdf->Cell(23, $alto, number_format(($Odet['Preciounitario']-$Odet['Total'])*$Odet['Cantidad'],2), 1, 1, 'R',0);
								
						}
				
							$pdf->Cell(181, 7,'', '', 1, 'R',0);
	$inicioFila = $pdf->GetX();
	$anchoGeneral=6;	
	
    $pdf->Cell(126, $anchoGeneral, 'Información Adicional','LRTB', 0,'L',0);
    	$numero_pagina_actual = $pdf->getPage();
    		$inicioInformacionAdicional = $pdf->GetY();
    	$pdf->Cell(1, $anchoGeneral,'', '', 1, 'R',0);
    	
						
							$inicioFila2 = $pdf->GetX();
							$fila2= $pdf->GetY();
							
							
						

							
				// 			$pdf->Cell(22, 7, 'Informacion Adicional',  '', 0, 'L',0);
				// 			$pdf->Cell(22, 7, '',  '', 0, 'L',0);
				// 			$pdf->Cell(65, 7, '',  '', 0, 'L',0);
				// 			$pdf->Cell(16, 7, '',  '', 0, 'L',0);
				// 			$pdf->Cell(35, 7, 'Subtotal 12% :',  'LRTB', 0, 'L',0);
				// 			$pdf->Cell(21, 7, number_format($subtiva,4), 'LRTB', 1, 'R',0);
				// 				 $result11=mysql_query( "SELECT detalle_ventas.cantidad,detalle_ventas.descuento,detalle_ventas.v_unitario,detalle_ventas.detalle,
				//  productos.codigo,productos.producto,productos.iva ,detalle_ventas.v_total
				// FROM detalle_ventas, productos WHERE detalle_ventas.id_servicio= productos.id_producto AND detalle_ventas.id_venta='".$factura."'");   ;   
				// 		while($det=mysql_fetch_array($result11)){
				// 		    $nadic[]= "detalle";
				// 		    $dadic[]= $det['detalle'];
				// 		}
						$result11=mysql_query( "SELECT detalle_ventas.cantidad,detalle_ventas.descuento,detalle_ventas.v_unitario,detalle_ventas.detalle,
				 productos.codigo,productos.producto,productos.iva ,detalle_ventas.v_total,detalle_ventas.total_iva, detalle_ventas.tarifa_iva, impuestos.iva as cantidad_iva
				FROM detalle_ventas, productos , impuestos
				WHERE detalle_ventas.id_servicio= productos.id_producto AND detalle_ventas.tarifa_iva = impuestos.id_iva AND detalle_ventas.id_venta='".$factura."'");   ;   
                $suma_detalles_iva = 0;
                $array_ivas= array ();
				while($det=mysql_fetch_array($result11)){
					$nadic[]= "detalle";
	                $dadic[]= $det['detalle'];
				    $clave = $det['tarifa_iva'] ;  
                    if (array_key_exists($clave, $array_ivas)) {
                        $array_ivas[$clave][0]= $det['cantidad_iva'];
                        $array_ivas[$clave][1]  += floatval($det['total_iva']);
                    } else {
                        $array_ivas[$clave][0]= $det['cantidad_iva'];
                        $array_ivas[$clave][1] = floatval($det['total_iva']);
                    }
				}
                  


							$pdf->Cell(32, $anchoGeneral, "Dirección :",  'LRTB', 0, 'L',0);
							$pdf->Cell(94, $anchoGeneral,  substr($clienteDireccion,0,60),  'LRTB', 1, 'L',0);


							$pdf->Cell(32, $anchoGeneral, "Teléfono :" ,  'LRTB', 0, 'L',0);
							$pdf->Cell(94, $anchoGeneral, substr($clienteTelefono ,0,60),  'LRTB', 1, 'L',0);
					

								$resultImpuestos=mysql_query( "select * from impuestos where id_empresa='".$sesion_id_empresa."' ");
								$Oimpuestos=mysql_fetch_array($resultImpuestos);  


							
							
							$pdf->Cell(32, $anchoGeneral, "Correo :",'LRTB' , 0, 'L',0);
							$pdf->Cell(94, $anchoGeneral, substr($clienteEmail,0,60),  'LRTB', 1, 'L',0);
						
						$sqlFormasPago="SELECT `id`, `id_forma`, `documento`, `id_factura`, `id_empresa`, `valor`, `tipo`, `porcentaje`, `fecha_registro`, `numero_retencion`, `autorizacion`, `intervalo_cuotas` FROM `cobrospagos` WHERE documento='0' AND `id_factura` = '".$factura."'  ORDER BY `cobrospagos`.`id` ASC LIMIT 1";
				$resultFormasPago = mysql_query($sqlFormasPago);
				$plazo=0;
				$tipo = 0;
				while($rowFP = mysql_fetch_array($resultFormasPago) ){
				    	$plazo=$rowFP['intervalo_cuotas'];
				    	$tipo =$rowFP['tipo'];
				}
				$plazo = trim($plazo)==''?0:$plazo;
							$a=array("SIN UTILIZACION DEL SISTEMA FINANCIERO"=>"1","20 OTROS CON UTILIZACION DEL SISTEMA FINANCIERO"=>"2","20 OTROS UTILIZACION DEL SISTEMA FINANCIERO"=>"3","CHEQUE"=>"4","DINERO ELECTRONICO"=>"17","TARJETA DE CREDITO"=>"19","20 OTROS CON UTILIZACION DEL SISTEMA FINANCIERO"=>"20");
							foreach($a as $b=>$c)
								if($c==$Ofact['FormaPago'])
									$formp=$b;
								// 	$importeTotal = '1000000.00';
							$formp=($formp=="")?"20 OTROS CON UTILIZACION DEL SISTEMA FINANCIERO":$formp;
						
							$pdf->SetX($inicioFila);
							$pdf->Cell(32, $anchoGeneral, 'FORMA DE PAGO: ',  'LRTB', 0, 'L',0);
								$pdf->SetFont('helvetica','B',6);
							$pdf->Cell(60, $anchoGeneral, $formp,  'LRTB', 0, 'L',0);
						
							$pdf->Cell(19, $anchoGeneral, '$ '.$importeTotal,  'LRTB', 0, 'L',0);
								$pdf->Cell(11, $anchoGeneral, 'PLAZO:',  'LRTB', 0, 'L',0);
							$pdf->Cell(4, $anchoGeneral, $plazo,  'LRTB', 1, 'L',0);
							
							
						$pdf->SetFont('helvetica','B',7);
						
						
							
							$pdf->Cell(32, $anchoGeneral, 'NOTA:',  'LRTB', 0, 'L',0);
							$pdf->MultiCell(94, $anchoGeneral, ''.$Ofact['descri'] ,1, 'L', 0, '1','', '', true);
							
	if($vendedor_id_tabla!=0 && $vendedor_id_tabla!=''){
	    $sqlVendedor = "SELECT `id_vendedor`, `codigo`, `nombre`, `apellidos`, `direccion`, `telefono`, `comision`, `id_empresa`, `caracter_identificacion`, `cedula`, `estado`, `email` FROM `vendedores` WHERE id_vendedor=$vendedor_id_tabla";
	    $resultVendedor = mysql_query($sqlVendedor); 
	    while($rowVen = mysql_fetch_array($resultVendedor) ){
	        $pdf->Cell(32, $anchoGeneral, 'Vendedor:',  'LRTB', 0, 'L',0);
	        	$pdf->Cell(94, $anchoGeneral,  $rowVen['nombre'].' '.$rowVen['apellidos'],  'LRTB', 1, 'L',0);
		    
	    }
	}
	
					
						
						if($socio!="0"){
			    $resultTrans=mysql_query( "select Nombres,Cedula,Placa,regimen,emision,est from transportista where Id='".$socio."' ");

  	         //   $resultTrans2=mysql_fetch_array($resultTrans);
  	            while ($resultTrans22 = mysql_fetch_array($resultTrans)) {
                            
                            $pdf->Cell(32, $anchoGeneral, 'Punto de emisión:',  'LRTB', 0, 'L',0);
							$pdf->Cell(94, $anchoGeneral, $resultTrans22['emision'], 'LRTB', 1, 'R',0); 
							$pdf->Cell(32, $anchoGeneral, 'Razón Social :',  'LRTB', 0, 'L',0);
							$pdf->Cell(94, $anchoGeneral, $resultTrans22['Nombres'], 'LRTB', 1, 'R',0); 	      
							$pdf->Cell(32, $anchoGeneral, 'Ruc :',  'LRTB', 0, 'L',0);
							$pdf->Cell(94, $anchoGeneral, $resultTrans22['Cedula'], 'LRTB', 1, 'R',0); 	 
							$pdf->Cell(32, $anchoGeneral, 'Régimen :',  'LRTB', 0, 'L',0);
							$pdf->Cell(94, $anchoGeneral, $resultTrans22['regimen'], 'LRTB', 1, 'R',0); 
							$pdf->Cell(32, $anchoGeneral, 'Placa :',  'LRTB', 0, 'L',0);
							$pdf->Cell(94, $anchoGeneral, $resultTrans22['Placa'], 'LRTB', 1, 'R',0); 	       

  	           
  	            }
			}
       
					//$pdf->SetY($fila2);
					      
				// 			//$pdf->SetY($yposNota+$anchoGeneral);
							$pdf->SetX($xpos);
							
			
			
			$pdf->setPage($numero_pagina_actual );
			$inicioFila=15;
							
				
		$inicioFila2=141;
					
					$pdf->setPage($numero_pagina_actual );
					$pdf->SetY($inicioInformacionAdicional);
					$pdf->SetX($inicioFila2);
							$sqlDetalleV = "SELECT impuestos.id_iva, impuestos.codigo, impuestos.iva, detalle_ventas.`id_detalle_venta`, detalle_ventas.`idBodega`, detalle_ventas.`idBodegaInventario`, detalle_ventas.`cantidad`, detalle_ventas.`estado`, detalle_ventas.`v_unitario`, detalle_ventas.`descuento`, SUM(detalle_ventas.`v_total`) AS base_imponible, detalle_ventas.`id_venta`, detalle_ventas.`id_servicio`, detalle_ventas.`detalle`, detalle_ventas.`id_kardex`, detalle_ventas.`tipo_venta`, detalle_ventas.`id_empresa`,  detalle_ventas.`tarifa_iva`, SUM(detalle_ventas.`total_iva`) as suma_iva FROM `detalle_ventas` INNER JOIN impuestos ON impuestos.id_iva = detalle_ventas.tarifa_iva WHERE detalle_ventas.id_venta = '".$factura."'  GROUP BY impuestos.id_iva ";
				$resultDetVenta = mysql_query( $sqlDetalleV );
				while($rowDetVent = mysql_fetch_array($resultDetVenta) ){
					$subT12 = $rowDetVent['base_imponible'];
							$fila2= $pdf->GetY();
							$pdf->SetX($inicioFila2);	
				$pdf->Cell(35, $anchoGeneral, 'Subtotal '.$rowDetVent['iva'].' % :',  'LRTB', 0, 'L',0);
							$pdf->Cell(21, $anchoGeneral,number_format($subT12,2), 'LRTB', 1, 'R',0);

				}
					
				// 	$pdf->Cell(35, $anchoGeneral, 'Subtotal 0% :',  'LRTB', 0, 'L',0);
				// 			$pdf->Cell(21, $anchoGeneral, number_format($subT0,2), 'LRTB', 1, 'R',0);
				// 			$filaSub12= $pdf->GetY();
							
				// 			$fila2= $pdf->GetY();
				// 			$pdf->SetX($inicioFila2);	
				// $pdf->Cell(35, $anchoGeneral, 'Subtotal 15% :',  'LRTB', 0, 'L',0);
				// 			$pdf->Cell(21, $anchoGeneral,number_format($subT12,2), 'LRTB', 1, 'R',0);
				// 	var_dump($array_ivas);	exit;	
	foreach ($array_ivas as $key => $value) {
            //   echo "Key: $key, Value: $value\n";
            if($value[1]>0 ){
                $pdf->SetX($inicioFila2);	
            	$pdf->Cell(35, $anchoGeneral,  utf8_decode("IVA ".$value[0]." %:" ),  'LRTB', 0, 'L',0);
            	$pdf->Cell(21, $anchoGeneral,number_format($value[1],2), 'LRTB', 1, 'R',0);
            }
            
            } 
            
	$pdf->SetX($inicioFila2);	
	$pdf->Cell(35, $anchoGeneral, utf8_decode("Subtotal :"),  'LRTB', 0, 'L',0);
	$pdf->Cell(21, $anchoGeneral,$sub_total, 'LRTB', 1, 'R',0);
	$pdf->SetX($inicioFila2);	
	$pdf->Cell(35, $anchoGeneral, 'Descuento :',  'LRTB', 0, 'L',0);
	$pdf->Cell(21, $anchoGeneral, number_format($totalDescuento,2), 'LRTB', 1, 'R',0);
	$pdf->SetX($inicioFila2);	
    $pdf->Cell(35, $anchoGeneral, 'Propina :',  'LRTB', 0, 'L',0);
							$pdf->Cell(21, $anchoGeneral, number_format($propina,2), 'LRTB', 1, 'R',0);		
					

		
							
							
							$pdf->SetX($inicioFila2);	
							$pdf->Cell(35, $anchoGeneral, 'Total :',  'LRTB', 0, 'L',0);
							$pdf->Cell(21, $anchoGeneral, number_format($importeTotal,2), 'LRTB', 1, 'R',0);
			$numero_pagina_actual2 = $pdf->getPage();
								$pdf->Output($fffile, 'I');
						
							
							
						
				

