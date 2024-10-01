<?php

	require_once('../ver_sesion.php');
	require_once "../conexion2.php";
	//Start session
	session_start();
		
	//Include database connection details
	require_once('../conexion.php');

        $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
        $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
        $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
 $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];


			
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <script type="text/javascript" src="js/clientes.js"></script>
    
    <title>Clientes</title>
</head>

<body onload="">
    <?php 
		$id_cliente = $_POST['id_cliente'];
		$sql="SELECT
		 clientes.`id_cliente` AS clientes_id_cliente,
		 clientes.`nombre` AS clientes_nombre,
		 clientes.`apellido` AS clientes_apellido,
		 clientes.`direccion` AS clientes_direccion,
		 clientes.`numero_casa` AS clientes_numero_casa,
		 clientes.`caracter_identificacion` AS clientes_caracter_identificacion,
		 clientes.`estado` AS clientes_estado,
		 clientes.`cedula` AS clientes_cedula,
		  clientes.`nacionalidad` AS clientes_nacionalidad,
		 clientes.`telefono` AS clientes_telefono,
		 clientes.`movil` AS clientes_movil,
		 clientes.`email` AS clientes_email,
		 clientes.`observacion` AS clientes_observacion,
		 clientes.`tipo_cliente` AS clientes_tipo_cliente,
		  clientes.`id_vendedor` AS clientes_id_vendedor,
		
		 ciudades.`ciudad` AS ciudades_ciudad,
		 ciudades.`id_provincia` AS ciudades_id_provincia,
		 provincias.provincia as nombre_provincias ,
		 clientes.id_ciudad as id_ciudad  
	FROM
		 `clientes` clientes 
		 LEFT JOIN ciudades ON ciudades.id_ciudad = clientes.id_ciudad 
		 LEFT JOIN provincias ON provincias.id_provincia= ciudades.id_provincia 
		 where clientes.`id_cliente`='".$id_cliente."';";

		$result=mysql_query($sql);
// echo $sql;
		while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
			{
				$id_cliente=$row['clientes_id_cliente'];




	?>

  <form name="frmClientesEditar" id="frmClientesEditar" method="post" action="javascript: guardarModificarCliente(7);" >
      <div class="modal-body">
        <div class="row">
            <div class="col-lg-12 text-center">
                <H1>Editar Cliente</H1>
            </div>
        </div>
		<input style="text-transform: capitalize;" type="hidden" tabindex="1"
						value="<?php echo $id_cliente; ?>" id="txtIdCliente"
						class="text_input1 " name="txtIdCliente"  />
		<div class="row p-3 rounded">
            <div class="col-lg-10">
                  <p>Seleccione el tipo de identificaci&oacute;n:</p>
            </div>              
              <div class="col-lg-10">
                 <div class="input-group mb-3">
                        
                        <input type="radio" class="btn-check" name="rbCaracterIdentificacion"  autocomplete="off" onClick="cambiarTxt();" value="05" id="cedula">
                        <label class="btn btn-outline-success" for="cedula">C&eacute;dula</label>
                        
                        <input type="radio" class="btn-check" name="rbCaracterIdentificacion"  autocomplete="off" onClick="cambiarTxt();" value="04"  <?php if($row['clientes_caracter_identificacion']=='04'){?> checked <?php } ?> id="ruc">
                        <label class="btn btn-outline-success" for="ruc">RUC</label>
                        
                        <input type="radio" class="btn-check" name="rbCaracterIdentificacion"  autocomplete="off" onClick="cambiarTxt();" value="06"  <?php if($row['clientes_caracter_identificacion']=='06'){?> checked <?php } ?> id="pasaporte">
                        <label class="btn btn-outline-success" for="pasaporte">Pasaporte</label>
                        
                        <input type="radio" class="btn-check" name="rbCaracterIdentificacion"  autocomplete="off" onClick="consumidorFinal()" value="07" <?php if($row['clientes_caracter_identificacion']=='07'){?> checked <?php } ?> id="final" >
                        <label class="btn btn-outline-success" for="final">Consumidor final</label>
                        
                        <input type="radio" class="btn-check" name="rbCaracterIdentificacion"  autocomplete="off" onClick="cambiarTxt();" value="06" <?php if($row['clientes_caracter_identificacion']=='06'){?> checked <?php } ?> id="otros"  >
                        <label class="btn btn-outline-success" for="otros">SAS</label>
                        
                       
                        <input type="radio" class="btn-check" name="rbCaracterIdentificacion"  autocomplete="off" onClick="cambiarTxt();" value="08" <?php if($row['clientes_caracter_identificacion']=='08'){?> checked <?php } ?> id="exterior">
                        <label class="btn btn-outline-success" for="exterior">EXTERIOR</label>
                        
                        
                        

                        <input type="text" tabindex="3"   value="<?php echo $row['clientes_cedula']; ?>" id="txtCedula"  class="form-control" style="width:100px" name="txtCedula"  
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
						

            <div class="row p-3 rounded bg-light ">
            <div class="col-lg-6" >
              <label for="txtNombre">Nombre</label>
                <input type="text" tabindex="1" value="<?php echo $row['clientes_nombre']; ?>" id="txtNombre" 
                class="form-control fs-4 p-1" 
                name="txtNombre"   maxlength="150" autocomplete="off"  />
            </div>
            
            <div class="col-lg-6" >
              <label for="txtApellido">Apellidos</label>
                <input  class="form-control fs-4 p-1" type="text" tabindex="2"  value="<?php echo $row['clientes_apellido']; ?>" id="txtApellido"  name="txtApellido"   maxlength="150" autocomplete="off" />
            </div>
          </div>
          
          <div class="row p-3">
            
            <div class="col-lg-6" >
                            <label for="txtDireccion">Direcci&oacute;n (requerido)</label>
                            <input  class="form-control fs-4 p-1" type="text" tabindex="4" id="txtDireccion" name="txtDireccion"  maxlength="200" autocomplete="off" value="<?php echo $row['clientes_direccion']; ?>"  />
            </div>
                   <div class="col-lg-6" >
                               <label for="txtEmail">Email</label>
                               <input type="text" tabindex="7" value="<?php echo $row['clientes_email']; ?>"  class="form-control fs-4 p-1" id="txtEmail"  name="txtEmail"  maxlength="400" autocomplete="off" />
                               <!--<input type="text" tabindex="7" value="<?php echo $row['clientes_email']; ?>"  class="form-control fs-4 p-1" id="txtEmail"  name="txtEmail"  maxlength="50" autocomplete="off" onBlur="return isEmailAddress(txtEmail); " onKeyup="no_repetir_email_cliente(txtEmail,4);" onClick="no_repetir_email_cliente(txtEmail,4);"/>-->
                         
                               <div id="mensajeEmail"></div>
                               <div id="noRepetirEmail"></div>
                         </div>
          </div>  
          <div class="row p-3">
                  
                         <div class="col-lg-3" >
                            <label for="txtTelefono">Tel&eacute;fono</label>
                            <input type="text" tabindex="5" value="<?php echo $row['clientes_telefono']; ?>"  class="form-control fs-4 p-1" id="txtTelefono" name="txtTelefono" 
                            maxlength="30" autocomplete="off" />
                         </div>
                         <div class="col-lg-3" >
                            <label for="txtMovil">M&oacute;vil</label>
                            <input type="text" tabindex="6" value="<?php echo $row['clientes_movil']; ?>"   class="form-control fs-4 p-1" id="txtMovil" name="txtMovil" 
                            maxlength="30" autocomplete="off" maxlength="10" onKeyPress="return soloNumeros(event);" />


                         </div>
						 <div class="col-lg-6">
						 <label for="cbprovincia">Provincias (requerido)</label>
                                 <select class="form-select fs-4 p-1 " tabindex="15" name="cbprovincia" id="cbprovincia"
                                 onChange="combociudad3(3);">
                                 <option value="<?php echo $row['ciudades_id_provincia'] ?>" selected><?php echo $row['nombre_provincias'] ?> </option>
                <?php 
        { 
         $sql3="Select id_provincia, provincia From provincias where id_provincia !='".$row['ciudades_id_provincia']."' ";
       }
       $result3=mysqli_query($conexion,$sql3);
       while($ver3=mysqli_fetch_row($result3)){ 
         ?>      
         <option value="<?php echo $ver3[0]?>"><?php echo $ver3[1]?></option> <?php   } ?>
		 </select>
                                 <input type="hidden" name="opcion1" id="opcion1" value=""/>
								
                        </div>
						 </div>
						
                         <div class="row p-3" >
                             	 <div class="col-lg-6">
						 <label for="cbciudad">Ciudades (requerido)</label>
                               <select class="form-select fs-4 p-1" tabindex="16" name="cbciudad" id="cbciudad" onChange="" >
                                   
                <option value="<?php echo $row['id_ciudad'] ?>"selected><?php echo $row['ciudades_ciudad'] ?></option>
          
                               <?php 
        { 
         $sql4="Select id_ciudad, ciudad From ciudades where id_provincia ='".$row['ciudades_id_provincia']."' and  ciudad !='".$row['ciudades_ciudad']."' ";
       }
       $result4=mysqli_query($conexion,$sql4);
       if(!$result4){
           ?>
           <option value="0">Seleccione Ciudad:</option>
           <?php
       }
       while($ver4=mysqli_fetch_row($result4)){ 

         ?> 
    
          
         <option value="<?php echo $ver4[0]?>"><?php echo $ver4[1]?></option><?php   } ?>
                               </select>
                               <input type="hidden" name="opcion2" id="opcion2" value="" id="opcion2"/>
                        </div>
                        
                       
                         <div class="col-lg-4 ">
                <div class="switch-field ">
                    <input type="radio" id="radio_estado_activo" name="switch-estado" value="Activo" class="p-4" <?php if($row['clientes_estado'] == 'Activo'){ ?> checked <?php } ?>  />
                    <label for="radio_estado_activo">Activo</label>
                    <input type="radio" id="radio_estado_inactivo" name="switch-estado" value="Inactivo" class="p-4"  <?php if($row['clientes_estado'] == 'Inactivo'){ ?> checked <?php } ?>   />
                    <label for="radio_estado_inactivo">Inactivo</label>
                </div>
            </div> 
          
             </div>
          </div>
          
          
          
          
          <div class="row p-3">
                <div class="col-lg-6">
	                    <label for="txtMovil">Observaci&oacute;n</label>
                        <textarea type="text" tabindex="6"  class="form-control fs-4 p-1" id="txtObservacion" name="txtObservacion" 
                        autocomplete="off"  ><?php echo $row['clientes_observacion']; ?> </textarea>
	            </div>

	   </div>
	         
    <div class="row p-3">
        <div class="col-lg-6" >
            <label for="cbVendedor"> Vendedor </label>
            <select style="border-style: none;" class="form-select fs-5 p-1" tabindex="16" name="cbVendedor" id="cbVendedor" onChange="" >
                 <option value="0">Selecionar una opci&oacute;n:</option>
        <?php
        
            $sqlVendedor="SELECT `id_vendedor`, `codigo`, `nombre`, `apellidos`, `direccion`, `telefono`, `comision`, `id_empresa`, `caracter_identificacion`, `cedula`, `estado`, `email` FROM `vendedores` WHERE id_empresa=$sesion_id_empresa AND estado='Activo' ;";
        
                $resultVendedor = mysql_query($sqlVendedor); 
        while($rowVend = mysql_fetch_array($resultVendedor) ){
            
        ?>
            <option value="<?php echo $rowVend['id_vendedor'];?>" <?php if($row['clientes_id_vendedor'] == $rowVend['id_vendedor']){ ?>
            selected <?php } ?> ><?php echo $rowVend['nombre'].''.$rowVend['apellidos'];?></option>
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
			      // echo $sqlp;
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
        	<input  type="submit" value="Guardar" id="btnGuardar" tabindex="18" class="btn btn-success " name="btnGuardar"  />
            
      </div>
      </form>
        
<?php } ?>

<script type="">

</script>

</body>

</html>