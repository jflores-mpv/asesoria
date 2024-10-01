<?php
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
                    'Title'=>'Saldo Inicial',
                    'Subject'=>'Saldo Inicial',
                    'Author'=>'25 de junio',
                    'Producer'=>'Laura Quimi'
                    );
$pdf->addInfo($datacreador);
$pdf->ezText("<b>Fecha actual:</b> ".date("d/m/Y"), 10,array( 'justification' => 'right' ));

$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
$sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];

$id_registro = $_GET["id_registro"];

        $fecha_desde_principal = explode(" ", $_GET['txtFechaDesde']);
        $fecha_hasta_principal = explode(" ", $_GET['txtFechaHasta']);
        $fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
        $fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");
        $pdf->ezText("<b>SALDO INICIAL</b>", 18,array( 'justification' => 'center' ));
        $pdf->ezText("<b>".strtoupper($sesion_empresa_nombre)."</b>", 14,array( 'justification' => 'center' ));
        $pdf->ezText("<b>Desde: ".strtoupper($fecha_desde_principal[0])."    Hasta: ".strtoupper($fecha_hasta_principal[0])."</b>\n", 12,array( 'justification' => 'center' ));

$sql = "SELECT
	 registros.`id_registro` AS registros_id_registro,
     registros.`numero_registro` AS registros_numero_registro,
	 registros.`id_cliente` AS registros_id_cliente,
	 registros.`fecha_registro` AS registros_fecha_registro,
	 registros.`sub_total` AS registros_sub_total,
	 registros.`id_iva` AS registros_id_iva,
	 registros.`total` AS registros_total,
     clientes.`id_cliente` AS clientes_id_cliente,
     clientes.`nombre` AS clientes_nombre,
     clientes.`apellido` AS clientes_apellido,
     clientes.`direccion` AS clientes_direccion,
     clientes.`cedula` AS clientes_cedula,
     clientes.`numero_casa` AS clientes_numero_casa
	FROM
     `clientes` clientes INNER JOIN `registros` registros ON clientes.`id_cliente` = registros.`id_cliente` " ;
     $sql .= " where registros.`id_empresa`='".$sesion_id_empresa."' ";
		 
		if (isset($_GET['txtFechaDesde'])){
            $sql .= " and  registros.`fecha_registro` BETWEEN '".$_GET['txtFechaDesde']."' and '".$_GET['txtFechaHasta']."' "; 
        }
		
		/* if (isset($_GET['$id_registro '])){
            $sql .= " and  registros.`id_registro`='".$id_registro."' ";
        } */
		$sql .= " order by clientes.`cedula`, registros.`id_registro` "; 
        $result = mysql_query($sql) or die(mysql_error());
        $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
        $numero = 0;
       
	 //  echo $sql;
        //echo $sql."***    ".$numero_filas;
        $data2 = array(
        );
		 while($row = mysql_fetch_array($result)) //for mayor    for($i=0; $i<$numero_filas; $i++)
        {
			$numero ++;

            //primera              
            $data[] = array(
            '#'=>$numero,
            'numero_registro'=>$row["registros_numero_registro"] ,
            'nombre'=>$row["clientes_nombre"] ,
            'fecha'=>$row["registros_fecha_registro"] ,
            'sub_total'=>$row["registros_sub_total"],
            'total'=>$row["registros_total"],
            );
        }
		
		$titles = array(
            '#' => '<b>#</b>',
            'numero_registro' => '<b>Registro</b>',
            'nombre' => '<b>Cliente</b>',
            'fecha' => '<b>Fecha</b>',
            'sub_total' => '<b>Sub_total</b>',
            'total' => '<b>Total</b>'
        );
            
             //tercera        
            $options = array(
                        'shadeCol'=>array(0.9,0.9,0.9),
                        'xOrientation'=>'center',
                        'width'=>550
                        );
        

        
        //$pdf->selectFont('Arial','B',14); // establece la fuente, le tipo ( 'B' para negrita, 'I' para itálica, '' para normal,...)
        //$pdf->ezImage('../images/encabezado_impresiones.jpg','','179','64','left','');
        //$pdf->setLineStyle(1,'square');
        //$pdf->setStrokeColor(0,0,0);
        //$pdf->line('550', '750', '40', '750');
        
        $txttit.= "";
        $pdf->ezText($txttit, 12);
        $pdf->ezTable($data, $titles, '', $options);
              
        //$pdf->ezText("<b>Hora:</b> ".date("H:i:s")."\n\n", 10);
        $pdf->ezStartPageNumbers(550, 80, 10);
        //$nombrearchivo = "reporteLibroMayor.pdf";
        $pdf->ezStream();
        //$pdf->ezOutput($nombrearchivo);
        $pdf->Output('reporteSaldoInicial.pdf', 'D');

//          $pdfcode = $pdf->ezOutput();
//          $pdfcode = str_replace('\n','\n<br>',htmlspecialchars($pdfcode));


        mysql_close();
        mysql_free_result($result);

?>

<?php

/* $sql = "SELECT
	 registros.`id_registro` AS registros_id_registro,
     registros.`numero_registro` AS registros_numero_registro,
	 registros.`id_cliente` AS registros_id_cliente,
	 registros.`fecha_registro` AS registros_fecha_registro,
	 registros.`sub_total` AS registros_sub_total,
	 registros.`id_iva` AS registros_id_iva,
	 registros.`total` AS registros_total,
     clientes.`id_cliente` AS clientes_id_cliente,
     clientes.`nombre` AS clientes_nombre,
     clientes.`apellido` AS clientes_apellido,
     clientes.`direccion` AS clientes_direccion,
     clientes.`cedula` AS clientes_cedula,
     clientes.`numero_casa` AS clientes_numero_casa,
	 detalle_registros.`id_detalle_registro` AS detalle_registros_id_detalle_registro,
	 detalle_registros.`cantidad` AS detalle_registros_cantidad,
	 detalle_registros.`v_unitario` AS detalle_registros_v_unitario,
	 detalle_registros.`v_total` AS detalle_registros_v_total
	FROM
     `clientes` clientes INNER JOIN `registros` registros ON clientes.`id_cliente` = registros.`id_cliente` 
	 INNER JOIN `detalle_registros` detalle_registros ON registros.`id_registro`= detalle_registros.`id_registro` " ;
     $sql .= " where registros.`id_empresa`='".$sesion_id_empresa."' ";
		 
		if (isset($_GET['txtFechaDesde'])){
            $sql .= " and  registros.`fecha_registro` BETWEEN '".$_GET['txtFechaDesde']."' and '".$_GET['txtFechaHasta']."' "; 
        }
		
		$sql .= " order by clientes.`cedula`, registros.`id_registro` "; 
 */
?>