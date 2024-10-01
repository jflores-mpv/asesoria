<?php
require_once('../ver_sesion.php');

	//Start session
session_start();

	//Include database connection details
require_once('../conexion.php');

?>

<html>
<head>

    <title>Modificar Proveedor</title>

    <!-- funcuiones para validar los campos  -->
    <script type="text/javascript" src="js/validaciones.js"></script>

    <!-- funciones -->
    <script type="text/javascript" src="js/funciones.js"></script>
    <!--END estilo para  el menu -->    
    
</head>

<body >
    <?php
    $id_proveedor=$_POST['id_proveedor'];
    $sqlm="SELECT
    paises.`id_pais` AS paises_id_pais,
    paises.`pais` AS paises_pais,
    proveedores.`id_proveedor` AS proveedores_id_proveedor,
    proveedores.rbCaracterIdentificacion AS proveedores_rbCaracterIdentificacion,
    proveedores.`nombre_comercial` AS proveedores_nombre_comercial,
    proveedores.`nombre` AS proveedores_nombre,
    proveedores.`direccion` AS proveedores_direccion,
    proveedores.`ruc` AS proveedores_ruc,
    proveedores.`telefono` AS proveedores_telefono,
    proveedores.`movil` AS proveedores_movil,
    proveedores.`fax` AS proveedores_fax,
    proveedores.`email` AS proveedores_email,
    proveedores.`web` AS proveedores_web,
    proveedores.`parteRel` AS proveedores_parteRel,
    proveedores.`observaciones` AS proveedores_observaciones,
    proveedores.`id_ciudad` AS proveedores_id_ciudad,
    proveedores.`id_plan_cuenta` AS proveedores_id_plan_cuenta,
    provincias.`id_provincia` AS provincias_id_provincia,
    provincias.`provincia` AS provincias_provincia,
    provincias.`id_pais` AS provincias_id_pais,
    ciudades.`id_ciudad` AS ciudades_id_ciudad,
    ciudades.`ciudad` AS ciudades_ciudad,
    ciudades.`id_provincia` AS ciudades_id_provincia

    FROM
    `paises` paises INNER JOIN `provincias` provincias ON paises.`id_pais` = provincias.`id_pais`
    INNER JOIN `ciudades` ciudades ON provincias.`id_provincia` = ciudades.`id_provincia`
    INNER JOIN `proveedores` proveedores ON ciudades.`id_ciudad` = proveedores.`id_ciudad`

    WHERE proveedores.`id_proveedor`='".$id_proveedor."' LIMIT 1; ";
              // echo $sqlm;
    $result=mysql_query($sqlm) or die("<div class='transparent_ajax_error'><p>Error en la consulta: \n".mysql_error()."</p></div>");
            while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
            {
                $codigo=$row['plan_cuentas_codigo'];
                $cadenaCodigo = split("\.",$codigo);
                $provincia= $row['ciudades_id_provincia'];
                $ciudad= $row['ciudades_id_ciudad'];
                ?>
                

                <div id="mensajeProveedor" ></div>
                <form name="form" id="form" method="post" action="javascript: guardarModificarProveedor(2);" >
                    <div style="display:none" id="mensaje" ></div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <H1>Modificar Proveedor</H1>
                            </div>
                        </div>
                        <div class="row r-10 p-3">
                            <div class="col-lg-6">
                                <label for="txtNombre" class="form-label">Nombre comercial (requerido)</label>
                                <input type="hidden" name="txtIdProveedor" value="<?php echo $row['proveedores_id_proveedor']; ?>" />
                                <input class="form-control fs-4 p-1"  type="text"  style="text-transform: capitalize;" tabindex="1" id="txtNombre" required 
                                name="txtNombre" maxlength="35" autocomplete="off" 
                                onKeyUp="no_repetir_nombre()" onClick="no_repetir_nombre()" 
                                value="<?php echo $row['proveedores_nombre_comercial']; ?>"/>
                                <div id="noRepetirNombre"></div><div id="nombreVacio"></div>
                            </div>
                            <div class="col-lg-6">
                                <label for="txtRazonSocial" class="form-label">Razón Social</label>
                                <input class="form-control fs-4 p-1"  type="text"  style="text-transform: capitalize;" tabindex="1" id="txtRazonSocial" required  value="<?php echo $row['proveedores_nombre']; ?>"
                                name="txtRazonSocial" maxlength="35" autocomplete="off" onKeyUp="no_repetir_nombre()" onClick="no_repetir_nombre()"/>
                                <div id="noRepetirNombre"></div><div id="nombreVacio"></div>
                            </div>
                            
                        </div>
                        <div class="row mt-1 r-10 p-3 ">
                            <div class="col-lg-6">
                                <label for="txtDireccion" class="form-label">Direcci&oacute;n (requerido)</label>
                                <input class="form-control fs-4 p-1"  type="text" value="<?php echo $row['proveedores_direccion']; ?>" style="text-transform: capitalize;" tabindex="2" id="txtDireccion"  required name="txtDireccion" title="Ingresa la direccion aquí"
                             />
                            </div>
                            <div class="col-lg-6">
                              
                                <label for="txtRucProveedor" class="form-label">
                                 
                                    <input type="radio" name="rbCaracterIdentificacion" id="rbCaracterIdentificacion" value="05" <?php if($row['proveedores_rbCaracterIdentificacion']=='5'||$row['proveedores_rbCaracterIdentificacion']=='05'){ ?> checked <?php } ?> onChange="cambiarTxt();"/><label for="rCedula">Cedula</label>
                                    <input type="radio" name="rbCaracterIdentificacion" id="rbCaracterIdentificacion"  value="04" <?php if($row['proveedores_rbCaracterIdentificacion']=='4'||$row['proveedores_rbCaracterIdentificacion']=='04'){ ?> checked <?php } ?>  onchange="cambiarTxt();"/><label for="rRuc"> Ruc  </label>
                                    <input type="radio" name="rbCaracterIdentificacion" id="rbCaracterIdentificacion" value="06"  <?php if($row['proveedores_rbCaracterIdentificacion']=='7'||$row['proveedores_rbCaracterIdentificacion']=='07'){ ?> checked <?php } ?>  onchange="cambiarTxt();"/><label for="rPasaporte">Pasaporte </label>
                                    <!--   <input type="radio" name="rbCaracterIdentificacion" id="rbCaracterIdentificacion" value="O"  onchange="cambiarTxt();"/><label for="rExportacion">Otros </label> --->
                                </label>
                                <input type="text" tabindex="3" id="txtRuc" class="form-control fs-4 p-1" required name="txtRuc" 
                                title="Ingresa el Código"   onBlur="return cedula_ruc(txtRucProveedor)"  
                                onKeyup="no_repetir_ruc(txtRucProveedor,4);"  autocomplete="off" onKeyPress="return soloNumeros(evt)"
                                value="<?php echo $row['proveedores_ruc']; ?>"/> 
                                <div id="noRepetirRuc"></div><div id="mensageCedula"></div> 
                                <!-- return long_cedula_ruc(txtRucProveedor,rbCaracterIdentificacion);--->
                            </div>
                            
                        </div>
                        <div class="row  mt-1  r-10 p-3">
                            <div class="col-lg-6">
                                <label for="txtRucProveedor" class="form-label">T&eacute;lefono (requerido)</label>
                                <input type="text" tabindex="4" id="txtTelefono" class="form-control fs-4 p-1" required name="txtTelefono" title="Ingresa el numero de telefono aquí" value="<?php echo $row['proveedores_telefono']; ?>"
                                maxlength="10" onKeyPress="return soloNumeros(evt)"/>
                            </div>
                            <div class="col-lg-6">
                                <label for="txtEmail" class="form-label">Email </strong></label>
                                <input type="text" tabindex="6" id="txtEmail" class="form-control fs-4 p-1" name="txtEmail" required title="Ingresa el email aquí Ej nombre@hotmail.com" value="<?php echo $row['proveedores_email']; ?>"
                                maxlength="35" onBlur="return isEmailAddress(txtEmail)"/>
                                <div id="mensajeEmail"></div>
                            </div>
                        </div>
                        <div class="row mt-1 r-10 p-3 ">
                           <div class="col-lg-4">
                            <label for="cmbTipoProveedor" class="form-label">Tipo Proveedor</label>
                            <select  id="cmbTipoProveedor" name="cmbTipoProveedor" required="required"  class="form-control fs-4 p-1"  data-live-search="true" > 
                                <option value="01">Persona Natural</option>
                                <option value="02">Sociedad</option>
                            </select>
                        </div>
                        
                        <div class="col-lg-4">
                            <label for="cbprovincia" class="form-label">Provincias (requerido)</label>

                            <select tabindex="11" class="form-control fs-4 p-1" name="cbprovincia" id="cbprovincia" onChange="combociudad(3)">

                                <option value="<?php echo $row['ciudades_id_provincia'] ?>"  ><?php echo $row['provincias_provincia'] ?> </option>
                                <?php 
                                { 
                                    echo $sql3="Select id_provincia, provincia From provincias where id_provincia  !='".$row['ciudades_id_provincia']."' ";
                                }
                                $result3=mysql_query($sql3);
                                while($ver3=mysql_fetch_row($result3)){ 
                                   ?> 
                                   
                                   <option value="<?php echo $ver3[0]?>"><?php echo $ver3[1]?></option><?php   } ?>


                               </select>
                               <input type="hidden" name="opcion1" value="<?php echo $row['ciudades_id_provincia'] ?>">
                           </div>
                           <div class="col-lg-4">
                            <label for="txtRucProveedor" class="form-label">Ciudades (requerido)</label>
                            <select tabindex="12" class="form-control fs-4 p-1" name="cbciudad" id="cbciudad" onChange="mostrarcombo()" >
                               <option value="<?php echo $row['ciudades_id_ciudad'] ?>"><?php echo $row['ciudades_ciudad'] ?></option>
                               <?php 
                               { 
                                   $sql4="Select id_ciudad, ciudad From ciudades where id_provincia ='".$row['ciudad_id_provincia']."' and  ciudad !='".$row['ciudad_id_ciudad']."' ";
                               }
                               $result4=mysql_query($sql4);
                               if(!$result4){
                                 ?>
                                 <option value="0">Seleccione Ciudad:</option>
                                 <?php
                             }
                             while($ver4=mysql_fetch_row($result4)){ 

                               ?> 
                               
                               
                               <option value="<?php echo $ver4[0]?>"><?php echo $ver4[1]?></option><?php   } ?>

                           </select>
                           <input type="hidden" name="opcion2" value="<?php echo $row['ciudades_id_ciudad'] ?>" id="opcion2">
                       </div>

                       
                   </div>
                   <div class="path2"></div>
                   <label style="display: none" for="cbpais"><strong class="leftSpace">Pais (requerido)</strong></label>
                   <select style="visibility: hidden" tabindex="10" class="text_input10 required"  name="cbpais" id="cbpais" 
                   onChange="comboprovincia(2);mostrarcombo()" ></select>
                   <input type="hidden" name="opcion" value="1"/>

                   
               </tr>
               <tr>
                
                <div class="row mt-1 r-10 p-3 ">
                <!-- <div class="col-lg-4">
                <label for="radio-estado-activo" class="form-label">Estado de Proveedor</label>
                    <div class="switch-field my-3">
                        <input type="radio" id="radio-estado-activo" name="switch-estado" value="Activo"/>
                        <label for="radio-estado-activo">Activo</label>

                        <input type="radio" id="radio-estado-inactivo" name="switch-estado" value="Inactivo" />
                        <label for="radio-estado-inactivo">Inactivo</label>
                       
                       
                    </div>
                </div>     -->
                
                <div class="col-lg-4">
                    <label for="radio-parteRel-SI" class="form-label">Parte Rel</label>
                    <div class="switch-field my-3">
                        <input type="radio" id="radio-parteRel-SI" name="switch-parteRel" value="SI"  <?php if($row['proveedores_parteRel']==='SI'){ ?> checked <?php } ?>/>
                        <label for="radio-parteRel-SI">SI</label>

                        <input type="radio" id="radio-parteRel-NO" name="switch-parteRel" value="NO" <?php if($row['proveedores_parteRel']==='NO'){ ?> checked <?php } ?> />
                        <label for="radio-parteRel-NO">NO</label>
                        
                        
                    </div>
                </div> 
                <!-- <div class="col-lg-4">
            <label for="diasCredito" class="form-label">Dias cr&eacute;dito</label>
            <input type="text" tabindex="3" id="diasCredito" class="form-control fs-4 p-1" name="diasCredito"   autocomplete="off" onKeyPress="return soloNumeros(evt)" value=""/> 
                   
                   
        </div> -->
    </div>
    
    
</tr>
</tbody>
</table>  

<center>
    


 <div class="modal-footer">
    <button type="button" class="btn btn-default" onClick="fn_cerrar()">Cerrar</button>
    <button class="btn btn-success" value="Guardar" type="submit" id="submit"  name="btnGuardar"  onclick="fn_cerrar();">Guardar Proveedor</button>
    
</div>


</center> 
</form>
<?php }    ?>
</body>
<script type="text/javascript">
    
    $(document).ready(function(){
     
                // comboprovincia(<?php echo $provincia ?>); 

                
            });
        </script>
        </html>