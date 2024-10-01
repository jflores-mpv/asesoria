<?php

	//Start session
	session_start();
	require('ver_sesion.php');
	error_reporting(E_ALL);
ini_set('display_errors', 1);
	
    	require_once('conexion2.php');
        // $cookie_id_empleado = $_COOKIE['id_empleado_cookie'];
        $sesion_id_empleado = $_SESSION["sesion_id_empleado"];
        
            $sesion_id_empresa = $_SESSION['sesion_id_empresa'];
    $sesion_nombre = $_SESSION["sesion_nombre"];
    $sesion_apellido = $_SESSION["sesion_apellido"];
    $sesion_tipo = $_SESSION["sesion_tipo"];
    
    $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
    
        $dominio = $_SERVER['SERVER_NAME'];
    
        // echo $dominio;
                // $dominio = substr($dominio, 4); 
        // echo $dominio;
        
?>

<html lang="es-ES">
    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Administrador</title>

  
	<link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">  
	<link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/alertify.css">
	<link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/themes/default.css">
    <link rel="stylesheet" type="text/css" href="librerias/select2/css/select2.css">
    <link rel="stylesheet" href="librerias/bootstrap/css/style.css"> 
	<script src="librerias/jquery-3.2.1.min.js"></script>
	<script src="librerias/bootstrap/js/bootstrap.js"></script>
	<script src="librerias/alertifyjs/alertify.js"></script>
    <script src="librerias/select2/js/select2.js"></script>
    <script src="librerias/bootstrap/js/main.js"></script>
     <link rel="stylesheet" href="css/style.css">

    <script src="https://kit.fontawesome.com/7816c50242.js" crossorigin="anonymous"></script>

    <script language="javascript" type="text/javascript" src="js/index.js"></script>

    <!-- estilo para validar los campos vacios -->
    <script type="text/javascript" src="js/jquery-1.4.1.min.js"></script>
    <script src="js/jquery.validate.js" type="text/javascript"></script>
    <script type="text/javascript">
        
        $(document).ready(function(){
                $("#ajax_contactform").validate();
        });
    </script>

            <!-- START ESTILOS Y CLASES PARA AJAX -->
    <!--<script language="javascript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>-->
    <!--<script language="javascript" type="text/javascript" src="js/jquery.blockUI.js"></script>-->
    <!--<script language="javascript" type="text/javascript" src="js/jquery.validate.1.5.2.js"></script>-->

    <!-- END ESTILOS Y CLASES PARA AJAX -->

    <!--validaciones de los campos -->
    <script type="text/javascript" src="js/validaciones.js"></script>
    <!-- funciones -->
    <script type="text/javascript" src="js/funciones.js"></script>
 
    <!--busquedas desplegables -->
    <script type="text/javascript" src="js/busquedas.js"></script>
	<script type="text/javascript" src="js/institucion.js"></script>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <!--END estilo para  el menu -->
  

<script src="librerias/bootstrap/js/main.js"></script>
  <link type="text/css" rel="stylesheet" href="css/calendario.css" media="screen"/>
    <script language="javascript" type="text/javascript" src="js/calendario.js"></script>
    
</head>

<body onLoad="listarEmpresasDos()">
    
    <div class="wrapper d-flex align-items-stretch celeste">
    
    <nav id="sidebar" ></nav>
    
    <div id="content"  class="p-0 m-0">
    
    <div class="row m-0"><?php  {include("header.php");      }   ?>  </div>
    
 <div id="content"  class="p-0 m-0">       
    <div class="row m-0">
                
                <div class="col-lg-3">
                      <!--<div class="col-lg-3">-->
                    <form name="frmEmpresasContaweb" id="frmEmpresasContaweb" method="post" action="javascript:  listadoEmpresas();">
                            <div class="row">
                                
                                <div class="col-lg-12 mb-2">
                                    <label for="id_label_single">Buscar por ruc:</label>
                                    <input type="text" tabindex="1" id="txtRUC" value="" class="form-control" required="required" name="txtRUC" onKeyup="listarEmpresasDos(1)" />

                                </div>
                                 <div class="col-lg-12 mb-2">
                                    <label for="id_label_single">Buscar por NOMBRE:</label>
                                    <input type="text" tabindex="1" id="txtNombreComercial" value="" class="form-control" required="required" name="txtNombreComercial" onKeyup="listarEmpresasDos(1)" />

                                </div>
                                
                                <div class="col-lg-12 mb-2">
                                    <label for="id_label_single">Buscar por razon social:</label>
                                    <input type="text" tabindex="1" id="txtNombre" value="" class="form-control" required="required" name="txtNombre" onKeyup="listarEmpresasDos(1)" />

                                </div>
                                
                                <div class="col-lg-12 mb-2">
                                    <label for="id_label_single">Buscar por codigo:</label>
                                    <input type="text" tabindex="1" id="txtCodigoIngreso" value="" class="form-control" required="required" name="txtCodigoIngreso" onKeyup="listarEmpresasDos(1)" />

                                </div>


                                <div class="col-lg-6">
                                    <label class="text-left">Desde: </label>
                                    <input type="date" tabindex="1" id="txtFechaDesde" value="<?php echo date('Y') . "-" . "01" . "-" . "01"; ?>" class="form-control" required="required" name="txtFechaDesde" onChange="listarEmpresasDos(1);listarEmpresasTotales()" />
                                    <!--<div id="mensajefecha_desde"></div>-->
                                </div>

                                <div class="col-lg-6">
                                    <label class="text-left">Hasta: </label>
                                    <input type="date" tabindex="2" id="txtFechaHasta" value="<?php echo date("Y-m-d", time()); ?>" class="form-control" required="required" name="txtFechaHasta" onChange="listarEmpresasDos(1);listarEmpresasTotales()" />
                                    <!--<div id="mensajefecha_hasta"></div>-->
                                </div>
                                <div class="col-lg-6">
                                      <label class="text-left">Mostrar: </label>
                                    <select name="criterio_mostrar" id="criterio_mostrar" class="form-select" onChange="listarEmpresasDos(1);listarEmpresasTotales()" >
                                        <option value="10">10</option>
                                        <option value="50">50</option>
                                        <option value="10000000000000">Todos</option>
                                    </select>
                                </div>
                                
                                


                                <!--<div class="col-lg-12 mt-3">-->
                                <!--    <input type="button" tabindex="5" name="submit" value="Descargar Reporte Detallado" id="" class="btn btn-outline-warning" align="right" -->
                                <!--    onclick="javascript: pdfReporteFacturaR(txtDominio.value,txtFechaDesde.value,txtFechaHasta.value,txtRUC.value,txtCodigoIngreso.value,txtdetalle.value);" />-->
                                <!--</div>-->

                                
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-8">

                            <div class="row">
                                <div class="col-lg-12">
      
                                        <div id="listadoEmpresas"></div>
                                    
                                </div>
                            </div>
                        </div>
                        
                        
                </div>
                
                
                
                
              
                        
                
                        
                        
            </div>
               
 
         
         

</div>

<script>
     function actualizar_renovacion(id){
       let fecha_renovacion = document.getElementById('txtFechaRenovacion'+id);
       if(fecha_renovacion){
        fecha_renovacion = document.getElementById('txtFechaRenovacion'+id).value;
       }
       $.ajax(
     {
         url: 'sql/empresa.php',
         data: "accion=20&id="+id+"&fecha_renovacion="+fecha_renovacion,
         type: 'post',
         success: function(data)	{
        // console.log(data);
             if(data==1){
             alertify.success("Fecha de renovacion actualizada correctamente :)");
         }else{
             alertify.error("No se actualizo la fecha de renovacion.");
         }
             listarEmpresasDos();
         }
     });
  
  }
  
      
  
    function pdfReporteFacturaR(dominio,fechaInicio,fechaFin,txtRUC,txtCodigoIngreso,detalle){
        console.log("dominio",dominio);
        console.log("fechaInicio",fechaInicio);
        console.log("fechaFin",fechaFin);
        console.log("detalle",detalle);
         miUrl = "reportes/empresasContaweb.php?dominio="+dominio+"&fechaInicio="+fechaInicio+"&fechaFin="+fechaFin+"&txtRUC="+txtRUC+"&txtCodigoIngreso="+txtCodigoIngreso+"&txtdetalle="+detalle;
         window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
}


    function pdfReporteFacturas2(dominio,fechaInicio,fechaFin,txtRUC,txtCodigoIngreso,detalle){
        console.log("dominio",dominio);
        console.log("fechaInicio",fechaInicio);
        console.log("fechaFin",fechaFin);
        console.log("detalle",detalle);
         miUrl = "reportes/empresasContawebReporte.php?dominio="+dominio+"&fechaInicio="+fechaInicio+"&fechaFin="+fechaFin+"&txtRUC="+txtRUC+"&txtCodigoIngreso="+txtCodigoIngreso+"&txtdetalle="+detalle;
         window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
}

   function pdfReporteFacturas3(dominio,fechaInicio,fechaFin,txtRUC,txtCodigoIngreso,detalle){
        console.log("dominio",dominio);
        console.log("fechaInicio",fechaInicio);
        console.log("fechaFin",fechaFin);
        console.log("detalle",detalle);
         miUrl = "reportes/empresasContawebExcel.php?dominio="+dominio+"&fechaInicio="+fechaInicio+"&fechaFin="+fechaFin+"&txtRUC="+txtRUC+"&txtCodigoIngreso="+txtCodigoIngreso+"&txtdetalle="+detalle;
         window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
}

  function modificatipo(id_empresa){
  
    let tipo = $('#tipo'+id_empresa).val();
  
    
    $.ajax({
            url: 'sql/empresa.php',
            data: "accion=25&id_empresa="+id_empresa+"&tipo="+tipo,
            type: 'post',
            success: function(data){
             let response = data.trim();
            if(response==='1'){
                alertify.success('Se actualizo correctamente el tipo de empresa');
            }else{
                alertify.error('Error al actualizar el tipo de empresa');
            }
           
            }
    });
    
}
  
</script>

</body>
</html>

