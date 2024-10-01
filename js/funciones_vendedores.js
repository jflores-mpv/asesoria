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

function nuevo_vendedor(id=0){
 //PAGINA: productos.php
console.log("id",id)
	$("#div_oculto").load("ajax/nuevo_vendedor.php?id="+id, function(){
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
function guardar_vendedor(accion){
         var str = $("#formEst").serialize();
         
        let id= document.getElementById('id').value;
        let accion_actual = accion;
        if(id!=-1){
            accion_actual= 11;
        }

          $.ajax(
		{
			url: 'sql/vendedores.php',
            data: str+"&accion="+accion_actual,
            type: 'post',
            success: function(data)	{
			let response = data.trim();
				if(response==1){
				alertify.success("Vendedor agregado con exito :)");
                listarVendedores(1);
				fn_cerrar()
			}else if(response==3){
				alertify.error("Vendedor ya existe :)");
                listarVendedores(1);
                fn_cerrar()
			}else if(response==5){
				alertify.success("Vendedor modificado con exito :)");
                listarVendedores(1);
                fn_cerrar()
			}else if(response==6){
				alertify.error("Vendedor no se actualizo ");
                listarVendedores(1);
                fn_cerrar()
			}else{
				alertify.error("Vendedor ya existe");
               
			}
            }
        });

}
function listarVendedores(page=1){
 //PAGINA: formas de pago.php
    //alert("listarCentroCostos");
    var str = $("#form1").serialize();
    $.ajax
	({
            url: 'ajax/listarVendedores.php',
            type: 'get',
            data: str+"&page="+page,
            success: function(data){
               // console.log("data",data);
                $("#listarVendedores").html(data);
                // cantidad_centros();
            }
    });
}

function preguntarSiNoVendedor(id){
	alertify.confirm('Eliminar Datos', '多Esta seguro de eliminar este registro?', 
		function(){ 
			eliminarVendedor(id) 
		}
		, function(){ alertify.error('Se cancelo')});
}

function eliminarVendedor(id){
	$.ajax(
	{
		url: 'sql/vendedores.php',
		data: "&accion=12&id="+id,
		type: 'post',
		success: function(data)	{
			let response = data.trim();
			if(response==1){
				alertify.success("Vendedor eliminado con exito :)");
				
			}else{
				alertify.error("OTRA");
			}
				listarVendedores();
		}
	});
}
function suspenderVendedor(id_vendedor, estado){
    var respuesta11 = confirm("Seguro desea Suspender/Activar este vendedor? \nEsta acci\u00f3n Suspendera/Activar de forma permanente la fila seleccionada");
	if (respuesta11){
		$.ajax({
			url: 'sql/vendedores.php',
			data: 'id_vendedor=' +id_vendedor+'&accion=13&estado='+estado,
			type: 'post',
			success: function(data){
			    let response = data.trim();
				if(response==1){
				    	alertify.success("Vendedor estado actualizado con exito :)");
				}else{
				    	alertify.error("Vendedor no se actualizo el estado.");
				}
				listarVendedores();
			}
		});
	}
}