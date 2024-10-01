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

function listar_clientes(){
    //PAGINA: clientesCondominios.php 
    var str = $("#frmClientes").serialize();
    $.ajax({
            url: 'ajax/listarClientes.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#listar_clientes").html(data);
            }
    });
}

function modificar_cliente(id_cliente, opcion){
    console.log("id cliente",id_cliente);
    console.log("opcion",opcion);
    
    //PAGINA: cargos.php
    $("#div_oculto1").load("ajax/modificarCliente.php", {id_cliente: id_cliente, opcion: opcion}, function(){
        $.blockUI({
                message: $('#div_oculto1'),
                overlayCSS: {backgroundColor: '#111'},
                css:{
                        '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px',
                        position: 'absolute',
                        background: '#f9f9f9',
                        top: '5%',
                        left: '15%',
                        width: '75%'
                }
        });
    });
}
/*
function modificar_cliente(id_cliente, opcion){
    alert(id_cliente);
    alert(opcion);
    //PAGINA: cargos.php
    $("#div_oculto1").load("ajax/modificarCliente.php", {id_cliente: id_cliente, opcion: opcion}, function(){
        console.log(id_cliente,opcion);
        $.blockUI({
                message: $('#div_oculto1'),
                overlayCSS: {backgroundColor: '#111'},
                css:{
                        '-webkit-border-radius': '10px',
                        '-moz-border-radius': '10px',
                        position: 'absolute',
                        background: 'white',
                        top: '15%',
                        left: '25%',
                        width: '50%'
                }
        });
    });
}
*/

function guardarModificarCliente(accion){
	//alert("<<<<<<<<<<<<<<va a modificar cliente pppp");
	//alert("Numero de opcion ");
console.log(accion);
 //PAGINA: cargos.php
    var str = $("#frmClientesEditar").serialize();
   // alert("ant3w sql");
	$.ajax({
		    url: 'sql/clientes.php',
			data: str+'&accion='+accion,
            type: 'post',
            success: function(data){
              			{
			    console.log(data);
				if(data==1){
				alertify.success("Cliente modificado con exito :)");
				fn_cerrar()
			}else{
				alertify.error("Error al modificar, revise los datos");
			}
			if(document.getElementById('div_listar_reporte_facturas')){
			    listar_reporte_clientes();
			}
			
            }
            }
    });

}


//data: str+"&txtAccion="+accion,

function eliminarCliente(id_cliente, accion){
//alert("eliminar");
    var respuesta12 = confirm("Seguro desea eliminar este registro?");
    if (respuesta12){
        $.ajax({
                url: 'sql/clientes.php',
                data: 'id_cliente='+id_cliente+'&accion='+accion,
			    type: 'post',
                success: function(data){
                         console.log(data);
				if(data==1){
				alertify.success("Cliente eliminado con exito :)");
			            listar_reporte_clientes(1);
			}else if(data==3){
				alertify.error("Cliente no se puede eliminar porque tiene ventas realizadas:)");
			            listar_reporte_clientes(1);
			}else{
				alertify.error("Error al eliminar");
			}
						
                }
        });
    }
}


function buscarClientes(){
    $("#div_oculto").load("ajax/buscarClientes.php", function(){
	$.blockUI({
			message: $('#div_oculto'),
			overlayCSS: {backgroundColor: '#111'},
			css:{
				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
				background: '', /* #f9f9f9*/
				top: '15%',
				position: 'absolute',
				width: '25%',
				left: '1%'
                //left: ($(window).width() - $('.caja').outerWidth())/2
			}
			
			
		});
       // listar_vendedores();
	});
}



function fn_cerrarx(opcion){
//	alert("xxxxxxxx");
	fn_cerrar1();
	repClientes(opcion);
	
}

/* function guardar(){
//	alert("xxxxxxxx");
	
	guardar_clientes(1)
	//clientesCondominios();
	url: 'sql/clientes.php'
} */


function fn_cerrar1(){
	$.unblockUI({
		onUnblock: function(){
			$("#div_oculto1").html("");
		}
	});
}

/* function limpiar()
{
	  var str = $("#frmClientes").serialize();
//   alert(str);
   alert(txtNombre);
   //txtNombre="";
    document.getElementById("txtNombre").innerHTML="";
   

} */

function no_repetir_email_propietario(valor, accion)
{
    var prop_email = valor.value;
    ajax21=objetoAjax();
    ajax21.open("POST", "sql/clientes.php",true);
    ajax21.onreadystatechange=function() {
        if (ajax21.readyState==4) {
            var respuesta21=ajax21.responseText;
//          alert("  login: "+login+" rp consulta: "+respuesta2.trim());
            if(respuesta21.trim()==1){
                 document.getElementById("noRepetirEmail2").innerHTML="<label style='color: #FF0000'>El Email del propietario que ingreso ya se encuentra registrado</label>";
                 document.getElementById("mensajeEmail2").innerHTML="";
                 valor.focus();
                 aux = 1;
                 return aux;
            }
            else {
                document.getElementById("noRepetirEmail2").innerHTML="";
                //document.getElementById("mensajeEmail").innerHTML="";
                aux = 0;
                return aux;
            }
        }
    }
    document.getElementById("noRepetirEmail2").innerHTML="";

    ajax21.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax21.send("prop_email="+prop_email+"&accion="+accion);
    return aux;
}

 function lookup_cliente(valor, accion) {
    //pagina: facturaVentaCondominios.php
    if(valor.length == 0) {
            // Hide the suggestion box.
            $('#suggestions9').hide();
    } else {
            $.post("sql/clientes.php", {queryString: valor, accion: accion}, function(data){
                    if(data.length >0) {
                            $('#suggestions9').show();
                            $('#autoSuggestionsList9').html(data);
                    }
            });
    }
} // lookup

function fill_cliente(cadena) {
    setTimeout("$('#suggestions9').hide();", 200);
    array = cadena.split("*");
    $('#textIdClienteFVC').val(array[5]);
    $('#txtCedulaFVC').val(array[2]);
    $('#txtNombreFVC').val(array[0]+" "+array[1]);
	
    //$('#txtTelefonoFVC').val(array[3]);

//    $('#txtDireccionFVC').val(array[4]);
  //  $('#txtCodigoServicio1').focus();
}

function mostrarCliente(aux){
	// alert('mostrar tipo punto'); 
	// alert(aux);
    ajax1=objetoAjax();
    ajax1.open("POST", "sql/clientes_ant.php",true);
    ajax1.onreadystatechange=function(){
        if (ajax1.readyState==4){
            var respuesta1=ajax1.responseText;
            asignaClientes_Ant(respuesta1);
        }
    }
    ajax1.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax1.send("accion="+aux)
}


function asignaClientes_Ant(cadena){
//	alert('asignar cliente');
    array = cadena.split( "?" );
//	 alert(array); 
    limite=array.length;
    cont=1;
    cont2=1;
	cont3=1;
	
    limpiacmbCliente(frmAnticipos);
    document.frmAnticipos.cmbCliente.options[0] = new Option("Seleccione cliente","0");
    for(i=1;i<limite;i=i+2){
        document.frmAnticipos.cmbCliente.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }

    document.frmAnticipos.cmbCliente.options[0] = new Option("Seleccione Cliente","0");
    for(i2=1;i2<limite;i2=i2+2){
        document.frmAnticipos.cmbCliente.options[cont2] = new Option(array[i2+1], array[i2]);
        cont2++;
    } 
	
}

function limpiacmbCliente()
{
    for (m=document.frmAnticipos.cmbCliente.options.length-1;m>=0;m--){
        document.frmAnticipos.cmbCliente.options[m]=null;
    }
     for (m2=document.frmAnticipos.cmbCliente.options.length-1;m2>=0;m2--){
        document.frmAnticipos.cmbCliente.options[m2]=null;
    } 
}
