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
        });
    
    

       /*     cargarFormasPago_compra(5);
            
			var prueba  = document.getElementById("txtIva").value;
			console.log("prueba",prueba);
			
			document.getElementById("txtSubTotal").value = document.getElementById("txtSubtotal").value;
			document.getElementById("txtIva1").value = document.getElementById("txtIva").value;
			var total=document.getElementById("txtTotal").value
			
			console.log("total",total);
			
			document.getElementById("txtDebeFP").value = total;
            document.getElementById("txtValorS1").value = total;  
            document.getElementById("txtPagoFP").value = total;  
            document.getElementById("txtSubtotalFVC").value = document.getElementById("txtTotal").value;               */
            
     
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
    <div class="modal-header">
    <h4>Formas de Pago</h4>
</div>

      	<div id="mensaje1" ></div>
        <form name="frmFormaPago" id="frmFormaPago" method="post" action="/action_page.php">
          <div id="mensajeFacturaVentaCondominios2"></div>
          <div class="row mb-5" id="tblTipoPago" >
            <div class="col-lg-2 ">
              <label>Sub-Total Compra:</label>
              <input   type="text" name="txtSubTotal" id="txtSubTotal" class="form-control fs-2" />
            </div>
            <div class="col-lg-2 ">
              <label>Total Iva:</label>
              <input   type="text" name="txtIva1" id="txtIva1" class="form-control fs-2" />
            </div>
            
			<div class="col-lg-2 ">
              <label>Total compra:</label>
              <input   type="text" name="txtDebeFP" id="txtDebeFP" class="form-control fs-2" onkeydown="saltar(event,this.form.txtPagoFP)" />
            </div>
            
            <div class="col-lg-2 ">
              <label>Cobro:</label>
              <input   type="text" name="txtPagoFP" autofocus id="txtPagoFP" class='form-control fs-2' onkeyup="calculaVuelto_cpra(txtDebeFP, txtPagoFP);"
              autocomplete="off" onkeydown="saltar(event,this.form.txtCambioFP)"/>
            </div>
            
            <div class="col-lg-2 ">
                    <label>Cambio:</label>
                    <input   type="text" name="txtCambioFP" id="txtCambioFP" class="form-control fs-2" onkeydown="saltar(event,this.form.cmbFormaPagoFP)"/>
            </div>
            <div class="col-lg-2 ">
				<td width="" >  <input type="hidden" id="txtContadorAsientosAgregadosFVC" value="0" readonly="readonly" class="" name="txtContadorAsientosAgregadosFVC" /></td>
				<td width="" align="center"  > <input type="hidden" id="txtContadorFilasFVC" value="0" readonly="readonly" class="" name="txtContadorFilasFVC" /> </td>
				<td width="" align="right" ><strong>SUB-TOTAL </strong></td>
				<td width="14%" ><input style="width: 100%; text-align: right;" type="text" name="txtSubtotalFVC" id="txtSubtotalFVC" class='form-control' readonly="readonly"  value="0.00" /></td>
			</div>
            
          </div>
                    
		 	<div class="row ">
			  <div class='col-lg-1 '></div>
				<div class='col-lg-1 '>Forma de Pago</div>
				<div class='col-lg-3 '>Descripcion Pago</div>
			<!--	<div class='col-lg-1 '>Tipo Pago</div> --->
                <div class='col-lg-1 '> Cpte #</div>									
				<div class='col-lg-1 '>    %   </div>
									
				<div class='col-lg-2 '>Valor</div>
				<!--	<th type="hidden"><strong>Cuenta</strong></th> -->
				<div class='col-lg-1 '># de Cuotas</div>
			<!--	<th ><strong>Dias Plazo</strong></th> --->
				<div class='col-lg-1 '>Fecha Inicio</div>
									
				<!--			<th ><strong>Fecha Pago</strong></th>   -->  
				</tr>								
			</div>						
		 	<div id="tablita" class="row bg-white" ></div>

		  <div id="div_listar_cuotasCpra" style="background-color: green">
          


          </div>
                        
          <table border="0" width="100%">
            <tbody>
                <tr>
					<td align="center"><input style="width: 150px" id="btnGuardarTP" name="btnGuardarTP"  class="btn btn-primary" type="button" value="Guardar" onclick="guardar_fact_cpra_xml(1);" /></td>
                    <td align="center"><input style="width: 150px" id="btnSalir" name="btnSalir" class="btn btn-primary" type="button" value="Salir" onclick="fn_cerrar();" /></td>
                </tr>                                
            </tbody>
        </table>
        </div>
        </form>


</body>



</html>