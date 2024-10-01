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



function buscar_secuencial_compra(accion,tipoDocumento){
	
    auxiliar=0;
    ajax9=objetoAjax();
    ajax9.open("POST", "../sql/facturaCompra.php",true);
    String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g, "");};
    ajax9.onreadystatechange=function() {
        if (ajax9.readyState==4) {
            var respuesta9=ajax9.responseText;

                let json = JSON.parse(respuesta9);
                let factura = json['numeroFactura'];

                let emisionFactura = json['emision_factura'];
                let emisionEstablecimiento = json['emision_establecimiento'];
                

                let txtnum =(tipoDocumento=='03')?json['numeroFactura2']:'';
                
                
                document.getElementById("txtEmision").value = emisionFactura;
                document.getElementById("txtSerie").value = emisionEstablecimiento;
                
                
				document.getElementById("txtFactura").value = factura;
			    document.getElementById("txtNum").value = txtnum;
			    
                auxiliar = respuesta9.trim();
                 cargaCompra();
                return auxiliar;
                
        }
    }
document.getElementById("txtFactura").innerHTML="" ;
ajax9.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
ajax9.send("&txtAccion="+accion+"&tipoDocumento="+tipoDocumento);
return auxiliar;

}



var contador=1;
function AgregarFilas(){
    
    let dominio= '';
     if(document.getElementById('dominioOculto')){
        dominio= document.getElementById('dominioOculto').value;
     }
     
    cadena = "<div class='input-group p-2'>";
    
    cadena = cadena + "<a type='button' class='input-group-text fa fa-times-circle-o '  name='img"+contador+"' alt='limpiar' title='Eliminar' onclick=\"limpiar(form3,"+contador+")\" /></a><a type='button' class='input-group-text fa fa-list ' name='img"+contador+"' alt='limpiar' title='Eliminar' onclick='codigos(form3,"+contador+")' /></a> ";

    cadena = cadena + "<input type='search' id='codProducto"+contador+"' name='codProducto"+contador+"'  class='form-control    ' placeholder='Agregar Codigo Producto Aqui'   > <div class='suggestionsBox' id='suggestions10"+contador+"' style='display: none; margin-top: 0px; '>"+" <div class='suggestionList' id='autoSuggestionsList10"+contador+"'>  </div> </div>";
    cadena = cadena + "<input type='text' id='des"+contador+"'  name='des"+contador+"'   class='form-control  bg-white' style='width:25%' placeholder='Buscar Producto Aqui...'  onclick=\"lookup_cpra_edu(this.value, "+contador+", 6)\" onKeyUp=\"lookup_cpra_edu(this.value, "+contador+", 6)\" >";
	cadena = cadena + "<input type='text' maxlength='10' id='bod"+contador+"'  name='bod"+contador+"'  class='form-control    '  onKeyUp=\"lookup_cpra_bod(this.value, "+contador+", 7)\"  >";
	
	cadena = cadena + "<input type='text' class='form-control' maxlength='10' id='desc"+contador+"' name='desc"+contador+"'    onclick=\"Recalcular2(form3,"+contador+")\" onKeyUp=\"Recalcular2(form3,"+contador+")\" autocomplete='off' >";

	cadena = cadena + "<input type='text' maxlength='10' id='cant"+contador+"' name='cant"+contador+"'   class='form-control    ' onclick=\"Recalcular2(form3,"+contador+")\" onKeyUp=\"Recalcular2(form3,"+contador+")\" autocomplete='off' >";
	cadena = cadena + "<input type='text' maxlength='10' id='valun"+contador+"' name='valun"+contador+"'   class='form-control   ' onKeyUp=\"Recalcular2(form3,"+contador+")\" onKeyPress=\"return precio(event)\">";
	
	cadena = cadena + "<input type='text' id='valtotal"+contador+"' name='valtotal"+contador+"'   class='form-control  bg-white ' readonly='readonly'>"	;

    cadena = cadena + "</div>";
    
    cadena = cadena + "<div class='input-group '>";
	cadena = cadena + "<div id='codprindiv"+contador+"' style='display:none' class='col-lg-2 col-sm-2 col-xs-2 offset-lg-1 my-2 bg-white'><label>Codigo Principal</label><select class='form-select  ' placeholder='bodegas' id='codPrincipal"+contador+"' name='codPrincipal"+contador+"'></select></div>"	;
	cadena = cadena + "<div id='codauxdiv"+contador+"'        style='display:none' class='col-lg-2 col-sm-2 col-xs-2 my-2 bg-white ml-1' >       <label>Codigo Auxiliar</label><select class='form-select  ' placeholder='codAux' id='codAux"+contador+"' name='codAux"+contador+"'></select></div>"	;
	cadena = cadena + "<div id='centrodecostodiv"+contador+"' style='display:none' class='col-lg-3 col-sm-2 col-xs-2 my-2 bg-white ml-1' >       <label>Centro de costo</label><select class='form-select  ' placeholder='bodegas' id='centrodecosto"+contador+"' name='centrodecosto"+contador+"'></select></div>";
	cadena = cadena + "<div id='bodInventariodiv"+contador+"' style='display:none' class='col-lg-2 col-sm-2 col-xs-2 my-2 bg-white ml-1' >       <label>Bodega Inventario</label><select class='form-select  ' placeholder='bodegas' id='bodInventario"+contador+"' name='bodInventario"+contador+"'></select></div>";
      cadena = cadena + "</div>";
     cadena = cadena + "<div class='input-group  offset-lg-1'>";
    
    if(dominio=='www.jderp.cloud' || dominio=='jderp.cloud' || dominio=='www.contaweb.ec' || dominio=='contaweb.ec' ){
        cadena = cadena + "<div id='lotediv"+contador+"' style='display:none' class='col-lg-2 col-sm-2 col-xs-2 my-2 bg-white' >            <label>Lote</label> ";
        cadena = cadena + "<input type='text' class='form-control' maxlength='10' id='txtLoteS"+contador+"' name='txtLoteS"+contador+"'    autocomplete='off' ></div>";
        
        cadena = cadena + "<div id='lote_calidad_div"+contador+"' style='display:none' class='col-lg-2 col-sm-2 col-xs-2 my-2 bg-white' >            <label>Calidad del lote</label><select class='form-select'   id='lote_calidad"+contador+"' name='lote_calidad"+contador+"'><option value='0'>0</option></select></div>";
        cadena = cadena + " <div id='fecha_elaboracion_div"+contador+"' style='display: none;' class='col-lg-2 col-sm-2 col-xs-2 my-2 bg-white ml-1' >  <label>Fecha Elaboracion </label><input name='textFechaElaboracion"+contador+"' id='textFechaElaboracion"+contador+"' class='form-control ' type='text'  onclick=\'displayCalendar(textFechaElaboracion"+contador+",`yyyy-mm-dd hh:ii:00`,this,this)\'></div>";
        
        cadena = cadena + " <div id='fecha_vencimiento_div"+contador+"' style='display:none' class='col-lg-2 col-sm-2 col-xs-2 my-2 bg-white ml-1' >  <label>Fecha Vencimiento</label><input name='textFechaVencimiento"+contador+"' id='textFechaVencimiento"+contador+"' class='form-control ' type='text'  onclick=\'displayCalendar(textFechaVencimiento"+contador+",`yyyy-mm-dd hh:ii:00`,this,this)\'></div>";
    }
    
    cadena = cadena + "</div>";
    cadena = cadena + "<div class='input-group  offset-lg-1'>";
    
    if(dominio=='www.contaweb.ec' || dominio=='contaweb.ec'  ){

    cadena = cadena + "<div id='serviciodiv"+contador+"' style='display:none' class='col-lg-2 col-sm-2 col-xs-2 my-2 bg-white' >            <label>Proyecto</label><select class='form-select'   id='servicioEmpresa"+contador+"' name='servicioEmpresa"+contador+"' value='0'></select></div>";
    }
        cadena = cadena + "<div id='centrodecostoEmpresadiv"+contador+"' style='display:none' class='col-lg-2 col-sm-2 col-xs-2 my-2 bg-white' ><label>Centro de costo</label><select class='form-select'   id='centrodeCostoEmpresa"+contador+"' name='centrodeCostoEmpresa"+contador+"'></select></div>";

      cadena = cadena + "</div>";
      
    cadena = cadena + "<input type='hidden' id='xml"+contador+"' name='xml"+contador+"'  class='form-control  border-0' placeholder='Buscar...' >";
	cadena = cadena + "<input type='hidden' id='idproducto"+contador+"' name='idproducto"+contador+"'  class='form-control  border-0' placeholder='Buscar...' >";
	cadena = cadena + "<input type='hidden' id='stock"+contador+"' name='stock"+contador+"'  class='form-control  border-0' readonly='readonly' > ";
	cadena = cadena + "<input type='hidden' id='exmax"+contador+"' name='exmax"+contador+"' class=''readonly='readonly' >";
	cadena = cadena + "<input type='hidden' id='idbodInventario"+contador+"'  name='idbodInventario"+contador+"'   class='form-control  border-0 bg-white' >";
	cadena = cadena + "<input type='hidden' id='idbod"+contador+"'  name='idbod"+contador+"'   class='form-control  border-0 bg-white' >";
	cadena = cadena + "<input type='hidden' id='idTipo"+contador+"'  name='idTipo"+contador+"'   class='form-control  border-0 bg-white' >";
	cadena = cadena + "<input type='hidden' id='ivaS"+contador+"' name='ivaS"+contador+"'  class='' readonly='readonly'>";
	cadena = cadena + "<input type='hidden' id='cuenta"+contador+"' name='cuenta"+contador+"' class='' readonly='readonly'>";
	cadena = cadena + "<input type='hidden' id='txtIvaItemS"+contador+"' name='txtIvaItemS"+contador+"'  class='' readonly='readonly'>";
	cadena = cadena + "<input type='hidden' id='txtTotalItemS"+contador+"' name='txtTotalItemS"+contador+"' class='' readonly='readonly'>";
	cadena = cadena + "</div>";
	
    document.getElementById('txtContadorFilasCpra').value=contador;
    cargarBodegas(10,contador);
    cargarCentrosdeCosto(11,contador);
    cargarCodigosAux(12,contador);
    cargarCodigosPrin(13,contador);
    cargarCentrosdeCostoEmpresa(14,contador);
    cargarservicios(21,contador);
    cargarCalidadLotes(contador)
    contador++;
    $("#campos").append(cadena); 
    
    
  
}
function cargarCalidadLotes(contador){
	$.ajax({
		url:'sql/lotes.php',
		type:'post',
		data: {txtAccion:8},
		success:function(res){
	//	    console.log(res);
			$("#lote_calidad"+contador).html(res);

		}
	});
}
function codigos(form,contador){

    cont = contador;

    
    let servicioEmpresadiv=document.getElementById('serviciodiv'+cont);
      
    if(servicioEmpresadiv){
         if(servicioEmpresadiv.style.display=='none'){
        servicioEmpresadiv.style.display='block';
        }else{
        servicioEmpresadiv.style.display='none';
        }
    }
    
    
    let centrodecostoEmpresadiv=document.getElementById('centrodecostoEmpresadiv'+cont);
      
    if(centrodecostoEmpresadiv){
         if(centrodecostoEmpresadiv.style.display=='none'){
        centrodecostoEmpresadiv.style.display='block';
        }else{
        centrodecostoEmpresadiv.style.display='none';
        }
    }
  

    var bodInventariodiv=document.getElementById('bodInventariodiv'+cont);
    
    if (bodInventariodiv.style.display=='none'){
         bodInventariodiv.style.display='block';
    }else{
        bodInventariodiv.style.display='none';
    }

      
     
    var codprin=document.getElementById('codprindiv'+cont);
    
    if (codprin.style.display=='none'){
         codprin.style.display='block';
    }else{
        codprin.style.display='none';
    }
    
    var codaux=document.getElementById('codauxdiv'+cont);
    if (codaux.style.display=='none'){
         codaux.style.display='block';
    }else{
        codaux.style.display='none';
    }
    let dominio= '';
     if(document.getElementById('dominioOculto')){
        dominio= document.getElementById('dominioOculto').value;
     }
     if(dominio=='www.jderp.cloud' || dominio=='jderp.cloud'   || dominio=='www.contaweb.ec' || dominio=='contaweb.ec'){
       var lote_div=document.getElementById('lotediv'+cont);
    if (lote_div.style.display=='none'){
        lote_div.style.display='block';
    }else{
        lote_div.style.display='none';
    }
    var lote_calidad_di=document.getElementById('lote_calidad_div'+cont);
    if (lote_calidad_di.style.display=='none'){
        lote_calidad_di.style.display='block';
    }else{
        lote_calidad_di.style.display='none';
    }
    // lote_calidad_div
    var lote_div_fecha_elab=document.getElementById('fecha_elaboracion_div'+cont);
    if (lote_div_fecha_elab.style.display=='none'){
        lote_div_fecha_elab.style.display='block';
    }else{
        lote_div_fecha_elab.style.display='none';
    }
    var lote_div_fecha_venc=document.getElementById('fecha_vencimiento_div'+cont);
    if (lote_div_fecha_venc.style.display=='none'){
        lote_div_fecha_venc.style.display='block';
    }else{
        lote_div_fecha_venc.style.display='none';
    }  
     }
     
    
    
}

function cargarCentrosdeCostoEmpresa(accion,contador){
	$.ajax({
		url:'sql/facturaCompra.php',
		type:'post',
		data: {txtAccion:accion},
		success:function(res){
	//	    console.log(res);
			$("#centrodeCostoEmpresa"+contador).html(res);
            
		}
	});
}
function cargarservicios(accion,contador){
	$.ajax({
		url:'sql/facturaCompra.php',
		type:'post',
		data: {txtAccion:accion},
		success:function(res){
		  //  console.log(res);
			$("#servicioEmpresa"+contador).html(res);

		}
	});
}


function cargarBodegas(accion,contador){
	$.ajax({
		url:'sql/facturaCompra.php',
		type:'post',
		data: {txtAccion:accion},
		success:function(res){
	//	    console.log(res);
			$("#bodInventario"+contador).html(res);

		}
	});
}

function cargarCentrosdeCosto(accion,contador){
	$.ajax({
		url:'sql/facturaCompra.php',
		type:'post',
		data: {txtAccion:accion},
		success:function(res){
	//	    console.log(res);
			$("#centrodecosto"+contador).html(res);
            
		}
	});
}

function cargarCodigosAux(accion,contador){
	$.ajax({
		url:'sql/facturaCompra.php',
		type:'post',
		data: {txtAccion:accion},
		success:function(res){
	//	    console.log(res);
			$("#codAux"+contador).html(res);
		}
	});
}

function cargarCodigosPrin(accion,contador){
	$.ajax({
		url:'sql/facturaCompra.php',
		type:'post',
		data: {txtAccion:accion},
		success:function(res){
	//	    console.log(res);
			$("#codPrincipal"+contador).html(res);
		}
	});
}



function EliminarFilas(){
     $("#campos").html("");
     contador = 1;
}

function botonesCompra(){
    let id_compra = document.getElementById('txtIdCompra').value; 
    if(id_compra!=''){
         $.ajax(
	{
		url: 'sql/facturaCompra.php',
		data: "txtAccion=22&id_compra="+id_compra,
		type: 'post',
		success: function(data)	{
		    let response= data.trim();
            if(response==0){
                document.getElementById('submit').style.display='block'; 
              
            }else{
               document.getElementById('submit').style.display='none'; 
            }
           
		}
	});
    }
}


function lookup_compra_educ(txtCuenta, cont, accion) 
{
   // alert(txtCuenta,cont,accion); 

    if(txtCuenta.length == 0) 
	{        
        $('#suggestions1').hide();
    } 
	else 
	{    
	//	alert("antes de factura");
		$.post("sql/compra_buscar_educat.php", {queryString: ""+txtCuenta+"",aux: cont, txtAccion: accion}, function(data)
		{
		    console.log(data);
		    let success = false;
		    let response = null;
		    try{
		        let r = JSON.parse(data);
		        response = r.filas;
		        if(response.length > 0){
		            console.log(response);
		            success = true;
		        }
		    }catch(error){
		        console.log(error);
		        let fact = txtFactura.value;
		        document.getElementById('form3').reset();  
		        txtFactura.value = fact;
		    }
		  //  console.log(data);
 			if(success)
			{	//le puse 5 xq aunq no haya datos me retorna 3 en data.length  
				             
		       
  if(document.getElementById('json_impuestos')){
				      const jsonImpuestos = JSON.parse(document.getElementById('json_impuestos').textContent);

                    for (const impuestoId of jsonImpuestos) {
                      const inputField = document.getElementById('subTotal'+impuestoId);
                      if (inputField) {
                        inputField.value = '0';
                      }
                    }   
				} 
				//arrayPrincipal = data.split("î");//  dividivos el vector principal con el caracter: î

				//array = arrayPrincipal[1].split("*");    
                EliminarFilas();
				limite = response.length;
				contFilas = limite ;							

				// ELIMINA FILAS DEPENDIENDO DEL CONTADOR DE LA CONSULTA
				for(c=1;c<=contFilas;c++){
					//eliminaFilas();
				}
					
				contFilas1=limite;
				contador = 1;
				
				for(c=1;c<=contFilas1-1;c++){
				limpiarFilas_Cpras(c);
				}
				// AGREGA LOS DATOS A LOS TXT
				
				for(i=1; i<=limite; i++)
    				{
    				    AgregarFilas();
    				}
				
				    setTimeout(function(){
                    for(i=1; i<=limite; i++)
    				{
    				    let fila = response[i-1];
    					j=i;

    					
    					$('#textFecha').val(fila.compras_fecha_compra);
    					$('#txtRuc').val(fila.proveedores_ruc);
    					$('#txtNombreRuc').val(fila.proveedores_nombre_comercial);
    					$('#textIdProveedor').val(fila.proveedores_id_proveedor);     
    				
    					$("#campos").find('#idproducto'+j).val(fila.detalle_compras_id_producto);
    					
    					$("#campos").find('#codProducto'+j).val(fila.productos_codigo);
    					
    					$("#campos").find('#stock'+j).val(fila.productos_stock);
    					
    					//$('#idproducto2').val(datos[7]);
    					$("#campos").find('#des'+j).val(fila.productos_nombre);	
    					
    					$("#campos").find('#bod'+j).val(fila.centro_descripcion);
    					
    					$("#campos").find('#idbod'+j).val(fila.centro_costo_id);	
                        $("#campos").find('#idTipo'+j).val(fila.centro_costo_tipo);
                        
                        $("#campos").find('#codPrincipal'+j).val(fila.productos_codPrincipal);
                        $("#campos").find('#codAux'+j).val(fila.productos_codAux);
                        $("#campos").find('#xml'+j).val(fila.detalle_compras_xml);
                        $("#campos").find('#desc'+j).val(fila.detalle_compras_des);
    						
    					
    					$("#campos").find('#cant'+j).val(fila.detalle_compras_cantidad);
    					$("#campos").find('#valun'+j).val(fila.detalle_compras_v_unitario);
    				
    					$("#campos").find('#valtotal'+j).val(fila.detalle_compras_v_total);
    					$("#campos").find('#ivaS'+j).val(fila.impuestos_iva);
    					
	
    					$("#campos").find('#cuenta'+j).val(fila.centro_costo_id_cuenta);
    					
    					$("#campos").find('#xml'+j).val(fila.detalle_compras_xml);
    
    					$('#txtAutorizacion').val(fila.compras_autorizacion);
    					$('#txtSerie').val(fila.compras_numSerie);
    					$('#txtFechaVencimiento').val(fila.compras_caducidad);
    					$('#txtTipoComprobante').val(fila.compras_TipoComprobante);
    					$('#codSustento').val(fila.compras_codSustento);
    					$('#txtEmision').val(fila.compras_txtEmision);
    					$('#txtNum').val(fila.compras_txtNum);
    					$('#textIdCompra').val(fila.compras_id_compra);  
    					
    					$('#subTotal0').val(fila.compras_subTotal0);
    					$('#subTotal12').val(fila.compras_subTotal12);
    					$('#txtIva').val(fila.compra_iva); 
    					
    					$('#txtDescuento').val(fila.compra_descuento); 
    					$('#txtSubtotalInventarios').val(fila.compra_subTotalInvenarios); 
    					
    					$('#textXML').val(fila.compra_xml); 
    					$('#txtTotal').val(fila.compra_total); 
    					$('#txtSubtotal').val(fila.compra_subtotal); 
                    let    posicion=j;
                    			let tipoS= fila.centro_costo_tipo;
                    let posicionn= parseInt(posicion);
                   
                    console.log("==>",tipoS);
                     if (tipoS=="1")
                     {
                    
                        if(!listaInventario.includes(posicionn)){
                            listaInventario.push(posicionn);
                        }              
                    }
                    if (tipoS=="2")
                    {
                        if(!listaServicios.includes(posicionn)){
                            listaServicios.push(posicionn);
                        }   
                    }
                    if (tipoS=="3")
                    {
                        if(!listaActivos.includes(posicionn)){
                            listaActivos.push(posicionn);
                        }   
                    }
                    if (tipoS=="4")
                    {
                        if(!listaProveeduria.includes(posicionn)){
                            listaProveeduria.push(posicionn);
                        }   
                    }
    				
    				}
    				
					var xml = document.getElementById("textXML").value;
					if (xml=0){
					    
			                           
                            calcular_total_cpra();
                            
					}	
					 Calcular_TotCpra();
					 botonesCompra();
				},1000);

		}
			else
			{ 
		// alert("No existe esta cuenta.");
			} 
		});
    }

} // lookup
function lookup_cpra_edu(txtNombre, cont, accion) {
//para agregar SERVCIO pagina: nuevaFacturaVenta.php
    //alert("nombre:"+txtNombre+"-"+cont+"-"+accion);
	//alert("buscar....");
    if(txtNombre.length == 0)
	{
        // Hide the suggestion box.
        $('#suggestions10'+cont).hide();
    }
	else 
	{
		//alert("Antes de entrar a sql");
        $.post("sql/facturaCompra.php", {queryString: txtNombre, cont: cont,  txtAccion: accion}, function(data)
		{
		  //  console.log(data);
			if(data.length >0) {
                 $('.suggestionsBox').hide();
                $('#suggestions10'+cont).show();
                $('#autoSuggestionsList10'+cont).html(data); 
             //  alert("entro: "+data);
            }
        });
    }
} // lookup



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
		//alert("Antes de entrar a sql");
        $.post("sql/facturaCompra.php", {queryString: txtNombre, cont: cont,  txtAccion: accion}, function(data)
		{
			if(data.length >0) {
                 $('.suggestionsBox').hide();
                $('#suggestions10'+cont).show();
                $('#autoSuggestionsList10'+cont).html(data); 
             //  alert("entro: "+data);
            }
        });
    }
} // lookup



// function fill_cpra_edu(cont1, idServicio, cadena){
//     console.log(cadena);
//     cont=cont1;
//     setTimeout("$('.suggestionsBox').hide();", 50);

//     //thisValue.replace(" ","");
//     array = cadena.split("*");

//     // este if debe ir antes de asignar a los txt
//     if($('#txtIdServicio'+cont).val() >= 1){
//         // cuando no usa el boton limpiar significa que
//         // si hay cuenta agregada en la fila y solo esta remplazando por otra cuenta
//         // ya no vuelve a sumar cuantas cuentas estan agregadas
//     }else{
//         // cuando usa el boton limpiar
//         // significa que ha quitado la cuenta y cunado agrega una nueva suma cuantas cuentas estan agregadas
//         sumaAsientosAgregados =  $('#txtContadorAsientosAgregadosFVC').val();
//         sumaAsientosAgregados ++;
//         $('#txtContadorAsientosAgregadosFVC').val(sumaAsientosAgregados);
//     }

//     //$('#idproducto'+cont).val(idServicio);
//     $('#idproducto'+cont).val(idServicio);
//     $('#codProducto'+cont).val(array[0]);
//     $('#des'+cont).val(array[1]);
    
//     $('#stock'+cont).val(array[3]);
    
//     var valorCosto = parseFloat(array[4]);
//     $('#valun'+cont).val(valorCosto.toFixed(2));
    
//     $('#cant'+cont).val("1");
	
// 	$('#ivaS'+cont).val(array[6]);
	
// 	$('#codPrincipal'+cont).val(array[12]);
	
// 	$('#codAux'+cont).val(array[13]);
	
// }

function fill_cpra_edu(cont1, idServicio, cadena) {
    cont = cont1;

    let existente = ($('#xml' + cont).val() == '' || $('#xml' + cont).val() == '0') ? 0 : 1;

    setTimeout(function () {
        $('.suggestionsBox').hide();
    }, 50);

    array = cadena.split("*");

    if ($('#txtIdServicio' + cont).val() >= 1) {
        // Existing account in the row; replacing with another account
    } else {
        // Adding a new account
        sumaAsientosAgregados = $('#txtContadorAsientosAgregadosFVC').val();
        sumaAsientosAgregados++;
        $('#txtContadorAsientosAgregadosFVC').val(sumaAsientosAgregados);
    }

    if (existente === 1) {
        // If xml is empty or 0, fill only the name field
        $('#idproducto' + cont).val(idServicio);
        $('#des' + cont).val(array[1]);
        $('#codProducto' + cont).val(array[0]);
        
    } else {
         $('#idproducto' + cont).val(idServicio);
        $('#codProducto' + cont).val(array[0]);
        $('#des' + cont).val(array[1]);
        $('#stock' + cont).val(array[3]);
        $('#cant' + cont).val("1");
        var valorCosto = parseFloat(array[4]);
        $('#valun' + cont).val(valorCosto.toFixed(4));
        $('#ivaS' + cont).val(array[6]);
        $('#codPrincipal' + cont).val(array[12]);
        $('#idbod' + cont).val(array[14]);
        $('#bod' + cont).val(array[18]);
        $('#cuenta' + cont).val(array[19]);
        $('#idTipo' + cont).val(array[17]);
        $('#txtTipoS' + cont).val(array[17]);
    }
    Recalcular2(form3, cont);
}





function fill_cpra_bod(cont1, idBodega, cadena){
    cont=cont1;
    setTimeout("$('.suggestionsBox').hide();", 50);

    array = cadena.split("*");

    $('#idbod'+cont).val(idBodega);
    $('#bod'+cont).val(array[1]);
    $('#cuenta'+cont).val(array[2]);
    $('#idTipo'+cont).val(array[3]);
    
    Recalcular2(form3,cont);
    
}





//********** FACTURACION COMPRA ********//

function agregar_producto_factura()
{
  // pagina: nuevaFacturaCompra.php

$("#div_oculto").load("ajax/buscarProductosFactura.php", function(){
        $.blockUI({
        message: $('#div_oculto'),
                overlayCSS: {backgroundColor: '#111'},
                css:{
                        '-webkit-border-radius': '10px',
						'-moz-border-radius': '10px',
                        position: 'absolute',
                        background: '#FFFFFF',
                        top: '40px',
                        left: '185px',
                        right: '185px',
                        width: '520px',
                        theme: true,
                        baseZ: 2000
                }
        });
});

}

//********** FACTURACION COMPRA ********//

function lookup7(txtNombre,cmbTipoCompra) {
//	alert(cmbTipoCompra);
//para agregar producto pagina: nuevaFacturaCompra.php
    if(txtNombre.length == 0)
	{
        // Hide the suggestion box.
        $('#suggestions').hide();
    } else {
        $.post("sql/BuscarProductoNombre.php", {queryString: ""+txtNombre+"",tipoCompra:""+cmbTipoCompra+""}, function(data){
            if(data.length >0) {
                $('#suggestions').show();
                $('#autoSuggestionsList').html(data);
            }
        });
    }
} // lookup

function fill7(thisValue) {
    $('#txtNombre').val(thisValue);
    setTimeout("$('#suggestions').hide();", 200);
}

function lookup8(txtProveedor) {
    if(txtProveedor.length == 0) {
        // Hide the suggestion box.
        $('#suggestions').hide();
    } else {
        $.post("sql/BuscarProductoProveedor.php", {queryString: ""+txtProveedor+""}, function(data){
            if(data.length >0) {
                $('#suggestions').show();
                $('#autoSuggestionsList').html(data);
            }
        });
    }
} // lookup

function fill8(thisValue) {
    $('#txtProveedor').val(thisValue);
    setTimeout("$('#suggestions').hide();", 200);
}

function guardar_compra(){

    if( document.getElementById("txtNum") ){
        let numero = document.getElementById("txtNum").value;
        if( numero.trim() == '' ){
            alertify.warning("Es necesario ingresar el numero de la factura para continuar");  
            return;
        }
    }
    var total = document.getElementById("txtTotal").value;

    
    if (total>0){
        contador1=1;
        tipoPago_compra();   
            
    }
    
    if (total==0){
            alertify.warning("Ingrese datos de compra");  
    }
}
// function guardar_compra(){
// // console.log("guardar");
//     var total = document.getElementById("txtTotal").value;

    
//     if (total>0){
//         contador1=1;
//         tipoPago_compra();   
            
//     }
    
//     if (total==0){
//             alertify.warning("Ingrese datos de compra");  
//     }
// }

function tipoPago_compra(opcion){

   // console.log("opcion",opcion);
    $("#div_oculto").load("ajax/tipoPago_compra.php",  function()
	{
        $.blockUI(
		{
	                message: $('#div_oculto'),
        overlayCSS: {backgroundColor: '#111'},
                css:{
                '-webkit-border-radius': '3px',
                '-moz-border-radius': '3px',
                'box-shadow': '6px 6px 20px gray',
				top: '10%',
				left: '15%',
				width: '75%',
                position: 'absolute'
                }
        });
    });
    revisarCuentasCobrar();
}


function guardar_compra_xml(contx){

    var total = document.getElementById("txtTotal").value;
    if (total>0){
            tipoPago_compra_xml(1);   
    }
    if (total==0){
            alertify.warning("Ingrese datos de compra");  
    }
}

function tipoPago_compra_xml(opcion){

  //  console.log("opcion",opcion);
    $("#div_oculto").load("ajax/tipoPago_compra_xml.php",  function()
	{
        $.blockUI(
		{
	            message: $('#div_oculto'),
                overlayCSS: {backgroundColor: '#111'},
                css:{
                '-webkit-border-radius': '3px',
                '-moz-border-radius': '3px',
                'box-shadow': '6px 6px 20px gray',
				top: '10%',
				left: '15%',
				width: '75%',
                position: 'absolute'
                }
        });
    });
}


function cargarFormasPago_compra(aux){ 
//alert("orma de pagooooo");
//alert(aux);
    ajax2=objetoAjax();    
    ajax2.open("POST", "sql/formasPago_cpra.php",true);    
    ajax2.onreadystatechange=function(){        
        if (ajax2.readyState==4){
            var respuesta2=ajax2.responseText;
            
            asignaFormasPago_cpra(respuesta2);
        }
    }
    ajax2.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax2.send("txtAccion="+aux);
}

function asignaFormasPago_cpra(cadena)
{   
    array = cadena.split( "?" );
	//alert("jjjj");
//	alert(cadena)
    limite=array.length;
    cont=1;
    
   // limpiaCmbFormasPago1();
    document.getElementById("cmbFormaPagoFP").options[0] = new Option("Seleccione Forma de Pago","0");
    for(i=1;i<limite;i=i+2){
        document.getElementById("cmbFormaPagoFP").options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }

}

function limpiaCmbFormasPago1()
{
    for (m=document.getElementById("cmbFormaPagoFP").options.length-1;m>=0;m--){
        document.getElementById("cmbFormaPagoFP").options[m]=null;
    }    
}


 function listar_cuotasFVC(){
     
    cadena = "<table id='tblListadoCuotas' class='table table-bordered table-hover' width='100%'>";
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
    
    plazo = document.getElementById("txtCuotasTP").value;    
    fecha_inicio = document.getElementById("txtFechaTP").value;    
    empleado = document.getElementById("txtNombreFVC").value;    
    total_credito = document.getElementById("txtDebeFP").value;
    numero_factura = document.getElementById("txtNumeroFacturaFVC").value;
    valor_cuotas = parseFloat(total_credito / plazo);
    //imp_final = parseFloat(document.formnp.txtImFinal.value);
    //valor_cuotas = parseFloat(document.formnp.txtCuotas.value).toFixed(2);
    contador = 0;
    //valor_restante = imp_final;
    for(i=0; i<plazo; i++){
        
        contador++;
        
        //valor_restante = (valor_restante - valor_cuotas).toFixed(2);
        fecha = sumaFechaFVC(i-1,fecha_inicio);
        
        if(contador%2==0){            
            cadena = cadena+"<tr class='odd' id='tr1'>";
            cadena = cadena+"<td>"+contador+"</td>";
            cadena = cadena+"<td>"+empleado+"</td>";
            cadena = cadena+"<td>"+numero_factura+"</td>";
            cadena = cadena+"<td>"+valor_cuotas.toFixed(2)+"</td>";
            cadena = cadena+"<td>"+fecha+"</td>";
            cadena = cadena+"</tr>";
        }
        if(contador%2==1){            
            cadena = cadena+"<tr class='odd' id='tr2'>";
            cadena = cadena+"<td>"+contador+"</td>";
            cadena = cadena+"<td>"+empleado+"</td>";
            cadena = cadena+"<td>"+numero_factura+"</td>";
            cadena = cadena+"<td>"+valor_cuotas.toFixed(2)+"</td>";
            cadena = cadena+"<td>"+fecha+"</td>";
            cadena = cadena+"</tr>";
        }
    }    

    cadena = cadena+"</tbody>";
    cadena = cadena+"</table>";
    
    $("#div_listar_cuotasFVC").html(cadena);
}

 sumaFechaFVC = function(m, fecha)
 {
    var Fecha = new Date();
    var sFecha = fecha || (Fecha.getDate() + "/" + (Fecha.getMonth() +1) + "/" + Fecha.getFullYear());    
    var sep = sFecha.indexOf('/') != -1 ? '/' : '-'; 
    var aFecha = sFecha.split(sep);
    var meses = m || 0;
    var mes = parseInt(aFecha[1]); 
    aFecha[1] = mes + meses;

    var fFecha = Date.UTC(aFecha[0],aFecha[1],aFecha[2])+(86400000); // 86400000 son los milisegundos que tiene un día
    var fechaFinal = new Date(fFecha);

    var anno = fechaFinal.getFullYear();
    var mes = fechaFinal.getMonth();
    mes = parseInt(mes)+1;
    var dia = fechaFinal.getDate();
    var mes = (mes < 10) ? ("0" + mes) : mes;
    var dia = (dia < 10) ? ("0" + dia) : dia;
    var fechaFinal = anno+sep+mes+sep+dia;

    return (fechaFinal);

 }
 
 ///   *********** Ventas
function AgregarFilasV(){
    for(j=1;j<=12;j++)
    {	
    //    document.getElementById("campos").innerHTML+="<img src='images/delete.png' name='img"+new String(img+1)+"' width='16' height='16' alt='limpiar' title='Eliminar' onclick=\"limpiar(form3,"+new String(contador)+")\" />   <a href='javascript: agregar_producto_factura();' class='tip'><img src='images/add.png' name='add"+new String(img+1)+"' width='16' height='16' alt='limpiar' title='Agregar' onclick=\"limpiar(form3,"+new String(contador)+")\" /></a>    <input type='text'  id='stock"+new String(st+1)+"' name='stock"+new String(st+1)+"' size='7' readonly='readonly' >"
    document.getElementById("campos").innerHTML+="<img src='images/delete.png' name='img"+new String(img+1)+"' width='16' height='16' alt='limpiar' title='Eliminar' onclick=\"limpiar(form3,"+new String(contador)+")\" />   <a href='javascript: agregar_producto_factura();' class='tip'><img src='images/add.png' name='add"+new String(img+1)+"' width='16' height='16' alt='limpiar' title='Agregar' onclick=\"limpiar(form3,"+new String(contador)+")\" /></a>    "
    img=img+1, contador=contador+1
    document.getElementById("campos").innerHTML+="<input type='text' id='idproducto"+new String(idp+1)+"' name='idproducto"+new String(idp+1)+"' size='10' readonly='readonly'  >"
    idp=idp+1
    document.getElementById("campos").innerHTML+="<input type='text' id='stock"+new String(st+1)+"' name='stock"+new String(st+1)+"' size='7' readonly='readonly' > "  
    st=st+1
	document.getElementById("campos").innerHTML+="<input type='hidden' id='exmax"+new String(max+1)+"' name='exmax"+new String(max+1)+"' size='3' readonly='readonly' >"
        max=max+1
	document.getElementById("campos").innerHTML+="<input type='text' maxlength='10' id='cant"+new String(cant+1)+"' name='cant"+new String(cant+1)+"' size='7' onclick=\"Recalcular(form3)\" onKeyUp=\"Recalcular(form3)\" onKeyPress=\"return validar_texto(event)\" autocomplete='off' >"
	cant=cant+1
	document.getElementById("campos").innerHTML+="<input type='text' id='des"+new String(des+1)+"'  name='des"+new String(des+1)+"' size='30' readonly='readonly'  value='0'>"
	des=des+1
	document.getElementById("campos").innerHTML+="<input type='text' maxlength='10' id='valun"+new String(valun+1)+"' name='valun"+new String(valun+1)+"' size='8' onKeyUp=\"Recalcular(form3)\" onKeyPress=\"return precio(event)\">"
	valun=valun+1
	document.getElementById("campos").innerHTML+="<input type='text' id='valtotal"+new String(valtotal+1)+"' name='valtotal"+new String(valtotal+1)+"' size='9' readonly='readonly'><br>"
	valtotal=valtotal+1
    }
}
 
//********** FORMA DE PAGO PARA COMPRA ********//
 var contador1=1;

function AgregarFilas_FP_Cpras(){
   
//	alert("agregar filkkk");
	cadena = "";
    cadena = cadena + "<div class='row'>";
    
    cadena = cadena + "<div class='col-lg-1'><a onclick=\"limpiarFilas_FP_Cpras("+contador1+");\" title='Limpiar fila'><i class='fa fa-times' aria-hidden='true'></i></a>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIdIvaS"+contador1+"' name='txtIdIvaS"+contador1+"'  readonly='readonly'>    <input type='hidden' style='margin: 0px; width: 100%;' class='text_input' id='txtIvaS"+contador1+"' name='txtIvaS"+contador1+"'  readonly='readonly'> </div>";
    
    cadena = cadena + "<div class='col-lg-1 border'><input  style='margin: 0px; width: 100%;' type='search' id='txtCodigoP"+contador1+"' name='txtCodigoP"+contador1+"' class='form-control border-0'   autocomplete='off'  placeholder='Buscar Forma...' onclick='lookup_FP_Cpra(this.value, "+contador1+", 4);' onKeyUp='lookup_FP_Cpra(this.value, "+contador1+", 4);' />  <div class='suggestionsBox' id='suggestions20"+contador1+"' style='display: none; margin-top: 0px; '> <div class='suggestionList' id='autoSuggestionsList20"+contador1+"'>  </div> </div>  </div>";
    
    cadena = cadena + "<div class='col-lg-3 border'><input type='search' style='margin: 0px; width: 100%;'class='form-control border-0' autocomplete='off'  id='txtDescripcionP"+contador1+"'  name='txtDescripcionP"+contador1+"'  value=''  >      </div> ";

    cadena = cadena + "<div class='col-lg-1 border'> <input type='text' maxlength='10' id='nrocpteC"+contador1+"' name='nrocpteC"+contador1+"' style='margin: 0px; width: 100%; text-align: right; ' class='form-control border-0'   autocomplete='off' > </div>";    

    cadena = cadena + "<div class='col-lg-1 border '><input type='text' style='margin: 0px; width: 100%; text-align: right; ' class='form-control border-0 bg-white' id='txtPorcentajeS"+contador1+"' name='txtPorcentajeS"+contador1+"' readonly='readonly' onKeyUp=\"calculoTotal_FP_Cpras("+contador1+")\" onclick=\"calculoTotal_FP_Cpras("+contador1+")\" autocomplete='off'  ></div>";
    
	cadena = cadena + "<div class='col-lg-2 border'> <input type='text' maxlength='10' id='txtValorS"+contador1+"' name='txtValorS"+contador1+"' style='margin: 0px; width: 100%; text-align: right; ' class='form-control border-0' onKeyUp=\"calculoTotal_FP_Cpras("+contador1+")\" onclick=\"calculoTotal_FP_Cpras("+contador1+")\"  autocomplete='off' > </div>";
    cadena = cadena + "<div class='col-lg-1 border' style='display:none' id='divCuotas"+contador1+"' > <input type='text' maxlength='4' id='txtCuotaS"+contador1+"' name='txtCuotaS"+contador1+"' onchange=\"listar_cuotas_Prov("+contador1+")\"  style='margin: 0px; width: 100%; text-align: right; ' class='form-control border-0'   autocomplete='off' > </div>";
    
      cadena = cadena + "<div class='col-lg-1 border ' style='display:none'  id='divDiasCuotas"+contador1+"' > <input type='text' maxlength='4' id='txtDiasPlazoS"+contador1+"' name='txtDiasPlazoS"+contador1+"' onchange=\"listar_cuotas_Prov("+contador1+")\"  style='margin: 0px; width: 100%; text-align: right; ' class='form-control border-0 bg-white'   autocomplete='off' > </div>";
 // cadena = cadena + "<td> <input type='text' maxlength='4' id='txtDiasPlazoS"+contador1+"' name='txtDiasPlazoS"+contador1+"' style='margin: 0px; width: 100%; text-align: right; ' class='form-control'   autocomplete='off' > </td>";
	cadena = cadena + "<div class='col-lg-1 border' style='display:none' id='divFechas"+contador1+"'> <input type='text' maxlength='20' id='txtFechaS"+contador1+"'  name='txtFechaS"+contador1+"' style='margin: 0px;font-size: 13; width: 100%; text-align: right; ' class='form-control border-0' onKeyUp=\"listar_cuotas_Prov("+contador1+")\" onclick=\"listar_cuotas_Prov("+contador1+")\"  autocomplete='off' > </div>";   
	
	cadena = cadena + "<input type='hidden'  maxlength='3' id='txtCuentaS"+contador1+"' name='txtCuentaS"+contador1+"' style='margin: 0px; width: 100%; text-align: right; ' class='form-control border-0'   autocomplete='off' >";
	cadena = cadena + "<input type='hidden' maxlength='10' id='txtTipoP"+contador1+"' name='txtTipoP"+contador1+"' style='margin: 0px; width: 100%; text-align: right; ' class='form-control border-0'   autocomplete='off' >";

	
	cadena = cadena + "</div>";
    //document.getElementById('txtContadorFilasFVC').value=contador;
    contador1++;
	
    $("#tablita ").append(cadena);
	//value="+fechaAcual+"
} 

function AgregarFilasC(){
    
    document.getElementById("campos").innerHTML+="<div class='col-lg-1 col-sm-1 col-xs-1 border '><span type='button' class='fa fa-times-circle-o m-2' name='img"+new String(img+1)+"' alt='limpiar' title='Eliminar' onclick=\"limpiar(form3,"+new String(contador)+")\" /></span></div> "
    img=img+1
    document.getElementById("campos").innerHTML+="<div class='col-lg-2 col-sm-2 col-xs-2 border'><input type='search' id='idproducto"+new String(idp+1)+"' name='idproducto"+new String(idp+1)+"'  class='form-control  border-0' placeholder='Buscar...'  onclick=\"lookup_cpra_edu(this.value, "+contador+", 6)\" onKeyUp=\"lookup_cpra_edu(this.value, "+contador+", 6)\" > <div class='suggestionsBox' id='suggestions10"+contador+"' style='display: none; margin-top: 0px; '>"+" <div class='suggestionList' id='autoSuggestionsList10"+contador+"'>  </div> </div></div>"
    contador=contador+1   
    idp=idp+1
    document.getElementById("campos").innerHTML+="<input type='hidden' id='stock"+new String(st+1)+"' name='stock"+new String(st+1)+"'  class='form-control  border-0' readonly='readonly' > "  
    st=st+1
	document.getElementById("campos").innerHTML+="<input type='hidden' id='exmax"+new String(max+1)+"' name='exmax"+new String(max+1)+"'  class=''readonly='readonly' >"
    max=max+1

	document.getElementById("campos").innerHTML+="<div class='col-lg-4 col-sm-4 col-xs-4 border'><input type='text' id='des"+new String(des+1)+"'  name='des"+new String(des+1)+"'   class='form-control  border-0 bg-white' readonly='readonly'  ></div>"
	des=des+1//                                                                                                                                                    onKeyUp=\"calculaCantidadFacturaVentaCondominios("+contador+")\"
		document.getElementById("campos").innerHTML+="<div class='col-lg-1 col-sm-1 col-xs-1 border'><input type='text' maxlength='10' id='cant"+new String(cant+1)+"' name='cant"+new String(cant+1)+"'    class='form-control  border-0' onclick=\"Recalcular(form3)\" onKeyUp=\"Recalcular(form3)\" onKeyPress=\"return validar_texto(event)\" autocomplete='off' ></div>"
	cant=cant+1
	document.getElementById("campos").innerHTML+="<div class='col-lg-2 col-sm-2 col-xs-2 border'><input type='text' maxlength='10' id='valun"+new String(valun+1)+"' name='valun"+new String(valun+1)+"'   class='form-control  border-0' onKeyUp=\"Recalcular(form3)\" onKeyPress=\"return precio(event)\"></div>"
	valun=valun+1
	document.getElementById("campos").innerHTML+="<div class='col-lg-2 col-sm-2 col-xs-2 border'><input type='text' id='valtotal"+new String(valtotal+1)+"' name='valtotal"+new String(valtotal+1)+"'   class='form-control  border-0 bg-white' readonly='readonly'></div>"	
	valtotal=valtotal+1    
	document.getElementById("campos").innerHTML+="<input type='hidden' id='ivaS"+new String(ivaS+1)+"' name='ivaS"+new String(ivaS+1)+"'  class='' readonly='readonly'>"
	document.getElementById("campos").innerHTML+="<input type='hidden' id='cuenta"+new String(cuenta+1)+"' name='cuenta"+new String(cuenta+1)+"' class='' readonly='readonly'>"
	cuenta=cuenta+1
	document.getElementById("campos").innerHTML+="<input type='hidden' id='txtIvaItemS"+new String(ivaS+1)+"' name='txtIvaItemS"+new String(ivaS+1)+"'  class='' readonly='readonly'>"
	document.getElementById("campos").innerHTML+="<input type='hidden' id='txtTotalItemS"+new String(ivaS+1)+"' name='txtTotalItemS"+new String(ivaS+1)+"' class='' readonly='readonly'>"
	ivaS=ivaS+1
    $("#campos ").append(cadena);
	//value="+fechaAcual+"
} 


function listar_cuotas_Prov(cont){
     var j=cont
    cadena = "<table id='tblListadoCuotas' class='table ' width='100%'>";
    cadena = cadena+"<thead>";
    cadena = cadena+"<tr>";
    cadena = cadena+"<th><strong>Ide</strong></th>";
    cadena = cadena+"<th><strong>Nombre</strong></th>";
    cadena = cadena+"<th><strong>Nro. Factura</strong></th>";
    cadena = cadena+"<th><strong>Cuota</strong></th>";
    cadena = cadena+"<th><strong>Fecha Pago</strong></th>";  
    cadena = cadena+"<th><strong>Dias Plazo</strong></th>"; 
    cadena = cadena+"</tr>";
    cadena = cadena+"</thead>";
    cadena = cadena+"<tbody>";
    
    //plazo = document.getElementById("txtCuotasTP").value;    
    plazo = $("#txtCuotaS"+j).val();
	//fecha_inicio = document.getElementById("txtFechaTP").value;    
    fecha_inicio = $("#txtFechaS"+j).val();
	empleado = document.getElementById("txtNombreRuc").value;    
    //empleado = $("#txtFechaS"+j).val();
	
	//total_credito = document.getElementById("txtDebeFP").value;
    total_credito = $("#txtValorS"+j).val();
	
	numero_factura = document.getElementById("txtFactura").value;
    //valor_cuotas = parseFloat(total_credito / plazo);
    valor_cuotax = $("#txtValorS"+j).val();
       dias = $("#txtDiasPlazoS"+j).val();
	//alert("valor_cuotax");
	//alert(valor_cuotax);
	valor_cuotas = valor_cuotax/plazo;
	
	//alert("CUOTA");
	//alert(valor_cuotas);
	
    contador = 0;
    //valor_restante = imp_final;
    for(i=0; i<plazo; i++){
        
        contador++;
        
        //valor_restante = (valor_restante - valor_cuotas).toFixed(2);
        // fecha = sumaFechaFVC(i-1,fecha_inicio);
          fecha = sumarDiasAFecha(fecha_inicio,dias )
        if(contador%2==0){            
            cadena = cadena+"<tr class='table' id='tr1'>";
            cadena = cadena+"<td>"+contador+"</td>";
            cadena = cadena+"<td>"+empleado+"</td>";
            cadena = cadena+"<td>"+numero_factura+"</td>";
            cadena = cadena+"<td>"+valor_cuotas.toFixed(2)+"</td>";
            cadena = cadena+"<td>"+fecha+"</td>";
            cadena = cadena+"<td>"+dias+"</td>";
            cadena = cadena+"</tr>";
        }
        if(contador%2==1){            
            cadena = cadena+"<tr class='table' id='tr2'>";
            cadena = cadena+"<td>"+contador+"</td>";
            cadena = cadena+"<td>"+empleado+"</td>";
            cadena = cadena+"<td>"+numero_factura+"</td>";
            cadena = cadena+"<td>"+valor_cuotas.toFixed(2)+"</td>";
            cadena = cadena+"<td>"+fecha+"</td>";
            cadena = cadena+"<td>"+dias+"</td>";
            cadena = cadena+"</tr>";
        }
          fecha_inicio=fecha;
    }    

    cadena = cadena+"</tbody>";
    cadena = cadena+"</table>";
    
    $("#div_listar_cuotasCpra").html(cadena);
	//  div_listar_cuotasCpra
	//  div_listar_cuotasCpra
}

function limpiarFilas_FP_Cpras(con){
   // alert ("entro "+con);
    //if($('#txtIdServicio'+con).val() >= 1)
    //{
    $("#txtIdIvaS"+con).val("0");
    $("#txtIvaS"+con).val("0");
    $("#txtCodigoP"+con).val("");
    $("#txtDescripcionP"+con).val("");
	$("#txtTipoP"+con).val("");
    $("#nrocpteC"+con).val("");
    $("#txtCantidadS"+con).val("");
    $("#txtPorcentajeS"+con).val("0");
    $("#txtValorS"+con).val("0");
    $("#txtCuentaS"+con).val("");
    calculoTotal_FP_Cpras1(con);
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
    //  asientosQuitadosFVC(); 
}

function limpiarFilas_Cpras(con)
{
	$("#txtNombreRuc"+con).val("");
	$("#txtRuc"+con).val("0");
	$("#idproducto"+con).val("");
	$("#cant"+con).val("");
	$("#des"+con).val("");
}
function lookup_FP_Cpra(txtNombre, cont, accion) {

 
    if(txtNombre.length == 0) {

        // Hide the suggestion box.
        $('#suggestions20'+cont).hide();
    } else {

        $.post("sql/compras.php", {queryString: txtNombre, cont: cont,  txtAccion: accion}, function(data){
           // alert("entro: "+data);
			if(data.length >0) {
                $('.suggestionsBox').hide();
                $('#suggestions20'+cont).show();
                $('#autoSuggestionsList20'+cont).html(data);
             //  alert("entro: "+data);
            }
        });
    }
} // lookup

// function fill10_FP_Cpras(cont, idServicio, cadena){
// //console.log(cadena);
 
//     setTimeout("$('.suggestionsBox').hide();", 50);

//     //thisValue.replace(" ","");
//     array = cadena.split("*");

//     // este if debe ir antes de asignar a los txt
//     if($('#txtCodigoP'+cont).val() >= 1){
//         // cuando no usa el boton limpiar significa que
//         // si hay cuenta agregada en la fila y solo esta remplazando por otra cuenta
//         // ya no vuelve a sumar cuantas cuentas estan agregadas
//     }else{
//         // cuando usa el boton limpiar
//         // significa que ha quitado la cuenta y cunado agrega una nueva suma cuantas cuentas estan agregadas
//       // revi abri  sumaAsientosAgregados =  $('#txtContadorAsientosAgregadosFVC').val();
//       //rev abri  sumaAsientosAgregados ++;
//      //rev abr   $('#txtContadorAsientosAgregadosFVC').val(sumaAsientosAgregados);
//     }

//     $('#txtCodigoP'+cont).val(array[0]);
//     $('#txtDescripcionP'+cont).val(array[1]);
// 	$('#txtTipoP'+cont).val(array[2]);
    
//     var porcen = parseFloat(array[3]);
// 	$('#txtPorcentajeS'+cont).val(porcen.toFixed(2));
//     var txtCuenta1 = parseInt(array[4]);
//     $("#txtCuentaS"+cont).val(txtCuenta1);
// 	var tipoS = $("#txtTipoP"+cont).val();
// 	var sesion_tipo_empresax=parseInt(array[6]);
// //	alert(sesion_tipo_empresax);
	
// 	if (tipoS=="credito")
// 	{
// 		$('#txtFechaS'+cont).val(array[5]);
// 		$('#txtCuotaS'+cont).val(1);

//     }else if (tipoS=="cheque") {
//           $('#nrocpteC'+cont).val("");         
//     }

// 	if (tipoS=="retencion-iva")
// 	{
// 		if (porcen>0)
// 		{
// 			if (sesion_tipo_empresax==6)
// 			{
// 				var subtotal = document.getElementById("txtIva1").value;
// 				var valor=subtotal*porcen/100;
// 				$('#txtValorS'+cont).val(valor.toFixed(2));
// 				calculoTotal_FP_Cpras();			
// 			}
// 	    //if (porcen>0){
// 		}
// 	}	
	
// 	if (tipoS=="retencion-fuente")
// 	{
// 		if (porcen>0)
// 		{
// 			if (sesion_tipo_empresax==6)
// 			{
// 			var subtotal = document.getElementById("txtSubTotal").value;
// 			var valor=subtotal*porcen/100;
// 			$('#txtValorS'+cont).val(valor.toFixed(2));
// 			calculoTotal_FP_Cpras();
// 			}
			
// 		}
// 	    //if (porcen>0){
// 	}
	
	
//     if (tipoS=="cheque")
// 	{
//         $('#nrocpteC'+cont).focus();
//     }else 
// 	{
// 		$('#txtValorS'+cont).focus();
//     }

// }


function cambioSecuencialCheque2(tipoDocumento, accccion,cont){
	    
        var planCuenta = $("#txtCuentaS"+cont).val();
        ajax23=objetoAjax();
        ajax23.open("POST", "sql/libroDiario.php",true);
        ajax23.onreadystatechange=function(){
            
            if (ajax23.readyState==4){
            var respuesta23=ajax23.responseText;
            console.log(respuesta23);
            // document.frmAsientosContables.txtNumeroDocumento.value=respuesta23;
            $("#nrocpteC"+cont).val(respuesta23);
        }
    }
    ajax23.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax23.send("txtAccion="+accccion+"&tipoDocumento="+tipoDocumento+"&planCuenta="+planCuenta)

}	    
		    
function fill10_FP_Cpras(cont, idServicio, cadena){
// console.log(cadena);

setTimeout("$('.suggestionsBox').hide();", 50);

    //thisValue.replace(" ","");
    array = cadena.split("*");

    // este if debe ir antes de asignar a los txt
    if($('#txtCodigoP'+cont).val() >= 1){
        // cuando no usa el boton limpiar significa que
        // si hay cuenta agregada en la fila y solo esta remplazando por otra cuenta
        // ya no vuelve a sumar cuantas cuentas estan agregadas
    }else{
        // cuando usa el boton limpiar
        // significa que ha quitado la cuenta y cunado agrega una nueva suma cuantas cuentas estan agregadas
      // revi abri  sumaAsientosAgregados =  $('#txtContadorAsientosAgregadosFVC').val();
      //rev abri  sumaAsientosAgregados ++;
     //rev abr   $('#txtContadorAsientosAgregadosFVC').val(sumaAsientosAgregados);
 }

 $('#txtCodigoP'+cont).val(array[0]);
 $('#txtDescripcionP'+cont).val(array[1]);
 $('#txtTipoP'+cont).val(array[2]);

 var porcen = parseFloat(array[3]);
 $('#txtPorcentajeS'+cont).val(porcen.toFixed(2));
 var txtCuenta1 = parseInt(array[4]);
 
 $("#txtCuentaS"+cont).val(txtCuenta1);
 var tipoS = $("#txtTipoP"+cont).val();
 var sesion_tipo_empresax=parseInt(array[6]);
//	alert(sesion_tipo_empresax);

// console.log("tipoS",tipoS)
if (tipoS=="credito")
{
    let v_pendiente = document.getElementById("txtCambioFP").value;
    	if(v_pendiente.trim()==''){
    		 v_pendiente = document.getElementById("txtDebeFP").value
    	}
	document.getElementById("txtValorS"+cont).value = v_pendiente;
	
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
		
  $('#txtFechaS'+cont).val(array[5]);
  $('#txtCuotaS'+cont).val(1);
  $('#txtDiasPlazoS'+cont).val(30);
listar_cuotas_Prov(cont)

}else if (tipoS=="cheque") {
  $('#nrocpteC'+cont).val("");  
    console.log("cheque 22");
 console.log({array});
    cambioSecuencialCheque2('Cheque',8,cont);
}else if (tipoS=="retencion-iva"){
  if (porcen>0)
  {
     if (sesion_tipo_empresax==6)
     {
        var subtotal = document.getElementById("txtIva1").value;
        var valor=subtotal*porcen/100;
        $('#txtValorS'+cont).val(valor.toFixed(2));
        calculoTotal_FP_Cpras(cont);			
    }
	    //if (porcen>0){
      }
  }else if (tipoS=="retencion-fuente-inventarios"){
      if (porcen>0)
      {
         if (sesion_tipo_empresax==6)
         {
             let subtotal = document.getElementById("txtSubtotalInventarios").value;
             let valor=subtotal*porcen/100;
             $('#txtValorS'+cont).val(valor.toFixed(2));
             calculoTotal_FP_Cpras(cont);
         }

     }
	    //if (porcen>0){
}else if (tipoS=="retencion-fuente-servicios"){
           if (porcen>0)
           {
              if (sesion_tipo_empresax==6)
              {
                  var subtotal = document.getElementById("txtSubtotalServicios").value;
                  var valor=subtotal*porcen/100;
                  $('#txtValorS'+cont).val(valor.toFixed(2));
                  calculoTotal_FP_Cpras(cont);
              }
     
            }
             //if (porcen>0){
        }else if (tipoS=="retencion-fuente-proveeduria"){
                if (porcen>0)
                {
                   if (sesion_tipo_empresax==6)
                   {
                       var subtotal = document.getElementById("txtSubtotalProveeduria").value;
                       var valor=subtotal*porcen/100;
                       $('#txtValorS'+cont).val(valor.toFixed(2));
                       calculoTotal_FP_Cpras(cont);
                   }
          
               }
                
    }else if (tipoS=="retencion-fuente-activos"){
                if (porcen>0)
                {
                   if (sesion_tipo_empresax==6)
                   {
                       var subtotal = document.getElementById("txtSubtotalActivos").value;
                       var valor=subtotal*porcen/100;
                       $('#txtValorS'+cont).val(valor.toFixed(2));
                       calculoTotal_FP_Cpras(cont);
                   }
          
               }
                
    }
    // else if (tipoS=="cheque"){
    //                     $('#nrocpteC'+cont).focus();
    // }
    else if(tipoS=="efectivo"){
            var subtotal = document.getElementById("txtTotal").value;
            var valor=subtotal*porcen/100;
            $('#txtValorS'+cont).val(valor.toFixed(2));
            //  calculoTotal_FP_Cpras(cont);
    }
    
    else 
    {
        console.log('ejecutando')
      $('#txtValorS'+cont).focus();
      calculoTotal_FP_Cpras(cont);
  }
calculoTotal_FP_Cpras(cont);
}		    
	
	

	    
function calculoTotal_FP_Cpras(con){
    //console.log("con",con);
	var j=0;
	j=con;
	valorTotal = $("#txtValorS"+j).val();
	calculoTotal_FP_Cpras1()  
}


function calculoTotal_FP_Cpras1(){
    var sumaValorTotal = 0;
    var valorTotal=0;
	var contador=5;
    for(i=1;i<=contador;i++)	{
            valorTotal = $("#txtValorS"+i).val();
            if(valorTotal == ""){   valorTotal=0;    }
        else
        { sumaValorTotal = sumaValorTotal + parseFloat(valorTotal); }
    }
    if(sumaValorTotal>0){
        document.getElementById('txtSubtotalFVC').value=(sumaValorTotal).toFixed(2);
        document.getElementById('txtPagoFP').value=(sumaValorTotal).toFixed(2);
        var debe1=document.getElementById('txtDebeFP').value;
        var cambio1=parseFloat(debe1)-parseFloat(sumaValorTotal);
        document.getElementById('txtCambioFP').value=(cambio1).toFixed(2);
    }
    
}


// * Mantemiento de compras 
function guardar_factura_compra(accion){
    if( document.getElementById('txtSubtotalFVC') && document.getElementById('txtDebeFP') ){
        if(document.getElementById('txtSubtotalFVC').value> document.getElementById('txtDebeFP').value){
            alertify.error("La cantidad a cobrar es mayor al total de la compra.");
           return;
        }
         if(document.getElementById('txtSubtotalFVC').value< document.getElementById('txtDebeFP').value){
            alertify.error("La cantidad a cobrar es menor al total de la compra.");
           return;
        }
    }
                var str = $("#form3,#frmFormaPago").serialize();
                document.getElementById('btnSalir').click();
                document.getElementById('form3').reset();  
                buscar_secuencial_compra(9);
                $.ajax({
                    url: 'sql/facturaCompra.php',
                    data: str+"&txtAccion="+accion,
                    type: 'post',
                    success: function(data){
                        console.log(data);
                       if(data) {
                            try {
                                let response = JSON.parse(data);
                               console.log('id del comprobante: ', response.idComprobante)
                               console.log('mensajes: ', response.mensajes)
                               console.log('logs: ', response.logs)
                                 if (confirm('Desea imprimir el Comprobante?')) {
                                       miUrl = "reportes/rptComprobanteDiario.php?txtComprobanteNumero="+response.idComprobante;
                                       window.open(miUrl,'noimporta','width=600, height=500, scrollbars=NO, titlebar=no');
                                        
                                }
                                response.mensajes.forEach(m => {
                                    alertify.success(m);
                                })
                                
                               
                                
                            } catch(e) {
                                alert(e); // error in the above string (in this case, yes)!
                            }
                        }
                       
                        
                        
                        
                        if(data==3){
                            alertify.warning("Faltan datos");
                        }
                    }
                });
                

}

/*
function abrirPdfLibroDiario(){

    miUrl = "reportes/rpt_libro_diario_basico.php?txtIdPeriodoContable="+document.getElementById("txtIdPeriodoContable").value+"&criterio_usu_per="+document.getElementById("criterio_usu_per").value+"&criterio_ordenar_por="+document.getElementById("criterio_ordenar_por").value+"&criterio_orden="+document.getElementById("criterio_orden").value+"&criterio_mostrar="+document.getElementById("criterio_mostrar").value+"&fecha_desde="+document.getElementById("txtFechaDesde").value+"&fecha_hasta="+document.getElementById("txtFechaHasta").value;
         console.log("miUrl",miUrl);
         window.open(miUrl,'informe del Libro Diario','width=600, height=600, scrollbars=NO, titlebar=no');

}
*/

 
 sumaFechaFVC = function(m, fecha){
     
    var Fecha = new Date();
    var sFecha = fecha || (Fecha.getDate() + "/" + (Fecha.getMonth() +1) + "/" + Fecha.getFullYear());    
    var sep = sFecha.indexOf('/') != -1 ? '/' : '-'; 
    var aFecha = sFecha.split(sep);
    var meses = m || 0;
    var mes = parseInt(aFecha[1]); 
    aFecha[1] = mes + meses;

    var fFecha = Date.UTC(aFecha[0],aFecha[1],aFecha[2])+(86400000); // 86400000 son los milisegundos que tiene un día
    var fechaFinal = new Date(fFecha);

    var anno = fechaFinal.getFullYear();
    var mes = fechaFinal.getMonth();
    mes = parseInt(mes)+1;
    var dia = fechaFinal.getDate();
    var mes = (mes < 10) ? ("0" + mes) : mes;
    var dia = (dia < 10) ? ("0" + dia) : dia;
    var fechaFinal = anno+sep+mes+sep+dia;

    return (fechaFinal);

 }


// NO SE USA

function tipoPago_cpra(valor,subtot_compra){
    //alert("va a grabar TIPOPAGO_CPRA");
	//alert(valor);
    // obtenemos el nombre del combobox
	var totcpra=subtot_compra.value;
	//alert(totcpra);
    var combo = document.getElementById(valor);
    var comboNombre = combo.options[combo.selectedIndex].text;
    //alert("combo");
	//alert(combo);
	//alert(comboNombre);

    /* Para obtener el idFormaPago */
    var idFormaPago = document.getElementById(valor).value;
    array = idFormaPago.split( "*" );
	//alert("PORCENTAJE="+array);
    //alert(array);
    
    var f = new Date();
    var fechaAcual = (f.getFullYear() + "-" + (f.getMonth() +1) + "-" + f.getDate());

//	var _formapago   = document.getElementById("cmbFormaPagoFP").value;
	var porcen = array;
	if (porcen>0)
	{
		var _precio=totcpra*porcen/100
	}
	else
	{
		porcen=" ";
		var _precio      = document.getElementById("txtPagoFP").value;
	}
	var valor_cuotas = parseFloat(_precio);	

//alert(valor_cuotas);	
	var _blanco      ="";
		
//		var cadena1="<tr><td>"+combo+"</td><td>"+_formapago+"</td><td>"+_blanco+"</td><td>"+_precio+"</td><td>"+_blanco+"</td></tr>";
//	var cadena1="<tr><td width='30'>"+indice_fp+"</td><td width='200'>"+comboNombre+"</td><td width='150'>"+_blanco+"</td><td width='150'>"+valor_cuotas.toFixed(2)+"</td><td>"+_blanco+"</td></tr>";
	var cadena1="<tr><td width='30'>"+porcen+"</td><td width='200'>"+comboNombre+"</td><td width='150'>"+_blanco+"</td><td width='150'>"+valor_cuotas.toFixed(2)+"</td><td>"+_blanco+"</td></tr>";

	$("#tablita").append(cadena1);  
	
   
    if(array[1] == "Ctas. por Cobrar"){
        cadena = "";
        cadena = cadena + "<tr>";
        cadena = cadena + "<td></td>";
        cadena = cadena + "<td></td>";
        cadena = cadena + "</tr>";
        cadena = cadena + "<tr style='font-size: 30px'>";
        cadena = cadena + "<td><label><strong>Cuotas:</strong></label></td>";
        cadena = cadena + "<td><input type='text' id='txtCuotasTP' name='txtCuotasTP' title='Ingrese un numero' style='font-size: 30px;  width: 100%' onKeyPress=\"return soloNumeros(event)\" onkeyup=\"listar_cuotasFVC()\" onClick=\"listar_cuotasFVC()\" class='text_input' maxlength='100' placeholder='Numero de cuotas' autocomplete='off'/></td>";
        cadena = cadena + "</tr>";
        cadena = cadena + "<tr style='font-size: 30px'>";
        cadena = cadena + "<td><label><strong>Dias Plazo:</strong></label></td>";
        cadena = cadena + "<td><input type='text' id='txtDiasPlazoTP' name='txtDiasPlazoTP' title='Ingrese un numero' style='font-size: 30px;  width: 100%' onKeyPress=\"return soloNumeros(event)\" class='text_input' maxlength='100' placeholder='Numero de dias plazo' autocomplete='off'/></td>";
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
       
    }else{
        /* limpia las filas */
        for(r=1; r<=4; r++){
            document.getElementById("tblTipoPago").deleteRow(4);
        }
        document.getElementById("tblListadoCuotas").remove();
        
        
    }
    
}

function guardar_fact_cpra_xml(accion){
    //PAGINA: nuevaFacturaCompra.php

    var idProveedor = document.form3['textIdProveedor'].value;
    var idProducto = document.form3['idproducto1'].value;
    var cantidad = document.form3['cant1'].value;
    if(idProveedor != ""){
        if(idProducto != ""){
            if(cantidad != ""){
                var str = $("#form3,#frmFormaPago").serialize();
               // console.log(str);
                $.ajax({
                    url: 'sql/facturaCompra_xml.php',
                    data: str+"&txtAccion="+accion,
                    type: 'post',
                    success: function(data){
                            console.log(data);
                           try {
                            let response = JSON.parse(data);
                            
                            alertify.success('Compra guardada correctamente ');
                            if (confirm('Desea imprimir el Comprobante?')) {
                                miUrl = "reportes/rptComprobanteDiario.php?txtComprobanteNumero="+response.numero_comprobante;

                                console.log(miUrl);
                                window.open(miUrl,'noimporta','width=600, height=500, scrollbars=NO, titlebar=no');
                                 
                         }
                        }catch(e) {
                            alert(e); // error in the above string (in this case, yes)!
                        }
                    //  console.log(data);
                    //     alertify.success(data);
                      //  document.getElementById('btnSalir').click();
                      //  document.getElementById('form3').reset();  
                //        buscar_secuencial_compra(9);
                    }
                });
                
            }else {
                alert ('Ingrese una cantidad.');
                document.form3.cant2.focus();
            }                        
        }else {
            alert ('Ingrese un Producto.');
        }
    }else {
        alert ('Ingrese un Proveedor.');
        document.form3.txtNombreRuc.focus();
    }
}



//var fm3=form3;
function AgregarProducto(cadena, fm3)
{        
//console.log("cadena",cadena);
//	alert(fm3);
	var fm=cadena;
	//var fm=fm3.txtBuscarProducto.value;	
	fn_cerrar();
	fm=fm+"-";	
	var tam=fm.length;
	var j;
	var cadena=" ";
	var cont=0;
	var id_prod;
	var producto;
	var precio;
	var stoc;
	var exismax;
	var iva="";
    var id_iva;
    var cantidad="";
	var cuenta="";
	
	for(i=0;i<tam;i++)
	{			
      j=fm.substring(i,i+1);
      if(j!="-")
	  {
		cadena=cadena+j;
	  }
      else
      {
        if(cont==0)
            id_prod=cadena;
        else if(cont==1)
              producto=cadena;
		//  alert(producto);
        else if(cont==2)
              precio=cadena;
        else if(cont==3)
              stoc=cadena;
        else if(cont==4)
              exismax=cadena;
        else if(cont==5)
              iva=cadena;
        else if(cont==6)
              id_iva=cadena;
        else if(cont==7)
              cuenta=cadena;
        else if(cont==8)
              cantidad=cadena;
		 // console.log("producto",producto);
		  //console.log("iva",iva);
		//  alert("cuenta");
	
		cadena="";
        cont++;               
      }
		
	}
	document.getElementById('divIva').innerHTML=12;
    document.getElementById('txtIdIva').value=id_iva;

	if(document.getElementById('des2').value==0)
	{            
      document.getElementById('exmax2').value=exismax;
      document.getElementById('des2').value=producto;
      document.getElementById('valun2').value=precio;
      document.getElementById('idproducto2').value=id_prod;
      document.getElementById('stock2').value=stoc;
	  document.getElementById('cant2').value=cantidad;
	  document.getElementById('cuenta2').value=cuenta;
	  document.getElementById('ivaS2').value=iva;	  
	}	
	else if(document.getElementById('des3').value==0)
	{
        document.getElementById('exmax3').value=exismax;
        document.getElementById('des3').value=producto;
        document.getElementById('valun3').value=precio;
        document.getElementById('idproducto3').value=id_prod;
        document.getElementById('stock3').value=stoc;
        document.getElementById('cant3').value=cantidad;
	    document.getElementById('cuenta3').value=cuenta;
	    document.getElementById('ivaS3').value=iva;
	  
	}
	else if(document.getElementById('des4').value==0)
	{
            document.getElementById('exmax4').value=exismax;
            document.getElementById('des4').value=producto;
            document.getElementById('valun4').value=precio;
            document.getElementById('idproducto4').value=id_prod;
            document.getElementById('stock4').value=stoc;
            document.getElementById('cant4').value=cantidad;
	  document.getElementById('cuenta4').value=cuenta;
	document.getElementById('ivaS4').value=iva;
	
	}
	else if(document.getElementById('des5').value==0)
	{
            document.getElementById('exmax5').value=exismax;
            document.getElementById('des5').value=producto;
            document.getElementById('valun5').value=precio;
            document.getElementById('idproducto5').value=id_prod;
            document.getElementById('stock5').value=stoc;
            document.getElementById('cant5').value=cantidad;
	  document.getElementById('cuenta5').value=cuenta;
	document.getElementById('ivaS5').value=iva;
	
	}
	else if(document.getElementById('des6').value==0)
	{
            document.getElementById('exmax6').value=exismax;
            document.getElementById('des6').value=producto;
            document.getElementById('valun6').value=precio;
            document.getElementById('idproducto6').value=id_prod;
            document.getElementById('stock6').value=stoc;
            document.getElementById('cant6').value=cantidad;
			  document.getElementById('cuenta6').value=cuenta;
	document.getElementById('ivaS6').value=iva;
	
	}
	else if(document.getElementById('des7').value==0)
	{
            document.getElementById('exmax7').value=exismax;
            document.getElementById('des7').value=producto;
            document.getElementById('valun7').value=precio;
            document.getElementById('idproducto7').value=id_prod;
            document.getElementById('stock7').value=stoc;
            document.getElementById('cant7').value=cantidad;
			  document.getElementById('cuenta7').value=cuenta;
	document.getElementById('ivaS7').value=iva;
	
	}
	else if(document.getElementById('des8').value==0)
	{
            document.getElementById('exmax8').value=exismax;
            document.getElementById('des8').value=producto;
            document.getElementById('valun8').value=precio;
            document.getElementById('idproducto8').value=id_prod;
            document.getElementById('stock8').value=stoc;
            document.getElementById('cant8').value=cantidad;
			document.getElementById('cuenta8').value=cuenta;
	        document.getElementById('ivaS8').value=iva;
	
	}
	else if(document.getElementById('des9').value==0)
	{
            document.getElementById('exmax9').value=exismax;
            document.getElementById('des9').value=producto;
            document.getElementById('valun9').value=precio;
            document.getElementById('idproducto9').value=id_prod;
            document.getElementById('stock9').value=stoc;
            document.getElementById('cant9').value=cantidad;
			  document.getElementById('cuenta9').value=cuenta;
			  document.getElementById('ivaS8').value=iva;
	
	
	}
	else if(document.getElementById('des10').value==0)
	{
                document.getElementById('exmax10').value=exismax;
                document.getElementById('des10').value=producto;
                document.getElementById('valun10').value=precio;
                document.getElementById('idproducto10').value=id_prod;
                document.getElementById('stock10').value=stoc;
                document.getElementById('cant10').value=cantidad;
			    document.getElementById('cuenta10').value=cuenta;
			    document.getElementById('ivaS10').value=iva;
	
	
	}
	else if(document.getElementById('des11').value==0)
	{
            document.getElementById('exmax11').value=exismax;
            document.getElementById('des11').value=producto;
            document.getElementById('valun11').value=precio;
            document.getElementById('idproducto11').value=id_prod;
            document.getElementById('stock11').value=stoc;
            document.getElementById('cant11').value=cantidad;
			  document.getElementById('cuenta11').value=cuenta;
			  document.getElementById('ivaS11').value=iva;
	
	
	}
	else if(document.getElementById('des12').value==0)
	{
            document.getElementById('exmax12').value=exismax;
            document.getElementById('des12').value=producto;
            document.getElementById('valun12').value=precio;
            document.getElementById('idproducto12').value=id_prod;
            document.getElementById('stock12').value=stoc;
            document.getElementById('cant12').value=cantidad;
			  document.getElementById('cuenta12').value=cuenta;
			  document.getElementById('ivaS12').value=iva;
	
	
	}
        else if(document.getElementById('des13').value==0)
	{
            document.getElementById('exmax13').value=exismax;
            document.getElementById('des13').value=producto;
            document.getElementById('valun13').value=precio;
            document.getElementById('idproducto13').value=id_prod;
            document.getElementById('stock13').value=stoc;
            document.getElementById('cant13').value=cantidad;
			  document.getElementById('cuenta13').value=cuenta;
	document.getElementById('ivaS13').value=iva;
	
	}
        
}	



var listaInventario=[];
var listaServicios=[];
var listaProveeduria=[];
var listaActivos=[];
function Recalcular2(fm3,posicion){

	var suma =0;
    var calculoIva = 0;
    var iva = 0;
    
    descuentoTotal = ($("#desc"+posicion).val()!='')?parseFloat( $("#desc"+posicion).val()):0;

    cantidad = parseFloat( $("#cant"+posicion).val());
    valorUnitario = parseFloat( $("#valun"+posicion).val());
    suma = parseFloat((valorUnitario * cantidad)-descuentoTotal);
    
    $("#valtotal"+posicion).val(suma.toFixed(4));
    
    
   
    let tipoS= $("#idTipo"+posicion).val();
    let posicionn= parseInt(posicion);
   
    console.log("==>",tipoS);
     if (tipoS=="1")
     {
    
        if(!listaInventario.includes(posicionn)){
            listaInventario.push(posicionn);
        }              
    }
    if (tipoS=="2")
    {
        if(!listaServicios.includes(posicionn)){
            listaServicios.push(posicionn);
        }   
    }
    if (tipoS=="3")
    {
        if(!listaActivos.includes(posicionn)){
            listaActivos.push(posicionn);
        }   
    }
    if (tipoS=="4")
    {
        if(!listaProveeduria.includes(posicionn)){
            listaProveeduria.push(posicionn);
        }   
    }

    
    Calcular_TotCpra()
}

function Recalcular33(fm3,posicion){

	var suma =0;
    var calculoIva = 0;
    var iva = 0;
    
    descuentoTotal = ($("#desc"+posicion).val()!='')?parseFloat( $("#desc"+posicion).val()):0;

    cantidad = parseFloat( $("#cant"+posicion).val());
    valorUnitario = parseFloat( $("#valun"+posicion).val());
    suma = parseFloat((valorUnitario * cantidad)-descuentoTotal);
    
    $("#valtotal"+posicion).val(suma.toFixed(4));
    
    
   
    let tipoS= $("#idTipo"+posicion).val();
    let posicionn= parseInt(posicion);
   
    console.log("==>",tipoS);
     if (tipoS=="1")
     {
    
        if(!listaInventario.includes(posicionn)){
            listaInventario.push(posicionn);
        }              
    }
    if (tipoS=="2")
    {
        if(!listaServicios.includes(posicionn)){
            listaServicios.push(posicionn);
        }   
    }
    if (tipoS=="3")
    {
        if(!listaActivos.includes(posicionn)){
            listaActivos.push(posicionn);
        }   
    }
    if (tipoS=="4")
    {
        if(!listaProveeduria.includes(posicionn)){
            listaProveeduria.push(posicionn);
        }   
    }

    
    Calcular_TotCpra()
}


function Calcular_TotCpra(){
   
    let totalInventario=0;
    let totalServicios=0;
    let totalProveeduria=0;
    let totalActivos=0;
    let vActual=0;
    let lInventario= listaInventario.length;
    let lServicios= listaServicios.length;
    let lProveeduria= listaProveeduria.length;
    let lActivos= listaActivos.length;
    
    
    
    
    let tipo_i=0;
    let sumaDescuentos=0;
    
    document.getElementById("txtDescuento").value= sumaDescuentos.toFixed(2);;
    
    for (i = 0; i < lInventario; i++) {
        vActual= listaInventario[i];
         tipo_i= $("#idTipo"+vActual).val();
        
        totales=parseFloat( $("#cant"+vActual).val())* parseFloat($("#valun"+vActual).val());
        descuentoTotal =($("#desc"+vActual).val()!='')? parseFloat( $("#desc"+vActual).val()):0;
        if (tipo_i=="1")
        {
            totalInventario= totalInventario +(  totales-descuentoTotal  );
        }
        sumaDescuentos = sumaDescuentos+descuentoTotal;
         
      } 
      document.getElementById("txtSubtotalInventarios").value= totalInventario.toFixed(2);
      vActual=0; 
      
      
    for (i = 0; i < lServicios; i++) {
        vActual= listaServicios[i];
        tipo_i= $("#idTipo"+vActual).val();
        totales=parseFloat( $("#cant"+vActual).val())* parseFloat($("#valun"+vActual).val());
        descuentoTotal = ( $("#desc"+vActual).val()!='')?parseFloat( $("#desc"+vActual).val()):0;
        if (tipo_i=="2")
        {
            totalServicios= totalServicios +(  totales-descuentoTotal );   
        }
        sumaDescuentos = sumaDescuentos+descuentoTotal;
    } 
    document.getElementById("txtSubtotalServicios").value= totalServicios.toFixed(2);;
    
    for (i = 0; i < lProveeduria; i++) {
        vActual= listaProveeduria[i];
        tipo_i= $("#idTipo"+vActual).val();
        totales=parseFloat( $("#cant"+vActual).val())* parseFloat($("#valun"+vActual).val());
        descuentoTotal = ( $("#desc"+vActual).val()!='')?parseFloat( $("#desc"+vActual).val()):0;
        if (tipo_i=="4")
        {
            totalProveeduria= totalProveeduria +(  totales-descuentoTotal );   
        }
        sumaDescuentos = sumaDescuentos+descuentoTotal;
    } 
    document.getElementById("txtSubtotalProveeduria").value= totalProveeduria.toFixed(2);
    
    for (i = 0; i < lActivos; i++) {
        vActual= listaActivos[i];
        tipo_i= $("#idTipo"+vActual).val();
        totales=parseFloat( $("#cant"+vActual).val())* parseFloat($("#valun"+vActual).val());
        descuentoTotal = ( $("#desc"+vActual).val()!='')?parseFloat( $("#desc"+vActual).val()):0;
        if (tipo_i=="3")
        {
            totalActivos= totalActivos +(  totales-descuentoTotal );   
        }
        sumaDescuentos = sumaDescuentos+descuentoTotal;
    } 
    document.getElementById("txtSubtotalActivos").value= totalActivos.toFixed(2);


var subTotItems=0 ;
var	pIva =0
var totIvaItems=0;
var tottems=0;	
var subTotCpra=0;
var totIvaCpra=0;
var totCpra=0;
var subtot01 = 0.0;
var subtot12 = 0.0;
let filasdetalle = document.getElementById('txtContadorFilasCpra').value;
var subtotalesPorPiva = {};

for(i=1;i<=filasdetalle;i++)	{
    
    
   subTotItems = parseFloat($("#valtotal"+i).val());
   
   pIva = $("#ivaS"+i).val();	
   
   
var subTotalIvaElement = $("#subTotal" + pIva);

if (subTotalIvaElement.length > 0) {
    // El campo de subtotal de IVA existe
    
    // alertify.success("El campo de subtotal de IVA existe.");
    // console.log("El campo de subtotal de IVA existe.");

    var subTotalIvaValue = subTotalIvaElement.val();
    
    console.log("Valor del campo de subtotal de IVA: " + subTotalIvaValue);
    // alertify.success("Valor del campo de subtotal de IVA: " + subTotalIvaValue);
    
    if (subTotItems>0){
        
    subTotCpra = subTotCpra + parseFloat(subTotItems);  
     
    }	
        
 
    
    totIvaItems=(parseFloat(subTotItems)*parseFloat(pIva))/100;
    
 
    
    totIvaCpra = totIvaCpra+parseFloat(totIvaItems);

    subtot12 += parseFloat(subTotItems);
        
    subtotalesPorPiva[pIva] = (subtotalesPorPiva[pIva] || 0) + subTotItems;

    if(parseInt(pIva)==0){
      subtot01=subtot01+ parseFloat(subTotItems);
    }
    
    if (subTotCpra>0){
     debe=document.getElementById('txtSubtotal').value=(subTotCpra).toFixed(2);	   	
    }
    
    if (totIvaCpra>0){
		debe1=document.getElementById('txtIva').value=(totIvaCpra).toFixed(2);	
	}

    
} else {
    // El campo de subtotal de IVA no existe
    alertify.warning("El campo de subtotal de IVA no existe." + pIva);
    console.log("El campo de subtotal de IVA no existe." + pIva);
}



    
}
for (var pIva in subtotalesPorPiva) {
    if (subtotalesPorPiva.hasOwnProperty(pIva)) {
        // Obtener el valor acumulado para el pIva actual
        var subtotalAcumulado = subtotalesPorPiva[pIva];

        // Verificar si el campo de subtotal de IVA correspondiente a pIva existe
        var subTotalIvaElement = $("#subTotal" + pIva);
         console.log("subTotalIvaElement " + subTotalIvaElement);
        if (subTotalIvaElement.length > 0) {
            // El campo de subtotal de IVA existe

            // Asignar el valor acumulado al campo de subtotal de IVA correspondiente
            subTotalIvaElement.val(subtotalAcumulado.toFixed(2));

            // Actualizar cualquier otra cosa que necesites hacer con este valor, como mostrarlo en la consola
            // console.log("El subtotal acumulado para pIva " + pIva + " es: " + subtotalAcumulado.toFixed(2));
        } else {
            // El campo de subtotal de IVA no existe
            console.log("El campo de subtotal de IVA no existe para pIva: " + pIva);
        }
    }
}

    // subTotal0=document.getElementById('subTotal0').value=(subtot01).toFixed(2);
    // subTotal12=document.getElementById('subTotal12').value=(subtot12).toFixed(2);

    


	totCpra = parseFloat(subTotCpra)			

	if(totIvaCpra>0)
	{
		totCpra = subTotCpra+parseFloat(totIvaCpra);
	} 

	if(totCpra)
	{
		debe2=document.getElementById('txtTotal').value=(totCpra).toFixed(2);
	}
	
}



//var fm3=form3;
//FUNCION QUE PERMITE RECALCULAR EL VALOR SUBTOTAL Y EL TOTAL
function Recalcular(fm3,condicion)
{
  //  console.log("fm3",fm3);
    //console.log("condicion",condicion);
	/*var subtot=fm3.txtSubtotal.value;	
	fm3.txtSubtotal.value=0;
	*/
    var iva=document.getElementById('divIva').innerHTML;        
	var subtot=0;	
	if(fm3.des2.value!=0)
	{
      if(condicion == 7){

      }else{
	    if((parseInt(fm3.stock2.value) + parseInt(fm3.cant2.value)) > parseInt(fm3.exmax2.value)){
            if(confirm("La cantidad ingresada supero la existencia Maxima: "+fm3.exmax2.value+". \nSeguro quieres continuar?")){

                    }else{
                        fm3.cant2.value = "";
                    }
                }
        }
        fm3.valtotal2.value=rounddecimal(fm3.cant2.value*fm3.valun2.value);
		Calcular_TotCpra();
     /*    fm3.txtSubtotal.value=rounddecimal(eval(subtot)+eval(fm3.valtotal2.value));
        subtot=eval(subtot)+eval(fm3.valtotal2.value);
		fm3.txtIva.value=rounddecimal((eval(fm3.txtSubtotal.value)*iva1)/100);
        fm3.txtTotal.value=rounddecimal(eval(fm3.txtSubtotal.value)+eval(fm3.txtIva.value));
 */	}
	if(fm3.des3.value!=0)
	{
            if((parseInt(fm3.stock3.value) + parseInt(fm3.cant3.value)) > parseInt(fm3.exmax3.value)){
                if(confirm("La cantidad ingresada supero la existencia Maxima: "+fm3.exmax3.value+". \nSeguro quieres continuar?")){

                }else{
                    fm3.cant3.value = "";
                }
            }
            fm3.valtotal3.value=rounddecimal(fm3.cant3.value*fm3.valun3.value);
			Calcular_TotCpra();
         /*    fm3.txtSubtotal.value=rounddecimal(eval(subtot)+eval(fm3.valtotal3.value));
            subtot=eval(subtot)+eval(fm3.valtotal3.value);
			fm3.txtIva.value=rounddecimal((eval(fm3.txtSubtotal.value)*iva)/100);
            fm3.txtTotal.value=rounddecimal(eval(fm3.txtSubtotal.value)+eval(fm3.txtIva.value));
 */	}
	if(fm3.des4.value!=0)
	{
            if((parseInt(fm3.stock4.value) + parseInt(fm3.cant4.value)) > parseInt(fm3.exmax4.value)){
                if(confirm("La cantidad ingresada supero la existencia Maxima: "+fm3.exmax4.value+". \nSeguro quieres continuar?")){

                }else{
                    fm3.cant4.value = "";
                }
            }
            fm3.valtotal4.value=rounddecimal(fm3.cant4.value*fm3.valun4.value);
			Calcular_TotCpra();
          /*   fm3.txtSubtotal.value=rounddecimal(eval(subtot)+eval(fm3.valtotal4.value));
            subtot=eval(subtot)+eval(fm3.valtotal4.value);
			
            fm3.txtIva.value=rounddecimal((eval(fm3.txtSubtotal.value)*iva)/100);
            fm3.txtTotal.value=rounddecimal(eval(fm3.txtSubtotal.value)+eval(fm3.txtIva.value));
 */	}
	if(fm3.des5.value!=0)
	{
            if((parseInt(fm3.stock5.value) + parseInt(fm3.cant5.value)) > parseInt(fm3.exmax5.value)){
                if(confirm("La cantidad ingresada supero la existencia Maxima: "+fm3.exmax5.value+". \nSeguro quieres continuar?")){

                }else{
                    fm3.cant5.value = "";
                }
            }
            fm3.valtotal5.value=rounddecimal(fm3.cant5.value*fm3.valun5.value);
			Calcular_TotCpra();
			
            /* fm3.txtSubtotal.value=rounddecimal(eval(subtot)+eval(fm3.valtotal5.value));
            subtot=eval(subtot)+eval(fm3.valtotal5.value);
            fm3.txtIva.value=rounddecimal((eval(fm3.txtSubtotal.value)*iva)/100);
            fm3.txtTotal.value=rounddecimal(eval(fm3.txtSubtotal.value)+eval(fm3.txtIva.value));
 */	}
	if(fm3.des6.value!=0)
	{
            if((parseInt(fm3.stock6.value) + parseInt(fm3.cant6.value)) > parseInt(fm3.exmax6.value)){
                if(confirm("La cantidad ingresada supero la existencia Maxima: "+fm3.exmax6.value+". \nSeguro quieres continuar?")){

                }else{
                    fm3.cant6.value = "";
                }
            }
            fm3.valtotal6.value=rounddecimal(fm3.cant6.value*fm3.valun6.value);
			Calcular_TotCpra();
			
         /*    fm3.txtSubtotal.value=rounddecimal(eval(subtot)+eval(fm3.valtotal6.value));
            subtot=eval(subtot)+eval(fm3.valtotal6.value);
            fm3.txtIva.value=rounddecimal((eval(fm3.txtSubtotal.value)*iva)/100);
            fm3.txtTotal.value=rounddecimal(eval(fm3.txtSubtotal.value)+eval(fm3.txtIva.value));
 */	}
	if(fm3.des7.value!=0)
	{
            if((parseInt(fm3.stock7.value) + parseInt(fm3.cant7.value)) > parseInt(fm3.exmax7.value)){
                if(confirm("La cantidad ingresada supero la existencia Maxima: "+fm3.exmax7.value+". \nSeguro quieres continuar?")){

                }else{
                    fm3.cant7.value = "";
                }
            }
            fm3.valtotal7.value=rounddecimal(fm3.cant7.value*fm3.valun7.value);
            Calcular_TotCpra();
            /*fm3.txtSubtotal.value=rounddecimal(eval(subtot)+eval(fm3.valtotal7.value));
            subtot=eval(subtot)+eval(fm3.valtotal7.value);
            fm3.txtIva.value=rounddecimal((eval(fm3.txtSubtotal.value)*iva)/100);
            fm3.txtTotal.value=rounddecimal(eval(fm3.txtSubtotal.value)+eval(fm3.txtIva.value));*/
	}
	if(fm3.des8.value!=0)
	{
            if((parseInt(fm3.stock8.value) + parseInt(fm3.cant8.value)) > parseInt(fm3.exmax8.value)){
                if(confirm("La cantidad ingresada supero la existencia Maxima: "+fm3.exmax8.value+". \nSeguro quieres continuar?")){

                }else{
                    fm3.cant8.value = "";
                }
            }
            fm3.valtotal8.value=rounddecimal(fm3.cant8.value*fm3.valun8.value);
            Calcular_TotCpra();
            /*
            fm3.txtSubtotal.value=rounddecimal(eval(subtot)+eval(fm3.valtotal8.value));
            subtot=eval(subtot)+eval(fm3.valtotal8.value);
            fm3.txtIva.value=rounddecimal((eval(fm3.txtSubtotal.value)*iva)/100);
            fm3.txtTotal.value=rounddecimal(eval(fm3.txtSubtotal.value)+eval(fm3.txtIva.value)); */
	}
	if(fm3.des9.value!=0)
	{
            if((parseInt(fm3.stock9.value) + parseInt(fm3.cant9.value)) > parseInt(fm3.exmax9.value)){
                if(confirm("La cantidad ingresada supero la existencia Maxima: "+fm3.exmax9.value+". \nSeguro quieres continuar?")){

                }else{
                    fm3.cant9.value = "";
                }
            }
            fm3.valtotal9.value=rounddecimal(fm3.cant9.value*fm3.valun9.value);
             Calcular_TotCpra();
            /*
            fm3.txtSubtotal.value=rounddecimal(eval(subtot)+eval(fm3.valtotal9.value));
            subtot=eval(subtot)+eval(fm3.valtotal9.value);
            fm3.txtIva.value=rounddecimal((eval(fm3.txtSubtotal.value)*iva)/100);
            fm3.txtTotal.value=rounddecimal(eval(fm3.txtSubtotal.value)+eval(fm3.txtIva.value));*/
	}
	if(fm3.des10.value!=0)
	{
            if((parseInt(fm3.stock10.value) + parseInt(fm3.cant10.value)) > parseInt(fm3.exmax10.value)){
                if(confirm("La cantidad ingresada supero la existencia Maxima: "+fm3.exmax10.value+". \nSeguro quieres continuar?")){

                }else{
                    fm3.cant10.value = "";
                }
            }
            fm3.valtotal10.value=rounddecimal(fm3.cant10.value*fm3.valun10.value);
             Calcular_TotCpra();
             /*
            fm3.txtSubtotal.value=rounddecimal(eval(subtot)+eval(fm3.valtotal10.value));
            subtot=eval(subtot)+eval(fm3.valtotal10.value);
            fm3.txtIva.value=rounddecimal((eval(fm3.txtSubtotal.value)*iva)/100);
            fm3.txtTotal.value=rounddecimal(eval(fm3.txtSubtotal.value)+eval(fm3.txtIva.value));*/
	}
	if(fm3.des11.value!=0)
	{
            if((parseInt(fm3.stock11.value) + parseInt(fm3.cant11.value)) > parseInt(fm3.exmax11.value)){
                if(confirm("La cantidad ingresada supero la existencia Maxima: "+fm3.exmax11.value+". \nSeguro quieres continuar?")){

                }else{
                    fm3.cant11.value = "";
                }
            }
            fm3.valtotal11.value=rounddecimal(fm3.cant11.value*fm3.valun11.value);
             Calcular_TotCpra();
             /*
            fm3.txtSubtotal.value=rounddecimal(eval(subtot)+eval(fm3.valtotal11.value));
            subtot=eval(subtot)+eval(fm3.valtotal11.value);
            fm3.txtIva.value=rounddecimal((eval(fm3.txtSubtotal.value)*iva)/100);
            fm3.txtTotal.value=rounddecimal(eval(fm3.txtSubtotal.value)+eval(fm3.txtIva.value));*/
	}
	if(fm3.des12.value!=0)
	{
            if((parseInt(fm3.stock12.value) + parseInt(fm3.cant12.value)) > parseInt(fm3.exmax12.value)){
                if(confirm("La cantidad ingresada supero la existencia Maxima: "+fm3.exmax12.value+". \nSeguro quieres continuar?")){

                }else{
                    fm3.cant12.value = "";
                }
            }
            fm3.valtotal12.value=rounddecimal(fm3.cant12.value*fm3.valun12.value);
             Calcular_TotCpra();
             /*
            fm3.txtSubtotal.value=rounddecimal(eval(subtot)+eval(fm3.valtotal12.value));
            subtot=eval(subtot)+eval(fm3.valtotal12.value);
            fm3.txtIva.value=rounddecimal((eval(fm3.txtSubtotal.value)*iva)/100);
            fm3.txtTotal.value=rounddecimal(eval(fm3.txtSubtotal.value)+eval(fm3.txtIva.value));*/
	}
        if(fm3.des13.value!=0)
	{
            if((parseInt(fm3.stock13.value) + parseInt(fm3.cant13.value)) > parseInt(fm3.exmax13.value)){
                if(confirm("La cantidad ingresada supero la existencia Maxima: "+fm3.exmax13.value+". \nSeguro quieres continuar?")){

                }else{
                    fm3.cant13.value = "";
                }
            }
            fm3.valtotal13.value=rounddecimal(fm3.cant13.value*fm3.valun13.value);
             Calcular_TotCpra();
             /*
            fm3.txtSubtotal.value=rounddecimal(eval(subtot)+eval(fm3.valtotal13.value));
            subtot=eval(subtot)+eval(fm3.valtotal13.value);
            fm3.txtIva.value=rounddecimal((eval(fm3.txtSubtotal.value)*iva)/100);
            fm3.txtTotal.value=rounddecimal(eval(fm3.txtSubtotal.value)+eval(fm3.txtIva.value));*/
	}
}



function calcular_total_cpra(){
    var suma =0;
	var totiva=0;
	var total1=0;
	var dec=0;
    for(i=1;i<=12;i++)
	{
		j=i+1
	    de = $("#valtotal"+j).val();
		//        txtIvaItemS
		de1 = $("#txtIvaItemS"+j).val();		
		de2 = $("#txtTotalItemS"+j).val();
	//	alert("de1"+de1);
		//alert("de2"+de2);
		
	    if(de > 0 )
		{
			suma = suma + parseFloat(de);
        }
		else
		{
			de=0;	
		}
		
        if(de1 > 0){
		//	alert("iva="+de1)
			totiva = totiva+ parseFloat(de1);
			//alert(totiva);
        }
        else
        {
			de1=0;
        }
        
        if(de2 >0){
			total1 = total1 + parseFloat(de2);
        }
        else
        {
            de2=0;        	
        }
		
		// alert("suma="+suma);
		// alert("totiva"+totiva);
		// alert("total1"+total1); 
		/* fm3.valtotal13.value=rounddecimal(fm3.cant13.value*fm3.valun13.value);
        fm3.txtSubtotal.value=rounddecimal(eval(subtot)+eval(fm3.valtotal13.value));
            subtot=eval(subtot)+eval(fm3.valtotal13.value);
            fm3.txtIva.value=rounddecimal((eval(fm3.txtSubtotal.value)*iva)/100);
            fm3.txtTotal.value=rounddecimal(eval(fm3.txtSubtotal.value)+eval(fm3.txtIva.value)); */
		if (suma > 0)
		{
			debe=document.getElementById('txtSubtotal').value=(suma).toFixed(4);	    
		}
		if (total1 > 0)
		{
		   debe1=document.getElementById('txtIva').value=(totiva).toFixed(4);
		}
        //txtTotalFVC
	}
		debe2=document.getElementById('txtTotal').value=(suma+totiva).toFixed(2);
	
	
/* 	dec=parseFloat(document.getElementById('txtTotalFVC').value);
	var ent=Math.floor(dec);
	dec=(dec%1)*10;
		if(dec>0)
			dec=(10-(parseFloat(dec)%1)*10)/100;
		else
			dec=0;
		if(document.getElementById('Redondeo').value>0 ){
				document.getElementById('txtOtrosFVC').value=dec.toFixed(4);
					totales=parseFloat(document.getElementById('txtSubtotalFVC').value)+parseFloat(document.getElementById('txtTotalIvaFVC').value)+parseFloat(dec);
		}else{
		    document.getElementById('txtOtrosFVC').value=0.00;
		   	totales=parseFloat(document.getElementById('txtSubtotalFVC').value)+parseFloat(document.getElementById('txtTotalIvaFVC').value);
		}
			
		document.getElementById('txtTotalFVC').value=totales.toFixed(2);
 */
}

/* function anular_compra(numero_cpra,accion)

{
//				  data: str+"&numeroFilaSelec="+nFila+"&txtAccion="+accion,
    var numero_compra=numero_cpra.value;
	//alert("accion"+accion);
	//alert("compra"+numero_compra);
	if(confirm("Desea anular la factura1?"))
	{
		if(confirm("Desea anular la factura?"))
		{
			$.ajax({
				url: 'sql/facturaCompra.php',
                data: 'numero_compra='+numero_compra+'&txtAccion='+accion,
                type: 'POST',
				success: function(data)
				{
                   //document.getElementById("mensajeFacturaVentaCondominios").innerHTML=data;
				}
			})
		       
 		}							
	}
} */

function anular_compra(numero_cpra, accion){
	
	// data: 'numero_cpra='+numero_compra+'&txtAccion='+accion,
                   
	var numero_compra=numero_cpra.value;
		var id_compra='';
	if(document.getElementById('textIdCompra')){
	    id_compra=document.getElementById('textIdCompra').value;
	}

    //PAGINA: ajax/formaPago.php
    var respuesta45 = confirm("Seguro desea eliminar este Factura? \nEsta acci\u00f3n eliminara de forma permanente la fila seleccionada");
    if (respuesta45){
           // alert("antes");
                
			$.ajax({
				    url: 'sql/facturaCompra.php',
                    data: 'numero_compra='+numero_compra+'&txtAccion='+accion+'&id_compra='+id_compra,
                    type: 'post',
                    success: function(data){
                            if(data!="")
                           // alert(data);
                            document.getElementById("mensaje3").innerHTML=""+data;
                            //listar_formas_pago();
                    }
            });
    }
}

function dev_compra2(txtIdProducto1,txtNombre1)
{
//	alert("devolucion1111");
	//alert(txtIdProducto1);
	//alert(txtNombre1);
	idproducto=txtIdProducto1.value;
	nombre=txtNombre1.value;
	//alert(idproducto);
	//alert(nombre);
//  $("#div_oculto").load("ajax/dev_compras.php", function(){	
	$("#div_oculto").load("ajax/dev_compras.php",{txtIdProducto1:idproducto,txtNombre1:nombre},  function(){
  
  
	$.blockUI({
			message: $('#div_oculto'),
			overlayCSS: {backgroundColor: '#111'},
			css:{
				'-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
				background: '', /* #f9f9f9*/
				top: '2%',
				position: 'absolute',
				width: '',
				left:'20%'
               // left: ($(window).width() - $('.caja').outerWidth())/2
			}
		});
        listar_vendedores();
	});
}

function lookup_Producto(txtNombre1) {
	//alert("buscar");
    //para agregar producto pagina: kardex.php
    if(txtNombre1.length == 0) {
        // Hide the suggestion box.
        $('#suggestions31').hide();
    } else {
        $.post("sql/buscarProducto_devol.php", {queryString: ""+txtNombre1+""}, function(data){
            if(data.length >0) {
                $('#suggestions31').show();
                $('#autoSuggestionsList31').html(data);
            }
        });
    }
} // lookup

function fill_Producto(id,thisValue) {
    $('#txtIdProducto1').val(id);
    $('#txtNombre1').val(thisValue);
    setTimeout("$('#suggestions31').hide();", 200);
}


function limpiar_id(a,b,c,d){
    document.getElementById(a).value = "";
    document.getElementById(b).value = "";
    document.getElementById(c).value = "";
    document.getElementById(d).value = "";
    
}

function guardar_devolucion(accion){
	var str = $("#frmDevolucion").serialize();
    $.ajax({
            url: 'sql/devolucion.php',
            type: 'post',
			data: str+"&accion="+accion,
            // para mostrar el loadian antes de cargar los datos
			beforeSend: function()
			{
            //imagen de carga
            $("#mensaje1").html("<p align='center'><img src='images/loading.gif' /></p>");
            },
            success: function(data){
                document.getElementById("mensaje1").innerHTML=data;
                //listar_vendedores();
               }
            });
}


////////   SUBIR XML 

function archivoXML(files) {
//console.log("files",files);
  
if(files.length == 0) 
	{        
            alertify.error("No hay Archivo");
    } 
	else 
	{    
		//alert("antes de factura");
		//$.post("sql/subirarchivoAjx.php", {files: '/tmp/phpf9hLg0xmlï»¿' }, function(data)
		$.post("sql/subirarchivoAjx.php", {files: files }, function(data)
		{
		   
 			if(data.length > 0)
			{	 console.log("data",data);	}
			else
			{
	            alertify.error("no hay data");
			}
		});
    }

} // lookup

function sumarDiasAFecha(fecha, dias) {
    dias=parseInt(dias);
    var partesFecha = fecha.split("-");
  var anio = parseInt(partesFecha[0]);
  var mes = parseInt(partesFecha[1]) - 1; // Restamos 1 al mes porque en JavaScript los meses van de 0 a 11
  var dia = parseInt(partesFecha[2]);
  
  var nuevaFecha = new Date(anio, mes, dia);

 
    nuevaFecha.setHours(0, 0, 0, 0);
    nuevaFecha.setTime(nuevaFecha.getTime() + (dias * 24 * 60 * 60 * 1000));

    
    var anio = nuevaFecha.getFullYear();
    var mes = ("0" + (nuevaFecha.getMonth() + 1)).slice(-2);
    var dia = ("0" + nuevaFecha.getDate()).slice(-2);
    
    var fechaFormateada = anio + "-" + mes + "-" + dia;
    return fechaFormateada;
  }




