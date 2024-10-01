/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function objetoAjax(){
    var xmlhttp=false;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
            xmlhttp = false;
        }
    }

    if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}

function fn_cerrar(){
	$.unblockUI({
		onUnblock: function(){
			$("#div_oculto").html("");
		}
	});
}



function listar_clientes(){
    //PAGINA: clientesCondominios.php 
    var str = $("#frmClientes").serialize();
    $.ajax({
            url: 'ajax/listarClientes.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#listar_clientes").html(data);

            }
    });

}

function listar_clientes2(){
    //PAGINA: clientesCondominios.php 
    var str = $("#frmClientes").serialize();
    $.ajax({
            url: 'ajax/listarClientes2.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#listar_clientes2").html(data);

            }
    });

}

function combopais2(aux){
    
    ajax1=objetoAjax();
    ajax1.open("POST", "sql/busquedas.php",true);
    ajax1.onreadystatechange=function(){
        if (ajax1.readyState==4){
            var respuesta=ajax1.responseText;            
            asignapais2(respuesta);
            
        }
    }
    ajax1.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax1.send("accion="+aux)
}

function asignapais2(cadena){
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;
    
    limpiapais2();
    document.frmClientes.cbpais.options[0] = new Option("Seleccione Pais","0");
    for(i=1;i<limite;i=i+2){
        document.frmClientes.cbpais.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
    //document.getElementById("cbpais").selectedIndex = "3";
    document.frmClientes.cbpais.selectedIndex = '1'; // seleccion para ecuador
}

function limpiapais2()
{
    
    for (m=document.frmClientes.cbpais.options.length-1;m>=0;m--){        
        document.frmClientes.cbpais.options[m]=null;
    }
}

function comboprovincia2(aux){

    codigo=document.frmClientes.opcion.value;// coje el txt al que se le dio el valor de 1 = ecuador
    ajax=objetoAjax();
    ajax.open("POST", "sql/busquedas.php",true);
    ajax.onreadystatechange=function(){
        if (ajax.readyState==4){
            var respuesta=ajax.responseText;
            asignaprovincia2(respuesta);
        }
    }
    ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax.send("accion="+aux+"&codigo="+codigo)
}

function asignaprovincia2(cadena){
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;
    document.frmClientes.opcion1.value="0";
    limpiaprovincia2();
    document.frmClientes.cbprovincia.options[0] = new Option("Seleccione Provincia","0");
    for(i=1;i<limite;i=i+2){
        document.frmClientes.cbprovincia.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
}

function limpiaprovincia2()
{
    for (m=document.frmClientes.cbprovincia.options.length-1;m>=0;m--){
        document.frmClientes.cbprovincia.options[m]=null;
    }
}

function combociudad2(aux){
    codigo= document.frmClientes.cbprovincia.value;
    ajax3=objetoAjax();
    ajax3.open("POST", "sql/busquedas.php",true);
    ajax3.onreadystatechange=function() {
        if (ajax3.readyState==4) {
            var respuesta=ajax3.responseText;
            asignaciudad2(respuesta);
        }
    }
    ajax3.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax3.send("accion="+aux+"&codigo="+codigo)
}

function asignaciudad2(cadena){
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;
    document.frmClientes.opcion2.value="0";
    limpiaciudad2();
    document.frmClientes.cbciudad.options[0] = new Option("Seleccione Ciudad","0");
    for(i=1;i<limite;i=i+2){
        document.frmClientes.cbciudad.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
    if(document.getElementById('reporte')){
        if(document.getElementById('reporte').value=='jderp'){
            listar_reporte_clientes_jd(1);
        }else{
            listar_reporte_facturas(1);
        }
    }
}

function limpiaciudad2()
{
    for (m=document.frmClientes.cbciudad.options.length-1;m>=0;m--){
        document.frmClientes.cbciudad.options[m]=null;
    }
}


// ** Cargar Tipo de Clientes ----

function comboTipoCliente(aux){
    //alert("ComboTipoCliente");
	//alert(aux);
    ajax5=objetoAjax();
    ajax5.open("POST", "sql/busquedas.php",true);
    ajax5.onreadystatechange=function(){
        if (ajax5.readyState==4){
            var respuesta=ajax5.responseText;  
		//	alert(respuesta);
            asignaTipoCliente(respuesta);
        }
    }
    ajax5.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax5.send("accion="+aux)
}

function asignaTipoCliente(cadena){
    array = cadena.split( "?" );
    limite=array.length;
	//alert(array);
    cont=1;
    limpiaTipoCliente();
    document.frmClientes.cbTipoCliente.options[0] = new Option("Seleccione Tipo Clientes","0");
    for(i=1;i<limite;i=i+2){
        document.frmClientes.cbTipoCliente.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
    //document.getElementById("cbpais").selectedIndex = "3";
    document.frmClientes.cbTipoCliente.selectedIndex = '0'; // seleccion para ecuador
}

function limpiaTipoCliente()
{ 
    for (m=document.frmClientes.cbTipoCliente.options.length-1;m>=0;m--){        
        document.frmClientes.cbTipoCliente.options[m]=null;
    }
}



//*******************************    CLIENTE   ******************************//

function lookup9(valor, accion) {
    //pagina: facturaVentaCondominios.php
    if(valor.length == 0) {
            // Hide the suggestion box.
            $('#suggestions9').hide();
    } else {
            $.post("sql/clientes.php", {queryString: valor, accion: accion}, function(data){
                    if(data.length >0) {
                            $('#suggestions9').show();
                            $('#labelClientes').show();
                            
                            $('#autoSuggestionsList9').html(data);
                         //    fill9(data);
                    }
            });
           
    }
} // lookup
function lookupClienteMostrar(id_cliente, accion) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "sql/clientes.php", true); // Asegúrate de que la URL del script PHP es correcta
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log("Respuesta del servidor:", xhr.responseText);
           fill9(xhr.responseText);
        }
    };

    var data = "accion=" + encodeURIComponent(accion) + "&queryString=" + encodeURIComponent(id_cliente);
    xhr.send(data);
}


function fill9(cadena) {
    setTimeout("$('#suggestions9').hide();", 200);
    array = cadena.split("*");
    
    console.log("cadena cliente", cadena);
    
    $('#textIdClienteFVC').val(array[5]);
    $('#txtTelefonoFVC').val(array[3]);
    $('#txtCedulaFVC').val(array[2]);
    $('#txtDireccionFVC').val(array[4]);
    $('#txtNombreFVC').val(array[0] + " " + array[1]);
    $('#txtCodigoServicio1').focus();
    
    $('#idtipocliente').val(array[6]);
    
    if (typeof vrExportacion === "function") {
 if (array[6] === '08') {
        vrExportacion(true);
    } else {
        vrExportacion(false);
    }
}
    
   if( document.getElementById('vendedor_id') ) {
        document.getElementById('vendedor_id').value=0;
    if(array[7]!='' ){
        document.getElementById('vendedor_id').value=array[7];
    }
   }
    
    
    calculoSubTotal_ventas();
}
// function fill9(cadena) {
//     setTimeout("$('#suggestions9').hide();", 200);
//     array = cadena.split("*");
    
//     // console.log("cadena", cadena);
    
//     $('#textIdClienteFVC').val(array[5]);
//     $('#txtTelefonoFVC').val(array[3]);
//     $('#txtCedulaFVC').val(array[2]);
//     $('#txtDireccionFVC').val(array[4]);
//     $('#txtNombreFVC').val(array[0] + " " + array[1]);
//     $('#txtCodigoServicio1').focus();
    
//     $('#idtipocliente').val(array[6]);
    
//     if (array[6] === '08') {
//         vrExportacion(true);
//     } else {
//         vrExportacion(false);
//     }
    
    
//     calculoSubTotal_ventas();
// }



function residentes(){
        $("#div_oculto").load("ajax/residentes.php", function(){
        
	$.blockUI({
			message: $('#div_oculto'),
		    overlayCSS: {backgroundColor: '#111'},
			css:{
			    backgroundColor: 'white',
				top: '5%',
				position: 'absolute',
				width:'75%',
                left: '15%'
			}
		}); 
       
	});
}


function lead(){

        $("#div_oculto").load("ajax/leads.php", function(){
		$.blockUI({
			message: $('#div_oculto'),

		overlayCSS: {backgroundColor: '#111'},
			css:{

				'-webkit-border-radius': '3px',
                '-moz-border-radius': '3px',
                'box-shadow': '6px 6px 20px gray',
                

				top: '5%',

				position: 'absolute',
				width: '75%',
                                left: '15%'


			}
		});
                //listar_formas_pago();
	});

}



//**************************** FACTURA VENTA - REGISTROS **********************

function guardar_factura_venta(accion){
    //PAGINA: nuevaFacturaVenta.php
    var idCliente = document.frmFacturaVentaCondominios['textIdClienteFVC'].value;
    var txtContadorAsientosAgregados = document.frmFacturaVentaCondominios['txtContadorAsientosAgregadosFVC'].value;
    var txtTotal = document.frmFacturaVentaCondominios['txtTotalFVC'].value;

    if(idCliente != ""){
        if(txtContadorAsientosAgregados >= 1){
            if(txtTotal != "" && txtTotal > 0){

                var str = $("#frmFacturaVentaCondominios").serialize();
                $.ajax({
                    url: 'sql/facturaVenta.php',
                    data: str+"&txtAccion="+accion,
                    type: 'post',
                    success: function(data){
                        document.getElementById("mensajeFacturaVentaCondominios").innerHTML=data;

                        if(data.length == 86){
                            //document.getElementById("frmFacVenta").reset();

                            if(confirm("Desea imprimir la factura?")){
                            miUrl = "reportes/rptFacturaVenta.php?id_venta="+document.getElementById("txtIdVentaFVC").value;
                            window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
                            //setTimeout('document.location.reload()', 5000);
                            }else{
                                //setTimeout('document.location.reload()', 2000);
                            }
                            if(confirm("Desea ingresar la Transacci\u00f3n de la factura?")){
                               facVenTransaccion();
                            }
                            document.frmFacturaVentaCondominios.submit.disabled=true;
                            document.location.reload();
                        }

                    }
                });


            }else {
                alert ('El total no puede ser nulo o cero.');
                document.frmFacturaVentaCondominios.txtTotalFVC.focus();
            }
        }else {
            alert ('Ingrese un Servicio.');
        }
    }else {
        alert ('Ingrese un Cliente.');
        document.frmFacturaVentaCondominios.txtCedulaFVC.focus();
    }
}

function facVenTipoPago(){
 //PAGINA: nuevaTransaccion.php
    $("#div_oculto").load("ajax/tipoPago.php",  function(){
        $.blockUI({
                message: $('#div_oculto'),
        overlayCSS: {backgroundColor: '#111'},
                css:{
                        '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px',

                        background: '', /*  #FFFFFF */
                        top: '5%',
                        
                        position: 'absolute',
                        width: '650px',
                        left: ($(window).width() - $('.caja').outerWidth())/2
                }
        });
    });
}

var contador=1;

function limpiarFilasFacturaVentaCondominios(con){
    //alert ("entro "+con);
    if($('#txtIdServicio'+con).val() >= 1){
        $("#txtIdIvaS"+con).val("0");
        $("#txtIvaS"+con).val("0");
        $("#txtIdServicio"+con).val("0");
        $("#txtCodigoServicio"+con).val("");
        $("#txtDescripcionS"+con).val("");
        $("#txtCantidadS"+con).val("");
        //$("#txtPrecioS"+con).val("");
        $("#txtCalculoIvaS"+con).val("0");
        $("#txtValorUnitarioS"+con).val("0");
        $("#txtValorTotalS"+con).val("0");

        $("#txtSubtotalFVC"+con).val("0");
        $("#txtDescuentoFVC"+con).val("0");
        $("#txtTotalIvaFVC"+con).val("0");
        $("#txtOtrosFVC"+con).val("0");
        $("#txtTotalFVC"+con).val("0");
        calculaCantidadFacturaVentaCondominios(con);
        asientosQuitadosFVC();

    }else{

    }

}

function AgregarFilasFacVentaCondominios(){
  //  cadena = cadena + "<td> <input type='text' maxlength='10' id='txtCantidadS"+contador+"' name='txtCantidadS"+contador+"' style='margin: 0px; width: 100%; text-align: right; ' class='text_input' onKeyUp=\"calculaCantidadFacturaVentaCondominios("+contador+")\" onclick=\"calculaCantidadFacturaVentaCondominios("+contador+")\" onKeyPress=\"return soloNumeros(event)\" autocomplete='off' > </td>";
  
    cadena = "";
    cadena = cadena + "<tr>";
    cadena = cadena + "<td style='text-align: center;' ><a onclick=\"limpiarFilasFacturaVentaCondominios("+contador+");\" title='Limpiar fila'><img style='margin-top: 5px' src='images/limpiar.gif' /></a>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIdIvaS"+contador+"' name='txtIdIvaS"+contador+"'  readonly='readonly'>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIvaS"+contador+"' name='txtIvaS"+contador+"'  readonly='readonly'> </td>";
    cadena =  cadena + "<td><input type='hidden' id='txtIdServicio"+contador+"' name='txtIdServicio"+contador+"' >     <input  style='margin: 0px; width: 100%;' type='search' id='txtCodigoServicio"+contador+"' name='txtCodigoServicio"+contador+"' class='text_input'   autocomplete='off'  placeholder='Buscar...' onclick='lookup10(this.value, "+contador+", 4);' onKeyUp='lookup10(this.value, "+contador+", 4);' />  <div class='suggestionsBox' id='suggestions10"+contador+"' style='display: none; margin-top: 0px; '> <div class='suggestionList' id='autoSuggestionsList10"+contador+"'>  </div> </div>  </td>";
    cadena = cadena + "<td colspan=''><input type='search' style='margin: 0px; width: 100%;' class='text_input' autocomplete='off'  id='txtDescripcionS"+contador+"'  name='txtDescripcionS"+contador+"'  value=''  >      </td> ";
    cadena = cadena + "<td> <input type='text' maxlength='10' id='txtCantidadS"+contador+"' name='txtCantidadS"+contador+"' style='margin: 0px; width: 100%; text-align: right; ' class='text_input' onKeyUp=\"calculaCantidadFacturaVentaCondominios("+contador+")\" onclick=\"calculaCantidadFacturaVentaCondominios("+contador+")\"  autocomplete='off' > </td>";
    cadena = cadena + "<td><select id='txtPrecioS"+contador+"' name='txtPrecioS"+contador+"' style='margin: 0px; width: 100%;' class='text_input' onchange=\"RecalcularV(frmFacVenta)\" onKeyPress=\"return precio(event)\"> <option value='1'>1</option><option value='2'>2</option><option value='3'> 3</option><option value='4'>4 </option><option value='5'>5 </option> <option value='6'>6</option> </select></td>";
    cadena = cadena + "<td><input type='text' style='margin: 0px; width: 100%; text-align: right; ' class='text_input' id='txtValorUnitarioS"+contador+"' name='txtValorUnitarioS"+contador+"' onKeyUp=\"calculaCantidadFacturaVentaCondominios("+contador+")\" onclick=\"calculaCantidadFacturaVentaCondominios("+contador+")\" autocomplete='off'  ></td>";
    cadena = cadena + "<td><input type='text' style='margin: 0px; width: 100%; text-align: right; ' class='text_input' id='txtValorTotalS"+contador+"' name='txtValorTotalS"+contador+"'  readonly='readonly'>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtCalculoIvaS"+contador+"' name='txtCalculoIvaS"+contador+"'  readonly='readonly'>  </td>";
    cadena = cadena + "</tr>";

    document.getElementById('txtContadorFilasFVC').value=contador;
    contador++;

    $("#tblBodyFacVentaCondominios ").append(cadena);

    //fn_dar_eliminar();
    //fn_cantidad();
} 

function calculaCantidadFacturaVentaCondominios(posicion){
    //FUNCION QUE PERMITE RECALCULAR EL VALOR IVA SUBTOTAL Y EL TOTAL
    var suma =0;
    var calculoIva = 0;
    var iva = 0;
    cantidad = $("#txtCantidadS"+posicion).val();
    valorUnitario = $("#txtValorUnitarioS"+posicion).val();
    suma = parseFloat(valorUnitario * cantidad);
    //if($("#idIva"+posicion).val() >= 1 && $("#iva"+posicion).val() >= 1){
        iva = $("#txtIvaS"+posicion).val();
        calculoIva = ((suma * iva ) /100);
    //}

    $("#txtValorTotalS"+posicion).val(suma.toFixed(4));
    $("#txtCalculoIvaS"+posicion).val(calculoIva.toFixed(4));
    calculoSubTotalFacturaVentaCondominios();
}

function calculoSubTotalFacturaVentaCondominios(){
    var sumaValorTotal = 0;
    var sumaCalculoIva = 0;

    for(i=1;i<contador;i++){
        valorTotal = $("#txtValorTotalS"+i).val();
        calculoIva = $("#txtCalculoIvaS"+i).val();
        if(valorTotal == ""){
            valorTotal=0;
        }
        if(calculoIva == ""){
            calculoIva=0;
        }
        sumaValorTotal = sumaValorTotal + parseFloat(valorTotal);
        sumaCalculoIva = sumaCalculoIva + parseFloat(calculoIva);
    }
    document.getElementById('txtSubtotalFVC').value=(sumaValorTotal).toFixed(4);
    document.getElementById('txtTotalIvaFVC').value=(sumaCalculoIva).toFixed(4);
	var ent=Math.floor(parseFloat(sumaValorTotal) + parseFloat(sumaCalculoIva));
	var dec=(parseFloat(sumaValorTotal) + parseFloat(sumaCalculoIva)%1)*10;
	if(dec>0)
		dec=(10-(parseFloat(dec)%1)*10)/100;
	else
		dec=0;
		
		if(document.getElementById('Redondeo').value>0){
	    	document.getElementById('txtOtrosFVC').value=dec.toFixed(4);
		     var total = (parseFloat(sumaValorTotal) + parseFloat(sumaCalculoIva)+parseFloat(dec));
		}else{
		   	document.getElementById('txtOtrosFVC').value=0.00;
		     var total = (parseFloat(sumaValorTotal) + parseFloat(sumaCalculoIva));
		}

   
	 $("#txtTotalFVC").val(total.toFixed(2));
	 	

    calculoTotalFacturaVentaCondominios();
}

function calculoTotalFacturaVentaCondominios(){
	
	
    var txtSubtotal = $("#txtSubtotalFVC").val();
    var txtTotalIva = $("#txtTotalIvaFVC").val();
	
    var total = (parseFloat(txtSubtotal) + parseFloat(txtTotalIva));
	var ent=Math.floor(total);
	var dec=(parseFloat(total)%1)*10;
	if(dec>0)
		dec=(10-(parseFloat(dec)%1)*10)/100;
	else
		dec=0;
	if(document.getElementById('Redondeo').value>0){
		document.getElementById('txtOtrosFVC').value=dec.toFixed(4);
		 var total = (parseFloat(txtSubtotal) + parseFloat(txtTotalIva)+parseFloat(dec));
	}else{
	    document.getElementById('txtOtrosFVC').value=0.00;
	    var total = (parseFloat(txtSubtotal) + parseFloat(txtTotalIva));
	   
	}
   
    $("#txtTotalFVC").val(total.toFixed(2));
}

function asientosQuitadosFVC(){// funcion para restar al contador de asientos agregados
    asientosAgregados = $('#txtContadorAsientosAgregadosFVC').val();
    if(asientosAgregados >= 1){
        asientosAgregados --;
        $('#txtContadorAsientosAgregadosFVC').val(asientosAgregados);
    }

}




//********************************  FORMAS DE PAGO ***********************************************

function forma_pago(){

        $("#div_oculto").load("ajax/formaPago.php", function(){
		$.blockUI({
			message: $('#div_oculto'),

		overlayCSS: {backgroundColor: '#111'},
			css:{

				'-webkit-border-radius': '3px',
                '-moz-border-radius': '3px',
                'box-shadow': '6px 6px 20px gray',
				top: '5%',
				left: '15%',
				width: '75%',
                position: 'absolute'


			}
		});
                listar_formas_pago();
	});

}

function listar_formas_pago(){
 //PAGINA: formas de pago.php
 //alert("entro");
    var str = $("#form").serialize();
    $.ajax({
            url: 'ajax/listarFormasPago.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#div_listar_forma_pagos").html(data);
                 cantidad_formas_pago();
            }
    });
}


function agregarFormaPagos(){
	nFilasFormaPago = 0;
    numFilas1 = document.getElementById('txtNumeroFila').value;
    nFilasFormaPago = numFilas1;
    nFilasFormaPago++;
    document.getElementById("mensaje2").innerHTML="";
    cadena = "<tr>";
    cadena = cadena + "<td width='5%'><input style='width: 100%' type='text' id='txtIdFormaPago"+nFilasFormaPago+"' name='txtIdFormaPago"+nFilasFormaPago+"' class='form-control' disabled='true' />    <input type='hidden' readonly='readonly' id='txtIdCuenta"+nFilasFormaPago+"' name='txtIdCuenta"+nFilasFormaPago+"' value='0' class='text_input3'/>    </td>";
    cadena = cadena + "<td width='35%'><input style='width: 100%' type='text' id='txtNombre"+nFilasFormaPago+"' name='txtNombre"+nFilasFormaPago+"' title='Ingrese un nombre' style='text-transform: capitalize;' class='form-control' required='required' onKeyPress='' maxlength='200' placeholder='Nombre' autocomplete='off'/></td>";
    cadena = cadena + "<td width='15%'><input style='width: 100%;' type='search' id='txtCodigo"+nFilasFormaPago+"' name='txtCodigo"+nFilasFormaPago+"' class='form-control' value='' onclick='lookup2(this.value,"+nFilasFormaPago+", 5);' onKeyUp='lookup2(this.value,"+nFilasFormaPago+", 5);'  autocomplete='off'  placeholder='Buscar..'   />       <div class='suggestionsBox' id='suggestions"+nFilasFormaPago+"' style='display: none; margin-top: 0px; width: 350px  '> <div class='suggestionList' id='autoSuggestionsList"+nFilasFormaPago+"'> &nbsp; </div> </div>   </td>";
    cadena = cadena + "<td width='25%'><input type='search' id='txtCuenta2"+nFilasFormaPago+"' name='txtCuenta2"+nFilasFormaPago+"' value=''  style='margin: 0px; width: 100%;' class='form-control' autocomplete='off'   /></td> ";
    cadena = cadena + "<td width='25%'><select style='width: 100%' name='cmbTipoMovimientoFVC"+nFilasFormaPago+"' id='cmbTipoMovimientoFVC"+nFilasFormaPago+"' class='form-control' ></select></td> ";
    cadena = cadena + "<td align='center'><input type='checkbox' name='checkDiario"+nFilasFormaPago+"' id='checkDiario"+nFilasFormaPago+"' value='ON' /></td>";
    cadena = cadena + "<td align='center'><input type='checkbox' name='checkIngreso"+nFilasFormaPago+"' id='checkIngreso"+nFilasFormaPago+"' value='ON' /></td>";
    cadena = cadena + "<td align='center'><input type='checkbox' name='checkEgreso"+nFilasFormaPago+"' id='checkEgreso"+nFilasFormaPago+"' value='ON' /></td>";    
    cadena = cadena + "<td align='center'><input type='checkbox' name='checkEgreso"+nFilasFormaPago+"' id='checkEgreso"+nFilasFormaPago+"' value='ON' /></td>";    
    cadena = cadena + "<td><a title='Guardar' href='javascript: guardarFormaPago("+nFilasFormaPago+", 1);'<span class='fa fa-floppy-o' aria-hidden='true'></a></td>";
    cadena = cadena + "<td><a class='eliminaFormaPago' title='Eliminar'><span class='glyphicon glyphicon-trash' aria-hidden='true'></a></td>";
    cadena = cadena + "</tr>";
    $("#grillaFormaPago tbody").append(cadena);
    cantidad_formas_pago();
    eliminaFormaPago();
    cargarTipoMovimiento(6,nFilasFormaPago);
}

function cantidad_formas_pago(){
        cantidad = $("#grillaFormaPago tbody").find("tr").length;
        $("#span_cantidadFormaPago").html(cantidad);
        document.getElementById('txtNumeroFila').value = cantidad;
}

function eliminaFormaPago(){
    $("a.eliminaFormaPago").click(function(){
        respuesta = confirm("Seguro desea eliminar la fila seleccionada");
        if (respuesta){
            $(this).parents("tr").fadeOut("normal", function(){
                $(this).remove();
                nFilasFormaPago --;
                 cantidad_formas_pago();
            })
        }
    });
}

function guardarFormaPago(nFila, accion){

    //PAGINA: ajax/formaPago.php
    if(document.getElementById("txtNombre"+nFila).value != ""){
        if(document.getElementById("txtIdCuenta"+nFila).value != "" && document.getElementById("txtIdCuenta"+nFila).value >= 1){
            if(document.getElementById("cmbTipoMovimientoFVC"+nFila).value != "" && document.getElementById("cmbTipoMovimientoFVC"+nFila).value >= 1){
                var respuesta43 = confirm("Seguro desea guardar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
                if (respuesta43){
                var str = $("#frmFormasPago").serialize();
                    $.ajax({
                            url: 'sql/formasPago.php',
                            type: 'post',
                            data: str+"&numeroFilaSelec="+nFila+"&txtAccion="+accion,
                            success: function(data){
                                    //$("#div_listar_RegistroDiario").html(data);
                                    document.getElementById("mensaje2").innerHTML=""+data;
                                    listar_formas_pago();

                            }
                    });
                }
            }else{
                alert("No se puede guardar. Seleccione el tipo de movimiento");
                document.getElementById("cmbTipoMovimientoFVC"+nFila).focus();
            }
            
        }else{
            alert("No se puede guardar. Seleccione una cuenta contable");
            document.getElementById("txtCodigo"+nFila).focus();
        }  

    }else{
        alert("No se puede guardar. El campo Nombre esta vacio.");
        document.getElementById("txtNombre"+nFila).focus();
    }
}

function modificar_forma_pago(idFormaPago, accion, fila){
    //PAGINA: ajax/formaPago.php
    if(document.getElementById("txtNombre"+fila).value != ""){
        if(document.getElementById("txtIdCuenta"+fila).value != "" && document.getElementById("txtIdCuenta"+fila).value >= 1){
            if(document.getElementById("cmbTipoMovimientoFVC"+fila).value != "" && document.getElementById("cmbTipoMovimientoFVC"+fila).value >= 1){
                var respuesta44 = confirm("Seguro desea modificar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
                if (respuesta44){
                var str = $("#frmFormasPago").serialize();
                    $.ajax({
                            url: 'sql/formasPago.php',
                            type: 'post',
                            data: str+"&idFormaPago="+idFormaPago+"&txtAccion="+accion+"&NumeroFilaSeleccionada="+fila,
                            success: function(data){
                                    //$("#div_listar_RegistroDiario").html(data);
                                    document.getElementById("mensaje2").innerHTML=""+data;
                                    listar_formas_pago();
                            }
                    });
                }
            }else{
                alert("No se puede guardar. Seleccione el tipo de movimiento");
                document.getElementById("cmbTipoMovimientoFVC"+fila).focus();
            }

        }else{
                alert("No se puede guardar. Seleccione una cuenta contable");
                document.getElementById("txtCodigo"+fila).focus();
            }

    }else{
        alert("No se puede guardar. El campo Nombre esta vacio.");
        document.getElementById("txtNombre"+fila).focus();
    }


}

function eliminar_forma_pago(idFormaPago, accion){
    //PAGINA: ajax/formaPago.php
    var respuesta45 = confirm("Seguro desea eliminar este Registro? \nEsta acci\u00f3n eliminara de forma permanente la fila seleccionada");
    if (respuesta45){
            $.ajax({
                    url: 'sql/formasPago.php',
                    data: 'idFormaPago='+idFormaPago+'&txtAccion='+accion,
                    type: 'post',
                    success: function(data){
                            if(data!="")
                            //alert(data);
                            document.getElementById("mensaje2").innerHTML=""+data;
                            listar_formas_pago();
                    }
            });
    }
}

function cargarFormasPago(aux){
    
    ajax2=objetoAjax();    
    ajax2.open("POST", "sql/formasPago.php",true);    
    ajax2.onreadystatechange=function(){        
        if (ajax2.readyState==4){
            var respuesta2=ajax2.responseText;
            
            asignaFormasPago(respuesta2);
        }
    }
    ajax2.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax2.send("txtAccion="+aux);
}

function asignaFormasPago(cadena){
    
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;
    
    limpiaCmbFormasPago();
    document.getElementById("cmbFormaPagoFP").options[0] = new Option("Seleccione Forma de Pago","0");
    for(i=1;i<limite;i=i+2){
        document.getElementById("cmbFormaPagoFP").options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }

}

function limpiaCmbFormasPago()
{
    for (m=document.getElementById("cmbFormaPagoFP").options.length-1;m>=0;m--){
        document.getElementById("cmbFormaPagoFP").options[m]=null;
    }    
}


function cargarTipoMovimiento(aux, contador){

    ajax2=objetoAjax();
    ajax2.open("POST", "sql/formasPago.php",true);
    ajax2.onreadystatechange=function(){
        if (ajax2.readyState==4){
            var respuesta2=ajax2.responseText;
            asignaTipoMovimiento(respuesta2, contador);
        }
    }
    ajax2.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax2.send("txtAccion="+aux)
}



function asignaTipoMovimiento(cadena, contador){
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;

    limpiaCmbTipoMovimiento(contador);
    document.getElementById("cmbTipoMovimientoFVC"+contador).options[0] = new Option("Seleccione Tipo de movimiento","0");
    for(i=1;i<limite;i=i+2){
        document.getElementById("cmbTipoMovimientoFVC"+contador).options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }

}

function limpiaCmbTipoMovimiento(contador)
{
    for (m=document.getElementById("cmbTipoMovimientoFVC"+contador).options.length-1;m>=0;m--){
        document.getElementById("cmbTipoMovimientoFVC"+contador).options[m]=null;
    }
}



//****************************  IVA  ********************************************

function impuestos(){

    
        $("#div_oculto").load("ajax/impuestos.php", function(){
            $.blockUI({
                message: $('#div_oculto'),

        overlayCSS: {backgroundColor: '#111'},
                css:{

                    '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px',

                    background: '', /* #f9f9f9*/
                    top: '5%',

                    position: 'absolute',
                    width: '400px',
                    left: ($(window).width() - $('.caja').outerWidth())/2

                }
            });
            
            //mostrarPlanCuentas(14);
	});
   

}

function listar_impuestos(){
 //PAGINA: impuestos.php
    var str = $("#frmImpuestos").serialize();
    $.ajax({
            url: 'ajax/listarImpuestos.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#div_listar_impuestos").html(data);
                 cantidad_filas_impuestos();
            }
    });
}

function guardar_impuestos(nFila, accion){

    //PAGINA: impuestos.php
    if(document.getElementById("txtPorcentaje"+nFila).value != ""){
        if(document.getElementById("cmbCuentaContableI").value != 0){
            var respuesta49 = confirm("Seguro desea guardar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
            if (respuesta49){
            var str = $("#frmImpuestos").serialize();
                $.ajax({
                        url: 'sql/impuestos.php',
                        type: 'post',
                        data: str+"&numeroFilaSelec="+nFila+"&txtAccion="+accion,
                        success: function(data){
                            //$("#div_listar_RegistroDiario").html(data);
                            document.getElementById("mensajeImpuestos").innerHTML=""+data;
                            listar_impuestos();
                            muestra_iva_actual(4);
                        }
                });
            }

        }else{
            alert("No se puede guardar. Seleccione una cuenta Contable");
            document.getElementById("cmbCuentaContableI").focus();
        }

    }else{
        alert("No se puede guardar. El campo porcentaje esta vacio");
        document.getElementById("txtPorcentaje"+nFila).focus();
    }
    
}

function modificar_impuestos(id, accion, fila){
    //PAGINA: parametrosComisiones.php
    if(document.getElementById("txtPorcentaje"+fila).value != ""){
        if(document.getElementById("cmbCuentaContableI").value != 0){
            var respuesta50 = confirm("Seguro desea modificar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
            if (respuesta50){
            var str = $("#frmImpuestos").serialize();
                $.ajax({
                        url: 'sql/impuestos.php',
                        type: 'post',
                        data: str+"&idImpuestos="+id+"&txtAccion="+accion+"&NumeroFilaSeleccionada="+fila,
                        success: function(data){
                            //$("#div_listar_RegistroDiario").html(data);
                            document.getElementById("mensajeImpuestos").innerHTML=""+data;
                            listar_impuestos();
                            muestra_iva_actual(4);
                        }
                });
               }
       }else{
            alert("No se puede guardar. Seleccione una cuenta Contable");
            document.getElementById("cmbCuentaContableI").focus();
        }

    }else{
        alert("No se puede guardar. El campo porcentaje esta vacio");
        document.getElementById("txtPorcentaje"+fila).focus();
    }
}

function eliminar_impuestos(id, accion){
    //PAGINA: parametrosComisiones.php
    var respuesta51 = confirm("Seguro desea eliminar este Registro? \nSi los datos que est\u00e1 intentando borrar tienen ya una relaci\u00f3n, esta acci\u00f3n puede afectar a las facturas compras y ventas y generar fallas en los datos. \nEsta acci\u00f3n eliminara de forma permanente la fila seleccionada");
    if (respuesta51){
        $.ajax({
                url: 'sql/impuestos.php',
                data: 'idIva='+id+'&txtAccion='+accion,
                type: 'post',
                success: function(data){
                    if(data!="")
                    //alert(data);
                    document.getElementById("mensajeImpuestos").innerHTML=""+data;
                    listar_impuestos();
                    muestra_iva_actual(4);
                }
        });
    }
}

nFilasImpuestos = 0;
function agregarImpuestos(){
    numFilas1 = document.getElementById('txtNumeroFila').value;
    nFilasImpuestos = numFilas1;
    nFilasImpuestos++;
    document.getElementById("mensajeImpuestos").innerHTML="";
    cadena = "<tr>";
    cadena = cadena + "<td><input type='text' id='txtIdIva"+nFilasImpuestos+"' name='txtIdIva"+nFilasImpuestos+"' class='text_input6' disabled /> </td>";
    cadena = cadena + "<td><input type='text' id='txtPorcentaje"+nFilasImpuestos+"' name='txtPorcentaje"+nFilasImpuestos+"' class='text_input6' onKeyPress='return soloNumeros(event)' maxlength='20' placeholder='0.00' autocomplete='off'/>%</td>";
    cadena = cadena + "<td><select id='txtEstado"+nFilasImpuestos+"' name='txtEstado"+nFilasImpuestos+"' class='text_input6' style='width: 80px;'><option id='Activo'>Activo</option></select></td>";

    cadena = cadena + "<td><a title='Guardar' href='javascript: guardar_impuestos("+nFilasImpuestos+", 1);'><img src='images/save16.png' /></a></td>";
    cadena = cadena + "<td><a class='eliminaImpuestos' title='Eliminar'><img src='images/delete.png' /></a></td>";
    //cadena = "</tr>";
    $("#grillaImpuestos tbody").append(cadena);
    cantidad_filas_impuestos();
    eliminarImpuestos();
}

function cantidad_filas_impuestos(){
    cantidad = $("#grillaImpuestos tbody").find("tr").length;
    $("#span_cantidadImpuestos").html(cantidad);
    document.getElementById('txtNumeroFila').value = cantidad;
}

function eliminarImpuestos(){
    $("a.eliminaImpuestos").click(function(){
        respuesta = confirm("Seguro desea eliminar la fila seleccionada");
        if (respuesta){
            $(this).parents("tr").fadeOut("normal", function(){
                $(this).remove();
                nFilasImpuestos --;
                cantidad_filas_impuestos();
            })
        }
    });

}

function muestra_iva_actual(txtAccion){
    //saca el IVA activo o actual pagina facturaVentaCondominios.php
    //id = String(codigo.value);
    
    ajax1=objetoAjax();
    ajax1.open("POST", "sql/impuestos.php",true);
    ajax1.onreadystatechange=function(){
        if (ajax1.readyState==4){
            var respuesta1=ajax1.responseText;            
            array = respuesta1.split( "*" );
            document.getElementById("ivaActual").innerHTML=""+array[1];
            $("#txtIdIvaFVC").val(array[0]);
        }
    }
    ajax1.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax1.send("txtAccion="+txtAccion)
}




//************************************ UNIDADES  ************************************

function mostrarTablaUnidades(aux){

    ajax2=objetoAjax();
    ajax2.open("POST", "sql/productos.php",true);
    ajax2.onreadystatechange=function(){
        if (ajax2.readyState==4){
            var respuesta2=ajax2.responseText;
            asignaTablaUnidades(respuesta2);
        }
    }
    ajax2.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax2.send("txtAccion="+aux)
}

function asignaTablaUnidades(cadena){
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;
    cont2=1;
    limpiaCmbUnidades();
    document.frmServicios.cmbUnidad.options[0] = new Option("Seleccione Unidad","0");
    for(i=1;i<limite;i=i+2){
        document.frmServicios.cmbUnidad.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }

    document.frmIngresoProductos.cmbUnidad.options[0] = new Option("Seleccione Unidad","0");
    for(i2=1;i2<limite;i2=i2+2){
        document.frmIngresoProductos.cmbUnidad.options[cont2] = new Option(array[i2+1], array[i2]);
        cont2++;
    }

}

function limpiaCmbUnidades()
{
    for (m=document.frmIngresoProductos.cmbUnidad.options.length-1;m>=0;m--){
        document.frmIngresoProductos.cmbUnidad.options[m]=null;
    }
    for (m2=document.frmServicios.cmbUnidad.options.length-1;m2>=0;m2--){
        document.frmServicios.cmbUnidad.options[m2]=null;
    }
}







//******************************************  UNIDADES  *****************************//

function unidades(){
 //PAGINA: inventarios.php
    $("#div_oculto").load("ajax/unidades.php", function(){
        $.blockUI({
                message: $('#div_oculto'),
        overlayCSS: {backgroundColor: '#111'},
                css:{
                        '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px',

                        background: '', /* #FFFFFF */
                        top: '5%',

                        position: 'absolute',

                        left: ($(window).width() - $('.caja').outerWidth())/2
                }
        });listar_unidades();
    });
}

function listar_unidades(){
 //PAGINA: inventarios.php
 //alert "voy a listar";
    var str = $("#form").serialize();
    $.ajax({
            url: 'ajax/listarUnidades.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#div_listar_unidades").html(data);
                 cantidad_filas_unidades();
            }
    });
}

function guardar_unidades(nFila, accion){

    //PAGINA: inventarios.php
    if(document.getElementById("txtUnidad"+nFila).value==""){
        alert("Faltan Campos por llenar");

    }else{
        var respuesta43 = confirm("Seguro desea guardar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
        if (respuesta43){
        var str = $("#form").serialize();
            $.ajax({
                    url: 'sql/productos.php',
                    type: 'post',
                    data: str+"&numeroFilaSelec="+nFila+"&txtAccion="+accion,
                    success: function(data){
                            //$("#div_listar_RegistroDiario").html(data);
                            document.getElementById("mensajeUnidades").innerHTML=""+data;
                            listar_unidades();
                            mostrarTablaUnidades(9); // pagina: inventarios.php
                    }
            });
           }
    }
}

function modificar_unidades(id, accion, fila){
    //PAGINA: ajax/unidades.php
    var respuesta44 = confirm("Seguro desea modificar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
    if (respuesta44){
    var str = $("#form").serialize();
	$.ajax({
		url: 'sql/productos.php',
		type: 'post',
		data: str+"&idUnidad="+id+"&txtAccion="+accion+"&NumeroFilaSeleccionada="+fila,
		success: function(data){
			//$("#div_listar_RegistroDiario").html(data);
                        document.getElementById("mensajeUnidades").innerHTML=""+data;
                        listar_unidades();
                        mostrarTablaUnidades(9);
		}
	});
       }
}

function eliminar_unidades(id, accion){
    //PAGINA: ajax/unidades.php
    var respuesta45 = confirm("Seguro desea eliminar este Registro? \nEsta acci\u00f3n eliminara de forma permanente la fila seleccionada");
    if (respuesta45){
            $.ajax({
                    url: 'sql/productos.php',
                    data: 'idUnidad='+id+'&txtAccion='+accion,
                    type: 'post',
                    success: function(data){
                            if(data!="")
                            //alert(data);
                            document.getElementById("mensajeUnidades").innerHTML=""+data;
                            listar_unidades();
                            mostrarTablaUnidades(9); // pagina: inventarios.php
                    }
            });
    }
}

nFilasUnidades = 0;
function agregarUnidades(){
    numFilas1 = document.getElementById('txtNumeroFila').value;
    nFilasUnidades = numFilas1;
    nFilasUnidades++;
    //document.getElementById("mensaje1").innerHTML="";
    cadena = "<tr>";
    cadena = cadena + "<td><input type='text' id='txtIdUnidad"+nFilasUnidades+"' name='txtIdUnidad"+nFilasUnidades+"' class='text_input6' disabled='true' /> </td>";
    cadena = cadena + "<td><input type='text' id='txtUnidad"+nFilasUnidades+"' name='txtUnidad"+nFilasUnidades+"' title='Ingrese un nombre' style='text-transform: capitalize; margin-top: 0px;' class='text_input4' required='required' maxlength='100' placeholder='Nombre de la unidad' autocomplete='off'/></td>";
    cadena = cadena + "<td><a title='Guardar' href='javascript: guardar_unidades("+nFilasUnidades+", 11);'><img src='images/save16.png' /></a></td>";
    cadena = cadena + "<td><a class='eliminaUnidades' title='Eliminar'><img src='images/delete.png' /></a></td>";
    //cadena = "</tr>";
    $("#grillaUnidades tbody").append(cadena);
    cantidad_filas_unidades();
    eliminarUnidades();
}

function cantidad_filas_unidades(){
        cantidad = $("#grillaUnidades tbody").find("tr").length;
        $("#span_cantidadUnidades").html(cantidad);
        document.getElementById('txtNumeroFila').value = cantidad;
}

function eliminarUnidades(){
    $("a.eliminaUnidades").click(function(){
        respuesta = confirm("Seguro desea eliminar la fila seleccionada");
        if (respuesta){
            $(this).parents("tr").fadeOut("normal", function(){
                $(this).remove();
                nFilasUnidades --;
                 cantidad_filas_unidades();
            })
        }
    });
}

function mostrarUnidades_Bod(aux){
//	alert("unidades....");
	//alert(aux);
    ajax2=objetoAjax();
    ajax2.open("POST", "sql/productos.php",true);
	//alert("despues");
    ajax2.onreadystatechange=function(){
        if (ajax2.readyState==4){
            var respuesta2=ajax2.responseText;
			//alert(respuesta2);
            asignaUnidades_Bod(respuesta2);
        }
    }
    ajax2.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax2.send("txtAccion="+aux)
}

function asignaUnidades_Bod(cadena){
    array = cadena.split( "?" );
    limite=array.length;
	//alert(limite);
	//alert(array);
    cont=1;
    cont3=1;
    limpiaCmbUnidades_Bod();
	//alert("despues de limpiar");
	document.frmBodegas.cmbUnidad.options[0] = new Option("Seleccione Unidad","0");
    for(i3=1;i3<limite;i3=i3+2){
        document.frmBodegas.cmbUnidad.options[cont3] = new Option(array[i3+1], array[i3]);
        cont3++;
    }
}

function limpiaCmbUnidades_Bod()
{
	//alert("Limpiar Bodegas");
	//x3 = document.frmBodegas.cmbUnidad.options.length;
	//alert("x3");
	//alert(x3);
    for (m3=document.frmBodegas.cmbUnidad.options.length-1;m3>=0;m3--){
		//cmbUnidad
        document.frmBodegas.cmbUnidad.options[m3]=null;
		//alert("limpir bodega");
    }
}



//****************************************  AREAS O GRUPO   ************************************

function area_grupo(){
 //PAGINA: inventarios.php
    $("#div_oculto").load("ajax/areaGrupo.php", function(){
        $.blockUI({
                message: $('#div_oculto'),
        overlayCSS: {backgroundColor: '#111'},
                css:{
                        '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px',

                        background: '', /* #FFFFFF */
                        top: '5%',

                        position: 'absolute',

                        left: ($(window).width() - $('.caja').outerWidth())/2
                }
        });
        listar_area_grupo();
       
    });
}

function listar_area_grupo(){
 //PAGINA: productos.php
    var str = $("#form").serialize();
    $.ajax({
            url: 'ajax/listarAreaGrupo.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#div_listar_area_grupo").html(data);
                 cantidad_filas_AreaGrupo();
            }
    });
}

nFilasCategoria = 0;
function agregarFilaAreaGrupo(){
    numFilas1 = document.getElementById('txtNumeroFila').value;
    nFilasCategoria = numFilas1;
    nFilasCategoria++;
    document.getElementById("mensaje1").innerHTML="";
    cadena = "<tr>";
    cadena = cadena + "<td><input style='width: 100%' type='text' id='txtIdCategoria"+nFilasCategoria+"' name='txtIdCategoria"+nFilasCategoria+"' class='text_input1' disabled='true' /> </td>";
    cadena = cadena + "<td><input type='text' id='txtCategoria"+nFilasCategoria+"' name='txtCategoria"+nFilasCategoria+"' title='Ingrese un nombre' style='margin-top: 0px; width: 100%' class='text_input1' required='required' onKeyPress='' maxlength='100' placeholder='Nombre de Area o Grupo' autocomplete='off'/></td>";
    cadena = cadena + "<td><a title='Guardar' href='javascript: guardar_area_grupo("+nFilasCategoria+", 5);'><img src='images/save16.png' /></a></td>";
    cadena = cadena + "<td><a class='eliminaFilaAreaGrupo' title='Eliminar'><img src='images/delete.png' /></a></td>";
    //cadena = "</tr>";
    $("#tblAreaGrupo tbody").append(cadena);
    cantidad_filas_AreaGrupo();
    eliminarFilaAreaGrupo();
}

function cantidad_filas_AreaGrupo(){
        cantidad = $("#tblAreaGrupo tbody").find("tr").length;
        $("#span_cantidadFilasAreaGrupo").html(cantidad);
        document.getElementById('txtNumeroFila').value = cantidad;
}

function eliminarFilaAreaGrupo(){
    $("a.eliminaFilaAreaGrupo").click(function(){
        respuesta = confirm("Seguro desea eliminar la fila seleccionada");
        if (respuesta){
            $(this).parents("tr").fadeOut("normal", function(){
                $(this).remove();
                nFilasCategoria --;
                 cantidad_filas_AreaGrupo();
            })
        }
    });
}

function guardar_area_grupo(nFila, accion){
    

    //PAGINA: areaGrupo.php
    if(document.getElementById("txtCategoria"+nFila).value != ""){
        
            var respuesta43 = confirm("Seguro desea guardar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
            if (respuesta43){
            var str = $("#form").serialize();
                $.ajax({
                        url: 'sql/productos.php',
                        type: 'post',
                        data: str+"&numeroFilaSelec="+nFila+"&txtAccion="+accion,
                        success: function(data){
                                //$("#div_listar_RegistroDiario").html(data);
                                document.getElementById("mensajeCategorias").innerHTML=""+data;
                                listar_area_grupo();
                                mostrarAreaGrupo(8); // pagina: inventarios.php
                        }
                });
             }        

    }else{
        alert("No se puede guardar. El campo nombre esta vacio");
        document.getElementById("txtCategoria"+nFila).focus();
    }
}

function modificar_area_grupo(id, accion, fila){
    //PAGINA: productos.php
    if(document.getElementById("txtCategoria"+fila).value != ""){
        
            var respuesta44 = confirm("Seguro desea modificar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
            if (respuesta44){
            var str = $("#form").serialize();
                $.ajax({
                        url: 'sql/productos.php',
                        type: 'post',
                        data: str+"&idCategorias="+id+"&txtAccion="+accion+"&NumeroFilaSeleccionada="+fila,
                        success: function(data){
                                //$("#div_listar_RegistroDiario").html(data);
                                document.getElementById("mensajeCategorias").innerHTML=""+data;
                                listar_area_grupo();
                                mostrarAreaGrupo(8); // pagina: inventarios.php
                        }
                });
           }
       
    }else{
        alert("No se puede guardar. El campo nombre esta vacio");
        document.getElementById("txtCategoria"+fila).focus();
    }
}

function eliminar_area_grupo(id, accion){
    //PAGINA: productos.php
    var respuesta45 = confirm("Seguro desea eliminar este Registro? \nEsta acci\u00f3n eliminara de forma permanente la fila seleccionada");
    if (respuesta45){
            $.ajax({
                    url: 'sql/productos.php',
                    data: 'idCategorias='+id+'&txtAccion='+accion,
                    type: 'post',
                    success: function(data){
                            if(data!="")
                            //alert(data);
                            document.getElementById("mensajeCategorias").innerHTML=""+data;
                            listar_area_grupo();
                            mostrarAreaGrupo(8); // pagina: inventarios.php
                    }
            });
    }
}

function mostrarAreaGrupo(aux){

    ajax3=objetoAjax();
    ajax3.open("POST", "sql/productos.php",true);
    ajax3.onreadystatechange=function(){
        if (ajax3.readyState==4){
            var respuesta3=ajax3.responseText;
            asignaAreaGrupo(respuesta3);
        }
    }
    ajax3.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax3.send("txtAccion="+aux)
}

function asignaAreaGrupo(cadena){
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;
    cont2=1;
    limpiaCmbAreaGrupo();
    document.frmServicios.cmbCategoria.options[0] = new Option("Seleccione Ãrea o Grupo","0");
    for(i=1;i<limite;i=i+2){
        document.frmServicios.cmbCategoria.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }

    document.frmIngresoProductos.cmbCategoria.options[0] = new Option("Seleccione Categoria","0");
    for(i2=1;i2<limite;i2=i2+2){
        document.frmIngresoProductos.cmbCategoria.options[cont2] = new Option(array[i2+1], array[i2]);
        cont2++;
    }

}

function limpiaCmbAreaGrupo()
{
    for (m=document.frmIngresoProductos.cmbCategoria.options.length-1;m>=0;m--){
        document.frmIngresoProductos.cmbCategoria.options[m]=null;
    }
    for (m2=document.frmServicios.cmbCategoria.options.length-1;m2>=0;m2--){
        document.frmServicios.cmbCategoria.options[m2]=null;
    }
}




//***************************************  TIPO SERVICIOS   **************************************

function mostrarTablaTipoServicio(aux){

    ajax4=objetoAjax();
    ajax4.open("POST", "sql/productos.php",true);
    ajax4.onreadystatechange=function(){
        if (ajax4.readyState==4){
            var respuesta4=ajax4.responseText;
            asignaTablaTipoServicio(respuesta4);
        }
    }
    ajax4.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax4.send("txtAccion="+aux)
}

function asignaTablaTipoServicio(cadena){
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;

    limpiaCmbTipoServicio();
    document.frmServicios.cmbTipoServicio.options[0] = new Option("Seleccione Tipo de servicio","0");
    for(i=1;i<limite;i=i+2){
        document.frmServicios.cmbTipoServicio.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }


}

function limpiaCmbTipoServicio()
{
    for (m=document.frmServicios.cmbTipoServicio.options.length-1;m>=0;m--){
        document.frmServicios.cmbTipoServicio.options[m]=null;
    }

}

function tipoServicio(){
 //PAGINA: inventarios.php
    $("#div_oculto").load("ajax/tipoServicios.php", function(){
        $.blockUI({
                message: $('#div_oculto'),
        overlayCSS: {backgroundColor: '#111'},
                css:{
                        '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px',

                        background: '', /* #FFFFFF */
                        top: '5%',

                        position: 'absolute',

                        left: ($(window).width() - $('.caja').outerWidth())/2
                }
        });listar_tipo_servicios();
    });
}

function listar_tipo_servicios(){
 //PAGINA: inventarios.php
    var str = $("#form").serialize();
    $.ajax({
            url: 'ajax/listarTipoServicios.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#div_listar_tipo_servicios").html(data);
                 cantidad_filas_tipo_servicios();
            }
    });
}

function guardar_tipo_servicios(nFila, accion){

    //PAGINA: inventarios.php
    if(document.getElementById("txtNombreServicio"+nFila).value==""){
        alert("Faltan Campos por llenar");

    }else{
        var respuesta43 = confirm("Seguro desea guardar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
        if (respuesta43){
        var str = $("#form").serialize();
            $.ajax({
                    url: 'sql/productos.php',
                    type: 'post',
                    data: str+"&numeroFilaSelec="+nFila+"&txtAccion="+accion,
                    success: function(data){
                            //$("#div_listar_RegistroDiario").html(data);
                            document.getElementById("mensajeTipoServicio").innerHTML=""+data;
                            listar_tipo_servicios();
                            mostrarTablaTipoServicio(10); // pagina: inventarios.php
                    }
            });
           }
    }
}

function modificar_tipo_servicios(id, accion, fila){
    //PAGINA: ajax/unidades.php
    var respuesta44 = confirm("Seguro desea modificar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
    if (respuesta44){
    var str = $("#form").serialize();
	$.ajax({
		url: 'sql/productos.php',
		type: 'post',
		data: str+"&idTipoServicio="+id+"&txtAccion="+accion+"&NumeroFilaSeleccionada="+fila,
		success: function(data){
			//$("#div_listar_RegistroDiario").html(data);
                        document.getElementById("mensajeTipoServicio").innerHTML=""+data;
                        listar_tipo_servicios();
                        mostrarTablaTipoServicio(10);
		}
	});
       }
}

function eliminar_tipo_servicios(id, accion){
    //PAGINA: ajax/unidades.php
    var respuesta45 = confirm("Seguro desea eliminar este Registro? \nEsta acci\u00f3n eliminara de forma permanente la fila seleccionada");
    if (respuesta45){
            $.ajax({
                    url: 'sql/productos.php',
                    data: 'idTipoServicio='+id+'&txtAccion='+accion,
                    type: 'post',
                    success: function(data){
                            if(data!="")
                            //alert(data);
                            document.getElementById("mensajeTipoServicio").innerHTML=""+data;
                            listar_tipo_servicios();
                            mostrarTablaTipoServicio(10); // pagina: inventarios.php
                    }
            });
    }
}

nFilasTipoServicios = 0;
function agregarTipoServicios(){
    numFilas1 = document.getElementById('txtNumeroFila').value;
    nFilasTipoServicios = numFilas1;
    nFilasTipoServicios++;
    document.getElementById("mensaje1").innerHTML="";
    cadena = "<tr>";
    cadena = cadena + "<td><input type='text' id='txtIdTipoServicio"+nFilasTipoServicios+"' name='txtIdTipoServicio"+nFilasTipoServicios+"' class='text_input6' disabled='true' /> </td>";
    cadena = cadena + "<td><input type='text' id='txtNombreServicio"+nFilasTipoServicios+"' name='txtNombreServicio"+nFilasTipoServicios+"' title='Ingrese un nombre' style='text-transform: capitalize; margin-top: 0px;' class='text_input4' required='required' maxlength='100' placeholder='Nombre tipo de servicio' autocomplete='off'/></td>";
    cadena = cadena + "<td><a title='Guardar' href='javascript: guardar_tipo_servicios("+nFilasTipoServicios+", 14);'><img src='images/save16.png' /></a></td>";
    cadena = cadena + "<td><a class='eliminaTipoServicios' title='Eliminar'><img src='images/delete.png' /></a></td>";
    //cadena = "</tr>";
    $("#grillaTipoServicios tbody").append(cadena);
    cantidad_filas_tipo_servicios();
    eliminarTipoServicios();
}

function cantidad_filas_tipo_servicios(){
        cantidad = $("#grillaTipoServicios tbody").find("tr").length;
        $("#span_cantidadTipoServicios").html(cantidad);
        document.getElementById('txtNumeroFila').value = cantidad;
}

function eliminarTipoServicios(){
    $("a.eliminaTipoServicios").click(function(){
        respuesta = confirm("Seguro desea eliminar la fila seleccionada");
        if (respuesta){
            $(this).parents("tr").fadeOut("normal", function(){
                $(this).remove();
                nFilasTipoServicios --;
                 cantidad_filas_tipo_servicios();
            })
        }
    });
}




//***********************************  PROVEEDORES  ************************************

function nuevoProveedor(){
 //PAGINA: proveedores.php
    $("#div_oculto").load("ajax/nuevoProveedor.php", function(){
        $.blockUI({
                message: $('#div_oculto'),
        overlayCSS: {backgroundColor: '#111'},
                css:{
                    '-webkit-border-radius': '10px',
    '-moz-border-radius': '10px',

                    background: '', /* #FFFFFF */
                    top: '5%',

                    position: 'absolute',

                    left: ($(window).width() - $('.caja').outerWidth())/2
                }
        });
    });
}

function mostrarTablaProveedores(aux){

    ajax5=objetoAjax();
    ajax5.open("POST", "sql/proveedores.php",true);
    ajax5.onreadystatechange=function(){
        if (ajax5.readyState==4){
            var respuesta5=ajax5.responseText;
            asignaTablaProveedores(respuesta5);
        }
    }
    ajax5.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax5.send("txtAccion="+aux)
}

function asignaTablaProveedores(cadena){
    //alert(cadena);
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;

    limpiaCmbProveedores();
    document.frmIngresoProductos.cmbProveedor.options[0] = new Option("Seleccione Proveedor","0");
    for(i=1;i<limite;i=i+2){
        document.frmIngresoProductos.cmbProveedor.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }


}

function limpiaCmbProveedores()
{
    for (m=document.frmIngresoProductos.cmbProveedor.options.length-1;m>=0;m--){
        document.frmIngresoProductos.cmbProveedor.options[m]=null;
    }

}



//***********************  SERVICOS ********************************

function guardar_servicios(accion){

    if(document.getElementById("cmbCuentaContable").value != "" && document.getElementById("cmbCuentaContable").value != 0){
        
        var str = $("#frmServicios").serialize();

         $.ajax({
            url: 'sql/servicios.php',
            type: 'post',
            data: str+"&txtAccion="+accion,
            // para mostrar el loadian antes de cargar los datos
            beforeSend: function(){
                //imagen de carga
                $("#mensaje3").html("<p align='center'><img src='images/loading.gif' /></p>");
            },
            success: function(data){
                //alert(data.length);
                document.getElementById("mensaje3").innerHTML=data;
                if(data.length == 87){
                    document.getElementById("frmServicios").reset();
                }
                listar_servicios();
            }
        });
    }else{
        alert("Selecciones una cuenta contable");
        document.getElementById("cmbCuentaContable").focus();
    }

    

}

function lookup10(txtNombre, cont, accion) {
//para agregar SERVCIO pagina: nuevaFacturaVenta.php
/* alert(txtNombre);
alert(cont);
alert(accion);
 */
    if(txtNombre.length == 0) {

        // Hide the suggestion box.
        $('#suggestions10'+cont).hide();
    } else {

        $.post("sql/servicios.php", {queryString: txtNombre, cont: cont,  txtAccion: accion}, function(data){
           // alert("entro: "+data);
			if(data.length >0) {
                $('.suggestionsBox').hide();
                $('#suggestions10'+cont).show();
                $('#autoSuggestionsList10'+cont).html(data);
             //  alert("entro: "+data);
            }
        });
    }
} // lookup

function fill10(cont, idServicio, cadena){
    //alert(" cont: "+cont+"  idServicio: "+idServicio+" cadena: "+cadena)
    //alert(cadena);

    setTimeout("$('.suggestionsBox').hide();", 50);

    //thisValue.replace(" ","");
    array = cadena.split("*");

    // este if debe ir antes de asignar a los txt
    if($('#txtIdServicio'+cont).val() >= 1){
        // cuando no usa el boton limpiar significa que
        // si hay cuenta agregada en la fila y solo esta remplazando por otra cuenta
        // ya no vuelve a sumar cuantas cuentas estan agregadas
    }else{
        // cuando usa el boton limpiar
        // significa que ha quitado la cuenta y cunado agrega una nueva suma cuantas cuentas estan agregadas
        sumaAsientosAgregados =  $('#txtContadorAsientosAgregadosFVC').val();
        sumaAsientosAgregados ++;
        $('#txtContadorAsientosAgregadosFVC').val(sumaAsientosAgregados);
    }

    $('#txtIdServicio'+cont).val(idServicio);
    $('#txtCodigoServicio'+cont).val(array[0]);
    $('#txtDescripcionS'+cont).val(array[1]);
    var valorUnitario = parseFloat(array[2]);
    $('#txtValorUnitarioS'+cont).val(valorUnitario.toFixed(4));
    $('#txtIdIvaS'+cont).val(array[3]);
	
    $('#txtIvaS'+cont).val(array[4]);
    //$('#txtIvaS'+cont).val(array[5]);
    
	$('#txtCalculoIvaS'+cont).val();
    $('#txtCantidadS'+cont).focus();
/* alert("aaa");
	alert($('#txtIvaS'+cont).val(array[4])); */
}

function listar_servicios(){
    //PAGINA: inventarios.php /servicios
    var str = $("#frmServicios").serialize();
    $.ajax({
            url: 'ajax/listarServicios.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#listar_servicios").html(data);

            }
    });

}

function listar_servicios2(){
    //PAGINA: inventarios.php /servicios
    var str = $("#frmServicios").serialize();
    $.ajax({
            url: 'ajax/listarServicios2.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#listar_servicios2").html(data);

            }
    });

}



//************************************************** PLAN CUENTAS ****************************
function mostrarPlanCuentas(aux){
    
    ajax6=objetoAjax();
    ajax6.open("POST", "sql/plan_cuentas.php",true);
    ajax6.onreadystatechange=function(){
        if (ajax6.readyState==4){
            var respuesta6=ajax6.responseText;
            
            asignaPlanCuentas(respuesta6);
        }
    }
    ajax6.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax6.send("accion="+aux)
}

function asignaPlanCuentas(cadena){
    
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;    
    cont2=1;
    //limpiaCmbPlanCuentas();
        
    //  pagina inventarios-servicios
    document.getElementById("cmbCuentaContable").options[0] = new Option("Seleccione Cuenta Contable", "0");
    for(i=1;i<limite;i=i+2){
        document.getElementById("cmbCuentaContable").options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }


    //  pagina impuestos
    document.getElementById("cmbCuentaContableI").options[0] = new Option("Seleccione Cuenta Contable", "0");
    for(i2=1;i2<limite;i2=i2+2){
        document.getElementById("cmbCuentaContableI").options[cont2] = new Option(array[i2+1], array[i2]);
        cont2++;
    }
       
}


function limpiaCmbPlanCuentas() // no usado
{      
    // pagina inventarios-servicios
    for (m=document.getElementById("cmbCuentaContable").options.length-1;m>=0;m--){        
        document.getElementById("cmbCuentaContable").options[m]=null;
    }
   
    // pagina impuestos
    for (m2=document.getElementById("cmbCuentaContableI").options.length-1;m2>=0;m2--){
        //alert();
        document.getElementById("cmbCuentaContableI").options[m2]=null;
    }
     
}


function tipoPago(valor){
    
    // obtenemos el nombre del combobox
    var combo = document.getElementById(valor);
    var comboNombre = combo.options[combo.selectedIndex].text;
    

    /* Para obtener el idFormaPago */
    var idFormaPago = document.getElementById(valor).value;
    array = idFormaPago.split( "*" );
    
    
    var f = new Date();
    var fechaAcual = (f.getFullYear() + "-" + (f.getMonth() +1) + "-" + f.getDate());


   
    if(array[1] == "Ctas. por Cobrar"){
        cadena = "";
        cadena = cadena + "<tr>";
        cadena = cadena + "<td></td>";
        cadena = cadena + "<td></td>";
        cadena = cadena + "</tr>";
        cadena = cadena + "<tr style='font-size: 30px'>";
        cadena = cadena + "<td><label><strong>Cuotas:</strong></label></td>";
        cadena = cadena + "<td><input type='text' id='txtCuotasTP' name='txtCuotasTP' title='Ingrese un numero' style='font-size: 30px;  width: 100%' onKeyPress=\"return soloNumeros(event)\" onkeyup=\"listar_cuotasFVC()\" onClick=\"listar_cuotasFVC()\" class='text_input' maxlength='100' placeholder='Numero de cuotas' autocomplete='off'/></td>";
        cadena = cadena + "</tr>";
        cadena = cadena + "<tr style='font-size: 30px'>";
        cadena = cadena + "<td><label><strong>Dias Plazo:</strong></label></td>";
        cadena = cadena + "<td><input type='text' id='txtDiasPlazoTP' name='txtDiasPlazoTP' title='Ingrese un numero' style='font-size: 30px;  width: 100%' onKeyPress=\"return soloNumeros(event)\" class='text_input' maxlength='100' placeholder='Numero de dias plazo' autocomplete='off'/></td>";
        cadena = cadena + "</tr>";
        cadena = cadena + "<tr style='font-size: 30px'>";
        cadena = cadena + "<td><label><strong>Fecha Inicio:</strong></label></td>";
        cadena = cadena + "<td><input type='text' id='txtFechaTP' value="+fechaAcual+" name='txtFechaTP' style='font-size: 30px;  width: 100%' onClick=\"displayCalendar(txtFechaTP,'yyyy-mm-dd',this)\" onKeyPress=\"listar_cuotasFVC()\" onchange=\"listar_cuotasFVC()\" title='Ingrese un numero' class='text_input' maxlength='100' placeholder='Numero de dias plazo' autocomplete='off'/></td>";
        cadena = cadena + "</tr>";
        cadena = cadena + "<tr>";
        cadena = cadena + "<td></td>";
        //cadena = cadena + "<td><input name='cancelar' type='button' id='submit' value='Ver Cuotas' onclick=\"listar_cuotasFVC();\" class='button'/></td>";
        cadena = cadena + "<td></td>";
        cadena = cadena + "</tr>";
            
        $("#tipoPagoCredito").append(cadena);
       
    }else{
        /* limpia las filas */
        for(r=1; r<=4; r++){
            document.getElementById("tblTipoPago").deleteRow(4);
        }
        document.getElementById("tblListadoCuotas").remove();
        
        
    }
    
}

 function listar_cuotasFVC(){
     
    cadena = "<table id='tblListadoCuotas' class='lista' width='100%'>";
    cadena = cadena+"<thead>";
    cadena = cadena+"<tr>";
    cadena = cadena+"<th><strong>Ide</strong></th>";
    cadena = cadena+"<th><strong>Nombre</strong></th>";
    cadena = cadena+"<th><strong>Nro. Fac</strong></th>";
    cadena = cadena+"<th><strong>Cuota</strong></th>";
    cadena = cadena+"<th><strong>Fecha Pago</strong></th>";    
    cadena = cadena+"</tr>";
    cadena = cadena+"</thead>";
    cadena = cadena+"<tbody>";
    
    plazo = document.getElementById("txtCuotasTP").value;    
    fecha_inicio = document.getElementById("txtFechaTP").value;    
    empleado = document.getElementById("txtNombreFVC").value;    
    total_credito = document.getElementById("txtDebeFP").value;
    numero_factura = document.getElementById("txtNumeroFacturaFVC").value;
    valor_cuotas = parseFloat(total_credito / plazo);
    //imp_final = parseFloat(document.formnp.txtImFinal.value);
    //valor_cuotas = parseFloat(document.formnp.txtCuotas.value).toFixed(2);
    contador = 0;
    //valor_restante = imp_final;
    for(i=0; i<plazo; i++){
        
        contador++;
        
        //valor_restante = (valor_restante - valor_cuotas).toFixed(2);
        fecha = sumaFechaFVC(i-1,fecha_inicio);
        
        if(contador%2==0){            
            cadena = cadena+"<tr class='odd' id='tr1'>";
            cadena = cadena+"<td>"+contador+"</td>";
            cadena = cadena+"<td>"+empleado+"</td>";
            cadena = cadena+"<td>"+numero_factura+"</td>";
            cadena = cadena+"<td>"+valor_cuotas.toFixed(4)+"</td>";
            cadena = cadena+"<td>"+fecha+"</td>";
            cadena = cadena+"</tr>";
        }
        if(contador%2==1){            
            cadena = cadena+"<tr class='odd' id='tr2'>";
            cadena = cadena+"<td>"+contador+"</td>";
            cadena = cadena+"<td>"+empleado+"</td>";
            cadena = cadena+"<td>"+numero_factura+"</td>";
            cadena = cadena+"<td>"+valor_cuotas.toFixed(4)+"</td>";
            cadena = cadena+"<td>"+fecha+"</td>";
            cadena = cadena+"</tr>";
        }
    }    

    cadena = cadena+"</tbody>";
    cadena = cadena+"</table>";
    
    $("#div_listar_cuotasFVC").html(cadena);
}

 sumaFechaFVC = function(m, fecha)
 {
    var Fecha = new Date();
    var sFecha = fecha || (Fecha.getDate() + "/" + (Fecha.getMonth() +1) + "/" + Fecha.getFullYear());    
    var sep = sFecha.indexOf('/') != -1 ? '/' : '-'; 
    var aFecha = sFecha.split(sep);
    var meses = m || 0;
    var mes = parseInt(aFecha[1]); 
    aFecha[1] = mes + meses;

    var fFecha = Date.UTC(aFecha[0],aFecha[1],aFecha[2])+(86400000); // 86400000 son los milisegundos que tiene un dÃ­a
    var fechaFinal = new Date(fFecha);

    var anno = fechaFinal.getFullYear();
    var mes = fechaFinal.getMonth();
    mes = parseInt(mes)+1;
    var dia = fechaFinal.getDate();
    var mes = (mes < 10) ? ("0" + mes) : mes;
    var dia = (dia < 10) ? ("0" + dia) : dia;
    var fechaFinal = anno+sep+mes+sep+dia;

    return (fechaFinal);

 }


function guardarFacVentaCondominios(accion){
    
  //  alert('guaradar');
    /* Para obtener el idFormaPago */
    var idFormaPago = document.getElementById("cmbFormaPagoFP").value;
    arrayFormaPago = idFormaPago.split( "*" );
    
    var txtDebeFP = document.getElementById("txtDebeFP").value;
    var txtPagoFP = document.getElementById("txtPagoFP").value;
    var txtCambioFP = document.getElementById("txtCambioFP").value;
    
    
    if(arrayFormaPago[1] == "Ctas. por Cobrar"){
        
        var txtCuotasTP = document.getElementById("txtCuotasTP").value;
        var txtDiasPlazoTP = document.getElementById("txtDiasPlazoTP").value;
        var txtFechaTP = document.getElementById("txtFechaTP").value;
        
    }
    
    if(idFormaPago != 0){
        var str = $("#frmFacturaVentaCondominios,#frmFormaPago").serialize();
        //var str2 = $("#frmFormaPago").serialize();
//alert(str);
        $.ajax
		({
            url: 'sql/facturaVenta.php',
            type: 'post',
            data: str+"&txtAccion="+accion+"&idFormaPago="+arrayFormaPago[0]+"&txtCuotasTP="+txtCuotasTP+"&txtDiasPlazoTP="+txtDiasPlazoTP+"&txtFechaTP="+txtFechaTP+"&tipoMovimiento="+arrayFormaPago[1],
            // para mostrar el loadian antes de cargar los datos
            beforeSend: function(){
                //imagen de carga
                $("#mensajeFacturaVentaCondominios2").html("<p align='center'><img src='images/loading.gif' /></p>");
            },
            success: function(data){
                //alert(data.length);
                document.getElementById("mensajeFacturaVentaCondominios2").innerHTML=data;
                if(data.length == 87){
                    //document.getElementById("frmServicios").reset();
                }
                //listar_servicios();
            }
        });
    }else{
        alert ('Seleccione el Tipo de Pago.');
        document.getElementById("cmbFormaPagoFP").focus();
        //dml.elements['cmbFormaPagoFP'].focus();
    }
    
}

function guardarFacVentaAautoCondominios(accion){
    
    var str = $("#frmFacturaAutomatica").serialize();
    //var str2 = $("#frmFormaPago").serialize();
    

    $.ajax({
        url: 'sql/facturaVenta.php',
        type: 'post',
        data: str+"&txtAccion="+accion,
        // para mostrar el loadian antes de cargar los datos
        beforeSend: function(){
            //imagen de carga
            $("#mensajeFacVentaAutoCondominios").html("<p align='center'><img src='images/loading.gif' /></p>");
        },
        success: function(data){
            
            //alert(data);
            document.getElementById("mensajeFacVentaAutoCondominios").innerHTML=data;
            if(data.length === 87){
                //document.getElementById("frmServicios").reset();
            }
            //listar_servicios();
        }
    });
    
}



function pagoMinimo(valorPagado, pagoMin, txtSaldo){
    
    // prestamos pagina: ajax/pagarCuota.php    
    var pago = parseFloat(valorPagado.value);
    var min = parseFloat(pagoMin);
    var saldo = parseFloat(txtSaldo.value);  
    var auxiliar = 0;
    //auxiliar = 0;
   /*  if(pago < min){
        document.getElementById("validaPagoMin").innerHTML="<label style='color: #FF0000'>El valor ingresado es incorrecto</label>";
        auxiliar = 1;
        return auxiliar;
    } */
    if(pago > saldo){
        document.getElementById("validaPagoMin").innerHTML="<label style='color: #FF0000'>El valor ingresado sobrepasa su saldo</label>";
        auxiliar = 1;
        return auxiliar;
    }
    if(pago >= min && pago <=saldo){
        document.getElementById("validaPagoMin").innerHTML="";
        auxiliar = 0;
        return auxiliar;
    }
    
    return auxiliar;
}

function calculaVuelto(txtValor, txtEfectivo){
    vuelto = txtEfectivo.value - txtValor.value;
    document.getElementById("txtSaldo").value = vuelto.toFixed(4);    
    
}

function calculaVueltoTP(txtValor, txtEfectivo){
    vuelto = txtEfectivo.value - txtValor.value;
    document.getElementById("txtCambioFP").value = vuelto.toFixed(4);    
    
}




// ********************************  CLIENTES **************************

/* function guardar_clientes(accion){

    var str = $("#frmClientes").serialize();
    
    
    noRptCedula = no_repetir_cedula_clientes(txtCedula, 3);
    //verifica_cedula = cedula_ruc(txtCedula);
    
    //alert("noRptCedula: "+noRptCedula+" verifica_cedula: "+verifica_cedula);
    
    noRptEmail = no_repetir_email_cliente(txtEmail,4);
    val_email = isEmailAddress(txtEmail);
    //alert("noRptEmail: "+noRptEmail+" val_email: "+val_email);
    if(verifica_cedula == true)
	{        
        if(noRptCedula == 0 ){
            if(val_email == true){
                if(noRptEmail == 0){
                    
                    $.ajax({
                        url: 'sql/clientes.php',
                        type: 'post',
                        data: str+"&accion="+accion,
                        // para mostrar el loadian antes de cargar los datos
                        beforeSend: function(){
                            //imagen de carga
                            $("#mensaje1").html("<p align='center'><img src='images/loading.gif' /></p>");
                        },
                        success: function(data){
                            document.getElementById("mensaje1").innerHTML=data;
                            listar_clientes();

                        }
                    });
                    
                }else{
                    $("#txtEmail").focus();
                    $("#txtEmail").val("");
                    //dml.elements['ruc'].focus();
                    alert("No se puede guardar. El Email ingresado ya se encuentra registrado.");
               }
           }else{
                $("#txtEmail").focus();                
                alert("No se puede guardar. El Email ingresado esta incorrecto.");
           }
        }else{
            $("#txtCedula").focus();
            $("#txtCedula").val("");
            //dml.elements['ruc'].focus();
            alert("No se puede guardar. La Cedula/Ruc que ingreso ya se encuentra registrada.");
        }
    }else{
        $("#txtCedula").focus();
        alert("No se puede guardar. La Cedula/Ruc que ingreso esta incorrecta.");
    }
     

}
 */
 
 
function guardar_clientes_ant(accion){

    var str = $("#frmClientes").serialize();
    
    noRptCedula = no_repetir_cedula_clientes(txtCedula, 3);
      //verifica_cedula = cedula_ruc(txtCedula);
    
        //alert("noRptCedula: "+noRptCedula+" verifica_cedula: "+verifica_cedula);
    
    noRptEmail = no_repetir_email_cliente(txtEmail,4);
    val_email = isEmailAddress(txtEmail);
    //alert("noRptEmail: "+noRptEmail+" val_email: "+val_email);
    //if(verifica_cedula == true)
	//{        
        if(noRptCedula == 0 ){
            if(val_email == true){
                if(noRptEmail == 0){
                    
                    $.ajax({
                        url: 'sql/clientes.php',
                        type: 'post',
                        data: str+"&accion="+accion,
                        // para mostrar el loadian antes de cargar los datos
                        beforeSend: function(){
                            //imagen de carga
                            $("#mensaje3").html("<p align='center'><img src='images/loading.gif' /></p>");
                        },
                        success: function(data){
                            if(data==2){
                                 alertify.error("Faltan datos de cliente");
                            }else{
                                console.log(data)
                                alertify.success("Cliente Agregado con exito!");
                                    listar_clientes();
                                fn_cerrar()
                            }
                            
                            
                        }
                    });
                    
                }else{
                    $("#txtEmail").focus();
                    $("#txtEmail").val("");
                    //dml.elements['ruc'].focus();
                    alert("No se puede guardar. El Email ingresado ya se encuentra registrado.");
               }
           }else{
                $("#txtEmail").focus();                
                alert("No se puede guardar. El Email ingresado esta incorrecto.");
           }
        }else{
            $("#txtCedula").focus();
            $("#txtCedula").val("");
            //dml.elements['ruc'].focus();
            alert("No se puede guardar. La Cedula/Ruc que ingreso ya se encuentra registrada.");
        }
    //}
	
	//else{
      //  $("#txtCedula").focus();
       // alert("No se puede guardar. La Cedula/Ruc que ingreso esta incorrecta.");
   // }
     

}



//******************************HOTELES************************************
function cargosHabitacion(){

        $("#div_oculto").load("ajax/cargosHabitacion.php", function(){
		$.blockUI({
			message: $('#div_oculto'),

		overlayCSS: {backgroundColor: '#111'},
			css:{

				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',

				background: '', /* #f9f9f9*/
				top: '5%',

				position: 'absolute',
				width: '400px',
                                left: ($(window).width() - $('.caja').outerWidth())/2


			}
		});
                //listar_formas_pago();
	});

}
