<?php
    //Start session
    session_start();
    require('ver_sesion.php');
    //Include database connection details
    require_once('conexion.php');
    $sesion_tipo = $_SESSION["sesion_tipo"];
    $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION['sesion_id_periodo_contable'];
    $sesion_empresa_nombre = $_SESSION['sesion_empresa_nombre'];
    $sesion_tipo = $_SESSION["sesion_tipo"];
    $sesion_nombre = $_SESSION["sesion_nombre"];
    $sesion_apellido = $_SESSION["sesion_apellido"];

?>

<html lang="es-ES">
    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Clientes</title>
    
    <script src="https://kit.fontawesome.com/7816c50242.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">  
	<link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/alertify.css">
	<link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/themes/default.css">
    <link rel="stylesheet" type="text/css" href="librerias/select2/css/select2.css">
    <link rel="stylesheet" href="librerias/bootstrap/css/style.css"> 
    <script src="librerias/bootstrap/js/main.js"></script> 
	<script src="librerias/jquery-3.2.1.min.js"></script>
	<script src="librerias/bootstrap/js/bootstrap.js"></script>
	<script src="librerias/alertifyjs/alertify.js"></script>
    <script src="librerias/select2/js/select2.js"></script>
    <link rel="stylesheet" href="css/style.css">

    <script src="js/jquery.validate.js" type="text/javascript"></script>

    
    <script language="javascript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.blockUI.js"></script>
    
    <script language="javascript" type="text/javascript" src="js/jquery.validate.1.5.2.js"></script>
    <script language="javascript" type="text/javascript" src="js/index.js"></script>
    <script language="javascript" type="text/javascript" src="js/index_clientes.js"></script>
    <script language="javascript" type="text/javascript" src="js/reportes.js"></script>
    <script language="javascript" type="text/javascript" src="js/crm.js"></script>
	<!-- END ESTILOS Y CLASES PARA AJAX -->

    <!--validaciones de los campos -->
    <script type="text/javascript" src="js/validaciones.js"></script>
    <!-- funciones -->
    <script type="text/javascript" src="js/condominios.js"></script>
    <script type="text/javascript" src="js/clientes.js"></script>
	<!--END estilo para  el menu -->
    <link rel="shortcut icon" href="images/logo.png">

</head>

<body onLoad=" listar_reporte_clientes(1);comboCiudadClientes(3); ">

<div class="wrapper d-flex align-items-stretch celeste">

    <nav id="sidebar" ><?php  {include("menusNiveles.php");      }   ?></nav>
    
    <div id="content"  class="p-0 m-0">
    
    <div class="row m-0 w-100"><?php  {include("header.php");      }   ?>  </div>
 
    <div class="row  m-0 "> 
        
      
    <a class="col-lg-2 col-sm-2 text-decoration-none card p-1 m-1"   type="button" tabindex="5" name="submit" value="Cliente" id=""    onClick="href:residentes()" /><div class="my-icon3 "><i class="fa fa-user-plus mr-3 fa-2x"></i><span>Nuevo cliente</span>  </div></a>    
          
                


       <div class="row bg-white rounded col-lg-8 col-sm-6 mx-5">
    <form action="" method="post"
  name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">

    <div class="input-group" style="  margin-top: 15;" >
    <input type="file" name="file"  id="file" accept=".xls,.xlsx" class="form form-control"> 
  <div class="input-group-append">
 
    
    <button type="submit" id="submit" name="import"  class="btn btn-outline-secondary">Importar Registros 2</button>
    
</div>



<div class="input-group-append">
      <a href="sql/subidas/formato_clientes.xlsx" download="formato_clientes.xlsx">  
        <input type="button" id="descargar" name="descargar" class="btn btn-outline-secondary" value="Descargar Formato"></a>
    </div>
</div>

</form>
</div> 
        
    </div>


    <div id="mensaje3" ></div>
    
            <div  class="row mt-3">
                 <div class="col-lg-3 bg-white p-3 ">   
                <form name="frmReporteFacturas" id="frmReporteFacturas" method="post" >
                    
                            <label>Ingrese nombre de cliente para buscar</label>
                            <input type="text" name="txtBuscar" id="txtBuscar" value="" class="form-control" placeholder="Buscar.." onkeyup="listar_reporte_clientes(1);"/>

                            <label># C&eacute;dula</label>
                            <input type="text" name="txtCedulaCliente" id="txtCedulaCliente" value="" class="form-control" placeholder="Buscar.." onkeyup="listar_reporte_clientes(1);"/>

                            <label>Ciudad</label>
                            <select name="txtCiudad" id="txtCiudad" class="form-control" onChange="listar_reporte_clientes(1);"></select>
                           
                            <label>Provincia</label>
                            <select name="txtProvincias" id="txtProvincias" class="form-control" onChange="comboCiudadClientes(3)">
                            <option value="0">Seleccione Provincia</option>
                            <?php 
                                $sqlProvincias ="SELECT `id_provincia`, `provincia` FROM `provincias` WHERE id_pais=1";
                                $resultProvincias= mysql_query($sqlProvincias);

                                while($rowProv = mysql_fetch_array($resultProvincias)){
                                    ?>
                                     <option value="<?php echo $rowProv['id_provincia'] ?>"><?php echo $rowProv['provincia'] ?></option>
                                    <?php
                                }
                            ?>
                               
                            </select>
                            <div class="col-lg-12">
                        <label>Ordenar</label>
                        <select class="form-select required" name="criterio_ordenar_por" id="criterio_ordenar_por" onChange="listar_reporte_clientes(1);">
                                   
                                 
                                     <option value=" clientes.`cedula` ">C&eacute;dula</option>
                                    <option value=" clientes.`nombre` " selected="selected">Nombre</option>
                               
                                    <option value=" clientes.`fecha_registro` ">Fecha ingreso</option>
                                </select>
                    </div>    
                    <div class="col-lg-12">
                        <label>Registros</label>
                        <select class="form-select" name="criterio_mostrar" id="criterio_mostrar" onChange="listar_reporte_clientes(1);">
                                	<option value="1">1</option>
                                	<option value="2">2</option>
                                	<option value="5">5</option>
                                	<option value="10">10</option>
                                	<option value="20" selected="selected">20</option>
                                	<option value="40">40</option>
                                        <option value="1000000">Todos</option>
                                </select>
                    </div>    
                    <div class="col-lg-12">
                        <label>En</label>
                        <select class="form-select required" name="criterio_orden" id="criterio_orden" onChange="listar_reporte_clientes(1);">
                                    <option value="asc">Ascendente</option>
                                    <option value="desc">Descendente</option>
                                </select>
                    </div>
                         <?php 
    if($sesion_id_empresa == '116'  || $sesion_id_empresa==1827 ){
        ?>
        <div class="col-lg-12">
                        <label>Tipo Cliente:</label>
                        <select class="form-select required" name="estado_general_cliente" id="estado_general_cliente" onChange="actualizarSelectEstados()">
                            <option value="0">TODOS</option>
                            
                            <option value="CLIENTES">CLIENTES</option>
                            <option value="AFILIADOS">AFILIADOS</option>
                                    
                                </select>
                    </div>
                    
                     <div class="col-lg-12">
                        <label>Estados:</label>
                        <select class="form-select required" name="estado_cliente" id="estado_cliente" onChange="listar_reporte_clientes(1);">
                            <option value="0">TODOS</option>
                                    
                                </select>
                    </div>
                    
                   <?php 
    }
    
   
        ?>      
        
            <div class="col-lg-12">
                    <label>Ingrese nombre de vendedor para buscar</label>
                            <input type="text" name="txtBuscarVendedor" id="txtBuscarVendedor" value="" class="form-control" placeholder="Buscar.." onkeyup="listar_reporte_clientes(1);"/>
                 </div>
       
                        <div class="col-lg-12 my-2">
                <input id="button" type="button" value="REPORTE" class="btn btn-info text-white w-100" onclick="pdfClientes()"></input>
            </div>
           
                    
                        </form>
                </div>
                <div class="col-lg-9"> 
                            <div id="div_listar_reporte_facturas">  </div> 
                </div>            
                    
            </div>
 
</div>      
            
     <div id="div_oculto1" style="display: none;"></div>
     <div id="div_oculto" style="display: none;"></div>
      <script>
      function actualizarSelectEstados(){
          let estado_cliente= document.getElementById('estado_cliente');
          let estado_general_cliente =document.getElementById('estado_general_cliente').value; 
          
          for (m=estado_cliente.options.length-1;m>=0;m--){
        estado_cliente.options[m]=null;
    }
    
    
    if(estado_general_cliente=='CLIENTES'){
        estado_cliente.options[0] = new Option("TODOS","0");
        estado_cliente.options[1] = new Option("ACTIVO","Activo");
        estado_cliente.options[2] = new Option("INACTIVO","Inactivo");
    }
    if(estado_general_cliente=='AFILIADOS'){
          estado_cliente.options[0] = new Option("TODOS","0");
    estado_cliente.options[1] = new Option("AL DIA","AL DIA");
    estado_cliente.options[2] = new Option("MORA","MORA");
    estado_cliente.options[3] = new Option("REZAGADO","REZAGADO");
    estado_cliente.options[4] = new Option("SUSPENDIDO","SUSPENDIDO");
    estado_cliente.options[5] = new Option("TRAMITE","TRAMITE");
    estado_cliente.options[6] = new Option("CANJE","CANJE");
    estado_cliente.options[7] = new Option("INCOMBRABLE","INCOMBRABLE");
    }
    if(estado_general_cliente=='0'){
          estado_cliente.options[0] = new Option("TODOS","0");
     estado_cliente.options[1] = new Option("ACTIVO","Activo");
    estado_cliente.options[2] = new Option("INACTIVO","Inactivo");
    estado_cliente.options[3] = new Option("AL DIA","AL DIA");
    estado_cliente.options[4] = new Option("MORA","MORA");
    estado_cliente.options[5] = new Option("REZAGADO","REZAGADO");
    estado_cliente.options[6] = new Option("SUSPENDIDO","SUSPENDIDO");
    estado_cliente.options[7] = new Option("TRAMITE","TRAMITE");
    estado_cliente.options[8] = new Option("CANJE","CANJE");
    estado_cliente.options[9] = new Option("INCOMBRABLE","INCOMBRABLE");
    }
    
  listar_reporte_clientes(1);
   
        
    
      }
     
      
      function actualizar_estados(){
    
   	$.ajax({
		url: 'sql/membresias.php',
		data: 'txtAccion=14',
    type: 'post',
    success: function(data){

			
				alertify.success("Se actualizo el estado de los clientes con exito :)");
			listar_reporte_clientes();
		  
		
    }
  }); 
}
function pdfClientes(){
    let formulario  = $("#frmReporteFacturas").serialize();
    
      miUrl = "reportes/rptClientes.php?"+formulario;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
}

function comboCiudadClientes(aux){
    codigo=txtProvincias.value;
    ajax3=objetoAjax();
    ajax3.open("POST", "sql/busquedas.php",true);
    ajax3.onreadystatechange=function() {
        if (ajax3.readyState==4) {
            var respuesta=ajax3.responseText;
            asignaciudadClientes(respuesta);

        }
    }
    ajax3.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax3.send("accion="+aux+"&codigo="+codigo)
}
function asignaciudadClientes(cadena){
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;
    for (m=txtCiudad.options.length-1;m>=0;m--){
        txtCiudad.options[m]=null;
    }
    txtCiudad.options[0] = new Option("Seleccione Ciudad","0");
    for(i=1;i<limite;i=i+2){
        txtCiudad.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
    listar_reporte_clientes(1);
}

comboCiudadClientes(3);

      (function($) {
    

	"use strict";

	var fullHeight = function() {
		   $('.js-fullheight').css('height', $(window).height());
		   $(window).resize(function(){
		   $('.js-fullheight').css('height', $(window).height());
		});

	};
	fullHeight();

    $('#sidebarCollapse').on('mouseover', function () {
	    
      $('#sidebar').toggleClass('active');
    });
 


})(jQuery);
  </script>


<script type="">




function imprimirCliente(id_cliente){

miUrl = "reportes/rptCliente.php?id_cliente="+id_cliente;
//miUrl = "reportes/rptComprobanteDiario.php";
 window.open(miUrl,'noimporta','width=600, height=500, scrollbars=NO, titlebar=no');
}

</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script> 
<script>
         // Give $ to prototype.js
         var $jq = jQuery.noConflict();
     </script>
     <script src="https://malsup.github.io/jquery.form.js"></script> 
     <script type="text/javascript">

        $jq(document).ready(function() {
          var options = {
            target: '#message', 
            url:'sql/clientes.php?accion=20',
            data: { accion: '20' }, 
            beforeSubmit: function() {
            },
            success:  function(data) {
                console.log(data);
            if(data.trim()=='Excel importado correctamente'){
                alertify.success('Excel importado correctamente.');
            }else{
                 alertify.error('Ocurrio un error al importar el excel.');
            }
            }
        };



        $jq('#frmExcelImport').submit(function() {
            $jq(this).ajaxSubmit(options);
             alertify.success('Excel importado correctamente.');
            return false;
        });



    }); 
    
    function modificar_cliente_version2(id_cliente){
    window.location.href = "moduloClientes.php?id_cliente="+id_cliente;
}
   function modificar_cliente_jderp(id_cliente){
    window.location.href = "moduloClientes_jderp.php?id_cliente="+id_cliente;
}

  function agregarAfiliados() {
            // $('#loader').show(); 	
            let fd = new FormData();
            fd.append("file", document.getElementById("file").files[0]);
            fd.append("accion", '38');

            fetch('sql/clientes.php', {
                method: 'POST',
                body: fd
            })
           .then(function(response) {
            // $('#loader').hide(); 	
            if (response.status >= 200 && response.status < 300) {
               
                return response.text()
            }
            throw new Error(response.statusText)
        })
  .then(function(response) {
   try{
       let respuesta = JSON.parse(response);
       
       
       
       let cantidad_erroneos =  respuesta.cedula_no_guardados.length;
       if(cantidad_erroneos>1){
           for(let a=0;a<cantidad_erroneos; a++){
           alertify.error( "El cliente :"+respuesta.cedula_no_guardados[a]+"con cedula:"+respuesta.cedula_no_guardados[a]+" no se guardo.");
       }
       }else{
           alertify.success('Registros guardados correctamente');
       }
       
       
       
   }catch (error) {
        console.error('Error:', error.message);
    }
    // if(response.trim()=='1'){
    //     alertify.success('Registros guardados correctamente');
    //     console.log(response);
    // }else if(response.trim()=='2'){
    //     alertify.error( "El archivo enviado es invalido. Por favor vuelva a intentarlo");
    //     console.log(response);
      
    // }else{
    //     alertify.error('Error al  guardar los clientes.');
    //     alertify.error(response);
    // }
           listar_reporte_clientes(1);
        })
        }
        function agregarRegistrosAfiliado() {
            // $('#loader').show(); 	
            let fd = new FormData();
            fd.append("file", document.getElementById("file2").files[0]);
            fd.append("accion", '39');

            fetch('sql/clientes.php', {
                method: 'POST',
                body: fd
            })
           .then(function(response) {
            // $('#loader').hide(); 	
            if (response.status >= 200 && response.status < 300) {
               
                return response.text()
            }
            throw new Error(response.statusText)
        })
  .then(function(response) {
   try{
       let respuesta = JSON.parse(response);
       
       
       
       let cantidad_erroneos =  respuesta.cedula_no_guardados.length;
       if(cantidad_erroneos>1){
           for(let a=0;a<cantidad_erroneos; a++){
           alertify.error( "El cliente :"+respuesta.cedula_no_guardados[a]+"con cedula:"+respuesta.cedula_no_guardados[a]+" no se guardo.");
       }
       }else{
           alertify.success('Registros guardados correctamente');
       }
       
       
       
   }catch (error) {
        console.error('Error:', error.message);
    }
    // if(response.trim()=='1'){
    //     alertify.success('Registros guardados correctamente');
    //     console.log(response);
    // }else if(response.trim()=='2'){
    //     alertify.error( "El archivo enviado es invalido. Por favor vuelva a intentarlo");
    //     console.log(response);
      
    // }else{
    //     alertify.error('Error al  guardar los clientes.');
    //     alertify.error(response);
    // }
           listar_reporte_clientes(1);
        })
        }
 function validarTipoDocumentoSelecionado() {
            var opciones = document.getElementsByName("rbCaracterIdentificacion");
            var seleccionada = false;

            for (var i = 0; i < opciones.length; i++) {
                if (opciones[i].checked) {
                    seleccionada = true;
                    break;
                }
            }

            if (!seleccionada) {
                alertify.error('Es necesario seleccionar al menos una opción antes de enviar el formulario');
                return false; // Evita que el formulario se envíe
            }

            // Puedes agregar más validaciones si es necesario
            return true; // Envía el formulario si la validación es exitosa
        }
</script>
    </body>

</html>
