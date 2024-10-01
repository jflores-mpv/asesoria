// ********************************  Tipo Movimientos **************************

function guardar_tipomovimientos(accion){
	//alert("grabar tipo de movimientos");
    var str = $("#frmTipoMovimientos").serialize();
    //alert(str);                
    $.ajax({
        url: 'sql/tipomovimientos.php',
        type: 'post',
        data: str+"&accion="+accion,
        // para mostrar el loadian antes de cargar los datos
        beforeSend: function()
		{
        //imagen de carga
        $("#mensaje1").html("<p align='center'><img src='images/loading.gif' /></p>");
        },
        success: function(data)
		{
            document.getElementById("mensaje1").innerHTML=data;
			listar_tipomovimientos();
        }
    });
}

function listar_tipomovimientos(){
    //PAGINA: clientesCondominios.php 
    var str = $("#frmTipoMovimientos").serialize();
    $.ajax({
            url: 'ajax/listarTipoMovimientos.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#listar_clientes").html(data);

            }
    });

}

function modificar_tipomovimiento(id_tipomovimiento){
    alert("tipo movimiento");
    //alert(id_cliente);
    //PAGINA: cargos.php
    $("#div_oculto").load("ajax/modificarTipoMovimiento.php", {id_tipomovimiento: id_tipomovimiento}, function(){
        $.blockUI({
                message: $('#div_oculto'),
                overlayCSS: {backgroundColor: '#111'},
                css:{
                        '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px',
                        position: 'absolute',
                        background: '#f9f9f9',
                        top: '20px',
                        left: '185px',
                        width: '620px'
                }
        });
    });
}



function guardarModificarTipoMovimiento(accion){
	//alert("<<<<<<<<<<<<<<va a modificar cliente pppp");
	//alert("Numero de opcion ");
	//alert(accion);
 //PAGINA: cargos.php
    var str = $("#form1").serialize();
   // alert("ant3w sql");
	$.ajax({
		    url: 'sql/TipoMov_mod.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data){
            document.getElementById('mensaje1').innerHTML+=""+data;
            listar_clientes();
            }
    });

}
