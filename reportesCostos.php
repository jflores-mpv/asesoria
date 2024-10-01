
<?php
	//Start session
	session_start();
	require_once('ver_sesion.php');
	//Include database connection details
	require_once('conexion2.php');

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
<title>Reporte de Costos</title>

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
	<link type="text/css" rel="stylesheet" href="css/calendario.css" media="screen"/>
	<script language="javascript" type="text/javascript" src="js/calendario.js"></script>
	<!-- reportes -->
    <script type="text/javascript">
        $(document).ready(function(){
            
           
            
			$('#txtClientes').select2();
            $('#bodegas').select2();
			
		});
    </script>

    <!--END estilo para  el menu -->

</head>

<body onload="listar_costos(1);">
    
    <div class="wrapper d-flex align-items-stretch celeste">
    <nav id="sidebar" ><?php  {include("menusNiveles.php");      }   ?></nav>
    <div id="content"  >
    <header ><?php  {include("header.php");      }   ?>  </header>
 

<div class="row pl-4 pt-5">
    <div class="col-lg-12 ">
        <div class="row  mb-2 text-info r-10  ">
            <a class="col-lg-2 col-sm-2 text-decoration-none card p-1 m-1"  type="button"   id="btnNuevaFactura" 
            name="btnNuevaFactura" onclick="location='costos.php'" />
                <div class="my-icon3 rounded "><i class="fa fa-shopping-cart mr-3 fa-2x"></i><span>Nueva Costo de Producci&oacute;n</span>  </div>
            </a>
        </div>
    </div>
</div>    

    <div class="modal-body">
       <form name="frmCostos" id="frmCostos" method="post" action="javascript:  listar_costos(1);">
                    <div class="row">
                        <div class="col-lg-3">

                            <div class="row">
                                
                                <div class="col-lg-12 mb-2">
                                    <label for="id_label_single">Buscar por Producto:</label>
                                    <select class="js-example-basic-single js-states form-control" id="txtClientes" name="txtClientes" onChange="listar_costos(1)">
                                        <option data-subtext="todos" value="0">Todos</option>
                                        <?php
                                        $consulta = "SELECT costos_cabecera.`id_costo`, productos.codigo as producto_codigo, productos.id_producto, productos.producto FROM `costos_cabecera` INNER JOIN productos ON productos.id_producto = costos_cabecera.`producto` WHERE costos_cabecera.empresa =$sesion_id_empresa and productos.id_empresa=$sesion_id_empresa";
                                        // echo $consulta;
                                        $resultado = mysqli_query($conexion, $consulta);

                                        while ($misdatos = mysqli_fetch_assoc($resultado)) {
                                        ?>
                                            <option data-subtext="<?php echo $misdatos["id_producto"]; ?>" value="<?php echo $misdatos["id_producto"]; ?>">
                                                <?php echo $misdatos["producto"]; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                              
                                <div class="col-lg-12">
                                    <label class="text-left">Desde: </label>
                                    <input type="text" tabindex="1" id="txtFechaDesde" value="<?php echo date('Y') . "-" . "01" . "-" . "01"; ?>" class="form-control" required="required" name="txtFechaDesde" onChange="listar_costos(1)" onClick="displayCalendar(txtFechaDesde,'yyyy-mm-dd',this)" />
                                    <div id="mensajefecha_desde"></div>
                                </div>

                                <div class="col-lg-12">
                                    <label class="text-left">Hasta: </label>
                                    <input type="text" tabindex="2" id="txtFechaHasta" value="<?php echo date("Y-m-d", time()); ?>" class="form-control" required="required" name="txtFechaHasta" onChange="listar_costos(1)" onClick="displayCalendar(txtFechaHasta,'yyyy-mm-dd',this)" />
                                    <div id="mensajefecha_hasta"></div>
                                </div>

                                
                                <div class="col-lg-4 mt-3">
                                    <select name="criterio_mostrar" id="criterio_mostrar" class="form-select" onChange="listar_costos(1)">
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="30">30</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 mt-3">
                                    <select name="criterio_ordenar_por" id="criterio_ordenar_por" class="form-select" onChange="listar_costos(1)">
                                        <option value="1">Id</option>
                                    </select>
                                </div>
                                  <div class="col-lg-4 mt-3">
                                    <select name="criterio_orden" id="criterio_orden" class="form-select" onChange="listar_costos(1)">
                                         <option value="desc">desc</option>
                                        <option value="asc">asc</option>
                                       
                                    </select>
                                </div>
                         
<!--                            
                                <div class="col-lg-12 mt-3">
                                    <input type="button" tabindex="5" name="submit" value="Descargar Reporte Detallado" id="" class="btn btn-outline-warning" align="right" 
                                    onclick="javascript: pdfReporteFacturaR();" />
                                </div> -->
                     
                            </div>

                        </div>
                        <div class="col-lg-9">
                            <div id="div_listar_costos"></div>
                        </div>
                    </div>



                </form>
    </div>


</body>
<script>
    const listar_costos =(page=1)=>{
        var str = $("#frmCostos").serialize();
    $.ajax({
            url: 'ajax/listarReportesCostos.php',
            type: 'get',
            data: str+"&page="+page,
            success: function(data){
                $("#div_listar_costos").html(data);
            }
    });
    }
    const generarPDF_costos=(id_costo=0, numeroCosto=0)=>{
        
          miUrl = "reportes/rptCostos.php?id_costo="+id_costo+"&numeroCosto="+numeroCosto;
         window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    }
</script>
</html>

