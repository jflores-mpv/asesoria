function listar_impuestos(){
 //PAGINA: impuestos.php
    var str = $("#frmImpuestos").serialize();
    $.ajax({
            url: 'ajax/listarImpuestos.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#div_listar_impuestos").html(data);
                 cantidad_filas_impuestos();
            }
    });
}

function guardar_impuestos(nFila, accion){

    //PAGINA: impuestos.php
    if(document.getElementById("txtPorcentaje"+nFila).value != ""){
        if(document.getElementById("cmbCuentaContableI").value != 0){
            var respuesta49 = confirm("Seguro desea guardar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
            if (respuesta49){
            var str = $("#frmImpuestos").serialize();
                $.ajax({
                        url: 'sql/impuestos.php',
                        type: 'post',
                        data: str+"&numeroFilaSelec="+nFila+"&txtAccion="+accion,
                        success: function(data){
                            //$("#div_listar_RegistroDiario").html(data);
                            document.getElementById("mensajeImpuestos").innerHTML=""+data;
                            listar_impuestos();
                            muestra_iva_actual(4);
                        }
                });
            }

        }else{
            alert("No se puede guardar. Seleccione una cuenta Contable");
            document.getElementById("cmbCuentaContableI").focus();
        }

    }else{
        alert("No se puede guardar. El campo porcentaje esta vacio");
        document.getElementById("txtPorcentaje"+nFila).focus();
    }
    
}

function modificar_impuestos(id, accion, fila){
    //PAGINA: parametrosComisiones.php
    if(document.getElementById("txtPorcentaje"+fila).value != ""){
        if(document.getElementById("cmbCuentaContableI").value != 0){
            var respuesta50 = confirm("Seguro desea modificar este Registro? \nEsta acci\u00f3n afectara a toda la fila seleccionada");
            if (respuesta50){
            var str = $("#frmImpuestos").serialize();
                $.ajax({
                        url: 'sql/impuestos.php',
                        type: 'post',
                        data: str+"&idImpuestos="+id+"&txtAccion="+accion+"&NumeroFilaSeleccionada="+fila,
                        success: function(data){
                            //$("#div_listar_RegistroDiario").html(data);
                            document.getElementById("mensajeImpuestos").innerHTML=""+data;
                            listar_impuestos();
                            muestra_iva_actual(4);
                        }
                });
               }
       }else{
            alert("No se puede guardar. Seleccione una cuenta Contable");
            document.getElementById("cmbCuentaContableI").focus();
        }

    }else{
        alert("No se puede guardar. El campo porcentaje esta vacio");
        document.getElementById("txtPorcentaje"+fila).focus();
    }
}

function eliminar_impuestos(id, accion){
    //PAGINA: parametrosComisiones.php
    var respuesta51 = confirm("Seguro desea eliminar este Registro? \nSi los datos que est\u00e1 intentando borrar tienen ya una relaci\u00f3n, esta acci\u00f3n puede afectar a las facturas compras y ventas y generar fallas en los datos. \nEsta acci\u00f3n eliminara de forma permanente la fila seleccionada");
    if (respuesta51){
        $.ajax({
                url: 'sql/impuestos.php',
                data: 'idIva='+id+'&txtAccion='+accion,
                type: 'post',
                success: function(data){
                    if(data!="")
                    //alert(data);
                    document.getElementById("mensajeImpuestos").innerHTML=""+data;
                    listar_impuestos();
                    muestra_iva_actual(4);
                }
        });
    }
}

nFilasImpuestos = 0;
// function agregarImpuestos(){
//     numFilas1 = document.getElementById('txtNumeroFila').value;
//     nFilasImpuestos = numFilas1;
//     nFilasImpuestos++;
//     document.getElementById("mensajeImpuestos").innerHTML="";
//     cadena = "<tr>";
//     cadena = cadena + "<td><input type='text' id='txtIdIva"+nFilasImpuestos+"' name='txtIdIva"+nFilasImpuestos+"' class='text_input6' disabled /> </td>";
//     cadena = cadena + "<td><input type='text' id='txtPorcentaje"+nFilasImpuestos+"' name='txtPorcentaje"+nFilasImpuestos+"' class='text_input6' onKeyPress='return soloNumeros(event)' maxlength='20' placeholder='0.00' autocomplete='off'/>%</td>";
//     cadena = cadena + "<td><select id='txtEstado"+nFilasImpuestos+"' name='txtEstado"+nFilasImpuestos+"' class='text_input6' style='width: 80px;'><option id='Activo'>Activo</option></select></td>";

//     cadena = cadena + "<td><a title='Guardar' href='javascript: guardar_impuestos("+nFilasImpuestos+", 1);'><img src='images/save16.png' /></a></td>";
//     cadena = cadena + "<td><a class='eliminaImpuestos' title='Eliminar'><img src='images/delete.png' /></a></td>";
//     //cadena = "</tr>";
//     $("#grillaImpuestos tbody").append(cadena);
//     cantidad_filas_impuestos();
//     eliminarImpuestos();
// }
function agregarImpuestos(){
    numFilas1 = document.getElementById('txtNumeroFila').value;
    nFilasImpuestos = numFilas1;
    nFilasImpuestos++;
    document.getElementById("mensajeImpuestos").innerHTML="";
    cadena = "<tr>";
    cadena = cadena + "<td><input type='hidden' id='txtIdIva"+nFilasImpuestos+"' name='txtIdIva"+nFilasImpuestos+"' class='text_input6 form-control' disabled /> ";
    cadena = cadena + "<input type='text' id='txtPorcentaje"+nFilasImpuestos+"' name='txtPorcentaje"+nFilasImpuestos+"' class='text_input6 form-control' onKeyPress='return soloNumeros(event)' maxlength='20' placeholder='0.00' autocomplete='off'/>%</td>";
     cadena = cadena + "<td><input type='text' id='txtCodigo"+nFilasImpuestos+"' name='txtCodigo"+nFilasImpuestos+"' class='text_input6 form-control'  maxlength='10' placeholder='2' autocomplete='off'/></td>";
    cadena = cadena + "<td><select id='txtEstado"+nFilasImpuestos+"' name='txtEstado"+nFilasImpuestos+"' class='text_input6 form-control' style='width: 80px;'><option id='Activo'>Activo</option><option id='Inactivo'>Inactivo</option></select></td>";

    cadena = cadena + "<td><a title='Guardar' href='javascript: guardar_impuestos("+nFilasImpuestos+", 1);' title='Editar' class='btn btn-success'><span class='fa fa-save'></span></a></td>";
    cadena = cadena + "<td><a class='eliminaImpuestos' title='Eliminar'title='Eliminar'><span class='fa fa-trash'></span></a></td>";
    //cadena = "</tr>";
    $("#grillaImpuestos tbody").append(cadena);
    cantidad_filas_impuestos();
    eliminarImpuestos();
}
function cantidad_filas_impuestos(){
    cantidad = $("#grillaImpuestos tbody").find("tr").length;
    $("#span_cantidadImpuestos").html(cantidad);
    document.getElementById('txtNumeroFila').value = cantidad;
}

function eliminarImpuestos(){
    $("a.eliminaImpuestos").click(function(){
        respuesta = confirm("Seguro desea eliminar la fila seleccionada");
        if (respuesta){
            $(this).parents("tr").fadeOut("normal", function(){
                $(this).remove();
                nFilasImpuestos --;
                cantidad_filas_impuestos();
            })
        }
    });

}