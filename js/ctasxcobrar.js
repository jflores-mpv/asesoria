//eliminarCtasxCobrar
function eliminarCtasxCobrar(cuentas_por_cobrar_id_cuenta_por_cobrar, accion){
alert("eliminar");
alert(cuentas_por_cobrar_id_cuenta_por_cobrar);
alert(accion); 
var respuesta12 = confirm("Seguro desea eliminar este cta x cobrar?");
    if (respuesta12){
        $.ajax({
                url: 'sql/cuentas_por_cobrar.php',
                data: 'id_cuenta_por_cobrar='+cuentas_por_cobrar_id_cuenta_por_cobrar+'&txtAccion='+accion,
                type: 'post',
                success: function(data){
                        if(data!="")
                               //alert(data);
                        listar_cuentas_por_cobrar();
                }
        });
    }
} 


function registrarCtasxCobrar(){
    // funcion pagar cuota pagina: prestamos.php
//	alert("cUENTRAS X COBRARiemtass");
 var str = $("#frmCuentasCobrar").serialize();
    $("#div_oculto").load("ajax/registrarCuentaCobrar.php?"+str, function()
	{
        $.blockUI(
		{
            message: $('#div_oculto'),
			overlayCSS: {backgroundColor: '#111'},
            css:
			{
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


// function guardar_cuenta_cobrar(accion)
// {
// 	//PAGINA: nuevaFacturaCompra.php
//     var idCliente = document.frmRegistrarCuentaCobrar['cmbCliente'].value;
// //	alert("idCliente");
	
//     var importe1 = document.frmRegistrarCuentaCobrar['txtTotal'].value;
// 	//alert(importe);
// 	var importe2=1;
// 	//alert('guardar ctaxcpnrar');
	
// 	if(idCliente != "")	
// 	{
// 	    if(importe2 != "")
// 		{
//           var str = $("#frmRegistrarCuentaCobrar").serialize();
// 		//   alert(str);
//           $.ajax(
// 		   {
// 			  url: 'sql/cuentas_por_cobrar.php',
//               data: str+"&txtAccion="+accion,
//               type: 'post',
//               success: function(data)
// 		      {
// 				 //alert(data);
//                  document.getElementById("mensajeCuentaCobrar").innerHTML+="<div class='transparent_notice'><p>"+data+"</p></div>";
//                  listar_cuentas_por_cobrar(1);
//                         document.getElementById("form3").reset();     
//                         fn_cerrar();
//             //            alert(data);
//                 //document.frmRegistrarCuentaPagar.submit.disabled=true;
//                         //document.getElementById("btnFacturar").disabled=false;
//               }
// 		   })
// 		}
// 		else{
			
// 		}
// 	}
	
// }

function guardar_cuenta_cobrar(accion)
{
	//PAGINA: nuevaFacturaCompra.php
    var idCliente = document.frmRegistrarCuentaCobrar['cmbProveedor'].value;
//	alert("idCliente");
	
    var importe1 = document.frmRegistrarCuentaCobrar['txtTotal'].value;
	//alert(importe);
	var importe2=1;
	//alert('guardar ctaxcpnrar');
	
	if(idCliente != "")	
	{
	    if(importe2 != "")
		{
           var str = $("#frmRegistrarCuentaCobrar").serialize();
		//   alert(str);
           $.ajax(
		   {
			  url: 'sql/cuentas_por_cobrar.php',
              data: str+"&txtAccion="+accion,
              type: 'post',
              success: function(data)
		      {
		          try {
     let response = JSON.parse(data);
 
                          if(response.tipo_comprobante=='Egreso'){
                            //   imprimirAnticipo(response.numero_comprobante);
                              if (confirm('Desea imprimir el Comprobante?')) {
                                  let tipoc = document.getElementById("seleccion").value;
                                      miUrl = "reportes/rptComprobanteEgreso.php?txtComprobanteNumero="+response.numero_comprobante+"&tC="+tipoc+"&idC="+idCliente;
                                      window.open(miUrl,'noimporta','width=600, height=500, scrollbars=NO, titlebar=no');
                                        
                                }
                           } 
                 if(response.guardo=='si'){
                     document.getElementById("mensajeCuentaCobrar").innerHTML+="<div class='transparent_notice'><p>"+response.mensaje+"</p></div>";
                 }  
                 if(document.getElementById("frmRegistrarCuentaCobrar")){
                     document.getElementById("frmRegistrarCuentaCobrar").reset();
                 }
} catch (error) {
 console.error(error);
}
		                       

               }
		   })
		}
		else{
			
		}
	}
	
}

 var contador1=1;
 function AgregarFilas_cxc(){
   
	cadena = "";
    cadena = cadena + "<div class='row bg-white border p-3'>";
    cadena = cadena + "<div class='col-lg-1'><a onclick=\"limpiarFilas_FP_Cpras("+contador1+");\" title='Limpiar fila'><i class='fa fa-times' aria-hidden='true'></i></a>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIdIvaS"+contador1+"' name='txtIdIvaS"+contador1+"'  readonly='readonly'>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIvaS"+contador1+"' name='txtIvaS"+contador1+"'  readonly='readonly'> </div>";
    cadena = cadena + "<div class='col-lg-2 border'><input  style='margin: 0px; width: 100%;' type='search' id='txtCodigoP"+contador1+"' name='txtCodigoP"+contador1+"' class='form-control border-0'   autocomplete='off'  placeholder='Buscar Forma...' onclick='lookup_FP_Cpra(this.value, "+contador1+", 4);' onKeyUp='lookup_FP_Cpra(this.value, "+contador1+", 4);' />  <div class='suggestionsBox' id='suggestions20"+contador1+"' style='display: none; margin-top: 0px; '> <div class='suggestionList' id='autoSuggestionsList20"+contador1+"'>  </div> </div>  </div>";
    cadena = cadena + "<div class='col-lg-4 border'><input type='search' style='margin: 0px; width: 100%;'class='form-control border-0' autocomplete='off'  id='txtDescripcionP"+contador1+"'  name='txtDescripcionP"+contador1+"'  value=''  >      </div> ";
    cadena = cadena + "<div class='col-lg-1 border' hidden=''> <input type='text' maxlength='10' id='txtTipoP"+contador1+"' name='txtTipoP"+contador1+"' style='margin: 0px; width: 100%; text-align: right; ' class='form-control border-0'   autocomplete='off' > </div>";
    cadena = cadena + "<div class='col-lg-1 border'> <input type='text' maxlength='10' id='nrocpteC"+contador1+"' name='nrocpteC"+contador1+"' style='margin: 0px; width: 100%; text-align: right; ' class='form-control border-0'   autocomplete='off' > </div>";    
    cadena = cadena + "<div class='col-lg-1 border '><input type='text' style='margin: 0px; width: 100%; text-align: right; ' class='form-control border-0 bg-white' id='txtPorcentajeS"+contador1+"' name='txtPorcentajeS"+contador1+"' readonly='readonly' onKeyUp=\"calculoTotal_FP_Cpras("+contador1+")\" onclick=\"calculoTotal_FP_Cpras("+contador1+")\" autocomplete='off'  ></div>";
	cadena = cadena + "<div class='col-lg-2 border'> <input type='text' maxlength='10' id='txtValorS"+contador1+"' name='txtValorS"+contador1+"' style='margin: 0px; width: 100%; text-align: right; ' class='form-control border-0' onKeyUp=\"calculoTotal_FP_Cpras("+contador1+")\" onclick=\"calculoTotal_FP_Cpras("+contador1+")\"  autocomplete='off' > </div>";
    cadena = cadena + "</div>";
    
    contador1++;
	
    $("#formascxc").append(cadena);

} 


function guardar_pago_cuenta_cobrar(accion){
   
    valorPagado = document.getElementById("txtSubtotalVta").value;
    pagoMin = 1;
    tSaldo = document.getElementById("txtDeudaTotal").value;
   
    resp = pagoMinimo(valorPagado, pagoMin, tSaldo);//retorna 1 o 0   
    
  //  formaCobro = document.getElementById("cmbFormaPagoFP").value;
    
 //   alert (formaCobro);
    if(resp == 0){
        //if(formaCobro != 0){   
            
            var str = $("#frmPagarCuentaCobrar").serialize();     
            $.ajax({
                url: 'sql/cuentas_por_cobrar.php',
                type: 'post',
                data: str+"&txtAccion="+accion,
                // para mostrar el loadian antes de cargar los datos
                beforeSend: function(){
                    //imagen de carga
                    $("#mensajePagarCuentaCobrar").html("<p align='center'><img src='images/loading.gif' /></p>");
                },
                success: function(data){            
                    document.getElementById("mensajePagarCuentaCobrar").innerHTML=data;
                    if(data.length === 93){
                        document.getElementById("submitPCC").style.visibility="hidden"; 
                    }
                    listar_cuentas_por_cobrar();
                }
            });
            
       // }else{
         //   alert ('Seleccione Forma de cobro.');
           // document.getElementById("cmbFormaPagoFP").focus();                      
       // }
        
    }else{
        alert ('No se puede guardar porque el valor ingresado es incorrecto.');
        document.getElementById("txtValor").focus();
        document.getElementById("txtValor").value="";
        document.getElementById("validaPagoMin").innerHTML="";
    }
}



//******************************************** CUENTAS POR COBRAR *********************************



function listar_cuentas_por_cobrar(page){
   
    //PAGINA: cuentasPorCobrar.php
   console.log("page",page);
    var str = $("#frmCuentasCobrar").serialize();
       console.log("str",str);
    $.ajax({
            url: 'ajax/listarCuentasCobrar.php?page='+page,
            type: 'get',
            data: str,
            success: function(data){
                $("#div_listar_cuentas_por_cobrar").html(data);
                 //cantidad_formas_pago();
            }
    });
}


function listar_reportes_cuentas_por_cobrar(){
    //PAGINA: cuentasPorCobrar.php
    //alert("entro");
    var str = $("#frmReportesCuentasCobrar").serialize();
    $.ajax({
            url: 'ajax/listarReportesCuentasCobrar.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#div_listar_reportes_cuentas_cobrar").html(data);
                 //cantidad_formas_pago();
            }
    });
}

function pagarCuentasCobrar(id_cuenta_por_cobrar){
    // funcion pagar cuota pagina: prestamos.php
    $("#div_oculto").load("ajax/pagarCuentaCobrar.php",{id_cuenta_por_cobrar:id_cuenta_por_cobrar}, function(){
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
}

function pagarCtaCobrar_xx(id_cliente){
    // funcion pagar cuota pagina: prestamos.php
//	alert("entro a jvoooooooo");
//	alert(id_cliente);
    $("#div_oculto").load("ajax/pagarCuentaCobrar_v.php",{id_cliente:id_cliente}, function(){
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
//	cargarFormasPago(5);
}

var total=0;
 var cliente_selecionado = '';

function pagarCtaCobrar(id_cliente,tipox,tipoCuenta){
    // funcion pagar cuota pagina: prestamos.php
	//alert("entro a jvoooooooo");
	//alert(id_cliente);
	var total_a_pagarx = document.getElementById("total_a_pagar").value;
	
	  if(id_cliente!=cliente_selecionado){
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
	console.log("tipox",tipox);
	
    $("#div_oculto").load("ajax/pagarCuentaCobrar_v.php",{id_cliente:id_cliente,tipox:tipox,total_a_pagar:total_a_pagarx,tipoCuenta:tipoCuenta}, function(){
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
//	cargarFormasPago(5);
}

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
 
function restar(box) 
{
    total-=valor; 
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


// var total=0;
 
// function sumar(valor) 
// {
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
 
// function restar(valor) 
// {
// //	alert(valor);
//     total-=valor; 
// //	alert(total);
    
// //   document.formulario.total.value=total;
// //frmCuentasPagar
// 	//document.frmCuentasPagar.total_a_pagar.value=total;
// 	document.getElementById('total_a_pagar').value=(total).toFixed(2);

// //document.frmCuentasPagar.txt_total1.value=total;
//     //alert(total);
    
// }


function guardar_pago_cuenta_cobrarV(accion,tipox){
    
     if(document.getElementById("txtCambioFP")){
        let vuelto = parseFloat(document.getElementById("txtCambioFP").value);
        if(vuelto<0){
            alertify.error('La cantidad a cobrar es mayor que el total.');
            return ;
        }
        
    }
   
    //   valorPagado = document.getElementById("txtSubtotalVta").value;
    //   pagoMin = 1;
    //   tSaldo = document.getElementById("txtDeudaTotal").value;
      
    //   resp = pagoMinimo(valorPagado, pagoMin, tSaldo);//retorna 1 o 0   
       
     //  formaCobro = document.getElementById("cmbFormaPagoFP").value;
       
    //   alert (formaCobro);
    //alert("aaaabbbb");
 
        tipox=tipox.value;
       resp=0;   
       if(resp == 0)
       {
           //if(formaCobro != 0){  
   //frmCobrarCuentaCobrarV		
           var str = $("#frmCuentasCobrar,#frmCobrarCuentaCobrarV").serialize(); 
   //alert(str);			
               $.ajax({
                   url: 'sql/cuentas_por_cobrarV.php',
                   type: 'post',
                   data: str+"&txtAccion="+accion+"&tipox="+tipox,
                   // para mostrar el loadian antes de cargar los datos
                   beforeSend: function(){
                       //imagen de carga
                       $("#mensajePagarCuentaCobrar").html("<p align='center'><img src='images/loading.gif' /></p>");
                   },
                   success: function(data){     
                    let json = JSON.parse(data);
                    console.log(json.mensajes)
                    if(json.mensajesf=='Registro insertado correctamente'){
                        alertify.success('Registro insertado correctamente');
                    }else{
                        alertify.error(json.mensajesf);
                    }
                    let detalles = json.idDetalles.toString();
                    let tipo = json.tipo;
                    let abonado = json.totalAbonado;
                    let pagos = json.pago.toString();
                    pdfAbonoCancelado( detalles,tipo, pagos,1);
                    if (typeof pdfEmailFacturaCuentaCobrar === "function") {
                    pdfEmailFacturaCuentaCobrar( detalles,tipo, pagos,1);
                    }
                    
                    //    document.getElementById("mensajePagarCuentaCobrar").innerHTML=data;
                    //    if(data.length === 93){
                    //        document.getElementById("submitPCC").style.visibility="hidden"; 
                    //    }
                    //    listar_cuentas_por_cobrar();
                    listar_cuentas_por_cobrar(1);
                    fn_cerrar();
                    
                   }
               });
               
          // }else{
            //   alert ('Seleccione Forma de cobro.');
              // document.getElementById("cmbFormaPagoFP").focus();                      
          // }
           
       }else{
           alert ('No se puede guardar porque el valor ingresado es incorrecto.');
           document.getElementById("txtValor").focus();
           document.getElementById("txtValor").value="";
           document.getElementById("validaPagoMin").innerHTML="";
       }
   }

function AgregarFilas_FP_Cliente(contador_fp)
{	
    contador1=contador_fp;
	//alert("agregar filkkk");
	cadena = "";
    cadena = cadena + "<div class='row'>";
//  cadena = cadena + "<div class='col-lg-1'><a onclick=\"limpiarFilas_FP_Cpras("+contador1+");\" title='Limpiar fila'><i class='fa fa-times' aria-hidden='true'></i></a>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIdIvaS"+contador1+"' name='txtIdIvaS"+contador1+"'  readonly='readonly'>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIvaS"+contador1+"' name='txtIvaS"+contador1+"'  readonly='readonly'> </div>";
	cadena = cadena + "<div class='col-lg-1'><a onclick=\"limpiarFilas_FP_Vtas("+contador1+");\" title='Limpiar fila'><span class='btn btn-default btn-sm glyphicon glyphicon-erase' ></span></a>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIdIvaS"+contador1+"' name='txtIdIvaS"+contador1+"'  readonly='readonly'>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIvaS"+contador1+"' name='txtIvaS"+contador1+"'  readonly='readonly'> </div>";     
//	cadena = cadena + "<div class='col-lg-2 border'><input  style='margin: 0px; width: 100%;' type='search' id='txtCodigoP"+contador1+"' name='txtCodigoP"+contador1+"' class='form-control border-0'   autocomplete='off'  placeholder='Buscar Forma...' onclick='lookup_FP_Cpra(this.value, "+contador1+", 4);' onKeyUp='lookup_FP_Cpra(this.value, "+contador1+", 4);' />  <div class='suggestionsBox' id='suggestions20"+contador1+"' style='display: none; margin-top: 0px; '> <div class='suggestionList' id='autoSuggestionsList20"+contador1+"'>  </div> </div>  </div>";
    cadena = cadena + "<div class='col-lg-2 border'><input  style='margin: 0px; width: 100%;' type='search' id='txtCodigoS"+contador1+"' name='txtCodigoS"+contador1+"' class='form-control border-0'   autocomplete='off'  placeholder='Buscar...' onclick='lookup_FP_Cliente(this.value, "+contador1+", 4);' onKeyUp='lookup_FP_Cliente(this.value, "+contador1+", 4);' />  <div class='suggestionsBox' id='suggestions20"+contador1+"' style='display: none; margin-top: 0px; '> <div class='suggestionList' id='autoSuggestionsList20"+contador1+"'>  </div> </div>  </div>";
//  cadena = cadena + "<div class='col-lg-3 border'><input type='search' style='margin: 0px; width: 100%;'class='form-control border-0' autocomplete='off'  id='txtDescripcionP"+contador1+"'  name='txtDescripcionP"+contador1+"'  value=''  >      </div> ";
	cadena = cadena + "<div class='col-lg-3 border'><input type='search' style='margin: 0px; width: 100%;'  class='form-control border-0' autocomplete='off'  id='txtDescPagoS"+contador1+"'  name='txtDescPagoS"+contador1+"'  value=''  >      </div> ";
    cadena = cadena + "<div class='col-lg-2 border' hidden=''> <input type='text' maxlength='20' id='txtTipoP"+contador1+"' name='txtTipoP"+contador1+"' style='margin: 0px; width: 100%; text-align: right; ' class='form-control border-0'   autocomplete='off' > </div>";

//  cadena = cadena + "<div class='col-lg-1 border'> <input type='text' maxlength='10' id='nrocpteC"+contador1+"' name='nrocpteC"+contador1+"' style='margin: 0px; width: 100%; text-align: right; ' class='form-control border-0'   autocomplete='off' > </div>";    
//  cadena = cadena + "<div class='col-lg-1 border '><input type='text' style='margin: 0px; width: 100%; text-align: right; ' class='form-control border-0 bg-white' id='txtPorcentajeS"+contador1+"' name='txtPorcentajeS"+contador1+"' readonly='readonly' onKeyUp=\"calculoTotal_FP_Cpras("+contador1+")\" onclick=\"calculoTotal_FP_Cpras("+contador1+")\" autocomplete='off'  ></div>";
	cadena = cadena + "<div class='col-lg-1 border '><input type='hidden'  style='margin: 0px; width: 100%; text-align: right; '  class='form-control' id='txtPorcentajeS"+contador1+"' name='txtPorcentajeS"+contador1+"' readonly='readonly' onKeyUp=\"calculoTotal_FP_Cliente("+contador1+")\" onclick=\"calculoTotal_FP_Cliente("+contador1+")\" autocomplete='off'  ></div>";
   
//	cadena = cadena + "<div class='col-lg-2 border'> <input type='text' maxlength='10' id='txtValorS"+contador1+"' name='txtValorS"+contador1+"' style='margin: 0px; width: 100%; text-align: right; ' class='form-control border-0' onKeyUp=\"calculoTotal_FP_Cpras("+contador1+")\" onclick=\"calculoTotal_FP_Cpras("+contador1+")\"  autocomplete='off' > </div>";

	cadena = cadena + "<div class='col-lg-2 border '><input type='text' maxlength='10' id='txtValorS"+contador1+"' name='txtValorS"+contador1+"' style='margin: 0px; width: 100%; text-align: right; '  class='form-control border-0' onKeyUp=\"calculoTotal_FP_Cliente("+contador1+")\" onclick=\"calculoTotal_FP_Cliente("+contador1+")\"  autocomplete='off' > </div>";
//  cadena = cadena + "<div class='col-lg-1 border' hidden=''> <input type='hidden' maxlength='3' id='txtCuentaS"+contador1+"' name='txtCuentaS"+contador1+"' style='margin: 0px; width: 100%; text-align: right; ' class='form-control border-0'   autocomplete='off' > </div>";
	cadena = cadena + "<div class='col-lg-3 border' hidden=''> <input type='hidden' maxlength='10' id='txtCuentaP"+contador1+"' name='txtCuentaP"+contador1+"' style='margin: 0px; width: 100%; text-align: right; '  class='form-control'   autocomplete='off' > </div>";
	
	cadena = cadena + "</div>";
    //document.getElementById('txtContadorFilasFVC').value=contador;
    contador1++;
	
    $("#tablita1 ").append(cadena);
	//value="+fechaAcual+"
} 



function lookup_FP_Cliente(txtNombre, cont, accion) {
//para agregar SERVCIO pagina: nuevaFacturaVenta.php
 //alert(txtNombre);
//alert(cont);
//alert(accion);
 
    if(txtNombre.length == 0) {
        // Hide the suggestion box.
        $('#suggestions20'+cont).hide();
    } else {

        $.post("sql/factura_cobros.php", {queryString: txtNombre, cont: cont,  txtAccion: accion}, function(data){
        console.log("entro: "+data);
			if(data.length >0) {
                $('.suggestionsBox').hide();
                $('#suggestions20'+cont).show();
                $('#autoSuggestionsList20'+cont).html(data);
              //alert("entro: "+data);
            }
        });
    }
} // lookup

function fill10_FP_Cliente(cont, idServicio, cadena){
//	alert("va a llenar tabla");
   // alert(" contador: "+cont+"  idServicio: "+idServicio+" cadena: "+cadena)
   console.log("cadena forma pago",cadena);
       	console.log("mensaje");
	console.log("tipoS1",tipoS1);

    setTimeout("$('.suggestionsBox').hide();", 50);
    //thisValue.replace(" ","");
    array = cadena.split("*");

    // este if debe ir antes de asignar a los txt
    if($('#txtCodigo'+cont).val() >= 1){
        // cuando no usa el boton limpiar significa que
        // si hay cuenta agregada en la fila y solo esta remplazando por otra cuenta
        // ya no vuelve a sumar cuantas cuentas estan agregadas
    }else{
        // cuando usa el boton limpiar
        // significa que ha quitado la cuenta y cunado agrega una nueva suma cuantas cuentas estan agregadas
      // revi abri  sumaAsientosAgregados =  $('#txtContadorAsientosAgregadosFVC').val();
      //rev abri  sumaAsientosAgregados ++;
     //rev abr   $('#txtContadorAsientosAgregadosFVC').val(sumaAsientosAgregados);
    }
    


    $('#txtCodigoS'+cont).val(array[0]);  
    $('#txtTipo1'+cont).val(array[5]);
    $('#txtDescPagoS'+cont).val(array[1]);
//	$('#txtTipoS'+cont).val(array[2]);
	var porcen=0;
    var txtCuenta1 = parseInt(array[3]);
    $("#txtCuentaP"+cont).val(txtCuenta1);	
	var tipoS1 = $("#txtTipo1"+cont).val();
	var sesion_tipo_empresax=parseInt(array[6]);

	console.log("mensaje");
	console.log("tipoS1",tipoS1);
	
	if (tipoS1=="4")
	{
		$('#txtFechaS'+cont).val(array[4]);
		$('#txtCuotaS'+cont).val(1);

    }
	else if (tipoS1=="cheque") 
	{
        $('#nrocpteC'+cont).val("");         
    } 
	
	if (tipoS1=="retencion-iva")
	{
		if (porcen>0)
		{
			if (sesion_tipo_empresax==6)
			{
				var subtotal = document.getElementById("txtIva1").value;
				var valor=subtotal*porcen/100;
				$('#txtValorS'+cont).val(valor.toFixed(2));
				//calculoTotal_FP_Cpras();		
				calculoTotal_FP_Cliente();	
			}
		}
	}	
	
	if (tipoS1=="retencion-fuente")
	{
		if (porcen>0)
		{
			if (sesion_tipo_empresax==6)
			{
			var subtotal = document.getElementById("txtSubTotal").value;
			var valor=subtotal*porcen/100;
			$('#txtValorS'+cont).val(valor.toFixed(2));
		//	calculoTotal_FP_Cpras();
			calculoTotal_FP_Cliente();
			}
		}
	    //if (porcen>0){
	}
	
	if (tipoS1=="cheque")
	{
     //   $('#nrocpteC'+cont).focus();
    }else 
	{
		$('#txtValorS'+cont).focus();
    }
/* 	if (tipoS=="113001001")
	{
//		alert("entro a credito1111");
		$('#txtFechaS'+cont).val(array[4]);
		$('#txtCuotaS'+cont).val(1);
	}
	
    if (porcen>0){
		var subtotal = document.getElementById("txtSubTotal").value;
		var valor=subtotal*porcen/100;
		$('#txtValorS'+cont).val(valor.toFixed(2));
		calculoTotal_FP_Vtas1();
	}
	else
	{
		$('#txtValorS'+cont).val();
		$('#txtValorS'+cont).focus();
		//calculoTotal_FP_Vtas1();
	}	     	 */
}

function calculoTotal_FP_Cliente(con){
	var j=0;
	j=con;
	//j=con-8;
//	alert("Con===="+j);
//	alert(j);
	valorTotal = $("#txtValorS"+j).val();
	calculoTotal_FP_Cliente1()
	//tipos = $("#txtTipoS"+j).val();
	//if tipos="credito"
	/* {
       
    }
	else
	{
       
	} */   
}

function calculoTotal_FP_Cliente1(){
	//alert("calcular subtotal2^^^^^====");
    var sumaValorTotal = 0;
    var valorTotal=0;
    //var contador=8;
	var contador=5;
    for(i=1;i<contador;i++){
        valorTotal = $("#txtValorS"+i).val();
		//alert("estoy dentro del for==="+i+"="+valorTotal);
		//alert(valorTotal);
		//alert("cc");
        if(valorTotal == ""){
            valorTotal=0;
			//alert("alert----0");
        }
        else
        {
            sumaValorTotal = sumaValorTotal + parseFloat(valorTotal);
	//	alert("suma===="+sumaValorTotal);
		   // alert(sumaValorTotal);		 
        }
    }
    if(sumaValorTotal>0){
		//alert("entro a total==="+sumaValorTotal);
       // document.getElementById('txtSubtotalVta').value=(sumaValorTotal).toFixed(2);
        document.getElementById('txtPagoFP').value=(sumaValorTotal).toFixed(2);
        var debe1=document.getElementById('txtDeudaTotal').value;
        var cambio1=parseFloat(debe1)-parseFloat(sumaValorTotal);
        document.getElementById('txtCambioFP').value=(cambio1).toFixed(2);
		//txtSubtotalFVC
    }    
}





function AgregarFilas_FP_Vtas(contador_fp){
	contador1=contador_fp;
//	alert("agregar filkkk");
//    cadena = cadena + "<td> <input type='text' maxlength='10' id='txtCantidadS"+contador+"' name='txtCantidadS"+contador+"' style='margin: 0px; width: 100%; text-align: right; ' class='text_input' onKeyUp=\"calculaCantidadFacturaVentaCondominios("+contador+")\" onclick=\"calculaCantidadFacturaVentaCondominios("+contador+")\" onKeyPress=\"return soloNumeros(event)\" autocomplete='off' > </td>";
	cadena = "";

    cadena = cadena + "<div class='col-lg-1 border'><a onclick=\"limpiarFilas_FP_Vtas("+contador1+");\" title='Limpiar fila'><span class='btn btn-default btn-sm glyphicon glyphicon-erase' ></span></a>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIdIvaS"+contador1+"' name='txtIdIvaS"+contador1+"'  readonly='readonly'>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIvaS"+contador1+"' name='txtIvaS"+contador1+"'  readonly='readonly'> </div>";
    cadena = cadena + "<div class='col-lg-2 border'><input  style='margin: 0px; width: 100%;' type='search' id='txtCodigoS"+contador1+"' name='txtCodigoS"+contador1+"' class='form-control border-0'   autocomplete='off'  placeholder='Buscar...' onclick='lookup_FP_Vtas(this.value, "+contador1+", 4);' onKeyUp='lookup_FP_Vtas(this.value, "+contador1+", 4);' />  <div class='suggestionsBox' id='suggestions20"+contador1+"' style='display: none; margin-top: 0px; '> <div class='suggestionList' id='autoSuggestionsList20"+contador1+"'>  </div> </div>  </div>";
    cadena = cadena + "<div class='col-lg-4 border'><input type='search' style='margin: 0px; width: 100%;'  class='form-control border-0' autocomplete='off'  id='txtDescPagoS"+contador1+"'  name='txtDescPagoS"+contador1+"'  value=''  >      </div> ";
    cadena = cadena + "<input type='hidden' maxlength='10' id='txtTipo1"+contador1+"' name='txtTipo1"+contador1+"' style='margin: 0px; width: 100%; text-align: right; '  class='form-control border-0'   autocomplete='off' >";
    cadena = cadena + "<td hidden=''><input type='hidden'  style='margin: 0px; width: 100%; text-align: right; '  class='form-control' id='txtPorcentajeS"+contador1+"' name='txtPorcentajeS"+contador1+"' readonly='readonly' onKeyUp=\"calculoTotal_FP_Vtas("+contador1+")\" onclick=\"calculoTotal_FP_Vtas("+contador1+")\" autocomplete='off'  ></div>";
    //cadena = cadena + "<td><input type='text' style='margin: 0px; width: 100%; text-align: right; ' class='text_input' id='txtValorS"+contador+"' name='txtValoS"+contador+"'  readonly='readonly'>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtCalculoIvaS"+contador+"' name='txtCalculoIvaS"+contador+"'  readonly='readonly'>  </td>";
	cadena = cadena + "<div class='col-lg-2 border '><input type='text' maxlength='10' id='txtValorS"+contador1+"' name='txtValorS"+contador1+"' style='margin: 0px; width: 100%; text-align: right; '  class='form-control border-0' onKeyUp=\"calculoTotal_FP_Vtas("+contador1+")\" onclick=\"calculoTotal_FP_Vtas("+contador1+")\"  autocomplete='off' > </div>";
    cadena = cadena + "<div class='col-lg-1 border '><input type='text' maxlength='4' id='txtCuotaS"+contador1+"' name='txtCuotaS"+contador1+"' style='margin: 0px; width: 100%; text-align: right; '  class='form-control border-0 '   autocomplete='off' > </div>";
   // cadena = cadena + "<div class='col-lg-1 border celeste'><input type='text' maxlength='4' id='txtDiasPlazoS"+contador1+"' name='txtDiasPlazoS"+contador1+"' style='margin: 0px; width: 100%; text-align: right; '  class='form-control border-0 celeste'  autocomplete='off' > </div>";
	cadena = cadena + "<div class='col-lg-2 border '><input type='text' maxlength='20' placeholder='fecha' id='txtFechaS"+contador1+"'  name='txtFechaS"+contador1+"' style='margin: 0px; width: 100%; text-align: right; '  class='form-control border-0 ' onKeyUp=\"listar_cuotas_Cliente("+contador1+")\" onclick=\"listar_cuotas_Cliente("+contador1+")\"  autocomplete='off' > </td>";   
	//cadena = cadena + "<td> <input type='text' maxlength='20' id='txtFechaS"+contador1+"'  value="+fechaAcual+"  name='txtFechaS"+contador1+"' style='margin: 0px; width: 100%; text-align: right; ' class='text_input' onKeyUp=\"listar_cuotas_Prov("+contador1+")\" onclick=\"listar_cuotas_Prov("+contador1+")\"  autocomplete='off' > </td>";   
	//cadena = cadena + "<td><input type='text' id='txtFechaTP' value="+fechaAcual+" name='txtFechaTP' style='font-size: 30px;  width: 100%' onClick=\"displayCalendar(txtFechaTP,'yyyy-mm-dd',this)\" onKeyPress=\"listar_cuotas_Prov()\" onchange=\"listar_cuotas_Prov()\" title='Ingrese un numero' class='text_input' maxlength='100' placeholder='Numero de dias plazo' autocomplete='off'/></td>";
	cadena = cadena + "<div class='col-lg-1 border' hidden=''> <input type='text' maxlength='3' id='txtCuentaP"+contador1+"' name='txtCuentaP"+contador1+"' style='margin: 0px; width: 100%; text-align: right; '  class='form-control'   autocomplete='off' > </div>";
   // type='hidden'

    //document.getElementById('txtContadorFilasFVC').value=contador;
    contador1++;
    $("#tablita1 ").append(cadena);
	//value="+fechaAcual+"
}
  function AgregarFilas_FP_CuentasPorCobrar(contador_fp){
        contador1=contador_fp;
        
        cadena = "";
        cadena = cadena + "<div class='row my-1'>";
        cadena = cadena + "<div class='col-lg-1'><a onclick=\"limpiarFilas_FP_Vtas("+contador1+");\" title='Limpiar fila'><i class='fa fa-times' aria-hidden='true'></i></a>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIdIvaS"+contador1+"' name='txtIdIvaS"+contador1+"'  readonly='readonly'>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIvaS"+contador1+"' name='txtIvaS"+contador1+"'  readonly='readonly'> </div>";
        cadena = cadena + "<input  style='margin: 0px; width: 100%;' type='hidden' id='txtCodigoS"+contador1+"' name='txtCodigoS"+contador1+"' class='form-control'   autocomplete='off'   /> ";
        cadena = cadena + "<div class='col-lg-4'><input type='search' style='margin: 0px; width: 100%;'  class='form-control' autocomplete='off'  id='txtDescPagoS"+contador1+"'  placeholder='Buscar...' name='txtDescPagoS"+contador1+"'  value='' onclick='lookup_FP_Vtas(this.value, "+contador1+", 4);' onKeyUp='lookup_FP_Vtas(this.value, "+contador1+", 4);'   >  <div class='suggestionsBox' id='suggestions20"+contador1+"' style='display: none; margin-top: 0px; '> <div class='suggestionList' id='autoSuggestionsList20"+contador1+"'>  </div> </div>      </div> ";
        cadena = cadena + "<input type='hidden' maxlength='10' id='txtTipo1"+contador1+"' name='txtTipo1"+contador1+"' style='margin: 0px; width: 100%; text-align: right; '  class='form-control'   autocomplete='off' >";
        cadena = cadena + "<input type='hidden'  style='margin: 0px; width: 100%; text-align: right; '  class='form-control' id='txtPorcentajeS"+contador1+"' name='txtPorcentajeS"+contador1+"' readonly='readonly' onKeyUp=\"calculoTotal_FP_Vtas("+contador1+")\" onclick=\"calculoTotal_FP_Vtas("+contador1+")\" autocomplete='off'>";
        
    
        cadena = cadena + "<div class='col-lg-2'><input type='text' maxlength='10' id='txtValorS"+contador1+"' name='txtValorS"+contador1+"' style='margin: 0px; width: 100%; text-align: right; '  class='form-control' onKeyUp=\"calculoTotal_FP_Cliente("+contador1+")\" onclick=\"calculoTotal_FP_Cliente("+contador1+")\"  autocomplete='off' > </div>";
    
    
        cadena = cadena + "<div class='col-lg-1' style='display:none' id='divCuotas"+contador1+"'><input type='text' maxlength='4' id='txtCuotaS"+contador1+"' name='txtCuotaS"+contador1+"' style='margin: 0px; width: 100%; text-align: right; '  class='form-control border-0 '   autocomplete='off' > </div>";
        cadena = cadena + "<div class='col-lg-2' style='display:none' id='divFechas"+contador1+"'><input type='text' maxlength='20' placeholder='fecha' id='txtFechaS"+contador1+"'  name='txtFechaS"+contador1+"' style='margin: 0px; width: 100%; text-align: right; '  class='form-control border-0 ' onKeyUp=\"listar_cuotas_Cliente("+contador1+")\" onclick=\"listar_cuotas_Cliente("+contador1+")\"  autocomplete='off' > </div>";   
    
         cadena = cadena + "<div class='col-lg-1' style='display:none' id='divNumeroRetencion"+contador1+"'><input type='text' maxlength='4' id='txtNumeroRetencionS"+contador1+"' name='txtNumeroRetencionS"+contador1+"' style='margin: 0px; width: 100%; text-align: right; '  class='form-control border-0 '   autocomplete='off' > </div>";

        cadena = cadena + "<div class='col-lg-4' style='display:none' id='divAutorizacion"+contador1+"'><input type='text' maxlength='60'  id='txtAutorizacion"+contador1+"'  name='txtAutorizacion"+contador1+"' style='margin: 0px; width: 100%; text-align: right; '  class='form-control border-0' autocomplete='off' > </div>"; 

        cadena = cadena + "<div class='col-lg-1' style='display:none' id='divDocumento'><input type='text' maxlength='20' placeholder='Numero' id='txtNumDocumento"+contador1+"'  name='txtNumDocumento"+contador1+"' style='margin: 0px; width: 100%; text-align: right; ' class='form-control border-0 '   autocomplete='off' > </div>";   
    
        cadena = cadena + "<div class='col-lg-1' hidden=''> <input type='text' maxlength='3' id='txtCuentaP"+contador1+"' name='txtCuentaP"+contador1+"' style='margin: 0px; width: 100%; text-align: right; '  class='form-control'   autocomplete='off' > </div>";
        cadena = cadena + "<input type='hidden'  class='form-control' id='formaPagoId"+contador1+"' name='formaPagoId"+contador1+"'  readonly='readonly'>";
    
        cadena = cadena + "</div>";
    
        contador1++;
        $("#tablita1 ").append(cadena);
    
    }
    function pdfEmailFacturaCuentaCobrar( idDetalles,tipo,abonado=0){
        //  id,tipo, idCuentas
       let id= frmCobrarCuentaCobrarV.txtIdCliente.value;
//  let tipo = frmCobrarCuentaCobrarV.txtTipoPago.value;

 let idCuentas= '0';
 $("input[type=checkbox]:checked[name=checkCobrar[]]").each(function() {
//   alert("Seleccionado el input " + $(this).val());
idCuentas = idCuentas+','+$(this).val();

});
idCuentas.substring(2);
         
      
     
        	$.ajax({
		url: 'reportes/rptFacturaCuentasPorCobrar_email.php',
		data: "id="+id+"&switch-four="+tipo+"&checkCobrar="+idCuentas+"&idDetalles="+idDetalles+"&abonado="+abonado,
    type: 'get',
    success: function(data){
      let response = data.trim();
			if(response==1 ){
				alertify.success("Correo enviado con exito :)");
			}else{
				alertify.error("Error al enviar correo, revise que el email sea correcto.");
			}   
		
    }
  });
  
    }