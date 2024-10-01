<?php
    require_once('ver_sesion.php');
//Start session
    session_start();
        
    //Include database connection details
    require_once('conexion.php');
    
    $sesion_id_institucion_educativa = $_SESSION["sesion_id_institucion_educativa"];
 // $nombre_institucion = mysql_query($conexion,"SELECT nombre from instituciones_educativas where id_institucion_educativa='$sesion_id_institucion_educativa'");
    $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_empresa_telefonos = $_SESSION["sesion_empresa_telefonos"];
    $sesion_empresa_direccion = $_SESSION["sesion_empresa_direccion"];
    $sesion_empresa_autorizacion = $_SESSION["sesion_empresa_autorizacion"];
    $sesion_empresa_ruc = $_SESSION["sesion_empresa_ruc"];
    $sesion_empresa_imagen = $_SESSION["sesion_empresa_imagen"];
    $sesion_empresa_ciudad = $_SESSION["sesion_empresa_ciudad"];
    $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
	$sesion_punto = $_SESSION['userpunto'];
	$sesion_id_est = $_SESSION['userest'];
    
    $sesion_id_usuario=$_SESSION["sesion_id_usuario"] ;
     
    
    $emision_ambiente= $_SESSION['emision_ambiente'] ;
    $emision_tipoFacturacion=$_SESSION['emision_tipoFacturacion'];
    $emision_tipoEmision=$_SESSION['emision_tipoEmision'] ;
    $sqlParametrosGenerales = "SELECT `id_parametros`, `limite_consumidor_final` FROM `parametros_generales`";
    
    $resultParametrosGenerales = mysql_query($sqlParametrosGenerales);
    while( $rowPG = mysql_fetch_array($resultParametrosGenerales) ){
            
           $limiteCF=floatval($rowPG['limite_consumidor_final']);
           
    }
   
   
?>

<html lang="es-ES">
    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Factura de venta</title>
    
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

    <!-- START ESTILOS Y CLASES PARA AJAX -->
    <script language="javascript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<!--<script language="javascript" type="text/javascript" src="js/jquery-1.4.1.min.js"></script>   
--> <script language="javascript" type="text/javascript" src="js/jquery.blockUI.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.validate.1.5.2.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js" ></script>
    <script language="javascript" type="text/javascript" src="js/index.js"></script>
    <script language="javascript" type="text/javascript" src="js/listadoFunciones.js"></script>
    <script type="text/javascript" src="js/validaciones.js"></script>
    <script type="text/javascript" src="js/condominios.js"></script>
    <script type="text/javascript" src="js/vendedores.js"></script>
    <script type="text/javascript" src="js/reportes.js"></script>
    <script type="text/javascript" src="js/ventas_educativo.js"></script>
    <script language="javascript" type="text/javascript" src="js/productos.js"></script>
    <script language="javascript" type="text/javascript" src="js/funcionesVentas.js"></script>
    <script language="javascript" type="text/javascript" src="js/choferes.js"></script>
    <!-- estilos para tablas -->
    <link href="css/listadoTablas.css" rel="stylesheet" type="text/css" />

    <!-- estilos para el calendario -->
    <link type="text/css" rel="stylesheet" href="css/calendario.css" media="screen"/>
    <script language="javascript" type="text/javascript" src="js/calendario.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/print-js"></script>

  <!--END estilo para  el menu -->
    <link rel="shortcut icon" href="images/logo.png">
<script>
const cargarConsumidorFinal=()=>{

$.ajax({
        url:'sql/facturaVentaEducat.php',
        type:'post',
        data:{txtAccion:16},
        success:function(res){
            try {
                let json = JSON.parse(res);
                if(json['numFilas']>0){
                    fill9(json['nombre']+'*'+json['apellido']+'*'+json['cedula']+'*'+json['telefono']+'*'+json['direccion']+'*'+json['id_cliente']+'*');
                }else{
                    // console.info('No existe consumidor final')
                }
            } catch (error) {
                console.info(error);
            }
        }
    });
}
      $(document).ready(function(){
           cargarConsumidorFinal();
           
        $("#cmbEst").click(function(){
          var codigo=$(this).val();
          $.ajax({
            url:'sql/establecimientos.php',
            type:'post',
            data:{codigo:codigo,txtAccion:3},
            success:function(res){
              $("#cmbEmi").html(res);
              numfac(cmbEst.value,cmbEmi.value,cmbTipoDocumentoFVC.value)
            }
          });
        });
      });

    function numfac(est,emi,tipo){
    $(document).ready(function(){
        $.ajax({
            url:'sql/establecimientos.php',
            type:'post',
            data:{emi:emi,txtAccion:5,est:est,tipo:tipo},
            success:function(res){
            document.getElementById("txtNumeroFacturaFVC").value = res;
            $("#txtNumeroFacturaFVC").html(res);
            }
        });
    });
    }
    function socio(emi){
    $(document).ready(function(){
        $.ajax({
            url:'sql/establecimientos.php',
            type:'post',
            data:{emi:emi,txtAccion:10},
            success:function(res){
                console.log("res",res);
                var valor = res;
                console.log("valor",valor);
                
                // $("#chofer_id").val(valor);
                $("#chofer_id option[value="+ valor +"]").attr("selected",true);
            }
        });
    });
    }


function limpiar_id(a){
    document.getElementById(a).value = "";
    
}
</script>
    
</head>



<script type="text/javascript">
    $(document).ready(function(){
        for(i=1; i<= 4; i++){
             AgregarFilasFacVentaEducativo('4');
        }
        muestra_iva_actual(4);
        
    });

    $(document).keyup(function(event)
    {
        if(event.which==27) //27 escape
        {
            //$('.suggestionsBox').hide();
            setTimeout("$('.suggestionsBox').hide();", 50); /* */
        }
    });
</script>

<script type="text/javascript">
 function validaDatosFacVenCondominios(accion, dml) {
    var tipoElement = document.getElementById("cmbTipoDocumentoFVC");
    console.log("cmbTipoDocumentoFVC", tipoElement);
    var tipo = tipoElement ? tipoElement.value : null;

    var direccionGuiaElement = document.getElementById("DirDestino");
    console.log("DirDestino", direccionGuiaElement);
    var direccionGuia = direccionGuiaElement ? direccionGuiaElement.value : null;

    var direccionGuiaOrigenElement = document.getElementById("DirOrigen");
    console.log("DirOrigen", direccionGuiaOrigenElement);
    var direccionGuiaOrigen = direccionGuiaOrigenElement ? direccionGuiaOrigenElement.value : null;

    var idtipoclienteElement = document.getElementById("idtipocliente");
    console.log("idtipocliente", idtipoclienteElement);
    var idtipocliente = idtipoclienteElement ? idtipoclienteElement.value : null;

    var choferGuiaElement = document.getElementById("chofer_id");
    console.log("chofer_id", choferGuiaElement);
    var choferGuia = choferGuiaElement ? choferGuiaElement.value : null;

    var facAnElement = document.getElementById("facAn");
    console.log("facAn", facAnElement);
    var facAn = facAnElement ? facAnElement.value : null;

    var textIdClienteElement = document.getElementById("textIdClienteFVC");
    console.log("textIdClienteFVC", textIdClienteElement);
    var textIdCliente = textIdClienteElement ? textIdClienteElement.value : null;

    var textCedulaElement = document.getElementById("txtCedulaFVC");
    console.log("txtCedulaFVC", textCedulaElement);
    var textCedula = textCedulaElement ? textCedulaElement.value : null;

    var txtTotalElement = document.getElementById("txtTotalFVC");
    console.log("txtTotalFVC", txtTotalElement);
    var txtTotal = txtTotalElement ? txtTotalElement.value : null;

    var txtContadorAsientosAgregadosElement = document.getElementById("txtContadorAsientosAgregadosFVC");
    console.log("txtContadorAsientosAgregadosFVC", txtContadorAsientosAgregadosElement);
    var txtContadorAsientosAgregados = txtContadorAsientosAgregadosElement ? txtContadorAsientosAgregadosElement.value : null;

    var textFechaElement = document.getElementById("textFechaFVC");
    console.log("textFechaFVC", textFechaElement);
    var textFecha = textFechaElement ? textFechaElement.value : null;

    var txtNumeroFacturaElement = document.getElementById("txtNumeroFacturaFVC");
    console.log("txtNumeroFacturaFVC", txtNumeroFacturaElement);
    var txtNumeroFactura = txtNumeroFacturaElement ? txtNumeroFacturaElement.value : null;

    var fleteInternacionalElement = document.getElementById("fleteInternacional");
    console.log("fleteInternacional", fleteInternacionalElement);
    var fleteInternacional = fleteInternacionalElement ? fleteInternacionalElement.value : null;

    var seguroInternacionalElement = document.getElementById("seguroInternacional");
    console.log("seguroInternacional", seguroInternacionalElement);
    var seguroInternacional = seguroInternacionalElement ? seguroInternacionalElement.value : null;

    var gastosAduanerosElement = document.getElementById("gastosAduaneros");
    console.log("gastosAduaneros", gastosAduanerosElement);
    var gastosAduaneros = gastosAduanerosElement ? gastosAduanerosElement.value : null;

    var gastosTransporteElement = document.getElementById("gastosTransporte");
    console.log("gastosTransporte", gastosTransporteElement);
    var gastosTransporte = gastosTransporteElement ? gastosTransporteElement.value : null;

    // Validaciones adicionales
    $('#txtAccionFVC').val(accion);
    let validadCantidadLleno = 0;

    for (let z = 1; z <= txtContadorAsientosAgregados; z++) {
        var txtCantidadSElement = document.getElementById("txtCantidadS" + z);
        var txtCodigoServicioElement = document.getElementById("txtCodigoServicio" + z);
        
        console.log("txtCantidadS" + z, txtCantidadSElement);
        console.log("txtCodigoServicio" + z, txtCodigoServicioElement);

        if (txtCantidadSElement && txtCantidadSElement.value == '') {
            if (txtCodigoServicioElement && txtCodigoServicioElement.value != '') {
                validadCantidadLleno = 1;
            }
            console.log("z", z)
            console.log("validadCantidadLleno", validadCantidadLleno)
        }
    }

    if (idtipocliente == "08") {
        console.log("idtipocliente", idtipocliente);
        if (fleteInternacional == "") {
            alert('Agregue valor de flete.');
            dml.elements['fleteInternacional'].focus();
            return false;
        }
        if (seguroInternacional == "") {
            alert('Agregue valor de seguroInternacional.');
            dml.elements['seguroInternacional'].focus();
            return false;
        }
        if (gastosAduaneros == "") {
            alert('Agregue valor de gastosAduaneros.');
            dml.elements['gastosAduaneros'].focus();
            return false;
        }
        if (gastosTransporte == "") {
            alert('Agregue valor de gastosTransporte.');
            dml.elements['gastosTransporte'].focus();
            return false;
        }
    }

    if (textIdCliente == "" || textIdCliente == 1) {
        alert('Agregue un cliente.');
        dml.elements['txtCedulaFVC'].focus();
        return false;
    } else if (txtContadorAsientosAgregados <= 0) {
        alert('No hay suficientes servicios para guardar.');
        dml.elements['txtCodigoServicio1'].focus();
        return false;
    } else if (txtTotal == "" || txtTotal <= 0) {
        alert('El total no puede ser nulo o cero.');
        dml.elements['txtCantidadS'].focus();
        return false;
    } else if (textFecha == "") {
        alert('La fecha no puede estar vacia.');
        dml.elements['textFechaFVC'].focus();
        return false;
    } else if (txtNumeroFactura == "") {
        alert('El numero de Registro no puede estar vacio.');
        dml.elements['txtNumeroFacturaFVC'].focus();
        return false;
    } else if (textCedula == "") {
        alert('El numero de Cedula/Ruc no puede estar vacio.');
        dml.elements['txtCedulaFVC'].focus();
        return false;
    } else if (tipo == "6") {
        if (direccionGuia == "") {
            alert('La direccion no puede estar vacia.');
            dml.elements['DirDestino'].focus();
            return false;
        } else if (direccionGuiaOrigen == "") {
            alert('La direccion de origen no puede estar vacia.');
            dml.elements['DirOrigen'].focus();
            return false;
        } else if (choferGuia == "0") {
            alert('Debe seleccionar chófer.');
            dml.elements['chofer_id'].focus();
            return false;
        }
    } else if (tipo == "4") {
        if (facAn == "0") {
            alert('Debe seleccionar factura relacionada.');
            dml.elements['facAn'].focus();
            return false;
        }
    } else if (validadCantidadLleno == 1) {
        alert('Las cantidades de los productos no pueden estar vacios.');
        dml.elements['txtCedulaFVC'].focus();
        return false;
    }
    return true;
}





      function VerComprobante() {
            var eldiv = document.getElementById('NotaC');
            var eldiv1 = document.getElementById('GuiaR');
            var eldiv2 = document.getElementById('NotaD');
            var eldiv3 = document.getElementById('NotaE');
      
            eldiv.style.display = 'none';
            eldiv.style.display = 'none';


            if ($("#cmbTipoDocumentoFVC").val() == "1") {
                eldiv.style.display = 'none';
                eldiv1.style.display = 'none';
                eldiv2.style.display = 'none';
                eldiv3.style.display = 'block';
            }

            if ($("#cmbTipoDocumentoFVC").val() == "4") {
                eldiv1.style.display = 'none';
                eldiv.style.display = 'block';
                eldiv2.style.display = 'block';
                eldiv3.style.display = 'none';
            }
            
            if ($("#cmbTipoDocumentoFVC").val() == "6") {
                eldiv.style.display = 'none';
                eldiv1.style.display = 'block';
                eldiv2.style.display = 'block';
                eldiv3.style.display = 'none';
            }

    }
</script>

<body onLoad="numfac(cmbEst.value,cmbEmi.value,cmbTipoDocumentoFVC.value);socio(cmbEmi.value)">
    
    
    <div class="wrapper d-flex align-items-stretch celeste">
    
    <nav id="sidebar" ><?php  {include("menusNiveles.php");      }   ?></nav>
    
    <div id="content"  class="p-0 m-0">
    
    <div class="row m-0"><?php  {include("header.php");      }   ?>  </div>
 
    
    
 <div class="row  m-0 ">  
     <input name="factura_xml" id="factura_xml" type="hidden" value="0"  />
        <form id="frmFacturaVentaCondominios" name="frmFacturaVentaCondominios" method="post" action="">
        <div id="mensajeFacturaVentaCondominios"></div>
         <input name="limiteCF" id="limiteCF" type="hidden" value="<?php echo  $limiteCF ?>" size="5"  />
         <input name="sesion_id_empresa" id="sesion_id_empresa" type="hidden" value="<?php echo  $sesion_id_empresa ?>" size="5"  />
         
        <!-- <select style="width: 50%"  name="cmbCuentaContable" id="cmbCuentaContable" ></select> -->

    <?php
        try {
            //consulta para sacar los datos de la tabla vendedores
            $sqlu="SELECT * FROM vendedores WHERE id_empresa='".$sesion_id_empresa."' and estado='Activo';";
            $resultu=mysql_query($sqlu);
            ?><select id="cmbIdVendedorFVC" class="form-control" name="cmbIdVendedorFVC" hidden=""><?php
            while($rowu=mysql_fetch_array($resultu))//permite ir de fila en fila de la tabla
            {
                 ?><option value="<?php echo $rowu['id_vendedor']; ?>"><?php echo $rowu['nombre']." - ".$rowu['codigo']; ?></option><?php
            }
            ?></select> <?php
        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }

        ?>
         
    <!--<input name="txtNumeroFacturaFVC" id="txtNumeroFacturaFVC" type="hidden" class="form-control" required  value="<?php echo $numero_factura_venta;?>" onclick="lookup_factura_educ(this.value,'', 8);" onKeyUp="lookup_factura_educ(this.value,'', 8);" /> -->
    <input name="textIdClienteFVC" id="textIdClienteFVC" type="hidden" value="" size="5" readonly="readonly" />
 <input name="idFactura" id="idFactura" type="hidden" value="" size="5" readonly="readonly" />
    <div class="container-fluid">
  <div class="row">
    <div class="col-12 px-4">

      
      
      <div class="row mb-2 text-info rounded">
            
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 p-1">
          <a href="facturaVentaEducativo.php" class="text-decoration-none card p-1">
            <div class="my-icon3"><i class="fa fa-file mr-3"></i><span>Nueva Venta</span></div>
          </a>
        </div>
        <div class="col-6 col-sm-4 col-md-3 col-lg-2 p-1">
          <a href="reportesVentas.php" class="text-decoration-none card p-1">
            <div class="my-icon3 rounded"><i class="fa fa-file-text-o mr-3"></i><span>Reportes</span></div>
          </a>
        </div>
        <div class="col-6 col-sm-4 col-md-3 col-lg-2 p-1">
          <a href="#" class="text-decoration-none card p-1" onclick="javascript: anular_venta_anular()">
            <div class="my-icon3"><i class="fa fa-trash-o mr-3"></i><span>Anular Factura</span></div>
          </a>
        </div>
            
            
             <div class="col-6 col-sm-4 col-md-3 col-lg-2 p-1 d-none d-lg-block">
                  <a href="#" class="text-decoration-none card p-1" onclick="javascript: anular_venta(txtNumeroFacturaFVC,'8',cmbTipoDocumentoFVC.value,cmbEst.value,cmbEmi.value)">
                    <div class="my-icon3"><i class="fa fa-trash-o mr-3"></i><span>Borrar</span></div>
                  </a>
            </div>
            
             <div class="col-6 col-sm-4 col-md-3 col-lg-2 p-1 d-none d-lg-block">
                <a class="text-decoration-none card p-1 " type="button" tabindex="5" name="submit" value="Enlaces" id="" onclick="href: forma_pago()">
                    <div class="my-icon3"><i class="fa fa-link mr-3"></i><span>Enlaces</span></div>
                </a>
            </div>
            
            
             <div class="col-6 col-sm-4 col-md-3 col-lg-2 p-1 d-none d-lg-block">
                <a class="text-decoration-none card p-1 " type="button" tabindex="5" name="submit" value="Cliente" id="" onClick="href:residentes()">
                    <div class="my-icon3"><i class="fa fa-user-plus mr-3"></i><span>Cliente</span></div>
                </a>
            </div>
             <div class="col-6 col-sm-4 col-md-3 col-lg-2 p-1 d-none d-lg-block">
                <a class="text-decoration-none card p-1 " type="button" tabindex="5" name="submit" value="Cliente" id="" onclick="javascript:nuevo_producto()">
                    <div class="my-icon3"><i class="fa fa-user-plus mr-3"></i><span>Nuevo Producto</span></div>
                </a>
            </div>
             <div class="col-6 col-sm-4 col-md-3 col-lg-2 p-1 d-none d-lg-block">
                <a class="text-decoration-none card p-1 " type="button" tabindex="5" name="submit" value="Cliente" id="" onclick="location='transportistas.php'">
                    <div class="my-icon3"><i class="fa fa-user-plus mr-3"></i><span>Transportistas</span></div>
                </a>
            </div>
            

                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 p-1 d-none d-lg-block">
                <a class="text-decoration-none card p-1 " type="button" tabindex="5" name="submit" value="Cliente" id="" onclick="location='cierreCaja.php'">
                    <div class="my-icon3"><i class="fa fa-user-plus mr-3"></i><span>Cierre de Caja</span></div>
                </a>
            </div>
              
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 p-1 d-none d-lg-block">
                <a class="text-decoration-none card p-1 " type="button" tabindex="5" name="submit" value="Vendedores" id="" onclick="location='vendedores.php'">
                    <div class="my-icon3"><i class="fa fa-user-plus mr-3"></i><span>Vendedores</span></div>
                </a>
            </div>
               
            
        </div>
      
      
</div>
 
  <div class="row py-1 bg-white rounded ">


        <div class="input-group mb-3">
               
                                        
             <input type="hidden" name="dominioOculto"  id="dominioOculto" value="" />
           <input type="hidden" name="txtAccionFVC"  id="txtAccionFVC" value="0" />
           
           
            <select name="cmbTipoDocumentoFVC" id="cmbTipoDocumentoFVC" class="form-select " 
            onChange="VerComprobante();numfac(cmbEst.value,cmbEmi.value,cmbTipoDocumentoFVC.value);activarBoton();mostrar_grilla_reembolso();">
                                    <option value="1">Facturas</option>
                                        <option value="100">Nota de venta</option>
                                    
                                    <option value="4">Nota de crédito</option>
                                    <option value="6">Guía de remisión</option>
                                    <option value="5">Proforma</option>
                                    <option value="41">Reembolso</option>
            </select> 
            
      
                                   
        <select tabindex="1" id="cmbEst" class="form-select" required="required" name="cmbEst">
            <?php
            $est2 = "SELECT * FROM establecimientos WHERE id = '".$sesion_id_est."';";
            $resultest2 = mysql_query($est2);
            while ($rowu = mysql_fetch_array($resultest2)) {
                echo "<option value='".$rowu['id']."'>".$rowu['codigo']."</option>";
            }
        
            // Obtener todos los establecimientos de la empresa excepto aquellos que ya están presentes en la base de datos
            $sqlu = "SELECT * FROM establecimientos WHERE id_empresa = '".$sesion_id_empresa."' AND id != '".$sesion_id_est."';";
            $resultu = mysql_query($sqlu);
            while ($rowu = mysql_fetch_array($resultu)) {
                echo "<option value='".$rowu['id']."'>".$rowu['codigo']."</option>";
            }
            ?>
        </select>
        
        <select id="cmbEmi" class="form-select" required="required" name="cmbEmi" onChange="numfac(cmbEst.value,cmbEmi.value,cmbTipoDocumentoFVC.value)">
            <?php
            $sqlEmi = "SELECT * FROM emision WHERE id = '".$sesion_punto."';";
            $sqlEmi1 = mysql_query($sqlEmi);
            while ($rowEmi = mysql_fetch_array($sqlEmi1)) {
                echo "<option value='".$sesion_punto."'>".$rowEmi['codigo']."</option>";
            }
            ?>
        </select>

            
                                    
        <input name="txtNumeroFacturaFVC" id="txtNumeroFacturaFVC" type="text" class="form-control text-center " required  
        onclick="lookup_factura_educ(this.value,'', 8,cmbEst.value,cmbEmi.value,cmbTipoDocumentoFVC.value);" 
        onKeyUp="lookup_factura_educ(this.value,'', 8,cmbEst.value,cmbEmi.value,cmbTipoDocumentoFVC.value);" >
  
        <span class="input-group-text">RUC:</span>
        <input type="text" id="txtCedulaFVC" class="form-control" name="txtCedulaFVC" placeholder="Buscar" required="required" onClick="lookup9(this.value, 6);" onKeyUp="lookup9(this.value, 6);" />
        
        <span class="input-group-text">Cliente:</span>
        <input type="text" id="txtNombreFVC" class="form-control" name="txtNombreFVC" placeholder="Cliente" required="required" />
        

        
  <input type="hidden" id="idClientesSelecionados" name="idClientesSelecionados" value="">
        
        <input type="hidden" id="idtipocliente" name="idtipocliente" value="">
        
        
        
        </div>
   
        <div class="input-group mb-3" >
                
                    <span  style="display: none" id="labelClientes" style="position: relative;width:100%">
                        <div class="suggestionsBox" id="suggestions9" style="display: none">
                            <div class="suggestionList" id="autoSuggestionsList9" >&nbsp; </div> 
                        </div>
                    </span>
               
                
                <span class="input-group-text">Observación:</span>
                <textarea  class="form-control" type="text"   id="txtDescripcionFVC" name="txtDescripcionFVC" rows="1"></textarea>

                <span class="input-group-text">Teléfono:</span>
                <input  class="form-control" type="text" name="txtTelefonoFVC" id="txtTelefonoFVC" >    
                    <span class="input-group-text">Direccion</span>
                <input  class="form-control" type="text" name="txtDireccionFVC" id="txtDireccionFVC">
                    
                                </div>
   
        <div class="input-group mb-3" >
                <span class="input-group-text">Fecha Emisión:</span>
                <input  class="form-control" type="datetime" name="textFechaFVC" id="textFechaFVC" value="<?php echo date("Y-m-d H:i:s")?>" onClick="displayCalendar(textFechaFVC,'yyyy-mm-dd hh:ii:00',this,this)" >

       
                
                
                <span class="input-group-text">Chofer:</span>
                
                <select  name="chofer_id" id="chofer_id" class="form-control" >
                    
                    <option value="0">Ninguno</option>
                    <?php
                    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
                    $sqltrans="Select * From transportista where id_empresa='".$sesion_id_empresa."';";
                    $sqltrans1=mysql_query($sqltrans);
                    while($rowtrans=mysql_fetch_array($sqltrans1))
                         {          
                    ?>
                          <option value="<?=$rowtrans['Id']; ?>"><?=$rowtrans['Nombres']; ?></option>
                    <?php } ?>
            	</select>
                
               
                  <span class="input-group-text">Vendedor:</span>
                
                <select  name="vendedor_id" id="vendedor_id" class="form-control" >
                    
                    <option value="0">Ninguno</option>
                    <?php
                    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
                    if($sesion_id_empresa == 116  || $sesion_id_empresa ==1827){
                         $sqltrans="Select * From vendedores where id_empresa='".$sesion_id_empresa."' AND estado='Activo' AND (tipo_vendedor='ambos' or tipo_vendedor='vendedor');";
                    }else{
                         $sqltrans="Select * From vendedores where id_empresa='".$sesion_id_empresa."' AND estado='Activo';";
                    }
                    $sqltrans1=mysql_query($sqltrans);
                    while($rowtrans=mysql_fetch_array($sqltrans1))
                         {          
                    ?>
                          <option value="<?=$rowtrans['id_vendedor']; ?>"><?=$rowtrans['nombre'].' '.$rowtrans['apellidos']; ?></option>
                    <?php } ?>
            	</select>
              
        </div>
    
    </div> 
    
   
    
    
    <div class="row text-left bg-white p-1" style="display:none" id="GuiaR">
            <div class="row">
                <div class="col-lg-2">
                      <label for="FechaInicio" class="form-label">Fecha Inicio.:</label>
                      <input name="FechaInicio" style="width: 100%" id="FechaInicio" type="text" class="form-control"  value="<?php echo date("Y-m-d")?>"/>
                </div>
                <div class="col-lg-2">
                    <label for="MotivoTraslado" class="form-label">Motivo Traslado:</label>
                    <?php 
        				$a=array("Venta"=>1,"Compra"=>2,"Transformacion"=>3,"Consignacion"=>4,"Traslado entre Establecimiento Misma Empresa"=>5,"Traslado por emisor itinerante de comprobante de venta"=>6,"Devolucion"=>7,"Importacion"=>8,"Exportacion"=>9,"Otros"=>10);
        				echo "<select   class='form-select input-sm' name=\"MotivoTraslado\" id=\"MotivoTraslado\"  >\n";
        				foreach($a as $k=>$v)
        				echo "<option value=\"$v\" >$k</option>";
        			    echo "</select>";
        			?>
                </div>
                <div class="col-lg-2">
                    <label for="FechaFin" class="form-label">Fecha fin:</label>
                    <input name="FechaFin" style="width: 100%" id="FechaFin" type="text" class="form-control" value="<?php echo date("Y-m-d ")?>" />
                </div>
                <div class="col-lg-6">
                    <label for="DirDestino" class="form-label">Destino:</label>
                    <input name="DirDestino" style="width: 100%" id="DirDestino" type="text" class="form-control" />
                </div>
                <div class="col-lg-6">
                    <label for="DirOrigen" class="form-label">Origen:</label>
                    <input name="DirOrigen" style="width: 100%" id="DirOrigen" type="text" class="form-control" />
                </div>
            </div>    
        </div>
        
    <div class="row text-left celeste p-1" style="display:none" id="NotaC">
        <div class="row">
            <div class="col-lg-5">
              <label for="MotivoNota" class="form-label">Motivo:</label>
              <input name="MotivoNota" style="width: 100%" id="MotivoNota" type="text" class="form-control" />
            </div>
        </div>
    </div>       
    
    <div class="row celeste p-1" style="display:none" id="NotaD">
               <div class="col-lg-3">
                
                <label for="MotivoNota" class="form-label">Factura a afectar:</label>
                     <input name="facAn"  id="facAn" type="hidden" 	  />
                <input type="search" name="selectfacAn" id="selectfacAn" value="" placeholder="Buscar por numero de factura" title="Ingrese numero de factura" class="form-control" onclick="cargarListaFacturas(this.value);" onkeyup="cargarListaFacturas(this.value);" onblur="cargarListaFacturas(this.value);" autocomplete="off">
                <div class="suggestionsBox" id="suggestionsFacAn" style="display: none;"> 
                    <div class="suggestionList" id="autoSuggestionsFacAn">
                    </div>
                </div>

               
            </div>
    </div>
    
     <div class="row celeste p-1" style="display:none" id="NotaE">
         
        <div class="col-lg-3">
                
            <label for="MotivoNota" class="form-label">Proforma:</label>
                 
            <select tabindex="1" id="facAnP" class="form-select " name="facAnP" required="required" onclick="lookup_notaCredito_educ(this.value,'', 4,cmbEst.value,cmbEmi.value);" onKeyUp="lookup_notaCredito_educ(this.value,'', 4,cmbEst.value,cmbEmi.value);" >
                     <option value="0">0</option> 
                    <?php
    
    			    $sqlc = "Select *,establecimientos.codigo as est,emision.codigo as emi From ventas,emision,establecimientos 
                                                    where ventas.id_empresa='" . $sesion_id_empresa . "' and 
                                                    emision.id=ventas.codigo_lug and establecimientos.id=ventas.codigo_pun 
                                                    and tipo_documento='5' and estado='Activo';";
    			    
                    $resultc=mysql_query($sqlc);
                        while($rowc=mysql_fetch_array($resultc))//permite ir de fila en fila de la tabla
                         { ?> 
                        <option value="<?= $rowc['id_venta']; ?>"><?php echo $rowc['est'] . "-" . $rowc['emi'] . "-" . $rowc['numero_factura_venta'] ?></option> <?php } ?>
            </select>
        </div>
        
    </div>
    
    
    
    
        
        <div class="row mt-3 bg-white " id="tblFacVentaCondominios" >
          
<div class="col-lg-4 mb-1 mt-4">
     <input class="form-control  text-center bg-white" type="text" name="codigoBarras" id="codigoBarras"   value="Busque aquí por codigo de barras" onkeyup="handleKeyPress(this.value);" onClick='borrarTextoInput()' />
 </div>
            
         
              
           
    <div class="col-lg-12 ">
           
            <div id="div_reembolso" style="display:none">

            <div class="form-group">
                <input name="txtContadorFilasReembolso"  id="txtContadorFilasReembolso" type="hidden" />
        
           
               <div class="input-group mt-1"> 
                    <a style='width:5% '  href="javascript: AgregarFilasReembolso();" title="Agregar nueva fila" class="btn btn-outline-secondary celeste">
                           <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>
                     
                    <div class=" border  py-2 form-control celeste " style="width:5%" >C&eacute;dula/R.U.C.</div>
                    <div class=" border  py-2 form-control celeste" style="width:5% ">C&oacute;digo Pais</div>
                      
                    <div style="width:10% " class=" border  py-2 form-control celeste">Tipo Proveedor</div>
                    <div style="width:10% " class=" border  py-2 form-control celeste">Tipo Documento</div>
                    <div style="width:7% " class=" border  py-2 form-control celeste">Establecimiento</div>
                    <div style="width:5% " class=" border  py-2 form-control celeste">Emisi&oacute;n</div>
                    <div style="width:5% " class=" border  py-2 form-control celeste">Secuencial</div>
                    <div style="width:5% " class=" border  py-2 form-control celeste">Fecha de Emisi&oacute;n</div>
                    <div style="width:5% " class=" border  py-2 form-control celeste">N&uacute;mero de Autorizaci&oacute;n</div>
                  
               </div>
            </div>
            
                <div  id="tblBodyReembolso" ></div>

            </div>
    </div>
    
            <div class="form-group pt-5">
                
                <div class="input-group"> 
                    <a href="javascript: AgregarFilasFacVentaEducativo(4);" title="Agregar nueva fila" class='btn btn-outline-secondary celeste'>
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>
                    
                    <div class='border  py-2 form-control celeste '>C&oacute;digo</div>
                    <div class='border  py-2 form-control celeste' style='width:25% '>Descripci&oacuten</div><a class='btn btn-outline-secondary'><i class='fas fa-plus'></i></a>
                    <div class='border  py-2 form-control celeste'>&Aacute;rea</div>
                    <div class='border  py-2 form-control celeste'>Cant</div>
                    <div class='border  py-2 form-control celeste'>Desc %</div>
                    <div class='border  py-2 form-control celeste'>Precio</div>
                    <div class='border  py-2 form-control celeste'>V.Unitario</div>
                    <div class='border  py-2 form-control celeste'>V.Descuento</div>
                    <div class='border  py-2 form-control celeste'>V.Total</div>
         
                </div>
            </div>
            
            <div  id="tblBodyFacVentaCondominios" ></div>
            
            <div class="col-lg-8  mt-5">
                
                <div class="col-lg-6  mt-5" style="display: inline-block;">
                    
              
                         
                    <input id="guardarEfectivo" type="button" tabindex="5" name="submit" value=" COBRO SOLO EN EFECTIVO" id="" class="btn btn-success btn-lg btn-block"  onclick="guardar_cobro_solo_efectivo();"  />
                    
                </div>
               
                <div class="col-lg-4 mt-5" style="display: inline-block;">     
                
                <div id="normal" >
                    <input type="button" tabindex="5" name="submit" value="GRABAR FACTURA" id="" class="btn btn-success btn-lg btn-block"  onclick="guardarFacturaVentaC();"  />
                </div>
                <div id="modificada" style="display:none">
                   <input type="button" tabindex="5" name="submit" value="GUARDAR FACTURA MODIFICADA" id="" class="btn btn-success btn-lg btn-block"  onclick="guardarFacturaVentaC();"  />
                </div>
                <div id="facturaxml" style="display:none">
                   <input type="button" tabindex="5" name="submit" value="GUARDAR FORMAS DE PAGO XML" id="" class="btn btn-success btn-lg btn-block"  onclick="guardarFacturaVentaC();"  />
                </div>
                
            </div>

        </div>
    
            
            
            <div class="col-lg-4 ">
                <input type="hidden" id="txtContadorAsientosAgregadosFVC" value="0" readonly="readonly" class="" name="txtContadorAsientosAgregadosFVC" />
                <input type="hidden" id="txtContadorFilasFVC" value="0" readonly="readonly" class="" name="txtContadorFilasFVC" />
                <input type="hidden" id="autorizado" value="0" readonly  name="autorizado" />
                <input type="hidden" id="txtContadorDetalleFilasFVC" value="1" readonly  name="txtContadorDetalleFilasFVC" />
                <div class="row align-items-center justify-content-center mb-2">
                
                    
                 <?php
                 $sqlImpuestos = "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='".$sesion_id_empresa."'  ";
                 $resultImpuestos = mysql_query($sqlImpuestos);
                 $listaImpuestos = array();
                 while( $rowImp = mysql_fetch_array($resultImpuestos) ){
                    $listaImpuestos[]= $rowImp['id_iva'];
                    ?>
                     <div class="row align-items-center justify-content-center mb-2">
                        <div class="col-lg-6 text-right">
                         <label>Sub total <?php echo $rowImp['iva'].' %' ?></label>
                        </div>
                        <div class="col-lg-6 ">
                        <input class="form-control  text-center bg-white" type="text" 
                        name="txtSubtotal<?php echo $rowImp['id_iva'] ?>" id="txtSubtotal<?php echo $rowImp['id_iva'] ?>" 
                        data-iva="<?php echo $rowImp['iva'] ?>" data-idiva="<?php echo $rowImp['id_iva'] ?>" readonly="readonly"  value="0.00" />
                        </div>
                    </div>  

                <?php
                 }
                 $listaImpuestosJson = json_encode($listaImpuestos);
                ?>
                <div id="json_impuestos" style="display: none;"><?php echo $listaImpuestosJson; ?></div>
                <div class="row align-items-center justify-content-center mb-2">
                 <div class="col-lg-6 text-right">
                    <label>Sub total</label>
                </div>
                <div class="col-lg-6 ">
                    <input class="form-control text-center bg-white" type="text" name="txtSubtotalFVC" id="txtSubtotalFVC" readonly="readonly"  value="0.00" />
                </div>
                 </div>  
                <div class="row align-items-center justify-content-center mb-2">
                <div class="col-lg-6 text-right">
                    <label>Descuento</label>
                </div>
                <div class="col-lg-2 ">
                    <input class="form-control  text-center bg-white" type="text" name="txtDescuentoFVC" id="txtDescuentoFVC"   value="0" onKeyuP="calculoDescuento();"/>
                </div>
                <div class="col-lg-1">
                    <label> % </label>
                </div>
                <div class="col-lg-3 ">
                    <input class="form-control text-center bg-light" readonly type="text" name="txtDescuentoFVCNum" id="txtDescuentoFVCNum"   value="0" onKeyuP="calculoDescuento();"/>
                </div>
                 </div>  
                <div class="row align-items-center justify-content-center mb-2">
                 <div class="col-lg-6 text-right">
                    <label>Sub total</label>
                </div>
                <div class="col-lg-6 ">
                    <input class="form-control text-center bg-white" type="text" name="txtSubtotalFVC2" id="txtSubtotalFVC2" readonly="readonly"  value="0.00" />
                </div>
                 </div>  
                <div class="row align-items-center justify-content-center mb-2">
                <div class="col-lg-6 text-right">
                    <label>IVA</label><input type="hidden" name="txtIdIvaFVC" id="txtIdIvaFVC" value="" /><label id="ivaActual" style="display: none;" ></label>
                </div>
                <div class="col-lg-6 "><input type="text" class="form-control  text-center bg-white" name="txtTotalIvaFVC" id="txtTotalIvaFVC" readonly="readonly"  value="0.00" /></div>
                 </div>  
                 
            <?php        
    $propinaIrbp="Propina";
            
               
            ?>
                
                
                 
                <div class="row align-items-center justify-content-center mb-2">
                <div class="col-lg-6 text-right">
                    <label><?php echo $propinaIrbp?></label>
                </div>
                <div class="col-lg-6 ">
                    <input class="form-control  text-center bg-white" type="text" name="txtPropinaFVC" id="txtPropinaFVC"   value="0.00" onKeyuP="calculoTotal_Ventas();"/>
                </div>
                </div>   
                <div class="row align-items-center justify-content-center mb-2">
                <div class="col-lg-6 text-right"><label>Total</label></div>
                <div class="col-lg-6  ">
                    <input type="text"  class="form-control  text-center bg-white"  name="txtTotalFVC" id="txtTotalFVC" readonly="readonly"  value="0.00" />
                </div>
            </div>  
    </div>
</div>  
</div>

</form>     


</div>


    <div id="listar_facturas" ></div>
</form>


    <div id="div_oculto_anular" style="display: none;"></div>
    <div id="div_oculto" style="display: none;"></div>
</div>         

</body>
    <script src="librerias/bootstrap/js/main.js"></script>      
    
<script type="">  
function pdfVentas_id(){
    let id_factura_venta = document.getElementById('idFactura').value;
    accion = 99;
    $.ajax
		({
            url: 'sql/facturaVentaEducat.php',
            type: 'post',
            data: "txtAccion="+accion+'&id_factura_venta='+id_factura_venta,
            beforeSend: function(){
            },
            success: function(data){
                 let response = JSON.parse(data);
               
                 let formato=response.formato;
                let idVenta=response.id;
                 let documento=response.tipo_documento;
                

            if(formato==1){
                    if(documento==1||documento==41||documento==100){
                        formatoF = 'rptFacturas_detallado.php';
                    }else if(documento==5){
                        formatoF = 'rptProforma_detallado.php';
                    }
            }
            if(formato==2){
                if(documento==1||documento==41||documento==100){
                       formatoF = 'rptFacturas_detalladoMini.php' ;
                }else if(documento==5){
                    formatoF = 'rptProforma_detallado.php';
                }
            }
            if(formato==3){
                    if(documento==1||documento==41||documento==100){
                       formatoF = 'rptFacturas_detalladoA5.php' ;
                }else if(documento==5){
                   formatoF = 'rptProforma_detallado.php';
                }
                    
                    
            }
    

 miUrl = "reportes/"+formatoF+"?txtComprobanteNumero="+idVenta;
 window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
                
  
                        
                        
                        
            }
        });
}

function actualizarPrecioUnitario(numfila){
    
    let precioFinal = document.getElementById('txtValorTotalConIvaS'+numfila).value;

    if(precioFinal!=0 && precioFinal.trim()!=''){
        let iva = document.getElementById('txtIvaS'+numfila).value;
        let precioDescuento = precioFinal / ( 1 + (iva/100) );
        let calculoiva = precioDescuento * (iva/100);
        document.getElementById('txtValorUnitarioShidden'+numfila).value = precioDescuento.toFixed(6);
        document.getElementById('txtValorUnitarioS'+numfila).value =  precioDescuento.toFixed(4) ;
        document.getElementById('txtCalculoIvaS'+numfila).value =  calculoiva ;
        
    }else{
        let precioAnterior = document.getElementById('precioOrignal'+numfila).value;
        let iva = document.getElementById('txtIvaS'+numfila).value;
        document.getElementById('txtValorUnitarioShidden'+numfila).value = precioAnterior.toFixed(6);
        document.getElementById('txtValorUnitarioS'+numfila).value =  precioAnterior.toFixed(4) ;
        document.getElementById('txtCalculoIvaS'+numfila).value =  precioAnterior*(iva/100) ;
    }

}

function calcularCambioEfectivo (){
    
        let entregado = parseFloat(document.getElementById('txtEntregadoEfectivo').value);
        let total = parseFloat(document.getElementById('txtTotalFVC').value);
        let estilos = document.getElementById('txtCambioEfectivo').style;
        let resultado=0;
        if(entregado>0){
            resultado=entregado - total;
            document.getElementById('txtCambioEfectivo').value= resultado.toFixed(2);
            if(resultado>=0){
                 estilos.color = "green";
               
            }else{
               estilos.color = "#FF0000";
            }
        }
       
}
</script>

      

</html>
