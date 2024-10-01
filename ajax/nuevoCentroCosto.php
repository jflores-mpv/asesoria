<?php
	require_once('../ver_sesion.php');

	//Start session
	session_start();	
	//Include database connection details
	require_once('../conexion.php');

    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
    $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
      $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
?>
<html>
<head>
<title>&Aacute;reas</title>
   
</head>

<body onload="">
    <div class="modal-header">
        <h3>&Aacute;reas</h3>
     <a href="javascript: fn_cerrar();" class="btn btn-lg fx-3 text-decoration-none " >Cerrar</a>
  </div>
  
  
  
        <div id="mensaje2" class=""></div>

             <form action="javascript: listarCentroCostos();" id="frmCentros" name="frmCentros" method="post">
                <input type="hidden" name="txtNumeroFila" id="txtNumeroFila" value="" />
                <div id="div_listar_centros"></div> 
             
          </form>


 
  <div class="col-lg-12 text-right">
    
  </div>
</body>

</html>