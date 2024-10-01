<?php
//ob_end_clean();
//Start session
session_start();


//Include database connection details
require_once('../conexion.php');
require_once('class.ezpdf.php');
date_default_timezone_set("America/Guayaquil");
$pdf =& new Cezpdf('letter','landscape');
$pdf->selectFont('fonts/courier.afm');
$pdf->ezSetCmMargins(1,1,1,1);
//$pdf->ezSetMargins(30, 30, 50, 30);
//$pdf->ezSetY(810);
$datacreador = array (
                    'Title'=>'Balance de Comprobacion',
                    'Subject'=>'detalle del Balance de Comprobacion',
                    );

   
	$sesion_id_empresa = $_GET['Empresa_id'];//$_SESSION["sesion_id_empresa"];
	$sesion_empresa_nombre = $_GET['NombreEmpresa'];//;$_SESSION["sesion_empresa_nombre"];
	$sesion_id_periodo_contable =$_GET['Periodo'];// $_SESSION["sesion_id_periodo_contable"];
    $id_empresa_cookies = $_COOKIE["id_empresa"];

    $pdf->addInfo($datacreador);

    function tab($no)
    {// funcion para dar espacion o sangria a las cuentas
        for($x=1; $x<$no; $x++)
        $tab.=" ";
        return $tab;
    }

    $fecha_desde_principal = explode(" ", $_GET['txtFechaDesde']);
    $fecha_hasta_principal = explode(" ", $_GET['txtFechaHasta']);
    $fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
    $fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");
    $fechaCompleta = "Desde: ".($fecha_desde_principal[0])."    Hasta:".($fecha_hasta_principal[0])."";

    $pdf->ezText("<b>".utf8_decode('BALANCE DE COMPROBACIÓN')."</b>", 16,array( 'justification' => 'center' ));
    $pdf->ezText("<b>".strtoupper($sesion_empresa_nombre)."</b>", 12,array( 'justification' => 'center' ));
    //$pdf->ezText("Desde: ".strtoupper($fecha_desde_principal[0])."    Hasta: ".strtoupper($fecha_hasta_principal[0])."", 12,array( 'justification' => 'right' ));        
    $pdf->ezText($fechaCompleta, 12,array('justification'=>'center'));
    $pdf->ezText("\n", 10);
    $pdf->ezText("<b>Fecha:</b> ".date("d/m/Y"), 10,array( 'justification' => 'left' ));

    $sql = "SELECT DISTINCT
     detalle_libro_diario.`id_plan_cuenta` AS detalle_libro_diario_id_plan_cuenta,
     plan_cuentas.`codigo` AS plan_cuentas_codigo,
     plan_cuentas.`nombre` AS plan_cuentas_nombre,
     plan_cuentas.`nivel` AS plan_cuentas_nivel,
     periodo_contable.`id_periodo_contable` AS periodo_contable_id_periodo_contable,
     plan_cuentas.`id_empresa` AS plan_cuentas_id_empresa,
     libro_diario.`id_libro_diario` AS libro_diario_id_libro_diario,
     libro_diario.`fecha` AS libro_diario_fecha,
     detalle_libro_diario.`debe` AS detalle_libro_diario_debe,
     detalle_libro_diario.`haber` AS detalle_libro_diario_haber
FROM
     `periodo_contable` periodo_contable INNER JOIN `detalle_libro_diario` detalle_libro_diario ON periodo_contable.`id_periodo_contable` = detalle_libro_diario.`id_periodo_contable`
     INNER JOIN `plan_cuentas` plan_cuentas ON detalle_libro_diario.`id_plan_cuenta` = plan_cuentas.`id_plan_cuenta`
     INNER JOIN `libro_diario` libro_diario ON detalle_libro_diario.`id_libro_diario` = libro_diario.`id_libro_diario`
     AND periodo_contable.`id_periodo_contable` = libro_diario.`id_periodo_contable` ";
        
    if(isset($_GET['txtFechaDesde'])){
        $sql .= " WHERE libro_diario.`fecha` BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."' AND plan_cuentas.`id_empresa` = '".$sesion_id_empresa."' AND periodo_contable.`id_periodo_contable`= '".$sesion_id_periodo_contable."' ";
    }
    if (isset($_GET['criterio_ordenar_por']))
            $sql .= " GROUP BY plan_cuentas.`codigo` order by  ".$_GET['criterio_ordenar_por']." ".$_GET['criterio_orden']." ";
    else
            $sql .= " GROUP BY plan_cuentas.`codigo` order by plan_cuentas.`codigo` asc ";
    if (isset($_GET['criterio_mostrar']))
            $sql .= " LIMIT ".((int)$_GET['criterio_mostrar']);
        
   // echo $sql;exit;
    $result = mysql_query($sql) or die(mysql_error());
    $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
    $i =  1;
    $totalDebe = 0;
    $totalHaber = 0;
    $posVect = 1;
    $posVect2 = 1;
    $verificaCuentaAgrupacion = "";
    $verificaCuenta = "";
    $vecesQuePasaNivel1 = 1;
    while($row = mysql_fetch_array($result)){ //for mayor    for($i=0; $i<$numero_filas; $i++)
        $detalle_libro_diario_id_plan_cuenta = $row['detalle_libro_diario_id_plan_cuenta'];
        $plan_cuentas_codigo = $row['plan_cuentas_codigo'];
        $plan_cuentas_nombre = $row['plan_cuentas_nombre'];
        $plan_cuentas_nivel = $row['plan_cuentas_nivel'];
        $fecha_desde = $fecha_desde;
        $fecha_hasta = $fecha_hasta;
        
        
        //primera

         // consulta para sumar el total del debe de cada cuenta
        $cadenaDebe = array();
        $cadenaHaber = array();
        $sql2 = "SELECT
        sum(detalle_libro_diario.`debe`) AS sumaDebe,
             detalle_libro_diario.`id_plan_cuenta` AS detalle_libro_diario_id_plan_cuenta,
             plan_cuentas.`codigo` AS plan_cuentas_codigo,
             plan_cuentas.`nombre` AS plan_cuentas_nombre,
             periodo_contable.`id_periodo_contable` AS periodo_contable_id_periodo_contable,
             plan_cuentas.`id_empresa` AS plan_cuentas_id_empresa,
             libro_diario.`id_libro_diario` AS libro_diario_id_libro_diario,
             libro_diario.`fecha` AS libro_diario_fecha,
             detalle_libro_diario.`debe` AS detalle_libro_diario_debe,
             detalle_libro_diario.`haber` AS detalle_libro_diario_haber
        FROM
             `periodo_contable` periodo_contable INNER JOIN `detalle_libro_diario` detalle_libro_diario ON periodo_contable.`id_periodo_contable` = detalle_libro_diario.`id_periodo_contable`
             INNER JOIN `plan_cuentas` plan_cuentas ON detalle_libro_diario.`id_plan_cuenta` = plan_cuentas.`id_plan_cuenta`
             INNER JOIN `libro_diario` libro_diario ON detalle_libro_diario.`id_libro_diario` = libro_diario.`id_libro_diario`
             AND periodo_contable.`id_periodo_contable` = libro_diario.`id_periodo_contable`
     
             WHERE libro_diario.`fecha` BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."' AND plan_cuentas.`id_empresa` = '".$sesion_id_empresa."' AND detalle_libro_diario.`id_plan_cuenta` ='".$detalle_libro_diario_id_plan_cuenta."' AND periodo_contable.`id_periodo_contable`= '".$sesion_id_periodo_contable."' GROUP BY periodo_contable.`id_periodo_contable` ;";

        $resp2 = mysql_query($sql2);
        while($row2=mysql_fetch_array($resp2)){
            $cadenaDebe[$i] = $row2["sumaDebe"];
        }
             // consulta para sumar el total del haber de cada cuenta
        $sql3 = "SELECT
        sum(detalle_libro_diario.`haber`) AS sumaHaber,
             detalle_libro_diario.`id_plan_cuenta` AS detalle_libro_diario_id_plan_cuenta,
             plan_cuentas.`codigo` AS plan_cuentas_codigo,
             plan_cuentas.`nombre` AS plan_cuentas_nombre,
             periodo_contable.`id_periodo_contable` AS periodo_contable_id_periodo_contable,
             plan_cuentas.`id_empresa` AS plan_cuentas_id_empresa,
             libro_diario.`id_libro_diario` AS libro_diario_id_libro_diario,
             libro_diario.`fecha` AS libro_diario_fecha,
             detalle_libro_diario.`debe` AS detalle_libro_diario_debe,
             detalle_libro_diario.`haber` AS detalle_libro_diario_haber
        FROM
             `periodo_contable` periodo_contable INNER JOIN `detalle_libro_diario` detalle_libro_diario ON periodo_contable.`id_periodo_contable` = detalle_libro_diario.`id_periodo_contable`
             INNER JOIN `plan_cuentas` plan_cuentas ON detalle_libro_diario.`id_plan_cuenta` = plan_cuentas.`id_plan_cuenta`
             INNER JOIN `libro_diario` libro_diario ON detalle_libro_diario.`id_libro_diario` = libro_diario.`id_libro_diario`
             AND periodo_contable.`id_periodo_contable` = libro_diario.`id_periodo_contable`
             WHERE libro_diario.`fecha` BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."' AND plan_cuentas.`id_empresa` = '".$sesion_id_empresa."' AND detalle_libro_diario.`id_plan_cuenta` ='".$detalle_libro_diario_id_plan_cuenta."' AND periodo_contable.`id_periodo_contable`= '".$sesion_id_periodo_contable."' GROUP BY  periodo_contable.`id_periodo_contable`;";
        $resp3 = mysql_query($sql3);
        while($row3=mysql_fetch_array($resp3)){
            $cadenaHaber [$i]= $row3["sumaHaber"];
        }

        $saldo =  $cadenaDebe[$i] - $cadenaHaber[$i];
		
        //************************************************************************

        //*************************  IMPRIME TODAS LAS CUENTAS   ********************

            if($numero_filas == '0'){

            }else{
                
                $primeros_caracteres = substr($plan_cuentas_codigo, 0, 1);  // devuelve los primeros caracteres en este caso es 1
                // saca todos los niveles que tiene la cuenta ejemplo cuenta 6 sacaria nivel 1,2,5
                $sqlNiveles = "SELECT * FROM plan_cuentas WHERE codigo like '".$primeros_caracteres."%' AND id_empresa='".$sesion_id_empresa."' AND tipo='Agrupación' GROUP BY nivel order by nivel asc;";
                $resultN = mysql_query($sqlNiveles);
                $posVec = 0;// esta es el tamaño del vector
                while($rowN=mysql_fetch_array($resultN)){
                    $posVec++;
                    // a los niveles de la cuentas lo guardo en un vector
                    $cadenaNiveles[$posVec]= $rowN["nivel"];
                    $cadenaNivelesNombre[$posVec]= $rowN["nombre"];

                }
                //echo $posVec."  consult 1: ".$sqlNiveles;


                // Inicia el bucle para imprimir las cuentas de agrupacion
                $a=1;
                
                while($a <= $posVec ){ // recorre de la cuenta, con nivel 1 hasta el utimo nivel. Va de mayor a menor
                    
                    if($cadenaNiveles[$a] == 1){
                        

                        if (in_array($plan_cuentas_codigo, $verificaCuentaAgrupacion)) {// para que no imprima cuentas de agrupacion repetidas                        
                            //echo " existe";
                            
                        }else {
                            //echo " no existe";
                            $verificaCuentaAgrupacion[$posVect2] = $plan_cuentas_codigo;
                            $posVect2++;

                            //consulta la primera cuenta de agrupacion
                            $sqlPrimerCuentaAgrupacion = "SELECT * FROM plan_cuentas WHERE codigo like '".$primeros_caracteres."%' AND id_empresa='".$sesion_id_empresa."' AND tipo like 'Agrupa%' AND nivel='".$cadenaNiveles[$a]."' order by codigo asc;";

                            //echo $cadenaNiveles[$a]."      consult 2: ".$sqlPrimerCuentaAgrupacion;

                            $resultPCA = mysql_query($sqlPrimerCuentaAgrupacion);

                            $numero_filasPCA = mysql_num_rows($resultPCA); // obtenemos el número de filas

                            while($rowPCA=mysql_fetch_array($resultPCA)){
                                
                                if (in_array($rowPCA["codigo"], $verificaCuenta)) {// para que no imprima cuentas de agrupacion repetidas
                                    // existe
                                }else{
                                    $verificaCuenta[$vecesQuePasaNivel1] = $rowPCA["codigo"];
                                    $data1[] = array(
                                        'plan_cuentas_codigo'=>utf8_decode($rowPCA["codigo"]),
                                        'plan_cuentas_nombre'=>utf8_decode($rowPCA["nombre"]),
                                        'saldo_anterior'=>'',
                                        'debito'=>'',
                                        'credito'=>'',
                                        'saldo'=>''
                                    );
                                    $vecesQuePasaNivel1++;
                                }
                            }
                        }
                        
                    }else{
                        //consulta las cuenta de agrupacion del segundo nivel en adelante
                        $mi_variable=substr($plan_cuentas_codigo,0,$cadenaNiveles[$a]);// coje los caratecteres principales de la cuenta de agrupacion dependiendo del nivel
                        $sqlCuentasAgrupacion = "SELECT * FROM plan_cuentas WHERE codigo like '".$mi_variable."%' AND id_empresa='".$sesion_id_empresa."' AND tipo='Agrupación' AND nivel='".$cadenaNiveles[$a]."' order by codigo asc;";
                        //echo $cadenaNiveles[$a]."      consult 3: ".$sqlCuentasAgrupacion;
                        $resultCA = mysql_query($sqlCuentasAgrupacion);
                        while($rowCA=mysql_fetch_array($resultCA)){
                            

                            if (in_array($rowCA["codigo"], $vecCuentasAgrupacionPasan)) {// para que no imprima cuentas de agrupacion repetidas
                                //echo "       Existe cuenta         ";
                                
                            }else {
                                $vecCuentasAgrupacionPasan[$posVect] = $rowCA["codigo"];
                                $data1[] = array(
                                    'plan_cuentas_codigo'=>utf8_decode($rowCA["codigo"]),
                                    'plan_cuentas_nombre'=>tab($rowCA["nivel"]).utf8_decode($rowCA["nombre"]),
                                    'saldo_anterior'=>'',
                                    'debito'=>'',
                                    'credito'=>'',
                                    'saldo'=>''
                                );
                            
                                $posVect++;
                            }

                        }
                    }
                    //$arr1 = str_split($plan_cuentas_codigo);
                    $a++;

                }
                //// fin del bucle para imprimir las cuentas de agrupacion

                //*************  IMPRIME LOS VALORES DE LAS CUENTAS DE MOVIMIENTO ****************/

                 $data1[] = array(
                    //'numero'=>$i,
                    'plan_cuentas_codigo'=>$plan_cuentas_codigo,
                    'plan_cuentas_nombre'=>tab($plan_cuentas_nivel).utf8_decode($plan_cuentas_nombre),
                    'saldo_anterior'=>'',
                    'debito'=>number_format($cadenaDebe[$i], 2, '.', ' '),
                    'credito'=>number_format($cadenaHaber[$i], 2, '.', ' '),
                    'saldo'=>number_format($saldo, 2, '.', ' ')
                );
                 

            }//--- caso contrario

        //************************************************************************
        /*
       
        */

        $titles1 = array(
        //'numero' => '<b>Id</b>',
        'plan_cuentas_codigo' => '<b>'.utf8_decode('CÓDIGO').'</b>',
        'plan_cuentas_nombre' => '<b>'.utf8_decode('DESCRIPCIÓN').'</b>',
        'saldo_anterior' => '<b>SALDO ANTERIOR</b>',
        'debito' => '<b>DEBITO</b>',
        'credito' => '<b>CREDITO</b>',
        'saldo' => '<b>SALDO</b>',
        );

                
        // calculos para sacar los totales finales
        $totalDebe = $totalDebe + $cadenaDebe[$i];
        $totalHaber = $totalHaber + $cadenaHaber[$i];
        $saldototal = $saldototal + $saldo;

        $i++;         
        
    }
    $titles2 = array(
        'vacio '=>' ',        
        'plan_cuentas_nombre'=>'TOTALES',
        'saldo_anterior'=>number_format('',2,'.',''),
        'debito'=>number_format($totalDebe,2,'.',''),
        'credito'=>number_format($totalHaber,2,'.',''),
        'saldo'=>number_format($saldototal,2,'.','') //$saldoAcreedor
    );
    
    $data2 = array(

    );
    
    $options1 = array(
        'shadeCol'=>array(0.9,0.9,0.9),
        'xOrientation'=>'center',
        'width'=>720,
        'cols'=>array(
                'plan_cuentas_codigo'=>array('justification'=>'left','width'=>90),
                'plan_cuentas_nombre'=>array('justification'=>'left','width'=>180),
                'saldo_anterior'=>array('justification'=>'right','width'=>90),
                'debito'=>array('justification'=>'right','width'=>120),
                'credito'=>array('justification'=>'right','width'=>120),
                'saldo'=>array('justification'=>'right','width'=>120)
             )
    );

    $options2 = array(
        'shadeCol'=>array(0.9,0.9,0.9),
        'xOrientation'=>'center',
        'width'=>720,
        'cols'=>array(
                'plan_cuentas_nombre'=>array('justification'=>'right','width'=>90),            
                'plan_cuentas_nombre'=>array('justification'=>'right','width'=>180),
                'saldo_anterior'=>array('justification'=>'right','width'=>90),
                'debito'=>array('justification'=>'right','width'=>120),
                'credito'=>array('justification'=>'right','width'=>120),
                'saldo'=>array('justification'=>'right','width'=>120)
             )
    );
    //$fechadesde = covertirFecha($fecha_desde);
    //$fechahasta = covertirFecha($fecha_hasta);
//        $fechadesde = explode(" ", $fecha_desde);
//        $fechahasta = explode(" ", $fecha_hasta);
    //$pdf->ezImage('../archivos/Desert.jpg','','179','64','left','');
    //$pdf->setLineStyle(1,'square');
    //$pdf->setStrokeColor(0,0,0);
    //$pdf->line('550', '750', '40', '750');
    
    

    $pdf->ezText($txttit, 12);
    $pdf->ezTable($data1, $titles1, '', $options1);
    $pdf->ezText($txttit, 2);
    $pdf->ezTable($data2, $titles2, '', $options2);
    
    //$pdf->ezText("\n\n\n", 10);
    //$pdf->ezText("<b>Contador                     Auditor                      Gerente</b> ", 10,array( 'justification' => 'center' ));

    //$pdf->ezText("<b>Hora:</b> ".date("H:i:s")."\n\n", 10);
    $pdf->ezStartPageNumbers(550, 80, 10);
    //$nombrearchivo = "reporteBalanceComprobacion.pdf";
    $pdf->ezStream();
    //$pdf->ezOutput($nombrearchivo);
    $pdf->Output('reporteBalanceComprobacion.pdf', 'D');

//          $pdfcode = $pdf->ezOutput();
//          $pdfcode = str_replace('\n','\n<br>',htmlspecialchars($pdfcode));

    mysql_close();
    mysql_free_result($result);

    function covertirFecha($fecha){
        $cadena=split("-",$fecha);// elimina el /
        $dia1 = $cadena[2];// guarda en variable
        $dia=floatval($dia1);// elima el cero
        $mes1 = $cadena[1];// guarda en variable
        $mes=floatval($mes1);// elima el cero
        $ano = $cadena[0];// guarda en variable
        $mesletra = "";
        switch($mes)
        {
            case 1:$mesletra="Enero";break;
            case 2:$mesletra="Febrero";break;
            case 3:$mesletra="Marzo";break;
            case 4:$mesletra="Abril";break;
            case 5:$mesletra="Mayo";break;
            case 6:$mesletra="Junio";break;
            case 7:$mesletra="Julio";break;
            case 8:$mesletra="Agosto";break;
            case 9:$mesletra="Septiembre";break;
            case 10:$mesletra="Octubre";break;
            case 11:$mesletra="Noviembre";break;
            case 12:$mesletra="Diciembre";break;
        }
        $fechanueva = $dia." de ".$mesletra." del ".$ano;
        return $fechanueva;
    }

?>