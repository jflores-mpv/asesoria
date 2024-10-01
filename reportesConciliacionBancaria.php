
<?php
	//Start session
	session_start();
	require_once('ver_sesion.php');
	//Include database connection details
	require_once('conexion2.php');

    $cookie_tipo = $_COOKIE['tipo_cookie'];
    $cookie_id_empleado = $_COOKIE['id_empleado_cookie'];
     $id_usuario=   $_SESSION['sesion_id_usuario'] ;
     $emision_SOCIO= $_SESSION["emision_SOCIO"];
    $nombre = $_SESSION['sesion_nombre'];
    $apellido = $_SESSION['sesion_apellido'];
    $sesion_tipo = $_SESSION["sesion_tipo"];
    $sesion_id_empleado = $_SESSION["sesion_id_empleado"];
    $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
//  echo "tipo===".$sesion_tipo;
        //PERMISOS AL MODULO ASIENTOS CONTABLES
    $sesion_bancos_guardar = $_SESSION['sesion_bancos_guardar'];
?>

<html>
<head>
 <link rel="shortcut icon" href="images/logo.png">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reporte Conciliaci&oacute;n Bancaria</title>

    <script src="https://kit.fontawesome.com/7816c50242.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">  
    <script src="librerias/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <script src="librerias/bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" href="librerias/bootstrap/css/style.css"> 
    <link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/alertify.css">
    <script src="librerias/alertifyjs/alertify.js"></script>
    <script src="librerias/select2/js/select2.js"></script>
    <link rel="stylesheet" type="text/css" href="librerias/select2/css/select2.css">
    
    
    
    <script language="javascript" type="text/javascript" src="js/condominios.js"></script>
	<script language="javascript" type="text/javascript" src="js/index.js"></script>
    <!-- validaciones -->
    <script language="javascript" type="text/javascript" src="js/reportes.js"></script>
    <script language="javascript" type="text/javascript" src="js/validaciones.js"></script>
	    <script language="javascript" type="text/javascript" src="js/funciones.js"></script>
	
		<link type="text/css" rel="stylesheet" href="css/calendario.css" media="screen"/>
	<script language="javascript" type="text/javascript" src="js/calendario.js"></script>
	
	<!-- reportes -->
    <script type="text/javascript">
        $(document).ready(function(){
            
            listar_reporte_ConciliacionBancaria(1);
            
			$('#txtClientes').select2();
			 $('#txtProductos').select2();
		});
    </script>

    <!--END estilo para  el menu -->

</head>

<body onload="cargaCuentasBancos2(1); ">
    
    <div class="wrapper d-flex align-items-stretch celeste">
    <nav id="sidebar" ><?php  {include("menusNiveles.php");      }   ?></nav>
    <div id="content"  >
    <header ><?php  {include("header.php");      }   ?>  </header>
 

<div class="row pl-4 pt-5">
    <div class="col-lg-12 ">
        <div class="row  mb-2 text-info r-10  ">
            <a class="col-lg-2 col-sm-2 text-decoration-none card p-1 m-1"  type="button"   id="btnNuevaFactura" 
            name="btnNuevaFactura" onclick="location='bancos.php'" />
                <div class="my-icon3 rounded "><i class="fa fa-shopping-cart mr-3 fa-2x"></i><span>Nueva Conciliaci&oacute;n Bancaria</span>  </div>
            </a>
        </div>
    </div>
</div>    

    <div class="modal-body">
       <form name="frmClientes" id="frmClientes" method="post" action="javascript:  listar_reporte_ConciliacionBancaria(1);">
                    <div class="row">
                        <div class="col-lg-3">

                            <div class="row">
                                
               
                             
                                <div class="col-lg-12">
                                    <label class="text-left">Desde: </label>
                                    <input type="text" tabindex="1" id="txtFechaDesde" value="<?php echo date('Y') . "-" . "01" . "-" . "01"; ?>" class="form-control" required="required" name="txtFechaDesde" onClick="displayCalendar(txtFechaDesde,'yyyy-mm-dd',this)"
                                    onChange="listar_reporte_ConciliacionBancaria(1)" />
                                    <div id="mensajefecha_desde"></div>
                                </div>

                                <div class="col-lg-12">
                                    <label class="text-left">Hasta: </label>
                                    <input type="text" tabindex="2" id="txtFechaHasta" value="<?php echo date("Y-m-d", time()); ?>" class="form-control" required="required" name="txtFechaHasta" onClick="displayCalendar(txtFechaHasta,'yyyy-mm-dd',this)" onChange="listar_reporte_ConciliacionBancaria(1)" />
                                    <div id="mensajefecha_hasta"></div>
                                </div>

                         
                               
                                <div class="col-lg-12">
                <label >Seleccione Banco: </label>
                      <select class="form-control" name="cmbNombreCuentaCB"  id="cmbNombreCuentaCB" title="" onchange="cargarDetallesCuentaBancos(2, cmbNombreCuentaCB.value);listar_reporte_ConciliacionBancaria(1);" > </select>
                </div>    
                 <div class="col-lg-4 mt-3">
                                    <select name="criterio_mostrar" id="criterio_mostrar" class="form-select" onChange="listar_reporte_ConciliacionBancaria(1)" >
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="30">30</option>
                                        <option value="10000">Todos</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 mt-3">
                                    <select name="criterio_ordenar_por" id="criterio_ordenar_por" class="form-select" onChange="listar_reporte_ConciliacionBancaria(1)" >
                                        <option value=" detalle_bancos.`tipo_documento`">Tipo Documento</option>
                                         <option value="detalle_bancos.`valor` ">Valor</option>
                                          <option value="detalle_bancos.`fecha_cobro`">Fecha de cobro</option>
                                          <option value="detalle_bancos.`fecha_vencimiento`">Fecha de vencimiento</option>
                                          
                                    </select>
                                </div>
                                  <div class="col-lg-4 mt-3">
                                    <select name="criterio_orden" id="criterio_orden" class="form-select" onChange="listar_reporte_ConciliacionBancaria(1)" >
                                        <option value="asc">asc</option>
                                        <option value="desc">desc</option>
                                    </select>
                                </div>
                     
                                <div class="col-lg-12 mt-3">
                                    <label>Estado</label>
                                    <select name="estado" id="estado" class="form-select" onChange="listar_reporte_ConciliacionBancaria(1)">
                                        <option value="0">Todos</option>
                                        <option value="1">Conciliados</option>
                                        <option value="2">No Conciliados</option>
                                        
                                    </select>
                                </div>
                                
                                
                <div class="col-lg-12 mt-3">
                            <input type="button" tabindex="5" name="submit" value="Descargar Reporte Detallado DCA" id="" class="btn btn-outline-warning" align="right" 
                            onclick="javascript: pdfReporteConciliacionBancariaDCA();" />
                </div>
                                

                        
                            

                            </div>

                        </div>
                        <div class="col-lg-9">
                            <!--<h5>Lamentablemente, debido a la indisponibilidad del sistema del Servicio de Rentas Internas (SRI), no se est&aacute;n autorizando las facturas en este momento.</h5>-->
                            <div id="div_listar_reporte_ConciliacionBancaria"></div>
                        </div>
                    </div>



                </form>
    </div>


<script type="">  

 function pdfReporteConciliacionBancaria(){
     var str = $("#frmClientes").serialize();
        miUrl = "reportes/rptConciliacionBancaria.php?"+str;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    }
    
    
 function pdfReporteConciliacionBancariaDCA(){
     var str = $("#frmClientes").serialize();
        miUrl = "reportes/rptConciliacionBancariaDca.php?"+str;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    }
    

function cargaCuentasBancos2(aux){

    ajax4=objetoAjax();
    ajax4.open("POST", "sql/bancos.php",true);
    ajax4.onreadystatechange=function(){
        if (ajax4.readyState==4){
            var respuesta=ajax4.responseText;
            asignaCuentasBancos2(respuesta);            
            //console.log("respuesta",respuesta);
        }
    }
    ajax4.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax4.send("accion="+aux)
}
function asignaCuentasBancos2(cadena){
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;

    //limpiaCuentasBancos();
    cmbNombreCuentaCB.options[0] = new Option("Seleccione Cuenta","0");
    for(i=1;i<limite;i=i+2){
       cmbNombreCuentaCB.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
    
}
</script>

</body>
</html>

