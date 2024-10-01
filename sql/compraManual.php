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
	
    //echo $sesion_tipo_empresa;
  
    date_default_timezone_set('America/Guayaquil');

        $accion = $_POST['txtAccion'];
    if($accion == "1")
{
 // GUARDAR FACTURA COMPRA PAGINA: nuevaFacturaCompra.php
    try
    {
		$numero_factura=$_POST['txtFactura'];
		$fecha_compra=$_POST['textFecha'];
		$total=$_POST['txtTotal'];
		$subTotal0=$_POST['subTotal0'];
		$subTotal12=$_POST['subTotal12'];
		
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
// 		$txtTipoComprobante=$_POST['txtTipoComprobante'];
	    $txtTipoComprobante='00';
		$codSustento=$_POST['codSustento'];
		
		$txtContadorFilas=$_POST['txtContadorFilasCpra'];
		
		$txtSubtotalInventarios=($_POST['txtSubtotalInventarios']=='')?'0.00':$_POST['txtSubtotalInventarios'];
		
		$txtSubtotalProveeduria=($_POST['txtSubtotalProveeduria']=='')?'0.00':$_POST['txtSubtotalProveeduria'];
		
		$txtSubtotalServicios=($_POST['txtSubtotalServicios']=='')?'0.00':$_POST['txtSubtotalServicios'];
		$txtDescuento = (trim($_POST['txtDescuento'])=='')?0:$_POST['txtDescuento'];
		
		$ats = $_POST['switch-two'];
		$ats = ($ats!='')?$ats :0;
			$response = [];
		if($fecha_compra!="" && $id_proveedor!="" )
		{

			$sql="insert into compras ( fecha_compra, total, sub_total, id_iva, id_proveedor, numero_factura_compra,
			id_empresa,numSerie,autorizacion,caducidad,	TipoComprobante,codSustento,txtEmision,txtNum ,subTotal12,subTotal0,exentoIVA, noObjetoIVA,xml,subTotalInvenarios,subTotalProveeduria,subtotalServicios,descuento,ats) 
			values ('".$fecha_compra."','".$total."','".$sub_total."','".$iva."','".$id_proveedor."',
			'".$numero_factura."', '".$sesion_id_empresa."', '".$numSerie."', '".$autorizacion."', '".$txtFechaVencimiento."'
			, '".$txtTipoComprobante."', '".$codSustento."','".$txtEmision."','".$txtNum."', '".$subTotal12."', '".$subTotal0."', '0.00', '0.00','1', '".$txtSubtotalInventarios."', '".$txtSubtotalProveeduria."', '".$txtSubtotalServicios."' ,'".$txtDescuento."' ,'".$ats."');";
			
			
        $result=mysql_query($sql);
        // echo $sql;
		$id_compra=mysql_insert_id();
	 $response['id_compra'] = $id_compra;
	 $response['id_proveedor'] = $id_proveedor;
        if ($result){
            $response['logs'] = [];
            array_push($response['logs'], 'La compra se ha guardado correctamente');
            for($i=1; $i<=$txtContadorFilas; $i++){
                
                    $id_producto2=$_POST['idproducto'.$i];
                    $id_producto=(int)$id_producto2;
                    
                    
                  
                        $centroCosto = (trim($_POST['centrodeCostoEmpresa'.$i])!='0')?trim($_POST['centrodeCostoEmpresa'.$i]):trim($_POST['cmbCentro']);
                    // $idbod2=$centroCosto;
                  
                         $idbod2=$_POST['idbod'.$i];
                    
                   
                    $idbod=(int)$idbod2;
                    
                    
                    
                    $idcuenta2=$_POST['cuenta'.$i];
                    $idcuenta=(int)$idcuenta2;
                    
                    $cantidad2=$_POST['cant'.$i];
                    $cantidad=(int)$cantidad2;
                    $valor_unitario2=$_POST['valun'.$i];
                    $valor_unitario=$valor_unitario2;
                    $valor_total2=$_POST['valtotal'.$i];
                    $valor_total=(int)$valor_total2;
                    $tipo2=$_POST['idTipo'.$i];
                    
                    $codProducto2=$_POST['codProducto'.$i];

                     $desc= (trim($_POST['desc'.$i])=='')?0:$_POST['desc'.$i];
                    
                    $codPrincipal=$_POST['codPrincipal'.$i];
                    $codAux=$_POST['codAux'.$i];
                    
                    $bodInventario1=$_POST['bodInventario'.$i];
                    
                  $sql2="insert into detalle_compras (    idBodega,   idBodegaInventario,    cantidad,       valor_unitario,     valor_total,        id_compra,          id_producto,        id_empresa,centro_costo_empresa,des ) values 
                                                        ('".$idbod."','".$bodInventario1."','".$cantidad."','".$valor_unitario."','".$valor_total."','".$id_compra."','".$id_producto."', '".$sesion_id_empresa."','".$centroCosto."','".$desc."');";
                    $id_detalle_compra=mysql_insert_id();
                    $result2=mysql_query($sql2) or die("\nError  1: ".mysql_error());
                }
                
                
            array_push($response['logs'], 'La  detalles de la compra se han guardado correctamente');


		$response['mensajes'] = [];
		array_push($response['mensajes'] , 'Compra  guardada correctamente!');


		    
			echo json_encode($response);
                
        } else { ?><div class="alert alert-danger">Compra no guardada</div><?php }

    
    
    }//fin if de pregunta, si hay datos guardar factura
        
    
    else{
        echo "3";
    }

    }catch (Exception $e) {
    // Error en algun momento.
       echo "Error: ".$e;
    }
}