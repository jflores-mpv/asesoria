<?php
    //Start session
    session_start();
    //Include database connection details
    require_once('../conexion.php');
    date_default_timezone_set('America/Guayaquil');
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $id_proveedor = $_POST['id_proveedor'];
	$tipo = $_POST['tipox'];
	$total_a_pagar = $_POST['total_a_pagar'];
	$tipoCuenta = $_POST['tipoCuenta'];
?>

<html>
<head>
<title>Pagar Cuentas</title>
<script src="https://kit.fontawesome.com/7816c50242.js" crossorigin="anonymous"></script>

    <script type="text/javascript">
    		$(document).ready(function(){
    		contador1=1
    		i=1;	
            for(i=1; i<= 5; i++)
    		{
    		 AgregarFilas_FP_Cpras()
            
            }
    $('#txtSubtotalInventarios').val( $('#txtSubtotalInventarios<?php echo $id_proveedor?>').val());
	$('#txtSubtotalProveeduria').val( $('#txtSubtotalProveeduria<?php echo $id_proveedor?>').val());
	$('#txtSubtotalServicios').val( $('#txtSubtotalServicios<?php echo $id_proveedor?>').val());

        });
    </script>    
</head>

<body onload="">
      <div class="modal-header">
        <a href="javascript: fn_cerrar();"><button type="button" class="close" ><span aria-hidden="true">&times;</span></button></a>
        <h4 class="modal-title" id="myModalLabel">Pagar varias cuenta</h4>
      </div>
      <form name="frmPagarCuentaPagarV" id="frmPagarCuentaPagarV" method="post" action="javascript: guardar_pago_cuenta_pagarV(1,txtTipoPago,txtTipoCuenta);"  >
          
<input type="hidden" id="txtSubtotalInventarios" name="txtSubtotalInventarios">
<input type="hidden" id="txtSubtotalProveeduria" name="txtSubtotalProveeduria">
<input type="hidden" id="txtSubtotalServicios" name="txtSubtotalServicios">
<input type="hidden" id="txtTotal" name="txtTotal" >
<input type="hidden" id="txtSubtotalFVC" name="txtSubtotalFVC" >
<input type="hidden" id="txtDebeFP" name="txtDebeFP" >
          
      <div class="modal-body">
             <div id="mensajePagarCuentaPagar" >     </div>
            <?php
            $id_compra=0;
            $id_proveedor = $_POST['id_proveedor'];
			$tipo = $_POST['tipox'];
			$total_a_pagar = $_POST['total_a_pagar'];
			$tipoCuenta = $_POST['tipoCuenta'];
            

			    if ($tipoCuenta ==1){
				$sql = "SELECT
				 proveedores.`nombre_comercial` AS proveedores_nombre_comercial,
				 sum(cuentas_por_pagar.`saldo`) AS cuentas_por_pagar_saldo,
				 cuentas_por_pagar.`id_proveedor` AS cuentas_por_pagar_id_proveedor,
				 cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
				cuentas_por_pagar.`id_plan_cuenta` AS cuentas_por_pagar_id_plan_cuenta,
        
				 cuentas_por_pagar.`estado` AS cuentas_por_pagar_estado,
				  cuentas_por_pagar.`id_compra` AS cuentas_por_pagar_id_compra
			FROM
				 `proveedores` proveedores INNER JOIN `cuentas_por_pagar` cuentas_por_pagar ON proveedores.`id_proveedor` = cuentas_por_pagar.`id_proveedor`
				  WHERE  cuentas_por_pagar.`id_empresa`= '".$sesion_id_empresa."' and 
				  cuentas_por_pagar.`id_proveedor`='".$id_proveedor."' and saldo>0 "." group by cuentas_por_pagar.id_empresa,
				  cuentas_por_pagar.id_proveedor, cuentas_por_pagar.`id_plan_cuenta` "; 
	  
				$result = mysql_query($sql) or die(mysql_error());
				while ($row = mysql_fetch_array($result)){                        
					$proveedores_nombre_comercial = $row['proveedores_nombre_comercial'];
						if ($tipo ==1){
						$cuentas_por_pagar_saldo = $row['cuentas_por_pagar_saldo']; 
						}else{
						    $cuentas_por_pagar_saldo =  $total_a_pagar;
						}
				    $id_compra= $row['cuentas_por_pagar_id_compra']; 
					$cuentas_por_pagar_id_proveedor = $row['cuentas_por_pagar_id_proveedor']; 
					$cuentas_por_pagar_id_plan_cuenta = $row['cuentas_por_pagar_id_plan_cuenta']; 
					$cuentas_por_pagar_id_cuenta_por_pagar = $row['cuentas_por_pagar_id_cuenta_por_pagar'];  
					$cuentas_por_pagar_numero_compra = $row['cuentas_por_pagar_numero_compra'];  
					
			
				}    
				
			}
			
			    if ($tipoCuenta ==2){
				$sql = "SELECT
				  CONCAT(clientes.`nombre` ,clientes.`apellido` ) 
				  AS proveedores_nombre_comercial,
				 sum(cuentas_por_pagar.`saldo`) AS cuentas_por_pagar_saldo,
				 cuentas_por_pagar.`id_cliente` AS cuentas_por_pagar_id_proveedor,
				 cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
				cuentas_por_pagar.`id_plan_cuenta` AS cuentas_por_pagar_id_plan_cuenta,
        
				 cuentas_por_pagar.`estado` AS cuentas_por_pagar_estado,
				 cuentas_por_pagar.`id_compra` AS cuentas_por_pagar_id_compra
			FROM
					`clientes` clientes  INNER JOIN `cuentas_por_pagar` cuentas_por_pagar 	ON clientes.`id_cliente` = cuentas_por_pagar.`id_cliente`
				  WHERE  cuentas_por_pagar.`id_empresa`= '".$sesion_id_empresa."' and 
				  cuentas_por_pagar.`id_cliente`='".$id_proveedor."' and saldo>0 "." group by cuentas_por_pagar.id_empresa,
				  cuentas_por_pagar.id_cliente, cuentas_por_pagar.`id_plan_cuenta` "; 
	  
				$result = mysql_query($sql) or die(mysql_error());
				while ($row = mysql_fetch_array($result)){                        
					$proveedores_nombre_comercial = $row['proveedores_nombre_comercial'];
						if ($tipo ==1){
						$cuentas_por_pagar_saldo = $row['cuentas_por_pagar_saldo']; 
						}else{
						    $cuentas_por_pagar_saldo =  $total_a_pagar;
						}
				    $id_compra= $row['cuentas_por_pagar_id_compra'];
					$cuentas_por_pagar_id_proveedor = $row['cuentas_por_pagar_id_proveedor']; 
					$cuentas_por_pagar_id_plan_cuenta = $row['cuentas_por_pagar_id_plan_cuenta']; 
					$cuentas_por_pagar_id_cuenta_por_pagar = $row['cuentas_por_pagar_id_cuenta_por_pagar'];  
					$cuentas_por_pagar_numero_compra = $row['cuentas_por_pagar_numero_compra'];  
					
			
				}    
				
			}
			
			    if ($tipoCuenta ==3){
				$sql = "SELECT
				 leads.`name` AS proveedores_nombre_comercial,
				 sum(cuentas_por_pagar.`saldo`) AS cuentas_por_pagar_saldo,
				 cuentas_por_pagar.`id_lead` AS cuentas_por_pagar_id_proveedor,
				 cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
				cuentas_por_pagar.`id_plan_cuenta` AS cuentas_por_pagar_id_plan_cuenta,
        
				 cuentas_por_pagar.`estado` AS cuentas_por_pagar_estado,
				 cuentas_por_pagar.`id_compra` AS cuentas_por_pagar_id_compra
			FROM
				 `leads` leads INNER JOIN `cuentas_por_pagar` cuentas_por_pagar ON leads.`id` = cuentas_por_pagar.`id_lead`
				  WHERE  cuentas_por_pagar.`id_empresa`= '".$sesion_id_empresa."' and 
				  cuentas_por_pagar.`id_lead`='".$id_proveedor."' and saldo>0 "." group by cuentas_por_pagar.id_empresa,
				  cuentas_por_pagar.id_lead, cuentas_por_pagar.`id_plan_cuenta` "; 

				$result = mysql_query($sql) or die(mysql_error());
				while ($row = mysql_fetch_array($result)){                        
					$proveedores_nombre_comercial = $row['proveedores_nombre_comercial'];
						if ($tipo ==1){
						$cuentas_por_pagar_saldo = $row['cuentas_por_pagar_saldo']; 
						}else{
						    $cuentas_por_pagar_saldo =  $total_a_pagar;
						}
				    $id_compra= $row['cuentas_por_pagar_id_compra'];
					$cuentas_por_pagar_id_proveedor = $row['cuentas_por_pagar_id_proveedor']; 
					$cuentas_por_pagar_id_plan_cuenta = $row['cuentas_por_pagar_id_plan_cuenta']; 
					$cuentas_por_pagar_id_cuenta_por_pagar = $row['cuentas_por_pagar_id_cuenta_por_pagar'];  
					$cuentas_por_pagar_numero_compra = $row['cuentas_por_pagar_numero_compra'];  
		//	echo	$cuentas_por_pagar_id_proveedor;	
			
				}    
				
			}
			if ($tipoCuenta ==4){
				$sql = "SELECT
				 empleados.`nombre` AS proveedores_nombre_comercial,
				 sum(cuentas_por_pagar.`saldo`) AS cuentas_por_pagar_saldo,
				 cuentas_por_pagar.`id_empleado` AS cuentas_por_pagar_id_proveedor,
				 cuentas_por_pagar.`id_empresa` AS cuentas_por_pagar_id_empresa,
				cuentas_por_pagar.`id_plan_cuenta` AS cuentas_por_pagar_id_plan_cuenta,
        
				 cuentas_por_pagar.`estado` AS cuentas_por_pagar_estado,
				 cuentas_por_pagar.`id_compra` AS cuentas_por_pagar_id_compra
			FROM
				  `empleados` empleados INNER JOIN `cuentas_por_pagar` cuentas_por_pagar ON  empleados.`id_empleado` = cuentas_por_pagar.`id_empleado`
				  WHERE  cuentas_por_pagar.`id_empresa`= '".$sesion_id_empresa."' and 
				  cuentas_por_pagar.`id_empleado`='".$id_proveedor."' and saldo>0 "." group by cuentas_por_pagar.id_empresa,
				  cuentas_por_pagar.id_empleado, cuentas_por_pagar.`id_plan_cuenta` "; 

				$result = mysql_query($sql) or die(mysql_error());
				while ($row = mysql_fetch_array($result)){                        
					$proveedores_nombre_comercial = $row['proveedores_nombre_comercial'];
						if ($tipo ==1){
						$cuentas_por_pagar_saldo = $row['cuentas_por_pagar_saldo']; 
						}else{
						    $cuentas_por_pagar_saldo =  $total_a_pagar;
						}
				    $id_compra= $row['cuentas_por_pagar_id_compra'];
					$cuentas_por_pagar_id_proveedor = $row['cuentas_por_pagar_id_proveedor']; 
					$cuentas_por_pagar_id_plan_cuenta = $row['cuentas_por_pagar_id_plan_cuenta']; 
					$cuentas_por_pagar_id_cuenta_por_pagar = $row['cuentas_por_pagar_id_cuenta_por_pagar'];  
					$cuentas_por_pagar_numero_compra = $row['cuentas_por_pagar_numero_compra'];  
		//	echo	$cuentas_por_pagar_id_proveedor;	
			
				}    
				
			}	
				if(trim($id_compra)==''){
				    $id_compra=0;
				}
			?>          
            
        <div class="row">
            <div class="col-lg-3">
                <input type="hidden" name="txtTipoPago" id="txtTipoPago" class="bg-danger" value="<?php echo $tipo;?>" />
                <input type="hidden" name="txtIdProveedor" id="txtIdProveedor" value="<?php echo $cuentas_por_pagar_id_proveedor;?>" />
                <input type="hidden" name="txtIdPlanCuentas" id="txtIdPlanCuentas" value="<?php echo $cuentas_por_pagar_id_plan_cuenta;?>" />
                <input type="hidden" name="txtIdCuentaPagar" id="txtIdCuentaPagar" value="<?php echo $cuentas_por_pagar_id_cuenta_por_pagar;?>" />
                <input type="hidden" name="txtFactura" id="txtFactura" value="<?php echo $cuentas_por_pagar_numero_compra;?>" />
                <input type="hidden" name="txtTipoCuenta" id="txtTipoCuenta" value="<?php echo $tipoCuenta;?>" class="bg-danger"/>
                <input type="hidden" name="id_compra" id="id_compra" value="<?php echo $id_compra;?>" />
             

                <label for="txtDeudor"><strong class="leftSpace">Acreedor:  </strong></label><br>
				<input type="text" tabindex="1" value="<?php echo $proveedores_nombre_comercial;?>" class="form-control " id="txtDeudor" name="txtDeudor" title="Deudor" required="required"  readonly="true"  />	
            </div>
            <div class="col-lg-2">
                <label for="txtDeudaTotal"><strong class="leftSpace">Saldo:  </strong></label>                                                              
                <input type="text" tabindex="2" value="<?php echo $cuentas_por_pagar_saldo;?>" class="form-control " id="txtDeudaTotal" name="txtDeudaTotal" title="Deuda Total" required="required" autocomplete="off" readonly="true"  />
            </div>
			<div class="col-lg-2">
                    <label for="txtPagoFP"><strong class="leftSpace">Cobro:  </strong></label>                                                              
                    <input type="text" tabindex="2"  class="form-control " id="txtPagoFP" name="txtPagoFP" title="Deuda Total" required="required" autocomplete="off" readonly="true"  />
            </div>

			<div class="col-lg-2">
                    <label for="txtCambioFP"><strong class="leftSpace">Saldo x pagar:  </strong></label>                                                              
                    <input type="text" tabindex="2"  class="form-control " id="txtCambioFP" name="txtCambioFP" title="Saldo Pendiente" autocomplete="off" readonly="true"  />
            </div>

			<div class="col-lg-3">
                     <label for="txtFechaPago"><strong class="leftSpace">Fecha de pago  </strong></label><img src="images/b_calendar.png" alt="" width="16" height="16" class="Estilo15" onClick="displayCalendar(txtFechaPago,'yyyy-mm-dd',this)"/><br>
                    <input type="text" tabindex="3" value="<?php echo date("Y-m-d",time()); ?>" class="form-control " id="txtFechaPago"  name="txtFechaPago" placeholder="Fecha de pago" required="required" autocomplete="off" maxlength="10" readonly="true" onClick="displayCalendar(txtFechaPago,'yyyy-mm-dd',this)"  />
                    <div id="validaPagoMin"></div>
            </div>            
        </div>
        
		<div class="row mt-5 border py-3">
		    
			  <div class='col-lg-1 '></div>
				<div class='col-lg-1 '>Pago:</div>
				<div class='col-lg-3 '>Descripcion Pago</div>
                <div class='col-lg-1 '> Cpte #</div>									
				<div class='col-lg-1 '>    %   </div>
				<div class='col-lg-2 '>Valor</div>
				
		    <!--	<th type="hidden"><strong>Cuenta</strong></th> -->
			<!--	<div class='col-lg-1 '># de Cuotas</div>-->
			<!--	<th ><strong>Dias Plazo</strong></th> --->
			<!--	<div class='col-lg-1 '>Fecha Inicio</div>-->
											
			</div>						
		 	<div id="tablita" class="row bg-white" ></div>	

	</div>
    <div class="modal-footer">
        <div class="col-lg-3">
            <input  type="submit" value="Realizar Pago" tabindex="10" id="submitPCC" class="btn btn-success" name="btnEnviar"  />
        </div>
    </div>
</form>       

</body>  
<script>
		$(document).ready(function(){
			let subtotal = $('#txtDeudaTotal').val();
			
			$('#txtSubtotalFVC').val(subtotal);
		
			$('#txtDebeFP').val(subtotal);
			$('#txtTotal').val(subtotal);
		});

	

</script>
</html>
