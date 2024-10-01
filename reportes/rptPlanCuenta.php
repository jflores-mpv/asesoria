<?php
//ob_end_clean();
//Start session
session_start();

//Include database connection details
require_once('../conexion.php');
require_once('class.ezpdf.php');
$pdf =& new Cezpdf('a4');
$pdf->selectFont('fonts/Courier.afm');
$pdf->ezSetCmMargins(1,1,1.5,1.5);

$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];


$sql = "select * from plan_cuentas";

if (isset($_GET['txtCuenta'])) {
    $sql .= " where (nombre like '%".$_GET['txtCuenta']."%' or codigo like '".$_GET['txtCuenta']."%') AND id_empresa='".$sesion_id_empresa."' "; }
if (isset($_GET['cmbOrdenar'])) {
    $sql .= " order by ".$_GET['cmbOrdenar']." ".$_GET['cmbOrden']." ";  }

if (isset($_GET['cmbRegistros'])) {
	$sql .=" LIMIT ". $_GET['cmbRegistros'].""; }

        $result = mysql_query($sql) or die(mysql_error());
//echo $sql;
        $ixx = 0;
        while($row = mysql_fetch_assoc($result)) {
                $ixx = $ixx+1;
                $codigo = $row["codigo"];
                $nivel = $row['nivel'];
                $nombre  = $row['nombre'];
                $clasificacion = $row["clasificacion"];
                $tipo = $row["tipo"];
                
                $data[] = array(
                    'codigo'=>$codigo,
                    'nombre'=>tab($nivel).utf8_decode($nombre),
                    'clasificacion'=>utf8_decode($clasificacion),
                    'tipo'=>utf8_decode($tipo)
                    );
        }
        $titles = array(
                        'codigo'=>'<b>'.utf8_decode('Código').'</b>',
                        'nombre'=>'<b>Nombre</b>',
                        //'clasificacion'=>'<b>'.utf8_decode('Clasificación').'</b>',
                        //'tipo'=>'<b>Tipo</b>',

                        );
        $options = array(
                        'shadeCol'=>array(0.9,0.9,0.9),
                        'xOrientation'=>'center',
                        'width'=>500
                        );

        //$pdf->ezImage('../images/encabezado_impresiones.jpg','','179','64','left','');
        //$pdf->setLineStyle(1,'square');
        //$pdf->setStrokeColor(0,0,0);
        //$pdf->line('550', '750', '40', '750');
        
        $pdf->ezText("<b>PLAN DE CUENTAS</b>", 18,array( 'justification' => 'center' ));
        $pdf->ezText("<b>".$sesion_empresa_nombre."</b>", 15,array( 'justification' => 'center' ));
        
     
        $txttit.= "";
        $pdf->ezText($txttit, 12);
        $pdf->ezTable($data, $titles, '', $options);
        $pdf->ezText("\n\n\n", 10);
        $pdf->ezText("<b>Fecha:</b> ".date("d/m/Y"), 10);
        $pdf->ezText("<b>Hora:</b> ".date("H:i:s")."\n\n", 10);
        $pdf->ezStartPageNumbers(550, 80, 10);
        $nombrearchivo = "reportePlanCuentas.pdf";
        $pdf->ezStream();
        $pdf->output($nombrearchivo);

//          $pdfcode = $pdf->ezOutput();
//          $pdfcode = str_replace('\n','\n<br>',htmlspecialchars($pdfcode));
      

        mysql_close();
        mysql_free_result($result);

        function tab($no)
        {
            for($x=1; $x<$no; $x++)
            $tab.=" ";
            return $tab;
        }
    
?>

