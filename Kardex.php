<?php

	require_once('ver_sesion.php');
	//Start session
	session_start();
		
	//Include database connection details
	require_once('conexion.php');
	 $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
	  $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION['sesion_id_periodo_contable'];
    $sesion_empresa_nombre = $_SESSION['sesion_empresa_nombre'];
    $date = date("Y-m-d ");
?>
<html lang="es-ES">
    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Kardex</title>
    <script src="https://kit.fontawesome.com/7816c50242.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">  
    <script src="librerias/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="librerias/bootstrap/css/style.css"> 

    <!-- START ESTILOS Y CLASES PARA AJAX -->
    <script language="javascript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.blockUI.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.validate.1.5.2.js"></script>
    <script language="javascript" type="text/javascript" src="js/index.js"></script>

    <!--validaciones de los campos -->
    <script type="text/javascript" src="js/validaciones.js"></script>

    <!--validaciones de los campos -->
    <script type="text/javascript" src="js/busquedas.js"></script>
	<script type="text/javascript" src="js/compras.js"></script> 

    <!-- estilos para tablas -->
    <link href="css/listadoTablas.css" rel="stylesheet" type="text/css" />

    <!-- calendario -->
    <link type="text/css" rel="stylesheet" href="css/calendario.css" media="screen"/>
    <script language="javascript" type="text/javascript" src="js/calendario.js"></script>

    <!--END estilo para  el menu -->
	

<link rel="shortcut icon" href="images/logo.png">

</head>

<body onload="listar_kardex();">

    
    <div class="wrapper d-flex align-items-stretch celeste">
    
    <nav id="sidebar" ><?php  {include("menusNiveles.php");      }   ?></nav>
    
    <div id="content"  class="p-0 m-0">
    
    <div class="row m-0"><?php  {include("header.php");      }   ?>  </div>
 
    
    

        
    <form name="frmKardex" id="frmKardex" method="post" action="javascript: listar_kardex();" >

    <div class="row  m-0 bg-white p-3">  
    
        <div class="col-lg-3">
            <label>Producto:</label>
             <input type="hidden" id="txtIdProducto" name="txtIdProducto" value="" />
             <input class="form-control"  type='search' tabindex='1' size='22' id='txtNombre'  name='txtNombre' required="required" title='Ingrese el nombre del producto' autocomplete="off" 
             maxlength="30" placeholder="Buscar producto" onclick="lookup13(this.value);listar_kardex()" onKeyUp="lookup13(this.value);" onChange="javascript:listar_kardex()" onBlur="fill13();" onchange="limpiar_id('txtIdProducto')" />
             <div class="suggestionsBox2" id="suggestions13" >
             <div class="suggestionList2" id="autoSuggestionsList13" ></div>
            </div>
        </div>
         
        <div class="col-lg-2">
        <label>Bodega</label>
     	<select  tabindex="7" id="cmbBodegas" class="form-control" name="cmbBodegas" required="required" onChange="javascript: listar_kardex();">
     	     <option value="0">Todos</option>
				<?php
					$sqlp="Select * From bodegas where id_empresa='".$sesion_id_empresa."';";
					echo $sqlp;
					$resultp=mysql_query($sqlp);
					while($rowp=mysql_fetch_array($resultp))//permite ir de fila en fila de la tabla
					{ ?> <option value="<?=$rowp['id']; ?>"><?=$rowp['detalle']?></option> <?php   } ?>
				</select>
        </div>   
         <div class="col-lg-2">
                            <LABEL>Fecha desde:</LABEL>
                             <input class="form-control" type='text' tabindex='1' size='22' id='txtFechaIngreso'  name='txtFechaIngreso' required="required" value="<?php echo $date?>"
                             title='Ingrese una fecha' placeholder="Fecha" autocomplete="off" maxlength="10" onClick="displayCalendar(txtFechaIngreso,'yyyy-mm-dd',this)" 
                             onchange="validaFechas(txtFechaIngreso.value, txtFechaSalida.value);javascript: listar_kardex();"/>
                             <div id="mensajefecha_desde" ></div>
             
         </div>
         <div class="col-lg-2">
             <LABEL>Fecha hasta:</LABEL>
             <input type='text' tabindex='1' size='22' id='txtFechaSalida' class='form-control' name='txtFechaSalida' required="required" title='Ingrese una fecha' 
             placeholder="Fecha" autocomplete="off" maxlength="10" onClick="displayCalendar(txtFechaSalida,'yyyy-mm-dd',this)" value="<?php echo $date?>"
             onchange="validaFechas(txtFechaIngreso.value, txtFechaSalida.value);"/>
             <div id="mensajefecha_hasta" ></div>
         </div>
         <div class="col-lg-3">
             <LABEL>M&eacute;todo</LABEL>
             <select id="cmbMetodo"  name="cmbMetodo" class='form-control'><option>PROMEDIO PONDERADO</option></select>
         </div>
    </div>
                             
    <div class="row  m-0 bg-white p-3 mb-3 align-items-end"> 
       <div class="input-group ">
            <input type="submit" value='Buscar' tabindex='5' id='submit' onClick="" class="btn btn-outline-success  " name='btnBuscar' align="right" /> 
 
            <input  type="button" tabindex="5" name="submit" value="Imprimir Kardex" id="" class="btn btn-outline-warning  " align="right" onclick="javascript: reporteKardex();" />
       
            <!--<input type="button" tabindex="5" name="submit" value="Devolucion" id="" class="btn btn-outline-danger  " align="right" onClick="href:dev_compra2(txtIdProducto,txtNombre);" />-->
        </div>
    </div>
    
    </form>
             
    <div id="div_listar_kardex" class="row m-0 bg-white "></div>
    <div id="div_oculto" style="display: none;"></div>


         </div>
    </div>
       
<script src="librerias/bootstrap/js/main.js"></script>
</body>

<script type="">
//  '    reporteKardex
function reporteKardex(){
    miUrl = "reportes/rptKardex.php?txtFechaIngreso="+document.getElementById("txtFechaIngreso").value+"&txtFechaSalida="+document.getElementById("txtFechaSalida").value+"&txtIdProducto="+document.getElementById("txtIdProducto").value+"&cmbBodegas="+document.getElementById("cmbBodegas").value+"&cmbMetodo="+document.getElementById("cmbMetodo").value;
    window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
}

function abrirPdfFacturaCompra(id_compra){
    miUrl = "reportes/rptFacturaCompra.php?id_compra="+id_compra;
    window.open(miUrl,'facturaCompra','width=600, height=600, scrollbars=NO, titlebar=no');
}

function abrirPdfFacturaVenta(id_venta){

    miUrl = "reportes/rptFacturaVenta.php?id_venta="+id_venta;
    window.open(miUrl,'facturaVenta','width=600, height=600, scrollbars=NO, titlebar=no');
}
function EliminarDevolucion(id_cuenta,accion){
    var decidir = confirm("Seguro de reversar esta Devolución ?");
    if (decidir){
        $.ajax({
            url: 'sql/kardex.php',
            data: 'id_cuenta=' + id_cuenta+"&accion="+accion,
            type: 'post',
            success: function(data){
                if(data!=""){
                    listar_kardex();
                }
            }
        });
    }
}
</script>

</html>