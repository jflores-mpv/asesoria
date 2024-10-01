
function guardarFacturaVentaC()
{    
     let limite = parseFloat(document.getElementById('limiteCF').value);
    let totalVenta = parseFloat(document.getElementById('txtTotalFVC').value);
    let cedula =  document.getElementById('txtCedulaFVC').value;
    if(totalVenta>limite &&  cedula==9999999999999){
        alertify.error('Se ha superado el limite del total para registrar como Consumidor Final, son necesarios los datos del cliente.');
        return;
    }
    
    console.log("GUARDAR");
    // tipo = cmbTipoDocumentoFVC.value;
    tipo= document.getElementById("cmbTipoDocumentoFVC").value;
    valor = validaDatosFacVenCondominios('1',frmFacturaVentaCondominios);// retorna true o false
    
    if(valor == true){
        if (tipo==4)
		{
			//alert("AAA");
            guardarNotaCredito(1);
        }
        else if (tipo==5)
		{
		     let idFactura =  document.getElementById('idFactura').value; 
            if(idFactura!=''){
                guardarProforma(25);
            }else{
                guardarProforma(1);
            }
           
        }
		else
		{
			if (tipo==6)
			{	guardarGuia(1);}
			else
               TipoPago_Educativo();
        }        
    }
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
                     console.log(data);
                     if(data=='kardexSI' ){
                             alertify.success("Venta Realizada con exito");
                                                          				     var camposAntes = {};
document.querySelectorAll('#frmFacturaVentaCondominios input').forEach(function(input) {
    camposAntes[input.name] = input.value;
});

// Limpiar el formulario
document.getElementById('frmFacturaVentaCondominios').reset();
 var hiddenInputs = document.querySelectorAll('#frmFacturaVentaCondominios input[type="hidden"]');
        
        // Loop through each hidden input and clear its value
        hiddenInputs.forEach(function(input) {
             if (input.name == 'txtContadorFilasFVC' || input.name == 'sesion_id_empresa'|| input.name == 'limiteCF') {
                
              }else{
                  input.value = '0';
              }
        });
// Mostrar los valores de los campos antes de limpiar en la consola
console.log('Valores de los campos antes de limpiar:');
console.log(camposAntes);
                             numfac(cmbEst,cmbEmi,Tipo);
                             fn_cerrar();
                             pdfVentas();
                     }
                     else if(data=='kardex' ){
                             alertify.success("Venta Realizada con exito");
                                                         				     var camposAntes = {};
document.querySelectorAll('#frmFacturaVentaCondominios input').forEach(function(input) {
    camposAntes[input.name] = input.value;
});

// Limpiar el formulario
document.getElementById('frmFacturaVentaCondominios').reset();
 var hiddenInputs = document.querySelectorAll('#frmFacturaVentaCondominios input[type="hidden"]');
        
        // Loop through each hidden input and clear its value
        hiddenInputs.forEach(function(input) {
             if (input.name == 'txtContadorFilasFVC' || input.name == 'sesion_id_empresa'|| input.name == 'limiteCF') {
                
              }else{
                  input.value = '0';
              }
        });
// Mostrar los valores de los campos antes de limpiar en la consola
console.log('Valores de los campos antes de limpiar:');
console.log(camposAntes);
                             numfac(cmbEst,cmbEmi,Tipo);
                             fn_cerrar();
                             pdfVentas();
                        }else if(data=='guia' ){
                             alertify.success("Guia Realizada con exito");
                                                         				     var camposAntes = {};
document.querySelectorAll('#frmFacturaVentaCondominios input').forEach(function(input) {
    camposAntes[input.name] = input.value;
});

// Limpiar el formulario
document.getElementById('frmFacturaVentaCondominios').reset();
 var hiddenInputs = document.querySelectorAll('#frmFacturaVentaCondominios input[type="hidden"]');
        
        // Loop through each hidden input and clear its value
        hiddenInputs.forEach(function(input) {
            if (input.name == 'txtContadorFilasFVC' || input.name == 'sesion_id_empresa'|| input.name == 'limiteCF') {
                
              }else{
                  input.value = '0';
              }
        });
// Mostrar los valores de los campos antes de limpiar en la consola
console.log('Valores de los campos antes de limpiar:');
console.log(camposAntes);
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
                            

const regex = /kardex/;
const match = data.match(regex);

if (match) {
 
                                                         alertify.success("Venta Realizada con exito");
                                                         				     var camposAntes = {};
document.querySelectorAll('#frmFacturaVentaCondominios input').forEach(function(input) {
    camposAntes[input.name] = input.value;
});

// Limpiar el formulario
document.getElementById('frmFacturaVentaCondominios').reset();
 var hiddenInputs = document.querySelectorAll('#frmFacturaVentaCondominios input[type="hidden"]');
        
        // Loop through each hidden input and clear its value
        hiddenInputs.forEach(function(input) {
             if (input.name == 'txtContadorFilasFVC' || input.name == 'sesion_id_empresa'|| input.name == 'limiteCF') {
                
              }else{
                  input.value = '0';
              }
        });
// Mostrar los valores de los campos antes de limpiar en la consola
console.log('Valores de los campos antes de limpiar:');
console.log(camposAntes);
                             numfac(cmbEst,cmbEmi,Tipo);
                             fn_cerrar();
                             pdfVentas();
} else {
   alertify.error('Es necesario verificar que la factura este correcta.');
}

                           
                            //  alertify.error(data);
                     }
                 }
             });
         }else{  alert ('Total a pagar deber ser mayor que 0.');   }
     }
     
     
     
     
     function activarBoton() {

    if(document.getElementById("cmbTipoDocumentoFVC").value==1){
        document.getElementById("guardarEfectivo").setAttribute('type', 'button');
    }else{
        document.getElementById("guardarEfectivo").setAttribute('type', 'hidden');
    }
}

function cargarListaFacturas(valor) {
    console.log(valor);
    if(valor.length == 0) {
            $('#suggestionsFacAn').hide();
    } else {
            $.post("sql/facturaVentaEducat.php", {txtAccion:22,queryString: ""+valor+""}, function(data){
                    if(data.length >0) {
                            $('#suggestionsFacAn').show();
                            $('#autoSuggestionsFacAn').html(data);
                    }
            });
    }
} 


function fillFacAn(idVenta,establecimiento, emision, numeroFactura){
    document.getElementById("selectfacAn").value = establecimiento+'-'+emision+'-'+numeroFactura;
    document.getElementById("facAn").value = idVenta;
    lookup_notaCredito_educ(facAn.value,'', 4,cmbEst.value,cmbEmi.value);
    $('#suggestionsFacAn').hide();
    console.log({idVenta});
}


function mostrar_grilla_reembolso(){
let tipo = document.getElementById('cmbTipoDocumentoFVC').value;
let div = document.getElementById('div_reembolso');
    if(tipo==41){
        div.style="display:block";
    }else{
        div.style="display:none";
    }
}
var contadorRe=1;
function limpiarFilasReembolso(fila){
    document.getElementById('txtCedulaReembolso'+fila).value='';
    document.getElementById('txtTipoProveedor'+fila).value='01';
    document.getElementById('txtTipoDocumento'+fila).value='01';
    document.getElementById('txtEstablecimientoReembolso'+fila).value='';
    document.getElementById('txtEmisionReembolso'+fila).value='';

    document.getElementById('txtSecuencialReembolso'+fila).value='';
    document.getElementById('txtNumeroAutorizacion'+fila).value='';
}

function AgregarFilasReembolso(){
    
    cadena = "";
    cadena = cadena + "<div class='form-group '>";
    cadena = cadena + "<div class='input-group mt-1 bg-light'>";
    
    cadena = cadena + " <a  style='width:5% '  onclick=\"limpiarFilasReembolso("+contadorRe+");\" title='Limpiar fila' class='btn btn-outline-secondary fa fa-window-close'></a>     ";  

    cadena = cadena + "<input   type='text' id='txtCedulaReembolso"+contadorRe+"' name='txtCedulaReembolso"+contadorRe+"' class='form-control '   autocomplete='off'  placeholder='C&eacute;dula/R.U.C.'style='width:5% ' />   ";

    cadena = cadena + "<input type='text' style='width:5% ' class='form-control'  autocomplete='off'  id='txtCodigoPais"+contadorRe+"'  name='txtCodigoPais"+contadorRe+"' value='593' readonly  >";

    cadena = cadena + "<select style='width:10%' id='txtTipoProveedor"+contadorRe+"' name='txtTipoProveedor"+contadorRe+"'  class='form-select' ><option value='01'>Persona natural</option><option value='02'>Sociedad</option></select>";

    cadena = cadena + "<select style='width:10%' id='txtTipoDocumento"+contadorRe+"' name='txtTipoDocumento"+contadorRe+"'  class='form-select' ><option value='01'>FACTURA</option><option value='03'>LIQUIDACI&Oacute;N DE COMPRA DE BIENES Y PRESTACI&Oacute;N DE SERVICIOS</option><option value='04'>NOTA DE CR&Eacute;DITO</option><option value='05'>NOTA DE D&Eacute;BITO</option><option value='06'>GU&Iacute;A DE REMISI&Oacute;N</option><option value='07'>COMPROBANTE DE RETENCI&Oacute;N</option></select>";

    cadena = cadena + "<input type='text' maxlength='3' style='width:7%' style='text-align: center; ' id='txtEstablecimientoReembolso"+contadorRe+"' name='txtEstablecimientoReembolso"+contadorRe+"' class='form-control'  autocomplete='off' >";
    
    cadena = cadena + "<input type='text' style='text-align: right;width:5%' class='form-control'  id='txtEmisionReembolso"+contadorRe+"' name='txtEmisionReembolso"+contadorRe+"' autocomplete='off' maxlength='3' >";

    cadena = cadena + "<input type='text' style='text-align: right;width:5%' class='form-control'  id='txtSecuencialReembolso"+contadorRe+"' name='txtSecuencialReembolso"+contadorRe+"' autocomplete='off' maxlength='9' >";

    cadena = cadena + "<input type='text' style='text-align: right;width:5%' class='form-control'  id='txtFechaReembolso"+contadorRe+"' name='txtFechaReembolso"+contadorRe+"' autocomplete='off' onclick=\"displayCalendar(txtFechaReembolso"+contadorRe+",'yyyy-mm-dd',this)\"  >";

    cadena = cadena + "<input type='text' style='text-align: right;width:5%' maxlength='49' class='form-control'  id='txtNumeroAutorizacion"+contadorRe+"' name='txtNumeroAutorizacion"+contadorRe+"' autocomplete='off'  >";

   
    
    cadena = cadena + "</div>";
    // <div class=' border  py-2 form-control celeste ' style='width:5%'>Código </div>
    cadena = cadena + "</br><div id='tblBodyCompensacion"+contadorRe+"' ><div class='form-group'><input name='txtContadorFilasCompensacion"+contadorRe+"' id='txtContadorFilasCompensacion"+contadorRe+"' type='hidden' value='0'><div class='input-group mt-1'> <div  style='width:5%'> </div><a style='width:5%' onclick='AgregarFilasImpuestos("+contadorRe+");' class='btn btn-outline-secondary celeste'><i class='fa fa-plus' aria-hidden='true'></i></a><div class=' border  py-2 form-control celeste ' style='width:5%'>Código impuesto</div><div class=' border  py-2 form-control celeste' style='width:5%'>Código Porcentaje</div><div style='width:5% ' class=' border  py-2 form-control celeste'>Tarifa</div><div style='width:5% ' class=' border  py-2 form-control celeste'>Base imponible</div><div style='width:5% ' class=' border  py-2 form-control celeste'>Impuesto</div></div></div>";
    cadena = cadena + "</div>";

    cadena = cadena + "</div>";
    
    document.getElementById('txtContadorFilasReembolso').value=contadorRe;
    contadorRe++;
    $("#tblBodyReembolso").append(cadena);

}
function limpiarFilasImpuestos(id,fila){
    document.getElementById('txtCodigoImpuestoCompensacion'+id+'_'+fila).value='2';
    document.getElementById('txtPorcentajeCompensacion'+id+'_'+fila).value='0';
    document.getElementById('txtTarifaCompensacion'+id+'_'+fila).value='';
    document.getElementById('txtBaseImponible'+id+'_'+fila).value='';
    document.getElementById('txtImpuestoCompensacion'+id+'_'+fila).value='';   
}
function AgregarFilasImpuestos(id_reembolso){
    let contadorR = document.getElementById('txtContadorFilasCompensacion'+id_reembolso).value;
    contadorR++;
    cadena = "";
    cadena = cadena + "<div class='form-group '>";
    cadena = cadena + "<div class='input-group mt-1 bg-light'>";
    
    cadena = cadena + "<div  style='width:5%'> </div> <a  style='width:5% '  onclick=\"limpiarFilasImpuestos("+id_reembolso+","+contadorR+");\" title='Limpiar fila' class='btn btn-outline-secondary fa fa-window-close'></a>     ";  

    // cadena = cadena + "<input   type='text' id='txtCodigoCompensacion"+id_reembolso+"_"+contadorR+"' name='txtCodigoCompensacion"+id_reembolso+"_"+contadorR+"' class='form-control '   autocomplete='off'  placeholder='C&oacute;digo'style='width:5% ' />   ";

    cadena = cadena + "<select  style='width:5% ' class='form-select'   id='txtCodigoImpuestoCompensacion"+id_reembolso+"_"+contadorR+"'  name='txtCodigoImpuestoCompensacion"+id_reembolso+"_"+contadorR+"' ><option value='2'>IVA</option><option value='3'>ICE</option><option value='5'>IRBPNR</option></select>";

    cadena = cadena + "<select  style='width:5% 'id='txtPorcentajeCompensacion"+id_reembolso+"_"+contadorR+"'  name='txtPorcentajeCompensacion"+id_reembolso+"_"+contadorR+"'  class='form-select   bg-white' ><option value='0'>0%</option><option value='2'>12%</option><option value='3'>14%</option><option value='6'>No Objeto de Impuesto</option><option value='7'>Exento de IVA</option><option value='8'>IVA diferenciado</option></select>";

    cadena = cadena + "<input type='text' maxlength='10' style='width:5%' style='text-align: center; ' id='txtTarifaCompensacion"+id_reembolso+"_"+contadorR+"' name='txtTarifaCompensacion"+id_reembolso+"_"+contadorR+"' class='form-control'  autocomplete='off' >";
    
    cadena = cadena + "<input type='text' style='text-align: right;width:5%' class='form-control'  id='txtBaseImponible"+id_reembolso+"_"+contadorR+"' name='txtBaseImponible"+id_reembolso+"_"+contadorR+"' autocomplete='off'  >";

    cadena = cadena + "<input type='text' style='text-align: right;width:5%' class='form-control'  id='txtImpuestoCompensacion"+id_reembolso+"_"+contadorR+"' name='txtImpuestoCompensacion"+id_reembolso+"_"+contadorR+"' autocomplete='off'  >";

    cadena = cadena + "</div>";

    cadena = cadena + "</div>";
    
    document.getElementById('txtContadorFilasCompensacion'+id_reembolso).value=contadorR;
    contadorR++;
    
    $("#tblBodyCompensacion"+id_reembolso).append(cadena);

}
function agregar(event) {
     event.preventDefault();
    // Obtener el input de tipo file
    let inputFiles = document.getElementById("files4");
    console.log("inputFiles");
    // Verificar si se han seleccionado archivos
    if (inputFiles.files.length > 0) {
        // Crear un objeto FormData para almacenar los archivos
        let fd = new FormData();

        // Agregar cada archivo al objeto FormData
        for (let i = 0; i < inputFiles.files.length; i++) {
            fd.append("files[]", inputFiles.files[i]);
        }

        // Realizar la solicitud Fetch para enviar los archivos al servidor
        fetch('sql/ventasXml.php', {
            method: 'POST',
            body: fd,
        })
        .then(function(response) {
            if (response.status >= 200 && response.status < 300) {
                return response.text();
            }
            throw new Error(response.statusText);
        })
        .then(function(response) {
            let respuesta='';
            let json ='';
                        try {
                                json = JSON.parse(response);
                              console.log(respuestaObjeto);
                        } catch (error) {
                              respuesta = response;
                            const inicioJSON = respuesta.indexOf('{');
                              const finJSON = respuesta.lastIndexOf('}');
                              
                              if (inicioJSON !== -1 && finJSON !== -1) {
                                const parteValidaJSON = respuesta.substring(inicioJSON, finJSON + 1);
                                
                                 json = JSON.parse(parteValidaJSON);
                               
                              } else {
                                console.log('No se pudo encontrar una parte válida de JSON en la respuesta.');
                              }
                        }
                        
            try {
            
            

           if(json['errores'] !== undefined){
                        let errores = json['errores'].length;
                        for(let a=0; a<errores;a++){
                        alertify.error(json['errores'][a]);
                    }
                    }
                 
                    if(json['advertencias'] !== undefined){
                        let advertencias = json['advertencias'].length;
                        for(let c=1; c<advertencias;c++){
                        alertify.warning(json['advertencias'][c]);
                    }
                    }
                    if(json['mensajes'] !== undefined){
                        let mensajes = json['mensajes'].length;
                        for(let b=1; b<mensajes;b++){
                        alertify.success(json['mensajes'][b]);
                    }
                    }
                     if(json['numero_factura_venta'] !== ''){
                         document.getElementById('cmbTipoDocumentoFVC').value =json['tipo_doc'];    
                         document.getElementById('cmbEst').value=json['establecimiento'];
                           document.getElementById('cmbEmi').value=json['emision'];
                let tipo_documento = document.getElementById('cmbTipoDocumentoFVC').value;
                    if(tipo_documento==4){
                        VerComprobante();
                        activarBoton();
                      
                    }
                           let option = "<option value="+json['emision']+ " selected>"+json['txtEmision']+"</option>";
                            $("#cmbEmi").html(option);
                        document.getElementById('txtNumeroFacturaFVC').value =json['numero_factura_venta'];
                        const txtNumeroFacturaFVC = document.querySelector('#txtNumeroFacturaFVC');

                            txtNumeroFacturaFVC.focus();
                            txtNumeroFacturaFVC.click();
                     }

            } catch (error) {
                alert(error);
                console.log(error);
                alertify.success('Xml se guardo correctamente.');
                // alertify.error('Error al subir xml');
            }
        })
        .catch(function(error) {
            console.error('Error en la solicitud Fetch:', error);
            alertify.error('Error al subir xml');
        });
    } else {
        alertify.warning('Por favor, selecciona al menos un archivo XML.');
    }
}
function AgregarDetalleFacVentaEducativo(){
    let contador = document.getElementById('txtContadorDetalleFilasFVC').value;
    cadena = "";
    cadena = cadena + "<div class='form-group '>";
    cadena = cadena + "<div class='input-group mt-1 bg-light'>";
    
    cadena = cadena + "<input type='text' style='width:50% ' class='form-control'  autocomplete='off'  id='txtAtributoDetalle"+contador+"'  name='txtAtributoDetalle"+contador+"' placeholder='Nombre' value='' class='btn btn-outline-secondary'></a>";

    cadena = cadena + "<input type='text' style='text-align: right;width:50%  ' class='form-control   bg-white' id='txtValorDetalle"+contador+"' name='txtValorDetalle"+contador+"'  placeholder='Valor'  >";

    cadena = cadena + "</div>";
    cadena = cadena + "</div>";
    
    
    contador++;
    document.getElementById('txtContadorDetalleFilasFVC').value = contador;
    $("#tblDetalleFacVentaCondominios").append(cadena);
}

 function validar_excedente(cont ){
        let efectivo = $('#txtValorS'+cont).val();

   if (efectivo.length === 2 && efectivo.charAt(0) == '0' ) {
        efectivo = efectivo.slice(1);
        $('#txtValorS'+cont).val(efectivo);
    }

        if (efectivo.charAt(efectivo.length - 1) === '.') {
              return;
         }
              

        efectivo = parseFloat(efectivo);
        if( isNaN(efectivo) ){
             $('#txtValorS'+cont).val('0');
             calculoTotal_FP_Vtas(cont)
        }

        let tipo = $('#txtTipo1'+cont).val();
       
       
        if(tipo==4){
            listar_cuotas_Cliente(cont);
        }
        calculoTotal_FP_Vtas(cont) ;
      
    }
    
function guardarFormasPagoXML(accion,modo){
   // console.log("accion",accion);

    var SubtotalVta = document.frmFormaPagoVta['txtSubtotalVta'].value;
    var cmbEst  = document.frmFacturaVentaCondominios['cmbEst'].value;
    var cmbEmi  = document.frmFacturaVentaCondominios['cmbEmi'].value;
    var Tipo    = document.frmFacturaVentaCondominios['cmbTipoDocumentoFVC'].value;
    var modoFacturacion ='sql/facturaVentaEducat.php';
    
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
                    let respuesta ='';
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
                      
                       
                      if(respuesta['forma_pago'] == 'si'){
                           alertify.error("La venta ya fue facturada.");
                          return;
                      }
                        if(respuesta['guardo'] == '1' && respuesta['claveAcceso'] == 'SI'  ){
                            alertify.success("Felicidades haz realizado una venta más!");
				            document.getElementById('frmFacturaVentaCondominios').reset(); 
				            numfac(cmbEst,cmbEmi,Tipo);
				            fn_cerrar();
				            pdfVentas();
                        }else if( respuesta['guardo'] == '1' &&  respuesta['emision_tipoEmision'] != 'E' ){
				            alertify.success("Felicidades haz realizado una venta más!");
				            document.getElementById('frmFacturaVentaCondominios').reset(); 
				            numfac(cmbEst,cmbEmi,Tipo);
				            fn_cerrar();
				            pdfVentas();
			       	}else if(respuesta['guardo'] == '1' && respuesta['claveAcceso'] == 'NO' ){
				            alertify.success("La factura no se a realizado, intenta otra vez");
				            document.getElementById('frmFacturaVentaCondominios').reset(); 
				            numfac(cmbEst,cmbEmi,Tipo);
				            fn_cerrar();
				            pdfVentas();
			       	}
                    // else if(data=='guia' ){
				    //         alertify.success("Guia Realizada con exito");
				    //         document.getElementById('frmFacturaVentaCondominios').reset(); 
				    //         numfac(cmbEst,cmbEmi,Tipo);
				    //         fn_cerrar();
				    //         pdfVentas();
			       	// }
			       	else if( respuesta['venta_registrada'] == 'SI'){
				            alertify.error("Venta ya registrada");
					        fn_cerrar();
					         fn_cerrar();
				            pdfVentas();
			       	}
                    // else if(data=='1 NO' ){ //tipo documento 4

				    //         alertify.success("Felicidades haz realizado una venta más!");
				    //         alertify.error("Factura no Autorizada");
					//         fn_cerrar();
					//          fn_cerrar();
				    //         pdfVentas();
			       	// }
			       	// else if(data=='1 SI'){ //tipo documento 4
				    //         alertify.success("Felicidades haz realizado una venta más!");
				    //         alertify.error("Factura Autorizada");
					//         fn_cerrar();
					//          fn_cerrar();
				    //         pdfVentas();
			       	// }
			       	
			       	else{
				            alertify.error('No se guardo .');
		        	}
                        
                        // console.log(jsonObject);
                    } catch (error) {
                        // Handle the exception if JSON parsing fails
                        console.error('Error parsing JSON:', error.message);
                        alertify.error('No se guardo.');
                    }
   
                }
            });
        }else{  alert ('Total a pagar deber ser mayor que 0.');   }
    }
    
        function cargar_datos_adicionales(id_venta){
            if(document.getElementById("tblDetalleFacVentaCondominios")){
                 document.getElementById("tblDetalleFacVentaCondominios").innerHTML='';
              document.getElementById('txtContadorDetalleFilasFVC').value=1;
            }
   
    $.ajax
	({
        url: 'sql/factura_buscar_educat.php?',
        type: 'POST',
        data: 'txtAccion=16&id_venta='+id_venta,
        success: function(data){
            let response = JSON.parse(data);
             
            if(response.numFilas>1 && document.getElementById("tblDetalleFacVentaCondominios")){
                for(let a=1; a<response.numFilas; a++){
                    AgregarDetalleFacVentaEducativo();
                }
                for(let i=1; i<response.numFilas; i++){

                    document.getElementById('txtAtributoDetalle'+i).value= response.campo[i];
                     document.getElementById('txtValorDetalle'+i).value= response.descripcion[i];
                }
                
            }
            
            
            
        }
    });
}


function pdfProforma(){
    let accion = 26;
    let idFactura =  document.getElementById('idFactura').value; 
    $.ajax
		({
            url: 'sql/facturaVentaEducat.php',
            type: 'post',
            data: "txtAccion="+accion+'&idFactura='+idFactura,
            beforeSend: function(){
            },
            success: function(data){
                 let response = JSON.parse(data);
               
                 let formato=response.formato;
                let idVenta=response.id;
                 let documento=response.tipo_documento;
                

            if(formato==1){
                    if(documento==1||documento==41){
                        formatoF = 'rptFacturas_detallado.php';
                    }else if(documento==5){
                        formatoF = 'rptProforma_detallado.php';
                    }
            }
            if(formato==2){
                if(documento==1||documento==41){
                       formatoF = 'rptFacturas_detalladoMini.php' ;
                }else if(documento==5){
                    formatoF = 'rptProforma_detallado.php';
                }
            }
            if(formato==3){
                    if(documento==1||documento==41){
                       formatoF = 'rptFacturas_detalladoA5.php' ;
                }else if(documento==5){
                   formatoF = 'rptProforma_detallado.php';
                }
                    
                    
            }
    

 miUrl = "reportes/"+formatoF+"?txtComprobanteNumero="+idVenta;
 window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
                
  
                        
                        
                        
            }
        });
}
  function validaDatosFacVenCondominios_cobro_efectivo(accion, dml) {
  return new Promise((resolve, reject) => {
    var tipo= document.getElementById("cmbTipoDocumentoFVC").value;
    var direccionGuia= document.getElementById("DirDestino").value;
    var direccionGuiaOrigen= document.getElementById("DirOrigen").value;
    var idtipocliente= document.getElementById("idtipocliente").value;
     console.log("idtipocliente 2",idtipocliente);
    var choferGuia= document.getElementById("chofer_id").value;
    var facAn= document.getElementById("facAn").value;
    

    var textIdCliente = document.getElementById("textIdClienteFVC").value;
    var textCedula = document.getElementById("txtCedulaFVC").value;
    var txtTotal=document.getElementById("txtTotalFVC").value;
    var txtContadorAsientosAgregados=document.getElementById("txtContadorAsientosAgregadosFVC").value;
    var textFecha = document.getElementById("textFechaFVC").value;
    var txtNumeroFactura = document.getElementById("txtNumeroFacturaFVC").value;
    
    
   
    
    $('#txtAccionFVC').val(accion);
    let validadCantidadLleno=0;
    
    for(let z=1; z<=txtContadorAsientosAgregados;z++){
           if(document.getElementById("txtCantidadS"+z).value==''){
               if( document.getElementById("txtCodigoServicio"+z).value!=''){
                   
                     validadCantidadLleno=1;
               }
                console.log("z",z)
          
            // txtCodigoServicio1
            console.log("validadCantidadLleno",validadCantidadLleno)
           }
    }

    
    if (textIdCliente == "" || textIdCliente == 1)
    {
        alert ('Agregue un cliente.');
        dml.elements['txtCedulaFVC'].focus();
        resolve(false);
    }
    else if (txtContadorAsientosAgregados <= 0){
        alert ('No hay suficientes servicios para guardar.');
        dml.elements['txtCodigoServicio1'].focus();
        resolve(false);
    }
    else if (txtTotal == "" || txtTotal <= 0)
    {
        alert ('El total no puede ser nulo o cero.');
        dml.elements['txtCantidadS'].focus();
        resolve(false);
    }
            
    else if (textFecha == ""){
        alert ('La fecha no puede estar vacia.');
        dml.elements['textFechaFVC'].focus();
        resolve(false);
    }
    else if (txtNumeroFactura == ""){
        alert ('El numero de Registro no puede estar vacio.');
        dml.elements['txtNumeroFacturaFVC'].focus();
        resolve(false);
    }
    else if (textCedula == ""){
        alert ('El numero de Cedula/Ruc no puede estar vacio.');
        dml.elements['txtCedulaFVC'].focus();
        resolve(false);
    }else if (tipo == "6"){
         if (direccionGuia == ""){
            alert ('La direccion no puede estar vacia.');
            dml.elements['DirDestino'].focus();
            resolve(false);
         }else if (direccionGuiaOrigen == ""){
            alert ('La direccion de origen no puede estar vacia.');
            dml.elements['DirOrigen'].focus();
            resolve(false);
         }else if(choferGuia == "0"){
            alert ('Debe seleccionar chófer.');
            dml.elements['chofer_id'].focus();
            resolve(false);
         }
    }else if (tipo == "4"){
         if(facAn == "0"){
            alert ('Debe seleccionar factura relacionada.');
            dml.elements['facAn'].focus();
            resolve(false);
         }
    }else if (validadCantidadLleno ==1){
        alert ('Las cantidades de los productos no pueden estar vacios.');
        dml.elements['txtCedulaFVC'].focus();
        resolve(false);
    }

     resolve(true); 
    });
}
function guardarFacVentaEducativoEfectivo_cobro_efectivo(resultado){
    if(resultado==false){
        return;
    }
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
    //  if(document.getElementById('sesion_id_empresa')){
    //      if(sesion_id_empresa.value=='116' ){
    //           modoFacturacion='sql/facturaVentaEducat_test.php';
    //         }
    //  }
    
     if(SubtotalVta != 0){
     var str = $("#frmFacturaVentaCondominios").serialize();
     // console.log(str);
         $.ajax
         ({
             url: modoFacturacion,
             type: 'post',
             data: str+"&txtAccion=15&modo=100",
             beforeSend: function(){
             },
                 success: function(data){
                     console.log(data);
                     if(data=='kardexSI' ){
                             alertify.success("Venta Realizada con exito");
                                                         				     var camposAntes = {};
document.querySelectorAll('#frmFacturaVentaCondominios input').forEach(function(input) {
    camposAntes[input.name] = input.value;
});

// Limpiar el formulario
document.getElementById('frmFacturaVentaCondominios').reset();

    var hiddenInputs = document.querySelectorAll('#frmFacturaVentaCondominios input[type="hidden"]');
 

// Recorrer los campos ocultos, excluyendo el llamado 'txtContadorFilasFVC'
hiddenInputs.forEach(function(input) {
  if (input.name !== 'txtContadorFilasFVC' || input.name !== 'sesion_id_empresa'|| input.name !== 'limiteCF') {
    input.value = '0';
  }
});


// Mostrar los valores de los campos antes de limpiar en la consola
console.log('Valores de los campos antes de limpiar:');
console.log(camposAntes);
                             numfac(cmbEst,cmbEmi,Tipo);
                             fn_cerrar();
                             pdfVentas();
                     }
                                    if(data=='kardexSI' ){
                             alertify.success("Venta Realizada con exito sin autorizar");
                                                         				     var camposAntes = {};
document.querySelectorAll('#frmFacturaVentaCondominios input').forEach(function(input) {
    camposAntes[input.name] = input.value;
});

document.getElementById('frmFacturaVentaCondominios').reset();

    var hiddenInputs = document.querySelectorAll('#frmFacturaVentaCondominios input[type="hidden"]');
        
       // Recorrer los campos ocultos, excluyendo el llamado 'txtContadorFilasFVC'
hiddenInputs.forEach(function(input) {
  if (input.name == 'txtContadorFilasFVC' || input.name == 'sesion_id_empresa'|| input.name == 'limiteCF') {
                
              }else{
                  input.value = '0';
              }
});
// Mostrar los valores de los campos antes de limpiar en la consola
console.log('Valores de los campos antes de limpiar:');
console.log(camposAntes);
                             numfac(cmbEst,cmbEmi,Tipo);
                             fn_cerrar();
                             pdfVentas();
                     }
                     else if(data=='kardex' ){
                             alertify.success("Venta Realizada con exito");
                                                       				     var camposAntes = {};
document.querySelectorAll('#frmFacturaVentaCondominios input').forEach(function(input) {
    camposAntes[input.name] = input.value;
});

document.getElementById('frmFacturaVentaCondominios').reset();

    var hiddenInputs = document.querySelectorAll('#frmFacturaVentaCondominios input[type="hidden"]');
        
        // Recorrer los campos ocultos, excluyendo el llamado 'txtContadorFilasFVC'
hiddenInputs.forEach(function(input) {
  if (input.name == 'txtContadorFilasFVC' || input.name == 'sesion_id_empresa'|| input.name == 'limiteCF') {
                
              }else{
                  input.value = '0';
              }
});

// Mostrar los valores de los campos antes de limpiar en la consola
console.log('Valores de los campos antes de limpiar:');
console.log(camposAntes); 
                             numfac(cmbEst,cmbEmi,Tipo);
                             fn_cerrar();
                             pdfVentas();
                        }else if(data=='guia' ){
                             alertify.success("Guia Realizada con exito");
                                                        				     var camposAntes = {};
document.querySelectorAll('#frmFacturaVentaCondominios input').forEach(function(input) {
    camposAntes[input.name] = input.value;
});

document.getElementById('frmFacturaVentaCondominios').reset();

    var hiddenInputs = document.querySelectorAll('#frmFacturaVentaCondominios input[type="hidden"]');
        
        // Recorrer los campos ocultos, excluyendo el llamado 'txtContadorFilasFVC'
hiddenInputs.forEach(function(input) {
  if (input.name == 'txtContadorFilasFVC' || input.name == 'sesion_id_empresa'|| input.name == 'limiteCF') {
                
              }else{
                  input.value = '0';
              }
});
// Mostrar los valores de los campos antes de limpiar en la consola
console.log('Valores de los campos antes de limpiar:');
console.log(camposAntes);
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
                            

const regex = /kardex/;
const match = data.match(regex);

if (match) {
    alertify.success("Venta Realizada con exito");
                             document.getElementById('frmFacturaVentaCondominios').reset();

    var hiddenInputs = document.querySelectorAll('#frmFacturaVentaCondominios input[type="hidden"]');
        
        // Recorrer los campos ocultos, excluyendo el llamado 'txtContadorFilasFVC'
hiddenInputs.forEach(function(input) {
  if (input.name == 'txtContadorFilasFVC' || input.name == 'sesion_id_empresa'|| input.name == 'limiteCF') {
                
              }else{
                  input.value = '0';
              }
});
                             numfac(cmbEst,cmbEmi,Tipo);
                             fn_cerrar();
                             pdfVentas();
} else {
   alertify.error('Es necesario verificar que la factura este correcta.');
}

                           
                            //  alertify.error(data);
                     }
                        actualizar_hora();
                 }
             });
         }else{  alert ('Total a pagar deber ser mayor que 0.');   }
      
     }

     
function guardar_cobro_solo_efectivo(){
    validaDatosFacVenCondominios_cobro_efectivo('1',frmFacturaVentaCondominios).then(resultado => {
       
        guardarFacVentaEducativoEfectivo_cobro_efectivo(resultado);
    });
}
    
function actualizar_hora(){
    let ahora = new Date();
    let fechaHoraActual = ahora.getFullYear() + '-' + 
                         ('0' + (ahora.getMonth() + 1)).slice(-2) + '-' + 
                         ('0' + ahora.getDate()).slice(-2) + ' ' + 
                         ('0' + ahora.getHours()).slice(-2) + ':' + 
                         ('0' + ahora.getMinutes()).slice(-2) + ':' + 
                         ('0' + ahora.getSeconds()).slice(-2);
 if(document.getElementById("textFechaFVC")){
     document.getElementById("textFechaFVC").value = fechaHoraActual;
 }
}