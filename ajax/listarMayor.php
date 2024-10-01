<?php
error_reporting(0);
//Start session
session_start(); 


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


    $sql = "SELECT
     mayorizacion.`id_mayorizacion` AS mayorizacion_id_mayorizacion,
     mayorizacion.`id_plan_cuenta` AS mayorizacion_id_plan_cuenta,
     mayorizacion.`id_periodo_contable` AS mayorizacion_id_periodo_contable,
     periodo_contable.`estado` AS periodo_contable_estado,
     plan_cuentas.`codigo` AS plan_cuentas_codigo,
     plan_cuentas.`nombre` AS plan_cuentas_nombre,
     plan_cuentas.`id_empresa` AS plan_cuentas_id_empresa,
     libro_diario.`fecha` AS libro_diario_fecha, libro_diario.`numero_asiento` AS numero_asiento , libro_diario.`descripcion` as libro_diario_descripcion
     
FROM
     `mayorizacion` mayorizacion INNER JOIN `periodo_contable` periodo_contable ON mayorizacion.`id_periodo_contable` = periodo_contable.`id_periodo_contable`
     INNER JOIN `plan_cuentas` plan_cuentas ON mayorizacion.`id_plan_cuenta` = plan_cuentas.`id_plan_cuenta`
     INNER JOIN `libro_diario` libro_diario ON periodo_contable.`id_periodo_contable` = libro_diario.`id_periodo_contable` ";

        if ($_GET['txtIdPlanCuenta'] >= 1){
		$sql .= " WHERE libro_diario.`fecha` BETWEEN '".$fecha_desde."' and '".$fecha_hasta."' AND plan_cuentas.`id_empresa` = '".$sesion_id_empresa."'
		AND plan_cuentas.`id_plan_cuenta` = '".$_GET['txtIdPlanCuenta']."' AND periodo_contable.`id_periodo_contable` = '".$sesion_id_periodo_contable."' ";}
        else {$sql .= " WHERE libro_diario.`fecha` BETWEEN '".$fecha_desde."' and '".$fecha_hasta."' 
                AND plan_cuentas.`id_empresa` = '".$sesion_id_empresa."' AND plan_cuentas.`id_plan_cuenta` like '".$_GET['txtIdPlanCuenta']."%' 
                AND periodo_contable.`id_periodo_contable` = '".$sesion_id_periodo_contable."' ";}
                
    if(trim($_GET['cuenta_contable'])!='' ){
        	$sql .= "  AND plan_cuentas.`nombre` like '%".trim($_GET['cuenta_contable'])."%'  ";
    }
     if(trim($_GET['glosa'])!='' ){
        	$sql .= "  AND libro_diario.`descripcion` like '%".trim($_GET['glosa'])."%'  ";
    }
    
    
	if (isset($_GET['criterio_ordenar_por']))
		$sql .= " GROUP BY plan_cuentas.`codigo` order by ".$_GET['criterio_ordenar_por']." ".$_GET['criterio_orden']."";
	else
		$sql .= " GROUP BY plan_cuentas.`codigo` order by plan_cuentas.`codigo` asc ";
    
// 	if($sesion_id_empresa==41){
// 	     echo $sql;
// 	}
// echo $sql;
        
        $result = mysql_query($sql) or die(mysql_error());
        $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
        $numero = 0;
       
        // echo $sql."***    ".$numero_filas;
        $data2 = array(
                 );

    if ($numero_filas > 0) {
        $page = false;
    //  echo "PAGE==>".$_GET["page"];
        //examino la pagina a mostrar y el inicio del registro a mostrar
        if (isset($_GET["page"])) {
            $page = $_GET["page"];
        }
     
        if (!$page) {
            $start = 0;
            $page = 1;
        } else {
            $start = ($page - 1) * $numeroRegistros;
        }
        
        // echo "start===>".$start;
        //calculo el total de paginas
        $total_pages = ceil($numero_filas / $numeroRegistros);
     
    

        // if ($criterio_ordenar_por!=''){
        //     $sql .= "  order by  $criterio_ordenar_por $criterio_orden"; 
        // }
         
        //  $sql.= "  ORDER BY $criterio_ordenar_por $criterio_orden LIMIT ".$start." , ".$numeroRegistros ;
        
        $sql.= "    LIMIT 1000000000000000000;";
        // echo $sql;
        // echo $sql;
        $result=mysql_query($sql);
   
    ?>
    
       
        
    <?php    
        if ($numero_filas > 0) {
            $contador=1+($page*$numeroRegistros)-$numeroRegistros;
            
               while($row = mysql_fetch_array($result)){
            $numero ++;
            $mayorizacion_id_plan_cuenta = $row['mayorizacion_id_plan_cuenta'];
            $plan_cuentas_codigo = $row['plan_cuentas_codigo'];
            $plan_cuentas_nombre = $row['plan_cuentas_nombre'];


          
            
        ?>
         <table id="grilla" class="table table-hover table-bordered table-striped bg-white"  >
        <thead >
            <tr>
            <th><strong>C&oacute;digo: <?php echo $plan_cuentas_codigo ?></strong></th>
            <th><strong>Cuenta: <?php echo ($plan_cuentas_nombre) ?></strong></th>
        </tr>
        <tr>
            <th><strong>#</strong></th>
            <th><strong>Cpte. Nro</strong></th>
            <th><strong>Fecha</strong></th>
            <th><strong>Descripci&oacute;n</strong></th>
            <th><strong>D&eacute;bito</strong></th>
            <th><strong>Credito</strong></th>
            <th><strong>Saldo</strong></th>
		     
        </tr>
        </thead>
    
    
        <tbody>
        <?php
             

            $sql2 = "SELECT
     detalle_libro_diario.`id_libro_diario`,
     detalle_libro_diario.`debe`,
     detalle_libro_diario.`haber`,
     libro_diario.`id_libro_diario` AS libro_diario_id_libro_diario,
     libro_diario.`fecha` AS libro_diario_fecha,
     libro_diario.`numero_asiento` AS numero_asiento
     
FROM
     `libro_diario` libro_diario INNER JOIN `detalle_libro_diario` detalle_libro_diario 
     ON libro_diario.`id_libro_diario` = detalle_libro_diario.`id_libro_diario`
           AND libro_diario.id_periodo_contable= detalle_libro_diario.`id_periodo_contable`
            WHERE libro_diario.`fecha` BETWEEN '".$fecha_desde."' and '".$fecha_hasta."' and 
            detalle_libro_diario.`id_plan_cuenta`='".$mayorizacion_id_plan_cuenta."' AND 
            detalle_libro_diario.`id_periodo_contable`='".$sesion_id_periodo_contable."' ";
           if(trim($_GET['glosa'])!='' ){
        	$sql2 .= "  AND libro_diario.`descripcion` like '%".trim($_GET['glosa'])."%'  ";
    }  
//   	if($sesion_id_empresa==41){
// 	     echo $sql2;
// 	}
// echo $sql2;
        
            $result2=mysql_query($sql2);
            $debe_detalle_mayorizacion = array();
            $haber_detalle_mayorizacion = array();
            $id_libro_diario = array();
            $id_libro_diario2 = "";
            $fecha = "";
            $numero_comprobante = "";
            $b=0;
            $sumadebe = 0;
            $sumahaber = 0;
            $numero_filas_detalle_mayorizacion = mysql_num_rows($result2); // obtenemos el número de filas
            while($row2=mysql_fetch_array($result2))//permite ir de fila en fila de la tabla
            {
                $sumadebe = $sumadebe + $row2['debe'];
                $sumahaber = $sumahaber + $row2['haber'];
                $debe_detalle_mayorizacion[$b] = $row2['debe'];
                $haber_detalle_mayorizacion[$b] = $row2['haber'];
                $id_libro_diario[$b] = $row2['id_libro_diario'];
                $b++;
            }
          
            for($j=0; $j<$numero_filas_detalle_mayorizacion; $j++){

                $sql3 = "SELECT
                         libro_diario.`id_libro_diario`,
                         libro_diario.`fecha`,
                         libro_diario.`numero_comprobante`,
                         libro_diario.`descripcion`,
                        
                         libro_diario.`tipo_comprobante`, libro_diario.`numero_asiento`
                         
                         
                    FROM
                         `libro_diario` libro_diario
                    WHERE libro_diario.`fecha` BETWEEN '".$fecha_desde."' and '".$fecha_hasta."' AND libro_diario.`id_libro_diario`='".$id_libro_diario[$j]."';  ";
                $result3=mysql_query($sql3);
                while($row3=mysql_fetch_array($result3))//permite ir de fila en fila de la tabla
                {
                    $id_libro_diario2 = $row3['id_libro_diario'];
                    $fecha = $row3['fecha'];
                    $numero_asiento = $row3['numero_asiento'];
                    
                    $numero_comprobante = $row3['numero_comprobante'];
                    $tipo_comprobante = $row3['tipo_comprobante'];
                    $descripcion = $row3['descripcion'];
                     $descripcion = $row3['descripcion'];
                }
                if($tipo_comprobante == "Diario"){
                    $letra_tipo_compro = "D-";
                }
                if($tipo_comprobante == "Ingreso"){
                    $letra_tipo_compro = "I-";
                }
                if($tipo_comprobante == "Egreso"){
                    $letra_tipo_compro = "E-";
                }
                $fecha2 = explode(" ", $fecha);
                
                 ?>
             <tr onclick= "revisarLibroDiario('<?php echo $descripcion ?>',<?php echo $id_libro_diario2 ?>)">
            <th><strong><?php echo $numero_asiento ?></strong></th>
            <th><strong><?php echo $letra_tipo_compro.$numero_comprobante ?></strong></th>
            <th><strong><?php echo $fecha2[0] ?></strong></th>
            <th><strong><?php echo ($descripcion) ?></strong></th>
            <th><strong><?php echo number_format($debe_detalle_mayorizacion[$j], 2, '.', ' ') ?></strong></th>
            <th><strong><?php echo number_format($haber_detalle_mayorizacion[$j], 2, '.', ' ') ?></strong></th>
            <th><strong>''</strong></th>
		      
        </tr>
            <?php
            

                //segunda
                $saldo_string = "";
                $saldo_deudor = 0;
                $saldo_acreedor =0;
                if($sumadebe > $sumahaber){
                    $saldo_deudor = $sumadebe - $sumahaber;
                    $saldo_string = "SD";
                }
                if($sumadebe < $sumahaber){
                    $saldo_acreedor = $sumahaber - $sumadebe;
                    $saldo_string = "SA";
                }
                if($saldo_deudor == "0" && $saldo_acreedor =="0"){
                    $saldo_string = "S";
                }
            }
            $data3 = array(
                );

            if($sumadebe == 0 & $sumahaber == 0){
                $numero --;
            }else{
                ?>
                 </tbody>
                 <tfoot>
    <tr>
        <td colspan="3"></td>
        <td>SUMAS: </td>
        <td><?php echo number_format($sumadebe, 2, '.', ' ') ?></td>
        <td><?php echo number_format($sumahaber, 2, '.', ' ') ?></td>
        <td><?php echo number_format($saldo_deudor+$saldo_acreedor, 2, '.', ' ') ?></td>
    </tr>
  </tfoot>
    </table>
                <?php

        
                
            }

          

        }

            // echo '</tbody>';
            //  echo '</table>';
        }
     


    }
    
    ?>
    