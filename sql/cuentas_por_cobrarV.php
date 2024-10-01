<?php

require_once('../ver_sesion.php');

//Start session
session_start();

//Include database connection details
require_once('../conexion.php');

$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];

date_default_timezone_set('America/Guayaquil');

$accion = $_POST['txtAccion'];
$tipoPago = $_POST['tipox'];
// function pagar_membresia( $id_cuentaCobrar, $apagar=0, $hoy){
  
// 					$apagar = floatval($apagar);
// 					$sql="SELECT
// 					registros_cuentas_por_cobrar.`id_cuenta_por_cobrar`,
// 					registros_cuentas_por_cobrar.`tipo_documento`,
// 					registros_cuentas_por_cobrar.`numero_factura`,
// 					registros_cuentas_por_cobrar.`referencia`,
// 					registros_cuentas_por_cobrar.`valor`,
// 					registros_cuentas_por_cobrar.`saldo`,
// 					registros_cuentas_por_cobrar.`numero_asiento`,
// 					registros_cuentas_por_cobrar.`fecha_vencimiento`,
// 					registros_cuentas_por_cobrar.`fecha_pago`,
// 					registros_cuentas_por_cobrar.`id_proveedor`,
// 					registros_cuentas_por_cobrar.`id_cliente`,
// 					registros_cuentas_por_cobrar.`id_plan_cuenta`,
// 					registros_cuentas_por_cobrar.`id_empresa`,
// 					registros_cuentas_por_cobrar.`id_venta`,
// 					registros_cuentas_por_cobrar.`estado`,
// 					registros_cuentas_por_cobrar.`id_forma_pago`,
// 					registros_cuentas_por_cobrar.`banco_referencia`,
// 					registros_cuentas_por_cobrar.`documento_numero`,
// 					registros_cuentas_por_cobrar.`id_empleado`,
// 					registros_cuentas_por_cobrar.`cuotaAdmin`,
// 					registros_cuentas_por_cobrar.`motivoDescuento`,
// 					registros_cuentas_por_cobrar.`id_lead`,
// 					registros_cuentas_por_cobrar.`fecha_correponde`,
// 					registros_cuentas_por_cobrar.`id_estudiante`,
// 					registros_cuentas_por_cobrar.`id_inmueble`,
// 					registros_cuentas_por_cobrar.`fecha_ingreso`,
// 					registros_cuentas_por_cobrar.`fecha_creacion_cuotas`
// 				FROM
// 					`registros_cuentas_por_cobrar`
// 				INNER JOIN ventas ON ventas.id_venta = registros_cuentas_por_cobrar.id_venta
// 				INNER JOIN cuentas_por_cobrar ON cuentas_por_cobrar.tipo_documento = 1 AND cuentas_por_cobrar.id_venta = ventas.id_venta AND cuentas_por_cobrar.membresia = 1
// 				WHERE registros_cuentas_por_cobrar.saldo>0 and cuentas_por_cobrar.id_cuenta_por_cobrar =$id_cuentaCobrar  
// 				ORDER by registros_cuentas_por_cobrar.fecha_vencimiento ASC";
		
// 					$result= mysql_query($sql);
// 					$numFilas = mysql_num_rows($result);
// 				$todo = '';
// 					$cobrando =0;
// 					$saldo=0;
// 					while($rowRCC = mysql_fetch_array($result) ){
// 						$id_registro = $rowRCC['id_cuenta_por_cobrar'];
// 						$saldo_registro = floatval($rowRCC['saldo']) ;
// 						$acobrar = 0;
						
						
// 						if ($saldo_registro<=$apagar)
// 							{
								
// 								$estadoRR = "Canceladas";
// 								$saldo=0;
// 								$acobrar= $apagar-$saldo_registro;
// 							}
// 							else
// 							{
// 								$saldo=$saldo_registro-$apagar;
// 								$estadoRR = "Pendientes";
// 								$acobrar=$apagar;
// 							}	
// 							$apagar = $apagar - $acobrar;
// 							// $saldo=$valor_x_cancelarx-$facturas_x_cobrar[$j][2];
						

// 						$sqlUpdate ="UPDATE `registros_cuentas_por_cobrar` SET `saldo`='".$saldo."',`fecha_pago`='".$hoy."',`estado`='".$estadoRR."' WHERE id_cuenta_por_cobrar=$id_registro ";
// 						$resultUpdate = mysql_query($sqlUpdate);
// 						$todo .= $sqlUpdate.'***'.$apagar.'***';
// 					}
// return $todo;
// 				}
if($accion == "11"){
    $listado_numeros_facturas='';
    function ceros($valor){
	$s='';
 for($i=1;$i<=9-strlen($valor);$i++)
	 $s.="0";
 return $s.$valor;
}
    function cantidad_num_factura($iV){
        
        $sqlVenta = "SELECT
    ventas.id_venta,
    ventas.fecha_venta,
    ventas.`numero_factura_venta` AS ventas_numero_factura_venta, 
     establecimientos.codigo as establecimientos_codigo, 
    emision.codigo as emision_codigo 
FROM
    `ventas`
 INNER JOIN establecimientos ON establecimientos.id= ventas.codigo_pun
      INNER JOIN emision ON emision.id=ventas.codigo_lug 
WHERE
    ventas.id_venta = $iV";
         $result2 = mysql_query($sqlVenta); 
	while($row2 = mysql_fetch_array($result2)) { 
	    $numeroFactura= $row2['ventas_numero_factura_venta'];
        $estCod= $row2['establecimientos_codigo'];
        $emiCod= $row2['emision_codigo'];
	    $numeroFactura= ceros($numeroFactura);
        $numeroVenta=  "(".$estCod."-".$emiCod."-".$numeroFactura.")";
	}

      return $numeroVenta;
    }
	$response = array();

		// GUARDAR PAGO CUENTAS POR COBRAR  PAGINA: 
		try
		{
			$txtIdCliente = ($_POST['txtIdCliente']);
			$txtIdPlanCuentas = ($_POST['txtIdPlanCuentas']);
			$txtFechaPago = ($_POST['txtFechaPago']);         
			$hora = date("H:i");
			$cmbFormaPagoFP = ($_POST['cmbFormaPagoFP']); 
			$idFormaPago = explode("*", $cmbFormaPagoFP);
			$txtBancoReferencia = ($_POST['txtBancoReferencia']);
			$txtDocumentoNumero = ($_POST['txtDocumentoNumero']);
			$txtValor = round(floatval($_POST['txtPagoFP']),2);
			$txtEfectivo = ($_POST['txtEfectivo']);
			$txtSaldo= ($_POST['txtSaldo']);
			$txtIdCuentaCobrar = ($_POST['txtIdCuentaCobrar']);
			$txtDeudaTotal = ($_POST['txtDeudaTotal']);		
			$txtDeudor = ($_POST['txtDeudor']);
			$fecha_pago = date("Y-m-d h:i:s");
			$tipo_persona= $_POST['switch-four'];
			$valor_x_cancelar=0;
			$valor_x_cancelar=$txtValor;
			$response['totalAbonado'] =$txtValor ;	

			$sumaSaldosCuentas =0;
			 if ($tipoPago == 1)
			{	
				$sumaSaldosCuentas =$txtDeudaTotal;
				$valor_x_cancelar=$txtValor;
			}
			else
			{
				$arregloCHK=$_POST['checkCobrar'];
				
				$num=count($arregloCHK);
				$numero_cancelaciones=$num;	
				$n=0;
				for ($n=0; $n<$num; $n++)
				{
					$id_pagos_cuotas = $arregloCHK[$n];
					$facturas_x_cobrar[$n][1]=$arregloCHK[$n];	
					$sqlBuscarVenta = "SELECT `id_cuenta_por_cobrar`,  `id_venta`,saldo FROM `cuentas_por_cobrar` WHERE id_cuenta_por_cobrar='".$arregloCHK[$n]."'";
					$resultBuscarVenta = mysql_query($sqlBuscarVenta);
					$numFilasVenta= mysql_num_rows($resultBuscarVenta);
						$response['sqlBuscarVenta'][] =$sqlBuscarVenta;	
					if($numFilasVenta>0){
						while($rowBV = mysql_fetch_array($resultBuscarVenta)){
							
							$sumaSaldosCuentas =$sumaSaldosCuentas + $rowBV['saldo'] ;

							if( $rowBV['id_venta']==''){
								$id_venta = '0';
							}else{
								$id_venta = $rowBV['id_venta'];
								$listado_numeros_facturas .= cantidad_num_factura($id_venta);
							}
						}
					}
				}
				$valor_x_cancelar=$txtValor;
			}
			
					$response['num1'] = $arregloCHK[1];	
						$response['num0'] = $arregloCHK[0];	
			
				$response['listado_numeros_facturas'] =$listado_numeros_facturas;	
			if($txtIdCliente != "" && $txtValor != "") 
			{	
			
			if ($tipoPago == 1)
			{	
				  
				 if($tipo_persona==2){
						 $sql = "SELECT
					cuentas_por_cobrar.`id_cuenta_por_cobrar` AS ctas_x_cobrar_id_cuenta_por_cobrar,
					cuentas_por_cobrar.`saldo` AS cuentas_por_cobrar_saldo,
					cuentas_por_cobrar.`id_cliente` AS cuentas_por_cobrar_id_cliente,
					cuentas_por_cobrar.`id_empresa` AS cuentas_por_cobrar_id_empresa,
					cuentas_por_cobrar.`id_venta` AS cuentas_por_cobrar_id_venta,
					cuentas_por_cobrar.`estado` AS cuentas_por_cobrar_estado
				FROM
					`clientes` clientes INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar 
					ON clientes.`id_cliente` = cuentas_por_cobrar.`id_cliente`
					WHERE  cuentas_por_cobrar.`id_empresa`= '".$sesion_id_empresa."' and 
					cuentas_por_cobrar.`id_cliente`='".$txtIdCliente."' and saldo>0 
				  order by cuentas_por_cobrar.`fecha_vencimiento`"; 
				 }else if($tipo_persona==1){
						 $sql = "SELECT
					cuentas_por_cobrar.`id_cuenta_por_cobrar` AS ctas_x_cobrar_id_cuenta_por_cobrar,
					cuentas_por_cobrar.`saldo` AS cuentas_por_cobrar_saldo,
					cuentas_por_cobrar.`id_proveedor` AS cuentas_por_cobrar_id_cliente,
					cuentas_por_cobrar.`id_venta` AS cuentas_por_cobrar_id_venta,
					cuentas_por_cobrar.`id_empresa` AS cuentas_por_cobrar_id_empresa,
					cuentas_por_cobrar.`estado` AS cuentas_por_cobrar_estado
				FROM
					  `proveedores` proveedores  INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar 
					ON proveedores.`id_proveedor` = cuentas_por_cobrar.`id_proveedor`
					WHERE  cuentas_por_cobrar.`id_empresa`= '".$sesion_id_empresa."' and 
					cuentas_por_cobrar.`id_proveedor`='".$txtIdCliente."' and saldo>0 
				  order by cuentas_por_cobrar.`fecha_vencimiento`"; 
				 }else if($tipo_persona==3){
						 $sql = "SELECT
					cuentas_por_cobrar.`id_cuenta_por_cobrar` AS ctas_x_cobrar_id_cuenta_por_cobrar,
					cuentas_por_cobrar.`saldo` AS cuentas_por_cobrar_saldo,
					cuentas_por_cobrar.`id_proveedor` AS cuentas_por_cobrar_id_cliente,
					cuentas_por_cobrar.`id_venta` AS cuentas_por_cobrar_id_venta,
					cuentas_por_cobrar.`id_empresa` AS cuentas_por_cobrar_id_empresa,
					cuentas_por_cobrar.`estado` AS cuentas_por_cobrar_estado
				FROM
					   `leads` leads INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar 
					ON leads.`id` = cuentas_por_cobrar.`id_lead` 
					WHERE  cuentas_por_cobrar.`id_empresa`= '".$sesion_id_empresa."' and 
					cuentas_por_cobrar.`id_lead`='".$txtIdCliente."' and saldo>0 
				  order by cuentas_por_cobrar.`fecha_vencimiento`"; 
				 }else if($tipo_persona==4){
						 $sql = "SELECT
					cuentas_por_cobrar.`id_cuenta_por_cobrar` AS ctas_x_cobrar_id_cuenta_por_cobrar,
					cuentas_por_cobrar.`saldo` AS cuentas_por_cobrar_saldo,
					cuentas_por_cobrar.`id_proveedor` AS cuentas_por_cobrar_id_cliente,
					cuentas_por_cobrar.`id_venta` AS cuentas_por_cobrar_id_venta,
					cuentas_por_cobrar.`id_empresa` AS cuentas_por_cobrar_id_empresa,
					cuentas_por_cobrar.`estado` AS cuentas_por_cobrar_estado
				FROM
					  `empleados` empleados INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar 
					ON empleados.`id_empleado` = cuentas_por_cobrar.`id_empleado` 
					WHERE  cuentas_por_cobrar.`id_empresa`= '".$sesion_id_empresa."' and 
					cuentas_por_cobrar.`id_empleado`='".$txtIdCliente."' and saldo>0 
				  order by cuentas_por_cobrar.`fecha_vencimiento`"; 
				 }else if($tipo_persona==5){
						 $sql = "SELECT
                    cuentas_por_cobrar.`id_cuenta_por_cobrar` AS ctas_x_cobrar_id_cuenta_por_cobrar,
                    cuentas_por_cobrar.`saldo` AS cuentas_por_cobrar_saldo,
                    cuentas_por_cobrar.`id_inmueble` AS cuentas_por_cobrar_id_cliente,
                    cuentas_por_cobrar.`id_venta` AS cuentas_por_cobrar_id_venta,
                    cuentas_por_cobrar.`id_empresa` AS cuentas_por_cobrar_id_empresa,
                    cuentas_por_cobrar.`estado` AS cuentas_por_cobrar_estado
                FROM
                    `inmueble` inmueble
                INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar ON
                    inmueble.`id_inmueble` = cuentas_por_cobrar.`id_inmueble`
                WHERE
                    cuentas_por_cobrar.`id_empresa` = '".$sesion_id_empresa."' AND cuentas_por_cobrar.`id_inmueble` = '".$txtIdCliente."' AND saldo > 0
                ORDER BY
                    cuentas_por_cobrar.`fecha_vencimiento`"; 
				 }else if($tipo_persona==6){
						 $sql = "SELECT
                    cuentas_por_cobrar.`id_cuenta_por_cobrar` AS ctas_x_cobrar_id_cuenta_por_cobrar,
                    cuentas_por_cobrar.`saldo` AS cuentas_por_cobrar_saldo,
                    cuentas_por_cobrar.`id_estudiante` AS cuentas_por_cobrar_id_cliente,
                    cuentas_por_cobrar.`id_venta` AS cuentas_por_cobrar_id_venta,
                    cuentas_por_cobrar.`id_empresa` AS cuentas_por_cobrar_id_empresa,
                    cuentas_por_cobrar.`estado` AS cuentas_por_cobrar_estado
                FROM
                    `estudiante` estudiante
                INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar ON
                    estudiante.`id_estudiante` = cuentas_por_cobrar.`id_estudiante`
                WHERE
                    cuentas_por_cobrar.`id_empresa` = '".$sesion_id_empresa."' AND cuentas_por_cobrar.`id_estudiante` = '".$txtIdCliente."' AND saldo > 0
                ORDER BY
                    cuentas_por_cobrar.`fecha_vencimiento`"; 
				 }
	
				 $response['consultas'][] = $sql;
				$result = mysql_query($sql) or die(mysql_error());
				$i=1;
				$valor_x_cancelarx=$valor_x_cancelar;
				while ($row = mysql_fetch_array($result))
				{ 
					$id_venta=$row['cuentas_por_cobrar_id_venta'];
					$saldo_x_factura=$row['cuentas_por_cobrar_saldo'];				
					$facturas_x_pagar[$i][1]=$row['ctas_x_cobrar_id_cuenta_por_cobrar'];
					$facturas_x_pagar[$i][2]=$row['cuentas_por_cobrar_saldo'];				
					$facturas_x_pagar[$i][3]=$row['cuentas_por_cobrar_id_cliente'];
			//		echo "<br/>"."i=".$i."   id cobros=".$facturas_x_pagar[$i][1];
					if ($saldo_x_factura<$valor_x_cancelarx)
					{
						$facturas_x_pagar[$i][4]=$saldo_x_factura;
					}
					else
					{
						$facturas_x_pagar[$i][4]=$valor_x_cancelarx;
					}
					
					$valor_x_cancelarx=$valor_x_cancelarx-$facturas_x_pagar[$i][4];
					//echo "Pendiente".$valor_x_cancelarx;
					if ($valor_x_cancelarx==0)
					{
						break;
					}
					$i=$i+1;
				}	
	
				$numero_cancelaciones=$i;
			
			
			}else{
					$valor_x_cancelarx=$txtValor;
			}
	
				//************************************ GUARDAR ASIENTO CONTABLE ***************************************/
				//************************************ LIBRO DIARIO ***************************************/
				//permite sacar el numero_asiento de libro_diario
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
				catch(Exception $ex) {
					$response['error'][] = "Error en el id max libro_diario:".$ex;
					}
						  
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
	
				}
				catch(Exception $ex) 
				{ $response['error'][] = "Error en el id max libro_diario:".$ex; }
						
				 
			$tipo_comprobante='Diario';
	//$respCuentaCobrar = mysql_query($sqlCuentaCobrar);            
	 
					// FIN ACTUALIZA LA TABLA CUENTAS POR COBRAR
				
	
					// SACA EL NUMERO DE COMPROBANTE DEPENDIENDO DEL TIPO DE COMPROBANTE
					try{
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
	
					}catch (Exception $e) {
						$response['error'][] = "Error max_numero_comprobante:".$ex;
					   
					}
	
					//SACA EL ID MAX DE COMPROBANTES
					try{
						$sqlCM="Select max(id_comprobante) From comprobantes;";
						$resultCM=mysql_query($sqlCM) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
						$id_comprobante=0;
						while($rowCM=mysql_fetch_array($resultCM))//permite ir de fila en fila de la tabla
						{
							$id_comprobante=$rowCM['max(id_comprobante)'];
						}
						$id_comprobante++;
	
					}catch(Exception $ex) { 
						$response['error'][] = "Error en el id max libro_diario:".$ex;
					}
	
					$fecha= $txtFechaPago." ".$hora;
					if($listado_numeros_facturas!=''){
					    $descripcion = $txtDeudor." Cobros de las Cuentas por Cobrar Generado Automaticamente ".$listado_numeros_facturas;
					}else{
					    $descripcion = $txtDeudor." Cobros de las Cuentas por Cobrar Generado Automaticamente";
					}
					
					
					$debe = $txtValor;
					$haber1 = $txtValor;
					//$haber2 = $total_iva;
					$total_debe = $debe;
					$total_haber = $haber1 ;
	
					//GUARDA EN  COMPROBANTES
					$sqlC = "insert into comprobantes (id_comprobante, tipo_comprobante, numero_comprobante, id_empresa) values 
					('".$id_comprobante."','".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
					
					$respC = mysql_query($sqlC);
					$response['consultas'][] = $sqlC;
					//GUARDA EN EL LIBRO DIARIO
					$sqlLD = "insert into libro_diario (id_libro_diario, id_periodo_contable, numero_asiento, fecha, total_debe, total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante) 
					values ('".$id_libro_diario."','".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha."','".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."', '".$id_comprobante."')";
			  
				 //  echo "</br>".$sqlLD; 
			  
					$resp = mysql_query($sqlLD);
					$response['consultas'][] = $sqlLD ;
					
					//************************************    GUARDA EN EL DETALLE LIBRO DIARIO      **********************
											
				//    $idPlanCuentas[1] = $formas_pago_id_plan_cuenta;
					$idPlanCuentas[1] = $txtIdPlanCuentas;    
				//$idPlanCuentas[2] = $id_plan_cuenta_credito;    
					//$idPlanCuentas[3] = $impuestos_id_plan_cuenta;
	
					$debeVector[1] = $txtValor;
					$haberVector[1] = 0;            
				
				$lin_diario=1;	
				$txtContadorFilas=8;
				for($i=1; $i<=$txtContadorFilas; $i++)
				{
				//$bancos[$lin_diario]='';
					if ($_POST['txtCuentaP'.$i] >=1)
					{   // verifica si en el campo esta agregada una cuenta, // permite sacar el id maximo de detalle_libro_diario
						$lin_diario=$lin_diario+1;
						$idformaPago[$lin_diario]=$_POST['txtCodigoS'.$i];
						$txtTipoP[$lin_diario]=$_POST['txtTipo1'.$i];
						$txtPorcentajeS[$lin_diario]=$_POST['txtPorcentajeS'.$i];
						$txtNumeroRetencionS[$lin_diario]=isset($_POST['txtNumeroRetencionS'.$i])?$_POST['txtNumeroRetencionS'.$i]:'';
						$listadoFP[$lin_diario]=$_POST['formaPagoId'.$i];
						
						$txtAutorizacion[$lin_diario]=isset($_POST['txtAutorizacion'.$i])?$_POST['txtAutorizacion'.$i]:'';
						$idPlanCuentas[$lin_diario]=$_POST['txtCodigoS'.$i];
						
						
						$debeVector[$lin_diario]=round(floatval($_POST['txtValorS'.$i]),2);
						$haberVector[$lin_diario]='0';
						
						
						$fechas_pag_ven[$lin_diario]=date("Ymd"); 
						$nro_cpte[$lin_diario]=$_POST['nrocpteC'.$i];
						$bancos[$lin_diario]='';
						if (strtolower($_POST['txtTipoP'.$i]) =='cheque' OR
						   strtolower($_POST['txtTipoP'.$i]) =='deposito' or
						   strtolower($_POST['txtTipoP'.$i])=='transferencia')
						{
						  $bancos[$lin_diario]=strtolower($_POST['txtTipoP'.$i]);
					 //      echo $bancos[$lin_diario];
						}
					}	
				}

	
		   $sql="Select id_plan_cuenta From formas_pago where id_tipo_movimiento='4' and id_empresa='".$sesion_id_empresa."';";
			$resultado=mysql_query($sql) ;
	
			while($row=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
			{
			$formas_pago_id_plan_cuenta_asiento=$row['id_plan_cuenta'];
			}
		//  		echo "FORMA DE PAGO ==".$formas_pago_id_plan_cuenta_asiento;
	
	//			$sqlDLD2 = "insert into detalle_libro_diario ( id_libro_diario, id_plan_cuenta,
	//					debe, haber, id_periodo_contable) values ('".$id_libro_diario."','".$formas_pago_id_plan_cuenta."','".$txtValor."','".'0'."','".$sesion_id_periodo_contable."');";
		$sqlDLD2 = "insert into detalle_libro_diario ( id_libro_diario, id_plan_cuenta,	debe, haber, id_periodo_contable) 
		values ('".$id_libro_diario."','".$formas_pago_id_plan_cuenta_asiento."','".'0'."','".$txtValor."','".$sesion_id_periodo_contable."');";      
					// 		echo "<br>"." detalle libro diario ".$sqlDLD2."</BR>";
			$resp2 = mysql_query($sqlDLD2) ;
			$response['consultas'][] = $sqlDLD2  ;
			
				for($i=2; $i<=$lin_diario; $i++)
				{
					if ($idPlanCuentas[$i] !="")
					{
	
	
						//GUARDA EN EL DETALLE LIBRO DIARIO
		  $sqlDLD = "insert into detalle_libro_diario (id_libro_diario,       id_plan_cuenta,debe,haber,id_periodo_contable) values ('".$id_libro_diario."','".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."','".$sesion_id_periodo_contable."');";                    
					// echo "<br>"." detalle libro diario 2 ".$sqlDLD."</BR>"; 
						$resp2 = mysql_query($sqlDLD) ;
						$response['consultas'][] =  $sqlDLD ;
						// CONSULTAS PARA GENERAR LA MAYORIZACION
						$sql5 = "SELECT * FROM mayorizacion WHERE id_plan_cuenta ='".$idPlanCuentas[$i]."' AND id_periodo_contable = '".$sesion_id_periodo_contable."';";
						$result5=mysql_query($sql5);
						while($row5=mysql_fetch_array($result5))//permite ir de fila en fila de la tabla
							{
								$id_mayorizacion=$row5['id_mayorizacion'];
							}
						 
	//echo "<br>"." sql5  ".$sql5;                         
						$numero = mysql_num_rows($result5); // obtenemos el número de filas
						if($numero > 0){
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
	
								$sql6 = "insert into mayorizacion (id_mayorizacion, id_plan_cuenta, id_periodo_contable) values 
								('".$id_mayorizacion."','".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
								$result6=mysql_query($sql6);
								$response['consultas'][] =  $sql6  ;
	//echo "<br>"." mayorizacion  ".$sql6;                     
	
							}
							catch(Exception $ex) { 
								$response['error'][] = " Error en la insercion de la tabla mayorizacion:".$ex ;
							 }
	
						}
					// detalle_cuentas_cobrar
					//  $sqlDetalleAbono ="INSERT INTO `detalle_cuentas_por_cobrar`(`valor`, `fecha_pago`, `id_cuenta_por_cobrar`, `id_forma_pago`, `banco_referencia`, `documento_numero`, `saldo`,id_plan_cuenta) VALUES ('".$debeVector[$i]."','".$fecha_pago."','".$txtIdCuentaCobrar."','".$numero_asiento."','".$txtBancoReferencia."','".$txtDocumentoNumero."','".$saldo."','".$idPlanCuentas[$i]."')";
					// 			$resultDetalleAbono= mysql_query($sqlDetalleAbono);
					// 			$response['idDetalles'][] = mysql_insert_id();
					}
				}    
	
		for($i=1; $i<=$lin_diario; $i++)				
				{
					$b=$i+1;
					if($idformaPago[$b] >=1)
					{	
						$id_venta = (trim($id_venta)!='')?$id_venta:0;
						 $sqlforma = "INSERT INTO `cobrospagos`( `id_forma`, `documento`, `id_factura`,id_empresa,valor,tipo,porcentaje,numero_retencion,autorizacion) 
						VALUES     ('".$idformaPago[$b]."','0','".$id_venta."','".$sesion_id_empresa."','".$debeVector[$b]."','".$txtTipoP[$b]."', NULL, '".$txtNumeroRetencionS[$b]."','".$txtAutorizacion[$b]."');";
						// echo $sqlforma;
						
					
						$respForma = mysql_query($sqlforma) or 	die('<div class="transparent_ajax_error"><p>Error 11 : '.mysql_error().' </p></div>  ');
						if($respForma){
							if($txtTipoP[$b]==1 ){
							 $identificador="01";
							}
							else if($txtTipoP[$b]==2){
							  $ident=1;
								$identificador="02";
							}else if($txtTipoP[$b]==16 && $txtTipoP[$b]==17 ){
							//   $ident=1;
								$identificador="19";
							}
							else{
								$identificador="03";
							}
							   if($ident==1){
								   $identificador="02";
							   } 
								 $sql3="update ventas set id_forma_pago='".$identificador."' where id_venta='".$id_venta."' ";
								$resp3 = mysql_query($sql3) or die(mysql_error());
								
							
						}
						
						
					}
				}
				$listadoCuentasCobrarModificadas = array();
				if ($tipoPago == 1)
				{				
					//echo "numero_cancelaciones".$numero_cancelaciones;
					$j=1;
					for ($j=1; $j<=$numero_cancelaciones; $j++)
					{	
				//	echo "valor a cancelar".$facturas_x_pagar[$j][4];
						if ($facturas_x_pagar[$j][4]>0) 
						{
							$txtIdCuentaCobrar=$facturas_x_pagar[$j][1];
							$listadoCuentasCobrarModificadas['idCuentaPagar'][]=$txtIdCuentaCobrar;
							$saldo = $facturas_x_pagar[$j][2]-$facturas_x_pagar[$j][4];
						
				//		echo "<br/>"."Saldo".$saldo;
							if($saldo == 0){
								$estadoCC = "Canceladas";
							}
							else
							{
							$estadoCC = "Pendientes";
							}
				// 	, numero_asiento='".$numero_asiento."'
							$sqlCuentaCobrar = "update cuentas_por_cobrar set saldo='".$saldo."', estado='".$estadoCC."', fecha_pago='".$fecha_pago."', banco_referencia='".$txtBancoReferencia."', 
							documento_numero='".$txtDocumentoNumero."'
							where id_cuenta_por_cobrar='".$txtIdCuentaCobrar."';";
							$respCuentaCobrar = mysql_query($sqlCuentaCobrar);
							$response['consultas'][] = $sqlCuentaCobrar  ;

							$listadoCuentasCobrarModificadas['valor'][]=round(floatval($facturas_x_pagar[$j][4]),2);
							$listadoCuentasCobrarModificadas['saldo'][]=$saldo ;
							if($sesion_id_empresa==41){
							    $hoy = date('Y-m-d');
							 //  $response['consultasmem'][] = pagar_membresia( $txtIdCuentaCobrar, $facturas_x_pagar[$j][4], $hoy);
							}
                            
						}
					}			
				}
				else
				{

	// 			echo "VALOR==>".	$valor_x_cancelarx=$valor_x_cancelar;
				
					// echo "NUMERO CANCELACIONES 222 ==> ".$numero_cancelaciones."</br>";
					$j=0;
					for ($j=0; $j<=$numero_cancelaciones; $j++)
					{	
	// 			echo "valor a cancelar".$facturas_x_pagar[$j][1]."</br>";
				
						if ($facturas_x_cobrar[$j][1]>0) 
						{
							
							$txtIdCuentaCobrar=$facturas_x_cobrar[$j][1];
							$listadoCuentasCobrarModificadas['idCuentaPagar'][]=$txtIdCuentaCobrar;
							$sql="Select saldo from cuentas_por_cobrar 
							where id_cuenta_por_cobrar='".$txtIdCuentaCobrar."';";
					///	echo $sql;
							$result = mysql_query($sql) or die(mysql_error());
							$saldo_x_factura=0;
							while ($row = mysql_fetch_array($result))
							{ 
								
								$saldo_x_factura=round($row['saldo'],2);				
							}
							$listadoCuentasCobrarModificadas['valor'][]=round(floatval($saldo_x_factura),2);

							if ($saldo_x_factura<$valor_x_cancelarx)
							{
								$facturas_x_cobrar[$j][2]=$saldo_x_factura;
								$estadoCC = "Canceladas";
								$saldo=0;
						//		echo "op1";
						// $listadoCuentasCobrarModificadas['valor'][]=round(floatval($saldo_x_factura),2);
						$listadoCuentasCobrarModificadas['saldo'][]=0; 
							}
							else
							{
								$saldo=$saldo_x_factura-$valor_x_cancelarx;
								$estadoCC = "Pendientes";
								$facturas_x_cobrar[$j][2]=$valor_x_cancelarx;
						//	echo "op2";
						// $listadoCuentasCobrarModificadas['valor'][]=round(floatval($valor_x_cancelarx),2);
						$listadoCuentasCobrarModificadas['saldo'][]=floatval($saldo);
							}	
							
							$valor_x_cancelarx=$valor_x_cancelarx-$facturas_x_cobrar[$j][2];
								// numero_asiento='".$numero_asiento."',
							$sqlCuentaCobrar = "update cuentas_por_cobrar set saldo='".$saldo."', estado='".$estadoCC."', fecha_pago='".$fecha_pago."', 
							 id_forma_pago='0', banco_referencia='".$txtBancoReferencia."', 
								documento_numero='".$txtDocumentoNumero."', id_plan_cuenta='0' 
								where id_cuenta_por_cobrar='".$txtIdCuentaCobrar."';";
							$respCuentaCobrar = mysql_query($sqlCuentaCobrar);
							if($respCuentaCobrar){
                                	if($sesion_id_empresa==41){
							    $hoy = date('Y-m-d');
							 //  $response['consultasmem'][] = pagar_membresia( $txtIdCuentaCobrar, $facturas_x_cobrar[$j][2], $hoy);
							}
							}
							$response['consultas'][] = $sqlCuentaCobrar  ;
						//	echo $sqlCuentaCobrar;
						}
					};
				}
				

				
				// var_dump($listadoCuentasCobrarModificadas);
				// echo '<b>';
				// echo '<b>';
				// echo '<b>';
				// var_dump($debeVector);
				// exit;
				$response['listadoCuentasCobrar'][] = $listadoCuentasCobrarModificadas ;
				$response['vectores'][] = $debeVector ;
				$cantidadCuentas = COUNT( $listadoCuentasCobrarModificadas['idCuentaPagar']) -1;
				$response['cantidadCuentas'] = $cantidadCuentas ;
				$contCC=0;
				
				$saldo_cc_actual=0;// es el valor que cobro por cada forma de pago 

					for($i2=2; $i2<=$lin_diario; $i2++)
					{
					

						if ($idPlanCuentas[$i2] !="")
						{
							$id_cc_actual = $listadoCuentasCobrarModificadas['idCuentaPagar'][$contCC];
							
							$response['comparacion'][] ='|'.$debeVector[$i2].'---'.$listadoCuentasCobrarModificadas['valor'][$contCC].'|';

							if(round($debeVector[$i2],2) < round($listadoCuentasCobrarModificadas['valor'][$contCC],2) ){
								// 0,7 < 1
								$saldo_cc_actual= $listadoCuentasCobrarModificadas['valor'][$contCC] - $debeVector[$i2] ;
								$response['saldo_cc_actual'][]=$saldo_cc_actual;
							
								$sqlDetalleAbono ="INSERT INTO `detalle_cuentas_por_cobrar`(`valor`, `fecha_pago`, `id_cuenta_por_cobrar`, `id_forma_pago`, `banco_referencia`, `documento_numero`,id_plan_cuenta,saldo,numero_asiento) VALUES ('".$debeVector[$i2]."','".$fecha_pago."','".$id_cc_actual."','".$listadoFP[$i2]."','".$txtBancoReferencia."','".$txtDocumentoNumero."','".$idPlanCuentas[$i2]."','".$saldo_cc_actual."','".$numero_asiento."')";
								// 			$sqlDetalleAbono = " INSERT INTO `detalle_cuentas_por_cobrar`( `valor`, `fecha_pago`, `id_cuenta_por_cobrar`) VALUES ('".$facturas_x_pagar[$j][4]."','".$fecha_pago."','".$txtIdCuentaCobrar."')";
											$resultDetalleAbono= mysql_query($sqlDetalleAbono);
											$response['consultas'][] = '1=>'.$sqlDetalleAbono  ;
											$response['idDetalles'][] = mysql_insert_id();
											$response['pago'][] =$debeVector[$i2];

											$listadoCuentasCobrarModificadas['valor'][$contCC] = $saldo_cc_actual;
											continue;	
							}

							if(round($debeVector[$i2],2) == round($listadoCuentasCobrarModificadas['valor'][$contCC],2)){
								// 0,7 == 0.7
								$saldoFinal = ($contCC==$cantidadCuentas)?$_POST['txtCambioFP']:0;

								$sqlDetalleAbono ="INSERT INTO `detalle_cuentas_por_cobrar`(`valor`, `fecha_pago`, `id_cuenta_por_cobrar`, `id_forma_pago`, `banco_referencia`, `documento_numero`,id_plan_cuenta,saldo,numero_asiento) VALUES ('".$debeVector[$i2]."','".$fecha_pago."','".$id_cc_actual."','".$listadoFP[$i2]."','".$txtBancoReferencia."','".$txtDocumentoNumero."','".$idPlanCuentas[$i2]."','".$saldoFinal."','".$numero_asiento."')";
								// 			$sqlDetalleAbono = " INSERT INTO `detalle_cuentas_por_cobrar`( `valor`, `fecha_pago`, `id_cuenta_por_cobrar`) VALUES ('".$facturas_x_pagar[$j][4]."','".$fecha_pago."','".$txtIdCuentaCobrar."')";
											$resultDetalleAbono= mysql_query($sqlDetalleAbono);
											$response['consultas'][] = '2=>'.$sqlDetalleAbono  ;
											$response['idDetalles'][] = mysql_insert_id();
											$response['pago'][] =$debeVector[$i2];
											$contCC++;
											continue;	
							}
							
							if(round($debeVector[$i2],2) > round($listadoCuentasCobrarModificadas['valor'][$contCC],2) ){
								// 1 == 0.7
								while($debeVector[$i2] > 0){
									$id_cc_actual = $listadoCuentasCobrarModificadas['idCuentaPagar'][$contCC];

									if( $debeVector[$i2] <= $listadoCuentasCobrarModificadas['valor'][$contCC] ){
										$pago_actual= $debeVector[$i2];
										$saldo_actual =  $listadoCuentasCobrarModificadas['valor'][$contCC] - $debeVector[$i2] ;
											$debeVector[$i2]=0;
									}else{
										$pago_actual =  $listadoCuentasCobrarModificadas['valor'][$contCC];
										$saldo_actual = 0 ;
											$debeVector[$i2]=round( $debeVector[$i2]-$pago_actual,2);
									}

									 $sqlDetalleAbono ="INSERT INTO `detalle_cuentas_por_cobrar`(`valor`, `fecha_pago`, `id_cuenta_por_cobrar`, `id_forma_pago`, `banco_referencia`, `documento_numero`,id_plan_cuenta,saldo,numero_asiento) VALUES ('".$pago_actual."','".$fecha_pago."','".$id_cc_actual."','".$listadoFP[$i2]."','".$txtBancoReferencia."','".$txtDocumentoNumero."','".$idPlanCuentas[$i2]."','".$saldo_actual."','".$numero_asiento."')";
									// 			$sqlDetalleAbono = " INSERT INTO `detalle_cuentas_por_cobrar`( `valor`, `fecha_pago`, `id_cuenta_por_cobrar`) VALUES ('".$facturas_x_pagar[$j][4]."','".$fecha_pago."','".$txtIdCuentaCobrar."')";
												$resultDetalleAbono= mysql_query($sqlDetalleAbono);
												$response['consultas'][] = '3=>'.$sqlDetalleAbono  ;
												$response['idDetalles'][] = mysql_insert_id();
												$response['pago'][] =$pago_actual;
                                        $listadoCuentasCobrarModificadas['valor'][$contCC]=$saldo_actual;
										$response['debeVector'][] =$debeVector[$i2];
										if($saldo_actual==0){
										    $contCC++;
										}
													
								}
							}
						}
					}

				if($resp && $respCuentaCobrar){
					$response['mensajesf'] ="Registro insertado correctamente"  ;
				  
				}else{
					$response['mensajesf'] ="Error al guarda los datos: problemas en la consulta"  ;
				}
	
			}else{
				$response['mensajesf'] ="Fallo en el envio del Formulario: No hay datos"  ;
			}
	
		}catch (Exception $e) {
			$response['mensajesf'] ="Error".$e  ;
	
		}
		$response['tipo'] =$tipo_persona ;
	echo json_encode($response);
	}


if($accion == "1"){
	$response = array();
		// GUARDAR PAGO CUENTAS POR COBRAR  PAGINA: 
		try
		{
			$txtIdCliente = ($_POST['txtIdCliente']);
			$txtIdPlanCuentas = ($_POST['txtIdPlanCuentas']);
			$txtFechaPago = ($_POST['txtFechaPago']);         
			$hora = date("H:i");
			$cmbFormaPagoFP = ($_POST['cmbFormaPagoFP']); 
			$idFormaPago = explode("*", $cmbFormaPagoFP);
			$txtBancoReferencia = ($_POST['txtBancoReferencia']);
			$txtDocumentoNumero = ($_POST['txtDocumentoNumero']);
			$txtValor = round(floatval($_POST['txtPagoFP']),2);
			$txtEfectivo = ($_POST['txtEfectivo']);
			$txtSaldo= ($_POST['txtSaldo']);
			$txtIdCuentaCobrar = ($_POST['txtIdCuentaCobrar']);
			$txtDeudaTotal = ($_POST['txtDeudaTotal']);		
			$txtDeudor = ($_POST['txtDeudor']);
			$fecha_pago = date("Y-m-d h:i:s");
			$tipo_persona= $_POST['switch-four'];
			$valor_x_cancelar=0;
			$valor_x_cancelar=$txtValor;
			$response['totalAbonado'] =$txtValor ;	

			$sumaSaldosCuentas =0;
			 if ($tipoPago == 1)
			{	
				$sumaSaldosCuentas =$txtDeudaTotal;
				$valor_x_cancelar=$txtValor;
			}
			else
			{
				$arregloCHK=$_POST['checkCobrar'];
				$num=count($arregloCHK);
				$numero_cancelaciones=$num;	
				$n=0;
				for ($n=0; $n<=$num; $n++)
				{
					$id_pagos_cuotas = $arregloCHK[$n];
					$facturas_x_cobrar[$n][1]=$arregloCHK[$n];	
					$sqlBuscarVenta = "SELECT `id_cuenta_por_cobrar`,  `id_venta`,saldo FROM `cuentas_por_cobrar` WHERE id_cuenta_por_cobrar='".$arregloCHK[$n]."'";
					$resultBuscarVenta = mysql_query($sqlBuscarVenta);
					$numFilasVenta= mysql_num_rows($resultBuscarVenta);
					if($numFilasVenta>0){
						while($rowBV = mysql_fetch_array($resultBuscarVenta)){
							
							$sumaSaldosCuentas =$sumaSaldosCuentas + $rowBV['saldo'] ;

							if( $rowBV['id_venta']==''){
								$id_venta = '0';
							}else{
								$id_venta = $rowBV['id_venta'];
							}
						}
					}
				}
				$valor_x_cancelar=$txtValor;
			}
			
			
			if($txtIdCliente != "" && $txtValor != "") 
			{	
			
			if ($tipoPago == 1)
			{	
				  
				 if($tipo_persona==2){
						 $sql = "SELECT
					cuentas_por_cobrar.`id_cuenta_por_cobrar` AS ctas_x_cobrar_id_cuenta_por_cobrar,
					cuentas_por_cobrar.`saldo` AS cuentas_por_cobrar_saldo,
					cuentas_por_cobrar.`id_cliente` AS cuentas_por_cobrar_id_cliente,
					cuentas_por_cobrar.`id_empresa` AS cuentas_por_cobrar_id_empresa,
					cuentas_por_cobrar.`id_venta` AS cuentas_por_cobrar_id_venta,
					cuentas_por_cobrar.`estado` AS cuentas_por_cobrar_estado
				FROM
					`clientes` clientes INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar 
					ON clientes.`id_cliente` = cuentas_por_cobrar.`id_cliente`
					WHERE  cuentas_por_cobrar.`id_empresa`= '".$sesion_id_empresa."' and 
					cuentas_por_cobrar.`id_cliente`='".$txtIdCliente."' and saldo>0 
				  order by cuentas_por_cobrar.`fecha_vencimiento`"; 
				 }else if($tipo_persona==1){
						 $sql = "SELECT
					cuentas_por_cobrar.`id_cuenta_por_cobrar` AS ctas_x_cobrar_id_cuenta_por_cobrar,
					cuentas_por_cobrar.`saldo` AS cuentas_por_cobrar_saldo,
					cuentas_por_cobrar.`id_proveedor` AS cuentas_por_cobrar_id_cliente,
					cuentas_por_cobrar.`id_venta` AS cuentas_por_cobrar_id_venta,
					cuentas_por_cobrar.`id_empresa` AS cuentas_por_cobrar_id_empresa,
					cuentas_por_cobrar.`estado` AS cuentas_por_cobrar_estado
				FROM
					  `proveedores` proveedores  INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar 
					ON proveedores.`id_proveedor` = cuentas_por_cobrar.`id_proveedor`
					WHERE  cuentas_por_cobrar.`id_empresa`= '".$sesion_id_empresa."' and 
					cuentas_por_cobrar.`id_proveedor`='".$txtIdCliente."' and saldo>0 
				  order by cuentas_por_cobrar.`fecha_vencimiento`"; 
				 }else if($tipo_persona==3){
						 $sql = "SELECT
					cuentas_por_cobrar.`id_cuenta_por_cobrar` AS ctas_x_cobrar_id_cuenta_por_cobrar,
					cuentas_por_cobrar.`saldo` AS cuentas_por_cobrar_saldo,
					cuentas_por_cobrar.`id_proveedor` AS cuentas_por_cobrar_id_cliente,
					cuentas_por_cobrar.`id_venta` AS cuentas_por_cobrar_id_venta,
					cuentas_por_cobrar.`id_empresa` AS cuentas_por_cobrar_id_empresa,
					cuentas_por_cobrar.`estado` AS cuentas_por_cobrar_estado
				FROM
					   `leads` leads INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar 
					ON leads.`id` = cuentas_por_cobrar.`id_lead` 
					WHERE  cuentas_por_cobrar.`id_empresa`= '".$sesion_id_empresa."' and 
					cuentas_por_cobrar.`id_lead`='".$txtIdCliente."' and saldo>0 
				  order by cuentas_por_cobrar.`fecha_vencimiento`"; 
				 }else if($tipo_persona==4){
						 $sql = "SELECT
					cuentas_por_cobrar.`id_cuenta_por_cobrar` AS ctas_x_cobrar_id_cuenta_por_cobrar,
					cuentas_por_cobrar.`saldo` AS cuentas_por_cobrar_saldo,
					cuentas_por_cobrar.`id_proveedor` AS cuentas_por_cobrar_id_cliente,
					cuentas_por_cobrar.`id_venta` AS cuentas_por_cobrar_id_venta,
					cuentas_por_cobrar.`id_empresa` AS cuentas_por_cobrar_id_empresa,
					cuentas_por_cobrar.`estado` AS cuentas_por_cobrar_estado
				FROM
					  `empleados` empleados INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar 
					ON empleados.`id_empleado` = cuentas_por_cobrar.`id_empleado` 
					WHERE  cuentas_por_cobrar.`id_empresa`= '".$sesion_id_empresa."' and 
					cuentas_por_cobrar.`id_empleado`='".$txtIdCliente."' and saldo>0 
				  order by cuentas_por_cobrar.`fecha_vencimiento`"; 
				 }else if($tipo_persona==5){
						 $sql = "SELECT
                    cuentas_por_cobrar.`id_cuenta_por_cobrar` AS ctas_x_cobrar_id_cuenta_por_cobrar,
                    cuentas_por_cobrar.`saldo` AS cuentas_por_cobrar_saldo,
                    cuentas_por_cobrar.`id_inmueble` AS cuentas_por_cobrar_id_cliente,
                    cuentas_por_cobrar.`id_venta` AS cuentas_por_cobrar_id_venta,
                    cuentas_por_cobrar.`id_empresa` AS cuentas_por_cobrar_id_empresa,
                    cuentas_por_cobrar.`estado` AS cuentas_por_cobrar_estado
                FROM
                    `inmueble` inmueble
                INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar ON
                    inmueble.`id_inmueble` = cuentas_por_cobrar.`id_inmueble`
                WHERE
                    cuentas_por_cobrar.`id_empresa` = '".$sesion_id_empresa."' AND cuentas_por_cobrar.`id_inmueble` = '".$txtIdCliente."' AND saldo > 0
                ORDER BY
                    cuentas_por_cobrar.`fecha_vencimiento`"; 
				 }else if($tipo_persona==6){
						 $sql = "SELECT
                    cuentas_por_cobrar.`id_cuenta_por_cobrar` AS ctas_x_cobrar_id_cuenta_por_cobrar,
                    cuentas_por_cobrar.`saldo` AS cuentas_por_cobrar_saldo,
                    cuentas_por_cobrar.`id_estudiante` AS cuentas_por_cobrar_id_cliente,
                    cuentas_por_cobrar.`id_venta` AS cuentas_por_cobrar_id_venta,
                    cuentas_por_cobrar.`id_empresa` AS cuentas_por_cobrar_id_empresa,
                    cuentas_por_cobrar.`estado` AS cuentas_por_cobrar_estado
                FROM
                    `estudiante` estudiante
                INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar ON
                    estudiante.`id_estudiante` = cuentas_por_cobrar.`id_estudiante`
                WHERE
                    cuentas_por_cobrar.`id_empresa` = '".$sesion_id_empresa."' AND cuentas_por_cobrar.`id_estudiante` = '".$txtIdCliente."' AND saldo > 0
                ORDER BY
                    cuentas_por_cobrar.`fecha_vencimiento`"; 
				 }
	
				 $response['consultas'][] = $sql;
				$result = mysql_query($sql) or die(mysql_error());
				$i=1;
				$valor_x_cancelarx=$valor_x_cancelar;
				while ($row = mysql_fetch_array($result))
				{ 
					$id_venta=$row['cuentas_por_cobrar_id_venta'];
					$saldo_x_factura=$row['cuentas_por_cobrar_saldo'];				
					$facturas_x_pagar[$i][1]=$row['ctas_x_cobrar_id_cuenta_por_cobrar'];
					$facturas_x_pagar[$i][2]=$row['cuentas_por_cobrar_saldo'];				
					$facturas_x_pagar[$i][3]=$row['cuentas_por_cobrar_id_cliente'];
			//		echo "<br/>"."i=".$i."   id cobros=".$facturas_x_pagar[$i][1];
					if ($saldo_x_factura<$valor_x_cancelarx)
					{
						$facturas_x_pagar[$i][4]=$saldo_x_factura;
					}
					else
					{
						$facturas_x_pagar[$i][4]=$valor_x_cancelarx;
					}
					
					$valor_x_cancelarx=$valor_x_cancelarx-$facturas_x_pagar[$i][4];
					//echo "Pendiente".$valor_x_cancelarx;
					if ($valor_x_cancelarx==0)
					{
						break;
					}
					$i=$i+1;
				}	
	
				$numero_cancelaciones=$i;
			
			
			}else{
					$valor_x_cancelarx=$txtValor;
			}
	
				//************************************ GUARDAR ASIENTO CONTABLE ***************************************/
				//************************************ LIBRO DIARIO ***************************************/
				//permite sacar el numero_asiento de libro_diario
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
				catch(Exception $ex) {
					$response['error'][] = "Error en el id max libro_diario:".$ex;
					}
						  
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
	
				}
				catch(Exception $ex) 
				{ $response['error'][] = "Error en el id max libro_diario:".$ex; }
						
				 
			$tipo_comprobante='Diario';
	//$respCuentaCobrar = mysql_query($sqlCuentaCobrar);            
	 
					// FIN ACTUALIZA LA TABLA CUENTAS POR COBRAR
				
	
					// SACA EL NUMERO DE COMPROBANTE DEPENDIENDO DEL TIPO DE COMPROBANTE
					try{
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
	
					}catch (Exception $e) {
						$response['error'][] = "Error max_numero_comprobante:".$ex;
					   
					}
	
					//SACA EL ID MAX DE COMPROBANTES
					try{
						$sqlCM="Select max(id_comprobante) From comprobantes;";
						$resultCM=mysql_query($sqlCM) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
						$id_comprobante=0;
						while($rowCM=mysql_fetch_array($resultCM))//permite ir de fila en fila de la tabla
						{
							$id_comprobante=$rowCM['max(id_comprobante)'];
						}
						$id_comprobante++;
	
					}catch(Exception $ex) { 
						$response['error'][] = "Error en el id max libro_diario:".$ex;
					}
	
					$fecha= $txtFechaPago." ".$hora;
					$descripcion = $txtDeudor." Cobros de las Cuentas por Cobrar Generado Automaticamente";
					
					$debe = $txtValor;
					$haber1 = $txtValor;
					//$haber2 = $total_iva;
					$total_debe = $debe;
					$total_haber = $haber1 ;
	
					//GUARDA EN  COMPROBANTES
					$sqlC = "insert into comprobantes (id_comprobante, tipo_comprobante, numero_comprobante, id_empresa) values 
					('".$id_comprobante."','".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
					
					$respC = mysql_query($sqlC);
					$response['consultas'][] = $sqlC;
					//GUARDA EN EL LIBRO DIARIO
					$sqlLD = "insert into libro_diario (id_libro_diario, id_periodo_contable, numero_asiento, fecha, total_debe, total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante) 
					values ('".$id_libro_diario."','".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha."','".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."', '".$id_comprobante."')";
			  
				 //  echo "</br>".$sqlLD; 
			  
					$resp = mysql_query($sqlLD);
					$response['consultas'][] = $sqlLD ;
					
					//************************************    GUARDA EN EL DETALLE LIBRO DIARIO      **********************
											
				//    $idPlanCuentas[1] = $formas_pago_id_plan_cuenta;
					$idPlanCuentas[1] = $txtIdPlanCuentas;    
				//$idPlanCuentas[2] = $id_plan_cuenta_credito;    
					//$idPlanCuentas[3] = $impuestos_id_plan_cuenta;
	
					$debeVector[1] = $txtValor;
					$haberVector[1] = 0;            
				
				$lin_diario=1;	
				$txtContadorFilas=8;
				for($i=1; $i<=$txtContadorFilas; $i++)
				{
				//$bancos[$lin_diario]='';
					if ($_POST['txtCuentaP'.$i] >=1)
					{   // verifica si en el campo esta agregada una cuenta, // permite sacar el id maximo de detalle_libro_diario
						$lin_diario=$lin_diario+1;
						$idformaPago[$lin_diario]=$_POST['txtCodigoS'.$i];
						$txtTipoP[$lin_diario]=$_POST['txtTipo1'.$i];
						$txtPorcentajeS[$lin_diario]=$_POST['txtPorcentajeS'.$i];
						$txtNumeroRetencionS[$lin_diario]=isset($_POST['txtNumeroRetencionS'.$i])?$_POST['txtNumeroRetencionS'.$i]:'';
						$listadoFP[$lin_diario]=$_POST['formaPagoId'.$i];
						
						$txtAutorizacion[$lin_diario]=isset($_POST['txtAutorizacion'.$i])?$_POST['txtAutorizacion'.$i]:'';
						$idPlanCuentas[$lin_diario]=$_POST['txtCodigoS'.$i];
						
						
						$debeVector[$lin_diario]=round(floatval($_POST['txtValorS'.$i]),2);
						$haberVector[$lin_diario]='0';
						
						
						$fechas_pag_ven[$lin_diario]=date("Ymd"); 
						$nro_cpte[$lin_diario]=$_POST['nrocpteC'.$i];
						$bancos[$lin_diario]='';
						if (strtolower($_POST['txtTipoP'.$i]) =='cheque' OR
						   strtolower($_POST['txtTipoP'.$i]) =='deposito' or
						   strtolower($_POST['txtTipoP'.$i])=='transferencia')
						{
						  $bancos[$lin_diario]=strtolower($_POST['txtTipoP'.$i]);
					 //      echo $bancos[$lin_diario];
						}
					}	
				}

	
		   $sql="Select id_plan_cuenta From formas_pago where id_tipo_movimiento='4' and id_empresa='".$sesion_id_empresa."';";
			$resultado=mysql_query($sql) ;
	
			while($row=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
			{
			$formas_pago_id_plan_cuenta_asiento=$row['id_plan_cuenta'];
			}
		//  		echo "FORMA DE PAGO ==".$formas_pago_id_plan_cuenta_asiento;
	
	//			$sqlDLD2 = "insert into detalle_libro_diario ( id_libro_diario, id_plan_cuenta,
	//					debe, haber, id_periodo_contable) values ('".$id_libro_diario."','".$formas_pago_id_plan_cuenta."','".$txtValor."','".'0'."','".$sesion_id_periodo_contable."');";
		$sqlDLD2 = "insert into detalle_libro_diario ( id_libro_diario, id_plan_cuenta,	debe, haber, id_periodo_contable) 
		values ('".$id_libro_diario."','".$formas_pago_id_plan_cuenta_asiento."','".'0'."','".$txtValor."','".$sesion_id_periodo_contable."');";      
					// 		echo "<br>"." detalle libro diario ".$sqlDLD2."</BR>";
			$resp2 = mysql_query($sqlDLD2) ;
			$response['consultas'][] = $sqlDLD2  ;
			
				for($i=2; $i<=$lin_diario; $i++)
				{
					if ($idPlanCuentas[$i] !="")
					{
	
	
						//GUARDA EN EL DETALLE LIBRO DIARIO
		  $sqlDLD = "insert into detalle_libro_diario (id_libro_diario,       id_plan_cuenta,debe,haber,id_periodo_contable) values ('".$id_libro_diario."','".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."','".$sesion_id_periodo_contable."');";                    
					// echo "<br>"." detalle libro diario 2 ".$sqlDLD."</BR>"; 
						$resp2 = mysql_query($sqlDLD) ;
						$response['consultas'][] =  $sqlDLD ;
						// CONSULTAS PARA GENERAR LA MAYORIZACION
						$sql5 = "SELECT * FROM mayorizacion WHERE id_plan_cuenta ='".$idPlanCuentas[$i]."' AND id_periodo_contable = '".$sesion_id_periodo_contable."';";
						$result5=mysql_query($sql5);
						while($row5=mysql_fetch_array($result5))//permite ir de fila en fila de la tabla
							{
								$id_mayorizacion=$row5['id_mayorizacion'];
							}
						 
	//echo "<br>"." sql5  ".$sql5;                         
						$numero = mysql_num_rows($result5); // obtenemos el número de filas
						if($numero > 0){
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
	
								$sql6 = "insert into mayorizacion (id_mayorizacion, id_plan_cuenta, id_periodo_contable) values 
								('".$id_mayorizacion."','".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
								$result6=mysql_query($sql6);
								$response['consultas'][] =  $sql6  ;
	//echo "<br>"." mayorizacion  ".$sql6;                     
	
							}
							catch(Exception $ex) { 
								$response['error'][] = " Error en la insercion de la tabla mayorizacion:".$ex ;
							 }
	
						}
					// detalle_cuentas_cobrar
					//  $sqlDetalleAbono ="INSERT INTO `detalle_cuentas_por_cobrar`(`valor`, `fecha_pago`, `id_cuenta_por_cobrar`, `id_forma_pago`, `banco_referencia`, `documento_numero`, `saldo`,id_plan_cuenta) VALUES ('".$debeVector[$i]."','".$fecha_pago."','".$txtIdCuentaCobrar."','".$numero_asiento."','".$txtBancoReferencia."','".$txtDocumentoNumero."','".$saldo."','".$idPlanCuentas[$i]."')";
					// 			$resultDetalleAbono= mysql_query($sqlDetalleAbono);
					// 			$response['idDetalles'][] = mysql_insert_id();
					}
				}    
	
		for($i=1; $i<=$lin_diario; $i++)				
				{
					$b=$i+1;
					if($idformaPago[$b] >=1)
					{	
						$id_venta = (trim($id_venta)!='')?$id_venta:0;
						 $sqlforma = "INSERT INTO `cobrospagos`( `id_forma`, `documento`, `id_factura`,id_empresa,valor,tipo,porcentaje,numero_retencion,autorizacion) 
						VALUES     ('".$idformaPago[$b]."','0','".$id_venta."','".$sesion_id_empresa."','".$debeVector[$b]."','".$txtTipoP[$b]."', NULL, '".$txtNumeroRetencionS[$b]."','".$txtAutorizacion[$b]."');";
						// echo $sqlforma;
						
					
						$respForma = mysql_query($sqlforma) or 	die('<div class="transparent_ajax_error"><p>Error 11 : '.mysql_error().' </p></div>  ');
						if($respForma){
							if($txtTipoP[$b]==1 ){
							 $identificador="01";
							}
							else if($txtTipoP[$b]==2){
							  $ident=1;
								$identificador="02";
							}else if($txtTipoP[$b]==16 && $txtTipoP[$b]==17 ){
							//   $ident=1;
								$identificador="19";
							}
							else{
								$identificador="03";
							}
							   if($ident==1){
								   $identificador="02";
							   } 
								 $sql3="update ventas set id_forma_pago='".$identificador."' where id_venta='".$id_venta."' ";
								$resp3 = mysql_query($sql3) or die(mysql_error());
								
							
						}
						
						
					}
				}
				$listadoCuentasCobrarModificadas = array();
				if ($tipoPago == 1)
				{				
					//echo "numero_cancelaciones".$numero_cancelaciones;
					$j=1;
					for ($j=1; $j<=$numero_cancelaciones; $j++)
					{	
				//	echo "valor a cancelar".$facturas_x_pagar[$j][4];
						if ($facturas_x_pagar[$j][4]>0) 
						{
							$txtIdCuentaCobrar=$facturas_x_pagar[$j][1];
							$listadoCuentasCobrarModificadas['idCuentaPagar'][]=$txtIdCuentaCobrar;
							$saldo = $facturas_x_pagar[$j][2]-$facturas_x_pagar[$j][4];
						
				//		echo "<br/>"."Saldo".$saldo;
							if($saldo == 0){
								$estadoCC = "Canceladas";
							}
							else
							{
							$estadoCC = "Pendientes";
							}
				// 	, numero_asiento='".$numero_asiento."'
							$sqlCuentaCobrar = "update cuentas_por_cobrar set saldo='".$saldo."', estado='".$estadoCC."', fecha_pago='".$fecha_pago."', banco_referencia='".$txtBancoReferencia."', 
							documento_numero='".$txtDocumentoNumero."'
							where id_cuenta_por_cobrar='".$txtIdCuentaCobrar."';";
							$respCuentaCobrar = mysql_query($sqlCuentaCobrar);
							$response['consultas'][] = $sqlCuentaCobrar  ;

							$listadoCuentasCobrarModificadas['valor'][]=round(floatval($facturas_x_pagar[$j][4]),2);
							$listadoCuentasCobrarModificadas['saldo'][]=$saldo ;
							if($sesion_id_empresa==41){
							    $hoy = date('Y-m-d');
							 //  $response['consultasmem'][] = pagar_membresia( $txtIdCuentaCobrar, $facturas_x_pagar[$j][4], $hoy);
							}
                            
						}
					}			
				}
				else
				{

	// 			echo "VALOR==>".	$valor_x_cancelarx=$valor_x_cancelar;
				
					// echo "NUMERO CANCELACIONES 222 ==> ".$numero_cancelaciones."</br>";
					$j=0;
					for ($j=0; $j<=$numero_cancelaciones; $j++)
					{	
	// 			echo "valor a cancelar".$facturas_x_pagar[$j][1]."</br>";
				
						if ($facturas_x_cobrar[$j][1]>0) 
						{
							
							$txtIdCuentaCobrar=$facturas_x_cobrar[$j][1];
							$listadoCuentasCobrarModificadas['idCuentaPagar'][]=$txtIdCuentaCobrar;
							$sql="Select saldo from cuentas_por_cobrar 
							where id_cuenta_por_cobrar='".$txtIdCuentaCobrar."';";
					///	echo $sql;
							$result = mysql_query($sql) or die(mysql_error());
							$saldo_x_factura=0;
							while ($row = mysql_fetch_array($result))
							{ 
								
								$saldo_x_factura=round($row['saldo'],2);				
							}
							$listadoCuentasCobrarModificadas['valor'][]=round(floatval($saldo_x_factura),2);

							if ($saldo_x_factura<$valor_x_cancelarx)
							{
								$facturas_x_cobrar[$j][2]=$saldo_x_factura;
								$estadoCC = "Canceladas";
								$saldo=0;
						//		echo "op1";
						// $listadoCuentasCobrarModificadas['valor'][]=round(floatval($saldo_x_factura),2);
						$listadoCuentasCobrarModificadas['saldo'][]=0; 
							}
							else
							{
								$saldo=$saldo_x_factura-$valor_x_cancelarx;
								$estadoCC = "Pendientes";
								$facturas_x_cobrar[$j][2]=$valor_x_cancelarx;
						//	echo "op2";
						// $listadoCuentasCobrarModificadas['valor'][]=round(floatval($valor_x_cancelarx),2);
						$listadoCuentasCobrarModificadas['saldo'][]=floatval($saldo);
							}	
							
							$valor_x_cancelarx=$valor_x_cancelarx-$facturas_x_cobrar[$j][2];
								// numero_asiento='".$numero_asiento."',
							$sqlCuentaCobrar = "update cuentas_por_cobrar set saldo='".$saldo."', estado='".$estadoCC."', fecha_pago='".$fecha_pago."', 
							 id_forma_pago='0', banco_referencia='".$txtBancoReferencia."', 
								documento_numero='".$txtDocumentoNumero."', id_plan_cuenta='0' 
								where id_cuenta_por_cobrar='".$txtIdCuentaCobrar."';";
							$respCuentaCobrar = mysql_query($sqlCuentaCobrar);
							if($respCuentaCobrar){
                                	if($sesion_id_empresa==41){
							    $hoy = date('Y-m-d');
							 //  $response['consultasmem'][] = pagar_membresia( $txtIdCuentaCobrar, $facturas_x_cobrar[$j][2], $hoy);
							}
							}
							$response['consultas'][] = $sqlCuentaCobrar  ;
						//	echo $sqlCuentaCobrar;
						}
					};
				}
				

				
				// var_dump($listadoCuentasCobrarModificadas);
				// echo '<b>';
				// echo '<b>';
				// echo '<b>';
				// var_dump($debeVector);
				// exit;
				$response['listadoCuentasCobrar'][] = $listadoCuentasCobrarModificadas ;
				$response['vectores'][] = $debeVector ;
				$cantidadCuentas = COUNT( $listadoCuentasCobrarModificadas['idCuentaPagar']) -1;
				$response['cantidadCuentas'] = $cantidadCuentas ;
				$contCC=0;
				
				$saldo_cc_actual=0;// es el valor que cobro por cada forma de pago 

					for($i2=2; $i2<=$lin_diario; $i2++)
					{
					

						if ($idPlanCuentas[$i2] !="")
						{
							$id_cc_actual = $listadoCuentasCobrarModificadas['idCuentaPagar'][$contCC];
							
							$response['comparacion'][] ='|'.$debeVector[$i2].'---'.$listadoCuentasCobrarModificadas['valor'][$contCC].'|';

							if(round($debeVector[$i2],2) < round($listadoCuentasCobrarModificadas['valor'][$contCC],2) ){
								// 0,7 < 1
								$saldo_cc_actual= $listadoCuentasCobrarModificadas['valor'][$contCC] - $debeVector[$i2] ;
								$response['saldo_cc_actual'][]=$saldo_cc_actual;
							
								$sqlDetalleAbono ="INSERT INTO `detalle_cuentas_por_cobrar`(`valor`, `fecha_pago`, `id_cuenta_por_cobrar`, `id_forma_pago`, `banco_referencia`, `documento_numero`,id_plan_cuenta,saldo,numero_asiento) VALUES ('".$debeVector[$i2]."','".$fecha_pago."','".$id_cc_actual."','".$listadoFP[$i2]."','".$txtBancoReferencia."','".$txtDocumentoNumero."','".$idPlanCuentas[$i2]."','".$saldo_cc_actual."','".$numero_asiento."')";
								// 			$sqlDetalleAbono = " INSERT INTO `detalle_cuentas_por_cobrar`( `valor`, `fecha_pago`, `id_cuenta_por_cobrar`) VALUES ('".$facturas_x_pagar[$j][4]."','".$fecha_pago."','".$txtIdCuentaCobrar."')";
											$resultDetalleAbono= mysql_query($sqlDetalleAbono);
											$response['consultas'][] = '1=>'.$sqlDetalleAbono  ;
											$response['idDetalles'][] = mysql_insert_id();
											$response['pago'][] =$debeVector[$i2];

											$listadoCuentasCobrarModificadas['valor'][$contCC] = $saldo_cc_actual;
											continue;	
							}

							if(round($debeVector[$i2],2) == round($listadoCuentasCobrarModificadas['valor'][$contCC],2)){
								// 0,7 == 0.7
								$saldoFinal = ($contCC==$cantidadCuentas)?$_POST['txtCambioFP']:0;

								$sqlDetalleAbono ="INSERT INTO `detalle_cuentas_por_cobrar`(`valor`, `fecha_pago`, `id_cuenta_por_cobrar`, `id_forma_pago`, `banco_referencia`, `documento_numero`,id_plan_cuenta,saldo,numero_asiento) VALUES ('".$debeVector[$i2]."','".$fecha_pago."','".$id_cc_actual."','".$listadoFP[$i2]."','".$txtBancoReferencia."','".$txtDocumentoNumero."','".$idPlanCuentas[$i2]."','".$saldoFinal."','".$numero_asiento."')";
								// 			$sqlDetalleAbono = " INSERT INTO `detalle_cuentas_por_cobrar`( `valor`, `fecha_pago`, `id_cuenta_por_cobrar`) VALUES ('".$facturas_x_pagar[$j][4]."','".$fecha_pago."','".$txtIdCuentaCobrar."')";
											$resultDetalleAbono= mysql_query($sqlDetalleAbono);
											$response['consultas'][] = '2=>'.$sqlDetalleAbono  ;
											$response['idDetalles'][] = mysql_insert_id();
											$response['pago'][] =$debeVector[$i2];
											$contCC++;
											continue;	
							}
							
							if(round($debeVector[$i2],2) > round($listadoCuentasCobrarModificadas['valor'][$contCC],2) ){
								// 1 == 0.7
								while($debeVector[$i2] > 0){
									$id_cc_actual = $listadoCuentasCobrarModificadas['idCuentaPagar'][$contCC];

									if( $debeVector[$i2] <= $listadoCuentasCobrarModificadas['valor'][$contCC] ){
										$pago_actual= $debeVector[$i2];
										$saldo_actual =  $listadoCuentasCobrarModificadas['valor'][$contCC] - $debeVector[$i2] ;
											$debeVector[$i2]=0;
									}else{
										$pago_actual =  $listadoCuentasCobrarModificadas['valor'][$contCC];
										$saldo_actual = 0 ;
											$debeVector[$i2]=round( $debeVector[$i2]-$pago_actual,2);
									}

									 $sqlDetalleAbono ="INSERT INTO `detalle_cuentas_por_cobrar`(`valor`, `fecha_pago`, `id_cuenta_por_cobrar`, `id_forma_pago`, `banco_referencia`, `documento_numero`,id_plan_cuenta,saldo,numero_asiento) VALUES ('".$pago_actual."','".$fecha_pago."','".$id_cc_actual."','".$listadoFP[$i2]."','".$txtBancoReferencia."','".$txtDocumentoNumero."','".$idPlanCuentas[$i2]."','".$saldo_actual."','".$numero_asiento."')";
									// 			$sqlDetalleAbono = " INSERT INTO `detalle_cuentas_por_cobrar`( `valor`, `fecha_pago`, `id_cuenta_por_cobrar`) VALUES ('".$facturas_x_pagar[$j][4]."','".$fecha_pago."','".$txtIdCuentaCobrar."')";
												$resultDetalleAbono= mysql_query($sqlDetalleAbono);
												$response['consultas'][] = '3=>'.$sqlDetalleAbono  ;
												$response['idDetalles'][] = mysql_insert_id();
												$response['pago'][] =$pago_actual;
                                        $listadoCuentasCobrarModificadas['valor'][$contCC]=$saldo_actual;
										$response['debeVector'][] =$debeVector[$i2];
										if($saldo_actual==0){
										    $contCC++;
										}
													
								}
							}
						}
					}

				if($resp && $respCuentaCobrar){
					$response['mensajesf'] ="Registro insertado correctamente"  ;
				  
				}else{
					$response['mensajesf'] ="Error al guarda los datos: problemas en la consulta"  ;
				}
	
			}else{
				$response['mensajesf'] ="Fallo en el envio del Formulario: No hay datos"  ;
			}
	
		}catch (Exception $e) {
			$response['mensajesf'] ="Error".$e  ;
	
		}
		$response['tipo'] =$tipo_persona ;
	echo json_encode($response);
	}


     if($accion == 2){
        
    

    }

    if($accion == "3")
	{
    // ELIMINA CARGOS PAGINA: cargos.php
		try
		{
		//	echo "codigo";
			//echo $_POST['id_cuenta_por_cobrar'];
			//id_cuenta_por_cobrar
			if(isset ($_POST['id_cuenta_por_cobrar'])){
				$id_cta_x_cobrar = $_POST['id_cuenta_por_cobrar'];
				$sql4 = "delete from cuentas_por_cobrar where id_cuenta_por_cobrar='".$id_cta_x_cobrar."';  ";
				//echo "iii";
				//echo $sql4;
				$resp4 = mysql_query($sql4);
				if(!mysql_query($sql4)){
					echo "Ocurrio un error\n$sql4";
				}
				else
				{
					echo "La cuenta por cobrar ha sido Eliminado."; }
				}else
					{
					?> <div class="transparent_ajax_error"><p>Error: No hay datos</p></div> <?php
					}
		}
		catch (Exception $e)
		{
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
   
		}
		

    }

if ($accion=="6")
	{
		$cmbTipoDocumentoFVC='';
		$cmbTipoDocumentoFVC="Compra#";		
		$numero_factura=$_POST['txtComprobante'];
		$fecha_compra=$_POST['textFecha'];
		$total=$_POST['txtTotal'];
		$id_cliente=$_POST['cmbCliente'];
		
		$txtFechaVencimiento=$_POST['txtFechaVencimiento'];
		
		$sqlp="Select * From clientes where id_empresa='".$sesion_id_empresa."' and id_cliente='".$id_cliente."'  ;";

        $resultp=mysql_query($sqlp);
        $txtNombreFVC="";
		while($rowp=mysql_fetch_array($resultp))//permite ir de fila en fila de la tabla
        {
			$txtNombreFVC=$rowp['apellido'].' '.$rowp['nombre'];
		}
	
		$numero_compra=$_POST['txtComprobante'];
		$formas_pago_id_plan_cuenta=0;

		$id_venta=NULL;
		$txtCuotasTP=1;
		
	
				if ($_POST['txtTipo1'.$i]=='4')
						{
							$formas_pago_id_plan_cuenta=$_POST['txtCodigoS'.$i];		
						}
						
						
						
	    
	
		if($total > 0 and $txtCuotasTP>0)
		{            
			$cuotas = round(($total / $txtCuotasTP),2); 
			//	$cuotas= round($cuotas_x * 100) / 100;
			$aux=round(($cuotas * $txtCuotasTP),2); 
			$dif=round(($total-$aux),2);
			$cuota_final=$cuotas;
			//echo $dif;
			if ($dif != 0)
			{
				$cuota_final=$cuota_final + $dif;
			}
						
			$estadoCC = "Pendientes";                
			for($i=0; $i<$txtCuotasTP; $i++)
			{
				if ($i == $txtCuotasTP-1)
				{
					$cuotax=$cuota_final;
				}
				else
				{
					$cuotax=$cuotas;
				} 
				//	echo $cuotax;
				//$date = date("d-m-Y");
				//Incrementando meses
				//txtFechaS
				//$mod_date = strtotime($txtFechaTP."+ ".$i." months");
				$mod_date = strtotime($txtFechaS."+ ".$i." months");
				//echo $mod_date;

				$fecha_nueva = date("Y-m-d",$mod_date);
	            $fecha_nueva=$txtFechaVencimiento;
					
				//$fecha_nueva = $mod_date;			
				$sqlm2="Select max(id_cuenta_por_cobrar) From cuentas_por_cobrar;";
				$resultm2=mysql_query($sqlm2) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
				$id_cuenta_por_cobrar=0;
				while($rowm2=mysql_fetch_array($resultm2))//permite ir de fila en fila de la tabla
				{
					$id_cuenta_por_cobrar=$rowm2['max(id_cuenta_por_cobrar)'];
				}
				$id_cuenta_por_cobrar++;
										
				$sql3 = "insert into cuentas_por_cobrar (id_cuenta_por_cobrar,        tipo_documento,           numero_factura,	      referencia,          valor,        saldo, 	numero_asiento, fecha_vencimiento, fecha_pago, id_cliente, id_plan_cuenta,	id_empresa, id_venta, estado) ". 
						"values                     ('".$id_cuenta_por_cobrar."','".$cmbTipoDocumentoFVC."','".$numero_factura."',	'".$txtNombreFVC." ','".$cuotax."','".$cuotax."','',             '".$fecha_nueva."', NULL,      '".$id_cliente."', '".$formas_pago_id_plan_cuenta."', 	'".$sesion_id_empresa."', NULL, '".$estadoCC."');";
								
			//echo "<br>"."cuentas por cobrar||".$sql3;						
				$resp3 = mysql_query($sql3) or die('<div class="alert alert-success"><p>Error al guardar en cuentas_por_cobrar: '.mysql_error().' </p></div>  ');
			}
			if($resp3)
			{
				?> <div class='alert alert-success'><p>***Registro cuentas por cobrar guardado correctamente***.</p></div> <?php
			}
		}

	}	
   
?>


