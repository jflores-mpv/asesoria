<?php

	require_once('../ver_sesion.php');
	session_start();
	require_once('../conexion.php');
 $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
        $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
        $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
        $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
			
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <script type="text/javascript" src="js/clientes.js"></script>
    
    <title>Clientes</title>

    <script type="text/javascript">
    $(document).ready(function(){
            //listar_clientes();
            combopais2(1); comboprovincia2(2); 
            //cargarVendedores(2);
            document.getElementById("txtCedula").value = document.getElementById("txtCedulaFVC").value;
    });
    </script>
   
</head>

<body onload="">
    
    
    
    <form name="frmClientes" id="frmClientes" method="post" action="javascript: guardar_clientes(1);" >
      
      <div class="modal-header">
           <H1>Nuevo Cliente </H1>
           <button type="button" class="btn btn-default" onClick="fn_cerrar()">Cerrar</button>
      </div>
    
 

      <div class="modal-body bg-light">
          <div class="row p-3 rounded">
              <div class="col-lg-10">
                  <p>Seleccione el tipo de identificaci&oacute;n:</p>
              </div>              
              <div class="col-lg-10">
                 <div class="input-group mb-3">
                        
                        <input type="radio" class="btn-check" name="rbCaracterIdentificacion"  autocomplete="off" onClick="cambiarTxt();" value="05" id="cedula" required >
                        <label class="btn btn-outline-success" for="cedula">C&eacute;dula</label>
                        
                        <input type="radio" class="btn-check" name="rbCaracterIdentificacion"  autocomplete="off" onClick="cambiarTxt();" value="04" id="ruc" required>
                        <label class="btn btn-outline-success" for="ruc">RUC</label>
                        
                        <input type="radio" class="btn-check" name="rbCaracterIdentificacion"  autocomplete="off" onClick="cambiarTxt();" value="06" id="pasaporte" required>
                        <label class="btn btn-outline-success" for="pasaporte">Pasaporte</label>
                        
                        <input type="radio" class="btn-check" name="rbCaracterIdentificacion"  autocomplete="off" 
                        onClick="consumidorFinal()" value="07" id="final"  required>
                        <label class="btn btn-outline-success" for="final">Consumidor final</label>
                        
                        <input type="radio" class="btn-check" name="rbCaracterIdentificacion"  autocomplete="off" onClick="cambiarTxt();" value="06" id="otros"  required >
                        <label class="btn btn-outline-success" for="otros">SAS</label>
                        
                 
                        
                        <input type="radio" class="btn-check" name="rbCaracterIdentificacion"  autocomplete="off" onClick="cambiarTxt();" value="08" id="exterior" required>
                        <label class="btn btn-outline-success" for="exterior">EXTERIOR</label>
                        
                     
                        

                        <input type="text" tabindex="3"   value="" id="txtCedula"  class="form-control" style="width:100px" name="txtCedula"  
                        autocomplete="off" onBlur="cedula_ruc(txtCedula.value)"  onChange="no_repetir_cedula_clientes(txtCedula.value, 3)" placeholder="Identificacion"/>  
                        <div id="mensageCedula"></div>
                        <div id="mensageCedula2"></div>
                    </div>
                    
	        
                <div class="switch-field " style="display:none">
                    <input type="radio" id="radio-one-pro" name="switch-four" value="1" class="p-4" checked />
                    <label for="radio-one-pro">Cliente</label>
                    <input type="radio" id="radio-two-pro" name="switch-four" value="0" class="p-4"   />
                    <label for="radio-two-pro">Empresa</label>
                </div>
            </div>
            </div>
            <div class="row p-3 rounded ">
            <div class="col-lg-6" >
              <label for="txtNombre">Nombres</label>
                <input type="text" tabindex="1" value="" id="txtNombre" style="border-style: none;" 
                class="form-control border  fs-5 p-1" 
                name="txtNombre"   maxlength="150" autocomplete="off"  />
            </div>
            
            <div class="col-lg-6" >
              <label for="txtApellido">Apellidos</label>
                <input style="border-style: none;"  class="form-control border  fs-5 p-1" type="text" tabindex="2"  value="" id="txtApellido"  name="txtApellido"   maxlength="150" autocomplete="off" />
            </div>
          </div>
          
          <div class="row p-3">
            
            <div class="col-lg-6" >
                            <label for="txtDireccion">Direcci&oacute;n (requerido)</label>
                            <input style="border-style: none;"   class="form-control border  fs-5 p-1" type="text" tabindex="4" value="" id="txtDireccion" name="txtDireccion"  maxlength="200" autocomplete="off" />
            </div>
                   <div class="col-lg-6" >
                               <label for="txtEmail">Email</label>
                               <input style="border-style: none;"  type="text" tabindex="7" value=""  class="form-control border  fs-5 p-1" id="txtEmail"  name="txtEmail"  maxlength="400" autocomplete="off" onBlur="return isEmailAddress(txtEmail); " onKeyup="no_repetir_email_cliente(txtEmail,4);" onClick="no_repetir_email_cliente(txtEmail,4);"/>
                               <div id="mensajeEmail"></div>
                               <div id="noRepetirEmail"></div>
                         </div>
          </div>  
          <div class="row p-3">
                  
                         <div class="col-lg-3" >
                            <label for="txtTelefono">Tel&eacute;fono</label>
                            <input style="border-style: none;"  type="text" tabindex="5" value=""  class="form-control border  fs-5 p-1" id="txtTelefono" name="txtTelefono" 
                            maxlength="30" autocomplete="off" />
                         </div>
                         <div class="col-lg-3" >
                            <label for="txtMovil">M&oacute;vil</label>
                            <input style="border-style: none;"  type="text" tabindex="6" value=""   class="form-control border  fs-5 p-1" id="txtMovil" name="txtMovil" 
                            maxlength="30" autocomplete="off" maxlength="10" onKeyPress="return soloNumeros(event);" />
                         </div>
                                 <div class="col-lg-3">
                                 <label for="cbprovincia">Provincias (requerido)</label>
                                 <select style="border-style: none;" class="form-select fs-5 p-1 " tabindex="15" name="cbprovincia" id="cbprovincia"
                                 onChange="combociudad2(3);"></select>
                                 <input type="hidden" name="opcion1" id="opcion1" value=""/>
                        </div>
                         <div class="col-lg-3" >
                               <label for="cbciudad">Ciudades (requerido)</label>
                               <select style="border-style: none;" class="form-select fs-5 p-1" tabindex="16" name="cbciudad" id="cbciudad" onChange="" ></select>
                               <input style="border-style: none;"  type="hidden" name="opcion2" id="opcion2" value="" id="opcion2"/>
                        </div>
          </div>
          
          
          
          
          <div class="row p-3">
                <div class="col-lg-6">
	                    <label for="txtMovil">Observaci&oacute;n</label>
                        <textarea style="border-style: none;"  type="text" tabindex="6" value=""   class="form-control border  fs-5 p-1" id="txtObservacion" name="txtObservacion" 
                        autocomplete="off"  /></textarea>
	            </div>
	     

	   </div>

                     <div class="row p-3">
        <div class="col-lg-6" >
            <label for="cbVendedor"> Vendedor </label>
            <select style="border-style: none;" class="form-select fs-5 p-1" tabindex="16" name="cbVendedor" id="cbVendedor" onChange="" >
                 <option value="0">Selecionar una opci&oacute;n:</option>
        <?php
        $sqlVendedor="SELECT `id_vendedor`, `codigo`, `nombre`, `apellidos`, `direccion`, `telefono`, `comision`, `id_empresa`, `caracter_identificacion`, `cedula`, `estado`, `email` FROM `vendedores` WHERE id_empresa=$sesion_id_empresa AND estado='Activo'";
        $resultVendedor = mysql_query($sqlVendedor);
        while($rowVend = mysql_fetch_array($resultVendedor) ){
        ?>
            <option value="<?php echo $rowVend['id_vendedor'];?>"><?php echo $rowVend['nombre'].''.$rowVend['apellidos'];?></option>
        <?php   
        }
	    ?>
            </select>
        </div>
     </div>
    


          <div class="row p-3" hidden="">     
                 
                         <div class="col-lg-4" >
                            <label for="cbEstado">Estado (requerido)</label>
                              <select class="form-control " required="required" tabindex="16" name="cmbEstado" id="cmbEstado" onChange="" >
							     <option value="Activo">Activo</option>
							     <option value="Inactivo">Inactivo</option>
						      </select> 
                        </div>
          </div>
    <!--      <div class="row">
            <div class="col-lg-6" style="margin-top:3%;">
              <label for="txtNombre">Tipo de cliente curso (requerido)</label>
                <select style="text-transform: capitalize;" tabindex="7" id="cmbCurso" class="form-control" name="cmbCurso" >			
		          <?php
                   $sqlp="Select id_curso,codigo,descripcion From tipo_cliente_curso where id_empresa='".$sesion_id_empresa."';";
			       echo $sqlp;
                   $resultp=mysql_query($sqlp);
                   while($rowp=mysql_fetch_array($resultp))//permite ir de fila en fila de la tabla
                   { ?>
				      <option value="<?=$rowp['id_curso']; ?>"><?=$rowp['codigo'].' '.$rowp['descripcion']; ?>
				      </option>
			       <?php   } ?>
                 </select>
			</div>
          </div>	--->	  

      <div class="row bordes" style="margin-top:1%" hidden="">
                <div class="col-lg-12">
                            <label  for="cbpais"><strong class="leftSpace">Pa&iacute;s (requerido)</strong></label>
                            <select  class="text_input10 " name="cbpais" id="cbpais" onChange="comboprovincia2(2);"></select>
                            <input type="text" name="opcion" id="opcion" value="1"/>
                </div>
      </div>                    

        

      <div class="modal-footer">
        <button type="button" class="btn btn-default" onClick="fn_cerrar()">Cerrar</button>
        	<input  type="submit" value="Guardar" id="btnGuardar" tabindex="18" class="btn btn-success " name="btnGuardar" onClick="validarTipoDocumentoSelecionado()" />
            
      </div>
      </form>
        


<script type="">
 function validarTipoDocumentoSelecionado() {
            var opciones = document.getElementsByName("rbCaracterIdentificacion");
            var seleccionada = false;

            for (var i = 0; i < opciones.length; i++) {
                if (opciones[i].checked) {
                    seleccionada = true;
                    break;
                }
            }

            if (!seleccionada) {
                alertify.error('Es necesario seleccionar al menos una opci&oacute;n antes de enviar el formulario');
                return false; // Evita que el formulario se env¨ªe
            }

            // Puedes agregar m¨¢s validaciones si es necesario
            return true; // Env¨ªa el formulario si la validaci¨®n es exitosa
        }
</script>

</body>

</html>