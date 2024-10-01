function guardarCierreCaja(id,accion){
    let formaPago = document.getElementById('idFormaPago'+id).value;
    let detalle = document.getElementById('detalle'+id).value;
    let valor = document.getElementById('valor'+id).value;
    let fecha_cierre = document.getElementById('fecha_cierre').value;
    
    $.ajax(
    {
     url: 'sql/cierreCaja.php',
     data: "txtAccion="+accion+"&valor="+valor+"&formaPago="+formaPago+"&detalle="+detalle+"&fecha_cierre="+fecha_cierre,
     type: 'post',
     success: function(data)	{
         console.log(data);
         if(data==1){
            alertify.success("Pago agregado con exito :)");
            listarCierreCaja(1);
            fn_cerrar()
        }else{
            alertify.error("Error al guardar pago");
                // fn_cerrar()
            }
        }
    });

}
function listarCierreCaja(page){
 //PAGINA: formas de pago.php
    //alert("listarCentroCostos");
    var str = $("#form1").serialize();
    $.ajax
    ({
        url: 'ajax/listarCierreCaja.php',
        type: 'get',
        data: str+"&page="+page,
        success: function(data){
               // console.log("data",data);
               $("#listarCierreCaja").html(data);
                // cantidad_centros();
            }
        });
}

function preguntarSiNoCierreCaja(id){
	alertify.confirm('Eliminar Datos', '多Esta seguro de eliminar este registro?', 
		function(){ 
			eliminarTransportista(id) 
		}
		, function(){ alertify.error('Se cancelo')});
}

function eliminarCierreCaja(id){
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
function pdfCierreCaja( id_venta,txtFecha){

    miUrl = "reportes/rptCierreCaja.php?id_venta="+id_venta+"&fecha="+txtFecha;
    window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
} 

function lookup_formaPagoCierreCaja(txtNombre, cont, accion) {

   
    if(txtNombre.length == 0) {

        // Hide the suggestion box.
        $('#suggestions20').hide();
    } else {

        $.post("sql/cierreCaja.php", {queryString: txtNombre, cont: cont,  txtAccion: accion}, function(data){
           // alert("entro: "+data);
           if(data.length >0) {
            $('.suggestionsBox').hide();
            $('#suggestions20').show();
            $('#autoSuggestionsList20').html(data);
            
        }
    });
    }
} // lookup
function fill10_formaPago(cont, idServicio, cadena){
//console.log(cadena);

setTimeout("$('.suggestionsBox').hide();", 50);

    //thisValue.replace(" ","");
    array = cadena.split("*");

    $('#idFormaPago'+cont).val(array[1]);
    $('#formaPago'+cont).val(array[0]);


}
