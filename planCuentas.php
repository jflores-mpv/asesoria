<?php
    //Start session
    session_start();
    require_once('ver_sesion.php');
    $id_empresa_cookies = $_COOKIE["id_empresa_cookie"];
    $cookie_tipo = $_COOKIE['tipo_cookie'];

    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_tipo = $_SESSION["sesion_tipo"];
    $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
     $sesion_id_institucion_educativa = $_SESSION["sesion_id_institucion_educativa"];
    //PERMISOS AL MODULO PLAN CUENTAS
    $sesion_plan_cuentas_guardar = $_SESSION["sesion_plan_cuentas_guardar"];
    $sesion_plan_cuentas_modificar = $_SESSION["sesion_plan_cuentas_modificar"];
    $sesion_plan_cuentas_eliminar = $_SESSION["sesion_plan_cuentas_eliminar"];

?>
<html lang="es-ES">
    
<head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Plan de cuentas</title>
     <script src="https://kit.fontawesome.com/7816c50242.js" crossorigin="anonymous"></script>
    <script src="librerias/jquery-3.2.1.min.js"></script>
    <script src="librerias/alertifyjs/alertify.js"></script>
    <script src="librerias/bootstrap/js/bootstrap.js"></script>
     <script src="https://kit.fontawesome.com/7816c50242.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="librerias/bootstrap/css/style.css"> 
    <link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/alertify.css">
   	<link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="librerias/dist/css/bootstrap-select.css">
    <link rel="stylesheet" type="text/css" href="librerias/select2/css/select2.css">
    <link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">  
 
    <script src="js/jquery.validate.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function(){
               cargaPlanCuentasEmpresas(13);
        });
    </script>
    <!-- START ESTILOS Y CLASES PARA AJAX -->
    <script language="javascript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.blockUI.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.validate.1.5.2.js"></script>
    <script language="javascript" type="text/javascript" src="js/index.js"></script>
    <!-- END ESTILOS Y CLASES PARA AJAX -->
    <!-- funcuiones para validar los campos  -->
    <script type="text/javascript" src="js/validaciones.js"></script>
    <!-- para las busquedas desplegables -->
    <script language="javascript" type="text/javascript" src="js/busquedas.js"></script>
     <!-- funciones -->
    <script type="text/javascript" src="js/funciones.js"></script>
    <!--END estilo para  el menu -->
    <link rel="shortcut icon" href="images/logo.png">
     
</head>

<body onload="fn_buscar(1);">
    
                <!-- PERMISOS PARA EL MODULO PLAN CUENTAS -->
                <input type="hidden" id="txtPermisosPlanCuentasGuardar" name="txtPermisosPlanCuentasGuardar" value="<?php echo $sesion_plan_cuentas_guardar; ?>" />
                <input type="hidden" id="txtPermisosPlanCuentasModificar" name="txtPermisosPlanCuentasModificar" value="<?php echo $sesion_plan_cuentas_modificar; ?>" />
                <input type="hidden" id="txtPermisosPlanCuentasEliminar" name="txtPermisosPlanCuentasEliminar" value="<?php echo $sesion_plan_cuentas_eliminar; ?>" />

<div class="wrapper d-flex align-items-stretch celeste">
    <nav id="sidebar" ><?php  {include("menusNiveles.php");      }   ?></nav>
<div id="content"  >
    <header ><?php  {include("header.php");      }   ?>  </header>
    
      <div class="row  pl-5 pt-5 pb-2 rounded">
        <div class="col-lg-3  ">
         <h3>Plan de cuentas</h3>
       </div>

          <a href="javascript: nuevoPlanCuentas('<?php echo $sesion_plan_cuentas_guardar; ?>');" class="col-lg-2 col-2 text-decoration-none card p-2 mx-1" style="text-decoration:none">
                <div class="my-icon3"><i class="fa fa-plus mr-3 fa-1x"></i><span>Nueva Cuenta</span></span>  </div></a>
        
        <a href="javascript: abrirPdfPlanCuenta()" class="col-lg-1 text-decoration-none card p-2 mx-1 col-2" style="text-decoration:none">
                 <div class="my-icon3 rounded bg-white"><i class="fa fa-print mr-3 fa-1x"></i><span>Imprimir </span></span>  </div></a>

                
    
            <a href="javascript: importar_plan_cuentas(26, txtPermisosPlanCuentasGuardar.value);" class="col-lg-2 col-2 text-decoration-none card p-2 mx-1" style="text-decoration:none">
                <div class="my-icon3 rounded bg-white"><i class="fa fa-folder-open-o mr-3 fa-1x"></i><span>Importar NIIF</span></span>  </div></a>

        
              

            <a href="javascript: reiniciar_cuenta(20)" class="col-lg-1 col-2 text-decoration-none card p-2 mx-1" style="text-decoration:none">
                <div class="my-icon3 rounded bg-white"><i class="fa fa-window-close mr-3 fa-1x"></i><span>Reiniciar</span></span>  </div></a>
    </div>
    
     <form action="javascript: fn_buscar(1);" id="frm_buscar" name="frm_buscar">
     <div class="row mx-5  rounded bg-white">
         <div class="col-lg-3">
             <div class="row">
                   <div class="col-lg-12">
                    <Label>Buscar: </Label>
                    <input class="form-control" name="criterio_usu_per" type="search" id="criterio_usu_per" 
                    placeholder="Codigo o Nombre de la cuenta" onKeyUp="javascript: fn_buscar(1);"/>
                    </div>
                    
                    <div class="col-lg-12">
                     <Label>Ordenar: </Label>
                                        <select class="form-control required" name="criterio_ordenar_por" id="criterio_ordenar_por" onChange="javascript: fn_buscar(1);">
                                            <option value="plan_cuentas.codigo ">C&oacute;digo</option>
                                            <option value="plan_cuentas.nombre">Nombre</option>
                                            <option value="plan_cuentas.clasificacion">Clasificaci&oacute;n</option>
                                            <option value="plan_cuentas.tipo">Tipo</option>
                                        </select>   
                    </div>
                    <div class="col-lg-12">
                        <Label>Cant: </Label>
                                        <select class="form-control" name="criterio_mostrar" id="criterio_mostrar" onChange="javascript: fn_buscar(1);">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="40">40</option>
                                                <option value="10000000000000000" selected="selected">Todos</option>
                                        </select>
                    </div> 
                    <div class="col-lg-12">
                      <Label>En: </Label>
                                        <select class="form-control required" name="criterio_orden" id="criterio_orden" onChange="javascript: fn_buscar(1);">
                                            <option value="asc">Ascendente</option>
                                            <option value="desc">Descendente</option>
                                        </select>
                    </div>
             </div>
         </div>
         <div class="col-lg-9">
             <div id="div_listar_cuenta"></div>
         </div>
         
         
                  

            
            
            
            
        </div>
    </form>
    
    <div id="mensaje1"></div>
    
    
            
        </div>

    </div>
</div>


<div id="div_oculto" style="display: none;"></div>
<script src="librerias/bootstrap/js/main.js"></script>

<script type="">

function abrirPdfPlanCuenta(){

miUrl = "reportes/rptPlanCuenta.php?txtCuenta="+document.getElementById("criterio_usu_per").value+"&cmbOrdenar="+document.getElementById("criterio_ordenar_por").value+"&cmbOrden="+document.getElementById("criterio_orden").value+"&cmbRegistros="+document.getElementById("criterio_mostrar").value;

 window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
}
</script>
</body>  

</html>