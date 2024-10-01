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

var contador=1;
function limpiarFilasPedido(con){
    //alert ("entro "+con);
    if($('#txtIdServicio'+con).val() >= 1)
    {
        $("#txtIdIvaS"+con).val("0");
        $("#txtIvaS"+con).val("0");
        $("#txtIdServicio"+con).val("0");
        $("#txtCodigoServicio"+con).val("");
        $("#txtDescripcionS"+con).val("");
        $("#txtCantidadS"+con).val("");
        //$("#txtPrecioS"+con).val("");
        $("#txtCalculoIvaS"+con).val("0");
        $("#txtValorUnitarioS"+con).val("0");
        $("#txtValorTotalS"+con).val("0");

        $("#txtSubtotalFVC"+con).val("0");
        $("#txtDescuentoFVC"+con).val("0");
        $("#txtTotalIvaFVC"+con).val("0");
        $("#txtOtrosFVC"+con).val("0");
        $("#txtTotalFVC"+con).val("0");
		$("#txtCuentaS"+con).val("");
		
        calculaCantidadFacturaVentaCondominios(con);
        asientosQuitadosFVC();

    }else{

    }
}

function limpiarFilasPedidos(con){
    //alert ("entro "+con);
    //if($('#txtIdServicio'+con).val() >= 1)
    //{
        $("#txtIdIvaS"+con).val("0");
        $("#txtIvaS"+con).val("0");
        $("#txtIdServicio"+con).val("0");
        $("#txtCodigoServicio"+con).val("");
        $("#txtDescripcionS"+con).val("");
        $("#txtCantidadS"+con).val("");
        //$("#txtPrecioS"+con).val("");
        $("#txtCalculoIvaS"+con).val("0");
        $("#txtValorUnitarioS"+con).val("0");
        $("#txtValorTotalS"+con).val("0");

		$("#txtIvaItemS"+con).val("0");
		$("#txtTotalItemS"+con).val("0");



        $("#txtSubtotalFVC"+con).val("0");
        $("#txtDescuentoFVC"+con).val("0");
        $("#txtTotalIvaFVC"+con).val("0");
        $("#txtOtrosFVC"+con).val("0");
        $("#txtTotalFVC"+con).val("0");
		$("#txtCuentaS"+con).val("");
		
        calculaCantidadFacturaVentaCondominios(con);
        asientosQuitadosFVC();

    //}else{

    //}
}
// function restarCantidadPedidos(con){
   
//   if( $("#txtCantidadS"+con).val()>1){
//       let  cantidad = Number($("#txtCantidadS"+con).val()) - 1;
//         $("#txtCantidadS"+con).val(cantidad);
//   }else{
//       let id = $("#txtIdServicio"+con).val();
       
//         $("#txtIdIvaS"+con).val("0");
//         $("#txtIvaS"+con).val("0");
//         $("#txtIdServicio"+con).val("0");
//         $("#txtCodigoServicio"+con).val("");
//         $("#txtDescripcionS"+con).val("");
//         $("#txtCantidadS"+con).val("");
//         //$("#txtPrecioS"+con).val("");
//         $("#txtCalculoIvaS"+con).val("0");
//         $("#txtValorUnitarioS"+con).val("0");
       
//         $("#txtValorUnitarioShidden"+con).val("0");
//         $("#txtValorTotalS"+con).val("0");

// 		$("#txtIvaItemS"+con).val("0");
// 		$("#txtTotalItemS"+con).val("0");
//         $("#IVA120"+con).val("");


//         $("#txtSubtotalFVC"+con).val("0");
//         $("#txtDescuentoFVC"+con).val("0");
//         $("#txtTotalIvaFVC"+con).val("0");
//         $("#txtOtrosFVC"+con).val("0");
//         $("#txtTotalFVC"+con).val("0");
// 		$("#txtCuentaS"+con).val("");
		
//         calculaCantidadFacturaVentaEducativo(con);
//         id = id.toString();
//          let index = codigos.indexOf(id);
//          codigos[index]='';
//          console.log(codigos);
//   }

// }

function restarCantidadPedidos(con){
   
   if( $("#txtCantidadS"+con).val()>1){
       let  cantidad = Number($("#txtCantidadS"+con).val()) - 1;
        $("#txtCantidadS"+con).val(cantidad);
   }else{

    let id = $("#txtIdServicio" + con).val();
    let bodega = $("#bodegaCantidad" + con).val();
    let lote = $("#txtLoteS" + con).val();
        codigos = codigos.filter(item => !(item.id === id.toString() && item.bodega === bodega.toString() && item.lote === lote.toString()));
   
         console.log({codigos});
      
       
        $("#txtIdIvaS"+con).val("0");
        $("#txtIvaS"+con).val("0");
        $("#txtIdServicio"+con).val("0");
        $("#txtCodigoServicio"+con).val("");
        $("#txtDescripcionS"+con).val("");
        $("#txtCantidadS"+con).val("");
        //$("#txtPrecioS"+con).val("");
        $("#txtCalculoIvaS"+con).val("0");
        $("#txtValorUnitarioS"+con).val("0");
       
        $("#txtValorUnitarioShidden"+con).val("0");
        $("#txtValorTotalS"+con).val("0");

        $("#txtIvaItemS"+con).val("0");
        $("#txtTotalItemS"+con).val("0");
        $("#IVA120"+con).val("");


        $("#txtSubtotalFVC"+con).val("0");
        $("#txtDescuentoFVC"+con).val("0");
        $("#txtTotalIvaFVC"+con).val("0");
        $("#txtOtrosFVC"+con).val("0");
        $("#txtTotalFVC"+con).val("0");
        $("#txtCuentaS"+con).val("");
        
        calculaCantidadFacturaVentaEducativo(con);
   
   }

}
var codigos =[];
// function AgregarFilasPedido(id='' , producto='', precio='', codigo='', idIva='',iva='', tiposCompras ='', centroId='', productoIva='', idCuenta='', centroDescripcion='', bodegaId=''){
//      let index = codigos.indexOf(id);
//      let contador2 =($('#txtContadorAsientosAgregadosFVC').val()=='')?1: parseInt($('#txtContadorAsientosAgregadosFVC').val())+1;

//     if(index>=0){
//          let fila = index + 1;
//         cantidad = Number($("#txtCantidadS"+fila).val()) + 1;
//         $("#txtCantidadS"+fila).val(cantidad);
//         calculaCantidadFacturaVentaEducativo(fila);
//     }else{

//         codigos.push(id);
//     cadena = "";
//     cadena = cadena + "<div id='filaPedido"+contador2+"' class='form-group '>";
//     cadena = cadena + "<div class='input-group mt-1 bg-light'>";
    
//     cadena = cadena + " <a onclick=\"restarCantidadPedidos("+contador2+");\" title='Limpiar fila' class='btn btn-outline-secondary fa fa-window-close'></a>     ";  

//     cadena = cadena + "<input type='hidden' id='txtIdServicio"+contador2+"' name='txtIdServicio"+contador2+"' value='"+id+"' >     <input   type='hidden' id='txtCodigoServicio"+contador2+"' name='txtCodigoServicio"+contador2+"' class='form-control '   autocomplete='off' value='"+codigo+"' />  ";
    
//     cadena = cadena + "<input type='search' style='width:25% ' class='form-control'  autocomplete='off'  id='txtDescripcionS"+contador2+"'  name='txtDescripcionS"+contador2+"'  value='"+producto+"'  ><a onclick='detalleAdicional("+contador2+");' class='btn btn-outline-secondary'><i class='fas fa-plus'></i></a>";

//     cadena = cadena + "<input type='hidden' maxlength='10' id='bod"+contador2+"'  name='bod"+contador2+"'  class='form-control   bg-white'  onKeyUp=\"lookup_cpra_bod(this.value, "+contador2+", 7)\"  value='"+centroDescripcion+"' >";

//     cadena = cadena + "<input type='text' maxlength='10' style='text-align: center; ' id='txtCantidadS"+contador2+"' name='txtCantidadS"+contador2+"' class='form-control ' onKeyUp=\"calculaCantidadFacturaVentaEducativo("+contador2+")\" onclick=\"calculaCantidadFacturaVentaEducativo("+contador2+")\"  autocomplete='off' value='1' >";
    
//     cadena = cadena + "<input type='text' style='text-align: right; ' class='form-control  ' id='txtdesc"+contador2+"' name='txtdesc"+contador2+"' onKeyUp=\"calculaValorunitariodescuento("+contador2+")\" onclick=\"calculaValorunitariodescuento("+contador2+")\" autocomplete='off' value='0' >";
  
//     cadena = cadena + "<select type='hidden' class='form-control bg-white' id='cambiarPrecio"+contador2+"' name='cambiarPrecio"+contador2+"'  onchange=\"cambiarPrecios(\'"+contador2+"\',txtIdServicio"+contador2+".value, this.value );calculaCantidadFacturaVentaEducativo(\'"+contador2+"\' )\" > <option value='precio1'>1</option> <option value='precio2'>2</option> <option value='precio3'>3</option> <option value='precio4'>4</option> <option value='precio5'>5</option> <option value='precio6'>6</option> </select>";

//     cadena = cadena + "<input type='text' style=' text-align: right; ' class='form-control  ' id='txtValorUnitarioShidden"+contador2+"' name='txtValorUnitarioShidden"+contador2+"' onKeyUp=\"calculaCantidadFacturaVentaEducativo("+contador2+")\" onclick=\"calculaCantidadFacturaVentaEducativo("+contador2+")\" autocomplete='off'  value='"+precio+"'>";

//     cadena = cadena + "<input type='text' style='text-align: right; ' class='form-control  ' id='txtValorUnitarioS"+contador2+"' name='txtValorUnitarioS"+contador2+"' onKeyUp=\"calculaCantidadFacturaVentaEducativo("+contador2+")\" onclick=\"calculaCantidadFacturaVentaEducativo("+contador2+")\" autocomplete='off' value='"+precio+"' >";
    
//     cadena = cadena + "<input type='text' style='text-align: right; ' class='form-control   bg-white' id='txtValorTotalS"+contador2+"' name='txtValorTotalS"+contador2+"'  readonly='readonly'>";

// 	cadena = cadena + "<input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIvaS"+contador2+"' name='txtIvaS"+contador2+"'  readonly='readonly' value='"+iva+"'> <input type='hidden'  class='text_input' id='txtTipoS1"+contador2+"' name='txtTipoS1"+contador2+"'  readonly='readonly' value='"+tiposCompras+"' > </div>";

//     cadena = cadena + "<input type='hidden' id='bodegaCantidad"+contador2+"'  name='bodegaCantidad"+contador2+"'   class='form-control  border-0 bg-white' value='"+bodegaId+"'  >";
//     cadena = cadena + "<input type='hidden' style='margin: 0px; width: 20%; text-align: right;' class='form-control  ' id='txtTipoS"+contador2+"' name='txtTipoS"+contador2+"' value='"+tiposCompras+"'  readonly='readonly'> ";
//     cadena = cadena + "<input type='hidden' style='margin: 0px; width: 25%; text-align: right;' class='form-control ' id='txtTipoProductoS"+contador2+"' name='txtTipoProductoS"+contador2+"' value='"+iva+"' readonly='readonly'> ";

//     cadena = cadena + "<input type='hidden' style='margin: 0px; width: 100%; text-align: right; ' class='form-control  ' id='txtdescant"+contador2+"' name='txtdescant"+contador2+"' onKeyUp=\"calculaValorunitariodescuento("+contador2+")\" onclick=\"calculaValorunitariodescuento("+contador2+")\" autocomplete='off'>";
// 	cadena = cadena + "<input type='hidden' id='cuenta"+contador2+"'  name='cuenta"+contador2+"'   class='form-control  border-0 bg-white' value='"+idCuenta+"' >";
// 	cadena = cadena + "<input type='hidden' id='idbod"+contador2+"'  name='idbod"+contador2+"'   class='form-control  border-0 bg-white' value='"+centroId+"' >";
//     cadena = cadena + "<input type='hidden' id='IVA120"+contador2+"'  name='IVA120"+contador2+"'   class='form-control  border-0 bg-white' value='"+productoIva+"' >";
    
// 	cadena = cadena + "<input type='hidden'  class='form-control' id='txtCalculoIvaS"+contador2+"' name='txtCalculoIvaS"+contador2+"'  readonly='readonly'>";
// 	cadena = cadena + "<input type='hidden'  class='text_input' id='txtIvaItemS"+contador2+  "' name='txtIvaItemS"+contador2+   "'   >";
// 	cadena = cadena + "<input type='hidden'  class='text_input' id='txtTotalItemS"+contador2+"' name='txtTotalItemS"+contador2+ "'  >"; 
// 	cadena = cadena + "<input type='hidden'  class='text_input' id='txtCuentaS"+contador2+"' name='txtCuentaS"+contador2+"'  readonly='readonly'>";
//     cadena = cadena + "</div>";
    
//     cadena = cadena + "<div id='detallePedido" + contador2 + "' class='input-group mt-1'>";
//     cadena = cadena + "<textarea type='text' class='form-control my-1 w-25' autocomplete='off'  id='txtdetalle2" + contador2 + "'  name='txtdetalle2" + contador2 + "'  value='' placeholder='Detalle Adicional' style='display:none' maxlength='300' ></textarea>";
//     cadena = cadena + "<input type='hidden'  class='form-control my-1 w-25' id='productoOnIva" + contador2 + "' name='productoOnIva" + contador2 + "'  >";
//     cadena = cadena + "</div>";

//     cadena = cadena + "</div>";
    
//     $("#tblBodyFacVentaCondominios ").append(cadena);
      
//     let contadorActual =contador2;
   
//     document.getElementById('txtContadorFilasFVC').value=contador2;
//     document.getElementById('txtContadorAsientosAgregadosFVC').value=contador2;
//     contador=parseInt(contador2)+1;
  
//     calculaCantidadFacturaVentaEducativo(contadorActual);
     
//     }

    
// }

// function AgregarFilasPedido(id='' , producto='', precio='', codigo='', idIva='',iva='', tiposCompras ='', centroId='', productoIva='', idCuenta='', centroDescripcion='', bodegaId='',lote='',cantidadEnBodega=''){

//      let index = codigos.indexOf(id);
//      let contador2 =($('#txtContadorAsientosAgregadosFVC').val()=='')?1: parseInt($('#txtContadorAsientosAgregadosFVC').val())+1;

// let columna_nueva_totaliva = document.getElementById('columna_total_mas_iva');

//     if(index>=0){
//          let fila = index + 1;
//         cantidad = Number($("#txtCantidadS"+fila).val()) + 1;
//         $("#txtCantidadS"+fila).val(cantidad);
//         calculaCantidadFacturaVentaEducativo(fila);
//     }else{

//         codigos.push(id);
//     cadena = "";
//     cadena = cadena + "<div id='filaPedido"+contador2+"' class='form-group '>";
//     cadena = cadena + "<div class='input-group mt-1 bg-light'>";
    
//     cadena = cadena + " <a onclick=\"restarCantidadPedidos("+contador2+");\" title='Limpiar fila' class='btn btn-outline-secondary fa fa-window-close'></a>     ";  

//     cadena = cadena + "<input type='hidden' id='txtIdServicio"+contador2+"' name='txtIdServicio"+contador2+"' value='"+id+"' >     <input   type='hidden' id='txtCodigoServicio"+contador2+"' name='txtCodigoServicio"+contador2+"' class='form-control '   autocomplete='off' value='"+codigo+"' />  ";
    
//     cadena = cadena + "<input type='search' style='width:25% ' class='form-control'  autocomplete='off'  id='txtDescripcionS"+contador2+"'  name='txtDescripcionS"+contador2+"'  value='"+producto+"'  ><a onclick='detalleAdicional("+contador2+");' class='btn btn-outline-secondary'><i class='fas fa-plus'></i></a>";

//     cadena = cadena + "<input type='hidden' maxlength='10' id='bod"+contador2+"'  name='bod"+contador2+"'  class='form-control   bg-white'  onKeyUp=\"lookup_cpra_bod(this.value, "+contador2+", 7)\"  value='"+centroDescripcion+"' >";
    
   
    
    
//     cadena = cadena + "<input type='text' maxlength='10' style='text-align: center; ' id='txtCantidadS"+contador2+"' name='txtCantidadS"+contador2+"' class='form-control ' onKeyUp=\"validarCantidad(txtCantidadS"+contador+",cantidadEnBodega"+contador2+".value);calculaCantidadFacturaVentaEducativo("+contador2+")\" onclick=\"validarCantidad(txtCantidadS"+contador2+",cantidadEnBodega"+contador2+".value);calculaCantidadFacturaVentaEducativo("+contador2+")\"  autocomplete='off' value='1' >";
    
//     cadena = cadena + "<input type='text' style='text-align: right; ' class='form-control  ' id='txtdesc"+contador2+"' name='txtdesc"+contador2+"' onKeyUp=\"calculaValorunitariodescuento("+contador2+")\" onclick=\"calculaValorunitariodescuento("+contador2+")\" autocomplete='off' value='0' >";
  
//     cadena = cadena + "<select type='hidden' class='form-control bg-white' id='cambiarPrecio"+contador2+"' name='cambiarPrecio"+contador2+"'  onchange=\"cambiarPrecios(\'"+contador2+"\',txtIdServicio"+contador2+".value, this.value );calculaCantidadFacturaVentaEducativo(\'"+contador2+"\' )\" > <option value='precio1'>1</option> <option value='precio2'>2</option> <option value='precio3'>3</option> <option value='precio4'>4</option> <option value='precio5'>5</option> <option value='precio6'>6</option> </select>";
//     if(columna_nueva_totaliva){
//         cadena = cadena + "<input type='text' style=' text-align: right; ' class='form-control  ' id='txtValorUnitarioShidden"+contador2+"' name='txtValorUnitarioShidden"+contador2+"' onKeyUp=\"actualizarPvp("+contador2+")\" onclick=\"actualizarPvp("+contador2+")\" autocomplete='off'  value='"+precio+"'>";
//     }else{
//         cadena = cadena + "<input type='text' style=' text-align: right; ' class='form-control  ' id='txtValorUnitarioShidden"+contador2+"' name='txtValorUnitarioShidden"+contador2+"' onKeyUp=\"calculaCantidadFacturaVentaEducativo("+contador2+")\" onclick=\"calculaCantidadFacturaVentaEducativo("+contador2+")\" autocomplete='off'  value='"+precio+"'>";
//     }
    

//     cadena = cadena + "<input type='text' style='text-align: right; ' class='form-control  ' id='txtValorUnitarioS"+contador2+"' name='txtValorUnitarioS"+contador2+"' onKeyUp=\"calculaCantidadFacturaVentaEducativo("+contador2+")\" onclick=\"calculaCantidadFacturaVentaEducativo("+contador2+")\" autocomplete='off' value='"+precio+"' >";
    
//     cadena = cadena + "<input type='text' style='text-align: right; ' class='form-control   bg-white' id='txtValorTotalS"+contador2+"' name='txtValorTotalS"+contador2+"'  readonly='readonly'>";
    
    
//     if(columna_nueva_totaliva){
//         cadena = cadena + "<input type='text' style='text-align: right; ' class='form-control   bg-white' id='txtValorTotalConIvaS"+contador2+"' name='txtValorTotalConIvaS"+contador2+"'  value='0' onKeyUp=\"actualizarPrecioUnitario("+contador2+")\"  >";
//     }
   
// 	cadena = cadena + "<input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIvaS"+contador2+"' name='txtIvaS"+contador2+"'  readonly='readonly' value='"+iva+"'> <input type='hidden'  class='text_input' id='txtTipoS1"+contador2+"' name='txtTipoS1"+contador2+"'  readonly='readonly' value='"+tiposCompras+"' > </div>";

//     cadena = cadena + "<input type='hidden' id='bodegaCantidad"+contador2+"'  name='bodegaCantidad"+contador2+"'   class='form-control  border-0 bg-white' value='"+bodegaId+"'  >";
//     cadena = cadena + "<input type='hidden' style='margin: 0px; width: 20%; text-align: right;' class='form-control  ' id='txtTipoS"+contador2+"' name='txtTipoS"+contador2+"' value='"+tiposCompras+"'  readonly='readonly'> ";
//     cadena = cadena + "<input type='hidden' style='margin: 0px; width: 25%; text-align: right;' class='form-control ' id='txtTipoProductoS"+contador2+"' name='txtTipoProductoS"+contador2+"' value='"+iva+"' readonly='readonly'> ";

//     cadena = cadena + "<input type='hidden' style='margin: 0px; width: 100%; text-align: right; ' class='form-control  ' id='txtdescant"+contador2+"' name='txtdescant"+contador2+"' onKeyUp=\"calculaValorunitariodescuento("+contador2+")\" onclick=\"calculaValorunitariodescuento("+contador2+")\" autocomplete='off'>";
// 	cadena = cadena + "<input type='hidden' id='cuenta"+contador2+"'  name='cuenta"+contador2+"'   class='form-control  border-0 bg-white' value='"+idCuenta+"' >";
// 	cadena = cadena + "<input type='hidden' id='idbod"+contador2+"'  name='idbod"+contador2+"'   class='form-control  border-0 bg-white' value='"+centroId+"' >";
//     cadena = cadena + "<input type='hidden' id='IVA120"+contador2+"'  name='IVA120"+contador2+"'   class='form-control  border-0 bg-white' value='"+productoIva+"' >";
    
//     cadena = cadena + "<input type='hidden'  class='form-control' id='precioOrignal"+contador2+"' name='precioOrignal"+contador2+"' value='"+precio+"'  readonly='readonly'>";
// 	cadena = cadena + "<input type='hidden'  class='form-control' id='txtCalculoIvaS"+contador2+"' name='txtCalculoIvaS"+contador2+"'  readonly='readonly'>";
// 	cadena = cadena + "<input type='hidden'  class='text_input' id='txtIvaItemS"+contador2+  "' name='txtIvaItemS"+contador2+   "'   >";
// 	cadena = cadena + "<input type='hidden'  class='text_input' id='txtTotalItemS"+contador2+"' name='txtTotalItemS"+contador2+ "'  >"; 
// 	cadena = cadena + "<input type='hidden'  class='text_input' id='txtCuentaS"+contador2+"' name='txtCuentaS"+contador2+"'  readonly='readonly'>";
// 	cadena = cadena + "<input type='hidden'  class='text_input' id='txtLoteS"+contador2+"' name='txtLoteS"+contador2+"' value='"+lote+"'  >";
// 	cadena = cadena + "<input type='hidden'  class='text_input' id='cantidadEnBodega"+contador2+"' name='cantidadEnBodega"+contador2+"' value='"+cantidadEnBodega+"'  >";
// 	cadena = cadena + "<input type='hidden'  class='text_input' id='cantidadOriginal"+contador2+"' name='cantidadOriginal"+contador2+"' value='0' >";
//     cadena = cadena + "</div>";
   
//     cadena = cadena + "<div id='detallePedido" + contador2 + "' class='input-group mt-1'>";
//     cadena = cadena + "<textarea type='text' class='form-control my-1 w-25' autocomplete='off'  id='txtdetalle2" + contador2 + "'  name='txtdetalle2" + contador2 + "'  value='' placeholder='Detalle Adicional' style='display:none' maxlength='300' ></textarea>";
//     cadena = cadena + "<input type='hidden'  class='form-control my-1 w-25' id='productoOnIva" + contador2 + "' name='productoOnIva" + contador2 + "'  >";
//     cadena = cadena + "</div>";

//     cadena = cadena + "</div>";
    
//     $("#tblBodyFacVentaCondominios ").append(cadena);
      
//     let contadorActual =contador2;
   
//     document.getElementById('txtContadorFilasFVC').value=contador2;
//     document.getElementById('txtContadorAsientosAgregadosFVC').value=contador2;
//     contador=parseInt(contador2)+1;
  
//     calculaCantidadFacturaVentaEducativo(contadorActual);
     
//     }

    
// }



function AgregarFilasPedido(id='', producto='', precio='', codigo='', idIva='', iva='', tiposCompras='', centroId='', productoIva='', idCuenta='', centroDescripcion='', bodegaId='', lote='', cantidadEnBodega='') {
    // Encontrar el índice del producto con id, bodega y lote coincidentes
    let index = codigos.findIndex(item => item.id === id && item.bodega === bodegaId && item.lote === lote);
    let contador2 = ($('#txtContadorAsientosAgregadosFVC').val() == '') ? 1 : parseInt($('#txtContadorAsientosAgregadosFVC').val()) + 1;

    let columna_nueva_totaliva = document.getElementById('columna_total_mas_iva');

    if (index >= 0) {
        // Producto encontrado, incrementar cantidad
        let fila = index + 1;
        cantidad = Number($("#txtCantidadS" + fila).val()) + 1;
        $("#txtCantidadS" + fila).val(cantidad);
        calculaCantidadFacturaVentaEducativo(fila);
    } else {
        // Producto no encontrado, agregar nueva entrada
        codigos.push({ id: id, bodega: bodegaId, lote: lote });

       
        let cadena = "";
        cadena += "<div id='filaPedido" + contador2 + "' class='form-group '>";
        cadena += "<div class='input-group mt-1 bg-light'>";
        cadena += "<a onclick=\"restarCantidadPedidos(" + contador2 + ");\" title='Limpiar fila' class='btn btn-outline-secondary fa fa-window-close'></a>";
        cadena += "<input type='hidden' id='txtIdServicio" + contador2 + "' name='txtIdServicio" + contador2 + "' value='" + id + "'>";
        cadena += "<input type='hidden' id='txtCodigoServicio" + contador2 + "' name='txtCodigoServicio" + contador2 + "' class='form-control' autocomplete='off' value='" + codigo + "'>";
        cadena += "<input type='search' style='width:25%' class='form-control' autocomplete='off' id='txtDescripcionS" + contador2 + "' name='txtDescripcionS" + contador2 + "' value='" + producto + "'><a onclick='detalleAdicional(" + contador2 + ");' class='btn btn-outline-secondary'><i class='fas fa-plus'></i></a>";
        cadena += "<input type='hidden' maxlength='10' id='bod" + contador2 + "' name='bod" + contador2 + "' class='form-control bg-white' onKeyUp=\"lookup_cpra_bod(this.value, " + contador2 + ", 7)\" value='" + centroDescripcion + "'>";
        cadena += "<input type='text' maxlength='10' style='text-align: center;' id='txtCantidadS" + contador2 + "' name='txtCantidadS" + contador2 + "' class='form-control' onKeyUp=\"validarCantidad(txtCantidadS" + contador2 + ", cantidadEnBodega" + contador2 + ".value);calculaCantidadFacturaVentaEducativo(" + contador2 + ")\" onclick=\"validarCantidad(txtCantidadS" + contador2 + ", cantidadEnBodega" + contador2 + ".value);calculaCantidadFacturaVentaEducativo(" + contador2 + ")\" autocomplete='off' value='1'>";
        cadena += "<input type='text' style='text-align: right;' class='form-control' id='txtdesc" + contador2 + "' name='txtdesc" + contador2 + "' onKeyUp=\"calculaValorunitariodescuento(" + contador2 + ")\" onclick=\"calculaValorunitariodescuento(" + contador2 + ")\" autocomplete='off' value='0'>";
        cadena += "<select type='hidden' class='form-control bg-white' id='cambiarPrecio" + contador2 + "' name='cambiarPrecio" + contador2 + "' onchange=\"cambiarPrecios('" + contador2 + "',txtIdServicio" + contador2 + ".value, this.value);calculaCantidadFacturaVentaEducativo('" + contador2 + "')\"><option value='precio1'>1</option><option value='precio2'>2</option><option value='precio3'>3</option><option value='precio4'>4</option><option value='precio5'>5</option><option value='precio6'>6</option></select>";
        if (columna_nueva_totaliva) {
            cadena += "<input type='text' style='text-align: right;' class='form-control' id='txtValorUnitarioShidden" + contador2 + "' name='txtValorUnitarioShidden" + contador2 + "' onKeyUp=\"actualizarPvp(" + contador2 + ")\" onclick=\"actualizarPvp(" + contador2 + ")\" autocomplete='off' value='" + precio + "'>";
        } else {
            cadena += "<input type='text' style='text-align: right;' class='form-control' id='txtValorUnitarioShidden" + contador2 + "' name='txtValorUnitarioShidden" + contador2 + "' onKeyUp=\"calculaCantidadFacturaVentaEducativo(" + contador2 + ")\" onclick=\"calculaCantidadFacturaVentaEducativo(" + contador2 + ")\" autocomplete='off' value='" + precio + "'>";
        }
        cadena += "<input type='text' style='text-align: right;' class='form-control' id='txtValorUnitarioS" + contador2 + "' name='txtValorUnitarioS" + contador2 + "' onKeyUp=\"calculaCantidadFacturaVentaEducativo(" + contador2 + ")\" onclick=\"calculaCantidadFacturaVentaEducativo(" + contador2 + ")\" autocomplete='off' value='" + precio + "'>";
        cadena += "<input type='text' style='text-align: right;' class='form-control bg-white' id='txtValorTotalS" + contador2 + "' name='txtValorTotalS" + contador2 + "' readonly='readonly'>";
        if (columna_nueva_totaliva) {
            cadena += "<input type='text' style='text-align: right;' class='form-control bg-white' id='txtValorTotalConIvaS" + contador2 + "' name='txtValorTotalConIvaS" + contador2 + "' value='0' onKeyUp=\"actualizarPrecioUnitario(" + contador2 + ")\">";
        }
        cadena += "<input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIvaS" + contador2 + "' name='txtIvaS" + contador2 + "' readonly='readonly' value='" + iva + "'>";
        cadena += "<input type='hidden' class='text_input' id='txtTipoS1" + contador2 + "' name='txtTipoS1" + contador2 + "' readonly='readonly' value='" + tiposCompras + "'>";
        cadena += "</div>";
        cadena += "<input type='hidden' id='bodegaCantidad" + contador2 + "' name='bodegaCantidad" + contador2 + "' class='form-control border-0 bg-white' value='" + bodegaId + "'>";
        cadena += "<input type='hidden' style='margin: 0px; width: 20%; text-align: right;' class='form-control' id='txtTipoS" + contador2 + "' name='txtTipoS" + contador2 + "' value='" + tiposCompras + "' readonly='readonly'>";
        cadena += "<input type='hidden' style='margin: 0px; width: 25%; text-align: right;' class='form-control' id='txtTipoProductoS" + contador2 + "' name='txtTipoProductoS" + contador2 + "' value='" + iva + "' readonly='readonly'>";
        cadena += "<input type='hidden' style='margin: 0px; width: 100%; text-align: right;' class='form-control' id='txtdescant" + contador2 + "' name='txtdescant" + contador2 + "' onKeyUp=\"calculaValorunitariodescuento(" + contador2 + ")\" onclick=\"calculaValorunitariodescuento(" + contador2 + ")\" autocomplete='off'>";
        cadena += "<input type='hidden' id='cuenta" + contador2 + "' name='cuenta" + contador2 + "' class='form-control border-0 bg-white' value='" + idCuenta + "'>";
        cadena += "<input type='hidden' id='idbod" + contador2 + "' name='idbod" + contador2 + "' class='form-control border-0 bg-white' value='" + centroId + "'>";
        cadena += "<input type='hidden' id='IVA120" + contador2 + "' name='IVA120" + contador2 + "' class='form-control border-0 bg-white' value='" + productoIva + "'>";
         cadena = cadena + "<input type='hidden'  class='form-control' id='precioOrignal"+contador2+"' name='precioOrignal"+contador2+"' value='"+precio+"'  readonly='readonly'>";
    cadena = cadena + "<input type='hidden'  class='form-control' id='txtCalculoIvaS"+contador2+"' name='txtCalculoIvaS"+contador2+"'  readonly='readonly'>";
    cadena = cadena + "<input type='hidden'  class='text_input' id='txtIvaItemS"+contador2+  "' name='txtIvaItemS"+contador2+   "'   >";
    cadena = cadena + "<input type='hidden'  class='text_input' id='txtTotalItemS"+contador2+"' name='txtTotalItemS"+contador2+ "'  >"; 
    cadena = cadena + "<input type='hidden'  class='text_input' id='txtCuentaS"+contador2+"' name='txtCuentaS"+contador2+"'  readonly='readonly'>";
    cadena = cadena + "<input type='hidden'  class='text_input' id='txtLoteS"+contador2+"' name='txtLoteS"+contador2+"' value='"+lote+"'  >";
    cadena = cadena + "<input type='hidden'  class='text_input' id='cantidadEnBodega"+contador2+"' name='cantidadEnBodega"+contador2+"' value='"+cantidadEnBodega+"'  >";
    cadena = cadena + "<input type='hidden'  class='text_input' id='cantidadOriginal"+contador2+"' name='cantidadOriginal"+contador2+"' value='0' >";
    cadena = cadena + "</div>";
   
    cadena = cadena + "<div id='detallePedido" + contador2 + "' class='input-group mt-1'>";
    cadena = cadena + "<textarea type='text' class='form-control my-1 w-25' autocomplete='off'  id='txtdetalle2" + contador2 + "'  name='txtdetalle2" + contador2 + "'  value='' placeholder='Detalle Adicional' style='display:none' maxlength='300' ></textarea>";
    cadena = cadena + "<input type='hidden'  class='form-control my-1 w-25' id='productoOnIva" + contador2 + "' name='productoOnIva" + contador2 + "'  >";
    cadena = cadena + "</div>";

    cadena = cadena + "</div>";
    
    $("#tblBodyFacVentaCondominios ").append(cadena);
      
    let contadorActual =contador2;
   
    document.getElementById('txtContadorFilasFVC').value=contador2;
    document.getElementById('txtContadorAsientosAgregadosFVC').value=contador2;
    contador=parseInt(contador2)+1;
  
    calculaCantidadFacturaVentaEducativo(contadorActual);
     
    }

    
}
function actualizarPvp(numfila){
    let precioUnit = document.getElementById('txtValorUnitarioShidden'+numfila).value;

    if(precioUnit!=0 && precioUnit.trim()!=''){
        let iva = document.getElementById('txtIvaS'+numfila).value;
        let calculo =  ( precioUnit * (iva/100) );
  
        document.getElementById('txtValorTotalConIvaS'+numfila).value =  parseFloat(precioUnit)+parseFloat(calculo) ;
        document.getElementById('txtCalculoIvaS'+numfila).value =  calculo ;
    }else{
        document.getElementById('txtCalculoIvaS'+numfila).value =  0;
        document.getElementById('txtValorTotalConIvaS'+numfila).value =  0 ;
    }
    calculaCantidadFacturaVentaEducativo(numfila);
}
function actualizarPrecioUnitario(numfila){
    let precioFinal = document.getElementById('txtValorTotalConIvaS'+numfila).value;

    if(precioFinal!=0 && precioFinal.trim()!=''){
        let iva = document.getElementById('txtIvaS'+numfila).value;
        let calculo = precioFinal / ( 1 + (iva/100) );
        document.getElementById('txtValorUnitarioShidden'+numfila).value = calculo;
        document.getElementById('txtCalculoIvaS'+numfila).value =  calculo*(iva/100) ;
    }else{
        let precioAnterior = document.getElementById('precioOrignal'+numfila).value;
        let iva = document.getElementById('txtIvaS'+numfila).value;
        document.getElementById('txtValorUnitarioShidden'+numfila).value = precioAnterior;
        document.getElementById('txtCalculoIvaS'+numfila).value =  precioAnterior*(iva/100) ;
    }
    calculaCantidadFacturaVentaEducativo(numfila);
}
function calculaCantidadPedido(posicion){
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
    calculoSubTotalPedido();
}


function calculoSubTotalPedido(){
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
    calculoTotalPedido();
}

function calculoTotalPedido(){
    var txtSubtotal = $("#txtSubtotalFVC").val();
    var txtTotalIva = $("#txtTotalIvaFVC").val();
    var total = (parseFloat(txtSubtotal) + parseFloat(txtTotalIva));

    $("#txtTotalFVC").val(total.toFixed(2));
}



function guardar_pedido(accion){
	//alert('ginventario');
	//alert(accion)
    var str = $("#frmPedidoCondominios").serialize();
	//alert("antes de sql");
         $.ajax({
            url: 'sql/pedidos.php',
            type: 'post',
            data: str+"&txtAccion="+accion,
            // para mostrar el loadian antes de cargar los datos
			//alert(data);
            beforeSend: function(){
                //imagen de carga
                $("#mensajeFacturaVentaCondominios").html("<p align='center'><img src='images/loading.gif' /></p>");
            },
            success: function(data){
                //alert(data.length);
                document.getElementById("mensajeFacturaVentaCondominios").innerHTML=data;
                if(data.length == 87){
                   // document.getElementById("frmPedidoCondominioss").reset();
                }
               // listar_bodegas();
            }
        });
}


function listarGrupos(){
    var str = $("#form1").serialize();
    $.ajax({
            url: 'ajax/listarGrupos.php',
            type: 'get',
            data: str,
            success: function(data){
                    $("#div_listar_grupos").html(data);
            }
    });
}
function listarMenu(id,text,page=1){

let url_listado = 'ajax/listarMenu.php';
if(dominioOculto){
    if( dominioOculto.value=='jderp.cloud' || dominioOculto.value =='www.jderp.cloud' || dominioOculto.value =='contaweb.ec' || dominioOculto.value=='www.contaweb.ec' ){
        url_listado = 'ajax/listarMenu_lotes.php';
    }
}

if (text === undefined || text === null) {
  text = "";
}

    var str = $("#frmMesas").serialize();
    $.ajax
	({
            url: url_listado,
            type: 'get',
            data: str+"id="+id+"&page="+page+"&text="+text,
            success: function(data){
               // console.log("data",data);
                $("#div_listar_menu").html(data);
            }
    });
}

// Buscar Pedidos
function lookup_pedido(txtCuenta, cont, accion) {
//	alert('lookup-pedido'+txtCuenta);
//	alert(accion);
// retorna en asientosContables.php;
    if(txtCuenta.length == 0) 
	{        
            // Hide the suggestion box.
            $('#suggestions1').hide();
    } 
	else 
	{    
		$.post("sql/Pedido_buscar.php", {queryString: ""+txtCuenta+"",aux: cont, txtAccion: accion}, function(data)
		{
    //alert(data);
			if(data.length > 5)
			{	//le puse 5 xq aunq no haya datos me retorna 3 en data.length                 
				arrayPrincipal = data.split("î");//  dividivos el vector principal con el caracter: î
				//alert(arrayPrincipal[1]);
				array = arrayPrincipal[1].split("*");// * dividivos el vector por el numero de filas que arroga la consulta
				//array = data.split("*");// * dividivos el vector por el numero de filas que arroga la consulta
				limite = array.length;
				//contFilas = $('#txtContadorFilas').val();
				contFilas = $('#txtContadorFilasFVC').val();
				contFilas1=8;
				// ELIMINA FILAS DEPENDIENDO DEL CONTADOR DE LA CONSULTA
				for(c=1;c<=contFilas;c++){
					eliminaFilas();
				}
				contador = 1;
				document.getElementById('txtContadorFilasFVC').value = contador;
				// AGREGA FILAS DEPENDIENDO DEL CONTADOR DE LA CONSULTA
				//for(c=1;c<=limite-1;c++)
				for(c=1;c<=contFilas1-1;c++)
				
				{
					//fn_agregar(0); // envia valor cero para resetear la posicion
				//	fn_agregar_factura(0);
				    limpiarFilasPedidos(c);
				
				}
				// AGREGA LOS DATOS A LOS TXT
				for(i=1; i<=limite-1; i++)
				{
					datos = array[i].split("?");
					//cuenta desde 0
					fecha = datos[3].split(" ");//solo cojemos la fecha, no la hora
					
					$('#textFechaFVC').val(fecha[0]);
					$('#txtCedulaFVC').val(datos[4]);
					
					$('#txtNombreFVC').val(datos[5]+" "+datos[6]);
					$('#txtTelefonoFVC').val(datos[7]);
 
					$('#txtDireccionFVC').val(datos[8]);
					
					$('#txtCodigoServicio'+i).val(datos[8]);
					$('#txtDescripcionS'+i).val(datos[9]);					
					$('#txtCantidadS'+i).val(datos[10]);
					$('#txtValorUnitarioS'+i).val(datos[11]);
					$('#txtValorTotalS'+i).val(datos[12]);
					$('#txtIvaItemS'+i).val(datos[13]);
					$('#txtTotalItemS'+i).val(datos[14]);				
					$('#txtContadorAsientosAgregadosFVC').val(limite-1)
					
					
					
					// para saber cuantas cuentas estan agregadas
				}
                        
				if(arrayPrincipal[0].length > 5)
				{
                
                
				}
				else
				{
                
            }
                        
          
            calcular_total1();
        		
		}
			else
			{
		// alert("No existe esta cuenta.");
			}
		});
    }

} // lookup

// Unificar Pedidos

function unificar_pedido(){

    $("#div_oculto").load("ajax/unificarPedido.php", function(){
	$.blockUI({
			message: $('#div_oculto'),

		overlayCSS: {backgroundColor: '#111'},
			css:{

				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',

				background: '', /* #f9f9f9*/
				top: '5%',

				position: 'absolute',
				width: '400px',
                                left: ($(window).width() - $('.caja').outerWidth())/2


			}
		});
//                listar_formas_pago();
	});

}

function listar_clientes_pedidos(){
    //PAGINA: cuentasPorCobrar.php
  //  alert("entro");
    var str = $("#frmUnificarPedidos").serialize();
    $.ajax({
            url: 'ajax/listarClientesPedidos.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#div_listar_clientes").html(data);
                 //cantidad_formas_pago();
            }
    });
}

function clientesPedidos(id_cliente){
    // funcion pagar cuota pagina: prestamos.php
//	alert('clientes');
    $("#div_oculto").load("ajax/pedidosCliente.php",{id_cliente:id_cliente}, function()
	{
        $.blockUI(
		{
            message: $('#div_oculto'),
			overlayCSS: {backgroundColor: '#111'},
            css:{
                '-webkit-border-radius': '10px',
				'-moz-border-radius': '10px',
                background: '',
                top: '20px',
                position: 'absolute',
                left:($(window).width() - $('.caja').outerWidth())/2
                }
        });
    });
   // cargarFormasPago(5);		2018-12-08
}




function lookup_temp_ven(txtCuenta, cont) {
// retorna en asientosContables.php;
//	alert(txtCuenta);
    if(txtCuenta.length == 0) 
	{        
        // Hide the suggestion box.
        $('#suggestions1').hide();
    } 
	else 
	{   
	//alert('ANTES DE ENTRAR SQL1111');
	//	$.post("sql/factura_buscar.php", {queryString: ""+txtCuenta+"",aux: cont, txtAccion: accion}, function(data)
		$.post("sql/tempven_buscar.php", {queryString: ""+txtCuenta+"",aux: cont}, function(data)
		{
//			alert('Mostrar datos');
//    		alert(data);
			if(data.length > 5)
			{	//le puse 5 xq aunq no haya datos me retorna 3 en data.length                 
				arrayPrincipal = data.split("î");//  dividivos el vector principal con el caracter: î
				//alert(arrayPrincipal[1]);
				array = arrayPrincipal[1].split("*");// * dividivos el vector por el numero de filas que arroga la consulta
				//array = data.split("*");// * dividivos el vector por el numero de filas que arroga la consulta
				limite = array.length;
				//contFilas = $('#txtContadorFilas').val();
				contFilas = $('#txtContadorFilasFVC').val();
				
				// ELIMINA FILAS DEPENDIENDO DEL CONTADOR DE LA CONSULTA
				for(c=1;c<=contFilas;c++){
					eliminaFilas();
				}
				contador = 1;
				document.getElementById('txtContadorFilasFVC').value = contador;
				// AGREGA FILAS DEPENDIENDO DEL CONTADOR DE LA CONSULTA
				for(c=1;c<=limite-1;c++){
					//fn_agregar(0); // envia valor cero para resetear la posicion
					//fn_agregar_factura(0);
				}
				// AGREGA LOS DATOS A LOS TXT
				conta1=0
				for(i=1; i<=limite-1; i++)
				{
					datos = array[i].split("?");
					//cuenta desde 0
					fecha = datos[3].split(" ");//solo cojemos la fecha, no la hora
					
				//	$('#textFechaFVC').val(fecha[0]);
					$('#txtNumeroFacturaFVC').val(datos[2]);
				 	$('#txtCedulaFVC').val(datos[4]);
					$('#textIdClienteFVC').val(datos[5]);
					$('#txtNombreFVC').val(datos[6]+" "+datos[7]);
					$('#txtTelefonoFVC').val(datos[9]);
 
					$('#txtDireccionFVC').val(datos[8]);
					$('#txtIdServicio'+i).val(datos[10]);
				
					$('#txtCodigoServicio'+i).val(datos[11]);
					$('#txtDescripcionS'+i).val(datos[12]);					
					$('#txtCantidadS'+i).val(datos[13]);
					$('#txtValorUnitarioS'+i).val(datos[14]);
					$('#txtValorTotalS'+i).val(datos[15]);
					$('#txtIvaItemS'+i).val(datos[16]);
					$('#txtTotalItemS'+i).val(datos[17]);	
				
									
					$('#txtContadorAsientosAgregadosFVC').val(limite-1)
					conta1=conta1+1;
				 	// para saber cuantas cuentas estan agregadas
				}
                $('#txtContadorFilasFVC').val(conta1);        
						
				if(arrayPrincipal[0].length > 5)
				{
               }
				else
				{
               
            }
                        

            calcular_total1();
			
		}
			else
			{
			}
		});
    }

} 

function guardar_seleccion(accion){
	//alert('ginventario');
	//alert(accion)
    var str = $("#frmPedidosCliente").serialize();
  //  alert(str);
	//alert("antes de sql");
         $.ajax({
            url: 'sql/crearFacturas.php',
            type: 'post',
            data: str+"&txtAccion="+accion,
            // para mostrar el loadian antes de cargar los datos
			//alert(data);
            beforeSend: function(){
                //imagen de carga
                $("#mensajePagarCuentaCobrar").html("<p align='center'><img src='images/loading.gif' /></p>");
            },
            success: function(data){
                //alert(data.length);
                document.getElementById("mensajePagarCuentaCobrar").innerHTML=data;
                if(data.length == 87){
                   // document.getElementById("frmPedidoCondominioss").reset();
                }
               // listar_bodegas();
            }
        });
}


function pedidoAnticipo1(id_pedido)
{
	//alert('estoy en pedido antiicpo1');
//	alert(id_pedido);
 //PAGINA: nuevaTransaccion.php
 //   $("#div_oculto").load("ajax/tipoPago_anticipo.php",{id_cliente:id_cliente},  function(){
 
    $("#div_oculto").load("ajax/pedidoAnticipo.php",{id_pedido:id_pedido},  function()
	{
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
				  width: '680px',
                  left: ($(window).width() - $('.caja').outerWidth())/2
                }
        });
    });
}


function AgregarFilasPedidoVacias(id='' , producto='', precio='', codigo='', idIva='',iva='', tiposCompras ='', centroId='', productoIva='', idCuenta='', centroDescripcion='', bodegaId='',lote='',cantidadEnBodega=''){
    let index = codigos.indexOf(id);
    let contador2 =($('#txtContadorAsientosAgregadosFVC').val()=='')?1: parseInt($('#txtContadorAsientosAgregadosFVC').val())+1;
    console.log(contador2)
    if(id!=''){
        codigos.push(id);
    }
     let columna_nueva_totaliva = document.getElementById('columna_total_mas_iva');  
   cadena = "";
   cadena = cadena + "<div id='filaPedido"+contador2+"' class='form-group '>";
   cadena = cadena + "<div class='input-group mt-1 bg-light'>";
   
   cadena = cadena + " <a onclick=\"restarCantidadPedidos("+contador2+");\" title='Limpiar fila' class='btn btn-outline-secondary fa fa-window-close'></a>     ";  

   cadena = cadena + "<input type='hidden' id='txtIdServicio"+contador2+"' name='txtIdServicio"+contador2+"' value='"+id+"' >     <input   type='hidden' id='txtCodigoServicio"+contador2+"' name='txtCodigoServicio"+contador2+"' class='form-control '   autocomplete='off' value='"+codigo+"' />  ";
   
   cadena = cadena + "<input type='search' style='width:25% ' class='form-control'  autocomplete='off'  id='txtDescripcionS"+contador2+"'  name='txtDescripcionS"+contador2+"'  value='"+producto+"'  ><a onclick='detalleAdicional("+contador2+");' class='btn btn-outline-secondary'><i class='fas fa-plus'></i></a>";

   cadena = cadena + "<input type='hidden' maxlength='10' id='bod"+contador2+"'  name='bod"+contador2+"'  class='form-control   bg-white'  onKeyUp=\"lookup_cpra_bod(this.value, "+contador2+", 7)\"  value='"+centroDescripcion+"' >";

   cadena = cadena + "<input type='text' maxlength='10' style='text-align: center; ' id='txtCantidadS"+contador2+"' name='txtCantidadS"+contador2+"' class='form-control ' onKeyUp=\"calculaCantidadFacturaVentaEducativo("+contador2+")\" onclick=\"calculaCantidadFacturaVentaEducativo("+contador2+")\"  autocomplete='off' value='1' >";
   
   cadena = cadena + "<input type='text' style='text-align: right; ' class='form-control  ' id='txtdesc"+contador2+"' name='txtdesc"+contador2+"' onKeyUp=\"calculaValorunitariodescuento("+contador2+")\" onclick=\"calculaValorunitariodescuento("+contador2+")\" autocomplete='off' value='0' >";
 
   cadena = cadena + "<select class='form-control bg-white' id='cambiarPrecio"+contador2+"' name='cambiarPrecio"+contador2+"'  onchange=\"cambiarPrecios(\'"+contador2+"\',txtIdServicio"+contador2+".value, this.value );calculaCantidadFacturaVentaEducativo(\'"+contador2+"\' )\" > <option value='precio1'>1</option> <option value='precio2'>2</option> <option value='precio3'>3</option> <option value='precio4'>4</option> <option value='precio5'>5</option> <option value='precio6'>6</option> </select>";
   
if(columna_nueva_totaliva){
        cadena = cadena + "<input type='text' style=' text-align: right; ' class='form-control  ' id='txtValorUnitarioShidden"+contador2+"' name='txtValorUnitarioShidden"+contador2+"' onKeyUp=\"actualizarPvp("+contador2+")\" onclick=\"actualizarPvp("+contador2+")\" autocomplete='off'  value='"+precio+"'>";
    }else{
       cadena = cadena + "<input type='text' style=' text-align: right; ' class='form-control  ' id='txtValorUnitarioShidden"+contador2+"' name='txtValorUnitarioShidden"+contador2+"' onKeyUp=\"calculaCantidadFacturaVentaEducativo("+contador2+")\" onclick=\"calculaCantidadFacturaVentaEducativo("+contador2+")\" autocomplete='off'  value='"+precio+"'>";
    }
   

   cadena = cadena + "<input type='text' style='text-align: right; ' class='form-control  ' id='txtValorUnitarioS"+contador2+"' name='txtValorUnitarioS"+contador2+"' onKeyUp=\"calculaCantidadFacturaVentaEducativo("+contador2+")\" onclick=\"calculaCantidadFacturaVentaEducativo("+contador2+")\" autocomplete='off' value='"+precio+"' >";
   
   cadena = cadena + "<input type='text' style='text-align: right; ' class='form-control   bg-white' id='txtValorTotalS"+contador2+"' name='txtValorTotalS"+contador2+"'  readonly='readonly'>";
     if(columna_nueva_totaliva){
        cadena = cadena + "<input type='text' style='text-align: right; ' class='form-control   bg-white' id='txtValorTotalConIvaS"+contador2+"' name='txtValorTotalConIvaS"+contador2+"'  value='0' onKeyUp=\"actualizarPrecioUnitario("+contador2+")\"  >";
    }
    
   cadena = cadena + "<input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIvaS"+contador2+"' name='txtIvaS"+contador2+"'  readonly='readonly' value='"+iva+"'> <input type='hidden'  class='text_input' id='txtTipoS1"+contador2+"' name='txtTipoS1"+contador2+"'  readonly='readonly' value='"+tiposCompras+"' > </div>";

   cadena = cadena + "<input type='hidden' id='bodegaCantidad"+contador2+"'  name='bodegaCantidad"+contador2+"'   class='form-control  border-0 bg-white' value='"+bodegaId+"'  >";
   cadena = cadena + "<input type='hidden' style='margin: 0px; width: 20%; text-align: right;' class='form-control  ' id='txtTipoS"+contador2+"' name='txtTipoS"+contador2+"'  readonly='readonly'> ";
   cadena = cadena + "<input type='hidden' style='margin: 0px; width: 25%; text-align: right;' class='form-control ' id='txtTipoProductoS"+contador2+"' name='txtTipoProductoS"+contador2+"' value='"+iva+"' readonly='readonly'> ";

   cadena = cadena + "<input type='hidden' style='margin: 0px; width: 100%; text-align: right; ' class='form-control  ' id='txtdescant"+contador2+"' name='txtdescant"+contador2+"' onKeyUp=\"calculaValorunitariodescuento("+contador2+")\" onclick=\"calculaValorunitariodescuento("+contador2+")\" autocomplete='off'>";
   cadena = cadena + "<input type='hidden' id='cuenta"+contador2+"'  name='cuenta"+contador2+"'   class='form-control  border-0 bg-white' value='"+idCuenta+"' >";
   cadena = cadena + "<input type='hidden' id='idbod"+contador2+"'  name='idbod"+contador2+"'   class='form-control  border-0 bg-white' value='"+centroId+"' >";
   cadena = cadena + "<input type='hidden' id='IVA120"+contador2+"'  name='IVA120"+contador2+"'   class='form-control  border-0 bg-white' value='"+productoIva+"' >";
   	cadena = cadena + "<input type='hidden'  class='text_input' id='txtLoteS"+contador2+"' name='txtLoteS"+contador2+"' value='"+lote+"'  >";
   	cadena = cadena + "<input type='hidden'  class='text_input' id='cantidadOriginal"+contador2+"' name='cantidadOriginal"+contador2+"' value='0'>";
   	cadena = cadena + "<input type='hidden'  class='text_input' id='cantidadEnBodega"+contador2+"' name='cantidadEnBodega"+contador2+"' value='"+cantidadEnBodega+"'  >";
   cadena = cadena + "<input type='hidden'  class='form-control' id='txtCalculoIvaS"+contador2+"' name='txtCalculoIvaS"+contador2+"'  readonly='readonly'>";
   cadena = cadena + "<input type='hidden'  class='text_input' id='txtIvaItemS"+contador2+  "' name='txtIvaItemS"+contador2+   "'   >";
   cadena = cadena + "<input type='hidden'  class='text_input' id='txtTotalItemS"+contador2+"' name='txtTotalItemS"+contador2+ "'  >"; 
   cadena = cadena + "<input type='hidden'  class='text_input' id='txtCuentaS"+contador2+"' name='txtCuentaS"+contador2+"'  readonly='readonly'>";
   cadena = cadena + "</div>";
   
   cadena = cadena + "<div id='detallePedido"+contador2+"' class='input-group mt-1'>";
   cadena = cadena + "<textarea type='text' class='form-control my-1' autocomplete='off'  id='txtdetalle2"+contador2+"'  name='txtdetalle2"+contador2+"'  value='' placeholder='Detalle Adicional' style='display:none' maxlength='300' ></textarea>";
   cadena = cadena + "</div>";

   cadena = cadena + "</div>";
     $("#tblBodyFacVentaCondominios ").append(cadena);
     
   let contadorActual =contador2;
  
   document.getElementById('txtContadorFilasFVC').value=contador2;
    document.getElementById('txtContadorAsientosAgregadosFVC').value=contadorActual;
    contador=parseInt(contador2)+1;
 
    calculaCantidadFacturaVentaEducativo(contadorActual);
   
}
function lookup_habitacion(valor, accion) {
    //pagina: pedidos.php
    if(valor.length == 0) {
            $('#suggestionsP').hide();
    } else {
            $.post("sql/habitaciones.php", {queryString: valor, txtAccion: accion}, function(data){
                    if(data.length >0) {
                            $('#suggestionsP').show();
                            $('#autoSuggestionsListP').html(data);
                    }
            });
    }
} // lookup

const cargarPedidoc = (a, b)=>{
     $('#suggestionsP').hide();
    cargarPedido(a, b);
     
}
const ocultarListadoHabitaciones=()=>{
     $('#suggestionsP').hide();
}