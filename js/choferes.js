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

function nuevochofer(id){
 //PAGINA: productos.php
console.log("id",id)
	$("#div_oculto").load("ajax/nuevochofer.php?id="+id, function(){
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
// 		listarTransportistas();
	});
}
function guardar_transportista(accion){
         var str = $("#formEst").serialize();
         console.log("str",str);
        let id= document.getElementById('id').value;
        let accion_actual = accion;
        if(id!=-1){
            accion_actual= 2;
        }

          $.ajax(
		{
			url: 'sql/choferes.php',
            data: str+"&txtAccion="+accion_actual,
            type: 'post',
            success: function(data)	{
			console.log(data);
				if(data==1){
				alertify.success("transportista agregado con exito :)");
				// $('#listadoEst').load('ajax/listadoEst.php');
                listarTransportistas(1);
				fn_cerrar()
			}else if(data==5){
				alertify.success("transportista modificado con exito :)");
                listarTransportistas(1);
                fn_cerrar()
			}else{
				alertify.error("Transportista ya existe");
                // fn_cerrar()
			}
            }
        });

}
function listarTransportistas(page){
 //PAGINA: formas de pago.php
    //alert("listarCentroCostos");
    var str = $("#form1").serialize();
    $.ajax
	({
            url: 'ajax/listarTransportistas.php',
            type: 'get',
            data: str+"&page="+page,
            success: function(data){
               // console.log("data",data);
                $("#listarTransportistas").html(data);
                // cantidad_centros();
            }
    });
}

function preguntarSiNoTransportista(id){
	alertify.confirm('Eliminar Datos', '多Esta seguro de eliminar este registro?', 
		function(){ 
			eliminarTransportista(id) 
		}
		, function(){ alertify.error('Se cancelo')});
}

function eliminarTransportista(id){
	$.ajax(
	{
		url: 'sql/choferes.php',
		data: "&txtAccion=3&id="+id,
		type: 'post',
		success: function(data)	{
			//////console.log(data);
			if(data==1){
				alertify.success("Transportista eliminado con exito :)");
				$('#listadoOrganizaciones').load('ajax/listarOrganizaciones.php');
			}else{
				alertify.error("OTRA");
			}
		}
	});
}