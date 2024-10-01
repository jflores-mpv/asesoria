//function tipoPago_nuevo(valor, txtValor, txtEfectivo){
function tipoPago_nuevo(valor, txtValor,txtEfectivo ){

    //alert("tipoPago_nuevo forma2");
    // obtenemos el nombre del combobox
	var indice = document.getElementById(valor).selectedIndex
	var valor1 = document.getElementById(valor).options[indice].value	
	var indice_fp=valor1.substr(0,1);
	
    var combo = document.getElementById(valor);
    var comboNombre = combo.options[combo.selectedIndex].text;
 
    /* Para obtener el idFormaPago */
    var idFormaPago = document.getElementById(valor).value;
    array = idFormaPago.split( "*" );
		  
	var f = new Date();
    var fechaAcual = (f.getFullYear() + "-" + (f.getMonth() +1) + "-" + f.getDate());

	var _formapago = document.getElementById("cmbFormaPagoFP").value;
	var _precio = document.getElementById("txtPagoFP").value;
	var valor_cuotas =parseFloat(_precio);		
	var _blanco="";
		
//		var cadena1="<tr><td>"+combo+"</td><td>"+_formapago+"</td><td>"+_blanco+"</td><td>"+_precio+"</td><td>"+_blanco+"</td></tr>";
		var cadena1="<tr><td width='30'>"+indice_fp+"</td><td width='200'>"+comboNombre+"</td><td width='150'>"+_blanco+"</td><td width='150'>"+valor_cuotas.toFixed(2)+"</td><td>"+_blanco+"</td></tr>";
		$("#tablita").append(cadena1);  
		
	
	if(array[1] !== "Ctas. por Cobrar")
	{
	}
	else
	{
		if(array[1] == "Ctas. por Cobrar")
		{
			cadena = "";
			cadena = cadena + "<tr>";
			cadena = cadena + "<td></td>";
			cadena = cadena + "<td></td>";
			cadena = cadena + "</tr>";
			cadena = cadena + "<tr style='font-size: 30px'>";
			cadena = cadena + "<td><label><strong>Dias Plazo:</strong></label></td>";
			cadena = cadena + "<td><input type='text' id='txtDiasPlazoTP' name='txtDiasPlazoTP' title='Ingrese un numero' style='font-size: 30px;  width: 100%' onKeyPress=\"return soloNumeros(event)\" class='text_input' maxlength='100' placeholder='Numero de dias plazo' autocomplete='off'/></td>";
			cadena = cadena + "</tr>";
			
			cadena = cadena + "<tr style='font-size: 30px'>";
			cadena = cadena + "<td><label><strong>Cuotas:</strong></label></td>";
			cadena = cadena + "<td><input type='text' id='txtCuotasTP' name='txtCuotasTP' title='Ingrese un numero' style='font-size: 30px;  width: 100%' onKeyPress=\"return soloNumeros(event)\" onkeyup=\"listar_cuotasFVC_nuevo()\" onClick=\"listar_cuotasFVC_nuevo()\" class='text_input' maxlength='100' placeholder='Numero de cuotas' autocomplete='off'/></td>";
			cadena = cadena + "</tr>";
			cadena = cadena + "<tr style='font-size: 30px'>";
			cadena = cadena + "<td><label><strong>Fecha Inicio:</strong></label></td>";
			cadena = cadena + "<td><input type='text' id='txtFechaTP' value="+fechaAcual+" name='txtFechaTP' style='font-size: 30px;  width: 100%' onClick=\"displayCalendar(txtFechaTP,'yyyy-mm-dd',this)\" onKeyPress=\"listar_cuotasFVC()\" onchange=\"listar_cuotasFVC()\" title='Ingrese un numero' class='text_input' maxlength='100' placeholder='Numero de dias plazo' autocomplete='off'/></td>";
			cadena = cadena + "</tr>";
			cadena = cadena + "<tr>";
			cadena = cadena + "<td></td>";
        //cadena = cadena + "<td><input name='cancelar' type='button' id='submit' value='Ver Cuotas' onclick=\"listar_cuotasFVC();\" class='button'/></td>";
			cadena = cadena + "<td></td>";
			cadena = cadena + "</tr>";
			$("#tipoPagoCredito").append(cadena);       
		}
		else
		{
        /* limpia las filas */
			for(r=1; r<=4; r++){
			//	document.getElementById("tblTipoPago").deleteRow(4);
			}
		//	document.getElementById("tblListadoCuotas").remove();        
		}
    }   

	vuelto = txtValor.value - txtEfectivo.value ;
	if (vuelto > 0)
	{
    document.getElementById("txtCambioFP").value = vuelto.toFixed(2);    
    document.getElementById("txtDebeFP").value = vuelto.toFixed(2);  
    document.getElementById("txtPagoFP").value = vuelto.toFixed(2);  
    document.getElementById("txtCredito").value = vuelto.toFixed(2);    
		
	}

}

function listar_cuotasFVC_nuevo(){
//alert("ddd");     
    cadena = "<table id='tblListadoCuotas' class='lista' width='100%'>";
    cadena = cadena+"<tbody>";
    
    plazo = document.getElementById("txtCuotasTP").value;  
    dias = document.getElementById("txtDiasPlazoTP").value;  
	
    fecha_inicio = document.getElementById("txtFechaTP").value;    
    empleado = document.getElementById("txtNombreFVC").value;    
    total_credito = document.getElementById("txtDebeFP").value;
    numero_factura = document.getElementById("txtNumeroFacturaFVC").value;
    valor_cuotas = parseFloat(total_credito / plazo);
    //imp_final = parseFloat(document.formnp.txtImFinal.value);
    //valor_cuotas = parseFloat(document.formnp.txtCuotas.value).toFixed(2);
    contador = 0;
    //valor_restante = imp_final;
	fecha=fecha_inicio;
	fecha=new Date();
    day=fecha.getDate();
    // el mes es devuelto entre 0 y 11
    month=fecha.getMonth()+1;
    year=fecha.getFullYear();
	fecha_inicio=day+"-"+month+"-"+year;
	//alert(fecha_inicio);
    for(i=0; i<plazo; i++){
        
        contador++;
        
        //valor_restante = (valor_restante - valor_cuotas).toFixed(2);
        fecha = sumaFechaFVC(i-1,fecha_inicio,dias );
        fecha_inicio=fecha;
        if(contador%2==0){            
            cadena = cadena+"<tr class='odd' id='tr1'>";
            cadena = cadena+"<td width='30'>"+contador+"</td>";
            cadena = cadena+"<td width='200'>"+empleado+"</td>";
            cadena = cadena+"<td width='150'>"+numero_factura+"</td>";
            cadena = cadena+"<td width='150'>"+valor_cuotas.toFixed(2)+"</td>";
            cadena = cadena+"<td>"+fecha+"</td>";
            cadena = cadena+"</tr>";
        }
        if(contador%2==1){            
            cadena = cadena+"<tr class='odd' id='tr2'>";
            cadena = cadena+"<td width='30'>"+contador+"</td>";
            cadena = cadena+"<td width='200'>"+empleado+"</td>";
            cadena = cadena+"<td width='150'>"+numero_factura+"</td>";
            cadena = cadena+"<td width='150'>"+valor_cuotas.toFixed(2)+"</td>";
            cadena = cadena+"<td>"+fecha+"</td>";
            cadena = cadena+"</tr>";
        }
    }    

    cadena = cadena+"</tbody>";
    cadena = cadena+"</table>";
    //alert(cadena);
    $("#div_listar_cuotasFVC").html(cadena);
}

function guardarFacVentaCondominios(accion)
{
	//alert("graba");
	var num_formapago1=document.getElementById("tablita").rows.length;   
	var num_formapago2=document.getElementById("tblListadoCuotas").rows.length;   
	//alert("numero de pagos"+num_formapago2);
	
	var tableReg = document.getElementById('tablita');
			
	var cellsOfRow="";
	var compareWith="";
	cadena="";
	// Recorremos todas las filas con contenido de la tabla
	for (var i = 0; i < tableReg.rows.length; i++)
	{
		cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
		// Recorremos todas las celdas
		linea="";
		for (var j = 0; j < cellsOfRow.length; j++)
		{
			if (j==0 || j==1 || j==3)
			{
				compareWith = cellsOfRow[j].innerHTML.toLowerCase();
				linea=linea+compareWith
				if ((j+1) < cellsOfRow.length)
				{
					linea=linea+",";
				}	
			}	
			// Buscamos el texto en el contenido de la celda
		}
		cadena=cadena+linea+"*"	
		//alert(cadena);
	}
	//alert(cadena);
	
	
    /* Para obtener el idFormaPago */
    var idFormaPago = document.getElementById("cmbFormaPagoFP").value;
    arrayFormaPago = idFormaPago.split( "*" );
    
    var txtDebeFP = document.getElementById("txtDebeFP").value;
    var txtPagoFP = document.getElementById("txtPagoFP").value;
    var txtCambioFP = document.getElementById("txtCambioFP").value;
	var txtCredito=document.getElementById("txtCredito").value;
   // alert(txtCredito);
    
 //   if(arrayFormaPago[1] == "Ctas. por Cobrar"){
    if(num_formapago2 > 0)
	{    
        var txtCuotasTP = document.getElementById("txtCuotasTP").value;
        var txtDiasPlazoTP = document.getElementById("txtDiasPlazoTP").value;
        var txtFechaTP = document.getElementById("txtFechaTP").value;   
    }
 //   alert('forma de pago');
//	alert(idFormaPago);
    if(idFormaPago != 0)
	{
        var str = $("#frmFacturaVentaCondominios").serialize();
        //var str2 = $("#frmFormaPago").serialize();
	//	alert("serializacion");
//alert(str);
        $.ajax
		({
            url: 'sql/facturaVenta_cambios.php',
            type: 'post',
            data: str+"&txtAccion="+accion+"&idFormaPago="+arrayFormaPago[0]+"&num_formapago1="+num_formapago1+"&num_formapago2="+num_formapago2+"&txtCuotasTP="+txtCuotasTP+"&txtDiasPlazoTP="+txtDiasPlazoTP+"&txtFechaTP="+txtFechaTP+"&tipoMovimiento="+arrayFormaPago[1]+"&txtCredito="+txtCredito+"&cadena="+cadena,
      
	//      data: str+"&txtAccion="+accion+"&idFormaPago="+arrayFormaPago[0]+"&txtCuotasTP="+txtCuotasTP+"&txtDiasPlazoTP="+txtDiasPlazoTP+"&txtFechaTP="+txtFechaTP+"&tipoMovimiento="+arrayFormaPago[1],
            // para mostrar el loadian antes de cargar los datos
            beforeSend: function(){
                //imagen de carga
                $("#mensajeFacturaVentaCondominios2").html("<p align='center'><img src='images/loading.gif' /></p>");
            },
            success: function(data){
                //alert(data.length);
                document.getElementById("mensajeFacturaVentaCondominios2").innerHTML=data;
                if(data.length == 87){
                    //document.getElementById("frmServicios").reset();
                }
                //listar_servicios();
            }
        });
    }else{
        alert ('Seleccione el Tipo de Pago.');
        document.getElementById("cmbFormaPagoFP").focus();
        //dml.elements['cmbFormaPagoFP'].focus();
    }
    
}



function eliminarVenta(id_venta, accion){
//alert("eliminar");
//alert(id_venta);
    var respuesta12 = confirm("Seguro desea eliminar este venta?");
    if (respuesta12){
        $.ajax({
                url: 'sql/facturaVenta.php',
                data: 'id_venta='+id_venta+'&txtAccion='+accion,
                type: 'post',
                success: function(data){
                        if(data!="")
                               // alert(data);
                        listar_facturas();
                }
        });
    }
} 

function listar_facturas(){
    //PAGINA: clientesCondominios.php 
    var str = $("#frmFacturaVentaCondominios").serialize();
    $.ajax({
            url: 'ajax/listarFacturasVentas.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#listar_facturas").html(data);

            }
    });
} 


function factura_imprimir(){
    //alert("entro");
	$("#div_oculto").load("ajax/factura_imprimir.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
			overlayCSS: {backgroundColor: '#111'},
			css:{

				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',

				background: '',
				top: '6px',
				left: '250px',
				position: 'absolute',
				width: '800px'
			}
		});
	});
}


function fn_agregar_factura(valor){
 posicion = valor;// esta valor es para empezar desde cero lo envia desde la pag: js/busquedas.js la funcion lookup15()  onBlur='fill2();'  onchange='habilitar("+contador+");' onchange='limpiar_id('txtCodigo"+contador+"','txtCuenta2"+contador+"','txtIdCuenta"+contador+"','txtIdDetalleLibroDiario"+contador+"')'
    contador2 = contador;
    contador2++;
    cadena = "<tr id='fila1'>";
     
   //cadena = "";
    //cadena = cadena + "<tr>";
    cadena = cadena + "<td style='text-align: center;' ><a onclick=\"limpiarFilasFacturaVentaCondominios("+contador+");\" title='Limpiar fila'><img style='margin-top: 5px' src='images/limpiar.gif' /></a>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIdIvaS"+contador+"' name='txtIdIvaS"+contador+"'  readonly='readonly'>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIvaS"+contador+"' name='txtIvaS"+contador+"'  readonly='readonly'> </td>";
    cadena =  cadena + "<td><input type='hidden' id='txtIdServicio"+contador+"' name='txtIdServicio"+contador+"' >     <input  style='margin: 0px; width: 100%;' type='search' id='txtCodigoServicio"+contador+"' name='txtCodigoServicio"+contador+"' class='text_input'   autocomplete='off'  placeholder='Buscar...' onclick='lookup10(this.value, "+contador+", 4);' onKeyUp='lookup10(this.value, "+contador+", 4);' />  <div class='suggestionsBox' id='suggestions10"+contador+"' style='display: none; margin-top: 0px; '> <div class='suggestionList' id='autoSuggestionsList10"+contador+"'>  </div> </div>  </td>";
    cadena = cadena + "<td colspan=''><input type='search' style='margin: 0px; width: 100%;' class='text_input' autocomplete='off'  id='txtDescripcionS"+contador+"'  name='txtDescripcionS"+contador+"'  value=''  >      </td> ";
    cadena = cadena + "<td> <input type='text' maxlength='10' id='txtCantidadS"+contador+"' name='txtCantidadS"+contador+"' style='margin: 0px; width: 100%; text-align: right; ' class='text_input' onKeyUp=\"calculaCantidadFacturaVentaCondominios("+contador+")\" onclick=\"calculaCantidadFacturaVentaCondominios1("+contador+")\" onKeyPress=\"return soloNumeros(event)\" autocomplete='off' > </td>";
    cadena = cadena + "<td><select id='txtPrecioS"+contador+"' name='txtPrecioS"+contador+"' style='margin: 0px; width: 100%;' class='text_input' onchange=\"RecalcularV(frmFacVenta)\" onKeyPress=\"return precio(event)\"> <option value='1'>1</option><option value='2'>2</option><option value='3'> 3</option><option value='4'>4 </option><option value='5'>5 </option> <option value='6'>6</option> </select></td>";
    cadena = cadena + "<td><input type='text' style='margin: 0px; width: 100%; text-align: right; ' class='text_input' id='txtValorUnitarioS"+contador+"' name='txtValorUnitarioS"+contador+"' onKeyUp=\"calculaCantidadFacturaVentaCondominios("+contador+")\" onclick=\"calculaCantidadFacturaVentaCondominios1("+contador+")\" autocomplete='off'  ></td>";
    cadena = cadena + "<td><input type='text' style='margin: 0px; width: 100%; text-align: right; ' class='text_input' id='txtValorTotalS"+contador+ "' name='txtValorTotalS"+contador+"'  readonly='readonly'>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtCalculoIvaS"+contador+"' name='txtCalculoIvaS"+contador+"'  readonly='readonly'>  </td>";
    cadena = cadena + "<td><input type='hidden' style='margin: 0px; width: 100%; text-align: right; ' class='text_input' id='txtIvaItemS"+contador+  "' name='txtIvaItemS"+contador+   "'  autocomplete='off'  ></td>";
	cadena = cadena + "<td><input type='hidden' style='margin: 0px; width: 100%; text-align: right; ' class='text_input' id='txtTotalItemS"+contador+"' name='txtTotalItemS"+contador+ "'  autocomplete='off'  ></td>"; 
	cadena = cadena + "</tr>";
														//$('#txtValorTotalS'+i).val(datos[12]);
														//$('#txtIvaItemS'+i).val(datos[13]);
														//$('#txtTotalItemS'+i).val(datos[14]);				

    document.getElementById('txtContadorFilasFVC').value=contador;
    contador++;

    $("#tblBodyFacVentaCondominios ").append(cadena);


    //fn_dar_eliminar();
    //fn_cantidad();
}


function calcular_total(){
    var suma =0;
	var totiva=0;
	var total1=0;
    for(i=1;i<contador;i++)
	{
		//$('#txtValorTotalS'+i).val(datos[12]);
		//$('#txtIvaItemS'+i).val(datos[13]);
		//$('#txtTotalItemS'+i).val(datos[14]);				

		//txtValorTotalS
        de = $("#txtValorTotalS"+i).val();
		ival_items = $("#txtIvaItemS"+i).val();		
		total_items = $("#txtTotalItemS"+i).val();
		//alert(iva_itemS);
		//alert(total_items);
        if(de == ""){
            de=0;
        }
        suma = suma + parseFloat(de);
		totiva = totiva+ parseFloat(ival_items);
		total1 = total1 + parseFloat(total_items);
		/* alert(suma);
		alert(totiva);
		alert(total1); */
		debe=document.getElementById('txtSubtotalFVC').value=(suma).toFixed(2);		
		debe1=document.getElementById('txtTotalIvaFVC').value=(totiva).toFixed(2);
    	debe2=document.getElementById('txtTotalFVC').value=(total1).toFixed(2);
    
	}
//	iva=document.getElementById('txtTotalIvaFVC').value=(totiva).toFixed(2);
//iva=document.getElementById('txtTotalFVC').value=(suma).toFixed(2);

}

function calculaCantidadFacturaVentaCondominios1(posicion){
    //FUNCION QUE PERMITE RECALCULAR EL VALOR IVA SUBTOTAL Y EL TOTAL
    var suma =0;
    var calculoIva = 0;
    var iva = 0;
    cantidad = $("#txtCantidadS"+posicion).val();
    valorUnitario = $("#txtValorUnitarioS"+posicion).val();
    suma = parseFloat(valorUnitario * cantidad);
    //if($("#idIva"+posicion).val() >= 1 && $("#iva"+posicion).val() >= 1){
        iva = $("#txtIvaS"+posicion).val();
        calculoIva = ((suma * iva ) /100);
    //}

    $("#txtValorTotalS"+posicion).val(suma.toFixed(2));
    $("#txtCalculoIvaS"+posicion).val(calculoIva.toFixed(2));
    calculoSubTotalFacturaVentaCondominios1();

}

function calculoSubTotalFacturaVentaCondominios1(){
    var sumaValorTotal = 0;
    var sumaCalculoIva = 0;
    for(i=1;i<contador;i++){
        valorTotal = $("#txtValorTotalS"+i).val();
        calculoIva = $("#txtCalculoIvaS"+i).val();
        if(valorTotal == ""){
            valorTotal=0;
        }
        if(calculoIva == ""){
            calculoIva=0;
        }
        sumaValorTotal = sumaValorTotal + parseFloat(valorTotal);
        sumaCalculoIva = sumaCalculoIva + parseFloat(calculoIva);
    }
    document.getElementById('txtSubtotalFVC').value=(sumaValorTotal).toFixed(2);
    document.getElementById('txtTotalIvaFVC').value=(sumaCalculoIva).toFixed(2);
    calculoTotalFacturaVentaCondominios();
}

/* 
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
 */



//fn_agregar_factura

/* function AgregarFilasFacVentaCondominios(){
    cadena = "";
    cadena = cadena + "<tr>";
    cadena = cadena + "<td style='text-align: center;' ><a onclick=\"limpiarFilasFacturaVentaCondominios("+contador+");\" title='Limpiar fila'><img style='margin-top: 5px' src='images/limpiar.gif' /></a>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIdIvaS"+contador+"' name='txtIdIvaS"+contador+"'  readonly='readonly'>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIvaS"+contador+"' name='txtIvaS"+contador+"'  readonly='readonly'> </td>";
    cadena =  cadena + "<td><input type='hidden' id='txtIdServicio"+contador+"' name='txtIdServicio"+contador+"' >     <input  style='margin: 0px; width: 100%;' type='search' id='txtCodigoServicio"+contador+"' name='txtCodigoServicio"+contador+"' class='text_input'   autocomplete='off'  placeholder='Buscar...' onclick='lookup10(this.value, "+contador+", 4);' onKeyUp='lookup10(this.value, "+contador+", 4);' />  <div class='suggestionsBox' id='suggestions10"+contador+"' style='display: none; margin-top: 0px; '> <div class='suggestionList' id='autoSuggestionsList10"+contador+"'>  </div> </div>  </td>";
    cadena = cadena + "<td colspan=''><input type='search' style='margin: 0px; width: 100%;' class='text_input' autocomplete='off'  id='txtDescripcionS"+contador+"'  name='txtDescripcionS"+contador+"'  value=''  >      </td> ";
    cadena = cadena + "<td> <input type='text' maxlength='10' id='txtCantidadS"+contador+"' name='txtCantidadS"+contador+"' style='margin: 0px; width: 100%; text-align: right; ' class='text_input' onKeyUp=\"calculaCantidadFacturaVentaCondominios("+contador+")\" onclick=\"calculaCantidadFacturaVentaCondominios("+contador+")\" onKeyPress=\"return soloNumeros(event)\" autocomplete='off' > </td>";
    cadena = cadena + "<td><select id='txtPrecioS"+contador+"' name='txtPrecioS"+contador+"' style='margin: 0px; width: 100%;' class='text_input' onchange=\"RecalcularV(frmFacVenta)\" onKeyPress=\"return precio(event)\"> <option value='1'>1</option><option value='2'>2</option><option value='3'> 3</option><option value='4'>4 </option><option value='5'>5 </option> <option value='6'>6</option> </select></td>";
    cadena = cadena + "<td><input type='text' style='margin: 0px; width: 100%; text-align: right; ' class='text_input' id='txtValorUnitarioS"+contador+"' name='txtValorUnitarioS"+contador+"' onKeyUp=\"calculaCantidadFacturaVentaCondominios("+contador+")\" onclick=\"calculaCantidadFacturaVentaCondominios("+contador+")\" autocomplete='off'  ></td>";
    cadena = cadena + "<td><input type='text' style='margin: 0px; width: 100%; text-align: right; ' class='text_input' id='txtValorTotalS"+contador+"' name='txtValorTotalS"+contador+"'  readonly='readonly'>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtCalculoIvaS"+contador+"' name='txtCalculoIvaS"+contador+"'  readonly='readonly'>  </td>";
    cadena = cadena + "</tr>";

    document.getElementById('txtContadorFilasFVC').value=contador;
    contador++;

    $("#tblBodyFacVentaCondominios ").append(cadena);

    //fn_dar_eliminar();
    //fn_cantidad();
}
 */

function fn_agregar_xx(valor){
    posicion = valor;// esta valor es para empezar desde cero lo envia desde la pag: js/busquedas.js la funcion lookup15()  onBlur='fill2();'  onchange='habilitar("+contador+");' onchange='limpiar_id('txtCodigo"+contador+"','txtCuenta2"+contador+"','txtIdCuenta"+contador+"','txtIdDetalleLibroDiario"+contador+"')'
    contador2 = contador;
    contador2++;
    cadena = "<tr id='fila1'>";
    cadena = cadena + "<td><input type='hidden' readonly='readonly' id='txtIdDetalleLibroDiario"+contador+"' name='txtIdDetalleLibroDiario"+contador+"' value='0' class='text_input3'/>    <input type='hidden' readonly='readonly' id='txtIdCuenta"+contador+"' name='txtIdCuenta"+contador+"' value='0' class='text_input3'/>      <input  style='margin: 0px; width: 100%;' type='search' id='txtCodigo"+contador+"' name='txtCodigo"+contador+"' value='' class='text_input' onclick='lookup2(this.value,"+contador+", 5);' onKeyUp='lookup2(this.value,"+contador+", 5);'  autocomplete='off'  placeholder='Buscar por Codigo o Nombre'  onKeyDown='saltar(event , this.form.txtCuenta2"+contador+")'   />       <div class='suggestionsBox' id='suggestions"+contador+"' style='display: none; margin-top: 0px; '> <div class='suggestionList' id='autoSuggestionsList"+contador+"'> &nbsp; </div> </div>    </td>";
    cadena = cadena + "<td colspan='2'><input type='search' id='txtCuenta2"+contador+"' name='txtCuenta2"+contador+"' value=''  style='margin: 0px; width: 100%;' class='text_input' autocomplete='off'   onKeyDown='saltar(event ,this.form.txtDebe"+contador+")'  /> ";
    cadena = cadena + "<input type='hidden' id='txtCuentaBanco"+contador+"' name='txtCuentaBanco"+contador+"' value=''  class='text_input' /> </td>";
    cadena = cadena + "<td><input style='text-align: right; margin: 0px; width: 100%;' type='text' id='txtDebe"+contador+"' name='txtDebe"+contador+"' value='0' class='text_input2' onKeyUp='calcular_debe();' onclick='calcular_debe();' onKeyPress='return precio(event)' autocomplete='off' onKeyDown='saltar(event,this.form.txtHaber"+contador+");'  /></td>";
    cadena = cadena + "<td><input style='text-align: right; margin: 0px; width: 100%;' type='text' id='txtHaber"+contador+"' name='txtHaber"+contador+"' value='0' class='text_input2' onKeyUp='calcular_haber();' onclick='calcular_haber();' onKeyPress='return precio(event)' autocomplete='off' onKeyDown='saltar(event,this.form.txtCodigo"+contador2+");'  /></td>";
    cadena = cadena + "<td style='text-align: center; '><a onclick='limpiaFila("+contador+");' title='Limpiar fila'><img style='margin-top: 5px' src='images/limpiar.gif' /></a></td>";
    document.getElementById('txtContadorFilas').value=contador;
    contador++;    
    
    
    $("#grilla tbody").append(cadena);    
    //fn_dar_eliminar();
    fn_cantidad();


}



