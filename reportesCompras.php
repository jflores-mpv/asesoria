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
    
    <script language="javascript" type="text/javascript" src="js/productos.js"></script>
    <script language="javascript" type="text/javascript" src="js/condominios.js"></script>
	<script language="javascript" type="text/javascript" src="js/index.js"></script>
    <!-- validaciones -->
    
    <script language="javascript" type="text/javascript" src="js/validaciones.js"></script>
    
		<link type="text/css" rel="stylesheet" href="css/calendario.css" media="screen"/>
	<script language="javascript" type="text/javascript" src="js/calendario.js"></script>
	<!-- reportes -->
    <script type="text/javascript">
        $(document).ready(function(){
            listarFacturasCompras(1);
			$('#txtProveedor').select2();
		});
    </script>
    <style>
        .select2-container .select2-selection--single {
            height: 36px;
        }
    </style>

    <!--END estilo para  el menu -->

</head>

<body >
    
<div class="wrapper d-flex align-items-stretch celeste">
    <nav id="sidebar" ><?php  {include("menusNiveles.php");      }   ?></nav>
    
        <div id="content"  class="p-0 m-0">
            <div class="row m-0"><?php  {include("header.php");      }   ?>  </div>
        
            <div class="row  m-0 ">  
                <div class="col-lg-12 ">
                    <div class="row  mb-2 text-info r-10  ">
                        <a class="col-lg-2 col-sm-2 text-decoration-none card p-1 m-1"  type="button"   id="btnNuevaFactura" name="btnNuevaFactura" onclick="location='nuevaFacturaCompra.php'" />
                            <div class="my-icon3 rounded "><i class="fa fa-shopping-cart mr-3 fa-2x"></i><span>Compras</span>  </div>
                        </a>
                 
                    </div>
                </div>
            </div> 
            <div class="row  m-0 ">
                <div class="col-lg-3">
                    <form name="frmReporteFacturas" id="frmReporteFacturas" method="post" action="javascript:  listarFacturasCompras(1);">
                        <div class="row">
                        
                        <div class="col-lg-8">
                            
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Buscar por proveedor:</label>
                                <select class="form-control p-5 w-25"  id="txtProveedor" name="txtProveedor" onChange="listarFacturasCompras(1)">
                                     <option data-subtext="todos" value="0">Todos</option>
                                    <?php
                                        $consulta ="Select * From proveedores where id_empresa='".$sesion_id_empresa."';";
                                        $resultado = mysqli_query($conexion , $consulta);
                                        $contador=0;
                                        
                                        while($misdatos = mysqli_fetch_assoc($resultado)){ $contador++; ?>
                                        <option data-subtext="<?php echo $misdatos["id_proveedor"]; ?>" value="<?php echo $misdatos["id_proveedor"]; ?>"><?php echo $misdatos["nombre"]; ?></option>
                                    <?php }?> 
                                </select>  
                            </div>
                            
                        </div>
                        
                        <div class="col-lg-4">
                            
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Numero: </label>
                                <input type="text" tabindex="1" id="txtnumfac"
                                class="form-control" required="required" name="txtnumfac" onKeyUp="listarFacturasCompras(1)" value="0"  />
                            </div>
                            
                        </div>
                        
                        <div class="col-lg-6">
                            
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Desde: </label>
                                <input type="text" tabindex="1" id="txtFechaDesde" value="<?php echo date('Y')."-"."01"."-"."01" ; ?>"
                                class="form-control" required="required" name="txtFechaDesde" onChange="listarFacturasCompras(1)" onClick="displayCalendar(txtFechaDesde,'yyyy-mm-dd',this)" />
                                <div id="mensajefecha_desde" ></div>
                            </div>
                            
                        </div>
                        
                        <div class="col-lg-6">
                            
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Hasta: </label>
                                <input type="text" tabindex="2" id="txtFechaHasta" value="<?php echo date("Y-m-d",time()); ?>" class="form-control"
                                required="required" name="txtFechaHasta"onChange="listarFacturasCompras(1)" onClick="displayCalendar(txtFechaHasta,'yyyy-mm-dd',this)"/>
                                <div id="mensajefecha_hasta" ></div>
                            </div>
                            
                        </div>
                        
                        <div class="col-lg-12">
                            
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Tipo Sustento: </label>
                                <select id="codSustento" name="codSustento" required="required" class="form-control required" onChange="listarFacturasCompras(1);llenarSelect();changeDocumento(this.value);">
                                <?php
                                      $sqlc="Select * From sustentosTributarios";
                                      $resultc=mysql_query($sqlc);
                                      while($rowc=mysql_fetch_array($resultc)){ ?>
                                           <option value="<?=$rowc['codigo']; ?>" data-tipo="<?=$rowc['comprobantes']; ?>"> <?=$rowc['codigo']."-".$rowc['detalle']; ?> </option>
                                      <?php             }             ?>
                                </select> 
                            </div>
                            
                        </div>
                     
                    <div class="col-lg-12">
                        <label class="font-weight-light">Tipo:</label>
                            <select id="txtTipoComprobante" name="txtTipoComprobante" required="required" class="form-control required" onChange="listarFacturasCompras(1);buscar_secuencial_compra(9,this.value);">
                            <?php
                              $sqlc="Select * From tipoDocumento";
                              $resultc=mysql_query($sqlc);
                              while($rowc=mysql_fetch_array($resultc)){ ?>
                                   <option id="<?=$rowc['id']; ?>" value="<?=$rowc['codigo']; ?>" style="display:block"> <?=$rowc['codigo']."-".$rowc['detalle']; ?> </option>
                                <?php             }             ?>
                              
                                                
                                  <option id="0" value="00" style="display:block">00- Orden de Compra</option>
                                    
                        </select> 
                        
    
                    </div>
                    
                      <div class="col-lg-12 mt-3">
                                    <label>Areas</label>
                                    <select name="areas" id="areas" class="form-select" onChange="listarFacturasCompras(1);listar_reporte_facturas(1)">
                                        <option value="A">Global</option>
                                        <option value="0">Todos</option>
                                   
                                    
                                         <option value="103" data-valor='103'>103</option>
                                        <?php
                                           
                                        $consultaCC = "SELECT * FROM centro_costo where empresa='" . $sesion_id_empresa . "'";
                                        $resultadoCC = mysqli_query($conexion, $consultaCC);
                                        while ($misdatosC = mysqli_fetch_assoc($resultadoCC)) {
                                        ?>
                                            <option value="<?php echo $misdatosC["id_centro_costo"]; ?>"  data-valor="<?php echo $misdatosC["descripcion"] ?>">
                                                <?php echo $misdatosC["descripcion"] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                               
                                    <div class="col-lg-12 mt-3">
                                    <label>Estado de factura</label>
                                    <select name="estado_factura" id="estado_factura" class="form-select" onChange="listarFacturasCompras(1);listar_reporte_facturas(1)">
                                      
                                        <option value="0">Todos</option>
                                        <option value="Pagadas">Pagadas</option>
                                        <option value="NoPagadas">No Pagadas</option>
                                    
                                    </select>
                                </div>
                            
                                
                    <div class="col-lg-4 mt-3">
                                <select name="criterio_mostrar" id="criterio_mostrar"class="form-select" onchange="listarFacturasCompras(1);">
                                  
                                    <option value="10">10</option>       
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="100000">Todas</option>
                                </select>
                    </div>            
                  <div class="col-lg-4 mt-3">            
                                <select name="criterio_ordenar_por" id="criterio_ordenar_por" class="form-select" onchange="listarFacturasCompras(1)">
                                    <option value="compras_numero_factura_compra ">Numero de compra</option>
                                    <option value=" proveedores_id_proveedor ">Proveedor</option>
                                      <option value=" compras_total ">Total</option>
                                       <option value=" compras_fecha_compra ">Fecha</option>
                                      
                                </select>
                    </div>            
                    <div class="col-lg-4 mt-3">
                                <select name="criterio_orden" id="criterio_orden" class="form-select" onchange="listarFacturasCompras(1);">
                                    <option value="desc">desc</option>
                                    <option value="asc">asc</option>
                                    
                                </select>
                    </div>
                       <div class="switch-field col-lg-12 text-center " style="margin-top:10px">
                        
                <input type="radio" id="mostrar_detalle1" name="mostrar_detalle" value="0" class="p-4" checked="" onchange="listarFacturasCompras(1);">
                <label for="mostrar_detalle1">General</label>
                
                <input type="radio" id="mostrar_detalle2" name="mostrar_detalle" value="1" class="p-4" onchange="listarFacturasCompras(1);">
                <label for="mostrar_detalle2">Detallado</label>
                
            </div>
            
                    <div class="col-lg-12 mt-3">
                        <input  type="button" tabindex="5" name="submit" value="Reporte de Compras" id="" class="btn btn-outline-warning w-100" 
                        align="right" onclick="javascript: pdfReporteComprasAreas();" />
                    </div>
                    <div class="col-lg-12 mt-3">
                        <input  type="button" tabindex="5" name="submit" value="Reporte Formas de Pago" id="" class="btn btn-outline-warning w-100" 
                        align="right" onclick="javascript: pdfReporteRetenciones();" />
                    </div>
                     <div class="col-lg-12 mt-3">
                        <input  type="button" tabindex="5" name="submit" value="Excel Reporte Formas de Pago" id="" class="btn btn-outline-warning w-100" 
                        align="right" onclick="javascript: excelReporteRetenciones();" />
                    
					</div>
					 <div class="col-lg-12 mt-3">
                        <input  type="button" tabindex="5" name="submit" value="Excel Reporte Compras" id="" class="btn btn-outline-warning w-100" 
                        align="right" onclick="javascript: excelReporteCompras();" />
                    
					</div>

                </div>

                    </form>
                    
                </div>
                <div class="col-lg-9">
                    <div id="div_listar_facturasCompras"></div>
                </div>
            </div>    
        </div>
</div>    
    
    
    
<script type="">  


    function pdfReporteComprasAreas(){
        let str = $('#frmReporteFacturas').serialize();
        let tipo = $('#areas').val();
        
        var dataAtributo = $('#areas option[value="' + tipo + '"]').data('valor');

        if(dataAtributo=='103'){
            miUrl = "reportes/rptServicioRentasInternas.php";
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
        return;
        }
        
        if (tipo=='A'){
            let str2 = $("#frmReporteFacturas").serialize();
       
        miUrl = "reportes/rptProductosCompras.php?"+str2;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
        
        //       miUrl = "reportes/rptCompras.php?txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value+"&txtProveedor="+document.getElementById("txtProveedor").value+"&mostrar="+document.getElementById("criterio_mostrar").value+"&orden="+document.getElementById("criterio_orden").value+"&motivo="+document.getElementById("criterio_ordenar_por").value+"&estado_factura="+document.getElementById("estado_factura").value;
        // window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
        }else{
            miUrl = "reportes/rptComprasAreas.php?"+str;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
        }
        
    } 
 function pdfReporteFactura_nuevo_formato(){
          var str = $("#frmReporteFacturas").serialize();
       
        miUrl = "reportes/rptProductosCompras.php?"+str;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    }
  function fichaCompra(id_cliente){

miUrl = "reportes/crearPdfCompras.php?id="+id_cliente;
// window.open(miUrl,'noimporta','width=600, height=500, scrollbars=NO, titlebar=no');
}

     function pdfSaldoInicial(id_venta, txtFechaDesde, txtFechaHasta){

        miUrl = "reportes/rptFacturas_detallado.php?id_venta="+id_venta+"&txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    } 

     function pdfReporteFacturaR(id_venta, txtFechaDesde, txtFechaHasta){

        miUrl = "reportes/rptVentas.php?id_venta="+id_venta+"&txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    } 

    function pdfReporteFacturaR1(id_compras, txtFechaDesde, txtFechaHasta,txtProveedor){
        
       
        miUrl = "reportes/rptCompras.php?id_compras="+id_compras+"&txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value+"&txtProveedor="+document.getElementById("txtProveedor").value+"&mostrar="+document.getElementById("criterio_mostrar").value+"&orden="+document.getElementById("criterio_orden").value+"&motivo="+document.getElementById("criterio_ordenar_por").value;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    } 
    
    
    function pdfReporteRetenciones(txtFechaDesde, txtFechaHasta){
        miUrl = "reportes/rptRetenciones2.php?&txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    } 
    
    function excelReporteRetenciones(txtFechaDesde, txtFechaHasta){
        miUrl = "reportes/excelRetenciones.php?&txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    } 
    
     function excelReporteCompras(){
          miUrl = "reportes/excelCompras.php?txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value+"&txtProveedor="+document.getElementById("txtProveedor").value+"&mostrar="+document.getElementById("criterio_mostrar").value+"&orden="+document.getElementById("criterio_orden").value+"&motivo="+document.getElementById("criterio_ordenar_por").value+"&estado_factura="+document.getElementById("estado_factura").value;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    } 

const llenarSelect=()=>{
    var codSustento = document.querySelector("#codSustento");
    var lista = codSustento.options[codSustento.selectedIndex].getAttribute('data-tipo');
    console.log(lista);
    let lista_n = lista.split(','); 
    
     var comprobante = document.querySelector("#txtTipoComprobante");
     

for (var i = 0; i < comprobante.length; i++) {
    
    var opt = comprobante[i];
    
    opt.style.display="none";

}

for (var i = 0; i < comprobante.length; i++) {
    
    var opt = comprobante[i];

console.log(lista_n.indexOf(opt.id));
if(lista_n.indexOf(opt.id)!=-1){
    console.log(opt.id);
    
      opt.style.display="block";
    // console.log(opt.value);
    // console.log("<====>",comprobante[i])
}
  
}

}
function pdfAbonoOrdenCompra(id_compra,id_proveedor ){
     miUrl = "reportes/rptCompraManualMini.php?id_compra="+id_compra+'&id_proveedor='+id_proveedor;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
}
</script>

</body>
</html>

