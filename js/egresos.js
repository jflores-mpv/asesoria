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


function AgregarFilasEgresos(){
  //  alert("iaaoooo");
    cadena = "";
    cadena = cadena + "<div class='row border mx-5 rounded  bg-white  mt-2 p-1 '>";
    cadena = cadena + "<div style='width:60px' class='pl-5 pt-2'><a onclick=\"limpiarFilasEgresos("+contador+");\" title='Limpiar fila'><i class='fa fa-times' aria-hidden='true'></i></a>    <input type='hidden' style='margin: 0px; width: 100%;' class='form-control' id='txtIdIvaS"+contador+"' name='txtIdIvaS"+contador+"'  readonly='readonly'>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIvaS"+contador+"' name='txtIvaS"+contador+"'  readonly='readonly'> <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtTipoS1"+contador+"' name='txtTipoS1"+contador+"'  readonly='readonly'> </div>";
    cadena = cadena + "<div class='col-lg-1 '> <input type='hidden' id='txtIdServicio"+contador+"' name='txtIdServicio"+contador+"' ><input   type='search' id='txtCodigoServicio"+contador+"' name='txtCodigoServicio"+contador+"' class='form-control  border-0 border-end'   autocomplete='off'  placeholder='Buscar...' onclick='lookup10_edu(this.value, "+contador+", 4,ingreso.value);' onKeyUp='lookup10_edu(this.value, "+contador+", 4,ingreso.value);' />  <div class='suggestionsBox' id='suggestions10"+contador+"' style='display: none; margin-top: 0px; '> <div class='suggestionList' id='autoSuggestionsList10"+contador+"'>  </div> </div></div>";

    cadena = cadena + "<div class='col-lg-4 '><input type='search'  class=' form-control border-0 border-end' autocomplete='off'  id='txtDescripcionS"+contador+"'  name='txtDescripcionS"+contador+"'  value=''  >      </div> ";
    cadena = cadena + "<input type='hidden'  style='margin: 0px; width: 10%; text-align: right; ' class='form-control bg-white' id='txtTipo"+contador+"' name='txtTipo"+contador+"'  readonly='readonly'>";


	cadena = cadena + "<div class='col-lg-1 '><input type='text'    maxlength='10' id='txtCantidadS"+contador+"' name='txtCantidadS"+contador+"'  text-align: right; ' class='form-control border-0 border-end' onKeyUp=\"calculaCantidadEgreso("+contador+")\" onclick=\"calculaCantidadEgreso("+contador+")\"  autocomplete='off' > </div>";
    cadena = cadena + "<div class='col-lg-1 '><input type='text'    maxlength='10' id='txtPrecioS"+contador+"' name='txtPrecioS"+contador+"'  class='form-control border-0 border-end' onchange=\"RecalcularV(frmFacVenta)\" onKeyPress=\"return precio(event)\"> </div>";
    cadena = cadena + "<div class='col-lg-1 '><input type='text'    class='form-control border-0 bg-white border-end' id='txtValorUnitarioS"+contador+"' name='txtValorUnitarioS"+contador+"' onKeyUp=\"calculaCantidadEgreso("+contador+")\" onclick=\"calculaCantidadEgreso("+contador+")\" autocomplete='off'  ></div>";
    cadena = cadena + "<div class='col-lg-2 '><input type='text'    class='form-control border-0 border-end bg-white' id='txtValorTotalS"+contador+"' name='txtValorTotalS"+contador+"'  readonly='readonly'>  </div>";

	cadena = cadena + "<input type='hidden' style='margin: 0px; width: 10%; text-align: right; ' id='cuenta"+contador+"' name='cuenta"+contador+"' class='' readonly='readonly'>"
  //  cadena = cadena + "<div class='col-lg-1 col-sm-3 col-xs-3 '><input type='text' maxlength='10' id='bod"+contador+"'  name='bod"+contador+"' placeholder='' class='form-control  border-0 border-end bg-white'  onKeyUp=\"lookup_cpra_bod(this.value, "+contador+", 7)\"  ></div>"
	cadena = cadena + "<div class='col-lg-1 col-sm-3 col-xs-3 '><input type='text' maxlength='10' id='bod"+contador+"'  name='bod"+contador+"' placeholder='' class='form-control  border-0 border-end bg-white'  onKeyUp=\"lookup_cpra_bod(this.value, "+contador+", 7)\"  ></div>"

    cadena = cadena + "<input type='hidden' style='margin: 0px; width: 10%; text-align: right; ' class='form-control' id='bodegaOrigen"+contador+"' name='bodegaOrigen"+contador+"'  readonly='readonly'>  ";

    cadena = cadena + "<input type='hidden' style='margin: 0px; width: 10%; text-align: right; ' class='form-control' id='txtCalculoIvaS"+contador+"' name='txtCalculoIvaS"+contador+"'  readonly='readonly'>  ";
	cadena = cadena + "<input type='hidden' style='margin: 0px; width: 10%; text-align: right; ' class='border-0 border-end' id='txtIvaItemS"+contador+  "' name='txtIvaItemS"+contador+   "' >";
	cadena = cadena + "<input type='hidden' style='margin: 0px; width: 10%; text-align: right; ' class='border-0 border-end' id='txtTotalItemS"+contador+"' name='txtTotalItemS"+contador+ "'>";
	cadena = cadena + "<input type='hidden' style='margin: 0px; width: 10%; text-align: right; ' placeholder='cuenta P' class='border-0 border-end' id='txtCuentaS"+contador+"' name='txtCuentaS"+contador+"'  readonly='readonly'>";
    cadena = cadena + "<input type='hidden' style='margin: 0px; width: 10%; text-align: right; ' placeholder='' class='border-0 border-end' id='idBodega"+contador+"' name='idBodega"+contador+"'  readonly='readonly'>";
    
	cadena = cadena + "</div>";

    document.getElementById('txtContadorFilas').value=contador;
    contador++;
    $("#tblBodyEgreso ").append(cadena);

}


function limpiarFilasEgresos(con){
    //alert ("entro "+con);
        $("#txtIdIvaS"+con).val("0");
        $("#txtIvaS"+con).val("0");
        $("#txtTipo"+con).val("");

        $("#txtIdServicio"+con).val("0");
        $("#txtCodigoServicio"+con).val("");
        $("#txtDescripcionS"+con).val("");
        $("#txtCantidadS"+con).val("");
       // $("#txtPrecioS"+con).val("0");
	    $("#txtPrecioS"+con).val("");
      //  $("#txtCalculoIvaS"+con).val("0");
      //  $("#txtValorUnitarioS"+con).val("0");
      //  $("#txtValorTotalS"+con).val("0");
		 $("#txtValorUnitarioS"+con).val("");
        $("#txtValorTotalS"+con).val("");
		
		$("#txtTipoProductoS"+con).val("");
		$("#idBodega"+con).val("");
		

        $("#txtSubtotal_EI"+con).val("0");
    //    $("#txtDescuentoFVC"+con).val("0");
    //    $("#txtTotalIvaFVC"+con).val("0");
    //    $("#txtOtrosFVC"+con).val("0");
        $("#txtTotalFVC"+con).val("0");

     //   calculaCantidadEgreso(con);
       // asientosQuitadosFVC();
}


function lookup10_edu(txtNombre, cont, accion,checkSaldoInicial)
{
    console.log("checkSaldoInicial==",checkSaldoInicial);
    if(txtNombre.length == 0)
	{
        // Hide the suggestion box.
        $('#suggestions10'+cont).hide();
    }
	else
	{
        $.post("sql/egresos.php", {queryString: txtNombre, cont: cont,  txtAccion: accion}, function(data){
//            alert(data);
		if(data.length >0)
		{
            $('.suggestionsBox').hide();
            $('#suggestions10'+cont).show();
            $('#autoSuggestionsList10'+cont).html(data);
             //  alert("entro: "+data);
        }
		});
    }
} // lookup


function lookup_cpra_bod(txtNombre, cont, accion) {
//para agregar SERVCIO pagina: nuevaFacturaVenta.php
    //alert("nombre:"+txtNombre+"-"+cont+"-"+accion);
    if(txtNombre.length == 0)
	{
        // Hide the suggestion box.
        $('#suggestions10'+cont).hide();
    }
	else
	{
		//alert("Antes de entrar a sql");
        $.post("sql/facturaCompra.php", {queryString: txtNombre, cont: cont,  txtAccion: accion}, function(data)
		{
			if(data.length >0) {
                 $('.suggestionsBox').hide();
                $('#suggestions10'+cont).show();
                $('#autoSuggestionsList10'+cont).html(data);
             //  alert("entro: "+data);
            }
        });
    }
} // lookup


function fill_cpra_bod(cont1, idBodega, cadena){
    cont=cont1;
    setTimeout("$('.suggestionsBox').hide();", 50);

    array = cadena.split("*");

    $('#idbod'+cont).val(idBodega);
    $('#bod'+cont).val(array[1]);
    $('#cuenta'+cont).val(array[2]);
    //$('#idTipo'+cont).val(array[3]);
	 $('#idBodega'+cont).val(array[3]);
   
}

function fill10_egreso(cont, idServicio, cadena){
console.log(cadena);
   setTimeout("$('.suggestionsBox').hide();", 50);

    array = cadena.split("*");
	$('#txtIdServicio'+cont).val(array[0]);
/*     if($('#txtIdServicio'+cont).val() >= 1){
    }else{
        sumaAsientosAgregados =  $('#txtContadorAsientosAgregadosFVC').val();
        sumaAsientosAgregados ++;
        $('#txtContadorAsientosAgregadosFVC').val(sumaAsientosAgregados);
    }
 */
    if($('#txtIdServicio'+cont).val() >= 1)
    {
        sumaAsientosAgregados =  $('#txtContadorAsientosAgregadosFVC').val();
        sumaAsientosAgregados ++;
        $('#txtContadorAsientosAgregadosFVC').val(sumaAsientosAgregados);
    }



   // $('#txtIdServicio'+cont).val(idServicio);
   $('#txtIdServicio'+cont).val(array[0]);
    $('#txtCodigoServicio'+cont).val(array[7]);
    $('#txtDescripcionS'+cont).val(array[1]);
	$('#txtPrecioS'+cont).val(array[2]);
	$('#txtIdIvaS'+cont).val(array[3]);

    $('#txtIvaS'+cont).val(array[4]);
	$('#txtTipo'+cont).val(array[5]);

	$('#txtCuentaS'+cont).val(array[6]);

    $('#txtTipoProductoS'+cont).val(array[5]);

	$('#txtCalculoIvaS'+cont).val();
      //  $("#txtValorUnitarioS"+con).val("0");
     //   $("#txtValorTotalS"+con).val("0");

    $('#bodegaOrigen'+cont).val(array[9]);
    
    
	var idProducto = parseFloat(array[0]);
    var promedio1 = "";
	CalcularPromedio1(idProducto,cont);

	//alert('promedio1========');
	//alert(promedio1);
	/*   var promedio = parseFloat(promedio1);
	  alert("dddd");
	  alert(promedio);
    $('#txtValorUnitarioS'+cont).val(promedio.toFixed(2));
    */
	 $('#txtCantidadS'+cont).focus();

}


function CalcularPromedio1(idProducto1,cont)
{
	var idProducto=idProducto1;
	//alert("cont==="+cont);
	//alert("estoy en calculo promedio");
	//alert(idProducto);
//    var idProducto = idProducto1.value;
//	alert(idProducto);
	var accion=1;
    ajax2=objetoAjax();
    ajax2.open("POST", "sql/promedio.php",true);
    ajax2.onreadystatechange=function()
	{
        if (ajax2.readyState==4)
		{
            var respuesta=ajax2.responseText;
			console.log("respuesta2====="+respuesta);
			asignapais2(respuesta,cont);
			//return respuesta2;
        }
    }
    //document.getElementById("noRepetirLogin").innerHTML="";
    ajax2.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax2.send("idProducto="+idProducto+"&txtAccion="+accion);
    //return aux;
	//return respuesta2;

}

function asignapais2(cadena,cont1){
	var cont2=cont1;
	promediox=cadena;
	var promedio = parseFloat(promediox);
    $('#txtValorUnitarioS'+cont2).val(promedio.toFixed(2));
}


function calculaCantidadEgreso(posicion){
	//alert("POSICON");
	//alert(posicion);
    //FUNCION QUE PERMITE RECALCULAR EL VALOR IVA SUBTOTAL Y EL TOTAL
    var suma =0;
    var calculoIva = 0;
    var iva = 0;
    cantidad = $("#txtCantidadS"+posicion).val();
    valorUnitario = $("#txtValorUnitarioS"+posicion).val();
    suma = parseFloat(valorUnitario * cantidad);
    iva = $("#txtIvaS"+posicion).val();
        calculoIva = ((suma * iva ) /100);

    $("#txtValorTotalS"+posicion).val(suma.toFixed(2));
    $("#txtCalculoIvaS"+posicion).val(calculoIva.toFixed(2));
    calculoSubTotal_egreso();
}


function calculoSubTotal_egreso(){
	//alert("TOTAL");
    var sumaValorTotal = 0;
    var sumaCalculoIva = 0;
	//alert("contador");
	//alert(contador);
	//var contador1=document.getElementById('txtContadorAsientosAgregadosFVC').value;
	var contador1=document.getElementById('txtContadorFilas').value;
	
//	alert("FILAS");
//	alert(contador1);
    for(i=1;i<contador1;i++){
        valorTotal = $("#txtValorTotalS"+i).val();
		//alert(valorTotal);
        if(valorTotal == ""){
            valorTotal=0;
        }
        sumaValorTotal = sumaValorTotal + parseFloat(valorTotal);
    //	alert(sumaValorTotal);
    }
	
	//txtSubtotalFVC
    document.getElementById('txtSubtotal_EI').value=(sumaValorTotal).toFixed(2);
    //document.getElementById('txtTotalIvaFVC').value=(sumaCalculoIva).toFixed(2);
    calculoTotal_egresos();
}

function calculoTotal_egresos(){
	//txtSubtotal_EI
    var txtSubtotal = $("#txtSubtotal_EI").val();
   // var txtTotalIva = $("#txtTotalIvaFVC").val();
    //var total = (parseFloat(txtSubtotal) + parseFloat(txtTotalIva));
	var total = (parseFloat(txtSubtotal) + parseFloat(txtTotalIva));

    $("#txtTotalFVC").val(total.toFixed(2));
}

function guardarIngresos(accion)
{
	var SubtotalVta = document.frmEgresos['txtSubtotal_EI'].value;
    if(SubtotalVta != 0){
       var str = $("#frmEgresos").serialize();
		//alert(str);
        $.ajax
		({
            url: 'sql/ingresos.php',
            type: 'post',
            data: str+"&txtAccion="+accion,
            beforeSend: function(){
                $("#mensaje1").html("<p align='center'><img src='images/loading.gif' /></p>");
            },
            success: function(data)
			{
		//		alert(data);
                    console.log(data);
                document.getElementById("mensajeEgreso").innerHTML=data;
                    alertify.success(data);
			        document.getElementById('frmEgresos').reset();
			        buscar_secuencial('Ingreso',5,1);

            }
        });
    }else{
        alert ('Total a pagar deber ser mayor que 0.');
    }

}



function guardarEgresos(accion)
{
    //var SubtotalVta = document.frmEgresos['txtTotalFVC'].value;
	var SubtotalVta = document.frmEgresos['txtSubtotal_EI'].value;

	//alert(SubtotalVta);
    if(SubtotalVta != 0){
       var str = $("#frmEgresos").serialize();
		console.log(str);
        $.ajax
		({
            url: 'sql/egresos.php',
            type: 'post',
            data: str+"&txtAccion="+accion,
            beforeSend: function(){
                $("#mensaje1").html("<p align='center'><img src='images/loading.gif' /></p>");
            },
            success: function(data)

			{
			     console.log(data);
			        alertify.success(data);
			        document.getElementById('frmEgresos').reset();
			        buscar_secuencial('Ingreso',5,1);
            }
        });
    }else{
        alert ('Total a pagar deber ser mayor que 0.');
        //document.getElementById("cmbFormaPagoFP").focus();
        //dml.elements['cmbFormaPagoFP'].focus();
    }

}




function guardarTransferencia(accion)

{alert("TRANSFERENCIA");
    //var SubtotalVta = document.frmEgresos['txtTotalFVC'].value;
	var SubtotalVta = document.frmEgresos['txtSubtotal_EI'].value;

	//alert(SubtotalVta);
    if(SubtotalVta != 0){
       var str = $("#frmEgresos").serialize();
		//alert(str);
        $.ajax
		({
            url: 'sql/transferencias.php',
            type: 'post',
            data: str+"&txtAccion="+accion,
            beforeSend: function(){
                $("#mensaje1").html("<p align='center'><img src='images/loading.gif' /></p>");
            },
            success: function(data)
			{

                document.getElementById("mensajeEgreso").innerHTML=data;
                if(data.length == 87){
                    //document.getElementById("frmServicios").reset();
                }
            }
        });
    }else{
        alert ('Total a pagar deber ser mayor que 0.');
        //document.getElementById("cmbFormaPagoFP").focus();
        //dml.elements['cmbFormaPagoFP'].focus();
    }
}


function lookup_egreso(txtCuenta, cont, accion)
{
	//alert("buscar");
    if(txtCuenta.length == 0)
	{
            // Hide the suggestion box.
            $('#suggestions1').hide();
    }
	else
	{
	let ingreso=   document.getElementById("radio-one").checked;
    let egreso=   document.getElementById("radio-two").checked;
    let transferencia=   document.getElementById("radio-three").checked;
	if (ingreso){
		tipomov='I';
		  accion=9;
	}
	if (egreso){
		tipomov='E';
		accion=8;
	}
	if (transferencia){
		tipomov='T';
        accion=10;
	}
	
	//var ingreso = document.frmEgresos['radio-one'].checked;
//var egreso = document.frmEgresos['radio-two'].checked;

	//alert("opCIONES");
	//alert(ingreso);
	//alert(egreso);
	//if ingreso
	//	{
	//	$.post("sql/ingreso_buscar.php", {queryString: ""+txtCuenta+"",aux: cont, txtAccion: accion}, function(data)
	//	$.post("sql/egreso_buscar.php", {queryString: ""+txtCuenta+"",aux: cont, txtAccion: accion}, function(data)
	//	}
	//	if egreso{
			$.post("sql/egreso_buscar.php", {queryString: ""+txtCuenta+"",aux: cont, txtAccion: accion, tipomov:tipomov }, function(data)
		
	//	}
		//alert("antes de buscar egreso");
		
		{
         //   alert(data);
		if(data.length >0)
		{
			arrayPrincipal = data.split("î");//  dividivos el vector principal con el caracter: î
				//alert(arrayPrincipal[1]);
			array = arrayPrincipal[1].split("*");// * dividivos el vector por el numero de filas que arroga la consulta
				//array = data.split("*");// * dividivos el vector por el numero de filas que arroga la consulta

			limite = array.length;
			var contFilas1;
				//contador = 1;
			//	document.getElementById('txtContadorFilas').value = contador;
			contFilas1=document.getElementById('txtContadorFilas').value
			
				// ELIMINA FILAS DEPENDIENDO DEL CONTADOR DE LA CONSULTA
			for(c=1;c<=contFilas1;c++){
					eliminaFilas();
			}

			for(c=1;c<=contFilas1;c++)
			{
					limpiarFilasEgresos(c);
			}
				// AGREGA LOS DATOS A LOS TXT

			var sumaValorTotal=0;
			var subtotal=0;
		//	var contador_filax=0;
	//		alert("aacccccaabbbb");
			for(i=1; i<=limite-1; i++)
			{
				datos = array[i].split("?");
				fecha = datos[3].split(" ");//solo cojemos la fecha, no la hora

				$('#txtFechaFVC').val(fecha[3]);
				$('#txtObservacion').val(datos[4]);
				
					$('#txtIdServicio'+i).val(datos[5]);
					$('#txtCodigoServicio'+i).val(datos[6]);
					$('#txtDescripcionS'+i).val(datos[7]);
					$('#txtCantidadS'+i).val(datos[8]);
					$('#txtValorUnitarioS'+i).val(datos[9]);
					$('#txtValorTotalS'+i).val(datos[10]);
					$('#bod'+i).val(datos[15]);
					$('#txtTipo'+i).val(datos[12]);
					$('#txtCuentaS'+i).val(datos[13]);
					$('#cuenta'+i).val(datos[14]);
					$('#idBodega'+i).val(datos[11]);
				    $('#txtPrecioS'+i).val(datos[16]);
			//		$('#txtIvaItemS'+i).val(datos[13]);
			//		$('#txtTotalItemS'+i).val(datos[14]);
			
			 
			cmbBodegas.value=datos[11];
					$('#txtContadorAsientosAgregadosFVC').val(limite-1)
					subtotal = $("#txtValorTotalS"+i).val();
					sumaValorTotal = sumaValorTotal + parseFloat(subtotal);
					//contador_filax=contador_filax+1;
				//	alert(sumaValorTotal);
            	if (transferencia){
            	    	cmbBodegas.value=datos[17];
	             $('#bodegaOrigen'+i).val(datos[11]);
                	}
			}
			//document.getElementById('contadorFilas').value=(contador_filax).toFixed(2);
			document.getElementById('txtSubtotal_EI').value=(sumaValorTotal).toFixed(2);
			document.getElementById('txtTotalFVC').value=(sumaValorTotal).toFixed(2);
			var numero_agregados=document.getElementById('txtContadorAsientosAgregadosFVC').value
			if (numero_agregados>contFilas1)
			{
				document.getElementById('txtContadorFilas').value=numero_agregados;
				calculoSubTotal_egreso();
			}
			
		}
			var subtotalx=document.getElementById('txtSubtotal_EI').value;
//alert(subtotalx);
			if (subtotalx >0)
            {
				//alert("entro1dddd");
				document.getElementById("btnEliminar").style.visibility = "visible";
                        //document.getElementById("btnGuardar").disabled = true;
				document.getElementById("btnGuardar").style.visibility="hidden";
				document.getElementById("btnEditar").style.visibility = "visible";
				
			}
			else
			{
				//alert("entro2");
				document.getElementById("btnEliminar").style.visibility = "hidden";
                       document.getElementById("btnGuardar").disabled = false;
				document.getElementById("btnGuardar").style.visibility="visible";
				document.getElementById("btnEditar").style.visibility = "hidden";

				
			}

		});

	}

} // lookup

function lookup10_edu(txtNombre, cont, accion,checkSaldoInicial)
{
    // console.log("checkSaldoInicial==",checkSaldoInicial);
    if(txtNombre.length == 0)
	{
        // Hide the suggestion box.
        $('#suggestions10'+cont).hide();
    }
	else
	{
        $.post("sql/egresos.php", {queryString: txtNombre, cont: cont,  txtAccion: accion,checkSaldoInicial:checkSaldoInicial}, function(data){
//            alert(data);
		if(data.length >0)
		{
            $('.suggestionsBox').hide();
            $('#suggestions10'+cont).show();
            $('#autoSuggestionsList10'+cont).html(data);
             //  alert("entro: "+data);
        }
		});
    }
} // lookup

function calcular_total_egreso(){
    var suma =0;
	var totiva=0;
	var total1=0;
	var dec=0;
    for(i=1;i<=8;i++)
	{
	    de = $("#txtValorTotalS"+i).val();
		de1 = $("#txtIvaItemS"+i).val();
		de2 = $("#txtTotalItemS"+i).val();
	//            txtTotalItemS
	//	alert(de);
	//	alert(de1);
	//	al);rt(de2);

	 if(de == ""){
            de=0;
        }
        else
        {
        suma = suma + parseFloat(de);
        }

        if(de1 == ""){
            de1=0;
        }
        else
        {
        totiva = totiva+ parseFloat(de1);
        }

        if(de2 == ""){
            de2=0;
        }
        else
        {
        	total1 = total1 + parseFloat(de2);
        }

		// alert(de2);
	//	alert(totiva);
		//alert(total1);
		if (suma > 0)
		{
		debe=document.getElementById('txtSubtotal_EI').value=(suma).toFixed(4);

		}
		if (total1 > 0)
		{
		debe1=document.getElementById('txtSubtotal_EI').value=(totiva).toFixed(4);
		}
		debe2=document.getElementById('txtSubtotal_EI').value=(total1).toFixed(2);
    //txtTotalFVC
	}
}

function buscar_secuencial(valor,accion,op)
{

	console.log("valor",valor,"accion",accion,"op",op);
	if (op==1)
	{ 	var tipo_docum=valor; 	}
	else
	{  var tipo_docum=valor.value; 	}

    auxiliar=0;
    ajax9=objetoAjax();
    ajax9.open("POST", "sql/egresos.php",true);
    String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g, "");};
    ajax9.onreadystatechange=function() 
	{
        if (ajax9.readyState==4) 
		{
            var respuesta9=ajax9.responseText;
		//	alert(respuesta9);
            if(respuesta9.trim()>0)
			{
			 console.log("respuest",respuesta9);
				document.getElementById("txtNumeroEgreso").value = respuesta9.trim();
                auxiliar = respuesta9.trim();
                return auxiliar;
            }
        }
    }
document.getElementById("txtNumeroEgreso").innerHTML="" ;
ajax9.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
ajax9.send("tipo_docum="+tipo_docum+"&txtAccion="+accion);
return auxiliar;
}

function modificar_egreso(form)
{
	//alert("modificar12222111");
	//var txtPermisosAsientosContablesModificar = form.elements['txtPermisosAsientosContablesModificar'].value;
	var txtPermisosAsientosContablesModificar="Si";
    if(txtPermisosAsientosContablesModificar == "No"){
         alert ("Usted No tiene permisos. \nConsulte con el Administrador.");
    }
	else
	{
        contadorFilas = $('#txtContadorFilas').val();
		//alert("dd");
		//alert(contadorFilas);

        if(contadorFilas >=1)
		{
            var respuesta32 = confirm("Seguro desea editar este Egreso?");
            if (respuesta32)
			{
                var accionEAS = 2;
                document.getElementById("btnEditar").style.visibility="hidden";
				document.getElementById("btnEliminar").style.visibility="hidden";
                var strEAC = $("#frmEgresos").serialize();
			//	    alert("antes");
          //      alert(strEAC);
                
				$.ajax(
				 {
				   url: 'sql/egresos.php',
                    data: strEAC+'&txtAccion='+accionEAS,
                    type: 'post',
                    success: function(data)
					  {
                      //          alert(data);
						$('#mensaje1').show();
						document.getElementById('mensaje1').innerHTML=""+data;
						document.getElementById('mensaje1').style.opacity = "1";

						clearTimeout(); //detiene el tiempo
						setTimeout(function(){
							for (i = 10; i >= 0; i--){
								setTimeout("document.getElementById('mensaje1').style.opacity = '" + (i / 10) + "'", (10 - i) * 100);
							}
							setTimeout("$('#mensaje1').hide();", 1000);
                        }, 5000);
                      }
                  });
            }
        }
		else
		{
                alert ('No hay suficientes cuentas para editar.');
        }
    }
}


function modificar_ingreso()
{
	//alert("modificar12222111");
	//var txtPermisosAsientosContablesModificar = form.elements['txtPermisosAsientosContablesModificar'].value;
	var txtPermisosAsientosContablesModificar="Si";
    if(txtPermisosAsientosContablesModificar == "No"){
         alert ("Usted No tiene permisos. \nConsulte con el Administrador.");
    }
	else
	{
        contadorFilas = $('#txtContadorFilas').val();
		//alert("dd");
		//alert(contadorFilas);

        if(contadorFilas >=1)
		{
            var respuesta32 = confirm("Seguro desea editar este Egreso?");
            if (respuesta32)
			{
                var accionEAS = 2;
                document.getElementById("btnEditar").style.visibility="hidden";
				document.getElementById("btnEliminar").style.visibility="hidden";
                var strEAC = $("#frmEgresos").serialize();
			//	    alert("antes");
                //alert(strEAC);
                
				$.ajax(
				 {
				   url: 'sql/ingresos.php',
                    data: strEAC+'&txtAccion='+accionEAS,
                    type: 'post',
                    success: function(data)
					  {
                      //          alert(data);
						$('#mensaje1').show();
						document.getElementById('mensaje1').innerHTML=""+data;
						document.getElementById('mensaje1').style.opacity = "1";

						clearTimeout(); //detiene el tiempo
						setTimeout(function(){
							for (i = 10; i >= 0; i--){
								setTimeout("document.getElementById('mensaje1').style.opacity = '" + (i / 10) + "'", (10 - i) * 100);
							}
							setTimeout("$('#mensaje1').hide();", 1000);
                        }, 5000);
                      }
                  });
            }
        }
		else
		{
                alert ('No hay suficientes cuentas para editar.');
        }
    }
}