<?php

    require_once('../ver_sesion.php');

    //Start session
    session_start();

    //Include database connection details
    require_once('../conexion.php');

    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $accion = $_POST['txtAccion'];
 
   
   if($accion == '1'){
        $filas=$_POST['filas'];
        $txtCodigo=$_POST['txtCodigoB'.$filas];
		$txtDescripcion= $_POST['txtDescripcion'.$filas];
	    $sql1 = "SELECT * from centro_costo where empresa ='".$sesion_id_empresa."' and codigo='".$txtCodigo."' and descripcion='".$txtDescripcion."'   ";

		$resultado = mysql_query($sql1);
		$fila=mysql_num_rows($resultado);
		if ($fila>0)
		{	?>  <div class='alert alert-danger'><p>Registro ya existe</p></div> <?php	} 
	else{
        $filas=$_POST['filas'];
        $id_bodega=$_POST['txtBodega'.$filas];
        $txtCodigo=$_POST['txtCodigoB'.$filas];	
        $descripcion=ucwords($_POST['txtDescripcion'.$filas]);
        $cmbCuenta=$_POST['txtIdCuenta'.$filas];
        $cmbTipoMovimiento=$_POST['cmbTipoMovimientoFVC'.$filas];
                    

        $sql = "insert into centro_costo( id_bodega,            codigo,       descripcion,          id_cuenta,          tipo,               empresa) values 
                                        ('".$txtCodigo."','".$txtCodigo."','".$descripcion."',  '".$cmbCuenta."','".$cmbTipoMovimiento."','".$sesion_id_empresa."'); ";
        $result = mysql_query($sql);
           
        if ($result){  ?>1<?php }
		else { ?> <div class='transparent_ajax_error'><p>Error al guarda en la tabla centro de costo: 	<?php echo mysql_error();?> </p></div> <?php }
        }
    }

   
    if($accion == '2'){
       // GUARDAR MODIFICACION PRODUCTOS PAGINA: productos.php
     try
     {
        $txtIdProducto = $_POST['txtIdProducto'];
        $cmbCategoria = $_POST['cmbCategoria'];
        $txtProducto = ucwords($_POST['txtProducto']);
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

          $sql2 = "update productos set producto='".$txtProducto."', existencia_minima='".$txtExistenciaMinima."', existencia_maxima='".$txtExistenciaMaxima."', id_categoria='".$cmbCategoria."', id_proveedor='".$cmbProveedor."', WHERE id_producto='".$txtIdProducto."'; ";
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

    


    if($accion == "4")
	{
    // VALIDA PARA QUE EL NOMBRE DEL PRODUCTO NO SE REPITA PAGINA: productos.php
		try
		{
			if(isset ($_POST['centrocosto']))
			{
				$centrocosto = $_POST['centrocosto'];
 
				$sql1 = "SELECT descripcion from centro_costo where empresa ='".$sesion_id_empresa."' and descripcion='".$centrocosto."'";

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
			}

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
            $consulta6="SELECT * FROM tipos_compras order by descripcion asc;";
            echo $consulta6;
			$result6=mysql_query($consulta6);
            while($row6=mysql_fetch_array($result6))//permite ir de fila en fila de la tabla
                {
                    $cadena6=$cadena6."?".$row6['id_tipo_cpra']."?".$row6['descripcion'];
                }
            echo $cadena6;

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
    }


    if($accion == '20'){

    try{
        
        
        $filas=$_POST['NumeroFilaSeleccionada'];
        $id=$_POST['idFormaCobro'];
        
        $id_bodega=$_POST['txtBodega'.$filas];
        $txtCodigo=$_POST['txtIdFormaCobro'.$filas];	
        $descripcion=ucwords($_POST['txtDescripcion'.$filas]);
        $cmbCuenta=$_POST['txtIdCuenta'.$filas];
        
        $cmbTipoCompra=$_POST['cmbTipoCompra'.$filas];
           $tarifa=0;
           
          if( isset( $_POST['hotel'.$filas] ) ){
            $tarifa=1;
        }
       
          $sql = "update centro_costo set codigo='".$txtCodigo."', descripcion='".$descripcion."', id_cuenta='".$cmbCuenta."', 
          
          tipo='".$cmbTipoCompra."' ,  tarifa='".$tarifa."' where id_centro_costo='".$id."'  ";
          
       
          
          $result = mysql_query($sql);
      //    echo $sql;
          
          if ($result)
          {  ?>1<?php    }
		  else 
			{ ?> <div class='transparent_ajax_error'><p>Error al actualizar en la tabla centro de costo: 	<?php echo mysql_error();?> </p></div> <?php }

     }catch (Exception $e) {
     // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
     }
   
   }
    if($accion == 7){
       
    $id_centro_costo = $_POST['id_centro_costo'];

    $consulta="UPDATE `centro_costo` SET `predeterminado`=0 WHERE empresa=$sesion_id_empresa";
    $result=mysql_query($consulta);

    if($result){
        $consulta6="UPDATE `centro_costo` SET `predeterminado`=1 WHERE id_centro_costo=$id_centro_costo and empresa=$sesion_id_empresa";
    $result6=mysql_query($consulta6);
    if($result6){
        echo '1';
    }else{
        echo '2';
    }
    }else{
        echo '2';
    }
    
}
    
     




?>