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




function reportePedidos(){
//	fn_cerrar1();
    $("#div_oculto").load("ajax/reportePedidos.php", function(){
	$.blockUI({
			message: $('#div_oculto'),
			overlayCSS: {backgroundColor: '#111'},
			css:{
                '-webkit-border-radius': '3px',
                '-moz-border-radius': '3px',
                'box-shadow': '6px 6px 20px gray',
				top: '15%',
				left: '5%',
				width: '90%',
                position: 'absolute'
			}
		});
       listarPedidos();
	});
} 
 
function listarPedidos(){
    var str = $("#form1").serialize();
    $.ajax({
            url: 'ajax/listarPedidos.php',
            type: 'get',
            data: str,
            success: function(data){
                    $("#div_listar_pedidos").html(data);
            }
    });
}


function facturar(){
//	fn_cerrar1();
    $("#div_oculto").load("ajax/facturar.php", function(){
	$.blockUI({
			message: $('#div_oculto'),
			overlayCSS: {backgroundColor: '#111'},
			css:{
                '-webkit-border-radius': '3px',
                '-moz-border-radius': '3px',
                'box-shadow': '6px 6px 20px gray',
				top: '15%',
				left: '5%',
				width: '90%',
                position: 'absolute'
			}
		});
       listarPedidos();
	});
} 

// txtCalculoIvaS

var codigos = [];
var contador=1;
function AgregarFilasPedido(id, producto,precio,iva='No'){
    cantidad = 1;
    let index = codigos.indexOf(id);
    if (index>=0){
        let fila = index + 1;
        cantidad = Number($("#txtCantidadS"+fila).val()) + 1;
        $("#txtCantidadS"+fila).val(cantidad);
        calculaCantidadEgreso(fila);
    }else{
        codigos.push(id);
        cadena = "";
        cadena = cadena + "<div class='row bg-light '>";
        cadena = cadena + "<div class='col-lg-1 '><a onclick=\"limpiarFilasEgresos("+contador+");\" title='Limpiar fila'><i class='fa fa-close'></i></a></div>";
    	cadena = cadena + "<input type='hidden' id='txtIdServicio"+contador+"' name='txtIdServicio"+contador+"' value='"+id+"' > ";
    	cadena = cadena + "<input  type='hidden' id='txtCodigoServicio"+contador+"' name='txtCodigoServicio"+contador+"' class='p-2 border-0 w-100 ' value='"+id+"'   autocomplete='off'  onclick='lookup10_edu(this.value, "+contador+", 4);' onKeyUp='lookup10_edu(this.value, "+contador+", 4);' />  <div class='suggestionsBox' id='suggestions10"+contador+"' style='display: none; margin-top: 0px; '> <div class='suggestionList' id='autoSuggestionsList10"+contador+"'>   </div></div>";
        cadena = cadena + "<div class='col-lg-3'><input type='search' style='margin: 0px; width: 100%;' class='form-control' autocomplete='off' value='"+producto+"'  id='txtDescripcionS"+contador+"'  placeholder='Buscar...'  name='txtDescripcionS"+contador+"'   />  </div> ";
    	cadena = cadena + "<div class='col-lg-2'><input type='text' maxlength='10' id='txtCantidadS"+contador+"' value='1' name='txtCantidadS"+contador+"' class='form-control' onKeyUp=\"calculaCantidadEgreso("+contador+")\" onclick=\"calculaCantidadEgreso("+contador+")\" onKeyPress=\"return soloNumeros(event)\" autocomplete='off' > </div>";
        cadena = cadena + "<div class='col-lg-2'><input type='text' style='margin: 0px; width: 100%; text-align: right; '  class='form-control' value='"+precio+"' id='txtValorUnitarioS"+contador+"' name='txtValorUnitarioS"+contador+"' onKeyUp=\"calculaCantidadEgreso("+contador+")\" onclick=\"calculaCantidadEgreso("+contador+")\" autocomplete='off'  ></div>";
    	cadena = cadena + "<input type='hidden' style='margin: 0px; width: 100%; text-align: right; ' class='form-control' id='txtIdCostoS"+contador+"' name='txtIdCostoS"+contador+"'  readonly='readonly'>";
    	
    		cadena = cadena + "<input type='hidden' style='margin: 0px; width: 100%; text-align: right; ' class='form-control' id='txtIvaS"+contador+"' name='txtIvaS"+contador+"' value='"+iva+"'  readonly='readonly'>";
    		
    		
    		
    		cadena = cadena + "<input type='hidden' style='margin: 0px; width: 100%; text-align: right; ' class='form-control' id='txtCalculoIvaS"+contador+"' name='txtCalculoIvaS"+contador+"'  readonly='readonly'>";
    	
    		
        cadena = cadena + "<div class='col-lg-2'><input type='text' style='margin: 0px; width: 100%; text-align: right; ' class='form-control' id='txtValorTotalS"+contador+"' value='"+precio+"' name='txtValorTotalS"+contador+"'  readonly='readonly'>  </div>";
        cadena = cadena + "<div class='col-lg-1'><input type='checkbox' class='form-check-input' id='facturar"+contador+"' name='facturar"+contador+"'  readonly='readonly'>  </div>";
        cadena = cadena + "<div class='col-lg-1 '><a onclick=\"agregarDetalle("+contador+");\" title='Limpiar fila'><i class='fa fa-plus'></i></a></div>";
        cadena = cadena + "</div>";
        cadena = cadena + "<div  id='detalles"+contador+"'></div>";
        
        document.getElementById('txtContadorFilasFVC').value=contador;
        $("#tblBodyProduccion").append(cadena).after();
        let temporal = contador;
        
        setTimeout(function(){calculaCantidadEgreso(temporal);}, 500);
        contador++;
        
    }
    

}


function AgregarPedidoFilas(){

        cadena = "";
        cadena = cadena + "<div class='row bg-light '>";
        cadena = cadena + "<div class='col-lg-1 '><a onclick=\"limpiarFilasEgresos("+contador+");\" title='Limpiar fila'><i class='fa fa-close'></i></a></div>";
    	cadena = cadena + "<input type='hidden' id='txtIdServicio"+contador+"' name='txtIdServicio"+contador+"'  > ";
    	cadena = cadena + "<input  type='hidden' id='txtCodigoServicio"+contador+"' name='txtCodigoServicio"+contador+"' class='p-2 border-0 w-100 '   autocomplete='off'  onclick='lookup10_edu(this.value, "+contador+", 4);' onKeyUp='lookup10_edu(this.value, "+contador+", 4);' />  <div class='suggestionsBox' id='suggestions10"+contador+"' style='display: none; margin-top: 0px; '> <div class='suggestionList' id='autoSuggestionsList10"+contador+"'>   </div></div>";
        cadena = cadena + "<div class='col-lg-3'><input type='search' style='margin: 0px; width: 100%;' class='form-control' autocomplete='off'  id='txtDescripcionS"+contador+"'  placeholder='Buscar...'  name='txtDescripcionS"+contador+"'   />  </div> ";
    	cadena = cadena + "<div class='col-lg-2'><input type='text' maxlength='10' id='txtCantidadS"+contador+"'  name='txtCantidadS"+contador+"' class='form-control' onKeyUp=\"calculaCantidadEgreso("+contador+")\" onclick=\"calculaCantidadEgreso("+contador+")\" onKeyPress=\"return soloNumeros(event)\" autocomplete='off' > </div>";
        cadena = cadena + "<div class='col-lg-2'><input type='text' style='margin: 0px; width: 100%; text-align: right; '  class='form-control'  id='txtValorUnitarioS"+contador+"' name='txtValorUnitarioS"+contador+"' onKeyUp=\"calculaCantidadEgreso("+contador+")\" onclick=\"calculaCantidadEgreso("+contador+")\" autocomplete='off'  ></div>";
    	cadena = cadena + "<input type='hidden' style='margin: 0px; width: 100%; text-align: right; ' class='form-control' id='txtIdCostoS"+contador+"' name='txtIdCostoS"+contador+"'  readonly='readonly'>";
        cadena = cadena + "<div class='col-lg-2'><input type='text' style='margin: 0px; width: 100%; text-align: right; ' class='form-control' id='txtValorTotalS"+contador+"' name='txtValorTotalS"+contador+"'  readonly='readonly'>  </div>";
        
        	cadena = cadena + "<input type='hidden' style='margin: 0px; width: 100%; text-align: right; ' class='form-control' id='txtIvaS"+contador+"' name='txtIvaS"+contador+"' value=''  readonly='readonly'>";
    		
    		
    		
    		cadena = cadena + "<input type='hidden' style='margin: 0px; width: 100%; text-align: right; ' class='form-control' id='txtCalculoIvaS"+contador+"' name='txtCalculoIvaS"+contador+"'  readonly='readonly'>";
    		
        cadena = cadena + "<div class='col-lg-1'><input type='checkbox' class='form-check-input' id='facturar"+contador+"' name='facturar"+contador+"'  readonly='readonly'>  </div>";
        cadena = cadena + "<div class='col-lg-1 '><a onclick=\"agregarDetalle("+contador+");\" title='Limpiar fila'><i class='fa fa-plus'></i></a></div>";
        cadena = cadena + "</div>";
        cadena = cadena + "<div  id='detalles"+contador+"'></div>";
        
        document.getElementById('txtContadorFilasFVC').value=contador;
        $("#tblBodyProduccion").append(cadena).after();
        
          let temporal = contador;
        setTimeout(function(){calculaCantidadEgreso(temporal);}, 500);
        contador++;

}





function EliminarFilas(){
     $("#tblBodyProduccion").html("");
     contador = 1;
}

function agregarDetalle(fila){
    //alert(contador2);
    cadena = "";
    
    cadena = cadena + "<div class='row bg-light my-2 r-10 px-2'>";

    cadena = cadena + "<div class='col-lg-12'><input type='search' style='margin: 0px; width: 100%;' class='form-control' autocomplete='off'  id='txtDescripcionDetalle"+fila+"'  placeholder='Buscar...'  name='txtDescripcionDetalle"+fila+"'  value=''></div> ";

    cadena = cadena + "</div>";
    
    //document.getElementById('txtContadorFilasDetalles').value=contador2;
    //contador2++;
    
    $("#detalles"+fila).append(cadena);
    
}


var contador=1;
// function AgregarFilasProduccion(){
   
//     cadena = "";
//     cadena = cadena + "<div class='input-group p-2'>";
//     cadena = cadena + "<a onclick=\"limpiarFilasEgresos("+contador+");\" title='Limpiar fila' class='btn btn-warning'><i class='fa fa-close'></i></a>";
// 	cadena = cadena + "<input type='hidden' id='txtIdServicio"+contador+"' name='txtIdServicio"+contador+"' > ";
// 	cadena = cadena + "<input  type='hidden' id='txtCodigoServicio"+contador+"' name='txtCodigoServicio"+contador+"' class='p-2 border-0 w-100 '  autocomplete='off'  onclick='lookup10_edu(this.value, "+contador+", 4);' onKeyUp='lookup10_edu(this.value, "+contador+", 4);' />  <div class='suggestionsBox' id='suggestions10"+contador+"' style='display: none; margin-top: 0px; '> <div class='suggestionList' id='autoSuggestionsList10"+contador+"'>   </div></div>";
//     cadena = cadena + "<div class='col-lg-3'><input type='search' style='margin: 0px; width: 100%;' class='form-control' autocomplete='off'  id='txtDescripcionS"+contador+"'  placeholder='Buscar...'  name='txtDescripcionS"+contador+"'  value='' onclick='lookup10_edu(this.value, "+contador+", 4);' onKeyUp='lookup10_edu(this.value, "+contador+", 4);' />  <div class='suggestionsBox' id='suggestions10"+contador+"' style='display: none; margin-top: 0px; '> <div class='suggestionList' id='autoSuggestionsList10"+contador+"'></div></div></div> ";
// 	cadena = cadena + "<input type='hidden' maxlength='10' id='bod"+contador+"' name='bod"+contador+"' class='form-control'  autocomplete='off' onkeyup=\"lookup_cpra_bod(this.value, 1, 20)\" ><input type='hidden'  id='idbod"+contador+"' name='idbod"+contador+"' ><input type='hidden'  id='cuenta"+contador+"' name='cuenta"+contador+"' ><input type='hidden'  id='txtTipoS"+contador+"' name='txtTipoS"+contador+"' >";
// 	cadena = cadena + "<div class='col-lg-2'><input type='text' maxlength='10' id='txtCantidadS"+contador+"' name='txtCantidadS"+contador+"' class='form-control' onKeyUp=\"calculaCantidadEgreso("+contador+")\" onclick=\"calculaCantidadEgreso("+contador+")\" onKeyPress=\"return soloNumeros(event)\" autocomplete='off' > </div>";
//     cadena = cadena + "<div class='col-lg-2'><input type='text' style='margin: 0px; width: 100%; text-align: right; ' class='form-control' id='txtValorUnitarioS"+contador+"' name='txtValorUnitarioS"+contador+"' onKeyUp=\"calculaCantidadEgreso("+contador+")\" onclick=\"calculaCantidadEgreso("+contador+")\" autocomplete='off'  ></div>";
// 	cadena = cadena + "<input type='hidden' style='margin: 0px; width: 100%; text-align: right; ' class='form-control' id='txtIdCostoS"+contador+"' name='txtIdCostoS"+contador+"'  readonly='readonly'>";
//     cadena = cadena + "<div class='col-lg-2'><input type='text' style='margin: 0px; width: 100%; text-align: right; ' class='form-control' id='txtValorTotalS"+contador+"' name='txtValorTotalS"+contador+"'  readonly='readonly'>  </div>";
//     // cadena = cadena + "<div class='col-lg-1'><input type='checkbox' class='form-check-input' id='facturar"+contador+"' name='facturar"+contador+"'  readonly='readonly'>  </div>";
//     // cadena = cadena + "<div class='col-lg-1 '><a onclick=\"agregarDetalle("+contador+");\" title='Limpiar fila'><i class='fa fa-plus'></i></a></div>";
//     cadena = cadena + "</div>";
//     cadena = cadena + "<div  id='detalles'></div>";
    
//     document.getElementById('txtContadorFilasFVC').value=contador;
//     contador++;
//     $("#tblBodyProduccion").append(cadena);

// }
function AgregarFilasProduccion(){
   
    cadena = "";
    cadena = cadena + "<div class='row bg-light my-2 r-10 p-2'>";
    cadena = cadena + "<div class='col-lg-1 '><a onclick=\"limpiarFilasEgresos("+contador+");\" title='Limpiar fila'><i class='fa fa-close'></i></a></div>";
	cadena = cadena + "<input type='hidden' id='txtIdServicio"+contador+"' name='txtIdServicio"+contador+"' > ";
	cadena = cadena + "<input  type='hidden' id='txtCodigoServicio"+contador+"' name='txtCodigoServicio"+contador+"' class='p-2 border-0 w-100 '  autocomplete='off'  onclick='lookup10_edu(this.value, "+contador+", 4);' onKeyUp='lookup10_edu(this.value, "+contador+", 4);' />  <div class='suggestionsBox' id='suggestions10"+contador+"' style='display: none; margin-top: 0px; '> <div class='suggestionList' id='autoSuggestionsList10"+contador+"'>   </div></div>";
    cadena = cadena + "<div class='col-lg-3'><input type='search' style='margin: 0px; width: 100%;' class='form-control' autocomplete='off'  id='txtDescripcionS"+contador+"'  placeholder='Buscar...'  name='txtDescripcionS"+contador+"'  value='' onclick='lookup10_edu(this.value, "+contador+", 4);' onKeyUp='lookup10_edu(this.value, "+contador+", 4);' />  <div class='suggestionsBox' id='suggestions10"+contador+"' style='display: none; margin-top: 0px; '> <div class='suggestionList' id='autoSuggestionsList10"+contador+"'></div></div></div> ";

	cadena = cadena + "<input type='hidden' maxlength='10' id='bod"+contador+"' name='bod"+contador+"' class='form-control'  autocomplete='off' onkeyup=\"lookup_cpra_bod(this.value, 1, 20)\" ><input type='hidden'  id='idbod"+contador+"' name='idbod"+contador+"' ><input type='hidden'  id='cuenta"+contador+"' name='cuenta"+contador+"' ><input type='hidden'  id='txtTipoS"+contador+"' name='txtTipoS"+contador+"' >";

	cadena = cadena + "<div class='col-lg-1'><input type='text' maxlength='10' id='txtCantidadS"+contador+"' name='txtCantidadS"+contador+"' class='form-control' onKeyUp=\"calculaCantidadEgreso("+contador+")\" onclick=\"calculaCantidadEgreso("+contador+")\" onKeyPress=\"return soloNumeros(event)\" autocomplete='off' > </div>";

    cadena = cadena + "<div class='col-lg-1'><select   id='txtLoteS"+contador+"' name='txtLoteS"+contador+"' class='form-control' ><option value='0'>No selecionado</option></select> </div>";
    
    cadena = cadena + "<div class='col-lg-1'><select   id='txtBodegasS"+contador+"' name='txtBodegasS"+contador+"' class='form-control' ><option value='0'>No selecionado</option></select> </div>";

    cadena = cadena + "<div class='col-lg-2'><input type='text' style='margin: 0px; width: 100%; text-align: right; ' class='form-control' id='txtValorUnitarioS"+contador+"' name='txtValorUnitarioS"+contador+"' onKeyUp=\"calculaCantidadEgreso("+contador+")\" onclick=\"calculaCantidadEgreso("+contador+")\" autocomplete='off'  ></div>";
	cadena = cadena + "<input type='hidden' style='margin: 0px; width: 100%; text-align: right; ' class='form-control' id='txtIdCostoS"+contador+"' name='txtIdCostoS"+contador+"'  readonly='readonly'>";
    cadena = cadena + "<div class='col-lg-2'><input type='text' style='margin: 0px; width: 100%; text-align: right; ' class='form-control' id='txtValorTotalS"+contador+"' name='txtValorTotalS"+contador+"'  readonly='readonly'>  </div>";
    cadena = cadena + "<div class='col-lg-1'><input type='checkbox' class='form-check-input' id='facturar"+contador+"' name='facturar"+contador+"'  readonly='readonly'>  </div>";
    cadena = cadena + "<div class='col-lg-1 '><a onclick=\"agregarDetalle("+contador+");\" title='Limpiar fila'><i class='fa fa-plus'></i></a></div>";
    cadena = cadena + "</div>";
    cadena = cadena + "<div  id='detalles'></div>";
    
    cargarLotes(4,contador);
   cargarBodegas(10,contador);
    
    document.getElementById('txtContadorFilasFVC').value=contador;
    contador++;

    $("#tblBodyProduccion").append(cadena);
}

function cargarLotes(accion,contador){
	$.ajax({
		url:'sql/lotes.php',
		type:'post',
		data: {txtAccion:accion},
		success:function(res){
		    console.log(res);
			$("#txtLoteS"+contador).html(res);

		}
	});
}
function cargarBodegas(accion,contador){
	$.ajax({
		url:'sql/facturaCompra.php',
		type:'post',
		data: {txtAccion:accion},
		success:function(res){
	//	    console.log(res);
			$("#txtBodegasS"+contador).html(res);

		}
	});
}

function listarGrupos(){
    var str = $("#form1").serialize();
    $.ajax({
            url: 'ajax/listarGrupos.php',
            type: 'get',
            data: str,
            success: function(data){
                    $("#div_listar_grupos").html(data);
            }
    });
}




function limpiarFilasEgresos(con){
    //alert ("entro "+con);
	    
    if($('#txtIdServicio'+con).val() >= 1){
      //  $("#txtIdIvaS"+con).val("0");
      //  $("#txtIvaS"+con).val("0");
        $("#txtTipo"+con).val("");

       // $("#txtIdServicio"+con).val("0");
	    $("#txtIdServicio"+con).val(" ");
        $("#txtCodigoServicio"+con).val("");
        $("#txtDescripcionS"+con).val("");
        $("#txtCantidadS"+con).val("");
        $("#txtValorUnitarioS"+con).val("0");
        $("#txtValorTotalS"+con).val("0");
		$("#txtCuentaS"+con).val("0");
        $("#txtIdCostoS"+con).val("0");
		$("#txtTipoProductoS"+con).val("");
        $("#txtSubtotalFVC"+con).val("0");
        $("#txtDescuentoFVC"+con).val("0");
        $("#txtTotalIvaFVC"+con).val("0");
        $("#txtOtrosFVC"+con).val("0");
        $("#txtTotalFVC"+con).val("0");
		
        calculaCantidadEgreso(con);
       // asientosQuitadosFVC();
    }else{

    }
}

function lookup10_edu(txtNombre, cont, accion) 
{
	produccion="Si";
  
	
    if(txtNombre.length == 0)
	{
        // Hide the suggestion box.
        $('#suggestions10'+cont).hide();
    }
	else 
	{
        $.post("sql/produccion.php", {queryString: txtNombre, cont: cont,  txtAccion: accion, txtProduccion: produccion}, function(data){
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


function fill_produccion(cont, idServicio, cadena)
{

//	if (cont>5)
	//{ 
//	 alert(" cont: "+cont+"  idServicio: "+idServicio+" cadena: "+cadena)
    // alert(cadena);
	//}
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

    $('#txtIdServicio'+cont).val(array[0]);
    $('#txtCodigoServicio'+cont).val(array[1]);
    $('#txtDescripcionS'+cont).val(array[2]);
//	$('#txtCuentaS'+cont).val(array[5]);
	$('#txtIdCostoS'+cont).val(array[6]);
	
	
	var total_cd = parseFloat(array[3]);
	var total_ci = parseFloat(array[4]);
	var total_prod=total_cd+total_ci;
	
	$('#txtValorUnitarioS'+cont).val(total_prod.toFixed(2));
		//$('#txtValorTotalS'+cont).val(array[5]);
		
	
   $('#txtCantidadS'+cont).focus();

}

function calculaCantidadEgreso(posicion){
    //FUNCION QUE PERMITE RECALCULAR EL VALOR IVA SUBTOTAL Y EL TOTAL
	//alert(posicion)
    var suma =0;
    var calculoIva = 0;
    var iva = 0;
    cantidad = $("#txtCantidadS"+posicion).val();
    valorUnitario = $("#txtValorUnitarioS"+posicion).val();
    suma = parseFloat(valorUnitario * cantidad);
    
    let existeIva= ($("#txtIvaS"+posicion).val()== undefined)?'no':$("#txtIvaS"+posicion).val();
    iva = (existeIva=='Si')? $("#impuestoGeneral").val() :0;
        calculoIva = ((suma * iva ) /100);
        
        
        
     $("#txtCalculoIvaS"+posicion).val(calculoIva.toFixed(2));
    // 	 calculoIva = ((suma * 1.12 ) /100);
   
    $("#txtValorTotalS"+posicion).val(suma.toFixed(2));
  
    calculoSubTotal_ventas();
}


function calculoSubTotal_ventas(){
    var sumaValorTotal = 0;
    var sumaCalculoIva = 0;
//	alert("contador"+contador);
    for(i=1;i<contador;i++){
	
		valorTotal = $("#txtValorTotalS"+i).val();
        calculoIva = $("#txtCalculoIvaS"+i).val();
        if(valorTotal == ""){
            valorTotal=0;
        }
        
	
		 
        sumaValorTotal = sumaValorTotal + parseFloat(valorTotal);
        sumaCalculoIva = sumaCalculoIva + parseFloat(calculoIva);
	

    }
    document.getElementById('txtSubtotalFVC').value=(sumaValorTotal).toFixed(2);
      if(document.getElementById('txtTotalIvaFVC')){
        document.getElementById('txtTotalIvaFVC').value=(sumaCalculoIva).toFixed(2);
    }

    calculoTotal_Ventas();
}

function calculoTotal_Ventas(){
    var txtSubtotal = $("#txtSubtotalFVC").val();
    var txtTotalIva = $("#txtTotalIvaFVC").val();
    var total = (parseFloat(txtSubtotal) + parseFloat(txtTotalIva));
    $("#txtTotalFVC").val(total.toFixed(2));
}
function guardarProduccion(accion){ 
	//txtSubtotalFVC
    //var SubtotalVta = document.frmEgresos['txtTotalFVC'].value;
	var SubtotalVta = document.frmProduccion['txtSubtotalFVC'].value;	
    let numeroOrden= document.getElementById('txtNumeroProduccion').value;
    if(SubtotalVta != 0){
       var str = $("#frmProduccion").serialize();
        $.ajax
		({
            url: 'sql/produccion.php',
            type: 'post',
            data: str+"&txtAccion="+accion,
            beforeSend: function(){
                $("#mensaje1").html("<p align='center'><img src='images/loading.gif' /></p>");
            },
            success: function(data)
			{
                if(data.trim()=="<div class='alert alert-success'><p>Registro guardado correctamente.</p></div>"){
                    console.log(numeroOrden);
                    alertify.success(data);
                    generarPDF_ordenesProduccion(0,numeroOrden);
                }else{
                    console.log(data);
                    alertify.error(data);
                }
			   

				//listarMesas();
            }
        });
    }
	else
	{
        alert ('Total a pagar deber ser mayor que 0.');
        //document.getElementById("cmbFormaPagoFP").focus();
        //dml.elements['cmbFormaPagoFP'].focus();
    }
    
}
   const generarPDF_ordenesProduccion=(id_produccion, numeroProduccion)=>{
        
          miUrl = "reportes/rptOrdenesProduccion.php?idProduccion="+id_produccion+"&numeroProduccion="+numeroProduccion;
         window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    }
// function guardarProduccion(accion){ 
// 	//txtSubtotalFVC
//     //var SubtotalVta = document.frmEgresos['txtTotalFVC'].value;
// 	var SubtotalVta = document.frmProduccion['txtSubtotalFVC'].value;	

//     if(SubtotalVta != 0){
//       var str = $("#frmProduccion").serialize();
//         $.ajax
// 		({
//             url: 'sql/produccion.php',
//             type: 'post',
//             data: str+"&txtAccion="+accion,
//             beforeSend: function(){
//                 $("#mensaje1").html("<p align='center'><img src='images/loading.gif' /></p>");
//             },
//             success: function(data)
// 			{
// 			    console.log(data);
// 				alertify.success(data);
// 				listarMesas();
//             }
//         });
//     }
// 	else
// 	{
//         alert ('Total a pagar deber ser mayor que 0.');
//         //document.getElementById("cmbFormaPagoFP").focus();
//         //dml.elements['cmbFormaPagoFP'].focus();
//     }
    
// }

function lookup_produccion(txtCuenta, cont, accion) 
{
// retorna en asientosContables.php;
//	var str = $("#frmPedidoCondominios").serialize();
	//var str = $("#frmFacturaVentaCondominios").serialize();
    if(txtCuenta.length == 0) 
	{        
        // Hide the suggestion box.
        $('#suggestions1').hide();
    } 
	else 
	{    
		//alert("antes de factura");
		$.post("sql/produccion.php", {queryString: txtCuenta, aux: cont, txtAccion: accion }, function(data)
		{
			if(data.length > 5)
			{	//le puse 5 xq aunq no haya datos me retorna 3 en data.length  
				       
//alert("entro");					   
				arrayPrincipal = data.split("î");//  dividivos el vector principal con el caracter: î
	//			alert(arrayPrincipal[1]);
				array = arrayPrincipal[1].split("*");// * dividivos el vector por el numero de filas que arroga la consulta
				//array = data.split("*");// * dividivos el vector por el numero de filas que arroga la consulta

				limite = array.length;
				//datos = array[1].split("?");
				
				//contFilas = $('#txtContadorFilas').val();
				//contFilas = $('#txtContadorFilasFVC').val(datos[1]);
				//contFilas = val(datos[0]).split(" ")
				//alert("fila"+contFilas)
				//contFilas = val(datos[1]).split(" ")
				//contFilas =val(datos[0]);
				//alert("fila"+contFilas)							

				// ELIMINA FILAS DEPENDIENDO DEL CONTADOR DE LA CONSULTA
				contFilas=5;
				for(c=1;c<=contFilas;c++){
					//eliminaFilas();
				}
					
			//	contFilas1=8;
			//	contador = 1;
			//	document.getElementById('txtContadorFilasFVC').value = contador;
				// AGREGA FILAS DEPENDIENDO DEL CONTADOR DE LA CONSULTA
				//for(c=1;c<=limite-1;c++)

				contFilas1=5
			 	for(c=1;c<=contFilas1-1;c++)
				{
					limpiarFilasEgresos(c);
				}
			 	// AGREGA LOS DATOS A LOS TXT
			 
		//datos = array[1].split("?");
			/* alert val(datos[0]);
			alert val(datos[1]);
			alert val(datos[2]);
			alert val(datos[3]);
			alert val(datos[4]);
			alert val(datos[5]);
			alert val(datos[6]);
			alert val(datos[7]); */
			
			//	alert("limite"+limite);
				for(i=1; i<=limite-1; i++)
				{
					datos = array[i].split("?");
					//cuenta desde 0
					fecha = datos[3].split(" ");//solo cojemos la fecha, no la hora
					
					$('#textFechaFVC').val(fecha[3]);
					
					$('#txtDescripcion').val(datos[4]);
				//	if (val(datos[5]) !="")
				//	{
					$('#txtIdServicio'+i).val(datos[5]);
					$('#txtCodigoServicio'+i).val(datos[6]);
					$('#txtDescripcionS'+i).val(datos[7]);					
					$('#txtCantidadS'+i).val(datos[8]);
					$('#txtValorUnitarioS'+i).val(datos[9]);
					$('#txtValorTotalS'+i).val(datos[10]);
					$('#txtIdCostoS'+i).val(datos[11]);
					//$('#txtDescripcion').val(datos[11]);					
					    if(document.getElementById('txtBodegasS'+i)){
                        document.getElementById('txtBodegasS'+i).value  =datos[13];
                    }				
					if(document.getElementById('txtLoteS'+i)){
					    let validarD = datos[12];
					    if(validarD.trim()!=''){
					         let selectElement = document.getElementById('txtLoteS'+i);
						let options = selectElement.options;
                        let desiredValue = datos[12];
						let optionExists = false;

						for (let ae = 0; ae < options.length; ae++) {
						  if (options[ae].value === desiredValue) {
						    optionExists = true;
						    break;
						  }
						}
						if (!optionExists) {
						  let newOption = document.createElement('option');
						  newOption.value = desiredValue;
						  newOption.text = datos[14]; // Replace with your desired text
						  selectElement.appendChild(newOption);
						}

 						document.getElementById('txtLoteS'+i).value =datos[12]; 
					    }
					   
 						
                    }
					$('#txtContadorAsientosAgregadosFVC').val(limite-1)
						
					//}					
					
					// para saber cuantas cuentas estan agregadas
				}
			            
           calcular_total_educ();
            
			}
			else
			{
		// alert("No existe esta cuenta.");
			}
		});
    }

} // lookup

function calcular_total_educ(){
    var suma =0;
	
	var dec=0;
    for(i=1;i<=5;i++)
	{
	    de = $("#txtValorTotalS"+i).val();
		//alert(de);
		if(de == "")
		{
            de=0;
        }
        else
        {
        suma = suma + parseFloat(de);  
        }
        
        if (suma > 0)
		{
		debe=document.getElementById('txtSubtotalFVC').value=(suma).toFixed(4);	   
		}
    //txtTotalFVC
	}
	
/* 	dec=parseFloat(document.getElementById('txtTotalFVC').value);
	var ent=Math.floor(dec);
	dec=(dec%1)*10;
		if(dec>0)
			dec=(10-(parseFloat(dec)%1)*10)/100;
		else
			dec=0;
		if(document.getElementById('Redondeo').value>0 ){
				document.getElementById('txtOtrosFVC').value=dec.toFixed(4);
					totales=parseFloat(document.getElementById('txtSubtotalFVC').value)+parseFloat(document.getElementById('txtTotalIvaFVC').value)+parseFloat(dec);
		}else{
		    document.getElementById('txtOtrosFVC').value=0.00;
		   	totales=parseFloat(document.getElementById('txtSubtotalFVC').value)+parseFloat(document.getElementById('txtTotalIvaFVC').value);
		}
			
		document.getElementById('txtTotalFVC').value=totales.toFixed(2);
 */
}


function pasarInventario(accion)
{ 
	//txtSubtotalFVC
    //var SubtotalVta = document.frmEgresos['txtTotalFVC'].value;
	var SubtotalVta = document.frmProduccion['txtSubtotalFVC'].value;	

    if(SubtotalVta != 0){
       var str = $("#frmProduccion").serialize();
        $.ajax
		({
            url: 'sql/produccion.php',
            type: 'post',
            data: str+"&txtAccion="+accion,
            beforeSend: function(){
                $("#mensaje1").html("<p align='center'><img src='images/loading.gif' /></p>");
            },
            success: function(data)
			{
				//alert(data);
                //alert(data.length);
                document.getElementById("mensajeEgreso").innerHTML=data;
                if(data.length == 87){
                    //document.getElementById("frmServicios").reset();
                }
                //listar_servicios();
            }
        });
    }
	else
	{
        alert ('Total a pagar deber ser mayor que 0.');
        //document.getElementById("cmbFormaPagoFP").focus();
        //dml.elements['cmbFormaPagoFP'].focus();
    }
    
}



function guardarPedido(accion){ 
	//txtSubtotalFVC
    //var SubtotalVta = document.frmEgresos['txtTotalFVC'].value;
// 	var SubtotalVta = document.frmProduccion['txtSubtotalFVC'].value;
		var SubtotalVta = document.frmFacturaVentaCondominios['txtSubtotalFVC'].value;
	

    if(SubtotalVta != 0){
       var str = $("#frmFacturaVentaCondominios").serialize();
        $.ajax
		({
            url: 'sql/pedidos.php',
            type: 'post',
            data: str+"&txtAccion="+accion,
            beforeSend: function(){
                $("#mensaje1").html("<p align='center'><img src='images/loading.gif' /></p>");
            },
            success: function(data)
			{
			    console.log(data);
				alertify.success(data);
				listarMesas();
            }
        });
    }
	else
	{
        alert ('Total a pagar deber ser mayor que 0.');
        //document.getElementById("cmbFormaPagoFP").focus();
        //dml.elements['cmbFormaPagoFP'].focus();
    }
    
}




function lookup_pedido(txtCuenta, cont, accion) {
    if(txtCuenta.length == 0) 
	{        
            // Hide the suggestion box.
            $('#suggestions1').hide();
    } 
	else 
	{    
		$.post("sql/Pedido_buscar.php", {queryString: ""+txtCuenta+"",aux: cont, txtAccion: accion}, function(data)
		{
           
			if(data.length > 5)
			{	//le puse 5 xq aunq no haya datos me retorna 3 en data.length                 
				arrayPrincipal = data.split("î");//  dividivos el vector principal con el caracter: î
				//alert(arrayPrincipal[1]);
				array = arrayPrincipal[1].split("*");// * dividivos el vector por el numero de filas que arroga la consulta
				//array = data.split("*");// * dividivos el vector por el numero de filas que arroga la consulta
				limite = array.length;
				
				EliminarFilas();
				//contFilas = $('#txtContadorFilas').val();
				//contFilas = $('#txtContadorFilasFVC').val();
				contFilas = limite;
				contFilas1=limite;

				contador = 1;
				document.getElementById('txtContadorFilasFVC').value = contador;
				// AGREGA FILAS DEPENDIENDO DEL CONTADOR DE LA CONSULTA
				//for(c=1;c<=limite-1;c++)
                
                for(i=1; i<=limite-1; i++)
    				{
    				    AgregarPedidoFilas();
    				}

				for(i=1; i<=limite-1; i++)
				{
					datos = array[i].split("?");
					//cuenta desde 0
					fecha = datos[3].split(" ");//solo cojemos la fecha, no la hora
					
					$('#textFechaFVC').val(fecha[0]);
					$('#idPedido').val(datos[1]);
					$('#txtCedulaFVC').val(datos[4]);
					$('#textIdClienteFVC').val(datos[17]);
					
					$('#txtNombreFVC').val(datos[5]+" "+datos[6]);
					$('#txtTelefonoFVC').val(datos[7]);
 
					$('#txtDireccionFVC').val(datos[8]);
					$('#txtMesa').val(datos[16]);
					
					
					$("#tblBodyProduccion").find('#txtCodigoServicio'+i).val(datos[8]);
				// 		$("#tblBodyProduccion").find('#txtIvaS'+i).val(datos[18]);
					
					$("#tblBodyProduccion").find('#txtIvaS'+i).val(datos[18]);	
					
					$("#tblBodyProduccion").find('#txtDescripcionS'+i).val(datos[9]);					
					$("#tblBodyProduccion").find('#txtCantidadS'+i).val(datos[10]);
					$("#tblBodyProduccion").find('#txtValorUnitarioS'+i).val(datos[11]);
					$("#tblBodyProduccion").find('#txtValorTotalS'+i).val(datos[12]);
					$("#tblBodyProduccion").find('#txtIvaItemS'+i).val(datos[13]);
					$("#tblBodyProduccion").find('#txtTotalItemS'+i).val(datos[14]);				
					$("#tblBodyProduccion").find('#txtContadorAsientosAgregadosFVC').val(limite-1)
					
					
					
					// para saber cuantas cuentas estan agregadas
				}
                        

		}
			else
			{
		// alert("No existe esta cuenta.");
			}
		});
    }

} // lookup

function lookup_pedidoMesa(txtCuenta, cont, accion) {
    if(txtCuenta.length == 0) 
	{        
            // Hide the suggestion box.
            $('#suggestions1').hide();
    } 
	else 
	{    
		$.post("sql/buscarPedidoMesa.php", {queryString: ""+txtCuenta+"",aux: cont, txtAccion: accion}, function(data)
		{

			if(data.length > 5)
			{	//le puse 5 xq aunq no haya datos me retorna 3 en data.length                 
				arrayPrincipal = data.split("î");//  dividivos el vector principal con el caracter: î
				//alert(arrayPrincipal[1]);
				array = arrayPrincipal[1].split("*");// * dividivos el vector por el numero de filas que arroga la consulta
				//array = data.split("*");// * dividivos el vector por el numero de filas que arroga la consulta
				limite = array.length;
				
				EliminarFilas();
				//contFilas = $('#txtContadorFilas').val();
				//contFilas = $('#txtContadorFilasFVC').val();
				contFilas = limite;
				contFilas1=limite;

				contador = 1;
				document.getElementById('txtContadorFilasFVC').value = contador;
				// AGREGA FILAS DEPENDIENDO DEL CONTADOR DE LA CONSULTA
				//for(c=1;c<=limite-1;c++)
                
                for(i=1; i<=limite-1; i++)
    				{
    				    AgregarPedidoFilas();
    				}

				for(i=1; i<=limite-1; i++)
				{
					datos = array[i].split("?");
					//cuenta desde 0
					fecha = datos[3].split(" ");//solo cojemos la fecha, no la hora
					
					$('#idPedido').val(datos[1]);
					$('#textFechaFVC').val(fecha[0]);
					$('#txtCedulaFVC').val(datos[4]);
					
					$('#txtNombreFVC').val(datos[5]+" "+datos[6]);
					$('#txtTelefonoFVC').val(datos[7]);
 
					$('#txtDireccionFVC').val(datos[8]);
					$('#txtNumeroRegistro').val(datos[16]);
					
					
					$("#tblBodyProduccion").find('#txtCodigoServicio'+i).val(datos[8]);
					$("#tblBodyProduccion").find('#txtDescripcionS'+i).val(datos[9]);					
					$("#tblBodyProduccion").find('#txtCantidadS'+i).val(datos[10]);
					$("#tblBodyProduccion").find('#txtValorUnitarioS'+i).val(datos[11]);
					$("#tblBodyProduccion").find('#txtValorTotalS'+i).val(datos[12]);
					$("#tblBodyProduccion").find('#txtIvaItemS'+i).val(datos[13]);
					$("#tblBodyProduccion").find('#txtTotalItemS'+i).val(datos[14]);				
					$("#tblBodyProduccion").find('#txtContadorAsientosAgregadosFVC').val(limite-1)
					
					
					
					// para saber cuantas cuentas estan agregadas
				}
                        

		}
			else
			{
		// alert("No existe esta cuenta.");
			}
		});
    }

} // lookup



function actualizaPedido(accion){ 
	//txtSubtotalFVC
    //var SubtotalVta = document.frmEgresos['txtTotalFVC'].value;
// 	var SubtotalVta = document.frmProduccion['txtSubtotalFVC'].value;
	var SubtotalVta = document.frmFacturaVentaCondominios['txtSubtotalFVC'].value;


    if(SubtotalVta != 0){
       var str = $("#frmFacturaVentaCondominios").serialize();
        $.ajax
		({
            url: 'sql/pedidos.php',
            type: 'post',
            data: str+"&txtAccion="+accion,
            beforeSend: function(){
                $("#mensaje1").html("<p align='center'><img src='images/loading.gif' /></p>");
            },
            success: function(data)
			{
			    console.log(data);
				alertify.success(data);
				listarMesas();
            }
        });
    }
	else
	{
        alert ('Total a pagar deber ser mayor que 0.');
        //document.getElementById("cmbFormaPagoFP").focus();
        //dml.elements['cmbFormaPagoFP'].focus();
    }
    
}



function crearHabitaciones(){
 //PAGINA: productos.php
// alert("nuevo_centrocosto");
	$("#div_oculto").load("ajax/nuevaHabitacion.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
			css:{
			 '-webkit-border-radius': '3px',
                '-moz-border-radius': '3px',
                'box-shadow': '6px 6px 20px gray',
				top: '10%',
				left: '10%',
				width:'75%',
                position: 'absolute'
			
			}
		});
		listarHabitacionesMesas();
	});
}


function nuevaAreaProduccion(){
	//PAGINA: productos.php
   // alert("nuevo_centrocosto");
	   $("#div_oculto").load("ajax/nuevaAreaProduccion.php", function(){
		   $.blockUI({
			   message: $('#div_oculto'),
		   overlayCSS: {backgroundColor: 'white'},
			   css:{
				'-webkit-border-radius': '3px',
				   '-moz-border-radius': '3px',
				   'box-shadow': '6px 6px 20px gray',
				   top: '10%',
				   left: '5%',
				   width:'90%',
				   position: 'absolute'
			   
			   }
		   });
		   listarAreasProduccion();
	   });
   }

   function listarAreasProduccion(){
	   var str = $("#form").serialize();
	   $.ajax
	   ({
			   url: 'ajax/listarAreasProduccion.php',
			   type: 'get',
			   data: str,
			   success: function(data){
				   $("#div_listar_areas").html(data);
			   }
	   });
   }

   function agregarAreaProduccion()
{
    
	nFilasCentro = 0;
    numFilas1 = document.getElementById('txtNumeroFila').value;
    nFilasCentro = numFilas1;
    nFilasCentro++;
    cadena = "<tr>";
    
   // cadena = cadena + "<td width='5%'><input type='hidden' id='txtNumeroFila' name='txtNumeroFila' value='"+nFilasCentro+"' class='form-control' /></td>";

    cadena = cadena + "<td width='10%'><input style='width: 100%' type='text' id='txtTipo"+nFilasCentro+"' name='txtTipo"+nFilasCentro+"' class='form-control' /></td>";

    cadena = cadena + "<td width='30%'><select style='width: 100%' name='cmbAreas"+nFilasCentro+"' id='cmbAreas"+nFilasCentro+"' class='form-control' ><option>Area1</option><option>Area2</option></select></td> ";
    cadena = cadena + "<td><a title='Guardar' href='javascript: guardar_area_produccion("+nFilasCentro+", 1);'><span class='fa fa-check' aria-hidden='true'></span></a></td>";
    cadena = cadena + "<td><a class='eliminaFormaPago' title='Eliminar'><span class='fa fa-erase' aria-hidden='true'></a></td>";
    cadena = cadena + "</tr>";
        
    $("#grillaCentros tbody").append(cadena);
    cantidad_areas();
    // cargarTipoMovimiento(6,nFilasCentro);
}



function cantidad_areas(){
   
    cantidad = $("#grillaCentros tbody").find("tr").length;
    $("#span_centros").html(cantidad);
    document.getElementById('txtNumeroFila').value = cantidad;
}
function modificar_areas( seleccion,valor){

                    $.ajax({
                            url: 'sql/produccion.php',
                            type: 'post',
                            data: "txtAccion=9&selecion="+seleccion+"&valor="+valor,
                            success: function(data){
                                if(data.trim()=='Se actualizo correctamente'){
                                    alertify.success("Registro Actualizado Correctamente");
                                    
                                }else if(data.trim()=='Se guardo correctamente'){
                                    alertify.success("Registro Guardado Correctamente");
                                }else{
									console.log(data);
									alertify.error("No se guardo.");
								}
                                    
                                  
                                    
                            }
                    });
}