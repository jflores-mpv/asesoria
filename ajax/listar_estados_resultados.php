<?php
        error_reporting(0);
        session_start(); 

        function tab($no)
        {
            for($x=1; $x<$no; $x++)
            $tab.="&nbsp;";
            return $tab;
        }
        
        include('../conexion.php');
        include('../conexion2.php');
        include "../clases/paginado_basico.php";
        
        $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
        $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
        $criterio_usu_per= $_GET['glosa'];
        $criterio_ordenar_por = $_GET['criterio_ordenar_por'];
        $criterio_tipo= $_GET['criterio_tipo']; 
        $criterio_mostrar = $_GET['criterio_mostrar'];
        $criterio_orden = $_GET['criterio_orden'];
        $fecha_desde_principal = explode(" ", $_GET['txtFechaDesde']);
        $fecha_hasta_principal = explode(" ", $_GET['txtFechaHasta']);
        $fecha_desde = ($fecha_desde_principal[0]." 00:00:00");
        $fecha_hasta = ($fecha_hasta_principal[0]." 23:59:59");
        $numeroRegistros= $_GET['criterio_mostrar'];
        $cuenta_contable = trim($_GET['cuenta_contable']);

    $sqlmayor = "SELECT
    
    parametros_estados_resultados.`id_parametros_estado_resultado` AS parametros_estados_resultados_id_parametros_estado_resultado,
    parametros_estados_resultados.`codigo_cuenta` AS parametros_estados_resultados_codigo_cuenta,
    parametros_estados_resultados.`nombre_cuenta` AS parametros_estados_resultados_nombre_cuenta
    
    FROM
    
    `parametros_estados_resultados` parametros_estados_resultados ";
    
    $respm = mysql_query($sqlmayor);
	$totaldebe = 0;  $totalhaber = 0;

    $numero_filas = mysql_num_rows($respm); // obtenemos el número de filas

    if ($numero_filas > 0) {
    
    $result=mysql_query($sqlmayor);

    ?>
    
    <table id="grilla" class="table table-hover table-bordered table-striped bg-white table-responsive"  >
    
    <thead>
        <tr>
            <th><strong>C&oacute;digo</strong></th>
            <th><strong>Cuenta</strong></th>
            <th><strong>Saldo Final</strong></th>
        </tr>
    </thead>
    <tbody>
        
    <?php 
   
if ($numero_filas > 0) {
            $contador=1+($page*$numeroRegistros)-$numeroRegistros;
            
               while($rowm = mysql_fetch_array($result)){
                   
        $vecesQuePasaNivel1 = 1;
        $parametros_codigo = $rowm["parametros_estados_resultados_codigo_cuenta"];
        $parametros_nombre = $rowm["parametros_estados_resultados_nombre_cuenta"];
         
         
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
             detalle_libro_diario.`id_periodo_contable`='".$sesion_id_periodo_contable."' AND libro_diario.`fecha` BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."'";
        if( $cuenta_contable!=''){
             $sqli.="  AND plan_cuentas.`nombre` LIKE '%".$cuenta_contable."%' ";
        }     
       $sqli.=" GROUP BY plan_cuentas.`codigo`
        ORDER BY
             plan_cuentas.`codigo` ASC;";
             
            
    $respi = mysql_query($sqli);
    $numero_filas = mysql_num_rows($respi);
    $posVect = 1;
    $vecCuentasAgrupacionPasan = array();
	$total_saldo =0;    
    
    while($rowi=mysql_fetch_array($respi)){
        $plan_cuentas_nivel = $rowi["plan_cuentas_nivel"];
        $plan_cuentas_codigo = $rowi["plan_cuentas_codigo"];
        $plan_cuentas_nombre = $rowi["plan_cuentas_nombre"];
        $id_plan_cuenta = $rowi["plan_cuentas_id_plan_cuenta"];

        $cadenaDebe = "";
        $cadenaHaber = "";
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
              
    
                // Inicia el bucle para imprimir las cuentas de agrupacion
                $a=1;
                while($a <= $posVec ){ 
                // echo 'a'.$a;
                // echo '$cadenaNiveles[$a]'.$cadenaNiveles[$a];
                    if($cadenaNiveles[$a] == 1){
                        //consulta la primera cuenta de agrupacion
                         $sqlPrimerCuentaAgrupacion = "SELECT * FROM plan_cuentas WHERE codigo like '".$parametros_codigo."%' AND id_empresa='".$sesion_id_empresa."' AND tipo='Agrupación' AND nivel='".$cadenaNiveles[$a]."' order by codigo asc;";
                        //echo $cadenaNiveles[$a]."      consult 2: ".$sqlPrimerCuentaAgrupacion;
                        $resultPCA = mysql_query($sqlPrimerCuentaAgrupacion);
                        $numero_filasPCA = mysql_num_rows($resultPCA); // obtenemos el número de filas
                        while($rowPCA=mysql_fetch_array($resultPCA)){
                            //
                            if($vecesQuePasaNivel1 == 1){
                           
                        echo ' <tr>
                        <th><strong>z'.($rowPCA["codigo"]).'</strong></th>
                        <th onclick="revisarLibroMayor(\''.$rowPCA["nombre"].'\','.($rowPCA["id_plan_cuenta"]).')">  <strong>'.$rowPCA["nombre"].')"</strong></th>
                        <th><strong></strong></th>
            		     </tr>';
                                
                                
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
                                // echo "       Existe cuenta         ";
                              
                            }else {
                                //  echo "   NO    Existe cuenta         ";
                                $vecCuentasAgrupacionPasan[$posVect] = $rowCA["codigo"];
                             
            //  echo '<tr><td  ><strong>'.($rowCA["codigo"]).'</strong></td><td onclick="revisarLibroMayor(\''.($rowCA["nombre"]).'\','.($rowCA["id_plan_cuenta"]).')"  ><strong>'.tab($cadenaNiveles[$a]).($rowCA["nombre"]).'</strong></td><td><strong></strong></td></tr>';
		      // echo "  _____________     ";
                                
                                $posVect++;
                            }

                        }
                        // echo '1';
                    }
                    //$arr1 = str_split($plan_cuentas_codigo);
                    $a++;

                }
            //   echo 'qwewq';

                //VERIFICA DONDE ESTA EL VALOR EN EL DEBE O HABER
				
                if($cadenaDebe != "0"  and $cadenaDebe != $cadenaHaber  ){
				//	echo "gastos".$cadenaDebe;
                    $total_saldo = $total_saldo - $cadenaDebe;
                    $totaldebe = $totaldebe + $cadenaDebe;
                    //ECHO " DEBE TOTAL SALDO ".$total_saldo;
                    // $data1[] = array(
                    //     'codigo'=>$plan_cuentas_codigo,
                    //     'cuenta'=>tab($plan_cuentas_nivel).utf8_decode($plan_cuentas_nombre),
                    //     'saldo_final'=>number_format('-'.$cadenaDebe, 2, '.', ' ')
                    // );
    //                 echo ' <tr>
    //         <th><strong>'.$plan_cuentas_codigo.'</strong></th>
    //         <th onclick="revisarLibroMayor(\''.$plan_cuentas_nombre.'\','.$id_plan_cuenta.')"  ><strong>'.tab($plan_cuentas_nivel).($plan_cuentas_nombre).'</strong></th>
    //         <th><strong>'.number_format('-'.$cadenaDebe, 2, '.', ' ').'</strong></th>
		  //   </tr>';
                    

                }
                //echo "  haber: ".$cadenaHaber;
                //VERIFICA DONDE ESTA EL VALOR EN EL DEBE O HABER
                if($cadenaHaber != "0" and $cadenaDebe != $cadenaHaber ){
                    //ECHO " HABER TOTAL SALDO ".$total_saldo;
                    $total_saldo = $total_saldo + $cadenaHaber;
                    $totalhaber = $totalhaber + $cadenaHaber;
                    // $data1[] = array(
                    //     'codigo'=>$plan_cuentas_codigo,
                    //     'cuenta'=>tab($plan_cuentas_nivel).utf8_decode($plan_cuentas_nombre),
                    //     'saldo_final'=>number_format($cadenaHaber, 2, '.', ' ')
                    // );
                    
    //                 echo ' <tr>
    //         <th><strong>'.$plan_cuentas_codigo.'</strong></th>
    //         <th onclick="revisarLibroMayor(\''.$plan_cuentas_nombre.'\','.$id_plan_cuenta.')"  ><strong>'.tab($plan_cuentas_nivel).($plan_cuentas_nombre).'</strong></th>
    //         <th><strong>'.number_format($cadenaHaber, 2, '.', ' ').'</strong></th>
		  //   </tr>';

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


 echo ' <tr>
            <th><strong>'.$plan_cuentas_codigo.'</strong></th>
            <th onclick="revisarLibroMayor(\''.$plan_cuentas_nombre.'\','.$id_plan_cuenta.')"  ><strong>'.tab($plan_cuentas_nivel).($plan_cuentas_nombre).'</strong></th>
            <th><strong>'.number_format($total_cuenta, 2, '.', ' ').'</strong></th>
		     </tr>';
            }
    }

      if($numero_filas == '0'){

             }else{
                //  $data1[] = array(
                //         'codigo'=>'',
                //         'cuenta'=>tab(40).utf8_decode('TOTAL '.$parametros_nombre),
                //         'saldo_final'=>number_format($total_saldo, 2, '.', ' ')
                //         );
                        
                 echo ' <tr>
            <th><strong></strong></th>
            <th><strong>'.tab(40).utf8_decode('TOTAL '.$parametros_nombre).'</strong></th>
            <th><strong>'.number_format($total_saldo, 2, '.', ' ').'</strong></th>
		     </tr>';
		      echo ' <tr>
            <th><strong></strong></th>
            <th><strong></th>
            <th><strong></strong></th>
		     </tr>';
		     
                //  $data1[] = array(
                //         'codigo'=>'',
                //         'cuenta'=>'',
                //         'saldo_final'=>''
                //         );
             }

    }
     $utilidad_neta = $totalhaber-$totaldebe;
      if($utilidad_neta < "0"){
        // $titles2 = array(
        //     'cuenta'=>utf8_decode('PERDIDA DEL EJERCICIO'),
        //     'saldo_final'=>number_format($utilidad_neta, 2, '.', ' ')
        // );
        echo ' <tr>
            <th colspan="2">PERDIDA DEL EJERCICIO</th>
            <th><strong>'.number_format($utilidad_neta, 2, '.', ' ').'</strong></th>
		     </tr> </tbody></table>';

    }else{
         echo ' <tr>
            <th colspan="2">UTILIDAD DEL EJERCICIO</th>
            <th><strong>'.number_format($utilidad_neta, 2, '.', ' ').'</strong></th>
		     </tr> </tbody></table>';
        // $titles2 = array(
        //     'cuenta'=>utf8_decode('UTILIDAD DEL EJERCICIO'),
        //     'saldo_final'=>number_format($utilidad_neta, 2, '.', ' ')
        // );
    }
    
        }

        }
     


    ?>
    