<?php
error_reporting(0);
session_start();
define('NUM_ITEMS_BY_PAGE', 10);
$id_empresa_cookies = $_COOKIE["id_empresa_cookie"];
$id_periodo_contable_cookies = $_COOKIE["id_periodo_contable_cookie"];
$cookie_tipo = $_COOKIE['tipo_cookie'];

$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$sesion_id_periodo_contable = $_SESSION['sesion_id_periodo_contable'];
$sesion_empresa_nombre = $_SESSION['sesion_empresa_nombre'];
$sesion_tipo = $_SESSION["sesion_tipo"];
$sesion_nombre = $_SESSION["sesion_nombre"];
$sesion_apellido = $_SESSION["sesion_apellido"];
$sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];

?>

<html lang="es-ES">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Vendedores</title>
	
	<script src="https://kit.fontawesome.com/7816c50242.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">  
	<script src="librerias/jquery-3.2.1.min.js"></script>
	<link rel="stylesheet" href="css/style.css">
	<script src="librerias/bootstrap/js/bootstrap.js"></script>
	<link rel="stylesheet" href="librerias/bootstrap/css/style.css"> 
	<link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/alertify.css">
	<script src="librerias/alertifyjs/alertify.js"></script>
    <!-- <link rel="stylesheet" href="librerias/dist/css/bootstrap-select.css">
    	<link rel="stylesheet" type="text/css" href="librerias/select2/css/select2.css"> -->

    	<!-- START ESTILOS Y CLASES PARA AJAX -->
    	<script language="javascript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
    	<script language="javascript" type="text/javascript" src="js/jquery.blockUI.js"></script>
    	<script language="javascript" type="text/javascript" src="js/jquery.validate.1.5.2.js"></script>
    	<script language="javascript" type="text/javascript" src="js/index.js"></script>
    	<!-- END ESTILOS Y CLASES PARA AJAX -->

    	<!-- estilos para tablas -->
    	<link href="css/listadoTablas.css" rel="stylesheet" type="text/css" />

    	<!-- FUNCIONES -->
    	<script type="text/javascript" src="js/funciones.js"></script>

    	<!--validaciones de los campos -->
    	<script type="text/javascript" src="js/validaciones.js"></script>
    	<script type="text/javascript" src="js/condominios.js"></script>
    	<script type="text/javascript" src="js/funciones_vendedores.js"></script>
    	<!--END estilo para  el menu -->
    	<link rel="shortcut icon" href="images/logo.png">
    </head>


    <body onload="listarVendedores(1);">
    	<div class="wrapper d-flex align-items-stretch celeste">
    		<nav id="sidebar" ><?php  {include("menusNiveles.php");      }   ?></nav>
    		<div id="content"  >
    			<header ><?php  {include("header.php");      }   ?>  </header>
    			

    			<form name="form1" id="form1" method="post" action="javascript: listarVendedores(1);">
    				
    				
    				<div class="row bg-white rounded p-2 pt-1  mx-5 mt-5  ">
    					<div class="col-lg-3">
    						<td>Nombre:</td>
    						<td><input class="form-control" name="criterio_usu_per" type="search" id="criterio_usu_per" placeholder="Nombre vendedor" 
    							title="Ingrese un nombre para buscar" onKeyUp="javascript: listarVendedores(1);"/></td>
    						</div>
    						<div class="col-lg-1">    
    							<td>Ordenar: </td>
    							<td>
    								<select class="form-select" name="criterio_ordenar_por" id="criterio_ordenar_por" onChange="javascript: listarVendedores(1);">
    									
    									<option value="Nombres">Nombre</option>
    									<option value="Direccion">Direccion</option>
    									<option value="Telefono">Telefono</option>
    									<option value="Cedula">Cedula</option>
    									<option value="Placa">Placa</option>
    								</select>
    							</td>

    						</div>
    						
    						<div class="col-lg-1">
    							<td>Registros: </td>
    							<td>
    								<select class="form-select" name="criterio_mostrar" id="criterio_mostrar" onChange="javascript: listarVendedores(1);">
    									<option value="1">1</option>
    									<option value="2">2</option>
    									<option value="5">5</option>
    									<option value="10">10</option>
    									<option value="20" selected="selected">20</option>
    									<option value="40">40</option>
    									<option value="100">100</option>
    									<option value="400000000">Todos</option>
    								</select>
    							</td>
    						</div>
    						<div class="col-lg-2">
    							<td>En: </td>

    							<select class="form-select" name="criterio_orden" id="criterio_orden" onChange="javascript: listarVendedores(1);">
    								<option value="asc">Ascendente</option>
    								<option value="desc">Descendente</option>
    							</select>

    							
    						</div>
    						<div class="col-lg-1">
    							<a href="javascript: nuevo_vendedor();" title="Agregar nuevo Vendedor"><span type="button" class="btn btn-success">Nuevo</a>

    							</div>    
    								<div class="col-lg-1">
    							<a href="javascript: pdfVendedores();" title="Pdf Vendedores"><span type="button" class="btn btn-secondary">PDF</a>

    							</div>    
    							
    							
    						</div>
    					</form>


    					
    					
    					<div id="listarVendedores" class="mx-5  mt-3"></div>
    					<div id="div_oculto" style="display: none;"></div>

    				</div>	

    			</div>

    			<script src="librerias/bootstrap/js/main.js"></script>      

    			<script>
    				$( document ).ready(function() {
    					listarVendedores(1);
    				});
    				function pdfVendedores(){
    let formulario  = $("#form1").serialize();
    
      miUrl = "reportes/rptVendedores.php?"+formulario;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
}
    			</script>
    			  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    		</body>	
    		
    		</html>