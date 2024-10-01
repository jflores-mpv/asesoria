<?php   

    //Start session
    session_start();

    //Include database connection details
    require_once('../conexion.php');
  //   require_once('facturaXml.php');
     
	$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
//	echo $sesion_id_empresa;
//	$sesion_id_periodo_contable=$_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
	$sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
	$sesion_usuario = $_SESSION['sesion_id_usuario'];
	$emision_tipoFacturacion = $_SESSION["emision_tipoFacturacion"];
	
    $emision_tipoEmision=$_SESSION['emision_tipoEmision'] ;
    
	$emision_codigo = $_SESSION["emision_codigo"];
	$establecimiento_codigo = $_SESSION["establecimiento_codigo"];
	$userest = $_SESSION["userest"];
    //echo $sesion_tipo_empresa;
  
    date_default_timezone_set('America/Guayaquil');

    $accion=$_POST['txtAccion'];
    //echo "estoy en sql".$accion;
function ceros($valor){
    $s='';
	for($i=1;$i<=9-strlen($valor);$i++)
		$s.="0";
	return $s.$valor;
}
	function insertar_cheque($sesion_id_periodo_contable,$descripcion)
	{
	  $sesion_id_periodo_contable1=  $sesion_id_periodo_contable;
	  $descripcion1=$descripcion;
	//echo "va chea";
	//echo $sesion_id_periodo_contable1;
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
            if (strtolower($_POST['txtTipoP'.$i]) =='cheque' OR strtolower($_POST['txtTipoP'.$i]) =='deposito' 
            or strtolower($_POST['txtTipoP'.$i])=='transferenciac'){
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
// 			echo $sql_suc;
            $res_suc= mysql_query($sql_suc) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
            $nro_bancos = mysql_num_rows($res_suc);

            if ($nro_bancos ==1)
			{
			//	echo "si existe";
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
			  
              $sql_bancos = "insert into detalle_bancos (tipo_documento,numero_documento,
			      detalle,valor,fecha_cobro,fecha_vencimiento,id_bancos,estado,id_libro_diario) values
				('".ucwords($bancos[$i])."','".ucfirst($nro_cpte[$i])."','".$descripcion."',
				'".$haberVector[$i]."','".$fechas_pag_ven[$i]."','".$fechas_pag_ven[$i]."',
				'".$id_bancos_suc."','No Conciliado', '".$id_libro_diario."' );";
		//		echo $sql_bancos;
              $res_bancos = mysql_query($sql_bancos) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
    		  $id_detalle_banco =mysql_insert_id();      
			}
			else 
			{
				//echo "no existe";
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
          //    echo $sql_crea_banco;
			  $res_crea_banco=mysql_query($sql_crea_banco) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
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
//echo $sql_bancos;
              $res_bancos = mysql_query($sql_bancos) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
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
		$numero_factura=$_POST['txtFactura'];
		$fecha_compra=$_POST['textFecha'];
		$total=$_POST['txtTotal'];
		$subTotal0=$_POST['subTotal0'];
		
// 		$subTotal12=$_POST['subTotal12'];
		
		$subTotal12="0.00";
		
			$subTotal0		= isset($_POST['subTotal0'])? $_POST['subTotal0']: 0;
    			$subTotal12	= isset($_POST['subTotal12'])? $_POST['subTotal12'] :0;
    			
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
		
		$ats = $_POST['switch-two'];
		$ats = ($ats!='')?$ats :0;
		
		if($fecha_compra!="" && $id_proveedor!="" )
		{
        
			$sql="insert into compras ( fecha_compra, total, sub_total, id_iva, id_proveedor, numero_factura_compra,
			id_empresa,numSerie,autorizacion,caducidad,	TipoComprobante,codSustento,txtEmision,txtNum ,subTotal12,subTotal0,exentoIVA, noObjetoIVA,xml,
			subTotalInvenarios,subTotalProveeduria,subtotalServicios,descuento,ats,id_usuario,	id_establecimiento,total_iva) 
			values ('".$fecha_compra."','".$total."','".$sub_total."','".$iva."','".$id_proveedor."','".$numero_factura."', '".$sesion_id_empresa."', '".$numSerie."', '".$autorizacion."', '".$txtFechaVencimiento."'
			, '".$txtTipoComprobante."', '".$codSustento."','".$txtEmision."','".$txtNum."', '".$subTotal12."', '".$subTotal0."', '0.00', '0.00','10',
			'".$txtSubtotalInventarios."', '".$txtSubtotalProveeduria."', '".$txtSubtotalServicios."' ,'".$txtDescuento."' ,'".$ats."','".$sesion_usuario."','".$userest."','".$txtIva."');";
			
			
        $result=mysql_query($sql);
        // echo $sql;
		$id_compra=mysql_insert_id();
		$response = [];
        if ($result){
            $response['logs'] = [];
            array_push($response['logs'], 'La compra se ha guardado correctamente');
            for($i=1; $i<=$txtContadorFilas; $i++){
                
                    $id_producto2=$_POST['idproducto'.$i];
                    $id_producto=(int)$id_producto2;
                    
                    
                  
                        
                    // $idbod2=$centroCosto;
                  
                         $idbod2=$_POST['idbod'.$i];
                    
                   
                    $idbod=(int)$idbod2;
                    
                    
                    
                    $idcuenta2=$_POST['cuenta'.$i];
                    $idcuenta=(int)$idcuenta2;
                    
                    $cantidad2=$_POST['cant'.$i];
                    $cantidad=floatval($cantidad2);
                    $valor_unitario2=$_POST['valun'.$i];
                    $valor_unitario=$valor_unitario2;
                    $valor_total2=$_POST['valtotal'.$i];
                    $valor_total=floatval($valor_total2);
                    $tipo2=$_POST['idTipo'.$i];
                    
                    $codProducto2=$_POST['codProducto'.$i];
                    $iva_producto = $_POST['ivaS'.$i]; 
                     $desc= (trim($_POST['desc'.$i])=='')?0:$_POST['desc'.$i];
                    
                    $codPrincipal=$_POST['codPrincipal'.$i];
                    $codAux=$_POST['codAux'.$i];
                    
                    $bodInventario1=$_POST['bodInventario'.$i];
                    
                    // $servicioEmpresa=$_POST['servicioEmpresa'.$i];
                    $centroCosto = (trim($_POST['centrodeCostoEmpresa'.$i])!='0')?trim($_POST['centrodeCostoEmpresa'.$i]):trim($_POST['cmbCentro']);
$centroCostoProyecto = (trim($_POST['servicioEmpresa'.$i]) !== '') ? trim($_POST['servicioEmpresa'.$i]) : 0;
                    

                    
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
                      $total_iva_producto = floatval($valor_total) * floatval($iva_producto);
                       
					 
					  
					
                  $sql2="insert into detalle_compras (    idBodega,   idBodegaInventario,    cantidad,       valor_unitario,     valor_total,        id_compra,          id_producto,        id_empresa,    centro_costo_empresa,   des,item,iva,total_iva ) values ('".$idbod."','".$bodInventario1."','".$cantidad."','".$valor_unitario."','".$valor_total."','".$id_compra."','".$id_producto."', '".$sesion_id_empresa."','".$centroCosto."','".$desc."','".$centroCostoProyecto."','".$txtIdIva."','".$total_iva_producto."');";
                    $id_detalle_compra=mysql_insert_id();
                    $result2=mysql_query($sql2) or die("\nError  1: ".mysql_error());

                     $stock2= $cantidad;
                    // $stock2=$_POST['stock'.$i] + $cantidad; // SUMA EL ESTOCK y asigna el precio bruto
                   $sql22="update productos set stock = '".$stock2."', costo='".$valor_unitario2."', tipos_compras='".$tipo2."',id_cuenta='".$idcuenta."',
                    grupo='".$idbod."',codPrincipal='".$codPrincipal."',codAux='".$codAux."',codigo='".$codProducto2."' where id_producto='".$id_producto2."';";
                    $result22=mysql_query($sql22) or die("\nError 3: ".mysql_error());
                    
                    
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
    	                    
    	                    
    	                    
    		            }else{
    		              
							
								$sqlbodegas="INSERT INTO `cantBodegas`(`idBodega`, `idProducto`, `cantidad`) 
								VALUES ('".$bodInventario1."','".$codProducto2."','".$cantidad2."')";
							 
							
                            $resultBodegas=mysql_query($sqlbodegas) or die("\nError guardar en bodega: ".mysql_error());
    		            }
                        
                    }
                    
                }
                
                
            array_push($response['logs'], 'La  detalles de la compra se han guardado correctamente');
                
            if ($tipo2=="1"){
		    
		            $sqlki="Select id_kardes From kardes where detalle='Compra' AND  id_factura=$id_compra";
        			$resultki=mysql_query($sqlki) ;
        		    $numFilaKardex = mysql_num_rows($resultki);
        		    
        		    if($numFilaKardex==0){
        		        $sqlk="insert into kardes ( fecha, detalle, cantidad ,bodegaInventario,id_factura,id_empresa) values
        			('".$fecha_compra."','Compra','".$cantidad."','".$bodInventario1."','".$id_compra."','".$sesion_id_empresa."')";
        	
        			$resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());
        			array_push($response['logs'], 'El kardex se ha guardado correctamente');
        			$id_kardes=mysql_insert_id();
        		    }
        			
			
		        }
			
			$descripcion = $txtNombreRuc." ".$cmbTipoDocumentoFVC." ".$numero_factura;
				
	
		 
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
			//echo $sqlMNA;
			$resultMNA=mysql_query($sqlMNA) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
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
			$resultm=mysql_query($sqlm) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
			$id_libro_diario=0;
			while($rowm=mysql_fetch_array($resultm))//permite ir de fila en fila de la tabla
			{
				$id_libro_diario=$rowm['max(id_libro_diario)']; 
			}
			$id_libro_diario++;

		  }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }			
		//Fin permite sacar el id maximo de libro_diario
				
		  $tipo_comprobante = $_POST['tipoDoc']; 
				
	// SACA EL NUMERO DE COMPROBANTE DEPENDIENDO DEL TIPO DE COMPROBANTE
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
			//echo "Numero de comprobante".$numero_comprobante;
		  }
		  catch (Exception $e)
		  {
			// Error en algun momento.
			 ?> <div class="transparent_ajax_error"><p>Error max_numero_comprobante: <?php echo "".$e ?></p></div> <?php
		  }
		// FIN DE SACA EL NUMERO DE COMPROBANTE DEPENDIENDO DEL TIPO DE COMPROBANTE
			

//SACA EL ID MAX DE COMPROBANTES
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

		  }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
							  
		//FIN SACA EL ID MAX DE COMPROBANTES
		
		//$fecha= date("Y-m-d h:i:s");
		  $fecha = $fecha_compra;
		  
		  
		  
		  
		  $cmbTipoDocumentoFVC="Compra#";
		  
		  
		
	
	
// 	if($sesion_id_empresa==41){
// 	   // $descripcion = (trim($_POST['detalleFP'])!='')?$_POST['tipoDoc'].' - '.$_POST['detalleFP'] : $txtNombreRuc." ".$cmbTipoDocumentoFVC." ".$numero_factura." - ".$_POST['tipoDoc'];
// 	     $descripcion = (trim($_POST['detalleFP'])!='')?$_POST['tipoDoc'].' - '.$_POST['detalleFP'] : $txtNombreRuc." ".$cmbTipoDocumentoFVC." ".'('.$numSerie.' - '.$txtEmision.' - '.ceros($txtNum).')';
	        
	   
// 	}else{
// 	    $descripcion =(trim($_POST['detalleFP'])!='')?$_POST['detalleFP'] : $txtNombreRuc." ".$cmbTipoDocumentoFVC." ".$numero_factura;
// 	}
		  //$descripcion = $txtNombreRuc." ".$cmbTipoDocumentoFVC." ".$numero_factura;
		  
		  $descripcion = (trim($_POST['detalleFP'])!='')?$_POST['tipoDoc'].' - '.$_POST['detalleFP'] : $txtNombreRuc." ".$cmbTipoDocumentoFVC." ".'('.$numSerie.' - '.$txtEmision.' - '.ceros($txtNum).')';
		  
	
		  $debe1 = $sub_total;
		  $debe2 = $_POST['txtIva'];
		  $total_debe = $debe1 + $debe2;	
		  $total_haber= $total;
		  $tipo_mov="C";

		    $sqlC = "insert into comprobantes ( tipo_comprobante, numero_comprobante, id_empresa) values ('".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
		    $respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: comprobantes, '.mysql_error().' </p></div>  ');
		    $id_comprobante=mysql_insert_id();	
		    array_push($response['logs'], 'El comprobante se ha guardado correctamente');
		    $response['idComprobante'] =  $numero_comprobante;
		//GUARDA EN EL LIBRO DIARIO
		  $sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha, total_debe,total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta,centroCosto )
		  values ('".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha."','".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."',
		  '".$tipo_comprobante."','".$id_comprobante."','".$tipo_mov."','".$numero_compra."' ,'".$centroCosto."'   )";
		  $resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: LIBRO DIARIO, '.mysql_error().' </p></div>  ');
		  $id_libro_diario=mysql_insert_id();
          array_push($response['logs'], 'El libro se ha guardado correctamente');
		  $j=0;
		  $valor[$j]=0;
		  $txtContadorFilas=8;
		  
		  for($i=1; $i<=$txtContadorFilas; $i++){
		      
			if($_POST['cuenta'.$i] >=1){
				
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
			$resultado=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;
			while($row=mysql_fetch_array($resultado)){           
			    $impuestos_id_plan_cuenta=$row['cuenta_contable'];
			}
			
			$j=$j+1;
			$idPlanCuentas[$j] = $impuestos_id_plan_cuenta;
			$debeVector[$j] = $txtIva;		
			$haberVector[$j] =0;		
		  }	
		  
		  
		    $lin_diario=$j;
            
            
            $ret=0;    
               
		  for($i=1; $i<=$txtContadorFilas; $i++){

            
    	    if ($_POST['txtCuentaS'.$i] >=1){   
				
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
                
                if (strtolower($_POST['txtTipoP'.$i]) =='cheque' or
					strtolower($_POST['txtTipoP'.$i]) =='deposito' or
					strtolower($_POST['txtTipoP'.$i]) =='transferenciac')
				{
                   $bancos[$lin_diario]=strtolower($_POST['txtTipoP'.$i]);
              
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
					  $sqlUpdateCtaPagar="UPDATE `cuentas_por_cobrar` SET `saldo`='$saldo_actual', estado='$text' WHERE id_cuenta_por_cobrar=$id_cuenta_pagar AND id_empresa='".$sesion_id_empresa."' ";
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
				$diasASumar=$_POST['txtDiasPlazoS'.$i];	
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
			$resultado=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;
			while($row=mysql_fetch_array($resultado)){           
			    $secuencialRetencion=$row['secRetencion1'];
			}
			$secuencialRetencion++;
		    $sqlUpdateRet=" UPDATE `retenciones` SET `secRetencion1`='".$secuencialRetencion."' WHERE `id_empresa`='".$sesion_id_empresa."'";
			 $resp = mysql_query($sqlUpdateRet) or die('<div class="transparent_ajax_error"><p>Error Actulizar Secuencial'.mysql_error().' </p></div>  ');
		           // echo 'entro en retencio';
		    array_push($response['logs'], 'Se ha actualizado la retencion');
		          
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
				$resp2 = mysql_query($sqlDLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
				$id_detalle_libro_diario=mysql_insert_id();

            if ($idformaPago[$i]>0) {    
                
               

			if($txtTipoP[$i]=='Anticipo'){
					$nombreProveedor= $_POST['txtNombreRuc'];

				//  $sqlCtaCobrar = "INSERT INTO `cuentas_por_cobrar`( `tipo_documento`, `numero_factura`, `referencia`, `valor`, `saldo`, `fecha_vencimiento`, `fecha_pago`, `id_proveedor`, `id_cliente`, `id_plan_cuenta`, `id_empresa`, `id_venta`, `estado`, `id_forma_pago`,numero_asiento) VALUES ('Anticipo#','".$numero_factura."','".$nombreProveedor."','".$haberVector[$i]."','".$haberVector[$i]."','".$fecha_compra."','".$fecha_compra."','".$id_proveedor."','0','".$idPlanCuentas[$i]."','".$sesion_id_empresa."','".$id_compra."','Pendientes','".$idformaPago[$i]."','')";
				// $resultCtaCobrar = mysql_query($sqlCtaCobrar);
				}
                    
            $sqlforma = "INSERT INTO `cobrospagos`( `id_forma`, `documento`, `id_factura`,`id_empresa`,`valor`,`tipo`, porcentaje) VALUES 
                (".$idformaPago[$i].",1,".$id_compra.",'".$sesion_id_empresa."', ".$haberVector[$i].",'".$txtTipoP[$i]."', '".$txtPorcentajeS[$i]."' );";

            $respForma = mysql_query($sqlforma) or 	die('<div class="transparent_ajax_error"><p>Error: cobros Pagos '.mysql_error().' </p></div>  ');
           
                if ($txtTipoP[$i]=='retencion-fuente-inventarios' || $txtTipoP[$i]=='retencion-fuente-servicios' || $txtTipoP[$i]=='retencion-fuente-proveeduria' ||$txtTipoP[$i]=='retencion-iva' ){
				$sql="Select id,	codigo_sri, codigo, tipo From enlaces_compras where id='".$idformaPago[$i]."' and id_empresa='".$sesion_id_empresa."';";
				$resultado=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;
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


                if ($bancos[$i] != ""){

                    $sql_suc="SELECT id_bancos from bancos where id_plan_cuenta ='".$idPlanCuentas[$i]."';";
                    $res_suc= mysql_query($sql_suc) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
                    $nro_bancos = mysql_num_rows($res_suc);

                    if ($nro_bancos ==1){
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
                        $res_bancos = mysql_query($sql_bancos) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
                    	$id_detalle_banco=mysql_insert_id();
					}else {

                        $sql_nuevo_banco="Select max(id_bancos)+1 as nuevo_banco From bancos ";
                        $res_nuevo_banco=mysql_query($sql_nuevo_banco);
                        $id_bancos=0;
                        while ($row_nuevo_banco=mysql_fetch_array($res_nuevo_banco)){
                            $id_bancos=$row_nuevo_banco['nuevo_banco'];
                        }

                        $sql_crea_banco = "insert into bancos ( id_plan_cuenta,id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
                        $res_crea_banco=mysql_query($sql_crea_banco) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
						$id_bancos=mysql_insert_id();
                        $sql_suc="SELECT id_bancos from bancos where id_plan_cuenta ='".$idPlanCuentas[$i]."';";
                        $res_suc= mysql_query($sql_suc) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
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


                        $res_bancos = mysql_query($sql_bancos) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
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
    	}
    	
    	
    	
    	
    	array_push($response['logs'], 'Asiento y mayorización creados correctamente');
    	// Fin de for  Fin del detalle libro diario		
	                   // Fin de registrar asiento


	        if ($sesion_tipo_empresa=="5")	{	
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
		$response['mensajes'] = [];
		array_push($response['mensajes'] , 'Compra  guardada correctamente!');
	        if ($total > 0 and $txtCuotasTP>0) {            
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
				$sumaDia= $diasASumar*$i;
				// $mod_date = strtotime($txtFechaS."+ ".$i." months");
				// $fecha_nueva = date("Y-m-d",$mod_date);	

				$mod_date = strtotime($txtFechaS . "+" . $sumaDia . " days");
				$fecha_nueva = date("Y-m-d", $mod_date);
				
				// $mod_date = strtotime($txtFechaS."+ ".$i." months");
				// $fecha_nueva = date("Y-m-d",$mod_date);	
				
				//$fecha_nueva= date("Y-m-d\TH:i:sP");

				$sqlm2="Select max(id_cuenta_por_pagar) From cuentas_por_pagar;";
				$resultm2=mysql_query($sqlm2) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
				$id_cuenta_por_pagar=0;
				while($rowm2=mysql_fetch_array($resultm2))//permite ir de fila en fila de la tabla
				{
					$id_cuenta_por_pagar=$rowm2['max(id_cuenta_por_pagar)'];
				}
				$id_cuenta_por_pagar++;

				$sql3 = "insert into cuentas_por_pagar (tipo_documento,       numero_compra,          referencia,            valor,                  saldo,         numero_asiento, 
				fecha_vencimiento,             id_proveedor, id_plan_cuenta, id_empresa, id_compra, estado) "
				                      	. "values ('".$cmbTipoDocumentoFVC."','".$numero_compra."',	'".$txtNombreRUC.", ".$cmbTipoDocumentoFVC." ,".$numero_compra."', '".$cuotax."',      '".$cuotax."',               '',	
				                      	'".$fecha_nueva."',  '".$id_proveedor."', '".$formas_pago_id_plan_cuenta."', '".$sesion_id_empresa."',
					'".$id_compra."', '".$estadoCC."');";
//echo $sql3;
				$resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>Error al guardar en cuentas_por_cobrar: '.mysql_error().' </p></div>  ');
				$id_cuenta_por_pagar=mysql_insert_id();
			}
			
			if($resp3)
			{
			    array_push($response['mensajes'] , 'Registro cuentas por pagar guardado correctamente.');
			}
		}
		
		    if ($result2 and $result222 and $result22 and $resp ){
		        array_push($response['mensajes'] , 'Compra a crédito guardada correctamente!');
		    }
		    
			echo json_encode($response);
                
			// inicio reembolso
			if($_POST['txtTipoComprobante']==41){
				$txtContadorFilasReembolso = $_POST['txtContadorFilasReembolso'];
				for($re=1;$re<=$txtContadorFilasReembolso;$re++){
					$txtCedulaReembolso = $_POST['txtCedulaReembolso'.$re];
					$txtCodigoPais = $_POST['txtCodigoPais'.$re];
					$txtTipoProveedor = $_POST['txtTipoProveedor'.$re];
					$txtTipoDocumento = $_POST['txtTipoDocumento'.$re];
					$txtEstablecimientoReembolso = $_POST['txtEstablecimientoReembolso'.$re];
					$txtEmisionReembolso = $_POST['txtEmisionReembolso'.$re];
					$txtSecuencialReembolso = $_POST['txtSecuencialReembolso'.$re];
					$txtFechaReembolso = $_POST['txtFechaReembolso'.$re];
					$txtNumeroAutorizacion = $_POST['txtNumeroAutorizacion'.$re];
					$cantidadCaracteres=  strlen($txtCedulaReembolso);
					$tipo_identificacion_proveedor_reembolso='05';
					if($cantidadCaracteres==13){
						$tipo_identificacion_proveedor_reembolso='06';
					}
                    if(trim($txtCedulaReembolso)!=''){
                       $sqlReembolso="INSERT INTO `reembolsos_gastos`( `tipo_identificacion_proveedor_reembolso`, `identificacion_proveedor_reembolso`, `cod_pais_proveedor_reembolso`, `tipo_proveedor_reembolso`, `cod_doc_reembolso`, `estab_doc_reembolso`, `pto_emi_doc_reembolso`, `fecha_emision_doc_reembolso`, `id_compra`,secuencial_doc_reembolso,numero_autorizacion_doc_reembolso) VALUES ('".$tipo_identificacion_proveedor_reembolso."','".$txtCedulaReembolso."','".$txtCodigoPais."','".$txtTipoProveedor."','".$txtTipoDocumento."','".$txtEstablecimientoReembolso."','".$txtEmisionReembolso."','".$txtFechaReembolso."','".$id_compra."','".$txtSecuencialReembolso."','".$txtNumeroAutorizacion."')";
					$resultReembolso = mysql_query($sqlReembolso);
					$id_reembolso = mysql_insert_id();
					$txtContadorFilasCompensacion = $_POST['txtContadorFilasCompensacion'.$re];

					for($t=1;$t<=$txtContadorFilasCompensacion;$t++){
						// $txtCodigoCompensacion=$_POST['txtCodigoCompensacion'.$re.'_'.$t];
						$txtCodigoImpuestoCompensacion=$_POST['txtCodigoImpuestoCompensacion'.$re.'_'.$t];
						$txtPorcentajeCompensacion=$_POST['txtPorcentajeCompensacion'.$re.'_'.$t];
						$txtTarifaCompensacion=$_POST['txtTarifaCompensacion'.$re.'_'.$t];
						$txtBaseImponible=$_POST['txtBaseImponible'.$re.'_'.$t];
						$txtImpuestoCompensacion=$_POST['txtImpuestoCompensacion'.$re.'_'.$t];

						// echo $sqlCompensacion="INSERT INTO `compensaciones_reembolso`( `codigo`, `tarifa`, `valor`, `id_reembolso`) VALUES ('".$txtCodigoCompensacion."','".$txtTarifaCompensacion."','".$txtBaseImponible."','".$id_reembolso."')";
						// $resultCompensacion = mysql_query($sqlCompensacion);

						 $sqlImpuestos="INSERT INTO `impuestos_reembolso`( `codigo_impuesto`, `codigo_porcentaje`, `tarifa`, `base_imponible`, `impuesto`, `id_reembolsos`) VALUES ('".$txtCodigoImpuestoCompensacion."','".$txtPorcentajeCompensacion."','".$txtTarifaCompensacion."','".$txtBaseImponible."','".$txtImpuestoCompensacion."','".$id_reembolso."')";
						$resultImpuestos = mysql_query($sqlImpuestos);

					}  
                    }
					

				}
				
			}
			// fin reembolso
        } else { ?><div class="alert alert-danger">Compra no guardada</div><?php }

    
        if ($emision_tipoEmision == 'E'){
		    genXmlRet($idRetencion);
		}
    }//fin if de pregunta, si hay datos guardar factura
        
    
    else{
        echo "3";
    }

    }catch (Exception $e) {
    // Error en algun momento.
       echo "Error: ".$e;
    }
}


if($accion == "2"){
// GUARDAR MODIFICACION FACTURA COMPRA PAGINA: modificarFacturaCompra.php
    try
    {
        $id_compra=$_POST['txtIdCompra'];
        $id_proveedor=$_POST['textIdProveedor'];
        $fecha_compra=$_POST['textFecha'];
        $numero_factura=$_POST['txtFactura'];
        $total=$_POST['txtTotal'];
        $sub_total=$_POST['txtSubtotal'];
        $iva=$_POST['txtIdIva'];

		if($fecha_compra!="" && $id_proveedor!="" && $iva!="")
		{
			$sql="update compras set fecha_compra='".$fecha_compra."', total='".$total."', sub_total='".$sub_total."', id_iva='".$iva."', id_proveedor='".$id_proveedor."', numero_factura_compra='".$numero_factura."' WHERE id_compra='".$id_compra."' ;";
			$result=mysql_query($sql);
			if ($result)
			{ ?> <div class='transparent_ajax_correcto'><p>Registro Modificado correctamente.</p></div> <?php }
			else
			{ ?> <div class='transparent_ajax_error'><p>Error al guarda los datos: Revise que haya ingresado todos los datos correctamente. o Consulte con el Administrador <?php echo " ".mysql_error(); ?>;</p></div> <?php }

			$sqldelet = "delete from detalle_compras where id_compra='".$id_compra."';";
			$resultdelet=mysql_query($sqldelet);

			//Inserccion a tabla detalle_compras
			$cant=2;        
			$valun=2;
			$valtotal=2;
			$idp=2;
			$st=2;//stock

			$contador=2;
        
			for($j=1;$j<=13;$j++){
            //FILAS
				$id_producto2=$_POST['idproducto'.$idp];
				if($id_producto2!=0)
				{
				$sqlm="Select max(id_detalle_compra) From detalle_compras;";
				$resultm=mysql_query($sqlm) or die("\nError al sacar el id maximo de detalles_compras Fila 2: ".mysql_error());
				$id_detalle_compra='0';
				while($rowm=mysql_fetch_array($resultm))//permite ir de fila en fila de la tabla
				{
					$id_detalle_compra=$rowm['max(id_detalle_compra)'];
				}
				$id_detalle_compra++;

				$cantidad2=$_POST['cant'.$cant];
				$valor_unitario2=$_POST['valun'.$valun];
				$valor_total2=$_POST['valtotal'.$valtotal];
				$sql2="insert into detalle_compras ( cantidad, valor_unitario, valor_total, id_compra, id_producto) values ('".$cantidad2."','".$valor_unitario2."','".$valor_total2."','".$id_compra."','".$id_producto2."');";
				$result2=mysql_query($sql2) or die("\nError al guardar detalles compra Fila 2: ".mysql_error());
				$id_detalle_compra=mysql_insert_id();
				$sql222="select * From productos WHERE id_producto='".$id_producto2."';";
				$result222=mysql_query($sql222) or die("\nError al actualizar el Stock Fila 2: ".mysql_error());
				while($row2=mysql_fetch_array($result222))
				{
					$ganacia1=$row2['ganancia1'];
					$ganacia2=$row2['ganancia2'];
				}
            $precio_venta1 = round(($valor_unitario2 * $ganacia1)/100)+$valor_unitario2; // saca el marguen de ganacia y lo accina al precio de venta
            $precio_venta2 = round(($valor_unitario2 * $ganacia2)/100)+$valor_unitario2; // saca el marguen de ganacia y lo accina al precio de venta

            $stock2=$_POST['stock'.$st] + $cantidad2; // SUMA EL ESTOCK y asigna el precio bruto
            $sql22="update productos set stock = '".$stock2."', costo='".$valor_unitario2."', precio1='".$precio_venta1."', precio2='".$precio_venta2."' where id_producto='".$id_producto2."';";
            $result22=mysql_query($sql22) or die("\nError al actualizar el Stock Fila 2: ".mysql_error());
            }
            $contador++;
            $st++; 
            $idp++;            
            $cant++;            
            $valun++;
            $valtotal++;
        }        

        // GUARDAR EN KARDEX
        
        $sqlk="UPDATE kardes SET fecha='".$fecha_compra."' WHERE id_factura='".$id_compra."' AND detalle='Compra';";
        $resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());

    }//fin if de pregunta, si hay datos guardar factura
    else{
        echo "Error: No ha ingresado suficiente datos de factura ".mysql_error();
    }

    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
    }

}


if($accion == "3")
{
    // ANULAR FACTURA COMPRA PAGINA: nuevaFacturaCompra.php
     try
    {
        if(isset ($_POST['idProveedor']))
		{
          $idProveedor = $_POST['idProveedor'];

          //cambia el estado a libre y limpia el usuario y cedula
          $sql3 = "delete from proveedores WHERE id_proveedor='".$idProveedor."'; ";
          $result3=mysql_query($sql3);

           if($result3){
               echo "Registro eliminado correctamente.";
              }
           else
		   {
             echo "Error al eliminar los datos: ".mysql_error();
           }
        }
		else{
          echo "Fallo en el envio del Formulario: No hay datos, ".mysql_error();
        }

    }
	catch (Exception $e) 
	{
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }

}


if($accion == "4")
{
    // VALIDA PARA QUE EL NUMERO DE LA FACTURA COMPRA NO SE REPITA PAGINA: nuevaFacturaCompra.php
     try
     {
        if(isset ($_POST['ruc'])){
          $nombre1 = $_POST['ruc'];
          $sql1 = "SELECT ruc from proveedores where ruc='".$nombre1."'; ";
          $resp1 = mysql_query($sql1);
          $entro=0;
          while($row1=mysql_fetch_array($resp1))//permite ir de fila en fila de la tabla
                    {
                        $var1=$row1["ruc"];
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

if ($accion=="5")
{

	$numero_cpra_vta=$_POST['numero_compra'];
	$id_compra=trim($_POST['id_compra']);

 
	//echo $numero_cpra_vta;
	
	$sqlCompras= "SELECT `id_compra`, `fecha_compra`, `numero_factura_compra`, `id_empresa`, `autorizacion`,`numSerie`, `txtEmision`,
	`txtNum` FROM `compras` WHERE numero_factura_compra='".$numero_cpra_vta."' and id_empresa='".$sesion_id_empresa."'  ";
	if($id_compra!=''){
	    $sqlCompras .=" AND id_compra=$id_compra";
	}
// 	if($sesion_id_empresa==41){
// 	    echo $sqlCompras;
// 	}
	$resultCompras = mysql_query($sqlCompras);
	$numFilasCompras = mysql_num_rows($resultCompras);
	$autorizacionCompra='';
	$idCompra=0;
	$numFactCompra= '0';
	if($numFilasCompras>0){
		while($rowC = mysql_fetch_array($resultCompras)){
			$autorizacionCompra = $rowC['autorizacion'];
			$idCompra = $rowC['id_compra'];
			$numFactCompra=	$rowC['numSerie']."-".$rowC['txtEmision']."-".$rowC['txtNum'];
            
		}
	}else{
		echo "No existe registros de la factura.";
		exit;
	}
    $id_compra = $idCompra;


	 $sqlMcRetencion = "SELECT `Id`, `Factura_id`, `Numero`,  `TipoC`, `Autorizacion`, anulado FROM `mcretencion` WHERE Factura_id=$idCompra  AND TipoC=1 ";
	$resultMcRentencion  = mysql_query($sqlMcRetencion);
	$numFilasMcRetencion = mysql_num_rows($resultMcRentencion);
	$autorizacionRetencion ='';
	$idMcRetencion='';
	$retencionesAnulados='';
	$sinAnular=0;
	if($numFilasMcRetencion>0){
		while($rowMC= mysql_fetch_array($resultMcRentencion)){
			$autorizacionRetencion  = $rowMC['Autorizacion'];
			$idMcRetencion = $rowMC['Id'];
			if($rowMC['anulado']=='0'){
			    $sinAnular++;
			}
		}
	}
    
    	if($sinAnular>0 ){
		echo "No se puede eliminar factura, las retenciones de la factura no estan anuladas";
		exit;
	}
	
	if(trim($autorizacionRetencion)!='' ){
		echo "No se puede eliminar factura, las retenciones de la factura ya estan autorizadas";
		exit;
	}

   	if(trim($autorizacionCompra)!='' &&  $sinAnular>0){
		echo "No se puede eliminar factura, ya esta autorizada ";
		exit;
	}
   

	$sql="SELECT numero_asiento FROM `cuentas_por_pagar` where id_empresa='".$sesion_id_empresa."' 
		and numero_compra='".$numero_cpra_vta."' and tipo_documento='Compra#';";
//	echo $sql;

	$numero_asiento=0;
	$result=mysql_query($sql);
	while($row=mysql_fetch_array($result))
	{
		$numero_asiento=$row['numero_asiento'];
	}
	if($numero_asiento>0)
	{	
		echo "No se puede eliminar factura, ya tiene registro de cobros ";
		exit;
	}
	else
	{
       

		 $sql="SELECT id_libro_diario, numero_comprobante FROM `libro_diario` 
		    where id_periodo_contable='".$sesion_id_periodo_contable."' 
			      and numero_cpra_vta='".$numero_cpra_vta."' and tipo_mov='C';";
		//echo $sql;
		$resultado=mysql_query($sql);
		$numFilasLD= mysql_num_rows($resultado);
		$id_libro_diario=0;
		$numero_comprobante=0;

		if($numFilasLD>0){
			while($row=mysql_fetch_array($resultado))  //permite ir de fila en fila de la tabla
			{
				$id_libro_diario=$row['id_libro_diario'];
				$numero_comprobante=$row['numero_comprobante'];
			}
		}
    
		if($id_libro_diario >0){
			$sqlDetalleBancos = "SELECT `id_detalle_banco`, `tipo_documento`, `numero_documento`, `detalle`, `valor`, `fecha_cobro`, `fecha_vencimiento`, `id_bancos`, `estado`, `id_libro_diario`, `fecha` FROM `detalle_bancos` WHERE id_libro_diario=$id_libro_diario ";
			$resultDetalleBancos = mysql_query($sqlDetalleBancos);
			$numFilasDB= mysql_num_rows($resultDetalleBancos);
			$autorizacionDetalleBancos ='';
			$idBanco='0';
			if($numFilasDB>0){
				while($rowDB= mysql_fetch_array($resultDetalleBancos)){
					$idBanco = $rowDB['id_bancos'];
					if(trim($rowDB['estado'])=='Conciliado'){
						echo "No se puede eliminar factura, ya tiene registrado un cobro en ".$rowDB['tipo_documento'];
						exit;
					}
				}
               
				$sqlEliminaDetalleBancos= "DELETE FROM `detalle_bancos` WHERE `id_libro_diario`=$id_libro_diario ";
				$resultEliminaDetalleBancos = mysql_query($sqlEliminaDetalleBancos);

				$sqlEliminaBancos= "DELETE FROM `bancos` WHERE `id_bancos`=$idBanco ";
				$resultEliminaBancos = mysql_query($sqlEliminaBancos);
			}
		}
      

		if($idMcRetencion!=''){
			$sqlEliminaDcRet = "DELETE FROM `dcretencion` WHERE `Retencion_id`=$idMcRetencion ";
			$resultEliminaDcRet = mysql_query($sqlEliminaDcRet);

			$sqlEliminaMcRet = "DELETE FROM `mcretencion` WHERE `Id`=$idMcRetencion ";
			$resultEliminaMcRet = mysql_query($sqlEliminaMcRet);
			
		}

		 $sqlEliminaCobrosPagos= "DELETE FROM `cobrospagos` WHERE `id_factura`=$idCompra AND `id_empresa`='".$sesion_id_empresa."' AND  `documento`=1";
		$resultEliminaCobrosPagos = mysql_query($sqlEliminaCobrosPagos);
		
     
		
		 $sql="delete FROM `comprobantes` where id_empresa='".$sesion_id_empresa."' 
			and numero_comprobante='".$numero_comprobante."';";
		$resultado1=mysql_query($sql);

       

		 $sql="delete FROM `detalle_libro_diario` where id_periodo_contable='".$sesion_id_periodo_contable."' 
			and id_libro_diario='".$id_libro_diario."';";
		$resultado2=mysql_query($sql);
	
		$tipo_mov='C';
		
		 $sql="delete FROM `libro_diario` where id_periodo_contable='".$sesion_id_periodo_contable."' 
		   and tipo_mov='".$tipo_mov."' and numero_cpra_vta='".$numero_cpra_vta."';";
	
		$resultado3=mysql_query($sql);


		 $sql="SELECT detalle_compras.`id_detalle_compra`, detalle_compras.`idBodegaInventario`, detalle_compras.`id_compra`, detalle_compras.cantidad, detalle_compras.`id_producto`, detalle_compras.`id_empresa`,productos.codigo 
		FROM `detalle_compras` 
		INNER JOIN productos ON productos.id_producto = detalle_compras.id_producto 
		WHERE detalle_compras.id_compra='".$id_compra."';";
	    //echo $sql;
	     $result2=mysql_query($sql);
      
   	    $id_producto=0;
   	$resultado4=true;
        while($row=mysql_fetch_array($result2))  //permite ir de fila en fila de la tabla
       {
		   $id_producto=$row['id_producto'];
	  	   $cantidad=$row['cantidad'];
		    $sql2="update productos set stock=stock-'".$cantidad."' where id_producto='".$id_producto."';";
            $resultado4=mysql_query($sql2);
            if(trim($row['idBodegaInventario'])!=''){
                 $sqlbodegas="UPDATE `cantBodegas` set `cantidad`=cantidad-'".$row['cantidad']."' WHERE idProducto='".$row['codigo']."' AND  idBodega='".$row['idBodegaInventario']."'  ";
			$resultBodegas=mysql_query($sqlbodegas) or die("\nError actualizar en bodega: ".mysql_error());
            }

	    }
       
	
	   
	    $descr='Compra';
		 $sql="delete FROM `kardes` where id_empresa='".$sesion_id_empresa."' and detalle='".$descr."' and id_factura='".$id_compra."';";
		//echo $sql;
		$resultado5=mysql_query($sql) or die("\nError eliminar en kardex: ".mysql_error());;
     
     $resultado6=true;
		if($resultado1 && $resultado2 && $resultado3 && $resultado4 && $resultado5  )
		{
			 $sql="delete FROM `detalle_compras` where id_compra='".$id_compra."';";
			$resultado6=mysql_query($sql) or die("\nError eliminar en detalle_compras: ".mysql_error());;
		}

       
		$resultado7=true;
		if($resultado1 && $resultado2 && $resultado3 && $resultado4 && $resultado5 && $resultado6 )
		{
	
			 $sql="delete FROM `compras` where id_empresa='".$sesion_id_empresa."' 
				and numero_factura_compra='".$numero_cpra_vta."';";
			$resultado7=mysql_query($sql);
		}
		
		 $sql="delete FROM `cuentas_por_pagar` where id_empresa='".$sesion_id_empresa."' 
			and numero_compra='".$numero_cpra_vta."'  and tipo_documento='Compra#' ; ";

	
		$resultado8=mysql_query($sql);
		
	
		
		$fechaBitacora = date('Y-m-d H:i:s');
	    $sqlBitacora="INSERT INTO `bitacora`( `id_usuario`, `descripcion`, `fecha_accion`, `id_empresa`, `modulo`, `registro`) VALUES ('$sesion_usuario','D','$fechaBitacora','$sesion_id_empresa','Compras', '".$numFactCompra."')";
				  $resultBitacora = mysql_query($sqlBitacora);

		if($resultado1 && $resultado2 && $resultado3 && $resultado4 && $resultado5 && $resultado6 && $resultado7 && $resultado8)
		{
			?> <div class='transparent_ajax_correcto'><p>Registros eliminados correctamente.</p></div> <?php
		}
		else
		{
			?> <div class='transparent_ajax_error'>
			<p>Error al eliminar registros de compras <?php echo " ".mysql_error(); ?>;</p></div> <?php
		}
	

		
		
	}
	
	
}



// if($accion == "6"){
// 	  if(isset($_POST['queryString'])) 
//         {
//         $queryString = $_POST['queryString'];
//         $cont = $_POST['cont'];

 
//     if(strlen($queryString) >0) 
// 		{        
		    
// 			$query = "SELECT
//             productos.`id_producto` AS productos_id_producto,
//             productos.`producto` AS productos_nombre,
// 			productos.`stock` AS productos_stock,
            
// 			productos.`costo` AS productos_costo,
            
//             productos.`precio1` AS productos_precio1,
//             productos.`precio2` AS productos_precio2,
//             productos.`id_empresa` AS productos_id_empresa,
//             productos.`iva` AS productos_iva,
// 			productos.`codigo` AS productos_codigo,
// 			productos.`id_cuenta` AS productos_id_cuenta,
//             productos.`tipos_compras` AS productos_tipos_compra,
            
//             productos.`codPrincipal` AS productos_codPrincipal,
// 			productos.`codAux` AS productos_codAux,
			
			
			
            
// 			categorias.`id_categoria` AS categorias_id_categoria,
//             categorias.`categoria` AS categorias_categoria,
//             categorias.`id_empresa` AS categorias_id_empresa,
// 	      	productos.`id_empresa` AS productos_id_empresa,
	      	
// 	      	centro_costo.`id_centro_costo` AS  centro_id,
// 			centro_costo.`descripcion` AS  centro_descripcion
	      	
	      	
            
//         FROM
        
//             `categorias`categorias 
            
//             INNER JOIN `productos` productos  ON categorias.`id_categoria` = productos.`id_categoria` 
//             INNER JOIN `centro_costo` centro_costo ON centro_costo.`id_centro_costo` = productos.`grupo`
            
//             WHERE productos.`id_empresa`='".$sesion_id_empresa."' and CONCAT(productos.`id_producto`, productos.`producto`) 
//             LIKE '%$queryString%'  LIMIT 20; ";


//             $result = mysql_query($query) or die(mysql_error());
//             $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
//             if($result) {
//                 if($numero_filas  == 0){
// 				           echo "<div class='alert alert-danger'> No hay resultados con el parámetro ingresado. </div>";
//                  }else{
				    
//                     echo "<table id='tblServicios".$cont."' class='table table-condensed table-hover'>";
//                     echo "<thead>";
//                     echo "<tr>";
//                     echo "<th style='padding-right: 5px;'>Código</th>  
//                           <th style='padding-right: 5px;'>Nombre</th>
//                           <th style='padding-right: 5px;'>Categoria</th> 
//                           <th style='padding-right: 5px;'>Iva</th> 
// 					      <th style='padding-right: 5px;'>Tipo Compra</th>  
// 					      <th style='padding-right: 5px;'><a href='javascript: fn_cerrar_div();'>	<button type='button' class='btn btn-default' aria-label='Left Align'><span class='fa fa-close' aria-hidden='true'></span></button></a></th>";
//                     echo "</tr>";
//                     echo "</thead>";
//                     echo "<tbody>";
//                     while ($row = mysql_fetch_array($result))
// 					{
//                         $id_iva = 0;
//                         $iva = 0;
                            
//                             // CONSULTA PARA EL IVA
// 						if ($row["productos_iva"]=='Si')
// 						{
// 							$sqliva = "SELECT * FROM impuestos WHERE estado='Activo' and id_empresa='".$sesion_id_empresa."';";
//                             $result2 = mysql_query($sqliva);
                            
// 							while ($row2 = mysql_fetch_array($result2)) {
// 									$id_iva = $row2["id_iva"];
// 									$iva = $row2["iva"];
// 							}					
// 						}
//                           echo '<tr  style="cursor: pointer" onClick="fill_cpra_edu(\''.$cont.'\','.$row["productos_id_producto"].',\''. $row["productos_codigo"]."*".$row["productos_nombre"]."*".$row["proveedores_nombre_comercial"]."*".
//                               $row["productos_stock"]."*".$row["productos_costo"]."*".$row["productos_id_cuenta"]."*".$iva."*".$row["productos_tipos_compra"]."*".$row["productos_precio1"]."*".$id_iva."*".$iva."*".$tipoProducto."*".$row["productos_codPrincipal"]."*".$row["productos_codAux"]."*".$row["centro_id"]."*".$row["centro_descripcion"].'\');" title="Clic para seleccionar">';
//                             echo "<td>".$row["productos_codigo"]."</td>";
//                             echo "<td>".$row["productos_nombre"]."</td>";
// 						//	echo "<td>".$row["proveedores_nombre_comercial"]."</td>";
// 						//	echo "<td>".$row["productos_stock"]."</td>";
// 						//	echo "<td>".$row["productos_costo"]."</td>";
//                             echo "<td>".$row["categorias_categoria"]."</td>";
// 						//	echo "<td>".$row["productos_id_cuenta"]."</td>";
							
//                          //   echo "<td>".$row["unidades_nombre"]."</td>";
//                          //   echo "<td>".$row["tipo_servicios_nombre"]."</td>";
// 							echo "<td>".$iva."</td>";
// 	                       echo "<td>".$row["productos_tipos_compra"]."</td>";
// 							echo "<td>".$tipoProducto."</td>";
// 	                        echo "</tr>";
//                     }
//                         echo "</tbody>";
//                         echo"</table>";
//                 }
//             } 
// 			else 
// 			{
// 				echo 'ERROR: Hay un problema con la consulta.';
//             }
//         } 
// 		else
// 			{
// 				echo 'La longitud no es la permitida.';
// 			}
//     } 
// 	else 	
// 	{
//         echo 'No hay ningún acceso directo a este script!';
// 	}
   
// }

	if($accion === "6")
	{
	//echo "estoy en sql accion kkkkkkkkkkkkkkkkkkkkkkkkkkkkjja";
		if(isset($_POST['queryString'])) 
		{
			$queryString = $_POST['queryString'];

			$tipo = $_POST['tipo'];
			$cont = $_POST['cont'];
//echo $cont;
        // Is the string length greater than 0?
			
			if(strlen($queryString) >0) 
			{        
			//echo "kkkkkkkkkkkkkkkkkkkkkkkkkkkkjja";
				$query = "SELECT
				productos.`id_producto` AS productos_id_producto,
				productos.`producto` AS productos_nombre,
				productos.`stock` AS productos_stock,
				
				productos.`costo` AS productos_costo,
				
				productos.`precio1` AS productos_precio1,
				productos.`precio2` AS productos_precio2,
				productos.`id_empresa` AS productos_id_empresa,
				productos.`iva` AS productos_iva,
				productos.`codigo` AS productos_codigo,
				productos.`id_cuenta` AS productos_id_cuenta,
				productos.`tipos_compras` AS productos_tipos_compra,
				productos.`codPrincipal` AS productos_codPrincipal,
				productos.`codAux` AS productos_codAux,
				
				categorias.`id_categoria` AS categorias_id_categoria,
				categorias.`categoria` AS categorias_categoria,
				categorias.`id_empresa` AS categorias_id_empresa,

				centro_costo.id_centro_costo as centro_costo_id,
				centro_costo.id_bodega as centro_costo_id_bodega,
				centro_costo.id_cuenta as centro_costo_id_cuenta,
				centro_costo.tipo as centro_costo_tipo,
				centro_costo.descripcion as centro_costo_descripcion
				
				
				FROM
				
				`categorias`categorias INNER JOIN `productos` productos  ON categorias.`id_categoria` = productos.`id_categoria` 
				LEFT JOIN centro_costo centro_costo on productos.grupo =centro_costo.id_centro_costo
		

				WHERE productos.`id_empresa`='".$sesion_id_empresa."' and CONCAT(productos.`id_producto`, productos.`producto`) LIKE '%$queryString%'  LIMIT 20; ";
        // echo $query;

				$result = mysql_query($query) or die(mysql_error());
            $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
            if($result) {
            	if($numero_filas  == 0){
            		echo "<div class='alert alert-danger'> No hay resultados con el parámetro ingresado. </div>";
            		echo "	<th style='padding-right: 5px;'><a href='javascript: fn_cerrar_div();'>	<button type='button' class='btn btn-danger' aria-label='Left Align'>Cerrar</button></a></th>";
            	}else{
            		
            		echo "<table id='tblServicios".$cont."' class='table table-condensed table-hover table-bordered'>";
            		echo "<thead>";
            		echo "<tr>";
            		echo "<th style='padding-right: 5px;'>Código</th>  
            		<th style='padding-right: 5px;'>Nombre</th>
            		<th style='padding-right: 5px;'>Categoria</th> 
            		<th style='padding-right: 5px;'>Area</th> 
            		<th style='padding-right: 5px;'>Iva</th> 
          
            		<th style='padding-right: 5px;'><a href='javascript: fn_cerrar_div();'>	<button type='button' class='btn btn-danger' aria-label='Left Align'>Cerrar</button></a></th>";
            		echo "</tr>";
            		echo "</thead>";
            		echo "<tbody>";
            		while ($row = mysql_fetch_array($result))
            		{
            		 
            			$id_iva = 0;
            			$iva = 0;
            			
                            // CONSULTA PARA EL IVA
            // 			if ($row["productos_iva"]=='Si')
            // 			{
            // 				$sqliva = "SELECT * FROM impuestos WHERE estado='Activo' and id_empresa='".$sesion_id_empresa."';";
            // 				$result2 = mysql_query($sqliva);
            				
            // 				while ($row2 = mysql_fetch_array($result2)) {
            // 					$id_iva = $row2["id_iva"];
            // 					$iva = $row2["iva"];
            // 				}	
            				
            // 				$sqliva = "SELECT proveedores.`enlace_retencion_iva` FROM proveedores WHERE id_proveedor='".$proveedor."'  ;";
            // 				$result2 = mysql_query($sqliva);
            				
            // 				while ($row2 = mysql_fetch_array($result2)) {
            // 					$enlaceRetencionIva = $row2["enlace_retencion_iva"];
            				
            // 				}	
            				
            // 			}
            
                        $sqliva = "SELECT * FROM impuestos WHERE estado='Activo' and id_iva='".$row["productos_iva"]."' ";
                 
                        $result2 = mysql_query($sqliva);
                        while ($row2 = mysql_fetch_array($result2)) {
                            
                                $id_iva = $row2["id_iva"];
                                $iva = $row2["iva"];
                                
                        }	
            
            			echo '<tr  style="cursor: pointer" onClick="fill_cpra_edu(\''.$cont.'\','.$row["productos_id_producto"].',\''. $row["productos_codigo"]."*".$row["productos_nombre"]."*".$row["proveedores_nombre_comercial"]."*".
            			$row["productos_stock"]."*".$row["productos_costo"]."*".$row["productos_id_cuenta"]."*".$iva."*".$row["productos_tipos_compra"]."*".$row["productos_precio1"]."*".$id_iva."*".$iva."*".$tipoProducto."*".$row["productos_codPrincipal"]."*".
            			$row["productos_codAux"]."*".$row["centro_costo_id"]."*".$row["centro_costo_codigo"]."*".$row["centro_costo_id_bodega"]."*".$row["centro_costo_tipo"]."*".$row["centro_costo_descripcion"]."*".$row["centro_costo_id_cuenta"].  '\');" title="Clic para seleccionar">';
						
            			echo "<td>".$row["productos_codigo"]."</td>";
            			echo "<td>".$row["productos_nombre"]."</td>";
            			echo "<td>".$row["categorias_categoria"]."</td>";
            			echo "<td>".$row["centro_costo_descripcion"]."</td>";
            			
            			echo "<td>".$iva."</td>";
            			
            			echo "<td>".$tipoProducto."</td>";
            			echo "</tr>";
            			
            		}
            		echo "</tbody>";
            		echo"</table>";
            	}
            } 
            else 
            {
            	echo 'ERROR: Hay un problema con la consulta.';
            }
        } 
        else
        {
        	echo 'La longitud no es la permitida.';
        }
    } 
    else 	
    {
    	echo 'No hay ningún acceso directo a este script!';
    }
    
}

if($accion == "7")
{
	//echo "estoy en sql accion kkkkkkkkkkkkkkkkkkkkkkkkkkkkjja";
	  if(isset($_POST['queryString'])) 
{
        $queryString = $_POST['queryString'];
        $cont = $_POST['cont'];
//echo $cont;
        // Is the string length greater than 0?
 
    if(strlen($queryString) >0) 
		{        
			//echo "kkkkkkkkkkkkkkkkkkkkkkkkkkkkjja";
			$query = "SELECT
			centro_costo.id_centro_costo as id_centro_costo,
			centro_costo.id_bodega as id_bod,
			centro_costo.codigo as cod,
            centro_costo.descripcion as descri,
            centro_costo.id_cuenta as cuenta,
            centro_costo.tipo as tipo,
            
            tipos_compras.id_tipo_cpra as tipo_id,
            tipos_compras.descripcion as tipo_des,
            
            plan_cuentas.id_plan_cuenta as id_cuenta,
            plan_cuentas.nombre as nombre_cuenta
            
            
        FROM
        
           centro_costo 
           INNER JOIN `tipos_compras` tipos_compras	ON tipos_compras.`id_tipo_cpra` = centro_costo.`tipo` 
           INNER JOIN `plan_cuentas` plan_cuentas	ON plan_cuentas.`id_plan_cuenta` = centro_costo.`id_cuenta` 
           
            WHERE centro_costo.`empresa`='".$sesion_id_empresa."' and CONCAT(centro_costo.`descripcion`, centro_costo.`codigo`) LIKE '%$queryString%'  LIMIT 20; ";

            $result = mysql_query($query) or die(mysql_error());
            $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
            if($result) {
                if($numero_filas  == 0){
				           echo "<div class='alert alert-danger'> No hay resultados con el parámetro ingresado. </div>";
                 }else{
				    
                    echo "<table id='tblServicios".$cont."' class='table table-condensed table-hover'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th style='padding-right: 5px;'>Código</th>  
                          <th style='padding-right: 5px;'>Nombre</th>
					      <th style='padding-right: 5px;'><a href='javascript: fn_cerrar_div();'>	<button type='button' class='btn btn-default' aria-label='Left Align'><span class='fa fa-close' aria-hidden='true'></span></button></a></th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while ($row = mysql_fetch_array($result))
					{
					      echo '<tr  style="cursor: pointer"
					    onClick="fill_cpra_bod(\''.$cont.'\','.$row["id_centro_costo"].',\''.
                               $row["cod"]."*".$row["descri"]."*".$row["cuenta"]."*".$row["tipo_id"].'\');" 
                               
                               title="Clic para seleccionar">';
					     
					        echo "<td>".$row["cod"]."</td>";
                            echo "<td>".$row["descri"]."</td>";
                            echo "<td>".$row["nombre_cuenta"]."</td>";
                            echo "<td>".$row["tipo_des"]."</td>";
                            
                            
                            
	                        echo "</tr>";
                    }
                        echo "</tbody>";
                        echo"</table>";
                }
            } 
			else 
			{
				echo 'ERROR: Hay un problema con la consulta.';
            }
        } 
		else
			{
				echo 'La longitud no es la permitida.';
			}
    } 
	else 	
	{
        echo 'No hay ningún acceso directo a este script!';
	}
   
}

if($accion == "8")
{
	//echo "estoy en sql accion kkkkkkkkkkkkkkkkkkkkkkkkkkkkjja";
	  if(isset($_POST['queryString'])) 
{
        $queryString = $_POST['queryString'];
        $cont = $_POST['cont'];
//echo $cont;
        // Is the string length greater than 0?
 
    if(strlen($queryString) >0) 
		{        
			//echo "kkkkkkkkkkkkkkkkkkkkkkkkkkkkjja";
			$query = "SELECT
			centro_costo.id_bodega as id_bod,
			centro_costo.codigo as cod,
            centro_costo.descripcion as descri,
            centro_costo.id_cuenta as cuenta
            
        FROM
        
           centro_costo
            WHERE centro_costo.`empresa`='".$sesion_id_empresa."' and CONCAT(centro_costo.`descripcion`, centro_costo.`codigo`) LIKE '%$queryString%'  LIMIT 20; ";

            $result = mysql_query($query) or die(mysql_error());
            $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
            if($result) {
                if($numero_filas  == 0){
				           echo "<div class='alert alert-danger'> No hay resultados con el parámetro ingresado. </div>";
                 }else{
				    
                    echo "<table id='tblServicios".$cont."' class='table table-condensed table-hover'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th style='padding-right: 5px;'>Código</th>  
                          <th style='padding-right: 5px;'>Nombre</th>
					      <th style='padding-right: 5px;'><a href='javascript: fn_cerrar_div();'>	<button type='button' class='btn btn-default' aria-label='Left Align'><span class='fa fa-close' aria-hidden='true'></span></button></a></th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while ($row = mysql_fetch_array($result))
					{
					      echo '<tr  style="cursor: pointer"
					    onClick="fill_cpra_bodD(\''.$cont.'\','.$row["id_bod"].',\''.
                               $row["cod"]."*".$row["descri"]."*".$row["cuenta"].'\');" 
                               
                               title="Clic para seleccionar">';
					     
					      echo "<td>".$row["cod"]."</td>";
                            echo "<td>".$row["descri"]."</td>";
	                        echo "</tr>";
                    }
                        echo "</tbody>";
                        echo"</table>";
                }
            } 
			else 
			{
				echo 'ERROR: Hay un problema con la consulta.';
            }
        } 
		else
			{
				echo 'La longitud no es la permitida.';
			}
    } 
	else 	
	{
        echo 'No hay ningún acceso directo a este script!';
	}
   
}


if($accion == "9"){
    
    
    //     $sqlEstablecimiento="Select codigo From  establecimientos where id='".$establecimiento_codigo."' ";
    //     $respuestas['sql']= $sqlEstablecimiento;
	   // $resultEstablecimiento = mysql_query($sqlEstablecimiento);
    //     while($rowEstablecimiento= mysql_fetch_array($resultEstablecimiento)){
    //              $est=$rowEstablecimiento['codigo'];
    //     }
        
    //     $sqlEmision="Select codigo From  emision where id='".$emision_codigo."' ";
	   // $resultEmision = mysql_query($sqlEmision);
    //     while($rowEmision= mysql_fetch_array($resultEmision)){
    //              $emi=$rowEmision['codigo'];
    //     }
    
    
	$sqlp="Select max(numero_factura_compra), max(id_compra) 
	
	From 
	
	compras where id_empresa='".$sesion_id_empresa."' ;";
	
    $result=mysql_query($sqlp);
    $numero_factura_compra='0';
    $id_compra="0";
    
    while($row=mysql_fetch_array($result)){ 
        $numero_factura_compra=$row['max(numero_factura_compra)'];
        $id_compra=$row['max(id_compra)'];
    }
    
    $numero_factura_compra++;
    $id_compra++;
    
    if($_POST['tipoDocumento']=='03'){
        $sql2="Select max(numero_factura_compra) From  compras where id_empresa='".$sesion_id_empresa."' and TipoComprobante='3'";
	
        $result2 = mysql_query($sql2);
        $numero_factura_compra2='0';
        while($row2= mysql_fetch_array($result2)){
                 $numero_factura_compra2=$row2['max(numero_factura_compra)'];
            
        }
        $numero_factura_compra2++;
        $respuestas['numeroFactura2']=  $numero_factura_compra2;
    }
    
    $respuestas['numeroFactura']=  $numero_factura_compra;
    $respuestas['emision_factura'] = $emision_codigo;
	$respuestas['emision_establecimiento'] = $establecimiento_codigo;
    echo json_encode($respuestas);
    
}




if($accion == "10"){
    
    	$consulta5="SELECT * FROM bodegas where id_empresa='".$sesion_id_empresa."' ";
        $result=mysql_query($consulta5);
        while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
        {
        echo "<option value='{$row["id"]}'   >{$row["detalle"]}</option>";
        }
}


if($accion == "11"){
    
    	$consulta5="SELECT * FROM centrodecostos where id_empresa='".$sesion_id_empresa."' ";
        $result=mysql_query($consulta5);
        while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
        {
            echo "<option value='{$row["id"]}'   >{$row["detalle"]}</option>";
        }
}

if($accion == "12"){
    
    	$consulta5="SELECT codAux FROM productos where id_empresa='".$sesion_id_empresa."' and codPrincipal!=''";
        $result=mysql_query($consulta5);
        while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
        {
            echo "<option value='{$row["codAux"]}'   >{$row["codAux"]}</option>";
        }
}

if($accion == "13"){
    
    	$consulta5="SELECT codPrincipal FROM productos where id_empresa='".$sesion_id_empresa."' and codPrincipal!='' ";
    	echo $consulta5;
        $result=mysql_query($consulta5);
        while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
        {
            echo "<option value='{$row["codPrincipal"]}'   >{$row["codPrincipal"]}</option>";
        }
}

if($accion == "14"){
    
	$consulta5="SELECT * FROM centro_costo_empresa where id_empresa='".$sesion_id_empresa."' ";
	$result=mysql_query($consulta5);
	echo "<option value='0' >Seleccionar centro de costo:</option>";
	while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
	{
		echo "<option value='{$row["id_centro_costo"]}'   >{$row["detalle"]}</option>";
	}
}
// if($accion === "15"){
    
//         $tipo_comprobante = $_POST['tipo_comprobante'];
//     	$sqlk="UPDATE `mcretencion` SET `anulado`='1' WHERE `id`='$tipo_comprobante'";
// 		$resultk=mysql_query($sqlk) ;
// 		if($resultk){
// 		    echo "1";
// 		}else{
// 		    echo "2";
// 		}
// }



if($accion === "15"){
    
    $tipo_comprobante = $_POST['tipo_comprobante'];
    echo $sqlk="UPDATE `mcretencion` SET `anulado`='1' WHERE `id`='$tipo_comprobante'";
    $resultk=mysql_query($sqlk) ;
    if($resultk){
        echo "1";
    }else{
        echo "2";
    }
}
if($accion === "16"){

$tipo_comprobante = $_POST['tipo_comprobante'];
$sqlk="SELECT MAX(`Id`) as id FROM `mcretencion`";
$resultk=mysql_query($sqlk) ;
$ultimoId=0;
while ($row = mysql_fetch_array($resultk)){
    $ultimoId= $row['id'];
}
 $ultimoId= ($ultimoId=='')?0:$ultimoId;

if($tipo_comprobante> $ultimoId){
    echo '1';
}else{
    echo '2';
}


}

if($accion === "17"){
$id = $_POST['tipo_comprobante'];


 $sql = "SELECT  autorizacion,Factura_id from mcretencion 
where id = '$id' ";
$resultk=mysql_query($sql) ;
$autorizacion='';
$factura='';
while ($row = mysql_fetch_array($resultk)){
    $autorizacion= $row['autorizacion'];
    $factura= $row['Factura_id'];
}

if($autorizacion==''){

     $sql2="DELETE FROM  `cobrospagos`  WHERE id_factura=$factura and documento='1' ";
    $result2=mysql_query($sql2) ;
    $seEjecuto[0]=($result2)?1:2;

     $sql3="DELETE FROM `mcretencion` WHERE `Id`=$id";
    $result3=mysql_query($sql3) ;
    $seEjecuto[1]=($result3)?1:2;

     $sql4="DELETE FROM `dcretencion` WHERE `Retencion_id`=$id";
    $result4=mysql_query($sql4) ;
    $seEjecuto[2]=($result4)?1:2;

     $sql5="SELECT secRetencion1 FROM `retenciones` WHERE`id_empresa`=$sesion_id_empresa AND `estabRetencion1` ='".$establecimiento_codigo."' AND `ptoEmiRetencion1`='".$emision_codigo."'";
    $result5=mysql_query($sql5) ;
    $seEjecuto[3]=($result5)?1:2;
    $numeroSecuencia=0;
    while($row5 = mysql_fetch_array($result5)){
     $numeroSecuencia = 	$row5['secRetencion1'];
    }
    $numeroSecuencia=$numeroSecuencia-1;

     $sql6="UPDATE `retenciones` SET `secRetencion1`='".$numeroSecuencia."' WHERE`id_empresa`=$sesion_id_empresa AND `estabRetencion1` ='".$establecimiento_codigo."' AND `ptoEmiRetencion1`='".$emision_codigo."' ";
    $result6 = mysql_query($sql6);
    $seEjecuto[4]=($result6)?1:2;

    if(array_search('2', $seEjecuto)){
        echo '2';
    }else{
        echo '1';
    }
    

}else{
    echo '2';
}

}


if($accion == "20")
{
	//echo "estoy en sql accion kkkkkkkkkkkkkkkkkkkkkkkkkkkkjja";
	  if(isset($_POST['queryString'])) 
{
        $queryString = $_POST['queryString'];
        $cont = $_POST['cont'];
//echo $cont;
        // Is the string length greater than 0?
 
    if(strlen($queryString) >0) 
		{        
			//echo "kkkkkkkkkkkkkkkkkkkkkkkkkkkkjja";
			$query = "SELECT
			'Mercaderia terminada' as produccion_descri,
			parametros_produccion.area_mercaderia_terminada,
			centro_costo.id_centro_costo as id_centro_costo,
			centro_costo.id_bodega as id_bod,
			centro_costo.codigo as cod,
            centro_costo.descripcion as descri,
            centro_costo.id_cuenta as cuenta,
            centro_costo.tipo as tipo,
            
            tipos_compras.id_tipo_cpra as tipo_id,
            tipos_compras.descripcion as tipo_des,
            
            plan_cuentas.id_plan_cuenta as id_cuenta,
            plan_cuentas.nombre as nombre_cuenta
            
            
        FROM
        	parametros_produccion 
            INNER JOIN  centro_costo on  centro_costo.id_centro_costo =parametros_produccion.area_mercaderia_terminada
           INNER JOIN `tipos_compras` tipos_compras	ON tipos_compras.`id_tipo_cpra` = centro_costo.`tipo` 
           INNER JOIN `plan_cuentas` plan_cuentas	ON plan_cuentas.`id_plan_cuenta` = centro_costo.`id_cuenta` 
           
            WHERE parametros_produccion.id_empresa='".$sesion_id_empresa."'
            
 UNION
 
 	SELECT
    		'Inventario en proceso' as produccion_descri,
			parametros_produccion.`area_inventario_proceso`,
			centro_costo.id_centro_costo as id_centro_costo,
			centro_costo.id_bodega as id_bod,
			centro_costo.codigo as cod,
            centro_costo.descripcion as descri,
            centro_costo.id_cuenta as cuenta,
            centro_costo.tipo as tipo,
            
            tipos_compras.id_tipo_cpra as tipo_id,
            tipos_compras.descripcion as tipo_des,
            
            plan_cuentas.id_plan_cuenta as id_cuenta,
            plan_cuentas.nombre as nombre_cuenta
            
            
        FROM
        	parametros_produccion 
            INNER JOIN  centro_costo on  centro_costo.id_centro_costo =parametros_produccion.area_inventario_proceso
           INNER JOIN `tipos_compras` tipos_compras	ON tipos_compras.`id_tipo_cpra` = centro_costo.`tipo` 
           INNER JOIN `plan_cuentas` plan_cuentas	ON plan_cuentas.`id_plan_cuenta` = centro_costo.`id_cuenta` 
           
            WHERE parametros_produccion.id_empresa='".$sesion_id_empresa."' ";

            $result = mysql_query($query) or die(mysql_error());
            $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
            if($result) {
                if($numero_filas  == 0){
				           echo "<div class='alert alert-danger'> No hay resultados con el parámetro ingresado. </div>";
                 }else{
				    
                    echo "<table id='tblServicios".$cont."' class='table table-condensed table-hover'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th style='padding-right: 5px;'>Código</th>  
                          <th style='padding-right: 5px;'>Nombre</th>
					      <th style='padding-right: 5px;'><a href='javascript: fn_cerrar_div();'>	<button type='button' class='btn btn-default' aria-label='Left Align'><span class='fa fa-close' aria-hidden='true'></span></button></a></th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while ($row = mysql_fetch_array($result))
					{
					      echo '<tr  style="cursor: pointer"
					    onClick="fill_cpra_bod(\''.$cont.'\','.$row["id_centro_costo"].',\''.
                               $row["cod"]."*".$row["produccion_descri"]."*".$row["cuenta"]."*".$row["tipo_id"].'\');" 
                               
                               title="Clic para seleccionar">';
					     
					        echo "<td>".$row["cod"]."</td>";
                            echo "<td>".$row["produccion_descri"]."</td>";
                            echo "<td>".$row["nombre_cuenta"]."</td>";
                            echo "<td>".$row["tipo_des"]."</td>";
                            
                            
                            
	                        echo "</tr>";
                    }
                        echo "</tbody>";
                        echo"</table>";
                }
            } 
			else 
			{
				echo 'ERROR: Hay un problema con la consulta.';
            }
        } 
		else
			{
				echo 'La longitud no es la permitida.';
			}
    } 
	else 	
	{
        echo 'No hay ningún acceso directo a este script!';
	}
   
}

if($accion == "21"){
    
	$consulta5="SELECT * FROM productos where id_empresa='".$sesion_id_empresa."' and tipos_compras='2'";
	$result=mysql_query($consulta5);
	echo "<option value='0' >Seleccionar:</option>";
	while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
	{
		echo "<option value='{$row["id_producto"]}'   >{$row["producto"]}</option>";
	}
	
}

if($accion == "22"){
    $id_compra = $_POST['id_compra'];
	$consulta5="SELECT proveedores.id_proveedor, proveedores.nombre_comercial, proveedores.nombre, proveedores.nombreProveedor, compras.id_compra , mcretencion.Factura_id, mcretencion.Autorizacion, mcretencion.anulado FROM proveedores 
	INNER JOIN compras ON compras.id_proveedor = proveedores.id_proveedor 
	INNER JOIN mcretencion ON mcretencion.Factura_id = compras.id_compra AND mcretencion.TipoC=1 
	WHERE id_compra = $id_compra AND mcretencion.anulado=0;";
	$result=mysql_query($consulta5);
    $numFilas = mysql_num_rows($result);

	echo $numFilas;
}

?>