function enlaces(){
    $("#div_oculto").load("ajax/enlaces.php", function(){
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
        listar_vendedores();
	});
}

function mostrarPlanCuentasE(aux){
	/* alert('mostrar tipo punto'); */
    ajax1=objetoAjax();
    ajax1.open("POST", "sql/plan_cuentas.php",true);
    ajax1.onreadystatechange=function(){
        if (ajax1.readyState==4){
            var respuesta1=ajax1.responseText;
            asignaPlanCuentasE(respuesta1);
        }
    }
    ajax1.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax1.send("accion="+aux)
}

function asignaPlanCuentasE(cadena){
//	alert('asignar punto');
    array = cadena.split( "?" );
	/* alert(array); */
    limite=array.length;
    cont=1;
    cont2=1;
	cont3=1;
	
    limpiacmbPlanCtas();
    document.frmEnlaces.cmbPlanCtas.options[0] = new Option("Seleccione cuenta","0");
    for(i=1;i<limite;i=i+2){
        document.frmEnlaces.cmbPlanCtas.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }

    document.frmEnlaces.cmbPlanCtas.options[0] = new Option("Seleccione Cuenta","0");
    for(i2=1;i2<limite;i2=i2+2){
        document.frmEnlaces.cmbPlanCtas.options[cont2] = new Option(array[i2+1], array[i2]);
        cont2++;
    } 
	
	document.frmEnlaces.cmbPlanCtasD.options[0] = new Option("Seleccione Cuenta ","0");
    for(i3=1;i3<limite;i3=i3+2){
        document.frmEnlaces.cmbPlanCtasD.options[cont3] = new Option(array[i3+1], array[i3]);
        cont3++;
    } 
}
function limpiacmbPlanCtas()
{
    for (m=document.frmEnlaces.cmbPlanCtas.options.length-1;m>=0;m--){
        document.frmEnlaces.cmbPlanCtas.options[m]=null;
    }
     for (m2=document.frmEnlaces.cmbPlanCtas.options.length-1;m2>=0;m2--){
        document.frmEnlaces.cmbPlanCtas.options[m2]=null;
    } 
    for (m3=document.frmEnlaces.cmbPlanCtasD.options.length-1;m3>=0;m3--){
        document.frmEnlaces.cmbPlanCtasD.options[m3]=null;
    } 

}

function guardar_enlace(accion){
//	 alert(accion); 
	var str = $("#frmEnlaces").serialize();
//	alert(str);
    $.ajax(
	{
        url: 'sql/enlaces.php',
        type: 'post',
		data: str+"&accion="+accion,
        // para mostrar el loadian antes de cargar los datos
		beforeSend: function()
		{
            //imagen de carga
            $("#mensaje1").html("<p align='center'><img src='images/loading.gif' /></p>");
        },
        success: function(data)
		{
			document.getElementById("mensaje1").innerHTML=data;
            //listar_vendedores();
        }
    });
}




	     
function listar_vendedores(){
    //PAGINA: clientesCondominios.php 
	//alert("va a listar vendedores");
    var str = $("#frmVendedores").serialize();
    $.ajax({
            url: 'ajax/listarVendedores.php',
            type: 'get',
            data: str,
            success: function(data){
                $("#listar_vendedores").html(data);
            }
    });
}

function modificar_vendedor(id_vendedor){
    //PAGINA: cargos.php
    $("#div_oculto").load("ajax/modificarVendedor.php", {id_vendedor: id_vendedor}, function(){
        $.blockUI({
          message: $('#div_oculto'),
          overlayCSS: {backgroundColor: '#111'},
          css:{
                '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px',
                        position: 'absolute',
                        background: '#f9f9f9',
                        top: '20px',
                        left: '185px',
                        width: '620px'
                }
        });
		listar_vendedores();

    });
}

function guardarModificarVendedor(accion){
 //PAGINA: cargos.php
    var str = $("#form1").serialize();
    $.ajax({
            url: 'sql/vend_modificar.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data){
            document.getElementById('mensaje1').innerHTML+=""+data;
            listar_vendedores();
            }
    });

}




  
