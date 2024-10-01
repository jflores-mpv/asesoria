function nuevodatotransportista(){
 //PAGINA: productos.php
 //alert("sss");
	$("#div_oculto").load("ajax/nuevodatotransportista.php", function(){
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

function guardar_datos_transporte(accion){
         var str = $("#formdatostrans").serialize();
         console.log("str",str);
          $.ajax(
		{
			url: 'sql/datosTransportistas.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data)	{
			console.log(data);
				if(data==1){
				alertify.success("datos transportistas agregados con exito :)");
				$('#listadoEst').load('ajax/listadoEst.php');
				fn_cerrar()
			}else{
				alertify.error("Establecimiento ya existe");
			}
            }
        });

}
