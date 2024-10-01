<?php

    //Start session
    session_start();

    require_once('ver_sesion.php');
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION['sesion_id_periodo_contable'];
    $sesion_empresa_nombre = $_SESSION['sesion_empresa_nombre'];
    $sesion_tipo = $_SESSION["sesion_tipo"];
    $sesion_nombre = $_SESSION["sesion_nombre"];
    $sesion_apellido = $_SESSION["sesion_apellido"];
    $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
    $sesion_punto = $_SESSION['userpunto'];

?>
<html lang="es-ES">
    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Usuarios</title>
  
    <script src="https://kit.fontawesome.com/7816c50242.js" crossorigin="anonymous"></script>
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


    <!-- estilo para validar los campos vacios -->
    <script type="text/javascript" src="js/jquery-1.4.1.min.js"></script>
    <script src="js/jquery.validate.js" type="text/javascript"></script>
    <script type="text/javascript">
    $(document).ready(function(){
            $("#ajax_contactform").validate();
    });
    </script>

    <!-- START ESTILOS Y CLASES PARA AJAX -->
    <script language="javascript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.blockUI.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.validate.1.5.2.js"></script>
    <script language="javascript" type="text/javascript" src="js/index.js"></script>
    <!-- END ESTILOS Y CLASES PARA AJAX -->

    <!-- estilos para el listado de las tablas  -->
    <link href="css/listadoTablas.css" rel="stylesheet" type="text/css" />

     <!-- funcuiones para validar los campos  -->
    <script type="text/javascript" src="js/validaciones.js"></script>
    <script type="text/javascript" src="js/establecimientos.js"></script>

     <!-- funciones -->
    <script type="text/javascript" src="js/funciones.js"></script>

</head>
<body onload="listar_usuarios_empresa(1); ">


<div class="wrapper d-flex align-items-stretch celeste">
    <nav id="sidebar" ><?php  {include("menusNiveles.php");      }   ?></nav>
<div id="content"  >
    <header ><?php  {include("header.php");      }   ?>  </header>

            <form action="javascript: listar_usuarios_empresa(1);" id="formUsuarios" name="formUsuarios"  >
                
                <div id="mensaje1"></div>
                <div class="row mx-5 bg-white r-10 p-1 pt-1  mt-5  p-2">
                    
                    <div class="col-lg-2">
                        <label>Usuario</label>
                        <input class="form-control" name="criterio_usu_per" type="search" id="criterio_usu_per" placeholder="Buscar por Login"
                        onKeyUp="javascript: listar_usuarios_empresa(1);"  />
                    </div>
                    
                    <div class="col-lg-2">
                                <label>Ordenar</label>
                                <select class="form-control" name="criterio_ordenar_por" id="criterio_ordenar_por">
                                   
                                    <option value=" usuarios.`login` ">Login</option>
                                     <option value=" empleados.`cedula` ">C&eacute;dula</option>
                                    <option value=" empleados.`nombre` " selected="selected">Nombre</option>
                                    <option value=" empleados.`apellido` ">Apellido</option>
                                    <option value=" usuarios.`tipo` ">Tipo</option>
                                </select>
                    </div>
                    <div class="col-lg-2">
                            <label>Registros</label>
                        	<select class="form-control" name="criterio_mostrar" id="criterio_mostrar">
                                	<option value="1">1</option>
                                	<option value="2">2</option>
                                	<option value="5">5</option>
                                	<option value="10">10</option>
                                	<option value="20" selected="selected">20</option>
                                	<option value="40">40</option>
                                        <option value="1000000">Todos</option>
                                </select>
                    </div>
                    <div class="col-lg-2">
                        <label>Orden:</label>
                        <select class="form-control" name="criterio_orden" id="criterio_orden">
                                    <option value="asc">Ascendente</option>
                                    <option value="desc">Descendente</option>
                                </select>
                    </div>
                    <div class="col-lg-2">
                                <label>Estado:</label>
                                <select class="form-control" name="estado_mostrar" id="estado_mostrar" onChange="javascript: listar_usuarios_empresa(1);">
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                    <option value="0">Todos</option>
                                </select>
                    </div>
                    <div class="col-lg-2">
                        <a href="javascript: nuevoUsuario();" class="btn btn-outline-success btn-xs" >Nuevo Usuario</a>
                    </div>
            </form>

                </div>
                
                
             <div id="div_listar_usuarios_empresa" class="row mx-5 bg-white r-10 p-1 pt-1  p-2"></div>   
                
            <div id="div_oculto" style="display: none;"></div>
                <br/>

        </div>	<!-- end #contentLeft .grid_10 -->
            <!-- END LEFT CONTENT -->

           <?php include("panelUsuarios.php"); ?>

         </div>	<!-- end #content -->


<script type="">

function abrirPdfUsuarios(){

miUrl = "reportes/rptUsuarios.php?criterio_usu_per="+document.getElementById("criterio_usu_per").value+"&criterio_ordenar_por="+document.getElementById("criterio_ordenar_por").value+"&criterio_orden="+document.getElementById("criterio_orden").value+"&criterio_mostrar="+document.getElementById("criterio_mostrar").value+"&estado_mostrar="+document.getElementById("estado_mostrar").value;

 window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
}

function pdfInfoUsuario(id_empleado){

miUrl = "reportes/rptInfoEmpleado.php?id_empleado="+id_empleado;

 window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
}
</script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>