<?php

	session_start(); 

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
    <title>Listado Asientos Contables</title>
    
    <script src="https://kit.fontawesome.com/7816c50242.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">  
    <script src="librerias/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <script src="librerias/bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" href="librerias/bootstrap/css/style.css"> 
    <link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/alertify.css">
    <script src="librerias/alertifyjs/alertify.js"></script>
    <link rel="stylesheet" href="librerias/dist/css/bootstrap-select.css">
    <link rel="stylesheet" type="text/css" href="librerias/select2/css/select2.css">
    <script src="librerias/select2/js/select2.js"></script>
    <!-- START ESTILOS Y CLASES PARA AJAX -->
    <!--<script language="javascript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>-->
    <!--<script language="javascript" type="text/javascript" src="js/jquery.blockUI.js"></script>-->
    <script language="javascript" type="text/javascript" src="js/jquery.validate.1.5.2.js"></script>
    <script language="javascript" type="text/javascript" src="js/index.js"></script>
	<script language="javascript" type="text/javascript" src="js/diarios.js"></script>
	
    <!-- END ESTILOS Y CLASES PARA AJAX -->

    <!-- estilos para tablas -->
    <link href="css/listadoTablas.css" rel="stylesheet" type="text/css" />

    <!-- FUNCIONES -->
    <script type="text/javascript" src="js/funciones.js"></script>

     <!--validaciones de los campos -->
    <script type="text/javascript" src="js/validaciones.js"></script>
     <script type="text/javascript" src="js/condominios.js"></script>

    <!--END estilo para  el menu -->
    <link rel="shortcut icon" href="images/logo.png">
</head>


<body onload="listar_diarios()">
    
<div class="wrapper d-flex align-items-stretch celeste">

    <nav id="sidebar" ><?php  {include("menusNiveles.php");      }   ?></nav>
    
    <div id="content"  class="p-0 m-0">
    
    <div class="row m-0 w-100"><?php  {include("header.php");      }   ?>  </div>
 
    <div class="row  m-0 ">  
    <div class="col-lg-3 bg-white p-3 mt-3 rounded">
        
            
        
        <form name="form1" id="form1" method="post" action="javascript: listados_contables();">
            
            <input  name="txtIdPlanCuenta" type="hidden" id="txtIdPlanCuenta" />
                   
            <div class="row">
                      <div class="col-lg-12">
                    <div class="mb-3">
                        <label for="cuenta_contable" class="form-label">Cuenta Contable:</label>
                        <input class="form-control" name="cuenta_contable" type="search" id="cuenta_contable" placeholder="Cuenta" title="Ingrese palabra para buscar" onChange="javascript: listados_contables();" onKeyUp="javascript: listados_contables();"/>
                    </div>
                </div>    
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label for="glosa" class="form-label">Descripci&oacute;n:</label>
                        <input class="form-control" name="glosa" type="search" id="glosa" placeholder="Descripcion" title="Ingrese palabra para buscar" onChange="javascript: listados_contables();" onKeyUp="javascript: listados_contables();"/>
                    </div>
                </div>    
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label for="criterio_ordenar_por" class="form-label">Ordenar:</label>
                        <select class="form-select" name="criterio_ordenar_por" id="criterio_ordenar_por" onChange="javascript: listados_contables();">
                            <option value="libro_diario.`descripcion`">Nombre</option>
                            <option value="libro_diario.`numero_asiento`">Asiento</option>
    						<option value="libro_diario.`id_libro_diario`">Id libro diario</option>
                        </select>
                    </div>
                </div>    
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label for="criterio_tipo" class="form-label">Tipo:</label>
                        <select class="form-select" name="criterio_tipo" id="criterio_tipo" onChange="javascript: listados_contables();">
                            <option value="Diario">Diario</option>
                            <option value="Ingreso">Ingreso</option>
                             <option value="Egreso">Egreso</option>
                        </select>
                    </div>
                </div>    
                <div class="col-lg-12">
                    
                    <div class="mb-3">
                        <label for="txtFechaDesde" class="form-label">Desde: </label>
                        <input type="text" tabindex="1" id="txtFechaDesde" value="<?php echo date('Y')."-"."01"."-"."01" ; ?>"
                        class="form-control" required="required" name="txtFechaDesde" onChange="listarFacturasCompras(1)" onClick="displayCalendar(txtFechaDesde,'yyyy-mm-dd',this)" />
                        <div id="mensajefecha_desde" ></div>
                    </div>
                    
                </div>
                
                <div class="col-lg-12">
                    
                    <div class="mb-3">
                        <label for="txtFechaHasta" class="form-label">Hasta: </label>
                        <input type="text" tabindex="2" id="txtFechaHasta" value="<?php echo date("Y-m-d",time()); ?>" class="form-control"
                        required="required" name="txtFechaHasta"onChange="listarFacturasCompras(1)" onClick="displayCalendar(txtFechaHasta,'yyyy-mm-dd',this)"/>
                        <div id="mensajefecha_hasta" ></div>
                    </div>
                    
                </div>
                
                <div class="col-lg-12"> 
                    <div class="mb-3">
                        <label for="criterio_mostrar" class="form-label">Cantidad:</label>
                        <select class="form-select" name="criterio_mostrar" id="criterio_mostrar" onChange="javascript: listados_contables();">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20" selected="selected">20</option>
                                <option value="40">40</option>
                                <option value="100">100</option>
                                <option value="400000000">Todos</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-12">    
                    <div class="mb-3">
                        <label for="criterio_orden" class="form-label">Orden:</label>
                        <select class="form-select" name="criterio_orden" id="criterio_orden" onChange="javascript: listados_contables();">
                            <option value="desc">Descendente</option>
                            <option value="asc">Ascendente</option>
                            
                        </select>
                    </div>
                </div>
            </div>
        </form>  
        
        </div>
        
        <div class="col-lg-9 p-3">
   
      
            <div id="div_listar_libro_diario" ></div>
           
             
        
        
        </div>
        
	    
        </div>

    
         
       
        <div id="div_oculto" style="display: none;"></div>

    </div>	

</div>

<script src="librerias/bootstrap/js/main.js"></script>      

<script type="">

function abrirPdfProductos(empresa){
console.log(empresa);
 miUrl = "reportes/rptProductos.php?empresa="+empresa;
 window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
}

function revisarLibroMayor(cuenta,idcuenta){
    
    document.getElementById('cuenta_contable').value=cuenta;
    document.getElementById('txtIdPlanCuenta').value=idcuenta;
     let boton = document.getElementById("nav-profile-tab");
  boton.click();
   document.getElementById('txtIdPlanCuenta').value='';
}
function revisarLibroDiario(cuenta,idcuenta){
    
    document.getElementById('cuenta_contable').value=cuenta;
    document.getElementById('txtIdPlanCuenta').value=idcuenta;
     let boton = document.getElementById("nav-home-tab");
  boton.click();
   document.getElementById('txtIdPlanCuenta').value='';
}

  $(document).ready(function(){
			$('#txtProducto').select2();
		});
		
		function reporteExcelEstadosResultados(){
    var str = $("#form1").serialize();
        miUrl = "reportes/excelEstadoResultados2.php?"+str;
        window.open(miUrl); 
}
function eliminarAsiento(id_libro_diario){

            if(id_libro_diario >= 1 && id_libro_diario != ""){
                var respuesta3 = confirm("Seguro desea eliminar este Asiento Contable?");
                if (respuesta3){
                    $.ajax
                    ({
                        url: 'sql/libroDiario.php',
                        data: 'id_libro_diario='+id_libro_diario+'&txtAccion=3',
                        type: 'post',
                       
                        success: function(data)
                        {
                        let response = data.trim();
 
                            if(response == 1)
                            {
                             alertify.success("Asiento ha sido eliminado.");
                            }
                            else
                            {
                             alertify.error("Asiento no ha sido eliminado.");
                            }
                        listar_diarios();
                        }
                    });
                }
            }else{
                alert("No hay datos para eliminar");
            }
}


</script>

</body>	
    
</html>