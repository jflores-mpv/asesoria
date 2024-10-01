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
var lista_productos_tabla;

var enProceso = false;
function handleKeyPress(codigo) {
    
    if (enProceso) {
    return;
  }
  var codigoL = codigo.length ;
  
  console.log("codigoL",codigoL);
  // Verificar si se ha ingresado el código de barras completo (por ejemplo, si tiene una longitud específica)
  if (codigo.length === 13 ) {
        enProceso = true;
    $.post("sql/servicios_edu.php", { queryString: codigo, cont: contador, txtAccion: 50 }, function(data) {
      if (data && data.length > 0) {
          
    
            
            let variable1 = data[0];

     
      }
        AgregarFilasPedido(data);
      enProceso = false;
    });
  }
}



function borrarTextoInput() {
  const input = document.getElementById('codigoBarras');
  input.value = ""; // Establece el valor del campo en una cadena vacía
}

function borrarTextoInputCant(contador) {
  const input = document.getElementById('txtCantidadS'+contador);
  input.value = ""; // Establece el valor del campo en una cadena vacía
}


var codigos = [];

function AgregarFilasPedido(data) {

    // console.log("data ===1==",data);
   
  var array = data.split(",");
//   console.log(array);
//   var codigo = array[0];
  var cantidad = 1;

 let datos=array[0].toString();
    datos = datos.trim();
  var index = codigos.indexOf(datos);
  
  if (index >= 0) {

    var contadorActual = parseInt($('#txtContadorAsientosAgregadosFVC').val() || 0) ;
       let fila = index + 1;
        cantidad = Number($("#txtCantidadS"+fila).val()) + 1;
        $("#txtCantidadS"+fila).val(cantidad);
     calculaCantidadFacturaVentaEducativo(contadorActual);
    
  } else {
    
    codigos.push(datos);

    var contadorActual = parseInt($('#txtContadorAsientosAgregadosFVC').val() || 0) + 1;
    
    if(contadorActual>4){
        AgregarFilasFacVentaEducativo();
    }
    const cadenaSinComillas = datos.replace(/['"]/g, '');
    const bodegasincomillas = array[8].replace(/['"]/g, '');
    const bodcantidasincomillas = array[11].replace(/['"]/g, '');
    
    
    
    $('#txtIdServicio' + contadorActual).val(cadenaSinComillas);
    $('#txtCodigoServicio' + contadorActual).val(array[1]);
    $('#txtDescripcionS' + contadorActual).val(array[2]);
    var valorUnitario = parseFloat(array[3]);
    $('#txtValorUnitarioS' + contadorActual).val(valorUnitario.toFixed(5));
    $('#txtValorUnitarioShidden' + contadorActual).val(valorUnitario.toFixed(5));
    $('#txtIdIvaS' + contadorActual).val(array[4]);
    $('#txtIvaS' + contadorActual).val(array[5]);
    $('#txtTipoS' + contadorActual).val(array[6]);
    $('#txtTipoProductoS' + contadorActual).val(array[5]);
    $('#idbod' + contadorActual).val(bodegasincomillas);
    $('#IVA120' + contadorActual).val(array[7]);
    $('#cuenta' + contadorActual).val(array[9]);
    $('#bod' + contadorActual).val(array[10]);
    $('#bodegaCantidad' + contadorActual).val(bodcantidasincomillas);
    $('#txtCantidadS' + contadorActual).val(1);

    document.getElementById('txtContadorFilasFVC').value = contadorActual;
    document.getElementById('txtContadorAsientosAgregadosFVC').value = contadorActual;
  
    calculaCantidadFacturaVentaEducativo(contadorActual);
  }
    
    borrarTextoInput();

}





function AgregarFilasFacVentaEducativoMovil(){
    

    cadena = "";
    
    
    cadena = cadena + "<div class='form-group '>";
    
    cadena = cadena + "<div class='input-group bg-light'>";
    
    cadena = cadena + " <a onclick=\"limpiarFilasFacturaVentaEducativo("+contador+");\" title='Limpiar fila' class='btn btn-outline-warning fa fa-window-close'></a>";  
    
    cadena = cadena + "</div>";
    
    
    cadena = cadena + "<div class='input-group bg-light'>";
    
    

    cadena = cadena + "<input type='hidden' id='txtIdServicio"+contador+"' name='txtIdServicio"+contador+"' ><input   type='search' id='txtCodigoServicio"+contador+"' name='txtCodigoServicio"+contador+"' class='form-control '   autocomplete='off'  placeholder='Codigo' onclick='lookup10_edu(this.value, "+contador+", 4,cmbTipoDocumentoFVC.value);' onKeyUp='lookup10_edu(this.value, "+contador+", 4,cmbTipoDocumentoFVC.value);' >   ";
    
    
    cadena = cadena + "</div>";
    
    cadena = cadena + "<div class='input-group mt-1 bg-light'>";
    
    cadena = cadena + "<div class='suggestionsBox' id='suggestions10"+contador+"' style='display: none; margin-top: 40px; '> <div class='suggestionList' id='autoSuggestionsList10"+contador+"'>  </div> </div> ";

    cadena = cadena + "</div>";
    cadena = cadena + "<div class='input-group mt-1 bg-light'>";

    cadena = cadena + "<input type='search' style='width:25% ' class='form-control'  autocomplete='off'  id='txtDescripcionS"+contador+"'  name='txtDescripcionS"+contador+"' placeholder='Buscar por Nombre' value='' onclick='lookup10_edu(this.value, "+contador+", 40,cmbTipoDocumentoFVC.value);' onKeyUp='lookup10_edu(this.value, "+contador+", 40,cmbTipoDocumentoFVC.value);'  ><a onclick='detalleAdicional("+contador+");' class='btn btn-outline-secondary'><i class='fas fa-plus'></i></a>";


    cadena = cadena + "</div>";
    
    
    cadena = cadena + "<div class='input-group bg-light'>";


    cadena = cadena + "<input type='text' maxlength='10' id='bod"+contador+"'  name='bod"+contador+"'  class='form-control   bg-white'  onKeyUp=\"lookup_cpra_bod(this.value, "+contador+", 7)\"  >";

    
    cadena = cadena + "</div>";
    cadena = cadena + "<div class='input-group mt-1 bg-light'>";
    
    cadena = cadena + "<input type='text' maxlength='10' style='text-align: center; ' id='txtCantidadS"+contador+"' name='txtCantidadS"+contador+"' class='form-control ' onKeyUp=\"calculaCantidadFacturaVentaEducativo("+contador+")\" onclick=\"calculaCantidadFacturaVentaEducativo("+contador+")\" onchange=\"validarCantidad(txtCantidadS"+contador+",cantidadEnBodega"+contador+".value);\"  autocomplete='off' >";
    cadena = cadena + "<input type='text' style='text-align: right; ' class='form-control  ' id='txtdesc_mostrar"+contador+"' name='txtdesc_mostrar"+contador+"' onKeyUp=\"calculaValorunitariodescuento("+contador+")\" onclick=\"calculaValorunitariodescuento("+contador+")\"  autocomplete='off'  >";
  
  
  
    cadena = cadena + "</div>";
    cadena = cadena + "<div class='input-group mt-1 bg-light'>";
  
  
    cadena = cadena + "<input type='hidden' style='text-align: right; ' class='form-control  ' id='txtdesc"+contador+"' name='txtdesc"+contador+"' onKeyUp=\"calculaValorunitariodescuento("+contador+")\" onclick=\"calculaValorunitariodescuento("+contador+")\"  autocomplete='off'  >";
  
  
    cadena = cadena + "</div>";
    cadena = cadena + "<div class='input-group mt-1 bg-light'>";
  
  
  
    cadena = cadena + "<select class='form-control bg-white' id='cambiarPrecio"+contador+"' name='cambiarPrecio"+contador+"'  onchange=\"cambiarPrecios(\'"+contador+"\',txtIdServicio"+contador+".value, this.value );calculaCantidadFacturaVentaEducativo(\'"+contador+"\' )\" > <option value='precio1'>1</option> <option value='precio2'>2</option> <option value='precio3'>3</option> <option value='precio4'>4</option> <option value='precio5'>5</option> <option value='precio6'>6</option> </select>";

    cadena = cadena + "<input type='text' style=' text-align: right; ' class='form-control  ' id='txtValorUnitarioShidden"+contador+"' name='txtValorUnitarioShidden"+contador+"' onKeyUp=\"calculaCantidadFacturaVentaEducativo("+contador+")\" onclick=\"calculaCantidadFacturaVentaEducativo("+contador+")\" autocomplete='off'>";

    cadena = cadena + "<input type='text' style='text-align: right; ' class='form-control  ' id='txtValorUnitarioS"+contador+"' name='txtValorUnitarioS"+contador+"' onKeyUp=\"calculaCantidadFacturaVentaEducativo("+contador+")\" onclick=\"calculaCantidadFacturaVentaEducativo("+contador+")\" autocomplete='off'  >";
    
    cadena = cadena + "<input type='text' style='text-align: right; ' class='form-control   bg-white' id='txtValorTotalS"+contador+"' name='txtValorTotalS"+contador+"'  readonly='readonly'>";

	cadena = cadena + "<input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIvaS"+contador+"' name='txtIvaS"+contador+"'  readonly='readonly'> <input type='hidden'  class='text_input' id='txtTipoS1"+contador+"' name='txtTipoS1"+contador+"'  readonly='readonly'> </div>";

    cadena = cadena + "<input type='hidden' id='bodegaCantidad"+contador+"'  name='bodegaCantidad"+contador+"'   class='form-control  border-0 bg-white' >";
    
    cadena = cadena + "<input type='hidden' id='id_proyecto"+contador+"'  name='id_proyecto"+contador+"'   class='form-control  border-0 bg-white' >";
    
    cadena = cadena + "<input type='hidden' style='margin: 0px; width: 20%; text-align: right;' class='form-control  ' id='txtTipoS"+contador+"' name='txtTipoS"+contador+"'  readonly='readonly'> ";
    cadena = cadena + "<input type='hidden' style='margin: 0px; width: 25%; text-align: right;' class='form-control ' id='txtTipoProductoS"+contador+"' name='txtTipoProductoS"+contador+"'  readonly='readonly'> ";

    cadena = cadena + "<input type='hidden' style='margin: 0px; width: 100%; text-align: right; ' class='form-control  ' id='txtdescant"+contador+"' name='txtdescant"+contador+"' onKeyUp=\"calculaValorunitariodescuento("+contador+")\" onclick=\"calculaValorunitariodescuento("+contador+")\" autocomplete='off'>";
	cadena = cadena + "<input type='hidden' id='cuenta"+contador+"'  name='cuenta"+contador+"'   class='form-control  border-0 bg-white' >";
	cadena = cadena + "<input type='hidden' id='idbod"+contador+"'  name='idbod"+contador+"'   class='form-control  border-0 bg-white' >";
    cadena = cadena + "<input type='hidden' id='IVA120"+contador+"'  name='IVA120"+contador+"'   class='form-control  border-0 bg-white' >";
    
     cadena = cadena + "<input type='hidden' id='cantidadEnBodega"+contador+"'  name='cantidadEnBodega"+contador+"'   class='form-control  border-0 bg-white' value='1' >";
    
    
	cadena = cadena + "<input type='hidden'  class='form-control' id='txtCalculoIvaS"+contador+"' name='txtCalculoIvaS"+contador+"'  readonly='readonly'>";
	cadena = cadena + "<input type='hidden'  class='text_input' id='txtIvaItemS"+contador+  "' name='txtIvaItemS"+contador+   "'   >";
	cadena = cadena + "<input type='hidden'  class='text_input' id='txtTotalItemS"+contador+"' name='txtTotalItemS"+contador+ "'  >"; 
	cadena = cadena + "<input type='hidden'  class='text_input' id='txtCuentaS"+contador+"' name='txtCuentaS"+contador+"'  readonly='readonly'>";
    cadena = cadena + "</div>";
    
    cadena = cadena + "<div class='input-group mt-1'>";
    cadena = cadena + "<textarea type='text' class='form-control my-1' autocomplete='off'  id='txtdetalle2"+contador+"'  name='txtdetalle2"+contador+"'  value='' placeholder='Detalle Adicional' style='display:none' maxlength='300' ></textarea>";
    cadena = cadena + "</div>";

    cadena = cadena + "</div>";
    
    document.getElementById('txtContadorFilasFVC').value=contador;
    contador++;
    $("#tblBodyFacVentaCondominios ").append(cadena);
    
    
  
}


function AgregarFilasFacVentaEducativo(aacionBuscarnombre){
    console.log("aacionBuscarnombre",aacionBuscarnombre);
    

    cadena = "";
    cadena = cadena + "<div class='form-group '>";
    cadena = cadena + "<div class='input-group mt-1 bg-light'>";
    
    cadena = cadena + " <a onclick=\"limpiarFilasFacturaVentaEducativo("+contador+");\" title='Limpiar fila' class='btn btn-outline-secondary fa fa-window-close'></a>     ";  

    cadena = cadena + "<input type='hidden' id='txtIdServicio"+contador+"' name='txtIdServicio"+contador+"' >     <input   type='search' id='txtCodigoServicio"+contador+"' name='txtCodigoServicio"+contador+"' class='form-control '   autocomplete='off'  placeholder='Codigo' onclick='lookup10_edu(this.value, "+contador+", 4,cmbTipoDocumentoFVC.value);' onKeyUp='lookup10_edu(this.value, "+contador+", 4,cmbTipoDocumentoFVC.value);' />   ";
    
    
    cadena = cadena + "<div class='suggestionsBox' id='suggestions10"+contador+"' style='display: none; margin-top: 40px; '> <div class='suggestionList' id='autoSuggestionsList10"+contador+"'>  </div> </div> ";

    
    cadena += "<input type='search' style='width:25%' class='form-control' autocomplete='off' id='txtDescripcionS" + contador + "' name='txtDescripcionS" + contador + "' placeholder='Buscar por Nombre' value='' onclick='lookup10_edu(this.value, " + contador + ", \"" + aacionBuscarnombre + "\", cmbTipoDocumentoFVC.value);' onKeyUp='lookup10_edu(this.value, " + contador + ", \"" + aacionBuscarnombre + "\", cmbTipoDocumentoFVC.value);'  ><a onclick='detalleAdicional("+contador+");' class='btn'><i class='fa fa-plus' aria-hidden='true'></i></a>";
    
    
    cadena = cadena + "<input type='text' maxlength='10' id='bod"+contador+"'  name='bod"+contador+"'  class='form-control   bg-white'  onKeyUp=\"lookup_cpra_bod(this.value, "+contador+", 7)\"  >";

    cadena = cadena + "<input type='text' maxlength='10' style='text-align: center; ' id='txtCantidadS"+contador+"' name='txtCantidadS"+contador+"' class='form-control ' onKeyUp=\"calculaCantidadFacturaVentaEducativo("+contador+")\" onclick=\"calculaCantidadFacturaVentaEducativo("+contador+")\" onchange=\"validarCantidad(txtCantidadS"+contador+",cantidadEnBodega"+contador+".value,cantidadOriginal"+contador+".value);\"  autocomplete='off' >";
    
    cadena = cadena + "<input type='text' style='text-align: right; ' class='form-control  ' id='txtdesc_mostrar"+contador+"' name='txtdesc_mostrar"+contador+"' onKeyUp=\"calculaValorunitariodescuento("+contador+")\" onclick=\"calculaValorunitariodescuento("+contador+")\"  autocomplete='off'  >";
  
    cadena = cadena + "<input type='hidden' style='text-align: right; ' class='form-control  ' id='txtdesc"+contador+"' name='txtdesc"+contador+"' onKeyUp=\"calculaValorunitariodescuento("+contador+")\" onclick=\"calculaValorunitariodescuento("+contador+")\"  autocomplete='off'  >";
  
    cadena = cadena + "<select class='form-control bg-white' id='cambiarPrecio"+contador+"' name='cambiarPrecio"+contador+"'  onchange=\"cambiarPrecios(\'"+contador+"\',txtIdServicio"+contador+".value, this.value );calculaCantidadFacturaVentaEducativo(\'"+contador+"\' )\" > <option value='precio1'>1</option> <option value='precio2'>2</option> <option value='precio3'>3</option> <option value='precio4'>4</option> <option value='precio5'>5</option> <option value='precio6'>6</option> </select>";
    
  
    cadena = cadena + "<input type='text' style=' text-align: right; ' class='form-control  ' id='txtValorUnitarioShidden"+contador+"' name='txtValorUnitarioShidden"+contador+"' onKeyUp=\"calculaCantidadFacturaVentaEducativo("+contador+")\" onclick=\"calculaCantidadFacturaVentaEducativo("+contador+")\" autocomplete='off'>";
    
    

    cadena = cadena + "<input type='text' style='text-align: right; ' class='form-control  ' id='txtValorUnitarioS"+contador+"' name='txtValorUnitarioS"+contador+"' onKeyUp=\"calculaCantidadFacturaVentaEducativo("+contador+")\" onclick=\"calculaCantidadFacturaVentaEducativo("+contador+")\" autocomplete='off'  >";
    
    cadena = cadena + "<input type='text' style='text-align: right; ' class='form-control   bg-white' id='txtValorTotalS"+contador+"' name='txtValorTotalS"+contador+"'  readonly='readonly'>";

    
    cadena = cadena + "<input type='hidden'  class='form-control' id='precioOrignal"+contador+"' name='precioOrignal"+contador+"'  readonly='readonly'>";
 
	cadena = cadena + "<input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIvaS"+contador+"' name='txtIvaS"+contador+"'  readonly='readonly'> <input type='hidden'  class='text_input' id='txtTipoS1"+contador+"' name='txtTipoS1"+contador+"'  readonly='readonly'> </div>";

    cadena = cadena + "<input type='hidden' id='bodegaCantidad"+contador+"'  name='bodegaCantidad"+contador+"'   class='form-control  border-0 bg-white' >";
     cadena = cadena + "<input type='hidden' id='cantidadOriginal"+contador+"'  name='cantidadOriginal"+contador+"'   class='form-control  border-0 bg-white' >";
    cadena = cadena + "<input type='hidden' id='id_proyecto"+contador+"'  name='id_proyecto"+contador+"'   class='form-control  border-0 bg-white' >";
    
    cadena = cadena + "<input type='hidden' style='margin: 0px; width: 20%; text-align: right;' class='form-control  ' id='txtTipoS"+contador+"' name='txtTipoS"+contador+"'  readonly='readonly'> ";
    cadena = cadena + "<input type='hidden' style='margin: 0px; width: 25%; text-align: right;' class='form-control ' id='txtTipoProductoS"+contador+"' name='txtTipoProductoS"+contador+"'  readonly='readonly'> ";

    cadena = cadena + "<input type='hidden' style='margin: 0px; width: 100%; text-align: right; ' class='form-control  ' id='txtdescant"+contador+"' name='txtdescant"+contador+"' onKeyUp=\"calculaValorunitariodescuento("+contador+")\" onclick=\"calculaValorunitariodescuento("+contador+")\" autocomplete='off'>";
	cadena = cadena + "<input type='hidden' id='cuenta"+contador+"'  name='cuenta"+contador+"'   class='form-control  border-0 bg-white' >";
	cadena = cadena + "<input type='hidden' id='idbod"+contador+"'  name='idbod"+contador+"'   class='form-control  border-0 bg-white' >";
    cadena = cadena + "<input type='hidden' id='IVA120"+contador+"'  name='IVA120"+contador+"'   class='form-control  border-0 bg-white' >";
    
     cadena = cadena + "<input type='hidden' id='cantidadEnBodega"+contador+"'  name='cantidadEnBodega"+contador+"'   class='form-control  border-0 bg-white' value='1' >";
    
    
	cadena = cadena + "<input type='hidden'  class='form-control' id='txtCalculoIvaS"+contador+"' name='txtCalculoIvaS"+contador+"'  readonly='readonly'>";
	cadena = cadena + "<input type='hidden'  class='text_input' id='txtIvaItemS"+contador+  "' name='txtIvaItemS"+contador+   "'   >";

	cadena = cadena + "<input type='hidden'  class='text_input' id='txtTotalItemS"+contador+"' name='txtTotalItemS"+contador+ "'  >"; 
	cadena = cadena + "<input type='hidden'  class='text_input' id='txtCuentaS"+contador+"' name='txtCuentaS"+contador+"'  readonly='readonly'>";
    cadena = cadena + "</div>";
    
    cadena = cadena + "<div class='input-group mt-1'>";
    cadena = cadena + "<textarea type='text' class='form-control my-1' autocomplete='off'  id='txtdetalle2"+contador+"'  name='txtdetalle2"+contador+"'  value='' placeholder='Detalle Adicional' style='display:none' maxlength='300' ></textarea>";
    cadena = cadena + "</div>";

    cadena = cadena + "</div>";
    
    document.getElementById('txtContadorFilasFVC').value=contador;
    contador++;
    $("#tblBodyFacVentaCondominios ").append(cadena);
    
    
  
}



function cambiarPrecios(fila,idProducto, tipoPrecio){

    $.ajax({
		url: 'sql/servicios_edu.php',
		type: 'POST',
		data: 'txtAccion=10'+'&idProducto='+idProducto+'&tipoPrecio='+tipoPrecio,
		beforeSend: function()
		{
			// $("#mensaje1").html("<p align='center'><img src='images/loading.gif' /></p>");
		},
		success: function(data){
            $('#txtValorUnitarioShidden'+fila).val(parseFloat(data));
			$('#txtValorUnitarioS'+fila).val(parseFloat(data));
		}
	});

}

function detalleAdicional(contador){

    cont = contador;
   
    
    
    var txtdetalle2=document.getElementById('txtdetalle2'+cont);
    
    if (txtdetalle2.style.display=='none'){
         txtdetalle2.style.display='block';
    }else{
        txtdetalle2.style.display='none';
    }

}


function limpiarFilasFacturaVentaEducativo(con){
    // alert ("entro "+con);
    if($('#txtIdServicio'+con).val() >= 1){
        $("#txtIdIvaS"+con).val("");
        $("#txtIvaS"+con).val("");
        $("#txtTipoS"+con).val("");

        $("#txtIdServicio"+con).val("");
        $("#txtCodigoServicio"+con).val("");
        $("#txtDescripcionS"+con).val("");
        $("#txtCantidadS"+con).val("");
        //$("#txtPrecioS"+con).val("");
        $("#txtCalculoIvaS"+con).val("");
        $("#txtValorUnitarioS"+con).val("");
        $("#txtValorUnitarioShidden"+con).val("");
        $("#txtValorTotalS"+con).val("");
        if( document.getElementById('txtValorTotalConIvaS'+con) ){
            document.getElementById('txtValorTotalConIvaS'+con).value="";
        }
        
        $('#txtdesc'+con).val("");
        $('#cantidadEnBodega'+con).val("");
         $('#precioOrignal'+con).val("");
        $('#idbod'+con).val("");
        $('#bod'+con).val("");
        $('#cuenta'+con).val("");
        $('#txtTipoS'+con).val("");
        $('#IVA120'+con).val("");
        

         calculaCantidadFacturaVentaEducativo(con);
       // asientosQuitadosFVC();
    }else{

    }
    // calculaCantidadFacturaVentaEducativo(con);
}

function lookup10_edu(txtNombre, cont, accion, llenar = 'no') {
    console.log("accion==>", accion);
    let destino ='sql/servicios_edu.php';
    //  if(sesion_id_empresa.value=='116' || sesion_id_empresa.value=='41' ){
    //     accion=41; 
    //   destino ='sql/servicios_edu_test2.php';
    // }
  
    
    if (txtNombre.length == 0) {
        $('#suggestions10' + cont).hide();
    } else {
        $.post(destino, { queryString: txtNombre, cont: cont, txtAccion: accion }, function (data) {
            try {
                
                // console.log("data==>", data);

                let json = JSON.parse(data);
                
                // console.log("Objeto JSON completo:", json);
                console.log("tiempo_ejecucion:", json['tiempo_ejecucion']);

                lista_productos_tabla = '';
                lista_productos_tabla = json;
                $('.suggestionsBox').hide();
                $('#suggestions10' + cont).show();
                $('#autoSuggestionsList10' + cont).html(json['tabla']);
                
            } catch (ex) {
                console.log(ex);
            }
        });

    }
}

function fill10_edu(cont, idServicio, cadena){
 //   alert(" cont: "+cont+"  idServicio: "+idServicio+" cadena: "+cadena)
//  console.log(cadena);
 
   
   setTimeout("$('.suggestionsBox').hide();", 50);
    array = cadena.split("*");


    // este if debe ir antes de asignar a los txt
    if($('#txtIdServicio'+cont).val() >= 1){
        // cuando no usa el boton limpiar significa que
        // si hay cuenta agregada en la fila y solo esta remplazando por otra cuenta
        // ya no vuelve a sumar cuantas cuentas estan agregadas
    }else{
        // cuando usa el boton limpiar
        // significa que ha quitado la cuenta y cunado agrega una nueva suma cuantas cuentas estan agregadas
        sumaAsientosAgregados =  $('#txtContadorAsientosAgregadosFVC').val();
        sumaAsientosAgregados ++;
        $('#txtContadorAsientosAgregadosFVC').val(sumaAsientosAgregados);
    }
    

    $('#txtIdServicio'+cont).val(idServicio);
    $('#txtCodigoServicio'+cont).val(array[0]);
    $('#txtDescripcionS'+cont).val(array[1]);
    var valorUnitario = parseFloat(array[2]);
    $('#txtValorUnitarioS'+cont).val(valorUnitario.toFixed(6));
    $('#txtValorUnitarioShidden'+cont).val(valorUnitario.toFixed(6));
    
    $('#txtIdIvaS'+cont).val(array[3]);
	
    $('#txtIvaS'+cont).val(array[4]);
    $('#txtTipoS'+cont).val(array[5]);
    $('#txtTipoProductoS'+cont).val(array[4]);
    $('#idbod'+cont).val(array[7]);
    $('#IVA120'+cont).val(array[6]);
    
    $('#cuenta'+cont).val(array[8]);
    $('#bod'+cont).val(array[9]);
    
    $('#bodegaCantidad'+cont).val(array[10]);
    
    
    
    
    
	$('#txtCalculoIvaS'+cont).val();
    $('#txtCantidadS'+cont).focus(); 
    
    
    

	//alert("aaa1111");
	//alert(('#txtValorUnitarioS'+cont).val(valorUnitario.toFixed(4));
    
	//alert($('#txtTipoProducto'+cont).val(array[5])); 
}


function lookup_cpra_bod(txtNombre, cont, accion) {
//para agregar SERVCIO pagina: nuevaFacturaVenta.php
    //alert("nombre:"+txtNombre+"-"+cont+"-"+accion);
    if(txtNombre.length == 0)
	{
        // Hide the suggestion box.
        $('#suggestions10'+cont).hide();
    }
	else 
	{
        $.post("sql/facturaCompra.php", {queryString: txtNombre, cont: cont,  txtAccion: accion}, function(data)
		{
			if(data.length >0) {
                 $('.suggestionsBox').hide();
                $('#suggestions10'+cont).show();
                $('#autoSuggestionsList10'+cont).html(data); 
            }
        });
    }
} // lookup


function fill_cpra_bod(cont1, idBodega, cadena){
    //console.log(cadena);
    cont=cont1;
    setTimeout("$('.suggestionsBox').hide();", 50);

    array = cadena.split("*");

    $('#idbod'+cont).val(idBodega);
    $('#bod'+cont).val(array[1]);
    $('#cuenta'+cont).val(array[2]);
    $('#txtTipoS'+cont).val(array[3]);
}

function calculaValorunitariodescuento(posicion){

if(document.getElementById('txtdesc_mostrar'+posicion)){
      let valor_porcentaje = document.getElementById('txtdesc_mostrar'+posicion).value;
   valor_porcentaje = (valor_porcentaje.trim()=='')?0:valor_porcentaje;

   document.getElementById('txtdesc'+posicion).value= parseFloat(document.getElementById('txtValorUnitarioShidden'+posicion).value) * parseFloat(valor_porcentaje)/100 ;
}
 

    //FUNCION QUE PERMITE RECALCULAR EL VALOR IVA SUBTOTAL Y EL TOTAL
//     var restadescuento =0;
    
//     var valorUnitario =0;
   
//     valorUnitario = $("#txtValorUnitarioS"+posicion).val();
//     restadescuento = parseFloat(valorUnitario - descuento);
   
//   console.log("descuento",descuento);
//   console.log("valorUnitario",valorUnitario);
//   console.log("restadescuento",restadescuento);
   
//     $("#txtValorUnitarioS"+posicion).val(restadescuento.toFixed(2));
    

    calculaCantidadFacturaVentaEducativo(posicion);
        // calculoSubTotal_ventas();
        
        
}


function calculaCantidadFacturaVentaEducativo(posicion) {
    var suma = 0;
    var calculoIva = 0;
    var iva = 0;

    var descuento = 0;
    descuento = $("#txtdesc" + posicion).val();
    cantidad = $("#txtCantidadS" + posicion).val();
    txtdescant = parseFloat(descuento * cantidad);
    valorUnitario1 = $("#txtValorUnitarioShidden" + posicion).val();

    // Verifica si el valorUnitario1 es 0 y asigna 0 a la variable suma
    suma = (valorUnitario1 == 0) ? 0 : parseFloat((valorUnitario1 - descuento) * cantidad);

    iva = $("#txtTipoProductoS" + posicion).val();
    calculoIva = ((suma * iva) / 100);

    var restadescuento = (valorUnitario1 - descuento).toFixed(4);
    suma = (suma === 0) ? 0 : parseFloat(suma.toFixed(4));
    calculoIva = (calculoIva === 0) ? '' : calculoIva.toFixed(4);
    txtdescant = (txtdescant === 0) ? '' : txtdescant.toFixed(4);

    $("#txtdescant" + posicion).val(txtdescant);
    $("#txtValorTotalS" + posicion).val(suma.toFixed(4)); // Aquí también se aplica toFixed para asegurar que sea una cadena con el formato adecuado
    $("#txtValorUnitarioS" + posicion).val(restadescuento);
    $("#txtCalculoIvaS" + posicion).val(calculoIva);

    calculoSubTotal_ventas();
}






function calculoSubTotal_ventas(){
  



    var sumaValorTotal0 = 0;
    var sumaValorTotal12 = 0;
    
    var sumaValorTotal = 0;
    var sumaCalculoIva = 0;
    let sumaDescuentos=0;
    let sumaSubtotales =[];
let tipo_cliente ='';

    if( document.getElementById('idtipocliente') ){
        tipo_cliente=$('#idtipocliente').val();
    }
     
    
    for(i=1;i<contador;i++){
        
        valorTotalIva120 = $("#IVA120"+i).val();
        
        valorTotal = $("#txtValorTotalS"+i).val();
        calculoIva = $("#txtCalculoIvaS"+i).val();


        if(valorTotal.trim() == ""){
            valorTotal=0;
        }
        
        if(calculoIva.trim() == "" || tipo_cliente == '08'){
            calculoIva=0;
        }
        
        txtdescant = $("#txtdescant"+i).val();
        if(txtdescant.trim() ==""){
            txtdescant = 0;
        }
        if(valorTotalIva120!=''){
            if (!sumaSubtotales[valorTotalIva120]) {
                sumaSubtotales[valorTotalIva120] = 0;
            }
           

            if(tipo_cliente == '08'){
              let inputWithZeroIva = document.querySelector('input[data-iva="0"]');
              if (!sumaSubtotales[inputWithZeroIva.dataset.idiva]) {
                  sumaSubtotales[inputWithZeroIva.dataset.idiva] = 0;
              }
             sumaSubtotales[inputWithZeroIva.dataset.idiva] +=  parseFloat(valorTotal);
            }else{
             sumaSubtotales[valorTotalIva120] +=  parseFloat(valorTotal);
            }
        }
       

        //  if(valorTotalIva120 == "Si" && tipo_cliente!='08'){
        //     sumaValorTotal12 = sumaValorTotal12 + parseFloat(valorTotal);
        // }
        
        //  if(valorTotalIva120 == "No" || tipo_cliente == '08'){
        //     sumaValorTotal0 = sumaValorTotal0 + parseFloat(valorTotal);
        // }
        console.log({sumaValorTotal0});
        sumaDescuentos = sumaDescuentos  + parseFloat(txtdescant);
        sumaValorTotal = sumaValorTotal + parseFloat(valorTotal);
        sumaCalculoIva = sumaCalculoIva + parseFloat(calculoIva);
        
    }
       console.log({sumaSubtotales});    
    const jsonElement = document.getElementById("json_impuestos");

    //     // Convertir el JSON a un objeto JavaScript
const jsonData = JSON.parse(jsonElement.textContent);

// Recorrer el array con un bucle
for (const element of jsonData) {
    console.log(element);
    let input_actual = document.getElementById('txtSubtotal' + element.toString());
    if (!sumaSubtotales[element]) {
        sumaSubtotales[element] = 0;
    }
    if (input_actual  ) {
        input_actual.value = sumaSubtotales[element];
    }
    
}

    document.getElementById('txtDescuentoFVCNum').value=(sumaDescuentos).toFixed(4);
    // document.getElementById('txtSubtotal0FVC').value=(sumaValorTotal0).toFixed(4);
    // document.getElementById('txtSubtotal12FVC').value=(sumaValorTotal12).toFixed(4);
    
    document.getElementById('txtSubtotalFVC').value=(sumaValorTotal).toFixed(4);
    document.getElementById('txtSubtotalFVC2').value=(sumaValorTotal).toFixed(4);
    document.getElementById('txtTotalIvaFVC').value=(sumaCalculoIva).toFixed(4);
    
    calculoTotal_Ventas();
}
// function calculoSubTotal_ventas(){
    
//     var sumaValorTotal0 = 0;
//     var sumaValorTotal12 = 0;
    
//     var sumaValorTotal = 0;
//     var sumaCalculoIva = 0;
//     let sumaDescuentos=0;
//     let tipo_cliente ='';

//     if( document.getElementById('idtipocliente') ){
//         tipo_cliente=$('#idtipocliente').val();
//     }   

    
//     for(i=1;i<contador;i++){
        
//         valorTotalIva120 = $("#IVA120"+i).val();
        
//         valorTotal = $("#txtValorTotalS"+i).val();
//         calculoIva = $("#txtCalculoIvaS"+i).val();
        
//       //   console.log("0",sumaValorTotal0 );
//         if(valorTotalIva120 == "Si"){
//             sumaValorTotal12 = sumaValorTotal12 + parseFloat(valorTotal);
//         }
        
//          if(valorTotalIva120 == "No"){
//             sumaValorTotal0 = sumaValorTotal0 + parseFloat(valorTotal);
//         }
        
//         if(valorTotal.trim() == ""){
//             valorTotal=0;
//         }
        
//         if(calculoIva.trim() == ""){
//             calculoIva=0;
//         }
        
//         txtdescant = $("#txtdescant"+i).val();
//         if(txtdescant.trim() ==""){
//             txtdescant = 0;
//         }
        
//         sumaDescuentos = sumaDescuentos  + parseFloat(txtdescant);
//         sumaValorTotal = sumaValorTotal + parseFloat(valorTotal);
//         sumaCalculoIva = sumaCalculoIva + parseFloat(calculoIva);
        
//     }
    
    
//     document.getElementById('txtDescuentoFVCNum').value=(sumaDescuentos).toFixed(4);
//     document.getElementById('txtSubtotal0FVC').value=(sumaValorTotal0).toFixed(4);
//     document.getElementById('txtSubtotal12FVC').value=(sumaValorTotal12).toFixed(4);
    
//     document.getElementById('txtSubtotalFVC').value=(sumaValorTotal).toFixed(4);
//     document.getElementById('txtSubtotalFVC2').value=(sumaValorTotal).toFixed(4);
//     document.getElementById('txtTotalIvaFVC').value=(sumaCalculoIva).toFixed(4);
    
//     calculoTotal_Ventas();
// }

function calculoTotal_Ventas(){
    
    var txtPropina = $("#txtPropinaFVC").val();
   
    var txtTotalIva = $("#txtTotalIvaFVC").val();
    var txtSubtotal = $("#txtDescuentoFVCNum").val();
    
    
    var txtSubtotalFVC2 = $("#txtSubtotalFVC2").val();
    var txtDescuentoFVCNum = $("#txtDescuentoFVCNum").val();
    
    let adicional =0;
    
    if(document.getElementById('fleteInternacional')){
       let fleteInternacional= document.getElementById('fleteInternacional').value;
       fleteInternacional = fleteInternacional.trim()==''?0:fleteInternacional.trim();
       adicional = adicional+parseFloat(fleteInternacional);
    }
    if(document.getElementById('seguroInternacional')){
       let seguroInternacional= document.getElementById('seguroInternacional').value;
       seguroInternacional = seguroInternacional.trim()==''?0:seguroInternacional.trim();
       adicional = adicional+parseFloat(seguroInternacional);
    }
    if(document.getElementById('gastosAduaneros')){
       let gastosAduaneros= document.getElementById('gastosAduaneros').value;
        gastosAduaneros = gastosAduaneros.trim()==''?0:gastosAduaneros.trim();
       adicional = adicional+parseFloat(gastosAduaneros);
    }
    if(document.getElementById('gastosTransporte')){
       let gastosTransporte= document.getElementById('gastosTransporte').value;
        gastosTransporte = gastosTransporte.trim()==''?0:gastosTransporte.trim();
       adicional = adicional+parseFloat(gastosTransporte);
    }
//   console.log(adicional);
     
    
    var total = (parseFloat(txtSubtotalFVC2) + parseFloat(txtTotalIva) +parseFloat(txtPropina) +parseFloat(adicional));
    $("#txtTotalFVC").val(total.toFixed(2));
    
}


function calculoDescuento(){
    
    var txtDescuento = document.getElementById('txtDescuentoFVC').value/100;
     
    sumaValorTotal0 = document.getElementById('txtSubtotal0FVC').value;
    sumaValorTotal12 = document.getElementById('txtSubtotal12FVC').value;
    
    sumaValorTotal = document.getElementById('txtSubtotalFVC').value;
    sumaCalculoIva = document.getElementById('txtTotalIvaFVC').value;
     
     
    subtotal0ConDesc =sumaValorTotal0 - (sumaValorTotal0*txtDescuento);
    subtotal12ConDesc =sumaValorTotal12 - (sumaValorTotal12*txtDescuento);
     
    document.getElementById('txtSubtotalFVC2').value = subtotal0ConDesc + subtotal12ConDesc;
     
     
     
    document.getElementById('txtTotalIvaFVC').value = subtotal12ConDesc*12/100;
    
    descuentoValor = document.getElementById('txtSubtotalFVC').value - document.getElementById('txtSubtotalFVC2').value;
    document.getElementById('txtDescuentoFVCNum').value = descuentoValor.toFixed(4)

    
    calculoTotal_Ventas();

}




function TipoPago_Educativo(){
 //PAGINA: nuevaTransaccion.php
    $("#div_oculto").load("ajax/tipoPago_Educativo.php",  function(){
        $.blockUI(
		{
                message: $('#div_oculto'),
                overlayCSS: {backgroundColor: '#111'},
                css:{
                '-webkit-border-radius': '3px',
                '-moz-border-radius': '3px',
                'box-shadow': '6px 6px 20px gray',
				top: '5%',
				left: '15%',
				width: '75%',
                position: 'absolute'
                }
        });
    });
     revisarCuentasPagar();
}
function crearCliente(){
 //PAGINA: nuevaTransaccion.php
    $("#div_oculto").load("ajax/nuevoCliente.php",  function(){
        $.blockUI(
		{
            message: $('#div_oculto'),
			overlayCSS: {backgroundColor: '#111'},
            css:{
                '-webkit-border-radius': '10px',
				'-moz-border-radius': '10px',
                background: '', /*  #FFFFFF */
                top: '5%',
                        position: 'absolute',
                        width: '75%',
                     //   left: ($(window).width() - $('.caja').outerWidth())/2
                          left:'15%'
                }
        });
    });
}




function guardarFacVentaEducativo_old(accion){
    
    /* Para obtener el idFormaPago */
    var idFormaPago = document.getElementById("cmbFormaPagoFP").value;
    arrayFormaPago = idFormaPago.split( "*" );
    
    var txtDebeFP = document.getElementById("txtDebeFP").value;
    var txtPagoFP = document.getElementById("txtPagoFP").value;
    var txtCambioFP = document.getElementById("txtCambioFP").value;
    
    
    if(arrayFormaPago[1] == "Ctas. por Cobrar"){
        
        var txtCuotasTP = document.getElementById("txtCuotasTP").value;
        var txtDiasPlazoTP = document.getElementById("txtDiasPlazoTP").value;
        var txtFechaTP = document.getElementById("txtFechaTP").value;   
    }
    
    if(idFormaPago != 0){
        var str = $("#frmFacturaVentaCondominios").serialize();
        //var str2 = $("#frmFormaPago").serialize();

        $.ajax
		({
            url: 'sql/facturaVentaEducat.php',
            type: 'post',
            data: str+"&txtAccion="+accion+"&idFormaPago="+arrayFormaPago[0]+"&txtCuotasTP="+txtCuotasTP+"&txtDiasPlazoTP="+txtDiasPlazoTP+"&txtFechaTP="+txtFechaTP+"&tipoMovimiento="+arrayFormaPago[1],
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



function guardarGuia(accion){
 //   console.log("accion",accion);

    //var SubtotalVta = document.frmFormaPagoVta['txtSubtotalVta'].value;

        var str = $("#frmFacturaVentaCondominios").serialize();
   
        $.ajax
		({
            url: 'sql/facturaVentaEducat.php',
            type: 'post',
            data: str+"&txtAccion="+accion,
            // para mostrar el loadian antes de cargar los datos
            beforeSend: function(){
                //imagen de carga
           //     $("#mensaje1").html("<p align='center'><img src='images/loading.gif' /></p>");
            },
            success: function(data){
                console.log(data);
                	if(data=='SI'){
				        alertify.success("Guia Realizada con exito");
				        fn_cerrar()
                	}
                	if(data=='NO'){
				        alertify.success("Guia Realizada con exito no autorizada");
				        fn_cerrar()
                	}else if(data==2 ){
				        alertify.error("Guia ya registrada");
					    fn_cerrar()
			        }else{
				    alertify.error("Error al guardar, revise los datos");
			}
			
                // document.getElementById("mensaje1").innerHTML=data;
             //   console.log(data);
                // if(data.length == 87){
                //     //document.getElementById("frmServicios").reset();
                // }

            }
        });

    
}


    

      function xml(id){
            window.open('reportes/xmlFactura.php?id='+ id);
       //     window.location.href='reportes/DescargaXML.php?';
    }

// function guardarFacVentaEducativo(accion,modo){
//   // console.log("accion",accion);

//     var SubtotalVta = document.frmFormaPagoVta['txtSubtotalVta'].value;
//     var cmbEst  = document.frmFacturaVentaCondominios['cmbEst'].value;
//     var cmbEmi  = document.frmFacturaVentaCondominios['cmbEmi'].value;
//     var Tipo    = document.frmFacturaVentaCondominios['cmbTipoDocumentoFVC'].value;
//     var modoFacturacion = (modo==300)?'sql/facturaVentaGrupal.php':'sql/facturaVentaEducat.php';
    
//     if(SubtotalVta != 0){
//     var str = $("#frmFacturaVentaCondominios,#frmFormaPagoVta").serialize();
//     // console.log(str);
//         $.ajax
// 		({
//             url: modoFacturacion,
//             type: 'post',
//             data: str+"&txtAccion="+accion+"&modo="+modo,
//             beforeSend: function(){
//             },
//                 success: function(data){
//                 	if(data=='kardexSI' ){
// 				            alertify.success("Felicidades haz realizado una venta más!");
// 				            document.getElementById('frmFacturaVentaCondominios').reset(); 
// 				            numfac(cmbEst,cmbEmi,Tipo);
// 				            fn_cerrar();
// 				            pdfVentas();
// 				    }
// 				    else if(data=='kardex' ){
// 				            alertify.success("Felicidades haz realizado una venta más!");
// 				            document.getElementById('frmFacturaVentaCondominios').reset(); 
// 				            numfac(cmbEst,cmbEmi,Tipo);
// 				            fn_cerrar();
// 				            pdfVentas();
// 			       	}else if(data=='kardexNO' ){
// 				            alertify.success("La factura no se a realizado, intenta otra vez");
// 				            document.getElementById('frmFacturaVentaCondominios').reset(); 
// 				            numfac(cmbEst,cmbEmi,Tipo);
// 				            fn_cerrar();
// 				            pdfVentas();
// 			       	}else if(data=='guia' ){
// 				            alertify.success("Guia Realizada con exito");
// 				            document.getElementById('frmFacturaVentaCondominios').reset(); 
// 				            numfac(cmbEst,cmbEmi,Tipo);
// 				            fn_cerrar();
// 				            pdfVentas();
// 			       	}
// 			       	else if(data=='2' ){
// 				            alertify.error("Venta ya registrada");
// 					        fn_cerrar();
// 					         fn_cerrar();
// 				            pdfVentas();
// 			       	}else if(data=='1 NO' ){
// 				            alertify.success("Felicidades haz realizado una venta más!");
// 				            alertify.error("Factura no Autorizada");
// 					        fn_cerrar();
// 					         fn_cerrar();
// 				            pdfVentas();
// 			       	}
// 			       	else if(data=='1 SI'){
// 				            alertify.success("Felicidades haz realizado una venta más!");
// 				            alertify.error("Factura Autorizada");
// 					        fn_cerrar();
// 					         fn_cerrar();
// 				            pdfVentas();
// 			       	}
			       	
// 			       	else{
// 				            alertify.error(data);
// 		        	}
//                 }
//             });
//         }else{  alert ('Total a pagar deber ser mayor que 0.');   }
//     }
function guardarFacVentaEducativo(accion,modo){

    if( parseFloat(txtPagoFP.value) != parseFloat(txtDebeFP.value) ){
                alertify.error("El total a pagar supera el costo de la venta.");
                return;
            }
        
   
    var SubtotalVta = document.frmFormaPagoVta['txtSubtotalVta'].value;
    var cmbEst  = document.frmFacturaVentaCondominios['cmbEst'].value;
    var cmbEmi  = document.frmFacturaVentaCondominios['cmbEmi'].value;
    var Tipo    = document.frmFacturaVentaCondominios['cmbTipoDocumentoFVC'].value;
    var modoFacturacion = (modo==300)?'sql/facturaVentaGrupal.php':'sql/facturaVentaEducat.php';
    

    var tipo_documento_descripcion = 'venta';
    if( Tipo =='100' ){
        tipo_documento_descripcion = 'nota de venta';
    }

    if(SubtotalVta != 0){
    var str = $("#frmFacturaVentaCondominios,#frmFormaPagoVta").serialize();
    // console.log(str);
        $.ajax
        ({
            url: modoFacturacion,
            type: 'post',
            data: str+"&txtAccion="+accion+"&modo="+modo,
            beforeSend: function(){
            },
                success: function(data){
                let respuesta='';
                
                        try {
                            respuesta = JSON.parse(data);
                            console.log(respuestaObjeto);
                        } catch (error) {
                              respuesta = data;
                            const inicioJSON = respuesta.indexOf('{');
                              const finJSON = respuesta.lastIndexOf('}');
                              
                              if (inicioJSON !== -1 && finJSON !== -1) {
                                const parteValidaJSON = respuesta.substring(inicioJSON, finJSON + 1);
                                
                                 respuesta = JSON.parse(parteValidaJSON);
                               
                              } else {
                                console.log('No se pudo encontrar una parte válida de JSON en la respuesta.');
                              }
                        }

                    try {
                      
             
                    
                        if(respuesta['guardo'] == '1' && respuesta['claveAcceso'] == 'SI'  ){
                            alertify.success("Felicidades haz realizado una "+tipo_documento_descripcion+" más!");
                            document.getElementById('frmFacturaVentaCondominios').reset(); 
                          
                        var hiddenInputs = document.querySelectorAll('#frmFacturaVentaCondominios input[type="hidden"]');
                            
                            // Loop through each hidden input and clear its value
                            hiddenInputs.forEach(function(input) {
                                if (input.name == 'txtContadorFilasFVC' || input.name == 'sesion_id_empresa'|| input.name == 'limiteCF') {
                
              }else{
                  input.value = '0';
              }
                            });
                            numfac(cmbEst,cmbEmi,Tipo);
                            // console.log()
                            fn_cerrar();
                            pdfVentas();
                        }else if( respuesta['guardo'] == '1' &&  respuesta['emision_tipoEmision'] != 'E' ){
                            alertify.success("Felicidades haz realizado una "+tipo_documento_descripcion+" más!");
                            document.getElementById('frmFacturaVentaCondominios').reset();

    var hiddenInputs = document.querySelectorAll('#frmFacturaVentaCondominios input[type="hidden"]');
        
        // Loop through each hidden input and clear its value
        hiddenInputs.forEach(function(input) {
            if (input.name == 'txtContadorFilasFVC' || input.name == 'sesion_id_empresa'|| input.name == 'limiteCF') {
                
              }else{
                  input.value = '0';
              }
        });



                            numfac(cmbEst,cmbEmi,Tipo);
                            fn_cerrar();
                            pdfVentas();
                    }else if(respuesta['guardo'] == '1' && respuesta['claveAcceso'] == 'NO' ){
                            alertify.success("La factura no se a realizado, intenta otra vez");
                           document.getElementById('frmFacturaVentaCondominios').reset();

    var hiddenInputs = document.querySelectorAll('#frmFacturaVentaCondominios input[type="hidden"]');
        
        // Loop through each hidden input and clear its value
        hiddenInputs.forEach(function(input) {
             if (input.name == 'txtContadorFilasFVC' || input.name == 'sesion_id_empresa'|| input.name == 'limiteCF') {
                
              }else{
                  input.value = '0';
              }
        });



                            numfac(cmbEst,cmbEmi,Tipo);
                            fn_cerrar();
                            pdfVentas();
                    }

                    else if( respuesta['venta_registrada'] == 'SI'){
                            alertify.error(tipo_documento_descripcion+" ya registrada");
                            fn_cerrar();
                             fn_cerrar();
                            pdfVentas();
                    }

                    
                    else{
                            alertify.error(data);
                    }
                        
                        // console.log(jsonObject);
                    } catch (error) {
                        // Handle the exception if JSON parsing fails
                        console.error('Error parsing JSON:', error.message);
                        alertify.error(data);
                    }
   actualizar_hora();
                }
            });
        }else{  alert ('Total a pagar deber ser mayor que 0.');   }
    }
function lookup_factura_educ(txtCuenta, cont, accion,est,emi,documento) {
    if(txtCuenta.length == 0) 
	{        
            // Hide the suggestion box.
            $('#suggestions1').hide();
    } 
	else 
	{    
		//alert("antes de factura");

		$.post("sql/factura_buscar_educat.php", {queryString: ""+txtCuenta+"",aux: cont, txtAccion: accion,est:est,emi:emi,documento:documento}, function(data)
		{
//  console.log(data);
			if(data.length > 5)
			{	//le puse 5 xq aunq no haya datos me retorna 3 en data.length  
				              
				arrayPrincipal = data.split("î");//  dividivos el vector principal con el caracter: î
				//alert(arrayPrincipal[1]);
				array = arrayPrincipal[1].split("*");// * dividivos el vector por el numero de filas que arroga la consulta
				//array = data.split("*");// * dividivos el vector por el numero de filas que arroga la consulta

				limite = array.length;
                console.log({limite});
				//contFilas = $('#txtContadorFilas').val();
				contFilas = $('#txtContadorFilasFVC').val();

				// ELIMINA FILAS DEPENDIENDO DEL CONTADOR DE LA CONSULTA
				// for(c=1;c<=contFilas;c++){
				// 	eliminaFilas();
				// }
					
				contFilas1=8;
				contador =contFilas;

				// AGREGA FILAS DEPENDIENDO DEL CONTADOR DE LA CONSULTA

                if(limite>contFilas){
                    let numeroFilasAgregar = limite-contFilas;
                    contador++;
                    for(c=0;c<numeroFilasAgregar;c++){
                        AgregarFilasFacVentaEducativo();
                       
                    }
                   
                }
              


				for(c=1;c<=contador;c++)
				{

					limpiarFilasFacturaVentaEducativo(c);
				}
				// AGREGA LOS DATOS A LOS TXT

				for(i=1; i<=limite-1; i++)
				{
					datos = array[i].split("?");
					//cuenta desde 0
				// 	console.log("datos",datos);
					fecha = datos[3].split(" ");//solo cojemos la fecha, no la hora
					if(accion==12){
                        $('#idPedido').val(datos[1]);
                    }
                    $('#idFactura').val(datos[1]);
                   
					$('#textFechaFVC').val(fecha[0]);
					$('#txtCedulaFVC').val(datos[4]);
					
					$('#txtNombreFVC').val(datos[5]+" "+datos[6]);
					$('#txtTelefonoFVC').val(datos[7]);
 
					$('#txtTotalFVC').val(datos[20]);
					
					$('#txtSubtotal12FVC').val(datos[17]);
					$('#txtSubtotal0FVC').val(datos[18]);
					$('#txtSubtotalFVC').val(datos[19]);
					
					$('#txtCodigoServicio'+i).val(datos[21]);//antes8
					$('#txtDescripcionS'+i).val(datos[9]);					
					$('#txtCantidadS'+i).val(datos[10]);
					$('#cantidadOriginal'+i).val(datos[10]);
					$('#txtValorTotalS'+i).val(datos[12]);
					$('#txtIvaItemS'+i).val(datos[13]);
					$('#txtTotalItemS'+i).val(datos[14]);

                    if(isNaN(datos[31]) || isNaN(datos[32]) ){

                    }else{
                        $('#txtdesc'+i).val(datos[31]);
                    $('#txtdescant'+i).val(datos[31]*datos[10]);
                    }
					 if(datos[11].trim()!='' && datos[31].trim()!='' ){
                        var valorUnitario = parseFloat(datos[11]);
                        let valorUnitarioDescuento = parseFloat(datos[11])- parseFloat(datos[31]);
                        $('#txtValorUnitarioS'+i).val(valorUnitarioDescuento.toFixed(6));
                        $('#txtValorUnitarioShidden'+i).val(valorUnitario.toFixed(6));
                    }
                    
                    
                 
                    $('#txtIdIvaS'+i).val(datos[29]);
                    $('#txtIvaS'+i).val(datos[15]);
                    $('#txtTipoS'+i).val(datos[23]);
                    $('#txtTipoProductoS'+i).val(datos[15]);
                   
                    $('#idbod'+i).val(datos[25]);
                    $('#IVA120'+i).val(datos[24]);
                    $('#cuenta'+i).val(datos[26]);
                    $('#bod'+i).val(datos[27]);
                    $('#bodegaCantidad'+i).val(datos[28]);
                    $('#cantidadEnBodega'+i).val(datos[38]);
                    
                    $('#txtDireccionFVC').val(datos[30]);
                    $('#txtDescripcionFVC').val(datos[36]);
                    $('#txtIdServicio'+i).val(datos[16]);
                    
                   
                   
                   
                    // txtdesc

                    // $('#txtCalculoIvaS'+i).val();
                    // $('#txtCantidadS'+i).focus();
                   
                    $('#txtDescuentoFVCNum').val(datos[33]);

                    $('#txtTotalIvaFVC').val(datos[32]);
					$('#txtCalculoIvaS'+i).val(datos[13]);
					$('#txtSubtotalFVC2').val(datos[19]);
                    $('#txtPropinaFVC').val(datos[34]);
                    $('#textIdClienteFVC').val(datos[35]);
					
					$('#txtContadorAsientosAgregadosFVC').val(limite-1)
					if(document.getElementById("vendedor_id")){
					    document.getElementById("vendedor_id").value=datos[58];
					}
                    let txtDescuentoFVCNum = parseFloat(datos[33]);
                    let txtSubtotalFVC2 = parseFloat(datos[19]);
                
                    let porcentaje =(txtSubtotalFVC2+txtDescuentoFVCNum==0)?0: (100*txtDescuentoFVCNum)/(txtSubtotalFVC2+txtDescuentoFVCNum);
                    $("#txtDescuentoFVC").val(porcentaje); 

                 
                    $('#chofer_id').val(datos[37]);

                    let autorizado= datos[39];
                    
                     let tipo_documento = document.getElementById('cmbTipoDocumentoFVC').value;
                     
                    if(tipo_documento==4){
                         $('#MotivoNota').val(datos[42]);
                          $('#facAn').val(datos[41]);
                        let selectElement = document.getElementById("cmbEst");
                        let selectedOption = selectElement.selectedOptions[0];
                        let displayedValue = selectedOption.text;
                        
                        let selectElement2 = document.getElementById("cmbEmi");
                        let selectedOption2 = selectElement2.selectedOptions[0];
                        let displayedValue2 = selectedOption2.text;
                        
                         document.getElementById("selectfacAn").value = displayedValue+'-'+displayedValue2+'-'+datos[57];
                    }
                     
                      
                    let guardar_normal = document.getElementById('normal');
                    let guardar_modificado = document.getElementById('modificada');
                    let guardar_efectivo = document.getElementById('guardarEfectivo');

                    autorizado = (typeof autorizado === 'undefined')?'0':autorizado;
                    
                         
                    if(autorizado =='' && datos[1]!=''&& documento!=4  ){
                     
                      guardar_normal.style.display = 'none';
                      if(documento==5   ){
                           guardar_modificado.style.display =  'none';
                      }else{
                      guardar_modificado.style.display =  'block';
                      }
                     
                      guardar_efectivo.style.display =  'none';
                    }else if(datos[1].trim()=='' ){
                        guardar_normal.style.display = 'block';
                        guardar_modificado.style.display =  'none';
                        guardar_efectivo.style.display =  'block';
                    }else{
                     guardar_normal.style.display = 'none';
                      guardar_modificado.style.display =  'none';
                      guardar_efectivo.style.display =  'none';
                    }
                    if(document.getElementById('factura_xml')&&  tipo_documento==1){
                          let factura_xml= datos[40];
                          document.getElementById('factura_xml').value=factura_xml;
                          
                          if(factura_xml==1 && (autorizado==''||autorizado=='0') ){
                               let guardar_xml = document.getElementById('facturaxml');
                                guardar_xml.style.display =  'block';
                                 guardar_normal.style.display = 'none';
                      guardar_modificado.style.display =  'none';
                      guardar_efectivo.style.display =  'none';
                          }
                     }else{
                              let guardar_xml = document.getElementById('facturaxml');
                              if(guardar_xml){
                                  guardar_xml.style.display =  'none';
                              }
                     }
					
					// para saber cuantas cuentas estan agregadas
				}
				calculoSubTotal_ventas();
				 cargar_datos_adicionales(datos[1]);
			       if(datos[56]=='08'){
			        vrExportacion(true);
					$('#idtipocliente').val(datos[56]);
					$('#incoterm').val(datos[43]);
					$('#lugarIncoTerm').val(datos[44]);
					$('#paisOrigen').val(datos[45]);
					$('#puertoEmbarque').val(datos[46]);
					$('#puertoDestino').val(datos[47]);
					$('#paisDestino').val(datos[48]);
					$('#paisAdquisicion').val(datos[49]);
					$('#numeroDae').val(datos[50]);
					
						$('#numeroTransporte').val(datos[51]);
					$('#fleteInternacional').val(datos[52]);
					$('#seguroInternacional').val(datos[53]);
					$('#gastosAduaneros').val(datos[54]);
					$('#gastosTransporte').val(datos[55]);
			       }else{
                        if (typeof verExportacion === 'function') {
                        // La función existe, puedes ejecutarla aquí
                        verExportacion(false);
                      } else {
                        // La función no existe
                        console.warn('La función verExportacion no está definida.');
                      }
                    }
			       
			            
			            
             //calcular_total_educ();
            //calcular_haber();     
			//calculoSubTotalFacturaVentaCondominios1();
			
		}
			else
			{
		// alert("No existe esta cuenta.");
			}
		});
    }

}


function calcular_total_educ(){
    var suma =0;
	var totiva=0;
	var total1=0;
	var dec=0;
    for(i=1;i<=8;i++)
	{
	    de = $("#txtValorTotalS"+i).val();
		de1 = $("#txtIvaItemS"+i).val();		
		de2 = $("#txtTotalItemS"+i).val();
	//            txtTotalItemS
	//	alert(de);
	//	alert(de1);
	//	alert(de2);
		
	 if(de == ""){
            de=0;
        }
        else
        {
        suma = suma + parseFloat(de);  
        }
        
        if(de1 == ""){
            de1=0;
        }
        else
        {
        totiva = totiva+ parseFloat(de1);
        }
        
        if(de2 == ""){
            de2=0;
        }
        else
        {
        	total1 = total1 + parseFloat(de2);
        }
		
		// alert(de2);
	//	alert(totiva);
		//alert(total1); 
		if (suma > 0)
		{
		debe=document.getElementById('txtSubtotalFVC').value=(suma).toFixed(4);	
		    
		}
		if (total1 > 0)
		{
		debe1=document.getElementById('txtTotalIvaFVC').value=(totiva).toFixed(4);
		}
		debe2=document.getElementById('txtTotalFVC').value=(total1).toFixed(4);
    //txtTotalFVC
	}
	

}


function anular_venta(numero_cpra, accion,documento,est,emi){
	
	//alert(numero_cpra.value);
	//alert(accion);
	// data: 'numero_cpra='+numero_compra+'&txtAccion='+accion,
                   
	var numero_venta=numero_cpra.value;
    //PAGINA: ajax/formaPago.php
    var respuesta45 = confirm("Seguro desea eliminar este Factura de venta? \nEsta acci\u00f3n eliminara de forma permanente la factura seleccionada");
    if (respuesta45)
	{
      //  alert("antes");            
		$.ajax
		({
		    url: 'sql/facturaVentaEducat.php',
                data: 'numero_venta='+numero_venta+'&txtAccion='+accion+'&documento='+documento+'&est='+est+'&emi='+emi,
                type: 'post',
            success: function(data)
			{
				if(data!="")
					  document.getElementById("mensajeFacturaVentaCondominios").innerHTML=data;
            }
        });
    }
}

//********** FORMA DE PAGO PARA VENTAS ********//
var contador1=1;

function AgregarFilas_FP_Vtas(contador_fp){
    
	contador1=contador_fp;
	
	cadena = "";
	
    cadena = cadena + "<div class='input-group mb-3'>";
    
    cadena = cadena + "<span  class='input-group-text'><a onclick=\"limpiarFilas_FP_Vtas("+contador1+");\" title='Limpiar fila'> <i class='fa fa-times' aria-hidden='true'></i></a>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIdIvaS"+contador1+"' name='txtIdIvaS"+contador1+"'  readonly='readonly'>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIvaS"+contador1+"' name='txtIvaS"+contador1+"'  readonly='readonly'> </span>";
    
    cadena = cadena + "<input  style='margin: 0px; width: 100%;' type='hidden' id='txtCodigoS"+contador1+"' name='txtCodigoS"+contador1+"' class='form-control'   autocomplete='off'   /> ";
    
    cadena = cadena + "<div class='col-lg-4'><input type='search' style='margin: 0px; width:100%;'  class='form-control' autocomplete='off'  id='txtDescPagoS"+contador1+"'  placeholder='Buscar...' name='txtDescPagoS"+contador1+"'  value='' onclick='lookup_FP_Vtas(this.value, "+contador1+", 4);' onKeyUp='lookup_FP_Vtas(this.value, "+contador1+", 4);'   >  <div class='suggestionsBox' id='suggestions20"+contador1+"' style='display: none; margin-top: 0px; '> <div class='suggestionList' id='autoSuggestionsList20"+contador1+"'>  </div> </div>      </div> ";
    
    cadena = cadena + "<input type='hidden' maxlength='10' id='txtTipo1"+contador1+"' name='txtTipo1"+contador1+"' style='margin: 0px; width: 100%; text-align: right; '  class='form-control'   autocomplete='off' >";
    
    cadena = cadena + "<input type='hidden'  style='margin: 0px; width: 100%; text-align: right; '  class='form-control' id='txtPorcentajeS"+contador1+"' name='txtPorcentajeS"+contador1+"' readonly='readonly' onKeyUp=\"calculoTotal_FP_Vtas("+contador1+")\" onclick=\"calculoTotal_FP_Vtas("+contador1+")\" autocomplete='off'>";
	
	cadena = cadena + "<div class='col-lg-2'><input type='text' maxlength='10' id='txtValorS"+contador1+"' name='txtValorS"+contador1+"' style='margin: 0px; width: 100%; text-align: right; '  class='form-control' onKeyUp=\"calculoTotal_FP_Vtas("+contador1+")\;validar_excedente("+contador1+")\" onclick=\"calculoTotal_FP_Vtas("+contador1+")\"  autocomplete='off' > </div>";
    // cadena = cadena + "<div class='col-lg-2'><input type='text' maxlength='10' id='txtValorS"+contador1+"' name='txtValorS"+contador1+"' style='margin: 0px; width: 100%; text-align: right; '  class='form-control' onKeyUp=\"validar_excedente("+contador1+")\" onclick=\"validar_excedente("+contador1+")\"  autocomplete='off' > </div>";
    
    
    cadena = cadena + "<div class='col-lg-1' style='display:none' id='divCuotas"+contador1+"'><input type='text' maxlength='4' id='txtCuotaS"+contador1+"' name='txtCuotaS"+contador1+"' style='margin: 0px; width: 100%; text-align: right; '  class='form-control '   autocomplete='off' onchange=\"listar_cuotas_Cliente("+contador1+")\" > </div>";
    
     cadena = cadena + "<div class='col-lg-1' style='display:none' id='divDiasCuotas"+contador1+"'><input type='text' maxlength='4' id='txtDiasCuotas"+contador1+"' name='txtDiasCuotas"+contador1+"' style='margin: 0px; width: 100%; text-align: right; '  class='form-control '   autocomplete='off' onchange=\"listar_cuotas_Cliente("+contador1+")\" value='30'> </div>";
	
	cadena = cadena + "<div class='col-lg-2' style='display:none' id='divFechas"+contador1+"'><input type='text' maxlength='20' placeholder='fecha' id='txtFechaS"+contador1+"'  name='txtFechaS"+contador1+"' style='margin: 0px; width: 100%; text-align: right; '  class='form-control ' onKeyUp=\"listar_cuotas_Cliente("+contador1+")\" onclick=\"listar_cuotas_Cliente("+contador1+")\"  autocomplete='off' > </div>";   
	
    cadena = cadena + "<div class='col-lg-2' style='display: none;' id='divNumeroRetencion"+contador1+"'><input type='text' maxlength='17' id='txtNumeroRetencionS"+contador1+"' name='txtNumeroRetencionS"+contador1+"' style='margin: 0px; width: 100%; text-align: right; ' class='form-control ' autocomplete='off'> </div>";
    cadena = cadena + "<div class='col-lg-3' style='display: none;' id='divAutorizacion"+contador1+"'><input type='text' maxlength='17' id='txtAutorizacion"+contador1+"' name='txtAutorizacion"+contador1+"' style='margin: 0px; width: 100%; text-align: right; ' class='form-control ' autocomplete='off'> </div>";

	cadena = cadena + "<div class='col-lg-2' style='display:none' id='divDocumento"+contador1+"'><input type='text' maxlength='20' placeholder='Numero' id='txtNumDocumento"+contador1+"'  name='txtNumDocumento"+contador1+"' style='margin: 0px; width: 100%; text-align: right; ' class='form-control border-0 '   autocomplete='off' > </div>";   

	cadena = cadena + "<div class='col-lg-1' hidden=''> <input type='text' maxlength='3' id='txtCuentaP"+contador1+"' name='txtCuentaP"+contador1+"' style='margin: 0px; width: 100%; text-align: right; '  class='form-control'   autocomplete='off' > </div>";
    
    cadena = cadena + "<input type='hidden'  class='form-control' id='formaPagoId"+contador1+"' name='formaPagoId"+contador1+"'  readonly='readonly'>";

    cadena = cadena + "</div>";

    contador1++;
    
    $("#tablita1").append(cadena);

}


function limpiarFilas_FP_Vtas(con){
  //  alert ("entro "+con);
    //if($('#txtIdServicio'+con).val() >= 1)
    //{
    $("#txtIdIvaS"+con).val("0");
    $("#txtIvaS"+con).val("0");
    $("#txtCodigoS"+con).val("");
    $("#txtDescPagoS"+con).val("");
	$("#txtTipo1"+con).val("");
    // $("#txtCantidadS"+con).val("");
    $("#txtPorcentajeS"+con).val("0");
    $("#txtValorS"+con).val("0");
    $("#txtCuentaP"+con).val("");
    calculoTotal_FP_Vtas1(con);
    if(document.getElementById("divAutorizacion"+con)){
        $("#txtAutorizacion"+con).val("");
        document.getElementById("divAutorizacion"+con).style.display = "none";     
    }
    if(document.getElementById("divNumeroRetencion"+con)){
        $("#txtNumeroRetencionS"+con).val("");
        document.getElementById("divNumeroRetencion"+con).style.display = "none";     
    }
    if(document.getElementById("divCuotas"+con)){
        $("#txtCuotaS"+con).val("");
        document.getElementById("divCuotas"+con).style.display = "none";     
    }
    if(document.getElementById("divDiasCuotas"+con)){
        $("#txtDiasCuotas"+con).val("");
        document.getElementById("divDiasCuotas"+con).style.display = "none";     
    }
    if(document.getElementById("divFechas"+con)){
        $("#txtFechaS"+con).val("");
        document.getElementById("divFechas"+con).style.display = "none";     
    }
    let existen=false;
    for(let a=1; a<=4 ; a++){
        if( document.getElementById("divAutorizacion"+a)){
            if( document.getElementById("divAutorizacion"+a).style.display == "block" ){
                existen=true;
                a=4;
            }
        }
       
    }
    if(existen==false){
        if( document.getElementById("labelRetencion") ){
            document.getElementById("labelRetencion").style.display = "none";       
        }
        if(document.getElementById("labelAutorizacion")){
            document.getElementById("labelAutorizacion").style.display = "none";
        }
    }
    let existen_creditos=false;
    for(let a=1; a<=4 ; a++){
        if( document.getElementById("divCuotas"+a) ){
            if( document.getElementById("divCuotas"+a).style.display == "block" ){
                existen_creditos=true;
                a=4;
            }
        } 
    }
    if(existen_creditos==false){
        if( document.getElementById("labelCuotas") ){
            document.getElementById("labelCuotas").style.display = "none";       
        }
        if(document.getElementById("titulo_dias_plazo")){
            document.getElementById("titulo_dias_plazo").style.display = "none";
        }
        if(document.getElementById("labelFecha")){
            document.getElementById("labelFecha").style.display = "none";
        }
        if(document.getElementById("div_listar_cuotasCpra")){
            document.getElementById("div_listar_cuotasCpra").style.display = "none";
        }
        
    }
     
}

function lookup_FP_Vtas(txtNombre, cont, accion) {
//para agregar SERVCIO pagina: nuevaFacturaVenta.php
 //alert(txtNombre);
//alert(cont);
//alert(accion);
 
    if(txtNombre.length == 0) {
        // Hide the suggestion box.
        $('#suggestions20'+cont).hide();
    } else {

        $.post("sql/factura.php", {queryString: txtNombre, cont: cont,  txtAccion: accion}, function(data){
          // console.log("entro: "+data);
			if(data.length >0) {
                $('.suggestionsBox').hide();
                $('#suggestions20'+cont).show();
                $('#autoSuggestionsList20'+cont).html(data);
              //alert("entro: "+data);
            }
        });
    }
} // lookup

function fill10_FP_Vtas(cont, idServicio, cadena){
// console.log(cadena);
    //   	console.log("mensaje");

    setTimeout("$('.suggestionsBox').hide();", 50);

    //thisValue.replace(" ","");
    array = cadena.split("*");

    // este if debe ir antes de asignar a los txt
    if($('#txtCodigo'+cont).val() >= 1){
        
    }else{

    }

    $('#txtCodigoS'+cont).val(array[0]);  
    $('#txtTipo1'+cont).val(array[5]);
    $('#txtDescPagoS'+cont).val(array[1]);
    
	  var porcen=0;

    var txtCuenta1 = parseInt(array[3]);
    $('#formaPagoId'+cont).val(array[7]);
    
    $("#txtCuentaP"+cont).val(txtCuenta1);

//	var tipoS = $("#txtTipoS"+cont).val();
	//var tipoS = $("#txtCodigoS"+cont).val();
	
	var tipoS1 = $("#txtTipo1"+cont).val();
	var sesion_tipo_empresax=parseInt(array[6]);

	if (tipoS1=="4")
	{
	 
	let v_pendiente = document.getElementById("txtCambioFP").value;
    	if(v_pendiente.trim()==''){
    		 v_pendiente = document.getElementById("txtDebeFP").value
    	}
	document.getElementById("txtValorS"+cont).value = v_pendiente;


   
   
        calculoTotal_FP_Vtas(2)
        if(document.getElementById("labelFecha")){
            document.getElementById("labelFecha").style.display = "block";
        }
	   
		document.getElementById("labelCuotas").style.display = "block";
        if(document.getElementById("titulo_dias_plazo")){
            document.getElementById("titulo_dias_plazo").style.display = "block";
        }
        if(document.getElementById("divDiasCuotas"+cont)){
            document.getElementById("divDiasCuotas"+cont).style.display = "block";
        }
  
		document.getElementById("divCuotas"+cont).style.display = "block";
		document.getElementById("divFechas"+cont).style.display = "block";
			
		$('#txtFechaS'+cont).val(array[4]);
		$('#txtCuotaS'+cont).val(1);
		
		listar_cuotas_Cliente(cont);

    }else if (tipoS1=="cheque") {
        $('#nrocpteC'+cont).val("");         
    }else if (tipoS1=="1") {
     
         	    var subtotal = document.getElementById("txtDebeFP").value;

				$('#txtValorS'+cont).val(subtotal);     
				calculoTotal_FP_Vtas();
    } else if (tipoS1==5 || tipoS1==6) {
        // tipoS1.indexOf("retencion")!=-1
        if( document.getElementById("labelRetencion") !=null){
            document.getElementById("labelRetencion").style.display = "block";
            if(document.getElementById("labelAutorizacion")){
                document.getElementById("labelAutorizacion").style.display = "block";
            }
            document.getElementById("divNumeroRetencion"+cont).style.display = "block";
            if(document.getElementById("divAutorizacion"+cont)){
                document.getElementById("divAutorizacion"+cont).style.display = "block";     
            }
            
        }
        
    }
	
	if (tipoS1=="retencion-iva")
	{
		if (porcen>0)
		{
			if (sesion_tipo_empresax==6)
			{
				var subtotal = document.getElementById("txtIva1").value;
				var valor=subtotal*porcen/100;
				$('#txtValorS'+cont).val(valor.toFixed(4));
				calculoTotal_FP_Cpras();			
			}
	    //if (porcen>0){
		}
	}	
	
	if (tipoS1=="retencion-fuente")
	{
		if (porcen>0)
		{
			if (sesion_tipo_empresax==6)
			{
			var subtotal = document.getElementById("txtSubTotal").value;
			var valor=subtotal*porcen/100;
			$('#txtValorS'+cont).val(valor.toFixed(4));
			calculoTotal_FP_Cpras(cont);
			}
			
		}
	    //if (porcen>0){
	}
	
	if (tipoS1=="17")
	{
	    console.log("TRANSFERENCIA");
// cadena = cadena + "<div class='col-lg-2' style='display:none' id='divDocumento'><input type='text' maxlength='20' placeholder='fecha' id='txtNumDocumento"+contador1+"'  name='txtNumDocumento"+contador1+"' style='margin: 0px; width: 100%; text-align: right; '  autocomplete='off' > </div>";   
        
        document.getElementById("labelNumCuenta").style.display = "block";
		document.getElementById("divDocumento"+cont).style.display = "block";
			
		$('#txtFechaS'+cont).val(array[4]);
		$('#txtCuotaS'+cont).val(1);
	}
	
	
	if (tipoS1=="cheque")
	{
     //   $('#nrocpteC'+cont).focus();
    }else 
	{
		$('#txtValorS'+cont).focus();
    }

}
function calculoTotal_FP_Vtas(con){
    var j=0;
	j=con;
	valorTotal = $("#txtValorS"+j).val();
	calculoTotal_FP_Vtas1()
}
function calculoTotal_FP_Vtas1(){
    var sumaValorTotal = 0;
    var valorTotal=0;
	var contador=5;
	
    for(i=1;i<contador;i++){
            valorTotal = $("#txtValorS"+i).val();
            if(valorTotal == ""){ 
                valorTotal=0; 
            }
            else{       
                sumaValorTotal = sumaValorTotal + parseFloat(valorTotal);  
            }
    } 
    if(sumaValorTotal>0){
        document.getElementById('txtSubtotalVta').value=(sumaValorTotal).toFixed(4);
        document.getElementById('txtPagoFP').value=(sumaValorTotal).toFixed(4);
        var debe1=document.getElementById('txtDebeFP').value;
        var cambio1=parseFloat(debe1)-parseFloat(sumaValorTotal);
        document.getElementById('txtCambioFP').value=(cambio1).toFixed(4);
	}    
}


function listar_cuotas_Cliente(cont){
    if(document.getElementById("div_listar_cuotasCpra")){
        	document.getElementById("div_listar_cuotasCpra").style.display = "block";
    }
    
    let dias = 30;
    
    if( document.getElementById("txtDiasCuotas"+cont)){
        dias =  document.getElementById("txtDiasCuotas"+cont).value;  
    }
              
            dias = (dias == '' )?1:dias;
  
   
//	alert("ssssss");
     var j=cont
    cadena = "<table id='tblListadoCuotas' class='table table-bordered' width='100%'>";
    cadena = cadena+"<thead>";
    cadena = cadena+"<tr>";
    cadena = cadena+"<th><strong>Ide</strong></th>";
    cadena = cadena+"<th><strong>Nombre</strong></th>";
    cadena = cadena+"<th><strong>Nro. Fac</strong></th>";
    cadena = cadena+"<th><strong>Cuota</strong></th>";
    cadena = cadena+"<th><strong>Fecha Pago</strong></th>";    
    cadena = cadena+"</tr>";
    cadena = cadena+"</thead>";
    cadena = cadena+"<tbody>";
    
 
    
    //plazo = document.getElementById("txtCuotasTP").value;    
    plazo = $("#txtCuotaS"+j).val();
	//alert(plazo);
	//fecha_inicio = document.getElementById("txtFechaTP").value;    
    fecha_inicio = $("#txtFechaS"+j).val();
    if( document.getElementById("txtNombreFVC") ){
        empleado = document.getElementById("txtNombreFVC").value;   
    }else{
        if( document.getElementById("txtDeudor") ){
            empleado = document.getElementById("txtDeudor").value;   
        }
       
    }
	 
    //empleado = $("#txtFechaS"+j).val();
	//alert(empleado);
	//total_credito = document.getElementById("txtDebeFP").value;
    total_credito = $("#txtValorS"+j).val();
	if( document.getElementById("txtNumeroFacturaFVC") ){
        numero_factura = document.getElementById("txtNumeroFacturaFVC").value;
    }else{
        if( document.getElementById("txtFactura") ){
            numero_factura = document.getElementById("txtFactura").value;
        }
    }
	

    
//var numero_factura=1;
    //valor_cuotas = parseFloat(total_credito / plazo);
    valor_cuotax = $("#txtValorS"+j).val();
	/* alert("valor_cuotax");
	alert(valor_cuotax); */
	valor_cuotas = valor_cuotax/plazo;
	
//	alert("CUOTA");

//	alert(valor_cuotas);
	
    let contador2 = 0;
    //valor_restante = imp_final;
    for(i=0; i<plazo; i++){        
        contador2++;
       
            fecha = sumarDiasAFecha(fecha_inicio, dias);
        
    
        
        
        if(contador2%2==0){            
            cadena = cadena+"<tr class='odd' id='tr1'>";
            cadena = cadena+"<td>"+contador2+"</td>";
            cadena = cadena+"<td>"+empleado+"</td>";
            cadena = cadena+"<td>"+numero_factura+"</td>";
            cadena = cadena+"<td>"+valor_cuotas.toFixed(4)+"</td>";
            cadena = cadena+"<td>"+fecha+"</td>";
            cadena = cadena+"</tr>";
			//alert(cadena);
        }
        if(contador2%2==1){            
            cadena = cadena+"<tr class='odd' id='tr2'>";
            cadena = cadena+"<td>"+contador2+"</td>";
            cadena = cadena+"<td>"+empleado+"</td>";
            cadena = cadena+"<td>"+numero_factura+"</td>";
            cadena = cadena+"<td>"+valor_cuotas.toFixed(4)+"</td>";
            cadena = cadena+"<td>"+fecha+"</td>";
            cadena = cadena+"</tr>";
		//	alert(cadena);
        }
       
            fecha_inicio = fecha;
      

    }    

    cadena = cadena+"</tbody>";
    cadena = cadena+"</table>";
    
    $("#div_listar_cuotasCpra").html(cadena);
	//  div_listar_cuotasCpra
	//  div_listar_cuotasCpra
}
function agregar_motivo_anulacion() {//FRANCIS
    //PAGINA: productos.php
    // alert("MOTIVO ANULACION");
    $("#div_oculto_anular").load("ajax/motivoAnulacion.php", function () {
        $.blockUI({
            message: $('#div_oculto_anular'),
            overlayCSS: {backgroundColor: '#111'},
            css: {
                '-webkit-border-radius': '3px',
                '-moz-border-radius': '3px',
                'box-shadow': '6px 6px 20px gray',
                top: '10%',
                left: '35%',
                position: 'absolute'
            }
        });
    });
}

function anular_venta_anular() {
    var respuesta45 = confirm("Seguro desea anular esta Factura? ");
    if (respuesta45)
    {
        agregar_motivo_anulacion();
    }
}





// function anular_ventasql(motivo_anul, numero_cpra, accion) {
    
function anular_ventasql(accion) {
    
    var str = $("#frmFacturaVentaCondominios").serialize();
    // var str1 = $("#formMotivo").serialize();
    // console.log(str);
    numFac= document.getElementById("txtNumeroFacturaFVC").value;
    motivo= document.getElementById("txtmotivoAnulacion").value;
    cmbEst= document.getElementById("cmbEst").value;
    cmbEmi= document.getElementById("cmbEmi").value;

    $.ajax({
                url: 'sql/facturaVentaEducat.php',
                data: 'txtNumeroFacturaFVC='+numFac+'&txtmotivoAnulacion='+motivo+'&txtAccion='+accion+'&cmbEst='+cmbEst+'&cmbEmi='+cmbEmi,
                type: 'post',
                success: function (data)
                {
                    if (data == 3) {
                        alertify.error("Factura anulada anteriormente");
                    }else if(data == 1){
                         alertify.success("Se anul&oacute; correctamente :)");
                         fn_cerrar()
                        // alertify.confirm("¿Desea ingresar Nota de cr&eacute;dito?",
                        //     function (e) {
                        //         if (e) {
                        //             document.getElementById('cmbTipoDocumentoFVC').value = 4;
                        //              fn_cerrar()
                        //         } else {
                        //              fn_cerrar()
                        //             location.reload();
                        //         }
                        //     }
                           
                        // );
                    }else if(data == 4){
                        alertify.error("No se puede anular la factura por que ya existen abonos realizados.");
                        fn_cerrar()
        
                   }    
                    
                    // if (data == 1) {
                        
                    //     alertify.success("Se anul&oacute; correctamente :)");
                    //     // alertify.confirm("¿Desea ingresar Nota de cr&eacute;dito?",
                    //         // function (e) {
                    //         //     if (e) {
                    //         //         document.getElementById('cmbTipoDocumentoFVC').value = 4;
                    //         //     } else {
                    //         //         // location.reload();
                    //         //     }
                    //         // }
                    //     // );
                        
                    //     
                    // } else {
                    //     alertify.error("Error..");
                    // }

                }
            });
        }
        
        
        
        
function lookup_notaCredito_educ(txtCuenta, cont, accion,est,emi) 
{
	//alert(txtCuenta);
    if(txtCuenta.length == 0) 
	{        
            // Hide the suggestion box.
            $('#suggestions1').hide();
    } 
	else 
	{    
	//	alert("antes de factura");
		//alert(document.frmFacturaVentaCondominios['cmbTipoDocumentoFVC'].value);
// 		if (document.frmFacturaVentaCondominios['cmbTipoDocumentoFVC'].value="4");
// 		{
// 			nfactura=document.frmFacturaVentaCondominios['facAn'].value;
			nfactura=txtCuenta;
		//	accion=4;
			//alert(nfactura);
//			alert("Estoy en nota de credito");
			$.post("sql/factura_buscar_educat.php", {queryString: ""+nfactura+"",aux: cont, txtAccion: accion,est:est,emi:emi}, function(data)
			{
//  console.log(data);
			if(data.length > 5)
			{	//le puse 5 xq aunq no haya datos me retorna 3 en data.length     
					arrayPrincipal = data.split("î");//  dividivos el vector principal con el caracter: î
				//alert(arrayPrincipal[1]);
				array = arrayPrincipal[1].split("*");// * dividivos el vector por el numero de filas que arroga la consulta
				//array = data.split("*");// * dividivos el vector por el numero de filas que arroga la consulta

				limite = array.length;
                console.log({limite});
				//contFilas = $('#txtContadorFilas').val();
				contFilas = $('#txtContadorFilasFVC').val();
											
                

               

				// ELIMINA FILAS DEPENDIENDO DEL CONTADOR DE LA CONSULTA
				// for(c=1;c<=contFilas;c++){
				// 	eliminaFilas();
				// }
					
				contFilas1=8;
				contador =contFilas;
	



				// AGREGA FILAS DEPENDIENDO DEL CONTADOR DE LA CONSULTA

                if(limite>contFilas){
                    let numeroFilasAgregar = limite-contFilas;
                    contador++;
                    for(c=0;c<numeroFilasAgregar;c++){
                        AgregarFilasFacVentaEducativo();
                       
                    }
                   
                }
              


				for(c=1;c<=contador;c++)
				{
					//fn_agregar(0); // envia valor cero para resetear la posicion
					//eliminaFilas
				//	fn_agregar_factura(0);
				    //limpiarFilasFacturaVenta(c);
					limpiarFilasFacturaVentaEducativo(c);
				}
				// AGREGA LOS DATOS A LOS TXT

				for(i=1; i<=limite-1; i++)
				{
					datos = array[i].split("?");
					//cuenta desde 0
				// 	console.log("datos",datos);
					fecha = datos[3].split(" ");//solo cojemos la fecha, no la hora
					$('#textIdClienteFVC').val(datos[15]);
					
					$('#textFechaFVC').val(datos[3]);
					$('#txtCedulaFVC').val(datos[4]);
					
					$('#txtNombreFVC').val(datos[5]+" "+datos[6]);
					$('#txtTelefonoFVC').val(datos[7]);
 
					$('#txtTotalFVC').val(datos[14]);
					
					$('#txtSubtotal12FVC').val(datos[17]);
					$('#txtSubtotal0FVC').val(datos[18]);
					$('#txtSubtotalFVC').val(datos[19]);
					
					$('#txtIdServicio'+i).val(datos[8]);
					$('#txtCodigoServicio'+i).val(datos[8]);
					$('#txtDescripcionS'+i).val(datos[9]);					
					$('#txtCantidadS'+i).val(datos[10]);
					$('#txtValorUnitarioS'+i).val(datos[11]);
					$('#txtValorTotalS'+i).val(datos[12]);
					$('#txtIvaItemS'+i).val(datos[13]);
					$('#txtTotalItemS'+i).val(datos[14]);
					
					$('#txtTipoProductoS'+i).val(datos[15]);
							
							
					$('#textIdClienteFVC').val(datos[20]);
					$('#txtValorUnitarioShidden'+i).val(datos[11]);
					
					$('#txtTotalFVC').val(datos[14]);
					
					$('#txtTotalIvaFVC').val(datos[22]);
					
   		             $('#idbod'+i).val(datos[23]);
                     $('#bod'+i).val(datos[25]);
                     $('#cuenta'+i).val(datos[26]);
                     $('#txtTipoS'+i).val(datos[24]);
					 $('#IVA120'+i).val(datos[27]);
					  $('#txtdesc'+i).val(datos[28]);
					
					$('#txtContadorAsientosAgregadosFVC').val(limite-1)
					// para saber cuantas cuentas estan agregadas
				}
			   cargar_datos_adicionales(datos[1]);          
            calculaCantidadFacturaVentaEducativo(1);
            // calcular_total_educ();
            //calcular_haber();     
			//calculoSubTotalFacturaVentaCondominios1();	
			
			}
			else
			{
		// alert("No existe esta cuenta.");
			}
			});
// 		}
    }
} // lookup

	function guardarNotaCredito(accion){
		//  console.log("accion",accion);
	  
		  var SubtotalVta = document.frmFacturaVentaCondominios['txtTotalFVC'].value;
		  var cmbEst  = document.frmFacturaVentaCondominios['cmbEst'].value;
		  var cmbEmi  = document.frmFacturaVentaCondominios['cmbEmi'].value;
		  var Tipo    = document.frmFacturaVentaCondominios['cmbTipoDocumentoFVC'].value;
		  
		  if(SubtotalVta != 0)
		  {
		  var str = $("#frmFacturaVentaCondominios,#frmFormaPagoVta").serialize();
			  $.ajax
			  ({
				  url: 'sql/facturaVentaEducat.php',
				  type: 'post',
				  data: str+"&txtAccion="+accion,
				  beforeSend: function(){
				  },
					  success: function(data){
					      
					  let textoIndeseado = "No fue posible leer los datos de la firma electrónica (verificar la contraseña)6";
                        let nuevaCadena = data.replace(textoIndeseado, '');
          try {
    let respuesta = JSON.parse(nuevaCadena);
    console.log("RESPUESTA ==>", respuesta); // Corregido para mostrar el objeto respuesta
     if (respuesta['venta_registrada'] == 'SI') {
        alertify.error("Venta ya registrada");
        fn_cerrar();
        // pdfVentas();
    } else if (respuesta['guardo'] == 'SI' && respuesta['venta_registrada'] == 'NO' && respuesta['claveAcceso'] == 'SI'   ) {
        alertify.success("Nota de credito se guardo y autorizó");
        fn_cerrar();
        // pdfVentas();
    }else if (respuesta['guardo'] == 'SI' && respuesta['venta_registrada'] == 'NO' && respuesta['claveAcceso'] == 'NO'    ) {
        alertify.success("Nota de credito se guardo sin autorizar");
        document.getElementById('frmFacturaVentaCondominios').reset();
        numfac(cmbEst, cmbEmi, Tipo);
        fn_cerrar();
        // pdfVentas();
    }else if (respuesta['guardo'] == 'SI' && respuesta['venta_registrada'] == 'NO' && respuesta['claveAcceso'] == 'F'    ) {
        alertify.success("Nota de credito se guardo");
        document.getElementById('frmFacturaVentaCondominios').reset();
        numfac(cmbEst, cmbEmi, Tipo);
        fn_cerrar();
        // pdfVentas();
    }  else {
        alertify.error('No se guardo correctamente.');
    }

} catch (error) {
    console.error('Error parsing JSON:', error.message);
    alertify.error('Error al procesar la respuesta JSON.');
}

					  
					  
					   
					  }
				  });
			  }else{  alert ('Total a pagar deber ser mayor que 0.');   }
		  }

// function guardarNotaCredito(accion){
//   //  console.log("accion",accion);

//     var SubtotalVta = document.frmFacturaVentaCondominios['txtTotalFVC'].value;
//     var cmbEst  = document.frmFacturaVentaCondominios['cmbEst'].value;
//     var cmbEmi  = document.frmFacturaVentaCondominios['cmbEmi'].value;
//     var Tipo    = document.frmFacturaVentaCondominios['cmbTipoDocumentoFVC'].value;
    
//     if(SubtotalVta != 0)
// 	{
//     var str = $("#frmFacturaVentaCondominios,#frmFormaPagoVta").serialize();
//         $.ajax
// 		({
//             url: 'sql/facturaVentaEducat.php',
//             type: 'post',
//             data: str+"&txtAccion="+accion,
//             beforeSend: function(){
//             },
//                 success: function(data){
//                 console.log(data);
//                 	if(data=='kardexSI' ){
// 				            alertify.success("Nota de Credito Realizada con exito");
// 				            document.getElementById('frmFacturaVentaCondominios').reset(); 
// 				            numfac(cmbEst,cmbEmi,Tipo);
// 				            fn_cerrar();
// 				            pdfVentas();
// 				    }
// 				    else if(data=='kardex' ){
// 				            alertify.success("Venta Realizada con exito");
// 				            document.getElementById('frmFacturaVentaCondominios').reset(); 
// 				            numfac(cmbEst,cmbEmi,Tipo);
// 				            fn_cerrar();
// 				            pdfVentas();
// 			       	}else if(data=='2' ){
// 				            alertify.error("Venta ya registrada");
// 					        fn_cerrar();
// 					         fn_cerrar();
// 				            pdfVentas();
// 			       	}else if(data=='1 NO' ){
// 				            alertify.success("Venta registrada");
// 				            alertify.error("Factura no Autorizada");
// 					        fn_cerrar();
// 					         fn_cerrar();
// 				            pdfVentas();
// 			       	}
// 			       	else if(data=='1 SI'){
// 				            alertify.success("Venta registrada");
// 				            alertify.error("Factura Autorizada");
// 					        fn_cerrar();
// 					         fn_cerrar();
// 				            pdfVentas();
// 			       	}			       	
// 			       	else{
// 				            alertify.error(data);
// 		        	}
//                 }
//             });
//         }else{  alert ('Total a pagar deber ser mayor que 0.');   }
//     }

function lookup_guiaFactura(txtCuenta, cont, accion,est,emi) 
{
	//alert(txtCuenta);
    if(txtCuenta.length == 0) 
	{        
            // Hide the suggestion box.
            $('#suggestions1').hide();
    } 
	else 
	{    
	//	alert("antes de factura");
		//alert(document.frmFacturaVentaCondominios['cmbTipoDocumentoFVC'].value);
// 		if (document.frmFacturaVentaCondominios['cmbTipoDocumentoFVC'].value="4");
// 		{
			nfactura=document.frmFacturaVentaCondominios['facafectar'].value
			console.log("nfactura", nfactura);
		//	accion=4;
			//alert(nfactura);
//			alert("Estoy en nota de credito");
			$.post("sql/factura_buscar_educat.php", {queryString: ""+nfactura+"",aux: cont, txtAccion: accion,est:est,emi:emi}, function(data)
			{
 console.log("data",data);
			if(data.length > 5)
			{	//le puse 5 xq aunq no haya datos me retorna 3 en data.length     
				arrayPrincipal = data.split("î");//  dividivos el vector principal con el caracter: î
				//alert(arrayPrincipal[1]);
				array = arrayPrincipal[1].split("*");// * dividivos el vector por el numero de filas que arroga la consulta
				array = data.split("*");// * dividivos el vector por el numero de filas que arroga la consulta

				limite = array.length;
                console.log("limite",limite);
				//contFilas = $('#txtContadorFilas').val();
				contFilas = $('#txtContadorFilasFVC').val();
											

				// ELIMINA FILAS DEPENDIENDO DEL CONTADOR DE LA CONSULTA
				for(c=1;c<=contFilas;c++){
					eliminaFilas();
				}
					
				contFilas1=8;
				contador = limite-1;
				document.getElementById('txtContadorFilasFVC').value = contador;
				// AGREGA FILAS DEPENDIENDO DEL CONTADOR DE LA CONSULTA
				//for(c=1;c<=limite-1;c++)
                $('#txtContadorAsientosAgregadosFVC').val(limite-1)   
				for(c=1;c<=contFilas1-1;c++)
				{
					//fn_agregar(0); // envia valor cero para resetear la posicion
					//eliminaFilas
				//	fn_agregar_factura(0);
				    //limpiarFilasFacturaVenta(c);
					limpiarFilasFacturaVentaEducativo(c);
				}
				// AGREGA LOS DATOS A LOS TXT

				for(i=1; i<=limite-1; i++)
				{
					datos = array[i].split("?");
					//cuenta desde 0
				// 	console.log("datos",datos);
					fecha = datos[3].split(" ");//solo cojemos la fecha, no la hora
					$('#textIdClienteFVC').val(datos[15]);
					
					$('#textFechaFVC').val(fecha[0]);
					$('#txtCedulaFVC').val(datos[4]);
					
					$('#txtNombreFVC').val(datos[5]+" "+datos[6]);
					$('#txtTelefonoFVC').val(datos[7]);
 
					$('#txtTotalFVC').val(datos[14]);
					
					$('#txtSubtotal12FVC').val(datos[17]);
					$('#txtSubtotal0FVC').val(datos[18]);
					$('#txtSubtotalFVC').val(datos[19]);
					
					$('#txtIdServicio'+i).val(datos[8]);
					$('#txtCodigoServicio'+i).val(datos[8]);
					$('#txtDescripcionS'+i).val(datos[9]);					
					$('#txtCantidadS'+i).val(datos[10]);
					$('#txtValorUnitarioS'+i).val(datos[11]);
					$('#txtValorTotalS'+i).val(datos[12]);
					$('#txtIvaItemS'+i).val(datos[13]);
					$('#txtTotalItemS'+i).val(datos[14]);
					$('#textIdClienteFVC').val(datos[20]);
					
					$('#txtTotalFVC').val(datos[14]);
					
					$('#txtTotalIvaFVC').val(datos[22]);
					
					
					// para saber cuantas cuentas estan agregadas
				}
			        
            
            // calcular_total_educ();
            //calcular_haber();     
			//calculoSubTotalFacturaVentaCondominios1();	
			
			}
			else
			{
		// alert("No existe esta cuenta.");
			}
			});
		}
    // }
} // lookup


function guardarProforma(accion){
  //  console.log("accion",accion);

    var SubtotalVta = document.frmFacturaVentaCondominios['txtTotalFVC'].value;
    var cmbEst  = document.frmFacturaVentaCondominios['cmbEst'].value;
    var cmbEmi  = document.frmFacturaVentaCondominios['cmbEmi'].value;
    var Tipo    = document.frmFacturaVentaCondominios['cmbTipoDocumentoFVC'].value;
    
    if(SubtotalVta != 0)
	{
    var str = $("#frmFacturaVentaCondominios,#frmFormaPagoVta").serialize();
        $.ajax
		({
            url: 'sql/facturaVentaEducat.php',
            type: 'post',
            data: str+"&txtAccion="+accion,
            beforeSend: function(){
            },
                success: function(data){
                console.log(data);
                	if(data=='1' ){
				            alertify.success("Proforma guardada con Exito");
				            document.getElementById('frmFacturaVentaCondominios').reset(); 
				            numfac(cmbEst,cmbEmi,Tipo);
				            fn_cerrar();
				            pdfVentas();
				    }else if(data=='3' ){
                        alertify.success("Proforma actualizada con Exito");
                        pdfProforma();
                        document.getElementById('frmFacturaVentaCondominios').reset(); 
                        numfac(cmbEst,cmbEmi,Tipo);
                        fn_cerrar();
                     
                }else{
				                alertify.error("Proforma no se pudo guardar");
				    }
                }
            });
        }else{  alert ('Total a pagar deber ser mayor que 0.');   }
    }
    
// function pdfVentas(){
//     accion = 9;
//     $.ajax
// 		({
//             url: 'sql/facturaVentaEducat.php',
//             type: 'post',
//             data: "txtAccion="+accion,
//             beforeSend: function(){
//             },
//             success: function(data){
//                  let response = JSON.parse(data);
               
//                  let formato=response.formato;
//                 let idVenta=response.id;
//                  let documento=response.tipo_documento;
                

//             if(formato==1){
//                     if(documento==1||documento==41||documento==100){
//                         formatoF = 'rptFacturas_detallado.php';
//                     }else if(documento==5){
//                         formatoF = 'rptProforma_detallado.php';
//                     }
//             }
//             if(formato==2){
//                 if(documento==1||documento==41||documento==100){
//                       formatoF = 'rptFacturas_detalladoMini.php' ;
//                 }else if(documento==5){
//                     formatoF = 'rptProforma_detallado.php';
//                 }
//             }
//             if(formato==3){
//                     if(documento==1||documento==41||documento==100){
//                       formatoF = 'rptFacturas_detalladoA5.php' ;
//                 }else if(documento==5){
//                   formatoF = 'rptProforma_detallado.php';
//                 }
                    
                    
//             }
    

//  miUrl = "reportes/"+formatoF+"?txtComprobanteNumero="+idVenta;
//  window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
                
  
                        
                        
                        
//             }
//         });
// }
        function pdfVentas(){
    accion = 9;
    $.ajax
		({
            url: 'sql/facturaVentaEducat.php',
            type: 'post',
            data: "txtAccion="+accion,
            beforeSend: function(){
            },
            success: function(data){
                let response = JSON.parse(data);

                let formato=response.formato;
                let idVenta=response.id;
                let documento=response.tipo_documento;
                let impresion= response.impresion;

                if(formato==1){
                    if(documento==1||documento==41||documento==100){
                        formatoF = 'rptFacturas_detallado.php';
                    }else if(documento==5){
                        formatoF = 'rptProforma_detallado.php';
                    }
                }
                if(formato==2){
                    if(documento==1||documento==41||documento==100){
                           formatoF = 'rptFacturas_detalladoMini.php' ;
                    }else if(documento==5){
                        formatoF = 'rptProforma_detallado.php';
                    }
                }
                if(formato==3){
                        if(documento==1||documento==41||documento==100){
                           formatoF = 'rptFacturas_detalladoA5.php' ;
                    }else if(documento==5){
                       formatoF = 'rptProforma_detallado.php';
                    }
                }
                 miUrl = "reportes/"+formatoF+"?txtComprobanteNumero="+idVenta;
                 
            if(impresion==0){
                
 window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
                
  
            }else{
                fetch(miUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la descarga del PDF');
            }
            return response.blob();
        })
        .then(blob => {
            // Crear un FormData para enviar el archivo al servidor
            const formData = new FormData();
            formData.append('archivo', blob, `comprobante_${idVenta}.pdf`); // Nombre del archivo y campo en FormData

            // Hacer una solicitud POST al script PHP en el servidor para guardar el archivo
            fetch('../sql/guardar_pdf.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al guardar el PDF en el servidor');
                }
                
                console.log('PDF guardado correctamente en el servidor');
                imprimirPDF('comprobante_' + idVenta);

            })
            .catch(error => {
                console.error('Error en la solicitud de guardar PDF:', error);
            });
        })
        .catch(error => {
            console.error('Error en la descarga del PDF:', error);
        });
            }
            
    


                        
                        
                        
            }
        });
}
function imprimirPDF(nombreArchivo) {
    const urlPDF = `../tickets/${nombreArchivo}.pdf`;
console.log('urlPDF',urlPDF);
    printJS(urlPDF);
}

   function guardarFacVentaEducativoEfectivo(modo){
    // console.log("accion",accion);
    let limite = parseFloat(document.getElementById('limiteCF').value);
    let totalVenta = parseFloat(document.getElementById('txtTotalFVC').value);
    let cedula =  document.getElementById('txtCedulaFVC').value;
    if(totalVenta>limite &&  cedula==9999999999999){
        alertify.error('Se ha superado el limite del total para registrar como cliente final, son necesarios los datos del cliente.');
        return;
    }
    
     var SubtotalVta = document.frmFacturaVentaCondominios['txtTotalFVC'].value;
     var cmbEst  = document.frmFacturaVentaCondominios['cmbEst'].value;
     var cmbEmi  = document.frmFacturaVentaCondominios['cmbEmi'].value;
     var Tipo    = document.frmFacturaVentaCondominios['cmbTipoDocumentoFVC'].value;
     var modoFacturacion = 'sql/facturaVentaEducat.php';
     
     if(SubtotalVta != 0){
     var str = $("#frmFacturaVentaCondominios").serialize();
     // console.log(str);
         $.ajax
         ({
             url: modoFacturacion,
             type: 'post',
             data: str+"&txtAccion=15&modo="+modo,
             beforeSend: function(){
             },
                 success: function(data){
                     if(data=='kardexSI' ){
                             alertify.success("Venta Realizada con exito");
                             document.getElementById('frmFacturaVentaCondominios').reset(); 
                             numfac(cmbEst,cmbEmi,Tipo);
                             fn_cerrar();
                             pdfVentas();
                     }
                     else if(data=='kardex' ){
                             alertify.success("Venta Realizada con exito");
                             document.getElementById('frmFacturaVentaCondominios').reset(); 
                             numfac(cmbEst,cmbEmi,Tipo);
                             fn_cerrar();
                             pdfVentas();
                        }else if(data=='guia' ){
                             alertify.success("Guia Realizada con exito");
                             document.getElementById('frmFacturaVentaCondominios').reset(); 
                             numfac(cmbEst,cmbEmi,Tipo);
                             fn_cerrar();
                             pdfVentas();
                        }
                        else if(data=='2' ){
                             alertify.error("Venta ya registrada");
                             fn_cerrar();
                              fn_cerrar();
                             pdfVentas();
                        }else if(data=='1 NO' ){
                             alertify.success("Venta registrada");
                             alertify.error("Factura no Autorizada");
                             fn_cerrar();
                              fn_cerrar();
                             pdfVentas();
                        }
                        else if(data=='1 SI'){
                             alertify.success("Venta registrada");
                             alertify.error("Factura Autorizada");
                             fn_cerrar();
                              fn_cerrar();
                             pdfVentas();
                        }
                        
                        else{
                             alertify.error(data);
                     }
                 }
             });
         }else{  alert ('Total a pagar deber ser mayor que 0.');   }
     }
     
     
     function cruzarAnticipo(funcion,saldo){
	alertify.confirm('Anticipo', 'CRUZAR CON ANTICIPO?', 
		function(){ 
	   eval(funcion);
	   txtValorS1.value=saldo;
       var inputElement = document.getElementById("txtValorS1");
inputElement.addEventListener("input", function() {
  console.log('Se ejecutó');
  let txtDescPagoS1 = document.getElementById("txtDescPagoS1").value;
  let txtTipo11 = document.getElementById("txtTipo11").value;
  let txtValorS1 = inputElement.value;
  
  if (txtDescPagoS1.trim() !== '' && txtTipo11 === '18') {
    if (parseFloat(txtValorS1 ) > parseFloat(saldo)) {
        document.getElementById("txtValorS1").value= saldo;
      alertify.error('El valor ingresado no puede ser mayor a la suma de saldos de las cuentas por pagar del cliente.');
    }
  }
});
	  
		}
		, function(){ alertify.error('Se cancelo')});
}

function revisarCuentasPagar(){
    let idFactura = document.getElementById("idFactura").value;
    let idCliente =document.getElementById("textIdClienteFVC").value; 

	$.ajax(
	{
		url: 'sql/habitaciones.php',
		data: "txtAccion=16&idCliente="+idCliente+"&idFactura="+idFactura,
		type: 'post',
		success: function(data)	{
		    let json =  JSON.parse(data);
            if(json.cobrosPagos>0){
                alertify.error('La venta no se puede modificar por que tiene canceladas cuentas por pagar');
                fn_cerrar();
                return;
            }
		    if(json.saldo>0 && json.cobrosPagos==0){
		     cruzarAnticipo(json.funcion,json.saldo)
		    }
           
          
		    
		}
	});
}

function sumarDiasAFecha(fecha, dias){
    dias= parseInt(dias);
    let partesFecha = fecha.split("-");
    let anio = parseInt(partesFecha[0]);
    let mes =  parseInt(partesFecha[1])-1;
    let dia =  parseInt(partesFecha[2]);

    let nuevaFecha = new Date(anio, mes, dia);

    nuevaFecha.setHours(0,0,0,0);
    nuevaFecha.setTime(nuevaFecha.getTime()+ (dias*24*60*60*1000))
    
    let anio2 = nuevaFecha.getFullYear();
    let mes2 = ("0"+(nuevaFecha.getMonth() +1 )).slice(-2);
    let dia2 = ("0" + nuevaFecha.getDate()).slice(-2);

    let fechaFormateada = anio2 + "-" + mes2 + "-" + dia2;
    return fechaFormateada;
}

function llenarProducto(cont, idServicio,tipoCompras,num){

try {

let variableJson = lista_productos_tabla;
//  console.log("variableJson==>", variableJson);
//  console.log("num==>", num); 
            // lista_productos_tabla = variableJson;
          // este if debe ir antes de asignar a los txt
          
if($('#txtIdServicio'+cont).val() >= 1){
    
    // cuando no usa el boton limpiar significa que
    // si hay cuenta agregada en la fila y solo esta remplazando por otra cuenta
    // ya no vuelve a sumar cuantas cuentas estan agregadas
    
}else{
    // cuando usa el boton limpiar
    // significa que ha quitado la cuenta y cunado agrega una nueva suma cuantas cuentas estan agregadas
    sumaAsientosAgregados =  $('#txtContadorAsientosAgregadosFVC').val();
    sumaAsientosAgregados ++;
    $('#txtContadorAsientosAgregadosFVC').val(sumaAsientosAgregados);
}

    
 
        if( variableJson['fecha_caducidad'] ){
            if(variableJson['fecha_caducidad'][num]!= null && variableJson['productos_tipos_compras'][num]=='1'){
                var today = new Date();
                var year = today.getFullYear();
                var month = String(today.getMonth() + 1).padStart(2, '0'); 
                var day = String(today.getDate()).padStart(2, '0');
                var formattedDate = year + '-' + month + '-' + day;
                console.log(formattedDate)
                
                let fecha_caducidad =variableJson['fecha_caducidad'][num] ;
                var d1 = new Date(formattedDate);
                var d2 = new Date(fecha_caducidad);
                if  (d1.getTime() > d2.getTime()) {
                    var respuesta45 = confirm("Esta seguro de vender un producto caducado ?. El producto caduco el :"+fecha_caducidad);
                    if (!respuesta45)
                    {
                        return;
                    }
                }
            }
        }

    $('#txtIdServicio'+cont).val(variableJson['productos_id_producto'][num]);
    
    $('#txtCodigoServicio'+cont).val(variableJson['productos_codigo'][num]);
    $('#txtDescripcionS'+cont).val(variableJson['productos_nombre'][num]);
    var valorUnitario = parseFloat(variableJson['productos_precio1'][num]);
    // $('#txtIdIvaS'+cont).val(variableJson['id_iva'][num]);
    $('#txtValorUnitarioS'+cont).val(valorUnitario.toFixed(6));
    $('#txtValorUnitarioShidden'+cont).val(valorUnitario.toFixed(6));
    if(document.getElementById('precioOrignal'+cont) ){
        $('#precioOrignal'+cont).val(valorUnitario.toFixed(6));
    }

    $('#txtIvaS'+cont).val(variableJson['iva'][num]);
    
    console.log("iva",variableJson['iva'][num]);
    
    $('#txtTipoProductoS'+cont).val(variableJson['iva'][num]);
    $('#txtTipoS'+cont).val(variableJson['productos_tipos_compras'][num]);
    $('#IVA120'+cont).val(variableJson['productos_iva'][num]);
    $('#idbod'+cont).val(variableJson['centro_id'][num]);
    $('#cuenta'+cont).val(variableJson['productos_id_cuenta'][num]);
    $('#bod'+cont).val(variableJson['centro_descripcion'][num]);
    $('#bodegaCantidad'+cont).val(variableJson['bodega_idBodega'][num]);
    $('#cantidadEnBodega'+cont).val(variableJson['cantidadEnBodega'][num]);
    $('#id_proyecto'+cont).val(variableJson['productos_proyecto'][num]);
   

    $('#txtdesc_mostrar'+cont).val('');
    $('#txtdesc'+cont).val('0');
    $('#txtValorTotalConIvaS'+cont).val('');
    $('#txtCantidadS'+cont).val(1);
    $('#txtCalculoIvaS'+cont).val();    
    $('#txtCantidadS'+cont).focus(); 
    $('#txtCantidadS'+cont).trigger("click")
    
 
    
          } catch (error) {

            console.error(error);
          
          }
 $('#suggestions10'+cont).hide();
}

function validarCantidad(valor, stockMaximo,cantidadOriginal=0){
   
        let cantidadIngresada = valor.value;
        if( cantidadIngresada.charAt(cantidadIngresada.length - 1) == '.'){
        return;
        }
    if(stockMaximo !='' && cantidadIngresada!=''){
        let cadena = '';
         let valores = 0;
         let cantidadValores =1;
        if( document.getElementById("idClientesSelecionados") ){
            cadena = $("#idClientesSelecionados").val();
            valores = cadena.split(',');
            cantidadValores = valores.length;
        }
         
         let cantidadClientes =(cantidadValores==1)?cantidadValores:cantidadValores-1;
         let valorIngresado = parseFloat(cantidadIngresada) * parseFloat(cantidadClientes);
         let valorMaximo = parseFloat(stockMaximo);
        let valorAdicional=0;
        if(document.getElementById("idPedido") ){
            if(document.getElementById("idPedido").value!=''){
                valorAdicional = cantidadOriginal;
            }
        }
        if(document.getElementById("idFactura") ){
            if(document.getElementById("idFactura").value!=''){
                valorAdicional = cantidadOriginal;
            }
        }
        valorMaximo = parseFloat(valorMaximo)+parseFloat(valorAdicional);
        
         if(valorIngresado>valorMaximo){
              let v= parseFloat(valorMaximo/cantidadClientes);
             valor.value=v;
            
                 alertify.error('La cantidad ingresada supera el stock actual:'+v+' del producto en la bodega y lote selecionado.')
                 valor.value=parseFloat(valorMaximo/cantidadClientes);
             }
    }

}

function pruebaValidarCantidad(){
    let valor = txtCantidadS1.value;
    let stockMaximo=cantidadEnBodega1.value;
    let tipos_compras = txtTipoS1.value;
     if(stockMaximo !='' && tipos_compras=='1'){
            let cadena = $("#idClientesSelecionados").val();
            let valores = cadena.split(',');
            let cantidadValores = valores.length;
            let cantidadClientes =(cantidadValores==1)?cantidadValores:cantidadValores-1;
            let valorIngresado = parseFloat(valor.value) * parseFloat(cantidadClientes);
            let valorMaximo = parseInt(stockMaximo);
    
            if(valorIngresado>valorMaximo){
                    alertify.error('La cantidad ingresada supera el stock actual del producto en la bodega selecionada.')
                    valor.value=parseInt(valorMaximo/cantidadClientes);
                }
        }
}
