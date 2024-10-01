<?php
	require_once('../ver_sesion.php');

	//Start session
	session_start();
		
	//Include database connection details
	require_once('../conexion.php');
	   $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
	
?>

<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">  
<title>Impuestos</title>

	<link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">  
	<link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/alertify.css">
	<link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/themes/default.css">
    <link rel="stylesheet" type="text/css" href="librerias/select2/css/select2.css">
    <link rel="stylesheet" href="librerias/bootstrap/css/style.css"> 
	<script src="librerias/bootstrap/js/bootstrap.js"></script>
	<script src="librerias/alertifyjs/alertify.js"></script>
    <script src="librerias/select2/js/select2.js"></script>

<script type="">
    $(document).ready(function(){
        listar_impuestos();
        mostrarPlanCuentas(14);
    });
</script>

</head>


<body onload="">

  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <a href="javascript: fn_cerrar();"><button type="button" class="close" ><span aria-hidden="true">&times;</span></button></a>
        <h4 class="modal-title" id="myModalLabel">COBRAR CUENTA</h4>
      </div>
      <div class="modal-body">

        <form name="frmImpuestos" id="frmImpuestos" method="post" action="javascript: listar_impuestos();">
            <div id="mensajeImpuestos" ></div>
            <div class="row">
            <h3>Seleccione cuenta contable del IVA</h3>
            <select  tabindex="7" id="cmbCuentaContableI" class="form-control" name="cmbCuentaContableI" required="required">
                <?php
                $sqlp="Select id_plan_cuenta,codigo,nombre From plan_cuentas where id_empresa='".$sesion_id_empresa."';";
			    $resultp=mysql_query($sqlp);
                 while($rowp=mysql_fetch_array($resultp))//permite ir de fila en fila de la tabla
                     { ?> <option value="<?=$rowp['id_plan_cuenta']; ?>"><?=$rowp['codigo'].' '.$rowp['nombre']; ?></option> <?php   } ?>
            </select>		
            </div>
            <div class="row">
                <input type="hidden" name="txtNumeroFila" id="txtNumeroFila" value="" />
            </div>
            
            
            
            
            
            
            <div id="div_listar_impuestos"></div>
        </form>

        </div>        
        
      
    </div>
</div>
</body>
</html>