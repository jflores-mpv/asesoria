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
    
    <title>Establecimiento</title>
</head>

<body> 
        
<?php
        $id=$_POST['id'];
        $sql="SELECT
         establecimientos.`id` AS establecimientos_id,
         establecimientos.`codigo` AS establecimientos_codigo,
         establecimientos.`direccion` AS establecimientos_direccion,
         centro_costo
    FROM
         `establecimientos` establecimientos  WHERE establecimientos.`id`='".$id."';";

        $result=mysql_query($sql);
         while($row=mysql_fetch_array($result)) {     ?>


        <form name="formEst" id="formEst" method="post" action="javascript:guardarModificarEstablecimiento(6);" >

       <div class="modal-body">
        <div class="row">
            <div class="col-lg-12 text-center">
                <H1>Modificar Establecimiento</H1>
            </div>
        </div>

<input type="hidden" tabindex="6" maxlength="3" required="required" id="txtIdCodigo" class="form-control fs-4 p-1 required" name="txtIdCodigo"  value="<?php echo $row['establecimientos_id']; ?>"   /> 
        
        <div class="row">
            <div class="col-lg-6 my-3">
                <label for="txtCodigo" class="form-check-label">C&oacute;digo</label>
				<input type="text" tabindex="6" maxlength="3" required="required" id="txtCodigo" class="form-control fs-4 p-1 required" name="txtCodigo"  value="<?php echo $row['establecimientos_codigo']; ?>"   /> 
            </div>
            
            <div class="col-lg-12 my-3">
                <label for="txtDireccion" class="form-check-label">Direcci&oacute;n</label>
				<input type="text" tabindex="6" maxlength="150" required="required" id="txtDireccion" class="form-control fs-4 p-1 required" name="txtDireccion" value="<?php echo $row['establecimientos_direccion']; ?>"    /> 
            </div>
            
               <?php        
            $dominio = $_SERVER['SERVER_NAME'];
            if( $dominio=='dcacorp.com.ec' or $dominio=='www.dcacorp.com.ec'){
            ?>
            
             <div class="col-lg-12 my-3">
                <label for="cmbCentroCosto" class="form-check-label">Centro de Costo:</label>
                <select id="cmbCentroCosto" name="cmbCentroCosto" class="form-control fs-4 p-1 required" >
                    <option value="0">Seleccionar area:</option>
                    <?php 
                    $sql2="SELECT `id_centro_costo`, `detalle`, `id_empresa` FROM `centro_costo_empresa` WHERE id_empresa =$sesion_id_empresa";
                    $result2 = mysql_query($sql2);
                    while($row2 = mysql_fetch_array($result2) ){
                        ?>
                    <option value="<?php echo $row2['id_centro_costo'] ?>"  <?php if($row2['id_centro_costo'] == $row['centro_costo'] ){ ?> selected<?php } ?>><?php echo $row2['detalle'] ?></option>   
        <?php
            }
            }else{
                ?>
                	<input type="hidden"  id="cmbCentroCosto" name="cmbCentroCosto" value="0"    /> 
                <?php
            }
        ?>
                </select>
			
            </div>

        </div>
        

          
            <div class="modal-footer">
                <button type="button" class="btn " onClick="fn_cerrar()">Cerrar</button>
        	    <input  type="submit" value="Guardar" id="submit" tabindex="18" class="btn btn-success " name="btnIngresar"  />
            
            </div>

   
 </form>
<?php      }    ?>
</body>	
    
</html>