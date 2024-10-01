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

function nuevo_establecimiento(){
 //PAGINA: productos.php
 //alert("sss");
	$("#div_oculto").load("ajax/nuevoEst.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
			css:{
			 '-webkit-border-radius': '3px',
                '-moz-border-radius': '3px',
                'box-shadow': '6px 6px 20px gray',
				top: '10%',
				left: '35%',
                position: 'absolute'
			
			}
		});
	});
}



function guardar_establecimiento(accion){
         var str = $("#formEst").serialize();
          $.ajax(
		{
			url: 'sql/establecimientos.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data)	{
			console.log(data);
				if(data==1){
				alertify.success("Establecimiento agregado con exito :)");
				$('#listadoEst').load('ajax/listadoEst.php');
				fn_cerrar()
			}else{
				alertify.error("Establecimiento ya existe");
			}
            }
        });

}

function modificarEstablecimiento(id){
	$("#div_oculto").load("ajax/modificarEstablecimiento.php", {id: id}, function(){
		$.blockUI({
			message: $('#div_oculto'),
			overlayCSS: {backgroundColor: '#111'},
			css:{
				'-webkit-border-radius': '3px',
                '-moz-border-radius': '3px',
                'box-shadow': '6px 6px 20px gray',
				top: '10%',
				left: '35%',
                position: 'absolute'
			}
		});
	});
}

function guardarModificarEstablecimiento(accion){

        var str = $("#formEst").serialize();
        console.log(str);
          $.ajax(
		{
			url: 'sql/establecimientos.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data)	{
			console.log(data);
				if(data==1){
				alertify.success("Establecimiento modificado con exito :)");
				$('#listadoEst').load('ajax/listadoEst.php');
				fn_cerrar()
			}else{
				alertify.error("Establecimiento ya existe");
			}
            }
        });
}


function nuevo_emision(){
 //PAGINA: productos.php
 //alert("sss");
	$("#div_oculto").load("ajax/emision.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
			css:{
			 '-webkit-border-radius': '3px',
                '-moz-border-radius': '3px',
                'box-shadow': '6px 6px 20px gray',
				top: '10%',
				left: '35%',
                position: 'absolute'
			
			}
		});
	});
}



function guardar_emision(accion){
    var str = $("#formEmi").serialize();
    $.ajax(
		{
			url: 'sql/establecimientos.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data)	{
			console.log(data);
				if(data==1){
				alertify.success("Punto de Emision agregado con exito :)");
				$('#listadoEmi').load('ajax/listadoEmision.php');
				fn_cerrar()
			}else{		alertify.error("Puntos de emision ya existe"); 	}
            }
        });
    }


function modificarEmision(id){
	$("#div_oculto").load("ajax/modificarEmision.php", {id: id}, function(){
		$.blockUI({
			message: $('#div_oculto'),
			overlayCSS: {backgroundColor: '#111'},
			css:{
				'-webkit-border-radius': '3px',
                '-moz-border-radius': '3px',
                'box-shadow': '6px 6px 20px gray',
				top: '10%',
				left: '35%',
                position: 'absolute'
			}
		});
	});
}


function guardarModificarEmision(accion,id){

        var str = $("#formEmi").serialize();
console.log(str);
          $.ajax(
		{
			url: 'sql/establecimientos.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data)	{
              //  console.log(data);
			if(data==1){
				alertify.success("Establecimiento modificado con exito :)");
				$('#listadoEmi').load('ajax/listadoEmi.php');
				fn_cerrar()
			}else{
				alertify.error("Establecimiento ya existe");
			}
            }
        });
}


function eliminarEmision(accion,id){
        var str = $("#formEst").serialize();
        console.log(str);
        $.ajax(
		{
			url: 'sql/establecimientos.php',
            data: str+"&txtAccion="+accion+"&id="+id,
            type: 'post',
            success: function(data)	{
			if(data==1){
    				alertify.error("No se puede eliminar porque existen ventas realizadas de este punto");
    				$('#listadoEmi').load('ajax/listadoEmi.php');
    			}else {
    			    alertify.success("Establecimiento eliminado");	
    			    		$('#listadoEmi').load('ajax/listadoEmi.php');
    			}
            }
        });
}

function eliminarEstablecimiento(accion,id){
        var str = $("#formEst").serialize();

        $.ajax(
		{
			url: 'sql/establecimientos.php',
            data: str+"&txtAccion="+accion+"&id="+id,
            type: 'post',
            success: function(data)	{
    			if(data==1){
    				alertify.error("No se puede eliminar porque existen ventas realizadas de este establecimiento");
    				$('#listadoEst').load('ajax/listadoEst.php');
    			}else {
    			    alertify.success("Establecimiento eliminado");	
    			    		$('#listadoEst').load('ajax/listadoEst.php');
    			}
            }
        });
}
