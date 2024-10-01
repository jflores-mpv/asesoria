/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// LIMPIA LAs CAJAs DE TEXTO, DE LOS IDES / FUNCIONA EN VARIAS PAGINAS: comprobantes.php
function limpiar_id(a,b,c,d){
    document.getElementById(a).value = "";
    document.getElementById(b).value = "";
    document.getElementById(c).value = "";
    document.getElementById(d).value = "";
    
}
function habilitar(acc){
    //funcion para habilitar los txt Debe y Haber pagina: libroDiario.php
    document.getElementById("txtDebe"+acc).disabled = false;
    document.getElementById("txtHaber"+acc).disabled = false;
    document.getElementById("txtIdCuenta"+acc).value = "";
    document.getElementById("txtCodigo"+acc).value = "";
}

// BUSCA LOS COMPROBANTES POR NUMERO - FUNCIONA EN LA PAGINA comprobantes.php
function lookup1(txtBuscarComprobante) {
    if(txtBuscarComprobante.length == 0) {
            // Hide the suggestion box.
            $('#suggestions').hide();
    } else {
        $.post("sql/buscarComprobantes.php", {queryString: ""+txtBuscarComprobante+""}, function(data){
            if(data.length >0) {
                $('#suggestions').show();
                $('#autoSuggestionsList').html(data);
            }
        });
    }
} // lookup

function fill1(thisValue, id) {
    $('#txtBuscarComprobante').val(thisValue);
    $('#txtIdComprobante').val(id);
    setTimeout("$('#suggestions').hide();", 200);
}



// funcion para buscar la cuenta en la pagina libroMayor.php
function lookup3(txtPlanCuenta, accion) {
  
    if(txtPlanCuenta.length == 0) {
            // Hide the suggestion box.
            $('#suggestions').hide();
    } else {
            $.post("sql/libroMayor.php", {queryString: ""+txtPlanCuenta+"", accion: accion}, function(data){
                    if(data.length >0) {
                            $('#suggestions').show();
                            $('#autoSuggestionsList').html(data);
                    }
            });
    }
} // lookup

function fill3(thisValue, id) {

    setTimeout("$('#suggestions').hide();", 200);
    
    //libro mayor
    $('#txtPlanCuenta').val(thisValue);
    $('#txtIdPlanCuenta').val(id);


    //nuevo asiento contable
    thisValue.replace(" ","");
    array = thisValue.split("-");
    $('#txtCodigo').val(array[0].replace(" ", ""));

    
}

//SE UTILIZA EN LA PAGINA: registroDiario.php, asignacionEmpleados.php, comisiones.php //
function lookup4(txtCliente, formulario='') {
    if(txtCliente.length == 0) {
            // Hide the suggestion box.
            $('#suggestions').hide();
            ('#suggestions1').hide();
    } else {
            $.post("sql/buscarUsuario.php", {queryString: ""+txtCliente+"",formulario: ""+formulario+""}, function(data){
                    if(data.length >0) {
                            $('#suggestions').show();
                            $('#autoSuggestionsList').html(data);
                            $('#suggestions1').show();
                            $('#autoSuggestionsList1').html(data);
                    }
            });
    }
} // lookup
function fill4(thisValue, id) {    
    $('#txtCliente').val(thisValue);
    $('#txtIdCliente').val(id);
    $('#txtCliente1').val(thisValue);
    $('#txtIdCliente1').val(id);
    setTimeout("$('#suggestions').hide();", 200);
    setTimeout("$('#suggestions1').hide();", 200);
}

// funciones para desplegar la lista de la busqueda de empleados retorna en la pagina empresa.php
function lookup5(txtNombreResponsable, accion) {
        if(txtNombreResponsable.length == 0) {
                // Hide the suggestion box.
                $('#suggestions').hide();
        } else {
                $.post("sql/empresa.php", {queryString: ""+txtNombreResponsable+"", accion: accion}, function(data){
                        if(data.length >0) {
                                $('#suggestions').show();
                                $('#autoSuggestionsList').html(data);
                        }
                });
        }
} // lookup

function fill5(thisValue, id) {
        $('#txtNombreResponsable').val(thisValue);
        $('#txtIdEmpleado').val(id);
        setTimeout("$('#suggestions').hide();", 200);
}

//******** BUSCAR PROVEEDOR  *******//
function lookup6(valor) {
    console.log(valor);
    //pagina: nuevaFacturaCompra.php
    if(valor.length == 0) {
            // Hide the suggestion box.
            $('#suggestions6').hide();
    } else {
            $.post("sql/buscarProveedorRucNombre.php", {queryString: ""+valor+""}, function(data){
                // console.log("data",data);
                    if(data.length >0) {
                            $('#suggestions6').show();
                            $('#autoSuggestionsList6').html(data);
                    }
            });
    }
} // lookup

function fill6(nombre, ruc, telefono, direccion, id) {
                    console.log("nombre",nombre);
                    console.log("ruc",ruc);
                    console.log("telefono",telefono);
                    console.log("direccion",direccion);
                    console.log("id",id);
    $('#textIdProveedor').val(id);     
    $('#txtTelefono').val(telefono);
    $('#txtRuc').val(ruc);
    $('#txtDireccion').val(direccion);
    $('#txtNombreRuc').val(nombre);
    setTimeout("$('#suggestions6').hide();", 200);
}

//********** FACTURACION COMPRA ********//
function lookup7(txtNombre, id) {
//para agregar producto pagina: nuevaFacturaCompra.php
    if(txtNombre.length == 0) {
        // Hide the suggestion box.
        $('#suggestions').hide();
    } else {
        $.post("sql/BuscarProductoNombre.php", {queryString: ""+txtNombre+"",idProveedor: id}, function(data){
            
            if(data.length >0) {
                $('#suggestions').show();
                $('#autoSuggestionsList').html(data);
            }
        });
    }
} // lookup

function fill7(thisValue) {
    $('#txtNombre').val(thisValue);
    setTimeout("$('#suggestions').hide();", 200);
}

function lookup8(txtProveedor) {
    if(txtProveedor.length == 0) {
        // Hide the suggestion box.
        $('#suggestions').hide();
    } else {
        $.post("sql/BuscarProductoProveedor.php", {queryString: ""+txtProveedor+""}, function(data){
            if(data.length >0) {
                $('#suggestions').show();
                $('#autoSuggestionsList').html(data);
            }
        });
    }
} // lookup

function fill8(thisValue) {
    $('#txtProveedor').val(thisValue);
    setTimeout("$('#suggestions').hide();", 200);
}




function lookup101(txtProveedor) {
//para agregar producto pagina: nuevaFacturaVenta.php
    if(txtProveedor.length == 0) {
        // Hide the suggestion box.
        $('#suggestions10').hide();
    } else {
        $.post("sql/BuscarProductoProveedorV.php", {queryString: ""+txtProveedor+""}, function(data){
            if(data.length >0) {
                $('#suggestions10').show();
                $('#autoSuggestionsList10').html(data);
            }
        });
    }
} // lookup

function fill101(thisValue) {
    $('#txtNombre').val(thisValue);
    setTimeout("$('#suggestions10').hide();", 200);
}
//******** BUSCAR FACTURA para las retenciones  *******//
function lookup11(valor) {
    //pagina: retenciones compra
    if(valor.length == 0) {
            // Hide the suggestion box.
            $('#suggestions11').hide();
    } else {
            $.post("sql/buscarFacturaRetencionesC.php", {queryString: ""+valor+""}, function(data){
                    if(data.length >0) {
                            $('#suggestions11').show();
                            $('#autoSuggestionsList11').html(data);
                    }
            });
    }
} // lookup

function fill11(numero, id, sub, total) {
    $('#txtNumFactura').val(numero);
    $('#txtBase').val(sub);
    $('#txtIdFactura').val(id);
    
    setTimeout("$('#suggestions11').hide();", 200);
}

//********************* Prestamos ************************ //
function lookup12(txtCliente) {
    // busca solo empleados con prestamos activos
    if(txtCliente.length == 0) {
            // Hide the suggestion box.
            $('#suggestions12').hide();

    } else {
        $.post("sql/prestamos.php", {queryString: ""+txtCliente+""}, function(data){
                if(data.length >0) {
                    $('#suggestions12').show();
                    $('#autoSuggestionsList12').html(data);
                }
        });
    }
} // lookup

function fill12(thisValue, id) {
    $('#txtCliente').val(thisValue);
    $('#txtIdCliente').val(id);
    $('#txtCliente1').val(thisValue);
    $('#txtIdCliente1').val(id);
    setTimeout("$('#suggestions12').hide();", 200);

}

//********** BUSCAR PRODUCTO KARDEX ********//
function lookup13(txtNombre) {
    console.log("txtNombre",txtNombre);
//para agregar producto pagina: kardex.php

    if(txtNombre.length == 0) {
        // Hide the suggestion box.
        $('#suggestions13').hide();
    } else {
        
        $.post("sql/buscarProductoKardex.php", {queryString: ""+txtNombre+""}, function(data){
            //console.log(data);
            if(data.length >0) {
                $('#suggestions13').show();
                $('#autoSuggestionsList13').html(data);
            }
        });
    }
} // lookup

function fill13(id,thisValue) {
    $('#txtIdProducto').val(id);
    $('#txtNombre').val(thisValue);
    setTimeout("$('#suggestions13').hide();", 200);
}


    // FUNCION PARA BUSCAR LAS CUENTAS CON SUS DEPRECIACIONES PAGINAS: ajax/nuevoActivo.php
function lookup14(txtCuenta, accion) {
// alert("contador "+cont);
    if(txtCuenta.length == 0) {
            // Hide the suggestion box.
            $('#suggestions1').hide();
    } else {
            $.post("sql/activos.php", {queryString: ""+txtCuenta+"",txtAccion: accion}, function(data){
                    if(data.length >0) {
                            $('#suggestions1').show();
                            $('#autoSuggestionsList1').html(data);
                    }
            });
    }

} // lookup

function fill14(thisValue, id, vida, porcentaje) {
//retorna en la pagina: ajax/nuevoActivo.php
    $('#txtIdCuenta1').val(id);
    $('#txtCuenta1').val(thisValue);
    $('#txtVidaUtil').val(vida);
    $('#txtPorcentajeDepre').val(porcentaje);
    setTimeout("$('#suggestions1').hide();", 200);
}


//********************** ASIENTOS CONTABLES *****************************

function lookup15(txtCuenta, cont, accion) {
    if(txtCuenta.length == 0) 
	{
            // Hide the suggestion box.
            $('#suggestions1').hide();
    } 
	else 
	{    
		$.post("sql/libroDiario_buscar.php", {queryString: ""+txtCuenta+"",aux: cont, txtAccion: accion}, function(data)
		{
			if(data.length > 5)
			{	//le puse 5 xq aunq no haya datos me retorna 3 en data.length                 
				arrayPrincipal = data.split("î");//  dividivos el vector principal con el caracter: î
				//alert(arrayPrincipal[1]);
				array = arrayPrincipal[1].split("*");// * dividivos el vector por el numero de filas que arroga la consulta
				//array = data.split("*");// * dividivos el vector por el numero de filas que arroga la consulta
				limite = array.length;
				contFilas = $('#txtContadorFilas').val();
				// ELIMINA FILAS DEPENDIENDO DEL CONTADOR DE LA CONSULTA
				for(c=1;c<=contFilas;c++){
					eliminaFilas();
				}
				contador = 1;
				document.getElementById('txtContadorFilas').value = contador;
				// AGREGA FILAS DEPENDIENDO DEL CONTADOR DE LA CONSULTA
				for(c=1;c<=limite-1;c++){
					fn_agregar(0); // envia valor cero para resetear la posicion
				}
				// AGREGA LOS DATOS A LOS TXT
				verificar_cuenta=0;
				tdebe=0;
				for(i=1; i<=limite-1; i++){
					datos = array[i].split("?");
					$('#txtIdDetalleLibroDiario'+i).val(datos[1]);
					$('#txtIdLibroDiario').val(datos[10]);
					$('#txtIdCuenta'+i).val(datos[11]);
					if(datos[12] == "Diario"){
						document.frmAsientosContables.cmbTipoComprobante.selectedIndex = '0';
					}
					if(datos[12] == "Ingreso"){
						document.frmAsientosContables.cmbTipoComprobante.selectedIndex = '1';
					}
					if(datos[12] == "Egreso"){
						document.frmAsientosContables.cmbTipoComprobante.selectedIndex = '2';
					}

					fecha = datos[8].split(" ");//solo cojemos la fecha, no la hora
					$('#txtFecha').val(fecha[0]);
					$('#txtNumeroComprobante').val(datos[9]);
					$('#txtDescripcion').val(datos[7]);
					$('#txtCodigo'+i).val(datos[2]);
					$('#txtCuenta2'+i).val(datos[3]+" "+datos[4]);
					$('#txtDebe'+i).val(datos[5]);
					$('#txtHaber'+i).val(datos[6]);
					$('#txtCuentaBanco'+i).val(datos[4]);
					$('#txtContadorAsientosAgregados').val(limite-1);// para saber cuantas cuentas estan agregadas
				}

				if(arrayPrincipal[0].length > 5)

				{
                // verificamos si hay cuentas de bancos
				//document.getElementById('oculto').style.display = 'block'; // google crom
					$('#Bancos').show();
					datosBancos = arrayPrincipal[0].split("?");// * dividivos el vector por el numero de campos

				$('#txtIdBancos').val(datosBancos[0]);
                $('#txtIdDetalleBancos').val(datosBancos[1]);
                if(datosBancos[2] == "Cheque"){
                   document.frmAsientosContables.cmbTipoDocumento.selectedIndex = '0';
                }
                if(datosBancos[2] == "Deposito"){
                    document.frmAsientosContables.cmbTipoDocumento.selectedIndex = '1';
                }
                if(datosBancos[2] == "Nota de Credito"){
                    document.frmAsientosContables.cmbTipoDocumento.selectedIndex = '2';
                }
                if(datosBancos[2] == "Nota de Debito"){
                    document.frmAsientosContables.cmbTipoDocumento.selectedIndex = '3';
                }
                $('#txtNumeroDocumento').val(datosBancos[3]);
                $('#txtFechaEmision').val(datosBancos[5]);
                $('#txtFechaVencimiento').val(datosBancos[6]);
                $('#txtDetalleDocumento').val(datosBancos[4]);
                cambioDetalle();
				}
				else
				{
                $('#Bancos').hide();
                $('#txtIdBancos').val("");
				$('#txtIdDetalleBancos').val("");
                document.frmAsientosContables.cmbTipoDocumento.selectedIndex = '0';
                $('#txtNumeroDocumento').val("");
                $('#txtFechaEmision').val("");
                $('#txtFechaVencimiento').val("");
                $('#txtDetalleDocumento').val("");
				}
			    calcular_debe();
				calcular_haber();
			
            //if (verificar_cuenta == 1)  
				//alert(document.frmAsientosContables.txtDebeTotal.value);
			if (document.frmAsientosContables.txtDebeTotal.value == 0.00)		
            {
				//alert("entro1dddd");
				document.getElementById("btnEliminar").style.visibility = "hidden";
                       document.getElementById("btnGuardar").disabled = false;
				document.getElementById("btnGuardar").style.visibility="visible";
				document.getElementById("btnEditar").style.visibility = "hidden";

			}
			else
			{
				//alert("entro2");
				document.getElementById("btnEliminar").style.visibility = "visible";
                        //document.getElementById("btnGuardar").disabled = true;
				document.getElementById("btnGuardar").style.visibility="hidden";
				document.getElementById("btnEditar").style.visibility = "visible";

				    
			}
		}
			else
			{
		// alert("No existe esta cuenta.");
			}
		});
    }

} // lookup



function lookup16(accion, boton) {
// carga los usuarios de la empresa retorna en administrarEmpresa.php;

            $.post("sql/usuarios.php", {txtAccion: accion}, function(data){

                    if(data.length >0) {
                        alert(data);
                            array = data.split("*");// * dividivos el vector por el numero de filas que arroga la consulta
                            limite = array.length;

                            



                            contFilas = $('#txtContadorFilas').val();
                            // ELIMINA FILAS DEPENDIENDO DEL CONTADOR DE LA CONSULTA
                            for(c=1;c<=contFilas;c++){
                                //eliminaFilas();
                            }
                            contador = 1;
                            document.getElementById('txtContadorFilas').value = contador;
                            // AGREGA FILAS DEPENDIENDO DEL CONTADOR DE LA CONSULTA
                            for(c=1;c<=limite-1;c++){
                                //fn_agregar(0); // envia valor cero para resetear la posicion
                            }
                            // AGREGA LOS DATOS A LOS TXT
                            for(i=1; i<=limite-1; i++){
                                datos = array[i].split("?");
                                //cuenta desde 0
                                $('#txtIdDetalleLibroDiario'+i).val(datos[1]);
                                $('#txtIdLibroDiario').val(datos[9]);
                                $('#txtIdCuenta'+i).val(datos[10]);
                                fecha = datos[7].split(" ");//solo cojemos la fecha, no la hora
                                $('#txtFecha').val(fecha[0]);
                                $('#txtNumeroComprobante').val(datos[8]);
                                $('#txtDescripcion').val(datos[6]);
                                $('#txtCodigo'+i).val(datos[2]);
                                $('#txtCuenta2'+i).val(datos[3]);
                                $('#txtDebe'+i).val(datos[4]);
                                $('#txtHaber'+i).val(datos[5]);

                                $('#txtContadorAsientosAgregados').val(limite-1);// para saber cuantas cuentas estan agregadas
                            }
                            document.getElementById("btnEliminar").style.visibility = "visible";
                            //document.getElementById("btnGuardar").disabled = true;
                            document.getElementById("btnGuardar").style.visibility="hidden";
                            document.getElementById("btnEditar").style.visibility = "visible";

                            //calcular_debe();
                            //calcular_haber();
                            //$('#suggestions1').show();
                            //$('#autoSuggestionsList1').html(data);
                    }else{
                        for(c=1;c<=contFilas;c++){
                                //eliminaFilas();
                            }
                    }
            });   

} // lookup