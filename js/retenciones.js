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


function guardar_datos_retencion(accion){ 
       var str = $("#formReten").serialize();
        $.ajax
		({
            url: 'sql/retenciones.php',
            type: 'post',
            data: str+"&accion="+accion,
            beforeSend: function(){
                $("#mensaje1").html("<p align='center'><img src='images/loading.gif' /></p>");
            },
            success: function(data)
			{
			    if(data='1'){
			        alertify.success("Datos ingresados correctamente");
			    }else{
			        alertify.warning("Revise los datos");
			    }
            }
        });
    }
    
    
    function actulizar_datos_retencion(accion){ 
       var str = $("#formReten").serialize();
        $.ajax
		({
            url: 'sql/retenciones.php',
            type: 'post',
            data: str+"&accion="+accion,
            beforeSend: function(){
                $("#mensaje1").html("<p align='center'><img src='images/loading.gif' /></p>");
            },
            success: function(data)
			{
			    console.log(data);
			   if(data='1'){
			        alertify.success("Datos actualizados correctamente");
			    }else{
			        alertify.warning("Revise los datos");
			    }
            }
        });
    }
    
    