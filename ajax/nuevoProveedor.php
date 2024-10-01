<?php
	require_once('../ver_sesion.php');

	//Start session
	session_start();
		
	//Include database connection details
	require_once('../conexion.php');
		
?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

<title>Nuevo Proveedor</title>

<script type="text/javascript">
        
    $(document).ready(function(){
            combopais(1);
            comboprovincia(2);    
            comboRetencionIva(6);
          comboRetencionFuente(7)              
    });
</script> 
     
</head>

<body onLoad="">

        <div id="mensajeProveedor" ></div>
        <form name="form" id="form" method="post" action="javascript: guardar_proveedores_modal();" >
             <div class="modal-body">
        <div class="row">
            <div class="col-lg-12 text-center">
                <H1>Nuevo Proveedor</H1>
            </div>
        </div>
            <div class="row r-10 p-3">
                <div class="col-lg-6">
                    <label for="txtNombreComercial" class="form-label">Nombre comercial (requerido)</label>
                    <input class="form-control fs-4 p-1"  type="text"  style="text-transform: capitalize;" tabindex="1" id="txtNombreComercial" required 
                    name="txtNombreComercial" maxlength="300" autocomplete="off" 
                    onKeyUp="no_repetir_nombre()" onClick="no_repetir_nombre()"/>
                    <div id="noRepetirNombre"></div><div id="nombreVacio"></div>
                </div>
                <div class="col-lg-6">
                    <label for="txtNombreComercial" class="form-label">Razón Social</label>
                    <input class="form-control fs-4 p-1"  type="text"  style="text-transform: capitalize;" tabindex="1" id="txtRazonSocial" required 
                    name="txtRazonSocial" maxlength="300" autocomplete="off" onKeyUp="no_repetir_nombre()" onClick="no_repetir_nombre()"/>
                    <div id="noRepetirNombre"></div><div id="nombreVacio"></div>
                </div>
                
            </div>
            <div class="row mt-1 r-10 p-3 ">
                <div class="col-lg-6">
                    <label for="txtDireccion" class="form-label">Direcci&oacute;n (requerido)</label>
                    <input class="form-control fs-4 p-1"  type="text" style="text-transform: capitalize;" tabindex="2" id="txtDireccion"  required name="txtDireccion" title="Ingresa la direccion aquí"
                    maxlength="35" />
                </div>
                <div class="col-lg-6">
                        <label for="txtRucProveedor" class="form-label">
                       
                            <input type="radio" name="rbCaracterIdentificacion" id="rbCaracterIdentificacion" value="05" checked onChange="cambiarTxt();"/><label for="rCedula">Cedula</label>
                            <input type="radio" name="rbCaracterIdentificacion" id="rbCaracterIdentificacion"  value="04"  onchange="cambiarTxt();"/><label for="rRuc"> Ruc  </label>
                            <input type="radio" name="rbCaracterIdentificacion" id="rbCaracterIdentificacion" value="06"  onchange="cambiarTxt();"/><label for="rPasaporte">Pasaporte </label>
                         <!--   <input type="radio" name="rbCaracterIdentificacion" id="rbCaracterIdentificacion" value="O"  onchange="cambiarTxt();"/><label for="rExportacion">Otros </label> --->
                        </label>
                        <input type="text" tabindex="3" id="txtRucProveedor" class="form-control fs-4 p-1" required name="txtRucProveedor" 
                            title="Ingresa el Código"   onBlur="return cedula_ruc(txtRucProveedor.value)"  
                            onKeyup="no_repetir_ruc(txtRucProveedor,4);"  autocomplete="off" onKeyPress="return soloNumeros(evt)"/> 
                            <div id="noRepetirRuc"></div><div id="mensageCedula"></div> <div id="mensageCedula2"></div> 
                            <!-- return long_cedula_ruc(txtRucProveedor,rbCaracterIdentificacion);--->
                </div>
                
            </div>
            <div class="row  mt-1  r-10 p-3">
                <div class="col-lg-6">
                    <label for="txtRucProveedor" class="form-label">T&eacute;lefono (requerido)</label>
                    <input type="text" tabindex="4" id="txtTelefono" class="form-control fs-4 p-1" required name="txtTelefono" title="Ingresa el numero de telefono aquí" 
                    maxlength="10" onKeyPress="return soloNumeros(evt)"/>
                </div>
                <div class="col-lg-6">
                    <label for="txtEmail" class="form-label">Email </strong></label>
                    <input type="text" tabindex="6" id="txtEmail" class="form-control fs-4 p-1" name="txtEmail" required title="Ingresa el email aquí Ej nombre@hotmail.com" 
                    maxlength="250" onBlur="return isEmailAddress(txtEmail)"/>
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
                    <label for="txtRucProveedor" class="form-label">Provincias (requerido)</label>
                    <select tabindex="11" class="form-control fs-4 p-1" name="cbprovincia" id="cbprovincia" onChange="combociudad(3);mostrarcombo()"></select>
                            <input type="hidden" name="opcion1" value="">
                </div>
                <div class="col-lg-4">
                    <label for="txtRucProveedor" class="form-label">Ciudades (requerido)</label>
                     <select tabindex="12" class="form-control fs-4 p-1" name="cbciudad" id="cbciudad" onChange="mostrarcombo()" ></select>
                            <input type="hidden" name="opcion2" value="" id="opcion2">
                </div>

               
            </div>
       
                
                <div class="row mt-1 r-10 p-3 ">
            <!-- <div class="col-lg-4">
            <label for="radio-estado-activo" class="form-label">Estado de Proveedor</label>
                <div class="switch-field my-3">
                    <input type="radio" id="radio-estado-activo" name="switch-estado" value="Activo" checked/>
                    <label for="radio-estado-activo">Activo</label>

                    <input type="radio" id="radio-estado-inactivo" name="switch-estado" value="Inactivo" />
                    <label for="radio-estado-inactivo">Inactivo</label>
                   
                   
                </div>
            </div>     -->
        
            <div class="col-lg-4">
            <label for="radio-parteRel-SI" class="form-label">Parte Rel</label>
                <div class="switch-field my-3">
                    <input type="radio" id="radio-parteRel-SI" name="switch-parteRel" value="SI" checked/>
                    <label for="radio-parteRel-SI">SI</label>

                    <input type="radio" id="radio-parteRel-NO" name="switch-parteRel" value="NO" />
                    <label for="radio-parteRel-NO">NO</label>
                   
                   
                </div>
            </div> 
            <!-- <div class="col-lg-4">
            <label for="diasCredito" class="form-label">Dias cr&eacute;dito</label>
            <input type="text" tabindex="3" id="diasCredito" class="form-control fs-4 p-1" name="diasCredito"   autocomplete="off" onKeyPress="return soloNumeros(evt)"/> 
                   
                   
                </div> -->
            </div> 
          

        </div>
           
                
              
            <div class="path2"></div>
                <label style="display: none" for="cbpais"><strong class="leftSpace">Pais (requerido)</strong></label>
                <select style="visibility: hidden" tabindex="10" class="text_input10 required" required="required" name="cbpais" id="cbpais" 
                onChange="comboprovincia(2);mostrarcombo()" ondblclick="combopais(1);"></select>
                <input type="hidden" name="opcion" value="1"/>

                        
                    </tr>
                </tbody>
            </table>  
              
       <center>
            


               <div class="modal-footer">
        <button type="button" class="btn btn-default" onClick="fn_cerrar()">Cerrar</button>
           <button class="btn btn-success" value="Guardar" type="submit" id="submit"  name="btnGuardar" >Guardar Proveedor</button>
            
      </div>
       
       
      </center> 
            </form>
</body>
    
</html>