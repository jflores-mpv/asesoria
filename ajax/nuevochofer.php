<?php
	require_once('../ver_sesion.php');

	//Start session
	session_start();
		
	//Include database connection details
	require_once('../conexion.php');
	   $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
       $id= $_GET['id'];
    //   echo "".$id."</br>";

       $sql = "SELECT `Id`, `Nombres`, `Direccion`, `Telefono`, `Cedula`, `Tipo`, `Placa`, `id_empresa` FROM `transportista` WHERE transportista.`id_empresa`='".$sesion_id_empresa."' AND  Id=$id ";
       $resultado = mysql_query($sql);
        // echo $sql;
       $id = '';
       $nombres= '';
       $direccion= '';
       $telefono = '';
       $cedula = '';
       $tipo = '';
       $placa = '';
       while($row=mysql_fetch_array($resultado)){ 
         $id = $row['Id'];
        $nombres= $row['Nombres'];
        $direccion= $row['Direccion'];
        $telefono = $row['Telefono'];
        $cedula = $row['Cedula'];
        $tipo = $row['Tipo'];
        $placa = $row['Placa'];
       }
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    
    <title>Establecimiento</title>
</head>

<body> 

        <form name="formEst" id="formEst" method="post" action="javascript:guardar_transportista(1);" >
       <input type="text" id="id" name="id" value="<?php echo ($id!='')?$id:-1; ?>">
       <div class="modal-body">
        <div class="row">
            <div class="col-lg-12 text-center">
                <H1>Nuevo Transportista</H1>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-4 my-3">
                <label for="txtCodigo" class="form-check-label">RAZON SOCIAL</label>
				<input type="text" tabindex="6" maxlength="250" required="required" id="txtRazonSocial" class="form-control fs-4 p-1 required" name="txtRazonSocial" value="<?php echo $nombres ?>"  /> 
            </div>
             <div class="col-lg-4 my-3">
                <label for="txtCodigo" class="form-check-label">DIRECCION</label>
				<input type="text" tabindex="6" maxlength="250" required="required" id="txtDireccion" class="form-control fs-4 p-1 required" name="txtDireccion" value="<?php echo $direccion ?>" /> 
            </div>
            
            <div class="col-lg-4 my-3">
                <label for="txtDireccion" class="form-check-label">Telefono</label>
				<input type="text" tabindex="6" maxlength="250" required="required" id="txtTelefono" class="form-control fs-4 p-1 required" name="txtTelefono" value="<?php echo $telefono ?>"/> 
            </div>

        </div>
         <div class="row">
            <div class="col-lg-4 my-3">
                <label for="txtCodigo" class="form-check-label">Tipo Identificacion</label>
				<select id="tipoIdentificacion" name="tipoIdentificacion" class="form-control">
				    <option value="05" <?php if($tipo==5){ ?> selected <?php } ?> >Cedula</option>
				    <option value="04" <?php if($tipo==4){ ?> selected <?php } ?> >RUC</option>
				</select>
            </div>
             <div class="col-lg-4 my-3">
                <label for="txtCodigo" class="form-check-label">Identificacion</label>
				<input type="text" tabindex="6" maxlength="250" required="required" id="txtIdentificacion" class="form-control fs-4 p-1 required" name="txtIdentificacion"  value="<?php echo $cedula ?>"    /> 
            </div>
            
            <div class="col-lg-4 my-3">
                <label for="txtDireccion" class="form-check-label">Placa</label>
				<input type="text" tabindex="6" maxlength="250" required="required" id="txtPlaca" class="form-control fs-4 p-1 required" name="txtPlaca"    value="<?php echo $placa ?>"  /> 
            </div>

        </div>
        

          
            <div class="modal-footer">
                <button type="button" class="btn " onClick="fn_cerrar()">Cerrar</button>
        	    <input  type="submit" value="Guardar" id="submit" tabindex="18" class="btn btn-success " name="btnIngresar"  />
            
            </div>

   
 </form>

</body>	
    
</html>