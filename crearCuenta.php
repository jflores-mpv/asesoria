<?php
	//Start session
	session_start();
    require_once('clases/configuraciones.php');
    $configuraciones = new configuraciones();
    //require_once('ver_sesion.php');
    $sesion_logo_sistema = $_SESSION["sesion_logo_sistema"];
    $cookie_tipo = $_COOKIE['tipo_cookie'];
    $sesion_tipo = $_SESSION["sesion_tipo"];     
    
    header("Cache-Control: no-cache, no-store, must-revalidate"); // Evita el almacenamiento en caché en navegadores modernos
    header("Pragma: no-cache"); // Evita el almacenamiento en caché en navegadores antiguos
    header("Expires: 0"); // Establece la fecha de expiración en el pasado
?>

<html lang="es-ES">
    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Asesoria Empresarial y Contabilidad</title>
    <script language="javascript" type="text/javascript" src="js/index.js"></script>

    <!--validaciones de los campos -->
    <script type="text/javascript" src="js/validaciones.js"></script>
    <!-- funciones -->
    <script type="text/javascript" src="js/funciones.js"></script>

    <!--busquedas desplegables -->
    <script type="text/javascript" src="js/busquedas.js"></script>
	<script type="text/javascript" src="js/institucion.js"></script>
	
    <script type="text/javascript" src="js/index.js"></script>
    <script type="text/javascript" src="js/validacion202006.js"></script>
	
    <script src="https://kit.fontawesome.com/7816c50242.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">  
    <script src="librerias/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <script src="librerias/bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" href="librerias/bootstrap/css/style.css"> 
    <link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/alertify.css">
   	<script src="librerias/alertifyjs/alertify.js"></script>
    <link rel="stylesheet" href="librerias/dist/css/bootstrap-select.css">
    <link rel="stylesheet" type="text/css" href="librerias/select2/css/select2.css">
    <script src="librerias/bootstrap/js/main.js"></script>    
    
    <script type="text/javascript" src="js/validaciones.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<!--<script language="javascript" type="text/javascript" src="js/jquery-1.4.1.min.js"></script>  -->
    <script language="javascript" type="text/javascript" src="js/jquery.blockUI.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.validate.1.5.2.js"></script>


    <style>
        body {
            background: url('images/back1.png') no-repeat center center fixed; /* Ruta a tu imagen de fondo */
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.9); /* Fondo blanco semitransparente */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px; /* Ajusta según tus necesidades */
            width: 100%;
            margin: auto;
        }
    </style>

</head>


<body onload="combopais(1); comboprovincia(2);" class="bg-light">

			
	<form name="form" id="form" method="post" action="javascript: guardar_empresa(1);" class="bg-white rounded shadow m-5 p-5"  >
		<div class="row">
		    <h1>Registro</h1>
		</div>
		<div class="row">
			<div class="col-lg-4"> 
			    <div class="mb-3 mx-4">
                    <label for="exampleFormControlInput1" class="form-label">Nombre Empresa</label>
                    <input type="text" id="txtNombreEmpresa" name="txtNombreEmpresa" maxlength="70" 
				    required="required" class="form-control" placeholder="Nombre Comercial" />
                </div>
	     	</div>
	     	<div class="col-lg-4"> 
			    <div class="mb-3 mx-4">
                    <label for="exampleFormControlInput1" class="form-label">Razón Social</label>
                    <input type="text" id="txtRazonSocial" name="txtRazonSocial" maxlength="70" 
				    required="required" class="form-control" placeholder="Nombre Comercial" />
                </div>
	     	</div>
	     	
	     	<div class="col-lg-4"> 
			    <div class="mb-3 mx-4">
                    <label for="exampleFormControlInput1" class="form-label">Dirección</label>
                     <input type="text" id="txtDireccion" class="form-control" name="txtDireccion" placeholder="Ingrese la direccion de la Empresa" 
                    title="Direccion" maxlength="60"  />
                </div>
	     	</div>
	     	
	     	<div class="col-lg-4"> 
			    <div class="mb-3 mx-4">
                    <label for="exampleFormControlInput1" class="form-label">Contraseña de ingreso</label>
                        <input class="form-control" placeholder="Contraseña de ingreso" type="text"  id="txtCodEmpresa" 
                        name="txtCodEmpresa" required="required" onkeyup="noRepetirCodigoEmpresa(txtCodEmpresa, 9);"
                        onclick="noRepetirCodigoEmpresa(txtCodEmpresa, 9);"/>
                        <div id="mensajeNoRepetirCodigoEmpresa"></div>
                </div>
	     	</div>
	     	
	     	<div class="col-lg-4"> 
			    <div class="mb-3 mx-4">
                    <label for="exampleFormControlInput1" class="form-label">Ruc o Cédula</label>
                        <input type="text" size="22" id="txtCedula" class="form-control" name="txtCedula" 
                        placeholder="Ingrese su cédula/RUC" required="required"  autocomplete="off" maxlength="13" onblur="return cedula_ruc(txtCedula)" 
                        onKeyup="noRepetirRucEmpresa(txtCedula,5);" onclick="noRepetirRucEmpresa(txtCedula,5); " />
                        <div id="mensageCedula"></div>
                        <div id="mensageCedula2"></div>
                </div>
	     	</div>
	     	<div class="col-lg-4"> 
			    <div class="mb-3 mx-4">
                    <label for="exampleFormControlInput1" class="form-label">Teléfono</label>
                     <input class="form-control" placeholder="Teléfono" type="text" id="txtTelefono1" name="txtTelefono1" maxlength="20" required="required"  />
                </div>
	     	</div> 
            <input type="hidden" disabled tabindex="1" id="txtTipoEmpresa" class="form-control" name="txtTipoEmpresa" required="required" value="20">
	     		     
            <div class="col-lg-4"> 
			    <div class="mb-3 mx-4">
                    <label for="exampleFormControlInput1" class="form-label">Email</label>
                    <input class="form-control" placeholder="E-mail" type="text"  id="txtEmail" name="txtEmail" maxlength="50" required="required" 
                    autocomplete="off" onblur="no_repetir_email_empresa(txtEmail,7); return isEmailAddress(txtEmail); " onKeyup="no_repetir_email_empresa(txtEmail,7);"
                    onclick="no_repetir_email_empresa(txtEmail,7);"/>
                    <div id="noRepetirEmail"></div>
                 <div id="mensajeEmail"></div>
                </div>
	     	</div>  
	     	
	     	
	     	

                
            <div class="col-lg-2">
                <div class="mb-3 ml-4">
                <select hidden name="cbpais" id="cbpais" onChange="comboprovincia(2);mostrarcombo();"></select>
                <input type="hidden" name="opcion" value="1"/>
                <label for="exampleFormControlInput1" class="form-label">Provincia</label>
                 <select class="form-select" tabindex="7" required="required"  name="cbprovincia" id="cbprovincia" 
                 onChange="combociudad(3);mostrarcombo()"></select>
                 <input type="hidden" name="opcion1" value=""/>
                </div> 
            </div>
            
            <div class="col-lg-2">
                <div class="mb-3 mr-4">
                <label for="exampleFormControlInput1" class="form-label">Ciudad</label>
                     <select  tabindex="3" id="cbciudad" class="form-select" required="required" name="cbciudad" 
                     onChange="mostrarcombo()"></select>
                     <input type="hidden" name="opcion2" id="opcion2" value=""/>
                </div>
            </div>
            
            <div class="col-lg-2">
                <div class="mb-3 ml-4">
                <label for="exampleFormControlInput1" class="form-label">Cantidad Comprobantes</label>
                     <input class="form-control text-center" placeholder="cantidadFacturas" type="text" 
                        id="cantidadFacturas"  name="cantidadFacturas" required="required" value="0"/> 
                </div>
            </div>
            
            <div class="col-lg-6" style="visibility:hidden">
                <label>Estado</label>
                <select class="form-select w-90 grid-1-2 m-auto" name="cmbEstado" id="cmbEstado" onChange="mostrarcombo()" >
                        <option value="Inactivo">Inactivo</option>
                </select>
            </div>
            


             <div class="col-lg-12 text-center my-1 p-5">
                 <a  class="btn btn-outline-info " href="index.php" type="button" value="Guardar" id="btnGuardar" name="btnGuardar">Regresar a inicio</a>
                 <button  class="btn btn-outline-info " type="submit" value="Guardar" id="btnGuardar" name="btnGuardar">Guardar Registro</button>
             </div>
        </div>
     </form>    

</body>


</html>