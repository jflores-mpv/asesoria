
<?php

    require_once('../ver_sesion.php');

    //Start session
    session_start();

    //Include database connection details
    require_once('../conexion.php');

    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];

    $accion = $_POST['txtAccion'];
       
        if($accion == 1){
            // GUARDAR EMPRESA
        
			$inv_codigo=$_POST['txtCodigoS'];
			$inv_descripcion=$_POST['txtNombreS'];
			$id_unidad=$_POST['cmbUnidad'];
		  // $id_bodega=$_POST['cmbTipoBodegas'];
			$id_bodega=$_POST['cmbBodegas'];
		   
			$descripcion=$_POST['txtDescripcionS'];
			$precio=$_POST['txtPrecio'];
			$ctacontable=$_POST['cmbCuentaContable'];
		
			$sqlm1="Select max(id_inventario) From inventarios_bodega;";
				$resultm1=mysql_query($sqlm1)  or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
				$id_inventario=0;
				while($rowm1=mysql_fetch_array($resultm1))//permite ir de fila en fila de la tabla
				{
					$id_inventario=$rowm1['max(id_inventario)'];
				}
				$id_inventario++;

				//$sql="insert into ventas (id_venta, fecha_venta, estado, total, sub_total, numero_factura_venta, fecha_anulacion, descripcion, id_iva, id_vendedor, id_cliente, id_empresa, tipo_documento, id_forma_pago) values ('".$id_venta."','".$fecha_venta."','".$estado."','".$total."','".$sub_total."','".$numero_factura."', '','".$txtDescripcion."', '".$txtIdIva."', '".$cmbIdVendedor."', '".$id_cliente."', '".$sesion_id_empresa."', '".$cmbTipoDocumentoFVC."', '".$idFormaPago."');";
				//echo $sql;
				//$result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al guardar en ventas: '.mysql_error().' </p></div>  ');
		
			$sqlp="insert into inventarios_bodega(id_inventario,inv_codigo, inv_descripcion, id_unidad, id_bodega, inv_observacion,id_empresa,inv_precio,inv_cuentacontable) values (' $id_inventario',' $inv_codigo','$inv_descripcion','$id_unidad','$id_bodega','$descripcion','$sesion_id_empresa','$precio','$ctacontable')";  
			//echo $sqlp;
	    ?>
		<div class='transparent_ajax_correcto'><p>Registro insertado correctamente.</p></div> <?php
                }
                    
           $result=mysql_query($sqlp);
		 
		if($accion == 10)
		{
			try 
			{
				$cadena10="";
				$consulta10="SELECT * FROM bodegas where id_empresa='".$sesion_id_empresa."' order by id_bodega asc;";
			//echo $consulta10;
				$result10=mysql_query($consulta10);
				while($row10=mysql_fetch_array($result10))//permite ir de fila en fila de la tabla
                {
                  $cadena10=$cadena10."?".$row10['id_bodega']."?".$row10['nombre_bodega'];
//                  echo $cadena10;
                }
				echo $cadena10;
			}
			catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
		}

	//echo "estroy en sql bodegas "."<br>"."accion";
	//echo $accion;
	
	 if($accion == 3){
    // GUARDA MODIFICACION CARGOS PAGINA: cargos.php
     try
     {
		 $txtId_bodega = $_POST['txtId_bodega'];
         $txtId_OtrosProd = $_POST['txtId_OtrosProd'];
		 $cmbUnidad = $_POST['cmbUnidad'];
		 $cmbUbicacion = $_POST['cmbUbicacion'];
         $txtDescripcion = ucwords($_POST['txtDescripcion']);
         $txtPrecio = $_POST['txtPrecio'];
		 $txtObservacion = $_POST['txtObservacion'];

         if($txtDescripcion != ""){
              $sql3 = "update inventarios_bodega
			  SET inv_codigo='". $txtId_OtrosProd."',inv_descripcion='".$txtDescripcion."', id_unidad='".$cmbUnidad."', id_bodega='".$cmbUbicacion."', inv_observacion='".$txtObservacion."',inv_precio='".$txtPrecio."'  where id_inventario='".$txtId_bodega."'; ";
			//  echo "ver sql acturl";
		//echo $sql3;
			 $resp3 = mysql_query($sql3);
              if($resp3){
                ?> <div class='transparent_ajax_correcto'><p>Registro modificado correctamente.</p></div> <?php
              }else{
                ?> <div class='transparent_ajax_error'><p>Error al guarda los datos: problemas en la consulta</p></div> <?php
              }
         }else {
              ?> <div class="transparent_ajax_error"><p>Error: No hay datos</p></div> <?php
              }

     }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }

    }
	
	if($accion == "4"){
    // ELIMINA CARGOS PAGINA: cargos.php
     try
     {
	// echo "codigo";
	// echo $_POST[inventarios_bodega_id_inventario];
        if(isset ($_POST['inventarios_bodega_id_inventario'])){
          $inventarios_bodega_id_inventario = $_POST['inventarios_bodega_id_inventario'];
          $sql4 = "delete from inventarios_bodega where id_inventario='".$inventarios_bodega_id_inventario."'; ";
		 // echo $sql4;
          $resp4 = mysql_query($sql4);
          if(!mysql_query($sql4)){
              echo "Ocurrio un error\n$sql4";
              }else{
                echo "El registro ha sido Eliminado."; }
         }else {
              ?> <div class="transparent_ajax_error"><p>Error: No hay datos</p></div> <?php
              }

     }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }

    }
	
  if($accion == '14'){
       // GUARDA TIPO SERVICIOS  PAGINA: ajax/tipoServicios.php
     try
     {
        $numeroFilaSelec=$_POST['filas'];
        $txtNombreUbicacion=ucwords($_POST['txtdetalleBodega'.$numeroFilaSelec]);

        if($numeroFilaSelec != "" && $txtNombreUbicacion != ""){

      
          $sql14 = "insert into bodegas( detalle, id_empresa) values ('".$txtNombreUbicacion."', '".$sesion_id_empresa."'); ";
          $result14 = mysql_query($sql14);
          if ($result14)
                {
                   echo "1";
                }else {
                    ?> <div class='transparent_ajax_error'><p>Error al guarda la categoria: <?php echo mysql_error();?> </p></div> <?php }
        }
        else{
            ?> <div class="transparent_ajax_error"><p>Fallo en el envio del Formulario: No hay datos, <?php echo mysql_error();?></p></div> <?php
        }

     }catch (Exception $e) {
     // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
     }
   }	
   
   if($accion == 15){
echo "actaaaa";
        // ACTUALIZA tabla TIPO SERVICIOS
        try
        {
            $idBodega15=$_POST['idUbicacion'];
            $numeroFila15 = $_POST['NumeroFilaSeleccionada'];
            $txtNombreUbicacion15 = ucwords($_POST['txtNombreUbicacion'.$numeroFila15]);
echo "<br>"."bodega";
			echo $idBodega15;
			echo "<br>"."ubicacion";
echo $txtNombreUbicacion15;
            if($txtNombreUbicacion15 != "" & $idBodega15 !=""){
                $sqlm15 = "update bodegas set nombre_bodega='".$txtNombreUbicacion15."', id_empresa='".$sesion_id_empresa."' where id_bodega='".$idBodega15."'; ";
echo $sqlm15 ;
                $respm15 = mysql_query($sqlm15);
                if($respm15){
                    ?> <div class='transparent_ajax_correcto'><p>Registro Modificado correctamente.</p></div> <?php
                }
                else{
                    ?> <div class='transparent_ajax_error'><p>Error al guarda los datos: problema con la consulta <?php echo "\n".mysql_error(); ?></p></div> <?php
                }
            }
            else{
                ?> <div class="transparent_ajax_error"><p>Fallo en el envio del Formulario: No hay datos...<?php echo "\n".mysql_error(); ?></p></div> <?php
            }

        }catch (Exception $e) {
            // Error en algun momento.
            ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
        }
   }
   
    if($accion == '16'){
        // ELIMINA TABLA TIPO SERVICIOS
        try
        {
            $idBodega16 = $_POST['idUbicacion'];
            $sqD16 = "delete from bodegas where id_bodega='".$idBodega16."';";
            if(!mysql_query($sqD16)){
                echo "<div class='transparent_ajax_error'><p>Ocurrio un error al eliminar: \n".mysql_error()."</p></div>";
            }
            else{
                echo "<div class='transparent_notice'><p>El registro ha sido Eliminado.</p></div>";
            }

        }catch (Exception $e) {
            // Error en algun momento.
            ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
        }

    }
        if($accion == '20')// modificar bodega
		{
			try 
			{
                $idBodega=$_POST['id'];
                $detalle=$_POST['detalle'];
				$cadena10="";
				 $consulta10="UPDATE `bodegas` SET `detalle`='".$detalle."' WHERE  id=$idBodega ;";
			//echo $consulta10;
				$result10=mysql_query($consulta10);
				
				echo $resultado = ($result10)?1:2;
			}
			catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
		}
		
    if($accion == '21'){
        // ELIMINA TABLA TIPO SERVICIOS
        
            $idBodega = trim($_POST['idBodega']);

            $sqlCantBodegas = "SELECT `id`, `idBodega` FROM `cantbodegas` WHERE `idBodega`=$idBodega ";
            $respCantBodegas = mysql_query($sqlCantBodegas);        
            $numFilasCantBodegas = mysql_num_rows($respCantBodegas);
           
            $sqlCentroCosto = "SELECT `id_centro_costo`, `id_bodega` FROM `centro_costo` WHERE `id_bodega`=$idBodega ";
            $respCentroCosto = mysql_query($sqlCentroCosto);        
            $numFilasCentroCosto = mysql_num_rows($respCentroCosto);

            $sqlDetalleCompras = "SELECT `id_detalle_compra`, `idBodega`, `idBodegaInventario`, `id_empresa` FROM `detalle_compras` WHERE `idBodega`=$idBodega and id_empresa=$sesion_id_empresa  ";
            $respDetalleCompras = mysql_query($sqlDetalleCompras);        
            $numFilasDetalleCompras = mysql_num_rows($respDetalleCompras);

            $sqlDetalleEgresos = "SELECT `id_detalle_egreso`, `bodega`, `id_empresa` FROM `detalle_egresos` WHERE `bodega` =$idBodega and id_empresa=$sesion_id_empresa  ";
            $respDetalleEgresos = mysql_query($sqlDetalleEgresos);        
            $numFilasDetalleEgresos = mysql_num_rows($respDetalleEgresos);

            $sqlDetalleIngresos = " SELECT `id_detalle_ingreso`, `bodega`, `id_empresa` FROM `detalle_ingresos` WHERE `bodega` =$idBodega and id_empresa=$sesion_id_empresa  ";
            $respDetalleIngresos = mysql_query($sqlDetalleIngresos);        
            $numFilasDetalleIngresos = mysql_num_rows($respDetalleIngresos);

            $sqlDetalleOrdenCompra = " SELECT `id_detalle_compra`, `idBodega`, `id_empresa` FROM `detalle_ordencompra` WHERE idBodega =$idBodega and id_empresa=$sesion_id_empresa  ";
            $respDetalleOrdenCompra = mysql_query($sqlDetalleOrdenCompra);        
            $numFilasDetalleOrdenCompra = mysql_num_rows($respDetalleOrdenCompra);

            $sqlDetallePedido = " SELECT `id_detalle_pedido`, `idBodega`, `id_bodega_inventario`,  `id_empresa` FROM `detalle_pedido`  WHERE id_bodega_inventario =$idBodega and id_empresa=$sesion_id_empresa  ";
            $respDetallePedido = mysql_query($sqlDetallePedido);        
            $numFilasDetallePedido = mysql_num_rows($respDetallePedido);

            $sqlDetalleVentas = " SELECT `id_detalle_venta`, `idBodega`, `idBodegaInventario`, `id_empresa` FROM `detalle_ventas`  WHERE idBodegaInventario =$idBodega and id_empresa=$sesion_id_empresa  ";
            $respDetalleVentas = mysql_query($sqlDetalleVentas);        
            $numFilasDetalleVentas = mysql_num_rows($respDetalleVentas);

            $sqlInventariosBodega = " SELECT `id_inventario`, `id_bodega`,  `id_empresa` FROM `inventarios_bodega` WHERE id_bodega =$idBodega and id_empresa=$sesion_id_empresa  ";
            $respInventariosBodega = mysql_query($sqlInventariosBodega);        
            $numFilasInventariosBodega = mysql_num_rows($respInventariosBodega);

            $sqlKardes = " SELECT `id_kardes`, `bodegaInventario`, `total`,  `id_empresa` FROM `kardes`  WHERE bodegaInventario =$idBodega and id_empresa=$sesion_id_empresa  ";
            $resKardex = mysql_query($sqlKardes);        
            $numFilasKardex = mysql_num_rows($resKardex);

          $cantidadTotalRegistros = $numFilasCantBodegas +  $numFilasCentroCosto =  $numFilasDetalleCompras +  $numFilasDetalleEgresos +  $numFilasDetalleIngresos  +  $numFilasDetalleOrdenCompra +  $numFilasDetallePedido +  $numFilasDetalleVentas +  $numFilasInventariosBodega + $numFilasKardex ;

          if($cantidadTotalRegistros==0){
           $sqlBodegas = "delete from bodegas where id='".$idBodega."';";
            $resBodegas= mysql_query($sqlBodegas);        
            if($resBodegas){
                echo '1';
            }else{
                echo '2';
            }

          }else{
            echo '2';
          }


    }
?>     

               




