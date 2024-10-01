<?php

	require_once('../ver_sesion.php');

	//Start session
	session_start();
		
	//Include database connection details
	require_once('../conexion.php');

        $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
        $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
        $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
			
?>





<html>
<head>




    <!--validaciones de los campos -->
    <script type="text/javascript" src="js/validaciones.js"></script>

    <script language="javascript" type="text/javascript" src="js/index.js"></script>
	 <script language="javascript" type="text/javascript" src="js/index_conta.js"></script>
    
   <!-- estilos para el calendario -->
    <link type="text/css" rel="stylesheet" href="css/calendario.css" media="screen"/>
    <script language="javascript" type="text/javascript" src="js/calendario.js"></script>

    <!-- estilos para agregar filas-->    
    <script language="javascript" type="text/javascript" src="js/listadoFunciones.js"></script>
    
    <!-- estilos para el listado de las tablas  -->
    <link href="css/listadoTablas.css" rel="stylesheet" type="text/css" />

    <!--validaciones de los campos -->
    <script type="text/javascript" src="js/funciones.js"></script>

    <!-- para las busquedas desplegables -->
    <script language="javascript" type="text/javascript" src="js/busquedas.js"></script>
    
<title>Comprobantes</title>
   
</head>

<body onLoad="">

  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <a href="javascript: fn_cerrar();"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></a>
        <h4 class="modal-title" id="myModalLabel">Buscar comprobantes</h4>
      </div>
<div class="modal-body p-3">
    <div class="row" >
        <div class="col-lg-12"> 
            <div class="row" >
              <div class="col-lg-4">   
                   <label>Desde esta fecha:</label>
                   <input type="hidden" name="txtIdPeriodoContable" id="txtIdPeriodoContable" value="<?php echo $sesion_id_periodo_contable;  ?>"  />
                   <input class="form-control" type="text" tabindex="1" id="txtFechaDesde" value="<?php echo date("Y-m-d",time()); ?>"  required name="txtFechaDesde" onClick="displayCalendar(txtFechaDesde,'yyyy-mm-dd',this)" onChange="validaFechas(txtFechaDesde.value, txtFechaHasta.value);" placeholder="Fecha inicio"  autocomplete="off"/>
                   <div id="mensajefecha_desde" ></div>
              </div>  
              <div class="col-lg-4">
                  <label>Hasta esta fecha:</label>   
                  <input  class="form-control" type="text" tabindex="2" id="txtFechaHasta" value="<?php echo date("Y-m-d",time()); ?>"  required name="txtFechaHasta" onClick="displayCalendar(txtFechaHasta,'yyyy-mm-dd',this)" onChange="validaFechas(txtFechaDesde.value, txtFechaHasta.value);" placeholder="Fecha tope"  autocomplete="off"/>
                <div id="mensajefecha_hasta" ></div>
              </div> 
              <div class="col-lg-4">
                  <label>Número comprobante:</label>
                  <input  class="form-control" tabindex="3" name="txtComprobanteNumero" type="search" id="txtComprobanteNumero" required placeholder="Buscar n&uacute;mero de comprobante" onKeyPress="return soloNumeros(evt)"/>
              </div>  
            </div>
        
        </div>     
    </div>
</div>
      <div class="modal-footer2 p-3">
          <div class="row">
            <div class="col-lg-4">
                    <input  type="button" value="Comprobante de Diario" id="btnDiario" class="btn btn-info" name="btnDiario" onClick="javascript: comprobanteDiario();" />
            </div>
            <div class="col-lg-4">
                    <input   type="button"  value="Comprobante de Ingreso" id="btnIngreso" class="btn btn-info" name="btnIngreso" onClick="javascript: comprobanteIngreso();" />
            </div>
            <div class="col-lg-4">
                    <input  type="button"  value="Comprobante de Egreso" id="btnEgreso" class="btn btn-info" name="btnEgreso" onClick="javascript: comprobanteEgreso();" />
            </div>   
            </div>
      </div>
    
  </div>
<div id="div_listar_comprobantes">


       
<script type="">

function comprobanteDiario(){

miUrl = "reportes/rptComprobanteDiario.php?txtComprobanteNumero="+document.getElementById("txtComprobanteNumero").value+"&fecha_desde="+document.getElementById("txtFechaDesde").value+"&fecha_hasta="+document.getElementById("txtFechaHasta").value;
//miUrl = "reportes/rptComprobanteDiario.php";
 window.open(miUrl,'noimporta','width=600, height=500, scrollbars=NO, titlebar=no');
}

function comprobanteIngreso(){

miUrl = "reportes/rptComprobanteIngreso.php?txtComprobanteNumero="+document.getElementById("txtComprobanteNumero").value+"&fecha_desde="+document.getElementById("txtFechaDesde").value+"&fecha_hasta="+document.getElementById("txtFechaHasta").value;

 window.open(miUrl,'noimporta','width=600, height=500, scrollbars=NO, titlebar=no');
}

function comprobanteEgreso(){

miUrl = "reportes/rptComprobanteEgreso.php?txtComprobanteNumero="+document.getElementById("txtComprobanteNumero").value+"&fecha_desde="+document.getElementById("txtFechaDesde").value+"&fecha_hasta="+document.getElementById("txtFechaHasta").value;

 window.open(miUrl,'noimporta','width=600, height=500, scrollbars=NO, titlebar=no');
}
/*
function abrirPdfComprobante(id){

miUrl = "reportes/rptComprobante.php?txtIdComprobante="+id+"&txtIdPeriodoContable="+document.getElementById("txtIdPeriodoContable").value+"&nombre_sistema="+document.getElementById("txtNombreSistema").value;

 window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
}
*/
</script>

</body>

</html>