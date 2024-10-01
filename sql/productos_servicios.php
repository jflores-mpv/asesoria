<?php

    require_once('../ver_sesion.php');

    //Start session
    session_start();

    //Include database connection details
    require_once('../conexion.php');

    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $accion = $_POST['txtAccion'];
    $emision_tipoFacturacion=$_SESSION['emision_tipoFacturacion'];
 
 if($accion == '1'){
       // GUARDA PRODUCTO Y DETALLE PRODUCTO PAGINA: productos.php
       
        $txtCodigo=$_POST['txtCodigo'];
		$txtProducto= $_POST['txtProducto'];
		
	    $sql1 = "SELECT codigo, producto from productos where id_empresa ='".$sesion_id_empresa."' and 
	    (codigo='".$txtCodigo."' or producto='".$txtProducto."')   ";
	   // echo $sql1;
 		$resultado = mysql_query($sql1);
		$fila=mysql_num_rows($resultado);
		if ($fila>0)
		{
           
		?>
		   3 <?php
		} 
		else
		{
     try
     {
         $ano = date("Y");
         $mes = date("m");
        $fecha = date("Y-m-d");
        $id_categoria=$_POST['cmbCategoria'];
        $producto=ucwords($_POST['txtProducto']);
        $existencia_minima=$_POST['txtExistenciaMinima'];
        $existencia_maxima=$_POST['txtExistenciaMaxima'];
        $stock="0";
        // $costo="0";        
        $id_proveedor=$_POST['cmbProveedor'];
        $id_proveedor="0";

        $imagen="no hay";
        $descripcion=ucwords($_POST['txtDescripcion']);

        $txtCodigo=$_POST['txtCodigo'];
        $CodigoPrin=$_POST['CodigoPrin'];
        $CodigoAux=$_POST['CodigoAux'];
        
        
        
		$detalle = $_POST['txtDetalleProducto'];
        $cmbTipoCompra='0';
        //$cmbCuenta=$_POST['cmbCuenta'];
		$cmbTipoCosto=$_POST['cmbTipoCosto'];
        $iva=$_POST['switch-one'] ;
        $ice=$_POST['switch-two'] ;
        $IRBPNR=$_POST['switch-three'] ;
        if($_POST['txtPrecioVenta1']!=''){
            $txtPrecioVenta1=$_POST['txtPrecioVenta1'] ;
        }else{
            $txtPrecioVenta1=0;
        }
        if($_POST['txtPrecioVenta2']!=''){
            $txtPrecioVenta2=$_POST['txtPrecioVenta2'] ;
        }else{
            $txtPrecioVenta2=0;
        }
       
        if($_POST['txtPrecioVenta3']!=''){
            $txtPrecioVenta3=$_POST['txtPrecioVenta3'] ;
        }else{
            $txtPrecioVenta3=0;
        }
       
        if($_POST['txtPrecioVenta4']!=''){
            $txtPrecioVenta4=$_POST['txtPrecioVenta4'] ;
        }else{
            $txtPrecioVenta4=0;
        }
       
        if($_POST['txtPrecioVenta5']!=''){
            $txtPrecioVenta5=$_POST['txtPrecioVenta5'] ;
        }else{
            $txtPrecioVenta5=0;
        }
       
        if($_POST['txtPrecioVenta6']!=''){
            $txtPrecioVenta6=$_POST['txtPrecioVenta6'] ;
        }else{
            $txtPrecioVenta6=0;
        }
       
        if($_POST['txtPrecioCosto']!=''){
            $costo=$_POST['txtPrecioCosto'] ;
        }else{
            $costo=0;
        }
       
        // $txtPrecioVenta2=$_POST['txtPrecioVenta2'] ;
        // $txtPrecioVenta3=$_POST['txtPrecioVenta3'] ;
        // $txtPrecioVenta4=$_POST['txtPrecioVenta4'] ;
        // $txtPrecioVenta5=$_POST['txtPrecioVenta5'] ;
        // $txtPrecioVenta6=$_POST['txtPrecioVenta6'] ;
        // $txtPrecioCosto=$_POST['txtPrecioCosto'] ;
        
        $marca = $_POST['txtMarca'];
        $modelo = $_POST['txtModelo'];
        $tipotxt = $_POST['txtTipo'];
        $color = $_POST['txtColor'];

        $switchtipo=$_POST['tipoCompra'];
        
        $hab=$_POST['txthab'] ;
        $cmbTipoCosto='D';
        $txtDescuento='0';
   $stock=$_POST['txtStock'];
        
        $produccion='Si';
        
        $id_grupo=$_POST['switch-five'];
        
                $sqlTipo="Select tipo,id_cuenta From centro_costo where id_centro_costo = '".$id_grupo."'; ";
                $resultTipo=mysql_query($sqlTipo);
                $tipo=0;
                $id_cuenta=0;
                while($rowTipo=mysql_fetch_array($resultTipo))//permite ir de fila en fila de la tabla
                    {
                        $tipo=$rowTipo['tipo'];
                        $id_cuenta=$rowTipo['id_cuenta'];
                    }
        $fechaCaducidad = isset($_POST['txtFechaCaducidad'])?$_POST['txtFechaCaducidad']:'0000-00-00';
        
        
        $proyecto = isset($_POST['proyecto'])?$_POST['proyecto']:'0';
        
        if($producto != "" && $id_categoria != ""   )
        
        {
          
        $sql = "insert into productos(  producto, existencia_minima, existencia_maxima, stock, costo, id_categoria, id_proveedor,iva,fecha_registro, ano, mes,id_empresa, codigo,codPrincipal,codAux, tipos_compras, id_cuenta, tipo_costo, produccion,proceso,hab,ICE, IRBPNR,grupo,promocion,precio1,precio2,precio3,precio4,precio5,precio6,detalle, marca, modelo, tipo, color, caducidad,	proyecto)
                  values (  '".$producto."','1','100000','".$stock."','".$costo."','".$id_categoria."','0','".$iva."','".$fecha."', '".$ano."', '".$mes."','".$sesion_id_empresa."', '".$txtCodigo."','".$CodigoPrin."','".$CodigoAux."','".$tipo."','".$id_cuenta."','".$cmbTipoCosto."','".$produccion."','0','".$hab."','".$ice."','".$IRBPNR."','".$id_grupo."', '".$txtDescuento."', '".$txtPrecioVenta1."', '".$txtPrecioVenta2."', '".$txtPrecioVenta3."', '".$txtPrecioVenta4."', '".$txtPrecioVenta5."', '".$txtPrecioVenta6."', '".$detalle."', '".$marca."', '".$modelo."', '".$tipotxt."', '".$color."', '".$fechaCaducidad."','".$proyecto."'); ";
        //   echo $sql;
        
        
          $result = mysql_query($sql) or mysql_error() ;
		  $id_producto=mysql_insert_id();
          if ($result)
               { 
                    echo "1";
  
            }
        }
        else{
            ?> <div class="transparent_ajax_error"><p>Fallo en el envio del Formulario: No hay datos</p> </div> <?php
        }

     }catch (Exception $e) {
     // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
     }
   }
   }

   
   if($accion == '2'){
       // GUARDAR MODIFICACION PRODUCTOS PAGINA: productos.php
     try
     {
        $txtIdProducto = $_POST['txtIdProducto'];
        $cmbCategoria = $_POST['cmbCategoria'];
        $txtProducto = ucwords($_POST['txtProducto']);
         $txtCosto = ucwords($_POST['$txtCosto']);
        $txtExistenciaMinima = $_POST['txtExistenciaMinima'];
        $txtExistenciaMaxima = $_POST['txtExistenciaMaxima'];
        $cmbProveedor = $_POST['cmbProveedor'];
     //   $txtColor = ucwords($_POST['txtColor']);
     //   $txtTamano = ucwords($_POST['txtTamano']);
    //    $txtMarca = ucwords($_POST['txtMarca']);
        $img ="";
        $txtDescripcion = ucwords($_POST['txtDescripcion']);
        $txtIdDetalle = $_POST['txtIdDetalle'];
     //   $txtGanancia1 = $_POST['txtGanancia1'];
     //   $txtGanancia2 = $_POST['txtGanancia2'];

        if($txtIdProducto != "" && $cmbCategoria != "" && $txtProducto != ""  ){                    

          $sql2 = "update productos set producto='".$txtProducto."', costo='".$txtCosto."',existencia_minima='".$txtExistenciaMinima."', existencia_maxima='".$txtExistenciaMaxima."', id_categoria='".$cmbCategoria."', id_proveedor='".$cmbProveedor."', WHERE id_producto='".$txtIdProducto."'; ";
          $resp2 = mysql_query($sql2) or die("<div class='transparent_ajax_error'><p>Error al modificar el producto: ".mysql_error()."</p></div>");
          // consultas para cambiar el ESTADO de CARGOS a LIBRE
          if($resp2){
              $sql22 = "update detalles set   imagen='".$img."', descripcion='".$txtDescripcion."', id_producto='".$txtIdProducto."' WHERE id_detalle='".$txtIdDetalle."'; ";
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

    
      
    if($accion === "3"){
    // ELIMINA PRODUCTOS PAGINA: productos.php
     try
     {
        if(isset ($_POST['id_producto'])){
          $id_producto = $_POST['id_producto'];

            $existen=0;
            $cantidadProducto=0;
            //1. Verificamos existencias en detallecompras 
          $sql1="SELECT `id_detalle_compra`, SUM(`cantidad`) as total, `id_producto`, COUNT(id_producto) as registros FROM `detalle_compras` WHERE id_producto='$id_producto'";
          $result1=mysql_query($sql1);
          while($row1=mysql_fetch_array($result1))
            {
                $existen=$row1['registros'];
                $cantidadProducto=$row1['total'];
            }
        $response[0]=($cantidadProducto>0 && $existen>0)?'Este producto esta registrado en los detalles de las compras, no se puede eliminar': 0; 

            if($response[0]===0){

                $sql2="SELECT `id_detalle_venta`, SUM(`cantidad`) as total, COUNT(`id_servicio`) as registros FROM `detalle_ventas` WHERE `id_servicio`='$id_producto'";
                $result2=mysql_query($sql2);
                while($row2=mysql_fetch_array($result2))
                  {
                      $existenV=$row2['registros'];
                      $cantidadProductoV=$row2['total'];
                  }
              $response[1]=($cantidadProductoV>0 && $existenV>0)?'Este producto esta registrado en los detalles de las ventas, no se puede eliminar': 0; 
        
              if($response[1]===0){
                $sql3="SELECT `id_detalle_ingreso`, SUM(`cantidad`) as total, COUNT(`id_producto`) as registros FROM `detalle_ingresos` WHERE `id_producto`='$id_producto'";
                $result3=mysql_query($sql3);
                while($row3=mysql_fetch_array($result3))
                  {
                      $existenI=$row3['registros'];
                      $cantidadProductoI=$row3['total'];
                  }
              $response[2]=($cantidadProductoI>0 && $existenI>0)?'Este producto esta registrado en los detalles de los ingresos, no se puede eliminar': 0; 
          

              if($response[2]===0){
                $sql4="SELECT `id_detalle_egreso`,  SUM(`cantidad`) as total, COUNT(`id_producto`) as registros  FROM `detalle_egresos` WHERE `id_producto`='$id_producto'";
                $result4=mysql_query($sql4);
                while($row4=mysql_fetch_array($result4))
                  {
                      $existenE=$row4['registros'];
                      $cantidadProductoE=$row4['total'];
                  }
              $response[3]=($cantidadProductoE>0 && $existenE>0)?'Este producto esta registrado en los detalles de los egresos, no se puede eliminar': 0; 
            
              if($response[3]===0){
                $sql5="SELECT COUNT(`id_producto`) as registros, `producto`, SUM(`stock`) as total FROM `productos`  WHERE `id_producto`='$id_producto'";
                $result5=mysql_query($sql5);
                while($row5=mysql_fetch_array($result5))
                  {
                      $existenP=$row5['registros'];
                      $cantidadProductoP=$row5['total'];
                  }
              $response[4]=($cantidadProductoP>0 && $existenP>0)?"Existen $existenP en inventario, no se puede eliminar": 0; 
              
              if(    $response[4]===0){
 //cambia el estado a libre y limpia el usuario y cedula
 $sql3 = "delete from productos WHERE id_producto='".$id_producto."'; ";
 $result3=mysql_query($sql3);

 $response[5]=($result3)?'Se elimino correctamente el producto': "Error al eliminar los datos: "; 
              }
                       
              }
             
                }
              }
             

            }
      
                    
         }else{
          //echo "Fallo en el envio del Formulario: No hay datos, ".mysql_error();
          $response[]='Fallo en el envio del Formulario: No hay datos'; 
      }
      echo json_encode($response);
     }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }

    }
    
    
    

    if($accion == "4"){
    // VALIDA PARA QUE EL NOMBRE DEL PRODUCTO NO SE REPITA PAGINA: productos.php
     try
     {
        if(isset ($_POST['nombre'])){
          $nombre1 = $_POST['nombre'];
          $sql1 = "SELECT producto from productos where id_empresa ='".$sesion_id_empresa."' and  producto='".$nombre1."'";
	 
          $resp1 = mysql_query($sql1);
          $entro=0;
          while($row1=mysql_fetch_array($resp1))//permite ir de fila en fila de la tabla
                    {
                        $var1=$row1["producto"];
                    }
           $nombre2 = strtolower($nombre1);
           $var2 = strtolower($var1);
          if($var2==$nombre2){
               if($var2==""&&$nombre2==""){
                     $entro=0;
                  }else{
                      $entro=1;
                  }
          }
         echo $entro;
         }

     }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }

    }

    if($accion == '5'){
       // GUARDA CATEGORIA DE PRODUCTO PAGINA: productos.php
     try
     {
        $numeroFilaSelec=$_POST['numeroFilaSelec'];
        $txtCategoria=ucwords($_POST['txtCategoria'.$numeroFilaSelec]);
        

        if($numeroFilaSelec != "" && $txtCategoria != ""){

           //permite sacar el id maximo de categorias
            try {
                $sqlc5="Select max(id_categoria) From categorias; ";
                $resultc5=mysql_query($sqlc5);
                $id_categoria=0;
                while($rowc5=mysql_fetch_array($resultc5))//permite ir de fila en fila de la tabla
                    {
                        $id_categoria=$rowc5['max(id_categoria)'];
                    }
                $id_categoria++;
            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }

          $sql5 = "insert into categorias( categoria, id_empresa) values ('".$txtCategoria."', '".$sesion_id_empresa."'); ";
          $result5 = mysql_query($sql5);
		  $id_categoria=mysql_insert_id();
          if ($result5)
                {
                    ?> <div class='transparent_ajax_correcto'><p>Registro insertado correctamente.</p></div> <?php
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

   if($accion == '6'){
       // actualiza tabla categorias
     try
     {
        $idCategorias=$_POST['idCategorias'];
        $numeroFila = $_POST['NumeroFilaSeleccionada'];
        $txtCategoria = ucwords($_POST['txtCategoria'.$numeroFila]);
        

        if($txtCategoria != "" & $idCategorias !=""){
              $sqlm = "update categorias set categoria='".$txtCategoria."', id_empresa='".$sesion_id_empresa."' where id_categoria='".$idCategorias."'; ";
              $respm = mysql_query($sqlm);
              if($respm){
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

   if($accion == '7'){
     try
     {
          $idCategorias = $_POST['idCategorias'];
          $sqD = "delete from categorias where id_categoria='".$idCategorias."';";
                if(!mysql_query($sqD))
                        {echo "<div class='transparent_ajax_error'><p>Ocurrio un error al eliminar: \n".mysql_error()."</p></div>";}
                else { echo "<div class='transparent_notice'><p>El registro ha sido Eliminado.</p></div>"; }

     }catch (Exception $e) {
        // Error en algun momento.
           ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
        }

     }

    if($accion == '8'){
        try {
            $cadena8="";
            $consulta8="SELECT * FROM categorias where id_empresa='".$sesion_id_empresa."' order by categoria asc;";
            $result8=mysql_query($consulta8);
            while($row8=mysql_fetch_array($result8))//permite ir de fila en fila de la tabla
                {
                    $cadena8=$cadena8."?".$row8['id_categoria']."?".$row8['categoria'];
                }
            echo $cadena8;

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
    }

    if($accion == '9'){
        try {
            $cadena9="";
            $consulta9="SELECT * FROM unidades where id_empresa='".$sesion_id_empresa."' order by nombre asc;";
            $result9=mysql_query($consulta9);
            while($row9=mysql_fetch_array($result9))//permite ir de fila en fila de la tabla
                {
                    $cadena9=$cadena9."?".$row9['id_unidad']."?".$row9['nombre'];
                }
            echo $cadena9;

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
    }

     if($accion == '10'){
        try {
            $cadena10="";
            $consulta10="SELECT * FROM tipo_servicios where id_empresa='".$sesion_id_empresa."' order by nombre asc;";
            $result10=mysql_query($consulta10);
            while($row10=mysql_fetch_array($result10))//permite ir de fila en fila de la tabla
                {
                    $cadena10=$cadena10."?".$row10['id_tipo_servicio']."?".$row10['nombre'];
                }
            echo $cadena10;

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
    }

    if($accion == '11'){
       // GUARDA UNIDADES  PAGINA: ajax/unidades.php
     try
     {
        $numeroFilaSelec=$_POST['numeroFilaSelec'];
        $txtUnidad=ucwords($_POST['txtUnidad'.$numeroFilaSelec]);

        if($numeroFilaSelec != "" && $txtUnidad != ""){

           //permite sacar el id maximo de unidades
            try {
                $sql11="Select max(id_unidad) From unidades; ";
                $result11=mysql_query($sql11);
                $id_unidad=0;
                while($row11=mysql_fetch_array($result11))//permite ir de fila en fila de la tabla
                    {
                        $id_unidad=$row11['max(id_unidad)'];
                    }
                $id_unidad++;
            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }

          $sql112 = "insert into unidades( nombre, id_empresa) values ('".$txtUnidad."', '".$sesion_id_empresa."'); ";
          $result112 = mysql_query($sql112);
		  $id_unidad=mysql_insert_id();
          if ($result112)
                {
                    ?> <div class='transparent_ajax_correcto'><p>Registro insertado correctamente.</p></div> <?php
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

    if($accion == '12'){

        // actualiza tabla unidades
        try
        {
            $idUnidad12=$_POST['idUnidad'];
            $numeroFila12 = $_POST['NumeroFilaSeleccionada'];
            $txtUnidad12 = ucwords($_POST['txtUnidad'.$numeroFila12]);

            if($txtUnidad12 != "" & $idUnidad12 !=""){
                $sqlm12 = "update unidades set nombre='".$txtUnidad12."', id_empresa='".$sesion_id_empresa."' where id_unidad='".$idUnidad12."'; ";
                $respm12 = mysql_query($sqlm12);
                if($respm12){
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

    if($accion == '13'){
        // ELIMINA TABLA UNIDADES
        try
        {
            $idUnidad13 = $_POST['idUnidad'];
            $sqD13 = "delete from unidades where id_unidad='".$idUnidad13."';";
            if(!mysql_query($sqD13)){
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

    if($accion == '14'){
       // GUARDA TIPO SERVICIOS  PAGINA: ajax/tipoServicios.php
     try
     {
        $numeroFilaSelec=$_POST['numeroFilaSelec'];
        $txtNombreServicio=ucwords($_POST['txtNombreServicio'.$numeroFilaSelec]);

        if($numeroFilaSelec != "" && $txtNombreServicio != ""){

           //permite sacar el id maximo de tipo_servicios
            try {
                $sqlm14="Select max(id_tipo_servicio) From tipo_servicios; ";
                $resultm14=mysql_query($sqlm14);
                $id_tipo_servicio=0;
                while($rowm14=mysql_fetch_array($resultm14))//permite ir de fila en fila de la tabla
                    {
                        $id_tipo_servicio=$rowm14['max(id_tipo_servicio)'];
                    }
                $id_tipo_servicio++;
            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }

          $sql14 = "insert into tipo_servicios( nombre, id_empresa) values ('".$txtNombreServicio."', '".$sesion_id_empresa."'); ";
          $result14 = mysql_query($sql14);
		  $id_tipo_servicio=mysql_insert_id();
          if ($result14)
                {
                    ?> <div class='transparent_ajax_correcto'><p>Registro insertado correctamente.</p></div> <?php
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

    if($accion == '15'){

        // ACTUALIZA tabla TIPO SERVICIOS
        try
        {
            $idTipoServicio15=$_POST['idTipoServicio'];
            $numeroFila15 = $_POST['NumeroFilaSeleccionada'];
            $txtNombreServicio15 = ucwords($_POST['txtNombreServicio'.$numeroFila15]);

            if($txtNombreServicio15 != "" & $idTipoServicio15 !=""){
                $sqlm15 = "update tipo_servicios set nombre='".$txtNombreServicio15."', id_empresa='".$sesion_id_empresa."' where id_tipo_servicio='".$idTipoServicio15."'; ";
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
            $idTipoServicio16 = $_POST['idTipoServicio'];
            $sqD16 = "delete from tipo_servicios where id_tipo_servicio='".$idTipoServicio16."';";
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
	
	
	if($accion == '17'){
        // ELIMINA TABLA TIPO SERVICIOS
        
            $idTipoServicio16 = $_POST['precio'];
            
            $idProducto = $_POST['producto'];

            $consulta10="SELECT * FROM productos where id_producto='".$idProducto."' ;";
            $result10=mysql_query($consulta10);
            while($row10=mysql_fetch_array($result10))//permite ir de fila en fila de la tabla
                {
                    $precioProducto1=$row10['precio1'];
                    $precioProducto2=$row10['precio2'];
                    $precioProducto3=$row10['precio3'];
                    $precioProducto4=$row10['precio4'];
                    $precioProducto5=$row10['precio5'];
                    $precioProducto=$row10['precio6'];
                    
                }
            echo $cadena10;


    }
?>