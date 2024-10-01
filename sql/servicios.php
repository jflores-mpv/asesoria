<?php

    require_once('../ver_sesion.php');

    //Start session
    session_start();

    //Include database connection details
    require_once('../conexion.php');


    $accion = $_POST['txtAccion'];
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
 
   
if($accion == '1'){

    // GUARDA SERVICIOS PAGINA: inventarios.php
     try
     {
         
        $txtCodigoS=$_POST['txtCodigoS'];
        $txtNombreS=ucwords($_POST['txtNombreS']);
        $cmbCategoria=$_POST['cmbCategoria'];
        $cmbUnidad=$_POST['cmbUnidad'];
        $cmbTipoServicio=$_POST['cmbTipoServicio'];
        $cmbCuentaContable=$_POST['cmbCuentaContable'];
                       
        if($_POST['checkIva'] == true){
            $iva="Si";
            /*
            $sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
            $resultIva1=mysql_query($sqlIva1);
            $iva=0;
            while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
            {
                $iva=$rowIva1['iva'];
                $id_iva=$rowIva1['id_iva'];
            }
            */
        }else{
            $iva = "No";
        }
        
        $txtDescripcionS=$_POST['txtDescripcionS'];                
        $txtPrecioVenta1S=($_POST['txtPrecioVenta1S']);
        $txtPrecioVenta2S=($_POST['txtPrecioVenta2S']);
        $txtPrecioVenta3S=($_POST['txtPrecioVenta3S']);
        $txtPrecioVenta4S=($_POST['txtPrecioVenta4S']);
        $txtPrecioVenta5S=$_POST['txtPrecioVenta5S'];
        $txtPrecioVenta6S=$_POST['txtPrecioVenta6S'];

        if($txtCodigoS != "" && $txtNombreS != "" && $cmbCategoria != "" && $cmbUnidad != "" && $cmbTipoServicio != "" && $cmbCuentaContable){
          
           //permite sacar el id maximo de servicios
            try {
                $sqlp="Select max(id_servicio) From servicios";
                $resultp=mysql_query($sqlp);
                $id_servicio=0;
                while($rowp=mysql_fetch_array($resultp))//permite ir de fila en fila de la tabla
                    {
                        $id_servicio=$rowp['max(id_servicio)'];
                    }
                $id_servicio++;
            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }

          $sql1 = "insert into servicios(id_servicio, codigo, nombre, observacion, id_categoria, id_unidad, id_tipo_servicio, precio_venta1, precio_venta2, precio_venta3, precio_venta4, precio_venta5, precio_venta6, iva, id_empresa, id_plan_cuenta)
                  values ('".$id_servicio."','".$txtCodigoS."','".$txtNombreS."','".$txtDescripcionS."','".$cmbCategoria."','".$cmbUnidad."','".$cmbTipoServicio."','".$txtPrecioVenta1S."','".$txtPrecioVenta2S."','".$txtPrecioVenta3S."', '".$txtPrecioVenta4S."', '".$txtPrecioVenta5S."', '".$txtPrecioVenta6S."', '".$iva."', '".$sesion_id_empresa."', '".$cmbCuentaContable."'); ";
          $result1 = mysql_query($sql1);

            if ($result1){
                ?><div class='transparent_ajax_correcto'><p>Registro insertado correctamente.</p></div><?php
            }else{
                ?> <div class='transparent_ajax_error'><p>Error al guarda en la tabla servicios: <?php echo mysql_error();?> </p></div> <?php
            }
        }else{
            ?> <div class="transparent_ajax_error"><p>Fallo en el envio del Formulario: No hay datos</p></div> <?php
        }

     }catch (Exception $e) {
     // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
     }
}

   
   if($accion == '2'){
       // GUARDAR MODIFICACION  PAGINA: productos.php
     try
     {
        $txtIdProducto = $_POST['txtIdProducto'];
        $cmbCategoria = $_POST['cmbCategoria'];
        $txtProducto = ucwords($_POST['txtProducto']);
        $txtExistenciaMinima = $_POST['txtExistenciaMinima'];
        $txtExistenciaMaxima = $_POST['txtExistenciaMaxima'];
        $cmbProveedor = $_POST['cmbProveedor'];
        $txtColor = ucwords($_POST['txtColor']);
        $txtTamano = ucwords($_POST['txtTamano']);
        $txtMarca = ucwords($_POST['txtMarca']);
        $img ="";
        $txtDescripcion = ucwords($_POST['txtDescripcion']);
        $txtIdDetalle = $_POST['txtIdDetalle'];
        $txtGanancia1 = $_POST['txtGanancia1'];
        $txtGanancia2 = $_POST['txtGanancia2'];

        if($txtIdProducto != "" && $cmbCategoria != "" && $txtProducto != "" && $txtColor != "" && $txtMarca != ""){                    

          $sql2 = "update  set producto='".$txtProducto."', existencia_minima='".$txtExistenciaMinima."', existencia_maxima='".$txtExistenciaMaxima."', id_categoria='".$cmbCategoria."', id_proveedor='".$cmbProveedor."', ganancia1='".$txtGanancia1."', ganancia2='".$txtGanancia2."' WHERE id_producto='".$txtIdProducto."'; ";
          $resp2 = mysql_query($sql2) or die("<div class='transparent_ajax_error'><p>Error al modificar el producto: ".mysql_error()."</p></div>");
          // consultas para cambiar el ESTADO de CARGOS a LIBRE
          if($resp2){
              $sql22 = "update detalles set color='".$txtColor."', tamano='".$txtTamano."', marca='".$txtMarca."', imagen='".$img."', descripcion='".$txtDescripcion."', id_producto='".$txtIdProducto."' WHERE id_detalle='".$txtIdDetalle."'; ";
              $resp22 = mysql_query($sql22);
              if ($resp22)
                    {
                        ?> <div class='transparent_ajax_correcto'><p>Registro modificado correctamente.</p></div> <?php
                    }else {
                        ?> <div class='transparent_ajax_error'><p>Error al modicar los detalles del producto: <?php echo mysql_error();?> </p></div> <?php }
          }else{
              ?> <div class='transparent_ajax_error'><p>Error al modificar el producto: <?php echo mysql_error();?> </p></div> <?php
          } 
          
      }
      else{
          ?> <div class="transparent_ajax_error"><p>Fallo en el envio del Formulario: No hay datos, <?php echo mysql_error();?></p></div> <?php
      }
    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }
   }

    
    if($accion == "3"){
    // ELIMINA  PAGINA: productos.php
     try
     {
        if(isset ($_POST['id_producto'])){
          $id_producto = $_POST['id_producto'];

          //cambia el estado a libre y limpia el usuario y cedula
          $sql3 = "delete from  WHERE id_producto='".$id_producto."'; ";
          $result3=mysql_query($sql3);

           if($result3){
               echo "Registro eliminado correctamente.";
              }
         else{
             echo "Error al eliminar los datos: ".mysql_error();
             }          
         }else{
          echo "Fallo en el envio del Formulario: No hay datos, ".mysql_error();
      }

     }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }

    }

if($accion == "4"){

    // Is there a posted query string?
    if(isset($_POST['queryString'])) {
        $queryString = $_POST['queryString'];
        $cont = $_POST['cont'];

        // Is the string length greater than 0?
        if(strlen($queryString) >0) {
           
            $query = "SELECT
             servicios.`id_servicio` AS servicios_id_servicio,
             servicios.`codigo` AS servicios_codigo,
             servicios.`nombre` AS servicios_nombre,
             servicios.`observacion` AS servicios_observacion,
             servicios.`id_categoria` AS servicios_id_categoria,
             servicios.`id_unidad` AS servicios_id_unidad,
             servicios.`id_tipo_servicio` AS servicios_id_tipo_servicio,
             servicios.`precio_venta1` AS servicios_precio_venta1,
             servicios.`precio_venta2` AS servicios_precio_venta2,
             servicios.`precio_venta3` AS servicios_precio_venta3,
             servicios.`precio_venta4` AS servicios_precio_venta4,
             servicios.`precio_venta5` AS servicios_precio_venta5,
             servicios.`precio_venta6` AS servicios_precio_venta6,
             servicios.`iva` AS servicios_iva,
             servicios.`id_empresa` AS servicios_id_empresa,
             categorias.`id_categoria` AS categorias_id_categoria,
             categorias.`categoria` AS categorias_categoria,
             categorias.`id_empresa` AS categorias_id_empresa,
             unidades.`id_unidad` AS unidades_id_unidad,
             unidades.`nombre` AS unidades_nombre,
             unidades.`id_empresa` AS unidades_id_empresa,
             tipo_servicios.`id_tipo_servicio` AS tipo_servicios_id_tipo_servicio,
             tipo_servicios.`nombre` AS tipo_servicios_nombre,
             tipo_servicios.`id_empresa` AS tipo_servicios_id_empresa
        FROM
             `categorias` categorias INNER JOIN `servicios` servicios ON categorias.`id_categoria` = servicios.`id_categoria`
             INNER JOIN `unidades` unidades ON servicios.`id_unidad` = unidades.`id_unidad`
             INNER JOIN `tipo_servicios` tipo_servicios ON servicios.`id_tipo_servicio` = tipo_servicios.`id_tipo_servicio`

            WHERE servicios.`id_empresa`='".$sesion_id_empresa."' and CONCAT(servicios.`codigo`, servicios.`nombre`) LIKE '%$queryString%'  LIMIT 10; ";

            $result = mysql_query($query) or die(mysql_error());
            $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
                if($result) {
                    if($numero_filas == 0){
                        echo "<center><p><label style'font-size: 20px'><strong> No hay resulados con el parámetro ingresado. <strong></label></p></center>";
                    }else{
                        echo "<table id='tblServicios".$cont."' class='lista' border='0' >";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th style='padding-left: 5px; padding-right: 5px;'>Código</th>  <th style='padding-left: 5px; padding-right: 5px;'>Nombre</th>  <th style='padding-left: 5px; padding-right: 5px;'>Categoria</th>  <th style='padding-left: 5px; padding-right: 5px;'>Unidad</th>  <th style='padding-left: 5px; padding-right: 5px;'>Tipo Servicio</th>  <th style='padding-left: 5px; padding-right: 5px;'><a href='javascript: fn_cerrar_div();'><img align='right' src='images/cerrar2.png' width='16' height='16' alt='cerrar' title='Cerrar' /></a></th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                         while ($row = mysql_fetch_array($result)) {
                            $id_iva = 0;
                            $iva = 0;
                            
                            // CONSULTA PARA EL IVA
							if ($row["servicios_iva"]=='Si'){
								
							$sqliva = "SELECT * FROM impuestos WHERE estado='Activo' and id_empresa='".$sesion_id_empresa."';";
                            $result2 = mysql_query($sqliva);
                            while ($row2 = mysql_fetch_array($result2)) {
                                $id_iva = $row2["id_iva"];
                                $iva = $row2["iva"];
                            }					
							}
                            
//                            echo '<tr onClick="fill10(\''.$cont.'\','.$row["servicios_id_servicio"].',\''.$row["servicios_codigo"]."*".$row["servicios_nombre"]." ".$row["categorias_categoria"]." ".$row["unidades_nombre"]." ".$row["tipo_servicios_nombre"]."*".$row["servicios_precio_venta1"]."*".$id_iva."*".$iva.'\');" style="cursor: pointer" title="Clic para seleccionar">';
              echo '<tr onClick="fill10(\''.$cont.'\','.$row["servicios_id_servicio"].',\''.$row["servicios_codigo"]."*".$row["servicios_nombre"]." "."*".$row["servicios_precio_venta1"]."*".$id_iva."*".$iva.'\');" style="cursor: pointer" title="Clic para seleccionar">';
                                
				//   "*".$row["servicios_codigo"]."*".$row["servicios_nombre"]." ".$row["categorias_categoria"]." ".$row["unidades_nombre"]." ".$row["tipo_servicios_nombre"]."*".$row["servicios_precio_venta1"]."*".$row["servicios_precio_venta2"]."*".$row["servicios_precio_venta3"]."*".$row["servicios_precio_venta4"]."*".$row["servicios_precio_venta5"]."*".$row["servicios_precio_venta6"]."*".$row["servicios_id_iva"].
                            echo "<td>".$row["servicios_codigo"]."</td>";
                            echo "<td>".$row["servicios_nombre"]."</td>";
                            echo "<td>".$row["categorias_categoria"]."</td>";
                            echo "<td>".$row["unidades_nombre"]."</td>";
                            echo "<td>".$row["tipo_servicios_nombre"]."</td>";
							echo "<td>".$iva."</td>";
							
                            echo "<td></td>";
                            
                            echo "</tr>";

                        }
                        echo "</tbody>";
                        echo"</table>";
                    }
                } else {
                        echo 'ERROR: Hay un problema con la consulta.';
                }
        } else {
            echo 'La longitud no es la permitida.';
        }
    } else {
            echo 'No hay ningún acceso directo a este script!';
    }
}

    
    
?>