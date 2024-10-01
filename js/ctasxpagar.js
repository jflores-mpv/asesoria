//******************************************** CUENTAS POR COBRAR *********************************
//listar_reportes_cuentas_por_cobrar
function listar_cuentas_por_pagar(page=1){
  var str = $("#frmCuentasPagar").serialize();
console.log("estado",str);
$.ajax({
  url: 'ajax/listarCuentasPagar.php?page='+page,
      type: 'get',
      data: str,
      success: function(data){
      $("#div_listar_cuentas_por_pagar").html(data);
      }
});		
}

function listar_reportes_cuentas_por_pagar(){
    //PAGINA: cuentasPorCobrar.php
//    alert("entro");
//	alert(opcion);
    var str = $("#frmReportesCuentasPagar").serialize();
    $.ajax
	({
        url: 'ajax/listarReportesCuentasPagar.php',
        type: 'get',
        data: str,
        success: function(data){
        $("#div_listar_reportes_cuentas_pagar").html(data);
        //cantidad_formas_pago();
        }
    });
}


function pagarCuentasPagar(id_cuenta_por_pagar){
    // funcion pagar cuota pagina: prestamos.php
//	alert(id_cuenta_por_pagar);
    $("#div_oculto").load("ajax/pagarCuentaPagar.php",{id_cuenta_por_pagar:id_cuenta_por_pagar}, function(){
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
    });
    cargarFormasPago(5);
}


function guardar_pago_cuenta_pagar(accion)
{
   // alert("guardar-pago-proveedor");
    valorPagado = document.getElementById("txtSubtotalFVC").value;
    pagoMin = 1;
    tSaldo = document.getElementById("txtDeudaTotal").value;
   
    resp = pagoMinimo(valorPagado, pagoMin, tSaldo);//retorna 1 o 0
	//alert("resp");
    //alert(resp);
    //formaCobro = document.getElementById("cmbFormaPagoFP").value;
    //alert(formaCobro);
    if(resp == 0){
       // if(formaCobro != 0){   
            //            frmPagarCuentaPagar
            var str = $("#frmPagarCuentaPagar").serialize(); 
		//	alert("antes de ingresar a sql");
            $.ajax({
                url: 'sql/cuentas_por_pagar.php',
                type: 'post',
                data: str+"&txtAccion="+accion,
                // para mostrar el loadian antes de cargar los datos
                beforeSend: function(){
                    //imagen de carga
                    $("#mensajePagarCuentaPagar").html("<p align='center'><img src='images/loading.gif' /></p>");
                },
                success: function(data)
				{            
				    alertify.success(data);
                  //  document.getElementById("mensajePagarCuentaPagar").innerHTML=data;
                  fn_cerrar();
                    if(data.length === 93){
                        document.getElementById("submitPCC").style.visibility="hidden"; 
                    }
                 //   listar_cuentas_por_cobrar();
                }
            });
            
      //  }else{
         //   alert ('Seleccione Forma de cobro.');
        //    document.getElementById("cmbFormaPagoFP").focus();                      
       // }
        
    }else{
        alert ('No se puede guardar porque el valor ingresado es incorrecto.');
        document.getElementById("txtValor").focus();
        document.getElementById("txtValor").value="";
        document.getElementById("validaPagoMin").innerHTML="";
    }
}


function registrarCtasxPagar(){
    // funcion pagar cuota pagina: prestamos.php
	//alert("ciemtass");
	 var str = $("#frmCuentasPagar").serialize();
    $("#div_oculto").load("ajax/registrarCuentaPagar.php?"+str, function(){
        $.blockUI({
                message: $('#div_oculto'),
        overlayCSS: {backgroundColor: '#111'},
                css:{

        '-webkit-border-radius': '10px',
    '-moz-border-radius': '10px',

                    background: '',
                    top: '5%',
                    width:'60%',
                    position: 'absolute',
                 //   left: ($(window).width() - $('.caja').outerWidth())/2
                 left:'20%'
                }
        });
    });
    cargarFormasPago(5);

}


//guardar_cuenta_pagar
function guardar_cuenta_pagar(accion)
{
	//PAGINA: nuevaFacturaCompra.php
    var idProveedor = document.frmRegistrarCuentaPagar['cmbProveedor'].value;
	//alert(idProveedor);
	
    var importe1 = document.frmRegistrarCuentaPagar['txtTotal'].value;
	//alert(importe);
	var importe2=1;
//	alert('guardar ctaxcpnrar');
    
	if(idProveedor != "")	
	{
	    if(importe2 != "")
		{
           var str = $("#frmRegistrarCuentaPagar").serialize();
		//   alert(str);
           $.ajax(
		   {
			  url: 'sql/cuentas_por_pagar.php',
              data: str+"&txtAccion="+accion,
              type: 'post',
              success: function(data)
		      {
				 //alert(data);
                 document.getElementById("mensajePagarCuentaPagar").innerHTML+="<div class='transparent_notice'><p>"+data+"</p></div>";
                        //document.getElementById("form3").reset();                        
            //            alert(data);
                //document.frmRegistrarCuentaPagar.submit.disabled=true;
                        //document.getElementById("btnFacturar").disabled=false;
               }
		   })
		}
		else{
			
		}
	}
}
 
 

 function pagarCtasPagar_orig(id_proveedor){
    // funcion pagar cuota pagina: prestamos.php
//	alert('aaaaaaaaaaa');
//	alert(id_cuenta_por_pagar);
    $("#div_oculto").load("ajax/pagarCuentaPagar_v.php",{id_proveedor:id_proveedor}, function(){
        $.blockUI({
             message: $('#div_oculto'),
        overlayCSS: {backgroundColor: '#111'},
                css:{

             '-webkit-border-radius': '3px',
                '-moz-border-radius': '3px',
                'box-shadow': '6px 6px 20px gray',
				top: '5%',
				left: '15%',
				width: '60%',
                position: 'absolute'
                }
        });
    });
    cargarFormasPago(5);

}


var total=0;
 
 function sumar(miCheckbox) 
{

    var cliente = miCheckbox.getAttribute('data-cliente');

    if(cliente_selecionado != cliente){
        deseleccionar(cliente_selecionado);
        cliente_selecionado=cliente;
    }

	total = sumarSaldoCliente(cliente);
	document.getElementById('total_a_pagar').value=(total).toFixed(2);
}
function sumarSaldoCliente(cliente) {
    var checkboxes = document.querySelectorAll('input[type="checkbox"][data-cliente="' + cliente + '"]');
    var totalSaldo = 0;

    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            var saldo = parseFloat(checkboxes[i].getAttribute('data-saldo'));
            totalSaldo += saldo;
        }
    }

    return totalSaldo;
}
function deseleccionar(id_cliente_anterior){
    var checkboxes = document.querySelectorAll('input[type="checkbox"][data-cliente="'+id_cliente_anterior+'"]');

for (var i = 0; i < checkboxes.length; i++) {
    checkboxes[i].checked = false;
}
}
// function sumar(valor) {
// 	//alert("aaaa1111");
// 	total += valor; 
// 	//alert(total);
    
//     //document.formulario.total.value=total;
// 	//frmCuentasPagar
// 	//document.frmCuentasPagar.total_a_pagar.value=total;
// 	document.getElementById('total_a_pagar').value=(total).toFixed(2);
//     //alert(total);
        
//   //document.getElementById("total_a_pagar").value=total;
//  //	document.frmCuentasPagar.txt_total1.value=total;
// }
 
// function restar(valor) {
// //	alert(valor);
//     total-=valor; 
// //	alert(total);
    
// //   document.formulario.total.value=total;
// //frmCuentasPagar
// 	//document.frmCuentasPagar.total_a_pagar.value=total;
// 	document.getElementById('total_a_pagar').value=(total).toFixed(2);

// //document.frmCuentasPagar.txt_total1.value=total;
//     //alert(total);
    
//     }


function sumar2(objeto,valor) {
	//alert("aaaakkkkk");
	//alert(valor);
   // total += valor; 
    //document.formulario.total.value=total;
	//document.frmCuentasPagar.total_a_pagar.value=total;
  //document.getElementById("total_a_pagar").value=total;
 //	document.frmCuentasPagar.txt_total1.value=total;
}
  var total=0;
 var cliente_selecionado = '';
  function pagarCtasPagar(id_proveedor,tipox,tipoCuenta){
  
      var total_a_pagarx = document.getElementById("total_a_pagar").value;
  
      if(id_proveedor!=cliente_selecionado){
        var checkboxes = document.querySelectorAll('input[type="checkbox"][data-cliente="'+cliente_selecionado+'"]');

        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = false;
        }
        total_a_pagarx=0;
        document.getElementById("total_a_pagar").value=0;
    }

      if(total_a_pagarx > 0){
          tipox = 2;
      }else {
           tipox = 1;
      }
  
      $("#div_oculto").load("ajax/pagarCuentaPagar_v.php",{id_proveedor:id_proveedor,tipox:tipox,total_a_pagar:total_a_pagarx,tipoCuenta:tipoCuenta}, function(){
          $.blockUI({
               message: $('#div_oculto'),
              overlayCSS: {backgroundColor: '#111'},
                  css:{
               '-webkit-border-radius': '3px',
                  '-moz-border-radius': '3px',
                  'box-shadow': '6px 6px 20px gray',
                  top: '5%',
                  left: '15%',
                  width: '80%',
                  position: 'absolute'
                  }
          });
      });
  }
// function pagarCtasPagar(id_proveedor,tipox,tipoCuenta){

// 	var total_a_pagarx = document.getElementById("total_a_pagar").value;

// 	if(total_a_pagarx > 0){
// 	    tipox = 2;
// 	}else {
// 	     tipox = 1;
// 	}

//     $("#div_oculto").load("ajax/pagarCuentaPagar_v.php",{id_proveedor:id_proveedor,tipox:tipox,total_a_pagar:total_a_pagarx,tipoCuenta:tipoCuenta}, function(){
//         $.blockUI({
//              message: $('#div_oculto'),
// 			overlayCSS: {backgroundColor: '#111'},
//                 css:{
//              '-webkit-border-radius': '3px',
//                 '-moz-border-radius': '3px',
//                 'box-shadow': '6px 6px 20px gray',
// 				top: '5%',
// 				left: '15%',
// 				width: '80%',
//                 position: 'absolute'
//                 }
//         });
//     });
// }


function AgregarFilas_FP_Proveed(contador_fp)
{   
	contador1=contador_fp;
	//alert("agregar filkkk");
	cadena = "";
    cadena = cadena + "<div class='row'>";
    cadena = cadena + "<div class='col-lg-1'><a onclick=\"limpiarFilas_FP_Cpras("+contador1+");\" title='Limpiar fila'><i class='fa fa-times' aria-hidden='true'></i></a>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIdIvaS"+contador1+"' name='txtIdIvaS"+contador1+"'  readonly='readonly'>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIvaS"+contador1+"' name='txtIvaS"+contador1+"'  readonly='readonly'> </div>";
    cadena = cadena + "<div class='col-lg-2 border'><input  style='margin: 0px; width: 100%;' type='search' id='txtCodigoP"+contador1+"' name='txtCodigoP"+contador1+"' class='form-control border-0'   autocomplete='off'  placeholder='Buscar Forma...' onclick='lookup_FP_Cpra(this.value, "+contador1+", 4);' onKeyUp='lookup_FP_Cpra(this.value, "+contador1+", 4);' />  <div class='suggestionsBox' id='suggestions20"+contador1+"' style='display: none; margin-top: 0px; '> <div class='suggestionList' id='autoSuggestionsList20"+contador1+"'>  </div> </div>  </div>";
    cadena = cadena + "<div class='col-lg-3 border'><input type='search' style='margin: 0px; width: 100%;'class='form-control border-0' autocomplete='off'  id='txtDescripcionP"+contador1+"'  name='txtDescripcionP"+contador1+"'  value=''  >      </div> ";
    cadena = cadena + "<div class='col-lg-1 border' hidden=''> <input type='text' maxlength='20' id='txtTipoP"+contador1+"' name='txtTipoP"+contador1+"' style='margin: 0px; width: 100%; text-align: right; ' class='form-control border-0'   autocomplete='off' > </div>";

  //  cadena = cadena + "<div class='col-lg-1 border'> <input type='text' maxlength='10' id='nrocpteC"+contador1+"' name='nrocpteC"+contador1+"' style='margin: 0px; width: 100%; text-align: right; ' class='form-control border-0'   autocomplete='off' > </div>";    
    cadena = cadena + "<div class='col-lg-2 border '><input type='text' style='margin: 0px; width: 100%; text-align: right; ' class='form-control border-0 bg-white' id='txtPorcentajeS"+contador1+"' name='txtPorcentajeS"+contador1+"' readonly='readonly' onKeyUp=\"calculoTotal_FP_Cpras("+contador1+")\" onclick=\"calculoTotal_FP_Cpras("+contador1+")\" autocomplete='off'  ></div>";
	cadena = cadena + "<div class='col-lg-2 border'> <input type='text' maxlength='10' id='txtValorS"+contador1+"' name='txtValorS"+contador1+"' style='margin: 0px; width: 100%; text-align: right; ' class='form-control border-0' onKeyUp=\"calculoTotal_FP_Proveed("+contador1+")\" onclick=\"calculoTotal_FP_Proveed("+contador1+")\"  autocomplete='off' > </div>";
    cadena = cadena + "<div class='col-lg-1 border' hidden=''> <input type='hidden' maxlength='3' id='txtCuentaS"+contador1+"' name='txtCuentaS"+contador1+"' style='margin: 0px; width: 100%; text-align: right; ' class='form-control border-0'   autocomplete='off' > </div>";
	cadena = cadena + "</div>";
    //document.getElementById('txtContadorFilasFVC').value=contador;
    contador1++;
	
    $("#tablita1 ").append(cadena);
	//value="+fechaAcual+"
} 

function calculoTotal_FP_Proveed(con){
   // console.log("con",con);
	var j=0;
	j=con;
	valorTotal = $("#txtValorS"+j).val();
	calculoTotal_FP_Proveed1()  
}

function calculoTotal_FP_Proveed1(){
    var sumaValorTotal = 0;
    var valorTotal=0;
	var contador=5;
    for(i=1;i<=contador;i++)	
	{
	//	alert("estoy en el for");		
        valorTotal = $("#txtValorS"+i).val();
	//	alert(valorTotal);
        if(valorTotal == "")
			{valorTotal=0;}
        else
			{sumaValorTotal = sumaValorTotal + parseFloat(valorTotal); }
	//	alert(valorTotal);
    }
    if(sumaValorTotal>0)
	{
     // document.getElementById('txtSubtotalFVC').value=(sumaValorTotal).toFixed(2);
        document.getElementById('txtPagoFP').value=(sumaValorTotal).toFixed(2);
        var debe1=document.getElementById('txtDeudaTotal').value;
        var cambio1=parseFloat(debe1)-parseFloat(sumaValorTotal);
        document.getElementById('txtCambioFP').value=(cambio1).toFixed(2);
    }
}
function comprobanteDiario_cuentasPagar(numero,fecha){

miUrl = "reportes/rptComprobanteDiario.php?txtComprobanteNumero="+numero+"&fecha_desde="+fecha+"&fecha_hasta="+fecha;

 window.open(miUrl,'noimporta','width=600, height=500, scrollbars=NO, titlebar=no');
}

function guardar_pago_cuenta_pagarV(accion, tipox, tipoCuenta) {
    if (document.getElementById("txtCambioFP")) {
        let vuelto = parseFloat(document.getElementById("txtCambioFP").value);
        if (vuelto < 0) {
            alertify.error('La cantidad a pagar es mayor que el total.');
            return;
        }
    }

    tipox = tipox.value;
    tipoCuenta = tipoCuenta.value;

    // Utilizando la sintaxis de template literals para mejorar la legibilidad
    // y eliminando el código comentado no necesario.

    $.ajax({
        url: 'sql/cuentas_por_pagarV.php',
        type: 'post',
        data: $("#frmCuentasPagar,#frmPagarCuentaPagarV").serialize() + "&txtAccion=" + accion + "&tipox=" + tipox + "&tipoCuenta=" + tipoCuenta,
        beforeSend: function () {
            $("#mensajePagarCuentaPagar").html("<p align='center'><img src='images/loading.gif' /></p>");
        },
        success: function (data) {
            try {
                var datos = JSON.parse(data);

                alertify.success(datos.mensajes);
                fn_cerrar();
                
                if (datos.mensajes.length === 93) {
                    document.getElementById("submitPCC").style.visibility = "hidden";
                }

                listar_cuentas_por_pagar();
                document.getElementById("total_a_pagar").value = "";
                comprobanteDiario_cuentasPagar(datos.numero_asiento, datos.fecha);
            } catch (error) {
                console.error(data);
            }
        }
    });
}

// Otras funciones de utilidad pueden ir aquí...


function formularioAbonos(id,lead){

	$("#div_oculto").load('ajax/editar_abonos.php?id='+id+'&lead='+lead, function(){

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
	});
}    


function guardar_abono(accion,lead){
	var str = $("#frmAbono").serialize();
	var fechaVencimiento = document.getElementById("frmAbono").fechaVencimiento.value; 
	var fechaIngreso = document.getElementById("frmAbono").fechaIngreso.value; 
	
	var str2 = str+"&fechaIngreso="+fechaIngreso+"&fechaVencimiento="+fechaVencimiento;   
	console.log(lead);
	$.ajax({
		url: 'sql/cuentas_por_pagarV.php',
		data: str2+'&txtAccion='+accion+'&lead='+lead,
		type: 'post',
		success: function(data){
			console.log(data);
			if(data='1'){
				alertify.success("Se guardo correctamente el abono");
				abonos(lead);
                fn_cerrar();
			}else{
				alertify.error("No se pudo guardar el abono");
			}

		}
	});
}

function abonos(id){
	console.log(id);
	$.ajax({
		url: 'ajax/listarAbono.php',
		type: 'post',
		data: "&id="+id,
		success: function(data){
			$("#listadoAbonos").html(data);

		}
	});
}
function modificarAbonos(id,lead){

	$("#div_oculto").load('ajax/modificarAbono.php?cuenta='+id+'&lead='+lead, function(){

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
	});
}    
function modificar_abono(accion,cuenta,lead){
	var str = $("#frmAbono").serialize();
	var fechaVencimiento = document.getElementById("frmAbono").fechaVencimiento.value; 
	var fechaIngreso = document.getElementById("frmAbono").fechaIngreso.value; 
	
	var str2 = str+"&fechaIngreso="+fechaIngreso+"&fechaVencimiento="+fechaVencimiento;   
	// console.log(lead);
	$.ajax({
		url: 'sql/cuentas_por_pagarV.php',
		data: str2+'&txtAccion='+accion+'&cuenta='+cuenta,
		type: 'post',
		success: function(data){
			console.log(data);
			if(data='1'){
				alertify.success("Abonos actualizados");
                abonos(lead);
                fn_cerrar();
			}else{
				alertify.error("Abonos no actualizado");
			}

		}
	});
}
function pdfAbono(id_cuenta_por_cobrar){

    miUrl = "reportes/rptAbono.php?id_cuenta_por_cobrar="+id_cuenta_por_cobrar;
    window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
}
