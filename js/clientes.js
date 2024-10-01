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

function guardar_clientes(accion){
    
    var str = $("#frmClientes").serialize();
    // console.log(str);
   noRptCedula = no_repetir_cedula(txtCedula.value, 3);

    
    noRptEmail = no_repetir_email_cliente(txtEmail,4);
    // val_email = isEmailAddress(txtEmail);

	if(noRptCedula == 0 )
	{
        // if(val_email == true){
            if(noRptEmail == 0)
			{
				//alert('va a grabar clientes1');
                $.ajax
				({
                   url: 'sql/clientes.php',
                   type: 'post',
                   data: str+"&accion="+accion,
                   success: function(data){
                       let json = JSON.parse(data);
                        // console.log(json.resultado);
                        // console.log(json.resultado.clientes);
                        let response=json.resultado.clientes.trim();
                          var id_cliente = json.resultado.id_cliente;
                        //   console.log("id_cliente",id_cliente);
                          
                       	if(response==1){
				                    alertify.success("Cliente Guardado con exito :)");
				                    fn_cerrar();
                                    listar_reporte_clientes();
                                    lookupClienteMostrar(id_cliente, '67');
                            
			                    }else  if(response==2){
				                    alertify.error("Faltan Datos de llenar");
				           
			                    }else  if(response==3){
				                    alertify.error("Ya existe cliente registrado con ese número de cédula.");
				           
			                    }else{
				                    alertify.error("Ocurri&oacute; un error al guardar");
			                    }
                          

                        }
                    });
                    
                }else{
                    $("#txtEmail").focus();
                    $("#txtEmail").val("");
                    //dml.elements['ruc'].focus();
                    alert("No se puede guardar. El Email ingresado ya se encuentra registrado.");
               }
        //   }else{
        //         $("#txtEmail").focus();                
        //         alert("No se puede guardar. El Email ingresado esta incorrecto.");
        //   }
   }
	else
	{
            $("#txtCedula").focus();
            $("#txtCedula").val("");
            //dml.elements['ruc'].focus();
            alert("No se puede guardar. La Cedula/Ruc que ingreso ya se encuentra registrada.");
    }
    //}
	
	//else{
      //  $("#txtCedula").focus();
       // alert("No se puede guardar. La Cedula/Ruc que ingreso esta incorrecta.");
   // }
     

}



function combopais3(aux){
    
    ajax1=objetoAjax();
    ajax1.open("POST", "sql/busquedas.php",true);
    ajax1.onreadystatechange=function(){
        if (ajax1.readyState==4){
            var respuesta=ajax1.responseText;            
            asignapais3(respuesta);
            
        }
    }
    ajax1.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax1.send("accion="+aux)
}

function asignapais3(cadena){
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;
    
    limpiapais3();
    document.frmClientesEditar.cbpais.options[0] = new Option("Seleccione Pais","0");
    for(i=1;i<limite;i=i+2){
        document.frmClientesEditar.cbpais.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
    //document.getElementById("cbpais").selectedIndex = "3";
    document.frmClientesEditar.cbpais.selectedIndex = '1'; // seleccion para ecuador
}

function limpiapais3()
{
    
    for (m=document.frmClientesEditar.cbpais.options.length-1;m>=0;m--){        
        document.frmClientesEditar.cbpais.options[m]=null;
    }
}

function comboprovincia3(aux){

    codigo=document.frmClientesEditar.opcion.value;// coje el txt al que se le dio el valor de 1 = ecuador
    ajax=objetoAjax();
    ajax.open("POST", "sql/busquedas.php",true);
    ajax.onreadystatechange=function(){
        if (ajax.readyState==4){
            var respuesta=ajax.responseText;
            asignaprovincia3(respuesta);
        }
    }
    ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax.send("accion="+aux+"&codigo="+codigo)
}

function asignaprovincia3(cadena){
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;
    document.frmClientesEditar.opcion1.value="0";
    limpiaprovincia3();
    document.frmClientesEditar.cbprovincia.options[0] = new Option("Seleccione Provincia","0");
    for(i=1;i<limite;i=i+2){
        document.frmClientesEditar.cbprovincia.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
}

function limpiaprovincia3()
{
    for (m=document.frmClientesEditar.cbprovincia.options.length-1;m>=0;m--){
        document.frmClientesEditar.cbprovincia.options[m]=null;
    }
}

function combociudad3(aux){
    codigo= document.frmClientesEditar.cbprovincia.value;
    ajax3=objetoAjax();
    ajax3.open("POST", "sql/busquedas.php",true);
    ajax3.onreadystatechange=function() {
        if (ajax3.readyState==4) {
            var respuesta=ajax3.responseText;
            asignaciudad3(respuesta);
        }
    }
    ajax3.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax3.send("accion="+aux+"&codigo="+codigo)
}

function asignaciudad3(cadena){
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;
    document.frmClientesEditar.opcion2.value="0";
    limpiaciudad3();
    document.frmClientesEditar.cbciudad.options[0] = new Option("Seleccione Ciudad","0");
    for(i=1;i<limite;i=i+2){
        document.frmClientesEditar.cbciudad.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
}

function limpiaciudad3()
{
    for (m=document.frmClientesEditar.cbciudad.options.length-1;m>=0;m--){
        document.frmClientesEditar.cbciudad.options[m]=null;
    }
}



