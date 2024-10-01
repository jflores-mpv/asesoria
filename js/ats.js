
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

function listar_compras(){
     //PAGINA: kardex.php
	var str = $("#frmComprasats").serialize();
	$.ajax({
		url: 'ajax/atsCompras.php',
		type: 'get',
		data: str,
		success: function(data){
			$("#div_listar_compras").html(data);
		}
	});
}

function listar_reporte_ats(page){

    var str = $("#filtrosAts").serialize();

    $.ajax({
            url: 'ajax/listarAts.php',
            type: 'get',
            data: str+"&page="+page,
            success: function(data){
                // console.log(data);
                $("#listarAnexos").html(data);
                 //cantidad_formas_pago();
            }
    });
}

 function xmlAnexo(){
    miUrl = "reportes/reporteXMLanexo.php?id="+document.getElementById("mes").value;
}

function crear_ats()
{
	var accion="1";
    var tipoDeclaracion = document.frmAnexoAts['tipoDeclaracion'].value;
	var anio=document.frmAnexoAts['anio'].value;
    var mes = document.frmAnexoAts['periodo'].value;
	if (anio!="") 
	{
		var str = $("#frmAnexoAts").serialize();
	    console.log(str);
        $.ajax({
            url: 'sql/crear_ats.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data)
			{
				// console.log(data);
				alertify.success(data);  
	 		// 	document.frmAnexoAts.getElementById("mensaje").innerHTML=data;   		
			    listar_reporte_ats(1);
			    
			}
        });	
    }
	else 
	{
		alert("NO Entro");
    }
}

    function eliminarATS(url, accion,id) {
        
        $.ajax({
            url: 'sql/crear_ats.php',
            type: 'post',
            data: "url=" + url + "&txtAccion=" + accion+ "&id=" + id,
            
            success: function(data) {
                alertify.success(data);  
                // console.log(data);
                listar_reporte_ats(1);
            }
        });
        
    }

function descargarArchivo(url) {
  // Crea un elemento <a> invisible
  var link = document.createElement("a");
  link.style.display = "none";
  // Establece la URL del archivo a descargar
  link.href = url;
  // A単ade el elemento <a> al DOM
  document.body.appendChild(link);
  // Simula un clic en el enlace para descargar el archivo
  link.click();
  // Elimina el elemento <a> del DOM
  document.body.removeChild(link);
}


