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
//echo "oooooo";
//echo $accion;
if($accion == "1"){

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
        $txtValor = ($_POST['txtValor']);
        $txtEfectivo = ($_POST['txtEfectivo']);
        $txtSaldo= ($_POST['txtSaldo']);
        $txtIdCuentaCobrar = ($_POST['txtIdCuentaCobrar']);
        $txtDeudaTotal = ($_POST['txtDeudaTotal']);
        $txtDeudor = ($_POST['txtDeudor']);
        $fecha_pago = date("Y-m-d h:i:s");
                
        if($txtIdCliente != "" && $cmbFormaPagoFP != "" && $txtValor != "") {
            /*
            //permite sacar el id maximo de detalle_cuentas_por_cobrar
            try {
                $sqli="Select max(id_detalle_cuentas_por_cobrar) From detalle_cuentas_por_cobrar";
                $result=mysql_query($sqli);
                $id_detalle_cuentas_por_cobrar=0;
                while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                {
                    $id_detalle_cuentas_por_cobrar=$row['max(id_detalle_cuentas_por_cobrar)'];
                }
                $id_detalle_cuentas_por_cobrar++;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }

            $sql = "insert into detalle_cuentas_por_cobrar (id_detalle_cuentas_por_cobrar, id_forma_pago, banco_referencia, documento_numero, valor, efectivo, saldo, fecha_pago, id_plan_cuenta, id_cliente, id_cuenta_por_cobrar) "
                    . "values ('".$id_detalle_cuentas_por_cobrar."','".$idFormaPago[0]."','".$txtBancoReferencia."','".$txtDocumentoNumero."','".$txtValor."','".$txtEfectivo."','".$txtSaldo."','".$txtFechaPago." ".$hora."','".$txtIdPlanCuentas."', '".$txtIdCliente."', '".$txtIdCuentaCobrar."'); ";
            $resp = mysql_query($sql);
            
             $saldo = $txtDeudaTotal - $txtValor;
            if($saldo == 0){
                $estadoCC = "Canceladas";
            }else{
                $estadoCC = "Pendientes";
            }
            $sqlCuentaCobrar = "update cuentas_por_cobrar set saldo='".$saldo."', estado='".$estadoCC."' where id_cuenta_por_cobrar='".$txtIdCuentaCobrar."';";
            $respCuentaCobrar = mysql_query($sqlCuentaCobrar);
            
            */
            
            
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
                $resultMNA=mysql_query($sqlMNA) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
                $numero_asiento=0;
                while($rowMNA=mysql_fetch_array($resultMNA))//permite ir de fila en fila de la tabla
                {
                    $numero_asiento=$rowMNA['max_numero_asiento'];
                }
                $numero_asiento++;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
                      
            
            //permite sacar el id maximo de libro_diario
            try{
                $sqlm="Select max(id_libro_diario) From libro_diario";
                $resultm=mysql_query($sqlm) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
                $id_libro_diario=0;
                while($rowm=mysql_fetch_array($resultm))//permite ir de fila en fila de la tabla
                {
                    $id_libro_diario=$rowm['max(id_libro_diario)'];
                }
                $id_libro_diario++;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }
                    
     
                    
                    
                    
                // CONSULTA A LA TABLA FORMAS DE PAGO
                $sqlFP = "SELECT
                formas_pago.`id_forma_pago` AS formas_pago_id_forma_pago,
                formas_pago.`nombre` AS formas_pago_nombre,
                formas_pago.`id_plan_cuenta` AS formas_pago_id_plan_cuenta,
                formas_pago.`id_empresa` AS formas_pago_id_empresa,
                formas_pago.`id_tipo_movimiento` AS formas_pago_id_tipo_movimiento,
                formas_pago.`diario` AS formas_pago_diario,
                formas_pago.`ingreso` AS formas_pago_ingreso,
                formas_pago.`egreso` AS formas_pago_egreso
           FROM
                `formas_pago` formas_pago 
                Where formas_pago.`id_empresa`='".$sesion_id_empresa."' and formas_pago.`id_forma_pago`='".$idFormaPago[0]."';";
                $resultFP=mysql_query($sqlFP) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');

                while($rowFP=mysql_fetch_array($resultFP))//permite ir de fila en fila de la tabla
                {
                    $formas_pago_diario=$rowFP['formas_pago_diario'];
                    $formas_pago_ingreso=$rowFP['formas_pago_ingreso'];
                    $formas_pago_egreso=$rowFP['formas_pago_egreso'];
            /*        $formas_pago_id_plan_cuenta=$rowFP['formas_pago_id_plan_cuenta'];*/   
                    $formas_pago_id_plan_cuenta=$rowFP['formas_pago_id_plan_cuenta'];
                    
                }
                
                              // CONSULTA A LA TABLA FORMAS DE PAGO
                $sqlFP = "SELECT
                formas_pago.`id_forma_pago` AS formas_pago_id_forma_pago,
                formas_pago.`nombre` AS formas_pago_nombre,
                formas_pago.`id_plan_cuenta` AS formas_pago_id_plan_cuenta,
                formas_pago.`id_empresa` AS formas_pago_id_empresa,
                formas_pago.`id_tipo_movimiento` AS formas_pago_id_tipo_movimiento,
                formas_pago.`diario` AS formas_pago_diario,
                formas_pago.`ingreso` AS formas_pago_ingreso,
                formas_pago.`egreso` AS formas_pago_egreso,
                formas_pago.`tipo` AS formas_tipo
           FROM
                `formas_pago` formas_pago 
                Where formas_pago.`id_empresa`='".$sesion_id_empresa."' and  formas_pago.`id_tipo_movimiento`='4';";
                $resultFP=mysql_query($sqlFP) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
                $id_plan_cuenta_credito="";
                while($rowFP=mysql_fetch_array($resultFP))//permite ir de fila en fila de la tabla
                {

                    $id_plan_cuenta_credito=$rowFP['formas_pago_id_plan_cuenta'];
                    
                }
                
                
                
                

                // BUSCA QUE TIPO DE COMPROBANTE ES
                switch ("Si"){
                    case $formas_pago_diario:  {
                        $tipo_comprobante = "Diario"; 
                        break;
                    }

                    case $formas_pago_ingreso: {
                        $tipo_comprobante = "Ingreso"; 
                        break;
                    }               
                    case $formas_pago_egreso: {
                        $tipo_comprobante = "Egreso"; 
                        break;
                    }                                
                }
                
                
                // ACTUALIZA LA TABLA CUENTAS POR COBRAR
            
                $saldo = $txtDeudaTotal - $txtValor;
                if($saldo == 0){
                    $estadoCC = "Canceladas";
                }else{
                    $estadoCC = "Pendientes";
                }
            /*    $sqlCuentaCobrar = "update cuentas_por_cobrar set saldo='".$saldo."', estado='".$estadoCC."', fecha_pago='".$fecha_pago."', 
                numero_asiento='".$numero_asiento."', id_forma_pago='".$idFormaPago[0]."', banco_referencia='".$txtBancoReferencia."', 
                documento_numero='".$txtDocumentoNumero."', id_plan_cuenta='".$formas_pago_id_plan_cuenta."' 
                where id_cuenta_por_cobrar='".$txtIdCuentaCobrar."';"; */
                $sqlCuentaCobrar = "update cuentas_por_cobrar set saldo='".$saldo."', estado='".$estadoCC."', fecha_pago='".$fecha_pago."', 
                numero_asiento='".$numero_asiento."', id_forma_pago='".$formas_pago_id_plan_cuenta."', banco_referencia='".$txtBancoReferencia."', 
                documento_numero='".$txtDocumentoNumero."', id_plan_cuenta='".$id_plan_cuenta_credito."' 
                where id_cuenta_por_cobrar='".$txtIdCuentaCobrar."';";
                
                $respCuentaCobrar = mysql_query($sqlCuentaCobrar);            

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
                $descripcion = $txtDeudor." Cobros de las Cuentas por Cobrar Generado Automaticamente";
                
                $debe = $txtValor;
                $haber1 = $txtValor;
                //$haber2 = $total_iva;
                $total_debe = $debe;
                $total_haber = $haber1 ;

                //GUARDA EN  COMPROBANTES
                $sqlC = "insert into comprobantes (id_comprobante, tipo_comprobante, numero_comprobante, id_empresa) values 
                ('".$id_comprobante."','".$tipo_comprobante."','".$numero_comprobante."','".$sesion_id_empresa."')";
                
                $respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');

                //GUARDA EN EL LIBRO DIARIO
                $sqlLD = "insert into libro_diario (id_libro_diario, id_periodo_contable, numero_asiento, fecha, total_debe, total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante) 
                values ('".$id_libro_diario."','".$sesion_id_periodo_contable."','".$numero_asiento."','".$fecha."','".$total_debe."','".$total_haber."','".$descripcion."','".$numero_comprobante."','".$tipo_comprobante."', '".$id_comprobante."')";
                $resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');

       //        echo $sqlLD; 
                
                //************************************    GUARDA EN EL DETALLE LIBRO DIARIO      **********************
                                        
                $idPlanCuentas[1] = $formas_pago_id_plan_cuenta;
            //    $idPlanCuentas[2] = $txtIdPlanCuentas;    
            $idPlanCuentas[2] = $id_plan_cuenta_credito;    
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

                for($i=1; $i<=$limite; $i++){

                    //permite sacar el id maximo de detalle_libro_diario
                    try {
                        $sqli="Select max(id_detalle_libro_diario) From detalle_libro_diario";
                        $resultmd=mysql_query($sqli);
                        $id_detalle_libro_diario=0;
                        while($row=mysql_fetch_array($resultmd))//permite ir de fila en fila de la tabla
                        {
                              $id_detalle_libro_diario=$row['max(id_detalle_libro_diario)'];
                        }
                        $id_detalle_libro_diario++;

                    }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max detalle_libro_diario: <?php echo "".$ex ?></p></div> <?php }


                    //GUARDA EN EL DETALLE LIBRO DIARIO
                    $sqlDLD = "insert into detalle_libro_diario (id_detalle_libro_diario,       id_libro_diario,       id_plan_cuenta,               debe,              haber,           id_periodo_contable) 
                                                      values ('".$id_detalle_libro_diario."','".$id_libro_diario."','".$idPlanCuentas[$i]."','".$debeVector[$i]."','".$haberVector[$i]."','".$sesion_id_periodo_contable."');";
                    
//echo "<br>"." detalle libro diario  ".$sqlDLD;   
					
					
					$resp2 = mysql_query($sqlDLD) or die('<div class="alert alert-danger"><p>Error: '.mysql_error().' </p></div>  ');

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

                    }else {
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

                            $sql6 = "insert into mayorizacion (id_mayorizacion, id_plan_cuenta, id_periodo_contable) values 
                            ('".$id_mayorizacion."','".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
                            $result6=mysql_query($sql6);
                            
//echo "<br>"." mayorizacion  ".$sql6;                     

                        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la insercion de la tabla mayorizacion: <?php echo "".$ex ?></p></div> <?php }

                    }
                }    
//echo "<br>"." resp  ".$resp; 
//echo "<br>"." resp  ".$respCuentaCobrar; 
            
            if($resp && $respCuentaCobrar){
                ?> <div class='alert alert-success'><p>Registro insertado correctamente.</p></div> <?php
            }else{
                ?> <div class='alert alert-danger'><p>Error al guarda los datos: problemas en la consulta</p></div> <?php
            }

        }else{
            ?> <div class="transparent_ajax_error"><p>Fallo en el envio del Formulario: No hay datos</p></div> <?php
        }

    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }

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


    if($accion == "5"){
        $idCuenta = $_POST['idCuenta'];

        $sqlCC="SELECT `id_cuenta_por_cobrar`, `tipo_documento`, `numero_factura`, `referencia`, `valor`, `saldo`, `numero_asiento`, `fecha_vencimiento`, `fecha_pago`, `id_cliente`, `id_proveedor`, `id_empleado`, `id_lead`, `id_plan_cuenta`, `id_empresa`, `id_venta`, `estado`, `id_forma_pago`, `banco_referencia`, `documento_numero`, `cuotaAdmin`, `motivoDescuento` FROM `cuentas_por_cobrar` WHERE  id_empresa = $sesion_id_empresa AND  id_cuenta_por_cobrar=$idCuenta ";
        $resultCC=mysql_query($sqlCC);
        while($rowCC = mysql_fetch_array($resultCC)){

            if(trim($rowCC['tipo_documento'])=='1' && trim($rowCC['id_venta']) !='' ){
                     $sqlVenta = "SELECT `id_venta`, `fecha_venta`, `estado`, `numero_factura_venta`, `fecha_anulacion`, `id_empresa`, `tipo_documento`, `Autorizacion`, `FechaAutorizacion`, `ClaveAcceso` FROM `ventas` WHERE id_venta=".$rowCC['id_venta'];
                    $resultVenta = mysql_query($sqlVenta);
                    
                    while($rowV = mysql_fetch_array($resultVenta)){
                        if($rowV['estado']=='Activo'){
                                echo '4';
                                exit;
                        }
                    }
            }
            if(trim($rowCC['tipo_documento'])=='Anticipo#' && trim($rowCC['id_venta']) !='' ){

                $sqlCompra = "SELECT `id_compra`, `anulado` FROM `compras` WHERE id_empresa = $sesion_id_empresa AND  id_compra=".$rowCC['id_venta'];
               $resultCompra = mysql_query($sqlCompra);
               
               while($rowC = mysql_fetch_array($resultCompra)){
                   if($rowC['anulado']=='0'){
                           echo '5';
                           exit;
                   }
               }
       }

            if($rowCC['valor']!=$rowCC['saldo']){
                echo '3';// existen pagos 
                exit;
            }
        }

            $sql="DELETE FROM `cuentas_por_cobrar` WHERE `id_cuenta_por_cobrar`=$idCuenta ";
            $result = mysql_query($sql);
            if($result){
                $sql2="DELETE FROM `detalle_cuentas_por_cobrar` WHERE `id_cuenta_por_cobrar`=$idCuenta";
                $result2 = mysql_query($sql2);
                echo '1';
            }else{
                echo '2';
            }
       
    }

 if ($accion=="6"){  $response = array();
        $switchsaldoanticipo=$_POST['switch-saldo-anticipo'];

		$cmbTipoDocumentoFVC='';
		
		$numero_factura=$_POST['txtComprobante'];
		$fecha_compra=$_POST['textFecha'];
		$total=$_POST['txtTotal'];
		$id_cliente=$_POST['cmbProveedor'];
		$txtFechaVencimiento=$_POST['txtFechaVencimiento'];
		
         $seleccion=trim($_POST['seleccion']);
        $seleccion=($seleccion!='')?$seleccion:2;
        
        if($switchsaldoanticipo==2){
            
             $tipo_comprobante = "Egreso"; 
        }else{
             $tipo_comprobante = "Diario"; 
        }
       
        $txtFormaPago=$_POST['txtFormaPago'];
        $seleccion_nombre = '' ;
        if($seleccion==1){
        //proveedores
        $seleccion_nombre = ' a proveedor' ;
        $cmbTipoDocumentoFVC="Compra#";		
            $sqlp="SELECT `id_proveedor`, `nombre_comercial` as apellido, `nombre` FROM `proveedores` WHERE `id_proveedor`='".$id_cliente."' AND id_empresa='".$sesion_id_empresa."';";
        }else if($seleccion==2){
        //clientes
        $seleccion_nombre = ' a cliente' ;
        $cmbTipoDocumentoFVC="1";		
            $sqlp="Select id_cliente, nombre, apellido From clientes where id_empresa='".$sesion_id_empresa."' and id_cliente='".$id_cliente."'  ;";                  
        }
        else if($seleccion==4){
        //empleados
         $seleccion_nombre = ' a empleado' ;
        $cmbTipoDocumentoFVC="Anticipo#";		
            $sqlp="SELECT `id_empleado`, `nombre`, `apellido` FROM `empleados` WHERE `id_empleado`='".$id_cliente."' AND `id_empresa`='".$sesion_id_empresa."' ";               
        }

		
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
				
				$idFormaPago = intval($txtFormaPago);
                $tipo_anticipo='0';
                $nombreAnticipo = '';
                if(isset($_POST['tipo_anticipo'])){
                    if(trim($_POST['tipo_anticipo'])!=''){
                        $tipo_anticipo=$_POST['tipo_anticipo'];
                        
                        $sqlBuscarAnticipo="SELECT `id_tipo_anticipo`, `nombre_anticipo`, `id_empresa`, `tipo`, `objetivo` FROM `tipo_anticipo` WHERE id_tipo_anticipo=$tipo_anticipo";
                        $resultBuscarAnticipo = mysql_query($sqlBuscarAnticipo);
                        while($rowBA = mysql_fetch_array($resultBuscarAnticipo) ){
                            $nombreAnticipo = $rowBA['nombre_anticipo'];
                        }
                    }
                }
                
                if($seleccion==1){
                    //proveedores
                    $sql3 = "insert into cuentas_por_cobrar (       tipo_documento,           numero_factura,	      referencia,          valor,        saldo, 	numero_asiento, fecha_vencimiento, fecha_pago, id_proveedor, id_plan_cuenta,	id_empresa, id_venta, estado,id_cliente,tipo_anticipo) ". 
						"values                     ('".$cmbTipoDocumentoFVC."','".$numero_factura."',	'".$txtNombreFVC." ','".$cuotax."','".$cuotax."','',             '".$fecha_nueva."', NULL,      '".$id_cliente."', '".$formas_pago_id_plan_cuenta."', 	'".$sesion_id_empresa."', NULL, '".$estadoCC."',0,'".$tipo_anticipo."');";
                }else if($seleccion==2){
                    //clientes
                    $sql3 = "insert into cuentas_por_cobrar (       tipo_documento,           numero_factura,	      referencia,          valor,        saldo, 	numero_asiento, fecha_vencimiento, fecha_pago, id_cliente, id_plan_cuenta,	id_empresa, id_venta, estado,tipo_anticipo) ". 
						"values                     ('".$cmbTipoDocumentoFVC."','".$numero_factura."',	'".$txtNombreFVC." ','".$cuotax."','".$cuotax."','',             '".$fecha_nueva."', NULL,      '".$id_cliente."', '".$formas_pago_id_plan_cuenta."', 	'".$sesion_id_empresa."', NULL, '".$estadoCC."','".$tipo_anticipo."');";
                }else if($seleccion==3){
                    //lead
                    $sql3 = "insert into cuentas_por_cobrar (        tipo_documento,           numero_factura,	      referencia,          valor,        saldo, 	numero_asiento, fecha_vencimiento, fecha_pago, id_lead, id_plan_cuenta,	id_empresa, id_venta, estado,id_cliente) ". 
						"values                     ('".$cmbTipoDocumentoFVC."','".$numero_factura."',	'".$txtNombreFVC." ','".$cuotax."','".$cuotax."','',             '".$fecha_nueva."', NULL,      '".$id_cliente."', '".$formas_pago_id_plan_cuenta."', 	'".$sesion_id_empresa."', NULL, '".$estadoCC."',0);";
                }
                else if($seleccion==4){
                    //empleados
                    $sql3 = "insert into cuentas_por_cobrar (        tipo_documento,           numero_factura,	      referencia,          valor,        saldo, 	numero_asiento, fecha_vencimiento, fecha_pago, id_empleado, id_plan_cuenta,	id_empresa, id_venta, estado,id_cliente) ". 
						"values                     ('".$cmbTipoDocumentoFVC."','".$numero_factura."',	'".$txtNombreFVC." ','".$cuotax."','".$cuotax."','',             '".$fecha_nueva."', NULL,      '".$id_cliente."', '".$formas_pago_id_plan_cuenta."', 	'".$sesion_id_empresa."', NULL, '".$estadoCC."',0);";
                }	
                $resp3 = mysql_query($sql3) or die('<div class="alert alert-success"><p>Error al guardar en cuentas_por_cobrar: '.mysql_error().' </p></div>  ');
                $id_cuenta_por_cobrar= mysql_insert_id();
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
    //   $tipo_comprobante = "Diario"; 
       $sqlC = "insert into comprobantes ( tipo_comprobante, numero_comprobante, id_empresa) 
       values ('".$tipo_comprobante."','".$numero_comprobante."',
       '".$sesion_id_empresa."')";
       $respC = mysql_query($sqlC) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
       $id_comprobante =mysql_insert_id();

       $id_proveedor= $_POST['cmbProveedor'];
     
       $txtDeudor=$txtNombreFVC;
       $txtFacturaCpra=$_POST['txtReferencia'];
       
       if($switchsaldoanticipo==2 ){
            
            $descripcion = " Anticipo $nombreAnticipo  $seleccion_nombre ".$txtDeudor ." Según Cheque: " . $txtNumeroDocumento;
        }else{
             $descripcion = " Pago de Factura No.".$txtFacturaCpra." a ".$txtDeudor;
        }
        
      
       $sqlLD = "insert into libro_diario ( id_periodo_contable, numero_asiento, fecha,
       total_debe, total_haber, descripcion, numero_comprobante, tipo_comprobante, id_comprobante) 
       values ('".$sesion_id_periodo_contable."','".$numero_asiento."','".$txtFechaEmision."',
       '".$total."','".$total."','".$descripcion."','".$numero_comprobante."',
       '".$tipo_comprobante."', '".$id_comprobante."')";
       $resp = mysql_query($sqlLD) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
       $id_libro_diario = mysql_insert_id();
   
 
   

     $sqlDLD2 = "insert into detalle_libro_diario ( id_libro_diario, id_plan_cuenta,debe, haber, id_periodo_contable) values ('".$id_libro_diario."','".$formas_pago_cuenta_cntabl."','0','".$total."','".$sesion_id_periodo_contable."');";
  

 
  $resp2 = mysql_query($sqlDLD2) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');
  
   $sqlDLD2 = "insert into detalle_libro_diario ( id_libro_diario, id_plan_cuenta,
   debe, haber, id_periodo_contable) values ('".$id_libro_diario."','".$formas_pago_id_plan_cuenta."','".$total."','0','".$sesion_id_periodo_contable."');";
   $resp2 = mysql_query($sqlDLD2) or die('<div class="transparent_ajax_error"><p>Error: '.mysql_error().' </p></div>  ');

$sql5 = "SELECT * FROM mayorizacion WHERE id_plan_cuenta ='".$formas_pago_id_plan_cuenta."' AND id_periodo_contable = '".$sesion_id_periodo_contable."';";
                  
       $result5=mysql_query($sql5);
       while($row5=mysql_fetch_array($result5))//permite ir de fila en fila de la tabla
       {
           $id_mayorizacion=$row5['id_mayorizacion'];
       }
       $numero = mysql_num_rows($result5); // obtenemos el número de filas
       if($numero == 0){
               try 
               {
                   $sql6 = "insert into mayorizacion ( id_plan_cuenta, id_periodo_contable) values ('".$idPlanCuentas[$i]."','".$sesion_id_periodo_contable."');";
                   $result6=mysql_query($sql6);
               }
               catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la insercion de la tabla mayorizacion: <?php echo "".$ex ?></p></div> <?php }
       } 
       
       $sqlActualizarCC="UPDATE `cuentas_por_cobrar` SET `numero_asiento`='".$numero_asiento."' WHERE id_cuenta_por_cobrar=$id_cuenta_por_cobrar";
       $resultActualizarCC = mysql_query($sqlActualizarCC);
   }					
				
			}
			 $response['sqlActualizarCC'] = $sqlActualizarCC;
			 $response['tipo_comprobante'] = $tipo_comprobante;
			 $response['guardo'] = 'no';
			  $response['numero_comprobante'] = $numero_comprobante;
			if($resp3)
			{
			  
			   $response['guardo'] = 'si';
			    $response['mensaje'] = "<div class='alert alert-success'><p>***Registro cuentas por cobrar guardado correctamente***.</p></div>";
			    
	            
			}
			echo json_encode($response);
		}

	}
   
if($accion == "7"){
        $id_proveedor =$_POST['id_proveedor'];
    $response = array();
    $response['tabla']='';
    $response['cantidad_anticipos']=0;
    $output='';

    
        $response['cantidad_anticipos']=1;
        $suma_anticipos = $_POST['sumatoriaSaldo'];
        $response['tabla']='<div class="input-group  celeste p-2 rounded"><span class="input-group-text"   >Total Anticipos:</span>
    <input name="apagar_valor_anticipo0" id="apagar_valor_anticipo0" type="text" class="form-control " value="0" onchange="validar_saldo_anticipo(0)">
    <span class="input-group-text" >Disponible: $</span>
    <input name="limite_valor_anticipo0" id="limite_valor_anticipo0" type="text" class="form-control " value="'.$suma_anticipos.'" readonly=""> </div><input name="cantidadFilasAnticipos" id="cantidadFilasAnticipos" type="hidden" class="form-control " value="1" readonly="">';

    

    
    echo json_encode( $response );
    }
?>


