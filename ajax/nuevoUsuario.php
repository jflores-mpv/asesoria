<?php
	require_once('../ver_sesion.php');

	//Start session
	session_start();
		
	//Include database connection details
	require_once('../conexion.php');
		
?>

<html lang="es-ES">
    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Nuevo Usuario</title>
    <script type="text/javascript">
        
    $(document).ready(function(){
            comboEmpleados(5);
            
    });
    </script>

</head>

<body onload="">    



        
<form name="form" id="form" method="post" action="javascript: guardar_usuario_empresa(1);" >

    <div id="mensaje11" ></div>
    
        <div class="modal-body">
        
        <div class="row">
            <div class="col-lg-12 text-center">
                <H1>Nuevo Usuario</H1>
            </div>
        </div>
     
        <div class="row p-3">
                
            <input type="hidden" tabindex="10"  required="" name="cmbEmpleados" id="cmbEmpleados" value="0">                          

                <div class="col-lg-6">
                     <label for="txtLogin"><strong class="leftSpace">Usuario (requerido)</strong></label><br>
                            <input type="text" tabindex="17" size="22" value="" id="txtLogin" class="form-control fs-4 p-1 required" name="txtLogin" placeholder="Ingrese su nombre de usuario" required="required" autocomplete="off" maxlength="35" onKeyup="no_repetir_login(txtLogin, 7);"/>
                            <div id="noRepetirLogin"></div>
                </div>    
            </div>    
            
            <div class="row p-3">
                <div class="col-lg-6">
                        <label for="cmbEstado"><strong class="leftSpace">Estado (requerido)</strong></label><br>
                            <select  tabindex="16" id="cmbEstado" class="form-select fs-4 p-1 required" required="required" name="cmbEstado" onclick="return isEmailAddress(txtEmail)">
                                <?php
                                $cookie_tipo = $_COOKIE['tipo_cookie'];
                                $sesion_tipo = $_SESSION["sesion_tipo"];
                                 if($cookie_tipo=="Administrador" || $sesion_tipo=="Administrador")
                                     {
                                     ?>
                                        <option value="Inactivo">Inactivo</option>
                                        <option value="Activo">Activo</option>

                                     <?php
                                     }
                                 else {
                                  ?>
                                <option value="Inactivo">Inactivo</option>
                                 <?php
                                     }
                                  ?>
                            </select>
                </div>    
             <div class="col-lg-6">
                            
                             <label for="txtPassword"><strong class="leftSpace">Password (requerido)</strong></label><br>
                             <input type="password" tabindex="18" size="22" value="" id="txtPassword" class="form-control fs-4 p-1 required" name="txtPassword" placeholder="Ingrese su contrase&ntilde;a" required="required" maxlength="35" autocomplete="off" onKeyup="validarContrasena(form);"/>
                             <div id="errorPassword"></div>
                           
             </div>
             
            </div> 
            <div class="row p-3">
                <div class="col-lg-6">
                    <label for="cmbPermisos"><strong class="leftSpace">Permisos (requerido)</strong></label><br>
                            <select  tabindex="13" id="cmbPermisos" class="form-control fs-4 p-1 required" required="required" name="cmbPermisos" >
                                  <option value="Lectura">Lectura</option>
                                  <option value="Lectura y Escritura">Lectura y Escritura</option>
                            </select>
                </div>
                <div class="col-lg-6">
                    <label for="txtRpPassword"><strong class="leftSpace">Repita Password (requerido)</strong></label><br>
                            <input type="password" tabindex="19" size="22" value="" id="txtRpPassword" class="form-control fs-4 p-1 required" name="txtRpPassword" required="required" placeholder="Repita su contrase&ntilde;a" maxlength="35" autocomplete="off" onblur="validarContrasena(form);" />
                            
                </div>
                
            </div>
            <div class="row p-3">
            <div class="col-lg-6">
                 <label for="cmbTipo"><strong class="leftSpace">Tipo (requerido)</strong></label><br>
                             <select  tabindex="15" id="cmbTipo" class="form-select fs-4 p-1 required" required="required" name="cmbTipo">
                                <?php
                                $cookie_tipo = $_COOKIE['tipo_cookie'];
                                $sesion_tipo = $_SESSION["sesion_tipo"];
                                 if($cookie_tipo=="Administrador" || $sesion_tipo=="Administrador")
                                     {
                                     ?>
                                      <option value="Administrador">Administrador</option>
                                      <option value="Contador">Contador</option>
                                      <option value="Empleado">Empleado</option>
                                     <?php
                                     }
                                 else {
                                  ?>
                               <option value="Empleado">Empleado</option>
                                 <?php
                                     }
                                  ?>
                            </select>
            </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onClick="fn_cerrar()">Cerrar</button>
                <button class="btn btn-success" value="Guardar" type="submit" id="submit"  name="btnGuardar" >Guardar </button>     
            </div>
            
            

            <div class="path"></div><br />

            </div>
               
        </form>        

         </div>	<!-- end contactForm -->
    </div>	<!-- end contentLeft -->
</div><!-- END contentTop4 -->




</body>

<script type="text/javascript">

    $(document).ready(function(){         
		$("#form").validate({


//			submitHandler: function(form) {
//				var respuesta = confirm('\xBFDesea realmente agregar a este cliente?')
//				if (respuesta)
//					form.submit();
//			}
		});
	});


</script>
    
</html>
