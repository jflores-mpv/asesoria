<?php
error_reporting(0);
    //require_once('../ver_sesion.php');

    //Start session
session_start();

    //Include database connection details
require_once('../conexion.php');

$sesion_id_empresa = $_SESSION["sesion_id_empresa"];

$accion=$_POST['txtAccion'];



require_once('../clases/contrasenas.php');
require_once('encriptar.php');
if($accion == "1"){
 // GUARDAR USUARIOS PAGINA: usuarios.php
 try
 {        

    if(isset ($_POST['login'])){
      $login = $_POST['login'];



      $sql="SELECT usuarios.`id_usuario` AS usuarios_id_usuario, usuarios.`id_empleado` AS usuarios_id_empleado, usuarios.`login` AS usuarios_login, usuarios.`estado` AS usuarios_estado, usuarios.`tipo` AS usuarios_tipo FROM usuarios

      WHERE usuarios.`login` ='".$login."' and usuarios.`id_empresa` ='".$sesion_id_empresa."'";
//          echo " consulta ".$sql;
      $resp = mysql_query($sql);
      $entro=0;
          while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
          {
            $var1=$row["usuarios_login"];

        }
        
        $login = strtolower($login);
        
        if($var1==$login){
         $entro=1;
         if($var1==""&&$login==""){
            $entro=0;
        }
    }

}

if($entro==0){

    $cmbEmpleados=$_POST['cmbEmpleados'];
    $txtLogin=$_POST['txtLogin'];

    if($cmbEmpleados != "" & $txtLogin != ""){

        $cmbEmpleados=$_POST['cmbEmpleados'];
        $cmbPermisos=$_POST['cmbPermisos'];
        $cmbTipo=$_POST['cmbTipo'];
        $cmbEstado=$_POST['cmbEstado'];
        $fecha_registro= date("Y-m-d H:i:s");
        $txtLogin=$_POST['txtLogin'];
        $txtPassword= ($_POST['txtPassword']);
        $txtEncriptado = ($txtPassword);
        
        $claveEncriptada= $encriptar($txtPassword);

        $sqlp="insert into usuarios( id_empleado,id_empresa, login, password, tipo, estado, fecha_registro, permisos,reportes_contables)
        values ('".$cmbEmpleados."','".$sesion_id_empresa."','".$txtLogin."','".$claveEncriptada."','".$cmbTipo."','".$cmbEstado."','".$fecha_registro."','".$cmbPermisos."','Si')";

        $result=mysql_query($sqlp);
        $id_usuario=mysql_insert_id();

        if($result){

            $sqlModulos="Select id From modulos ;";
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

            $query;
            $resultPermisos=mysql_query($query);


        }
        else{ ?> <div class='transparent_ajax_error'><p>Error al guarda los datos: problemas con la consulta <?php echo "\n".mysql_error(); ?></p></div> 

    <?php  }   } else{
      ?> <div class="transparent_ajax_error"><p>Fallo en el envio del Formulario: No hay datos</p></div> <?php

  }
  echo $entro;
}else{
    echo $entro;
}


}catch (Exception $e) {
    // Error en algun momento.
   ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
}
}


if($accion == "2"){
// GUARDAR MODIFICACION USUARIOS PAGINA: usuarios.php
    try
    {
        $txtIdUsuario=$_POST['txtIdUsuario'];
        $cmbEmpleados=$_POST['cmbEmpleados'];
        if($txtIdUsuario != "" & $cmbEmpleados != ""){

            $cmbEmpleados=$_POST['cmbEmpleados'];
            $cmbPermisos=$_POST['cmbPermisos'];
            $cmbTipo=$_POST['cmbTipo'];
            $cmbEstado=$_POST['cmbEstado'];
            //$fecha_registro= date("Y-m-d");
            $txtLogin=$_POST['txtLogin'];
            $txtPassword=($_POST['txtPassword']);
            
            $txtEncriptado = encriptar($txtPassword);
            
            $claveEncriptada= $encriptar($txtPassword);
            $sqlp="update usuarios set  id_empleado='".$cmbEmpleados."',login='".$txtLogin."', 
            password='".$claveEncriptada."', tipo='".$cmbTipo."', estado='".$cmbEstado."', permisos='".$cmbPermisos."' where id_usuario='".$txtIdUsuario."'; ";

            $result=mysql_query($sqlp);

            if($result){
              ?> <div class='transparent_ajax_correcto'><p>Registro actualizado correctamente.</p></div> <?php
          }
          else{
             ?> <div class='transparent_ajax_error'><p>Error al guarda los datos: problemas con la consulta <?php echo "\n".mysql_error(); ?></p></div> <?php
         }

     }else{
      ?> <div class="transparent_ajax_error"><p>Fallo en el envio del Formulario: No hay datos</p></div> <?php
  }

}catch (Exception $e) {
    // Error en algun momento.
   ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
}

}


if($accion == "3"){
// Inactivo/Activo USUARIOS CAMBIANDO EL ESTADO EN INACTIVO O ACTIVO PAGINA: usuarios.php
    try
    {
        $id_usuario=$_POST['id_usuario'];
        $nombre=$_POST['nombre'];
        $sqlp="update usuarios set estado='Inactivo' where id_usuario='".$id_usuario."'; ";
        $result=mysql_query($sqlp);
        if($result){
          ?> <div class='transparent_ajax_correcto'><p>Usuario <?php echo $nombre; ?> Suspendido.</p></div> <?php
      }
      else{
         ?> <div class='transparent_ajax_error'><p>Error al Inactivar usuario: problemas con la consulta <?php echo "\n".mysql_error(); ?></p></div> <?php
     }
 }catch (Exception $e) {
    // Error en algun momento.
   ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
}
}


if($accion == "4"){
// Inactivo/Activo usuarios CAMBIANDO EL ESTADO EN INACTIVO O ACTIVO PAGINA: usuarios.php
    try
    {
        $id_usuario=$_POST['id_usuario'];
        $nombre=$_POST['nombre'];
        $sqlp="update usuarios set estado='Activo' where id_usuario='".$id_usuario."'; ";
        $result=mysql_query($sqlp);
        if($result){
          ?> <div class='transparent_ajax_correcto'><p>Usuario <?php echo $nombre; ?> Activado.</p></div> <?php
      }
      else{
         ?> <div class='transparent_ajax_error'><p>Error al Activar usuario: problemas con la consulta <?php echo "\n".mysql_error(); ?></p></div> <?php
     }
 }catch (Exception $e) {
    // Error en algun momento.
   ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
}
}




if($accion == "5"){
    //muestra todos los empleados activos
   $cadena="";
   //consulta
   try {
       $consulta5="SELECT * FROM empleados where estado='Activo' and id_empresa='".$sesion_id_empresa."';";
       $result=mysql_query($consulta5);
        while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
        {
            $cadena=$cadena."?".$row['id_empleado']."?".$row['nombre']." ".$row['apellido'];
        }
        echo "".$cadena;

    }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
}

if($accion == "6"){    
    // ELIMINA USUARIOS PAGINA: usuarios.php
 try
 {
    if(isset ($_POST['id_usuario'])){
      $id_usuario = $_POST['id_usuario'];
      $sql4 = "delete from usuarios where id_usuario='".$id_usuario."'; ";
      $resp4 = mysql_query($sql4);
      if(!mysql_query($sql4)){
          echo "Ocurrio un error\n$sql4";
      }else{
        echo "El registro ha sido Eliminado."; }
    }else {
      ?> <div class="transparent_ajax_error"><p>Error: No hay datos</p></div> <?php
  }

}catch (Exception $e) {
    // Error en algun momento.
   ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
}

}

if($accion == "7"){
// VALIDACIONES PARA QUE EL LOGIN NO SE REPITA
 try
 {
     if(isset ($_POST['login'])){
      $login = $_POST['login'];



      $sql="SELECT usuarios.`id_usuario` AS usuarios_id_usuario, usuarios.`id_empleado` AS usuarios_id_empleado, usuarios.`login` AS usuarios_login, usuarios.`estado` AS usuarios_estado, usuarios.`tipo` AS usuarios_tipo FROM usuarios

      WHERE usuarios.`login` ='".$login."' and usuarios.`id_empresa` ='".$sesion_id_empresa."'";
//          echo " consulta ".$sql;
      $resp = mysql_query($sql);
      $entro=0;
          while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
          {
            $var1=$row["usuarios_login"];

        }
        
        $login = strtolower($login);
        
        if($var1==$login){
         $entro=1;
         if($var1==""&&$login==""){
            $entro=0;
        }
    }
    echo $entro;
}

}catch (Exception $e) {
    // Error en algun momento.
   ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
}

}

if($accion == "8"){
// GUARDA MI PERFIL EN LA TABLA EMPLEADOS Y USUARIOS PAG: ajax/modificarUsuarioEmpresa.php
 try
 {
     $txtIdEmpleado = $_POST['txtIdEmpleado'];
     $txtIdUsuario = $_POST['txtIdUsuario'];
         //empleados
     $nombre=ucwords($_POST['txtNombre']);
     $apellido=ucwords($_POST['txtApellido']);
     $cmbPermisos=($_POST['cmbPermisos']);
     $cmbTipo=($_POST['cmbTipo']);
     $cmbEstado=($_POST['cmbEstado']);

     $txtIdPermisoAsientoContable=($_POST['txtIdPermisoAsientoContable']);
     $txtIdPermisoPlanCuenta=($_POST['txtIdPermisoPlanCuenta']);
     $txtIdPermisoBancos=($_POST['txtIdPermisoBancos']);


     $txtLogin=$_POST['txtLogin'];
     $txtPassword=($_POST['txtPassword']);
 //   $txtEncriptado = encriptar($txtPassword);
            $claveEncriptada= $encriptar($txtPassword);

     $cmbEmi=$_POST['cmbEmi'];
     $id_est=$_POST['cmbEst'];
     
     
     $user_hashed_password = private_hash($txtPassword);
     $sql2="update usuarios set login='".$txtLogin."', password='".$claveEncriptada."', tipo='".$cmbTipo."', 
     estado='".$cmbEstado."', permisos='".$cmbPermisos."', 
     id_permiso_asiento_contable='0', id_permiso_plan_cuenta='0', reportes_contables='0',
     id_permisos_bancos='0',id_punto='".$cmbEmi."',id_est='".$id_est."'  where id_usuario='".$txtIdUsuario."'; "; 
//echo $sql2;
     $resp2 = mysql_query($sql2); 

     $txtEncriptado = ($txtPassword);

     $sql1="update empleados set  nombre='".$nombre."', apellido='".$apellido."', tipo='".$cmbTipo."', estado='".$cmbEstado."' 
     where id_empleado='".$txtIdEmpleado."'; ";
     $resp1 = mysql_query($sql1);
     
     
        $nombreSocio=$_POST['nombreSocio'];
        $rucSocio=$_POST['rucSocio'];
        $placaSocio=$_POST['placaSocio'];
        $regimenSocio=$_POST['regimenSocio'];
        
    if($nombreSocio!=""){
        
        
            $sqlEmiEst = "SELECT establecimientos.codigo as idEst, emision.codigo as emisionId from emision,establecimientos 
             where establecimientos.id='".$id_est."' and emision.id= '".$cmbEmi."'";
            // echo $sql1;
            $sqlEmiEst = mysql_query($sqlEmiEst);
            while($sqlEmiEst=mysql_fetch_array($sqlEmiEst))
            {
                $regimenEst=$sqlEmiEst['idEst'];
                $regimenEmision=$sqlEmiEst['emisionId'];
            }
        
        $sqlTransportistaConsulta = "SELECT * from transportista where id_empresa ='".$sesion_id_empresa."' and cedula='".$rucSocio."' ";

		$sqlTransportistaConsulta = mysql_query($sqlTransportistaConsulta);
		$sqlTransportistaConsultaNum=mysql_num_rows($sqlTransportistaConsulta);
		while($sqlTransportistaConsulta=mysql_fetch_array($sqlTransportistaConsulta)){
		        $id_transportista = $sqlTransportistaConsulta['Id'];
		        
		    }
		    
		if ($sqlTransportistaConsultaNum>0)
		{	
		    
		    $sqltransportista = "UPDATE `transportista` SET `Nombres`='".$nombreSocio."',`Cedula`='".$rucSocio."',`Placa`='".$placaSocio."',
		    `regimen`='".$regimenSocio."',`emision`='".$regimenEmision."',`est`='".$regimenEst."' WHERE Id='".$id_transportista."' ";
            $resultTransportista=mysql_query($sqltransportista) or die(mysql_error());
		    
		    
		} 
	else{
	        $sqltransportista = "insert into transportista( Nombres, Cedula, placa,id_empresa,regimen,emision,est)
            values ('".$nombreSocio."','".$rucSocio."', '".$placaSocio."', '".$sesion_id_empresa."','".$regimenSocio."','".$regimenEmision."','".$regimenEst."');";
            $resultTransportista=mysql_query($sqltransportista) or die(mysql_error());
            $id_transportista=mysql_insert_id();
	}
            
            $sql1 = "SELECT SOCIO from emision where id='".$cmbEmi."' ";
            $resultado = mysql_query($sql1);
            while($rowsOCIO=mysql_fetch_array($resultado))
            {
                 $sql11="update emision set  SOCIO='".$id_transportista."' where id='".$cmbEmi."'; ";
                 $resp1 = mysql_query($sql11);
             
            }
    }
  
    

     
     

     if($resp1 && $resp2){   


      
           $sqlElminarPermisos="DELETE FROM `permisos_usuarios` WHERE `id_usuario`=$txtIdUsuario";
        $resultEliminarPermisos= mysql_query($sqlElminarPermisos);


            $idsModulos=$_POST['idModulo'];
            $length = count($idsModulos);
            $query = "";
            $acciones = ["mostrar", "guardar", "editar","eliminar","reportes"];

            for($i = 0; $i < $length; $i++){
            //mostrar, grabar, editar,eliminar,reportes
                $id = $idsModulos[$i];


                $permisos = $_POST['permiso'][$id];
              //  var_dump ($permisos);
                $query = "INSERT INTO `permisos_usuarios`( `id_usuario`, `id_modulo`, `mostrar`, `guardar`, `editar`, `eliminar`, `reportes`) VALUES ('".$txtIdUsuario."', '".$id."',";
                for($j = 0; $j < count($acciones); $j++){
                    $permitido = $permisos[$j] == 'on' ? 'SI':'NO';
                    if($j <count($acciones) - 1){
                        $query = $query . "  '$permitido', ";

                    }else{
                        $query = $query . " '$permitido' );";

                    }

                }
           
                $updateResult = mysql_query($query);
          
               

            }

        if($txtIdPermisoAsientoContable == 0){
          $sqlPermisosAsientosContables = "insert into permisos_asientos_contables( guardar, modificar, eliminar, id_usuario)
          values ('Si', 'Si', 'Si', '".$txtIdUsuario."');";
          $result3=mysql_query($sqlPermisosAsientosContables) or die(mysql_error());

      }else{
       $sqlPermisosAsientosContables = "update permisos_asientos_contables set guardar = 'Si', modificar = 'Si', eliminar = 'Si'
       where id_permiso_asiento_contable = '".$txtIdPermisoAsientoContable."';";
       $result3=mysql_query($sqlPermisosAsientosContables) or die(mysql_error());
   }


                //permisos_plan_cuentas
   if($txtIdPermisoPlanCuenta == 0){
    $sqlPermisosPlanCuentas = "insert into permisos_plan_cuentas( guardar, modificar, eliminar, id_usuario)
    values ('Si', 'Si', 'Si', '".$txtIdUsuario."');";
    $result4=mysql_query($sqlPermisosPlanCuentas) or die(mysql_error());
}else{
    $sqlPermisosPlanCuentas = "update permisos_plan_cuentas set guardar='Si', modificar='Si', eliminar='Si' where id_permiso_plan_cuenta = '".$txtIdPermisoPlanCuenta."' ;";
    $result4=mysql_query($sqlPermisosPlanCuentas) or die(mysql_error());
}

            // PERMISOS BANCOS

                //permisos_bancos
if($txtIdPermisoBancos == 0){
    $sqlPermisosBancos = "insert into permisos_bancos ( guardar, modificar, eliminar, id_usuario)
    values ('Si', 'Si', 'Si', '".$txtIdUsuario."');";
    $result5=mysql_query($sqlPermisosBancos) or die(mysql_error());
}else{
    $sqlPermisosBancos = "update permisos_bancos set guardar='Si', modificar='Si', eliminar='Si' where id_permisos_bancos = '".$txtIdPermisoBancos."' ;";
    $result5=mysql_query($sqlPermisosBancos) or die(mysql_error());
}




if ($query){
    echo "1";
}



}

else{
 ?> <div class='transparent_ajax_error'><p>Error al guarda los datos: problemas con la consulta <?php echo mysql_error(); ?></p></div> <?php
}


}catch (Exception $e) {
    // Error en algun momento.
   ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
}

}

if($accion == 9){

    try {
    // esta pagina retorna en la pagina: js/busquedas.js y luego en la pagina administrarEmpresa.php
        $contador = 1;
        $query9 = "SELECT
        usuarios.`id_usuario` AS usuarios_id_usuario,
        usuarios.`id_empleado` AS usuarios_id_empleado,
        usuarios.`login` AS usuarios_login,
        usuarios.`password` AS usuarios_password,
        usuarios.`tipo` AS usuarios_tipo,
        usuarios.`estado` AS usuarios_estado,
        usuarios.`fecha_registro` AS usuarios_fecha_registro,
        usuarios.`permisos` AS usuarios_permisos,
        usuarios.`clave_administrador` AS usuarios_clave_administrador,
        usuarios.`clave_modulos` AS usuarios_clave_modulos,
        usuarios.`asientos_contables` AS usuarios_asientos_contables,
        usuarios.`plan_cuentas` AS usuarios_plan_cuentas,
        usuarios.`reportes_contables` AS usuarios_reportes_contables,
        empleados.`id_empleado` AS empleados_id_empleado,
        empleados.`nombre` AS empleados_nombre,
        empleados.`id_empresa` AS empleados_id_empresa,
        empleados.`estado` AS empleados_estado,
        empleados.`apellido` AS empleados_apellido,
        empleados.`tipo` AS empleados_tipo
        FROM
        `empleados` empleados INNER JOIN `usuarios` usuarios ON empleados.`id_empleado` = usuarios.`id_empleado`

        WHERE empleados.`id_empresa`='".$sesion_id_empresa."' order by empleados.`id_empleado` asc; ";

        $result9 = mysql_query($query9) or die(mysql_error());
            $numero_filas9 = mysql_num_rows($result9); // obtenemos el número de filas
            if($result9) {
                if($numero_filas9 == 0){
                    //echo "nohayresultados";
                        //echo "<center><p><label> No hay resulados con el parámetro ingresado. </label></p></center>";
                }else{
                 $cadena9 = "";
                 while ($row9 = mysql_fetch_assoc($result9)) {

                    $cadena9=$cadena9."*".$contador."?".$row9['usuarios_id_usuario']."?".$row9['empleados_id_empleado']."?".$row9['usuarios_login']."?".$row9['usuarios_password']."?".$row9['empleados_nombre']."?".$row9['usuarios_tipo']."?".$row9['usuarios_estado']."?".$row9['usuarios_asientos_contables']."?".$row9['usuarios_plan_cuentas']."?".$row9['usuarios_reportes_contables'];
                    $contador ++;
                }
                echo "".$cadena9;
            }

        } else {
            echo 'ERROR: Hay un problema con la consulta.';
        }


    }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
}
?>