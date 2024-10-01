<?php
  require_once('ver_sesion.php');
  session_start();    
  require_once('conexion.php');
    
    $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $emision_tipoFacturacion = $_SESSION["emision_tipoFacturacion"];
    $id_compra = $_GET['idCompra'];
    $numeroCompra =  $_GET['numeroCompra'];
  ?>
<html lang="es-ES">
    
<head>

    <title>Compras</title>
    
    <script src="https://kit.fontawesome.com/7816c50242.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">  
    <script src="librerias/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <script src="librerias/bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" href="librerias/bootstrap/css/style.css"> 
    <link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/alertify.css">
    <script src="librerias/alertifyjs/alertify.js"></script>
    <link rel="stylesheet" type="text/css" href="librerias/select2/css/select2.css">
    
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
  	<link type="text/css" rel="stylesheet" href="css/calendario.css" media="screen"/>
	<script language="javascript" type="text/javascript" src="js/calendario.js"></script>
	
    <script language="javascript" type="text/javascript" src="js/subirXML.js"></script>
    <script type="text/javascript" src="js/centroCosto.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
     <script>
         var  lotes_empresa1='';
        const url = 'sql/produccion.php';

const formData = new FormData();

// Agregar pares clave-valor manualmente
formData.append('txtAccion', '10' );


fetch(url, {
method: 'POST',
body:formData, // Convierte los datos a formato JSON
})
.then(response => {
if (!response.ok) {
throw new Error('Network response was not ok');
}
return response.text();; // Parsea la respuesta como JSON
})
.then(textData => {
      lotes_empresa1 = textData.trim();
    console.log({lotes_empresa1})
 })
.catch(error => {
console.error('There was a problem with the fetch operation:', error);
});


    </script>
</head>


<body onLoad="buscar_secuencial_compra(9)">
    
    <div class="wrapper d-flex align-items-stretch celeste">
    
    <nav id="sidebar" ><?php  {include("menusNiveles.php");      }   ?></nav>
    
    <div id="content"  class="p-0 m-0">
    
        <div class="row m-0"><?php  {include("header.php");      }   ?>  </div>
 
    <div class="row  m-0 ">  
    
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 p-1">
                <a class="text-decoration-none card p-1"  type="button"   id="btnNuevaFactura" name="btnNuevaFactura" onclick="javascript:forma_cobro();"/>
                    <div class="my-icon3"><i class="fa fa-link mr-3"></i><span>Enlaces</span>  </div>
                </a>
            </div>
    
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 p-1">
                <a class="text-decoration-none card p-1"  type="button"   id="btnNuevaFactura" name="btnNuevaFactura" onclick="location='reportesCompras.php'" />
                    <div class="my-icon3"><i class="fa fa-file-text-o mr-3"></i><span>Reportes</span>  </div>
                </a> 
            </div>
    
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 p-1">
                <a class="text-decoration-none card p-1 "  type="button"   id="btnNuevaFactura" name="btnNuevaFactura" onclick="javascript:nuevo_producto();"/>
                    <div class="my-icon3"><i class="fa fa-plus-circle mr-3"></i><span>Nuevo Producto</span>  </div>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 p-1">
                <a class="text-decoration-none card p-1"  type="button"   id="btnNuevaFactura" name="btnNuevaFactura" onclick="location='nuevaFacturaCompra.php'" />
                    <div class="my-icon3"><i class="fa fa-shopping-cart mr-3"></i><span>Nueva Compra</span>  </div>
                </a>
            </div>
            
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 p-1">
                <a class="text-decoration-none card p-1"  type="button"   id="btnNuevaFactura" name="btnNuevaFactura" onClick="javascript: nuevoProveedor();" />
                    <div class="my-icon3"><i class="fa fa-user-plus mr-3"></i><span>Crear Proveedor</span>  </div>
                </a>
            </div>
            
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 p-1">
                <a class="text-decoration-none card p-1"  type="button"   id="btnNuevaFactura" name="btnNuevaFactura" onClick="javascript: anular_compra(txtFactura,'5')" />
                    <div class="my-icon3"><i class="fa fa-trash-o mr-3"></i>Borrar Compra</div>
                </a>
            </div>
            
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 p-1">
                <a class="text-decoration-none card p-1"  type="button"  id="btnNuevaFactura" name="btnNuevaFactura" onClick="javascript: nuevo_centrocosto()" />
                    <div class="my-icon3 "><i class="fa fa-file mr-3"></i><span>Gestionar areas</span>  </div>
                </a> 
            </div>
            
            
  
    <div class="col-6 col-sm-4 col-md-3 col-lg-2 p-1">
        <a class="text-decoration-none card p-1"  type="button"  id="btnNuevaFactura" name="btnNuevaFactura" data-bs-toggle="modal" data-bs-target="#staticBackdrop" />
            <div class="my-icon3 "><i class="fa fa-file mr-3"></i><span>Tutorial Compras</span>  </div>
        </a> 
    </div>
 
            
            
    
                
                <!-- Modal -->
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Como ingresar una compra manual</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body p-5 text-center">
                            <iframe src="https://scribehow.com/embed/Como_ingresar_una_factura_de_compra_de_gastos_inventarios_servicios_en_el_sistema_de_forma_manual__5FTOETfdQXenDvW2f16gsw?as=scrollable&skipIntro=true&removeLogo=true" width="100%" height="640" allowfullscreen frameborder="0"></iframe>     
                       </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Entendido</button>
                      
                      </div>
                    </div>
                  </div>
                </div>

            
            <div class="col-2 col-sm-3 col-md-3 col-lg-4 p-1">
    <form method="post" name="xmlForm" id="xmlForm1" enctype="multipart/form-data" onsubmit="return handleXmlFormSubmit();">
        <div class="form-group">
            <div class="input-group">
                <input type="file" tabindex="1" id="files" name="files" title="Selecciona un archivo XML" class="form-control" autocomplete="off" onchange="return validarExt()" />
                <button class="btn btn-outline-secondary" type="submit">SUBIR XML</button>
            </div>
        </div>
    </form>
    <div class="col-lg-4" style="display:none">
        <div id="uploader"></div>
        <div id="message"></div>
        <div id="visorArchivo"></div>
        <p class="statusMsg"></p>
    </div>
</div>

            <div class="col-2 col-sm-3 col-md-3 col-lg-4 p-1">
                <form   method="post"  name="xmlForm" id="xmlForm" enctype="multipart/form-data" action="reportesComprastxt.php" >  
                    <div class="form-group">
                        <div class="input-group">
                            <input type="file" tabindex="1" id="userfile" name="userfile" title="Selecciona un archivo TXT"  class="form-control" autocomplete="off" /> 
                            <button class="btn btn-outline-secondary" type="submit" >SUBIR TXT</button>
                        </div>
                    </div>
                </form> 
            </div>
            
    
    </div>    
 <form id="form3" name="form3" method="post" action="javascript: guardar_compra();">
<div class="row bg-white rounded px-4 pt-2 ">  
<div id="mensaje3"></div>

    <input name="textXML" id="textXML" type="hidden"/> 
    <input type="hidden" name="txtIdCompra" id="txtIdCompra" value="<?php echo $id_compra; ?>" />
    <input name="textIdCompra" id="textIdCompra" type="hidden"/>    
      <input type="hidden" name="dominioOculto" id="dominioOculto" value="<?php echo $dominio; ?>" />
    <input name="textIdProveedor" id="textIdProveedor" type="hidden"/>
    <input type="hidden" name="numeroCompra" id="numeroCompra" value="<?php echo $numeroCompra; ?>" />
     <input type="hidden" name="seleccion_anticipo" id="seleccion_anticipo" />
    
    <div class="input-group  celeste p-2 rounded">
           <span class="input-group-text" id="basic-addon1">#</span>
    <input  name="txtFactura" id="txtFactura" type="text" class="form-control" value="" onclick="lookup_compra_educ(this.value,'', 8);" onKeyUp="lookup_compra_educ(this.value,'', 8);" />

        <span class="input-group-text" id="basic-addon1">Autorizaci&oacute;n:</span>
        <input name="txtAutorizacion" id="txtAutorizacion" type="text" class="form-control w-25"  />

        <span class="input-group-text" id="basic-addon1">Est:</span>
        <input name="txtSerie" id="txtSerie" type="text" class="form-control" value="" maxlength="3" />
        
        <span class="input-group-text" id="basic-addon1">Emisi&oacute;n:</span>
        <input name="txtEmision" id="txtEmision" type="text" class="form-control" value="" maxlength="3" />
        
        <span class="input-group-text" id="basic-addon1">Num:</span>
        <input name="txtNum" id="txtNum" type="text" class="form-control" value="" />
        

    </div>
    
    <div class="input-group  celeste p-2 rounded">
     <span class="input-group-text" id="basic-addon1">Ruc:</span>
    <input  name="txtRuc" id="txtRuc" type="text" class="form-control " value="" onclick="lookup6(this.value, 6);"  onKeyUp="lookup6(this.value, 6);" onBlur="fill6();" />
           
     <span class="input-group-text" id="basic-addon1">Proveedor:</span>
    <input  type="search" name="txtNombreRuc" id="txtNombreRuc" value="" placeholder="Buscar por Ruc o Nombre" 
    title="Ingrese Ruc o Nombre " class="form-control"  onclick="lookup6(this.value, 6);"  onKeyUp="lookup6(this.value, 6);" onBlur="fill6();"  
    autocomplete="off"  onchange="limpiar_id('textIdProveedor')"/>
    <div class="suggestionsBox" id="suggestions6" style="display:none"> 
        <div class="suggestionList" id="autoSuggestionsList6" ></div>
    </div>
    
    <span class="input-group-text" id="basic-addon1">Fecha Emision:</span>
    <input name="textFecha" id="textFecha" class="form-control " type="text" value="<?php echo date("Y-m-d H:i:s")?>"  onClick="displayCalendar(textFecha,'yyyy-mm-dd hh:ii:00',this,this)"  />
    
            <span class="input-group-text" id="basic-addon1">Caducidad:</span>
        <input name="txtFechaVencimiento" id="txtFechaVencimiento" type="text" value="2021/08/05"class="form-control" value="" onClick="displayCalendar(txtFechaVencimiento,'yyyy/mm/dd',this)"  />
    
    <input type="radio" class="btn-check" name="switch-two" id="radio-one-ice" value="0" checked>
    <label class="btn btn-outline-success" for="radio-one-ice">ATS</label>
    <input type="radio" class="btn-check" name="switch-two" id="radio-two-ice" value="1"  >
    <label class="btn btn-outline-success" for="radio-two-ice">NO ATS</label>
    
    </div>
    
    <div class="input-group mb-3 celeste p-2 rounded">
        <span class="input-group-text">Tipo Comprobante:</span>
        <select id="tipoDoc" name="tipoDoc" required="required" class="form-select required" >
        <option value="Diario" >Diario</option>
        <option value="Ingreso" >Ingreso</option>
        <option value="Egreso" >Egreso</option>
        </select> 
                       <div class="col-lg-1" hidden="">
          <label>Tipo de Compra</label>
              <select tabindex="1" id="cmbTipoCompra"  class="form-control required" name="cmbTipoCompra" required="required" >
                  <?php
                      $sqlc="Select * From tipos_compras  where id_tipo_cpra <=2;";
                      $resultc=mysql_query($sqlc);
                      while($rowc=mysql_fetch_array($resultc)){ ?>
                           <option value="<?=$rowc['descripcion']; ?>"> <?=$rowc['descripcion']; ?> </option>
                      <?php             }             ?>
              </select>
        </div>
        
        <span class="input-group-text">Tipo Sustento :</span>
        <select id="codSustento" name="codSustento" required="required" class="form-select required" onChange="llenarSelect();changeDocumento(this.value);">
                <?php
              $sqlc="Select * From sustentosTributarios";
              $resultc=mysql_query($sqlc);
              while($rowc=mysql_fetch_array($resultc)){ ?>
                   <option value="<?=$rowc['codigo']; ?>" data-tipo="<?=$rowc['id']; ?>"> <?=$rowc['codigo']."-".$rowc['detalle']; ?> </option>
              <?php             }             ?>
        </select> 
     
        <span class="input-group-text">Tipo Documento:</span>
        <select id="txtTipoComprobante" name="txtTipoComprobante" required="required" class="form-control required" onChange="buscar_secuencial_compra(9,this.value);mostrar_grilla_reembolso();">
                <?php
                      $sqlc="Select * From tipoDocumento";
                      $resultc=mysql_query($sqlc);
                      while($rowc=mysql_fetch_array($resultc)){ ?>
                           <option id="<?=$rowc['id']; ?>" value="<?=$rowc['codigo']; ?>" style="display:block"> <?=$rowc['codigo']."-".$rowc['detalle']; ?> </option>
                      <?php             }             ?>
        </select> 
                
                
                
                
                
        <span class="input-group-text">Centro de costos:</span>    
                    <select  name="cmbCentro" id="cmbCentro" class="form-select"  required="required" >
                        <option value="0">Selecciona un centro de costo</option>
                        <?php
                $est2="Select * From centro_costo_empresa where id_empresa='".$sesion_id_empresa."';";
                $resultest2=mysql_query($est2);
                while($rowu=mysql_fetch_array($resultest2))
                     { ?>   <option value="<?=$rowu['id_centro_costo']; ?>"><?=$rowu['detalle']; ?></option>  <?php  }  ?>
                    </select>
    </div>
    
</div>

<div class="row bg-white pt-0">
    
    
    
<div class="col-lg-12 ">        
        <div class="col-lg-12 ">
           
            <div id="div_reembolso" style="display:none">

            <div class="form-group">
                
                <input name="txtContadorFilasReembolso"  id="txtContadorFilasReembolso" type="hidden" />
               <div class="input-group mt-1"> 
                   <a style='width:5% '  href="javascript: AgregarFilasReembolso();" title="Agregar nueva fila" class="btn btn-outline-secondary celeste">
                       <i class="fa fa-plus" aria-hidden="true"></i>
                   </a>
                 
                   <div class=" border  py-2 form-control celeste " style="width:7%" >C&eacute;dula/R.U.C.</div>
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
                        </br>
                        </br>

        </div>
            </div>
            
        <div class='input-group p-2 border'>
            
            <span class="input-group-text" style='width:6%' ><a href="javascript: AgregarFilas();" title="Agregar nueva fila"><i class='fa fa-plus' aria-hidden='true'></i></a></span>
            <input type='text' class='form-control border-0' value="C&oacute;digo">
            <input type='text' class='form-control border-0' style='width:25%' value="Descripci&oacute;n">
            <input type='text' class='form-control border-0' value="&Aacute;rea">
            <input type='text' class='form-control border-0' value="Desc">
            <input type='text' class='form-control border-0' value="Cant">
            <input type='text' class='form-control border-0' value="V.Unitario">
            <input type='text' class='form-control border-0' value="V.Total">
            
        </div>
        
        <div id="campos"></div>
        <div class="row">
             
           
            <div class="col-lg-3 offset-lg-2 pt-5 ">
                <button class="btn btn-success btn-lg" id="submit" type="submit" tabindex="5"  name="submit" value="Guardar Compra" >Guardar Compra</button>
            </div>
            
         
            
            
           
                
            <div class="col-lg-3 "> 
            
                <div class="input-group my-3">
                    <span class="input-group-text w-50" id="basic-addon1">ST Inventarios</span>
                    <input type="text" class="form-control  border-0 bg-light" id="txtSubtotalInventarios" name="txtSubtotalInventarios" readonly="readonly" />
                </div>
                <div class="input-group my-3">
                    <span class="input-group-text w-50" id="basic-addon1">ST Proveeduria</span>
                    <input type="text" class="form-control  border-0 bg-light" id="txtSubtotalProveeduria" name="txtSubtotalProveeduria" readonly="readonly" />
                </div>
               
               
                      <?php
                 $sqlImpuestos = "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='".$sesion_id_empresa."'  ";
                 $resultImpuestos = mysql_query($sqlImpuestos);
                 $listaImpuestos = array();
                 while( $rowImp = mysql_fetch_array($resultImpuestos) ){
                    $listaImpuestos[]= $rowImp['id_iva'];
                    ?>
                    
                    
                <div class="input-group my-3">
                    <span class="input-group-text w-50" id="basic-addon1">IVA <?php echo $rowImp['iva'].' %' ?></span>
                    <input type="text" class="form-control  border-0 bg-white" id="subTotal<?php echo $rowImp['iva'] ?>"
                    name="subTotal<?php echo $rowImp['iva'] ?>" readonly="readonly" value="0.00" />
                </div>  
                    
                    


                <?php
                 }
    $listaImpuestosJson = json_encode($listaImpuestos);
                ?>
                
               
                
            </div>    
              <div id="json_impuestos" style="display: none;"><?php echo $listaImpuestosJson; ?></div>
             <div class="col-lg-3 ">    
                
                 <div class="input-group my-3">
                    <span class="input-group-text  w-50" id="basic-addon1">ST Servicios</span>
                    <input type="text" class="form-control  border-0 bg-light" id="txtSubtotalServicios" name="txtSubtotalServicios" readonly="readonly" />
                </div>   
                <div class="input-group my-3">
                    <span class="input-group-text  w-50" id="basic-addon1">ST Activos</span>
                    <input type="text" class="form-control  border-0 bg-light" id="txtSubtotalActivos" name="txtSubtotalActivos" readonly="readonly" />
                </div>   
              
                <div class="input-group my-3">
                     <span class="input-group-text  w-50" id="basic-addon1">ST Sin Impuestos</span>
                     <input type="text" class="form-control  border-0 bg-white" id="txtSubtotal" name="txtSubtotal" readonly="readonly" />
                </div>
                <div class="input-group my-3">
                     <span class="input-group-text  w-50" id="basic-addon1">Descuento</span>
                     <input type="text" class="form-control  border-0 bg-white" id="txtDescuento" name="txtDescuento" readonly="readonly" />
                </div>
                <!--<div class="input-group my-3">-->
                <!--     <span class="input-group-text  w-50" id="basic-addon1">ICE</span>-->
                <!--     <input type="text" class="form-control  border-0 bg-white" id="txtIce" name="txtIce" readonly="readonly" />-->
                <!--</div>-->
                <div class="input-group my-3">
                        <span class="input-group-text  w-50" id="basic-addon1">IVA</span>
                        <input  type="hidden" id="txtIdIva" name="txtIdIva" value=" 12" />
                        <input type="text" class="form-control  border-0 bg-white"  id="txtIva" name="txtIva" readonly="readonly" value="0"/>
                </div>
                <div class="input-group my-3">
                     <span class="input-group-text  w-50" id="basic-addon1">Total</span>
                     <input type="text" class="form-control  border-0 bg-white" name="txtTotal" id="txtTotal" readonly="readonly" value="0" />
                     <input type="hidden" class="form-control border-0" id="txtContadorFilasCpra" name="txtContadorFilasCpra" readonly />
                </div>

            </div>
            
        </div>
    </div>        
</div> 

       
    </form>
   </div>  
</div>

 <div id="div_oculto"  style="display: none;"></div> 
</div>
</div>  


</body>
        
     
<script type="">

function cargaCompra(){
    let valor =  document.getElementById('numeroCompra').value;
    if(valor.trim()!=''){
        txtFactura.value= valor;
        lookup_compra_educ(valor,'', 8);
    }
  
}

function reporteCompras(id_compra){
    miUrl = "reportes/rptFacturaCompra.php?id_compra="+id_compra;
    window.open(miUrl,'facturaCompra','width=600, height=600, scrollbars=NO, titlebar=no');
    
}

    const llenarSelect=()=>{
        
        var codSustento = document.querySelector("#codSustento");
        var lista = codSustento.options[codSustento.selectedIndex].getAttribute('data-tipo');
     
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
    
            }
      
        }
        
    }
    
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script> 

<script>

         // Give $ to prototype.js
         var $jq = jQuery.noConflict();
       </script>
       <script src="https://malsup.github.io/jquery.form.js"></script> 
       <script type="text/javascript">
      
        var obj = { one: 1, two: 2, three: 3 };
        var key = 'two';
 
        var hasKey = (obj[key] !== undefined);




function handleXmlFormSubmit() {
    var options = {
        target: '#message',
        url: 'sql/compraXml.php',
        beforeSubmit: function() {
            console.log('antes');
        },
        success: function(data) {
            // console.log('cguardo');
            // console.log(data);
            try {
                let json = JSON.parse(data);
                if (json['errores'] !== undefined) {
                    let errores = json['errores'].length;
                    for (let a = 0; a < errores; a++) {
                        alertify.error(json['errores'][a]);
                    }
                }
                if (json['advertencias'] !== undefined) {
                    let advertencias = json['advertencias'].length;
                    for (let c = 0; c < advertencias; c++) {
                        alertify.warning(json['advertencias'][c]);
                    }
                }
                if (json['mensajes'] !== undefined) {
                    let mensajes = json['mensajes'].length;
                    for (let b = 0; b < mensajes; b++) {
                        alertify.success(json['mensajes'][b]);
                    }
                }
                if (json['numeroCompra'] != '') {
                    lookup_compra_educ(json['numeroCompra'], '', 8);
                }
            } catch (error) {
                console.log(error);
                alertify.error('Error al subir xml');
            }
        }
    };

    $jq('#xmlForm1').submit(function() {
        $jq(this).ajaxSubmit(options);
        return false;
    });
}

$jq(document).ready(function() {
    handleXmlFormSubmit();
});


        function validarExt()
        {
          var archivoInput = document.getElementById('files');
          var archivoRuta = archivoInput.value;
          var extPermitidas = /(.xml|.XML)$/i;
          if(!extPermitidas.exec(archivoRuta)){
            alertify.error('Tipos de archivos vÃ¡lidos admitidos :  XML');
            archivoInput.value = '';
            return false;
          }
        }
    

function revisarCuentasCobrar(){
    let idFactura = document.getElementById("textIdCompra").value;
    let idCliente =document.getElementById("textIdProveedor").value; 
    console.log("idCliente",idCliente);

	$.ajax(
	{
		url: 'sql/habitaciones.php',
		data: "txtAccion=25&idCliente="+idCliente+"&idFactura="+idFactura,
		type: 'post',
		success: function(data)	{
		    
		    let json =  JSON.parse(data);
		  //  console.log(data);
            if(json.cobrosPagos>0){
                alertify.error('La compra no se puede modificar por que tiene canceladas cuentas por cobrar');
                fn_cerrar();
                return;
            }
		    if(json.saldo>0 && json.cobrosPagos==0 && json.funcion!=null){
		         cargar_formas (json.saldo);
                document.getElementById("funcionAnticipo").value = json.funcion;
                document.getElementById("sumatoriaSaldoAnticipo").value = json.saldo;
              
               
                //  cruzarAnticipoCobrar(json.funcion,json.saldo,json.saldo_agrupado);
		    }
		      if(json.funcion==null){
                document.getElementById('formulario_anticipos').innerHTML = 
          '<strong><p style="color:red">No existe enlace de tipo anticipo. Es necesario crearlo para acceder a las cuentas por cobrar.</p></strong>';

                // alertify.error('Es necesario crear un enlace compras de tipo anticipo, para cruzar anticipos.');
		    }
           
          
		    
		}
	});

}
function agregar() {
            // $('#loader').show(); 	
            let fd = new FormData();
            fd.append("files", document.getElementById("files3").files[0]);


            fetch('sql/compraXml3.php', {
                method: 'POST',
                body: fd,
            })
           .then(function(response) {
            // $('#loader').hide(); 	
            if (response.status >= 200 && response.status < 300) {
               
                return response.text()
            }
            throw new Error(response.statusText)
        })
  .then(function(response) {

                try {
                    let json =  JSON.parse(response);
                    if(json['errores'] !== undefined){
                        let errores = json['errores'].length;
                        for(let a=0; a<errores;a++){
                        alertify.error(json['errores'][a]);
                    }
                    }
                 
                    if(json['advertencias'] !== undefined){
                        let advertencias = json['advertencias'].length;
                        for(let c=0; c<advertencias;c++){
                        alertify.warning(json['advertencias'][c]);
                    }
                    }
                    if(json['mensajes'] !== undefined){
                        let mensajes = json['mensajes'].length;
                        for(let b=0; b<mensajes;b++){
                        alertify.success(json['mensajes'][b]);
                    }
                    }


                } catch (error) {
                    console.log(error);
                    alertify.error('Error al subir xml')
                }
           
        })
        } 
        

function guardar_factura_compra_manual(accion){
                var str = $("#form3").serialize();

                $.ajax({
                    url: 'sql/compraManual.php',
                    data: str+"&txtAccion="+accion,
                    type: 'post',
                    success: function(data){
                        console.log(data);
                       if(data) {
                            try {
                                let response = JSON.parse(data);
            
                               console.log('mensajes: ', response.mensajes)
                               console.log('logs: ', response.logs)
                              
                                response.mensajes.forEach(m => {
                                    alertify.success(m);
                                })
  
                                document.getElementById('form3').reset();  
                                buscar_secuencial_compra(9);
                                 miUrl = "reportes/rptCompraManualMini.php?id_compra="+response.id_compra+'&id_proveedor='+response.id_proveedor;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
                            } catch(e) {
                                alert(e); // error in the above string (in this case, yes)!
                            }
                        }
                       
                        
                        
                        
                        if(data==3){
                            alertify.warning("Faltan datos");
                        }
                    }
                });
                

}

function area_predeterminada (id_centro_costo){
    $.ajax(
	{
		url: 'sql/centro_costo.php',
		data: "txtAccion=7&id_centro_costo="+id_centro_costo,
		type: 'post',
		success: function(data)	{
		    let response= data.trim();
            if(response==1){
                alertify.success('Area de compra selecionada como predeterminada correctamente.');
            }else{
                alertify.error('Error al selecionar como predeterminada el area de compra.');
            }
            listarCentroCostos();
		}
	});
}

function mostrar_grilla_reembolso(){
let tipo = document.getElementById('txtTipoComprobante').value;
let div = document.getElementById('div_reembolso');
    if(tipo==41){
        div.style="display:block";
    }else{
        div.style="display:none";
    }
}
var contadorRe=1;

function limpiarFilasReembolso(fila){
    document.getElementById('txtCedulaReembolso'+fila).value='';
    document.getElementById('txtTipoProveedor'+fila).value='01';
    document.getElementById('txtTipoDocumento'+fila).value='01';
    document.getElementById('txtEstablecimientoReembolso'+fila).value='';
    document.getElementById('txtEmisionReembolso'+fila).value='';

    document.getElementById('txtSecuencialReembolso'+fila).value='';
    document.getElementById('txtNumeroAutorizacion'+fila).value='';
}

function AgregarFilasReembolso(){
    
    cadena = "";
    cadena = cadena + "<div class='form-group '>";
    cadena = cadena + "<div class='input-group mt-1 bg-light'>";
    
    cadena = cadena + " <a  style='width:5% '  onclick=\"limpiarFilasReembolso("+contadorRe+");\" title='Limpiar fila' class='btn btn-outline-secondary fa fa-window-close'></a>     ";  

    cadena = cadena + "<input   type='text' id='txtCedulaReembolso"+contadorRe+"' name='txtCedulaReembolso"+contadorRe+"' class='form-control '   autocomplete='off'  placeholder='C&eacute;dula/R.U.C.'style='width:7% ' />   ";

    cadena = cadena + "<input type='text' style='width:5% ' class='form-control'  autocomplete='off'  id='txtCodigoPais"+contadorRe+"'  name='txtCodigoPais"+contadorRe+"' value='593' readonly  >";

    cadena = cadena + "<select style='width:10%' id='txtTipoProveedor"+contadorRe+"' name='txtTipoProveedor"+contadorRe+"'  class='form-select' ><option value='01'>Persona natural</option><option value='02'>Sociedad</option></select>";

    cadena = cadena + "<select style='width:10%' id='txtTipoDocumento"+contadorRe+"' name='txtTipoDocumento"+contadorRe+"'  class='form-select' ><option value='01'>FACTURA</option><option value='03'>LIQUIDACI&Oacute;N DE COMPRA DE BIENES Y PRESTACI&Oacute;N DE SERVICIOS</option><option value='04'>NOTA DE CR&Eacute;DITO</option><option value='05'>NOTA DE D&Eacute;BITO</option><option value='06'>GU&Iacute;A DE REMISI&Oacute;N</option><option value='07'>COMPROBANTE DE RETENCI&Oacute;N</option></select>";

    cadena = cadena + "<input type='text' maxlength='3' style='width:7%' style='text-align: center; ' id='txtEstablecimientoReembolso"+contadorRe+"' name='txtEstablecimientoReembolso"+contadorRe+"' class='form-control'  autocomplete='off' >";
    
    cadena = cadena + "<input type='text' style='text-align: right;width:5%' class='form-control'  id='txtEmisionReembolso"+contadorRe+"' name='txtEmisionReembolso"+contadorRe+"' autocomplete='off' maxlength='3' >";

    cadena = cadena + "<input type='text' style='text-align: right;width:5%' class='form-control'  id='txtSecuencialReembolso"+contadorRe+"' name='txtSecuencialReembolso"+contadorRe+"' autocomplete='off' maxlength='9' >";

    cadena = cadena + "<input type='text' style='text-align: right;width:5%' class='form-control'  id='txtFechaReembolso"+contadorRe+"' name='txtFechaReembolso"+contadorRe+"' autocomplete='off' onclick=\"displayCalendar(txtFechaReembolso"+contadorRe+",'yyyy-mm-dd',this)\"  >";

    cadena = cadena + "<input type='text' style='text-align: right;width:5%' maxlength='49' class='form-control'  id='txtNumeroAutorizacion"+contadorRe+"' name='txtNumeroAutorizacion"+contadorRe+"' autocomplete='off'  >";

   
    
    cadena = cadena + "</div>";
    // <div class=' border  py-2 form-control celeste ' style='width:5%'>C¨®digo </div>
    cadena = cadena + "</br><div id='tblBodyCompensacion"+contadorRe+"' ><div class='form-group'><input name='txtContadorFilasCompensacion"+contadorRe+"' id='txtContadorFilasCompensacion"+contadorRe+"' type='hidden' value='0'><div class='input-group mt-1'> <div  style='width:5%'> </div><a style='width:5%' onclick='AgregarFilasImpuestos("+contadorRe+");' class='btn btn-outline-secondary celeste'><i class='fa fa-plus' aria-hidden='true'></i></a><div class=' border  py-2 form-control celeste ' style='width:5%'>C&oacute;digo impuesto</div><div class=' border  py-2 form-control celeste' style='width:5%'>C&oacute;digo Porcentaje</div><div style='width:5% ' class=' border  py-2 form-control celeste'>Tarifa</div><div style='width:5% ' class=' border  py-2 form-control celeste'>Base imponible</div><div style='width:5% ' class=' border  py-2 form-control celeste'>Impuesto</div></div></div>";
    cadena = cadena + "</div>";

    cadena = cadena + "</div>";
    
    document.getElementById('txtContadorFilasReembolso').value=contadorRe;
    contadorRe++;
    $("#tblBodyReembolso").append(cadena);

}
function limpiarFilasImpuestos(id,fila){
    document.getElementById('txtCodigoImpuestoCompensacion'+id+'_'+fila).value='2';
    document.getElementById('txtPorcentajeCompensacion'+id+'_'+fila).value='0';
    document.getElementById('txtTarifaCompensacion'+id+'_'+fila).value='';
    document.getElementById('txtBaseImponible'+id+'_'+fila).value='';
    document.getElementById('txtImpuestoCompensacion'+id+'_'+fila).value='';   
}
function AgregarFilasImpuestos(id_reembolso){
    let contadorR = document.getElementById('txtContadorFilasCompensacion'+id_reembolso).value;
    contadorR++;
    cadena = "";
    cadena = cadena + "<div class='form-group '>";
    cadena = cadena + "<div class='input-group mt-1 bg-light'>";
    
    cadena = cadena + "<div  style='width:5%'> </div> <a  style='width:5% '  onclick=\"limpiarFilasImpuestos("+id_reembolso+","+contadorR+");\" title='Limpiar fila' class='btn btn-outline-secondary fa fa-window-close'></a>     ";  

    // cadena = cadena + "<input   type='text' id='txtCodigoCompensacion"+id_reembolso+"_"+contadorR+"' name='txtCodigoCompensacion"+id_reembolso+"_"+contadorR+"' class='form-control '   autocomplete='off'  placeholder='C&oacute;digo'style='width:5% ' />   ";

    cadena = cadena + "<select  style='width:5% ' class='form-select'   id='txtCodigoImpuestoCompensacion"+id_reembolso+"_"+contadorR+"'  name='txtCodigoImpuestoCompensacion"+id_reembolso+"_"+contadorR+"' ><option value='2'>IVA</option><option value='3'>ICE</option><option value='5'>IRBPNR</option></select>";

    cadena = cadena + "<select  style='width:5% 'id='txtPorcentajeCompensacion"+id_reembolso+"_"+contadorR+"'  name='txtPorcentajeCompensacion"+id_reembolso+"_"+contadorR+"'  class='form-select   bg-white' ><option value='0'>0%</option><option value='2'>12%</option><option value='3'>14%</option><option value='6'>No Objeto de Impuesto</option><option value='7'>Exento de IVA</option><option value='8'>IVA diferenciado</option></select>";

    cadena = cadena + "<input type='text' maxlength='10' style='width:5%' style='text-align: center; ' id='txtTarifaCompensacion"+id_reembolso+"_"+contadorR+"' name='txtTarifaCompensacion"+id_reembolso+"_"+contadorR+"' class='form-control'  autocomplete='off' >";
    
    cadena = cadena + "<input type='text' style='text-align: right;width:5%' class='form-control'  id='txtBaseImponible"+id_reembolso+"_"+contadorR+"' name='txtBaseImponible"+id_reembolso+"_"+contadorR+"' autocomplete='off'  >";

    cadena = cadena + "<input type='text' style='text-align: right;width:5%' class='form-control'  id='txtImpuestoCompensacion"+id_reembolso+"_"+contadorR+"' name='txtImpuestoCompensacion"+id_reembolso+"_"+contadorR+"' autocomplete='off'  >";

    cadena = cadena + "</div>";

    cadena = cadena + "</div>";
    
    document.getElementById('txtContadorFilasCompensacion'+id_reembolso).value=contadorR;
    contadorR++;
    
    $("#tblBodyCompensacion"+id_reembolso).append(cadena);

}
 function validacion_interna_anticipo(funcion,saldo,saldosTipos){
	eval(funcion);
	txtValorS1.value=saldo;
    var inputElement = document.getElementById("txtValorS1");
    inputElement.addEventListener("input", function() {

    let txtDescPagoS1 = document.getElementById("txtDescPagoS1").value;
    let txtTipo11 = document.getElementById("txtTipoP1").value;
    let txtValorS1 = inputElement.value;
  
    if (txtDescPagoS1.trim() !== '' && txtTipo11 === 'Anticipo') {
        if (parseFloat(txtValorS1 ) > parseFloat(saldo)) {
            document.getElementById("txtValorS1").value= saldo;
        alertify.error('El valor ingresado no puede ser mayor a la suma de saldos de las cuentas por cobrar del cliente.');
        }
    }
    });
  }

</script>

<script>
  function cargar_formas (saldo=0){
    let id_proveedor = document.getElementById('textIdProveedor').value;
    let sumatoriaSaldo = saldo;
$.ajax(
	{
		url: 'sql/cuentas_por_cobrar.php?',
		data: "txtAccion=7&id_proveedor="+id_proveedor+'&sumatoriaSaldo='+sumatoriaSaldo,
		type: 'post',
		success: function(data)	{
		   try {
        let response = JSON.parse(data);

        if(response.cantidad_anticipos == 0){
            document.getElementById('formulario_anticipos').innerHTML = 
          '<strong><p style="color:red">No existe anticipo creado de tipo compras es necesario crear uno para agregarlo como forma de pago.</p></strong>';

         
        }else{
          document.getElementById('formulario_anticipos').innerHTML = 
          response.tabla;
        }



       
            
       } catch (error) {
        console.warn(error)
       }
		   
		}
	});
}


function validar_saldo_anticipo (fila){
  let limite = parseFloat(document.getElementById('limite_valor_anticipo'+fila).value);
  let celda_validar = parseFloat(document.getElementById('apagar_valor_anticipo'+fila).value);
  if(celda_validar > limite){
    document.getElementById('apagar_valor_anticipo'+fila).value = limite;
    alertify.error('La cantidad selecionada supera la cantidad disponible para este tipo de anticipo.');
  }else{
    document.getElementById('apagar_valor_anticipo'+fila).value = celda_validar;
  }
  calcular_insertar_forma_pago_anticipo();
}
function calcular_insertar_forma_pago_anticipo(){
  let filas = parseInt(document.getElementById('cantidadFilasAnticipos').value);
  let sumaTotal = 0;
  let a=0;
  for( a=0; a<filas; a++){
    let celda_validar = parseFloat(document.getElementById('apagar_valor_anticipo'+a).value);
    sumaTotal = sumaTotal + celda_validar;
  }

  existeFPanticipo = false;
  filaExiste = '';
  let filaFp =5;
  for(let b=1; b<=filaFp ; b++){
    if(document.getElementById('txtTipoP'+b).value=='Anticipo' && existeFPanticipo==false){
      existeFPanticipo = true;
      filaExiste= b;
    }
  }
  if( filaExiste!=''){
    document.getElementById('txtValorS'+filaExiste).value  =sumaTotal;
  }else{
    let funcion = document.getElementById("funcionAnticipo").value;
    if(sumaTotal!=0){
         eval(funcion) 
    }
  

   for(let b=1; b<=filaFp ; b++){
    if(document.getElementById('txtTipoP'+b).value=='Anticipo' && existeFPanticipo==false){
      existeFPanticipo = true;
      filaExiste= b;
    }
  }
  if( filaExiste!=''){
    document.getElementById('txtValorS'+filaExiste).value  =sumaTotal;
  }
   
  }

}

</script>
 
</html>
