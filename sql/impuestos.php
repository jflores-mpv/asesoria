<?php

	require_once('../ver_sesion.php');

	//Start session
	session_start();

	//Include database connection details
	require_once('../conexion.php');

	$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
        $accion = $_POST['txtAccion'];

   
   if($accion == '1'){
       // GUARDA LOS DATOS EN LA TABLE impuestos PAGINA: ajax/impuestos.php
     try
     {      
        $numeroFila = $_POST['numeroFilaSelec'];
        $txtPorcentaje = $_POST['txtPorcentaje'.$numeroFila];
        $txtEstado = $_POST['txtEstado'.$numeroFila];
        $txtCodigo = $_POST['txtCodigo'.$numeroFila];
        $cmbCuentaContable = $_POST['cmbCuentaContableI'];
        
        if($txtPorcentaje != "" & $txtEstado !=""){

           //permite sacar el id maximo de impuestos
            try {
                    $sqli="Select max(id_iva) From impuestos";
                    $result=mysql_query($sqli);
                    $id_iva=0;
                    while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                    {
                            $id_iva=$row['max(id_iva)'];
                    }
                    $id_iva++;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }

          $sql = "insert into impuestos (id_iva, iva, estado, id_empresa, id_plan_cuenta,codigo) values 
          ('".$id_iva."','".$txtPorcentaje."','".$txtEstado."', '".$sesion_id_empresa."', '".$cmbCuentaContable."', '".$txtCodigo."')";
          $resp = mysql_query($sql);
          
          if($resp){
              ?> <div class='transparent_ajax_correcto'><p>Registro insertado correctamente.</p></div> <?php
              }
         else{
             ?> <div class='transparent_ajax_error'><p>Error al guarda los datos: problema con la consulta <?php echo "\n".mysql_error(); ?></p></div> <?php
             }          
      }
      else{
          ?> <div class="transparent_ajax_error"><p>Fallo en el envio del Formulario: No hay datos</p></div> <?php
      }

    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }
   }



   
   if($accion == '2'){
       // MODIFICACION EN LA TABLE impuestos PAGINA: ajax/impuestos.php
     try
     {
        $idImpuestos=$_POST['idImpuestos'];
        $numeroFila = $_POST['NumeroFilaSeleccionada'];
        $txtPorcentaje = $_POST['txtPorcentaje'.$numeroFila];
        $txtEstado = $_POST['txtEstado'.$numeroFila];
          $txtCodigo = $_POST['txtCodigo'.$numeroFila];
        $cmbCuentaContable = $_POST['cmbCuentaContableI'];
                       
        if($txtPorcentaje != "" & $txtEstado !="" ){
              $sqlm = "update impuestos set iva='".$txtPorcentaje."', estado='".$txtEstado."',  id_empresa='".$sesion_id_empresa."', id_plan_cuenta='".$cmbCuentaContable."',  codigo='".$txtCodigo."' where id_iva='".$idImpuestos."'; ";
              $respm = mysql_query($sqlm);
              if($respm){
                  ?> <div class='transparent_ajax_correcto'><p>Registro Modificado correctamente.</p></div> <?php
                  }
             else{
                 ?> <div class='transparent_ajax_error'><p>Error al guarda los datos: problema con la consulta <?php echo "\n".mysql_error(); ?></p></div> <?php
                 }
      }
      else{
          ?> <div class="transparent_ajax_error"><p>Fallo en el envio del Formulario: No hay datos...</p></div> <?php
      }


    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }
   }

   if($accion == '3'){
       // ELIMINA IMPUESTOS
     try
     {  
          $idIva = $_POST['idIva'];
          $sqD = "delete from impuestos where id_iva='".$idIva."';";
                if(!mysql_query($sqD))
                        {echo "<div class='transparent_ajax_error'><p>Ocurrio un error al eliminar: \n".mysql_error()."</p></div>";}
                else { echo "<div class='transparent_notice'><p>El registro ha sido Eliminado.</p></div>"; }

     }catch (Exception $e) {
        // Error en algun momento.
           ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
        }

     }

     if($accion == '4'){
         // saca el porcentaje del iva actual o activo
        try
        {
            $id_iva = 0;
            $iva = 0;
            $sqliva = "SELECT * FROM impuestos WHERE estado='Activo' and id_empresa='".$sesion_id_empresa."';";
            $result = mysql_query($sqliva);
            while ($row = mysql_fetch_array($result)){
                $id_iva = $row["id_iva"];
                $iva = $row["iva"];
            }
            if($result){
                  echo $id_iva."*".$iva;
            }else{
                echo "Error: problema con la consulta.";
            }


        }catch (Exception $e) {
        // Error en algun momento.
           ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
        }

     }

?>