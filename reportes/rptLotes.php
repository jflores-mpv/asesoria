<?php

	session_start();
	$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_id_periodo_contable = $_SESSION['sesion_id_periodo_contable'];
    $sesion_empresa_ruc = $_SESSION['sesion_empresa_ruc'];
    $sesion_ciudades_ciudad = $_SESSION['sesion_ciudades_ciudad'];
	$sesion_empresa_direccion = $_SESSION['sesion_empresa_direccion'];

	
	require_once('../conexion.php');
	require_once('class.ezpdf.php');
    date_default_timezone_set("America/Guayaquil");
    $pdf =& new Cezpdf('a4');
    $pdf->selectFont('fonts/courier.afm');
    $pdf->ezSetCmMargins(1,1,1.5,1.5);
    $datacreador = array (
    	'Title'=>'Facturas',
    	'Subject'=>'Saldo Inicial',
    	'Author'=>'25 de junio',
    	'Producer'=>'Macarena Lalama'
    	);
	
    $pdf->addInfo($datacreador);
    $pdf->ezText("<b>Fecha actual:</b> ".date("d/m/Y"), 10,array( 'justification' => 'right' ));
	$pdf->ezText("<b>REPORTE DE LOTES ".$titulo."</b>", 18,array( 'justification' => 'center' ));
	$pdf->ezText("<b>".strtoupper($sesion_empresa_nombre)."</b>", 14,array( 'justification' => 'center' ));
// 	$pdf->ezText("<b>Desde: ".$fecha_desde."    Hasta: ".$fecha_hasta."</b>\n", 12,array( 'justification' => 'center' ));
	
	

	
    $sql ="SELECT 
    lotes.`id_lote`, 
    lotes.`numero_lote`, 
    lotes.`fecha_elaboracion`, 
    lotes.`fecha_caducidad`, 
    lotes.`calidad_lote`, 
    lotes.`estado_lote`, 
    lotes.`fecha_registro`, 
    lotes.`detalle`, 
    lotes.`id_empresa` ,
     lotes.`tipo_origen_lote` ,
    calidad_lote.calidad,
    estado_lote.estado
FROM `lotes` 
LEFT JOIN estado_lote ON estado_lote.id_estado = lotes.estado_lote
LEFT JOIN calidad_lote ON calidad_lote.id_calidad = lotes.calidad_lote
WHERE  lotes.`id_empresa`='".$sesion_id_empresa."'
 ";  
 if(trim($_GET["txtFechaElaboracion"])!=''){
        $sql .= " AND DATE_FORMAT(lotes.`fecha_elaboracion`,'%Y-%m-%d')= '".$_GET["txtFechaElaboracion"]."' "; 
    }
    if(trim($_GET["txtFechaRegistro"])!=''){
        $sql .= " AND DATE_FORMAT(lotes.`fecha_registro`,'%Y-%m-%d')= '".$_GET["txtFechaRegistro"]."' "; 
    }
    if(trim($_GET["txtFechaVencimiento"])!=''){
        $sql .= " AND DATE_FORMAT(lotes.`fecha_caducidad`,'%Y-%m-%d')= '".$_GET["txtFechaVencimiento"]."' "; 
    }
if(trim($_GET["calidad_lote"])!='0'){
    $sql .= " AND lotes.`calidad_lote`= '".$_GET["calidad_lote"]."' "; 
}
if(trim($_GET["estado_lote"])!='0'){
    $sql .= " AND lotes.`estado_lote`= '".$_GET["estado_lote"]."' "; 
}
if(trim($_GET["criterio_usu_per"])!=''){
    $sql .= " and lotes.`numero_lote` like '%".$_GET["criterio_usu_per"]."%' "; 
}
   $sql.= " order by tipo_origen_lote, ".$_GET['criterio_ordenar_por']."  ".$_GET['criterio_orden'] ;
  
		  function ceros($valor){
			$s='';
		 for($i=1;$i<=9-strlen($valor);$i++)
			 $s.="0";
		 return $s.$valor;
	 }
	 
	$data=array();
    $codigoPun=0;
        $titles = array(
		'numero_lote' => '<b>No. de Lote</b>',
		'fecha_elaboracion' => '<b>Fecha de elaboracion</b>',
		'fecha_caducidad' => '<b>Fecha de caducidad</b>',
		
		'calidad_lote' => '<b>Calidad del lote</b>',
		'estado_lote' => '<b>Estado del lote</b>',
		'fecha_registro' => '<b>Fecha de registro</b>',
	); 

        	$options = array(
			'shadeCol'=>array(0.9,0.9,0.9),
			'xOrientation'=>'center',
			'width'=>1500,
			'cols'=>array(
			 'numero_lote'=>array('justification'=>'center','width'=>40),
			'fecha_elaboracion'=>array('justification'=>'left','width'=>60),
			'fecha_caducidad'=>array('justification'=>'left','width'=>70),
			'fecha_registro'=>array('justification'=>'center','width'=>50),
			'calidad_lote'=>array('justification'=>'right','width'=>75),
			'estado_lote'=>array('justification'=>'right','width'=>75),
			)
			);
        
	$options['fontSize']= 7;
	$numero=0;
	$numeroProducto=0;
    $data = array();
    $data2 = array();
	$result = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($result)) //for mayor    for($i=0; $i<$numero_filas; $i++)
	{
	    if($row['tipo_origen_lote']==1){
	        $data[] = array(
                'numero_lote'=>  $row['numero_lote'] ,
                'fecha_elaboracion'=>date("Y-m-d",strtotime($row['fecha_elaboracion']) ),
                
                'fecha_caducidad'=> date("Y-m-d",strtotime($row['fecha_caducidad']) ) ,
                'fecha_registro'=>date("Y-m-d",strtotime($row['fecha_registro']) ),
                'calidad_lote'=>($row['calidad']) ,
                'estado_lote'=>$row['estado']
                );
       
	    }else{
	        $data2[] = array(
                'numero_lote'=>  $row['numero_lote'] ,
                'fecha_elaboracion'=>date("Y-m-d",strtotime($row['fecha_elaboracion']) ),
                
                'fecha_caducidad'=> date("Y-m-d",strtotime($row['fecha_caducidad']) ) ,
                'fecha_registro'=>date("Y-m-d",strtotime($row['fecha_registro']) ),
                'calidad_lote'=>($row['calidad']) ,
                'estado_lote'=>$row['estado']
                );
       
	    }
	     
    
    
	  
	}

	$pdf->ezText("" ,12);
	$pdf->ezText("<b>PRODUCCION</b>", 11,array( 'justification' => 'center' ));
	$pdf->ezText("" );
	$pdf->ezTable($data, $titles, '', $options);
	
	$cantidad_c = count($data2);
	if($cantidad_c>0){
	    $pdf->ezText("<b>COMPRAS</b>", 11,array( 'justification' => 'center' ));
	$pdf->ezText("");
	$pdf->ezTable($data2, $titles, '', $options);
	}
	
	$pdf->ezStartPageNumbers(550, 80, 10);
	$pdf->ezStream();
	$pdf->Output('reporteSaldoInicial.pdf', 'D');

	mysql_close();
	mysql_free_result($result);

?>
