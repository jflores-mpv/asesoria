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
                    'Title'=>'Empleado',
                    'Subject'=>'Lista de Empelados',
                    'Author'=>'25 de junio',
                    'Producer'=>'Andres Anrrango'
                    );
$pdf->addInfo($datacreador);

$id_empleado = $_GET['id_empleado'];
$sql = "SELECT * FROM empleados WHERE empleados.`id_empleado` = '".$id_empleado."'; ";
$result = mysql_query($sql) or die(mysql_error());

    while($row = mysql_fetch_assoc($result)) {        
        $cedula = $row["cedula"];
        $nombre = $row["nombre"];
        $apellido = $row["apellido"];
        $direccion = $row["direccion"];
        $telefono = $row["telefono"];
        $movil = $row["movil"];
        $fecha_nacimiento = $row["fecha_nacimiento"];
        $email = $row["email"];        
        $tipoE = $row["tipo"];
        $estadoE = $row["estado"];
        $posicion = $row["posicion"];
        $id_ciudad = $row["id_ciudad"];
        $fecha_ingreso = $row["fecha_ingreso"];
        $numero_cargas = $row["numero_cargas"];
        $estado_civil = $row["estado_civil"];
        $cuenta_bancaria = $row["cuenta_bancaria"];
        $fondos_reserva = $row["fondos_reserva"];
        $decimos = $row["decimos"];
    }

$sql2 = "SELECT * FROM ciudades WHERE ciudades.`id_ciudad` = '".$id_ciudad."'; ";
$result2 = mysql_query($sql2) or die(mysql_error());
while($row2 = mysql_fetch_assoc($result2)) {
        $id_ciudad = $row2["id_ciudad"];
        $ciudad = $row2["ciudad"];
        $id_provincia = $row2["id_provincia"];
    }

$sql3 = "SELECT * FROM provincias WHERE provincias.`id_provincia` = '".$id_provincia."'; ";
$result3 = mysql_query($sql3) or die(mysql_error());
while($row3 = mysql_fetch_assoc($result3)) {
        $id_provincia = $row3["id_provincia"];
        $provincia = $row3["provincia"];
        $id_pais = $row3["id_pais"];
    }

$sql4 = "SELECT * FROM paises WHERE paises.`id_pais` = '".$id_pais."'; ";
$result4 = mysql_query($sql4) or die(mysql_error());
while($row4 = mysql_fetch_assoc($result4)) {
        $id_pais = $row4["id_pais"];
        $pais = $row4["pais"];
    }


$sql5 = "SELECT * FROM asignacion_empleados WHERE asignacion_empleados.`id_empleado` = '".$id_empleado."'; ";
$result5 = mysql_query($sql5) or die(mysql_error());
while($row5 = mysql_fetch_assoc($result5)){
    $id_asignacion_empleado = $row5["id_asignacion_empleado"];
    $id_empleado = $row5["id_empleado"];
    $fecha_inicio = $row5["fecha_inicio"];
    $fecha_fin = $row5["fecha_fin"];
    $id_cargo = $row5["id_cargo"];
    $tipo_novedad = $row5["tipo_novedad"];
    $descripcion = $row5["descripcion"];
    $estadoA = $row5["estado"];
    $fecha_fondos_reserva = $row5["fecha_fondos_reserva"];
}

$sql6 = "SELECT * FROM cargos WHERE cargos.`id_cargo` = '".$id_cargo."'; ";
$result6 = mysql_query($sql6) or die(mysql_error());
while($row6 = mysql_fetch_assoc($result6)) {
        $id_cargo = $row6["id_cargo"];
        $nombre_cargo = $row6["nombre_cargo"];
        $sueldo = $row6["sueldo"];
        $id_departamento = $row6["id_departamento"];
        $estadoC = $row6["estado"];
        $id_empleado = $row6["id_empleado"];
}


$sql7 = "SELECT * FROM departamentos WHERE departamentos.`id_departamento` = '".$id_departamento."'; ";
$result7 = mysql_query($sql7) or die(mysql_error());
while($row7 = mysql_fetch_assoc($result7)) {
        $id_departamento = $row7["id_departamento"];
        $nombre_departamento = $row7["nombre_departamento"];
        $descripcion = $row7["descripcion"];
}

$sql8 = "SELECT * FROM usuarios WHERE usuarios.`id_empleado` = '".$id_empleado."'; ";
$result8 = mysql_query($sql8) or die(mysql_error());
while($row8 = mysql_fetch_assoc($result8)) {
        $login = $row8["login"];
        $tipo = $row8["tipo"];
        $estadoU = $row8["estado"];
        $fecha_registroU = $row8["fecha_registro"];
        $permisos = $row8["permisos"];
}


    $data[] = array('1'=>$nombre_departamento, '2'=>$nombre_cargo, '3'=>$sueldo, '4'=>$fecha_inicio, '5'=>$fecha_fin, '6'=>$tipo_novedad);


    $titles = array('1'=>'<b>Departamento</b>', '2'=>'<b>Cargo</b>', '3'=>'<b>Sueldo</b>', '4'=>'<b>Fecha Inicio</b>', '5'=>'<b>Fecha Fin</b>', '6'=>'<b>Tipo Novedad</b>');
    

    $options = array(
                    'shadeCol'=>array(0.9,0.9,0.9),
                    'xOrientation'=>'center',
                    'width'=>500
                    
                    );


    //$pdf->ezImage('../images/encabezado_impresiones.jpg','','179','64','left','');
    //$pdf->setLineStyle(1,'square');
    //$pdf->setStrokeColor(0,0,0);
    //$pdf->line('550', '750', '40', '750');
    $pdf->ezText("\n<b>INFORMACION</b>", 18,array( 'justification' => 'center' ));
    $pdf->ezText("\n\n<b>Datos personales</b>\n", 12,array( 'justification' => 'left' ));    
   
    $txttit.= "\n<b>NOMBRE: </b>".$nombre."\n";
    $txttit.= "\n<b>APELLIDO: </b>".$apellido."\n";
    $txttit.= "\n<b>CEDULA: </b>".$cedula."\n";
    $txttit.= "\n<b>DIRECCION: </b>".$direccion."\n";
    $txttit.= "\n<b>TELEFONO: </b>".$telefono."\n";
    $txttit.= "\n<b>MOVIL: </b>".$movil."\n";
    $txttit.= "\n<b>PAIS: </b>".$pais."\n";
    $txttit.= "\n<b>PROVINCIA: </b>".$provincia."\n";
    $txttit.= "\n<b>CIUDAD: </b>".$ciudad."\n";
    $txttit.= "\n<b>FECHA NACIMIENTO: </b>".$fecha_nacimiento."\n";
    $txttit.= "\n<b>E-MAIL: </b>".$email."\n";    
    $txttit.= "\n<b>NUMERO CARGAS: </b>".$numero_cargas."\n";
    $txttit.= "\n<b>FECHA REGISTRO: </b>".$fecha_ingreso."\n";
    $txttit.= "\n<b>ESTADO CIVIL: </b>".$estado_civil."\n\n\n";

    $txttit.= "\n\n<b>Historial</b>\n\n";
    
    $txttit2.= "\n\n\n\n<b>Datos administrativos</b>\n\n";
    $txttit2.= "\n<b>USUARIO: </b>".$login."\n";
    $txttit2.= "\n<b>FECHA REGISTRO: </b>".$fecha_registroU."\n";
    $txttit2.= "\n<b>PERMISOS: </b>".$permisos."\n";
    $txttit2.= "\n<b>ESTADO: </b>".$estadoU."\n";    
    $txttit2.= "\n<b>CUENTA BANCARIA: </b>".$cuenta_bancaria."\n";
    $txttit2.= "\n<b>FONDOS RESERVA: </b>".$fondos_reserva."\n";
    $txttit2.= "\n<b>DECIMOS: </b>".$decimos."\n";

    $pdf->ezText($txttit, 10);
    $pdf->ezTable($data, $titles, '', $options);
    $pdf->ezText($txttit2, 10);
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

