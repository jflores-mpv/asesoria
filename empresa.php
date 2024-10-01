<?php	

   //Start session
    session_start();
    //require_once('ver_sesion.php');
    //Include database connection details
    require_once('conexion.php');

    require('ver_sesion.php');
    $sesion_id_institucion_educativa= $_SESSION["sesion_id_institucion_educativa"];
 
    $sesion_id_nivel_institucion = $_SESSION["sesion_id_nivel_institucion"];
    $fcha = date("Y-m-d");
	$fecha = date("Y-m-d H:i:s");
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
  
	$sesion_cod_activacion_prof = $_SESSION["sesion_cod_activacion"];
	$sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];

    
?>

<html lang="es-ES">
    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Perfil</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.png">
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

    <!-- START ESTILOS Y CLASES PARA AJAX -->
    <script language="javascript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<!--<script language="javascript" type="text/javascript" src="js/jquery-1.4.1.min.js"></script>   
--> <script language="javascript" type="text/javascript" src="js/jquery.blockUI.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.validate.1.5.2.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js" ></script>
    
    <script type="text/javascript">
        $(document).ready(function(){
            //combopais(1);
            //consulta_empresa(2);
                $("#ajax_contactform").validate();
        });
    </script>
   
    <!-- END ESTILOS Y CLASES PARA AJAX -->
	<script type="text/javascript" src="js/institucion.js"></script>
	<script type="text/javascript" src="js/index.js"></script>
    <!--validaciones de los campos -->
    <script type="text/javascript" src="js/validaciones.js"></script>
    <!-- funciones -->
    <script type="text/javascript" src="js/funciones.js"></script>

    <!--busquedas desplegables -->
    <script type="text/javascript" src="js/busquedas.js"></script>
    <script type="text/javascript" src="js/establecimientos.js"></script>
    <script type="text/javascript" src="js/transportistas.js"></script>
     <script type="text/javascript" src="js/impuestos.js"></script>
     <script type="text/javascript" src="js/retenciones.js"></script>
  <script type="text/javascript" src="js/centro_costos_empresa.js"></script>
    <!--END estilo para  el menu -->
    <link rel="shortcut icon" href="images/logo.png">
    
</head>


<body onload=" listar_impuestos(); " >


<div class="wrapper d-flex align-items-stretch celeste">
        <nav id="sidebar" ><?php  {include("menusNiveles.php");      }   ?></nav>
    <div id="content"  >
        <header ><?php  {include("header.php");      }   ?>  </header>
    
            <div class="row  r-10   mx-5 mt-5  p-3">
                
            <div class="col-lg-6 p-3 ">
                <?php
                   $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
                
                    try {
                    
                    $sqle="SELECT
                          id_empresa as empresaId,
                          nombre as empresaNombre,
                          razonSocial as razonSocial,
                           direccion as direccion,
                          ruc as ruc,
                          telefono1 as empresaTelefono1,
                          email as empresaEmail,
                          codigo_empresa as empresaCodigo,
                          id_tipo_empresa as empresaTipo,
                          id_ciudad as id_ciudad,
                          clave as clave,
                          imagen as imagen,
                          FElectronica as firma,
                          Ocontabilidad as obligado,
                        email as email,
                          leyenda as leyenda,
                          leyenda2 as leyenda2,
                          leyenda3 as leyenda3,
                          leyenda4 as leyenda4,
                          nombreContador,
                          limiteFacturas as limiteFacturas,
                          autorizacion_sri as firmaC,
                          ruc_representante,
                          ruc_contador
                    FROM
                          `empresa` empresa where id_empresa=". $sesion_id_empresa." ; ";
        
                    $result=mysql_query($sqle);
                    while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                        {
                           $id_empresa=$row['empresaId'];         
                           $obligado=$row['obligado']; 
                           
                    ?>
                            
                        <form name="form" id="form" method="post" action="javascript: modificar_empresa(3);" >
                            <div class="row bg-white rounded p-3">
                                <h3>Datos Empresa</h3>
                                
                                <div class="col-lg-12 p-1">
                                    <label for="txtNombreEmpresa"><strong class="leftSpace">Nombre Comercial</strong></label>
                                    <input hidden=""  id="txtIdEmpresa" value="<?php echo $row['empresaId']; ?>" class="form-control" name="txtIdEmpresa" />
                                    <input style="text-transform: capitalize;" type="text" tabindex="1" value="<?php echo $row['empresaNombre']; ?>" 
                                    id="txtNombreEmpresa" class="form-control" name="txtNombreEmpresa" placeholder="Ingrese el nombre del Sistema" maxlength="250"  />
                                </div>
                                
                                <div class="col-lg-12 p-1">
                                    <label for="txtrazonSocial"><strong class="leftSpace">Raz&oacute;n Social</strong></label>
                                    <input hidden=""  id="txtIdEmpresa" value="<?php echo $row['empresaId']; ?>" class="form-control" name="txtIdEmpresa" />
                                    <input style="text-transform: capitalize;" type="text" tabindex="1" value="<?php echo $row['razonSocial']; ?>" 
                                    id="txtrazonSocial" class="form-control" name="txtrazonSocial" placeholder="Ingrese Raz車n Social" maxlength="250"  />
                                </div>
                                
                                <div class="col-lg-6 p-1">
                                    <label for="txtRuc"><strong class="leftSpace">RUC</strong></label>
                                    <input style="text-transform: capitalize;" type="text" tabindex="1" value="<?php echo $row['ruc']; ?>" 
                                    id="txtRuc" class="form-control" name="txtRuc" placeholder="Ingrese el nombre del Sistema" maxlength="70"  />
                                </div>
                                
                                <div class="col-lg-6 p-1">
                                    <label for="txtDireccion"><strong class="leftSpace">Contraseña Ingreso</strong></label>
                                    <input type="text" tabindex="1" value="<?php echo $row['empresaCodigo']; ?>" 
                                    id="empresaCodigo" class="form-control" name="empresaCodigo" placeholder="Ingrese el nombre del Sistema" maxlength="70"  />
                                </div>
                                
                                <div class="col-lg-12 p-1">
                                    <label for="txtDireccion"><strong class="leftSpace">Direcci&oacute;n</strong></label>
                                    <input style="text-transform: capitalize;" type="text" tabindex="1" value="<?php echo $row['direccion']; ?>" 
                                    id="txtDireccion" class="form-control" name="txtDireccion" placeholder="Ingrese el nombre del Sistema" maxlength="70"  />
                                </div>
                                 <div class="col-lg-8 p-1">
                                    <label for="txtDireccion"><strong class="leftSpace">Email</strong></label>
                                    <input  type="text" tabindex="1" value="<?php echo $row['email']; ?>" 
                                    id="txtEmail" class="form-control" name="txtEmail" placeholder="Ingrese el nombre del Sistema" maxlength="70"  />
                                </div>
                                
                                  <div class="col-lg-4 p-1">
                                    <label for="txtDireccion"><strong class="leftSpace">Tel&eacute;fono</strong></label>
                                    <input  type="text" tabindex="1" value="<?php echo $row['empresaTelefono1']; ?>" 
                                    id="empresaTelefono1" class="form-control" name="empresaTelefono1" placeholder="Numero" maxlength="70"  />
                                </div>
                                
                                
                                
                                <div class="col-lg-6 mt-3"><label>Obligado a llevar contabilidad</label></div>
                                <div class="col-lg-6 mt-3">
                                        <div class="switch-field">
                                            <?php if($obligado== '1')   { ?>
                                                <input type="radio" id="radio-obligado" name="switch-three" value="1" checked />
                                                <label for="radio-obligado">SI</label>
                                                
                                                <input type="radio" id="radio-no" name="switch-three" value="2"  />
                                                <label for="radio-no">NO</label>
                                                
                                            <?php }else { ?>
                                            
                                                <input type="radio" id="radio-obligado" name="switch-three" value="1" />
                                                <label for="radio-obligado">SI</label>
                                                
                                                <input type="radio" id="radio-no" name="switch-three" value="2" checked />
                                                <label for="radio-no">NO</label>
                                            
                                            <?php }  ?>
                                        </div>
                                </div>
                                
                                
                                
                                <label for="txtLeyenda" class="mt-5"><strong class="leftSpace">Contribuyente Regimen Rimpe:</strong></label>
                                
                                <div class="switch-field">
                                    <input type="radio" id="radio-leyendaR" name="switch-rimpe" value="CONTRIBUYENTE RÉGIMEN RIMPE" <?php if($row['leyenda']=='CONTRIBUYENTE RÉGIMEN RIMPE'){?> checked <?php } ?> />
                                    <label for="radio-leyendaR">CONTRIBUYENTE RÉGIMEN RIMPE</label>
                                    <input type="radio" id="radio-leyendaN" name="switch-rimpe" value="CONTRIBUYENTE NEGOCIO POPULAR - RÉGIMEN RIMPE"  <?php if($row['leyenda']=='CONTRIBUYENTE NEGOCIO POPULAR - RÉGIMEN RIMPE'){?> checked <?php } ?> />
                                    <label for="radio-leyendaN">CONTRIBUYENTE NEGOCIO POPULAR</label>
                                    <input type="radio" id="radio-leyendaO" name="switch-rimpe" value=""  />
                                    <label for="radio-leyendaO">Otro</label>
                                </div>
                                
                                <div class="col-lg-12 p-1 mt-3">
                                    
                                    <label for="txtLeyenda" class="mt-5"><strong class="leftSpace">Agente de retención:</strong></label>
                                
                                <div class="switch-field">
                                    <input type="radio" id="radio-leyenda1" name="txtLeyenda2" value="1"
                                    <?php if($row['leyenda2']=='1'){?> checked <?php } ?> />
                                    <label for="radio-leyenda1">Nro. NAC-DNCRASC20-00000001</label>
                                    
                                    <input type="radio" id="radio-leyenda2" name="txtLeyenda2" value="2" 
                                    <?php if($row['leyenda2']=='2'){?> checked <?php } ?> />
                                    <label for="radio-leyenda2">NAC- GTRRIOC22-00000001</label>
                                    
                                    <input type="radio" id="radio-leyenda3" name="txtLeyenda2" value="3" 
                                    <?php if($row['leyenda2']=='3'){?> checked <?php } ?> />
                                    <label for="radio-leyenda3">NAC- GTRRIOC22-00000003</label>
                                    
                                    <input type="radio" id="radio-leyenda4" name="txtLeyenda2" value=""  />
                                    <label for="radio-leyenda4">Ninguna</label>
                                </div>
                                    
                                    
                                    <!--<label for="txtLeyenda"><strong class="leftSpace">Agente de retención:</strong></label>-->
                                    <!--<input style="text-transform: capitalize;" type="text" tabindex="1" value="<?php echo $row['leyenda2']; ?>" -->
                                    <!--id="txtLeyenda2" class="form-control" name="txtLeyenda2" placeholder="" maxlength="70"  />-->
                                </div>
                                
                                 <div class="col-lg-12 p-1">
                                    <label for="txtLeyenda"><strong class="leftSpace">LEYENDA</strong></label>
                                    <input style="text-transform: capitalize;" type="text" tabindex="1" value="<?php echo $row['leyenda3']; ?>" 
                                    id="txtLeyenda3" class="form-control" name="txtLeyenda3" placeholder="" maxlength="300"  />
                                </div>
                                
                                <div class="col-lg-12 p-1">
                                    <label for="txtLeyenda"><strong class="leftSpace">LEYENDA</strong></label>
                                    <input style="text-transform: capitalize;" type="text" tabindex="1" value="<?php echo $row['leyenda4']; ?>" 
                                    id="txtLeyenda4" class="form-control" name="txtLeyenda4" placeholder="" maxlength="70"  />
                                </div>
                                
                                   <div class="col-lg-12 p-1">
                                    <label for="txtLeyenda"><strong class="leftSpace">Contador</strong></label>
                                    <input style="text-transform: capitalize;" type="text" tabindex="1" value="<?php echo $row['nombreContador']; ?>" 
                                    id="nombreContador" class="form-control" name="nombreContador" placeholder="" maxlength="70"  />
                                </div>
                                 <div class="col-lg-12 p-1">
                                    <label for="txtRucContador"><strong class="leftSpace">R.U.C. Contador</strong></label>
                                    <input style="text-transform: capitalize;" type="text" tabindex="1" value="<?php echo $row['ruc_contador']; ?>" 
                                    id="txtRucContador" class="form-control" name="txtRucContador" placeholder="" maxlength="20"  />
                                </div>
                                 <div class="col-lg-12 p-1">
                                    <label for="txtRucRepresentanteLegal"><strong class="leftSpace">R.U.C. Representante Legal</strong></label>
                                    <input style="text-transform: capitalize;" type="text" tabindex="1" value="<?php echo $row['ruc_representante']; ?>" 
                                    id="txtRucRepresentanteLegal" class="form-control" name="txtRucRepresentanteLegal" placeholder="" maxlength="20"  />
                                </div>
                                
                                 <div class="col-lg-8 p-1">
                                    <label for="txtLeyenda"><strong class="leftSpace">FACTURAS RESTANTES</strong></label>
                                </div>
                                
                                 <div class="col-lg-2">
                                    <input style="text-transform: capitalize;" type="text" tabindex="1" readonly value="<?php echo $row['limiteFacturas']; ?>" 
                                     class="form-control"  placeholder="" maxlength="70"  />
                                </div>
                                
                                
                                <div class="col-lg-12 p-1 m-5  ">
                                    <input type="submit" value="Actualizar Datos" id="btnForm" class="btn btn-success " name="btnForm" />
                                </div> 
                                
                            </div>
                        </form>      
            
            <form action="sql/firmaElectronica.php" method="post" enctype="multipart/form-data">
        	    
        	   <div class="row bg-white rounded p-3 mb-5">
        	        
        	        <div class="col-lg-6">
        	              <label for="txtDireccion"><strong class="leftSpace">Firma Electronica</strong></label>
        	            <input  value="<?php echo $row['firma']; ?>" class="form-control border-none" readonly/>
        	        </div>
        	        <div class="col-lg-5 ">   
        		        <label for="txtClave"><strong class="leftSpace">Clave</strong></label>
        		        <input  type="password" tabindex="1" value="<?php echo $row['clave']; ?>"  id="txtClave" class="form-control" name="txtClave" />
                    </div>
                    <div class="col-lg-1">
                                <input type="checkbox" class="form-check-input" onclick="Toggle()">
                            </div>
        	        
        	        <div class="col-lg-12 mt-2">
        	            <input hidden=""  id="txtIdEmpresa" value="<?php echo $row['empresaId']; ?>" class="form-control" name="txtIdEmpresa" />
        		        <input type="file" name="archivo" class="form-control" value="<?php echo $row['firma']; ?>" >
        		    </div>
        		    
        		    
                    
                    <div class="col-lg-12 mt-2 ">  
                        <div class="switch-field d-block">

                            
                            <input type="radio" id="radio-security" name="switch-proveedor" value="CN=AUTORIDAD DE CERTIFICACION SUBCA-2 SECURITY DATA,OU=ENTIDAD DE CERTIFICACION DE INFORMACION,O=SECURITY DATA S.A. 2,C=EC" 
                            <?php if($row['firmaC']=='CN=AUTORIDAD DE CERTIFICACION SUBCA-2 SECURITY DATA,OU=ENTIDAD DE CERTIFICACION DE INFORMACION,O=SECURITY DATA S.A. 2,C=EC'){?> checked <?php } ?>/>
                            <label for="radio-security">SECURITY DATA</label>
                            
                            <input type="radio" id="radio-uanataca" name="switch-proveedor" value="2.5.4.97=VATES-A66721499,CN=UANATACA CA2 2016,OU=TSP-UANATACA,O = UANATACA S.A.,L=Barcelona (see current address at www.uanataca.com/address),C=ES" 
                            <?php if($row['firmaC']=='2.5.4.97=VATES-A66721499,CN=UANATACA CA2 2016,OU=TSP-UANATACA,O = UANATACA S.A.,L=Barcelona (see current address at www.uanataca.com/address),C=ES'){?> checked <?php } ?>/>
                            <label for="radio-uanataca">UANATACA CA2</label>
                            
                            <input type="radio" id="radio-uanataca2" name="switch-proveedor" value="2.5.4.97= VATES-A66721499,CN= UANATACA CA1 2016,OU = TSP-UANATACA,O = UANATACA S.A.,L = Barcelona (see current address at www.uanataca.com/address),C = ES" 
                            <?php if($row['firmaC']=='2.5.4.97= VATES-A66721499,CN= UANATACA CA1 2016,OU = TSP-UANATACA,O = UANATACA S.A.,L = Barcelona (see current address at www.uanataca.com/address),C = ES'){?> checked <?php } ?>/>
                            <label for="radio-uanataca2">UANATACA CA1</label>
                            
                            <input type="radio" id="radio-anf" name="switch-proveedor" value="CN= ANF High Assurance Ecuador Intermediate CA,OU = ANF Autoridad intermedia  EC,O= ANFAC AUTORIDAD DE CERTIFICACION ECUADOR C.A.,C= EC,SERIALNUMBER = 1792601215001" 
                            <?php if($row['firmaC']=='CN= ANF High Assurance Ecuador Intermediate CA,OU = ANF Autoridad intermedia  EC,O= ANFAC AUTORIDAD DE CERTIFICACION ECUADOR C.A.,C= EC,SERIALNUMBER = 1792601215001'){?> checked <?php } ?>/>
                            <label for="radio-anf">ANF</label>
                            
                            <input type="radio" id="radio-jud" name="switch-proveedor" value="CN= ENTIDAD DE CERTIFICACION ICERT-EC,OU= SUBDIRECCION NACIONAL DE SEGURIDAD DE LA INFORMACION DNTICS,O= CONSEJO DE LA JUDICATURA,L= DM QUITO,C= EC" 
                            <?php if($row['firmaC']=='CN= ENTIDAD DE CERTIFICACION ICERT-EC,OU= SUBDIRECCION NACIONAL DE SEGURIDAD DE LA INFORMACION DNTICS,O= CONSEJO DE LA JUDICATURA,L= DM QUITO,C= EC'){?> checked <?php } ?>/>
                            <label for="radio-jud">JUDICATURA</label>
                            
                            <input type="radio" id="radio-laz" name="switch-proveedor" value="CN=Lazzate Emisor CA,OU=Ente de Certificacion,O=Lazzate Cia. Ltda.,2.5.4.97=59382,E=certificados@enext.ec,L=Quito,S=Quito-Pichincha,C=EC" 
                            <?php if($row['firmaC']=='CN=Lazzate Emisor CA,OU=Ente de Certificacion,O=Lazzate Cia. Ltda.,2.5.4.97=59382,E=certificados@enext.ec,L=Quito,S=Quito-Pichincha,C=EC'){?> checked <?php } ?>/>
                            <label for="radio-laz">LAZZATE</label>
                            
                            <input type="radio" id="radio-arg" name="switch-proveedor" value="CN= ArgosData CA 1-SHA256,OU =ArgosData CA,O= ArgosData,C= EC" 
                            <?php if($row['firmaC']=='CN= ArgosData CA 1-SHA256,OU =ArgosData CA,O= ArgosData,C= EC'){?> checked <?php } ?>/>
                            <label for="radio-arg">ARGOS</label>
                            
                            <input type="radio" id="radio-central" name="switch-proveedor" value="CN=AC BANCO CENTRAL DEL ECUADOR,L=QUITO,OU=ENTIDAD DE CERTIFICACION DE INFORMACION-ECIBCE,O=BANCO CENTRAL DEL ECUADOR,C=EC" 
                            <?php if($row['firmaC']=='CN=AC BANCO CENTRAL DEL ECUADOR,L=QUITO,OU=ENTIDAD DE CERTIFICACION DE INFORMACION-ECIBCE,O=BANCO CENTRAL DEL ECUADOR,C=EC'){?> checked <?php } ?>/>
                            <label for="radio-central">BANCO CENTRAL</label>
                            
                            
                            
                        </div>
                    </div>
                    
                    <div class="col-lg-12 justify-content-center mt-2">
        		        <button class="btn btn-success ">Subir Archivo</button>
        		    </div>
        		    
        		</div>
        		
        
        		
        	</form> 
        	
        	
        	
        	
        	
        	<form id="guardarLogo" method="post" enctype="multipart/form-data">
        	    
        	    <div class="row bg-white rounded p-3 mb-5">
        	        
                	        <div class="col-lg-2">
                	           <!-- <img src="sql/archivos/<?php echo $row['imagen']; ?>" class="img-fluid" > -->
                	        </div>
                	        <div class="col-lg-10">
                    	       <div class="input-group mb-3">

                    	            <input hidden=""  id="txtIdEmpresa" value="<?php echo $row['empresaId']; ?>" class="form-control" name="txtIdEmpresa" />
                    	            <input type="file" name="archivo" class="form-control" id="archivo"   onchange="return validarExt()">
                                    <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Subir Logotipo</button>
                		        </div>
                		    </div>
                		     <div class="col-lg-4">
                                <div id="uploader"></div>
                                <div id="message"></div>
                                <div id="visorArchivo"></div>
                                <p class="statusMsg"></p>
                            </div>    
        		        </div>
        		    
        		
        	</form>   
                        
                
                
            <?php } }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }    ?>
            
                    
                
                
               </div>
                        
            <div class="col-lg-6 p-3 ">
                        <div class="row bg-white p-3">
                            <div class="col-lg-12 ">
                                Impuestos
                            </div>
                            <div class="col-lg-12" id="div_listar_impuestos"></div>
                        </div>
                        
                        <div class="row bg-white p-3 my-3">
                            <h3>Datos para retenciones</h3>
                            <form id="formReten" name="formReten" method="post"  >
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <td>
                                            Establecimiento
                                        </td>
                                        <td>
                                            Punto 
                                        </td>
                                        <td>
                                            Secuencia
                                        </td>
                                        <td>
                                            Autorizaci&oacute;n
                                        </td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                    <?php
        
                  
                    //consulta para sacar los datos de la tabla configuraciones
                    $sql3="SELECT
                          estabRetencion1 as estabRetencion1,
                          ptoEmiRetencion1 as ptoEmiRetencion1,
                          secRetencion1 as secRetencion1,
                          autRetencion1 as autRetencion1
                          
                    FROM
                          `retenciones` retenciones where id_empresa=". $sesion_id_empresa."; ";
        
                        $result=mysql_query($sql3);
        
                       while($row3=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                        {
                            
                            $establecimiento = $row3['estabRetencion1'];
                            $emision = $row3['ptoEmiRetencion1'];
                            $secRetencion1 = $row3['secRetencion1'];
                            $autRetencion1 = $row3['autRetencion1'];
                            
                            }               ?>
                                    
                                    
                                          
                                               <tr>
                                        <td >
                                            <input id="estRetencion" name="estRetencion" class="form-control" value="<?php echo $establecimiento ?>" readonly >
                                        </td>
                                        <td>
                                            <input id="emiRetencion" name="emiRetencion" class="form-control" value="<?php echo $emision ?>" readonly>
                                        </td>
                                        <td>
                                            <input id="secuenciaRetencion" name="secuenciaRetencion" class="form-control" value="<?php echo $secRetencion1 ?>">
                                        </td>
                                        <td>
                                            <input id="autRetencion" name="autRetencion" class="form-control" value="<?php echo $autRetencion1 ?>">
                                        </td>
                                        <td >
                                            <a  id="btnReten" value="Grabar" onClick="javascript: guardar_datos_retencion(3);"><span class="btn fa fa-save"></span></a>
                                        </td>
                                        <td>
                                            <a id="btnReten" value="Grabar" onClick="javascript: guardar_datos_retencion(4);"><span class="btn fa fa-edit"></span></a>
                                        </td>
                                    </tr>
                                    
                                    
                                </tbody>
                            </table>
                             </form>
                        </div> 
                
        
        
                <div class="row bg-white p-3 rounded">
                      <h6>Establecimientos y puntos de emisi&oacute;n</h6>
                      
                <a class="col-lg-5 col-sm-2 text-decoration-none card p-1 m-1"  type="button"  id="btnNuevaFactura" name="btnNuevaFactura" onClick="javascript: nuevo_establecimiento();" />
                        <div class="my-icon3 "><i class="fa fa-file mr-3 fa-2x"></i><span>Nuevo Establecimiento</span>  </div>
                </a> 
                    
                <a class="col-lg-5 col-sm-2 text-decoration-none card p-1 m-1"  type="button"  id="btnNuevaFactura" name="btnNuevaFactura" onClick="javascript: nuevo_emision();"/>
                        <div class="my-icon3 "><i class="fa fa-file mr-3 fa-2x"></i><span>Nuevo Punto de Emisi&oacute;n</span>  </div>
                </a>    
        
                                  <div id="div_oculto" style="display: none;"></div>
        
                                   <div id="listadoEst" ></div>
        
                                   <div id="listadoEmi" ></div>
                            </div>
                            
                            
        <!-- inicio centros de costos -->
        <div class="row bg-white p-3 rounded mt-3">
            <h3>Centros de costos</h3>  
            <a class="col-lg-5 col-sm-2 text-decoration-none card p-1 m-1"  type="button"  id="btnNuevaFactura" name="btnNuevaFactura" onClick="javascript: nuevo_centro_costo();" /><div class="my-icon3 "><i class="fa fa-file mr-3 fa-2x"></i><span>Nuevo Centro de Costos</span>  </div></a> 
            <div id="listadoCentroCostos" ></div>
            
        </div>
         <!-- fin centros de costos -->
          
                            <div class="row bg-white p-3 rounded mt-3">
                                  <h3>Informaci&oacute;n para Proformas</h3>
                                  
                                <?php
                    try {
                    
                    $sqle="SELECT condicionesPago,formaPago,id_empresa as empresaId
                          
                    FROM
                          `empresa` empresa where id_empresa=".$sesion_id_empresa." ; ";
        
                    $result=mysql_query($sqle);
                    while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                        {
                           $id_empresa=$row['empresaId'];     
                           
                    ?>  
                                  
                                 
                                <form name="formProforma" id="formProforma" method="post" action="javascript: modificar_empresa(11);" >
                                    <input hidden=""  id="txtIdEmpresa" value="<?php echo $row['empresaId']; ?>" class="form-control" name="txtIdEmpresa" />
                                  <h5>Condiciones de Pago:</h5>
                                  <textarea class="condicionesPago" name="condicionesPago" class="form-control"> <?php echo $row['condicionesPago']; ?> </textarea>
                                  
                                   <h5>Forma de Pago:</h5>
                                  <textarea class="formaPago" name="formaPago" class="form-control" > <?php echo $row['formaPago']; ?> </textarea>
                                  
                                  <div class="col-lg-12 p-1 m-5  ">
                                    <input type="submit" value="Actualizar Datos" id="btnForm" class="btn btn-success " name="btnForm" />
                                </div> 
                                </form>
                                
                       <?php } }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }    ?>     
                            </div>
                            
                            
                            
                        </div>
                
            
               
                    </div>
                </div>	
                
            </div>	

       
</body>

<script type="text/javascript">

	$(document).ready(function(){
		$('#listadoEst').load('ajax/listadoEst.php');

		$('#listadoEmi').load('ajax/listadoEmision.php');

		$('#listadoCentroCostos').load('ajax/listadoCentroCostos.php');
		
		if(document.getElementById("listadoProyecto")){
		    	$('#listadoProyecto').load('ajax/listadoProyectos.php');
		}
	
	});
	
	
	 function Toggle() {
            var temp = document.getElementById("txtClave");
            if (temp.type === "password") {
                temp.type = "text";
            }
            else {
                temp.type = "password";
            }
        }

  function validarExt()
        {
          var archivoInput = document.getElementById('archivo');
        //   console.log("archivoInput",archivoInput);
        //   console.log("archivoRuta",archivoRuta);
          var archivoRuta = archivoInput.value;
        //   console.log("archivoRuta",archivoRuta);
          var extPermitidas = /(.pdf|.PNG|.png|.JPG|.jpg|.jpeg)$/i;
          if(!extPermitidas.exec(archivoRuta)){
            alertify.error('Tipos de archivos válidos admitidos :  PDF o JPG');
            archivoInput.value = '';
            return false;
          }

          else
          {
        //PRevio del PDF
        if (archivoInput.files && archivoInput.files[0]) 
        {
          var visor = new FileReader();
          visor.onload = function(e) 
          {
            document.getElementById('visorArchivo').innerHTML = 
            '<embed src="'+e.target.result+'" width="500" height="375" />';
          };
          visor.readAsDataURL(archivoInput.files[0]);
        }
      }
    }
    


   
function nuevo_proyecto(id=0){
    //PAGINA: productos.php
    //alert("sss");
       $("#div_oculto").load("ajax/proyectos.php?id="+id, function(){
           $.blockUI({
               message: $('#div_oculto'),
           overlayCSS: {backgroundColor: '#111'},
               css:{
                '-webkit-border-radius': '3px',
                   '-moz-border-radius': '3px',
                   'box-shadow': '6px 6px 20px gray',
                   top: '10%',
                   left: '35%',
                   position: 'absolute'
               
               }
           });
       });
   }
   
   function crud_proyectos(accion,id=0){
       
         var str = '';
         
    if(document.getElementById('formulario_proyectos')){
        str = $("#formulario_proyectos").serialize();
    }
  
    $.ajax(
		{
			url: 'sql/proyectos.php',
            data: str+"&txtAccion="+accion+"&id="+id,
            type: 'post',
            success: function(data)	{
            let response= data.trim();

			if(response==1){
				alertify.success("Proyecto agregado con exito :)");
			}else if (response==2){
                alertify.error("Proyecto no se actualizo.");
            }else if (response==3){
                alertify.success("Proyecto actualizado con exito :)");
            }else if (response==4){
                alertify.error("Proyecto no se elimino.");
            }
            else if (response==5){
                alertify.success("Proyecto eliminado con exito :)");
            }
            else{		
                alertify.error("Proyecto no se guardo"); 	
            }
           $('#listadoProyecto').load('ajax/listadoProyectos.php');
            fn_cerrar()

            }
        });
    }
      function confirmar_eliminar_proyecto(id){
	alertify.confirm('Eliminar Proyecto', 'Esta seguro de eliminar este registro?', 
		function(){ 
			crud_proyectos('3',id)
		}
		, function(){ alertify.error('Se cancelo')});
}
</script>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script> 
 <script>
         // Give $ to prototype.js
         var $jq = jQuery.noConflict();
       </script>
       <script src="https://malsup.github.io/jquery.form.js"></script> 
       <script type="text/javascript">
        
        $jq(document).ready(function() {
          var options = {
            target: '#message', 
            url:'sql/logo.php', 
            beforeSubmit: function() {
                console.log('antes')
            },
            success:  function() {
             console.log('cguardo');
            }
          };

          $jq('#guardarLogo').submit(function() {
            $jq(this).ajaxSubmit(options);
            return false;
          });
        
       
        }); 
        
        
        
      </script>
</html>