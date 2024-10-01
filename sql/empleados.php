<?php

    //require_once('../ver_sesion.php');
error_reporting(0);
    //Start session
session_start();

    //Include database connection details
require_once('../conexion.php');

$accion=$_POST['txtAccion'];
$sesion_id_empresa = $_SESSION["sesion_id_empresa"];

date_default_timezone_set('America/Guayaquil');

require_once('../clases/contrasenas.php');


if($accion == "1"){
 // GUARDAR EMPLEADOS PAGINA: registro.php empleados.php
 try
 {        
   $nombre=($_POST['txtNombre']!='')?$_POST['txtNombre']:'indefinido';
   $cedula=($_POST['txtCedula']!='')?$_POST['txtCedula']:'0';
   if($cedula != "" & $nombre != ""){

    $apellido=($_POST['txtApellido']!='')?$_POST['txtApellido']:'indefinido';
    $nacionalidad=($_POST['cbnacionalidad']!='')?$_POST['cbnacionalidad']:'indefinido';
    $fechaNacimiento=($_POST['txtFechaNacimiento']!='')?$_POST['txtFechaNacimiento']:date('Y-m-d');
    $lugarNacimiento=($_POST['lugarNacimiento']!='')?$_POST['lugarNacimiento']:'indefinido';
    $pais=($_POST['cbpais']!='')?$_POST['cbpais']:'0';
    $ciudad=($_POST['cbciudad']!='')?$_POST['cbciudad']:'0';
    $radio_discapacidad=($_POST['radio-discapacidad']!='')?$_POST['radio-discapacidad']:'NO';
    $porcentajeDiscapacidad=($_POST['porcentajeDiscapacidad']!='')?$_POST['porcentajeDiscapacidad']:'0';
    $radio_genero=($_POST['radio-genero']!='')?$_POST['radio-genero']:'M';
    $radio_sangre=($_POST['radio-tSangre']!='')?$_POST['radio-tSangre']:'indefinido';
    $licencia=($_POST['licencia']!='')?$_POST['licencia']:'indefinido';
    $tipo=($_POST['cmbTipo']!='')?$_POST['cmbTipo']:'indefinido';

    $radio_estadoCivil=($_POST['cmbEstadoCivil']!='')?$_POST['cmbEstadoCivil']:'indefinido';
    $otrosEstadoCivil=($_POST['otrosEstadoCivil']!='')?$_POST['otrosEstadoCivil']:$radio_estadoCivil;

    $email=($_POST['txtEmail']!='')?$_POST['txtEmail']:'indefinido';
    $viveCon=($_POST['radio-viveCon']!='')?$_POST['radio-viveCon']:'Solo';
    $radio_medicinas=($_POST['radio-medicinas']!='')?$_POST['radio-medicinas']:'NO';
    $causaMedicina=($_POST['causaMedicina']!='')?$_POST['causaMedicina']:'indefinido';
    $radio_alergias=($_POST['radio-alergias']!='')?$_POST['radio-alergias']:'NO';
    $tipoAlergia=($_POST['tipoAlergia']!='')?$_POST['tipoAlergia']:'indefinido';
    $radio_dolencias=($_POST['radio-dolencias']!='')?$_POST['radio-dolencias']:'NO';
    $dolencias=($_POST['dolencia']!='')?$_POST['dolencia']:'NO';
    $radio_cirugias=($_POST['radio-cirugias']!='')?$_POST['radio-cirugias']:'NO';
    $cirugias=($_POST['cirugias']!='')?$_POST['cirugias']:'indefinido';
    $medicoPersonal=($_POST['medicoPersonal']!='')?$_POST['medicoPersonal']:'indefinido';

    $telefonoMedico=($_POST['telefonoMedico']!='')?$_POST['telefonoMedico']:'0';
    $radio_ligada=($_POST['radio-ligada']!='')?$_POST['radio-ligada']:'NO';
    $callePrincipal=($_POST['callePrincipal']!='')?$_POST['callePrincipal']:'Indefinido';
    $calleSecundaria=($_POST['calleSecundaria']!='')?$_POST['calleSecundaria']:'Indefinido';
    $casaNumeracion=($_POST['casaNumeracion']!='')?$_POST['casaNumeracion']:'indefinido';
    $departamentoNumeracion=($_POST['departamentoNumeracion']!='')?$_POST['departamentoNumeracion']:'indefinido';
    $sector=($_POST['sector']!='')?$_POST['sector']:'indefinido';
    $parroquia=($_POST['parroquia']!='')?$_POST['parroquia']:'indefinido';
    $cbciudad2=($_POST['cbciudad2']!='')?$_POST['cbciudad2']:'0';
    $radio_viveEn=($_POST['radio-viveEn']!='')?$_POST['radio-viveEn']:'indefinido';


    $radio_viviendaDispone=($_POST['radio-viviendaDispone']!='')?$_POST['radio-viviendaDispone']:'0';
    

    $otrosVivienda=($_POST['otrosVivienda']!='')?$_POST['otrosVivienda']:$radio_viviendaDispone;
    $referencia=($_POST['referencia']!='')?$_POST['referencia']:'Indefinido';
    $telefonoConvencional=($_POST['telefonoConvencional']!='')?$_POST['telefonoConvencional']:'0';
    $celular1=($_POST['celular1']!='')?$_POST['celular1']:'0';
    $operadora1=($_POST['operadora1']!='')?$_POST['operadora1']:'indefinido';
    $celular2=($_POST['celular2']!='')?$_POST['celular2']:'0';
    $operadora2=($_POST['operadora2']!='')?$_POST['operadora2']:'indefinido';
    $celular3=($_POST['celular3']!='')?$_POST['celular3']:'0';
    $operadora3=($_POST['operadora3']!='')?$_POST['operadora3']:'indefinido';

    $facebook=($_POST['facebook']!='')?$_POST['facebook']:'indefinido';
    $twitter=($_POST['Twitter']!='')?$_POST['Twitter']:'indefinido';
    $instagram=($_POST['Instagram']!='')?$_POST['Instagram']:'indefinido';
    $youtube=($_POST['youtube']!='')?$_POST['youtube']:'indefinido';
    $tikTok=($_POST['tikTok']!='')?$_POST['tikTok']:'indefinido';
    $Linkedln=($_POST['Linkedln']!='')?$_POST['Linkedln']:'indefinido';

    $hoy= Date('Y-m-d');

    $fichero = $_FILES["file"];
    $foto= ($fichero["name"]!='')?$fichero["name"]:'blanco.png';

    $path = "subidas/empleados/".$cedula;

    if (!is_dir($path)) {
      mkdir($path, 0777, true);
    }

    $searchString = " ";
    $replaceString = "";

    
    $foto = str_replace($searchString, $replaceString,$fichero["name"] ); 
  //ech

// Cargando el fichero en la carpeta "subidas"
    move_uploaded_file($fichero["tmp_name"], "subidas/empleados/".$cedula."/".$foto);

    echo $sqlp="INSERT INTO `empleados`( `nombre`, `apellido`, `cedula`, `direccion`, `telefono`, `movil`, `fecha_nacimiento`, `email`, `tipo`, `estado`, `id_ciudad`, `fecha_registro`, `numero_cargas`, `estado_civil`, `posicion`, `cuenta_bancaria`, `nacionalidad`, `discapacidad`, `porcentaje_discapacidad`, `genero`, `tipo_sangre`, `licencia_conducir`, `foto`, `vive_con`, `toma_medicinas`, `causa_medicina`, `tiene_alergia`, `tipo_alergia`, `tiene_dolencia`, `tipo_dolencia`, `tiene_cirugias`, `tipos_cirugias`, `medico_personal`, `telefono_medico`, `mujer_ligada`, `calle_principal`, `calle_secundaria`, `casa_numeracion`, `departamento_numeracion`, `sector`, `parroquia`, `ciudad_domicilio`, `vive_en`, `vivienda_noDispone`, `referencia_domicilio`, `celular1`, `operadora1`, `celular2`, `operadora2`, `celular3`, `operadora3`, `facebook`, `twitter`, `instagram`, `youtube`, `tiktok`, `linkedln`, `fondos_reserva`, `alimentacion`, `vacaciones`, `otros_ingresos`, `prestamos_iess`, `impuesto_renta`, `retencion_judicial`, `asociacion`, `otros_descuentos`, `decimos`, `id_empresa`, `fecha_ingreso`) VALUES ('$nombre','$apellido','$cedula','$callePrincipal','$celular1','$celular1','$fechaNacimiento','$email','$tipo','Activo','$ciudad','$hoy','0','$otrosEstadoCivil','0','0','$nacionalidad', '$radio_discapacidad','$porcentajeDiscapacidad',  '$radio_genero',  '$radio_sangre',  '$licencia','$foto','$viveCon', '$radio_medicinas' ,'$causaMedicina' ,'$radio_alergias','$tipoAlergia', '$radio_dolencias','$dolencias','$radio_cirugias','$cirugias','$medicoPersonal',  '$telefonoMedico',  '$radio_ligada', '$callePrincipal' ,'$calleSecundaria',  '$casaNumeracion','$departamentoNumeracion','$sector','$parroquia',  '$cbciudad2','$radio_viveEn','$otrosVivienda', '$referencia' ,'$celular1'  ,'$operadora1','$celular2','$operadora2','$celular3','$operadora3','$facebook','$twitter','$instagram', '$youtube','$tikTok','$Linkedln','0','0','0','0','0','0','0','0','0','0','$sesion_id_empresa','$hoy')";

    $result=mysql_query($sqlp);

    if($result){
      echo 'Se ingreso el empleado correctamente.';

      $id_empleado=mysql_insert_id();
      // variables datos familiares 

      
      if($_POST['txtNumeroFilaFamiliares']>0){
        $cFamiliares=$_POST['txtNumeroFilaFamiliares'];
        for($i=1;$i<=$cFamiliares;$i++){
          $parentesco=($_POST['parentesco'.$i]!='')?$_POST['parentesco'.$i]:'indefinido';
          $apellidoFamiliar=($_POST['apellidos'.$i]!='')?$_POST['apellidos'.$i]:'indefinido';
          $nombresFamiliar=($_POST['nombres'.$i]!='')?$_POST['nombres'.$i]:'indefinido';
          $fechaFamiliar=($_POST['fechaDeNacimiento'.$i]!='')?$_POST['fechaDeNacimiento'.$i]:'0000-00-00';
          $cedulaFamiliar=($_POST['cedula'.$i]!='')?$_POST['cedula'.$i]:'0';
          $direccionFamiliar=($_POST['domicilio'.$i]!='')?$_POST['domicilio'.$i]:'indefinido';

          $vivenConUsted=($_POST['radio-vivenConEmpleado'.$i]!='')?$_POST['radio-vivenConEmpleado'.$i]:'0';
          $porcentajeDiscapacidad=($_POST['porcentajeDiscapacidad'.$i]!='')?$_POST['porcentajeDiscapacidad'.$i]:'indefinido';
          $tipoDiscapacidad=($_POST['tipoDiscapacidad'.$i]!='')?$_POST['tipoDiscapacidad'.$i]:'0';
          
          echo $sqlDatosFamiliares="INSERT INTO `datos_familiares`( `parentesco`, `apellidos`, `nombres`, `fecha_nacimiento`, `cedula`, `direccion`, `id_empleado`,`vivenConEmpleado`,`porcentajeDiscapacidad`,`tipoDiscapacidad`) VALUES ('$parentesco','$apellidoFamiliar','$nombresFamiliar','$fechaFamiliar','$cedulaFamiliar','$direccionFamiliar','$id_empleado','$vivenConUsted','$porcentajeDiscapacidad','$tipoDiscapacidad')";
          $resultFamiliares=mysql_query($sqlDatosFamiliares);
        }
      }
      if($_POST['txtNumeroFilaInstruccion']>0){
        $cInstruccion=$_POST['txtNumeroFilaInstruccion'];
        for($k=1;$k<=$cInstruccion;$k++){
          $instruccion=($_POST['instruccion'.$k]!='')?$_POST['instruccion'.$k]:'indefinido';
          $titulo=($_POST['titulo'.$k]!='')?$_POST['titulo'.$k]:'indefinido';

          $institucion=($_POST['nombreInstitucion'.$k]!='')?$_POST['nombreInstitucion'.$k]:'indefinido';
          $lugarInstruccion=($_POST['lugarInstruccion'.$k]!='')?$_POST['lugarInstruccion'.$k]:'indefinido';
          $horario=($_POST['horario'.$k]!='')?$_POST['horario'.$k]:'indefinido';
          
          
          echo $sqlInstruccion="INSERT INTO `instruccion_academica`( `instruccion`, `titulo`, `id_empleado`,`institucion_academica`,`lugar_academico`,`horario_academico`) VALUES ('$instruccion','$titulo','$id_empleado', '$institucion','$lugarInstruccion','$horario')";
          $resultInstruccion=mysql_query($sqlInstruccion);
        }
      }
      if($_POST['txtNumeroFilaCursosRealizados']>0){
        $cursos=$_POST['txtNumeroFilaCursosRealizados'];
        for($j=1;$j<=$cursos;$j++){
          $institucion=($_POST['institucion'.$j]!='')?$_POST['institucion'.$j]:'indefinido';
          $certificado=($_POST['certificado'.$j]!='')?$_POST['certificado'.$j]:'indefinido';
          $horas=($_POST['horas'.$j]!='')?$_POST['horas'.$j]:'0';
          
          echo  $sqlInstruccion="INSERT INTO `cursos_realizados`( `institucion`, `certificado_obtenido`, `horas_instruccion`, `id_empleado`) VALUES ('$institucion','$certificado','$horas','$id_empleado')";
          $resultInstruccion=mysql_query($sqlInstruccion);
        }
      }
      if($_POST['txtNumeroFilaBancaria']>0){
        $filasBanco=$_POST['txtNumeroFilaBancaria'];
        for($l=1;$l<=$filasBanco;$l++){
          $entidadBancaria=($_POST['entidadBancaria'.$l]!='')?$_POST['entidadBancaria'.$l]:'indefinido';
          $tipoCuenta=($_POST['tipoCuenta'.$l]!='')?$_POST['tipoCuenta'.$l]:'0';
          $numeroCuenta=($_POST['numeroCuenta'.$l]!='')?$_POST['numeroCuenta'.$l]:'0';
          
          echo $sqlEntidadBancaria="INSERT INTO `entidad_bancaria`( `nombre_entidad`, `tipo_cuenta`, `numero_cuenta`, `id_empleado`) VALUES ('$entidadBancaria','$tipoCuenta','$numeroCuenta','$id_empleado')";
          $resultEntidadBancaria=mysql_query($sqlEntidadBancaria);
        }
      }
      
      $fecha_registro= date("Y-m-d H:i:s");
      if($debeTenerUsuario=="Si"){
        if($existe==false){

         $sql_usuario="insert into usuarios( id_empleado,id_empresa, login, password, tipo, estado, fecha_registro, permisos,reportes_contables)
         values ('".$id_empleado."','".$sesion_id_empresa."','".$txtLogin."','contaweb','Empleado','Activo','".$fecha_registro."','Lectura y Escritura','Si')";

         $result_usuario=mysql_query($sql_usuario);
         $id_usuario=mysql_insert_id();

         if($result_usuario){

          $sqlModulos="Select id From modulos ;";
          $resultModulos=mysql_query($sqlModulos);
          $query = "INSERT INTO permisos_usuarios VALUES ";
          $numRows = mysql_num_rows($resultModulos);
          $counter = 0;
          while($rowModulos=mysql_fetch_array($resultModulos))
          {  
            $counter ++;
            $id = $rowModulos['id'];
                        // $permisos = $_POST['permiso'][$id];
            if($counter < $numRows){
              $query = $query . "\n(null, '$id_usuario', '$id', 'SI','SI','SI','SI','SI'),";
            }else{
              $query = $query . "\n(null, '$id_usuario', '$id', 'SI','SI','SI','SI','SI');";
            }

          }   

          $query;
          $resultPermisos=mysql_query($query);

        }else{
          echo "error al insertar usuario";

        }
      }else{
                // si el empleado existe 
        $sqlActualizarEmpleado="UPDATE `usuarios` SET `id_empleado`='".$id_empleado."'WHERE  login='".$txtLogin."'";


        $resultActualizarEmpleado=mysql_query($sqlActualizarEmpleado);
                //echo $sqlActualizarEmpleado;
      }
    }



        // fin asignar usuario
  }else{
    echo "Error al insertar empleado";

  }



}
else{
  ?><div class="transparent_ajax_error"><p>Error en el envio del Formulario: La c&eacute;dula y el nombre estan vacios</p></div><?php

}

}catch (Exception $e) {
    // Error en algun momento.
 ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
}
}


if($accion == "2"){
// GUARDAR MODIFICACION EMPLEADOS PAGINA: empleados.php
  try
  {
    $idempleado=$_POST['txtIdEmpleado'];
    $nombre=$_POST['txtNombre'];
    $cedula=$_POST['txtCedula'];

    if($cedula != "" & $nombre != "" & $idempleado!= ""){

      $nombre=ucwords($_POST['txtNombre']);
      $apellido=ucwords($_POST['txtApellido']);
      $cedula=$_POST['txtCedula'];
      $direccion=ucwords($_POST['txtDireccion']);
      $telefono=$_POST['txtTelefono'];
      $movil=$_POST['txtMovil'];
      $fecha_nacimiento=$_POST['txtFechaNacimiento'];
      $email=$_POST['txtEmail'];            
      $tipo=$_POST['cmbTipo'];
            //$estado=$_POST['cmbEstado'];
      $id_ciudad=$_POST['cbciudad'];
            //$fecha_ingreso= date("Y-m-d");
      $numero_cargas=$_POST['txtNumeroCargas'];
      $estado_civil=$_POST['cmbEstadoCivil'];
      $cuenta_bancaria=$_POST['txtCuentaBancaria'];
      $fondo_reserva=$_POST['cmbFondoReserva'];
      $cmbDecimos=$_POST['cmbDecimos'];
      $txtAlimentacion=$_POST['txtAlimentacion'];
      $txtVacaciones=$_POST['txtVacaciones'];
      $txtPrestamosIESS=$_POST['txtPrestamosIESS'];
      $txtImpuestoRenta=$_POST['txtImpuestoRenta'];
      $txtRetencionJudicial=$_POST['txtRetencionJudicial'];
      $txtAsociacion=$_POST['txtAsociacion'];
      $txtOtrosIngresos=$_POST['txtOtrosIngresos'];
      $txtOtrosDescuentos=$_POST['txtOtrosDescuentos'];     
             // validacion datos del empleado

      if($nombre==""){
        $nombre = "Indefinido";
      }
      if($apellido==""){
        $apellido = "Indefinido";
      }
      if($cedula==""){
        $cedula ="00000000000";
      }
      if($direccion==""){
        $direccion="Indefinido";
      }
      if($telefono==""){
        $telefono="000000000";
      }
      if($movil ==""){
        $movil="000000000";
      }
      if($fechaNacimiento==""){
        $fechaNacimiento= "2020-01-01";
      }
      if($email==""){
        $email ="indefinido";
      }
      if($numero_cargas == ""){
        $numero_cargas="0";
      }
      if($estadoCivil==""){
        $estadoCivil="Soltero";
      }
      if($fondoReserva==""){
       $fondoReserva="Indefinido";
     }
     if($decimos==""){
       $decimos ="Indefinido";
     }
     if($cuentaBancaria==""){
       $cuentaBancaria="0";
     }
     if($tipo==""){
       $tipo = "Empleado";
     }
     if($estado==""){
       $estado="Inactivo";
     }
     if($alimentacion==""){
       $alimentacion="0";
     }
     if($vacaciones==""){
       $vacaciones="0";
     }
     if($prestamos==""){
      $prestamos="0";
    }
    if($impuestoRenta==""){
     $impuestoRenta="0";
   }
   if($retencionJudicial==""){
     $retencionJudicial="0";
   }
   if($asociacion==""){
     $asociacion="0";
   }
   if($otrosIngresos==""){
     $otrosIngresos="0";
   }
   if($otrosDescuentos==""){
     $otrosDescuentos="0";
   }
  //debe tener usuario para entrar al sistema
   $debeTenerUsuario = $_POST['usuario'];
   if($debeTenerUsuario=="Si"){
     $usuario= $_POST['registrado'];
     $txtLogin="";
     $existe=false;
     if($usuario =="Si"){
       $txtLogin= $_POST['category'];
       $existe = true;
     }else{
       $txtLogin=  substr($nombre , 0,1); 
       $txtLogin= $txtLogin . $apellido;
     //  echo $txtLogin;
     }
   }

   $sqlp="update empleados set  nombre='".$nombre."', apellido='".$apellido."', cedula='".$cedula."', direccion='".$direccion."', telefono='".$telefono."', movil='".$movil."', fecha_nacimiento='".$fecha_nacimiento."', email='".$email."', tipo='".$tipo."', id_ciudad='".$id_ciudad."', numero_cargas='".$numero_cargas."', estado_civil='".$estado_civil."', cuenta_bancaria='".$cuenta_bancaria."', fondos_reserva='".$fondo_reserva."', alimentacion='".$txtAlimentacion."', vacaciones='".$txtVacaciones."', otros_ingresos='".$txtOtrosIngresos."', prestamos_iess='".$txtPrestamosIESS."', impuesto_renta='".$txtImpuestoRenta."', retencion_judicial='".$txtRetencionJudicial."', asociacion='".$txtAsociacion."', otros_descuentos='".$txtOtrosDescuentos."', decimos='".$cmbDecimos."' where id_empleado='".$idempleado."'; ";

   $result=mysql_query($sqlp);

   if($result){
    echo 'Registro actualizado correctamente.';

    $fecha_registro= date("Y-m-d H:i:s");
    if($debeTenerUsuario=="Si"){
      if($existe==false){

       $sql_usuario="insert into usuarios( id_empleado,id_empresa, login, password, tipo, estado, fecha_registro, permisos,reportes_contables)
       values ('".$idempleado."','".$sesion_id_empresa."','".$txtLogin."','contaweb','Empleado','Activo','".$fecha_registro."','Lectura y Escritura','Si')";

       $result_usuario=mysql_query($sql_usuario);
       $id_usuario=mysql_insert_id();

       if($result_usuario){

        $sqlModulos="Select id From modulos ;";
        $resultModulos=mysql_query($sqlModulos);
        $query = "INSERT INTO permisos_usuarios VALUES ";
        $numRows = mysql_num_rows($resultModulos);
        $counter = 0;
        while($rowModulos=mysql_fetch_array($resultModulos))
        {  
          $counter ++;
          $id = $rowModulos['id'];
                              // $permisos = $_POST['permiso'][$id];
          if($counter < $numRows){
            $query = $query . "\n(null, '$id_usuario', '$id', 'SI','SI','SI','SI','SI'),";
          }else{
            $query = $query . "\n(null, '$id_usuario', '$id', 'SI','SI','SI','SI','SI');";
          }

        }   

        $query;
        $resultPermisos=mysql_query($query);

      }else{
        echo "error al insertar usuario";

      }
    }else{
                      // si el empleado existe 
      $sqlActualizarEmpleado="UPDATE `usuarios` SET `id_empleado`='".$idempleado."'WHERE  login='".$txtLogin."'";


      $resultActualizarEmpleado=mysql_query($sqlActualizarEmpleado);
                      //echo $sqlActualizarEmpleado;
    }
  }


}
else{
  echo 'Error al guarda los datos: problemas con la consulta.';
}

}else{
  echo 'Error en el envio del Formulario: No hay datos.';
}

}catch (Exception $e) {
    // Error en algun momento.
 ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
}

}


if($accion == "3"){
// Inactivo/Activo EMPLEADOS CAMBIANDO EL ESTADO EN INACTIVO O ACTIVO PAGINA: empleados.php
  try
  {
    $id_emple=$_POST['id_empleado'];
    $sqlp="update empleados set estado='Inactivo' where id_empleado='".$id_emple."'; ";
    $result=mysql_query($sqlp);
    if($result){
      ?> <div class='transparent_ajax_correcto'><p>Empleado Suspendido.</p></div> <?php
    }
    else{
     ?> <div class='transparent_ajax_error'><p>Error al Inactivar empleado: problemas con la consulta <?php echo "\n".mysql_error(); ?></p></div> <?php
   }
 }catch (Exception $e) {
    // Error en algun momento.
   ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
 }
}


if($accion == "4"){
// Inactivo/Activo EMPLEADOS CAMBIANDO EL ESTADO EN INACTIVO O ACTIVO PAGINA: empleados.php
  try
  {
    $id_emple=$_POST['id_empleado'];
    $sqlp="update empleados set estado='Activo' where id_empleado='".$id_emple."'; ";
    $result=mysql_query($sqlp);
    if($result){
      ?> <div class='transparent_ajax_correcto'><p>Empleado Activado.</p></div> <?php
    }
    else{
     ?> <div class='transparent_ajax_error'><p>Error al Activar empleado: problemas con la consulta <?php echo "\n".mysql_error(); ?></p></div> <?php
   }
 }catch (Exception $e) {
    // Error en algun momento.
   ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
 }
}



if($accion == "5"){
//saca el nombre y apellido, esta consulta retorna en la pagina: empresa.php
  try
  {
    $id_empl=$_POST['id_empleado'];
    $consulta5="SELECT * FROM empleados WHERE id_empleado='".$id_empl."';";
    $result=mysql_query($consulta5);
    if($result){
            while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
            {
              $cadena=$cadena."?".$row['id_empleado']."?".$row['nombre']."?".$row['apellido'];
            }
            echo "".$cadena;
          }else{
           ?> <div class='transparent_ajax_error'><p>Error: problemas con la consulta <?php echo "\n".mysql_error(); ?></p></div> <?php
         }
       }catch (Exception $e) {
    // Error en algun momento.
         ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
       }

     }

     if($accion == "6"){    
    // ELIMINA EMPLEADOS PAGINA: empleados.php
       try
       {
        if(isset ($_POST['id_empleado'])){
          $id_empleado = $_POST['id_empleado'];
          $sql4 = "delete from empleados where id_empleado='".$id_empleado."'; ";
          $resp4 = mysql_query($sql4);
          if(!mysql_query($sql4)){
            echo "Ocurrio un error: Pruebe eliminando primero el usuario\n$sql4";
          }else{
            echo "El registro ha sido Eliminado."; 
            $sqlEliminaEmpleado = "delete from usuarios where id_empleado='".$id_empleado."'; ";
            $respEliminaEmpleado = mysql_query($sqlEliminaEmpleado);
          }



        }else {
          ?> <div class="transparent_ajax_error"><p>Error: No hay datos</p></div> <?php
        }

      }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
     }

   }

   if($accion == "7"){
// VALIDACIONES PARA QUE EL EMAIL DE LA EMPRESA NO SE REPITA
     try
     {
       if(isset ($_POST['email'])){
        $email = $_POST['email'];
        $sql7="Select email From empresa WHERE email ='".$email."';";
//          echo " consulta ".$sql;
        $resp7 = mysql_query($sql7);
        $entro=0;
          while($row7=mysql_fetch_array($resp7))//permite ir de fila en fila de la tabla
          {
            $var1=$row7["email"];
          }
          $email = strtolower($email);
          if($var1==$email){
           if($var1==""&&$email==""){
             $entro=0;
           }else {
            $entro=1;
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

  $parentesco=($_POST['parentesco']!='')?$_POST['parentesco']:'indefinido';
  $apellidoFamiliar=($_POST['apellidos']!='')?$_POST['apellidos']:'indefinido';
  $nombresFamiliar=($_POST['nombres']!='')?$_POST['nombres']:'indefinido';
  $fechaFamiliar=($_POST['fechaDeNacimiento']!='')?$_POST['fechaDeNacimiento']:'0000-00-00';
  $cedulaFamiliar=($_POST['cedula']!='')?$_POST['cedula']:'0';
  $direccionFamiliar=($_POST['domicilio']!='')?$_POST['domicilio']:'indefinido';
  $id_empleado=($_POST['idEmpleado']!='')?$_POST['idEmpleado']:'0';
  $porcentajeDiscapacidad=($_POST['porcentajeDiscapacidad']!='')?$_POST['porcentajeDiscapacidad']:'0';
  $tipoDiscapacidad=($_POST['tipoDiscapacidad']!='')?$_POST['tipoDiscapacidad']:'0';
  $viven=($_POST['viven']!='')?$_POST['viven']:'NO';


  $sqlDatosFamiliares="INSERT INTO `datos_familiares`( `parentesco`, `apellidos`, `nombres`, `fecha_nacimiento`, `cedula`, `direccion`, `id_empleado`,`vivenConEmpleado`,`porcentajeDiscapacidad`,`tipoDiscapacidad`) VALUES ('$parentesco','$apellidoFamiliar','$nombresFamiliar','$fechaFamiliar','$cedulaFamiliar','$direccionFamiliar','$id_empleado','$viven','$porcentajeDiscapacidad','$tipoDiscapacidad')";
  $resultFamiliares=mysql_query($sqlDatosFamiliares);
  echo $response= ($resultFamiliares)?'1':'2';
}
if($accion === "9"){
  $idInstruccion=($_POST['idI']!='')?$_POST['idI']:'indefinido';
  $instruccion=($_POST['instruccion']!='')?$_POST['instruccion']:'indefinido';
  $titulo=($_POST['titulo']!='')?$_POST['titulo']:'indefinido';
  $id_empleado=($_POST['id_empleado']!='')?$_POST['id_empleado']:'0';
  $institucion=($_POST['institucion']!='')?$_POST['institucion']:'indefinido';
  $lugar=($_POST['lugar']!='')?$_POST['lugar']:'indefinido';
  $horario=($_POST['horario']!='')?$_POST['horario']:'indefinido';

  $sqlInstruccion="INSERT INTO `instruccion_academica`( `instruccion`, `titulo`, `id_empleado`, `institucion_academica`, `lugar_academico`, `horario_academico`) VALUES ('$instruccion','$titulo','$id_empleado','$institucion','$lugar','$horario')";
  $resultInstruccion=mysql_query($sqlInstruccion);
  echo $respuesta= ($resultInstruccion)?'1':'2';

}
if($accion === "10"){
  $institucion=($_POST['institucion']!='')?$_POST['institucion']:'indefinido';
  $certificado=($_POST['certificado']!='')?$_POST['certificado']:'indefinido';
  $horas=($_POST['horas']!='')?$_POST['horas']:'0';
  $id_empleado=($_POST['id_empleado']!='')?$_POST['id_empleado']:'0';

  $sqlInstruccion="INSERT INTO `cursos_realizados`( `institucion`, `certificado_obtenido`, `horas_instruccion`, `id_empleado`) VALUES ('$institucion','$certificado','$horas','$id_empleado')";
  $resultInstruccion=mysql_query($sqlInstruccion);
  echo $response = ($resultInstruccion)?'1':'2';
}
if($accion === "11"){
  $entidadBancaria=($_POST['entidadBancaria']!='')?$_POST['entidadBancaria']:'indefinido';
  $tipoCuenta=($_POST['tipoCuenta']!='')?$_POST['tipoCuenta']:'0';
  $numeroCuenta=($_POST['numeroCuenta']!='')?$_POST['numeroCuenta']:'0';
  $id_empleado=($_POST['id_empleado']!='')?$_POST['id_empleado']:'0';

  $sqlEntidadBancaria="INSERT INTO `entidad_bancaria`( `nombre_entidad`, `tipo_cuenta`, `numero_cuenta`, `id_empleado`,cuenta) VALUES ('$entidadBancaria','$tipoCuenta','$numeroCuenta','$id_empleado',0)";
  $resultEntidadBancaria=mysql_query($sqlEntidadBancaria);
  echo $resultado= ($resultEntidadBancaria)?'1':'2';
}

if($accion == "12"){
  // GUARDAR EMPLEADOS PAGINA: registro.php empleados.php
  try
  {        
    $nombre=($_POST['txtNombre']!='')?$_POST['txtNombre']:'indefinido';
    $cedula=($_POST['txtCedula']!='')?$_POST['txtCedula']:'0';
    if($cedula != "" & $nombre != ""){
     
     $apellido=($_POST['txtApellido']!='')?$_POST['txtApellido']:'indefinido';
     $nacionalidad=($_POST['cbnacionalidad']!='')?$_POST['cbnacionalidad']:'indefinido';
     $fechaNacimiento=($_POST['txtFechaNacimiento']!='')?$_POST['txtFechaNacimiento']:date('Y-m-d');
     $lugarNacimiento=($_POST['lugarNacimiento']!='')?$_POST['lugarNacimiento']:'indefinido';
     $pais=($_POST['cbpais']!='')?$_POST['cbpais']:'0';
     $ciudad=($_POST['cbciudad']!='')?$_POST['cbciudad']:'0';
     $radio_discapacidad=($_POST['radio-discapacidad']!='')?$_POST['radio-discapacidad']:'NO';
     $porcentajeDiscapacidad=($_POST['iGporcentajeDiscapacidad']!='')?$_POST['iGporcentajeDiscapacidad']:'0';
     $radio_genero=($_POST['radio-genero']!='')?$_POST['radio-genero']:'M';
     $radio_sangre=($_POST['radio-tSangre']!='')?$_POST['radio-tSangre']:'indefinido';
     $licencia=($_POST['licencia']!='')?$_POST['licencia']:'indefinido';
     
     $radio_estadoCivil=($_POST['radio-estadoCivil']!='')?$_POST['radio-estadoCivil']:'indefinido';
     $otrosEstadoCivil=$radio_estadoCivil;
     
     $email=($_POST['txtEmail']!='')?$_POST['txtEmail']:'indefinido';
     $viveCon=($_POST['radio-viveCon']!='')?$_POST['radio-viveCon']:'Solo';
     $radio_medicinas=($_POST['radio-medicinas']!='')?$_POST['radio-medicinas']:'NO';
     $causaMedicina=($_POST['radio-medicinas']=='SI')?$_POST['causaMedicina']:'indefinido';
     $radio_alergias=($_POST['radio-alergias']!='')?$_POST['radio-alergias']:'NO';
     $tipoAlergia=($_POST['radio-alergias']=='SI')?$_POST['tipoAlergia']:'indefinido';
     $radio_dolencias=($_POST['radio-dolencias']!='')?$_POST['radio-dolencias']:'NO';
     $dolencias=($_POST['radio-dolencias']=='SI')?$_POST['dolencia']:'indefinido';
     $radio_cirugias=($_POST['radio-cirugias']!='')?$_POST['radio-cirugias']:'NO';
     $cirugias=($_POST['radio-cirugias']=='SI')?$_POST['cirugias']:'indefinido';
     $medicoPersonal=($_POST['medicoPersonal']!='')?$_POST['medicoPersonal']:'indefinido';
     
     $telefonoMedico=($_POST['telefonoMedico']!='')?$_POST['telefonoMedico']:'0';
     $radio_ligada=($_POST['radio-ligada']!='')?$_POST['radio-ligada']:'NO';
     $callePrincipal=($_POST['callePrincipal']!='')?$_POST['callePrincipal']:'Indefinido';
     $calleSecundaria=($_POST['calleSecundaria']!='')?$_POST['calleSecundaria']:'Indefinido';
     $casaNumeracion=($_POST['casaNumeracion']!='')?$_POST['casaNumeracion']:'indefinido';
     $departamentoNumeracion=($_POST['departamentoNumeracion']!='')?$_POST['departamentoNumeracion']:'indefinido';
     $sector=($_POST['sector']!='')?$_POST['sector']:'indefinido';
     $parroquia=($_POST['parroquia']!='')?$_POST['parroquia']:'indefinido';
     $cbciudad2=($_POST['cbciudad2']!='')?$_POST['cbciudad2']:'0';
     $radio_viveEn=($_POST['radio-viveEn']!='')?$_POST['radio-viveEn']:'indefinido';
     
     
     $radio_viviendaDispone=($_POST['radio-viviendaDispone']!='')?$_POST['radio-viviendaDispone']:'0';
     $cadenaViviendaDispone='';
     
     foreach($_POST['radio-viviendaDispone'] as $vD){
      $cadenaViviendaDispone.= $vD.',' ;
    }
    
    $otrosVivienda=($_POST['otrosVivienda']!='')? $cadenaViviendaDispone.$_POST['otrosVivienda']:$cadenaViviendaDispone;

    $referencia=($_POST['referencia']!='')?$_POST['referencia']:'Indefinido';
    $telefonoConvencional=($_POST['telefonoConvencional']!='')?$_POST['telefonoConvencional']:'0';
    $celular1=($_POST['celular1']!='')?$_POST['celular1']:'0';
    $operadora1=($_POST['operadora1']!='')?$_POST['operadora1']:'indefinido';
    $celular2=($_POST['celular2']!='')?$_POST['celular2']:'0';
    $operadora2=($_POST['operadora2']!='')?$_POST['operadora2']:'indefinido';
    $celular3=($_POST['celular3']!='')?$_POST['celular3']:'0';
    $operadora3=($_POST['operadora3']!='')?$_POST['operadora3']:'indefinido';
    
    $facebook=($_POST['facebook']!='')?$_POST['facebook']:'indefinido';
    $twitter=($_POST['Twitter']!='')?$_POST['Twitter']:'indefinido';
    $instagram=($_POST['Instagram']!='')?$_POST['Instagram']:'indefinido';
    $youtube=($_POST['youtube']!='')?$_POST['youtube']:'indefinido';
    $tikTok=($_POST['tikTok']!='')?$_POST['tikTok']:'indefinido';
    $Linkedln=($_POST['Linkedln']!='')?$_POST['Linkedln']:'indefinido';

     $fondoReserva=($_POST['cmbFondoReserva']!='')?$_POST['cmbFondoReserva']:'indefinido'; //ok
     $decimos=($_POST['cmbDecimos']!='')?$_POST['cmbDecimos']:'indefinido'; //ok
     $decimo14=($_POST['cmbDecimosCuarto']!='')?$_POST['cmbDecimosCuarto']:'indefinido';
     $tipo=($_POST['cmbTipo']!='')?$_POST['cmbTipo']:'indefinido'; //ok
     $estado=($_POST['cmbEstado']!='')?$_POST['cmbEstado']:'indefinido'; //ok
     $vacaciones=($_POST['txtVacaciones']!='')?$_POST['txtVacaciones']:'0'; //ok
     $prestamosIess=($_POST['txtPrestamosIESS']!='')?$_POST['txtPrestamosIESS']:'0'; //ok
     $impuestoRenta=($_POST['txtImpuestoRenta']!='')?$_POST['txtImpuestoRenta']:'0'; //ok
     $retencionJudicial=($_POST['txtRetencionJudicial']!='')?$_POST['txtRetencionJudicial']:'0'; //ok
     $asociacion=($_POST['txtAsociacion']!='')?$_POST['txtAsociacion']:'0'; //ok
     $otrosIngresos=($_POST['txtOtrosIngresos']!='')?$_POST['txtOtrosIngresos']:'0';//ok
     $otrosDescuentos=($_POST['txtOtrosDescuentos']!='')?$_POST['txtOtrosDescuentos']:'0'; //ok
     $cargo=($_POST['cmbCargo']!='')?$_POST['cmbCargo']:'0'; //ok
     $tipoEmpleado=($_POST['cmbTipoEmpleado']!='')?$_POST['cmbTipoEmpleado']:'0'; //ok


     
     $numeroContrato=($_POST['txtNumeroContrato']!='')?$_POST['txtNumeroContrato']:'0';
     $fechaContrato=($_POST['fechaContrato']!='')?$_POST['fechaContrato']:'0000-00-00';
     $fechaIngreso=($_POST['fechaIngreso']!='')?$_POST['fechaIngreso']:'0000-00-00';
     $tipoSalario=($_POST['cmbTipoSalario']!='')?$_POST['cmbTipoSalario']:'indefinido';
     
     $departamento=($_POST['cmbDepartamento']!='')?$_POST['cmbDepartamento']:'0';
     $cCostos=($_POST['cmbCostos']!='')?$_POST['cmbCostos']:'indefinido';
    //  $estado=($_POST['txtRetencionJudicial']!='')?$_POST['txtRetencionJudicial']:'indefinido';
     $fechaSalida=($_POST['fechaSalida']!='')?$_POST['fechaSalida']:'0000-00-00';
     $formaPago=($_POST['cmbFormaPago']!='')?$_POST['cmbFormaPago']:'indefinido';
     $decimoTercer= isset($_POST['decimoTercerSueldo'])?'SI':'NO';
     $decimoCuarto= isset($_POST['decimoCuartoSueldo'])?'SI':'NO';
     $decimoAsumirIess= isset($_POST['asumirIess'])?'SI':'NO';
     $propietarioAportacion= isset($_POST['aportacion'])?'SI':'NO';
     
     
     $lugar_nacimiento= $_POST['lugarNacimiento'];
     $sucursal= isset($_POST['txtSucursal'])? $_POST['txtSucursal']:'0'; 
     $tipo_categoria = isset($_POST['cmbCategoria'])? $_POST['cmbCategoria']:'0';
     $hoy= Date('Y-m-d');
     
     $fichero = $_FILES["file"];
     $foto= ($fichero["name"]!='')?$fichero["name"]:'';
     
     $path = "subidas/empleados/".$cedula;
     
     if (!is_dir($path)) {
       mkdir($path, 0777, true);
     }
     $searchString = " ";
     $replaceString = "";
     $foto = str_replace($searchString, $replaceString,$fichero["name"] ); 
 // Cargando el fichero en la carpeta "subidas"
     move_uploaded_file($fichero["tmp_name"], "subidas/empleados/".$cedula."/".$foto);

     $fecha_salida=($_POST['fechaSalida']!='')?$_POST['fechaSalida']:'0000-00-00';
     $fechaIngreso=($_POST['fechaIngreso']!='')?$_POST['fechaIngreso']:'0000-00-00';
     
     $id_empleado = $_POST['id_empleado'];
 $sql="UPDATE `empleados` SET `nombre`='$nombre',`apellido`='$apellido',`cedula`='$cedula',`direccion`='$callePrincipal',`telefono`='$telefonoConvencional',`movil`='$celular1',`fecha_nacimiento`='$fechaNacimiento',`email`='$email',`tipo`='$tipo',`estado`='$estado',`id_ciudad`='$ciudad',`fecha_registro`='$hoy',`numero_cargas`='0',`estado_civil`='$otrosEstadoCivil',`posicion`='Ocupado',`cuenta_bancaria`='0',`nacionalidad`='$nacionalidad',`discapacidad`='$radio_discapacidad',`porcentaje_discapacidad`='$porcentajeDiscapacidad',`genero`='$radio_genero',`tipo_sangre`= '$radio_sangre',`licencia_conducir`='$licencia',`vive_con`='$viveCon',`toma_medicinas`='$radio_medicinas',`causa_medicina`='$causaMedicina',`tiene_alergia`='$radio_alergias',`tipo_alergia`='$tipoAlergia',`tiene_dolencia`='$radio_dolencias',`tipo_dolencia`='$dolencias',`tiene_cirugias`='$radio_cirugias',`tipos_cirugias`='$cirugias',`medico_personal`='$medicoPersonal',`telefono_medico`= '$telefonoMedico',`mujer_ligada`='$radio_ligada',`calle_principal`='$callePrincipal',`calle_secundaria`='$calleSecundaria',`casa_numeracion`='$casaNumeracion',`departamento_numeracion`='$departamentoNumeracion',`sector`='$sector',`parroquia`='$parroquia',`ciudad_domicilio`='$cbciudad2',`vive_en`='$radio_viveEn',`vivienda_noDispone`='$otrosVivienda',`referencia_domicilio`='$referencia',`celular1`='$celular1',`operadora1`='$operadora1',`celular2`='$celular2',`operadora2`='$operadora2',`celular3`='$celular3',`operadora3`='$operadora3',`facebook`='$facebook',`twitter`='$twitter',`instagram`='$instagram',`youtube`='$youtube',`tiktok`='$tikTok',`linkedln`='$Linkedln',`fondos_reserva`='$fondoReserva',`alimentacion`='0',`vacaciones`='$vacaciones',`otros_ingresos`='$otrosIngresos',`prestamos_iess`='$prestamosIess',`impuesto_renta`='$impuestoRenta',`retencion_judicial`=' $retencionJudicial',`asociacion`='$asociacion',`otros_descuentos`='$otrosDescuentos',`decimos`='$decimos',`id_empresa`='$sesion_id_empresa',`fecha_ingreso`='$hoy',`cargos`='$cargo', sucursal='$sucursal',lugar_nacimiento='$lugar_nacimiento',`numeroContrato`='$numeroContrato',`fechaContrato`='$fechaContrato',`tipoSalario`='$tipoSalario',`departamento`='$departamento',`centroCostos`='$cCostos',`fechaSalida`='$fechaSalida',`formaPago`='$formaPago',`decimoTercerSueldo`='$decimoTercer',`decimoCuartoSueldo`='$decimoCuarto',`asumirIess`='$decimoAsumirIess',`propietarioAportacion`='$propietarioAportacion', 	tipo_categoria='$tipo_categoria' ,fecha_salida='$fecha_salida', fecha_ingreso='$fechaIngreso',decimo14='$decimo14'   ";

     if($foto!=''){
      $sql .=" , `foto`='$foto'";
    }
    
    $sql .=" WHERE id_empleado='$id_empleado';";
    // echo $sql;
    $result=mysql_query($sql);

//   echo $sql;
    if($result){
     echo 'Se actualizo el empleado correctamente.';
         // fin asignar usuario
   }else{
     echo "Error al insertar empleado";
     
   }
   
   

   
 }
 else{
   ?><div class="transparent_ajax_error"><p>Error en el envio del Formulario: La c&eacute;dula y el nombre estan vacios</p></div><?php
   
 }
 
}catch (Exception $e) {
     // Error en algun momento.
  ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
}
}


if($accion === "13"){
  
  $parentesco=($_POST['parentesco']!='')?$_POST['parentesco']:'indefinido';
  $apellidoFamiliar=($_POST['apellidos']!='')?$_POST['apellidos']:'indefinido';
  $nombresFamiliar=($_POST['nombres']!='')?$_POST['nombres']:'indefinido';
  $fechaFamiliar=($_POST['fechaDeNacimiento']!='')?$_POST['fechaDeNacimiento']:'0000-00-00';
  $cedulaFamiliar=($_POST['cedula']!='')?$_POST['cedula']:'0';
  $direccionFamiliar=($_POST['domicilio']!='')?$_POST['domicilio']:'indefinido';
  $id_empleado=($_POST['idEmpleado']!='')?$_POST['idEmpleado']:'0';
  $porcentajeDiscapacidad=($_POST['porcentajeDiscapacidad']!='')?$_POST['porcentajeDiscapacidad']:'0';
  $tipoDiscapacidad=($_POST['tipoDiscapacidad']!='')?$_POST['tipoDiscapacidad']:'0';
  $viven=($_POST['viven']!='')?$_POST['viven']:'NO';
  
  $idDF=($_POST['idDF']!='')?$_POST['idDF']:'indefinido';

  $sqlFamiliares="UPDATE `datos_familiares` SET `parentesco`='$parentesco',`apellidos`='$apellidoFamiliar',`nombres`='$nombresFamiliar',`fecha_nacimiento`='$fechaFamiliar',`cedula`='$cedulaFamiliar',`direccion`='$direccionFamiliar',`vivenConEmpleado`='$viven',`porcentajeDiscapacidad`='$porcentajeDiscapacidad',`tipoDiscapacidad`='$tipoDiscapacidad' WHERE `id_empleado`='$id_empleado' and id='$idDF'";
  $resultFamiliares=mysql_query($sqlFamiliares);
  echo $respuesta = ($resultFamiliares)?'3':'4';
}
if($accion === "14"){
  $idDF=($_POST['idDF']!='')?$_POST['idDF']:'indefinido';
  $sqlFamiliares="DELETE FROM `datos_familiares` WHERE id='$idDF'";
  $resultFamiliares=mysql_query($sqlFamiliares);
  echo $respuesta = ($resultFamiliares)?'1':'2';
}
if($accion === "15"){
  $idInstruccion=($_POST['idI']!='')?$_POST['idI']:'indefinido';
  $instruccion=($_POST['instruccion']!='')?$_POST['instruccion']:'indefinido';
  $titulo=($_POST['titulo']!='')?$_POST['titulo']:'indefinido';
  $id_empleado=($_POST['id_empleado']!='')?$_POST['id_empleado']:'0';
  $institucion=($_POST['institucion']!='')?$_POST['institucion']:'indefinido';
  $lugar=($_POST['lugar']!='')?$_POST['lugar']:'indefinido';
  $horario=($_POST['horario']!='')?$_POST['horario']:'indefinido';

  $sqlInstruccion="UPDATE `instruccion_academica` SET `instruccion`='$instruccion',`titulo`='$titulo',`institucion_academica`='$institucion',`lugar_academico`='$lugar',`horario_academico`='$horario' WHERE `id_instruccion`='$idInstruccion'";
  $resultInstruccion=mysql_query($sqlInstruccion);
  echo $response=($resultInstruccion)?'3':'4';
}
if($accion === "16"){
  $idInstruccion=($_POST['idDF']!='')?$_POST['idDF']:'indefinido';
  $sqlInstruccion="DELETE FROM `instruccion_academica`  WHERE `id_instruccion`='$idInstruccion'";
  $resultInstruccion=mysql_query($sqlInstruccion);
  echo $response=($resultInstruccion)?'3':'4';
}
if($accion === "17"){
  $idCurso=($_POST['idDF']!='')?$_POST['idDF']:'indefinido';
  $sqlInstruccion="DELETE FROM `cursos_realizados` WHERE `id_cursos`='$idCurso'";
  $resultInstruccion=mysql_query($sqlInstruccion);
  echo $response=($resultInstruccion)?'5':'6';
}
if($accion === "18"){
  $idCursos=($_POST['idCurso']!='')?$_POST['idCurso']:'indefinido';
  $institucion=($_POST['institucion']!='')?$_POST['institucion']:'indefinido';
  $certificado=($_POST['certificado']!='')?$_POST['certificado']:'indefinido';
  $horas=($_POST['horas']!='')?$_POST['horas']:'0';
  $id_empleado=($_POST['id_empleado']!='')?$_POST['id_empleado']:'0';

  $sqlInstruccion="UPDATE `cursos_realizados` SET `institucion`='$institucion',`certificado_obtenido`='$certificado',`horas_instruccion`='$horas',`id_empleado`='$id_empleado' WHERE id_cursos='$idCursos'";
  $resultInstruccion=mysql_query($sqlInstruccion);
  echo $response = ($resultInstruccion)?'3':'4';
}
if($accion === "19"){
  $idEB=($_POST['idEB']!='')?$_POST['idEB']:'indefinido';
  $entidadBancaria=($_POST['entidadBancaria']!='')?$_POST['entidadBancaria']:'indefinido';
  $tipoCuenta=($_POST['tipoCuenta']!='')?$_POST['tipoCuenta']:'0';
  $numeroCuenta=($_POST['numeroCuenta']!='')?$_POST['numeroCuenta']:'0';
  $id_empleado=($_POST['id_empleado']!='')?$_POST['id_empleado']:'0';

  $sqlEntidadBancaria="UPDATE `entidad_bancaria` SET `nombre_entidad`='$entidadBancaria',`tipo_cuenta`='$tipoCuenta',`numero_cuenta`='$numeroCuenta',`id_empleado`='$id_empleado' WHERE `id_entidad`='$idEB'";
  $resultEntidadBancaria=mysql_query($sqlEntidadBancaria);
  echo $resultado = ($resultEntidadBancaria)?'3':'4';
}
if($accion === "20"){
  $idEB=($_POST['idDF']!='')?$_POST['idDF']:'indefinido';
  $sqlEntidadBancaria="DELETE FROM `entidad_bancaria` WHERE `id_entidad`='$idEB'";
  $resultEntidadBancaria=mysql_query($sqlEntidadBancaria);
  echo $resultado = ($resultEntidadBancaria)?'7':'8';
}
if($accion === "21"){
  $idE=($_POST['idE']!='')?$_POST['idE']:'indefinido';
  $nombres=($_POST['nombres']!='')?$_POST['nombres']:'indefinido';
  $apellidos=($_POST['apellidos']!='')?$_POST['apellidos']:'indefinido';
  $parentesco=($_POST['parentesco']!='')?$_POST['parentesco']:'indefinido';
  $convencional=($_POST['convencional']!='')?$_POST['convencional']:'indefinido';
  $celular1=($_POST['celular1']!='')?$_POST['celular1']:'indefinido';
  $celular2=($_POST['celular2']!='')?$_POST['celular2']:'indefinido';
  $idE=($_POST['idE']!='')?$_POST['idE']:'indefinido';
  $id_empleado=($_POST['id_empleado']!='')?$_POST['id_empleado']:'0';


  $sqlDatosEmergencia="INSERT INTO `datos_emergencia`( `nombres`, `apellidos`, `parentesco`, `convencional`, `celular1`, `celular2`, `id_empleado`) VALUES ('$nombres','$apellidos','$parentesco','$convencional','$celular1','$celular2','$id_empleado')";
  $resultDatosEmergencia=mysql_query($sqlDatosEmergencia);
  echo $resultado = ($resultDatosEmergencia)?'1':'2';
}
if($accion === "22"){
  $idE=($_POST['idE']!='')?$_POST['idE']:'indefinido';
  $nombres=($_POST['nombres']!='')?$_POST['nombres']:'indefinido';
  $apellidos=($_POST['apellidos']!='')?$_POST['apellidos']:'indefinido';
  $parentesco=($_POST['parentesco']!='')?$_POST['parentesco']:'indefinido';
  $convencional=($_POST['convencional']!='')?$_POST['convencional']:'indefinido';
  $celular1=($_POST['celular1']!='')?$_POST['celular1']:'indefinido';
  $celular2=($_POST['celular2']!='')?$_POST['celular2']:'indefinido';
  
  $id_empleado=($_POST['id_empleado']!='')?$_POST['id_empleado']:'0';


  $sqlDatosEmergencia="UPDATE `datos_emergencia` SET `nombres`='$nombres',`apellidos`='$apellidos',`parentesco`='$parentesco',`convencional`='$convencional',`celular1`='$celular1',`celular2`='$celular2',`id_empleado`='$id_empleado' WHERE  `id_emergencia`='$idE'";
  $resultDatosEmergencia=mysql_query($sqlDatosEmergencia);
  echo $resultado = ($resultDatosEmergencia)?'3':'5';
}

if($accion === "23"){
  $idE=($_POST['idDF']!='')?$_POST['idDF']:'indefinido';

  $sqlDatosEmergencia="DELETE FROM `datos_emergencia` WHERE `id_emergencia`='$idE'";
  $resultDatosEmergencia=mysql_query($sqlDatosEmergencia);
  echo $resultado = ($resultDatosEmergencia)?'9':'10';
}

if($accion === "25"){
 $id_empleado= $_POST['id_empleado'];
 $totalIngresosEsteEmpleador=($_POST['totalIngresosEsteEmpleador']!='')?$_POST['totalIngresosEsteEmpleador']:'0';
 $totalIngresosOtrosEmpleadores=($_POST['totalIngresosOtrosEmpleadores']!='')?$_POST['totalIngresosOtrosEmpleadores']:'0';
 $totalIngresosProyectados=($_POST['totalIngresosProyectados']!='')?$_POST['totalIngresosProyectados']:'0';
 $gastosVivienda=($_POST['gastosVivienda']!='')?$_POST['gastosVivienda']:'0';

 $gastosEducacionArteCultura=($_POST['gastosEducacionArteCultura']!='')?$_POST['gastosEducacionArteCultura']:'0';
 $gastosSalud=($_POST['gastosSalud']!='')?$_POST['gastosSalud']:'0';
 $gastosVestimenta=($_POST['gastosVestimenta']!='')?$_POST['gastosVestimenta']:'0';
 $gastosAlimentacion=($_POST['gastosAlimentacion']!='')?$_POST['gastosAlimentacion']:'0';
 
 $gastosTurismo=($_POST['gastosTurismo']!='')?$_POST['gastosTurismo']:'0';
 $totalGastosProyectados=($_POST['totalGastosProyectados']!='')?$_POST['totalGastosProyectados']:'0';
 $rebajaImpuestoRenta=($_POST['rebajaImpuestoRenta']!='')?$_POST['rebajaImpuestoRenta']:'0';
 
 $fechaLimite= '';
 $sqlParametros ="SELECT  `fecha_hasta_gastos_personales` FROM `parametros_rol_pagos` WHERE id_empresa=$sesion_id_empresa";
 $resultParametros=mysql_query($sqlParametros);
while($rowParametros=mysql_fetch_array($resultParametros))//permite ir de fila en fila de la tabla
{
  $fechaLimite = $rowParametros['fecha_hasta_gastos_personales'];
}
// echo date('Y-m-d');
// echo '->';
$fecha_actual = strtotime(date('Y-m-d'));
// echo '|';
// echo $fechaLimite;
// echo '->';
$fecha_limite = strtotime($fechaLimite);
// echo '<br>';

// echo gettype($fecha_actual);
// echo '<br>';

// echo gettype($fechaLimite);
if($fecha_actual < $fecha_limite){
    //   echo 'entro1';
 $fecha_gastos=$_POST['fecha_gastos'];
 $fechaComoEntero = strtotime($fecha_gastos);
 $anio = date("Y", $fechaComoEntero);
 $sql3="SELECT `id_gastos` FROM `gastos_personales` WHERE `id_empleado`=$id_empleado and YEAR(fecha_gastos)='$anio' " ;
 $result3=mysql_query($sql3);
 $existeGastoPersonal=  mysql_num_rows($result3);

 
 if($existeGastoPersonal== 0){
  $sql2="INSERT INTO `gastos_personales`( `total_ingresos_este_empleador`, `total_ingresos_otros_empleadores`, `total_ingresos_proyectados`, `gastos_vivienda`, `gastos_educacion_arte_cultura`, `gastos_salud`, `gastos_vestimenta`, `gastos_alimentacion`, `gastos_turismo`, `total_gastos_proyectados`, `rebaja_impuesto_renta`, `id_empleado`,fecha_gastos) VALUES ('$totalIngresosEsteEmpleador','$totalIngresosOtrosEmpleadores','$totalIngresosProyectados','$gastosVivienda','$gastosEducacionArteCultura','$gastosSalud','$gastosVestimenta','$gastosAlimentacion','$gastosTurismo','$totalGastosProyectados','$rebajaImpuestoRenta',$id_empleado,'$fecha_gastos')";

}else{
 $sql2="UPDATE `gastos_personales` SET `total_ingresos_este_empleador`='$totalIngresosEsteEmpleador',`total_ingresos_otros_empleadores`='$totalIngresosOtrosEmpleadores',`total_ingresos_proyectados`='$totalIngresosProyectados',`gastos_vivienda`='$gastosVivienda',`gastos_educacion_arte_cultura`='$gastosEducacionArteCultura',`gastos_salud`='$gastosSalud',`gastos_vestimenta`='$gastosVestimenta',`gastos_alimentacion`='$gastosAlimentacion',`gastos_turismo`='$gastosTurismo',`total_gastos_proyectados`='$totalGastosProyectados',`rebaja_impuesto_renta`='$rebajaImpuestoRenta' ,fecha_gastos='$fecha_gastos'  WHERE `id_empleado`=$id_empleado and YEAR(fecha_gastos)=$anio ";

}
$result2=mysql_query($sql2);
if($result2){
  echo 'guardo';
}else{
  echo 'no guardo';
}

}else{
 echo 'Fecha limite alcanzado no guardo';
}

}
if($accion == "26"){
 $idEntidad=($_POST['idEB']!='')?$_POST['idEB']:0;
 $cuenta=$_POST['cuenta'];
 $id_empleado=$_POST['id_empleado'];
 
 $sqlEntidadBancariaVaciar="UPDATE `entidad_bancaria` SET `cuenta`=0 WHERE `id_empleado`=$id_empleado";
 $resultEntidadBancariaVaciar=mysql_query($sqlEntidadBancariaVaciar);

 $sqlEntidadBancaria="UPDATE `entidad_bancaria` SET `cuenta`=$cuenta WHERE `id_entidad`=$idEntidad";
 
 $resultEntidadBancaria=mysql_query($sqlEntidadBancaria);
 echo $resultado= ($resultEntidadBancaria)?'4':'5';
}

if($accion == "27"){
 
  $fechaIngreso=$_POST['fechaIngreso'];
  $fechaSalida=($_POST['fechaSalida']=='')?'00-00-0000':$_POST['fechaSalida'];
  $id_empleado=$_POST['id_empleado'];
  

    $sqlFechasTrabajadasVaciar="UPDATE `fechas_trabajadas_empleados` SET `actual`='0' WHERE `id_empleado`=$id_empleado";
   $resultFechasTrabajadasVaciar=mysql_query($sqlFechasTrabajadasVaciar);
   
    $sql="UPDATE `empleados` SET `fecha_ingreso`='$fechaIngreso',`fecha_salida`='$fechaSalida' WHERE `id_empleado`=$id_empleado ";
    $result =mysql_query($sql);  


  $sqlFechasTrabajadas="INSERT INTO `fechas_trabajadas_empleados` ( `fechaIngreso`, `fechaSalida`, `id_empleado`, `actual`) VALUES ( '$fechaIngreso', ' $fechaSalida', '$id_empleado', '1')";
  $resultFechasTrabajadas=mysql_query($sqlFechasTrabajadas);

  echo $resultado= ($resultFechasTrabajadas)?'1':'2';
}

if($accion == "28"){
 
  $fechaIngreso=$_POST['fechaIngreso'];
  $fechaSalida=$_POST['fechaSalida'];
  $id_empleado=$_POST['id_empleado'];
  $id_fechasTrabajadas=$_POST['idFechasTrabajadas'];
  $identificador=$_POST['cuenta'];
  

      $sqlMayor="SELECT `id_fechasTrabajadas`, `fechaIngreso`, `fechaSalida`, `id_empleado`, `actual` FROM `fechas_trabajadas_empleados` WHERE id_empleado=$id_empleado ORDER BY fechaIngreso DESC LIMIT 1 ";
      $resultMayor = mysql_query($sqlMayor);
      $fechaMayor='0000-00-00';
      while($rowM = mysql_fetch_array($resultMayor)){
          $fechaMayor = $rowM['fechaIngreso'];
      }
      $identificador = ($fechaMayor <= $fechaIngreso)?2:$identificador;
  
  
  if($identificador==0){
   $sqlFechasTrabajadas="UPDATE `fechas_trabajadas_empleados` SET `fechaIngreso`='$fechaIngreso',`fechaSalida`='$fechaSalida',`id_empleado`='$id_empleado' WHERE `id_fechasTrabajadas`=$id_fechasTrabajadas";
   $resultFechasTrabajadas=mysql_query($sqlFechasTrabajadas);
   echo $resultado= ($resultFechasTrabajadas)?'3':'4';
   
 }else if($identificador==2){
    $sqlFechasTrabajadasVaciar="UPDATE `fechas_trabajadas_empleados` SET `actual`='0' WHERE `id_empleado`=$id_empleado";
   $resultFechasTrabajadasVaciar=mysql_query($sqlFechasTrabajadasVaciar);
   
   $sqlFechasTrabajadasLlenar="UPDATE `fechas_trabajadas_empleados` SET `actual`='1', `fechaIngreso`='$fechaIngreso', `fechaSalida`='$fechaSalida' WHERE `id_fechasTrabajadas`=$id_fechasTrabajadas";
   $resultFechasTrabajadasllenar=mysql_query($sqlFechasTrabajadasLlenar);
   echo $resultado= ($resultFechasTrabajadasllenar)?'5':'6';
   
   if($resultado==5){
    $sql="UPDATE `empleados` SET `fecha_ingreso`='$fechaIngreso',`fecha_salida`='$fechaSalida' WHERE `id_empleado`=$id_empleado ";
    $result =mysql_query($sql);  
  }
 }else{
   $sqlFechasTrabajadasVaciar="UPDATE `fechas_trabajadas_empleados` SET `actual`='0' WHERE `id_empleado`=$id_empleado";
   $resultFechasTrabajadasVaciar=mysql_query($sqlFechasTrabajadasVaciar);
   
   $sqlFechasTrabajadasLlenar="UPDATE `fechas_trabajadas_empleados` SET `actual`='1' WHERE `id_fechasTrabajadas`=$id_fechasTrabajadas";
   $resultFechasTrabajadasllenar=mysql_query($sqlFechasTrabajadasLlenar);
   echo $resultado= ($resultFechasTrabajadasllenar)?'5':'6';
   
   if($resultado==5){
    $sql="UPDATE `empleados` SET `fecha_ingreso`='$fechaIngreso',`fecha_salida`='$fechaSalida' WHERE `id_empleado`=$id_empleado ";
    $result =mysql_query($sql);  
  }

}

}
if($accion == "29"){
  $id=$_POST['idDF'];
  
  $sqlFechasTrabajadas="DELETE FROM `fechas_trabajadas_empleados`  WHERE `id_fechasTrabajadas`=$id";
  $resultFechasTrabajadas=mysql_query($sqlFechasTrabajadas);
  echo $resultado= ($resultFechasTrabajadas)?'10':'11';
  

}

if($accion == 80){
  
  include('../conexion2.php');
  
  require_once('../vendor/php-excel-reader/excel_reader2.php');
  
  require_once('../vendor/SpreadsheetReader.php');
  
  
  $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
  
  if(in_array($_FILES["file"]["type"],$allowedFileType)){
    
   $targetPath = 'subidas/empleados/'.$_FILES['file']['name'];
   move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
   
   $Reader = new SpreadsheetReader($targetPath);
   $con = $conexion;
    //   $sheetCount = count($Reader->sheets());
   $sheetCount = count($Reader->sheets());
   for($i=0;$i<$sheetCount;$i++)
   {
        //   echo $i;
    $Reader->ChangeSheet($i);
    //   var_dump($Reader);
    $cont=0;
    foreach ($Reader as $Row)
    {
      
      $nombresCompletos = (isset($Row[0]) || $Row[0]=='')? mysqli_real_escape_string($con,$Row[0]): '' ;
      if(trim($nombresCompletos)=='NOMBRES COMPLETOS'){
          goto fin;
      }
      
      $nombres = explode(" ", $nombresCompletos);
      $apellidos= $nombres[0]." ".$nombres[1];
      $nombres= $nombres[2]." ".$nombres[3];
        //  $apellidos = (isset($Row[0] )|| $Row[0]=='')?mysqli_real_escape_string($con,$Row[0]):'';
      
      $departamento = (isset($Row[1]) || $Row[1]=='')? mysqli_real_escape_string($con,$Row[1]): '' ;


      $sqlDepartamento="SELECT `id_departamento`, `nombre_departamento`, `descripcion`, `id_empresa` FROM `departamentos` WHERE nombre_departamento='$departamento' and id_empresa=$sesion_id_empresa    LIMIT 1";
      $resultDepartamento= mysql_query($sqlDepartamento);
      while($rowDepar=mysql_fetch_array($resultDepartamento)){
        $departamento= $rowDepar['id_departamento'];
        
      }
      if(!$resultDepartamento){$departamento='0';}

      $sucursal = (isset($Row[2]) || $Row[2]=='')? mysqli_real_escape_string($con,$Row[2]): '' ;
      
      
      $sqlSucursal="SELECT `id_sucursal`, `nombre_sucursal`, `id_empresa` FROM `surcursal` WHERE nombre_sucursal='$sucursal' AND  id_empresa=$sesion_id_empresa";
      $resultSucursal= mysql_query($sqlSucursal);
       $sucursal =0;
      while($rowSucur=mysql_fetch_array($resultSucursal)){
        $sucursal= $rowSucur['id_sucursal'];
        
      }
      if(!$resultSucursal){$sucursal='0';}
      $fechaIngreso = (isset($Row[3]) || $Row[3]=='')? mysqli_real_escape_string($con,$Row[3]): '' ;
      
      $cedula = (isset($Row[4]) || $Row[4]=='')? mysqli_real_escape_string($con,$Row[4]): '' ;
      
      $cargos = (isset($Row[5]) || $Row[5]=='')? mysqli_real_escape_string($con,$Row[5]): '' ;
      
      
      $sqlCargo[$cont]="SELECT `id_cargo`, `nombre_cargo`, `sueldo`, `id_departamento`, `estado`, `id_empleado`, `cedula`, `id_empresa` FROM `cargos`  WHERE nombre_cargo='$cargos' AND  id_empresa=$sesion_id_empresa LIMIT 1";
      $resultCargo[$cont]= mysql_query($sqlCargo[$cont]);
      $cargos='';
      while($rowCargo=mysql_fetch_array($resultCargo[$cont])){
    // echo '|';
       $cargos= $rowCargo['id_cargo'];
       
     } 


     if(!$resultCargo){$cargo='0';}

     $sueldo = (isset($Row[6]) || $Row[6]=='')? mysqli_real_escape_string($con,$Row[6]): 0 ;
     
     
     
     $idCiudad= ($ciudad=='')?0:$ciudad;
     $cargos= ($cargos=='')?0:$cargos;
     $sucursal= ($sucursal=='')?0:$sucursal;
     $hoy = date('Y-m-d');
     
      $query[$cont]="INSERT INTO `empleados`( `nombre`, `apellido`, `cedula`, `direccion`, `telefono`, `movil`, `fecha_nacimiento`, `email`, `tipo`, `estado`, `id_ciudad`, `fecha_registro`, `numero_cargas`, `estado_civil`, `posicion`, `cuenta_bancaria`, `nacionalidad`, `discapacidad`, `porcentaje_discapacidad`, `genero`, `tipo_sangre`, `licencia_conducir`, `foto`, `vive_con`, `toma_medicinas`, `causa_medicina`, `tiene_alergia`, `tipo_alergia`, `tiene_dolencia`, `tipo_dolencia`, `tiene_cirugias`, `tipos_cirugias`, `medico_personal`, `telefono_medico`, `mujer_ligada`, `calle_principal`, `calle_secundaria`, `casa_numeracion`, `departamento_numeracion`, `sector`, `parroquia`, `ciudad_domicilio`, `vive_en`, `vivienda_noDispone`, `referencia_domicilio`, `celular1`, `operadora1`, `celular2`, `operadora2`, `celular3`, `operadora3`, `facebook`, `twitter`, `instagram`, `youtube`, `tiktok`, `linkedln`, `numeroContrato`, `fechaContrato`, `tipoSalario`,  `centroCostos`, `fechaSalida`, `formaPago`, `decimoTercerSueldo`, `decimoCuartoSueldo`, `asumirIess`, `propietarioAportacion`, `lugar_nacimiento`, `fondos_reserva`, `alimentacion`, `vacaciones`, `otros_ingresos`, `prestamos_iess`, `impuesto_renta`, `retencion_judicial`, `asociacion`, `otros_descuentos`, `decimos`, `id_empresa`, `fecha_ingreso`, `fecha_salida`, `cargos`, `sucursal`, `tipo_categoria`,`departamento`) VALUES ('$nombres','$apellidos','$cedula','','','','0000-00-00','','','Activo','$idCiudad','$hoy','0','indefinido','Ocupado','','indefinido','','0','','','','','','','','','','','','','','','0','','','','','','','','0','','','','0','','0','','0','','','','','','','','','0000-00-00','','','0000-00-00','','','','','','','','0','0','0','0','0','0','0','0','Mensual','$sesion_id_empresa','0000-00-00','0000-00-00','$cargos','$sucursal','0','$departamento')";

     $resultados[$cont] = mysql_query($query[$cont]);
     
     if (! empty($resultados)) {
      $type = "success";
      $message = "Excel importado correctamente";
    } else {
      $type = "error";
      $message = "Hubo un problema al importar registros";
    }
            //  }
    $cont++;
  }
  fin:
  echo '1';
 
}
}
else
{ 
  $type = "error";
  $message = "El archivo enviado es invalido. Por favor vuelva a intentarlo";
   echo '2';
}
}
?>