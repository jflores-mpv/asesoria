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
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
    </head>

    <body> 

        <form name="formMotivo" id="formMotivo" method="post" action="javascript:javascript: anular_ventasql(11);" >
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <H1>Motivo Anulaci&oacute;n</H1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 my-5">
                         <textarea tabindex="8" maxlength="50" required="required" id="txtmotivoAnulacion" class="form-control fs-4 p-1 required" name="txtmotivoAnulacion"     /></textarea> 
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn " onClick="fn_cerrar()">Cerrar</button>
                    <input  type="submit" value="Continuar" name="submit" tabindex="20" class="btn btn-success " name="btnAcceder"  />
                </div>


        </form>

    </body>	

</html>