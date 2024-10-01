<?php

	require_once('../ver_sesion.php');

	//Start session
	session_start();
		
	//Include database connection details
	require_once('../conexion.php');
	   $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
       $id= $_GET['id'];
    //   echo "".$id."</br>";

       $sql = "SELECT `id_vendedor`, `codigo`, `nombre`, `apellidos`, `direccion`, `telefono`, `comision`, `id_empresa`, `caracter_identificacion`, `cedula`, `estado`,email,tipo_vendedor FROM `vendedores` 
       WHERE `id_empresa`='".$sesion_id_empresa."' AND  id_vendedor=$id ";
       $resultado = mysql_query($sql);
       $id = '';
       $nombres= '';
       $apellidos = '';
       $direccion= '';
       $telefono = '';
       $caracter_identificacion= '';
       $cedula = '';
       $estado = '';
      
       while($row=mysql_fetch_array($resultado)){ 
         $id = $row['id_vendedor'];
        $nombres= $row['nombre'];
        $email= $row['email'];
        $apellidos= $row['apellidos'];
        $direccion= $row['direccion'];
        $telefono = $row['telefono'];
        $caracter_identificacion = $row['caracter_identificacion'];
        $estado = $row['estado'];
        $cedula = $row['cedula'];
        $tipo_vendedor = $row['tipo_vendedor'];
       }
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    
    <title>Vendedores</title>
</head>

<body> 

        <form name="formEst" id="formEst" method="post" action="javascript:guardar_vendedor(10);" >
       <input type="text" id="id" name="id" value="<?php echo ($id!='')?$id:-1; ?>">
       <div class="modal-body">
        <div class="row">
            <div class="col-lg-12 text-center">
                <H1>Nuevo Vendedor</H1>
            </div>
        </div>
        
        <div class="row">
             <div class="input-group mb-3">
                        
                        <input type="radio" class="btn-check" name="rbCaracterIdentificacion"  autocomplete="off" onClick="cambiarTxt();" value="05" id="cedula" required  <?php if($caracter_identificacion=='05'){ ?> checked <?php } ?> >
                        <label class="btn btn-outline-success" for="cedula">C&eacute;dula</label>
                        
                        <input type="radio" class="btn-check" name="rbCaracterIdentificacion"  autocomplete="off" onClick="cambiarTxt();" value="04" id="ruc" <?php if($caracter_identificacion=='04'){ ?> checked <?php } ?> required>
                        <label class="btn btn-outline-success" for="ruc">R.U.C.</label>
                        
                        <input type="radio" class="btn-check" name="rbCaracterIdentificacion"  autocomplete="off" onClick="cambiarTxt();" value="06" <?php if($caracter_identificacion=='06'){ ?> checked <?php } ?> id="pasaporte" required>
                        <label class="btn btn-outline-success" for="pasaporte">Pasaporte</label>
                        
                        <input type="radio" class="btn-check" name="rbCaracterIdentificacion"  autocomplete="off" 
                        onClick="consumidorFinal()" value="07" <?php if($caracter_identificacion=='07'){ ?> checked <?php } ?> id="final"  required>
                        <label class="btn btn-outline-success" for="final">Consumidor final</label>
                        
                        <input type="radio" class="btn-check" name="rbCaracterIdentificacion"  autocomplete="off" onClick="cambiarTxt();" value="06" <?php if($caracter_identificacion=='06'){ ?> checked <?php } ?> id="otros"  required >
                        <label class="btn btn-outline-success" for="otros">S.A.S.</label>
                        
                       
                        <input type="radio" class="btn-check" name="rbCaracterIdentificacion"  autocomplete="off" onClick="cambiarTxt();" value="08" <?php if($caracter_identificacion=='08'){ ?> checked <?php } ?> id="exterior" required>
                        <label class="btn btn-outline-success" for="exterior">EXTERIOR</label>
                        
                     
                        

                        <input type="text" tabindex="3"   value="<?php echo $cedula ?>" id="txtCedula"  class="form-control" style="width:100px" name="txtCedula"  
                        autocomplete="off" onBlur="cedula_ruc(txtCedula.value)"  onChange="no_repetir_cedula_clientes(txtCedula.value, 3)" placeholder="Identificaci&oacute;n"/>  
                        <div id="mensageCedula"></div>
                        <div id="mensageCedula2"></div>
                    </div>
            <div class="col-lg-4 my-3">
                <label for="txtNombre" class="form-check-label">NOMBRES:</label>
				<input type="text" tabindex="6" maxlength="250" required="required" id="txtNombre" class="form-control fs-4 p-1 required" name="txtNombre" value="<?php echo $nombres ?>"  /> 
            </div>
            <div class="col-lg-4 my-3">
                <label for="txtApellidos" class="form-check-label">APELLIDOS:</label>
				<input type="text" tabindex="6" maxlength="250" required="required" id="txtApellidos" class="form-control fs-4 p-1 required" name="txtApellidos" value="<?php echo $apellidos ?>"  /> 
            </div>
            
            <div class="col-lg-4 my-3">
                <label for="txtCorreo" class="form-check-label">CORREO:</label>
				<input type="text" tabindex="6" maxlength="250" required="required" id="txtCorreo" class="form-control fs-4 p-1 required" name="txtCorreo" value="<?php echo $email ?>" /> 
            </div>
            
             <div class="col-lg-4 my-3">
                <label for="txtDireccion" class="form-check-label">DIRECCI&Oacute;N</label>
				<input type="text" tabindex="6" maxlength="250" required="required" id="txtDireccion" class="form-control fs-4 p-1 required" name="txtDireccion" value="<?php echo $direccion ?>" /> 
            </div>
            
            <div class="col-lg-4 my-3">
                <label for="txtTelefono" class="form-check-label">TEL&Eacute;FONO</label>
				<input type="text" tabindex="6" maxlength="250" required="required" id="txtTelefono" class="form-control fs-4 p-1 required" name="txtTelefono" value="<?php echo $telefono ?>"/> 
            </div>
     
            
        </div>

            <div class="modal-footer">
                <button type="button" class="btn " onClick="fn_cerrar()">Cerrar</button>
        	    <input  type="submit" value="Guardar" id="submit" tabindex="18" class="btn btn-success " name="btnIngresar"  />
            
            </div>

   
 </form>

</body>	
    
</html>