

function mostrarTablaBodegas(aux){
	//alert("mostrarTablaBodegas");
    ajax4=objetoAjax();
    ajax4.open("POST", "sql/bodegas.php",true);
    ajax4.onreadystatechange=function(){
        if (ajax4.readyState==4){
            var respuesta4=ajax4.responseText;
//			alert(respuesta4);
            asignaTablaBodegas(respuesta4);
        }
    }
    ajax4.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax4.send("txtAccion="+aux)
}

function asignaTablaBodegas(cadena){
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;
    limpiaCmbBodegas();
    document.frmBodegas.cmbBodegas.options[0] = new Option("Seleccione Bodegas","0");
    for(i=1;i<limite;i=i+2){
        document.frmBodegas.cmbBodegas.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
}

function limpiaCmbBodegas()
{
    for (m=document.frmBodegas.cmbBodegas.options.length-1;m>=0;m--){
        document.frmBodegas.cmbBodegas.options[m]=null;
    }
}


//***********************  SERVICOS ********************************

function guardar_inventarios1(accion){

    var str = $("#frmBodegas").serialize();
         $.ajax({
            url: 'sql/bodegas.php',
            type: 'post',
            data: str+"&txtAccion="+accion,
            // para mostrar el loadian antes de cargar los datos
            beforeSend: function(){
                //imagen de carga
                $("#mensaje3").html("<p align='center'><img src='images/loading.gif' /></p>");
            },
            success: function(data){
                //alert(data.length);
                document.getElementById("mensaje3").innerHTML=data;
                if(data.length == 87){
                    document.getElementById("frmBodegas").reset();
                }
                listar_bodegas();
            }
        });
}

function modificar_bodinventario(inventarios_bodega_id_inventario){
	//alert("inventario de bodega");
	//alert(inventarios_bodega_id_inventario);
    //PAGINA: cargos.php
    $("#div_oculto").load("ajax/modificarBodinventario.php", {inventarios_bodega_id_inventario: inventarios_bodega_id_inventario}, function(){
        $.blockUI
		({
            message: $('#div_oculto'),
            overlayCSS: {backgroundColor: '#111'},
            css:{
                 '-webkit-border-radius': '10px',
				 '-moz-border-radius': '10px',
                 position: 'absolute',
                 background: '#f9f9f9',
                 top: '20px',
                 left: '185px',
                        width: '450px'
                }
        });
    });
}

function guardarModificarProducto2(accion){
 //PAGINA: cargos.php
 //alert("guardar");
    var str = $("#form1").serialize();
    $.ajax({

            url: 'sql/bodegas.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data){
            document.getElementById('mensaje1').innerHTML+=""+data;
            listar_bodegas()
        }
    });
}


function eliminar_OtroProd(inventarios_bodega_id_inventario, accion){
//alert("eliminar");
    var respuesta12 = confirm("Seguro desea eliminar este registro?");
    if (respuesta12){
        $.ajax({
                url: 'sql/bodegas.php',
                data: 'inventarios_bodega_id_inventario='+inventarios_bodega_id_inventario+'&txtAccion='+accion,
                type: 'post',
                success: function(data){
                        if(data!="")
                                alert(data);
                        listar_bodegas();
                }
        });
    }
}

function listar_bodegas(){
    var str = $("#frmBodegas").serialize();
    $.ajax({
        url: 'ajax/listarBodegas.php',
        type: 'get',
        data: str,
        success: function(data){
            $("#listar_bodegas").html(data);
        }
    });
}

function lugarubicacion(){
//	alert("va a listar ubicacion");
 //PAGINA: inventarios.php
    $("#div_oculto").load("ajax/lugarUbicacion.php", function(){
        $.blockUI({
                message: $('#div_oculto'),
        overlayCSS: {backgroundColor: '#111'},
                css:{
                        '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px',

                        background: '', /* #FFFFFF */
                        top: '5%',

                        position: 'absolute',

                        left: ($(window).width() - $('.caja').outerWidth())/2
                }
        });listar_lugar_ubicacion();
    });
}

function listar_lugar_ubicacion(){
 //PAGINA: inventarios.php
// alert("listar-lugar");
    var str = $("#form").serialize();
    $.ajax({
            url: 'ajax/listarLugarUbicacion.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#div_listar_tipo_servicios").html(data);
                 cantidad_filas_ubicacion();
            }
    });
}


function agregarLugarUbicacion()
{
	alert("agregar");
	nFilasUbicacion = 0;
    numFilas1 = document.getElementById('txtNumeroFila').value;
    nFilasUbicacion = numFilas1;
    nFilasUbicacion++;
//	alert(nFilasUbicacion);
  //  document.getElementById("mensaje1").innerHTML="";
	cadena = "<tr>";
	cadena = cadena + "<td><input type='text' id='txtIdUbicacion"+nFilasUbicacion+"' name='txtIdUbicacion"+nFilasUbicacion+"' class='text_input6' disabled='false' /> </td>";
    cadena = cadena + "<td><input type='text' id='txtNombreUbicacion"+nFilasUbicacion+"' name='txtNombreUbicacion"+nFilasUbicacion+"' title='Ingrese un nombre' style='text-transform: capitalize; margin-top: 0px;' class='text_input4' required='required' maxlength='100' placeholder='Nombre tipo de servicio' autocomplete='off'/></td>";
    cadena = cadena + "<td><a title='Guardar' href='javascript: guardar_ubicacion("+nFilasUbicacion+", 14);'><img src='images/save16.png' /></a></td>";
    cadena = cadena + "<td><a class='eliminaTipoServicios' title='Eliminar'><img src='images/delete.png' /></a></td>";

    //cadena = "</tr>";
//alert(cadena);
    $("#grillaUbicacion tbody").append(cadena);
    cantidad_filas_ubicacion();
    //eliminarTipoServicios();
}

function cantidad_filas_ubicacion(){
        cantidad = $("#grillaUbicacion tbody").find("tr").length;
        $("#span_cantidadUbicacion").html(cantidad);
        document.getElementById('txtNumeroFila').value = cantidad;
}

function guardar_ubicacion(nFila, accion){

    //PAGINA: inventarios.php
    if(document.getElementById("txtNombreUbicacion"+nFila).value==""){
        alert("Faltan Campos por llenar");

    }else{
        var respuesta43 = confirm("Seguro desea guardar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
        if (respuesta43){
        var str = $("#form").serialize();
            $.ajax({
                    url: 'sql/bodegas.php',
                    type: 'post',
                    data: str+"&numeroFilaSelec="+nFila+"&txtAccion="+accion,
                    success: function(data){
                            //$("#div_listar_RegistroDiario").html(data);
                            document.getElementById("mensajeTipoServicio").innerHTML=""+data;
                            listar_lugar_ubicacion();
                            //mostrarTablaUbicacion(10); // pagina: inventarios.php
                    }
            });
           }
    }
}

function modificar_ubicacion(id, accion, fila){
    //PAGINA: ajax/unidades.php
//	alert("ddd");
    var respuesta44 = confirm("Seguro desea modificar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
    if (respuesta44){
    var str = $("#form").serialize();
	$.ajax({
		url: 'sql/bodegas.php',
		type: 'post',
		data: str+"&idUbicacion="+id+"&txtAccion="+accion+"&NumeroFilaSeleccionada="+fila,
		success: function(data){
			//$("#div_listar_RegistroDiario").html(data);
                        document.getElementById("mensajeTipoServicio").innerHTML=""+data;
                        listar_lugar_ubicacion();
						//listar_tipo_servicios();
                        //mostrarTablaTipoServicio(10);
		}
	});
       }
}

function eliminar_ubicacion(id, accion){
    //PAGINA: ajax/unidades.php
    var respuesta45 = confirm("Seguro desea eliminar este Registro? \nEsta acci\u00f3n eliminara de forma permanente la fila seleccionada");
    if (respuesta45){
            $.ajax({
                    url: 'sql/bodegas.php',
                    data: 'idUbicacion='+id+'&txtAccion='+accion,
                    type: 'post',
                    success: function(data){
                            if(data!="")
                            //alert(data);
                            document.getElementById("mensajeTipoServicio").innerHTML=""+data;
                            listar_lugar_ubicacion();
                            //mostrarTablaTipoServicio(10); // pagina: inventarios.php
                    }
            });
    }
}

