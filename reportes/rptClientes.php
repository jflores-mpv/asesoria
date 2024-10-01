<?php
error_reporting(0);


	session_start();
	 $dominio = $_SERVER['SERVER_NAME'];
	$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_id_periodo_contable = $_SESSION['sesion_id_periodo_contable'];
    $sesion_empresa_ruc = $_SESSION['sesion_empresa_ruc'];
    $sesion_ciudades_ciudad = $_SESSION['sesion_ciudades_ciudad'];
	$sesion_empresa_direccion = $_SESSION['sesion_empresa_direccion'];
		// $sesion_empresa_telefono = $_SESSION['sesion_empresa_telefono1'];
// 		$sesion_codigo_lug = $_SESSION['userest'];
// 	$sesion_codigo_lug = isset($_SESSION['sesion_codigo_lug'])?$_SESSION['sesion_codigo_lug']:0;
	
	require_once('../conexion.php');
	require_once('class.ezpdf.php');
    date_default_timezone_set("America/Guayaquil");
    $pdf =& new Cezpdf('a4','Landscape');

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


 $criterio_usu_per= $_GET['txtBuscar'];
  $criterio_usu_tipo= $_GET['criterio_tipo'];
  $num_registros = $_GET['criterio_mostrar'];
  $criterio_ordenar_por= $_GET['criterio_ordenar_por'];
  $criterio_orden = $_GET['criterio_orden'];
  $criterio_mostrar = $_GET['criterio_mostrar'];
  $estado_cliente = $_GET['estado_cliente'];
$estado_general_cliente =$_GET['estado_general_cliente'];

	$pdf->ezText("<b>REPORTE DE CLIENTES ".$titulo."</b>", 18,array( 'justification' => 'center' ));
	$pdf->ezText("<b>".strtoupper($sesion_empresa_nombre)."</b>", 14,array( 'justification' => 'center' ));
// 	$pdf->ezText("<b>Desde: ".$fecha_desde."    Hasta: ".$fecha_hasta."</b>\n", 12,array( 'justification' => 'center' ));
	
	

 $txtBuscarVendedor = trim($_GET['txtBuscarVendedor']);
	

    $sql ="SELECT
     clientes.`id_cliente` AS clientes_id_cliente,
     clientes.`nombre` AS clientes_nombre,
     clientes.`apellido` AS clientes_apellido,
     clientes.`direccion` AS clientes_direccion,
     clientes.`cedula` AS clientes_cedula,
     clientes.`telefono` AS clientes_telefono,
     clientes.`movil` AS clientes_movil,
     clientes.`fecha_nacimiento` AS clientes_fecha_nacimiento,
     clientes.`email` AS clientes_email,
     clientes.`estado` AS clientes_estado,
     clientes.`id_ciudad` AS clientes_id_ciudad,
     clientes.`fecha_registro` AS clientes_fecha_registro,
      clientes.`fecha_afiliacion` AS clientes_fecha_afiliacion,
     ciudades.ciudad AS ciudades_ciudad,
      clientes.`estado_afiliado`,
      clientes.numero_afiliado,
      vendedores.nombre as vendedor_nombre,
       vendedores.apellidos as vendedor_apellido
FROM
     `clientes` clientes
     LEFT JOIN ciudades ON  ciudades.id_ciudad = clientes.id_ciudad
     LEFT JOIN vendedores ON  vendedores.id_vendedor = clientes.id_vendedor
 where clientes.`id_empresa`= '".$sesion_id_empresa."'
     ";  
      
     
   	if ($criterio_usu_per!=''){
        $sql .= " and CONCAT (clientes.nombre, ' ',clientes.apellido )  like '%".trim($criterio_usu_per)."%'   "; 
    }
      
      
            
    if($dominio=='jderp.cloud' or $dominio=='www.jderp.cloud'or $dominio=='contaweb.ec' or $dominio=='www.contaweb.com.ec' or $dominio=='www.contaweb.ec'){
        if ($txtBuscarVendedor!=''){
        $sql .= " and CONCAT (vendedores.nombre, ' ',vendedores.apellidos )  like '%".trim($txtBuscarVendedor)."%'   "; 
        }
    }
     
      

    if($_GET["txtProvincias"]!='0'){
        $sql .= " and ciudades.`id_provincia` =".$_GET["txtProvincias"]." "; 

        if($_GET["txtCiudad"]!='0'){
            $sql .= " and ciudades.`id_ciudad` =".$_GET["txtCiudad"]." ";
        }
    }

    if(trim($_GET["txtCedulaCliente"])!=''){
        $sql .= " and clientes.`cedula` like '%".$_GET["txtCedulaCliente"]."%' "; 
    }
      if($estado_general_cliente!='0' && ($sesion_id_empresa==116 || $sesion_id_empresa==1827 ) ){
        
        if($estado_general_cliente == 'CLIENTES'){
            
            if($estado_cliente=='0'){
                $sql .= " AND  UPPER(clientes.`estado_afiliado`) = 'DESAFILIADO' "; 
            }else{
                $sql .= " and clientes.`estado` = '".$estado_cliente."' AND  UPPER(clientes.`estado_afiliado`) = 'DESAFILIADO' "; 
            }
            
        }
        
        if($estado_general_cliente == 'AFILIADOS'){
            if($estado_cliente=='0'){
                $sql .= " AND  UPPER(clientes.`estado_afiliado`) != 'DESAFILIADO' "; 
            }else{
                 $sql .= " and UPPER(clientes.`estado_afiliado`) = '".$estado_cliente."'  "; 
            }
             
        }
        
    }
    
    $sql.= " ORDER BY $criterio_ordenar_por $criterio_orden LIMIT $criterio_mostrar";

	$result = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($result)) 
	{
        
              
              
    if($estado_general_cliente!='0' && ($sesion_id_empresa==116 || $sesion_id_empresa==1827  ) ){
        
        if($estado_general_cliente == 'CLIENTES'){
           	$data[] = array(
		'cedula' => $row['clientes_cedula'],
		'nombres' =>  utf8_decode($row['clientes_nombre'].' '.$row['clientes_apellido']),
		'email' => utf8_decode($row['clientes_email']),
		'ciudad' => utf8_decode($row['ciudades_ciudad']),
		'fechaRegistro' => $row['clientes_fecha_registro'],
		'estado' => $row['clientes_estado']
		);
        }
        if($estado_general_cliente == 'AFILIADOS'){
           	$data[] = array(
		'cedula' => $row['clientes_cedula'],
		'nombres' =>  utf8_decode($row['clientes_nombre'].' '.$row['clientes_apellido']),
		'email' => utf8_decode($row['clientes_email']),
		'ciudad' => utf8_decode($row['ciudades_ciudad']),
		'fechaRegistro' => $row['clientes_fecha_registro'],
		'fecha_afiliado' => $row['clientes_fecha_afiliacion'],
		'estado_afiliado' => $row['estado_afiliado'],
		'numero_afiliado' => $row['numero_afiliado']
		);
        }
    }else{
         if($dominio=='jderp.cloud' or $dominio=='www.jderp.cloud'or $dominio=='contaweb.ec' or $dominio=='www.contaweb.com.ec' or $dominio=='www.contaweb.ec'){
             	$data[] = array(
		'cedula' => $row['clientes_cedula'],
		'nombres' =>  utf8_decode($row['clientes_nombre'].' '.$row['clientes_apellido']),
		'email' => utf8_decode($row['clientes_email']),
		'ciudad' => utf8_decode($row['ciudades_ciudad']),
		'fechaRegistro' => $row['clientes_fecha_registro'],
		'vendedor' => $row['vendedor_nombre'].' '.$row['vendedor_apellido']
		);
         }else{
             	$data[] = array(
		'cedula' => $row['clientes_cedula'],
		'nombres' =>  utf8_decode($row['clientes_nombre'].' '.$row['clientes_apellido']),
		'email' => utf8_decode($row['clientes_email']),
		'ciudad' => utf8_decode($row['ciudades_ciudad']),
		'fechaRegistro' => $row['clientes_fecha_registro']
		);
         }
        
    }
           
            
	
	 
	}

	
	  if($estado_general_cliente!='0' && ($sesion_id_empresa==116 || $sesion_id_empresa==1827 ) ){
        
        if($estado_general_cliente == 'CLIENTES'){
           $titles = array(
		'cedula' => utf8_decode('<b>Cédula</b>'),
		'nombres' => '<b>Nombres</b>',
		'email' => '<b>Email</b>',
		'ciudad' => '<b>Ciudad</b>',
		'fechaRegistro' => '<b>Fecha de Registro</b>',
			'estado' => '<b>Estado</b>'
	);
		$options = array
	(
			'shadeCol'=>array(0.9,0.9,0.9),
			'xOrientation'=>'center',
			'width'=>800,
			'cols'=>array(
			'cedula'=>array('justification'=>'left','width'=>70),
			'nombres'=>array('justification'=>'left','width'=>250),
			 'email'=>array('justification'=>'left','width'=>150),
			'ciudad'=>array('justification'=>'left','width'=>100),
			'fechaRegistro'=>array('justification'=>'left','width'=>100),
			'estado'=>array('justification'=>'left','width'=>100)
			)
	 
	);
	
        }
        if($estado_general_cliente == 'AFILIADOS'){
           $titles = array(
		'cedula' => utf8_decode('<b>Cédula</b>'),
		'nombres' => '<b>Nombres</b>',
		'email' => '<b>Email</b>',
		'ciudad' => '<b>Ciudad</b>',
		'fechaRegistro' => '<b>Fecha de Registro</b>',
			'fecha_afiliado' => utf8_decode('<b>Fecha de Afiliación</b>'),
			'estado_afiliado' => '<b>Estado</b>',
		'numero_afiliado' => utf8_decode('<b>Numero Afiliado</b>')
	);
		$options = array
	(
			'shadeCol'=>array(0.9,0.9,0.9),
			'xOrientation'=>'center',
			'width'=>800,
			'cols'=>array(
			'cedula'=>array('justification'=>'left','width'=>70),
			'nombres'=>array('justification'=>'left','width'=>250),
			 'email'=>array('justification'=>'left','width'=>150),
			'ciudad'=>array('justification'=>'left','width'=>100),
			'fechaRegistro'=>array('justification'=>'left','width'=>80),
				'fecha_afiliado'=>array('justification'=>'left','width'=>50),
			'estado_afiliado' => array('justification'=>'left','width'=>60),
		    'numero_afiliado' =>array('justification'=>'left','width'=>40)
			)
	 
	);
        }
    }else{
        if($dominio=='jderp.cloud' or $dominio=='www.jderp.cloud'or $dominio=='contaweb.ec' or $dominio=='www.contaweb.com.ec' or $dominio=='www.contaweb.ec'){
               $titles = array(
		'cedula' => utf8_decode('<b>Cédula</b>'),
		'nombres' => '<b>Nombres</b>',
		'email' => '<b>Email</b>',
		'ciudad' => '<b>Ciudad</b>',
		'fechaRegistro' => '<b>Fecha de Registro</b>',
		'vendedor' => '<b>Vendedor</b>'
	);
		$options = array
	(
			'shadeCol'=>array(0.9,0.9,0.9),
			'xOrientation'=>'center',
			'width'=>800,
			'cols'=>array(
			'cedula'=>array('justification'=>'left','width'=>70),
			'nombres'=>array('justification'=>'left','width'=>250),
			 'email'=>array('justification'=>'left','width'=>150),
			'ciudad'=>array('justification'=>'left','width'=>100),
			'fechaRegistro'=>array('justification'=>'left','width'=>100),
				'vendedor'=>array('justification'=>'left','width'=>100)
			)
	 
	);
        }else{
               $titles = array(
		'cedula' => utf8_decode('<b>Cédula</b>'),
		'nombres' => '<b>Nombres</b>',
		'email' => '<b>Email</b>',
		'ciudad' => '<b>Ciudad</b>',
		'fechaRegistro' => '<b>Fecha de Registro</b>'
	);
		$options = array
	(
			'shadeCol'=>array(0.9,0.9,0.9),
			'xOrientation'=>'center',
			'width'=>800,
			'cols'=>array(
			'cedula'=>array('justification'=>'left','width'=>70),
			'nombres'=>array('justification'=>'left','width'=>250),
			 'email'=>array('justification'=>'left','width'=>150),
			'ciudad'=>array('justification'=>'left','width'=>100),
			'fechaRegistro'=>array('justification'=>'left','width'=>100)
			)
	 
	);
        }
     
    }



	$options['fontSize']= 7;

	$txttit.= "";
	$pdf->ezText($txttit, 12);
	$pdf->ezTable($data, $titles, '', $options);
	$pdf->ezStartPageNumbers(550, 80, 10);
	$pdf->ezStream();
	$pdf->Output('Reporte_clientes.pdf', 'D');

	mysql_close();
	mysql_free_result($result);

?>
