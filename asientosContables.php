<?php

    //Start session
	session_start();

	require_once('ver_sesion.php');
		
	//Include database connection details
	require_once('conexion.php');

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
    // echo $sesion_id_periodo_contable;
        
        //PERMISOS AL MODULO ASIENTOS CONTABLES
    $sesion_reportes_contables = $_SESSION['sesion_reportes_contables'];
    $sesion_asientos_contables_guardar = $_SESSION["sesion_asientos_contables_guardar"];
    $sesion_asientos_contables_modificar = $_SESSION["sesion_asientos_contables_modificar"];
    $sesion_asientos_contables_eliminar = $_SESSION["sesion_asientos_contables_eliminar"];
    
    	 // permiso a modulos
        $sesion_asientos_contables = $_SESSION["sesion_asientos_contables"];
        $sesion_plan_cuentas = $_SESSION["sesion_plan_cuentas"];
        $sesion_reportes_contables = $_SESSION["sesion_reportes_contables"];
        $sesion_bancos = $_SESSION["sesion_bancos"];
        $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];


     $numero_asiento="";
if(isset( $_GET["numero_asientox"])){
    $numero_asiento = $_GET["numero_asientox"];
    echo $numero_asiento;
    $a=0;
}else{
    try {

        $sqli="SELECT
         max(numero_asiento) AS max_numero_asiento,
         periodo_contable.`id_periodo_contable` AS periodo_contable_id_periodo_contable,
         periodo_contable.`fecha_desde` AS periodo_contable_fecha_desde,
         periodo_contable.`fecha_hasta` AS periodo_contable_fecha_hasta,
         periodo_contable.`estado` AS periodo_contable_estado,
         periodo_contable.`ingresos` AS periodo_contable_ingresos,
         periodo_contable.`gastos` AS periodo_contable_gastos,
         periodo_contable.`id_empresa` AS periodo_contable_id_empresa
        FROM
         `periodo_contable` periodo_contable INNER JOIN `libro_diario` libro_diario ON periodo_contable.`id_periodo_contable` = libro_diario.`id_periodo_contable`
         WHERE periodo_contable.`id_empresa` ='".$sesion_id_empresa."' GROUP BY periodo_contable.`id_periodo_contable` ;";
        
        $result=mysql_query($sqli);
        $numero_asiento=0;
        while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
        { 
           $numero_asiento=$row['max_numero_asiento']; 
           
        }  
           $numero_asiento++;
        
        }catch(Exception $ex) { echo "".$ex;  }
}


?>
				

<html lang="es-ES">
    
<head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Asientos Contables</title>
 
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
    <script src="librerias/bootstrap/js/main.js"></script>    
    
    <script type="text/javascript" src="js/validaciones.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<!--<script language="javascript" type="text/javascript" src="js/jquery-1.4.1.min.js"></script>  -->
    <script language="javascript" type="text/javascript" src="js/jquery.blockUI.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.validate.1.5.2.js"></script>
    
    
    <script language="javascript" type="text/javascript" src="js/index.js"></script>
	<script language="javascript" type="text/javascript" src="js/index_conta.js"></script>
	<script language="javascript" type="text/javascript" src="js/diarios.js"></script>
 
	    <!-- estilos para el calendario -->
    <link type="text/css" rel="stylesheet" href="css/calendario.css" media="screen"/>
    <script language="javascript" type="text/javascript" src="js/calendario.js"></script>

    <!-- estilos para agregar filas-->    
 
    <script type="text/javascript" src="js/centroCosto.js"></script>

    <script language="javascript" type="text/javascript" src="js/listadoFunciones.js"></script>
    <!--validaciones de los campos -->
    <script type="text/javascript" src="js/funciones.js"></script>

    <!-- para las busquedas desplegables -->
    <script language="javascript" type="text/javascript" src="js/busquedas.js"></script>
   
    <!--END estilo para  el menu -->


<script type="text/javascript">
  


    function funValidaDatosAS(accion, dml)
    {
        var debe=document.getElementById("txtDebeTotal").value;
        var haber=document.getElementById("txtHaberTotal").value;
        var txtDescripcion=document.getElementById("txtDescripcion").value;
        var txtContadorAsientosAgregados=document.getElementById("txtContadorAsientosAgregados").value;

        var txtFecha = document.getElementById("txtFecha").value;
        var txtNumeroAsiento = document.getElementById("txtNumeroAsiento").value;

        $('#txtAccion').val(accion);
        //var idCuenta=document.getElementById("txtIdCuenta").value;
        contadorFilas=document.getElementById("txtContadorFilas").value;
        numfilas = 0;
console.log("ASIENTOS 2===>",txtContadorAsientosAgregados);

        if (debe > haber)
        {
            alert ('El Debe y el Haber deben cumplir la partida doble.');
            dml.elements['txtDebeTotal'].focus();
            return false;
        }
        else if (debe < haber)
        {
            alert ('El Debe y el Haber deben cumplir la partida doble.');
            dml.elements['txtHaberTotal'].focus();
            return false;
        }
        else if (debe == "" && haber == "")
        {
            alert ('El Debe y el Haber estan vacios.');
            dml.elements['txtHaberTotal'].focus();
            return false;
        }
        else if (debe < 0 && haber <0)
        {
            alert ('El Debe y el Haber no pueden tener valores Nulos.');
            dml.elements['txtHaberTotal'].focus();
            return false;
        }
        else if (txtDescripcion == "")
        {
            alert ('La descripcion del asiento no pueden tener valores Nulos.');
            dml.elements['txtDescripcion'].focus();
            return false;
        
            
        }else if (txtContadorAsientosAgregados.trim() <= 1){
            alert ('No hay suficientes cuentas para guardar.');
            dml.elements['txtCodigo1'].focus();
            return false;
        }
        else if (txtFecha == ""){
            alert ('La fecha no puede estar vacia.');
            dml.elements['txtFecha'].focus();
            return false;
        }
        else if (txtNumeroAsiento == ""){
            alert ('El numero de Asiento no puede estar vacio.');
            dml.elements['txtNumeroAsiento'].focus();
            return false;
        }
        
        return true;
    }

</script>

<script type="text/javascript">
function saltar(e,id, accion) {

    // Obtenemos la tecla pulsada
    //(e.keyCode)?k=e.keyCode:k=e.which;
    var k = document.all ? e.which : e.keyCode;
    if(accion == 1){
        contFilas = $('#txtNumeroAsiento').val();
        limiteNumeroAsiento = $('#txtLimiteNumeroAsiento').val();

        if (k == 39){
            //flecha derecha
            //alert(contFilas+" - "+limiteNumeroAsiento);
            if(parseInt(contFilas) < limiteNumeroAsiento){                
                contFilas ++;
                $('#txtNumeroAsiento').val(contFilas);
                document.getElementById("cmbTipoComprobante").readOnly = true;
                lookup15(this.value,'', 6);
                
            }
            else{
                
            }
        }
        else if (k == 37){
            //flecha izquierda
            //document.frmAsientosContables.submit.disabled=true;
            if(parseInt(contFilas) > 1){
                contFilas --;
                $('#txtNumeroAsiento').val(contFilas);
                document.getElementById("cmbTipoComprobante").readOnly = true;
                lookup15(this.value,'', 6);
                
            }else{
                //mensaje = utf8_decode("Excedio el Lأ╦mite");  btnGuardar    submit
                //alert ("Excedio el L\u00EDmite");
            }
        }
        else if (k == 40){
        //flecha abajo

        }
        else if (k == 38){
        // flecha arriba

        }
    } 
     if (k == 13){
          // Si la tecla pulsada es enter (codigo ascii 13)           
            if(id=="btnGuardar")
            {                
                // Si la variable id contiene "submit" enviamos el formulario
                document.forms[0].submit();
            }else{                
                // nos posicionamos en el siguiente input
                //document.getElementById(id).focus();
                id.focus();
                //document.frmAsientosContables.id.focus();
            }          
          //document.forms[0].focus();
          //return false;       
      }    
   // return true;    
}

$(document).keyup(function(event){

        if(event.which==27) //27 escape
        {                  
            setTimeout("$('.suggestionsBox').hide();", 20); /* */
        }
    });

//window.onkeydown = compruebaTecla;

</script>

    <script type="text/javascript">
        $(document).ready(function(){
            for(i=1; i<= 5; i++){
                fn_agregar();
            }
            numeroComprobante(7, cmbTipoComprobante.value);
            
       });
    </script>
</head>



<body  onload="<?php  if(isset($_GET['numero_asientox'])){ ?>lookup15(<?php echo $_GET['numero_asientox']?>,'', 6); <?php } ?>" >
    
<div class="wrapper d-flex align-items-stretch celeste">
    
    <nav id="sidebar" ><?php  {include("menusNiveles.php");      }   ?></nav>
    
<div id="content"  class="p-0 m-0">
    
    <div class="row m-0"><?php  {include("header.php");      }   ?>  </div>
 
    

    
        <form name="frmAsientosContables" id="frmAsientosContables" method="post" action="">
            <!-- PERMISOS PARA EL MODULO ASIENTOS CONTABLES -->
            <input type="hidden" id="txtPermisosAsientosContablesGuardar" name="txtPermisosAsientosContablesGuardar" value="<?php echo $sesion_asientos_contables_guardar; ?>" />
            <input type="hidden" id="txtPermisosAsientosContablesModificar" name="txtPermisosAsientosContablesModificar" value="<?php echo $sesion_asientos_contables_modificar; ?>" />
            <input type="hidden" id="txtPermisosAsientosContablesEliminar" name="txtPermisosAsientosContablesEliminar" value="<?php echo $sesion_asientos_contables_eliminar; ?>" />
           
            <input type="hidden" id="txtAccion" value="" readonly class="text_input2" name="txtAccion" />
            
                   <!-- <div class="path2"></div>-->
          

      
        <div class="row  my-1 px-1">  

                <a class="col-lg-3 col-sm-2 text-decoration-none "  type="button"  id="btnNuevaFactura" name="btnNuevaFactura" onClick="javascript: comprobantes();" />
                    <div class="bg-white border">
                        <div class="my-icon3 rounded bg-white">
                            
                            <i class="fa fa-list-alt   mr-3 fa-2x"></i>
                            
                            Buscar Comprobante 
                        </div>
                    </div>    
                </a>
                
                <a  class="col-lg-3 col-sm-2 text-decoration-none "  type="button"  id="btnNuevaFactura" name="btnNuevaFactura" onClick="location='planCuentas.php'"/>
                <div class="bg-white border">
                <div class="my-icon3 rounded bg-white">
                    <i class="fa fa-list-ol mr-3 fa-2x"></i>
                        Plan de cuentas  
                </div></div>
                </a> 
                
                <a  class="col-lg-3 col-sm-2 text-decoration-none"  type="button"  id="libros" name="libros" onClick="javascript: librosContables()"/>
                <div class="bg-white border"><div class="my-icon3 rounded bg-white"><i class="fa fa-book mr-3 fa-2x"></i>Reportes Contables  </div></div></a>
                
                <a  class="col-lg-3 col-sm-2 text-decoration-none"  type="button"  id="libros" name="libros" onClick="location='diariosContables.php'"/>
                <div class="bg-white border"><div class="my-icon3 rounded bg-white"><i class="fa fa-book mr-3 fa-2x"></i>Listado   </div></div></a>
                

        </div>
    
            <div class="row mx-5 p-3 bg-white rounded ">
                
                <div class="col-lg-2"> 
                    <label for="txtNumeroComprobante" >N° Asiento.</label>
                    <input style="display: none;" type="hidden" id="txtIdLibroDiario" value="" class="form-control" name="txtIdLibroDiario" autocomplete="off" onClick="" />
                    <input style="display: none; " type="hidden" id="txtLimiteNumeroAsiento" value="<?php echo ''.$numero_asiento-1;?>" class="form-control" 
                    name="txtLimiteNumeroAsiento" autocomplete="off" />
                    <input  type="text"   id="txtNumeroAsiento" name="txtNumeroAsiento" value="<?php echo ''.$numero_asiento;?>" class="form-control " 
                    name="txtNumeroAsiento" title="N&uacute;mero de Asiento" autocomplete="off" required onKeyUp="lookup15(this.value,'', 6);" 
                    onKeyDown="saltar(event,this.form.txtDescripcion,'1')"  />    
                </div>
                
                <div class="col-lg-2"> 
                        <label for="txtNumeroComprobante" >Fecha</label>
                        <input type="text"  id="txtFecha" value="<?php echo date("Y-m-d"); ?>" 
                        class="form-control" name="txtFecha" placeholder="Ingrese la fecha de hoy" title="Fecha Actual" 
                        required autocomplete="off" onClick="displayCalendar(txtFecha,'yyyy-mm-dd',this)"/>
                </div>
                
                <div class="col-lg-2">
                        <label for="txtNumeroComprobante" >N° Comprobante</label>
                        <input style="display: none" type="text" id="txtIdPeriodoContable" class="form-control" name="txtIdPeriodoContable" value="<?php echo $sesion_id_periodo_contable;?>" />
                        <input type="text"  id="txtNumeroComprobante" class="form-control" name="txtNumeroComprobante" placeholder="N&uacute;mero de Comprobante" title="N&uacute;mero de Comprobante" autocomplete="off" required value="<?php echo $numero_comprobante;?>" /></td>
                </div>
                    
                <div class="col-lg-2">
                    <label for="txtNumeroComprobante" >Tipo Comprobante</label>
                    <select  name="cmbTipoComprobante" id="cmbTipoComprobante" class="form-select"  required="required" onChange="numeroComprobante(7, this.value);">
                            <option value="Diario" selected="selected">Diario</option>
                            <option value="Ingreso" >Ingreso</option>
                            <option value="Egreso" >Egreso</option>
                    </select>
                </div>
                
                 <div class="col-lg-2">    
                    
                    <label for="txtNumeroComprobante" >Centro de costos</label>
                    <select  name="cmbCentro" id="cmbCentro" class="form-select"  required="required" >
                        
                        <option value="0">Selecciona un centro de costo</option>
                        <?php
                $est2="Select * From centro_costo where empresa='".$sesion_id_empresa."';";
                $resultest2=mysql_query($est2);
                while($rowu=mysql_fetch_array($resultest2))
                     { ?>   <option value="<?=$rowu['id_centro_costo']; ?>"><?=$rowu['descripcion']; ?></option>  <?php  }  ?>
                     
                    </select>
                    
                </div> 
                
        </div>
        
        <div class="row mx-5 p-3 bg-white rounded ">
              <div class="col-lg-12"> 
                    <label for="txtDescripcion" >Descripci&oacute;n</label>
                    <textarea id="txtDescripcion" name="txtDescripcion" type="text" class="form-control" aria-required="true" aria-invalid="false"  
                    onKeyDown="saltar(event,this.form.txtCodigo1);"></textarea>
                </div>
        </div>
        
        
        
   

    
     <div class="row mx-5 p-3 mt-2 bg-white rounded ">
         
                 <table id="grilla">
                     <thead class="text-center">
                         <td style='width:15%'>
                             <label>C&oacute;digo</label>
                         </td>
                          <td style='width:45%'>
                             <label>Cuenta</label>
                         </td>
                          <td style='width:15%'>
                             <label>Debe</label>
                         </td>
                          <td style='width:15%'>
                             <label>Haber</label>
                         </td>
                          <td style='width:10%'>
                             <a id="" title="A&ntilde;adir fila" onClick="javascript: fn_agregar();"><span class="btn btn-success" aria-hidden="true">+</span></a>
                         </td>
                     </thead>
                     <tbody></tbody>
                     <tfoot>
                        <tr>
                            <td colspan="2" class="text-right pr-5">
                                <input type="hidden" id="txtContadorFilas" value="" readonly class="form-control" name="txtContadorFilas" />
                                <input type="hidden" id="txtContadorAsientosAgregados" value="0" readonly class="form-control" name="txtContadorAsientosAgregados" />
                                <label>Totales</label>
                             </td>
                              <td style='width:15%'>
                                  <input type="text" id="txtDebeTotal" style="text-align: right"  name="txtDebeTotal" value="" class="form-control required" />
                             </td>
                              <td style='width:15%'>
                                 <input type="text" id="txtHaberTotal" style="text-align: right" name="txtHaberTotal" value="" class="form-control required" />
                             </td>
                              <td style='width:10%'>
                             </td>
                         </tr>
                     </tfoot>
                 </table>
    </div> 
    
   <div class="row mx-5 p-3 mt-2 bg-white rounded " id="Bancos" style="display: none;" >    
   
        <input type="hidden" name="txtIdBancos" id="txtIdBancos" value="0" />
        <input type="hidden" name="txtIdDetalleBancos" id="txtIdDetalleBancos" value="0" />
        <div class="col-lg-3"><h6>Tipo Documento</h6>
            
            <select  name="cmbTipoDocumento" id="cmbTipoDocumento" class="form-control-lg form-control" onChange="cambioDetalle(this.value);cambioSecuencialCheque(this.value,8)">
                                    <option value="Cheque">Cheque</option>
                                    <option value="Deposito">Depósito</option>
                                    <option value="Nota de Credito">Nota de Crédito</option>
                                    <option value="Nota de Debito">Nota de Débito</option>
                                    <option value="Transferenciac">Transferencia Compra</option>
                                    <option value="Transferencia">Transferencia Venta</option>
            </select> 
            
        </div>  
        <div class="col-lg-3"><h6>Número Documento</h6>
            <!--<input tabindex="1" type="text" maxlength="50" id="txtNumeroDocumento"  class="form-control form-control-lg " name="txtNumeroDocumento" placeholder="Ingrese el numero documento" onBlur="noRepetirNumeroDocumento(this, 2);" onKeyup="noRepetirNumeroDocumento(this, 2);" onChange="" onClick="noRepetirNumeroDocumento(this, 2);"  autocomplete="off"/>-->
            <input tabindex="1" type="text" maxlength="50" id="txtNumeroDocumento"  class="form-control form-control-lg " name="txtNumeroDocumento" placeholder="Ingrese el numero documento"  autocomplete="off"/>

            <div id="noRepetirNumeroDocumento"></div>
        </div>
        <div class="col-lg-3"><h6>Fecha de Emisión</h6>
                <input type="text"  id="txtFechaEmision" value="" class="form-control form-control-lg" name="txtFechaEmision" placeholder="Ingrese la Fecha de Emisi&oacute;n" title="Fecha Emisi&oacute;n"  autocomplete="off" onClick="displayCalendar(txtFechaEmision,'yyyy-mm-dd',this)"/>
        </div>
        <div class="col-lg-3"><h6>Fecha de Vencimiento</h6>
            <input type="text"  id="txtFechaVencimiento" value="" class="form-control form-control-lg" name="txtFechaVencimiento" placeholder="Ingrese la Fecha de Vencimiento" title="Fecha Vencimiento"  autocomplete="off" onClick="displayCalendar(txtFechaVencimiento,'yyyy-mm-dd',this)"/>
        </div>
        <div class="col-lg-6"><div id="cambiaPalabra"></div>
            <input tabindex="1" type="text" maxlength="200" id="txtDetalleDocumento form-control-lg"  class="form-control " name="txtDetalleDocumento" onBlur=""  onKeyup="" onChange="" onClick=""  autocomplete="off"/>
        </div>
    </div>

    <div id="mensaje11" class="m-2"></div>
        
   <div class="row mx-5 p-3 mt-2 bg-white rounded " >
       <input type="hidden" id="planCuentaUnicoBanco" name="planCuentaUnicoBanco">
        <div class="col-lg-12">
                  <a style="visibility: hidden; width: 20%;font-size:15px" value="Eliminar" VALUE="ELIMINAR"   type="button"  id="btnEliminar"   class="btn btn-outline-danger  "    onClick="eliminar();" />Eliminar</a>
                  <a style="visibility: hidden; width: 20%;font-size:15px" value="Editar"   VALUE ="EDITAR"    type="button"  id="btnEditar"     class="btn btn-outline-warning  "   onClick="editar();" />Editar</a>
                  
                  <a style="width: 20%;font-size:15px"   type="button"   class="btn btn-outline-success  "   id="btnGuardar"  value="Guardar" name="btnGuardar"     onClick="guardar();">Grabar</a>
                  <a style="width  20%;font-size:15px"   type="button"   class="btn btn-outline-info  "    onclick = "location='asientosContables.php'" >Nuevo</a>
                  <a style="width: 20%;font-size:15px"   type="button"   class="btn btn-outline-warning  "  name="btnGuardar"      onClick="javascript: fn_agregar();" />Añadir Fila</a>
        </div>
    </div> 
    
            </div>
        </form>
    </div>
</section>

   
    <div id="div_oculto" style="display: none;"></div>
     

<script type=""> 

function guardar(){
    valor = funValidaDatosAS('1',document.forms['frmAsientosContables']);// retorna true o false
    if(valor == true){
        
        asientos_contables(frmAsientosContables);
    }
}

function editar(){
    valor = funValidaDatosAS('2',document.forms['frmAsientosContables']);// retorna true o false
    if(valor == true){
        asientos_contables(frmAsientosContables);
    }
}

function eliminar(){
    valor = funValidaDatosAS('3',document.forms['frmAsientosContables']);// retorna true o false
    if(valor == true){
        asientos_contables(frmAsientosContables);
    }
}

function cambioDetalle(){
    
    //$('#cambiaPalabra').html('aa');
    detalle = $('#cmbTipoDocumento').val();
    //alert(detalle);
    if(detalle == "Cheque"){
        $('#cambiaPalabra').html("Páguese A:");
    }
    if(detalle == "Deposito"){
        $('#cambiaPalabra').html("Realizado por:");
    }
    if(detalle == "Nota de Credito"){
        $('#cambiaPalabra').html("Detalle:");
    }
    if(detalle == "Nota de Debito"){
        $('#cambiaPalabra').html("Detalle:");
    }
}

  function excelBalanceComprobacion(){
       miUrl = "reportes/excelBalanceComprobacion.php?criterio_ordenar_por="+document.getElementById("criterio_ordenar_por").value+"&criterio_orden="+document.getElementById("criterio_orden").value+"&criterio_mostrar="+document.getElementById("criterio_mostrar").value+"&txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value;

   
window.open(miUrl);
    }
function excelEstadoResultados (){
     miUrl = "reportes/excelEstadoResultados.php?criterio_ordenar_por="+document.getElementById("criterio_ordenar_por").value+"&criterio_orden="+document.getElementById("criterio_orden").value+"&criterio_mostrar="+document.getElementById("criterio_mostrar").value+"&txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value;
window.open(miUrl);
} 
function excelEstadoSituacion (){
     miUrl = "reportes/excelEstadoSituacion.php?criterio_ordenar_por="+document.getElementById("criterio_ordenar_por").value+"&criterio_orden="+document.getElementById("criterio_orden").value+"&criterio_mostrar="+document.getElementById("criterio_mostrar").value+"&txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value;
window.open(miUrl);
} 
// excelEstadoSituacion

function excelLibroDiario(){

    miUrl = "reportes/excelLibroDiario.php?txtIdPeriodoContable="+document.getElementById("txtIdPeriodoContable").value+"&criterio_usu_per="+document.getElementById("criterio_usu_per").value+"&criterio_ordenar_por="+document.getElementById("criterio_ordenar_por").value+"&criterio_orden="+document.getElementById("criterio_orden").value+"&criterio_mostrar="+document.getElementById("criterio_mostrar").value+"&fecha_desde="+document.getElementById("txtFechaDesde").value+"&fecha_hasta="+document.getElementById("txtFechaHasta").value;
         console.log("miUrl",miUrl);
         window.open(miUrl);

}

function excelLibroMayor(){

    miUrl = "reportes/excelLibroMayor.php?txtIdPlanCuenta="+document.getElementById("txtIdPlanCuenta").value+"&criterio_ordenar_por="+document.getElementById("criterio_ordenar_por").value+"&criterio_orden="+document.getElementById("criterio_orden").value+"&criterio_mostrar="+document.getElementById("criterio_mostrar").value+"&txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value;

     window.open(miUrl);
    }

</script>



</body>

</html>