<?php
	//Start session
	session_start();
	require_once('../ver_sesion.php');

	//Include database connection details
	require_once('../conexion.php');

    $cookie_tipo = $_COOKIE['tipo_cookie'];
    $cookie_id_empleado = $_COOKIE['id_empleado_cookie'];

    $nombre = $_SESSION['sesion_nombre'];
    $apellido = $_SESSION['sesion_apellido'];
    $sesion_tipo = $_SESSION["sesion_tipo"];
    $sesion_id_empleado = $_SESSION["sesion_id_empleado"];
    $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];

        //PERMISOS AL MODULO ASIENTOS CONTABLES
    $sesion_bancos_guardar = $_SESSION['sesion_bancos_guardar'];
?>

<html>
<head>
 <link rel="shortcut icon" href="images/logo.png">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reporte Facturas</title>
    <!-- estilos para el calendario -->
    <link type="text/css" rel="stylesheet" href="css/calendario.css" media="screen"/>
    <script language="javascript" type="text/javascript" src="js/calendario.js"></script>
    
    <!-- condominios -->
    <script language="javascript" type="text/javascript" src="js/condominios.js"></script>
    
    <!-- validaciones -->
    <script language="javascript" type="text/javascript" src="js/validaciones.js"></script>
	
	<!-- reportes -->
    
	<script language="javascript" type="text/javascript" src="js/reportes.js"></script>
    

    <script type="text/javascript">

        $(document).ready(function(){
            
            listar_reporte_facturas();

        });

    </script>

    <!--END estilo para  el menu -->

</head>

<body onload="">

    <div class="modal-header">
        <h3>Reportes de ventas</h3>
        <a href="javascript: fn_cerrar();"><button type="button" class="close" ><span aria-hidden="true">&times;</span></button></a>
    </div>
    <div class="modal-body">
        <form name="frmReporteFacturas" id="frmReporteFacturas" method="post" action="javascript:  listar_reporte_facturas();">
        <div class="row">
            <div class="col-lg-3">
                
                <div class="row">
                    <div class="col-lg-12">
                        <label class="text-left">Buscar: </label> 
                        <input  type="text" name="txtBuscar" id="txtBuscar" value="" class="form-control " placeholder="Buscar por cliente" onkeyup="listar_reporte_facturas();"/>
                    </div>
                    <div class="col-lg-12">
                        <label class="text-left">Desde: </label> 
                        <input type="datetime" tabindex="1" id="txtFechaDesde" value="<?php echo date('Y')."-"."01"."-"."01" ; ?>"
                        class="form-control" required="required" name="txtFechaDesde" onClick="displayCalendar(txtFechaDesde,'yyyy-mm-dd',this)"
                        onchange="validaFechas(txtFechaDesde.value, txtFechaHasta.value); listar_reporte_facturas();" placeholder="Fecha inicio"  autocomplete="off"/>
                        <div id="mensajefecha_desde" ></div>
                    </div>
                    <div class="col-lg-12">
                        <label class="text-left">Hasta: </label> 
                        <input type="text" tabindex="2" id="txtFechaHasta" value="<?php echo date("Y-m-d",time()); ?>" class="form-control"
                        required="required" name="txtFechaHasta" onClick="displayCalendar(txtFechaHasta,'yyyy-mm-dd',this)" 
                        onchange="validaFechas(txtFechaDesde.value, txtFechaHasta.value); listar_reporte_facturas();" placeholder="Fecha tope"  autocomplete="off"/>
                        <div id="mensajefecha_hasta" ></div>
                    </div>   
                    <div class="col-lg-12">
                         <label class="text-left">Documento: </label> 
                         <select id="tipoDoc" class="form-select" name="tipoDoc"  onchange="javascript:listar_reporte_facturas();">
                                            <option value="1">Facturas</option>
                                            <option value="4">Nota de crédito</option>
                                            <option value="3">Guía de remisión</option>
                                            <option value="5">Proforma</option>
                                </select>
                    </div>
                    <div class="col-lg-4 mt-3">
                                <select name="criterio_mostrar" class="form-select" >
                                    <option value="10">10</option>       
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                </select>
                    </div> 
                </div>
            </div>
            <div class="col-lg-9">
                 <div id="div_listar_reporte_facturas"></div>
            </div>
        </div>
    </div>
    
    

        


            <div id="mensaje1" ></div>

            

                
                

                            <td style="display: none">
                                <select name="criterio_mostrar" >
                                    <option value="100000000000000000000">100000000000000000000</option>                                    
                                </select>
                                <select name="criterio_ordenar_por" style="display: none">
                                    <option value=" ventas.`id_venta` ">Id</option>
                                    
                                </select>
                                <select name="criterio_orden" style="display: none">
                                    <option value=" asc ">asc</option>
                                    <option value=" desc ">desc</option>
                                </select>
                                
                            </td>

            	</div>
            </form>
        <div id="div_oculto" class="caja" style="display: none;"></div>
 


<script type="">  

     function pdfSaldoInicial(id_venta, txtFechaDesde, txtFechaHasta){

        miUrl = "reportes/rptFacturas_detallado.php?id_venta="+id_venta+"&txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    } 

     function pdfReporteFacturaR(id_venta, txtFechaDesde, txtFechaHasta){

        miUrl = "reportes/rptVentas.php?id_venta="+id_venta+"&txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    } 

    function pdfReporteFacturaR1(id_venta, txtFechaDesde, txtFechaHasta){

        miUrl = "reportes/rptFacturas.php?id_venta="+id_venta+"&txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    } 


</script>

</body>
</html>

