<?php
session_start();

//Include database connection details
require_once('../conexion.php');

date_default_timezone_set("America/Guayaquil");

$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$sesion_empresa_nombre = $_SESSION['sesion_empresa_nombre'];
$sesion_id_periodo_contable =$_GET['Periodo'];

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

	
	header("Content-Type: application/xls;charset=utf-8");
	header("Content-Disposition: attachment; filename=estados_resultados".date('Y:m:d:m:s').".xls");
	header("Pragma: no-cache");
	header("Expices:0");

    $arrayTotales = array();
    $sqlcentro = "SELECT * FROM centro_costo_empresa WHERE id_empresa = $sesion_id_empresa";
    $rescentro = mysql_query( $sqlcentro);
 
    ?>
    <table class="table table-bordered bg-white">
     <tr>
        
        <th>Centro de Costo</th>
        <?php while ($filacentro = mysql_fetch_array($rescentro)) { ?>
            <td colspan="2">
                <?php echo utf8_decode($filacentro['detalle']); ?>
            </td>
        <?php } ?>
        
    </tr>
     <?php mysql_data_seek($rescentro, 0); ?>
        
      <tr>    
        <th>Linea de Negocio</th>
    <?php while ($filacentro = mysql_fetch_array($rescentro)) {     ?>
    
    <?php
     $sqlareas = "SELECT * FROM centro_costo INNER JOIN plan_cuentas ON plan_cuentas.id_plan_cuenta=centro_costo.id_cuenta WHERE empresa = $sesion_id_empresa AND centro_costo.tipo IN (2)";
    $resareas = mysql_query($sqlareas);
    ?>
        <?php while ($filaAreas = mysql_fetch_array($resareas)) { ?>
            <td colspan="2">
                <?php echo utf8_decode($filaAreas['descripcion']); ?>
                
            </td>
        <?php } ?>
      <?php } ?>    
    </tr>


  <?php mysql_data_seek($rescentro, 0); ?>
        
    <tr>    
        <th>Proyecto</th>
        
    <?php while ($filacentro = mysql_fetch_array($rescentro)) {     ?>

        <?php
        
        
        // Reinicia el puntero de resultados para la segunda consulta
        mysql_data_seek($resareas, 0);

        while ($filaAreas = mysql_fetch_array($resareas)) {
            
            $area_id = $filaAreas['id_centro_costo']; 
            
            // Asegúrate de tener el campo correcto que identifica el área

            // Consulta para obtener productos según el área actual
            $sqlProductos = "SELECT * FROM productos WHERE grupo = $area_id";
            
            $resProductos = mysql_query( $sqlProductos);

            while ($filaProductos = mysql_fetch_array($resProductos)) {
                
                echo '<td style="white-space: nowrap;">';
                
                echo utf8_decode($filaProductos['producto']) . '<br>';
                
                echo '</td>';
                
            }  }  ?>
        
        <?php } ?>    
    </tr>
     <?php
            $sqlareas = "SELECT * FROM centro_costo INNER JOIN plan_cuentas ON plan_cuentas.id_plan_cuenta=centro_costo.id_cuenta WHERE empresa = $sesion_id_empresa AND centro_costo.tipo IN (2,4) ORDER BY plan_cuentas.codigo";
            // $sqlareas = "SELECT * FROM centro_costo WHERE empresa = $sesion_id_empresa AND tipo IN (2,4) ";
            $resareas2 = mysql_query( $sqlareas);
            ?>

            <?php while ($filaAreas2 = mysql_fetch_array($resareas2)) { 
                $id_centro_costo = $filaAreas2['id_centro_costo'];
            ?>
            
            
    <?php mysql_data_seek($rescentro, 0); ?>
        
    <tr>
                
    <td style="white-space: nowrap;"><small>aqui<?php echo utf8_decode($filaAreas2['codigo'])?>--<?php echo utf8_decode($filaAreas2['descripcion'])?>--
        <?php echo utf8_decode($filaAreas2['nombre'])?></small>
        </td>
    <?php while ($filacentro = mysql_fetch_array($rescentro)) {     ?>
            
            
   
            
            <?php
        
   
            mysql_data_seek($resareas, 0); 
            
            while ($filaAreas = mysql_fetch_array($resareas)) {
            $area_id = $filaAreas['id_centro_costo']; 
            
            
            $sumaVentas=0;
            $sumaCompras=0;
            $sqlProductos = "SELECT * FROM productos WHERE grupo = $area_id";
            
            $resProductos = mysql_query( $sqlProductos);
            while ($filaProductos = mysql_fetch_array($resProductos)) {
            ?>
            
        
            
            <?php     
            
                $idproductoc=$filaProductos['id_producto'];
                $comprasCosto=$filaAreas2['id_centro_costo'];
                $comprasCostoCentro=$filacentro['id_centro_costo']; 
                 $sqlcompras = "SELECT SUM(valor_total) as total_compras FROM detalle_compras INNER JOIN compras ON compras.id_compra = detalle_compras.id_compra WHERE idBodega = $comprasCosto and item=$idproductoc and centro_costo_empresa=$comprasCostoCentro  AND DATE_FORMAT(fecha_compra, '%Y-%m-%d')>'".$fecha_desde_principal[0]."' AND DATE_FORMAT(fecha_compra, '%Y-%m-%d')<'".$fecha_hasta_principal[0]."';";
                $rescompras = mysql_query( $sqlcompras);

                $sqlventas = "SELECT SUM(v_total) as total_ventas FROM detalle_ventas INNER JOIN ventas ON ventas.id_venta = detalle_ventas.id_venta WHERE idBodega = $comprasCosto and id_servicio=$idproductoc and id_proyecto=$comprasCostoCentro  AND DATE_FORMAT(fecha_venta, '%Y-%m-%d')>'".$fecha_desde_principal[0]."' AND DATE_FORMAT(fecha_venta, '%Y-%m-%d')<'".$fecha_hasta_principal[0]."';";
                $resventas = mysql_query( $sqlventas);
                   $sumaCompras = 0;
                    $sumaVentas = 0;
                 if ($rescompras) {
                    $filacompras = mysql_fetch_array($rescompras);
                    $sumaCompras += number_format($filacompras['total_compras'], 2);
                }else{
                    $sumaCompras=0;
                }

                if ($resventas) {
                    $filaventas = mysql_fetch_array($resventas);
                     $sumaVentas += number_format($filaventas['total_ventas'], 2); 
                }else{
                     $sumaVentas=0; 
                }
                
                echo "<td style='text-align:right'>";
                    if ($sumaCompras != 0) {
                         $totales=$sumaCompras;
                         $arrayTotales[$comprasCostoCentro][$area_id][$idproductoc]= $arrayTotales[$comprasCostoCentro][$area_id][$idproductoc] -$totales;
                    } else {
                        $totales= $sumaVentas;
                        $arrayTotales[$comprasCostoCentro][$area_id][$idproductoc]= $arrayTotales[$comprasCostoCentro][$area_id][$idproductoc] +$totales;
                    }
                    
                    //  $arrayTotales[$comprasCostoCentro][$area_id][$idproductoc]= $arrayTotales[$comprasCostoCentro][$area_id][$idproductoc] +$totales;
                   
                    echo      number_format($totales, 2, '.', ',') . '</td>';
                
            ?>    
            
   
            
    <?php } } }  }
            
            echo "</tr>";
            echo "<tr>";
            echo "<th>TOTALES INGRESOS</th>";
           foreach ($arrayTotales as $t => $primerArray) {
            foreach ($primerArray as $y => $segundoArray) {
                foreach ($segundoArray as $u => $valor) {
                    echo "<th style='text-align:right'>" .number_format($valor, 2, '.', ',')  . "</th>";
                }
            }
        }
           
            echo "</tr>";
            echo "<tr>";
            echo "<th>TOTALES EGRESOS</th>";
           foreach ($arrayTotales as $t => $primerArray) {
            foreach ($primerArray as $y => $segundoArray) {
                foreach ($segundoArray as $u => $valor) {
                    echo "<th style='text-align:right'>" .number_format($valor, 2, '.', ',')  . "</th>";
                }
            }
        }
           
            echo "</tr>";
            
            echo "<tr>";
            echo "<th>TOTALES:  UTILIDAD / (-PERDIDA)</th>";
           foreach ($arrayTotales as $t => $primerArray) {
            foreach ($primerArray as $y => $segundoArray) {
                foreach ($segundoArray as $u => $valor) {
                    echo "<th style='text-align:right'>" .number_format($valor, 2, '.', ',')  . "</th>";
                }
            }
        }
           
            echo "</tr>";
           
            ?>
        

    
</table>
  