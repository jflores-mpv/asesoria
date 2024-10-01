<?php
 
        session_start(); 
        
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        
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
				
        ?>

<div class="col-lg-10 table-responsive">

        <div class="col-lg-12 my-2">
                <input id="button" type="button" value="REPORTE EXCEL" class="btn btn-info text-white w-100" onclick="reporteExcelEstadosResultados()">
            </div>

    
<table class="table table-bordered bg-white">

    <?php
    $arrayTotales = array();
    
    $sqlcentro = "SELECT * FROM centro_costo_empresa WHERE id_empresa = $sesion_id_empresa";
    $rescentro = mysqli_query($conexion, $sqlcentro);
    ?>
    
    <tr>
        
        <th>Centro de Costo</th>
        <?php while ($filacentro = mysqli_fetch_assoc($rescentro)) { ?>
            <td colspan="2">
                <?php echo $filacentro['detalle']; ?>
            </td>
        <?php } ?>
        
    </tr>
    
   
    <?php mysqli_data_seek($rescentro, 0); ?>
        
      <tr>    
        <th>Linea de Negocio</th>
    <?php while ($filacentro = mysqli_fetch_assoc($rescentro)) {     ?>
    
    <?php
     $sqlareas = "SELECT * FROM centro_costo INNER JOIN plan_cuentas ON plan_cuentas.id_plan_cuenta=centro_costo.id_cuenta WHERE empresa = $sesion_id_empresa AND centro_costo.tipo IN (2)";
    $resareas = mysqli_query($conexion, $sqlareas);
    ?>
        <?php while ($filaAreas = mysqli_fetch_assoc($resareas)) { ?>
            <td colspan="2">
                <?php echo $filaAreas['descripcion']; ?>
                
            </td>
        <?php } ?>
      <?php } ?>    
    </tr>

    <?php mysqli_data_seek($rescentro, 0); ?>
        
    <tr>    
        <th>Proyecto</th>
        
    <?php while ($filacentro = mysqli_fetch_assoc($rescentro)) {     ?>

        <?php
        
        
        // Reinicia el puntero de resultados para la segunda consulta
        mysqli_data_seek($resareas, 0);

        while ($filaAreas = mysqli_fetch_assoc($resareas)) {
            
            $area_id = $filaAreas['id_centro_costo']; 
            
            // Asegúrate de tener el campo correcto que identifica el área

            // Consulta para obtener productos según el área actual
            $sqlProductos = "SELECT * FROM productos WHERE grupo = $area_id";
            
            $resProductos = mysqli_query($conexion, $sqlProductos);

            while ($filaProductos = mysqli_fetch_assoc($resProductos)) {
                
                echo '<td style="white-space: nowrap;" >';
                
                echo $filaProductos['producto'] . '<br>';
                
                echo '</td>';
                
            }  }  ?>
        
        <?php } ?>    
    </tr>





            <?php
            $sqlareas = "SELECT * FROM centro_costo INNER JOIN plan_cuentas ON plan_cuentas.id_plan_cuenta=centro_costo.id_cuenta WHERE empresa = $sesion_id_empresa AND centro_costo.tipo IN (2,4) ORDER BY plan_cuentas.codigo";
            // $sqlareas = "SELECT * FROM centro_costo WHERE empresa = $sesion_id_empresa AND tipo IN (2,4) ";
            $resareas2 = mysqli_query($conexion, $sqlareas);
            ?>

            <?php while ($filaAreas2 = mysqli_fetch_assoc($resareas2)) { 
                $id_centro_costo = $filaAreas2['id_centro_costo'];
            ?>
            
            
    <?php mysqli_data_seek($rescentro, 0); ?>
        
    <tr>
                
    <td onclick="revisarLibroMayor('<?php echo utf8_encode($filaAreas2['descripcion'])?>','<?php echo $filaAreas2['id_plan_cuenta'];?>')" style="white-space: nowrap;"><small><?php echo utf8_encode($filaAreas2['codigo'])?>--<?php echo utf8_encode($filaAreas2['descripcion'])?>--
        <?php echo utf8_encode($filaAreas2['nombre'])?></small>
        </td>
    <?php while ($filacentro = mysqli_fetch_assoc($rescentro)) {     ?>
            
            
   
            
            <?php
        
   
            mysqli_data_seek($resareas, 0); 
            
            while ($filaAreas = mysqli_fetch_assoc($resareas)) {
            $area_id = $filaAreas['id_centro_costo']; 
            
            
            $sumaVentas=0;
            $sumaCompras=0;
            $sqlProductos = "SELECT * FROM productos WHERE grupo = $area_id";
            
            $resProductos = mysqli_query($conexion, $sqlProductos);
            while ($filaProductos = mysqli_fetch_assoc($resProductos)) {
            ?>
            
        
            
            <?php     
            
                $idproductoc=$filaProductos['id_producto'];
                $comprasCosto=$filaAreas2['id_centro_costo'];
                $comprasCostoCentro=$filacentro['id_centro_costo']; 
                $sqlcompras = "SELECT SUM(valor_total) as total_compras FROM detalle_compras INNER JOIN compras ON compras.id_compra = detalle_compras.id_compra WHERE idBodega = $comprasCosto and item=$idproductoc and centro_costo_empresa=$comprasCostoCentro AND DATE_FORMAT(fecha_compra, '%Y-%m-%d')>'".$fecha_desde_principal[0]."' AND DATE_FORMAT(fecha_compra, '%Y-%m-%d')<'".$fecha_hasta_principal[0]."'";
                $rescompras = mysqli_query($conexion, $sqlcompras);

                $sqlventas = "SELECT SUM(v_total) as total_ventas FROM detalle_ventas INNER JOIN ventas ON ventas.id_venta = detalle_ventas.id_venta   WHERE idBodega = $comprasCosto and id_servicio=$idproductoc and id_proyecto=$comprasCostoCentro AND DATE_FORMAT(fecha_venta, '%Y-%m-%d')>'".$fecha_desde_principal[0]."' AND DATE_FORMAT(fecha_venta, '%Y-%m-%d')<'".$fecha_hasta_principal[0]."' ";
                $resventas = mysqli_query($conexion, $sqlventas);
                   $sumaCompras = 0;
                    $sumaVentas = 0;
                 if ($rescompras) {
                    $filacompras = mysqli_fetch_assoc($rescompras);
                    $sumaCompras += $filacompras['total_compras'];
                }else{
                    $sumaCompras=0;
                }

                if ($resventas) {
                    $filaventas = mysqli_fetch_assoc($resventas);
                     $sumaVentas += $filaventas['total_ventas'];
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
                    echo "<th style='text-align:right'>" . $valor . "</th>";
                }
            }
        }
           
            echo "</tr>";
            echo "<tr>";
            echo "<th>TOTALES EGRESOS</th>";
           foreach ($arrayTotales as $t => $primerArray) {
            foreach ($primerArray as $y => $segundoArray) {
                foreach ($segundoArray as $u => $valor) {
                    echo "<th style='text-align:right'>" . $valor . "</th>";
                }
            }
        }
           
            echo "</tr>";
            
            echo "<tr>";
            echo "<th>TOTALES:  UTILIDAD / (-PERDIDA)</th>";
           foreach ($arrayTotales as $t => $primerArray) {
            foreach ($primerArray as $y => $segundoArray) {
                foreach ($segundoArray as $u => $valor) {
                    echo "<th style='text-align:right'>" . $valor . "</th>";
                }
            }
        }
           
            echo "</tr>";
           
            ?>
        

    
</table>
        
        </div>
 