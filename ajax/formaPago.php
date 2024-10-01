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
<title>Formas de pago</title>
   
</head>

<body onload="">
        


      <div class="row">
          <a href="javascript: fn_cerrar();"><button type="button" class="close" ><span aria-hidden="true">&times;</span></button></a>
        <h4 class="modal-title" id="myModalLabel">Enlaces Contables</h4>
      </div>
      <div class="row">

            <div id="mensaje2" class=""></div>

            <form action="javascript: listar_formas_pago();" id="frmFormasPago" name="frmFormasPago" method="post">
                <input type="hidden" name="txtNumeroFila" id="txtNumeroFila" value="" />
                <div id="div_listar_forma_pagos"></div>
                
            </form>


        </div>


       
<script type="">

</script>

</body>

</html>