<?php
error_reporting(0);
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
	$emision=$_GET['emision'];
	$establecimiento=$_GET['establecimiento'];
	$placa = '';
	$sqlPed="SELECT `pedido`,`numero_pedido`,numeroHospedaje, id_mecanico FROM `pedidos` WHERE numero_pedido=$factura  and codigo_lug =$emision and id_empresa=$sesion_id_empresa ";
	$resultPed = mysql_query($sqlPed);
	while($rowPed= mysql_fetch_array($resultPed)){
	    $idPedido = $rowPed['pedido'];
	    $factura = $rowPed['pedido'];
		$id_mecanico = $rowPed['id_mecanico'];
		$placa = $rowPed['numeroHospedaje'];
	}
	$placa= ($placa=='0')?'':$placa;

	$ordenTrabajo = false;
	if ( $sesion_tipo_empresa=='TALLER' && $placa!='' ){
                  
		$sqlVehiculo = "SELECT `id`, `idCliente`, `modelo`, `marca`, `color`, `chasis`, `motor`, `placa`, `modeloMotor`, `kilometraje`, `archivo1`, `archivo2`, `archivo3`, `fechaRegistro` FROM `vehiculos`  WHERE placa='".$placa."';";
	   $resultVehiculo = mysql_query( $sqlVehiculo);
	   while($rowVehiculo = mysql_fetch_array($resultVehiculo)){
			$idVehiculo=$rowVehiculo['id'];
			$marcaVehiculo = $rowVehiculo['marca'];
			$modeloVehiculo = $rowVehiculo['modelo'];
			$colorVehiculo = $rowVehiculo['color'];
			$chasisVehiculo = $rowVehiculo['chasis'];
			$motorVehiculo = $rowVehiculo['motor'];
			$placaVehiculo = $rowVehiculo['placa'];
			$modeloMotorVehiculo = $rowVehiculo['modeloMotor'];
			$kilometrajeVehiculo = $rowVehiculo['kilometraje'];
			$ordenTrabajo=true;
						   }

		$sqlMecanico ="SELECT pedidos.pedido, empleados.nombre, empleados.apellido, empleados.cedula  FROM pedidos INNER JOIN empleados ON empleados.id_empleado = pedidos.id_mecanico WHERE pedidos.pedido=$idPedido";			
		$resultMecanico = mysql_query($sqlMecanico); 
		while($rowM = mysql_fetch_array($resultMecanico)){
			$nombreMecanico = $rowM['nombre'].' '.$rowM['apellido'];
			$cedulaMecanico = $rowM['cedula'];
		}  
	}

	 $sql1= "SELECT
    codigo_lug AS codigo_lug,
    ambiente AS ambiente,
    tipoEmision AS tipoEmision,
    empresa.nombre AS nombreComercial,
    empresa.razonSocial AS razonSocial,
    empresa.ruc AS ruc,
    clave_acceso AS claveAcceso,
    tipo_documento AS codDoc,
    establecimientos.codigo AS estab,
    emision.codigo AS ptoEmi,
    pedidos.numero_pedido AS secuencial,
    empresa.direccion AS dirMatriz,
    empresa.clave AS clave,
    empresa.FElectronica AS firma,
    pedidos.fecha_pedido AS fechaEmision,
    clientes.apellido,
    clientes.nombre,
    clientes.direccion,
    clientes.cedula,
    clientes.estado,
    clientes.caracter_identificacion,
    empresa.Ocontabilidad,
    empresa.FElectronica,
    pedidos.sub0,
    pedidos.sub12,
    pedidos.sub_total,
    pedidos.descuento,
    impuestos.iva,
    clientes.telefono,
    clientes.email,
    empresa.autorizacion_sri,
    pedidos.descripcion,
    establecimientos.direccion AS dirSucursal,
    empresa.leyenda AS rimpe,
    empresa.leyenda2 AS retencion,
    pedidos.propina AS propina,
    pedidos.total AS totalventas,
    pedidos.autorizacion AS numAutorizacion,
    pedidos.fecha_autorizacion AS fechaAutorizacion,
    emision.SOCIO AS socio,
    pedidos.vendedor_id AS vendedor
FROM
    pedidos,
    emision,
    empresa,
    establecimientos,
    clientes,
    impuestos
WHERE
    impuestos.id_iva = pedidos.id_iva AND clientes.id_cliente = pedidos.id_cliente AND pedidos.pedido =$factura AND emision.id = codigo_lug AND empresa.id_empresa = pedidos.id_empresa AND establecimientos.id = pedidos.codigo_pun AND emision.id = pedidos.codigo_lug";  
$result1 = mysql_query($sql1);

$vendedor=0;
while($row1 = mysql_fetch_array($result1)){
    $tipoemision="1";
    $codDoc="0".$row1['codDoc'];
   $vendedor=$row1['vendedor'];
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
					
							 	    $tipoEmision= '1';

	               
                         
                            


	$Ofact=mysql_fetch_array(mysql_query("SELECT
    pedidos.id_cliente AS Cliente_id,
    clientes.cedula AS Cedula,
    pedidos.fecha_pedido AS Fecha,
    CONCAT(
        clientes.nombre,
        ' ',
        clientes.apellido
    ) AS Nombres,
    pedidos.fecha_autorizacion,
    id_forma_pago AS FormaPago,
    clientes.email AS Correoe,
    empresa.leyenda AS leyenda,
    empresa.leyenda2 AS leyenda2,
    empresa.leyenda3 AS leyenda3,
    empresa.leyenda4 AS leyenda4,
    pedidos.descripcion AS descri,
    empresa.nombre AS nombreComercial,
    empresa.email AS email,
    empresa.imagen AS imagen,
    emision.SOCIO AS socio,
    pedidos.vendedor_id AS vendedor
FROM
    pedidos,
    clientes,
    empresa,
    emision
WHERE
    clientes.id_cliente = pedidos.id_cliente AND pedidos.pedido =$factura AND empresa.id_empresa = pedidos.id_empresa AND emision.id = codigo_lug"));
                            
                    	$ffile="Pedido";
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
						
						//descomentar
				// 		$pdf->Image("archivos/".$Ofact['imagen'], 15, 12, 80, 25, 'jpg', '', '', true, 150, '', false, false,0, false, false, false);
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
						
						
						
$pdf->Cell(91, 7, strtoupper($_SERVER['SERVER_NAME'] == 'jderp.cloud' || $_SERVER['SERVER_NAME'] == 'www.jderp.cloud') ? 'Pedido:' : 'ORDEN DE TRABAJO:', '', 1, 'L', 1);

						
						
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
                    $ultimaLinea2=1;
					if  ($ordenTrabajo  ){
					
					$pdf->SetX(104);
					$pdf->setFillColor(0, 0, 0   ); 
						$pdf->SetTextColor(255, 255, 255  ); 
						$pdf->Cell(91,7, strtoupper('Datos del VehÍculo')." :", '', 1, 'L',1);
						$pdf->SetTextColor(0, 0, 0  ); 
						$pdf->SetY($yActual+7);
						$pdf->SetX(104);
					$pdf->Cell(80,7, "Marca: ".$marcaVehiculo, '', 1, 'L',0);
					$pdf->SetY($yActual+7);
					$pdf->SetX(144);
					$pdf->Cell(80,7, "Modelo: ".$modeloVehiculo, '', 1, 'L',0);
					$pdf->SetX(104);
					$pdf->Cell(80,7, "Color: ".$colorVehiculo, '', 1, 'L',0);
					$pdf->SetY($yActual+14);
					$pdf->SetX(144);
					$pdf->Cell(80,7, "Chasis: ".$chasisVehiculo, '', 1, 'L',0);
					$pdf->SetX(104);
					$pdf->Cell(80,7, "Motor: ".$motorVehiculo, '', 1, 'L',0);
					$pdf->SetY($yActual+21);
					$pdf->SetX(144);
					$pdf->Cell(80,7, "Placa: ".$placaVehiculo, '', 1, 'L',0);
					$pdf->SetX(104);
					$pdf->Cell(80,7, "Kilometraje: ".$kilometrajeVehiculo , '', 1, 'L',0);
					$pdf->SetY($yActual+28);
					$pdf->SetX(144);
					$pdf->Cell(80,7, "Modelo de motor: ".$modeloMotorVehiculo, '', 1, 'L',0);

					$pdf->SetX(104);
					$pdf->setFillColor(0, 0, 0   ); 
						$pdf->SetTextColor(255, 255, 255  ); 
						$pdf->Cell(91,7, strtoupper('Datos del MecÁnico')." :", '', 1, 'L',1);
						$pdf->SetTextColor(0, 0, 0  ); 
						
						$pdf->SetX(104);
						$pdf->Cell(80,7, "Cedula: ".$cedulaMecanico , '', 1, 'L',0);
						$pdf->SetY($yActual+42);
						$pdf->SetX(144);
						$pdf->Cell(80,7, "Nombre: ".$nombreMecanico , '', 1, 'L',0);
						 $ultimaLinea2= $pdf->GetY();
					}
					$pdf->SetX(10);
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
						

						$pdf->Cell(80,7, '', '', 1, 'L',0);
						//$pdf->MultiCell(80,7, "Dir. Matriz: ".$dirMatriz,0, 'L', 0, 0, '', '', false);
						$pdf->Cell(80,7,"Email: ".$Ofact['email'],'', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$ambiente=($ambiente==1)?'PRUEBAS':'PRODUCCIÓN';
						$pdf->Cell(80,7,'', '', 1, 'L',0);
						$pdf->Cell(80,7, "Dir. Matriz: ".substr($dirMatriz,0,45),'', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						
					
						
						$lbla=($claveAcceso!=$numeroAutorizacion)?"FECHA AUTORIZACIÓN :":"";
						
	
						
						$pdf->Cell(80,7,  '', '', 1, 'L',0);
						$pdf->Cell(80,7,"Contribuyente Especial Nro: NO",'', 0, 'L',0);
						$pdf->Cell(9, 7, '', '', 0, 'L',0);
						$pdf->Cell(80,7, '', '', 1, 'L',0);
						$obligadoContabilidad=($obligadoContabilidad==1)?"SI":"NO";
						$pdf->Cell(80,7,  "OBLIGADO A LLEVAR CONTABILIDAD: ".($obligadoContabilidad),'', 0, 'L',0);
						$ultimaLinea= $pdf->GetY()+7;
		
						// $Ofact['leyenda']='	Leyenda1 ipsum, dolor sit amet consectetur adipisicing elit. Earum esse ab fuga natus tempora culpa nisi deleniti, quo ad ut, error nulla et voluptatum consectetur non, distinctio omnis modi quibusdam';
 $pdf->SetY($ultimaLinea);
 $pdf->SetX(15);
				if($Ofact['leyenda']!=''){
				    $pdf->MultiCell(80, 7, ''.substr($Ofact['leyenda'],0, 125), '0', 'L', false);
				   	$ultimaLinea= $pdf->GetY();
				}
				$debajoLeyenda1 = $pdf->GetY();	

						
				if($Ofact['leyenda2']=='1'){
						    $retencion='Resolución Nro. NAC-DNCRASC20-00000001 ';
						  //  	$ultimaLinea= $pdf->GetY();
						}else{
						      $retencion='';
						}	
						
						if($Ofact['leyenda']!=''){
						$pdf->SetY($debajoLeyenda1+1);}
						
				if(trim($Ofact['leyenda2'])!=''){
				    $pdf->MultiCell(80, 7, ''.$retencion, '0', 'L', false);
				    	$ultimaLinea= $pdf->GetY();
				}
				
				$debajoLeyenda2 = $pdf->GetY();	

						
					
			 //	 $Ofact['leyenda3']='	Leyenda3 ipsum, dolor sit amet consectetur adipisicing elit. Earum esse ab fuga natus tempora culpa nisi deleniti, quo ad ut, error nulla et voluptatum consectetur non, distinctio omnis modi quibusdam';
						if($Ofact['leyenda2']=='1'){
						$pdf->SetY($debajoLeyenda2);}
						
				if($Ofact['leyenda3']!=''){
				    $pdf->MultiCell(80, 7, ''.substr($Ofact['leyenda3'],0, 125), '0', 'L', false);
				    $ultimaLinea= $pdf->GetY();
				}
					$debajoLeyenda3 = $pdf->GetY();	
	
						if($Ofact['leyenda3']!=''){
						 $pdf->SetY($debajoLeyenda3);
						}
				// 		$Ofact['leyenda4']='	Leyenda4 ipsum, dolor sit amet consectetur adipisicing elit. Earum esse ab fuga natus tempora culpa nisi deleniti, quo ad ut, error nulla et voluptatum consectetur non, distinctio omnis modi quibusdam';
						
				if(trim($Ofact['leyenda4'])!=''){
				    $pdf->MultiCell(80, 7, ''.substr($Ofact['leyenda4'],0, 125), '0', 'L', false);
				    $ultimaLinea= $pdf->GetY();
				}
				
				$ultimaLinea= ($ultimaLinea2>$ultimaLinea)?$ultimaLinea2:$ultimaLinea;
						$pdf->SetY($ultimaLinea+7);
				
                    
						$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(215,215,215)));
	
					    $pdf->Cell(135, 7, "Razón Social/Nombres y Apellidos: ".substr($razonSocialComprador,0,50),'LT', 0, 'L',0);
						$pdf->Cell(46, 7, "RUC/Cl: ".$identificacionComprador, 'TR', 1, 'L',0);
						$pdf->Cell(135, 7,"Fecha Emisión: ".$fechaEmision,'LB', 0, 'L',0);
						$pdf->Cell(46, 7, "" , 'BR', 1, 'L',0);
						$pdf->Cell(5, 7, '', '', 0, 'L',0);
						$pdf->Cell(80,7, '', '', 1, 'L',0);
						$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(215,215,215)));
						$lineainferior=$ultimaLinea-37;
						$lineainferior2=$ultimaLinea-17;
						$pdf->RoundedRect(15, 40, 86, $lineainferior, 3.50, '0000');
						$pdf->RoundedRect(103, 20, 93, $lineainferior2, 3.50, '0000');
						$pdf->setFillColor(0, 0, 0  ); 
						$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(215,215,215)));
	
					$pdf->SetTextColor(255, 255, 255  );
						$pdf->Cell(20, 7, 'Código', 1, 0, 'L',1);
						$pdf->setFillColor(0, 0, 0 ); 
						$pdf->Cell(10, 7, 'Cant', 1, 0, 'R',1);
						$pdf->setFillColor(0, 0, 0  ); 
						$pdf->Cell(55, 7, 'Descripción', 1, 0, 'L',1);
						$pdf->Cell(35, 7, 'Detalle Adicional', 1, 0, 'L',1);
						$pdf->setFillColor(0, 0, 0  ); 
						
						$pdf->MultiCell(13, 7, 'Precio Unitario', 1, 'L', 1, 0, '', '', true);
				// 		$pdf->Cell(11, 7, 'Precio Unitario', 1, 0, 'R',1);
						$pdf->setFillColor(0, 0, 0   ); 
						$pdf->Cell(13, 7, 'Desc', 1, 0, 'R',1);
						$pdf->setFillColor(0, 0, 0   ); 
						$pdf->MultiCell(13, 7, 'Precio Total', 1, 'L', 1, 0, '', '', true);
				// 		$pdf->Cell(13, 7, 'Precio Total', 1, 0, 'R',1);
						$pdf->setFillColor(0, 0, 0   ); 
						$pdf->Cell(23, 7, 'Subtotal', 1, 1, 'R',1);
						$pdf->SetTextColor(0, 0, 0  );

						$cdet=(mysql_query("SELECT 
						productos.id_producto AS Id, productos.codigo AS Codigo,
						productos.producto AS Nombre, 
						detalle_pedido.cantidad AS Cantidad, 
						detalle_pedido.v_unitario AS Preciounitario,
						detalle_pedido.descuento AS Total, 
						detalle_pedido.detalle_descuento AS detalle, 
						detalle_pedido.v_total AS v_total ,
						centro_costo.descripcion as centro_descripcion,
						'' AS Nombreproducto 
						FROM detalle_pedido
						INNER JOIN productos on productos.id_producto = detalle_pedido.id_servicio
						INNER JOIN centro_costo ON centro_costo.id_centro_costo = productos.grupo
						WHERE  detalle_pedido.pedido =$factura 
						ORDER BY centro_costo.id_centro_costo"));
					
						$cm=0;
						$centro ='';
						while($Odet=mysql_fetch_array($cdet)){
						    if($cm==0 && $sesion_tipo_empresa=='TALLER'){
						        $centro= $Odet['centro_descripcion'];
						        	$pdf->setFillColor(200,200, 200 ); 
	 $pdf->Cell(182, $alto, $centro,  1, 1, 'C',1);
						        $cm++;
						    }
						    
						    $Odet['Preciounitario'] = $Odet['Preciounitario']+  $Odet['Total'];
// store current object
$pdf->startTransaction();
// get the number of lines
$lines = $pdf->MultiCell(35, 0, $Odet['Nombre'], 0, 'L', 0, 0, '', '', true, 0, false,true, 0);
// restore previous object
$pdf = $pdf->rollbackTransaction();

$pdf->startTransaction();
// get the number of lines
$lines2 = $pdf->MultiCell(35, 0, $Odet['Nombre'], 0, 'L', 0, 0, '', '', true, 0, false,true, 0);
// restore previous object
$pdf = $pdf->rollbackTransaction();

$lines3= ($lines>$lines2)?$lines:$lines2;
$alto=$lines3*3.5;
// listo

if($centro != $Odet['centro_descripcion']  && $sesion_tipo_empresa=='TALLER'){
    $centro= $Odet['centro_descripcion'];
    	$pdf->setFillColor(200,200, 200 ); 
	 $pdf->Cell(182, $alto, $centro,  1, 1, 'C',1);
}


	  	$pdf->setFillColor(0,0, 0 );
						    	//15,15,55,40,35,21
						    $pdf->Cell(20, $alto, $Odet['Codigo'],  1, 0, 'L',0);//$pdf->Cell(22, 7, $dcod[$i],  1, 0, 'L',0);
							$pdf->Cell(10, $alto, $Odet['Cantidad'],  1, 0, 'R',0);//$pdf->Cell(22, 7, $dcant[$i],  1, 0, 'R',0);
							
							
							$pdf->setFillColor(255, 255, 255  );
							$pdf->MultiCell(55, $alto, ''.$Odet['Nombre'], 1, 'L', 1, 0, '', '', true);
							
										
							$pdf->MultiCell(35, $alto, ''.$Odet['detalle'], 1, 'L', 1, 0, '', '', true);
							
							$pdf->Cell(13, $alto, number_format($Odet['Preciounitario'],6),  1, 0, 'R',0);
							
							$pdf->Cell(13, $alto, number_format($Odet['Total'],2),  1, 0, 'R',0);
							
							$pdf->Cell(13, $alto, number_format($Odet['Preciounitario']-$Odet['Total'],2),  1, 0, 'R',0);
							
							if($lnom>=100){
								$pdf->Cell(21, $alto, number_format($Odet['v_total'],2), 1, 0, 'R',0);
								$pdf->setFillColor(255, 255, 255  );
								$pdf->MultiCell(72, $alto, ''.$Odet['Nombre'], 1, 'L', 1, 1, '', '', true);
							}else
								$pdf->Cell(23, $alto, number_format($Odet['v_total'],2), 1, 1, 'R',0);
						}
						
						
							$pdf->Cell(181, 7,'', '', 1, 'R',0);

						
						
							$pdf->Cell(22, 7, 'Información Adicional',  '', 0, 'L',0);
						$pdf->Ln();
						$numero_pagina_actual = $pdf->getPage();
    		$inicioInformacionAdicional = $pdf->GetY();
					
			
								 
				// $pdf->Cell(21, 7,utf8_decode($rowDetVent['suma_iva']), 'LRTB', 1, 'R',1);
		    $pdf->SetX(15);			
			$pdf->Cell(32, 7, "Dirección :",  '', 0, 'L',0);
			$pdf->Cell(12, 7,  substr($clienteDireccion,0,60),  '', 0, 'L',1);
        	$pdf->Ln();
        	
            $pdf->SetX(15);	
			$pdf->Cell(32, 7, "Teléfono :" ,  '', 0, 'L',0);
			$pdf->Cell(12, 7, substr($clienteTelefono ,0,60),  '', 0, 'L',1);
				$pdf->Ln();
				
			$pdf->SetX(15);			
			$pdf->Cell(32, 7, "Correo :",'' , 0, 'L',0);
			$pdf->Cell(12, 7, substr($clienteEmail,0,60),  '', 0, 'L',1);
				$pdf->Ln();
			
		
		
			
			$a=array("SIN UTILIZACION DEL SISTEMA FINANCIERO"=>"1","20 OTROS CON UTILIZACION DEL SISTEMA FINANCIERO"=>"2","CON UTILIZACION DEL SISTEMA FINANCIERO"=>"3","CHEQUE"=>"4","DINERO ELECTRONICO"=>"17","TARJETA DE CREDITO"=>"19","20 OTROS CON UTILIZACION DEL SISTEMA FINANCIERO"=>"20");
							foreach($a as $b=>$c)
								if($c==$Ofact['FormaPago'])
									$formp=$b;
							$formp=($formp=="")?"20 OTROS CON UTILIZACION DEL SISTEMA FINANCIERO":$formp;
							
			$pdf->Cell(32, 7, 'FORMA DE PAGO: ',  '', 0, 'L',0);
			$pdf->Cell(65, 7, $formp,  '', 0, 'L',0);
			$pdf->Cell(12, 7, $importeTotal,  '', 0, 'L',0);
	$pdf->Ln();
	
			$pdf->SetX(15);	
			$pdf->Cell(32, 7, 'NOTA:',  '', 0, 'L',0);
			$pdf->MultiCell(70, 7, ''.$Ofact['descri'], 0, 'L',false);				

							
			$yposNota= $pdf->GetY();
							
						
					        if( $vendedor!='0' && trim($vendedor)!=''){
					            $sqlVendedor="SELECT `id_vendedor`, `codigo`, `nombre`, `apellidos`, `direccion`, `telefono`, `comision`, `id_empresa`, `caracter_identificacion`, `cedula`, `estado`, `email` FROM `vendedores` WHERE id_vendedor=$vendedor";
					            $resultVendedor= mysql_query($sqlVendedor);
					            $nombreVendedor =='';
					            while($rowVend = mysql_fetch_array($resultVendedor) ){
					                $nombreVendedor =$rowVend['nombre'].' '.$rowVend['apellidos'];
					            }
					          $pdf->Cell(32, 7, 'VENDEDOR:',  '', 0, 'L',0);
							
					        $pdf->MultiCell(70, 7, $nombreVendedor, 0, 'L',false);  
					        
					        }
					        
					        
			
	
						
					
			if($socio!="0"){
			    $resultTrans=mysql_query( "select Nombres,Cedula,Placa from transportista where Id='".$socio."' ");

  	         //   $resultTrans2=mysql_fetch_array($resultTrans);
  	            while ($resultTrans22 = mysql_fetch_array($resultTrans)) {

							$pdf->Cell(35, 7, 'Nombres y Apellidos :',  'LRTB', 0, 'L',0);
							$pdf->Cell(90, 7, $resultTrans22['Nombres'], 'LRTB', 1, 'R',0); 
						
							$pdf->Cell(35, 7, 'Identificacion :',  'LRTB', 0, 'L',0);
							$pdf->Cell(90, 7, $resultTrans22['Cedula'], 'LRTB', 1, 'R',0); 	  
						
							$pdf->Cell(35, 7, 'Placa :',  'LRTB', 0, 'L',0);
							$pdf->Cell(90, 7, $resultTrans22['Placa'], 'LRTB', 1, 'R',0); 	
							

  	           
  	           
  	            }
			}
			
			
			$inicioFila2=141;
					
					$pdf->setPage($numero_pagina_actual );
					$pdf->SetY($inicioInformacionAdicional);
					$pdf->SetX($inicioFila2);
							$sqlDetalleV = "SELECT
                    impuestos.id_iva,
                    impuestos.codigo,
                    impuestos.iva,
                    detalle_pedido.`tarifa_iva`,
                    SUM(detalle_pedido.`v_total`) AS base_imponible,
                    SUM(detalle_pedido.`total_iva`) AS suma_iva
                FROM
                    `detalle_pedido`
                INNER JOIN impuestos ON impuestos.id_iva = detalle_pedido.tarifa_iva
                WHERE
                    detalle_pedido.pedido = '".$factura."'
                GROUP BY
                    impuestos.id_iva";
				$resultDetVenta = mysql_query( $sqlDetalleV );
				while($rowDetVent = mysql_fetch_array($resultDetVenta) ){
					$subT12 = $rowDetVent['base_imponible'];
							$fila2= $pdf->GetY();
							$pdf->SetX($inicioFila2);	
				$pdf->Cell(35, 7, 'Subtotal '.$rowDetVent['iva'].' % :',  'LRTB', 0, 'L',0);
							$pdf->Cell(21, 7,number_format($subT12,2), 'LRTB', 1, 'R',1);

				}		
					$sqlDetalleV = "SELECT
                    impuestos.id_iva,
                    impuestos.codigo,
                    impuestos.iva,
                    detalle_pedido.`tarifa_iva`,
                    SUM(detalle_pedido.`v_total`) AS base_imponible,
                    SUM(detalle_pedido.`total_iva`) AS suma_iva
                FROM
                    `detalle_pedido`
                INNER JOIN impuestos ON impuestos.id_iva = detalle_pedido.tarifa_iva
                WHERE
                    detalle_pedido.pedido = '".$factura."'
                GROUP BY
                    impuestos.id_iva";
				$resultDetVenta = mysql_query( $sqlDetalleV );
				while($rowDetVent = mysql_fetch_array($resultDetVenta) ){
					$subT12 = $rowDetVent['base_imponible'];
							$fila2= $pdf->GetY();
							$pdf->SetX($inicioFila2);
							
				$pdf->Cell(35, 7,  utf8_decode("IVA ".$rowDetVent['iva']."% :" ),  'LRTB', 0, 'L',0);
							$pdf->Cell(21, 7,utf8_decode($rowDetVent['suma_iva']), 'LRTB', 1, 'R',1);

				}
				$pdf->SetX($inicioFila2);
				$pdf->Cell(35, 7, utf8_decode("Subtotal :"),  'LRTB', 0, 'L',0);
				$pdf->Cell(21, 7,$sub_total, 'LRTB', 1, 'R',1);
				
				$pdf->SetX($inicioFila2);
				$pdf->Cell(35, 7, 'Descuento :',  'LRTB', 0, 'L',0);
				$pdf->Cell(21, 7, number_format($totalDescuento,2), 'LRTB', 1, 'R',1);			
				
				$pdf->SetX($inicioFila2);
				$pdf->Cell(35, 7, 'Propina :',  'LRTB', 0, 'L',0);
				$pdf->Cell(21, 7, number_format($propina,2), 'LRTB', 1, 'R',1);
				
				$pdf->SetX($inicioFila2);	
				$pdf->Cell(35, 7, 'Total :',  'LRTB', 0, 'L',0);
				$pdf->Cell(21, 7, number_format($importeTotal,2), 'LRTB', 1, 'R',1);
							
								$pdf->Output($fffile, 'I');
						
							
							
						
				
