<?php

    //Start session
    session_start();

    //Include database connection details
    require_once('../conexion.php');
     $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
   

     try
     {
        $accion = $_POST['accion'];        
       
        
        if($accion == 1){
            //saca el porcentajes de retencion dependiendo de lo que se seleccione del combobox
           $cadena="";
           $codigo = $_POST['codigo'];
           
           //consulta
            try {
               $consulta5="SELECT * FROM porcentaje_retencion WHERE id_porcentaje_retencion='".$codigo."';";
                $result=mysql_query($consulta5);
                while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                    {
                        $cadena=$row['porcentaje'];
                    }
               echo $cadena;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
          }

          if($accion == 2){
            //saca los porcentajes de retencion y los muestra en el combobox
           $cadena="";
            try {
               $consulta5="SELECT * FROM porcentaje_retencion order by porcentaje asc;";
                $result=mysql_query($consulta5);
                while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                    {
                        $cadena = $cadena.$row['id_porcentaje_retencion']."-".$row['porcentaje']."-";
                    }
               echo $cadena;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
          }

          if($accion == '3'){
              // GUARDAR EN LA TABLE Porcentajes de Retenciones PAGINA: porcentajesRetencion.php          
         try
         {
             
             
            $estRetencion = $_POST['estRetencion'];
            $emiRetencion = $_POST['emiRetencion'];   
            $secuenciaRetencion = $_POST['secuenciaRetencion'];
            $autRetencion = $_POST['autRetencion']; 
            


              $sql = "insert into retenciones ( `estabRetencion1`, `ptoEmiRetencion1`, `secRetencion1`, `autRetencion1`, `fechaEmiRet1`, `id_empresa`) 
              values ('".$estRetencion."','".$emiRetencion."','".$secuenciaRetencion."','".$autRetencion."','".$autRetencion."','".$sesion_id_empresa."');";
              $resp = mysql_query($sql);
            //  echo $sql;
              if($resp) { echo 1; }
                    else{echo 2; }
      

        }catch (Exception $e) {
        // Error en algun momento.
           ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
        }
       }




  
if($accion == '4'){
        
    $estRetencion = $_POST['estRetencion'];
    $emiRetencion = $_POST['emiRetencion'];   
    $secuenciaRetencion = $_POST['secuenciaRetencion'];
    $autRetencion = $_POST['autRetencion']; 

   
          $sqlm = "update retenciones set secRetencion1='".$secuenciaRetencion."'
          where id_empresa='".$sesion_id_empresa."' and estabRetencion1='".$estRetencion."' and ptoEmiRetencion1='".$emiRetencion."'; ";
          
          $respm = mysql_query($sqlm);
                if($respm)   { echo 'Datos actualizados correctamente'; }
                        else{echo 'Revise los datos'; }
}
       

       if($accion == '5'){
           // ELIMINA DE LA TABLE Porcentajes de Retenciones PAGINA: porcentajesRetencion.php
         try
         {
              $id = $_POST['id'];
              $sqD = "delete from porcentaje_retencion where id_porcentaje_retencion='".$id."';";
                    if(!mysql_query($sqD))
                            {echo "<div class='transparent_ajax_error'><p>Ocurrio un error al eliminar: \n".mysql_error()."</p></div>";}
                    else { echo "<div class='transparent_notice'><p>El registro ha sido Eliminado.</p></div>"; }

         }catch (Exception $e) {
            // Error en algun momento.
               ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
            }

         }

         

    }
    catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }

?>