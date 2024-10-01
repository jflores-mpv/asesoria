<?php

	//require_once('../ver_sesion.php');
	//Start session
	session_start();
	//Include database connection details
	require_once('../conexion.php');
    require_once('../clases/correo.php');
    require_once('../clases/contrasenas.php');
    require_once('encriptar.php');
    date_default_timezone_set('America/Guayaquil');

try
    {
      $accion = $_POST['accion'];
       
      if($accion == 1) {
          
		$ruc = "";
		$ruc=$_POST['txtCedula'];

		$existe_activacion=1;

		if ($ruc != "")
		{
			$ruc_repetido=0;
			$sql="SELECT ruc from empresa where ruc='".$ruc."'";
			$resultado = mysql_query($sql);
			$fila=mysql_num_rows($resultado);
			if ($fila>0)
			{
				$ruc_repetido=1;
				?>
				<div class='alert alert-danger'><p>Ruc o Pasaporte ya existe</p></div> <?php
			} 
		}
			
		if ($existe_activacion==1 and $ruc_repetido==0)
		{
			//	echo "codigoActivacion";
			//	echo $codigoActivacion;
			
            // GUARDAR EMPRESA
            
            $razonSocial=ucwords($_POST['txtRazonSocial']);
			$nombre = ucwords($_POST['txtNombreEmpresa']);
			$direccion = ucwords($_POST['txtDireccion']);
			$telefono1 = $_POST['txtTelefono1'];
			$telefono2 = $_POST['txtTelefono2'];
			$email = $_POST['txtEmail'];
			$web = $_POST['txtWeb'];
			$pais = $_POST['opcion'];
			$provincia = $_POST['opcion1'];
			$id_ciudad = $_POST['opcion2'];     
            
            $estado_empresa = $_POST['cmbEstado'];
            $txtCodActivacion = $_POST['txtCodActivacion'];
            //$codigoActivacion = $_POST['codigoActivacion'];
            

            //permisos ASIENTOS CONTABLES
            $cheAsientosContablesGuardar = "Si";
            $cheAsientosContablesModificar = "Si";
            $cheAsientosContablesEliminar = "Si";
            // permisos PLAN CUENTAS
            $chePlanCuentasGuardar = "Si";
            $chePlanCuentasModificar = "Si";
            $chePlanCuentasEliminar = "Si";

            // permisos BANCOS
            $cheBancosGuardar = "Si";
            $cheBancosModificar = "Si";
            $cheBancosEliminar = "Si";


            $actividad = ucwords($_POST['txtActividad']);
            $cod_empresa = $_POST['txtCodEmpresa'];

            //************** valores para el perido contable
            $fecha_inicio= date("Y-m-d H:i:s");
            //$fecha_inicio = $_POST['txtFechaIngreso'];
            $año = date("Y")+1;
            $fecha = date("m-d H:i:s");
            // $fecha_fin = $año."-".$fecha;
            
            $fechaActualFechaFin = new DateTime();

// Incrementar el año en 1
            $fechaActualFechaFin->modify('+1 year');
            
            // Comprobar si el nuevo año es bisiesto y si la fecha es 29 de febrero
            if ($fechaActualFechaFin->format('m-d') == '02-29' && !checkdate(2, 29, $fechaActual->format('Y'))) {
                // Si no es bisiesto, ajustar la fecha a 28 de febrero
                $fechaActualFechaFin->modify('-1 day');
            }
            $fecha_fin = $fechaActualFechaFin->format('Y-m-d H:i:s');
            
            
            $estado= "Activo";
		
            $ingresos= "0";
            $gastos= "0";

			$hoy = date("Y-m-d");  
     
            
// 			$id_tipo_empresa=$_POST['txtTipoEmpresa'];
$id_tipo_empresa=20;
            
            $limite =$_POST['cantidadFacturas'];
            
            
           
                
                $estado_empresa ="Inactivo";
                
                if($limite<='50') {
                    $valor = '10';
                } else if($limite=='100') {
                    $valor = '12.5';
                } else if($limite=='200') {
                    $valor = '20';
                } else if($limite=='300') {
                    $valor = '27.5';
                } else if($limite=='400') {
                    $valor = '35';
                } else if($limite=='500' or $limite=='600') {
                    $valor = '40';
                } else if($limite=='1200') {
                    $valor = '60';
                } else if($limite=='0') {
                    $valor = '70';
                } else {
                    $valor = '0';
                }
            

            
            
            
            
            
            
         $sqlp="insert into empresa  (  `ruc`,     `nombre`,    `razonSocial`,   `direccion`,     `pais`,     `provincia`,        `id_ciudad`,     `telefono1`,     `telefono2`,         `email`,    `pag_web`,  `imagen`,   `fecha_inicio`, `informacion_general`, `perfil_empresa`, `descripcion`, `mision`, `vision`, `actividad_empresa`, `codigo_empresa`,      `caracter_identificacion`,    `autorizacion_sri`,       `id_tipo_empresa`,          `estado`,             `Redondeo`, `Cliente_id`, `Ocontabilidad`, `FElectronica`, `clave`, `cod_aula`, `limiteFacturas`, `URL`,`planOriginal`,`distribuidor`	)
                                values ( '".$ruc."','".$nombre."'   ,    '".$razonSocial."'         ,'".$direccion."',         '".$pais."','".$provincia."',  '".$id_ciudad."','".$telefono1."',NULL,    '".$email."',   NULL,  NULL,  '".$hoy."',         NULL,            NULL,           NULL,        NULL ,     NULL,    'NULL',  '".$cod_empresa."',   'NULL',                  'NULL',         '".$id_tipo_empresa."',  '".$estado_empresa."',        NULL,      NULL,             NULL,       NULL,          NULL,     NULL,'".$limite."' ,'".$dominio."','".$limite."','".$valor."' );";

            $result=mysql_query($sqlp) or die(mysql_error());
            
            $id_empresa=mysql_insert_id();

                 $sql = "insert into periodo_contable (fecha_desde, fecha_hasta, estado, ingresos, gastos, id_empresa) values 
                ('".$fecha_inicio."','".$fecha_fin."','".$estado."','".$ingresos."','".$gastos."', '".$id_empresa."')";
                $id_periodo_contable=mysql_insert_id($sql);
                $resp = mysql_query($sql) or die(mysql_error());
               
                    //  "</br>periodo contable creado";
                    $sqlEst = "insert into establecimientos ( id_empresa, codigo, direccion) values ('".$id_empresa."','001','direccion')";
                    
                    $respEst = mysql_query($sqlEst) or die(mysql_error());
                    $id_establecimiento=mysql_insert_id();  
                                //  "establecimiento";
                                $sqlemi = "insert into emision (  `id_est`, `codigo`, `numFac`, `tipoEmision`, `ambiente`, `tipoFacturacion`, `formato`) values 
                                ('".$id_establecimiento."','001','2','F','1','1','1')";
                                $id_emision=mysql_insert_id();
                                $respemi= mysql_query($sqlemi) or die(mysql_error());
                                $id_emision=mysql_insert_id();
                                //  $sqlemi;
                                        $sqlAdminEmpleado = "insert into empleados(nombre, apellido, tipo, estado, id_ciudad, fecha_registro, id_empresa)
                                        values ( '".$nombre."', '".$nombre."', 'Administrador', 'Activo', '".$id_ciudad."', '".$hoy."', '".$id_empresa."');";
                                        $resultAdminEmpleado=mysql_query($sqlAdminEmpleado) or die(mysql_error());
                                        $id_empleado=mysql_insert_id();
                                        //   echo "</br>Empleado guardado";  
                                                
                                                $sqlPermisosAsientosContables = "insert into permisos_asientos_contables  ( guardar, modificar, eliminar)
                                                                                                                            values ('Si', 'Si', 'Si');";
                                                                                                                         
                                                $resultPermisosAsientosContables=mysql_query($sqlPermisosAsientosContables) or die(mysql_error());
                                                $id_PermisosAsientosContables=mysql_insert_id();   
                                                $sqlPermisosPlanCuentas = "insert into permisos_plan_cuentas( guardar, modificar, eliminar)
                                                                                                            values ('Si', 'Si', 'Si');";               
                                                $resultPermisosPlanCuentas=mysql_query($sqlPermisosPlanCuentas) or die(mysql_error());
                                                $id_permiso_plan_cuenta=mysql_insert_id(); 
                                                
                                                $sqlPermisosBancos = "insert into permisos_bancos(guardar, modificar, eliminar)
                                                                                            values ('Si', 'Si', 'Si');";                                                
                                                $resultPermisosBancos=mysql_query($sqlPermisosBancos) or die(mysql_error());
                                                $id_permisos_bancos=mysql_insert_id(); 
                                          
                                                $user_password = 'CONTAWEB';
                                                  $claveEncriptada= $encriptar($user_password);
                                                $sqlAdminUsuario = "insert into usuarios( `id_empresa`,     `id_empleado`,        `login`, `password`,  
                                                `tipo`,      `estado`, `fecha_registro`,       `permisos`,        `id_permiso_asiento_contable`,
                                                `id_permiso_plan_cuenta`, `reportes_contables`, `id_permisos_bancos`, `id_est`, `id_punto`, `Factura`, `Pedido`, `Redondeo`)
                                                
                                                    values 
                                                
                                                ('".$id_empresa."', '".$id_empleado."',   'CONTAWEB', '$claveEncriptada', 'Administrador', 'Activo', '".$hoy."',  
                                                    'Lectura y Escritura', '".$id_PermisosAsientosContables."', '".$id_permiso_plan_cuenta."', 'Si', 
                                                    '".$id_permisos_bancos."', '".$id_establecimiento."', '".$id_emision."', '0', NULL, NULL);";
                                                    
                                                $resultAdminUsuario=mysql_query($sqlAdminUsuario) or die(mysql_error());
                                                $id_usuario=mysql_insert_id();
                                                            
                                                $sqlModulos="Select id From modulos where tipoEmpresa='6';";
                                                $resultModulos=mysql_query($sqlModulos);
                                                $query = "INSERT INTO permisos_usuarios VALUES ";
                                                $numRows = mysql_num_rows($resultModulos);
                                                $counter = 0;
                                                while($rowModulos=mysql_fetch_array($resultModulos))
                                                 {  
                                                    $counter ++;
                                                    $id = $rowModulos['id'];
                                                    $permisos = $_POST['permiso'][$id];
                                                    if($counter < $numRows){
                                                        $query = $query . "\n(null, '$id_usuario', '$id', 'SI','SI','SI','SI','SI'),";
                                                    }else{
                                                        $query = $query . "\n(null, '$id_usuario', '$id', 'SI','SI','SI','SI','SI');";
                                                    }
                                                    
                                                 }   
                                                 
                                                //  echo $query;
                                                $resultPermisos=mysql_query($query);
                                                
                                                    // echo "</br>usuario registrado";
                                                        
                                                        
                                                        
                                                        
                                                        
                                                        
                                                        $correoEnviado = enviarCorreo($id_empresa, $nombre,$ruc,$cod_empresa);
                  
                                                        if($correoEnviado == "Mensaje enviado"){
                                                            echo "correo";
				                                        }else{
				                                            echo "correo no enviado";
                                                        }

        }
	  }    
            
            
            

      
    if($accion == 2){
        // CONSULTAR EMPRESA

        $sqle="Select * From empresa";
        $result=mysql_query($sqle);
        //$num_filas=0;
        while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
        {
            $cadena=$cadena."?".$row['id_empresa']."?".$row['ruc']."?".$row['nombre']."?".$row['direccion']."?".$row['pais']."?".$row['provincia']."?".$row['id_ciudad']."?".$row['telefono1']."?".$row['telefono2']."?".$row['email']."?".$row['pag_web']."?".$row['imagen']."?".$row['fecha_inicio']."?".$row['informacion_general']."?".$row['perfil_empresa']."?".$row['descripcion']."?".$row['mision']."?".$row['vision']."?".$row['id_usuario'];
          
        }
        echo "".$cadena;
    }

    if($accion == 3){

        $id_empresa = $_POST['txtIdEmpresa'];
        $nombre = ucwords($_POST['txtNombreEmpresa']);
        $razonSocial= ucwords($_POST['txtrazonSocial']);
        $direccion = ucwords($_POST['txtDireccion']);
        $ruc = $_POST['txtRuc'];
        $empresaCodigo = $_POST['empresaCodigo'];
        $telefono1 = $_POST['empresaTelefono1'];
        $telefono2 = $_POST['txtTelefono2'];
        $email = $_POST['txtEmail'];
        $web = $_POST['txtWeb'];
        $pais = $_POST['cbpais'];
        $provincia = $_POST['cbprovincia'];
        $id_ciudad = $_POST['cbciudad'];
        $imagen = $_POST['txtImagen'];
        $fecha_inicio = $_POST['txtFechaInicio'];
        $info_general = ucwords($_POST['txtInfGeneral']);
        $perfil_empresa = ucwords($_POST['txtPerfilEmpresa']);
        $descripcion = ucwords($_POST['txtDescripcion']);
        $mision = ucwords($_POST['txtMision']);
        $vision = ucwords($_POST['txtVision']);
        $clave = $_POST['txtClave'];
        
        
        $nombreContador=$_POST['nombreContador'];
        $obligado = $_POST['switch-three'];
        
        $leyenda = $_POST['switch-rimpe'];
        
        $leyenda2 = $_POST['txtLeyenda2'];
        $leyenda3 = $_POST['txtLeyenda3'];
        $leyenda4 = $_POST['txtLeyenda4'];
        $txtRucContador = $_POST['txtRucContador'];
        $txtRucRepresentanteLegal = $_POST['txtRucRepresentanteLegal'];

        $sqla="update empresa set nombre='".$nombre."' , razonSocial ='".$razonSocial."',ruc='".$ruc."' , direccion='".$direccion."', 
        codigo_empresa='".$empresaCodigo."' ,   Ocontabilidad  ='".$obligado."' , telefono1='".$telefono1."',
        leyenda ='".$leyenda."',leyenda2 ='".$leyenda2."',leyenda3 ='".$leyenda3."',leyenda4 ='".$leyenda4."',email ='".$email."' , nombreContador ='".$nombreContador."' ,ruc_contador='".$txtRucContador."',ruc_representante='".$txtRucRepresentanteLegal."'
        WHERE id_empresa='".$id_empresa."'; ";
        $resultaa=mysql_query($sqla);
        
//echo $sqla;

        if($resultaa){
            ?>1<?php
        }
        else{
            ?> <div class='transparent_ajax_error'><p>Error al guarda los datos: Revise que haya ingresado todos los datos correctamente. o Consulte con el Administrador</p></div> <?php echo $sqla;
        }
    }


if($accion == 4){
        // busca los empleados activos pagina: empresa.php

        if(isset($_POST['queryString'])) {
            $queryString = $_POST['queryString'];
            if(strlen($queryString) >0) {
                $query = "SELECT * FROM empleados WHERE (empleados.nombre LIKE '%$queryString%' OR empleados.apellido LIKE '%$queryString%') and empleados.estado='Activo' order by empleados.nombre asc LIMIT 10;";
                $result = mysql_query($query) or die(mysql_error());
                $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
                if($result) {
                    if($numero_filas == 0){
                            echo "<center><p><label> No hay resulados con el parámetro ingresado. </label></p></center>";
                     }else{
                        while ($row = mysql_fetch_assoc($result)) {
                            //$canti=$result->reservaciones_Cantidad;
                            //$cantidad=substr($canti,0,1);
                            echo '<li onClick="fill5(\''.$row["nombre"]." ".$row["apellido"].'\','.$row["id_empleado"].');">'.$row["nombre"]."   ".$row["apellido"].'</li>';
                        }
                    }

                } else {
                        echo 'ERROR: Hay un problema con la consulta.';
                }
            } else {
                echo 'La longitud no es la permitida.';
                    // Dont do anything.
            } // There is a queryString.
        } else {
            echo 'No hay ningún acceso directo a este script!';
        }

    }
      
        
        if($accion == 5){
            // VALIDACIONES PARA QUE EL RUC NO SE REPITA
            $ruc = $_POST['ruc'];
            //echo "".$cedula;
            $sql = "SELECT ruc from empresa where ruc='".$ruc."';";
            $resp = mysql_query($sql);
            $entro=0;
            while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
                      {
                          $var=$row["ruc"];
                      }
            if($var==$ruc){
                 if($var==""&&$ruc==""){
                       $entro=0;                    
                    }else{
                        $entro=1;
                    }
            }              
           echo $entro;
        }
        
        
        if($accion == 6){
            // levanta sessiones para direccionar al menu principal
            $id_empresa = $_POST['id_empresa'];
            $periodo = $_POST['periodo'];
            $empresa_nombre = $_POST['empresa_nombre'];
            
            $_SESSION["sesion_id_empresa"] = $id_empresa;
            $_SESSION["sesion_id_periodo_contable"] = $periodo;
            $_SESSION["sesion_empresa_nombre"] = $empresa_nombre;        
            echo "1";// dato sin ninguna funcion solo es para que retorne algo lo q sea cualquier cosa
        }

        if($accion == "7"){
        // ELIMINA EMPRESA PAGINA: Administrador.php

        if(isset ($_POST['id_empresa'])){
          $id_empresa = $_POST['id_empresa'];
          $sql4 = "delete from empresa where id_empresa='".$id_empresa."'; ";
          $resp4 = mysql_query($sql4);
          if(!mysql_query($sql4)){
              echo "Ocurrio un error: \n$sql4";
              }else{
                echo "La empresa ha sido Eliminada."; }
         }else {
              ?> <div class="transparent_ajax_error"><p>Error: No hay datos</p></div> <?php
              }


    }

    if($accion == "8"){
        $consulta8="SELECT * FROM tipo_empresas where tipo_empresa <> 'Super Administrador' and id_tipo_empresa>11 order by tipo_empresa asc;";
        $result8=mysql_query($consulta8);
        while($row8=mysql_fetch_array($result8))//permite ir de fila en fila de la tabla
            {
                $cadena8=$cadena8."?".$row8['id_tipo_empresa']."?".$row8['tipo_empresa'];
            }
        echo "".$cadena8;
    }

    if($accion == 9){
        // VALIDACIONES PARA QUE EL codigo de empresa NO SE REPITA
        $txtCodEmpresa = $_POST['txtCodEmpresa'];
        //echo "".$cedula;
        $sql9 = "SELECT codigo_empresa from empresa where codigo_empresa='".$txtCodEmpresa."'";
        $resp9 = mysql_query($sql9);
        $entro9=0;
        while($row9=mysql_fetch_array($resp9))//permite ir de fila en fila de la tabla
            {
                $var9=$row9["codigo_empresa"];
            }
        if($var9 == $txtCodEmpresa){
             if($var == "" && $txtCodEmpresa == ""){
                   $entro9=0;
                }else{
                    $entro9=1;
                }
        }
       echo $entro9;
    }


    if($accion == 10){
    
        $empresa= $_POST['id_empresa'];
        $limite = $_POST['limite'];
        $valor = $_POST['valor'];
        $url = $_POST['url'];
        $observacion = $_POST['observacion'];
        
        $planOriginal = $_POST['original'];
        
        $tipo = $_POST['tipo'];
   
      
        $sql= "UPDATE `empresa` SET `limiteFacturas`='$limite',distribuidor='$valor',url='$url',observacion='$observacion',planOriginal='$planOriginal',
        id_tipo_empresa='$tipo' WHERE id_empresa='$empresa' ";

    
        $resp = mysql_query($sql);
        echo  $respuesta= ($resp)?'1':'2';

    }

      if($accion ==11){

        $id_empresa = $_POST['txtIdEmpresa'];
        $condicionesPago = ucwords($_POST['condicionesPago']);
        $formaPago= ucwords($_POST['formaPago']);


        $sqla="update empresa set formaPago='".$formaPago."' , condicionesPago ='".$condicionesPago."'  WHERE id_empresa='".$id_empresa."'; ";
        $resultaa=mysql_query($sqla);


        if($resultaa){
            ?>1<?php
        }
        else{
            ?> <div class='transparent_ajax_error'><p>Error al guarda los datos: Revise que haya ingresado todos los datos correctamente. o Consulte con el Administrador</p></div> <?php echo $sqla;
        }
    }
    
    

    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error grave: <?php echo "".$e ?></p></div> <?php
    }
    
    
    if($accion == "12"){
        // echo "</br>accion".$accion;
// Inactivo/Activo EMPLEADOS CAMBIANDO EL ESTADO EN INACTIVO O ACTIVO PAGINA: empleados.php
    try
     {
        $id_emple=$_POST['id_empresa'];
        $sqlp="update empresa set estado='Inactivo' where id_empresa='".$id_emple."'; ";
        $result=mysql_query($sqlp);
        // echo $sqlp;
      if($result){
          ?> <div class='transparent_ajax_correcto'><p>empresa Suspendido.</p></div> <?php
          }
     else{
         ?> <div class='transparent_ajax_error'><p>Error al Inactivar empleado: problemas con la consulta <?php echo "\n".mysql_error(); ?></p></div> <?php
         }
    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
    }
}


if($accion == "13"){
// Inactivo/Activo EMPLEADOS CAMBIANDO EL ESTADO EN INACTIVO O ACTIVO PAGINA: empleados.php
    try
     {
        $id_emple=$_POST['id_empresa'];
        $sqlp="update empresa set estado='Activo' where id_empresa='".$id_emple."'; ";
        $result=mysql_query($sqlp);
      if($result){
          ?> <div class='transparent_ajax_correcto'><p>Empresa Activado.</p></div> <?php
          }
     else{
         ?> <div class='transparent_ajax_error'><p>Error al Activar empleado: problemas con la consulta <?php echo "\n".mysql_error(); ?></p></div> <?php
         }
    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
    }
}
    
        if($accion == "14"){
        // echo "</br>accion".$accion;
// Inactivo/Activo EMPLEADOS CAMBIANDO EL ESTADO EN INACTIVO O ACTIVO PAGINA: empleados.php
    try
     {
        $id_emple=$_POST['id_empresa'];
        $sqlp="update empresa set pago='SI' where id_empresa='".$id_emple."'; ";
        $result=mysql_query($sqlp);
        // echo $sqlp;
      if($result){
          ?> <div class='transparent_ajax_correcto'><p>Pago Registrado</p></div> <?php
          }
     else{
         ?> <div class='transparent_ajax_error'><p>Error al Inactivar empleado: problemas con la consulta <?php echo "\n".mysql_error(); ?></p></div> <?php
         }
    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
    }
}


if($accion == "15"){
// Inactivo/Activo EMPLEADOS CAMBIANDO EL ESTADO EN INACTIVO O ACTIVO PAGINA: empleados.php
    try
     {
        $id_emple=$_POST['id_empresa'];
        $sqlp="update empresa set pago='NO' where id_empresa='".$id_emple."'; ";
        $result=mysql_query($sqlp);
      if($result){
          ?> <div class='transparent_ajax_correcto'><p>Pago No Registrado.</p></div> <?php
          }
     else{
         ?> <div class='transparent_ajax_error'><p>Error al Activar empleado: problemas con la consulta <?php echo "\n".mysql_error(); ?></p></div> <?php
         }
    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
    }
}
    //   if($accion == 16){
    
    //     $empresa= $_POST['id_empresa'];
    //     $limite = $_POST['limite'];

    //     $sql= "UPDATE `empresa` SET `limiteFacturas`='$limite'where id_empresa='".$empresa."'";
    //     $resp = mysql_query($sql);
    //     echo  $respuesta= ($resp)?'1':'2';

    // }
    
if($accion == 16){
    
        $empresa= $_POST['id_empresa'];
        $limite = $_POST['limite'];

        $sql= "UPDATE `empresa` SET `limiteFacturas`='$limite'where id_empresa='".$empresa."'";
        $resp = mysql_query($sql);
        echo  $respuesta= ($resp)?'1':'2';

        
        $fecha_actual = date('Y-m-d'); // Obtiene la fecha actual en formato "YYYY-MM-DD"
        $fecha_nueva = date('Y-m-d', strtotime($fecha_actual . ' +1 year')); // Suma 1 año a la fecha actual

        $sqlRenovaciones="SELECT `id_renovacion`, `fecha_registro`, `fecha_renovacion`, `id_empresa` FROM `renovaciones` WHERE id_empresa=".$empresa;
        $resultRenovaciones = mysql_query($sqlRenovaciones);
        $numFilas = mysql_num_rows($resultRenovaciones);
        if( $numFilas==0){
            $sqlUpdateRenovaciones="INSERT INTO `renovaciones`(`fecha_registro`, `fecha_renovacion`, `id_empresa`) VALUES ('".$fecha_actual."','".$fecha_nueva."',$empresa)";
        }else{
            $sqlUpdateRenovaciones="UPDATE `renovaciones` SET `fecha_renovacion`='".$fecha_nueva."' WHERE id_empresa=".$empresa;
        }
 
        $resultUpdateRenovaciones = mysql_query($sqlUpdateRenovaciones);

    }
if($accion == 20) {

        $id=$_POST['id'];
        $sqlRenovaciones="SELECT `id_renovacion`, `fecha_registro`, `fecha_renovacion`, `id_empresa` FROM `renovaciones` WHERE id_renovacion=".$id;
        $resultRenovaciones = mysql_query($sqlRenovaciones);

        while($rowRen = mysql_fetch_array($resultRenovaciones)){
            $fechaRenovacion = $rowRen['fecha_renovacion'];
          

            // $fecha_objeto = new DateTime($fechaRenovacion);
            // $fecha_objeto->modify('+1 year');
            // $nueva_fecha = $fecha_objeto->format('Y-m-d'); 
              $nueva_fecha = $_POST['fecha_renovacion'];
            $sqlActualizar= "UPDATE `renovaciones` SET `fecha_renovacion`='".$nueva_fecha."'  WHERE id_renovacion=$id";
            $resultActualizar = mysql_query($sqlActualizar);
            if($resultActualizar){
                echo '1';
            }else{
                echo '2';
            }
        }


    }
    
    // if($accion == 17){
    // // echo "accion".$accion;
    //  $sqlEmpresa="SELECT `id_empresa`, `planOriginal` FROM `empresa`  ";
    //                                 $resultemmpresa = mysql_query($sqlEmpresa);
    //                                 $planOriginal=0;
    //                                 while ($rowEmpresa = mysql_fetch_assoc($resultemmpresa)) {
    //                                         $planOriginal= $rowEmpresa['planOriginal'];
    //                                         $idEmpresa = $rowEmpresa['id_empresa'];
    //                                         $consultaVentas = "Select COUNT(id_venta) as ventas, id_empresa From ventas WHERE id_empresa=$idEmpresa ;";
    //                                         $resultadoVentas = mysql_query($consultaVentas);
    //                                         $cantidadVentas=0;
    //                                           while ($rowVentas = mysql_fetch_assoc($resultadoVentas)) {
    //                                               $cantidadVentas = $rowVentas['ventas'];
    //                                           }
    //                                           if($planOriginal==''){
    //                                               $limite=0;
    //                                               $planOriginal=0;
    //                                           }else{
    //                                               $limite = $planOriginal-$cantidadVentas;
                                                   
    //                                           }
                                   
                                     
    //                                   echo  $sqlActualizar= "UPDATE `empresa` SET `limiteFacturas`='$limite', planOriginal='$planOriginal'  where id_empresa='".$idEmpresa."'";
    //                                  $respActualizar = mysql_query($sqlActualizar);
    //                                  echo  $respuesta= ($respActualizar)?'1':'2';
    //                                 }    
                           

    // }

    if($accion == 25) {
        $id_empresa = $_POST['id_empresa'];
        $tipo = $_POST['tipo'];

        $sql="UPDATE `empresa` SET `id_tipo_empresa`='".$tipo."'  WHERE id_empresa=$id_empresa   ";
        $result = mysql_query($sql);
        if($result){
            echo '1';
        }else{
            echo '2';
        }
    }
?>