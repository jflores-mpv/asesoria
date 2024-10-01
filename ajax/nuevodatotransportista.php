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
    
    <title>Emisi贸n</title>
    
    <script>
function est11(){
    var codigo=$('#cmbEst').val();
      $.ajax({
        url:'sql/establecimientos.php',
        type:'post',
        data:{codigo:codigo,txtAccion:3},
        success:function(res){
          $("#cmbEmi").html(res);
          
        }
      });
    }
    </script>
    
</head>

<body> 

        <form name="formdatostrans" id="formdatostrans" method="post" action="javascript:guardar_datos_transporte(1);" >

       <div class="modal-body">
        <div class="row">
            <div class="col-lg-12 text-center">
                <H1>Nuevo Punto de Emisi贸n</H1>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-6 my-3">
                <label for="txtCodigo" class="form-check-label">Establecimiento</label>
              <select  tabindex="1" id="cmbEst" class="form-select" required="required" name="cmbEst" onClick="est11()" >

                <?php
                $sqlu="Select * From establecimientos where id_empresa='".$sesion_id_empresa."';";
                $resultu=mysql_query($sqlu);
                while($rowu=mysql_fetch_array($resultu))
                 {  ?>
                 
                  <option value="<?=$rowu['id']; ?>"><?=$rowu['codigo']; ?></option>

                <?php         }           ?>
              </select> 
            
            
            </div>
            
    <div class="col-lg-6 my-3">
       <label for="txtCodigo" class="form-check-label">Emision</label>
              <select   id="cmbEmi" class="form-select" required="required" name="cmbEmi">
              </select>
    </div>
            
              <div class="col-lg-6 my-3">
                <label for="txtDireccion" class="form-check-label">Identificacion</label>
				<input type="text" tabindex="6" maxlength="6" required="required" id="txtIdentificacion" 
				class="form-control fs-4 p-1 required" name="txtIdentificacion"     /> 
            </div>
            
            <div class="col-lg-6 my-3">
                <label for="txtDireccion" class="form-check-label">Nombre</label>
				<input type="text" tabindex="6" maxlength="6" required="txtNombre" id="txtNombre" 
				class="form-control fs-4 p-1 required" name="txtNombre"     /> 
            </div>
              <div class="col-lg-6 my-3">
                <label for="txtDireccion" class="form-check-label">Placa</label>
				<input type="text" tabindex="6" maxlength="6" required="required" id="txtPlaca" 
				class="form-control fs-4 p-1 required" name="txtPlaca"     /> 
            </div>
            
        </div>
        

          
            <div class="modal-footer">
                <button type="button" class="btn " onClick="fn_cerrar()">Cerrar</button>
        	    <input  type="submit" value="Guardar" id="submit" tabindex="18" class="btn btn-success " name="btnIngresar"  />
            
            </div>

   
 </form>

</body>	
    
</html>