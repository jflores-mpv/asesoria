<?php
error_reporting(0);


	session_start();
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

	$pdf->ezText("<b>REPORTE DE PROVEEDORES ".$titulo."</b>", 18,array( 'justification' => 'center' ));
	$pdf->ezText("<b>".strtoupper($sesion_empresa_nombre)."</b>", 14,array( 'justification' => 'center' ));
// 	$pdf->ezText("<b>Desde: ".$fecha_desde."    Hasta: ".$fecha_hasta."</b>\n", 12,array( 'justification' => 'center' ));
	
	


	

    $sql ="SELECT
    `id_proveedor`,
    `nombre_comercial`,
    `nombre`,
    `ruc`,
    `email`,
    proveedores.`id_ciudad`,
    `id_empresa`,
    `nombreProveedor`,
    apellidoProveedor,
    ciudades.ciudad
FROM
    `proveedores`
INNER JOIN  ciudades ON ciudades.id_ciudad = proveedores.id_ciudad
WHERE
    `id_empresa` ='".$sesion_id_empresa."'
     ";  
      
     
   	if ($criterio_usu_per!=''){
        $sql .= " and nombre_comercial  like '%".trim($criterio_usu_per)."%'   "; 
    }
      
   

    if($_GET["txtProvincias"]!='0'){
        // $sql .= " and ciudades.`id_provincia` =".$_GET["txtProvincias"]." "; 

        if($_GET["txtCiudad"]!='0'){
            // $sql .= " and ciudades.`id_ciudad` =".$_GET["txtCiudad"]." ";
        }
    }

    if(trim($_GET["txtCedulaCliente"])!=''){
        $sql .= " and proveedores.`ruc` like '%".$criterio_usu_per."%' "; 
    }
    $sql.= " ORDER BY $criterio_ordenar_por $criterio_orden LIMIT $criterio_mostrar";

	$result = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($result)) 
	{

		$data[] = array(
		'cedula' => $row['ruc'],
		'nombres' =>  utf8_decode($row['nombre_comercial']),
		'email' => utf8_decode($row['email']),
		'ciudad' => utf8_decode($row['ciudad'])
	
		);
	 
	}

		
	$titles = array(
		'cedula' => utf8_decode('<b>Cédula</b>'),
		'nombres' => '<b>Nombres</b>',
		'email' => '<b>Email</b>',
		'ciudad' => '<b>Ciudad</b>'
	);
		
		 //tercera        
	$options = array
	(
			'shadeCol'=>array(0.9,0.9,0.9),
			'xOrientation'=>'center',
			'width'=>800,
			'cols'=>array(
			'cedula'=>array('justification'=>'left','width'=>70),
			'nombres'=>array('justification'=>'left','width'=>250),
			 'email'=>array('justification'=>'left','width'=>150),
			'ciudad'=>array('justification'=>'left','width'=>100)
			)
	 
	);


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
