<?php
require_once('../ver_sesion.php');

	//Start session
session_start();

	//Include database connection details
require_once('../conexion.php');

require_once('../clases/contrasenas.php');
require_once('../sql/encriptar.php');
?>

<html lang="es-ES">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Modificar Usuario</title>
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


<body onload="">

  <div class="modal-header">
    <h5>Modificar Usuario</h5>
    <a href="javascript: fn_cerrar();"><span class="fa fa-close"></span></a>
  </div>


  <form name="form" id="form" method="post" action="javascript: guardar_modificacion_usuario(8);" >


    <div class="modal-body">

      <?php
      try {

        $idUsuario = $_POST['id_usuario'];
        $sql="SELECT
        usuarios.`id_usuario` AS usuarios_id_usuario,
        usuarios.`id_empleado` AS usuarios_id_empleado,
        usuarios.`login` AS usuarios_login,
        usuarios.`password` AS usuarios_password,
        usuarios.`tipo` AS usuarios_tipo,
        usuarios.`estado` AS usuarios_estado,
        usuarios.`fecha_registro` AS usuarios_fecha_registro,
        usuarios.`permisos` AS usuarios_permisos,
        usuarios.`id_est` AS usuarios_establecimiento,
        usuarios.`id_punto` AS usuarios_punto,

        usuarios.`id_permiso_asiento_contable` AS usuarios_id_permiso_asiento_contable,
        usuarios.`id_permiso_plan_cuenta` AS usuarios_id_permiso_plan_cuenta,
        usuarios.`reportes_contables` AS usuarios_reportes_contables,
        usuarios.`id_permisos_bancos` AS usuarios_id_permisos_bancos

        FROM
        `usuarios` usuarios  WHERE usuarios.`id_usuario` = '".$idUsuario."';";
        // echo $sql;
        $result=mysql_query($sql);

$emision=0;
           while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
           {
            $emision= $row['usuarios_punto'];
            ?>
            
            <input type="hidden" id="txtIdUsuario" name="txtIdUsuario" readonly="readonly" value="<?php echo $idUsuario; ?>"  />
            <input type="hidden" id="txtIdEmpleado" name="txtIdEmpleado" readonly="readonly" value="<?php echo $row['empleados_id_empleado']; ?>"  />

<div class="row">
    <div class="col-lg-4">
        <div class="row">
            <div class="col-lg-12">
                <label for="cmbPermisos"><strong class="leftSpace">Permiso</strong></label><br>
                <select  tabindex="13" id="cmbPermisos" class="form-control   p-1" required="required" name="cmbPermisos" >
                  <option value="<?php echo $row['usuarios_permisos']; ?>" selected ><?php echo $row['usuarios_permisos']; ?></option>
                  <option value="Lectura" >Lectura</option>
                  <option value="Lectura y Escritura">Lectura y Escritura</option>
                </select>
              </div>
              <div class="col-lg-12">
                <label for="cmbTipo"><strong class="leftSpace">Tipo (requerido)</strong></label><br>
                <select  tabindex="15" id="cmbTipo" class="form-select   p-1 required" required="required" name="cmbTipo">
                 <option value="<?php echo $row['usuarios_tipo']; ?>" selected ><?php echo $row['usuarios_tipo']; ?></option>
                 <option value="Administrador">Administrador</option>
                 <option value="Empleado">Empleado</option>
               </select>
             </div>
             <div class="col-lg-12">
              <label for="cmbEstado"><strong class="leftSpace">Estado (requerido)</strong></label><br>
              <select  tabindex="16" id="cmbEstado" class="form-control   p-1 required" required="required" name="cmbEstado" >
                <option value="<?php echo $row['usuarios_estado']; ?>" selected ><?php echo $row['usuarios_estado']; ?></option>
                <option value="Inactivo">Inactivo</option>
                <option value="Activo">Activo</option>
              </select>
            </div>
            
        </div>
    </div>
    <div class="col-lg-4">
        <div class="row">
            <div class="col-lg-4">

              <label for="cmbEst" class="form-check-label">Establecimiento</label>
              <select  tabindex="1" id="cmbEst" class="form-select" required="required" name="cmbEst" onClick="est11()" >

                <?php
                $sqlu="Select * From establecimientos where id_empresa='".$sesion_id_empresa."';";
                $resultu=mysql_query($sqlu);
                while($rowu=mysql_fetch_array($resultu))
                 {  ?>
                 
                  <option value="<?=$rowu['id']; ?>" <?php if($row['usuarios_establecimiento']==intval($rowu['id'])){ ?> selected <?php } ?>  ><?=$rowu['codigo']; ?></option>

                <?php         }           ?>
              </select> 
            </div>
            

            <div class="col-lg-4 offset-lg-1">
              <label for="txtCodigo" class="form-check-label">Emision</label>
              <select   id="cmbEmi" class="form-select" required="required" name="cmbEmi"></select>
            </div> 	
            <div class="col-lg-12">
              <label for="txtLogin"><strong class="leftSpace">Login (requerido)</strong></label><br>
              <input type="text" tabindex="17"  value="<?php echo $row['usuarios_login']; ?>" id="txtLogin" class="form-control   p-1 required" name="txtLogin" placeholder="Ingrese su nombre de usuario" required="required" autocomplete="off"  onKeyup="no_repetir_login(txtLogin, 7);"/>
              <div id="noRepetirLogin"></div>
            </div>
            <div class="col-lg-11">
                
              <?php
              $txtEncriptado = ($row['usuarios_password']);
              
              $dato_desencriptado = $desencriptar($txtEncriptado); 
              ?>
             <label for="txtPassword"><strong class="leftSpace">Contraseña (requerido)</strong></label><br>
              <input type="password" tabindex="18"  value="<?php  echo $dato_desencriptado; ?>" id="txtPassword" class="form-control   p-1 required" name="txtPassword" placeholder="Ingrese su contrase&ntilde;a" required="required"  autocomplete="off" onKeyup="validarContrasena(form);"/>
              <div id="errorPassword"></div>
             
            </div>
            <div class="col-lg-1 pt-3">
                 <input type="checkbox" onclick="Toggle()">
            </div>
            
            <div class="col-lg-12">
              <label for="txtRpPassword"><strong class="leftSpace">Repita Contraseña (requerido)</strong></label><br>
              <input type="password" tabindex="19"  value="<?php echo $dato_desencriptado; ?>" id="txtRpPassword" class="form-control   p-1 required" name="txtRpPassword" required="required" placeholder="Repita su contrase&ntilde;a"  autocomplete="off" onblur="validarContrasena(form);" />
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="row">
             <p>Datos para compañias de transporte</p>
                <?php
                $sqlEmision="Select * From emision where id='".$row['usuarios_punto']."';";
                $sqlEmision=mysql_query($sqlEmision);
                while($sqlEmision=mysql_fetch_array($sqlEmision))
                    { 
                        $sqlEmision =$sqlEmision['SOCIO'] ;
                        if($sqlEmision=='0'){
                                        $txtNombre='';
                                        $txtRuc='';
                                        $txtPlaca='';
                        }else{
                    
                        $sqlTRANS="Select * From transportista where Id='".$sqlEmision."';";
                        $sqlTRANS=mysql_query($sqlTRANS);
                                while($sqlTRANS=mysql_fetch_array($sqlTRANS))
                                {  
                                     $sqlTRANS['Nombres'];
                                     $txtNombre=($sqlTRANS['Nombres']=='0')?'':$sqlTRANS['Nombres'];
                                     $txtRuc=($sqlTRANS['Cedula']=='')?'0':$sqlTRANS['Cedula'];
                                     $txtPlaca=($sqlTRANS['Placa']=='')?'0':$sqlTRANS['Placa'];
                                     $txtRegimen=($sqlTRANS['regimen']=='')?'0':$sqlTRANS['regimen'];
                                     
                                }
                        }
                    ?>
                    
            
                <div class="col-lg-12">
                  <label for="nombreSocio"><strong class="leftSpace">Nombre Socio</strong></label><br>
                    <input type="text" tabindex="19" value="<?php echo $txtNombre ?>" id="nombreSocio" 
                    class="form-control p-1 " name="nombreSocio" placeholder="Nombre Socio"  autocomplete="off"  />
                </div>
                <div class="col-lg-12">
                    
                    <label for="rucSocio"><strong class="leftSpace">RUC Socio</strong></label><br>
                    <input type="text" tabindex="13"  value="<?php echo $txtRuc ?>" id="rucSocio" 
                    class="form-control   p-1 " name="rucSocio" 
                    placeholder="Ruc Socio"  autocomplete="off"  />
                    
                </div>
                <div class="col-lg-12">
                     <label for="placaSocio"><strong class="leftSpace">Placa Socio</strong></label><br>
                    <input type="text" tabindex="19" value="<?php echo $txtPlaca ?>" id="placaSocio" 
                    class="form-control   p-1 " name="placaSocio" placeholder="Placa Socio"  autocomplete="off"  />
                </div>
                <div class="col-lg-12">
                    
                    <label for="regimenSocio"><strong class="leftSpace">Regimen</strong></label><br>
                    <input type="text" tabindex="19" value="<?php echo $txtRegimen ?>" id="regimenSocio" 
                    class="form-control   p-1 " name="regimenSocio" placeholder="Placa Socio"  autocomplete="off"  />
                    
                </div>
    
             
                  <?php  }   ?>
     
           
                
        </div>
        
        
    </div>
</div>


          <div class="row">
            <div class="col-lg-12 text-center">
              <h3>Permisos</h3>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <ul class="list-group">
                <li class="list-group-item">
                    
                <div class="row">
                     
                    <div class="col-lg-1"><input  class="form-check-input"  type="checkbox" onclick="marcar(this);" /> Mostrar</div>
                    <div class="col-lg-6">M&oacute;dulo</div>
                    <div class="col-lg-1"><input  class="form-check-input"  type="checkbox" onclick="marcarG(this);" />Grabar</div>
                    <div class="col-lg-1"><input  class="form-check-input"  type="checkbox" onclick="marcarE(this);" />Editar</div>
                    <div class="col-lg-1"><input  class="form-check-input"  type="checkbox" onclick="marcarD(this);" />Eliminar</div>
                    <div class="col-lg-1"><input  class="form-check-input"  type="checkbox" onclick="marcarR(this);" />Reportes</div>
                  </div>
                </li>
                <?php
                
                // $sqlModulos="Select permisos_usuarios.*, modulos.modulo FROM permisos_usuarios 
                // INNER JOIN modulos on modulos.id = permisos_usuarios.id_modulo WHERE permisos_usuarios.id_usuario='".$idUsuario."' ";
                
                
                $sqlModulos=$sqlModulos = "SELECT DISTINCT modulos.id as modulo_id, modulos.modulo, permisos_usuarios.* FROM modulos 
                LEFT JOIN permisos_usuarios ON modulos.id = permisos_usuarios.id_modulo AND permisos_usuarios.id_usuario = '".$idUsuario."' 
                ORDER BY modulos.id";
         
                $resultModulos=mysql_query($sqlModulos);
                while($rowModulos=mysql_fetch_array($resultModulos))
                {               
                  $modulos = $rowModulos['modulo'];
                  $id = $rowModulos['modulo_id'];
                  ?>
                  <li class="list-group-item">
                    <div class="row">
                      <input type="hidden" name="idModulo[]" value="<?php echo $id ?>" class="form-control">
                      <div class="col-lg-1 text-center"><input  class="form-check-input mostrar" type="checkbox"    name="permiso[<?php echo $id ?>][0]" <?php if($rowModulos['mostrar']=="SI"){ ?>  checked <?php } ?> ></div>
                      <div class="col-lg-6"><?php echo $modulos ?></div>
                      <div class="col-lg-1 text-center"><input  class="form-check-input guardar" type="checkbox"    name="permiso[<?php echo $id ?>][1]"   <?php if($rowModulos['guardar']=="SI"){ ?>  checked <?php } ?>  ></div>
                      <div class="col-lg-1 text-center"><input  class="form-check-input editar" type="checkbox"    name="permiso[<?php echo $id ?>][2]"    <?php if($rowModulos['editar']=="SI"){  ?>  checked <?php } ?> ></div>
                      <div class="col-lg-1 text-center"><input  class="form-check-input eliminar" type="checkbox"    name="permiso[<?php echo $id ?>][3]"  <?php if($rowModulos['eliminar']=="SI"){  ?>  checked <?php } ?> ></div>
                      <div class="col-lg-1 text-center"><input  class="form-check-input reportes" type="checkbox"    name="permiso[<?php echo $id ?>][4]"  <?php if($rowModulos['reportes']=="SI"){  ?>  checked <?php } ?> ></div>
                    </div>
                  </li>

                <?php         }           ?>
              </ul>
            </div>
          </div>
          <div class="modal-footer mt-3">
            <div class="col-lg-3 offset-lg-3">
             <input style="width: 150px" type="submit" value="Guardar" tabindex="13" id="submit" class="btn btn-primary" name="btnEnviar"  />
           </div>
           <div class="col-lg-3 ">
             <input style="width: 150px" name="cancelar" type="button" id="submit" value="Cerrar" onclick="fn_cerrar();" class="btn btn-primary"/>
           </div>
         </div>
       <?php }

     }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }

     ?>            



   </div>
   <br>
 </form>


</body>

<script type="text/javascript">
    $( document ).ready(function() {
      est11();
      setTimeout(() => {
        $("#cmbEmi option[value='<?php echo $emision  ?>'").attr("selected",true);
        console.log('<?php echo $emision  ?>');
}, 500)

    
});
         function Toggle() {
            var temp = document.getElementById("txtPassword");
            if (temp.type === "password") {
                temp.type = "text";
            }
            else {
                temp.type = "password";
            }
        }

	function marcar(source) 
	{
		checkboxes=document.getElementsByClassName('mostrar'); //obtenemos todos los controles del tipo Input
		for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
		{
			if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
			{
				checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
			}
		}
	}
    
    	function marcarG(source) 
	{
		checkboxes=document.getElementsByClassName('guardar'); //obtenemos todos los controles del tipo Input
		for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
		{
			if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
			{
				checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
			}
		}
	}

    	function marcarE(source) 
	{
		checkboxes=document.getElementsByClassName('editar'); //obtenemos todos los controles del tipo Input
		for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
		{
			if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
			{
				checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
			}
		}
	}

    	function marcarD(source) 
	{
		checkboxes=document.getElementsByClassName('eliminar'); //obtenemos todos los controles del tipo Input
		for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
		{
			if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
			{
				checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
			}
		}
	}

    	function marcarR(source) 
	{
		checkboxes=document.getElementsByClassName('reportes'); //obtenemos todos los controles del tipo Input
		for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
		{
			if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
			{
				checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
			}
		}
	}

  function guardar_modificacion_usuario(txtAccion){    
    //var login = "";
    //login = no_repetir_login(form,7);//retorna 1 o 0
    var contrasena = "";
    contrasena = validarContrasena(form);// retorna true o false    
//     alert ('   login: '+login);  
if(contrasena == true){
  var str = $("#form").serialize();
//   console.log(str);
  $.ajax({
    url: 'sql/usuarios.php',
    data: str+"&txtAccion="+txtAccion,
    type: 'POST',
    success: function(data){
      console.log(data);
      if(data==1){
        alertify.success("Usuario modificado correctamente");
        fn_cerrar();
        listar_usuarios_empresa(1);
      }else{
        alertify.error(data);
      }
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
}



</script>


</html>
