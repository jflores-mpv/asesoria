<?php
error_reporting(0);
	require_once('ver_sesion.php');

	//Start session
	session_start();
		
	//Include database connection details
	require_once('conexion.php');
     
    $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_empresa_telefonos = $_SESSION["sesion_empresa_telefonos"];
    $sesion_empresa_direccion = $_SESSION["sesion_empresa_direccion"];
    $sesion_empresa_autorizacion = $_SESSION["sesion_empresa_autorizacion"];
    $sesion_empresa_ruc = $_SESSION["sesion_empresa_ruc"];
    $sesion_empresa_imagen = $_SESSION["sesion_empresa_imagen"];
    $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
?>
<html lang="es-ES">
    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>EGRESOS | INGRESOS</title>
    
    <script src="https://kit.fontawesome.com/7816c50242.js" crossorigin="anonymous"></script>
    <script src="librerias/jquery-3.2.1.min.js"></script>
    <script src="librerias/alertifyjs/alertify.js"></script>
    <script src="librerias/bootstrap/js/bootstrap.js"></script>
    
    <link rel="stylesheet" href="librerias/bootstrap/css/style.css"> 
    <link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/alertify.css">
   	<link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="librerias/dist/css/bootstrap-select.css">
    <link rel="stylesheet" type="text/css" href="librerias/select2/css/select2.css">
    <link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">  

    <!-- START ESTILOS Y CLASES PARA AJAX -->
    <script language="javascript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<!--<script language="javascript" type="text/javascript" src="js/jquery-1.4.1.min.js"></script>   
--> <script language="javascript" type="text/javascript" src="js/jquery.blockUI.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.validate.1.5.2.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js" ></script>
	<script language="javascript" type="text/javascript" src="js/index.js"></script>
	<script language="javascript" type="text/javascript" src="js/listadoFunciones.js"></script>
	<script type="text/javascript" src="js/validaciones.js"></script>
	<script type="text/javascript" src="js/validacion202006.js"></script>

	<script type="text/javascript" src="js/condominios.js"></script>
	<script type="text/javascript" src="js/vendedores.js"></script>
	<script type="text/javascript" src="js/reportes.js"></script>
	<script type="text/javascript" src="js/ventas_educativo.js"></script>
	<script type="text/javascript" src="js/centroCosto.js"></script>
	<script type="text/javascript" src="js/egresos.js"></script>
	<script language="javascript" type="text/javascript" src="js/productos.js"></script>
	
	
    <!-- END ESTILOS Y CLASES PARA AJAX -->

	

    <!-- estilos para tablas -->
    <link href="css/listadoTablas.css" rel="stylesheet" type="text/css" />

    <!-- estilos para el calendario -->
    <link type="text/css" rel="stylesheet" href="css/calendario.css" media="screen"/>
    <script language="javascript" type="text/javascript" src="js/calendario.js"></script>

    <!--validaciones de los campos -->
    
  
    <!-- condominios -->
    
<!--	<script type="text/javascript" src="js/Facturas_Nuevo.js"></script>
-->
<!--	<script type="text/javascript" src="js/compras.js"></script>
    -->
  <!--END estilo para  el menu -->
	<link rel="shortcut icon" href="images/logo.png">
</head>

<script type="text/javascript">
    $(document).ready(function(){
		//contador=0;
		//alert("va a llenar");
		//i=1;
        for(i=1; i<= 5; i++){
            AgregarFilasEgresos();
        }
      //  muestra_iva_actual(4);
        
    });

    $(document).keyup(function(event)
	{
		if(event.which==27) //27 escape
        {
            //$('.suggestionsBox').hide();
            setTimeout("$('.suggestionsBox').hide();", 50); /* */
        }
    });

	$(document).ready(function(){
          //      listar_facturas();
        });
    </script>

<script type="text/javascript">
    function validaDatosFacVenCondominios(accion, dml)
    {
        var textIdCliente = document.getElementById("textIdClienteFVC").value;
        var textCedula = document.getElementById("txtCedulaFVC").value;
        var txtTotal=document.getElementById("txtTotalFVC").value;
        var txtContadorAsientosAgregados=document.getElementById("txtContadorAsientosAgregadosFVC").value;
        var txtFecha = document.getElementById("txtFechaFVC").value;
        var txtNumeroEgreso = document.getElementById("txtNumeroEgreso").value;
        $('#txtAccionFVC').val(accion);
       
         if (txtContadorAsientosAgregados <= 0){
            alert ('No hay suficientes servicios para guardar.');
            dml.elements['txtCodigoServicio1'].focus();
            return false;
        }
       
                
        else if (txtFecha == ""){
            alert ('La fecha no puede estar vacia.');
            dml.elements['txtFechaFVC'].focus();
            return false;
        }
        else if (txtNumeroEgreso == ""){
            alert ('El numero de Registro no puede estar vacio.');
            dml.elements['txtNumeroEgreso'].focus();
            return false;
        }
        return true;
    }

	
	
</script>

<body onLoad = "buscar_secuencial('Ingreso',5,1);">
    
<div class="wrapper d-flex align-items-stretch celeste">
        <nav id="sidebar" ><?php  {include("menusNiveles.php");      }   ?></nav>
    <div id="content"  >
        <header ><?php  {include("header.php");      }   ?>  </header>

        <div class="row p-4 pt-1  mt-5 ml-5  ">
                <a class="col-lg-2 col-sm-2 text-decoration-none card p-1 m-1"  type="button"  id="btnNuevaFactura" 
                name="btnNuevaFactura" onclick = "location='egresos.php'" />
                <div class="my-icon3 "><i class="fa fa-file mr-3 fa-2x"></i><span>Nuevo</span>  </div></a> 
                
                <a class="col-lg-2 col-sm-2 text-decoration-none card p-1 m-1"  type="button"  id="btnNuevaFactura" 
                name="btnNuevaFactura" onClick="javascript: nuevo_centrocosto()" />
                <div class="my-icon3 "><i class="fa fa-file mr-3 fa-2x"></i><span>Nuevo Centro de costo</span>  </div></a> 
                
                <a class="col-lg-2 col-sm-2 text-decoration-none card p-1 m-1"  type="button"  id="btnNuevaFactura" 
                name="btnNuevaFactura" onClick="javascript: nuevo_producto();" />
                <div class="my-icon3 "><i class="fa fa-file mr-3 fa-2x"></i><span>Nuevo Producto</span>  </div></a> 
                
                 <a class="col-lg-2 col-sm-2 text-decoration-none card p-1 m-1"  type="button"  id="btnNuevaFactura" 
                name="btnNuevaFactura" onclick = "location='reportesTransferencias.php'" />
                <div class="my-icon3 "><i class="fa fa-file mr-3 fa-2x"></i><span>Reportes</span>  </div></a> 
                
                
                
        </div>
  
 <form id="frmEgresos" name="frmEgresos" method="post" action="">
    <div class="row bg-white r-10 mx-5 mt-1 p-3">       
		<select style="width: 50%; display: none"  name="cmbCuentaContable" id="cmbCuentaContable"  class="text_input1"></select>
        <input type="hidden" name="txtAccionFVC"  id="txtAccionFVC" value="0" />
            
		<div class="col-lg-3"> 
			<div class="row">
				<div class="switch-field ">
					<input type="radio" id="radio-one" name="ingreso" value="Ingreso" checked 
					onChange="javascript: buscar_secuencial(ingreso,5,2)" />
					<label for="radio-one">Ingreso</label>
					<input type="radio" id="radio-two" name="ingreso" value="Egreso" 
					onChange="javascript: buscar_secuencial(ingreso,5,2)"  />
					<label for="radio-two">Egreso</label>
		            <input type="radio" id="radio-three" name="ingreso" value="trans" 
					onChange="javascript: buscar_secuencial(ingreso,5,2)"  />
					<label for="radio-three">Transferencia</label>
				</div>
			</div>	 
		</div>
		<div id="mensaje1" class="m-2"></div>
        
        <div class="col-lg-1">
            	 <input style="width: 100%" name="txtNumeroEgreso" id="txtNumeroEgreso" type="text" class="form-control" required  
					onclick="lookup_egreso(this.value,'', 8);" onKeyUp="lookup_egreso(this.value,'', 8);" />
			<input name="textIdClienteFVC" id="textIdClienteFVC" type="hidden" value="" size="5" readonly="readonly" />
		</div>
		<div class="col-lg-2">
		    <label>Fecha</label>
            <input name="txtFechaFVC" style="width: 100%" id="txtFechaFVC" class="form-control" required="required" type="text" value="<?php echo date("Y-m-d H:i:s")?>"onClick="displayCalendar(txtFechaFVC,'yyyy-mm-dd hh:ii:00',this,this)"  />
        </div>
         <div class="col-lg-3">
            <label>Observacion</label>
             <input type="text" style="width: 100%" name="txtObservacion" id="txtObservacion" value=""  title="Nombre " class="form-control"  required="required" autocomplete="off" />
        </div>
         <div class="col-lg-2">
            <label>Centro de costo</label>
            
             	<select  tabindex="7" id="cmbCosto" class="form-control" name="cmbCosto" required="required">
						<?php
						 $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
							$sqlp="Select id_cuenta,codigo,descripcion From centro_costo where empresa='".$sesion_id_empresa."';";
						//	echo $sqlp;
							$resultp=mysql_query($sqlp);
							while($rowp=mysql_fetch_array($resultp))//permite ir de fila en fila de la tabla
							{ ?> <option value="<?=$rowp['id_cuenta']; ?>"><?=$rowp['codigo'].' '.$rowp['descripcion']; ?></option> <?php   } ?>
						</select>
						
        </div>
        <div class="col-lg-2">
        <label>Bodega</label>
     	        <select  tabindex="7" id="cmbBodegas" class="form-control" name="cmbBodegas" required="required" >
				    <?php
				     $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
					$sqlp="Select * From bodegas where id_empresa='".$sesion_id_empresa."';";
					echo $sqlp;
					$resultp=mysql_query($sqlp);
					while($rowp=mysql_fetch_array($resultp))//permite ir de fila en fila de la tabla
					{ ?> <option value="<?=$rowp['id']; ?>"><?=$rowp['detalle']?></option> <?php   } ?>
				</select>
        </div> 
        <div class="col-lg-2">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="checkSaldoInicial" id="checkSaldoInicial"  value="saldo" >
              <label class="form-check-label" for="checkSaldoInicial">
               Saldo Inicial
              </label>
            </div>
        </div>
          
        <div class="col-lg-2 " style="display:none;" >
            <td >
                <span class="Estilo7"><strong>Cedula/Ruc:</strong></span>
            </td>
            <td style="display:none;"  width="" >
                        <input name="txtCedulaFVC" style="width: 100%" id="txtCedulaFVC" type="search" 
						autofocus class="form-control" placeholder="Buscar.."onClick="lookup9(this.value, 6);" autocomplete="off" required="required" onKeyUp="lookup9(this.value, 6);" onBlur="fill9();" onChange="limpiar_id('textIdClienteFVC')"/>
                        <div class="suggestionsBox2" id="suggestions9" style="display: none">
                            <img src="images/upArrow.png" style="position: relative; top: -10px; left: 60px;" alt="upArrow" />
                            <div class="suggestionList2" id="autoSuggestionsList9" >
                                    &nbsp;
                            </div>
                        </div>
            </td>
        </div>
    </div>
    
    <div class="row border mx-5 p-3 r-10 bg-white mt-2 " >
        <div class='mx-3 ' style='width:60px'><a href="javascript: AgregarFilasEgresos();" title="Agregar nueva fila"><i class='fa fa-plus' aria-hidden='true'></i></a></div>
                    <div class='col-lg-2'><strong>C&oacute;digo</strong></div>
                    <div class='col-lg-3'><strong>Descripci&oacute;n</strong></div>
			        
                    <div class='col-lg-1'><strong>Cant.</strong></div>
                    <div class='col-lg-1'><strong>Costo.</strong></div>
                    <div class='col-lg-1'><strong>Prom.</strong></div>
                    <div class='col-lg-2'><strong>V.Total.</strong></div>
                    <div class='col-lg-1'><strong>Enviar a:</strong></div>
    </div>
        
    <div  id="tblBodyEgreso"></div>
       
    <div class="row border mx-5 p-3 rounded mb-4 bg-white mt-2 ">
                                       
		<input type="hidden" id="txtContadorAsientosAgregadosFVC" value="0" readonly="readonly" class="" name="txtContadorAsientosAgregadosFVC" />
            <input type="hidden" id="txtContadorFilas" value="0" readonly="readonly" class="" name="txtContadorFilas" />
            <div class='col-lg-10  text-right mt-2'><strong>TOTAL</strong></div>
            <div class='col-lg-2'><input  style="width: 100%; text-align: right;" type="text" name="txtSubtotal_EI" id="txtSubtotal_EI" class='form-control' readonly="readonly"  value="0.00" /></div>
    </div>
      
    <div id="mensajeEgreso"></div>

    <div class="row bg-white r-10 mx-5 mt-1  p-3">
        <input type="hidden" style="width: 100%; text-align: right; font-size: 18px; font-weight: bold; color: #900; "  name="txtTotalFVC" id="txtTotalFVC" readonly="readonly" class='form-control'  value="0.00" />
        <td width="" >  <input type="hidden" id="txtContadorAsientosAgregadosFVC" value="0" readonly="readonly" class="" name="txtContadorAsientosAgregadosFVC" /></td>
        <td width="" align="center"  > 
        <td width="" align="right" ><strong></strong></td>
        <td width="14%" >
	<!--	<input style="width: 150px" type="button" tabindex="5" name="submit" value="GRABAR" id="" class="btn btn-primary" align="right" onclick="guardarEgreso();"  />
		<input style="width: 150px" type="button" tabindex="5" name="submit" value="MODIFICAR" id="" class="btn btn-primary" align="right" onclick="modificarEgreso();"  />
		<input style="width: 150px" type="button" tabindex="5" name="submit" value="ELIMINAR" id="" class="btn btn-primary" align="right" onclick="eliminarEgreso();"  />
	-->
	    <div class="row bg-white mt-1 p-4 rounded">
			<div class="col-lg-12">
                  <a style="visibility: hidden; width: 20%;font-size:15px" value="Eliminar" VALUE="ELIMINAR"   type="button"  id="btnEliminar"   class="btn btn-outline-danger  "    onClick="eliminar();" />Eliminar</a>
                  <a style="visibility: hidden; width: 20%;font-size:15px" value="Editar"   VALUE ="EDITAR"    type="button"  id="btnEditar"     class="btn btn-outline-warning  "   onClick="editar();" />Editar</a>
                  
                  <a style="width: 20%;font-size:15px"   type="button"   class="btn btn-outline-success  "   id="btnGuardar"  value="Guardar" name="btnGuardar"     onClick="guardarEgreso();">Grabar</a>
                  <a style="width  20%;font-size:15px"   type="button"   class="btn btn-outline-info  "    onclick = "location='egresos.php'" >Nuevo</a>
               <!--   <a style="width: 20%;font-size:15px"   type="button"   class="btn btn-outline-warning  "  name="btnGuardar"      onClick="javascript: fn_agregar();" />A���adir Fila</a>
			-->
			</div>
		</div> 
	</div>
		
	<div id="listar_facturas"  ></div>
             
    <div id="div_oculto"  style="display: none;"></div>
</form>
                
    </div>	                      
    </div>	<!-- end #contentLeft .grid_10 -->
            <!-- END LEFT CONTENT -->            
<!--</div>-->
</div>
</body>    
<script type="">  

function guardarEgreso(){
    valor = validaDatosFacVenCondominios('1',frmEgresos);// retorna true o false    
    ingreso=   document.getElementById("radio-one").checked;
    egreso=   document.getElementById("radio-two").checked;
    trans=   document.getElementById("radio-three").checked;
//	alert(valor);
    if(valor == true){
		if (ingreso)   {	guardarIngresos(1);	}
		if (egreso)	    {	guardarEgresos(1);	}
		if (trans)	    {	guardarTransferencia(1);	}
    }
}

function editar(){
    let tipo = '';
    let radios = document.querySelectorAll('input[name="ingreso"]');
  for (let radio of radios) {
    if (radio.checked) {
        tipo = radio.value;
      break;
    }
  }
    valor=true
    if(valor == true){
		if (tipo == 'Ingreso')   {	modificar_ingreso(2);	}
		if (tipo == 'Egreso')	    {	modificar_egreso(2);	}
		  if (tipo == 'trans')	    {	modificar_transferencia(2);	}
    }
}

function eliminar(){
    let id = txtNumeroEgreso.value;
    let tipo = '';
    let radios = document.querySelectorAll('input[name="ingreso"]');
  for (let radio of radios) {
    if (radio.checked) {
        tipo = radio.value;
      break;
    }
  }

		if (tipo == 'Ingreso')   {	preguntarEliminarIngreso(id);	}
		if (tipo == 'Egreso')	    {	preguntarEliminarEgreso(id);	}
		  if (tipo == 'trans')	    {	preguntarEliminarTransferencia(id);	}
    
}

function preguntarEliminarIngreso(id){
	alertify.confirm('Eliminar Datos', 'Esta seguro de eliminar este ingreso?', 
		function(){ 
			eliminarIngreso(id)
		}
		, function(){ alertify.error('Se cancelo')});
}
function eliminarIngreso(id){
	//console.log(id+name);
	$.ajax({
		url: 'sql/ingresos.php',
		type: 'post',
		data: "txtAccion=3&id="+id,
		success: function(data){
			let response = data.trim();
			$('#mensaje1').show();
						document.getElementById('mensaje1').innerHTML=""+data;
						document.getElementById('mensaje1').style.opacity = "1";

						clearTimeout(); //detiene el tiempo
						setTimeout(function(){
							for (i = 10; i >= 0; i--){
								setTimeout("document.getElementById('mensaje1').style.opacity = '" + (i / 10) + "'", (10 - i) * 100);
							}
							setTimeout("$('#mensaje1').hide();", 1000);
                        }, 5000);
            // if(response == 1){
            //     alertify.success('Registro eliminado correctamente');
            // }else{
            //     alertify.error('Registro no se  elimino correctamente');
            // }
		}
	});
}
function preguntarEliminarEgreso(id){
	alertify.confirm('Eliminar Datos', 'Esta seguro de eliminar este ingreso?', 
		function(){ 
			eliminarEgreso(id)
		}
		, function(){ alertify.error('Se cancelo')});
}
function eliminarEgreso(id){
	//console.log(id+name);
	$.ajax({
		url: 'sql/egresos.php',
		type: 'post',
		data: "txtAccion=3&id="+id,
		success: function(data){
            let response = data.trim();
			$('#mensaje1').show();
						document.getElementById('mensaje1').innerHTML=""+data;
						document.getElementById('mensaje1').style.opacity = "1";

						clearTimeout(); //detiene el tiempo
						setTimeout(function(){
							for (i = 10; i >= 0; i--){
								setTimeout("document.getElementById('mensaje1').style.opacity = '" + (i / 10) + "'", (10 - i) * 100);
							}
							setTimeout("$('#mensaje1').hide();", 1000);
                        }, 5000);
		}
	});
}
function preguntarEliminarTransferencia(id){
	alertify.confirm('Eliminar Datos', 'Esta seguro de eliminar esta transferencia?', 
		function(){ 
			eliminarTransferencia(id)
		}
		, function(){ alertify.error('Se cancelo')});
}
function eliminarTransferencia(id){
	//console.log(id+name);
	$.ajax({
		url: 'sql/transferencias.php',
		type: 'post',
		data: "txtAccion=3&id="+id,
		success: function(data){
            let response = data.trim();
			$('#mensaje1').show();
						document.getElementById('mensaje1').innerHTML=""+data;
						document.getElementById('mensaje1').style.opacity = "1";

						clearTimeout(); //detiene el tiempo
						setTimeout(function(){
							for (i = 10; i >= 0; i--){
								setTimeout("document.getElementById('mensaje1').style.opacity = '" + (i / 10) + "'", (10 - i) * 100);
							}
							setTimeout("$('#mensaje1').hide();", 1000);
                        }, 5000);
		}
	});
}
function modificar_transferencia(form)
{
	var txtPermisosAsientosContablesModificar="Si";
    if(txtPermisosAsientosContablesModificar == "No"){
         alert ("Usted No tiene permisos. \nConsulte con el Administrador.");
    }
	else
	{
        contadorFilas = $('#txtContadorFilas').val();
        if(contadorFilas >=1)
		{
            var respuesta32 = confirm("Seguro desea editar esta Transferencia?");
            if (respuesta32)
			{
                var accionEAS = 2;
                document.getElementById("btnEditar").style.visibility="hidden";
				document.getElementById("btnEliminar").style.visibility="hidden";
                var strEAC = $("#frmEgresos").serialize();

				$.ajax(
				 {
				   url: 'sql/transferencias.php',
                    data: strEAC+'&txtAccion='+accionEAS,
                    type: 'post',
                    success: function(data)
					  {
                      //          alert(data);
						$('#mensaje1').show();
						document.getElementById('mensaje1').innerHTML=""+data;
						document.getElementById('mensaje1').style.opacity = "1";

						clearTimeout(); //detiene el tiempo
						setTimeout(function(){
							for (i = 10; i >= 0; i--){
								setTimeout("document.getElementById('mensaje1').style.opacity = '" + (i / 10) + "'", (10 - i) * 100);
							}
							setTimeout("$('#mensaje1').hide();", 1000);
                        }, 5000);
                      }
                  });
            }
        }
		else
		{
                alert ('No hay suficientes cuentas para editar.');
        }
    }
}
</script>

</html>
