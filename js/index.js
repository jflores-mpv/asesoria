
// JavaScript Document
/*
$(document).ready(function(){                                         
	$("#grilla tbody tr").mouseover(function(){
		$(this).addClass("over");
	}).mouseout(function(){
		$(this).removeClass("over");
	});
});
*/



function fn_cerrar(){
	$.unblockUI({ 
		onUnblock: function(){
			$("#div_oculto").html("");
		}
	});
}

function ingreso_sistema(){
    // valida el ingreo al sistema
    var str = $("#form").serialize();
    // console.log("form",str);
    
    $.ajax
	({
        url: 'sql/validarLogin.php',
            type: 'post',
            data: str,
            beforeSend: function(){
                //imagen de carga
                $("#mensaje").html("<img align='center' src='images/loading.gif' />");
            },
            success: function(data){
              console.log("funcion iniciar sesion");
                // document.getElementById("mensaje").innerHTML=""+data;

                if(data == 23){
                    location.href='administrador.php';
                }
                if(data == 50){
                    location.href='administradorContaweb.php';
                }
                if(data == 51){
                    console.log(data);
                    location.href='administradocontricapsas.php';
                    
                }
                if(data == 2){
                    location.href='menuPrincipalCondominios.php';
                }
                if(data == 3){                    
                    location.href='menuPrincipalCondominios.php';
                }
                if(data == 4){
                    location.href='menuPrincipalCondominios.php';
                }
                if(data == 5){
                    location.href='menuPrincipalCondominios.php';
                }
                if(data == 6 && data == 101 && data == 102 && data == 103 && data == 104 && data == 105 ){
                    location.href='menuPrincipalCondominios.php';
                }
                if(data == 7){
                    location.href='menuPrincipalCondominios.php';
                }
                if(data == 8){
                    location.href='menuPrincipalCondominios.php';
                }
                if(data == 9){
                    location.href='menuPrincipalCondominios.php';
                }
                if(data >= 10){
                    location.href='menuPrincipalCondominios.php';
                }
                if(data == 11){
                    location.href='menuPrincipalCondominios.php';
                }
                if(data == 12){
                    location.href='menuPrincipalCondominios.php';
                }
                 if(data == 13){
                    location.href='menuPrincipalCondominios.php';
                }
                 if(data == 14){
                    location.href='menuPrincipalCondominios.php';
                }
                 if(data == 15){
                    location.href='menuPrincipalCondominios.php';
                }
                 if(data == 16){
                    location.href='menuPrincipalCondominios.php';
                }
                 if(data == 17){
                    location.href='menuPrincipalCondominios.php';
                }
                 if(data == 18){
                    location.href='menuPrincipalCondominios.php';
                }
                    if(data == 19){
                    location.href='menuPrincipalCondominios.php';
                }
                    if(data == 20){
                    location.href='menuPrincipalCondominios.php';
                }
                      if(data == 21){
                    location.href='menuPrincipalCondominios.php';
                }
                      if(data == 22){
                    location.href='menuPrincipalCondominios.php';
                }
                
                 if(data == 100){
                    location.href='cambios.php';
                }
            }
    });

}

function ingreso_cursoProf(){
    // valida el ingreo al sistema
    var str = $("#form").serialize();
    // console.log("form",str);
    $.ajax({
            url: 'sql/validarLogin.php',
            type: 'post',
            data: str,
            beforeSend: function(){
                //imagen de carga
                $("#mensaje").html(" <span class='glyphicon glyphicon-hourglass' aria-hidden='true'></span>");
            },
            success: function(data){
              console.log(data);
                document.getElementById("mensaje").innerHTML=""+data;
             // alert("data");
            //  alert(data)
                 if(data == 23){
                    location.href='administrador.php';
                }
                 if(data == 50){
                    location.href='administradorContaweb.php';
                }
                  if(data == 51){
                    console.log(data);
                    location.href='administradocontricapsas.php';
                    
                }
			    if(data == 12){
                    location.href='menuPrincipalCondominios.php';
                }
                 if(data == 13){
                    location.href='menuPrincipalCondominios.php';
                }
                 if(data == 14){
                    location.href='menuPrincipalCondominios.php';
                }
                 if(data == 15){
                    location.href='menuPrincipalCondominios.php';
                }
                 if(data == 16){
                    location.href='menuPrincipalCondominios.php';
                }
                 if(data == 17){
                    location.href='menuPrincipalCondominios.php';
                }
                 if(data == 18){
                    location.href='menuPrincipalCondominios.php';
                }
                    if(data == 19){
                    location.href='administrador-empresa.php';
                }
                  if(data == 24){
                    location.href='estudiante.php';
                }
                
                if(data == 100){
                    location.href='cambios.php';
                }
                
                
            }
    });

}


function ingreso_empresa(accion) {
    var str = $("#form").serialize();
    $.ajax({
        url: 'sql/ingresoEmpresa.php',
        type: 'post',
        data: str + "&accion=" + accion,
        success: function(data) {
            if (data == 1) {
                showPopup('success', '¡Ingreso exitoso!', 'Ha ingresado al sistema correctamente.');
                setTimeout(function() {
                    location.href = 'ingreso.php';
                }, 1000); // Redirige después de 1 segundos
            } else {
                document.getElementById("mensaje").innerHTML = "" + data;
            }
        }
    });
}

// Función para mostrar el popup
function showPopup(type, title, message) {
    var popup = document.createElement('div');
    popup.className = `popup popup--visible popup--icon -${type}`;
    popup.innerHTML = `
        <div class="popup__background"></div>
        <div class="popup__content">
            <div class="popup__content__title">${title}</div>
            <p>${message}</p>
            <button class="button" onclick="closePopup(this)">Cerrar</button>
        </div>
    `;
    document.body.appendChild(popup);
}

// Función para cerrar el popup
function closePopup(button) {
    const popup = button.closest('.popup');
    popup.classList.remove('popup--visible');
    setTimeout(() => {
        popup.remove();
    }, 300);
}





function listadoEmpresas(){
    console.log("listado");
    var fechaDesde = document.getElementById('txtFechaDesde').value;
    var fechaHasta = document.getElementById('txtFechaHasta').value;
    console.log("fechaDesde",fechaDesde);
    console.log("fechaHasta",fechaHasta);
    var str = $("#frmEmpresasContaweb").serialize();
    console.log(str);
    
    $.ajax({
            url: 'ajax/listadoEmpresas.php',
            type: 'get',
            data: str+"&fechaDesde="+fechaDesde+"&fechaHasta="+fechaHasta,
            success: function(data){
                
                // console.log(data);
                $("#listadoEmpresas").html(data);
            }
    });
}

function listarEmpresasDos(){
    
    var fechaDesde = document.getElementById('txtFechaDesde').value;
    var fechaHasta = document.getElementById('txtFechaHasta').value;
    console.log("fechaDesde",fechaDesde);
    console.log("fechaHasta",fechaHasta);
    var str = $("#frmEmpresasContaweb").serialize();
    console.log(str);
    
    $.ajax({
            url: 'ajax/listadoEmpresas.php',
            type: 'get',
            data: str+"&fechaDesde="+fechaDesde+"&fechaHasta="+fechaHasta,
            success: function(data){
                
                // console.log(data);
                $("#listadoEmpresas").html(data);
            }
    });
}

function listarEmpresas(page){
    
    var fechaDesde = document.getElementById('txtFechaDesde').value;
    var fechaHasta = document.getElementById('txtFechaHasta').value;
    console.log("fechaDesde",fechaDesde);
    console.log("fechaHasta",fechaHasta);
    var str = $("#frmEmpresasContaweb").serialize();
    console.log(str);
    
    $.ajax({
            url: 'ajax/listarEmpresasContaweb.php',
            type: 'get',
            data: str+"&page="+page+"&fechaDesde="+fechaDesde+"&fechaHasta="+fechaHasta,
            success: function(data){
                
                // console.log(data);
                $("#listarEmpresasContaweb").html(data);
            }
    });
}

function listarEmpresasTotales(page){
    
    var fechaDesde = document.getElementById('txtFechaDesde').value;
    var fechaHasta = document.getElementById('txtFechaHasta').value;
    console.log("fechaDesde",fechaDesde);
    console.log("fechaHasta",fechaHasta);
    var str = $("#frmEmpresasContaweb").serialize();
    console.log("totales",str);
    
    $.ajax({
            url: 'ajax/listarEmpresasContawebTotales.php',
            type: 'get',
            data: str+"&page="+page+"&fechaDesde="+fechaDesde+"&fechaHasta="+fechaHasta,
            success: function(data){
                
                // console.log(data);
                $("#listarEmpresasContawebTotales").html(data);
            }
    });
}

function listarEmpresasContricapsas(page){
    var str = $("#frmEmpresas").serialize();
    console.log(str);
    
    $.ajax({
            url: 'ajax/listarEmpresasContricapsas.php',
            type: 'get',
            data: str+"&page="+page,
            success: function(data){
                
                // console.log(data);
                $("#listarEmpresasContricapsas").html(data);
            }
    });
}

function modificaLimite(accion,id_empresa,limite){
    console.log("MODIFICAR");
    console.log(accion);
    console.log("limite",limite)
    $.ajax({
            url: 'sql/empresa.php',
            data: "accion="+accion+"&id_empresa="+id_empresa+"&limite="+limite,
            type: 'post',
            success: function(data){
               console.log(data);
            }
    });
    
}


function preguntarSiNo(id,periodo){
    console.log(id,periodo);
	alertify.confirm('Eliminar Datos', '多Esta seguro de eliminar este registro?', 
					function(){ eliminarDatos(id, periodo) }
                , function(){ alertify.error('Se cancelo')});
}

function eliminarDatos(id,periodo){

	cadena="id="      + id +
	       "&periodo=" + periodo ;

		$.ajax({
			type:"POST",
			url:"sql/eliminarEmpresa.php",
			data:cadena,
			success:function(r){
			    console.log(r)
				if(r==1){
				//	$('#listadoCheques').load('ajax/listadoCheques.php');
					alertify.success("Eliminado con exito!");
				}
				else{
					alertify.error("Fallo el servidor :(");
				}
			}
		});
}


function modificaLimite3(accion,id_empresa){
    console.log("MODIFICAR");

    let limite = $('#limite'+id_empresa).val();

     accion = 0;
     accion = 16;
    $.ajax({
            url: 'sql/empresa.php',
            data: "accion="+accion+"&id_empresa="+id_empresa+"&limite="+limite,
            type: 'post',
            success: function(data){
             console.log(data);
            if(data==='1'){
                alertify.success('Se actualizo correctamente el limite');
            }else{
                alertify.error('Error al actualizar el limite');
            }
           
            }
    });
    
}

function modificaLimite4(accion,id_empresa){

     accion = 17;
    $.ajax({
            url: 'sql/empresa.php',
            data: "accion="+accion,
            type: 'post',
            success: function(data){
             console.log(data);
            if(data==='1'){
                alertify.success('Se actualizo correctamente el limite');
            }else{
                alertify.error('Error al actualizar el limite');
            }
           
            }
    });
    
}

function modificaLimite1(accion,id_empresa){
    console.log("MODIFICAR");
    console.log(accion);
    let limite = $('#limite'+id_empresa).val();
    let valor = $('#valor'+id_empresa).val();
    let url = $('#url'+id_empresa).val();
    let observacion = $('#observacion'+id_empresa).val();
     let original = $('#original'+id_empresa).val();
     let tipo = $('#tipo'+id_empresa).val();
     console.log(url)
    $.ajax({
            url: 'sql/empresa.php',
            data: "accion="+accion+"&id_empresa="+id_empresa+"&limite="+limite+"&valor="+valor+"&url="+url+"&observacion="+observacion+"&original="+original+"&tipo="+tipo,
            type: 'post',
            success: function(data){
             console.log(data);
            if(data==='1'){
                alertify.success('Se actualizo correctamente el limite');
                listarEmpresas(1);
            }else{
                alertify.error('Error al actualizar el limite');
            }
           
            }
    });
    
}

// FUNCION PARA BUSCAR LAS CUENTAS Y AGREGARLAS EN LA CREACCION DE CADA TRANSACCION, PAGINAS: libroDiario.php, balanceComprobacion.php, depreciaciones.PHP
function lookup2(txtCuenta, cont, accion) {
// alert(" "+txtCuenta+" "+accion);
    if(txtCuenta.length == 0) {
        //txtCuenta.blur();
         $('#suggestions'+cont).hide();
        //$('#cmbLista').hide();
    } else {
            $.post("sql/libroDiario.php", {queryString: ""+txtCuenta+"",aux: cont, txtAccion: accion}, function(data){
                    if(data.length >0) {
                            //$('#cmbLista').show(); /* suggestions1 */
                            //$('#cmbLista').html(data); /* autoSuggestionsList1 */
                            $('.suggestionsBox').hide();
                            $('#suggestions'+cont).show();
                            $('#autoSuggestionsList'+cont).html(data);
                    }
            });
    }

} // lookup


function fill2(thisValue, contt, id, cuenta_banco) {

    //retorna en asientosContables.php y ajax/formaPago.php
    //alert(thisValue);
    setTimeout("$('.suggestionsBox').hide();", 100);

        thisValue.replace(" ","");
        array = thisValue.split("-");

        // este if debe ir antes de axignar a los txt
        if($('#txtIdCuenta'+contt).val() >= 1){
            // cuando no usa el boton limpiar significa que
            // si hay cuenta agregada en la fila y solo esta remplazando por otra cuenta
            // ya no vuelve a sumar cuantas cuentas estan agregadas
        }else{
            // cuando usa el boton limpiar
            // significa que ha quitado la cuenta y cunado agrega una nueva suma cuantas cuentas estan agregadas
            sumaAsientosAgregados =  $('#txtContadorAsientosAgregados').val();
            sumaAsientosAgregados ++;
            $('#txtContadorAsientosAgregados').val(sumaAsientosAgregados);
        }
        let nombreCuenta = array.slice(1).join("-");
        // estos txt deben ir aqui porque tambien se usa en la pagina ajax/formaPago.php
        $('#txtIdCuenta'+contt).val(id);
        $('#txtCuenta2'+contt).val(nombreCuenta.replace(" ",""));
        $('#txtCodigo'+contt).val(array[0].replace(" ",""));
        $('#txtCuenta2'+contt).focus();

        if(cuenta_banco > 1){
            // si utuliza la cuenta bancos muestra el recuadro de bancos con los campos tipo docuemnto, numero de documento, etc.
            $('#Bancos').show();
            $('#planCuentaUnicoBanco').val(id);
            cambioDetalle();//funcion para el cambio de palabras
        }

        $('#txtCuentaBanco'+contt).val(cuenta_banco);


    //asientosAgregados();

 /*
  //retorna en la pagina: balanceComprobacion.php
    $('#txtPlanCuenta').val(thisValue);
    $('#txtIdPlanCuenta').val(id);
    //retorna en la pagina: libroDiario.php
    $('#txtIdCuenta'+contt).val(id);
    $('#txtCuenta'+contt).val(thisValue);

    if(String(categoria) == "Debito"){
        document.getElementById("txtHaber"+contt).disabled = true;
    }
    if(String(categoria) == "Credito"){
        document.getElementById("txtDebe"+contt).disabled = true;
    }
    */

    //retorna en la pagina: ajax/modificarTransaccion.php

    //$('#txtIdCuenta2'+contt).val(id);


}

//**************************** PLAN CUENTAS ************************//
// function fn_buscar(){
// 	var str = $("#frm_buscar").serialize();
// 	$.ajax({
// 		url: 'ajax/listarPlanCuentas.php',
// 		type: 'get',
// 		data: str,
// 		success: function(data){
// 			$("#div_listar_cuenta").html(data);
// 		}
// 	});
// }


function fn_buscar(page=1, mantener=0){
    
    if(mantener==1){
        let lis = document.querySelector(".page-item.active");
        if(lis){
         let paginaActual = lis.dataset.paginaactual;
        page =paginaActual;
        }
    }
        
       
        var str = $("#frm_buscar").serialize();
        $.ajax({
                url: 'ajax/listarPlanCuentas.php',
                type: 'get',
                data: str+"&page="+page,
                success: function(data){
                        $("#div_listar_cuenta").html(data);
                }
        });
}

// function fn_buscar(page=1){
// 	var str = $("#frm_buscar").serialize();
// // 	console.log(str);
// 	$.ajax({
// 		url: 'ajax/listarPlanCuentas.php',
// 		type: 'get',
// 		data: str+"&page="+page,
// 		success: function(data){
// 			$("#div_listar_cuenta").html(data);
// 		}
// 	});
// }


function fn_cerrar_div(){
    // funcion para cerrar el cuadro de busqueda pagina: asientosContables.php
    setTimeout("$('.suggestionsBox').hide();", 20);
}

function nuevoPlanCuentas(permisos_plan_cuentas_guardar){
    
    if(permisos_plan_cuentas_guardar == "No"){
        alert("Usted No tiene permisos. \nConsulte con el Administrador.");
    }else{
        $("#div_oculto").load("ajax/nuevoPlanCuentas.php", function(){
		$.blockUI({
			message: $('#div_oculto'),

		overlayCSS: {backgroundColor: '#111'},
			css:{
				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
				background: 'white', /* #f9f9f9*/
				top: '5%',
				position: 'absolute',
				width: '25%',
				left: '40%' 
				// left: ($(window).width() - $('.caja').outerWidth())/2
			}
		});
	});
    }
	
}

function modificarPlanCuentas(id_plan_cuenta, permisos_plan_cuentas_modificar){

    if(permisos_plan_cuentas_modificar == "No"){
        alert("Usted No tiene permisos. \nConsulte con el Administrador.");
    }else{
	$("#div_oculto").load("ajax/modificarPlanCuentas.php", {id_plan_cuenta: id_plan_cuenta}, function(){
		$.blockUI({
			message: $('#div_oculto'),
			overlayCSS: {backgroundColor: '#111'},
			css:{
				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                                position: 'absolute',
				background: '',
				top: '10%',
				left: '40%'
			}
		});
	});
    }
}

function eliminarPlanCuentas(id_cuenta, accion, permisos_plan_cuentas_eliminar){
    if(permisos_plan_cuentas_eliminar == "No"){
        alert("Usted No tiene permisos. \nConsulte con el Administrador.");
    }else{
	var respuesta1 = confirm("Seguro desea eliminar esta cuenta?");
	if (respuesta1){
		$.ajax({
			url: 'sql/plan_cuentas.php',
			data: 'id_cuenta=' + id_cuenta+"&accion="+accion,
			type: 'post',
			success: function(data){
				if(data!="")
				 alertify.error("Cuenta Eliminada con exito");
				fn_buscar()
			}
		});
	}
    }
}

function modificar_plan_cuentas(){
	$("#div_oculto").load("ajax/modificar_plan_cuentas.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
			overlayCSS: {backgroundColor: '#111'},
			css:{
				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                                position: 'absolute',
				background: '#f9f9f9',
				top: '20px',
				left: '185px',
				width: '640px'
			}
		});
	});
}

function eliminar_plan_cuentas(){
	$("#div_oculto").load("ajax/eliminar_plan_cuentas.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
			overlayCSS: {backgroundColor: '#111'},
			css:{
				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                                position: 'absolute',
				background: '#f9f9f9',
				top: '20px',
				left: '185px',
				width: '640px'
			}
		});
	});
}

function vaciar_plan_cuentas(accion){
	var respuesta2 = confirm("Seguro desea vaciar la tabla Plan Cuentas?");
	if (respuesta2){
		$.ajax({
			url: 'sql/plan_cuentas.php',
			data: 'accion=' + accion,
			type: 'post',
			success: function(data){
				if(data!="")
					alert(data);
				fn_buscar()
			}
		});
	}
}


//******************** FACTURA COMPRA ******************//
function agregar_producto_factura(){
  // pagina: nuevaFacturaCompra.php

$("#div_oculto").load("ajax/buscarProductosFactura.php", function(){
        $.blockUI({
                message: $('#div_oculto'),
                overlayCSS: {backgroundColor: '#111'},
                css:{
                        '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px',
                        position: 'absolute',
                        background: '#FFFFFF',
                        top: '40px',
                        left: '185px',
                        right: '185px',
                        width: '520px',
                        theme: true,
                        baseZ: 2000
                }
        });
});
}
/* 
function guardar_factura_compra(accion){
    //PAGINA: nuevaFacturaCompra.php
    var idProveedor = document.form3['textIdProveedor'].value;
    var idProducto = document.form3['idproducto2'].value;
    var cantidad = document.form3['cant2'].value;
    if(idProveedor != ""){
        if(idProducto != ""){
            if(cantidad != ""){
                var str = $("#form3").serialize();
                $.ajax({
                    url: 'sql/facturaCompra.php',
                    data: str+"&txtAccion="+accion,
                    type: 'post',
                    success: function(data){
                        document.getElementById("mensaje3").innerHTML+="<div class='transparent_notice'><p>"+data+"</p></div>";
                        //document.getElementById("form3").reset();                        
                        alert(data);
                        if(confirm("Desea imprimir la factura?")){
                            miUrl = "reportes/rptFacturaCompra.php?id_compra="+document.getElementById("txtIdCompra").value;
                            window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
                            //setTimeout('document.location.reload()', 5000);
                        }else{
                            //setTimeout('document.location.reload()', 2000);
                        }
                        if(confirm("Desea ingresar la Transacci\u00f3n de la factura?")){
                           facCompTransaccion();
                        }else{
                            //setTimeout('document.location.reload()', 2000);
                        }
                        document.form3.submit.disabled=true;
                        //document.getElementById("btnFacturar").disabled=false;
                    }
                });
                
            }else {
                alert ('Ingrese una cantidad.');
                document.form3.cant2.focus();
            }                        
        }else {
            alert ('Ingrese un Producto.');
        }
    }else {
        alert ('Ingrese un Proveedor.');
        document.form3.txtNombreRuc.focus();
    }
}
 */
 
function repCompras(){
//	fn_cerrar1();
    $("#div_oculto").load("ajax/reportesCompras.php", function(){
	$.blockUI({
			message: $('#div_oculto'),
			overlayCSS: {backgroundColor: '#111'},
			css:{
                '-webkit-border-radius': '3px',
                '-moz-border-radius': '3px',
                'box-shadow': '6px 6px 20px gray',
				top: '5%',
				left: '5%',
				width: '90%',
                position: 'absolute'
			}
		});
       // listar_vendedores();
	});
} 
 
// function listarFacturasCompras(){
//     var str = $("#frmReporteFacturas").serialize();
//     $.ajax({
//             url: 'ajax/listarFacturasCompras.php',
//             type: 'get',
//             data: str,
//             success: function(data){
//                     $("#div_listar_facturasCompras").html(data);
//             }
//     });
// }


function listarFacturasCompras(page){

    var str = $("#frmReporteFacturas").serialize();

    $.ajax({
            url: 'ajax/listarFacturasCompras.php',
            type: 'get',
            data: str+"&page="+page,
            success: function(data){
                $("#div_listar_facturasCompras").html(data);
            }
    });
}

function facCompTransaccion(){
 //PAGINA: nuevaTransaccion.php
    $("#div_oculto").load("ajax/nuevaTransaccion.php", function(){
        $.blockUI({
                message: $('#div_oculto'),
        overlayCSS: {backgroundColor: '#111'},
                css:{
                        '-webkit-border-radius': '10px',
                        '-moz-border-radius': '10px',

                        background: '#FFFFFF',
                        top: '20px',
                        left: '185px',
                        position: 'absolute',
                        width: '650px'
                }
        });
    });
}

function retencionCompra(){
    //retencion para las facturas compras
    $("#div_oculto").load("ajax/nuevaRetencionC.php", function(){
        $.blockUI({
                message: $('#div_oculto'),
        overlayCSS: {backgroundColor: '#111'},
                css:{
                        '-webkit-border-radius': '10px',
                        '-moz-border-radius': '10px',
                        background: '#FFFFFF',
                        top: '20px',
                        left: '185px',
                        position: 'absolute',
                        width: '650px'
                }
        });
    });
}

function modificarCompra(id_compra){
    //pagina: verFacturasCompras.php
    $("#div_oculto2").load("ajax/modificarFacturaCompra.php", {id_compra: id_compra}, function(){
        $.blockUI({

                message: $('#div_oculto2'),
                overlayCSS: {backgroundColor: '#111'},
                css:{
                        '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px',
                        position: 'absolute',
                        background: '#f9f9f9',
                        top: '40px',
                        left: '185px',
                        right: '185px',
                        width: '700px'
                }
        });
});

}

function guardarModificarFacturaCompra(accion){
    //PAGINA: modificarFacturaCompra.php
    var str = $("#form3").serialize();
    $.ajax({
            url: 'sql/facturaCompra.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data){
               document.getElementById('mensaje3').innerHTML=""+data;
               listarFacturasCompras();
               //window.locationf="verFacturasCompras.php";
            }
    });
    
}






function listarFacturasVentas(){
    var str = $("#form1").serialize();
    $.ajax({
            url: 'ajax/listarFacturasVentas.php',
            type: 'get',
            data: str,
            success: function(data){
                    $("#div_listar_facturasVentas").html(data);
            }
    });
}

function guardarModificarFacturaVenta(accion){
    //PAGINA: modificarFacturaVenta.php
    var str = $("#frmFacVenta").serialize();
    $.ajax({
            url: 'sql/facturaVenta.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data){
               document.getElementById('mensaje3').innerHTML=""+data;
               listarFacturasVentas();
               //window.locationf="verFacturasVentas.php";
               document.frmFacVenta.submit.disabled=true;
            }
    });
}


//******************* PAGINADO DE PAGINAS **********************//
function fn_paginar(var_div, url){       
	var div = $("#" + var_div);
	$(div).load(url);
	/*
	div.fadeOut("fast", function(){
		$(div).load(url, function(){
			$(div).fadeIn("fast");
		});
	});
	*/
}


//*************** ASIENTOS CONTABLES ******************//

function asientos_contables(form) {
    const accion = form.elements['txtAccion'].value;
    
    switch (accion) {
        case '1':
            guardarAsiento(form);
            break;
        case '2':
            editarAsiento(form);
            break;
        case '3':
            eliminarAsiento(form);
            break;
        default:
            console.error("Acción no reconocida");
    }
}

function guardarAsiento(form) {
    const permisosGuardar = form.elements['txtPermisosAsientosContablesGuardar'].value;

    if (permisosGuardar === "No") {
        alert("Usted No tiene permisos. \nConsulte con el Administrador.");
        return;
    }

    const contadorFilas = parseInt($('#txtContadorFilas').val());
    let numFilas = 0;

    for (let i = 1; i <= contadorFilas; i++) {
        const txtIdCuenta = $(`#txtIdCuenta${i}`).val();
        if (parseInt(txtIdCuenta) > 0) {
            numFilas++;
        }
    }

    if (numFilas < 2) {
        alert('No hay suficientes cuentas para guardar.');
        return;
    }

    $('#btnGuardar').css('visibility', 'hidden');
    const str = $("#frmAsientosContables").serialize();

    $.ajax({
        url: 'sql/libroDiario.php',
        data: `${str}&txtAccion=1`,
        type: 'POST',
        success: function(data) {
            handleGuardarResponse(data, form);
        }
    });
}

function handleGuardarResponse(data, form) {
    if (data == 1) {
        alertify.success("Asiento agregado con éxito :)");
        if (confirm('Desea imprimir el Comprobante?')) {
            const cmbTipoComprobante = form.elements['cmbTipoComprobante'].value;
            const txtComprobanteNumero = form.elements['txtNumeroComprobante'].value;
            const txtFecha = form.elements['txtFecha'].value;
            let miUrl = '';

            switch (cmbTipoComprobante) {
                case "Diario":
                    miUrl = `reportes/rptComprobanteDiario.php?txtComprobanteNumero=${txtComprobanteNumero}&fecha_desde=${txtFecha}&fecha_hasta=${txtFecha}`;
                    break;
                case "Ingreso":
                    miUrl = `reportes/rptComprobanteIngreso.php?txtComprobanteNumero=${txtComprobanteNumero}&fecha_desde=${txtFecha}&fecha_hasta=${txtFecha}`;
                    break;
                case "Egreso":
                    miUrl = `reportes/rptComprobanteEgreso.php?txtComprobanteNumero=${txtComprobanteNumero}&fecha_desde=${txtFecha}&fecha_hasta=${txtFecha}`;
                    break;
                default:
                    console.error("Tipo de comprobante no reconocido");
            }

            if (miUrl) {
                window.open(miUrl, 'noimporta', 'width=600, height=500, scrollbars=NO, titlebar=no');
            }
        }

        resetForm(form);
    } else {
        handleErrorResponse(data);
        $('#btnGuardar').css('visibility', 'visible');
    }
}

function editarAsiento(form) {
    const permisosModificar = form.elements['txtPermisosAsientosContablesModificar'].value;

    if (permisosModificar === "No") {
        alert("Usted No tiene permisos. \nConsulte con el Administrador.");
        return;
    }

    const contadorFilas = parseInt($('#txtContadorFilas').val());
    let numFilas = 0;

    for (let i = 1; i <= contadorFilas; i++) {
        const txtIdDetalleLibroDiario = $(`#txtIdDetalleLibroDiario${i}`).val();
        if (parseInt(txtIdDetalleLibroDiario) > 0) {
            numFilas++;
        }
    }

    if (confirm("Seguro desea editar este Asiento Contable?")) {
        const strEAC = $("#frmAsientosContables").serialize();
        $('#btnEditar').css('visibility', 'hidden');

        $.ajax({
            url: 'sql/libroDiario.php',
            data: `${strEAC}&txtAccion=22`,
            type: 'POST',
            success: function(data) {
                showMessage(data, 'mensaje11');
                // setTimeout(() => location.reload(), 1000);
            }
        });
    }
}

function eliminarAsiento(form) {
    const permisosEliminar = form.elements['txtPermisosAsientosContablesEliminar'].value;

    if (permisosEliminar === "No") {
        alert("Usted No tiene permisos. \nConsulte con el Administrador.");
        return;
    }

    const id_libro_diario = form.elements['txtIdLibroDiario'].value;

    if (parseInt(id_libro_diario) < 1 || id_libro_diario === "") {
        alert("No hay datos para eliminar");
        return;
    }

    if (confirm("Seguro desea eliminar este Asiento Contable?")) {
        $.ajax({
            url: 'sql/libroDiario.php',
            data: `id_libro_diario=${id_libro_diario}&txtAccion=3`,
            type: 'POST',
            beforeSend: function() {
                $("#mensaje11").html("<p align='center'><img src='images/loading.gif' /></p>");
            },
            success: function(data) {
                handleEliminarResponse(data);
            }
        });
    }
}

function handleEliminarResponse(data) {
    if (data == 1) {
        $('#mensaje11').html("<div class='alert alert-success'><p>Asiento ha sido eliminado.</p></div>");
    } else {
        $('#mensaje11').html("<div class='alert alert-danger'><p>Asiento no ha sido eliminado.</p></div>");
    }

    document.frmAsientosContables.btnEliminar.disabled = true;
    document.frmAsientosContables.btnGuardar.disabled = true;
}

function handleErrorResponse(data) {
    let message = "";

    switch (data) {
        case '2':
            message = "Error al guardar los datos. Problemas con la consulta 2";
            break;
        case '3':
            message = "Error al guardar los datos. Problemas con la consulta 1";
            break;
        case '4':
            message = "El Debe y el Haber deben cumplir la partida doble.";
            break;
        case '5':
            message = "No hay suficientes cuentas para guardar.";
            break;
        default:
            message = "Error desconocido";
    }

    $('#mensaje11').html(`<div class='alert alert-danger'><p>${message}</p></div>`);
}

function showMessage(message, elementId) {
    $(`#${elementId}`).show().html(message).css('opacity', '1');
    setTimeout(() => {
        for (let i = 10; i >= 0; i--) {
            setTimeout(() => $(`#${elementId}`).css('opacity', i / 10), (10 - i) * 100);
        }
        setTimeout(() => $(`#${elementId}`).hide(), 1000);
    }, 5000);
}

function resetForm(form) {
    form.reset();
    setTimeout(() => location.reload(), 1000);
}

function listar_libro_diario(){
	var str = $("#frm_libro").serialize();
	$.ajax({
		url: 'ajax/listarLibroDiario.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#div_listar_libroDiario").html(data);
		}
	});
}

function modificarTransaccion(id_libro_diario){
//pagina: ajax/modificarTransaccion.php
    $("#div_oculto").load("ajax/modificarTransaccion.php", {id_libro_diario: id_libro_diario}, function(){
            $.blockUI({
                    message: $('#div_oculto'),
            overlayCSS: {backgroundColor: '#111'},
                    css:{

                            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',

                            background: '#f9f9f9',
                            top: '20px',
                            left: '185px',
                            position: 'absolute',
                            width: '800px'

                    }
            });
    });
}

function guardar_modificacion_transaccion(accion){
//pagina: ajax/modificarTransaccion.php
console.log("accion", accion)
    var str = $("#form").serialize();
    $.ajax({
            url: 'sql/libroDiario.php',
            data: str+'&txtAccion='+accion,
            type: 'post',
            success: function(data){
               document.getElementById('mensaje').innerHTML=""+data;
               setTimeout('fn_cerrar()', 4000);
               //fn_cerrar();
               listar_libro_diario();
            }
    });
}


function eliminarTransaccion(id_libro_diario, accion){
    var respuesta3 = confirm("Seguro desea eliminar esta Transacci\u00f3n? \nEsta acci\u00f3n eliminara de forma permanente la fila seleccionada");
    if (respuesta3){
            $.ajax({
                    url: 'sql/libroDiario.php',
                    data: 'id_libro_diario='+id_libro_diario+'&txtAccion='+accion,
                    type: 'post',
                    success: function(data){
                            if(data!="")
                            alert(data);
                            listar_libro_diario();
                    }
            });
    }
}




function listar_libro_mayor(){
	var str = $("#form").serialize();
	$.ajax({
		url: 'ajax/listarLibroMayor.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#div_listar_mayor").html(data);
		}
	});
}

function listar_balance_comprobacion(){
   
	var str = $("#frmBalanceComprobacion").serialize();
	$.ajax({
		url: 'ajax/listarBalanceComprobacion.php',
		type: 'get',
		data: str,
		success: function(data){                      
			$("#div_listar_balance_comprobacion").html(data);                       
		}
	});
}

function listar_estado_resultados(){

	var str = $("#form").serialize();
	$.ajax({
		url: 'ajax/listarEstadoResultados.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#listar_estado_resultados").html(data);
		}
	});
}

function vaciar_libro_diario(accion, periodo){
	var respuesta4 = confirm("Seguro desea vaciar la tabla libro Diario?");
	if (respuesta4){
		$.ajax({
			url: 'sql/libroDiario.php',
			data: 'txtAccion='+accion+"&periodo="+periodo,
			type: 'post',
			success: function(data){
				if(data!="")
					alert(data);
				listar_libro_diario()
			}
		});
	}
}

function planCuentas(){
    //alert("entro");
	$("#div_oculto").load("ajax/planCuentas.php", function(){
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

function librosContables(){
    //alert("entro");
	$("#div_oculto").load("ajax/librosContables.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
			css:{
                '-webkit-border-radius': '3px',
                '-moz-border-radius': '3px',
                'box-shadow': '6px 6px 20px gray',
				top: '5%',
				left: '25%',
				width: '50%',
                position: 'absolute'
			}
		});
	});
}


function libroDiario(){
    //alert("entro");
	$("#div_oculto").load("ajax/libroDiario.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
			css:{

				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',

				background: '',
				top: '5%',
				left: '25%',
				position: 'absolute'
			
			}
		});
	});
}

function libroMayor(){
    //alert("entro");
	$("#div_oculto").load("ajax/libroMayor.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
			css:{
                '-webkit-border-radius': '3px',
                '-moz-border-radius': '3px',
                'box-shadow': '6px 6px 20px gray',
				top: '5%',
				left: '25%',
				width: '50%',
                position: 'absolute'
			}
		});
	});
}

function balanceComprobacion(){
    //alert("entro");
	$("#div_oculto").load("ajax/balanceComprobacion.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
			css:{

				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',

			    background: '',
				top: '5%',
				left: '25%',
				position: 'absolute'
			}
		});
	});
}

function estadoResultados(){
    //alert("entro");
	$("#div_oculto").load("ajax/estadoResultados.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
			css:{

				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',

				background: '',
				top: '5%',
				left: '25%',
				position: 'absolute'
			}
		});
	});
}

function balanceSituacion(){
    //alert("entro");
	$("#div_oculto").load("ajax/balanceSituacion.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
			css:{

				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',

				background: '',
				top: '5%',
				left: '25%',
				position: 'absolute'
			}
		});
	});
}


function comprobantes(){
    //alert("entro");
	$("#div_oculto").load("ajax/comprobantes.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
			css:{

				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',

				background: '',
		    	top: '25%',
				left: '15%',
				position: 'absolute',
				width: '75%'
			}
		});
	});
}


//*****************  DEPARTAMENTOS  *************//

function listar_departamentos(){
    //pagina: departamentos.php
    var str = $("#form").serialize();
    $.ajax({
            url: 'ajax/listarDepartamentos.php',
            type: 'get',
            data: str,
            success: function(data){
                    $("#div_listar_departamentos").html(data);
            }
    });
}

function nuevo_departamento(){
//pagina: departamento.php
console.log("nuevo");
	$("#div_oculto").load("ajax/nuevoDepartamento.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
			css:{
				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',

				background: '#FFFFFF',
				top: '20px',
				left: '185px',
                                position: 'absolute',
				width: '450px'
			}
		});
	});
}
// function guardar_departamento(accion){
//     //pagina departamentos.php
//     var nombre = "";
//     var valor = "";
//     valor = document.form1['txtNombre'].value;
//     nombre = no_repetir_nombre_departamentos(valor, '2');//retorna 1 o 0

//     if(nombre == 0){
//             var str = $("#form1").serialize();
//             $.ajax({

//                     url: 'sql/departamentos.php',
//                     data: str+"&txtAccion="+accion,
//                     type: 'post',
//                     success: function(data){
//                     document.getElementById('mensaje1').innerHTML+=""+data;
//                     document.getElementById("form1").reset();
//                     listar_departamentos();
//                     }
//             });
//     }else {
//             alert ('No se puede guardar porque el nombre "'+document.form1['txtNombre'].value+'" ya se encuentra registrado.');
//             document.form1.txtNombre.focus();
//             document.form1.txtNombre.value="";
//             document.getElementById("noRepetirNombreDepartamento").innerHTML="";
//         }
// }
function modificar_departamento(id_departamento){
    //pagina: departamentos.php
    $("#div_oculto").load("ajax/modificarDepartamento.php", {id_departamento: id_departamento}, function(){
        $.blockUI({
                message: $('#div_oculto'),
                overlayCSS: {backgroundColor: '#111'},
                css:{
                        '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px',
                        position: 'absolute',
                        background: '#FFFFFF',
                        top: '20px',
                        left: '185px',
                        width: '450px'
                }
        });
    });
}

function guardarModificarDepartamento(accion){
 //PAGINA: departamentos.php
    var str = $("#form1").serialize();
    $.ajax({
            url: 'sql/departamentos.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data){
               document.getElementById('mensaje').innerHTML=""+data;
               listar_departamentos();
            }
    });
}

function eliminar_departamento(id_departamento, accion){
    var respuesta5 = confirm("Seguro desea eliminar este registro?");
    if (respuesta5){
        $.ajax({
            url: 'sql/departamentos.php',
            data: 'id_departamento='+id_departamento+'&txtAccion='+accion,
            type: 'post',
            success: function(data){
                    if(data!="")
                            alert(data);
                    listar_departamentos();
            }
        });
    }
}



function listar_comprobantes(){
	var str = $("#frm_comprobantes").serialize();
	$.ajax({
		url: 'ajax/listarComprobantes.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#div_listar_comprobantes").html(data);
		}
	});
}

function listar_balance_general(){
	var str = $("#form").serialize();
	$.ajax({
		url: 'ajax/listarBalanceGeneral.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#div_listar_BalanceGeneral").html(data);
		}
	});
}

//***************************   PERIODO CONTABLE   *****************************
function listar_periodo_contable(){
	var str = $("#frm_periodo_contable").serialize();        
	$.ajax({
		url: 'ajax/listarPeriodoContable.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#div_listar_periodo").html(data);
		}
	});
}

function modificarPeriodoContable(id_periodo_contable){

    $("#div_oculto").load("ajax/modificarPeriodoContable.php", {id_periodo_contable: id_periodo_contable}, function(){
            $.blockUI({
                    message: $('#div_oculto'),
                    overlayCSS: {backgroundColor: '#111'},
                    css:{
                            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
                            position: 'absolute',
                            background: '#f9f9f9',
                            top: '20px',
                            left: '185px',
                            width: '450px'
                    }
            });
    });
}

function guardar_modificacion_periodo_contable(accion){
//    document.getElementById('mensaje').innerHTML=""+codigo+" , "+nombre;
        var str = $("#form").serialize();
        $.ajax({

                url: 'sql/periodo_contable.php',
                data: str+"&accion="+accion,
                type: 'post',
                success: function(data){
                   document.getElementById('mensaje').innerHTML=""+data;

                if (data == 1){
                   document.getElementById('fechaDesdeVacio').innerHTML="";
                   document.getElementById("fechaHastaVacio").innerHTML="";
                   document.getElementById("estadoVacio").innerHTML="";
                   document.getElementById("ingresosVacio").innerHTML="";
                   document.getElementById("gastosVacio").innerHTML="";
                   document.getElementById('mensaje').innerHTML="<div class='transparent_ajax_error'><p>Fallo en el envio del Formulario: Id vacio. </p></div>";
                }
                if (data == 2){
                   document.getElementById('fechaDesdeVacio').innerHTML="<label style='color: #FF0000'>Este campo no puede estar vacio</label>";
                   document.getElementById("fechaHastaVacio").innerHTML="";
                   document.getElementById("estadoVacio").innerHTML="";
                   document.getElementById("ingresosVacio").innerHTML="";
                   document.getElementById("gastosVacio").innerHTML="";
                   document.getElementById('mensaje').innerHTML="<div class='transparent_ajax_error'><p>Fallo en el envio del Formulario: Faltan datos. </p></div>";
                }
                if (data == 3){
                   document.getElementById('fechaHastaVacio').innerHTML="<label style='color: #FF0000'>Este campo no puede estar vacio</label>";
                   document.getElementById('fechaDesdeVacio').innerHTML="";
                   document.getElementById("estadoVacio").innerHTML="";
                   document.getElementById("ingresosVacio").innerHTML="";
                   document.getElementById("gastosVacio").innerHTML="";
                   document.getElementById('mensaje').innerHTML="<div class='transparent_ajax_error'><p>Fallo en el envio del Formulario: Faltan datos. </p></div>";
                }
                if (data == 4){
                   document.getElementById('estadoVacio').innerHTML="<label style='color: #FF0000'>Este campo no puede estar vacio</label>";
                   document.getElementById('fechaDesdeVacio').innerHTML="";
                   document.getElementById("fechaHastaVacio").innerHTML="";
                   document.getElementById("ingresosVacio").innerHTML="";
                   document.getElementById("gastosVacio").innerHTML="";
                   document.getElementById('mensaje').innerHTML="<div class='transparent_ajax_error'><p>Fallo en el envio del Formulario: Faltan datos. </p></div>";
                }
                if (data == 5){
                   document.getElementById('ingresosVacio').innerHTML="<label style='color: #FF0000'>Este campo no puede estar vacio</label>";
                   document.getElementById('fechaDesdeVacio').innerHTML="";
                   document.getElementById("fechaHastaVacio").innerHTML="";
                   document.getElementById("estadoVacio").innerHTML="";
                   document.getElementById("gastosVacio").innerHTML="";
                   document.getElementById('mensaje').innerHTML="<div class='transparent_ajax_error'><p>Fallo en el envio del Formulario: Faltan datos. </p></div>";
                }
                if (data == 6){
                   document.getElementById('gastosVacio').innerHTML="<label style='color: #FF0000'>Este campo no puede estar vacio</label>";
                   document.getElementById('fechaDesdeVacio').innerHTML="";
                   document.getElementById("fechaHastaVacio").innerHTML="";
                   document.getElementById("estadoVacio").innerHTML="";
                   document.getElementById("ingresosVacio").innerHTML="";
                   document.getElementById('mensaje').innerHTML="<div class='transparent_ajax_error'><p>Fallo en el envio del Formulario: Faltan datos. </p></div>";
                }
                if (data == 7){
                   document.getElementById('mensaje').innerHTML="<div class='transparent_ajax_error'><p>Error al guarda los datos: Fallo el SQL</p></div>";
                   document.getElementById('fechaDesdeVacio').innerHTML="";
                   document.getElementById("fechaHastaVacio").innerHTML="";
                   document.getElementById("estadoVacio").innerHTML="";
                   document.getElementById("ingresosVacio").innerHTML="";
                   document.getElementById("gastosVacio").innerHTML="";
                }
                if (data == 8){
                   document.getElementById('mensaje').innerHTML="<div class='transparent_ajax_correcto'><p>Registro insertado correctamente.</p></div>";
                   document.getElementById('fechaDesdeVacio').innerHTML="";
                   document.getElementById("fechaHastaVacio").innerHTML="";
                   document.getElementById("estadoVacio").innerHTML="";
                   document.getElementById("ingresosVacio").innerHTML="";
                   document.getElementById("gastosVacio").innerHTML="";
                   listar_periodo_contable();
                   setTimeout('fn_cerrar()', 3000);
                }

                 listar_periodo_contable();
                }
        });
}

function estadoPeriodo(id_periodo, accion){
    var respuesta6 = confirm("Seguro desea Cerrar/Activar este Periodo Contable? \nEsta acci\u00f3n Suspendera/Activara de forma permanente la fila seleccionada");
	if (respuesta6){
		$.ajax({
			url: 'sql/periodo_contable.php',
			data: 'id_periodo=' +id_periodo+'&accion='+accion,
			type: 'post',
			success: function(data){
				if(data!="")
				//alert(data);
                                document.getElementById("mensaje1").innerHTML=""+data;
				listar_periodo_contable();
			}
		});
	}
}

function eliminarPeriodoContable(id_periodo, accion){
    var respuesta7 = confirm("Seguro desea eliminar este registro?");
    if (respuesta7){
        $.ajax({
                url: 'sql/periodo_contable.php',
                data: 'id_periodo='+id_periodo+'&accion='+accion,
                type: 'post',
                success: function(data){
                        if(data!="")
                                alert(data);
                        listar_periodo_contable();
                }
        });
    }
}

function generar_comparacion(){
	var str = $("#form").serialize();        
	$.ajax({
		url: 'columnSimple.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#div_listar_comparacion").html(data);
		}
	});
}


//*****************  EMPLEADOS  *************//


function listar_empleados(page=1){
// funcion listar_empleados funciona en la pagina: empleados.php
	var str = $("#form_Empleados").serialize();
	$.ajax({
		url: 'ajax/listarEmpleados.php?page='+page,
		type: 'get',
		data: str,
		success: function(data){
			$("#div_listar_empleados").html(data);
		}
	});
}

function nuevoEmpleado(){
// funcion nuevoEmpleado funciona en la pagina: empleados.php
console.log("nuevoEmpleado");
	$("#div_oculto").load("ajax/nuevoEmpleado.php", function(){
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


function modificarEmpleado(id_empleado){
    window.location.href = "nuevoEmpleado.php?id_empleado="+id_empleado;
}

// function modificarEmpleado(id_empleado){
// // funcion modifica empleados funciona en la pagina: empleados.php
// 	$("#div_oculto").load("ajax/modificarEmpleado.php", {id_empleado: id_empleado}, function(){
// 		$.blockUI({
// 			message: $('#div_oculto'),
// 		overlayCSS: {backgroundColor: '#111'},
// 			css:{

// 				'-webkit-border-radius': '10px',
//                 '-moz-border-radius': '10px',

// 				background: '#f9f9f9',
// 				top: '20px',
// 				left: '185px',
// 				position: 'absolute',
// 				width: '690px'

// 			}
// 		});
// 	});
// }

function eliminarEmpleado(id_empleado, accion){
    console.log("id_empleado",id_empleado,"accion",accion);
    var respuesta8 = confirm("Seguro desea eliminar este registro?");
    if (respuesta8){
        $.ajax({
                url: 'sql/empleados.php',
                data: 'id_empleado='+id_empleado+'&txtAccion='+accion,
                type: 'post',
                success: function(data){
                        //if(data!="")
                        alert(data);
                        listar_empleados();
                        listar_usuarios_empresa();
                }
        });
    }
}

function suspenderEmpleado(id_empleado, accion){
    console.log(id_empleado,"accion",accion);
    var respuesta9 = confirm("Seguro desea Suspender/Activar este empleado? \nEsta acci\u00f3n Suspendera/Activar de forma permanente la fila seleccionada");
	if (respuesta9){
		$.ajax({
			url: 'sql/empleados.php',
			data: 'id_empleado=' +id_empleado+'&txtAccion='+accion,
			type: 'post',
			success: function(data){
				if(data!="")
				//alert(data);
                    //document.getElementById("mensaje1").innerHTML=""+data;
                     alertify.success("Empleado actualizado");
				listar_empleados();
			}
		});
	}
}

function suspenderEmpresa(id_empresa, accion){
    console.log(id_empresa,"accion",accion);
    var respuesta9 = confirm("Seguro desea Suspender/Activar esta empresa? \nEsta acci\u00f3n Suspendera/Activar de forma permanente la fila seleccionada");
	
	if (respuesta9){
		$.ajax({
			url: 'sql/empresa.php',
			data: 'id_empresa='+id_empresa+'&accion='+accion,
			type: 'post',
			success: function(data){
			    
			    console.log("data",data);
				if(data!=""){
				    alertify.success(data);
				    listarEmpresas(1);
				}
			}
		});
	}
}

function pagoEmpresa(id_empresa, accion){
    console.log(id_empresa,"accion",accion);
    var respuesta9 = confirm("Seguro desea Suspender/Activar esta empresa? \nEsta acci\u00f3n Suspendera/Activar de forma permanente la fila seleccionada");
	
	if (respuesta9){
		$.ajax({
			url: 'sql/empresa.php',
			data: 'id_empresa='+id_empresa+'&accion='+accion,
			type: 'post',
			success: function(data){
			    
			    console.log("data",data);
				if(data!=""){
				    alertify.success(data);
				    listarEmpresas(1);
				}
			}
		});
	}
}

//*****************  USUARIOS  *************//


function listarUsuarios(){
// funcion listarUsuarios funciona en la pagina: usuarios.php
console.log("USUATIO");
	var str = $("#formUsuarios").serialize();
	$.ajax({
		url: 'ajax/listarUsuarios.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#div_listar_usuarios").html(data);
		}
	});
}

function nuevoUsuario(){
// funcion nuevoUsuario funciona en la pagina: usuarios.php
	$("#div_oculto").load("ajax/nuevoUsuario.php", function(){
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

function modificarUsuario(id_usuario){
// funcion modifica usuarios funciona en la pagina: ajax/modificarUsuario.php
	$("#div_oculto").load("ajax/modificarUsuario.php", {id_usuario: id_usuario}, function(){
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
function modificarUsuarioEmpresa(id_usuario){
// funcion modifica usuarios funciona en la pagina: ajax/modificarUsuario.php
	$("#div_oculto").load("ajax/modificarUsuarioEmpresa.php", {id_usuario: id_usuario}, function(){
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

function eliminarUsuario(id_usuario, accion){
    var respuesta10 = confirm("Seguro desea eliminar este registro?");
    if (respuesta10){
        $.ajax({
                url: 'sql/usuarios.php',
                data: 'id_usuario='+id_usuario+'&txtAccion='+accion,
                type: 'post',
                success: function(data){
                        if(data!="")
                                alert(data);
                        listarUsuarios();
                        listar_usuarios_empresa();
                }
        });
    }
}

function suspenderUsuario(id_usuario, accion, nombre){
    var respuesta11 = confirm("Seguro desea Suspender/Activar este empleado? \nEsta acci\u00f3n Suspendera/Activar de forma permanente la fila seleccionada");
	if (respuesta11){
		$.ajax({
			url: 'sql/usuarios.php',
			data: 'id_usuario=' +id_usuario+'&txtAccion='+accion+'&nombre='+nombre,
			type: 'post',
			success: function(data){
				if(data!="")
				//alert(data);
                                document.getElementById("mensaje1").innerHTML=""+data;
				listarUsuarios();
                                listar_usuarios_empresa();
			}
		});
	}
}

//*****************  CARGOS  *************//
function listar_cargos(){
	var str = $("#form").serialize();
	$.ajax({
		url: 'ajax/listarCargos.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#div_listar_cargos").html(data);
		}
	});
}

function nuevo_cargo(){
//    alert("entro");
    $("#div_oculto").load("ajax/nuevoCargo.php", function(){
        $.blockUI({
                message: $('#div_oculto'),
        overlayCSS: {backgroundColor: '#111'},
                css:{
                        '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px',

                        background: '#FFFFFF',
                        top: '20px',
                        left: '185px',
                        position: 'absolute',
                        width: '450px'
                }
        });
    });
}

function guardar_cargo(accion){

    var nombre = "";
    var valor = "";
    valor = document.form1['txtNombre'].value;
    nombre = no_repetir_nombre_cargo(valor, '2');//retorna 1 o 0

    if(nombre == 0){

            var str = $("#form1").serialize();
            $.ajax({

                    url: 'sql/cargos.php',
                    data: str+"&txtAccion="+accion,
                    type: 'post',
                    success: function(data){
                    document.getElementById('mensaje1').innerHTML+=""+data;
                    document.getElementById("form1").reset();
                    listar_cargos();
                    }
            });
    }else {
            alert ('No se puede guardar porque el nombre "'+document.form1['txtNombre'].value+'" ya se encuentra registrado.');
            document.form1.txtNombre.focus();
            document.form1.txtNombre.value="";
            document.getElementById("noRepetirNombreCargo").innerHTML="";
        }
}


function modificar_cargo(id_cargo){
    //PAGINA: cargos.php
    $("#div_oculto").load("ajax/modificarCargo.php", {id_cargo: id_cargo}, function(){
        $.blockUI({
                message: $('#div_oculto'),
                overlayCSS: {backgroundColor: '#111'},
                css:{
                        '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px',
                        position: 'absolute',
                        background: '#f9f9f9',
                        top: '20px',
                        left: '185px',
                        width: '450px'
                }
        });
    });
}


function guardarModificarCargo(accion){
 //PAGINA: cargos.php
    var str = $("#form1").serialize();
    $.ajax({

            url: 'sql/cargos.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data){
            document.getElementById('mensaje1').innerHTML+=""+data;
            listar_cargos();
            }
    });

}

function eliminarCargo(id_cargo, accion){
    var respuesta12 = confirm("Seguro desea eliminar este registro?");
    if (respuesta12){
        $.ajax({
                url: 'sql/cargos.php',
                data: 'id_cargo='+id_cargo+'&txtAccion='+accion,
                type: 'post',
                success: function(data){
                        if(data!="")
                                alert(data);
                        listar_cargos();
                }
        });
    }
}

function ver_empleado(){
//PAGINA: cargos.php
    $("#div_oculto").load("ajax/verEmpleado.php", function(){
        $.blockUI({
                message: $('#div_oculto'),
        overlayCSS: {backgroundColor: '#111'},
                css:{
                        '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px',

                        background: '#FFFFFF',
                        top: '20px',
                        left: '185px',
                        position: 'absolute',
                        width: '450px'

                }
        });
    });
}


//***************** ASIGNACION DE EMPLEADOS  *************//

function listar_asignacion_empleados(){
    //PAGINA: asignacionEmpleados.php
	var str = $("#form").serialize();
	$.ajax({
		url: 'ajax/listarAsignacionEmpleados.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#div_listar_AsignacionEmpleados").html(data);
		}
	});
}

function nuevo_asignacionEmple(){
//PAGINA: asignacionEmpleados.php
    $("#div_oculto").load("ajax/nuevaAsignacionEmpleado.php", function(){
        $.blockUI({
                message: $('#div_oculto'),
        overlayCSS: {backgroundColor: '#111'},
                css:{
                        '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px',

                        background: '#FFFFFF',
                        top: '20px',
                        left: '185px',
                        position: 'absolute',
                        width: '450px'

                }
        });
    });
}

function modificar_asignacion(id_asignacion_empleado){
//PAGINA: asignacionEmpleados.php
	$("#div_oculto").load("ajax/modificarAsignacionEmpleado.php", {id_asignacion_empleado: id_asignacion_empleado},  function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
			css:{
				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',

				background: '#FFFFFF',
				top: '20px',
				left: '185px',
                                position: 'absolute',
				width: '450px'

			}
		});
	});
}

function suspender_asignacion(id_asignacion_empleado, accion){
    //PAGINA: asignacionEmpleados.php
     var respuesta13 = confirm("Seguro desea Suspender/Activar esta Asignaci\u00f3n? \nEsta acci\u00f3n Suspendera/Activar de forma permanente la fila seleccionada");
	if (respuesta13){
		$.ajax({
			url: 'sql/asignacion_empleados.php',
			data: 'id_asignacion_empleado=' +id_asignacion_empleado+'&txtAccion='+accion,
			type: 'post',
			success: function(data){
                            if(data!="")
                            //alert(data);
                            document.getElementById("mensaje1").innerHTML=""+data;
                            listar_asignacion_empleados();
			}
		});
	}
}


function eliminarAsigEmpleado(id_asignacion_empleado, accion){
    //PAGINA: asignacionEmpleados.php
    var respuesta14 = confirm("Seguro desea eliminar este registro?");
    if (respuesta14){
        $.ajax({
                url: 'sql/asignacion_empleados.php',
                data: 'id_asignacion_empleado='+id_asignacion_empleado+'&txtAccion='+accion,
                type: 'post',
                success: function(data){
                        if(data!="")
                         alert(data);
                         document.getElementById("mensaje1").innerHTML=""+data;
                        listar_asignacion_empleados();
                }
        });
    }
}

//***************** REGISTRO DIARIO (hora entrada, hora salida)   *************//

function listar_registro_diario(){
     //PAGINA: registroDiario.php
	var str = $("#form").serialize();
	$.ajax({
		url: 'ajax/listarRegistroDiario.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#div_listar_RegistroDiario").html(data);
		}
	});
}


function nuevo_registroDiario(){
 //PAGINA: registroDiario.php
	$("#div_oculto").load("ajax/nuevoRegistroDiario.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
			css:{
				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',

				background: '#FFFFFF',
				top: '20px',
				left: '185px',
                                position: 'absolute',
				width: '450px'

			}
		});
	});
}

function hora_entrada(){
 //PAGINA: asistencia.php
    $("#div_oculto").load("ajax/horaEntrada.php", function(){
            $.blockUI({
                    message: $('#div_oculto'),
            overlayCSS: {backgroundColor: '#111'},
                    css:{
                            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',

                            background: '#FFFFFF',
                            top: '20px',
                            left: '185px',
                            position: 'absolute',
                            width: '450px'

                    }
            });
    });
}

function guardar_hora_entrada(accion){

    var str = $("#form1").serialize();
    $.ajax({
            url: 'sql/registroDiario.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data){
            document.getElementById('mensaje11').innerHTML+=""+data;
            document.getElementById("form1").reset();
            listar_registro_diario();
            }
    });
}

function hora_salida(){
    //PAGINA: asistencia.php
    $("#div_oculto").load("ajax/horaSalida.php", function(){
            $.blockUI({
                    message: $('#div_oculto'),
            overlayCSS: {backgroundColor: '#111'},
                    css:{
                            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',

                            background: '#FFFFFF',
                            top: '20px',
                            left: '185px',
                            position: 'absolute',
                            width: '450px'

                    }
            });
    });
}

function guardar_hora_salida(accion){

    var str = $("#form1").serialize();
    $.ajax({
            url: 'sql/registroDiario.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data){
            document.getElementById('mensaje11').innerHTML+=""+data;
            document.getElementById("form1").reset();
            listar_registro_diario();
            }
    });
}

function modificar_registro_diario(id){
 //PAGINA: registroDiario.php
	$("#div_oculto").load("ajax/modificarRegistroDiario.php", {id_detalle_registro_diario: id}, function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
			css:{
				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',

				background: '#FFFFFF',
				top: '20px',
				left: '185px',
                                position: 'absolute',
				width: '450px'

			}
		});
	});
}

function eliminar_registro_diario(id_detalle_registro, accion){
     //PAGINA: registroDiario.php
    var respuesta15 = confirm("Seguro desea eliminar este Registro? \nEsta acci\u00f3n eliminara de forma permanente la fila seleccionada");
    if (respuesta15){
            $.ajax({
                    url: 'sql/registroDiario.php',
                    data: 'id_detalle_registro=' +id_detalle_registro+'&txtAccion='+accion,
                    type: 'post',
                    success: function(data){
                            if(data!="")
                            alert(data);
                            listar_registro_diario();
                    }
            });
    }

}

//************************* PARAMETROS / REGISTRO DIARIO PAGINA: registroDiario.php   ************//

function paramRegistroDiario(){
 //PAGINA: registroDiario.php
    $("#div_oculto").load("ajax/parametrosRegistroDiario.php", function(){
        $.blockUI({
                message: $('#div_oculto'),
        overlayCSS: {backgroundColor: '#111'},
                css:{
                        '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px',

                        background: '#FFFFFF',
                        top: '20px',
                        left: '185px',
                        position: 'absolute',
                        width: '550px'
                }
        }); 
    });
}

//function listarParamRegistroDiario(){
// //PAGINA: registroDiario.php
//    var str = $("#form").serialize();
//    $.ajax({
//            url: 'ajax/listarParamRegistroDiario.php',
//            type: 'get',
//            data: str,
//            success: function(data){
//                $("#div_listar_C").html(data);
//                 cantidad_filas_categorias();
//            }
//    });
//}

//function guardarParamRegistroDiario(nFila, accion){
//    //PAGINA: registroDiario.php
//    if(document.getElementById("txtCategoria"+nFila).value==""){
//        alert("Faltan Campos por llenar");
//
//    }else{
//        var respuesta = confirm("Seguro desea guardar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
//        if (respuesta){
//        var str = $("#form").serialize();
//            $.ajax({
//                    url: 'sql/registroDiario.php',
//                    type: 'post',
//                    data: str+"&numeroFilaSelec="+nFila+"&txtAccion="+accion,
//                    success: function(data){
//                            //$("#div_listar_RegistroDiario").html(data);
//                            document.getElementById("mensaje1").innerHTML=""+data;
//                            listar_categorias();
//                    }
//            });
//           }
//    }
//}

function modificarParamRegistroDiario(accion){
    //PAGINA: registroDiario.php
    var respuesta16 = confirm("Seguro desea modificar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
    if (respuesta16){
    var str = $("#form1").serialize();
	$.ajax({
		url: 'sql/registroDiario.php',
		type: 'post',
		data: str+"&txtAccion="+accion,
		success: function(data){
			//$("#div_listar_RegistroDiario").html(data);
                        document.getElementById("mensaje").innerHTML=""+data;                        
		}
	});
       }
}


//***************** INGRESOS GRAVABLES  *************//

function listar_ingresos_gravables(){
 //PAGINA: ingresosGravables.php
    var str = $("#form").serialize();
    $.ajax({
            url: 'ajax/listarIngresosGravables.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#div_listar_RegistroDiario").html(data);
                 cantidad_filas();
            }
    });
}

function guardar_ingresos_gravables(nFila){
    //PAGINA: ingresosGravables.php
    if(document.getElementById("desde"+nFila).value=="" || document.getElementById("hasta"+nFila).value=="" || document.getElementById("imp_fd"+nFila).value=="" || document.getElementById("imp_exd"+nFila).value==""){
        alert("Faltan Campos por llenar");
        
    }else{
        var respuesta17 = confirm("Seguro desea guardar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
        if (respuesta17){
        var str = $("#form").serialize();
            $.ajax({
                    url: 'sql/ingresos_gravables.php',
                    type: 'post',
                    data: str+"&numeroFilaSelec="+nFila,
                    success: function(data){
                            //$("#div_listar_RegistroDiario").html(data);
                            document.getElementById("mensaje1").innerHTML=""+data;
                            listar_ingresos_gravables();
                    }
            });
           }
    }
}

function modificar_ingresos_gravables(id, accion, fila){
    //PAGINA: ingresosGravables.php
    var respuesta18 = confirm("Seguro desea modificar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
    if (respuesta18){
    var str = $("#form").serialize();
	$.ajax({
		url: 'sql/ingresos_gravables.php',
		type: 'post',
		data: str+"&idIngresoGravable="+id+"&txtAccion="+accion+"&NumeroFilaSeleccionada="+fila,
		success: function(data){
			//$("#div_listar_RegistroDiario").html(data);
                        document.getElementById("mensaje1").innerHTML=""+data;
                        listar_ingresos_gravables();
		}
	});
       }
}

function eliminar_ingresos_gravables(id, accion){
    //PAGINA: ingresosGravables.php
    var respuesta19 = confirm("Seguro desea eliminar este Registro? \nEsta acci\u00f3n eliminara de forma permanente la fila seleccionada");
    if (respuesta19){
            $.ajax({
                    url: 'sql/ingresos_gravables.php',
                    data: 'idIngresoGravable='+id+'&txtAccion='+accion,
                    type: 'post',
                    success: function(data){
                            if(data!="")
                            //alert(data);
                            document.getElementById("mensaje1").innerHTML=""+data;
                            listar_ingresos_gravables();
                    }
            });
    }
}

//***************** SUBSIDIO ANTIGUEDAD   *************//

function listar_subsidio_antiguedad(){
 //PAGINA: subAntiguedad.php
    var str = $("#form").serialize();
    $.ajax({
            url: 'ajax/listarSubAntiguedad.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#div_listar_SA").html(data);
                 cantidad_filas_SubAntiguedad();
            }
    });
}

function guardar_subsidio_antiguedad(nFila){
    //PAGINA: subAntiguedad.php
    if(document.getElementById("desde"+nFila).value=="" || document.getElementById("hasta"+nFila).value=="" || document.getElementById("porcentaje"+nFila).value==""){
        alert("Faltan Campos por llenar");

    }else{
        var respuesta20 = confirm("Seguro desea guardar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
        if (respuesta20){
        var str = $("#form").serialize();
            $.ajax({
                    url: 'sql/subsidio_antiguedad.php',
                    type: 'post',
                    data: str+"&numeroFilaSelec="+nFila,
                    success: function(data){
                            //$("#div_listar_RegistroDiario").html(data);
                            document.getElementById("mensaje1").innerHTML=""+data;
                            listar_subsidio_antiguedad();
                    }
            });
           }
    }
}

function modificar_subsidio_antiguedad(id, accion, fila){
    //PAGINA: subAntiguedad.php
    var respuesta21 = confirm("Seguro desea modificar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
    if (respuesta21){
    var str = $("#form").serialize();
	$.ajax({
		url: 'sql/subsidio_antiguedad.php',
		type: 'post',
		data: str+"&idSubAnt="+id+"&txtAccion="+accion+"&NumeroFilaSeleccionada="+fila,
		success: function(data){
			//$("#div_listar_RegistroDiario").html(data);
                        document.getElementById("mensaje1").innerHTML=""+data;
                        listar_subsidio_antiguedad();
		}
	});
       }
}

function eliminar_subsidio_antiguedad(id, accion){
    //PAGINA: subAntiguedad.php
    var respuesta22 = confirm("Seguro desea eliminar este Registro? \nEsta acci\u00f3n eliminara de forma permanente la fila seleccionada");
    if (respuesta22){
            $.ajax({
                    url: 'sql/subsidio_antiguedad.php',
                    data: 'idSubAnt='+id+'&txtAccion='+accion,
                    type: 'post',
                    success: function(data){
                            if(data!="")
                            //alert(data);
                            document.getElementById("mensaje1").innerHTML=""+data;
                            listar_subsidio_antiguedad();
                    }
            });
    }
}

//***************** SUBSIDIO FAMILIAR   *************//

function listar_subsidio_familiar(){
 //PAGINA: subFamiliar.php
    var str = $("#form").serialize();
    $.ajax({
            url: 'ajax/listarSubFamiliar.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#div_listar_SF").html(data);
                 cantidad_filas_SubFami();
            }
    });
}

function guardar_subsidio_familiar(nFila){
    //PAGINA: subFamiliar.php
    if(document.getElementById("desde"+nFila).value=="" || document.getElementById("hasta"+nFila).value=="" || document.getElementById("porcentaje"+nFila).value==""){
        alert("Faltan Campos por llenar");

    }else{
        var respuesta23 = confirm("Seguro desea guardar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
        if (respuesta23){
        var str = $("#form").serialize();
            $.ajax({
                    url: 'sql/subsidio_familiar.php',
                    type: 'post',
                    data: str+"&numeroFilaSelec="+nFila,
                    success: function(data){
                            //$("#div_listar_RegistroDiario").html(data);
                            document.getElementById("mensaje1").innerHTML=""+data;
                            listar_subsidio_familiar();
                    }
            });
           }
    }
}

function modificar_subsidio_familiar(id, accion, fila){
    //PAGINA: subFamiliar.php
    var respuesta24 = confirm("Seguro desea modificar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
    if (respuesta24){
    var str = $("#form").serialize();
	$.ajax({
		url: 'sql/subsidio_familiar.php',
		type: 'post',
		data: str+"&idSubFam="+id+"&txtAccion="+accion+"&NumeroFilaSeleccionada="+fila,
		success: function(data){
			//$("#div_listar_RegistroDiario").html(data);
                        document.getElementById("mensaje1").innerHTML=""+data;
                        listar_subsidio_familiar();
		}
	});
       }
}

function eliminar_subsidio_familiar(id, accion){
    //PAGINA: subFamiliar.php
    var respuesta25 = confirm("Seguro desea eliminar este Registro? \nEsta acci\u00f3n eliminara de forma permanente la fila seleccionada");
    if (respuesta25){
            $.ajax({
                    url: 'sql/subsidio_familiar.php',
                    data: 'idSubFam='+id+'&txtAccion='+accion,
                    type: 'post',
                    success: function(data){
                            if(data!="")
                            //alert(data);
                            document.getElementById("mensaje1").innerHTML=""+data;
                            listar_subsidio_familiar();
                    }
            });
    }
}


//***************** PARAMETROS ATRASOS   *************//

function listar_atrasos(){
 //PAGINA: parametrosAtrasos.php
    var str = $("#form").serialize();
    $.ajax({
            url: 'ajax/listarParametrosAtrasos.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#div_listar_atrasos").html(data);
                 cantidad_filas_atrasos();                 
            }
    });
}

function guardar_atrasos(nFila){
    //PAGINA: parametrosAtrasos.php
    if(document.getElementById("txtDesde"+nFila).value=="" || document.getElementById("txtHasta"+nFila).value=="" || document.getElementById("txtPorcentaje"+nFila).value==""){
        alert("Faltan Campos por llenar");

    }else{
        var respuesta26 = confirm("Seguro desea guardar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
        if (respuesta26){
        var str = $("#form").serialize();
            $.ajax({
                    url: 'sql/parametros_atrasos.php',
                    type: 'post',
                    data: str+"&numeroFilaSelec="+nFila,
                    success: function(data){
                            //$("#div_listar_RegistroDiario").html(data);
                            document.getElementById("mensaje1").innerHTML=""+data;
                            listar_atrasos();
                    }
            });
           }
    }
}

function modificar_atrasos(id, accion, fila){
    //PAGINA: parametrosAtrasos.php
    var respuesta27 = confirm("Seguro desea modificar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
    if (respuesta27){
    var str = $("#form").serialize();
	$.ajax({
		url: 'sql/parametros_atrasos.php',
		type: 'post',
		data: str+"&idParaAtrasos="+id+"&txtAccion="+accion+"&NumeroFilaSeleccionada="+fila,
		success: function(data){
			//$("#div_listar_RegistroDiario").html(data);
                        document.getElementById("mensaje1").innerHTML=""+data;
                        listar_atrasos();
		}
	});
       }
}

function eliminar_atrasos(id, accion){
    //PAGINA: parametrosAtrasos.php
    var respuesta28 = confirm("Seguro desea eliminar este Registro? \nEsta acci\u00f3n eliminara de forma permanente la fila seleccionada");
    if (respuesta28){
            $.ajax({
                    url: 'sql/parametros_atrasos.php',
                    data: 'idParaAtrasos='+id+'&txtAccion='+accion,
                    type: 'post',
                    success: function(data){
                            if(data!="")
                            //alert(data);
                            document.getElementById("mensaje1").innerHTML=""+data;
                            listar_atrasos();
                    }
            });
    }
}


//***************** PARAMETROS COMISIONES   *************//

function listar_parametros_comisiones(){
 //PAGINA: parametrosComisiones.php
    var str = $("#form").serialize();
    $.ajax({
            url: 'ajax/listarParametrosComisiones.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#div_listar_PC").html(data);
                 cantidad_filas_Pcomisiones();
            }
    });
}

function guardar_parametros_comisiones(nFila){
    
    //PAGINA: parametrosComisiones.php
    if(document.getElementById("txtDesde"+nFila).value=="" || document.getElementById("txtHasta"+nFila).value=="" || document.getElementById("txtPorcentaje"+nFila).value==""){
        alert("Faltan Campos por llenar");

    }else{
        var respuesta29 = confirm("Seguro desea guardar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
        if (respuesta29){
        var str = $("#form").serialize();
            $.ajax({
                    url: 'sql/parametrosComisiones.php',
                    type: 'post',
                    data: str+"&numeroFilaSelec="+nFila,
                    success: function(data){
                        //$("#div_listar_RegistroDiario").html(data);
                        document.getElementById("mensaje1").innerHTML=""+data;
                        listar_parametros_comisiones();
                    }
            });
           }
    }
}

function modificar_parametros_comisiones(id, accion, fila){
    //PAGINA: parametrosComisiones.php
    var respuesta30 = confirm("Seguro desea modificar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
    if (respuesta30){
    var str = $("#form").serialize();
	$.ajax({
		url: 'sql/parametrosComisiones.php',
		type: 'post',
		data: str+"&idComisiones="+id+"&txtAccion="+accion+"&NumeroFilaSeleccionada="+fila,
		success: function(data){
                    //$("#div_listar_RegistroDiario").html(data);
                    document.getElementById("mensaje1").innerHTML=""+data;
                    listar_parametros_comisiones();
		}
	});
       }
}

function eliminar_parametros_comisiones(id, accion){
    //PAGINA: parametrosComisiones.php
    var respuesta31 = confirm("Seguro desea eliminar este Registro? \nEsta acci\u00f3n eliminara de forma permanente la fila seleccionada");
    if (respuesta31){
        $.ajax({
                url: 'sql/parametrosComisiones.php',
                data: 'idComisiones='+id+'&txtAccion='+accion,
                type: 'post',
                success: function(data){
                    if(data!="")
                    //alert(data);
                    document.getElementById("mensaje1").innerHTML=""+data;
                    listar_parametros_comisiones();
                }
        });
    }
}


//*****************  PRESTAMOS  *************//

function listar_prestamos(){
// funcion listar_prestamos funciona en la pagina: prestamos.php
    var str = $("#form").serialize();
    $.ajax({
            url: 'ajax/listarPrestamos.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#div_listar_prestamos").html(data);
            }
    });
}

function nuevoPrestamo(){
// funcion nuevo prestamo funciona en la pagina: prestamos.php
    $("#div_oculto").load("ajax/nuevoPrestamo.php", function(){
        $.blockUI({
                message: $('#div_oculto'),
        overlayCSS: {backgroundColor: '#111'},
                css:{

                    '-webkit-border-radius': '10px',
    '-moz-border-radius': '10px',

                    background: '#f9f9f9',
                    top: '20px',
                    left: '185px',
                    position: 'absolute',
                    width: '690px'
                }
        });
    });
}

function guardar_prestamo(accion){
    var respuesta32 = confirm("Seguro desea guardar este registro?");
    if (respuesta32){
    var str = $("#formnp").serialize();
        $.ajax({
                url: 'sql/prestamos.php',
                type: 'post',
                data: str+"&txtAccion="+accion,
                success: function(data){                        
                        document.getElementById("mensaje1").innerHTML=""+data;
                        listar_prestamos();
                        document.getElementById("formnp").reset();
                }
        });
   }

}


function guardar_pago_cuota(accion){
    var resp = "";
    valorPagado = document.frmpc['txtPago'];
    pagoMin = document.frmpc['txtCuota'];
    tSaldo = document.frmpc['txtSaldo'];
    resp = pagoMinimo(valorPagado, pagoMin, tSaldo);//retorna 1 o 0    
    if(resp == 0){
        var respuesta33 = confirm("Seguro desea guardar este registro?");
        if (respuesta33){
        var str = $("#frmpc").serialize();
            $.ajax({
                    url: 'sql/prestamos.php',
                    type: 'post',
                    data: str+"&txtAccion="+accion,
                    success: function(data){
                            document.getElementById("mensajepc").innerHTML=""+data;
                            listar_prestamos();
                            setTimeout('document.location.reload()', 4000);
                            document.getElementById("frmpc").reset();
                    }
            });
        }
    }else{
        alert ('No se puede guardar porque el valor ingresado no es correcto.');
        document.frmpc.txtPago.focus();
        document.frmpc.txtPago.value="";
        document.getElementById("validaPagoMin").innerHTML="";
    }
}

function modificarPrestamo(id_prestamo){
// funcion modifica prestamo funciona en la pagina: ajax/modificarPrestamo.php
    $("#div_oculto").load("ajax/modificarPrestamo.php", {id_prestamo: id_prestamo}, function(){
            $.blockUI({
                    message: $('#div_oculto'),
            overlayCSS: {backgroundColor: '#111'},
                    css:{

                            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',

                            background: '#f9f9f9',
                            top: '20px',
                            left: '185px',
                            position: 'absolute',
                            width: '690px'
                    }
            });
    });
}


//***************** PARAMETROS ROL PAGOS  *************//

function guardar_param_rol_pagos(id, accion){

    //PAGINA: parametrosRolPagos.php
    if(document.getElementById("txtPorcentajeFondoReserva").value=="" || document.getElementById("txtAportePersonal").value== 0 ){
        alert("Faltan Campos por llenar");

    }else{
        var respuesta34 = confirm("Seguro desea guardar este Registro?");
        if (respuesta34){
        var str = $("#form").serialize();
            $.ajax({
                    url: 'sql/parametrosRolPagos.php',
                    type: 'post',
                    data: str+"&idParamRolPagos="+id+"&txtAccion="+accion,
                    success: function(data){
                            //$("#div_listar_RegistroDiario").html(data);
                            document.getElementById("mensaje1").innerHTML=""+data;
                            //listar_parametros_rol_pagos();
                    }
            });
           }
    }
}

//***************** ROL PAGOS  *************//
function listar_rol_pagos(){

	var str = $("#frmRolPagos").serialize();
	$.ajax({
		url: 'ajax/listarRolPagos.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#listar_rol_pagos").html(data);

		}
	});
}

function guardar_rol_pagos(accion){   
    
    //PAGINA: sql/rol_pagos.php
    var respuesta35 = confirm("Seguro desea guardar este Registro?");
    if (respuesta35){
    var str = $("#frmRolPagos").serialize();
        $.ajax({
            url: 'sql/rol_pagos.php',
            type: 'post',
            data: str+"&txtAccion="+accion,
            success: function(data){
                    document.getElementById("mensaje1").innerHTML=""+data;
                    document.getElementById("btnEnviar").style.display = "none";
                    if(data == "Registros insertados correctamente."){
                        alert("Registros insertados correctamente.");
                    }
                    
            }
        });
   }
}

// function listar_todos_roles_pagos(){
//      //PAGINA: todosRolPagos.php
// 	var str = $("#frmTodosRolesPagos").serialize();
// 	$.ajax({
// 		url: 'ajax/listarTRolesPagos.php',
// 		type: 'get',
// 		data: str,
// 		success: function(data){
// 			$("#div_listar_TRolesPagos").html(data);
// 		}
// 	});
// }

function modificar_rol_pago(id_detalle){
// funcion modifica rol pagos en la pagina: todosRolpagos.php
    $("#div_oculto").load("ajax/modificarRolPago.php", {id_detalle: id_detalle}, function(){
            $.blockUI({
                    message: $('#div_oculto'),
            overlayCSS: {backgroundColor: '#111'},
                    css:{

                            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',

                            background: '#f9f9f9',
                            top: '20px',
                            left: '185px',
                            position: 'absolute',
                            width: '650px'
                    }
            });
    });
}

function guardar_modificar_rol_pagos(accion){
     //PAGINA: ajax/modificarRegistroDiario.php     
     total_neto = document.frmMRolPagos['txtTotalNeto'].value;     
     if(total_neto > 0){
        var str = $("#frmMRolPagos").serialize();
        $.ajax({
                url: 'sql/rol_pagos.php',
                data: str+"&txtAccion="+accion,
                type: 'post',
                success: function(data){
                   document.getElementById('mensaje11').innerHTML=""+data;
                   listar_todos_roles_pagos();
                }
        });
     }else{
         alert ('No se puede guardar, existen datos erroneos');}
}


function eliminar_roles_mensuales(accion, ano, mes){
    //PAGINA: ajax/listarTRolesPagos.php
    var respuesta36 = confirm("Seguro desea eliminar este registro?");
    if (respuesta36){
        $.ajax({
            url: 'sql/rol_pagos.php',
            data: 'ano='+ano+'&mes='+mes+'&txtAccion='+accion,
            type: 'post',
            success: function(data){
                if(data!="")
                    if(data == '1'){
                        document.getElementById('mensaje1').innerHTML="<div class='transparent_ajax_error'><p>Ocurrio un error al eliminar: \n</p></div>";
                        alert("Ocurrio un error al eliminar");
                    }
                    if(data == '2'){
                        document.getElementById('mensaje1').innerHTML="<div class='transparent_ajax_correcto'><p>El registro mensual de Roles ha sido Eliminado.</p></div>";
                        alert("El registro ha sido Eliminado");
                    }                    
                    listar_todos_roles_pagos();
            }
        });
    }
}

function eliminar_rol_pago(id, accion){
    //PAGINA: ajax/listarTRolesPagos.php
    var respuesta37 = confirm("Seguro desea eliminar este registro?");
    if (respuesta37){
        $.ajax({
            url: 'sql/rol_pagos.php',
            data: 'id_detalle_rol_pago='+id+'&txtAccion='+accion,
            type: 'post',
            success: function(data){
                if(data!="")
                    if(data == '1'){
                        document.getElementById('mensaje1').innerHTML="<div class='transparent_ajax_error'><p>Ocurrio un error al eliminar: \n</p></div>";
                        alert("Ocurrio un error al eliminar");
                    }
                    if(data == '2'){
                        document.getElementById('mensaje1').innerHTML="<div class='transparent_ajax_correcto'><p>El registro ha sido Eliminado.</p></div>";
                        alert("El registro ha sido Eliminado");
                    }
                    listar_todos_roles_pagos();
            }
        });
    }

}
//***************** GUARDAR EMPRESA  *************//


function guardar_empresa(accion){
    //PAGINA: crear_empresa.php
 
    var ciudad = document.form['opcion2'].value;//validamos si es > que cero
    var ruc = "";
    var ruc2 = "";
        ruc = noRepetirRucEmpresa(document.form['txtCedula'].value,5);//retorna 1 o 0
		//lcCedula=document.form['txtCedula'].value;
		//ruc2 = cedula_ruc(lcCedula); // retorna true o false
        ruc2 = cedula_ruc(txtCedula); // retorna true o false
    
    
    var noRptCodigoEmpresa = noRepetirCodigoEmpresa(txtCodEmpresa, 9); //retorna 1 o 0
    var email = no_repetir_email_empresa(txtEmail,7); // retorna 1 o 0
    var email2 = isEmailAddress(txtEmail); // retorna true o false
  
    if(email == 0 && email2 == true){
        
        if(ruc == 0 && ruc2 == true)
		{
           // alert("entro2");
            if(ciudad >= 1)
			{
          //      if(cmbTipoEmpresa > 0){
                if(noRptCodigoEmpresa == 0)
				{
                    var respuesta38 = confirm("Seguro desea guardar este Registro?");
                    if (respuesta38)
					{
                        document.getElementById("btnGuardar").style.visibility="hidden";
                                //document.getElementById("btnGuardar").disabled = true;
                        var str = $("#form").serialize();
				
                        $.ajax({
                            url: 'sql/empresa.php',
                                        type: 'post',
                                        data: str+"&accion="+accion,
                                        success: function(data){
                                            console.log(data);
                                       
                                            if(data.length == 370 || data.length == 86 || data.length == 371){
                                                document.getElementById("form").reset();
                                                //document.getElementById("btnGuardar").style.visibility="visible";
                                                document.getElementById("mensaje1").focus();
                                            }
                                            setTimeout ("alert ('Registro Exitoso..!');", 2000); 
                                          }

                                });

                    }
                }
				else
				{
                    alert ('No se puede guardar por que el Codigo de Empresa ya se encuentra Registrado.');
                    document.form.txtCodEmpresa.focus();
                }
         /*       }else{
                    alert ('No se puede guardar por que no ha seleccionado el Tipo de Empresa.');
                    document.form.cmbTipoEmpresa.focus();
                } */
            }
			else
			{
                alert ('No se puede guardar por que no ha seleccionado su ciudad');
                document.form.cbciudad.focus();
            }

        }
		else{
            alert ('No se puede guardar por que el Ruc: '+txtCedula+' ya se encuentra registrado.');
            document.form.txtCedula.focus();
        }

    }else{
        alert ('No se puede guardar el Email: '+txtRuc+' por que esta mal escrito o ya esta registrado. ');
        document.form.txtRuc.focus();

    }

    
}

function modificar_empresa(accion){

                    var respuesta38 = confirm("Seguro desea actualizar este Registro?");
                    if (respuesta38){


                        var str = '';
if(accion==11){
    str = $("#formProforma").serialize();
}else{
    str = $("#form").serialize();
}
console.log(str);
                        $.ajax({
                            url: 'sql/empresa.php',
                                        type: 'post',
                                        data: str+"&accion="+accion,
                                        success: function(data){
                             console.log(data);
                                           if(data==1){
				                                    alertify.success("Actualizado con exito :)");

	
			                                }else{
				                                alertify.error("Fallo el servidor :(");
			                                }
                                        }

                                });

                    } 
    
}
  
  
  
  
//***************** DIRECCIONES  pagina: direcciones.php  *************//

function listar_direcciones(){
	var str = $("#frm_direcciones").serialize();
	$.ajax({
		url: 'ajax/listarDirecciones.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#div_listar_direcciones").html(data);
		}
	});
}

function nuevo_pais(){
 //PAGINA: direcciones.php
	$("#div_oculto").load("ajax/nuevoPais.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
			css:{
				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',

				background: '#FFFFFF',
				top: '20px',
				left: '185px',
                                position: 'absolute',
				width: '450px'
			}
		});
	});
}

function guardar_pais(accion){
   //guardar pais pagina: direcciones.php
    var nombre = "";
    var valor = "";
    valor = document.form1['txtNombrePais'].value;
    nombre = no_repetir_pais(valor, '2');//retorna 1 o 0

    if(nombre == 0){

		var str = $("#form1").serialize();
		$.ajax({

			url: 'sql/direcciones.php',
			data: str+"&txtAccion="+accion,
			type: 'post',
			success: function(data){
                        document.getElementById('mensaje1').innerHTML+=""+data;
                        document.getElementById("form1").reset();
                        listar_direcciones();
			}
		});
    }else {
            alert ('No se puede guardar porque el nombre "'+document.form1['txtNombrePais'].value+'" ya se encuentra registrado.');
            document.form1.txtNombrePais.focus();
            document.form1.txtNombrePais.value="";
            document.getElementById("noRepetirPais").innerHTML="";
        }
}

function eliminar_pais(id_pais, accion){
	var respuesta39 = confirm("Seguro desea eliminar este registro?");
	if (respuesta39){
		$.ajax({
			url: 'sql/direcciones.php',
			data: 'id_pais=' + id_pais+'&txtAccion='+accion,
			type: 'post',
			success: function(data){
				if(data!="")
					alert(data);
				listar_direcciones();
			}
		});
	}
}

function modificar_pais(id_pais){
    //PAGINA: direcciones.php
    $("#div_oculto").load("ajax/modificarPais.php", {id_pais: id_pais}, function(){
            $.blockUI({
                    message: $('#div_oculto'),
                    overlayCSS: {backgroundColor: '#111'},
                    css:{
                            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
                            position: 'absolute',
                            background: '#f9f9f9',
                            top: '20px',
                            left: '185px',
                            width: '450px'
                    }
            });
    });
}

function guardarModificarPais(accion){
     //PAGINA: direcciones.php
        var str = $("#form").serialize();
        $.ajax({
                url: 'sql/direcciones.php',
                data: str+"&txtAccion="+accion,
                type: 'post',
                success: function(data){
                   document.getElementById('mensaje').innerHTML=""+data;
                   listar_direcciones();
                }
        });
}


function nueva_provincia(){
 //PAGINA: direcciones.php
	$("#div_oculto").load("ajax/nuevaProvincia.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
			css:{
				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',

				background: '#FFFFFF',
				top: '20px',
				left: '185px',
                                position: 'absolute',
				width: '450px'
			}
		});
	});
}

function guardar_provincia(accion){
 //PAGINA: direcciones.php
    var nombre = "";
    var valor = "";
    valor = document.form1['txtProvincia'].value;
    id = document.form1['cmbPaises'].value;
    nombre = no_repetir_provincia(valor, '5');//retorna 1 o 0

    if(nombre == 0){

		var str = $("#form1").serialize();
		$.ajax({

			url: 'sql/direcciones.php',
			data: str+"&txtAccion="+accion,
			type: 'post',
			success: function(data){
                        document.getElementById('mensaje11').innerHTML+=""+data;
                        document.getElementById("form1").reset();
                        listar_direcciones();
			}
		});
    }else {
            alert ('No se puede guardar porque el nombre "'+document.form1['txtProvincia'].value+'" ya se encuentra registrado.');
            document.form1.txtProvincia.focus();
            document.form1.txtProvincia.value="";
            document.getElementById("noRepetirProvincia").innerHTML="";
        }
}

function eliminar_provincia(id_provincia, accion){
    var respuesta40 = confirm("Seguro desea eliminar este registro?");
    if (respuesta40){
            $.ajax({
                    url: 'sql/direcciones.php',
                    data: 'id_provincia=' + id_provincia+'&txtAccion='+accion,
                    type: 'post',
                    success: function(data){
                            if(data!="")
                                    alert(data);
                            listar_direcciones();
                    }
            });
    }
}

function modificar_provincia(id_provincia){
    //PAGINA: direcciones.php
    $("#div_oculto").load("ajax/modificarProvincias.php", {id_provincia: id_provincia}, function(){
            $.blockUI({
                    message: $('#div_oculto'),
                    overlayCSS: {backgroundColor: '#111'},
                    css:{
                            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
                            position: 'absolute',
                            background: '#f9f9f9',
                            top: '20px',
                            left: '185px',
                            width: '450px'
                    }
            });
    });
}

function guardarModificarProvincias(accion){
 //PAGINA: direcciones.php
    var str = $("#form1").serialize();
    $.ajax({

            url: 'sql/direcciones.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data){
            document.getElementById('mensaje11').innerHTML+=""+data;
            listar_direcciones();
            }
    });

}

function nueva_ciudad(){
 //PAGINA: direcciones.php
    $("#div_oculto").load("ajax/nuevaCiudad.php", function(){
            $.blockUI({
                    message: $('#div_oculto'),
            overlayCSS: {backgroundColor: '#111'},
                    css:{
                            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',

                            background: '#FFFFFF',
                            top: '20px',
                            left: '185px',
                            position: 'absolute',
                            width: '450px'
                    }
            });
    });
}





function guardar_ciudad(accion){
 //PAGINA: direcciones.php
    var nombre = "";
    //var valor = "";
    //valor = document.form['txtCiudad'].value;
    //nombre = no_repetir_ciudad(valor, '9');//retorna 1 o 0 // la inactive porque hay ciudades que tienen el mismo nombre en diferente provincia
    nombre = 0;
    if(nombre == 0){
           
            document.getElementById('mensaje11').innerHTML="";
            var str = $("#form").serialize();
            $.ajax({
                    url: 'sql/direcciones.php',
                    data: str+"&txtAccion="+accion,
                    type: 'post',
                    success: function(data){
                        //$("#mensaje11").remove();
                    document.getElementById('noRepetirCiudad').innerHTML="";
                    document.getElementById('mensaje11').innerHTML+=""+data;
                    //document.getElementById("form").reset();
                    document.form.txtCiudad.value="";
                    listar_direcciones();
                    }
            });
    }else {
            alert ('No se puede guardar porque el nombre "'+document.form['txtCiudad'].value+'" ya se encuentra registrado.');
            document.form.txtCiudad.focus();
            document.form.txtCiudad.value="";
            document.getElementById("noRepetirProvincia").innerHTML="";
        }
}

function modificar_ciudad(id_ciudad){
    //PAGINA: direcciones.php
    $("#div_oculto").load("ajax/modificarCiudad.php", {id_ciudad: id_ciudad}, function(){
            $.blockUI({
                    message: $('#div_oculto'),
                    overlayCSS: {backgroundColor: '#111'},
                    css:{
                            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
                            position: 'absolute',
                            background: '#f9f9f9',
                            top: '20px',
                            left: '185px',
                            width: '450px'
                    }
            });
    });
}

function guardarModificarCiudad(accion){
 //PAGINA: direcciones.php
    var str = $("#form").serialize();
    $.ajax({

            url: 'sql/direcciones.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data){
            document.getElementById('mensaje11').innerHTML+=""+data;
            listar_direcciones();
            }
    });

}

function eliminar_ciudad(id_ciudad, accion){
    var respuesta41 = confirm("Seguro desea eliminar este registro?");
    if (respuesta41){
            $.ajax({
                    url: 'sql/direcciones.php',
                    data: 'id_ciudad=' + id_ciudad+'&txtAccion='+accion,
                    type: 'post',
                    success: function(data){
                            if(data!="")
                                    alert(data);
                            listar_direcciones();
                    }
            });
    }
}

//*****************   PRODUCTOS    *************//

function listar_productos(page){
 //PAGINA: productos.php
    var str = $("#form1").serialize();
    // console.log("str",str);
    $.ajax({
            url: 'ajax/listarProductos.php',
            type: 'get',
            data: str+"&page="+page,
            success: function(data){
                console.log("data",data);
                    $("#div_listar_productos").html(data);
            }
    });
}

function nuevo_producto(){
 //PAGINA: productos.php

	$("#div_oculto").load("ajax/nuevoProducto.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
	/*	overlayCSS: {backgroundColor: '#111'}, */
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


function nuevo_producto_formulario(){
    //PAGINA: proveedores.php
    //  var str = $("#form1").serialize();
    // alert("producto");
   
    $.ajax({
            url: 'ajax/nuevoProducto.php',
            type: 'post',
        //    data: str,
            success: function(data){
               // console.log(data);
                    $("#nuevo_producto_formulario").html(data);
            }
    });
}



function guardar_producto(accion){
   //guardar producto: productos.php
    var nombre = "";
    var valor = "";
    valor = document.form['txtProducto'].value;
    nombre = no_repetir_producto(valor, '4');//retorna 1 o 0

    if(nombre == 0){

            var str = $("#form").serialize();
            $.ajax({

                    url: 'sql/productos.php',
                    data: str+"&txtAccion="+accion,
                    type: 'post',
                    success: function(data){
                  //  document.getElementById('mensaje1').innerHTML+=""+data;
                    //document.getElementById("form1").reset();
                    //listar_productos();
                    	    console.log(data);
				if(data==1){
				alertify.success("Producto agregado con exito :)");
				fn_cerrar();
				listar_productos(1);
			}else{
				alertify.error("Error al guardar, revise los datos");
			}
                    }
            });
    }else {
            alert ('No se puede guardar porque el nombre "'+document.form['txtProducto'].value+'" ya se encuentra registrado.');
            document.form.txtProducto.focus();
            document.form.txtProducto.value="";
            document.getElementById("noRepetirProducto").innerHTML="";
        }
}

// function eliminarProducto(id_producto, accion){
//     // PAGINA: productos.php
//     var respuesta42 = confirm("Seguro desea eliminar este registro?");
//     if (respuesta42){
//         $.ajax({
//                 url: 'sql/productos.php',
//                 data: 'id_producto='+id_producto+'&txtAccion='+accion,
//                 type: 'post',
//                 success: function(data){
//                         if(data!="")
//                                 alert(data);
//                         listar_productos();
//                 }
//         });
//     }
// }

function eliminarProducto(id_producto, accion){
    // PAGINA: productos.php
    var respuesta42 = confirm("Seguro desea eliminar este registro?");
    if (respuesta42){
        $.ajax({
                url: 'sql/productos_servicios.php',
                data: 'id_producto='+id_producto+'&txtAccion='+accion,
                type: 'post',
                success: function(data){
                    let json = JSON.parse(data);
                    // console.log(typeof(data));
                    // console.log(typeof(json));
                    // console.log(json);
                    // console.log(json.length);

              for(let i=0;i<json.length;i++){
                if(json[i]!=0){
                    if(json[i]==='Se elimino correctamente el producto'){
                        alertify.success(json[i]);
                    }else{
                        alertify.error(json[i]);
                    }
                    
                }
               
              }
                 

                 
                       
                        listar_productos();
                }
        });
    }
}

function bodegas(id_producto){
    console.log("id_producto",id_producto);
    //PAGINA: productos.php
    $("#div_oculto").load("ajax/bodegas.php", {id_producto: id_producto}, function(){
        $.blockUI({
                message: $('#div_oculto'),
                overlayCSS: {backgroundColor: '#111'},
                css:{
                        '-webkit-border-radius': '10px',
                        '-moz-border-radius': '10px',
                        position: 'absolute',
                        top: '10%',
                        left: '25%',
                        width: '50%'
                }
        });
    });
}


function modificarProducto(id_producto){
    //PAGINA: productos.php
    $("#div_oculto").load("ajax/modificarProducto.php", {id_producto: id_producto}, function(){
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


function fotoProducto(id){
    $("#div_oculto").load("ajax/fotoProducto.php",{id: id}, function(){
    $.blockUI({
        message: $('#div_oculto'),
        overlayCSS: {backgroundColor: '#111'},
                css:{
                '-webkit-border-radius': '3px',
                '-moz-border-radius': '3px',
                'box-shadow': '6px 6px 20px gray',
				top: '5%',
				left: '35%',
				width: '25%',
                position: 'absolute'
                }
        });
    });
    
}

function guardarModificarProducto(accion){
     //PAGINA: productos.php
    var str = $("#form").serialize();
    $.ajax({
            url: 'sql/productos.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data){
                console.log(data);
            if(data==1){
				alertify.success("Producto modificado con exito :)");
				fn_cerrar()
				listar_productos(1);
			}else{
				alertify.error("Error al guardar, revise los datos");
			}
            }
    });
}




//*****************   PROVEEDORES    *************//

function listar_proveedores(){
 //PAGINA: proveedores.php
    var str = $("#form1").serialize();
    $.ajax({
            url: 'ajax/listarProveedores.php',
            type: 'get',
            data: str,
            success: function(data){
                    $("#div_listar_proveedores").html(data);
            }
    });
}



function nuevoProveedor(){
 //PAGINA: proveedores.php
    $("#div_oculto").load("ajax/nuevoProveedor.php", function(){
        $.blockUI({
                message: $('#div_oculto'),
        overlayCSS: {backgroundColor: '#111'},
                css:{
                    '-webkit-border-radius': '10px',
    '-moz-border-radius': '10px',

                    background: '#FFFFFF',
                    top: '20px',
                    left: '185px',
                    position: 'absolute',
                    width: '50%'
                }
        });
    });
}



function guardar_proveedores(codigo, nombre){
//pagina proveedores.php
if(codigo == 0 && nombre == 0){
    var repetir_ruc = "";
    repetir_ruc = no_repetir_ruc(document.form['txtRuc'].value, 4);//retorna 1 o 0
//  var cedula_ruc = cedula_ruc(txtCedula);//retorna true o false    
    var ciudad = document.form.opcion2.value//validamos si es > que cero

        if(repetir_ruc == 0 ){
            if(ciudad >= 1){
                var str = $("#form").serialize();
                $.ajax({
                        url: 'sql/proveedores.php',
                        data: str+"&txtAccion="+1,
                        type: 'POST',
                        success: function(data){
                        document.getElementById('mensaje').innerHTML=""+data;
                        document.getElementById('mensaje').innerHTML=""+data;
                        //document.getElementById("form").reset();
                        listar_proveedores();
                        }
                    });
                }else{
                    alert ('No se puede guardar porque no ha seleccionado su ciudad');
                    document.form.cbciudad.focus();
                }
        }else {
            alert ('No se puede guardar porque el Ruc "'+document.form['txtRuc'].value+'" ya se encuentra registrado.');
            document.form.txtRuc.focus();
            document.form.txtRuc.value="";
            document.getElementById("noRepetirRuc").innerHTML="" ;
        }
    }
}

function modificarProveedor(id_proveedor){
    //PAGINA: proveedores.php
    $("#div_oculto").load("ajax/modificarProveedor.php", {id_proveedor: id_proveedor}, function(){
        $.blockUI({
                message: $('#div_oculto'),
                overlayCSS: {backgroundColor: '#111'},
                css:{
                        '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px',
                        position: 'absolute',
                        background: '#f9f9f9',
                        top: '20px',
                        left: '185px',
                        width: '450px'
                }
        });
    });
}

function guardarModificarProveedor(accion){
     //PAGINA: proveedores.php
     var ciudad = document.form.opcion2.value//validamos si es > que cero
     if(ciudad >= 1){
        var str = $("#form").serialize();
        $.ajax({
                url: 'sql/proveedores.php',
                data: str+"&txtAccion="+accion,
                type: 'post',
                success: function(data){
                   document.getElementById('mensaje').innerHTML=""+data;
                   listar_proveedores();
                }
        });
     }else{
        alert ('No se puede guardar porque no ha seleccionado su ciudad');
        document.form.cbciudad.focus();
    }
}

function eliminarProveedor(id, accion, id_plan_cuenta){
    //PAGINA: proveedores.php
    var respuesta46 = confirm("Seguro desea eliminar este Registro? \nEsta acci\u00f3n eliminara de forma permanente la fila seleccionada del Modulo Proveedores y del Modulo Plan Cuentas.");
    if (respuesta46){
        $.ajax({
                url: 'sql/proveedores.php',
                data: 'idProveedor='+id+'&txtAccion='+accion+'&id_cuenta='+id_plan_cuenta,
                type: 'post',
                success: function(data){
                    if(data!="")
                    //alert(data);
                    document.getElementById("mensaje1").innerHTML="<div class='transparent_notice'><p>"+data+"</p></div>";
                    listar_proveedores();
                }
        });
    }
}

//***************** COMISIONES   *************//

function listar_comisiones(){
     //PAGINA: comision.php
	var str = $("#form").serialize();
	$.ajax({
		url: 'ajax/listarComisiones.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#div_listar_Comisiones").html(data);
		}
	});
}


function nueva_comision(){
 //PAGINA: comision.php
	$("#div_oculto").load("ajax/nuevaComision.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
			css:{
				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',

				background: '#FFFFFF',
				top: '20px',
				left: '185px',
                                position: 'absolute',
				width: '450px'

			}
		});
	});
}

function guardar_comision(accion){
     //PAGINA: comision.php
    var compararComision = "";
    compararComision = comprobar(document.form1['txtIdCliente'].value, 4,document.form1['txtAno'].value,document.form1['txtMes'].value);//retorna 1 o 0
    
    if(compararComision == 0 ){
    var str = $("#form1").serialize();
    $.ajax({
            url: 'sql/comisiones.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data){
            document.getElementById('mensaje11').innerHTML+=""+data;
            document.getElementById("form1").reset();            
            listar_comisiones();
            }
    });
    }else {        
            alert ('No se puede guardar por que la comisi\u00f3n para este empleado ya se encuentra ingresada.');
            document.form1.txtCliente.focus();
            document.form1.txtCliente.value="";
            document.form1.txtIdCliente.value="";
            document.getElementById("mensajeComparacion").innerHTML="";
        }
}

function modificar_comision(id){
  //PAGINA: comision.php
	$("#div_oculto").load("ajax/modificarComision.php", {id_comision: id}, function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
			css:{
				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',

				background: '#FFFFFF',
				top: '20px',
				left: '185px',
                                position: 'absolute',
				width: '450px'

			}
		});
	});
}


function guardar_modificacion_comision(accion){
     //PAGINA: comision.php
    var str = $("#form2").serialize();
    $.ajax({
            url: 'sql/comisiones.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data){
            document.getElementById('mensaje11').innerHTML+=""+data;
            //document.getElementById("form2").reset();
            listar_comisiones();
            }
    });

}

function eliminar_comision(id, accion){
     //PAGINA: comisiones.php
    var respuesta47 = confirm("Seguro desea eliminar este Registro? \nEsta acci\u00f3n eliminara de forma permanente la fila seleccionada");
    if (respuesta47){
            $.ajax({
                    url: 'sql/comisiones.php',
                    data: 'txtIdComision='+id+'&txtAccion='+accion,
                    type: 'post',
                    success: function(data){                            
                            alert(data);
                            
                            listar_comisiones(); 
                    }
            });
    }

}

//***************** MULTAS   *************//

function listar_multas(){
     //PAGINA: multas.php
	var str = $("#form").serialize();
	$.ajax({
		url: 'ajax/listarMultas.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#div_listar_multas").html(data);
		}
	});
}


function nueva_multa(){
//PAGINA: ajax/nuevaMulta.php
    $("#div_oculto").load("ajax/nuevaMulta.php", function(){
            $.blockUI({
                    message: $('#div_oculto'),
            overlayCSS: {backgroundColor: '#111'},
                    css:{
                            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',

                            background: '#FFFFFF',
                            top: '20px',
                            left: '185px',
                            position: 'absolute',
                            width: '450px'

                    }
            });
    });
}

function guardar_multa(accion){

    var str = $("#frmNuevaMulta").serialize();
    $.ajax({
            url: 'sql/multas.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data){
            document.getElementById('mensaje11').innerHTML+=""+data;
            document.getElementById("frmNuevaMulta").reset();
            listar_multas();
            }
    });
}

function modificar_multa(id){
 //PAGINA: ajax/modificarMulta.php
	$("#div_oculto").load("ajax/modificarMulta.php", {id_multa: id}, function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
			css:{
				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',

				background: '#FFFFFF',
				top: '20px',
				left: '185px',
                                position: 'absolute',
				width: '450px'

			}
		});
	});
}

function guardar_modificacion_multa(accion){

    var str = $("#frmModificaMulta").serialize();
    $.ajax({
            url: 'sql/multas.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data){
            document.getElementById('mensaje11').innerHTML+=""+data;            
            listar_multas();
            }
    });
}

function eliminar_multa(id_multa, accion){
     //PAGINA: multas.php
    var respuesta48 = confirm("Seguro desea eliminar este Registro? \nEsta acci\u00f3n eliminara de forma permanente la fila seleccionada");
    if (respuesta48){
            $.ajax({
                    url: 'sql/multas.php',
                    data: 'id_multa='+id_multa+'&txtAccion='+accion,
                    type: 'post',
                    success: function(data){
                            if(data!="")
                            alert(data);
                            listar_multas();
                    }
            });
    }
}


//***************** KARDEX   *************//

function listar_kardex(){
    // Mostrar el icono de carga
    $("#loadingIcon").show();
    
    var str = $("#frmKardex").serialize();
    //console.log("str",str);
    $.ajax({
        url: 'ajax/listarKardex.php',
        type: 'get',
        data: str,
        success: function(data){
            console.log(data);
            $("#div_listar_kardex").html(data);
        },
        complete: function() {
            // Ocultar el icono de carga cuando la solicitud esté completa
            $("#loadingIcon").hide();
        }
    });
}

//***************** INVENTARIO   *************//

function listar_inventario(){
     //PAGINA: kardex.php
	var str = $("#frmInventario").serialize();
	$.ajax({
		url: 'ajax/listarInventario.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#div_listar_inventario").html(data);
		}
	});
}




//***************** DECIMO CUARTO **********//

function listar_decimo_cuarto(){
     //PAGINA: decimoCuarto.php
	var str = $("#frmDecimoC").serialize();
	$.ajax({
		url: 'ajax/listarDecimoCuarto.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#div_listar_decimoC").html(data);
		}
	});
}

function nuevoDecimoCuarto(){
    //PAGINA: ajax/nuevoDecimoCuarto.php
    $("#div_oculto").load("ajax/nuevoDecimoCuarto.php", function(){
            $.blockUI({
                    message: $('#div_oculto'),
            overlayCSS: {backgroundColor: '#111'},
                    css:{
                            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',

                            background: '#FFFFFF',
                            top: '20px',
                            left: '185px',
                            position: 'absolute',
                            width: '650px'

                    }
            });
    });
}


function guardar_decimo_cuarto(accion){
    //PAGINA: ajax/nuevoDecimoCuarto.php
//    if(document.getElementById("txtPorcentaje"+nFila).value=="" ){
//        alert("Faltan Campos por llenar");
//
//    }else{
        var respuesta52 = confirm("Seguro desea guardar este Registro? \nEsta acci\u00f3n afectara a todos los trabajadores");
        if (respuesta52){
        var str = $("#frmNuevoDC").serialize();
            $.ajax({
                    url: 'sql/decimos.php',
                    type: 'post',
                    data: str+"&txtAccion="+accion,
                    success: function(data){
                        //$("#div_listar_RegistroDiario").html(data);
                        document.getElementById("mensaje11").innerHTML=""+data;
                        listar_decimo_cuarto();
                    }
            });
           }
    //}
}

function calcular_decimo_cuarto(){
    //PAGINA: ajax/calcularDecimoCuarto.php
    var str = $("#frmNuevoDC").serialize();
    $.ajax({
            url: 'ajax/calcularDecimoCuarto.php',
            type: 'get',
            data: str,
            success: function(data){
                    $("#div_calculadc").html(data);
            }
    });
}

function vaciarDecimoCuarto(accion){
    //PAGINA: decimoCuarto.php
        var respuesta53 = confirm("Seguro desea vaciar los registros del Decimo Cuarto del a\u00f1o seleccionado? \nEsta acci\u00f3n afectara a todos los registros");
        if (respuesta53){
        var str = $("#frmDecimoC").serialize();
            $.ajax({
                    url: 'sql/decimos.php',
                    type: 'post',
                    data: str+"&txtAccion="+accion,
                    success: function(data){
                        //$("#div_listar_RegistroDiario").html(data);
                        document.getElementById("mensaje1").innerHTML=""+data;
                        listar_decimo_cuarto();
                    }
            });
           }    
}

function modificar_decimo_cuarto (id_decimo_cuarto){
    //PAGINA: ajax/modificarDecimoCuarto.php
    $("#div_oculto").load("ajax/modificarDecimoCuarto.php", {id_decimo_cuarto: id_decimo_cuarto}, function(){
            $.blockUI({
                    message: $('#div_oculto'),
            overlayCSS: {backgroundColor: '#111'},
                    css:{
                            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',

                            background: '#FFFFFF',
                            top: '20px',
                            left: '185px',
                            position: 'absolute',
                            width: '450px'

                    }
            });
    });
}

function guardar_modificacion_DC (accion){
    //PAGINA: sql/decimos.php
        var respuesta54 = confirm("Seguro desea guardar este Registro? \n");
        if (respuesta54){
        var str = $("#frmModificarDC").serialize();
            $.ajax({
                    url: 'sql/decimos.php',
                    type: 'post',
                    data: str+"&txtAccion="+accion,
                    success: function(data){
                        //$("#div_listar_RegistroDiario").html(data);
                        document.getElementById("mensaje11").innerHTML=""+data;
                        listar_decimo_cuarto();
                    }
            });
           }
}


// ************* DECIMO TERCERO *******//

function listar_decimo_tercero(){
     //PAGINA: decimoTercero.php
	var str = $("#frmDecimoT").serialize();
	$.ajax({
		url: 'ajax/listarDecimoTercero.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#div_listar_TRolesPagos").html(data);
		}
	});
}

function nuevoDecimoTercero(){
    //PAGINA: ajax/nuevoDecimoTercero.php
    $("#div_oculto").load("ajax/nuevoDecimoTercero.php", function(){
            $.blockUI({
                    message: $('#div_oculto'),
            overlayCSS: {backgroundColor: '#111'},
                    css:{
                            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',

                            background: '#FFFFFF',
                            top: '20px',
                            left: '185px',
                            position: 'absolute',
                            width: '650px'

                    }
            });
    });
}

function calcular_decimo_tercero(){
    //PAGINA: ajax/calcularDecimoTercero.php
    var str = $("#frmNuevoDT").serialize();
    $.ajax({
            url: 'ajax/calcularDecimoTercero.php',
            type: 'get',
            data: str,
            success: function(data){
                    $("#div_calculadt").html(data);
            }
    });
}
function guardar_decimo_tercero(accion){
    //PAGINA: ajax/nuevoDecimoTercero.php
//    if(document.getElementById("txtPorcentaje"+nFila).value=="" ){
//        alert("Faltan Campos por llenar");
//
//    }else{
        var respuesta55 = confirm("Seguro desea guardar este Registro? \nEsta acci\u00f3n afectara a todos los trabajadores");
        if (respuesta55){
        var str = $("#frmNuevoDT").serialize();
            $.ajax({
                    url: 'sql/decimos.php',
                    type: 'post',
                    data: str+"&txtAccion="+accion,
                    success: function(data){
                        //$("#div_listar_RegistroDiario").html(data);
                        document.getElementById("mensaje11").innerHTML=""+data;
                        listar_decimo_tercero();
                    }
            });
           }
    //}
}

function vaciarDecimoTercero(accion){
    //PAGINA: decimoTercero.php
        var respuesta56 = confirm("Seguro desea vaciar los registros del Decimo Tercero del a\u00f1o seleccionado? \nEsta acci\u00f3n afectara a todos los registros");
        if (respuesta56){
        var str = $("#frmDecimoT").serialize();
            $.ajax({
                    url: 'sql/decimos.php',
                    type: 'post',
                    data: str+"&txtAccion="+accion,
                    success: function(data){
                        //$("#div_listar_RegistroDiario").html(data);
                        document.getElementById("mensaje1").innerHTML=""+data;
                        listar_decimo_tercero();
                    }
            });
           }
}

//*****************   ACTIVOS    *************//

function listar_activos(){
 //PAGINA: activos.php
    var str = $("#frmActivos").serialize();
    $.ajax({
            url: 'ajax/listarActivos.php',
            type: 'get',
            data: str,
            success: function(data){
                    $("#div_listar_activos").html(data);
            }
    });
}

function nuevo_activo(){
 //PAGINA: ajax/nuevoActivo.php
    $("#div_oculto").load("ajax/nuevoActivo.php", function(){
            $.blockUI({
                    message: $('#div_oculto'),
            overlayCSS: {backgroundColor: '#111'},
                    css:{
                            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',

                            background: '#FFFFFF',
                            top: '20px',
                            left: '185px',
                            position: 'absolute',
                            width: '700px'
                    }
            });
    });
}

function guardar_activo(accion){
   //guardar activo: sql/activos.php 
    var str = $("#frmNuevoActivo").serialize();
    $.ajax({

            url: 'sql/activos.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data){
            document.getElementById('mensaje1').innerHTML+=""+data;
            document.getElementById("frmNuevoActivo").reset();
            listar_activos();
            }
    });
}

function modificarActivo(id_activo){
    //PAGINA: activo.php
    $("#div_oculto").load("ajax/modificarActivo.php", {id_activo: id_activo}, function(){
        $.blockUI({
                message: $('#div_oculto'),
                overlayCSS: {backgroundColor: '#111'},
                css:{
                        '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px',
                        position: 'absolute',
                        background: '#f9f9f9',
                        top: '20px',
                        left: '185px',
                        width: '700px'
                }
        });
    });
}

function listarActivoDepreciado(){
 //PAGINA: activoDepreciado.php
    var str = $("#frmActivoDepreciado").serialize();
    $.ajax({
            url: 'ajax/listarActivoDepreciado.php',
            type: 'get',
            data: str,
            success: function(data){
                    $("#div_listar_activo_depreciado").html(data);
            }
    });
}

function guardarModificacionActivo(accion){
    //PAGINA: ajax/modificarActivo.php

    var str = $("#frmModificarActivo").serialize();
	$.ajax({
		url: 'sql/activos.php',
		type: 'post',
		data: str+"&txtAccion="+accion,
		success: function(data){
                    //$("#div_listar_RegistroDiario").html(data);
                    document.getElementById("mensaje1").innerHTML=""+data;
                    listar_activos();
		}
	});
}


function eliminarActivo(id, accion){
    //PAGINA: activos.php
    var respuesta57 = confirm("Seguro desea eliminar este Registro? ");
    if (respuesta57){
        $.ajax({
                url: 'sql/activos.php',
                data: 'id='+id+'&txtAccion='+accion,
                type: 'post',
                success: function(data){
                    if(data!="")
                    //alert(data);
                    document.getElementById("mensaje1").innerHTML=""+data;
                    listar_activos();
                }
        });
    }
}


//************************* DEPRECIACION PAGINA: depreciaciones.php   ************//

function depreciaciones(){
 //PAGINA: ajax/depreciaciones.php
    $("#div_oculto").load("ajax/depreciaciones.php", function(){
        $.blockUI({
                message: $('#div_oculto'),
        overlayCSS: {backgroundColor: '#111'},
                css:{
                        '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px',

                        background: '#FFFFFF',
                        top: '20px',
                        left: '185px',
                        position: 'absolute',
                        width: '700px'                        
                }
        });listar_depreciaciones();
    });
}

function listar_depreciaciones(){
 //PAGINA: ajax/depreciaciones.php
    var str = $("#frmDepreciaciones").serialize();
    $.ajax({
            url: 'ajax/listarDepreciaciones.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#div_listar_D").html(data);
                 cantidad_filas_Depreciaciones();
            }
    });
}

function guardar_depreciaciones(nFila, accion){

    //PAGINA: ajax/depreciaciones.php
    if(document.getElementById("txtCuenta2"+nFila).value=="" && document.getElementById("txtVidaUtil"+nFila).value=="" && document.getElementById("txtPorcentajeDepre"+nFila).value==""){
        alert("Faltan Campos por llenar");

    }else{
        var respuesta58 = confirm("Seguro desea guardar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
        if (respuesta58){
        var str = $("#frmDepreciaciones").serialize();
            $.ajax({
                    url: 'sql/depreciaciones.php',
                    type: 'post',
                    data: str+"&numeroFilaSelec="+nFila+"&txtAccion="+accion,
                    success: function(data){
                            //$("#div_listar_RegistroDiario").html(data);
                            document.getElementById("mensaje1").innerHTML=""+data;
                            listar_depreciaciones();
                    }
            });
           }
    }
}

function modificar_depreciaciones(id, accion, fila){
    //PAGINA: ajax/depreciaciones.php
    var respuesta59 = confirm("Seguro desea modificar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
    if (respuesta59){
    var str = $("#frmDepreciaciones").serialize();
	$.ajax({
		url: 'sql/depreciaciones.php',
		type: 'post',
		data: str+"&idDepreciacion="+id+"&txtAccion="+accion+"&NumeroFilaSeleccionada="+fila,
		success: function(data){
			//$("#div_listar_RegistroDiario").html(data);
                        document.getElementById("mensaje1").innerHTML=""+data;
                        listar_depreciaciones();
		}
	});
       }
}

function eliminar_depreciaciones(id, accion){
    //PAGINA: ajax/depreciaciones.php
    var respuesta60 = confirm("Seguro desea eliminar este Registro? \nEsta acci\u00f3n eliminara de forma permanente la fila seleccionada");
    if (respuesta60){
            $.ajax({
                    url: 'sql/depreciaciones.php',
                    data: 'idDepreciacion='+id+'&txtAccion='+accion,
                    type: 'post',
                    success: function(data){
                            if(data!="")
                            //alert(data);
                            document.getElementById("mensaje1").innerHTML=""+data;
                            listar_depreciaciones();
                    }
            });
    }
}

function nuevoActivoDepreciado(id_activo_fijo){ 
// funcion nuevo prestamo funciona en la pagina: prestamos.php
    $("#div_oculto").load("ajax/nuevaDepreciacion.php", {id_activo_fijo: id_activo_fijo},function(){
        $.blockUI({
                message: $('#div_oculto'),
        overlayCSS: {backgroundColor: '#111'},
                css:{

                    '-webkit-border-radius': '10px',
    '-moz-border-radius': '10px',

                    background: '#f9f9f9',
                    top: '20px',
                    left: '185px',
                    position: 'absolute',
                    width: '690px'
                }
        });
    });
}
function guardar_activo_depreciado(accion){
   //guardar activo depreciado: sql/activos.php
    var str = $("#frmNuevoActivoDepreciado").serialize();
    $.ajax({

            url: 'sql/activos.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data){
            document.getElementById('mensaje1').innerHTML+=""+data;
            document.getElementById("frmNuevoActivoDepreciado").reset();
            //window.locationf="activoDepreciado.php";
            setTimeout('document.location.reload()', 4000);            
            }
    });
}

//***************** TABLA DE PORCENTAJES DE RETENCIONES   *************//

function listarPorcentajesRetencion(){
 //PAGINA: porcentajesRetencion.php
    var str = $("#frmPorcentajesRetencion").serialize();
    $.ajax({
            url: 'ajax/listarPorcentajesRetencion.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#div_listar_porcentajes_retencion").html(data);
                 cantidadFilasPorcentajesRetencion();
            }
    });
}

function guardarPorcentajesRetencion(nFila){

    //PAGINA: porcentajesRetencion.php
    if(document.getElementById("txtPorcentaje"+nFila).value=="" ){
        alert("Faltan Campos por llenar");

    }else{
        var respuesta61 = confirm("Seguro desea guardar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
        if (respuesta61){
        var str = $("#frmPorcentajesRetencion").serialize();
            $.ajax({
                    url: 'sql/retenciones.php',
                    type: 'post',
                    data: str+"&numeroFilaSelec="+nFila,
                    success: function(data){
                        //$("#div_listar_RegistroDiario").html(data);
                        document.getElementById("mensaje1").innerHTML=""+data;
                        listarPorcentajesRetencion();
                    }
            });
           }
    }
}

function modificarPorcentajesRetencion(id, accion, fila){
    //PAGINA: porcentajesRetencion.php
    var respuesta62 = confirm("Seguro desea modificar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
    if (respuesta62){
    var str = $("#frmPorcentajesRetencion").serialize();
	$.ajax({
		url: 'sql/retenciones.php',
		type: 'post',
		data: str+"&id="+id+"&accion="+accion+"&NumeroFilaSeleccionada="+fila,
		success: function(data){
                    //$("#div_listar_RegistroDiario").html(data);
                    document.getElementById("mensaje1").innerHTML=""+data;
                    listarPorcentajesRetencion();
		}
	});
       }
}

function eliminarPorcentajesRetencion(id, accion){
    //PAGINA: porcentajesRetencion.php
    var respuesta63 = confirm("Seguro desea eliminar este Registro? \nEsta acci\u00f3n eliminara de forma permanente la fila seleccionada");
    if (respuesta63){
        $.ajax({
                url: 'sql/retenciones.php',
                data: 'id='+id+'&accion='+accion,
                type: 'post',
                success: function(data){
                    if(data!="")
                    //alert(data);
                    document.getElementById("mensaje1").innerHTML=""+data;
                    listarPorcentajesRetencion();
                }
        });
    }
}

function porcentajesRetencion(){
    //MUESTRA EN LA PAGINA EL LISTADO DE PORCENTAJES DE RETENCIONES
    miUrl = "porcentajesRetencion.php";
    window.open(miUrl,'noimporta','width=650, height=500, scrollbars=NO, titlebar=no');
}





//---------------------  MI PERFIL -------------
function guardar_miPerfil(txtAccion){
    // GUARDA MI PERFIL EN LA PAGINA:  sql/usuarios.php
    var ciudad = document.form.opcion2.value//validamos si es > que cero
    var contrasena = "";
    contrasena = validarContrasena(form);// retorna true o false

        if(ciudad >= 1){
                if(contrasena == true){

                        var str = $("#form").serialize();
                        $.ajax({
                                url: 'sql/usuarios.php',
                                data: str+"&txtAccion="+txtAccion,
                                type: 'POST',
                                success: function(data){
                                document.getElementById('mensaje1').innerHTML+=""+data;
                             
                                }
                        });

                }else{
                    alert ('No se puede guardar porque las contrase\u00f1as no son iguales');
                    document.form.txtPassword.focus();
                    document.form.txtPassword.value="";
                    document.form.txtRpPassword.value="";
                    document.getElementById("errorPassword").innerHTML="" ;
                }
       }else{
                alert ('No se puede guardar porque no ha seleccionado su ciudad');
                document.form.cbciudad.focus();
       }
}

function irEmpresa1(id_empresa, periodo, empresa_nombre, accion){
   
        $.ajax({
                url: 'sql/empresa.php',
                data: 'id_empresa='+id_empresa+'&periodo='+periodo+'&empresa_nombre='+empresa_nombre+'&accion='+accion,
                type: 'post',
                success: function(data){                    
                    if(data!=""){                        
                        window.location="menuPrincipalCondominios.php";                        
                    }                    
                    
                }
        });  
    
}

function irEmpresa(id_empresa, periodo, empresa_nombre, accion)
{
	     //data: 'id_empresa='+id_empresa+'&periodo='+periodo+'&empresa_nombre='+empresa_nombre+'&accion='+accion,
       //alert(accion);   
	//   alert("va a ingreso");
        $.ajax({
                url: 'sql/ingresoCurso.php',
                data: 'id_empresa='+id_empresa+'&accion='+accion,
                type: 'post',
                success: function(data){    
//alert("DATA=");				
//alert(data);				
                    if(data!="1"){                        
                       // window.location="menuPrincipalCondominios.php";     
					   location.href='menuPrincipalCondominios.php';						
                    }                    
                }
        });  
    
}

function accesoSistema(){

    var str = $("#form").serialize();
    $.ajax({
            url: 'ver_sesion.php',
            data: str,
            type: 'POST',
            success: function(data){
                //alert("entro "+data);
                if(data == 7){

                }else{
                    $("#div_oculto").load("ajax/logearse.php", function(){
                            $.blockUI({
                                    message: $('#div_oculto'),
                            overlayCSS: {backgroundColor: '#111'},
                                    css:{

                                            '-webkit-border-radius': '10px',
                            '-moz-border-radius': '10px',

                                            background: '#f9f9f9',
                                            top: '150px',
                                            /*left: '185px',*/
                                            /* position: 'center', */
                                            width: '250px'
                                    }
                            });
                    });
                }
            }
    });
}

/*
function contabilidad(){

    var str = $("#form").serialize();
    $.ajax({
            url: 'ver_sesion.php',
            data: str,
            type: 'POST',
            success: function(data){
                //alert("entro "+data);
                if(data == 7){

                }else{
                    $("#div_oculto").load("ajax/logearse.php", function(){
                            $.blockUI({
                                    message: $('#div_oculto'),
                            overlayCSS: {backgroundColor: '#111'},
                                    css:{

                                            '-webkit-border-radius': '10px',
                            '-moz-border-radius': '10px',

                                            background: '#f9f9f9',
                                            top: '150px',
                                            /*left: '185px',*/
                                            /* position: 'center', */ /*
                                            width: '250px'
                                    }
                            });
                    });
                }
            }
    });

}

*/

