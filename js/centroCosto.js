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



function listarCentroCostos(){
 //PAGINA: formas de pago.php
    //alert("listarCentroCostos");
    var str = $("#form").serialize();
    $.ajax
	({
            url: 'ajax/listarCentroCostos.php',
            type: 'get',
            data: str,
            success: function(data){
               // console.log("data",data);
                $("#div_listar_centros").html(data);
                cantidad_centros();
            }
    });
}


function cantidad_centros(){
   
    cantidad = $("#grillaCentros tbody").find("tr").length;
    $("#span_centros").html(cantidad);
    document.getElementById('txtNumeroFila').value = cantidad;
}

function cargarTipoMovimiento(aux, contador){
    
    ajax2=objetoAjax();
    ajax2.open("POST", "sql/centro_costo.php",true);
    ajax2.onreadystatechange=function(){
        console.log(respuesta2);
        if (ajax2.readyState==4){
            
            var respuesta2=ajax2.responseText;
            
            asignaTipoMovimiento(respuesta2, contador);
        }
    }
    ajax2.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax2.send("txtAccion="+aux)
}



function asignaTipoMovimiento(cadena, contador){
    console.log("cadena",cadena);
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;

    limpiaCmbTipoMovimiento(contador);
    document.getElementById("cmbTipoMovimientoFVC"+contador).options[0] = new Option("Seleccione Tipo de movimiento","0");
    for(i=1;i<limite;i=i+2){
        document.getElementById("cmbTipoMovimientoFVC"+contador).options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }

}

function limpiaCmbTipoMovimiento(contador)
{
    for (m=document.getElementById("cmbTipoMovimientoFVC"+contador).options.length-1;m>=0;m--){
        document.getElementById("cmbTipoMovimientoFVC"+contador).options[m]=null;
    }
}

function agregarCentroCosto()
{
    
	nFilasCentro = 0;
    numFilas1 = document.getElementById('txtNumeroFila').value;
    nFilasCentro = numFilas1;
    nFilasCentro++;
    cadena = "<tr>";
    
   // cadena = cadena + "<td width='5%'><input type='hidden' id='txtNumeroFila' name='txtNumeroFila' value='"+nFilasCentro+"' class='form-control' /></td>";

    cadena = cadena + "<td width='10%'><input style='width: 100%' type='text' id='txtCodigoB"+nFilasCentro+"' name='txtCodigoB"+nFilasCentro+"' class='form-control' /></td>";
    cadena = cadena + "<td width='15%'><input style='width: 100%' type='text' id='txtDescripcion"+nFilasCentro+"' name='txtDescripcion"+nFilasCentro+"' title='Ingrese un nombre' style='text-transform: capitalize;' class='form-control' required='required' onKeyPress='' maxlength='200' placeholder='Nombre' autocomplete='off'/></td>";
    cadena = cadena + "<td width='15%'><input style='width: 100%;' type='search' id='txtCodigo"+nFilasCentro+"' name='txtCodigo"+nFilasCentro+"' class='form-control' value='' onclick='lookup2(this.value,"+nFilasCentro+", 5);' onKeyUp='lookup2(this.value,"+nFilasCentro+", 5);'  autocomplete='off'  placeholder='Buscar..'   />       <div class='suggestionsBox' id='suggestions"+nFilasCentro+"' style='display: none; margin-top: 0px; width: 350px  '> <div class='suggestionList' id='autoSuggestionsList"+nFilasCentro+"'> &nbsp; </div> </div>   </td>";
    cadena = cadena + "<td width='30%'><input style='width: 100%;' type='hidden' id='txtIdCuenta"+nFilasCentro+"' name='txtIdCuenta"+nFilasCentro+"' class='bg-info' value=''><input type='text' id='txtCuenta2"+nFilasCentro+"' name='txtCuenta2"+nFilasCentro+"' value=''  style='margin: 0px; width: 100%;' class='form-control' autocomplete='off'   /></td> ";
    cadena = cadena + "<td width='30%'><select style='width: 100%' name='cmbTipoMovimientoFVC"+nFilasCentro+"' id='cmbTipoMovimientoFVC"+nFilasCentro+"' class='form-control' ></select></td> ";
    cadena = cadena + "<td><a title='Guardar' href='javascript: guardar_centro_costo("+nFilasCentro+", 1);'><span class='fa fa-check' aria-hidden='true'></span></a></td>";
    cadena = cadena + "<td><a class='eliminaFormaPago' title='Eliminar'><span class='fa fa-erase' aria-hidden='true'></a></td>";
    cadena = cadena + "</tr>";
        
    $("#grillaCentros tbody").append(cadena);
    cantidad_centros();
    cargarTipoMovimiento(6,nFilasCentro);
}

function nuevo_centrocosto(){
 //PAGINA: productos.php
// alert("nuevo_centrocosto");
	$("#div_oculto").load("ajax/nuevoCentroCosto.php", function(){
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
		listarCentroCostos();
	});
}


function guardar_centro_costo(filas,accion)
{

    var str = $("#frmCentros").serialize();
        console.log("str",str);
        $.ajax(
		{
			url: 'sql/centro_costo.php',
            data: str+"&txtAccion="+accion+"&filas="+filas,
            type: 'post',
            success: function(data)
			{
			console.log(data);
				if(data==1){
				alertify.success("Centro de costo agregado con exito :)");
				agregarCentroCosto();
			}else{
			    alertify.alert('Error', 'Error al guardar, faltan datos');
			}
            }
        });

}

function no_repetir_centrocosto(valor, accion)
{
    // validar pa que el nombre de PRODUCTO no se repita pagina: productos.php
	//alert("va a validar");
    var centrocosto = valor.value;
	//alert(centrocosto);
    ajax9=objetoAjax();
    ajax9.open("POST", "sql/centro_costo.php",true);
    String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g, "");};
    ajax9.onreadystatechange=function() 
	{
        if (ajax9.readyState==4) {
            var respuesta9=ajax9.responseText;
			//alert("respuesta de la consulta  ");
	     //  alert(respuesta9.trim());
		
            if(respuesta9.trim()==1){
                 document.getElementById("noRepetirCentroCosto").innerHTML="<label style='color: #FF0000'>El nombre del centro de costo ya se encuentra registrado</label>";
                 auxiliar = 1;
                 return auxiliar;
            }
            else {
                document.getElementById("noRepetirCentroCosto").innerHTML="" ;
                auxiliar = 0;
                return auxiliar;
            }
        }
    }
document.getElementById("noRepetirCentroCosto").innerHTML="" ;
ajax9.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
ajax9.send("centrocosto="+centrocosto+"&txtAccion="+accion);
return auxiliar;
}



function modificar_centro(idFormaCobro, accion, fila){
    if(document.getElementById("txtDescripcion"+fila).value != ""){
        if(document.getElementById("txtIdCuenta"+fila).value != "" && document.getElementById("txtIdCuenta"+fila).value >= 1){
                var respuesta44 = confirm("Seguro desea modificar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
                if (respuesta44){
                var str = $("#frmCentros").serialize();
             //   console.log("str",str);
		//		alert(str)
                    $.ajax({
                            url: 'sql/centro_costo.php',
                            type: 'post',
                            data: str+"&idFormaCobro="+idFormaCobro+"&txtAccion="+accion+"&NumeroFilaSeleccionada="+fila,
                            success: function(data){
                                if(data=1){
                                    alertify.success("Registro Actualizado Correctamente");
                                    listarCentroCostos();
                                }else{
                                    alertify.error("Registro no se pudo actualizar");
                                }
                                    
                                  
                                    
                            }
                    });
                }
        }else{
                alert("No se puede guardar. Seleccione una cuenta contable");
                document.getElementById("txtCodigo"+fila).focus();
            }

    }else{
        alert("No se puede guardar. El campo Nombre esta vacio.");
        document.getElementById("txtNombre"+fila).focus();
    }
}



