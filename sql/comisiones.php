<?php

	require_once('../ver_sesion.php');

	//Start session
	session_start();

	//Include database connection details
	require_once('../conexion.php');

	
        $accion = $_POST['txtAccion'];
 
   // GUARDA LAS COMISIONES PAGINA: nuevaComision.php
   if($accion == '1'){
     try
     {
        $id_empleado = $_POST['txtIdCliente'];
        
        if($id_empleado != "" ){
            
            $ano = date("Y");
            $mes = date("m");            
            $fecha = date("Y-m-d");

            $id_empleado = $_POST['txtIdCliente'];
            $txtValor = ($_POST['txtValor']);
            $txtObservaciones = ucwords($_POST['txtObservaciones']);

           //permite sacar el id maximo de registro_diario
            try {
                    $sqli="Select max(id_comision) From comisiones;";
                    $result=mysql_query($sqli) or die ("<div class='transparent_ajax_error'><p>Error al sacar el id max: ".mysql_error()."</p></div>");
                    $id_comision=0;
                    while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                    {
                                    $id_comision=$row['max(id_comision)'];
                    }
                    $id_comision++;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }

          $sql = "insert into comisiones (id_comision, valor, fecha, id_empleado, ano, mes, descripcion, estado) values ('".$id_comision."','".$txtValor."','".$fecha."','".$id_empleado."','".$ano."','".$mes."','".$txtObservaciones."', 'Activo')";
          $resp = mysql_query($sql);
          if($resp){
              ?> <div class='transparent_ajax_correcto'><p>Registro insertado correctamente.</p></div> <?php
              }
         else{
             ?> <div class='transparent_ajax_error'><p>Error al guarda los datos: problema con la consulta <?php echo "\n".mysql_error(); ?></p></div> <?php
             }          
      }
      else{
          ?> <div class="transparent_ajax_error"><p>Fallo en el envio del Formulario: No hay datos <?php echo "\n".mysql_error(); ?></p></div> <?php
      }

    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }
   }



   // GUARDAR MODIFICACION C0MISION PAGINA: ajax/modificarComision.php
   if($accion == '2'){
     try
     {
        $id_empleado = $_POST['txtIdCliente'];

        if($id_empleado != "" ){

            $ano = $_POST['txtAno'];
            $mes = $_POST['txtMes'];
            $idComision = $_POST['txtIdComision'];
            $txtValor = $_POST['txtValor'];
            $txtObservaciones = ucwords($_POST['txtObservaciones']);

            //$mes1=floatval($mes);// elima el cero
            $mesletra = "";
            switch($mes)
            {
            case Enero:$mesletra="01";break;
            case Febrero:$mesletra="02";break;
            case Marzo:$mesletra="03";break;
            case Abril:$mesletra="04";break;
            case Mayo:$mesletra="05";break;
            case Junio:$mesletra="06";break;
            case Julio:$mesletra="07";break;
            case Agosto:$mesletra="08";break;
            case Septiembre:$mesletra="09";break;
            case Octubre:$mesletra="10";break;
            case Noviembre:$mesletra="11";break;
            case Diciembre:$mesletra="12";break;
            }
            $mesnuevo = $mesletra;

          $sql = "update comisiones set  valor='".$txtValor."', id_empleado='".$id_empleado."', ano='".$ano."', mes='".$mesnuevo."', descripcion='".$txtObservaciones."' where id_comision='".$idComision."' ;";
          $resp = mysql_query($sql);
          if($resp){
              ?> <div class='transparent_ajax_correcto'><p>Registro modificado correctamente.</p></div> <?php
              }
         else{
             ?> <div class='transparent_ajax_error'><p>Error al guarda los datos: problema con la consulta <?php echo "\n".mysql_error(); ?></p></div> <?php
             }
      }
      else{
          ?> <div class="transparent_ajax_error"><p>Fallo en el envio del Formulario: No hay datos <?php echo "\n".mysql_error(); ?></p></div> <?php
      }

    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }
   }

   if($accion == '3'){
     try
     {  
          $txtIdComision = $_POST['txtIdComision'];
          $sqlD = "update comisiones  set estado='Eliminado' where id_comision='".$txtIdComision."';";
          $respD = mysql_query($sqlD);
          if($respD){
              echo "Registro Eliminado correctamente.";
              }
         else{
              echo "Error al eliminar: problema con la consulta"."\n".mysql_error();
             }

     }catch (Exception $e) {
        // Error en algun momento.
           ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
        }

     }    

     
   if($accion == '4'){
       // busca si el empleado ya ha sido asignado su comision mensual PAGINA: nuevaComision.php
     try
     {
          $idempleado = $_POST['idEmpleado'];
          $txtano1 = $_POST['ano'];
          $txtmes1 = $_POST['mes'];
          $sql = "SELECT id_empleado, mes , ano from comisiones where id_empleado='".$idempleado."' AND comisiones.`estado`='Activo';";
          $resp = mysql_query($sql);
          $entro=0;
          while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
            {
                $var=$row["id_empleado"];
                $ano1=$row["ano"];
                $mes1=$row["mes"];
            }
            $txtano = strtolower($txtano1);
            $txtmes = date('m');
            $ano = strtolower($ano1);
            $mes = strtolower($mes1);                      
          if($var==$idempleado){
               if($var==""&&$idempleado==""){
                   echo "entro";
                     $entro=0;
                  }else if($txtano== $ano && $txtmes == $mes){
                      $entro=1;
                  }else{
                      $entro=0;
                  }
          }
         echo $entro;


    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }
   }

?>