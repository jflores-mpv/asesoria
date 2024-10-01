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
        'Title'=>'Estado de Resultados',
        'Subject'=>'Detalle del Estado de Resultados',
        'Author'=>'Macarena',
        'Producer'=>'Macarena'
);

$pdf->addInfo($datacreador);

function tab($no)
{// funcion para dar espacion o sangria a las cuentas
    for($x=1; $x<$no; $x++)
    $tab.=" ";
    return $tab;
}

$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
$sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_id_usuario =  $_SESSION["sesion_id_usuario"];
$id_empresa_cookies = $_COOKIE["id_empresa"];

$fecha_desde_principal = explode(" ", $_GET['txtFechaDesde']);
$fecha_hasta_principal = explode(" ", $_GET['txtFechaHasta']);
$fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
$fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");

$pdf->ezText("<b>".strtoupper($sesion_empresa_nombre)."</b>", 12,array( 'justification' => 'center' ));
$pdf->ezText("<b>".utf8_decode('ESTADO DE RESULTADOS INTEGRAL')."</b>", 16,array( 'justification' => 'center' ));



$pdf->ezText("<b>Desde: ".strtoupper($fecha_desde_principal[0])."    Hasta: ".strtoupper($fecha_hasta_principal[0])."</b>", 12,array( 'justification' => 'center' ));
$pdf->ezText("\n", 10);
$pdf->ezText("<b>Fecha:</b> ".date("d/m/Y"), 10,array( 'justification' => 'left' ));



    //************** PARAMETROS PARA EL BALANCE DE ESTADOS DE RESULTADOS *****************//
                    //************** FOR MAYOR *****************//

    $sqlmayor = "SELECT
    parametros_estados_resultados.`id_parametros_estado_resultado` AS parametros_estados_resultados_id_parametros_estado_resultado,
    parametros_estados_resultados.`codigo_cuenta` AS parametros_estados_resultados_codigo_cuenta,
    parametros_estados_resultados.`nombre_cuenta` AS parametros_estados_resultados_nombre_cuenta
FROM
    `parametros_estados_resultados` parametros_estados_resultados;";
    //echo $sqlmayor;
    
    //   echo " / ".$sqlmayor;
    //     exit();
    
    $respm = mysql_query($sqlmayor);
	  $totaldebe = 0;  $totalhaber = 0;
    while($rowm=mysql_fetch_array($respm)){//********************************* INICO BUCLE PRINCIPAL ********************************************

         $vecesQuePasaNivel1 = 1;
         $parametros_codigo = $rowm["parametros_estados_resultados_codigo_cuenta"];
         $parametros_nombre = $rowm["parametros_estados_resultados_nombre_cuenta"];

         // CONSULTA PARA EL ESTADO DE RESULTADOS  4, 5, 6,

	 $sqli = "SELECT
         detalle_libro_diario.`id_detalle_libro_diario` AS detalle_libro_diario_id_detalle_libro_diario,
         detalle_libro_diario.`id_plan_cuenta` AS detalle_libro_diario_id_plan_cuenta,
         plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_plan_cuenta,
         plan_cuentas.`codigo` AS plan_cuentas_codigo,
         plan_cuentas.`tipo` AS plan_cuentas_tipo,
         plan_cuentas.`nombre` AS plan_cuentas_nombre,
         plan_cuentas.`nivel` AS plan_cuentas_nivel,
         periodo_contable.`id_periodo_contable` AS periodo_contable_id_periodo_contable,
         periodo_contable.`fecha_desde` AS periodo_contable_fecha_desde,
         periodo_contable.`fecha_hasta` AS periodo_contable_fecha_hasta,
         periodo_contable.`estado` AS periodo_contable_estado,
         periodo_contable.`ingresos` AS periodo_contable_ingresos,
         periodo_contable.`gastos` AS periodo_contable_gastos,
         libro_diario.`id_libro_diario` AS libro_diario_id_libro_diario,
         libro_diario.`id_periodo_contable` AS libro_diario_id_periodo_contable,
         libro_diario.`numero_asiento` AS libro_diario_numero_asiento,
         libro_diario.`fecha` AS libro_diario_fecha,
         libro_diario.`total_debe` AS libro_diario_total_debe,
         libro_diario.`total_haber` AS libro_diario_total_haber,
         libro_diario.`descripcion` AS libro_diario_descripcion,
         libro_diario.`numero_comprobante` AS libro_diario_numero_comprobante,
         plan_cuentas.`id_empresa` AS plan_cuentas_id_empresa
    FROM
         `detalle_libro_diario` detalle_libro_diario INNER JOIN `plan_cuentas` plan_cuentas ON detalle_libro_diario.`id_plan_cuenta` = plan_cuentas.`id_plan_cuenta`
         INNER JOIN `periodo_contable` periodo_contable ON detalle_libro_diario.`id_periodo_contable` = periodo_contable.`id_periodo_contable`
         INNER JOIN `libro_diario` libro_diario ON detalle_libro_diario.`id_libro_diario` = libro_diario.`id_libro_diario`
         AND periodo_contable.`id_periodo_contable` = libro_diario.`id_periodo_contable`

        WHERE
             plan_cuentas.`id_empresa` = '".$sesion_id_empresa."' AND plan_cuentas.`codigo` like '".$parametros_codigo."%' AND 
             detalle_libro_diario.`id_periodo_contable`='".$sesion_id_periodo_contable."' AND libro_diario.`fecha` BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."'
        GROUP BY plan_cuentas.`codigo`
        ORDER BY
             plan_cuentas.`codigo` ASC;";

    //   echo $sqli;

        $respi = mysql_query($sqli);
        $numero_filas = mysql_num_rows($respi); // obtenemos el número de filas
        //echo "          numero filas:  ".$numero_filas;

        $posVect = 1;
        $vecCuentasAgrupacionPasan = array();
		$total_saldo =0;
		
        //********************************** SACA LAS CUENTAS DEL LIBRO DIARIO ***********************************//

        while($rowi=mysql_fetch_array($respi)){ // ******************************* INICIO BUCLE SECUNDARIO ********************************************
	
            $plan_cuentas_nivel = $rowi["plan_cuentas_nivel"];
            $plan_cuentas_codigo = $rowi["plan_cuentas_codigo"];
            $plan_cuentas_nombre = $rowi["plan_cuentas_nombre"];
            $id_plan_cuenta = $rowi["plan_cuentas_id_plan_cuenta"];

            $cadenaDebe = "";
            $cadenaHaber = "";


            // EN INGRESOS, CUANDO EL VALOR SALE EN DEBE EL TOTAL SE LO PONE EN NEGATICVO
            $sql2 = "SELECT sum(debe) AS sumaDebe FROM
            `detalle_libro_diario` detalle_libro_diario 
            INNER JOIN `plan_cuentas` plan_cuentas ON detalle_libro_diario.`id_plan_cuenta` = plan_cuentas.`id_plan_cuenta`
            INNER JOIN `libro_diario` libro_diario ON detalle_libro_diario.`id_libro_diario` = libro_diario.`id_libro_diario`
            WHERE plan_cuentas.id_plan_cuenta ='".$id_plan_cuenta."' and plan_cuentas.`codigo` like '".$parametros_codigo."%' 
            AND detalle_libro_diario.`id_periodo_contable`='".$sesion_id_periodo_contable."'
            AND libro_diario.`fecha` BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."';";
            // echo " ******* ".$sql2;
            $resp2 = mysql_query($sql2);
            while($row=mysql_fetch_array($resp2)){
                  $cadenaDebe = $row["sumaDebe"];
            }

            // EN EGRESOS, CUANDO EL VALOR SALE EN HABER EL TOTAL SE LO PONE EN POSITIVO
//  $sql3 = "SELECT round(sum(haber),2) AS sumaHaber FROM
            $sql3 = "SELECT sum(haber) AS sumaHaber FROM
            `detalle_libro_diario` detalle_libro_diario 
            INNER JOIN `plan_cuentas` plan_cuentas ON detalle_libro_diario.`id_plan_cuenta` = plan_cuentas.`id_plan_cuenta`
            INNER JOIN `libro_diario` libro_diario ON detalle_libro_diario.`id_libro_diario` = libro_diario.`id_libro_diario`
            WHERE plan_cuentas.id_plan_cuenta ='".$id_plan_cuenta."' and plan_cuentas.`codigo` like '".$parametros_codigo."%' 
            AND detalle_libro_diario.`id_periodo_contable`='".$sesion_id_periodo_contable."'
            AND libro_diario.`fecha` BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."';";
            // echo " ******* ".$sql3;
            $resp3 = mysql_query($sql3);
            while($row3=mysql_fetch_array($resp3)){
                  $cadenaHaber = $row3["sumaHaber"];
            }
//echo "debe".$cadenaDebe;
//echo "haber".$cadenaHaber;   
            //*************************  IMPRIME LAS CUENTAS  4, 5, 6,  ********************

            if($numero_filas == '0'){

            }else{

                // saca todos los niveles que tiene la cuenta ejemplo cuenta 6 sacaria nivel 1,2,5
                $sqlNiveles = "SELECT * FROM plan_cuentas WHERE codigo like '".$parametros_codigo."%' AND id_empresa='".$sesion_id_empresa."' AND tipo='Agrupación' GROUP BY nivel order by nivel asc;";
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
                        //consulta la primera cuenta de agrupacion
                        $sqlPrimerCuentaAgrupacion = "SELECT * FROM plan_cuentas WHERE codigo like '".$parametros_codigo."%' AND id_empresa='".$sesion_id_empresa."' 
                        AND tipo='Agrupación' AND nivel='".$cadenaNiveles[$a]."' order by codigo asc;";
                        //echo $cadenaNiveles[$a]."      consult 2: ".$sqlPrimerCuentaAgrupacion;
                        $resultPCA = mysql_query($sqlPrimerCuentaAgrupacion);
                        $numero_filasPCA = mysql_num_rows($resultPCA); // obtenemos el número de filas
                        while($rowPCA=mysql_fetch_array($resultPCA)){
                            //
                            if($vecesQuePasaNivel1 == 1){// para que no imprima cuentas de agrupacion repetidas
                                $data1[] = array(
                                    'codigo'=>utf8_decode($rowPCA["codigo"]),
                                    'cuenta'=>utf8_decode($rowPCA["nombre"]),
                                    'saldo_final'=>''
                                );
                            }
                            $vecesQuePasaNivel1++;
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
                                    'codigo'=>utf8_decode($rowCA["codigo"]),
                                    'cuenta'=>tab($cadenaNiveles[$a]).utf8_decode($rowCA["nombre"]),
                                    'saldo_final'=>''
                                );
                                $posVect++;
                            }

                        }
                    }
                    //$arr1 = str_split($plan_cuentas_codigo);
                    $a++;

                }
                //// fin del bucle para imprimir las cuentas de agrupacion

                //*************  IMPRIME LOS VALORES DE LAS CUENTAS ****************//
              //  $aux=strval($plan_cuentas_codigo);
            //    echo $aux;
             //  if($cadenaDebe = $cadenaHaber*(-1)){
				//echo "id cuenta".$plan_cuentas_codigo;
                //   $total_saldo = $cadenaDebe - $cadenaHaber*(-1);
                //     $total_saldo = $total_saldo - $cadenaDebe;
                  //      $totaldebe = $totaldebe + $cadenaDebe;
                  
                    //ECHO " DEBE TOTAL SALDO ".$total_saldo;
            //     $data1[] = array(
              //         'codigo'=>$plan_cuentas_codigo,
                //       'cuenta'=>tab($plan_cuentas_nivel).utf8_decode($plan_cuentas_nombre),
                  //     'saldo_final'=>number_format('-'.$total_saldo, 2, '.', ' ')
            //      );

              //  }

                  
		//	echo "gastos".$cadenaDebe;
		//	echo "gastos".$cadenaHaber;
                //echo " debe: ".$cadenaDebe;
                //VERIFICA DONDE ESTA EL VALOR EN EL DEBE O HABER
				
           // EN INGRESOS, CUANDO EL VALOR SALE EN DEBE EL TOTAL SE LO PONE EN POSITIVO
              if($cadenaDebe != "0"  and $cadenaDebe != $cadenaHaber  ){
			
                    $total_saldo = $total_saldo - $cadenaDebe;
                    $totaldebe = $totaldebe + $cadenaDebe;
         

                }
                //echo "  haber: ".$cadenaHaber;
                //VERIFICA DONDE ESTA EL VALOR EN EL DEBE O HABER
                if($cadenaHaber != "0" and $cadenaDebe != $cadenaHaber ){
                    
                    $total_saldo = $total_saldo + $cadenaHaber;
                    $totalhaber = $totalhaber + $cadenaHaber;
                    

                }
// TOTAL DE LA CUENTA
$total_cuenta = 0;

// AGREGA EL DEBIDO AL TOTAL DE LA CUENTA
if (is_numeric($cadenaDebe)) {
  $total_cuenta += $cadenaDebe;
}

// AGREGA EL HABER AL TOTAL DE LA CUENTA
if (is_numeric($cadenaHaber)) {
  $total_cuenta -= $cadenaHaber;
}

// AGREGA LA CUENTA AL ARRAY
$data1[] = array(
  'codigo' => $plan_cuentas_codigo,
  'cuenta' => tab($plan_cuentas_nivel).utf8_decode($plan_cuentas_nombre),
  'saldo_final' => number_format($total_cuenta, 2, '.', ' ')
);



            }//--- caso contrario


        }// ******************************* FIN BUCLE SECUNDARIO ********************************************

            //$pdf->ezText("\n",8);
            // CUANDO LA CONSULTA NO ARROGA NINGUN VALOR
            if($numero_filas == '0'){

             }else{
                 $data1[] = array(
                        'codigo'=>'',
                        'cuenta'=>tab(40).utf8_decode('TOTAL '.$parametros_nombre),
                        'saldo_final'=>number_format($total_saldo, 2, '.', ' ')
                        );
                 $data1[] = array(
                        'codigo'=>'',
                        'cuenta'=>'',
                        'saldo_final'=>''
                        );
             }

    } // ************************************** FIN BUCLE PRINCIPAL **************************************************





    //************************** RESUMEN *********************************
    //$pdf->ezText("\n\n");
    $utilidad_neta = $totalhaber-$totaldebe;
    //echo " total ingresos: ".$total_ingresos." total gastos: ".$total_gastos;
    $data2 = array(

    );

    if($utilidad_neta < "0"){
        $titles2 = array(
            'cuenta'=>utf8_decode('PERDIDA DEL EJERCICIO'),
            'saldo_final'=>number_format($utilidad_neta, 2, '.', ' ')
        );

    }else{
        $titles2 = array(
            'cuenta'=>utf8_decode('UTILIDAD DEL EJERCICIO'),
            'saldo_final'=>number_format($utilidad_neta, 2, '.', ' ')
        );
    }

    $titles1 = array(
        'codigo' => '<b>Codigo</b>',
        'cuenta' => '<b>Cuenta</b>',
        'saldo_final' => '<b>Saldo Final</b>',

    );

    $options1 = array(
        'shadeCol'=>array(0.9,0.9,0.9),
        'xOrientation'=>'center',
        'width'=>550,
        'cols'=>array(
            'codigo'=>array('justification'=>'left','width'=>100),
            'cuenta'=>array('justification'=>'left','width'=>350),
            'saldo_final'=>array('justification'=>'right','width'=>100)
         )
    );
    
    $options2 = array(
        'shadeCol'=>array(0.9,0.9,0.9),
        'xOrientation'=>'center',
        'width'=>550,
        'cols'=>array(
            'cuenta'=>array('justification'=>'right','width'=>450),
            'saldo_final'=>array('justification'=>'right','width'=>100)
         )
    );
    
    
    
     $options3 = array(
        'shadeCol'=>array(0.9,0.9,0.9),
        'xOrientation'=>'center',
        'width'=>550,
         'showHeadings' => 0 ,
         'cols'=>array(
           "$sesion_empresa_nombre"=>array('justification'=>'center'),
            'gerente'=>array('justification'=>'center')
         )
        
    );
       $titles3 = array(
            "$sesion_empresa_nombre"=>array('justification'=>'center','width'=>100),
            'gerente'=>array('justification'=>'center','width'=>100)
        );
        
   $data3[] = array(
                        "$sesion_empresa_nombre"=>'Contador',
                        'gerente'=>'Gerente',
                        
                        );
    $pdf->ezText($txttit, 12);
    $pdf->ezTable($data1, $titles1, '', $options1);
    $pdf->ezText($txttit, 2);
    $pdf->ezTable($data2, $titles2, '', $options2);
       $pdf->ezText("\n\n\n\n", 5);
    //  $pdf->ezText("Firmas de responsabilidad",10);
    // $pdf->ezText("\n\n\n\n", 5);
             
    //  $pdf->ezTable($data3, $titles3, '', $options3);
   
    $sqlEmpresa= " SELECT `razonSocial`, `nombreContador` FROM `empresa` WHERE empresa.`id_empresa`=$sesion_id_empresa";
      
    $resultEmpresa= mysql_query($sqlEmpresa);
    while($rEmpresa = mysql_fetch_array($resultEmpresa)){
        $razonSocialEmpresa = $rEmpresa['razonSocial'];
        $nombreContador=$rEmpresa['nombreContador'];
        
    }
    
    //  $razonSocialEmpresa='COMPAÑIA DE TRANSPORTE PUBLICO DE PASAJEROS INTRAPROVINCIAL CIUDAD DE ALAMOR TRANSALAMOR';
    // $nombreContador = "Genesis Castro";
    // $nombreEmpleado="ESCOBAR PAZMIÑO WILSON ROBERTO Calle: SIMON BOLIVAR Intersección: JUAN".' '."ESCOBAR PAZMIÑO WILSON ROBERTO Calle: SIMON BOLIVAR Intersección: JUAN";
    


    $opcionFirmas = array(
        'showHeadings'=>0,
        'shaded'=>0,
        'showLines'=>0,
       
        'cols'=>array(
                'razonSocial'=>array('width'=>255,'justification'=>'center'),
                'contador'=>array('width'=>255,'justification'=>'center'),
               // 'empleado'=>array('width'=>170,'justification'=>'center')
            )
        );
    $data =array( 
          array('razonSocial'=>'_________________________','contador'=>'_________________________','empleado'=>''),
        array('razonSocial'=>utf8_decode($nombreContador),'contador'=>utf8_decode($razonSocialEmpresa),'empleado'=>''),
         array('razonSocial'=>'DPTO. CONTABILIDAD','contador'=>'REPRESENTANTE LEGAL','empleado'=>'')
    
    );

     $pdf->ezTable($data,array('razonSocial'=>'','contador'=>'','empleado'=>''),'',$opcionFirmas);
     
    
   
    $pdf->ezStartPageNumbers(550, 80, 10);
    $pdf->ezStream();
    $pdf->Output('reporteEstadoResultados.pdf', 'D');
    mysql_close();
    mysql_free_result($respi);
?>