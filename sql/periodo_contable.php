<?php

	require_once('../ver_sesion.php');

	//Start session
	session_start();

	//Include database connection details
	require_once('../conexion.php');

 
 

try
    {
        $accion = $_POST['accion'];
       
       // consulta para sacar los periodos contables  dependiendo a lo que se seleccion en los combobox y luego mostrarlos en las barras
        if($accion == 1){               
            $id_desde = $_POST['desde'];
            $id_hasta = $_POST['hasta'];

            if($id_desde != "" & $id_hasta != ""){
               
               //$consulta4="SELECT (DATE_FORMAT(fecha_desde, '%M %Y ')) fecha_desde, (DATE_FORMAT(fecha_hasta, '%M %Y ')) fecha_hasta, ingresos, gastos FROM periodo_contable WHERE id_periodo_contable ='".$id_desde."'";
               $consulta4="SELECT id_periodo_contable, fecha_desde, fecha_hasta, ingresos, gastos FROM periodo_contable WHERE id_periodo_contable ='".$id_desde."'";
               $result=mysql_query($consulta4);
                while($row1=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                    {
                        $desde=$desde."?".$row1['fecha_desde']."?".$row1['fecha_hasta']."?".$row1['ingresos']."?".$row1['gastos'];
                    }

               $consulta5="Select id_periodo_contable, fecha_desde, fecha_hasta, ingresos, gastos FROM periodo_contable WHERE id_periodo_contable = '".$id_hasta."'";
               $result=mysql_query($consulta5);
                while($row2=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                    {
                        $hasta=$hasta."?".$row2['fecha_desde']."?".$row2['fecha_hasta']."?".$row2['ingresos']."?".$row2['gastos'];
                    }

                    echo $cadenaMayor=$desde."/".$hasta;
          
              }else{
             ?> <div class='transparent_ajax_error'><p>Error: No hay Datos </p></div> <?php
             }
      }

      // consulta para mostar los periodos contables en los combobox
       if($accion == 2){
           $cadena="";
           //consulta
            try {
               $consulta5="Select * From periodo_contable WHERE estado = 'Inactivo' order by fecha_desde desc; ";
                $result=mysql_query($consulta5);
                while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                    {
                        $cadena=$cadena."?".$row['id_periodo_contable']."?".$row['fecha_desde']."?".$row['fecha_hasta'];
                    }
               echo "".$cadena;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
          }

    // guarda los datos del periodo contable
          if($accion == 3){
        $fecha_inicio = $_POST['txtFechaIngreso'];
        $fecha_fin = $_POST['txtFechaSalida'];
        if($fecha_inicio != "" && $fecha_fin!= ""){

           $estado= "Activo";
           $ingresos= "0";
           $gastos= "0";

           //permite sacar el id maximo de categorias
            try {
                    $sqli="Select max(id_periodo_contable) From periodo_contable";
                    $result1=mysql_query($sqli);
                    $id_periodo_contable=0;
                    while($row1=mysql_fetch_array($result1))//permite ir de fila en fila de la tabla
                    {
                                    $id_periodo_contable=$row1['max(id_periodo_contable)'];
                    }
                    $id_periodo_contable++;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }

            $sql="Select estado From periodo_contable WHERE estado = 'Activo'";
            $result=mysql_query($sql);
            $estado2="";
            while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
            {
                            $estado2=$row['estado'];
            }

            if($estado2 != 'Activo'){
                $sql = "insert into periodo_contable (id_periodo_contable, fecha_desde, fecha_hasta, estado, ingresos, gastos) values ('".$id_periodo_contable."','".$fecha_inicio."','".$fecha_fin."','".$estado."','".$ingresos."','".$gastos."')";
                $resp = mysql_query($sql);
            }
          if($resp){
              ?> <div class='transparent_ajax_correcto'><p>Registro insertado correctamente.</p></div> <?php
              }
         else{
             ?> <div class='transparent_ajax_error'><p>Error al guarda los datos: Posiblemente ya hay un Periodo Contable activo.</p></div> <?php
             }

      }
      else{
          ?> <div class="transparent_ajax_error"><p>Fallo en el envio del Formulario: No hay datos</p></div> <?php
      }

      }
      /* ELIMINAR
       // consulta para sacar el periodo contable activo y actual
       if($accion == 4){
           
           $cadena13="";
           //consulta
            try {
               $sql13="select *from periodo_contable where estado ='Activo' order by id_periodo_contable asc";
                $result13=mysql_query($sql13);
                while($row13=mysql_fetch_array($result13))//permite ir de fila en fila de la tabla
                    {
                        $cadena13=$cadena13."?".$row13['id_periodo_contable']."?".$row13['fecha_desde']."?".$row13['fecha_hasta'];
                    }
               echo "".$cadena13;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
          }
          */

        
        if($accion == 5){
           //saca las fechas desde y hasta del periodo seleccionado
           $id_periodo_contable = $_POST['id_periodo_contable'];
           $cadena="";          
            try {
               $consulta5="SELECT fecha_desde, fecha_hasta FROM periodo_contable where id_periodo_contable='".$id_periodo_contable."';";
                $result=mysql_query($consulta5);
                while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                    {
                        $cadena=$cadena.$row['fecha_desde']."?".$row['fecha_hasta'];
                    }
               echo $cadena;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
          }

          if($accion == 6){
          //guarda modificacion periodo contable
          try
            {
            $id_periodo_contable = $_POST['txtIdPeriodoContable'];
            $fecha_desde = $_POST['txtFechaDesde'];
            $fecha_hasta = $_POST['txtFechaHasta'];
            $estado = $_POST['txtEstado'];
            $ingresos = $_POST['txtIngresos'];
            $gastos = $_POST['txtGastos'];

                if($id_periodo_contable != ""){

                     if($fecha_desde != ""){

                         if($fecha_hasta != ""){

                             if($estado != ""){

                                 if($ingresos != ""){

                                     if($gastos != ""){

                                        $id_periodo_contable = $_POST['txtIdPeriodoContable'];
                                        $fecha_desde = $_POST['txtFechaDesde'];
                                        $fecha_hasta = $_POST['txtFechaHasta'];
                                        $estado = $_POST['txtEstado'];
                                        $ingresos = $_POST['txtIngresos'];
                                        $gastos = $_POST['txtGastos'];

                                        $sql = "update periodo_contable set  fecha_desde='".$fecha_desde."', fecha_hasta='".$fecha_hasta."', estado='".$estado."', ingresos='".$ingresos."', gastos='".$gastos."' where id_periodo_contable='".$id_periodo_contable."';";

                                        $resp = mysql_query($sql);

                                        if($resp){
                                              ?>8<?php
                                              }
                                        else{
                                             ?>7<?php
                                             }

                                     }else{ ?> 6<?php  }

                                 }else{ ?> 5<?php  }

                             }else{ ?> 4<?php  }

                         }else{ ?> 3<?php }

                    }else{ ?> 2<?php }

               }else{ ?> 1<?php }

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
          }


          if($accion == "7"){
            // Cierra PERIODO CONTABLE CAMBIANDO EL ESTADO EN ACTIVO O INACTIVO PAGINA: verPeriodoContable.php
                try
                 {
                    $id_periodo=$_POST['id_periodo'];
                    $sqlp="update periodo_contable set estado='Inactivo' where id_periodo_contable='".$id_periodo."'; ";
                    $result=mysql_query($sqlp);
                  if($result){
                      ?> <div class='transparent_ajax_correcto'><p>Periodo Cerrado.</p></div> <?php
                      }
                 else{
                     ?> <div class='transparent_ajax_error'><p>Error al Cerrar el Periodo Contable: problemas con la consulta <?php echo "\n".mysql_error(); ?></p></div> <?php
                     }
                }catch (Exception $e) {
                // Error en algun momento.
                   ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
                }
            }


            if($accion == "8"){
            // Abre PERIODO CONTABLE CAMBIANDO EL ESTADO EN ACTIVO O INACTIVO PAGINA: verPeriodoContable.php
                try
                 {
                    $id_periodo=$_POST['id_periodo'];
                    $sqlp="update periodo_contable set estado='Activo' where id_periodo_contable='".$id_periodo."'; ";
                    $result=mysql_query($sqlp);
                  if($result){
                      ?> <div class='transparent_ajax_correcto'><p>Periodo Activo.</p></div> <?php
                      }
                 else{
                     ?> <div class='transparent_ajax_error'><p>Error al Activar el Periodo Contable: problemas con la consulta <?php echo "\n".mysql_error(); ?></p></div> <?php
                     }
                }catch (Exception $e) {
                // Error en algun momento.
                   ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
                }
            }

            if($accion == "9"){
                $periodo = $_POST['id_periodo'];
                $consulta6="delete from `periodo_contable` where id_periodo_contable='".$periodo."';";
                $result6=mysql_query($consulta6);
                if($result6){                    
                    echo "Se han eliminado el Periodo Contable correctamente";
                    
                }else{
                    echo "Error al Eliminar el Periodo Contable";
                }

           }


     
    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error grave: <?php echo "".$e ?></p></div> <?php
    }
?>

