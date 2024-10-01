function vendedor(){
    $("#div_oculto").load("ajax/vendedores.php", function(){
	$.blockUI({
			message: $('#div_oculto'),
			overlayCSS: {backgroundColor: '#111'},
			css:{
				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
				background: '', /* #f9f9f9*/
				top: '5%',
				position: 'absolute',
				width: '400px',
                left: ($(window).width() - $('.caja').outerWidth())/2
			}
		});
        listar_vendedores();
	});
}

function guardar_vendedores(accion){
	var str = $("#frmVendedores").serialize();
    $.ajax({
            url: 'sql/vendedores.php',
            type: 'post',
			data: str+"&accion="+accion,
            // para mostrar el loadian antes de cargar los datos
			beforeSend: function()
			{
            //imagen de carga
            $("#mensaje1").html("<p align='center'><img src='images/loading.gif' /></p>");
            },
            success: function(data){
                document.getElementById("mensaje1").innerHTML=data;
                listar_vendedores();
               }
            });
}
	     
function listar_vendedores(){
    //PAGINA: clientesCondominios.php 
	//alert("va a listar vendedores");
    var str = $("#frmVendedores").serialize();
    $.ajax({
            url: 'ajax/listarVendedores.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#listar_vendedores").html(data);
            }
    });
}

function modificar_vendedor(id_vendedor){
    //PAGINA: cargos.php
    $("#div_oculto").load("ajax/modificarVendedor.php", {id_vendedor: id_vendedor}, function(){
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
		listar_vendedores();

    });
}

function guardarModificarVendedor(accion){
 //PAGINA: cargos.php
    var str = $("#form1").serialize();
    $.ajax({
            url: 'sql/vend_modificar.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data){
            document.getElementById('mensaje1').innerHTML+=""+data;
            listar_vendedores();
            }
    });

}




  
