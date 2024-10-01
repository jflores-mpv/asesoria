function subirXML(){
//	fn_cerrar1();
    $("#div_oculto").load("ajax/subirXML.php", function(){
	$.blockUI({
			message: $('#div_oculto'),
			overlayCSS: {backgroundColor: '#111'},
			css:{
				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
				background: '', /* #f9f9f9*/
				top: '15%',
				position: 'absolute',
				width: '400',
					left: '30%'
                //left: ($(window).width() - $('.caja').outerWidth())/2
			}
		});
       // listar_vendedores();
	});
}
function repClientes(){
//	fn_cerrar1();
    $("#div_oculto").load("ajax/reporteClientes.php", function(){
	$.blockUI({
			message: $('#div_oculto'),
			overlayCSS: {backgroundColor: '#111'},
			css:{
				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
				background: '', /* #f9f9f9*/
				top: '15%',
				position: 'absolute',
				width: '400',
					left: '30%'
                //left: ($(window).width() - $('.caja').outerWidth())/2
			}
		});
       // listar_vendedores();
	});
}

// function listar_reporte_clientes(){
//     //PAGINA: cuentasPorCobrar.php
//  //  alert("listar_listar_reporte_clientes");
//     var str = $("#frmReporteFacturas").serialize();
// 	//alert(str);
//     $.ajax({
//             url: 'ajax/listarReporteClientes.php',
//             type: 'get',
//             data: str,
//             success: function(data){
//                 $("#div_listar_reporte_facturas").html(data);
//                  //cantidad_formas_pago();
//             }
//     });
// }


function listar_reporte_clientes(page=1){
    var str = $("#frmReporteFacturas").serialize();
    console.log(str);
    
    $.ajax({
            url: 'ajax/listarReporteClientes.php',
            type: 'get',
            data: str+"&page="+page,
            success: function(data){
                
                // console.log(data);
                $("#div_listar_reporte_facturas").html(data);
            }
    });
}

function listar_reporte_clientes_jd(page=1){
    var str = $("#frmClientes").serialize();
    console.log(str);
    
    $.ajax({
            url: 'ajax/listarReporteFacturasJd.php',
            type: 'get',
            data: str+"&page="+page,
            success: function(data){
                
                if(document.getElementById('div_listar_reporte_facturas_jd') ){
                     $("#div_listar_reporte_facturas_jd").html(data);
                }
               
            }
    });
}



function repSaldoInicial(){
//	alert("saldo inicial");
    $("#div_oculto").load("ajax/reporteSaldoInicial.php", function(){
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
        listar_vendedores();
	});
}

		 //listar_reporte_saldo_inicial
function listar_reporte_saldo_inicial(){
    //PAGINA: cuentasPorCobrar.php
  // alert("*");
    var str = $("#frmReporteRegistro").serialize();
	//alert(str);
    $.ajax({
            url: 'ajax/listarReporteSaldoInicial.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#div_listar_reporte_saldo_inicial").html(data);
                 //cantidad_formas_pago();
            }
    });
}

function repFacturas(){
    $("#div_oculto").load("ajax/reporteFacturas.php", function(){
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
       listar_reporte_facturas();
	});
}
function listar_reporte_facturas(page=1){

    var str = $("#frmClientes").serialize();
    var emi ='';
    if(document.getElementById("emision") ){
       emi = document.getElementById("emision").value;
    }
    let reporte = 'ajax/listarReporteFacturas.php';
    if(emi=='Totales'){
    reporte = 'ajax/listar_totales_emision_ventas.php';
    }

    $.ajax({
            url: reporte,
            type: 'get',
            data: str+"&page="+page,
            success: function(data){
                // console.log(data);
                $("#div_listar_reporte_facturas").html(data);
                 //cantidad_formas_pago();
            }
    });
}
// function listar_reporte_facturas(page){
//     //PAGINA: cuentasPorCobrar.php
//   // alert("reportes");
// //   console.log("pagina",page);
//     var str = $("#frmClientes").serialize();
//     // console.log("LISTADO"); 
//     // console.log(str);
//     $.ajax({
//             url: 'ajax/listarReporteFacturas.php',
//             type: 'get',
//             data: str+"&page="+page,
//             success: function(data){
//                 // console.log(data);
//                 $("#div_listar_reporte_facturas").html(data);
//                  //cantidad_formas_pago();
//             }
//     });
// }

function listar_reporte_pedidos(page){
    //PAGINA: cuentasPorCobrar.php
   // alert("reportes");
//   console.log("pagina",page);
    var str = $("#frmClientes").serialize();
    // console.log("LISTADO"); 
    // console.log(str);
    $.ajax({
            url: 'ajax/listarReportePedidos.php',
            type: 'get',
            data: str+"&page="+page,
            success: function(data){
                // console.log(data);
                $("#div_listar_reporte_pedidos").html(data);
                 //cantidad_formas_pago();
            }
    });
}

function listar_reportes_asientos(){
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

function listar_asientos(){
    //PAGINA: cuentasPorCobrar.php
   // alert("entro1");
    var str = $("#frmCuentasCobrar").serialize();
    $.ajax({
            url: 'ajax/listarAsientos.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#div_listar_cuentas_por_cobrar").html(data);
                 //cantidad_formas_pago();
            }
    });
}



function listar_reporte_transferencias(page=1){
    // console.log("listat");
    $("#first").select2('val');
    var str = $("#frmClientes").serialize();
    $.ajax({
            url: 'ajax/listarReporteTransferencias.php',
            type: 'get',
            data: str+"&page="+page,
            success: function(data){
                $("#div_listar_reporte_transferencias").html(data);
            }
    });
}
function listar_reporte_ConciliacionBancaria(page){
    var str = $("#frmClientes").serialize();
    $.ajax({
            url: 'ajax/listarReportesConciliacionesBancarias.php',
            type: 'get',
            data: str+"&page="+page,
            success: function(data){
                $("#div_listar_reporte_ConciliacionBancaria").html(data);
            }
    });
}