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


function comboRetencionIvaCompra(aux){
//	alert("AAAAA");
	//alert(aux);
    ajaxIva=objetoAjax();
    ajaxIva.open("POST", "sql/busquedas.php",true);
    ajaxIva.onreadystatechange=function(){         
        if (ajaxIva.readyState==4){
            var respuesta=ajaxIva.responseText;            
            asignarRetencionIvaCompra(respuesta);
        }
    }
    ajaxIva.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajaxIva.send("accion="+aux)
}

function asignarRetencionIvaCompra(cadena){
    array = cadena.split( "?" );
	//alert("cademea");
	//alert(array);
    limite=array.length;
    contIva=1;
    //console.log(limite)
    if (limite == 1) {
        document.form3.txtEnlaceRetencionIva.options[0] = new Option("Aun no a agregado enlaces para retencion de IVA","0");        
    }else{
    document.form3.txtEnlaceRetencionIva.options[0] = new Option("Seleccione Retencion para el IVA","0");
    }

    for(i=1;i<limite;i=i+2){
        document.form3.txtEnlaceRetencionIva.options[contIva] = new Option(array[i+1], array[i]);
        contIva++;
    }
    //document.getElementById("cbpais").selectedIndex = "3";
    //document.form.txtRetencionIva.selectedIndex = '0'; // seleccion para ecuador    
}


function comboRetencionFuente(aux){
  //  alert(aux);
    ajaxFuente=objetoAjax();
    ajaxFuente.open("POST", "sql/busquedas.php",true);
    ajaxFuente.onreadystatechange=function(){         
        if (ajaxFuente.readyState==4){
            var respuesta=ajaxFuente.responseText;            
            asignarRetencionFuente(respuesta);
        }
    }
    ajaxFuente.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajaxFuente.send("accion="+aux)
}

function asignarRetencionFuente(cadena){
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;

    if (limite == 1) {
        document.form.txtEnlaceRetencionIva.options[0] = new Option("Aun no a agregado enlaces para retencion de la Fuente","0");        
    }else{
    document.form.txtRetencionFuente.options[0] = new Option("Seleccione Retencion para la Fuente","0");
    }    
    for(i=1;i<limite;i=i+2){
        document.form.txtRetencionFuente.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
    //document.getElementById("cbpais").selectedIndex = "3";
    //document.form.txtRetencionIva.selectedIndex = '0'; // seleccion para ecuador    
}

function comboRetencionFuenteCompra(aux){
    
    ajaxFuente=objetoAjax();
    ajaxFuente.open("POST", "sql/busquedas.php",true);
    ajaxFuente.onreadystatechange=function(){         
        if (ajaxFuente.readyState==4){
            var respuesta=ajaxFuente.responseText;            
            asignarRetencionFuenteCompra(respuesta);
        }
    }
    ajaxFuente.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajaxFuente.send("accion="+aux)
}

function asignarRetencionFuenteCompra(cadena){
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;

    if (limite == 1) {
        document.form3.txtEnlaceRetencionIva.options[0] = new Option("Aun no a agregado enlaces para retencion de la Fuente","0");        
    }else{
    document.form3.txtRetencionFuente.options[0] = new Option("Seleccione Retencion para la Fuente","0");
    }    
    for(i=1;i<limite;i=i+2){
        document.form3.txtRetencionFuente.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
    //document.getElementById("cbpais").selectedIndex = "3";
    //document.form.txtRetencionIva.selectedIndex = '0'; // seleccion para ecuador    
}


function comboRetencionIva(aux){
    ajaxIva=objetoAjax();
    ajaxIva.open("POST", "sql/busquedas.php",true);
    ajaxIva.onreadystatechange=function(){         
        if (ajaxIva.readyState==4){
            var respuesta=ajaxIva.responseText;            
            asignarRetencionIva(respuesta);
        }
    }
    ajaxIva.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajaxIva.send("accion="+aux)
}

function asignarRetencionIva(cadena){
    array = cadena.split( "?" );
    limite=array.length;
    contIva=1;
    //console.log(limite)
    if (limite == 1) {
        document.form.txtEnlaceRetencionIva.options[0] = new Option("Aun no a agregado enlaces para retencion de IVA","0");        
    }else{
    document.form.txtEnlaceRetencionIva.options[0] = new Option("Seleccione Retencion para el IVA","0");
    }

    for(i=1;i<limite;i=i+2){
        document.form.txtEnlaceRetencionIva.options[contIva] = new Option(array[i+1], array[i]);
        contIva++;

    }
    //document.getElementById("cbpais").selectedIndex = "3";
    //document.form.txtRetencionIva.selectedIndex = '0'; // seleccion para ecuador    
}

