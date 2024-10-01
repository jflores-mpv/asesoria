<?php
	//Start session
	session_start();
	require_once('../ver_sesion.php');

	//Include database connection details
	require_once('../conexion2.php');

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
    <script language="javascript" type="text/javascript" src="js/validaciones.js"></script>
	
	<!-- reportes -->
    <script type="text/javascript">

        $(document).ready(function(){
            
            listarFacturasCompras();

			$('#txtProveedor').select2();
	});
</script>

    <!--END estilo para  el menu -->

</head>

<body onload="">


    <div class="modal-header">
        <h3>Reportes de Compras</h3>
        <a href="javascript: fn_cerrar();"><button type="button" class="close" ><span aria-hidden="true">&times;</span></button></a>
    </div>
    <div class="modal-body">
                <form name="frmReporteFacturas" id="frmReporteFacturas" method="post" action="javascript:  listarFacturasCompras();">
        <div class="row">
            <div class="col-lg-3">
                
                <div class="row">
                    <div class="col-lg-12">
                        <label class="text-left">Buscar: </label> 
                                <select class="form-control" id="txtProveedor" name="txtProveedor">
                                <?php
                                    $consulta ="Select * From proveedores where id_empresa='".$sesion_id_empresa."';";
                                    $resultado = mysqli_query($conexion , $consulta);
                                    $contador=0;
                                    
                                    while($misdatos = mysqli_fetch_assoc($resultado)){ $contador++;?>
                                    <option data-subtext="<?php echo $misdatos["id_proveedor"]; ?>"><?php echo $misdatos["nombre"]; ?></option>
                                <?php }?> 
                               
                            </select>
                    </div>    
                    
                    <div class="col-lg-12">
                        <label class="text-left">Desde: </label> 
                        <input type="datetime" tabindex="1" id="txtFechaDesde" value="<?php echo date('Y')."-"."01"."-"."01" ; ?>"
                        class="form-control" required="required" name="txtFechaDesde" onClick="displayCalendar(txtFechaDesde,'yyyy-mm-dd',this)"
                        onchange="validaFechas(txtFechaDesde.value, txtFechaHasta.value); listarFacturasCompras();" placeholder="Fecha inicio"  autocomplete="off"/>
                        <div id="mensajefecha_desde" ></div>
                    </div>
                    
                    <div class="col-lg-12">
                        <label class="text-left">Hasta: </label> 
                        <input type="text" tabindex="2" id="txtFechaHasta" value="<?php echo date("Y-m-d",time()); ?>" class="form-control"
                        required="required" name="txtFechaHasta" onClick="displayCalendar(txtFechaHasta,'yyyy-mm-dd',this)" 
                        onchange="validaFechas(txtFechaDesde.value, txtFechaHasta.value); listarFacturasCompras();" placeholder="Fecha tope"  autocomplete="off"/>
                        <div id="mensajefecha_hasta" ></div>
                    </div>   
                    
                    <div class="col-lg-12">
                         <label class="text-left">Documento: </label> 
                                <select id="tipoDoc" class="form-select">
                                            <option value="1">Facturas</option>
                                            <option value="2">Nota de crédito</option>
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
                    <div class="col-lg-4 mt-3">            
                                <select name="criterio_ordenar_por"  class="form-select">
                                    <option value=" ventas.`id_venta` ">Id</option>
                                </select>
                    </div>            
                    <div class="col-lg-4 mt-3">
                                <select name="criterio_orden" class="form-select">
                                    <option value="asc">asc</option>
                                    <option value="desc">desc</option>
                                </select>
                    </div>
                    <div class="col-lg-12 mt-3">
                        <input  type="button" tabindex="5" name="submit" value="Descargar Reporte Detallado" id="" class="btn btn-outline-warning" 
                        align="right" onclick="javascript: pdfReporteFacturaR1();" />
                    </div>
                </div>
                
            </div>
            <div class="col-lg-9">
                 <div id="div_listar_facturasCompras"></div>
            </div>
        </div>
        </form>
    </div>


<script type="">  

     function pdfSaldoInicial(id_venta, txtFechaDesde, txtFechaHasta){

        miUrl = "reportes/rptFacturas_detallado.php?id_venta="+id_venta+"&txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    } 

     function pdfReporteFacturaR(id_venta, txtFechaDesde, txtFechaHasta){

        miUrl = "reportes/rptVentas.php?id_venta="+id_venta+"&txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    } 

    function pdfReporteFacturaR1(id_compras, txtFechaDesde, txtFechaHasta,txtProveedor){

        miUrl = "reportes/rptCompras.php?id_compras="+id_compras+"&txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    } 


</script>

</body>
</html>

