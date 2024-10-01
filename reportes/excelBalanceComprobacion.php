<?php
//ob_end_clean();
//Start session
error_reporting(0);
session_start();
function ceros($valor){
	$s='';
 for($i=1;$i<=9-strlen($valor);$i++)
	 $s.="0";
 return $s.$valor;
}
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
    

    
	
	header("Content-Type: application/xls");
	header("Content-Disposition: attachment; filename=balance_comprobacion_".date('Y:m:d:m:s').".xls");
	header("Pragma: no-cache");
	header("Expices:0");



    


	$output .="<table style=' border-collapse: collapse;
        width: 100%;'> <thead>
	<tr ><th colspan='6'  >".utf8_decode('BALANCE DE COMPROBACIÓN')."</th>
	</tr>
	<tr >
		<th colspan='6'  >".strtoupper($sesion_empresa_nombre)."</th>
	</tr>
	<tr >
	<th >Fecha desde: </th>
	<th>".$_GET['txtFechaDesde']."</th>
	<th >Fecha hasta: </th>
	<th>".$_GET['txtFechaHasta']."</th>
	<th></th>
	</tr>
	<tr></tr>
	<tr style='border-style: solid;'>
	<th>#".utf8_decode('CÓDIGO')."</th>
	<th>".utf8_decode('DESCRIPCIÓN')."</th>
	<th>SALDO ANTERIOR</th>
	<th>".utf8_decode('DÉBITO')."</th>
    <th>".utf8_decode('CRÉDITO')."</th>
    <th>SALDO</th>
	</tr> </thead> <tbody>";
	
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
        
        
     $result = mysql_query($sql) or die(mysql_error());
    $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
    $i =  1;
    $totalDebe = 0.0000;
    $totalHaber = 0.0000;
	$saldo=0.0000;
    $posVect = 1;
    $posVect2 = 1;
    $verificaCuentaAgrupacion = "";
    $verificaCuenta = "";
    $vecesQuePasaNivel1 = 1;
    while($row = mysql_fetch_array($result)){ 
        
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
            $sumaDebe = $row2["sumaDebe"];
             $sumaDebeRedondeada = number_format($sumaDebe, 4, '.', '');
            $cadenaDebe [$i]=  $sumaDebeRedondeada;    
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
             $sumaHaber = $row3["sumaHaber"];
            $sumaHaberRedondeada = number_format($sumaHaber, 4, '.', '');
            $cadenaHaber [$i]=  $sumaHaberRedondeada;   
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
                            $sqlPrimerCuentaAgrupacion = "SELECT * FROM plan_cuentas WHERE codigo like '".$primeros_caracteres."%' AND id_empresa='".$sesion_id_empresa."' AND tipo='Agrupación' AND nivel='".$cadenaNiveles[$a]."' order by codigo asc;";

                            //echo $cadenaNiveles[$a]."      consult 2: ".$sqlPrimerCuentaAgrupacion;

                            $resultPCA = mysql_query($sqlPrimerCuentaAgrupacion);

                            $numero_filasPCA = mysql_num_rows($resultPCA); // obtenemos el número de filas

                            while($rowPCA=mysql_fetch_array($resultPCA)){
                                
                                if (in_array($rowPCA["codigo"], $verificaCuenta)) {// para que no imprima cuentas de agrupacion repetidas
                                    // existe
                                }else{
                                    	$output .="
                                		<tr style=' border-left: 2px solid black;border-right: 2px solid black;'>
                                		<td>".utf8_decode($rowPCA["codigo"])."</td>
                                		<td>".utf8_decode($rowPCA["nombre"])."</td>
                                		<td>0.00</td>	
                                		<td>0.00</td>
                                	    <td>0.00</td>
                                	     <td>0.00</td>
                                		</tr>";
                                		
                                    $verificaCuenta[$vecesQuePasaNivel1] = $rowPCA["codigo"];
                                    // $data1[] = array(
                                    //     'plan_cuentas_codigo'=>utf8_decode($rowPCA["codigo"]),
                                    //     'plan_cuentas_nombre'=>utf8_decode($rowPCA["nombre"]),
                                    //     'saldo_anterior'=>'',
                                    //     'debito'=>'',
                                    //     'credito'=>'',
                                    //     'saldo'=>''
                                    // );
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
                                // $data1[] = array(
                                //     'plan_cuentas_codigo'=>utf8_decode($rowCA["codigo"]),
                                //     'plan_cuentas_nombre'=>tab($rowCA["nivel"]).utf8_decode($rowCA["nombre"]),
                                //     'saldo_anterior'=>'',
                                //     'debito'=>'',
                                //     'credito'=>'',
                                //     'saldo'=>''
                                // );
                                	$output .="
                                		<tr style=' border-left: 2px solid black;border-right: 2px solid black;'>
                                		<td>".utf8_decode($rowCA["codigo"])."</td>
                                		<td>".tab($rowCA["nivel"]).utf8_decode($rowCA["nombre"])."</td>
                                		<td>0.00</td>	
                                		<td>0.00</td>
                                	    <td>0.00</td>
                                	     <td>0.00</td>
                                		</tr>";
                            
                                $posVect++;
                            }

                        }
                    }
                    //$arr1 = str_split($plan_cuentas_codigo);
                    $a++;

                }
                //// fin del bucle para imprimir las cuentas de agrupacion

                //*************  IMPRIME LOS VALORES DE LAS CUENTAS DE MOVIMIENTO ****************/
$output .="
  <tr style='border-left: 2px solid black; border-right: 2px solid black;'>
    <td>".utf8_decode($plan_cuentas_codigo)."</td>
    <td>".tab($plan_cuentas_nivel) . utf8_decode($plan_cuentas_nombre)."</td>
    <td>0.00</td>	
    <td>".number_format(abs($cadenaDebe[$i]), 2, '.', ',')."</td>
    <td>".number_format(abs($cadenaHaber[$i]), 2, '.', ',')."</td>
    <td>".number_format(abs($saldo), 2, '.', ',')."</td>
</tr>";
                  		
                //  $data1[] = array(
                //     //'numero'=>$i,
                //     'plan_cuentas_codigo'=>$plan_cuentas_codigo,
                //     'plan_cuentas_nombre'=>tab($plan_cuentas_nivel).utf8_decode($plan_cuentas_nombre),
                //     'saldo_anterior'=>'',
                //     'debito'=>number_format($cadenaDebe[$i], 2, '.', ' '),
                //     'credito'=>number_format($cadenaHaber[$i], 2, '.', ' '),
                //     'saldo'=>number_format($saldo, 2, '.', ' ')
                // );
                 

            }//--- caso contrario

        // calculos para sacar los totales finales
        $totalDebe = $totalDebe + $cadenaDebe[$i];
        $totalHaber = $totalHaber + $cadenaHaber[$i];
        $saldototal = $saldototal + $saldo;

        $i++;         
        
    }
	 
$sqlEmpresa= " SELECT `razonSocial`, `nombreContador` FROM `empresa`  WHERE empresa.`id_empresa`=$sesion_id_empresa ";
      
    $resultEmpresa= mysql_query($sqlEmpresa);
    while($rEmpresa = mysql_fetch_array($resultEmpresa)){
        $razonSocialEmpresa = $rEmpresa['razonSocial'];
        $nombreContador=$rEmpresa['nombreContador'];
        
    }
    
	 $output .="
		<tr style='border-style: solid;'>
	
		<td></td>
		<td>TOTALES</td>	
		<td>".number_format('0.0000',2,'.',',')."</td>
	    <td>".number_format($totalDebe,2,'.',',')."</td>
	    <td>".number_format($totalHaber,2,'.',',')."</td>
	    <td>".number_format($saldototal,2,'.',',')."</td>
		</tr></table>";
		
		 $output .="<table>
		  <tr></tr>
		  <tr></tr>
		  <tr></tr>
		 <tr>
		 <td></td>
		 <td>___________________</td>
		 
		 <td>_________________________</td>
		
		 <td>___________________</td>
		 </tr>
		 
		 <tr>
		 <td></td>
		 <th>".$nombreContador."</th>
		 
		 <th>".utf8_decode($razonSocialEmpresa)."</th>
		
		 <td></td>
		 </tr>
		 
		 <tr>
		 <td></td>
		 <td>DPTO. CONTABILIDAD</td>
		 
		 <td>REPRESENTANTE LEGAL</td>
         
         
		 </tr>
		 </table>";	
		echo $output;

	?>
