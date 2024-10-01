<?php

require_once('../ver_sesion.php');
//Start session
session_start();

//Include database connection details
require_once('../conexion.php');
$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
$emision_tipoEmision=$_SESSION['emision_tipoEmision'] ;
$emision_codigo = $_SESSION["emision_codigo"];
$establecimiento_codigo = $_SESSION["establecimiento_codigo"];
date_default_timezone_set('America/Guayaquil');

$accion = $_POST['txtAccion'];
$tipoPago = $_POST['tipox'];
//echo "estoy en sql=====";
//echo $tipoPago;
//array $facturas_x_pagar[10,5];

if($accion == "1")
{
     $listado_numeros_facturas='';
         function ceros($valor){
	$s='';
 for($i=1;$i<=9-strlen($valor);$i++)
	 $s.="0";
 return $s.$valor;
}
 function cantidad_num_factura($iV,$pagar){
     $pagar2=floatval($pagar);
     $numeroCompra='';
    $num=count($iV);
	$numero_cancelaciones=$num;		
	$n=0;
	for ($n=0; $n<$num; $n++){
	    $id_pagos_cuotas = $iV[$n];
	     $sqlCuentaCobrar = "SELECT `id_cuenta_por_pagar`, `tipo_documento`, `numero_compra`, `referencia`, `valor`, `saldo`, `numero_asiento`, `fecha_vencimiento`, `fecha_pago`, `id_proveedor`, `id_cliente`, `id_lead`, `id_plan_cuenta`, `id_empresa`, `id_compra`, `estado`, `id_forma_pago`, `banco_referencia`, `documento_numero`, `id_empleado`, `motivoAnticipo`, `tipo_anticipo` FROM `cuentas_por_pagar` WHERE id_cuenta_por_pagar=$id_pagos_cuotas";
	    $resultCuentaCobrar = mysql_query($sqlCuentaCobrar);
        while($rowCta = mysql_fetch_array($resultCuentaCobrar)) { 
            $valor_act =floatval($rowCta['saldo']);
            $id_compra_act =$rowCta['id_compra'];
            if($pagar2>0){
            $pagar2 = $pagar2-$valor_act;
            }else{
                return $numeroCompra;
            }
            
               if($id_compra_act!=''){
	    $sqlCompras = "SELECT numSerie, txtEmision, txtNum FROM compras WHERE id_compra = $id_compra_act;";
        $resultCompras = mysql_query($sqlCompras);
        while($row2 = mysql_fetch_array($resultCompras)) { 
            $numeroFactura= $row2['txtNum'];
            $estCod= $row2['numSerie'];
            $emiCod= $row2['txtEmision'];
            $numeroFactura= ceros($numeroFactura);
            $numeroCompra.=  "(".$estCod."-".$emiCod."-".$numeroFactura.")";
        }
        
	    }// fin if
           
        }

    }// fin for
    return $numeroCompra;
}
    // GUARDAR PAGO CUENTAS POR COBRAR  PAGINA: 
    try
    {
        $response=[];
        $id_compra =  $_POST['id_compra'];
//		echo "estoy en la opcion 1";
        $txtIdProveedor = ($_POST['txtIdProveedor']);
        $txtIdPlanCuentas = ($_POST['txtIdPlanCuentas']);
        $txtFechaPago = ($_POST['txtFechaPago']);         
        $hora = date("H:i");
        $txtBancoReferencia = ($_POST['txtBancoReferencia']);
        $txtDocumentoNumero = ($_POST['txtDocumentoNumero']);
    //    $txtValor = ($_POST['txtSubtotalFVC']);
	    $txtValor = ($_POST['txtPagoFP']);
        $txtEfectivo = ($_POST['txtEfectivo']);
        $txtSaldo= ($_POST['txtSaldo']);
        $txtIdCuentaPagar = ($_POST['txtIdCuentaPagar']);
        $txtDeudaTotal = ($_POST['txtDeudaTotal']);
        $txtDeudor = ($_POST['txtDeudor']);
        $fecha_pago = date("Y-m-d h:i:s");
        $txtFacturaCpra=($_POST['id_compra']);
        $tipo_persona= $_POST['switch-four'];
		$valor_x_cancelar=0;
		$valor_x_cancelar=$txtValor;
		$id_cuenta_pagar_LB=0;
		if ($tipoPago == 1)
		{	
			$valor_x_cancelar=$txtValor;
		}
		else
		{
			$arregloCHK=$_POST['checkCobrar'];
			$listado_numeros_facturas = cantidad_num_factura($arregloCHK,$txtValor);
			$response['arregloCHK'] =$arregloCHK;
			 $id_cuenta_pagar_LB= $arregloCHK[0];
			 $response['listado_numeros_facturas'] =$listado_numeros_facturas;
			$num=count($arregloCHK);
			$numero_cancelaciones=$num;		
		    $n=0;
			for ($n=0; $n<=$num; $n++)
			{
			   
			    $id_pagos_cuotas = $arregloCHK[$n];
				$facturas_x_pagar[$n][1]=$arregloCHK[$n];;	
			}
			$valor_x_cancelar=$txtDeudaTotal;

			$valor_x_cancelar=$txtValor; 
		}

        if($txtIdProveedor != ""  && $valor_x_cancelar != "") 
		{


        if ($tipoPago == 1)
		{
		
		 if($tipo_persona==2){
		     	$sql = "SELECT
			 cuentas_por_pagar.`id_cuenta_por_pagar` AS ctas_x_pagar_id_cuenta_por_pagar,
             cuentas_por_pagar.`saldo` AS cuentas_por_pagar_saldo,
             cuentas_por_pagar.`id_cliente` AS cuentas_por_pagar_id_proveedor,
             cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
             cuentas_por_pagar.`estado` AS cuentas_por_pagar_estado
        FROM
             `cuentas_por_pagar` cuentas_por_pagar 
              WHERE  cuentas_por_pagar.`id_empresa`= '".$sesion_id_empresa."' and 
			  cuentas_por_pagar.`id_cliente`='".$txtIdProveedor."' and saldo>0 
			  order by cuentas_por_pagar.`fecha_vencimiento`"; 
			  
			     
			 }else if($tipo_persona==1){
			     		$sql = "SELECT
			 cuentas_por_pagar.`id_cuenta_por_pagar` AS ctas_x_pagar_id_cuenta_por_pagar,
             cuentas_por_pagar.`saldo` AS cuentas_por_pagar_saldo,
             cuentas_por_pagar.`id_proveedor` AS cuentas_por_pagar_id_proveedor,
             cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
             cuentas_por_pagar.`estado` AS cuentas_por_pagar_estado
        FROM
            	`cuentas_por_pagar` cuentas_por_pagar 
			
              WHERE  cuentas_por_pagar.`id_empresa`= '".$sesion_id_empresa."' and   cuentas_por_pagar.`id_proveedor`='".$txtIdProveedor."' and saldo>0 
			  order by cuentas_por_pagar.`fecha_vencimiento`"; 
			  
			 }else if($tipo_persona==3){
			     	$sql = "SELECT
			 cuentas_por_pagar.`id_cuenta_por_pagar` AS ctas_x_pagar_id_cuenta_por_pagar,
             cuentas_por_pagar.`saldo` AS cuentas_por_pagar_saldo,
             cuentas_por_pagar.`id_lead` AS cuentas_por_pagar_id_proveedor,
             cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
             cuentas_por_pagar.`estado` AS cuentas_por_pagar_estado
        FROM
            	`cuentas_por_pagar` cuentas_por_pagar 
			
              WHERE  cuentas_por_pagar.`id_empresa`= '".$sesion_id_empresa."' and   cuentas_por_pagar.`id_lead`='".$txtIdProveedor."' and saldo>0 
			  order by cuentas_por_pagar.`fecha_vencimiento`";
			 }else if($tipo_persona==4){
			     	$sql = "SELECT
			 cuentas_por_pagar.`id_cuenta_por_pagar` AS ctas_x_pagar_id_cuenta_por_pagar,
             cuentas_por_pagar.`saldo` AS cuentas_por_pagar_saldo,
             cuentas_por_pagar.`id_empleado` AS cuentas_por_pagar_id_proveedor,
             cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
             cuentas_por_pagar.`estado` AS cuentas_por_pagar_estado
        FROM
            	`cuentas_por_pagar` cuentas_por_pagar 
			
              WHERE  cuentas_por_pagar.`id_empresa`= '".$sesion_id_empresa."' and   cuentas_por_pagar.`id_empleado`='".$txtIdProveedor."' and saldo>0 
			  order by cuentas_por_pagar.`fecha_vencimiento`";
			 }

            $response['$sql']=$sql ;
            $result = mysql_query($sql) or die(mysql_error());
            $i=1;

			$valor_x_cancelarx=$valor_x_cancelar;
			while ($row = mysql_fetch_array($result))
			{ 
			     $id_cuenta_pagar_LB= $row['ctas_x_pagar_id_cuenta_por_pagar'];
				$saldo_x_factura=$row['cuentas_por_pagar_saldo'];				
				$facturas_x_pagar[$i][1]=$row['ctas_x_pagar_id_cuenta_por_pagar'];
				$facturas_x_pagar[$i][2]=$row['cuentas_por_pagar_saldo'];				
				$facturas_x_pagar[$i][3]=$row['cuentas_por_pagar_id_proveedor'];
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
			//echo "va a distribuir";
			//echo "I=".$i;
			for ($j=1; $j<=$i; $j++)
			{
			/*	echo "<br/>"."ID cobros=";
				echo $facturas_x_pagar[$j][1];
				echo "saldo";
				echo $facturas_x_pagar[$j][2];
//				echo "Proveedor";
//				echo $facturas_x_pagar[$j][3];
				echo "cancelar";
				echo $facturas_x_pagar[$j][4];
				echo "<br>";
				*/
			}
		}
		
            
             //************************************ GUARDAR ASIENTO CONTABLE ***************************************/
            //****************************************** LIBRO DIARIO ***************************************/
                    
            
            //permite sacar el numero_asiento de libro_diario
            try{
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
            //    echo $sqlMNA;
				$resultMNA=mysql_query($sqlMNA) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
                $numero_asiento=0;
                while($rowMNA=mysql_fetch_array($resultMNA))//permite ir de fila en fila de la tabla
                {
                    $numero_asiento=$rowMNA['max_numero_asiento'];
                }
                $numero_asiento++;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
                      
          //  echo "<br>"."va a grabar asiento";
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

            }catch(Exception $ex) 
			{ ?> <div class="transparent_ajax_error">
			<p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
                          

            
     		$sql="Select cuenta_contable From enlaces_compras where tipo='credito' and id_empresa='".$sesion_id_empresa."';";
		$resultado=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;
//			echo $sql;
		while($row=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
		{
			$formas_pago_id_plan_cuenta=$row['cuenta_contable'];
		}
     	
		  $tipo_comprobante = "Diario"; 



			  
			// ACTUALIZA LA TABLA CUENTAS POR COBRAR
        //    echo "<br>"."actualizar la cuentas_por_pagar";
             

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
                    // Error en algun momento.
                   ?> <div class="transparent_ajax_error"><p>Error max_numero_comprobante: <?php echo "".$e ?></p></div> <?php
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

                }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }

                $fecha= $txtFechaPago." ".$hora;
                
                
                
             //   $descripcion = " Realizado desde Cuentas por Pagar a".$txtDeudor;
try {
    $sqlCompras = "SELECT numSerie, txtEmision, txtNum FROM compras WHERE id_compra = $id_compra;";
    $resultCompras = mysql_query($sqlCompras) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>');

    $comprasData = array();

    while ($rowCompras = mysql_fetch_assoc($resultCompras)) {
        $comprasData[] = $rowCompras;
    }

    // Aquí puedes utilizar $comprasData para acceder a los valores de txtnum, txtemision y txtserie
    // por ejemplo, $comprasData[0]['txtnum'], $comprasData[0]['txtemision'], $comprasData[0]['txtserie']

} catch (Exception $ex) {
    ?>
    <div class="transparent_ajax_error">
        <p>Error en la consulta de compras: <?php echo "".$ex ?></p>
    </div>
    <?php
}
// if($sesion_id_empresa==116){
    $descripcion = "Pago de Factura No.".$listado_numeros_facturas. " a " . $txtDeudor;
    
// }else{
//     $descripcion = "Pago de Factura No." . $comprasData[0]['numSerie'] . "-" . $comprasData[0]['txtEmision'] . "-" . $comprasData[0]['txtNum'] . " a " . $txtDeudor;
// }


                $debe = $txtValor;
                $haber1 = $txtValor;
                //$haber2 = $total_iva;
                $total_debe = $debe;
                $total_haber = $haber1 ;

//echo "<br>"."va a guardar comprobantes";
                //GUARDA EN  COMPROBANTES
                
                
                  $lin_diario=$j;
				$lin_diario=1;
		  
                
                $sqlC = "insert into comprobantes (id_comprobante, tipo_comprobante, numero_comprobante, id_empresa) 
				values ('".$id_comprobante."','".$tipo_comprobante."','".$numero_comprobante."',
				'".$sesion_id_empresa."')";
                $respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');

//echo "<br>"."va a guardar en el libro diario";

                //GUARDA EN EL LIBRO DIARIO
                $id_cuenta_pagar_LB = trim($id_cuenta_pagar_LB)==''?0:$id_cuenta_pagar_LB;
                $sqlLD = "insert into libro_diario (id_libro_diario, id_periodo_contable, numero_asiento, fecha,
				total_debe, total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta) 
				values ('".$id_libro_diario."','".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha."',
				'".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."',
				'".$tipo_comprobante."', '".$id_comprobante."', 'CP', '".$id_cuenta_pagar_LB."')";
                $resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
                
            $response['numero_asiento']=$numero_comprobante ;
            $fecha_asiento = date("Y-m-d");
            $response['fecha']=$fecha_asiento ;
                //$txtDeudor************************************    GUARDA EN EL DETALLE LIBRO DIARIO      **********************
                                        
            //28    $idPlanCuentas[1] = $formas_pago_id_plan_cuenta;
            //28    $idPlanCuentas[2] = $txtIdPlanCuentas;                    
                //$idPlanCuentas[3] = $impuestos_id_plan_cuenta;

            //28    $debeVector[1] = $txtValor;
           //28      $debeVector[2] = 0;
                //$debeVector[3] = 0;
            //28     $haberVector[1] = 0;
             //28    $haberVector[2] = $txtValor;
                //$haberVector[3] = $total_iva;


                $idPlanCuentas[1] = $txtIdPlanCuentas;                    

		//$idPlanCuentas[2] = $formas_pago_id_plan_cuenta;
                //$idPlanCuentas[3] = $impuestos_id_plan_cuenta;

                $debeVector[1] = $txtValor;
				$haberVector[1] = 0;
                          
			//	$debeVector[2] = 0;
            //  $haberVector[2] = $txtValor;
            
            //    if($servicios_iva == "Si"){
            //        $limite = 3;
            //    }else{
            //        $limite = 2;
            //    }
          
		  $lin_diario=1;
		  $txtContadorFilas=8;
		  $ret=0;    
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
                if (strtolower($_POST['txtTipoP'.$i]) =='cheque' OR
					strtolower($_POST['txtTipoP'.$i]) =='deposito' or
					strtolower($_POST['txtTipoP'.$i])=='transferenciac')
				{
                   $bancos[$lin_diario]=strtolower($_POST['txtTipoP'.$i]);
//echo "banco";
				//   echo $bancos[$lin_diario];
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
			$resultado=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;
			while($row=mysql_fetch_array($resultado)){           
			    $secuencialRetencion=$row['secRetencion1'];
			}
			$secuencialRetencion++;
		    $sqlUpdateRet=" UPDATE `retenciones` SET `secRetencion1`='".$secuencialRetencion."' WHERE `id_empresa`='".$sesion_id_empresa."'";
			 $resp = mysql_query($sqlUpdateRet) or die('<div class="transparent_ajax_error"><p>Error Actulizar Secuencial'.mysql_error().' </p></div>  ');
		        
		    
             
		    $sqlRetenciones="INSERT INTO `mcretencion`(`Factura_id`, `Numero`, `Fecha`, `TipoC`, `Autorizacion`, `Total`, `Total1`, 
                 `FechaAutorizacion`, `Retfuente`, `ClaveAcceso`, `Observaciones`, `Serie`) 
                 VALUES ('".$id_compra."','".$secuencialRetencion."','".$txtFechaPago."','1',NULL,NULL,NULL,NULL,$Retfuente,NULL,NULL,'".$establecimiento_codigo."-".$emision_codigo."') ";
            $resp = mysql_query($sqlRetenciones) or die('<div class="transparent_ajax_error"><p>Error Actulizar mc retencion'.mysql_error().' </p></div>  ');
            $idRetencion=mysql_insert_id();
    
            }
            
            for ($i=1; $i<=$lin_diario; $i++){
    if ($idPlanCuentas[$i] !=""){  
        if ($idformaPago[$i]>0) {  
                    
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
					VALUES ('".$idRetencion."','".substr($txtFechaPago,0,4)."','".$baseImp."','".$codigoImp."','".$codigoSri."','".$txtPorcentajeS[$i]."') ";
					$resp2 = mysql_query($sqlDetalleRetencion) or die('<div class="transparent_ajax_error"><p>Error Detalle Retencion: '.mysql_error().' </p></div>  ');

			}  
    }
     if ($bancos[$i] != "")
				{
				    //  echo   $bancos[$lin_diario]=strtolower($_POST['txtTipoP'.$i])."texto</br>";
				// 	echo $bancos[$i];
				 
                    $sql_suc="SELECT id_bancos from bancos where id_plan_cuenta ='".$idPlanCuentas[$i]."';";
                    $res_suc= mysql_query($sql_suc);
                    $nro_bancos = mysql_num_rows($res_suc);
 $response['mensajes4'] =$sql_suc;
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
                        $res_bancos = mysql_query($sql_bancos);
                         $response['mensajes5'] =$sql_bancos;
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
//echo "bbb";



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

//estoy agregando estas instrucciones

						$sql="Select max(id_detalle_banco)+1 as id_max From detalle_bancos ";
						$resultado=mysql_query($sql);
						$id_detalle_banco=0;
						while ($row=mysql_fetch_array($resultado))
						{
							$id_detalle_banco=$row['id_max'];
						}



			  
                        $res_bancos = mysql_query($sql_bancos) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
     
                        $sql_bancos = "insert into detalle_bancos (tipo_documento,numero_documento,detalle,valor,fecha_cobro,fecha_vencimiento,id_bancos,estado,
                            id_libro_diario) values ('".ucwords($bancos[$i])."','".ucfirst($nro_cpte[$i])."','".$descripcion."','".$haberVector[$i]."','".$fechas_pag_ven[$i]."','".$fechas_pag_ven[$i]."','".$id_bancos_suc."','No Conciliado', '".$id_libro_diario."' );";


                        $res_bancos = mysql_query($sql_bancos) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
						$id_detalle_banco=mysql_insert_id();
						
                    }
                }
    }
}
//print_r($idPlanCuentas);

            $sqlDLD2 = "insert into detalle_libro_diario ( id_libro_diario, id_plan_cuenta,
					debe, haber, id_periodo_contable) values ('".$id_libro_diario."','".$formas_pago_id_plan_cuenta."','".$txtValor."','".'0'."','".$sesion_id_periodo_contable."');";
            $resp2 = mysql_query($sqlDLD2) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');

		  for ($i=2; $i<=$lin_diario; $i++){
			if ($idPlanCuentas[$i] !=""){    //permite sacar el id maximo de detalle_libro_diario
				try{
					$sql="Select max(id_detalle_libro_diario) From detalle_libro_diario";
					$resultado=mysql_query($sql);
					$id_detalle_libro_diario=0;
					while($row=mysql_fetch_array($resultado)){//permite ir de fila en fila de la tabla
						$id_detalle_libro_diario=$row['max(id_detalle_libro_diario)'];
					}
					$id_detalle_libro_diario++;
				}
				catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max detalle_libro_diario: <?php echo "".$ex ?></p></div> <?php }
				
				$sqlDLD2 = "insert into detalle_libro_diario ( id_libro_diario, id_plan_cuenta,
					debe, haber, id_periodo_contable) values ('".$id_libro_diario."','".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."','".$sesion_id_periodo_contable."');";
                $resp2 = mysql_query($sqlDLD2) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
         //  	echo "DETALLE222||".$sqlDLD2."</br>";  


                    // CONSULTAS PARA GENERAR LA MAYORIZACION
                $sql5 = "SELECT * FROM mayorizacion WHERE id_plan_cuenta ='".$idPlanCuentas[$i]."' AND id_periodo_contable = '".$sesion_id_periodo_contable."';";
//echo $sql5;                   
				$result5=mysql_query($sql5);
                while($row5=mysql_fetch_array($result5))//permite ir de fila en fila de la tabla
                {
                    $id_mayorizacion=$row5['id_mayorizacion'];
                }
                $numero = mysql_num_rows($result5); // obtenemos el número de filas
                if($numero > 0){
                           // si hay filas
                }
				else 
				{ 
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

	//						echo "<br>"."va a mayorizar";
                            $sql6 = "insert into mayorizacion (id_mayorizacion, id_plan_cuenta, id_periodo_contable) values ('".$id_mayorizacion."','".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
                            $result6=mysql_query($sql6);
                        }
						catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la insercion de la tabla mayorizacion: <?php echo "".$ex ?></p></div> <?php }
                }
			}
		  } 
               
               
               
                
  /*           $saldo = $txtDeudaTotal - $txtValor;
                if($saldo == 0){
                    $estadoCC = "Canceladas";
                }else{
                    $estadoCC = "Pendientes";
                }
    $sqlCuentaPagar = "update cuentas_por_pagar set saldo='".$saldo."', estado='".$estadoCC."', fecha_pago='".$fecha_pago."', numero_asiento='".$numero_asiento."', 
    id_forma_pago='0', banco_referencia='".$txtBancoReferencia."', documento_numero='".$txtDocumentoNumero."' where id_cuenta_por_pagar='".$txtIdCuentaPagar."';";

				$respCuentaPagar = mysql_query($sqlCuentaPagar);        
 */
 
		if ($tipoPago == 1)
		{
			$j=1;
// 			echo 'nCancelaciones=>|'.$numero_cancelaciones.'|';
			for ($j=1; $j<=$numero_cancelaciones; $j++)
			{	
			    
// 			var_dump($facturas_x_pagar[$j]);
				if ($facturas_x_pagar[$j][4]>0) 
				{
				//$saldo_x_factura=$row['cuentas_por_pagar_saldo'];				
				$txtIdCuentaPagar=$facturas_x_pagar[$j][1];
			//	echo "id de cobranza=".$txtIdCuentaPagar;
			//$saldo = $txtDeudaTotal - $txtValor;
			    //    echo "<br/>"."Saldo".$facturas_x_pagar[$j][2];
				//	echo "<br/>"."Cancela".$facturas_x_pagar[$j][4];
					$saldo = $facturas_x_pagar[$j][2]-$facturas_x_pagar[$j][4];
				//	echo "<br/>"."Saldo".$saldo;
					if($saldo == 0){
						$estadoCC = "Canceladas";
					}else{
						$estadoCC = "Pendientes";
					}
					$sqlCuentaPagar = "update cuentas_por_pagar set saldo='".$saldo."', estado='".$estadoCC."', 
					fecha_pago='".$fecha_pago."',
					numero_asiento='".$numero_asiento."', id_forma_pago='0', banco_referencia='".$txtBancoReferencia."',
					documento_numero='".$txtDocumentoNumero."' where id_cuenta_por_pagar='".$txtIdCuentaPagar."';";
				// 	echo $sqlCuentaPagar;
					$respCuentaPagar = mysql_query($sqlCuentaPagar); 
					 if($respCuentaPagar){
                      $sqlDetalleAbono = " INSERT INTO `detalle_cuentas_por_pagar`( `id_cuenta_por_pagar`, `valor`, `fecha`,numero_asiento) VALUES ('".$txtIdCuentaPagar."','".$facturas_x_pagar[$j][4]."','".$fecha_pago."','".$numero_asiento."')";
                        $resultDetalleAbono= mysql_query($sqlDetalleAbono);
                    } 
				}
			};	
		}
		else
		{
		    
			$valor_x_cancelarx=$valor_x_cancelar;	
			 $response['$numero_cancelaciones'] = $numero_cancelaciones;
			 $response['$valor_x_cancelarx'] = $valor_x_cancelarx;
		$j=0;
			for ($j=0; $j<=$numero_cancelaciones; $j++)
			{	
			//	echo "valor a cancelar".$facturas_x_pagar[$j][1];
				if ($facturas_x_pagar[$j][1]>0) 
				{
					$txtIdCuentaPagar=$facturas_x_pagar[$j][1];
					$sql="Select saldo from cuentas_por_pagar 
					where id_cuenta_por_pagar='".$txtIdCuentaPagar."';";
				//	echo $sql;
					$result = mysql_query($sql) or die(mysql_error());
					$saldo_x_factura=0;
					while ($row = mysql_fetch_array($result))
					{ 
						$saldo_x_factura=$row['saldo'];				
					}

//echo "valor".$valor_x_cancelarx;
//echo "saldo".$saldo_x_factura;

					if ($saldo_x_factura<$valor_x_cancelarx)
					{
						$facturas_x_pagar[$j][2]=$saldo_x_factura;
						$estadoCC = "Canceladas";
						$saldo=0;
//						echo "op1";
					}
					else
					{
						$saldo=$saldo_x_factura-$valor_x_cancelarx;
						$estadoCC = "Pendientes";
						$facturas_x_pagar[$j][2]=$valor_x_cancelarx;
	//				echo "op2";
					}
					
				
					$valor_x_cancelarx=$valor_x_cancelarx-$facturas_x_pagar[$j][2];
						//echo "Pendiente".$valor_x_cancelarx;
 
				$sqlCuentaPagar = "update cuentas_por_pagar set saldo='".$saldo."',
					estado='".$estadoCC."', 
					fecha_pago='".$fecha_pago."',
					numero_asiento='".$numero_asiento."', id_forma_pago='0', banco_referencia='".$txtBancoReferencia."',
					documento_numero='".$txtDocumentoNumero."' 
					where id_cuenta_por_pagar='".$txtIdCuentaPagar."';";
				// 	echo "2!".$sqlCuentaPagar;
				 $response['$sqlCuentaPagar'] = $sqlCuentaPagar;
					$respCuentaPagar = mysql_query($sqlCuentaPagar);
					   if($respCuentaPagar){
                         $sqlDetalleAbono = " INSERT INTO `detalle_cuentas_por_pagar`( `id_cuenta_por_pagar`, `valor`, `fecha`,numero_asiento) VALUES ('".$txtIdCuentaPagar."','".$facturas_x_pagar[$j][2]."','".$fecha_pago."','".$numero_asiento."')";
                         $resultDetalleAbono= mysql_query($sqlDetalleAbono);
                     }
					if ($valor_x_cancelarx==0)
					{
						break;
					}
					//$i=$i+1;
				}
			};
		}


            if($resp && $respCuentaPagar){
               $response['mensajes'] = "<div class='transparent_ajax_correcto'><p>Registro insertado correctamente.</p></div>";
            }else{
                 $response['mensajes2'] =$resp;
                 $response['mensajes3'] =$respCuentaPagar;
                $response['mensajes'] = " <div class='transparent_ajax_error'><p>Error al guarda los datos: problemas en la consulta</p></div>";
            }

        }else{
            $response['mensajes'] = " <div class='transparent_ajax_error'><p>Fallo en el envio del Formulario: No hay datos</p></div>";
        }
echo json_encode($response);
    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }

}

if($accion == "11")
{
    // GUARDAR PAGO CUENTAS POR COBRAR  PAGINA: 
    try
    {
        $response=[];
        $id_compra =  $_POST['id_compra'];
//		echo "estoy en la opcion 1";
        $txtIdProveedor = ($_POST['txtIdProveedor']);
        $txtIdPlanCuentas = ($_POST['txtIdPlanCuentas']);
        $txtFechaPago = ($_POST['txtFechaPago']);         
        $hora = date("H:i");
        $txtBancoReferencia = ($_POST['txtBancoReferencia']);
        $txtDocumentoNumero = ($_POST['txtDocumentoNumero']);
    //    $txtValor = ($_POST['txtSubtotalFVC']);
	    $txtValor = ($_POST['txtPagoFP']);
        $txtEfectivo = ($_POST['txtEfectivo']);
        $txtSaldo= ($_POST['txtSaldo']);
        $txtIdCuentaPagar = ($_POST['txtIdCuentaPagar']);
        $txtDeudaTotal = ($_POST['txtDeudaTotal']);
        $txtDeudor = ($_POST['txtDeudor']);
        $fecha_pago = date("Y-m-d h:i:s");
        $txtFacturaCpra=($_POST['id_compra']);
        $tipo_persona= $_POST['switch-four'];
		$valor_x_cancelar=0;
		$valor_x_cancelar=$txtValor;
		
		if ($tipoPago == 1)
		{	
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
				$facturas_x_pagar[$n][1]=$arregloCHK[$n];;	
			}
			$valor_x_cancelar=$txtDeudaTotal;

			$valor_x_cancelar=$txtValor; 
		}

        if($txtIdProveedor != ""  && $valor_x_cancelar != "") 
		{


        if ($tipoPago == 1)
		{
		
		 if($tipo_persona==2){
		     	$sql = "SELECT
			 cuentas_por_pagar.`id_cuenta_por_pagar` AS ctas_x_pagar_id_cuenta_por_pagar,
             cuentas_por_pagar.`saldo` AS cuentas_por_pagar_saldo,
             cuentas_por_pagar.`id_cliente` AS cuentas_por_pagar_id_proveedor,
             cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
             cuentas_por_pagar.`estado` AS cuentas_por_pagar_estado
        FROM
             `cuentas_por_pagar` cuentas_por_pagar 
              WHERE  cuentas_por_pagar.`id_empresa`= '".$sesion_id_empresa."' and 
			  cuentas_por_pagar.`id_cliente`='".$txtIdProveedor."' and saldo>0 
			  order by cuentas_por_pagar.`fecha_vencimiento`"; 
			  
			     
			 }else if($tipo_persona==1){
			     		$sql = "SELECT
			 cuentas_por_pagar.`id_cuenta_por_pagar` AS ctas_x_pagar_id_cuenta_por_pagar,
             cuentas_por_pagar.`saldo` AS cuentas_por_pagar_saldo,
             cuentas_por_pagar.`id_proveedor` AS cuentas_por_pagar_id_proveedor,
             cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
             cuentas_por_pagar.`estado` AS cuentas_por_pagar_estado
        FROM
            	`cuentas_por_pagar` cuentas_por_pagar 
			
              WHERE  cuentas_por_pagar.`id_empresa`= '".$sesion_id_empresa."' and   cuentas_por_pagar.`id_proveedor`='".$txtIdProveedor."' and saldo>0 
			  order by cuentas_por_pagar.`fecha_vencimiento`"; 
			  
			 }else if($tipo_persona==3){
			     	$sql = "SELECT
			 cuentas_por_pagar.`id_cuenta_por_pagar` AS ctas_x_pagar_id_cuenta_por_pagar,
             cuentas_por_pagar.`saldo` AS cuentas_por_pagar_saldo,
             cuentas_por_pagar.`id_lead` AS cuentas_por_pagar_id_proveedor,
             cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
             cuentas_por_pagar.`estado` AS cuentas_por_pagar_estado
        FROM
            	`cuentas_por_pagar` cuentas_por_pagar 
			
              WHERE  cuentas_por_pagar.`id_empresa`= '".$sesion_id_empresa."' and   cuentas_por_pagar.`id_lead`='".$txtIdProveedor."' and saldo>0 
			  order by cuentas_por_pagar.`fecha_vencimiento`";
			 }else if($tipo_persona==4){
			     	$sql = "SELECT
			 cuentas_por_pagar.`id_cuenta_por_pagar` AS ctas_x_pagar_id_cuenta_por_pagar,
             cuentas_por_pagar.`saldo` AS cuentas_por_pagar_saldo,
             cuentas_por_pagar.`id_empleado` AS cuentas_por_pagar_id_proveedor,
             cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
             cuentas_por_pagar.`estado` AS cuentas_por_pagar_estado
        FROM
            	`cuentas_por_pagar` cuentas_por_pagar 
			
              WHERE  cuentas_por_pagar.`id_empresa`= '".$sesion_id_empresa."' and   cuentas_por_pagar.`id_empleado`='".$txtIdProveedor."' and saldo>0 
			  order by cuentas_por_pagar.`fecha_vencimiento`";
			 }

            $response['$sql']=$sql ;
            $result = mysql_query($sql) or die(mysql_error());
            $i=1;

			$valor_x_cancelarx=$valor_x_cancelar;
			while ($row = mysql_fetch_array($result))
			{ 
				$saldo_x_factura=$row['cuentas_por_pagar_saldo'];				
				$facturas_x_pagar[$i][1]=$row['ctas_x_pagar_id_cuenta_por_pagar'];
				$facturas_x_pagar[$i][2]=$row['cuentas_por_pagar_saldo'];				
				$facturas_x_pagar[$i][3]=$row['cuentas_por_pagar_id_proveedor'];
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
			//echo "va a distribuir";
			//echo "I=".$i;
			for ($j=1; $j<=$i; $j++)
			{
			/*	echo "<br/>"."ID cobros=";
				echo $facturas_x_pagar[$j][1];
				echo "saldo";
				echo $facturas_x_pagar[$j][2];
//				echo "Proveedor";
//				echo $facturas_x_pagar[$j][3];
				echo "cancelar";
				echo $facturas_x_pagar[$j][4];
				echo "<br>";
				*/
			}
		}
		
            
             //************************************ GUARDAR ASIENTO CONTABLE ***************************************/
            //****************************************** LIBRO DIARIO ***************************************/
                    
            
            //permite sacar el numero_asiento de libro_diario
            try{
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
            //    echo $sqlMNA;
				$resultMNA=mysql_query($sqlMNA) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
                $numero_asiento=0;
                while($rowMNA=mysql_fetch_array($resultMNA))//permite ir de fila en fila de la tabla
                {
                    $numero_asiento=$rowMNA['max_numero_asiento'];
                }
                $numero_asiento++;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
                      
          //  echo "<br>"."va a grabar asiento";
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

            }catch(Exception $ex) 
			{ ?> <div class="transparent_ajax_error">
			<p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
                          

            
     		$sql="Select cuenta_contable From enlaces_compras where tipo='credito' and id_empresa='".$sesion_id_empresa."';";
		$resultado=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;
//			echo $sql;
		while($row=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
		{
			$formas_pago_id_plan_cuenta=$row['cuenta_contable'];
		}
     	
		  $tipo_comprobante = "Diario"; 



			  
			// ACTUALIZA LA TABLA CUENTAS POR COBRAR
        //    echo "<br>"."actualizar la cuentas_por_pagar";
             

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
                    // Error en algun momento.
                   ?> <div class="transparent_ajax_error"><p>Error max_numero_comprobante: <?php echo "".$e ?></p></div> <?php
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

                }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }

                $fecha= $txtFechaPago." ".$hora;
                
                
                
             //   $descripcion = " Realizado desde Cuentas por Pagar a".$txtDeudor;
try {
    $sqlCompras = "SELECT numSerie, txtEmision, txtNum FROM compras WHERE id_compra = $id_compra;";
    $resultCompras = mysql_query($sqlCompras) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>');

    $comprasData = array();

    while ($rowCompras = mysql_fetch_assoc($resultCompras)) {
        $comprasData[] = $rowCompras;
    }

    // Aquí puedes utilizar $comprasData para acceder a los valores de txtnum, txtemision y txtserie
    // por ejemplo, $comprasData[0]['txtnum'], $comprasData[0]['txtemision'], $comprasData[0]['txtserie']

} catch (Exception $ex) {
    ?>
    <div class="transparent_ajax_error">
        <p>Error en la consulta de compras: <?php echo "".$ex ?></p>
    </div>
    <?php
}

$descripcion = "Pago de Factura No." . $comprasData[0]['numSerie'] . "-" . $comprasData[0]['txtEmision'] . "-" . $comprasData[0]['txtNum'] . " a " . $txtDeudor;

                $debe = $txtValor;
                $haber1 = $txtValor;
                //$haber2 = $total_iva;
                $total_debe = $debe;
                $total_haber = $haber1 ;

//echo "<br>"."va a guardar comprobantes";
                //GUARDA EN  COMPROBANTES
                
                
                  $lin_diario=$j;
				$lin_diario=1;
		  
                
                $sqlC = "insert into comprobantes (id_comprobante, tipo_comprobante, numero_comprobante, id_empresa) 
				values ('".$id_comprobante."','".$tipo_comprobante."','".$numero_comprobante."',
				'".$sesion_id_empresa."')";
                $respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');

//echo "<br>"."va a guardar en el libro diario";

                //GUARDA EN EL LIBRO DIARIO
                $sqlLD = "insert into libro_diario (id_libro_diario, id_periodo_contable, numero_asiento, fecha,
				total_debe, total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante) 
				values ('".$id_libro_diario."','".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha."',
				'".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."',
				'".$tipo_comprobante."', '".$id_comprobante."')";
                $resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
                
            $response['numero_asiento']=$numero_comprobante ;
            $fecha_asiento = date("Y-m-d");
            $response['fecha']=$fecha_asiento ;
                //$txtDeudor************************************    GUARDA EN EL DETALLE LIBRO DIARIO      **********************
                                        
            //28    $idPlanCuentas[1] = $formas_pago_id_plan_cuenta;
            //28    $idPlanCuentas[2] = $txtIdPlanCuentas;                    
                //$idPlanCuentas[3] = $impuestos_id_plan_cuenta;

            //28    $debeVector[1] = $txtValor;
           //28      $debeVector[2] = 0;
                //$debeVector[3] = 0;
            //28     $haberVector[1] = 0;
             //28    $haberVector[2] = $txtValor;
                //$haberVector[3] = $total_iva;


                $idPlanCuentas[1] = $txtIdPlanCuentas;                    

		//$idPlanCuentas[2] = $formas_pago_id_plan_cuenta;
                //$idPlanCuentas[3] = $impuestos_id_plan_cuenta;

                $debeVector[1] = $txtValor;
				$haberVector[1] = 0;
                          
			//	$debeVector[2] = 0;
            //  $haberVector[2] = $txtValor;
            
            //    if($servicios_iva == "Si"){
            //        $limite = 3;
            //    }else{
            //        $limite = 2;
            //    }
          
		  $lin_diario=1;
		  $txtContadorFilas=8;
		  $ret=0;    
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
                if (strtolower($_POST['txtTipoP'.$i]) =='cheque' OR
					strtolower($_POST['txtTipoP'.$i]) =='deposito' or
					strtolower($_POST['txtTipoP'.$i])=='transferenciac')
				{
                   $bancos[$lin_diario]=strtolower($_POST['txtTipoP'.$i]);
//echo "banco";
				//   echo $bancos[$lin_diario];
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
			$resultado=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;
			while($row=mysql_fetch_array($resultado)){           
			    $secuencialRetencion=$row['secRetencion1'];
			}
			$secuencialRetencion++;
		    $sqlUpdateRet=" UPDATE `retenciones` SET `secRetencion1`='".$secuencialRetencion."' WHERE `id_empresa`='".$sesion_id_empresa."'";
			 $resp = mysql_query($sqlUpdateRet) or die('<div class="transparent_ajax_error"><p>Error Actulizar Secuencial'.mysql_error().' </p></div>  ');
		        
		    
             
		    $sqlRetenciones="INSERT INTO `mcretencion`(`Factura_id`, `Numero`, `Fecha`, `TipoC`, `Autorizacion`, `Total`, `Total1`, 
                 `FechaAutorizacion`, `Retfuente`, `ClaveAcceso`, `Observaciones`, `Serie`) 
                 VALUES ('".$id_compra."','".$secuencialRetencion."','".$txtFechaPago."','1',NULL,NULL,NULL,NULL,$Retfuente,NULL,NULL,'".$establecimiento_codigo."-".$emision_codigo."') ";
            $resp = mysql_query($sqlRetenciones) or die('<div class="transparent_ajax_error"><p>Error Actulizar mc retencion'.mysql_error().' </p></div>  ');
            $idRetencion=mysql_insert_id();
    
            }
            
            for ($i=1; $i<=$lin_diario; $i++){
    if ($idPlanCuentas[$i] !=""){  
        if ($idformaPago[$i]>0) {  
                    
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
					VALUES ('".$idRetencion."','".substr($txtFechaPago,0,4)."','".$baseImp."','".$codigoImp."','".$codigoSri."','".$txtPorcentajeS[$i]."') ";
					$resp2 = mysql_query($sqlDetalleRetencion) or die('<div class="transparent_ajax_error"><p>Error Detalle Retencion: '.mysql_error().' </p></div>  ');

			}  
    }
     if ($bancos[$i] != "")
				{
				    //  echo   $bancos[$lin_diario]=strtolower($_POST['txtTipoP'.$i])."texto</br>";
				// 	echo $bancos[$i];
				 
                    $sql_suc="SELECT id_bancos from bancos where id_plan_cuenta ='".$idPlanCuentas[$i]."';";
                    $res_suc= mysql_query($sql_suc);
                    $nro_bancos = mysql_num_rows($res_suc);
 $response['mensajes4'] =$sql_suc;
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
                        $res_bancos = mysql_query($sql_bancos);
                         $response['mensajes5'] =$sql_bancos;
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
//echo "bbb";



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

//estoy agregando estas instrucciones

						$sql="Select max(id_detalle_banco)+1 as id_max From detalle_bancos ";
						$resultado=mysql_query($sql);
						$id_detalle_banco=0;
						while ($row=mysql_fetch_array($resultado))
						{
							$id_detalle_banco=$row['id_max'];
						}



			  
                        $res_bancos = mysql_query($sql_bancos) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
     
                        $sql_bancos = "insert into detalle_bancos (tipo_documento,numero_documento,detalle,valor,fecha_cobro,fecha_vencimiento,id_bancos,estado,
                            id_libro_diario) values ('".ucwords($bancos[$i])."','".ucfirst($nro_cpte[$i])."','".$descripcion."','".$haberVector[$i]."','".$fechas_pag_ven[$i]."','".$fechas_pag_ven[$i]."','".$id_bancos_suc."','No Conciliado', '".$id_libro_diario."' );";


                        $res_bancos = mysql_query($sql_bancos) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
						$id_detalle_banco=mysql_insert_id();
						
                    }
                }
    }
}
//print_r($idPlanCuentas);

            $sqlDLD2 = "insert into detalle_libro_diario ( id_libro_diario, id_plan_cuenta,
					debe, haber, id_periodo_contable) values ('".$id_libro_diario."','".$formas_pago_id_plan_cuenta."','".$txtValor."','".'0'."','".$sesion_id_periodo_contable."');";
            $resp2 = mysql_query($sqlDLD2) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');

		  for ($i=2; $i<=$lin_diario; $i++){
			if ($idPlanCuentas[$i] !=""){    //permite sacar el id maximo de detalle_libro_diario
				try{
					$sql="Select max(id_detalle_libro_diario) From detalle_libro_diario";
					$resultado=mysql_query($sql);
					$id_detalle_libro_diario=0;
					while($row=mysql_fetch_array($resultado)){//permite ir de fila en fila de la tabla
						$id_detalle_libro_diario=$row['max(id_detalle_libro_diario)'];
					}
					$id_detalle_libro_diario++;
				}
				catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max detalle_libro_diario: <?php echo "".$ex ?></p></div> <?php }
				
				$sqlDLD2 = "insert into detalle_libro_diario ( id_libro_diario, id_plan_cuenta,
					debe, haber, id_periodo_contable) values ('".$id_libro_diario."','".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."','".$sesion_id_periodo_contable."');";
                $resp2 = mysql_query($sqlDLD2) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
         //  	echo "DETALLE222||".$sqlDLD2."</br>";  


                    // CONSULTAS PARA GENERAR LA MAYORIZACION
                $sql5 = "SELECT * FROM mayorizacion WHERE id_plan_cuenta ='".$idPlanCuentas[$i]."' AND id_periodo_contable = '".$sesion_id_periodo_contable."';";
//echo $sql5;                   
				$result5=mysql_query($sql5);
                while($row5=mysql_fetch_array($result5))//permite ir de fila en fila de la tabla
                {
                    $id_mayorizacion=$row5['id_mayorizacion'];
                }
                $numero = mysql_num_rows($result5); // obtenemos el número de filas
                if($numero > 0){
                           // si hay filas
                }
				else 
				{ 
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

	//						echo "<br>"."va a mayorizar";
                            $sql6 = "insert into mayorizacion (id_mayorizacion, id_plan_cuenta, id_periodo_contable) values ('".$id_mayorizacion."','".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
                            $result6=mysql_query($sql6);
                        }
						catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la insercion de la tabla mayorizacion: <?php echo "".$ex ?></p></div> <?php }
                }
			}
		  } 
               
               
               
                
  /*           $saldo = $txtDeudaTotal - $txtValor;
                if($saldo == 0){
                    $estadoCC = "Canceladas";
                }else{
                    $estadoCC = "Pendientes";
                }
    $sqlCuentaPagar = "update cuentas_por_pagar set saldo='".$saldo."', estado='".$estadoCC."', fecha_pago='".$fecha_pago."', numero_asiento='".$numero_asiento."', 
    id_forma_pago='0', banco_referencia='".$txtBancoReferencia."', documento_numero='".$txtDocumentoNumero."' where id_cuenta_por_pagar='".$txtIdCuentaPagar."';";

				$respCuentaPagar = mysql_query($sqlCuentaPagar);        
 */
 
		if ($tipoPago == 1)
		{
			$j=1;
// 			echo 'nCancelaciones=>|'.$numero_cancelaciones.'|';
			for ($j=1; $j<=$numero_cancelaciones; $j++)
			{	
			    
// 			var_dump($facturas_x_pagar[$j]);
				if ($facturas_x_pagar[$j][4]>0) 
				{
				//$saldo_x_factura=$row['cuentas_por_pagar_saldo'];				
				$txtIdCuentaPagar=$facturas_x_pagar[$j][1];
			//	echo "id de cobranza=".$txtIdCuentaPagar;
			//$saldo = $txtDeudaTotal - $txtValor;
			    //    echo "<br/>"."Saldo".$facturas_x_pagar[$j][2];
				//	echo "<br/>"."Cancela".$facturas_x_pagar[$j][4];
					$saldo = $facturas_x_pagar[$j][2]-$facturas_x_pagar[$j][4];
				//	echo "<br/>"."Saldo".$saldo;
					if($saldo == 0){
						$estadoCC = "Canceladas";
					}else{
						$estadoCC = "Pendientes";
					}
					$sqlCuentaPagar = "update cuentas_por_pagar set saldo='".$saldo."', estado='".$estadoCC."', 
					fecha_pago='".$fecha_pago."',
					numero_asiento='".$numero_asiento."', id_forma_pago='0', banco_referencia='".$txtBancoReferencia."',
					documento_numero='".$txtDocumentoNumero."' where id_cuenta_por_pagar='".$txtIdCuentaPagar."';";
				// 	echo $sqlCuentaPagar;
					$respCuentaPagar = mysql_query($sqlCuentaPagar); 
					 if($respCuentaPagar){
                      $sqlDetalleAbono = " INSERT INTO `detalle_cuentas_por_pagar`( `id_cuenta_por_pagar`, `valor`, `fecha`,numero_asiento) VALUES ('".$txtIdCuentaPagar."','".$facturas_x_pagar[$j][4]."','".$fecha_pago."','".$numero_asiento."')";
                        $resultDetalleAbono= mysql_query($sqlDetalleAbono);
                    } 
				}
			};	
		}
		else
		{
		    
			$valor_x_cancelarx=$valor_x_cancelar;	
			 $response['$numero_cancelaciones'] = $numero_cancelaciones;
			 $response['$valor_x_cancelarx'] = $valor_x_cancelarx;
		$j=0;
			for ($j=0; $j<=$numero_cancelaciones; $j++)
			{	
			//	echo "valor a cancelar".$facturas_x_pagar[$j][1];
				if ($facturas_x_pagar[$j][1]>0) 
				{
					$txtIdCuentaPagar=$facturas_x_pagar[$j][1];
					$sql="Select saldo from cuentas_por_pagar 
					where id_cuenta_por_pagar='".$txtIdCuentaPagar."';";
				//	echo $sql;
					$result = mysql_query($sql) or die(mysql_error());
					$saldo_x_factura=0;
					while ($row = mysql_fetch_array($result))
					{ 
						$saldo_x_factura=$row['saldo'];				
					}

//echo "valor".$valor_x_cancelarx;
//echo "saldo".$saldo_x_factura;

					if ($saldo_x_factura<$valor_x_cancelarx)
					{
						$facturas_x_pagar[$j][2]=$saldo_x_factura;
						$estadoCC = "Canceladas";
						$saldo=0;
//						echo "op1";
					}
					else
					{
						$saldo=$saldo_x_factura-$valor_x_cancelarx;
						$estadoCC = "Pendientes";
						$facturas_x_pagar[$j][2]=$valor_x_cancelarx;
	//				echo "op2";
					}
					
				
					$valor_x_cancelarx=$valor_x_cancelarx-$facturas_x_pagar[$j][2];
						//echo "Pendiente".$valor_x_cancelarx;
 
				$sqlCuentaPagar = "update cuentas_por_pagar set saldo='".$saldo."',
					estado='".$estadoCC."', 
					fecha_pago='".$fecha_pago."',
					numero_asiento='".$numero_asiento."', id_forma_pago='0', banco_referencia='".$txtBancoReferencia."',
					documento_numero='".$txtDocumentoNumero."' 
					where id_cuenta_por_pagar='".$txtIdCuentaPagar."';";
				// 	echo "2!".$sqlCuentaPagar;
				 $response['$sqlCuentaPagar'] = $sqlCuentaPagar;
					$respCuentaPagar = mysql_query($sqlCuentaPagar);
					   if($respCuentaPagar){
                         $sqlDetalleAbono = " INSERT INTO `detalle_cuentas_por_pagar`( `id_cuenta_por_pagar`, `valor`, `fecha`,numero_asiento) VALUES ('".$txtIdCuentaPagar."','".$facturas_x_pagar[$j][2]."','".$fecha_pago."','".$numero_asiento."')";
                         $resultDetalleAbono= mysql_query($sqlDetalleAbono);
                     }
					if ($valor_x_cancelarx==0)
					{
						break;
					}
					//$i=$i+1;
				}
			};
		}


            if($resp && $respCuentaPagar){
               $response['mensajes'] = "<div class='transparent_ajax_correcto'><p>Registro insertado correctamente.</p></div>";
            }else{
                 $response['mensajes2'] =$resp;
                 $response['mensajes3'] =$respCuentaPagar;
                $response['mensajes'] = " <div class='transparent_ajax_error'><p>Error al guarda los datos: problemas en la consulta</p></div>";
            }

        }else{
            $response['mensajes'] = " <div class='transparent_ajax_error'><p>Fallo en el envio del Formulario: No hay datos</p></div>";
        }
echo json_encode($response);
    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }

}
    if($accion == 2)
	{
        
    

    }

    if($accion == "3")
	{
    // ELIMINA CARGOS PAGINA: cargos.php
		try
		{
		//	echo "codigo";
			//echo $_POST['id_cuenta_por_cobrar'];
			//id_cuenta_por_cobrar
			if(isset ($_POST['id_cuenta_por_cobrar']))
			{
				$id_cta_x_cobrar = $_POST['id_cuenta_por_pagar'];
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
// echo "ESTOY EN SQLaccion";
  //  echo $accion;
		
		$cmbTipoDocumentoFVC='';
		$cmbTipoDocumentoFVC="Compra#";		
//		$numero_factura=$_POST['txtFactura'];
		$numero_factura=$_POST['txtComprobante'];
		$fecha_compra=$_POST['textFecha'];
		$total=$_POST['txtTotal'];
	//	$sub_total=$_POST['txtSubtotal'];
	//	$iva=$_POST['txtIdIva'];
	//	$id_proveedor=$_POST['textIdProveedor'];
		
		$id_proveedor=$_POST['cmbProveedor'];

	//	$txtNombreRuc=$_POST['txtNombreRuc'];
	
	
		$txtFechaVencimiento=$_POST['txtFechaVencimiento'];
	    //$fecha_nueva=$_POST['txtFechaVencimiento'};	
			//echo $numero_factura;
		//echo $total;
		//echo $id_proveedor;
		
		$sqlp="Select * From proveedores where id_empresa='".$sesion_id_empresa."' and id_proveedor='".$id_proveedor."' ;";
	//	echo $sqlp;
        $resultp=mysql_query($sqlp);
        $txtNombreRUC="";
		while($rowp=mysql_fetch_array($resultp))//permite ir de fila en fila de la tabla
        {
			$txtNombreRUC=$rowp['nombre_comercial'];
		}
	
	//	$txtIva=$_POST['txtIva'];
	//	$numero_compra=$_POST['txtFactura'];
		$numero_compra=$_POST['txtComprobante'];
		$formas_pago_id_plan_cuenta=0;
	//	$cmbTipoCompra=$_POST['cmbTipoCompra'];

		$id_compra="";
		$txtCuotasTP=1;
		
		$sql="Select cuenta_contable From enlaces_compras where tipo='credito' and id_empresa='".$sesion_id_empresa."';";
		$resultado=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;
//			echo $sql;
		while($row=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
		{
			$formas_pago_id_plan_cuenta=$row['cuenta_contable'];
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
				
				$sqlm2="Select max(id_cuenta_por_pagar) From cuentas_por_pagar;";
				$resultm2=mysql_query($sqlm2) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
				$id_cuenta_por_pagar=0;
				while($rowm2=mysql_fetch_array($resultm2))//permite ir de fila en fila de la tabla
				{
					$id_cuenta_por_pagar=$rowm2['max(id_cuenta_por_pagar)'];
				}
				$id_cuenta_por_pagar++;
				//	echo "ide cta por cobrar";
				//echo $id_cuenta_por_cobrar++;
				//$sql3 = "insert into cuentas_por_cobrar (id_cuenta_por_cobrar, numero_factura, referencia, valor, saldo, numero_asiento, fecha_vencimiento, fecha_pago, id_cliente, id_plan_cuenta, id_empresa, id_venta, estado) "
				//	. "values ('".$id_cuenta_por_cobrar."','".$numero_factura."','".$txtNombreFVC." ".$cmbTipoDocumentoFVC." ".$numero_factura."','".$cuotax."','".$cuotax."','', '".$fecha_nueva."', '', '".$id_cliente."', '".$formas_pago_id_plan_cuenta."', '".$sesion_id_empresa."', '".$id_venta."', '".$estadoCC."');";
				$sql3 = "insert into cuentas_por_pagar (id_cuenta_por_pagar, tipo_documento,numero_compra, referencia, valor, saldo, numero_asiento, fecha_vencimiento, fecha_pago, id_proveedor, id_plan_cuenta, id_empresa, id_compra, estado) "
					. "values ('".$id_cuenta_por_pagar."','".$cmbTipoDocumentoFVC."','".$numero_compra."','".$txtNombreRUC."','".$cuotax."','".$cuotax."','',		'".$fecha_nueva."', NULL, '".$id_proveedor."', '".$formas_pago_id_plan_cuenta."', '".$sesion_id_empresa."', NULL, '".$estadoCC."');";

			/* $sql3 = "insert into cuentas_por_pagar (id_cuenta_por_pagar, tipo_documento,numero_compra, 
				referencia, 
				valor, saldo, numero_asiento, fecha_vencimiento, fecha_pago, id_proveedor,
				id_plan_cuenta, id_empresa, id_compra, estado) "
			. "values ('".$id_cuenta_por_pagar."','".$cmbTipoDocumentoFVC."','".$numero_compra."',
				'".$txtNombreRUC." ".$cmbTipoDocumentoFVC." ".$numero_compra."',
				'".$cuotax."','".$cuotax."','','".$fecha_nueva."', '', '".$id_proveedor."',
				'".$formas_pago_id_plan_cuenta."', '".$sesion_id_empresa."','".$id_compra."', '".$estadoCC."');";					
 */					
		//		echo $sql3;						
				$resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>Error al guardar en cuentas_por_cobrar: '.mysql_error().' </p></div>  ');
			}
			if($resp3)
			{
				?> <div class='alert alert-success'><p>***Registro cuentas por pagar guardado correctamente***.</p></div> <?php
			}
		}

		
		
	}
	
	 if($accion == 7)
	{
    $valor =			 $_POST['valor'];    
    $fechaVencimiento =	 $_POST['fechaVencimiento'];
	$fechaIngreso =		 $_POST['fechaIngreso'];
	$descripcion = 		 $_POST['descripcion'];
	$lead= 				 $_POST['lead'];
if($valor==""){
	$valor=0;
}
if($descripcion==""){
	$descripcion="Ninguna";
}


	if($lead !=''){


$sqlLead="SELECT  cuentas_por_pagar.id_lead, cuentas_por_pagar.numero_compra, cuentas_por_pagar.saldo FROM  cuentas_por_pagar  WHERE cuentas_por_pagar.id_lead='".$lead."' ";

$resultLead=mysql_query($sqlLead) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
				$numero=0;
				$saldo = 0;
				while($rowm2=mysql_fetch_array($resultLead))//permite ir de fila en fila de la tabla
				{
					$numero=$rowm2['numero_compra'];
					$saldo=$rowm2['saldo'];
					$valorInscripcion=$rowm2['valorInscripcion'];
				}
				$numero = $numero +1;

				if($numero==1){
					$sqlP="SELECT proyeccionCompra.valorInscripcion, proyeccionCompra.idCliente FROM `proyeccionCompra` WHERE aprobado='1' and proyeccionCompra.idCliente='".$lead."' ";

					$resultP=mysql_query($sqlP) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
				while($rowmP=mysql_fetch_array($resultP))//permite ir de fila en fila de la tabla
				{
				
					$valorInscripcion=$rowmP['valorInscripcion'];
				}
				$saldo = $valorInscripcion - $valor;
				}else{
					$saldo = $saldo - $valor;
				}
			
			


$sql= " INSERT INTO `cuentas_por_pagar`( `tipo_documento`, `numero_compra`, `referencia`, `valor`, `saldo`, `fecha_vencimiento`, `id_lead`, `estado`, `banco_referencia`,`id_empresa`) VALUES ('abono#','".$numero."','abono#".$numero."','".$valor."','".$saldo."','".$fechaVencimiento."','".$lead."', 'Pendientes', '".$descripcion."', '".$sesion_id_empresa."');";

$resp3 = mysql_query($sql) or die('<div class="alert alert-danger"><p>Error al guardar en cuentas_por_cobrar: '.mysql_error().' </p></div>  ');

	}else{
		echo "2";
	}

    }

	if($accion == 8)
	{
    $valor =			 $_POST['valor'];    
    $fechaVencimiento =	 $_POST['fechaVencimiento'];
	$fechaIngreso =		 $_POST['fechaIngreso'];
	$descripcion = 		 $_POST['descripcion'];
	$lead= 				 $_POST['lead'];
	$cuenta= 			 $_POST['cuenta'];
	if($valor==""){
		$valor=0;
	}
	if($descripcion==""){
		$descripcion="Ninguna";
	}

	if($cuenta !=''){

		$sqlP="SELECT  * FROM  cuentas_por_pagar  WHERE id_cuenta_por_pagar='".$cuenta."'  and cuentas_por_pagar.id_lead='".$lead."' ";
echo $sqlP;
		$resultP=mysql_query($sqlP) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
	while($rowmP=mysql_fetch_array($resultP))//permite ir de fila en fila de la tabla
	{
	
		$saldo=$rowmP['saldo'] + $rowmP['valor']  ;
	}
	$nuevoSaldo = $saldo - $valor;

$sql= "UPDATE `cuentas_por_pagar` SET `valor`='".$valor."' ,`saldo`='".$nuevoSaldo."' ,`fecha_vencimiento`='".$fechaVencimiento."' ,`banco_referencia`='".$descripcion."'  WHERE id_cuenta_por_pagar='".$cuenta."' ;";

$resp3 = mysql_query($sql) or die('<div class="alert alert-danger"><p>Error al guardar en cuentas_por_cobrar: '.mysql_error().' </p></div>  ');

	}else{
		echo "2";
	}

    }




   



