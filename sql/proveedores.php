<?php
    error_reporting(0);
    //require_once('../ver_sesion.php');

    //Start session
    session_start();

    //Include database connection details
    require_once('../conexion.php');

    $accion=$_POST['txtAccion'];

    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
date_default_timezone_set("America/Guayaquil");


if($accion == "1"){
 // GUARDAR PROVEEDORES Y plan cuenta PAGINA: proveedores.php
     try
     {
        $txtNombreComercial=ucwords($_POST['txtNombreComercial']);
        $txtNombreRepresentante=$_POST['txtRazonSocial'];
        $txtDireccion=ucwords($_POST['txtDireccion']);
        $txtRuc=$_POST['txtRuc'];
        $txtTelefono=$_POST['txtTelefono'];
        $txtMovil="";
        $txtFax="";
        $txtEmail=$_POST['txtEmail'];
        $txtWeb="";
        $cbciudad=$_POST['cbciudad'];
        $txtObservaciones=ucwords($_POST['txtObservaciones']);
        $id_plan_cuenta = 0;
        $txtAutorizacionSRI=$_POST['txtAutorizacionSRI'];
        $txtFechaVencimiento=$_POST['txtFechaVencimiento'];
        $cmbTipoProveedor=$_POST['cmbTipoProveedor'];
        $rbCaracterIdentificacion=$_POST['rbCaracterIdentificacion'];
        

        if($txtNombre != "" & $txtRuc != "" & $cbciudad != ""){
        /*
        // GUARDA EN EL PLAN DE CUENTAS
        $txtCodigo = $_POST['txtCodigo'];
        $clasificacion = $_POST['cmbClase'];
        $tipo = $_POST['cmbTipo'];
        $categoria = "CreditoDebito";

        $clase = filter_var($clasificacion, FILTER_SANITIZE_NUMBER_INT);
        switch ($clase) {
            case "1":
                $clasifica = "Activo";
                break;
            case "2":
                $clasifica = "Pasivo";
                break;
            case "3":
                $clasifica = "Patrimonio";
                break;
            case "4":
                $clasifica = "Gasto";
                break;
            case "5":
                $clasifica = "Ingresos";
                break;
            case "6":
                $clasifica = "Cuentas Contingentes";
                break;
            case "7":
                $clasifica = "Cuentas de Orden";
                break;
        }

        // FUNCION PARA AUMENTAR EL . ASI EL EL USUARIO HAYA PUESTO EL PUNTO O NO
        $cadena9=filter_var($txtCodigo, FILTER_SANITIZE_NUMBER_INT);// elimina el . y solo coje numeros  //echo preg_replace('/[^0-9]/','',$cadena);
        $longitud = strlen($cadena9); // longitud de l acadena
        $caracter = "";
        for($i=0; $i<$longitud; $i++){
            $caracter = $caracter.$cadena9[$i].".";
        }
        $nivel = $longitud;
        //permite sacar el id maximo de plan_cuentas
        try {
                $sqli="Select max(id_plan_cuenta) From plan_cuentas";
                $result=mysql_query($sqli);
                $id_plan_cuenta=0;
                while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                {
                    $id_plan_cuenta=$row['max(id_plan_cuenta)'];
                }
                $id_plan_cuenta++;

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }

          $sql = "insert into plan_cuentas (id_plan_cuenta, codigo, nombre, clasificacion, tipo, categoria, nivel) values ('".$id_plan_cuenta."','".$caracter."','".$txtNombre."','".$clasifica."','".$tipo."','".$categoria."','".$nivel."')";
          $resp = mysql_query($sql);
          if($resp){
              }
              */

            $sqlp="insert into proveedores( rbCaracterIdentificacion,nombre_comercial, nombre, direccion, ruc, telefono, movil, fax, email, web, observaciones, id_ciudad, id_plan_cuenta, autorizacion_sri, fecha_vencimiento, id_empresa, tipo_proveedor)
                    values ('".$rbCaracterIdentificacion."','".$txtNombreComercial."','".$txtNombreRepresentante."','".$txtDireccion."','".$txtRuc."','".$txtTelefono."', '".$txtMovil."','".$txtFax."', '".$txtEmail."', '".$txtWeb."', '".$txtObservaciones."','".$cbciudad."','".$id_plan_cuenta."', '".$txtAutorizacionSRI."', '".$txtFechaVencimiento."', '".$sesion_id_empresa."', '".$cmbTipoProveedor."'); ";
                    $result1=mysql_query($sqlp);
			        $id_proveedor=mysql_insert_id();
			
            if($result1){
              echo '1';
            }else{
                echo '2';
            }            
          
      }else{
          ?>   <div class='transparent_ajax_error'><p>Error al guarda en la tabla Productos: <?php echo mysql_error();?> </p></div>  <?php
        
      }

    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
    }
}


if($accion == "2"){
    // GUARDAR MODIFICACION PROVEEDORES PAGINA: proveedores.php
        try
         {
            $txtIdProveedor=$_POST['txtIdProveedor'];
            $txtNombre = strtoupper($_POST['txtNombre']);
            $txtNombreRepresentante=strtoupper($_POST['txtRazonSocial']);
            $txtDireccion=ucwords($_POST['txtDireccion']);
            $txtRuc=$_POST['txtRuc'];
            $txtTelefono=$_POST['txtTelefono'];
            // $txtMovil=$_POST['txtMovil'];
            // $txtFax=$_POST['txtFax'];
            $txtEmail=$_POST['txtEmail'];
            // $txtWeb=$_POST['txtWeb'];
            $cbciudad=$_POST['cbciudad'];
            // $txtObservaciones=ucwords($_POST['txtObservaciones']);
    
            // $txtEstado=$_POST['switch-estado'];
            $parteRel=$_POST['switch-parteRel'];
            // $diasCredito=$_POST['diasCredito'];
            
            if($txtNombre != "" & $txtRuc != "" & $cbciudad != ""){
    
                //GUARDA MODIFICACION PLAN CUENTAS
                $id_plan_cuenta = $_POST['txtIdPlanCuenta'];
                $txtCodigo = $_POST['txtCodigo'];
                $clasificacion = $_POST['cmbClase'];
                $tipo = $_POST['cmbTipo'];
                $categoria = $_POST['cmbCategoria'];
    
                // $clase = filter_var($clasificacion, FILTER_SANITIZE_NUMBER_INT);
                //     switch ($clase) {
                //         case "1":
                //             $clasifica = "Activo";
                //             break;
                //         case "2":
                //             $clasifica = "Pasivo";
                //             break;
                //         case "3":
                //             $clasifica = "Patrimonio";
                //             break;
                //         case "4":
                //             $clasifica = "Gasto";
                //             break;
                //         case "5":
                //             $clasifica = "Ingresos";
                //             break;
                //         case "6":
                //             $clasifica = "Cuentas Contingentes";
                //             break;
                //         case "7":
                //             $clasifica = "Cuentas de Orden";
                //             break;
                //     }
    
                // FUNCION PARA AUMENTAR EL . ASI EL EL USUARIO HAYA PUESTO EL PUNTO O NO
                // $cadena9=filter_var($txtCodigo, FILTER_SANITIZE_NUMBER_INT);// elimina el . y solo coje numeros  //echo preg_replace('/[^0-9]/','',$cadena);
                // $longitud = strlen($cadena9); // longitud de l acadena
                // $caracter = "";
                // for($i=0; $i<$longitud; $i++){
                //     $caracter = $caracter.$cadena9[$i].".";
                // }
                // $nivel = $longitud;
                // $sql = "update plan_cuentas set  codigo='".$caracter."', nombre='".$txtNombre."', clasificacion='".$clasifica."', tipo='".$tipo."', categoria='".$categoria."', nivel='".$nivel."' where id_plan_cuenta='".$id_plan_cuenta."';";
    
                // $resp = mysql_query($sql);
    
                // if($resp){
                    //GUARDA MODIFICACION PROVEEDORES
    
                  $tipoProveedor= $_POST['rbCaracterIdentificacion'];
    
                   $sqlp2="update proveedores set nombre_comercial='".$txtNombre."', nombre='".$txtNombreRepresentante."', direccion='".$txtDireccion."', ruc='".$txtRuc."', telefono='".$txtTelefono."', email='".$txtEmail."', id_ciudad='".$cbciudad."',  parteRel='".$parteRel."',   id_plan_cuenta='0', rbCaracterIdentificacion='$tipoProveedor'  where id_proveedor='".$txtIdProveedor."'; ";
                    $result2=mysql_query($sqlp2);
    
                    if($result2){
                     echo '1';
                    }else{
                       echo '2';
                    }
                // }
    
          }else{
              ?> <div class="transparent_ajax_error"><p>Fallo en el envio del Formulario: No hay datos, <?php echo "\n".mysql_error(); ?></p></div> <?php
          }
    
        }catch (Exception $e) {
        // Error en algun momento.
           ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
        }
    
    }
    

	if($accion == "7"){
 // GUARDAR PROVEEDORES Y plan cuenta PAGINA: proveedores.php
     try
     {
          $txtRucProveedor=$_POST['txtRucProveedor'];
    
            $sqlValidacion = "SELECT `id_proveedor`, `ruc`, id_empresa  FROM `proveedores` WHERE ruc='".$txtRucProveedor."' AND id_empresa=$sesion_id_empresa ";
            $resultValidacion = mysql_query($sqlValidacion);
            $numFilasValidacion = mysql_num_rows($resultValidacion); 
            if($numFilasValidacion>0){
                echo '3';
                exit;
            }
        
         
         
        $txtNombreComercial=strtoupper($_POST['txtNombreComercial']);
        $txtNombreRepresentante=strtoupper($_POST['txtRazonSocial']);
        $txtDireccion=ucwords($_POST['txtDireccion']);
       
        $txtTelefono=$_POST['txtTelefono'];
        $txtMovil="";
        $txtFax="";
        $txtEmail=$_POST['txtEmail'];
        $txtWeb="";
        $cbciudad=$_POST['cbciudad'];
        $txtObservaciones=ucwords($_POST['txtObservaciones']);
        $id_plan_cuenta = 0;
        $txtAutorizacionSRI=$_POST['txtAutorizacionSRI'];

        $txtEstado=$_POST['switch-estado'];
        $diasCredito=$_POST['diasCredito'];
       // $txtFechaVencimiento=$_POST['txtFechaVencimiento'];
        $fecha= date("Y-m-d h:i:s");
       /*
        if ($_POST['cmbTipoProveedor'] == "Normal") {
          $cmbTipoProveedor = 1;
        }else{
          $cmbTipoProveedor = 2;
        }*/
        
        $cmbTipoProveedor=$_POST['cmbTipoProveedor'];
        
        $cmbTipoPago=0;
        $txtPasaporte=$_POST['txtPasaporte'];
        $txtOtro=$_POST['txtOtro'];
      //  $txtTipoProveedor=$_POST['txtTipoProveedor'];
        $txtTipoProveedor=0;
        
       // $txtTipoSustento=$_POST['txtTipoSustento'];
        $txtTipoSustento=0;
        
      //  $txtTipoComprobante=$_POST['txtTipoComprobante'];
         $txtTipoComprobante=0;
        
     //   $txtRetencionFuente=$_POST['txtRetencionFuente'];
      //  $txtEnlaceRetencionIva=$_POST['txtEnlaceRetencionIva'];
      $txtRetencionFuente=0;
        $txtEnlaceRetencionIva=0;
        $rbCaracterIdentificacion=$_POST['rbCaracterIdentificacion'];
        
        $parteRel=$_POST['switch-parteRel'];
		$txtPasaporte="";
		$txtOtro="";
		 
		
     
            $sqlp="INSERT into proveedores ( nombre_comercial, nombre, direccion, ruc, telefono, movil, fax, email, web, observaciones, id_ciudad, id_plan_cuenta, autorizacion_sri, fecha_vencimiento, id_empresa, id_tipo_proveedor, tipo_pago, pasaporte, otro, tipo_sustento, tipo_comprobante, enlace_retencion_fuente, enlace_retencion_iva,rbCaracterIdentificacion,parteRel)
                    VALUES ('".$txtNombreComercial."','".$txtNombreRepresentante."','".$txtDireccion."','".$txtRucProveedor."','".$txtTelefono."', '".$txtMovil."','".$txtFax."', '".$txtEmail."', '".$txtWeb."', '".$txtObservaciones."','".$cbciudad."','".$id_plan_cuenta."', '".$txtAutorizacionSRI."', '".$fecha."', '".$sesion_id_empresa."', '".$cmbTipoProveedor."', '".$cmbTipoPago."', '".$txtPasaporte."', '".$txtOtro."', '".$txtTipoSustento."', '".$txtTipoComprobante."', '".$txtRetencionFuente."', '".$txtEnlaceRetencionIva."','".$rbCaracterIdentificacion."','".$parteRel."'); ";


            $result1=mysql_query($sqlp);
            if($result1){
                ?><?php echo 1?> <?php
            }else{
                ?><?php echo 2?> <?php
            }     

        /*if($txtNombreComercial != "" & $txtRucProveedor != "" & $cbciudad != ""){
        /*
        // GUARDA EN EL PLAN DE CUENTAS
        $txtCodigo = $_POST['txtCodigo'];
        $clasificacion = $_POST['cmbClase'];
        $tipo = $_POST['cmbTipo'];
        $categoria = "CreditoDebito";

        $clase = filter_var($clasificacion, FILTER_SANITIZE_NUMBER_INT);
        switch ($clase) {
            case "1":
                $clasifica = "Activo";
                break;
            case "2":
                $clasifica = "Pasivo";
                break;
            case "3":
                $clasifica = "Patrimonio";
                break;
            case "4":
                $clasifica = "Gasto";
                break;
            case "5":
                $clasifica = "Ingresos";
                break;
            case "6":
                $clasifica = "Cuentas Contingentes";
                break;
            case "7":
                $clasifica = "Cuentas de Orden";
                break;
        }

        // FUNCION PARA AUMENTAR EL . ASI EL EL USUARIO HAYA PUESTO EL PUNTO O NO
        $cadena9=filter_var($txtCodigo, FILTER_SANITIZE_NUMBER_INT);// elimina el . y solo coje numeros  //echo preg_replace('/[^0-9]/','',$cadena);
        $longitud = strlen($cadena9); // longitud de l acadena
        $caracter = "";
        for($i=0; $i<$longitud; $i++){
            $caracter = $caracter.$cadena9[$i].".";
        }
        $nivel = $longitud;
        //permite sacar el id maximo de plan_cuentas
        try {
                $sqli="Select max(id_plan_cuenta) From plan_cuentas";
                $result=mysql_query($sqli);
                $id_plan_cuenta=0;
                while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                {
                    $id_plan_cuenta=$row['max(id_plan_cuenta)'];
                }
                $id_plan_cuenta++;

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }

          $sql = "insert into plan_cuentas (id_plan_cuenta, codigo, nombre, clasificacion, tipo, categoria, nivel) values ('".$id_plan_cuenta."','".$caracter."','".$txtNombre."','".$clasifica."','".$tipo."','".$categoria."','".$nivel."')";
          $resp = mysql_query($sql);
          if($resp){
              }

             //GUARDA EN LA TABLA PROVEEDORES
            //permite sacar el id maximo de proveedores
       
          
      }else{
          ?> <div class="transparent_ajax_error"><p>Fallo en el envio del Formulario: No hay datos,<?php echo "\n".mysql_error();?></p></div> <?php
      }
              */

    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
    }
}


if($accion == "77"){
     try
     {
        $txtNombreComercial=ucwords($_POST['txtNombreComercial']);
        // $txtNombreRepresentante=$_POST['txtRazonSocial'];
        $txtDireccion=ucwords($_POST['txtDireccion']);
        $txtRucProveedor=$_POST['txtRucProveedor'];
        $txtTelefono=$_POST['txtTelefono'];
        $txtMovil="";
        $txtFax="";
        $txtEmail=$_POST['txtEmail'];
        $txtWeb="";
        $cbciudad=$_POST['cbciudad'];
        $txtObservaciones=ucwords($_POST['txtObservaciones']);
        $id_plan_cuenta = 0;
        $txtAutorizacionSRI=$_POST['txtAutorizacionSRI'];
        
        $fecha= date("Y-m-d h:i:s");
        
        $cmbTipoProveedor=$_POST['cmbTipoProveedor'];
        
        $cmbTipoPago=0;
        $txtPasaporte=$_POST['txtPasaporte'];
        $txtOtro=$_POST['txtOtro'];
        
        $txtTipoProveedor=$_POST['regimenProveedor'];
        
        $txtTipoSustento=$_POST['codSustento'];
        
        $txtTipoComprobante=$_POST['txtTipoComprobante'];
        
        $txtRetencionFuente=$_POST['retencionFuente'];
        
        // $txtRetencionFuente=$_POST['retencionFuente'];
        
         $retencion_fuente_servicios=$_POST['retencionFuenteServicios'];
         $retencion_fuente_proveeduria=$_POST['retencionFuenteProveeduria'];
        $txtEnlaceRetencionIva=$_POST['retencionIVA'];
        
        
        $txtNombre=$_POST['txtNombre'];
        $txtApellido=$_POST['txtApellido'];
       
        $txtNombreRepresentante = $txtNombre." ".$txtApellido;
        
        $banco=$_POST['banco'];
        $cuentaBanco=$_POST['cuentaBanco'];
        $tipoCuenta=$_POST['tipoCuenta'];
        
       
        $parteRel=$_POST['switch-parteRel'];
		$txtPasaporte="";
		$txtOtro="";
		 
		$txtEstado=$_POST['switch-estado'];
		
		$pagoResidente=$_POST['pagoResidente'];
        $tipoRegimen=$_POST['tipoRegimen'];
        $paisPago=$_POST['paisPago'];
        $pagoParaiso=$_POST['pagoParaiso'];
        $pagoPreferente=$_POST['pagoPreferente'];
        $paisResidencia=$_POST['paisResidencia'];
        
        
        try 
		{
            $sqlm="Select max(id_proveedor) From proveedores";
                $result=mysql_query($sqlm);
                $id_proveedor=0;
            while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
            {
                    $id_proveedor=$row['max(id_proveedor)'];
            }
            $id_proveedor++;
        }catch(Exception $ex) { ?> 
        
			<div class="transparent_ajax_error"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }
			
			   $diasCredito=(trim($_POST['diasCredito'])=='')?0:$_POST['diasCredito'];
			   $retencion_fuente_activos = $_POST['retencionFuenteActivos'];
			 
			 $rbCaracterIdentificacion=$_POST['rbCaracterIdentificacion'];
          $sqlp="INSERT into proveedores ( id_proveedor,          nombre_comercial,          nombre,                   nombreProveedor,      apellidoProveedor, direccion, ruc, telefono, movil, fax, email, web, observaciones, id_ciudad, id_plan_cuenta, autorizacion_sri, fecha_vencimiento, id_empresa, id_tipo_proveedor, tipo_pago, pasaporte, otro, tipo_sustento, tipo_comprobante, enlace_retencion_fuente, enlace_retencion_iva,rbCaracterIdentificacion,parteRel,estado_Proveedor,diasCredito,tipoContribuyente,banco,cuentaBanco,tipoCuenta,pagoResidente,tipoRegimen,paisPago,pagoParaiso,pagoPreferente,paisResidencia,retencion_fuente_servicios, retencion_fuente_activos,retencion_fuente_proveeduria)
                    VALUES (            '".$id_proveedor."','".$txtNombreComercial."','".$txtNombreRepresentante."',   '".$txtNombre."',     '".$txtApellido."','".$txtDireccion."','".$txtRucProveedor."','".$txtTelefono."', '".$txtMovil."','".$txtFax."', '".$txtEmail."', '".$txtWeb."', '".$txtObservaciones."','".$cbciudad."','".$id_plan_cuenta."', '".$txtAutorizacionSRI."', '".$fecha."', '".$sesion_id_empresa."', '".$cmbTipoProveedor."', '".$cmbTipoPago."', '".$txtPasaporte."', '".$txtOtro."', '".$txtTipoSustento."', '".$txtTipoComprobante."', '".$txtRetencionFuente."', '".$txtEnlaceRetencionIva."','".$rbCaracterIdentificacion."','".$parteRel."','".$txtEstado."','".$diasCredito."',
                    '".$txtTipoProveedor."','".$banco."','".$cuentaBanco."','".$tipoCuenta."','".$pagoResidente."','".$tipoRegimen."','".$paisPago."','".$pagoParaiso."','".$pagoPreferente."','".$paisResidencia."','".$retencion_fuente_servicios."','".$retencion_fuente_activos."','".$retencion_fuente_proveeduria."'); ";

// if($sesion_id_empresa==41){
//     echo $sqlp;
// }
            $result1=mysql_query($sqlp);
            if($result1){
                ?><?php echo 1?> <?php
            }else{
                ?><?php echo 2?> <?php
            }     

     

    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
    }
}
if($accion == "88"){
     try
     {
        $txtNombreComercial=ucwords($_POST['txtNombreComercial']);
        // $txtNombreRepresentante=$_POST['txtRazonSocial'];
        $txtDireccion=ucwords($_POST['txtDireccion']);
        $txtRucProveedor=$_POST['txtRucProveedor'];
        $txtTelefono=$_POST['txtTelefono'];
        $txtMovil="";
        $txtFax="";
        $txtEmail=$_POST['txtEmail'];
        $txtWeb="";
        $cbciudad=$_POST['cbciudad'];
        $txtObservaciones=ucwords($_POST['txtObservaciones']);
        $id_plan_cuenta = 0;
        $txtAutorizacionSRI=$_POST['txtAutorizacionSRI'];
        
        $fecha= date("Y-m-d h:i:s");
        
        $cmbTipoProveedor=$_POST['cmbTipoProveedor'];
        
        $cmbTipoPago=0;
        $txtPasaporte=$_POST['txtPasaporte'];
        $txtOtro=$_POST['txtOtro'];
        
        $txtTipoProveedor=$_POST['regimenProveedor'];
        
        $txtTipoSustento=$_POST['codSustento'];
        
        $txtTipoComprobante=$_POST['txtTipoComprobante'];
        
        $txtRetencionFuente=$_POST['retencionFuente'];
        
        // $txtRetencionFuente=$_POST['retencionFuente'];
        
        $retencion_fuente_servicios=$_POST['retencionFuenteServicios'];
         $retencion_fuente_proveeduria=$_POST['retencionFuenteProveeduria'];
     $retencion_fuente_activos= $_POST['retencionFuenteActivos'];
        $txtEnlaceRetencionIva=$_POST['retencionIVA'];
        
        
        $txtNombre=$_POST['txtNombre'];
        $txtApellido=$_POST['txtApellido'];
       
        $txtNombreRepresentante = $txtNombre." ".$txtApellido;
        
        $banco=$_POST['banco'];
        $cuentaBanco=(trim($_POST['cuentaBanco'])=='')?0:$_POST['cuentaBanco'];
        $tipoCuenta=$_POST['tipoCuenta'];
        
       
        $parteRel=$_POST['switch-parteRel'];
		$txtPasaporte="";
		$txtOtro="";
		 
		$txtEstado=$_POST['switch-estado'];
		
		$pagoResidente=$_POST['pagoResidente'];
        $tipoRegimen=$_POST['tipoRegimen'];
        $paisPago=$_POST['paisPago'];
        $pagoParaiso=$_POST['pagoParaiso'];
        $pagoPreferente=$_POST['pagoPreferente'];
        $paisResidencia=$_POST['paisResidencia'];
        $id_proveedor=$_POST['idproveedor'];
        
				 $rbCaracterIdentificacion=$_POST['rbCaracterIdentificacion'];
			   $diasCredito=(trim($_POST['diasCredito'])=='')?0:$_POST['diasCredito'];
                    
      $sqlp="UPDATE `proveedores` SET `parteRel`='".$parteRel."',`nombre_comercial`='".$txtNombreComercial."' ,`nombre`='".$txtNombre."',
      `nombreProveedor`='".$txtRucProveedor."',`apellidoProveedor`='".$txtApellido."',`direccion`='".$txtDireccion."',`ruc`='".$txtRucProveedor."',
      `telefono`='".$txtTelefono."',`movil`='".$txtMovil."',`fax`='".$txtFax."',`email`='".$txtEmail."',`web`='".$txtWeb."',`observaciones`='".$txtObservaciones."',
      `id_ciudad`='".$cbciudad."',`id_plan_cuenta`='".$id_plan_cuenta."',`autorizacion_sri`='".$txtAutorizacionSRI."',`fecha_vencimiento`='".$fecha."',
      `id_empresa`='".$sesion_id_empresa."',`id_tipo_proveedor`='".$cmbTipoProveedor."',`tipo_pago`='".$cmbTipoPago."',`pasaporte`='".$txtPasaporte."',
      `otro`='".$txtOtro."',`tipo_sustento`='".$txtTipoSustento."',`tipo_comprobante`='".$txtTipoComprobante."',`enlace_retencion_fuente`='".$txtRetencionFuente."',
      `enlace_retencion_iva`='".$txtEnlaceRetencionIva."',`estado_Proveedor`='".$txtEstado."',`diasCredito`='".$diasCredito."',`tipoContribuyente`='".$txtTipoProveedor."',
      `banco`='".$banco."',`cuentaBanco`='".$cuentaBanco."',`tipoCuenta`='".$tipoCuenta."',`pagoResidente`='".$pagoResidente."',`tipoRegimen`='".$tipoRegimen."',
      `paisPago`='".$paisPago."',`pagoParaiso`='".$pagoParaiso."',`pagoPreferente`='".$pagoPreferente."',`paisResidencia`='".$paisResidencia."',
      `retencion_fuente_servicios`='".$retencion_fuente_servicios."', `rbCaracterIdentificacion`='".$rbCaracterIdentificacion."',`parteRel`='".$parteRel."',
      retencion_fuente_activos='".$retencion_fuente_activos."', retencion_fuente_proveeduria= '".$retencion_fuente_proveeduria."'       WHERE  `id_proveedor`=$id_proveedor ";

// echo $sqlp;
            $result1=mysql_query($sqlp);
            if($result1){
                ?><?php echo 3?> <?php
            }else{
                ?><?php echo 4?> <?php
            }     

     

    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
    }
}
if($accion == "3"){
    $id_proveedor= $_POST['idProveedor'];
    
    $sqlCompras = "SELECT id_compra FROM compras WHERE id_proveedor = $id_proveedor AND id_empresa = $sesion_id_empresa";
    $resultCompras = mysql_query($sqlCompras);

    $cantidadCompras = mysql_num_rows($resultCompras);

    if ($cantidadCompras == 0) {
        // No hay compras asociadas, se puede eliminar el proveedor
        $sqlEliminarProveedor = "DELETE FROM proveedores WHERE id_proveedor = $id_proveedor";
        $resultEliminarProveedor = mysql_query($sqlEliminarProveedor);

        if ($resultEliminarProveedor) {
            echo 'Proveedor eliminado con éxito.';
        } else {
            echo 'No se pudo eliminar el proveedor porque existen registros de compras.';
        }
    } else {
        // El proveedor tiene compras registradas, no se puede eliminar
        echo 'El proveedor tiene compras registradas, no se puede eliminar.';
    }

}

if($accion == "4"){
    // VALIDA PARA QUE EL RUC DEL PROVEEDOR NO SE REPITA PAGINA: proveedores.php
     try
     {
        if(isset ($_POST['ruc'])){
          $nombre1 = $_POST['ruc'];
          $sql1 = "SELECT ruc from proveedores where ruc='".$nombre1."' and id_empresa='".$sesion_id_empresa."'; ";
          $resp1 = mysql_query($sql1);
          $entro=0;
          while($row1=mysql_fetch_array($resp1))//permite ir de fila en fila de la tabla
                    {
                        $var1=$row1["ruc"];
                    }
           $nombre2 = $nombre1;
           $var2 = $var1;
          if($var2==$nombre2){
               if($var2==""&&$nombre2==""){
                     $entro=0;
                  }else{
                      $entro=1;
                  }
          }
       
         echo $entro;
         }

     }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="alert alert-danger"><p>Error: <?php echo "".$e ?></p></div> <?php
    }

    }

if ($accion == 8) {
    include('../conexion2.php');
    require_once('../vendor/php-excel-reader/excel_reader2.php');
    require_once('../vendor/SpreadsheetReader.php');

    $allowedFileType = ['application/vnd.ms-excel', 'text/xls', 'text/xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

    if (in_array($_FILES["file"]["type"], $allowedFileType)) {

        $targetPath = 'subidas/proveedores/' . $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

        $Reader = new SpreadsheetReader($targetPath);
        $con = $conexion;
        $sheetCount = count($Reader->sheets());
        $columna_error = null; // Variable para almacenar el índice de la columna en caso de error

        for ($i = 0; $i < $sheetCount; $i++) {
            $Reader->ChangeSheet($i);

            foreach ($Reader as $indice_columna => $Row) {
                if (trim($Row[5]) == '') {
                    break;
                }

                $rbCaracterIdentificacion = ($Row[0] != '') ? mysqli_real_escape_string($con, $Row[0]) : '';

                $parteRel = ($Row[1] != '') ? mysqli_real_escape_string($con, $Row[1]) : '';

                $nombre_comercial = ($Row[2] != '') ? mysqli_real_escape_string($con, $Row[2]) : '';

                $nombre = ($Row[3] != '') ? mysqli_real_escape_string($con, $Row[3]) : '';

                $direccion = ($Row[4] != '') ? mysqli_real_escape_string($con, $Row[4]) : '';

                $ruc = ($Row[5] != '') ? mysqli_real_escape_string($con, $Row[5]) : '';

                if (strlen($Row[0]) == 10) {
                    $rbCaracterIdentificacion = '05';
                } elseif (strlen($Row[0]) == 13) {
                    $rbCaracterIdentificacion = '04';
                } else {
                    $rbCaracterIdentificacion = '06';
                }

                $telefono = ($Row[6] != '') ? mysqli_real_escape_string($con, $Row[6]) : '';

                $movil = ($Row[7] != '') ? mysqli_real_escape_string($con, $Row[7]) : 0;

                $fax = ($Row[8] != '') ? mysqli_real_escape_string($con, $Row[8]) : 0;

                $email = ($Row[9] != '') ? mysqli_real_escape_string($con, $Row[9]) : 0;

                $web = ($Row[10] != '') ? mysqli_real_escape_string($con, $Row[10]) : 0;

                $observaciones = ($Row[11] != '') ? mysqli_real_escape_string($con, $Row[11]) : 0;

                $nombreCiudad = ($Row[12] != '') ? mysqli_real_escape_string($con, $Row[12]) : 0;

                if ($nombreCiudad != '0') {
                    $nombreCiudad = mb_strtoupper($nombreCiudad);
                    $sqlCiudad = "SELECT `id_ciudad`, `ciudad`, `id_provincia` FROM `ciudades` WHERE ciudad='" . $nombreCiudad . "'";
                    $resultCiudad = mysql_query($sqlCiudad);
                    while ($rowCiudad = mysql_fetch_array($resultCiudad)) {
                        $id_ciudad = $rowCiudad['id_ciudad'];
                    }
                } else {
                    $id_ciudad = 0;
                }

                $autorizacion_sri = ($Row[13] != '') ? mysqli_real_escape_string($con, $Row[13]) : '';

                $fecha_vencimiento = ($Row[14] != '') ? mysqli_real_escape_string($con, $Row[14]) : '0000-00-00';

                $tipo_proveedor = ($Row[15] != '') ? mysqli_real_escape_string($con, $Row[15]) : '';

                if (mb_strtoupper($tipo_proveedor) == 'PERSONA NATURAL' || trim($tipo_proveedor) == '01') {
                    $id_tipo_proveedor = '01';
                } elseif (mb_strtoupper($tipo_proveedor) == 'SOCIEDAD' || trim($tipo_proveedor) == '02') {
                    $id_tipo_proveedor = '02';
                } else {
                    $id_tipo_proveedor = '0';
                }

                $pasaporte = ($Row[16] != '') ? mysqli_real_escape_string($con, $Row[16]) : '';

                $otro = ($Row[17] != '') ? mysqli_real_escape_string($con, $Row[17]) : '';

             echo   $query = "INSERT into proveedores ( nombre_comercial, nombre, direccion, ruc, telefono, movil, fax, email, web, observaciones, id_ciudad, id_plan_cuenta, autorizacion_sri, fecha_vencimiento, id_empresa, id_tipo_proveedor, tipo_pago, pasaporte, otro, tipo_sustento, tipo_comprobante,rbCaracterIdentificacion,parteRel)
                VALUES ('" . $nombre_comercial . "','" . $nombre . "','" . $direccion . "','" . $ruc . "','" . $telefono . "', '" . $movil . "','" . $fax . "', '" . $email . "', '" . $web . "', '" . $observaciones . "','" . $id_ciudad . "','0', '" . $autorizacion_sri . "', '" . $fecha_vencimiento . "', '" . $sesion_id_empresa . "', '" . $id_tipo_proveedor . "', '0', '" . $pasaporte . "', '" . $otro . "', '0', '0','" . $rbCaracterIdentificacion . "','" . $parteRel . "'); ";

                $resultados = mysqli_query($con, $query);

                if (empty($resultados)) {
                    $columna_error = $indice_columna; // Almacenar el índice de la columna que causó el error
                    break; // Salir del bucle foreach si hay un error
                }
            }

            if (!is_null($columna_error)) {
                break; // Salir del bucle for si hay un error en alguna columna
            }
        }

        if (!is_null($columna_error)) {
            echo 'Error en la columna: ' . $columna_error;
            exit;
        } else {
            echo 'Todos los registros fueron importados correctamente';
        }
    } else {
        $type = "error";
        $message = "El archivo enviado es invalido. Por favor vuelva a intentarlo";
        echo '2';
    }
}


// if($accion == 8){
     
//     include('../conexion2.php');
//     require_once('../vendor/php-excel-reader/excel_reader2.php');
//     require_once('../vendor/SpreadsheetReader.php');
 
    
//       $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
      
//       if(in_array($_FILES["file"]["type"],$allowedFileType)){
    
//      $targetPath = 'subidas/proveedores/'.$_FILES['file']['name'];
//         move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
    
//         $Reader = new SpreadsheetReader($targetPath);
//     $con = $conexion;
//     //   $sheetCount = count($Reader->sheets());
//     $sheetCount = count($Reader->sheets());
//         for($i=0;$i<$sheetCount;$i++)
//         {
//         //   echo $i;
//           $Reader->ChangeSheet($i);
//     //   var_dump($Reader);
//           foreach ($Reader as $Row)
//           {

//             $rbCaracterIdentificacion = (isset($Row[1]) || $Row[1]=='')? mysqli_real_escape_string($con,$Row[1]): '' ;

//             $parteRel = (isset($Row[2] )|| $Row[2]=='')?mysqli_real_escape_string($con,$Row[2]):'';
            
//             $nombre_comercial = (isset($Row[3]) || $Row[3]=='')? mysqli_real_escape_string($con,$Row[3]): '' ;

//             $nombre = (isset($Row[4]) || $Row[4]=='')? mysqli_real_escape_string($con,$Row[4]): '' ;
          
//             $direccion = (isset($Row[5]) || $Row[5]=='')? mysqli_real_escape_string($con,$Row[5]): '' ;
          
//             $ruc = (isset($Row[6]) || $Row[6]=='')? mysqli_real_escape_string($con,$Row[6]): '' ;
           
//             $telefono = (isset($Row[7]) || $Row[7]=='')? mysqli_real_escape_string($con,$Row[7]): '' ;
            
//             $movil = (isset($Row[8]) || $Row[8]=='')? mysqli_real_escape_string($con,$Row[8]): 0 ;
            
//             $fax = (isset($Row[9]) || $Row[9]=='')? mysqli_real_escape_string($con,$Row[9]): 0 ;
            
//             $email = (isset($Row[10]) || $Row[10]=='')? mysqli_real_escape_string($con,$Row[10]): 0;
         
//             $web = (isset($Row[11]) || $Row[11]=='')? mysqli_real_escape_string($con,$Row[11]): 0 ;
            
//             $observaciones = (isset($Row[12]) || $Row[12]=='')? mysqli_real_escape_string($con,$Row[12]): 0 ;
            
//             $id_ciudad = (isset($Row[13]) || $Row[13]=='')? mysqli_real_escape_string($con,$Row[13]): 0 ;


//             $id_plan_cuenta = (isset($Row[14]) || $Row[14]=='')? mysqli_real_escape_string($con,$Row[14]): '' ;
            
//             $autorizacion_sri = (isset($Row[15]) || $Row[15]=='')? mysqli_real_escape_string($con,$Row[15]): '' ;
            
//             $fecha_vencimiento = (isset($Row[16]) || $Row[16]=='')? mysqli_real_escape_string($con,$Row[16]): '' ;
         
//             // $id_empresa = (isset($Row[17]) || $Row[17]=='')? mysqli_real_escape_string($con,$Row[17]): '' ;
            
//             $id_tipo_proveedor = (isset($Row[18]) || $Row[18]=='')? mysqli_real_escape_string($con,$Row[18]): '' ;
            
//             $tipo_pago = (isset($Row[19]) || $Row[19]=='')? mysqli_real_escape_string($con,$Row[19]): '' ;

         
//             $pasaporte = (isset($Row[20]) || $Row[20]=='')? mysqli_real_escape_string($con,$Row[20]): '' ;
            
//             $otro = (isset($Row[21]) || $Row[21]=='')? mysqli_real_escape_string($con,$Row[21]): '' ;
            
//             $tipo_sustento = (isset($Row[22]) || $Row[22]=='')? mysqli_real_escape_string($con,$Row[22]): '' ;
         
//             $tipo_comprobante = (isset($Row[23]) || $Row[23]=='')? mysqli_real_escape_string($con,$Row[23]): '' ;
            
//             $enlace_retencion_fuente = (isset($Row[24]) || $Row[24]=='')? mysqli_real_escape_string($con,$Row[24]): '' ;
            
//             $enlace_retencion_iva = (isset($Row[25]) || $Row[25]=='')? mysqli_real_escape_string($con,$Row[25]): '' ;


//             $query="INSERT into proveedores ( nombre_comercial, nombre, direccion, ruc, telefono, movil, fax, email, web, observaciones, id_ciudad, id_plan_cuenta, autorizacion_sri, fecha_vencimiento, id_empresa, id_tipo_proveedor, tipo_pago, pasaporte, otro, tipo_sustento, tipo_comprobante, enlace_retencion_fuente, enlace_retencion_iva,rbCaracterIdentificacion,parteRel)
//             VALUES ('".$nombre_comercial."','".$nombre."','".$direccion."','".$ruc."','".$telefono."', '".$movil."','".$fax."', '".$email."', '".$web."', '".$observaciones."','".$id_ciudad."','".$id_plan_cuenta."', '".$autorizacion_sri."', '".$fecha_vencimiento."', '".$sesion_id_empresa."', '".$id_tipo_proveedor."', '".$tipo_pago."', '".$pasaporte."', '".$otro."', '".$tipo_sustento."', '".$tipo_comprobante."', '".$enlace_retencion_fuente."', '".$enlace_retencion_iva."','".$rbCaracterIdentificacion."','".$parteRel."'); ";

//             // if (!empty($nombres) || !empty($cargo) || !empty($celular) || !empty($descripcion)) {
//         //   echo  $query = "INSERT INTO `productos`(`producto`, `existencia_minima`, `existencia_maxima`, `stock`, `costo`, `id_categoria`, `id_proveedor`, `precio1`, `precio2`, `precio3`, `precio4`, `precio5`, `precio6`, `iva`, `ICE`, `IRBPNR`, `hab`, `ganancia1`, `ganancia2`, `fecha_registro`, `ano`, `mes`, `id_empresa`, `codigo`, `codPrincipal`, `codAux`, `tipos_compras`, `id_cuenta`, `tipo_costo`, `produccion`, `proceso`, `grupo`, `promocion`, `img`, `detalle`, `marca`, `modelo`, `tipo`, `color`, `id_bodega`, `id_centroCostos`) VALUES ('$producto',$existencia_minima,'$existencia_maxima','$stock','$costo','$id_categoria','$id_proveedor','$precio1','$precio2','$precio3' ,'$precio4','$precio5', '$precio6' , '$iva' , '$ice','$aIRBPNR','$hab', '$ganancia1','$ganancia2','$fecha_registro','$ano','$mes','$id_empresa','$codigo','$codPrincipal','$codAux' ,'$tipos_compras','$id_cuenta','$tipo_costo' ,'$produccion', '$proceso','$grupo','$promocion','$img','$detalle','$marca','$modelo','$tipo','$color','$id_bodega','$id_centroCostos')";
//               // $query = "insert into productos(nombres,cargo, celular, descripcion) values('".$nombres."','".$cargo."','".$celular."','".$descripcion."')";
//               $resultados = mysqli_query($con, $query);
 
//               if (! empty($resultados)) {
//                 $type = "success";
//                 $message = "Excel importado correctamente";
//               } else {
//                 $type = "error";
//                 $message = "Hubo un problema al importar registros";
//               }
//             //  }
//           }
    
//         }
//       }
//       else
//       { 
//         $type = "error";
//         $message = "El archivo enviado es invalido. Por favor vuelva a intentarlo";
//       }
//     }
     if($accion == 9){
        require_once('../conexion2.php');
   
        include("../librerias/PHPExcel/Classes/PHPExcel.php"); 

        if(!empty($_FILES["file"]))  
        {  
           $connect=$conexion;
            // $connect = mysqli_connect("localhost", "root", "", "testing");  
             $file_array = explode(".", $_FILES["file"]["name"]);  
            // echo $file_array[0];
            // echo $file_array[1];
             if($file_array[1] == "xlsx")  
             {  
                 
                  $output = '';  

                  $object = PHPExcel_IOFactory::load($_FILES["file"]["tmp_name"]);
          
                  foreach($object->getWorksheetIterator() as $worksheet)  
                  {  
                   
                       $highestRow = $worksheet->getHighestRow();  
                       for($row=1; $row<=$highestRow; $row++)  
                       {  

                    //    echo $worksheet->getCell('A' . $row)->getValue();

                        // echo $worksheet->getCellByColumnAndRow(5, $row)->getValue();

                            $rbCaracterIdentificacion = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(0, $row)->getValue());  
                            if( strlen($rbCaracterIdentificacion)==10){
                                $rbCaracterIdentificacion='05';
                            }else if ( strlen($rbCaracterIdentificacion)==13){
                                 $rbCaracterIdentificacion='06';
                            }
                           
                            $parteRel = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(1, $row)->getValue());  
                           
                            $nombre_comercial = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(2, $row)->getValue()); 

                            $nombre = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(3, $row)->getValue());  

                            $direccion = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(4, $row)->getValue());  
                           
                            $ruc = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(5, $row)->getValue()); 

                        //     if(trim($ruc) ==''){
                        //         break;
                        // }
                            $telefono = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(6, $row)->getValue());  

                            $movil = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(7, $row)->getValue());  
                           
                            $fax = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(8, $row)->getValue()); 

                            $email = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(9, $row)->getValue());  

                            $web = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(10, $row)->getValue());  
                           
                            $observaciones = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(11, $row)->getValue()); 

                            $nombreCiudad = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(12, $row)->getValue());  
                           
                            if($nombreCiudad != '0'){
                                $nombreCiudad = mb_strtoupper($nombreCiudad);
                                 $sqlCiudad = "SELECT `id_ciudad`, `ciudad`, `id_provincia` FROM `ciudades` WHERE ciudad='".$nombreCiudad."'";
                                $resultCiudad = mysql_query($sqlCiudad);
                                while($rowCiudad = mysql_fetch_array($resultCiudad)){
                                    $id_ciudad = $rowCiudad['id_ciudad'];
                                }
                            }else{
                                $id_ciudad=0;
                            }
                            
                            $autorizacion_sri = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(13, $row)->getValue()); 

                            $fecha_vencimiento = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(14, $row)->getValue()); 
                            $fecha_vencimiento = ($fecha_vencimiento=='')?'0000-00-00':$fecha_vencimiento;

                            $tipo_proveedor = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(15, $row)->getValue());  
                           
                            if( mb_strtoupper($tipo_proveedor) =='PERSONA NATURAL' || trim($tipo_proveedor)=='01' ){
                                $id_tipo_proveedor='01';

                            }elseif(mb_strtoupper($tipo_proveedor) =='SOCIEDAD' || trim($tipo_proveedor)=='02'){
                                $id_tipo_proveedor='02';

                            }else{
                                $id_tipo_proveedor='0';
                            }
                            $pasaporte = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(16, $row)->getValue()); 

                            $otro = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(17, $row)->getValue());  

                        if($ruc==''){
                                $output .= '</table>';  
                                // echo $output;  
                                echo 'Datos del proveedor guardados correctamente';
                                exit;
                        }
                            $query="INSERT into proveedores ( nombre_comercial, nombre, direccion, ruc, telefono, movil, fax, email, web, observaciones, id_ciudad, id_plan_cuenta, autorizacion_sri, fecha_vencimiento, id_empresa, id_tipo_proveedor, tipo_pago, pasaporte, otro, tipo_sustento, tipo_comprobante,rbCaracterIdentificacion,parteRel)
                            VALUES ('".$nombre_comercial."','".$nombre."','".$direccion."','".$ruc."','".$telefono."', '".$movil."','".$fax."', '".$email."', '".$web."', '".$observaciones."','".$id_ciudad."','0', '".$autorizacion_sri."', '".$fecha_vencimiento."', '".$sesion_id_empresa."', '".$id_tipo_proveedor."', '0', '".$pasaporte."', '".$otro."', '0', '0','".$rbCaracterIdentificacion."','".$parteRel."'); ";
                            $result = mysqli_query($connect, $query);  

                            if(!$result){

                                echo 'Error al guardar el proveedor con RUC => '.$ruc.' , se detuvo en ese registro el guardado de datos del proveedor.';
                                exit;
                            }
                       }  
                  }  

             }  
             else  
             {  
                  echo '<label class="text-danger">Invalid File</label>';  
             }  
        }  else{
           echo 'No hay archivo';
        }
    }	
?>