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
//echo "estoy en sql oooooo";
//echo $accion;

if($accion == "1"){

    // GUARDAR PAGO CUENTAS POR COBRAR  PAGINA: 
    try
    {
//		echo "estoy en la opcion 1";
        $txtIdProveedor = ($_POST['txtIdProveedor']);
        $txtIdPlanCuentas = ($_POST['txtIdPlanCuentas']);
        $txtFechaPago = ($_POST['txtFechaPago']);         
        $hora = date("H:i");
        $txtBancoReferencia = ($_POST['txtBancoReferencia']);
        $txtDocumentoNumero = ($_POST['txtDocumentoNumero']);
        $txtValor = ($_POST['txtSubtotalFVC']);
        $txtEfectivo = ($_POST['txtEfectivo']);
        $txtSaldo= ($_POST['txtSaldo']);
        $txtIdCuentaPagar = ($_POST['txtIdCuentaPagar']);
        $txtDeudaTotal = ($_POST['txtDeudaTotal']);
        $txtDeudor = ($_POST['txtDeudor']);
        $fecha_pago = date("Y-m-d h:i:s");
        $txtFacturaCpra=($_POST['txtFactura']);
		
        if($txtIdProveedor != ""  && $txtValor != "") {

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
                $descripcion = " Pago de Factura No.".$txtFacturaCpra." a ".$txtDeudor;
                $debe = $txtValor;
                $haber1 = $txtValor;
                //$haber2 = $total_iva;
                $total_debe = $debe;
                $total_haber = $haber1 ;

//echo "<br>"."va a guardar comprobantes";
                //GUARDA EN  COMPROBANTES
                
                
                  $lin_diario=$j;

		  
                
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
                $debeVector[2] = 0;
                //$debeVector[3] = 0;
                $haberVector[1] = 0;
                $haberVector[2] = $txtValor;
                //$haberVector[3] = $total_iva;


                if($servicios_iva == "Si"){
                    $limite = 3;
                }else{
                    $limite = 2;
                }



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
                if (strtolower($_POST['txtTipoP'.$i]) =='cheque' OR
					strtolower($_POST['txtTipoP'.$i]) =='deposito' or
					strtolower($_POST['txtTipoP'.$i])=='transferencia')
				{
                   $bancos[$lin_diario]=strtolower($_POST['txtTipoP'.$i]);

				    $bancos[$lin_diario];
                }
			}	
		  }
//print_r($idPlanCuentas);

            $sqlDLD2 = "insert into detalle_libro_diario ( id_libro_diario, id_plan_cuenta,
					debe, haber, id_periodo_contable) values ('".$id_libro_diario."','".$formas_pago_id_plan_cuenta."','".$txtValor."','".'0'."','".$sesion_id_periodo_contable."');";
            $resp2 = mysql_query($sqlDLD2) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');

		  for ($i=1; $i<=$lin_diario; $i++){
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
			}
		  } 
               
               
               
                for($i=1; $i<=$limite; $i++){


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
						//echo "no hay filas";
                        // no hay filas
                        //INSERCION DE LA TABLA MAYORIZACION
                        try {
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
                        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la insercion de la tabla mayorizacion: <?php echo "".$ex ?></p></div> <?php }

                    }
                }    
                
            $saldo = $txtDeudaTotal - $txtValor;
                if($saldo == 0){
                    $estadoCC = "Canceladas";
                }else{
                    $estadoCC = "Pendientes";
                }
 //           $sqlCuentaPagar = "update cuentas_por_pagar set saldo='".$saldo."', estado='".$estadoCC."', fecha_pago='".$fecha_pago."', numero_asiento='".$numero_asiento."', id_forma_pago='".$idFormaPago[0]."', banco_referencia='".$txtBancoReferencia."', documento_numero='".$txtDocumentoNumero."', id_plan_cuenta='".$formas_pago_id_plan_cuenta."' where id_cuenta_por_pagar='".$txtIdCuentaPagar."';";
                $sqlCuentaPagar = "update cuentas_por_pagar set saldo='".$saldo."', estado='".$estadoCC."', fecha_pago='".$fecha_pago."', numero_asiento='".$numero_asiento."', 
    id_forma_pago='0', banco_referencia='".$txtBancoReferencia."', documento_numero='".$txtDocumentoNumero."' where id_cuenta_por_pagar='".$txtIdCuentaPagar."';";

				$respCuentaPagar = mysql_query($sqlCuentaPagar);        

			
            if($resp && $respCuentaPagar){
                ?> <div class='transparent_ajax_correcto'><p>Registro insertado correctamente.</p></div> <?php
            }else{
                ?> <div class='transparent_ajax_error'><p>Error al guarda los datos: problemas en la consulta</p></div> <?php
            }

        }else{
            ?> <div class="transparent_ajax_error"><p>Fallo en el envio del Formulario: No hay datos</p></div> <?php
        }

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
		$switchsaldoanticipo=$_POST['switch-saldo-anticipo'];
		$cmbTipoDocumentoFVC='';
		$cmbTipoDocumentoFVC="Compra#";		
//		$numero_factura=$_POST['txtFactura'];
		$numero_factura=$_POST['txtComprobante'];
		$fecha_compra=$_POST['textFecha'];
		$total=$_POST['txtTotal'];
	//	$sub_total=$_POST['txtSubtotal'];
	//	$iva=$_POST['txtIdIva'];
	$tipo_comprobante = "Diario"; 
	
		$txtFormaPago=$_POST['txtFormaPago'];
		

		
		$id_proveedor=$_POST['cmbProveedor'];
		

		$txtFechaVencimiento=$_POST['txtFechaVencimiento'];

	 $seleccion=trim($_POST['seleccion']);
        $seleccion=($seleccion!='')?$seleccion:2;


		if($seleccion==1){
            //proveedores
            $cmbTipoDocumentoFVC="Compra#";		
                $sqlp="SELECT `id_proveedor`, `nombre_comercial` as apellido, '' as `nombre` FROM `proveedores` WHERE `id_proveedor`='".$id_proveedor."' AND id_empresa='".$sesion_id_empresa."';";
            }else if($seleccion==2){
            //clientes
            $cmbTipoDocumentoFVC="Factura No.";		
                $sqlp="Select id_cliente, nombre, apellido From clientes where id_empresa='".$sesion_id_empresa."' and id_cliente='".$id_proveedor."'  ;";                  
            }
            else if($seleccion==4){
            //empleados
            $cmbTipoDocumentoFVC="Anticipo#";		
                $sqlp="SELECT `id_empleado`, `nombre`, `apellido` FROM `empleados` WHERE `id_empleado`='".$id_proveedor."' AND `id_empresa`='".$sesion_id_empresa."' ";               
            }
            
        $resultp=mysql_query($sqlp);
        $txtNombreRUC="";
		while($rowp=mysql_fetch_array($resultp))//permite ir de fila en fila de la tabla
        {
			$txtNombreRUC=$rowp['apellido'].' '.$rowp['nombre'];
		}
	
		$numero_compra=$_POST['txtComprobante'];
		$formas_pago_id_plan_cuenta=0;

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
				$mod_date = strtotime($fecha_compra."+ ".$i." months");
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

                $idFormaPago = intval($txtFormaPago);
                $tipo_anticipo='0';
                
                if(isset($_POST['tipo_anticipo'])){
                    if(trim($_POST['tipo_anticipo'])!=''){
                        $tipo_anticipo=$_POST['tipo_anticipo'];
                    }
                }
                if($seleccion==1){
                    //proveedores
                    	$sql3 = "insert into cuentas_por_pagar (id_cuenta_por_pagar, tipo_documento,numero_compra, referencia, valor, saldo, numero_asiento, fecha_vencimiento, fecha_pago, id_proveedor, id_plan_cuenta, id_empresa, id_compra, estado,id_forma_pago,tipo_anticipo) "
					. "values ('".$id_cuenta_por_pagar."','".$cmbTipoDocumentoFVC."','".$numero_compra."','".$txtNombreRUC."','".$cuotax."','".$cuotax."','',		'".$fecha_nueva."', NULL, '".$id_proveedor."', '".$formas_pago_id_plan_cuenta."', '".$sesion_id_empresa."', NULL, '".$estadoCC."','".$idFormaPago."','".$tipo_anticipo."');";
					
                }else if($seleccion==2){
                    //clientes
                   $sql3 = "insert into cuentas_por_pagar (id_cuenta_por_pagar, tipo_documento,numero_compra, referencia, valor, saldo, numero_asiento, fecha_vencimiento, fecha_pago, id_cliente, id_plan_cuenta, id_empresa, id_compra, estado,id_forma_pago,tipo_anticipo) "
					. "values ('".$id_cuenta_por_pagar."','".$cmbTipoDocumentoFVC."','".$numero_compra."','".$txtNombreRUC."','".$cuotax."','".$cuotax."','',		'".$fecha_nueva."', NULL, '".$id_proveedor."', '".$formas_pago_id_plan_cuenta."', '".$sesion_id_empresa."', NULL, '".$estadoCC."','".$idFormaPago."','".$tipo_anticipo."');";
					
                }else if($seleccion==3){
                    //lead
                    $sql3 = "insert into cuentas_por_pagar (id_cuenta_por_pagar, tipo_documento,numero_compra, referencia, valor, saldo, numero_asiento, fecha_vencimiento, fecha_pago, id_lead, id_plan_cuenta, id_empresa, id_compra, estado,id_forma_pago) "
					. "values ('".$id_cuenta_por_pagar."','".$cmbTipoDocumentoFVC."','".$numero_compra."','".$txtNombreRUC."','".$cuotax."','".$cuotax."','',		'".$fecha_nueva."', NULL, '".$id_proveedor."', '".$formas_pago_id_plan_cuenta."', '".$sesion_id_empresa."', NULL, '".$estadoCC."','".$idFormaPago."');";
					
                }
                else if($seleccion==4){
                    //empleados
                    $sql3 = "insert into cuentas_por_pagar (id_cuenta_por_pagar, tipo_documento,numero_compra, referencia, valor, saldo, numero_asiento, fecha_vencimiento, fecha_pago, id_empleado, id_plan_cuenta, id_empresa, id_compra, estado,id_forma_pago) "
					. "values ('".$id_cuenta_por_pagar."','".$cmbTipoDocumentoFVC."','".$numero_compra."','".$txtNombreRUC."','".$cuotax."','".$cuotax."','',		'".$fecha_nueva."', NULL, '".$id_proveedor."', '".$formas_pago_id_plan_cuenta."', '".$sesion_id_empresa."', NULL, '".$estadoCC."','".$idFormaPago."');";
                }
			
            // echo $sql3;
             
            if($switchsaldoanticipo=='2'){
                
                     		$sql="Select * From enlaces_compras where tipo='Anticipo' and id_empresa='".$sesion_id_empresa."';";
                    		$resultado=mysql_query($sql) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;
                    		while($row=mysql_fetch_array($resultado))//permite ir de fila en fila de la tabla
                    		{
                    		    $formas_pago_id_plan_cuenta=$row['cuenta_contable'];
                    		    $formas_pago_i_nombre=$row['nombre'];
                    		}
                    		
                    	    $sqlCuenta="Select * From enlaces_compras where id='$idFormaPago' and id_empresa='".$sesion_id_empresa."';";
                    		$resultadoCuenta=mysql_query($sqlCuenta) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');;
                    		while($rowCuenta=mysql_fetch_array($resultadoCuenta))//permite ir de fila en fila de la tabla
                    		{
                    		    $formas_pago_tpo_cuenta=$rowCuenta['tipo'];
                    		    $formas_pago_cuenta_cntabl=$rowCuenta['cuenta_contable'];
                    		    $formas_pago_nombre=$rowCuenta['nombre'];
                                $formas_pago_porcentaje= $rowCuenta['porcentaje'];
                    		}
                    		
             
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
                                        
                                        $id_libro_diario='0';
                                        
                                        $cmbTipoDocumento=$formas_pago_tpo_cuenta;
                                        $txtNumeroDocumento=$_POST['txtReferencia'];
                                        $txtDetalleDocumento=$formas_pago_tpo_cuenta. ' a proveedor'  ;
                                        $txtFechaEmision=$_POST['txtFecha'];
                                        $txtFechaVencimiento=$_POST['txtFechaVencimiento'];
                                        $saldo_conciliado = 0;
                                        $valorConciliacion = $_POST['txtTotal'];
                                        $estado = "No Conciliado";
                                        
                                        $sqlb2 = "SELECT * FROM bancos WHERE id_plan_cuenta ='".$formas_pago_cuenta_cntabl."' 
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

                                        }else {
                                            
                                            $sqlB = "insert into bancos ( id_plan_cuenta, saldo_conciliado, id_periodo_contable) values 
                                            ('".$_POST['txtCodigoS'.$i]."','".$saldo_conciliado."', '".$sesion_id_periodo_contable."');";
                                            $resultB=mysql_query($sqlB);
                							$id_bancos=mysql_insert_id();

                                            $sqlDB = "insert into detalle_bancos ( tipo_documento, numero_documento, detalle, valor, fecha_cobro, fecha_vencimiento, id_bancos, estado, id_libro_diario) 
                                            values ('".$cmbTipoDocumento."','".$txtNumeroDocumento."','".$txtDetalleDocumento."','".$valorConciliacion."','".$txtFechaEmision."','".$txtFechaVencimiento."','".$id_bancos2."', '".$estado."', '".$id_libro_diario."');";
                                            $resultDB=mysql_query($sqlDB);
                							$id_detalle_banco=mysql_insert_id();

                                        }
    
            //   if($sesion_id_empresa==41){
                     try{
                    $sqlMNA="SELECT
                        max(numero_asiento) AS max_numero_asiento
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
                $tipo_comprobante = "Diario"; 
                 $sqlC = "insert into comprobantes ( tipo_comprobante, numero_comprobante, id_empresa) 
				values ('".$tipo_comprobante."','".$numero_comprobante."',
				'".$sesion_id_empresa."')";
                $respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
                $id_comprobante =mysql_insert_id();

                $id_proveedor= $_POST['cmbProveedor'];
                $sqlProveedor=" SELECT
                `id_proveedor` as id,
                `nombre_comercial`as nombre
            FROM
                `proveedores`
            WHERE
                `id_empresa`='".$sesion_id_empresa."' AND id_proveedor ='".$id_proveedor."' ";
                $resultProveedor = mysql_query($sqlProveedor);
                while($rowPro = mysql_fetch_array($resultProveedor) ){
                    $txtDeudor= $rowPro['nombre'];
                }
                $txtFacturaCpra=$_POST['txtReferencia'];
                $descripcion = " Pago de Factura No.".$txtFacturaCpra." a ".$txtDeudor;
                 $sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha,
				total_debe, total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante,tipo_mov,numero_cpra_vta) 
				values ('".$sesion_id_periodo_contable."','".$numero_asiento."','".$txtFechaEmision."',
				'".$total."','".$total."','".$descripcion."','".$numero_comprobante."',
				'".$tipo_comprobante."', '".$id_comprobante."', 'CP','".$id_cuenta_por_pagar."')";
                $resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
                $id_libro_diario = mysql_insert_id();
            
          
             $sqlDLD2 = "insert into detalle_libro_diario ( id_libro_diario, id_plan_cuenta,debe, haber, id_periodo_contable) values ('".$id_libro_diario."','".$formas_pago_cuenta_cntabl."','".$total."','".'0'."','".$sesion_id_periodo_contable."');";
            $resp2 = mysql_query($sqlDLD2) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');

            $sqlDLD2 = "insert into detalle_libro_diario ( id_libro_diario, id_plan_cuenta,
            debe, haber, id_periodo_contable) values ('".$id_libro_diario."','".$formas_pago_id_plan_cuenta."','0','".$total."','".$sesion_id_periodo_contable."');";
            $resp2 = mysql_query($sqlDLD2) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');

        $sql5 = "SELECT * FROM mayorizacion WHERE id_plan_cuenta ='".$formas_pago_id_plan_cuenta."' AND id_periodo_contable = '".$sesion_id_periodo_contable."';";
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
                            $sql6 = "insert into mayorizacion ( id_plan_cuenta, id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
                            $result6=mysql_query($sql6);
                        }
						catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la insercion de la tabla mayorizacion: <?php echo "".$ex ?></p></div> <?php }
                }
            //   }
      		
                
            }
            	$resp3 = mysql_query($sql3) or die('<div class="alert alert-danger"><p>Error al guardar en cuentas_por_cobrar: '.mysql_error().' </p></div>  ');
			}
			if($resp3)
			{
				?> <div class='alert alert-success'><p>***Registro cuentas por pagar guardado correctamente***.</p></div> <?php
			}
		}

		
		
	}	
   
?>


