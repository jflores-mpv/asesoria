<?php

	require_once('../ver_sesion.php');

	//Start session
	session_start();
		$dominio = $_SERVER['SERVER_NAME'];
	//Include database connection details
	require_once('../conexion.php');

        //PERMISOS AL MODULO PLAN CUENTAS
        $sesion_plan_cuentas_guardar = $_SESSION["sesion_plan_cuentas_guardar"];
			
?>
<html>
<head>
<title>Nueva Cuenta</title>    

   <style>
       .switch-field {
	display: flex;
	margin-bottom: 36px;
	overflow: hidden;
}

.switch-field input {
	position: absolute !important;
	clip: rect(0, 0, 0, 0);
	height: 1px;
	width: 1px;
	border: 0;
	overflow: hidden;
}

.switch-field label {
	background-color: #e4e4e4;
	color: rgba(0, 0, 0, 0.6);
	font-size: 14px;
	line-height: 1;
	text-align: center;
	padding: 8px 16px;
	margin-right: -1px;
	border: 1px solid rgba(0, 0, 0, 0.2);
	box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
	transition: all 0.1s ease-in-out;
}

.switch-field label:hover {
	cursor: pointer;
}

.switch-field input:checked + label {
	background-color: #a5dc86;
	box-shadow: none;
}

.switch-field label:first-of-type {
	border-radius: 4px 0 0 4px;
}

.switch-field label:last-of-type {
	border-radius: 0 4px 4px 0;
}

   </style>
</head>

<body onload="">

        <form name="form" id="form" method="post" action="javascript: guardar_plan_cuentas(document.forms['form']);"  >
                <input type="hidden" id="txtPermisosPlanCuentasGuardar" name="txtPermisosPlanCuentasGuardar" value="<?php echo $sesion_plan_cuentas_guardar; ?>" />
                <input type="hidden" id="txtPermisosPlanCuentasModificar" name="txtPermisosPlanCuentasModificar" value="<?php echo $sesion_plan_cuentas_modificar; ?>" />
                <input type="hidden" id="txtPermisosPlanCuentasEliminar" name="txtPermisosPlanCuentasEliminar" value="<?php echo $sesion_plan_cuentas_eliminar; ?>" />
           
        <div class="col-lg-12 my-4">
            <label style="color: black"><strong>C&oacute;digo: (requerido)</strong></label><br>
            <input type="text" maxlength="20" id="txtCodigo" required="required" class="form-control required" 
            name="txtCodigo" placeholder="Ingrese el codigo para la cuenta" onblur="no_repetir_codigo(txtCodigo)" 
            onBlur="fill3();" onKeyup="no_repetir_codigo(txtCodigo)" 
            onclick="no_repetir_codigo(txtCodigo); lookup3(this.value,1);"  autocomplete="off"/>

            <div id="noRepetirCodigo"></div>
            <div id="codigoVacio"></div>
        </div>
        <div class="col-lg-12  my-4">
            <label style="color: black" for="name"><strong>Nombre: (requerido)</strong></label>
            <input style="text-transform: capitalize;" type="text" tabindex="1" maxlength="100" id="txtNombre" class="form-control required" required="required" name="txtNombre" placeholder="Ingrese el nombre de la cuenta" onKeyUp="no_repetir_nombre()" onclick="no_repetir_nombre()" autocomplete="off"/>
            <div id="noRepetirNombre"></div>
            <div id="nombreVacio"></div>
        </div>
           <div class="col-lg-12  my-4">
     <label >Esta Cuenta es de : (requerido)</label>
    <div class="switch-field">
		<input type="radio" id="radio-one" name="switch-one" value="Movimiento" checked/>
		<label for="radio-one">Movimiento</label>
		<input type="radio" id="radio-two" name="switch-one" value="Agrupacion" />
		<label for="radio-two">Agrupaci&oacute;n</label>
	</div>
	
        </div>  
        
     <!--   <div class="col-lg-12  my-4">
            <label style="color: black" for="name"><strong>Esta Cuenta es de : (requerido)</strong></label>
            <select name="cmbTipo" id="cmbTipo" class="form-control required" required="required">
                <option value="Agrupaci&oacute;n" selected="selected">Agrupaci&oacute;n</option>
                <option value="Movimiento" >Movimiento</option>
            </select>
        </div>   -->
        
         <div class="col-lg-12  my-4">
            <label style="color: black" for="name"><strong>Cuenta de banco</strong></label>
            <input style="text-transform: capitalize;" type="text" tabindex="1" maxlength="100" id="txtCuentaBanco" 
            class="form-control"  name="txtCuentaBanco" placeholder="Ingrese el nombre de la cuenta" 
         autocomplete="off"/>

        </div>
        <?php        
            $dominio = $_SERVER['SERVER_NAME'];
            
            if($dominio=='www.contaweb.com' or $dominio=='contaweb.ec' 
            or $dominio=='www.contaweb.com.ec'  or $dominio=='www.contaweb.ec'){
    ?>       
         <div class="col-lg-12  my-4">
            <label style="color: black" for="name"><strong>Formato Cheque</strong></label>
            <select name="cmbFormatoCheque" id="cmbFormatoCheque" class="form-control required" required="required">
                <option value="0" selected="selected">Ninguno</option>
                <option value="pichincha" >Banco Pichincha</option>
                <option value="produbanco" >Banco Produbanco</option>
            </select>
        </div>  
        <?php        
            }
    ?>       
     
     
            <input id="btnGuardar" type="submit" value="Guardar" class="submit" onClick="" class="btn btn-1 btn-sm m-2 w-100"/> 
                              
            <input type="button" value="Cerrar" id="btnCerrar" name="btnCerrar" class="submit" onclick="fn_cerrar();" class="btn btn-1 btn-sm m-2 w-100" />
        </form>        

</body>

</html>