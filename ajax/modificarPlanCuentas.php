<?php
 error_reporting(0);
	require_once('../ver_sesion.php');

	//Start session
	session_start();

	//Include database connection details
	require_once('../conexion.php');
	
?>

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Modificar Cuenta Contable</title>



<script type="text/javascript">
    $(document).ready(function(){
      
            $("#ajax_contactform").validate();
    });
</script>

</head>

<body>
    
        <?php

        try {
            $id_plan_cuenta = $_POST['id_plan_cuenta'];
           //permite sacar el id maximo de categorias

                $sql="Select * From plan_cuentas where id_plan_cuenta = '".$id_plan_cuenta."';";
                $result=mysql_query($sql);

               while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                {
                   $id_plan_cuenta=$row['id_plan_cuenta'];
                   //$codigo=$row['codigo'];
                   //$cadenaCodigo = split("\.",$codigo);
//                   $cadena9=filter_var($codigo, FILTER_SANITIZE_NUMBER_INT);// elimina el . y solo coje numeros  //echo preg_replace('/[^0-9]/','',$cadena);
//                   $longitud = strlen($cadena9); // longitud de l acadena

                   ?>

  <div class="row" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <a href="javascript: fn_cerrar();"><button type="button" class="close" ><span aria-hidden="true">&times;</span></button></a>
        <h4 class="modal-title" id="myModalLabel">Modificar Cuenta Contable</h4>
      </div>
      <div class="modal-body">
      	<div id="mensaje" ></div>
        <form name="frmModificarPC" id="frmModificarPC" method="post" action="javascript: guardar_modificacion_plan_cuentas(0,0);" class="pure-form" style="padding:5%">
        

            <div class="mb-3">
                <label for="cmbTipo" class="form-label">Esta cuenta es:</label>
                <select tabindex="1" name="cmbTipo" id="cmbTipo" class="form-control required" required="required">
               
                    <option value="<?php echo "".$tipo=$row['tipo']; ?>" ><?php echo "".$tipo=$row['tipo']; ?></option>
                    <option>----------------</option>
                    <option value="Agrupaci&oacute;n" >Agrupaci&oacute;n</option> 
                    <option value="Movimiento" >Movimiento</option>
               
            </div>
            
            <div class="mb-3">
                <label for="txtCodigo" class="form-label">C&oacute;digo</label>
                <input type="hidden" size="22" id="txtIdPlanCuenta" readonly="readonly" class="form-control" value="<?php echo "".$id_plan_cuenta=$row['id_plan_cuenta']; ?>" name="txtIdPlanCuenta" />
                <input type="text" tabindex="2" maxlength="50" id="txtCodigo" required="required" class="form-control " value="<?php echo "".$codigo=$row['codigo']; ?>" name="txtCodigo" placeholder="Ingrese el codigo para la cuenta" onKeyPress="return precio(event)" onKeyup="no_repetir_codigo2(txtCodigo)" onchange="no_repetir_codigo2(txtCodigo)" onclick="no_repetir_codigo2(txtCodigo)" readonly="readonly" autocomplete="off"/></p>
                    <div id="noRepetirCodigoMPC" ></div>
                    <div id="codigoVacioMPC"></div>
            </div>
            
            <div class="mb-3">
                <label for="txtNombre" class="form-label">Nombre</label>
                <input style="text-transform: capitalize;" type="search" tabindex="3"  id="txtNombre" required="required" class="form-control " value="<?php echo "".$nombre=$row['nombre']; ?>" name="txtNombre" placeholder="Ingrese el nombre de la cuenta" onKeyUp="no_repetir_nombre2(txtNombre)" onclick="no_repetir_nombre2(txtNombre)" autocomplete="off"/></p>
                    <div id="noRepetirNombreMPC"></div>
                    <div id="nombreVacioMPC"></div>
            </div>
            
            <div class="mb-3">
                <label for="txtCuentaBanco" class="form-label">Cuenta de banco</label>
                <input style="" tabindex="1" type="text" maxlength="50" id="txtCuentaBanco" value="<?php echo $row['cuenta_banco']; ?>" class="form-control " name="txtCuentaBanco" placeholder="Ingrese el numero de cuenta del banco" onblur="" onKeyPress="return precio(event)" onKeyup="" onchange="" onclick=""  autocomplete="off"/></p>
            </div>
            
     
            <div class="mb-3">
            <label style="color: black" for="name"><strong>Formato Cheque</strong></label>
            <select name="cmbFormatoCheque" id="cmbFormatoCheque" class="form-control required" required="required">
                <option value="0" <?php if ($row['tipo_cheque'] == "" || $row['tipo_cheque'] == "0"){ ?> selected<?php  } ?> >Ninguno</option>
              <option value="pichincha" <?php if ($row['tipo_cheque'] == "pichincha"){ ?> selected<?php  } ?> >Banco Pichincha</option>
                <option value="produbanco"  <?php if ($row['tipo_cheque'] == "produbanco"){ ?> selected<?php  } ?> >Banco Produbanco</option>
               
            </select>
          </div>
  
    
    <?php }
    
    }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }  ?>

      <div class="modal-footer">
        <input type="submit" value="Guardar Cambios" tabindex="4" id="submit" class="btn btn-warning" name="submit"/>
      </div>
      	</form>
    </div>
  </div>



    
    

    

        <div class="path2"></div>

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

function guardar_modificacion_plan_cuentas(codigo, nombre){    

//    document.getElementById('mensaje').innerHTML=""+codigo+" , "+nombre;
    if(codigo == 0 && nombre == 0){        

		var str = $("#frmModificarPC").serialize();
		$.ajax({

			url: 'sql/plan_cuentas.php',
			data: str+"&accion="+4,
			type: 'post',
			success: function(data){
                           //document.getElementById('mensaje').innerHTML=""+data;

                        if (data == 1){
                            document.getElementById('codigoVacioMPC').innerHTML="<label style='color: #FF0000'>Este campo no puede estar vacio</label>";
                           document.getElementById("nombreVacioMPC").innerHTML="";
                           document.getElementById("noRepetirNombreMPC").innerHTML="";
                           document.getElementById("noRepetirCodigoMPC").innerHTML="";
//                           document.getElementById('mensaje').innerHTML="<div class='transparent_ajax_error'><p>Fallo en el envio del Formulario: Faltan datos. </p></div>";
                        }
                        if (data == 2){
                           document.getElementById('nombreVacioMPC').innerHTML="<label style='color: #FF0000'>Este campo no puede estar vacio</label>";
                           document.getElementById("codigoVacioMPC").innerHTML="";
                           document.getElementById("noRepetirNombreMPC").innerHTML="";
                           document.getElementById("noRepetirCodigoMPC").innerHTML="";
//                           document.getElementById('mensaje').innerHTML="<div class='transparent_ajax_error'><p>Fallo en el envio del Formulario: Faltan datos. </p></div>";
                        }
                        if (data == 3){
                           document.getElementById('mensaje').innerHTML="<div class='alert alert-danger'><p>Error al guarda los datos: </p></div>";
                        }
                        if (data == 4){
                           document.getElementById('mensaje').innerHTML="<div class='alert alert-success'><p>Registro insertado correctamente.</p></div>";
                           document.getElementById('txtCodigo').value="";
                           document.getElementById('txtNombre').value="";
                           document.getElementById("codigoVacioMPC").innerHTML="";
                           document.getElementById("nombreVacioMPC").innerHTML="";
                           document.getElementById("noRepetirNombreMPC").innerHTML="";
                           document.getElementById("noRepetirCodigoMPC").innerHTML="";
                           fn_cerrar();
                        }

                         fn_buscar();
			}
		});
             
             
    }
}
</script>

</html>