// JavaScript Document

			
function fn_cantidad(){
    cantidad = $("#grilla tbody").find("tr").length;
    $("#span_cantidad").html(cantidad);
    document.getElementById('txtContadorFilas').value = cantidad;
    
}


function calcular_debe(){
    var suma =0;
    for(i=1;i<contador;i++){
        de = $("#txtDebe"+i).val();
        if(de == ""){
            de=0;
        }
        suma = suma + parseFloat(de);
    debe=document.getElementById('txtDebeTotal').value=(suma).toFixed(2);
    }
}

function calcular_haber(){
    var suma =0;
    for(i=1;i<contador;i++){
        de = $("#txtHaber"+i).val();
        if(de == ""){
            de=0;
        }
        suma = suma + parseFloat(de);
    debe=document.getElementById('txtHaberTotal').value=(suma).toFixed(2);
    }
}



cont=1;
contador =1;
//contador2 = 2;
function fn_agregar(valor){
    posicion = valor;// esta valor es para empezar desde cero lo envia desde la pag: js/busquedas.js la funcion lookup15()  onBlur='fill2();'  onchange='habilitar("+contador+");' onchange='limpiar_id('txtCodigo"+contador+"','txtCuenta2"+contador+"','txtIdCuenta"+contador+"','txtIdDetalleLibroDiario"+contador+"')'
    contador2 = contador;
    contador2++;
    cadena = "<tr id='fila1' style='margin-bottom:10px'>";
    cadena = cadena + "<td style='width:15%'><input type='hidden' readonly='readonly' id='txtIdDetalleLibroDiario"+contador+"' name='txtIdDetalleLibroDiario"+contador+"' value='0' class='form-control'/>    <input type='hidden' readonly='readonly' id='txtIdCuenta"+contador+"' name='txtIdCuenta"+contador+"' value='0' class='form-control'/>      <input type='search' id='txtCodigo"+contador+"' name='txtCodigo"+contador+"' value='' class='form-control' onclick='lookup2(this.value,"+contador+", 5);' onKeyUp='lookup2(this.value,"+contador+", 5);'  autocomplete='off'  placeholder='Buscar por Codigo o Nombre'  onKeyDown='saltar(event , this.form.txtCuenta2"+contador+")'   />       <div class='suggestionsBox' id='suggestions"+contador+"' style='display: none; margin-top: 0px; '> <div class='suggestionList' id='autoSuggestionsList"+contador+"'> &nbsp; </div> </div>    </td>";
    cadena = cadena + "<td style='width:45%'><input type='search' id='txtCuenta2"+contador+"' name='txtCuenta2"+contador+"' value=''  style='margin: 0px; width: 100%;' class='form-control' autocomplete='off'   onKeyDown='saltar(event ,this.form.txtDebe"+contador+")'  /> <input type='hidden' id='txtCuentaBanco"+contador+"' name='txtCuentaBanco"+contador+"' value=''  class='form-control' /> ";
    cadena = cadena + "<td style='width:15%'><input style='text-align: right; margin: 0px; width: 100%;' type='text' id='txtDebe"+contador+"' name='txtDebe"+contador+"' value='0' class='form-control' onKeyUp='calcular_debe();' onclick='calcular_debe();' onKeyPress='return precio(event)' autocomplete='off' onKeyDown='saltar(event,this.form.txtHaber"+contador+");'  /></td>";
    cadena = cadena + "<td style='width:15%'><input style='text-align: right; margin: 0px; width: 100%;' type='text' id='txtHaber"+contador+"' name='txtHaber"+contador+"' value='0' class='form-control' onKeyUp='calcular_haber();' onclick='calcular_haber();' onKeyPress='return precio(event)' autocomplete='off' onKeyDown='saltar(event,this.form.txtCodigo"+contador2+");'  /></td>";
    cadena = cadena + "<td style='width:10%'><a onclick='limpiaFila("+contador+");' title='Limpiar fila'> <button type='button' class='btn btn-default' aria-label='Left Align'><span class='fa fa-eraser'></span></button></a></td>";
    cadena = cadena + "</tr>";

    document.getElementById('txtContadorFilas').value=contador;
    contador++;    
    
    
    $("#grilla tbody").append(cadena);    
    //fn_dar_eliminar();
    fn_cantidad();


}

function limpiaFila(num){  //limpia la fila de la cuenta agregada
    
    if($('#txtIdCuenta'+num).val() >= 1){
         document.getElementById('txtIdCuenta'+num).value = "0";
        document.getElementById('txtCuenta2'+num).value = "";
        document.getElementById('txtCodigo'+num).value = "";
        document.getElementById('txtDebe'+num).value = "0";
        document.getElementById('txtHaber'+num).value = "0";
        document.getElementById('txtCuentaBanco'+num).value = "";
        asientosQuitados(); // esta funcion debe ir primera
        calcular_debe();
        calcular_haber();
    }else{
       
    }
    
}



function asientosQuitados(){// funcion para restar al contador de asientos agregados
    
        asientosAgregados = $('#txtContadorAsientosAgregados').val();
        if(asientosAgregados >= 1){
            asientosAgregados --;
            $('#txtContadorAsientosAgregados').val(asientosAgregados);
        }
        
    
}

function asientosAgregados(){// funcion para saber cuantas cuentas estan agregadas
    //alert($('#txtContadorAsientosAgregados').val());
    sumaAsientosAgregados =  $('#txtContadorAsientosAgregados').val();
    sumaAsientosAgregados ++;
    $('#txtContadorAsientosAgregados').val(sumaAsientosAgregados);
}
            
function fn_dar_eliminar(){
    /*
    $("a.elimina"). click(function(){       
        respuesta = confirm("Seguro desea eliminar la fila seleccionada");
        if (respuesta){
            $(this).parents("tr").fadeOut("normal", function(){
                $(this).remove();
                contador --;
                 fn_cantidad();
                 calcular_debe();
                 calcular_haber();

            })
        }        
    });
    */
}

function eliminaFilas() {
    $("#fila1").remove();
}

//-----------------------funciones para la pagina: modificarTransaccion.php
//contador2 = document.getElementById('txtContadorFilas2').value;
//toop2 = parseInt(document.getElementById('txtToop2').value);
function fn_cantidad2(){
    cantidad2 = $("#tblTransaccion tbody").find("tr").length;
    $("#span_cantidad2").html(cantidad2);
    document.getElementById('txtContadorFilas2').value = cantidad2;
}

function calcular_debe2(){ 
contador2 = document.getElementById('txtContadorFilas2').value;
    var suma =0;
    for(i=1;i<=contador2;i++){
        //alert(i);
        de = $("#txtDebe"+i).val();
        if(de == ""){
            de=0;
        }else{
        suma = suma + parseFloat(de);}
    document.getElementById('txtDebeTotal2').value=(suma).toFixed(2);
    }
}

function calcular_haber2(){
contador2 = document.getElementById('txtContadorFilas2').value;
    var suma =0;
    for(i=1;i<=contador2;i++){
         //alert(i);
        de = $("#txtHaber"+i).val();
        if(de == ""){
            de=0;
        }else{
        suma = suma + parseFloat(de);}
    document.getElementById('txtHaberTotal2').value=(suma).toFixed(2);
    }
}

function fn_agregar2(){    
       
    contador2++;    
    cadena = "<tr>";
    cadena = cadena + "<td><input type='text' id='txtCodigo"+contador2+"' name='txtCodigo"+contador2+"' value='0' class='text_input3' readonly='true'/></td>";
    cadena = cadena + "<td><input type='hidden' id='txtIdCuenta"+contador2+"' name='txtIdCuenta"+contador2+"' value='' class='text_input3'/> <input type='search' required='required' id='txtCuenta2"+contador2+"' name='txtCuenta2"+contador2+"' value='' class='text_input5 required' onclick='lookup2(this.value,"+contador2+", 5);' onKeyUp='lookup2(this.value,"+contador2+", 5);' onBlur='fill2();' autocomplete='off' onchange='habilitar("+contador2+");'/><div class='suggestionsBox' id='suggestions1' style='display: none; top: "+toop2+"px; left: 50px; width:250px'> <img src='images/upArrow.png' style='position: relative; top: -12px; left: 50px;' alt='upArrow' /> <div class='suggestionList' id='autoSuggestionsList1'> &nbsp; </div> </div> </td>";
    cadena = cadena + "<td><input type='text' id='txtDebe"+contador2+"' name='txtDebe"+contador2+"' value='0' class='text_input3' onKeyUp='calcular_debe2();' onclick='calcular_debe2();' onKeyPress='return precio(event)' autocomplete='off'/></td>";
    cadena = cadena + "<td><input type='text' id='txtHaber"+contador2+"' name='txtHaber"+contador2+"' value='0' class='text_input3' onKeyUp='calcular_haber2();' onclick='calcular_haber2();' onKeyPress='return precio(event)' autocomplete='off'/></td>";
    //cadena = cadena + "<td><input type='text' id='txtDescripcion"+contador+"' name='txtDescripcion"+contador+"' value='' class='text_input2'/></td>";
    cadena = cadena + "<td><a class='elimina2' title='Eliminar'><img src='images/delete.png' /></a></td>";
    document.getElementById('txtContadorFilas2').value=contador2;    
    toop2=parseInt(toop2+20);
    $("#tblTransaccion tbody").append(cadena);
    /*
        aqui puedes enviar un conunto de tados ajax para agregar al usuairo
        $.post("agregar.php", {ide_usu: $("#valor_ide").val(), nom_usu: $("#valor_uno").val()});
    */
    fn_dar_eliminar2();
    fn_cantidad2();
//              fn_debe_haber();
//              alert("Usuario agregado");

}

function fn_dar_eliminar2(){
    $("a.elimina2").click(function(){
//                    id = $(this).parents("tr").find("td").eq(0).html();
//                    id = $(this).parents("tr").find("td").eq("input").html();
        respuesta = confirm("Seguro desea eliminar la fila seleccionada");
        if (respuesta){
            $(this).parents("tr").fadeOut("normal", function(){
                $(this).remove();
                contador --;
                 fn_cantidad2();
                 calcular_debe2();
                 calcular_haber2();
//                            alert("Usuario " + id + " eliminado")
                /*
                    aqui puedes enviar un conjunto de datos por ajax
                    $.post("eliminar.php", {ide_usu: id})
                */
            })
        }
    });

}

//*************** PAGINA: ingresosGravables.php   *******************
nFilas = 0;
function agregarIngresosGravables(){
    numFilas = document.getElementById('txtNumeroFila').value;
    nFilas = numFilas;
    nFilas++;
    document.getElementById("mensaje1").innerHTML="";
    cadena = "<tr>";
    cadena = cadena + "<td><input type='text' id='IdIngresoGravables"+nFilas+"' name='IdIngresoGravables"+nFilas+"' value='' class='text_input6' disabled /> </td>";
    cadena = cadena + "<td><input type='text' id='desde"+nFilas+"' name='desde"+nFilas+"' value='' class='text_input6'  onKeyPress='return precio(event)' maxlength='10'/></td>";
    cadena = cadena + "<td><input type='text' id='hasta"+nFilas+"' name='hasta"+nFilas+"' value='' class='text_input6'  onKeyPress='return precio(event)' maxlength='10'/></td>";
    cadena = cadena + "<td><input type='text' id='imp_fd"+nFilas+"' name='imp_fd"+nFilas+"' value='' class='text_input6' onKeyPress='return precio(event)' maxlength='10'/></td>";
    cadena = cadena + "<td><input type='text' id='imp_exd"+nFilas+"' name='imp_exd"+nFilas+"' value='' class='text_input6' onKeyPress='return precio(event)' maxlength='10'/></td>";
    cadena = cadena + "<td><a title='Guardar' href='javascript: guardar_ingresos_gravables("+nFilas+");'><img src='images/save16.png' /></a></td>";
    cadena = cadena + "<td><a class='elimina3' title='Eliminar'><img src='images/delete.png' /></a></td>";    
    $("#grilla1 tbody").append(cadena);
    cantidad_filas();    
    eliminarIngGra();

}
function cantidad_filas(){
    cantidad = $("#grilla1 tbody").find("tr").length;
    $("#span_cantidad1").html(cantidad);
    document.getElementById('txtNumeroFila').value = cantidad;
}

function eliminarIngGra(){
    $("a.elimina3").click(function(){
        respuesta = confirm("Seguro desea eliminar la fila seleccionada");
        if (respuesta){
            $(this).parents("tr").fadeOut("normal", function(){
                $(this).remove();
                nFilas --;
                 cantidad_filas();
            })
        }
    });

}


//*************** PAGINA: subAntiguedad.php   *******************

nFilasSubAnt = 0;
function agregarSubAntiguedad(){
    numFilas = document.getElementById('txtNumeroFila').value;
    nFilasSubAnt = numFilas;
    nFilasSubAnt++;
    document.getElementById("mensaje1").innerHTML="";
    cadena = "<tr>";
    cadena = cadena + "<td><input type='text' id='IdSubAnti"+nFilasSubAnt+"' name='IdSubAnti"+nFilasSubAnt+"' value='' class='text_input6' disabled /> </td>";
    cadena = cadena + "<td><input type='text' id='desde"+nFilasSubAnt+"' name='desde"+nFilasSubAnt+"' value='' class='text_input6'  onKeyPress='return precio(event)' maxlength='10'/></td>";
    cadena = cadena + "<td><input type='text' id='hasta"+nFilasSubAnt+"' name='hasta"+nFilasSubAnt+"' value='' class='text_input6'  onKeyPress='return precio(event)' maxlength='10'/></td>";
    cadena = cadena + "<td><input type='text' id='porcentaje"+nFilasSubAnt+"' name='porcentaje"+nFilasSubAnt+"' value='' class='text_input6' onKeyPress='return precio(event)' maxlength='10'/></td>";    
    cadena = cadena + "<td><a title='Guardar' href='javascript: guardar_subsidio_antiguedad("+nFilasSubAnt+");'><img src='images/save16.png' /></a></td>";
    cadena = cadena + "<td><a class='eliminaSubAntiguedad' title='Eliminar'><img src='images/delete.png' /></a></td>";
    $("#grilla2 tbody").append(cadena);
     cantidad_filas_SubAntiguedad();
    eliminarSubAntiguedad();
}

function cantidad_filas_SubAntiguedad(){
    cantidad = $("#grilla2 tbody").find("tr").length;
    $("#span_cantidad2").html(cantidad);
    document.getElementById('txtNumeroFila').value = cantidad;
}

function eliminarSubAntiguedad(){
    $("a.eliminaSubAntiguedad").click(function(){
        respuesta = confirm("Seguro desea eliminar la fila seleccionada");
        if (respuesta){
            $(this).parents("tr").fadeOut("normal", function(){
                $(this).remove();
                nFilasSubAnt --;
                 cantidad_filas_SubAntiguedad();
            })
        }
    });

}


//*************** PAGINA: subFamiliar.php   *******************

nFilasSubFam = 0;
function agregarSubFami(){
    numFilas = document.getElementById('txtNumeroFila').value;
    nFilasSubFam = numFilas;
    nFilasSubFam++;
    document.getElementById("mensaje1").innerHTML="";
    cadena = "<tr>";
    cadena = cadena + "<td><input type='text' id='IdSubFami"+nFilasSubFam+"' name='IdSubFami"+nFilasSubFam+"' value='' class='text_input6' disabled /> </td>";
    cadena = cadena + "<td><input type='text' id='desde"+nFilasSubFam+"' name='desde"+nFilasSubFam+"' value='' class='text_input6'  onKeyPress='return precio(event)' maxlength='10'/></td>";
    cadena = cadena + "<td><input type='text' id='hasta"+nFilasSubFam+"' name='hasta"+nFilasSubFam+"' value='' class='text_input6'  onKeyPress='return precio(event)' maxlength='10'/></td>";
    cadena = cadena + "<td><input type='text' id='porcentaje"+nFilasSubFam+"' name='porcentaje"+nFilasSubFam+"' value='' class='text_input6' onKeyPress='return precio(event)' maxlength='10'/></td>";
    cadena = cadena + "<td><a title='Guardar' href='javascript: guardar_subsidio_familiar("+nFilasSubFam+");'><img src='images/save16.png' /></a></td>";
    cadena = cadena + "<td><a class='eliminaSubFami' title='Eliminar'><img src='images/delete.png' /></a></td>";
    $("#grillaSF tbody").append(cadena);
    cantidad_filas_SubFami();
    eliminarSubFami();

}
function cantidad_filas_SubFami(){
    cantidad = $("#grillaSF tbody").find("tr").length;
    $("#span_cantidadSF").html(cantidad);
    document.getElementById('txtNumeroFila').value = cantidad;
}

function eliminarSubFami(){
    $("a.eliminaSubFami").click(function(){
        respuesta = confirm("Seguro desea eliminar la fila seleccionada");
        if (respuesta){
            $(this).parents("tr").fadeOut("normal", function(){
                $(this).remove();
                nFilasSubFam --;
                 cantidad_filas_SubFami();
            })
        }
    });

}


//*************** PAGINA: parametrosAtrasos.php   *******************

nFilasAtrasos = 0;
function agregarAtrasos(){
    numFilas1 = document.getElementById('txtNumeroFila').value;
    nFilasAtrasos = numFilas1;
    nFilasAtrasos++;
    document.getElementById("mensaje1").innerHTML="";
    cadena = "<tr>";
    cadena = cadena + "<td><input type='text' id='txtIdAtrasos"+nFilasAtrasos+"' name='txtIdAtrasos"+nFilasAtrasos+"' class='text_input6' disabled /> </td>";
    cadena = cadena + "<td><input type='text' id='txtDesde"+nFilasAtrasos+"' name='txtDesde"+nFilasAtrasos+"' class='text_input6' onkeyup='mascara(this,':',patron,true)' maxlength='5' placeholder='00:00' autocomplete='off'/></td>";
    cadena = cadena + "<td><input type='text' id='txtHasta"+nFilasAtrasos+"' name='txtHasta"+nFilasAtrasos+"' class='text_input6' onkeyup='mascara(this,':',patron,true)' maxlength='5' placeholder='00:00' autocomplete='off'/></td>";
    cadena = cadena + "<td><input type='text' id='txtPorcentaje"+nFilasAtrasos+"' name='txtPorcentaje"+nFilasAtrasos+"' class='text_input6'  onKeyPress='return precio(event)' maxlength='5'/></td>";
    cadena = cadena + "<td><a title='Guardar' href='javascript: guardar_atrasos("+nFilasAtrasos+");'><img src='images/save16.png' /></a></td>";
    cadena = cadena + "<td><a class='eliminaAtrasos' title='Eliminar'><img src='images/delete.png' /></a></td>";
    $("#grillaAt tbody").append(cadena);
    cantidad_filas_atrasos();
    eliminarAtrasos();

}
function cantidad_filas_atrasos(){   
    cantidad = $("#grillaAt tbody").find("tr").length;
    $("#span_cantidadAtrasos").html(cantidad);
    document.getElementById('txtNumeroFila').value = cantidad;
}

function eliminarAtrasos(){
    $("a.eliminaAtrasos").click(function(){
        respuesta = confirm("Seguro desea eliminar la fila seleccionada");
        if (respuesta){
            $(this).parents("tr").fadeOut("normal", function(){
                $(this).remove();
                nFilasAtrasos --;
                cantidad_filas_atrasos();
            })
        }
    });

}

//*************** PAGINA: parametrosComisiones.php   *******************

nFilasComisiones = 0;
function agregarParametrosComisiones(){    
    numFilas1 = document.getElementById('txtNumeroFila').value;
    nFilasComisiones = numFilas1;
    nFilasComisiones++;
    document.getElementById("mensaje1").innerHTML="";
    cadena = "<tr>";
    cadena = cadena + "<td><input type='text' id='txtIdComisiones"+nFilasComisiones+"' name='txtIdComisiones"+nFilasComisiones+"' class='text_input6' disabled /> </td>";
    cadena = cadena + "<td><input type='text' id='txtDesde"+nFilasComisiones+"' name='txtDesde"+nFilasComisiones+"' class='text_input6' onKeyPress='return precio(event)' maxlength='20' placeholder='0.00' autocomplete='off'/></td>";
    cadena = cadena + "<td><input type='text' id='txtHasta"+nFilasComisiones+"' name='txtHasta"+nFilasComisiones+"' class='text_input6' onKeyPress='return precio(event)' maxlength='20' placeholder='0.00' autocomplete='off'/></td>";
    cadena = cadena + "<td><input type='text' id='txtPorcentaje"+nFilasComisiones+"' name='txtPorcentaje"+nFilasComisiones+"' class='text_input6'  onKeyPress='return precio(event)' maxlength='20' placeholder='0.00' autocomplete='off'/></td>";
    cadena = cadena + "<td><a title='Guardar' href='javascript: guardar_parametros_comisiones("+nFilasComisiones+");'><img src='images/save16.png' /></a></td>";
    cadena = cadena + "<td><a class='eliminaPComisiones' title='Eliminar'><img src='images/delete.png' /></a></td>";
    //cadena = "</tr>";
    $("#grillaPC tbody").append(cadena);
    cantidad_filas_Pcomisiones();
    eliminarPComisiones();
}

function cantidad_filas_Pcomisiones(){
    cantidad = $("#grillaPC tbody").find("tr").length;
    $("#span_cantidadPComisiones").html(cantidad);
    document.getElementById('txtNumeroFila').value = cantidad;
}

function eliminarPComisiones(){
    $("a.eliminaPComisiones").click(function(){
        respuesta = confirm("Seguro desea eliminar la fila seleccionada");
        if (respuesta){
            $(this).parents("tr").fadeOut("normal", function(){
                $(this).remove();
                nFilasComisiones --;
                cantidad_filas_Pcomisiones();
            })
        }
    });

}





//*************** DEPRECIACIONES PAGINA: depreciaciones.php   *******************
nFilasDepreciaciones = 0;
function agregarDepreciaciones(){
    numFilas1 = document.getElementById('txtNumeroFila').value;
    nFilasDepreciaciones = numFilas1;
    nFilasDepreciaciones++;
    document.getElementById("mensaje1").innerHTML="";
    cadena = "<tr>";
    cadena = cadena + "<td><input type='text' id='txtIdDepreciacion"+nFilasDepreciaciones+"' name='txtIdDepreciacion"+nFilasDepreciaciones+"' class='text_input6' disabled='true' /> </td>";
    cadena = cadena + "<td><input type='hidden' id='txtIdCuenta"+nFilasDepreciaciones+"' name='txtIdCuenta"+nFilasDepreciaciones+"' title='Ingrese un nombre' class='text_input6' required='required' maxlength='20' placeholder='Nombre de categoria' autocomplete='off'/>";
    cadena = cadena + "<input type='text' id='txtCodigo"+nFilasDepreciaciones+"' name='txtCodigo"+nFilasDepreciaciones+"' title='Ingrese un nombre' class='text_input6' required='required' maxlength='20' placeholder='Codigo' autocomplete='off' onclick='lookup2(this.value,"+nFilasDepreciaciones+", 5);' onKeyUp='lookup2(this.value,"+nFilasDepreciaciones+", 5);' onBlur='fill2();'/></td>";
    cadena = cadena + "<td><input type='search' id='txtCuenta2"+nFilasDepreciaciones+"' name='txtCuenta2"+nFilasDepreciaciones+"' title='Ingrese un nombre' class='text_input2' required='required' maxlength='20' placeholder='Buscar Cuenta' onclick='lookup2(this.value,"+nFilasDepreciaciones+", 5);' onKeyUp='lookup2(this.value,"+nFilasDepreciaciones+", 5);' onBlur='fill2();' autocomplete='off' onchange='limpiar_id('txtIdCuenta"+nFilasDepreciaciones+"', 'txtCodigo"+nFilasDepreciaciones+"');' /> <div class='suggestionsBox' id='suggestions1' style='display: none; top: 228px; left: 50px; width:250px'><img src='images/upArrow.png' style='position: relative; top: -12px; left: 50px;' alt='upArrow' /><div class='suggestionList' id='autoSuggestionsList1'> &nbsp; </div> </div> </td> ";
    cadena = cadena + "<td><input type='text' id='txtVidaUtil"+nFilasDepreciaciones+"' name='txtVidaUtil"+nFilasDepreciaciones+"' title='Ingrese un nombre' class='text_input6' required='required' maxlength='20' placeholder='Vida Util' autocomplete='off' onKeyPress='return precio(event)' /></td>";
    cadena = cadena + "<td><input type='text' id='txtPorcentajeDepre"+nFilasDepreciaciones+"' name='txtPorcentajeDepre"+nFilasDepreciaciones+"' title='Ingrese un nombre' class='text_input6' required='required' maxlength='20' placeholder='Porcentaje depreciacion' autocomplete='off' onKeyPress='return precio(event)'/> <input type='hidden' id='txtDebe"+nFilasDepreciaciones+"' name='txtDebe"+nFilasDepreciaciones+"' value='' class='text_input6' /><input type='hidden' id='txtHaber"+nFilasDepreciaciones+"' name='txtHaber"+nFilasDepreciaciones+"' value='' class='text_input6' /></td>";
    cadena = cadena + "<td><a title='Guardar' href='javascript: guardar_depreciaciones("+nFilasDepreciaciones+", 5);'><img src='images/save16.png' /></a></td>";
    cadena = cadena + "<td><a class='eliminaDepreciaciones' title='Eliminar'><img src='images/delete.png' /></a></td>";
    //cadena = "</tr>";
    $("#grillaD tbody").append(cadena);
    cantidad_filas_Depreciaciones();
    eliminarDepreciaciones();
}

function cantidad_filas_Depreciaciones(){
        cantidad = $("#grillaD tbody").find("tr").length;
        $("#span_cantidadDepre").html(cantidad);
        document.getElementById('txtNumeroFila').value = cantidad;
}

function eliminarDepreciaciones(){
    $("a.eliminaDepreciaciones").click(function(){
        respuesta = confirm("Seguro desea eliminar la fila seleccionada");
        if (respuesta){
            $(this).parents("tr").fadeOut("normal", function(){
                $(this).remove();
                nFilasDepreciaciones --;
                 cantidad_filas_Depreciaciones();
            })
        }
    });
}

//*************** PORCENTAJES RETENCIONES PAGINA:ajax/listarPorcentajesRetencion.php  *******************

nFilasPorcentajesRetencion = 0;
function agregarPorcentajesRetencion(){
    numFilas1 = document.getElementById('txtNumeroFila').value;
    nFilasPorcentajesRetencion = numFilas1;
    nFilasPorcentajesRetencion++;
    document.getElementById("mensaje1").innerHTML="";
    cadena = "<tr>";
    cadena = cadena + "<td><input type='text' id='txtIdPorcentajeRetencion"+nFilasPorcentajesRetencion+"' name='txtIdPorcentajeRetencion"+nFilasPorcentajesRetencion+"' class='text_input6' disabled /> </td>";
    cadena = cadena + "<td><input type='text' id='txtPorcentaje"+nFilasPorcentajesRetencion+"' name='txtPorcentaje"+nFilasPorcentajesRetencion+"' class='text_input6' onKeyPress='return precio(event)' maxlength='20' placeholder='0.00' autocomplete='off'/>%</td>";

    cadena = cadena + "<td><a title='Guardar' href='javascript: guardarPorcentajesRetencion("+nFilasPorcentajesRetencion+");'><img src='images/save16.png' /></a></td>";
    cadena = cadena + "<td><a class='elimina_porcentajes_retencion' title='Eliminar'><img src='images/delete.png' /></a></td>";
    //cadena = "</tr>";
    $("#tblPorcentajesRetencion tbody").append(cadena);
    cantidadFilasPorcentajesRetencion();
    eliminar_Porcentajes_Retencion();
}

function cantidadFilasPorcentajesRetencion(){
    cantidad = $("#tblPorcentajesRetencion tbody").find("tr").length;
    $("#span_cantidad_porcentajes_retencion").html(cantidad);
    document.getElementById('txtNumeroFila').value = cantidad;
}

function eliminar_Porcentajes_Retencion(){
    $("a.elimina_porcentajes_retencion").click(function(){
        respuesta = confirm("Seguro desea eliminar la fila seleccionada");
        if (respuesta){
            $(this).parents("tr").fadeOut("normal", function(){
                $(this).remove();
                nFilasPorcentajesRetencion --;
                cantidadFilasPorcentajesRetencion();
            })
        }
    });

}

function reversarAsiento(){
    
}


