<?php
//ob_end_clean();
//Start session
error_reporting(0);
session_start();

//Include database connection details
require_once('../conexion.php');
require_once('class.ezpdf.php');
date_default_timezone_set("America/Guayaquil");

 $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
    $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_id_usuario =  $_SESSION["sesion_id_usuario"];
    $id_empresa_cookies = $_COOKIE["id_empresa"];

   function tab($no)
    {// funcion para dar espacion o sangria a las cuentas
        for($x=1; $x<$no; $x++)
        $tab.="&nbsp;";
        return $tab;
    }
    
    $fecha_desde_principal = explode(" ", $_GET['txtFechaDesde']);
    $fecha_hasta_principal = explode(" ", $_GET['txtFechaHasta']);
    $fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
    $fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");
    $fechaCompleta = "Desde: ".($fecha_desde_principal[0])."    Hasta:".($fecha_hasta_principal[0])."";
    

    $total_saldo =0;
    //************** PARAMETROS PARA EL BALANCE DE ESTADOS DE RESULTADOS *****************//
                    //************** FOR MAYOR *****************//

    $sqlmayor = "SELECT
    parametros_estados_resultados.`id_parametros_estado_resultado` AS parametros_estados_resultados_id_parametros_estado_resultado,
    parametros_estados_resultados.`codigo_cuenta` AS parametros_estados_resultados_codigo_cuenta,
    parametros_estados_resultados.`nombre_cuenta` AS parametros_estados_resultados_nombre_cuenta
    FROM
    `parametros_estados_resultados` parametros_estados_resultados;";

    $respm = mysql_query($sqlmayor);

    
    while($rowm=mysql_fetch_array($respm)){
        
        $parametros_codigo1 = $rowm["parametros_estados_resultados_codigo_cuenta"];
        $parametros_nombre = $rowm["parametros_estados_resultados_nombre_cuenta"];
        
        $sqli = "SELECT
        detalle_libro_diario.`id_detalle_libro_diario` AS detalle_libro_diario_id_detalle_libro_diario,
        detalle_libro_diario.`id_plan_cuenta` AS detalle_libro_diario_id_plan_cuenta,
        plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_plan_cuenta,
        plan_cuentas.`codigo` AS plan_cuentas_codigo,
        plan_cuentas.`tipo` AS plan_cuentas_tipo,
        plan_cuentas.`nombre` AS plan_cuentas_nombre,
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
        `detalle_libro_diario` detalle_libro_diario INNER JOIN `plan_cuentas` plan_cuentas 
		ON detalle_libro_diario.`id_plan_cuenta` = plan_cuentas.`id_plan_cuenta`
        INNER JOIN `periodo_contable` periodo_contable 
		ON detalle_libro_diario.`id_periodo_contable` = periodo_contable.`id_periodo_contable`
        INNER JOIN `libro_diario` libro_diario ON detalle_libro_diario.`id_libro_diario` = libro_diario.`id_libro_diario`
        AND periodo_contable.`id_periodo_contable` = libro_diario.`id_periodo_contable`
        WHERE
        plan_cuentas.`id_empresa` = '".$sesion_id_empresa."' AND plan_cuentas.`codigo` like '".$parametros_codigo1."%' 
		AND detalle_libro_diario.`id_periodo_contable`='".$sesion_id_periodo_contable."' AND 
		libro_diario.`fecha` BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."'
        GROUP BY plan_cuentas.`codigo`
        ORDER BY
        plan_cuentas.`codigo` ASC";



        $aux =0;
        $max =0;
        $cadena = "";
        $respi = mysql_query($sqli);
        $numero_filas1 = mysql_num_rows($respi); // obtenemos el número de filas
        
        while($rowi=mysql_fetch_array($respi)){
            
            $plan_cuentas_nombre = $rowi["plan_cuentas_nombre"];
            $id_plan_cuenta = $rowi["plan_cuentas_id_plan_cuenta"];

            if($id_plan_cuenta== $aux){
                
              
             }else{
                 
            
                 
                 $cadena = $cadena."-".$id_plan_cuenta;

                 
                 $aux = $id_plan_cuenta;
                 
                 $max++;
  
                 
             }
        }
        
        
        

        $vector = array();
        $vector = split('[-]', $cadena);
        $cadenaDebe = array();
        $cadenaHaber = array();
        
        
        for($i=1; $i<=$max; $i++){
            

            $sql2 = "SELECT sum(debe) AS sumaDebe FROM
            `detalle_libro_diario` detalle_libro_diario 
            INNER JOIN `plan_cuentas` plan_cuentas 	ON detalle_libro_diario.`id_plan_cuenta` = plan_cuentas.`id_plan_cuenta`
            INNER JOIN `libro_diario` libro_diario ON detalle_libro_diario.`id_libro_diario` = libro_diario.`id_libro_diario`

            WHERE plan_cuentas.id_plan_cuenta ='".$vector[$i]."' and plan_cuentas.`codigo` 
            
			like '".$parametros_codigo1."%' AND
			
			detalle_libro_diario.`id_periodo_contable`='".$sesion_id_periodo_contable."'
			
			AND libro_diario.`fecha` BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."';";

            $resp2 = mysql_query($sql2);
            while($row=mysql_fetch_array($resp2)){

                $cadenaDebe[$i] = $row["sumaDebe"];

            }
            
        }
       
        for($i1=1; $i1<=$max; $i1++){
            
                // $pdf->ezText("<b>SUMA DEBE</b>", 12,array( 'justification' => 'LEFT' ));
            
            $sql3 = "SELECT sum(haber) AS sumaHaber FROM
            `detalle_libro_diario` detalle_libro_diario 
            
            INNER JOIN `plan_cuentas` plan_cuentas ON detalle_libro_diario.`id_plan_cuenta` = plan_cuentas.`id_plan_cuenta`
            INNER JOIN `libro_diario` libro_diario ON detalle_libro_diario.`id_libro_diario` = libro_diario.`id_libro_diario`

            WHERE plan_cuentas.id_plan_cuenta ='".$vector[$i1]."' and 
			plan_cuentas.`codigo` like '".$parametros_codigo1."%' AND 
			detalle_libro_diario.`id_periodo_contable`='".$sesion_id_periodo_contable."'
			 AND libro_diario.`fecha` BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."';";
			
     
             
             
            $resp3 = mysql_query($sql3);
            while($row3=mysql_fetch_array($resp3)){

             
                $cadenaHaber [$i1]= $row3["sumaHaber"];

            }
                

        }
        

        $total = 0;
        for ($i4=1; $i4<=$max; $i4++){
            
            $sql = "SELECT plan_cuentas.`codigo` AS plan_cuentas_codigo,  plan_cuentas.`nombre` AS plan_cuentas_nombre
			FROM plan_cuentas WHERE id_plan_cuenta = '".$vector[$i4]."';";
            $resp = mysql_query($sql);
            while($row=mysql_fetch_array($resp)){
                  $plan_cuentas_nombre = $row["plan_cuentas_nombre"];
                  $codigo = $row["plan_cuentas_codigo"];
            }

            //*************  IMPRIME LOS VALORES ****************//

            //VERIFICA DONDE ESTA EL VALOR EN EL DEBE O HABER
            if($cadenaDebe[$i4] != "0"){
                $total_saldo = $total_saldo - $cadenaDebe[$i4];
                $total = $total - $cadenaDebe[$i4];
                //ECHO " DEBE TOTAL SALDO ".$total_saldo;
            }
            
         
            //VERIFICA DONDE ESTA EL VALOR EN EL DEBE O HABER
            if($cadenaHaber[$i4] != "0"){
                //ECHO " HABER TOTAL SALDO ".$total_saldo;
                $total_saldo = $total_saldo + $cadenaHaber[$i4];
                $total = $total + $cadenaHaber[$i4];
            }

             
        }

       


    } // **************** FIN BUCLE *********************

    $utilidad_neta = $total_saldo;
	
	header("Content-Type: application/xls");
	header("Content-Disposition: attachment; filename=estado_situacion_".date('Y:m:d:m:s').".xls");
	header("Pragma: no-cache");
	header("Expices:0");



    


	$output .="<table style=' border-collapse: collapse;
        width: 100%;'> <thead>
        
	<tr>
	    <th colspan='3'  >".utf8_decode('ESTADO DE SITUACIÓN')."</th>
	</tr>
	<tr>
		<th colspan='3'  >".strtoupper($sesion_empresa_nombre)."</th>
	</tr>
	<tr ><th colspan='3'  >Fecha desde: ".$_GET['txtFechaDesde']." &nbsp; &nbsp;Fecha hasta: ".$_GET['txtFechaHasta']." </th>
	</tr>
	

	<tr></tr>
	<tr style='border-style: solid;'>
	<th># ".utf8_decode('CÓDIGO')."</th>
	<th>CUENTA</th>
	<th>SALDO FINAL</th>
	</tr> </thead> <tbody>";
	
	 $total_patrimonio =0;
    $totalPasivos = 0;
      $SsqlPBS = "SELECT
    parametros_balance_situacion.`id_balance_situacion` AS parametros_balance_situacion_id_balance_situacion,
    parametros_balance_situacion.`codigo_cuenta` AS parametros_balance_situacion_codigo_cuenta,
    parametros_balance_situacion.`nombre_cuenta` AS parametros_balance_situacion_nombre_cuenta
    FROM
    `parametros_balance_situacion` parametros_balance_situacion;";

    //echo " --> ".$SsqlPBS;
    $respPBS = mysql_query($SsqlPBS);
    while($rowi=mysql_fetch_array($respPBS)){//********************************* INICO BUCLE PRINCIPAL ********************************************

        $vecesQuePasaNivel1 = 1;
        $parametros_codigo = $rowi["parametros_balance_situacion_codigo_cuenta"];
        $parametros_nombre = $rowi["parametros_balance_situacion_nombre_cuenta"];

        // CONSULTA TODAS LAS CUENTAS CON EL PARAMETRO 1, 2, 3,

        $sqlg = "SELECT
        detalle_libro_diario.`id_detalle_libro_diario` AS detalle_libro_diario_id_detalle_libro_diario,
        detalle_libro_diario.`id_plan_cuenta` AS detalle_libro_diario_id_plan_cuenta,
        plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_plan_cuenta,
        plan_cuentas.`codigo` AS plan_cuentas_codigo,
        plan_cuentas.`tipo` AS plan_cuentas_tipo,
        plan_cuentas.`nombre` AS plan_cuentas_nombre,
        plan_cuentas.`nivel` AS plan_cuentas_nivel,
        plan_cuentas.`id_empresa` AS plan_cuentas_id_empresa,
        libro_diario.`id_libro_diario` AS libro_diario_id_libro_diario,
        libro_diario.`fecha` AS libro_diario_fecha
        FROM
        `detalle_libro_diario` detalle_libro_diario INNER JOIN `plan_cuentas` plan_cuentas ON detalle_libro_diario.`id_plan_cuenta` = plan_cuentas.`id_plan_cuenta`
        INNER JOIN `libro_diario` libro_diario ON detalle_libro_diario.`id_libro_diario` = libro_diario.`id_libro_diario`
        WHERE plan_cuentas.`id_empresa` = '".$sesion_id_empresa."' AND
        libro_diario.`fecha` BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."' AND
        plan_cuentas.`codigo` like '".$parametros_codigo."%'
        AND detalle_libro_diario.`id_periodo_contable` = '".$sesion_id_periodo_contable."'
        GROUP BY plan_cuentas.`codigo` ORDER BY  plan_cuentas.`codigo` ASC;";
//echo $sqlg;
        // CONSULTA DEBE Y HABER

        $respg = mysql_query($sqlg);
        $numero_filas = mysql_num_rows($respg); // obtenemos el número de filas
        //echo $numero_filas."  ****->".$sqlg;
        $posVect = 1;
        
        $vecCuentasAgrupacionPasan = array();
       

        //********************************** SACA LAS CUENTAS DEL LIBRO DIARIO ***********************************//
        $limiteRegistros = 0;
        $totalCuentas = 0;
        
        $totalPatrimonio = 0;
        while($rowg=mysql_fetch_array($respg)){// ******************************* INICIO BUCLE SECUNDARIO ********************************************
            $limiteRegistros++;
            $plan_cuentas_nivel = $rowg["plan_cuentas_nivel"];
            $plan_cuentas_codigo = $rowg["plan_cuentas_codigo"];
            $plan_cuentas_nombre = $rowg["plan_cuentas_nombre"];
            $id_plan_cuenta = $rowg["plan_cuentas_id_plan_cuenta"];

            $cadenaDebe = 0;
            $cadenaHaber = 0;

            // EN INGRESOS, CUANDO EL VALOR SALE EN DEBE EL TOTAL SE LO PONE EN NEGATICVO
            $sql2 = "SELECT sum(debe) AS sumaDebe FROM
            `detalle_libro_diario` detalle_libro_diario 
            
            INNER JOIN `plan_cuentas` plan_cuentas ON detalle_libro_diario.`id_plan_cuenta` = plan_cuentas.`id_plan_cuenta`
            INNER JOIN `libro_diario` libro_diario ON detalle_libro_diario.`id_libro_diario` = libro_diario.`id_libro_diario`
            
            WHERE plan_cuentas.id_plan_cuenta ='".$id_plan_cuenta."' and plan_cuentas.`codigo` like '".$parametros_codigo."%' 
            AND detalle_libro_diario.`id_periodo_contable`='".$sesion_id_periodo_contable."'
            AND libro_diario.`fecha` BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."';";
            //echo " 1: ".$sql2;
            $resp2 = mysql_query($sql2);
            while($row=mysql_fetch_array($resp2)){
                  $cadenaDebe = $row["sumaDebe"];
            }

            // EN INGRESOS, CUANDO EL VALOR SALE EN HABER EL TOTAL SE LO PONE EN POSITIVO
            $sql3 = "SELECT sum(haber) AS sumaHaber FROM
            `detalle_libro_diario` detalle_libro_diario 
            INNER JOIN `plan_cuentas` plan_cuentas ON detalle_libro_diario.`id_plan_cuenta` = plan_cuentas.`id_plan_cuenta`
            INNER JOIN `libro_diario` libro_diario ON detalle_libro_diario.`id_libro_diario` = libro_diario.`id_libro_diario`
            
            WHERE plan_cuentas.id_plan_cuenta ='".$id_plan_cuenta."' 
            
            AND plan_cuentas.`codigo` like '".$parametros_codigo."%' 
            AND detalle_libro_diario.`id_periodo_contable`='".$sesion_id_periodo_contable."'
            
            AND libro_diario.`fecha` BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."';";
            //echo " 2: ".$sql3;
            $resp3 = mysql_query($sql3);
            while($row=mysql_fetch_array($resp3)){
                $cadenaHaber= $row["sumaHaber"];
            }

            //*************************  IMPRIME LAS CUENTAS  1, 2, 3,  ********************
                        
            if($numero_filas == '0'){

            }
			else
			{

                // saca todos los niveles que tiene la cuenta ejemplo cuenta 1 sacaria nivel 1,2,5
                $sqlNiveles = "SELECT * FROM plan_cuentas WHERE codigo like '".$parametros_codigo."%' AND 
                id_empresa='".$sesion_id_empresa."' AND tipo='Agrupación' GROUP BY nivel order by nivel asc;";
                $resultN = mysql_query($sqlNiveles);
                $posVec = 0;// esta es el tamaño del vector
                while($rowN=mysql_fetch_array($resultN))
				{
                    $posVec++;
                    // a los niveles de la cuentas lo guardo en un vector
                    $cadenaNiveles[$posVec]= $rowN["nivel"];
                    $cadenaNivelesNombre[$posVec]= $rowN["nombre"];
                }
                //echo $posVec."  consult 1: ".$sqlNiveles;

                // Inicia el bucle para imprimir las cuentas de agrupacion
                $a=1;
				$codigo1="";
				$totalCuentaMovimiento = 0; 
                while($a <= $posVec ){ // recorre de la cuenta, con nivel 1 hasta el utimo nivel. Va de mayor a menor

                    if($cadenaNiveles[$a] == 1)
					{
                        //consulta la primera cuenta de agrupacion
                        $sqlPrimerCuentaAgrupacion = "SELECT * FROM plan_cuentas WHERE codigo like 
                        '".$parametros_codigo."%' AND id_empresa='".$sesion_id_empresa."' AND tipo='Agrupación' 
                        AND nivel='".$cadenaNiveles[$a]."' order by codigo asc;";
                        //echo $cadenaNiveles[$a]."      consult 2: ".$sqlPrimerCuentaAgrupacion;
                        $resultPCA = mysql_query($sqlPrimerCuentaAgrupacion);
                        $numero_filasPCA = mysql_num_rows($resultPCA); // obtenemos el número de filas
						$codigo1="";
                        while($rowPCA=mysql_fetch_array($resultPCA))
						{
                            //
                            if($vecesQuePasaNivel1 == 1){// para que no imprima cuentas de agrupacion repetidas

								$codigo1=$rowPCA["codigo"];
								$cuentaa=$rowPCA["nombre"];
                               
                                $output .="
                                <tr style=' border-left: 2px solid black;border-right: 2px solid black;'>
                                <td>".utf8_decode($rowPCA["codigo"])."</td>
                                <td>".utf8_decode($rowPCA["nombre"])."</td>
                                <td></td>
                                </tr>";
                            }
                            $vecesQuePasaNivel1++;
                        }
                    }
					else
					{
                        //consulta las cuenta de agrupacion del segundo nivel en adelante
                        $mi_variable=substr($plan_cuentas_codigo,0,$cadenaNiveles[$a]);// coje los caratecteres principales de la cuenta de agrupacion dependiendo del nivel
                        $sqlCuentasAgrupacion = "SELECT * FROM plan_cuentas WHERE codigo like 
                        '".$mi_variable."%' AND id_empresa='".$sesion_id_empresa."' 
                        AND tipo='Agrupación' AND nivel='".$cadenaNiveles[$a]."' order by codigo asc;";
                        //echo $cadenaNiveles[$a]."      consult 3: ".$sqlCuentasAgrupacion;
                        $resultCA = mysql_query($sqlCuentasAgrupacion);
						$codigo1="";
						
                        while($rowCA=mysql_fetch_array($resultCA)){

                            if (in_array($rowCA["codigo"], $vecCuentasAgrupacionPasan)) {// para que no imprima cuentas de agrupacion repetidas
                                //echo "       Existe cuenta         ";
                                
                            }else {
                                
                                $vecCuentasAgrupacionPasan[$posVect] = $rowCA["codigo"];
								$codigo1=$rowPCA["codigo"];
								$cuentaa=$rowPCA["nombre"];
								
                               
                                $output .="
                                <tr style=' border-left: 2px solid black;border-right: 2px solid black;'>
                                <td>".utf8_decode($rowCA["codigo"])."</td>
                                <td>".tab($cadenaNiveles[$a]).utf8_decode($rowCA["nombre"])."</td>
                                <td></td>
                                </tr>";
                                
                                
                                $posVect++;
                                
                            }

                        }
                    }
                    //$arr1 = str_split($plan_cuentas_codigo);
                    $a++;

                }
                //// fin del bucle para imprimir las cuentas de agrupacion

                //*************  IMPRIME LOS VALORES DE LAS CUENTAS ****************//

                if($plan_cuentas_codigo[0]==3){
                     
                            $totalCuentaMovimiento = abs($cadenaDebe - $cadenaHaber);
                      
                 }else{
                      $esCuentaDeActivo = true;
                if($plan_cuentas_codigo[0]==2){
                    $esCuentaDeActivo=false;
                }
                    if ($esCuentaDeActivo) {
                        if ($cadenaDebe > $cadenaHaber) {
                            $totalCuentaMovimiento = $cadenaDebe - $cadenaHaber;
                        } elseif ($cadenaDebe < $cadenaHaber) {
                            $totalCuentaMovimiento = -($cadenaHaber - $cadenaDebe);
                        }
                    } else {
                      
                        // Lógica invertida para cuentas que no son de activo
                        if ($cadenaDebe > $cadenaHaber) {
                            $totalCuentaMovimiento = -($cadenaDebe - $cadenaHaber);
                        } elseif ($cadenaDebe < $cadenaHaber) {
                            $totalCuentaMovimiento = $cadenaHaber - $cadenaDebe;
                        }
                    }
                 }
                 
                
                $total_patrimonio = $total_patrimonio + $totalCuentaMovimiento;
			//	if ((substr($codigo1,0,5)=="12002") or (substr($plan_cuentas_codigo,0,5)=="12002" ))
			
		 if (substr($plan_cuentas_nombre, -1) == "-") {
                    $totalCuentas -= $totalCuentaMovimiento;
                } else {
                    $totalCuentas += $totalCuentaMovimiento;
                }

                $output .="
                                <tr style=' border-left: 2px solid black;border-right: 2px solid black;'>
                                <td>".$plan_cuentas_codigo."</td>
                                <td>".tab($plan_cuentas_nivel).utf8_decode($plan_cuentas_nombre)."</td>
                                <td>".number_format(abs($totalCuentaMovimiento), 2, ',', '.')."</td>
                                </tr>";
                  
                 
              
                if($parametros_codigo == '2'){ //CODIGO ESTATICO                    
                    $totalPasivos = $totalPasivos + $totalCuentaMovimiento;
                }

            }//- caso contrario

        }// ******************************* FIN BUCLE SECUNDARIO ********************************************

        //***************** IMPRIME VALORES **************
                
                if($parametros_codigo == '3'){ //CODIGO ESTATICO

                    if($numero_filas == 0){
             
                        $output .="
                                <tr style=' border-left: 2px solid black;border-right: 2px solid black;'>
                                <td>".utf8_decode($parametros_codigo)."</td>
                                <td>".utf8_decode($parametros_nombre)."</td>
                                <td></td>
                                </tr>";
                     }
                    
                     if($utilidad_neta < "0"){
                     
                            $output .="
                                <tr style=' border-left: 2px solid black;border-right: 2px solid black;'>
                                <td></td>
                                <td>".utf8_decode('Perdida del ejercicio')."</td>
                                <td>".number_format($utilidad_neta, 2, ',', '.')."</td>
                                </tr>";
                    }else{
                        $output .="
                                <tr style=' border-left: 2px solid black;border-right: 2px solid black;'>
                                <td></td>
                                <td>".tab($plan_cuentas_nivel).utf8_decode('Utilidad del ejercicio')."</td>
                                <td>".number_format($utilidad_neta, 2, ',', '.')."</td>
                                </tr>";
                       
                    }

                }else{
                    if($numero_filas == $limiteRegistros){
                            $output .="
                                <tr style=' border-left: 2px solid black;border-right: 2px solid black;'>
                                <td>".utf8_decode($rowPCA["codigo"])."</td>
                                <td>".utf8_decode('TOTAL '.utf8_decode($parametros_nombre))."</td>
                                <td>".number_format($totalCuentas, 2, ',', '.')."</td>
                                </tr>";
                                $output .="
                                <tr style=' border-left: 2px solid black;border-right: 2px solid black;'>
                                <td></td>
                                <td></td>
                                <td></td>
                                </tr>";

                    }

                }


    }


  $total_patrimonio = $totalCuentas + $utilidad_neta;  
$output .="<tr style='border-style: solid;'>
                    <td></td>
                    <td>TOTAL PATRIMONIO</td>
                    <td>".number_format($total_patrimonio, 2, ',', '.')."</td>
                </tr>";	
$totalEjercicio =  ($totalPasivos + $total_patrimonio);
$output .="<tr style='border-style: solid;'>
                    <td></td>
                    <td>TOTAL DEL PASIVO Y PATRIMONIO</td>
                    <td>".number_format($totalEjercicio, 2, ',', '.')."</td>
                </tr></table>";	
                
$sqlEmpresa= " SELECT `razonSocial`, `nombreContador` FROM `empresa`  WHERE empresa.`id_empresa`=$sesion_id_empresa ";
      
    $resultEmpresa= mysql_query($sqlEmpresa);
    while($rEmpresa = mysql_fetch_array($resultEmpresa)){
        $razonSocialEmpresa = $rEmpresa['razonSocial'];
        $nombreContador=$rEmpresa['nombreContador'];
        
    }
    
	
		
		 $output .="<table>
		  <tr></tr>
		  <tr></tr>
		  <tr></tr>
		 <tr>
		
		 <td>___________________</td>
		 
		 <td>_________________________</td>
		
		 <td></td>
		 </tr>
		 
		 <tr>
		 
		 <th>".$nombreContador."</th>
		 
		 <th>".utf8_decode($razonSocialEmpresa)."</th>
		<th></th>
		 
		 </tr>
		 
		 <tr>
		 
		 <td>DPTO. CONTABILIDAD</td>
		 
		 <td>REPRESENTANTE LEGAL</td>
		 
		 <td>/td>
		 </tr>
		 </table>";	
		echo $output;

	?>
