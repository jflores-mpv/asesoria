<?php
	require_once('../ver_sesion.php');

	//Start session
	session_start();

	//Include database connection details
	require_once('../conexion.php');

?>

<html>
<head>

<title>Modificar Usuario</title>

    <script type="text/javascript">

    $(document).ready(function(){
            //comboEmpleados(5);

    });
    </script>

</head>
 

<body onload="">

    
<div id="contentTop4">

    <div id="contentLeft" class="grid_10">
   
        <!-- BEGIN CONTACT FORM -->
        <div id="contactForm" class="grid_10">
        <h2>Modificar Usuario</h2>

        <div class="transparent_notice" >
            <p >Porfavor llena los campos requeridos.</p>
        </div>

        <div class="lineHor"></div>

        <form name="form" id="form" method="post" action="javascript: guardar_modificacion_usuario(2);" class="ajax_contactform">

            <div id="mensaje2" ></div>

            <div class="socialMiddle" style="">

            <th><h5>Datos Personales</h5></th>

            <div class="path"></div>

            <?php
             try {
            $idUsuario = $_POST['id_usuario'];
            $sql="SELECT
     empleados.`id_empleado` AS empleados_id_empleado,
     empleados.`nombre` AS empleados_nombre,
     empleados.`apellido` AS empleados_apellido,
     empleados.`cedula` AS empleados_cedula,
     empleados.`direccion` AS empleados_direccion,
     empleados.`telefono` AS empleados_telefono,
     empleados.`movil` AS empleados_movil,
     empleados.`tipo` AS empleados_tipo,
     empleados.`estado` AS empleados_estado,
     empleados.`posicion` AS empleados_posicion,
     usuarios.`id_usuario` AS usuarios_id_usuario,
     usuarios.`id_empleado` AS usuarios_id_empleado,
     usuarios.`login` AS usuarios_login,
     usuarios.`password` AS usuarios_password,
     usuarios.`tipo` AS usuarios_tipo,
     usuarios.`estado` AS usuarios_estado,
     usuarios.`fecha_registro` AS usuarios_fecha_registro,
     usuarios.`permisos` AS usuarios_permisos,
     empleados.`fecha_nacimiento` AS empleados_fecha_nacimiento
FROM
     `empleados` empleados INNER JOIN `usuarios` usuarios ON empleados.`id_empleado` = usuarios.`id_empleado`
         WHERE usuarios.`id_usuario` = '".$idUsuario."';";
           $result=mysql_query($sql);             
           while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
               {

            ?>
            
            <input type="hidden" id="txtIdUsuario" name="txtIdUsuario" readonly="readonly" value="<?php echo $idUsuario; ?>"  />

            <table border="0" width="300">
                <tbody>
                    <tr>
                        <td>
                            <label for="cmbEmpleados"><strong class="leftSpace">Empleados (requerido)</strong></label><br>
                            <select tabindex="10" class="text_input required" required="required" name="cmbEmpleados" id="cmbEmpleados" onChange="" ondblclick="comboEmpleados(5)">
                               <option value="<?php echo $row['empleados_id_empleado']; ?>" selected><?php echo $row['empleados_nombre']." ".$row['empleados_apellido'];  ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="cmbPermisos"><strong class="leftSpace">Permisos (requerido)</strong></label><br>
                            <select  tabindex="13" id="cmbPermisos" class="text_input required" required="required" name="cmbPermisos" >
                                <option value="<?php echo $row['usuarios_permisos']; ?>" selected ><?php echo $row['usuarios_permisos']; ?></option>
                                <option value="Lectura" >Lectura</option>
                                <option value="Lectura y Escritura">Lectura y Escritura</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                            <td>
                                <label for="cmbTipo"><strong class="leftSpace">Tipo (requerido)</strong></label><br>
                                 <select  tabindex="15" id="cmbTipo" class="text_input required" required="required" name="cmbTipo">
                                     <option value="<?php echo $row['usuarios_tipo']; ?>" selected ><?php echo $row['usuarios_tipo']; ?></option>
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
                           </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="cmbEstado"><strong class="leftSpace">Estado (requerido)</strong></label><br>
                                <select  tabindex="16" id="cmbEstado" class="text_input required" required="required" name="cmbEstado" onclick="return isEmailAddress(txtEmail)">
                                    <option value="<?php echo $row['usuarios_estado']; ?>" selected ><?php echo $row['usuarios_estado']; ?></option>
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
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="txtLogin"><strong class="leftSpace">Login (requerido)</strong></label><br>
                                <input type="text" tabindex="17" size="22" value="<?php echo $row['usuarios_login']; ?>" id="txtLogin" class="text_input required" name="txtLogin" placeholder="Ingrese su nombre de usuario" required="required" autocomplete="off" maxlength="35" onKeyup="no_repetir_login(txtLogin, 7);"/>
                                <div id="noRepetirLogin"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                 <label for="txtPassword"><strong class="leftSpace">Password (requerido)</strong></label><br>
                                 <input type="password" tabindex="18" size="22" value="<?php echo $row['usuarios_password']; ?>" id="txtPassword" class="text_input required" name="txtPassword" placeholder="Ingrese su contrase&ntilde;a" required="required" maxlength="35" autocomplete="off" onKeyup="validarContrasena(form);"/>
                                 <div id="errorPassword"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="txtRpPassword"><strong class="leftSpace">Repita Password (requerido)</strong></label><br>
                                <input type="password" tabindex="19" size="22" value="<?php echo $row['usuarios_password']; ?>" id="txtRpPassword" class="text_input required" name="txtRpPassword" required="required" placeholder="Repita su contrase&ntilde;a" maxlength="35" autocomplete="off" onblur="validarContrasena(form);" />
                            </td>
                        </tr>

                        <tr align="center">
                            <td>
                                <input type="submit" value="Guardar" tabindex="13" id="submit" class="button" name="btnEnviar"  />
                                <input name="cancelar" type="button" id="submit" value="Cerrar" onclick="fn_cerrar();" class="button"/>
                            </td>
                        </tr>
                </tbody>
            </table>
         <?php }

                }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }

              ?>            

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

function guardar_modificacion_usuario(txtAccion){    
    //var login = "";
    //login = no_repetir_login(form,7);//retorna 1 o 0
    var contrasena = "";
    contrasena = validarContrasena(form);// retorna true o false    
//     alert ('   login: '+login);  
        if(contrasena == true){
            var str = $("#form").serialize();
            $.ajax({
                    url: 'sql/usuarios.php',
                    data: str+"&txtAccion="+txtAccion,
                    type: 'POST',
                    success: function(data){
                    //document.getElementById('mensaje2').innerHTML=""+data;
                    //document.getElementById("form").reset();
                    if(data==1){
                        alertify.suceess("Usuario modificado correctamente");
                    }
                    listar_usuarios_empresa(1);
                    }
            });

        }
        else{
            alert ('No se puede guardar porque las contrase\u00f1as no son iguales');
            document.form.txtPassword.focus();
            document.form.txtPassword.value="";
            document.form.txtRpPassword.value="";
            document.getElementById("errorPassword").innerHTML="" ;
        }

        
        //fn_cerrar();
};
</script>

</html>
