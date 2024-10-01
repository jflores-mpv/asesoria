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
    
    <title>Emisi√≥n</title>
</head>

<body> 

        <form name="formEmi" id="formEmi" method="post" action="javascript:guardar_emision(2);" >

       <div class="modal-body">
        <div class="row">
            <div class="col-lg-12 text-center">
                <H1>Nuevo Punto de Emisi√≥n</H1>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-6 my-3">
                <label for="txtCodigo" class="form-check-label">Establecimiento</label>
            <select  tabindex="1" id="cmbEst" class="form-select" required="required" name="cmbEst">
                <?php
                $sqlu="Select * From establecimientos where id_empresa='".$sesion_id_empresa."';";
                $resultu=mysql_query($sqlu);
                 while($rowu=mysql_fetch_array($resultu))//permite ir de fila en fila de la tabla
                     {
                     ?>
                      <option value="<?=$rowu['id']; ?>"><?=$rowu['codigo']; ?></option>

                     <?php
                     }

                  ?>
            </select>
            
            
            </div>
            
            <div class="col-lg-6 my-3">
                <label for="txtDireccion" class="form-check-label">C√≥digo</label>
				<input type="text" tabindex="6" maxlength="3" required="required" id="txtEmision" class="form-control fs-4 p-1 required" name="txtEmision"     /> 
            </div>
            
              <div class="col-lg-6 my-3">
                <label for="txtDireccion" class="form-check-label">Facturaci&oacute;n</label>
				<input type="text" tabindex="6" maxlength="6" required="required" id="numfac" class="form-control fs-4 p-1 required" name="numfac"     /> 
            </div>
            
            <div class="col-lg-6 my-3">
                                <label>Tipo de emisi&oacute;n</label>
                                           <select id="tipoEmision" name="tipoEmision" required="required" class="form-select required">
                                                <option value="F">Fisica</option>
                                                <option value="E">Electr®Ænica</option>
                                </select> 
            </div>
            
            <div class="col-lg-6 my-3">
                                <label>Ambiente</label>
                                    <select id="ambiente" name="ambiente" required="required" class="form-select required">
                                            <option value="01">Producci®Æn</option>
                                            <option value="02">Pruebas</option>
                                    </select> 
            </div>
            
            <div class="col-lg-6 my-3">
                                <label>Tipo Facturacion</label>
                                    <select id="tipoFac" name="tipoFac" required="required" class="form-select required">
                                            <option value="02">Comercio</option>
                                            <option value="03">Restaurante</option>
                                    </select> 
            </div>
               <div class="col-lg-6 my-3">
                                    <label>FORMATO</label>
                                    <select id="formato" name="formato" required="required" class="form-select required">
                                                    <option value="2">Rollo</option>
                                                    <option value="1">A4</option>
                                                    <option value="1">A5</option>
                                    </select> 
                            </div>


        </div>
        

          
            <div class="modal-footer">
                <button type="button" class="btn " onClick="fn_cerrar()">Cerrar</button>
        	    <input  type="submit" value="Guardar" id="submit" tabindex="18" class="btn btn-success " name="btnIngresar"  />
            
            </div>

   
 </form>

</body>	
    
</html>