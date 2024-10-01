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
                    'Title'=>'Proveedor',
                    'Subject'=>'Lista del Proveedor',
                    'Author'=>'25 de junio',
                    'Producer'=>'Andres Anrrango'
                    );
$pdf->addInfo($datacreador);

$id_proveedores = $_GET['id_proveedores'];
$sql = "SELECT
     ciudades.`id_ciudad` AS ciudades_id_ciudad,
     ciudades.`ciudad` AS ciudades_ciudad,
     ciudades.`id_provincia` AS ciudades_id_provincia,
     paises.`id_pais` AS paises_id_pais,
     paises.`pais` AS paises_pais,
     provincias.`id_provincia` AS provincias_id_provincia,
     provincias.`provincia` AS provincias_provincia,
     provincias.`id_pais` AS provincias_id_pais,
     proveedores.`id_proveedor` AS proveedores_id_proveedor,
     proveedores.`nombre_comercial` AS proveedores_nombre_comercial,
     proveedores.`nombre` AS proveedores_nombre,
     proveedores.`direccion` AS proveedores_direccion,
     proveedores.`ruc` AS proveedores_ruc,
     proveedores.`telefono` AS proveedores_telefono,
     proveedores.`movil` AS proveedores_movil,
     proveedores.`fax` AS proveedores_fax,
     proveedores.`email` AS proveedores_email,
     proveedores.`web` AS proveedores_web,
     proveedores.`observaciones` AS proveedores_observaciones,
     proveedores.`id_ciudad` AS proveedores_id_ciudad
FROM
     `paises` paises INNER JOIN `provincias` provincias ON paises.`id_pais` = provincias.`id_pais`
     INNER JOIN `ciudades` ciudades ON provincias.`id_provincia` = ciudades.`id_provincia`
     INNER JOIN `proveedores` proveedores ON ciudades.`id_ciudad` = proveedores.`id_ciudad`
     WHERE proveedores.`id_proveedor` = '".$id_proveedores."'; ";
    $result = mysql_query($sql) or die(mysql_error());
    $aux1=0;
    while($row = mysql_fetch_assoc($result)) {
        $aux1++;
        $id = $row["proveedores_id_proveedor"];
        $nom_comercial = $row["proveedores_nombre_comercial"];
        $nombre = $row["proveedores_nombre"];
        $direccion = $row["proveedores_direccion"];
        $ruc = $row["proveedores_ruc"];
        $telefono = $row["proveedores_telefono"];
        $movil = $row["proveedores_movil"];
        $fax = $row["proveedores_fax"];
        $email = $row["proveedores_email"];
        $web = $row["proveedores_web"];
        $ciudad = $row["ciudades_ciudad"];
        $provincia = $row["provincias_provincia"];
        $pais = $row["paises_pais"];
        $observa = $row["proveedores_observaciones"];
    }

    
    

    $options = array(
                    'shadeCol'=>array(0.9,0.9,0.9),
                    'xOrientation'=>'center',
                    'width'=>500
                    
                    );


    $pdf->ezImage('../images/encabezado_impresiones.jpg','','179','64','left','');
    $pdf->setLineStyle(1,'square');
    $pdf->setStrokeColor(0,0,0);
    $pdf->line('550', '750', '40', '750');
    $pdf->ezText("\n<b>INFORMACION</b>", 18,array( 'justification' => 'center' ));
    $pdf->ezText("\n\n<b>Datos personales</b>\n", 13,array( 'justification' => 'left' ));

    $txttit.= "\n<b>ID: </b>".$id."\n";
    $txttit.= "\n<b>NOMBRE: </b>".$nom_comercial."\n";
    $txttit.= "\n<b>REPRESENTANTE: </b>".$nombre."\n";
    $txttit.= "\n<b>RUC: </b>".$ruc."\n";
    $txttit.= "\n<b>DIRECCION: </b>".$direccion."\n";
    $txttit.= "\n<b>TELEFONO: </b>".$telefono."\n";
    $txttit.= "\n<b>MOVIL: </b>".$movil."\n";
    $txttit.= "\n<b>PAIS: </b>".$pais."\n";
    $txttit.= "\n<b>PROVINCIA: </b>".$provincia."\n";
    $txttit.= "\n<b>CIUDAD: </b>".$ciudad."\n";
    $txttit.= "\n<b>FAX: </b>".$fax."\n";
    $txttit.= "\n<b>E-MAIL: </b>".$email."\n";       
    $txttit.= "\n<b>WEB: </b>".$web."\n";
    $txttit.= "\n<b>OBSERVACIONES: </b>".$observa."\n\n\n";

    $pdf->ezText($txttit, 10);
    $pdf->ezTable($data, $titles, '', $options);
    
    $pdf->ezText("\n\n\n", 10);
    $pdf->ezText("<b>Fecha:</b> ".date("d/m/Y"), 10);
    $pdf->ezText("<b>Hora:</b> ".date("H:i:s")."\n\n", 10);
    $pdf->ezStartPageNumbers(550, 80, 10);
    $nombrearchivo = "reporteEmpleados.pdf";
    $pdf->ezStream();
    
    
    $pdf->ezOutput($nombrearchivo);

//          $pdfcode = $pdf->ezOutput();
//          $pdfcode = str_replace('\n','\n<br>',htmlspecialchars($pdfcode));


    mysql_close();
    mysql_free_result($result);

?>

