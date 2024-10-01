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
?>

<html lang="es-ES">
    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Cuentas por Pagar</title>
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
    
    <script language="javascript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.blockUI.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.validate.1.5.2.js"></script>
        
    <script language="javascript" type="text/javascript" src="js/index.js"></script>

    <!-- estilos para el calendario -->
    <link type="text/css" rel="stylesheet" href="css/calendario.css" media="screen"/>
    
    <script language="javascript" type="text/javascript" src="js/calendario.js"></script>
    
    <!-- condominios -->
    <script language="javascript" type="text/javascript" src="js/condominios.js"></script> 
    <script language="javascript" type="text/javascript" src="js/ctasxpagar.js"></script>
    <script language="javascript" type="text/javascript" src="js/compras.js"></script>
    <style>
        .accordion {
          background-color: #eee;
          color: #444;
          cursor: pointer;
          padding: 18px;
          width: 100%;
          border: none;
          text-align: left;
          outline: none;
          font-size: 15px;
          transition: 0.4s;
        }
        
        .active, .accordion:hover {
          background-color: #ccc;
        }
        
        .panel {
          padding: 0 18px;
          background-color: white;
          max-height: 0;
          overflow: hidden;
          transition: max-height 0.2s ease-out;
        }
    </style>
        
    <!-- validaciones -->
    <script language="javascript" type="text/javascript" src="js/validaciones.js"></script>

    <script type="text/javascript">

    $(document).ready(function(){
        listar_cuentas_por_pagar();
    });

    </script>

    <!--END estilo para  el menu -->

</head>

<body onload="">
    <div class="wrapper d-flex align-items-stretch celeste">
    
    <nav id="sidebar" ><?php  {include("menusNiveles.php");      }   ?></nav>
    
    <div id="content"  class="p-0 m-0">
    
    <div class="row m-0"><?php  {include("header.php");      }   ?>  </div>
 <form name="frmCuentasPagar" id="frmCuentasPagar" method="post"  action="">
    <div class="row  mt-3 bg-white p-2">  
    
    <div id="mensaje1" ></div>
    
    
    
    

      <div class="input-group mb-3 ">
        
        <span class="input-group-text" id="basic-addon1">Buscar</span>
        <input type="text" name="txtBuscarCuentasPagar" id="txtBuscarCuentasPagar" value="" class="form-control "  onkeyup="listar_cuentas_por_pagar(1);"/>

  <div>
            <input type="radio" class="btn-check" name="switch_tipo_fecha" id="radio_emision" value="Emision" autocomplete="off"  onchange="listar_cuentas_por_pagar(1);">
            <label class="btn btn-outline-success" for="radio_emision">Fecha Emisi&oacute;n</label>
            
            <input type="radio" class="btn-check" name="switch_tipo_fecha" id="radio_vencimiento" value="Vencimiento" autocomplete="off" onchange="listar_cuentas_por_pagar(1);" checked>
            <label class="btn btn-outline-success" for="radio_vencimiento">Fecha Vencimiento/Pago</label>
        </div>
        <span class="input-group-text" id="basic-addon1">Fecha desde: </span>
        <input type="text" tabindex="1" id="txtFechaDesde" value="<?php echo date('Y')."-".date('m')."-"."01" ; ?>"   class="form-control " required="required" name="txtFechaDesde" onClick="displayCalendar(txtFechaDesde,'yyyy-mm-dd',this)" onchange="validaFechas(txtFechaDesde.value, txtFechaHasta.value);  listar_cuentas_por_pagar(1);" placeholder="Fecha inicio"  autocomplete="off"/>
        <div id="mensajefecha_desde" ></div>
        
        <span class="input-group-text" id="basic-addon1">Fecha hasta: </span>
        <input type="text" tabindex="2" id="txtFechaHasta" value="<?php echo date("Y-m-d",time()); ?>" class="form-control " required="required" name="txtFechaHasta" onClick="displayCalendar(txtFechaHasta,'yyyy-mm-dd',this)" onchange="validaFechas(txtFechaDesde.value, txtFechaHasta.value); listar_cuentas_por_pagar(1);" placeholder="Fecha tope"  autocomplete="off"/>
        <div id="mensajefecha_hasta" ></div>
        
    </div>
    
    <div class="input-group mb-3 ">    
        
        <span class="input-group-text" id="basic-addon1">Mostrar </span>
        <select name="criterio_mostrar" class="form form-control w-25" onchange="listar_cuentas_por_pagar(1);">
            <option value="10000000000000000">Todos</option> 
            <option value="5">5</option> 
            <option value="10">10</option> 
             <option value="50">50</option> 
            <option value="100">100</option> 
        </select>
        <span class="input-group-text" id="basic-addon1">Listado por: </span>
        
        <select name="listado_por" class="form form-control w-25" onchange="listar_cuentas_por_pagar(1);">
                <option value="Clientes">Clientes</option> 
                <option value="Facturas">Facturas</option> 
        </select>
       
        <a type="button" class="btn btn-outline-secondary"  id="botonSaldo" name="botonSaldo" onClick="href:registrarCtasxPagar()"/>
           Saldo Inicial 
        </a>   
        
        <a   type="button" class="btn btn-outline-secondary" id="btnNuevaFactura" name="btnNuevaFactura"  onClick="javascript:pdfCuentaPagar(<?php echo $sesion_id_empresa?>)"/>
            Reporte
        </a> 

        <a type="button"  class="btn btn-outline-secondary" id="btnNuevaFactura" name="btnNuevaFactura"  onClick="javascript:reporteExcelCuentasPagar()"/>
            Reporte Excel 
        </a> 

    	<div class="col-lg-2" >
            <select name="criterio_ordenar_por" style="display: none">
                <option value=" cuentas_por_cobrar.`id_cuenta_por_cobrar` ">Id</option>
            </select>
            <select name="criterio_orden" style="display: none">
                <option value=" asc ">asc</option>
                <option value=" desc ">desc</option>
            </select>
        </div>
    </div>
    
   
</div>
        
    <div class="row text-center p-3">
                
            <div class="col-lg-5 offset-lg-2">    
            
                <input type="radio" class="btn-check col" name="switch-four" id="Proveedores-outlined" value="1" autocomplete="off" checked onChange="comboAnticipos('P');botonSaldoInicial()">
                <label class="btn btn-outline-success" for="Proveedores-outlined">Proveedores</label>
                
                <input type="radio" class="btn-check col" name="switch-four" id="Clientes-outlined" value="2"  autocomplete="off" onChange="comboAnticipos('C');botonSaldoInicial()">
                <label class="btn btn-outline-success" for="Clientes-outlined">Clientes</label>
                
                <input type="radio" class="btn-check col" name="switch-four" id="Leads-outlined" value="3"  autocomplete="off" onChange="listar_cuentas_por_pagar();botonSaldoInicial()" >
                <label class="btn btn-outline-success" for="Leads-outlined">Leads</label>
                
                <input type="radio" class="btn-check col" name="switch-four" id="Empleados-outlined" value="4"  autocomplete="off" onChange="listar_cuentas_por_pagar();botonSaldoInicial()" >
                <label class="btn btn-outline-success" for="Empleados-outlined">Empleados</label>
            
            </div>
            
             <div class="col-lg-4 offset-lg-1">    
              
                 <input type="radio" class="btn-check col" name="switch-estado" id="Todos-outlined" value="Todos" autocomplete="off" checked onChange="listar_cuentas_por_pagar(1)">
                <label class="btn btn-outline-success" for="Todos-outlined">Todos</label>
      
            
                <input type="radio" class="btn-check col" name="switch-estado" id="Pendientes-outlined" value="Pendientes" autocomplete="off" checked onChange="listar_cuentas_por_pagar(1)">
                <label class="btn btn-outline-success" for="Pendientes-outlined">Pendientes</label>
                
                <input type="radio" class="btn-check col" name="switch-estado" id="Canceladas-outlined" value="Canceladas"  autocomplete="off" onChange="listar_cuentas_por_pagar(1)">
                <label class="btn btn-outline-success" for="Canceladas-outlined">Canceladas</label>
                
            
            </div>
                
                
                
             
            </div>
             <div id="div_listar_cuentas_por_pagar" class="bg-white rounded mx-5"></div>
           </form>  
           
                
           
        
        
        <div id="div_oculto" style="display: none;"></div>
        
    </div>
</div>
  	<script src="librerias/bootstrap/js/main.js"></script>    
    <script type="">  
        function pdfCuentaPagar(empresa){
            console.log(empresa);
             let str = $("#frmCuentasPagar").serialize();
            
             miUrl = "reportes/rptCuentaPagar.php?empresa="+empresa+"&"+str;
            window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
        }
        
         function botonSaldoInicial(){
        console.log('btnSaldoInicial')
        let boton = document.getElementById('botonSaldo');
        let checkbox = document.getElementById("radio-lead");
        if(boton && checkbox){
            if(checkbox.checked){
                boton.style.display='none';
            }else{
                boton.style.display='block';
            }
           
        }
    }
    const reporteExcelCuentasPagar=()=>{
    let str = $("#frmCuentasPagar").serialize();
    let miUrl = "reportes/excel_cuentas_pagar.php?"+str;
    console.log(miUrl);
window.open(miUrl);
} 
function verAnticipos(){
   
     
        let checkbox = document.getElementById("anticipo-outlined");
        let divAnt = document.getElementById("tipos_anticipos");
            if(checkbox.checked){
                divAnt.style.display='block';
            }else{
                divAnt.style.display='none';
            }
          
    }
function comboAnticipos(valor){
    codigo= valor;
    ajax3=objetoAjax();
    ajax3.open("POST", "sql/busquedas.php",true);
    ajax3.onreadystatechange=function() {
        if (ajax3.readyState==4) {
            var respuesta=ajax3.responseText;
            asignaAnticipos(respuesta);
        }
    }
    ajax3.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax3.send("accion=15&codigo="+codigo)
}

function asignaAnticipos(cadena){
     if(document.getElementById("tipo_anticipo") ){
          array = cadena.split( "?" );
    limite=array.length;
    cont=1;
    limpiaAnticipos();
    tipo_anticipo.options[0] = new Option("Seleccionar Anticipo","0");
    for(i=1;i<limite;i=i+2){
        tipo_anticipo.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
     }
   
    listar_cuentas_por_pagar(1);
}

function limpiaAnticipos()
{
    if(document.getElementById("tipo_anticipo") ){
        for (m=tipo_anticipo.options.length-1;m>=0;m--){
        tipo_anticipo.options[m]=null;
    } 
    }
} 
    </script>

</body>
</html>

