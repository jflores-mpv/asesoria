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
function clientesCrm(){
	var str = $("#frmClientes").serialize();
	$.ajax({
		url: 'ajax/listarClientesCrm.php',
		type: 'get',
		data: str,
		success: function(data){
               // //console.log(data);
               $("#listarClientesCrm").html(data);
           }
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
    });
}


function leads(){
	var str = $("#frmLeads").serialize();
	$.ajax({
		url: 'ajax/listadoLeads.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#listarleads").html(data);

		}
	});
}


function leadsSinTareas(){
	var str = $("#frmLeads").serialize();
	$.ajax({
		url: 'ajax/listadoLeadSinTareas.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#listarleadsSinTareas").html(data);
		}
	});
}


function leadsSinGestion(){

	var str = $("#frmLeads").serialize();
	$.ajax({
		url: 'ajax/leadsSinGestion.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#leadsSinGestion").html(data);
		}
	});
}

function leadsTareasHoy(){
	var str = $("#frmLeads").serialize();
	$.ajax({
		url: 'ajax/tareasParaHoy.php',
		type: 'get',
		data: str,
		success: function(data){
                //console.log(data);
                $("#listarleadsParaHoy").html(data);
            }
        });
}

function leadsTareasVencidas(){

	var str = $("#frmLeads").serialize();
	console.log(str);
	$.ajax({
		url: 'ajax/leadsTareasVencidas.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#leadsTareasVencidas").html(data);
		}
	});
}

function listarleadsFavoritos(){

	var str = $("#frmLeads").serialize();
	$.ajax({
		url: 'ajax/listarleadsFavoritos.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#listarleadsFavoritos").html(data);
		}
	});
}





function tareas(id){
	var str = $("#frmClientes").serialize();
	$.ajax({
		url: 'ajax/info.php',
		type: 'get',
		data:  str+"&id="+id,
		success: function(data){
			$("#divTareas").html(data);
		}
	});
}

function infoDiaria(id){
	var str = $("#frmClientes").serialize();
	$.ajax({
		url: 'ajax/infoDiaria.php',
		type: 'get',
		data:  str+"&id="+id,
		success: function(data){
			$("#divUltimaTarea").html(data);
		}
	});
}


function editarTarea(id){
 //PAGINA: productos.php
 //alert("sss");
 $("#div_oculto").load('ajax/editarTarea.php?id='+id, 

 	function(){
 		$.blockUI({
 			message: $('#div_oculto'),
 			overlayCSS: {backgroundColor: '#111'},
 			css:{
 				'-webkit-border-radius': '3px',
 				'-moz-border-radius': '3px',
 				'box-shadow': '6px 6px 20px gray',
 				top: '10%',
 				left: '15%',
 				width:'75%',
 				position: 'absolute'

 			}
 		});
 	});
}

function guardarEditarTarea(accion){
	var str = $("#formOrg").serialize();
	console.log(str);
	$.ajax(
	{
		url: 'sql/crm.php',
		data: str+"&txtAccion="+accion,
		type: 'post',
		success: function(data)	{
			console.log("data",data);
			if(data==1){
				alertify.success("Tarea actualizada con exito :)");
				leads();
				fn_cerrar();
			}else if(data==3){
				alertify.warning("Organizacion ya existe :)");
			}else{
				alertify.error("OTRA");
			}
		}
	});
}



function nueva_organizacion(id){
 //PAGINA: productos.php
 //alert("sss");
 $("#div_oculto").load('ajax/nueva_organizacion.php?id='+id, 

 	function(){
 		$.blockUI({
 			message: $('#div_oculto'),
 			overlayCSS: {backgroundColor: '#111'},
 			css:{
 				'-webkit-border-radius': '3px',
 				'-moz-border-radius': '3px',
 				'box-shadow': '6px 6px 20px gray',
 				top: '10%',
 				left: '15%',
 				width:'75%',
 				position: 'absolute'

 			}
 		});
 	});
}

function guardar_organizacion(accion,id){
	var str = $("#formOrg").serialize();
	console.log(str);
	$.ajax(
	{
		url: 'sql/crm.php',
		data: str+"&txtAccion="+accion+"&id="+id,
		type: 'post',
		success: function(data)	{
			console.log("data",data);
			if(data==1){
				alertify.success("Tarea agregada con exito :)");
				tareas(id);
			}else if(data==3){
				alertify.warning("Organizacion ya existe :)");
			}else{
				alertify.error("OTRA");
			}
		}
	});
}

function modificarOrganizacion(accion,id){
	var str = $("#formOrg").serialize();
	console.log(str);
	$.ajax(
	{
		url: 'sql/crm.php',
		data: str+"&txtAccion="+accion+"&id="+id,
		type: 'post',
		success: function(data)	{
			console.log("data",data);
			if(data==1){
				alertify.success("Tarea Actualizada con exito :)");
				tareas(id);
			}else if(data==3){
				alertify.warning("Organizacion ya existe :)");
			}else{
				alertify.error("OTRA");
			}
		}
	});
}

function preguntarSiNo(id,accion){
	alertify.confirm('Eliminar Datos', '多Esta seguro de eliminar este registro?', 
		function(){ 
			eliminarOrganizacion(id,accion) 
		}
		, function(){ alertify.error('Se cancelo')});
}

function eliminarOrganizacion(id,accion){
	console.log(id);
	console.log(accion);
	var str = $("#formOrg").serialize();

	$.ajax(
	{
		url: 'sql/crm.php',
		data: "&txtAccion="+accion+"&id="+id,
		type: 'post',
		success: function(data)	{
			//console.log(data);
			if(data==1){
				alertify.success("Organizacion eliminada con exito :)");
				$('#listadoOrganizaciones').load('ajax/listarOrganizaciones.php');
			}else if(data==3){
				alertify.warning("Organizacion ya existe :)");
			}else{
				alertify.error("OTRA");
			}
		}
	});

}

function info(id){
	$("#div_oculto").load('ajax/info.php?id='+id, 
		function(){
			$.blockUI({
				message: $('#div_oculto'),
				overlayCSS: {backgroundColor: '#111'},
				css:{
					'-webkit-border-radius': '3px',
					'-moz-border-radius': '3px',
					'box-shadow': '6px 6px 20px gray',
					top: '10%',
					left: '15%',
					width:'75%',
					position: 'absolute'
				}
			});
		});
}





function guardar_lead(accion){
	var str = $("#frmLead").serialize();
	var fechaHacer = document.getElementById("frmLead").fechaHacer.value;     
	var str2 = str+"&fechaHacer="+fechaHacer;

	$.ajax(
	{
		url: 'sql/crm.php',
		data: str2+"&txtAccion="+accion,
		type: 'post',
		success: function(data)	{
			console.log(data);
			if(data==1){
				alertify.success("lead agregado con exito :)");
				//	clientesCrm();
				leads();
				fn_cerrar();
			}else if(data==2){
				alertify.warning("Lead ya existe :)");
			}else{
				alertify.error("OTRA");
			}
		}
	});
}

function modificar_lead(id){
	$("#div_oculto").load('ajax/modificarLead.php?id='+id, 
		function(){
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




function guardar_modificar_lead(id,accion){
	var str = $("#frmLead").serialize();
	console.log(str);
	$.ajax(
	{
		url: 'sql/crm.php',
		data: str+"&id="+id+"&txtAccion="+accion,
		type: 'post',
		success: function(data)	{
			//console.log(data);
			if(data==1){
				alertify.success("lead Modificado con exito :)");
				clientesCrm();
				leads();
				fn_cerrar();
			}else if(data==2){
				alertify.warning("Lead ya existe :)");
			}else{
				alertify.error("OTRA");
			}
		}
	});
}






function listarOrigenes(){

	$("#div_oculto").load("ajax/listarOrigenes.php", function(){
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



function modificarOrigen(accion,contador){

	console.log("accion",accion);
	console.log("contador",contador);
  //  var respuesta11 = alertify.confirm("Actualizar Origen?");

//	if (respuesta11){
	var str = $("#frmOrigenes"+contador).serialize();
	console.log(str);
	$.ajax({
		url: 'sql/crm.php',
		data: str +'&txtAccion='+accion+'&contador='+contador,
		type: 'post',
		success: function(data){
			    //console.log(data);
			    if(data='1'){
			    	alertify.success("Origen Actualizad0");
			    	listarOrigenes();
			    }else{
			    	alertify.eror("Origen no actualizado");
			    }
			}
		});
//	}
}

function eliminarOrigen(accion,contador){

	console.log("accion",accion);
	console.log("contador",contador);

	var str = $("#frmOrigenes"+contador).serialize();
	console.log(str);
	$.ajax({
		url: 'sql/crm.php',
		data: str +'&txtAccion='+accion+'&contador='+contador,
		type: 'post',
		success: function(data){
			    //console.log(data);
			    if(data='1'){
			    	alertify.success("Origen Eliminado");
			    	listarOrigenes();
			    }else{
			    	alertify.eror("Origen no actualizado");
			    }
			}
		});
	
}




function modificarInteraccion( accion,contador){
	alert("modificar interaccion");
	var str = $("#frmInteracciones"+contador).serialize();
	console.log(str);

	$.ajax({
		url: 'sql/crm.php',
		data: str +'&txtAccion='+accion+'&contador='+contador,
		type: 'post',
		success: function(data){
			    //console.log(data);
			    if(data='1'){
			    	alertify.success("Interaccion Actualizada");
			    	listarOrigenes();
			    }else{
			    	alertify.eror("Interaccion no actualizado");
			    }
			}
		});

}



function agregarOrigen(accion)
{

	var str = $("#frmNuevoOrigen").serialize();
	$.ajax(
	{
		url: 'sql/crm.php',
		data: str+"&txtAccion="+accion,
		type: 'post',
		success: function(data)	{
            //console.log(data);
            if(data==1){
            	alertify.success("Nuevo Origen creado con exito :)");
            	listarOrigenes();
            }else if(data==2){
            	alertify.warning("Lead ya existe :)");
            }else{
            	alertify.error("OTRA");
            }
        }
    });
}

function agregarInteraccion(accion)
{
	console.log("accion",accion);
	var str = $("#frmNuevaInteraccion").serialize();
	console.log(str);
	$.ajax(
	{
		url: 'sql/crm.php',
		data: str+"&txtAccion="+accion,
		type: 'post',
		success: function(data)	{
                        //console.log(data);
                        if(data==1){
                        	alertify.success("Nueva creada interaccion con exito :)");
                        	listarOrigenes();
                        }else if(data==2){
                        	alertify.warning("Lead ya existe :)");
                        }else{
                        	alertify.error("OTRA");
                        }
                    }
                });
}

function eliminarInteraccion(accion,contador){
	var str = $("#frmInteracciones"+contador).serialize();
	console.log(str);
	$.ajax({
		url: 'sql/crm.php',
		data: str +'&txtAccion='+accion+'&contador='+contador,
		type: 'post',
		success: function(data){
			    //console.log(data);
			    if(data='1'){
			    	alertify.success("Origen Eliminado");
			    	listarOrigenes();
			    }else{
			    	alertify.eror("Origen no actualizado");
			    }
			}
		});
	
}


function agregarNivel(accion)
{
	console.log("accion",accion);
	var str = $("#frmNuevoNivel").serialize();

	$.ajax(
	{
		url: 'sql/crm.php',
		data: str+"&txtAccion="+accion,
		type: 'post',
		success: function(data)	{

			if(data==1){
				alertify.success("Nueva creada interaccion con exito :)");
				listarOrigenes();
			}else if(data==2){
				alertify.warning("Lead ya existe :)");
			}else{
				alertify.error("OTRA");
			}
		}
	});
}

function eliminarNivelLead(accion,contador){
	var str = $("#frmNivelLead"+contador).serialize();

	$.ajax({
		url: 'sql/crm.php',
		data: str +'&txtAccion='+accion+'&contador='+contador,
		type: 'post',
		success: function(data){

			    //console.log(data);
			    
			    if(data='1'){
			    	alertify.success("Origen Eliminado");
			    	listarOrigenes();
			    }else{
			    	alertify.eror("Origen no actualizado");
			    }
			}
		});
	
}



function cotizador(id){

	$("#div_oculto").load('ajax/cotizador.php?id='+id, function(){

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

function modificarNivel(accion,contador){
	var str = $("#frmNivelLead"+contador).serialize();
	$.ajax({
		url: 'sql/crm.php',
		data: str +'&txtAccion='+accion+'&contador='+contador,
		type: 'post',
		success: function(data){
			if(data='1'){
				alertify.success("Temperatura Actualizada");
				listarOrigenes();
			}else{
				alertify.eror("Temperatura no actualizada");
			}
		}
	});
}


function agregarOportunidad(accion){
	console.log("accion",accion);
	var str = $("#frmNuevaOportunidad").serialize();
	console.log(str);
	$.ajax({
		url: 'sql/crm.php',
		data: str+"&txtAccion="+accion,
		type: 'post',
		success: function(data)	{
                console.log(data);
                if(data==1){
                	alertify.success("Nueva oportunidad creada con exito :)");
                	listarOrigenes();
                }else if(data==2){
                	alertify.warning("Lead ya existe :)");
                }else{
                	alertify.error("OTRA");
                }
            }
        });
}


function modificarOportunidad(accion,contador){
	console.log("accion",accion);
	console.log("modificar oportunidad");
	var str = $("#frmOportunidad"+contador).serialize();
	console.log(str);

	$.ajax({
		url: 'sql/crm.php',
		data: str +'&txtAccion='+accion+'&contador='+contador,
		type: 'post',
		success: function(data){
			if(data='1'){
				alertify.success("Oportunidad Actualizada");
				listarOrigenes();
			}else{
				alertify.eror("Oportunidad no actualizada");
			}
		}
	});
}


function eliminarOportunidad(accion,contador){

	var str = $("#frmOportunidad"+contador).serialize();
	console.log(str);
	$.ajax({
		url: 'sql/crm.php',
		data: str +'&txtAccion='+accion+'&contador='+contador,
		type: 'post',
		success: function(data){

			    //console.log(data);
			    
			    if(data='1'){
			    	alertify.success("Oportunidad Eliminada");
			    	listarOrigenes();
			    }else{
			    	alertify.eror("Origen no actualizado");
			    }
			}
		});
}


function nuevaTarea(id, accion,id2){

	$("#div_oculto").load("ajax/motivo.php",{id: id, accion: accion, id2: id2},  function(){

		$.blockUI({
			message: $('#div_oculto'),
			overlayCSS: {backgroundColor: '#111'},
			css:{

				'-webkit-border-radius': '3px',
				'-moz-border-radius': '3px',
				'box-shadow': '6px 6px 20px gray',
				'border-radius': '0px 20px 20px 20px',


				top: '25%',

				position: 'absolute',
				width: '30%',
				left: '30%'


			}
		});
	});
}


function TareaRealizada(id, accion,id2,realizar){
// console.log(id);
	var str = $("#frmtareaActual").serialize();

	$.ajax({
		url: 'sql/crm.php',
		data: str+'&id=' +id+'&txtAccion='+accion+'&id2='+id2,
		type: 'post',
		success: function(data){
			    console.log(data);

			   if(data==1){
			   	alertify.success("Tarea Realizada");
			   	if(realizar==1){
			   	    nuevaTarea(id,5,id2);
			   	}
			    infoDiaria(id2);
				tareas(id2);
			   }else if (data==2) {
			   	alertify.error("Tarea No Realizada");
			   }else if (data==3) {
			   	alertify.error("Agregue Respuesta");
			   }

			}
		});
	
}


function TareaNoRealizada(id, accion,id2,realizar){
	var str = $("#frmtareaActual").serialize();
// 	console.log(str);
	$.ajax({
		url: 'sql/crm.php',
		data: str+'&id=' +id+'&txtAccion='+accion+'&id2='+id2,
		type: 'post',
		success: function(data){
			     console.log(data);
			     if(data==1){
    			   	alertify.success("Tarea Actualizada");
    			   	if(realizar==1){
    			   	    nuevaTarea(id,5,id2);
    			   	}
    			    infoDiaria(id2);
    				tareas(id2);
    				leads();
    			   }
			     
			    
			 }
			});
}


function nuevaTareaGuardar(id, accion,id2,form){
	console.log("form",form);
  var str = $("#" + form.id).serialize();

    // Utiliza jQuery para acceder al valor del campo fechaHacer
    var fecha = $("#" + form.id + " input[name='fechaHacer']").val();
// 	console.log("str",str);
	$.ajax({
		url: 'sql/crm.php',
		data: str+'&id=' +id+'&txtAccion='+accion+'&id2='+id2+'&fechaHacer='+fecha,
		type: 'post',
		success: function(data){
			console.log(data);

			if(data ='1'){
				alertify.success("Tarea agregada");
				fn_cerrar();
				infoDiaria(id2);
				tareas(id2);

			}else {
				alertify.error("Tarea no agregada");
			}
		}
	});
}


function favorito(id, num,accion){
	$.ajax({
		url: 'sql/crm.php',
		data: 'id=' +id+'&txtAccion='+accion+'&num='+num,
		type: 'post',
		success: function(data){

			if(data ='1')
				alertify.success("Lead Favorito");

			datosLead(id);
		}
	});
}


function datosLead(id){
	$.ajax({
		url: 'ajax/datosLead.php',
		type: 'post',
		data: "id="+id,
		success: function(data){

			$("#datosLead").html(data);

		}
	});
}


function modificarlead(accion,id){
	var str = $("#modificarLead").serialize();
	var fecha = document.getElementById("modificarLead").nacimientoLead.value;
	console.log(str);
	$.ajax({
		url: 'sql/crm.php',
		data: str+'&txtAccion='+accion+'&id='+id+'&fecha='+fecha,
		type: 'post',
		success: function(data){
			console.log(data);
			if(data='1'){
				alertify.success("Lead Actualizado");
				datosLead(id);
			}else{
				alertify.error("Lead no actualizado");
			}

		}
	});
}


function cargarProvincias(id){
	$.ajax({
		url:'sql/crm.php',
		type:'post',
		data: {txtAccion:id},
		success:function(res){
			$("#provincias").html(res);

		}
	});
}

function cargarCiudad(id,accion){
	console.log(id,accion);
	$.ajax({
		url:'sql/crm.php',
		type:'post',
		data: {id:id,txtAccion:accion},

		success:function(res){
		//	console.log(res);
			$("#ciudad").html(res);
		}
	});
}


function guardar_proyeccion(accion,id){

	var str = $("#frmCotizador").serialize();
	console.log(str);

	$.ajax(
	{
		url: 'sql/crm.php',
		data: str+"&txtAccion="+accion+"&id="+id,
		type: 'post',
		success: function(data)	{
			console.log(data);
			if(data==1){
				alertify.success("proyeccion agregada con exito :)");
				listarProyecciones(id);
				fn_cerrar();
			}else if(data==2){
				modificar_proyeccion(27,id)
			}else{
				alertify.error("OTRA");
			}
		}
	});
}

function modificar_proyeccion(accion,id){
	var str = $("#frmCotizador").serialize();

	$.ajax({
		url: 'sql/crm.php',
		data: str+'&txtAccion='+accion+'&id='+id,
		type: 'post',
		success: function(data){
			    //console.log(data);
			    if(data='1'){
			    	alertify.success("Cotizacion Actualizado");
			    	datosLead(id);
			    }else{
			    	alertify.eror("Cotizacion no actualizado");
			    }
			    
			}
		});
}


function laborales(id){

	$("#div_oculto").load('ajax/laborales.php?id='+id, function(){

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


function listarProyecciones(id){
	console.log(id);
	$.ajax({
		url: 'ajax/listarProyeccion.php',
		type: 'post',
		data: "&id="+id,
		success: function(data){
			$("#proyecciones").html(data);
			listarLaborales(id);
		}
	});
}


function listarLaborales(id){
	console.log(id);
	$.ajax({
		url: 'ajax/listarLaborales.php',
		type: 'post',
		data: "&id="+id,
		success: function(data){
			$("#laborales").html(data);
			economicos(id);
		}
	});
}

function economicos(id){
	console.log(id);
	$.ajax({
		url: 'ajax/listarEconomicos.php',
		type: 'post',
		data: "&id="+id,
		success: function(data){
			$("#economicos").html(data);
			conyuge(id);
		}
	});
}


function conyuge(id){
	console.log(id);
	$.ajax({
		url: 'ajax/listarConyuge.php',
		type: 'post',
		data: "&id="+id,
		success: function(data){
			$("#conyuge").html(data);

		}
	});
}



function aceptarPresupuesto(id,nombre){
	var cadena;
	console.log(nombre);
	cadena= nombre+" ,gracias por aceptar esta propuesta, en breve un asesor se comunicará contigo";
	alertify.confirm(cadena, 
		function(){ 
			alertify.success('Enviando Presupuesto')      
			presupuesto(id,3)

		}, 
		function(){ alertify.error('Cancel')});
}

function presupuesto(id,accion){

	$.ajax({
		url: 'sql/notificaciones.php',
		type: 'post',
		data: "txtAccion="+accion+"&id="+id,
		success: function(data){
			console.log(data);
			if (data=='1'){
				alertify.success("Presupuesto Aceptado Enviado");
			}else{
				alertify.error("Presupuesto no Enviado");
			}

		}
	}); 
}


function cargarUsuarios(id){
	$.ajax({
		url:'sql/crm.php',
		type:'post',
		data: {txtAccion:id},
		success:function(res){
			$("#usuario").html(res);
		}
	});
}


function listar_crm_leads(){

	console.log("LISTADO");

	var str = $("#formCrmLead").serialize();
	console.log(str);

	$.ajax({
		url: 'ajax/reporteLeadsTareas.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#div_listar_crm_lead").html(data);
		}
	});
}


function listar_crm_usuarios(){

	console.log("LISTADO");

	var str = $("#formCrmUsuarios").serialize();
	console.log(str);

	$.ajax({
		url: 'ajax/reporteUsuarioTareas.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#div_listar_crm_usuarios").html(data);
		}
	});
}

function listar_crm_tareas(){

	console.log("LISTADO");

	var str = $("#formCrmUsuarios").serialize();
	console.log(str);

	$.ajax({
		url: 'ajax/reporteUsuarioTareasCantidad.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#reporteUsuarioTareasCantidad").html(data);
		}
	});
}
// guarda datos laborales y economicos de prepupuestos -perfil economico
function guardar_laboral(accion,id){
	var str = $("#frmlaborales").serialize();
	var fechaIngreso = document.getElementById("frmlaborales").fechaIngreso.value; 
	var str2 = str+"&fechaIngreso="+fechaIngreso;   
	console.log(str);
	$.ajax(
	{
		url: 'sql/crm.php',
		data: str2+"&txtAccion="+accion+"&id="+id,
		type: 'post',
		success: function(data)	{
			console.log("data",data);
			if(data==1){
				alertify.success("Datos economicos agregados con exito :)");
				tareas(id);
				fn_cerrar();
				listarLaborales();
			}else if(data==3){
				alertify.warning("Datos economicos ya existen :)");
			}else{
				alertify.error("OTRA");
			}
		}
	});
}

function formularioLaboral(id){

	$("#div_oculto").load('ajax/editar_laborables.php?id='+id, function(){

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


function editar_laboral(accion,id){
	var str = $("#frmlaborales").serialize();
	var fechaIngreso = document.getElementById("frmlaborales").fechaIngreso.value; 
	var str2 = str+"&fechaIngreso="+fechaIngreso;   
	console.log(str);
	$.ajax({
		url: 'sql/crm.php',
		data: str2+'&txtAccion='+accion+'&id='+id,
		type: 'post',
		success: function(data){
			console.log(data);
			if(data='1'){
				alertify.success("Datos laborales actualizados");
				datosLead(id);
			}else{
				alertify.error("Datos laborales no actualizados");
			}

		}
	});
}


// muestra formulario para insertar nuevo conyuge
function formularioConyuge(id){

	$("#div_oculto").load('ajax/conyuge.php?id='+id, function(){

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
//al presionar el boton guardar del formulario conyuge se guardan los datos
function guardar_conyuge(accion,id){
	var str = $("#frmlaborales").serialize();
	var fechaNacimiento = document.getElementById("frmlaborales").fechaNacimiento.value; 
	var str2 = str+"&fechaNacimiento="+fechaNacimiento;   
	console.log(str);
	$.ajax(
	{
		url: 'sql/crm.php',
		data: str2+"&txtAccion="+accion+"&id="+id,
		type: 'post',
		success: function(data)	{
			console.log("data",data);
			if(data==1){
				alertify.success("Datos del conyuge agregados con exito :)");
				tareas(id);
			}else if(data==3){
				alertify.warning("Datos del conyuge ya existen :)");
			}else{
				alertify.error("OTRA");
			}
		}
	});
}
// muestra un formulario cargado con los datos del conyuge listos para editar
function editar_conyuge(id){

	$("#div_oculto").load('ajax/editar_conyuge.php?id='+id, function(){

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
//una vez editados los datos del conyuge se los actualiza mediante esta funcion
function modificar_conyuge(accion,id){
	var str = $("#frmlaborales").serialize();
	var fechaNacimiento = document.getElementById("frmlaborales").fechaNacimiento.value; 
	var str2 = str+"&fechaNacimiento="+fechaNacimiento;   
	console.log(str);
	$.ajax({
		url: 'sql/crm.php',
		data: str2+'&txtAccion='+accion+'&id='+id,
		type: 'post',
		success: function(data){
			console.log(data);
			if(data='1'){
				alertify.success("C&oacute;nyuge Actualizado");
				datosLead(id);
			}else{
				alertify.error("C&oacute;nyuge no actualizado");
			}

		}
	});
}


function comprobarCedula() {
        if(document.getElementById("txtCedula").value != ""){
            $("#loaderIcon").show();
	jQuery.ajax({
	url: "../sql/comprobarCampos.php",
	data:'cedula='+$("#txtCedula").val(),
	type: "POST",
	success:function(data){
	
		$("#loaderIcon").hide();
        var cadena;
        cadena =  data;
     

    if(cadena !="<span style='color:green' class='estado-disponible-usuario'> Cedula disponible.</span>"){
        $("#estadoCedula").html(data);
        document.getElementById("txtCedula").value = "";
        alertify.confirm(cadena);
    }

	},
	error:function (){}
	});
        }


}
function comprobarEmail() {

    if(   document.getElementById("txtEmail").value != ""){
        $("#loaderIcon").show();
	jQuery.ajax({
	url: "../sql/comprobarCampos.php",
	data:'email='+$("#txtEmail").val(),
	type: "POST",
	success:function(data){
		
		$("#loaderIcon").hide();
        var cadena;
        cadena =  data;
    
    if(cadena !="<span style='color:green' class='estado-disponible-usuario'> Email disponible.</span>"){
        document.getElementById("txtEmail").value = "";
        alertify.confirm(cadena);
        $("#estadoEmail").html(data);
    }

	},
	error:function (){}
	});
    }

	
}
function comprobarTelefono() {
        
    if( document.getElementById("txtTelefono").value != ""){

        $("#loaderIcon").show();
	jQuery.ajax({
	url: "../sql/comprobarCampos.php",
	data:'telefono='+$("#txtTelefono").val(),
	type: "POST",
	success:function(data){
		
		$("#loaderIcon").hide();
        var cadena;
        cadena =  data;
        
    if(cadena !="<span style='color:green' class='estado-disponible-usuario'> Telefono disponible.</span>"){
        document.getElementById("txtTelefono").value = "";
        alertify.confirm(cadena);
        $("#estadoTelefono").html(data);
    }
	},
	error:function (){}
	});
    }

}



//agregar participante al equipo

function agregarParticipante(accion)
{

	var str = $("#nuevoParticipante").serialize();

	$.ajax(
	{
		url: 'sql/crm.php',
		data: str+"&txtAccion="+accion,
		type: 'post',
		success: function(data)	{
            //console.log(data);
            if(data==1){
            	alertify.success("Nuevo participante creado con exito :)");
				loadGrupoUsuarios();
				loadTodosGrupos();
            	loadFormaGrupos();
            }else if(data==2){
            	alertify.warning("Participante ya existe :)");
            }else{
            	alertify.error("OTRA");
            }
        }
    });
}
function equipos(id){
	
	$.ajax({
		url: 'ajax/listadoEquipos.php',
		type: 'post',
		data: "&id="+id,
		success: function(data){
			// if(id=1){
			// 	$("#listarEquipo1").html(data);
			// }
			// if(id=2){
			// 	$("#listarEquipo2").html(data);
			// }
			//document.getElementById("#listarEquipos").innerHTML = "";
			$("#listarEquipos"+id).html(data);

		}
	});
}

function eliminarParticipante(accion,contador){

	console.log("accion",accion);
	console.log("contador",contador);

	var str = $("#listaParticipantes").serialize();
	console.log(str);
	$.ajax({
		url: 'sql/crm.php',
		data: str +'&txtAccion='+accion+'&contador='+contador,
		type: 'post',
		success: function(data){
			    //console.log(data);
			    if(data='1'){
			    	alertify.success("Participante Eliminado");
					loadGrupoUsuarios();
					loadTodosGrupos();
			    	loadFormaGrupos();
				


			    }else{
			    	alertify.eror("Participante no actualizado");
			    }
			}
		});
	
}

function eliminarGrupoEmpleados(accion,contador){

	console.log("accion",accion);
	console.log("contador",contador);

	var str = $("#frmOrigenes"+contador).serialize();
	console.log(str);
	$.ajax({
		url: 'sql/crm.php',
		data: str +'&txtAccion='+accion+'&contador='+contador,
		type: 'post',
		success: function(data){
			    //console.log(data);
			    if(data='1'){
					listarGruposUsuarios();
			    	
			    	loadGrupoUsuarios();
					loadTodosGrupos();
					loadFormaGrupos();
					loadTitulosGrupos();
					openCity(null, '<?php echo $verE[0] ?>')
					alertify.success("Grupo Eliminado");
			    }else{
			    	alertify.eror("Grupo no actualizado");
			    }
			}
		});
	
}



function loadFormaGrupos(){

	let qw="a";
	$.ajax({
		url: 'ajax/listarDetalleEquipos.php',
		type: 'post',
		data: qw,
		success: function(data){
		
			$("#listarUsuariosGruposTodos").html(data);
		
		}
	});
}

// seleccionar lider equipos en los usuarios que tienen grupo

function liderequipo(accion,usuario,equipo){

	console.log("accion",accion);
	console.log("usuario ",usuario);
	console.log("equipo",equipo);

	
	$.ajax({
		url: 'sql/crm.php',
		data: '&txtAccion='+accion+'&usuario='+usuario+'&equipo='+equipo,
		type: 'post',
		success: function(data){
			    //console.log(data);
			    if(data='1'){
			    	alertify.success("Participante lider elegido");
					loadGrupoUsuarios();
					loadTodosGrupos();
			    	loadFormaGrupos();
			    }else{
			    	alertify.eror("Participante lider no elegido");
			    }
			}
		});
	
}
//INICIO modificar grupo de empleados , ej : grupo 1 a grupo1

function modificarGrupoEmpleados(accion,contador){
console.log("si modifica");
	var str = $("#frmOrigenes"+contador).serialize();
	console.log(str);
	$.ajax({
		url: 'sql/crm.php',
		data: str +'&txtAccion='+accion+'&contador='+contador,
		type: 'post',
		success: function(data){
			    //console.log(data);
			    if(data='1'){
					listarGruposUsuarios();
			    	alertify.success("Grupo actualizado");
			    	loadGrupoUsuarios();
					loadTodosGrupos();
					loadFormaGrupos();
				
			    }else{
			    	alertify.eror("Grupo no actualizado");
			    }
			}
		});
	
}

// fin modificar grupo de empleados 

//INICIO insertar o modificar los usuarios sin grupo y asginarles un grupo

function modificarEmpleadosSinGrupo(accion,contador,usuario){
	console.log("");
		var str = $("#formUsuarioSinGrupo").serialize();
		console.log(str);
		$.ajax({
			url: 'sql/crm.php',
			data: str +'&txtAccion='+accion+'&contador='+contador+'&usuario='+usuario,
			type: 'post',
			success: function(data){
					//console.log(data);
					if(data='1'){
					
						alertify.success("Usuario actualizado");
						loadGrupoUsuarios();
						loadTodosGrupos();
						loadFormaGrupos();
						loadTitulosGrupos();
					}else{
						alertify.eror("Usuario no actualizado");
					}
				}
			});
		
	}
	
	// fin modificar grupo de empleados 

// seleccionar lider equipos en los usuarios sin grupo

function liderequiposs(accion,usuario,contador){
	var str = $("#formUsuarioSinGrupo").serialize();


	$.ajax({
		url: 'sql/crm.php',
		data: str+'&txtAccion='+accion+'&usuario='+usuario+'&contador='+contador,
		type: 'post',
		success: function(data){
			    //console.log(data);
			    if(data='1'){
			    	alertify.success("Participante lider elegido");
					loadGrupoUsuarios();
					loadTodosGrupos();
			    	loadFormaGrupos();
			    }else{
			    	alertify.error("Participante lider no elegido");
			    }
			}
		});
	
}

// INICIO recarga titulos de equipos ej; equipo 1 equpos2

function loadTitulosGrupos(){

	let qw="a";
	$.ajax({
		url: 'ajax/titulosEquipos.php',
		type: 'post',
		data: qw,
		success: function(data){
			// if(id=1){
			// 	$("#listarEquipo1").html(data);
			// }
			// if(id=2){
			// 	$("#listarEquipo2").html(data);
			// }
			//document.getElementById("#listarEquipos").innerHTML = "";
			
			$("#listarTitulosGrupos").html(data);
		console.log("secargo el titulo de cada equipo");
		}
	});
}

// FIN recarga titulos de equipos

function listarGruposUsuarios(){

	$("#div_oculto").load("ajax/listarGruposUsuarios.php", function(){
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


// crear nuevo grupo de empleados


function generarGrupoEmpleados(accion)
{

	var str = $("#frmGrupoEmpleado").serialize();
	$.ajax(
	{
		url: 'sql/crm.php',
		data: str+"&txtAccion="+accion,
		type: 'post',
		success: function(data)	{
            console.log(data);
            if(data==1){
				listarGruposUsuarios();
            	alertify.success("Nuevo grupo creado con exito :)");
				loadGrupoUsuarios();
				loadTodosGrupos();
			

            }else if(data==2){
            	alertify.warning("Grupo ya existe :)");
            }else{
            	alertify.error("OTRA");
            }
        }
    });
}

// INICIO AUTOCARGA USUARIOS EN SELECT DE EQUIPOS 

function loadGrupoUsuarios(){

	let qw="a";
	$.ajax({
		url: 'ajax/listadoUsuariosSingrupo.php',
		type: 'post',
		data: qw,
		success: function(data){
			// if(id=1){
			// 	$("#listarEquipo1").html(data);
			// }
			// if(id=2){
			// 	$("#listarEquipo2").html(data);
			// }
			//document.getElementById("#listarEquipos").innerHTML = "";
			
			$("#listarUsuariosGrupos").html(data);
		
		}
	});
}

// FIN AUTOCARGA USUARIOS EN SELECT DE EQUIPOS 

function loadTodosGrupos(){

	let qw="a";
	$.ajax({
		url: 'ajax/listarTodosEquipos.php',
		type: 'post',
		data: qw,
		success: function(data){
			// if(id=1){
			// 	$("#listarEquipo1").html(data);
			// }
			// if(id=2){
			// 	$("#listarEquipo2").html(data);
			// }
			//document.getElementById("#listarEquipos").innerHTML = "";
			
			$("#cargarTodosEquipos").html(data);
		
		}
	});
}


//INICIO eliminar grupo de empleados

function eliminarGrupoEmpleados(accion,contador){

//	console.log("accion",accion);
//	console.log("contador",contador);

	var str = $("#frmOrigenes"+contador).serialize();
//	console.log(str);
	$.ajax({
		url: 'sql/crm.php',
		data: str +'&txtAccion='+accion+'&contador='+contador,
		type: 'post',
		success: function(data){
			    //console.log(data);
			    if(data='1'){
					listarGruposUsuarios();
			    	alertify.success("Grupo Eliminado");
			    	loadGrupoUsuarios();
					loadTodosGrupos();
			    }else{
			    	alertify.eror("Grupo no actualizado");
			    }
			}
		});
	
}

// fin eliminar grupo de empleados 


function listarUsuariosSinEquipo(){
	var str = $("#listarUsuariosSinEquipos").serialize();
	$.ajax({
		url: 'ajax/listarUsuariosSinEquipos.php',
		type: 'get',
		data: str,
		success: function(data){
               console.log(data);
               $("#listarUsuariosSinEquipos").html(data);
           }
       });
}


function modificarLeadOrigen(accion,id){
	var str = $("#origenLead").serialize();
	
	
//	console.log(str);
	$.ajax({
		url: 'sql/crm.php',
		data: str+'&txtAccion='+accion+'&id='+id,
		type: 'post',
		success: function(data){
			console.log(data);
			if(data='1'){
				alertify.success("Lead Actualizado");
				datosLead(id);
			}else{
				alertify.error("Lead no actualizado");
			}

		}
	});
}


function proyeccionAprobacion(id, num,accion,lead, abono){

	if(abono==0){
		$.ajax({
			url: 'sql/crm.php',
			data: 'id=' +id+'&txtAccion='+accion+'&num='+num+'&lead='+lead,
			type: 'post',
			success: function(data){

				if(data ='1')
					alertify.success("Aprobado");
				listarProyecciones(lead);
				
			}
		});
	}else{
		alertify.warning("Ya existe un abono realizado en la actual proyecci&oacute;n de compra");
	}

	
}


