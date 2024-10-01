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

function no_repetir_codigo_centrocosto(valor, accion)
{
    //pagina: proveedores.php
	//alert("va a validad");
	//alert(valor.value);
    var codigo = valor.value;
    ajax10=objetoAjax();
    ajax10.open("POST", "sql/centro_costo.php",true);
  //alert("salio de la consulta de cedula");
    String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g, "");};
    ajax10.onreadystatechange=function() 
	{
        if (ajax10.readyState==4) 
	   {
           var respuesta10=ajax10.responseText;
		 //  alert(respuesta10);
           if(respuesta10.trim()==1)
		  {
                 document.getElementById("noRepetirCodigoCentroCosto").innerHTML="<label style='color: #FF0000'>Codigo de producto ya existe</label>";
                 auxiliar = 1;
                 return auxiliar;
           }
           else
	       {
                document.getElementById("noRepetirCodigoCentroCosto").innerHTML="" ;
                auxiliar = 0;
                return auxiliar;
            }
        }    
    }
   document.getElementById("noRepetirCodigoCentroCosto").innerHTML="" ;
   ajax10.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
   ajax10.send("codigo="+codigo+"&txtAccion="+accion);
   return auxiliar;
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

function validar_codigoActivacion(valor, accion)
{
    // validar pa que el nombre de PRODUCTO no se repita pagina: productos.php
	//alert("va a validar");
    var cod_activacion = valor.value;
	if (cod_activacion !="")
	{
		var respuesta51="";
		ajax51=objetoAjax();
		ajax51.open("POST", "sql/codigoActivacion.php",true);
		String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g, "");};
		ajax51.onreadystatechange=function() 
		{
			if (ajax51.readyState==4) 
			{
            respuesta51=ajax51.responseText;
			//console.log(ajax51.responseText);
			//alert(ajax51.responseText)
				if(respuesta51.trim()==1)
				{
					document.getElementById("noCodigoActivacion").innerHTML="<label style='color: #FF0000'>Codigo de activacion no existe </label>";
					auxiliar = 1;
					return auxiliar;
				}
				else 
				{
					if(respuesta51.trim()==2)
					{
						document.getElementById("noCodigoActivacion").innerHTML="<label style='color: #FF0000'>Codigo de activacion fue asignado a otra empresa</label>";
						auxiliar = 1;
						return auxiliar;
					}	
					else
					{
						document.getElementById("noCodigoActivacion").innerHTML="" ;
						auxiliar = 0;
						return auxiliar;
					}
				}	
			}
		}
		document.getElementById("noCodigoActivacion").innerHTML="" ;
		ajax51.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax51.send("cod_activacion="+cod_activacion+"&txtAccion="+accion);
		return auxiliar;
	}	
	else
	{
		 document.getElementById("noCodigoActivacion").innerHTML="" ;
	}
}

