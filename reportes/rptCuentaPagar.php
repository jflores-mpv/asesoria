<?php
error_reporting(0);
//ob_end_clean();
//Start session

session_start();

//Include database connection details
require_once('../conexion.php');
require_once('class.ezpdf.php');
$pdf =& new Cezpdf('a4');
$pdf->selectFont('fonts/courier.afm');
$pdf->ezSetCmMargins(1,1,1.5,1.5);
$datacreador = array (
                    'Title'=>'Libro Mayor',
                    'Subject'=>'detalle del Libro Mayor',
                    'Author'=>'25 de junio',
                    'Producer'=>'Macarena Lalama'
                    );
$pdf->addInfo($datacreador);
$pdf->ezText("<b>Fecha:</b> ".date("d/m/Y"), 10,array( 'justification' => 'right' ));
 $tipo_anticipo = $_GET['tipo_anticipo'];
$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
$sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];


        $fecha_desde_principal = explode(" ", $_GET['txtFechaDesde']);
        $fecha_hasta_principal = explode(" ", $_GET['txtFechaHasta']);
        $fecha_desde =  $fecha_desde_principal[0];
        $fecha_hasta = $fecha_hasta_principal[0];
        $pdf->ezText("<b>CUENTAS POR PAGAR</b>", 18,array( 'justification' => 'center' ));
        $pdf->ezText("<b>".strtoupper($sesion_empresa_nombre)."</b>", 14,array( 'justification' => 'center' ));
        $pdf->ezText("<b>Desde: ".strtoupper($fecha_desde_principal[0])."    Hasta: ".strtoupper($fecha_hasta_principal[0])."</b>\n", 12,array( 'justification' => 'center' ));
    $cuentaTipo=$_GET['switch-four'];
      $estado=$_GET['switch-estado'];
      $switch_tipo_fecha = isset($_GET['switch_tipo_fecha']) ?$_GET['switch_tipo_fecha']:'Vencimiento'; 
    if($estado=='Canceladas'){
               $fehca='fecha_pago';
              
          }else{
             $fehca='fecha_vencimiento';
          }

if($cuentaTipo==1){
$sql = "SELECT
     proveedores.id_proveedor AS proveedores_id_cliente,
     proveedores.`nombre_comercial` AS proveedores_nombre,
     proveedores.`direccion` AS proveedores_direccion,
     proveedores.`ruc` AS proveedores_cedula,
     proveedores.`telefono` AS proveedores_telefono,
     cuentas_por_pagar.id_cuenta_por_pagar AS cuentas_por_pagar_id_cuenta_por_cobrar,
     cuentas_por_pagar.numero_compra AS cuentas_por_pagar_numero_factura,
     cuentas_por_pagar.referencia AS cuentas_por_pagar_referencia,
     cuentas_por_pagar.valor AS cuentas_por_pagar_valor,
     cuentas_por_pagar.saldo AS cuentas_por_pagar_saldo,
     cuentas_por_pagar.numero_asiento AS cuentas_por_pagar_numero_asiento,
     cuentas_por_pagar.fecha_vencimiento AS cuentas_por_pagar_fecha_vencimiento,
     cuentas_por_pagar.fecha_pago AS cuentas_por_pagar_fecha_pago,
     cuentas_por_pagar.id_proveedor AS cuentas_por_pagar_id_cliente,
     cuentas_por_pagar.`id_plan_cuenta` AS cuentas_por_pagar_id_plan_cuenta,
     cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
     cuentas_por_pagar.id_compra AS cuentas_por_pagar_id_venta,
     cuentas_por_pagar.estado AS cuentas_por_pagar_estado,
     cuentas_por_pagar.id_forma_pago AS cuentas_por_pagar_id_forma_pago,
     cuentas_por_pagar.banco_referencia AS cuentas_por_pagar_banco_referencia,
     cuentas_por_pagar.documento_numero AS cuentas_por_pagar_documento_numero
FROM
     `proveedores` proveedores 
    INNER JOIN `cuentas_por_pagar` cuentas_por_pagar ON proveedores.id_proveedor = cuentas_por_pagar.id_proveedor and proveedores.id_empresa=".$sesion_id_empresa." 
    LEFT JOIN compras	ON compras.id_compra = cuentas_por_pagar.id_compra" ;
    
  $sql .= " where cuentas_por_pagar.`id_empresa`='".$sesion_id_empresa."'  ";  
  
 
        if ($_GET['txtBuscarCuentasPagar']!=''){
		$sql .= " and proveedores.`nombre` like '%".substr($_GET['txtBuscarCuentasPagar'], 0, 16)."%' "; }
	
	    if($estado != 'Todos'){
               if($_GET['switch-estado']=='Pendientes' ){
            $sql.="  and  cuentas_por_pagar.saldo>0 ";
        }else{
             $sql.="  and  cuentas_por_pagar.saldo=0 ";
        }
          }
          
	if($switch_tipo_fecha == 'Vencimiento'){
		     	 if(isset($_GET['txtFechaDesde']) && isset($_GET['txtFechaHasta']) ){
		     	     if($estado == 'Todos'){
     $sql .= " AND cuentas_por_pagar.`id_empresa`='".$sesion_id_empresa."'  AND(
    
    (DATE_FORMAT(
        cuentas_por_pagar.fecha_pago,
        '%Y-%m-%d'
    ) >= '".$fecha_desde."' AND DATE_FORMAT(
        cuentas_por_pagar.fecha_pago,
        '%Y-%m-%d'
    ) <= '".$fecha_hasta."' AND cuentas_por_pagar.`saldo` = 0) or 
    (DATE_FORMAT(
        cuentas_por_pagar.fecha_vencimiento,
        '%Y-%m-%d'
    ) >= '".$fecha_desde."' AND DATE_FORMAT(
        cuentas_por_pagar.fecha_vencimiento,
        '%Y-%m-%d'
    ) <= '".$fecha_hasta."' ) ) "; 
}else{
       $sql.="  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') <= '".$fecha_hasta."' ";
}
            // $sql.="  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') <= '".$fecha_hasta."' ";
        }
        
	}else{
		   $sql.="  and DATE_FORMAT(compras.fecha_compra, '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(compras.fecha_compra, '%Y-%m-%d') <= '".$fecha_hasta."' ";  
	}
	
        if($_GET['listado_por']=='Facturas'){
        $sql.=" GROUP BY  cuentas_por_pagar.`numero_compra`  ";
    }  
      
       $sql.=" ORDER BY  cuentas_por_pagar.`id_proveedor`  DESC  LIMIT ".$_GET['criterio_mostrar'] ;
      
}
if ($cuentaTipo=='2'){
    $sql = "SELECT
     clientes.`id_cliente` AS proveedores_id_cliente,
    CONCAT(clientes.`nombre`,' ',clientes.`apellido` ) AS proveedores_nombre,
     clientes.`direccion` AS proveedores_direccion,
     clientes.`cedula` AS proveedores_cedula,
     clientes.`telefono` AS proveedores_telefono,
     cuentas_por_pagar.id_cuenta_por_pagar AS cuentas_por_pagar_id_cuenta_por_cobrar,
     cuentas_por_pagar.numero_compra AS cuentas_por_pagar_numero_factura,
     cuentas_por_pagar.referencia AS cuentas_por_pagar_referencia,
     cuentas_por_pagar.valor AS cuentas_por_pagar_valor,
     cuentas_por_pagar.saldo AS cuentas_por_pagar_saldo,
     cuentas_por_pagar.numero_asiento AS cuentas_por_pagar_numero_asiento,
     cuentas_por_pagar.fecha_vencimiento AS cuentas_por_pagar_fecha_vencimiento,
     cuentas_por_pagar.fecha_pago AS cuentas_por_pagar_fecha_pago,
     cuentas_por_pagar.id_proveedor AS cuentas_por_pagar_id_cliente,
     cuentas_por_pagar.`id_plan_cuenta` AS cuentas_por_pagar_id_plan_cuenta,
     cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
     cuentas_por_pagar.id_compra AS cuentas_por_pagar_id_venta,
     cuentas_por_pagar.estado AS cuentas_por_pagar_estado,
     cuentas_por_pagar.id_forma_pago AS cuentas_por_pagar_id_forma_pago,
     cuentas_por_pagar.banco_referencia AS cuentas_por_pagar_banco_referencia,
     cuentas_por_pagar.documento_numero AS cuentas_por_pagar_documento_numero
FROM
      `clientes` clientes
     
     INNER JOIN `cuentas_por_pagar` cuentas_por_pagar ON clientes.`id_cliente` = cuentas_por_pagar.`id_cliente`
     WHERE clientes.id_empresa=".$sesion_id_empresa." ";
   
        if ($_GET['txtBuscarCuentasPagar']!=''){
		$sql .= " and clientes.`nombre` like '%".substr($_GET['txtBuscarCuentasPagar'], 0, 16)."%' "; }
		
	     if(isset($_GET['txtFechaDesde']) && isset($_GET['txtFechaHasta']) ){
	              	     if($estado == 'Todos'){
     $sql .= " AND cuentas_por_pagar.`id_empresa`='".$sesion_id_empresa."'  AND(
    
    (DATE_FORMAT(
        cuentas_por_pagar.fecha_pago,
        '%Y-%m-%d'
    ) >= '".$fecha_desde."' AND DATE_FORMAT(
        cuentas_por_pagar.fecha_pago,
        '%Y-%m-%d'
    ) <= '".$fecha_hasta."' AND cuentas_por_pagar.`saldo` = 0) or 
    (DATE_FORMAT(
        cuentas_por_pagar.fecha_vencimiento,
        '%Y-%m-%d'
    ) >= '".$fecha_desde."' AND DATE_FORMAT(
        cuentas_por_pagar.fecha_vencimiento,
        '%Y-%m-%d'
    ) <= '".$fecha_hasta."' ) ) "; 
}else{
       $sql.="  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') <= '".$fecha_hasta."' ";
}
        //     $sql.="  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') 
        // <= '".$fecha_hasta."' ";
        }
        
          if($estado != 'Todos'){
               if($_GET['switch-estado']=='Pendientes' ){
            $sql.="  and  cuentas_por_pagar.saldo>0 ";
        }else{
             $sql.="  and  cuentas_por_pagar.saldo=0 ";
        }
          }
 $sql.=" ORDER BY  cuentas_por_pagar.`id_cliente`  DESC  LIMIT ".$_GET['criterio_mostrar'];
}
if ($cuentaTipo=='3'){
    $sql = "SELECT
      leads.`id` AS proveedores_id_cliente,
    CONCAT(leads.`name`,' ',leads.`apellido` ) AS proveedores_nombre,
     leads.`direccion` AS proveedores_direccion,
     leads.`identificacion` AS proveedores_cedula,
     leads.`telefono` AS proveedores_telefono,
     cuentas_por_pagar.id_cuenta_por_pagar AS cuentas_por_pagar_id_cuenta_por_cobrar,
     cuentas_por_pagar.numero_compra AS cuentas_por_pagar_numero_factura,
     cuentas_por_pagar.referencia AS cuentas_por_pagar_referencia,
     cuentas_por_pagar.valor AS cuentas_por_pagar_valor,
     cuentas_por_pagar.saldo AS cuentas_por_pagar_saldo,
     cuentas_por_pagar.numero_asiento AS cuentas_por_pagar_numero_asiento,
     cuentas_por_pagar.fecha_vencimiento AS cuentas_por_pagar_fecha_vencimiento,
     cuentas_por_pagar.fecha_pago AS cuentas_por_pagar_fecha_pago,
     cuentas_por_pagar.id_proveedor AS cuentas_por_pagar_id_cliente,
     cuentas_por_pagar.`id_plan_cuenta` AS cuentas_por_pagar_id_plan_cuenta,
     cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
     cuentas_por_pagar.id_compra AS cuentas_por_pagar_id_venta,
     cuentas_por_pagar.estado AS cuentas_por_pagar_estado,
     cuentas_por_pagar.id_forma_pago AS cuentas_por_pagar_id_forma_pago,
     cuentas_por_pagar.banco_referencia AS cuentas_por_pagar_banco_referencia,
     cuentas_por_pagar.documento_numero AS cuentas_por_pagar_documento_numero
FROM

     
     `leads` leads
     
     INNER JOIN `cuentas_por_pagar` cuentas_por_pagar ON leads.`id` = cuentas_por_pagar.`id_lead` 
     WHERE leads.id_empresa=".$sesion_id_empresa." ";
        if ($_GET['txtBuscarCuentasPagar']!=''){
		$sql .= " and  CONCAT(leads.`name`,leads.`apellido` ) like '%".substr($_GET['txtBuscarCuentasPagar'], 0, 16)."%' "; }
		
	     if(isset($_GET['txtFechaDesde']) && isset($_GET['txtFechaHasta']) ){
	              	     if($estado == 'Todos'){
     $sql .= " AND cuentas_por_pagar.`id_empresa`='".$sesion_id_empresa."'  AND(
    
    (DATE_FORMAT(
        cuentas_por_pagar.fecha_pago,
        '%Y-%m-%d'
    ) >= '".$fecha_desde."' AND DATE_FORMAT(
        cuentas_por_pagar.fecha_pago,
        '%Y-%m-%d'
    ) <= '".$fecha_hasta."' AND cuentas_por_pagar.`saldo` = 0) or 
    (DATE_FORMAT(
        cuentas_por_pagar.fecha_vencimiento,
        '%Y-%m-%d'
    ) >= '".$fecha_desde."' AND DATE_FORMAT(
        cuentas_por_pagar.fecha_vencimiento,
        '%Y-%m-%d'
    ) <= '".$fecha_hasta."' ) ) "; 
}else{
       $sql.="  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') <= '".$fecha_hasta."' ";
}
        //     $sql.="  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') 
        // <= '".$fecha_hasta."' ";
        }
        
          if($estado != 'Todos'){
               if($_GET['switch-estado']=='Pendientes' ){
            $sql.="  and  cuentas_por_pagar.saldo>0 ";
        }else{
             $sql.="  and  cuentas_por_pagar.saldo=0 ";
        }
          }
          
    
  $sql.=" ORDER BY  cuentas_por_pagar.`id_lead`  DESC  LIMIT ".$_GET['criterio_mostrar'];
}
if ($cuentaTipo=='4'){
    $sql = "SELECT
      empleados.`id_empleado` AS proveedores_id_cliente,
    CONCAT(empleados.`nombre`,' ',empleados.`apellido` ) AS proveedores_nombre,
     empleados.`direccion` AS proveedores_direccion,
     empleados.`cedula` AS proveedores_cedula,
     empleados.`telefono` AS proveedores_telefono,
     cuentas_por_pagar.id_cuenta_por_pagar AS cuentas_por_pagar_id_cuenta_por_cobrar,
     cuentas_por_pagar.numero_compra AS cuentas_por_pagar_numero_factura,
     cuentas_por_pagar.referencia AS cuentas_por_pagar_referencia,
     cuentas_por_pagar.valor AS cuentas_por_pagar_valor,
     cuentas_por_pagar.saldo AS cuentas_por_pagar_saldo,
     cuentas_por_pagar.numero_asiento AS cuentas_por_pagar_numero_asiento,
     cuentas_por_pagar.fecha_vencimiento AS cuentas_por_pagar_fecha_vencimiento,
     cuentas_por_pagar.fecha_pago AS cuentas_por_pagar_fecha_pago,
     cuentas_por_pagar.id_proveedor AS cuentas_por_pagar_id_cliente,
     cuentas_por_pagar.`id_plan_cuenta` AS cuentas_por_pagar_id_plan_cuenta,
     cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
     cuentas_por_pagar.id_compra AS cuentas_por_pagar_id_venta,
     cuentas_por_pagar.estado AS cuentas_por_pagar_estado,
     cuentas_por_pagar.id_forma_pago AS cuentas_por_pagar_id_forma_pago,
     cuentas_por_pagar.banco_referencia AS cuentas_por_pagar_banco_referencia,
     cuentas_por_pagar.documento_numero AS cuentas_por_pagar_documento_numero
FROM
     
     `empleados` empleados
     
     INNER JOIN `cuentas_por_pagar` cuentas_por_pagar ON  empleados.`id_empleado` = cuentas_por_pagar.`id_empleado`
     WHERE empleados.id_empresa=".$sesion_id_empresa." ";
        if (isset($_GET['txtBuscarCuentasPagar'])){
	$sql .= " and  empleados.`nombre`  like '%".substr($_GET['txtBuscarCuentasPagar'], 0, 16)."%' "; }
	
		
	     if(isset($_GET['txtFechaDesde']) && isset($_GET['txtFechaHasta']) ){
	              	     if($estado == 'Todos'){
     $sql .= " AND cuentas_por_pagar.`id_empresa`='".$sesion_id_empresa."'  AND(
    
    (DATE_FORMAT(
        cuentas_por_pagar.fecha_pago,
        '%Y-%m-%d'
    ) >= '".$fecha_desde."' AND DATE_FORMAT(
        cuentas_por_pagar.fecha_pago,
        '%Y-%m-%d'
    ) <= '".$fecha_hasta."' AND cuentas_por_pagar.`saldo` = 0) or 
    (DATE_FORMAT(
        cuentas_por_pagar.fecha_vencimiento,
        '%Y-%m-%d'
    ) >= '".$fecha_desde."' AND DATE_FORMAT(
        cuentas_por_pagar.fecha_vencimiento,
        '%Y-%m-%d'
    ) <= '".$fecha_hasta."' ) ) "; 
}else{
       $sql.="  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') <= '".$fecha_hasta."' ";
}
        //     $sql.="  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') >= '".$fecha_desde."'  and DATE_FORMAT(cuentas_por_pagar.".$fehca.", '%Y-%m-%d') 
        // <= '".$fecha_hasta."' ";
        }
          if($estado != 'Todos'){
               if($_GET['switch-estado']=='Pendientes' ){
            $sql.="  and  cuentas_por_pagar.saldo>0 ";
        }else{
             $sql.="  and  cuentas_por_pagar.saldo=0 ";
        }
          }
        
  	  $sql.=" ORDER BY  cuentas_por_pagar.`id_empleado`  DESC  LIMIT ".$_GET['criterio_mostrar'];
}
    
    //  echo $sql;
     
	
	if($sesion_id_empresa==41){
	   // echo $sql;
	}
         
        $result = mysql_query($sql) or die(mysql_error());
        $numero_filas = mysql_num_rows($result); // obtenemos el nأ╙mero de filas
        $numero = 0;
       
    //    echo $sql."***    ".$numero_filas;exit;
        $data2 = array(
        );
          $options1 = array(
                        'shadeCol'=>array(0.9,0.9,0.9),
                        'xOrientation'=>'center',
                        'width'=>550,
                        'cols'=>array(
							
                            'numero_factura'=>array('justification'=>'left','width'=>80),
                            'documento_numero'=>array('justification'=>'left','width'=>80),
                            'fecha_emision'=>array('justification'=>'left','width'=>100),
                            'fecha_pago'=>array('justification'=>'right','width'=>100),
                            'valor_inicial'=>array('justification'=>'right','width'=>95),
                            'saldo'=>array('justification'=>'right','width'=>95)
                             )
                        );

            $options2 = array(
                        'shadeCol'=>array(0.9,0.9,0.9),
                        'xOrientation'=>'center',
                        'width'=>550,
                        'cols'=>array(
                                    'cedula'=>array('justification'=>'left','width'=>140),
                                    'nombre'=>array('justification'=>'left','width'=>260),
                                    'telefono'=>array('justification'=>'left','width'=>150),

                                     )
                        );
            $options3 = array(
                        'shadeCol'=>array(0.9,0.9,0.9),
                        'xOrientation'=>'center',
                        'width'=>550,
                        'cols'=>array(
                            'numero_factura'=>array('justification'=>'right','width'=>360),
                            'valor_inicial'=>array('justification'=>'right','width'=>95),                            
                            'saldo'=>array('justification'=>'right','width'=>95)

                                     )
                        );
                        
            $data3 = array(
            );
            
                $titles1 = array(
				
                'numero_factura' => '<b>Nro. Fac</b>',
                'documento_numero' => '<b>Dcto. Nro.</b>',                
                'fecha_emision' => '<b>'.utf8_decode('Fecha Emi').'</b>',
                'fecha_pago' => '<b>'.utf8_decode('Fecha Pago').'</b>',
                'valor_inicial' => '<b>Val. Inicial</b>',
                'saldo' => '<b>Saldo</b>'
                );
               
        

            
        $id_proveedor= 0;
        $sumaValor=0;
        $sumaSaldo=0;
        while($row = mysql_fetch_array($result)){ //************************* FOR MAYOR ****************************************
        if($numero==0){
             $id_proveedor  = $row['proveedores_id_cliente'] ;
        }
        if($row['proveedores_id_cliente'] != $id_proveedor && $numero>0){
             $pdf->ezTable($data2, $titles2, '', $options2);
            $pdf->ezText($txttit, 0.5);
            $pdf->ezTable($data1, $titles1, '', $options1);
            $pdf->ezText($txttit, 2);
            $pdf->ezTable($data3, $titles3, '', $options3);
            
            //$pdf->ezTable($data3, $titles3, '', $options3);
            $pdf->ezText("\n\n\n", 10);
            $id_proveedor  = $row['proveedores_id_cliente'] ;
                     $data1 = "";
                     $titles3='';
                     $titles2='';
                      $sumaValor=0;
        $sumaSaldo=0;
        }
            $numero ++;
            $proveedores_nombre_apellido = $row['proveedores_nombre']." ".$row['proveedores_apellido'];
            $proveedores_cedula = $row['proveedores_cedula'];
            $proveedores_telefono = $row['proveedores_telefono'];
            
            $cuentas_por_pagar_numero_factura = $row['cuentas_por_pagar_numero_factura'];
            $cuentas_por_pagar_documento_numero = $row['cuentas_por_pagar_documento_numero'];
            $cuentas_por_pagar_fecha_vencimiento = $row['cuentas_por_pagar_fecha_vencimiento'];
            $cuentas_por_pagar_fecha_pago = $row['cuentas_por_pagar_fecha_pago'];
            $cuentas_por_pagar_valor =  $row['cuentas_por_pagar_valor'];
            $cuentas_por_pagar_saldo =   $row['cuentas_por_pagar_saldo'];
            
            $sumaValor = $sumaValor + $cuentas_por_pagar_valor;
            $sumaSaldo = $sumaSaldo + $cuentas_por_pagar_saldo;
                
            $titles2 = array(
                'cedula' => 'Cedula: '.$proveedores_cedula,
                'nombre' => 'Nombre: '.utf8_decode($proveedores_nombre_apellido),
                'telefono' => 'Tel: '.($proveedores_telefono)

                );
            $data1[] = array(
				
                'numero_factura'=>$cuentas_por_pagar_numero_factura,
                'documento_numero'=>$cuentas_por_pagar_documento_numero,
                'fecha_emision'=>$cuentas_por_pagar_fecha_vencimiento,
                'fecha_pago'=>$cuentas_por_pagar_fecha_pago,
                'valor_inicial'=>number_format($cuentas_por_pagar_valor, 2, '.', ' '),
                'saldo'=>number_format($cuentas_por_pagar_saldo, 2, '.', ' ')
                );

            $titles3 = array(
            'numero_factura'=>'TOTALES: ',
            'valor_inicial'=>number_format($sumaValor, 2, '.', ' '),                
            'saldo'=>number_format($sumaSaldo, 2, '.', ' '),

            );
  
        }
        
         $pdf->ezTable($data2, $titles2, '', $options2);
            $pdf->ezText($txttit, 0.5);
            $pdf->ezTable($data1, $titles1, '', $options1);
            $pdf->ezText($txttit, 2);
            $pdf->ezTable($data3, $titles3, '', $options3);
            
            //$pdf->ezTable($data3, $titles3, '', $options3);
            $pdf->ezText("\n\n\n", 10);
       
        
        $txttit.= "";
        $pdf->ezText($txttit, 12);
                
        //$pdf->ezText("<b>Hora:</b> ".date("H:i:s")."\n\n", 10);
        $pdf->ezStartPageNumbers(550, 80, 10);
        //$nombrearchivo = "reporteLibroMayor.pdf";
        $pdf->ezStream();
        //$pdf->ezOutput($nombrearchivo);
        $pdf->Output('CuentasPorPagar.pdf', 'D');

        mysql_close();
        mysql_free_result($result);

?>