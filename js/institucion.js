
// funciones para filtrar las instituciones educativas pagina registro.php
function comboInstitucion(aux){
    codigo= document.form.cbciudad.value;
    ajax44=objetoAjax();
    ajax44.open("POST", "sql/instituciones.php",true);
    ajax44.onreadystatechange=function() {
        if (ajax44.readyState==4) {
            var respuesta44=ajax44.responseText;
            asignaInstitucion(respuesta44);
        }
    }
    ajax44.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax44.send("accion="+aux+"&codigo="+codigo)
}

function asignaInstitucion(cadena){
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;
    document.form.opcion3.value="0";
    limpiaInstitucion();
    document.form.cmbInstitucion.options[0] = new Option("Todas","");
    for(i=1;i<limite;i=i+2){
        document.form.cmbInstitucion.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
}

function limpiaInstitucion()
{
    for (m=document.form.cmbInstitucion.options.length-1;m>=0;m--){
        document.form.cmbInstitucion.options[m]=null;
    }
}

function comboTipoInstitucion(aux){
    ajax5=objetoAjax();
//	alert("aaaassss"+aux);	
//	alert("va a sql");
    ajax5.open("POST","sql/instituciones.php",true);
    ajax5.onreadystatechange=function(){         
        if (ajax5.readyState==4){
            var respuesta=ajax5.responseText;            
            asignaTipoInstitucion(respuesta);
        }
    }
    ajax5.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax5.send("accion="+aux)
}

function asignaTipoInstitucion(cadena){  
    array = cadena.split( "?" );
	//alert("arreglo");
	//alert(array);
    limite=array.length;
    cont=1;
    limpiaTipoInstitucion();
    document.form.cmbTipoInstitucion.options[0] = new Option("Seleccione Tipo de Institucion","0");
    for(i=1;i<limite;i=i+2){
        document.form.cmbTipoInstitucion.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
    //document.getElementById("cbpais").selectedIndex = "3";
    document.form.cmbTipoInstitucion.selectedIndex = '1'; // seleccion para ecuador
}

function limpiaTipoInstitucion()
{  
    for (m=document.form.cmbTipoInstitucion.options.length-1;m>=0;m--){        
        document.form.cmbTipoInstitucion.options[m]=null;
    }
}

function nueva_institucion(){
 //PAGINA: direcciones.php
    $("#div_oculto").load("ajax/nuevaInstitucion.php", function()
	{
        $.blockUI
		({
            message: $('#div_oculto'),
            overlayCSS: {backgroundColor: '#111'},
                    css:{
                            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',

                            background: '#FFFFFF',
                            top: '20px',
                            left: '10%',
                            position: 'absolute',
                            width: '410px'
                    }
        });
    });
}

function nuevo_tipo_institucion(){
 //PAGINA: direcciones.php
    $("#div_oculto").load("ajax/nuevoTipoInstitucion.php", function(){
            $.blockUI({
                    message: $('#div_oculto'),
            overlayCSS: {backgroundColor: '#111'},
                    css:{
                            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',

                            background: '#FFFFFF',
                            top: '20px',
                            left: '10%',
                            position: 'absolute',
                            width: '410px'
                    }
            });
    });
}

function nuevo_nivel_institucion(){
    //PAGINA: direcciones.php
    $("#div_oculto").load("ajax/nuevosNivelesInstituciones.php", function(){
            $.blockUI({
                    message: $('#div_oculto'),
            overlayCSS: {backgroundColor: '#111'},
                    css:{
                            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',

                            background: '#FFFFFF', /*  #FFFFFF */
                            top: '5%',
                            width: '350px',
                            position: 'absolute',
                            left: ($(window).width() - $('.caja').outerWidth())/2
                            
                    }
            });
    });
}


function guardar_institucion(accion){
 //PAGINA: direcciones.php
 //alert(accion);
    var nombre = "";
    //var valor = "";
    //valor = document.form['txtCiudad'].value;
    //nombre = no_repetir_ciudad(valor, '9');//retorna 1 o 0 // la inactive porque hay ciudades que tienen el mismo nombre en diferente provincia
    nombre = 0;
    if(nombre === 0)
	{
        document.getElementById('mensaje11').innerHTML="";
            var str = $("#form").serialize();
            $.ajax({
                    url: 'sql/direcciones.php',
                    data: str+"&txtAccion="+accion,
                    type: 'post',
                    success: function(data){
                        //$("#mensaje11").remove();
                        
                        document.getElementById('noRepetirInstitucion').innerHTML="";
                        document.getElementById('mensaje11').innerHTML=""+data;
                        //document.getElementById("form").reset();
                        document.form.txtInstitucion.value="";
                        listar_direcciones();
                    }
            });
    }else {
            alert ('No se puede guardar porque el nombre "'+document.form['txtInstitucion'].value+'" ya se encuentra registrado.');
            document.form.txtInstitucion.focus();
            document.form.txtInstitucion.value="";
            document.getElementById("noRepetirInstitucion").innerHTML="";
        }
}

function modificar_institucion(id_institucion_educativa){
    //PAGINA: direcciones.php
    
    $("#div_oculto").load("ajax/modificarInstitucion.php", {id_institucion_educativa: id_institucion_educativa}, function(){
            $.blockUI({
                    message: $('#div_oculto'),
                    overlayCSS: {backgroundColor: '#111'},
                    css:{
                            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
                            position: 'fixed',  /* absolute fixed relative */
                            background: '#f9f9f9',
                            top: '20px',
                            left: '185px',
                            width: '350px'
                    }
            });
    });
}

function eliminar_institucion(id_institucion_educativa, accion){
    var respuesta41 = confirm("Seguro desea eliminar este registro?");
    if (respuesta41){
            $.ajax({
                    url: 'sql/direcciones.php',
                    data: 'id_institucion_educativa=' + id_institucion_educativa+'&txtAccion='+accion,
                    type: 'post',
                    success: function(data){
                            if(data!="")
                                    alert(data);
                            listar_direcciones();
                    }
            });
    }
}

function guardarModificarInstitucion(accion){
 //PAGINA: direcciones.php
    var str = $("#form").serialize();
    $.ajax({

            url: 'sql/direcciones.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data){
                document.getElementById('mensaje11').innerHTML=""+data;
                listar_direcciones();

                if(data.length == 90){
                   fn_cerrar();
                }else{

                }
            }
    });

}


function comboNivelInstitucion(aux){
//	alert('opcion nivel de inst');
//	alert(aux);
    codigo= document.form.cmbInstitucion.value;
    ajax44=objetoAjax();
//	alert('va a sql');
    ajax44.open("POST", "sql/instituciones.php",true);
    ajax44.onreadystatechange=function() {
        if (ajax44.readyState==4) {
            var respuesta44=ajax44.responseText;
            asignaNivelInstitucion(respuesta44);
        }
    }
    ajax44.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax44.send("accion="+aux+"&codigo="+codigo)
}


function asignaNivelInstitucion(cadena){
    array = cadena.split( "?" );
//alert(array);   
    limite=array.length;
    cont=1;
    document.form.opcion4.value="0";
    limpiaNivelInstitucion();
    document.form.cmbNivel.options[0] = new Option("Todos","");
    for(i=1;i<limite;i=i+2){
        document.form.cmbNivel.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
}

function asignaNivelInstitucionProfesor(cadena){
    array = cadena.split( "?" );
//alert(array);   
    limite=array.length;
    cont=1;
    document.form.opcion5.value="0";
    limpiaNivelInstitucion();
    document.form.cmbNivelProfesor.options[0] = new Option("Todos","");
    for(i=1;i<limite;i=i+2){
        document.form.cmbNivelProfesor.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
}


function limpiaNivelInstitucion()
{
    for (m=document.form.cmbNivel.options.length-1;m>=0;m--){
        document.form.cmbNivel.options[m]=null;
    }
}

//       guardar_nivel_institucion
function guardar_nivel_institucion(accion)
{
	//alert('guardar niveles'+accion);
    document.getElementById('mensaje11').innerHTML="";
    var str = $("#form").serialize();
//	alert(str);
	$.ajax
	(
		{
            url: 'sql/direcciones.php',
            data: str+"&txtAccion="+accion,
            type: 'POST',
            success: function(data)
			//alert('aaaaaaaaa');
			//alert(data);
			{
            //$("#mensaje11").remove();                       
          //  document.getElementById('noRepetirNivelInstitucion').innerHTML="";
            document.getElementById('mensaje11').innerHTML=""+data;
            //document.getElementById("form").reset();
            document.form.txtNivel.value="";
            //listar_direcciones();
			}
		}
	);
    
}


 