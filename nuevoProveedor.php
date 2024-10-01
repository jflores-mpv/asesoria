<?php
	//require_once('ver_sesion.php');
 include'conexion2.php';
 require_once('conexion.php');
	//Start session
	session_start();
		
	//Include database connection details
	//require_once('conexion.php');
	    $id_empresa_cookies = $_COOKIE["id_empresa_cookie"];
    $id_periodo_contable_cookies = $_COOKIE["id_periodo_contable_cookie"];
    $cookie_tipo = $_COOKIE['tipo_cookie'];

    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION['sesion_id_periodo_contable'];
    $sesion_empresa_nombre = $_SESSION['sesion_empresa_nombre'];
    $sesion_tipo = $_SESSION["sesion_tipo"];
    $sesion_nombre = $_SESSION["sesion_nombre"];
    $sesion_apellido = $_SESSION["sesion_apellido"];
    $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
    $id_proveedor= isset($_GET['id_proveedor'])?$_GET['id_proveedor']:-1;
	
	if($id_proveedor>=0){
	    $sql="";
	    
	     $sql="SELECT `id_proveedor`,nombre, cuentaBanco,`rbCaracterIdentificacion`, `parteRel`, `nombre_comercial`, `nombre`, `nombreProveedor`, `apellidoProveedor`, `direccion`, `ruc`, `telefono`, `movil`, `fax`, `email`, `web`, `observaciones`, proveedores.`id_ciudad`, `id_plan_cuenta`, `autorizacion_sri`, `fecha_vencimiento`, `id_empresa`, `id_tipo_proveedor`, `tipo_pago`, `pasaporte`, `otro`, `tipo_sustento`, `tipo_comprobante`, `enlace_retencion_fuente`, `enlace_retencion_iva`, `estado_Proveedor`, `diasCredito`, `tipoContribuyente`, `banco`, `cuentaBanco`, `tipoCuenta`, `pagoResidente`, `tipoRegimen`, `paisPago`, `pagoParaiso`, `pagoPreferente`, `paisResidencia`, `retencion_fuente_servicios`, `retencion_fuente_activos`,ciudades.id_provincia,retencion_fuente_proveeduria	 FROM `proveedores` INNER JOIN ciudades ON ciudades.id_ciudad= proveedores.id_ciudad  WHERE `id_proveedor`='$id_proveedor'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
        { 
            $tipoDoc= $row['rbCaracterIdentificacion'];
            $ruc= $row['ruc'];
             $nombreP= $row['nombre'];
            $nombreProveedor= $row['nombreProveedor'];
            $apellidoProveedor= $row['apellidoProveedor'];
            $nombre_comercial= $row['nombre_comercial'];
            $direccion= $row['direccion'];
            $telefono= $row['telefono'];
            
            $email= $row['email'];
            $id_tipo_proveedor= $row['id_tipo_proveedor'];
            $estado_Proveedor= $row['estado_Proveedor'];
            $parteRel= $row['parteRel'];
            $diasCredito= $row['diasCredito'];
            $paisPago= $row['paisPago'];
            $id_provincia= $row['id_provincia'];
            $id_ciudad= $row['id_ciudad'];
            
            $regimenProveedor =  $row['tipoContribuyente'];
            $tipoRegimen= $row['tipoRegimen'];
            $tipo_sustento= $row['tipo_sustento'];
            $tipo_comprobante= $row['tipo_comprobante'];
            $enlace_retencion_fuente= $row['enlace_retencion_fuente'];
            $enlace_retencion_iva= $row['enlace_retencion_iva'];
            $retencion_fuente_activos= $row['retencion_fuente_activos'];
            $retencion_fuente_servicios= $row['retencion_fuente_servicios'];
            $retencion_fuente_proveeduria= $row['retencion_fuente_proveeduria'];
            $banco= $row['banco'];
            $cuentaBanco= $row['cuentaBanco'];
            $tipoCuenta= $row['tipoCuenta'];
            
            $pagoResidente = $row['pagoResidente'];
            $tipoRegimen = $row['tipoRegimen'];
            $paisPago = $row['paisPago'];
            $paisParaiso = $row['pagoParaiso'];
            $pagoPreferente = $row['pagoPreferente'];
            $paisResidencia = $row['paisResidencia'];
            
            
        }
	}
	
?>

<html lang="es-ES">
    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Proveedores</title>
    
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
  <script language="javascript" type="text/javascript" src="js/proveedor.js"></script>
   <script src="librerias/select2/js/select2.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.blockUI.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.validate.1.5.2.js"></script>
    
    <script language="javascript" type="text/javascript" src="js/index.js"></script>
    
    <script language="javascript" type="text/javascript" src="js/proveedor.js"></script>
    <script language="javascript" type="text/javascript" src="js/compras.js"></script>
    <script language="javascript" type="text/javascript" src="js/enlaces_compras.js"></script>

    <script language="javascript" type="text/javascript" src="js/productos.js"></script>
    <script type="text/javascript" src="js/funciones.js"></script>
    <script type="text/javascript" src="js/validaciones.js"></script>
    <script type="text/javascript" src="js/validaFactura.js"></script>
    <script type="text/javascript" src="js/compras.js"></script>
    <!--<script type="text/javascript" src="js/sri.js"></script> -->
    <script type="text/javascript" src="js/busquedas.js"></script>
    <script language="javascript" type="text/javascript" src="js/calendario.js"></script>
    <script language="javascript" type="text/javascript" src="js/subirXML.js"></script>
    <script type="text/javascript" src="js/centroCosto.js"></script>

    <script type="text/javascript">
        
    $(document).ready(function(){
              setTimeout(function(){
                  <?php if(isset($id_provincia)){  ?>
     $("#cbprovincia> option[value=<?php echo $id_provincia  ?>]").attr("selected",true);
    <?php }   ?>
    document.getElementById('cbprovincia').click();
  combociudad(3);
     console.log('provincia')
}, 1500); 
    
    setTimeout(function(){
                  <?php if(isset($id_ciudad)){      ?>
     $("#cbciudad> option[value=<?php echo $id_ciudad  ?>]").attr("selected",true);
     <?php }   ?>
     
     console.log('ciudad')
}, 3500); 

            combopais(1);
            comboprovincia(2 );    
            comboRetencionIva(6);
            comboRetencionFuente(7)    
            $('#banco').select2();
           
            
      
//               setTimeout(function(){
//       $("#cbciudad> option[value=""]").attr("selected",true);
// }, 1000); 
            
           
    });
    
    
    
</script> 

</head>



<body >
<div class="wrapper d-flex align-items-stretch celeste">
        <nav id="sidebar" ><?php  {include("menusNiveles.php");      }   ?></nav>
    <div id="content"  >
        <header ><?php  {include("header.php");      }   ?>  </header>
       
    <div class="row mx-5 mt-5 bg-white rounded p-2">
    

      <form name="form" id="form" method="post" action="javascript: guardar_proveedores_modal3();" >
          
     <input type="hidden" id="idproveedor"  name="idproveedor" required  value="<?php echo  $id_proveedor ?>" />
     
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12 text-center">
                <H1>Nuevo Proveedor</H1>
            </div>
        </div>
        <div class="row r-10 p-3">
                <div class="col-lg-4">
                    <label for="txtRucProveedor" class="form-label">
                   
                    <input type="radio" name="rbCaracterIdentificacion" id="rbCaracterIdentificacion" value="05" <?php if($tipoDoc=='05'){ ?>checked <?php } ?> onChange=""/><label for="rCedula">Cedula</label>
                    <input type="radio" name="rbCaracterIdentificacion" id="rbCaracterIdentificacion" value="04" <?php if($tipoDoc=='04'){ ?>checked <?php } ?>  onchange=""/><label for="rRuc"> Ruc  </label>
                    <input type="radio" name="rbCaracterIdentificacion" id="rbCaracterIdentificacion" value="07"  <?php if($tipoDoc=='07'){ ?>checked <?php } ?>  onchange="datosPago()"/><label for="rPasaporte">Pasaporte </label>
                    </label>
                    <input type="text" tabindex="3" id="txtRucProveedor" class="form-control" required name="txtRucProveedor" 
                        title="Ingresa el Código"   autocomplete="off"
                        onblur="cedula_ruc(txtRucProveedor.value)"onKeyPress="return soloNumeros(event);" onChange="no_repetir_ruc(txtRucProveedor,4);" value="<?php echo $ruc ?>"  /> 
                        <div id="noRepetirRuc"></div><div id="mensageCedula"></div> <div id="mensageCedula2"></div> 
                </div>
            
                <div class="col-lg-4">
                    <label for="txtNombreComercial" class="form-label">Nombres</label>
                    <input class="form-control"  type="text"  style="text-transform: uppercase;" tabindex="1" id="txtNombre" required 
                    name="txtNombre" maxlength="35" autocomplete="off" 
                    onKeyUp="no_repetir_nombre()" onClick="no_repetir_nombre()" value="<?php echo $nombreP ?>" />
                    <div id="noRepetirNombre"></div><div id="nombreVacio"></div>
                </div>
                <div class="col-lg-4">
                    <label for="txtNombreComercial" class="form-label">Apellidos</label>
                    <input class="form-control"  type="text"  style="text-transform: uppercase;" tabindex="1" id="txtApellido" required 
                    name="txtApellido" maxlength="35" autocomplete="off" onKeyUp="no_repetir_nombre()" onClick="no_repetir_nombre()" value="<?php echo $apellidoProveedor ?>" />
                    <div id="noRepetirNombre"></div><div id="nombreVacio"></div>
                </div>
                
                
            </div>
            
            <div class="row  mt-1  r-10 p-3">
                
               
                <div class="col-lg-4">
                    <label for="txtNombreComercial" class="form-label">Nombre comercial (requerido)</label>
                    <input class="form-control"  type="text"  style="text-transform: uppercase;" tabindex="1" id="txtNombreComercial" required 
                    name="txtNombreComercial" maxlength="35" autocomplete="off" 
                    onKeyUp="no_repetir_nombre()" onClick="no_repetir_nombre()" value="<?php echo $nombre_comercial ?>" />
                    <div id="noRepetirNombre"></div><div id="nombreVacio"></div>
                </div>
                <div class="col-lg-4">
                    <label for="txtDireccion" class="form-label">Direcci&oacute;n (requerido)</label>
                    <input class="form-control"  type="text" style="text-transform: capitalize;" tabindex="2" id="txtDireccion" 
                    required name="txtDireccion" title="Ingresa la direccion aquí" value="<?php echo $direccion ?>"
                    maxlength="180" />
                </div>
                <div class="col-lg-4">
                    <label for="txtRucProveedor" class="form-label">T&eacute;lefono (requerido)</label>
                    <input type="text" tabindex="4" id="txtTelefono" class="form-control" required name="txtTelefono" title="Ingresa el numero de telefono aquí"   value="<?php echo $telefono ?>"
                    maxlength="10" onKeyPress="return soloNumeros(evt)"/>
                </div>
               
            </div>
            <div class="row mt-1 r-10 p-3 ">
                 <div class="col-lg-4">
               
                    <label for="txtEmail" class="form-label">Email </strong></label>
                         <!--<a onClick="correoGenerico()" class="btn btn-success btn-small">Correo Generico</a>-->
                    <input type="text" tabindex="6" id="txtEmail" class="form-control" name="txtEmail" required title="Ingresa el email aquí Ej nombre@hotmail.com" 
                    maxlength="250" onBlur="return isEmailAddress(txtEmail)" value="<?php echo $email ?>" />
                    <div id="mensajeEmail"></div>
                </div>
                
                 <div class="col-lg-2">
                    <label for="cmbTipoProveedor" class="form-label">Tipo Proveedor</label>
                <select  id="cmbTipoProveedor" name="cmbTipoProveedor" required="required"  class="form-control"  data-live-search="true" > 
                <option value="01" <?php if($id_tipo_proveedor =='01'){ ?> selected  <?php }  ?> >Persona Natural</option>
                <option value="02" <?php if($id_tipo_proveedor =='02'){ ?> selected  <?php }  ?> >Sociedad</option>
                </select>
                </div>
        <div class="col-lg-2 text-center">
            <label for="radio-estado-activo" class="form-label">Estado de Proveedor</label>
                <div class="switch-field my-3 justify-content-center">
                    <input type="radio" id="radio-estado-activo" name="switch-estado" value="Activo"   <?php if($estado_Proveedor =='Activo'){ ?> checked  <?php }  ?> />
                    <label for="radio-estado-activo">Activo</label>

                    <input type="radio" id="radio-estado-inactivo" name="switch-estado" value="Inactivo" <?php if($estado_Proveedor =='Inactivo'){ ?> checked  <?php }  ?> />
                    <label for="radio-estado-inactivo">Inactivo</label>
                </div>
        </div>   
                
            <div class="col-lg-2 text-center">
            <label for="radio-parteRel-SI" class="form-label">Parte Rel</label>
                <div class="switch-field my-3 justify-content-center">
                    <input type="radio" id="radio-parteRel-SI" name="switch-parteRel" value="SI" <?php if($parteRel =='SI'){ ?> checked  <?php }  ?> />
                    <label for="radio-parteRel-SI">SI</label>

                    <input type="radio" id="radio-parteRel-NO" name="switch-parteRel" value="NO" <?php if($parteRel =='NO'){ ?> checked  <?php }  ?>  />
                    <label for="radio-parteRel-NO">NO</label>
                </div>
            </div> 
             <div class="col-lg-2">
                    <label for="diasCredito" class="form-label">Dias cr&eacute;dito</label>
                    <input type="text" tabindex="3" id="diasCredito" class="form-control fs-4 p-1 text-center" name="diasCredito" 
                    autocomplete="off" onKeyPress="return soloNumeros(evt)" 
                    value="<?php echo $diasCredito ?>"/> 
               </div>
            </div>    
               <div class="row mt-1 r-10 p-3 ">    
                
                <div class="col-lg-3">
                    <label  for="cbpais"  class="form-label">Pais (requerido)</label>
                <select  tabindex="10"  class="form-control" required="required" name="cbpais" id="cbpais" 
                onChange="comboprovincia(2);mostrarcombo()" ondblclick="combopais(1);"></select>
                <input type="hidden" name="opcion" value="1"/>
                </div>
                
                <div class="col-lg-3">
                    <label for="txtRucProveedor" class="form-label">Provincias (requerido)</label>
                    <select tabindex="11" class="form-control" name="cbprovincia" id="cbprovincia" onChange="combociudad(3);mostrarcombo()"></select>
                            <input type="hidden" name="opcion1" value="">
                </div>
                <div class="col-lg-3">
                    <label for="txtRucProveedor" class="form-label">Ciudades (requerido)</label>
                     <select tabindex="12" class="form-control" name="cbciudad" id="cbciudad" onChange="mostrarcombo()" ></select>
                            <input type="hidden" name="opcion2" value="" id="opcion2">
                </div>
            
               
            </div>
       
                
        <div class="row mt-1 r-10 p-3 ">             
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2>Datos Administrativos</h2>
                        </div>
                        
                        <div class="col-lg-12 mt-3">
                    
                <label class="fw-bold">Regimen</label>
                    <select id="regimenProveedor" name="regimenProveedor" class="form-control required">
                    <?php
                          $sqlc="Select * From tipoProveedor";
                          $resultc=mysql_query($sqlc);
                          while($rowc=mysql_fetch_array($resultc)){ ?>
                               <option value="<?=$rowc['id']; ?>"  <?php if($regimenProveedor ==$rowc['id'] ){ ?> selected  <?php }  ?> > <?=$rowc['detalle']; ?> </option>
                          <?php             }             ?>
                    </select> 
                </div>           
                <div class="col-lg-12 ">
                <label class="font-weight-light">Tipo Sustento</label>
                <select id="codSustento" name="codSustento" required="required" class="form-control required" onChange="llenarSelect();">
                <?php
                      $sqlc="Select * From sustentosTributarios";
                      $resultc=mysql_query($sqlc);
                      while($rowc=mysql_fetch_array($resultc)){ ?>
                           <option value="<?=$rowc['codigo']; ?>" data-tipo="<?=$rowc['comprobantes']; ?>"   <?php if($tipo_sustento ==$rowc['codigo'] ){ ?> selected  <?php }  ?>   > <?=$rowc['codigo']."-".$rowc['detalle']; ?> </option>
                      <?php             }             ?>
                </select> 
            </div>
                
            <div class="col-lg-12">
                <label class="font-weight-light">Tipo:</label>
                 <select id="txtTipoComprobante" name="txtTipoComprobante" required="required" class="form-control required" onChange="buscar_secuencial_compra(9,this.value);">
                <option value="0">Ninguno</option>
                <?php
                      $sqlc="Select * From tipoDocumento";
                      $resultc=mysql_query($sqlc);
                      while($rowc=mysql_fetch_array($resultc)){ ?>
                           <option id="<?=$rowc['id']; ?>" value="<?=$rowc['codigo']; ?>" style="display:block" <?php if($tipo_comprobante ==$rowc['codigo'] ){ ?> selected  <?php }  ?>  > <?=$rowc['codigo']."-".$rowc['detalle']; ?> </option>
                      <?php             }             ?>
                </select> 
            </div>
                        
            <div class="col-lg-12 my-3">
                <label class="fw-bold">Retencion fuente Inventarios:</label>
                 <select id="retencionFuente" name="retencionFuente" required="required" class="form-control required">
                     <option value="0">Ninguna</option>
                <?php
                      $sqlc="Select * From enlaces_compras where tipo like 'retencion-fuente%' and id_empresa='".$sesion_id_empresa."' ";
                      $resultc=mysql_query($sqlc);
                      while($rowc=mysql_fetch_array($resultc)){ ?>
                           <option value="<?=$rowc['id']; ?>" <?php if($enlace_retencion_fuente ==$rowc['id'] ){ ?> selected  <?php }  ?>  > <?=$rowc['nombre']; ?> </option>
                      <?php             }             ?>
                </select> 
            </div>
            <div class="col-lg-12">
                <label class="fw-bold">Retencion fuente Servicios:</label>
                 <select id="retencionFuente" name="retencionFuenteServicios" required="required" class="form-control required">
                     <option value="0">Ninguna</option>
                <?php
                      $sqlc="Select * From enlaces_compras where tipo like 'retencion-fuente%' and id_empresa='".$sesion_id_empresa."' ";
                      $resultc=mysql_query($sqlc);
                      while($rowc=mysql_fetch_array($resultc)){ ?>
                           <option value="<?=$rowc['id']; ?>"  <?php if($retencion_fuente_servicios ==$rowc['id'] ){ ?> selected  <?php }  ?>  > <?=$rowc['nombre']; ?> </option>
                      <?php             }             ?>
                </select> 
            </div>
            
            <div class="col-lg-12">
                <label class="fw-bold">Retencion fuente Proveeduria:</label>
                 <select id="retencionFuenteProveeduria" name="retencionFuenteProveeduria" required="required" class="form-control required">
                     <option value="0">Ninguna</option>
                <?php
                      $sqlc="Select * From enlaces_compras where tipo like 'retencion-fuente%' and id_empresa='".$sesion_id_empresa."' ";
                      $resultc=mysql_query($sqlc);
                      while($rowc=mysql_fetch_array($resultc)){ ?>
                           <option value="<?=$rowc['id']; ?>"  <?php if($retencion_fuente_proveeduria ==$rowc['id'] ){ ?> selected  <?php }  ?>  > <?=$rowc['nombre']; ?> </option>
                      <?php             }             ?>
                </select> 
            </div>
            <div class="col-lg-12 my-3">
                <label class="fw-bold">Retencion fuente Activos:</label>
                 <select id="retencionFuente" name="retencionFuenteActivos" required="required" class="form-control required">
                     <option value="0">Ninguna</option>
                <?php
                      $sqlc="Select * From enlaces_compras where tipo like 'retencion-fuente%' and id_empresa='".$sesion_id_empresa."' ";
                      $resultc=mysql_query($sqlc);
                      while($rowc=mysql_fetch_array($resultc)){ ?>
                           <option value="<?=$rowc['id']; ?>"  <?php if($retencion_fuente_activos ==$rowc['id'] ){ ?> selected  <?php }  ?> > <?=$rowc['nombre']; ?> </option>
                      <?php             }             ?>
                </select> 
            </div>
            <div class="col-lg-12">
                <label class="fw-bold">Retencion IVA:</label>
                 <select id="retencionIVA" name="retencionIVA" required="required" class="form-control required">
                        <option value="0">Ninguna</option>
                <?php
                      $sqlc="Select * From enlaces_compras where tipo like 'retencion-iva%' and id_empresa='".$sesion_id_empresa."' ";
                      $resultc=mysql_query($sqlc);
                      while($rowc=mysql_fetch_array($resultc)){ ?>
                            <option value="<?=$rowc['id']; ?>"  <?php if($enlace_retencion_iva ==$rowc['id'] ){ ?> selected  <?php }  ?> > <?=$rowc['nombre']; ?> </option>
                      <?php             }             ?>
                </select> 
            </div>
                        
                        
                        
                    </div>
                </div>
                
            <div class="col-lg-4">
                 <div class="row">
                      <div class="col-lg-12">
                            <h2>Datos Bancarios</h2>
                        </div>
            
            <div class="col-lg-12">
                  <label for="diasCredito" class="form-label">Banco</label>
                    <!--<input type="text" tabindex="3" id="banco" class="form-control fs-4 p-1 text-center" name="banco" -->
                    <!--autocomplete="off" onKeyPress="return soloNumeros(evt)"/> -->
                    
                     <select id="banco" name="banco" required="required" class="form-control required">
                        <option value="0">Ninguna</option>
                <?php
                      $sqlc="Select * From  listadoBancos  ";
                      $resultc=mysql_query($sqlc);
                      while($rowc=mysql_fetch_array($resultc)){ ?>
                            <option value="<?=$rowc['codigoCash']; ?>"  <?php if($banco ==$rowc['codigoCash'] ){ ?> selected  <?php }  ?>  > <?=$rowc['nombre']; ?> </option>
                      <?php             }             ?>
                </select> 
                    
            </div>
            <div class="col-lg-12 my-3">
                  <label for="diasCredito" class="form-label"># Cuenta</label>
                    <input type="text" tabindex="3" id="cuentaBanco" class="form-control fs-4 p-1 text-center" name="cuentaBanco" 
                    autocomplete="off" onKeyPress="return soloNumeros(evt)" value="<?php echo $cuentaBanco ?>"/> 
            </div>
            <div class="col-lg-12">
                  <label for="diasCredito" class="form-label">Tipo de cuenta</label>
                    <select id="tipoCuenta" name="tipoCuenta" class="form-control">
                        <option class="ahorros" <?php if($tipoCuenta =='Ahorros' ){ ?> selected  <?php }  ?> >Ahorros</option>
                        <option class="corriente" <?php if($tipoCuenta =='Corriente' ){ ?> selected  <?php }  ?> >Corriente</option>
                    </select>
            </div>
            
        </div>
            </div>
        <div class="col-lg-4" id="datosPago" style="display:none">
            
            <div class="row mt-5" >
                    <div class="col-lg-12">
                            <h2>Dato de pago</h2>
                        </div>
                
            <div class="col-lg-12">
                  <label for="diasCredito" class="form-label">Residente o no residente</label>
                  <select class="form-control" id="pagoResidente" name="pagoResidente">
                      <option value="01" <?php if($pagoResidente=='01'){ ?> selected  <?php } ?>  >PAGO A RESIDENTE 01</option>
                      <option value="02"  <?php if($pagoResidente=='02'){ ?> selected  <?php } ?> >PAGO A NO RESIDENTE 02</option>
                  </select>
            </div>
            <div class="col-lg-12">
                   <label for="diasCredito" class="form-label">Regimen Fiscal</label>
                  <select class="form-control" id="tipoRegimen" name="tipoRegimen">
                      <option value="0"  <?php if($tipoRegimen=='0'){ ?> selected  <?php } ?> >No aplica</option>
                      <option value="01" <?php if($tipoRegimen=='01'){ ?> selected  <?php } ?> >Regimen General</option>
                      <option value="02" <?php if($tipoRegimen=='02'){ ?> selected  <?php } ?> >Paraiso Fiscal</option>
                      <option value="03" <?php if($tipoRegimen=='03'){ ?> selected  <?php } ?> >Régimen fiscal preferente o jurisdicción de menor imposición</option>
                  </select>
            </div>
            
            <div class="col-lg-12">
                  <label for="diasCredito" class="form-label">Pais Pago Regimen Fiscal</label>
                    <select id="paisPago" name="paisPago" class="form-control">
                        <option value="0">No aplica</option>
                         <?php
                      $sqlc="Select * From paisesExterior";
                      $resultc=mysql_query($sqlc);
                      while($rowc=mysql_fetch_array($resultc)){ ?>
                           <option value="<?=$rowc['codigo']; ?>"  <?php if($paisPago==$rowc['codigo']){ ?> selected  <?php } ?> > <?=$rowc['detalle']; ?> </option>
                      <?php             }             ?>
                        
                        
                    </select>
            </div>
            <div class="col-lg-12">
                  <label for="diasCredito" class="form-label">Pais Pago Paraiso Fiscal </label>
                    <select id="pagoParaiso" name="pagoParaiso" class="form-control">
                        <option value="0">No aplica</option>
                         <?php
                      $sqlc="Select * From paisesExterior";
                      $resultc=mysql_query($sqlc);
                      while($rowc=mysql_fetch_array($resultc)){ ?>
                           <option value="<?=$rowc['codigo']; ?>"  <?php if($paisParaiso==$rowc['codigo']){ ?> selected  <?php } ?>  > <?=$rowc['detalle']; ?> </option>
                      <?php             }             ?>
                        
                        
                    </select>
            </div>
            
             <div class="col-lg-12">
                  <label for="diasCredito" class="form-label">Denominación del régimen fiscal preferente o jurisdicción de menor imposición.</label>
                <input name="pagoPreferente" id="pagoPreferente" class="form-control" value="<?php echo $pagoPreferente ?>">
            </div>

            
            <div class="col-lg-12">
                <label for="diasCredito" class="form-label">País de residencia o establecimiento permanente a quién se efectúa el pago.</label>
                <select id="paisResidencia" name="paisResidencia" class="form-control">
                    <option value="0">No aplica</option>
                        <?php
                          $sqlc="Select * From paisesExterior";
                          $resultc=mysql_query($sqlc);
                          while($rowc=mysql_fetch_array($resultc)){ ?>
                               <option value="<?=$rowc['codigo']; ?>" <?php if($paisResidencia==$rowc['codigo']){ ?> selected  <?php } ?> > <?=$rowc['detalle']; ?> </option>
                        <?php             }             ?>
                    </select>
            </div>
        </div>
        </div>    
            
        </div>
        

        
        
        
        
        
       
        
        
        
           
                
              
            <div class="path2"></div>
                

               <div class="modal-footer">
        <button type="button" class="btn btn-default" onClick="fn_cerrar()">Cerrar</button>
           <button class="btn btn-success" value="Guardar" type="submit" id="submit"  name="btnGuardar" >Guardar Proveedor</button>
            
      </div>
       
       
      </center> 
            </form>
   </div> 
         
    <div id="div_oculto" style="display: none;"></div>

    </div>	

</div>

</body>	
    <script>
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
function correoGenerico(){
    console.log("email");
    document.getElementById("txtEmail").value = "comprobantes@alimco-sys.com";
    // $("#txtEmail").value("facturas@alimco-sys.com");
}
function datosPago(){
   
        $("#datosPago").css("display", "");
    
}
</script>
</html>