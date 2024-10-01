
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
     date_default_timezone_set('America/Guayaquil');
    $dominio = $_SERVER['SERVER_NAME'];
    
    $dominio = $_SERVER['SERVER_NAME'];
    if($dominio=='jderp.cloud' or $dominio=='www.jderp.cloud'){
                $fecha_desde = date("Y-m-d",time() ) ;
    }else{
        $fecha_desde =  date('Y')."-"."01"."-"."01" ;
    }
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
    <script language="javascript" type="text/javascript" src="js/reportes.js"></script>
    <script language="javascript" type="text/javascript" src="js/validaciones.js"></script>
	
	
		<link type="text/css" rel="stylesheet" href="css/calendario.css" media="screen"/>
	<script language="javascript" type="text/javascript" src="js/calendario.js"></script>
	
	<!-- reportes -->
    <script type="text/javascript">
        $(document).ready(function(){
            
            listar_reporte_facturas(1);
            listar_reporte_clientes_jd(1);
			$('#txtClientes').select2();
			 $('#txtProductos').select2();
		});
    </script>

    <!--END estilo para  el menu -->

</head>

<body onload="combopais2(1); comboprovincia2(2); ">
    
<div class="wrapper d-flex align-items-stretch celeste">
    <nav id="sidebar" ><?php  {include("menusNiveles.php");      }   ?></nav>
    
        <div id="content"  class="p-0 m-0">
            <div class="row m-0"><?php  {include("header.php");      }   ?>  </div>
        
            <div class="row  m-0 "> 
                <div class="col-lg-12 ">
                    <div class="row  ">
                        <a class="col-lg-2 col-sm-2 text-decoration-none card p-1 m-1"  type="button"   id="btnNuevaFactura" 
                        name="btnNuevaFactura" onclick="location='facturaVentaEducativo.php'" />
                            <div class="my-icon3 rounded "><i class="fa fa-shopping-cart mr-3 fa-2x"></i><span>Nueva Venta</span>  </div>
                        </a>
                    </div>
                </div>
            </div>    

    <div class="modal-body">
       <form name="frmClientes" id="frmClientes" method="post" action="javascript:  listar_reporte_facturas(1);listar_reporte_clientes_jd(1)">
                    <div class="row">
                        <?php
                        $dominio = $_SERVER['SERVER_NAME'];
                    
                        if($dominio=='jderp.cloud' || $dominio=='www.jderp.cloud'  ){
                            ?>
                               <div class="col-lg-12">
<input type="hidden"  id="reporte" value="jderp"/>
                            <div class="row bg-white rounded px-4 pt-2 ">
                                
                                <div  class="input-group  celeste p-2 rounded" >
                                    <span class="input-group-text" id="basic-addon1">Buscar por Cliente:</span>
                                 
                                    <select class="js-example-basic-single js-states form-control" id="txtClientes" name="txtClientes" onChange="listar_reporte_clientes_jd(1)">
                                        <option data-subtext="todos" value="0">Todos</option>
                                        <?php
                                        $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
                                        $consulta = "Select * From clientes where id_empresa='".$sesion_id_empresa."';";
                                        
                                        // echo $consulta;
                                        $resultado = mysqli_query($conexion, $consulta);

                                        while ($misdatos = mysqli_fetch_assoc($resultado)) {
                                        ?>
                                            <option data-subtext="<?php echo $misdatos["id_cliente"]; ?>" value="<?php echo $misdatos["id_cliente"]; ?>">
                                                <?php echo $misdatos["nombre"] . $misdatos["apellido"]; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                <span class="input-group-text" id="basic-addon1">Buscar por Productos:</span>
                                 
                                    <label for="id_label_single"></label>
                                    <select class="js-example-basic-single js-states form-control" id="txtProductos" name="txtProductos" onChange="listar_reporte_clientes_jd(1);">
                                         
                                        <option data-subtext="todos" value="A">GLOBAL</option>
                                        <option data-subtext="todos" value="B">RESUMEN</option>
                                        <option data-subtext="todos" value="0">DETALLADO</option>
                                        <?php
                                        $consulta = "Select * From productos where id_empresa='".$sesion_id_empresa."';";
                                        // echo $consulta;
                                        $resultado2 = mysqli_query($conexion, $consulta);

                                        while ($misdatos2 = mysqli_fetch_assoc($resultado2)) {
                                        ?>
                                            <option data-subtext="<?php echo $misdatos2["id_producto"]; ?>" value="<?php echo $misdatos2["id_producto"]; ?>">
                                                <?php echo $misdatos2["producto"] ; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                <span class="input-group-text" id="basic-addon1">Desde:</span>
                                  
                                    <input type="text" tabindex="1" id="txtFechaDesde" value="<?php echo $fecha_desde; ?>" class="form-control" required="required" name="txtFechaDesde" onClick="displayCalendar(txtFechaDesde,'yyyy-mm-dd',this)"
                                    onChange="listar_reporte_clientes_jd(1)" />
                                    <div id="mensajefecha_desde"></div>
                                <span class="input-group-text" id="basic-addon1">Hasta:</span>
                                    
                                    <input type="text" tabindex="2" id="txtFechaHasta" value="<?php echo date("Y-m-d", time()); ?>" class="form-control" required="required" name="txtFechaHasta" onClick="displayCalendar(txtFechaHasta,'yyyy-mm-dd',this)" onChange="listar_reporte_clientes_jd(1)" />
                                    <div id="mensajefecha_hasta"></div>
                              
                              <span class="input-group-text" id="basic-addon1">Documento:</span>
                                   
                                    <select id="tipoDoc" class="form-select" name="tipoDoc" onChange="listar_reporte_clientes_jd(1)" >
                                            <option value="1">Facturas</option>
                                            <option value="4">Nota de cr&eacute;dito</option>
                                            <option value="6">Gu&iacute;a de remisi&oacute;n</option>
                                            <option value="5">Proforma</option>
                                            <option value="10">Pedido</option>
                                             <option value="41">Reembolsos</option>
                                            </select>
                             </div>
                            <div  class="input-group  celeste p-2 rounded" >
                               <span class="input-group-text" id="basic-addon1">Mostrar:</span>
                                    <select name="criterio_mostrar" id="criterio_mostrar" class="form-select" onChange="listar_reporte_clientes_jd(1)">
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="30">30</option>
                                        <option value="10000">Todos</option>
                                    </select>
                               <span class="input-group-text" id="basic-addon1">Ordenar:</span>
                                    <select name="criterio_ordenar_por" id="criterio_ordenar_por" class="form-select" onChange="listar_reporte_clientes_jd(1)">
                                        <option value="numero_factura_venta">Numero de Factura</option>
                                    </select>
                                <span class="input-group-text" id="basic-addon1">Orden:</span>
                                     
                                    <select name="criterio_orden" id="criterio_orden" class="form-select" onChange="listar_reporte_clientes_jd(1)">
                                        <option value="desc">desc</option>
                                        <option value="asc">asc</option>
                                        
                                    </select>
                                 <span class="input-group-text" id="basic-addon1">Estado Facturas:</span>
                                   
                                    <select name="estado" id="estado" class="form-select" onChange="listar_reporte_clientes_jd(1);resetearProducto_areas(this.value)">
                                        <option value="0">Todos</option>
                                        <option value="Pasivo">Anuladas</option>
                                        <option value="Activo">Activas</option>
                                        <option value="D">Detalle</option>
                                    </select>
                               <span class="input-group-text" id="basic-addon1">Autorizaci&oacute;n</span>
                                   
                                    <select name="autorizacion" id="autorizacion" class="form-select" onChange="listar_reporte_clientes_jd(1)">
                                        <option value="0">Todos</option>
                                        <option value="1">Autorizadas</option>
                                        <option value="2">No Autorizadas</option>
                                        
                                    </select>
                               <span class="input-group-text" id="basic-addon1">Areas</span>
                                  
                                    <select name="areas" id="areas" class="form-select" onChange="listar_reporte_clientes_jd(1);"onblur="resetearProducto()">
                                        <option value="A">Global</option>
                                        <option value="B">RESUMEN</option>
                                        <option value="0">DETALLADO</option>
                                         <?php
                                           if($dominio=='www.contaweb.ec' or $dominio=='contaweb.ec'       or $dominio=='contanet-ec.com' or $dominio=='www.contanet-ec.com' ){
                                        ?>
                                    
                                         <option value="104" data-valor='104'>104</option>
                                        <?php
                                           }
                                      
                                        $consultaCC = "SELECT * FROM centro_costo where empresa='" . $sesion_id_empresa . "'";
                                        $resultadoCC = mysqli_query($conexion, $consultaCC);
                                        while ($misdatosC = mysqli_fetch_assoc($resultadoCC)) {
                                        ?>
                                            <option value="<?php echo $misdatosC["id_centro_costo"]; ?>">
                                                <?php echo $misdatosC["descripcion"] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    
                                    <span class="input-group-text">Vendedor:</span>
                
                <select  name="vendedor_id" id="vendedor_id" class="form-control" onChange="listar_reporte_clientes_jd(1);" >
                    
                    <option value="0">Ninguno</option>
                    <?php
                  
                    $sqltrans="Select * From vendedores where id_empresa='".$sesion_id_empresa."' AND estado='Activo';";
                    $sqltrans1=mysql_query($sqltrans);
                    while($rowtrans=mysql_fetch_array($sqltrans1))
                         {          
                    ?>
                          <option value="<?=$rowtrans['id_vendedor']; ?>"><?=$rowtrans['nombre'].' '.$rowtrans['apellidos']; ?></option>
                    <?php } ?>
            	</select>
                                </div>
                                <div class="input-group  celeste p-2 rounded">
                                     <span class="input-group-text" id="basic-addon1">Buscar por usuarios:</span>
                                  
                                    <select class="js-example-basic-single js-states form-control" id="txtUsuarios" name="txtUsuarios" onChange="listar_reporte_clientes_jd(1)">
                                        
                                        <?php
                                       
                                        if($emision_SOCIO!='0' && $sesion_tipo!='Administrador'){
                                            $consulta = "SELECT * FROM usuarios where id_usuario='" . $id_usuario . "'   ";
                                        }
                                        
                                        else if($emision_SOCIO!='0' && $sesion_tipo=='Administrador'){
                                            
                                             $consulta = "SELECT * FROM usuarios where id_empresa='" . $sesion_id_empresa . "'   ";
                                            echo "<option data-subtext='todos' value='0'>Todos</option>";
                                        }
                                        
                                        else{
                                            $consulta = "SELECT * FROM usuarios where id_empresa='" . $sesion_id_empresa . "'   ";
                                            echo "<option data-subtext='todos' value='0'>Todos</option>";
                                        }
                                        
                                        $resultado = mysqli_query($conexion, $consulta);
                                        while ($misdatos = mysqli_fetch_assoc($resultado)) {
                                        ?>
                                            <option data-subtext="<?php echo $misdatos["id_usuario"]; ?>" value="<?php echo $misdatos["id_usuario"]; ?>">
                                                <?php echo $misdatos["login"] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                          <span class="input-group-text" id="basic-addon1">Provincias (requerido)</span>

                                            <select class="form-control" tabindex="15" name="cbprovincia" id="cbprovincia" onChange="combociudad2(3);"></select>
                                            <input type="hidden" name="opcion1" id="opcion1" value="" />
                             <span class="input-group-text" id="basic-addon1">Ciudades (requerido)</span>          
                                           
                                            <select class="form-control" tabindex="16" name="cbciudad" id="cbciudad" onChange="listar_reporte_clientes_jd(1)"></select>
                                            <input style="border-style: none;" type="hidden" name="opcion2" id="opcion2" value="" id="opcion2" />
                            <span class="input-group-text" id="basic-addon1">Punto de Emisi&oacute;n</span>              
                                    
                                    <select name="emision" id="emision" class="form-select" onChange="listar_reporte_clientes_jd(1)">
                                    <option value="0">Todos</option>
                                      <option value="GLOBAL">Global</option>
                                        <?php 
                                         $sqlFactuFisica="SELECT establecimientos.id_empresa,id_est,emision.codigo as codigo_emision,establecimientos.codigo as codEstable,emision.id as emision_id ,emision.tipoFacturacion,emision.tipoEmision from emision,establecimientos where establecimientos.id_empresa=$sesion_id_empresa and emision.id_est=establecimientos.id ";
                                         $resultFactuFisica  = mysql_query($sqlFactuFisica);
                                         while ($rowFF= mysql_fetch_array($resultFactuFisica)){
                                        ?>
                                        <option value="<?php echo $rowFF['emision_id'] ?>"><?php echo $rowFF['codEstable']." - ".$rowFF['codigo_emision'] ?></option>
                                        <?php 
                                         }
                                        ?>
                                        
                                    </select>
                                    <span class="input-group-text" id="basic-addon1">Matrizador</span>              
                                    
                                    <select name="matrizador" id="matrizador" class="form-select" onChange="listar_reporte_clientes_jd(1)">
                                    <option value="0">Todos</option>
                                   
                                        <?php 
                                         $sqlMatrizador="SELECT `id_info_adicional`, `campo`, `descripcion`, `id_venta`, `id_empresa`  FROM `info_adicional` 
                                         WHERE `campo` = 'Matrizador' and id_empresa='".$sesion_id_empresa."' GROUP BY descripcion ";
                                         $resultMatrizador  = mysql_query($sqlMatrizador);
                                         while ($rowMatrizador= mysql_fetch_array($resultMatrizador)){
                                        ?>
                                        <option value="<?php echo $rowMatrizador['descripcion'] ?>"><?php echo $rowMatrizador['descripcion'] ?></option>
                                        <?php 
                                         }
                                        ?>
                                        
                                    </select>
                                    <span class="input-group-text" id="basic-addon1">N&uacute;mero de libro</span>              
                                    <input type="text" tabindex="1" id="numero_libro" class="form-control" required="required" name="numero_libro"  onkeyup="listar_reporte_clientes_jd(1)">
                                    
                                      
                                    <!--<select name="numero_libro" id="numero_libro" class="form-select" onChange="listar_reporte_clientes_jd(1)">-->
                                    <!--<option value="0">Todos</option>-->
                                   
                                    
                                        
                                    <!--</select>-->
                                </div>
                                <div class="input-group celeste p-2 rounded d-flex justify-content-between ">
                                 
                                    <input type="button" tabindex="5" name="submit" value="Descargar Reporte Detallado" id="" class="btn btn-success btn-lg" align="right" 
                                    onclick="javascript: pdfReporteFacturaR();" />
                      
                            <input type="button" tabindex="5" name="submit" value="Descargar Excel" id="" class="btn btn-success btn-lg" align="right"  onclick="javascript: reporteExcelVentas();" />
                           
                            <?php
                        if($sesion_id_empresa==41){
                            ?>
                         
                            <input type="button" tabindex="5" name="submit" value="Autorizacion Grupal" id="" class="btn btn-success btn-lg" align="right"  onclick="javascript: autorizarGrupal();" />
                 

                            <?php
                        }
                        ?>
                        </div>
                        
                                    <div class="row bordes" style="margin-top:1%" hidden="">
                                        <div class="col-lg-2">
                                            <label for="cbpais"><strong class="leftSpace">Pa&iacute;s (requerido)</strong></label>
                                            <select class="text_input10 " name="cbpais" id="cbpais" onChange="comboprovincia2(2);"></select>
                                            <input type="text" name="opcion" id="opcion" value="1" />
                                        </div>
                                    </div>
                                  
                         
                                   &nbsp;
                                </div>
                                
                               
                        
                       
                            </div>

                        </div>
                        <div class="col-lg-12">
                            <!--<h5>Lamentablemente, debido a la indisponibilidad del sistema del Servicio de Rentas Internas (SRI), no se est&aacute;n autorizando las facturas en este momento.</h5>-->
                            <div class="bg-white" id="div_listar_reporte_facturas_jd"></div>
                        </div>
                            <?php
                        }else{
                             ?>
                                <div class="col-lg-3">
<input type="hidden"  id="reporte" value="general"/>
                            <div class="row">
                                
                                <div class="col-lg-12 mb-2">
                                    <label for="id_label_single">Buscar por Cliente:</label>
                                    <select class="js-example-basic-single js-states form-control" id="txtClientes" name="txtClientes" onChange="listar_reporte_facturas(1)">
                                        <option data-subtext="todos" value="0">Todos</option>
                                        <?php
                                        $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
                                        $consulta = "Select * From clientes where id_empresa='".$sesion_id_empresa."';";
                                        
                                        // echo $consulta;
                                        $resultado = mysqli_query($conexion, $consulta);

                                        while ($misdatos = mysqli_fetch_assoc($resultado)) {
                                        ?>
                                            <option data-subtext="<?php echo $misdatos["id_cliente"]; ?>" value="<?php echo $misdatos["id_cliente"]; ?>">
                                                <?php echo $misdatos["nombre"] . $misdatos["apellido"]; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                 <div class="col-lg-12 mb-2">
                                    <label for="id_label_single">Buscar por Productos:</label>
                                    <select class="js-example-basic-single js-states form-control" id="txtProductos" name="txtProductos" onChange="listar_reporte_facturas(1);">
                                         
                                        <option data-subtext="todos" value="A">GLOBAL</option>
                                        <option data-subtext="todos" value="B">RESUMEN</option>
                                        <option data-subtext="todos" value="0">DETALLADO</option>
                                        <?php
                                        $consulta = "Select * From productos where id_empresa='".$sesion_id_empresa."';";
                                        // echo $consulta;
                                        $resultado2 = mysqli_query($conexion, $consulta);

                                        while ($misdatos2 = mysqli_fetch_assoc($resultado2)) {
                                        ?>
                                            <option data-subtext="<?php echo $misdatos2["id_producto"]; ?>" value="<?php echo $misdatos2["id_producto"]; ?>">
                                                <?php echo $misdatos2["producto"] ; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-lg-12">
                                    <label class="text-left">Desde: </label>
                                    <input type="text" tabindex="1" id="txtFechaDesde" value="<?php echo date('Y') . "-" . "01" . "-" . "01"; ?>" class="form-control" required="required" name="txtFechaDesde" onClick="displayCalendar(txtFechaDesde,'yyyy-mm-dd',this)"
                                    onChange="listar_reporte_facturas(1)" />
                                    <div id="mensajefecha_desde"></div>
                                </div>

                                <div class="col-lg-12">
                                    <label class="text-left">Hasta: </label>
                                    <input type="text" tabindex="2" id="txtFechaHasta" value="<?php echo date("Y-m-d", time()); ?>" class="form-control" required="required" name="txtFechaHasta" onClick="displayCalendar(txtFechaHasta,'yyyy-mm-dd',this)" onChange="listar_reporte_facturas(1)" />
                                    <div id="mensajefecha_hasta"></div>
                                </div>

                                <div class="col-lg-12">
                                    <label class="text-left">Documento: </label>
                                    <select id="tipoDoc" class="form-select" name="tipoDoc" onChange="listar_reporte_facturas(1)" >
                                            <option value="1">Facturas</option>
                                            <option value="4">Nota de cr&eacute;dito</option>
                                            <option value="6">Gu&iacute;a de remisi&oacute;n</option>
                                            <option value="5">Proforma</option>
                                            <option value="10">Pedido</option>
                                             <option value="41">Reembolsos</option>
                                            </select>
                                </div>
                                <div class="col-lg-4 mt-3">
                                    <select name="criterio_mostrar" id="criterio_mostrar" class="form-select" onChange="listar_reporte_facturas(1)">
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="30">30</option>
                                        <option value="10000">Todos</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 mt-3">
                                    <select name="criterio_ordenar_por" id="criterio_ordenar_por" class="form-select" onChange="listar_reporte_facturas(1)">
                                        <option value="numero_factura_venta">Numero de factura</option>
                                    </select>
                                </div>
                                  <div class="col-lg-4 mt-3">
                                    <select name="criterio_orden" id="criterio_orden" class="form-select" onChange="listar_reporte_facturas(1)">
                                        <option value="desc">desc</option>
                                        <option value="asc">asc</option>
                                        
                                    </select>
                                </div>
                                <div class="col-lg-12 mt-3">
                                    <label>Estado Facturas</label>
                                    <select name="estado" id="estado" class="form-select" onChange="listar_reporte_facturas(1);resetearProducto_areas(this.value)">
                                        <option value="0">Todos</option>
                                        <option value="Pasivo">Anuladas</option>
                                        <option value="Activo">Activas</option>
                                        <option value="D">Detalle</option>
                                    </select>
                                </div>
                                <div class="col-lg-12 mt-3">
                                    <label>Autorizaci&oacute;n</label>
                                    <select name="autorizacion" id="autorizacion" class="form-select" onChange="listar_reporte_facturas(1)">
                                        <option value="0">Todos</option>
                                        <option value="1">Autorizadas</option>
                                        <option value="2">No Autorizadas</option>
                                        
                                    </select>
                                </div>
                                
                                
                                <div class="col-lg-12 mt-3">
                                    <label>Areas</label>
                                    <select name="areas" id="areas" class="form-select" onChange="listar_reporte_facturas(1);"onblur="resetearProducto()">
                                        <option value="A">Global</option>
                                        <option value="B">RESUMEN</option>
                                        <option value="0">DETALLADO</option>
                                         <?php
                                           if($dominio=='www.contaweb.ec' or $dominio=='contaweb.ec'       or $dominio=='contanet-ec.com' or $dominio=='www.contanet-ec.com' ){
                                        ?>
                                    
                                         <option value="104" data-valor='104'>104</option>
                                        <?php
                                           }
                                      
                                        $consultaCC = "SELECT * FROM centro_costo where empresa='" . $sesion_id_empresa . "'";
                                        $resultadoCC = mysqli_query($conexion, $consultaCC);
                                        while ($misdatosC = mysqli_fetch_assoc($resultadoCC)) {
                                        ?>
                                            <option value="<?php echo $misdatosC["id_centro_costo"]; ?>">
                                                <?php echo $misdatosC["descripcion"] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-lg-12 mb-2">
                                    <label for="id_label_single">Buscar por usuarios:</label>
                                    <select class="js-example-basic-single js-states form-control" id="txtUsuarios" name="txtUsuarios" onChange="listar_reporte_facturas(1)">
                                        
                                        <?php
                                       
                                        if($emision_SOCIO!='0' && $sesion_tipo!='Administrador'){
                                            $consulta = "SELECT * FROM usuarios where id_usuario='" . $id_usuario . "'   ";
                                        }
                                        
                                        else if($emision_SOCIO!='0' && $sesion_tipo=='Administrador'){
                                            
                                             $consulta = "SELECT * FROM usuarios where id_empresa='" . $sesion_id_empresa . "'   ";
                                            echo "<option data-subtext='todos' value='0'>Todos</option>";
                                        }
                                        
                                        else{
                                            $consulta = "SELECT * FROM usuarios where id_empresa='" . $sesion_id_empresa . "'   ";
                                            echo "<option data-subtext='todos' value='0'>Todos</option>";
                                        }
                                        
                                        $resultado = mysqli_query($conexion, $consulta);
                                        while ($misdatos = mysqli_fetch_assoc($resultado)) {
                                        ?>
                                            <option data-subtext="<?php echo $misdatos["id_usuario"]; ?>" value="<?php echo $misdatos["id_usuario"]; ?>">
                                                <?php echo $misdatos["login"] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-lg-12 mb-2">
                                    <div class="row p-3">
                                        <div class="select-ciudad">
                                            <label for="cbprovincia">Provincias (requerido)</label>
                                            <select class="form-control" tabindex="15" name="cbprovincia" id="cbprovincia" onChange="combociudad2(3);"></select>
                                            <input type="hidden" name="opcion1" id="opcion1" value="" />
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="cbciudad">Ciudades (requerido)</label>
                                            <select class="form-control" tabindex="16" name="cbciudad" id="cbciudad"  onChange="listar_reporte_facturas(1)"></select>
                                            <input style="border-style: none;" type="hidden" name="opcion2" id="opcion2" value="" id="opcion2" />
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mt-3">
                                    <label>Punto de Emisi&oacute;n</label>
                                    <select name="emision" id="emision" class="form-select" onChange="listar_reporte_facturas(1)">
                                    <option value="0">Todos</option>
                                      <option value="GLOBAL">Global</option>
                                      <?php
                                     if( $_SERVER['SERVER_NAME'] == 'cmauditoresyconsultores.com' or $_SERVER['SERVER_NAME'] == 'www.cmauditoresyconsultores.com'){
                                          ?>
                                          <option value="Totales">Totales</option>
                                          <?php
                                      }
                                      ?>
                                        <?php 
                                         $sqlFactuFisica="SELECT establecimientos.id_empresa,id_est,emision.codigo as codigo_emision,establecimientos.codigo as codEstable,emision.id as emision_id ,emision.tipoFacturacion,emision.tipoEmision from emision,establecimientos where establecimientos.id_empresa=$sesion_id_empresa and emision.id_est=establecimientos.id ";
                                         $resultFactuFisica  = mysql_query($sqlFactuFisica);
                                         while ($rowFF= mysql_fetch_array($resultFactuFisica)){
                                        ?>
                                        <option value="<?php echo $rowFF['emision_id'] ?>"><?php echo $rowFF['codEstable']." - ".$rowFF['codigo_emision'] ?></option>
                                        <?php 
                                         }
                                        ?>
                                        
                                    </select>
                                </div>

                                    <div class="row bordes" style="margin-top:1%" hidden="">
                                        <div class="col-lg-12">
                                            <label for="cbpais"><strong class="leftSpace">Pa&iacute;s (requerido)</strong></label>
                                            <select class="text_input10 " name="cbpais" id="cbpais" onChange="comboprovincia2(2);"></select>
                                            <input type="text" name="opcion" id="opcion" value="1" />
                                        </div>
                                    </div>

                                </div>
                                
                                <div class="col-lg-12 mt-3">
                                    <input type="button" tabindex="5" name="submit" value="Descargar Reporte Detallado" id="" class="btn btn-outline-warning" align="right" 
                                    onclick="javascript: pdfReporteFacturaR();" />
                                </div>
                                <div class="col-lg-12 mt-3">
                            <input type="button" tabindex="5" name="submit" value="Descargar Excel" id="" class="btn btn-outline-warning" align="right"  onclick="javascript: reporteExcelVentas();" />
                        </div>
                        
                        <?php
                        if($sesion_id_empresa==364 ){
                            ?>
                              <div class="col-lg-12 mt-3">
                            <input type="button" tabindex="5" name="submit" value="Autorizacion Grupal" id="" class="btn btn-outline-warning" align="right"  onclick="javascript: autorizarGrupal();" />
                        </div>

                            <?php
                        }
                        ?>
                            </div>

                        </div>
                        <div class="col-lg-9">
                            <!--<h5>Lamentablemente, debido a la indisponibilidad del sistema del Servicio de Rentas Internas (SRI), no se est&aacute;n autorizando las facturas en este momento.</h5>-->
                            <div id="div_listar_reporte_facturas"></div>
                        </div>
                            <?php
                        }
                        ?>
                     
                        
                    </div>



                </form>
               
                      <iframe id="pdfFrame" style="display: none;"></iframe>
               
    </div>


<script type="">  
   $("#txtProductos").on('select2:close', function () {
 
   $('#areas').val('A').trigger('change.select2');

 $('#estado').val('0');
});

function resetearProducto(){
    $('#txtProductos').val('A').trigger('change.select2');
    $('#estado').val('0')

}
function resetearProducto_areas(valor){
    console.log('valor=>', valor);
    if(valor=='D'){
        $('#areas').val('A').trigger('change.select2');
         $('#txtProductos').val('A').trigger('change.select2');
    }
}
function impresionMiniFactura(id){

$.ajax({
        url: 'sql/imprimirMiniFactura.php',
        type: 'post',
        data: 'id='+id,
        success: function(data){
            var mywindow = window.open('', 'PRINT', 'height=400,width=600');
            mywindow.document.write(data);
            // mywindow.document.close(); // necessary for IE >= 10
            // mywindow.focus(); // necessary for IE >= 10*/

            //  mywindow.print();
            // mywindow.close();
        }
});
}
const reporteExcelVentas=()=>{
    
    let vendedor = 0;
        
        if(document.getElementById("vendedor_id")){
            vendedor  = document.getElementById("vendedor_id").value;
        }
        
    let matrizador = 0;
        
        if(document.getElementById("matrizador")){
            matrizador  = document.getElementById("matrizador").value;
        }
        
        
        let numero_libro = 0;
        
        if(document.getElementById("numero_libro")){
            numero_libro  = document.getElementById("numero_libro").value;
        }

miUrl = "reportes/excelVentas.php?txtClientes="+document.getElementById("txtClientes").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value+"&txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&tipoDoc="+document.getElementById("tipoDoc").value+"&txtUsuarios="+document.getElementById("txtUsuarios").value+"&cbciudad="+document.getElementById("cbciudad").value+"&criterio_ordenar_por="+document.getElementById("criterio_ordenar_por").value+"&criterio_orden="+document.getElementById("criterio_orden").value+"&estado="+document.getElementById("estado").value+"&areas="+document.getElementById("areas").value+"&autorizacion="+document.getElementById("autorizacion").value+"&txtProductos="+document.getElementById("txtProductos").value+"&emision="+document.getElementById("emision").value+"&matrizador="+matrizador+"&numero_libro="+numero_libro+"&vendedor="+vendedor;
console.log(miUrl);
window.open(miUrl);
} 
// const reporteExcelVentas2=()=>{

// miUrl = "reportes/excelVentas2.php?txtClientes="+document.getElementById("txtClientes").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value+"&txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&tipoDoc="+document.getElementById("tipoDoc").value+"&txtUsuarios="+document.getElementById("txtUsuarios").value+"&cbciudad="+document.getElementById("cbciudad").value+"&criterio_ordenar_por="+document.getElementById("criterio_ordenar_por").value+"&criterio_orden="+document.getElementById("criterio_orden").value+"&estado="+document.getElementById("estado").value+"&areas="+document.getElementById("areas").value+"&autorizacion="+document.getElementById("autorizacion").value+"&txtProductos="+document.getElementById("txtProductos").value;
// window.open(miUrl);
// } 

const reporteExcelVentas3=()=>{

miUrl = "reportes/excelVentas3.php?txtClientes="+document.getElementById("txtClientes").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value+"&txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&tipoDoc="+document.getElementById("tipoDoc").value+"&txtUsuarios="+document.getElementById("txtUsuarios").value+"&cbciudad="+document.getElementById("cbciudad").value+"&criterio_ordenar_por="+document.getElementById("criterio_ordenar_por").value+"&criterio_orden="+document.getElementById("criterio_orden").value+"&estado="+document.getElementById("estado").value+"&areas="+document.getElementById("areas").value+"&autorizacion="+document.getElementById("autorizacion").value+"&txtProductos="+document.getElementById("txtProductos").value+"&emision="+document.getElementById("emision").value;
window.open(miUrl);
} 

     function pdfSaldoInicial(id_venta, txtFechaDesde, txtFechaHasta){

        miUrl = "reportes/rptFacturas_detallado.php?id_venta="+id_venta+"&txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    } 

    // function pdfReporteFacturaR(id_venta, txtFechaDesde, txtFechaHasta,cbciudad,estado){
        
    //     let matrizador = 0;
        
    //     if(document.getElementById("matrizador")){
    //         matrizador  = document.getElementById("matrizador").value;
    //     }
        
        
    //     let numero_libro = 0;
        
    //     if(document.getElementById("numero_libro")){
    //         numero_libro  = document.getElementById("numero_libro").value;
    //     }
    //     let tipoxx = $('#areas').val();
        
    //     var dataAtributo = $('#areas option[value="' + tipoxx + '"]').data('valor');

    //     if(dataAtributo=='104'){
    //         miUrl = "reportes/rpt_104.php";
    //     window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    //     return;
    //     }
        
    //      let tipo3 =document.getElementById("estado").value;
       
    //   if(tipo3=='D'){
    //          miUrl = "reportes/rptVentaDetalle.php?txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value+"&txtClientes="+document.getElementById("txtClientes").value+"&mostrar="+document.getElementById("criterio_mostrar").value+"&orden="+document.getElementById("criterio_orden").value+"&motivo="+document.getElementById("criterio_ordenar_por").value+"&cbciudad="+document.getElementById("cbciudad").value+"&txtUsuarios="+document.getElementById("txtUsuarios").value+"&estado="+document.getElementById("estado").value+"&areas="+document.getElementById("areas").value+"&tipoDoc="+document.getElementById("tipoDoc").value+"&autorizacion="+document.getElementById("autorizacion").value+"&emision="+document.getElementById("emision").value+"&matrizador="+matrizador+"&numero_libro="+numero_libro;
             
    //     window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    //     return;
        
    //   }
       
       
    //      let tipo2 =document.getElementById("areas").value;
    //      let tipo1 =document.getElementById("txtProductos").value;
    //      if(tipo1 !='A'){
    //         pdfReporteFacturaProductos();
    //      }else{
    //         if(tipo2 =='A'){
    //          tipoDoc
    //           miUrl = "reportes/rptVentas.php?txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value+"&txtClientes="+document.getElementById("txtClientes").value+"&mostrar="+document.getElementById("criterio_mostrar").value+"&orden="+document.getElementById("criterio_orden").value+"&motivo="+document.getElementById("criterio_ordenar_por").value+"&cbciudad="+document.getElementById("cbciudad").value+"&txtUsuarios="+document.getElementById("txtUsuarios").value+"&estado="+document.getElementById("estado").value+"&areas="+document.getElementById("areas").value+"&tipoDoc="+document.getElementById("tipoDoc").value+"&autorizacion="+document.getElementById("autorizacion").value+"&emision="+document.getElementById("emision").value+"&matrizador="+matrizador+"&numero_libro="+numero_libro;
    //     window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    //      }else if (tipo2 =='B'){
    //         //RESUMEN
    //          tipoDoc
    //           miUrl = "reportes/rptVentasAreas.php?txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value+"&txtClientes="+document.getElementById("txtClientes").value+"&mostrar="+document.getElementById("criterio_mostrar").value+"&orden="+document.getElementById("criterio_orden").value+"&motivo="+document.getElementById("criterio_ordenar_por").value+"&cbciudad="+document.getElementById("cbciudad").value+"&txtUsuarios="+document.getElementById("txtUsuarios").value+"&estado="+document.getElementById("estado").value+"&areas="+document.getElementById("areas").value+"&tipoDoc="+document.getElementById("tipoDoc").value+"&autorizacion="+document.getElementById("autorizacion").value+"&emision="+document.getElementById("emision").value+"&idProducto="+document.getElementById("txtProductos").value+"&matrizador="+matrizador+"&numero_libro="+numero_libro;
    //     window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    //      }else{
    //           miUrl = "reportes/rptVentas2.php?txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value+"&txtClientes="+document.getElementById("txtClientes").value+"&mostrar="+document.getElementById("criterio_mostrar").value+"&orden="+document.getElementById("criterio_orden").value+"&motivo="+document.getElementById("criterio_ordenar_por").value+"&cbciudad="+document.getElementById("cbciudad").value+"&txtUsuarios="+document.getElementById("txtUsuarios").value+"&estado="+document.getElementById("estado").value+"&areas="+document.getElementById("areas").value+"&tipoDoc="+document.getElementById("tipoDoc").value+"&autorizacion="+document.getElementById("autorizacion").value+"&emision="+document.getElementById("emision").value+"&matrizador="+matrizador+"&numero_libro="+numero_libro;
    //     window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    //      }
    //      }
         
        
       
    // } 
function pdfReporteFacturaR(id_venta, txtFechaDesde, txtFechaHasta,cbciudad,estado){
        let vendedor = 0;
        
        if(document.getElementById("vendedor_id")){
            vendedor  = document.getElementById("vendedor_id").value;
        }
        
        let matrizador = 0;
        
        if(document.getElementById("matrizador")){
            matrizador  = document.getElementById("matrizador").value;
        }
        
        
        let numero_libro = 0;
        
        if(document.getElementById("numero_libro")){
            numero_libro  = document.getElementById("numero_libro").value;
        }
        let tipoxx = $('#areas').val();
        
        var dataAtributo = $('#areas option[value="' + tipoxx + '"]').data('valor');

        if(dataAtributo=='104'){
            miUrl = "reportes/rpt_104.php";
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
        return;
        }
        
         let tipo3 =document.getElementById("estado").value;
       
       if(tipo3=='D'){
             miUrl = "reportes/rptVentaDetalle.php?txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value+"&txtClientes="+document.getElementById("txtClientes").value+"&mostrar="+document.getElementById("criterio_mostrar").value+"&orden="+document.getElementById("criterio_orden").value+"&motivo="+document.getElementById("criterio_ordenar_por").value+"&cbciudad="+document.getElementById("cbciudad").value+"&txtUsuarios="+document.getElementById("txtUsuarios").value+"&estado="+document.getElementById("estado").value+"&areas="+document.getElementById("areas").value+"&tipoDoc="+document.getElementById("tipoDoc").value+"&autorizacion="+document.getElementById("autorizacion").value+"&emision="+document.getElementById("emision").value+"&matrizador="+matrizador+"&numero_libro="+numero_libro+"&vendedor="+vendedor;
             
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
        return;
        
       }
       
       
         let tipo2 =document.getElementById("areas").value;
         let tipo1 =document.getElementById("txtProductos").value;
         if(tipo1 !='A'){
            pdfReporteFacturaProductos();
         }else{
            if(tipo2 =='A'){
             tipoDoc
              miUrl = "reportes/rptVentas.php?txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value+"&txtClientes="+document.getElementById("txtClientes").value+"&mostrar="+document.getElementById("criterio_mostrar").value+"&orden="+document.getElementById("criterio_orden").value+"&motivo="+document.getElementById("criterio_ordenar_por").value+"&cbciudad="+document.getElementById("cbciudad").value+"&txtUsuarios="+document.getElementById("txtUsuarios").value+"&estado="+document.getElementById("estado").value+"&areas="+document.getElementById("areas").value+"&tipoDoc="+document.getElementById("tipoDoc").value+"&autorizacion="+document.getElementById("autorizacion").value+"&emision="+document.getElementById("emision").value+"&matrizador="+matrizador+"&numero_libro="+numero_libro+"&vendedor="+vendedor;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
         }else if (tipo2 =='B'){
            //RESUMEN
             tipoDoc
              miUrl = "reportes/rptVentasAreas.php?txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value+"&txtClientes="+document.getElementById("txtClientes").value+"&mostrar="+document.getElementById("criterio_mostrar").value+"&orden="+document.getElementById("criterio_orden").value+"&motivo="+document.getElementById("criterio_ordenar_por").value+"&cbciudad="+document.getElementById("cbciudad").value+"&txtUsuarios="+document.getElementById("txtUsuarios").value+"&estado="+document.getElementById("estado").value+"&areas="+document.getElementById("areas").value+"&tipoDoc="+document.getElementById("tipoDoc").value+"&autorizacion="+document.getElementById("autorizacion").value+"&emision="+document.getElementById("emision").value+"&idProducto="+document.getElementById("txtProductos").value+"&matrizador="+matrizador+"&numero_libro="+numero_libro+"&vendedor="+vendedor;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
         }else{
              miUrl = "reportes/rptVentas2.php?txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value+"&txtClientes="+document.getElementById("txtClientes").value+"&mostrar="+document.getElementById("criterio_mostrar").value+"&orden="+document.getElementById("criterio_orden").value+"&motivo="+document.getElementById("criterio_ordenar_por").value+"&cbciudad="+document.getElementById("cbciudad").value+"&txtUsuarios="+document.getElementById("txtUsuarios").value+"&estado="+document.getElementById("estado").value+"&areas="+document.getElementById("areas").value+"&tipoDoc="+document.getElementById("tipoDoc").value+"&autorizacion="+document.getElementById("autorizacion").value+"&emision="+document.getElementById("emision").value+"&matrizador="+matrizador+"&numero_libro="+numero_libro+"&vendedor="+vendedor;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
         }
         }
         
        
       
    }
    function pdfReporteFacturaR1(id_compras, txtFechaDesde, txtFechaHasta,txtProveedor){
        miUrl = "reportes/rptCompras.php?id_compras="+id_compras+"&txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value+"&txtProveedor="+document.getElementById("txtProveedor").value;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    } 
    
    
    function pdfReporteRetenciones(txtFechaDesde, txtFechaHasta){
        miUrl = "reportes/rptRetenciones.php?&txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    } 

  function pdfReporteFacturaProductos(){
      
       let tipo3 =document.getElementById("estado").value;
       
       if(tipo3=='D'){
             miUrl = "reportes/rptVentaDetalle.php?txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value+"&txtClientes="+document.getElementById("txtClientes").value+"&mostrar="+document.getElementById("criterio_mostrar").value+"&orden="+document.getElementById("criterio_orden").value+"&motivo="+document.getElementById("criterio_ordenar_por").value+"&cbciudad="+document.getElementById("cbciudad").value+"&txtUsuarios="+document.getElementById("txtUsuarios").value+"&estado="+document.getElementById("estado").value+"&areas="+document.getElementById("areas").value+"&tipoDoc="+document.getElementById("tipoDoc").value+"&autorizacion="+document.getElementById("autorizacion").value+"&emision="+document.getElementById("emision").value;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
        return;
        
       }
      
         let tipo2 =document.getElementById("txtProductos").value;
         
         if(tipo2 =='A'){
            //GLOBAL -> PARA QUE SALGA EL DE VENTAS COMPLETO 
             
              miUrl = "reportes/rptVentas.php?txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value+"&txtClientes="+document.getElementById("txtClientes").value+"&mostrar="+document.getElementById("criterio_mostrar").value+"&orden="+document.getElementById("criterio_orden").value+"&motivo="+document.getElementById("criterio_ordenar_por").value+"&cbciudad="+document.getElementById("cbciudad").value+"&txtUsuarios="+document.getElementById("txtUsuarios").value+"&estado="+document.getElementById("estado").value+"&areas="+document.getElementById("areas").value+"&tipoDoc="+document.getElementById("tipoDoc").value+"&autorizacion="+document.getElementById("autorizacion").value+"&emision="+document.getElementById("emision").value;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
         }else if (tipo2 =='B'){
            //RESUMEN -> COMO LA IMAGEN
             
              miUrl = "reportes/rptVentasProductos2.php?txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value+"&txtClientes="+document.getElementById("txtClientes").value+"&mostrar="+document.getElementById("criterio_mostrar").value+"&orden="+document.getElementById("criterio_orden").value+"&motivo="+document.getElementById("criterio_ordenar_por").value+"&cbciudad="+document.getElementById("cbciudad").value+"&txtUsuarios="+document.getElementById("txtUsuarios").value+"&estado="+document.getElementById("estado").value+"&areas="+document.getElementById("areas").value+"&tipoDoc="+document.getElementById("tipoDoc").value+"&autorizacion="+document.getElementById("autorizacion").value+"&emision="+document.getElementById("emision").value;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
         }else if (tipo2 =='D'){
            // DETALLADO-> VENTAS Y DETALLE VENTAS AGRUPADO POR PRODUCTOS / REPORTE POR PRODUCTOS
             
              miUrl = "reportes/rptProductosVentas.php?txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value+"&txtClientes="+document.getElementById("txtClientes").value+"&mostrar="+document.getElementById("criterio_mostrar").value+"&orden="+document.getElementById("criterio_orden").value+"&motivo="+document.getElementById("criterio_ordenar_por").value+"&cbciudad="+document.getElementById("cbciudad").value+"&txtUsuarios="+document.getElementById("txtUsuarios").value+"&estado="+document.getElementById("estado").value+"&areas="+document.getElementById("areas").value+"&tipoDoc="+document.getElementById("tipoDoc").value+"&autorizacion="+document.getElementById("autorizacion").value+"&emision="+document.getElementById("emision").value;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
         }else{
           // REPORTE POR PRODUCTOS
              miUrl = "reportes/rptProductosVentas.php?txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value+"&txtClientes="+document.getElementById("txtClientes").value+"&mostrar="+document.getElementById("criterio_mostrar").value+"&orden="+document.getElementById("criterio_orden").value+"&motivo="+document.getElementById("criterio_ordenar_por").value+"&cbciudad="+document.getElementById("cbciudad").value+"&txtUsuarios="+document.getElementById("txtUsuarios").value+"&estado="+document.getElementById("estado").value+"&areas="+document.getElementById("areas").value+"&tipoDoc="+document.getElementById("tipoDoc").value+"&autorizacion="+document.getElementById("autorizacion").value+"&idProducto="+document.getElementById("txtProductos").value+"&emision="+document.getElementById("emision").value;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
         }
       
    } 
        function numeroLineas(id){

$.ajax({
        url: 'reportes/rptCalculaLineas.php?txtComprobanteNumero='+id,
        type: 'post',
        data: 'id='+id,
        success: function(data){
            miUrl = "reportes/rptTicketFactura.php?txtComprobanteNumero="+id+"&numLineas="+data;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
        }
});
}
function autorizarGrupal(){
    let fecha_desde=  document.getElementById('txtFechaDesde').value;
    let fecha_hasta=  document.getElementById('txtFechaHasta').value;

    alertify.confirm('Autorizacion Grupal', 'Esta accion envia a autorizar las facturas entre las fechas ¡°'+fecha_desde+'¡± y ¡°'+fecha_hasta+'¡±?', 
       function(){ 
        $.ajax({
        url: 'sql/facturaVentaEducat.php',
        type: 'post',
        data: 'txtAccion=23&fecha_desde='+fecha_desde+'&fecha_hasta='+fecha_hasta,
        success: function(data){
            let json = JSON.parse(data);

            console.log(json);
            cantidadVentas= json['id_venta'].length;
            for (let i = 0; i < cantidadVentas ; i++) {
                console.log(json['id_venta'][i]);
         
                $.post("sql/autorizar.php", {id: json['id_venta'][i],tipo_comprobante: "1",correo:""}, function(data2){
                    let response2= data2.trim();

                    if     (response2==1){
                        alertify.success("Factura N¡ã"+json['numero_factura_venta'][i]+" Autorizada");
                    }else if (response2==2){
                        alertify.error("Factura N¡ã"+json['numero_factura_venta'][i]+"  no Autorizada");
                    }
                });
            } 
        }
        });
        }
       , function(){ alertify.error('Se cancelo')});
   }
</script>

</body>
</html>

