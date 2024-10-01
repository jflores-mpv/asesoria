/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

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
function consumidorFinal(){
    let tipo = $("input[type=radio][name=rbCaracterIdentificacion]:checked").val();
    if(tipo=='07'){
        document.getElementById('txtNombre').value= 'CONSUMIDOR';
        document.getElementById('txtApellido').value= 'FINAL';
        document.getElementById('txtCedula').value = '9999999999999'
    }

}
// VALIDACION DE CEDULA Y RUC
function cedula_ruc (campo) {
       let pasaporte_input = document.getElementById("pasaporte");
       let exterior_input = document.getElementById("exterior");
       
    if(pasaporte_input){
        if(pasaporte_input.checked){
            
          
             document.getElementById("mensageCedula2").innerHTML="";
            return true;
        }
        
    }
    if(exterior_input){
        if(exterior_input.checked){
            
             document.getElementById("mensageCedula2").innerHTML="";
            return true;
        }
    }
    document.getElementById("mensageCedula2").innerHTML="";
  let tipo2 = $("input[type=radio][name=rbCaracterIdentificacion]:checked").val();
  let otros = $("input[type=radio][id=otros]:checked").val();
  if(otros==undefined){
      
    if ( tipo2=='07' ){
    
        document.getElementById("mensageCedula2").innerHTML="";
        campo.focus();
            return false;
        
    }
    numero = campo;
    numero = numero.toString();
    num = numero.length;
    if (num >= 1 & num < 10 ){
        //alert("La cedula o RUC no puede ser menor a 10 digitos");
        document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>La cedula o RUC no puede ser menor a 10 digitos</label>";
        campo.focus();
        return false;
    }
       
    if ( num == 13  && tipo2=='05' ){
    
        document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>La cedula debe tener solo 10 digitos</label>";
        campo.focus();
            return false;
        
        
    }
    if ( num == 10  && tipo2=='04' ){
    
        document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El RUC debe tener 13 digitos</label>";
        campo.focus();
            return false;
        
    }
    if ( num == 10 || num==13 ){
        var suma = 0;
        var residuo = 0;
        var pri = false;
        var pub = false;
        var nat = false;
        var numeroProvincias = 22;
        var modulo = 11;

        /* Verifico que el campo no contenga letras */
        var ok=1;
        //for (i=0; i numeroProvincias){
        //alert('El c�digo de la provincia (dos primeros d�gitos) es inv�lido'); return false;
        //}

        /* Aqui almacenamos los digitos de la cedula en variables. */
        d1 = numero.substr(0,1);
        d2 = numero.substr(1,1);
        d3 = numero.substr(2,1);
        d4 = numero.substr(3,1);
        d5 = numero.substr(4,1);
        d6 = numero.substr(5,1);
        d7 = numero.substr(6,1);
        d8 = numero.substr(7,1);
        d9 = numero.substr(8,1);
        d10 = numero.substr(9,1);

        /* El tercer digito es: */
        /* 9 para sociedades privadas y extranjeros */
        /* 6 para sociedades publicas */
        /* menor que 6 (0,1,2,3,4,5) para personas naturales */

        if (d3==7 || d3==8){
            document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El tercer d\u00edgito ingresado es inv\u00e1lido</label>";
            //alert('El tercer d\u00edgito ingresado es inv\u00e1lido');
            campo.focus();
            return false;
        }

        /* Solo para personas naturales (modulo 10) */
        if (d3 < 6){
            nat = true;
            p1 = d1 * 2;if (p1 >= 10) p1 -= 9;
            p2 = d2 * 1;if (p2 >= 10) p2 -= 9;
            p3 = d3 * 2;if (p3 >= 10) p3 -= 9;
            p4 = d4 * 1;if (p4 >= 10) p4 -= 9;
            p5 = d5 * 2;if (p5 >= 10) p5 -= 9;
            p6 = d6 * 1;if (p6 >= 10) p6 -= 9;
            p7 = d7 * 2;if (p7 >= 10) p7 -= 9;
            p8 = d8 * 1;if (p8 >= 10) p8 -= 9;
            p9 = d9 * 2;if (p9 >= 10) p9 -= 9;
            modulo = 10;
        }

        /* Solo para sociedades publicas (modulo 11) */
        /* Aqui el digito verficador esta en la posicion 9, en las otras 2 en la pos. 10 */
        else if(d3 == 6){
            pub = true;
            p1 = d1 * 3;
            p2 = d2 * 2;
            p3 = d3 * 7;
            p4 = d4 * 6;
            p5 = d5 * 5;
            p6 = d6 * 4;
            p7 = d7 * 3;
            p8 = d8 * 2;
            p9 = 0;
        }

        /* Solo para entidades privadas (modulo 11) */
        else if(d3 == 9) {
            pri = true;
            p1 = d1 * 4;
            p2 = d2 * 3;
            p3 = d3 * 2;
            p4 = d4 * 7;
            p5 = d5 * 6;
            p6 = d6 * 5;
            p7 = d7 * 4;
            p8 = d8 * 3;
            p9 = d9 * 2;
        }

        suma = p1 + p2 + p3 + p4 + p5 + p6 + p7 + p8 + p9;
        residuo = suma % modulo;

        /* Si residuo=0, dig.ver.=0, caso contrario 10 - residuo*/
        digitoVerificador = residuo==0 ? 0: modulo - residuo;

        /* ahora comparamos el elemento de la posicion 10 con el dig. ver.*/
        if (pub==true){
            if (digitoVerificador != d9){
                //alert('El ruc de la empresa del sector p\u00fablico es incorrecto.');
                document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El ruc de la empresa del sector p\u00fablico es incorrecto.</label>";
                campo.focus();
                return false;
            }
            /* El ruc de las empresas del sector publico terminan con 0001*/
            if ( numero.substr(9,4) != '0001' ){
                //alert('El ruc de la empresa del sector p\u00fablico debe terminar con 0001');
                document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El ruc de la empresa del sector p\u00fablico debe terminar con 0001.</label>";
                campo.focus();
                return false;
            }
        }
        else if(pri == true){
            if (digitoVerificador != d10){
                //alert('El ruc de la empresa del sector privado es incorrecto.');
                document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El ruc de la empresa del sector privado es incorrecto.</label>";
                campo.focus();
                return false;
            }
            if ( numero.substr(10,3) != '001' ){
                //alert('El ruc de la empresa del sector privado debe terminar con 001');
                document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El ruc de la empresa del sector privado debe terminar con 001.</label>";
                campo.focus();
                return false;
            }
        }

        else if(nat == true){
            if (digitoVerificador != d10){
                //alert("El n\u00famero de c\u00e9dula de la persona natural es incorrecto.");
                document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El n\u00famero de c\u00e9dula de la persona natural es incorrecto.</label>";
                campo.focus();
                return false;
            }
            let tipo = $("input[type=radio][name=rbCaracterIdentificacion]:checked").val();
            if (numero.length >10 && numero.substr(10,3) != '001' && tipo =='04'){
                document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El ruc de la persona natural debe terminar con 001.</label>";
                //alert('El ruc de la persona natural debe terminar con 001');
                campo.focus();
                return false;
            }else{
                document.getElementById("mensageCedula2").innerHTML="";
            }
        }
    return true;
    document.getElementById("mensageCedula2").innerHTML="";
    }else{
        let tipo = $("input[type=radio][name=rbCaracterIdentificacion]:checked").val();
        if(tipo=='04'){
            document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El numero de RUC debe tener 13 digitos</label>";
        }
        if(tipo=='05'){
            document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El numero de cédula debe tener 10 digitos</label>";
        }
   
        
    }
    return true;
  }
  
   document.getElementById("mensageCedula2").innerHTML="";
return true;

    
}

function cedula_ruc_clientes_jderp (campo) {
       let pasaporte_input = document.getElementById("pasaporte");
       let exterior_input = document.getElementById("exterior");
       
    if(pasaporte_input){
        if(pasaporte_input.checked){
            
          
             document.getElementById("mensageCedula2").innerHTML="";
            return true;
        }
        
    }
    if(exterior_input){
        if(exterior_input.checked){
            
             document.getElementById("mensageCedula2").innerHTML="";
            return true;
        }
    }
    document.getElementById("mensageCedula2").innerHTML="";
  let tipo2 = $("input[type=radio][name=rbCaracterIdentificacion]:checked").val();
  let otros = $("input[type=radio][id=otros]:checked").val();
  if(otros==undefined){
      
    if ( tipo2=='07' ){
    
        document.getElementById("mensageCedula2").innerHTML="";
        campo.focus();
            return false;
        
    }
    numero = campo.value;
    numero = numero.toString();
    num = numero.length;
    if (num >= 1 & num < 10 ){
        //alert("La cedula o RUC no puede ser menor a 10 digitos");
        document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>La cedula o RUC no puede ser menor a 10 digitos</label>";
        campo.focus();
        return false;
    }
       
    if ( num == 13  && tipo2=='05' ){
    
        document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>La cedula debe tener solo 10 digitos</label>";
        campo.focus();
            return false;
        
        
    }
    if ( num == 10  && tipo2=='04' ){
    
        document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El RUC debe tener 13 digitos</label>";
        campo.focus();
            return false;
        
    }
    if ( num == 10 || num==13 ){
        var suma = 0;
        var residuo = 0;
        var pri = false;
        var pub = false;
        var nat = false;
        var numeroProvincias = 22;
        var modulo = 11;

        /* Verifico que el campo no contenga letras */
        var ok=1;
        //for (i=0; i numeroProvincias){
        //alert('El c�digo de la provincia (dos primeros d�gitos) es inv�lido'); return false;
        //}

        /* Aqui almacenamos los digitos de la cedula en variables. */
        d1 = numero.substr(0,1);
        d2 = numero.substr(1,1);
        d3 = numero.substr(2,1);
        d4 = numero.substr(3,1);
        d5 = numero.substr(4,1);
        d6 = numero.substr(5,1);
        d7 = numero.substr(6,1);
        d8 = numero.substr(7,1);
        d9 = numero.substr(8,1);
        d10 = numero.substr(9,1);

        /* El tercer digito es: */
        /* 9 para sociedades privadas y extranjeros */
        /* 6 para sociedades publicas */
        /* menor que 6 (0,1,2,3,4,5) para personas naturales */

        if (d3==7 || d3==8){
            document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El tercer d\u00edgito ingresado es inv\u00e1lido</label>";
            //alert('El tercer d\u00edgito ingresado es inv\u00e1lido');
            campo.focus();
            return false;
        }

        /* Solo para personas naturales (modulo 10) */
        if (d3 < 6){
            nat = true;
            p1 = d1 * 2;if (p1 >= 10) p1 -= 9;
            p2 = d2 * 1;if (p2 >= 10) p2 -= 9;
            p3 = d3 * 2;if (p3 >= 10) p3 -= 9;
            p4 = d4 * 1;if (p4 >= 10) p4 -= 9;
            p5 = d5 * 2;if (p5 >= 10) p5 -= 9;
            p6 = d6 * 1;if (p6 >= 10) p6 -= 9;
            p7 = d7 * 2;if (p7 >= 10) p7 -= 9;
            p8 = d8 * 1;if (p8 >= 10) p8 -= 9;
            p9 = d9 * 2;if (p9 >= 10) p9 -= 9;
            modulo = 10;
        }

        /* Solo para sociedades publicas (modulo 11) */
        /* Aqui el digito verficador esta en la posicion 9, en las otras 2 en la pos. 10 */
        else if(d3 == 6){
            pub = true;
            p1 = d1 * 3;
            p2 = d2 * 2;
            p3 = d3 * 7;
            p4 = d4 * 6;
            p5 = d5 * 5;
            p6 = d6 * 4;
            p7 = d7 * 3;
            p8 = d8 * 2;
            p9 = 0;
        }

        /* Solo para entidades privadas (modulo 11) */
        else if(d3 == 9) {
            pri = true;
            p1 = d1 * 4;
            p2 = d2 * 3;
            p3 = d3 * 2;
            p4 = d4 * 7;
            p5 = d5 * 6;
            p6 = d6 * 5;
            p7 = d7 * 4;
            p8 = d8 * 3;
            p9 = d9 * 2;
        }

        suma = p1 + p2 + p3 + p4 + p5 + p6 + p7 + p8 + p9;
        residuo = suma % modulo;

        /* Si residuo=0, dig.ver.=0, caso contrario 10 - residuo*/
        digitoVerificador = residuo==0 ? 0: modulo - residuo;

        /* ahora comparamos el elemento de la posicion 10 con el dig. ver.*/
        if (pub==true){
            if (digitoVerificador != d9){
                //alert('El ruc de la empresa del sector p\u00fablico es incorrecto.');
                document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El ruc de la empresa del sector p\u00fablico es incorrecto.</label>";
                campo.focus();
                return false;
            }
            /* El ruc de las empresas del sector publico terminan con 0001*/
            if ( numero.substr(9,4) != '0001' ){
                //alert('El ruc de la empresa del sector p\u00fablico debe terminar con 0001');
                document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El ruc de la empresa del sector p\u00fablico debe terminar con 0001.</label>";
                campo.focus();
                return false;
            }
        }
        else if(pri == true){
            if (digitoVerificador != d10){
                //alert('El ruc de la empresa del sector privado es incorrecto.');
                document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El ruc de la empresa del sector privado es incorrecto.</label>";
                campo.focus();
                return false;
            }
            if ( numero.substr(10,3) != '001' ){
                //alert('El ruc de la empresa del sector privado debe terminar con 001');
                document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El ruc de la empresa del sector privado debe terminar con 001.</label>";
                campo.focus();
                return false;
            }
        }

        else if(nat == true){
            if (digitoVerificador != d10){
                //alert("El n\u00famero de c\u00e9dula de la persona natural es incorrecto.");
                document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El n\u00famero de c\u00e9dula de la persona natural es incorrecto.</label>";
                campo.focus();
                return false;
            }
            let tipo = $("input[type=radio][name=rbCaracterIdentificacion]:checked").val();
            if (numero.length >10 && numero.substr(10,3) != '001' && tipo =='04'){
                document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El ruc de la persona natural debe terminar con 001.</label>";
                //alert('El ruc de la persona natural debe terminar con 001');
                campo.focus();
                return false;
            }else{
                document.getElementById("mensageCedula2").innerHTML="";
            }
        }
    return true;
    document.getElementById("mensageCedula2").innerHTML="";
    }else{
        let tipo = $("input[type=radio][name=rbCaracterIdentificacion]:checked").val();
        if(tipo=='04'){
            document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El numero de RUC debe tener 13 digitos</label>";
        }
        if(tipo=='05'){
            document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El numero de cédula debe tener 10 digitos</label>";
        }
   
        
    }
    return true;
  }
  
   document.getElementById("mensageCedula2").innerHTML="";
return true;

    
}

function no_repetir_ruc(valor, accion)
{
    var auxiliar=0;
    //pagina: proveedores.php
    var ruc = valor.value;
    ajax10=objetoAjax();
    ajax10.open("POST", "sql/proveedores.php",true);
//  alert("salio de la consulta de cedula");
    String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g, "");};
    ajax10.onreadystatechange=function() {
        if (ajax10.readyState==4 ||ajax10.readyState==1) {
            var respuesta10=ajax10.responseText;
//          alert("respuesta de la consulta  "+respuesta4.trim());
            if(respuesta10.trim()==1){
                 document.getElementById("noRepetirRuc").innerHTML="<label style='color: #FF0000'>El Ruc que ingreso ya se encuentra registrado</label>";
                 auxiliar = 1;
                 return auxiliar;
            }
            else {
                document.getElementById("noRepetirRuc").innerHTML="" ;
                auxiliar = 0;
                return auxiliar;
            }
        }
    }
document.getElementById("noRepetirRuc").innerHTML="" ;
ajax10.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
ajax10.send("ruc="+ruc+"&txtAccion="+accion);
return auxiliar;
}


function no_repetir_cedula(valor){    
    
    // validar que la cedula no se repita de los empleados
    var cedula = valor.value;
    ajax4=objetoAjax();
    ajax4.open("POST", "sql/noRepetirCedula.php",true);
  //alert("salio de la consulta de cedula");
    String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g, "");};
    ajax4.onreadystatechange=function() {
        if (ajax4.readyState==4) {
            var respuesta4=ajax4.responseText;
//          alert("respuesta de la consulta  "+respuesta4.trim());
            if(respuesta4.trim()==1){
                 document.getElementById("mensageCedula").innerHTML="<label style='color: #FF0000'>La cedula que ingreso ya se encuentra registrada</label>";
                 auxiliar = 1;
                 return auxiliar;
            }
            else {
                document.getElementById("mensageCedula").innerHTML="" ;
                auxiliar = 0;
                return auxiliar;
            }
        }
    }
document.getElementById("mensageCedula").innerHTML="" ;
ajax4.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
ajax4.send("cedula="+cedula);
return auxiliar;
}



function no_repetir_cedula_empleado(valor){ 
    console.log("valor",valor);
    
    // validar que la cedula no se repita de los empleados
    
    var cedula = valor.value;
    
    ajax4=objetoAjax();
    
    ajax4.open("POST", "sql/noRepetirCedulaEmpleado.php",true);
    
    auxiliar ='0';
  //alert("salio de la consulta de cedula");
    String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g, "");};
    ajax4.onreadystatechange=function() {
        if (ajax4.readyState==4) {
            var respuesta4=ajax4.responseText;
//          alert("respuesta de la consulta  "+respuesta4.trim());
            if(respuesta4.trim()==1){
                 document.getElementById("mensageCedula").innerHTML="<label style='color: #FF0000'>La cedula que ingreso ya se encuentra registrada</label>";
                 auxiliar = 1;
                 return auxiliar;
            }
            else {
                document.getElementById("mensageCedula").innerHTML="" ;
                auxiliar = 0;
                return auxiliar;
            }
        }
    }
document.getElementById("mensageCedula").innerHTML="" ;
ajax4.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
ajax4.send("cedula="+cedula);
return auxiliar; }



//<!-- validacion correo--->
function isEmailAddress(theElement )
{
    var s = theElement.value;
    var filter=/^[A-Za-z0-9_.-]*@[A-Za-z0-9_-]+\.[A-Za-z0-9_.-]+[A-Za-z]$/;
    if (s.length == 0 ) return true;
    //alert("La direccion de correo no es valida  \n Ej: andres@hotmail.com");
    if (filter.test(s)){
        document.getElementById("mensajeEmail").innerHTML="";
        return true;


    }else{
        theElement.focus(); 
        document.getElementById("mensajeEmail").innerHTML="<label style='color: #FF0000'>La direcci\u00f3n de correo no es valida  \n Ej: ejemplo@hotmail.com.</label>";
        document.getElementById("noRepetirEmail").innerHTML="";
        //alert("La direcci\u00f3n de correo no es valida  \n Ej: ejemplo@hotmail.com");
        //theElement.value="";
        return false;
    }
}

   //fin validacion correo--->

 
// <!-- valida solo letras --->

function validar_numero(e,modo)
{ // 1
tecla = (document.all) ? e.keyCode : e.which; // 2
if (tecla==8) return true; // 3
patron = (modo=='letra') ? /[A-Za-zñÑáéíóúÁÉÍÓÚ ]/ : /d/ // 4
te = String.fromCharCode(tecla); // 5
return patron.test(te); // 6
}


//<!--valida  solo numeros --->

  function soloNumeros(evt)
   {
   // desde el 48 solo numeros
   // 44=, 45=–_ 46=. 47=/ 48=0 49=1 en adelante numeros hasta el 57
   var charCode = (evt.which) ? evt.which : event.keyCode
   if (charCode > 31 && (charCode < 48 || charCode > 57))
   return false;
   }


   
function letras_numeros(e){
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8){
        return true;
    }
    // Patron de entrada, en este caso solo acepta numeros
    patron =/[0-9a-zA-Z ]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}



 //<!-- fin validaciones --->

 function long_cedula_ruc(campo,tipo1)
 {	

  // alert("campghhhhhhhhhggggggggo1");
   tipo=tipo1.value;
   numero = campo.value;
     num = numero.length;
	// alert(num);
	// alert(tipo);
	 if (tipo=="C")
		{
			if (num >= 1 & num < 10 ){
		//		alert("entro1");
        //alert("La cedula o RUC no puede ser menor a 10 digitos");
   //     document.getElementById("noRepetirRuc").innerHTML="<label style='color: #FF0000'>La cedula o RUC no puede ser menor a 10 digitos</label>";
				document.getElementById("mensageCedula").innerHTML="<label style='color: #FF0000'>La cedula  no puede ser menor a 10 digitos</label>";
        
            campo.focus();
      //  return false;
				//auxiliar = 1;
                 return false;
			}
			else
				if (num > 10 )
				{
			//		alert("entro2");
        //alert("La cedula o RUC no puede ser menor a 10 digitos");
   //    document.getElementById("noRepetirRuc").innerHTML="<label style='color: #FF0000'>La cedula  no puede ser mayor a 10 digitos</label>";
					document.getElementById("mensageCedula").innerHTML="<label style='color: #FF0000'>La cedula no puede ser mayor a 10 digitos</label>";
        
        campo.focus();
        //return false;
		
				//	auxiliar = 1;
					return false;

				}
				else
				   {
				     document.getElementById("mensageCedula").innerHTML="" ;
                     auxiliar = 0;
                    return true;
			       }	
		}
		else
       {
			if (tipo=="R")
		   {
			   if (num != 13 )
			   {
			//	alert("entro3");
				document.getElementById("mensageCedula").innerHTML="<label style='color: #FF0000'>El ruc  no puede ser diferente de 13 digitos</label>";      
     campo.focus();				
 				//auxiliar = 1;
                 return false;
			   }
			   else
				   {
				     document.getElementById("mensageCedula").innerHTML="" ;
                auxiliar = 0;
                return true;
			   }	 
			   
	  	   }
		   else
			    {
				     document.getElementById("mensageCedula").innerHTML="" ;
                auxiliar = 0;
                return true;
			   }	 
			   
		}
		
/* 	 
	   alert(cedula1.value)
		alert(tipo.value);
		alert(val(cedula1.length));
	  	if (tipo.value=="C")
		{
			if (cedula1.length != 10)
			{
                 alert("entro");
	 		     document.getElementById("noRepetirRuc").innerHTML="<label style='color: #FF0000'>Para cedula el numero de digito debe ser 10</label>";
                 auxiliar = 1;
                 return auxiliar;
			}
	    }
		else
			if (tipo.value=="R")
		   {
			if (cedula1.length != 13)
			{
//			return false;
	 		     document.getElementById("noRepetirRuc").innerHTML="<label style='color: #FF0000'>El ruc debe tener 13 diigitos</label>";
                 auxiliar = 1;
                 return auxiliar;
			}
	    } */
		
		     
   // desde el 48 solo numeros
   // 44=, 45=–_ 46=. 47=/ 48=0 49=1 en adelante numeros hasta el 57
  /*  var charCode = (evt.which) ? evt.which : event.keyCode
   if (charCode > 31 && (charCode < 48 || charCode > 57))
   return false; */
   }

 
 //<!-- valida pone la primera letra en mayuscula --->

function validar(solicitar){
    var index;
    var tmpStr;
    var tmpChar;
    var preString;
    var postString;
    var strlen;
    tmpStr = solicitar.value.toLowerCase();
    strLen = tmpStr.length;
    if (strLen > 0)
    {
        for (index = 0; index < strLen; index++)
        {
            if (index == 0)
            {
                tmpChar = tmpStr.substring(0,1).toUpperCase();
                postString = tmpStr.substring(1,strLen);
                tmpStr = tmpChar + postString;
            }
            else
            {
                tmpChar = tmpStr.substring(index, index+1);
                if (tmpChar == " " && index < (strLen-1))
                {
                    tmpChar = tmpStr.substring(index+1, index+2).toUpperCase();
                    preString = tmpStr.substring(0, index+1);
                    postString = tmpStr.substring(index+2,strLen);
                    tmpStr = preString + tmpChar + postString;
                }
            }
        }
    }
    solicitar.value = tmpStr;
}


// valida contraseñas que coincidan
function validarContrasena(dml)
{    
    contrasena = dml.elements['txtPassword'].value;
    longitud1 = contrasena.length;
    rpcontrasena = dml.elements['txtRpPassword'].value
    longitud2 = rpcontrasena.length;

    if(longitud1 >= 1 && longitud2 >=1){
        if (dml.elements['txtPassword'].value==dml.elements['txtRpPassword'].value)
        {
            document.getElementById("errorPassword").innerHTML="";
            return true;            
        }
        else
        {
            document.getElementById("errorPassword").innerHTML="<label style='color: #FF0000'>Las contrase&ntilde;as no son iguales</label>";
            dml.elements['txtPassword'].focus();
            return false;
        }
    }
    document.getElementById("errorPassword").innerHTML="";
    return true;    
}

 //<!--valida  precio --->

function precio(evt)
    {
        // desde el 48 solo numeros
        // 44=, 45=–_ 46=. 47=/ 48=0 49=1 en adelante numeros hasta el 57
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 44 || charCode > 57))
        return false;
    }


// valida que no se repita el login de usuario
function no_repetir_login(valor, accion)
{    
    var login = valor.value;
    ajax2=objetoAjax();
    ajax2.open("POST", "sql/usuarios.php",true);
    ajax2.onreadystatechange=function() {
        if (ajax2.readyState==4) {
            var respuesta2=ajax2.responseText;
//          alert("  login: "+login+"  consulta: "+respuesta2.trim());
            if(respuesta2.trim()==1){
                 document.getElementById("noRepetirLogin").innerHTML="<label style='color: #FF0000'>El nombre de usuario que ingreso ya se encuentra registrado</label>";
                 valor.focus();
                 aux = 1;
                 return aux;
            }
            else {
                document.getElementById("noRepetirLogin").innerHTML="";
                aux = 0;
                return aux;
            }
        }
    }
    document.getElementById("noRepetirLogin").innerHTML="";
    ajax2.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax2.send("login="+login+"&txtAccion="+accion);
    return aux;
}

 function eliminarCeros(cad){
    if(cad.length==2){
        if(cad.charAt(0)=='0')
        return cad.charAt(1);
    }
    return cad;
}

// valida fechas en la pagina periodoContable.php
function validarFechas(){

    fecha_desde=document.getElementById("txtFechaIngreso").value;
    fecha_hasta=document.getElementById("txtFechaSalida").value;
    fa = fecha_desde.split("-");
    fn = fecha_hasta.split("-");

    if(parseInt(fn[0]) > parseInt(fa[0])){
         document.getElementById('fecha_hasta').innerHTML="correcto 1";
         document.getElementById('fecha_desde').innerHTML="";
         //guardar_periodo_contable();
         return true;
    }
    else if(parseInt(fn[0]) < parseInt(fa[0])){
         document.getElementById('fecha_desde').innerHTML="";
         document.getElementById('fecha_hasta').innerHTML="<label style='color: #FF0000'>Esta fecha no puede ser Menor.</label>";
         return false;
    }
    else if(parseInt(fn[0]) == parseInt(fa[0])){
        if(parseInt(eliminarCeros(fn[1]))>parseInt(eliminarCeros(fa[1]))){
             document.getElementById('fecha_hasta').innerHTML="correcto 3";
             document.getElementById('fecha_desde').innerHTML="";
             //guardar_periodo_contable();
             return true;
        }
        else if(parseInt(eliminarCeros(fn[1]))<parseInt(eliminarCeros(fa[1]))) {
             document.getElementById('fecha_desde').innerHTML="";
             document.getElementById('fecha_hasta').innerHTML="<label style='color: #FF0000'>Esta fecha no puede ser Menor.</label>";
             return false;
        }
        else if(parseInt(eliminarCeros(fn[1]))==parseInt(eliminarCeros(fa[1]))){
            if(parseInt(eliminarCeros(fn[2]))>parseInt(eliminarCeros(fa[2]))){
                 document.getElementById('fecha_hasta').innerHTML="correcto 5";
                 document.getElementById('fecha_desde').innerHTML="";
                 //guardar_periodo_contable();
                 return true;
            }
            else if(parseInt(eliminarCeros(fn[2]))<parseInt(eliminarCeros(fa[2]))){
                 document.getElementById('fecha_desde').innerHTML="";
                 document.getElementById('fecha_hasta').innerHTML="<label style='color: #FF0000'>Esta fecha no puede ser Menor.</label>";
                 return false;
            }
            else{
                document.getElementById('fecha_hasta').innerHTML="<label style='color: #FF0000'>Las fechas no pueden ser iguales.</label>";
                document.getElementById('fecha_desde').innerHTML="<label style='color: #FF0000'>Las fechas no pueden ser iguales.</label>";
                return false;
            }
        }
    }
 return false;
}

// valida fechas en la pagina verlibroDiario.php
function validaFechas(txtFechaDesde, txtFechaHasta){

    fecha_desde=txtFechaDesde;
    fecha_hasta=txtFechaHasta;    
    //fecha_desde=document.getElementById("txtFechaDesde").value;
    //fecha_hasta=document.getElementById("txtFechaHasta").value;
    fa = fecha_desde.split("-");
    fn = fecha_hasta.split("-");

    if(parseInt(fn[0]) > parseInt(fa[0])){
         document.getElementById('mensajefecha_hasta').innerHTML="";
         document.getElementById('mensajefecha_desde').innerHTML="";
         //guardar_periodo_contable();
         return true;
    }
    else if(parseInt(fn[0]) < parseInt(fa[0])){         
         document.getElementById('mensajefecha_hasta').innerHTML="<label style='color: #FF0000'>Esta fecha no puede ser Menor.</label>";
         document.getElementById('mensajefecha_desde').innerHTML="<label style='color: #FF0000'>Esta fecha no puede ser Mayor.</label>";
         return false;
    }
    else if(parseInt(fn[0]) == parseInt(fa[0])){
        if(parseInt(eliminarCeros(fn[1]))>parseInt(eliminarCeros(fa[1]))){
             document.getElementById('mensajefecha_hasta').innerHTML="";
             document.getElementById('mensajefecha_desde').innerHTML="";
             //guardar_periodo_contable();
             return true;
        }
        else if(parseInt(eliminarCeros(fn[1]))<parseInt(eliminarCeros(fa[1]))) {             
             document.getElementById('mensajefecha_hasta').innerHTML="<label style='color: #FF0000'>Esta fecha no puede ser Menor.</label>";
             document.getElementById('mensajefecha_desde').innerHTML="<label style='color: #FF0000'>Esta fecha no puede ser Mayor.</label>";
             return false;
        }
        else if(parseInt(eliminarCeros(fn[1]))==parseInt(eliminarCeros(fa[1]))){
            if(parseInt(eliminarCeros(fn[2]))>parseInt(eliminarCeros(fa[2]))){
                 document.getElementById('mensajefecha_hasta').innerHTML="";
                 document.getElementById('mensajefecha_desde').innerHTML="";
                 //guardar_periodo_contable();
                 return true;
            }
            else if(parseInt(eliminarCeros(fn[2]))<parseInt(eliminarCeros(fa[2]))){                
                 document.getElementById('mensajefecha_hasta').innerHTML="<label style='color: #FF0000'>Esta fecha no puede ser Menor.</label>";
                 document.getElementById('mensajefecha_desde').innerHTML="<label style='color: #FF0000'>Esta fecha no puede ser Mayor.</label>";
                 return false;
            }
            else{
                //document.getElementById('mensajefecha_hasta').innerHTML="<label style='color: #FF0000'>Las fechas no pueden ser iguales.</label>";
                //document.getElementById('mensajefecha_desde').innerHTML="<label style='color: #FF0000'>Las fechas no pueden ser iguales.</label>";
                //return false;
                 return true;
            }
        }
    }
 return false;
}

// validar pa que el nombre de departamentos no se repita pagina: nuevoDepartamento.php
function no_repetir_nombre_departamentos(valor, accion)
{    
    var nombre = valor.value;   
    ajax1=objetoAjax();
    ajax1.open("POST", "sql/departamentos.php",true);  
    String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g, "");};
    ajax1.onreadystatechange=function() {
        if (ajax1.readyState==4) {
            var respuesta1=ajax1.responseText;          
            if(respuesta1.trim()==1){
                 document.getElementById("noRepetirNombreDepartamento").innerHTML="<label style='color: #FF0000'>El nombre que ingreso ya se encuentra registrado</label>";
                 auxiliar = 1;
                 return auxiliar;
            }
            else {
                document.getElementById("noRepetirNombreDepartamento").innerHTML="" ;
                auxiliar = 0;
                return auxiliar;
            }
        }
    }
    document.getElementById("noRepetirNombreDepartamento").innerHTML="" ;
    ajax1.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax1.send("nombre="+nombre+"&txtAccion="+accion);
    return auxiliar;
}

// validar pa que el nombre de cargos no se repita pagina: nuevoCargo.php
function no_repetir_nombre_cargo(valor, accion)
{
    var nombre = valor.value;
    ajax5=objetoAjax();
    ajax5.open("POST", "sql/cargos.php",true);
    String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g, "");};
    ajax5.onreadystatechange=function() {
        if (ajax5.readyState==4) {
            var respuesta5=ajax5.responseText;
            if(respuesta5.trim()==1){
                 document.getElementById("noRepetirNombreCargo").innerHTML="<label style='color: #FF0000'>El nombre que ingreso ya se encuentra registrado</label>";
                 auxiliar = 1;
                 return auxiliar;
            }
            else {
                document.getElementById("noRepetirNombreCargo").innerHTML="" ;
                auxiliar = 0;
                return auxiliar;
            }
        }
    }
    document.getElementById("noRepetirNombreCargo").innerHTML="" ;
    ajax5.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax5.send("nombre="+nombre+"&txtAccion="+accion);
    return auxiliar;
}

// validar el formato de entrada de horas
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function mascara(d,sep,pat,nums){
if(d.valant != d.value){
	val = d.value
	largo = val.length
	val = val.split(sep)
	val2 = ''
	for(r=0;r<val.length;r++){
		val2 += val[r]
	}
	if(nums){
            for(z=0;z<val2.length;z++){
                if(isNaN(val2.charAt(z))){
                    letra = new RegExp(val2.charAt(z),"g")
                    val2 = val2.replace(letra,"")
                }
            }
	}
	val = ''
	val3 = new Array()
	for(s=0; s<pat.length; s++){
            val3[s] = val2.substring(0,pat[s])
            val2 = val2.substr(pat[s])
	}
	for(q=0;q<val3.length; q++){
            if(q ==0){
                val = val3[q]
            }
            else{
                if(val3[q] != ""){
                    val += sep + val3[q]
                }
            }
	}
	d.value = val
	d.valant = val
	}
}

function jornadaTrabajada(form, cmbCombo){
    //funciona en la pagina: registroDiario.php
    if(cmbCombo.value == "No"){
    document.getElementById("txtHoraIngreso").value="";
    document.getElementById("txtHoraIngreso").disabled=true;
    document.getElementById("txtHoraSalida").disabled=true;
    document.getElementById("txtHorasExtras").disabled=true;
    document.getElementById("txtHorasSuplementarias").disabled=true;
    document.getElementById("txtSobretiempo").disabled=true;
    }else {
        document.getElementById("txtHoraIngreso").disabled=false;
        document.getElementById("txtHoraSalida").disabled=false;
        document.getElementById("txtHorasExtras").disabled=false;
        document.getElementById("txtHorasSuplementarias").disabled=false;
        document.getElementById("txtSobretiempo").disabled=false;
    }
}


function number_format (number, decimals, dec_point, thousands_sep) {
    // valida los decimales funciona pagina: rolPagos.php
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function (n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);

}


function no_repetir_pais(valor, accion)
{
    // validar pa que el nombre de pais no se repita pagina: direcciones.php
    var nombre = valor.value;
    ajax6=objetoAjax();
    ajax6.open("POST", "sql/direcciones.php",true);
    String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g, "");};
    ajax6.onreadystatechange=function() {
        if (ajax6.readyState==4) {
            var respuesta6=ajax6.responseText;
            if(respuesta6.trim()==1){
                 document.getElementById("noRepetirPais").innerHTML="<label style='color: #FF0000'>El nombre que ingreso ya se encuentra registrado</label>";
                 auxiliar = 1;
                 return auxiliar;
            }
            else {
                document.getElementById("noRepetirPais").innerHTML="" ;
                auxiliar = 0;
                return auxiliar;
            }
        }
    }
document.getElementById("noRepetirPais").innerHTML="" ;
ajax6.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
ajax6.send("nombre="+nombre+"&txtAccion="+accion);
return auxiliar;
}

function no_repetir_provincia(valor, accion)
{
    // validar pa que el nombre de provincia no se repita pagina: direcciones.php
    var nombre = valor.value;
    ajax7=objetoAjax();
    ajax7.open("POST", "sql/direcciones.php",true);
    String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g, "");};
    ajax7.onreadystatechange=function() {
        if (ajax7.readyState==4) {
            var respuesta7=ajax7.responseText;
            if(respuesta7.trim()==1){
                 document.getElementById("noRepetirProvincia").innerHTML="<label style='color: #FF0000'>El nombre que ingreso ya se encuentra registrado</label>";
                 auxiliar = 1;
                 return auxiliar;
            }
            else {
                document.getElementById("noRepetirProvincia").innerHTML="" ;
                auxiliar = 0;
                return auxiliar;
            }
        }
    }
document.getElementById("noRepetirProvincia").innerHTML="" ;
ajax7.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
ajax7.send("nombre="+nombre+"&txtAccion="+accion);
return auxiliar;
}

function no_repetir_ciudad(valor, accion)
{
    // validar pa que el nombre de provincia no se repita pagina: direcciones.php
    var nombre = valor.value;
    ajax8=objetoAjax();
    ajax8.open("POST", "sql/direcciones.php",true);
    String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g, "");};
    ajax8.onreadystatechange=function() {
        if (ajax8.readyState==4) {
            var respuesta8=ajax8.responseText;
            if(respuesta8.trim()==1){
                 document.getElementById("noRepetirCiudad").innerHTML="<label style='color: #FF0000'>El nombre que ingreso ya se encuentra registrado</label>";
                 auxiliar = 1;
                 return auxiliar;
            }
            else {
                document.getElementById("noRepetirCiudad").innerHTML="" ;
                auxiliar = 0;
                return auxiliar;
            }
        }
    }
document.getElementById("noRepetirCiudad").innerHTML="" ;
ajax8.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
ajax8.send("nombre="+nombre+"&txtAccion="+accion);
return auxiliar;
}


function no_repetir_codigo_producto(valor, accion)
{
    //pagina: proveedores.php
//	alert(valor.value);
    var codigo = valor.value;
	//alert("codigo");
	//alert(codigo);
    ajax10=objetoAjax();
    var auxiliar =0;
    ajax10.open("POST", "sql/productos.php",true);
  //alert("salio de la consulta de cedula");
    String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g, "");};
    ajax10.onreadystatechange=function() 
	{
        if (ajax10.readyState==4) 
	   {
           var respuesta10=ajax10.responseText;
           if(respuesta10.trim()==1)
		  {
                 document.getElementById("noRepetirCodigoProducto").innerHTML="<label style='color: #FF0000'>Codigo de producto ya existe</label>";
                 auxiliar = 1;
                 return auxiliar;
           }
           else
	       {
                document.getElementById("noRepetirCodigoProducto").innerHTML="" ;
                auxiliar = 0;
                return auxiliar;
            }
        }    
    }
   document.getElementById("noRepetirCodigoProducto").innerHTML="" ;
   ajax10.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
   ajax10.send("codigo="+codigo+"&txtAccion="+accion);
   return auxiliar;
}


function no_repetir_producto(valor, accion)
{

	var nombre = valor.value;
	var auxiliar=0;
    ajax9=objetoAjax();
    ajax9.open("POST", "sql/productos.php",true);
    String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g, "");};
    ajax9.onreadystatechange=function() {
        if (ajax9.readyState==4) {
            var respuesta9=ajax9.responseText;
			
	       //alert(respuesta9.trim());
		
            if(respuesta9.trim()==1){
                 document.getElementById("noRepetirProducto").innerHTML="<label style='color: #FF0000'>El nombre del producto ya esta registrado</label>";
                 auxiliar = 1;
                 return auxiliar;
            }
            else {
                document.getElementById("noRepetirProducto").innerHTML="" ;
                auxiliar = 0;
                return auxiliar;
            }
        }
    }
document.getElementById("noRepetirProducto").innerHTML="" ;
ajax9.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
ajax9.send("producto="+nombre+"&txtAccion="+accion);
return auxiliar;
}




function comprobar(txtId, accion, txtano, txtmes)
{    
    // COMPROBAR Q UN empleado TENGA UNA COMISION POR MES PAGINA: nuevaComision.php
    var id = txtId.value;
    var ano = txtano.value;
    var mes = txtmes.value;  
    auxiliar = '0';
    ajax11=objetoAjax();
    ajax11.open("POST", "sql/comisiones.php",true);
    String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g, "");};
    ajax11.onreadystatechange=function() {
        if (ajax11.readyState==4) {
            var respuesta4=ajax11.responseText;
//          alert("respuesta de la consulta  "+respuesta4.trim());
            if(respuesta4.trim()==1){
                 document.getElementById("mensajeComparacion").innerHTML="<label style='color: #FF0000'>La comision para este empleado ya se encuentra ingresada\n Solo puede ingresar una comision por cada mes para un empleado</label>";
                 auxiliar = 1;
                 return auxiliar;
            }
            else {
                document.getElementById("mensajeComparacion").innerHTML="";
                auxiliar = 0;
                return auxiliar;
            }
        }
    }
document.getElementById("mensajeComparacion").innerHTML="" ;
ajax11.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
ajax11.send("idEmpleado="+id+"&txtAccion="+accion+"&ano="+ano+"&mes="+mes);
return auxiliar;
}


//**************************  PLAN CUENTAS *****************************//

function no_repetir_codigo(txtCodigo){
    
    //funcion para que no se repita el codigo al registrar una cuenta contable Pagina: ajax/nuevoplanCuentas.php
    var codigo=txtCodigo.value;    
    //auxiliar = 1;
    ajax12=objetoAjax();
    ajax12.open("POST", "sql/plan_cuentas.php",true);

    String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g, "");};
    ajax12.onreadystatechange=function() {
        if (ajax12.readyState==4) {
            var respuesta4=ajax12.responseText;

            if(respuesta4.trim()==1){                
                document.getElementById("codigoVacio").innerHTML="";
                document.getElementById("nombreVacio").innerHTML="";
                document.getElementById("noRepetirCodigo").innerHTML="<label style='color: #FF0000'>El codigo que ingreso ya se encuentra registrado</label>";
                auxiliar = 1;
                return auxiliar;
            }
            else{                
                document.getElementById("noRepetirCodigo").innerHTML="";
                auxiliar = 0;
                return auxiliar;
            }
        }
    }
    ajax12.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax12.send("codigo="+codigo+"&accion="+10);
    return auxiliar;
}

function no_repetir_nombre(){
    //funcion para que no se repita el nombre al registrar una cuenta contable Pagina: ajax/nuevoplanCuentas.php
    //alert("valida nombre");
    var nombre=document.getElementById("txtNombre").value;

            ajax13=objetoAjax();
            ajax13.open("POST", "sql/plan_cuentas.php",true);
            String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g, "");};
            ajax13.onreadystatechange=function() {
                if (ajax13.readyState==4) {
                    var respuesta4=ajax13.responseText;
//                         alert("respuesta "+respuesta4);
                    if(respuesta4.trim()==1){
                        document.getElementById("codigoVacio").innerHTML="";
                        document.getElementById("nombreVacio").innerHTML="";
                        document.getElementById("noRepetirNombre").innerHTML="<label style='color: #FF0000'>El nombre que ingreso ya se encuentra registrado</label>";
                    }
                    else{document.getElementById("noRepetirNombre").innerHTML="" ;}
                }
            }
            ajax13.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
            ajax13.send("nombre="+nombre+"&accion="+11);

}

function guardar_plan_cuentas(dml)
{
    // guarda plan cuentas pagina: planCuentas.php
    
    var txtPermisosPlanCuentasGuardar = dml.elements['txtPermisosPlanCuentasGuardar'].value;
    
    if(txtPermisosPlanCuentasGuardar == "No"){
        alert("Usted No tiene permisos. \nConsulte con el Administrador.");
    }else{
        var nombre = dml.elements['txtNombre'].value;
        var txtCodigo = dml.elements['txtCodigo'].value;

        codigo = txtCodigo.replace(/[^\d]/g, '');//[^0-9] '/[^0-9]/'

        if(codigo >= 1){

            var codigo = no_repetir_codigo(txtCodigo)// retorna 0 o 1
            if(codigo == 0){
                var str = $("#form").serialize();
                $.ajax({
                        url: 'sql/plan_cuentas.php',
                        data: str+"&accion="+3,
                        type: 'post',
                        success: function(data){
                            console.log(data);
                            alertify.success("Cuenta Agregada con exito");
                            //document.getElementById('mensaje1').innerHTML=""+data;
                            dml.elements['txtCodigo'].value = "";
                            dml.elements['txtNombre'].value = "";
                            document.getElementById("codigoVacio").innerHTML="";
                            document.getElementById("nombreVacio").innerHTML="";
                            document.getElementById("noRepetirNombre").innerHTML="" ;
                            document.getElementById("noRepetirCodigo").innerHTML="" ;
                             fn_cerrar();
                            fn_buscar(1,1);
                        }
                });
            } else{
                alert("No se puede guardar por que el Codigo: "+txtCodigo+" ya se encuentra registrado");
                dml.elements['txtCodigo'].focus();
            }

        }else{
            document.getElementById("codigoVacio").innerHTML="<label style='color: #FF0000'>Este campo no puede estar vacio</label>";
            dml.elements['txtCodigo'].focus();
        }
    }    
    
}


//**************************** PROVEEDORES *************************

function validaDatosProveedores(dml,accion)
{
	accion=accion.val();
alert("aaa");
	alert(accion);
	if (accion == 1)
	{		
    // Codigo
    cod = 0;
    nom = 0;
    
    var ruc = no_repetir_ruc(document.getElementById("txtRuc").value, 4)
    
    var idCiudad=document.getElementById("opcion2").value; 

    if(ruc == 0){
        if(idCiudad >=1){
            guardar_proveedores(cod,nom);
        }else{
            alert ('No ha seleccionado la ciudad.');
            dml.elements['cbciudad'].focus();
        }

    }else{
        alert ('El ruc '+ruc+' que ingreso ya se encuentra registrado.');
        dml.elements['ruc'].focus();
    }
    }
}

function noRepetirRucEmpresa(valor, accion)
{
    // validar que la cedula no se repita de los empleados
    var ruc = valor.value;
    ajax18=objetoAjax();
    auxiliar = 0;
    ajax18.open("POST", "sql/empresa.php",true);
//  alert("salio de la consulta de cedula");
    String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g, "");};
    ajax18.onreadystatechange=function() {
        if (ajax18.readyState==4) {
            var respuesta4=ajax18.responseText;
       //alert("respuesta de la consulta  "+respuesta4.trim());
            if(respuesta4.trim()==1){
                 document.getElementById("mensageCedula").innerHTML="<label style='color: #FF0000'>El Ruc que ingreso ya se encuentra registrado</label>";
                 valor.focus();
                 auxiliar = 1;
                 return auxiliar;
            }
            else {
                document.getElementById("mensageCedula").innerHTML="" ;
                auxiliar = 0;
                return auxiliar;
            }
        }
    }
document.getElementById("mensageCedula").innerHTML="" ;
ajax18.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
ajax18.send("ruc="+ruc+"&accion="+accion);
return auxiliar;
}

function noRepetirCodigoEmpresa(valor, accion)
{
    
    // validar que el codigo de empresa no se repita pag: crear_empresa.php
    var codigo = valor.value;
    ajax19=objetoAjax();
    ajax19.open("POST", "sql/empresa.php",true);
//  alert("salio de la consulta de cedula");
auxiliar = 0;
    String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g, "");};
    ajax19.onreadystatechange=function() {
        if (ajax19.readyState==4) {
            var respuesta19=ajax19.responseText;
//          alert("respuesta de la consulta  "+respuesta19.trim());
            if(respuesta19.trim()==1){
                 document.getElementById("mensajeNoRepetirCodigoEmpresa").innerHTML="<label style='color: #FF0000'>El Codigo que ingreso ya se encuentra registrado</label>";
                 valor.focus();
                 auxiliar = 1;
                 return auxiliar;
            }
            else {
                document.getElementById("mensajeNoRepetirCodigoEmpresa").innerHTML="" ;
                auxiliar = 0;
                return auxiliar;
            }
        }
    }
document.getElementById("mensajeNoRepetirCodigoEmpresa").innerHTML="" ;
ajax19.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
ajax19.send("txtCodEmpresa="+codigo+"&accion="+accion);
return auxiliar;
}

// valida que no se repita el email
function no_repetir_email_empresa(valor, accion)
{
    var email = valor.value;
    ajax20=objetoAjax();
    aux = 0;
    ajax20.open("POST", "sql/empleados.php",true);
    ajax20.onreadystatechange=function() {
        if (ajax20.readyState==4) {
            var respuesta20=ajax20.responseText;
//          alert("  login: "+login+" rp consulta: "+respuesta2.trim());
            if(respuesta20.trim()==1){
                 document.getElementById("noRepetirEmail").innerHTML="<label style='color: #FF0000'>El Email que ingreso ya se encuentra registrado</label>";
                 document.getElementById("mensajeEmail").innerHTML="";
                 valor.focus();
                 aux = 1;
                 return aux;
            }
            else {
                document.getElementById("noRepetirEmail").innerHTML="";
                //document.getElementById("mensajeEmail").innerHTML="";
                aux = 0;
                return aux;
            }
        }
    }
    document.getElementById("noRepetirEmail").innerHTML="";

    ajax20.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax20.send("email="+email+"&txtAccion="+accion);
    return aux;
}











function no_repetir_cedula_clientes(valor, accion)
{
     
    // validar que la cedula no se repita de los empleados
   // alert("valor: "+valor);
    
    var cedula = valor;
  //  console.log("cedula",cedula);
    ajax20=objetoAjax();
    ajax20.open("POST", "sql/clientes.php",true);
    auxiliar = 0;
  //alert("salio de la consulta de cedula");
    String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g, "");};
    ajax20.onreadystatechange=function() {
    if (ajax20.readyState==4) {
            var respuesta20=ajax20.responseText;
console.log("respuesta de la consulta  ",respuesta20);
            if(respuesta20.trim()==1){
                 document.getElementById("mensageCedula").innerHTML="<div class='bg-danger p-2'>La cedula que ingreso ya se encuentra registrada</div>";
                // valor.focus();
                 auxiliar = 1;
                 return auxiliar;
            }
            else {
                document.getElementById("mensageCedula").innerHTML="" ;
                auxiliar = 0;
                return auxiliar;
            }
        }
    }
//document.getElementById("mensageCedula").innerHTML="" ;
ajax20.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
ajax20.send("cedula="+cedula+"&accion="+accion);
return auxiliar;
}

// valida que no se repita el email
function no_repetir_telefono_lead(valor, accion)
{
    var telefono = valor.value;
    ajax21=objetoAjax();
    ajax21.open("POST", "sql/clientes.php",true);
    ajax21.onreadystatechange=function() {
        if (ajax21.readyState==4) {
            var respuesta21=ajax21.responseText;
        //console.log(" rp consulta: "+respuesta21.trim());
            if(respuesta21.trim()==1){
                 document.getElementById("noRepetirTelefono").innerHTML="<label style='color: #FF0000'>El telefono que ingreso ya se encuentra registrado</label>";
                 document.getElementById("mensajeTelefono").innerHTML="";
                 valor.focus();
                 aux = 1;
                 return aux;
            }
            else {
                document.getElementById("noRepetirTelefono").innerHTML="";
                //document.getElementById("mensajeEmail").innerHTML="";
                aux = 0;
                return aux;
            }
        }
    }
    document.getElementById("noRepetirTelefono").innerHTML="";

    ajax21.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax21.send("telefono="+telefono+"&accion="+accion);
    return aux;
}

function no_repetir_email_cliente(valor, accion)
{
    aux=0;
    var email = valor.value;
    ajax21=objetoAjax();
    
    ajax21.open("POST", "sql/clientes.php",true);
    ajax21.onreadystatechange=function() {
        if (ajax21.readyState==4) {
            var respuesta21=ajax21.responseText;
//          alert("  login: "+login+" rp consulta: "+respuesta2.trim());
            if(respuesta21.trim()==1){
                 document.getElementById("noRepetirEmail").innerHTML="<label style='color: #FF0000'>El Email que ingreso ya se encuentra registrado</label>";
                 document.getElementById("mensajeEmail").innerHTML="";
                 valor.focus();
                 aux = 1;
                 return aux;
            }
            else {
                document.getElementById("noRepetirEmail").innerHTML="";
                //document.getElementById("mensajeEmail").innerHTML="";
                aux = 0;
                return aux;
            }
        }
    }
    document.getElementById("noRepetirEmail").innerHTML="";

    ajax21.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax21.send("email="+email+"&accion="+accion);
    return aux;
}



function noRepetirNumeroDocumento(valor, accion){

    var numero_documento = valor.value;
    ajax22=objetoAjax();
    ajax22.open("POST", "sql/bancos.php",true);
    ajax22.onreadystatechange=function() {
        if (ajax22.readyState==4) {
            var respuesta22=ajax22.responseText;
          //alert("  numero_documento: "+numero_documento+" rp consulta: "+respuesta22.trim());
            if(respuesta22.trim()==1){
                //alert("entro");
                 document.getElementById("noRepetirNumeroDocumento").innerHTML="<div class='alert alert-danger'>El Número de Documento ya se encuentra registrado</div>";
                 //document.getElementById("noRepetirNumeroDocumento").innerHTML="";
                 valor.focus();
                 aux = 1;
                 return aux;
            }
            else {
                document.getElementById("noRepetirNumeroDocumento").innerHTML="";
                //document.getElementById("mensajeEmail").innerHTML="";
                aux = 0;
                return aux;
            }
        }
    }
    document.getElementById("noRepetirNumeroDocumento").innerHTML="";

    ajax22.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax22.send("numero_documento="+numero_documento+"&accion="+accion);
    return aux;

}

function no_repetir_numero_casa_cliente(valor, accion)
{
    var numero_casa = valor.value;
    ajax23=objetoAjax();
    ajax23.open("POST", "sql/clientes.php",true);
    ajax23.onreadystatechange=function() {
        if (ajax23.readyState==4) {
            var respuesta23=ajax23.responseText;
          //alert("  numero_casa: "+numero_casa+" rp consulta: "+respuesta23.trim());
            if(respuesta23.trim()==1){
                 document.getElementById("noRepetirNumeroCasa").innerHTML="<label style='color: #FF0000'>El número de casa que ingreso ya se encuentra registrado</label>";
                 
                 valor.focus();
                 aux = 1;
                 return aux;
            }
            else {
                document.getElementById("noRepetirNumeroCasa").innerHTML="";
                //document.getElementById("mensajeEmail").innerHTML="";
                aux = 0;
                return aux;
            }
        }
    }
    document.getElementById("noRepetirNumeroCasa").innerHTML="";

    ajax23.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax23.send("numero_casa="+numero_casa+"&accion="+accion);
    return aux;
}


// validar pa que el nombre de departamentos no se repita pagina: nuevoDepartamento.php
function no_repetir_nombre_departamentos(valor, accion)
{    
    console.log("valor",valor);
    var nombre = valor.value;   
    ajax1=objetoAjax();
    ajax1.open("POST", "sql/departamentos.php",true);  
    String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g, "");};
    ajax1.onreadystatechange=function() {
        if (ajax1.readyState==4) {
            var respuesta1=ajax1.responseText;          
            if(respuesta1.trim()==1){
                 document.getElementById("noRepetirNombreDepartamento").innerHTML="<label style='color: #FF0000'>El nombre que ingreso ya se encuentra registrado</label>";
                 auxiliar = 1;
                 return auxiliar;
            }
            else {
                document.getElementById("noRepetirNombreDepartamento").innerHTML="" ;
                auxiliar = 0;
                return auxiliar;
            }
        }
    }
    document.getElementById("noRepetirNombreDepartamento").innerHTML="" ;
    ajax1.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax1.send("nombre="+nombre+"&txtAccion="+accion);
    return auxiliar;
}


// VALIDACION DE CEDULA Y RUC 2 mejorada 
function cedula_ruc_2 (valor,campo) {
    // console.log('validacion');
    // let tipo2 = $("input[type=radio][name=rbCaracterIdentificacion]:checked").val();
    let tipo2 = '05';

    if (  tipo2 =='10'){

        num = valor.length;
        if(num!=13){
            document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El numero ingresado debe tener 13 digitos</label>";
            campo.focus();
            return false;
        }else{
            document.getElementById("mensageCedula2").innerHTML="";
        }
        let numero = valor.toString();
        numero = numero.slice(-3); 
    
        if(numero !='001'){
            document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El numero ingresado terminar en 001</label>";
            campo.focus();
            return false;
        }
     
        return false;
    }
    // valida que no sea consumidor final
    if ( tipo2=='07' ){
        document.getElementById("mensageCedula2").innerHTML="";
        campo.focus();
            return false;
    }
    numero = valor;
    numero = numero.toString();
    num = numero.length;
    if (num >= 1 & num < 10 ){
        //alert("La cedula o RUC no puede ser menor a 10 digitos");
        document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>La cedula o RUC no puede ser menor a 10 digitos</label>";
        campo.focus();
        return false;
    }

    if ( num !=10  && tipo2=='05' ){
    
        document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>La cedula debe tener solo 10 digitos</label>";
        campo.focus();
            return false;
        
        
    }
    if ( num == 10  && tipo2=='04' ){
    
        document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El RUC debe tener 13 digitos</label>";
        campo.focus();
            return false;
        
    }
    if ( num == 10 || num==13 ){
        var suma = 0;
        var residuo = 0;
        var pri = false;
        var pub = false;
        var nat = false;
        var numeroProvincias = 22;
        var modulo = 11;

        /* Verifico que el campo no contenga letras */
        var ok=1;
        //for (i=0; i numeroProvincias){
        //alert('El c�digo de la provincia (dos primeros d�gitos) es inv�lido'); return false;
        //}

        /* Aqui almacenamos los digitos de la cedula en variables. */
        d1 = numero.substr(0,1);
        d2 = numero.substr(1,1);
        d3 = numero.substr(2,1);
        d4 = numero.substr(3,1);
        d5 = numero.substr(4,1);
        d6 = numero.substr(5,1);
        d7 = numero.substr(6,1);
        d8 = numero.substr(7,1);
        d9 = numero.substr(8,1);
        d10 = numero.substr(9,1);

        /* El tercer digito es: */
        /* 9 para sociedades privadas y extranjeros */
        /* 6 para sociedades publicas */
        /* menor que 6 (0,1,2,3,4,5) para personas naturales */

        if (d3==7 || d3==8){
            document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El tercer d\u00edgito ingresado es inv\u00e1lido</label>";
            //alert('El tercer d\u00edgito ingresado es inv\u00e1lido');
            campo.focus();
            return false;
        }

        /* Solo para personas naturales (modulo 10) */
        if (d3 < 6){
            nat = true;
            p1 = d1 * 2;if (p1 >= 10) p1 -= 9;
            p2 = d2 * 1;if (p2 >= 10) p2 -= 9;
            p3 = d3 * 2;if (p3 >= 10) p3 -= 9;
            p4 = d4 * 1;if (p4 >= 10) p4 -= 9;
            p5 = d5 * 2;if (p5 >= 10) p5 -= 9;
            p6 = d6 * 1;if (p6 >= 10) p6 -= 9;
            p7 = d7 * 2;if (p7 >= 10) p7 -= 9;
            p8 = d8 * 1;if (p8 >= 10) p8 -= 9;
            p9 = d9 * 2;if (p9 >= 10) p9 -= 9;
            modulo = 10;
        }

        /* Solo para sociedades publicas (modulo 11) */
        /* Aqui el digito verficador esta en la posicion 9, en las otras 2 en la pos. 10 */
        else if(d3 == 6){
            pub = true;
            p1 = d1 * 3;
            p2 = d2 * 2;
            p3 = d3 * 7;
            p4 = d4 * 6;
            p5 = d5 * 5;
            p6 = d6 * 4;
            p7 = d7 * 3;
            p8 = d8 * 2;
            p9 = 0;
        }

        /* Solo para entidades privadas (modulo 11) */
        else if(d3 == 9) {
            pri = true;
            p1 = d1 * 4;
            p2 = d2 * 3;
            p3 = d3 * 2;
            p4 = d4 * 7;
            p5 = d5 * 6;
            p6 = d6 * 5;
            p7 = d7 * 4;
            p8 = d8 * 3;
            p9 = d9 * 2;
        }

        suma = p1 + p2 + p3 + p4 + p5 + p6 + p7 + p8 + p9;
        residuo = suma % modulo;

        /* Si residuo=0, dig.ver.=0, caso contrario 10 - residuo*/
        digitoVerificador = residuo==0 ? 0: modulo - residuo;

        /* ahora comparamos el elemento de la posicion 10 con el dig. ver.*/
        if (pub==true){
            if (digitoVerificador != d9){
                //alert('El ruc de la empresa del sector p\u00fablico es incorrecto.');
                document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El ruc de la empresa del sector p\u00fablico es incorrecto.</label>";
                campo.focus();
                return false;
            }
            /* El ruc de las empresas del sector publico terminan con 0001*/
            if ( numero.substr(9,4) != '0001' ){
                //alert('El ruc de la empresa del sector p\u00fablico debe terminar con 0001');
                document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El ruc de la empresa del sector p\u00fablico debe terminar con 0001.</label>";
                campo.focus();
                return false;
            }
        }
        else if(pri == true){
            if (digitoVerificador != d10){
                //alert('El ruc de la empresa del sector privado es incorrecto.');
                document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El ruc de la empresa del sector privado es incorrecto.</label>";
                campo.focus();
                return false;
            }
            if ( numero.substr(10,3) != '001' ){
                //alert('El ruc de la empresa del sector privado debe terminar con 001');
                document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El ruc de la empresa del sector privado debe terminar con 001.</label>";
                campo.focus();
                return false;
            }
        }

        else if(nat == true){
            if (digitoVerificador != d10){
                //alert("El n\u00famero de c\u00e9dula de la persona natural es incorrecto.");
                document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El n\u00famero de c\u00e9dula de la persona natural es incorrecto.</label>";
                campo.focus();
                return false;
            }
            let tipo = $("input[type=radio][name=rbCaracterIdentificacion]:checked").val();
            if (numero.length >10 && numero.substr(10,3) != '001' && tipo =='04'){
                document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El ruc de la persona natural debe terminar con 001.</label>";
                //alert('El ruc de la persona natural debe terminar con 001');
                campo.focus();
                return false;
            }else{
                document.getElementById("mensageCedula2").innerHTML="";
            }
        }
        document.getElementById("mensageCedula2").innerHTML='';
    return true;

    }else{
        let tipo = $("input[type=radio][name=rbCaracterIdentificacion]:checked").val();
        if(tipo=='04'){
            document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El numero de RUC debe tener 13 digitos</label>";
        }
        if(tipo=='05'){
            document.getElementById("mensageCedula2").innerHTML="<label style='color: #FF0000'>El numero de cédula debe tener 10 digitos</label>";
        }
   
        
    }
    document.getElementById("mensageCedula2").innerHTML='';
return true;

    
}