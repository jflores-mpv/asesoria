function forma_cobro(){
//alert("aaaa");
        $("#div_oculto").load("ajax/formaCobro.php", function(){
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
       listar_formas_cobro();
       listar_formas_cobro2();
	});
}


//listar_formas_cobro
function listar_formas_cobro(){
 //PAGINA: formas de pago.php
   // alert("entro11133331111111111111");
    var str = $("#form").serialize();
    $.ajax
	({
        url: 'ajax/listarFormasCobro.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#div_listar_forma_pagos").html(data);
                 cantidad_formas_cobro();
            }
    });
}




function agregarFormaCobros()
{
	nFilasFormaPago = 0;
    numFilas1 = document.getElementById('txtNumeroFila').value;
	//alert("nfila");
	//alert(numFilas1);
    nFilasFormaPago = numFilas1;
    nFilasFormaPago++;
    document.getElementById("mensaje2").innerHTML="";
    cadena = "<tr>";
    cadena = cadena + "<input style='width: 100%' type='hidden' id='txtIdFormaPago"+nFilasFormaPago+"' name='txtIdFormaPago"+nFilasFormaPago+"' class='form-control' disabled='true' />    <input type='hidden' readonly='readonly' id='txtIdCuenta"+nFilasFormaPago+"' name='txtIdCuenta"+nFilasFormaPago+"' value='0' class='text_input3'/>";
    cadena = cadena + "<td width='35%'><input style='width: 100%' type='text' id='txtNombre"+nFilasFormaPago+"' name='txtNombre"+nFilasFormaPago+"' title='Ingrese un nombre' style='text-transform: capitalize;' class='form-control' required='required' onKeyPress='' maxlength='200' placeholder='Nombre' autocomplete='off'/></td>";
    cadena = cadena + "<td width='15%'><input style='width: 100%;' type='search' id='txtCodigo"+nFilasFormaPago+"' name='txtCodigo"+nFilasFormaPago+"' class='form-control' value='' onclick='lookup2(this.value,"+nFilasFormaPago+", 5);' onKeyUp='lookup2(this.value,"+nFilasFormaPago+", 5);'  autocomplete='off'  placeholder='Buscar..'   />       <div class='suggestionsBox' id='suggestions"+nFilasFormaPago+"' style='display: none; margin-top: 0px; width: 350px  '> <div class='suggestionList' id='autoSuggestionsList"+nFilasFormaPago+"'> &nbsp; </div> </div>   </td>";
    cadena = cadena + "<td width='25%'><input type='search' id='txtCuenta2"+nFilasFormaPago+"' name='txtCuenta2"+nFilasFormaPago+"' value=''  style='margin: 0px; width: 100%;' class='form-control' autocomplete='off'   /></td> ";
    cadena = cadena + "<td width='25%'><select style='width: 100%' name='cmbTipoMovimientoFVC"+nFilasFormaPago+"' id='cmbTipoMovimientoFVC"+nFilasFormaPago+"' class='form-control' ></select></td> ";
    //cadena = cadena + "<td width='25%'><input type='search' id='txtCuenta2"+nFilasFormaPago+"' name='txtCuenta2"+nFilasFormaPago+"' value=''  style='margin: 0px; width: 100%;' class='form-control' autocomplete='off'   /></td> ";
    cadena = cadena + "<td width='25%'><input type='search' id='txtPorcentaje"+nFilasFormaPago+"' name='txtPorcentaje"+nFilasFormaPago+"' value=''  style='margin: 0px; width: 100%;' class='form-control' autocomplete='off'   /></td> ";
    cadena = cadena + "<td width='25%'><input type='search' id='txtCodigoSri"+nFilasFormaPago+"' name='txtCodigoSri"+nFilasFormaPago+"' value=''  style='margin: 0px; width: 100%;' class='form-control' autocomplete='off'   /></td> ";    
    cadena = cadena + "<td><a title='Guardar' href='javascript: guardarFormaCobro("+nFilasFormaPago+", 1);'><span class='fa fa-check' aria-hidden='true'></span></a></td>";
    cadena = cadena + "<td><a class='eliminaFormaPago' title='Eliminar'><span class='fa fa-delete' aria-hidden='true'></a></td>";
    cadena = cadena + "</tr>";
    $("#grillaFormaPago tbody").append(cadena);
    cantidad_formas_cobro();
    eliminaFormaPago();
    cargarTipoMovCompra(6,nFilasFormaPago);
}


function cargarTipoMovCompra(aux, contador){
console.log("aux",aux);
console.log("contador",contador);
    ajax2=objetoAjax();
    ajax2.open("POST", "sql/formasCobro.php",true);
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
	//alert(array);
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


function cantidad_formas_cobro(){
    cantidad = $("#grillaFormaPago tbody").find("tr").length;
	//alert("cantidad");
	//alert(cantidad);
    $("#span_cantidadFormaCobro").html(cantidad);
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

function guardarFormaCobro(nFila, accion){
//alert("fffgg");
//	alert(document.getElementById("txtPorcentaje"+nfila).value);

    //PAGINA: ajax/formaPago.php
    if(document.getElementById("txtNombre"+nFila).value != ""){
        if(document.getElementById("txtIdCuenta"+nFila).value != "" && document.getElementById("txtIdCuenta"+nFila).value >= 1){
            if(document.getElementById("cmbTipoMovimientoFVC"+nFila).value != "" && document.getElementById("cmbTipoMovimientoFVC"+nFila).value >= 1){
                var respuesta43 = confirm("Seguro desea guardar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
                if (respuesta43){
                var str = $("#frmFormasCobro").serialize();
	//			alert(str);
                    $.ajax({
                            url: 'sql/formasCobro.php',
                            type: 'post',
                            data: str+"&numeroFilaSelec="+nFila+"&txtAccion="+accion,
                            success: function(data){
                                			    console.log(data);
				if(data==1){
				alertify.success("Enlace guardado con exito :)");
				fn_cerrar()
			}else{
				alertify.error("Error al guardar, revise los datos");
			}
                                    

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

function modificar_forma_cobro(idFormaCobro, accion, fila){
    //PAGINA: ajax/formaPago.php
	//alert(document.getElementById("txtPorcentaje"+fila).value);
console.log("idFormaCobro",idFormaCobro);
console.log("accion",accion);
console.log("fila",fila);

    if(document.getElementById("txtNombre"+fila).value != ""){
        if(document.getElementById("txtIdCuenta"+fila).value != "" && document.getElementById("txtIdCuenta"+fila).value >= 1){
            if(document.getElementById("cmbTipoMovimientoFVC"+fila).value != "" && document.getElementById("cmbTipoMovimientoFVC"+fila).value >= 1){
                var respuesta44 = confirm("Seguro desea modificar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
                if (respuesta44){
                var str = $("#frmFormasCobro").serialize();
		//		alert(str)
                    $.ajax({
                            url: 'sql/formasCobro.php',
                            type: 'post',
                            data: str+"&idFormaCobro="+idFormaCobro+"&txtAccion="+accion+"&NumeroFilaSeleccionada="+fila,
                            success: function(data){
                                    //$("#div_listar_RegistroDiario").html(data);
                                    document.getElementById("mensaje2").innerHTML=""+data;
                                    listar_formas_cobro();
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


function listar_formas_cobro2(){
 //PAGINA: formas de pago.php
 //  alert("listar_formas_cobro2");
    var str = $("#form").serialize();
    $.ajax
    ({
        url: 'ajax/listarFormasCobro2.php',
            type: 'get',
            data: str,
            success: function(data){
                //console.log("data",data);
                $("#div_listar_forma_pagos2").html(data);
                 cantidad_formas_cobro2();
            }
    });
}

function cantidad_formas_cobro2(){
    cantidad = $("#grillaFormaPago2 tbody").find("tr").length;
    //alert("cantidad");
    //alert(cantidad);
    $("#span_cantidadFormaCobro2").html(cantidad);
    document.getElementById('txtNumeroFila2').value = cantidad;
}




function agregarFormaCobros2()
{
    nFilasFormaPago = 0;
    numFilas1 = document.getElementById('txtNumeroFila2').value;
    alert("nfila");
    alert(numFilas1);
    nFilasFormaPago = numFilas1;
    nFilasFormaPago++;
    document.getElementById("mensaje2").innerHTML="";
    cadena = "<tr>";
    cadena = cadena + "<td width='5%'><input style='width: 100%' type='text' id='txtIdFormaPago"+nFilasFormaPago+"' name='txtIdFormaPago"+nFilasFormaPago+"' class='form-control' disabled='true' />    <input type='hidden' readonly='readonly' id='txtIdCuenta"+nFilasFormaPago+"' name='txtIdCuenta"+nFilasFormaPago+"' value='0' class='text_input3'/>    </td>";
    cadena = cadena + "<td width='35%'><input style='width: 100%' type='text' id='txtNombre"+nFilasFormaPago+"' name='txtNombre"+nFilasFormaPago+"' title='Ingrese un nombre' style='text-transform: capitalize;' class='form-control' required='required' onKeyPress='' maxlength='200' placeholder='Nombre' autocomplete='off'/></td>";
   // cadena = cadena + "<td width='15%'><input style='width: 100%;' type='search' id='txtCodigo"+nFilasFormaPago+"' name='txtCodigo"+nFilasFormaPago+"' class='form-control' value='' onclick='lookup2(this.value,"+nFilasFormaPago+", 5);' onKeyUp='lookup2(this.value,"+nFilasFormaPago+", 5);'  autocomplete='off'  placeholder='Buscar..'   />       <div class='suggestionsBox' id='suggestions"+nFilasFormaPago+"' style='display: none; margin-top: 0px; width: 350px  '> <div class='suggestionList' id='autoSuggestionsList"+nFilasFormaPago+"'> &nbsp; </div> </div>   </td>";
   // cadena = cadena + "<td width='25%'><input type='search' id='txtCuenta2"+nFilasFormaPago+"' name='txtCuenta2"+nFilasFormaPago+"' value=''  style='margin: 0px; width: 100%;' class='form-control' autocomplete='off'   /></td> ";
    cadena = cadena + "<td width='25%'><select style='width: 100%' name='cmbTipoMovimientoFVC"+nFilasFormaPago+"' id='cmbTipoMovimientoFVC"+nFilasFormaPago+"' class='form-control' ></select></td> ";
    //cadena = cadena + "<td width='25%'><input type='search' id='txtCuenta2"+nFilasFormaPago+"' name='txtCuenta2"+nFilasFormaPago+"' value=''  style='margin: 0px; width: 100%;' class='form-control' autocomplete='off'   /></td> ";
    cadena = cadena + "<td width='25%'><input type='search' id='txtPorcentaje"+nFilasFormaPago+"' name='txtPorcentaje"+nFilasFormaPago+"' value=''  style='margin: 0px; width: 100%;' class='form-control' autocomplete='off'   /></td> ";
    cadena = cadena + "<td width='25%'><input type='search' id='txtCodigoSri"+nFilasFormaPago+"' name='txtCodigoSri"+nFilasFormaPago+"' value=''  style='margin: 0px; width: 100%;' class='form-control' autocomplete='off'   /></td> ";    
    cadena = cadena + "<td><a title='Guardar' href='javascript: guardarFormaCobro2("+nFilasFormaPago+", 1);'><span class='glyphicon glyphicon-ok' aria-hidden='true'></span></a></td>";
    cadena = cadena + "<td><a class='eliminaFormaPago' title='Eliminar'><span class='glyphicon glyphicon-delete' aria-hidden='true'></a></td>";
    cadena = cadena + "</tr>";
    $("#grillaFormaPago2 tbody").append(cadena);
    cantidad_formas_cobro2();
    eliminaFormaPago();
    cargarTipoMovCompra(6,nFilasFormaPago);
}

function guardarFormaCobro2(nFila, accion){

    if(document.getElementById("txtNombre"+nFila).value != ""){
       // if(document.getElementById("txtIdCuenta"+nFila).value != "" && document.getElementById("txtIdCuenta"+nFila).value >= 1){
            if(document.getElementById("cmbTipoMovimientoFVC"+nFila).value != "" && document.getElementById("cmbTipoMovimientoFVC"+nFila).value >= 1){
                var respuesta43 = confirm("Seguro desea guardar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
                if (respuesta43){
                var str = $("#frmFormasCobro2").serialize();
         // console.log(str);
                    $.ajax({
                            url: 'sql/formasCobro.php',
                            type: 'post',
                            data: str+"&numeroFilaSelec="+nFila+"&txtAccion="+accion,
                            success: function(data){
                                                console.log(data);
                if(data==1){
                alertify.success("Enlace guardado con exito :)");
                fn_cerrar()
            }else{
                alertify.error("Error al guardar, revise los datos");
            }
                                    

                            }
                    });
                }
            }else{
                alert("No se puede guardar. Seleccione el tipo de movimiento");
                document.getElementById("cmbTipoMovimientoFVC"+nFila).focus();
            }
            
    //    }else{
    //        alert("No se puede guardar. Seleccione una cuenta contable");
    //        document.getElementById("txtCodigo"+nFila).focus();
    //    }  

    }else{
        alert("No se puede guardar. El campo Nombre esta vacio.");
        document.getElementById("txtNombre"+nFila).focus();
    }
}


function modificar_forma_cobro2(idFormaCobro, accion, fila){
    //PAGINA: ajax/formaPago.php
    //alert(document.getElementById("txtPorcentaje"+fila).value);
console.log("idFormaCobro",idFormaCobro);
console.log("accion",accion);
console.log("fila",fila);

    if(document.getElementById("txtNombre"+fila).value != ""){
       // if(document.getElementById("txtIdCuenta"+fila).value != "" && document.getElementById("txtIdCuenta"+fila).value >= 1){
            if(document.getElementById("cmbTipoMovimientoFVC"+fila).value != "" && document.getElementById("cmbTipoMovimientoFVC"+fila).value >= 1){
                var respuesta44 = confirm("Seguro desea modificar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
                if (respuesta44){
                var str = $("#frmFormasCobro2").serialize();
        //      alert(str)
                    $.ajax({
                            url: 'sql/formasCobro.php',
                            type: 'post',
                            data: str+"&idFormaCobro="+idFormaCobro+"&txtAccion="+accion+"&NumeroFilaSeleccionada="+fila,
                            success: function(data){
                                    //$("#div_listar_RegistroDiario").html(data);
                                    document.getElementById("mensaje2").innerHTML=""+data;
                                    listar_formas_cobro2();
                            }
                    });
                }
            }else{
                alert("No se puede guardar. Seleccione el tipo de movimiento");
                document.getElementById("cmbTipoMovimientoFVC"+fila).focus();
            }

      //  }else{
        //        alert("No se puede guardar. Seleccione una cuenta contable");
          //      document.getElementById("txtCodigo"+fila).focus();
           // }

    }else{
        alert("No se puede guardar. El campo Nombre esta vacio.");
        document.getElementById("txtNombre"+fila).focus();
    }


}
function eliminar_forma_cobro(idFormaPago, accion){
    //PAGINA: ajax/formaPago.php
    var respuesta45 = confirm("Seguro desea eliminar este Registro? \nEsta acci\u00f3n eliminara de forma permanente la fila seleccionada");
    if (respuesta45){
            $.ajax({
                    url: 'sql/formasCobro.php',
                    data: 'idFormaPago='+idFormaPago+'&txtAccion='+accion,
                    type: 'post',
                    success: function(data){
                            if(data!="")
                            document.getElementById("mensaje2").innerHTML=""+data;


                            listar_formas_cobro();
                    }
            });
    }
}