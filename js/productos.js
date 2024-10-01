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

function imprimirCodigos(id,modulo,cantidad) {
    // Muestra las alertas para verificar que la función se está ejecutando correctamente
    console.log("Imprimir: " + id);
    console.log("modulo: " + modulo);
    console.log("cantidad: " + cantidad);


    $.ajax({
        type: "POST",
        url: "imprimirCodigo.php",
        data: { code: id },
        success: function(response) {
            // La respuesta del servidor será el PDF generado
            // Puedes abrirlo en una nueva ventana o simplemente descargarlo
        miUrl = "imprimirCodigo.php?code=" + encodeURIComponent(id) + "&modulo=" + encodeURIComponent(modulo);


            window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');

        },
        error: function(xhr, status, error) {
            // Manejar errores de AJAX si es necesario
            console.error(xhr, status, error);
        }
    });
}



function guardar_producto_servicio(accion){

    var nombre = "";
    var valor = "";
    valor = document.form['txtProducto'].value;
    nombre = no_repetir_producto(valor, '4');//retorna 1 o 0

    if(nombre == 0){
        var str = $("#form").serialize();
        console.log(str);
        $.ajax(
		{
			url: 'sql/productos_servicios.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data)
			{
			    console.log(data);
			 
			 if(data==1){
				alertify.success("Producto agregado con exito :)");
				fn_cerrar();
			}else if(data==3){
				alertify.error("Producto ya existe");
			}else{
				alertify.error("Error al guardar, revise los datos");
			}
            }
        });
    }
	else 
	{
        alert ('No se puede guardar porque el nombre "'+document.form['txtProducto'].value+'" ya se encuentra registrado.');
        document.form.txtProducto.focus();
        document.form.txtProducto.value="";
        document.getElementById("noRepetirProducto").innerHTML="";
    }
}


function ocultarProducto(id_producto, mostrar){
    let respuesta = 'ocultar';
    if(mostrar==0){
         respuesta = 'mostrar';
    }
    var respuesta11 = confirm("Seguro desea "+respuesta+" este producto? ");
	if (respuesta11){
		$.ajax({
			url: 'sql/productos.php',
			data: 'id_producto=' +id_producto+'&txtAccion=23&mostrar='+mostrar,
			type: 'post',
			success: function(data){
			    let response = data.trim();
				if(response==1){
				    	alertify.success("Producto actualizado con exito.");
				}else{
				    	alertify.error("No se actualizo el producto");
				}

			}
		});
		listar_productos(1);
	}
}

function nuevaBodega(){
 //PAGINA: productos.php
// alert("nuevo_centrocosto");
	$("#div_oculto").load("ajax/nuevaBodega.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: 'white'},
			css:{
            'box-shadow': '6px 6px 20px gray',
            '-webkit-border-radius': '10px',
			'-moz-border-radius': '10px',
                        position: 'absolute',
                        top: '10%',
                        left: '25%',
                        width: '50%'
			
			}
		});
		ListarBodegas();
	});
}


function ListarBodegas(){
 //PAGINA: formas de pago.php
    //alert("listarCentroCostos");
    var str = $("#form").serialize();
    console.log("str",str);
    $.ajax
	({
            url: 'ajax/ListarBodegas.php',
            type: 'get',
            data: str,
            success: function(data){
                console.log("data",data);
                $("#div_ListarBodegas").html(data);
                cantidad_bodegas();
            }
    });
}


function cantidad_bodegas(){
    cantidad = $("#grillaBodegas tbody").find("tr").length;
    $("#span_bodegas").html(cantidad);
    document.getElementById('txtNumeroFila').value = cantidad;
}

function agregarBodega()
{
    
	nFilasCentro = 0;
    numFilas1 = document.getElementById('txtNumeroFila').value;
    nFilasCentro = numFilas1;
    nFilasCentro++;
    
    cadena = "<tr>";
    
    cadena = cadena + "<td ><input  type='text' id='txtdetalleBodega"+nFilasCentro+"' name='txtdetalleBodega"+nFilasCentro+"' class='form-control' /></td>";
    cadena = cadena + "<td ><a href='javascript: guardarBodega("+nFilasCentro+",14);' title='Editar Producto'><button type='button' class='btn btn-default' aria-label='Left Align'><i class='fa fa-save' aria-hidden='true'></i> </a></td>";



    cadena = cadena + "</tr>";
        
    $("#grillaBodegas tbody").append(cadena);
    cantidad_bodegas();
}

function guardarBodega(filas,accion)
{

    var str = $("#frmBodegas").serialize();
        console.log("str",str);
        $.ajax(
		{
			url: 'sql/bodegas.php',
            data: str+"&txtAccion="+accion+"&filas="+filas,
            type: 'post',
            success: function(data)
			{
			console.log(data);
				if(data==1){
				alertify.success("bodega agregado con exito :)");
				// agregarCentroCosto();
				fn_cerrar();
			}else{
				alertify.error("Centro de costo ya existe");
			}
            }
        });

}

function modificarBodega(id,accion,detalle)
{

        $.ajax(
		{
			url: 'sql/bodegas.php',
            data: "txtAccion="+accion+"&id="+id+"&detalle="+detalle,
            type: 'post',
            success: function(data)
			{
			console.log(data);
				if(data==1){
				alertify.success("Bodega modificada con exito :)");
				// agregarCentroCosto();
				fn_cerrar();
			}else{
				alertify.error("Error al actualizar el nombre de la bodega");
			}
            }
        });

}

function eliminarBodega(id)
{

    alertify.confirm('Eliminar Datos', '多Esta seguro de eliminar este registro?', 
    function(){ 
        $.ajax(
            {
                url: 'sql/bodegas.php',
                data: "txtAccion=21&idBodega="+id,
                type: 'post',
                success: function(data)
                {
                console.log(data);
                    if(data.trim()==1){
                    alertify.success("Bodega eliminada con exito :)");
                    // agregarCentroCosto();
                    fn_cerrar();
                }else{
                    alertify.error("Error al eliminar la bodega, existen registros relacionados a esta bodega.");
                }
                }
            });
       
     }
    , function(){ alertify.error('Se cancelo')});


     

}
