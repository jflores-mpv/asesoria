<?php
	
    //Start session
    session_start();

    //Include database connection details
    require_once('../conexion.php');
    date_default_timezone_set('America/Guayaquil');
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    
?>

<html>
<head>
<title>Cobrar Cuentasr</title>
<script src="https://kit.fontawesome.com/7816c50242.js" crossorigin="anonymous"></script>
<script type="text/javascript">
	$(document).ready(function()
	{
	//	alert("ddddddddddddd");
	i=1;
   for(i=1; i<= 5; i++)
	{
        AgregarFilas_FP_CuentasPorCobrar(i)
    }
		
    });
</script>
    
</head>

<body onload="">
      <div class="modal-header">
        <a href="javascript: fn_cerrar();"><button type="button" class="close" ><span aria-hidden="true">&times;</span></button></a>
        <h4 class="modal-title" id="myModalLabel">Cobrar varias cuenta</h4>
      </div>
	  
      <form name="frmCobrarCuentaCobrarV" id="frmCobrarCuentaCobrarV" method="post" action="javascript: guardar_pago_cuenta_cobrarV(1,txtTipoPago);"  >
      <div class="modal-body">
             <div id="mensajePagarCuentaCobrar" >     </div>
            <?php
            
//			$id_empresax=3;
            $id_cliente = $_POST['id_cliente'];
			$tipo = $_POST['tipox'];
			$total_a_pagar = $_POST['total_a_pagar'];
			$tipoCuenta = $_POST['tipoCuenta'];
			
// 			echo "tipo===".$tipo."</br>";
//             echo "tipo CUENTA===".$tipoCuenta."</br>";
//             echo "tOTAL ===".$total_a_pagar."</br>";
            
            
         
                
			    if ($tipoCuenta ==1){
			        
			        	 $sql = "SELECT
             proveedores.`nombreProveedor`  AS clientes_nombre,
             proveedores.`apellidoProveedor` AS clientes_apellido,
             sum(cuentas_por_cobrar.`saldo`) AS cuentas_por_cobrar_saldo,
             cuentas_por_cobrar.`id_proveedor` AS cuentas_por_cobrar_id_cliente,
			 cuentas_por_cobrar.`id_plan_cuenta` AS cuentas_por_cobrar_id_plan_cuenta,
             cuentas_por_cobrar.`estado` AS cuentas_por_cobrar_estado
        FROM
             `proveedores` proveedores  INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar 
			 ON proveedores.`id_proveedor` =  cuentas_por_cobrar.`id_proveedor`
              WHERE cuentas_por_cobrar.`id_empresa`= '".$sesion_id_empresa."' and 
			  cuentas_por_cobrar.`id_proveedor`='".$id_cliente."' and saldo>0 

			 "; 
        //  echo $sql;

				    }else if ($tipoCuenta ==2){
			        
			        	 $sql = "SELECT
             clientes.`nombre` AS clientes_nombre,
             clientes.`apellido` AS clientes_apellido,
             sum(cuentas_por_cobrar.`saldo`) AS cuentas_por_cobrar_saldo,
             cuentas_por_cobrar.`id_cliente` AS cuentas_por_cobrar_id_cliente,
			 cuentas_por_cobrar.`id_plan_cuenta` AS cuentas_por_cobrar_id_plan_cuenta,
             cuentas_por_cobrar.`estado` AS cuentas_por_cobrar_estado
        FROM
             `clientes` clientes INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar 
			 ON clientes.`id_cliente` = cuentas_por_cobrar.`id_cliente`
              WHERE cuentas_por_cobrar.`id_empresa`= '".$sesion_id_empresa."' and 
			  cuentas_por_cobrar.`id_cliente`='".$id_cliente."' and saldo>0 

			 "; 

				    }else if ($tipoCuenta ==3){
			        
			        	 $sql = "SELECT
             leads.`name` AS clientes_nombre,
              leads.`apellido` AS clientes_apellido,
             sum(cuentas_por_cobrar.`saldo`) AS cuentas_por_cobrar_saldo,
             cuentas_por_cobrar.`id_lead` AS cuentas_por_cobrar_id_cliente,
			 cuentas_por_cobrar.`id_plan_cuenta` AS cuentas_por_cobrar_id_plan_cuenta,
             cuentas_por_cobrar.`estado` AS cuentas_por_cobrar_estado
        FROM
             `leads` leads INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar 
			 ON leads.`id` = cuentas_por_cobrar.`id_lead` 
              WHERE cuentas_por_cobrar.`id_empresa`= '".$sesion_id_empresa."' and cuentas_por_cobrar.`estado` = 'Pendientes'   and 
			  cuentas_por_cobrar.`id_lead`='".$id_cliente."' and saldo>0 

			 "; 

				    }else if ($tipoCuenta ==4){
			        
			        	 $sql = "SELECT
            empleados.`nombre`AS clientes_nombre,
              empleados.`apellido` AS clientes_apellido,
             sum(cuentas_por_cobrar.`saldo`) AS cuentas_por_cobrar_saldo,
             cuentas_por_cobrar.`id_empleado` AS cuentas_por_cobrar_id_cliente,
			 cuentas_por_cobrar.`id_plan_cuenta` AS cuentas_por_cobrar_id_plan_cuenta,
             cuentas_por_cobrar.`estado` AS cuentas_por_cobrar_estado
        FROM
             `empleados` empleados INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar 
			 ON empleados.`id_empleado` = cuentas_por_cobrar.`id_empleado`
              WHERE cuentas_por_cobrar.`id_empresa`= '".$sesion_id_empresa."' and 
			  cuentas_por_cobrar.`id_empleado`='".$id_cliente."' and saldo>0 

			 "; 

				    }else if ($tipoCuenta ==5){
			        
			 //       	 $sql = "SELECT
    //                 inmueble.`id_inmueble`,
    //         `torre`AS clientes_nombre,
    //         `apartamento`AS clientes_apellido,
           
    //          sum(cuentas_por_cobrar.`saldo`) AS cuentas_por_cobrar_saldo,
    //          cuentas_por_cobrar.`id_inmueble` AS cuentas_por_cobrar_id_cliente,
			 //cuentas_por_cobrar.`id_plan_cuenta` AS cuentas_por_cobrar_id_plan_cuenta,
    //          cuentas_por_cobrar.`estado` AS cuentas_por_cobrar_estado
    //     FROM
    //          `inmueble` inmueble 
    //     INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar 
			 //ON inmueble.`id_inmueble` = cuentas_por_cobrar.`id_inmueble`
    //     WHERE cuentas_por_cobrar.`id_empresa`= '".$sesion_id_empresa."' and 
			 // cuentas_por_cobrar.`id_inmueble`='".$id_cliente."' and saldo>0 "; 
			 	 $sql = "SELECT
             clientes.`nombre` AS clientes_nombre,
             clientes.`apellido` AS clientes_apellido,
             sum(cuentas_por_cobrar.`saldo`) AS cuentas_por_cobrar_saldo,
             cuentas_por_cobrar.`id_cliente` AS cuentas_por_cobrar_id_cliente,
			 cuentas_por_cobrar.`id_plan_cuenta` AS cuentas_por_cobrar_id_plan_cuenta,
             cuentas_por_cobrar.`estado` AS cuentas_por_cobrar_estado
        FROM
             `clientes` clientes INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar 
			 ON clientes.`id_cliente` = cuentas_por_cobrar.`id_cliente`
              WHERE cuentas_por_cobrar.`id_empresa`= '".$sesion_id_empresa."' and 
			  cuentas_por_cobrar.`id_cliente`='".$id_cliente."' and saldo>0 

			 "; 

				    }else if ($tipoCuenta ==6){
			        
			 //       	 $sql = "SELECT
    //                 estudiante.`id_estudiante`,
    //         `nombres_estudiante`AS clientes_nombre,
    //         `apellidos_estudiante`AS clientes_apellido,
           
    //          sum(cuentas_por_cobrar.`saldo`) AS cuentas_por_cobrar_saldo,
    //          cuentas_por_cobrar.`id_estudiante` AS cuentas_por_cobrar_id_cliente,
			 //cuentas_por_cobrar.`id_plan_cuenta` AS cuentas_por_cobrar_id_plan_cuenta,
    //          cuentas_por_cobrar.`estado` AS cuentas_por_cobrar_estado
    //     FROM
    //          `estudiante` estudiante 
    //     INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar 
			 //ON estudiante.`id_estudiante` = cuentas_por_cobrar.`id_estudiante`
    //     WHERE cuentas_por_cobrar.`id_empresa`= '".$sesion_id_empresa."' and 
			 // cuentas_por_cobrar.`id_estudiante`='".$id_cliente."' and saldo>0 "; 
	 $sql = "SELECT
             clientes.`nombre` AS clientes_nombre,
             clientes.`apellido` AS clientes_apellido,
             sum(cuentas_por_cobrar.`saldo`) AS cuentas_por_cobrar_saldo,
             cuentas_por_cobrar.`id_cliente` AS cuentas_por_cobrar_id_cliente,
			 cuentas_por_cobrar.`id_plan_cuenta` AS cuentas_por_cobrar_id_plan_cuenta,
             cuentas_por_cobrar.`estado` AS cuentas_por_cobrar_estado
        FROM
             `clientes` clientes INNER JOIN `cuentas_por_cobrar` cuentas_por_cobrar 
			 ON clientes.`id_cliente` = cuentas_por_cobrar.`id_cliente`
              WHERE cuentas_por_cobrar.`id_empresa`= '".$sesion_id_empresa."' and 
			  cuentas_por_cobrar.`id_cliente`='".$id_cliente."' and saldo>0 

			 "; 
				    }
				   
				 
				    $result = mysql_query($sql) or die(mysql_error());
            while ($row = mysql_fetch_array($result)){                        
             	$clientes_nombre = $row['clientes_nombre'];
                $clientes_apellido = $row['clientes_apellido'];   
                $cuentas_por_cobrar_saldo = $row['cuentas_por_cobrar_saldo']; 
                $cuentas_por_cobrar_id_cliente = $row['cuentas_por_cobrar_id_cliente']; 
                $cuentas_por_cobrar_id_plan_cuenta = $row['cuentas_por_cobrar_id_plan_cuenta']; 
                $cuentas_por_cobrar_id_cuenta_por_cobrar = $row['cuentas_por_cobrar_id_cuenta_por_cobrar'];                 				
            } 
				
				
				   if ($tipo ==2){

				 $cuentas_por_cobrar_saldo =  $total_a_pagar;   
				    
				//   echo "cuentas_por_cobrar_saldo ===".$cuentas_por_cobrar_saldo."</br>"; 
				}
            
            
		//	echo "CLIENTE".$id_cliente;
          		// 	echo "cuentas_por_cobrar_saldo 33 ===".$cuentas_por_cobrar_saldo."</br>";
          		
          		if($sesion_id_empresa==41){
          		    // echo $sql;
          		}
            ?>          
            
        <div class="row">
                <div class="col-lg-3">
        <input type="hidden" name="txtDebeFP" id="txtDebeFP"  value="<?php echo $cuentas_por_cobrar_saldo;?>" />
        <input type="hidden" name="txtSubtotalVta" id="txtSubtotalVta"   />

				    <input type="hidden" name="txtTipoPago" id="txtTipoPago" value="<?php echo $tipo;?>" />
                    <input type="hidden" name="txtIdCliente" id="txtIdCliente" value="<?php echo $cuentas_por_cobrar_id_cliente;?>" />
                    <input type="hidden" name="txtIdPlanCuentas" id="txtIdPlanCuentas" value="<?php echo $cuentas_por_cobrar_id_plan_cuenta;?>" />
                    <input type="hidden" name="txtIdCuentaPagar" id="txtIdCuentaPagar" value="<?php echo $cuentas_por_cobrar_id_cuenta_por_cobrar;?>" />
                    <input type="hidden" name="txtFactura" id="txtFactura" value="<?php echo $cuentas_por_pagar_numero_compra;?>" />
                    <label for="txtDeudor"><strong class="leftSpace">Cliente :  </strong></label><br>
					<input type="text" tabindex="1" value="<?php echo $clientes_apellido.' '.$clientes_nombre;?>" class="form-control " id="txtDeudor" name="txtDeudor" title="Deudor" required="required"  readonly="true"  />	
                </div>
                <div class="col-lg-3">
                    <label for="txtDeudaTotal"><strong class="leftSpace">Saldo:  </strong></label>                                                              
                    <input type="text" tabindex="2" value="<?php echo $cuentas_por_cobrar_saldo;?>" class="form-control " id="txtDeudaTotal" name="txtDeudaTotal" title="Deuda Total" required="required" autocomplete="off" readonly="true"  />
                </div>
				
				<div class="col-lg-2">
                    <label for=""><strong class="leftSpace">Cobro:  </strong></label>                                                              
                    <input type="text" tabindex="2"  class="form-control " id="txtPagoFP" name="txtPagoFP" title="Cobros" required="required" autocomplete="off" readonly="true"  />
				</div>
				
				<div class="col-lg-2">
                    <label for=""><strong class="leftSpace">Saldo x pagar:  </strong></label>                                                              
                    <input type="text" tabindex="2"  class="form-control " id="txtCambioFP" name="txtCambioFP" title="Saldo Pendiente" autocomplete="off" readonly="true"  />
				</div>

                <div class="col-lg-2">
                     <label for="txtFechaPago"><strong class="leftSpace">Fecha de pago  </strong></label><img src="images/b_calendar.png" alt="" width="16" height="16" class="Estilo15" onClick="displayCalendar(txtFechaPago,'yyyy-mm-dd',this)"/><br>
                    <input type="text" tabindex="3" value="<?php echo date("Y-m-d",time()); ?>" class="form-control " id="txtFechaPago"  name="txtFechaPago" placeholder="Fecha de pago" required="required" autocomplete="off" maxlength="10" readonly="true" onClick="displayCalendar(txtFechaPago,'yyyy-mm-dd',this)"  />
                    <div id="validaPagoMin"></div>
                </div>            
        </div>

	
		        <div class="row ">
			
            <div class='col-lg-1 '></div>
            <div class='col-lg-4 '>Forma de cobro</div>
            <div class='col-lg-2 '>Valor</div>
                 <div class="col-lg-5" style="text-align: center;">
        <div class="row" style="text-align: center;">
            <div class="col-lg-2" style="display: none; text-align: left;" id="labelCuotas"># de Cuotas</div>
            <!-- <div id="titulo_dias_plazo" style="display: none; text-align: center;" class="col-lg-2 ">Días Plazo</div>  -->
            <div class="col-lg-4  " style="display: none; text-align: center;" id="labelFecha">Fecha Inicio</div>
        </div>
        <div class="row" style="text-align: center;">
            <div class="col-lg-3  " style="display: none;" id="labelRetencion"># Retención</div>
            <div class="col-lg-4  " style="display: none;" id="labelAutorizacion"># Autorización</div>
        </div>
    </div>
    
            <!--<div class='col-lg-1  ' style='display:none' id="labelCuotas"># de Cuotas</div>-->
            <!--<div class='col-lg-1  ' style='display:none' id="labelFecha">Fecha Inicio</div>-->
            <!--<div class='col-lg-1  ' style='display:none' id="labelRetencion"># Retenci&oacute;n</div>-->
            <!--<div class='col-lg-4  ' style='display:none' id="labelAutorizacion">Autorizaci&oacute;n</div>-->
        </div>								
                                
        
     <div id="tablita1"  ></div>
		
	</div>
    <div class="modal-footer">
        <div class="col-lg-3">
            <input  type="submit" value="Realizar Pago" tabindex="10" id="submitPCC" class="btn btn-success" name="btnEnviar"  />
        </div>
    </div>
</form>       

</body>  
<script>
    document.getElementById('txtPagoFP').addEventListener("onchange", actualizarPagoFp());

function actualizarPagoFp() {
    document.getElementById('txtSubtotalVta').value= document.getElementById('txtPagoFP').value;
}
</script>
</html>
