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
    
    <title>Emisi&oacute;n</title>
</head>

<body> 

        

<div class="modal-body">
        <div class="row">
            <div class="col-lg-12 text-center">
                <H1>Modificar Punto de Emisi&oacute;n</H1>
            </div>
        </div>
        
        <?php
        $id=$_POST['id'];
        
        $sql="SELECT emision.`id` as id, `id_est` as id_est, emision.`codigo` as codigo , `numFac` as numFac, `tipoEmision` as tipoEmision, `ambiente` as ambiente, 
        `tipoFacturacion` as tipoFacturacion,emision.formato as formato,
        establecimientos.codigo as codigo_est,establecimientos.id as id_est,  emision.`id_bodega` as emision_id_bodega, emision.impresion	as emision_impresion
    FROM
          `emision` emision
          INNER JOIN `establecimientos` establecimientos ON establecimientos.id=emision.id_est
          WHERE emision.id ='".$id."' ;";


        $result=mysql_query($sql);
        while($row=mysql_fetch_array($result)) {   $emision_impresion= $row['emision_impresion'];  ?>
         
    <form name="formEmi" id="formEmi" method="post" action="javascript:guardarModificarEmision(7);" >
            
        <input type="hidden" tabindex="6" maxlength="3" required="required" id="txtIdCodigo" name="txtIdCodigo"  value="<?php echo $row['id']; ?>"   /> 
            
        <div class="row">
            <div class="col-lg-6 my-3">
                <label for="txtCodigo" class="form-check-label">Establecimiento</label>
            
                <select  tabindex="1" id="cmbEst" class="form-select" required="required" name="cmbEst">
                    <option value="<?php echo $row['id_est']; ?>"><?php echo $row['codigo_est']; ?></option>
                    <?php
                        $sqlu="Select * From establecimientos where id_empresa='".$sesion_id_empresa."';";
                        $resultu=mysql_query($sqlu);
                            while($rowu=mysql_fetch_array($resultu))    {     ?>
                                <option value="<?=$rowu['id']; ?>"><?=$rowu['codigo']; ?></option>
                    <?php    }    ?>
            </select>
            
            
            </div>
            
            <div class="col-lg-6 my-3">
                <label for="txtDireccion" class="form-check-label">C&oacute;digo</label>
				<input type="text" tabindex="6" maxlength="3" required="required" id="txtEmisionCodigo" class="form-control fs-4 p-1 required" name="txtEmisionCodigo"  value="<?php echo $row['codigo']; ?>"    /> 
            </div>
            
              <div class="col-lg-6 my-3">
                <label for="txtDireccion" class="form-check-label">Facturaci&oacute;n</label>
				<input type="text" tabindex="6" maxlength="6" required="required" id="numfac" class="form-control fs-4 p-1 required" name="numfac"   value="<?php echo $row['numFac']; ?>"  /> 
            </div>
            
            <div class="col-lg-6 my-3">
                                <label>Tipo de emisi&oacute;n</label>
                                           <select id="tipoEmision" name="tipoEmision" required="required" class="form-select ">
                                             
                                                    <option value="E" <?php if($row['tipoEmision']=='E'){?> selected <?php } ?>>Electr&oacute;nica</option> 
                                                    <option value="F" <?php if($row['tipoEmision']=='F'){?> selected <?php } ?>>Fisica</option>
                                         
                                            </select> 
            </div>
            
            <div class="col-lg-6 my-3">
                                <label>Ambiente</label>
                                
                                    <select id="ambiente" name="ambiente" required="required" class="form-select required">
                                          
                                                    <option value="1"  <?php if($row['ambiente']=='1'){?> selected <?php } ?>>Pruebas</option>
                                                    <option value="2"<?php if($row['ambiente']=='2'){?> selected <?php } ?> >Producci¨®n</option>
                                                    
                                          
                                    </select> 
            </div>
            
                            <div class="col-lg-6 my-3">
                                    <label>Tipo Facturaci&oacute;n</label>
                                    <select id="tipoFac" name="tipoFac" required="required" class="form-select required">
                                                    <option value="02">Comercio</option>
                                            
                                       
                                        
                                    </select> 
                            </div>
                            
                             <div class="col-lg-6 my-3">
                                    <label>FORMATO</label>
                                    <select id="formato" name="formato" required="required" class="form-select required">
                                                    <option value="2" <?php if($row['formato']=='2'){?> selected <?php } ?> >Rollo</option>
                                                    <option value="1" <?php if($row['formato']=='1'){?> selected <?php } ?>>A4</option>
                                                    <option value="3" <?php if($row['formato']=='3'){?> selected <?php } ?> >A5</option>
                                    </select> 
                            </div>
                         
                            
       
                  
                     
                               <div class="col-lg-6 mt-3">
                                   <label>Impresi&oacute;n luego de venta:</label>
                                        <div class="switch-field">
                                                                                        
                                                <input type="radio" id="radio_impresion_si" name="radio_impresion" value="1" <?php if($emision_impresion==1){ ?>checked <?php }?>>
                                                <label for="radio_impresion_si">SI</label>
                                                
                                                <input type="radio" id="radio_impresion_no" name="radio_impresion" value="0" <?php if($emision_impresion==0){ ?>checked <?php }?> >
                                                <label for="radio_impresion_no">NO</label>
                                            
                                                                                    </div>
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