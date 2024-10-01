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

function anticipos(){
    $("#div_oculto").load("ajax/anticipos.php", function()
	{
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
//        listar_vendedores();
	});
	
}


//function guardar_anticipos(accion,origen_ant){
//data: str+"&accion="+accion,
		
function guardar_anticipos(accion,origen_ant){
	//alert(accion);
	var str = $("#frmAnticipos").serialize();
	//alert(str);
    $.ajax(
	{
        url: 'sql/libroDiario_Anticipo.php',
        type: 'post',
		data: str+"&accion="+accion+"&origen_ant="+origen_ant,
        // para mostrar el loadian antes de cargar los datos
		beforeSend: function()
		{
            //imagen de carga
            $("#mensaje1").html("<p align='center'><img src='images/loading.gif' /></p>");
        },
        success: function(data)//
		{
			document.getElementById("mensaje1").innerHTML=data;
			//	document.frmAnticipos.btnGuardar.disabled=true;
			document.frmAnticipos.txtDescripcion.value="";
			document.frmAnticipos.txtValor.value="";
			
        
            //listar_vendedores();
        }
    });
}


function listar_libro_diario(){
	var str = $("#frm_libro").serialize();
	$.ajax({
		url: 'ajax/listarLibroDiario.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#div_listar_libroDiario").html(data);
		}
	});
}

 function facVenTipoPago_anticipo(id_cliente){

//alert(id_cliente)
    $("#div_oculto").load("ajax/tipoPago_anticipo.php",{id_cliente:id_cliente},  function(){
  
    $.blockUI
		 ({
             message: $('#div_oculto'),
			 overlayCSS: {backgroundColor: '#111'},
             css:{
                  '-webkit-border-radius': '10px',
				  '-moz-border-radius': '10px',

                   background: '', /*  #FFFFFF */
                 top: '5%',     
                 position: 'absolute',
                 width: '650px',
                 left: ($(window).width() - $('.caja').outerWidth())/2
               }
       });
		
   });

}     

function calculaVueltoTP_xx(txtValor, txtEfectivo){
    vuelto = txtEfectivo.value - txtValor.value;
    document.getElementById("txtCambioFP").value = vuelto.toFixed(2);    
    
}

function calculaAnticipo_x(txtAntAnterior, txtValor1, txtDebito){
	//alert("valor anticipo nuevo1111");
	var anticipo_ant=0;
	var tanticipo=0;
	var tsaldo=0;
	var txtValor=0;
	
	var tabla = document.getElementById('grilla1'),rIndex;
	//alert('longitud de tabla='+tabla.rows.length);
	var limite=tabla.rows.length-1
  	var i=0; 
	var n=0;
	var x=0;	
   	for(i=0;i<limite;i++)
	{
	    if(document.frmFormaPago.Anticipo[i].checked) 
		{
			//alert('entro para asignar valor22222');
			txtValor = document.frmFormaPago.Anticipo[i].value; 
			//alert("txtValor =="+txtValor);
			tanticipo = parseFloat(tanticipo) + parseFloat(txtValor);
        }	
    }
	
/*  	alert('nuevo valor anticipo');
	alert(tanticipo);
	alert('valor seleccionado');
	alert(txtValor);
	alert(txtDebito.value); 
 */ 
	if (tanticipo >= 0)
	{
		anticipo2=document.getElementById('txtAnticipo').value=(tanticipo).toFixed(2);	
	}
	tsaldo = txtDebito.value - tanticipo;
	var resultado = Math.round(tsaldo*100)/100;
	/* alert('saldo');
	alert(resultado); */	
	if (resultado != 0)
	{
		saldo2=document.getElementById('txtDebeFP').value=(resultado).toFixed(2);	
	}    
}

function calculaAnticipo_n(txtAntAnterior, txtValor, txtDebito){
	//alert("valor anticipo nuevo8888999");
	//alert(txtAntAnterior.value); 
	
	var anticipo_ant=0;
	var tanticipo=0;
	var tsaldo=0;
	if(txtAntAnterior.value != 0 ){
		// alert("entro");
		anticipo_ant=txtAntAnterior.value;
	}
	
/* 	alert('nuevo valor anticipo');
	alert(anticipo_ant);
	alert('valor seleccionado');
	alert(txtValor);
	alert(txtDebito.value); 
 */	
	tanticipo = parseFloat(anticipo_ant) + parseFloat(txtValor);
	//alert(tanticipo);
	if (tanticipo != 0)
	{
		anticipo2=document.getElementById('txtAnticipo').value=(tanticipo).toFixed(2);	
		    
	}
	tsaldo = txtDebito.value - tanticipo;
	var resultado = Math.round(tsaldo*100)/100;
	/* alert('saldo');
	alert(resultado); */	
	if (resultado != 0)
	{
		saldo2=document.getElementById('txtDebeFP').value=(resultado).toFixed(2);	
	}
	
	/* tsaldo = txtDebito.value - tanticipo;
	//alert('saldo');
	//alert(tsaldo);	
	if (tsaldo != 0)
	{
		saldo2=document.getElementById('txtSaldo').value=(tsaldo).toFixed(2);	
		    
	} */
	
   // document.getElementById("txtCambioFP").value = vuelto.toFixed(2);    
    
}


/* function calcular_anticipo(accion) 
{
    // retorna en bancos.php;
    
   // var cmbIdBancos = $('#cmbNombreCuentaCB').val();
    alert('CALCULAR ANTICIPOsql ');
    var str = $("#frmFormaPago").serialize();
	alert(str);
    $.ajax
	({
        url: 'sql/total_anticipos.php',
        type: 'post',
        data: str,
        success: function(data)
		{
            alert(data);
            codigo_sadoTotal = data.split("ô");
			alert(codigo_sadoTotal);
//			document.getElementById('txtSubtotalFVC').value=(sumaValorTotal).toFixed(2);
//			document.getElementById('txtAnticipo').value=(codigo_sadoTotal).toFixed(2);
			
			document.frmFormaPago.txtAnticipo = (codigo_sadoTotal).toFixed(2);
			
//			document.getElementById('txtContadorFilasFVC').value=contador;
			
			//$('#txtAnticipo').val(parseFloat(codigo_sadoTotal[0]).toFixed(2));
			
		//	$('#txtCodigoCB').val(codigo_sadoTotal[1]);
              
	   //  $('#txtSaldoConsolidacionCB').val(parseFloat(codigo_sadoTotal[0]).toFixed(2));
          //  $('#txtCodigoCB').val(codigo_sadoTotal[1]);
        }
    });    
} */


