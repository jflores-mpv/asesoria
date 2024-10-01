<?php   

    //Start session
    session_start();

    //Include database connection details
    require_once('../conexion.php');
	$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
//	echo $sesion_id_empresa;
//	$sesion_id_periodo_contable=$_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
	$sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
//echo $sesion_tipo_empresa;  
    date_default_timezone_set('America/Guayaquil');
    
    	$emision_codigo = $_SESSION["emision_codigo"];
	$establecimiento_codigo = $_SESSION["establecimiento_codigo"];

    $accion=$_POST['txtAccion'];
    //echo "estoy en sql".$accion;

	function insertar_cheque($sesion_id_periodo_contable,$descripcion)
	{
	  $sesion_id_periodo_contable1=  $sesion_id_periodo_contable;
	  $descripcion1=$descripcion;
	//  "va chea";
	//  echo $sesion_id_periodo_contable1;
	  $txtContadorFilas=12;	
	  $lin_diario=0;	
	  for($i=1; $i<=$txtContadorFilas; $i++)
	  {
        if ($_POST['txtCuentaS'.$i] >=1)
		{   // verifica si en el campo esta agregada una cuenta, // permite sacar el id maximo de detalle_libro_diario
			$lin_diario=$lin_diario+1;
			$idPlanCuentas[$lin_diario]=$_POST['txtCuentaS'.$i];
			$debeVector[$lin_diario]=0;
			$haberVector[$lin_diario]=$_POST['txtValorS'.$i];
            $fechas_pag_ven[$lin_diario]=date("Ymd"); 
            $nro_cpte[$lin_diario]=$_POST['nrocpteC'.$i];
            $bancos[$lin_diario]='';
            
            if (strtolower($_POST['txtTipoP'.$i]) =='cheque' 
            or strtolower($_POST['txtTipoP'.$i]) =='deposito' 
            or strtolower($_POST['txtTipoP'.$i]) =='TransferenciaC'){
               $bancos[$lin_diario]=strtolower($_POST['txtTipoP'.$i]);
            }
		}	
		/* if ($_POST['txtTipoP'.$i]=='credito')
		{
				$total=$_POST['txtValorS'.$i];
				$txtCuotasTP=$_POST['txtCuotaS'.$i];
				$formas_pago_id_plan_cuenta=$_POST['txtCuentaS'.$i];		
			}*/
	  } 
      $i=1;
	  for ($i=1; $i<=$lin_diario; $i++)
	  {
		if ($idPlanCuentas[$i] !="")
		{   
          if ($bancos[$i] != "")
		  {
            $sql_suc="SELECT id_bancos from bancos where id_plan_cuenta ='".$idPlanCuentas[$i]."';";
		//	//echo $sql_suc;
            $res_suc= mysql_query($sql_suc) or die('<div class="transparent_ajax_error"><p>Error 64: '.mysql_error().' </p></div>  ');
            $nro_bancos = mysql_num_rows($res_suc);

            if ($nro_bancos ==1)
			{
			//	//echo "si existe";
			  $id_bancos_suc=0;
              while($row_suc=mysql_fetch_array($res_suc))
			  {
                $id_bancos_suc=$row_suc['id_bancos'];
              }
			  
			  $sql="Select max(id_detalle_banco)+1 as id_max From detalle_bancos ";
              $resultado=mysql_query($sql);
              $id_detalle_banco=0;
              while ($row=mysql_fetch_array($resultado))
			  {
                $id_detalle_banco=$row['id_max'];
              }
			  
         echo     $sql_bancos = "insert into detalle_bancos (tipo_documento,numero_documento,
			      detalle,valor,fecha_cobro,fecha_vencimiento,id_bancos,estado,id_libro_diario) values
				('".ucwords($bancos[$i])."','".ucfirst($nro_cpte[$i])."','".$descripcion."',
				'".$haberVector[$i]."','".$fechas_pag_ven[$i]."','".$fechas_pag_ven[$i]."',
				'".$id_bancos_suc."','No Conciliado', '".$id_libro_diario."' );";
		//		//echo $sql_bancos;
              $res_bancos = mysql_query($sql_bancos) or die('<div class="transparent_ajax_error"><p>Error 90: '.mysql_error().' </p></div>  ');
    		  $id_detalle_banco =mysql_insert_id();      
			}
			else 
			{
				////echo "no existe";
               // 1. determinar id proximo del banco de la empresa actual
                        // 2. crear el banco en la table respectiva (bancos)
                        //$sql_nuevo_banco="Select max(id_bancos)+1 From bancos where id_plan_cuenta='".$idPlanCuentas[$i]." and id_periodo_contable='".$

              $sql_nuevo_banco="Select max(id_bancos)+1 as nuevo_banco From bancos ";
              $res_nuevo_banco=mysql_query($sql_nuevo_banco);
              $id_bancos=0;
              while ($row_nuevo_banco=mysql_fetch_array($res_nuevo_banco))
			  {
                $id_bancos=$row_nuevo_banco['nuevo_banco'];
              }

              $sql_crea_banco = "insert into bancos ( id_plan_cuenta,id_periodo_contable) values 
			  ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable1."');";
          //    //echo $sql_crea_banco;
			  $res_crea_banco=mysql_query($sql_crea_banco) or die('<div class="transparent_ajax_error"><p>Error 111: '.mysql_error().' </p></div>  ');
			$id_bancos=mysql_insert_id();
              $sql_suc="SELECT id_bancos from bancos where id_plan_cuenta ='".$idPlanCuentas[$i]."';";
              $res_suc= mysql_query($sql_suc) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
              $nro_bancos = mysql_num_rows($res_suc);

              $id_bancos_suc=0;
              while($row_suc=mysql_fetch_array($res_suc))
			  {
                $id_bancos_suc=$row_suc['id_bancos'];
              }

			  $sql="Select max(id_detalle_banco)+1 as id_max From detalle_bancos ";
              $resultado=mysql_query($sql);
              $id_detalle_banco=0;
              while ($row=mysql_fetch_array($resultado))
			  {
                $id_detalle_banco=$row['id_max'];
              }

              $sql_bancos = "insert into detalle_bancos (tipo_documento,numero_documento,detalle,valor,
			       fecha_cobro,fecha_vencimiento,id_bancos,estado,id_libro_diario) values 
			       ('".ucwords($bancos[$i])."','".ucfirst($nro_cpte[$i])."','".$descripcion."',
				   '".$haberVector[$i]."','".$fechas_pag_ven[$i]."','".$fechas_pag_ven[$i]."',
				   '".$id_bancos_suc."','No Conciliado', '".$id_libro_diario."' );";
////echo $sql_bancos;
              $res_bancos = mysql_query($sql_bancos) or die('<div class="transparent_ajax_error"><p>Error137: '.mysql_error().' </p></div>  ');
			  $id_detalle_banco=mysql_insert_id();

            }
          }
			
    	}         		
      }
	}
			
		  

		 

	


if($accion == "1")
{
 // GUARDAR FACTURA COMPRA PAGINA: nuevaFacturaCompra.php
    try
    {
		$id_compra=$_POST['textIdCompra'];
		$numero_factura=$_POST['txtFactura'];
		$fecha_compra=$_POST['textFecha'];
		$total=$_POST['txtTotal'];
		$subTotal0=$_POST['subTotal0'];
		
	// 	$subTotal12=$_POST['subTotal12'];
		
		$subTotal12="0.00";
		
		$sub_total=$_POST['txtSubtotal'];
		$iva=$_POST['txtIdIva'];
		$id_proveedor=$_POST['textIdProveedor'];
		$txtNombreRuc=$_POST['txtNombreRuc'];
		$txtIva=$_POST['txtIva'];
		$cmbTipoDocumentoFVC='Compra #'; 
		$numero_compra=$_POST['txtFactura'];
	//	$formas_pago_id_plan_cuenta=0;
		$cmbTipoCompra=$_POST['cmbTipoCompra'];
		$numSerie=$_POST['txtSerie'];
		
		$txtEmision=$_POST['txtEmision'];
		$txtNum=$_POST['txtNum'];
		
		$autorizacion=$_POST['txtAutorizacion'];
		$txtFechaVencimiento=$_POST['txtFechaVencimiento'];
		$txtTipoComprobante=$_POST['txtTipoComprobante'];
		$codSustento=$_POST['codSustento'];
		
		$txtContadorFilas=$_POST['txtContadorFilasCpra'];

        
	    $txtSubtotalInventarios=($_POST['txtSubtotalInventarios']=='')?'0.00':$_POST['txtSubtotalInventarios'];
		
		$txtSubtotalProveeduria=($_POST['txtSubtotalProveeduria']=='')?'0.00':$_POST['txtSubtotalProveeduria'];
		
		$txtSubtotalServicios=($_POST['txtSubtotalServicios']=='')?'0.00':$_POST['txtSubtotalServicios'];
        $txtDescuento = (trim($_POST['txtDescuento'])=='')?0:$_POST['txtDescuento'];
	$response = [];
		if($fecha_compra!="" && $id_proveedor!="" )	{
		    
		   $sqlCompras= "UPDATE `compras` SET `fecha_compra`='".$fecha_compra."',`total`='".$total."',`sub_total`='".$sub_total."',`subTotal0`='".$subTotal0."',`subTotal12`='".$subTotal12."',`subTotalInvenarios`='".$txtSubtotalInventarios."',`descuento`='".$txtDescuento." ',`id_iva`='".$iva."',`id_proveedor`='".$id_proveedor."',`numero_factura_compra`='".$numero_factura."',`numSerie`='".$numSerie."',`txtEmision`='".$txtEmision."',`txtNum`='".$txtNum."',`autorizacion`='".$autorizacion."',`caducidad`='".$txtFechaVencimiento."',`TipoComprobante`='".$txtTipoComprobante."',`codSustento`='".$codSustento."',`subTotalProveeduria`='".$txtSubtotalProveeduria."',`subtotalServicios`= '".$txtSubtotalServicios."',`total_iva`= '".$txtIva."'  WHERE `id_compra`='".$id_compra."' ";
		    
	   // $sqlCompras = "UPDATE `compras` SET `subTotalInvenarios`='".$txtSubtotalInventarios."' ,`subTotalProveeduria`='".$txtSubtotalProveeduria."',`subtotalServicios`='".$txtSubtotalServicios."' WHERE `id_compra`='".$id_compra."'";
		   $resultUpdateCompras=mysql_query($sqlCompras) or die("\nError al actualizar  la compra : ".mysql_error());
		    
		$response['compras']=$sqlCompras;
		$response['comprasG']=$resultUpdateCompras;
		$response['txtContadorFilas']=$txtContadorFilas;
		
		$sqlVerDetalle = "SELECT
    detalle_compras.`id_detalle_compra`,
    detalle_compras.`idBodega`,
    detalle_compras.`idBodegaInventario`,
    detalle_compras.`cantidad`,
    detalle_compras.`valor_unitario`,
    detalle_compras.`des`,
    detalle_compras.`valor_total`,
    detalle_compras.`id_compra`,
    detalle_compras.`id_producto`,
    detalle_compras.`id_empresa`,
    detalle_compras.`xml`,
    detalle_compras.`centro_costo_empresa`,
    productos.codigo
FROM
    `detalle_compras`
INNER JOIN productos ON productos.id_producto = detalle_compras.id_producto WHERE   detalle_compras.id_compra='".$id_compra."' AND detalle_compras.`id_empresa`='".$sesion_id_empresa."'";
$response['sqlVerDetalle'][]=$sqlVerDetalle;
		
		$resultDetalle = mysql_query($sqlVerDetalle);
		
		while($rowD = mysql_fetch_array($resultDetalle) ){
		    $cantidadD= $rowD['cantidad'];
		    $idBodegaD= $rowD['idBodegaInventario'];
		    $codProducto2D= $rowD['codigo'];
		 
		    $stockBodegasD="SELECT id,cantidad FROM `cantBodegas` WHERE idBodega='".$idBodegaD."' and idProducto='".$codProducto2D."' ";
		    
		    $response['verifica_bodega'][]=$stockBodegasD;
            $resultadoD = mysql_query($stockBodegasD);
            
            while($rowBodegas1D=mysql_fetch_array($resultadoD)){
            	$idBodegaCantidadD=$rowBodegas1D['id'];

            	$sqlbodegasD="UPDATE `cantBodegas` set `cantidad`=cantidad-".$cantidadD." WHERE id='".$idBodegaCantidadD."' and idProducto='".$codProducto2D."' ";
            	$response['restauraCantbodegas'][]=$sqlbodegasD;
    	       $resultBodegasD=mysql_query($sqlbodegasD);
            }
            				
		       
		}
		
		 $sqlDeleteDetalle="DELETE FROM `detalle_compras` WHERE id_compra='".$id_compra."' AND `id_empresa`='".$sesion_id_empresa."' ";
		 $resultDeleteDetalle = mysql_query($sqlDeleteDetalle);
		 $response['deleteDetalle'][]=$sqlDeleteDetalle;
        $response['deleteDetalleR'][]=$resultDeleteDetalle;
        	
        for($i=1; $i<=$txtContadorFilas; $i++){
             
                    $id_producto2=$_POST['idproducto'.$i];
                    $id_producto=(int)$id_producto2;
                    $idbod2=$_POST['idbod'.$i];
                    $idbod=(int)$idbod2;
                    $idcuenta2=$_POST['cuenta'.$i];
                    $idcuenta=(int)$idcuenta2;
                    
                    $cantidad2=$_POST['cant'.$i];
                    $cantidad=$cantidad2;
                    $valor_unitario2=$_POST['valun'.$i];
                    $valor_unitario=$valor_unitario2;
                    $valor_total2=$_POST['valtotal'.$i];
                    $valor_total=$valor_total2;
                    $tipo2=$_POST['idTipo'.$i];
                    
                     $iva_producto = $_POST['ivaS'.$i]; 
                    $codProducto2=$_POST['codProducto'.$i];
                    $codPrincipal=$_POST['codPrincipal'.$i];
                    $codAux=$_POST['codAux'.$i];
                    
                    $bodInventario1=$_POST['bodInventario'.$i];
                    $centroCosto = (trim($_POST['centrodeCostoEmpresa'.$i])!='0')?trim($_POST['centrodeCostoEmpresa'.$i]):trim($_POST['cmbCentro']);
                    $desc= (trim($_POST['desc'.$i])=='')?0:$_POST['desc'.$i];

            $sqlIva1="Select * From impuestos where id_empresa='".$sesion_id_empresa."'  AND iva = '".$iva_producto."' ";
			$resultIva1=mysql_query($sqlIva1) or die('<div class="transparent_ajax_error"><p>Error en impuestos: '.mysql_error().' </p></div>  ');;
			$iva=0;
			$impuestos_id_plan_cuenta = 0;
			while($rowIva1=mysql_fetch_array($resultIva1))//permite ir de fila en fila de la tabla
									{
										$iva=$rowIva1['iva'];
										$txtIdIva=$rowIva1['id_iva'];
										//$impuestos_id_plan_cuenta=$rowIva1['id_plan_cuenta'];
										
									}
        $total_iva_producto1 = floatval($valor_total) * floatval($iva_producto/100);          
        $total_iva_producto = number_format($total_iva_producto1, 2, '.', ''); // Formatear a dos decimales
        
                $sqlUpdateDetalle="insert into detalle_compras (    idBodega,   idBodegaInventario,    cantidad,       valor_unitario,     valor_total,        id_compra,          id_producto,        id_empresa,centro_costo_empresa,des,iva ,total_iva ) values 
                    ('".$idbod."','".$bodInventario1."','".$cantidad."','".$valor_unitario."','".$valor_total."','".$id_compra."','".$id_producto."', '".$sesion_id_empresa."','".$centroCosto."','".$desc."','".$txtIdIva."','".$total_iva_producto."');";
                    $id_detalle_compra=mysql_insert_id();
                  
                    
                    $resultUpdateDetalle=mysql_query($sqlUpdateDetalle) or die("\nError al actualizar los detalles de la compra : ".mysql_error());
                    	$response['logs'][] =$sqlUpdateDetalle;
					$response['logs'][] =($resultUpdateDetalle)?"La  detalles de la compra se han actualizado correctamente":'Error al actualizar los detalles de la compra.';
                    $stock2= $cantidad;
                    
                    // $stock2=$_POST['stock'.$i] + $cantidad; // SUMA EL ESTOCK y asigna el precio bruto
                    $sql22="update productos set stock = '".$stock2."', costo='".$valor_unitario2."', tipos_compras='".$tipo2."',id_cuenta='".$idcuenta."',
                    grupo='".$idbod."',codPrincipal='".$codPrincipal."',codAux='".$codAux."',codigo='".$codProducto2."' where id_producto='".$id_producto2."';";
                    $result22=mysql_query($sql22) or die("\nError al actualizar los productos: ".mysql_error());
					$response['logs'][] =($result22)?"Los productos se han actualizado correctamente":'Error al actualizar los productos.';
                    
                    
                    if($tipo2==1){
                        
                        $stockBodegas="SELECT id,cantidad FROM `cantBodegas` WHERE idBodega='".$bodInventario1."' and idProducto='".$codProducto2."' ";
                        $resultado = mysql_query($stockBodegas);
                        while($rowBodegas1=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
            				{
            					$idBodegaCantidad=$rowBodegas1['id'];
            					$cantidad=$rowBodegas1['cantidad'];
            				}
            				
    		            $fila=mysql_num_rows($resultado);
    		            if ($fila>0){
    		                $cantidadBodega = $cantidad+$cantidad2;
    		                $sqlbodegas="UPDATE `cantBodegas` set `cantidad`='".$cantidadBodega."' WHERE id='".$idBodegaCantidad."'";
    	                    $resultBodegas=mysql_query($sqlbodegas) or die("\nError guardar en bodega: ".mysql_error());
    	                    $response['logs'][] =($resultBodegas)?"La cantidad en la bodega se han actualizado correctamente":'Error al actualizar la cantidad en la bodega.';
    	                    
    	                    
    		            }else{
    		                $sqlbodegas="INSERT INTO `cantBodegas`(`idBodega`, `idProducto`, `cantidad`) 
                            VALUES ('".$bodInventario1."','".$codProducto2."','".$cantidad2."')";
                            $resultBodegas=mysql_query($sqlbodegas) or die("\nError guardar en bodega: ".mysql_error());
							$response['logs'][] =($resultBodegas)?"La cantidad en la bodega se han actualizado correctamente":'Error al actualizar la cantidad en la bodega.';
    		            }
                        
                    }
            }


            //  if ($tipo2=="1"){
		    
		            $sqlki="Select id_kardes From kardes where detalle='Compra' AND  id_factura=$id_compra";
        			$resultki=mysql_query($sqlki) ;
        		    $numFilaKardex = mysql_num_rows($resultki);
        		
        			
        			if($numFilaKardex==0){
        			    $sqlk="insert into kardes ( fecha, detalle, cantidad ,bodegaInventario,id_factura,id_empresa) values
        			('".$fecha_compra."','Compra','".$cantidadBodega."','".$bodInventario1."','".$id_compra."','".$sesion_id_empresa."')";
        	
        			$resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());
        			$response['logs'][]= ($resultk)?'El kardex se ha guardado correctamente':'Error al guardar el kardex';
        			$id_kardes=mysql_insert_id();
        			}
        			
			
		      //  }
        // GUARDAR EN KARDEX
	
        

		$descripcion = $txtNombreRuc." ".$cmbTipoDocumentoFVC." ".$numero_factura;
	
	
// 		if ( $sesion_tipo_empresa=="Profesor")
// 		{ //$sesion_tipo_empresa=="6" or
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
			////echo $sqlMNA;
			$resultMNA=mysql_query($sqlMNA) or die('<div class="transparent_ajax_error"><p>Error 384: '.mysql_error().' </p></div>  ');
				$numero_asiento=0;
				while($rowMNA=mysql_fetch_array($resultMNA))//permite ir de fila en fila de la tabla
				{
					$numero_asiento=$rowMNA['max_numero_asiento'];
				}
				$numero_asiento++;
				
		  }
		  catch(Exception $ex) 
		  { ?> <div class="transparent_ajax_error">
		   <p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php 
		   }
					
	 	//Fin permite sacar el numero_asiento de libro_diario
		//permite sacar el id maximo de libro_diario
		  try
		  {
			$sqlm="Select max(id_libro_diario) From libro_diario";
			$resultm=mysql_query($sqlm) or die('<div class="transparent_ajax_error"><p>Error 403: '.mysql_error().' </p></div>  ');
			$id_libro_diario=0;
			while($rowm=mysql_fetch_array($resultm))//permite ir de fila en fila de la tabla
			{
				$id_libro_diario=$rowm['max(id_libro_diario)']; 
			}
			$id_libro_diario++;

		  }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }			
		//Fin permite sacar el id maximo de libro_diario
				
		  $tipo_comprobante = "Diario"; 
				
	// SACA EL NUMERO DE COMPROBANTE DEPENDIENDO DEL TIPO DE COMPROBANTE
		  try
		  {
			$tipoComprobante = $tipo_comprobante;
			$consulta7="SELECT
			 max(numero_comprobante) AS max_numero_comprobante
			FROM
				`comprobantes` comprobantes
			WHERE comprobantes.`id_empresa` = '".$sesion_id_empresa."' AND  comprobantes.`tipo_comprobante` = '".$tipoComprobante."' ;";
			$result7=mysql_query($consulta7) or die('<div class="transparent_ajax_error"><p>Error 425: '.mysql_error().' </p></div>  ');
			$numero_comprobante = 0;
			while($row7=mysql_fetch_array($result7))//permite ir de fila en fila de la tabla
			{
				$numero_comprobante=$row7['max_numero_comprobante'];
			}
			$numero_comprobante ++;
			//echo "Numero de comprobante".$numero_comprobante;
		  }
		  catch (Exception $e)
		  {
			// Error en algun momento.
			 ?> <div class="transparent_ajax_error"><p>Error max_numero_comprobante: <?php echo "".$e ?></p></div> <?php
		  }

		  try
		  {
			$sqlCM="Select max(id_comprobante) From comprobantes; ";
			$resultCM=mysql_query($sqlCM) or die('<div class="transparent_ajax_error"><p>Error 443: '.mysql_error().' </p></div>  ');
			$id_comprobante=0;
			while($rowCM=mysql_fetch_array($resultCM))//permite ir de fila en fila de la tabla
			{
				$id_comprobante=$rowCM['max(id_comprobante)'];
			}
			$id_comprobante++;

		  }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
							  
		//FIN SACA EL ID MAX DE COMPROBANTES
		
		//$fecha= date("Y-m-d h:i:s");
		  $fecha = $fecha_compra;
		  $cmbTipoDocumentoFVC="Compra#";
		  	$descripcion =(trim($_POST['detalleFP'])!='')?$_POST['detalleFP'] : $txtNombreRuc." ".$cmbTipoDocumentoFVC." ".$numero_factura;
		  //$descripcion = $txtNombreRuc." ".$cmbTipoDocumentoFVC." ".$numero_factura;
	
		  $debe1 = $sub_total;
		  $debe2 = $_POST['txtIva'];
		  $total_debe = $debe1 + $debe2;	
		  $total_haber= $total;
		  $tipo_mov="C";

		  $sqlC = "insert into comprobantes ( tipo_comprobante, numero_comprobante, id_empresa) values ('".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
		  $respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error 468: comprobantes, '.mysql_error().' </p></div>  ');
		 $id_comprobante=mysql_insert_id();	
		 $response['logs'][]=($respC)?'Comprobante guardado correctamente':'Error al guardar comprobante';
		 $response['id_comprobante']=($respC)?$id_comprobante:'0';
		 $response['numero_comprobante']=($respC)?$numero_comprobante:'0';
		//GUARDA EN EL LIBRO DIARIO
		  $sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha, total_debe,total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta )
		  values ('".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha."','".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."',
		  '".$tipo_comprobante."','".$id_comprobante."','".$tipo_mov."','".$numero_compra."'    )";
		  $resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error 477: LIBRO DIARIO, '.mysql_error().' </p></div>  ');
		 $id_libro_diario=mysql_insert_id();

		 $response['logs'][]=($resp)?'Libro diario guardado correctamente':'Error al guardar libro diario';
		 $response['id_libro_diario']=($resp)?$id_libro_diario:0;

		  $j=0;
		  $valor[$j]=0;
		  $txtContadorFilas=13;
		  
		  for($i=1; $i<=$txtContadorFilas; $i++)				
		  {
			if($_POST['cuenta'.$i] >=1)
			{
				//echo $_POST['cuenta'.$i];
				$encontrado="NO";
				if ($j>0) {
					for ($k=1;$k<=$j;$k++){
						if ($idPlanCuentas[$k] == $_POST['cuenta'.$i]) 
						{
							$valor[$k]  = $valor[$k]+$_POST['valtotal'.$i];
							$debeVector[$k] =$valor[$k] ;
							$haberVector[$k] = 0;
							$encontrado="SI";
    					}
					}
				}
				if ($j==0 or $encontrado=="NO") {
					$j=$j+1;
					$idPlanCuentas[$j] = $_POST['cuenta'.$i];
					$valor[$j]         = $_POST['valtotal'.$i];
					$debeVector[$j]    = $valor[$j] ;
					$haberVector[$j] = 0;
				}
			}
		  }
		  

		  if($txtIva>0 ){            
			$sql="Select cuenta_contable From enlaces_compras where tipo='iva-compra' and id_empresa='".$sesion_id_empresa."';";
			$resultado=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error enlaces compras: '.mysql_error().' </p></div>  ');;
			while($row=mysql_fetch_array($resultado)){              //permite ir de fila en fila de la tabla
				$impuestos_id_plan_cuenta=$row['cuenta_contable'];
			}	
			$j=$j+1;
			$idPlanCuentas[$j] = $impuestos_id_plan_cuenta;
			$debeVector[$j] = $txtIva;		
			$haberVector[$j] =0;		
		  }	
		  $lin_diario=$j;

		  $txtContadorFilas=8;
		  for($i=1; $i<=$txtContadorFilas; $i++)
		  {
            //$bancos[$lin_diario]='';
    	    if ($_POST['txtCuentaS'.$i] >=1)
			{   // verifica si en el campo esta agregada una cuenta, // permite sacar el id maximo de detalle_libro_diario
				
				$lin_diario=$lin_diario+1;
				$idformaPago[$lin_diario]=$_POST['txtCodigoP'.$i];
				$txtTipoP[$lin_diario]=$_POST['txtTipoP'.$i];
				$txtPorcentajeS[$lin_diario]=$_POST['txtPorcentajeS'.$i];
				
				
				$idPlanCuentas[$lin_diario]=$_POST['txtCuentaS'.$i];
				$debeVector[$lin_diario]=0;
				$haberVector[$lin_diario]=$_POST['txtValorS'.$i];
                $fechas_pag_ven[$lin_diario]=date("Ymd"); 
                $nro_cpte[$lin_diario]=$_POST['nrocpteC'.$i];
                $bancos[$lin_diario]='';
                // echo $_POST['txtTipoP'.$i];
                if (strtolower($_POST['txtTipoP'.$i]) =='cheque' OR
					strtolower($_POST['txtTipoP'.$i]) =='deposito' or
					strtolower($_POST['txtTipoP'.$i])=='TransferenciaC')
				{
				    //   echo $_POST['txtTipoP'.$i];
                   $bancos[$lin_diario]=strtolower($_POST['txtTipoP'.$i]);
//echo "banco";
				
                }
			}	

			
			if ($_POST['txtTipoP'.$i]=='Anticipo'){
			
			$sqlCtaPagar = "SELECT
				cuentas_por_cobrar.`id_cuenta_por_cobrar` AS ctas_x_pagar_id_cuenta_por_pagar,
				cuentas_por_cobrar.`saldo` AS cuentas_por_pagar_saldo,
				cuentas_por_cobrar.`id_proveedor` AS cuentas_por_pagar_id_proveedor,
				cuentas_por_cobrar.`id_empresa` AS cuentas_por_pagar_id_empresa,
				cuentas_por_cobrar.`estado` AS cuentas_por_pagar_estado
		    FROM
				`cuentas_por_cobrar` cuentas_por_cobrar 
				 WHERE  
				 cuentas_por_cobrar.`id_empresa`= '".$sesion_id_empresa."' and 
				 cuentas_por_cobrar.`id_proveedor`='".$id_proveedor."' and 
				 cuentas_por_cobrar.saldo>0 
				 order by cuentas_por_cobrar.`fecha_vencimiento`"; 
				 array_push($response['sql'], $sqlCtaPagar);
				 $resultCtaPagar= mysql_query($sqlCtaPagar);
				 
			 

				  $cantidadPagar = round(floatval($_POST['txtValorS'.$i]),2);
				 
				 while ($row = mysql_fetch_array($resultCtaPagar))
				 { 
					 $id_cuenta_pagar= $row['ctas_x_pagar_id_cuenta_por_pagar'];
 
					  $saldo_pagar= round(floatval($row['cuentas_por_pagar_saldo']), 2);
 
					 if($cantidadPagar>= $saldo_pagar){
						 $cantidadPagar  = $cantidadPagar - $saldo_pagar;
						 $saldo_actual = 0;
						 $text='Canceladas';
					 }else{
						 $saldo_actual = $saldo_pagar-$cantidadPagar;
						 $cantidadPagar=0;
						 $text='Pendientes';
					 }
					  $sqlUpdateCtaPagar="UPDATE `cuentas_por_cobrar` SET `saldo`='$saldo_actual', estado='$text' ,`fecha_pago`=NOW()
					  WHERE id_cuenta_por_cobrar=$id_cuenta_pagar AND id_empresa='".$sesion_id_empresa."' ";
					 $resultUpdateCtaPagar= mysql_query($sqlUpdateCtaPagar);
				
					 if($cantidadPagar==0){
						 break;
					 }
				 }
			
					
			}
			
			
			if ($_POST['txtTipoP'.$i]=='credito'){
				$total=$_POST['txtValorS'.$i];
				$txtCuotasTP=$_POST['txtCuotaS'.$i];
				$formas_pago_id_plan_cuenta=$_POST['txtCuentaS'.$i];		
			}
			
			
	        if ($_POST['txtTipoP'.$i]=='retencion-fuente-inventarios' || $_POST['txtTipoP'.$i]=='retencion-fuente-servicios' || $_POST['txtTipoP'.$i]=='retencion-fuente-proveeduria' ||$_POST['txtTipoP'.$i]=='retencion-iva' ){
			    $ret='1';
			}
			
			
			
			
		  }
		  
		    if ($emision_tipoEmision =='E'){ 
		        $Retfuente='-2';
		    }else{
		         $Retfuente='0';
		    }
		    
		  
		  
		    if ($ret >=1){ 
		  
		    $sql="Select 	secRetencion1 From retenciones where  id_empresa='".$sesion_id_empresa."';";
			$resultado=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error 691: '.mysql_error().' </p></div>  ');;
			while($row=mysql_fetch_array($resultado)){           
			    $secuencialRetencion=$row['secRetencion1'];
			}
			$secuencialRetencion++;
		    $sqlUpdateRet=" UPDATE `retenciones` SET `secRetencion1`='".$secuencialRetencion."' WHERE `id_empresa`='".$sesion_id_empresa."'";
			 $resp = mysql_query($sqlUpdateRet) or die('<div class="transparent_ajax_error"><p>Error Actulizar Secuencial'.mysql_error().' </p></div>  ');
		           // echo 'entro en retencio';
				   $response['logs'][]=($resp)?'Se ha actualizado la retencion':'Error al actualizar retencion'; 
		    // array_push($response['logs'], 'Se ha actualizado la retencion');
		          
		    $sqlRetenciones="INSERT INTO `mcretencion`(`Factura_id`, `Numero`, `Fecha`, `TipoC`, `Autorizacion`, `Total`, `Total1`, 
                 `FechaAutorizacion`, `Retfuente`, `ClaveAcceso`, `Observaciones`, `Serie`) 
                 VALUES ('".$id_compra."','".$secuencialRetencion."','".$fecha_compra."','1',NULL,NULL,NULL,NULL,$Retfuente,NULL,NULL,'".$establecimiento_codigo."-".$emision_codigo."') ";
            $resp = mysql_query($sqlRetenciones) or die('<div class="transparent_ajax_error"><p>Error Actulizar mc retencion'.mysql_error().' </p></div>  ');
            $idRetencion=mysql_insert_id();
         
                
                
            } 
			
			
		  
		  
		  
		  

		  for ($i=1; $i<=$lin_diario; $i++){
			if ($idPlanCuentas[$i] !=""){    //permite sacar el id maximo de detalle_libro_diario
				
				$sqlDLD = "insert into detalle_libro_diario ( id_libro_diario, id_plan_cuenta,debe, haber, id_periodo_contable) 
				values ('".$id_libro_diario."','".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."','".$sesion_id_periodo_contable."');";

				$resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error 724: '.mysql_error().' </p></div>  ');
				$id_detalle_libro_diario=mysql_insert_id();

           if ($idformaPago[$i]>0) {
                    
            $sqlforma = "INSERT INTO `cobrospagos`( `id_forma`, `documento`, `id_factura`,`id_empresa`,`valor`,`tipo`, porcentaje) VALUES 
                (".$idformaPago[$i].",1,".$id_compra.",'".$sesion_id_empresa."', ".$haberVector[$i].",'".$txtTipoP[$i]."', '".$txtPorcentajeS[$i]."' );";

            $respForma = mysql_query($sqlforma) or 	die('<div class="transparent_ajax_error"><p>Error 732: cobros Pagos '.mysql_error().' </p></div>  ');
           
                if ($txtTipoP[$i]=='retencion-fuente-inventarios' || $txtTipoP[$i]=='retencion-fuente-servicios' || $txtTipoP[$i]=='retencion-fuente-proveeduria' ||$txtTipoP[$i]=='retencion-iva' ){
				$sql="Select id,	codigo_sri, codigo, tipo From enlaces_compras where id='".$idformaPago[$i]."' and id_empresa='".$sesion_id_empresa."';";
				$resultado=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error 736: '.mysql_error().' </p></div>  ');;
				while($row=mysql_fetch_array($resultado)){           
					$codigoSri=$row['codigo_sri'];
					$codigoImp=$row['codigo'];
					$tipoEnlace=$row['tipo'];
				}
					$baseImp=$haberVector[$i];
					
				  		
					  		
				if($txtTipoP[$i]=='retencion-fuente-inventarios' ){
				    	$baseImp=$_POST['txtSubtotalInventarios'];
				}
				if($txtTipoP[$i]=='retencion-fuente-servicios' ){
				    	$baseImp=$_POST['txtSubtotalServicios'];
				}
				if($txtTipoP[$i]=='retencion-fuente-proveeduria' ){
				    	$baseImp=$_POST['txtSubtotalProveeduria'];
				}
				if($txtTipoP[$i]=='retencion-iva' ){
				    	$baseImp=$_POST['txtIva'];
				}
					
				 $sqlDetalleRetencion="INSERT INTO `dcretencion`(`Retencion_id`, `EjFiscal`, `BaseImp`, `TipoImp`, `CodImp`, `Porcentaje`)
					VALUES ('".$idRetencion."','".substr($fecha_compra,0,4)."','".$baseImp."','".$codigoImp."','".$codigoSri."','".$txtPorcentajeS[$i]."') ";
					$resp2 = mysql_query($sqlDetalleRetencion) or die('<div class="transparent_ajax_error"><p>Error Detalle Retencion: '.mysql_error().' </p></div>  ');

			}  
    }

                // Registro el Detalle de Bancos para las formas de pago en modalidad de cheque, deposito o transferencia
                
                



                if ($bancos[$i] != "")
				{
					
                    $sql_suc="SELECT id_bancos from bancos where id_plan_cuenta ='".$idPlanCuentas[$i]."';";
                    $res_suc= mysql_query($sql_suc) or die('<div class="transparent_ajax_error"><p>Error 776: '.mysql_error().' </p></div>  ');
                    $nro_bancos = mysql_num_rows($res_suc);

                    if ($nro_bancos ==1){
                        $id_bancos_suc=0;
                        while($row_suc=mysql_fetch_array($res_suc)){
                            $id_bancos_suc=$row_suc['id_bancos'];
                        }

//estoy agregando estas instrucciones
						
						$sql="Select max(id_detalle_banco)+1 as id_max From detalle_bancos ";
						$resultado=mysql_query($sql);
						$id_detalle_banco=0;
						while ($row=mysql_fetch_array($resultado))
						{
							$id_detalle_banco=$row['id_max'];
						}
	           
       $sql_bancos = "insert into detalle_bancos (tipo_documento,numero_documento,detalle,valor,fecha_cobro,fecha_vencimiento,id_bancos,estado,
                            id_libro_diario) values ('".ucwords($bancos[$i])."','".ucfirst($nro_cpte[$i])."','".$descripcion."','".$haberVector[$i]."','".$fechas_pag_ven[$i]."','".$fechas_pag_ven[$i]."','".$id_bancos_suc."','No Conciliado', '".$id_libro_diario."' );";
                        $res_bancos = mysql_query($sql_bancos) or die('<div class="transparent_ajax_error"><p>Error 797: '.mysql_error().' </p></div>  ');
                    	$id_detalle_banco=mysql_insert_id();
					}else {
                        // 1. determinar id proximo del banco de la empresa actual
                        // 2. crear el banco en la table respectiva (bancos)
                        //$sql_nuevo_banco="Select max(id_bancos)+1 From bancos where id_plan_cuenta='".$idPlanCuentas[$i]." and id_periodo_contable='".$

                        $sql_nuevo_banco="Select max(id_bancos)+1 as nuevo_banco From bancos ";
                        $res_nuevo_banco=mysql_query($sql_nuevo_banco);
                        $id_bancos=0;
                        while ($row_nuevo_banco=mysql_fetch_array($res_nuevo_banco)){
                            $id_bancos=$row_nuevo_banco['nuevo_banco'];
                        }



                        $sql_crea_banco = "insert into bancos ( id_plan_cuenta,id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
                        $res_crea_banco=mysql_query($sql_crea_banco) or die('<div class="transparent_ajax_error"><p>Error 815: '.mysql_error().' </p></div>  ');
						$id_bancos=mysql_insert_id();
                        $sql_suc="SELECT id_bancos from bancos where id_plan_cuenta ='".$idPlanCuentas[$i]."';";
                        $res_suc= mysql_query($sql_suc) or die('<div class="transparent_ajax_error"><p>Error 818: '.mysql_error().' </p></div>  ');
                        $nro_bancos = mysql_num_rows($res_suc);

                        $id_bancos_suc=0;
                        while($row_suc=mysql_fetch_array($res_suc)){
                            $id_bancos_suc=$row_suc['id_bancos'];
                        }


						$sql="Select max(id_detalle_banco)+1 as id_max From detalle_bancos ";
						$resultado=mysql_query($sql);
						$id_detalle_banco=0;
						while ($row=mysql_fetch_array($resultado))
						{
							$id_detalle_banco=$row['id_max'];
						}

                            $sql_bancos = "insert into detalle_bancos (tipo_documento,numero_documento,detalle,valor,fecha_cobro,fecha_vencimiento,id_bancos,estado,
                            id_libro_diario) values ('".ucwords($bancos[$i])."','".ucfirst($nro_cpte[$i])."','".$descripcion."','".$haberVector[$i]."','".$fechas_pag_ven[$i]."','".$fechas_pag_ven[$i]."','".$id_bancos_suc."','No Conciliado', '".$id_libro_diario."' );";


                        $res_bancos = mysql_query($sql_bancos) or die('<div class="transparent_ajax_error"><p>Error 850: '.mysql_error().' </p></div>  ');
						$id_detalle_banco=mysql_insert_id();
						
                    }
                }

					// CONSULTAS PARA GENERAR LA MAYORIZACION
				$sql5 = "SELECT * FROM mayorizacion WHERE id_plan_cuenta ='".$idPlanCuentas[$i]."' AND id_periodo_contable = '".$sesion_id_periodo_contable."';";
				$result5=mysql_query($sql5);
				while ($row5=mysql_fetch_array($result5)) { //permite ir de fila en fila de la tabla
						  $id_mayorizacion=$row5['id_mayorizacion'];
				}
				$numero = mysql_num_rows($result5); // obtenemos el número de filas
				if($numero > 0){
			        // si hay filas
				}else{                                                              //INSERCION DE LA TABLA MAYORIZACION
					try{                                                      		//permite sacar el id maximo de la tabla mayorizacion
						$sqli6="Select max(id_mayorizacion) From mayorizacion";
						$resulti6=mysql_query($sqli6);
						$id_mayorizacion=0;
						while($row6=mysql_fetch_array($resulti6)){
							$id_mayorizacion=$row6['max(id_mayorizacion)'];
						}
						$id_mayorizacion++;
						$sql6 = "insert into mayorizacion ( id_plan_cuenta, id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
						$result6=mysql_query($sql6);
						$id_mayorizacion=mysql_insert_id();
					}
					catch (Exception $ex) {
                          ?> <div class="transparent_ajax_error"> <p>Error en la insercion de la tabla mayorizacion:	<?php echo "".$ex ?> </p></div> <?php }
				}					
			}
    	}              // Fin de for  Fin del detalle libro diario		
	   //}                  // Fin de registrar asiento


//echo "aaaaaaaa";
	   
	    if ($sesion_tipo_empresa=="5")
		{	
		  insertar_cheque($sesion_id_periodo_contable,$descripcion);	
		  if ($total > 0 and $txtCuotasTP==0) 
		  {
			$txtContadorFilas=8;
			$cont_cheq=0;
			for($i=1; $i<=$txtContadorFilas; $i++)
			{			
			  if ($_POST['txtTipoP'.$i]=='credito')
			  {
				$total=$_POST['txtValorS'.$i];
				$txtCuotasTP=$_POST['txtCuotaS'.$i];
				$formas_pago_id_plan_cuenta=$_POST['txtCuentaS'.$i];		
		      }
		    }
		  }
		}
	
	    if ($total > 0 and $txtCuotasTP>0){            
           $cuotas = round(($total / $txtCuotasTP),2); 
           $aux=round(($cuotas * $txtCuotasTP),2); 
           $dif=round(($total-$aux),2);
           $cuota_final=$cuotas;
		   if ($dif != 0){
				$cuota_final=$cuota_final + $dif;
			}
			$estadoCC = "Pendientes";                
			for($i=1; $i<=$txtCuotasTP; $i++){
				if ($i == $txtCuotasTP){
					$cuotax=$cuota_final;
				}else{
					$cuotax=$cuotas;
				} 
				$mod_date = strtotime($txtFechaS."+ ".$i." months");
				$fecha_nueva = date("Y-m-d",$mod_date);	
				

				$sql3 = "insert into cuentas_por_pagar (tipo_documento,       numero_compra,          referencia,            valor,                  saldo,         numero_asiento, 
				fecha_vencimiento,  id_proveedor, id_plan_cuenta, id_empresa, id_compra, estado) "
				                      	. "values ('".$cmbTipoDocumentoFVC."','".$numero_compra."',	'".$txtNombreRUC.", ".$cmbTipoDocumentoFVC." ,".$numero_compra."', '".$cuotax."',      '".$cuotax."',               '',	
				                      	'".$fecha_nueva."',  '".$id_proveedor."', '".$formas_pago_id_plan_cuenta."', '".$sesion_id_empresa."','".$id_compra."', '".$estadoCC."');";
				$resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>Error al guardar en cuentas_por_cobrar: '.mysql_error().' </p></div>  ');
				$id_cuenta_por_pagar=mysql_insert_id();
				$response['id_cuenta_por_pagar'] = ($resp3)?$id_cuenta_por_pagar:0;
				$response['logs'][] = ($resp3)?'Cuentas por pagar guardadas correctamente':'Error al guardar compras por pagar';
			}
		
		}
		
		
		echo json_encode($response);

		if ($emision_tipoEmision == 'E'){
		    genXmlRet($idRetencion);
		}

		
		

    }//fin if de pregunta, si hay datos guardar factura
    else{
        echo "Error 950: No ha ingresado suficiente datos de factura ".mysql_error();
    }

    }catch (Exception $e) {
    // Error en algun momento.
       echo "Error: ".$e;
    }
}