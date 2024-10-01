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
<title>Pagar Compra</title>
    <script type="text/javascript">
    	$(document).ready(function(){
    	    
    	i=1;    
        for(i=1; i<= 5; i++)
		{
         AgregarFilas_FP_Cpras()
        }

    });
    
            $(document).ready(function(){
          //  cargarFormasPago_compra(5);
                //fn_agregar();
		
//			document.getElementById("txtDebeFP").value = document.getElementById("txtSubtotal").value;
			document.getElementById("txtSubTotal").value = document.getElementById("txtSubtotal").value;
			document.getElementById("txtIva1").value = document.getElementById("txtIva").value;
		
			var prueba  = document.getElementById("txtIva").value;
			document.getElementById("txtDebeFP").value = document.getElementById("txtTotal").value;
			//alert(document.getElementById("txtDebeFP").value)
                        //txtDebeFP
			//document.getElementById("txtPagoFP").value = document.getElementById("txtTotalFVC").value;
			
			 var guardarXML = document.getElementById("textXML").value;
			 if (guardarXML>0){ 
			     var intro = document.getElementById("btnGuardarXML");
                    intro.style.display = 'block';
                 var intro2 = document.getElementById("btnGuardarTP");
                    intro2.style.display = 'none';
			 }
        });
    
    

   
            
     
    function saltar(e,id) {
        var k = document.all ? e.which : e.keyCode;
        if (k == 13){
          // Si la tecla pulsada es enter (codigo ascii 13)           
            if(id=="btnGuardarTP")
            {                
                // Si la variable id contiene "submit" enviamos el formulario
                document.forms[0].submit();
            }else{                
                // nos posicionamos en el siguiente input
                //document.getElementById(id).focus();
                id.focus();
                //document.frmAsientosContables.id.focus();
            }          
          //document.forms[0].focus();
          //return false;       
      } 
    } 
    </script>


</head>
<body>
<form name="frmFormaPago" id="frmFormaPago" method="post" action="/action_page.php">
<div class="modal-header">

 <h4>Forma de Pago Anticipo</h4>

       
    </div>
<div id="formulario_anticipos" class="row celeste mt-3">

</div>
    <div class="modal-header">
        <h4>Formas de Pago</h4>
    </div>
    <div class="modal-body">
      	<div id="mensaje1" ></div>
      
          <div id="mensajeFacturaVentaCondominios2"></div>
        <div class="row mb-5" id="tblTipoPago" >
              <input type="hidden" id="txtContadorAsientosAgregadosFVC" value="0" readonly="readonly" class="" name="txtContadorAsientosAgregadosFVC" />
              <input type="hidden" id="txtContadorFilasFVC" value="0" readonly="readonly" class="" name="txtContadorFilasFVC" />
              <input name="funcionAnticipo" id="funcionAnticipo" type="hidden" class="form-control " value="'.$cont.'" readonly="">
              <input name="sumatoriaSaldoAnticipo" id="sumatoriaSaldoAnticipo" type="hidden" class="form-control " value="0" readonly="">
              
            <div class="input-group">
              <span class="input-group-text">Sub-Total Compra:</span>
              <input   type="text" name="txtSubTotal" id="txtSubTotal" class="form-control fs-3" >
              <span class="input-group-text">Total Iva:</span>
              <input   type="text" name="txtIva1" id="txtIva1" class="form-control fs-3" />
              <span class="input-group-text">Total compra:</span>
              <input   type="text" name="txtDebeFP" id="txtDebeFP" class="form-control fs-3" onkeydown="saltar(event,this.form.txtPagoFP)" />
            </div>
            
            <div class="input-group mt-3">
                <span class="input-group-text">Cobro:</span>
                <input   type="text" name="txtPagoFP" autofocus id="txtPagoFP" class='form-control fs-3' onkeyup="calculaVuelto_cpra(txtDebeFP, txtPagoFP);"
                autocomplete="off" onkeydown="saltar(event,this.form.txtCambioFP)"/>
                <span class="input-group-text">Cambio:</span>
                <input   type="text" name="txtCambioFP" id="txtCambioFP" class="form-control fs-3" onkeydown="saltar(event,this.form.cmbFormaPagoFP)"/>
                <span class="input-group-text">Pago:</span>
                <input  type="text" name="txtSubtotalFVC" id="txtSubtotalFVC" class='form-control fs-3' readonly="readonly"  value="0.00" />
            </div>

          
            <div class="input-group mt-3">
                
                <span class="input-group-text">Tipo Comprobante:</span>
                
                <select class="form-select">
                  <option>Diario</option>
                  <option>Ingreso</option>
                  <option>Egreso</option>
                </select>
                
                <span class="input-group-text ">Detalle para Asiento Contable (Llenar si es necesario):</span>
                <textarea class="form-control" id="detalleFP"  name="detalleFP" id="" cols="30" rows="1"></textarea>
            </div>    
            
            
        </div>

           <br> 
          
          
                    
		 	<div class="row ">
      
			  <div class='col-lg-1 '></div>
				<div class='col-lg-1 '>Forma de Pago</div>
				<div class='col-lg-3 '>Descripcion Pago</div>
			<!--	<div class='col-lg-1 '>Tipo Pago</div> --->
                <div class='col-lg-1 '> Cpte #</div>									
				<div class='col-lg-1 '>    %   </div>
									
				<div class='col-lg-2 '>Valor</div>
				<!--	<th type="hidden"><strong>Cuenta</strong></th> -->
				
			 
			 <div class='col-lg-1  ' style='display:none;text-align: center;' id="labelCuotas"># de Cuotas</div>
                              
            <div id="titulo_dias_plazo" style='display:none;text-align: center;' class='col-lg-1 '>D&iacute;as Plazo</div> 

			<div class='col-lg-1  ' style='display:none;text-align: center;' id="labelFecha">Fecha Inicio</div>
									
									
				<!--<div class='col-lg-1 '># de Cuotas</div>-->
			<!--	<th ><strong>Dias Plazo</strong></th> --->
			<!--<div class='col-lg-1 '>D&iacute;as Plazo</div>-->
				<!--<div class='col-lg-1 '>Fecha Inicio</div>-->
									
				<!--			<th ><strong>Fecha Pago</strong></th>   -->  
				</tr>								
			</div>						
		 	<div id="tablita" class="row bg-white" ></div>

		  <div id="div_listar_cuotasCpra" style="background-color: green">
          


          </div>
                        
          <table border="0" width="100%">
            <tbody>
                <tr>
   
                    <td align="center"><input  id="btnGuardarXML" name="btnGuardarXML"  class="btn btn-primary" type="button" value="Guardar Compra" onclick="guardar_fact_cpra_xml(1);" style="display:none"/></td>  
            
                 	<td align="center"><input  id="btnGuardarTP" name="btnGuardarTP"  class="btn btn-primary" type="button" value="Guardar Compra" onclick="guardar_factura_compra(1);" style="display:block" /></td>
               
                    <td align="center"><input  id="btnSalir" name="btnSalir" class="btn btn-primary" type="button" value="Salir" onclick="fn_cerrar();" /></td>
                    
                    <!--
                    <td align="center"><input style="width: 150px" id="btnGuardarTP" name="btnGuardarTP"  class="btn btn-primary" type="button" value="Guardar" onclick="guardar_fact_cpra_xml(1);" /></td>

					<td align="center"><input style="width: 150px" id="btnGuardarTP" name="btnGuardarTP"  class="btn btn-primary" type="button" value="Guardar" onclick="guardar_factura_compra(1);" /></td>
                     -->
                </tr>                                
            </tbody>
            </table>
        </div>
       

    </div>
    </form>
</body>



</html>