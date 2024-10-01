function nuevo_centro_costo(id=0){
    //PAGINA: productos.php
    //alert("sss");
       $("#div_oculto").load("ajax/centro_costo.php?id="+id, function(){
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

   function confirmar_eliminar_centroCosto(id){
	alertify.confirm('Eliminar Centro de Costo', 'Esta seguro de eliminar este registro?', 
		function(){ 
			crud_centro_costo('3',id)
		}
		, function(){ alertify.error('Se cancelo')});
}

   function crud_centro_costo(accion,id=0){

    var str = $("#formulario_centro_costos").serialize();
    $.ajax(
		{
			url: 'sql/centros_costos.php',
            data: str+"&txtAccion="+accion+"&id="+id,
            type: 'post',
            success: function(data)	{
            let response= data.trim();

			if(response==1){
				alertify.success("Centro de Costo agregado con exito :)");
			}else if (response==2){
                alertify.error("Centro de Costo no se actualizo.");
            }else if (response==3){
                alertify.success("Centro de Costo actualizado con exito :)");
            }else if (response==4){
                alertify.error("Centro de Costo no se elimino.");
            }
            else if (response==5){
                alertify.success("Centro de Costo eliminado con exito :)");
            }
            else{		
                alertify.error("Centro de Costo no se guardo"); 	
            }
            $('#listadoCentroCostos').load('ajax/listadoCentroCostos.php');
            fn_cerrar()

            }
        });
    }