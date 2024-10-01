<?php
    require_once('../ver_sesion.php');
    //Start session
    session_start();
    //Include database connection details
    require_once('../conexion.php');

    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
    $accion = $_POST['txtAccion'];
    
   

//  echo "aaaa".$accion;
 if($accion == '1'){
        $filas=$_POST['filas'];
        $txtNumeroCama=$_POST['txtNumeroCama'.$filas];
		$txtDescripcion= $_POST['txtDescripcion'.$filas];
	    $sql1 = "SELECT * from habitacion 
		where id_empresa ='".$sesion_id_empresa."' and numeroHabitacion='".$txtNumeroCama."'
		and descripcion='".$txtDescripcion."'   ";
//echo $sql1;
		$resultado = mysql_query($sql1);
		$fila=mysql_num_rows($resultado);
		if ($fila>0)
		{	?>  <div class='alert alert-danger'><p>Habitacion ya existe</p></div> <?php	} 
	else{
        $filas=$_POST['filas'];
//        $id_bodega=$_POST['txtBodega'.$filas
        $id=$filas;
        $numeroCama=$_POST['txtNumeroCama'.$filas];	
        $descripcion=ucwords($_POST['txtDescripcion'.$filas]);
        $estado=$_POST['cmbEstado'.$filas];
//		$paga_iva= $_POST['txtPaga_Iva'.$filas];
	//	echo $paga_iva;
	    $observacion=$_POST['txtObservacion'.$filas];
      
        $sql = "insert into habitacion( numeroHabitacion, descripcion,  estado,     
		observacion,  id_empresa,tipo_habitacion) values 
        ('".$numeroCama."','".$descripcion."','3','".
		$observacion."','".$sesion_id_empresa."','".$estado."'); ";
        // echo $sql;
		$result = mysql_query($sql);
           
        if ($result)
		{  ?>1<?php }
		else 
		{ 
		?> <div class='transparent_ajax_error'><p>Error al guarda en la tabla centro de costo: 	<?php echo mysql_error();?> </p></div> <?php }
        }
    }

   
    if($accion == '2'){
       // GUARDAR MODIFICACION PRODUCTOS PAGINA: productos.php
     try
     {
		
        $filas=$_POST['NumeroFilaSeleccionada'];
			//echo "fila";
			//echo $filas;
        $txtIdHabitacion = $_POST['txtIdHabitacion'.$filas];
        $txtNumeroCama = $_POST['txtNumeroCama'.$filas];
        $txtDescripcion=ucwords($_POST['txtDescripcion'.$filas]);
        $cmbMostrarHabitacion = $_POST['cmbMostrarHabitacion'.$filas];
        //$txtPrecio = $_POST['txtPrecio'.$filas];
		$estado=$_POST['cmbEstado'.$filas];
		//$paga_iva = $_POST['txtPaga_Iva'.$filas];
		$paga_iva ="";
        $txtObservacion = ucwords($_POST['txtObservacion'.$filas]);
		if($txtIdHabitacion != "" && $txtNumeroCama != ""  )
		{                    
		$sql2 = "update habitacion set numeroHabitacion='".$txtNumeroCama."', 
		  descripcion='".$txtDescripcion."', tipo_habitacion='".$estado."',
		  paga_iva='".$paga_iva."', observacion='".$txtObservacion."' , mostrar='".$cmbMostrarHabitacion."' 
		  WHERE idHabitacion='".$txtIdHabitacion."'; ";
          //echo $sql2;
		  $resp2 = mysql_query($sql2) or die("<div class='transparent_ajax_error'><p>Error al modificar el producto: ".mysql_error()."</p></div>");
          // consultas para cambiar el ESTADO de CARGOS a LIBRE
          if($resp2)
		  {
           echo '1';
          }
		  else
		  {
              echo '2';
          }     
        }
    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }
   }

    if($accion == '3'){
       // GUARDAR MODIFICACION PRODUCTOS PAGINA: productos.php
     try
     {
         
         
		
        $filas=$_POST['NumeroFilaSeleccionada'];
	//		echo "fila";
		//	echo $filas;
        $txtIdHabitacion = $_POST['txtIdHabitacion'.$filas];
//        echo "datos";
//		echo $txtIdHabitacion;
		if($txtIdHabitacion != ""  )
		{                    
		    $sql="SELECT `idHabitacion`, `numeroHabitacion`, `descripcion`, `estado`, `observacion`, `id_empresa`, `paga_iva`, `tipo_habitacion` FROM `habitacion` WHERE idHabitacion = '".$txtIdHabitacion."' ";
		    $result = mysql_query($sql);
		    $numeroHabitacion='';
		    while($row = mysql_fetch_array($result) ){
		        $numeroHabitacion = $row['numeroHabitacion'];
		    }
		    	
		
		 $sqlMantenimiento = "SELECT `mantenimientos_id`, `mantenimiento_numero`, `habitacion_id`, `fecha_desde`, `fecha_hasta`, `id_empresa`, `motivo`, `estado_mantenimiento`, `fecha_completado`, `comentarios` FROM `mantenimientos`
                    WHERE habitacion_id ='".$txtIdHabitacion."'  AND estado_mantenimiento!='Completado' ";
         $resultMantenimiento = mysql_query( $sqlMantenimiento);
        $numero_filasMantenimiento = mysql_num_rows($resultMantenimiento);
        if($numero_filasMantenimiento>0){
            echo '4';
            exit;
        }
        
        $sqlReserva = "SELECT * FROM `reservaciones` WHERE habitacion_id ='".$txtIdHabitacion."'  ";
        $resultReserva = mysql_query( $sqlReserva);
        $numero_filasReserva = mysql_num_rows($resultReserva);
        if($numero_filasReserva>0){
            echo '5';
            exit;
        }
        
        $sqlOcupada = "SELECT * FROM `pedidos`
                        INNER JOIN clientes ON clientes.id_cliente = pedidos.id_cliente
                        WHERE numeroHospedaje = ".$numeroHabitacion."
                        AND pedidos.id_empresa='$sesion_id_empresa' ;";
        
        $resultOcupada = mysql_query( $sqlOcupada);
        $numero_filasOcupada = mysql_num_rows($resultOcupada);
        
         if($numero_filasOcupada>0){
            echo '6';
            exit;
        }
		
		$sql2 = "delete from habitacion 
		WHERE idHabitacion='".$txtIdHabitacion."'; ";
        //   echo $sql2;
		  $resp2 = mysql_query($sql2) or die("<div class='transparent_ajax_error'><p>Error al modificar el producto: ".mysql_error()."</p></div>");
          // consultas para cambiar el ESTADO de CARGOS a LIBRE
          if($resp2)
		  {
            echo '1';
          }
		  else
		  {
              echo '2';
          }     
        }else{
            echo '3';
        }
    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }
   }
    


    if($accion == "4")
	{
		echo "opcion 4";
    // VALIDA PARA QUE EL NOMBRE DEL PRODUCTO NO SE REPITA PAGINA: productos.php
		try
		{
			$sql1 = "SELECT descripcion from hab_estado where empresa ='".$sesion_id_empresa."'";

				$resp1 = mysql_query($sql1);
				$entro=0;
				while($row1=mysql_fetch_array($resp1))//permite ir de fila en fila de la tabla
				{
					$var1=$row1["descripcion"];
				}
				$centrocosto2 = strtolower($centrocosto);
				$var2 = strtolower($var1);
				if($var2==$centrocosto2)
				{
					if($var2==""&&$centrocosto2=="")
					{
                     $entro=0;
					}
					else
					{
                      $entro=1;
					}
				}
				echo $entro;
				
		//	if(isset ($_POST['centrocosto']))
			//{
			//	$centrocosto = $_POST['centrocosto'];
 
				
				
				
		//	}

		}
		catch (Exception $e) 
		{
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
		}
    }
   
    
    if($accion == 6){
        $cadena6="";
        try {
            $consulta6="SELECT * FROM hab_estado order by descripcion asc;";
            echo $consulta6;
			$result6=mysql_query($consulta6);
            while($row6=mysql_fetch_array($result6))//permite ir de fila en fila de la tabla
                {
                    $cadena6=$cadena6."?".$row6['id_habEstado']."?".$row6['descripcion'];
                }
            echo $cadena6;

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
    }

    if($accion == 7){
        $numHab = $_POST['hab'];
        $numPedido='';
        $facturado='';
          $sql = "SELECT `pedido`, `numero_pedido`,`id_empresa`, `facturado`, `Mesa_id`, `numeroHospedaje` FROM `pedidos` WHERE id_empresa =$sesion_id_empresa and numeroHospedaje='$numHab' ORDER BY numero_pedido DESC LIMIT 1; ";	
    
       
		$result = mysql_query($sql);
		while($row= mysql_fetch_array($result)){
		    $numPedido = $row['numero_pedido'];
		    $facturado= $row['facturado'];
		}
		if($facturado==1){
		    $numPedido='';
		}
		
		echo $numPedido;
    }
   if($accion == 8){
       // buscador de pedidos segun el numero de habitacion
        if(isset($_POST['queryString'])) {
            $queryString = $_POST['queryString'];
            // Is the string length greater than 0?
            if(strlen($queryString) >0) {
               $query = "SELECT
					pedidos.`pedido` AS pedidos_pedido,
					pedidos.`numero_pedido` AS pedidos_numero_pedido,
					pedidos.`fecha_pedido` AS pedidos_fecha_pedido,
					pedidos.`descripcion` AS pedidos_descripcion,
					pedidos.`Mesa_id` AS pedidos_Mesa_id,
					pedidos.`vendedor_id` AS pedidos_vendedor_id,

					pedidos.`sub0` AS sub0,
					pedidos.`sub12` AS sub12,
					pedidos.`sub_total` AS sub_total,
					pedidos.`total` AS total,
					pedidos.`descuento` AS descuento,
					pedidos.`propina` AS propina,
					pedidos.`numeroHospedaje` AS numeroHospedaje,
                    pedidos.`facturado` AS facturado,
					clientes.`id_cliente` AS clientes_id_cliente,
					clientes.`cedula` AS clientes_cedula,
					clientes.`nombre` AS clientes_nombre,
					clientes.`apellido` AS clientes_apellido,
					clientes.`direccion` AS clientes_direccion,
					clientes.`telefono` AS clientes_telefono

					FROM
						`pedidos` pedidos
					
						INNER JOIN `clientes` clientes
						ON pedidos.`id_cliente` = clientes.`id_cliente`
						
						WHERE pedidos.`id_empresa`='".$sesion_id_empresa."' AND
						pedidos.`numeroHospedaje` LIKE '%".$queryString."%' AND (pedidos.`facturado`is null or `pedidos`.`facturado`!=1);"; 
				// 		echo $query;
                $result = mysql_query($query) or die(mysql_error());

                $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
                if($result) {
                    if($numero_filas == 0){
                        echo "<center><p><div class='alert alert-danger'> No hay resultados con el parámetro ingresado. </label></p></center>";
                    }else{
                        // While there are results loop through them - fetching an Object (i like PHP5 btw!). overTable
                        echo "<table border='1'  class='table table-bordered table-hover' ><tr><th>C&eacute;dula</th><th>Nombre</th><th>#Pedido</th><th>N&uacute;mero</th><th>Facturado</th></tr>";
                        while ($row = mysql_fetch_array($result)) {
                            $facturado = ($row["facturado"]=='1')?'SI':'NO';
                            echo '<tr title="Clic para seleccionar" onClick="cargarPedidoc(\''.$row["pedidos_numero_pedido"].'\',cmbEmi.value);" >';
                            echo "<td>".$row["clientes_cedula"]."</td>";
                            echo "<td>".$row["clientes_nombre"]." ".$row["clientes_apellido"]."</td>";
                            echo "<td>".$row["pedidos_numero_pedido"]."</td>";
                            echo "<td>".$row["numeroHospedaje"]."</td>";
                              echo "<td>".$facturado."</td>";
                            echo "</tr>";
                        }
                        echo"</table> <input type='button' class='btn btn-success' onclick='ocultarListadoHabitaciones()'value='CERRAR' /> ";
                    }
                }else {
                    echo 'ERROR: Hay un problema con la consulta.';
                }
            }else{
                echo 'La longitud no es la permitida.';
                    // Dont do anything.
            } // There is a queryString.
        }else{
            echo 'No hay ningún acceso directo a este script!';
        }
   }
   
   if($accion == 9){
       // Validar que la habitacion no este ocupada;
        $numHab = $_POST['hab'];
        $idVehiculo = trim($_POST['idVehiculo']);
        
        $numPedido='';
        $facturado='';
         
         $sql = "SELECT `pedido`, `numero_pedido`,`id_empresa`, `facturado`, `Mesa_id`, `numeroHospedaje` FROM `pedidos` WHERE id_empresa =$sesion_id_empresa and numeroHospedaje='".$numHab."' ORDER BY numero_pedido DESC LIMIT 1; ";	
    
       
		$result = mysql_query($sql);
		$numFiLas= mysql_num_rows($result);
		if($numFiLas>0){
		    while($row= mysql_fetch_array($result)){
		    $numPedido = $row['numero_pedido'];
		    $facturado= $row['facturado'];
		    }
		    
		
		     $sqlHabitacion ="SELECT `idHabitacion`, `numeroHabitacion`, `descripcion`, `estado`, `observacion`, `id_empresa`, `paga_iva` FROM `habitacion` WHERE numeroHabitacion='".$numHab."' and  id_empresa =$sesion_id_empresa  ";
		    $resultHabitacion=  mysql_query($sqlHabitacion);
    		while($rowH = mysql_fetch_array($resultHabitacion) ){
    		    $estadoHabitacion = $rowH['estado'];
    		}
		  
		
		
// 		echo '$estadoHabitacion='.$estadoHabitacion.'|';
		if($facturado==1 && $estadoHabitacion==3){
		    echo '1';// fue facturado y esta libre la habitacion
		}else if($facturado==1 && $estadoHabitacion==6){
		    echo '2';// fue facturado y esta en limpieza
		}else if($facturado==1 && $estadoHabitacion==4){
		    echo '3'; //  fue facturado y esta ocupada
		}else if($facturado==1 && $estadoHabitacion==5){
		    echo '4'; //  fue facturado y esta mantenimiento
		}else if($facturado==1 && $estadoHabitacion==7){
		    echo '1'; //  fue facturado y esta reservada
		}else if(trim($numPedido)!='' && $estadoHabitacion==4 && $facturado!=1){
		    echo '7'; //  existe el pedido y se puede modificar
		}else if( $idVehiculo!=''){
		    echo '1'; //  si el ultimo pedido esta  facturado y es un vehiculo se puede guardar
		}else{
		    	echo 6;
		}
		    
		}else{
		    echo '1';
		}
    }
    
    if($accion == 10){
        // pasar de limpieza a libre las habitaciones
        
        $idHabitacion= $_POST['idHabitacion'];
        
        $sqlLibre="UPDATE `habitacion` SET `estado`='3' WHERE idHabitacion=$idHabitacion  ";
        $resultLibre= mysql_query($sqlLibre);
      
        
         $reserva= $_POST['reserva'];
         if($reserva!='0' && trim($reserva)!=''){
              $sqlLibre2="UPDATE reservaciones SET `estado_reserva`='Libre' WHERE `reserva_id`=$reserva  ";
                $resultLibre2= mysql_query($sqlLibre2);
                if($resultLibre2){
                    echo '1';
                    exit;
                }else{
                     echo '2';
                     exit;
                }
         }
         
         $mantenimiento= $_POST['mantenimiento'];
         if($mantenimiento!='0' && trim($mantenimiento)!=''){
             $dia = date('Y-m-d');
              $sqlMantenimiento = "UPDATE `mantenimientos` SET `estado_mantenimiento`='Completado',`fecha_completado`='".$dia."' WHERE `mantenimientos_id`=$mantenimiento  ";
                $resultMantenimiento= mysql_query( $sqlMantenimiento );
                if($resultMantenimiento){
                    echo '1';
                    exit;
                }else{
                     echo '2';
                     exit;
                }
         }
         
        if($resultLibre){
                    echo '1';
                    exit;
                }else{
                     echo '2';
                     exit;
                }
         
    }
  
  if($accion == 11){
        // pasar de limpieza a libre las habitaciones
        $fechaDesde = trim($_POST['tabla_fecha_desde']);
        $fechaHasta = trim($_POST['tabla_fecha_hasta']);

     

        $idHabitacion= trim($_POST['idHabitacion']);
         $idCliente= $_POST['idCliente'];
        $txtCedulaFVC = $_POST['txtCedulaFVC'];
        $fechaDesde =( $fechaDesde=='')?$_POST['fechaDesde']: $fechaDesde;
        $fechaHasta = ( $fechaHasta=='')?$_POST['fechaHasta']: $fechaHasta ;
        $noches = $_POST['noches'];
        $adultos = (trim($_POST['adultos'])!='')?$_POST['adultos']:0;
        $ninos = (trim($_POST['ninos'])!='')?$_POST['ninos']:0;
        $idServicio = (trim($_POST['idServicio'])!='')?$_POST['idServicio'] :0 ;
        
        $numeroReserva=0;
        $sqlR="SELECT `reserva_id`, `numero_reserva` FROM `reservaciones` WHERE id_empresa =$sesion_id_empresa ";
        $resultR = mysql_query($sqlR);
        while($rowR =mysql_fetch_array($resultR)){
            $numeroReserva = $rowR['numero_reserva'];
        }
        $numeroReserva++;
            if($idCliente!='' &&  $idHabitacion!=''){
                 $sqlReserva = "INSERT INTO `reservaciones`( `cliente_id`, `habitacion_id`, `fecha_entrada`, `fecha_salida`, `estado_reserva`, noches, adultos, ninos, id_servicio, numero_reserva,id_empresa ) VALUES ('".$idCliente."','".$idHabitacion."','".$fechaDesde."','".$fechaHasta."','Por confirmar','".$noches."','".$adultos."','".$ninos."','".$idServicio."','".$numeroReserva."','".$sesion_id_empresa."')";
                $resultReserva = mysql_query($sqlReserva);
                    
                    if($resultReserva){
                      $sqlLibre="UPDATE `habitacion` SET `estado`='7' WHERE idHabitacion=$idHabitacion  ";
                    $resultLibre= mysql_query($sqlLibre);
                    if($resultLibre){
                        echo '1';
                    }else{
                         echo '2';
                    }  
                    }else{
                        echo '2';
                    }
                
            }else{
                echo '3';
            }
    }
  
    if($accion == 12){
        //modificar reservacion
        
        $id_reserva= $_POST['id_reserva'];
        $idHabitacion= $_POST['idHabitacion'];
         $idCliente= $_POST['txtClientesF'];
        $estado_reserva = $_POST['estado_reserva'];
        $fechaDesde = $_POST['fechaDesdeF'];
        $fechaHasta = $_POST['fechaHastaF'];
        $noches = $_POST['nochesF'];
        $adultos = (trim($_POST['adultosF'])!='')?$_POST['adultosF']:0;
        $ninos = (trim($_POST['ninosF'])!='')?$_POST['ninosF']:0;
        $idServicio = (trim($_POST['idServicio'])!='')?$_POST['idServicio'] :0 ;
        
        
        $sqlValidarFechas = "SELECT
            habitacion.*,
            reservaciones.habitacion_id,
           reservaciones.reserva_id AS reserva_id,
        	reservaciones.numero_reserva AS numero_reserva,
           	reservaciones.fecha_entrada AS fecha_entrada,
           reservaciones.fecha_salida AS fecha_salida,
            reservaciones.estado_reserva,
            clientes.nombre,
            clientes.apellido,
            clientes.cedula
        FROM
            `habitacion`
        INNER JOIN reservaciones ON habitacion.idHabitacion = reservaciones.habitacion_id AND(
                (
                    DATE_FORMAT(fecha_entrada, '%Y-%m-%d') >= '".$fechaDesde."' AND DATE_FORMAT(fecha_entrada, '%Y-%m-%d') <= '".$fechaHasta."'
                ) OR(
                    DATE_FORMAT(fecha_salida, '%Y-%m-%d') >= '".$fechaDesde."' AND DATE_FORMAT(fecha_salida, '%Y-%m-%d') <= '".$fechaHasta."'
                ) OR(
                    DATE_FORMAT(fecha_entrada, '%Y-%m-%d') <= '".$fechaDesde."' AND DATE_FORMAT(fecha_entrada, '%Y-%m-%d') >= '".$fechaHasta."'
                )
            )
        INNER JOIN clientes ON clientes.id_cliente = reservaciones.cliente_id
        WHERE
            habitacion.id_empresa =$sesion_id_empresa and reservaciones.reserva_id !=$id_reserva and habitacion.idHabitacion=$idHabitacion ";
        $resultValidarFechas = mysql_query($sqlValidarFechas);
        $numFilasValidarFechas = mysql_num_rows($resultValidarFechas);
        if($numFilasValidarFechas>0){
            //existe reservaciones entre esas fechas
            echo '33';
            exit;
        }
        
    
            if($idCliente!=''){
                 $sqlReserva = "UPDATE `reservaciones` SET `cliente_id`='".$idCliente."',`fecha_entrada`='".$fechaDesde."',`fecha_salida`='".$fechaHasta."',`estado_reserva`='".$estado_reserva."',`noches`='".$noches."',`adultos`='".$adultos."',`ninos`='".$ninos."',`id_servicio`='".$idServicio."' WHERE reserva_id=$id_reserva ";
                $resultReserva = mysql_query($sqlReserva);
                    
                    if($resultReserva){
                      $sqlLibre="UPDATE `habitacion` SET `estado`='7' WHERE idHabitacion=$idHabitacion  ";
                    $resultLibre= mysql_query($sqlLibre);
                    if($resultLibre){
                        echo '4';
                    }else{
                         echo '5';
                    }  
                    }else{
                        echo '2';
                    }
                
            }else{
                echo '3';
            }
       
     
    }
    
        if($accion == 13){
        // pasar a mantenimiento 
        
        $idHabitacion= $_POST['idHabitacion'];
        
        $sqlLibre="UPDATE `habitacion` SET `estado`='5' WHERE idHabitacion=$idHabitacion  ";
        $resultLibre= mysql_query($sqlLibre);
        if($resultLibre){
           echo '1'; 
        }else{
            echo '2';
        }
         
    }
    
      if($accion == 14){
        //actualizar fecha de reserva
        
        $reserva_id= $_POST['id'];
         $habitacion=0;
        $fecha_entrada = $_POST['newStart'];
        $fecha_entrada = date('Y-m-d', strtotime($fecha_entrada));

        $fecha_salida = $_POST['newEnd'];
        $fecha_salida = date('Y-m-d', strtotime($fecha_salida));
        
      $sql = "SELECT `reserva_id`, `numero_reserva`, `cliente_id`, `habitacion_id` FROM `reservaciones` WHERE reserva_id=$reserva_id ";
    $result = mysql_query($sql);
      
    	while($row = mysql_fetch_array($result) ){
		    $habitacion = $row['habitacion_id'];
		}
		
    
      $sqlValidaFecha= "SELECT
    `reserva_id`,
    `numero_reserva`,
    `cliente_id`,
    `habitacion_id`,
    `fecha_entrada`,
    `fecha_salida`,
    `estado_reserva`,
    `noches`,
    `adultos`,
    `ninos`,
    `id_servicio`,
    `id_empresa`
FROM
    `reservaciones`
WHERE
    id_empresa =$sesion_id_empresa AND reserva_id !=$reserva_id AND habitacion_id =$habitacion AND(
        (
            DATE_FORMAT(
                reservaciones.fecha_entrada,
                '%Y-%m-%d'
            ) <= '".$fecha_entrada."' AND DATE_FORMAT(
                reservaciones.fecha_salida,
                '%Y-%m-%d'
            ) >= '".$fecha_entrada."'
        ) OR(
            DATE_FORMAT(
                reservaciones.fecha_entrada,
                '%Y-%m-%d'
            ) <= '".$fecha_salida."' AND DATE_FORMAT(
                reservaciones.fecha_salida,
                '%Y-%m-%d'
            ) >= '".$fecha_salida."'
        )
    );";
        $resultValidacion = mysql_query($sqlValidaFecha);
        $numFilas= mysql_num_rows($resultValidacion);
    
    if($numFilas==0){
          $sqlLibre="UPDATE `reservaciones` SET `fecha_entrada`='".$fecha_entrada."',`fecha_salida`='".$fecha_salida."' WHERE reserva_id=$reserva_id AND id_empresa=$sesion_id_empresa ";
        $resultLibre= mysql_query($sqlLibre);
        if($resultLibre){
           echo '1'; 
        }else{
            echo '2';
        }
    }else{
         echo    '3';
    }
    
      
         
    }
    
    if($accion == 15){
        $id_reserva = $_POST['id_reserva'];
        $sqlReserva = "SELECT
    reservaciones.`reserva_id`,
    reservaciones.`numero_reserva`,
    reservaciones.`cliente_id`,
    reservaciones.`habitacion_id`,
    reservaciones.`fecha_entrada`,
    reservaciones.`fecha_salida`,
    reservaciones.`estado_reserva`,
    reservaciones.`noches`,
    reservaciones.`adultos`,
    reservaciones.`ninos`,
    reservaciones.`id_servicio`,
    reservaciones.`id_empresa`,
    clientes.id_cliente,
    clientes.nombre,
    clientes.apellido,
    productos.producto,
    productos.precio1
FROM
    `reservaciones`
INNER JOIN clientes ON clientes.id_cliente = reservaciones.cliente_id
INNER JOIN productos ON reservaciones.id_servicio = productos.id_producto
WHERE
    reservaciones.id_empresa=$sesion_id_empresa AND  reservaciones.`reserva_id`=$id_reserva  ;";
    $resultReserva = mysql_query($sqlReserva);
    $id_venta = $id_reserva;
    $id_cliente='Indeterminado';
    $txtNombreFVC='Indeterminado';
    $numero_factura = '1';
    $fecha_venta = date('Y-m-d');
    
    while($rowR = mysql_fetch_array($resultReserva)){
          $id_cliente= $rowR['cliente_id'];
        $txtNombreFVC= $rowR['nombre'].' '.$rowR['apellido'] ;
        $numero_factura =  $rowR['numero_reserva'];
         $fecha_venta_reserva =  $rowR['fecha_entrada'];
    }
        
       
   
      
        $total = (trim($_POST['txtDebeFP'])!='')?$_POST['txtDebeFP']:$_POST['txtPagoFP'];
        $sub_total=(trim($_POST['txtSubTotal'])!='')?$_POST['txtDebeFP']:$_POST['txtPagoFP'];
        $descuento=0;
        $propina = 0;
       
        $haber2= (trim($_POST['txtSubTotal'])!='')?$_POST['txtDebeFP']:$_POST['txtTotalIvaFVC']; 
       
             
        
					//permite sacar el numero_asiento de libro_diario
			        $tot_costo=0;
					try
					{
					  $sqlMNA="SELECT
						max(numero_asiento) AS max_numero_asiento,
						periodo_contable.`id_periodo_contable` AS periodo_contable_id_periodo_contable,
						periodo_contable.`fecha_desde` AS periodo_contable_fecha_desde,
						periodo_contable.`fecha_hasta` AS periodo_contable_fecha_hasta,
						periodo_contable.`estado` AS periodo_contable_estado,
						periodo_contable.`ingresos` AS periodo_contable_ingresos,
						periodo_contable.`gastos` AS periodo_contable_gastos,
						periodo_contable.`id_empresa` AS periodo_contable_id_empresa
					  FROM
						 `periodo_contable` periodo_contable INNER JOIN `libro_diario` libro_diario ON periodo_contable.`id_periodo_contable` = libro_diario.`id_periodo_contable`
						 WHERE periodo_contable.`id_empresa` ='".$sesion_id_empresa."' GROUP BY periodo_contable.`id_periodo_contable` ;";
						$resultMNA=mysql_query($sqlMNA) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
						$numero_asiento=0;
						while($rowMNA=mysql_fetch_array($resultMNA))//permite ir de fila en fila de la tabla
						{
							$numero_asiento=$rowMNA['max_numero_asiento'];
						}
						$numero_asiento++;
					}
					 catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }


					try
					{
						$sqlm="Select max(id_libro_diario) From libro_diario";
						$resultm=mysql_query($sqlm) or die('<div class="transparent_ajax_error"><p>Error : '.mysql_error().' </p></div>  ');
						$id_libro_diario=0;
						while($rowm=mysql_fetch_array($resultm))//permite ir de fila en fila de la tabla
						{
							$id_libro_diario=$rowm['max(id_libro_diario)'];
						}
						$id_libro_diario++;

					}catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
				
					//Fin permite sacar el id maximo de libro_diario
		
		
		$tipo_comprobante = "Diario"; 
				
					try
					{
						$tipoComprobante = $tipo_comprobante;
						$consulta7="SELECT
							max(numero_comprobante) AS max_numero_comprobante
						FROM
							`comprobantes` comprobantes
							WHERE comprobantes.`id_empresa` = '".$sesion_id_empresa."' AND  comprobantes.`tipo_comprobante` = '".$tipoComprobante."' ;";
								$result7=mysql_query($consulta7) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
								$numero_comprobante = 0;
							while($row7=mysql_fetch_array($result7))//permite ir de fila en fila de la tabla
							{
								$numero_comprobante=$row7['max_numero_comprobante'];
							}
							$numero_comprobante ++;
					}
					catch (Exception $e) 
					{
						// Error en algun momento.
					   ?> <div class="transparent_ajax_error"><p>Error max_numero_comprobante: <?php echo "".$e ?></p></div> <?php
					}
					
					try
					{
						$sqlCM="Select max(id_comprobante) From comprobantes; ";
						$resultCM=mysql_query($sqlCM) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
						$id_comprobante=0;
						while($rowCM=mysql_fetch_array($resultCM))//permite ir de fila en fila de la tabla
						{
							$id_comprobante=$rowCM['max(id_comprobante)'];
						}
						$id_comprobante++;

					}	catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
							  
				//FIN SACA EL ID MAX DE COMPROBANTES
				
				
			        
					$fecha= date("Y-m-d h:i:s");
					$descripcion = "Anticipo #".$numero_factura." realizada a ".$txtNombreFVC;
					
					$debe = $total;
					$debe2 = $descuento;
					$total_debe = $debe + $debe2;
					
					$haber1 = $sub_total;
				// 	$haber2 = $_POST['txtTotalIvaFVC'];
					
					$total_haber = $haber1 + $haber2 + $propina;
					
					$tipo_mov="R";
	
				//GUARDA EN  COMPROBANTES
					$sqlC = "insert into comprobantes ( tipo_comprobante, numero_comprobante, id_empresa) values ('".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
					$respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: comprobantes, '.mysql_error().' </p></div>  ');
					$id_comprobante=mysql_insert_id();
				//GUARDA EN EL LIBRO DIARIO
					$sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha, total_debe,
					total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta) 
					values ('".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha_venta."',	'".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."',
					'".$id_comprobante."','".$tipo_mov."','".$numero_factura."' )";
					$resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: LIBRO DIARIO, '.mysql_error().' </p></div>  ');
					$id_libro_diario=mysql_insert_id();
				
					$idPlanCuentas[1] = '';
					$idPlanCuentas[2] = '';
					$idPlanCuentas[3] = '';
					$debeVector[1] = 0;
					$debeVector[2] = 0;
					$debeVector[3] = 0;
					$haberVector[1] = 0;
					$haberVector[2] = 0;
					$haberVector[3] = 0;		
					
					
					$lin_diario=0;
                    
                    	try 
				{
                    $sqlAnticipo="SELECT
                        enlaces_compras.`id` AS enlaces_compras_id,
                        enlaces_compras.`nombre` AS enlaces_compras_nombre,
                        enlaces_compras.`cuenta_contable` AS enlaces_compras_id_plan_cuenta,
                        enlaces_compras.`id_empresa` AS enlaces_compras_id_empresa,
                        enlaces_compras.`tipo_cpra` AS enlaces_compras_tipo_mov_cpra,
                        plan_cuentas.id_plan_cuenta,
                        plan_cuentas.codigo AS codigo_plan_cuentas
                    FROM
                        enlaces_compras
                    INNER JOIN plan_cuentas ON plan_cuentas.id_plan_cuenta = enlaces_compras.cuenta_contable
                    WHERE
                        enlaces_compras.`tipo_cpra` = 19 AND enlaces_compras.id_empresa ='".$sesion_id_empresa."'  ";
                    $resultAnticipo=mysql_query($sqlAnticipo);
                    $idcodigo_anticipo=0;
                    while($rowA=mysql_fetch_array($resultAnticipo))
                    {
                        $idcodigo_anticipo=$rowA['id_plan_cuenta'];
                    }
                    // $idcodigo_v;
				}
				catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }
                    
	$estadoCC="Pendientes";
							
							$total_x_pagar=$total;
							$formas_pago_id_plan_cuenta=$idcodigo_anticipo;
							$cuentaxpagar="SI";
							$sqlm2="Select max(id_cuenta_por_pagar) From cuentas_por_pagar;";
							$resultm2=mysql_query($sqlm2) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
							$id_cuenta_por_pagar=0;
							while($rowm2=mysql_fetch_array($resultm2))//permite ir de fila en fila de la tabla
							{
								$id_cuenta_por_pagar=$rowm2['max(id_cuenta_por_pagar)'];
							}
							$id_cuenta_por_pagar++;
							
							$cmbTipoDocumentoFVC="Reserva No.";
							$sql3 = "insert into cuentas_por_pagar (tipo_documento, numero_compra, referencia,  
							valor, saldo,numero_asiento,fecha_vencimiento,  id_proveedor,id_cliente ,id_plan_cuenta, id_empresa,
							id_compra, estado,fecha_pago) " . "values 
							('".$cmbTipoDocumentoFVC."','".$numero_factura."','".
							$txtNombreFVC.", ".$cmbTipoDocumentoFVC.",".$numero_factura."', '".
							$total_x_pagar."','".$total_x_pagar."','','".$fecha_venta_reserva."',null,'".$id_cliente."','".
							$formas_pago_id_plan_cuenta."', '".$sesion_id_empresa."',
							'".$id_venta."', '".$estadoCC."', '".$fecha_venta."');";

            				$resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>Error al guardar en cuentas_por_pagar: '.mysql_error().' </p></div>  ');
            				$id_cuenta_por_pagar=mysql_insert_id();
            				

		            $valor[$lin_diario]=0;
					$ident=0;
					$txtContadorFilas=4;
					for($i=1; $i<=$txtContadorFilas; $i++)				
					{
						if($_POST['txtCodigoS'.$i] >=1)
						{	
						    
						    
							$lin_diario=$lin_diario+1;
							$idPlanCuentas[$lin_diario]=$_POST['txtCodigoS'.$i];
							$debeVector[$lin_diario]=$_POST['txtValorS'.$i];
							$haberVector[$lin_diario]=0; 
							
							$formaPagoId[$lin_diario]=$_POST['formaPagoId'.$i];
							
                            $sqlforma = "INSERT INTO `cobrospagos`( `id_forma`, `documento`, `id_factura`,id_empresa,valor,tipo,porcentaje) 
                            VALUES     ('".$formaPagoId[$i]."','5','".$id_venta."','".$sesion_id_empresa."','".$debeVector[$i]."','".$_POST['txtTipo1'.$i]."', NULL );";
				            // echo $sqlforma;
				            $respForma = mysql_query($sqlforma) or 	die('<div class="transparent_ajax_error"><p>Error 11 : '.mysql_error().' </p></div>  ');
  
						}
						
						if ($_POST['txtTipo1'.$i]=='4')
						{
							$total=$_POST['txtValorS'.$i];
							$txtCuotasTP=$_POST['txtCuotaS'.$i];
							$formas_pago_id_plan_cuenta=$_POST['txtCodigoS'.$i];		
						}

						if ($_POST['txtTipo1'.$i]=='17')
						{
						    
						    echo "TRANSFERENCIA</br>";
						                  //permite sacar el id maximo de bancos
                                try{
                                    $sqlmaxb="Select max(id_bancos) From bancos;";
                                    $resultmaxb=mysql_query($sqlmaxb);
                                    $id_bancos=0;
                                    while($rowmaxb=mysql_fetch_array($resultmaxb))//permite ir de fila en fila de la tabla
                                    {
                                        $id_bancos=$rowmaxb['max(id_bancos)'];
                                    }
                                    $id_bancos++;
        
                                }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
        
                                try {
                                    //permite sacar el id maximo de la tabla detalle_bancos
                                    $sqlmaxdb="Select max(id_detalle_banco) From detalle_bancos;";
                                    $resultmaxdb=mysql_query($sqlmaxdb);
                                    $id_detalle_banco=0;
                                    while($rowmaxdb=mysql_fetch_array($resultmaxdb))//permite ir de fila en fila de la tabla
                                    {
                                        $id_detalle_banco=$rowmaxdb['max(id_detalle_banco)'];
                                    }
                                    $id_detalle_banco++;
        
                                }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la insercion de la tabla mayorizacion: <?php echo "".$ex ?></p></div> <?php }
                                    
                                    
                                    
                                    $cmbTipoDocumento='Transferencia';
                                    $txtNumeroDocumento=$_POST['txtNumDocumento'];
                                    $txtDetalleDocumento="Transferencia de ".$txtNombreFVC ;
                                    $txtFechaEmision=$fecha_venta;
                                    $txtFechaVencimiento=$fecha_venta;
                                    $saldo_conciliado = 0;
                                    $valorConciliacion = $_POST['txtValorS'.$i];
                                    $estado = "No Conciliado";
                                    
                                    $sqlb2 = "SELECT * FROM bancos WHERE id_plan_cuenta ='".$_POST['txtCodigoS'.$i]."' 
                                    AND id_periodo_contable = '".$sesion_id_periodo_contable."';";
                                    $resultb2=mysql_query($sqlb2);
                                    while($rowb2=mysql_fetch_array($resultb2))//permite ir de fila en fila de la tabla
                                    {
                                        $id_bancos2=$rowb2['id_bancos'];
                                    }    
                                    
                                    $numero_fil = mysql_num_rows($resultb2);
                                    
                                    if($numero_fil > 0){
                                        
                                        $sqlDB = "insert into detalle_bancos ( tipo_documento, numero_documento, detalle, valor, fecha_cobro, fecha_vencimiento, id_bancos, estado, id_libro_diario) 
                                        values ('".$cmbTipoDocumento."','".$txtNumeroDocumento."','".$txtDetalleDocumento."','".$valorConciliacion."','".$txtFechaEmision."','".$txtFechaVencimiento."','".$id_bancos2."', '".$estado."', '".$id_libro_diario."');";
                                        $resultDB=mysql_query($sqlDB);
            							$id_detalle_banco=mysql_insert_id();
            							echo "trans</br>".$sqlDB."</br>";
                                    }else {
                                        
                                        $sqlB = "insert into bancos ( id_plan_cuenta, saldo_conciliado, id_periodo_contable) values 
                                        ('".$_POST['txtCodigoS'.$i]."','".$saldo_conciliado."', '".$sesion_id_periodo_contable."');";
                                        $resultB=mysql_query($sqlB);
            							$id_bancos=mysql_insert_id();
                                        $sqlDB = "insert into detalle_bancos ( tipo_documento, numero_documento, detalle, valor, fecha_cobro, fecha_vencimiento, id_bancos, estado, id_libro_diario) 
                                        values ('".$cmbTipoDocumento."','".$txtNumeroDocumento."','".$txtDetalleDocumento."','".$valorConciliacion."','".$txtFechaEmision."','".$txtFechaVencimiento."','".$id_bancos2."', '".$estado."', '".$id_libro_diario."');";
                                        $resultDB=mysql_query($sqlDB);
            							$id_detalle_banco=mysql_insert_id();
            							echo "bancos</br>".$sqlB."</br>";
            							echo "detalle</br>".$sqlDB."</br>";
                                    }
						}
					}
					

					$tot_ventas=0;
					$tot_servicios=0;
					$tot_costo=0;
					if(trim($idcodigo_anticipo)!=''){
					    $lin_diario=$lin_diario+1;
    					$idPlanCuentas[$lin_diario]= $idcodigo_anticipo;
    					$debeVector[$lin_diario]=0;
    					$haberVector[$lin_diario]=$total;
					}


					for($i=1; $i<=$lin_diario; $i++)
					{
					    

						if ($idPlanCuentas[$i] !="" and ($debeVector[$i]!=0 or $haberVector[$i]!=0 ))
						{
						//permite sacar el id maximo de detalle_libro_diario

						//GUARDA EN EL DETALLE LIBRO DIARIO
						$sqlDLD = "insert into detalle_libro_diario ( id_libro_diario, 	id_plan_cuenta,debe, haber, id_periodo_contable) values 
							('".$id_libro_diario."',		'".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."',	'".$sesion_id_periodo_contable."');";
					
							$resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
							$id_detalle_libro_diario=mysql_insert_id();		
				// 			echo "DETALLE==".$sqlDLD."</br>";
           							// CONSULTAS PARA GENERAR LA MAYORIZACION
							$sql5 = "SELECT * FROM mayorizacion WHERE id_plan_cuenta ='".$idPlanCuentas[$i]."' AND id_periodo_contable = '".$sesion_id_periodo_contable."';";
							$result5=mysql_query($sql5);
							while($row5=mysql_fetch_array($result5))//permite ir de fila en fila de la tabla
							{
								$id_mayorizacion=$row5['id_mayorizacion'];
							}
							$numero = mysql_num_rows($result5); // obtenemos el número de filas
							if($numero > 0)
							{
									   // si hay filas
							}
							else 
							{
								// no hay filas
								//INSERCION DE LA TABLA MAYORIZACION
								try 
								{
									//permite sacar el id maximo de la tabla mayorizacion
									$sqli6="Select max(id_mayorizacion) From mayorizacion";
									$resulti6=mysql_query($sqli6);
									$id_mayorizacion=0;
									while($row6=mysql_fetch_array($resulti6))//permite ir de fila en fila de la tabla
									{
										$id_mayorizacion=$row6['max(id_mayorizacion)'];
									}
									$id_mayorizacion++;

								$sql6 = "insert into mayorizacion ( id_plan_cuenta, id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
									$result6=mysql_query($sql6);
									$id_mayorizacion=mysql_insert_id();
								}
								catch(Exception $ex) 
								{ ?> <div class="transparent_ajax_error">
									<p>Error en la insercion de la tabla mayorizacion: 
									<?php echo "".$ex ?></p></div> <?php }
								// FIN DE MAYORIZACION
							}
						}
					}
					if($resp2 && $resp3){
					    echo '1';
					}else{
					    echo '2';
					}
				}
    
   if($accion == 16){
    $response= array();
    $fecha= date("Y-m-d");
    $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
    $idCliente= $_POST['idCliente'];
    $idFactura= trim($_POST['idFactura']);
    $valorPagado = 0;

    if($idFactura!=''){
        $sqlFactura="SELECT
        `id`,
        `id_forma`,
        `documento`,
        `id_factura`,
        `id_empresa`,
        `valor`,
        `tipo`,
        `porcentaje`,
        `fecha_registro`,
        `numero_retencion`,
        `autorizacion`
    FROM
        `cobrospagos`
    WHERE
        id_empresa =$sesion_id_empresa AND documento = '0' AND id_factura =$idFactura AND tipo = '18'";
        $resultFactura = mysql_query($sqlFactura);
        $numFilasFacturar= mysql_num_rows($resultFactura);
        
          if($numFilasFacturar>0){
              while($rowCob = mysql_fetch_array($resultFactura)){
                  $valorPagado = $rowCob['valor'];
                }        
          }
    }
    $response['cobrosPagos']=$valorPagado;
      
      $sql="SELECT
            SUM(saldo) AS saldo,
            SUM(valor) AS valor
        FROM
            `cuentas_por_pagar`
        WHERE
            cuentas_por_pagar.id_cliente =$idCliente AND cuentas_por_pagar.saldo > 0 AND cuentas_por_pagar.id_empresa =$sesion_id_empresa
       ;";
      $result = mysql_query($sql);
      $saldo=0;
      while($row = mysql_fetch_array($result)){
          $saldo = $row['saldo'];
      }
  
      $response['saldo']=$saldo;
      
      $sqlCta="SELECT
    formas_pago.`id_forma_pago` AS formas_pago_id,
    formas_pago.`nombre` AS formas_pago_nombre,
    formas_pago.`id_plan_cuenta` AS formas_pago_id_plan_cuenta,
    formas_pago.`id_tipo_movimiento` AS formas_pago_tipo,
    plan_cuentas.`codigo` AS plan_cuentas_id_plan_cuenta
FROM
    `formas_pago` formas_pago
INNER JOIN `plan_cuentas` plan_cuentas ON
    formas_pago.`id_plan_cuenta` = plan_cuentas.`id_plan_cuenta` AND plan_cuentas.`id_empresa` =$sesion_id_empresa
INNER JOIN `tipo_movimientos` tipo_movimientos ON
    formas_pago.`id_tipo_movimiento` = tipo_movimientos.`id_tipo_movimiento`
WHERE
    formas_pago.`id_empresa` =$sesion_id_empresa AND tipo_movimientos.id_tipo_movimiento = 18;";
        $resultCta = mysql_query($sqlCta);
        $cont=1;
        while($row = mysql_fetch_array($resultCta)){
            $funcion ='fill10_FP_Vtas(\''.$cont.'\','.$row["formas_pago_id_plan_cuenta"].',\''.$row["formas_pago_id_plan_cuenta"]."*".$row["formas_pago_nombre"]." "."*".$row["formas_pago_nombre"]."*".$row["plan_cuentas_id_plan_cuenta"]."*".$fecha."*".$row["formas_pago_tipo"]."*".$sesion_tipo_empresa."*".$row["formas_pago_id"].'\')';
        }
      $response['funcion']=$funcion;
      echo json_encode($response);
  }   
  
   if($accion == 17){
       //cargar tipos de habitacion 
        $cadena6="";
        try {
            $consulta6="SELECT `id_tipo`, `descripcion_tipo`, `id_empresa` FROM `tipo_habitacion` WHERE 1 order by descripcion_tipo asc;";
            echo $consulta6;
			$result6=mysql_query($consulta6);
            while($row6=mysql_fetch_array($result6))//permite ir de fila en fila de la tabla
                {
                    $cadena6=$cadena6."?".$row6['id_tipo']."?".$row6['descripcion_tipo'];
                }
            echo $cadena6;

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
    }
    
       if($accion == 19){
    $cadena="";
    $codigo = $_POST['codigo'];
    //consulta
     try {
        $consulta5="SELECT `id_cliente`, `nombre`, `apellido` FROM `clientes` WHERE id_empresa=$sesion_id_empresa ";
         $result=mysql_query($consulta5);
         while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
             {
                 $cadena=$cadena."?".$row['id_cliente']."?".$row['nombre'].' '.$row['apellido'];
             }
        echo "".$cadena;

     }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
   }
   if($accion == 20){
    $idHabitacion = $_POST['idHabitacion'];
    $fechaDesdeM = $_POST['fechaDesdeM'];
    $fechaHastaM = $_POST['fechaHastaM'];
    $motivo_mantenimiento  = $_POST['motivo_mantenimiento'];
    $fecha_completado = trim(['fecha_completado']);
    $comentarios = $_POST['comentarios'];
    $estado_mantenimiento = $_POST['estado_mantenimiento'];
 
    //consulta
     try {
        $sql="SELECT `mantenimientos_id`, MAX(`mantenimiento_numero`) as mantenimiento_numero FROM `mantenimientos` WHERE habitacion_id =$idHabitacion AND id_empresa = $sesion_id_empresa";
        $result= mysql_query($sql);
        $numero=0;
        while($row = mysql_fetch_array($result)){
            $numero = $row['mantenimiento_numero'];
        }
        $numero++;
    $fecha_completado = ($fecha_completado!='')?$fecha_completado:'0000-00-00';
         $consulta5="INSERT INTO `mantenimientos`( `mantenimiento_numero`, `habitacion_id`, `fecha_desde`, `fecha_hasta`, `id_empresa`, `motivo`, `estado_mantenimiento`, `fecha_completado`, `comentarios`) VALUES ('".$numero."','".$idHabitacion."','".$fechaDesdeM."','".$fechaHastaM."','".$sesion_id_empresa."','".$motivo_mantenimiento."','".$estado_mantenimiento."','".$fecha_completado."','".$comentarios."') ";
         $result=mysql_query($consulta5);
        if($result){
            echo '1';
        }else{
            echo '2';
        }

     }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
   }
   if($accion == 21){
    $idHabitacion = $_POST['idHabitacion'];
    $fechaDesdeM = $_POST['fechaDesdeM'];
    $fechaHastaM = $_POST['fechaHastaM'];
    $motivo_mantenimiento  = $_POST['motivo_mantenimiento'];
    $fecha_completado = $_POST['fecha_completado'];
    $comentarios = $_POST['comentarios'];
    $estado_mantenimiento = $_POST['estado_mantenimiento'];
    $id_mantenimiento = $_POST['id_mantenimiento'];
    //consulta
     try {
   
        $consulta5="UPDATE `mantenimientos` SET `fecha_desde`='".$fechaDesdeM."',`fecha_hasta`='".$fechaHastaM."',`id_empresa`='".$sesion_id_empresa."',`motivo`='".$motivo_mantenimiento."',`estado_mantenimiento`='".$estado_mantenimiento."',`fecha_completado`='".$fecha_completado."',`comentarios`='".$comentarios."' WHERE mantenimientos_id =$id_mantenimiento ";
         $result=mysql_query($consulta5);
        if($result){
            echo '1';
        }else{
            echo '2';
        }

     }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
   }
   
        if($accion == 22){
   
    $idPedido = $_POST['idPedido'];
    $nuevaHabitacion = $_POST['nuevaHabitacion'];
    $antiguaHabitacion = $_POST['antiguaHabitacion'];
      
    $consulta5="UPDATE `pedidos` SET `numeroHospedaje`='".$nuevaHabitacion."'  WHERE pedido=$idPedido ";
    $result=mysql_query($consulta5);
    
    if($result){
        $sqlHabitacionAnterior="UPDATE `habitacion` SET `estado`='6' WHERE idHabitacion= $antiguaHabitacion";
        $resultHab = mysql_query($sqlHabitacionAnterior);
        if($resultHab){
            echo '1';
        }else{
            echo '2';
        }
    }else{
        echo '2';
    }
         
    
   }
   
   
   if($accion == 25){
    $response= array();
    $fecha= date("Y-m-d");
    $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
    $idCliente= $_POST['idCliente'];
    $idFactura= trim($_POST['idFactura']);
    $valorPagado = 0;

    if($idFactura!=''){
        $sqlFactura="SELECT
        `id`,
        `id_forma`,
        `documento`,
        `id_factura`,
        `id_empresa`,
        `valor`,
        `tipo`,
        `porcentaje`,
        `fecha_registro`,
        `numero_retencion`,
        `autorizacion`
    FROM
        `cobrospagos`
    WHERE
        id_empresa =$sesion_id_empresa AND documento = '1' AND id_factura =$idFactura AND tipo = '19'";
        $resultFactura = mysql_query($sqlFactura);
        $numFilasFacturar= mysql_num_rows($resultFactura);
        
          if($numFilasFacturar>0){
              while($rowCob = mysql_fetch_array($resultFactura)){
                  $valorPagado = $rowCob['valor'];
                }        
          }
    }
    $response['cobrosPagos']=$valorPagado;
      
      $sql="SELECT
            SUM(saldo) AS saldo,
            SUM(valor) AS valor
        FROM
            `cuentas_por_cobrar`
        WHERE
        cuentas_por_cobrar.id_proveedor =$idCliente AND cuentas_por_cobrar.saldo > 0 AND cuentas_por_cobrar.id_empresa =$sesion_id_empresa
       ;";
      $result = mysql_query($sql);
      $saldo=0;
      while($row = mysql_fetch_array($result)){
          $saldo = $row['saldo'];
      }
  
      $response['saldo']=$saldo;
 $response['sql']=$sql;

 $sqlTipoAnticipo="SELECT
    SUM(saldo) AS saldo,
    SUM(valor) AS valor,
    tipo_anticipo.id_tipo_anticipo,
    tipo_anticipo.nombre_anticipo
    FROM
        `cuentas_por_cobrar`
        LEFT JOIN tipo_anticipo ON tipo_anticipo.id_tipo_anticipo=cuentas_por_cobrar.tipo_anticipo
    WHERE
    cuentas_por_cobrar.id_proveedor =$idCliente AND cuentas_por_cobrar.saldo > 0 AND cuentas_por_cobrar.id_empresa=$sesion_id_empresa GROUP BY cuentas_por_cobrar.tipo_anticipo;";
    $resultTipoAnticipo = mysql_query($sqlTipoAnticipo);
    $response['saldo_agrupado']= array();
    while($rowTP = mysql_fetch_array($resultTipoAnticipo) ){
        $id_actual = $rowTP['id_tipo_anticipo'];
        $response['saldo_agrupado'][$id_actual]= $rowTP['saldo'];
    }
    
     $sqlCta="
    SELECT
    enlaces_compras.`id` AS enlaces_compras_id,
    enlaces_compras.`nombre` AS enlaces_compras_nombre,
    enlaces_compras.`tipo` AS enlaces_compras_tipo,
    '#cpte' AS nro_cpte,
    enlaces_compras.`porcentaje` AS enlaces_compras_porcentaje,
    enlaces_compras.`cuenta_contable` AS enlaces_compras_cuenta_contable
FROM
    `enlaces_compras` enlaces_compras
WHERE
    enlaces_compras.`id_empresa` = '".$sesion_id_empresa."' AND enlaces_compras.tipo_cpra=19
LIMIT 10
    ";
        $resultCta = mysql_query($sqlCta);
        $cont=1;
        while($row = mysql_fetch_array($resultCta)){
            $funcion = 'fill10_FP_Cpras(\''.$cont.'\','.$row["enlaces_compras_id"].',\''.$row["enlaces_compras_id"]."*".$row["enlaces_compras_nombre"]." "."*".$row["enlaces_compras_tipo"]."*".$row["enlaces_compras_porcentaje"]."*".$row["enlaces_compras_cuenta_contable"]."*".$fecha."*".$sesion_tipo_empresa.'\')';
        }
         $response['funcion_sql']=$sqlCta;
      $response['funcion']=$funcion;
     
      echo json_encode($response);
  }  
   
?>