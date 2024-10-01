<?php

	//Start session
	session_start();

	require_once('ver_sesion.php');

	//Include database connection details
	require_once('conexion.php');

        $cookie_tipo = $_COOKIE['tipo_cookie'];
        $cookie_id_empleado = $_COOKIE['id_empleado_cookie'];

        $nombre = $_SESSION['sesion_nombre'];
        $apellido = $_SESSION['sesion_apellido'];
        $sesion_tipo = $_SESSION["sesion_tipo"];
        $sesion_id_empleado = $_SESSION["sesion_id_empleado"];
        $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
        $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
        $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
        $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];

        //PERMISOS AL MODULO ASIENTOS CONTABLES
        $sesion_bancos_guardar = $_SESSION['sesion_bancos_guardar'];
date_default_timezone_set('America/Guayaquil');
        
?>

<html lang="es-ES">
    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Conciliaci&oacute;n Bancaria</title>
     <script src="https://kit.fontawesome.com/7816c50242.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">  
    <script src="librerias/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="librerias/bootstrap/css/style.css"> 
    
    <script language="javascript" type="text/javascript" src="js/index.js"></script>

    <script language="javascript" type="text/javascript" src="js/funciones.js"></script>

     <!-- estilos para el listado de las tablas  -->
     <link href="css/listadoTablas.css" rel="stylesheet" type="text/css" />

       <!-- estilos para el calendario -->
    <link type="text/css" rel="stylesheet" href="css/calendario.css" media="screen"/>
    <script language="javascript" type="text/javascript" src="js/calendario.js"></script>
    
    <script type="text/javascript">

        $(document).ready(function(){
                
                

        });

    </script>

    <!--validaciones de los campos -->
    <script type="text/javascript" src="js/validaciones.js"></script>

    <!--END estilo para  el menu -->

</head>

<body onload="cargaCuentasBancos(1); ">
    
<div class="wrapper d-flex align-items-stretch celeste">
    <nav id="sidebar" ><?php  {include("menusNiveles.php");      }   ?></nav>
<div id="content"  >
    <header ><?php  {include("header.php");      }   ?>  </header>

        <div id="mensaje2"  class="row bg-white r-10 p-1 pt-1  mt-3  "></div>
        <form name="frmConciliacionBancaria" id="frmConciliacionBancaria" method="post" action="javascript: listar_bancos_CB(document.forms['frmConciliacionBancaria']);" >

        <div class="row pl-5 bg-white r-10 p-3 pt-1  mt-3  " >
         
             <input type="hidden" id="txtPermisosBancosGuardar" name="txtPermisosBancosGuardar" value="<?php echo $sesion_bancos_guardar; ?>" />
              <div class="path2"></div>
               
                
                <div class="col-lg-6">
                <label >Seleccione Banco</label>
                      <select class="form-control" name="cmbNombreCuentaCB"  id="cmbNombreCuentaCB" title="" onchange="cargarDetallesCuentaBancos(2, cmbNombreCuentaCB.value);" > </select>
                </div>    
                
                <div class="col-lg-6">
                    <label  > Seleccione: </label>
                    <select class="form-control" name="cmdEstado" id="cmdEstado" onchange="cargarDetallesCuentaBancos(2, cmbNombreCuentaCB.value);">
                    <option value="No Conciliado">No Conciliados</option>
                        <option value="Conciliado">Conciliados</option>
                    </select>
                </div>
               
               
                    <div class="col-lg-6">
                                    <label >C&oacute;digo: </label>
                                    <input  class="form-control" name="txtCodigoCB" type="search" id="txtCodigoCB"  />
                    </div>
                    <div class="col-lg-6">
                                    <label>Saldo Conciliado</label>
                                    <input class="form-control" name="txtSaldoConsolidacionCB" type="search" value="0.00" id="txtSaldoConsolidacionCB" />
                    </div>  
        </div>
        
        <?php
         $dominio = $_SERVER['SERVER_NAME'];
        if($dominio=='dcacorp.com.ec' or $dominio=='www.dcacorp.com.ec' or $dominio=='contaweb.ec' or $dominio=='www.contaweb.ec'){ 
            ?>
 <div class="row pl-5 bg-white r-10 p-3 pt-1  mt-3  " >
         
            
             
                
                <div class="col-lg-4">
                <label > Fecha Desde:</label>
                      <input  class="form-control" name="fechaDesdeInfo" type="text"  id="fechaDesdeInfo" onclick="displayCalendar(fechaDesdeInfo,'yyyy-mm-dd',this)" value= "<?php echo date('Y-m').'-01'?>"/>
                </div>   
                
                <div class="col-lg-4">
                <label > Fecha Hasta:</label>
                      <input  class="form-control" name="fechaHastaInfo"  type="text"  id="fechaHastaInfo" onclick="displayCalendar(fechaHastaInfo,'yyyy-mm-dd',this)"  value= "<?php echo date('Y-m-d')?>" />
                </div>   
                
            
                
                <div class="col-lg-4">
                <label >Valor Registrado:</label>
                      <input  class="form-control" name="valor_registrado"  id="valor_registrado"  />
                </div> 
                
            
              
        </div>
            <?php
        }
        ?>
        
        
       <div class="row pl-5 bg-white r-10 p-3 pt-1  mt-3  " >
                <div class="col-lg-2">
                                <input type="radio" name="opt" id="opt" value="Deposito" onchange="cargarDetallesCuentaBancos(2, cmbNombreCuentaCB.value);" />
                                <label for="txtDepositosCB"><strong class="leftSpace">Dep&oacute;sitos: </strong></label><br>
                                <input class="form-control"  name="txtDepositosCB" value="0.00" type="search" id="txtDepositosCB" placeholder="" title="" />
                 </div>
                 <div class="col-lg-2">
                                <input type="radio" name="opt" id="opt" value="TransferenciaC" onchange="cargarDetallesCuentaBancos(2, cmbNombreCuentaCB.value);" />
                                <label for="txtTransferenciasCB"><strong class="leftSpace">Transferencias Compra: </strong></label><br>
                                <input class="form-control"  name="txtTransferenciasComprasCB" value="0.00" type="search" id="txtTransferenciasComprasCB" placeholder="" title="" />
                 </div>
                 
                 <div class="col-lg-2">
                                <input type="radio" name="opt" id="opt" value="Transferencia" onchange="cargarDetallesCuentaBancos(2, cmbNombreCuentaCB.value);" />
                                <label for="txtTransferenciasCB"><strong class="leftSpace">Transferencias Ventas: </strong></label><br>
                                <input class="form-control"  name="txtTransferenciasVentasCB" value="0.00" type="search" id="txtTransferenciasVentasCB" placeholder="" title="" />
                 </div>
                 
                <div class="col-lg-2">
                                <input type="radio" name="opt" id="opt" value="Cheque" onchange="cargarDetallesCuentaBancos(2, cmbNombreCuentaCB.value);" />
                                <label for="txtChequesCB"><strong class="leftSpace">Cheques: </strong></label><br>
                                <input class="form-control"  name="txtChequesCB" value="0.00" type="search" id="txtChequesCB" placeholder="" title="" />
                </div>    
                <div class="col-lg-2">
                                <input type="radio" name="opt" id="opt" value="NotaCredito" onchange="cargarDetallesCuentaBancos(2, cmbNombreCuentaCB.value);"/>
                                <label for="txtNotaCreditoCB"><strong class="leftSpace">Nota de Cr&eacute;dito: </strong></label><br>
                                <input class="form-control"  name="txtNotaCreditoCB" value="0.00" type="search" id="txtNotaCreditoCB" placeholder="" title="" />
                </div>
                <div class="col-lg-2">
                                <input type="radio" name="opt" id="opt" value="NotaDebito" onchange="cargarDetallesCuentaBancos(2, cmbNombreCuentaCB.value);"/>
                                <label for="txtNotaDebito"><strong class="leftSpace">Nota de d&eacute;bito: </strong></label><br>
                                <input class="form-control" name="txtNotaDebitoCB" value="0.00" type="search" id="txtNotaDebitoCB" placeholder="" title="" />
                </div> 
                <div class="col-lg-1">
                                <label for="txtTodosCB"><strong class="leftSpace">Todos: </strong></label>
                                <input type="radio" name="opt" id="opt" checked value="Todos" onchange="cargarDetallesCuentaBancos(2, cmbNombreCuentaCB.value);"/>
                                <select name="criterio_mostrar" style="display: none"><option value="100000000000000000000">100000000000000000000</option></select>
                </div>
        </div>
        <div class="row pl-5 bg-white r-10 p-1 pt-1 mt-3  " id="div_listar_detallesCuentaBancos"></div>
         
        <div class="row pl-5  bg-white r-10 p-1 pt-1  mt-3  " >
                <a class="col-lg-2 r-10 p-2  mx-1 border btn btn-success" 
                style="text-decoration:none" onclick="conciliarCuenta(frmConciliacionBancaria);" 
                class="" name="btnConciliacion" id="btnConciliacion" >
                <p>Conciliar</p></a>
                
         
         <a href="reportesConciliacionBancaria.php?id=<?php echo $sesion_id_periodo_contable; ?> " class="col-lg-2 r-10 p-2  mx-1 border" style="text-decoration:none">
                <p>Reporte</p></a>
       
                
                
        </div>
        
       </form>
</div>
</div>
    <div id="div_oculto" class="caja" style="display: none;"></div>
	<script src="librerias/bootstrap/js/main.js"></script>   
	<script>
	function conciliarCuenta(frmConciliacionBancaria){ //

    console.log("frmConciliacionBancaria",frmConciliacionBancaria);
    var txtPermisosBancosGuardar = frmConciliacionBancaria.elements['txtPermisosBancosGuardar'].value;

    if(txtPermisosBancosGuardar == "No"){
        alert ("Usted No tiene permisos. \nConsulte con el Administrador.");
    }else{
        
        var numeroDeCuentasdeBancos = $('#txtNumeroFilas').val();

        var str = $("#frmConciliacionBancaria").serialize();
        
         $.ajax({
                        url: 'sql/bancos.php',
                        type: 'post',
                        data: str+"&accion=8",
                        success: function(data){
                            console.log(data);
                        }
                    });
                    
        $("input:checkbox").each(
            function() {
                if( $(this).is(':checked') ) {
                    //alert("El checkbox con valor " + $(this).val() + " está seleccionado");
                    var id_detalle_bancos1 = $(this).val();
                    console.log("id_detalle_bancos1",id_detalle_bancos1);
                    $.ajax({
                        url: 'sql/bancos.php',
                        type: 'post',
                        data: str+"&id_detalle_bancos="+id_detalle_bancos1+"&accion=4&numeroDeCuentasdeBancos="+numeroDeCuentasdeBancos,
                        // para mostrar el loadian antes de cargar los datos
                        beforeSend: function(){
                            //imagen de carga
                            $("#mensaje2").html("<p align='center'><img src='images/loading.gif' /></p>");
                        },
                        success: function(data){
                            console.log(data);
                            document.getElementById("mensaje2").innerHTML=data;
                            saldoTotalConciliado(3);
                            cargarDetallesCuentaBancos(2, cmbNombreCuentaCB.value);
                        }
                    });
                }else{
                    //alert("El checkbox con valor " + $(this).val() + " está de-seleccionado");
                    var id_detalle_bancos2 = $(this).val();
                    $.ajax({
                        url: 'sql/bancos.php',
                        type: 'post',
                        data: str+"&id_detalle_bancos="+id_detalle_bancos2+"&accion=5&numeroDeCuentasdeBancos="+numeroDeCuentasdeBancos,
                        // para mostrar el loadian antes de cargar los datos
                        beforeSend: function(){
                            //imagen de carga
                            $("#mensaje2").html("<p align='center'><img src='images/loading.gif' /></p>");
                        },
                        success: function(data){
                            document.getElementById("mensaje2").innerHTML=data;
                            saldoTotalConciliado(3);
                            cargarDetallesCuentaBancos(2, cmbNombreCuentaCB.value);
                        }
                    });
                }

            }
            );
        
    }

}


	</script>
</body>
</html>

