function registroTipoPago(){
 //PAGINA: nuevaTransaccion.php
 //alert("registro tipo pago");
    $("#div_oculto").load("ajax/registro_tipoPago.php",  function(){
        $.blockUI({
            message: $('#div_oculto'),
			overlayCSS: {backgroundColor: '#111'},
                css:{
                     '-webkit-border-radius': '10px',
					 '-moz-border-radius': '10px',
                        background: '', 
                        top: '5%',
                        position: 'absolute',
                        width: '650px',
                        left: ($(window).width() - $('.caja').outerWidth())/2
                }
        });
    });
}


function guardar_registros(accion){
    
    /* Para obtener el idFormaPago */
    var idFormaPago = document.getElementById("cmbFormaPagoFP").value;
    arrayFormaPago = idFormaPago.split( "*" );
    
    var txtDebeFP = document.getElementById("txtDebeFP").value;
    var txtPagoFP = document.getElementById("txtPagoFP").value;
    var txtCambioFP = document.getElementById("txtCambioFP").value;
    
    
    if(arrayFormaPago[1] == "Ctas. por Cobrar"){
        
        var txtCuotasTP = document.getElementById("txtCuotasTP").value;
        var txtDiasPlazoTP = document.getElementById("txtDiasPlazoTP").value;
        var txtFechaTP = document.getElementById("txtFechaTP").value;
        
    }
    
    if(idFormaPago != 0){
        var str = $("#frmregistroCondominios").serialize();
        //var str2 = $("#frmFormaPago").serialize();

        $.ajax({
            url: 'sql/registros.php',
            type: 'post',
            data: str+"&txtAccion="+accion+"&idFormaPago="+arrayFormaPago[0]+"&txtCuotasTP="+txtCuotasTP+"&txtDiasPlazoTP="+txtDiasPlazoTP+"&txtFechaTP="+txtFechaTP+"&tipoMovimiento="+arrayFormaPago[1],
            // para mostrar el loadian antes de cargar los datos
            beforeSend: function(){
                //imagen de carga
                $("#mensajeFacturaVentaCondominios2").html("<p align='center'><img src='images/loading.gif' /></p>");
            },
            success: function(data){
                //alert(data.length);
                document.getElementById("mensajeFacturaVentaCondominios2").innerHTML=data;
                if(data.length == 87){
                    //document.getElementById("frmServicios").reset();
                }
                //listar_servicios();
            }
        });
    }else{
        alert ('Seleccione el Tipo de Pago.');
        document.getElementById("cmbFormaPagoFP").focus();
        //dml.elements['cmbFormaPagoFP'].focus();
    }
    
}

