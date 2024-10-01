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
?>
<html>
	<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Kardex</title>

    <!-- STAR estilo para la plantilla  -->

    <link rel="stylesheet" type="text/css" media="screen" href="css/960.css" /><!--panel derecho-->
    <link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" /><!--cuerpo de la pagina-->
    <link rel="stylesheet" type="text/css" href="css/style.css" media="screen" /><!--fondo y color-->


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
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="shortcut icon" href="images/logo.png">

</head>
<body onload="listar_kardex();">
    
<?php      {        include("barraCelular-Escritorio.php");      }   ?>   
 <div class="page-wrapper">
 <div class="page-container2">     
 <div class="section__content section__content--p30"  >
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg">
                                    <div class="card-header" style="margin-top:7%">
                                        <strong><center><H4>.:: KARDEX ::.</H4></center></strong>
                                        <small> <?php echo $sesion_empresa_nombre; ?></small>
                                    </div>
                                <div class="card">
                                    <div class="card-body">                 
    <form name="frmKardex" id="frmKardex" method="post" action="javascript: listar_kardex();">
        
        <div class='socialMiddle2'>
            

        <div class='path'></div>

        <table class="table" >
            
            <tbody>                                            
                <tr>
                    <td >
                        <label for='name'><strong class='leftSpace'>Producto:</strong></label>
                        <input type="hidden" id="txtIdProducto" name="txtIdProducto" value="" />
                    </td>
                    <td>
                        
                        <input type='search' tabindex='1' size='22' id='txtNombre' class='text_input10' name='txtNombre' required="required" title='Ingrese el nombre del producto' autocomplete="off"
                        maxlength="30" placeholder="Buscar producto" onclick="lookup13(this.value);" onKeyUp="lookup13(this.value);" onBlur="fill13();" onchange="limpiar_id('txtIdProducto')" />
                        
                    <div class="suggestionsBox2" id="suggestions13" >
                        
                    <!--    <img src="images/Arrow.png" style="position: relative; top: -10px; left: 100px;" alt="upArrow" /> -->
                        
                        <div class="suggestionList2" id="autoSuggestionsList13" >
                            
                                &nbsp;
                        </div>
                    </div>
                    </td>
                    <td><label for='name' ><strong class='leftSpace'>Fecha desde: </strong></label></td>
                    <td><input type='text' tabindex='1' size='22' id='txtFechaIngreso' class='text_input10' name='txtFechaIngreso' required="required" title='Ingrese una fecha' placeholder="Fecha" autocomplete="off" maxlength="10" onClick="displayCalendar(txtFechaIngreso,'yyyy-mm-dd',this)" onchange="validaFechas(txtFechaIngreso.value, txtFechaSalida.value);"/>
                    <div id="mensajefecha_desde" ></div></td>
                    <td ><label for='name' ><strong class='leftSpace'>Fecha hasta: </strong></label></td>
                    <td><input type='text' tabindex='1' size='22' id='txtFechaSalida' class='text_input10' name='txtFechaSalida'
                    required="required" title='Ingrese una fecha' placeholder="Fecha" autocomplete="off" maxlength="10" 
                    onClick="displayCalendar(txtFechaSalida,'yyyy-mm-dd',this)" onchange="validaFechas(txtFechaIngreso.value, txtFechaSalida.value);"/>
                   
                    <div id="mensajefecha_hasta" ></div></td>
                    
                </tr>
           
                <tr>
                    
				
					
					
                </tr>
                <tr>                    
                    <td>
                        <center><input type="submit" value='Buscar' tabindex='5' id='submit' onClick="" class="btn btn-primary" name='btnBuscar' align="right" /></center>
                    </td>
                    	<td> 
					<center>	<input  type="button" tabindex="5" name="submit" value="Imprimir Kardex" id="" class="btn btn-primary" align="right" onclick="javascript: reporteKardex();" />	</center>		   
					</td>
					<td>
					<center>	<input type="button" tabindex="5" name="submit" value="Devolucion" id="" class="btn btn-primary" align="right" onClick="href:dev_compra2(txtIdProducto,txtNombre);" /></center>		
					
					</td>
                </tr>
            </tbody>
        </table>
        
                        <label style="visibility:hidden" for='name' type="hidden" width="90"><strong class='leftSpace'>M&eacute;todo: </strong></label></td>
                  
                        <select id="cmbMetodo"  name="cmbMetodo" class='text_input1' style="visibility:hidden">
                            <option>PROMEDIO PONDERADO</option>                            
                        </select>
                 
             
    </div>                
</form>
                <br/>
             

                <div id="div_listar_kardex"></div>
                <div id="div_oculto" style="display: none;"></div>

            </div>	<!-- close contactForm -->
                <!-- END CONTACT FORM -->
        </div>	<!-- end #contentLeft .grid_10 -->
            <!-- END LEFT CONTENT -->
             
           <?php //include("panelKardex.php"); ?>

         </div>	<!-- end #content -->
    </div>	<!-- end #content-wrap -->
    <!-- END PAGE CONTENT -->

</div>
        
<?php //include("bag.php"); ?> 

</body>

<script type="">
//  '    reporteKardex
function reporteKardex(){
alert('aaaa');
    miUrl = "reportes/rptKardex.php?txtFechaIngreso="+document.getElementById("txtFechaIngreso").value+"&txtFechaSalida="+document.getElementById("txtFechaSalida").value+"&txtIdProducto="+document.getElementById("txtIdProducto").value+"&cmbMetodo="+document.getElementById("cmbMetodo").value;
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
    var respuesta1 = confirm("Seguro desea eliminar esta cuenta?");
    if (respuesta1){
        $.ajax({
            url: 'sql/kardex.php',
            data: 'id_cuenta=' + id_cuenta+"&accion="+accion,
            type: 'post',
            success: function(data){
                if(data!=""){
                    alert(data);
                }

            }

        });
    }
}
</script>

</html>