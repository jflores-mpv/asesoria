<?php
	require_once('../ver_sesion.php');
	//Start session
	session_start();
	//Include database connection details
	require_once('../conexion.php');
    date_default_timezone_set('America/Guayaquil');
?>
<html>
<head>
	<title>Tipo pago compra</title>
    <script type="text/javascript">
    
		$(document).ready(function(){

   
    if(document.getElementById("textIdClienteFVC")){
         let idCliente = textIdClienteFVC.value ;
         $.ajax(
	{
		url: 'sql/clientes.php',
		data: "accion=55&idCliente="+idCliente,
		type: 'post',
		success: function(data)	{
		    let json =  JSON.parse(data);
            console.log({json});
            txtLimiteCredito.value = json.limite_actual;
            txtPoseeLimiteCredito.value = json.tiene_limite_credito;
            txtlimiteCliente.value = json.limite;
		}
	});
	
    }
            

		i=1;
        for(i=1; i<= 4; i++){
            AgregarFilas_FP_Vtas(i)
        }
        
        	document.getElementById("txtSubTotal").value = document.getElementById("txtSubtotalFVC").value;
			document.getElementById("txtIva1").value = document.getElementById("txtTotalIvaFVC").value;
			var prueba  = document.getElementById("txtIva1").value;
			document.getElementById("txtDebeFP").value = document.getElementById("txtTotalFVC").value;
			
        });
    
  
    function saltar(e,id) {
        var k = document.all ? e.which : e.keyCode;

        if (k == 13){ 
            if(id=="btnGuardarTP") {                
                document.forms[0].submit();
            }else{                
                id.focus();
            }          
      } 
    }  
    
    
    function calculaVuelto_cpra(txtDebeFP, txtPagoFP){
        console.log("txtDebeFP",txtDebeFP);
        console.log("txtPagoFP",txtPagoFP);
        valor = txtDebeFP;
        restar =txtPagoFP;
         resta= restar-valor;
        document.getElementById("txtCambioFP").value = resta.toFixed(2);
        
    }
</script>

</head>
<body>
<div class="modal-header">
    <h4>Formas de cobro</h4>
</div>
 <div class="modal-body">
			    <div id="mensaje1" ></div>
                <form name="frmFormaPagoVta" id="frmFormaPagoVta" method="post" action="/action_page.php">
					<div id="mensajeFacturaVentaCondominios2"></div>
                    <input name="txtLimiteCredito" id="txtLimiteCredito" type="hidden" value=""  />
                    <input name="txtPoseeLimiteCredito" id="txtPoseeLimiteCredito" type="hidden" value=""  />
                    <input name="txtlimiteCliente" id="txtlimiteCliente" type="hidden" value=""  />
                        <div class="row mb-5 " id="tipoPagoCredito" >

	                            	<div class="col-lg-2 offset-lg-3">
                                        <label  >Cobro:</label>
										<input  type="text" name="txtCobroFP" autofocus id="txtCobroFP" class="form-control fs-2" onkeyup="calculaVuelto_cpra(txtDebeFP.value, this.value);" autocomplete="off" onkeydown="saltar(event,this.form.txtCambioFP)"/>
                                    </div>
								
                                                          
									<div class="col-lg-2">
                                        <label >Cambio:</label>
                                        <input  type="text" name="txtCambioFP" id="txtCambioFP" class="form-control fs-2" onkeydown="saltar(event,this.form.cmbFormaPagoFP)"/>
                                    
									</div>
									<div class="col-lg-2">
								 <input type="hidden" id="txtContadorAsientosAgregadosFVC" value="0" readonly="readonly" class="" name="txtContadorAsientosAgregadosFVC" />
								 <input type="hidden" id="txtContadorFilasFVC1" value="0" readonly="readonly" class="" name="txtContadorFilasFVC1" />
								
								<label >Total a Cobrar </label>
								<input style="width: 100%; text-align: right;" type="text" name="txtSubtotalVta" id="txtSubtotalVta" class="form-control fs-2" readonly="readonly"  value="0.00" />
							        </div>
							</div>	
			
						
						

						
							<div class="row ">
							    	<div class='col-lg-1 '></div>
                            <!--<span class="input-group-text" style="visibility: hidden;"><a  title="Limpiar fila"> <i class="fa fa-times" aria-hidden="true"></i></a> </span>-->
							    	<!-- <div class='col-lg-1 '></div> -->
									<!--<div class='col-lg-2 '>Forma de Pago</div>-->
									<div class='col-lg-4 '  style='text-align: center;' >Forma de cobro</div>

									
									<div class='col-lg-2 ' style='text-align: center;' >Valor</div>
								<!--<th type="hidden"><strong>Cuenta</strong></th> -->
    <div class="col-lg-5" style="margin-left: -50px;text-align: center;">
        <div class="row" style="text-align: center;">
           
            <div class="col-lg-2" style="display: none; text-align: left;" id="labelCuotas"># de Cuotas</div>
            <div id="titulo_dias_plazo" style="display: none; text-align: center;" class="col-lg-2 ">D&iacute;as Plazo</div> 
            <div class="col-lg-4  " style="display: none; text-align: center;" id="labelFecha">Fecha Inicio</div>
        </div>
        <div class="row" style="text-align: center;">
            <div class="col-lg-5  " style="display: none;" id="labelRetencion"># Retenci&oacute;n</div>
            <div class="col-lg-4  " style="display: none;" id="labelAutorizacion"># Autorizaci&oacute;n</div>
        </div>
         
        <div class="row" style="text-align: center;">
             <div class="col-lg-2" style="display: none; text-align: left;" id="labelNumCuenta"># de Cuenta</div>
        </div>
    </div>
									<!-- <div class='col-lg-1  ' style='display:none;text-align: center;' id="labelCuotas"># de Cuotas</div>
                              
                                        <div id="titulo_dias_plazo" style='display:none;text-align: center;' class='col-lg-1 '>D&iacute;as Plazo</div> 
                           
									
									<div class='col-lg-2  ' style='display:none;text-align: center;' id="labelFecha">Fecha Inicio</div>
                                    <div class='col-lg-2  ' style='display:none' id="labelRetencion"># Retenci&oacute;n</div>
                                    <div class='col-lg-3  ' style='display:none' id="labelAutorizacion"># Autorizaci&oacute;n</div> -->
						<!--			<th ><strong>Fecha Pago</strong></th>   -->  
								</div>								
														
								
						 	<div id="tablita1"  ></div>

			
						<div id="div_listar_cuotasCpra" >
                            
						</div>
						
						<div class="row my-5">
							<div class="col-lg-2 offset-lg-2">
                                        <label  >Suma:</label>
										<input  type="text" name="txtPagoFP" autofocus id="txtPagoFP" class="form-control fs-2"  autocomplete="off" onkeydown="saltar(event,this.form.txtCambioFP)"/>
                            </div>
						
						    <div class="col-lg-2">
                                        <label >Sub-Total Ventas:</label>
									   <input  type="text" name="txtSubTotal"  id="txtSubTotal"class="form-control fs-2" />
                                    </div> 

									
									<div class="col-lg-2">
                                        <label >IVA:</label>
									   <input  type="text" name="txtIva1"  id="txtIva1"class="form-control fs-2" />
                                    </div>

                                    <div class="col-lg-2">
                                        <label >Total Venta:</label>
    								   <input  type="text" name="txtDebeFP"  id="txtDebeFP"class="form-control fs-2" />
                                    </div>
						
                        </div>
                        <?php 
                         $sqlFactuFisica="SELECT establecimientos.id_empresa,id_est,emision.codigo,establecimientos.codigo,emision.id as emision_id ,emision.tipoFacturacion,emision.tipoEmision from emision,establecimientos where establecimientos.id_empresa=$sesion_id_empresa and emision.id_est=establecimientos.id AND emision.tipoEmision = 'F' LIMIT 1";
                         $resultFactuFisica  = mysql_query($sqlFactuFisica);
                         $numFilasFF = mysql_num_rows($resultFactuFisica);
                       
                        
                        ?>
                        
						<table border="0" width="100%">
                            
                            <tbody>
                                <tr>
                                    <?php
                                        if($numFilasFF > 0){ 
                                    ?>
                                <td align="center"><input style="width: 200px" id="facturacionFisica" name="facturacionFisica"  class="btn btn-primary" type="button" value="Facturacion Fisica" onclick="guardarFacVentaEducativo(21,200);" /></td>
                                <?php
                                        }
                                    ?>
                                    
                                     <?php
                        $dominio = $_SERVER['SERVER_NAME'];
                    
                        if($dominio=='econtweb.com' || $dominio=='www.econtweb.com'  or $dominio=='contaweb.com' || $dominio=='www.contaweb.com'  ){ ?>
                            
                    <td align="center"><input style="width: 200px" id="btnGuardarTP" name="btnGuardarTP"  class="btn btn-primary" type="button" value="+" onclick="guardarFacVentaEducativo(1,300);" /></td>
  
                            
                        <?php }   ?>
                                     
                                     
                                     
                                    <td align="center"><input style="width: 200px" id="actualizar_venta" name="actualizar_venta"  class="btn btn-primary" type="button" value="Actualizar Venta" onclick="modificarFacVentaEducativo(20,400);" /></td>
                                        
                                    <td align="center"><input style="width: 200px"id="autorizar_venta" name="autorizar_venta"  class="btn btn-primary" type="button" value="Guardar y Autorizar" onclick="guardarFacVentaEducativo(1,100);" /></td>
                                    
                                    <td align="center"><input style="width: 200px" id="revisar_venta" name="revisar_venta"  class="btn btn-primary" type="button" value="Guardar y Revisar" onclick="guardarFacVentaEducativo(1,200);" /></td>
                                     <td align="center" ><input style="width: 200px;display:none;" id="guardar_xml" name="guardar_xml"  class="btn btn-primary" type="button" value="Facturar" onclick="guardarFormasPagoXML(24,200);" /></td>
                                    
                                    <td align="center"><input style="width: 200px" id="btnSalir" name="btnSalir" class="btn btn-primary" type="button" value="Salir" onclick="fn_cerrar();" /></td>
                                </tr>                                
                            </tbody>
						</table>

				</form>

  
</div>
</body>

<script type="text/javascript">

    $(document).ready(function(){
         if(document.getElementById('factura_xml').value=='1'){
             document.getElementById('guardar_xml').style.display='block';
              document.getElementById('actualizar_venta').style.display='none';
              
            if(document.getElementById('btnGuardarTP')){
                document.getElementById('btnGuardarTP').style.display='none';
            }
            
            document.getElementById('autorizar_venta').style.display='none';
            document.getElementById('revisar_venta').style.display='none';
            document.getElementById('facturacionFisica').style.display='none';
            
            
         }else{
              if(document.getElementById('modificada').style.display=='block'){
                        document.getElementById('actualizar_venta').style.display='block';
            if(document.getElementById('btnGuardarTP')){
                document.getElementById('btnGuardarTP').style.display='none';
            }
            
            document.getElementById('autorizar_venta').style.display='none';
            document.getElementById('revisar_venta').style.display='none';
           document.getElementById('guardar_xml').style.display='none';
        }else{
                        document.getElementById('actualizar_venta').style.display='none';
            if(document.getElementById('btnGuardarTP')){
                document.getElementById('btnGuardarTP').style.display='block';
            }
            
            document.getElementById('autorizar_venta').style.display='block';
            document.getElementById('revisar_venta').style.display='block';
           document.getElementById('guardar_xml').style.display='none'; 

        }
         }
        
       
        
		$("#frmNuevaTransaccion").validate({

		});
	});

function guardar_libro(accion){

            var str = $("#frmNuevaTransaccion").serialize();
            $.ajax({

                url: 'sql/libroDiario.php',
                data: str+"&txtAccion="+accion,

                type: 'post',
                success: function(data){
                    document.getElementById('mensaje2').innerHTML+=""+data;
                    document.getElementById("frmNuevaTransaccion").reset();
                    //fn_cerrar();
                    //facCompTransaccion();
                    setTimeout('document.location.reload()', 4000);
                    if (confirm('Desea imprimir el Comprobante?')) {
                        miUrl = "reportes/rptComprobante.php?txtIdComprobante="+document.getElementById("txtNumeroAsiento").value+"&txtIdPeriodoContable="+document.getElementById("txtIdPeriodoContable").value;
                        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
                        //setTimeout('document.location.reload()', 7000);
                    } else {
                        //setTimeout('document.location.reload()', 4000);
                    }
                    listar_libro_diario();
                }
            });
};
function modificarFacVentaEducativo(accion,modo){

let existe=0;
   
    var str = $("#frmFacturaVentaCondominios").serialize();
   
    $.ajax
	({
            url: 'sql/factura_buscar_educat.php',
            type: 'post',
            data: str+'&txtAccion=14',
            success: function(data){
              existe =parseInt( data.trim());
             console.log('|'+existe+'|');
             if(existe == 1){
    var SubtotalVta = document.frmFormaPagoVta['txtSubtotalVta'].value;
    var cmbEst  = document.frmFacturaVentaCondominios['cmbEst'].value;
    var cmbEmi  = document.frmFacturaVentaCondominios['cmbEmi'].value;
    var Tipo    = document.frmFacturaVentaCondominios['cmbTipoDocumentoFVC'].value;
    var tipo_documento_descripcion = 'Venta';
    if(Tipo==100){
        tipo_documento_descripcion = 'Nota de venta';
    }
    var modoFacturacion = (modo==300)?'sql/facturaVentaGrupal.php':'sql/facturaVentaEducat.php';
    // if(sesion_id_empresa){
    //     if(sesion_id_empresa.value=='116'  ){
    //   modoFacturacion='sql/facturaVentaEducat_test.php';
    // }
    // }
    
    if(SubtotalVta != 0){
    var str = $("#frmFacturaVentaCondominios,#frmFormaPagoVta").serialize();
    // console.log(str);
        $.ajax
		({
            url: modoFacturacion,
            type: 'post',
            data: str+"&txtAccion="+accion+"&modo="+modo,
            beforeSend: function(){
            },
                success: function(data){
                	if(data.trim()=='kardexSI' ){
                	     pdfVentas_id();
				            alertify.success(tipo_documento_descripcion+" Modificada con exito");
				            
				            document.getElementById('frmFacturaVentaCondominios').reset(); 
				            var hiddenInputs = document.querySelectorAll('#frmFacturaVentaCondominios input[type="hidden"]');
        
     hiddenInputs.forEach(function(input) {
 if (input.name == 'txtContadorFilasFVC' || input.name == 'sesion_id_empresa'|| input.name == 'limiteCF') {
                
              }else{
                  input.value = '0';
              }
});
				            numfac(cmbEst,cmbEmi,Tipo);
				            fn_cerrar();
				           
				    }
				    else if(data=='kardex' ){
				          pdfVentas_id();
				            alertify.success(tipo_documento_descripcion+" Realizada con exito");
				            
				            document.getElementById('frmFacturaVentaCondominios').reset(); 
				            var hiddenInputs = document.querySelectorAll('#frmFacturaVentaCondominios input[type="hidden"]');
        
        // Recorrer los campos ocultos, excluyendo el llamado 'txtContadorFilasFVC'
hiddenInputs.forEach(function(input) {
 if (input.name == 'txtContadorFilasFVC' || input.name == 'sesion_id_empresa'|| input.name == 'limiteCF') {
                
              }else{
                  input.value = '0';
              }
});
				            numfac(cmbEst,cmbEmi,Tipo);
				            fn_cerrar();
				          
			       	}
                  
			       	else{
				            alertify.error(data);
		        	}
		        	actualizar_hora();
                }
            });
        }else{  alert ('Total a pagar deber ser mayor que 0.');   }

    }else if(existe == 2){
        alertify.error("La "+tipo_documento_descripcion+" ya esta autorizada, no se puede modificar.");
    }else if(existe == 4){
        alertify.error("No se puede eliminar factura, ya tiene registro de cobros.");
    }
    else{
        alertify.error("No existe la "+tipo_documento_descripcion+" que se quiere modificar");		           
    }
            }
    });

   

    }
</script>

</html>