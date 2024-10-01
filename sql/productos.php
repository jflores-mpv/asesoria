<?php

    require_once('../ver_sesion.php');

    //Start session
    session_start();

    //Include database connection details
    require_once('../conexion.php');
    include('../conexion2.php');
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $accion = $_POST['txtAccion'];
    
    
   if($accion == '1'){
       // GUARDA PRODUCTO Y DETALLE PRODUCTO PAGINA: productos.php
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
        $costo="0";        
        $id_proveedor=$_POST['cmbProveedor'];
        $id_proveedor=$_POST[''];
        
        // detalles producto
     //   $color=ucwords($_POST['txtColor']);
    //    $tamano=ucwords($_POST['txtTamano']);
       // $marca=ucwords($_POST['txtMarca']);
       
        $imagen="no hay";
        $descripcion=ucwords($_POST['txtDescripcion']);
        
   //     $txtGanancia1=$_POST['txtGanancia1'];
     //   $txtGanancia2=$_POST['txtGanancia2'];
        $txtCodigo=$_POST['cmbCuenta'];
        
        $txtStock=$_POST['txtStock'];
        $txtDescuento='0';


          if($producto != "" && $id_categoria != "" ){
      
          
           //permite sacar el id maximo de categorias
            try {
                $sqlp="Select max(id_producto) From productos";
                $resultp=mysql_query($sqlp);
                $id_producto=0;
                while($rowp=mysql_fetch_array($resultp))//permite ir de fila en fila de la tabla
                    {
                        $id_producto=$rowp['max(id_producto)'];
                    }
                $id_producto++;
            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }

          $sql = "insert into productos(id_producto, producto, existencia_minima, existencia_maxima, stock, costo, id_categoria, id_proveedor,iva, 
          fecha_registro, ano, mes,id_empresa, codigo,promocion,stock)
          values ('".$id_producto."','".$producto."','".$existencia_minima."','".$existencia_maxima."','".$stock."',
          '".$costo."','".$id_categoria."', 
          '".$id_proveedor."','".$iva."','".$fecha."', '".$ano."', '".$mes."','".$sesion_id_empresa."',
          '".$txtCodigo."','".$txtDescuento."','".$txtStock."'); ";
          $result = mysql_query($sql);
          
// echo $sql;
          if ($result)
              {
                //permite sacar el id maximo de detalles
                try {
                    $sqld="Select max(id_detalle) From detalles";
                    $resultd=mysql_query($sqld);
                    $id_detalle=0;
                    while($rowd=mysql_fetch_array($resultd))//permite ir de fila en fila de la tabla
                        {
                            $id_detalle=$rowd['max(id_detalle)'];
                        }
                    $id_detalle++;
                }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }
               
               // $sqld="insert into detalles(id_detalle,    imagen, descripcion, id_producto)
                 //       values ('".$id_detalle."','".$imagen."','".$descripcion."','".$id_producto."');";
               
         //       $resultd=mysql_query($sqld);
                if ($resultd)
                    {
                        ?> <div class='alert alert-success'><p>Registro insertado correctamente.</p></div> <?php
                    }else {
                        ?> <div class='alert alert-danger'><p>Error al guarda detalles del producto: <?php echo mysql_error();?> </p></div> <?php }

               }else {
                   ?> <div class='transparent_ajax_error'><p>Error al guarda en la tabla Productos: <?php echo mysql_error();?> </p></div> <?php }
        }
        else{
            ?> <div class="transparent_ajax_error"><p>Fallo en el envio del Formulario: No hay datos</p></div> <?php
        }

     }catch (Exception $e) {
     // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
     }
   }

   
    if($accion == '2'){
       // GUARDAR MODIFICACION PRODUCTOS PAGINA: productos.php

     try
     {
         $txtIdProducto= $_POST['txtIdProducto'];
        $ano = date("Y");
        $mes = date("m");
       $fecha = date("Y-m-d");
       $id_categoria=$_POST['cmbCategoria'];
       $producto=ucwords($_POST['txtProducto']);
       $existencia_minima=$_POST['txtExistenciaMinima'];
       $existencia_maxima=$_POST['txtExistenciaMaxima'];
       $stock=$_POST['txtStock'];
     //  $stock="0";
       
       
       // $costo="0";        
       $id_proveedor=$_POST['cmbProveedor'];
       $id_proveedor="0";

       $imagen="no hay";
       $descripcion=ucwords($_POST['txtDescripcion']);

        $txtCodigoAnterior=$_POST['txtCodigoAnterior'];
       $txtCodigo=$_POST['txtCodigo'];
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

       
       $produccion='Si';
       
       $id_grupo=$_POST['switch-five'];

                $sqlTipo="Select tipo From centro_costo where id_centro_costo = '".$id_grupo."'; ";
                $resultTipo=mysql_query($sqlTipo);
                $tipo=0;
                while($rowTipo=mysql_fetch_array($resultTipo))//permite ir de fila en fila de la tabla
                    {
                        $tipo=$rowTipo['tipo'];
                    }
        
    //    $txtMarca = ucwords($_POST['txtMarca']);
        $fechaCaducidad = isset($_POST['txtFechaCaducidad'])?$_POST['txtFechaCaducidad']:'0000-00-00';
        
        $fechaCaducidad =  (trim($fechaCaducidad)!='')?$_POST['txtFechaCaducidad']:'0000-00-00';
  
       
        
        if( $id_categoria != "" && $producto != ""  ){  
            
            
            
         
                
                $CodigoPrin= $_POST['CodigoPrin'];
                $CodigoAux = $_POST['CodigoAux'];
                
            $sqlCod="SELECT `codigo` FROM `productos` WHERE `id_producto`=$txtIdProducto";
            $resultCod = mysql_query($sqlCod);
            $codigoProducto='';
            while($rowCod = mysql_fetch_array($resultCod) ){
                $codigoProducto = $rowCod['codigo'];
            }
             $proyecto= isset($_POST['proyecto'])?$_POST['proyecto']:'0';
             
             $sql2 = "update productos set producto='".$producto."', costo='".$costo."', 
          id_categoria='".$id_categoria."', iva='".$iva."',
          
          tipo_costo='".$cmbTipoCosto."',  
          codigo='".$txtCodigo."',codPrincipal='".$CodigoPrin."',codAux='".$CodigoAux."',stock='".$stock."' ,promocion='".$txtDescuento."' , produccion='".$produccion."',grupo='".$id_grupo."', 
          detalle='".$detalle."', hab='".$hab."', ICE='".$ice."', tipos_compras='".$tipo."', IRBPNR='".$IRBPNR."',  
          precio1='".$txtPrecioVenta1."', precio2='".$txtPrecioVenta2."', precio3='".$txtPrecioVenta3."', precio4='".$txtPrecioVenta4."', precio5='".$txtPrecioVenta5."',precio6='".$txtPrecioVenta6."', marca='".$marca."', modelo='".$modelo."', tipo='".$tipo."', tipo='".$tipotxt."',  color='".$color."',  caducidad='".$fechaCaducidad."',proyecto='".$proyecto."'
          WHERE codigo='".$codigoProducto."' and id_empresa='".$sesion_id_empresa."'; ";
            
     
            
          
            
   
          $resp2 = mysql_query($sql2) or 
          die("<div class='transparent_ajax_error'><p>Error al modificar el producto: ".mysql_error()."</p></div>");

   if ($resp2)
                {
         if($txtCodigo!=$txtCodigoAnterior){
             
                   
                $sqlSelectBo="Select * From cantBodegas where idProducto='".$txtCodigoAnterior."'; ";
                $resultSelectBo=mysql_query($sqlSelectBo);
                $tipo=0;
                while($rowBodegas=mysql_fetch_array($resultSelectBo))//permite ir de fila en fila de la tabla
                    {
                        $idProducto=$rowBodegas['idProducto'];
                        $cantidad=$rowBodegas['cantidad'];
                    }
                // echo $cantidad."==".$idProducto;
               $sqlSelectBo2="Select cantBodegas.id,idProducto,cantidad From cantBodegas
                INNER JOIN `bodegas` bodegas ON cantBodegas.idBodega=bodegas.id
                where idProducto='".$txtCodigo."' and bodegas.id_empresa='".$sesion_id_empresa."'
                ; ";
                $resultSelectBo2=mysql_query($sqlSelectBo2);
                $tipo=0;
                while($rowBodegas2=mysql_fetch_array($resultSelectBo2))//permite ir de fila en fila de la tabla
                    {
                        $idBodega2=$rowBodegas2['id'];
                        $idProducto2=$rowBodegas2['idProducto'];
                        $cantidad2=$rowBodegas2['cantidad'];
                    }
                    
                    
                $sqlSelectBo4="Select cantBodegas.id,idProducto,cantidad From cantBodegas
                INNER JOIN `bodegas` bodegas ON cantBodegas.idBodega=bodegas.id
                where idProducto='".$txtCodigoAnterior."' and bodegas.id_empresa='".$sesion_id_empresa."'
                ; ";
                $resultSelectBo4=mysql_query($sqlSelectBo4);
                $tipo=0;
                while($rowBodegas4=mysql_fetch_array($resultSelectBo4))//permite ir de fila en fila de la tabla
                    {
                        $idBodega4=$rowBodegas4['id'];
                        $idProducto4=$rowBodegas4['idProducto'];
                        $cantidad4=$rowBodegas4['cantidad'];
                    }
                    
                // echo $cantidad2."==".$idProducto2;
                    
                $cantidad3=$cantidad4+$cantidad2;
                    
             $sqlActualizar="UPDATE cantBodegas set  cantidad='".$cantidad3."'  where id='".$idBodega2."'; ";
                
                $resultActualizar=mysql_query($sqlActualizar);
                if($resultActualizar){
                   
                      $sqlDELETE="DELETE  FROM  cantBodegas where  id='".$idBodega4."'; ";
                        
                        $resulDELETE=mysql_query($sqlDELETE);
                        if($resulDELETE){
                          echo "1";
                        }else{
                             echo "PRODUCTO no ACTUALIZADO";
                        }
                    
                   
                }else{
                     echo "PRODUCTO no ACTUALIZADO";
                }
                
         }else{
             echo "1";
         }
                
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
    // ELIMINA PRODUCTOS PAGINA: productos.php
     try
     {
        if(isset ($_POST['id_producto'])){
          $id_producto = $_POST['id_producto'];

          //cambia el estado a libre y limpia el usuario y cedula
          $sql3 = "delete from productos WHERE id_producto='".$id_producto."'; ";
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
    // VALIDA PARA QUE EL NOMBRE DEL PRODUCTO NO SE REPITA PAGINA: productos.php
     try
     {
        if(isset ($_POST['producto'])){
          $nombre1 = $_POST['producto'];
          $sql1 = "SELECT producto from productos where producto='".$nombre1."' and id_empresa='".$sesion_id_empresa."' ;";
			
//echo $sql1;
          $resp1 = mysql_query($sql1);
          $entro=0;
          while($row1=mysql_fetch_array($resp1))//permite ir de fila en fila de la tabla
          {
             $var1=$row1["producto"];
          }
           $nombre2 = strtolower($nombre1);
           $var2 = strtolower($var1);
		  
		   if($var2==$nombre2)
			   {
                  if($var2=="" && $nombre2=="")
				  {
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

          $sql5 = "insert into categorias(id_categoria, categoria, id_empresa) values ('".$id_categoria."','".$txtCategoria."', '".$sesion_id_empresa."'); ";
          $result5 = mysql_query($sql5);
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

          $sql112 = "insert into unidades(id_unidad, nombre, id_empresa) values ('".$id_unidad."','".$txtUnidad."', '".$sesion_id_empresa."'); ";
          $result112 = mysql_query($sql112);
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

          $sql14 = "insert into tipo_servicios(id_tipo_servicio, nombre, id_empresa) values ('".$id_tipo_servicio."','".$txtNombreServicio."', '".$sesion_id_empresa."'); ";
          $result14 = mysql_query($sql14);
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
    
    
    if($accion == "17")
	{
    // VALIDA PARA QUE EL RUC DEL PROVEEDOR NO SE REPITA PAGINA: proveedores.php
		try
		{
			if(isset ($_POST['codigo']))
			{
			  $nombre1 = $_POST['codigo'];
			  $sql1 = "SELECT codigo from productos where codigo='".$nombre1."' and id_empresa='".$sesion_id_empresa."' ;";
			  $resp1 = mysql_query($sql1);
			  $entro=0;
			  while($row1=mysql_fetch_array($resp1))   //permite ir de fila en fila de la tabla
				{
                        $var1=$row1["codigo"];
                 }
               $nombre2 = strtolower($nombre1);
               $var2 = strtolower($var1);
               if($var2==$nombre2)
			   {
                  if($var2=="" && $nombre2=="")
				  {
                     $entro=0;
                  }else{
                      $entro=1;
                  }
                }
                echo $entro;
            }
        }
		catch (Exception $e) 
		{
    // Error en algun momento.
           ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
		}

    }
    
    
      if($accion == 20){
          
        //   echo "==>1";
    //  
    include('../conexion2.php');
    require_once('../vendor/php-excel-reader/excel_reader2.php');
    require_once('../vendor/SpreadsheetReader.php');
//   echo "==>2";
    
      $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
      
      if(in_array($_FILES["file"]["type"],$allowedFileType)){
    
     $targetPath = 'subidas/'.$_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
    //   echo "==>3";
        $Reader = new SpreadsheetReader($targetPath);
    $con = $conexion;
    //   $sheetCount = count($Reader->sheets());
    $sheetCount = count($Reader->sheets());
        for($i=0;$i<$sheetCount;$i++)
        {
        //   echo $i;
          $Reader->ChangeSheet($i);
    //   var_dump($Reader);
    
    //  echo "==>4";
          foreach ($Reader as $Row)
          {
var_dump($Row);
            $producto = (isset($Row[1]) || $Row[1]=='')? mysqli_real_escape_string($con,$Row[1]): '' ;

            $existencia_minima = (isset($Row[2] )|| $Row[2]=='')?mysqli_real_escape_string($con,$Row[2]):'';
            
            $existencia_maxima = (isset($Row[3]) || $Row[3]=='')? mysqli_real_escape_string($con,$Row[3]): '' ;

            $stock = (isset($Row[4]) || $Row[4]=='')? mysqli_real_escape_string($con,$Row[4]): '' ;
          
            $costo = (isset($Row[5]) || $Row[5]=='')? mysqli_real_escape_string($con,$Row[5]): '' ;
         
            $sqlCategoria= "SELECT `id_categoria` FROM `categorias` WHERE 
            `id_empresa`=$sesion_id_empresa AND categoria='PRODUCTOS'";
            // echo $sqlCategoria;
            $resultCategoria= mysqli_query($con, $sqlCategoria);
            $id_categoria=0;
            while($rowCat=mysqli_fetch_array($resultCategoria)){
                $id_categoria = $rowCat['id_categoria'];
            }
            // echo $id_categoria;
            // $id_categoria = (isset($Row[6]) || $Row[6]=='')? mysqli_real_escape_string($con,$Row[6]): '' ;
           
            $id_proveedor = (isset($Row[7]) || $Row[7]=='')? mysqli_real_escape_string($con,$Row[7]): '' ;
            // echo '|';
        //   echo $Row[8];
          
            $precio1 = (isset($Row[8]) || $Row[8]=='')?$Row[8] : 0 ;
            //   exit;
            $precio2 = (isset($Row[9]) || $Row[9]=='')? mysqli_real_escape_string($con,$Row[9]): 0 ;
            
            $precio3 = (isset($Row[10]) || $Row[10]=='')? mysqli_real_escape_string($con,$Row[10]): 0;
         
            $precio4 = (isset($Row[11]) || $Row[11]=='')? mysqli_real_escape_string($con,$Row[11]): 0 ;
            
            $precio5 = (isset($Row[12]) || $Row[12]=='')? mysqli_real_escape_string($con,$Row[12]): 0 ;
            
            $precio6 = (isset($Row[13]) || $Row[13]=='')? mysqli_real_escape_string($con,$Row[13]): 0 ;


            $iva = (isset($Row[14]) || $Row[14]=='')? mysqli_real_escape_string($con,$Row[14]): '' ;
            
            $ice = (isset($Row[15]) || $Row[15]=='')? mysqli_real_escape_string($con,$Row[15]): '' ;
            
            $aIRBPNR = (isset($Row[16]) || $Row[16]=='')? mysqli_real_escape_string($con,$Row[16]): '' ;
         
            $hab = (isset($Row[17]) || $Row[17]=='')? mysqli_real_escape_string($con,$Row[17]): '' ;
            
            $ganancia1 = (isset($Row[18]) || $Row[18]=='')? mysqli_real_escape_string($con,$Row[18]): '' ;
            
            $ganancia2 = (isset($Row[19]) || $Row[19]=='')? mysqli_real_escape_string($con,$Row[19]): '' ;

         
            if(isset($Row[20])) {
              $fecha_registro = mysqli_real_escape_string($con,$Row[20]);
            }
            $ano = "";
            if(isset($Row[21])) {
              $ano = mysqli_real_escape_string($con,$Row[21]);
            }
            $mes = "";
            if(isset($Row[22])) {
              $mes = mysqli_real_escape_string($con,$Row[22]);
            }
            
            $id_empresa = $sesion_id_empresa;
            
    
            $codigo = "";
            if(isset($Row[24])) {
              $codigo = mysqli_real_escape_string($con,$Row[24]);
            }
            $codPrincipal = "";
            if(isset($Row[25])) {
              $codPrincipal = mysqli_real_escape_string($con,$Row[25]);
            }
            $codAux = "";
            if(isset($Row[26])) {
              $codAux = mysqli_real_escape_string($con,$Row[26]);
            }
            

    
            $id_cuenta = "0";
           
            $tipo_costo = "";
            if(isset($Row[29])) {
              $tipo_costo = mysqli_real_escape_string($con,$Row[29]);
            }
            $produccion = "";
            if(isset($Row[30])) {
              $produccion = mysqli_real_escape_string($con,$Row[30]);
            }
            $proceso = "";
            if(isset($Row[31])) {
              $proceso = mysqli_real_escape_string($con,$Row[31]);
            }
            $grupo = "";
            
            $tipos_compras= "";
            $sqlGrupo= "SELECT `id_centro_costo`, `tipo` FROM `centro_costo` WHERE `tipo`='1' and empresa='$sesion_id_empresa'";
            // echo $sqlGrupo;
            $resultGrupo= mysqli_query($con, $sqlGrupo);
            while($rowGrupo=mysqli_fetch_array($resultGrupo)){
            
                $grupo = $rowGrupo['id_centro_costo'];
                $tipos_compras =$rowGrupo['tipo'];
            }
            
            
            $proceso = mysqli_real_escape_string($con,$Row[31]);
          
            $promocion = "";
            if(isset($Row[33])) {
              $promocion = mysqli_real_escape_string($con,$Row[33]);
            }
            $img = "";
            if(isset($Row[34])) {
              $img = mysqli_real_escape_string($con,$Row[34]);
            }
            $detalle = "";
            if(isset($Row[35])) {
              $detalle = mysqli_real_escape_string($con,$Row[35]);
            }
    
            $marca = "";
            if(isset($Row[36])) {
              $marca = mysqli_real_escape_string($con,$Row[36]);
            }
            $modelo = "";
            if(isset($Row[37])) {
              $modelo = mysqli_real_escape_string($con,$Row[37]);
            }
            $tipo = "";
            if(isset($Row[38])) {
              $tipo = mysqli_real_escape_string($con,$Row[38]);
            }
            $color = "";
            if(isset($Row[39])) {
              $color = mysqli_real_escape_string($con,$Row[39]);
            }
            $id_bodega = "";
            if(isset($Row[40])) {
              $id_bodega = mysqli_real_escape_string($con,$Row[40]);
            }
            $id_centroCostos = "";
            if(isset($Row[41])) {
              $id_centroCostos = mysqli_real_escape_string($con,$Row[41]);
            }
           
            // if (!empty($nombres) || !empty($cargo) || !empty($celular) || !empty($descripcion)) {
          echo  $query = "INSERT INTO `productos`(`producto`, `existencia_minima`, `existencia_maxima`, `stock`, `costo`, `id_categoria`, `id_proveedor`, `precio1`, `precio2`, `precio3`, `precio4`, `precio5`, `precio6`, `iva`, `ICE`, `IRBPNR`, `hab`, `ganancia1`, `ganancia2`, `fecha_registro`, `ano`, `mes`, `id_empresa`, `codigo`, `codPrincipal`, `codAux`, `tipos_compras`, `id_cuenta`, `tipo_costo`, `produccion`, `proceso`, `grupo`, `promocion`, `img`, `detalle`, `marca`, `modelo`, `tipo`, `color`) VALUES ('$producto',$existencia_minima,'$existencia_maxima','$stock','$costo','$id_categoria','$id_proveedor','$precio1','$precio2','$precio3' ,'$precio4','$precio5', '$precio6' , '$iva' , '$ice','$aIRBPNR','$hab', '$ganancia1','$ganancia2','$fecha_registro','$ano','$mes','$id_empresa','$codigo','$codPrincipal','$codAux' ,'$tipos_compras','$id_cuenta','$tipo_costo' ,'$produccion', '$proceso','$grupo','$promocion','$img','$detalle','$marca','$modelo','$tipo','$color')";
              // $query = "insert into productos(nombres,cargo, celular, descripcion) values('".$nombres."','".$cargo."','".$celular."','".$descripcion."')";
              $resultados = mysqli_query($con, $query);
 
              if (! empty($resultados)) {
                $type = "success";
                $message = "Excel importado correctamente";
              } else {
                $type = "error";
                $message = "Hubo un problema al importar registros";
              }
            //  }
          }
    
        }
      }
      else
      { 
        $type = "error";
        $message = "El archivo enviado es invalido. Por favor vuelva a intentarlo";
      }
    }
    
//     if($accion == 21){
//         $fecha = date("Y-m-d");
//         //   echo "==>1";
//     //  
//     include('../conexion2.php');
//     require_once('../vendor/php-excel-reader/excel_reader2.php');
//     require_once('../vendor/SpreadsheetReader.php');
// //   echo "==>2";
    
//       $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
      
//       if(in_array($_FILES["file"]["type"],$allowedFileType)){
    
//      $targetPath = 'subidas/'.$_FILES['file']['name'];
//         move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
//     //   echo "==>3";
//         $Reader = new SpreadsheetReader($targetPath);
//     $con = $conexion;

//     //INICIO INGRESOS
//   echo  $sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
//     $resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;
//     $iva=0;
//     while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
//     {
//         $iva=$rowIva1['iva'];
//         $txtIdIva=$rowIva1['id_iva'];
//         $impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
//     }

//     $sqlNI = "SELECT MAX(numero) as numero from ingresos where  id_empresa='".$sesion_id_empresa."';";
//     $respNI = mysql_query($sqlNI);
//     while($row=mysql_fetch_array($respNI))//permite ir de fila en fila de la tabla
//     {
//         $var=$row["numero"];
//     }
// $var++;
//     $sql="insert into ingresos ( fecha, estado, total, sub_total, numero, 
// 				fecha_anulacion, descripcion, id_iva, id_empresa,id_cuenta, tipo_documento, observacion) 
// 				values ('".$fecha."','Activo','0','0','".$var."',
// 				NULL,'', '".$txtIdIva."', '".$sesion_id_empresa."',
// 				NULL,'', '');";
// 		//echo $sql;
// 				$result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al guardar en ventas: '.mysql_error().' </p></div>  ');

//                 $id_ingreso = mysql_insert_id();
// //FIN INGRESOS
// $total=0;
// $idServicio=0;
//     //   $sheetCount = count($Reader->sheets());
//     $sheetCount = count($Reader->sheets());
//         for($i=0;$i<$sheetCount;$i++)
//         {
//         //   echo $i;
//           $Reader->ChangeSheet($i);
//     //   var_dump($Reader);
    
//     //  echo "==>4";
//           foreach ($Reader as $Row)
//           {
// // var_dump($Row);
//     if(trim($Row[0])=='' && trim($Row[1])==''){

//     goto end;
//     }
//             $producto = ($Row[0]!='')? mysqli_real_escape_string($con,$Row[0]): '' ;

//             // $existencia_minima = (isset($Row[2] )|| $Row[2]=='')?mysqli_real_escape_string($con,$Row[2]):'';
            
//             // $existencia_maxima = (isset($Row[3]) || $Row[3]=='')? mysqli_real_escape_string($con,$Row[3]): '' ;

//             $stock = ( $Row[1]!='')? mysqli_real_escape_string($con,$Row[1]): '0' ;
          
//             $costo = ( $Row[2]!='')? mysqli_real_escape_string($con,$Row[2]): '0' ;
         
//             $sqlCategoria= "SELECT `id_categoria` FROM `categorias` WHERE 
//             `id_empresa`=$sesion_id_empresa AND categoria='PRODUCTOS'";
//             // echo $sqlCategoria;
//             $resultCategoria= mysqli_query($con, $sqlCategoria);
//             $id_categoria=0;
//             while($rowCat=mysqli_fetch_array($resultCategoria)){
//                 $id_categoria = $rowCat['id_categoria'];
//             }

//             // $id_proveedor = (isset($Row[7]) )? mysqli_real_escape_string($con,$Row[7]): '' ;

          
//             $precio1 = ( $Row[3]!='')?$Row[3] : 0 ;
//             //   exit;
//             $precio2 = ( $Row[4]!='')? mysqli_real_escape_string($con,$Row[4]): 0 ;
            
//             $precio3 = ( $Row[5]!='')? mysqli_real_escape_string($con,$Row[5]): 0;
         
//             $precio4 = ( $Row[6]!='')? mysqli_real_escape_string($con,$Row[6]): 0 ;
            
//             $precio5 = ( $Row[7]!='')? mysqli_real_escape_string($con,$Row[7]): 0 ;
            
//             $precio6 = ( $Row[8]!='')? mysqli_real_escape_string($con,$Row[8]): 0 ;


//             $iva = ( $Row[9]!='')? mysqli_real_escape_string($con,$Row[9]): '' ;
            

            
//             // $fecha_registro = ( $Row[10]!='')? mysqli_real_escape_string($con,$Row[10]): date('Y-m-d') ;
//               echo $Row[10];
//           echo '<br>';
          
//               if( $Row[10]!=''){
         
//           $fecha = date_create_from_format('m-d-y', $Row[10]);
//           echo  $fecha_registro=date_format($fecha, 'Y-m-d');
//             }else{
//                 $fecha_registro = date('Y-m-d') ;
//             }
     

//             $ano = ( $Row[11]!='')? mysqli_real_escape_string($con,$Row[11]): date('Y') ;

//             $mes = ( $Row[12]!='')? mysqli_real_escape_string($con,$Row[12]): date('m') ;

            
//             $id_empresa = $sesion_id_empresa;
            
//             $codigo = ( $Row[13]!='')? mysqli_real_escape_string($con,$Row[13]): '' ;

//             $codPrincipal = ( $Row[14]!='')? mysqli_real_escape_string($con,$Row[14]): '';

//             $codAux = ( $Row[15]!='')? mysqli_real_escape_string($con,$Row[15]): '';

    
//             $id_cuenta = "0";
         
            
//             $tipos_compras= "";
//             $sqlGrupo= "SELECT `id_centro_costo`, `tipo` FROM `centro_costo` WHERE `tipo`='1' and empresa='$sesion_id_empresa'";
//             // echo $sqlGrupo;
//             $resultGrupo= mysqli_query($con, $sqlGrupo);
//             while($rowGrupo=mysqli_fetch_array($resultGrupo)){
            
//                 $grupo = $rowGrupo['id_centro_costo'];
//                 $tipos_compras =$rowGrupo['tipo'];
//             }
            
  

//                 $idBodega = $_POST['idBodega'];
//               echo $sqlExisteProducto = "SELECT `id_producto`, `producto`, `existencia_minima`, `existencia_maxima`, `stock`, `costo`, `id_categoria`, `id_proveedor`, `precio1`, `precio2`, `precio3`, `precio4`, `precio5`, `precio6`, `iva`, `ICE`, `IRBPNR`, `hab`, `ganancia1`, `ganancia2`, `fecha_registro`, `ano`, `mes`, `id_empresa`, `codigo`, `codPrincipal`, `codAux`, `tipos_compras`, `id_cuenta`, `tipo_costo`, `produccion`, `proceso`, `grupo`, `promocion`, `img`, `detalle`, `marca`, `modelo`, `tipo`, `color` FROM `productos` WHERE `codigo`= '".$codigo."' and id_empresa='".$sesion_id_empresa."' ";
//                 $resultExisteProducto = mysqli_query($con, $sqlExisteProducto);
               
//                 echo $numFilaExisteProducto = mysqli_num_rows( $resultExisteProducto);
// $idServicio=0;
//                 if($numFilaExisteProducto>0){
//                      while($row2 = mysqli_fetch_array($resultExisteProducto)){
//                           echo '|';
//                       echo   $idServicio = $row2['id_producto'];
//                         echo '|';
//                     }
                   
//                 }else{
//                     echo  $query = "INSERT INTO `productos`(`producto`, `existencia_minima`, `existencia_maxima`, `stock`, `costo`, `id_categoria`, `id_proveedor`, `precio1`, `precio2`, `precio3`, `precio4`, `precio5`, `precio6`, `iva`, `ICE`, `IRBPNR`, `hab`, `ganancia1`, `ganancia2`, `fecha_registro`, `ano`, `mes`, `id_empresa`, `codigo`, `codPrincipal`, `codAux`, `tipos_compras`, `id_cuenta`) VALUES ('$producto','0','0','$stock','$costo','$id_categoria','0','$precio1','$precio2','$precio3' ,'$precio4','$precio5', '$precio6' , '$iva' , '0','0','0', '0','0','$fecha_registro','$ano','$mes','$id_empresa','$codigo','$codPrincipal','$codAux' ,'$tipos_compras','$id_cuenta')";
//                     // $query = "insert into productos(nombres,cargo, celular, descripcion) values('".$nombres."','".$cargo."','".$celular."','".$descripcion."')";
//                     $resultados = mysqli_query($con, $query);
//                     echo '|';
//                   echo $idServicio = mysqli_insert_id($con);
//                      echo '|';
//                 }

               
             

//              echo   $sql5 = "SELECT `id`, `idBodega`, `idProducto`, `cantidad`, `proceso` FROM `cantBodegas` WHERE idBodega= $idBodega   and idProducto='".$codigo."'  ";
//                 $result5 = mysqli_query($con, $sql5);
//                 echo $numFilasCantBodegas = mysqli_num_rows($result5);

//                 if($numFilasCantBodegas>0){
//                     echo $sql3= "UPDATE `cantBodegas` SET `cantidad`=cantidad+'".$stock."' WHERE idBodega='".$idBodega."' and idProducto='".$codigo."' ";
//                     $result3= mysqli_query($con, $sql3);
    
//                 }else{
//                     echo $sql4 = "INSERT INTO `cantBodegas`( `idBodega`, `idProducto`, `cantidad`, `proceso`) VALUES ('".$idBodega."','".$codigo."','".$stock."','0')";
//                     $result4 = mysqli_query($con, $sql4);
//                 }

//                 $estado = "Activo";
//                 $valorTotal = 0;
//                 $valorTotal = $stock*$precio1;
                
//               echo  $sqlDI = "insert into detalle_ingresos (bodega, cantidad, estado, v_unitario, v_total, 					
//                 id_ingreso, id_producto,tipo_movim, id_empresa) values
                
//                 ('".$idBodega."','".$stock."','".$estado."','".$precio1."','".$valorTotal."',
//                 '".$id_ingreso."', '".$idServicio."', 'P','".$sesion_id_empresa."' );";
        
//                 $respDI = mysql_query($sqlDI) or die('<div class="transparent_ajax_error"><p>Error al guardar en detalles ventas: '.mysql_error().' </p></div>  ');
               
//                 $total = $total + $valorTotal ;
//               if (! empty($resultados)) {
//                 $type = "success";
//                 $message = "Excel importado correctamente";
//               } else {
//                 $type = "error";
//                 $message = "Hubo un problema al importar registros";
//               }
           
//           }
    
//         }
//     end:
        
// echo $sqlActualizarIngreso = "UPDATE `ingresos` SET `total`='".$total."' WHERE id_ingreso=$id_ingreso ";
// $resultActualizarIngreso = mysql_query($sqlActualizarIngreso);


//         $detalle="Saldo Inicial";	
// echo 'c';
//  $fechag = date("Y-m-d");
//       $sqlk="INSERT INTO kardes ( fecha, detalle,  id_factura, id_empresa)values('".$fechag."','".$detalle."','".$id_ingreso."', '".$sesion_id_empresa."')";
       
//       echo $sqlk;
//         $resultk=mysql_query($sqlk) ;
//       echo 'b';  
        
//       }
//       else
//       { 
//         $type = "error";
//       echo  $message = "El archivo enviado es invalido. Por favor vuelva a intentarlo";
//       }
//     }

if($accion == 21){
    $fecha = date("Y-m-d");
    
    $idArea = $_POST["idArea"];
    //   echo "==>accion 21";
//  
include('../conexion2.php');
require_once('../vendor/php-excel-reader/excel_reader2.php');
require_once('../vendor/SpreadsheetReader.php');
//   echo "==>2";

  $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
  
  if(in_array($_FILES["file"]["type"],$allowedFileType)){

 $targetPath = 'subidas/'.$_FILES['file']['name'];
    move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
  
//   echo "==>3";
  try {
    $Reader = new SpreadsheetReader($targetPath);
} catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
}
   
$con = $conexion;

//INICIO INGRESOS
  $sqlIva1="Select * From impuestos where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
$resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;
$iva=0;
while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
{
    $iva=$rowIva1['iva'];
    $txtIdIva=$rowIva1['id_iva'];
    $impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
}

$sqlNI = "SELECT MAX(numero) as numero from ingresos where  id_empresa='".$sesion_id_empresa."';";
$respNI = mysql_query($sqlNI);
while($row=mysql_fetch_array($respNI))//permite ir de fila en fila de la tabla
{
    $var=$row["numero"];
}
$var++;
$sql="insert into ingresos ( fecha, estado, total, sub_total, numero, 
            fecha_anulacion, descripcion, id_iva, id_empresa,id_cuenta, tipo_documento, observacion) 
            values ('".$fecha."','Activo','0','0','".$var."',
            NULL,'', '".$txtIdIva."', '".$sesion_id_empresa."',
            NULL,'', '');";
    // echo $sql;
            $result=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error al guardar ingreso: '.mysql_error().' </p></div>  ');

            $id_ingreso = mysql_insert_id();
//FIN INGRESOS
$total=0;
$idServicio=0;
//   $sheetCount = count($Reader->sheets());
 $sheetCount = count($Reader->sheets());

    for($i=0;$i<$sheetCount;$i++)
    {
      // echo $i;
      $Reader->ChangeSheet($i);
  // var_dump($Reader);


      foreach ($Reader as $Row)
      {
// var_dump($Row);
if(trim($Row[0])=='' && trim($Row[1])==''){

goto end;
}
      
        $producto = ($Row[0]!='')? mysqli_real_escape_string($con,$Row[0]): '' ;
        

        // $existencia_minima = (isset($Row[2] )|| $Row[2]=='')?mysqli_real_escape_string($con,$Row[2]):'';
        
        // $existencia_maxima = (isset($Row[3]) || $Row[3]=='')? mysqli_real_escape_string($con,$Row[3]): '' ;

        $stock = ( $Row[1]!='')? mysqli_real_escape_string($con,$Row[1]): '0' ;
      
        $costo = ( $Row[2]!='')? mysqli_real_escape_string($con,$Row[2]): '0' ;
     
        $sqlCategoria= "SELECT `id_categoria` FROM `categorias` WHERE 
        `id_empresa`=$sesion_id_empresa AND categoria='PRODUCTOS'";
        // echo $sqlCategoria;
        $resultCategoria= mysqli_query($con, $sqlCategoria);
        $id_categoria=0;
        while($rowCat=mysqli_fetch_array($resultCategoria)){
            $id_categoria = $rowCat['id_categoria'];
        }

        // $id_proveedor = (isset($Row[7]) )? mysqli_real_escape_string($con,$Row[7]): '' ;

      
        $precio1 = ( $Row[3]!='')?$Row[3] : 0 ;
        //   exit;
        $precio2 = ( $Row[4]!='')? mysqli_real_escape_string($con,$Row[4]): 0 ;
        
        $precio3 = ( $Row[5]!='')? mysqli_real_escape_string($con,$Row[5]): 0;
     
        $precio4 = ( $Row[6]!='')? mysqli_real_escape_string($con,$Row[6]): 0 ;
        
        $precio5 = ( $Row[7]!='')? mysqli_real_escape_string($con,$Row[7]): 0 ;
        
        $precio6 = ( $Row[8]!='')? mysqli_real_escape_string($con,$Row[8]): 0 ;
        
        

    
    $valorNormalizado = strtolower(mysqli_real_escape_string($con, $Row[9]));

    $sqlImpuestos ="SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` 
    FROM `impuestos` WHERE id_empresa='".$sesion_id_empresa."' AND  iva='".$valorNormalizado."'  ";
    $resultImpuestos = mysql_query( $sqlImpuestos );
    $numFilasImpuestos= mysql_num_rows($resultImpuestos);
    $iva=0;
    if( $numFilasImpuestos >0){
        while($rowImp = mysql_fetch_array( $resultImpuestos) ){
            $iva=$rowImp['id_iva'];
        }
    }
    


        $fecha_registro = date('Y-m-d') ;

        $ano = date('Y') ;

        $mes = date('m') ;


        
        $id_empresa = $sesion_id_empresa;
        
        $codigo = ( $Row[10]!='')? mysqli_real_escape_string($con,$Row[10]): '' ;
        $codigo=trim($codigo);
        $codPrincipal = ( $Row[11]!='')? mysqli_real_escape_string($con,$Row[11]): '';

        $codAux = ( $Row[12]!='')? mysqli_real_escape_string($con,$Row[12]): '';
        
        $marca = ( $Row[13]!='')? mysqli_real_escape_string($con,$Row[13]): '' ;

        $modelo = ( $Row[14]!='')? mysqli_real_escape_string($con,$Row[14]): '';

        $tipo = ( $Row[15]!='')? mysqli_real_escape_string($con,$Row[15]): '';
        $color = ( $Row[16]!='')? mysqli_real_escape_string($con,$Row[16]): '';


        $id_cuenta = "0";
     
        
        $tipos_compras= "";
        $sqlGrupo= "SELECT `id_centro_costo`, `tipo`,id_cuenta FROM `centro_costo` WHERE id_centro_costo=$idArea and empresa='$sesion_id_empresa'";
        // echo $sqlGrupo;
        $resultGrupo= mysqli_query($con, $sqlGrupo);
        while($rowGrupo=mysqli_fetch_array($resultGrupo)){
        
            $grupo = $rowGrupo['id_centro_costo'];
            $tipos_compras =$rowGrupo['tipo'];
            $id_cuenta =$rowGrupo['id_cuenta'];
        }
        


            $idBodega = $_POST['idBodega'];
             $sqlExisteProducto = "SELECT `id_producto`, `producto`, `existencia_minima`, `existencia_maxima`, `stock`, `costo`, `id_categoria`, `id_proveedor`, `precio1`, `precio2`, `precio3`, 
             `precio4`, `precio5`, `precio6`, `iva`, `ICE`, `IRBPNR`, `hab`, `ganancia1`, `ganancia2`, `fecha_registro`, `ano`, `mes`, `id_empresa`, `codigo`, `codPrincipal`,
             `codAux`, `tipos_compras`, `id_cuenta`, `tipo_costo`, `produccion`, `proceso`, `grupo`, `promocion`, `img`, `detalle`, `marca`, `modelo`, `tipo`, `color` 
             FROM `productos` WHERE `codigo`= '".$codigo."' and id_empresa='".$sesion_id_empresa."' ";
            $resultExisteProducto = mysqli_query($con, $sqlExisteProducto);
           
            $numFilaExisteProducto = mysqli_num_rows( $resultExisteProducto);
$idServicio=0;
            if($numFilaExisteProducto>0){
                 while($row2 = mysqli_fetch_array($resultExisteProducto)){
                    
                   $idServicio = $row2['id_producto'];
                    
                }
               
            }else{
  $query = "INSERT INTO `productos`(`producto`, `existencia_minima`, `existencia_maxima`, `stock`, `costo`, `id_categoria`, `id_proveedor`, `precio1`,
           `precio2`, `precio3`, `precio4`, `precio5`, `precio6`, `iva`, `ICE`, `IRBPNR`, `hab`, `ganancia1`, `ganancia2`, `fecha_registro`, `ano`, `mes`, `id_empresa`, 
           `codigo`, `codPrincipal`, `codAux`, `tipos_compras`, `id_cuenta`,`grupo`,`marca`,`modelo`,`tipo`,`color`) VALUES
           ('$producto','0','0','$stock','$costo','$id_categoria','0','$precio1','$precio2','$precio3' ,'$precio4','$precio5', '$precio6' , '$iva' , 
           '0','0','0', '0','0','$fecha_registro','$ano','$mes','$id_empresa','$codigo','$codPrincipal','$codAux' ,'$tipos_compras','$id_cuenta','".$idArea."',
           '".$marca."','".$modelo."','".$tipo."','".$color."')";

$resultados = mysqli_query($con, $query) or die('<div class="transparent_ajax_error"><p>Error al guardar producto '.$producto.' con codigo:'.$codigo.' =>'.mysqli_error($con).' </p></div>');

                
            $idServicio = mysqli_insert_id($con);
                 
            }

           
         

            $sql5 = "SELECT `id`, `idBodega`, `idProducto`, `cantidad`, `proceso` FROM `cantBodegas` WHERE idBodega= $idBodega   and idProducto='".$codigo."'  ";
            $result5 = mysqli_query($con, $sql5);
            $numFilasCantBodegas = mysqli_num_rows($result5);

            if($numFilasCantBodegas>0){
                
                $sql3= "UPDATE `cantBodegas` SET `cantidad`=cantidad+'".$stock."' WHERE idBodega='".$idBodega."' and idProducto='".$codigo."' ";
                $result3= mysqli_query($con, $sql3)or die('<div class="transparent_ajax_error"><p>Error al actualizar cantbodega del producto: '.$codigo.'-'.$stock.' => '.mysql_error().' </p></div>  ');

            }else{
                $sql4 = "INSERT INTO `cantBodegas`( `idBodega`, `idProducto`, `cantidad`, `proceso`) VALUES ('".$idBodega."','".$codigo."','".$stock."','0')";
                $result4 = mysqli_query($con, $sql4)or die('<div class="transparent_ajax_error"><p>Error al guardar cantbodega del producto: '.$codigo.'-'.$stock.' => '.mysql_error().' </p></div>  ');
            }

            $estado = "Activo";
            $valorTotal = 0;
            $valorTotal = $stock*$precio1;
            
            $sqlDI = "insert into detalle_ingresos (bodega, cantidad, estado, v_unitario, v_total, 					
            id_ingreso, id_producto,tipo_movim, id_empresa) values
            
            ('".$idBodega."','".$stock."','".$estado."','".$precio1."','".$valorTotal."',
            '".$id_ingreso."', '".$idServicio."', 'P','".$sesion_id_empresa."' );";
    
            $respDI = mysql_query($sqlDI) or die('<div class="transparent_ajax_error"><p>Error al guardar en detalles ingresos del producto :'.$idServicio.' => '.mysql_error().' </p></div>  ');
           
            $total = $total + $valorTotal ;
        
       
      }

    }
end:
    
$sqlActualizarIngreso = "UPDATE `ingresos` SET `total`='".$total."' WHERE id_ingreso=$id_ingreso ";
$resultActualizarIngreso = mysql_query($sqlActualizarIngreso)or die('<div class="transparent_ajax_error"><p>Error al guardar ingreso: '.mysql_error().' </p></div>  ');


    $detalle="Saldo Inicial";	
$fechag = date("Y-m-d");
  $sqlk="INSERT INTO kardes ( fecha, detalle,  id_factura, id_empresa)values('".$fechag."','".$detalle."','".$id_ingreso."', '".$sesion_id_empresa."')";
    $resultk=mysql_query($sqlk)or die('<div class="transparent_ajax_error"><p>Error al guardar kardes: '.mysql_error().' </p></div>  ') ;

    if( $resultk && $resultActualizarIngreso ){
        echo '1';
    }else{
        echo '2';
    }

    
  }
  else
  { 
    $type = "error";
   echo  $message = "El archivo enviado es invalido. Por favor vuelva a intentarlo";
  }
}


if($accion == 22){
    $conn =$conexion;
    $id_producto = $_POST['id_producto'];
    $codigo_anterior= $_POST['codigo_anterior'];
    $codigo= $_POST['codigo'];

    
     $sql1 = "SELECT productos.codigo, productos.id_producto,  IF(cantBodegas.cantidad IS NULL , 0,cantBodegas.cantidad) AS cantidad ,productos.producto, cantBodegas.id as cantbodegas_id
            FROM productos
            LEFT JOIN cantBodegas ON cantBodegas.idProducto = productos.codigo 
            WHERE productos.id_empresa = '".$sesion_id_empresa."' AND productos.id_producto='".$id_producto."'  GROUP BY productos.id_producto ORDER BY  cantBodegas.cantidad LIMIT 1";
   
           $result1 = $conn->query($sql1);
           $cantidadReal =0;
           while( $row1 = $result1->fetch_assoc() ){
            //    $codigo = $row1["codigo"];
               $idProducto = $row1["id_producto"];
               $cantidadBodegas = $row1["cantidad"];
               $producto = $row1["producto"];
       
               // Consulta para obtener la suma de compras
               $sqlCompras = "SELECT SUM(cantidad) AS suma_compras
                              FROM detalle_compras
                              WHERE id_producto = '$idProducto'";
       
               $resultCompras = $conn->query($sqlCompras);
               $rowCompras = $resultCompras->fetch_assoc();
               $sumaCompras = $rowCompras["suma_compras"];

            //    echo '$sumaCompras='.$sumaCompras;
       
               // Consulta para obtener la suma de ingresos
               $sqlIngresos = "SELECT SUM(cantidad) AS suma_ingresos
                               FROM detalle_ingresos
                               WHERE id_producto = '$idProducto'";
       
               $resultIngresos = $conn->query($sqlIngresos);
               $rowIngresos = $resultIngresos->fetch_assoc();
               $sumaIngresos = $rowIngresos["suma_ingresos"];
            //    echo 'sumaIngresos='.$sumaIngresos;
       
               // Consulta para obtener la suma de ventas
               $sqlVentas = "SELECT SUM(cantidad) AS suma_ventas
                             FROM detalle_ventas
                             WHERE id_servicio = '$idProducto'";
       
               $resultVentas = $conn->query($sqlVentas);
               $rowVentas = $resultVentas->fetch_assoc();
               $sumaVentas = $rowVentas["suma_ventas"];
            //    echo 'sumaVentas='.$sumaVentas;
       
               // Consulta para obtener la suma de egresos
               $sqlEgresos = "SELECT SUM(cantidad) AS suma_egresos
                              FROM detalle_egresos
                              WHERE id_producto = '$idProducto'";
       
               $resultEgresos = $conn->query($sqlEgresos);
               $rowEgresos = $resultEgresos->fetch_assoc();
               $sumaEgresos = $rowEgresos["suma_egresos"];
            //    echo 'sumaEgresos='.$sumaEgresos;
       
               // Calcular la cantidad real
               $cantidadReal = ($sumaCompras + $sumaIngresos) - ($sumaVentas + $sumaEgresos);

            $totalR= $cantidadReal;

                $sqlBodegaR = "SELECT cantBodegas.id as canbodegas_id, cantBodegas.cantidad 
               FROM cantBodegas 
               INNER JOIN bodegas ON bodegas.id = cantBodegas.idBodega 
               WHERE bodegas.id_empresa=$sesion_id_empresa AND cantBodegas.idProducto='".$codigo_anterior."'  ";
               $resultBodegaR  = mysql_query($sqlBodegaR);
               $recorridos =0;
               while($rowBR = mysql_fetch_array($resultBodegaR ) ){

            
                if($totalR >= $rowBR['cantidad']){
                    $totalR = $totalR -$rowBR['cantidad'];
                    $sqlRestar = "UPDATE cantBodegas SET cantidad=0 WHERE id='".$rowBR['canbodegas_id']."' ";
                    $resultRestar = mysql_query($sqlRestar);
                }else{
                    $sqlRestar = "UPDATE cantBodegas SET cantidad=cantidad-$totalR WHERE id='".$rowBR['canbodegas_id']."' ";
                    $resultRestar = mysql_query($sqlRestar);
                    $totalR = 0;
                    break;
                }
                // echo '$recorridos'.$recorridos;
                $recorridos++;
               }

         
            //    $sumaIngreso=($sumaCompras + $sumaIngresos); 
           }

     $sqlBodega = "SELECT cantBodegas.id as canbodegas_id, cantBodegas.cantidad 
    FROM cantBodegas 
    INNER JOIN bodegas ON bodegas.id = cantBodegas.idBodega 
    WHERE bodegas.id_empresa=$sesion_id_empresa AND cantBodegas.idProducto='".$codigo."' LIMIT 1 ";
    $resultBodega = mysql_query( $sqlBodega );
    $numFila = mysql_num_rows($resultBodega);

    if($numFila>0){
        while($rowB = mysql_fetch_array( $resultBodega) ){
           $sqlSumar = "UPDATE `cantBodegas` SET `cantidad`=cantidad+$cantidadReal WHERE id = '".$rowB['canbodegas_id']."' ";
           $resultSumar = mysql_query($sqlSumar);
        }
    }else{

        $sqlBodegaPorDefecto =" SELECT `id`, `detalle`, `id_empresa` FROM `bodegas` WHERE  id_empresa = $sesion_id_empresa LIMIT 1" ;
        $resultBodegaPorDefecto = mysql_query($sqlBodegaPorDefecto);
        $id_nueva_bodega =0;
        while($rowBDP = mysql_fetch_array($resultBodegaPorDefecto) ){
            $id_nueva_bodega = $rowBDP['id'];
        }

         $sqlNuevaBodega="INSERT INTO `cantBodegas`( `idBodega`, `idProducto`, `cantidad`, `proceso`) VALUES ('".$id_nueva_bodega."','".$codigo."','".$cantidadReal."',0)";
        $resultNuevaBodega = mysql_query( $sqlNuevaBodega);
        // if($resultNuevaBodega){
        //     echo '1';
        // }else{
        //     echo '2';
        // }

    }
   
     $sql = "UPDATE `productos` SET `codigo`='".$codigo."'  WHERE id_producto=$id_producto AND id_empresa=$sesion_id_empresa";
    $result = mysql_query($sql);
    if($result){
        echo '1';
    }else{
        echo '2';
    }
}


if($accion == 23){
    $mostrar = $_POST['mostrar'];
    $id_producto =  $_POST['id_producto'];
    $icono = 0;
    if($mostrar==0){
        $icono =1;
    }
    
    $sql="UPDATE `productos` SET `mostrar`='".$icono."' WHERE id_producto=$id_producto";
    $result = mysql_query($sql);
    if($result){
        echo '1';
    }else{
        echo '0';
    }
}
?>