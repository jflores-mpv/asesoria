<?php
error_reporting(0);
	//Start session
session_start();
	//Include database connection details
require_once('../conexion.php');
require_once('encriptar.php');
require_once('../clases/contrasenas.php');
$sesion_id_empresa= $_SESSION["sesion_id_empresa"];
$sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];

// echo "sesion".$sesion_tipo_empresa;

        try{
            
          function safe($value){
           return mysql_real_escape_string($value);
         }

    $txtLogin = safe($_POST['txtLogin']);

    $txtPassword= $_POST['txtPassword'];//safe($_POST['txtPassword']);
    
    if($txtLogin != "" && $txtPassword != ""){

    $user_hashed_password =  $encriptar($txtPassword);

    $id_usuario="";
    $nombre="";
    $apellido="";  
    $tipo="";
    $estado="";       
    $login="";
    $password="";

     $sql="SELECT

     usuarios.`id_usuario` AS usuarios_id_usuario,
     usuarios.`id_empleado` AS usuarios_id_empleado,
     usuarios.`login` AS usuarios_login,
     usuarios.`password` AS usuarios_password,
     usuarios.`tipo` AS usuarios_tipo,
     usuarios.`estado` AS usuarios_estado,
     usuarios.`fecha_registro` AS usuarios_fecha_registro,
     usuarios.`permisos` AS usuarios_permisos,
     usuarios.`id_punto` AS usuarios_id_punto,
     usuarios.`id_est` AS usuarios_id_est,
     
     usuarios.`id_permiso_asiento_contable` AS usuarios_id_permiso_asiento_contable,
     usuarios.`id_permiso_plan_cuenta` AS usuarios_id_permiso_plan_cuenta,
     usuarios.`reportes_contables` AS usuarios_reportes_contables,
     usuarios.`id_permisos_bancos` AS usuarios_id_permisos_bancos,
     
     emision.`id` AS emision_id,
     emision.`id_est` AS emision_id_est,
     emision.`ambiente` AS emision_ambiente,
     emision.`tipoFacturacion` AS emision_tipoFacturacion,
     emision.`tipoEmision` AS emision_tipoEmision,
     emision.`codigo` AS emision_codigo,
     emision.`SOCIO` AS emision_SOCIO,
     establecimientos.`id` AS establecimiento_id,
     establecimientos.`codigo` AS establecimiento_codigo
     
    FROM
    
    `usuarios` usuarios 
    INNER JOIN `emision` emision ON  usuarios.`id_punto`=emision.`id` 
    INNER JOIN `establecimientos` establecimientos ON  establecimientos.`id`=emision.`id_est` 

    WHERE  usuarios.`login`='".$txtLogin."' and usuarios.`id_empresa`='".$sesion_id_empresa."' and  
    usuarios.`password`='".$user_hashed_password."' ;";

    $result = mysql_query($sql);   
    $numero_filas = mysql_num_rows($result);
   
        //    echo $numero_filas;
        
          while($row=mysql_fetch_array($result)){
              
    //      echo "llega";
    
            $id_usuario = $row["usuarios_id_usuario"];
            $id_empleado = $row["empleados_id_empleado"];
            $nombre = $row["empleados_nombre"];
            $apellido = $row["empleados_apellido"];
            $login = $row["usuarios_login"];
            $password = $row["usuarios_password"];
            $tipo = $row["usuarios_tipo"];
            $estado = $row["usuarios_estado"];
            $id_punto = $row["usuarios_id_punto"];
            $id_est = $row["usuarios_id_est"];
            $permisos = $row["usuarios_permisos"];            
            $empleado_id_empresa = $row["empleados_id_empresa"];
            //echo $estado;
            //$revela = $row["usuarios_password"];

            // ID permisos a los modulos
            $usuarios_id_permiso_asiento_contable = $row["usuarios_id_permiso_asiento_contable"];
            $usuarios_id_permiso_plan_cuenta = $row["usuarios_id_permiso_plan_cuenta"];
            $usuarios_reportes_contables = $row["usuarios_reportes_contables"];
            $usuarios_id_permisos_bancos = $row["usuarios_id_permisos_bancos"];
            
            $emision_ambiente = $row["emision_ambiente"];
            $emision_tipoFacturacion = $row["emision_tipoFacturacion"];
            $emision_tipoEmision = $row["emision_tipoEmision"];
            $emision_codigo= $row["emision_codigo"];
            $emision_SOCIO= $row["emision_SOCIO"];
            
            $establecimiento_codigo= $row["establecimiento_codigo"];
            
         }


          $resultPAC = mysql_query("select Redondeo from empresa where id_empresa=".$sesion_id_empresa);
          $row=mysql_fetch_array($resultPAC);
          $Redondeo = $row["Redondeo"];
        // PERMISOS PARA ASIENTOS CONTABLES
          $sqlPAC = "SELECT * FROM permisos_asientos_contables WHERE id_permiso_asiento_contable = '".$usuarios_id_permiso_asiento_contable."'; ";
          $resultPAC = mysql_query($sqlPAC);
          while($rowPAC=mysql_fetch_array($resultPAC)){
            $permisos_asientos_contables_guardar = $rowPAC["guardar"];
            $permisos_asientos_contables_modificar = $rowPAC["modificar"];
            $permisos_asientos_contables_eliminar = $rowPAC["eliminar"];
          }

        // PERMISOS PLAN DE CUENTAS
          $sqlPPC = "SELECT * FROM permisos_plan_cuentas WHERE id_permiso_plan_cuenta = '".$usuarios_id_permiso_plan_cuenta."'; ";
          $resultPPC = mysql_query($sqlPPC);
          while($rowPPC=mysql_fetch_array($resultPPC)){
            $permisos_plan_cuentas_guardar = $rowPPC["guardar"];
            $permisos_plan_cuentas_modificar = $rowPPC["modificar"];
            $permisos_plan_cuentas_eliminar = $rowPPC["eliminar"];
          }

        // PERMISOS BANCOS
          $sqlPB = "SELECT * FROM permisos_bancos WHERE id_permisos_bancos = '".$usuarios_id_permisos_bancos."'; ";
          $resultPB = mysql_query($sqlPB);
          while($rowPB=mysql_fetch_array($resultPB)){
            $permisos_bancos_guardar = $rowPB["guardar"];
            $permisos_bancos_modificar = $rowPB["modificar"];
            $permisos_bancos_eliminar = $rowPB["eliminar"];
          }

   // $sqlModulos="Select p.guardar, p.editar, p.eliminar, p.reportes, p.id_modulo as id, m.modulo as nombre From permisos_usuarios p INNER JOIN modulos m ON p.id_modulo=m.id where id_usuario='".$id_usuario."';";

          $sqlModulos="Select p.* From permisos_usuarios p INNER JOIN modulos m ON p.id_modulo=m.id where id_usuario='".$id_usuario."';";

          $resultModulos=mysql_query($sqlModulos);
          $permisos = [];
          while($rowModulos=mysql_fetch_array($resultModulos))
          {  
            array_push($permisos, json_encode($rowModulos));
          }   
          $data["permisos"] = $permisos;
          $_SESSION['permisos'] = json_encode($data);


          if($password!="" && $login!="" ){
//echo "entro";
            if($estado == "Activo"){
//echo "activo";
            //variables de secion
              $_SESSION['sesion_id_usuario'] = $id_usuario;
              $_SESSION['sesion_id_empleado'] = $id_empleado;
              $_SESSION['sesion_tipo'] = $tipo;
              $_SESSION['sesion_estado'] = $estado;
              $_SESSION['sesion_nombre'] = $nombre;
              $_SESSION['sesion_apellido'] = $apellido;
              $_SESSION['sesion_permisos'] = $permisos;
              $_SESSION['sesion_logo_sistema'] = $logo_sistema;
              $_SESSION['Redondeo'] = $Redondeo;
              $_SESSION['userpunto']= $id_punto;
              $_SESSION['userest']= $id_est;
              
              $_SESSION['usuarios_login']= $login;


            // permisos de modulos
            // PERMISOS ASIENTOS CONTABLES
              $_SESSION['sesion_asientos_contables'] = $usuarios_id_permiso_asiento_contable;
              $_SESSION['sesion_asientos_contables_guardar'] = $permisos_asientos_contables_guardar;
              $_SESSION['sesion_asientos_contables_modificar'] = $permisos_asientos_contables_modificar;
              $_SESSION['sesion_asientos_contables_eliminar'] = $permisos_asientos_contables_eliminar;

            // PERMISOS PLAN CUENTAS
              $_SESSION['sesion_plan_cuentas'] = $usuarios_id_permiso_plan_cuenta;
              $_SESSION['sesion_plan_cuentas_guardar'] = $permisos_plan_cuentas_guardar;
              $_SESSION['sesion_plan_cuentas_modificar'] = $permisos_plan_cuentas_modificar;
              $_SESSION['sesion_plan_cuentas_eliminar'] = $permisos_plan_cuentas_eliminar;

            // PERMISOS REPORTES CONTABLES
              $_SESSION['sesion_reportes_contables'] = $usuarios_reportes_contables;

            // PERMISOS BANCOS
              $_SESSION['sesion_bancos'] = $usuarios_id_permisos_bancos;
              $_SESSION['sesion_bancos_guardar'] = $permisos_bancos_guardar;
              $_SESSION['sesion_bancos_modificar'] = $permisos_bancos_modificar;
              $_SESSION['sesion_bancos_eliminar'] = $permisos_bancos_eliminar;




              $_SESSION['emision_ambiente']= $emision_ambiente;
              $_SESSION['emision_tipoFacturacion']= $emision_tipoFacturacion;
              $_SESSION['emision_tipoEmision']= $emision_tipoEmision;
                $_SESSION['emision_SOCIO']= $emision_SOCIO;
              $_SESSION['emision_codigo']= $emision_codigo;
              $_SESSION['establecimiento_codigo']= $establecimiento_codigo;




            //PARA EL SUPER ADMINISTRADOR
         //   if($sesion_tipo_empresa=="Super Administrador" ){ //$tipo
          //      echo "1";                
          //  }


            //PARA TIPOS DE EMPRESAS CONTABILIDAD
              if($sesion_tipo_empresa == "Contabilidad" ){
                echo "2";
              }
            //PARA TIPOS DE EMPRESAS COMERCIO
              if($sesion_tipo_empresa == "Comercio" ){
                echo "3";
              }
            //PARA TIPOS DE EMPRESAS HOTELES
              if($sesion_tipo_empresa == "Hoteles" ){
                echo "4";
              }
            //PARA TIPOS DE EMPRESAS RESTAURANTES
              if($sesion_tipo_empresa == "Restaurantes" ){
                echo "5";
              }
            //PARA TIPOS DE EMPRESAS SEGURIDAD
              if($sesion_tipo_empresa == "Seguridad" ){
                echo "6";
              }
            //PARA TIPOS DE EMPRESAS PUNTO DE VENTA
              if($sesion_tipo_empresa == "Punto de Venta" ){
                echo "7";
              }
            //PARA TIPOS DE EMPRESAS PRODUCCION
              if($sesion_tipo_empresa == "Producción" ){
                echo "8";
              }
            //PARA TIPOS DE EMPRESAS CONTADOR
              if($sesion_tipo_empresa == "Contador" ){
                echo "9";
              }
            //PARA TIPOS DE EMPRESAS OTROS
              if($sesion_tipo_empresa == "LAVANDERIA" ){
                echo "10";
              }
            //PARA TIPOS DE EMPRESAS CONDOMINIOS
              if($sesion_tipo_empresa == "Condominios" ){
                echo "11";
              }
            //PARA TIPOS DE EMPRESAS COOPERATIVAS DE TRANSPORTE
          //  if($sesion_tipo_empresa == "Cooperativas de transporte" ){
            //    echo "12";
        //    }

              if($sesion_tipo_empresa == "1" ){
                echo "12";
              }
              if($sesion_tipo_empresa == "2" ){
                echo "13";
              }
              if($sesion_tipo_empresa == "3" ){
                echo "14";
              }
              if($sesion_tipo_empresa == "4" ){
                echo "15";
              }
              if($sesion_tipo_empresa == "5" ){
                echo "16";
              }
           //   if($sesion_tipo_empresa == "sexto" ){
              if($sesion_tipo_empresa == "6" || $sesion_tipo_empresa == "HOTEL"  || $sesion_tipo_empresa == "RESTAURANTE" || $sesion_tipo_empresa == "COLEGIO" || $sesion_tipo_empresa == "TALLER" || $sesion_tipo_empresa == "URBANIZACION"){
                echo "17";
               // echo "entro en seis"
              }
              if($sesion_tipo_empresa == "Profesor" ){
                echo "19";
              }
              if($sesion_tipo_empresa == "7" ){
                echo "20";
              }
              if($sesion_tipo_empresa == "HOTELES" ){
                echo "21";
              }
            if($sesion_tipo_empresa == "RESTAURANTES" ){
                echo "22";
            }
              
            if($sesion_tipo_empresa=="Super Administrador" ){ //$tipo
                echo "23";                
            }
            
            if($sesion_tipo_empresa=="CONTAWEB" ){ //$tipo
                echo "50";                
            }
            
            if($sesion_tipo_empresa=="CONTRICAPSAS" ){ //$tipo
                echo "51";                
            }
            if($sesion_tipo_empresa=="29" ){ //$tipo
                echo "24";                
            }
            
            if($sesion_tipo_empresa=="100" ){ 
                echo "100";                
            }
            

          }else{
            //session_destroy();
            $_SESSION["sesion_id_usuario"] = "";
            $_SESSION["sesion_id_empleado"] = "";
            $_SESSION["sesion_tipo"] = "";
            $_SESSION["sesion_estado"] = "";
            $_SESSION["sesion_nombre"] = "";
            $_SESSION["sesion_apellido"] = "";
            $_SESSION["sesion_permisos"] = "";
            $_SESSION["sesion_logo_sistema"] = "";

            $_SESSION["sesion_asientos_contables"] = "";
            $_SESSION["sesion_plan_cuentas"] = "";
            $_SESSION["sesion_reportes_contables"] = "";



            unset($_SESSION["sesion_id_usuario"]);
            unset($_SESSION["sesion_id_empleado"]);
            unset($_SESSION["sesion_tipo"]);
            unset($_SESSION["sesion_estado"]);
            unset($_SESSION["sesion_nombre"]);
            unset($_SESSION["sesion_apellido"]);
            unset($_SESSION["sesion_permisos"]);
            unset($_SESSION["sesion_logo_sistema"]);

            unset($_SESSION["sesion_asientos_contables"]);
            unset($_SESSION["sesion_plan_cuentas"]);
            unset($_SESSION["sesion_reportes_contables"]);

            ?> <div class='transparent_ajax_error'><p>Usted no puede acceder al sistema porque su cuenta no esta Activa o ha sido Eliminada. Porfavor consulte con el Administrador</p></div> <?php

          }

        }else{
        //session_destroy();
         $_SESSION["sesion_id_usuario"] = "";
         $_SESSION["sesion_id_empleado"] = "";
         $_SESSION["sesion_tipo"] = "";
         $_SESSION["sesion_estado"] = "";
         $_SESSION["sesion_nombre"] = "";
         $_SESSION["sesion_apellido"] = "";
         $_SESSION["sesion_permisos"] = "";
         $_SESSION["sesion_logo_sistema"] = "";

         $_SESSION["sesion_asientos_contables"] = "";
         $_SESSION["sesion_plan_cuentas"] = "";
         $_SESSION["sesion_reportes_contables"] = "";



         unset($_SESSION["sesion_id_usuario"]);
         unset($_SESSION["sesion_id_empleado"]);
         unset($_SESSION["sesion_tipo"]);
         unset($_SESSION["sesion_estado"]);
         unset($_SESSION["sesion_nombre"]);
         unset($_SESSION["sesion_apellido"]);
         unset($_SESSION["sesion_permisos"]);
         unset($_SESSION["sesion_logo_sistema"]);

         unset($_SESSION["sesion_asientos_contables"]);
         unset($_SESSION["sesion_plan_cuentas"]);
         unset($_SESSION["sesion_reportes_contables"]);
         ?> <div class='alert alert-danger'><p>Datos incorrectos. Verifique su Usuario y su Contrase&ntilde;a</p></div> <?php
         

       }

     }else{
    //session_destroy();
      $_SESSION["sesion_id_usuario"] = "";
      $_SESSION["sesion_id_empleado"] = "";
      $_SESSION["sesion_tipo"] = "";
      $_SESSION["sesion_estado"] = "";
      $_SESSION["sesion_nombre"] = "";
      $_SESSION["sesion_apellido"] = "";
      $_SESSION["sesion_permisos"] = "";
      $_SESSION["sesion_logo_sistema"] = "";

      $_SESSION["sesion_asientos_contables"] = "";
      $_SESSION["sesion_plan_cuentas"] = "";
      $_SESSION["sesion_reportes_contables"] = "";



      unset($_SESSION["sesion_id_usuario"]);
      unset($_SESSION["sesion_id_empleado"]);
      unset($_SESSION["sesion_tipo"]);
      unset($_SESSION["sesion_estado"]);
      unset($_SESSION["sesion_nombre"]);
      unset($_SESSION["sesion_apellido"]);
      unset($_SESSION["sesion_permisos"]);
      unset($_SESSION["sesion_logo_sistema"]);

      unset($_SESSION["sesion_asientos_contables"]);
      unset($_SESSION["sesion_plan_cuentas"]);
      unset($_SESSION["sesion_reportes_contables"]);
      ?> <div class='alert alert-danger'><p>Los campos estan vacios</p></div> <?php

    }

  }catch(Exception $e){

    ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
  }

  ?>   
